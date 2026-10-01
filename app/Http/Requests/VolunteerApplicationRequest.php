<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Support\SpamProtection;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class VolunteerApplicationRequest extends FormRequest
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
            'phone' => ['required', 'string', 'max:50'],
            'member_group_id' => ['nullable', 'exists:member_groups,id'],
            'skills' => ['nullable', 'string', 'max:1000'],
            'availability' => ['nullable', 'string', 'max:500'],
            'message' => ['nullable', 'string', 'max:5000'],
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
            'name.required' => 'Please tell us your full name.',
            'email.required' => 'An email address is required so our volunteer coordinator can reach you.',
            'email.email' => 'Please provide a valid email format (e.g. name@example.com).',
            'phone.required' => 'Please provide a contact phone number for rapid coordination.',
            'member_group_id.exists' => 'The selected community member group is not recognized.',
            '_hp_website.max' => 'Spam submission detected.',
        ];
    }
}
