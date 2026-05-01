<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * This middleware protects admin-only routes.
 *
 * It runs on every request to a route that has the 'admin' middleware applied.
 * If the logged-in user does not have the is_admin flag set to true,
 * the request is immediately stopped with a 403 Forbidden error.
 *
 * It is registered as the 'admin' alias in bootstrap/app.php, which is why
 * you can use ->middleware('admin') in routes/web.php.
 */
class EnsureUserIsAdmin
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If the logged-in user is not an admin, stop the request with 403 Forbidden.
        // The ?-> (nullsafe operator) handles the edge case where no user is logged in at all.
        if (! $request->user()?->is_admin) {
            abort(403);
        }

        // The user is an admin — let the request continue to the controller
        return $next($request);
    }
}
