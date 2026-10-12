<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\StoreRegistrationRequest;
use App\Models\Store;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class VendorSettingsController extends Controller
{
    public function edit(): View
    {
        $user = Auth::user();
        $store = $user->store ?? new Store(['user_id' => $user->id]);

        return view('vendor.settings', compact('store', 'user'));
    }

    public function update(StoreRegistrationRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $store = $user->store;

        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('stores/logos', 'public');
        }

        if ($request->hasFile('banner')) {
            $data['banner'] = $request->file('banner')->store('stores/banners', 'public');
        }

        if ($store) {
            $store->update($data);
        } else {
            $data['user_id'] = $user->id;
            $data['status'] = 'pending';
            $store = Store::create($data);
        }

        return back()->with('success', 'Store settings and payout details updated successfully!');
    }
}
