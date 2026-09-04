import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    darkMode: 'class',

    theme: {
        extend: {
            fontFamily: {
                sans: ['Geist', 'Inter', ...defaultTheme.fontFamily.sans],
                body: ['Inter', ...defaultTheme.fontFamily.sans],
                mono: ['Fira Code', 'IBM Plex Mono', ...defaultTheme.fontFamily.mono],
            },

            colors: {
                primary: {
                    DEFAULT: 'rgb(var(--c-primary) / <alpha-value>)',
                    hover: 'rgb(var(--c-primary-hover) / <alpha-value>)',
                    bright: 'rgb(var(--c-primary-bright) / <alpha-value>)',
                },
                accent: {
                    DEFAULT: 'rgb(var(--c-accent) / <alpha-value>)',
                    light: 'rgb(var(--c-accent-light) / <alpha-value>)',
                },
                cream: 'rgb(var(--c-bg) / <alpha-value>)',
                surface: {
                    DEFAULT: 'rgb(var(--c-surface) / <alpha-value>)',
                    soft: 'rgb(var(--c-surface-2) / <alpha-value>)',
                    hover: 'rgb(var(--c-surface-hover) / <alpha-value>)',
                },
                line: {
                    DEFAULT: 'rgb(var(--c-border) / <alpha-value>)',
                    soft: 'rgb(var(--c-border-soft) / <alpha-value>)',
                },
                ink: {
                    DEFAULT: 'rgb(var(--c-text) / <alpha-value>)',
                    secondary: 'rgb(var(--c-text-secondary) / <alpha-value>)',
                    muted: 'rgb(var(--c-text-muted) / <alpha-value>)',
                },
                success: { DEFAULT: '#10B981', light: '#6EE7B7' },
                warning: { DEFAULT: '#F59E0B' },
                danger: { DEFAULT: '#EF4444' },
                info: { DEFAULT: '#3B82F6' },
            },

            borderRadius: {
                sm: '4px',
                md: '8px',
                lg: '12px',
                xl: '16px',
            },

            maxWidth: {
                site: '1200px',
            },

            boxShadow: {
                xs: '0 1px 2px 0 rgba(26, 20, 16, 0.05)',
                sm: '0 2px 4px 0 rgba(26, 20, 16, 0.08)',
                md: '0 4px 8px 0 rgba(26, 20, 16, 0.1)',
                card: '0 8px 16px 0 rgba(26, 20, 16, 0.08), 0 0 1px 0 rgba(26, 20, 16, 0.04)',
                lg: '0 12px 24px 0 rgba(26, 20, 16, 0.12)',
                elevated: '0 20px 40px 0 rgba(26, 20, 16, 0.15), 0 0 1px 0 rgba(26, 20, 16, 0.04)',
                xl: '0 32px 64px 0 rgba(26, 20, 16, 0.2)',
                glow: '0 0 20px rgba(230, 126, 34, 0.3)',
                'focus-ring': '0 0 0 2px rgb(var(--c-bg)), 0 0 0 4px rgba(124, 90, 47, 0.2)',
            },

            transitionDuration: {
                100: '100ms',
                250: '250ms',
                350: '350ms',
            },

            transitionTimingFunction: {
                standard: 'cubic-bezier(0.4, 0, 0.2, 1)',
                entrance: 'cubic-bezier(0.34, 1.56, 0.64, 1)',
                exit: 'cubic-bezier(0.34, 0.56, 0.64, 0)',
                smooth: 'cubic-bezier(0.25, 0.46, 0.45, 0.94)',
                bounce: 'cubic-bezier(0.175, 0.885, 0.32, 1.275)',
            },

            keyframes: {
                fadeIn: {
                    from: { opacity: 0 },
                    to: { opacity: 1 },
                },
                slideUp: {
                    from: { opacity: 0, transform: 'translateY(16px)' },
                    to: { opacity: 1, transform: 'translateY(0)' },
                },
                scaleIn: {
                    from: { opacity: 0, transform: 'scale(0.95)' },
                    to: { opacity: 1, transform: 'scale(1)' },
                },
                pulseSoft: {
                    '0%, 100%': { transform: 'scale(1)', opacity: 1 },
                    '50%': { transform: 'scale(1.1)', opacity: 0.7 },
                },
                shimmer: {
                    from: { backgroundPosition: '-200% 0' },
                    to: { backgroundPosition: '200% 0' },
                },
                gradientFlow: {
                    '0%, 100%': { backgroundPosition: '0% 50%' },
                    '50%': { backgroundPosition: '100% 50%' },
                },
                float: {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-8px)' },
                },
            },

            animation: {
                fade: 'fadeIn 250ms cubic-bezier(0.4, 0, 0.2, 1)',
                'fade-in': 'fadeIn 300ms cubic-bezier(0.34, 1.56, 0.64, 1)',
                'slide-up': 'slideUp 300ms cubic-bezier(0.34, 1.56, 0.64, 1)',
                'slide-down': 'slideDown 300ms cubic-bezier(0.34, 0.56, 0.64, 0)',
                'scale-in': 'scaleIn 250ms cubic-bezier(0.34, 1.56, 0.64, 1)',
                pulse: 'pulseSoft 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                shimmer: 'shimmer 1.5s linear infinite',
                'gradient-flow': 'gradientFlow 6s ease infinite',
                float: 'float 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
            },

            backgroundImage: {
                'primary-gradient': 'linear-gradient(135deg, #7C5A2F 0%, #B8740F 100%)',
                'accent-gradient': 'linear-gradient(120deg, #E67E22 0%, #F5A76B 100%)',
            },
        },
    },

    plugins: [forms],
};
