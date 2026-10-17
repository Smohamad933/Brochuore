<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\URL;

class Event extends Model
{
    use HasFactory;

    /* ------------------------------------------------------------------ */
    /* Statuses                                                            */
    /* ------------------------------------------------------------------ */

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PENDING_REVIEW = 'pending_review';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_EXPIRED = 'expired';

    public const STATUSES = [
        self::STATUS_DRAFT => 'Draft',
        self::STATUS_PENDING_REVIEW => 'Pending Review',
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_EXPIRED => 'Expired',
    ];

    /* ------------------------------------------------------------------ */
    /* Output types (what the client ordered)                              */
    /* ------------------------------------------------------------------ */

    public const OUTPUT_BROCHURE = 'brochure';
    public const OUTPUT_RSVP = 'rsvp';
    public const OUTPUT_INVITATION = 'invitation';

    public const OUTPUT_TYPES = [
        self::OUTPUT_BROCHURE => 'Digital Brochure',
        self::OUTPUT_RSVP => 'RSVP Form',
        self::OUTPUT_INVITATION => 'Graphic Invitation Card',
    ];

    /* ------------------------------------------------------------------ */
    /* Event types                                                         */
    /* ------------------------------------------------------------------ */

    public const EVENT_TYPES = [
        'concert' => 'Concert / Live Music',
        'festival' => 'Festival',
        'seminar' => 'Seminar / Workshop',
        'conference' => 'Conference / Corporate',
        'exhibition' => 'Exhibition / Fair',
        'theater' => 'Theater / Performance',
        'webinar' => 'Webinar / Online',
        'other' => 'Other',
    ];

    /* ------------------------------------------------------------------ */
    /* Pre-built responsive templates                                      */
    /* ------------------------------------------------------------------ */

    public const TEMPLATES = [
        'default-concert' => [
            'label' => 'Dark Stage',
            'description' => 'Bold dark theme with glowing accents — ideal for concerts and live shows.',
        ],
        'minimal-corporate' => [
            'label' => 'Clean Minimal',
            'description' => 'Airy, professional layout for seminars, conferences and corporate events.',
        ],
        'vibrant-exhibition' => [
            'label' => 'Vibrant Showcase',
            'description' => 'Colourful, energetic design for exhibitions, fairs and festivals.',
        ],
        'elegant-invitation' => [
            'label' => 'Elegant Invitation',
            'description' => 'Refined card-style layout for formal invitations and ceremonies.',
        ],
    ];

    /* ------------------------------------------------------------------ */
    /* Slugs that can never be issued                                      */
    /* ------------------------------------------------------------------ */

    public const RESERVED_SLUGS = [
        'admin', 'administrator', 'api', 'login', 'logout', 'register',
        'create', 'submit', 'events', 'event', 'preview', 'preview-ready',
        'storage', 'build', 'assets', 'css', 'js', 'img', 'images', 'fonts',
        'up', 'b', 'rsvp', 'home', 'about', 'contact', 'dashboard',
        'password', 'settings', 'profile', 'health',
    ];

    /* ------------------------------------------------------------------ */
    /* Eloquent                                                            */
    /* ------------------------------------------------------------------ */

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'event_type',
        'event_date',
        'venue',
        'description',
        'logo_path',
        'brand_color',
        'secondary_color',
        'template_name',
        'output_type',
        'status',
        'contact_phone',
        'admin_notes',
        'activated_at',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'datetime',
            'activated_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function rsvps(): HasMany
    {
        return $this->hasMany(Rsvp::class)->latest();
    }

    /* ------------------------------------------------------------------ */
    /* Query helpers                                                       */
    /* ------------------------------------------------------------------ */

    /**
     * Free-text search over title, slug, venue and contact phone.
     */
    public function scopeSearch($query, ?string $term)
    {
        if (! $term) {
            return $query;
        }

        $like = '%'.$term.'%';

        return $query->where(function ($q) use ($like) {
            $q->where('title', 'like', $like)
                ->orWhere('slug', 'like', $like)
                ->orWhere('venue', 'like', $like)
                ->orWhere('contact_phone', 'like', $like);
        });
    }

    /**
     * Filter by one or more statuses.
     */
    public function scopeStatus($query, ?string $status)
    {
        if (! $status || ! array_key_exists($status, self::STATUSES)) {
            return $query;
        }

        return $query->where('status', $status);
    }

    /* ------------------------------------------------------------------ */
    /* State helpers                                                       */
    /* ------------------------------------------------------------------ */

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING_REVIEW;
    }

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isExpired(): bool
    {
        return $this->status === self::STATUS_EXPIRED;
    }

    /**
     * The slug is locked once the event has been activated.
     */
    public function isSlugLocked(): bool
    {
        return $this->isActive() || $this->isExpired();
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }

    public function outputTypeLabel(): string
    {
        return self::OUTPUT_TYPES[$this->output_type] ?? ucfirst($this->output_type);
    }

    public function eventTypeLabel(): string
    {
        return self::EVENT_TYPES[$this->event_type] ?? ucfirst($this->event_type);
    }

    /* ------------------------------------------------------------------ */
    /* URLs                                                                */
    /* ------------------------------------------------------------------ */

    /**
     * Absolute public URL of the brochure (e.g. https://negahm.ir/B/my-event).
     */
    public function publicUrl(): string
    {
        return route('events.show', ['slug' => $this->slug]);
    }

    /**
     * Pretty, branded display URL (fixed prefix: negahm.ir/B/{slug}).
     */
    public function displayUrl(): string
    {
        return rtrim(config('negah.base_display_url', 'negahm.ir/B/'), '/').'/'.$this->slug;
    }

    /**
     * Temporary signed preview link, valid before activation.
     */
    public function temporaryPreviewUrl(): string
    {
        return URL::temporarySignedRoute(
            'events.show',
            now()->addHours((int) config('negah.preview_ttl_hours', 48)),
            ['slug' => $this->slug]
        );
    }

    /* ------------------------------------------------------------------ */
    /* Template suggestion                                                 */
    /* ------------------------------------------------------------------ */

    /**
     * Pick the best pre-built template for this event automatically.
     */
    public static function suggestTemplate(?string $eventType, ?string $outputType = null): string
    {
        if ($outputType === self::OUTPUT_INVITATION) {
            return 'elegant-invitation';
        }

        return match ($eventType) {
            'concert', 'festival', 'theater' => 'default-concert',
            'exhibition' => 'vibrant-exhibition',
            'seminar', 'conference', 'webinar' => 'minimal-corporate',
            default => 'default-concert',
        };
    }
}
