<?php

namespace App\Module\PasswordAuth\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function response;

class SpaLoginPasswordController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
                                              'email' => ['required', 'email'],
                                              'password' => ['required'],
                                          ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return response('success', 200);
        }

        return [
            'errors' => [
                'email' => 'The provided credentials do not match our records.',
            ],
        ];
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response('success', 200);
    }
}
