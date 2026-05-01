<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\RedirectResponse;

class EventResponseController extends Controller
{
    /**
     * Handle a visitor clicking the "Going" button on an event card.
     *
     * We store the visitor's choice in the session so:
     *   - The button stays highlighted if they refresh the page
     *   - We don't count the same visitor twice for the same event
     *
     * The session key looks like: "event_5_response"
     * The session value is either "going" or "interested"
     */
    public function markAsGoing(Event $event): RedirectResponse
    {
        $sessionKey = 'event_' . $event->id . '_response';

        // Only increment if this visitor has not already responded to this event
        if (! session()->has($sessionKey)) {
            $event->increment('going_count');
            session([$sessionKey => 'going']);
        }

        return redirect('/');
    }

    /**
     * Handle a visitor clicking the "Interested" button on an event card.
     *
     * Same session-based deduplication as markAsGoing() above.
     */
    public function markAsInterested(Event $event): RedirectResponse
    {
        $sessionKey = 'event_' . $event->id . '_response';

        // Only increment if this visitor has not already responded to this event
        if (! session()->has($sessionKey)) {
            $event->increment('interested_count');
            session([$sessionKey => 'interested']);
        }

        return redirect('/');
    }
}
