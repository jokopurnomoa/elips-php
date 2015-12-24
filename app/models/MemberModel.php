<?php

class MemberModel extends Model {

    static $table = 'gxa_members';

    public static function init(){
        parent::setTable(MemberModel::$table);
        parent::setFieldId('member_id');
    }

}
