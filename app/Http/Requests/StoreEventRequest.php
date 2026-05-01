<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    /**
     * All logged-in admin users are allowed to create events.
     * The 'admin' middleware on the route already ensures only admins reach here.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Define which fields are required and what format they must be in.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'location'     => ['required', 'string', 'max:255'],
            'starts_at'    => ['required', 'date'],
            // ends_at is optional, but if provided it must be after the start time
            'ends_at'      => ['nullable', 'date', 'after:starts_at'],
            'is_published' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * Fix up the raw form data before validation runs.
     *
     * HTML forms have two quirks we need to handle:
     *
     * 1. CHECKBOXES: An unchecked checkbox sends nothing at all (not "false").
     *    We call $this->boolean('is_published') which returns false when the
     *    checkbox is absent, so the validator always receives a proper boolean.
     *
     * 2. EMPTY DATE FIELDS: An empty <input type="datetime-local"> sends an
     *    empty string (""). We convert that to null so the database stores NULL
     *    instead of an invalid date string.
     */
    protected function prepareForValidation(): void
    {
        $corrections = [
            // Always give the validator a real boolean, never a missing key
            'is_published' => $this->boolean('is_published'),
        ];

        // Treat a blank ends_at the same as "no end date"
        $endsAtValue = $this->input('ends_at');
        if ($endsAtValue === '' || $endsAtValue === null) {
            $corrections['ends_at'] = null;
        }

        $this->merge($corrections);
    }
}
