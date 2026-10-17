<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Negah Brochure Platform')</title>
    <meta name="description" content="@yield('meta_description', 'Negah — event brochure and digital invitation platform. Create beautiful digital brochures, RSVP forms and graphic invitation cards for your events.')">

    {{-- Tailwind CSS (CDN build — no node build step required on IIS) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        display: ['"Playfair Display"', 'ui-serif', 'Georgia', 'serif'],
                    },
                    colors: {
                        ink: {
                            50: '#f6f7f9', 100: '#eceef2', 200: '#d5dae2', 300: '#b0bac9',
                            400: '#8594ab', 500: '#667791', 600: '#515f77', 700: '#434e61',
                            800: '#3a4352', 900: '#343a46', 950: '#22262f',
                        },
                        brand: {
                            50: '#eef7ff', 100: '#d9edff', 200: '#bce0ff', 300: '#8ecdff',
                            400: '#59b0ff', 500: '#338eff', 600: '#1b6ef5', 700: '#1457e1',
                            800: '#1747b6', 900: '#193f8f', 950: '#142857',
                        },
                    },
                },
            },
        };
    </script>

    {{-- Alpine.js for interactive live previews --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.9/dist/cdn.min.js"></script>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">

    @stack('head')
</head>
<body class="min-h-screen bg-ink-50 font-sans text-ink-900 antialiased flex flex-col">

    {{-- Navigation --}}
    <header class="sticky top-0 z-40 border-b border-ink-200/70 bg-white/80 backdrop-blur">
        <nav class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="group flex items-center gap-2.5">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-brand-600 to-brand-800 text-lg font-bold text-white shadow-sm shadow-brand-600/30 transition group-hover:shadow-brand-600/50">N</span>
                <span class="leading-tight">
                    <span class="block text-[15px] font-bold tracking-tight text-ink-950">Negah</span>
                    <span class="block text-[10px] font-medium uppercase tracking-[0.18em] text-ink-400">Brochure Platform</span>
                </span>
            </a>

            <div class="hidden items-center gap-8 text-sm font-medium text-ink-600 md:flex">
                <a href="{{ route('home') }}#how-it-works" class="transition hover:text-brand-700">How it works</a>
                <a href="{{ route('home') }}#outputs" class="transition hover:text-brand-700">What you get</a>
                <a href="{{ route('events.create') }}" class="transition hover:text-brand-700">Submit event</a>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.login') }}" class="hidden text-sm font-medium text-ink-500 transition hover:text-ink-800 sm:block">Admin</a>
                <a href="{{ route('events.create') }}" class="rounded-full bg-brand-600 px-5 py-2 text-sm font-semibold text-white shadow-sm shadow-brand-600/30 transition hover:bg-brand-700">
                    Create brochure
                </a>
            </div>
        </nav>
    </header>

    {{-- Flash messages --}}
    @if (session('error'))
        <div class="border-b border-red-200 bg-red-50 px-4 py-3 text-center text-sm font-medium text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <main class="flex-1">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="border-t border-ink-200/70 bg-white">
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <div class="flex flex-col items-start justify-between gap-8 md:flex-row md:items-center">
                <div>
                    <div class="flex items-center gap-2.5">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-brand-600 to-brand-800 text-sm font-bold text-white">N</span>
                        <span class="text-sm font-bold text-ink-900">Negah Brochure Platform</span>
                    </div>
                    <p class="mt-2 max-w-md text-sm text-ink-500">
                        Digital event brochures, RSVP pages and graphic invitation cards —
                        self-service submission with concierge review and activation.
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-x-8 gap-y-2 text-sm text-ink-500">
                    <a href="{{ route('events.create') }}" class="font-medium text-ink-700 hover:text-brand-700">Submit an event</a>
                    <a href="{{ route('admin.login') }}" class="hover:text-brand-700">Admin panel</a>
                    <span class="text-ink-400">negahm.ir/B/&lt;your-slug&gt;</span>
                </div>
            </div>
            <div class="mt-8 border-t border-ink-100 pt-6 text-xs text-ink-400">
                &copy; {{ date('Y') }} Negah. All rights reserved.
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
