<?php

namespace App\Module\Operator\Nova;

use App\Nova\Resource;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

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
            // TODO

            // Маршрут (Список мест через тире)
            // TODO

            // Стоимость (Нет "прочерк", Сумма)
            // TODO
            Text::make('Стоимость доставки', 'delivery_price')
                ->nullable()
                ->sortable()
                ->rules('required', 'max:255'),

            // Оплачено (Нет "прочерк", Ожидаем "часы", Оплачено "галочка")
            // TODO

            // Курьер (Не назначен "прочерк", Назначен "имя")
            // TODO
//            ID::make(__('Courier ID'), 'assigned_courier_id')
//                ->nullable()
//                ->sortable(),
            BelongsTo::make('Курьер', 'assignedCourier', Courier::class),

            // Покупатель (Телефон - первые и последние цифры +79..45, активная ссылка для звонка)
            // TODO
//            ID::make(__('Customer ID'), 'customer_id')->sortable(),
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
            // TODO

            // 2. Подтвердить платёж
            // TODO

            // 3. Назначить курьера
            // TODO
        ];
    }
}
