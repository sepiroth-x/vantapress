<?php

namespace Modules\VPEssential1\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Verification extends Model
{
    protected $table = 'vp_verifications';
    
    protected $fillable = [
        'user_id',
        'is_verified',
        'badge_type',
        'verified_by',
        'reason',
        'verified_at',
    ];
    
    protected $casts = [
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get badge emoji
     */
    public function getBadgeEmojiAttribute(): string
    {
        return match($this->badge_type) {
            'blue' => '✓',
            'gold' => '★',
            'gray' => '◆',
            default => '✓',
        };
    }
    
    /**
     * Get badge color class
     */
    public function getBadgeColorAttribute(): string
    {
        return match($this->badge_type) {
            'blue' => 'text-blue-500',
            'gold' => 'text-yellow-500',
            'gray' => 'text-gray-500',
            default => 'text-blue-500',
        };
    }
}
