module.exports = {
    mode: 'jit',
    content: [
        './*/*.php',
        './**/*.php',
        './assets/css/*.css',
        './resources/js/*.js',
    ],
    corePlugins: {
      // ...
     container: false,
    },
    theme: {
        fontSize: {
            'xs': '.75rem',
            'sm': '.875rem',
            'tiny': '.875rem',
            'base': '1rem',
            'lg': '1.125rem',
            'xl': '1.25rem',
            '1xl': '1.35rem',
            '2xl': '1.5rem',
            '3xl': '1.875rem',
            '4xl': '2.25rem',
            '5xl': '2.5rem',
            '6xl': '3rem',
            '7xl': '5rem',
        },
        fontFamily: {
            'sans': ['Lato', 'system-ui'],
            'serif': ['Playfair Display', 'Georgia'],
            'body': ['Lato'],
        },
        extend: {
            colors: {
                'amuse-purple': '#573B7F',
                'amuse-pink': '#f70099',
                'amuse-dark-purple': '#210B3D',
            },
            screens: {
                'xl': '1200px',
            },
        },
    },
    plugins: [],
}

