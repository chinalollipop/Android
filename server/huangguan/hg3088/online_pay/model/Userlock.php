<?php
/**
 * 用户锁
 *
 * */

class Userlock_model{

    private static $_mysqli = null;
    function __construct($mysqli)
    {
        self::$_mysqli = $mysqli;
    }

    /**
     * 开启事务，会员加锁
     *
     * @param $userid
     */
    function lock($userid){
        mysqli_autocommit(self::$_mysqli,false);// 关闭本次数据库连接的自动命令提交事务模式
        $oRes = mysqli_query(self::$_mysqli, "SELECT userid FROM ".DBPREFIX."gxfcy_userlock WHERE userid = {$userid}");
        $iCou = mysqli_num_rows($oRes);
        if($iCou == 0){
			$insertRes= mysqli_query(self::$_mysqli, "insert into `".DBPREFIX."gxfcy_userlock` set `userid` = {$userid}");
			if(!$insertRes){ return false; }
		}
        $res = mysqli_query(self::$_mysqli, "select userid from `".DBPREFIX."gxfcy_userlock` where `userid` = {$userid} for update");
        if($res){//锁住用户资金表获取用户当前资金余额写入资金日志
        	$userInfoSql = "select ID,UserName as uname,Money,Alias,test_flag,Pay_Type,Status,Agents,World,Corprator,Super,Admin,Bank_Account,Phone,Notes,layer,AddDate from `".DBPREFIX.MEMBERTABLE."` WHERE `ID` = {$userid} for update";
        	$userInfoRes = mysqli_query(self::$_mysqli,$userInfoSql);
		   	if($userInfoRes){
		   		$userInfoRow = mysqli_fetch_assoc($userInfoRes);
		   		return json_encode($userInfoRow);
		   	}
		}else{
        	return false;
        } 
    }


    /**
     * 提交事务操作
     *
     * @param $mysqli
     */
    function commit_lock(){
        mysqli_autocommit(self::$_mysqli, true);
        mysqli_close(self::$_mysqli);
    }
}
?>