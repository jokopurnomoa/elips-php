<?php
/**
 * Created by PhpStorm.
 * User: jokopurnomoa
 * Date: 12/24/15
 * Time: 12:02 PM
 */

use JasonGrimes\Paginator;
use Gregwar\Captcha\CaptchaBuilder;

class TestingController extends BaseController
{

    public function index()
    {
        Load::library('DB');
        Load::model('Member');

        //$members = Member::all('*', 10);
        //$members = Member::where('email', 'LIKE', '%joko%')->get();
        //$members = Member::first();
        $members = Member::byId('M1411190001');

        echo '<pre>';
        print_r($members);
        echo '</pre>';

        Member::where('member_id', 'M1411190001')
            ->update(array(
                'postcode' => '41234'
            ));
    }

    public function ajax()
    {
        Blade::render('ajax');
    }

    public function cache()
    {

        Load::library('DB');

        Benchmark::startTime('get_data');

        $member_list = Cache::get('member_list_cache');

        if($member_list == null){
            echo 'NO CACHE';
            $member_list = DB::table('member')
                ->limit(100)
                ->get();

            Cache::store('member_list_cache', $member_list);
        }

        echo nl2br(PHP_EOL);
        echo 'Get data time : ' . Benchmark::getTime('get_data', 5) . ' s';

        //Cache::delete('member_list_cache');
        echo '<pre>';
        print_r($member_list);
        echo '</pre>';
    }

    public function database()
    {
        Load::library('DB');

        DB::beginTransaction();
        DB::insert('test', array('val1' => 'A', 'val2' => 'B'));
        $insert_id = DB::insertId();
        DB::update('test', 'test_id', $insert_id - 1, array('val1' => 'A2', 'val2' => 'B2'));
        DB::delete('test', 'test_id', $insert_id - 3);
        DB::commit();
    }

    public function database2()
    {
        Load::library('DB');

        $sql = "SELECT * FROM member WHERE email = ? AND name LIKE ?";
        $data = DB::getAllQuery($sql, array('jokopurnomoa@gmail.com', '%Elips%'));
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }

    public function database3()
    {
        Load::library('DB');

        $data = DB::table('member')
            ->join('member_category', 'catm_id')
            ->select(array('*'))
            ->select('YEAR(CURRENT_TIMESTAMP) - YEAR(birthdate) - (RIGHT(CURRENT_TIMESTAMP, 5) < RIGHT(birthdate, 5)) as age')
            ->where('active', 1)
            ->where('name', 'LIKE', '%\'Joko%')
            ->orWhere('name', 'LIKE', '%Purnomo%')
            ->having('age', '>', 20)
            ->orderBy('name', 'DESC')
            ->orderBy('age', 'ASC')
            ->limit(10)
            ->get();

        echo '<pre>';
        print_r($data);
        echo '</pre>';

        DB::table('member_category')
            ->insert(array(
                'catm_id' => '6',
                'category' => 'AAA'
            ));

        DB::table('member_category')
            ->where('catm_id', '6')
            ->update(array(
                'category' => 'ZZZ'
            ));

        DB::table('member_category')
            ->where('catm_id', '6')
            ->delete();
    }

    public function database4()
    {
        Load::library('DB');

        $db2 = DB::getInstance(get_app_config('db', 'optional'));
        $db2->createTable('member', array(
            array('member_id', 'string(20) PRIMARY KEY'),
            array('name', 'string(50)')
        ));

        $db2->table('member')
            ->insert(array(
                'member_id' => 'M00001',
                'name' => 'Joko Purnomo A'
            ));

        $members = $db2->table('member')->get();
        echo '<pre>';
        print_r($members);
        echo '</pre>';
    }

    public function sqlite()
    {
        Load::library('DB');

        DB::createTable('member', array(
            array('member_id', 'VARCHAR(40)', 'PRIMARY KEY'),
            array('name', 'VARCHAR(100)'),
            array('birthdate', 'DATE'),
            array('gender', 'CHARACTER(1)'),
            array('address', 'VARCHAR(255)'),
            array('district_id', 'INT(11)'),
            array('postcode', 'VARCHAR(10)'),
            array('phone', 'VARCHAR(20)'),
            array('email', 'VARCHAR(100)'),
            array('image', 'VARCHAR(255)'),
            array('username', 'VARCHAR(100)'),
            array('password', 'VARCHAR(255)'),
            array('resgisterdate', 'DATETIME'),
            array('expired_date', 'DATETIME'),
            array('catm_id', 'INT(4)')
        ));

        //Database::insert('member', array('member_id' => 'M00001', 'name' => 'Joko Purnomo A'));
        DB::getCountQuery('SELECT * FROM member');
        $data = DB::getFirstField('member', array('member_id', 'name'));
        print_r($data);
    }

