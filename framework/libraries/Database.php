<?php
/**
 * Database Library
 *
 * Control database driver
 *
 */

class Database {

    private static $db_driver;

    /**
     * Initialize Database
     */
    public static function init(){
        if(get_app_config('db', 'main', 'driver') === 'mysqli'){
            require 'DBDriver/MySQLi.php';
            self::$db_driver = new MySQLiDriver(get_app_config('db', 'main'));
            self::$db_driver->connect();
        } elseif(get_app_config('db', 'main', 'driver') === 'sqlite'){
            require 'DBDriver/SQLite.php';
            self::$db_driver = new SQLiteDriver(get_app_config('db', 'main'));
            self::$db_driver->connect();
        } elseif(APP_ENV === 'development') {
            error_dump('Database Driver \'' . get_app_config('db', 'main', 'driver') . '\' not avaiable.');die();
        }
    }

    /**
     * Escaping String
     *
     * @param $string
     * @return mixed
     */
    public static function escape($string){
        return self::$db_driver->escape($string);
    }

    /**
     * Get Count Data Using Query
     *
     * @param $sql
     * @return mixed
     */
    public static function getCountQuery($sql, $params = null){
        return self::$db_driver->getCountQuery($sql, $params);
    }

    /**
     * Get Count Data
     *
     * @param $table
     * @param null $where
     * @param null $order
     * @param null $limit
     * @return mixed
     */
    public static function getCount($table, $where = null, $order = null, $limit = null){
        return self::$db_driver->getCount($table, $where, $order, $limit);
    }

    /**
     * Get All Data Using Query
     *
     * @param $sql
     * @return mixed
     */
    public static function getAllQuery($sql, $params = null){
        return self::$db_driver->getAllQuery($sql, $params);
    }

    /**
     * Get All Data
     *
     * @param $table
     * @param null $where
     * @param null $order
     * @param null $limit
     * @return mixed
     */
    public static function getAll($table, $where = null, $order = null, $limit = null){
        return self::$db_driver->getAll($table, $where, $order, $limit);
    }

    /**
     * Get All Data By Field
     *
     * @param $table
     * @param null $field
     * @param null $where
     * @param null $order
     * @param null $limit
     * @return mixed
     */
    public static function getAllField($table, $field = null, $where = null, $order = null, $limit = null){
        return self::$db_driver->getAllField($table, $field, $where, $order, $limit);
    }

    /**
     * Get First Data Using Query
     *
     * @param $sql
     * @return mixed
     */
    public static function getFirstQuery($sql, $params = null){
        return self::$db_driver->getFirstQuery($sql, $params);
    }

    /**
     * Get First Data
     *
     * @param $table
     * @param null $where
     * @param null $order
     * @param null $limit
     * @return mixed
     */
    public static function getFirst($table, $where = null, $order = null, $limit = null){
        return self::$db_driver->getFirst($table, $where, $order, $limit);
    }

    /**
     * Get First Data By Field
     *
     * @param $table
     * @param null $field
     * @param null $where
     * @param null $order
     * @param null $limit
     * @return mixed
     */
    public static function getFirstField($table, $field = null, $where = null, $order = null, $limit = null){
        return self::$db_driver->getFirstField($table, $field, $where, $order, $limit);
    }

    /**
     * Insert Data Using Query
     *
     * @param $sql
     * @return mixed
     */
    public static function insertQuery($sql, $params = null){
        return self::$db_driver->insertQuery($sql, $params);
    }

    /**
     * Insert Data
     *
     * @param $table
     * @param $data
     * @return mixed
     */
    public static function insert($table, $data){
        return self::$db_driver->insert($table, $data);
    }

    /**
     * Update Data Using Query
     *
     * @param $sql
     * @return mixed
     */
    public function updateQuery($sql, $params = null){
        return self::$db_driver->updateQuery($sql, $params);
    }

    /**
     * Update Data
     *
     * @param $table
     * @param $field
     * @param $id
     * @param $data
     * @return mixed
     */
    public static function update($table, $field, $id, $data){
        return self::$db_driver->update($table, $field, $id, $data);
    }

    /**
     * Delete Data Using Query
     *
     * @param $sql
     * @return mixed
     */
    public static function deleteQuery($sql, $params = null){
        return self::$db_driver->deleteQuery($sql, $params);
    }

    /**
     * Delete Data
     *
     * @param $table
     * @param $field
     * @param $id
     * @param int $limit
     * @return mixed
     */
    public static function delete($table, $field, $id, $limit = 1){
        return self::$db_driver->delete($table, $field, $id, $limit);
    }

    /**
     * Begin Transaction
     *
     * @return mixed
     */
    public static function beginTransaction(){
        return self::$db_driver->beginTransaction();
    }

    /**
     * Commit Transaction
     *
     * @return mixed
     */
    public static function commit(){
        return self::$db_driver->commit();
    }

    /**
     * Rollback Transaction
     *
     * @return mixed
     */
    public static function rollback(){
        return self::$db_driver->rollback();
    }

    /**
     * get Transaction Status
     *
     * @return mixed
     */
    public static function transactionStatus(){
        return self::$db_driver->transactionStatus();
    }

    /**
     * Get Insert Id
     *
     * @return mixed
     */
    public static function insertId(){
        return self::$db_driver->insertId();
    }

    /**
     * Get Affected Rows
     *
     * @return mixed
     */
    public static function affectedRows(){
        return self::$db_driver->affectedRows();
    }

    /**
     * Create Table
     *
     * @param $structure
     * @return mixed
     */
    public static function createTable($table, $fields){
        return self::$db_driver->createTable($table, $fields);
    }

    /**
     * Drop Table
     *
     * @param $table
     * @return mixed
     */
    public static function dropTable($table){
        return self::$db_driver->dropTable($table);
    }
}
