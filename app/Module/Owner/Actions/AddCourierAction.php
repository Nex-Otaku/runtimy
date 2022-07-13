<?php

namespace App\Module\Owner\Actions;

use App\Module\Admin\LkAccountRegistry;
use App\Module\Common\PhoneNumber;
use App\Module\Customer\Entities\Courier;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class AddCourierAction extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * The displayable name of the action.
     *
     * @var string
     */
    public $name = 'Добавить курьера';

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $name = $fields->get('name');
        $phoneNumber = PhoneNumber::fromInputString($fields->get('phone_number'));
        Courier::createFromLk($name, $phoneNumber);
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
            Text::make('Имя', 'name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Телефон', 'phone_number')
                ->sortable()
                ->rules('required', 'max:255'),
        ];
    }
}
