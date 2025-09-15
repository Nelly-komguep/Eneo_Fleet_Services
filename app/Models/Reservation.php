<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_reservation',
        'date_depart',
        'date_arrivee',
        'lieu_depart',
        'lieu_arrive',
        'nombre_places',
        'liste_passagers',
        'motif',
        'ordre_mission',
        'statut',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
