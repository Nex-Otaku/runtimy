<?php

namespace App\Module\Operator\Actions;

use App\Module\Common\Money;
use App\Module\Customer\Entities\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Http\Requests\NovaRequest;

class SetOrderPriceAction extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * The displayable name of the action.
     *
     * @var string
     */
    public $name = 'Указать стоимость';

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $price = Money::createFromString($fields->get('price'));

        foreach ($models as $model) {
            Order::get($model->id)->setDeliveryPrice($price);
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Number::make('Стоимость доставки', 'price')
                ->rules('required', 'max:255'),
        ];
    }
}
