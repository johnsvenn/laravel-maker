<?php
namespace AbCreative\LaravelMaker\FileParsers;

use Symfony\Component\Yaml\Parser;

class YamlParser extends Parser{

    public function parseTheFile($filesystem, $file)
    {

        $string =  $filesystem->get($file);

        return $this->parse($string);
        
    }

}