const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')

if (mix.inProduction()) {
   mix.version();
}

mix.browserSync({
      proxy:'http://127.0.0.1:8000'
   });
   
mix.disableNotifications();
   