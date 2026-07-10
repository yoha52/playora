<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicon.png') }}">
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="author" content="Devsbeta">
<meta name="creator" content="Faizan Amin">

<title>{{ $title ?? 'Playora' }}</title>

<script>
    // Apply saved theme immediately to prevent flash
    if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
</script>

@vite(['resources/css/app.css', 'resources/js/app.js'])
@livewireStyles
@stack('styles')
