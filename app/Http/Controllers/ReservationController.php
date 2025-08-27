<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Exports\ReservationsExport;
use Barryvdh\DomPDF\Facade\Pdf;


class ReservationController extends Controller
{

    public function create() {
        return view('reservation');
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'type_reservation' => 'required|string',
            'date_depart' => 'required|date',
            'date_arrivee' => 'required|date',
            'lieu_depart' => 'required|string',
            'lieu_arrive' => 'required|string',
            'nombre_places' => 'required|integer',
            'liste_passagers' => 'required|string',
            'motif' => 'required|string',
            'ordre_mission' => 'required|string',
            'statut' => 'nullable|string'
        ]);

       
        $validated['statut'] = 'en cours';
        $validated['user_id'] = Auth::id();

        Reservation::create($validated);
        $role = Auth::user()->role;

    if ($role === 'superadmin') {
        return redirect()->route('dashboard-superAdmin')->with('success', 'Réservation créée avec succès');
    } elseif ($role === 'admin') {
        return redirect()->route('dashboard')->with('success', 'Réservation créée avec succès');
    } else {
        return redirect()->route('dashboard.user')->with('success', 'Réservation créée avec succès');
    }

    }

        public function dashboardreservation() {
            // $reservations = reservation::all();
            $reservations = Reservation::with('user')->orderBy('created_at', 'desc')->paginate(10);
             $total = $reservations->count();
             $validees = $reservations->where('statut', 'validee')->count();
             $rejetee = $reservations->where('statut', 'rejetee')->count();
             $encours  = $reservations->where('statut', 'en cours')->count();

              $reservationsParJour = Reservation::select(
        DB::raw('DATE(created_at) as date'),
        DB::raw('count(*) as total')
    )
    ->where('created_at', '>=', now()->subDays(6))
    ->groupBy('date')
    ->orderBy('date')
    ->get();

    $reservationLabels = $reservationsParJour->pluck('date')->toArray();
    $reservationValues = $reservationsParJour->pluck('total')->toArray();

    // $reservations = Reservation::latest()->get();
    
        return view('dashboard', compact(
        'total',
        'validees',
        'rejetee',
        'encours',
        'reservationsParJour',
        'reservationLabels',
        'reservationValues',
        'reservations'
    ));
        }

    public function dashboardUser()
    {
         $user = Auth::user();

    if ($user->role === 'user') {
        $reservations = Reservation::where('user_id', $user->id)->orderBy('created_at', 'desc')
            ->paginate(10);
    } else {
        // $reservations = Reservation::all();
         $reservations = Reservation::with('user')->orderBy('created_at', 'desc')->paginate(10);
    }
        // $reservations = Reservation::all();
        $total = $reservations->count();
        $validees = $reservations->where('statut', 'validee')->count();
        $rejetee = $reservations->where('statut', 'rejetee')->count();
        $encours  = $reservations->where('statut', 'en cours')->count();

      return view('dashboard-user', compact('reservations', 'total', 'validees', 'rejetee', 'encours'));
    }
    
    
    public function dashboardAdmin()
    {
        // $reservations = Reservation::all();
         $reservations = Reservation::with('user')->orderBy('created_at', 'desc')->paginate(10);
        $total = $reservations->count();
        $validees = $reservations->where('statut', 'validee')->count();
        $rejetee = $reservations->where('statut', 'rejetee')->count();
        $encours  = $reservations->where('statut', 'en cours')->count();
   
        $reservationsParJour = Reservation::select(
        DB::raw('DATE(created_at) as date'),
        DB::raw('count(*) as total')
    )
    ->where('created_at', '>=', now()->subDays(6))
    ->groupBy('date')
    ->orderBy('date')
    ->get();

    $reservationLabels = $reservationsParJour->pluck('date')->toArray();
    $reservationValues = $reservationsParJour->pluck('total')->toArray();

    // $reservations = Reservation::latest()->get();

        return view('dashboard-superAdmin', compact(
        'total',
        'validees',
        'rejetee',
        'encours',
        'reservationsParJour',
        'reservationLabels',
        'reservationValues',
        'reservations'
    ));
}
    

public function updateStatus(Request $request, $id)
{
    $reservation = Reservation::findOrFail($id);
    $reservation->statut = $request->statut;
    $reservation->save();

    return redirect()->back()->with('success', 'Statut mis à jour.');
}

public function filtrerParStatut($statut)
{
    $reservations = Reservation::where('statut', $statut)->get();

    $total = Reservation::count();
    $validees = Reservation::where('statut', 'validee')->count();
    $rejetee = Reservation::where('statut', 'rejetee')->count();
    $encours = Reservation::where('statut', 'en cours')->count();

    return view('dashboard', compact('reservations', 'total', 'validees', 'rejetee', 'encours', 'statut'));
}

public function dashboardUserByStatut($statut)
{
    $reservations = Reservation::where('statut', $statut)->get();
    $total = Reservation::count();
    $validees = Reservation::where('statut', 'validee')->count();
    $rejetee = Reservation::where('statut', 'rejetee')->count();
    $encours  = Reservation::where('statut', 'en cours')->count();

    return view('dashboard-user', compact('reservations', 'total', 'validees', 'rejetee', 'encours', 'statut'));
}

public function filtreParStatut($statut)
{
    $reservations = Reservation::where('statut', $statut)->get();
    $total = Reservation::count();
    $validees = Reservation::where('statut', 'validee')->count();
    $rejetee = Reservation::where('statut', 'rejetee')->count();
    $encours = Reservation::where('statut', 'en cours')->count();

    return view('dashboard-superAdmin', compact('reservations', 'total', 'validees', 'rejetee', 'encours', 'statut'));
}

public function update(Request $request, $id)
{
    $reservation = Reservation::findOrFail($id);
    $reservation->update($request->all());

    return redirect()->back()->with('success', 'Réservation mise à jour avec succès.');
}

public function destroy($id)
{
    Reservation::destroy($id);
    return redirect('/dashboard-user');
}

public function index()
{
    $reservations = reservation::all();
    return view('dashboard', compact('reservations'));
}
 // Export PDF
    public function exportPdf()
    {
        $reservations = Reservation::all();
        $pdf = Pdf::loadView('exports.reservations_pdf', compact('reservations'));
        return $pdf->download('reservations.pdf');
    }

    // Export CSV
    public function exportCsv()
    {
        $reservations = Reservation::all();
        $filename = "reservations.csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'Type', 'Départ', 'Arrivée', 'Lieu Départ', 'Lieu Arrivée',
            'Places', 'Passagers', 'Motif', 'Ordre Mission', 'Statut'
        ];

        $callback = function() use ($reservations, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach($reservations as $reservation) {
                fputcsv($file, [
                    $reservation->type_reservation,
                    $reservation->date_depart,
                    $reservation->date_arrivee,
                    $reservation->lieu_depart,
                    $reservation->lieu_arrive,
                    $reservation->nombre_places,
                    $reservation->liste_passagers,
                    $reservation->motif,
                    $reservation->ordre_mission,
                    ucfirst($reservation->statut ?? 'en cours'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}


