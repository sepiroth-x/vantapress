import preset from './vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./vendor/filament/**/*.blade.php",
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
    theme: {
        extend: {
            colors: {
                // VantaPress Villain Brand Colors
                'vanta-black': '#050505',
                'deep-obsidian': '#0A0A0A',
                'crimson-villain': '#D40026',
                'dark-violet': '#6A0F91',
                'steel-gray': '#A1A1A5',
                'ghost-gray': '#2A2A2E',
                'panel-dark-gray': '#121212',
                'input-dark': '#1A1A1D',
                'success-green': '#32D27C',
                'warning-gold': '#EFB336',
                'error-red': '#FF4A4A',
                'info-blue': '#3E84F8',
                'villain-pulse-red': '#FF0033',
                'shadow-violet': '#8C1FB8',
                
                // Primary Scale - Crimson Villain
                primary: {
                    50: '#ffe5ea',
                    100: '#ffccd6',
                    200: '#ff99ad',
                    300: '#ff6685',
                    400: '#ff335c',
                    500: '#D40026',  // Crimson Villain
                    600: '#aa001e',
                    700: '#800017',
                    800: '#55000f',
                    900: '#2b0008',
                    950: '#150004',
                },
                
                // Gray Scale - Vanta to Steel
                gray: {
                    50: '#fafafa',
                    100: '#e6e6e6',
                    200: '#a1a1a5',  // Steel Gray
                    300: '#646464',
                    400: '#464646',
                    500: '#2a2a2e',  // Ghost Gray
                    600: '#1a1a1d',  // Input Dark
                    700: '#121212',  // Panel Dark
                    800: '#0a0a0a',  // Deep Obsidian
                    900: '#050505',  // Vanta Black
                    950: '#050505',
                },
                
                // Success Scale - Neon Green
                success: {
                    50: '#e6fff0',
                    100: '#ccffe0',
                    200: '#99ffc2',
                    300: '#66e6a3',
                    400: '#4cdc8f',
                    500: '#32d27c',  // Success Green
                    600: '#28a863',
                    700: '#1e7e4a',
                    800: '#145432',
                    900: '#0a2a19',
                    950: '#05150c',
                },
                
                // Danger Scale - Error Red
                danger: {
                    50: '#fff0f0',
                    100: '#ffdcdc',
                    200: '#ffb4b4',
                    300: '#ff8c8c',
                    400: '#ff6464',
                    500: '#ff4a4a',  // Error Red
                    600: '#e63232',
                    700: '#c81e1e',
                    800: '#aa1414',
                    900: '#8c0a0a',
                    950: '#640505',
                },
                
                // Warning Scale - Gold
                warning: {
                    50: '#fffae6',
                    100: '#fff5c8',
                    200: '#ffeb96',
                    300: '#ffdc64',
                    400: '#fac84b',
                    500: '#efb336',  // Warning Gold
                    600: '#c89628',
                    700: '#a0781e',
                    800: '#785a14',
                    900: '#503c0a',
                    950: '#281e05',
                },
                
                // Info Scale - Blue
                info: {
                    50: '#e6f0ff',
                    100: '#c8dcff',
                    200: '#96beff',
                    300: '#64a0ff',
                    400: '#5091fc',
                    500: '#3e84f8',  // Info Blue
                    600: '#3269c8',
                    700: '#285096',
                    800: '#1e3c64',
                    900: '#141e32',
                    950: '#0a1419',
                },
            },
        },
    },
    plugins: [],
}
