<?php

namespace App\Module\Customer\Entities;

use App\Models\Courier as CourierModel;
use App\Models\User;
use Faker\Factory;

class Courier
{
    /** @var CourierModel */
    private $courier;

    private function __construct(
        CourierModel $Courier
    ) {
        $this->courier = $Courier;
    }

    public static function createRandom(): self
    {
        $faker = Factory::create();

        /** @var User $user */
        $user = User::firstOrCreate(
            [
                'email' => $faker->email,
            ],
            [
                'name' => $faker->name,
                'password' => bcrypt('secret'),
            ]
        );

        $courier = new CourierModel(
            [
                'user_id' => $user->id,
                'name' => $user->name,
                'avatar_url' => $faker->imageUrl,
                'phone_number' => $faker->phoneNumber,
            ]
        );

        $courier->saveOrFail();

        return new self($courier);
    }

    public static function findFirstAvailableCourier(): ?self
    {
        $courier = CourierModel::first();

        if ($courier === null) {
            return null;
        }

        return new self($courier);
    }
}
