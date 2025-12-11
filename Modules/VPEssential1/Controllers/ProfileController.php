<?php

namespace Modules\VPEssential1\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\VPEssential1\Models\UserProfile;
use Modules\VPEssential1\Models\Verification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display user profile
     */
    public function show($userId = null)
    {
        $user = $userId ? \App\Models\User::findOrFail($userId) : Auth::user();
        $profile = $user->profile ?? $this->createProfile($user);
        
        return view('vpessential1::profile.show', compact('user', 'profile'));
    }
    
    /**
     * Edit profile page
     */
    public function edit()
    {
        $user = Auth::user();
        $profile = $user->profile ?? $this->createProfile($user);
        
        return view('vpessential1::profile.edit', compact('profile'));
    }
    
    /**
     * Update profile
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'display_name' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:500',
            'website' => 'nullable|url',
            'twitter' => 'nullable|string|max:255',
            'github' => 'nullable|string|max:255',
            'linkedin' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|max:2048',
            'cover_image' => 'nullable|image|max:5120',
        ]);
        
        $profile = Auth::user()->profile ?? $this->createProfile(Auth::user());
        
        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            if ($profile->avatar) {
                Storage::delete($profile->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }
        
        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            if ($profile->cover_image) {
                Storage::delete($profile->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }
        
        $profile->update($validated);
        
        return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
    }
    
    /**
     * Create profile if not exists
     */
    protected function createProfile($user)
    {
        return UserProfile::create([
            'user_id' => $user->id,
            'display_name' => $user->name,
        ]);
    }
}
