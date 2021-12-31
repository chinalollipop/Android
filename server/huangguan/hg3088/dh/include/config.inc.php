<?php

define("ROOT_DIR",  dirname(dirname(dirname(__FILE__))));	
include ROOT_DIR.'/common/database.php';
include ROOT_DIR.'/common/config.php';
include ROOT_DIR.'/common/function.php';
require_once "redis.php";

$redisObj = new Ciredis();

// http 域名配置
$datajson_url = $redisObj->getSimpleOne('http_ts_url'); // 取redis 设置的值
$datastr_url = json_decode($datajson_url,true) ;
//定义使用 HTTP 的网址
if(!defined("HTTPS_WEBSITE")) {
    define("HTTPS_WEBSITE", $datastr_url['http_url']);
}

if(isset($_SERVER['HTTP_HOST']) && !empty($_SERVER['HTTP_HOST']) ) {
    $mainhost=getMainHostThis();
    if(strpos(HTTPS_WEBSITE, $mainhost) === false) { // https
        if(!defined("HTTPS_HEAD")) {
            define("HTTPS_HEAD", "https");
        }
        if(!defined("WS_HEAD")) {
            define("WS_HEAD", "wss");
        }
        if(!defined("WEBSOCKET_IP")) {
            define("WEBSOCKET_IP", $datastr_url['ts_https_url']);
        }
    }else { // http
        if(!defined("HTTPS_HEAD")) {
            define("HTTPS_HEAD", "http");
        }
        if(!defined("WS_HEAD")) {
            define("WS_HEAD", "ws");
        }
        if(!defined("WEBSOCKET_IP")) {
            define("WEBSOCKET_IP", $datastr_url['ts_http_url']);
        }
    }
}


//SQL注入开关    SAFE_INJECTION
if(SAFE_INJECTION) {
	include ROOT_DIR.'/codeIgniter/injection.php';
}

class Dbnew{
    private static $_instance_m = null;	//该类中的唯一一个实例
    private static $_instance_s = null;	//该类中的唯一一个实例
    private static $_instance_data_m = null;	//该类中的唯一一个实例
    private static $_instance_data_s = null;	//该类中的唯一一个实例
    private function __construct($type){	//防止在外部实例化该类
        GLOBAL $database;
        if($type == "master"){
            //$dbString='dataDefault';  }else{ $dbString='gameDefault'; }
            self::$_instance_m = @mysqli_connect($database['gameDefault']['host'],$database['gameDefault']['user'],$database['gameDefault']['password'],$database['gameDefault']['dbname'],$database['gameDefault']['port']) or die("master mysqli connect error".mysqli_connect_error()) ;
            mysqli_query(self::$_instance_m,"SET NAMES 'utf8'");
            mysqli_query(self::$_instance_m,"SET CHARACTER_SET_CLIENT=utf8");
            mysqli_query(self::$_instance_m,"SET CHARACTER_SET_RESULTS=utf8");
            mysqli_query(self::$_instance_m,"SET time_zone = '-04:00'");
        }elseif($type=="slave"){
            //){  $dbString='dataSlave';  }else{ $dbString='gameSlave'; }
            $slaveNo = rand(1,count($database['gameSlave']));
            self::$_instance_s = @mysqli_connect($database['gameSlave'][$slaveNo]['host'],$database['gameSlave'][$slaveNo]['user'],$database['gameSlave'][$slaveNo]['password'],$database['gameSlave'][$slaveNo]['dbname'],$database['gameSlave'][$slaveNo]['port']) or die("slave mysqli connect error".mysqli_connect_error()) ;
            mysqli_query(self::$_instance_s,"SET NAMES 'utf8'");
            mysqli_query(self::$_instance_s,"SET CHARACTER_SET_CLIENT=utf8");
            mysqli_query(self::$_instance_s,"SET CHARACTER_SET_RESULTS=utf8");
            mysqli_query(self::$_instance_s,"SET time_zone = '-04:00'");
        }elseif($type == "data_master"){
            self::$_instance_data_m = @mysqli_connect($database['dataDefault']['host'],$database['dataDefault']['user'],$database['dataDefault']['password'],$database['dataDefault']['dbname'],$database['dataDefault']['port']) or die("data_master mysqli connect error".mysqli_connect_error()) ;
            mysqli_query(self::$_instance_data_m,"SET NAMES 'utf8'");
            mysqli_query(self::$_instance_data_m,"SET CHARACTER_SET_CLIENT=utf8");
            mysqli_query(self::$_instance_data_m,"SET CHARACTER_SET_RESULTS=utf8");
            mysqli_query(self::$_instance_data_m,"SET time_zone = '-04:00'");
        }elseif($type == "data_slave"){
            $slaveNo = rand(1,count($database['dataSlave']));
            self::$_instance_data_s = @mysqli_connect($database['dataSlave'][$slaveNo]['host'],$database['dataSlave'][$slaveNo]['user'],$database['dataSlave'][$slaveNo]['password'],$database['dataSlave'][$slaveNo]['dbname'],$database['dataSlave'][$slaveNo]['port']) or die("data_slave mysqli connect error".mysqli_connect_error()) ;
            mysqli_query(self::$_instance_data_s,"SET NAMES 'utf8'");
            mysqli_query(self::$_instance_data_s,"SET CHARACTER_SET_CLIENT=utf8");
            mysqli_query(self::$_instance_data_s,"SET CHARACTER_SET_RESULTS=utf8");
            mysqli_query(self::$_instance_data_s,"SET time_zone = '-04:00'");
        }

        /*echo '<pre>';
        print_r($mysqliObj).'instance';
        echo '<br/>';*/
    }

    private function __clone(){}		//禁止通过复制的方式实例化该类

    public static function getInstance($type,$flag=''){
        if($type=='master'){
            if( self::$_instance_m == null )	new self('master');
            return self::$_instance_m;
        }elseif($type=='slave'){
            if( self::$_instance_s == null )	new self('slave');
            return self::$_instance_s;
        }elseif($type=='data_master'){
            if( self::$_instance_data_m == null )	new self('data_master');
            return self::$_instance_data_m;
        }elseif($type=='data_slave'){
            if( self::$_instance_data_s == null )	new self('data_slave');
            return self::$_instance_data_s;
        }
    }

    private function __destruct(){

    }
}

$dbLink  = Dbnew::getInstance('slave');
$dbMasterLink  = Dbnew::getInstance('master');


$define_data='lovemyself';

function v($expression,$type=0){
	echo '<br/>';
	echo '<pre>';
	if($type==0){
		print_r($expression);
		echo '<br/>';	
	}elseif($type==1){
		var_dump($expression);
		echo '<br/>';
	}
	die();
}
function getMainHost() {
	$host = $_SERVER["HTTP_HOST"];
	$hostArray = explode('.', $host);
	$count = count($hostArray); 
	switch ($count) 
	{
		case 4 : 
		case 3 : 
			unset($hostArray[0]);
			$mainhost = implode(".", $hostArray);
			break;
		case 2 : 
			$mainhost = implode(".", $hostArray);
			break;
		case 1 : 
			$mainhost = false;
			break;
	}
	return $mainhost;
}

function getSysConfig($key = ''){
    global $redisObj;
    $sysConfigSet = $redisObj->getSimpleOne('sys_config_set');
    $aSysConfig = json_decode($sysConfigSet, true);
    if($key == ''){
        return $aSysConfig;
    }else{
        return isset($aSysConfig[$key]) ? $aSysConfig[$key] : '';
    }
}

?>
