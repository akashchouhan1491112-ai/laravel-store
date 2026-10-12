<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index(): View
    {
        $groupedItems = $this->cartService->getItemsGroupedByStore();
        $summary = $this->cartService->getSummary();

        return view('cart.index', compact('groupedItems', 'summary'));
    }

    public function add(Request $request): JsonResponse|RedirectResponse
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $productId = (int) $request->input('product_id');
        $quantity = (int) $request->input('quantity', 1);

        $result = $this->cartService->addItem($productId, $quantity);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json($result, $result['success'] ? 200 : 422);
        }

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        return back()->with('success', $result['message']);
    }

    public function update(Request $request, int $id): JsonResponse|RedirectResponse
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:0', 'max:100'],
        ]);

        $result = $this->cartService->updateQuantity($id, (int) $request->quantity);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json($result, $result['success'] ? 200 : 422);
        }

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        return redirect()->route('cart.index')->with('success', $result['message']);
    }

    public function remove(Request $request, int $id): JsonResponse|RedirectResponse
    {
        $result = $this->cartService->removeItem($id);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json($result);
        }

        return redirect()->route('cart.index')->with('success', $result['message']);
    }

    public function clear(): RedirectResponse
    {
        $this->cartService->clear();
        return redirect()->route('cart.index')->with('info', 'Shopping cart cleared.');
    }

    public function applyCoupon(Request $request): JsonResponse|RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'max:50'],
        ]);

        $result = $this->cartService->applyCoupon($request->code);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json($result, $result['success'] ? 200 : 422);
        }

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        return back()->with('success', $result['message']);
    }

    public function removeCoupon(): RedirectResponse
    {
        $this->cartService->removeCoupon();
        return back()->with('info', 'Coupon removed.');
    }
}
