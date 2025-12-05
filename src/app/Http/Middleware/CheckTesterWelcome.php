<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTesterWelcome
{
    /**
     * Handle an incoming request.
     * Redirect to tester welcome page if user is a tester and hasn't been welcomed yet.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only check for authenticated users
        if (!auth()->check()) {
            return $next($request);
        }

        $user = auth()->user();

        // Skip if already on the welcome page or marking as welcomed
        if ($request->routeIs('tester.welcome') || $request->routeIs('tester.welcomed')) {
            return $next($request);
        }

        // Redirect to welcome page if tester needs welcome
        if ($user->needsTesterWelcome()) {
            return redirect()->route('tester.welcome');
        }

        return $next($request);
    }
}
