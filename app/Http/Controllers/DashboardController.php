<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        }

        if ($user->isPanitia()) {
            return $this->panitiaDashboard($user);
        }

        return $this->pesertaDashboard($user);
    }

    protected function adminDashboard(): View
    {
        $stats = [
            'total_events' => Event::count(),
            'total_categories' => Category::count(),
            'total_users' => User::count(),
            'total_registrations' => Registration::count(),
            'pending_registrations' => Registration::where('status', 'pending')->count(),
            'approved_registrations' => Registration::where('status', 'approved')->count(),
            'users_admin' => User::where('role', 'admin')->count(),
            'users_panitia' => User::where('role', 'panitia')->count(),
            'users_peserta' => User::where('role', 'peserta')->count(),
        ];

        $recentEvents = Event::with(['category', 'organizer'])
            ->withCount(['registrations as approved_registrations_count' => fn ($q) => $q->where('status', 'approved')])
            ->latest()
            ->take(5)
            ->get();

        $recentRegistrations = Registration::with(['user', 'event'])
            ->latest('registered_at')
            ->take(5)
            ->get();

        return view('dashboard.admin', compact('stats', 'recentEvents', 'recentRegistrations'));
    }

    protected function panitiaDashboard(User $user): View
    {
        $myEventsCount = Event::where('organizer_id', $user->id)->count();

        $registrationsQuery = Registration::whereHas('event', function ($q) use ($user) {
            $q->where('organizer_id', $user->id);
        });

        $stats = [
            'total_events' => $myEventsCount,
            'total_registrations' => (clone $registrationsQuery)->count(),
            'pending_registrations' => (clone $registrationsQuery)->where('status', 'pending')->count(),
            'approved_registrations' => (clone $registrationsQuery)->where('status', 'approved')->count(),
            'rejected_registrations' => (clone $registrationsQuery)->where('status', 'rejected')->count(),
        ];

        $pendingApprovals = Registration::with(['user', 'event'])
            ->whereHas('event', function ($q) use ($user) {
                $q->where('organizer_id', $user->id);
            })
            ->where('status', 'pending')
            ->latest('registered_at')
            ->take(5)
            ->get();

        $myEvents = Event::with(['category'])
            ->withCount([
                'registrations',
                'registrations as approved_registrations_count' => fn ($q) => $q->where('status', 'approved'),
            ])
            ->where('organizer_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.panitia', compact('stats', 'pendingApprovals', 'myEvents'));
    }

    protected function pesertaDashboard(User $user): View
    {
        $stats = [
            'total_registrations' => Registration::where('user_id', $user->id)->count(),
            'approved' => Registration::where('user_id', $user->id)->where('status', 'approved')->count(),
            'pending' => Registration::where('user_id', $user->id)->where('status', 'pending')->count(),
            'rejected' => Registration::where('user_id', $user->id)->where('status', 'rejected')->count(),
        ];

        $myRegistrations = Registration::with(['event.category', 'event.organizer'])
            ->where('user_id', $user->id)
            ->latest('registered_at')
            ->take(5)
            ->get();

        $upcomingEvents = Event::with(['category', 'organizer'])
            ->withCount(['registrations as approved_registrations_count' => fn ($q) => $q->where('status', 'approved')])
            ->whereIn('status', ['upcoming', 'ongoing'])
            ->whereDoesntHave('registrations', fn ($q) => $q->where('user_id', $user->id))
            ->orderBy('start_date', 'asc')
            ->take(3)
            ->get();

        return view('dashboard.peserta', compact('stats', 'myRegistrations', 'upcomingEvents'));
    }
}
