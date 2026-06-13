<?php

use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\GalleryImageController;
use App\Http\Controllers\Admin\PageController;
use App\Models\GalleryImage;
use App\Models\Page;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

$pages = [
    '/' => [
        'page_slug' => 'sakums',
        'title' => 'Top Care Group | Buvnieciba, renovacija un ipasumu uzturesana Latvija',
        'description' => 'SIA Top Care Group nodrosina buvniecibas, renovacijas un ipasumu uzturesanas pakalpojumus privatpersonam, uznemumiem un namu apsaimniekotajiem visa Latvija.',
        'canonical' => '/',
    ],
    '/pakalpojumi' => [
        'page_slug' => 'pakalpojumi',
        'title' => 'Top Care Group pakalpojumi | Renovacija, fasades, jumti un labiekartosana',
        'description' => 'Apskatiet Top Care Group pakalpojumus: renovacijas un ieksdarbus, fasazu atjaunosanu, jumtu darbus, brugesanu, labiekartosanu un ipasumu uzturesanu.',
        'canonical' => '/pakalpojumi',
    ],
    '/galerija' => [
        'page_slug' => 'galerija',
        'title' => 'Top Care Group galerija | Darbi un ikdienas process',
        'description' => 'Apskatiet Top Care Group paveiktos darbus, darba procesu un ikdienas objektos visa Latvija.',
        'canonical' => '/galerija',
    ],
    '/par-mums' => [
        'page_slug' => 'par-mums',
        'title' => 'Par Top Care Group | Pieredzejusi komanda visa Latvija',
        'description' => 'Top Care Group ir Latvijas uznemums ar pieredzejusu komandu, kas strada pie buvniecibas, renovacijas un ipasumu uzturesanas projektiem visa Latvija.',
        'canonical' => '/par-mums',
    ],
    '/pirms-pec' => [
        'page_slug' => 'pirms-pec',
        'title' => 'Top Care Group pirms un pec | Redzami rezultati objektos',
        'description' => 'Apskatiet Top Care Group paveikto darbu rezultatus fasazu un jumtu objektos pirms un pec darbu izpildes.',
        'canonical' => '/pirms-pec',
    ],
    '/kontakti' => [
        'page_slug' => 'kontakti',
        'title' => 'Top Care Group kontakti | Sanemiet piedavajumu',
        'description' => 'Sazinieties ar Top Care Group par renovacijas, fasazu, jumtu, brugesanas, labiekartosanas un ipasumu uzturesanas darbiem visa Latvija.',
        'canonical' => '/kontakti',
    ],
    '/privatuma-politika' => [
        'page_slug' => 'privatuma-politika',
        'title' => 'Privātuma politika | Top Care Group',
        'description' => 'Privātuma politika un informācija par personas datu apstrādi uzņēmumā Top Care Group.',
        'canonical' => '/privatuma-politika',
    ],
];

foreach ($pages as $path => $meta) {
    Route::get($path, function () use ($meta) {
        $pageSlug = $meta['page_slug'] ?? null;
        $defaults = $pageSlug ? (config("site_pages.pages.{$pageSlug}.defaults") ?? []) : [];
        $page = $pageSlug && Schema::hasTable('pages')
            ? Page::query()->where('slug', $pageSlug)->first()
            : null;
        $galleryImages = $pageSlug === 'galerija' && Schema::hasTable('gallery_images')
            ? GalleryImage::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('created_at')
                ->get(['title', 'category', 'image'])
                ->map(fn (GalleryImage $image) => [
                    'title' => $image->title,
                    'category' => $image->category,
                    'image' => $image->image,
                ])
                ->values()
                ->all()
            : [];

        return view('welcome', [
            ...$meta,
            'pageSlug' => $pageSlug,
            'pageContent' => array_replace($defaults, $page?->content ?? []),
            'galleryImages' => $galleryImages,
        ]);
    });
}

Route::get('/media/{path}', function (string $path) {
    abort_unless(str_starts_with($path, 'pages/') || str_starts_with($path, 'gallery/'), 404);
    abort_unless(Storage::disk('public')->exists($path), 404);

    return Storage::disk('public')->response($path);
})->where('path', '.*')->name('media.public');

Route::get('/login', fn () => redirect()->route('admin.login'))->name('login');

Route::prefix('admin')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('admin.login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('admin.login.store');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::redirect('/', '/admin/pages')->name('admin.dashboard');
        Route::get('/pages', [PageController::class, 'index'])->name('admin.pages.index');
        Route::get('/pages/{page}/edit', [PageController::class, 'edit'])->name('admin.pages.edit');
        Route::put('/pages/{page}', [PageController::class, 'update'])->name('admin.pages.update');
        Route::get('/gallery-images', [GalleryImageController::class, 'index'])->name('admin.gallery-images.index');
        Route::post('/gallery-images', [GalleryImageController::class, 'store'])->name('admin.gallery-images.store');
        Route::put('/gallery-images/{galleryImage}', [GalleryImageController::class, 'update'])->name('admin.gallery-images.update');
        Route::delete('/gallery-images/{galleryImage}', [GalleryImageController::class, 'destroy'])->name('admin.gallery-images.destroy');
        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('admin.logout');
    });
});
