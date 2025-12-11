<?php

namespace Modules\VPToDoList\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;

class Project extends Model
{
    protected $table = 'vp_projects';

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'color',
        'status',
        'start_date',
        'due_date',
        'order',
    ];

    protected $casts = [
        'start_date' => 'date',
        'due_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class)->orderBy('order');
    }

    public function completedTasks(): HasMany
    {
        return $this->hasMany(Task::class)->where('status', 'completed');
    }

    public function getProgressAttribute(): float
    {
        $totalTasks = $this->tasks()->count();
        if ($totalTasks === 0) {
            return 0;
        }
        
        $completedTasks = $this->completedTasks()->count();
        return round(($completedTasks / $totalTasks) * 100, 2);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
