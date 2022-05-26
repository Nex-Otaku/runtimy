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
                'next_place_id' => null,
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
                    'sort_index' => $place->getSortIndex(),
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

    public function isComing(): bool
    {
        return $this->orderStatus->phase === self::PHASE_COMING;
    }

    private function isCanceled(): bool
    {
        return $this->orderStatus->phase === self::PHASE_CANCELED;
    }

    public function getStatusLabel(): string
    {
        return self::PHASE_LABELS[$this->orderStatus->phase] ?? '';
    }

    private function getStatusPlaces(): array
    {
        $order = Order::get($this->orderStatus->order_id);
        $places = $order->getPlaces();
        $result = [];

        foreach ($places as $place) {
            /** @var OrderStatusPlace $orderStatusPlace */
            $orderStatusPlace = OrderStatusPlace::where(
                [
                    'order_status_id' => $this->orderStatus->id,
                    'order_place_id' => $place->getModelId(),
                ],
            )->firstOrFail();

            $result [] = [
                'sort_index' => $orderStatusPlace->sort_index,
                'street_address' => $place->getStreetAddress(),
            ];

            if ($orderStatusPlace->is_estimated_coming_time) {
                $result['will_come_at'] = $this->getComingTime($orderStatusPlace);
            }
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

    public function getNextPlaceStreetAddress(): string
    {
        $nextPlaceModel = $this->findNextPlaceModel();

        if ($nextPlaceModel === null) {
            return '';
        }

        $orderPlace = OrderPlace::getByModelId($nextPlaceModel->order_place_id);

        return $orderPlace->getStreetAddress();
    }

    public function getNextPlaceComingTime(): string
    {
        $nextPlaceModel = $this->findNextPlaceModel();

        if ($nextPlaceModel === null) {
            return '';
        }

        return $this->getComingTime($nextPlaceModel);
    }

    private function findNextPlaceModel(): ?OrderStatusPlace
    {
        if ($this->orderStatus->next_place_id === null) {
            return null;
        }

        return OrderStatusPlace::where(['id' => $this->orderStatus->next_place_id])->firstOrFail();
    }

    private function getComingTime(OrderStatusPlace $orderStatusPlace): string
    {
        return 'с '
            . $this->toLocalDayTime($orderStatusPlace->will_come_from)
            . ' до '
            . $this->toLocalDayTime($orderStatusPlace->will_come_to);
    }

    public function setCanceled(): void
    {
        $this->orderStatus->phase = self::PHASE_CANCELED;
        $this->orderStatus->saveOrFail();
    }

    public function setIsComing(): void
    {
        $this->orderStatus->phase = self::PHASE_COMING;
        $this->orderStatus->saveOrFail();
    }

    public function setNextPlace()
    {
        $nextIndex = $this->getNextIndex();

        if ($nextIndex === null) {
            return;
        }

        $nextOrderStatusPlace = OrderStatusPlace::where([
                                                            'order_status_id' => $this->orderStatus->id,
                                                            'sort_index' => $nextIndex,
                                                        ])
                                                ->firstOrFail();

        $this->orderStatus->next_place_id = $nextOrderStatusPlace->id;
        $this->orderStatus->saveOrFail();
    }

    private function getNextIndex(): ?int
    {
        $minIndex = OrderStatusPlace::where(['order_status_id' => $this->orderStatus->id])
                                    ->min('sort_index');

        if ($minIndex === null) {
            return null;
        }

        $maxIndex = OrderStatusPlace::where(['order_status_id' => $this->orderStatus->id])
                                    ->max('sort_index');

        if ($maxIndex === null) {
            return null;
        }

        $currentIndex = $this->orderStatus->next_place_id !== null
            ? OrderStatusPlace::where([
                                          'id' => $this->orderStatus->next_place_id,
                                      ])
                              ->firstOrFail()->sort_index
            : null;

        if ($currentIndex === null) {
            return $minIndex;
        }

        if ($currentIndex === $maxIndex) {
            return null;
        }

        return $currentIndex + 1;
    }
}
