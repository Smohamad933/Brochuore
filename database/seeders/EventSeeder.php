<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Demo submissions covering every status, output type and template.
     */
    public function run(): void
    {
        Event::create([
            'title' => 'Midnight Live 2026 — Spring Concert',
            'slug' => 'midnight-live-2026',
            'event_type' => 'concert',
            'output_type' => Event::OUTPUT_BROCHURE,
            'event_date' => now()->addMonths(2)->setTime(20, 0),
            'venue' => 'Grand Arena Hall, Tehran',
            'description' => "An unforgettable night of live music featuring the region's most exciting artists.\n\nDoors open at 19:00. Expect a full light show, special guests and a setlist built around the brand-new album.",
            'logo_path' => null,
            'brand_color' => '#1B6EF5',
            'secondary_color' => '#F59E0B',
            'template_name' => 'default-concert',
            'status' => Event::STATUS_ACTIVE,
            'contact_phone' => '0912 000 0001',
            'activated_at' => now()->subWeek(),
        ]);

        Event::create([
            'title' => 'Innovation Summit 2026',
            'slug' => 'innovation-summit-2026',
            'event_type' => 'conference',
            'output_type' => Event::OUTPUT_RSVP,
            'event_date' => now()->addMonths(1)->setTime(9, 0),
            'venue' => 'Parsian International Conference Center',
            'description' => "A one-day summit for product leaders, engineers and founders.\n\nThree tracks, twenty speakers and hands-on workshops covering AI, cloud architecture and digital transformation. RSVP to reserve your seat — places are limited.",
            'logo_path' => null,
            'brand_color' => '#0F766E',
            'secondary_color' => '#F97316',
            'template_name' => 'minimal-corporate',
            'status' => Event::STATUS_PENDING_REVIEW,
            'contact_phone' => '0912 000 0002',
        ]);

        Event::create([
            'title' => 'Spring Art & Craft Fair',
            'slug' => 'spring-art-fair',
            'event_type' => 'exhibition',
            'output_type' => Event::OUTPUT_BROCHURE,
            'event_date' => now()->addWeeks(3),
            'venue' => 'City Garden Exhibition Hall',
            'description' => "The city's favourite independent art fair returns with over 120 exhibitors, live painting, food trucks and a dedicated kids' corner. Free entry all weekend.",
            'logo_path' => null,
            'brand_color' => '#DB2777',
            'secondary_color' => '#7C3AED',
            'template_name' => 'vibrant-exhibition',
            'status' => Event::STATUS_PENDING_REVIEW,
            'contact_phone' => '0912 000 0003',
        ]);

        Event::create([
            'title' => 'The Autumn Gala Evening',
            'slug' => 'autumn-gala-evening',
            'event_type' => 'other',
            'output_type' => Event::OUTPUT_INVITATION,
            'event_date' => now()->addMonths(3)->setTime(19, 30),
            'venue' => 'The Marble Room, Espinas Palace',
            'description' => 'You are cordially invited to an elegant evening of dinner, live orchestra and charity fundraising. Formal attire. Kindly confirm your attendance in advance.',
            'logo_path' => null,
            'brand_color' => '#B45309',
            'secondary_color' => '#FEF3C7',
            'template_name' => 'elegant-invitation',
            'status' => Event::STATUS_DRAFT,
            'contact_phone' => '0912 000 0004',
        ]);

        Event::create([
            'title' => 'Winter Jazz Nights 2025',
            'slug' => 'winter-jazz-nights',
            'event_type' => 'concert',
            'output_type' => Event::OUTPUT_BROCHURE,
            'event_date' => now()->subMonths(2),
            'venue' => 'Blue Note Stage',
            'description' => 'A three-night jazz marathon that wrapped up the season. Kept online for reference.',
            'logo_path' => null,
            'brand_color' => '#1E3A8A',
            'secondary_color' => '#E5E7EB',
            'template_name' => 'default-concert',
            'status' => Event::STATUS_EXPIRED,
            'contact_phone' => '0912 000 0005',
            'activated_at' => now()->subMonths(3),
        ]);
    }
}
