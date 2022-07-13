<?php

namespace App\Module\Operator\Actions;

use App\Module\Customer\Entities\Courier;
use App\Module\Customer\Entities\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class AssignOrderCourierAction extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * The displayable name of the action.
     *
     * @var string
     */
    public $name = 'Назначить курьера';

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $courier = Courier::getByModelId($fields->get('courier_id'));

        foreach ($models as $model) {
            Order::get($model->id)->assignCourier($courier);
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
            Select::make('Курьер', 'courier_id')
                  ->options(Courier::listCourierOptions())
                  ->rules('required'),
        ];
    }
}
