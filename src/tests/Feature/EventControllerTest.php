<?php
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Models\Event;
use Spatie\Permission\PermissionRegistrar;

beforeEach(function () {
    // Clear Spatie’s cache so it reloads the fresh tables
    app(PermissionRegistrar::class)->forgetCachedPermissions();

    // Seed only the roles you need for your policies
    Role::create(['name' => 'producer', 'guard_name' => 'api']);
    Role::create(['name' => 'client', 'guard_name' => 'api']);
});

test('event index page loads for a producer', function () {
    $producer = User::factory()->create();
    $producer->assignRole('producer');

    $this
        ->actingAs($producer, 'api')
        ->getJson('/api/v1/events')
        ->assertOk()
        ->assertJson([]);
});

test('event index page is forbidden for a client', function () {
    $client = User::factory()->create();
    $client->assignRole('client');

    $this
        ->actingAs($client, 'api')
        ->getJson('/api/v1/events')
        ->assertForbidden();
});

test('event index page is forbidden for an unauthenticated user', function () {
    $this
        ->getJson('/api/v1/events')
        ->assertUnauthorized();
});

test('lets a producer create an event', function () {
    $producer = User::factory()->create();
    $producer->assignRole('producer');

    $this
        ->actingAs($producer, 'api')
        ->postJson('/api/v1/events', [
            'title' => 'My Party',
            'description' => 'Fun!',
            'date' => '2025-10-10',
            'start_time' => '18:00:00',
            'end_time' => '20:00:00',
            'city' => 'São Paulo',
            'venue' => 'Club',
        ])
        ->assertCreated()
        ->assertJsonPath('producer_id', $producer->id);

    expect(Event::count())->toBe(1);
});

test('denies a client creating an event', function () {
    $client = User::factory()->create();
    $client->assignRole('client');

    $this
        ->actingAs($client, 'api')
        ->postJson('/api/v1/events', [
            'title' => '',
        ])
        ->assertForbidden();
});