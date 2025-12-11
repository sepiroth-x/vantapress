<?php

namespace Modules\VPEssential1\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Hashtaggable extends Model
{
    protected $table = 'vp_hashtaggables';
    
    protected $fillable = [
        'hashtag_id',
        'hashtaggable_id',
        'hashtaggable_type',
    ];
    
    public function hashtag(): BelongsTo
    {
        return $this->belongsTo(Hashtag::class);
    }
    
    public function hashtaggable(): MorphTo
    {
        return $this->morphTo();
    }
}
