<?php
namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public const LOCALES = ['sw', 'en'];

    public function handle(Request $request, Closure $next)
    {
        $locale = $request->session()->get('locale', 'sw');
        if (! in_array($locale, self::LOCALES, true)) {
            $locale = 'sw';
        }
        app()->setLocale($locale);
        Carbon::setLocale($locale);
        return $next($request);
    }
}
