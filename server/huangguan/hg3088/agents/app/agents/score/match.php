<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include ("../include/address.mem.php");
require_once ("../include/config.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$loginname=$_SESSION['UserName'];
$langx=$_SESSION["langx"];
require ("../include/traditional.$langx.inc.php");

$lv = $_SESSION['admin_level']; // 管理员层级
$uid=$_REQUEST["uid"];
$gid=$_REQUEST['gid'];
$open=$_REQUEST['open']; // open 1 开盘操作 ，0 关盘操作
$date_start=$_REQUEST['date_start'];
$gtype=$_REQUEST['gtype'];
$page=$_REQUEST["page"];
$league=$_REQUEST["league"];
$seachtext = $_REQUEST['seachtext'] ; // 搜索赛事
$actionname= isset($_REQUEST['actionname'])?$_REQUEST['actionname']:'' ; // 注单处理名称
$betdetail=$_REQUEST["betdetail"]; // 用于注单笔数，投注额，输赢
$match_pwd=$_REQUEST["match_pwd"]; // 关闭盘口需要输入密码
$pk_pwd = 'bysbgbpky';

if(isset($seachtext)&&strlen($seachtext)>0){
    if($gtype=='FS'){
        $seachwhere = " and (M_League LIKE '%$seachtext%' or MB_Team  LIKE '%$seachtext%')"; // 用于模糊搜索
    }else{
        $seachwhere = " and (M_League LIKE '%$seachtext%' or MB_Team  LIKE '%$seachtext%' or TG_Team  LIKE '%$seachtext%')"; // 用于模糊搜索
    }
}else{
    $seachwhere='';
}

if($gtype==''){
    $gtype='FT';
}
if($date_start=='') {
    $date_start=date('Y-m-d');
}
if($league==""){
    $sleague="";
}else{
    $sleague="and $m_league='".$league."'";
}

$action=$_REQUEST['action'];
$confirmed=$_REQUEST['confirmed'];

//$action==1 取消赛事和注单
if($action==1){
    $errorArr=array();
    $halfLineType=array(11,12,13,14,204,15,46,19,20,31,50,165,123,144,204,205,206,244);
    if($gtype=='FS'){
        $rsql = "select ID from ".DBPREFIX."web_report_data where MID='".$gid."' and Pay_Type=1 and LineType=16";
    }else{
        $rsql = "select ID from ".DBPREFIX."web_report_data where MID='".$gid."' and Pay_Type=1";
    }
    $rresult = mysqli_query($dbLink, $rsql);
    $rrowCount = mysqli_num_rows($rresult);
    while ($rrow = mysqli_fetch_assoc($rresult)){
        $u_sql='';
        $beginFrom = mysqli_query($dbMasterLink,"start transaction");
        if($beginFrom){
            $id=$rrow['ID'];
            $resultCheck = mysqli_query($dbMasterLink,"select userid,M_Name,Pay_Type,BetScore,M_Result,LineType,Cancel from ".DBPREFIX."web_report_data where ID=$id for update");
            $rowCheck = mysqli_fetch_assoc($resultCheck);
            if( $rowCheck['Cancel']==0 ){
                $userid=$rowCheck['userid'];
                $username=$rowCheck['M_Name'];
                $betscore=$rowCheck['BetScore'];
                $m_result=$rowCheck['M_Result'];
                $resultMem = mysqli_query($dbMasterLink,"select ID,UserName,test_flag,Money from  ".DBPREFIX.MEMBERTABLE." where ID=$userid for update");
                if($resultMem){
                    $rowMem = mysqli_fetch_assoc($resultMem);
                    if($m_result==''){//未结算
                        if($confirmed==-51 || $confirmed==-52){
                            if($confirmed==-51&&in_array($rowCheck['LineType'],$halfLineType)){//斩半场
                                $u_sql = "update ".DBPREFIX.MEMBERTABLE." set Money=Money+$betscore where ID=".$userid;
                            }
                            if($confirmed==-52&&!in_array($rowCheck['LineType'],$halfLineType)){//斩全场
                                $u_sql = "update ".DBPREFIX.MEMBERTABLE." set Money=Money+$betscore where ID=".$userid;
                            }
                        }else{
                            $u_sql = "update ".DBPREFIX.MEMBERTABLE." set Money=Money+$betscore where ID=".$userid;
                        }
                    }else{//已结算

                        /*if (intval($rowMem['Money']) < intval($m_result)){
                            mysqli_query($dbMasterLink,"ROLLBACK");
                            $errorArr[$id]="{$id}会员资金不足，取消赛事和注单失败！";
                            continue;
                        }*/

                        if($confirmed==-51 || $confirmed==-52){
                            if($confirmed==-51&&in_array($rowCheck['LineType'],$halfLineType)){//斩半场
                                $u_sql = "update ".DBPREFIX.MEMBERTABLE." set Money=Money-$m_result where ID=".$userid;
                            }
                            if($confirmed==-52&&!in_array($rowCheck['LineType'],$halfLineType)){//斩全场
                                $u_sql = "update ".DBPREFIX.MEMBERTABLE." set Money=Money-$m_result where ID=".$userid;
                            }
                        }else{
                            $u_sql = "update ".DBPREFIX.MEMBERTABLE." set Money=Money-$m_result where ID=".$userid;
                        }
                    }
                    if(strlen($u_sql)>0){
                        if(mysqli_query($dbMasterLink,$u_sql)){
                            if($confirmed==-51){//斩半场
                                $strLineType=implode(',',$halfLineType);
                                $sql1="update ".DBPREFIX."web_report_data set VGOLD=0,M_Result=0,A_Result=0,B_Result=0,C_Result=0,D_Result=0,T_Result=0,Confirmed='$confirmed',Danger=0,Cancel=1,Checked=1,updateTime='".date('Y-m-d H:i:s',time())."' where `ID`='".$id."' and LineType in(".$strLineType.")";
                            }elseif($confirmed==-52){//斩全场
                                $strLineType=implode(',',$halfLineType);
                                $sql1="update ".DBPREFIX."web_report_data set VGOLD=0,M_Result=0,A_Result=0,B_Result=0,C_Result=0,D_Result=0,T_Result=0,Confirmed='$confirmed',Danger=0,Cancel=1,Checked=1,updateTime='".date('Y-m-d H:i:s',time())."' where `ID`='".$id."' and LineType not in(".$strLineType.")";
                            }else{
                                $sql1="update ".DBPREFIX."web_report_data set VGOLD=0,M_Result=0,A_Result=0,B_Result=0,C_Result=0,D_Result=0,T_Result=0,Confirmed='$confirmed',Danger=0,Cancel=1,Checked=1,updateTime='".date('Y-m-d H:i:s',time())."' where `ID`='".$id."'";
                            }
                            if(mysqli_query($dbMasterLink,$sql1)){
                                $descCancel='Score'.$confirmed*-1;
                                if($m_result==''){
                                    $moneyLog=$betscore;
                                    $moneyDesLog=$$descCancel.'：退回用户投注金额';
                                }else{
                                    $moneyLog=$m_result*-1;
                                    if($m_result==0){
                                        $moneyDesLog=$$descCancel.'：和局,无资金变化';
                                    }elseif($m_result>0){
                                        $moneyDesLog=$$descCancel."：取消派彩,平台入款{$m_result}";
                                    }elseif($m_result<0){
                                        if($m_result==$betscore*-1){
                                            $moneyDesLog=$$descCancel."：退回用户投注金额";
                                        }else{
                                            $moneyDesLog=$$descCancel."：取消派彩,平台入款{$m_result}";
                                        }
                                    }
                                }
                                if($gtype=='FS'){
                                    $moneyDesLog="[审核比分-冠军]".$moneyDesLog.",操作人:{$loginname}";
                                }else{
                                    $moneyDesLog="[审核比分]".$moneyDesLog.",操作人:{$loginname}";
                                }
                                $moneyLogRes=addAccountRecords(array($userid,$username,$rowMem['test_flag'],$rowMem['Money'],$moneyLog,$rowMem['Money']+$moneyLog,2,6,$id,$moneyDesLog));
                                if($moneyLogRes){
                                    mysqli_query($dbMasterLink,"COMMIT");
                                }else{
                                    mysqli_query($dbMasterLink,"ROLLBACK");
                                    $errorArr[$id]="{$id}用户资金账变添加失败！";
                                    continue;
                                }
                            }else{
                                mysqli_query($dbMasterLink,"ROLLBACK");
                                $errorArr[$id]="{$id}订单更新失败！";
                                continue;
                            }
                        }else{
                            mysqli_query($dbMasterLink,"ROLLBACK");
                            $errorArr[$id]="{$id}用户资金账户更新失败！";
                            continue;
                        }
                    }
                }else{
                    mysqli_query($dbMasterLink,"ROLLBACK");
                    $errorArr[$id]="{$id}用户资金锁定失败！";
                    continue;
                }
            }else{
                mysqli_query($dbMasterLink,"ROLLBACK");
                $errorArr[$id]="{$id}订单已被取消,不能重复操作！";
                continue;
            }
        }else{
            mysqli_query($dbMasterLink,"ROLLBACK");
            $errorArr[$id]="{$id}事务开启失败！";
            continue;
        }
    }

    if(count($errorArr)==0){
        if($confirmed==-51){//斩半场
            $sql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set TG_Inball_HR='-2',MB_Inball_HR='-2' where `Type`='".$gtype."' and `MID`='".$gid."'";
            mysqli_query($dbMasterLink,$sql) or die ("操作失败1");
        }elseif($confirmed==-52){//斩全场
            $sql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='-2',TG_Inball='-2' where `Type`='".$gtype."' and `MID`='".$gid."'";
            mysqli_query($dbMasterLink,$sql) or die ("操作失败1");
        }else{
            if($gtype=='FS'){
                $sql="update ".DBPREFIX.SPORT_FLUSH_FS_MATCH_TABLE." set win=0,score=0,Cancel=1 where `MID`='".$gid."'";
                mysqli_query($dbMasterLink,$sql) or die ("操作失败1");
            }else{
                $sql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='$confirmed',TG_Inball='$confirmed',TG_Inball_HR='$confirmed',MB_Inball_HR='$confirmed',Score=1,Cancel=1 where `Type`='".$gtype."' and `MID`='".$gid."'";
                mysqli_query($dbMasterLink,$sql) or die ("操作失败1");
            }
        }
        /* 插入系统日志 */
        if($gtype=='FS'){
            $loginfo = $loginname.' 在审核-冠军比分中对赛事进行了 <font class="red">'.$actionname.'</font> 操作,gid 为 <font class="red">'.$gid.'</font>,gid 为 <font class="red">'.$gid.'</font>,gtype 为 <font class="blue">'.$gtype.'</font>' ;
            innsertSystemLog($loginname,$lv,$loginfo);
        }else{
            $loginfo = $loginname.' 在审核比分中对赛事进行了 <font class="red">'.$actionname.'</font> 操作,gid 为 <font class="red">'.$gid.'</font>,gid 为 <font class="red">'.$gid.'</font>,gtype 为 <font class="blue">'.$gtype.'</font>' ;
            innsertSystemLog($loginname,$lv,$loginfo);
        }
    }else{
        $errorMessage=implode("<br>",$errorArr);
        echo "<script>alert('".$errorMessage."');</script>";
    }
}

//$action==2 恢复赛事和注单
if($action==2){
	$errorArr=array();
	$halfLineType=array(11,12,13,14,204,15,46,19,20,31,50,165,123,144,204,205,206,244);
	if($gtype=='FS'){
		$rowCheck=array('MB_Inball'=>0,'MB_Inball_HR'=>0,'LineType'=>16);
		$rsql = "select ID,orderNo,userid,M_Name,Pay_Type,BetScore,M_Result,LineType,Cancel,Checked from ".DBPREFIX."web_report_data where MID='".$gid."' and LineType=16 and Pay_Type=1";	
	}else{
		$sql="select MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `MID`='".$gid."'";
		$resultCheck = mysqli_query($dbLink,$sql) or die ("操作失败168");
		$rowCheck = mysqli_fetch_assoc($resultCheck);
		$rsql = "select ID,orderNo,userid,M_Name,Pay_Type,BetScore,M_Result,LineType,Cancel,Checked from ".DBPREFIX."web_report_data where MID='".$gid."' and Pay_Type=1";
	}
	$rresult = mysqli_query($dbLink, $rsql);
	while($rrow = mysqli_fetch_assoc($rresult)){
		$beginFrom = mysqli_query($dbMasterLink,"start transaction");
		$id=$rrow['ID'];
		if($beginFrom){
			$orderno=$rrow['orderNo'];
			$userid=$rrow['userid'];
			$username=$rrow['M_Name'];
			$betscore=$rrow['BetScore'];
			$resForupdate = mysqli_query($dbMasterLink,"select Checked,M_Result,BetScore from ".DBPREFIX."web_report_data where ID=$id for update");
			$rowForupdate = mysqli_fetch_assoc($resForupdate);
			if($rowForupdate['Checked']==1){//有结果
				$m_result=$rowForupdate['M_Result'];
				$resultMem = mysqli_query($dbMasterLink,"select ID,UserName,test_flag,Money from  ".DBPREFIX.MEMBERTABLE." where ID=$userid for update");
				if($resultMem){
					if($rowCheck['MB_Inball']==-52 || $rowCheck['MB_Inball_HR']==-51){
						if($rowCheck['MB_Inball_HR']==-51 && in_array($rowCheck['LineType'],$halfLineType)){//半场
							$cash=$betscore+$m_result;
							$u_sql ="update ".DBPREFIX.MEMBERTABLE." SET Money=Money-$cash where ID=".$userid;
						}
						if($rowCheck['MB_Inball_HR']==-52 && !in_array($rowCheck['LineType'],$halfLineType)){//全场
							$cash=$betscore+$m_result;
							$u_sql ="update ".DBPREFIX.MEMBERTABLE." SET Money=Money-$cash where ID=".$userid;
						}
					}else{
						$cash=$betscore+$m_result;
						$u_sql ="update ".DBPREFIX.MEMBERTABLE." SET Money=Money-$cash where ID=".$userid;
					}
					if(strlen($u_sql)>0){
						$rowMem = mysqli_fetch_assoc($resultMem);
						/*if($cash>0 && $rowMem['Money'] < $cash) {
							mysqli_query($dbMasterLink,"ROLLBACK");
    						$errorArr[$id]="用户{$userid}资金不足,恢复订单{$orderno}失败！";
							continue;
						}*/
						if(mysqli_query($dbMasterLink,$u_sql)){
							if($gtype=='FS'){
								$moneyLogRes=addAccountRecords(array($userid,$username,$rowMem['test_flag'],$rowMem['Money'],$cash*-1,$rowMem['Money']-$cash,5,6,$id,"[审核比分-冠军],操作人:{$loginname}"));
							}else{
								$moneyLogRes=addAccountRecords(array($userid,$username,$rowMem['test_flag'],$rowMem['Money'],$cash*-1,$rowMem['Money']-$cash,5,6,$id,"[审核比分],操作人:{$loginname}"));	
							}
							if(!$moneyLogRes){
								mysqli_query($dbMasterLink,"ROLLBACK");
								$errorArr[$id]="用户{$userid}资金账变添加失败！订单号为: {$orderno}。";
								continue;
							}
						}else{
							mysqli_query($dbMasterLink,"ROLLBACK");
							$errorArr[$id]="用户{$userid}资金账户更新失败！订单号为: {$orderno}。";
							continue;
						}
					}else {
						mysqli_query($dbMasterLink,"ROLLBACK");
						$errorArr[$id]="用户{$userid}没有得到账户的资金账变SQL语句！订单号为: {$orderno}。";
						continue;
					}
				}else{
					mysqli_query($dbMasterLink,"ROLLBACK");
					$errorArr[$id]="用户{$userid}锁定失败！订单号为: {$orderno}。";
					continue;
				}
			}
			
			if($rowCheck['MB_Inball']==-52 || $rowCheck['MB_Inball_HR']==-51){
				if($rowCheck['MB_Inball_HR']==-51){//半场
					$strLineType=implode(',',$halfLineType);
					$rsql="update ".DBPREFIX."web_report_data set VGOLD='',M_Result='',A_Result='',B_Result='',C_Result='',D_Result='',T_Result='',Confirmed=0,Danger=0,Cancel=0,Checked=0,updateTime='".date('Y-m-d H:i:s',time())."' where `ID`=".$id." and LineType in(".$strLineType.")";
				}
				if($rowCheck['MB_Inball']==-52){//全场
					$strLineType=implode(',',$halfLineType);
					$rsql="update ".DBPREFIX."web_report_data set VGOLD='',M_Result='',A_Result='',B_Result='',C_Result='',D_Result='',T_Result='',Confirmed=0,Danger=0,Cancel=0,Checked=0,updateTime='".date('Y-m-d H:i:s',time())."' where `ID`=".$id." and LineType not in(".$strLineType.")";
				}
			}else{
					$rsql="update ".DBPREFIX."web_report_data set VGOLD='',M_Result='',A_Result='',B_Result='',C_Result='',D_Result='',T_Result='',Confirmed=0,Danger=0,Cancel=0,Checked=0,updateTime='".date('Y-m-d H:i:s',time())."' where `ID`=".$id;
			}
			if(mysqli_query($dbMasterLink,$rsql)){
				mysqli_query($dbMasterLink,"COMMIT");
			}else{
				mysqli_query($dbMasterLink,"ROLLBACK");
				$errorArr[$id]="订单号为: {$orderno}。更新失败！";
				continue;	
			}
		}else{
			mysqli_query($dbMasterLink,"ROLLBACK");
			$errorArr[$id]="{$id}事务开启失败！";
			continue;
		}
	}

	if($rowCheck['MB_Inball']==-52 || $rowCheck['MB_Inball_HR']==-51){
		if($rowCheck['MB_Inball_HR']==-51){//半场
			$sql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball_HR='',TG_Inball_HR='',Score=0,Cancel=0,Score_Source='' where `Type`='".$gtype."' and `MID`='".$gid."'";
			mysqli_query($dbMasterLink,$sql) or die ("操作失败11");
		}
		if($rowCheck['MB_Inball']==-52){//全场
			$sql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='',TG_Inball='',Score=0,Cancel=0,Score_Source='' where `Type`='".$gtype."' and `MID`='".$gid."'";
			mysqli_query($dbMasterLink,$sql) or die ("操作失败13");
		}
	}else{
		if($gtype=='FS'){
			$sql="update ".DBPREFIX.SPORT_FLUSH_FS_MATCH_TABLE." set win=0,Score=0,Cancel=0,Score_Source='' where `MID`='".$gid."'";
			mysqli_query($dbMasterLink,$sql) or die ("操作失败1");
		}else{
			$sql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='',TG_Inball='',TG_Inball_HR='',MB_Inball_HR='',Score=0,Cancel=0,Score_Source='' where `Type`='".$gtype."' and `MID`='".$gid."'";
			mysqli_query($dbMasterLink,$sql) or die ("操作失败1");
		}
	}

	/* 插入系统日志 */
	if($gtype=='FS'){
		$loginfo = $loginname.' 在审核比分-冠军中对赛事进行了 <font class="red">恢复注单操作</font> 操作,gid 为 <font class="red">'.$gid.'</font>,gid 为 <font class="red">'.$gid.'</font>,gtype 为 <font class="blue">'.$gtype.'</font>' ;
		innsertSystemLog($loginname,$lv,$loginfo);	
	}else{
		$loginfo = $loginname.' 在审核比分中对赛事进行了 <font class="red">恢复注单操作</font> 操作,gid 为 <font class="red">'.$gid.'</font>,gid 为 <font class="red">'.$gid.'</font>,gtype 为 <font class="blue">'.$gtype.'</font>' ;
		innsertSystemLog($loginname,$lv,$loginfo);	
	}
	
	if(count($errorArr)!=0){
		$errorMessage=implode("<br>",$errorArr);
		echo "<script>alert('".$errorMessage."');</script>";
	}	
}

//$action==3 关闭某一场赛事
if($action==3){
    if($open ==1){
        $loginfo_status = '关盘' ;
    }else{
        $loginfo_status = '开盘' ;
        if($match_pwd !=$pk_pwd){ // 验证操作密码
            echo "<script languag='JavaScript'>alert('操作密码不正确');history.go(-1);</script>";
            die;
        }
    }
    $sql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set open='$open' where `Type`='".$gtype."' and `MID`='".$gid."'";
    mysqli_query($dbMasterLink,$sql) or die ("操作失败3");
    /* 插入系统日志 */
    $loginfo = $loginname.' 审核比分中对赛事进行了 <font class="red">'.$loginfo_status.'</font> 操作, gid 为 <font class="red">'.$gid.'</font> ,gtype 为 <font class="blue">'.$gtype.'</font>' ;
    innsertSystemLog($loginname,$lv,$loginfo);
    echo "<script languag='JavaScript'>self.location='match.php?uid=$uid&langx=$langx&gtype=$gtype&date_start=$date_start&page=$page&league=$league'</script>";
}
//$action==4 关闭全部赛事 或者 全部显示
if ($action==4){
    if($open ==1){
        $loginfo_status = '打开全部盘口' ;
    }else{
        $loginfo_status = '关闭全部盘口' ;
        if($match_pwd !=$pk_pwd){ // 验证操作密码
            echo "<script languag='JavaScript'>alert('操作密码不正确');history.go(-1);</script>";
            die;
        }
    }
    $sql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set open='$open' where `Type`='".$gtype."' and `M_Date`='".$date_start."'";
    mysqli_query($dbMasterLink,$sql) or die ("操作失败4");
    /* 插入系统日志 */
    $loginfo = $loginname.' 审核比分中对赛事进行了 <font class="red">'.$loginfo_status.'</font> 操作' ;
    innsertSystemLog($loginname,$lv,$loginfo);
    echo "<script languag='JavaScript'>self.location='match.php?uid=$uid&langx=$langx&gtype=$gtype&date_start=$date_start&page=$page&league=$league'</script>";
}


// 推荐赛事提交，最多3个赛事
$redisObj = new Ciredis();
$sRecommendedMatchs = $redisObj->getSimpleOne('recommended_match');
$aRecommendedMatchs = json_decode($sRecommendedMatchs,true);
$aRecommendedMatchsMid = array_column($aRecommendedMatchs, 'MID');
if ($action==5){

    $sql = "SELECT `MID`,`Type`,`MB_Team`,`TG_Team`,`M_Date`,`M_Time`,`M_Start`,`M_League` FROM `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` WHERE MID = '$gid'";
    $result = mysqli_query($dbLink,$sql);
    $row = mysqli_fetch_assoc($result);
    foreach ($aRecommendedMatchs as $k =>$v){
        if ($v['MID'] == $gid){
            echo "<script languag='JavaScript'>alert('此赛事已推荐，请重新推荐其他赛事');</script>";
            echo "<script languag='JavaScript'>self.location='match.php?uid=$uid&langx=$langx&gtype=$gtype&date_start=$date_start&page=$page&league=$league'</script>";
        }
    }
    // 已经有3个了，把排第一个的删掉，追加最新的
    // 足球三个、篮球3个
    if(count($aRecommendedMatchs)>=4){
        $aRecommendedMatchs[]=$row;
        array_shift($aRecommendedMatchs);
    }else{ // 追加到数组尾部
        $aRecommendedMatchs[]= $row;
    }
    $redisObj->setOne('recommended_match',json_encode($aRecommendedMatchs,JSON_UNESCAPED_UNICODE));
    $sRecommendedMatchs = $redisObj->getSimpleOne('recommended_match');
    $aRecommendedMatchs = json_decode($sRecommendedMatchs,true);
    $aRecommendedMatchsMid = array_column($aRecommendedMatchs, 'MID');

}

// 统计当前赛事注单笔数，投注额，输赢 ,  $betdetail 为1会执行
function getBetTotal($dbLink , $data) {
    global $date_start;
    $midCount = $data['gid'];
    //  Checked =0 (1已经派彩完毕)   Cancel !=1 (1取消), M_result>=0  会员结果  大于0 会员赢  ， 小于0 会员输
    $mysql="select ID,MID,M_date,BetTime,BetScore,M_Result,Cancel,Checked,Confirmed from ".DBPREFIX."web_report_data where M_Date='".$date_start."' and FIND_IN_SET($midCount,MID)>0";
    //echo $mysql;echo '<br>';
    $sql_result = mysqli_query($dbLink, $mysql);
    $betnum = 0;
    $returnBetScore = [];
    while($reportRow = mysqli_fetch_assoc($sql_result)){
//            $valuearr = explode(',' , $reportRow['MID']);
//            if(count($valuearr) == 1){  //count($valuearr) ==3   不统计综合过关
        $betnum++;
        $returnBetScore['BetTotal'] += $reportRow['BetScore'];// 投注额
        //if($reportRow['Checked'] == 0) {
        $returnBetScore['M_Result'] += $reportRow['M_Result'];// 总输赢
        //}
//            }
    }

    $betResult['BetNum'] = $betnum; // 注单笔
    $betResult['BetTotal'] = !empty($returnBetScore['BetTotal']) ? number_format($returnBetScore['BetTotal']):0;
    //$betResult['M_Result'] = !empty($returnBetScore['M_Result']) ? number_format($returnBetScore['M_Result'] , 1):0;
    $betResult['M_Result'] = !empty($returnBetScore['M_Result']) ? $returnBetScore['M_Result']:0;
    return $betResult;
}

if($gtype=='FS'){
    $sql = "SELECT MID,MB_Team,M_League,M_Start,Cancel FROM `".DBPREFIX.SPORT_FLUSH_FS_MATCH_TABLE."` WHERE M_Start>'".$date_start."' ".$sleague." $seachwhere GROUP BY MID,M_Start order by M_Start,$m_league,$mb_team asc";
}else{
    $sql = "SELECT MID,M_Date,M_Time,$mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR,Cancel,Checked,Score_Source,Open from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `Type`='".$gtype."' and M_Date='".$date_start."' ".$sleague." $seachwhere order by M_Start,$m_league,$mb_team asc";
}

$result = mysqli_query($dbLink,$sql);
$count=mysqli_num_rows($result);

if( isset($betdetail) && $betdetail==1){
    $page_size=20;
}else{
    $page_size=60;
}

$page_count=ceil($count/$page_size);
$offset=$page*$page_size;
$mysql=$sql."  limit $offset,$page_size;";
/*echo "<br/>";
echo $mysql;
echo "<br/>";*/
$result = mysqli_query($dbLink,$mysql);
$aMatchs=[];
$aMids=[];
while ($row = mysqli_fetch_assoc($result)){
    $aMids[]=$row['MID'];
    $aMatchs[]=$row;
}

$sMids = implode(',',$aMids);

$mysqlMidCount="select MID,COUNT(MID) as betCount from ".DBPREFIX."web_report_data where MID in ($sMids) and Checked=0 and lineType!=8 GROUP BY MID";
/*echo "<br/>";
echo $mysqlMidCount;
echo "<br/>";*/
$resultMidCount = mysqli_query($dbLink, $mysqlMidCount);
$aMidCount=[];
while ($row = mysqli_fetch_assoc($resultMidCount)){
    $aMidCount[$row['MID']]=$row['betCount'];
}
//print_r($aMidCount);
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
    <link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <style>
        .m_tab .score_td {width:100px;}
    </style>

</head>
<body onload="onLoad()";>
<FORM NAME="myFORM" ACTION="" METHOD=POST>
    <dl class="main-nav">
        <dt>审核比分</dt>
        <dd>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr class="m_tline">
                    <td >&nbsp;
                        类别:
                        <select class="za_select_auto" onchange=document.myFORM.submit(); id="gtype" name="gtype">
                            <option value="FS">冠军</option>
                            <option value="FT">足球</option>
                            <option value="BK">篮球</option>
                            <option value="BS">棒球</option>
                            <option value="TN">网球</option>
                            <option value="VB">排球</option>
                            <option value="OP">其它</option>
                            <option value="FU">指数</option>
                        </select>
                        比赛日期:
                        <?php if($gtype=="FS"){ ?>
                            <input class="za_select_auto date_start" id="date_start" name="date_start" />
                        <?php }else{ ?>
                            <select class="za_select_auto date_start" onchange=document.myFORM.submit(); id="date_start" name="date_start">
                                <?php
                                $dd = 24*60*60;
                                $t = time()+$dd; // 日期选项最晚到明天 20180517
                                $aa=0;
                                $bb=0;
                                for($i=0;$i<=15;$i++)
                                {
                                    $today=date('Y-m-d',$t);
                                    if ($date_start==date('Y-m-d',$t)){
                                        echo "<option value='$today' selected>".date('Y-m-d',$t)."</option>";
                                    }else{
                                        echo "<option value='$today'>".date('Y-m-d',$t)."</option>";
                                    }
                                    $t -= $dd;
                                }
                                $match_date_yesterday = date('Y-m-d',time()-86400);
                                $match_date_today = date('Y-m-d');
                                $match_date_tomorrow = date('Y-m-d',time()+86400);
                                ?>
                            </select>
                        <?php } ?>
                        <input type="button" class="match_date_yesterday" value="昨日" onclick="match_date('<?php echo $match_date_yesterday;?>')" />
                        <input type="button" class="match_date_today" value="今日" onclick="match_date('<?php echo $match_date_today;?>')" />
                        <input type="button" class="match_date_tomorrow" value="明日" onclick="match_date('<?php echo $match_date_tomorrow;?>')" />

                        -- 盘口操作:
                        &nbsp;<a href="javascript:CheckCLOSE(1,'match.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&gtype=<?php echo $gtype?>&date_start=<?php echo $date_start?>&action=4&open=1')" title="点击会打开全部盘口">打开全部盘口</a>&nbsp;&nbsp;&nbsp;
                         <a href="javascript:CheckCLOSE(0,'match.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&gtype=<?php echo $gtype?>&date_start=<?php echo $date_start?>&action=4&open=0')" title="点击会关闭全部盘口">关闭全部盘口</a>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="text" id="seachtext" name="seachtext" placeholder="输入关键字查询" value="<?php echo $seachtext?>" class="select_btn">
                        <input type="submit" id="btnSearch" class="za_button" value="搜索" >
                    </td>
                </tr>
            </table>
        </dd>
    </dl>
    <div class="main-ui width_1300">
        <table id="glist_table" class="m_tab" >
            <tr class="m_title">
                <td colspan="<?php if($gtype=='FS'){ echo 2; }else{ echo 3;}?>" align="left">&nbsp;选择联盟:
                    <select class=za_select onchange=document.myFORM.submit(); id="league" name="league">
                        <option value="">全部</option>
                        <?php
                        if($gtype=='FS'){
                            $league_mysql = "SELECT distinct M_League FROM `".DBPREFIX.SPORT_FLUSH_FS_MATCH_TABLE."` WHERE `M_Start`>'".$date_start."' GROUP BY MID";
                        }else{
                            $league_mysql = "select distinct $m_league as M_League FROM `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` WHERE `Type`='".$gtype."' and `M_Date`='".$date_start."'";
                        }
                        $league_result = mysqli_query($dbLink, $league_mysql);
                        while($league_row=mysqli_fetch_array($league_result)){
                            echo "<OPTION value='$league_row[M_League]'>$league_row[M_League]</OPTION>";
                        }
                        ?>
                    </select>
                </td>
                <td colspan="<?php if($gtype=='FS'){ echo 2; }else{ echo 4;}?>" align="left">&nbsp;
                    <?php
                    for($i=0;$i<$page_count;$i++){

                        //$num=$i+1;
                        echo "<a class='a_link ".($page == $i?'a_link_active':'')."'  href='match.php?uid=$uid&langx=$langx&gtype=$gtype&date_start=$date_start&page=$i&league=$league&betdetail=$betdetail'><b>".($i+1)."页</b></a>&nbsp;&nbsp;";
                    }
                    ?>
                </td>
                <td colspan="<?php if($gtype=='FS'){ echo 4; }else{ echo 5;}?>" align="center">功能</td>
                <td colspan="<?php if($gtype=='FS'){ echo 3; }else{ echo 3;}?>">
                    <a  href="./match.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&date_start=<?php echo $date_start?>&page=<?php echo $page?>&league=<?php echo $league?>&gtype=<?php echo $gtype?>&betdetail=1"><font color=red>查看以下三项</font></a>
                </td>
                <td>推荐赛事</td>
            </tr>
            <tr class="m_title">
                <?php
                if($gtype=='FS'){
                    ?>
                    <td width="200" >联赛名称</td>
                    <td width="150">时间</td>
                    <td width="180">赛事名称</td>
                    <td width="180"><font color='red'><strong>获胜队伍</strong></font></td>
                    <td width="84">赛事取消</td>
                    <td width="60">注单 </td>
                    <td width="40">操作</td>
                    <td width="40">显示</td>
                    <!--新增三行-->
                    <td width="40">注单笔</td>
                    <td width="40">下注</td>
                    <td width="50">输赢</td>
                    <?php
                }else{
                    ?>
                    <td width="139" ><?php echo $date_start?>--赛事</td>
                    <td width="40">时间</td>
                    <td width="180">主场队伍</td>
                    <td class="score_td"  >全场比分</td>
                    <td width="180">客场队伍</td>
                    <td class="score_td" >半场比分</td>
                    <td width="84">赛事取消</td>
                    <td width="60">注单 </td>
                    <td width="40">操作</td>
                    <td width="40">操作</td>
                    <td width="40">显示</td>
                    <td width="40">状态</td>
                    <!--新增三行-->
                    <td width="40">注单笔</td>
                    <td width="40">下注</td>
                    <td width="50">输赢</td>
                    <?php
                }
                ?>
                <td width="40"></td>
            </tr>
            <?php
            if ($count<>0){
//                while ($row = mysqli_fetch_assoc($result)){
                foreach ($aMatchs as $k=> $row){
                    if($row['MID']==$gid){
                        ?>
                        <tr class="m_title">
                        <?php
                    }else{
                        ?>
                        <tr class="m_cen">
                        <?php
                    }
                    if($gtype=='FS'){//冠军列表
                        $fsSend="/";
                        $fsSendFlag=0;
                        $sqlWin = "SELECT M_Item FROM ".DBPREFIX.SPORT_FLUSH_FS_MATCH_TABLE." WHERE  MID=".$row['MID']." and win=1 and Score=1";
                        $resultWin = mysqli_query($dbLink,$sqlWin);
                        while($rowWin=mysqli_fetch_assoc($resultWin)){
                            $rowWins[]=$rowWin;
                        }
                        $count=count($rowWins);
                        if($count==1){
                            $fsSendFlag=1;
                            $fsSend=$rowWins[0]['M_Item'];
                        }elseif($count>1){
                            $fsSendFlag=1;
                            foreach($rowWins as $key=>$value){
                                $guanjunNew[]=$value['M_Item'];
                            }
                            $fsSend=implode("<br/>",$guanjunNew);
                        }

                        ?>
                        <td><?php echo $row["M_League"]; ?></td>
                        <td><?php echo $row["M_Start"]; ?></td>
                        <td><div align="right"><?php echo str_replace('[主]','',$row["MB_Team"])?></div></td>
                        <td><div align="center"><?php echo $fsSend; ?></div></td>
                        <td>
                            <?php
                            if($row['Cancel']==0){
                                ?>
                                <select onchange=javascript:CheckSTOP(this) name="select1">
                                    <option>赛事处理</option>
                                    <option value="match.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&gtype=<?php echo $gtype?>&gid=<?php echo $row['MID']?>&date_start=<?php echo $row['M_Date']?>&page=<?php echo $page?>&league=<?php echo $league?>&action=1&confirmed=-1"><?php echo $Score1?></option>
                                </select>
                                <?php
                            }elseif($row['Cancel']==1){
                                echo "<font color='red'><strong>已注销</strong></font>";
                            }
                            ?>
                        </td>
                        <td>
                            <!--括号里面显示当前注单投注总额-->
                            <a class="a_link" href="./showdata.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&gtype=<?php echo $gtype?>&gid=<?php echo $row['MID']?>&date_start=<?php echo $row['M_Date']?>">注单<?php echo $aMidCount[$row['MID']]>0?'('.$aMidCount[$row['MID']].')':'(0)';?></a>
                        </td>
                        <td>
                            <?php if($fsSendFlag==0){ ?>
                                <a class="a_link" href="set_score_fs.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&gtype=<?php echo $gtype?>&gid=<?php echo $row['MID']?>&date_start=<?php echo $row['M_Date']?>&page=<?php echo $page;?>"><font color='blue'>结算</font></a><br>
                            <?php }else{
                                echo '-';
                            }?>
                        </td>
                        <td>
                            <?php if($fsSendFlag==0 && $row['Cancel']==0){
                                echo '正常';
                            }elseif($fsSendFlag==1 || $row['Cancel']==1){ ?>
                                <a class="a_link" href="match.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&gtype=<?php echo $gtype?>&gid=<?php echo $row['MID']?>&date_start=<?php echo $row['M_Date']?>&page=<?php echo $page?>&league=<?php echo $league?>&action=2"><font color='blue'>恢复</font></a><br>
                            <?php } ?>
                        </td>
                        <?php
                        unset($fsSend);
                        unset($rowWins);
                        unset($guanjunNew);
                    }else{//非冠军列表
                        ?>
                        <td><?php echo $row["M_League"]?></td>
                        <td><?php echo $row["M_Time"]?></td>
                        <td><div align="right"><?php echo str_replace('[主]','',$row["MB_Team"])?></div></td>
                        <td class="score_td" >
                            <!-- <a class="a_link" href="./showdata.php?uid=<?php echo $uid?>&gid=<?php echo $row['MID']?>&date_start=<?php echo $row['M_Date']?>&gtype=<?php echo $gtype?>&langx=<?php echo $langx?>"> -->
                            <?php if ($row["MB_Inball"]=='-1'){
                                ?>
                                <font color="red"><b><?php echo $Score1?></b></font>
                                <?php
                            }else if($row["MB_Inball"]=='-2'){
                                ?>
                                <font color="red"><b><?php echo $Score2?></b></font>
                                <?php
                            }else if($row["MB_Inball"]=='-3'){
                                ?>
                                <font color="red"><b><?php echo $Score3?></b></font>
                                <?php
                            }else if($row["MB_Inball"]=='-4'){
                                ?>
                                <font color="red"><b><?php echo $Score4?></b></font>
                                <?php
                            }else if($row["MB_Inball"]=='-5'){
                                ?>
                                <font color="red"><b><?php echo $Score5?></b></font>
                                <?php
                            }else if($row["MB_Inball"]=='-6'){
                                ?>
                                <font color="red"><b><?php echo $Score6?></b></font>
                                <?php
                            }else if($row["MB_Inball"]=='-7'){
                                ?>
                                <font color="red"><b><?php echo $Score7?></b></font>
                                <?php
                            }else if($row["MB_Inball"]=='-8'){
                                ?>
                                <font color="red"><b><?php echo $Score8?></b></font>
                                <?php
                            }else if($row["MB_Inball"]=='-9'){
                                ?>
                                <font color="red"><b><?php echo $Score9?></b></font>
                                <?php
                            }else if($row["MB_Inball"]=='-10'){
                                ?>
                                <font color="red"><b><?php echo $Score10?></b></font>
                                <?php
                            }else if($row["MB_Inball"]=='-11'){
                                ?>
                                <font color="red"><b><?php echo $Score11?></b></font>
                                <?php
                            }else if($row["MB_Inball"]=='-12'){
                                ?>
                                <font color="red"><b><?php echo $Score12?></b></font>
                                <?php
                            }else if($row["MB_Inball"]=='-13'){
                                ?>
                                <font color="red"><b><?php echo $Score13?></b></font>
                                <?php
                            }else if($row["MB_Inball"]=='-14'){
                                ?>
                                <font color="red"><b><?php echo $Score14?></b></font>
                                <?php
                            }else if($row["MB_Inball"]=='-15'){
                                ?>
                                <font color="red"><b><?php echo $Score15?></b></font>
                                <?php
                            }else if($row["MB_Inball"]=='-16'){
                                ?>
                                <font color="red"><b><?php echo $Score16?></b></font>
                                <?php
                            }else if($row["MB_Inball"]=='-17'){
                                ?>
                                <font color="red"><b><?php echo $Score17?></b></font>
                                <?php
                            }else if($row["MB_Inball"]=='-18'){
                                ?>
                                <font color="red"><b><?php echo $Score18?></b></font>
                                <?php
                            }else if($row["MB_Inball"]=='-19'){
                                ?>
                                <font color="red"><b><?php echo $Score19?></b></font>
                                <?php
                            }else if($row["MB_Inball"]=='-42'){
                                ?>
                                <font color="red"><b><?php echo $Score42?></b></font>
                                <?php
                            }else if($row["MB_Inball"]=='-51'){
                                ?>
                                <font color="red"><b><?php echo $Score51?></b></font>
                                <?php
                            }else if($row["MB_Inball"]=='-52'){
                                ?>
                                <font color="red"><b><?php echo $Score52?></b></font>
                                <?php
                            }else if($row["MB_Inball"]=='-53'){
                                ?>
                                <font color="red"><b><?php echo $Score53?></b></font>
                                <?php
                            }elseif( strlen($row["MB_Inball"])>0 && strlen($row["TG_Inball"])>0  ){
                                ?>
                                <font color="red"><b><input name="mb_score_all_<?php echo $row['MID']?>" type="hidden" value="<?php echo $row["MB_Inball"]?>" /><?php echo $row["MB_Inball"]?></b> - <b><input name="tg_score_all_<?php echo $row['MID']?>" type="hidden" value="<?php echo $row["TG_Inball"]?>" /><?php echo $row["TG_Inball"]?></b></font>
                                <?php
                            }else{
                            ?>
                            <font color="red"><b><input size="1" name="mb_score_all_<?php echo $row['MID']?>" value="" /></b> - <b><input size="1" name="tg_score_all_<?php echo $row['MID']?>" value="" /></b></font>
                            <?php
                            }
                            ?><!-- </a> -->
                        </td>
                        <td  data-mbball="<?php echo $row['MB_Inball']?>" data-tgball="<?php echo $row['TG_Inball']?>"><div align="left" ><?php echo $row["TG_Team"]?></div></td>
                        <td class="score_td" >
                            <!-- <a class="a_link" href="./showdata.php?uid=<?php echo $uid?>&gid=<?php echo $row['MID']?>&date_start=<?php echo $row['M_Date']?>&gtype=<?php echo $gtype?>&langx=<?php echo $langx?>"> -->
                            <?php
                            if ($row["MB_Inball_HR"]=='-1'){
                                ?>
                                <font color="red"><b><?php echo $Score1?></b></font>
                                <?php
                            }else if($row["MB_Inball_HR"]=='-2'){
                                ?>
                                <font color="red"><b><?php echo $Score2?></b></font>
                                <?php
                            }else if($row["MB_Inball_HR"]=='-3'){
                                ?>
                                <font color="red"><b><?php echo $Score3?></b></font>
                                <?php
                            }else if($row["MB_Inball_HR"]=='-4'){
                                ?>
                                <font color="red"><b><?php echo $Score4?></b></font>
                                <?php
                            }else if($row["MB_Inball_HR"]=='-5'){
                                ?>
                                <font color="red"><b><?php echo $Score5?></b></font>
                                <?php
                            }else if($row["MB_Inball_HR"]=='-6'){
                                ?>
                                <font color="red"><b><?php echo $Score6?></b></font>
                                <?php
                            }else if($row["MB_Inball_HR"]=='-7'){
                                ?>
                                <font color="red"><b><?php echo $Score7?></b></font>
                                <?php
                            }else if($row["MB_Inball_HR"]=='-8'){
                                ?>
                                <font color="red"><b><?php echo $Score8?></b></font>
                                <?php
                            }else if($row["MB_Inball_HR"]=='-9'){
                                ?>
                                <font color="red"><b><?php echo $Score9?></b></font>
                                <?php
                            }else if($row["MB_Inball_HR"]=='-10'){
                                ?>
                                <font color="red"><b><?php echo $Score10?></b></font>
                                <?php
                            }else if($row["MB_Inball_HR"]=='-11'){
                                ?>
                                <font color="red"><b><?php echo $Score11?></b></font>
                                <?php
                            }else if($row["MB_Inball_HR"]=='-12'){
                                ?>
                                <font color="red"><b><?php echo $Score12?></b></font>
                                <?php
                            }else if($row["MB_Inball_HR"]=='-13'){
                                ?>
                                <font color="red"><b><?php echo $Score13?></b></font>
                                <?php
                            }else if($row["MB_Inball_HR"]=='-14'){
                                ?>
                                <font color="red"><b><?php echo $Score14?></b></font>
                                <?php
                            }else if($row["MB_Inball_HR"]=='-15'){
                                ?>
                                <font color="red"><b><?php echo $Score15?></b></font>
                                <?php
                            }else if($row["MB_Inball_HR"]=='-16'){
                                ?>
                                <font color="red"><b><?php echo $Score16?></b></font>
                                <?php
                            }else if($row["MB_Inball_HR"]=='-17'){
                                ?>
                                <font color="red"><b><?php echo $Score17?></b></font>
                                <?php
                            }else if($row["MB_Inball_HR"]=='-18'){
                                ?>
                                <font color="red"><b><?php echo $Score18?></b></font>
                                <?php
                            }else if($row["MB_Inball_HR"]=='-19'){
                                ?>
                                <font color="red"><b><?php echo $Score19?></b></font>
                                <?php
                            }else if($row["MB_Inball_HR"]=='-42'){
                                ?>
                                <font color="red"><b><?php echo $Score42?></b></font>
                                <?php
                            }else if($row["MB_Inball_HR"]=='-51'){
                                ?>
                                <font color="red"><b><?php echo $Score51?></b></font>
                                <?php
                            }else if($row["MB_Inball_HR"]=='-52'){
                                ?>
                                <font color="red"><b><?php echo $Score52?></b></font>
                                <?php
                            }else if($row["MB_Inball_HR"]=='-53'){
                                ?>
                                <font color="red"><b><?php echo $Score53?></b></font>
                                <?php
                            }elseif( strlen($row["MB_Inball_HR"])>0 && strlen($row["TG_Inball_HR"])>0 ){
                                ?>
                                <font color="red"><b><input name="mb_score_half_<?php echo $row['MID']?>" type="hidden" value="<?php echo $row["MB_Inball_HR"]?>" /><?php echo $row["MB_Inball_HR"]?></b> - <b><input name="tg_score_half_<?php echo $row["MID"];?>" type="hidden" value="<?php echo $row["TG_Inball_HR"]?>" /><?php echo $row["TG_Inball_HR"]?></b></font>
                                <?php
                            }else{
                                ?>
                                <font color="red"><b><input size="1" name="mb_score_half_<?php echo $row['MID']?>" value="" /></b> - <b><input size="1" name="tg_score_half_<?php echo $row["MID"];?>" value="" /></b></font>
                                <?php
                            }
                            ?>
                            <!-- </a> --></td>
                        <td >
                            <select onchange=javascript:CheckSTOP(this) name="select1">
                                <option>赛事处理</option>
                                <option value="match.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&action=1&gtype=<?php echo $gtype?>&gid=<?php echo $row[MID]?>&date_start=<?php echo $row['M_Date']?>&page=<?php echo $page?>&league=<?php echo $league?>&confirmed=-1&actionname=<?php echo $Score1?>"><?php echo $Score1?></option>
                                <option value="match.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&action=1&gtype=<?php echo $gtype?>&gid=<?php echo $row[MID]?>&date_start=<?php echo $row['M_Date']?>&page=<?php echo $page?>&league=<?php echo $league?>&confirmed=-2&actionname=<?php echo $Score2?>"><?php echo $Score2?></option>
                                <option value="match.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&action=1&gtype=<?php echo $gtype?>&gid=<?php echo $row[MID]?>&date_start=<?php echo $row['M_Date']?>&page=<?php echo $page?>&league=<?php echo $league?>&confirmed=-3&actionname=<?php echo $Score3?>"><?php echo $Score3?></option>
                                <option value="match.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&action=1&gtype=<?php echo $gtype?>&gid=<?php echo $row[MID]?>&date_start=<?php echo $row['M_Date']?>&page=<?php echo $page?>&league=<?php echo $league?>&confirmed=-4&actionname=<?php echo $Score4?>"><?php echo $Score4?></option>
                                <option value="match.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&action=1&gtype=<?php echo $gtype?>&gid=<?php echo $row[MID]?>&date_start=<?php echo $row['M_Date']?>&page=<?php echo $page?>&league=<?php echo $league?>&confirmed=-5&actionname=<?php echo $Score5?>"><?php echo $Score5?></option>
                                <option value="match.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&action=1&gtype=<?php echo $gtype?>&gid=<?php echo $row[MID]?>&date_start=<?php echo $row['M_Date']?>&page=<?php echo $page?>&league=<?php echo $league?>&confirmed=-6&actionname=<?php echo $Score6?>"><?php echo $Score6?></option>
                                <option value="match.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&action=1&gtype=<?php echo $gtype?>&gid=<?php echo $row[MID]?>&date_start=<?php echo $row['M_Date']?>&page=<?php echo $page?>&league=<?php echo $league?>&confirmed=-7&actionname=<?php echo $Score7?>"><?php echo $Score7?></option>
                                <option value="match.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&action=1&gtype=<?php echo $gtype?>&gid=<?php echo $row[MID]?>&date_start=<?php echo $row['M_Date']?>&page=<?php echo $page?>&league=<?php echo $league?>&confirmed=-8&actionname=<?php echo $Score8?>"><?php echo $Score8?></option>
                                <option value="match.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&action=1&gtype=<?php echo $gtype?>&gid=<?php echo $row[MID]?>&date_start=<?php echo $row['M_Date']?>&page=<?php echo $page?>&league=<?php echo $league?>&confirmed=-9&actionname=<?php echo $Score9?>"><?php echo $Score9?></option>
                                <option value="match.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&action=1&gtype=<?php echo $gtype?>&gid=<?php echo $row[MID]?>&date_start=<?php echo $row['M_Date']?>&page=<?php echo $page?>&league=<?php echo $league?>&confirmed=-10&actionname=<?php echo $Score10?>"><?php echo $Score10?></option>
                                <option value="match.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&action=1&gtype=<?php echo $gtype?>&gid=<?php echo $row[MID]?>&date_start=<?php echo $row['M_Date']?>&page=<?php echo $page?>&league=<?php echo $league?>&confirmed=-11&actionname=<?php echo $Score11?>"><?php echo $Score11?></option>
                                <option value="match.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&action=1&gtype=<?php echo $gtype?>&gid=<?php echo $row[MID]?>&date_start=<?php echo $row['M_Date']?>&page=<?php echo $page?>&league=<?php echo $league?>&confirmed=-12&actionname=<?php echo $Score12?>"><?php echo $Score12?></option>
                                <option value="match.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&action=1&gtype=<?php echo $gtype?>&gid=<?php echo $row[MID]?>&date_start=<?php echo $row['M_Date']?>&page=<?php echo $page?>&league=<?php echo $league?>&confirmed=-13&actionname=<?php echo $Score13?>"><?php echo $Score13?></option>
                                <option value="match.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&action=1&gtype=<?php echo $gtype?>&gid=<?php echo $row[MID]?>&date_start=<?php echo $row['M_Date']?>&page=<?php echo $page?>&league=<?php echo $league?>&confirmed=-42&actionname=<?php echo $Score42?>"><?php echo $Score42?></option>
                                <?php if($gtype=="FT"){?>
                                    <option value="match.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&action=1&gtype=<?php echo $gtype?>&gid=<?php echo $row[MID]?>&date_start=<?php echo $row['M_Date']?>&page=<?php echo $page?>&league=<?php echo $league?>&confirmed=-51&actionname=<?php echo $Score51?>"><?php echo $Score51?></option>
                                    <option value="match.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&action=1&gtype=<?php echo $gtype?>&gid=<?php echo $row[MID]?>&date_start=<?php echo $row['M_Date']?>&page=<?php echo $page?>&league=<?php echo $league?>&confirmed=-52&actionname=<?php echo $Score52?>"><?php echo $Score52?></option>
                                    <option value="match.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&action=1&gtype=<?php echo $gtype?>&gid=<?php echo $row[MID]?>&date_start=<?php echo $row['M_Date']?>&page=<?php echo $page?>&league=<?php echo $league?>&confirmed=-53&actionname=<?php echo $Score53?>"><?php echo $Score53?></option>
                                <?php }?>
                                <!--option value="match.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&action=1&gtype=<?php echo $gtype?>&gid=<?php echo $row[MID]?>&date_start=<?php echo $row['M_Date']?>&page=<?php echo $page?>&league=<?php echo $league?>&confirmed=-14"><?php echo $Score14?></option>
	          <option value="match.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&action=1&gtype=<?php echo $gtype?>&gid=<?php echo $row[MID]?>&date_start=<?php echo $row['M_Date']?>&page=<?php echo $page?>&league=<?php echo $league?>&confirmed=-15"><?php echo $Score15?></option>
	          <option value="match.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&action=1&gtype=<?php echo $gtype?>&gid=<?php echo $row[MID]?>&date_start=<?php echo $row['M_Date']?>&page=<?php echo $page?>&league=<?php echo $league?>&confirmed=-16"><?php echo $Score16?></option>
	          <option value="match.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&action=1&gtype=<?php echo $gtype?>&gid=<?php echo $row[MID]?>&date_start=<?php echo $row['M_Date']?>&page=<?php echo $page?>&league=<?php echo $league?>&confirmed=-17"><?php echo $Score17?></option>
	          <option value="match.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&action=1&gtype=<?php echo $gtype?>&gid=<?php echo $row[MID]?>&date_start=<?php echo $row['M_Date']?>&page=<?php echo $page?>&league=<?php echo $league?>&confirmed=-18"><?php echo $Score18?></option>
	          <option value="match.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&action=1&gtype=<?php echo $gtype?>&gid=<?php echo $row[MID]?>&date_start=<?php echo $row['M_Date']?>&page=<?php echo $page?>&league=<?php echo $league?>&confirmed=-19"><?php echo $Score19?></option-->
                            </select>
                        </td>
                        <td >
                            <!--括号里面显示当前注单投注总额-->
                            <a class="a_link" href="./showdata.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&gtype=<?php echo $gtype?>&gid=<?php echo $row['MID']?>&date_start=<?php echo $row['M_Date']?>">注单<?php echo $aMidCount[$row['MID']]['betCount']>0?'('.$aMidCount[$row['MID']]['betCount'].')':'(0)';?></a>
                        </td>
                        <td >
                            <input class="za_button" type="button" data-mbinball="<?php echo $row['MB_Inball']?>" data-tginball="<?php echo $row['TG_Inball']?>" data-hmbinball="<?php echo $row['MB_Inball_HR']?>" data-htginball="<?php echo $row['TG_Inball_HR']?>" value="结算" onclick="speendCount(<?php echo $row["MID"] ?>,'<?php echo $gtype ?>',this)" />
                            <!-- <a class="a_link" href="./showdata.php?uid=<?php /*echo $uid*/?>&gid=<?php /*echo $row['MID']*/?>&date_start=<?php /*echo $row['M_Date']*/?>&gtype=<?php /*echo $gtype*/?>&langx=<?php /*echo $langx*/?>">
	         <input class="za_button" type="button" value="详细" />
	         </a>-->
                        </td>
                        <td >
                            <a class="a_link" href="set_score.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&gtype=<?php echo $gtype?>&gid=<?php echo $row['MID']?>&date_start=<?php echo $row['M_Date']?>&page=<?php echo $page;?>">结算</a><br>
                            <?php
                            if ($row['Checked']==1){
                                if($row['Score_Source']==2)   echo "<font color=red>二次<br>比分</font>";
                                if($row['Score_Source']==3)   echo "<font color=red>管理员<br>比分</font>";
                            }
                            ?>
                        </td>
                        <td >
                            <?php
                            if ($row['MB_Inball']!='' || $row['MB_Inball_HR']!=''){
                                ?>
                                <a class="a_link" href="match.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&gtype=<?php echo $gtype?>&gid=<?php echo $row['MID']?>&date_start=<?php echo $row['M_Date']?>&page=<?php echo $page?>&league=<?php echo $league?>&action=2"><font color=blue>恢复</font></a>
                                <?php
                            }else{
                                ?>
                                正常
                                <?php
                            }
                            ?></td>
                        <td >
                            <?php
                            if ($row["Open"]==1){ // 盘口开启状态，操作关盘
                                ?>
                                <a class="a_link" href=javascript:CheckCLOSE(0,"match.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&gtype=<?php echo $gtype?>&gid=<?php echo $row['MID']?>&date_start=<?php echo $row['M_Date']?>&page=<?php echo $page?>&league=<?php echo $league?>&action=3&open=0")>开盘</a>
                                <?php
                            }else{
                                ?>
                                <a class="a_link" href=javascript:CheckCLOSE(1,"match.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&gtype=<?php echo $gtype?>&gid=<?php echo $row['MID']?>&date_start=<?php echo $row['M_Date']?>&page=<?php echo $page?>&league=<?php echo $league?>&action=3&open=1")><font color=red>关盘</font></a>
                                <?php
                            }
                            ?>
                        </td>
                        <?php
                    }
                    // 如果查看选中
                    if ($betdetail == '1') {
                        $data = array('gtype' => $gtype, 'gid' => $row['MID']);
                        $betResult = getBetTotal($dbLink, $data);  //array(3) { "BetNum"=> 0, "BetTotal"=> 0, "M_Result"=>0)
                    }
                    ?>
                    <td><a class="a_link"><?php if($betdetail == '1'){ echo "<font color=red>".$betResult['BetNum']."</font>"; } ?></a></td><!--注单笔-->
                    <td><a class="a_link"><?php if($betdetail == '1'){ echo $betResult['BetTotal']; } ?></a></td><!--下注-->
                    <!--赛事输赢    统计会员结果  大于0 会员赢 页面显示红色 红色显示负数， 小于0 会员输 页面显示黑色 黑色不显示负数  -->
                    <td><a>
                            <?php if($betdetail == '1'){
                                if($betResult['M_Result']<=0){ //小于0 会员输 页面显示黑色 黑色不显示负数
                                    echo abs(sprintf("%01.1f", $betResult['M_Result']));
                                } elseif($betResult['M_Result']>0) { //大于0 会员赢 页面显示红色 红色显示负数
                                    echo "<font color=red>".-sprintf("%01.1f", $betResult['M_Result'])."</font>";
                                }
                                //echo "<font color=red>".$betResult['M_Result']."</font>";
                            } ?>
                        </a>
                    </td>
                    <td><input type="button" onclick="recommend_match(<?php echo $row['MID'];?>)"
                               value="推荐"  <?php if (in_array($row['MID'],$aRecommendedMatchsMid)){echo 'style="background-color: red;"';}  ?>/></td>
                    </tr>
                    <?php
                }
            }
            ?>
        </table>
    </div>
</form>
<script type="text/javascript" src="../../../js/agents/jquery.js" ></script>
<script type="text/javascript" src="../../../js/agents/layer/layer.js"></script>
<script type="text/javascript" src="../../../js/agents/common.js?v=<?php echo AUTOVER; ?>" ></script>

<script language="javascript">
    var userlv = '<?php echo $lv?>' ;
    var uid = '<?php echo $uid;?>';
    var langx = "<?php echo $langx;?>";
    var url = 'match.php';

    // 昨日、今日、明日，选择时同步提交表单中的内容，并显示页面数据
    function match_date( str ) {
        var gtype = $("#gtype option:selected").val();
        var date_start = str;
        var seachtext = myFORM.seachtext.value;
        var league = myFORM.league.value;

        var form = $('<form></form>');
        form.attr('action',url);
        form.attr('method', 'post');
        form.attr('target', '_self');

        form.append("<input type='hidden' name='uid' value='"+uid+"'>");
        form.append("<input type='hidden' name='gtype' value='"+gtype+"'>");
        form.append("<input type='hidden' name='date_start' value='"+date_start+"'>");
        form.append("<input type='hidden' name='seachtext' value='"+seachtext+"'>");
        form.append("<input type='hidden' name='league' value='"+league+"'>");
        form.append("<input type='hidden' name='lv' value='"+userlv+"'>");
        $(document.body).append(form);
        form.submit();

    }

    function recommend_match(gid) {
        var form = $('<form></form>');
        form.attr('action',url);
        form.attr('method', 'post');
        form.attr('target', '_self');
        form.append("<input type='hidden' name='uid' value='"+uid+"'>");
        form.append("<input type='hidden' name='action' value='5'>");
        form.append("<input type='hidden' name='gid' value='"+gid+"'>");
        $(document.body).append(form);
        form.submit();
    }

    function onLoad(){
        var gtype = document.getElementById('gtype');
        gtype.value = '<?php echo $gtype?>';
        var league = document.getElementById('league');
        league.value = '<?php echo $league?>';
    }
    // str 跳转链接，txt 提示文本
    function CheckSTOP(obj){
        var str =obj.options[obj.selectedIndex].value ; // this.options[this.selectedIndex].value
        var txt =obj.options[obj.selectedIndex].text ; // this.options[this.selectedIndex].text
        if(confirm('确认对注单进行'+txt+'吗？')){
            document.location=str;
        }

    }
    function CheckCLOSE(type,str){ // type: 0 关闭，1 开启
        var con = '<div class="match_input"><input type="password" class="close_match_pwd" maxlength="20" placeholder="请输入操作密码" /></div>';
        var tit = type?'确认开启盘口吗？':con;

        layer.confirm(tit, {
            title:'提示',
            icon:6,
            btn: ['确定','取消'], //按钮
            yes: function(index, layero){
                var pwd = $('.close_match_pwd').val()? $('.close_match_pwd').val():'';
                str +='&match_pwd='+pwd;
                //parent.main.location = url;
                document.location=str;
                layer.close(index);
                //按钮【按钮一】的回调
            },
            cancel: function(){
                //右上角关闭回调
            },
        });
    }
    function speendCount(mid,gtype,obj){
        var $mbhalf = $("input[name='mb_score_half_"+mid+"']") ;
        var $tghalf = $("input[name='tg_score_half_"+mid+"']") ;
        var $mball = $("input[name='mb_score_all_"+mid+"']") ;
        var $tgall = $("input[name='tg_score_all_"+mid+"']") ;
        var hasmbball = $(obj).data('mbinball') ; // 已经有比分的全场主队比分
        var hastgball = $(obj).data('tginball') ; // 已经有比分的全场客队比分
        var hasmb_h_ball = $(obj).data('hmbinball') ; // 已经有比分的半主队比分
        var hastg_h_ball = $(obj).data('htginball') ; // 已经有比分的半场客队比分
        var mb_score_all,tg_score_all,mb_score_half,tg_score_half ;


        if(hasmbball >=0 && hasmbball!=''){
            mb_score_all = hasmbball;
        }else{
            mb_score_all = $mball.val();
        }

        if(hastgball >=0 && hastgball!=''){
            tg_score_all = hastgball;
        }else{
            tg_score_all = $tgall.val();
        }

        if(mb_score_half >=0 && mb_score_half!=''){
            mb_score_half = hasmb_h_ball;
        }else{
            mb_score_half = $mbhalf.val();
        }

        if(hastg_h_ball >=0 && hastg_h_ball!=''){
            tg_score_half = hastg_h_ball;
        }else{
            tg_score_half = $tghalf.val();
        }

        // console.log($mbhalf);
        // console.log($tghalf);
        // console.log($mball);
        // console.log($tgall);

        if(($mbhalf.val()=='' || $tghalf.val()=='') && ($mball.val()=='' || $tgall.val()=='')){
            alert("请输入上半场或者全场的进球数!");
            return false;
        }

        if($mbhalf.length==0 || $tghalf.length==0 || $mball.length==0 || $tgall.length==0){
            if(($mbhalf.val()=='' || $tghalf.val()=='') || ($mball.val()=='' || $tgall.val()=='')){
                alert("请输入上半场或者全场的进球数!!");
                return false;
            }
        }

        if( $mbhalf.val() || $tghalf.val() ){ // 如果半场有值
            if($mbhalf.val()<0){
                $mbhalf.focus();
                alert("请输入主队上半场进球数!!");
                return false;
            }
            if($tghalf.val()<0){
                $tghalf.focus();
                alert("请输入客队上半场进球数!!");
                return false;
            }
        }

        if( $mball.val() || $tgall.val() ){ // 如果全场有值
            if($mball.val()<0){
                $mball.focus();
                alert("请输入主队全场进球数!!");
                return false;
            }
            if($mball.val()<0){
                $tgall.focus();
                alert("请输入客队全场进球数!!");
                return false;
            }
        }

        // console.log(mb_score_all);
        // console.log(tg_score_all);
        // console.log(mb_score_half);
        // console.log(tg_score_half);

        if(confirm("主队半场进球数："+mb_score_half+"  主队全场进球数："+mb_score_all+"\n\n客队半场进球数："+tg_score_half+"  客队全场进球数："+tg_score_all+"\n\n请确定输入是否正确?")){
            var page = "<?php echo $page;?>";
            var str="uid="+uid+"&gid="+mid+"&mb_inball="+mb_score_all+"&tg_inball="+tg_score_all+"&mb_inball_v="+mb_score_half+"&tg_inball_v="+tg_score_half+"&langx="+langx+"&lv="+userlv+"&gtype="+gtype+"&page="+page ;
            location.href='../clearing/clearing'+gtype.toUpperCase()+'.php?'+str;
        }
    }
</script>

</body>
</html>
