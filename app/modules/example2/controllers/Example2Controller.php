<?php
/**
 * Created by PhpStorm.
 * User: jokopurnomoa
 * Date: 12/24/15
 * Time: 12:02 PM
 */

namespace App\Modules\Example2\Controllers;

use App\Controllers\BaseController;
use App\Modules\Example2\Models\Example2;

use Elips\Libraries\Blade;

class Example2Controller extends BaseController
{

    public function index()
    {

        $this->data['member_list'] = Example2::all('*', 10);

        //Blade::render('example/example', $this->data);
        Blade::render('example', $this->data);

    }

}
