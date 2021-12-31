<?php
/**
 * AG真人接口
 *   检查或者注册AG账号  action = a
 *   查询真人余额 action = b
 *   预备转账
 *   额度转换
 *   查询订单状态
 *
 */

include ("address.mem.php");
include('config.php');

$langx=$_SESSION['langx'];
require "agproxy.php";
include "aggame.php";

//var_dump( $_SESSION ); die;

// $uid=$_SESSION['Oid'];
$action = $_REQUEST['action'];
$username = $_REQUEST['username'];
$f=$_REQUEST['f'];
$t=$_REQUEST['t'];
$b=$_REQUEST['b']; // 转账金额
$lv = isset($_POST['lv'])? $_POST['lv']:""; // 管理员层级
$loginname = isset($_POST['loginname'])? $_POST['loginname']:""; // 管理员用户名
if ($f=='hg'&&$t=='ag'){ // 皇冠转 ag
    $action='hg2ag';
}
if ($f=='ag'&&$t=='hg'){ // ag 转皇冠
    $action='ag2hg';
}

$sql = "select `ID`,`Money`,`test_flag`,`UserName`,`Agents`,`World`,`Corprator`,`Super`,`Admin`,`Alias`,`Phone` from ".DBPREFIX.MEMBERTABLE." where UserName='$username' and Status<=1";
$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);
$cou=mysqli_num_rows($result);
if($cou==0){
    exit(json_encode( [ 'err'=>'-1','msg'=>'用戶名不存在' ] ) );
}
$aUser = $row;

/*$sql = "select `Prefix` from `".DBPREFIX."web_agents_data` where `UserName`='{$aUser['Agents']}' ";
$result = mysqli_query($dbLink,$sql);
$cou = mysqli_num_rows($result);
if ($cou==0){
    exit(json_encode( [ 'err'=>'-11','msg'=>'代理错误' ] ) );
}
$row = mysqli_fetch_assoc($result);
$aAgent=$row;
$sPrefix = $aAgent['Prefix'];*/

$sPrefix = $agsxInitp['data_api_cagent']; // 新建账号增加AG代理前缀
$userPrefix = $agsxInitp['data_api_user_prefix'];// 新建账号增加AG用户前缀
$domain_url = $agsxInit['domain_url'];
$api_url = $agsxInit['api_url'];
$game_api_url = $agsxInit['game_api_url'];
$cagent = $agsxInit['cagent'];
$md5_key = $agsxInit['md5_key'];
$des_key = $agsxInit['des_key'];
$testers = $agsxInit['tester'];
$cny = $agsxInit['cur'];
$oAg = new agproxy($domain_url, $api_url, $game_api_url, $cagent, $md5_key, $des_key, $testers, $cny);

