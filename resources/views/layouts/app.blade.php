<!DOCTYPE html>
<html lang="en" class="h-full bg-white text-slate-900 antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'OmniMarket') - Online Marketplace</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        heading: ['"Outfit"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                            950: '#1e1b4b',
                        },
                        amber: {
                            500: '#f59e0b',
                            600: '#d97706',
                        },
                        emerald: {
                            500: '#10b981',
                            600: '#059669',
                        }
                    },
                    boxShadow: {
                        'soft': '0 4px 20px -2px rgba(0, 0, 0, 0.04)',
                        'glow': '0 0 25px -5px rgba(99, 102, 241, 0.25)',
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        h1, h2, h3, h4, h5, h6, .font-heading { font-family: 'Outfit', sans-serif; }
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }
        .gradient-brand {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #ec4899 100%);
        }
        .gradient-light {
            background: linear-gradient(135deg, #f8fafc 0%, #eef2ff 100%);
        }
    </style>
</head>
<body class="min-h-full flex flex-col bg-white text-slate-900">

    <!-- Top Utility & Announcement Bar -->
    <div class="bg-slate-900 text-slate-300 text-xs py-2 px-4 border-b border-slate-800">
        <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 text-slate-300 font-medium text-[11px]">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    Free express shipping on all orders over $99
                </span>
                <span class="text-slate-700 hidden md:inline">|</span>
                <span class="text-slate-400 hidden md:inline text-[11px]">Guaranteed Authenticity & Buyer Protection</span>
            </div>
            <div class="flex items-center gap-4 text-[11px]">
                <a href="{{ route('stores.index') }}" class="text-slate-300 hover:text-white transition">Explore Stores</a>
                <a href="{{ route('vendor.register') }}" class="text-indigo-400 hover:text-indigo-300 font-semibold transition">Sell on OmniMarket</a>
                @auth
                    <form method="POST" action="{{ route('logout') }}" class="inline ml-2">
                        @csrf
                        <button type="submit" class="text-slate-400 hover:text-rose-400 transition">
                            Log out ({{ Auth::user()->name }})
                        </button>
                    </form>
                @endauth
            </div>
        </div>
    </div>

    <!-- Main Navigation Header -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200/80 shadow-sm" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20 gap-4">
                
                <!-- Brand Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0 group">
                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800 flex items-center justify-center text-white shadow-md shadow-indigo-500/20 group-hover:scale-105 transition-transform duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <div>
                        <div class="font-heading font-extrabold text-2xl tracking-tight text-slate-900 leading-none">
                            Omni<span class="bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Market</span>
                        </div>
                        <div class="text-[11px] font-medium tracking-wide text-slate-500 uppercase mt-0.5">Marketplace</div>
                    </div>
                </a>

                <!-- Search Bar -->
                <div class="hidden md:flex flex-1 max-w-lg mx-6">
                    <form action="{{ route('products.index') }}" method="GET" class="w-full relative">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search products, brands, stores..." 
                               class="w-full pl-11 pr-24 py-2.5 bg-slate-100/80 hover:bg-slate-100 focus:bg-white border border-slate-200 focus:border-indigo-500 rounded-full text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 transition shadow-inner">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <button type="submit" class="absolute inset-y-1.5 right-1.5 px-4 rounded-full bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold shadow-sm transition">
                            Search
                        </button>
                    </form>
                </div>

                <!-- Navigation Links & User Actions -->
                <div class="flex items-center gap-3 lg:gap-4">
                    
                    <nav class="hidden lg:flex items-center gap-6 text-sm font-medium text-slate-600">
                        <a href="{{ route('products.index') }}" class="hover:text-indigo-600 transition {{ request()->routeIs('products.*') ? 'text-indigo-600 font-semibold' : '' }}">Products</a>
                        <a href="{{ route('stores.index') }}" class="hover:text-indigo-600 transition {{ request()->routeIs('stores.*') ? 'text-indigo-600 font-semibold' : '' }}">Stores</a>
                        <a href="{{ route('vendor.register') }}" class="hover:text-indigo-600 transition text-amber-600 font-semibold flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            Sell
                        </a>
                    </nav>



                    <!-- Shopping Cart Button -->
                    @php
                        $cartService = app(\App\Services\CartService::class);
                        $cartCount = $cartService->getCount();
                    @endphp
                    <a href="{{ route('cart.index') }}" class="relative p-2.5 text-slate-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition flex items-center gap-2 group">
                        <div class="relative">
                            <svg class="w-6 h-6 text-slate-700 group-hover:text-indigo-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            @if($cartCount > 0)
                                <span class="absolute -top-2 -right-2.5 bg-indigo-600 text-white font-bold text-[11px] w-5 h-5 rounded-full flex items-center justify-center ring-2 ring-white shadow-sm animate-pulse">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        </div>
                        <span class="hidden sm:inline text-xs font-semibold text-slate-700 group-hover:text-indigo-600">Cart</span>
                    </a>

                    <!-- User Account / Portal Menu -->
                    @auth
                        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                            <button @click="open = !open" class="flex items-center gap-2.5 pl-2 pr-3 py-1.5 rounded-full bg-slate-100 hover:bg-slate-200/80 border border-slate-200 text-slate-800 text-sm font-medium transition focus:outline-none">
                                <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="w-7 h-7 rounded-full object-cover ring-1 ring-slate-300">
                                <span class="hidden md:inline max-w-[120px] truncate">{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <div x-show="open" x-transition.opacity.scale.95
                                 class="absolute right-0 mt-2 w-64 rounded-2xl bg-white shadow-xl ring-1 ring-black/5 py-2 z-50 divide-y divide-slate-100 font-medium text-sm">
                                
                                <div class="px-4 py-3">
                                    <p class="text-xs text-slate-600">Signed in as</p>
                                    <p class="font-bold text-slate-900 truncate">{{ Auth::user()->name }}</p>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase mt-1
                                        {{ Auth::user()->isAdmin() ? 'bg-rose-100 text-rose-800' : (Auth::user()->isVendor() ? 'bg-indigo-100 text-indigo-800' : 'bg-emerald-100 text-emerald-800') }}">
                                        {{ Auth::user()->role }} Account
                                    </span>
                                </div>

                                <div class="py-1">
                                    @if(Auth::user()->isAdmin())
                                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-slate-700 hover:bg-indigo-50 hover:text-indigo-600">
                                            <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                            Admin Dashboard
                                        </a>
                                        <a href="{{ route('admin.vendors.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-slate-700 hover:bg-indigo-50 hover:text-indigo-600">
                                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                            Manage Vendors
                                        </a>
                                    @endif

                                    @if(Auth::user()->isVendor() || Auth::user()->isAdmin())
                                        <a href="{{ route('vendor.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-slate-700 hover:bg-indigo-50 hover:text-indigo-600">
                                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                                            Vendor Merchant Portal
                                        </a>
                                        <a href="{{ route('vendor.products.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-slate-700 hover:bg-indigo-50 hover:text-indigo-600">
                                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                            My Vendor Products
                                        </a>
                                        <a href="{{ route('vendor.orders.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-slate-700 hover:bg-indigo-50 hover:text-indigo-600">
                                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                            Vendor Order Fulfillment
                                        </a>
                                    @endif

                                    <a href="{{ route('customer.orders') }}" class="flex items-center gap-2.5 px-4 py-2 text-slate-700 hover:bg-indigo-50 hover:text-indigo-600">
                                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                        My Customer Orders
                                    </a>
                                    <a href="{{ route('customer.profile') }}" class="flex items-center gap-2.5 px-4 py-2 text-slate-700 hover:bg-indigo-50 hover:text-indigo-600">
                                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        Account Settings
                                    </a>
                                </div>

                                <div class="py-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-rose-600 hover:bg-rose-50 flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                            Sign Out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center gap-2">
                            <a href="{{ route('login') }}" class="px-4 py-2 rounded-xl text-slate-700 hover:text-indigo-600 hover:bg-slate-100 font-semibold text-sm transition">
                                Sign In
                            </a>
                            <a href="{{ route('register') }}" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm shadow-md shadow-indigo-600/20 transition">
                                Create Account
                            </a>
                        </div>
                    @endauth

                    <!-- Mobile Menu Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 text-slate-700 hover:text-indigo-600 rounded-lg lg:hidden">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div x-show="mobileMenuOpen" class="lg:hidden border-t border-slate-200 bg-white px-4 pt-3 pb-6 space-y-3">
            <form action="{{ route('products.index') }}" method="GET" class="w-full relative mb-3">
                <input type="text" name="q" placeholder="Search marketplace..." class="w-full pl-10 pr-4 py-2 bg-slate-100 border border-slate-200 rounded-xl text-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </form>
            <div class="flex flex-col gap-2 font-medium text-slate-700 text-sm">
                <a href="{{ route('home') }}" class="px-3 py-2 rounded-lg hover:bg-slate-100">Home</a>
                <a href="{{ route('products.index') }}" class="px-3 py-2 rounded-lg hover:bg-slate-100">All Products</a>
                <a href="{{ route('stores.index') }}" class="px-3 py-2 rounded-lg hover:bg-slate-100">Verified Vendors</a>
                <a href="{{ route('vendor.register') }}" class="px-3 py-2 rounded-lg bg-indigo-50 text-indigo-700 font-semibold">Open a Vendor Store</a>
            </div>
        </div>
    </header>

    <!-- Global Toast & Flash Alerts -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if(session('success'))
            <div class="rounded-2xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-900 text-sm flex items-center gap-3 shadow-sm mb-4">
                <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div class="flex-1 font-medium">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-2xl bg-rose-50 border border-rose-200 p-4 text-rose-900 text-sm flex items-center gap-3 shadow-sm mb-4">
                <div class="w-8 h-8 rounded-xl bg-rose-500 text-white flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </div>
                <div class="flex-1 font-medium">{{ session('error') }}</div>
            </div>
        @endif

        @if(session('info'))
            <div class="rounded-2xl bg-blue-50 border border-blue-200 p-4 text-blue-900 text-sm flex items-center gap-3 shadow-sm mb-4">
                <div class="w-8 h-8 rounded-xl bg-blue-500 text-white flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="flex-1 font-medium">{{ session('info') }}</div>
            </div>
        @endif

        @if(session('warning'))
            <div class="rounded-2xl bg-amber-50 border border-amber-200 p-4 text-amber-900 text-sm flex items-center gap-3 shadow-sm mb-4">
                <div class="w-8 h-8 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div class="flex-1 font-medium">{{ session('warning') }}</div>
            </div>
        @endif
    </div>

    <!-- Page Body Content -->
    <main class="flex-1 pb-16 lg:pb-0">
        @yield('content')
    </main>

    <!-- Rich Marketplace Footer (White/Light Theme) -->
    <footer class="bg-white text-slate-600 mt-20 border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10">
                
                <div class="lg:col-span-2">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-600 to-purple-700 flex items-center justify-center text-white font-bold shadow-sm">
                            OM
                        </div>
                        <span class="font-heading font-extrabold text-2xl text-slate-900">Omni<span class="text-indigo-600">Market</span></span>
                    </div>
                    <p class="text-sm text-slate-500 max-w-sm mb-6 leading-relaxed">
                        Discover exceptional craftsmanship, modern lifestyle goods, and innovative audio tech from verified creators worldwide. Secured with direct buyer protection.
                    </p>
                    <div class="flex items-center gap-4 text-xs text-slate-500 font-medium">
                        <span class="inline-flex items-center gap-1.5 text-emerald-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            100% Buyer Protection
                        </span>
                        <span>•</span>
                        <span class="inline-flex items-center gap-1.5 text-indigo-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            Encrypted Checkout
                        </span>
                    </div>
                </div>

                <div>
                    <h4 class="text-slate-900 font-heading font-bold text-xs tracking-wider uppercase mb-4">Marketplace</h4>
                    <ul class="space-y-2.5 text-sm text-slate-600">
                        <li><a href="{{ route('products.index') }}" class="hover:text-indigo-600 transition">All Products</a></li>
                        <li><a href="{{ route('stores.index') }}" class="hover:text-indigo-600 transition">Verified Shops</a></li>
                        <li><a href="{{ route('products.index', ['sort' => 'popular']) }}" class="hover:text-indigo-600 transition">Top Rated Items</a></li>
                        <li><a href="{{ route('cart.index') }}" class="hover:text-indigo-600 transition">Shopping Bag</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-slate-900 font-heading font-bold text-xs tracking-wider uppercase mb-4">Sell With Us</h4>
                    <ul class="space-y-2.5 text-sm text-slate-600">
                        <li><a href="{{ route('vendor.register') }}" class="text-indigo-600 hover:text-indigo-700 font-semibold transition">Open a Store</a></li>
                        <li><a href="{{ route('vendor.dashboard') }}" class="hover:text-indigo-600 transition">Merchant Portal</a></li>
                        <li><a href="{{ route('vendor.products.index') }}" class="hover:text-indigo-600 transition">Product Catalog</a></li>
                        <li><a href="{{ route('vendor.payouts.index') }}" class="hover:text-indigo-600 transition">Payout Schedule</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-slate-900 font-heading font-bold text-xs tracking-wider uppercase mb-4">Customer Care</h4>
                    <ul class="space-y-2.5 text-sm text-slate-600">
                        <li><a href="{{ route('customer.orders') }}" class="hover:text-indigo-600 transition">Order Tracking</a></li>
                        <li><a href="{{ route('customer.profile') }}" class="hover:text-indigo-600 transition">Account Profile</a></li>
                        <li><span class="text-slate-400">support@omnimarket.com</span></li>
                        <li><span class="text-slate-400">Mon - Sat: 9am - 8pm EST</span></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-slate-200 mt-12 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} OmniMarketplace Inc. All rights reserved.</p>
                <div class="flex items-center gap-6">
                    <span class="hover:text-slate-800 transition">Privacy Policy</span>
                    <span>•</span>
                    <span class="hover:text-slate-800 transition">Terms of Service</span>
                    <span>•</span>
                    <span class="hover:text-slate-800 transition">Security</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Mobile Bottom Sticky Navigation Bar (Visible on phones) -->
    <div class="lg:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-slate-200 px-4 py-2 flex items-center justify-around text-slate-600 shadow-lg">
        <a href="{{ route('home') }}" class="flex flex-col items-center gap-0.5 text-[10px] font-medium {{ request()->routeIs('home') ? 'text-indigo-600 font-bold' : 'hover:text-indigo-600' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span>Home</span>
        </a>

        <a href="{{ route('products.index') }}" class="flex flex-col items-center gap-0.5 text-[10px] font-medium {{ request()->routeIs('products.*') ? 'text-indigo-600 font-bold' : 'hover:text-indigo-600' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            <span>Shop</span>
        </a>

        <a href="{{ route('cart.index') }}" class="flex flex-col items-center gap-0.5 text-[10px] font-medium relative {{ request()->routeIs('cart.*') ? 'text-indigo-600 font-bold' : 'hover:text-indigo-600' }}">
            <div class="relative">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                @if($cartCount > 0)
                    <span class="absolute -top-1.5 -right-2 bg-indigo-600 text-white font-bold text-[9px] w-4 h-4 rounded-full flex items-center justify-center">
                        {{ $cartCount }}
                    </span>
                @endif
            </div>
            <span>Cart</span>
        </a>

        <a href="{{ route('stores.index') }}" class="flex flex-col items-center gap-0.5 text-[10px] font-medium {{ request()->routeIs('stores.*') ? 'text-indigo-600 font-bold' : 'hover:text-indigo-600' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            <span>Stores</span>
        </a>

        @auth
            @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center gap-0.5 text-[10px] font-medium text-rose-600 font-bold">
                    <span class="w-5 h-5 flex items-center justify-center font-bold">⚡</span>
                    <span>Admin</span>
                </a>
            @elseif(Auth::user()->isVendor())
                <a href="{{ route('vendor.dashboard') }}" class="flex flex-col items-center gap-0.5 text-[10px] font-medium text-indigo-600 font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    <span>Vendor</span>
                </a>
            @else
                <a href="{{ route('customer.orders') }}" class="flex flex-col items-center gap-0.5 text-[10px] font-medium {{ request()->routeIs('customer.*') ? 'text-indigo-600 font-bold' : 'hover:text-indigo-600' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <span>Account</span>
                </a>
            @endif
        @else
            <a href="{{ route('login') }}" class="flex flex-col items-center gap-0.5 text-[10px] font-medium {{ request()->routeIs('login') ? 'text-indigo-600 font-bold' : 'hover:text-indigo-600' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                <span>Login</span>
            </a>
        @endauth
    </div>

    @stack('scripts')
</body>
</html>
