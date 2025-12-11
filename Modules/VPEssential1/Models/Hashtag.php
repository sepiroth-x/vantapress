<?php

namespace Modules\VPEssential1\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Hashtag extends Model
{
    protected $table = 'vp_hashtags';
    
    protected $fillable = [
        'name',
        'slug',
        'usage_count',
        'description',
        'is_trending',
    ];
    
    protected $casts = [
        'is_trending' => 'boolean',
    ];
    
    public function hashtaggables(): HasMany
    {
        return $this->hasMany(Hashtaggable::class);
    }
    
    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($hashtag) {
            if (empty($hashtag->slug)) {
                $hashtag->slug = Str::slug($hashtag->name);
            }
        });
    }
    
    /**
     * Increment usage count
     */
    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }
    
    /**
     * Decrement usage count
     */
    public function decrementUsage(): void
    {
        $this->decrement('usage_count');
    }
}
