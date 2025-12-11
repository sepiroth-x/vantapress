<?php

namespace Modules\VPEssential1\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class ConversationParticipant extends Model
{
    protected $table = 'vp_conversation_participants';
    
    protected $fillable = [
        'conversation_id',
        'user_id',
        'last_read_at',
        'is_muted',
    ];
    
    protected $casts = [
        'last_read_at' => 'datetime',
        'is_muted' => 'boolean',
    ];
    
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get unread messages count
     */
    public function unreadCount(): int
    {
        return $this->conversation->messages()
            ->where('user_id', '!=', $this->user_id)
            ->where('created_at', '>', $this->last_read_at ?? now()->subYears(10))
            ->count();
    }
}
