<?php

namespace App\Module\Customer\Entities;

use App\Models\Order as OrderModel;

class Order
{
    private const TRANSPORT_TYPE_FEET = 'feet';
    private const TRANSPORT_TYPE_PASSENGER = 'passenger';
    private const TRANSPORT_TYPE_CARGO = 'cargo';

    private const SIZE_TYPE_SMALL = 'small';
    private const SIZE_TYPE_MEDIUM = 'medium';
    private const SIZE_TYPE_LARGE = 'large';
    private const SIZE_TYPE_EXTRA_LARGE = 'extra-large';

    private const WEIGHT_TYPE_UNDER_1_KG = '1kg';
    private const WEIGHT_TYPE_UNDER_5_KG = '5kg';
    private const WEIGHT_TYPE_UNDER_10_KG = '10kg';

    /** @var OrderModel */
    private $order;

    private function __construct(
        OrderModel $order
    )
    {
        $this->order = $order;
    }

    public static function create(array $params): self
    {
        $order = new OrderModel(
            [
                'transport_type' => $params['transport_type'] ?? self::TRANSPORT_TYPE_FEET,
                'size_type' => $params['size_type'] ?? self::SIZE_TYPE_SMALL,
                'weight_type' => $params['weight_type'] ?? self::WEIGHT_TYPE_UNDER_1_KG,
                'description' => $params['description'] ?? '',
                'price_of_package' => $params['price_of_package'] ?? null,
            ]
        );

        $order->saveOrFail();

        return new self($order);
    }

    public static function getTransportTypes(): array
    {
        return [
            self::TRANSPORT_TYPE_FEET,
            self::TRANSPORT_TYPE_PASSENGER,
            self::TRANSPORT_TYPE_CARGO,
        ];
    }

    public static function getSizeTypes(): array
    {
        return [
            self::SIZE_TYPE_SMALL,
            self::SIZE_TYPE_MEDIUM,
            self::SIZE_TYPE_LARGE,
            self::SIZE_TYPE_EXTRA_LARGE,
        ];
    }

    public static function getWeightTypes(): array
    {
        return [
            self::WEIGHT_TYPE_UNDER_1_KG,
            self::WEIGHT_TYPE_UNDER_5_KG,
            self::WEIGHT_TYPE_UNDER_10_KG,
        ];
    }
}
