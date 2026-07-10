<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Installation' }} - {{ config('app.name', 'Playora') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 dark:bg-gray-900 antialiased">
    <div class="min-h-screen flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-2xl">
            <!-- Logo -->
            <div class="flex justify-center mb-8">
                <div class="flex items-center">
                    <x-app-logo-icon class="w-12 h-12" />
                </div>
            </div>

            <!-- Steps indicator -->
            @if(isset($currentStep))
            <div class="mb-8">
                <div class="flex items-center justify-center">
                    @php
                        $steps = [
                            ['name' => 'Welcome', 'route' => 'install.welcome'],
                            ['name' => 'Requirements', 'route' => 'install.requirements'],
                            ['name' => 'Permissions', 'route' => 'install.permissions'],
                            ['name' => 'Site Name', 'route' => 'install.site-name'],
                            ['name' => 'Database', 'route' => 'install.database'],
                            ['name' => 'License', 'route' => 'install.license'],
                            ['name' => 'SMTP', 'route' => 'install.smtp'],
                            ['name' => 'Admin', 'route' => 'install.admin'],
                            ['name' => 'Complete', 'route' => 'install.complete'],
                        ];
                    @endphp

                    @foreach($steps as $index => $step)
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full {{ $currentStep > $index + 1 ? 'bg-brand-500 text-white' : ($currentStep == $index + 1 ? 'bg-brand-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400') }}">
                                @if($currentStep > $index + 1)
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                @else
                                    {{ $index + 1 }}
                                @endif
                            </div>
                            @if($index < count($steps) - 1)
                                <div class="w-8 h-0.5 {{ $currentStep > $index + 1 ? 'bg-brand-500' : 'bg-gray-200 dark:bg-gray-700' }}"></div>
                            @endif
                        </div>
                    @endforeach
                </div>
                <div class="mt-2 text-center text-sm text-gray-500 dark:text-gray-400">
                    Step {{ $currentStep }} of {{ count($steps) }}: {{ $steps[$currentStep - 1]['name'] }}
                </div>
            </div>
            @endif

            <!-- Card -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
                {{ $slot }}
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.js"></script>
</body>
</html>
