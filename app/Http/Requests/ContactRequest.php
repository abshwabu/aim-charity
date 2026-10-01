<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Support\SpamProtection;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        SpamProtection::verify($this);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'message' => ['required', 'string', 'max:5000'],
            '_hp_website' => ['nullable', 'max:0'],
            '_form_time' => ['nullable', 'string'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Please provide your full name so our team knows who to address.',
            'email.required' => 'Please provide a valid email address so our coordination team can reply to you.',
            'email.email' => 'Please enter a valid email format (e.g. name@example.com).',
            'message.required' => 'Please write a brief message or question for our team.',
            '_hp_website.max' => 'Spam submission detected.',
        ];
    }
}
