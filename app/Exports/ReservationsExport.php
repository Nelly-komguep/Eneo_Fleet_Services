<?php

namespace App\Exports;

use App\Models\Reservation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReservationsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Reservation::select('id', 'user_id', 'date', 'statut', 'created_at')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Utilisateur',
            'Date',
            'Statut',
            'Créée le',
        ];
    }
}
