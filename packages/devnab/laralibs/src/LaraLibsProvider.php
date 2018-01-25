<?php

namespace DevNab\LaraLibs;

use DevNab\LaraLibs\LaraLibsRoute;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class LaraLibsProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //

        $this->loadHelpers();

        if (Schema::hasTable('voyager_themes')) {
            //load theme function
            
            $theme = DB::table('voyager_themes')->where('active',1)->select('folder')->first();

            $this->loadThemeFunctions($theme);
        }
        
        
        
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind("cmsRoute","DevNab\LaraLibs\CMSRoute");

        $loader = AliasLoader::getInstance();
        $loader->alias("CMSRoute","DevNab\LaraLibs\Facades\CMSRoute");
    }


    private function loadHelpers() {
        foreach (glob(__DIR__.'/helpers/*.php') as $filename) {
            require_once $filename;
        }
    }


    private function loadThemeFunctions($theme) {

        //locate themes
        $theme_folder = config('themes.themes_folder',resource_path('views/themes'));
        
        $theme_function_file = $theme_folder . '/' . $theme->folder . '/functions.php';
        
        if(file_exists($theme_function_file)) {
            require_once $theme_function_file;
        }
        
    }

     // Duplicating the rescue function that's available in 5.5, just in case
    // A user wants to use this hook with 5.4
    
    function rescue(callable $callback, $rescue = null)
    {
        try {
            return $callback();
        } catch (Throwable $e) {
            report($e);
            return value($rescue);
        }
    }
}
