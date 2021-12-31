<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

require ("../include/config.inc.php");
require ("../include/curl_http.php");

$cpMasterDbLink = @mysqli_connect($database['cpDefault']['host'],$database['cpDefault']['user'],$database['cpDefault']['password'],$database['cpDefault']['dbname'],$database['cpDefault']['port']) or die("mysqli connect error".mysqli_connect_error());


if(!is_array($_SESSION['notice_idstic'])) {
    $_SESSION['notice_idstic'] = array();
}

function creatOrderCode(){
    $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
    $orderSn = $yCode[mt_rand(0,9)] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
    return $orderSn;
}

$mon = $_POST['b'] ; // 转账金额
$id = $_SESSION['userid'] ;
$uid = $_SESSION['Oid'] ;
$userName = $_SESSION['UserName'] ;
$from = $_POST['f'];
$to = $_POST['t'];
$aData = array();

//添加扩展刷水账号
if($_POST['action'] == 'fundLimitTrans') {
    $insertId='';
    $logDes='';
    if(!$uid){

        $status = '400.1';
        $describe = '1缺少uid!' ;
        original_phone_request_response($status,$describe,$aData);
    }

    if(!$uid || !$uid || !$from || !$to || !$mon){

        $status = '400.2';
        $describe = '2缺少参数!' ;
        original_phone_request_response($status,$describe,$aData);
    }

    if(!is_numeric($mon)){
        $status = '400.3';
        $describe = '3转换金额无效!' ;
        original_phone_request_response($status,$describe,$aData);
    }

    if(!preg_match("/^[1-9][0-9]*$/",$mon)){
       // exit(json_encode( [ 'status'=>'1','msg'=>'4转账金额只支持正整数，请重新输入' ] ));
        $status = '400.4';
        $describe = '4转账金额只支持正整数，请重新输入!' ;
        original_phone_request_response($status,$describe,$aData);
    }



    $fund = $mon;


    $sql = "select `ID`,`test_flag`,`UserName`,`Agents`,`World`,`Corprator`,`Super`,`Admin`,`Alias`,`Money`,`layer` from ".DBPREFIX.MEMBERTABLE." where Oid='$uid' and Status<=1";
    $result = mysqli_query($dbLink,$sql);
    $cou=mysqli_num_rows($result);
    if($cou==0){
        // exit(json_encode( [ 'err'=>'-1','msg'=>'请重新登录' ] ) );
        $status = '502';
        $describe = '请重新登录!' ;
        original_phone_request_response($status,$describe,$aData);
    }
    $row = mysqli_fetch_assoc($result);

    $sUserlayer = $row['layer'];
    // 检查当前会员是否设置不准操作额度分层
    // 检查分层是否开启 status 1 开启 0 关闭
    // layer 1 不返水 2 不优惠 3 不准操作资金 4 不准领取彩金 5 仅限可以投注体育，不能额度转换去其它馆
    if ($sUserlayer==3 || $sUserlayer==5){
        $layer = getUserLayerById($sUserlayer);
        if ($layer['status']==1) {
            $status = '502';
            $describe = '账号分层异常，请联系我们在线客服' ;
            original_phone_request_response($status,$describe,$aData);
        }
    }

    $test_flag=$row['test_flag'];
    $Agents=$row['Agents'];
    $World=$row['World'];
    $Corprator=$row['Corprator'];
    $Super=$row['Super'];
    $Admin=$row['Admin'];
    $Alias=$row['Alias'];
    $Hgmoney=$row['Money'];

    $result = mysqli_query($cpMasterDbLink,"SELECT id,username,lcurrency FROM `gxfcy_user` WHERE hguid=".$id);
    $row = mysqli_fetch_assoc($result);
    $cou = mysqli_num_rows($result);
    $idtTO = $row['id'];
    $usernameTO = $row['username'];
    $lcurrencyTO = $row['lcurrency'];

    if($from=="cp" || $to=="cp"){
        if($from=="hg" && $to=="cp"){// 体育转彩票
            $hgSign="-";
            $cpSign="+";
            $flagType=1;
            $orderprefix='hgcp';
            $cpTypecode=0;

            if ($Hgmoney < $fund){

                $status = '400.6';
                $describe = '额度有误，请确认金额!' ;
                original_phone_request_response($status,$describe,$aData);
            }
            $moneyf = $Hgmoney; // 用户账变前余额
            $currency_after = $Hgmoney-$fund; // 用户账变后的余额
        }elseif($from=="cp" && $to=="hg"){ // 彩票转体育
            $hgSign="+";
            $cpSign="-";
            $flagType=2;
            $orderprefix='cphg';
            $cpTypecode=1;

            if ($lcurrencyTO < $fund){

                $status = '400.7';
                $describe = '额度有误，请确认金额!' ;
                original_phone_request_response($status,$describe,$aData);
            }
            $moneyf = $Hgmoney; // 用户账变前余额
            $currency_after = $Hgmoney+$fund; // 用户账变后的余额
        }
        $beginFrom = mysqli_query($dbMasterLink,"start transaction");//开启事务$from
        if($beginFrom){
            $oRes = mysqli_query($dbLink, "SELECT * FROM ".DBPREFIX."gxfcy_userlock WHERE userid = {$id}");
            $iCou = mysqli_num_rows($oRes);
            if($iCou == 0){
                $insert_flag = mysqli_query($dbMasterLink, "insert into `".DBPREFIX."gxfcy_userlock` set `userid` = {$id}");
                if(!$insert_flag) {
                    mysqli_query($dbMasterLink,"ROLLBACK");

                    $status = '400.8';
                    $describe = '添加用户锁失败!' ;
                    original_phone_request_response($status,$describe,$aData);
                }
            }
            $lockUser = mysqli_query($dbMasterLink, "select userid from `".DBPREFIX."gxfcy_userlock` where `userid` = {$id} for update");
            $lockMoney = mysqli_query($dbMasterLink, "select Money,test_flag from `".DBPREFIX.MEMBERTABLE."` where `ID` = {$id} for update");
            if($lockUser && $lockMoney){
                $rowMoney=mysqli_fetch_assoc($lockMoney);

                // 体育转入彩票，在事务中判断体育的余额
                if($from=="hg" && $to=="cp"){

                    if ($rowMoney['Money'] < $fund){
                        mysqli_query($dbMasterLink,"ROLLBACK");

                        $status = '400.9';
                        $describe = '额度有误，请确认金额!' ;
                        original_phone_request_response($status,$describe,$aData);
                    }

                    $moneyf = $rowMoney["Money"];
                    $currency_after = $moneyf-$fund;
                    $moneyCur=$fund*-1;
                    $logDes='彩票游戏额度转换体育到彩票';
                    $logType=21;
                }elseif($from=="cp" && $to=="hg"){
                    $moneyf = $rowMoney["Money"];
                    $currency_after = $moneyf+$fund;
                    $moneyCur=$fund;
                    $logDes='彩票游戏额度转换彩票到体育';
                    $logType=22;
                }
                $orderCode=$orderprefix.creatOrderCode();//获取订单号
                $addFundRecordFromSql="INSERT INTO ".DBPREFIX."web_sys800_data(`userid`,`Checked`,`Gold`,`moneyf`,`currency_after`,`AddDate`,`Type`,`From`,`To`,`UserName`,`Agents`,`World`,`Corprator`,`Super`,`Admin`,`Name`,`CurType`,`Date`,`AuditDate`,`Order_Code`,`reason`,`test_flag`)
				    	VALUES($id,1,$fund,$moneyf,$currency_after,CURDATE(),'Q','$from','$to','$userName','$Agents','$World','$Corprator','$Super','$Admin','$Alias','RMB',NOW(),NOW(),'$orderCode','额度转换',$test_flag)";
                $addFundRecordFrom = mysqli_query($dbMasterLink,$addFundRecordFromSql);//添加历史记录表
                if($addFundRecordFrom){
                    $inserId=mysqli_insert_id($dbMasterLink);
                    $updateUserFundFrom = mysqli_query($dbMasterLink,"UPDATE ".DBPREFIX.MEMBERTABLE." SET Money=Money".$hgSign.$fund." , Online=1 , OnlineTime=now() WHERE ID=".$id);//更新用户资金数据$from
                    if($updateUserFundFrom){
                        $moneyLogRes=addAccountRecords(array($id,$userName,$rowMoney['test_flag'],$moneyf,$moneyCur,$currency_after,$logType,22,$inserId,$logDes));
                        if($moneyLogRes){
                            $addFlagRecordFrom = mysqli_query($dbMasterLink,"INSERT INTO ".DBPREFIX."fundlimitrans_flag(`ordercode`,`type`)VALUES('$orderCode',$flagType)");//添加中间记录
                            if($addFlagRecordFrom){
                                $beginTo = mysqli_query($cpMasterDbLink,"start transaction");//开启事务$to
                                if($beginTo){
                                    $resultMcp = mysqli_query($cpMasterDbLink,"SELECT lcurrency FROM `gxfcy_user` WHERE hguid=".$id." for update");
                                    if($resultMcp){
                                        $rowMcp = mysqli_fetch_assoc($resultMcp);
                                        $cou = mysqli_num_rows($resultMcp);
                                        $lcurrencyTO = $rowMcp['lcurrency'];
                                        // 彩票转体育时，在事务中判断彩票的余额
                                        if($from=="cp" && $to=="hg"){
                                            if ($lcurrencyTO < $fund){
                                                mysqli_query($cpMasterDbLink,"ROLLBACK");
                                                mysqli_query($dbMasterLink,"ROLLBACK");

                                                $status = '400.10';
                                                $describe = '额度有误，请确认金额!' ;
                                                original_phone_request_response($status,$describe,$aData);
                                            }
                                        }
                                        $updateUserFundTo = mysqli_query($cpMasterDbLink,"UPDATE gxfcy_user SET lcurrency=lcurrency".$cpSign.$fund." WHERE hguid=".$id);//更新用户资金数据$from
                                        if($updateUserFundTo){
                                            $addMoneyLogToSql="INSERT INTO gxfcy_moneylog(`userid`,`currency_before`,`money`,`currency_after`,`TYPE`,`bill_id`,`log_time`)VALUES($idtTO,$lcurrencyTO,$fund,$lcurrencyTO".$cpSign."$fund,8,'$orderCode',now())";
                                            $addMoneyLogTo = mysqli_query($cpMasterDbLink,$addMoneyLogToSql);//添加资金变更记录
                                            if($addMoneyLogTo){
                                                $addPayRecordToSql="INSERT INTO gxfcy_pay_record(`order_code`,`type_code`,`pay_way`,`addtime`,`userid`,`chargetime`,`money`,`moneyf`,`currency_after`,`STATUS`,`is_clear`,`is_auto`,`is_auto_flag`)VALUES('$orderCode','$cpTypecode',4,UNIX_TIMESTAMP(NOW()),$idtTO,NOW(),$fund,$lcurrencyTO,$lcurrencyTO".$cpSign."$fund,1,1,1,1)";
                                                $addPayRecordLogTo = mysqli_query($cpMasterDbLink,$addPayRecordToSql);//添加资金变更记录
                                                if($addPayRecordLogTo){
                                                    $commitTo = mysqli_query($cpMasterDbLink,"COMMIT");
                                                    if($commitTo){
                                                        $commitFrom=mysqli_query($dbMasterLink,"COMMIT");
                                                        if($commitFrom){
                                                            //没有一定成功，有不一定失败，此时两次提交都成功，但是有可能删除中间件失败 ，不做判断不在主逻辑内
                                                            $deleteFlagRecordFrom = mysqli_query($dbMasterLink,"delete from ".DBPREFIX."fundlimitrans_flag where ordercode='".$orderCode."' and type=$flagType");//删除中间件记录表

                                                            $status = '200';
                                                            $describe = '转账成功!' ;
                                                            original_phone_request_response($status,$describe,$aData);
                                                            exit;
                                                        }else{
                                                            mysqli_query($dbMasterLink,"ROLLBACK");
                                                            $note="用户".$usernameTO."(".$id.")".$from."平台扣款失败";
                                                            echo "UPDATE ".DBPREFIX."fundlimitrans_flag SET note='".$note."' where ordercode='".$orderCode;
                                                            $updateFlagFrom = mysqli_query($dbMasterLink,"UPDATE ".DBPREFIX."fundlimitrans_flag SET note='".$note."' where ordercode='".$orderCode."'");//更新用户资金数据$from
                                                            //To方到账 From未完成该扣款，flag仍然存在，根据flag对From方进行扣款即可，之后完成手动删除

                                                            $status = '400.11';
                                                            $describe = '扣款失败!' ;
                                                            original_phone_request_response($status,$describe,$aData);

                                                        }
                                                    }
                                                }else{
                                                    mysqli_query($cpMasterDbLink,"ROLLBACK");

                                                    $status = '400.12';
                                                    $describe = '添加资金变更记录失败!' ;
                                                    original_phone_request_response($status,$describe,$aData);
                                                }
                                            }else{
                                                mysqli_query($cpMasterDbLink,"ROLLBACK");

                                                $status = '400.13';
                                                $describe = '添加资金变更日志失败!' ;
                                                original_phone_request_response($status,$describe,$aData);
                                            }
                                        }else{
                                            mysqli_query($cpMasterDbLink,"ROLLBACK");

                                            $status = '400.14';
                                            $describe = '彩票用户资金更新失败!' ;
                                            original_phone_request_response($status,$describe,$aData);
                                        }
                                    }else{
                                        mysqli_query($cpMasterDbLink,"ROLLBACK");

                                        $status = '400.15';
                                        $describe = 'CP用户资金锁开启失败!' ;
                                        original_phone_request_response($status,$describe,$aData);
                                    }
                                }else{
                                    mysqli_query($cpMasterDbLink,"ROLLBACK");

                                    $status = '400.16';
                                    $describe = 'CP事务开启失败!' ;
                                    original_phone_request_response($status,$describe,$aData);
                                }
                            }else{
                                mysqli_query($dbMasterLink,"ROLLBACK");

                                $status = '400.17';
                                $describe = '添加中间记录失败!' ;
                                original_phone_request_response($status,$describe,$aData);
                            }
                        }else{
                            mysqli_query($dbMasterLink,"ROLLBACK");

                            $status = '400.18';
                            $describe = '资金账变记录添加失败!' ;
                            original_phone_request_response($status,$describe,$aData);
                        }
                    }else{
                        mysqli_query($dbMasterLink,"ROLLBACK");

                        $status = '400.19';
                        $describe = '更新用户资金失败!' ;
                        original_phone_request_response($status,$describe,$aData);
                    }
                }else{
                    mysqli_query($dbMasterLink,"ROLLBACK");

                    $status = '400.20';
                    $describe = '添加历史记录表失败!' ;
                    original_phone_request_response($status,$describe,$aData);
                }
            }else{
                mysqli_query($dbMasterLink,"ROLLBACK");

                $status = '400.21';
                $describe = '锁定记录失败!' ;
                original_phone_request_response($status,$describe,$aData);
            }
        }else{

            $status = '400.22';
            $describe = '非法转换!' ;
            original_phone_request_response($status,$describe,$aData);
        }
    }
}
?>