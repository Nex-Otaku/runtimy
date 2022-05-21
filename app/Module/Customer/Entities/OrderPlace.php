<?php

namespace App\Module\Customer\Entities;

use App\Models\OrderPlace as OrderPlaceModel;

class OrderPlace
{
    /** @var OrderPlaceModel */
    private $orderPlace;

    private function __construct(
        OrderPlaceModel $orderPlace
    )
    {
        $this->orderPlace = $orderPlace;
    }

    public static function createPlacesForOrder(Order $order, array $params): void
    {
        $sortIndex = 1;

        foreach ($params['places'] as $placeParams) {
            $place = new OrderPlaceModel(
                [
                    'order_id' => $order->getModelId(),
                    'sort_index' => $sortIndex,
                    'street_address' => $placeParams['street_address'] ?? '',
                    'phone_number' => $placeParams['phone_number'] ?? '',
                    'courier_comment' => $placeParams['courier_comment'] ?? '',
                ]
            );

            $place->saveOrFail();
            $sortIndex++;
        }
    }

    /**
     * @param Order $order
     * @return self[]
     */
    public static function forOrder(Order $order): array
    {
        $items = [];
        $records = OrderPlaceModel::where(['order_id' => $order->getModelId()])->get();

        foreach ($records as $record) {
            $items []= new self($record);
        }

        return $items;
    }

    public function getModelId(): int
    {
        return $this->orderPlace->id;
    }

    public function getStreetAddress(): string
    {
        return $this->orderPlace->street_address;
    }
}
