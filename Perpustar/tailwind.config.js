/** @type {import('tailwindcss').Config} */
import daisyui from 'daisyui';
export default {
  content: ['./index.html', './src/**/*.{vue,js,ts,jsx,tsx}'],
  theme: {
    container: {
      center: true,
      padding: '36px',
    },
    extend: {
      colors: {
        coal: '#14b8a6',
        bluebird: '#478CCF',
      },
      screens: {
        '2xl': '1320px',
      },
      fontFamily: {
        body: ['Oswald'],
      },
    },
  },
  plugins: [daisyui],
  daisyui: {
    themes: ['lofi', 'night'],
  },
};
