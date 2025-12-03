<?php
/**
 * TCC School CMS - Module Middleware
 * 
 * Middleware to check if a required module is enabled before allowing access.
 * Protects module-specific routes from access when module is disabled.
 * 
 * @package TCC_School_CMS
 * @subpackage Http\Middleware
 * @author Sepiroth X Villainous (Richard Cebel Cupal, LPT)
 * @version 1.0.0
 * @license Commercial / Paid
 * 
 * Copyright (c) 2025 Sepiroth X Villainous (Richard Cebel Cupal, LPT)
 * All Rights Reserved.
 * 
 * Contact Information:
 * Email: chardy.tsadiq02@gmail.com
 * Mobile: +63 915 0388 448
 * 
 * This software is proprietary and confidential. Unauthorized copying,
 * modification, distribution, or use of this software, via any medium,
 * is strictly prohibited without explicit written permission from the author.
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\CMS\ModuleManager;

class ModuleMiddleware
{
    protected ModuleManager $moduleManager;

    public function __construct(ModuleManager $moduleManager)
    {
        $this->moduleManager = $moduleManager;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $moduleName
     */
    public function handle(Request $request, Closure $next, string $moduleName): Response
    {
        // Check if module is enabled
        if (!$this->moduleManager->isEnabled($moduleName)) {
            abort(404, "Module '{$moduleName}' is not available.");
        }

        return $next($request);
    }
}
