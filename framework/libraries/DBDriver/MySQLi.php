<?php
/**
 * MySQLiDriver
 *
 * Basic CRUD database functions with MySQLi
 *
 */

class MySQLiDriver {

    private $link;
    private $config;
    private $transaction_status;

    public function __construct($config = null){
        $this->config = $config;
    }

    public function __destruct(){
        $this->disconnect();
    }

    public function init($config = null){
        $this->config = $config;
    }

    public function connect(){
        $this->link = mysqli_connect($this->config['host'], $this->config['user'], $this->config['pass'], $this->config['db']);
    }

    public function disconnect(){
        mysqli_close($this->link);
    }

    public function escape($string){
        return mysqli_real_escape_string($this->link, $string);
    }

    public function getCountQuery($sql){
        $query = mysqli_query($this->link, $sql);
        if($query) {
            return mysqli_num_rows($query);
        }
        return 0;
    }

    public function getCount($table, $where = null, $limit = null){
        $sql = "SELECT * FROM $table ";
        if($where != null){
            $sql .= " WHERE ";
            $_i = 0;
            foreach($where as $key => $val){
                if($_i == 0){
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

    public function getAllQuery($sql){
        $query = mysqli_query($this->link, $sql);
        if($query){
            if(mysqli_num_rows($query) > 0){
                $result = array();
                while($data = mysqli_fetch_assoc($query)){
                    $result[] = (object)$data;
                }
                return $result;
            } else {
                return null;
            }
        }
    }

    public function getAll($table, $where = null, $order = null, $limit = null){
        $sql = "SELECT * FROM $table ";
        if($where != null){
            $sql .= " WHERE ";
            $_i = 0;
            foreach($where as $key => $val){
                if($_i == 0){
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
                if($_i == 0){
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

    public function getAllField($table, $field = null, $where = null, $order = null, $limit = null){
        $sql = "SELECT ";
        if($field != null){
            $_i = 0;
            foreach($field as $val){
                if($_i == 0){
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
                if($_i == 0){
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
                if($_i == 0){
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

    public function getFirstQuery($sql){
        $query = mysqli_query($this->link, $sql);
        if($query) {
            if(mysqli_num_rows($query) > 0) {
                return (object)mysqli_fetch_assoc($query);
            }
        }
        return null;
    }

    public function getFirst($table, $where = null, $order = null, $limit = null){
        $sql = "SELECT * FROM $table ";
        if($where != null){
            $sql .= " WHERE ";
            $_i = 0;
            foreach($where as $key => $val){
                if($_i == 0){
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
                if($_i == 0){
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

    public function getFirstField($table, $field = null, $where = null, $order = null, $limit = null){
        $sql = "SELECT ";
        if($field != null){
            $_i = 0;
            foreach($field as $val){
                if($_i == 0){
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
                if($_i == 0){
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
                if($_i == 0){
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

    public function insertQuery($sql){
        mysqli_query($this->link, $sql);
        return mysqli_affected_rows($this->link) > 0 ? true : false;
    }

    public function insert($table, $data){
        $_this = $this;
        if($data != null){
            $sql = "INSERT INTO $table ";
            $_i = 0;
            $_fields = "(";
            $_values = "(";
            foreach($data as $key => $val){
                if($_i == 0){
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

    public function updateQuery($sql){
        mysqli_query($this->link, $sql);
        return mysqli_affected_rows($this->link) > 0 ? true : false;
    }

    public function update($table, $field, $id, $data){
        if($data != null && $field != '' && $id != ''){
            $sql = "UPDATE $table SET ";
            $_i = 0;
            foreach($data as $key => $val){
                if($_i == 0){
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

    public function deleteQuery($sql){
        mysqli_query($this->link, $sql);
        return mysqli_affected_rows($this->link) > 0 ? true : false;
    }

    public function delete($table, $field, $id, $limit = 1){
        $limit = (int)$limit;
        $sql = "DELETE FROM $table WHERE $field = '" . $this->escape($id) . "' LIMIT $limit";
        return $this->deleteQuery($sql);
    }

    public function beginTransaction(){
        $this->transaction_status = false;
        mysqli_autocommit($this->link, FALSE);
        return mysqli_begin_transaction($this->link);
    }

    public function commit(){
        $this->transaction_status = mysqli_commit($this->link);
        mysqli_autocommit($this->link, TRUE);
        return $this->transaction_status;
    }

    public function rollback(){
        return mysqli_rollback($this->link);
    }

    public function transactionStatus(){
        return $this->transaction_status;
    }

    public function insertId(){
        return mysqli_insert_id($this->link);
    }

    public function affectedRows(){
        return mysqli_affected_rows($this->link);
    }

}
