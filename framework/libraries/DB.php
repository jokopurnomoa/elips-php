<?php
/**
 * Database Library
 *
 * Control database driver
 *
 */

class DB
{

    private static $dbDriver;

    /**
     * Initialize Database
     */
    public static function init()
    {
        if (get_app_config('db', 'main', 'driver') === 'mysqli') {
            require_once 'DBDriver/MySQLi.php';
            self::$dbDriver = new MySQLiDriver(get_app_config('db', 'main'));
            self::$dbDriver->connect();
        } elseif(get_app_config('db', 'main', 'driver') === 'sqlite') {
            require_once 'DBDriver/SQLite.php';
            self::$dbDriver = new SQLiteDriver(get_app_config('db', 'main'));
            self::$dbDriver->connect();
        } elseif(APP_ENV === 'development') {
            error_dump('Database Driver \'' . get_app_config('db', 'main', 'driver') . '\' not avaiable.');die();
        }
    }

    /**
     * Get database instance
     * Usefull for multiple connection
     *
     * @param array $config
     * @return Database
     */
    public static function getInstance($config)
    {
        return new Database($config);
    }

    /**
     * Escaping String
     *
     * @param string $string
     * @return mixed
     */
    public static function escape($string)
    {
        return self::$dbDriver->escape($string);
    }

    /**
     * Get Count Data Using Query
     *
     * @param string $sql
     * @return mixed
     */
    public static function getCountQuery($sql, $params = null)
    {
        return self::$dbDriver->getCountQuery($sql, $params);
    }

    /**
     * Get Count Data
     *
     * @param string $table
     * @param null|array $where
     * @param null|array $order
     * @param null|string $limit
     * @return mixed
     */
    public static function getCount($table, $where = null, $order = null, $limit = null)
    {
        return self::$dbDriver->getCount($table, $where, $order, $limit);
    }

    /**
     * Get All Data Using Query
     *
     * @param string $sql
     * @return mixed
     */
    public static function getAllQuery($sql, $params = null)
    {
        return self::$dbDriver->getAllQuery($sql, $params);
    }

    /**
     * Get All Data
     *
     * @param string $table
     * @param null|array $where
     * @param null|array $order
     * @param null|string $limit
     * @return mixed
     */
    public static function getAll($table, $where = null, $order = null, $limit = null)
    {
        return self::$dbDriver->getAll($table, $where, $order, $limit);
    }

    /**
     * Get All Data By Field
     *
     * @param string $table
     * @param null|array $field
     * @param null|array $where
     * @param null|array $order
     * @param null|string $limit
     * @return mixed
     */
    public static function getAllField($table, $field = null, $where = null, $order = null, $limit = null)
    {
        return self::$dbDriver->getAllField($table, $field, $where, $order, $limit);
    }

    /**
     * Get First Data Using Query
     *
     * @param string $sql
     * @return mixed
     */
    public static function getFirstQuery($sql, $params = null)
    {
        return self::$dbDriver->getFirstQuery($sql, $params);
    }

    /**
     * Get First Data
     *
     * @param string $table
     * @param null|array $where
     * @param null|array $order
     * @param null|string $limit
     * @return mixed
     */
    public static function getFirst($table, $where = null, $order = null, $limit = null)
    {
        return self::$dbDriver->getFirst($table, $where, $order, $limit);
    }

    /**
     * Get First Data By Field
     *
     * @param string $table
     * @param null|array $field
     * @param null|array $where
     * @param null|array $order
     * @param null|string $limit
     * @return mixed
     */
    public static function getFirstField($table, $field = null, $where = null, $order = null, $limit = null)
    {
        return self::$dbDriver->getFirstField($table, $field, $where, $order, $limit);
    }

    /**
     * Insert Data Using Query
     *
     * @param string $sql
     * @return mixed
     */
    public static function insertQuery($sql, $params = null)
    {
        return self::$dbDriver->insertQuery($sql, $params);
    }

    /**
     * Insert Data
     *
     * @param string $table
     * @param array $data
     * @return mixed
     */
    public static function insert($table, $data)
    {
        return self::$dbDriver->insert($table, $data);
    }

