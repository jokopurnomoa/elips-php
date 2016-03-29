<?php
/**
 * QueryBilder library
 *
 */

namespace Elips\Libraries\DBDriver\QueryBuilder;

class SQLiteQueryBuilder
{

    private $db = null;
    private $table = '';
    private $joins = array();
    private $columns = array('*');
    private $wheres = array();
    private $havings = array();
    private $orders = array();
    private $limit = '';

    public function __construct($db = '')
    {
        $this->db = $db;
    }

    /**
     * Set db
     *
     * @param $db
     */
    public function setDB($db)
    {
        $this->db = $db;
    }

    /**
     * Set table
     *
     * @param string $table
     * @return $this
     */
    public function table($table)
    {
        $this->table = $table;
        return $this;
    }

    /**
     * Select table field
     *
     * @param array $columns
     * @return $this
     */
    public function select($columns = null)
    {
        if ($columns != null) {
            if (is_array($columns)) {
                foreach ($columns as $row) {
                    if (!in_array($row, $this->columns)) {
                        $this->columns[] = $row;
                    }
                }
            } else {
                if (!in_array($columns, $this->columns)) {
                    $this->columns[] = $columns;
                }
            }
        }

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
        if ($condition != null && $key2 != null) {
            $this->joins[] = 'JOIN ' . $table . ' ON ' . $key1 . ' ' . $condition . ' ' . $key2 . ' ';
        } else {
            $this->joins[] = 'JOIN ' . $table . ' USING(' . $key1 . ') ';
        }

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
        if ($condition != null && $key2 != null) {
            $this->joins[] = 'INNER JOIN ' . $table . ' ON ' . $key1 . ' ' . $condition . ' ' . $key2 . ' ';
        } else {
            $this->joins[] = 'INNER JOIN ' . $table . ' USING(' . $key1 . ') ';
        }

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
        if ($condition != null && $key2 != null) {
            $this->joins[] = 'OUTER JOIN ' . $table . ' ON ' . $key1 . ' ' . $condition . ' ' . $key2 . ' ';
        } else {
            $this->joins[] = 'OUTER JOIN ' . $table . ' USING(' . $key1 . ') ';
        }

        return $this;
    }

    /**
     * Where condition
     *
     * @param string $field
     * @param string $valueOrCondition
     * @param null|string $value
     * @return $this
     */
    public function where($field, $valueOrCondition, $value = null)
    {
        if ($value === null) {
            $this->wheres[] = array(
                'condition' => 'AND',
                'value' => $field . ' = ' . '\''. $this->escape($valueOrCondition) .'\''
            );
        } else {
            $this->wheres[] = array(
                'condition' => 'AND',
                'value' => $field . ' ' . $valueOrCondition . ' \''. $this->escape($value) .'\''
            );
        }

        return $this;
    }

    /**
     * Or Where condition
     *
     * @param $field
     * @param $valueOrCondition
     * @param null $value
     * @return $this
     */
    public function orWhere($field, $valueOrCondition, $value = null)
    {
        if ($value === null) {
            $this->wheres[] = array(
                'condition' => 'OR',
                'value' => $field . ' = ' . '\''. $this->escape($valueOrCondition) .'\''
            );
        } else {
            $this->wheres[] = array(
                'condition' => 'OR',
                'value' => $field . ' ' . $valueOrCondition . ' \''. $this->escape($value) .'\''
            );
        }

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
        if ($value === null) {
            $this->havings[] = array(
                'condition' => 'AND',
                'value' => $field . ' = ' . '\''. $this->escape($valueOrCondition) .'\''
            );
        } else {
            $this->havings[] = array(
                'condition' => 'AND',
                'value' => $field . ' ' . $valueOrCondition . ' \''. $this->escape($value) .'\''
            );
        }

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
        if ($value === null) {
            $this->havings[] = array(
                'condition' => 'OR',
                'value' => $field . ' = ' . '\''. $this->escape($valueOrCondition) .'\''
            );
        } else {
            $this->havings[] = array(
                'condition' => 'OR',
                'value' => $field . ' ' . $valueOrCondition . ' \''. $this->escape($value) .'\''
            );
        }

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
        $this->orders[] = $field . ' ' . $order;
        return $this;
    }

    /**
     * Limit result
     *
     * @param string $start
     * @param null|int $length
     * @return $this
     */
    public function limit($start, $length = null)
    {
        if ($length === null) {
            $this->limit = (int)$start;
        } else {
            $this->limit = (int)$start . ',' . (int)$length;
        }

        return $this;
    }

    /**
     * Generate get all SQL
     *
     * @return string
     */
    public function get()
    {
        $sql = 'SELECT ';

        if (is_array($this->columns) && $this->columns !== null) {
            $this->columns = implode(',', $this->columns);
        }

        $sql .= $this->columns . ' FROM ' . $this->table . ' ' . implode(' ', $this->joins);

        $where = '';
        if ($this->wheres != null) {
            foreach ($this->wheres as $val) {
                if ($where === '') {
                    if (is_array($val)) {
                        $where .= 'WHERE ' . $val['value'];
                    }
                } else {
                    if ($val['condition'] === 'AND') {
                        $where .= ' AND ' . $val['value'];
                    } elseif ($val['condition'] === 'OR') {
                        $where .= ' OR ' . $val['value'];
                    }
                }
            }
        }

        $sql .= $where;

        $having = '';
        if ($this->havings != null) {
            foreach ($this->havings as $val) {
                if ($having === '') {
                    if (is_array($val)) {
                        $having .= ' HAVING ' . $val['value'];
                    }
                } else {
                    if ($val['condition'] === 'AND') {
                        $having .= ' AND ' . $val['value'];
                    } elseif ($val['condition'] === 'OR') {
                        $having .= ' OR ' . $val['value'];
                    }
                }
            }
        }

        $sql .= $having;

        if ($this->orders != null) {
            $sql .= ' ORDER BY ' . implode(',', $this->orders);
        }

        if ($this->limit != '') {
            $sql .= ' LIMIT ' . $this->limit;
        }

        $this->table = '';
        $this->joins = array();
        $this->columns = array('*');
        $this->wheres = array();
        $this->havings = array();
        $this->orders = array();
        $this->limit = '';

        return $sql;
    }

