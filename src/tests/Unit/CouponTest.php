<?php

use App\Jobs\ProcessPayment;
use App\Models\Batch;
use App\Models\Coupon;
use App\Models\Event;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Sector;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(Tests\TestCase::class, RefreshDatabase::class);

it('can be created and persisted via factory', function () {
    $coupon = Coupon::factory()->create();

    expect(Coupon::count())->toBe(1)
        ->and($coupon)->toBeInstanceOf(Coupon::class)
        ->and(is_string($coupon->code))->toBeTrue()
        ->and(is_float($coupon->discount))->toBeTrue();
});

it('allows mass assignment of fillable attributes', function () {
    $data = [
        'code' => 'MASS123',
        'discount' => 20.0,
        'valid_from' => '2025-06-14',
        'valid_until' => '2025-06-15',
        'max_uses' => 3,
    ];

    $coupon = Coupon::create($data);

    // Only assert primitives; carbon casts tested separately
    expect([
        'code' => $coupon->code,
        'discount' => $coupon->discount,
        'max_uses' => $coupon->max_uses,
    ])->toEqual([
                'code' => $data['code'],
                'discount' => $data['discount'],
                'max_uses' => $data['max_uses'],
            ]);
});

it('casts valid_from and valid_until to Carbon dates', function () {
    $coupon = Coupon::factory()->create([
        'valid_from' => '2025-01-01',
        'valid_until' => '2025-12-31',
    ]);

    expect($coupon->valid_from)
        ->toBeInstanceOf(Carbon::class)
        ->and($coupon->valid_from->format('Y-m-d'))->toBe('2025-01-01')
        ->and($coupon->valid_until)
        ->toBeInstanceOf(Carbon::class)
        ->and($coupon->valid_until->format('Y-m-d'))->toBe('2025-12-31');
});

it('has many tickets after payment processing', function () {
    // 1) Create producer and a fully populated Event
    $producer = User::factory()->create(['role' => 'producer']);

    $event = Event::factory()->create([
        'producer_id' => $producer->id,
        'title' => 'Test Event',
        'description' => 'Just a test',
        'banner_url' => null,
        'date' => Carbon::today()->toDateString(),
        'start_time' => '12:00:00',
        'end_time' => '14:00:00',
        'city' => 'Testville',
        'venue' => 'Test Arena',
    ]);

    // 2) Create a Sector manually (no factory)
    $sector = Sector::create([
        'event_id' => $event->id,
        'name' => 'Test Sector',
    ]);

    $batch = Batch::factory()->create([
        'sector_id' => $sector->id,
        'price' => 100.00,
        'start_date' => Carbon::today()->toDateString(),
        'end_date' => Carbon::today()->addDay()->toDateString(),
        'total_quantity' => 50,
    ]);
    $order = Order::factory()->create();

    // 4) The coupon under test
    $coupon = Coupon::factory()->create();

    // 5) Add an OrderItem that uses this coupon for 3 tickets
    $order->items()->create([
        'batch_id' => $batch->id,
        'quantity' => 3,
        'unit_price' => $batch->price,
        'coupon_id' => $coupon->id,
        'discount_value' => 0,
    ]);

    // 6) Fake a successful payment
    $payment = Payment::factory()->create([
        'order_id' => $order->id,
        'status' => 'success',
    ]);

    // 7) Process payment synchronously—marks as paid and generates tickets
    ProcessPayment::dispatchSync($payment->id);

    // 8) Assert that the coupon now has 3 tickets
    expect($coupon->tickets)
        ->toHaveCount(3)
        ->and($coupon->tickets->first())->toBeInstanceOf(Ticket::class);
});
