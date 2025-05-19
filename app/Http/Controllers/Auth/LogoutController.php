<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController
{
    public function logout(Request $request)
    {
        if (Auth::guard('company')->check()) {
            Auth::guard('company')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return to_route('auth.login.empresa');
        }

        if (Auth::guard('user')->check()) {
            Auth::guard('user')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return to_route('auth.login.usuario-interno');
        }

    }
}
