<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRegistrationRequest;
use App\Http\Requests\UpdateRegistrationStatusRequest;
use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    public function create(Request $request, Event $event): View|RedirectResponse
    {
        $user = $request->user();

        $event->load(['category', 'organizer'])
            ->loadCount(['registrations as approved_registrations_count' => function ($q) {
                $q->where('status', 'approved');
            }]);

        if ($user->registrations()->where('event_id', $event->id)->exists()) {
            return redirect()->route('events.show', $event)
                ->with('error', 'Anda sudah terdaftar pada event ini.');
        }

        if (! $event->canAcceptRegistrations()) {
            return redirect()->route('events.show', $event)
                ->with('error', 'Event ini tidak membuka pendaftaran atau kuota sudah penuh.');
        }

        return view('events.register', compact('event', 'user'));
    }

    public function store(StoreRegistrationRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        $data['status'] = 'pending';
        $data['registered_at'] = now();

        Registration::create($data);

        return redirect()->route('my.registrations')
            ->with('success', 'Pendaftaran berhasil! Detail tiket telah dikirim ke email ' . $data['email'] . '.')
            ->with('email_sent_to', $data['email']);
    }

    public function myRegistrations(Request $request): View
    {
        $user = $request->user();

        $registrations = Registration::with(['event.category', 'event.organizer'])
            ->where('user_id', $user->id)
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->search, function ($query, $search) {
                $query->whereHas('event', function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%");
                });
            })
            ->latest('registered_at')
            ->paginate(10)
            ->withQueryString();

        return view('registrations.my', compact('registrations'));
    }

    public function cancel(Request $request, Registration $registration): RedirectResponse
    {
        if ($registration->user_id !== $request->user()->id) {
            abort(403, 'Akses ditolak.');
        }

        if ($registration->status !== 'pending') {
            return back()->with('error', 'Hanya pendaftaran dengan status pending yang dapat dibatalkan.');
        }

        $registration->delete();

        return redirect()->route('my.registrations')
            ->with('success', 'Pendaftaran berhasil dibatalkan.');
    }

    public function manage(Request $request, ?Event $event = null): View
    {
        $user = $request->user();

        $eventsQuery = Event::query();
        if ($user->isPanitia()) {
            $eventsQuery->where('organizer_id', $user->id);
        }
        $events = $eventsQuery->orderBy('title')->get();

        $query = Registration::with(['user', 'event.category', 'event.organizer']);

        if ($event && $event->exists) {
            $this->authorizeEventRegistrationAccess($user, $event);
            $query->where('event_id', $event->id);
        } elseif ($user->isPanitia()) {
            $query->whereHas('event', function ($q) use ($user) {
                $q->where('organizer_id', $user->id);
            });
        }

        if ($request->filled('event_id')) {
            $selectedEventId = $request->input('event_id');
            if ($user->isPanitia()) {
                $owns = $events->contains('id', $selectedEventId);
                if (! $owns) {
                    abort(403, 'Akses ditolak.');
                }
            }
            $query->where('event_id', $selectedEventId);
        }

        $registrations = $query->filter($request->only(['search', 'status']))
            ->latest('registered_at')
            ->paginate(15)
            ->withQueryString();

        $selectedEvent = $event && $event->exists ? $event : ($request->filled('event_id') ? Event::find($request->event_id) : null);

        return view('registrations.manage', compact('registrations', 'events', 'selectedEvent'));
    }

    public function updateStatus(UpdateRegistrationStatusRequest $request, Registration $registration): RedirectResponse
    {
        $user = $request->user();
        $this->authorizeEventRegistrationAccess($user, $registration->event);

        $newStatus = $request->validated()['status'];

        // If changing to approved, verify capacity
        if ($newStatus === 'approved' && $registration->status !== 'approved') {
            $event = $registration->event;
            $approvedCount = $event->approvedRegistrations()->count();

            if ($approvedCount >= $event->capacity) {
                return back()->with('error', 'Kapasitas event sudah penuh (' . $event->capacity . ' peserta). Tidak dapat menyetujui pendaftaran baru.');
            }
        }

        $registration->update(['status' => $newStatus]);

        return back()->with('success', 'Status pendaftaran peserta berhasil diperbarui menjadi ' . ucfirst($newStatus) . '.');
    }

    protected function authorizeEventRegistrationAccess(User $user, Event $event): void
    {
        if ($user->isAdmin()) {
            return;
        }

        if ($user->isPanitia() && $event->organizer_id === $user->id) {
            return;
        }

        abort(403, 'Anda tidak memiliki hak akses untuk mengelola pendaftaran event ini.');
    }
}
