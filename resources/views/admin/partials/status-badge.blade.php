@php
    $styles = [
        'draft' => 'bg-ink-100 text-ink-600',
        'pending_review' => 'bg-amber-100 text-amber-700',
        'active' => 'bg-emerald-100 text-emerald-700',
        'expired' => 'bg-red-100 text-red-700',
    ];
    $style = $styles[$status] ?? 'bg-ink-100 text-ink-600';
@endphp
<span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold {{ $style }}">
    <span class="h-1.5 w-1.5 rounded-full {{ $status === 'active' ? 'bg-emerald-500' : ($status === 'pending_review' ? 'bg-amber-500' : ($status === 'expired' ? 'bg-red-500' : 'bg-ink-400')) }}"></span>
    {{ \App\Models\Event::STATUSES[$status] ?? ucfirst($status) }}
</span>
