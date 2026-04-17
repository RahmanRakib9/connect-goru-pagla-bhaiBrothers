<?php

namespace Tests\Feature\Admin;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_from_admin_events(): void
    {
        $this->get(route('admin.events.index'))->assertRedirect(route('login'));
    }

    public function test_authenticated_non_admin_users_receive_403(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('admin.events.index'))->assertForbidden();
    }

    public function test_admin_can_view_event_index(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get(route('admin.events.index'))->assertOk();
    }

    public function test_admin_can_create_event(): void
    {
        $admin = User::factory()->admin()->create();

        $payload = [
            'title' => 'Spring Market',
            'description' => 'Test details',
            'location' => 'Farm Road 1',
            'starts_at' => '2026-05-01T10:00',
            'ends_at' => '2026-05-01T18:00',
            'is_published' => '1',
        ];

        $this->actingAs($admin)->post(route('admin.events.store'), $payload)
            ->assertRedirect(route('admin.events.index', absolute: false));

        $this->assertDatabaseHas('events', [
            'title' => 'Spring Market',
            'slug' => 'spring-market',
            'location' => 'Farm Road 1',
            'is_published' => true,
        ]);
    }

    public function test_admin_can_update_event(): void
    {
        $admin = User::factory()->admin()->create();
        $starts = now()->addDay()->startOfHour();
        $event = Event::factory()->create([
            'title' => 'Old Title',
            'slug' => 'old-title',
            'starts_at' => $starts,
            'ends_at' => $starts->copy()->addHours(4),
        ]);

        $this->actingAs($admin)->put(route('admin.events.update', $event), [
            'title' => 'New Title',
            'description' => null,
            'location' => $event->location,
            'starts_at' => $event->starts_at->format('Y-m-d\TH:i'),
            'ends_at' => $event->ends_at->format('Y-m-d\TH:i'),
            'is_published' => false,
        ])->assertRedirect(route('admin.events.show', $event));

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'title' => 'New Title',
            'slug' => 'new-title',
        ]);
    }

    public function test_admin_can_delete_event(): void
    {
        $admin = User::factory()->admin()->create();
        $event = Event::factory()->create();

        $this->actingAs($admin)->delete(route('admin.events.destroy', $event))
            ->assertRedirect(route('admin.events.index', absolute: false));

        $this->assertDatabaseMissing('events', ['id' => $event->id]);
    }

    public function test_store_validation_requires_fields(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post(route('admin.events.store'), [])
            ->assertSessionHasErrors(['title', 'location', 'starts_at']);
    }
}
