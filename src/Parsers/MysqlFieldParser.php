<?php

namespace AbCreative\LaravelMaker\Parsers;

/**
 * Parse the SQL field names into thier laravel migration equivalents e.g. varchar -> string.
 *
 * @see https://laravel.com/docs/5.5/migrations#columns
 *
 * https://laravel.com/docs/5.5/migrations#columns
 *
 * Command Description
 * -------------------
 * $table->bigIncrements('id');	Auto-incrementing UNSIGNED BIGINT (primary key) equivalent column.
 * $table->bigInteger('votes');	BIGINT equivalent column.
 * $table->binary('data');	BLOB equivalent column.
 * $table->boolean('confirmed');
 * $table->char('name', 100)
 * $table->date('created_at')
 * $table->dateTime('created_at'); DATETIME equivalent column.
 * $table->dateTimeTz('created_at');   DATETIME (with timezone) equivalent column.
 * $table->decimal('amount', 8, 2);    DECIMAL equivalent column with a precision (total digits) and scale (decimal digits).
 * $table->double('amount', 8, 2); DOUBLE equivalent column with a precision (total digits) and scale (decimal digits).
 * $table->enum('level', ['easy', 'hard']);    ENUM equivalent column.
 * $table->float('amount', 8, 2);  FLOAT equivalent column with a precision (total digits) and scale (decimal digits).
 * $table->geometry('positions');  GEOMETRY equivalent column.
 * $table->geometryCollection('positions');    GEOMETRYCOLLECTION equivalent column.
 * $table->increments('id');   Auto-incrementing UNSIGNED INTEGER (primary key) equivalent column.
 * $table->integer('votes');   INTEGER equivalent column.
 * $table->ipAddress('visitor');   IP address equivalent column.
 * $table->json('options');    JSON equivalent column.
 * $table->jsonb('options');   JSONB equivalent column.
 * $table->lineString('positions');    LINESTRING equivalent column.
 * $table->longText('description');    LONGTEXT equivalent column.
 * $table->macAddress('device');   MAC address equivalent column.
 * $table->mediumIncrements('id'); Auto-incrementing UNSIGNED MEDIUMINT (primary key) equivalent colum.
 * $table->mediumInteger('votes'); MEDIUMINT equivalent column.
 * $table->mediumText('description');  MEDIUMTEXT equivalent column.
 * $table->morphs('taggable'); Adds taggable_id UNSIGNED INTEGER and  taggable_type VARCHAR equivalent columns.
 * $table->multiLineString('positions');   MULTILINESTRING equivalent column.
 * $table->multiPoint('positions');    MULTIPOINT equivalent column.
 * $table->multiPolygon('positions');  MULTIPOLYGON equivalent column.
 * $table->nullableMorphs('taggable'); Adds nullable versions of morphs() columns.
 * $table->nullableTimestamps();   Adds nullable versions of timestamps() columns.
 * $table->point('position');  POINT equivalent column.
 * $table->polygon('positions');   POLYGON equivalent column.
 * $table->rememberToken();    Adds a nullable remember_token VARCHAR(100) equivalent column.
 * $table->smallIncrements('id');  Auto-incrementing UNSIGNED SMALLINT (primary key) equivalent column.
 * $table->smallInteger('votes');  SMALLINT equivalent column.
 * $table->softDeletes();  Adds a nullable deleted_at TIMESTAMP equivalent column for soft deletes.
 * $table->softDeletesTz();    Adds a nullable deleted_at TIMESTAMP (with timezone) equivalent column for soft deletes.
 * $table->string('name', 100);    VARCHAR equivalent column with a optional length.
 * $table->text('description');    TEXT equivalent column.
 * $table->time('sunrise');    TIME equivalent column.
 * $table->timeTz('sunrise');  TIME (with timezone) equivalent column.
 * $table->timestamp('added_on');  TIMESTAMP equivalent column.
 * $table->timestampTz('added_on');    TIMESTAMP (with timezone) equivalent column.
 * $table->timestamps();   Adds nullable created_at and updated_at TIMESTAMP equivalent columns.
 * $table->timestampsTz(); Adds nullable created_at and updated_at TIMESTAMP (with timezone) equivalent columns.
 * $table->tinyIncrements('id');   Auto-incrementing UNSIGNED TINYINT (primary key) equivalent column.
 * $table->tinyInteger('votes');   TINYINT equivalent column.
 * $table->unsignedBigInteger('votes');    UNSIGNED BIGINT equivalent column.
 * $table->unsignedDecimal('amount', 8, 2);    UNSIGNED DECIMAL equivalent column with a precision (total digits) and scale (decimal digits).
 * $table->unsignedInteger('votes');   UNSIGNED INTEGER equivalent column.
 * $table->unsignedMediumInteger('votes'); UNSIGNED MEDIUMINT equivalent column.
 * $table->unsignedSmallInteger('votes');  UNSIGNED SMALLINT equivalent column.
 * $table->unsignedTinyInteger('votes');   UNSIGNED TINYINT equivalent column.
 * $table->uuid('id'); UUID equivalent column.
 *
 *
 *
 *
 * @see https://dev.mysql.com/doc/refman/5.7/en/data-types.html
 *
 * This is eseentailly the reverse of the Illuminate\Database\Schema\Grammars
 */
