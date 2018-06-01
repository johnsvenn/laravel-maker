<?php

namespace AbCreative\LaravelMaker;

use AbCreative\LaravelMaker\ResourceValidator;
use AbCreative\LaravelMaker\ProcessResourceRelationships;
/**
 * Format 
 * @author john
 *
 */
class Resource {
    
   
    public $name = '';

    
    /**
     * The placeholders that are used in the stubs
     *
     * __MODEL.TABLE__ e.g. posts
     * __MODEL.NAME__ The name of the model class e.g. Post
     * __RESOURCE.VIEW.DIRECTORY__ e.g. posts
     * __RESOURCE.VIEW.NAMESPACE__ e.g. admin
     * __CONTROLLER.NAMESPACE__ e.g. Admin
     * __ROUTE.SLUG__ The route in dot format e.g. posts
     * __ROUTE.PREFIX__ The route prefix defined as 'as' in the route group variable e.g. admin
     * __MODEL.NAME.HUMAN__ The singular friendly name of the model e.g. post
     * __MODEL.NAME.HUMAN.PLURAL__ The plural friendly name of the model e.g. posts
     * __MODEL.VAR.NAME__ The singular variable name of the model typically representing a single result in a collection e.g. post
     * __MODEL.VAR.NAME.PLURAL__ The plural variable name of the model typically representing a collection e.g. posts
     * __CONTROLLER.NAME__ e.g. Post
     *
     * @var array
     */
    public $placeholders = [
        'model_table' => '',
        'model_name' => '',
        'resource_view_directory' => '',
        'resource_view_namespace' => '', //admin
        'controller_namespace' => 'Admin', //Admin
        'route_slug' => '',
        'route_prefix' => '', //admin
        'model_name_human' => '',
        'model_name_human_plural' => '',
        'model_var_name' => '',
        'model_var_name_plural' => '',
        'model_fillable' => '',
        'model_hidden' => '',
        'model_guarded' => '',
        'model_dates' => '',
        'model_rules' => '',
        'model_messages' => '',
        'controller_name' => '',
        
    ];
    
    public $fields = [];
    
    public $fillable = [];
    
    public $guarded = [];
    
    /**
     * Array containing the relationships on this model
     * @var array
     */
    public $relationships = [];

    public $required = [];
    
    public $rules = [];
    
    public $messages = [];
    
    public $dates = [
        'created_at',
        'updated_at'
    ];
    
    public $hidden = [];
    
    /**
     * The fields that are added by the migration automatically
     * @var array
     */
    public $auto_generated_fields = [
        'id',
        'created_at',
        'updated_at'
    
    ];
    
    private $laravel_date_field_types = [
        'date'
    ];
    
    private $resource_validator;
    
    private $process_resource_relationships; 
    
    /**
     * 
     * @param ResourceValidator $resource_validator
     * @param string $name
     * @param array $var
     */
    public function __construct(ResourceValidator $resource_validator, $name, $var) {
        
        $this->resource_validator = $resource_validator;
        
        $this->process_resource_relationships = new ProcessResourceRelationships();
        
        $this->name = $name;
        
        $this->placeholders['model_name'] = ucfirst($name);
        
        $this->setupPlaceholders($var);
        
        /*
         * Process the fields array for each model definition
         */
        $this->modelFields($var);
        
        $this->modelRelationships($var);
        
        $this->modelRelationshipFields($var);
        
        $this->complete();
        



    }
    
    /**
     * Return a variable in the format we are using in stubs
     * 
     * e.g. resource_view_directory -> __RESOURCE.VIEW.DIRECTORY__
     * 
     * @param unknown $string
     * @return string
     */
    public function placeholder($string) {
        
        $str = '__' . str_replace('_', '.', strtoupper($string)) . '__';
        
        return $str;
    }
    
   
    
