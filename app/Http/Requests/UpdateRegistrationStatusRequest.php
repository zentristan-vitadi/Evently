<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRegistrationStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && ($this->user()->isAdmin() || $this->user()->isPanitia());
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'in:pending,approved,rejected'],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Status pendaftaran wajib dipilih.',
            'status.in' => 'Status pendaftaran harus pending, approved, atau rejected.',
        ];
    }
}
