@extends('layouts.admin')

@section('title', $event->title)

@section('content')
    <div class="pt-6">
        <nav class="flex items-center gap-2 text-sm text-ink-400">
            <a href="{{ route('admin.events.index') }}" class="hover:text-brand-600">Submissions</a>
            <span>/</span>
            <span class="font-medium text-ink-600">{{ \Illuminate\Support\Str::limit($event->title, 40) }}</span>
        </nav>

        <div class="mt-4 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-bold tracking-tight text-ink-950">{{ $event->title }}</h1>
                    @include('admin.partials.status-badge', ['status' => $event->status])
                </div>
                <p class="mt-1 text-ink-500">
                    Submitted {{ $event->created_at->format('d M Y, H:i') }}
                    · Template: <strong class="font-semibold text-ink-700">{{ \App\Models\Event::TEMPLATES[$event->template_name]['label'] ?? $event->template_name }}</strong>
                    · Output: <strong class="font-semibold text-ink-700">{{ $event->outputTypeLabel() }}</strong>
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ $event->temporaryPreviewUrl() }}" target="_blank"
                   class="rounded-full border border-ink-200 bg-white px-5 py-2.5 text-sm font-semibold text-ink-700 transition hover:bg-ink-50">
                    Open preview ↗
                </a>
                @if ($event->isActive())
                    <a href="{{ $event->publicUrl() }}" target="_blank"
                       class="rounded-full bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-700">
                        Open live page ↗
                    </a>
                @endif
                <a href="{{ route('admin.events.edit', $event) }}"
                   class="rounded-full bg-ink-950 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-ink-800">
                    Make adjustments
                </a>
            </div>
        </div>
    </div>

    <div class="mt-8 grid gap-8 lg:grid-cols-3">

        {{-- ============================================================ --}}
        {{-- Details                                                      --}}
        {{-- ============================================================ --}}
        <div class="space-y-6 lg:col-span-2">

            <div class="rounded-3xl border border-ink-200/70 bg-white p-6 shadow-sm sm:p-8">
                <h2 class="text-lg font-bold text-ink-900">Submission details</h2>

                <dl class="mt-5 grid gap-x-8 gap-y-5 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-ink-400">Event type</dt>
                        <dd class="mt-1 text-[15px] font-medium text-ink-800">{{ $event->eventTypeLabel() }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-ink-400">Date &amp; time</dt>
                        <dd class="mt-1 text-[15px] font-medium text-ink-800">{{ $event->event_date->format('l, d M Y — H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-ink-400">Venue</dt>
                        <dd class="mt-1 text-[15px] font-medium text-ink-800">{{ $event->venue }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-ink-400">Contact phone</dt>
                        <dd class="mt-1 text-[15px] font-medium text-ink-800">
                            <a href="tel:{{ $event->contact_phone }}" class="text-brand-700 hover:underline">{{ $event->contact_phone }}</a>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-ink-400">Requested link</dt>
                        <dd class="mt-1">
                            <code class="rounded bg-ink-100 px-2 py-1 text-sm font-semibold text-ink-800">{{ $event->displayUrl() }}</code>
                            @if ($event->isSlugLocked())
                                <span class="ml-2 inline-flex items-center gap-1 text-xs font-semibold text-emerald-700">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/></svg>
                                    Slug locked
                                </span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-ink-400">Brand colours</dt>
                        <dd class="mt-1 flex items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 rounded-full border border-ink-200 px-2.5 py-1 text-xs font-semibold text-ink-700">
                                <span class="h-3.5 w-3.5 rounded-full border border-ink-200" style="background: {{ $event->brand_color }}"></span>
                                {{ $event->brand_color }}
                            </span>
                            @if ($event->secondary_color)
                                <span class="inline-flex items-center gap-1.5 rounded-full border border-ink-200 px-2.5 py-1 text-xs font-semibold text-ink-700">
                                    <span class="h-3.5 w-3.5 rounded-full border border-ink-200" style="background: {{ $event->secondary_color }}"></span>
                                    {{ $event->secondary_color }}
                                </span>
                            @endif
                        </dd>
                    </div>
                </dl>

                <div class="mt-6">
                    <dt class="text-xs font-semibold uppercase tracking-wider text-ink-400">Description</dt>
                    <dd class="mt-2 whitespace-pre-line rounded-2xl bg-ink-50 p-4 text-[15px] leading-relaxed text-ink-700">{{ $event->description }}</dd>
                </div>

                @if ($event->logo_path)
                    <div class="mt-6">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-ink-400">Uploaded logo</dt>
                        <dd class="mt-2">
                            <div class="h-24 w-24 overflow-hidden rounded-2xl border border-ink-200 bg-white p-2">
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($event->logo_path) }}" alt="Logo" class="h-full w-full object-contain">
                            </div>
                        </dd>
                    </div>
                @endif
            </div>

            {{-- RSVPs --}}
            <div class="rounded-3xl border border-ink-200/70 bg-white p-6 shadow-sm sm:p-8">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-bold text-ink-900">RSVP responses</h2>
                    <span class="rounded-full bg-ink-100 px-3 py-1 text-xs font-semibold text-ink-600">
                        {{ $event->rsvps->count() }} total
                    </span>
                </div>

                @if ($event->rsvps->isEmpty())
                    <p class="mt-4 text-sm text-ink-400">
                        No RSVPs yet. Responses appear here when the event is active with the RSVP output type.
                    </p>
                @else
                    <div class="mt-5 overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b border-ink-100 text-xs uppercase tracking-wider text-ink-400">
                                    <th class="py-2.5 pr-4 font-semibold">Name</th>
                                    <th class="py-2.5 pr-4 font-semibold">Email</th>
                                    <th class="py-2.5 pr-4 font-semibold">Phone</th>
                                    <th class="py-2.5 pr-4 font-semibold">Guests</th>
                                    <th class="py-2.5 pr-4 font-semibold">Received</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-ink-100">
                                @foreach ($event->rsvps as $rsvp)
                                    <tr>
                                        <td class="py-3 pr-4 font-medium text-ink-800">
                                            {{ $rsvp->name }}
                                            @if ($rsvp->message)
                                                <p class="mt-0.5 max-w-xs text-xs font-normal text-ink-400">{{ $rsvp->message }}</p>
                                            @endif
                                        </td>
                                        <td class="py-3 pr-4 text-ink-600">{{ $rsvp->email }}</td>
                                        <td class="py-3 pr-4 text-ink-600">{{ $rsvp->phone ?: '—' }}</td>
                                        <td class="py-3 pr-4 text-ink-600">{{ $rsvp->guests }}</td>
                                        <td class="py-3 pr-4 text-xs text-ink-400">{{ $rsvp->created_at->format('d M, H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- Sidebar: status workflow                                     --}}
        {{-- ============================================================ --}}
        <div class="space-y-6">

            {{-- Status actions --}}
            <div class="rounded-3xl border border-ink-200/70 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-bold text-ink-900">Status workflow</h2>
                <p class="mt-2 text-sm text-ink-500">
                    After the telephone consultation and payment verification, activate the submission to
                    publish its permanent link. Activation <strong class="font-semibold text-ink-700">locks the slug</strong>.
                </p>

                <form method="POST" action="{{ route('admin.events.status', $event) }}" class="mt-5 space-y-4">
                    @csrf

                    <div>
                        <label for="admin_notes" class="block text-xs font-semibold uppercase tracking-wider text-ink-400">
                            Consultation notes
                        </label>
                        <textarea id="admin_notes" name="admin_notes" rows="4"
                                  placeholder="Notes from the phone call: requested adjustments, payment reference…"
                                  class="mt-2 w-full rounded-xl border border-ink-200 px-4 py-3 text-sm outline-none transition focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10">{{ old('admin_notes', $event->admin_notes) }}</textarea>
                    </div>

                    <div class="grid gap-2">
                        @if (! $event->isActive())
                            <button type="submit" name="status" value="active"
                                    class="flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-emerald-600/25 transition hover:bg-emerald-700">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                Approve &amp; activate
                            </button>
                        @endif

                        @if ($event->status !== \App\Models\Event::STATUS_PENDING_REVIEW && ! $event->isActive())
                            <button type="submit" name="status" value="pending_review"
                                    class="w-full rounded-xl border border-amber-200 bg-amber-50 px-5 py-3 text-sm font-bold text-amber-700 transition hover:bg-amber-100">
                                Move back to pending review
                            </button>
                        @endif

                        @if ($event->status !== \App\Models\Event::STATUS_EXPIRED)
                            <button type="submit" name="status" value="expired"
                                    class="w-full rounded-xl border border-red-200 bg-red-50 px-5 py-3 text-sm font-bold text-red-700 transition hover:bg-red-100">
                                Mark as expired
                            </button>
                        @endif

                        @if ($event->status !== \App\Models\Event::STATUS_DRAFT && ! $event->isActive())
                            <button type="submit" name="status" value="draft"
                                    class="w-full rounded-xl border border-ink-200 bg-ink-50 px-5 py-3 text-sm font-bold text-ink-600 transition hover:bg-ink-100">
                                Set as draft
                            </button>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Quick info card --}}
            <div class="rounded-3xl border border-ink-200/70 bg-white p-6 shadow-sm">
                <h2 class="text-sm font-bold uppercase tracking-wider text-ink-400">Quick info</h2>
                <dl class="mt-4 space-y-3 text-sm">
                    <div class="flex justify-between gap-4">
                        <dt class="text-ink-500">Activated</dt>
                        <dd class="font-semibold text-ink-800">
                            {{ $event->activated_at ? $event->activated_at->format('d M Y, H:i') : '—' }}
                        </dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-ink-500">Last updated</dt>
                        <dd class="font-semibold text-ink-800">{{ $event->updated_at->format('d M Y, H:i') }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-ink-500">Output</dt>
                        <dd class="font-semibold text-ink-800">{{ $event->outputTypeLabel() }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-ink-500">Template</dt>
                        <dd class="font-semibold text-ink-800">{{ \App\Models\Event::TEMPLATES[$event->template_name]['label'] ?? $event->template_name }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Danger zone --}}
            @if (! $event->isActive())
                <div class="rounded-3xl border border-red-200 bg-red-50/50 p-6">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-red-600">Danger zone</h2>
                    <p class="mt-2 text-sm text-red-700/80">Permanently delete this submission (not possible for active events).</p>
                    <form method="POST" action="{{ route('admin.events.destroy', $event) }}"
                          onsubmit="return confirm('Delete this submission permanently?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="mt-4 w-full rounded-xl border border-red-300 bg-white px-5 py-2.5 text-sm font-bold text-red-700 transition hover:bg-red-100">
                            Delete submission
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection
