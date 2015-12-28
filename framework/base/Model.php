<?php
/**
 * Base Model
 *
 *
 */

class Model {

    private static $table;
    private static $field_id;

    /**
     * Set Default Table
     *
     * @param $table
     */
    public static function setTable($table){
        self::$table = $table;
    }

    /**
     * Set Default Field Id
     *
     * @param $fieldname
     */
    public static function setFieldId($fieldname){
        self::$field_id = $fieldname;
    }

    /**
     * Get All Data
     *
     * @param null $limit
     * @return mixed
     */
    public static function getAll($limit = null){
        return Database::getAll(self::$table, null, null, $limit);
    }

    /**
     * Get Data By Id
     *
     * @param $id
     * @return mixed
     */
    public static function getById($id){
        return Database::getFirst(self::$table, array(self::$field_id => $id));
    }

    /**
     * Get Count Data
     *
     * @return mixed
     */
    public static function getCount(){
        return Database::getCount(self::$table);
    }

    /**
     * Insert Data
     *
     * @param $data
     * @return mixed
     */
    public static function insert($data){
        return Database::insert(self::$table, $data);
    }

    /**
     * Update Data By Id
     *
     * @param $id
     * @param $data
     * @return mixed
     */
    public static function updateById($id, $data){
        return Database::update(self::$table, self::$field_id, $id, $data);
    }

    /**
     * Delete Data By Id
     *
     * @param $id
     * @return mixed
     */
    public static function deleteById($id){
        return Database::delete(self::$table, self::$field_id, $id);
    }

    /**
     * Eascaping String
     *
     * @param $string
     * @return mixed
     */
    public static function escape($string){
        return Database::escape($string);
    }

}
