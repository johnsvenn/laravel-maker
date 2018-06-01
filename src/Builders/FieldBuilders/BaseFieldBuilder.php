<?php

namespace AbCreative\LaravelMaker\Builders\FieldBuilders;

use AbCreative\LaravelMaker\Resource;
use Illuminate\Filesystem\Filesystem;

class BaseFieldBuilder {

    protected $resource = null;
    
    protected $filesystem = null;
    
    /**
     * Map Laravel field types to the LaravelCollective/html FormBuilder field types
     * 
     * @var array
     */
    public $map = [
        
        'string' => 'text',
        'text' => 'textarea',
        'tinyint' => 'checkbox',
        'boolean' => 'checkbox',
        'float' => 'number',
        'decimal' => 'number',
        'integer' => 'number'
        
    ];
    
    /**
     * HTML5 form field types that are suported by LaravelCollective/html FormBuilder
     * 
     * @var array
     */
    public $form_field_types = [
        
        'hidden',
        'search',
        'email',
        'tel',
        'number',
        'date',
        'datetime',
        'datetime-local',
        'time',
        'url',
        'file',
        'textarea',
        'select',
        'checkbox',
        'radio',
        'image',
        'color'
        
    ];

    /**
     *
     * @param Resource $resource
     */
    public function __construct(Resource $resource, Filesystem $filesystem)
    {
        $this->resource = $resource;
        
        $this->filesystem = $filesystem;

    }
    
    /**
     * Print out a list of all the available fields with type
     * 
     * @return string
     */
    public function availableFields()
    {
        
        $str = [];
        
        foreach ($this->resource->fields as $name => $vars) {
            
            $str[] = $name . ' ' . $vars['type'];
            
        }
        
        $str = '<?php ' . PHP_EOL . '/*' . PHP_EOL . ' * Available fields: ' . PHP_EOL . ' * ' . PHP_EOL . ' * ' . implode(PHP_EOL . ' * ', $str) . PHP_EOL . ' */' . PHP_EOL . '?>' . PHP_EOL;
        
        return $str;
        
        
    }
    
    /**
     * 
     * @param string $match
     * @param string $str
     * @return mixed
     */
    public function process($match, $str)
    {
        
        
        $output = $this->availableFields();
        
        $output .= $this->fields();
        
        $str = str_replace($match, $output, $str);
        
        return $str;
        
    }
    
    /**
     * 
     * @return string
     */
    public function fields() 
    {
        
        return '';
        
    }
    
    /**
     * Check if this field should be displayed in a view
     * 
     * @param array $defaults array of default allowed fields defines in config maker.stubs-view-fields
     * @param array $vars array containg data for the field
     * @return boolean
     */
    public function displayAllowedField($defaults, $vars)
    {
        $return = false;

        /*
         * Is this field allowed in the defaults?
         */
        if (in_array($vars['field'], $defaults)) {
            
            $return = true;

        }
        
        /*
         * Is this field in the show array for the yaml definition for this model?
         */
        if (in_array($this->stub, $vars['show'])) {
            
            $return = true;

        }
        
        /*
         * Is this field in the hide array for the yaml definition for this model?
         */

        if (in_array($this->stub, $vars['hide'])) {
        
            $return = false;

        }
        
        /*
         * Is this field listed in the hidden array in the model?
         */
        if ($vars['hidden'] === true) {

            $return = false;
            
        }
        
        return $return;
    }
    
   
    
    /**
     * Map Laravel field types to the FormBuilder field types
     * 
     * @param array $vars
     */
    public function map($vars) 
    {
        
        if (isset($this->map[$vars['field_type']])) {
            
            $vars['field_type'] = $this->map[$vars['field_type']];
        }

        if (isset($vars['hidden']) && $vars['hidden'] == true) {
            
            $vars['field_type'] = 'hidden';
            
        }
        
        if ($vars['field_type'] == 'textarea') {
            
            
        }
        
        if (! in_array($vars['field_type'], $this->form_field_types)) {
            
            $vars['field_type'] = 'text';
            
        }
        
        return $vars;
        
    }
    
}