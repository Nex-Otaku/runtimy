<?php

namespace App\Module\Operator\Nova;

use Laravel\Nova\Http\Requests\NovaRequest;

class ArchivedOrder extends ActiveOrder
{
    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return __('Архив заказов');
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->join('order_statuses', 'orders.id', '=', 'order_statuses.order_id')
            ->where('order_statuses.is_active', 0);
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
