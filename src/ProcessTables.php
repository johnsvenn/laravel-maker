<?php

namespace AbCreative\LaravelMaker;

use AbCreative\LaravelMaker\Parsers\TableParser;
use AbCreative\LaravelMaker\FileParsers\YamlDumper;

class ProcessTables 
{
    
    
    /**
     * An array of resource objects
     * @var array
     */
    public $resources = [];

    protected $resource_validator = null;
    
    public $tables = [];
    
    protected $outputArray = [];
    
    protected $dumper = null;
    
    public function __construct($tables)
    {
       
        $this->tables = $tables;
        
        $this->parser = new TableParser();
        
        $this->dumper =  new YamlDumper();
       
    }
    
    public function process()
    {

        foreach ($this->tables as $table) {
            
            $this->outputArray = $this->makeData($table);
    
        }

    }
    
    public function output()
    {
        
        $output = $this->dumper->output($this->outputArray);
        
        return $output;
        
    }
    
    protected function makeData($table)
    {
        
        $array = [];
        
        $this->parser->parseTable($table);
        
        $fields = $this->parser->getTableSchema();
        
        $modelName = studly_case(str_singular($table));
        
        $array[$modelName]['model']['name'] = $modelName;
        $array[$modelName]['model']['table'] = $table;
        $array[$modelName]['model']['fields'] = $fields;

        return $array;
        
        
    }
    
   
    
    
    
    
}