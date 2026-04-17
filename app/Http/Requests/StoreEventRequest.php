<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'location' => ['required', 'string', 'max:255'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['nullable', 'date', 'after:starts_at'],
            'is_published' => ['sometimes', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $merge = [
            'is_published' => $this->boolean('is_published'),
        ];
        if ($this->input('ends_at') === '' || $this->input('ends_at') === null) {
            $merge['ends_at'] = null;
        }
        $this->merge($merge);
    }
}
