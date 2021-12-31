<?php

/**
 * 获取余额，体育余额、彩票余额、真人余额
 * 额度转换： ag2hg
 * 额度转换： cp2hg
 */

header("Content-type: text/html; charset=utf-8");
require ("../include/config.inc.php");
require "../include/agproxy.php";

$insertId='';
$uid=$_REQUEST['uid'];
$action = $_REQUEST['action'];
$f=$_REQUEST['f'];
$t=$_REQUEST['t'];
$b=$_REQUEST['b'];
if ($f=='ag'&&$t=='hg'){
    $action='ag2hg';
}
if ($f=='cp'&&$t=='hg'){
    $action='cp2hg';
}

$sql = "select ID,Money,UserName,Agents,World,Corprator,Super,Admin,Alias,Phone,test_flag from ".DBPREFIX.MEMBERTABLE." where Oid='$uid' and Status=0";
$result = mysqli_query($dbLink,$sql);
$row=mysqli_fetch_assoc($result);
$cou=mysqli_num_rows($result);
if($cou==0){
    exit(json_encode( [ 'err'=>'-1','msg'=>'请重新登录' ] ) );
}
$test_flag=$row['test_flag'];
$Agents=$row['Agents'];
$World=$row['World'];
$Corprator=$row['Corprator'];
$Super=$row['Super'];
$Admin=$row['Admin'];
$Alias=$row['Alias'];
$aUser = $row;


$domain_url = $agsxInit['domain_url'];
$api_url = $agsxInit['api_url'];
$game_api_url = $agsxInit['game_api_url'];
$cagent = $agsxInit['cagent'];
$md5_key = $agsxInit['md5_key'];
$des_key = $agsxInit['des_key'];
$testers = '';
$cny = $agsxInit['cur'];
$oAg = new agproxy($domain_url, $api_url, $game_api_url, $cagent, $md5_key, $des_key, $testers, $cny);

