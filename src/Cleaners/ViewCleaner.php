<?php

namespace AbCreative\LaravelMaker\Cleaners;

use AbCreative\LaravelMaker\Builders\ViewBuilder;

class ViewCleaner extends ViewBuilder
{
    use CleanerTrait;

    public function init()
    {
        $this->cleanStubs();
    }
}
