<?php

namespace Modules\VPEssential1\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use Carbon\Carbon;

class Story extends Model
{
    protected $table = 'vp_stories';

    protected $fillable = [
        'user_id',
        'type',
        'media_url',
        'content',
        'background_color',
        'duration',
        'viewers',
        'views_count',
        'expires_at',
    ];

    protected $casts = [
        'viewers' => 'array',
        'views_count' => 'integer',
        'duration' => 'integer',
        'expires_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($story) {
            // Stories expire after 24 hours
            $story->expires_at = Carbon::now()->addHours(24);
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function views(): HasMany
    {
        return $this->hasMany(StoryView::class);
    }

    public function scopeActive($query)
    {
        return $query->where('expires_at', '>', Carbon::now());
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function hasViewedBy(int $userId): bool
    {
        return $this->views()->where('user_id', $userId)->exists();
    }

    public function addView(int $userId): void
    {
        if (!$this->hasViewedBy($userId)) {
            StoryView::create([
                'story_id' => $this->id,
                'user_id' => $userId,
                'viewed_at' => now(),
            ]);

            $this->increment('views_count');
        }
    }

    public function isOwnedBy(int $userId): bool
    {
        return $this->user_id === $userId;
    }
}
