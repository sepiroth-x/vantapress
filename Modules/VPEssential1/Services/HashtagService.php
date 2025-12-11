<?php

namespace Modules\VPEssential1\Services;

use Modules\VPEssential1\Models\Hashtag;
use Modules\VPEssential1\Models\Hashtaggable;
use Illuminate\Support\Str;

class HashtagService
{
    /**
     * Extract hashtags from content
     */
    public function extract(string $content): array
    {
        preg_match_all('/#([a-zA-Z0-9_]+)/', $content, $matches);
        return array_unique($matches[1] ?? []);
    }
    
    /**
     * Extract hashtags and attach them to a model
     */
    public function extractAndAttach($model, string $content): void
    {
        $hashtags = $this->extract($content);
        
        foreach ($hashtags as $tagName) {
            $hashtag = Hashtag::firstOrCreate(
                ['name' => $tagName],
                ['slug' => Str::slug($tagName)]
            );
            
            // Attach to model if not already attached
            $exists = Hashtaggable::where('hashtag_id', $hashtag->id)
                ->where('hashtaggable_id', $model->id)
                ->where('hashtaggable_type', get_class($model))
                ->exists();
            
            if (!$exists) {
                Hashtaggable::create([
                    'hashtag_id' => $hashtag->id,
                    'hashtaggable_id' => $model->id,
                    'hashtaggable_type' => get_class($model),
                ]);
                
                $hashtag->incrementUsage();
            }
        }
    }
    
    /**
     * Remove hashtags from a model
     */
    public function detachAll($model): void
    {
        $hashtaggables = Hashtaggable::where('hashtaggable_id', $model->id)
            ->where('hashtaggable_type', get_class($model))
            ->get();
        
        foreach ($hashtaggables as $hashtaggable) {
            $hashtag = $hashtaggable->hashtag;
            $hashtaggable->delete();
            $hashtag->decrementUsage();
        }
    }
    
    /**
     * Get trending hashtags
     */
    public function getTrending(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return Hashtag::where('is_trending', true)
            ->orWhere('usage_count', '>', 0)
            ->orderBy('usage_count', 'desc')
            ->limit($limit)
            ->get();
    }
    
    /**
     * Search content by hashtag
     */
    public function search(string $hashtag, $modelClass = null)
    {
        $hashtagModel = Hashtag::where('name', $hashtag)
            ->orWhere('slug', Str::slug($hashtag))
            ->first();
        
        if (!$hashtagModel) {
            return collect();
        }
        
        $query = Hashtaggable::where('hashtag_id', $hashtagModel->id);
        
        if ($modelClass) {
            $query->where('hashtaggable_type', $modelClass);
        }
        
        return $query->with('hashtaggable')->get()->pluck('hashtaggable');
    }
}
