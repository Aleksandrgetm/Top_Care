<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        if ($request->user()?->is_admin) {
            return redirect()->route('admin.pages.index');
        }

        if ($request->user()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return view('admin.auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        if ($request->user()?->is_admin) {
            return redirect()->route('admin.pages.index');
        }

        $validated = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $login = trim($validated['login']);
        $remember = $request->boolean('remember');

        $authenticated = Auth::attempt([
            'name' => $login,
            'password' => $validated['password'],
        ], $remember);

        if (! $authenticated && filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $authenticated = Auth::attempt([
                'email' => $login,
                'password' => $validated['password'],
            ], $remember);
        }

        if (! $authenticated) {
            return back()
                ->withErrors(['login' => 'Incorrect login or password.'])
                ->onlyInput('login');
        }

        $request->session()->regenerate();

        if (! $request->user()?->is_admin) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withErrors(['login' => 'You do not have access to the admin panel.'])
                ->onlyInput('login');
        }

        return redirect()->intended(route('admin.pages.index'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
