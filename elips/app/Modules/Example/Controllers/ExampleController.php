<?php
/**
 * Created by PhpStorm.
 * User: jokopurnomoa
 * Date: 12/24/15
 * Time: 12:02 PM
 */

namespace App\Controllers;

use Elips\Libraries\Blade;
use App\Modules\Example\Models\Example;

class ExampleController extends BaseController
{

    public function index()
    {

        $this->data['member_list'] = Example::all('*', 10);
        $this->data['var'] = array('<?php echo "AAA";?>');
        Blade::render('example', $this->data);

    }

}
