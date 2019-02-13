<?php

namespace AbCreative\LaravelMaker;

use Orchestra\Testbench\TestCase;
use Illuminate\Filesystem\Filesystem;
use AbCreative\LaravelMaker\Exceptions\ResourceValidatorException;

/*
 * Test of relationshsips using the default values
 * 
 */
class DefaultRelationshipsTest extends Testcase
{
    
    

    
    /**
     * Test that a simple definition e.g. 
     * 
     * hasMany:
     *   - Book
     * 
     * 
     */
    public function testHasMany()
    {
        
        $filesystem = new Filesystem();
        
        $file = __DIR__ . '/data/defaultrelationshipstest.yaml';
        
        $resource_definitions = new ProcessResourceDefinitions($filesystem);
        
        $resource_definitions->process($file);
        
        $data = $resource_definitions->resources;
      
        $relationship = [
                
                        'method' => 'books',
                        'model' => 'App\Models\Book',
                        'foreign_key' => 'book_id',
                        'local_key' => ''
                
        ];
        
        if (!isset($data[0]->relationships['hasMany'][0]) || empty($data[0]->relationships['hasMany'][0])) {
        
            $this->fail('No relationship present');
        
        } else {
        
            $this->assertEquals($relationship, $data[0]->relationships['hasMany'][0]);
        
        
        }
        
    }
    
    /**
     * Test that a simple definition e.g.
     *
     * hasMany:
     *   - Book
     *
     *
     */
    public function testHasOne()
    {
    
        $filesystem = new Filesystem();
    
        $file = __DIR__ . '/data/defaultrelationshipstest.yaml';
    
        $resource_definitions = new ProcessResourceDefinitions($filesystem);
    
        $resource_definitions->process($file);
    
        $data = $resource_definitions->resources;
    
        $relationship = [
    
                'method' => 'editor',
                'model' => 'App\Models\Editor',
                'foreign_key' => 'editor_id',
                'local_key' => ''
    
        ];
    
        if (!isset($data[1]->relationships['hasOne'][0]) || empty($data[1]->relationships['hasOne'][0])) {
    
            $this->fail('No relationship present');
    
        } else {
    
            $this->assertEquals($relationship, $data[1]->relationships['hasOne'][0]);
    
    
        }
    
    }
    
    /**
     * Test that a simple definition e.g.
     * 
     * belongsTo:
     *   - Author
     *
     *
     */
    public function testBelongsTo()
    {
    
        $filesystem = new Filesystem();
    
        $file = __DIR__ . '/data/defaultrelationshipstest.yaml';
    
        $resource_definitions = new ProcessResourceDefinitions($filesystem);
    
        $resource_definitions->process($file);
    
        $data = $resource_definitions->resources;
    
        $relationship = [
                
                        'method' => 'author',
                        'model' => 'App\Models\Author',
                        'foreign_key' => 'author_id',
                        'other_key' => ''
                
        ];
    
        if (!isset($data[1]->relationships['belongsTo'][0]) || empty($data[1]->relationships['belongsTo'][0])) {
    
            $this->fail('No relationship present');
    
        } else {
    
            $this->assertEquals($relationship, $data[1]->relationships['belongsTo'][0]);

        }
    
    }
    
    /**
     * Test that a simple definition e.g.
     * 
     * belongsTo:
     *   - App\User
     *
     *
     */
    public function testBelongsToUser()
    {
    
        $filesystem = new Filesystem();
    
        $file = __DIR__ . '/data/defaultrelationshipstest.yaml';
    
        $resource_definitions = new ProcessResourceDefinitions($filesystem);
    
        $resource_definitions->process($file);
    
        $data = $resource_definitions->resources;
    
        $relationship = [
               
                        'method' => 'user',
                        'model' => 'App\User',
                        'foreign_key' => 'user_id',
                        'other_key' => ''
                
        ];
    
        if (!isset($data[0]->relationships['belongsTo'][0]) || empty($data[0]->relationships['belongsTo'][0])) {
    
            $this->fail('No relationship present');
    
        } else {
    
            $this->assertEquals($relationship, $data[0]->relationships['belongsTo'][0]);
    
    
        }
    
    }
    
    /**
     * Test that 
     */
    public function testModelNamespace()
    {
        
        $object = new ProcessResourceRelationships();
        
        $model = 'App\User';
        
        $new_model = $object->getModel($model);
        
        $this->assertEquals($new_model, 'App\\User');
        
        $model = 'User';
        
        $new_model = $object->getModel($model);
        
        $this->assertEquals('App\\Models\\User', $new_model);
        
        
    }
    
   
    
}
