<?php

namespace Modules\VPEssential1\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Friend extends Model
{
    protected $table = 'vp_friends';
    
    protected $fillable = [
        'user_id',
        'friend_id',
        'status',
        'accepted_at',
    ];
    
    protected $casts = [
        'accepted_at' => 'datetime',
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function friend(): BelongsTo
    {
        return $this->belongsTo(User::class, 'friend_id');
    }
    
    /**
     * Check if the friendship is accepted
     */
    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }
    
    /**
     * Check if the friendship is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
    
    /**
     * Check if the friendship is blocked
     */
    public function isBlocked(): bool
    {
        return $this->status === 'blocked';
    }
}
