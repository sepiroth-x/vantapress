<?php

namespace Modules\VPEssential1\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\VPEssential1\Models\Group;
use Modules\VPEssential1\Models\Post;

class GroupController extends Controller
{
    public function index()
    {
        $myGroups = Auth::user()->groups()
            ->wherePivot('status', 'approved')
            ->latest()
            ->paginate(12);
            
        $suggestedGroups = Group::where('privacy', 'public')
            ->whereNotIn('id', Auth::user()->groups()->pluck('vp_groups.id'))
            ->orderByDesc('members_count')
            ->take(6)
            ->get();

        return view('vpessential1::groups.index', compact('myGroups', 'suggestedGroups'));
    }

    public function show($slug)
    {
        $group = Group::where('slug', $slug)->firstOrFail();
        
        // Check privacy
        if ($group->privacy === 'private' && !$group->isMember(Auth::id()) && !$group->isAdmin(Auth::id())) {
            abort(403, 'This group is private.');
        }
        
        if ($group->privacy === 'secret' && !$group->isMember(Auth::id())) {
            abort(404);
        }
        
        $posts = $group->posts()
            ->with(['user', 'user.profile', 'comments', 'reactions'])
            ->where('is_approved', true)
            ->paginate(20);

        return view('vpessential1::groups.show', compact('group', 'posts'));
    }

    public function create()
    {
        return view('vpessential1::groups.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'privacy' => 'required|in:public,private,secret',
            'post_permissions' => 'required|in:all_members,admins_only',
            'avatar' => 'nullable|image|max:2048',
            'cover_image' => 'nullable|image|max:4096',
        ]);

        $validated['created_by'] = Auth::id();

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('groups/avatars', 'public');
        }

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('groups/covers', 'public');
        }

        $group = Group::create($validated);

        // Add creator as admin
        $group->members()->attach(Auth::id(), [
            'role' => 'admin',
            'status' => 'approved',
            'joined_at' => now(),
        ]);

        $group->updateMembersCount();

        return redirect()->route('social.groups.show', $group->slug)
            ->with('success', 'Group created successfully!');
    }

    public function join($slug)
    {
        $group = Group::where('slug', $slug)->firstOrFail();

        if ($group->isMember(Auth::id())) {
            return back()->with('info', 'You are already a member of this group.');
        }

        $status = $group->privacy === 'private' ? 'pending' : 'approved';

        $group->members()->attach(Auth::id(), [
            'role' => 'member',
            'status' => $status,
            'joined_at' => $status === 'approved' ? now() : null,
        ]);

        if ($status === 'approved') {
            $group->updateMembersCount();
            return back()->with('success', 'You have joined the group!');
        }

        return back()->with('success', 'Your join request has been sent.');
    }

    public function leave($slug)
    {
        $group = Group::where('slug', $slug)->firstOrFail();

        if (!$group->isMember(Auth::id())) {
            return back()->with('error', 'You are not a member of this group.');
        }

        // Prevent last admin from leaving
        if ($group->isAdmin(Auth::id()) && $group->admins()->count() === 1) {
            return back()->with('error', 'You cannot leave the group as you are the only admin. Please assign another admin first.');
        }

        $group->members()->detach(Auth::id());
        $group->updateMembersCount();

        return redirect()->route('social.groups.index')
            ->with('success', 'You have left the group.');
    }
}
