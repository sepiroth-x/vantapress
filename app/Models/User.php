<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'student_id',
        'teacher_id',
        'department_id',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get activity log options
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'is_active'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Check if user is a student
     */
    public function isStudent(): bool
    {
        return $this->hasRole('student');
    }

    /**
     * Check if user is a teacher
     */
    public function isTeacher(): bool
    {
        return $this->hasRole('teacher');
    }

    /**
     * Check if user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->hasRole(['admin', 'super-admin']);
    }

    /**
     * Check if user is active
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    // ========================================
    // VPEssential1 Social Networking Relationships
    // ========================================
    
    /**
     * User profile relationship
     */
    public function profile()
    {
        return $this->hasOne(\Modules\VPEssential1\Models\UserProfile::class);
    }
    
    /**
     * User's posts
     */
    public function posts()
    {
        return $this->hasMany(\Modules\VPEssential1\Models\Post::class);
    }
    
    /**
     * User's tweets
     */
    public function tweets()
    {
        return $this->hasMany(\Modules\VPEssential1\Models\Tweet::class);
    }
    
    /**
     * Friend requests sent by user
     */
    public function friendRequestsSent()
    {
        return $this->hasMany(\Modules\VPEssential1\Models\Friend::class, 'user_id');
    }
    
    /**
     * Friend requests received by user
     */
    public function friendRequestsReceived()
    {
        return $this->hasMany(\Modules\VPEssential1\Models\Friend::class, 'friend_id');
    }
    
    /**
     * Get all accepted friends
     */
    public function friends()
    {
        return $this->friendRequestsSent()
            ->where('status', 'accepted')
            ->with('friend');
    }
    
    /**
     * Followers (users following this user)
     */
    public function followers()
    {
        return $this->hasMany(\Modules\VPEssential1\Models\Follower::class, 'user_id');
    }
    
    /**
     * Following (users this user is following)
     */
    public function following()
    {
        return $this->hasMany(\Modules\VPEssential1\Models\Follower::class, 'follower_id');
    }
    
    /**
     * Pokes received
     */
    public function pokesReceived()
    {
        return $this->hasMany(\Modules\VPEssential1\Models\Poke::class, 'to_user_id');
    }
    
    /**
     * Pokes sent
     */
    public function pokesSent()
    {
        return $this->hasMany(\Modules\VPEssential1\Models\Poke::class, 'from_user_id');
    }
    
    /**
     * User's comments
     */
    public function comments()
    {
        return $this->hasMany(\Modules\VPEssential1\Models\Comment::class);
    }
    
    /**
     * User's reactions
     */
    public function reactions()
    {
        return $this->hasMany(\Modules\VPEssential1\Models\Reaction::class);
    }
    
    /**
     * User's conversations
     */
    public function conversations()
    {
        return $this->hasManyThrough(
            \Modules\VPEssential1\Models\Conversation::class,
            \Modules\VPEssential1\Models\ConversationParticipant::class,
            'user_id',
            'id',
            'id',
            'conversation_id'
        );
    }
    
    /**
     * User's messages
     */
    public function messages()
    {
        return $this->hasMany(\Modules\VPEssential1\Models\Message::class);
    }
    
    /**
     * User's notifications
     */
    public function vpNotifications()
    {
        return $this->hasMany(\Modules\VPEssential1\Models\Notification::class);
    }
    
    /**
     * Verification status
     */
    public function verification()
    {
        return $this->hasOne(\Modules\VPEssential1\Models\Verification::class);
    }
    
    /**
     * Check if user is verified
     */
    public function isVerified(): bool
    {
        return $this->verification()->where('is_verified', true)->exists();
    }
    
    /**
     * Check if users are friends
     */
    public function isFriendsWith($userId): bool
    {
        return $this->friends()->where('friend_id', $userId)->exists() ||
               $this->friendRequestsReceived()->where('user_id', $userId)->where('status', 'accepted')->exists();
    }
    
    /**
     * Check if user is following another user
     */
    public function isFollowing($userId): bool
    {
        return $this->following()->where('user_id', $userId)->exists();
    }
}
