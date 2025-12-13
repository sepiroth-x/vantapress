{{--
/**
 * The Villain Arise - Hero Component
 * 
 * Customizable hero section component.
 * Controlled by VP Essential 1 theme settings.
 * 
 * @package TheVillainArise
 * @version 1.0.0
 * 
 * DEVELOPER NOTES:
 * - Fully customizable via vp_get_hero_config()
 * - Supports background image or gradient
 * - Supports CTA buttons
 * - Dark villain aesthetic with animated effects
 * 
 * PROPS:
 * @prop array $config - Hero configuration from theme settings
 */
--}}
@php
    // Get hero configuration from VP Essential 1
    $heroConfig = function_exists('vp_get_hero_config') 
        ? vp_get_hero_config() 
        : [
            'title' => 'Rise of the Villain',
            'subtitle' => 'Unleash the power of VantaPress CMS',
            'description' => 'A modular, themeable, and powerful content management system built for developers who dare to be different.',
            'cta_primary_text' => 'Get Started',
            'cta_primary_url' => url('/admin'),
            'cta_secondary_text' => 'View Docs',
            'cta_secondary_url' => 'https://github.com/sepiroth-x/vantapress/blob/standard-release/README.md',
            'background_type' => 'gradient',
            'background_image' => '',
            'show_cta' => true
        ];
@endphp

<section class="villain-hero relative overflow-hidden">
    {{-- Background Layer --}}
    @if($heroConfig['background_type'] === 'image' && !empty($heroConfig['background_image']))
        <div class="absolute inset-0 z-0">
            <img src="{{ asset($heroConfig['background_image']) }}" 
                 alt="Hero Background" 
                 class="w-full h-full object-cover opacity-30">
            <div class="absolute inset-0 bg-gradient-to-b from-gray-900/80 to-gray-900"></div>
        </div>
    @else
        {{-- Default Gradient Background --}}
        <div class="absolute inset-0 z-0 bg-gradient-to-br from-gray-900 via-villain-900/20 to-gray-900"></div>
    @endif
    
    {{-- Animated Grid Overlay --}}
    <div class="absolute inset-0 z-0 opacity-10">
        <div class="grid-pattern"></div>
    </div>
    
    {{-- Content Layer --}}
    <div class="relative z-10 container mx-auto px-4 py-32">
        <div class="max-w-4xl mx-auto text-center">
            
            {{-- Title --}}
            <h1 class="text-5xl md:text-7xl font-black font-orbitron mb-6 leading-tight">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-villain-500 to-villain-300 animate-pulse">
                    {{ $heroConfig['title'] }}
                </span>
            </h1>
            
            {{-- Subtitle --}}
            <p class="text-2xl md:text-3xl text-gray-300 mb-6 font-light">
                {{ $heroConfig['subtitle'] }}
            </p>
            
            {{-- Description --}}
            <p class="text-lg text-gray-400 mb-10 max-w-2xl mx-auto leading-relaxed">
                {{ $heroConfig['description'] }}
            </p>
            
            {{-- CTA Buttons --}}
            @if($heroConfig['show_cta'])
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <a href="{{ $heroConfig['cta_primary_url'] }}" 
                       class="group relative px-8 py-4 bg-villain-600 hover:bg-villain-700 text-white font-bold rounded-lg 
                              transition-all duration-300 transform hover:scale-105 hover:shadow-xl hover:shadow-villain-500/50
                              uppercase tracking-wider">
                        <span class="relative z-10">{{ $heroConfig['cta_primary_text'] }}</span>
                        <div class="absolute inset-0 bg-gradient-to-r from-villain-500 to-villain-700 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </a>
                    
                    @if(!empty($heroConfig['cta_secondary_text']))
                        <a href="{{ $heroConfig['cta_secondary_url'] }}" 
                           class="px-8 py-4 border-2 border-villain-600 hover:border-villain-500 text-villain-500 hover:text-villain-400 
                                  font-bold rounded-lg transition-all duration-300 uppercase tracking-wider">
                            {{ $heroConfig['cta_secondary_text'] }}
                        </a>
                    @endif
                </div>
            @endif
            
            {{-- Decorative Elements --}}
            <div class="mt-20 flex justify-center space-x-8 opacity-50">
                <div class="w-20 h-1 bg-gradient-to-r from-transparent via-villain-500 to-transparent"></div>
                <div class="w-2 h-2 bg-villain-500 rounded-full animate-pulse"></div>
                <div class="w-20 h-1 bg-gradient-to-r from-transparent via-villain-500 to-transparent"></div>
            </div>
        </div>
    </div>
    
    {{-- Bottom Wave --}}
    <div class="absolute bottom-0 left-0 right-0 z-20">
        <svg viewBox="0 0 1440 120" class="w-full h-auto text-gray-900">
            <path fill="currentColor" d="M0,64L48,69.3C96,75,192,85,288,80C384,75,480,53,576,48C672,43,768,53,864,58.7C960,64,1056,64,1152,58.7C1248,53,1344,43,1392,37.3L1440,32L1440,120L1392,120C1344,120,1248,120,1152,120C1056,120,960,120,864,120C768,120,672,120,576,120C480,120,384,120,288,120C192,120,96,120,48,120L0,120Z"></path>
        </svg>
    </div>
</section>
