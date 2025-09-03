<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicule;

class VehiculeController extends Controller
{
    public function index()
{
    $vehicules = Vehicule::all();

    return view('dashboardVehicule', compact('vehicules'));

}
public function store(Request $request)
    {
        $data = $request->validate([
            'marque'         => 'required|string|max:255',
            'modele'         => 'required|string|max:255',
            'chauffeur'      => 'required|string|max:255',
            'disponibilite'  => 'required|in:Disponible,Indisponible,En réparation',
            'places_total'   => 'required|integer|min:1',
            'places_restantes'=> 'nullable|integer', 
        ]);

        $data['places_restantes'] = $data['places_total'];

        $vehicule = Vehicule::create($data);

         // Réponse JSON si AJAX
    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'vehicule' => $vehicule
        ]);
    }
    
        return redirect()->route('dashboardVehicule')
                         ->with('success', 'Véhicule ajouté avec succès.');
    }
    
 
public function update(Request $request, Vehicule $vehicule){
    $vehicule->update($request->all());
    return redirect()->back()->with('success', 'Véhicule mis à jour avec succès');
}

public function destroy(Vehicule $vehicule){
    $vehicule->delete();
    return redirect()->back()->with('success', 'Véhicule supprimé avec succès');
}
}
