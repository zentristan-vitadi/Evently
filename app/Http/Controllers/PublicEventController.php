<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicEventController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::orderBy('name')->get();

        $query = Event::with(['category', 'organizer'])
            ->withCount(['registrations as approved_registrations_count' => function ($q) {
                $q->where('status', 'approved');
            }])
            ->filter($request->only(['search', 'category_id', 'status']));

        // If no explicit status filter requested, default to showing active events or all except draft for guests
        if (! $request->filled('status')) {
            $query->whereIn('status', ['upcoming', 'ongoing', 'completed']);
        }

        $events = $query->orderByRaw("
            CASE 
                WHEN status = 'upcoming' THEN 1 
                WHEN status = 'ongoing' THEN 2 
                WHEN status = 'completed' THEN 3 
                ELSE 4 
            END
        ")->orderBy('start_date', 'asc')->paginate(9)->withQueryString();

        return view('events.index', compact('events', 'categories'));
    }

    public function show(Event $event): View
    {
        $event->load(['category', 'organizer'])
            ->loadCount(['registrations as approved_registrations_count' => function ($q) {
                $q->where('status', 'approved');
            }]);

        $userRegistration = null;
        if (auth()->check()) {
            $userRegistration = $event->registrations()
                ->where('user_id', auth()->id())
                ->first();
        }

        return view('events.show', compact('event', 'userRegistration'));
    }
}
