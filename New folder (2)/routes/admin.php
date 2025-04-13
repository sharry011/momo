<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

use App\Models\Settings;

$admin_link = Settings::find(1)->admin_link;

Route::get('/getadminlink/8202', function () use ($admin_link) {
    return $admin_link;
});

Route::group(['prefix' => $admin_link], function () {
    Route::get('/', [AuthController::class, 'login_page'])->name('login');
    Route::post('/', [AuthController::class, 'try_login'])->name('try_login');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::group(['prefix' => $admin_link, 'middleware' => ['auth', 'admin']], function () {

    Route::get('/articles', [AdminController::class, 'articles_all'])->name('admin.articles.all.get');
    Route::get('/articles/new', [AdminController::class, 'articles_new_page'])->name('admin.articles.new.get');
    Route::post('/articles/new', [AdminController::class, 'articles_new'])->name('admin.articles.new.post');
    Route::get('/articles/{id}', [AdminController::class, 'articles_update_page'])->name('admin.articles.update.get');
    Route::post('/articles/{id}/update', [AdminController::class, 'articles_update'])->name('admin.articles.update.post');
    Route::get('/articles/{id}/copy', [AdminController::class, 'articles_copy_page'])->name('admin.articles.copy.get');
    Route::get('/articles/{id}/copy_from_season', [AdminController::class, 'articles_copy_from_season'])->name('admin.articles.copy_from_season.get');
    Route::post('/articles/delete', [AdminController::class, 'articles_delete'])->name('admin.articles.delete');
    Route::post('/articles/update_time', [AdminController::class, 'articles_update_time'])->name('admin.articles.update_time');
    Route::post('/articles/unpin', [AdminController::class, 'articles_unpin'])->name('admin.articles.unpin');
    Route::post('/articles/pin', [AdminController::class, 'articles_pin'])->name('admin.articles.pin');
    Route::post('/articles/hide', [AdminController::class, 'articles_hide'])->name('admin.articles.hide');
    Route::post('/articles/show', [AdminController::class, 'articles_show'])->name('admin.articles.show');

    Route::get('/seasons', [AdminController::class, 'seasons_all'])->name('admin.seasons.all.get');
    Route::get('/seasons/new', [AdminController::class, 'seasons_new_page'])->name('admin.seasons.new.get');
    Route::post('/seasons/new', [AdminController::class, 'seasons_new'])->name('admin.seasons.new.post');
    Route::get('/seasons/{id}', [AdminController::class, 'seasons_update_page'])->name('admin.seasons.update.get');
    Route::post('/seasons/{id}/update', [AdminController::class, 'seasons_update'])->name('admin.seasons.update.post');
    Route::get('/seasons/{id}/copy', [AdminController::class, 'seasons_copy_page'])->name('admin.seasons.copy.get');
    Route::get('/seasons/{id}/copy_from_serie', [AdminController::class, 'seasons_copy_from_serie'])->name('admin.seasons.copy_from_serie.get');
    Route::post('/seasons/delete', [AdminController::class, 'seasons_delete'])->name('admin.seasons.delete');

    Route::get('/series', [AdminController::class, 'series_all'])->name('admin.series.all.get');
    Route::get('/series/new', [AdminController::class, 'series_new_page'])->name('admin.series.new.get');
    Route::post('/series/new', [AdminController::class, 'series_new'])->name('admin.series.new.post');
    Route::get('/series/{id}/copy', [AdminController::class, 'series_copy_page'])->name('admin.series.copy.get');
    Route::get('/series/{id}', [AdminController::class, 'series_update_page'])->name('admin.series.update.get');
    Route::post('/series/{id}/update', [AdminController::class, 'series_update'])->name('admin.series.update.post');
    Route::post('/series/delete', [AdminController::class, 'series_delete'])->name('admin.series.delete');

    Route::get('/site_settings', [AdminController::class, 'site_settings_page'])->name('admin.site_settings.get');
    Route::post('/site_settings', [AdminController::class, 'update_site_settings'])->name('admin.site_settings.post');

    Route::get('/site_settings_scripts', [AdminController::class, 'site_settings_scripts_page'])->name('admin.site_settings_scripts.get');
    Route::post('/site_settings_scripts', [AdminController::class, 'update_site_settings_scripts'])->name('admin.site_settings_scripts.post');

    Route::get('/bars/{id}', [AdminController::class, 'bar_page'])->name('admin.bar.get');
    Route::post('/bars/{id}/add_item', [AdminController::class, 'bar_add_item'])->name('admin.bar.add_item.post');
    Route::post('/bars/{id}/update', [AdminController::class, 'bar_update'])->name('admin.bar.bar_update.post');

    Route::get('/categories', [AdminController::class, 'categories_all'])->name('admin.categories.all.get');
    Route::get('/categories/new', [AdminController::class, 'categories_new_page'])->name('admin.categories.new.get');
    Route::post('/categories/new', [AdminController::class, 'categories_new'])->name('admin.categories.new.post');
    Route::post('/categories/{id}/delete', [AdminController::class, 'categories_delete'])->name('admin.categories.delete.post');
    Route::get('/categories/{id}/update', [AdminController::class, 'categories_update_page'])->name('admin.categories.update.get');
    Route::post('/categories/{id}/update', [AdminController::class, 'categories_update'])->name('admin.categories.update.post');

    Route::get('/types', [AdminController::class, 'types_all'])->name('admin.types.all.get');
    Route::post('/types/new', [AdminController::class, 'types_new'])->name('admin.types.new.post');;
    Route::post('/types/{id}/update', [AdminController::class, 'types_update'])->name('admin.types.update.post');
    Route::post('/types/{id}/delete', [AdminController::class, 'types_delete'])->name('admin.types.delete.post');

    Route::get('/qualities', [AdminController::class, 'qualities_all'])->name('admin.qualities.all.get');
    Route::post('/qualities/new', [AdminController::class, 'qualities_new'])->name('admin.qualities.new.post');
    Route::post('/qualities/{id}/update', [AdminController::class, 'qualities_update'])->name('admin.qualities.update.post');
    Route::post('/qualities/{id}/delete', [AdminController::class, 'qualities_delete'])->name('admin.qualities.delete.post');

    Route::get('/watch_servers', [AdminController::class, 'watch_servers_all'])->name('admin.watch_servers.all.get');
    Route::post('/watch_servers/new', [AdminController::class, 'watch_servers_new'])->name('admin.watch_servers.new.post');
    Route::post('/watch_servers/update', [AdminController::class, 'watch_servers_update'])->name('admin.watch_servers.update.post');

    Route::get('/down_servers', [AdminController::class, 'down_servers_all'])->name('admin.down_servers.all.get');
    Route::post('/down_servers/new', [AdminController::class, 'down_servers_new'])->name('admin.down_servers.new.post');
    Route::post('/down_servers/update', [AdminController::class, 'down_servers_update'])->name('admin.down_servers.update.post');

    Route::get('/glide', [AdminController::class, 'glide_page'])->name('admin.glide.get');
    Route::post('/glide/new_post', [AdminController::class, 'glide_new_post'])->name('admin.glide_new_post.post');
    Route::post('/glide/remove_post', [AdminController::class, 'glide_remove_post'])->name('admin.glide_remove_post.post');

    Route::get('/social_media', [AdminController::class, 'social_media_page'])->name('admin.social_media.get');
    Route::post('/social_media/new', [AdminController::class, 'social_media_new'])->name('admin.social_media_new.post');
    Route::post('/social_media/update', [AdminController::class, 'social_media_update'])->name('admin.social_media_update.post');
    Route::post('/social_media/delete', [AdminController::class, 'social_media_delete'])->name('admin.social_media_delete.post');

    Route::get('/accounts', [AdminController::class, 'accounts_all'])->name('admin.accounts.all.get');
    Route::post('/accounts/delete', [AdminController::class, 'accounts_delete'])->name('admin.accounts.delete.post');
    Route::post('/accounts/new', [AdminController::class, 'accounts_new'])->name('admin.accounts.new.post');
    Route::post('/accounts/update_info', [AdminController::class, 'accounts_update_info'])->name('admin.accounts.update_info.post');
    Route::post('/accounts/update_password', [AdminController::class, 'accounts_update_password'])->name('admin.accounts.update_password.post');

//    Route::get('/sitemap', [AdminController::class, 'sitemap_page'])->name('admin.sitemap.get');
//    Route::post('/sitemap', [AdminController::class, 'update_sitemap'])->name('admin.sitemap.post');

//    Route::get('/add_xml', [AdminController::class, 'add_post_to_xml'])->name('admin.logs.get');
});


