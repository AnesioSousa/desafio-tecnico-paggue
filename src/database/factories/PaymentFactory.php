<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class PaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Payment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Associate with an order (will create one via OrderFactory)
            'order_id' => Order::factory(),

            // Required unique external identifier
            'external_id' => (string) Str::uuid(),

            // Payer info
            'payer_name' => $this->faker->name(),

            // Amount in cents
            'amount' => $this->faker->numberBetween(100, 100000),

            // Optional description
            'description' => $this->faker->sentence(),

            // Raw PIX payload (stored as JSON)
            'pix_payload' => [],

            // The actual PIX code or identifier
            'payment' => $this->faker->regexify('[A-Z0-9]{20}'),

            // End-to-end and reference IDs
            'end_to_end_id' => (string) Str::uuid(),
            'reference' => $this->faker->word(),

            // Default status
            'status' => 'waiting',

            // Expiration and paid timestamps
            'expiration_at' => Carbon::now()->addHour(),
            'paid_at' => null,
        ];
    }
}
