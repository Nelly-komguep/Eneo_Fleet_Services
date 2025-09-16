<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\Job;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLogin() {
        return view('login');
    }

    public function log(Request $request) {
        $credentials = $request->only('name', 'password');
        $remember = $request->filled('remember'); 

         if (Auth::attempt($credentials, $remember)) {
        $user = Auth::user();

        // session([
        //     'user_id' => $user->id,
        //     'user_name' => $user->name,
        // ]);
        session()->flash('success', 'Vous êtes connecté(e) avec succès !');

        if ($user->role === 'superadmin') {
            return redirect()->route('dashboard-superAdmin');
        } elseif ($user->role === 'admin') {
            return redirect()->route('dashboard');
        } else {
            return redirect()->route('dashboard.user');
        }
    }

        return back()->withErrors([
            'namepassword' => 'Les identifiants sont incorrects.',
        ]);
    }
    public function showRegistration() {
       return view('registrer');
        
    }

    public function reg(Request $request) {
        $request->validate([
            'name' => 'required|string|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'email' => 'required|string',
            'g-recaptcha-response' => 'required|captcha',
            'role' => 'required|string',
            'code' => 'nullable|string',
            'jobs' => 'nullable|string',
        ]);

        $imageUrl = null;

    if ($request->hasFile('profile_image')) {
        // Upload direct vers Cloudinary
        $upload = Cloudinary::upload(
            $request->file('profile_image')->getRealPath(),
            [
                'folder' => 'users',          // dossier Cloudinary
                'transformation' => [         // optimisation automatique
                    'width' => 400,
                    'height' => 400,
                    'crop' => 'limit',
                    'quality' => 'auto',
                    'fetch_format' => 'auto'
                ],
            ]
        );
        $imageUrl = $upload->getSecurePath(); // URL https optimisée
    }

        if ($request->role === 'admin' && $request->code !== 'ADMIN123') {
            return back()->withErrors(['code' => 'Code incorrect pour le rôle Administrateur.'])->withInput();
        }

        if ($request->role === 'superadmin' && $request->code !== 'SUPER123') {
            return back()->withErrors(['code' => 'Code incorrect pour le rôle Super Administrateur.'])->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'profile_image' => $imageUrl,
            'jobs' => $request->jobs,
        ]);

        $recaptchaResponse = $request->input('g-recaptcha-response');
$secret = env('RECAPTCHA_SECRET_KEY');

$response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
    'secret' => $secret,
    'response' => $recaptchaResponse,
]);

$result = json_decode($response->body());
if(!$result->success){
    return back()->withErrors(['captcha' => 'ReCAPTCHA invalide']);
}


        // Connecter automatiquement l'utilisateur
        Auth::login($user);
        session()->flash('success', 'Compte créé avec succès !');
        return redirect()->route('login')->with('success', 'Compte créé avec succès');
    }

    public function checkUnique(Request $request)
{
    $field = $request->field; 
    $value = $request->value;

    $exists = \App\Models\User::where($field, $value)->exists();

    return response()->json(['exists' => $exists]);
}

public function showPhoneLogin()
{
    return view('auth.phone-login'); // nom du fichier blade que tu vas créer
}

public function loginWithPhone(Request $request)
{
    $request->validate([
        'phone' => 'required',
        'firebase_uid' => 'required'
    ]);

    // Vérifie si un utilisateur existe déjà
    $user = User::firstOrCreate(
        ['phone' => $request->phone],
        [
            'name' => $request->phone,
            'password' => bcrypt(Str::random(8)), // mot de passe aléatoire
            'firebase_uid' => $request->firebase_uid
        ]
    );

    Auth::login($user);

    if ($user->role === 'admin') {
        return redirect()->route('dashboard.admin');
    } elseif ($user->role === 'manager') {
        return redirect()->route('dashboard.manager');
    } else {
        return redirect()->route('dashboard.user');
    }
}

    public function logout(Request $request)
{
    Auth::logout();              
    $request->session()->invalidate(); 
    $request->session()->regenerateToken(); 

    return redirect()->route('login'); 
}

public function checkField(Request $request)
{
    $field = $request->input('field');
    $value = $request->input('value');

    if (!in_array($field, ['name', 'email'])) {
        return response()->json(['exists' => false]);
    }

    $exists = \App\Models\User::where($field, $value)->exists();

    return response()->json(['exists' => $exists]);
}


}
