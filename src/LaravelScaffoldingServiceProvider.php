<?php

namespace AbCreative\LaravelScaffolding;

use Illuminate\Support\ServiceProvider;

class LaravelScaffoldingServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/scaffolding.php' => config_path('scaffolding.php'),
            __DIR__ . '/stubs/database/definitions/example.yaml' => database_path('definitions/example.yaml'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        
        $this->commands(
            'AbCreative\LaravelScaffolding\Commands\BuildCrudCommand',
            'AbCreative\LaravelScaffolding\Commands\BuildViewCommand',
            'AbCreative\LaravelScaffolding\Commands\BuildModelCommand',
            'AbCreative\LaravelScaffolding\Commands\BuildControllerCommand',
            'AbCreative\LaravelScaffolding\Commands\BuildRequestCommand',
            'AbCreative\LaravelScaffolding\Commands\BuildMigrationCommand',
            'AbCreative\LaravelScaffolding\Commands\BuildCleanCommand',
            'AbCreative\LaravelScaffolding\Commands\BuildRouteCommand',
            'AbCreative\LaravelScaffolding\Commands\BuildYamlCommand'
        );
        
        $this->mergeConfigFrom(
            __DIR__ . '/config/scaffolding.php', 'scaffolding'
        );
        
    }
}
