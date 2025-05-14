<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use App\Models\Setting;

class SetLanguage
{
    /**
     * use Illuminate\Support\Facades\Session;
     */
    public function handle(Request $request, Closure $next): Response
    {
        $localeLanguage = Session::get('languageName');
        $lan = Setting::where('key','language')->get()->toArray()[0];

        if (! isset($localeLanguage)) {

            if (isset($localeLanguage)) {
                \App::setLocale($localeLanguage);
            }

            \App::setLocale($lan['value']);
            Session::put('languageName', $lan['value']);

        } else {
            \App::setLocale($localeLanguage);
        }

        return $next($request);
    }
}