    /**
     * Ensure that any variables that have not been explicitly set are set following conventions
     * 
     * @param unknown $name
     */
    private function complete() 
    {

        $name = $this->placeholders['model_name'];
        
        $this->ifEmptyPlaceholder('controller_name', ucfirst($name));

        $this->ifEmptyPlaceholder('model_table', str_plural(strtolower($name)));
        
        $this->ifEmptyPlaceholder('resource_view_directory', str_plural(strtolower($name)));
        
        $this->ifEmptyPlaceholder('resource_view_namespace', strtolower($this->placeholders['controller_namespace']));
        
        $this->ifEmptyPlaceholder('route_slug', str_plural(strtolower($name)));
        
        $this->ifEmptyPlaceholder('route_prefix', strtolower($this->placeholders['controller_namespace']));
        
        $this->ifEmptyPlaceholder('model_name_human', strtolower($this->friendlyCase($name)));
        
        $this->ifEmptyPlaceholder('model_name_human_plural', ucfirst(strtolower($this->friendlyCasePlural($name))));
        
        $this->ifEmptyPlaceholder('model_var_name', strtolower($name));
        
        $this->ifEmptyPlaceholder('model_var_name_plural', str_plural(strtolower($name)));
        
        

    }
    
    private function ifEmptyPlaceholder($key, $value)
    {
        if (empty($this->placeholders[$key])) {
            
            $this->placeholders[$key] = $value;
            
        } 
    }
    
    /**
     * Process the Resource array
     * Populate the defined placeholders if the keys match
     * 
     * $type = model, controller, view etc.
     * 
     * @param array $data
     */
    private function setupPlaceholders($data)
    {
       // dd($data);
        foreach ($data as $type => $vars) {
        
            if (is_array($vars)) {
                
                foreach ($vars as $key => $value) {
    
                    if (isset($this->placeholders[$type . '_' . $key])) {
                         
                        $this->placeholders[$type . '_' . $key] = $value;
                         
                    }
                    
                }
                
            } else {
                
                $this->placeholders[$type] = $vars;
                
            }
            
        }
        
        
    }
    
    
    
    
    