    public function email()
    {
        Load::library('Email');

        Email::host('smtp.gmail.com');
        Email::username('email@gmail.com');
        Email::password('secret');
        Email::encryption('ssl');
        Email::port(465);

        Email::from('email@gmail.com', 'Name');
        Email::to('jokopurnomoa@gmail.com');

        Email::html(true);
        Email::subject('Subject Test');
        Email::message('Message Test');
        Email::send();

        print_r(Email::getSendingMessage());
    }

    public function session()
    {
        Load::library('Session');

        Session::set('name', 'Joko');
        Session::set('email', 'jokopurnomoa@gmail.com');
        Session::set('address', 'Jl Dipatiukur No. 5 Bandung');

        echo Session::get('name');
        echo PHP_EOL;
        echo Session::get('email');
        echo PHP_EOL;
        echo Session::get('address');
    }

    public function sessionDestroy()
    {
        Load::library('Session');

        Session::destroy();
    }

    public function methodGet()
    {
        echo get_input('name');
    }

    public function memory()
    {
        echo Benchmark::memoryUsage();
    }

    public function sanitize()
    {
        Load::library('Sanitize');
        Load::library('Validate');

        $email = Sanitize::email('jokopurnomoa@gmail.com');
        if(Validate::email($email)){
            echo $email;
        }

        $string = Sanitize::float('123.98');
        if(Validate::float($string)){
            echo $string;
        }
    }

    public function cookie()
    {
        Load::library('Cookie');

        Cookie::set('test', 'AAA');
        Cookie::delete('test');
        echo Cookie::get('test');

    }

    public function showMessage()
    {
        echo 'This is a test message';
    }

    public function resizeImage()
    {
        Load::library('ImageLib');

        ImageLib::setConfig(array(
            'source_image' => './storage/the-lorax.jpg',
            'new_image' => './storage/the-lorax-thumb.jpg',
            'create_thumb' => true,
            'maintain_ratio' => true,
            'width' => 500,
            'height' => 500
        ));

        ImageLib::resize();
    }

    public function security()
    {
        Load::library('Session');
        Security::generateCSRFToken('test');
        echo Security::getCSRFToken('test');
        echo '<br>';
        echo Security::xssFilter('<script>alert("malicious code");</script>');
    }

    public function curlGet()
    {
        echo '<pre>';
        echo 'ID   : ' . Input::get('id');
        echo '<br>';
        echo 'Name : ' . Input::get('name');
        echo '</pre>';
    }

    public function curlPost()
    {
        echo '<pre>';
        echo 'ID   : ' . Input::post('id');
        echo '<br>';
        echo 'Name : ' . Input::post('name');
        echo '</pre>';
    }

    public function curl()
    {
        Load::library('CURL');

        echo 'CURL get<br>';
        echo CURL::get(URI::baseUrl() . 'testing/curl_get?id=100&name=Joko Purnomo A');
        echo '<br><br>CURL post<br>';
        echo CURL::post(URI::baseUrl() . 'testing/curl_post', array('id' => 200, 'name' => 'Joko Purnomo A'));
    }

    public function pagination()
    {
        require MAIN_PATH . 'vendor/jasongrimes/paginator/src/JasonGrimes/Paginator.php';

        $totalItems = 100000;
        $itemsPerPage = 50;
        $currentPage = 1900;
        $urlPattern = '/foo/page/(:num)';

        $paginator = new Paginator($totalItems, $itemsPerPage, $currentPage, $urlPattern);
        $paginator->setMaxPagesToShow(7);
        echo $paginator;
    }

    public function captcha()
    {
        Load::library('Session');

        require MAIN_PATH . 'vendor/gregwar/captcha/autoload.php';

        $builder = new CaptchaBuilder;
        $builder->build();

        Session::set('captcha', $builder->getPhrase());

        if($builder->testPhrase(Session::get('captcha'))) {
            // instructions if user phrase is good
        }
        else {
            // user phrase is wrong
        }
        header('Content-type: image/jpeg');
        $builder->output();
    }

    public function encryption(){
        echo $cipher = Encryption::encode('Hello');
        echo nl2br(PHP_EOL);
        echo Encryption::decode($cipher);
    }

    public function complex()
    {
        Load::library('DB');
        Load::library('Session');

        $memberList = DB::table('member')
            ->limit(100)
            ->get();

        Cache::store('member_list_cache', $memberList);
        Session::set('member_list', Cache::get('member_list_cache'));
        $this->data['member_list'] = Session::get('member_list');

        DB::beginTransaction();
        DB::insert('test', array('val1' => 'A', 'val2' => 'B'));
        $insert_id = DB::insertId();
        DB::update('test', 'test_id', $insert_id - 1, array('val1' => 'A2', 'val2' => 'B2'));
        DB::delete('test', 'test_id', $insert_id - 3);
        DB::commit();

        $sql = "SELECT * FROM member WHERE email = ? AND name LIKE ?";
        $data = DB::getAllQuery($sql, array('jokopurnomoa@gmail.com', '%Elips%'));

        Blade::render('testing', $this->data);
    }
}
