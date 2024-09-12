/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        'node_modules/preline/dist/*.js',
    ],
  theme: {
    extend: {},
      container: {
          center: true,
          padding: '1rem',
      },
      screens: {
          sm: '640px',
          md: '768px',
          lg: '1024px',
          xl: '1280px',
      },
      fontFamily: {
          manrope: ['Manrope', 'sans-serif'],
      },
  },
    darkMode: 'class',
  plugins: [
      require('preline/plugin'),
  ],
}

