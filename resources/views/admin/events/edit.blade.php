@extends('layouts.admin')

@section('title', 'Edit: '.$event->title)

@section('content')
    <div class="pt-6">
        <nav class="flex items-center gap-2 text-sm text-ink-400">
            <a href="{{ route('admin.events.index') }}" class="hover:text-brand-600">Submissions</a>
            <span>/</span>
            <a href="{{ route('admin.events.show', $event) }}" class="hover:text-brand-600">{{ \Illuminate\Support\Str::limit($event->title, 36) }}</a>
            <span>/</span>
            <span class="font-medium text-ink-600">Edit</span>
        </nav>

        <h1 class="mt-4 text-2xl font-bold tracking-tight text-ink-950">Adjust submission</h1>
        <p class="mt-1 text-ink-500">
            Apply changes requested during the telephone consultation.
            @if ($event->isSlugLocked())
                <span class="font-semibold text-emerald-700">The slug is locked — the public link can no longer change.</span>
            @endif
        </p>
    </div>

    @if ($errors->any())
        <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 p-5">
            <ul class="list-inside list-disc space-y-1 text-sm text-red-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.events.update', $event) }}" class="mt-8 space-y-8 pb-10">
        @csrf
        @method('PUT')

        <div class="grid gap-8 lg:grid-cols-2">

            {{-- Details --}}
            <div class="rounded-3xl border border-ink-200/70 bg-white p-6 shadow-sm sm:p-8">
                <h2 class="text-lg font-bold text-ink-900">Event details</h2>

                <div class="mt-5 space-y-5">
                    <div>
                        <label for="title" class="block text-sm font-semibold text-ink-700">Title <span class="text-red-500">*</span></label>
                        <input id="title" name="title" type="text" required maxlength="180"
                               value="{{ old('title', $event->title) }}"
                               class="mt-2 w-full rounded-xl border border-ink-200 px-4 py-3 text-[15px] outline-none focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10">
                    </div>

                    <div>
                        <label for="slug" class="block text-sm font-semibold text-ink-700">
                            Slug
                            @if ($event->isSlugLocked())
                                <span class="ml-2 rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-emerald-700">Locked</span>
                            @endif
                        </label>
                        <div class="mt-2 flex items-stretch overflow-hidden rounded-xl border border-ink-200 focus-within:border-brand-500">
                            <span class="flex select-none items-center whitespace-nowrap border-r border-ink-200 bg-ink-50 px-4 text-sm text-ink-500">
                                {{ config('negah.base_display_url') }}
                            </span>
                            <input id="slug" name="slug" type="text" value="{{ old('slug', $event->slug) }}"
                                   @if ($event->isSlugLocked()) readonly
                                   class="w-full border-0 bg-ink-50 px-4 py-3 text-[15px] text-ink-500"
                                   @else
                                   class="w-full border-0 px-4 py-3 text-[15px] outline-none focus:ring-0"
                                   @endif>
                        </div>
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="event_type" class="block text-sm font-semibold text-ink-700">Event type <span class="text-red-500">*</span></label>
                            <select id="event_type" name="event_type" required
                                    class="mt-2 w-full rounded-xl border border-ink-200 bg-white px-4 py-3 text-[15px] outline-none focus:border-brand-500">
                                @foreach ($eventTypes as $value => $label)
                                    <option value="{{ $value }}" @selected(old('event_type', $event->event_type) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="output_type" class="block text-sm font-semibold text-ink-700">Output type <span class="text-red-500">*</span></label>
                            <select id="output_type" name="output_type" required
                                    class="mt-2 w-full rounded-xl border border-ink-200 bg-white px-4 py-3 text-[15px] outline-none focus:border-brand-500">
                                @foreach ($outputTypes as $value => $label)
                                    <option value="{{ $value }}" @selected(old('output_type', $event->output_type) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="event_date" class="block text-sm font-semibold text-ink-700">Date &amp; time <span class="text-red-500">*</span></label>
                        <input id="event_date" name="event_date" type="datetime-local" required
                               value="{{ old('event_date', $event->event_date->format('Y-m-d\TH:i')) }}"
                               class="mt-2 w-full rounded-xl border border-ink-200 px-4 py-3 text-[15px] outline-none focus:border-brand-500">
                    </div>

                    <div>
                        <label for="venue" class="block text-sm font-semibold text-ink-700">Venue <span class="text-red-500">*</span></label>
                        <input id="venue" name="venue" type="text" required maxlength="500"
                               value="{{ old('venue', $event->venue) }}"
                               class="mt-2 w-full rounded-xl border border-ink-200 px-4 py-3 text-[15px] outline-none focus:border-brand-500">
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-semibold text-ink-700">Description <span class="text-red-500">*</span></label>
                        <textarea id="description" name="description" rows="6" required
                                  class="mt-2 w-full rounded-xl border border-ink-200 px-4 py-3 text-[15px] outline-none focus:border-brand-500">{{ old('description', $event->description) }}</textarea>
                    </div>

                    <div>
                        <label for="contact_phone" class="block text-sm font-semibold text-ink-700">Contact phone <span class="text-red-500">*</span></label>
                        <input id="contact_phone" name="contact_phone" type="text" required maxlength="40"
                               value="{{ old('contact_phone', $event->contact_phone) }}"
                               class="mt-2 w-full rounded-xl border border-ink-200 px-4 py-3 text-[15px] outline-none focus:border-brand-500">
                    </div>
                </div>
            </div>

            {{-- Visual identity + notes --}}
            <div class="space-y-8">
                <div class="rounded-3xl border border-ink-200/70 bg-white p-6 shadow-sm sm:p-8">
                    <h2 class="text-lg font-bold text-ink-900">Visual identity</h2>

                    <div class="mt-5 space-y-5">
                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="brand_color" class="block text-sm font-semibold text-ink-700">Primary colour <span class="text-red-500">*</span></label>
                                <input id="brand_color" name="brand_color" type="color"
                                       value="{{ old('brand_color', $event->brand_color) }}"
                                       class="mt-2 h-12 w-full cursor-pointer rounded-xl border border-ink-200 bg-white p-1">
                            </div>
                            <div>
                                <label for="secondary_color" class="block text-sm font-semibold text-ink-700">Secondary colour</label>
                                <input id="secondary_color" name="secondary_color" type="color"
                                       value="{{ old('secondary_color', $event->secondary_color ?? '#f59e0b') }}"
                                       class="mt-2 h-12 w-full cursor-pointer rounded-xl border border-ink-200 bg-white p-1">
                            </div>
                        </div>

                        <div>
                            <label for="template_name" class="block text-sm font-semibold text-ink-700">Template <span class="text-red-500">*</span></label>
                            <select id="template_name" name="template_name" required
                                    class="mt-2 w-full rounded-xl border border-ink-200 bg-white px-4 py-3 text-[15px] outline-none focus:border-brand-500">
                                @foreach ($templates as $key => $tpl)
                                    <option value="{{ $key }}" @selected(old('template_name', $event->template_name) === $key)>
                                        {{ $tpl['label'] }} — {{ $tpl['description'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        @if ($event->logo_path)
                            <div>
                                <p class="text-sm font-semibold text-ink-700">Current logo</p>
                                <div class="mt-2 h-20 w-20 overflow-hidden rounded-2xl border border-ink-200 bg-white p-2">
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($event->logo_path) }}" alt="Logo" class="h-full w-full object-contain">
                                </div>
                                <p class="mt-2 text-xs text-ink-400">To change the logo, ask the client to re-submit it or replace the file in <code>storage/app/public/{{ $event->logo_path }}</code>.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="rounded-3xl border border-ink-200/70 bg-white p-6 shadow-sm sm:p-8">
                    <h2 class="text-lg font-bold text-ink-900">Admin notes</h2>
                    <textarea name="admin_notes" rows="5"
                              class="mt-4 w-full rounded-xl border border-ink-200 px-4 py-3 text-sm outline-none focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10"
                              placeholder="Internal notes — never shown publicly.">{{ old('admin_notes', $event->admin_notes) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex flex-col gap-3 rounded-3xl bg-ink-950 p-6 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-ink-300">
                Changes are applied to the live/preview page immediately after saving.
            </p>
            <div class="flex gap-3">
                <a href="{{ route('admin.events.show', $event) }}"
                   class="rounded-full border border-white/20 px-6 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                    Cancel
                </a>
                <button type="submit"
                        class="rounded-full bg-brand-500 px-8 py-3 text-sm font-bold text-white shadow-lg transition hover:bg-brand-400">
                    Save changes
                </button>
            </div>
        </div>
    </form>
@endsection
