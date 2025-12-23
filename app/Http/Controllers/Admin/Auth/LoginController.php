<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Redirect path after login
     */
    protected string $redirectTo = 'admin';

    /**
     * Show admin login form
     */
    public function showLoginForm()
    {
        $pageTitle = "Admin Login";
        return view('admin.auth.login', compact('pageTitle'));
    }

    /**
     * Admin username field
     */
    protected function username(): string
    {
        return 'username';
    }

    /**
     * Admin guard
     */
    protected function guard()
    {
        return Auth::guard('admin');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password'       => 'required|string',
        ]);

        if (!verifyCaptcha()) {
            return back()->withNotify([
                ['error', 'Invalid captcha provided'],
            ]);
        }


        $credentials = $request->only($this->username(), 'password');

        if ($this->guard()->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // handle redirect
            if ($request->filled('redirect')) {
                $this->redirectTo = $this->validateRedirect($request->redirect);
            }

            return redirect()->to($this->redirectTo);
        }

        return back()->withErrors([
            $this->username() => 'Invalid login credentials',
        ]);
    }

    /**
     * Validate redirect URL safely
     */
    protected function validateRedirect(string $redirect): string
    {
        try {
            if (!str_starts_with($redirect, 'admin')) {
                $redirect = 'admin/' . ltrim($redirect, '/');
            }

            return url($redirect) ? $redirect : $this->redirectTo;
        } catch (\Throwable) {
            return $this->redirectTo;
        }
    }

    /**
     * Logout admin
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