    /**
     * Update Data Using Query
     *
     * @param string $sql
     * @return mixed
     */
    public static function updateQuery($sql, $params = null)
    {
        return self::$dbDriver->updateQuery($sql, $params);
    }

    /**
     * Update Data
     *
     * @param string $table
     * @param string $field
     * @param string $id
     * @param array $data
     * @return mixed
     */
    public static function update($table, $field, $id, $data)
    {
        return self::$dbDriver->update($table, $field, $id, $data);
    }

    /**
     * Delete Data Using Query
     *
     * @param string $sql
     * @return mixed
     */
    public static function deleteQuery($sql, $params = null)
    {
        return self::$dbDriver->deleteQuery($sql, $params);
    }

    /**
     * Delete Data
     *
     * @param string $table
     * @param string $field
     * @param string $id
     * @param int $limit
     * @return mixed
     */
    public static function delete($table, $field, $id, $limit = 1)
    {
        return self::$dbDriver->delete($table, $field, $id, $limit);
    }

    /**
     * Begin Transaction
     *
     * @return mixed
     */
    public static function beginTransaction()
    {
        return self::$dbDriver->beginTransaction();
    }

    /**
     * Commit Transaction
     *
     * @return mixed
     */
    public static function commit()
    {
        return self::$dbDriver->commit();
    }

    /**
     * Rollback Transaction
     *
     * @return mixed
     */
    public static function rollback()
    {
        return self::$dbDriver->rollback();
    }

    /**
     * get Transaction Status
     *
     * @return mixed
     */
    public static function transactionStatus()
    {
        return self::$dbDriver->transactionStatus();
    }

    /**
     * Get Insert Id
     *
     * @return mixed
     */
    public static function insertId()
    {
        return self::$dbDriver->insertId();
    }

    /**
     * Get Affected Rows
     *
     * @return mixed
     */
    public static function affectedRows()
    {
        return self::$dbDriver->affectedRows();
    }

    /**
     * Create Table
     *
     * @param $structure
     * @return mixed
     */
    public static function createTable($table, $fields)
    {
        return self::$dbDriver->createTable($table, $fields);
    }

    /**
     * Drop Table
     *
     * @param string $table
     * @return mixed
     */
    public static function dropTable($table)
    {
        return self::$dbDriver->dropTable($table);
    }

    /**
     * Get database driver with set table
     *
     * @param string $table
     * @return mixed
     */
    public static function table($table)
    {
        self::$dbDriver->table($table);
        return self::$dbDriver;
    }

}

class Database
{

    private $dbDriver;

    public function __construct($config)
    {
        if ($config['driver'] === 'mysqli') {
            require_once 'DBDriver/MySQLi.php';
            $this->dbDriver = new MySQLiDriver($config);
            $this->dbDriver->connect();
        } elseif($config['driver'] === 'sqlite') {
            require_once 'DBDriver/SQLite.php';
            $this->dbDriver = new SQLiteDriver($config);
            $this->dbDriver->connect();
        } elseif(APP_ENV === 'development') {
            error_dump('Database Driver \'' . $config['driver'] . '\' not avaiable.');die();
        }
    }

    /**
     * Escaping String
     *
     * @param string $string
     * @return mixed
     */
    public function escape($string)
    {
        return $this->dbDriver->escape($string);
    }

    /**
     * Get Count Data Using Query
     *
     * @param string $sql
     * @return mixed
     */
    public function getCountQuery($sql, $params = null)
    {
        return $this->dbDriver->getCountQuery($sql, $params);
    }

    /**
     * Get Count Data
     *
     * @param string $table
     * @param null|array $where
     * @param null|array $order
     * @param null|string $limit
     * @return mixed
     */
    public function getCount($table, $where = null, $order = null, $limit = null)
    {
        return $this->dbDriver->getCount($table, $where, $order, $limit);
    }

    /**
     * Get All Data Using Query
     *
     * @param string $sql
     * @return mixed
     */
    public function getAllQuery($sql, $params = null)
    {
        return $this->dbDriver->getAllQuery($sql, $params);
    }

    /**
     * Get All Data
     *
     * @param string $table
     * @param null|array $where
     * @param null|array $order
     * @param null|string $limit
     * @return mixed
     */
    public function getAll($table, $where = null, $order = null, $limit = null)
    {
        return $this->dbDriver->getAll($table, $where, $order, $limit);
    }

