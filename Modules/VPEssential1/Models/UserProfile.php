<?php

namespace Modules\VPEssential1\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class UserProfile extends Model
{
    protected $table = 'vp_user_profiles';
    
    protected $fillable = [
        'user_id',
        'display_name',
        'bio',
        'avatar',
        'cover_image',
        'website',
        'twitter',
        'github',
        'linkedin',
        'location',
        'social_links',
        'settings',
    ];
    
    protected $casts = [
        'social_links' => 'array',
        'settings' => 'array',
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
