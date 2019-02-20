<?php

namespace AbCreative\LaravelMaker\Parsers;

use Illuminate\Support\Facades\DB;

/**
 * Book:
 *     model:
 *       fields:
 *         name:
 *           type: string
 *           validate:
 *             - required
 *         slug:
 *           type: string
 *           modifiers:
 *             - nullable()
 *           validate:
 *             - required
 *         money:
 *           type: decimal, 8, 2
 *           modifiers:
 *             - default('20.00')
 *         content:
 *           type: text
 *         spam:
 *           type: integer
 *           fillable: false
 *           hidden: true.
 *
 *
 *
 * @author john
 */
class TableParser
{
    private $selects = ['column_name AS Field', 'column_type AS Type', 'is_nullable AS Null', 'column_key AS Key', 'column_default AS Default', 'extra AS Extra', 'data_type AS Data_Type'];

    private $database = '';

    private $table = null;

    private $fieldParser = null;

    private $table_schema = [];

    /**
     * The fields that can have options (as opposed to modifiers) passed to the Laravel methods.
     * @var array
     */
    private $fields_with_options = [
        'char',
        'decimal',
        'double',
        'enum',
        'float',
        'string',
        'unsignedDecimal',
    ];

    public function __construct()
    {
        $this->database = DB::getDatabaseName();

        $parser = 'AbCreative\\LaravelMaker\\Parsers\\'.ucfirst(config('database.default')).'FieldParser';

        $this->fieldParser = new $parser;
    }

    public function parseTable($table)
    {
        $data = $this->queryTable($table)->toArray();

        $array = $this->tableArray($table, $data);

        $this->table_schema = $array;
    }

    public function getTableSchema()
    {
        return $this->table_schema;
    }

    protected function queryTable($table)
    {
        return DB::table('information_schema.columns')
        ->where('table_schema', '=', $this->database)
        ->where('table_name', '=', $table)
        ->get($this->selects);
    }

    /**
     * Loop through the array of Field objects returned from getTable().
     *
     * @see https://dev.mysql.com/doc/refman/5.7/en/show-columns.html
     *
     * e.g.
     *
     * object(stdClass) {
     *
     *      ["Field"]       => string "slug"
     *      ["Type"]        => string "varchar(120)" or string "enum('one','two','three')" or string "timestamp" etc.
     *      ["Null"]        => string "NO" or "YES"
     *      ["Key"]         => string "" or string "PRI" etc.
     *      ["Default"]     => string "" or string "something" or NULL or "CURRENT_TIMESTAMP" etc.
     *      ["Extra"]       => string "" or string "on update CURRENT_TIMESTAMP" or string "auto_increment"
     *      ["Data_Type"]   => string  "varchar"
     *
     * }
     *
     *
     *
     * @param unknown $table
     * @param unknown $data
     */
    protected function tableArray($table, $data)
    {
        $array = [];

        if (! empty($data)) {
            foreach ($data as $item) {

                // $item is an object passed by reference but a return value makes the code easier to read...

                $item = $this->typeBuilder($item);

                $array[$item->Field] = [];

                $array[$item->Field]['type'] = $item->type;

                if (! empty($item->modifiers)) {
                    $array[$item->Field]['modifiers'] = $item->modifiers;
                }
            }
        }

        return $array;
    }

    /**
     * Our yaml representation of the field follows the following format:.
     *
     * my_field_name:
     *      type: laravel_field_type @see https://laravel.com/docs/5.5/migrations#creating-columns
     *      modifiers: @see
     *             - nullable()
     *
     *
     *
     *
     * @param unknown $item
     * @return $item;
     */
    protected function typeBuilder($item)
    {
        $item->type = $item->Data_Type;

        $item->modifiers = [];

        /*
         * Normalise the data
         */
        $extra = strtolower($item->Extra);
        $default = strtolower($item->Default);

        if ($item->null === 'YES') {
            $item->modifiers[] = 'nullable()';

            /*
             * If we are using Laravel nullbale() we don't need default('NULL')
             */
            if ($default === 'null') {
                $item->Default = null;
            }
        }

        if ($extra === 'auto_increment') {
            $item->modifiers[] = 'autoIncrement()';
        }

        if ($default === 'current_timestamp' || $default === 'current_timestamp()') {
            $item->modifiers[] = 'useCurrent()';
            $item->Default = null;
        }

        if ($extra === 'on update current_timestamp' || $extra === 'on update current_timestamp()') {
            if (! in_array('useCurrent()', $item->modifiers)) {
                $item->modifiers[] = 'useCurrent()';
                $item->Default = null;
            }
        }

        /*
         * Default values need to be enclosed in single quotes
         */
        if ($item->Default !== null) {
            $default = trim($item->Default);

            $default = str_replace_first("'", '', $default);

            $default = str_replace_last("'", '', $default);

            $default = str_replace("'", "\'", $default);

            $item->modifiers[] = "default('".$default."')";
        }

        $item->options = str_replace_first($item->type, '', $item->Type);

        if (! empty($item->options)) {
            if (strpos($item->options, 'unsigned') !== false) {
                $item->modifiers[] = 'unsigned()';

                $item->options = str_replace('unsigned', '', $item->options);
            }
        }

        $item = $this->fieldParser->setup($item)->get();

        if (in_array($item->new_type, $this->fields_with_options) && ! empty($item->options)) {
            $item->options = str_replace_first('(', '', $item->options);

            $item->options = str_replace_last(')', '', $item->options);

            if ($item->type === 'enum') {
                $item->options = '['.$item->options.']';
            }

            $item->type .= ', '.$item->options;
        }

        return $item;
    }
}
