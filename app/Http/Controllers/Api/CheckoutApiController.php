<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Services\OrderService;
use Exception;
use Illuminate\Http\JsonResponse;

class CheckoutApiController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function checkout(CheckoutRequest $request): JsonResponse
    {
        try {
            $customerData = $request->only([
                'name',
                'email',
                'phone',
                'address',
                'city',
                'state',
                'zip',
                'country',
                'notes',
            ]);

            $paymentMethod = $request->input('payment_method', 'card');

            $order = $this->orderService->createOrder($customerData, $paymentMethod);

            return response()->json([
                'message' => 'Order created successfully.',
                'order' => $order,
            ], 201);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
