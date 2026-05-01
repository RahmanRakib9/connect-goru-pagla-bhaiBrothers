<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title', config('app.name', 'Goru Pagla'))</title>

        <link rel="icon" type="image/png" href="{{ asset('images/gorur-logo.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/gorur-logo.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Tailwind CSS + Alpine.js (compiled by Vite) -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-900">

        <!-- Top navigation bar -->
        <header class="bg-white shadow-sm">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-center gap-3">
                <a href="/">
                    <x-application-logo class="!h-10 !w-10" />
                </a>
                <span class="text-xl font-semibold text-gray-800">
                    {{ config('app.name', 'Goru Pagla') }}
                </span>
            </div>
        </header>

        <!-- Page content injected by each view that extends this layout -->
        <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            @yield('content')
        </main>

    </body>
</html>
