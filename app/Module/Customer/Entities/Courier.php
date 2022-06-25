<?php

namespace App\Module\Customer\Entities;

use App\Models\User;
use App\Module\Common\PhoneNumber;
use App\Module\Customer\Models\Courier as CourierModel;
use App\Module\PasswordAuth\Models\PasswordAccount;
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

        $user = new User();
        $user->saveOrFail();

        $passwordAccount = new PasswordAccount(
            [
                'user_id' => $user->id,
                'email' => $faker->email(),
                'name' => $faker->name(),
                'password' => bcrypt('secret'),
            ]
        );

        $passwordAccount->saveOrFail();

        $courier = new CourierModel(
            [
                'user_id' => $user->id,
                'name' => $passwordAccount->name,
                'avatar_url' => $faker->imageUrl(),
                'phone_number' => PhoneNumber::fromFakerString($faker->phoneNumber())->asDbValue(),
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

    public static function getByModelId(int $courierId): self
    {
        $model = CourierModel::where(['id' => $courierId])->firstOrFail();

        return new self($model);
    }

    public function getName(): string
    {
        return $this->courier->name;
    }

    public function getAvatarUrl(): string
    {
        return $this->courier->avatar_url;
    }

    public function getPhoneNumber(): PhoneNumber
    {
        return PhoneNumber::fromDb($this->courier->phone_number);
    }

    public function getModelId(): int
    {
        return $this->courier->id;
    }
}
