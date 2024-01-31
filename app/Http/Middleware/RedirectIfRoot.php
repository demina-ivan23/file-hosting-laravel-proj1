<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfRoot
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        // Check if the request is for the root ("/") page
        if ($request->is('/')) {
            // Redirect to the desired page (e.g., "/dashboard")
            return redirect('/global-files/public');
        }

        // Continue with the request if not accessing the root page
        return $next($request);
    }

}
