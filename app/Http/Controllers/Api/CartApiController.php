<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartApiController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index(): JsonResponse
    {
        return response()->json([
            'items' => $this->cartService->getItems(),
            'grouped_by_store' => $this->cartService->getItemsGroupedByStore(),
            'summary' => $this->cartService->getSummary(),
        ]);
    }

    public function add(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $result = $this->cartService->addItem(
            (int) $request->product_id,
            (int) $request->input('quantity', 1)
        );

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        $result = $this->cartService->updateQuantity($id, (int) $request->quantity);

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    public function remove(int $id): JsonResponse
    {
        $result = $this->cartService->removeItem($id);
        return response()->json($result);
    }

    public function clear(): JsonResponse
    {
        $this->cartService->clear();
        return response()->json(['message' => 'Cart cleared.']);
    }

    public function applyCoupon(Request $request): JsonResponse
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        $result = $this->cartService->applyCoupon($request->code);
        return response()->json($result, $result['success'] ? 200 : 422);
    }
}
