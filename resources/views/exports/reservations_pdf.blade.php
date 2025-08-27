<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des réservations</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 5px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h2>Liste des Réservations</h2>
    <table>
        <thead>
            <tr>
                <th>Type</th>
                <th>Départ</th>
                <th>Arrivée</th>
                <th>Lieu Départ</th>
                <th>Lieu Arrivée</th>
                <th>Places</th>
                <th>Passagers</th>
                <th>Motif</th>
                <th>Ordre Mission</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservations as $reservation)
            <tr>
                <td>{{ $reservation->type_reservation }}</td>
                <td>{{ $reservation->date_depart }}</td>
                <td>{{ $reservation->date_arrivee }}</td>
                <td>{{ $reservation->lieu_depart }}</td>
                <td>{{ $reservation->lieu_arrive }}</td>
                <td>{{ $reservation->nombre_places }}</td>
                <td>{{ $reservation->liste_passagers }}</td>
                <td>{{ $reservation->motif }}</td>
                <td>{{ $reservation->ordre_mission }}</td>
                <td>{{ ucfirst($reservation->statut ?? 'en cours') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
