<?php

namespace App\Module\MobileAuth\Controllers;

use App\Http\Controllers\Controller;
use App\Module\MobileAuth\Entities\MobileAccount;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function response;

class SpaLoginPincodeController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loginPhone(Request $request)
    {
        $credentials = $request->validate(['phone' => ['required']]);
        $phoneNumber = $credentials['phone'];

        if (!MobileAccount::existsByPhone($phoneNumber)) {
            MobileAccount::register($phoneNumber);
        }

        MobileAccount::sendPincode($phoneNumber);

        return $this->success();
    }

    /**
     * Handle an authentication attempt.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loginPincode(Request $request)
    {
        $credentials = $request->validate([
                                              'phone' => ['required'],
                                              'pincode' => ['required'],
                                          ]);

        $phoneNumber = $credentials['phone'];
        $pincode = $credentials['pincode'];

        if (!MobileAccount::existsByPhone($phoneNumber)) {
            return $this->failLoginPincode();
        }

        $mobileAccount = MobileAccount::getExistingByPhone($phoneNumber);

        if (!$mobileAccount->isValidPincode($pincode)) {
            return $this->failLoginPincode();
        }

        Auth::loginUsingId($mobileAccount->getUserModelId());
        $request->session()->regenerate();

        return $this->success();
    }

    private function failLoginPincode(): JsonResponse
    {
        return response()->json([
            'errors' => [
                'auth' => 'Неверный номер или пинкод',
            ],
        ], 400);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return $this->success();
    }

    private function success(): JsonResponse
    {
        return response()->json([
                                    'success',
                                ]);
    }
}
