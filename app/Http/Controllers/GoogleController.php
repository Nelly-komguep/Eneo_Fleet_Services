<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function Callback()
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

    // Redirection vers Twitter
public function redirectToTwitter()
{
    return Socialite::driver('twitter')->redirect();
}

// Callback Twitter
public function handleTwitterCallback()
{
    try {
        $twitterUser = Socialite::driver('twitter')->user();
    } catch (\Exception $e) {
        return redirect()->route('login')->with('error', 'Connexion Twitter annulée ou erreur.');
    }

    $twitterId = $twitterUser->getId();
    $name = $twitterUser->getName();
    $nickname = $twitterUser->getNickname();
    $avatar = $twitterUser->getAvatar();

    // ⚠️ Twitter ne donne pas toujours l’email (sauf si ton app est validée pour ça)
    $email = $twitterUser->getEmail();

    // Cherche un user existant
    $user = User::where('provider', 'twitter')->where('provider_id', $twitterId)->first();

    if (!$user) {
        // si pas trouvé, chercher par email si dispo
        if ($email) {
            $user = User::where('email', $email)->first();
        }

        if ($user) {
            $user->update([
                'provider' => 'twitter',
                'provider_id' => $twitterId,
                'avatar' => $avatar,
            ]);
        } else {
            $user = User::create([
                'name' => $name ?? $nickname,
                'email' => $email ?? ($nickname.'@twitter.com'), // fallback si pas d'email
                'password' => bcrypt(Str::random(24)),
                'provider' => 'twitter',
                'provider_id' => $twitterId,
                'avatar' => $avatar,
            ]);
        }
    }

    Auth::login($user, true);

    return redirect()->intended('/dashboard-user');
}

//  public function redirectToFacebook()
//     {
//         // demande l'email aussi
//         return Socialite::driver('facebook')->scopes(['email'])->redirect();
//     }

//     // Callback de Facebook
//     public function handleFacebookCallback(Request $request)
//     {
//         try {
//             // Si tu utilises une API stateless, utiliser ->stateless()
//             $fbUser = Socialite::driver('facebook')->user();
//         } catch (\Exception $e) {
//             return redirect()->route('login')->with('error', 'Connexion Facebook annulée ou erreur.');
//         }

//         // Récupère email — attention : Facebook peut ne pas renvoyer l'email
//         $email = $fbUser->getEmail();
//         $providerId = $fbUser->getId();
//         $name = $fbUser->getName();
//         $avatar = $fbUser->getAvatar();

//         if (!$email) {
//             // cas où Facebook ne donne pas d'email : rediriger demander email manuellement
//             return redirect()->route('login')->with('error', 'Facebook n’a pas fourni d’email. Veuillez vous inscrire avec un email.');
//         }

//         // Chercher user existant par provider_id ou email
//         $user = User::where('provider', 'facebook')->where('provider_id', $providerId)->first();

//         if (!$user) {
//             // si pas trouvé par provider, chercher par email (compte existant)
//             $user = User::where('email', $email)->first();

//             if ($user) {
//                 // lier le provider au compte existant
//                 $user->update([
//                     'provider' => 'facebook',
//                     'provider_id' => $providerId,
//                     'avatar' => $avatar,
//                 ]);
//             } else {
//                 // créer un nouvel utilisateur
//                 $user = User::create([
//                     'name' => $name ?? $email,
//                     'email' => $email,
//                     // mot de passe aléatoire car login via FB
//                     'password' => bcrypt(Str::random(24)),
//                     'provider' => 'facebook',
//                     'provider_id' => $providerId,
//                     'avatar' => $avatar,
//                 ]);
//             }
//         }

//         // Connecte l'utilisateur (session)
//         Auth::login($user, true);

//         // redirige vers le dashboard approprié
//         return redirect()->intended('/dashboard-user');
//     }

}
