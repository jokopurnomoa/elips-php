<?php
/**
 * SQLite Driver
 *
 * Basic CRUD database functions with SQLite
 *
 */

class SQLiteDriver extends SQLite3 {

    private $db;
    private $config;
    private $transaction_status;

    /**
     * Constructor
     *
     * @param null $config
     */
    public function __construct($config = null){
        $this->config = $config;
    }

    /**
     * Destructor
     */
    public function __destruct(){
        $this->disconnect();
    }

    /**
     * Initialize Driver
     *
     * @param null $config
     */
    public function init($config = null){
        $this->config = $config;
    }

    /**
     * Connnect
     */
    public function connect(){
        if(!file_exists('storage/sqlite/' . $this->config['db'] . '.db')){
            $handle = fopen('storage/sqlite/' . $this->config['db'] . '.db', 'w');
            fclose($handle);
        }
        $this->db = new SQLite3('storage/sqlite/' . $this->config['db'] . '.db', SQLITE3_OPEN_READWRITE, $this->config['pass']);
    }

    /**
     * Disconnect
     */
    public function disconnect(){
        $this->db->close();
    }

    /**
     * @param $string
     * @return string
     */
    public function escape($string){
        return $this->db->escapeString($string);
    }

    /**
     * Get Count Data Using Query
     *
     * @param $sql
     * @return int
     */
    public function getCountQuery($sql, $params = null){
        if($params != null){
            foreach($params as $param){
                $param_pos = strpos($sql, '?');
                if($param_pos !== false) {
                    $sql = substr_replace($sql, '\'' . $this->escape($param) . '\'', $param_pos, 1);
                }
            }
        }

        $sql = 'SELECT count(*) AS num_rows FROM (' . $sql . ') as tmp';
        $query = $this->db->query($sql);
        $data = (object)$query->fetchArray(SQLITE3_ASSOC);
        if($data != null){
            return $data->num_rows;
        }
        return 0;
    }

    /**
     * Get Count Data
     *
     * @param $table
     * @param null $where
     * @param null $limit
     * @return int
     */
    public function getCount($table, $where = null, $limit = null){
        $sql = "SELECT * FROM $table ";
        if($where != null){
            $sql .= " WHERE ";
            $_i = 0;
            foreach($where as $key => $val){
                if($_i === 0){
                    $sql .= " $key = '" . $this->escape($val) . "' ";
                } else {
                    $sql .= " AND $key = '" . $this->escape($val) . "' ";
                }

                $_i++;
            }
        }

        if($limit != null){
            $sql .= " LIMIT $limit";
        }
        return $this->getCountQuery($sql);
    }

    /**
     * Get All Data Using Query
     *
     * @param $sql
     * @return array|null
     */
    public function getAllQuery($sql, $params = null){
        if($params != null){
            foreach($params as $param){
                $param_pos = strpos($sql, '?');
                if($param_pos !== false) {
                    $sql = substr_replace($sql, '\'' . $this->escape($param) . '\'', $param_pos, 1);
                }
            }
        }

        $num_rows = $this->getCountQuery($sql);
        $query = $this->db->query($sql);

        if($query){
            if($num_rows > 0){
                $result = null;
                while($data = $query->fetchArray(SQLITE3_ASSOC)) {
                    $result[] = (object)$data;
                }
                return $result;
            }
        }
        return null;
    }

    /**
     * Get All Data
     *
     * @param $table
     * @param null $where
     * @param null $order
     * @param null $limit
     * @return array|null
     */
    public function getAll($table, $where = null, $order = null, $limit = null){
        $sql = "SELECT * FROM $table ";
        if($where != null){
            $sql .= " WHERE ";
            $_i = 0;
            foreach($where as $key => $val){
                if($_i === 0){
                    $sql .= " $key = '" . $this->escape($val) . "' ";
                } else {
                    $sql .= " AND $key = '" . $this->escape($val) . "' ";
                }

                $_i++;
            }
        }

        if($order != null){
            $sql .= " ORDER BY ";
            $_i = 0;
            foreach($order as $key => $val){
                if($_i === 0){
                    $sql .= " $key $val ";
                } else {
                    $sql .= " ,$key $val ";
                }

                $_i++;
            }
        }

        if($limit != null){
            $sql .= " LIMIT $limit";
        }

        return $this->getAllQuery($sql);
    }

