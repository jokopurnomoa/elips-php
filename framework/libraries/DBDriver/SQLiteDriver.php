<?php
/**
 * SQLite Driver
 *
 * Basic CRUD database functions with SQLite
 *
 */

namespace Elips\Libraries\DBDriver;

use Elips\Libraries\DBDriver\QueryBuilder\SQLiteQueryBuilder;

class SQLiteDriver extends \SQLite3
{

    private $db;
    private $config;
    private $transaction_status;
    private $queryBuilder;

    /**
     * Constructor
     *
     * @param null $config
     */
    public function __construct($config = null)
    {
        $this->config = $config;
        $this->queryBuilder = new SQLiteQueryBuilder();
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        $this->disconnect();
    }

    /**
     * Initialize Driver
     *
     * @param null $config
     */
    public function init($config = null)
    {
        $this->config = $config;
    }

    /**
     * Connnect
     */
    public function connect()
    {
        $this->db = new \SQLite3('storage/sqlite/' . $this->config['db'] . '.db');
        $this->queryBuilder->setDB($this->db);
    }

    /**
     * Disconnect
     */
    public function disconnect()
    {
        $this->db->close();
    }

    /**
     * Escape String
     *
     * @param $string
     * @return string
     */
    public function escape($string)
    {
        return $this->db->escapeString($string);
    }

