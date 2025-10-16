<?php

use App\Http\Controllers\LinkController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QRCodeController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function (\App\Services\AnalyticsService $analyticsService) {
        $user = auth()->user();

        $stats = array_merge(
            [
                'total_links' => $user->shortLinks()->count(),
            ],
            $analyticsService->getUserStats($user->id)
        );

        $recentLinks = $user->shortLinks()
            ->withCount('clickLogs as click_count')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($link) {
                $link->public_url = url('/' . $link->slug);

                return $link;
            });

        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'recentLinks' => $recentLinks,
        ]);
    })->name('dashboard');

    // Link management
    Route::resource('links', LinkController::class);

    // Profile management
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // QR Code generation
    Route::get('/links/{link}/qr', [QRCodeController::class, 'generate'])->name('links.qr');
    Route::get('/links/{link}/qr/download', [QRCodeController::class, 'download'])->name('links.qr.download');
});

// Public routes
Route::get('/{slug}', [LinkController::class, 'redirect'])->name('links.redirect')->where('slug', '[a-zA-Z0-9\-]+');
Route::get('/@{profileSlug}', [ProfileController::class, 'show'])->name('profile.public')->where('profileSlug', '[a-zA-Z0-9_\-]+');

require __DIR__ . '/auth.php';
