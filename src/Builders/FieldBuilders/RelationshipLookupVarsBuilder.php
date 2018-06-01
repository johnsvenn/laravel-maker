<?php

namespace AbCreative\LaravelMaker\Builders\FieldBuilders;

class RelationshipLookupVarsBuilder extends BaseFieldBuilder {

    /**
     *
     * @param unknown $match
     * @param unknown $str
     * @return mixed
     */
    public function process($match, $str)
    {


        $output = $this->buildRelationships();

        $str = str_replace($match, $output, $str) . PHP_EOL;

        return $str;

    }

    /**
     * Loop through the array of relationship types - there could be multiple instances of each type of relationship
     *
     * @return string
     */
    private function buildRelationships()
    {

        $str = '';

        if (!empty($this->resource->relationships)) {

            foreach ($this->resource->relationships as $key => $vars) {

                switch ($key) {
                    
                    case 'belongsTo':
                        
                        foreach ($vars as $v) {
                        
                            $str .= ", '" . $v['method'] . "'";
                            
                        }
                        
                        break;
                    
                    
                }

            }

        }

        return $str;

    }

    


}


