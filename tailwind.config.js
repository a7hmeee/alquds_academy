import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Cairo', 'Tajawal', 'IBM Plex Sans Arabic', ...defaultTheme.fontFamily.sans],
                arabic: ['Cairo', 'Tajawal', 'IBM Plex Sans Arabic'],
            },
            colors: {
                'deep-green': {
                    DEFAULT: '#062C24',
                    50: '#E6F2EF',
                    100: '#CCE5DF',
                    200: '#99CCBF',
                    300: '#66B29F',
                    400: '#33997F',
                    500: '#0F766E',
                    600: '#0C5C57',
                    700: '#094340',
                    800: '#062C24',
                    900: '#031612',
                },
                'emerald-premium': {
                    DEFAULT: '#0F766E',
                    50: '#ECFDF5',
                    100: '#D1FAE5',
                    200: '#A7F3D0',
                    300: '#6EE7B7',
                    400: '#34D399',
                    500: '#10B981',
                    600: '#0F766E',
                    700: '#047857',
                    800: '#065F46',
                    900: '#064E3B',
                },
                'soft-mint': {
                    DEFAULT: '#D1FAE5',
                    50: '#F0FDF9',
                    100: '#D1FAE5',
                    200: '#A7F3D0',
                    300: '#6EE7B7',
                },
                'warm-white': {
                    DEFAULT: '#FAFAF7',
                    50: '#FEFEFE',
                    100: '#FAFAF7',
                    200: '#F5F5F2',
                    300: '#F0F0ED',
                },
                'gold-accent': {
                    DEFAULT: '#D4AF37',
                    50: '#FBF8E8',
                    100: '#F7F1D1',
                    200: '#EFE3A3',
                    300: '#E7D575',
                    400: '#DFC747',
                    500: '#D4AF37',
                    600: '#B8952B',
                    700: '#8A6F20',
                    800: '#5C4A15',
                    900: '#2E250B',
                },
            },
            backgroundImage: {
                'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
                'gradient-islamic': 'linear-gradient(135deg, #062C24 0%, #0F766E 50%, #10B981 100%)',
                'gradient-hero': 'linear-gradient(135deg, rgba(6, 44, 36, 0.95) 0%, rgba(15, 118, 110, 0.9) 100%)',
            },
            boxShadow: {
                'glow-sm': '0 0 15px rgba(16, 185, 129, 0.3)',
                'glow-md': '0 0 30px rgba(16, 185, 129, 0.4)',
                'glow-lg': '0 0 45px rgba(16, 185, 129, 0.5)',
                'premium': '0 10px 40px rgba(0, 0, 0, 0.2)',
                'card': '0 4px 20px rgba(0, 0, 0, 0.1)',
            },
            animation: {
                'float': 'float 6s ease-in-out infinite',
                'float-slow': 'float 8s ease-in-out infinite',
                'fade-up': 'fadeUp 0.6s ease-out',
                'fade-in': 'fadeIn 0.4s ease-out',
                'slide-up': 'slideUp 0.5s ease-out',
                'scale-in': 'scaleIn 0.3s ease-out',
                'glow-pulse': 'glowPulse 3s ease-in-out infinite',
            },
            keyframes: {
                float: {
                    '0%, 100%': { transform: 'translateY(0px)' },
                    '50%': { transform: 'translateY(-20px)' },
                },
                fadeUp: {
                    '0%': { opacity: '0', transform: 'translateY(30px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                slideUp: {
                    '0%': { transform: 'translateY(100%)' },
                    '100%': { transform: 'translateY(0)' },
                },
                scaleIn: {
                    '0%': { transform: 'scale(0.9)', opacity: '0' },
                    '100%': { transform: 'scale(1)', opacity: '1' },
                },
                glowPulse: {
                    '0%, 100%': { boxShadow: '0 0 20px rgba(16, 185, 129, 0.3)' },
                    '50%': { boxShadow: '0 0 40px rgba(16, 185, 129, 0.6)' },
                },
            },
        },
    },

    plugins: [forms],
};
