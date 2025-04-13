<?php

namespace App\Exceptions;

use App\Models\Bar;
use App\Models\Settings;
use App\Models\Settings_scripts;
use App\Models\SocialMedia;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        $settings = Settings::where('id', 1)->first();
        $settings_scripts = Settings_scripts::where('id', 1)->first();

        $social_media = SocialMedia::all();
        $header_bar = Bar::find(1);
        if ($header_bar) $header_bar->setHeaderItems();

        return response()->view('errors.404', [
            'settings' => $settings,
            'settings_scripts' => $settings_scripts,
            'header_bar' => $header_bar,
            'social_media' => $social_media
        ], 404);

        return parent::render($request, $exception);
    }

}