    /**
     * Get All Data By Field
     *
     * @param string $table
     * @param null|array $field
     * @param null|array $where
     * @param null|array $order
     * @param null|string $limit
     * @return mixed
     */
    public function getAllField($table, $field = null, $where = null, $order = null, $limit = null)
    {
        return $this->dbDriver->getAllField($table, $field, $where, $order, $limit);
    }

    /**
     * Get First Data Using Query
     *
     * @param string $sql
     * @return mixed
     */
    public function getFirstQuery($sql, $params = null)
    {
        return $this->dbDriver->getFirstQuery($sql, $params);
    }

    /**
     * Get First Data
     *
     * @param string $table
     * @param null|array $where
     * @param null|array $order
     * @param null|string $limit
     * @return mixed
     */
    public function getFirst($table, $where = null, $order = null, $limit = null)
    {
        return $this->dbDriver->getFirst($table, $where, $order, $limit);
    }

    /**
     * Get First Data By Field
     *
     * @param string $table
     * @param null|array $field
     * @param null|array $where
     * @param null|array $order
     * @param null|string $limit
     * @return mixed
     */
    public function getFirstField($table, $field = null, $where = null, $order = null, $limit = null)
    {
        return $this->dbDriver->getFirstField($table, $field, $where, $order, $limit);
    }

    /**
     * Insert Data Using Query
     *
     * @param string $sql
     * @return mixed
     */
    public function insertQuery($sql, $params = null)
    {
        return $this->dbDriver->insertQuery($sql, $params);
    }

    /**
     * Insert Data
     *
     * @param string $table
     * @param array $data
     * @return mixed
     */
    public function insert($table, $data)
    {
        return $this->dbDriver->insert($table, $data);
    }

    /**
     * Update Data Using Query
     *
     * @param string $sql
     * @return mixed
     */
    public function updateQuery($sql, $params = null)
    {
        return $this->dbDriver->updateQuery($sql, $params);
    }

    /**
     * Update Data
     *
     * @param string $table
     * @param string $field
     * @param string $id
     * @param array $data
     * @return mixed
     */
    public function update($table, $field, $id, $data)
    {
        return $this->dbDriver->update($table, $field, $id, $data);
    }

    /**
     * Delete Data Using Query
     *
     * @param string $sql
     * @return mixed
     */
    public function deleteQuery($sql, $params = null)
    {
        return $this->dbDriver->deleteQuery($sql, $params);
    }

    /**
     * Delete Data
     *
     * @param string $table
     * @param string $field
     * @param string $id
     * @param int $limit
     * @return mixed
     */
    public function delete($table, $field, $id, $limit = 1)
    {
        return $this->dbDriver->delete($table, $field, $id, $limit);
    }

    /**
     * Begin Transaction
     *
     * @return mixed
     */
    public function beginTransaction()
    {
        return $this->dbDriver->beginTransaction();
    }

    /**
     * Commit Transaction
     *
     * @return mixed
     */
    public function commit()
    {
        return $this->dbDriver->commit();
    }

    /**
     * Rollback Transaction
     *
     * @return mixed
     */
    public function rollback()
    {
        return $this->dbDriver->rollback();
    }

    /**
     * get Transaction Status
     *
     * @return mixed
     */
    public function transactionStatus()
    {
        return $this->dbDriver->transactionStatus();
    }

    /**
     * Get Insert Id
     *
     * @return mixed
     */
    public function insertId()
    {
        return $this->dbDriver->insertId();
    }

    /**
     * Get Affected Rows
     *
     * @return mixed
     */
    public function affectedRows()
    {
        return $this->dbDriver->affectedRows();
    }

    /**
     * Create Table
     *
     * @param $structure
     * @return mixed
     */
    public function createTable($table, $fields)
    {
        return $this->dbDriver->createTable($table, $fields);
    }

    /**
     * Drop Table
     *
     * @param string $table
     * @return mixed
     */
    public function dropTable($table)
    {
        return $this->dbDriver->dropTable($table);
    }

    /**
     * Get database driver with set table
     *
     * @param string $table
     * @return mixed
     */
    public function table($table)
    {
        $this->dbDriver->table($table);
        return $this->dbDriver;
    }

}
