<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckActiveUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && !auth()->user()->is_active) {
            // Don't logout, just redirect to pending page
            // This allows user to see their email and contact admin
            if (!$request->routeIs('account.pending')) {
                return redirect()->route('account.pending')
                    ->with('email', auth()->user()->email);
            }
        }

        return $next($request);
    }
}

