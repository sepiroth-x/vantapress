<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// User search for mentions (no auth required for now, can add auth later)
Route::get('/users/search', function (Request $request) {
    $query = $request->get('q', '');
    $limit = $request->get('limit', 10);
    
    $users = User::where('name', 'LIKE', "%{$query}%")
        ->orWhere('username', 'LIKE', "%{$query}%")
        ->orWhere('email', 'LIKE', "%{$query}%")
        ->limit($limit)
        ->get()
        ->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username ?? 'user' . $user->id,
                'avatar' => $user->profile && $user->profile->avatar 
                    ? asset('storage/' . $user->profile->avatar)
                    : 'https://ui-avatars.com/api/?name=' . urlencode($user->name),
            ];
        });
    
    return response()->json(['users' => $users]);
});
