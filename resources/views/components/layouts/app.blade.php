<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
</head>
<body class="bg-white dark:bg-zinc-800 min-h-screen">
    <x-top-nav />

    <div class="flex pt-16 min-h-screen bg-gray-50 dark:bg-gray-900">
        <x-sidebar />
        <div class="relative w-full min-h-[calc(100vh-4rem)] flex flex-col bg-gray-50 lg:ml-64 dark:bg-gray-900">
            <main class="px-4 pt-6 flex-grow">
                {{ $slot }}
            </main>

            <x-footer />
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.js"></script>
    <script src="{{ route('assets.lang') }}"></script>
    @livewireScripts
    @stack('scripts')
</body>
</html>

