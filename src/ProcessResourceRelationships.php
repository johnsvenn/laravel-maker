<?php

namespace AbCreative\LaravelMaker;

/**
 * Format.
 * @author john
 *
 * Build the arrays of relationship arguments used by the ModelRelationshipsBuilder
 *
 * @see https://laravel.com/docs/5.6/eloquent-relationship
 *
 * Options:
 *
 * 1. Define a simple Model e.g.
 *
 *    belongsTo:
 *      - Book
 *
 * 2. Define an array and set individual attributes e.g.
 *
 *    belongsTo:
 *      - User:
 *          method: createdBy
 *
 * Model -> methodName(s) -> foreign_key -> local_key/other_key
 */
class ProcessResourceRelationships
{
    /**
     * Get the arguments for a belongsTo relationship.
     *
     * @param array $vars
     * @return array
     */
    public function belongsTo($vars)
    {
        $arguments = [

            'method' => '',
            'model' => '',
            'foreign_key' => '',
            'other_key' => '',

        ];

        $arguments = $this->setupVars($arguments, $vars);

        if (empty($arguments['method'])) {
            $arguments['method'] = camel_case(class_basename($arguments['model']));
        }

        return $arguments;
    }

    /**
     * Get the arguments for a hasMany relationship
     * (This is the same as a hasOnne apart from method name is plural e.g. books vs book).
     *
     * @param array $vars
     * @return array
     */
    public function hasMany($vars)
    {
        $arguments = [

            'method' => '',
            'model' => '',
            'foreign_key' => '',
            'local_key' => '',

        ];

        $arguments = $this->setupVars($arguments, $vars);

        if (empty($arguments['method'])) {
            $arguments['method'] = camel_case(str_plural(class_basename($arguments['model'])));
        }

        return $arguments;
    }

    /**
     * Get the arguments for a hasOne relationship.
     *
     * @param array $vars
     * @return array
     */
    public function hasOne($vars)
    {
        $arguments = [

                'method' => '',
                'model' => '',
                'foreign_key' => '',
                'local_key' => '',

        ];

        $arguments = $this->setupVars($arguments, $vars);

        if (empty($arguments['method'])) {
            $arguments['method'] = camel_case(class_basename($arguments['model']));
        }

        return $arguments;
    }

    public function belongsToMany($vars)
    {
    }

    /**
     * Setuo the arguments that are common to relationships.
     *
     * @param array $arguments
     * @param array $vars
     * @return array
     */
    private function setupVars($arguments, $vars)
    {
        if (is_array($vars)) {
            $key = key($vars);

            $arguments['model'] = $key;

            $arguments = array_merge($arguments, $vars[$key]);
        } else {
            $arguments['model'] = $this->model($vars);
        }

        if (empty($arguments['foreign_key'])) {
            $arguments['foreign_key'] = strtolower(snake_case(class_basename($arguments['model']))).'_id';
        }

        return $arguments;
    }

    /**
     * Return a string for model and correct namespace.
     *
     * @param string $model
     * @return string
     */
    private function model($model)
    {
        $new_model = '';

        if (starts_with($model, 'App\\')) {

            // use as is

            $new_model = $model;
        } else {
            $new_model = 'App\\Models\\'.$model;
        }

        return $new_model;
    }

    /**
     * Getter for model method.
     *
     * @param string $model
     * @return string
     */
    public function getModel($model)
    {
        return $this->model($model);
    }
}
