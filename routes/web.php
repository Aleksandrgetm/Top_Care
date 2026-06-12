<?php

use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

$pages = [
    '/' => [
        'title' => 'Top Care Group | Buvnieciba, renovacija un ipasumu uzturesana Latvija',
        'description' => 'SIA Top Care Group nodrosina buvniecibas, renovacijas un ipasumu uzturesanas pakalpojumus privatpersonam, uznemumiem un namu apsaimniekotajiem visa Latvija.',
        'canonical' => '/',
    ],
    '/pakalpojumi' => [
        'title' => 'Top Care Group pakalpojumi | Renovacija, fasades, jumti un labiekartosana',
        'description' => 'Apskatiet Top Care Group pakalpojumus: renovacijas un ieksdarbus, fasazu atjaunosanu, jumtu darbus, brugesanu, labiekartosanu un ipasumu uzturesanu.',
        'canonical' => '/pakalpojumi',
    ],
    '/par-mums' => [
        'title' => 'Par Top Care Group | Pieredzejusi komanda visa Latvija',
        'description' => 'Top Care Group ir Latvijas uznemums ar pieredzejusu komandu, kas strada pie buvniecibas, renovacijas un ipasumu uzturesanas projektiem visa Latvija.',
        'canonical' => '/par-mums',
    ],
    '/pirms-pec' => [
        'title' => 'Top Care Group pirms un pec | Redzami rezultati objektos',
        'description' => 'Apskatiet Top Care Group paveikto darbu rezultatus fasazu un jumtu objektos pirms un pec darbu izpildes.',
        'canonical' => '/pirms-pec',
    ],
    '/kontakti' => [
        'title' => 'Top Care Group kontakti | Sanemiet piedavajumu',
        'description' => 'Sazinieties ar Top Care Group par renovacijas, fasazu, jumtu, brugesanas, labiekartosanas un ipasumu uzturesanas darbiem visa Latvija.',
        'canonical' => '/kontakti',
    ],
    '/privatuma-politika' => [
        'title' => 'Privātuma politika | Top Care Group',
        'description' => 'Privātuma politika un informācija par personas datu apstrādi uzņēmumā Top Care Group.',
        'canonical' => '/privatuma-politika',
    ],
];

foreach ($pages as $path => $meta) {
    Route::get($path, function () use ($meta) {
        return view('welcome', $meta);
    });
}

Route::prefix('admin')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('admin.login');
        Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('admin.login.store');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', DashboardController::class)->name('admin.dashboard');
        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('admin.logout');
    });
});