if( $action != 'cga' ){
    $ag_sql = "select username,password,is_test from `".DBPREFIX."ag_users` where `userid` = '{$aUser['ID']}'";
    $ag_result = mysqli_query($dbLink, $ag_sql);
    $ag_cou = mysqli_num_rows($ag_result);
    if ($ag_cou == 0) {
        exit( json_encode( [ 'err'=>-3, 'msg'=> 'AG账号异常~~' ] ) );
    }
    $ag_row = mysqli_fetch_assoc($ag_result);
}
switch ($action){
    case 'b': // 获取余额

        if (AG_TRANSFER_SWITCH === TRUE) {
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

        exit( json_encode( ['err'=>0, 'balance_ag'=>number_format($agGetbalance["balance"],2), 'balance_hg'=>number_format($row['Money'],2) ] ) );

        break;
    case 'ag2hg': // 从AG真人转到体育。首先调用接口，然后成功后再更新本地资金

        if (AG_TRANSFER_SWITCH === TRUE) {
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

        if ($agGetbalance["balance"] < $b){
            exit(json_encode( [ 'err'=>'-6','msg'=>'AG余额错误~~' ] ));
        }

//        if ($b < 1 or $b > 50000){
//            exit(json_encode( [ 'err'=>'-2','msg'=>'转账金额有误，请重新输入' ] ));
//        }

//        if(!preg_match("/^[1-9][0-9]*$/",$b)){
//            exit(json_encode( [ 'err'=>'-7','msg'=>'转账只支持正整数，请重新输入' ] ));
//        }

        $fShiftMoney = bcmul(floatval($b), 1, 2);
        //ag生成流水号
        $oDatetime = new DateTime('NOW');
        $sTime8 = dechex($oDatetime->format('U')); // 8bit
        $sUser6 = sprintf("%06s", substr(dechex($aUser['ID']), 0, 6)); // 6bit
        $sTrans_no = 'AGOUT' . $sTime8 . $sUser6; //AG平台 订单号生成规则

        if (AG_TRANSFER_SWITCH === TRUE) {
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
		
        mysqli_autocommit($dbMasterLink,false);// 关闭本次数据库连接的自动命令提交事务模式
        $oRes = mysqli_query($dbMasterLink, "SELECT userid FROM ".DBPREFIX."gxfcy_userlock WHERE userid = {$aUser['ID']}");
        $iCou = mysqli_num_rows($oRes);
        if($iCou == 0){
            $insert_flag = mysqli_query($dbMasterLink, "insert into `".DBPREFIX."gxfcy_userlock` set `userid` = {$aUser['ID']}");
			if(!$insert_flag) {
				mysqli_rollback($dbMasterLink);
				exit(json_encode( [ 'err'=>'-17','msg'=>'添加用户锁失败' ] ));
			}
        }
        $lock = mysqli_query($dbMasterLink, "select userid from `".DBPREFIX."gxfcy_userlock` where `userid` = {$aUser['ID']} for update");
        if ($lock){

            $lockMoney = mysqli_query($dbMasterLink, "select Money from `".DBPREFIX.MEMBERTABLE."` where `ID` = {$aUser['ID']} for update");
            if($lockMoney) {

                $aMoney = mysqli_fetch_assoc($lockMoney);
                $moneyf = $aMoney['Money'];
                $currency_after = $aMoney['Money'] + $fShiftMoney;
                // 更新玩家账户余额
                $up = mysqli_query($dbMasterLink, "UPDATE " . DBPREFIX.MEMBERTABLE." SET Money=Money+$fShiftMoney WHERE ID=" . $aUser['ID']);
                if ($up) {

                    $data['userid']=$aUser['ID'];
                    $data['Checked']=1;
                    $data['reason']='ag to hg';
                    $data['AuditDate']=date("Y-m-d H:i:s");
                    $data['Gold']=$fShiftMoney;
                    $data['moneyf']=$moneyf;
                    $data['currency_after']=$currency_after;
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
                    //校验通过开始处理订单
                    $in = mysqli_query($dbMasterLink, "insert into `" . DBPREFIX . "web_sys800_data` set $sInsData");
                    if ($in) {
                    	$inserId=mysqli_insert_id($dbMasterLink);
                    	$moneyLogRes=addAccountRecords(array($aUser['ID'],$aUser['UserName'],$aUser['test_flag'],$moneyf,$fShiftMoney,$currency_after,24,6,$inserId,"资金归集"));
                    	if($moneyLogRes){
                    		mysqli_commit($dbMasterLink);
                    		 // 插入系统日志
					        $loginfo = $loginname.' 对会员帐号 <font class="green">'.$username.'</font> 进行了<font class="red">AG资金归集</font>操作,金额为 <font class="red">'.number_format($b,2).'</font>,转账单号 <font class="blue">'.$sTrans_no.'</font> ' ;
					        innsertSystemLog($loginname,$lv,$loginfo);
                    	}else{
                    		mysqli_rollback($dbMasterLink);
                        	exit(json_encode(['err' => '-10', 'msg' => '添加用户资金账变记录失败']));
                    	}
                    } else {
                        mysqli_rollback($dbMasterLink);
                        exit(json_encode(['err' => '-10', 'msg' => '添加账变记录失败']));
                    }

                } else {
                    mysqli_rollback($dbMasterLink);
                    exit(json_encode(['err' => '-9', 'msg' => '余额更新失败']));
                }
            }else{
                mysqli_rollback($dbMasterLink);
                exit(json_encode( [ 'err'=>'-8','msg'=>'添加用户资金锁失败' ] ));
            }
        }else{
            mysqli_rollback($dbMasterLink);
            exit(json_encode( [ 'err'=>'-8','msg'=>'添加用户锁失败' ] ));
        }

        exit( json_encode( ['err'=>0, 'msg'=>'' ] ) ); // 转账成功
        break;
}



