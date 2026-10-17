<?php

namespace Tests\Feature;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminFlowTest extends TestCase
{
    use RefreshDatabase;

    private function makeEvent(array $overrides = []): Event
    {
        return Event::create(array_merge([
            'title' => 'Review Me',
            'slug' => 'review-me',
            'event_type' => 'concert',
            'output_type' => 'brochure',
            'event_date' => now()->addDays(5),
            'venue' => 'Test Hall',
            'description' => 'A description long enough to pass validation rules.',
            'brand_color' => '#1B6EF5',
            'template_name' => 'default-concert',
            'status' => Event::STATUS_PENDING_REVIEW,
            'contact_phone' => '09120000000',
        ], $overrides));
    }

    private function loginAdmin(): void
    {
        $this->post('/admin/login', [
            'username' => config('negah.admin.username'),
            'password' => config('negah.admin.password'),
        ]);
    }

    public function test_admin_requires_login(): void
    {
        $this->get('/admin/events')->assertRedirect(route('admin.login'));
    }

    public function test_login_and_view_dashboard(): void
    {
        $this->loginAdmin();
        $this->get('/admin')->assertOk();
    }

    public function test_activation_locks_slug_and_goes_live(): void
    {
        $event = $this->makeEvent();
        $this->loginAdmin();

        $this->post('/admin/events/'.$event->id.'/status', ['status' => 'active'])
            ->assertRedirect(route('admin.events.show', $event));

        $event->refresh();
        $this->assertSame(Event::STATUS_ACTIVE, $event->status);
        $this->assertNotNull($event->activated_at);
        $this->assertTrue($event->isSlugLocked());

        // Now publicly visible without a signature.
        $this->get('/B/'.$event->slug)->assertOk();
    }

    public function test_slug_cannot_change_once_active(): void
    {
        $event = $this->makeEvent(['status' => Event::STATUS_ACTIVE, 'activated_at' => now()]);
        $this->loginAdmin();

        $this->put('/admin/events/'.$event->id, [
            'title' => 'Renamed Event',
            'slug' => 'renamed-slug',
            'event_type' => 'concert',
            'output_type' => 'brochure',
            'event_date' => now()->addDays(5)->format('Y-m-d\TH:i'),
            'venue' => 'Test Hall',
            'description' => 'A description long enough to pass validation rules.',
            'brand_color' => '#1B6EF5',
            'template_name' => 'default-concert',
            'contact_phone' => '09120000000',
        ])->assertRedirect(route('admin.events.show', $event));

        $event->refresh();
        $this->assertSame('review-me', $event->slug);      // locked
        $this->assertSame('Renamed Event', $event->title); // editable
    }
}
