import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                futura: ['Futura', ...defaultTheme.fontFamily.sans],
            },
            gridTemplateColumns: {
                'frame-auto': '1fr auto',
                'auto-frame': 'auto 1fr',
                'auto2frame': 'auto auto 1fr',
                'auto-frame-auto': 'auto 1fr auto',
                'auto-fill-10': 'repeat(auto-fill, minmax(10rem, 1fr))',
                'auto-fill-20': 'repeat(auto-fill, minmax(20rem, 1fr))',
                'auto-fill-25': 'repeat(auto-fill, minmax(25rem, 1fr))',
            },
            gridTemplateRows: {
                'frame-auto': '1fr auto',
                'auto-frame': 'auto 1fr',
                'auto-auto': 'auto auto',
                'auto-frame-auto': 'auto 1fr auto',
            },
            animation: {
                'scale-up-center': 'scaleUpCenter 0.4s cubic-bezier(0.390, 0.575, 0.565, 1.000) both',
                'move-search-bar': 'moveUpAdjustWidth 2s ease-in-out'
            },
            keyframes: {
                scaleUpCenter: {
                    '0%': {
                        'background-color': '#ffffff'
                    },
                    '100%': {
                        'background-color': '#38bdf8'
                    }
                },
                moveUpAdjustWidth: {
                    '0%': {
                        width: '100%',
                        'margin-top': '5rem',
                        opacity: 1,
                        '-webkit-transform': 'translateX(0)',
                        transform: 'translateX(0)'
                    },
                    '100%': {
                        width: '70%',
                        'margin-top': '0',
                        opacity: 0,
                        '-webkit-transform': 'translateX(20rem)',
                        transform: 'translateX(20rem)'
                    }
                }
            }
        },
        screens: {
            'xs': '420px',
            'mdx1': '820px',
            '1xl': '1300px',
            ...defaultTheme.screens,
        },
    },

    plugins: [forms, typography],
};