    private function modelFields($data)
    {

        foreach ($data['model']['fields'] as $key => $vars) {
            
            
            $is_nullable = false;
            
            /*
             * Unless fillable is specifically set to be false it is true (we assume everything is fillable)
             */
            $is_fillable = true;
            
            
            $this->fields[$key] = [
                'field' => $key,
                'label' => ucfirst(str_replace(['-', '_'], ' ', $key)),
                'placeholder' => ucfirst(str_replace(['-', '_'], ' ', $key)),
                'type' => 'string',
                'field_type' => null, // an override of the html field type rendered 
                'options' => '[]',
                'params' => null,
                'column' => 'string()',
                'modifiers' => null,
                'rules' => null,
                'rules_update' => null,
                'messages' => null,
                'unique_with' => null,
                'hidden' => false, // model hidden
                'fillable' => true, // model fillable
                'guarded' => false, // model guarded
                'show' => [], // which views to show the field on
                'hide' => [] // which views to hide the field on
            ];

            
            if (isset($vars['type'])) {
                
                $params = '';
                
                $type = explode(',', $vars['type'], 2);

                $field = trim($type[0]);
                    
                $this->fields[$key]['type'] = $field;
                
                $this->fields[$key]['field_type'] = $field;
                
                if (in_array($field, $this->laravel_date_field_types)) {
                    
                    $this->dates[] = $field;
                    
                }
                
                if (isset($type[1])) {
                
                    $params = trim($type[1]);

                    $this->fields[$key]['column'] = $field . '(' . $params . ')';
                    
                    if ($field === 'enum') {
                        
                        $this->fields[$key]['params'] = $params;
                        
                    } else {
                        
                        $this->fields[$key]['params'] = explode(',', $params);
                        
                    }
                    
                    
                    
                } else {
                    
                    $this->fields[$key]['column'] = $field;
                }
                
                

            }
            
            if (isset($vars['field_type']) && !empty($vars['field_type'])) {
            
                $this->fields[$key]['field_type'] = $vars['field_type'];
            
            }
            
            if (isset($vars['label']) && !empty($vars['label'])) {
            
                $this->fields[$key]['label'] = $vars['label'];
            
            }
            
            if (isset($vars['placeholder']) && !empty($vars['placeholder'])) {
            
                $this->fields[$key]['placeholder'] = $vars['placeholder'];
            
            }
            
            if (isset($vars['messages']) && !empty($vars['messages'])) {
            
                $this->fields[$key]['messages'] = $vars['messages'];
            
            }
            
            if (isset($vars['show']) && is_array($vars['show'])) {
                
                $this->fields[$key]['show'] = $vars['show'];
                
            }
            
            if (isset($vars['hide']) && is_array($vars['hide'])) {
            
                $this->fields[$key]['hide'] = $vars['hide'];
            
            }
            
            if (isset($vars['modifiers']) && !empty($vars['modifiers'])) {

                if (! is_array($vars['modifiers'])) {
                    
                    // when would this happen?
                
                    $vars['modifiers'] = explode('->', $vars['modifiers']);
                
                }
               
                foreach ($vars['modifiers'] as $modifier) {
                
                    $this->fields[$key]['modifiers'][] = $modifier;
                    
                    if ($modifier === 'nullable()') {
                        
                        $is_nullable = true;
                        
                    }
                    
                    if (starts_with($modifier, 'default(')) {
                        
                        // do we need to do anything here?
                        
                    }
                
                }

                
            }
            
            

            if (isset($vars['messages']) && !empty($vars['messages'])) {

                $this->fields[$key]['messages'] = $vars['messages'];
                
                $this->messages[] = $key . ".required' => '" . $vars['messages'];
            
            }

            /*
             * Multi column unique validation
             * @see https://github.com/felixkiss/uniquewith-validator
             */
            if (isset($vars['unique_with']) && !empty($vars['unique_with'])) {

                $this->fields[$key]['unique_with'] = $vars['unique_with'];

                $this->messages[] = $key . ".unique_with' => '" . $vars['unique_with'];

            }

            if (isset($vars['fillable']) && $vars['fillable'] === false) {

                $is_fillable = false;
                $this->fields[$key]['fillable'] = false;
                $this->fields[$key]['guarded'] = true;

            } else {
                
                $this->fillable[] = $key;
                
                
            }
            
            if (isset($vars['guarded']) && $vars['guarded'] === true) {
            
                $is_fillable = false;
                $this->fields[$key]['fillable'] = false;
                $this->fields[$key]['guarded'] = true;
            
            } else {
            
                $this->fillable[] = $key;

            }
            
            
            
            if (isset($vars['hidden']) && $vars['hidden'] === true) {
            
                $this->hidden[] = $key;
                $this->fields[$key]['hidden'] = true;
            
            }
            
            if ($is_nullable === false && $is_fillable === true) {
                
                $is_required = false;
                
                if (isset($vars['rules']) && strpos($vars['rules'], 'required') !== false) {
                    
                    $is_required = true;
                    
                } 
                
                if ($is_required === false) {
                    
                    if (isset($vars['rules'])) {
                        
                        $vars['rules'] .= '|required';
                        
                    } else {
                        
                        $vars['rules'] = 'required';
                        
                    }
                    
                }
  
            }
            

            if (isset($vars['rules']) && !empty($vars['rules']) ) {

                if (isset($vars['guarded']) && $vars['guarded'] === true) {
                    
                    continue;
                    
                } else {
                    
                    $this->fields[$key]['rules'] = $vars['rules'];

                    $this->rules[] = $key . "' => '" . $vars['rules'];
                    
                }
            
            }
            
            $this->filter_fields[] = $key . "' => '" . $this->fields[$key]['label'];
 
            
        }
        
        
        $this->setDefaultFields();
       
        
        $this->resource_validator->validateFields($this->fields);
        
        if (!empty($this->fillable)) {
            
            $this->fillable = array_values(array_unique($this->fillable));
            
        }
        
        if (!empty($this->guarded)) {
        
            $this->guarded = array_values(array_unique($this->guarded));
        
        }
        
        if (!empty($this->hidden)) {
        
            $this->hidden = array_values(array_unique($this->hidden));
        
        }
        
        $this->makeUpdateRules();
        
       
        $this->placeholders['model_hidden'] = $this->prettyImplode($this->hidden);
        $this->placeholders['model_fillable'] = $this->prettyImplode($this->fillable);
        $this->placeholders['model_guarded'] = $this->prettyImplode($this->guarded);
        $this->placeholders['model_dates'] = $this->prettyImplode($this->dates);
        $this->placeholders['model_rules'] = $this->prettyImplode($this->rules, $tabs = "\t\t\t");
        $this->placeholders['model_rules_update'] = $this->prettyImplode($this->rules_update, $tabs = "\t\t\t");
        $this->placeholders['model_messages'] = $this->prettyImplode($this->messages, $tabs = "\t\t\t");
        $this->placeholders['model_filter_fields'] = $this->prettyImplode($this->filter_fields, $tabs = "\t\t");
        
    }
    
