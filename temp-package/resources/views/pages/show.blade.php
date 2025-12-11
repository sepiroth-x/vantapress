<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page->meta_title ?? $page->title }} - {{ config('app.name') }}</title>
    
    @if($page->meta_description)
        <meta name="description" content="{{ $page->meta_description }}">
    @endif
    
    @if($page->meta_keywords)
        <meta name="keywords" content="{{ $page->meta_keywords }}">
    @endif
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f5f5f5;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .page-header {
            background: white;
            padding: 40px;
            margin-bottom: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: #1a202c;
        }
        
        .page-meta {
            color: #718096;
            font-size: 0.9rem;
            margin-bottom: 20px;
        }
        
        .page-excerpt {
            font-size: 1.1rem;
            color: #4a5568;
            font-style: italic;
        }
        
        .page-featured-image {
            width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        
        .page-content {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .page-content h1,
        .page-content h2,
        .page-content h3,
        .page-content h4,
        .page-content h5,
        .page-content h6 {
            margin-top: 1.5em;
            margin-bottom: 0.5em;
            font-weight: 600;
            line-height: 1.3;
        }
        
        .page-content h1 { font-size: 2rem; }
        .page-content h2 { font-size: 1.75rem; }
        .page-content h3 { font-size: 1.5rem; }
        .page-content h4 { font-size: 1.25rem; }
        
        .page-content p {
            margin-bottom: 1em;
        }
        
        .page-content ul,
        .page-content ol {
            margin-bottom: 1em;
            padding-left: 2em;
        }
        
        .page-content li {
            margin-bottom: 0.5em;
        }
        
        .page-content a {
            color: #3182ce;
            text-decoration: none;
        }
        
        .page-content a:hover {
            text-decoration: underline;
        }
        
        .page-content img {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
            margin: 1em 0;
        }
        
        .page-content blockquote {
            border-left: 4px solid #e2e8f0;
            padding-left: 1em;
            margin: 1em 0;
            color: #718096;
            font-style: italic;
        }
        
        .page-content code {
            background: #f7fafc;
            padding: 0.2em 0.4em;
            border-radius: 3px;
            font-family: "Courier New", monospace;
            font-size: 0.9em;
        }
        
        .page-content pre {
            background: #2d3748;
            color: #e2e8f0;
            padding: 1em;
            border-radius: 4px;
            overflow-x: auto;
            margin: 1em 0;
        }
        
        .page-content pre code {
            background: transparent;
            padding: 0;
            color: inherit;
        }
        
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #3182ce;
            text-decoration: none;
            font-weight: 500;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="/" class="back-link">← Back to Home</a>
        
        <div class="page-header">
            <h1 class="page-title">{{ $page->title }}</h1>
            
            <div class="page-meta">
                @if($page->author)
                    By {{ $page->author->name }} • 
                @endif
                Published {{ $page->published_at?->format('F j, Y') ?? $page->created_at->format('F j, Y') }}
            </div>
            
            @if($page->excerpt)
                <p class="page-excerpt">{{ $page->excerpt }}</p>
            @endif
        </div>
        
        @if($page->featuredImage)
            <img src="{{ Storage::url($page->featuredImage->file_path) }}" 
                 alt="{{ $page->featuredImage->alt_text ?? $page->title }}" 
                 class="page-featured-image">
        @endif
        
        <div class="page-content">
            {!! $page->content !!}
        </div>
    </div>
</body>
</html>
