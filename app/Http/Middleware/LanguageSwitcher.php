<?php

namespace App\Http\Middleware;

use App;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class LanguageSwitcher
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        $lang = $request->session()->get('language', 'en'); // get the language from the session else default en

        // user
        if (Auth::user() != null) {
            App::setLocale(Auth::user()->language); // get user language from database ar,en

            if (Auth::user()->language == 'ar') { // if selected is arabic send to the view the variable rtl with value true
                View::share('rtl', 'true');
            }
        }
        // guest
        elseif (isset($lang)) {
            App::setLocale($lang);

            if ($lang == 'ar') {
                View::share('rtl', 'true');
            }
        }

        return $next($request);
    }
}
