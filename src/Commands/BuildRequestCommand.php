<?php

namespace AbCreative\LaravelMaker\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use AbCreative\LaravelMaker\ProcessResourceDefinitions;
use AbCreative\LaravelMaker\Builders\RequestBuilder;


class BuildRequestCommand extends BaseCommand 
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:request {file : The name of the definition file in /databases/models/ e.g. posts.yaml} {--force} {--clean}';

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
    protected $description = 'Create a new set of request classes';

    /**
     * The resource_types to run...
     * 
     * @var array
     */
    protected $resource_types = [
        'Request'
    ];
    

}