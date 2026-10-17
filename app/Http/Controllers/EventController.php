<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\View\View;

class EventController extends Controller
{
    /**
     * Show the public submission form (with the Alpine.js live preview pane).
     */
    public function create(): View
    {
        return view('events.create', [
            'eventTypes' => Event::EVENT_TYPES,
            'outputTypes' => Event::OUTPUT_TYPES,
            'templates' => Event::TEMPLATES,
            'baseDisplayUrl' => config('negah.base_display_url'),
        ]);
    }

    /**
     * AJAX endpoint: check slug availability in real time.
     * GET /slug-check?slug=my-event
     */
    public function checkSlug(Request $request): JsonResponse
    {
        $slug = Str::of((string) $request->query('slug', ''))
            ->lower()
            ->trim()
            ->replaceMatches('/[^a-z0-9\-]/', '-')
            ->replaceMatches('/-{2,}/', '-')
            ->trim('-')
            ->toString();

        $min = (int) config('negah.slug_min', 3);
        $max = (int) config('negah.slug_max', 40);

        if (strlen($slug) < $min || strlen($slug) > $max) {
            return response()->json([
                'available' => false,
                'slug' => $slug,
                'message' => "Use between {$min} and {$max} characters (lowercase letters, numbers, dashes).",
                'suggestion' => null,
            ]);
        }

        if (in_array($slug, Event::RESERVED_SLUGS, true)) {
            return response()->json([
                'available' => false,
                'slug' => $slug,
                'message' => 'This link is reserved by the platform.',
                'suggestion' => $slug.'-'.now()->format('Y'),
            ]);
        }

        if (! Event::where('slug', $slug)->exists()) {
            return response()->json([
                'available' => true,
                'slug' => $slug,
                'message' => 'Great! This link is available.',
                'suggestion' => null,
            ]);
        }

        // Suggest the first free alternative: slug-2, slug-3, ...
        $base = $slug;
        $n = 2;
        while (Event::where('slug', $base.'-'.$n)->exists()) {
            $n++;
        }

        return response()->json([
            'available' => false,
            'slug' => $slug,
            'message' => 'This link is already taken.',
            'suggestion' => $base.'-'.$n,
        ]);
    }

    /**
     * Validate + persist the submission, then show the "Preview Ready" screen.
     */
    public function store(StoreEventRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $request->file('logo')->store('logos', 'public');
        }

        // Fall back to the automated suggestion if no template was chosen.
        $data['template_name'] ??= Event::suggestTemplate(
            $data['event_type'],
            $data['output_type'] ?? null
        );

        $data['status'] = Event::STATUS_PENDING_REVIEW;
        $data['user_id'] = null; // reserved for future customer accounts

        $event = Event::create($data);

        // Redirect straight to the temporary, signed "Preview Ready" screen.
        return redirect()->to($this->previewReadyUrl($event));
    }

    /**
     * "Preview Ready" screen — temporary link + what happens next.
     * Protected by Laravel's "signed" middleware (temporary signature).
     */
    public function previewReady(Event $event): View
    {
        return view('events.preview-ready', [
            'event' => $event,
            'previewUrl' => $event->temporaryPreviewUrl(),
            'baseDisplayUrl' => config('negah.base_display_url'),
        ]);
    }

    /**
     * Render the dynamic brochure / RSVP page / invitation card.
     *
     * Public behaviour:
     *  - active    → always visible, permanent link.
     *  - expired   → visible with an "expired" notice.
     *  - draft /
     *    pending   → visible ONLY via the temporary signed preview link
     *                (or to a logged-in admin).
     */
    public function show(Request $request, string $slug): View
    {
        $event = Event::where('slug', $slug)->firstOrFail();

        if (! $event->isActive() && ! $event->isExpired()) {
            $isAdmin = (bool) $request->session()->get('admin_logged_in');

            if (! $isAdmin && ! $request->hasValidSignature()) {
                abort(403, 'This brochure is not active yet. Please use your temporary preview link, or contact the Negah team.');
            }
        }

        $previewMode = ! $event->isActive() && ! $event->isExpired();

        return view('events.show', [
            'event' => $event,
            'previewMode' => $previewMode,
        ]);
    }

    /**
     * Accept RSVP submissions on active events that use the RSVP output type.
     */
    public function storeRsvp(Request $request, string $slug): RedirectResponse
    {
        $event = Event::where('slug', $slug)->firstOrFail();

        if (! $event->isActive() || $event->output_type !== Event::OUTPUT_RSVP) {
            abort(404);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:120'],
            'email' => ['required', 'email', 'max:180'],
            'phone' => ['nullable', 'string', 'max:40'],
            'guests' => ['required', 'integer', 'min:1', 'max:20'],
            'message' => ['nullable', 'string', 'max:2000'],
        ], [
            'guests.min' => 'Please enter at least 1 guest.',
            'guests.max' => 'For groups larger than 20, please contact the organiser directly.',
        ]);

        $event->rsvps()->create($data);

        return redirect()
            ->route('events.show', ['slug' => $event->slug])
            ->with('rsvp_success', 'Thank you! Your RSVP has been received. The organiser will be in touch shortly.');
    }

    /**
     * Temporary signed URL for the preview-ready screen.
     */
    private function previewReadyUrl(Event $event): string
    {
        return URL::temporarySignedRoute(
            'events.preview-ready',
            now()->addHours((int) config('negah.preview_ttl_hours', 48)),
            ['event' => $event->id]
        );
    }
}
