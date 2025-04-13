<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use Spatie\Feed\Feed;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::feeds();

include __DIR__.'/admin.php';
include __DIR__.'/data_provider.php';
include __DIR__.'/sitemap.php';

// User Router
Route::get('/', [UserController::class, 'index'])->name('index');
Route::get('/film/{slug}', [UserController::class, 'film'])->name('film');
Route::get('/episode/{slug}', [UserController::class, 'episode'])->name('episode');
Route::get('/post/{slug}', [UserController::class, 'post'])->name('post');

Route::get('/watch/{slug}', [UserController::class, 'watch'])->name('watch');
Route::get('/download/{slug}', [UserController::class, 'download'])->name('download');

Route::get('/season/{slug}', [UserController::class, 'season'])->name('season');
Route::get('/season/{slug}/episodes', [UserController::class, 'season_episodes'])->name('season_episodes');

Route::get('/series/{slug}', [UserController::class, 'serie'])->name('serie');
Route::get('/series/{slug}/seasons', [UserController::class, 'serie_seasons'])->name('serie_seasons');

Route::get('/category/{slug}', [UserController::class, 'category'])->name('category');
Route::get('/release-year/{slug}', [UserController::class, 'year'])->name('year');
Route::get('/quality/{slug}', [UserController::class, 'quality'])->name('quality');
Route::get('/genre/{slug}', [UserController::class, 'genre'])->name('quality');

Route::get('/search', [UserController::class, 'search'])->name('search');

Route::get('/actor/{slug}', [UserController::class, 'actor'])->name('actor');
Route::get('/director/{slug}', [UserController::class, 'director'])->name('director');
Route::get('/writer/{slug}', [UserController::class, 'writer'])->name('writer');

//Route::get('/tt', function () {
//    return view('welcome');
//});


Route::get('/generate-sitemap', function () {
    $filePath = storage_path('sitemap_last_updated.txt');

    if (File::exists($filePath)) {
        $lastUpdated = File::get($filePath);
        $lastUpdated = \Carbon\Carbon::parse($lastUpdated);
    } else {
        $lastUpdated = null;
    }

        Artisan::call('sitemap:generate');
        File::put($filePath, now());

        return "Sitemap generated successfully.";

});

//Redis Test
Route::get('/redis-test', function () {
    try {
        // Store value using Cache facade (Laravel will automatically use Redis if configured)
        Cache::put('redis_cache_test_key', 'Redis is working via Laravel Cache!', 600); // 10 mins

        // Retrieve value from Cache facade (this will get it from Redis if Redis is the backend)
        $value = Cache::get('redis_cache_test_key');

        if ($value) {
            return response()->json([
                'cache_driver' => config('cache.default'),
                'redis_raw_value' => $value,
                'status' => 'Success — Laravel Cache is using Redis backend properly.',
            ]);
        } else {
            return response()->json([
                'status' => 'Key not found in Cache.',
            ]);
        }

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'Redis error',
            'message' => $e->getMessage(),
        ]);
    }
});


