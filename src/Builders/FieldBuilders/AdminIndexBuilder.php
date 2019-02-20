<?php

namespace AbCreative\LaravelMaker\Builders\FieldBuilders;

class AdminIndexBuilder extends BaseFieldBuilder implements AdminIndexInterface
{
    public $stub = 'admin.index';

    private function tab($n)
    {
        $str = '';

        $tab = '    '; // $spaces

        for ($i = 0; $i < $n; $i++) {
            $str .= $tab;
        }

        return $str;
    }

    /**
     * {@inheritdoc}
     * @see \AbCreative\LaravelMaker\Builders\FieldBuilders\FieldBuilderInterface::process()
     */
    public function fields()
    {
        $thead = [];
        $tbody = [];

        $defaults = config('maker.stubs-view-fields');

        $defaults = $defaults[$this->stub];

        foreach ($this->resource->fields as $vars) {
            if ($this->displayAllowedField($defaults, $vars)) {
                $thead[] = '<th>'.$vars['label'].'</th>';

                $tbody[] = '<td>{{ $__MODEL.VAR.NAME__->'.$vars['field'].' }}</td>';
            }
        }

        $vars = [
            'thead' => implode(PHP_EOL.$this->tab(1), $thead),
            'tbody' => implode(PHP_EOL.$this->tab(3), $tbody),

        ];

        return $this->stub($vars);
    }

    /**
     * @param unknown $vars
     * @return mixed|string
     */
    public function stub($vars)
    {
        $stub = __DIR__.'/fields/'.$this->stub.'.stub';

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
