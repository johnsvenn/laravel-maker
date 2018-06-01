<?php
namespace AbCreative\LaravelMaker\FileParsers;

use Symfony\Component\Yaml\Dumper;

class YamlDumper extends Dumper{

    /**
     * {@inheritDoc}
     * @see \Symfony\Component\Yaml\Dumper::dump()
     */
    public function output($array)
    {

        /*
         * The second argument of the :method:`Symfony\\Component\\Yaml\\Dumper::dump` 
         * method customizes the level at which the output switches from the expanded 
         * representation to the inline one
         */
        $string = $this->dump($array, 10);
        
        /*
         * Let's not be quite as zealous with the quoting...
         * e.g. 'enum(''one'',''two'',''three'')' -> enum('one','two','three') (still valid)
         * 
         */        
        $string = str_replace("''", '<quote>', $string);
        
        $string = str_replace("'", '', $string);
        
        $string = str_replace('<quote>', "'", $string);
        
        return $string;
        
    }
    
    
}