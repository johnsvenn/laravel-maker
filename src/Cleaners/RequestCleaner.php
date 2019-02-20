<?php

namespace AbCreative\LaravelMaker\Cleaners;

use AbCreative\LaravelMaker\Builders\RequestBuilder;

class RequestCleaner extends RequestBuilder
{
    use CleanerTrait;

    public function init()
    {
        $this->cleanStubs();
    }
}
