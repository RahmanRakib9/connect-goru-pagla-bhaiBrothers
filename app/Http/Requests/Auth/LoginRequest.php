<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Anyone is allowed to attempt a login — authorization happens inside authenticate().
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Basic rules: both email and password are required strings.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to log the user in.
     *
     * This method is called from AuthenticatedSessionController after the
     * basic field validation above has already passed.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        // Step 1: If this email+IP has failed too many times recently, block the attempt
        // before we even check the database. This prevents brute-force attacks.
        $this->abortIfTooManyLoginAttempts();

        // Step 2: Try to log in using the submitted email and password.
        // The second argument (true/false) controls the "remember me" cookie.
        $loginSucceeded = Auth::attempt(
            $this->only('email', 'password'),
            $this->boolean('remember')
        );

        if (! $loginSucceeded) {
            // Record this failed attempt so we can enforce the rate limit above
            RateLimiter::hit($this->buildLoginRateLimitKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // Step 3: Credentials are correct, but this is an admin-only panel.
        // If the user is not an admin, log them back out and show an error.
        if (! Auth::user()->is_admin) {
            Auth::guard('web')->logout();

            // Count this as a failed attempt too, to deter enumeration
            RateLimiter::hit($this->buildLoginRateLimitKey());

            throw ValidationException::withMessages([
                'email' => __('These credentials do not grant access to the admin panel.'),
            ]);
        }

        // Step 4: Login was fully successful — clear the failed-attempt counter
        // so the user is not blocked on their next normal login.
        RateLimiter::clear($this->buildLoginRateLimitKey());
    }

    /**
     * If the user has sent too many failed login attempts recently, throw an
     * error telling them how many seconds they must wait before trying again.
     *
     * The limit is 5 attempts per unique email + IP address combination.
     *
     * @throws ValidationException
     */
    public function abortIfTooManyLoginAttempts(): void
    {
        $rateLimitKey = $this->buildLoginRateLimitKey();

        // RateLimiter::tooManyAttempts() returns true when the limit has been exceeded
        if (! RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            return; // Still within the allowed limit — proceed normally
        }

        // Fire a Lockout event so the application can log or react to it
        event(new Lockout($this));

        // Tell the user exactly how long they have to wait
        $secondsUntilUnlocked = RateLimiter::availableIn($rateLimitKey);

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $secondsUntilUnlocked,
                'minutes' => ceil($secondsUntilUnlocked / 60),
            ]),
        ]);
    }

    /**
     * Build a unique key used to track login attempts for rate limiting.
     *
     * The key combines the submitted email address and the visitor's IP address
     * so that different IPs trying the same email are tracked separately.
     *
     * Example result: "admin@example.com|127.0.0.1"
     */
    public function buildLoginRateLimitKey(): string
    {
        // Str::transliterate converts accented characters to plain ASCII
        // so that "héllo@example.com" and "hello@example.com" map to the same key
        return Str::transliterate(Str::lower($this->string('email')) . '|' . $this->ip());
    }
}
