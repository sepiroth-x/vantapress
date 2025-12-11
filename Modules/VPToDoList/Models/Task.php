<?php

namespace Modules\VPToDoList\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Task extends Model
{
    protected $table = 'vp_tasks';

    protected $fillable = [
        'project_id',
        'user_id',
        'title',
        'description',
        'status',
        'priority',
        'due_date',
        'completed_at',
        'order',
        'is_pinned',
        'tags',
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed_at' => 'datetime',
        'is_pinned' => 'boolean',
        'tags' => 'array',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
            ->whereNotIn('status', ['completed']);
    }

    public function scopeDueToday($query)
    {
        return $query->whereDate('due_date', today())
            ->whereNotIn('status', ['completed']);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('due_date', '>', today())
            ->whereNotIn('status', ['completed'])
            ->orderBy('due_date');
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->due_date && 
               $this->due_date->isPast() && 
               $this->status !== 'completed';
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'todo' => 'gray',
            'in_progress' => 'info',
            'review' => 'warning',
            'completed' => 'success',
            'blocked' => 'danger',
            default => 'gray',
        };
    }

    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            'low' => 'gray',
            'medium' => 'info',
            'high' => 'warning',
            'urgent' => 'danger',
            default => 'gray',
        };
    }
}
