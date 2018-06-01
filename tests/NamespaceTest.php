<?php

namespace AbCreative\LaravelMaker;

use Tests\TestCase;
use Illuminate\Filesystem\Filesystem;

/*
 * Test of relationshsips using the default values
 * 
 * Run this with ../../../vendor/bin/phpunit
 */
class NamespaceTest extends Testcase
{
    
    

    
    /**
     * Test that we can set an alternate namespace 'Members' 
     */
    public function testNonAdminNamespace()
    {
        
        $filesystem = new Filesystem();
        
        $file = __DIR__ . '/data/namespacetest.yaml';
        
        $resource_definitions = new ProcessResourceDefinitions($filesystem);
        
        $resource_definitions->process($file);
        
        $data = $resource_definitions->resources;
        
        $this->assertEquals('Members', $data[0]->placeholders['controller_namespace']);
        
        $this->assertEquals('members', $data[0]->placeholders['resource_view_namespace']);
        
        $this->assertEquals('members', $data[0]->placeholders['route_prefix']);
        
    }
    
    /**
     * Test that default admin namespace 'Admin' works
     */
    public function testAdminNamespace()
    {
    
        $filesystem = new Filesystem();
    
        $file = __DIR__ . '/data/good.yaml';
    
        $resource_definitions = new ProcessResourceDefinitions($filesystem);
    
        $resource_definitions->process($file);
    
        $data = $resource_definitions->resources;
    
        $this->assertEquals('Admin', $data[0]->placeholders['controller_namespace']);
    
        $this->assertEquals('admin', $data[0]->placeholders['resource_view_namespace']);
    
        $this->assertEquals('admin', $data[0]->placeholders['route_prefix']);
    
    }
    
}
