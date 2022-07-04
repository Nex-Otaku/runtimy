<?php

namespace Database\Factories;

use App\Module\Customer\Entities\Order as OrderEntity;
use App\Module\Customer\Models\Order;
use App\Module\Customer\Models\OrderPlace;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Module\Customer\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Order $order) {
            OrderPlace::factory()
                      ->count(1)
                      ->for($order)
                      ->sortIndex(1)
                      ->create();

            OrderPlace::factory()
                      ->count(1)
                      ->for($order)
                      ->sortIndex(2)
                      ->create();
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'customer_id' => 1,
            'transport_type' => $this->faker->randomElement(OrderEntity::getTransportTypes()),
            'size_type' => $this->faker->randomElement(OrderEntity::getSizeTypes()),
            'weight_type' => $this->faker->randomElement(OrderEntity::getWeightTypes()),
            'description' => $this->faker->text(),
            'price_of_package' => null,
            'delivery_price' => null,
        ];
    }
}
