<?php

namespace Modules\VPEssential1\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\VPEssential1\Models\Story;
use Carbon\Carbon;

class StoryController extends Controller
{
    public function index()
    {
        // Get active stories from friends and self
        $friendIds = Auth::user()->friends()
            ->where('status', 'accepted')
            ->pluck('friend_id')
            ->merge(Auth::user()->friendRequestsReceived()->where('status', 'accepted')->pluck('user_id'))
            ->push(Auth::id())
            ->unique();

        $stories = Story::with(['user', 'user.profile'])
            ->whereIn('user_id', $friendIds)
            ->active()
            ->orderByDesc('created_at')
            ->get()
            ->groupBy('user_id'); // Group by user so we see one avatar per user

        return view('vpessential1::stories.index', compact('stories'));
    }

    public function create()
    {
        return view('vpessential1::stories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:image,video,text',
            'media' => 'required_if:type,image,video|file|max:10240', // 10MB max
            'content' => 'required_if:type,text|string|max:500',
            'background_color' => 'nullable|string',
            'duration' => 'nullable|integer|min:3|max:15',
        ]);

        $data = [
            'user_id' => Auth::id(),
            'type' => $validated['type'],
            'duration' => $validated['duration'] ?? 5,
        ];

        if (in_array($validated['type'], ['image', 'video'])) {
            $data['media_url'] = $request->file('media')->store('stories', 'public');
        } else {
            $data['content'] = $validated['content'];
            $data['background_color'] = $validated['background_color'] ?? '#1877f2';
        }

        Story::create($data);

        return redirect()->route('social.newsfeed')->with('success', 'Story posted successfully!');
    }

    public function show($id)
    {
        $story = Story::with(['user', 'user.profile'])->findOrFail($id);

        if ($story->isExpired()) {
            abort(404, 'This story is no longer available.');
        }

        // Mark as viewed by current user
        if (Auth::id() !== $story->user_id) {
            $story->addView(Auth::id());
        }

        // Get all stories from this user
        $userStories = Story::where('user_id', $story->user_id)
            ->active()
            ->orderBy('created_at')
            ->get();

        return view('vpessential1::stories.show', compact('story', 'userStories'));
    }

    public function destroy($id)
    {
        $story = Story::findOrFail($id);

        if (!$story->isOwnedBy(Auth::id())) {
            abort(403);
        }

        if ($story->media_url) {
            \Storage::disk('public')->delete($story->media_url);
        }

        $story->delete();

        return redirect()->route('social.newsfeed')->with('success', 'Story deleted successfully.');
    }
}
