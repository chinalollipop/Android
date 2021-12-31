<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include ("address.mem.php");
require ("config.php");

$cpMasterDbLink = @mysqli_connect($database['cpDefault']['host'],$database['cpDefault']['user'],$database['cpDefault']['password'],$database['cpDefault']['dbname'],$database['cpDefault']['port']) or die("mysqli connect error".mysqli_connect_error());

if(!is_array($_SESSION['notice_idstic'])) {
    $_SESSION['notice_idstic'] = array();
}

function creatOrderCode(){
    $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
    $orderSn = $yCode[mt_rand(0,9)] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
    return $orderSn;
}

$action = isset($_POST['action'])?$_POST['action']:'' ;
$userName = isset($_POST['username'])? $_POST['username']:"";

if($action == 'b'){ // 查询余额
    $acsql = "select ID,UserName,Money from ".DBPREFIX.MEMBERTABLE." where UserName='$userName'";
    $acresult = mysqli_query($dbLink,$acsql);
    $acrow = mysqli_fetch_assoc($acresult);
    $hgmoney = number_format($acrow['Money'],2); // 体育余额
    $hgId=$acrow['ID']; // 皇冠 id

    $cpMasterDbLink = @mysqli_connect($database['cpDefault']['host'],$database['cpDefault']['user'],$database['cpDefault']['password'],$database['cpDefault']['dbname'],$database['cpDefault']['port']) or die("mysqli connect error".mysqli_connect_error()) ;
    $cpsql = "select lcurrency from ".$database['cpDefault']['prefix']."user where hguid=".$hgId;
    $cpresult = mysqli_query($cpMasterDbLink,$cpsql);
    $cprow = mysqli_fetch_assoc($cpresult);
    $cpcou = mysqli_num_rows($cpresult);
    if($cpresult){
        $cpFund = $cprow['lcurrency']; // 彩票余额
        echo json_encode(array('status'=>200,'err'=>0,'message'=>'余额查询成功！','cp_balance'=>$cpFund?$cpFund:'0.00'));exit;
    }else{
        echo json_encode(array('status'=>501,'message'=>'余额查询失败！'));exit;
    }


}