class MysqlFieldParser
{
    private $item = null;

    public function __construct()
    {
        return $this;
    }

    public function setup($item)
    {
        $type = $item->Data_Type.'Type';

        $item = $this->$type($item);

        $item->type = str_replace_first($item->Data_Type, $item->new_type, $item->type);

        $this->item = $item;

        return $this;
    }

    public function get()
    {
        $item = $this->item;

        $this->item = null;

        return $item;
    }

    /**
     * @param unknown $method
     * @param unknown $args
     */
    public function __call($method, $args)
    {
        if (! empty($args)) {
            foreach ($args as $arg) {
                if (isset($arg->Type)) {
                    $arg->new_type = false;

                    return $arg;
                }
            }
        }
    }

    /**
     * @param stdClass $item
     * @return stdClass
     */
    private function varcharType($item)
    {
        $item->new_type = 'string';

        return $item;
    }

    /**
     * @param stdClass $item
     * @return stdClass
     */
    private function boolType($item)
    {
        return $this->tinyintType($item);
    }

    /**
     * @param stdClass $item
     * @return stdClass
     */
    private function booleanType($item)
    {
        return $this->tinyintType($item);
    }

    /**
     * @param stdClass $item
     * @return stdClass
     */
    private function tinyintType($item)
    {
        if (in_array('unsigned()', $item->modifiers)) {
            $item->new_type = 'unsignedTinyInteger';
        } else {
            $item->new_type = 'tinyInteger';
        }

        return $item;
    }

    /**
     * @param stdClass $item
     * @return stdClass
     */
    private function smallintType($item)
    {
        if (in_array('unsigned()', $item->modifiers)) {
            $item->new_type = 'unsignedSmallInteger';
        } else {
            $item->new_type = 'smallInteger';
        }

        return $item;
    }

    /**
     * @param stdClass $item
     * @return stdClass
     */
    private function mediumintType($item)
    {
        if (in_array('unsigned()', $item->modifiers)) {
            $item->new_type = 'unsignedMediumInteger';
        } else {
            $item->new_type = 'mediumInteger';
        }

        return $item;
    }

    /**
     * @param stdClass $item
     * @return stdClass
     */
    private function integerType($item)
    {
        return $this->intType($item);
    }

    /**
     * @param stdClass $item
     * @return stdClass
     */
    private function intType($item)
    {
        if (in_array('unsigned()', $item->modifiers)) {
            $item->new_type = 'unsignedInteger';
        } else {
            $item->new_type = 'integer';
        }

        return $item;
    }

    /**
     * @param stdClass $item
     * @return stdClass
     */
    private function bigintType($item)
    {
        if (in_array('unsigned()', $item->modifiers)) {
            $item->new_type = 'unsignedBigInteger';
        } else {
            $item->new_type = 'bigInteger';
        }

        return $item;
    }

    /**
     * @param stdClass $item
     * @return stdClass
     */
    private function serialType($item)
    {
        $item->new_type = 'unsignedBigInteger';

        if (! in_array('auto_increment', $item->modifiers)) {
            $item->modifiers[] = 'auto_increment';
            $item->modifiers[] = 'unique()';
        }

        return $item;
    }

    /**
     * @param stdClass $item
     * @return stdClass
     */
    private function decimalType($item)
    {
        if (in_array('unsigned()', $item->modifiers)) {
            $item->new_type = 'unsignedDecimal';
        } else {
            $item->new_type = 'decimal';
        }

        return $item;
    }

    /**
     * @param stdClass $item
     * @return stdClass
     */
    private function decType($item)
    {
        return $this->decimalType($item);
    }

