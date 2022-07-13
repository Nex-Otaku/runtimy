<?php

namespace App\Module\Operator\Nova;

use App\Module\Admin\Access\LkAccess;
use App\Module\Operator\Actions\AssignOrderCourierAction;
use App\Module\Operator\Actions\SetOrderPriceAction;
use App\Nova\Resource;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\ActionRequest;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Module\Customer\Entities\Order as OrderEntity;
use App\Module\Customer\Models\Order as OrderModel;

class Order extends Resource
{
    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Техподдержка';

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return __('Заказы');
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return __('Заказ');
    }

    /**
     * Get the text for the create resource button.
     *
     * @return string|null
     */
    public static function createButtonLabel()
    {
        return __('Добавить заказ');
    }

    /**
     * Get the text for the update resource button.
     *
     * @return string|null
     */
    public static function updateButtonLabel()
    {
        return __('Сохранить');
    }

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Module\Customer\Models\Order::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),

            // Статус
            Text::make('Статус', function () {
                $order = OrderEntity::get($this->id);

                return $order->getStatusLabel();
            }),

            // Маршрут (Список мест через тире)
            Text::make('Маршрут', function () {
                $order = OrderEntity::get($this->id);

                return $order->getRouteLabel();
            }),

            // Стоимость (Нет "прочерк", Сумма)
            Number::make('Стоимость доставки', 'delivery_price')
                ->nullable()
                ->sortable()
                ->rules('required', 'max:255'),

            // Оплачено (Нет "прочерк", Ожидаем "часы", Оплачено "галочка")
            // TODO флаг is_paid
            // TODO поле payment_status none|waiting|post-payment|paid

            // Курьер (Не назначен "прочерк", Назначен "имя")
            BelongsTo::make('Курьер', 'assignedCourier', Courier::class),

            // Покупатель (Телефон - первые и последние цифры +79..45, активная ссылка для звонка)
            BelongsTo::make('Клиент', 'customer', Customer::class),


//            Text::make('Транспорт', 'transport_type')
//                ->sortable()
//                ->rules('required', 'max:255')
//                ->hideFromIndex(),
//
//            Text::make('Габариты', 'size_type')
//                ->sortable()
//                ->rules('required', 'max:255')
//                ->hideFromIndex(),
//
//            Text::make('Вес', 'weight_type')
//                ->sortable()
//                ->rules('required', 'max:255')
//                ->hideFromIndex(),
//
//            Text::make('Что везём', 'description')
//                ->sortable()
//                ->rules('required', 'max:255')
//                ->hideFromIndex(),
//
//            Text::make('Объявленная ценность', 'price_of_package')
//                ->nullable()
//                ->sortable()
//                ->rules('required', 'max:255')
//                ->hideFromIndex(),

        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [
            // 1. Указать стоимость доставки
            SetOrderPriceAction::make()
                ->showInline()
                ->canSee(function ($request) {
                    $isAuthorized = LkAccess::of($request->user()->user_id)->canSetOrderPrice();

                    if ($request instanceof ActionRequest) {
                        return $isAuthorized;
                    }

                    return $isAuthorized
                        && $this->resource instanceof OrderModel
                        && $this->resource->exists
                        && $this->getEntity($this->resource)->isWaitingForDeliveryPrice();
                }),

            // 2. Подтвердить платёж
            // TODO только для физиков, выставить флаг is_paid

            // 3. Назначить курьера
            AssignOrderCourierAction::make()
                ->showInline()
                ->canSee(function ($request) {
                    $isAuthorized = LkAccess::of($request->user()->user_id)->canAssignCourier();

                    if ($request instanceof ActionRequest) {
                        return $isAuthorized;
                    }

                    return $isAuthorized
                        && $this->resource instanceof OrderModel
                        && $this->resource->exists
                        && $this->getEntity($this->resource)->isWaitingForCourierAssign();
                }),
        ];
    }

    private function getEntity(OrderModel $resource): OrderEntity
    {
        return OrderEntity::get($resource->id);
    }
}
