<?php

namespace AbCreative\LaravelMaker\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use AbCreative\LaravelMaker\ProcessResourceDefinitions;


abstract class BaseCommand extends Command
{
    /**
     * Should we overwrite existing files?
     * @var boolean
     */
    public $force = false;
    
    /**
     * Should we delete existing files?
     * @var boolean
     */
    public $clean = false;

    /**
     * The resource_types to run...
     *
     * @var array
     */
    protected $resource_types = [];
    

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();

        $this->filesystem = $filesystem;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $file = trim($this->input->getArgument('file'));

        $this->force = $this->option('force');
        
        $this->clean = $this->option('clean');
        
        $object_type = 'Builder';
        
        if ($this->clean == true) {
            
            $object_type = 'Cleaner';
            
        }

        $file = base_path(config('maker.definitions-directory') . $file);

        if (! $this->filesystem->exists($file)) {

            $this->error($file . ' does not exist');

            return false;

        } else {

            /*
             * Returns an array of resource objects
             */
            $resource_definitions = new ProcessResourceDefinitions($this->filesystem);

            $resource_definitions->process($file);

            /*
             * We are throwing exceptions so we only get to this point if all the resource definitions are correct
             */

            if (!empty($resource_definitions->resources)) {

                
                foreach ($resource_definitions->resources as $resource) {

                    /*
                     * Create Builder or Cleaner objects
                     */
                    foreach ($this->resource_types as $object) {
                        
                        $object = 'AbCreative\\LaravelMaker\\' . str_plural($object_type) . '\\' . $object . $object_type;

                        new $object($this, $resource);
                    
                    }
                    

                }


            }

        }

    }
    
    

}