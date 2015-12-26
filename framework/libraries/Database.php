<?php
/**
 * Database
 *
 * Control database driver
 *
 */

class Database {

    private static $db_driver;

    public static function init(){
        $config = null;
        if(file_exists(APP_PATH . 'config/database.php')){
            require APP_PATH . 'config/database.php';
        } elseif(APP_ENV === 'development') {
            error_dump('File \'' . APP_PATH . 'config/database.php\' not found!');die();
        }

        if($config['db']['main']['driver'] == 'mysqli'){
            require 'DBDriver/MySQLi.php';
            self::$db_driver = new MySQLiDriver($config['db']['main']);
            self::$db_driver->connect();
        } elseif(APP_ENV === 'development') {
            error_dump('Database Driver \'' . $config['db']['main']['driver'] . '\' not avaiable.');die();
        }
    }

    public static function destruct(){
        if(self::$db_driver != null){
            self::$db_driver->disconnect();
        }
    }

    public static function escape($string){
        return self::$db_driver->escape($string);
    }

    public static function getCountQuery($sql){
        return self::$db_driver->getCountQuery($sql);
    }

    public static function getCount($table, $where = null, $order = null, $limit = null){
        return self::$db_driver->getCount($table, $where, $order, $limit);
    }

    public static function getAllQuery($sql){
        return self::$db_driver->getAllQuery($sql);
    }

    public static function getAll($table, $where = null, $order = null, $limit = null){
        return self::$db_driver->getAll($table, $where, $order, $limit);
    }

    public static function getAllField($table, $field = null, $where = null, $order = null, $limit = null){
        return self::$db_driver->getAllField($table, $field, $where, $order, $limit);
    }

    public static function getFirstQuery($sql){
        return self::$db_driver->getFirstQuery($sql);
    }

    public static function getFirst($table, $where = null, $order = null, $limit = null){
        return self::$db_driver->getFirst($table, $where, $order, $limit);
    }

    public static function getFirstField($table, $field = null, $where = null, $order = null, $limit = null){
        return self::$db_driver->getFirstField($table, $field, $where, $order, $limit);
    }

    public static function insertQuery($sql){
        return self::$db_driver->insertQuery($sql);
    }

    public static function insert($table, $data){
        return self::$db_driver->insert($table, $data);
    }

    public function updateQuery($sql){
        return self::$db_driver->updateQuery($sql);
    }

    public static function update($table, $field, $id, $data){
        return self::$db_driver->update($table, $field, $id, $data);
    }

    public static function deleteQuery($sql){
        return self::$db_driver->deleteQuery($sql);
    }

    public static function delete($table, $field, $id){
        return self::$db_driver->delete($table, $field, $id);
    }

    public static function deleteAll($table, $field, $id){
        return self::$db_driver->deleteAll($table, $field, $id);
    }

    public static function beginTransaction(){
        return self::$db_driver->beginTransaction();
    }

    public static function commit(){
        return self::$db_driver->commit();
    }

    public static function rollback(){
        return self::$db_driver->rollback();
    }

    public static function transactionStatus(){
        return self::$db_driver->transactionStatus();
    }

    public static function insertId(){
        return self::$db_driver->insertId();
    }

    public static function affectedRows(){
        return self::$db_driver->affectedRows();
    }
}
