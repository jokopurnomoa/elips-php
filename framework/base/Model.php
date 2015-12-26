<?php
/**
 * MODEL
 *
 */

class Model {

    private static $table;
    private static $field_id;

    public static function setTable($table){
        self::$table = $table;
    }

    public static function setFieldId($fieldname){
        self::$field_id = $fieldname;
    }

    public static function getAll($limit = null){
        return Database::getAll(self::$table, null, null, $limit);
    }

    public static function getById($id){
        return Database::getFirst(self::$table, array(self::$field_id => $id));
    }

    public static function getCount(){
        return Database::getCount(self::$table);
    }

    public static function insert($data){
        return Database::insert(self::$table, $data);
    }

    public static function updateById($id, $data){
        return Database::update(self::$table, self::$field_id, $id, $data);
    }

    public static function deleteById($id){
        return Database::delete(self::$table, self::$field_id, $id);
    }

    public static function deleteAllById($id){
        return Database::deleteAll(self::$table, self::$field_id, $id);
    }

    public static function escape($string){
        return Database::escape($string);
    }

}
