<?php

namespace App\Module\Customer\Entities;

use App\Module\Common\Money;
use App\Module\Customer\Models\Order as OrderModel;
use App\Module\Payment\Contracts\PaymentOrder;

class Order implements PaymentOrder
{
    private const TRANSPORT_TYPE_FEET      = 'feet';
    private const TRANSPORT_TYPE_PASSENGER = 'passenger';
    private const TRANSPORT_TYPE_CARGO     = 'cargo';

    private const TRANSPORT_LABELS = [
        self::TRANSPORT_TYPE_FEET => 'Пешком',
        self::TRANSPORT_TYPE_PASSENGER => 'Легковой',
        self::TRANSPORT_TYPE_CARGO => 'Грузовой',
    ];

    private const SIZE_TYPE_SMALL       = 'small';
    private const SIZE_TYPE_MEDIUM      = 'medium';
    private const SIZE_TYPE_LARGE       = 'large';
    private const SIZE_TYPE_EXTRA_LARGE = 'extra-large';

    private const WEIGHT_TYPE_UNDER_1_KG  = '1kg';
    private const WEIGHT_TYPE_UNDER_5_KG  = '5kg';
    private const WEIGHT_TYPE_UNDER_10_KG = '10kg';

    private const WEIGHT_LABELS = [
        self::WEIGHT_TYPE_UNDER_1_KG => 'До 1 кг',
        self::WEIGHT_TYPE_UNDER_5_KG => 'До 5 кг',
        self::WEIGHT_TYPE_UNDER_10_KG => 'До 10 кг',
    ];

    // TODO Сделать тип оплаты заказа
    private const PAYMENT_TYPE = 'Картой онлайн';

    /** @var OrderModel */
    private $order;

    private function __construct(
        OrderModel $order
    ) {
        $this->order = $order;
    }

    public static function create(Customer $customer, array $params): self
    {
        $order = new OrderModel(
            [
                'customer_id' => $customer->getCustomerModelId(),
                'assigned_courier_id' => null,
                'transport_type' => $params['transport_type'] ?? self::TRANSPORT_TYPE_FEET,
                'size_type' => $params['size_type'] ?? self::SIZE_TYPE_SMALL,
                'weight_type' => $params['weight_type'] ?? self::WEIGHT_TYPE_UNDER_1_KG,
                'description' => $params['description'] ?? '',
                'price_of_package' => $params['price_of_package'] ?? null,
                'delivery_price' => null,
            ]
        );

        $order->saveOrFail();
        $entity = new self($order);

        OrderPlace::createPlacesForOrder($entity, $params);
        OrderStatus::create($entity);

        return $entity;
    }

