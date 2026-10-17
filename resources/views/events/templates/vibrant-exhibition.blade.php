{{-- Template: Vibrant Showcase — colourful energetic design for exhibitions & fairs --}}
@php
    $logoUrl = $event->logo_path ? \Illuminate\Support\Facades\Storage::url($event->logo_path) : null;
@endphp

<div class="min-h-screen bg-white">

    {{-- Hero --}}
    <header class="relative overflow-hidden text-white"
            style="background: linear-gradient(135deg, {{ $event->brand_color }} 0%, {{ $event->secondary_color ?: $event->brand_color }} 100%);">
        <div class="pointer-events-none absolute inset-0 opacity-25"
             style="background-image: radial-gradient(circle at 18% 22%, rgba(255,255,255,.55) 0, transparent 32%), radial-gradient(circle at 82% 72%, rgba(255,255,255,.35) 0, transparent 38%), radial-gradient(circle at 55% 10%, rgba(255,255,255,.25) 0, transparent 30%);"></div>

        <div class="relative mx-auto max-w-5xl px-4 pb-20 pt-14">
            <div class="flex items-center justify-between">
                <span class="rounded-full bg-white/25 px-4 py-1.5 text-xs font-bold uppercase tracking-[0.25em] backdrop-blur">
                    {{ $event->eventTypeLabel() }}
                </span>
                @if ($logoUrl)
                    <div class="h-14 w-14 overflow-hidden rounded-2xl bg-white/25 p-1.5 backdrop-blur">
                        <img src="{{ $logoUrl }}" alt="{{ $event->title }} logo" class="h-full w-full object-contain">
                    </div>
                @endif
            </div>

            <h1 class="mt-10 max-w-3xl font-display text-5xl font-bold leading-[1.05] sm:text-7xl">
                {{ $event->title }}
            </h1>

            <div class="mt-8 flex flex-wrap items-center gap-x-10 gap-y-3 text-lg font-medium text-white/90">
                <p class="flex items-center gap-2.5">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-white/20 backdrop-blur">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                    </span>
                    {{ $event->event_date->isoFormat('dddd, D MMMM YYYY — HH:mm') }}
                </p>
                <p class="flex items-center gap-2.5">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-white/20 backdrop-blur">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                    </span>
                    {{ $event->venue }}
                </p>
            </div>

            <div class="mt-10 flex flex-wrap gap-4">
                @if ($event->output_type === \App\Models\Event::OUTPUT_RSVP && $event->isActive())
                    <a href="#rsvp"
                       class="rounded-full bg-white px-9 py-3.5 text-base font-bold shadow-xl transition hover:-translate-y-0.5"
                       style="color: {{ $event->brand_color }};">
                        Reserve a spot
                    </a>
                @endif
                <button onclick="window.print()"
                        class="no-print rounded-full border border-white/40 px-8 py-3.5 text-base font-semibold text-white transition hover:bg-white/10">
                    Save / print
                </button>
            </div>
        </div>

        {{-- Wave divider --}}
        <svg class="absolute inset-x-0 bottom-0 w-full text-white" viewBox="0 0 1440 72" fill="currentColor" preserveAspectRatio="none">
            <path d="M0,40 C240,88 480,88 720,48 C960,8 1200,8 1440,40 L1440,72 L0,72 Z"></path>
        </svg>
    </header>

    {{-- About --}}
    <section class="mx-auto max-w-4xl px-4 pb-8 pt-12">
        <div class="flex items-center gap-3">
            <div class="h-2 w-12 rounded-full" style="background: {{ $event->brand_color }};"></div>
            <div class="h-2 w-12 rounded-full" style="background: {{ $event->secondary_color ?: $event->brand_color }};"></div>
        </div>
        <h2 class="mt-5 text-3xl font-extrabold tracking-tight text-ink-950">What to expect</h2>
        <div class="prose-event mt-6 text-lg leading-relaxed text-ink-700 whitespace-pre-line">{{ $event->description }}</div>
    </section>

    {{-- Highlight cards --}}
    <section class="mx-auto max-w-5xl px-4 pb-16 pt-8">
        <div class="grid gap-6 sm:grid-cols-3">
            <div class="rounded-3xl p-7 text-white"
                 style="background: linear-gradient(140deg, {{ $event->brand_color }}, {{ $event->brand_color }}cc);">
                <p class="text-[11px] font-bold uppercase tracking-[0.25em] opacity-75">Date</p>
                <p class="mt-3 text-xl font-bold">{{ $event->event_date->isoFormat('D MMM YYYY') }}</p>
                <p class="mt-1 opacity-85">{{ $event->event_date->isoFormat('HH:mm') }}</p>
            </div>
            <div class="rounded-3xl border border-ink-100 bg-ink-50 p-7">
                <p class="text-[11px] font-bold uppercase tracking-[0.25em] text-ink-400">Venue</p>
                <p class="mt-3 text-xl font-bold text-ink-900">{{ $event->venue }}</p>
                <a href="https://maps.google.com/?q={{ urlencode($event->venue) }}" target="_blank" rel="noopener"
                   class="mt-2 inline-block text-sm font-semibold" style="color: {{ $event->brand_color }};">Get directions →</a>
            </div>
            <div class="rounded-3xl p-7 text-white"
                 style="background: linear-gradient(140deg, {{ $event->secondary_color ?: $event->brand_color }}, {{ $event->secondary_color ?: $event->brand_color }}cc);">
                <p class="text-[11px] font-bold uppercase tracking-[0.25em] opacity-75">Your host</p>
                <p class="mt-3 text-xl font-bold">{{ \Illuminate\Support\Str::before($event->title, '—') }}</p>
                <p class="mt-1 opacity-85 text-sm">{{ $event->displayUrl() }}</p>
            </div>
        </div>
    </section>
</div>
