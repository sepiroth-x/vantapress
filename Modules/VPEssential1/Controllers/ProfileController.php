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
            'avatar' => 'nullable|file|max:2048',
            'cover_image' => 'nullable|file|max:5120',
        ]);
        
        $profile = Auth::user()->profile ?? $this->createProfile(Auth::user());
        
        // Handle avatar upload with manual extension check
        if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            $avatar = $request->file('avatar');
            $extension = strtolower($avatar->getClientOriginalExtension());
            
            // Validate allowed extensions manually
            if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                return back()->withErrors(['avatar' => 'Avatar must be a jpg, jpeg, png, gif, or webp file.']);
            }
            
            if ($profile->avatar) {
                Storage::delete($profile->avatar);
            }
            
            // Generate filename manually to avoid MIME type detection
            $filename = uniqid() . '_' . time() . '.' . $extension;
            $validated['avatar'] = $avatar->storeAs('avatars', $filename, 'public');
        } else {
            unset($validated['avatar']);
        }
        
        // Handle cover image upload with manual extension check
        if ($request->hasFile('cover_image') && $request->file('cover_image')->isValid()) {
            $coverImage = $request->file('cover_image');
            $extension = strtolower($coverImage->getClientOriginalExtension());
            
            // Validate allowed extensions manually
            if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                return back()->withErrors(['cover_image' => 'Cover image must be a jpg, jpeg, png, gif, or webp file.']);
            }
            
            if ($profile->cover_image) {
                Storage::delete($profile->cover_image);
            }
            
            // Generate filename manually to avoid MIME type detection
            $filename = uniqid() . '_' . time() . '.' . $extension;
            $validated['cover_image'] = $coverImage->storeAs('covers', $filename, 'public');
        } else {
            unset($validated['cover_image']);
        }
        
        $profile->update($validated);
        
        return redirect()->route('social.profile.show')->with('success', 'Profile updated successfully!');
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
