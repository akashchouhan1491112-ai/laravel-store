@extends('layouts.app')

@section('title', 'Become a Vendor - Open Your Marketplace Store')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-12">
    
    <div class="bg-white rounded-3xl border border-slate-200/80 p-8 sm:p-10 shadow-sm space-y-8">
        
        <div class="text-center space-y-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-500/10 text-amber-700 text-xs font-bold uppercase border border-amber-500/20">
                ⭐ Merchant Onboarding
            </span>
            <h1 class="font-heading font-extrabold text-3xl text-slate-900 tracking-tight">Open Your Vendor Store</h1>
            <p class="text-xs text-slate-500 max-w-md mx-auto leading-relaxed">
                Join our curated community of verified makers. Once submitted, your store will be reviewed by platform admins.
            </p>
        </div>

        <form action="{{ route('register.post') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="role" value="vendor">

            <div class="space-y-4">
                <h3 class="font-heading font-bold text-sm text-slate-900 uppercase tracking-wider text-indigo-600 border-b border-slate-100 pb-2">
                    1. Account & Owner Details
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Owner Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('name')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Owner Email *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('email')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Business Address</label>
                        <input type="text" name="address" value="{{ old('address') }}" placeholder="City, State, Country"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <h3 class="font-heading font-bold text-sm text-slate-900 uppercase tracking-wider text-indigo-600 border-b border-slate-100 pb-2">
                    2. Storefront Identity
                </h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Store / Brand Name *</label>
                        <input type="text" name="store_name" value="{{ old('store_name') }}" required placeholder="e.g. Apex Audio Labs, Nordic Crafts"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('store_name')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Store Tagline / Bio</label>
                        <textarea name="store_description" rows="2" placeholder="Describe the types of products and craft behind your brand..."
                                  class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('store_description') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <h3 class="font-heading font-bold text-sm text-slate-900 uppercase tracking-wider text-indigo-600 border-b border-slate-100 pb-2">
                    3. Security Credentials
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Password *</label>
                        <input type="password" name="password" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('password')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Confirm Password *</label>
                        <input type="password" name="password_confirmation" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full py-4 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-lg shadow-indigo-600/30 transition">
                Submit Store Application →
            </button>
        </form>

        <div class="text-center text-xs text-slate-500 pt-2 border-t border-slate-100">
            Already registered? <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:underline">Sign into Vendor Portal</a>
        </div>
    </div>

</div>
@endsection
