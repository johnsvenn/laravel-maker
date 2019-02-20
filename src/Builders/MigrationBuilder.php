<?php

namespace AbCreative\LaravelMaker\Builders;

use AbCreative\LaravelMaker\Resource;
use Illuminate\Console\Command;

class MigrationBuilder implements BuilderInterface
{
    /**
     * @param Command $command
     * @param resource $resource
     */
    public function __construct(Command $command, Resource $resource)
    {
        $this->command = $command;

        $this->resource = $resource;

        $this->init();
    }

    public function init()
    {
        $this->buildMigration();
    }

    /**
     * Build a migration using https://github.com/laracasts/Laravel-5-Generators-Extended.
     */
    protected function buildMigration()
    {
        $params = [];
        $params['name'] = $this->getMigrationName();
        $params['--schema'] = $this->buildSchema();
        $params['--model'] = false;

        \Artisan::call('make:migration:schema', $params);
    }

    /**
     * Consistent format for migration names;.
     *
     * @return string
     */
    protected function getMigrationName()
    {
        return 'create_'.$this->resource->placeholders['model_table'].'_table';
    }

    /**
     * Build a schema string that can be passed into make:migration:schema.
     *
     * @link https://github.com/laracasts/Laravel-5-Generators-Extended#migrations-with-schema
     *
     * @return string
     */
    protected function buildSchema()
    {
        $array = [];

        foreach ($this->resource->migratableFields() as $field => $vars) {
            $str = [];

            $str[] = $field;
            $str[] = $vars['column'];

            if (! empty($vars['modifiers'])) {
                foreach ($vars['modifiers'] as $var) {

                    /*
                     * In the generator only options like defaults can have the () e.g. default('20')
                     */
                    if (ends_with($var, '()')) {
                        $var = str_replace('()', '', $var);
                    }

                    $str[] = $var;
                }
            }

            $array[] = implode(':', $str);
        }

        $str = '';

        $str = implode(', ', $array);

        return $str;
    }
}
