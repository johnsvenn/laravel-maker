<?php

namespace AbCreative\LaravelMaker;

use Illuminate\Filesystem\Filesystem;
use Orchestra\Testbench\TestCase;

/*
 * Test relationships that don't use the defaults
 *
 */
class CustomRelationshipsTest extends Testcase
{
    /**
     * Test a definition with custom method name and foregiegn_key e.g.
     *
     * belongsTo:
     *  - App\User:
     *      method: createdBy
     *      foreign_key: created_by
     */
    public function testBelongsToCreatedBy()
    {
        $filesystem = new Filesystem();

        $file = __DIR__.'/data/customrelationshipstest.yaml';

        $resource_definitions = new ProcessResourceDefinitions($filesystem);

        $resource_definitions->process($file);

        $data = $resource_definitions->resources;

        $relationship = [

                'method' => 'createdBy',
                'model' => 'App\User',
                'foreign_key' => 'created_by',
                'other_key' => '',

        ];

        if (! isset($data[0]->relationships['belongsTo'][0]) || empty($data[0]->relationships['belongsTo'][0])) {
            $this->fail('No relationship present');
        } else {
            $this->assertEquals($relationship, $data[0]->relationships['belongsTo'][0]);
        }
    }

    /**
     * Test a definition with custom attributes e.g.
     *
     * belongsTo:
     *     - Editor:
     *       method: bookEditor
     *       foreign_key: ref_id
     *       other_key: editor_ref_id
     */
    public function testBelongsToEditor()
    {
        $filesystem = new Filesystem();

        $file = __DIR__.'/data/customrelationshipstest.yaml';

        $resource_definitions = new ProcessResourceDefinitions($filesystem);

        $resource_definitions->process($file);

        $data = $resource_definitions->resources;

        $relationship = [

                'method' => 'bookEditor',
                'model' => 'Editor',
                'foreign_key' => 'ref_id',
                'other_key' => 'editor_ref_id',

        ];

        if (! isset($data[1]->relationships['belongsTo'][1]) || empty($data[1]->relationships['belongsTo'][1])) {
            $this->fail('No relationship present');
        } else {
            $this->assertEquals($relationship, $data[1]->relationships['belongsTo'][1]);
        }
    }

    /**
     * Test that a hasMany realtionship with cutom attributes e.g.
     *
     * hasMany:
     *    - Book:
     *       foreign_key: editor_ref_id
     *       local_key: ref_id
     */
    public function testEditorHasManyBook()
    {
        $filesystem = new Filesystem();

        $file = __DIR__.'/data/customrelationshipstest.yaml';

        $resource_definitions = new ProcessResourceDefinitions($filesystem);

        $resource_definitions->process($file);

        $data = $resource_definitions->resources;

        $relationship = [

                'method' => 'books',
                'model' => 'Book',
                'foreign_key' => 'editor_ref_id',
                'local_key' => 'ref_id',

        ];

        if (! isset($data[2]->relationships['hasMany'][0]) || empty($data[2]->relationships['hasMany'][0])) {
            $this->fail('No relationship present');
        } else {
            $this->assertEquals($relationship, $data[2]->relationships['hasMany'][0]);
        }
    }
}
