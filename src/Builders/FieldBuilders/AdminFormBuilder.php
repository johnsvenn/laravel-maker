<?php

namespace AbCreative\LaravelMaker\Builders\FieldBuilders;

class AdminFormBuilder extends BaseFieldBuilder implements FormInterface, FieldBuilderInterface {
    
    /**
     * 
     * {@inheritDoc}
     * @see \AbCreative\LaravelMaker\Builders\FieldBuilders\FieldBuilderInterface::process()
     */
    public function fields()
    {
        
        $str = '';
       
        foreach ($this->resource->editableFields() as $field => $vars) {

            $str .= $this->stub($vars);
 
        }

        return $str;
        
    }
    
    /**
     * Process the stub and do the text replacement
     *
     * @param array $vars
     * @return mixed|string
     */
    public function stub($vars)
    {
        $vars = $this->map($vars);
    
        $stub = __DIR__ . '/fields/' . $vars['field_type'] . '.form.field.stub';
    
        if (! $this->filesystem->exists($stub)) {
    
            $stub = __DIR__ . '/fields/text.form.field.stub';
    
        }

        $output = $this->filesystem->get($stub);

        foreach ($vars as $key => $value) {

            $key = '__' . strtoupper($key) . '__';
    
            if (strpos($output, $key) !== false) {
    
                $output = str_replace($key, $value, $output);
    
            }
    
        }
    
        return $output;
    
    }
    
   

}

