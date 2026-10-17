<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') — Negah Admin</title>
    <meta name="robots" content="noindex, nofollow">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
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

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.9/dist/cdn.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="min-h-full bg-ink-100/70 font-sans text-ink-900 antialiased">

    {{-- Top bar --}}
    <header class="sticky top-0 z-40 border-b border-ink-200 bg-ink-950 text-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
            <div class="flex items-center gap-8">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/10 text-sm font-bold">N</span>
                    <span>
                        <span class="block text-sm font-bold leading-tight">Negah</span>
                        <span class="block text-[10px] font-medium uppercase tracking-[0.18em] text-ink-400">Admin Panel</span>
                    </span>
                </a>

                <nav class="hidden items-center gap-1 text-sm md:flex">
                    <a href="{{ route('admin.dashboard') }}"
                       class="rounded-lg px-3.5 py-2 font-medium transition {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 text-white' : 'text-ink-300 hover:bg-white/5 hover:text-white' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('admin.events.index') }}"
                       class="rounded-lg px-3.5 py-2 font-medium transition {{ request()->routeIs('admin.events.*') ? 'bg-white/10 text-white' : 'text-ink-300 hover:bg-white/5 hover:text-white' }}">
                        Submissions
                    </a>
                    <a href="{{ route('admin.events.index', ['status' => 'pending_review']) }}"
                       class="rounded-lg px-3.5 py-2 font-medium text-amber-300 transition hover:bg-white/5">
                        Pending queue
                    </a>
                </nav>
            </div>

            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" target="_blank" class="hidden text-sm text-ink-300 hover:text-white sm:block">View site ↗</a>
                <div class="flex items-center gap-2.5">
                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-brand-600 text-xs font-bold">
                        {{ strtoupper(substr(session('admin_user', 'A'), 0, 1)) }}
                    </span>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="text-sm font-medium text-ink-300 transition hover:text-white">Sign out</button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    {{-- Flash messages --}}
    <div class="mx-auto max-w-7xl px-4 pt-6 sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-800">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-semibold text-red-800">
                {{ session('error') }}
            </div>
        @endif
    </div>

    <main class="mx-auto max-w-7xl px-4 pb-16 sm:px-6 lg:px-8">
        @yield('content')
    </main>

</body>
</html>
