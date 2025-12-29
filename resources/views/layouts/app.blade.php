<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Dynamic page title --}}
    <title>{{ $title ?? 'Bastion' }}</title>

    {{-- Example meta tags (can be dynamic too) --}}
    <meta name="description" content="{{ $metaDescription ?? "Your trusted online flower boutique" }}">
    <meta name="keywords" content="{{ $metaKeywords ?? 'flowers, bouquets, Tian Hua' }}">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&display=swap" rel="stylesheet">

    <script>
        const csrf = "{{ csrf_token() }}";
    </script>


    @vite('resources/css/app.css')
</head>
<body class="font-sans bg-[#0b1d2a] text-gray-800">

    @livewire('navigation-menu')

        {{-- 🔹 Jetstream Navigation Bar
    @auth
        @include('layouts.navigation')
    @endauth --}}

    {{-- Page content --}}
    <main class="min-h-screen py-6 bg-[#0b1d2a]">
        {{ $slot }}
    </main>

    {{-- Common footer --}}
    <footer class="text-center py-4 text-gray-500 border-t border-[#122c3f] bg-[#0b1d2a]">
        &copy; {{ date('Y') }} Bastion. All rights reserved.
    </footer>

    {{-- @vite('resources/js/app.js') --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</body>
</html>