    /**
     * Get Count Data Using Query
     *
     * @param $sql
     * @return int
     */
    public function getCountQuery($sql, $params = null)
    {
        if ($params != null) {
            foreach ($params as $param) {
                $param_pos = strpos($sql, '?');
                if ($param_pos !== false) {
                    $sql = substr_replace($sql, '\'' . $this->escape($param) . '\'', $param_pos, 1);
                }
            }
        }

        $sql = 'SELECT count(*) AS num_rows FROM (' . $sql . ') as tmp';
        $query = $this->db->query($sql);
        $data = (object)$query->fetchArray(SQLITE3_ASSOC);
        if ($data != null) {
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
    public function getCount($table, $where = null, $limit = null)
    {
        $sql = "SELECT * FROM $table ";
        if ($where != null) {
            $sql .= " WHERE ";
            $_i = 0;
            foreach ($where as $key => $val) {
                if ($_i === 0) {
                    $sql .= " $key = '" . $this->escape($val) . "' ";
                } else {
                    $sql .= " AND $key = '" . $this->escape($val) . "' ";
                }

                $_i++;
            }
        }

        if ($limit != null) {
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
    public function getAllQuery($sql, $params = null)
    {
        if ($params != null) {
            foreach ($params as $param) {
                $param_pos = strpos($sql, '?');
                if ($param_pos !== false) {
                    $sql = substr_replace($sql, '\'' . $this->escape($param) . '\'', $param_pos, 1);
                }
            }
        }

        $num_rows = $this->getCountQuery($sql);
        $query = $this->db->query($sql);

        if ($query) {
            if ($num_rows > 0) {
                $result = null;
                while ($data = $query->fetchArray(SQLITE3_ASSOC)) {
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
    public function getAll($table, $where = null, $order = null, $limit = null)
    {
        $sql = "SELECT * FROM $table ";
        if ($where != null) {
            $sql .= " WHERE ";
            $_i = 0;
            foreach ($where as $key => $val) {
                if ($_i === 0) {
                    $sql .= " $key = '" . $this->escape($val) . "' ";
                } else {
                    $sql .= " AND $key = '" . $this->escape($val) . "' ";
                }

                $_i++;
            }
        }

        if ($order != null) {
            $sql .= " ORDER BY ";
            $_i = 0;
            foreach ($order as $key => $val) {
                if ($_i === 0) {
                    $sql .= " $key $val ";
                } else {
                    $sql .= " ,$key $val ";
                }

                $_i++;
            }
        }

        if ($limit != null) {
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
    public function getAllField($table, $field = null, $where = null, $order = null, $limit = null)
    {
        $sql = "SELECT ";
        if ($field != null) {
            $_i = 0;
            foreach ($field as $val) {
                if ($_i === 0) {
                    $sql .= "$val ";
                } else {
                    $sql .= ",$val ";
                }

                $_i++;
            }
        }

        $sql .= " FROM $table ";

        if ($where != null) {
            $sql .= " WHERE ";
            $_i = 0;
            foreach ($where as $key => $val) {
                if ($_i === 0) {
                    $sql .= " $key = '" . $this->escape($val) . "' ";
                } else {
                    $sql .= " AND $key = '" . $this->escape($val) . "' ";
                }

                $_i++;
            }
        }

        if ($order != null) {
            $sql .= " ORDER BY ";
            $_i = 0;
            foreach ($order as $key => $val) {
                if ($_i === 0) {
                    $sql .= " $key $val ";
                } else {
                    $sql .= " ,$key $val ";
                }

                $_i++;
            }
        }

        if ($limit != null) {
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
    public function getFirstQuery($sql, $params = null)
    {
        if ($params != null) {
            foreach ($params as $param) {
                $param_pos = strpos($sql, '?');
                if ($param_pos !== false) {
                    $sql = substr_replace($sql, '\'' . $this->escape($param) . '\'', $param_pos, 1);
                }
            }
        }

        $num_rows = $this->getCountQuery($sql);
        $query = $this->db->query($sql);

        if ($query) {
            if ($num_rows > 0) {
                $data = $query->fetchArray(SQLITE3_ASSOC);
                if ($data != null) {
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
    public function getFirst($table, $where = null, $order = null, $limit = null)
    {
        $sql = "SELECT * FROM $table ";
        if ($where != null) {
            $sql .= " WHERE ";
            $_i = 0;
            foreach ($where as $key => $val) {
                if ($_i === 0) {
                    $sql .= " $key = '" . $this->escape($val) . "' ";
                } else {
                    $sql .= " AND $key = '" . $this->escape($val) . "' ";
                }

                $_i++;
            }
        }

        if ($order != null) {
            $sql .= " ORDER BY ";
            $_i = 0;
            foreach ($order as $key => $val) {
                if ($_i === 0) {
                    $sql .= " $key $val ";
                } else {
                    $sql .= " ,$key $val ";
                }

                $_i++;
            }
        }

        if ($limit != null) {
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
    public function getFirstField($table, $field = null, $where = null, $order = null, $limit = null)
    {
        $sql = "SELECT ";
        if ($field != null) {
            $_i = 0;
            foreach ($field as $val) {
                if ($_i === 0) {
                    $sql .= "$val ";
                } else {
                    $sql .= ",$val ";
                }

                $_i++;
            }
        }

        $sql .= " FROM $table ";

        if ($where != null) {
            $sql .= " WHERE ";
            $_i = 0;
            foreach ($where as $key => $val) {
                if ($_i === 0) {
                    $sql .= " $key = '" . $this->escape($val) . "' ";
                } else {
                    $sql .= " AND $key = '" . $this->escape($val) . "' ";
                }

                $_i++;
            }
        }

        if ($order != null) {
            $sql .= " ORDER BY ";
            $_i = 0;
            foreach ($order as $key => $val) {
                if ($_i === 0) {
                    $sql .= " $key $val ";
                } else {
                    $sql .= " ,$key $val ";
                }

                $_i++;
            }
        }

        if ($limit != null) {
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
    public function insertQuery($sql, $params = null)
    {
        if ($params != null) {
            foreach ($params as $param) {
                $param_pos = strpos($sql, '?');
                if ($param_pos !== false) {
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
    public function insert($tableOrData, $data = null)
    {
        $sql = null;
        if ($data != null) {
            $this->queryBuilder->table($tableOrData);
            $sql = $this->queryBuilder->insert($data);
        } else {
            $sql = $this->queryBuilder->insert($tableOrData);
        }

        return $sql !== null ? $this->insertQuery($sql) : false;
    }

    /**
     * Update Data Using Query
     *
     * @param $sql
     * @return bool
     */
    public function updateQuery($sql, $params = null)
    {
        if ($params != null) {
            foreach ($params as $param) {
                $param_pos = strpos($sql, '?');
                if ($param_pos !== false) {
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
    public function update($tableOrData, $field = null, $id = null, $data = null, $limit = 1)
    {
        $sql = null;
        if ($field != null && $id != null && $data != null) {
            $sql = $this->queryBuilder->table($tableOrData)
                ->where($field, $id)
                ->limit($limit)
                ->update($data);
        } else {
            $sql = $this->queryBuilder->update($tableOrData);
        }

        return $sql !== null ? $this->updateQuery($sql) : false;
    }

    /**
     * Delete Data Using Query
     *
     * @param $sql
     * @return bool
     */
    public function deleteQuery($sql, $params = null)
    {
        if ($params != null) {
            foreach ($params as $param) {
                $param_pos = strpos($sql, '?');
                if ($param_pos !== false) {
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
    public function delete($table = null, $field = null, $id = null, $limit = 1)
    {
        $sql = null;
        if ($table != null && $field != null && $id != null) {
            $sql = $this->queryBuilder->table($table)
                ->where($field, $id)
                ->limit($limit)
                ->delete();
        } else {
            $sql = $this->queryBuilder->delete();
        }

        return $sql !== null ? $this->deleteQuery($sql) : false;
    }

    /**
     * Begin Transaction
     *
     * @return bool
     */
    public function beginTransaction()
    {
        $this->transaction_status = false;
        return $this->db->query("BEGIN TRANSACTION");
    }

    /**
     * Commit Transaction
     *
     * @return bool
     */
    public function commit()
    {
        $query = $this->db->query("COMMIT");
        if ($query) {
            $this->transaction_status = true;
        }
    }

    /**
     * Rollback Transaction
     *
     * @return bool
     */
    public function rollback()
    {
        return $this->db->query("ROLLBACK");
    }

    /**
     * @return mixed
     */
    public function transactionStatus()
    {
        return $this->transaction_status;
    }

    /**
     * Get Insert Id
     *
     * @return int|string
     */
    public function insertId()
    {
        return $this->db->lastInsertRowID();
    }

    /**
     * Get Affected Rows
     *
     * @return int
     */
    public function affectedRows()
    {
        return $this->db->changes();
    }

    /**
     * Create Table
     *
     * @param $table
     * @param $fields
     * @return bool
     */
    public function createTable($table, $fields)
    {
        if ($table != null && $fields != null) {
            $sql = 'CREATE TABLE IF NOT EXISTS ' . $table . ' (';
            $_i = 0;
            foreach ($fields as $field) {
                if (is_array($field)) {
                    if ($_i == 0) {
                        $sql .= $field[0] . ' ' . $field[1] . (isset($field[2]) ? ' ' . $field[2] : '');
                    } else {
                        $sql .= ', ' . $field[0] . ' ' . $field[1] . (isset($field[2]) ? ' ' . $field[2] : '');
                    }
                }
                $_i++;
            }
            $sql .= ')';

            if ($this->db->query($sql)) {
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
    public function dropTable($table)
    {
        $sql = 'DROP TABLE IF EXISTS ' . $table;
        if ($this->db->query($sql)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Set table
     *
     * @param string $table
     * @return $this
     */
    public function table($table)
    {
        $this->queryBuilder->table($table);
        return $this;
    }

    /**
     * Select table fields
     *
     * @param array $columns
     * @return $this
     */
    public function select($columns = null)
    {
        $this->queryBuilder->select($columns);
        return $this;
    }

    /**
     * Join with other table
     *
     * @param string $table
     * @param string $key1
     * @param null|string $condition
     * @param null|string $key2
     * @return $this
     */
    public function join($table, $key1, $condition = null, $key2 = null)
    {
        $this->queryBuilder->join($table, $key1, $condition, $key2);
        return $this;
    }

    /**
     * Inner Join with other table
     *
     * @param string $table
     * @param string $key1
     * @param null|string $condition
     * @param null|string $key2
     * @return $this
     */
    public function innerJoin($table, $key1, $condition = null, $key2 = null)
    {
        $this->queryBuilder->innerJoin($table, $key1, $condition, $key2);
        return $this;
    }

    /**
     * Outer Join with other table
     *
     * @param string $table
     * @param string $key1
     * @param null|string $condition
     * @param null|string $key2
     * @return $this
     */
    public function outerJoin($table, $key1, $condition = null, $key2 = null)
    {
        $this->queryBuilder->outerJoin($table, $key1, $condition, $key2);
        return $this;
    }

    /**
     * Where condition
     *
     * @param $field
     * @param $valueOrCondition
     * @param null $value
     * @return $this
     */
    public function where($field, $valueOrCondition, $value = null)
    {
        $this->queryBuilder->where($field, $valueOrCondition, $value);
        return $this;
    }

    /**
     * Or Where condition
     *
     * @param string $field
     * @param string $valueOrCondition
     * @param null|string $value
     * @return $this
     */
    public function orWhere($field, $valueOrCondition, $value = null)
    {
        $this->queryBuilder->orWhere($field, $valueOrCondition, $value);
        return $this;
    }

    /**
     * Having condition
     *
     * @param string $field
     * @param string $valueOrCondition
     * @param null|string $value
     * @return $this
     */
    public function having($field, $valueOrCondition, $value = null)
    {
        $this->queryBuilder->having($field, $valueOrCondition, $value);
        return $this;
    }

    /**
     * Or Having condition
     *
     * @param string $field
     * @param string $valueOrCondition
     * @param null|string $value
     * @return $this
     */
    public function orHaving($field, $valueOrCondition, $value = null)
    {
        $this->queryBuilder->orHaving($field, $valueOrCondition, $value);
        return $this;
    }

    /**
     * Order result
     *
     * @param string $field
     * @param string $order
     * @return $this
     */
    public function orderBy($field, $order = 'ASC')
    {
        $this->queryBuilder->orderBy($field, $order);
        return $this;
    }

    /**
     * Limit result
     *
     * @param int $start
     * @param null|int $length
     * @return $this
     */
    public function limit($start, $length = null)
    {
        $this->queryBuilder->limit($start, $length);
        return $this;
    }

    /**
     * Get all data
     *
     * @return array|null
     */
    public function get()
    {
        $sql = $this->queryBuilder->get();
        return $sql != null ? $this->getAllQuery($sql) : null;
    }

    /**
     * Get generated SQL
     *
     * @return string
     */
    public function getSQL()
    {
        return $this->queryBuilder->get();
    }

    /**
     * Get first data
     *
     * @return null|object
     */
    public function first()
    {
        $sql = $this->queryBuilder->get();
        return $sql != null ? $this->getFirstQuery($sql) : null;
    }

    /**
     * Get data count
     *
     * @return int
     */
    public function count()
    {
        $sql = $this->queryBuilder->count();
        return $sql != 0 ? $this->getCountQuery($sql) : 0;
    }

}
