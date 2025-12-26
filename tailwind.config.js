/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        primary: '#1b1b18',
        secondary: '#706f6c',
      }
    },
  },
  darkMode: 'class',
  plugins: [],
}
