<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CustomerDashboardController extends Controller
{
    public function orders(): View
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['items.store', 'items.product'])
            ->latest()
            ->paginate(10);

        return view('customer.orders', compact('orders'));
    }

    public function orderDetail(string $orderNumber): View
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->with(['items.store', 'items.product', 'items.review'])
            ->firstOrFail();

        return view('customer.order-detail', compact('order'));
    }

    public function storeReview(ReviewRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $productId = (int) $request->product_id;

        // Check verified purchase
        $orderItem = \App\Models\OrderItem::where('product_id', $productId)
            ->whereHas('order', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->latest()
            ->first();

        // Update or create review
        Review::updateOrCreate(
            [
                'user_id' => $user->id,
                'product_id' => $productId,
            ],
            [
                'order_item_id' => $orderItem ? $orderItem->id : null,
                'rating' => $request->rating,
                'title' => $request->title,
                'comment' => $request->comment,
                'is_approved' => true,
            ]
        );

        return back()->with('success', 'Thank you for submitting your product review!');
    }

    public function profile(): View
    {
        $user = Auth::user();
        return view('customer.profile', compact('user'));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:25'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        $user->update($request->only('name', 'phone', 'address'));

        return back()->with('success', 'Profile updated successfully!');
    }
}
