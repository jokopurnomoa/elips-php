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

    /**
     * Destructor
     */
    public static function destruct(){
        if(self::$db_driver != null){
            self::$db_driver->disconnect();
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
    public static function getCountQuery($sql){
        return self::$db_driver->getCountQuery($sql);
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
    public static function getAllQuery($sql){
        return self::$db_driver->getAllQuery($sql);
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
    public static function getFirstQuery($sql){
        return self::$db_driver->getFirstQuery($sql);
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
    public static function insertQuery($sql){
        return self::$db_driver->insertQuery($sql);
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
    public function updateQuery($sql){
        return self::$db_driver->updateQuery($sql);
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
    public static function deleteQuery($sql){
        return self::$db_driver->deleteQuery($sql);
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
}
