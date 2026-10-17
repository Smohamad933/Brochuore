<?php

namespace Tests\Feature;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class PublicFlowTest extends TestCase
{
    use RefreshDatabase;

    private function makeEvent(array $overrides = []): Event
    {
        return Event::create(array_merge([
            'title' => 'Test Concert',
            'slug' => 'test-concert',
            'event_type' => 'concert',
            'output_type' => 'brochure',
            'event_date' => now()->addDays(10),
            'venue' => 'Test Hall',
            'description' => 'A description long enough to pass validation rules.',
            'brand_color' => '#1B6EF5',
            'template_name' => 'default-concert',
            'status' => Event::STATUS_PENDING_REVIEW,
            'contact_phone' => '09120000000',
        ], $overrides));
    }

    public function test_home_page_loads(): void
    {
        $this->get('/')->assertOk();
    }

    public function test_create_form_loads(): void
    {
        $this->get('/create')->assertOk()->assertSee('Create your event brochure');
    }

    public function test_slug_check_reports_availability(): void
    {
        $this->makeEvent();

        $this->getJson('/slug-check?slug=test-concert')
            ->assertOk()
            ->assertJson(['available' => false]);

        $this->getJson('/slug-check?slug=fresh-slug')
            ->assertOk()
            ->assertJson(['available' => true]);
    }

    public function test_submission_is_stored_and_redirects_to_preview(): void
    {
        $response = $this->post('/events', [
            'title' => 'My New Event',
            'slug' => 'my-new-event',
            'event_type' => 'seminar',
            'output_type' => 'rsvp',
            'event_date' => now()->addWeek()->format('Y-m-d\TH:i'),
            'venue' => 'Main Conference Room',
            'description' => 'This is a long enough description for the form.',
            'brand_color' => '#0F766E',
            'template_name' => 'minimal-corporate',
            'contact_phone' => '09120000001',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('events', [
            'slug' => 'my-new-event',
            'status' => Event::STATUS_PENDING_REVIEW,
        ]);
    }

    public function test_pending_event_requires_signed_link(): void
    {
        $event = $this->makeEvent();

        $this->get('/B/'.$event->slug)->assertForbidden();

        $this->get($event->temporaryPreviewUrl())->assertOk()->assertSee('Test Concert');
    }

    public function test_active_event_is_publicly_visible(): void
    {
        $event = $this->makeEvent(['status' => Event::STATUS_ACTIVE, 'activated_at' => now()]);

        $this->get('/B/'.$event->slug)->assertOk()->assertSee('Test Concert');
    }

    public function test_duplicate_slug_is_rejected(): void
    {
        $this->makeEvent();

        $response = $this->post('/events', [
            'title' => 'Duplicate Slug Event',
            'slug' => 'test-concert',
            'event_type' => 'concert',
            'output_type' => 'brochure',
            'event_date' => now()->addWeek()->format('Y-m-d\TH:i'),
            'venue' => 'Somewhere Hall',
            'description' => 'This is a long enough description for the form.',
            'brand_color' => '#000000',
            'template_name' => 'default-concert',
            'contact_phone' => '09120000002',
        ]);

        $response->assertSessionHasErrors('slug');
    }
}
