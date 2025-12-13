<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Theme;

class VPSocialThemeRequired
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if VPSocial theme is active
        $activeTheme = Theme::where('is_active', true)->first();
        
        if (!$activeTheme || $activeTheme->slug !== 'VPSocial') {
            // VPSocial theme is not active, redirect to homepage
            return redirect('/')->with('error', 'This feature requires VPSocial theme to be active.');
        }
        
        return $next($request);
    }
}
