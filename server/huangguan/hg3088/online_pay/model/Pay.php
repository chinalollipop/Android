<?php
//ini_set("display_errors","Off");
//error_reporting(E_ALL);

//ROOT_DIR  /www/huangguan/hg3088
include_once ROOT_DIR.'/online_pay/class/paytype.php';
include_once ROOT_DIR.'/common/count/function.php';
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

    /**
     * 支付完成，操作会员资金
     * Bank银行， Bank_Address开户行，Bank_Account银行账号，PayName银行简称
     * @param $aUser          当前会员信息
     * @param $aData          回传参数 (会员名称|渠道id|用户Oid|支付方式代码)
     * @param $MemberID       商户号
     * @param $TransID        支付平台订单号
     * @param $FactMoney      资金
     * @param $aThirdPayRow   当前支付渠道
     *
     * @param $ThirdPayCode
     */
    function userpayin($aUser, $aData, $MemberID, $TransID, $FactMoney, $aThirdPayRow){
		$moneyLogBank='';
		$moneyLogWaterId='';
        // 检查此订单是否已经上分，不允许重复上分。
        $sSql = "select ID from `".DBPREFIX."web_sys800_data` WHERE `Order_Code` = '{$TransID}' AND `Checked` = 1";
        $oRes = mysqli_query(self::$_mysqli,$sSql);
        $iCou = mysqli_num_rows($oRes);
        if ($iCou > 0) {
            //echo "报错：不能重复上分";
            echo "success";
            die;
        }else{
            $date = date("Y-m-d H:i:s");
            //  $data['PayName'] 的顺序不能改变，payName一定要在最后一位，否则会报错
        	$data['userid']=$aUser['ID'];
        	$data['test_flag']=$aUser['test_flag']; //是否为测试账号
            $data['Checked']=1; //审核状态
            $data['reason']=''; //原因
            $data['AuditDate']=$date;
            $data['Payway']='W';
            $data['AddDate']=$date;
            $data['Type']='S';
            $data['UserName']=$aUser['uname'];
            $data['Agents']=$aUser['Agents'];
            $data['World']=$aUser['World'];
            $data['Corprator']=$aUser['Corprator'];
            $data['Super']=$aUser['Super'];
            $data['Admin']=$aUser['Admin'];
            $data['CurType']='RMB';
            $data['Date']=$date;
            $data['Name']=$aUser['Alias'];
            $data['Waterno']='';
            $data['Phone']=$aUser['Phone'];
            $data['Notes']='即时入账';
            $data['Bank_Account']=$aUser['Bank_Account'];
//            $FactMoney = '100.00';
            // 判断第三方支付：
            if($aThirdPayRow['thirdpay_code']=='sf' || $aThirdPayRow['thirdpay_code']=='xingchen') {
                // 闪付以分为单位
                $FactMoney = bcmul(floatval($FactMoney/100), 1, 2); //分
            } else{
                $FactMoney = bcmul(floatval($FactMoney), 1, 2);  //元
            }

            if ($aUser['layer']!=2){
                // 公司入款，额度需要加上百分比
                if ($aThirdPayRow['has_company_youhui']==1){
                    $youhui = preferentialGold($FactMoney, $aUser['AddDate'],'','');
                    $FactMoney += $youhui;
                }
            }
            $data['Gold']=$FactMoney;  //人/出款金额
            $data['moneyf']=$aUser['Money'];  //用户充提之前余额
            $data['currency_after']=$aUser['Money']+$FactMoney; //用户充提之后余额

            // Bank_Address开户行，Bank银行, Bank_Account银行账号，PayName银行简称
            //  $data['Bank_Address'],     $data['Bank']=$moneyLogBank='仁信';

            $BankInfos = getBankInfo($aThirdPayRow , $aData);
            $data['Bank_Address'] = $BankInfos['Bank_Address'];
            $data['Bank'] = $moneyLogBank = $BankInfos['Bank'];
            $data['Order_Code']=$TransID; // 订单号
            $data['Cancel']='';
            $data['PayType']=strval($aThirdPayRow['id']);  //第三方数据存入id，account_company，thirdpay_code

            // ---------------------------------------------------------count bet start-----------------------------------------------------------------------
            // 公司入款，默认更新打码量（入款更新-20191204）
            $betCount = round($FactMoney); // 打码量四舍五入
            $updateMemberOweBet = ",owe_bet=owe_bet+$FactMoney"; // 累计会员提款打码量
            $data['owe_bet'] = $betCount; // 更新此入款单打码量

            // 判断是否更新打码量统计时间（入款更新-20191204）
            $countBetTime = countBetTime($aUser['ID']);
            $updateMemberOweBet .= ($countBetTime == '' ? ",owe_bet_time='$date'" : ",owe_bet_time='$countBetTime'"); // 更新会员打码量开始统计时间
            // ---------------------------------------------------------count bet end-----------------------------------------------------------------------

            $data['PayName']=$aData[3];  //银行简称

            $sInsData = '';
            foreach ($data as $key => $value){
                if ($key=='PayName') {
                    $sInsData.= "`$key` = '{$value}'";
                }else{
                    $sInsData.= "`$key` = '{$value}',";
                }
            }

            //校验通过开始处理订单
            $sql1 = "insert into `".DBPREFIX."web_sys800_data` set $sInsData";

            // 更新玩家账户余额
            $sql2="update ".DBPREFIX.MEMBERTABLE." set Money=Money+$FactMoney,Credit=Credit+$FactMoney,WinLossCredit=WinLossCredit+$FactMoney,DepositTimes=DepositTimes+1,Online=1,OnlineTime=now() $updateMemberOweBet where UserName='{$aUser['uname']}'";
            // 会员解锁
            $sql3 = "delete from `".DBPREFIX."gxfcy_userlock` where `userid` = {$aUser['ID']} ";

            $in = mysqli_query(self::$_mysqli,$sql1);
            $moneyLogWaterId=mysqli_insert_id(self::$_mysqli);
            $up = mysqli_query(self::$_mysqli,$sql2);
            $del = mysqli_query(self::$_mysqli,$sql3);

            if(!mysqli_connect_error(self::$_mysqli)){
            	$moneyLogRes=addAccountRecords(array($aUser['ID'],$aUser['uname'],$aUser['test_flag'],$aUser['Money'],$FactMoney,$aUser['Money']+$FactMoney,11,7,$moneyLogWaterId,"{$moneyLogBank}在线存款入账"));
                if($moneyLogRes){
                    //用户分层处理
                    $resLevelDeal = level_deal($aUser['ID'],$FactMoney);
                    if($resLevelDeal){
                        mysqli_commit(self::$_mysqli);
                        return ("ok");
                    }else{
                        mysqli_rollback(self::$_mysqli);
                        return "用户层级操作错误！";
                    }
                }else{
                    mysqli_rollback(self::$_mysqli);
                    return "用户存款日志添加失败！";
                }
            }else{
                $err = mysqli_connect_error(self::$_mysqli);
                mysqli_rollback(self::$_mysqli);
                return $err;
            }
        }
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

    /*
     *	三方订单数据
     *	$data  三方数据
     *	return true/false
     * */
    function thirdDataOrder($data) {
        $sInsData = '';
        foreach ($data as $key => $value){
            if ($key=='Reviewer') {
                $sInsData.= "`$key` = '{$value}'";
            }else{
                $sInsData.= "`$key` = '{$value}',";
            }
        }

        //校验通过开始处理订单
        $sql1 = "insert into `".DBPREFIX."web_thirdpay_data` set $sInsData";
        if(mysqli_query(self::$_mysqli,$sql1)) {
            return true;
        } else {
            return false;
        }
    }

    /*
     *	  更改三方订单状态
     * *  param  thirdSysOrder  三方平台订单
     * *  param  SysTime  三方平台订单
     * *  param  CallbackTime  三方平台订单
     * *  param  Status  三方平台订单
     * * return true/false
     */
    function updateThirdOrder($data) {
        $sSql = "select ID,userid,UserName,Alias,Gold from `".DBPREFIX."web_thirdpay_data` WHERE `Order_Code` = '{$data['Order_Code']}' AND `Status` = 0";
        $oRes = mysqli_query(self::$_mysqli,$sSql);
        $iCou = mysqli_num_rows($oRes);
        $row = mysqli_fetch_assoc($oRes);
        if($iCou) {
            $mysql="update ".DBPREFIX."web_thirdpay_data set `thirdSysOrder`='{$data['thirdSysOrder']}',`CallbackTime`='{$data['CallbackTime']}',`Status`='{$data['Status']}' where Order_Code='".$data['Order_Code'] ."'";
            if(mysqli_query(self::$_mysqli,$mysql)) {
                $loginfo_status= '订单'.$data['Order_Code'].'回调更新成功';
                mysqli_commit(self::$_mysqli);
            } else {
                $loginfo_status= '订单回调更新失败';
                mysqli_rollback(self::$_mysqli);
            }

            $loginfo = '三方充值。会员帐号 '.$row['UserName'].' 回调状态置为'.$data['Status'].' 信息:'.$loginfo_status.',金额为 '.number_format($row['Gold'],2) ;
            @error_log(date('Y-m-d H:i:s').'-'.$loginfo.PHP_EOL, 3, '/tmp/updateThirdOrder.log');

            return true;
        }else {
            return false;
        }
    }
}