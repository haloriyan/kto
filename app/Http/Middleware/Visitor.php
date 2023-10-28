<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class Visitor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $myData = Auth::guard('visitor')->user();
        if ($myData == "") {
            $r = URL::full();
            return redirect()->route('visitor.loginPage', [
                'r' => base64_encode($r)
            ])->withErrors([
                'Please register first before continue'
            ]);
        }
        return $next($request);
    }
}
