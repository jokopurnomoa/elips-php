<?php
/**
 * MySQLiDriver
 *
 * Basic CRUD database functions with MySQLi
 *
 */

class MySQLiDriver {

    private static $link;
    private static $config;
    private static $transaction_status;

    public static function init($config = null){
        self::$config = $config;
    }

    public static function connect(){
        self::$link = mysqli_connect(self::$config['host'], self::$config['user'], self::$config['pass'], self::$config['db']);
    }

    public static function disconnect(){
        mysqli_close(self::$link);
    }

    public static function escape($string){
        return mysqli_escape_string(self::$link, $string);
    }

    public static function realEscape($string){
        return mysqli_real_escape_string(self::$link, $string);
    }

    public static function getCountQuery($sql){
        $query = mysqli_query(self::$link, $sql);
        return mysqli_num_rows($query);
    }

    public static function getCount($table, $where = null, $limit = null){
        $sql = "SELECT * FROM $table ";
        if($where != null){
            $sql .= " WHERE ";
            $_i = 0;
            foreach($where as $key => $val){
                if($_i == 0){
                    $sql .= " $key = '$val' ";
                } else {
                    $sql .= " AND $key = '$val' ";
                }

                $_i++;
            }
        }

        if($limit != null){
            $sql .= " LIMIT $limit";
        }
        return self::getCountQuery($sql);
    }

    public static function getAllQuery($sql){
        $query = mysqli_query(self::$link, $sql);
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

    public static function getAll($table, $where = null, $order = null, $limit = null){
        $sql = "SELECT * FROM $table ";
        if($where != null){
            $sql .= " WHERE ";
            $_i = 0;
            foreach($where as $key => $val){
                if($_i == 0){
                    $sql .= " $key = '$val' ";
                } else {
                    $sql .= " AND $key = '$val' ";
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

        return self::getAllQuery($sql);
    }

    public static function getAllField($table, $field = null, $where = null, $order = null, $limit = null){
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
                    $sql .= " $key = '$val' ";
                } else {
                    $sql .= " AND $key = '$val' ";
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
        return self::getAllQuery($sql);
    }

    public static function getFirstQuery($sql){
        $query = mysqli_query(self::$link, $sql);
        if(mysqli_num_rows($query) > 0){
            return (object)mysqli_fetch_assoc($query);
        } else {
            return null;
        }
    }

    public static function getFirst($table, $where = null, $order = null, $limit = null){
        $sql = "SELECT * FROM $table ";
        if($where != null){
            $sql .= " WHERE ";
            $_i = 0;
            foreach($where as $key => $val){
                if($_i == 0){
                    $sql .= " $key = '$val' ";
                } else {
                    $sql .= " AND $key = '$val' ";
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

        return self::getFirstQuery($sql);
    }

    public static function getFirstField($table, $field = null, $where = null, $order = null, $limit = null){
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
                    $sql .= " $key = '$val' ";
                } else {
                    $sql .= " AND $key = '$val' ";
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
        return self::getFirstQuery($sql);
    }

    public static function insertQuery($sql){
        mysqli_query(self::$link, $sql);
        return mysqli_affected_rows(self::$link) > 0 ? true : false;
    }

    public static function insert($table, $data){
        if($data != null){
            $sql = "INSERT INTO $table ";
            $_i = 0;
            $_fields = "(";
            $_values = "(";
            foreach($data as $key => $val){
                if($_i == 0){
                    $_fields .= $key;
                    $_values .= '\'' . $val . '\'';
                } else {
                    $_fields .= ',' . $key;
                    $_values .= ',\'' . $val . '\'';
                }

                $_i++;
            }
            $_fields .= ")";
            $_values .= ")";
            $sql .= $_fields . " VALUES " . $_values;
            return self::insertQuery($sql);
        }
        return false;
    }

    public static function updateQuery($sql){
        mysqli_query(self::$link, $sql);
        return mysqli_affected_rows(self::$link) > 0 ? true : false;
    }

    public static function update($table, $field, $id, $data){
        if($data != null && $field != '' && $id != ''){
            $sql = "UPDATE $table SET ";
            $_i = 0;
            foreach($data as $key => $val){
                if($_i == 0){
                    $sql .= "$key = '$val' ";
                } else {
                    $sql .= ",$key = '$val' ";
                }

                $_i++;
            }
            $sql .= " WHERE $field = '$id' ";
            return self::updateQuery($sql);
        }
        return false;
    }

    public static function deleteQuery($sql){
        mysqli_query(self::$link, $sql);
        return mysqli_affected_rows(self::$link) > 0 ? true : false;
    }

    public static function delete($table, $field, $id){
        $sql = "DELETE FROM $table WHERE $field = '$id' LIMIT 1";
        return self::deleteQuery($sql);
    }

    public static function deleteAll($table, $field, $id){
        $sql = "DELETE FROM $table WHERE $field = '$id'";
        return self::deleteQuery($sql);
    }

    public static function startTransaction(){
        self::$transaction_status = false;
        return mysqli_autocommit(self::$link, FALSE);
    }

    public static function commit(){
        self::$transaction_status = mysqli_commit(self::$link);
        mysqli_autocommit(self::$link, TRUE);
        return self::$transaction_status;
    }

    public static function rollback(){
        return mysqli_rollback(self::$link);
    }

    public static function transactionStatus(){
        return self::$transaction_status;
    }

    public static function insertId(){
        return mysqli_insert_id(self::$link);
    }

    public static function affectedRows(){
        return mysqli_affected_rows(self::$link);
    }

}
