<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $event->title }}
            </h2>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.events.edit', $event) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Edit') }}
                </a>
                <form method="POST" action="{{ route('admin.events.destroy', $event) }}" onsubmit="return confirm('{{ __('Delete this event?') }}');">
                    @csrf
                    @method('DELETE')
                    <x-danger-button type="submit">{{ __('Delete') }}</x-danger-button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">{{ __('Slug') }}</p>
                        <p class="mt-1 font-mono text-sm">{{ $event->slug }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">{{ __('Location') }}</p>
                        <p class="mt-1">{{ $event->location }}</p>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">{{ __('Starts at') }}</p>
                            <p class="mt-1">{{ $event->starts_at->timezone(config('app.timezone'))->format('Y-m-d H:i') }}</p>
                        </div>
                        @if ($event->ends_at)
                            <div>
                                <p class="text-sm font-medium text-gray-500">{{ __('Ends at') }}</p>
                                <p class="mt-1">{{ $event->ends_at->timezone(config('app.timezone'))->format('Y-m-d H:i') }}</p>
                            </div>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">{{ __('Published') }}</p>
                        <p class="mt-1">{{ $event->is_published ? __('Yes') : __('No') }}</p>
                    </div>
                    @if ($event->description)
                        <div>
                            <p class="text-sm font-medium text-gray-500">{{ __('Details') }}</p>
                            <div class="mt-1 text-sm text-gray-800 whitespace-pre-wrap">{{ $event->description }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
