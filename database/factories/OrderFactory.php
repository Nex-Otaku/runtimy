<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Module\Customer\Entities\Order as OrderEntity;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'transport_type' => $this->faker->randomElement(OrderEntity::getTransportTypes()),
            'size_type' => $this->faker->randomElement(OrderEntity::getSizeTypes()),
            'weight_type' => $this->faker->randomElement(OrderEntity::getWeightTypes()),
            'description' => $this->faker->text(),
            'price_of_package' => null,
        ];
    }
}
