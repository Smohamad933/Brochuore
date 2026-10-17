{{-- Template: Clean Minimal — airy professional layout for corporate events --}}
@php
    $logoUrl = $event->logo_path ? \Illuminate\Support\Facades\Storage::url($event->logo_path) : null;
@endphp

<div class="min-h-screen bg-white">

    {{-- Accent bar --}}
    <div class="h-2 w-full" style="background: linear-gradient(90deg, {{ $event->brand_color }}, {{ $event->secondary_color ?: $event->brand_color }});"></div>

    {{-- Hero --}}
    <header class="mx-auto max-w-4xl px-4 pb-14 pt-14 sm:pt-20">
        <div class="flex items-start gap-5">
            @if ($logoUrl)
                <div class="h-20 w-20 shrink-0 overflow-hidden rounded-2xl border border-ink-200 bg-white p-2">
                    <img src="{{ $logoUrl }}" alt="{{ $event->title }} logo" class="h-full w-full object-contain">
                </div>
            @endif
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.28em]" style="color: {{ $event->brand_color }};">
                    {{ $event->eventTypeLabel() }}
                </p>
                <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-ink-950 sm:text-5xl">
                    {{ $event->title }}
                </h1>
                <p class="mt-3 text-lg text-ink-500">
                    {{ $event->event_date->isoFormat('dddd, D MMMM YYYY — HH:mm') }}
                </p>
            </div>
        </div>

        <div class="mt-10 flex flex-wrap gap-4">
            @if ($event->output_type === \App\Models\Event::OUTPUT_RSVP && $event->isActive())
                <a href="#rsvp"
                   class="rounded-xl px-8 py-3.5 text-base font-bold text-white shadow-lg transition hover:-translate-y-0.5"
                   style="background: {{ $event->brand_color }};">
                    RSVP now
                </a>
            @endif
            <button onclick="window.print()"
                    class="no-print rounded-xl border border-ink-200 px-8 py-3.5 text-base font-semibold text-ink-700 transition hover:bg-ink-50">
                Save / print
            </button>
        </div>
    </header>

    {{-- Info cards --}}
    <section class="border-y border-ink-100 bg-ink-50/70">
        <div class="mx-auto grid max-w-4xl gap-6 px-4 py-12 sm:grid-cols-2">
            <div class="rounded-2xl border border-ink-100 bg-white p-6">
                <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-ink-400">When</p>
                <p class="mt-2 text-lg font-bold text-ink-900">{{ $event->event_date->isoFormat('ddd, D MMM YYYY') }}</p>
                <p class="mt-1 text-ink-500">{{ $event->event_date->isoFormat('HH:mm') }}</p>
            </div>
            <div class="rounded-2xl border border-ink-100 bg-white p-6">
                <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-ink-400">Where</p>
                <p class="mt-2 text-lg font-bold text-ink-900">{{ $event->venue }}</p>
                <a href="https://maps.google.com/?q={{ urlencode($event->venue) }}" target="_blank" rel="noopener"
                   class="mt-2 inline-flex items-center gap-1 text-sm font-semibold" style="color: {{ $event->brand_color }};">
                    Open in maps
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                </a>
            </div>
        </div>
    </section>

    {{-- About --}}
    <section class="mx-auto max-w-3xl px-4 py-16">
        <div class="flex items-center gap-3">
            <span class="h-1.5 w-10 rounded-full" style="background: {{ $event->brand_color }};"></span>
            <h2 class="text-sm font-bold uppercase tracking-[0.22em] text-ink-400">About this event</h2>
        </div>
        <div class="prose-event mt-6 text-lg leading-relaxed text-ink-700 whitespace-pre-line">{{ $event->description }}</div>
    </section>

    {{-- CTA strip --}}
    <section class="mx-auto max-w-4xl px-4 pb-16">
        <div class="rounded-3xl p-8 text-center"
             style="background: linear-gradient(120deg, {{ $event->brand_color }}, {{ $event->secondary_color ?: $event->brand_color }});">
            <p class="text-xl font-bold text-white">{{ $event->title }}</p>
            <p class="mt-2 text-white/85">{{ $event->event_date->isoFormat('D MMMM YYYY') }} · {{ $event->venue }}</p>
            <p class="mt-4 inline-flex items-center gap-2 rounded-full bg-white/15 px-5 py-2 text-sm font-semibold text-white">
                Share this page: {{ $event->displayUrl() }}
            </p>
        </div>
    </section>
</div>
