<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ReunionController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\PhoneAuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationExportController;
use App\Http\Controllers\VehiculeController;

use function PHPUnit\Framework\callback;

Route::get('/', function () {
    return view('login');
});

// Authentification

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'log']);

Route::get('/register', [AuthController::class, 'showRegistration'])->name('register');
Route::post('/register', [AuthController::class, 'reg']);

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Formulaire et création
Route::get('/reservation', function () {
    return view('reservation');
})->name('reservation');

Route::post('/reservation', [ReservationController::class, 'store'])
    ->name('reservation.store')
    ->middleware('auth');

Route::get('/reservation-dashboard', [ReservationController::class, 'create'])->name('reservation.dashboard');
Route::post('/reservation-dashboard', [ReservationController::class, 'store'])->name('reserve.store');
Route::resource('reservations', ReservationController::class);
// Mise à jour et suppression
Route::put('/reservations/{id}', [ReservationController::class, 'update'])->name('reservations.update');
Route::put('/reservations/{id}/statut', [ReservationController::class, 'updateStatus'])->name('reservations.updateStatus');

// Route::resource('reservations', ReservationController::class);

// Dashboards avec contrôle des rôles

Route::get('/dashboard-user', [ReservationController::class, 'dashboardUser'])
    ->name('dashboard.user')
    ->middleware(['auth', 'role:user']);

Route::get('/dashboard', [ReservationController::class, 'dashboardreservation'])
    ->name('dashboard')
    ->middleware(['auth', 'role:admin']);

Route::get('/dashboard-superAdmin', [ReservationController::class, 'dashboardAdmin'])
    ->name('dashboard-superAdmin')
    ->middleware(['auth', 'role:superadmin']);

//filtre de statuts

Route::get('/dashboard/statut/{statut}', [ReservationController::class, 'filtrerParStatut'])->name('dashboard.filtre');
Route::get('/dashboard-user/statut/{statut}', [ReservationController::class, 'dashboardUserByStatut'])->name('dashboard.user.statut');
Route::get('/dashboard-superAdmin/statut/{statut}', [ReservationController::class, 'filtreParStatut'])->name('dashboard.superAdmin.statut');

Route::get('/check-unique', [App\Http\Controllers\AuthController::class, 'checkUnique'])->name('check.unique');
Route::post('/check-field', [AuthController::class, 'checkField']);

Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');

// Route::get('login/phone', [PhoneAuthController::class, 'showPhoneForm'])->name('login.phone');
// Route::post('login/phone/send', [PhoneAuthController::class, 'sendOTP'])->name('phone.send');
// Route::post('login/phone/verify', [PhoneAuthController::class, 'verifyOTP'])->name('phone.verify');
// Route::post('/phone-login', [AuthController::class, 'loginWithPhone'])->name('phone.login');
// Route::get('/phone', [AuthController::class, 'showPhoneLogin'])->name('phone.login.page');
// Route::post('/phone-login', [AuthController::class, 'loginWithPhone'])->name('phone.login');

// Route::get('/dashboardVehicule', function () {
//     return view('dashboardVehicule');
// })->name('dashboardVehicule');

Route::get('/export-pdf', [ReservationController::class, 'exportPdf'])->name('reservations.exportPdf');
Route::get('/export-csv', [ReservationController::class, 'exportCsv'])->name('reservations.exportCsv');

Route::get('login/twitter', [GoogleController::class, 'redirectToTwitter'])->name('login.twitter');
Route::get('login/twitter/callback', [GoogleController::class, 'handleTwitterCallback']);

// Route::resource('vehicules', VehiculeController::class);
Route::get('/dashboardVehicule', [VehiculeController::class, 'index'])->name('dashboardVehicule');
Route::post('/dashboardVehicule', [VehiculeController::class, 'store'])->name('vehicules.store.dashboard');

Route::put('/vehicules/{vehicule}', [VehiculeController::class, 'update'])->name('vehicules.update');
Route::delete('/vehicules/{vehicule}', [VehiculeController::class, 'destroy'])->name('vehicules.destroy');


// Route::get('login/facebook', [GoogleController::class, 'redirectToFacebook'])->name('login.facebook');
// Route::get('login/facebook/callback', [GoogleController::class, 'handleFacebookCallback']);

// Route::get('/', function () {
//     return view('login');
// });

// Route::get('/reservation', function() {
//     return view('reservation');
// })->name('reservation');

// Route::post('/reservation', [ReservationController::class, 'store'])
//     ->name('reservation.store')
//     ->middleware('auth');  


// Route::get('/dashboard', function() {
//     return view('dashboard');
// })->middleware(['auth', 'role:admin', 'role:superadmin']);

// Route::get('/dashboard-user', function() {
//     return view('dashboard-user');
// })->middleware(['auth', 'role:user']);

// Route::get('/registrer', function() {
//     return view('registrer');
// })->name('registrer');

// Route::get('/dashboard-superAdmin', function() {
//     return view('dashboard-superAdmin');
// })->name('dashboard-superAdmin');

// Route::put('/reservations/{id}', [ReservationController::class, 'update'])->name('reservations.update');
// // route pour la mise a jour du statut
// Route::put('/reservations/{id}/statut', [ReservationController::class, 'updateStatus'])->name('reservations.updateStatus');

// Route::get('/dashboard', [ReservationController::class, 'dashboardreservation' ])->name('dashboard-user');
// Route::get('/dashboard-user', [ReservationController::class, 'dashboardUser'])->name('dashboard.user');
// Route::get('/dashboard-superAdmin', [ReservationController::class, 'dashboardAdmin' ])->name('dashboard-superAdmin');

// Route::get('/dashboard-user', [ReservationController::class, 'dashboardreservation' ])->name('dashboard-user');
// Route::get('/reservation-dashboard', [ReservationController::class, 'create' ])->name('reservation');
// Route::post('/reservation-dashboard', [ReservationController::class, 'store' ])->name('reserve.store');
// Route::get('/dashboard', [ReservationController::class, 'dashboardreservation' ])->name('dashboard');

// Route::resource('reservations', ReservationController::class);

// // pour filtrer les statuts 
// Route::get('/dashboard/statut/{statut}', [ReservationController::class, 'filtrerParStatut'])->name('dashboard.filtre');
// Route::get('/dashboard-user/statut/{statut}', [ReservationController::class, 'dashboardUserByStatut'])->name('dashboard.user.statut');
// Route::get('/dashboard-superAdmin/statut/{statut}', [ReservationController::class, 'filtreParStatut'])->name('dashboard.superAdmin.statut');

// Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
// Route::post('/login', [AuthController::class, 'log']);

// Route::get('/register', [AuthController::class, 'showRegistration'])->name('register');
// Route::post('/register', [AuthController::class, 'reg']);

// Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Route::get('/dashboard-user', [ReservationController::class, 'dashboardUser'])
//     ->name('dashboard.user')
//     ->middleware(['auth', 'role:user']);

// Route::get('/dashboard-admin', [ReservationController::class, 'dashboardreservation'])
//     ->name('dashboard.admin')
//     ->middleware(['auth', 'role:admin']);

// Route::get('/dashboard-superAdmin', [ReservationController::class, 'dashboardAdmin'])
//     ->name('dashboard.superAdmin')
//     ->middleware(['auth', 'role:superadmin']);
