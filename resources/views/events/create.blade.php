@extends('layouts.app')

@section('title', 'Create your event brochure — Negah')
@section('meta_description', 'Submit your event details, choose your visual identity and get an instant live preview of your digital brochure, RSVP page or invitation card.')

@push('head')
<style>
    [x-cloak] { display: none !important; }
    .preview-scroll::-webkit-scrollbar { width: 6px; }
    .preview-scroll::-webkit-scrollbar-thumb { background: rgba(100,116,139,.35); border-radius: 99px; }
</style>
@endpush

@section('content')
<div x-data="brochureForm()" x-init="init()"
     class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">

    {{-- Page header --}}
    <div class="max-w-2xl">
        <nav class="flex items-center gap-2 text-sm text-ink-400">
            <a href="{{ route('home') }}" class="hover:text-brand-600">Home</a>
            <span>/</span>
            <span class="font-medium text-ink-600">New submission</span>
        </nav>
        <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-ink-950 sm:text-4xl">Create your event brochure</h1>
        <p class="mt-3 text-lg text-ink-600">
            Fill in the details below — the live preview on the right updates as you type.
            Once submitted, our concierge team reviews your event and activates your permanent link.
        </p>
    </div>

    {{-- Server-side validation errors --}}
    @if ($errors->any())
        <div class="mt-8 rounded-2xl border border-red-200 bg-red-50 p-5">
            <p class="flex items-center gap-2 text-sm font-semibold text-red-800">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008v.008H12v-.008ZM21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                Please fix the following:
            </p>
            <ul class="mt-2 list-inside list-disc space-y-1 text-sm text-red-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mt-10 grid gap-10 lg:grid-cols-5">

        {{-- ============================================================ --}}
        {{-- FORM                                                         --}}
        {{-- ============================================================ --}}
        <form method="POST" action="{{ route('events.store') }}" enctype="multipart/form-data"
              class="space-y-10 lg:col-span-3">
            @csrf

            {{-- STEP 1 · Event details --}}
            <section class="rounded-3xl border border-ink-200/70 bg-white p-6 shadow-sm sm:p-8">
                <div class="flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-brand-600 text-sm font-bold text-white">1</span>
                    <h2 class="text-xl font-bold text-ink-900">Event details</h2>
                </div>

                <div class="mt-6 grid gap-6 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label for="title" class="block text-sm font-semibold text-ink-700">Event title <span class="text-red-500">*</span></label>
                        <input id="title" name="title" type="text" required maxlength="180"
                               x-model="title" @input="onTitleInput()"
                               value="{{ old('title') }}"
                               placeholder="e.g. Midnight Live 2026 — Spring Concert"
                               class="mt-2 w-full rounded-xl border border-ink-200 px-4 py-3 text-[15px] outline-none transition focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10">
                        <p class="mt-1.5 text-xs text-ink-400">Shown as the main headline of your brochure.</p>
                    </div>

                    <div>
                        <label for="event_type" class="block text-sm font-semibold text-ink-700">Event type <span class="text-red-500">*</span></label>
                        <select id="event_type" name="event_type" required x-model="eventType" @change="onTypeChange()"
                                class="mt-2 w-full rounded-xl border border-ink-200 bg-white px-4 py-3 text-[15px] outline-none transition focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10">
                            @foreach ($eventTypes as $value => $label)
                                <option value="{{ $value }}" @selected(old('event_type', 'concert') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="event_date" class="block text-sm font-semibold text-ink-700">Date &amp; time <span class="text-red-500">*</span></label>
                        <input id="event_date" name="event_date" type="datetime-local" required
                               x-model="eventDate" value="{{ old('event_date') }}"
                               class="mt-2 w-full rounded-xl border border-ink-200 px-4 py-3 text-[15px] outline-none transition focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10">
                    </div>

                    <div class="sm:col-span-2">
                        <label for="venue" class="block text-sm font-semibold text-ink-700">Location / venue <span class="text-red-500">*</span></label>
                        <input id="venue" name="venue" type="text" required maxlength="500"
                               x-model="venue" value="{{ old('venue') }}"
                               placeholder="e.g. Grand Arena Hall, Tehran"
                               class="mt-2 w-full rounded-xl border border-ink-200 px-4 py-3 text-[15px] outline-none transition focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10">
                    </div>

                    <div class="sm:col-span-2">
                        <label for="description" class="block text-sm font-semibold text-ink-700">Short description <span class="text-red-500">*</span></label>
                        <textarea id="description" name="description" rows="5" required minlength="10" maxlength="20000"
                                  x-model="description" value="{{ old('description') }}"
                                  placeholder="Describe your event: the programme, performers, speakers, highlights…"
                                  class="mt-2 w-full rounded-xl border border-ink-200 px-4 py-3 text-[15px] outline-none transition focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10">{{ old('description') }}</textarea>
                    </div>
                </div>
            </section>

            {{-- STEP 2 · Visual identity --}}
            <section class="rounded-3xl border border-ink-200/70 bg-white p-6 shadow-sm sm:p-8">
                <div class="flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-brand-600 text-sm font-bold text-white">2</span>
                    <h2 class="text-xl font-bold text-ink-900">Visual identity</h2>
                </div>

                <div class="mt-6 grid gap-6 sm:grid-cols-2">
                    {{-- Logo upload --}}
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-ink-700">Logo (optional)</label>
                        <div class="mt-2 flex items-start gap-5">
                            <label for="logo"
                                   class="flex h-24 w-24 shrink-0 cursor-pointer flex-col items-center justify-center overflow-hidden rounded-2xl border-2 border-dashed border-ink-200 bg-ink-50 text-ink-400 transition hover:border-brand-400 hover:bg-brand-50/50">
                                <template x-if="!logoDataUrl">
                                    <span class="flex flex-col items-center">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/></svg>
                                        <span class="mt-1 text-[10px] font-semibold">Upload</span>
                                    </span>
                                </template>
                                <img :src="logoDataUrl" alt="Logo preview" class="h-full w-full object-contain" x-show="logoDataUrl">
                            </label>
                            <input id="logo" name="logo" type="file" accept=".png,.jpg,.jpeg,.webp" class="hidden"
                                   @change="onLogoChange($event)">
                            <div class="pt-2">
                                <p class="text-sm text-ink-600">Drag or click to upload — PNG, JPG or WEBP, up to 4 MB.</p>
                                <p class="mt-1 text-xs text-ink-400">Your logo is placed on the brochure header and invitation card.</p>
                                <button type="button" x-show="logoDataUrl" x-cloak @click="clearLogo()"
                                        class="mt-2 text-xs font-semibold text-red-500 hover:text-red-700">Remove logo</button>
                            </div>
                        </div>
                    </div>

                    {{-- Brand colours --}}
                    <div>
                        <label for="brand_color" class="block text-sm font-semibold text-ink-700">Primary brand colour <span class="text-red-500">*</span></label>
                        <div class="mt-2 flex items-center gap-3">
                            <input id="brand_color" name="brand_color" type="color" x-model="brandColor"
                                   value="{{ old('brand_color', '#1b6ef5') }}"
                                   class="h-12 w-14 cursor-pointer rounded-xl border border-ink-200 bg-white p-1">
                            <input type="text" x-model="brandColor" maxlength="7" placeholder="#1B6EF5"
                                   class="w-full rounded-xl border border-ink-200 px-4 py-3 font-mono text-[15px] uppercase outline-none transition focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10">
                        </div>
                    </div>

                    <div>
                        <label for="secondary_color" class="block text-sm font-semibold text-ink-700">Secondary colour (optional)</label>
                        <div class="mt-2 flex items-center gap-3">
                            <input id="secondary_color" name="secondary_color" type="color" x-model="secondaryColor"
                                   value="{{ old('secondary_color', '#f59e0b') }}"
                                   class="h-12 w-14 cursor-pointer rounded-xl border border-ink-200 bg-white p-1">
                            <input type="text" x-model="secondaryColor" maxlength="7" placeholder="#F59E0B"
                                   class="w-full rounded-xl border border-ink-200 px-4 py-3 font-mono text-[15px] uppercase outline-none transition focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10">
                        </div>
                    </div>

                    {{-- Template picker --}}
                    <div class="sm:col-span-2">
                        <div class="flex items-center justify-between">
                            <label class="block text-sm font-semibold text-ink-700">Template <span class="text-red-500">*</span></label>
                            <button type="button" @click="resetTemplateSuggestion()"
                                    class="text-xs font-semibold text-brand-600 hover:text-brand-800">
                                ↻ Use recommended for this event type
                            </button>
                        </div>
                        <input type="hidden" name="template_name" :value="templateName">
                        <div class="mt-3 grid gap-3 sm:grid-cols-2">
                            @foreach ($templates as $key => $tpl)
                                <button type="button"
                                        @click="setTemplate('{{ $key }}')"
                                        :class="templateName === '{{ $key }}'
                                            ? 'border-brand-500 ring-2 ring-brand-500/30 bg-brand-50/40'
                                            : 'border-ink-200 hover:border-ink-300'"
                                        class="rounded-2xl border p-4 text-left transition">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-bold text-ink-900">{{ $tpl['label'] }}</span>
                                        <span class="flex h-4 w-4 items-center justify-center rounded-full border-2"
                                              :class="templateName === '{{ $key }}' ? 'border-brand-600 bg-brand-600' : 'border-ink-300'">
                                            <span class="h-1.5 w-1.5 rounded-full bg-white" x-show="templateName === '{{ $key }}'"></span>
                                        </span>
                                    </div>
                                    <p class="mt-1 text-xs leading-relaxed text-ink-500">{{ $tpl['description'] }}</p>
                                    <span class="mt-2 inline-block rounded-full bg-ink-100 px-2 py-0.5 text-[10px] font-semibold text-ink-500"
                                          x-show="suggestedTemplate() === '{{ $key }}'">Recommended</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>

            {{-- STEP 3 · Custom link --}}
            <section class="rounded-3xl border border-ink-200/70 bg-white p-6 shadow-sm sm:p-8">
                <div class="flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-brand-600 text-sm font-bold text-white">3</span>
                    <h2 class="text-xl font-bold text-ink-900">Your custom link</h2>
                </div>

                <div class="mt-6">
                    <label for="slug" class="block text-sm font-semibold text-ink-700">
                        Choose your slug <span class="text-red-500">*</span>
                    </label>

                    <div class="mt-2 flex items-stretch overflow-hidden rounded-xl border focus-within:border-brand-500 focus-within:ring-4 focus-within:ring-brand-500/10"
                         :class="slugStatus === 'taken' || slugStatus === 'invalid' ? 'border-red-400' : slugStatus === 'available' ? 'border-emerald-400' : 'border-ink-200'">
                        <span class="flex select-none items-center whitespace-nowrap rounded-l-xl border-r border-ink-200 bg-ink-50 px-4 text-[15px] font-medium text-ink-500">
                            {{ $baseDisplayUrl }}
                        </span>
                        <input id="slug" name="slug" type="text" required x-model="slug"
                               @input="onSlugInput()" maxlength="40"
                               value="{{ old('slug') }}"
                               placeholder="my-event-2026"
                               class="w-full border-0 px-4 py-3 text-[15px] font-medium outline-none focus:ring-0">
                    </div>

                    {{-- Availability feedback --}}
                    <div class="mt-2 min-h-[20px] text-sm">
                        <span x-show="slugStatus === 'checking'" x-cloak class="text-ink-400">Checking availability…</span>
                        <span x-show="slugStatus === 'available'" x-cloak class="flex items-center gap-1.5 font-medium text-emerald-600">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                            <span x-text="slugMessage"></span>
                        </span>
                        <span x-show="slugStatus === 'taken' || slugStatus === 'invalid'" x-cloak class="flex flex-wrap items-center gap-2 font-medium text-red-600">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                            <span x-text="slugMessage"></span>
                            <button type="button" x-show="slugSuggestion" x-cloak @click="applySuggestion()"
                                    class="rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-semibold text-red-700 hover:bg-red-100">
                                Use “<span x-text="slugSuggestion"></span>”
                            </button>
                        </span>
                        <span x-show="slugStatus === 'idle' && slug" x-cloak class="text-ink-400">
                            Your public link will be <strong class="font-semibold text-ink-600" x-text="fullUrl()"></strong>
                        </span>
                    </div>
                </div>
            </section>

            {{-- STEP 4 · Output type --}}
            <section class="rounded-3xl border border-ink-200/70 bg-white p-6 shadow-sm sm:p-8">
                <div class="flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-brand-600 text-sm font-bold text-white">4</span>
                    <h2 class="text-xl font-bold text-ink-900">What should we build?</h2>
                </div>

                <div class="mt-6 grid gap-3 sm:grid-cols-3">
                    @foreach ($outputTypes as $value => $label)
                        <label class="cursor-pointer">
                            <input type="radio" name="output_type" value="{{ $value }}" class="peer sr-only"
                                   x-model="outputType" @change="onTypeChange()"
                                   @checked(old('output_type', 'brochure') === $value)>
                            <div class="rounded-2xl border border-ink-200 p-5 text-center transition peer-checked:border-brand-500 peer-checked:bg-brand-50/50 peer-checked:ring-2 peer-checked:ring-brand-500/30">
                                <div class="text-2xl">
                                    @if ($value === 'brochure') 📖 @elseif ($value === 'rsvp') ✉️ @else 🎨 @endif
                                </div>
                                <p class="mt-2 text-sm font-bold text-ink-900">{{ $label }}</p>
                            </div>
                        </label>
                    @endforeach
                </div>
            </section>

            {{-- STEP 5 · Contact --}}
            <section class="rounded-3xl border border-ink-200/70 bg-white p-6 shadow-sm sm:p-8">
                <div class="flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-brand-600 text-sm font-bold text-white">5</span>
                    <h2 class="text-xl font-bold text-ink-900">Contact for consultation</h2>
                </div>

                <div class="mt-6">
                    <label for="contact_phone" class="block text-sm font-semibold text-ink-700">
                        Contact phone <span class="text-red-500">*</span>
                    </label>
                    <input id="contact_phone" name="contact_phone" type="tel" required maxlength="40"
                           x-model="contactPhone" value="{{ old('contact_phone') }}"
                           placeholder="e.g. 0912 000 0000"
                           class="mt-2 w-full rounded-xl border border-ink-200 px-4 py-3 text-[15px] outline-none transition focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10">
                    <p class="mt-1.5 text-xs text-ink-400">
                        Our concierge team will call you to confirm the details before activation.
                    </p>
                </div>
            </section>

            {{-- Submit --}}
            <div class="flex flex-col items-start gap-4 rounded-3xl bg-ink-950 p-6 sm:flex-row sm:items-center sm:justify-between sm:p-8">
                <div>
                    <p class="text-base font-semibold text-white">Ready to see your brochure?</p>
                    <p class="mt-1 text-sm text-ink-300">You'll get an instant preview link right after submitting.</p>
                </div>
                <button type="submit"
                        class="w-full rounded-full bg-brand-500 px-8 py-3.5 text-base font-bold text-white shadow-lg shadow-brand-500/30 transition hover:-translate-y-0.5 hover:bg-brand-400 sm:w-auto">
                    Submit &amp; generate preview →
                </button>
            </div>
        </form>

        {{-- ============================================================ --}}
        {{-- LIVE PREVIEW (Alpine.js)                                      --}}
        {{-- ============================================================ --}}
        <div class="lg:col-span-2">
            <div class="sticky top-24">
                <div class="flex items-center justify-between">
                    <h2 class="flex items-center gap-2 text-sm font-bold uppercase tracking-wider text-ink-500">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                        </span>
                        Live preview
                    </h2>
                    <span class="rounded-full bg-ink-100 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-wide text-ink-500"
                          x-text="templateLabel()"></span>
                </div>

                {{-- URL chip --}}
                <div class="mt-3 truncate rounded-xl border border-ink-200/70 bg-white px-4 py-2.5 text-sm shadow-sm">
                    <span class="text-ink-400">{{ $baseDisplayUrl }}</span><span class="font-semibold text-brand-700" x-text="slug || 'your-slug'"></span>
                </div>

                {{-- Preview canvas --}}
                <div class="preview-scroll mt-4 max-h-[70vh] overflow-y-auto rounded-[1.75rem] border border-ink-200/70 bg-white shadow-xl shadow-ink-900/5">

                    {{-- CONCERT (dark) --}}
                    <div x-show="templateName === 'default-concert'" class="relative overflow-hidden text-white"
                         :style="{ background: 'linear-gradient(160deg, #0b0f1a 0%, #101728 55%, ' + brandColor + '22 100%)' }">
                        <div class="absolute inset-x-0 top-0 h-48 opacity-40 blur-3xl"
                             :style="{ background: 'radial-gradient(circle at 50% 0%, ' + brandColor + ', transparent)' }"></div>
                        <div class="relative px-7 pb-9 pt-10 text-center">
                            <div class="mx-auto h-16 w-16 overflow-hidden rounded-2xl border border-white/20 bg-white/10">
                                <img x-show="logoDataUrl" :src="logoDataUrl" class="h-full w-full object-contain" alt="">
                                <span x-show="!logoDataUrl" class="flex h-full w-full items-center justify-center text-2xl font-black"
                                      :style="{ color: brandColor }">N</span>
                            </div>
                            <p class="mt-5 text-[10px] font-bold uppercase tracking-[0.32em]" :style="{ color: brandColor }"
                               x-text="eventTypeLabel()"></p>
                            <h3 class="mt-2 font-display text-3xl font-bold leading-tight" x-text="title || 'Your event title'"></h3>
                            <p class="mt-3 text-sm text-slate-300" x-text="formattedDate() || 'Date & time'"></p>
                            <p class="mt-1 text-sm text-slate-400" x-text="venue || 'Venue / location'"></p>
                            <button type="button" class="mt-6 rounded-full px-7 py-2.5 text-sm font-bold text-white shadow-lg"
                                    :style="{ background: brandColor, boxShadow: '0 10px 25px ' + brandColor + '55' }"
                                    x-text="outputType === 'rsvp' ? 'Reserve your seat' : 'Learn more'"></button>
                        </div>
                        <div class="border-t border-white/10 px-7 py-7">
                            <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-slate-400">About the event</p>
                            <p class="mt-2 text-sm leading-relaxed text-slate-300 line-clamp-4"
                               x-text="description || 'Your event description will appear here — tell your audience what makes this event unmissable.'"></p>
                            <div class="mt-5 flex items-center justify-between rounded-xl px-4 py-3"
                                 :style="{ background: 'linear-gradient(90deg, ' + secondaryColor + '22, transparent)' }">
                                <span class="text-xs text-slate-300">Powered by</span>
                                <span class="text-xs font-bold" :style="{ color: secondaryColor }">Negah</span>
                            </div>
                        </div>
                    </div>

                    {{-- CORPORATE (clean minimal) --}}
                    <div x-show="templateName === 'minimal-corporate'" class="bg-white">
                        <div class="h-1.5 w-full" :style="{ background: 'linear-gradient(90deg, ' + brandColor + ', ' + secondaryColor + ')' }"></div>
                        <div class="px-7 pb-9 pt-9">
                            <div class="flex items-center gap-3">
                                <div class="h-12 w-12 overflow-hidden rounded-xl border border-ink-200">
                                    <img x-show="logoDataUrl" :src="logoDataUrl" class="h-full w-full object-contain" alt="">
                                    <span x-show="!logoDataUrl" class="flex h-full w-full items-center justify-center text-lg font-black"
                                          :style="{ color: brandColor }">N</span>
                                </div>
                                <div>
                                    <p class="text-[10px] font-semibold uppercase tracking-[0.22em] text-ink-400" x-text="eventTypeLabel()"></p>
                                    <div class="mt-0.5 h-8 w-24 rounded bg-ink-100" x-show="!title"></div>
                                    <h3 class="mt-0.5 text-xl font-bold text-ink-900" x-text="title || 'Your event title'"></h3>
                                </div>
                            </div>
                            <div class="mt-6 grid grid-cols-2 gap-4">
                                <div class="rounded-xl bg-ink-50 p-3.5">
                                    <p class="text-[10px] font-semibold uppercase tracking-wider text-ink-400">Date</p>
                                    <p class="mt-1 text-sm font-semibold text-ink-800" x-text="formattedDate() || '—'"></p>
                                </div>
                                <div class="rounded-xl bg-ink-50 p-3.5">
                                    <p class="text-[10px] font-semibold uppercase tracking-wider text-ink-400">Venue</p>
                                    <p class="mt-1 text-sm font-semibold text-ink-800" x-text="venue || '—'"></p>
                                </div>
                            </div>
                            <p class="mt-5 text-sm leading-relaxed text-ink-600"
                               x-text="description || 'Your event description will appear here — tell your audience what makes this event unmissable.'"></p>
                            <button type="button" class="mt-6 w-full rounded-xl py-3 text-sm font-bold text-white shadow-md"
                                    :style="{ background: brandColor }"
                                    x-text="outputType === 'rsvp' ? 'RSVP now' : 'Register interest'"></button>
                            <p class="mt-5 border-t border-ink-100 pt-4 text-center text-[11px] text-ink-400">
                                Organised with <span class="font-semibold" :style="{ color: brandColor }">Negah</span>
                            </p>
                        </div>
                    </div>

                    {{-- EXHIBITION (vibrant) --}}
                    <div x-show="templateName === 'vibrant-exhibition'" class="relative">
                        <div class="px-7 pb-9 pt-10 text-white"
                             :style="{ background: 'linear-gradient(135deg, ' + brandColor + ' 0%, ' + secondaryColor + ' 100%)' }">
                            <div class="absolute inset-0 opacity-20"
                                 style="background-image: radial-gradient(circle at 20% 20%, rgba(255,255,255,.6) 0, transparent 35%), radial-gradient(circle at 80% 70%, rgba(255,255,255,.4) 0, transparent 40%)"></div>
                            <div class="relative">
                                <div class="flex items-center justify-between">
                                    <span class="rounded-full bg-white/25 px-3 py-1 text-[10px] font-bold uppercase tracking-[0.22em] backdrop-blur"
                                          x-text="eventTypeLabel()"></span>
                                    <div class="h-10 w-10 overflow-hidden rounded-full bg-white/25 backdrop-blur">
                                        <img x-show="logoDataUrl" :src="logoDataUrl" class="h-full w-full object-contain" alt="">
                                    </div>
                                </div>
                                <h3 class="mt-6 font-display text-3xl font-bold leading-tight" x-text="title || 'Your event title'"></h3>
                                <p class="mt-3 text-sm font-medium text-white/90" x-text="formattedDate() || 'Date & time'"></p>
                                <p class="mt-1 text-sm text-white/80" x-text="venue || 'Venue / location'"></p>
                                <button type="button" class="mt-6 rounded-full bg-white px-7 py-2.5 text-sm font-bold shadow-lg"
                                        :style="{ color: brandColor }"
                                        x-text="outputType === 'rsvp' ? 'Reserve a spot' : 'Explore'"></button>
                            </div>
                        </div>
                        <div class="px-7 py-8">
                            <div class="flex gap-3">
                                <div class="h-1.5 w-10 rounded-full" :style="{ background: brandColor }"></div>
                                <div class="h-1.5 w-10 rounded-full" :style="{ background: secondaryColor }"></div>
                            </div>
                            <p class="mt-4 text-sm leading-relaxed text-ink-600"
                               x-text="description || 'Your event description will appear here — tell your audience what makes this event unmissable.'"></p>
                        </div>
                    </div>

                    {{-- INVITATION (elegant card) --}}
                    <div x-show="templateName === 'elegant-invitation'" class="bg-ink-50 px-6 py-9">
                        <div class="border-2 bg-white p-8 text-center shadow-sm"
                             :style="{ borderColor: brandColor + '55', background: 'linear-gradient(180deg, #fff 0%, ' + secondaryColor + '0d 100%)' }">
                            <div class="mx-auto h-16 w-16 overflow-hidden rounded-full border-2 border-dashed"
                                 :style="{ borderColor: brandColor + '66' }">
                                <img x-show="logoDataUrl" :src="logoDataUrl" class="h-full w-full object-contain" alt="">
                                <span x-show="!logoDataUrl" class="flex h-full w-full items-center justify-center text-2xl font-black"
                                      :style="{ color: brandColor }">N</span>
                            </div>
                            <p class="mt-5 text-[11px] font-semibold uppercase tracking-[0.35em]" :style="{ color: brandColor }">
                                You are cordially invited
                            </p>
                            <h3 class="mt-3 font-display text-3xl font-semibold italic text-ink-900" x-text="title || 'Your event title'"></h3>
                            <div class="mx-auto my-5 flex w-24 items-center gap-2">
                                <span class="h-px flex-1" :style="{ background: brandColor + '55' }"></span>
                                <span class="h-1.5 w-1.5 rotate-45" :style="{ background: brandColor }"></span>
                                <span class="h-px flex-1" :style="{ background: brandColor + '55' }"></span>
                            </div>
                            <p class="text-sm text-ink-600" x-text="formattedDate() || 'Date & time'"></p>
                            <p class="mt-1 text-sm italic text-ink-500" x-text="venue || 'Venue / location'"></p>
                            <p class="mx-auto mt-6 max-w-xs text-sm leading-relaxed text-ink-500"
                               x-text="description || 'Your event description will appear here.'"></p>
                            <button type="button" class="mt-7 rounded-full border-2 px-8 py-2.5 text-sm font-semibold transition"
                                    :style="{ borderColor: brandColor, color: brandColor }"
                                    x-text="outputType === 'rsvp' ? 'Kindly respond' : 'View details'"></button>
                        </div>
                    </div>

                    {{-- RSVP badge --}}
                    <div class="border-t border-ink-100 bg-white px-6 py-4 text-center">
                        <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-[11px] font-semibold"
                              :class="outputType === 'rsvp' ? 'bg-emerald-50 text-emerald-700' : outputType === 'invitation' ? 'bg-purple-50 text-purple-700' : 'bg-brand-50 text-brand-700'"
                              x-text="outputLabel()"></span>
                    </div>
                </div>

                <p class="mt-4 text-xs leading-relaxed text-ink-400">
                    This is a live in-page preview. After submission, the full responsive brochure is
                    rendered server-side from the selected template and published to your permanent link
                    after concierge review.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function brochureForm() {
        return {
            /* ---- form state (prefilled from old() server values) ---- */
            title: @js(old('title', '')),
            eventType: @js(old('event_type', 'concert')),
            outputType: @js(old('output_type', 'brochure')),
            eventDate: @js(old('event_date', '')),
            venue: @js(old('venue', '')),
            description: @js(old('description', '')),
            brandColor: @js(old('brand_color', '#1B6EF5')),
            secondaryColor: @js(old('secondary_color', '#F59E0B')),
            templateName: @js(old('template_name', 'default-concert')),
            slug: @js(old('slug', '')),
            contactPhone: @js(old('contact_phone', '')),
            logoDataUrl: '',

            /* ---- reactive ui state ---- */
            slugTouched: @js(old('slug') ? true : false),
            templateTouched: @js(old('template_name') ? true : false),
            slugStatus: 'idle',
            slugMessage: '',
            slugSuggestion: '',
            slugTimer: null,

            eventTypes: @js($eventTypes),
            outputTypes: @js($outputTypes),
            templates: @js($templates),

            init() {
                if (this.slug) this.checkSlug();
            },

            /* ---------------- helpers ---------------- */
            slugify(str) {
                return (str || '')
                    .toLowerCase()
                    .normalize('NFKD')
                    .replace(/[^a-z0-9\s-]/g, '')
                    .trim()
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-')
                    .slice(0, 40);
            },

            fullUrl() {
                return @js($baseDisplayUrl) + (this.slug || 'your-slug');
            },

            formattedDate() {
                if (!this.eventDate) return '';
                const d = new Date(this.eventDate);
                if (isNaN(d)) return this.eventDate;
                return d.toLocaleDateString('en-GB', { weekday: 'short', day: 'numeric', month: 'short', year: 'numeric' })
                    + ' — ' + d.toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit' });
            },

            eventTypeLabel() {
                return this.eventTypes[this.eventType] || this.eventType;
            },

            outputLabel() {
                return this.outputTypes[this.outputType] || this.outputType;
            },

            templateLabel() {
                return (this.templates[this.templateName] || {}).label || this.templateName;
            },

            /* ---------------- events ---------------- */
            onTitleInput() {
                if (!this.slugTouched) {
                    this.slug = this.slugify(this.title);
                    this.queueSlugCheck();
                }
            },

            onSlugInput() {
                this.slugTouched = true;
                this.slug = this.slugify(this.slug);
                this.queueSlugCheck();
            },

            onTypeChange() {
                if (!this.templateTouched) {
                    this.templateName = this.suggestedTemplate();
                }
            },

            setTemplate(name) {
                this.templateName = name;
                this.templateTouched = true;
            },

            resetTemplateSuggestion() {
                this.templateName = this.suggestedTemplate();
                this.templateTouched = false;
            },

            suggestedTemplate() {
                if (this.outputType === 'invitation') return 'elegant-invitation';
                switch (this.eventType) {
                    case 'concert':
                    case 'festival':
                    case 'theater':
                        return 'default-concert';
                    case 'exhibition':
                        return 'vibrant-exhibition';
                    case 'seminar':
                    case 'conference':
                    case 'webinar':
                        return 'minimal-corporate';
                    default:
                        return 'default-concert';
                }
            },

            onLogoChange(event) {
                const file = event.target.files && event.target.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = (e) => { this.logoDataUrl = e.target.result; };
                reader.readAsDataURL(file);
            },

            clearLogo() {
                this.logoDataUrl = '';
                const input = document.getElementById('logo');
                if (input) input.value = '';
            },

            /* ---------------- slug availability ---------------- */
            queueSlugCheck() {
                clearTimeout(this.slugTimer);
                this.slugStatus = 'checking';
                this.slugTimer = setTimeout(() => this.checkSlug(), 450);
            },

            async checkSlug() {
                if (!this.slug || this.slug.length < 3) {
                    this.slugStatus = 'idle';
                    this.slugMessage = '';
                    return;
                }
                this.slugStatus = 'checking';
                try {
                    const res = await fetch('/slug-check?slug=' + encodeURIComponent(this.slug), {
                        headers: { 'Accept': 'application/json' },
                    });
                    const data = await res.json();
                    this.slugMessage = data.message;
                    this.slugSuggestion = data.suggestion || '';
                    this.slugStatus = data.available ? 'available' : 'taken';
                    if (data.slug && data.slug !== this.slug && this.slugStatus === 'taken') {
                        // normalized server-side; keep user's value
                    }
                } catch (e) {
                    this.slugStatus = 'idle';
                }
            },

            applySuggestion() {
                this.slug = this.slugSuggestion;
                this.slugTouched = true;
                this.checkSlug();
            },
        };
    }
</script>
@endpush
