<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * This factory creates fake User records for use in tests and local seeding.
 *
 * Usage examples:
 *   User::factory()->create()           — creates a regular (non-admin) user
 *   User::factory()->admin()->create()  — creates an admin user
 *   User::factory()->count(10)->create() — creates 10 users at once
 *
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * Stores the hashed test password so we only compute the bcrypt hash once
     * per test run, no matter how many users the factory creates.
     * Hashing is intentionally slow (for security), so caching it here keeps
     * the test suite fast when many users are created.
     */
    protected static ?string $cachedHashedPassword;

    /**
     * The default field values for a newly created fake user.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'              => fake()->name(),
            'email'             => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            // ??= means: if $cachedHashedPassword is null, hash 'password' and store it,
            // then return the stored value. On every subsequent call it reuses the cache.
            'password'          => static::$cachedHashedPassword ??= Hash::make('password'),
            'remember_token'    => Str::random(10),
            'is_admin'          => false,
        ];
    }

    /**
     * Return a version of this factory that creates users with no verified email.
     * Useful for testing email-verification flows.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Return a version of this factory that creates admin users.
     * Used in tests that need to act as an admin, e.g. User::factory()->admin()->create().
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_admin' => true,
        ]);
    }
}
