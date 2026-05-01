<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Creates (or updates) the admin user when the app is seeded.
 *
 * The admin credentials are read from the .env file so they are never
 * hard-coded in version control. To use this seeder, add these lines to .env:
 *
 *   ADMIN_EMAIL=admin@example.com
 *   ADMIN_PASSWORD=your-secret-password
 *   ADMIN_NAME=Administrator   (optional, defaults to "Administrator")
 *
 * Then run: php artisan db:seed
 */
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Read the admin credentials from environment variables
        $adminEmail    = env('ADMIN_EMAIL');
        $adminPassword = env('ADMIN_PASSWORD');

        // If either value is missing or blank, skip this seeder silently.
        // This prevents errors when seeding a fresh environment that has not
        // configured admin credentials yet.
        $emailIsMissing    = ! is_string($adminEmail) || $adminEmail === '';
        $passwordIsMissing = ! is_string($adminPassword) || $adminPassword === '';

        if ($emailIsMissing || $passwordIsMissing) {
            return;
        }

        // updateOrCreate works like this:
        //   - Find a user with the given email address.
        //   - If found: update their name, password, and admin flag.
        //   - If not found: create a brand new user with all of these values.
        // This makes the seeder safe to run multiple times without creating duplicates.
        User::updateOrCreate(
            // The "find by" condition
            ['email' => $adminEmail],
            // The values to set (whether creating or updating)
            [
                'name'              => env('ADMIN_NAME', 'Administrator'),
                'password'          => Hash::make($adminPassword),
                'is_admin'          => true,
                'email_verified_at' => now(), // Mark the email as pre-verified for the admin
            ]
        );
    }
}
