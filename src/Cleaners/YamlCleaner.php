<?php

namespace AbCreative\LaravelMaker\Cleaners;

use AbCreative\LaravelMaker\Builders\YamlBuilder;

class YamlCleaner extends YamlBuilder
{
    use CleanerTrait;

    public function init()
    {
        $this->clean();
    }

    public function clean()
    {
        $file = implode('_', $this->resource->tables).'.yaml';

        $file = base_path(config('maker.definitions-directory').$file);

        if ($this->command->filesystem->exists($file)) {
            $this->command->filesystem->delete($file);

            $this->command->info('Deleted: '.$file);
        }
    }
}
