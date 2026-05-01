<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EventController extends Controller
{
    /**
     * Show a paginated list of all events, sorted by start date (newest first).
     */
    public function index(): View
    {
        $events = Event::query()
            ->orderByDesc('starts_at')
            ->paginate(15);

        return view('admin.events.index', compact('events'));
    }

    /**
     * Show the blank form for creating a new event.
     */
    public function create(): View
    {
        return view('admin.events.create');
    }

    /**
     * Validate and save the new event submitted from the create form.
     * A unique URL slug is automatically generated from the event title.
     */
    public function store(StoreEventRequest $request): RedirectResponse
    {
        // $validated contains only the fields that passed the form rules
        $validated = $request->validated();

        // Automatically build a slug like "spring-market" from the event title
        $validated['slug'] = Event::generateUniqueSlugFromTitle($validated['title']);

        Event::create($validated);

        return redirect()
            ->route('admin.events.index')
            ->with('status', __('Event created.'));
    }

    /**
     * Show the details page for a single event.
     * Laravel automatically finds the Event by the ID in the URL (route model binding).
     */
    public function show(Event $event): View
    {
        return view('admin.events.show', compact('event'));
    }

    /**
     * Show the edit form pre-filled with the existing event data.
     */
    public function edit(Event $event): View
    {
        return view('admin.events.edit', compact('event'));
    }

    /**
     * Validate and save the changes submitted from the edit form.
     * If the title changed, regenerate the slug so it stays in sync.
     */
    public function update(UpdateEventRequest $request, Event $event): RedirectResponse
    {
        $validated = $request->validated();

        // Only regenerate the slug when the title actually changed
        // We pass the current event ID so the slug checker ignores this event's own row
        if ($validated['title'] !== $event->title) {
            $validated['slug'] = Event::generateUniqueSlugFromTitle($validated['title'], $event->id);
        }

        $event->update($validated);

        return redirect()
            ->route('admin.events.show', $event)
            ->with('status', __('Event updated.'));
    }

    /**
     * Delete the event from the database and redirect back to the list.
     */
    public function destroy(Event $event): RedirectResponse
    {
        $event->delete();

        return redirect()
            ->route('admin.events.index')
            ->with('status', __('Event deleted.'));
    }
}
