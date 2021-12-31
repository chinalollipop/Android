<?php  
if ( function_exists("date_default_timezone_set")) date_default_timezone_set ("Etc/GMT+4");


//ini_set('display_errors', 1);//error_reporting(E_ALL );

//echo ROOT_DIR; // /www/huangguan/hg3088
 define('APPPATH', ROOT_DIR . '/codeIgniter/application/');
 define('SYSPATH', ROOT_DIR . '/codeIgniter/system/');
// 验证方法
//include ROOT_DIR.'/codeIgniter/function.php';
include SYSPATH . 'core/Function.php';
// 引进CI_Security类
include SYSPATH . 'core/Security.php';
// 验证基类是否定义
include SYSPATH . 'core/Common.php';

//添加防止SQL语句注入的代码
$security = new CI_Security();

//总入口变量过滤，daddslashes函数已废弃
$_GET = daddslashes_new($_GET, $security);
$_POST = daddslashes_new($_POST, $security);
$_COOKIE = daddslashes_new($_COOKIE, $security);
$_SERVER = daddslashes_new($_SERVER, $security);
$_FILES = daddslashes_new($_FILES, $security);
$_REQUEST = daddslashes_new($_REQUEST, $security);

//sql injection check
mysql_injection_check($_GET, $security);
mysql_injection_check($_POST, $security);
mysql_injection_check($_COOKIE, $security);
mysql_injection_check($_FILES, $security);
mysql_injection_check($_REQUEST, $security);


//危险字符串定义
$evilWords = array(
		array('select', 'from'),
		array('select', 'unhex'),
		array('select', 'char'),
		array('delete', 'from'),
		array('update', 'set'),
		array('insert', 'into'),
		array('replace', 'into'),
		array('information_schema'),
);

//递归检测请求数据中的危险字符串
sanitizeRequestRecursive($_REQUEST, $evilWords);

/* End of file CodeIgniter.php */
/* Location: ./system/core/CodeIgniter.php */
