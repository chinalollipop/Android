<?php

define("ROOT_DIR",  dirname(dirname(dirname(dirname(dirname(__FILE__))))));	

require_once ROOT_DIR.'/common/database.php';
require_once ROOT_DIR.'/common/config.php';
require_once ROOT_DIR.'/common/function.php';
require_once "redis.php";

// 获取当前用户Oid
$redisObj = new Ciredis();
$returnOid = $redisObj->getSimpleOne('loginuser_'.$_SESSION['userid']);
if($_SESSION['Oid'] != $returnOid) {
    // 当前登录用户oid和redis中Oid不一致
    session_destroy();
    unset($_SESSION['userid']);
	unset($_SESSION['Oid']);
	unset($_SESSION['UserName']);
	unset($_SESSION['UserName']);
}

// 获取刷水渠道，方便后续做赔率转换判断
$flushWay = $redisObj->getSimpleOne('flush_way'); // 刷水渠道
$flushDoamin = getSysConfig('flush_domain'); // 刷水网址
$flushMatchTable = $redisObj->getSimpleOne('flush_match_table'); // 刷水的表名
$flushFsMatchTable = $redisObj->getSimpleOne('flush_fs_match_table'); // 冠军刷水的表名
if(!defined("SPORT_FLUSH_WAY")) {
    define("SPORT_FLUSH_WAY", $flushWay);
}
if(!defined("SPORT_FLUSH_DOMAIN")) {
    define("SPORT_FLUSH_DOMAIN", $flushDoamin);
}
if(!defined("SPORT_FLUSH_MATCH_TABLE")) {
    if($flushMatchTable){
        define("SPORT_FLUSH_MATCH_TABLE", $flushMatchTable);
    }else{
        define("SPORT_FLUSH_MATCH_TABLE", 'match_sports');
    }
}
if(!defined("SPORT_FLUSH_FS_MATCH_TABLE")){
    if($flushFsMatchTable){
        define("SPORT_FLUSH_FS_MATCH_TABLE", $flushFsMatchTable);
    }else{
        define("SPORT_FLUSH_FS_MATCH_TABLE", 'match_crown');
    }
}

// 前台注册登录验证码开关
if(!defined("LOGIN_IS_VERIFY_CODE")) {
    $codeOpenSwitch = $redisObj->getSimpleOne('code_open_switch'); // 取redis 设置的值
    if(!$codeOpenSwitch){ // 默认打开
        define("LOGIN_IS_VERIFY_CODE", true);
    }else{
        define("LOGIN_IS_VERIFY_CODE", eval("return $codeOpenSwitch;"));
       // var_dump(LOGIN_IS_VERIFY_CODE);
    }
}

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

$global_vars = array(
    "BROWSER_IP"		=>	HTTPS_HEAD."://".$_SERVER['HTTP_HOST'],
    "CASINO"            =>  "SI2",
);

