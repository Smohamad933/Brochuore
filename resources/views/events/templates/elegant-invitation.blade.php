{{-- Template: Elegant Invitation — refined card-style digital invitation --}}
@php
    $logoUrl = $event->logo_path ? \Illuminate\Support\Facades\Storage::url($event->logo_path) : null;
@endphp

<div class="min-h-screen px-4 py-12 sm:py-16"
     style="background: linear-gradient(180deg, {{ $event->secondary_color ?: $event->brand_color }}14 0%, #f8f7f4 45%, {{ $event->brand_color }}0d 100%);">

    {{-- Invitation card --}}
    <main class="mx-auto max-w-2xl">
        <div class="border-2 bg-white px-6 py-12 text-center shadow-2xl sm:px-14 sm:py-16"
             style="border-color: {{ $event->brand_color }}55;">

            {{-- Ornament top --}}
            <div class="mx-auto flex w-32 items-center gap-2">
                <span class="h-px flex-1" style="background: {{ $event->brand_color }}55;"></span>
                <span class="h-1.5 w-1.5 rotate-45" style="background: {{ $event->brand_color }};"></span>
                <span class="h-px flex-1" style="background: {{ $event->brand_color }}55;"></span>
            </div>

            @if ($logoUrl)
                <div class="mx-auto mt-8 h-24 w-24 overflow-hidden rounded-full border border-ink-100 p-2">
                    <img src="{{ $logoUrl }}" alt="{{ $event->title }} logo" class="h-full w-full object-contain">
                </div>
            @endif

            <p class="mt-10 text-xs font-semibold uppercase tracking-[0.4em]" style="color: {{ $event->brand_color }};">
                You are cordially invited to
            </p>

            <h1 class="mt-6 font-display text-4xl font-semibold italic leading-tight text-ink-900 sm:text-5xl">
                {{ $event->title }}
            </h1>

            <div class="mx-auto my-8 flex w-28 items-center gap-2">
                <span class="h-px flex-1" style="background: {{ $event->brand_color }}55;"></span>
                <span class="h-1.5 w-1.5 rotate-45" style="background: {{ $event->brand_color }};"></span>
                <span class="h-1.5 w-1.5 rotate-45" style="background: {{ $event->secondary_color ?: $event->brand_color }};"></span>
                <span class="h-1.5 w-1.5 rotate-45" style="background: {{ $event->brand_color }};"></span>
                <span class="h-px flex-1" style="background: {{ $event->brand_color }}55;"></span>
            </div>

            <p class="text-base font-medium uppercase tracking-[0.2em] text-ink-500">
                {{ $event->event_date->isoFormat('dddd, D MMMM YYYY') }}
            </p>
            <p class="mt-2 text-lg text-ink-600">at {{ $event->event_date->isoFormat('HH:mm') }}</p>

            <p class="mt-6 text-lg italic text-ink-500">{{ $event->venue }}</p>

            <div class="prose-event mx-auto mt-8 max-w-md text-base leading-relaxed text-ink-600 whitespace-pre-line">{{ $event->description }}</div>

            <div class="mt-10">
                @if ($event->output_type === \App\Models\Event::OUTPUT_RSVP && $event->isActive())
                    <a href="#rsvp"
                       class="inline-block rounded-full border-2 px-10 py-3 text-sm font-semibold tracking-wide transition hover:brightness-95"
                       style="border-color: {{ $event->brand_color }}; color: {{ $event->brand_color }};">
                        Kindly respond
                    </a>
                @else
                    <button onclick="window.print()"
                            class="no-print inline-block rounded-full border-2 px-10 py-3 text-sm font-semibold tracking-wide transition hover:brightness-95"
                            style="border-color: {{ $event->brand_color }}; color: {{ $event->brand_color }};">
                        Save this invitation
                    </button>
                @endif
            </div>

            {{-- Ornament bottom --}}
            <div class="mx-auto mt-12 flex w-32 items-center gap-2">
                <span class="h-px flex-1" style="background: {{ $event->brand_color }}55;"></span>
                <span class="h-1.5 w-1.5 rotate-45" style="background: {{ $event->brand_color }};"></span>
                <span class="h-px flex-1" style="background: {{ $event->brand_color }}55;"></span>
            </div>

            <p class="mt-8 text-xs uppercase tracking-[0.3em] text-ink-400">
                {{ \Illuminate\Support\Str::before($event->title, '—') }}
            </p>
        </div>

        <p class="mt-6 text-center text-xs text-ink-400">
            {{ $event->displayUrl() }}
        </p>
    </main>
</div>
