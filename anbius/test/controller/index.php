<?php
namespace app\test\Controller;

use Think\Request;

class test extends Controller{

public function index(){

$result = Db::connect('db')->query('select * from article where aid=28');

dump($result);
}


}










?>