@extends('layouts.app')

@section('title', 'My Account Profile')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="mb-8">
        <h1 class="font-heading font-extrabold text-3xl text-slate-900 tracking-tight">Account Profile</h1>
        <p class="text-xs text-slate-600 mt-1">Manage your customer contact details and default shipping address</p>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200/80 p-8 shadow-sm space-y-6">
        <form action="{{ route('customer.profile.update') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Full Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email Address (Read-only)</label>
                <input type="email" value="{{ $user->email }}" disabled
                       class="w-full px-3.5 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-xs text-slate-600 cursor-not-allowed">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Phone Number</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Default Shipping Address</label>
                <textarea name="address" rows="3" placeholder="Street, City, State, ZIP..."
                          class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('address', $user->address) }}</textarea>
            </div>

            <button type="submit" class="px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition">
                Save Profile Changes
            </button>
        </form>
    </div>

</div>
@endsection
