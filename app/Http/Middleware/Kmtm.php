<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class Kmtm
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $myData = Auth::guard('buyer')->user();
        if ($myData == "") {
            $r = URL::full();
            return redirect()->route('kmtm.loginPage', [
                'r' => base64_encode($r)
            ])->withErrors([
                'You have to logged in first before access this page'
            ]);
        }
        return $next($request);
    }
}
