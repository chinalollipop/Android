<?php

/**
 * 第三方资金操作（存钱上分、自动出款）
 *
 * */

class Pay_model{

    private static $_mysqli = null;
    public $masterLink;
    public $slaveLink;
    function __construct($mysqli)
    {
    	global $dbMasterLink,$dbLink;
        self::$_mysqli = $mysqli;
        $this->masterLink = $dbMasterLink;
        $this->slaveLink = $dbLink;
    }



    /*
     *
     *	自动更新订单状态
     *	$out_trade_no	订单号
     *	$flag	true/false
     *	$resp_desc 返回码描述
     * */
    function updateAutoWithdrawer($out_trade_no, $flag, $resp_desc = "") {

        $reviewDate = date('Y-m-d H:i:s');
        $sSql = "select ID,userid,Gold from `".DBPREFIX."web_sys800_data` WHERE `Order_Code` = '{$out_trade_no}' AND `checked` = 2";
        $oRes = mysqli_query(self::$_mysqli,$sSql);
        $iCou = mysqli_num_rows($oRes);
        $row = mysqli_fetch_assoc($oRes);
        $gold = $row['Gold']; // 取款金额（更新用户输赢额度）
        if($iCou==0) {
            $loginfo_status= '报错：订单已处理，请勿重复处理';
        }else {
            if($flag) {
                $beginFrom = mysqli_query(self::$_mysqli,"start transaction");  //开启事务$from
                if($beginFrom){
                    $mysql_check=mysqli_query(self::$_mysqli,"select Checked from ".DBPREFIX."web_sys800_data where ID=".$row['ID']." for update");
                    $mysqlCheckResult = mysqli_fetch_assoc($mysql_check);
                    if($mysqlCheckResult['Checked']&&$mysqlCheckResult['Checked']==2) { // 出款二次审核
                        $resultMem = mysqli_query(self::$_mysqli, "select ID,UserName,test_flag,Money from  ".DBPREFIX.MEMBERTABLE." where  ID='{$row['userid']}' for update");
                        if ($resultMem) {
                            $rowMem = mysqli_fetch_assoc($resultMem);

                            $mysql="update ".DBPREFIX.MEMBERTABLE." set WithdrawalTimes=WithdrawalTimes+1,WinLossCredit=WinLossCredit-$gold,Online=1,OnlineTime=now(),owe_bet=0,owe_bet_time='{$reviewDate}' where ID='".$row['userid']."'"; // 自动出款成功后扣除输赢额度&会员打码量清0
                            if(mysqli_query(self::$_mysqli,$mysql)) {

                                $mysql="update ".DBPREFIX."web_sys800_data set Checked='1',is_auto='1',is_auto_flag='1',reviewDate='{$reviewDate}' where ID=".$row['ID'];
                                if(mysqli_query(self::$_mysqli,$mysql)){
                                    $res = level_deal($row['userid'],$row['Gold'],1);//用户层级关系处理
                                    if($res){
                                        $loginfo_status='自动提款成功';
                                        mysqli_query(self::$_mysqli,"COMMIT");
                                    }else{
                                        $loginfo_status= '自动提款成功，层级更新失败';
                                        mysqli_query(self::$_mysqli,"ROLLBACK");
                                    }
                                }else{
                                    $loginfo_status= '自动提款成功，订单更新失败';
                                    mysqli_query(self::$_mysqli,"ROLLBACK");
                                }
                            }else{
                                $loginfo_status = '更新提款次数失败!';
                                mysqli_query(self::$_mysqli,"ROLLBACK");
                            }
                        }else{
                            $loginfo_status= '自动提款成功，会员提款次数加锁失败';
                            mysqli_query(self::$_mysqli,"ROLLBACK");
                        }
                    }else{
                        $loginfo_status= '自动提款成功，订单查询失败';
                        mysqli_query(self::$_mysqli,"ROLLBACK");
                    }
                }else{
                    $loginfo_status= '自动提款成功，事务开启失败';
                    mysqli_query(self::$_mysqli,"ROLLBACK");
                }

            }else {
                $mysql="update ".DBPREFIX."web_sys800_data set Checked='2',is_auto='1',is_auto_flag='0',reviewDate='{$reviewDate}',auto_memo='{$resp_desc}' where ID=".$row['ID'];
                if(mysqli_query(self::$_mysqli,$mysql)){
                    $loginfo_status= '自动提款失败，订单更新成功';
                }else{
                    $loginfo_status= '自动提款失败，订单更新失败';
                }
            }
        }
        $loginfo = '自动出款。会员帐号 '.$rowMem['UserName'].' 出款状态置为 '.$loginfo_status.',金额为 '.number_format($row['Gold'],2) ;
        @error_log(date('Y-m-d H:i:s').'-'.$loginfo.PHP_EOL, 3, '/tmp/updateAutoWithdrawer.log');

    }
    
}