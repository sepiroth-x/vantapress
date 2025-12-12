<?php

namespace Modules\VPEssential1\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class UrlPreviewService
{
    /**
     * Extract URL preview metadata
     * 
     * @param string $url
     * @return array|null
     */
    public function fetchMetadata(string $url): ?array
    {
        // Validate URL
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return null;
        }
        
        // Check cache first (24 hours)
        $cacheKey = 'url_preview_' . md5($url);
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        
        try {
            $response = Http::timeout(10)->get($url);
            
            if (!$response->successful()) {
                return null;
            }
            
            $html = $response->body();
            $metadata = $this->parseHtml($html, $url);
            
            // Cache for 24 hours
            if ($metadata) {
                Cache::put($cacheKey, $metadata, now()->addHours(24));
            }
            
            return $metadata;
            
        } catch (\Exception $e) {
            \Log::warning('URL preview fetch failed: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Parse HTML to extract metadata
     * 
     * @param string $html
     * @param string $url
     * @return array|null
     */
    protected function parseHtml(string $html, string $url): ?array
    {
        // Extract Open Graph tags
        $metadata = [
            'url' => $url,
            'title' => null,
            'description' => null,
            'image' => null,
            'site_name' => null,
        ];
        
        // Try Open Graph (og:) meta tags first
        preg_match('/<meta\s+property=["\']og:title["\']\s+content=["\'](.*?)["\']/i', $html, $ogTitle);
        preg_match('/<meta\s+property=["\']og:description["\']\s+content=["\'](.*?)["\']/i', $html, $ogDescription);
        preg_match('/<meta\s+property=["\']og:image["\']\s+content=["\'](.*?)["\']/i', $html, $ogImage);
        preg_match('/<meta\s+property=["\']og:site_name["\']\s+content=["\'](.*?)["\']/i', $html, $ogSiteName);
        
        // Try Twitter Card meta tags as fallback
        preg_match('/<meta\s+name=["\']twitter:title["\']\s+content=["\'](.*?)["\']/i', $html, $twitterTitle);
        preg_match('/<meta\s+name=["\']twitter:description["\']\s+content=["\'](.*?)["\']/i', $html, $twitterDescription);
        preg_match('/<meta\s+name=["\']twitter:image["\']\s+content=["\'](.*?)["\']/i', $html, $twitterImage);
        
        // Regular meta tags as last fallback
        preg_match('/<meta\s+name=["\']description["\']\s+content=["\'](.*?)["\']/i', $html, $metaDescription);
        
        // Extract title from <title> tag if not found in meta
        preg_match('/<title>(.*?)<\/title>/i', $html, $titleTag);
        
        // Populate metadata with priority: OG > Twitter > Standard > Title Tag
        $metadata['title'] = $ogTitle[1] ?? $twitterTitle[1] ?? $titleTag[1] ?? null;
        $metadata['description'] = $ogDescription[1] ?? $twitterDescription[1] ?? $metaDescription[1] ?? null;
        $metadata['image'] = $ogImage[1] ?? $twitterImage[1] ?? null;
        $metadata['site_name'] = $ogSiteName[1] ?? parse_url($url, PHP_URL_HOST);
        
        // Make image URL absolute if relative
        if ($metadata['image'] && !str_starts_with($metadata['image'], 'http')) {
            $parsedUrl = parse_url($url);
            $baseUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'];
            $metadata['image'] = $baseUrl . '/' . ltrim($metadata['image'], '/');
        }
        
        // Return null if no useful metadata found
        if (!$metadata['title'] && !$metadata['description'] && !$metadata['image']) {
            return null;
        }
        
        return $metadata;
    }
    
    /**
     * Extract URLs from text content
     * 
     * @param string $content
     * @return array
     */
    public function extractUrls(string $content): array
    {
        $pattern = '/https?:\/\/[^\s<>"]+/i';
        preg_match_all($pattern, $content, $matches);
        
        return array_unique($matches[0] ?? []);
    }
    
    /**
     * Get preview for first URL in content
     * 
     * @param string $content
     * @return array|null
     */
    public function getFirstUrlPreview(string $content): ?array
    {
        $urls = $this->extractUrls($content);
        
        if (empty($urls)) {
            return null;
        }
        
        return $this->fetchMetadata($urls[0]);
    }
}
