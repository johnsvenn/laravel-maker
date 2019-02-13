<?php

namespace AbCreative\LaravelScaffolding;

use Orchestra\Testbench\TestCase;
use Illuminate\Filesystem\Filesystem;
use AbCreative\LaravelScaffolding\Exceptions\ResourceValidatorException;

/*
 * If a field is not nullable AND does not have a default IT MUST be required
 * 
 * If a field has a default it must be set in the model
 * 
 */
class NullableDefaultRequiredTest extends Testcase
{
    /**
     * Just test that out tests are working!
     */
    public function testEmpty()
    {
        $stack = [];
        
        $this->assertEmpty($stack);
    
        return $stack;
    }
}