while (list($key, $value) = each($global_vars)) {
    define($key, $value);
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

$str="and|select|update|from|where|order|by|*|delete|'|insert|into|values|create|table|database|<|>|=|\"|&|$|#|{|}|[|]|;|?";  //非法字符
$arr=explode("|",$str);//数组非法字符，变单个  
foreach ($_REQUEST as $key=>$value){
	for($i=0;$i<sizeof($arr);$i++){
		if (substr_count(strtolower($_REQUEST[$key]),$arr[$i])>0){       //检验传递数据是否包含非法字符
            if($i == "0") {
                if(!preg_match("/(\s+and\s*)|(\s*and\s+)/", strtolower($_REQUEST[$key]))) {
                    continue;
                }
            }
            if($i == "6") {
                if(!preg_match("/(\s+by\s*)|(\s*by\s+)/", strtolower($_REQUEST[$key]))) {
                    continue;
                }
            }		
		    echo "Illegal Character ".$arr[$i];
            exit;
		}
	} 
}

/*****************************新站结果及冠军***************************************/
//$newdatabase="http://hg.xhg518.cn";
//$newdatabase="http://www.hg3088_member_new.lcn";
//
//
//$sql="select id from ".DBPREFIX.MEMBERTABLE." where Oid='".$_REQUEST['uid']."'";
//$result = mysqli_query($dbLink,$sql);
//$row = mysqli_fetch_assoc($result);
//$cou = mysqli_num_rows($result);
//
//if($cou>0){
//	$userid=intval($row['id']);
//}else{
//	$userid=0;
//}
//echo '111111111111111111111111111111<br/>';
$_SESSION['langx']="zh-cn";
//足球用户金额同步到时时彩

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

    /*
     * $flag
     * 会员分层逻辑
     * $userid	用户id
     * $fee		总费用
     * $kflag	kflag 0存款 	1取款 	
     * */
	function level_deal($userid,$fee,$kflag=0){
		global $dbLink,$dbMasterLink;
        $sql = "select withdraw_num,deposit_num,withdraw_money,deposit_money,max_deposit_money from ".DBPREFIX."gxfcy_usermoney_statistics where userid={$userid}";
        $result = mysqli_query($dbLink,$sql);
		$money_statis = mysqli_fetch_assoc($result);
        if(empty($money_statis)){
            $flag = 1;//进行插入操作
            $money['update_time']  = $money['create_time'] = date("Y-m-d H:i:s");
            $money['userid']  = $userid;
            if($kflag==1){
                $money['withdraw_num'] = 1;
                $money['withdraw_money'] = $fee;
                $money['deposit_num'] = 0;
                $money['deposit_money'] = $money['max_deposit_money'] = 0;
            }else{
                $money['withdraw_num'] = 0;
                $money['withdraw_money'] = 0;
                $money['deposit_num'] = 1;
                $money['deposit_money'] = $money['max_deposit_money'] = $fee;
            }
        }else{
            $flag = 0;
            $money['update_time']  = date("Y-m-d H:i:s");
            if($kflag==1){
                $money['withdraw_num'] = $money_statis['withdraw_num']+1;
                $money['withdraw_money'] = $money_statis['']+$fee;
                $money['deposit_num'] = $money_statis['deposit_num'];
                $money['deposit_money'] =  $money_statis['deposit_money'];
                $money['max_deposit_money'] =  $money_statis['max_deposit_money'];
            }else{
                $money['withdraw_num'] = $money_statis['withdraw_num'];
                $money['withdraw_money'] = $money_statis['withdraw_money'];
                $money['deposit_num'] = $money_statis['deposit_num']+1;
                $money['deposit_money'] =  $money_statis['deposit_money'] + $fee;
                $money['max_deposit_money'] =  $money_statis['max_deposit_money'] > $fee?$money_statis['max_deposit_money']:$fee;
            }
        }
		foreach($money as $key=>$val){
			$tmp[]=$key.'=\''.$val.'\'';
        }
        if($flag==1){
			$sqlinsert="insert into ".DBPREFIX."gxfcy_usermoney_statistics set ".implode(',',$tmp);
			$res = mysqli_query($dbMasterLink,$sqlinsert);
        }else{
        	$sqlupdate="update ".DBPREFIX."gxfcy_usermoney_statistics set ".implode(',',$tmp)." where userid = {$userid}";
			$res = mysqli_query($dbMasterLink,$sqlupdate);
        }
        if(!$res){
            return false;
        }
        
        $sql = "select pay_class from ".DBPREFIX.MEMBERTABLE." where ID={$userid}";
        $result = mysqli_query($dbLink,$sql);
		$userinfo = mysqli_fetch_assoc($result);
		
        $class = set_user_level($money,$userinfo['pay_class']);

        /*层级有修改更新用户表*/
        if($class !==false){
        	$sql="update ".DBPREFIX.MEMBERTABLE." set pay_class='".$class."' , Online=1 , OnlineTime=now() where ID=".$userid;
        	$result = mysqli_query($dbMasterLink,$sql);
            if(!$result){
                return false;
            }
        }
        return true;
    }

    /**
    	会员分层方法
     */
    function set_user_level($list,$userclass){
    	global $dbLink,$dbMasterLink;
        $levelList=array();
    	$sql = "select withdraw_num,deposit_num,withdraw_money,deposit_money,max_deposit_money from ".DBPREFIX."gxfcy_userlevel where level!=0 order by level ASC";
        $result = mysqli_query($dbLink,$sql);
		while($row = mysqli_fetch_assoc($result)){
			$levelList[]=$row;
		}
        
        $max_count = count($levelList);
        $arr = array();
        foreach($levelList as $p=>$item){
            $arr[] = $item['ename'];
            if($userclass== $item['ename']){
                $userlevel = $p;
            }
        }
        foreach($levelList as $k=>$val){
            if($k==$max_count-1) $level = $max_count-1;
            if($list['deposit_num']<$val['deposit_num']){
                $level = $k-1;break;
            }
            if($list['deposit_money']<$val['deposit_money']){
                $level = $k-1;break;
            }
            if($list['max_deposit_money']<$val['max_deposit_money']){
                $level = $k-1;break;
            }
            if($list['withdraw_num']<$val['withdraw_num']){
                $level = $k-1;break;
            }
            if($list['withdraw_money']<$val['withdraw_money']){
                $level = $k-1;break;
            }
        }
        if($level<=$userlevel){
            return false;
        }
        if(!in_array($userclass,$arr)){
            return false;
        }
        if($levelList[$level]['ename']==$userclass){
            return false;
        }
        return $levelList[$level]['ename'];
    }
/**
 * 获取指定日期段内每一天的日期
 * @param  Date  $startdate 开始日期
 * @param  Date  $enddate   结束日期
 * @return Array
 */
function getDateFromRange($startdate, $enddate){
    $stimestamp = strtotime($startdate);
    $etimestamp = strtotime($enddate);
    // 计算日期段内有多少天
    $days = ($etimestamp-$stimestamp)/86400+1;
    // 保存每天日期
    $date = array();
    for($i=0; $i<$days; $i++){
        $date[] = date('Y-m-d', $stimestamp+(86400*$i));
    }
    return $date;
}

//以下使用 PHP 正则表达式来匹配日期格式 "YYYY-MM-DD"：
function checkDateFormat($date)
{
    //匹配日期格式
    if (preg_match ("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $date, $parts))
    {
        //检测是否为日期
        if(checkdate($parts[2],$parts[3],$parts[1]))
            return true;
        else
        return false;
    }
    else
        return false;
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

function formatMoney($money){
    // 读取余额小数配置参数
    $decimals = intval(getSysConfig('money_decimal_number'));
    if($decimals == 0){ // 默认floor处理
        $moneyFormat = floor($money);
    }else{
        $moneyFormat = sprintf("%.{$decimals}f",$money);
    }
    return $moneyFormat;
}
?>
