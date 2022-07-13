<?php

namespace App\Module\Customer\Entities;

use App\Module\Common\ModuleSystem;
use App\Module\Common\PhoneNumber;
use App\Module\Customer\Contracts\CourierAccount;
use App\Module\Customer\Models\Courier as CourierModel;
use Faker\Factory;

class Courier implements CourierAccount
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

        return self::create(
            $faker->name(),
            $faker->imageUrl(),
            PhoneNumber::fromFakerString($faker->phoneNumber())
        );
    }

    public static function createFromLk(
        string $name,
        PhoneNumber $phoneNumber
    ): self
    {
        return self::create(
            $name,
            null,
            $phoneNumber
        );
    }

    private static function create(
        string $name,
        ?string $url,
        PhoneNumber $phoneNumber
    ): self
    {
        $courierAccountRegistry = ModuleSystem::instance()->getCourierAccountRegistry();
        $courierAccount = $courierAccountRegistry->registerCourier();

        $courier = new CourierModel(
            [
                'user_id' => $courierAccount->getUserId(),
                'name' => $name,
                'avatar_url' => $url,
                'phone_number' => $phoneNumber->asDbValue(),
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

    public function remove(): void
    {
        $courierAccountRegistry = ModuleSystem::instance()->getCourierAccountRegistry();
        $courierAccountRegistry->removeCourier($this);
        $this->courier->delete();
    }

    public function getUserId(): int
    {
        return $this->courier->user_id;
    }
}
