<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && ($this->user()->isAdmin() || $this->user()->isPanitia());
    }

    public function rules(): array
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['required', 'string'],
            'location' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'capacity' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'in:draft,upcoming,ongoing,completed,cancelled'],
        ];

        if ($this->user()?->isAdmin()) {
            $rules['organizer_id'] = ['required', 'exists:users,id'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul event wajib diisi.',
            'category_id.required' => 'Kategori event wajib dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'description.required' => 'Deskripsi event wajib diisi.',
            'location.required' => 'Lokasi event wajib diisi.',
            'start_date.required' => 'Waktu mulai event wajib diisi.',
            'end_date.required' => 'Waktu selesai event wajib diisi.',
            'end_date.after_or_equal' => 'Waktu selesai harus sama atau setelah waktu mulai.',
            'capacity.required' => 'Kapasitas event wajib diisi.',
            'capacity.min' => 'Kapasitas minimal 1 peserta.',
            'status.required' => 'Status event wajib dipilih.',
            'status.in' => 'Status event tidak valid.',
            'organizer_id.required' => 'Panitia penanggung jawab wajib dipilih.',
            'organizer_id.exists' => 'Panitia yang dipilih tidak valid.',
        ];
    }
}
