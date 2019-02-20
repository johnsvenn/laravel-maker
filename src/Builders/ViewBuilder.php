<?php

namespace AbCreative\LaravelMaker\Builders;

class ViewBuilder extends BaseBuilder implements BuilderInterface
{
    /*
     * Stubs for this Builder are specified in /src/config/maker.php
     */

    public function init()
    {
        $this->processStubs();
    }

    /**
     * Replace the placeholders in the stub file.
     *
     * @param string $stub
     * @return string
     */
    protected function replacePlaceholders($stub)
    {
        $output = parent::replacePlaceholders($stub);

        return $output;
    }
}
