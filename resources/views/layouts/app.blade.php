<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'FestivaL Film Pendek Blitar')</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Tailwind CSS via CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        safelist: [
            'grid-cols-1',
            'sm:grid-cols-2', 
            'lg:grid-cols-4',
            'grid-cols-4',
        ],
        theme: {
            extend: {
                fontFamily: {
                    display: ['"Playfair Display"', 'serif'],
                    body:    ['"Plus Jakarta Sans"', 'sans-serif'],
                },
                colors: {
                    orange: {
                        50:  '#FFF7ED',
                        100: '#FFEDD5',
                        200: '#FED7AA',
                        400: '#FB923C',
                        500: '#F97316',
                        600: '#EA580C',
                        700: '#C2410C',
                        800: '#9A3412',
                    },
                    brand: {
                        blue:        '#1D4ED8',
                        'blue-dark': '#1E3A8A',
                        'blue-light':'#BFDBFE',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-display { font-family: 'Playfair Display', serif; }
        .vote-bar-fill { transition: width 0.6s ease; }
        @keyframes fadeIn { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:translateY(0); } }
        .animate-fade-in { animation: fadeIn 0.4s ease forwards; }
    </style>

    @stack('styles')
</head>
<body class="bg-white text-gray-900 min-h-screen flex flex-col">

    {{-- NAVBAR --}}
    @include('partials.navbar')

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
        <div id="flash-success"
             class="fixed bottom-6 right-6 z-50 bg-brand-blue-dark text-white px-6 py-4 rounded-xl shadow-2xl flex items-center gap-3 animate-fade-in">
            <span class="text-xl">✅</span>
            <span class="font-semibold text-sm">{{ session('success') }}</span>
        </div>
        <script>setTimeout(() => document.getElementById('flash-success')?.remove(), 4000)</script>
    @endif

    @if(session('error'))
        <div id="flash-error"
             class="fixed bottom-6 right-6 z-50 bg-red-600 text-white px-6 py-4 rounded-xl shadow-2xl flex items-center gap-3 animate-fade-in">
            <span class="text-xl">⚠️</span>
            <span class="font-semibold text-sm">{{ session('error') }}</span>
        </div>
        <script>setTimeout(() => document.getElementById('flash-error')?.remove(), 4000)</script>
    @endif

    {{-- KONTEN UTAMA --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    @include('partials.footer')

    @stack('scripts')
</body>
</html>
