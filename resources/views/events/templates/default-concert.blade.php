{{-- Template: Dark Stage — bold dark theme for concerts & live shows --}}
@php
    $logoUrl = $event->logo_path ? \Illuminate\Support\Facades\Storage::url($event->logo_path) : null;
@endphp

<div class="relative min-h-screen text-white" style="background: linear-gradient(165deg, #0b0f1a 0%, #101728 55%, {{ $event->brand_color }}22 100%);">

    {{-- Ambient glow --}}
    <div class="pointer-events-none absolute inset-x-0 top-0 h-96 opacity-40 blur-3xl"
         style="background: radial-gradient(circle at 50% 0%, {{ $event->brand_color }}, transparent);"></div>

    {{-- Hero --}}
    <header class="relative mx-auto max-w-4xl px-4 pb-16 pt-16 text-center sm:pt-24">
        @if ($logoUrl)
            <div class="mx-auto h-24 w-24 overflow-hidden rounded-3xl border border-white/20 bg-white/10 p-2">
                <img src="{{ $logoUrl }}" alt="{{ $event->title }} logo" class="h-full w-full object-contain">
            </div>
        @endif

        <p class="mt-8 text-xs font-bold uppercase tracking-[0.35em]" style="color: {{ $event->brand_color }};">
            {{ $event->eventTypeLabel() }}
        </p>

        <h1 class="mt-4 font-display text-4xl font-bold leading-tight sm:text-6xl">
            {{ $event->title }}
        </h1>

        <div class="mt-8 flex flex-col items-center justify-center gap-3 text-slate-300 sm:flex-row sm:gap-8">
            <p class="flex items-center gap-2 text-base">
                <svg class="h-5 w-5 opacity-70" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                {{ $event->event_date->isoFormat('dddd, D MMMM YYYY — HH:mm') }}
            </p>
            <p class="flex items-center gap-2 text-base">
                <svg class="h-5 w-5 opacity-70" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                {{ $event->venue }}
            </p>
        </div>

        <div class="mt-10 flex flex-wrap items-center justify-center gap-4">
            @if ($event->output_type === \App\Models\Event::OUTPUT_RSVP && $event->isActive())
                <a href="#rsvp"
                   class="rounded-full px-9 py-3.5 text-base font-bold text-white shadow-xl transition hover:-translate-y-0.5"
                   style="background: {{ $event->brand_color }}; box-shadow: 0 14px 34px {{ $event->brand_color }}55;">
                    Reserve your seat
                </a>
            @elseif ($event->output_type === \App\Models\Event::OUTPUT_BROCHURE)
                <a href="#about"
                   class="rounded-full px-9 py-3.5 text-base font-bold text-white shadow-xl transition hover:-translate-y-0.5"
                   style="background: {{ $event->brand_color }}; box-shadow: 0 14px 34px {{ $event->brand_color }}55;">
                    Discover the event
                </a>
            @endif

            <button onclick="window.print()"
                    class="no-print rounded-full border border-white/25 px-8 py-3.5 text-base font-semibold text-white/90 transition hover:bg-white/10">
                Save / print
            </button>
        </div>
    </header>

    {{-- About --}}
    <section id="about" class="relative border-t border-white/10">
        <div class="mx-auto max-w-3xl px-4 py-16">
            <p class="text-xs font-bold uppercase tracking-[0.3em] text-white/40">About the event</p>
            <div class="prose-event mt-5 text-base leading-relaxed text-slate-300 whitespace-pre-line">{{ $event->description }}</div>
        </div>
    </section>

    {{-- Details strip --}}
    <section class="relative border-t border-white/10">
        <div class="mx-auto grid max-w-4xl gap-6 px-4 py-12 sm:grid-cols-3">
            <div class="rounded-2xl border border-white/10 bg-white/5 p-6 text-center">
                <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-white/40">Date</p>
                <p class="mt-2 font-semibold text-white">{{ $event->event_date->isoFormat('D MMM YYYY') }}</p>
                <p class="mt-1 text-sm text-slate-400">{{ $event->event_date->isoFormat('HH:mm') }}</p>
            </div>
            <div class="rounded-2xl border border-white/10 bg-white/5 p-6 text-center">
                <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-white/40">Venue</p>
                <p class="mt-2 font-semibold text-white">{{ $event->venue }}</p>
            </div>
            <div class="rounded-2xl border border-white/10 bg-white/5 p-6 text-center"
                 style="background: linear-gradient(135deg, {{ $event->secondary_color ? $event->secondary_color.'22' : $event->brand_color.'22' }}, transparent);">
                <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-white/40">Presented by</p>
                <p class="mt-2 font-semibold text-white">{{ \Illuminate\Support\Str::before($event->title, '—') }}</p>
            </div>
        </div>
    </section>
</div>