    /**
     * Get All Data By Field
     *
     * @param $table
     * @param null $field
     * @param null $where
     * @param null $order
     * @param null $limit
     * @return array|null
     */
    public function getAllField($table, $field = null, $where = null, $order = null, $limit = null){
        $sql = "SELECT ";
        if($field != null){
            $_i = 0;
            foreach($field as $val){
                if($_i === 0){
                    $sql .= "$val ";
                } else {
                    $sql .= ",$val ";
                }

                $_i++;
            }
        }

        $sql .= " FROM $table ";

        if($where != null){
            $sql .= " WHERE ";
            $_i = 0;
            foreach($where as $key => $val){
                if($_i === 0){
                    $sql .= " $key = '" . $this->escape($val) . "' ";
                } else {
                    $sql .= " AND $key = '" . $this->escape($val) . "' ";
                }

                $_i++;
            }
        }

        if($order != null){
            $sql .= " ORDER BY ";
            $_i = 0;
            foreach($order as $key => $val){
                if($_i === 0){
                    $sql .= " $key $val ";
                } else {
                    $sql .= " ,$key $val ";
                }

                $_i++;
            }
        }

        if($limit != null){
            $sql .= " LIMIT $limit";
        }
        return $this->getAllQuery($sql);
    }

    /**
     * Get First Data Using Query
     *
     * @param $sql
     * @return null|object
     */
    public function getFirstQuery($sql, $params = null){
        if($params != null){
            foreach($params as $param){
                $param_pos = strpos($sql, '?');
                if($param_pos !== false) {
                    $sql = substr_replace($sql, '\'' . $this->escape($param) . '\'', $param_pos, 1);
                }
            }
        }

        $num_rows = $this->getCountQuery($sql);
        $query = $this->db->query($sql);

        if($query){
            if($num_rows > 0){
                $data = $query->fetchArray(SQLITE3_ASSOC);
                if($data != null){
                    return (object)$data;
                }
            }
        }
        return null;
    }

    /**
     * Get First Data
     *
     * @param $table
     * @param null $where
     * @param null $order
     * @param null $limit
     * @return null|object
     */
    public function getFirst($table, $where = null, $order = null, $limit = null){
        $sql = "SELECT * FROM $table ";
        if($where != null){
            $sql .= " WHERE ";
            $_i = 0;
            foreach($where as $key => $val){
                if($_i === 0){
                    $sql .= " $key = '" . $this->escape($val) . "' ";
                } else {
                    $sql .= " AND $key = '" . $this->escape($val) . "' ";
                }

                $_i++;
            }
        }

        if($order != null){
            $sql .= " ORDER BY ";
            $_i = 0;
            foreach($order as $key => $val){
                if($_i === 0){
                    $sql .= " $key $val ";
                } else {
                    $sql .= " ,$key $val ";
                }

                $_i++;
            }
        }

        if($limit != null){
            $sql .= " LIMIT $limit";
        }

        return $this->getFirstQuery($sql);
    }

    /**
     * Get First Data By Field
     *
     * @param $table
     * @param null $field
     * @param null $where
     * @param null $order
     * @param null $limit
     * @return null|object
     */
    public function getFirstField($table, $field = null, $where = null, $order = null, $limit = null){
        $sql = "SELECT ";
        if($field != null){
            $_i = 0;
            foreach($field as $val){
                if($_i === 0){
                    $sql .= "$val ";
                } else {
                    $sql .= ",$val ";
                }

                $_i++;
            }
        }

        $sql .= " FROM $table ";

        if($where != null){
            $sql .= " WHERE ";
            $_i = 0;
            foreach($where as $key => $val){
                if($_i === 0){
                    $sql .= " $key = '" . $this->escape($val) . "' ";
                } else {
                    $sql .= " AND $key = '" . $this->escape($val) . "' ";
                }

                $_i++;
            }
        }

        if($order != null){
            $sql .= " ORDER BY ";
            $_i = 0;
            foreach($order as $key => $val){
                if($_i === 0){
                    $sql .= " $key $val ";
                } else {
                    $sql .= " ,$key $val ";
                }

                $_i++;
            }
        }

        if($limit != null){
            $sql .= " LIMIT $limit";
        }
        return $this->getFirstQuery($sql);
    }

    /**
     * Insert Data Using Query
     *
     * @param $sql
     * @return bool
     */
    public function insertQuery($sql, $params = null){
        if($params != null){
            foreach($params as $param){
                $param_pos = strpos($sql, '?');
                if($param_pos !== false) {
                    $sql = substr_replace($sql, '\'' . $this->escape($param) . '\'', $param_pos, 1);
                }
            }
        }

        $this->db->query($sql);
        return ($this->db->changes() > 0 ? true : false);
    }

