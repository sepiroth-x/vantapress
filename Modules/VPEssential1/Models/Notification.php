<?php

namespace Modules\VPEssential1\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Models\User;

class Notification extends Model
{
    protected $table = 'vp_notifications';
    
    protected $fillable = [
        'user_id',
        'from_user_id',
        'type',
        'notifiable_id',
        'notifiable_type',
        'title',
        'message',
        'link',
        'is_read',
        'read_at',
    ];
    
    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }
    
    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }
    
    /**
     * Mark notification as read
     */
    public function markAsRead(): void
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }
    
    /**
     * Scope to get unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
}
