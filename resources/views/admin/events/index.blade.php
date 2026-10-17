@extends('layouts.admin')

@section('title', 'Submissions')

@section('content')
    <div class="flex flex-col gap-4 pt-6 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-ink-950">Event submissions</h1>
            <p class="mt-1 text-ink-500">Review, adjust and activate client submissions.</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="mt-6 rounded-2xl border border-ink-200/70 bg-white p-4">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            {{-- Status tabs --}}
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.events.index') }}"
                   class="rounded-full px-4 py-2 text-sm font-semibold transition {{ ! $currentStatus ? 'bg-ink-950 text-white' : 'bg-ink-100 text-ink-600 hover:bg-ink-200' }}">
                    All
                </a>
                @foreach ($statuses as $value => $label)
                    <a href="{{ route('admin.events.index', ['status' => $value]) }}"
                       class="rounded-full px-4 py-2 text-sm font-semibold transition {{ $currentStatus === $value ? 'bg-ink-950 text-white' : 'bg-ink-100 text-ink-600 hover:bg-ink-200' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            {{-- Search --}}
            <form method="GET" action="{{ route('admin.events.index') }}" class="flex gap-2">
                @if ($currentStatus)
                    <input type="hidden" name="status" value="{{ $currentStatus }}">
                @endif
                <input type="text" name="q" value="{{ $q }}" placeholder="Search title, slug, phone…"
                       class="w-full rounded-xl border border-ink-200 px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 lg:w-72">
                <button type="submit" class="rounded-xl bg-ink-950 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-ink-800">
                    Search
                </button>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="mt-6 overflow-hidden rounded-3xl border border-ink-200/70 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-ink-100 text-xs uppercase tracking-wider text-ink-400">
                        <th class="px-6 py-4 font-semibold">Event</th>
                        <th class="px-6 py-4 font-semibold">Output</th>
                        <th class="px-6 py-4 font-semibold">Event date</th>
                        <th class="px-6 py-4 font-semibold">Contact</th>
                        <th class="px-6 py-4 font-semibold">RSVPs</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-100">
                    @forelse ($events as $event)
                        <tr class="transition hover:bg-ink-50/70">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="h-2.5 w-2.5 shrink-0 rounded-full" style="background: {{ $event->brand_color }};"></span>
                                    <div>
                                        <a href="{{ route('admin.events.show', $event) }}" class="font-semibold text-ink-900 hover:text-brand-700">
                                            {{ $event->title }}
                                        </a>
                                        <p class="mt-0.5 text-xs text-ink-400">{{ $event->displayUrl() }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-ink-600">{{ $event->outputTypeLabel() }}</td>
                            <td class="px-6 py-4 text-ink-600">{{ $event->event_date->format('d M Y, H:i') }}</td>
                            <td class="px-6 py-4 text-ink-600">{{ $event->contact_phone }}</td>
                            <td class="px-6 py-4">
                                <span class="rounded-full bg-ink-100 px-2.5 py-1 text-xs font-semibold text-ink-600">
                                    {{ $event->rsvps_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @include('admin.partials.status-badge', ['status' => $event->status])
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.events.show', $event) }}"
                                   class="rounded-full bg-ink-950 px-4 py-2 text-xs font-semibold text-white transition hover:bg-ink-800">
                                    Review
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-14 text-center">
                                <p class="text-ink-400">No submissions found{{ $q ? ' for “'.$q.'”' : '' }}.</p>
                                <a href="{{ route('admin.events.index') }}" class="mt-2 inline-block text-sm font-semibold text-brand-600 hover:text-brand-800">
                                    Clear filters
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($events->hasPages())
            <div class="border-t border-ink-100 px-6 py-4">
                {{ $events->links() }}
            </div>
        @endif
    </div>
@endsection
