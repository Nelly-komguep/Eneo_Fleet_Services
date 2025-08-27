<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('type_reservation');
            $table->date('date_depart');
            $table->date('date_arrivee');
            $table->string('lieu_depart');
            $table->string('lieu_arrive');
            $table->integer('nombre_places');
            $table->string('liste_passagers');
            $table->string('motif');
            $table->string('ordre_mission');
            $table->string('statut')->default('en cours');
            $table->timestamps();
        });

    }

    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
