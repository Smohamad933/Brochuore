{{--
    Dynamic brochure shell.
    Renders one of the pre-built responsive templates based on
    $event->template_name, plus the RSVP section when applicable.

    Standalone (white-label) page: carries the EVENT's branding, not the
    platform chrome.
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $event->title }} — {{ $event->displayUrl() }}</title>
    <meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags($event->description), 160) }}">
    <meta name="theme-color" content="{{ $event->brand_color }}">

    {{-- Tailwind CSS (CDN build) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        display: ['"Playfair Display"', 'ui-serif', 'Georgia', 'serif'],
                    },
                },
            },
        };
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand: {{ $event->brand_color }};
            --brand-soft: {{ $event->brand_color }}1a;
            --brand-line: {{ $event->brand_color }}33;
            --secondary: {{ $event->secondary_color ?: $event->brand_color }};
        }
        html { scroll-behavior: smooth; }
        .prose-event p { margin-top: 1em; }
        .prose-event p:first-child { margin-top: 0; }
        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; }
        }
    </style>
</head>
<body class="font-sans antialiased">

    {{-- ============================================================== --}}
    {{-- PREVIEW / STATUS BANNERS (never visible on a live page)         --}}
    {{-- ============================================================== --}}
    @if ($previewMode)
        <div class="no-print sticky top-0 z-50 border-b border-amber-200 bg-amber-400/95 px-4 py-2.5 text-center text-sm font-semibold text-amber-950 backdrop-blur">
            <span class="inline-flex items-center gap-2">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                PREVIEW MODE — This brochure is awaiting concierge review &amp; activation.
                The public link is not live yet.
            </span>
        </div>
    @endif

    @if ($event->isExpired())
        <div class="no-print border-b border-ink-200 bg-ink-100 px-4 py-2.5 text-center text-sm font-semibold text-ink-600">
            This event has ended. The page is kept for reference.
        </div>
    @endif

    @if (session('rsvp_success'))
        <div class="no-print sticky top-0 z-50 border-b border-emerald-200 bg-emerald-500 px-4 py-2.5 text-center text-sm font-semibold text-white">
            {{ session('rsvp_success') }}
        </div>
    @endif

    {{-- ============================================================== --}}
    {{-- TEMPLATE RENDER                                                --}}
    {{-- ============================================================== --}}
    @includeIf('events.templates.'.$event->template_name, ['event' => $event, 'previewMode' => $previewMode])

    {{-- ============================================================== --}}
    {{-- RSVP SECTION (only for RSVP output type, when active)           --}}
    {{-- ============================================================== --}}
    @if ($event->output_type === \App\Models\Event::OUTPUT_RSVP && $event->isActive())
        @include('events.partials.rsvp-form', ['event' => $event])
    @endif

    {{-- ============================================================== --}}
    {{-- FOOTER                                                         --}}
    {{-- ============================================================== --}}
    <footer class="border-t border-ink-200/70 bg-white">
        <div class="mx-auto max-w-5xl px-4 py-8 text-center">
            <p class="text-sm text-ink-400">
                {{ $event->title }} &middot;
                <a href="{{ $event->publicUrl() }}" class="font-medium text-ink-500 hover:underline">{{ $event->displayUrl() }}</a>
            </p>
            <p class="mt-2 text-xs text-ink-400">
                Published with
                <a href="{{ route('home') }}" class="font-semibold text-ink-500 hover:underline">Negah Brochure Platform</a>
            </p>
            @if ($previewMode)
                <p class="no-print mt-4 text-xs text-amber-600">
                    Temporary preview link — expires after
                    {{ config('negah.preview_ttl_hours') }} hours or upon activation.
                </p>
            @endif
        </div>
    </footer>

</body>
</html>
