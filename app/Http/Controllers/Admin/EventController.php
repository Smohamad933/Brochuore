<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EventController extends Controller
{
    /**
     * List submissions with status filter + search.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $q = trim((string) $request->query('q', ''));

        $events = Event::query()
            ->status($status)
            ->search($q !== '' ? $q : null)
            ->withCount('rsvps')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.events.index', [
            'events' => $events,
            'statuses' => Event::STATUSES,
            'currentStatus' => $status,
            'q' => $q,
        ]);
    }

    /**
     * Review screen: full submission details, RSVPs, and status actions.
     */
    public function show(Event $event): View
    {
        $event->load('rsvps');

        return view('admin.events.show', [
            'event' => $event,
            'statuses' => Event::STATUSES,
            'outputTypes' => Event::OUTPUT_TYPES,
        ]);
    }

    /**
     * Edit form — for adjustments requested during the phone consultation.
     */
    public function edit(Event $event): View
    {
        return view('admin.events.edit', [
            'event' => $event,
            'eventTypes' => Event::EVENT_TYPES,
            'outputTypes' => Event::OUTPUT_TYPES,
            'templates' => Event::TEMPLATES,
        ]);
    }

    /**
     * Apply adjustments. The slug is locked once the event is active/expired.
     */
    public function update(Request $request, Event $event): RedirectResponse
    {
        $slugLocked = $event->isSlugLocked();

        $rules = [
            'title' => ['required', 'string', 'min:3', 'max:180'],
            'event_type' => ['required', Rule::in(array_keys(Event::EVENT_TYPES))],
            'output_type' => ['required', Rule::in(array_keys(Event::OUTPUT_TYPES))],
            'event_date' => ['required', 'date'],
            'venue' => ['required', 'string', 'min:3', 'max:500'],
            'description' => ['required', 'string', 'min:10', 'max:20000'],
            'brand_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'secondary_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'template_name' => ['required', Rule::in(array_keys(Event::TEMPLATES))],
            'contact_phone' => ['required', 'string', 'min:5', 'max:40'],
            'admin_notes' => ['nullable', 'string', 'max:10000'],
        ];

        if (! $slugLocked) {
            $rules['slug'] = [
                'required',
                'string',
                'min:'.config('negah.slug_min', 3),
                'max:'.config('negah.slug_max', 40),
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::notIn(Event::RESERVED_SLUGS),
                Rule::unique('events', 'slug')->ignore($event->id),
            ];
        }

        $data = $request->validate($rules, [
            'slug.regex' => 'The link may only contain lowercase letters, numbers and dashes.',
            'slug.unique' => 'That link is already taken.',
            'slug.not_in' => 'That link is reserved by the platform.',
        ]);

        if ($slugLocked) {
            // Never allow the locked slug to change through this endpoint.
            unset($data['slug']);
        }

        $event->update($data);

        return redirect()
            ->route('admin.events.show', $event)
            ->with('success', 'Submission updated. Changes are reflected in the preview immediately.');
    }

    /**
     * Status workflow transitions (pending_review → active, …).
     * Activating a submission locks its slug permanently.
     */
    public function updateStatus(Request $request, Event $event): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(array_keys(Event::STATUSES))],
            'admin_notes' => ['nullable', 'string', 'max:10000'],
        ]);

        if ($data['status'] === Event::STATUS_ACTIVE && ! $event->isActive()) {
            $event->update([
                'status' => Event::STATUS_ACTIVE,
                'activated_at' => now(),
                'admin_notes' => $data['admin_notes'] ?? $event->admin_notes,
            ]);

            return redirect()
                ->route('admin.events.show', $event)
                ->with('success', 'Event ACTIVATED — the public link is now live and the slug is permanently locked.');
        }

        $event->update([
            'status' => $data['status'],
            'admin_notes' => $data['admin_notes'] ?? $event->admin_notes,
        ]);

        $label = Event::STATUSES[$data['status']];

        return redirect()
            ->route('admin.events.show', $event)
            ->with('success', "Status changed to “{$label}”.");
    }

    /**
     * Delete a submission (drafts / spam only — active events should be expired).
     */
    public function destroy(Event $event): RedirectResponse
    {
        if ($event->isActive()) {
            return redirect()
                ->route('admin.events.show', $event)
                ->with('error', 'Active events cannot be deleted — set them to “Expired” instead.');
        }

        $event->delete();

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Submission deleted.');
    }
}
