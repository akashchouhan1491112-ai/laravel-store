<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Store;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->status === 'suspended') {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account has been suspended. Please contact support.']);
            }

            // Transfer any guest cart items to the authenticated user
            $this->cartService->transferGuestCartToUser($user);

            return $this->redirectBasedOnRole($user)->with('success', "Welcome back, {$user->name}!");
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.register');
    }

    public function showVendorRegister(): View|RedirectResponse
    {
        if (Auth::check()) {
            if (Auth::user()->isVendor()) {
                return redirect()->route('vendor.dashboard');
            }
        }
        return view('auth.vendor-register');
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $role = $request->input('role', 'customer');

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role,
            'phone' => $request->phone,
            'address' => $request->address,
            'status' => 'active',
        ]);

        if ($role === 'vendor') {
            $storeName = $request->input('store_name', $user->name . "'s Store");
            Store::create([
                'user_id' => $user->id,
                'name' => $storeName,
                'slug' => Str::slug($storeName) . '-' . Str::random(5),
                'description' => $request->input('store_description', 'Welcome to my store!'),
                'status' => 'pending', // Vendor stores start as pending approval
                'commission_rate' => 10.00,
                'balance' => 0.00,
                'total_sales' => 0.00,
            ]);
        }

        Auth::login($user);
        $this->cartService->transferGuestCartToUser($user);

        return $this->redirectBasedOnRole($user)->with('success', 'Account created successfully!');
    }

    /**
     * Demo Fast-Login Switcher for effortless testing of different roles!
     */
    public function demoLogin(string $role): RedirectResponse
    {
        $email = match ($role) {
            'admin' => 'admin@marketplace.com',
            'vendor' => 'tech@gearhub.com',
            'vendor2' => 'artisan@decorcraft.com',
            'customer' => 'sarah@example.com',
            default => 'sarah@example.com',
        };

        $user = User::where('email', $email)->first();

        if ($user) {
            Auth::login($user);
            $this->cartService->transferGuestCartToUser($user);
            return $this->redirectBasedOnRole($user)->with('info', "Switched to demo role: " . strtoupper($user->role) . " ({$user->name})");
        }

        return redirect()->route('home')->with('error', 'Demo user not found. Please run database seeds.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('info', 'You have been logged out.');
    }

    protected function redirectBasedOnRole(User $user): RedirectResponse
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isVendor()) {
            return redirect()->route('vendor.dashboard');
        }

        return redirect()->route('customer.orders');
    }
}
