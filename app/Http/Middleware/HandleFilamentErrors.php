<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Filament\Notifications\Notification;

class HandleFilamentErrors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            return $next($request);
        } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
            Log::error('Unique Constraint Violation', [
                'message' => $e->getMessage(),
                'url' => $request->fullUrl(),
                'user' => auth()->id(),
            ]);
            
            if ($request->wantsJson() || $request->is('admin/*') || $request->is('livewire/*')) {
                Notification::make()
                    ->danger()
                    ->title('Duplicate Entry')
                    ->body('This record already exists. Please use a unique identifier.')
                    ->persistent()
                    ->send();
                
                return back()->withInput();
            }
            
            throw $e;
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database Query Error', [
                'message' => $e->getMessage(),
                'sql' => $e->getSql() ?? 'N/A',
                'url' => $request->fullUrl(),
                'user' => auth()->id(),
            ]);
            
            if ($request->wantsJson() || $request->is('admin/*') || $request->is('livewire/*')) {
                $message = 'A database error occurred.';
                
                // Provide more specific messages for common errors
                if (str_contains($e->getMessage(), "doesn't have a default value")) {
                    preg_match("/Field '(\w+)'/", $e->getMessage(), $matches);
                    $field = $matches[1] ?? 'field';
                    $message = "Required field '{$field}' is missing a value.";
                } elseif (str_contains($e->getMessage(), 'Duplicate entry')) {
                    $message = 'This record already exists in the database.';
                } elseif (str_contains($e->getMessage(), 'foreign key constraint')) {
                    $message = 'Cannot delete this record as it is referenced by other data.';
                }
                
                Notification::make()
                    ->danger()
                    ->title('Database Error')
                    ->body(config('app.debug') ? $e->getMessage() : $message)
                    ->persistent()
                    ->send();
                
                return back()->withInput();
            }
            
            throw $e;
        } catch (\Illuminate\Auth\AuthenticationException $e) {
            Log::warning('Authentication Failed', [
                'url' => $request->fullUrl(),
            ]);
            
            return redirect()->route('login');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Let validation exceptions pass through (handled by Filament)
            throw $e;
        } catch (\Exception $e) {
            Log::error('Unexpected Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'url' => $request->fullUrl(),
                'user' => auth()->id(),
            ]);
            
            if ($request->wantsJson() || $request->is('admin/*') || $request->is('livewire/*')) {
                Notification::make()
                    ->danger()
                    ->title('An Error Occurred')
                    ->body(config('app.debug') ? $e->getMessage() : 'An unexpected error occurred. Please try again.')
                    ->persistent()
                    ->send();
                
                return back()->withInput();
            }
            
            throw $e;
        }
    }
}
