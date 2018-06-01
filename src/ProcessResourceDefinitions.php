<?php

namespace AbCreative\LaravelMaker;

use AbCreative\LaravelMaker\FileParsers\YamlParser;
use AbCreative\LaravelMaker\ResourceValidator;


class ProcessResourceDefinitions {
    
    protected $parser = null;
    
    /**
     * An array of resource objects 
     * @var array
     */
    public $resources = [];
    
    protected $filesystem = null;
    
    protected $resource_validator = null;
    
    public function __construct($filesystem)
    {  
            $this->filesystem = $filesystem;
            $this->parser = new YamlParser();
            $this->resource_validator = new ResourceValidator();
    }
    
    public function process($file)
    {

        $resources = $this->parseFile($file);

        foreach ($resources as $resource => $var) {
            
            $this->resources[] = new Resource($this->resource_validator, $resource, $var);
            
        }

    }
    
    public function parseFile($file)
    {
        return $this->parser->parseTheFile($this->filesystem, $file);
    }
    
    
}