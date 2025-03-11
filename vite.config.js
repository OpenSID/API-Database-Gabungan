import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    build: {
        rollupOptions: {
          //external: ['datatables.net-responsive', 'dataTables.mark.js', 'dataTables.net-buttons/js/buttons.colVis.min.mjs', 'dataTables.net-buttons/js/buttons.html5.min.mjs', 'dataTables.net-buttons/js/buttons.print.min.mjs'],
          output: {
            // expose jQuery as a global variable
            globals: {
              jquery: 'jQuery'
            }
          }
        },
      },
});
