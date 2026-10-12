@extends('layouts.app')

@section('title', 'Sign In')

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    
    <div class="bg-white rounded-3xl border border-slate-200/80 p-8 shadow-sm space-y-6">
        <div class="text-center space-y-1">
            <h1 class="font-heading font-extrabold text-2xl text-slate-900 tracking-tight">Welcome Back</h1>
            <p class="text-xs text-slate-500">Sign in to your customer, vendor, or admin account</p>
        </div>

        <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="name@example.com"
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @error('email')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="block text-xs font-bold text-slate-700 uppercase">Password</label>
                </div>
                <input type="password" id="password" name="password" required placeholder="••••••••"
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @error('password')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center gap-2 cursor-pointer text-slate-600">
                    <input type="checkbox" name="remember" class="rounded text-indigo-600 focus:ring-indigo-500">
                    <span>Remember me</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3.5 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition">
                Sign In
            </button>
        </form>

        <div class="text-center text-xs text-slate-500 pt-2 border-t border-slate-100 space-y-2">
            <div>Don't have an account? <a href="{{ route('register') }}" class="font-bold text-indigo-600 hover:underline">Sign up as buyer</a></div>
            <div>Want to sell? <a href="{{ route('vendor.register') }}" class="font-bold text-amber-600 hover:underline">Register vendor store</a></div>
        </div>
    </div>

</div>
@endsection
