@extends('layouts.public')

@section('title', 'Upcoming Events — ' . config('app.name'))

@section('content')

    <h1 class="text-2xl font-bold text-gray-800 mb-2">Upcoming Events</h1>
    <p class="text-gray-500 mb-8">Browse the latest livestock market events happening near you.</p>

    {{-- Show a friendly message when there are no published upcoming events --}}
    @if ($upcomingEvents->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-10 text-center text-gray-500">
            No upcoming events at the moment. Check back soon!
        </div>
    @endif

    {{-- Loop through each upcoming event and render a card --}}
    <div class="grid grid-cols-1 gap-6">
        @foreach ($upcomingEvents as $event)

            {{--
                Figure out if this visitor has already responded to this event.
                The session key looks like "event_5_response" and holds "going" or "interested".
            --}}
            @php
                $sessionKey      = 'event_' . $event->id . '_response';
                $visitorResponse = session($sessionKey); // null | 'going' | 'interested'
            @endphp

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">

                {{-- Event title --}}
                <h2 class="text-xl font-semibold text-gray-800 mb-1">{{ $event->title }}</h2>

                {{-- Location and date row --}}
                <div class="flex flex-wrap gap-4 text-sm text-gray-500 mb-4">
                    <span>
                        📍 {{ $event->location }}
                    </span>
                    <span>
                        🗓️ {{ $event->starts_at->format('D, d M Y • g:i A') }}
                    </span>
                    @if ($event->ends_at)
                        <span>
                            — {{ $event->ends_at->format('g:i A') }}
                        </span>
                    @endif
                </div>

                {{-- Optional description (trimmed to 200 characters) --}}
                @if ($event->description)
                    <p class="text-gray-600 text-sm mb-5 leading-relaxed">
                        {{ Str::limit($event->description, 200) }}
                    </p>
                @endif

                {{-- Going and Interested buttons with their current counts --}}
                <div class="flex flex-wrap gap-3 items-center">

                    {{--
                        "Going" button.
                        - Submits a POST form to /events/{id}/going
                        - Highlighted in green if the visitor already clicked it
                        - Disabled if the visitor already chose "interested"
                    --}}
                    <form method="POST" action="/events/{{ $event->id }}/going">
                        @csrf
                        <button
                            type="submit"
                            @if ($visitorResponse !== null) disabled @endif
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium border transition
                                {{-- Green when this visitor picked "going", plain grey otherwise --}}
                                {{ $visitorResponse === 'going'
                                    ? 'bg-green-100 border-green-400 text-green-700 cursor-default'
                                    : 'bg-white border-gray-300 text-gray-700 hover:bg-green-50 hover:border-green-400 hover:text-green-700' }}
                                {{-- Muted when the visitor already clicked the other button --}}
                                {{ $visitorResponse === 'interested' ? 'opacity-50 cursor-not-allowed' : '' }}"
                        >
                            ✅ Going
                            <span class="font-semibold">{{ $event->going_count }}</span>
                        </button>
                    </form>

                    {{--
                        "Interested" button.
                        - Submits a POST form to /events/{id}/interested
                        - Highlighted in yellow if the visitor already clicked it
                        - Disabled if the visitor already chose "going"
                    --}}
                    <form method="POST" action="/events/{{ $event->id }}/interested">
                        @csrf
                        <button
                            type="submit"
                            @if ($visitorResponse !== null) disabled @endif
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium border transition
                                {{-- Yellow when this visitor picked "interested", plain grey otherwise --}}
                                {{ $visitorResponse === 'interested'
                                    ? 'bg-yellow-100 border-yellow-400 text-yellow-700 cursor-default'
                                    : 'bg-white border-gray-300 text-gray-700 hover:bg-yellow-50 hover:border-yellow-400 hover:text-yellow-700' }}
                                {{-- Muted when the visitor already clicked the other button --}}
                                {{ $visitorResponse === 'going' ? 'opacity-50 cursor-not-allowed' : '' }}"
                        >
                            ⭐ Interested
                            <span class="font-semibold">{{ $event->interested_count }}</span>
                        </button>
                    </form>

                    {{-- Show a small confirmation message once the visitor has responded --}}
                    @if ($visitorResponse !== null)
                        <span class="text-xs text-gray-400 italic">
                            You marked yourself as {{ $visitorResponse }}.
                        </span>
                    @endif

                </div>
            </div>

        @endforeach
    </div>

@endsection
