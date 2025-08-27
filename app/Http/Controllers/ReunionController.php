<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reunion;
use Illuminate\Support\Facades\Auth;

class ReunionController extends Controller
{
    public function index()
    {
        $reunions = Reunion::orderBy('date')->
        view('dashboard-reunions', compact('reunions'));
    }


     public function create()
    {
        return view('reunions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string',
            'date' => 'required|date',
            'heure_debut' => 'required',
            'heure_fin' => 'required',
        ]);

        Reunion::create($request->all());

        return redirect()->route('reunions.index')->with('success', 'Réunion ajoutée avec succès');
    }
}
