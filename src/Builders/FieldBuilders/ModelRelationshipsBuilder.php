<?php

namespace AbCreative\LaravelMaker\Builders\FieldBuilders;

class ModelRelationshipsBuilder extends BaseFieldBuilder implements ModelRelationshipsInterface {
    
    /**
     *
     * @param unknown $match
     * @param unknown $str
     * @return mixed
     */
    public function process($match, $str)
    {
    
    
        $output = $this->buildRelationships();

        $str = str_replace($match, $output, $str) . PHP_EOL;
    
        return $str;
    
    }
    
    /**
     * Loop through the array of relationship types - there could be multiple instances of each type of relationship
     * 
     * @return string
     */
    private function buildRelationships()
    {
        
        $str = '';
        
        if (!empty($this->resource->relationships)) {
            
            foreach ($this->resource->relationships as $key => $vars) {
                
                $stub = __DIR__ . '/fields/' . $key . '.stub';
                
                $stub = $this->filesystem->get($stub);
                
                foreach ($vars as $relationship) {
                
                    $output = $this->stub($stub, $relationship);
                
                    $str .= $output . PHP_EOL . PHP_EOL;
                
                }

            }
 
        }
        
        return $str;
        
    }
    
    /**
     * Create the relationship method from the stubs
     * 
     * @param string $output the stub template file for this relationship
     * @param array $vars
     * @return string
     */
    private function stub($output, $vars)
    {

        foreach ($vars as $key => $value) {

            $key = '__' . strtoupper($key) . '__';

            if (strpos($output, $key) !== false) {
                
                if (!empty($value)) {
                    
                    switch ($key) {
                        
                        case '__METHOD__':
                            
                            // do nothing
                            
                            break;
                        case '__MODEL__':
                            
                            $value = "'" . $value . "'";
                            
                            break;
                        default:
                            
                            $value = ", '" . $value . "'";
                        
                        
                    }
                }
                
                $output = str_replace($key, $value, $output);
        
            } 
        
        }
        
        return $output;
        
    }

    
}


