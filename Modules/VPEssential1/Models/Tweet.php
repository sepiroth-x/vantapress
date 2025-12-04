<?php

namespace Modules\VPEssential1\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Tweet extends Model
{
    use SoftDeletes;
    
    protected $table = 'vp_tweets';
    
    protected $fillable = [
        'user_id',
        'content',
        'likes_count',
        'retweets_count',
        'replies_count',
        'reply_to_id',
        'retweet_of_id',
        'is_pinned',
        'is_published',
    ];
    
    protected $casts = [
        'is_pinned' => 'boolean',
        'is_published' => 'boolean',
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function replyTo(): BelongsTo
    {
        return $this->belongsTo(Tweet::class, 'reply_to_id');
    }
    
    public function retweetOf(): BelongsTo
    {
        return $this->belongsTo(Tweet::class, 'retweet_of_id');
    }
    
    public function replies(): HasMany
    {
        return $this->hasMany(Tweet::class, 'reply_to_id');
    }
    
    public function likes(): HasMany
    {
        return $this->hasMany(TweetLike::class);
    }
}
