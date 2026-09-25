<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Farmer;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $user = DB::transaction(function () use ($data): User {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'role' => $data['role'],
                'password' => $data['password'],
                'status' => 'active',
            ]);

            if ($user->isFarmer()) {
                Farmer::create([
                    'user_id' => $user->id,
                    'name' => $data['stall_name'] ?? $user->name,
                    'owner_name' => $user->name,
                    'location' => $data['address'] ?? 'Pending confirmation',
                    'status' => 'pending',
                    'rating' => 0,
                    'slots' => null,
                    'is_demo' => false,
                ]);
            }

            return $user;
        });

        Auth::login($user);
        $request->session()->regenerate();

        $redirectRoute = $user->isFarmer()
            ? 'farmer.dashboard'
            : ($user->isAdmin() ? 'admin.dashboard' : 'dashboard');

        return redirect()->route($redirectRoute)->with(
            'status',
            $user->isFarmer()
                ? 'Farmer account created. Your profile is pending administrator approval.'
                : 'Account created successfully.',
        );
    }

    public function showLogin(): View
    {
        return view('auth.login', ['loginRole' => null]);
    }

    public function showLoginForRole(string $role): View
    {
        abort_unless(in_array($role, ['admin', 'farmer'], true), 404);

        return view('auth.login', ['loginRole' => $role]);
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->safe()->only(['email', 'password']);
        $expectedRole = $request->input('login_role') ?: $request->route('login_role');
        $user = User::where('email', $credentials['email'])->first();

        if ($expectedRole && (!$user || $user->role !== $expectedRole)) {
            return back()->withErrors([
                'email' => 'These credentials are not for the selected portal.',
            ])->onlyInput('email');
        }
        if ($user && in_array($user->status ?? 'active', ['inactive', 'suspended'], true)) {
            return back()->withErrors(['email' => 'This account is currently inactive or suspended.'])->onlyInput('email');
        }
        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        if ($user->isFarmer()) {
            $farmerProfile = $user->farmer()->first();

            if ($farmerProfile === null) {
                $farmerProfile = Farmer::create([
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'owner_name' => $user->name,
                    'location' => 'Pending confirmation',
                    'status' => 'pending',
                    'rating' => 0,
                    'is_demo' => false,
                ]);
            }

            if ($farmerProfile->status === 'suspended') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->withErrors([
                    'email' => 'This farmer profile has been suspended by an administrator.',
                ])->withInput(['email' => $user->email]);
            }
        }

        $redirectRoute = $user->isFarmer()
            ? 'farmer.dashboard'
            : ($user->isAdmin() ? 'admin.dashboard' : 'dashboard');

        return redirect()->route($redirectRoute);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
