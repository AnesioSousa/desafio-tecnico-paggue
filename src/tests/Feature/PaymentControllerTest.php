<?php

namespace Tests\Feature;


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