if($action == 'fundLimitTrans') { // 资金归集
    if(!$_POST['id'] || !$_POST['from'] || !$_POST['to'] || !$_POST['fund']){
        echo json_encode(array('status'=>1,'message'=>'2缺少参数！'));
        exit;
    }
    if(!is_numeric($_POST['fund'])){
        echo json_encode(array('status'=>1,'message'=>'3转换金额无效！'));
        exit;
    }
    $id = $_POST['id'];
    $from = $_POST['from'];
    $to = $_POST['to'];
    $fund = $_POST['fund']; // 金额
    $lv = isset($_POST['lv'])? $_POST['lv']:""; // 管理员层级
    $loginname = isset($_POST['loginname'])? $_POST['loginname']:""; // 管理员用户名

    $result = mysqli_query($cpMasterDbLink,"SELECT id,username,lcurrency FROM `gxfcy_user` WHERE hguid=".$id);
    $row = mysqli_fetch_assoc($result);
    $cou = mysqli_num_rows($result);
    $idtTO = $row['id'];
    $usernameTO = $row['username'];
    $lcurrencyTO = $row['lcurrency'];
    
    
    $sql = "select ID,Money,UserName,Agents,World,Corprator,Super,Admin,Alias,Phone,test_flag from ".DBPREFIX.MEMBERTABLE." where UserName='$userName' and Status=0";
	$result = mysqli_query($dbLink,$sql);
	$rowM=mysqli_fetch_assoc($result);
	$cou=mysqli_num_rows($result);
	if($cou==0){
	    echo json_encode(array('status'=>-111,'message'=>'操作用户不存在！'));
		exit;
	}
	$test_flag=$rowM['test_flag'];
	$Agents=$rowM['Agents'];
	$World=$rowM['World'];
	$Corprator=$rowM['Corprator'];
	$Super=$rowM['Super'];
	$Admin=$rowM['Admin'];
	$Alias=$rowM['Alias'];
	
        if($from=="hg" && $to=="cp"){ // 皇冠转彩票
            $hgSign="-";
            $cpSign="+";
            $flagType=1;
            $orderprefix='hgcp';
            $cpTypecode=0;
        }elseif($from=="cp" && $to=="hg"){ // 彩票转皇冠
            $hgSign="+";
            $cpSign="-";
            $flagType=2;
            $orderprefix='cphg';
            $cpTypecode=1;
        }
        $beginFrom = mysqli_query($dbMasterLink,"start transaction");//开启事务$from
        if($beginFrom){
        		$oRes = mysqli_query($dbMasterLink, "SELECT * FROM ".DBPREFIX."gxfcy_userlock WHERE userid = {$id}");
		        $iCou = mysqli_num_rows($oRes);
		        if($iCou == 0){
		            mysqli_query($dbMasterLink, "insert into `".DBPREFIX."gxfcy_userlock` set `userid` = {$id}");
		       	}
		        $lockUser = mysqli_query($dbMasterLink, "select userid from `".DBPREFIX."gxfcy_userlock` where `userid` = {$id} for update");	
	        	$lockMoney = mysqli_query($dbMasterLink, "select Money,test_flag from `".DBPREFIX.MEMBERTABLE."` where `ID` = {$id} for update");
	            if($lockUser && $lockMoney){
		            $rowMoney=mysqli_fetch_assoc($lockMoney);
            		$moneyf = $rowMoney["Money"];
		            $currency_after = $moneyf+$fund;
		            $moneyCur=$fund;
		            $logDes='资金归集';
					$logType=22;
					$orderCode=$orderprefix.creatOrderCode();//获取订单号
					$addFundRecordFromSql="INSERT INTO ".DBPREFIX."web_sys800_data(`userid`,`Checked`,`Gold`,`moneyf`,`currency_after`,`AddDate`,`Type`,`From`,`To`,`UserName`,`Agents`,`World`,`Corprator`,`Super`,`Admin`,`Name`,`CurType`,`Date`,`AuditDate`,`Order_Code`,`reason`,`test_flag`)
				    	VALUES($id,1,$fund,$moneyf,$currency_after,CURDATE(),'Q','$from','$to','$userName','$Agents','$World','$Corprator','$Super','$Admin','$Alias','RMB',NOW(),NOW(),'$orderCode','额度转换',$test_flag)";
	            	$addFundRecordFrom = mysqli_query($dbMasterLink,$addFundRecordFromSql);
	                if($addFundRecordFrom){
	                	$inserId=mysqli_insert_id($dbMasterLink);
	                    $updateUserFundFrom = mysqli_query($dbMasterLink,"UPDATE ".DBPREFIX.MEMBERTABLE." SET Money=Money".$hgSign.$fund." WHERE ID=".$id);//更新用户资金数据$from
	                    if($updateUserFundFrom){
	                    		$moneyLogRes=addAccountRecords(array($id,$userName,$rowMoney['test_flag'],$moneyf,$moneyCur,$currency_after,$logType,6,$inserId,$logDes));
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
					                                                // 插入系统日志
					                                                $loginfo = $loginname.' 对会员帐号 <font class="green">'.$userName.'</font> 进行了<font class="red">彩票资金归集</font>操作,金额为 <font class="red">'.number_format($fund,2).'</font>,转账单号 <font class="blue">'.$orderCode.'</font> ' ;
					                                                innsertSystemLog($loginname,$lv,$loginfo);
					                                                echo json_encode(array('status'=>0,'message'=>'操作成功！'));
					                                                exit;
					                                            }else{
	 																mysqli_query($dbMasterLink,"ROLLBACK");
					                                            	$note="用户".$usernameTO."(".$id.")".$from."平台扣款失败";
					                                                echo "UPDATE ".DBPREFIX."fundlimitrans_flag SET note='".$note."' where ordercode='".$orderCode;
					                                                $updateFlagFrom = mysqli_query($dbMasterLink,"UPDATE ".DBPREFIX."fundlimitrans_flag SET note='".$note."' where ordercode='".$orderCode."'");//更新用户资金数据$from
					                                                //To方到账 From未完成该扣款，flag仍然存在，根据flag对From方进行扣款即可，之后完成手动删除
					                                                echo json_encode(array('status'=>-15,'message'=>'扣款失败！'));  
					                                                exit;
					                                            }
					                                        }
					                                    }else{
					                                    	mysqli_query($cpMasterDbLink,"ROLLBACK");
					                                    	echo json_encode(array('status'=>-14,'message'=>'添加资金变更记录失败！'));exit;  
					                                    }
					                                }else{
					                            		mysqli_query($cpMasterDbLink,"ROLLBACK");
					                               		echo json_encode(array('status'=>-13,'message'=>'添加资金变更日志失败！')); exit;    
					                                }
					                            }else{
					                        		mysqli_query($cpMasterDbLink,"ROLLBACK");
					                           		echo json_encode(array('status'=>-12,'message'=>'彩票用户资金更新失败！'));exit;    
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
			                    		echo json_encode(array('status'=>-15,'message'=>'添加中间记录失败！')); exit; 
			                    	}
	                    		}else{
	                    			mysqli_query($dbMasterLink,"ROLLBACK");
		                    		echo json_encode(array('status'=>-15,'message'=>'资金账变记录添加失败！')); exit; 
	                    		}
	                    }else{
	                    	mysqli_query($dbMasterLink,"ROLLBACK");
	                    	echo json_encode(array('status'=>-11,'message'=>'更新用户资金失败！'));exit;   
	                    }
	                }else{
	                	mysqli_query($dbMasterLink,"ROLLBACK");
	                	echo json_encode(array('status'=>-10,'message'=>'添加历史记录表失败！'));exit;    
	                }
	            }else{
	        		mysqli_query($dbMasterLink,"ROLLBACK");
	           		echo json_encode(array('status'=>-9,'message'=>'锁定记录失败！'));exit;    
	            }
        }
        echo json_encode(array('status'=>0,'message'=>'操作失败！'));
}
?>