<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show the admin login form.
     */
    public function showLogin(): View
    {
        return view('admin.auth.login');
    }

    /**
     * Authenticate the concierge / review team member.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string', 'max:100'],
            'password' => ['required', 'string', 'max:200'],
        ]);

        $expectedUser = (string) config('negah.admin.username');
        $expectedPass = (string) config('negah.admin.password');

        $userOk = hash_equals($expectedUser, $credentials['username']);

        // Support either a plaintext env value or a bcrypt hash (starts with $2y$).
        $passOk = str_starts_with($expectedPass, '$2y$')
            ? Hash::check($credentials['password'], $expectedPass)
            : hash_equals($expectedPass, $credentials['password']);

        if (! $userOk || ! $passOk) {
            return back()
                ->withInput($request->only('username'))
                ->withErrors(['username' => 'Invalid username or password.']);
        }

        $request->session()->regenerate();
        $request->session()->put('admin_logged_in', true);
        $request->session()->put('admin_user', $credentials['username']);

        return redirect()->route('admin.dashboard');
    }

    /**
     * Sign out of the admin panel.
     */
    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget(['admin_logged_in', 'admin_user']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('admin.login')
            ->with('error', 'You have been signed out.');
    }
}
