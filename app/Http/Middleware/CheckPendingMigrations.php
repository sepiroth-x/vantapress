<?php

namespace App\Http\Middleware;

use App\Services\WebMigrationService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Filament\Notifications\Notification;

/**
 * Check for Pending Migrations Middleware
 * 
 * Automatically checks if there are pending database migrations when admin users
 * access the admin panel. Shows notification banner if migrations are needed.
 * 
 * WordPress-inspired: Similar to "Database Update Required" notices.
 */
class CheckPendingMigrations
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only check for authenticated admin users
        if (!auth()->check() || !$request->is('admin*')) {
            return $next($request);
        }

        // Skip check on login/logout routes to prevent interference with authentication
        if ($request->is('admin/login') || $request->is('admin/logout')) {
            return $next($request);
        }

        // Skip on POST requests (form submissions, including login processing)
        if ($request->isMethod('post')) {
            return $next($request);
        }

        // Skip check on Database Updates page itself to avoid loops
        if ($request->is('admin/database-updates*')) {
            return $next($request);
        }

        // Only check if we're in a normal admin page request (not AJAX/API)
        if ($request->expectsJson() || $request->ajax()) {
            return $next($request);
        }

        // Check for pending migrations
        try {
            $migrationService = new WebMigrationService();
            $status = $migrationService->checkPendingMigrations();

            if ($status['pending'] && $status['count'] > 0 && $status['count'] !== 'Error') {
                // Log for debugging
                \Log::info('Pending migrations detected, sending notification', [
                    'count' => $status['count'],
                    'user' => auth()->id()
                ]);
                
                // Show notification banner
                Notification::make()
                    ->warning()
                    ->title('Database Update Required')
                    ->body(
                        $status['count'] . ' database migration(s) are pending. ' .
                        'Click here to update your database now.'
                    )
                    ->persistent() // Stay visible until dismissed
                    ->actions([
                        \Filament\Notifications\Actions\Action::make('update_now')
                            ->label('Update Database Now')
                            ->url('/admin/database-updates')
                            ->button()
                            ->color('warning'),
                        \Filament\Notifications\Actions\Action::make('dismiss')
                            ->label('Remind Me Later')
                            ->close(),
                    ])
                    ->sendToDatabase(auth()->user());
            }
        } catch (\Exception $e) {
            // Silently fail - don't break admin panel if check fails
            \Log::warning('Failed to check pending migrations', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        return $next($request);
    }
}
