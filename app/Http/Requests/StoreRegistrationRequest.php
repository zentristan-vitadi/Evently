<?php

namespace App\Http\Requests;

use App\Models\Event;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isPeserta() ?? false;
    }

    public function rules(): array
    {
        return [
            'event_id' => ['required', 'exists:events,id'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $eventId = $this->input('event_id');
            if (! $eventId) {
                return;
            }

            $event = Event::withCount(['registrations as approved_registrations_count' => function ($q) {
                $q->where('status', 'approved');
            }])->find($eventId);

            if (! $event) {
                return;
            }

            if (! in_array($event->status, ['upcoming', 'ongoing'], true)) {
                $validator->errors()->add('event_id', 'Event ini tidak membuka pendaftaran saat ini.');
            }

            if ($event->remaining_quota <= 0) {
                $validator->errors()->add('event_id', 'Kapasitas event sudah penuh.');
            }

            if ($this->user()?->registrations()->where('event_id', $eventId)->exists()) {
                $validator->errors()->add('event_id', 'Anda sudah terdaftar pada event ini.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'event_id.required' => 'Event wajib dipilih.',
            'event_id.exists' => 'Event tidak ditemukan.',
        ];
    }
}
