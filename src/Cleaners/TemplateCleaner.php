<?php

namespace AbCreative\LaravelMaker\Cleaners;

use AbCreative\LaravelMaker\Builders\TemplateBuilder;

class TemplateCleaner extends TemplateBuilder
{
    use CleanerTrait;

    public function init()
    {
        $this->cleanStubs();
    }
}
