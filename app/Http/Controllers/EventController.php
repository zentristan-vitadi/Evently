<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Category;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $categories = Category::orderBy('name')->get();

        $query = Event::with(['category', 'organizer'])
            ->withCount([
                'registrations',
                'registrations as approved_registrations_count' => function ($q) {
                    $q->where('status', 'approved');
                },
                'registrations as pending_registrations_count' => function ($q) {
                    $q->where('status', 'pending');
                },
            ])
            ->filter($request->only(['search', 'category_id', 'status']));

        if ($user->isPanitia()) {
            $query->where('organizer_id', $user->id);
        } elseif ($user->isAdmin() && $request->filled('organizer_id')) {
            $query->where('organizer_id', $request->organizer_id);
        }

        $events = $query->latest()->paginate(10)->withQueryString();
        $organizers = $user->isAdmin() ? User::whereIn('role', ['admin', 'panitia'])->orderBy('name')->get() : collect();

        return view('events.manage', compact('events', 'categories', 'organizers'));
    }

    public function create(Request $request): View
    {
        $categories = Category::orderBy('name')->get();
        $organizers = $request->user()->isAdmin()
            ? User::whereIn('role', ['admin', 'panitia'])->orderBy('name')->get()
            : collect();

        return view('events.create', compact('categories', 'organizers'));
    }

    public function store(StoreEventRequest $request): RedirectResponse
    {
        $data = $request->validated();
        if ($request->user()->isPanitia() || empty($data['organizer_id'])) {
            $data['organizer_id'] = $request->user()->id;
        }

        Event::create($data);

        return redirect()->route('manage.events.index')
            ->with('success', 'Event berhasil dibuat.');
    }

    public function edit(Request $request, Event $event): View
    {
        $this->authorizeEventAccess($request->user(), $event);

        $categories = Category::orderBy('name')->get();
        $organizers = $request->user()->isAdmin()
            ? User::whereIn('role', ['admin', 'panitia'])->orderBy('name')->get()
            : collect();

        return view('events.edit', compact('event', 'categories', 'organizers'));
    }

    public function update(UpdateEventRequest $request, Event $event): RedirectResponse
    {
        $this->authorizeEventAccess($request->user(), $event);

        $data = $request->validated();
        if ($request->user()->isPanitia()) {
            $data['organizer_id'] = $event->organizer_id;
        }

        $event->update($data);

        return redirect()->route('manage.events.index')
            ->with('success', 'Event berhasil diperbarui.');
    }

    public function destroy(Request $request, Event $event): RedirectResponse
    {
        $this->authorizeEventAccess($request->user(), $event);

        $event->delete();

        return redirect()->route('manage.events.index')
            ->with('success', 'Event berhasil dihapus.');
    }

    protected function authorizeEventAccess(User $user, Event $event): void
    {
        if ($user->isAdmin()) {
            return;
        }

        if ($user->isPanitia() && $event->organizer_id === $user->id) {
            return;
        }

        abort(403, 'Anda tidak memiliki hak akses untuk mengelola event ini.');
    }
}
