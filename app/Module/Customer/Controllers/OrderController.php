<?php

namespace App\Module\Customer\Controllers;

use App\Http\Controllers\Controller;
use App\Module\Customer\Entities\Customer;
use App\Module\Customer\Entities\Order;
use App\Module\Customer\Entities\OrderStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function newOrder(Request $request)
    {
        try {
            $order = Order::create(Customer::takeLogined(), $request->post());
            $order->confirmPayment();
            $order->assignCourier();
        } catch (\Throwable $throwable) {
            return $this->failResponse($throwable->getMessage());
        }

        return $this->successResponse();
    }

    public function getOrderStatusList()
    {
        try {
            $orderStatuses = $this->serializeStatusList(OrderStatus::findActiveForCustomer(Customer::takeLogined()));
        } catch (\Throwable $throwable) {
            return $this->failResponse($throwable->getMessage());
        }

        return $this->successResponse(
            $orderStatuses
        );
    }

    public function viewOrder(int $id)
    {
        try {
            $order = Order::findForCustomerById(Customer::takeLogined(), $id);
        } catch (\Throwable $throwable) {
            return $this->failResponse($throwable->getMessage());
        }

        return $this->successResponse(
            $order->serializeInfo()
        );
    }

    public function cancelOrder(int $id)
    {
        try {
            $order = Order::findForCustomerById(Customer::takeLogined(), $id);
            $order->cancel();
        } catch (\Throwable $throwable) {
            return $this->failResponse($throwable->getMessage());
        }

        return $this->successResponse();
    }

    /**
     * @param OrderStatus[] $items
     * @return array
     */
    private function serializeStatusList(array $items): array
    {
        $result = [];
        $sortIndex = 1;

        foreach ($items as $item) {
            $itemSerialized = $item->serialize();
            $itemSerialized['sort_index'] = $sortIndex;
            $result [] = $itemSerialized;
            $sortIndex++;
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
