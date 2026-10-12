<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewRequest;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerApiController extends Controller
{
    public function orders(Request $request): JsonResponse
    {
        $orders = Order::where('user_id', $request->user()->id)
            ->with(['items.store', 'items.product'])
            ->latest()
            ->paginate(10);

        return response()->json($orders);
    }

    public function orderDetail(Request $request, string $orderNumber): JsonResponse
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', $request->user()->id)
            ->with(['items.store', 'items.product', 'items.review'])
            ->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found.'], 404);
        }

        return response()->json($order);
    }

    public function submitReview(ReviewRequest $request): JsonResponse
    {
        $user = $request->user();
        $productId = (int) $request->product_id;

        $orderItem = \App\Models\OrderItem::where('product_id', $productId)
            ->whereHas('order', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->latest()
            ->first();

        $review = Review::updateOrCreate(
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

        return response()->json([
            'message' => 'Review submitted successfully.',
            'review' => $review,
        ]);
    }
}
