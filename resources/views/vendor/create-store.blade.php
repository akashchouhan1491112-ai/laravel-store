@extends('layouts.app')

@section('title', 'Set Up Your Store')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-12">
    <div class="bg-white rounded-3xl border border-slate-200/80 p-8 shadow-sm text-center space-y-6">
        <div class="w-16 h-16 rounded-3xl bg-indigo-50 text-indigo-600 mx-auto flex items-center justify-center">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
        </div>

        <h1 class="font-heading font-extrabold text-2xl text-slate-900">Set Up Your Store Profile</h1>
        <p class="text-xs text-slate-600 max-w-sm mx-auto">
            Please fill in your storefront details so buyers can discover your brand on the marketplace.
        </p>

        <a href="{{ route('vendor.settings') }}" class="inline-block px-8 py-3.5 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition">
            Configure Store Now →
        </a>
    </div>
</div>
@endsection
