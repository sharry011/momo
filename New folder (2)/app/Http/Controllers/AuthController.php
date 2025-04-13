<?php

namespace App\Http\Controllers;

use App\Models\Bar;
use App\Models\Settings;
use App\Models\Settings_scripts;
use App\Models\SocialMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function login_page()
    {
        if (auth()->check()) {
            return redirect()->route('admin.articles.all.get');
        }
        $settings = Settings::find(1);
        $settings_scripts = Settings_scripts::find(1);
        $social_media = SocialMedia::all();
        $header_bar = Bar::find(1);
        if ($header_bar) $header_bar->setHeaderItems();
        return view('pages/login', compact('settings', 'settings_scripts', 'social_media', 'header_bar'));
    }

    public function try_login()
    {
        validator(request()->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ])->validate();

        if (auth()->attempt(request()->only('email', 'password'))) {
            return redirect()->route('admin.articles.all.get');
        }

        return redirect()->route('login')
            ->withErrors(['email' => 'Email or password is wrong.']);
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->route('index');
    }
}
