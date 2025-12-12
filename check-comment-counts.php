<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Modules\VPEssential1\Models\Post;

echo "Checking comment counts...\n\n";

$posts = Post::with('comments')->get();

foreach ($posts as $post) {
    $actualCount = $post->comments->count();
    $storedCount = $post->comments_count;
    
    echo "Post ID: {$post->id}\n";
    echo "  Stored comments_count: {$storedCount}\n";
    echo "  Actual comments: {$actualCount}\n";
    
    if ($storedCount !== $actualCount) {
        echo "  ⚠️ MISMATCH! Updating...\n";
        $post->update(['comments_count' => $actualCount]);
    } else {
        echo "  ✓ OK\n";
    }
    echo "\n";
}

echo "Done!\n";
