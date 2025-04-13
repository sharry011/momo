<?php
use App\Http\Controllers\dataProviderController;
use Illuminate\Support\Facades\Route;

Route::get('/data_provider/imdb/{id}', [dataProviderController::class, 'imdb'])->name('imdb');
Route::get('/data_provider/elcinema/{id}', [dataProviderController::class, 'elcinema'])->name('elcinema');

Route::get('/data_provider/search_persons/{search}', [dataProviderController::class, 'search_persons'])->name('search_persons');
Route::get('/data_provider/search_posts/{search}', [dataProviderController::class, 'search_posts'])->name('search_posts');
Route::get('/data_provider/search_seasons/{search}', [dataProviderController::class, 'search_seasons'])->name('search_seasons');
Route::get('/data_provider/search_series/{search}', [dataProviderController::class, 'search_series'])->name('search_series');
