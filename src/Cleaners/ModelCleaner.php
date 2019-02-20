<?php

namespace AbCreative\LaravelMaker\Cleaners;

use AbCreative\LaravelMaker\Builders\ModelBuilder;

class ModelCleaner extends ModelBuilder
{
    use CleanerTrait;

    public function init()
    {
        $this->cleanStubs();
    }
}
