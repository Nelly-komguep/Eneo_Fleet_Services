<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Twilio\Rest\Client;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PhoneAuthController extends Controller
{
    public function showPhoneForm()
    {
        return view('auth.phone_login');
    }

    public function sendOTP(Request $request)
    {
        $request->validate([
            'phone' => 'required|string'
        ]);

        $user = User::where('phone', $request->phone)->first();
        if (!$user) {
            return back()->withErrors(['phone' => 'Numéro non enregistré.']);
        }

        $otp = rand(100000, 999999);
        $user->phone_otp = $otp;
        $user->save();

        // Envoi SMS via Twilio
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $from = env('TWILIO_FROM');

        $client = new Client($sid, $token);
        $client->messages->create($user->phone, [
            'from' => $from,
            'body' => "Votre code de connexion est : $otp"
        ]);

        return view('auth.verify_phone', compact('user'));
    }

    public function verifyOTP(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'otp' => 'required|string'
        ]);

        $user = User::where('phone', $request->phone)
                    ->where('phone_otp', $request->otp)
                    ->first();

        if (!$user) {
            return back()->withErrors(['otp' => 'OTP incorrect.']);
        }

        $user->phone_verified_at = now();
        $user->phone_otp = null;
        $user->save();

        Auth::login($user);

        // Redirection selon rôle
        if ($user->role === 'superadmin') {
            return redirect()->route('dashboard-superAdmin');
        } elseif ($user->role === 'admin') {
            return redirect()->route('dashboard');
        } else {
            return redirect()->route('dashboard.user');
        }
    }
}
