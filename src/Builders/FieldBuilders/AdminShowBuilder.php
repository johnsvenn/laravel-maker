<?php

namespace AbCreative\LaravelMaker\Builders\FieldBuilders;

class AdminShowBuilder extends BaseFieldBuilder implements AdminShowInterface, FieldBuilderInterface
{
    /**
     * {@inheritdoc}
     * @see \AbCreative\LaravelMaker\Builders\FieldBuilders\FieldBuilderInterface::process()
     */
    public function fields()
    {
        $str = '';

        foreach ($this->resource->fields as $field => $vars) {
            $str .= $this->stub($vars);
        }

        return $str;
    }

    /**
     * @param unknown $vars
     * @return mixed|string
     */
    public function stub($vars)
    {
        $vars = $this->map($vars);

        $stub = __DIR__.'/fields/admin.show.stub';

        $output = $this->filesystem->get($stub);

        foreach ($vars as $key => $value) {
            $key = '__'.strtoupper($key).'__';

            if (strpos($output, $key) !== false) {
                $output = str_replace($key, $value, $output);
            }
        }

        return $output;
    }
}
