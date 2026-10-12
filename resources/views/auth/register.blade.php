@extends('layouts.app')

@section('title', 'Create Buyer Account')

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    
    <div class="bg-white rounded-3xl border border-slate-200/80 p-8 shadow-sm space-y-6">
        <div class="text-center space-y-1">
            <h1 class="font-heading font-extrabold text-2xl text-slate-900 tracking-tight">Create Customer Account</h1>
            <p class="text-xs text-slate-500">Shop from independent makers with seamless tracking</p>
        </div>

        <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="role" value="customer">

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @error('name')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @error('email')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Phone Number (Optional)</label>
                <input type="text" name="phone" value="{{ old('phone') }}"
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Password</label>
                <input type="password" name="password" required
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @error('password')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" required
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <button type="submit" class="w-full py-3.5 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition">
                Create Account
            </button>
        </form>

        <div class="text-center text-xs text-slate-500 pt-2 border-t border-slate-100 space-y-2">
            <div>Already have an account? <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:underline">Sign In</a></div>
            <div>Want to sell products? <a href="{{ route('vendor.register') }}" class="font-bold text-amber-600 hover:underline">Create a vendor store</a></div>
        </div>
    </div>

</div>
@endsection
