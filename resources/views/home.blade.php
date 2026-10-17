@extends('layouts.app')

@section('title', 'Negah — Event Brochures & Digital Invitations')
@section('meta_description', 'Create a digital event brochure, RSVP page or graphic invitation card in minutes. Self-service submission, concierge review, permanent link at negahm.ir/B/your-slug.')

@section('content')
    {{-- Hero --}}
    <section class="relative overflow-hidden">
        <div class="pointer-events-none absolute inset-0 -z-10">
            <div class="absolute -top-32 right-0 h-[32rem] w-[32rem] rounded-full bg-brand-200/40 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 h-[26rem] w-[26rem] rounded-full bg-indigo-200/40 blur-3xl"></div>
        </div>

        <div class="mx-auto max-w-7xl px-4 pb-20 pt-16 sm:px-6 sm:pt-20 lg:px-8 lg:pt-24">
            <div class="grid items-center gap-12 lg:grid-cols-2">
                <div>
                    <span class="inline-flex items-center gap-2 rounded-full border border-brand-200 bg-brand-50 px-4 py-1.5 text-xs font-semibold uppercase tracking-wider text-brand-700">
                        <span class="h-1.5 w-1.5 rounded-full bg-brand-600"></span>
                        Hybrid model · Self-service + concierge
                    </span>

                    <h1 class="mt-6 text-4xl font-extrabold leading-[1.08] tracking-tight text-ink-950 sm:text-5xl lg:text-6xl">
                        Event brochures that look
                        <span class="bg-gradient-to-r from-brand-600 to-indigo-600 bg-clip-text text-transparent">hand-designed</span>
                        — in minutes.
                    </h1>

                    <p class="mt-6 max-w-xl text-lg leading-relaxed text-ink-600">
                        Submit your event once. Get an instant live preview on a beautiful responsive
                        template, a permanent branded link like
                        <code class="rounded bg-ink-100 px-1.5 py-0.5 text-[15px] font-semibold text-brand-700">negahm.ir/B/your-slug</code>,
                        and a concierge team that reviews, adjusts and activates everything.
                    </p>

                    <div class="mt-8 flex flex-wrap items-center gap-4">
                        <a href="{{ route('events.create') }}" class="rounded-full bg-brand-600 px-8 py-3.5 text-base font-semibold text-white shadow-lg shadow-brand-600/25 transition hover:-translate-y-0.5 hover:bg-brand-700 hover:shadow-brand-600/40">
                            Create your brochure →
                        </a>
                        <a href="#how-it-works" class="rounded-full border border-ink-200 bg-white px-8 py-3.5 text-base font-semibold text-ink-700 transition hover:border-ink-300 hover:bg-ink-50">
                            See how it works
                        </a>
                    </div>

                    <div class="mt-10 flex items-center gap-6 text-sm text-ink-500">
                        <div class="flex -space-x-2">
                            @foreach (['#1b6ef5', '#7c3aed', '#db2777'] as $color)
                                <span class="h-8 w-8 rounded-full border-2 border-white" style="background: {{ $color }}"></span>
                            @endforeach
                        </div>
                        <p><strong class="font-semibold text-ink-800">Trusted by event teams</strong><br>Concerts · Seminars · Exhibitions · Weddings</p>
                    </div>
                </div>

                {{-- Hero mockup --}}
                <div class="relative mx-auto w-full max-w-md lg:max-w-none">
                    <div class="rounded-[2rem] border border-ink-200/70 bg-white p-3 shadow-2xl shadow-ink-900/10">
                        <div class="overflow-hidden rounded-[1.5rem] bg-ink-950">
                            <div class="flex items-center gap-1.5 bg-ink-900/80 px-4 py-3">
                                <span class="h-2.5 w-2.5 rounded-full bg-red-400"></span>
                                <span class="h-2.5 w-2.5 rounded-full bg-amber-400"></span>
                                <span class="h-2.5 w-2.5 rounded-full bg-emerald-400"></span>
                                <span class="ml-3 truncate rounded bg-ink-800 px-3 py-1 text-[11px] text-ink-300">negahm.ir/B/midnight-live-2026</span>
                            </div>
                            <div class="relative px-8 py-12 text-center">
                                <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_0%,rgba(51,142,255,0.25),transparent_60%)]"></div>
                                <p class="relative text-xs font-semibold uppercase tracking-[0.3em] text-brand-400">Live in concert</p>
                                <h3 class="relative mt-3 font-display text-3xl font-bold text-white">Midnight Live <span class="text-brand-400">2026</span></h3>
                                <p class="relative mt-3 text-sm text-ink-300">Grand Arena Hall · Friday, 20 Nov — 20:00</p>
                                <div class="relative mt-6 inline-flex items-center gap-2 rounded-full bg-brand-500 px-5 py-2.5 text-sm font-semibold text-white">
                                    Get tickets
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="absolute -left-6 bottom-8 hidden rounded-2xl border border-ink-200/70 bg-white px-5 py-4 shadow-xl sm:block">
                        <p class="text-xs font-semibold text-ink-400">Live preview</p>
                        <p class="mt-1 flex items-center gap-1.5 text-sm font-semibold text-emerald-600">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                            Preview ready instantly
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Output types --}}
    <section id="outputs" class="border-y border-ink-200/70 bg-white py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="text-3xl font-bold tracking-tight text-ink-950 sm:text-4xl">One form. Three beautiful outputs.</h2>
                <p class="mt-4 text-lg text-ink-600">Choose exactly what your event needs — every option is mobile-first and fully branded.</p>
            </div>

            <div class="mt-14 grid gap-8 md:grid-cols-3">
                @foreach ([
                    ['icon' => '📖', 'title' => 'Digital Brochure', 'text' => 'A responsive, scroll-ready event page with your logo, brand colours, schedule and venue details.'],
                    ['icon' => '✉️', 'title' => 'RSVP Form', 'text' => 'A brochure with a built-in RSVP form so guests can confirm attendance and you can track responses.'],
                    ['icon' => '🎨', 'title' => 'Graphic Invitation Card', 'text' => 'A refined, card-style digital invitation — perfect for weddings, galas and formal ceremonies.'],
                ] as $card)
                    <div class="group rounded-3xl border border-ink-200/70 bg-ink-50/60 p-8 transition hover:-translate-y-1 hover:border-brand-200 hover:bg-white hover:shadow-xl hover:shadow-brand-900/5">
                        <div class="text-4xl">{{ $card['icon'] }}</div>
                        <h3 class="mt-5 text-xl font-bold text-ink-900">{{ $card['title'] }}</h3>
                        <p class="mt-3 leading-relaxed text-ink-600">{{ $card['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- How it works --}}
    <section id="how-it-works" class="bg-ink-50 py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="text-3xl font-bold tracking-tight text-ink-950 sm:text-4xl">The hybrid model: automation + human care</h2>
                <p class="mt-4 text-lg text-ink-600">Instant previews from the template engine, then a concierge review before your link goes live.</p>
            </div>

            <div class="mt-14 grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['step' => '01', 'title' => 'Submit your event', 'text' => 'Fill the dynamic form: details, logo, brand colours and your custom link slug.'],
                    ['step' => '02', 'title' => 'Instant live preview', 'text' => 'The template engine renders a responsive preview immediately — with a temporary link you can share.'],
                    ['step' => '03', 'title' => 'Concierge review', 'text' => 'Our team reviews your submission, calls you to fine-tune the details, and prepares activation.'],
                    ['step' => '04', 'title' => 'Activation & lock-in', 'text' => 'Once confirmed, negahm.ir/B/your-slug goes live permanently with a locked, secure slug.'],
                ] as $step)
                    <div class="relative rounded-3xl border border-ink-200/70 bg-white p-8">
                        <span class="text-sm font-bold text-brand-600">{{ $step['step'] }}</span>
                        <h3 class="mt-3 text-lg font-bold text-ink-900">{{ $step['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-ink-600">{{ $step['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="bg-gradient-to-br from-brand-700 via-brand-600 to-indigo-700 py-20">
        <div class="mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">Ready to publish your event?</h2>
            <p class="mx-auto mt-4 max-w-2xl text-lg text-brand-100">
                Start with the submission form — the live preview is instant, and the concierge team
                takes it from there.
            </p>
            <a href="{{ route('events.create') }}" class="mt-8 inline-flex items-center gap-2 rounded-full bg-white px-10 py-4 text-base font-bold text-brand-700 shadow-xl shadow-ink-950/20 transition hover:-translate-y-0.5 hover:shadow-2xl">
                Create your brochure
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
            </a>
        </div>
    </section>
@endsection
