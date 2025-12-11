<?php

namespace Modules\VPEssential1\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Comment extends Model
{
    use SoftDeletes;
    
    protected $table = 'vp_comments';
    
    protected $fillable = [
        'user_id',
        'commentable_id',
        'commentable_type',
        'parent_id',
        'content',
        'likes_count',
        'replies_count',
        'is_published',
    ];
    
    protected $casts = [
        'is_published' => 'boolean',
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }
    
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }
    
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
    
    public function likes(): HasMany
    {
        return $this->hasMany(CommentLike::class);
    }
    
    public function reactions(): MorphMany
    {
        return $this->morphMany(Reaction::class, 'reactable');
    }
    
    /**
     * Check if this is a reply
     */
    public function isReply(): bool
    {
        return $this->parent_id !== null;
    }
}
