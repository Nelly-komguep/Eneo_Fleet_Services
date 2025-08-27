<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;

class ReservationExportController extends Controller
{
    public function export(Request $request)
    {
        $format = $request->format ?? 'csv';
        $reservations = Reservation::all();

        if ($format === 'pdf') {
            $pdf = PDF::loadView('exports.reservations_pdf', compact('reservations'));
            return $pdf->download('reservations.pdf');
        }

        // CSV pour Excel
        $filename = "reservations.csv";
        $columns = ['Type', 'Départ', 'Arrivée', 'Lieu Départ', 'Lieu Arrivée', 'Places', 'Passagers', 'Motif', 'Ordre Mission', 'Statut'];

        $callback = function() use ($reservations, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($reservations as $res) {
                fputcsv($file, [
                    $res->type_reservation,
                    $res->date_depart,
                    $res->date_arrivee,
                    $res->lieu_depart,
                    $res->lieu_arrive,
                    $res->nombre_places,
                    $res->liste_passagers,
                    $res->motif,
                    $res->ordre_mission,
                    $res->statut,
                ]);
            }
            fclose($file);
        };

        return Response::stream($callback, 200, [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename={$filename}"
        ]);
    }
}