switch ($action){
    case 'b': // 获取体育余额-AG余额-彩票余额

        $ag_sql = "select username,password,is_test from `".DBPREFIX."ag_users` where `userid` = '{$aUser['ID']}'";
        $ag_result = mysqli_query($dbLink, $ag_sql);
        $ag_cou = mysqli_num_rows($ag_result);
        if ($ag_cou == 0) {
            $agGetbalance["balance"] = number_format(0, 2, '.', ',');;
        }else{
            $ag_row = mysqli_fetch_assoc($ag_result);
            if (AG_TRANSFER_SWITCH === TRUE){
                $url = AG_TRANSFER_URL.'?action=b&username='.$ag_row['username'].'&password='.$ag_row['password'].'&is_test='.$ag_row['is_test'];
                $agGetbalance = file_get_contents($url);
                $agGetbalance = json_decode($agGetbalance, true);
            }
            else{
                $agGetbalance = $oAg->ag_getBalance($ag_row['username'], $ag_row['password'], $ag_row['is_test']);
            }
            if ( $agGetbalance['info'] != 'error' ) {
                $agGetbalance["balance"] = number_format($agGetbalance["info"], 2, '.', ',');
            } else {
                $agGetbalance["balance"] = number_format(0, 2, '.', ',');;;
            }
        }

        $hgId = $row['ID'];

        $cpMasterDbLink = @mysqli_connect($database['cpDefault']['host'],$database['cpDefault']['user'],$database['cpDefault']['password'],$database['cpDefault']['dbname'],$database['cpDefault']['port']) or die("mysqli connect error".mysqli_connect_error()) ;
        $sql = "select lcurrency from ".$database['cpDefault']['prefix']."user where hguid=".$hgId;
        $result = mysqli_query($cpMasterDbLink,$sql);
        $row = mysqli_fetch_assoc($result);
        $cou = mysqli_num_rows($result);

        if($cou==0){
            exit( json_encode( [ 'err'=>'-2','msg'=>'彩票余额获取失败' ] ) );
        }
        $cpFund = $row['lcurrency'];
        $row['Money'] = number_format($row['Money'], 2, '.', ',');

        exit( json_encode( ['err'=>0, 'balance_ag'=>$agGetbalance["balance"], 'balance_hg'=>number_format($aUser['Money'],2), 'balance_cp'=>number_format($cpFund,2) ] ) );
        break;
    case 'ag2hg': // 从AG真人转到体育。首先调用接口，然后成功后再更新本地资金

        $ag_sql = "select username,password,is_test from `".DBPREFIX."ag_users` where `userid` = '{$aUser['ID']}'";
        $ag_result = mysqli_query($dbLink, $ag_sql);
        $ag_row = mysqli_fetch_assoc($ag_result);

        if (AG_TRANSFER_SWITCH === TRUE){
            $url = AG_TRANSFER_URL.'?action=b&username='.$ag_row['username'].'&password='.$ag_row['password'].'&is_test='.$ag_row['is_test'];
            $agGetbalance = file_get_contents($url);
            $agGetbalance = json_decode($agGetbalance, true);
        }
        else{
            $agGetbalance = $oAg->ag_getBalance($ag_row['username'], $ag_row['password'], $ag_row['is_test']);
        }
        if ( $agGetbalance['info'] != 'error' ) {
            $agGetbalance["balance"] = $agGetbalance["info"];
        } else {
            exit( json_encode( [ 'err'=>'-1','msg'=>'AG余额获取失败' ] ) );
        }

        if (floatval($agGetbalance["balance"]) < floatval($b)){
            exit(json_encode( [ 'err'=>'-6','msg'=>'AG余额错误~~' ] ));
        }

        $agGetbalance["balance"] = number_format($agGetbalance["balance"], 2, '.', ',');

        /*if ($b < 1 or $b > 50000){
            exit(json_encode( [ 'err'=>'-2','msg'=>'转账金额有误，请重新输入' ] ));
        }*/

        if(!preg_match("/^[1-9][0-9]*$/",$b)){
            exit(json_encode( [ 'err'=>'-7','msg'=>'转账只支持正整数，请重新输入' ] ));
        }

        $fShiftMoney = bcmul(floatval($b), 1, 2);
        //ag生成流水号
        $oDatetime = new DateTime('NOW');
        $sTime8 = dechex($oDatetime->format('U')); // 8bit
        $sUser6 = sprintf("%06s", substr(dechex($aUser['ID']), 0, 6)); // 6bit
        $sTrans_no = 'AGOUT' . $sTime8 . $sUser6; //AG平台 订单号生成规则

        if (AG_TRANSFER_SWITCH === TRUE){
            $url = AG_TRANSFER_URL.'?action=ag2hg&username='.$ag_row['username'].'&password='.$ag_row['password'].'&transNo='.$sTrans_no.'&shiftMoney='.$fShiftMoney;
            $sResult = file_get_contents($url);
            $aResult = json_decode($sResult, true);
        }
        else{
            $aResult = $oAg->player_withdraw($ag_row['username'],$ag_row['password'], $sTrans_no,$fShiftMoney);
        }

        if (isset($aResult['code'])) {

            if ($aResult['code'] == '-1.02') { // 预备转账失败

                $aErrData['code'] = $aResult['code'];
                $aErrData[''] = 'AGOUT';
                error_log(date('Y-m-d H:i:s').'-'.serialize($aErrData).PHP_EOL, 3, '/tmp/AG_ERROR.log');
                exit(json_encode(['err'=>-4, 'msg'=>"预备转账失败"]));

            } elseif ($aResult['code'] == '-998') { //确认转帐失败，先不回滚，待确认。人工处理

                $oAgGame = new ag_game($dbMasterLink);
                $third_err = $oAgGame->third_deposit_or_withdraw_error_in($ag_row['username'], $sTrans_no, $fShiftMoney);
                exit(json_encode(['err'=>-5, 'msg'=>"确认转账失败，请与客服联系"]));
            }
        }

        $data['userid']=$aUser['ID'];
        $data['Checked']=1;
        $data['reason']='ag to hg';
        $data['AuditDate']=date("Y-m-d H:i:s");
        $data['Gold']=$fShiftMoney;
        $data['AddDate']=date("Y-m-d",time());
        $data['Type']='Q';
        $data['From']='ag';
        $data['To']='hg';
        $data['UserName']=$aUser['UserName'];
        $data['Agents']=$aUser['Agents'];
        $data['World']=$aUser['World'];
        $data['Corprator']=$aUser['Corprator'];
        $data['Super']=$aUser['Super'];
        $data['Admin']=$aUser['Admin'];
        $data['CurType']='RMB';
        $data['Date']=date("Y-m-d H:i:s",time());
        $data['Name']=$aUser['Alias'];
        $data['Waterno']='';
        $data['Phone']=$aUser['Phone'];
        $data['Notes']='即时入账';
        $data['test_flag'] = $aUser['test_flag'];
        $data['Order_Code']=$sTrans_no;

        $sInsData = '';
        foreach ($data as $key => $value){
            if ($key=='Order_Code') {
                $sInsData.= "`$key` = '{$value}'";
            }else{
                $sInsData.= "`$key` = '{$value}',";
            }
        }

        mysqli_autocommit($dbMasterLink,false);// 关闭本次数据库连接的自动命令提交事务模式
        $oRes = mysqli_query($dbMasterLink, "SELECT * FROM ".DBPREFIX."gxfcy_userlock WHERE userid = {$aUser['ID']}");
        $iCou = mysqli_num_rows($oRes);
        if($iCou == 0){
            $insert_flag = mysqli_query($dbMasterLink, "insert into `".DBPREFIX."gxfcy_userlock` set `userid` = {$aUser['ID']}");
            if(!$insert_flag) {
                mysqli_rollback($dbMasterLink);
                exit(json_encode( [ 'err'=>'-17','msg'=>'添加用户锁失败' ] ));
            }
        }
        $lock = mysqli_query($dbMasterLink, "select userid from `".DBPREFIX."gxfcy_userlock` where `userid` = {$aUser['ID']} for update");
        if($lock){
            $lockMoney = mysqli_query($dbMasterLink, "select Money from `".DBPREFIX.MEMBERTABLE."` where `ID` = {$aUser['ID']} for update");
            if($lockMoney){
                // 更新玩家账户余额
                $up = mysqli_query($dbMasterLink,"UPDATE ".DBPREFIX.MEMBERTABLE." SET Money=Money+$fShiftMoney , Online=1 , OnlineTime=now() WHERE ID=".$aUser['ID']);
                if($up){
                    //校验通过开始处理订单
                    $in = mysqli_query($dbMasterLink,"insert into `".DBPREFIX."web_sys800_data` set $sInsData");
                    if($in){
                        //添加资金账变日志
                        $rowMoney=mysqli_fetch_assoc($lockMoney);
                        $moneyLogRes=addAccountRecords(array($aUser['ID'],$aUser['UserName'],$aUser['test_flag'],$rowMoney['Money'],$fShiftMoney,$rowMoney['Money']+$fShiftMoney,24,2,mysqli_insert_id($dbMasterLink),"提款额度转换:真人电子到体育"));
                        if($moneyLogRes){
                            mysqli_commit($dbMasterLink);
                            mysqli_close($dbMasterLink);
                        }else{
                            mysqli_rollback($dbMasterLink);
                            exit(json_encode( [ 'err'=>'-11','msg'=>'添加资金账变记录失败' ] ));
                        }
                    }else{
                        mysqli_rollback($dbMasterLink);
                        exit(json_encode( [ 'err'=>'-10','msg'=>'添加账变记录失败' ] ));
                    }
                }else{
                    mysqli_rollback($dbMasterLink);
                    exit(json_encode( [ 'err'=>'-9','msg'=>'余额更新失败' ] ));
                }
            }else{
                mysqli_rollback($dbMasterLink);
                exit(json_encode( [ 'err'=>'-8','msg'=>'添加用户资金锁失败' ] ));
            }
        }else{
            mysqli_rollback($dbMasterLink);
            exit(json_encode( [ 'err'=>'-8','msg'=>'添加用户锁失败' ] ));
        }
        exit( json_encode( ['err'=>0, 'msg'=>'' ] ) );
        break;
    case 'cp2hg': // 从彩票转账到体育，摘自彩票游戏中的转账
        $cpMasterDbLink = @mysqli_connect($database['cpDefault']['host'],$database['cpDefault']['user'],$database['cpDefault']['password'],$database['cpDefault']['dbname'],$database['cpDefault']['port']) or die("mysqli connect error".mysqli_connect_error());

        $hgSign="+";
        $cpSign="-";
        $flagType=2;
        $orderprefix='cphg';
        $cpTypecode=1;

        $fund = $b;
        $from= $f;
        $to = $t;
        $userName = $aUser['UserName'];
        $id = $aUser['ID'];


        $result = mysqli_query($cpMasterDbLink,"SELECT id,username,lcurrency FROM `gxfcy_user` WHERE hguid=".$id);
        $row = mysqli_fetch_assoc($result);
        $cou = mysqli_num_rows($result);
        $idtTO = $row['id'];
        $usernameTO = $row['username'];
        $lcurrencyTO = $row['lcurrency'] ;

        if (floatval($lcurrencyTO) < floatval($b)){
            exit(json_encode( [ 'err'=>'-6','msg'=>'彩票余额错误~~' ] ));
        }

        $beginFrom = mysqli_query($dbMasterLink,"start transaction");//开启事务$from
        if($beginFrom){
            $oRes = mysqli_query($dbMasterLink, "SELECT * FROM ".DBPREFIX."gxfcy_userlock WHERE userid = {$id}");
            $iCou = mysqli_num_rows($oRes);
            if($iCou == 0){
                $insert_flag = mysqli_query($dbMasterLink, "insert into `".DBPREFIX."gxfcy_userlock` set `userid` = {$id}");
                if(!$insert_flag) {
                    mysqli_query($dbMasterLink,"ROLLBACK");
                    exit(json_encode( [ 'err'=>'-17','msg'=>'添加用户锁失败' ] ));
                }
            }

            $lockUser = mysqli_query($dbMasterLink, "select userid from `".DBPREFIX."gxfcy_userlock` where `userid` = {$aUser['ID']} for update");
            $lockMoney = mysqli_query($dbMasterLink, "select Money,test_flag from `".DBPREFIX.MEMBERTABLE."` where `ID` = {$id} for update");
            if($lockUser && $lockMoney){
                $rowMoney=mysqli_fetch_assoc($lockMoney);
                $moneyf = $rowMoney["Money"];
                $currency_after = $moneyf+$fund;
                $orderCode=$orderprefix.creatOrderCode();//获取订单号
                $addFundRecordFromSql="INSERT INTO ".DBPREFIX."web_sys800_data(`userid`,`Checked`,`Gold`,`moneyf`,`currency_after`,`AddDate`,`Type`,`From`,`To`,`UserName`,`Agents`,`World`,`Corprator`,`Super`,`Admin`,`Name`,`CurType`,`Date`,`AuditDate`,`Order_Code`,`reason`,`test_flag`)
					    VALUES($id,1,$fund,$moneyf,$currency_after,CURDATE(),'Q','$from','$to','$userName','$Agents','$World','$Corprator','$Super','$Admin','$Alias','RMB',NOW(),NOW(),'$orderCode','额度转换',$test_flag)";
                $addFundRecordFrom = mysqli_query($dbMasterLink,$addFundRecordFromSql);//添加历史记录表
                if($addFundRecordFrom){
                    $inserId=mysqli_insert_id($dbMasterLink);
                    $updateUserFundFrom = mysqli_query($dbMasterLink,"UPDATE ".DBPREFIX.MEMBERTABLE." SET Money=Money".$hgSign.$fund." , Online=1 , OnlineTime=now() WHERE ID=".$id);//更新用户资金数据$from
                    if($updateUserFundFrom){
                        $rowMoney=mysqli_fetch_assoc($lockMoney);
                        $moneyLogRes=addAccountRecords(array($id,$userName,$rowMoney['test_flag'],$rowMoney['Money'],$fund,$rowMoney['Money']+$fund,22,2,$inserId,"提款额度转换:彩票到体育"));
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
                                                exit( json_encode(array('status'=>-2,'message'=>'额度有误，请确认金额！')));
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
                                                            exit( json_encode( ['err'=>0, 'msg'=>'' ] ) );
                                                            exit;
                                                        }else{
                                                            mysqli_query($dbMasterLink,"ROLLBACK");
                                                            $note="用户".$usernameTO."(".$id.")".$from."平台扣款失败";
                                                            echo "UPDATE ".DBPREFIX."fundlimitrans_flag SET note='".$note."' where ordercode='".$orderCode;
                                                            $updateFlagFrom = mysqli_query($dbMasterLink,"UPDATE ".DBPREFIX."fundlimitrans_flag SET note='".$note."' where ordercode='".$orderCode."'");//更新用户资金数据$from
                                                            //To方到账 From未完成该扣款，flag仍然存在，根据flag对From方进行扣款即可，之后完成手动删除
                                                            exit(json_encode( [ 'err'=>'-16','msg'=>'扣款失败！' ] ));
                                                            exit;
                                                        }
                                                    }
                                                }else{
                                                    mysqli_query($cpMasterDbLink,"ROLLBACK");
                                                    exit(json_encode( [ 'err'=>'-14','msg'=>'添加资金变更记录失败' ] ));
                                                }
                                            }else{
                                                mysqli_query($cpMasterDbLink,"ROLLBACK");
                                                exit(json_encode( [ 'err'=>'-13','msg'=>'添加资金变更日志失败' ] ));
                                            }
                                        }else{
                                            mysqli_query($cpMasterDbLink,"ROLLBACK");
                                            exit(json_encode( [ 'err'=>'-12','msg'=>'彩票用户资金更新失败！' ] ));
                                        }
                                    }else{
                                        mysqli_query($cpMasterDbLink,"ROLLBACK");
                                        echo json_encode(array('status'=>-18,'message'=>'CP用户资金锁开启失败！')); exit;
                                    }
                                }else{
                                    mysqli_query($cpMasterDbLink,"ROLLBACK");
                                    echo json_encode(array('status'=>-17,'message'=>'CP事务开启失败！')); exit;
                                }
                            }else{
                                mysqli_query($dbMasterLink,"ROLLBACK");
                                exit(json_encode( [ 'err'=>'-15','msg'=>'添加中间记录失败' ] ));
                            }
                        }else{
                            mysqli_query($dbMasterLink,"ROLLBACK");
                            exit(json_encode([ 'err'=>'-15','msg'=>'资金账变记录添加失败！' ]));
                        }
                    }else{
                        mysqli_query($dbMasterLink,"ROLLBACK");
                        exit(json_encode( [ 'err'=>'-11','msg'=>'更新用户资金失败' ] ));
                    }
                }else{
                    mysqli_query($dbMasterLink,"ROLLBACK");
                    exit(json_encode( [ 'err'=>'-10','msg'=>'添加历史记录表失败' ] ));
                }
            }else{
                mysqli_query($dbMasterLink,"ROLLBACK");
                exit(json_encode( [ 'err'=>'-9','msg'=>"锁定记录失败" ] ));
            }
        }
        echo json_encode(array('status'=>-100,'msg'=>'操作失败！'));
        exit;
        break;
    default:
        exit(json_encode(array('status'=>-999,'msg'=>'非法操作！')));
        break;
}


function creatOrderCode(){
    $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
    $orderSn = $yCode[mt_rand(0,9)] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
    return $orderSn;
}