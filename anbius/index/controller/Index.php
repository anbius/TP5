<?php
namespace app\index\controller;
use \think\View;
use \think\Db;
use \app\index\model\Article;
class Index
{
    public function index()
    {

        return '<style type="text/css">*{ padding: 0; margin: 0; } .think_default_text{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_bd568ce7058a1091"></thinkad>';
    }

public function render(){

//echo 111;
//die;
//$article = db('article');




/*==================查询============================*/


               /*===设置缓存==========*/
             $h  = db('article')->where('aid','28')->cache('key',60)->find();

              $datas = \think\Cache::get('key');


//dump($data);
//die;
               /*====缓存设置的底部===*/


$article = new Article();
$a = $article -> where('aid','28')->find();

$b = $a->content;

//dump($b);
//die;
/*查询  单条 注意 命名空间*/
$m = Article::getByAid('28');
//dump($m->tojson());

$r = Article::find('42');

//对象转为数组
dump($r->toArray());

//die;

//$m  = Db::table('hd_article')->where('aid','28')->find();
//$m = Db::name('article')->where('aid','28')->find();
//dump($m);
//die;
$b = $m->content;
//dump($m->content);
//die;

//打印sql 语句
//$xx = db('article')->where('title|content','like','%小%')->fetchSql(true)->select();
$xxx = db('article')->where('title|content','like','%小%')->select();

$xx  = Article::getLastSql();
//获取表中所有的信息
$oo = Db::getTableInfo('hd_article');

//dump($oo);
//die;

dump($xx);
die;

$aid = db('article')->count('aid');
//echo $aid;
//die;

$sum = db('article')->sum('cid');

//echo db('article')->fetchSql(true)->avg('cid');
//echo $sum;
//die;


//输出sql  种查询语句
//$q = db('article')->where('time','yesterday')->select(false);
//$q = db('article')->where('time','today')->fetchSql()->select();
$q = db('article')->where('aid','in','40,41,42')->buildSql();

dump($q);
//die;

/*======原生查询========*/

$w = Db::query('select * from hd_article where aid = 40');

//修改

//$w = Db::execute("update hd_article set title='冷湃' where aid =40");
//dump($w);
//die;



/*===================================================*/

/*=====================t添加===============================*/

$data['title'] ='小dos同学';
$data['time']      =time();
$data['content']  = '这确实是一个比较悲伤地故事,眼睛痛'; 

//$id = db('article')->insertGetId($data);
//echo $id;
//die;
//$p = Db::field('cid')->table('hd_article')->union('select cid from hd_article')->union('select cid from hd_catagory')->select();
//dump($p);
//
/*==============================================================*/
/*======================修改====================================*/
	$n = db('article')->where('aid','40')->setField('title','小百 ');
//echo $n ;
//die;

/*==============================================================*/

/*=========================删除=================================*/
	//	db('article')->delete(41);

/*==============================================================*/


/*事务的处理*/

Db::startTrans();
try{
	Db::table('hd_article')->find(40);
	Db::table('hd_article')->delete(40);

	//提交事务
	Db::commit();


}catch(\Exception $e){

throw new  \think\Exception("Error Processing Request", 1);

	//回滚事务
	Db::rollback();
}




/*Db::listen(function($sql,$time,$explain){
echo $sql.'['.$time.'s]';

dump($explain);

}*/




//dump($b);
$view = new View();
$view->assign('name',$b);
$view->assign('email','thinkphp@qq.com');
return $view->fetch('render');

}



}