    /**
     * Generate get count SQL
     *
     * @return string
     */
    public function count()
    {
        $sql = 'SELECT ';

        if (is_array($this->columns) && $this->columns !== null) {
            $this->columns = implode(',', $this->columns);
        }

        $sql .= $this->columns . ' FROM ' . $this->table . ' ' . implode(' ', $this->joins);

        $where = '';
        if ($this->wheres != null) {
            foreach ($this->wheres as $val) {
                if ($where === '') {
                    if (is_array($val)) {
                        $where .= 'WHERE ' . $val['value'];
                    }
                } else {
                    if ($val['condition'] === 'AND') {
                        $where .= ' AND ' . $val['value'];
                    } elseif ($val['condition'] === 'OR') {
                        $where .= ' OR ' . $val['value'];
                    }
                }
            }
        }

        $sql .= $where;

        $having = '';
        if ($this->havings != null) {
            foreach ($this->havings as $val) {
                if ($having === '') {
                    if (is_array($val)) {
                        $having .= ' HAVING ' . $val['value'];
                    }
                } else {
                    if ($val['condition'] === 'AND') {
                        $having .= ' AND ' . $val['value'];
                    } elseif ($val['condition'] === 'OR') {
                        $having .= ' OR ' . $val['value'];
                    }
                }
            }
        }

        $sql .= $having;

        $this->table = '';
        $this->joins = array();
        $this->columns = array('*');
        $this->wheres = array();
        $this->havings = array();

        return $sql;
    }

    /**
     * Generate insert SQL
     *
     * @param $data
     * @return null|string
     */
    public function insert($data)
    {
        if ($this->table != '' && $data != null) {
            $sql = 'INSERT INTO ' . $this->table . ' ';
            $i = 0;
            $fields = "(";
            $values = "(";
            foreach ($data as $key => $val) {
                if ($i === 0) {
                    $fields .= $key;
                    $values .= "'" . $this->escape($val) . "'";
                } else {
                    $fields .= ',' . $key;
                    $values .= ",'" . $this->escape($val) . "'";
                }

                $i++;
            }
            $fields .= ")";
            $values .= ")";

            $sql .= $fields . " VALUES " . $values;

            $this->table = '';

            return $sql;
        }
        return null;
    }

    /**
     * Generate update SQL
     *
     * @param $data
     * @return null|string
     */
    public function update($data)
    {
        if ($this->table != '' && $this->wheres != null && $data != null) {
            $where = '';
            if ($this->wheres != null) {
                foreach ($this->wheres as $val) {
                    if ($where === '') {
                        if (is_array($val)) {
                            $where .= 'WHERE ' . $val['value'];
                        }
                    } else {
                        if ($val['condition'] === 'AND') {
                            $where .= ' AND ' . $val['value'];
                        } elseif ($val['condition'] === 'OR') {
                            $where .= ' OR ' . $val['value'];
                        }
                    }
                }
            }

            $sql = 'UPDATE ' . $this->table . ' SET ';
            $_i = 0;
            foreach ($data as $key => $val) {
                if ($_i === 0) {
                    $sql .= "$key = '" . $this->escape($val) . "' ";
                } else {
                    $sql .= ",$key = '" . $this->escape($val) . "' ";
                }

                $_i++;
            }

            $sql .= $where;

            if ($this->limit != '') {
                if ($this->limit != '-1') {
                    $sql .= ' LIMIT ' . $this->limit;
                }
            } else {
                $sql .= ' LIMIT 1';
            }

            $this->table = '';
            $this->wheres = array();
            $this->limit = '';

            return $sql;
        }
        return null;
    }

    /**
     * Generate delete SQL
     *
     * @return null|string
     */
    public function delete()
    {
        if ($this->table != '' && $this->wheres != null) {
            $where = '';
            if ($this->wheres != null) {
                foreach ($this->wheres as $val) {
                    if ($where === '') {
                        if (is_array($val)) {
                            $where .= 'WHERE ' . $val['value'];
                        }
                    } else {
                        if ($val['condition'] === 'AND') {
                            $where .= ' AND ' . $val['value'];
                        } elseif ($val['condition'] === 'OR') {
                            $where .= ' OR ' . $val['value'];
                        }
                    }
                }
            }

            $sql = 'DELETE FROM ' . $this->table . ' ' . $where;
            if ($this->limit != '') {
                if ($this->limit != '-1') {
                    $sql .= ' LIMIT ' . $this->limit;
                }
            } else {
                $sql .= ' LIMIT 1';
            }

            $this->table = '';
            $this->wheres = array();
            $this->limit = '';

            return $sql;
        }
        return null;
    }

    /**
     * Escape data
     *
     * @param $string
     * @return string
     */
    private function escape($string)
    {
        return $this->db->escapeString($string);
    }

}