    /**
     * If a unique rule is set on a Model then we need to make sure that it ignores the Model id on update
     * @see 
     */
    private function makeUpdateRules()
    {
        $rules = $this->rules;
        $rules_update = [];
        
        if (!empty($rules)) {
            
            /*
             * Each model can have many rules
             */
            foreach ($rules as $rule) {

                $var = explode('|', $rule);
 
                $fragments = [];
                
                /*
                 * The unique param may come in any position in a rule
                 */
                foreach ($var as $v) {
                    
                    if (mb_strpos($v, 'unique:') !== false) {
     
                        $v .= ",id,' . \$this->" . $this->placeholders['model_name'] . " .'";
   
                    }
                    
                    $fragments[] = $v;
                    
                }
                
                $str = implode('|', $fragments);
               
                $rules_update[] = $str;
 
            }
        }

        $this->rules_update = $rules_update;
        
    }
    
    /**
     * Extract any relationships and pass them on to the parser to build them
     * 
     * @param array $data
     */
    private function modelRelationships($data)
    {

        if (!empty($data['model']['relationships'])) {
            
            foreach ($data['model']['relationships'] as $key => $vars) {

                if (!empty($vars)) {
                
                    foreach ($vars as $relationship_vars) {

                        $this->relationships[$key][] = $this->process_resource_relationships->$key($relationship_vars);
                
                    }
                }  
            }   
        }  
    }
    
   
    
