<?php

namespace Modules\VPEssential1\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\User;

class Group extends Model
{
    use SoftDeletes;

    protected $table = 'vp_groups';

    protected $fillable = [
        'created_by',
        'name',
        'slug',
        'description',
        'cover_image',
        'avatar',
        'privacy',
        'post_permissions',
        'is_verified',
        'members_count',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'members_count' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($group) {
            if (empty($group->slug)) {
                $group->slug = Str::slug($group->name);
            }
        });
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'vp_group_members', 'group_id', 'user_id')
            ->withPivot('role', 'status', 'joined_at')
            ->withTimestamps();
    }

    public function approvedMembers(): BelongsToMany
    {
        return $this->members()->wherePivot('status', 'approved');
    }

    public function admins(): BelongsToMany
    {
        return $this->members()->wherePivot('role', 'admin')->wherePivot('status', 'approved');
    }

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'vp_group_posts', 'group_id', 'post_id')
            ->withPivot('is_pinned', 'is_approved')
            ->withTimestamps()
            ->orderByDesc('vp_group_posts.is_pinned')
            ->orderByDesc('vp_group_posts.created_at');
    }

    public function isMember(int $userId): bool
    {
        return $this->members()
            ->wherePivot('user_id', $userId)
            ->wherePivot('status', 'approved')
            ->exists();
    }

    public function isAdmin(int $userId): bool
    {
        return $this->members()
            ->wherePivot('user_id', $userId)
            ->wherePivot('role', 'admin')
            ->wherePivot('status', 'approved')
            ->exists();
    }

    public function canPost(int $userId): bool
    {
        if ($this->post_permissions === 'admins_only') {
            return $this->isAdmin($userId);
        }
        
        return $this->isMember($userId);
    }

    public function updateMembersCount(): void
    {
        $this->members_count = $this->members()->wherePivot('status', 'approved')->count();
        $this->save();
    }
}
