<?php

namespace App\Module\Customer\Entities;

use App\Models\Order as OrderModel;
use App\Module\Customer\SerializableItem;
use App\Models\OrderPlace;

class Order implements SerializableItem
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

    public static function create(Customer $customer, array $params): self
    {
        $order = new OrderModel(
            [
                'customer_id' => $customer->getSpaUserId(),
                'transport_type' => $params['transport_type'] ?? self::TRANSPORT_TYPE_FEET,
                'size_type' => $params['size_type'] ?? self::SIZE_TYPE_SMALL,
                'weight_type' => $params['weight_type'] ?? self::WEIGHT_TYPE_UNDER_1_KG,
                'description' => $params['description'] ?? '',
                'price_of_package' => $params['price_of_package'] ?? null,
            ]
        );

        $order->saveOrFail();
        $sortIndex = 1;

        foreach ($params['places'] as $placeParams) {
            $place = new OrderPlace(
                [
                    'order_id' => $order->id,
                    'sort_index' => $sortIndex,
                    'street_address' => $placeParams['street_address'] ?? '',
                    'phone_number' => $placeParams['phone_number'] ?? '',
                    'courier_comment' => $placeParams['courier_comment'] ?? '',
                ]
            );

            $place->saveOrFail();

            $sortIndex++;
        }

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

    /**
     * @param Customer $customer
     * @return Order[]
     */
    public static function findForCustomer(Customer $customer): array
    {
        $items = [];
        $records = OrderModel::where(['customer_id' => $customer->getSpaUserId()])->get();

        foreach ($records as $record) {
            $items []= new self($record);
        }

        return $items;
    }

    public function serialize(): array
    {
        return [
            'transport_type' => $this->order->transport_type,
            'size_type' => $this->order->size_type,
            'weight_type' => $this->order->weight_type,
            'description' => $this->order->description,
            'price_of_package' => $this->order->price_of_package,
        ];
    }
}
