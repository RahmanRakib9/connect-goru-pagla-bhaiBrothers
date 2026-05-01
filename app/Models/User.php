<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * The #[Fillable] attribute lists the columns that can be mass-assigned.
 * "Mass assignment" means setting multiple fields at once via User::create([...])
 * or $user->fill([...]). Only columns listed here are accepted; all others are
 * silently ignored, which prevents attackers from injecting unexpected fields
 * (e.g. 'is_admin') through a form submission.
 */
#[Fillable(['name', 'email', 'password', 'is_admin'])]

/**
 * The #[Hidden] attribute lists columns that are NEVER included when the model
 * is converted to an array or JSON (e.g. when returned from an API endpoint).
 * This ensures the hashed password and the session remember-token are never
 * accidentally exposed in a response.
 */
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Tell Laravel how to cast raw database values to PHP types.
     *
     * - email_verified_at is stored as a timestamp string but returned as a Carbon date object.
     * - password is automatically hashed whenever it is set on the model.
     * - is_admin is stored as 0/1 in the database but read as a true/false boolean.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }
}
