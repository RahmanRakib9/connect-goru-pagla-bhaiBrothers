<img
    src="{{ asset('images/gorur-logo.png') }}"
    alt="{{ config('app.name') }}"
    width="36"
    height="36"
    loading="lazy"
    decoding="async"
    {{ $attributes->merge([
        'class' => 'block h-9 w-9 shrink-0 rounded-full object-cover object-[center_32%] shadow ring-2 ring-amber-100/90',
    ]) }}
/>
