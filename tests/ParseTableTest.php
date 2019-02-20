<?php

namespace AbCreative\LaravelMaker;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Orchestra\Testbench\TestCase;

class ParseTableTest extends Testcase
{
    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'mysql');
        $app['config']->set('database.connections.mysql', [
                'driver'   => 'mysql',
                'host' => 'localhost',
                'database' => env('DB_DATABASE', 'laravelmakertests'),
                'username' => env('DB_USERNAME', 'homestead'),
                'password' => env('DB_PASSWORD', 'secret'),
        ]);
    }

    protected function load()
    {
        $contents = File::get(__DIR__.'/data/createyaml.sql');

        DB::unprepared($contents);
    }

    protected function unload()
    {
        $contents = File::get(__DIR__.'/data/drop_createyaml.sql');

        DB::unprepared($contents);
    }

    /**
     * Create a database table
     * Parse the table
     * Check that created Yaml matches the expected Yaml.
     */
    public function testParseTableToYaml()
    {
        $this->load();

        //

        $tables = ['examples'];

        $resource = new ProcessTables($tables);

        $resource->process();

        $test = $resource->output();

        $expected = File::get(__DIR__.'/data/example.yaml');

        $this->assertEquals($test, $expected);

        //

        $this->unload();
    }
}
