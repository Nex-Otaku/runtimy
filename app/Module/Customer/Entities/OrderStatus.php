<?php

namespace App\Module\Customer\Entities;

use App\Models\OrderStatus as OrderStatusModel;
use App\Models\OrderStatusPlace;

class OrderStatus
{
    private const PHASE_WAITING_FOR_PAYMENT = 'waiting-for-payment';
    private const PHASE_WAITING_FOR_COURIER = 'waiting-for-courier';
    private const PHASE_COMING              = 'coming';
    private const PHASE_CANCELED            = 'canceled';
    private const PHASE_COMPLETED           = 'completed';

    private const PHASE_LABELS = [
        self::PHASE_WAITING_FOR_PAYMENT => 'Ждём оплату',
        self::PHASE_WAITING_FOR_COURIER => 'Ищем курьера',
        self::PHASE_COMING => 'Курьер в пути',
        self::PHASE_CANCELED => 'Отменён',
        self::PHASE_COMPLETED => 'Завершён',
    ];

    /** @var OrderStatusModel */
    private $orderStatus;

    private function __construct(
        OrderStatusModel $orderStatus
    ) {
        $this->orderStatus = $orderStatus;
    }

    public static function create(Order $order): self
    {
        $orderStatus = new OrderStatusModel(
            [
                'order_id' => $order->getModelId(),
                'phase' => self::PHASE_WAITING_FOR_PAYMENT,
            ]
        );

        $orderStatus->saveOrFail();

        $orderPlaces = $order->getPlaces();
        $sortIndex = 1;

        foreach ($orderPlaces as $place) {
            $orderStatusPlace = new OrderStatusPlace(
                [
                    'order_status_id' => $orderStatus->id,
                    'order_place_id' => $place->getModelId(),
                    'is_estimated_coming_time' => false,
                    'will_come_from' => null,
                    'will_come_to' => null,
                ]
            );

            $orderStatusPlace->saveOrFail();
            $sortIndex++;
        }

        return new self($orderStatus);
    }

    /**
     * @param Customer $customer
     * @return OrderStatus[]
     */
    public static function findActiveForCustomer(Customer $customer): array
    {
        $orders = Order::findForCustomer($customer);
        $items = [];

        foreach ($orders as $order) {
            $record = OrderStatusModel::where(['order_id' => $order->getModelId()])
                                      ->where('phase', '<>', self::PHASE_WAITING_FOR_PAYMENT)
                                      ->first();

            if ($record === null) {
                continue;
            }

            $items [] = new self($record);
        }

        return $items;
    }

    public static function get(Order $order): self
    {
        $model = OrderStatusModel::where(['order_id' => $order->getModelId()])->firstOrFail();

        return new self($model);
    }

    public function serialize(): array
    {
        return [
            'order_number' => $this->orderStatus->order_id,
            'isComing' => $this->isComing(),
            'isCanceled' => $this->isCanceled(),
            'label' => $this->getStatusLabel(),
            'places' => $this->getStatusPlaces(),
        ];
    }

    private function isComing(): bool
    {
        return $this->orderStatus->phase === self::PHASE_COMING;
    }

    private function isCanceled(): bool
    {
        return $this->orderStatus->phase === self::PHASE_CANCELED;
    }

    private function getStatusLabel(): string
    {
        return self::PHASE_LABELS[$this->orderStatus->phase] ?? '';
    }

    private function getStatusPlaces(): array
    {
        $order = Order::get($this->orderStatus->order_id);
        $places = $order->getPlaces();
        $result = [];
        $sortIndex = 1;

        foreach ($places as $place) {
            /** @var OrderStatusPlace|null $orderStatusPlace */
            $orderStatusPlace = OrderStatusPlace::where(
                [
                    'order_status_id' => $this->orderStatus->id,
                    'order_place_id' => $place->getModelId(),
                ],
            )->firstOrFail();

            $result [] = [
                'sort_index' => $sortIndex,
                'street_address' => $place->getStreetAddress(),
            ];

            if ($orderStatusPlace->is_estimated_coming_time) {
                $result['will_come_at'] = 'с '
                    . $this->toLocalDayTime($orderStatusPlace->will_come_from)
                    . ' до '
                    . $this->toLocalDayTime($orderStatusPlace->will_come_to);
            }

            $sortIndex++;
        }

        return $result;
    }

    private function toLocalDayTime($time)
    {
        return '{' . var_export($time, true) . '}';
    }

    public function confirmPayment(): void
    {
        if ($this->orderStatus->phase === self::PHASE_WAITING_FOR_COURIER) {
            return;
        }

        if ($this->orderStatus->phase !== self::PHASE_WAITING_FOR_PAYMENT) {
            throw new \LogicException('Нельзя оплатить заказ, который не ожидает оплаты');
        }

        $this->orderStatus->phase = self::PHASE_WAITING_FOR_COURIER;
        $this->orderStatus->saveOrFail();
    }
}
