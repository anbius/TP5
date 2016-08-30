<?php 
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    '__pattern__' => [      //__pattern__中定义的变量规则我们称之为全局变量规则，在路由规则里面定义的变量规则我们称之为局部变量规则，如果一个变量同时定义了全局规则和局部规则的话，当前的局部规则会覆盖全局规则的
        'name' => '\w+',
    ],
    '[hello]'     => [  //hello开头的并且带参数的访问都会路由到index控制器的hello操作方法
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],

/*测试路由*/
'[art]' => [
        ':year/:month' => ['art/archive', ['method' => 'get'], ['year' => '\d{4}', 'month' => '\d{2}']],    
        ':id'          => ['art/get', ['method' => 'get'], ['id' => '\d+']],
        ':name'        => ['art/read', ['method' => 'get'], ['name' => '\w+']],

        ':xxoo'        =>function($xxoo){
            return 'hello'.$xxoo.'!';
        },
    ],



];
