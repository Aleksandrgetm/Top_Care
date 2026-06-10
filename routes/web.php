<?php

use Illuminate\Support\Facades\Route;

$pages = [
    '/' => [
        'title' => 'TopCare | Fasazu, jumtu un teritoriju tirsana Latvija',
        'description' => 'TopCare nodrosina profesionalu fasazu, jumtu un teritoriju tirsanu visa Latvija.',
    ],
    '/pakalpojumi' => [
        'title' => 'TopCare pakalpojumi | Fasazu, jumtu un brugja tirsana',
        'description' => 'Apskatiet visus TopCare pakalpojumus: fasazu, jumtu, brugja un metala jumtu tirsanu, malkas pakalpojumus un ipasumu uzturesanu.',
    ],
    '/par-mums' => [
        'title' => 'Par TopCare | Profesionala komanda visa Latvija',
        'description' => 'TopCare ir Latvijas uznemums ar pieredzejusu komandu, kas strada ar fasazu, jumtu, brugja un teritoriju tirsanas objektiem visa Latvija.',
    ],
    '/pirms-pec' => [
        'title' => 'TopCare pirms un pec | Redzami tirsanas rezultati',
        'description' => 'Salidziniet TopCare objektus pirms un pec fasazu un jumtu tirsanas darbiem.',
    ],
    '/kontakti' => [
        'title' => 'TopCare kontakti | Sanemiet piedavajumu',
        'description' => 'Sazinieties ar TopCare, lai sanemtu piedavajumu fasazu, jumtu, brugja un teritoriju tirsanas darbiem.',
    ],
];

foreach ($pages as $path => $meta) {
    Route::get($path, function () use ($meta) {
        return view('welcome', $meta);
    });
}
