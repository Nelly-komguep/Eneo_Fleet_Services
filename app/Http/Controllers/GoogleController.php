<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::updateOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => bcrypt(Str::random(16)), // mot de passe aléatoire
                    'google_id' => $googleUser->getId()
                ]
            );

            Auth::login($user, true);
            session()->regenerate();

            if ($user->role === 'superadmin') {
    return redirect()->route('dashboard-superAdmin');
} elseif ($user->role === 'admin') {
    return redirect()->route('dashboard');
} else {
    return redirect()->route('dashboard.user');
}
            // return redirect('/dashboard-user'); // Redirection après connexion
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Erreur de connexion Google.');
        }
    }
}
