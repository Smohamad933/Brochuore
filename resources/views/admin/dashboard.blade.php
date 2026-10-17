@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="pt-6">
        <h1 class="text-2xl font-bold tracking-tight text-ink-950">Dashboard</h1>
        <p class="mt-1 text-ink-500">Submission pipeline at a glance.</p>
    </div>

    {{-- Stat cards --}}
    <div class="mt-8 grid grid-cols-2 gap-4 lg:grid-cols-5">
        <a href="{{ route('admin.events.index') }}"
           class="rounded-2xl border border-ink-200/70 bg-white p-5 transition hover:shadow-md">
            <p class="text-xs font-semibold uppercase tracking-wider text-ink-400">Total</p>
            <p class="mt-2 text-3xl font-extrabold text-ink-950">{{ $counts['all'] }}</p>
        </a>
        <a href="{{ route('admin.events.index', ['status' => 'pending_review']) }}"
           class="rounded-2xl border border-amber-200 bg-amber-50 p-5 transition hover:shadow-md">
            <p class="text-xs font-semibold uppercase tracking-wider text-amber-600">Pending review</p>
            <p class="mt-2 text-3xl font-extrabold text-amber-700">{{ $counts['pending'] }}</p>
        </a>
        <a href="{{ route('admin.events.index', ['status' => 'active']) }}"
           class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 transition hover:shadow-md">
            <p class="text-xs font-semibold uppercase tracking-wider text-emerald-600">Active</p>
            <p class="mt-2 text-3xl font-extrabold text-emerald-700">{{ $counts['active'] }}</p>
        </a>
        <a href="{{ route('admin.events.index', ['status' => 'draft']) }}"
           class="rounded-2xl border border-ink-200/70 bg-white p-5 transition hover:shadow-md">
            <p class="text-xs font-semibold uppercase tracking-wider text-ink-400">Drafts</p>
            <p class="mt-2 text-3xl font-extrabold text-ink-700">{{ $counts['draft'] }}</p>
        </a>
        <a href="{{ route('admin.events.index', ['status' => 'expired']) }}"
           class="rounded-2xl border border-ink-200/70 bg-white p-5 transition hover:shadow-md">
            <p class="text-xs font-semibold uppercase tracking-wider text-ink-400">Expired</p>
            <p class="mt-2 text-3xl font-extrabold text-ink-700">{{ $counts['expired'] }}</p>
        </a>
    </div>

    <div class="mt-6 flex items-center gap-3 rounded-2xl border border-brand-200 bg-brand-50/60 px-5 py-4">
        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-brand-600 text-white">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
        </span>
        <div>
            <p class="text-sm font-bold text-brand-900">{{ $rsvpCount }} RSVP {{ Str::plural('response', $rsvpCount) }} collected</p>
            <p class="text-xs text-brand-700">Across all active events with RSVP forms. RSVP lists are shown on each event's review page.</p>
        </div>
    </div>

    {{-- Recent submissions --}}
    <div class="mt-10 overflow-hidden rounded-3xl border border-ink-200/70 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-ink-100 px-6 py-5">
            <h2 class="text-lg font-bold text-ink-900">Latest submissions</h2>
            <a href="{{ route('admin.events.index') }}" class="text-sm font-semibold text-brand-600 hover:text-brand-800">
                View all →
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-ink-100 text-xs uppercase tracking-wider text-ink-400">
                        <th class="px-6 py-3.5 font-semibold">Event</th>
                        <th class="px-6 py-3.5 font-semibold">Type</th>
                        <th class="px-6 py-3.5 font-semibold">Date</th>
                        <th class="px-6 py-3.5 font-semibold">Status</th>
                        <th class="px-6 py-3.5 font-semibold">Submitted</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-100">
                    @forelse ($recentEvents as $event)
                        <tr class="transition hover:bg-ink-50/70">
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.events.show', $event) }}" class="font-semibold text-ink-900 hover:text-brand-700">
                                    {{ $event->title }}
                                </a>
                                <p class="mt-0.5 text-xs text-ink-400">{{ $event->displayUrl() }}</p>
                            </td>
                            <td class="px-6 py-4 text-ink-600">{{ $event->outputTypeLabel() }}</td>
                            <td class="px-6 py-4 text-ink-600">{{ $event->event_date->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                @include('admin.partials.status-badge', ['status' => $event->status])
                            </td>
                            <td class="px-6 py-4 text-xs text-ink-400">{{ $event->created_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-ink-400">
                                No submissions yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