    /**
     * @param stdClass $item
     * @return stdClass
     */
    private function numericType($item)
    {
        return $this->decimalType($item);
    }

    /**
     * @param stdClass $item
     * @return stdClass
     */
    private function fixedType($item)
    {
        return $this->decimalType($item);
    }

    /**
     * laravel has no unsignedFloat().
     *
     * @param stdClass $item
     * @return stdClass
     */
    private function floatType($item)
    {
        if (in_array('unsigned()', $item->modifiers)) {
            $item->new_type = false;
        } else {
            $item->new_type = 'float';
        }

        return $item;
    }

    /**
     * laravel has no unsignedDouble().
     *
     * @param stdClass $item
     * @return stdClass
     */
    private function doubleType($item)
    {
        if (in_array('unsigned()', $item->modifiers)) {
            $item->new_type = false;
        } else {
            $item->new_type = 'double';
        }

        return $item;
    }

    /**
     * @param stdClass $item
     * @return stdClass
     */
    private function dateType($item)
    {
        $item->new_type = 'date';

        return $item;
    }

    /**
     * @param stdClass $item
     * @return stdClass
     */
    private function datetimeType($item)
    {
        $item->new_type = 'dateTime';

        return $item;
    }

    /**
     * @param stdClass $item
     * @return stdClass
     */
    private function timeType($item)
    {
        $item->new_type = 'time';

        return $item;
    }

    /**
     * @param stdClass $item
     * @return stdClass
     */
    private function timestampType($item)
    {
        $item->new_type = 'timestamp';

        return $item;
    }

    /**
     * @param stdClass $item
     * @return stdClass
     */
    private function yearType($item)
    {
        $item->new_type = false;

        return $item;
    }

    /**
     * @param stdClass $item
     * @return stdClass
     */
    private function charType($item)
    {
        $item->new_type = 'char';

        return $item;
    }

    /**
     * @param stdClass $item
     * @return stdClass
     */
    private function binaryType($item)
    {
        $item->new_type = 'binary';

        return $item;
    }

    /**
     * @param stdClass $item
     * @return stdClass
     */
    private function blobType($item)
    {
        $item->new_type = 'binary';

        return $item;
    }

    /**
     * @param stdClass $item
     * @return stdClass
     */
    private function textType($item)
    {
        $item->new_type = 'text';

        return $item;
    }

    /**
     * @param stdClass $item
     * @return stdClass
     */
    private function longtextType($item)
    {
        $item->new_type = 'longText';

        return $item;
    }

    /**
     * @param stdClass $item
     * @return stdClass
     */
    private function mediumtextType($item)
    {
        $item->new_type = 'mediumText';

        return $item;
    }

    /**
     * @param stdClass $item
     * @return stdClass
     */
    private function enumType($item)
    {
        $item->new_type = 'enum';

        return $item;
    }

    /**
     * @param stdClass $item
     * @return stdClass
     */
    private function setType($item)
    {
        $item->new_type = false;

        return $item;
    }

    /**
     * @param stdClass $item
     * @return stdClass
     */
    private function geometryType($item)
    {
        $item->new_type = 'geometry';

        return $item;
    }

    /**
     * @param stdClass $item
     * @return stdClass
     */
    private function pointType($item)
    {
        $item->new_type = 'point';

        return $item;
    }

    /**
     * @param stdClass $item
     * @return stdClass
     */
    private function linestringType($item)
    {
        $item->new_type = 'lineString';

        return $item;
    }

    /**
     * @param stdClass $item
     * @return stdClass
     */
    private function polygonType($item)
    {
        $item->new_type = 'polygon';

        return $item;
    }

    /**
     * @param stdClass $item
     * @return stdClass
     */
    private function geometrycollectionType($item)
    {
        $item->new_type = 'geometryCollection';

        return $item;
    }

    /**
     * @param stdClass $item
     * @return stdClass
     */
    private function multipointType($item)
    {
        $item->new_type = 'multiPoint';

        return $item;
    }

    /**
     * @param stdClass $item
     * @return stdClass
     */
    private function multilinestringType($item)
    {
        $item->new_type = 'multiLineString';

        return $item;
    }

    /**
     * @param stdClass $item
     * @return stdClass
     */
    private function multipolygonType($item)
    {
        $item->new_type = 'multiPolygon';

        return $item;
    }

    /**
     * @param stdClass $item
     * @return stdClass
     */
    private function jsonType($item)
    {
        $item->new_type = 'json';

        return $item;
    }
}
