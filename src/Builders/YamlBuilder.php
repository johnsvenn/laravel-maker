<?php

namespace AbCreative\LaravelMaker\Builders;

use AbCreative\LaravelMaker\ProcessTables;
use Illuminate\Console\Command;

class YamlBuilder extends BaseBuilder implements BuilderInterface
{
    /*
     * Stubs for this Builder are specified in /src/config/maker.php
     */

    /**
     * @param Command $command
     * @param resource $resource
     */
    public function __construct(Command $command, ProcessTables $resource)
    {
        $this->command = $command;

        $this->resource = $resource;

        $this->init();
    }

    public function init()
    {
        $output = $this->resource->output();

        $file = implode('_', $this->resource->tables).'.yaml';

        $file = base_path(config('maker.definitions-directory').$file);

        if ($this->writeOutputFile($file, $output)) {
            $this->command->info('Created: '.$file);
        } else {
            $this->command->error('Unable to create: '.$file);
        }
    }
}