    /**
     * Extract any relationships and pass them on to the parser to build them
     * 
     * 
     *
     * @param  array  $vars  The original vars for the field from the YAML
     */
    private function modelRelationshipFields($vars)
    {
    
        if (isset($this->relationships['belongsTo'])) {

            foreach ($this->relationships['belongsTo'] as $var) {
                
                $this->fields[$var['foreign_key']]['options'] = '$' . $var['method'];
                $this->fields[$var['foreign_key']]['field_type'] = 'select';
                
                if (!isset($vars['model']['fields'][$var['foreign_key']]['placeholder'])) {
                    
                    $this->fields[$var['foreign_key']]['placeholder'] = 'Please select...';
                    
                }                
            }
        }  
    }
    
    
    /**
     * Add id, created_at, updated_at
     */
    protected function setDefaultFields()
    {

        $id = [
                'id' => [
                'field' => 'id',
                'label' => 'ID',
                'placeholder' => 'ID',
                'type' => 'unsignedInteger',
                'field_type' => 'unsignedInteger',
                'params' => null,
                'column' => 'unsignedInteger',
                'modifiers' => [
    
                    0 => 'autoIncrement()',
                    1 => 'unsigned()'
                    
                ],
                'rules' => null,
                'messages' => null,
                'hidden' => false,
                'fillable' => false, // model fillable
                'guarded' => true,
                'show' => [], // which views to show the field on
                'hide' => [] // which views to hide the field on
                
            ]  
        ];

        if (!isset($this->fields['id'])) {
        
            $this->fields = $id + $this->fields;
        
        }
        
        if (!isset($this->fields['created_at'])) {
        
            $this->fields['created_at'] = [
            
                'field' => 'created_at',
                'label' => 'Created at',
                'placeholder' => 'Created at',
                'type' => 'timestamp',
                'field_type' => 'timestamp',
                'params' => null,
                'column' => 'timestamp',
                'modifiers' => [
    
                    0 => 'nullable()'
                    
                ],
                'rules' => null,
                'messages' => null,
                'hidden' => false,
                'fillable' => false, // model fillable
                'guarded' => true,
                'show' => [], // which views to show the field on
                'hide' => [] // which views to hide the field on
            ];
            
        }
        
        if (!isset($this->fields['updated_at'])) {
            
            $this->fields['updated_at'] = [
                
                'field' => 'updated_at',
                'label' => 'Updated at',
                'placeholder' => 'Updated at',
                'type' => 'timestamp',
                'field_type' => 'timestamp',
                'params' => null,
                'column' => 'timestamp',
                'modifiers' => [
                
                    0 => 'nullable()'
                    
                ],
                'rules' => null,
                'messages' => null,
                'hidden' => false,
                'fillable' => false, // model fillable
                'guarded' => true,
                'show' => [], // which views to show the field on
                'hide' => [] // which views to hide the field on
    
            ];
        
        }
        
    }
    
    
    private function prettyImplode($array = array(), $tabs = "\t\t") 
    {
        $str = '';
        
        if (!empty($array)) {
            
            $str = "'" . implode("'," . PHP_EOL . $tabs . "'", $array) . "'";
            
        }
        
        
        return $str;
    }
    
    /**
     * Return $this->fields but filtered to only show the fields that can appear on a form 
     * 
     * Exclude:
     *  - id
     *  - created_at
     *  - updated_at
     *  
     * @return array
     */
    public function editableFields()
    {

        $fields = [];
        
        foreach ($this->fields as $key => $vars) {
        
            if (in_array($key, $this->auto_generated_fields)) {
                
                continue;
                
            }
        
            if ($vars['fillable'] === false || $vars['guarded'] === true) {
        
                continue;
        
            }
            
            $fields[$key] = $vars;
        
        }
        
        return $fields;
    }
    
    /**
     * Return $this->fields but filtered to exclude the autogenerated fields that would break a migration
     *
     * Exclude:
     *  - id
     *  - created_at
     *  - updated_at
     *
     * @return array
     */
    public function migratableFields()
    {
        
        $fields = $this->fields;
        
        foreach ($this->auto_generated_fields as $var) {
        
            if (isset($fields[$var])) {
        
                unset($fields[$var]);
        
            }
        }
        
        return $fields;
       
    }
    
    /**
     * Convert a StudlyCaseString into a friendly string e.g. Studly Case String
     * 
     * @param string $string
     * @return string
     */
    protected function friendlyCase($string) 
    {
        return preg_replace('/(?<!^)([A-Z])/', ' \\1', $string);
    }
    
    /**
     * Convert a StudlyCaseString into a plural friendly string e.g. Studly Case Strings
     *
     * @param string $string
     * @return string
     */
    protected function friendlyCasePlural($string)
    {
        $string = preg_replace('/(?<!^)([A-Z])/', ' \\1', $string);
        
        $string = explode(' ', $string);
        
        $end = array_pop($string);
        
        $end = str_plural($end);
        
        array_push($string, $end);
        
        $string = implode(' ', $string);
        
        return $string;
    }
    
    
    
    
    
}