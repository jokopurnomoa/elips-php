<?php
/**
 * Base Model
 *
 *
 */

class Model {

    protected static $instance;
    protected $table;
    protected $primary;

    private function __construct(){
    }

    private function __clone(){
    }

    private function __wakeup(){
    }

    /**
     * Get model instance
     *
     * @return mixed
     */
    public static function getInstance(){
        if(null === static::$instance){
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * Get all data from table
     *
     * @param null|string $columns
     * @param null|int $limit
     * @return mixed
     */
    public static function all($columns = null, $limit = null){
        $instance = static::getInstance();

        if($limit != null){
            return DB::table($instance->table)
                ->select($columns)
                ->limit($limit)
                ->get();
        }
        return DB::table($instance->table)
            ->select($columns)
            ->get();
    }

    /**
     * Get database instance
     *
     * @param string $field
     * @param string $valueOrCondition
     * @param null|string $value
     * @return mixed
     */
    public static function where($field, $valueOrCondition, $value = null){
        $instance = static::getInstance();
        return DB::table($instance->table)->where($field, $valueOrCondition, $value);
    }

    /**
     * Get first data from table
     *
     * @param null $columns
     * @return mixed
     */
    public static function first($columns = null){
        $instance = static::getInstance();
        return DB::table($instance->table)
            ->select($columns)
            ->first();
    }

    /**
     * Get first data from table by id
     *
     * @param $id
     * @return mixed
     */
    public static function byId($id){
        $instance = static::getInstance();
        return DB::table($instance->table)
            ->where($instance->primary, $id)
            ->first();
    }

}
