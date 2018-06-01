<?php

namespace AbCreative\LaravelMaker;

use Illuminate\Support\ServiceProvider;

class LaravelMakerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/maker.php' => config_path('maker.php'),
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
            'AbCreative\LaravelMaker\Commands\BuildCrudCommand',
            'AbCreative\LaravelMaker\Commands\BuildViewCommand',
            'AbCreative\LaravelMaker\Commands\BuildModelCommand',
            'AbCreative\LaravelMaker\Commands\BuildControllerCommand',
            'AbCreative\LaravelMaker\Commands\BuildRequestCommand',
            'AbCreative\LaravelMaker\Commands\BuildMigrationCommand',
            'AbCreative\LaravelMaker\Commands\BuildCleanCommand',
            'AbCreative\LaravelMaker\Commands\BuildRouteCommand',
            'AbCreative\LaravelMaker\Commands\BuildYamlCommand'
        );
        
        $this->mergeConfigFrom(
            __DIR__ . '/config/maker.php', 'maker'
        );
        
    }
}
