<?php

namespace AbCreative\LaravelMaker\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use AbCreative\LaravelMaker\ProcessResourceDefinitions;
use AbCreative\LaravelMaker\Builders\ModelBuilder;
use AbCreative\LaravelMaker\Builders\ControllerBuilder;
use AbCreative\LaravelMaker\Builders\RequestBuilder;
use AbCreative\LaravelMaker\Builders\MigrationBuilder;
use AbCreative\LaravelMaker\Builders\ViewBuilder;
use AbCreative\LaravelMaker\Builders\RouteBuilder;

class BuildCrudCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:crud {file : The name of the definition file in /databases/models/ e.g. posts.yaml} {--force} {--clean}';

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    public $filesystem;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new model, controllers, routes, views and migration';
    
    
    /**
     * The resource_types to run...
     * 
     * @var array
     */
    protected $resource_types = [
        
        'Migration',
        'Controller',
        'Template',
        'Model',
        'Request',
        'View',
        'Route'
        
    ];

    
 
}