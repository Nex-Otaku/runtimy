<?php

namespace App\Module\Customer\Controllers;

use App\Http\Controllers\Controller;
use App\Module\Customer\Entities\Customer;
use App\Module\Customer\Entities\Order;
use App\Module\Customer\SerializableItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function newOrder(Request $request)
    {
        try {
            Order::create(Customer::takeLogined(), $request->post());
        } catch (\Throwable $throwable) {
            return $this->failResponse($throwable->getMessage());
        }

        return $this->successResponse();
    }

    public function getOrders()
    {
        try {
            $orders = $this->serializeList(Order::findForCustomer(Customer::takeLogined()));
        } catch (\Throwable $throwable) {
            return $this->failResponse($throwable->getMessage());
        }

        return $this->successResponse(
            $orders
        );
    }

    /**
     * @param SerializableItem[] $items
     * @return array
     */
    private function serializeList(array $items): array
    {
        $result = [];

        foreach ($items as $item) {
            $result [] = $item->serialize();
        }

        return $result;
    }

    private function successResponse(?array $data = null): JsonResponse
    {
        $result = [
            'result' => 'success',
        ];

        if ($data !== null) {
            $result['data'] = $data;
        }

        return response()->json($result);
    }

    private function failResponse(string $error): JsonResponse
    {
        return response()
            ->json([
                       'result' => 'fail',
                       'message' => $error,
                   ], 500);
    }
}
