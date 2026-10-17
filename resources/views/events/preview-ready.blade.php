@extends('layouts.app')

@section('title', 'Preview ready — '.$event->title)
@section('meta_description', 'Your brochure preview is ready. Share the temporary link while our concierge team reviews your event.')

@section('content')
<div class="mx-auto max-w-3xl px-4 py-16 sm:px-6 lg:px-8">

    {{-- Success header --}}
    <div class="text-center">
        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-emerald-100">
            <svg class="h-10 w-10 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
        </div>

        <h1 class="mt-6 text-3xl font-extrabold tracking-tight text-ink-950 sm:text-4xl">
            Preview ready!
        </h1>
        <p class="mx-auto mt-4 max-w-xl text-lg text-ink-600">
            Your brochure for <strong class="font-semibold text-ink-900">{{ $event->title }}</strong>
            has been generated with the <strong class="font-semibold text-ink-900">{{ \App\Models\Event::TEMPLATES[$event->template_name]['label'] ?? $event->template_name }}</strong>
            template.
        </p>
    </div>

    {{-- Temporary link card --}}
    <div class="mt-10 rounded-3xl border border-brand-200 bg-brand-50/60 p-6 sm:p-8">
        <p class="text-xs font-bold uppercase tracking-[0.22em] text-brand-700">Temporary preview link</p>

        <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center">
            <div class="flex-1 overflow-x-auto rounded-xl border border-brand-200 bg-white px-4 py-3">
                <code id="preview-link" class="block whitespace-nowrap text-sm text-ink-700">{{ $previewUrl }}</code>
            </div>
            <button type="button"
                    onclick="navigator.clipboard.writeText(document.getElementById('preview-link').innerText).then(() => { this.textContent = 'Copied!'; setTimeout(() => this.textContent = 'Copy link', 1500); })"
                    class="shrink-0 rounded-xl bg-brand-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-brand-700">
                Copy link
            </button>
        </div>

        <p class="mt-3 text-xs leading-relaxed text-ink-500">
            This signed link is valid for <strong>{{ config('negah.preview_ttl_hours') }} hours</strong>.
            Use it to review the design and share it with your team before activation.
        </p>
    </div>

    {{-- Future permanent link --}}
    <div class="mt-6 rounded-3xl border border-ink-200/70 bg-white p-6 sm:p-8">
        <p class="text-xs font-bold uppercase tracking-[0.22em] text-ink-400">Your permanent public link (after activation)</p>
        <p class="mt-3 text-xl font-bold text-ink-900">
            <span class="text-ink-400">{{ $baseDisplayUrl }}</span><span class="text-brand-700">{{ $event->slug }}</span>
        </p>
        <p class="mt-2 text-sm text-ink-500">
            Your requested link is reserved and locked for this submission. It goes live permanently
            once our team completes the review.
        </p>
    </div>

    {{-- What happens next --}}
    <div class="mt-10">
        <h2 class="text-lg font-bold text-ink-900">What happens next?</h2>
        <div class="mt-4 space-y-4">
            @foreach ([
                ['title' => 'Concierge review', 'text' => 'Our team reviews your event details and the generated brochure.'],
                ['title' => 'Phone consultation', 'text' => 'We call you on '.$event->contact_phone.' to confirm details and make any adjustments you want.'],
                ['title' => 'Activation', 'text' => 'After confirmation (and payment verification), your link goes live permanently with a secure, locked slug.'],
            ] as $i => $step)
                <div class="flex gap-4 rounded-2xl border border-ink-200/70 bg-white p-5">
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-brand-600 text-sm font-bold text-white">
                        {{ $i + 1 }}
                    </span>
                    <div>
                        <p class="font-semibold text-ink-900">{{ $step['title'] }}</p>
                        <p class="mt-1 text-sm text-ink-600">{{ $step['text'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Actions --}}
    <div class="mt-10 flex flex-col gap-4 sm:flex-row sm:justify-center">
        <a href="{{ $previewUrl }}"
           class="rounded-full bg-ink-950 px-8 py-3.5 text-center text-base font-semibold text-white transition hover:bg-ink-800">
            Open the live preview →
        </a>
        <a href="{{ route('events.create') }}"
           class="rounded-full border border-ink-200 bg-white px-8 py-3.5 text-center text-base font-semibold text-ink-700 transition hover:bg-ink-50">
            Submit another event
        </a>
    </div>
</div>
@endsection
