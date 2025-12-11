<?php

namespace Modules\VPEssential1\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Post extends Model
{
    use SoftDeletes;
    
    protected $table = 'vp_posts';
    
    protected $fillable = [
        'user_id',
        'content',
        'type',
        'media',
        'link_url',
        'link_title',
        'link_description',
        'link_image',
        'shared_post_id',
        'visibility',
        'likes_count',
        'comments_count',
        'shares_count',
        'is_pinned',
        'is_published',
    ];
    
    protected $casts = [
        'media' => 'array',
        'is_pinned' => 'boolean',
        'is_published' => 'boolean',
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function sharedPost(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'shared_post_id');
    }
    
    public function shares(): HasMany
    {
        return $this->hasMany(PostShare::class);
    }
    
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
    
    public function reactions(): MorphMany
    {
        return $this->morphMany(Reaction::class, 'reactable');
    }
    
    public function hashtags(): MorphMany
    {
        return $this->morphMany(Hashtaggable::class, 'hashtaggable');
    }
    
    /**
     * Scope to get public posts
     */
    public function scopePublic($query)
    {
        return $query->where('visibility', 'public')->where('is_published', true);
    }
    
    /**
     * Scope to get posts visible to friends
     */
    public function scopeFriendsOnly($query)
    {
        return $query->where('visibility', 'friends')->where('is_published', true);
    }
}
