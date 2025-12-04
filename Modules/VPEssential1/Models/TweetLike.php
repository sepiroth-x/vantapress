<?php

namespace Modules\VPEssential1\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class TweetLike extends Model
{
    protected $table = 'vp_tweet_likes';
    
    protected $fillable = [
        'tweet_id',
        'user_id',
    ];
    
    public function tweet(): BelongsTo
    {
        return $this->belongsTo(Tweet::class);
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
