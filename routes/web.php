<?php

use Illuminate\Support\Facades\Route;

$pages = [
    '/' => [
        'title' => 'Top Care Group | Buvnieciba, renovacija un ipasumu uzturesana Latvija',
        'description' => 'SIA Top Care Group nodrosina buvniecibas, renovacijas un ipasumu uzturesanas pakalpojumus privatpersonam, uznemumiem un namu apsaimniekotajiem visa Latvija.',
    ],
    '/pakalpojumi' => [
        'title' => 'Top Care Group pakalpojumi | Renovacija, fasades, jumti un labiekartosana',
        'description' => 'Apskatiet Top Care Group pakalpojumus: renovacijas un ieksdarbus, fasazu atjaunosanu, jumtu darbus, brugesanu, labiekartosanu un ipasumu uzturesanu.',
    ],
    '/par-mums' => [
        'title' => 'Par Top Care Group | Pieredzejusi komanda visa Latvija',
        'description' => 'Top Care Group ir Latvijas uznemums ar pieredzejusu komandu, kas strada pie buvniecibas, renovacijas un ipasumu uzturesanas projektiem visa Latvija.',
    ],
    '/pirms-pec' => [
        'title' => 'Top Care Group pirms un pec | Redzami rezultati objektos',
        'description' => 'Apskatiet Top Care Group paveikto darbu rezultatus fasazu un jumtu objektos pirms un pec darbu izpildes.',
    ],
    '/kontakti' => [
        'title' => 'Top Care Group kontakti | Sanemiet piedavajumu',
        'description' => 'Sazinieties ar Top Care Group par renovacijas, fasazu, jumtu, brugesanas, labiekartosanas un ipasumu uzturesanas darbiem visa Latvija.',
    ],
];

foreach ($pages as $path => $meta) {
    Route::get($path, function () use ($meta) {
        return view('welcome', $meta);
    });
}
