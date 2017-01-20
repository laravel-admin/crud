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
         $this->loadViewsFrom(__DIR__.'/../resources/views', 'crud');
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
