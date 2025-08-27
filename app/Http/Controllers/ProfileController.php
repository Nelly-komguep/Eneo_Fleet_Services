<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ProfileController extends Controller
{
    // public function updatePhoto(Request $request)
    // {
    //     $request->validate([
    //         'photo' => 'required|image|max:2048', // max 2Mo
    //     ]);

    //     // Upload sur Cloudinary
    //     $uploadedFileUrl = Cloudinary::upload($request->file('photo')->getRealPath())->getSecurePath();

    //     // Sauvegarde dans la BDD de l’utilisateur connecté
    //     $user = auth()->user();
    //     $user->profile_image = $uploadedFileUrl;
    //     $user->save();

    //     return back()->with('success', 'Photo de profil mise à jour avec succès !');
    // }
}
