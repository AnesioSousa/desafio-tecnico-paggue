<?php

namespace Database\Factories;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;

class CouponFactory extends Factory
{
    /**  
     * The name of the factory’s corresponding model.  
     *  
     * @var string  
     */
    protected $model = Coupon::class;

    /**  
     * Define the model’s default state.  
     */
    public function definition(): array
    {
        $from = $this->faker->dateTimeBetween('-1 month', 'now');
        $until = $this->faker->dateTimeBetween('now', '+1 year');

        return [
            'code' => strtoupper($this->faker->unique()->bothify('???###')),
            'discount' => $this->faker->randomFloat(2, 1, 100),
            'valid_from' => $from->format('Y-m-d'),
            'valid_until' => $until->format('Y-m-d'),
            'max_uses' => $this->faker->numberBetween(1, 500),
        ];
    }
}
