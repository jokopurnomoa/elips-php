<?php

class ExampleModel extends Model {

    static $table = 'gxa_members';

    public static function init(){
        parent::setTable(self::$table);
        parent::setFieldId('member_id');
    }

    public static function getAll($where = null, $order = null, $limit = null){
        return Database::getAll(self::$table, $where, $order, $limit);
    }

}
