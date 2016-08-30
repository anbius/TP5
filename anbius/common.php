
<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------
// 应用公共文件
/**
 * @name 输出json的数据
 * @desc 输出状态码，及json数据
 * @param $code 状态码，200为成功，300为未授权，400为失败   
 * @param $data 数据
 * @param $ticket 验证
 * @return $result 结果
 */
function getOutData($retcode = 400,$data = "",$ticket = ''){
    if(!is_numeric($retcode)) {
        return '';
    }
    $result['retcode'] = $retcode;
    if($data)	$result['data'] = $data;
    if($ticket)	$result['ticket'] = $ticket;
	return $result;
}


// 应用公共文件
function jsons($data = "",$ticket = ''){
   
    if($data)	$result['data'] = $data;
    if($ticket)	$result['ticket'] = $ticket;
    echo urldecode(json_encode($result));
    exit;
}
/**
 * 获取和设置配置参数 支持批量定义
 * @param string|array $name 配置变量
 * @param mixed $value 配置值
 * @param mixed $default 默认值
 * @return mixed
 */
function C($name = null, $value = null, $default = null) {
    // 无参数时获取所有
    if (empty($name)) {
        return \think\Config::get();
    }
    // 优先执行设置获取或赋值
    if (is_string($name)) {
        if (defined($name)) {
            return constant($name);
        }
        if (strpos($name, '.')) {
            // 二维数组设置和获取支持
            $name = explode('.', $name);
            $name[0] = strtoupper($name[0]);
            $name = implode('.', $name);
        } else {
            $name = strtoupper($name);
        }
        if (is_null($value)) {
            $result = \think\Config::get($name);
            return is_null($result) ? $default : $result;
        }
        \think\Config::set($name, $value);
        return null;
    }
    // 批量设置
    if (is_array($name)) {
        \think\Config::set($name);
        return null;
    }
    return null; // 避免非法参数
}

/**
 * 抛出异常处理
 * @param string $msg 异常消息
 * @param integer $code 异常代码 默认为0
 * @throws Think\Exception
 * @return void
 */
function E($msg, $code = 0) {
    throw new think\Exception($msg, $code);
}

/**
 * 获取输入数据 支持默认值和过滤
 * @param string $key 获取的变量名
 * @param mixed $default 默认值
 * @param string $filter 过滤方法
 * @param bool $merge 是否合并系统默认过滤方法
 * @return mixed
 */
function I($name, $default = '', $filter = null, $datas = null) {
    return input($name, $default, $filter, $datas);
}

function array_map_recursive($filter, $data) {
    $result = array();
    foreach ($data as $key => $val) {
        $result[$key] = is_array($val) ? array_map_recursive($filter, $val) : call_user_func($filter, $val);
    }
    return $result;
}

/**
 * 字符串命名风格转换
 * type 0 将Java风格转换为C的风格 1 将C风格转换为Java的风格
 * @param string $name 字符串
 * @param integer $type 转换类型
 * @return string
 */
function parse_name($name, $type = 0) {
    if ($type) {
        return ucfirst(preg_replace_callback('/_([a-zA-Z])/', function($match) { return strtoupper($match[1]); }, $name));
    } else {
        return strtolower(trim(preg_replace("/[A-Z]/", "_\\0", $name), "_"));
    }
}

/**
 * 实例化模型类 格式 [资源://][模块/]模型
 * @param string $name 资源地址
 * @param string $layer 模型层名称
 * @return Model
 */
function D($name = '' , $model = 'model') {	
	$app	= config('app_namespace' );
	$module = request()->module();
	$class  = '\\'.$app.'\\'.$module.'\\'.$model.'\\'.$name;	
	$m = '';
	if(class_exists($class)){
		eval("\$m = new ".$class.';');
	}else{
		$m = db($name);
	}
	return $m;
}

/**
 * 实例化一个没有模型文件的Model
 * @param string $name Model名称
 * @return Think\Model
 */
function M($name = '') {
    return db($name);
}

/**
 * 缓存管理
 * @param mixed $name 缓存名称，如果为数组表示进行缓存设置
 * @param mixed $value 缓存值
 * @param mixed $options 缓存参数
 * @return mixed
 */
