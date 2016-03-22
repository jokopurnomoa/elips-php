<?php
/**
 * Created by PhpStorm.
 * User: jokopurnomoa
 * Date: 12/24/15
 * Time: 12:02 PM
 */

class Example extends Base {

    public function index(){

        Load::library('Database');
        Load::model('ExampleModel');

        $this->data['member_list'] = ExampleModel::getAll(null, null, 10);
        $this->data['var'] = array('<?php echo "AAA";?>');
        Blade::render('example', $this->data);

    }

}
