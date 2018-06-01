<?php

namespace AbCreative\LaravelMaker\Builders\FieldBuilders;

use AbCreative\LaravelMaker\Resource;
use Illuminate\Filesystem\Filesystem;
/**
 * Classes that display fields in stubs should define this interface
 *
 * @author john
 *
 */
interface FieldBuilderInterface {
    
    /**
     *
     * @param Resource $resource
     */
    public function __construct(Resource $resource, Filesystem $filesystem);
    
    /**
     * 
     * @param string $match
     * @param string $str
     */
    public function process($match, $str);
    
    
    
    
}