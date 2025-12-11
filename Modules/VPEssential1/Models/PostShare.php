<?php

namespace Modules\VPEssential1\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class PostShare extends Model
{
    protected $table = 'vp_post_shares';
    
    protected $fillable = [
        'post_id',
        'user_id',
        'comment',
    ];
    
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
