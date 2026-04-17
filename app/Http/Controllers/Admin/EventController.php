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
    public function index(): View
    {
        $events = Event::query()
            ->orderByDesc('starts_at')
            ->paginate(15);

        return view('admin.events.index', compact('events'));
    }

    public function create(): View
    {
        return view('admin.events.create');
    }

    public function store(StoreEventRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['slug'] = Event::uniqueSlugFromTitle($validated['title']);

        Event::create($validated);

        return redirect()
            ->route('admin.events.index')
            ->with('status', __('Event created.'));
    }

    public function show(Event $event): View
    {
        return view('admin.events.show', compact('event'));
    }

    public function edit(Event $event): View
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(UpdateEventRequest $request, Event $event): RedirectResponse
    {
        $validated = $request->validated();

        if ($validated['title'] !== $event->title) {
            $validated['slug'] = Event::uniqueSlugFromTitle($validated['title'], $event->id);
        }

        $event->update($validated);

        return redirect()
            ->route('admin.events.show', $event)
            ->with('status', __('Event updated.'));
    }

    public function destroy(Event $event): RedirectResponse
    {
        $event->delete();

        return redirect()
            ->route('admin.events.index')
            ->with('status', __('Event deleted.'));
    }
}
