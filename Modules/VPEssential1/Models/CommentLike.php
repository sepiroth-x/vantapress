<?php

namespace Modules\VPEssential1\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class CommentLike extends Model
{
    protected $table = 'vp_comment_likes';
    
    protected $fillable = [
        'comment_id',
        'user_id',
    ];
    
    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class);
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
