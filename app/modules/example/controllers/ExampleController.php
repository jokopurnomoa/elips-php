<?php
/**
 * Created by PhpStorm.
 * User: jokopurnomoa
 * Date: 12/24/15
 * Time: 12:02 PM
 */

class Example extends BaseController
{

    public function index()
    {

        Load::library('Database');
        Load::model('Example');

        $this->data['member_list'] = Example::all('*', 10);
        $this->data['var'] = array('<?php echo "AAA";?>');
        Blade::render('example', $this->data);

    }

}
