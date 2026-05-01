<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Show the public home page with a list of upcoming events.
     *
     * We only show events that:
     *   1. Have been published by an admin (is_published = true)
     *   2. Have not started yet (starts_at is in the future)
     *
     * Events are sorted by start date so the next event appears first.
     */
    public function showUpcomingEvents(): View
    {
        $upcomingEvents = Event::query()
            ->where('is_published', true)
            ->where('starts_at', '>', now())
            ->orderBy('starts_at')
            ->get();

        return view('home', compact('upcomingEvents'));
    }
}
