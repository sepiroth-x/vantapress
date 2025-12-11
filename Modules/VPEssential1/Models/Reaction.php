<?php

namespace Modules\VPEssential1\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Models\User;

class Reaction extends Model
{
    protected $table = 'vp_reactions';
    
    protected $fillable = [
        'user_id',
        'reactable_id',
        'reactable_type',
        'type',
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function reactable(): MorphTo
    {
        return $this->morphTo();
    }
    
    /**
     * Get emoji for reaction type
     */
    public function getEmojiAttribute(): string
    {
        return match($this->type) {
            'like' => '👍',
            'love' => '❤️',
            'haha' => '😂',
            'wow' => '😮',
            'sad' => '😢',
            'angry' => '😠',
            default => '👍',
        };
    }
}
