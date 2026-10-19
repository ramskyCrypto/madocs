/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                // Palet resmi identitas Madocs.
                cream: {
                    DEFAULT: '#FCE9CF',
                    50: '#FFFdf8',
                    100: '#FEF6EA',
                    200: '#FCE9CF',
                    300: '#F8D8AC',
                    400: '#F3C280',
                },
                madblue: {
                    DEFAULT: '#2A82F6',
                    50: '#EAF3FE',
                    100: '#D3E6FD',
                    400: '#5A9DF8',
                    500: '#2A82F6',
                    600: '#1465D6',
                    700: '#104FA8',
                },
                madorange: {
                    DEFAULT: '#F15A24',
                    50: '#FEEEE7',
                    100: '#FDDACB',
                    400: '#F37C51',
                    500: '#F15A24',
                    600: '#CC4415',
                    700: '#9E3611',
                },
            },
            fontFamily: {
                sans: ['Plus Jakarta Sans', 'ui-sans-serif', 'system-ui', 'sans-serif'],
            },
            boxShadow: {
                card: '0 1px 2px 0 rgba(16, 24, 40, 0.06), 0 1px 3px 0 rgba(16, 24, 40, 0.08)',
            },
            borderRadius: {
                xl2: '1.25rem',
            },
        },
    },
    plugins: [],
};
