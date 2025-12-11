<?php

namespace Modules\VPEssential1\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    protected $table = 'vp_conversations';
    
    protected $fillable = [
        'name',
        'type',
    ];
    
    public function participants(): HasMany
    {
        return $this->hasMany(ConversationParticipant::class);
    }
    
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
    
    /**
     * Get the last message in the conversation
     */
    public function lastMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }
    
    /**
     * Check if conversation is a group chat
     */
    public function isGroup(): bool
    {
        return $this->type === 'group';
    }
}