    /**
     * Insert Data
     *
     * @param $table
     * @param $data
     * @return bool
     */
    public function insert($table, $data){
        $_this = $this;
        if($data != null){
            $sql = "INSERT INTO $table ";
            $_i = 0;
            $_fields = "(";
            $_values = "(";
            foreach($data as $key => $val){
                if($_i === 0){
                    $_fields .= $key;
                    $_values .= "'" . $_this->escape($val) . "'";
                } else {
                    $_fields .= ',' . $key;
                    $_values .= ",'" . $_this->escape($val) . "'";
                }

                $_i++;
            }
            $_fields .= ")";
            $_values .= ")";
            $sql .= $_fields . " VALUES " . $_values;
            return $this->insertQuery($sql);
        }
        return false;
    }

    /**
     * Update Data Using Query
     *
     * @param $sql
     * @return bool
     */
    public function updateQuery($sql, $params = null){
        if($params != null){
            foreach($params as $param){
                $param_pos = strpos($sql, '?');
                if($param_pos !== false) {
                    $sql = substr_replace($sql, '\'' . $this->escape($param) . '\'', $param_pos, 1);
                }
            }
        }

        $this->db->query($sql);
        return ($this->db->changes() > 0 ? true : false);
    }

    /**
     * Update Data
     *
     * @param $table
     * @param $field
     * @param $id
     * @param $data
     * @return bool
     */
    public function update($table, $field, $id, $data){
        if($data != null && $field != '' && $id != ''){
            $sql = "UPDATE $table SET ";
            $_i = 0;
            foreach($data as $key => $val){
                if($_i === 0){
                    $sql .= "$key = '" . $this->escape($val) . "' ";
                } else {
                    $sql .= ",$key = '" . $this->escape($val) . "' ";
                }

                $_i++;
            }
            $sql .= " WHERE $field = '" . $this->escape($id) . "' ";
            return $this->updateQuery($sql);
        }
        return false;
    }

    /**
     * Delete Data Using Query
     *
     * @param $sql
     * @return bool
     */
    public function deleteQuery($sql, $params = null){
        if($params != null){
            foreach($params as $param){
                $param_pos = strpos($sql, '?');
                if($param_pos !== false) {
                    $sql = substr_replace($sql, '\'' . $this->escape($param) . '\'', $param_pos, 1);
                }
            }
        }

        $this->db->query($sql);
        return ($this->db->changes() > 0 ? true : false);
    }

    /**
     * Delete Data
     *
     * @param $table
     * @param $field
     * @param $id
     * @param int $limit
     * @return bool
     */
    public function delete($table, $field, $id, $limit = 1){
        $limit = (int)$limit;
        $sql = "DELETE FROM $table WHERE $field = '" . $this->escape($id) . "' LIMIT $limit";
        return $this->deleteQuery($sql);
    }

    /**
     * Begin Transaction
     *
     * @return bool
     */
    public function beginTransaction(){
        $this->transaction_status = false;
        return $this->db->query("BEGIN TRANSACTION");
    }

    /**
     * Commit Transaction
     *
     * @return bool
     */
    public function commit(){
        $query = $this->db->query("COMMIT");
        if($query){
            $this->transaction_status = true;
        }
    }

    /**
     * Rollback Transaction
     *
     * @return bool
     */
    public function rollback(){
        return $this->db->query("ROLLBACK");
    }

    /**
     * @return mixed
     */
    public function transactionStatus(){
        return $this->transaction_status;
    }

    /**
     * Get Insert Id
     *
     * @return int|string
     */
    public function insertId(){
        return $this->db->lastInsertRowID();
    }

    /**
     * Get Affected Rows
     *
     * @return int
     */
    public function affectedRows(){
        return $this->db->changes();
    }

    /**
     * Create Table
     *
     * @param $table
     * @param $fields
     * @return bool
     */
    public function createTable($table, $fields){
        if($table != null && $fields != null){
            $sql = 'CREATE TABLE IF NOT EXISTS ' . $table . ' (';
            $_i = 0;
            foreach($fields as $field){
                if(is_array($field)){
                    if($_i == 0){
                        $sql .= $field[0] . ' ' . $field[1] . (isset($field[2]) ? ' ' . $field[2] : '');
                    } else {
                        $sql .= ', ' . $field[0] . ' ' . $field[1] . (isset($field[2]) ? ' ' . $field[2] : '');
                    }
                }
                $_i++;
            }
            $sql .= ')';

            if($this->db->query($sql)){
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    /**
     * Drop Table
     *
     * @param $table
     * @return bool
     */
    public function dropTable($table){
        $sql = 'DROP TABLE IF EXISTS ' . $table;
        if($this->db->query($sql)){
            return true;
        } else {
            return false;
        }
    }

}
