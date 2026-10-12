@extends('vendor.layout')

@section('title', 'Store Settings')

@section('vendor-content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-heading font-extrabold text-2xl text-slate-900 tracking-tight">Store Profile & Banking</h1>
            <p class="text-xs text-slate-600 mt-1">Configure your public storefront branding and payout receiving account</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200/80 p-8 shadow-sm">
        <form action="{{ route('vendor.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <!-- Section 1: Store Branding -->
            <div class="space-y-4">
                <h3 class="font-heading font-bold text-sm text-slate-900 uppercase tracking-wider text-indigo-600 border-b border-slate-100 pb-2">
                    1. Storefront Branding
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Store / Brand Name *</label>
                        <input type="text" name="name" value="{{ old('name', $store->name) }}" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Store Tagline</label>
                        <input type="text" name="tagline" value="{{ old('tagline', $store->tagline) }}" placeholder="e.g. Handcrafted ceramics & ambient lighting"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Store Description / Bio</label>
                        <textarea name="description" rows="3" placeholder="Tell buyers about your workshop and products..."
                                  class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('description', $store->description) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Store Logo</label>
                        <div class="flex items-center gap-3">
                            <img src="{{ $store->logo_url }}" alt="Logo" class="w-12 h-12 rounded-xl object-cover ring-1 ring-slate-200">
                            <input type="file" name="logo" accept="image/*" class="w-full text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Store Cover Banner</label>
                        <div class="flex items-center gap-3">
                            <img src="{{ $store->banner_url }}" alt="Banner" class="w-20 h-12 rounded-xl object-cover ring-1 ring-slate-200">
                            <input type="file" name="banner" accept="image/*" class="w-full text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Contact Details -->
            <div class="space-y-4">
                <h3 class="font-heading font-bold text-sm text-slate-900 uppercase tracking-wider text-indigo-600 border-b border-slate-100 pb-2">
                    2. Public Contact Details
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Store Support Email</label>
                        <input type="email" name="email" value="{{ old('email', $store->email) }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Store Support Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $store->phone) }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Store Workshop Address</label>
                        <input type="text" name="address" value="{{ old('address', $store->address) }}" placeholder="City, State, Country"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
            </div>

            <!-- Section 3: Bank Account for Payouts -->
            <div class="space-y-4">
                <h3 class="font-heading font-bold text-sm text-slate-900 uppercase tracking-wider text-indigo-600 border-b border-slate-100 pb-2">
                    3. Banking Information for Automated Payouts
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Bank Name</label>
                        <input type="text" name="bank_name" value="{{ old('bank_name', $store->bank_name) }}" placeholder="e.g. Chase Bank, Silicon Valley Bank"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Account Holder Name</label>
                        <input type="text" name="account_name" value="{{ old('account_name', $store->account_name) }}" placeholder="Legal Entity or Personal Name"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Account Number / IBAN</label>
                        <input type="text" name="account_number" value="{{ old('account_number', $store->account_number) }}" placeholder="**** **** 4892"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">SWIFT / Routing Code</label>
                        <input type="text" name="swift_code" value="{{ old('swift_code', $store->swift_code) }}" placeholder="e.g. CHASUS33"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end">
                <button type="submit" class="px-7 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition">
                    Save Storefront Settings
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
