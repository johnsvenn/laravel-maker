<?php

namespace AbCreative\LaravelMaker;

use Tests\TestCase;
use Illuminate\Filesystem\Filesystem;
use AbCreative\LaravelMaker\Exceptions\ResourceValidatorException;

/*
 * Run this with ../../../vendor/bin/phpunit
 */
class ParseYamlTest extends Testcase
{
   
    
    /**
     * Using good data test that a resource object is returned
     */
    public function testGoodYaml()
    {
        $filesystem = new Filesystem();
        
        $file = __DIR__ . '/data/good.yaml';
        
        $resource_definitions = new ProcessResourceDefinitions($filesystem);
        
        $resource_definitions->process($file);
        
        $data = $resource_definitions->resources;
        
        if (empty($data)) {
            
            $this->fail('No resource present');
            
        } else {
            
            $this->assertInstanceOf(Resource::class, $data[0]);
            
            
        }
        
    }
  
    /**
     * Using bad data ensure that an exception is thrown
     * (the type definition for the field 'money' is wrong)
     */
    public function testBadYaml()
    {
        
        $this->expectException(ResourceValidatorException::class);
        
        $filesystem = new Filesystem();
        
        $file = __DIR__ . '/data/bad.yaml';
        
        $resource_definitions = new ProcessResourceDefinitions($filesystem);
        
        $resource_definitions->process($file);

    }
    
    
    
    
}
