<?php

namespace AbCreative\LaravelMaker;

trait UtilsTrait {
    
    /**
     * Create a directory if it doesn't exist
     *
     * @param string $path
     */
    protected function makeDirectory($path, $mode = '0777')
    {
    
        if (! $this->command->filesystem->exists($path)) {
    
            $this->command->filesystem->makeDirectory($path, $mode, true);
    
            $this->command->info('Created directory: ' . $path);
    
        }
    
    }
    
    /**
     * Make sure that the parent directories of a file path exist
     *
     * @param string $file
     */
    protected function createParentDirectoryPath($file)
    {
    
        $dir = dirname($file);
    
        $this->makeDirectory($dir);
    
    }
    
    
    
}