    public function updateFields(array $params): void
    {
        $this->order->updateOrFail([
                                 'transport_type' => $params['transport_type'] ?? $this->order->transport_type,
                                 'size_type' => $params['size_type'] ?? $this->order->size_type,
                                 'weight_type' => $params['weight_type'] ?? $this->order->weight_type,
                                 'description' => $params['description'] ?? $this->order->description,
                                 'price_of_package' => $params['price_of_package'] ?? $this->order->price_of_package,
                             ]);

        foreach ($params['places'] as $place) {
            $orderPlace = $this->getOrderPlaceByIndex($place['sort_index'] ?? 0);
            $orderPlace->updateFields($place);
        }
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

    public static function findForCustomerById(Customer $customer, int $orderModelId): self
    {
        $model = OrderModel::where([
                                       'id' => $orderModelId,
                                       'customer_id' => $customer->getCustomerModelId(),
                                   ])->firstOrFail();

        return new self($model);
    }

    /**
     * @return OrderPlace[]
     */
    public function getPlaces(): array
    {
        return OrderPlace::forOrder($this);
    }

    public function getModelId(): int
    {
        return $this->order->id;
    }

    public static function get(int $modelId): self
    {
        $model = OrderModel::where(['id' => $modelId])->firstOrFail();

        return new self($model);
    }

    /**
     * @param Customer $customer
     * @return Order[]
     */
    public static function findForCustomer(Customer $customer): array
    {
        $items = [];
        $records = OrderModel::where(['customer_id' => $customer->getCustomerModelId()])->get();

        foreach ($records as $record) {
            $items[] = new self($record);
        }

        return $items;
    }

    public function isWaitingForDeliveryPrice(): bool
    {
        return $this->getStatus()->isWaitingForDeliveryPrice();
    }

    public function setDeliveryPrice(Money $price): void
    {
        $this->order->delivery_price = $price->toString();
        $this->order->saveOrFail();
        $this->getStatus()->markPriceWasAssigned();
    }

    public function confirmPayment(): void
    {
        $this->getStatus()->confirmPayment();
    }

    public function serializeInfo(): array
    {
        $orderStatus = $this->getStatus();
        $isAssignedCourier = $this->isAssignedCourier();
        $courier = $this->getAssignedCourier();

        return [
            'order_number' => $this->getModelId(),
            'order_status_label' => $orderStatus->getStatusLabel(),
            'order_price' => $this->getAmount()->toFrontAsString(),
            'is_set_price' => $this->order->delivery_price !== null,
            'is_coming_next_place' => $orderStatus->isComing(),
            'can_be_canceled' => $orderStatus->canBeCanceled(),
            'can_be_edited' => $orderStatus->canBeEdited(),
            'order_next_place_address' => $orderStatus->getNextPlaceStreetAddress(),
            'next_place_coming_time' => $orderStatus->getNextPlaceComingTime(),
            'is_assigned_courier' => $isAssignedCourier,
            'courier_name' => $isAssignedCourier ? $courier->getName() : '',
            'courier_avatar' => $isAssignedCourier ? $courier->getAvatarUrl() : '',
            'courier_phone_number' => $isAssignedCourier ? $courier->getPhoneNumber()->asApiFormat() : '',
            'courier_phone_number_uri' => $isAssignedCourier ? $courier->getPhoneNumber()->asUri() : '',
            'order_created_at' => $this->order->created_at->format('d.m.Y H:i'),
            'transport_type_and_weight_type' =>
                $this->getTransportTypeLabel()
                . ', '
                . $this->getWeightTypeLabel(),
            'description' => $this->order->description,
            'payment_type' => self::PAYMENT_TYPE,
            'places' => $this->serializePlaces(),
        ];
    }

    public function serializeEditFields(): array
    {
        return [
            'order_number' => $this->getModelId(),
            'transport_type' => $this->order->transport_type,
            'size_type' => $this->order->size_type,
            'weight_type' => $this->order->weight_type,
            'price_of_package' => $this->order->price_of_package,
            'description' => $this->order->description,
            'places' => $this->serializePlaces(),
        ];
    }

    private function serializePlaces(): array
    {
        $places = [];
        $sortIndex = 1;

        foreach ($this->getPlaces() as $place) {
            $places[] = [
                'sort_index' => $sortIndex,
                'street_address' => $place->getStreetAddress(),
                'phone_number' => $place->getPhoneNumber()->asDbValue(),
                'courier_comment' => $place->getCourierComment(),
            ];

            $sortIndex++;
        }
        return $places;
    }

    private function getStatus(): OrderStatus
    {
        return OrderStatus::get($this);
    }

    public function getStatusLabel(): string
    {
        return $this->getStatus()->getStatusLabel();
    }

    private function getTransportTypeLabel(): string
    {
        return self::TRANSPORT_LABELS[$this->order->transport_type] ?? '';
    }

    private function getWeightTypeLabel(): string
    {
        return self::WEIGHT_LABELS[$this->order->weight_type] ?? '';
    }

    private function getAssignedCourier(): ?Courier
    {
        if (!$this->isAssignedCourier()) {
            return null;
        }

        return Courier::getByModelId($this->order->assigned_courier_id);
    }

    private function isAssignedCourier(): bool
    {
        return $this->order->assigned_courier_id !== null;
    }

    public function cancel(): void
    {
        $this->getStatus()->setCanceled();
    }

    public function assignCourier(Courier $courier): void
    {
        $this->order->assigned_courier_id = $courier->getModelId();
        $this->order->saveOrFail();

        $this->getStatus()->setIsComing();
        $this->getStatus()->setNextPlace();
    }

    private function getOrderPlaceByIndex(int $sortIndex): OrderPlace
    {
        return OrderPlace::forOrderWithIndex($this, $sortIndex);
    }

    public function getCustomerId(): int
    {
        return $this->order->customer_id;
    }

    public function getAmount(): Money
    {
        return Money::createFromString($this->order->delivery_price ?? '0');
    }

    public function getRouteLabel(): string
    {
        return $this->getStatus()->getRouteLabel();
    }

    public function isPostPayment(): bool
    {
        // Временно мы затачиваемся на юриков, поэтому все заказы у нас идут по постоплате.
        // Когда будут физики то сделаем различие по профилю клиента.
        // Тип "постоплата" или "предоплата" должны быть записаны в заказ.

        return true;
    }

    public function isWaitingForCourierAssign(): bool
    {
        return $this->getStatus()->isWaitingForCourierAssign();
    }

    public function canBeCanceled(): bool
    {
        return $this->getStatus()->canBeCanceled();
    }
}
