<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Livestock market events') }}
            </h2>
            <a href="{{ route('admin.events.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Create event') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($events->isEmpty())
                        <p>{{ __('No events yet. Create one to get started.') }}</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Title') }}</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Location') }}</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Starts') }}</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Published') }}</th>
                                        <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($events as $event)
                                        <tr>
                                            <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                                <a href="{{ route('admin.events.show', $event) }}" class="text-indigo-600 hover:text-indigo-900">{{ $event->title }}</a>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-600">{{ $event->location }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-600">{{ $event->starts_at->timezone(config('app.timezone'))->format('Y-m-d H:i') }}</td>
                                            <td class="px-4 py-3 text-sm">
                                                @if ($event->is_published)
                                                    <span class="text-green-700">{{ __('Yes') }}</span>
                                                @else
                                                    <span class="text-gray-500">{{ __('No') }}</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-sm text-right space-x-2 whitespace-nowrap">
                                                <a href="{{ route('admin.events.edit', $event) }}" class="text-indigo-600 hover:text-indigo-900">{{ __('Edit') }}</a>
                                                <form method="POST" action="{{ route('admin.events.destroy', $event) }}" class="inline" onsubmit="return confirm('{{ __('Delete this event?') }}');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">{{ __('Delete') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $events->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
