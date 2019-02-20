<?php

namespace AbCreative\LaravelMaker\Cleaners;

use AbCreative\LaravelMaker\Builders\ControllerBuilder;

class ControllerCleaner extends ControllerBuilder
{
    use CleanerTrait;

    public function init()
    {
        $this->cleanStubs();
    }
}
