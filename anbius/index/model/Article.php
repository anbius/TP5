<?php
namespace app\index\model;

use think\Model;
//use think\Loader;

class Article extends Model{

 protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
        //parentent
        //TODO:自定义的初始化
    }

}



?>