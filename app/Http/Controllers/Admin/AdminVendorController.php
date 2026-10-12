<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStatusRequest;
use App\Models\Store;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminVendorController extends Controller
{
    public function index(Request $request): View
    {
        $query = Store::with('user')->withCount('products');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $term = $request->q;
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('email', 'like', "%{$term}%")
                  ->orWhereHas('user', function ($u) use ($term) {
                      $u->where('name', 'like', "%{$term}%")
                        ->orWhere('email', 'like', "%{$term}%");
                  });
            });
        }

        $stores = $query->latest()->paginate(12)->withQueryString();

        return view('admin.vendors.index', compact('stores'));
    }

    public function show(Store $store): View
    {
        $store->load(['user', 'products' => function ($q) {
            $q->latest()->take(10);
        }, 'transactions' => function ($q) {
            $q->latest()->take(10);
        }]);

        return view('admin.vendors.show', compact('store'));
    }

    public function updateStatus(StoreStatusRequest $request, Store $store): RedirectResponse
    {
        $store->update([
            'status' => $request->status,
            'commission_rate' => $request->filled('commission_rate') ? (float) $request->commission_rate : $store->commission_rate,
        ]);

        return back()->with('success', "Vendor '{$store->name}' status updated to '" . ucfirst($request->status) . "'. Commission rate set to {$store->commission_rate}%.");
    }
}