function S($name, $value = '', $options = null) {
    if ('' === $value) { // 获取缓存
        return \think\Cache::get($name);
    } elseif (is_null($value)) { // 删除缓存
        return \think\Cache::rm($name);
    } else { // 缓存数据
        if (is_array($options)) {
            $expire = isset($options['expire']) ? $options['expire'] : NULL;
        } else {
            $expire = is_numeric($options) ? $options : NULL;
        }
        return \think\Cache::set($name, $value, $expire);
    }
}
/**
 * 快速文件数据读取和保存 针对简单类型数据 字符串、数组
 * @param string $name 缓存名称
 * @param mixed $value 缓存值
 * @param string $path 缓存路径
 * @return mixed
 */
function F($name, $value = '', $path = DATA_PATH) {
    static $_cache = array();
    $filename = $path . $name . '.php';
    if ('' !== $value) {
        if (is_null($value)) {
            // 删除缓存
            if (false !== strpos($name, '*')) {
                return false; // TODO
            } else {
                unset($_cache[$name]);
                return Think\Storage::unlink($filename, 'F');
            }
        } else {
            Think\Storage::put($filename, serialize($value), 'F');
            // 缓存数据
            $_cache[$name] = $value;
            return null;
        }
    }
    // 获取缓存数据
    if (isset($_cache[$name]))
        return $_cache[$name];
    if (Think\Storage::has($filename, 'F')) {
        $value = unserialize(Think\Storage::read($filename, 'F'));
        $_cache[$name] = $value;
    } else {
        $value = false;
    }
    return $value;
}

function U($url = '', $vars = '', $suffix = true, $domain = false) {
    return \think\Url::build($url, $vars, $suffix, $domain);
}

/**
 * 解析资源地址并导入类库文件
 * 例如 module/controller addon://module/behavior
 * @param string $name 资源地址 格式：[扩展://][模块/]资源名
 * @param string $layer 分层名称
 * @param integer $level 控制器层次
 * @return string
 */
function parse_res_name($name, $layer, $level = 1) {
    if (strpos($name, '://')) {// 指定扩展资源
        list($extend, $name) = explode('://', $name);
    } else {
        $extend = '';
    }
    if (strpos($name, '/') && substr_count($name, '/') >= $level) { // 指定模块
        list($module, $name) = explode('/', $name, 2);
    } else {
        $module = defined('MODULE_NAME') ? MODULE_NAME : '';
    }
    $array = explode('/', $name);
    $class = C('APP_NAMESPACE') . '\\' . $module . '\\' . $layer;
    foreach ($array as $name) {
        $class .= '\\' . parse_name($name, 1);
    }
    // 导入资源类库
    if ($extend) { // 扩展资源
        $class = $extend . '\\' . $class;
    }
    return $class; // . $layer;
}
if (!function_exists('array_column')) {
    function array_column(array $input, $columnKey, $indexKey = null) {
        $result = array();
        if (null === $indexKey) {
            if (null === $columnKey) {
                $result = array_values($input);
            } else {
                foreach ($input as $row) {
                    $result[] = $row[$columnKey];
                }
            }
        } else {
            if (null === $columnKey) {
                foreach ($input as $row) {
                    $result[$row[$indexKey]] = $row;
                }
            } else {
                foreach ($input as $row) {
                    $result[$row[$indexKey]] = $row[$columnKey];
                }
            }
        }
        return $result;
    }
}
// 不区分大小写的in_array实现
function in_array_case($value, $array) {
    return in_array(strtolower($value), array_map('strtolower', (array) $array));
}
/**
 * 获取客户端IP地址
 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @param boolean $adv 是否进行高级模式获取（有可能被伪装）
 * @return mixed
 */
function get_client_ip($type = 0, $adv = false) {
    $type = $type ? 1 : 0;
    static $ip = NULL;
    if ($ip !== NULL)
        return $ip[$type];
    if ($adv) {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos = array_search('unknown', $arr);
            if (false !== $pos)
                unset($arr[$pos]);
            $ip = trim($arr[0]);
        }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u", ip2long($ip));
    $ip = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}

