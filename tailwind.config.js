import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import preline from 'preline/plugin';

/** @type {import('tailwindcss').Config} */

export default {
    darkMode: 'class', // Enables class-based dark mode
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        'node_modules/preline/dist/*.js',
        './src/**/*.{html,js}',
    ],
    theme: {
        extend: {

        }
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('preline/plugin'),
    ],
};
