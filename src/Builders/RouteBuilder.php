<?php

namespace AbCreative\LaravelMaker\Builders;



class RouteBuilder extends BaseBuilder implements BuilderInterface {

    /*
     * Stubs for this Builder are specified in /src/config/maker.php
     */


    public function init()
    {

        
        $this->processStubs();
        

    }
    
    public function processStubs()
    {
        
        foreach ($this->stubs() as $stub => $file) {
            
            $file = $this->getOutputPath($file);
            
            $current_routes_file = $this->command->filesystem->get($file);
            
            $stub = $this->getStubPath($stub);
            
            $output = $this->replacePlaceholders($stub);

            if (strpos($current_routes_file, $output) === false) {
                
                $output = $current_routes_file . PHP_EOL . $output;
                
                if ($this->writeOutputFile($file, $output)) {
                     
                    $this->command->info('Updated: ' . $file);
                     
                } else {
                     
                    $this->command->error('Unable to update: ' . $file);
                }
                
            } else {
                
                $this->command->info('Route exists - skipping: ' . $file);
                
            }
            
            
        }
        
        
        
    }


}