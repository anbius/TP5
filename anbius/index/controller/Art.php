<?php
namespace app\index\controller;

use think\Request;
class art{
	//控制器类是继承的\think\Controller的话，系统已经自动为你引入了 \traits\controller\Jump，无需再次引入。
	use \traits\controller\Jump; //关页面跳转和重定向方法，

	public function index(){

		// echo $_SERVER['SERVER_ADDR'];  服务端
			echo $_SERVER['HTTP_HOST'];//客户端

			if($_SERVER['HTTP_HOST']=='localhost'){
				$this -> success('hello,蛋蛋','xx');
			}else{
				$this-> error('xxoo,蛋蛋');
			}
	}


public function xx(Request $request){

	//添加Request 参数 
	//echo request()->url();


		$data = ['name' => 'thinkphp', 'status' => '1'];
/*dump($data);
die;*/
	return json($data);
//	return xml($data,201);
}


public function oo($name="dandan"){
	// 无需添加的request对象 （）
//echo 1111;
	//echo request()->url();
	//echo request()->baseurl();

/*dump(request()->param());
dump(input());

dump(request()->get('name'));*/
	//echo request()->baseFile();  ///TP5/index.php

echo '请求方法：' . request()->method() . '<br/>';
        echo '资源类型：' . request()->type() . '<br/>';
        echo '访问地址：' . request()->ip() . '<br/>';
        echo '是否AJax请求：' . var_export(request()->isAjax(), true) . '<br/>';
        echo '请求参数：';
        dump(request()->param());
        echo '请求参数：仅包含name';
        dump(request()->only(['name']));
        echo '请求参数：排除name';
        dump(request()->except(['name']));
    

    // 获取当前域名
        echo 'domain: ' . request()->domain() . '<br/>';
        // 获取当前入口文件
        echo 'file: ' . request()->baseFile() . '<br/>';
        // 获取当前URL地址 不含域名
        echo 'url: ' . request()->url() . '<br/>';
        // 获取包含域名的完整URL地址
        echo 'url with domain: ' . request()->url(true) . '<br/>';
        // 获取当前URL地址 不含QUERY_STRING
        echo 'url without query: ' . request()->baseUrl() . '<br/>';
        // 获取URL访问的ROOT地址
        echo 'root:' . request()->root() . '<br/>';
        // 获取URL访问的ROOT地址
        echo 'root with domain: ' . request()->root(true) . '<br/>';
        // 获取URL地址中的PATH_INFO信息
        echo 'pathinfo: ' . request()->pathinfo() . '<br/>';
        // 获取URL地址中的PATH_INFO信息 不含后缀
        echo 'pathinfo: ' . request()->path() . '<br/>';
        // 获取URL地址中的后缀信息
        echo 'ext: ' . request()->ext() . '<br/>';

        return 'Hello,' . $name . '！';


}

public function HelloWorld(){

	echo 'hello world!';

	echo url('art/read?name=xxoo');
}

public function get($id){
	return '查看id='.$id.'的内容';
}

public function read($name){

	return  '查看name='.$name.'内容';
}

public function archive($year, $month)
    {
        return '查看' . $year . '/' . $month . '的归档内容';
    }


public function xxoo($xxoo){
    echo  1111;
}
}




?>