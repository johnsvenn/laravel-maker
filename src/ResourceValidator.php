<?php

namespace AbCreative\LaravelMaker;

use AbCreative\LaravelMaker\Exceptions\ResourceValidatorException;

/**
 * Format.
 * @author john
 */
class ResourceValidator
{
    private $className = 'Illuminate\Database\Schema\Blueprint';

    public function __construct()
    {
    }

    /**
     * @param unknown $fields
     */
    public function validateFields($fields)
    {
        foreach ($fields as $key => $var) {
            $this->validateColumn($key, $var);
        }
    }

    /**
     * Ensure that the column types match https://laravel.com/docs/5.5/migrations#creating-columns.
     *
     * @param unknown $key
     * @param unknown $var
     * @throws ResourceValidatorException
     */
    public function validateColumn($key, $var)
    {
        try {
            $r = new \ReflectionMethod($this->className, $var['type']);

            $min = $r->getNumberOfRequiredParameters();

            $max = $r->getNumberOfParameters();

            $count_params = 0;

            if ($var['params'] !== null) {
                $count_params = count($var['params']);
            }

            if (($min > $count_params + 1) || ($max < $count_params + 1)) {
                throw new ResourceValidatorException("Type '".$var['type']."' for field  '".$key."' has the wrong number of arguments. Please refer to https://laravel.com/docs/migrations#creating-columns.");
            }
        } catch (\ReflectionException $e) {
            throw new ResourceValidatorException("Type '".$var['type']."' for field  '".$key."' is not a valid laravel column type. Please refer to https://laravel.com/docs/migrations#creating-columns.");
        }
    }
}
