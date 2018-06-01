<?php

namespace AbCreative\LaravelMaker;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use AbCreative\LaravelMaker\ProcessTables;
use Illuminate\Support\Facades\File;


/*
 * Run this with ../../../vendor/bin/phpunit
 */
class ParseTableTest extends Testcase
{
    
    protected function load()
    {
        
     

        $contents = File::get(__DIR__ . '/data/createyaml.sql');
        
        DB::unprepared($contents);
        
    }
    
    protected function unload()
    {
    

        $contents = File::get(__DIR__ . '/data/drop_createyaml.sql');
        
        DB::unprepared($contents);
    
    }
    
    /**
     * Create a database table 
     * Parse the table
     * Check that created Yaml matches the expected Yaml
     */
    public function testParseTableToYaml()
    {

        $this->load();
        
        //

        $tables = array('examples');
        
        $resource = new ProcessTables($tables);
        
        $resource->process();
        
        $test = $resource->output();

        $expected = File::get(__DIR__ . '/data/example.yaml');
        
        $this->assertEquals($test, $expected);
 
        //
        
        $this->unload();
        
    }
    
    
    
    
    
    
}