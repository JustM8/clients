<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
    public function index($locale)
    {
        if (isset($locale) && in_array($locale, config('app.available_locales'))) {
            session(['locale' => $locale]);
            App::setLocale($locale);
        }else {
            session(['locale' => config('app.fallback_locale')]);
            App::setLocale(config('app.fallback_locale'));
        }
        return redirect()->back();
    }
}
