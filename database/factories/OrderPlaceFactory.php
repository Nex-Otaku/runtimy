<?php

namespace Database\Factories;

use App\Models\OrderPlace;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderPlace>
 */
class OrderPlaceFactory extends Factory
{
    protected $model = OrderPlace::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'sort_index' => 0,
            'street_address' => $this->faker->streetAddress,
            'phone_number' => $this->faker->phoneNumber,
            'courier_comment' => $this->faker->text(),
        ];
    }

    public function sortIndex(int $sortIndex)
    {
        return $this->state(function (array $attributes) use ($sortIndex) {
            return [
                'sort_index' => $sortIndex,
            ];
        });
    }
}
