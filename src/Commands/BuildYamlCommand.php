<?php

namespace AbCreative\LaravelMaker\Commands;

use AbCreative\LaravelMaker\ProcessTables;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Schema;

class BuildYamlCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:yaml {tables : The tables to create Yaml for e.g table1, table2 } {--force} {--clean}';

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
    protected $description = 'Create Yaml from one or more database tables';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $tables = trim($this->input->getArgument('tables'));

        $this->force = $this->option('force');

        $this->clean = $this->option('clean');

        $object_type = 'Builder';

        if ($this->clean == true) {
            $object_type = 'Cleaner';
        }

        $tables = array_map('trim', explode(',', $tables));

        if (! empty($tables)) {
            foreach ($tables as $table) {
                if (! Schema::hasTable($table)) {
                    $this->error('Table '.$table.' does not exist');

                    return false;
                }
            }

            $resource = new ProcessTables($tables);

            $resource->process();

            $object = 'AbCreative\\LaravelMaker\\'.str_plural($object_type).'\\Yaml'.$object_type;

            new $object($this, $resource);

        /*

        */
        } else {
            $this->error('Please specify tables');
        }
    }
}
