<?php

namespace LaravelAdmin\Crud;

use Illuminate\Support\ServiceProvider;

class CrudServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //	Enable to load views with the specified prefix from the package
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'crud');

        // Publish the config
        $this->publishes([__DIR__ . '/../resources/config/layout.php' => config_path('layout.php')], 'admin-config');

        // Load config file
        $this->mergeConfigFrom(__DIR__ . '/../resources/config/layout.php', 'layout');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
