<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OtpAuthController extends Controller
{
    public function show()
    {
        return view('frontend.user.otp-auth');
    }

    public function sendOtp(Request $request)
    {
        $request->validate(['phone' => 'required|digits:10']);

        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $user = User::where('phone', $request->phone)->first();
        $isNewUser = !$user;

        if ($user) {
            $user->update(['otp' => $otp, 'otp_expires_at' => now()->addMinutes(5)]);
        } else {
            session([
                'pending_otp_phone' => $request->phone,
                'pending_otp' => $otp,
                'pending_otp_expires' => now()->addMinutes(5),
            ]);
        }

        // TODO: Replace this with the real Nettyfish SMS API call once credentials are provided.
        // Example: NettyfishService::send($request->phone, "Your StayZio verification code is $otp");

        return response()->json([
            'status' => 'success',
            'is_new_user' => $isNewUser,
            'test_mode_otp' => $otp, // TEST MODE ONLY - remove this line once real SMS sending is wired up
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['phone' => 'required|digits:10', 'otp' => 'required|digits:6']);

        $user = User::where('phone', $request->phone)->first();

        if ($user) {
            if ($user->otp !== $request->otp || !$user->otp_expires_at || now()->gt($user->otp_expires_at)) {
                return response()->json(['status' => 'error', 'message' => 'Invalid or expired OTP'], 422);
            }
            $user->update(['otp' => null, 'otp_expires_at' => null]);
            Auth::guard('web')->login($user);
            return response()->json(['status' => 'success', 'is_new_user' => false, 'redirect' => route('user.dashboard')]);
        }

        if (session('pending_otp_phone') !== $request->phone
            || session('pending_otp') !== $request->otp
            || !session('pending_otp_expires')
            || now()->gt(session('pending_otp_expires'))) {
            return response()->json(['status' => 'error', 'message' => 'Invalid or expired OTP'], 422);
        }

        return response()->json(['status' => 'success', 'is_new_user' => true]);
    }

    public function completeSignup(Request $request)
    {
        $request->validate(['phone' => 'required|digits:10', 'name' => 'required|string|max:255']);

        if (session('pending_otp_phone') !== $request->phone) {
            return response()->json(['status' => 'error', 'message' => 'Session expired, please request a new OTP'], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'username' => $request->phone,
            'phone' => $request->phone,
            'email' => 'user_' . $request->phone . '@stayziohotels.com',
            'password' => Hash::make(Str::random(32)),
            'email_verified_at' => now(),
        ]);

        session()->forget(['pending_otp_phone', 'pending_otp', 'pending_otp_expires']);
        Auth::guard('web')->login($user);

        return response()->json(['status' => 'success', 'redirect' => route('user.dashboard')]);
    }
}