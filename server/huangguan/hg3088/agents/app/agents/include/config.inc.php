<?php
session_start();
define("ROOT_DIR",  dirname(dirname(dirname(dirname(dirname(__FILE__))))));
include_once ROOT_DIR.'/common/database.php';
include_once ROOT_DIR.'/common/config.php';
require_once ROOT_DIR.'/common/function.php';
require_once ROOT_DIR.'/common/count/function.php';
require_once ROOT_DIR."/agents/app/agents/include/redis.php";

//SQL注入开关    SAFE_INJECTION
if(SAFE_INJECTION) {
	include ROOT_DIR.'/codeIgniter/injection.php';
}

// 获取刷水渠道，方便后续做赔率转换判断
$redisObj = new Ciredis();
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


class Dbnew{
	private static $_instance_m = null;	//该类中的唯一一个实例
	private static $_instance_s = null;	//该类中的唯一一个实例
    private static $_instance_data_m = null;	//该类中的唯一一个实例
    private static $_instance_data_s = null;	//该类中的唯一一个实例
    private function __construct($type){	//防止在外部实例化该类
    	GLOBAL $database;
    	if($type == "master"){
            self::$_instance_m = @mysqli_connect($database['gameDefault']['host'],$database['gameDefault']['user'],$database['gameDefault']['password'],$database['gameDefault']['dbname'],$database['gameDefault']['port']) or die("master mysqli connect error".mysqli_connect_error()) ;
			mysqli_query(self::$_instance_m,"SET NAMES 'utf8'");
	        mysqli_query(self::$_instance_m,"SET CHARACTER_SET_CLIENT=utf8");
	        mysqli_query(self::$_instance_m,"SET CHARACTER_SET_RESULTS=utf8");
	        mysqli_query(self::$_instance_m,"SET time_zone = '-04:00'");
    	}elseif($type=="slave"){
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

    /*
     * $flag
     * 会员分层逻辑
     * $userid	用户id
     * $fee		总费用
     * $kflag	kflag 0存款  1取款 	
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
                $money['withdraw_money'] = $money_statis['withdraw_money']+$fee;
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
        	$sql="update ".DBPREFIX.MEMBERTABLE." set pay_class='".$class."' where ID=".$userid;
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

// 判断后台帐号级别
function reAdminLevel($lev){
    switch ($lev){
        case 'M':
            $user_level='管理员';
            break;
        case 'A':
            $user_level='公司';
            break;
        case 'B':
            $user_level='股东';
            break;
        case 'C':
            $user_level='总代理';
            break;
        case 'D':
            $user_level='代理商';
            break;
    }
    return $user_level ;
}

// 插入系统日志公用函数 $username 操作人，$lv 操作人层级，$memname 操作对(会员帐号)，$loginfo 操作信息
function innsertSystemLog($username,$lv,$loginfo){
    global $dbMasterLink ;
    $user_level = reAdminLevel($lv) ;
    $ip_addr = get_ip();
    $log_mysql="insert into ".DBPREFIX."web_mem_log_data(UserName,Logintime,ConText,Loginip,Url,Level) values('$username',now(),'$loginfo','$ip_addr','".BROWSER_IP."','$user_level')";
    mysqli_query($dbMasterLink,$log_mysql);
}


/* *
 * 获取子账号权限分层
 * $status 区分存出款   出款，提款审核1     存款0
 * */
function getCompetenceLevel($Competence , $status) {
    $Competence_array = explode(',', $Competence);
    foreach ($Competence_array as $key => $value) {
        if (strpos($value, '-') !== false) {
            $level_array = explode('-', $value);
            // 根据存提款状态 返回被选中的层级
            if ($level_array[1] == $status) {
                $levels[] = $level_array[0];
            }
        }
    }
    $subuser_level = array_unique($levels);
    return $subuser_level;
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
