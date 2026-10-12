<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Services\CartService;
use App\Services\OrderService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    protected CartService $cartService;
    protected OrderService $orderService;

    public function __construct(CartService $cartService, OrderService $orderService)
    {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
    }

    public function index(): View|RedirectResponse
    {
        $items = $this->cartService->getItems();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('warning', 'Your shopping cart is empty.');
        }

        $groupedItems = $this->cartService->getItemsGroupedByStore();
        $summary = $this->cartService->getSummary();
        $user = Auth::user();

        return view('checkout.index', compact('groupedItems', 'summary', 'user'));
    }

    public function process(CheckoutRequest $request): RedirectResponse
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

            return redirect()->route('checkout.success', $order->order_number)
                ->with('success', 'Order placed successfully! Vendors will begin fulfillment.');
        } catch (Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function success(string $orderNumber): View
    {
        $order = Order::where('order_number', $orderNumber)
            ->with(['items.store', 'items.product', 'user'])
            ->firstOrFail();

        return view('checkout.success', compact('order'));
    }
}
