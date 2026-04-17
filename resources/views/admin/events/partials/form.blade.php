@php
    /** @var \App\Models\Event|null $event */
    $event = $event ?? null;
@endphp

<div class="space-y-6">
    <div>
        <x-input-label for="title" :value="__('Title')" />
        <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $event?->title)" required autofocus />
        <x-input-error :messages="$errors->get('title')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="description" :value="__('Details')" />
        <textarea id="description" name="description" rows="6" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $event?->description) }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="location" :value="__('Location')" />
        <x-text-input id="location" class="block mt-1 w-full" type="text" name="location" :value="old('location', $event?->location)" required />
        <x-input-error :messages="$errors->get('location')" class="mt-2" />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <x-input-label for="starts_at" :value="__('Starts at')" />
            <x-text-input id="starts_at" class="block mt-1 w-full" type="datetime-local" name="starts_at" :value="old('starts_at', $event?->starts_at?->format('Y-m-d\TH:i'))" required />
            <x-input-error :messages="$errors->get('starts_at')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="ends_at" :value="__('Ends at (optional)')" />
            <x-text-input id="ends_at" class="block mt-1 w-full" type="datetime-local" name="ends_at" :value="old('ends_at', $event?->ends_at?->format('Y-m-d\TH:i'))" />
            <x-input-error :messages="$errors->get('ends_at')" class="mt-2" />
        </div>
    </div>

    <div class="flex items-center">
        <input id="is_published" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="is_published" value="1" @checked(old('is_published', $event?->is_published ?? false)) />
        <x-input-label for="is_published" class="ms-2" :value="__('Published (visible when the public site launches)')" />
    </div>
    <x-input-error :messages="$errors->get('is_published')" class="mt-2" />
</div>
