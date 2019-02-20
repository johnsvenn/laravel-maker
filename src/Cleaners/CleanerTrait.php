<?php

namespace AbCreative\LaravelMaker\Cleaners;

trait CleanerTrait
{
    /**
     * Delete all the stub files.
     */
    protected function cleanStubs()
    {
        $dirs = [];

        foreach ($this->stubs() as $stub => $file) {
            $file = $this->getOutputPath($file);

            if ($this->command->filesystem->exists($file)) {
                $this->command->filesystem->delete($file);

                $this->command->info('Deleted: '.$file);
            }

            $dirs[] = dirname($file);
        }

        $this->removeEmptyDirectories($dirs);
    }

    /**
     * Delete any empty directories.
     *
     * @param array $dirs
     */
    protected function removeEmptyDirectories($dirs = [])
    {
        $dirs = array_unique($dirs);

        foreach ($dirs as $dir) {
            if ($this->command->filesystem->exists($dir)) {
                $files = $this->command->filesystem->files($dir);

                if (empty($files)) {
                    $this->command->filesystem->deleteDirectory($dir);

                    $this->command->info('Deleted directory: '.$dir);
                }
            }
        }
    }
}
