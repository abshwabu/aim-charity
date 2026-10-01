<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Support\SpamProtection;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class NewsletterSubscribeRequest extends FormRequest
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
            'email' => ['required', 'email', 'max:255'],
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
            'email.required' => 'Please enter your email address to subscribe to coalition updates.',
            'email.email' => 'Please enter a valid email format (e.g. name@example.com).',
            '_hp_website.max' => 'Spam submission detected.',
        ];
    }
}
