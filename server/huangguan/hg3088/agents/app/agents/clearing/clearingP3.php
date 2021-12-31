<?php
session_start();
include ("../include/address.mem.php");
require ("../include/config.inc.php");
require ("../include/define_function.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$uid=$_REQUEST["uid"];
$langx=$_REQUEST["langx"];
require ("../include/traditional.zh-cn.inc.php");
$loginname=$_SESSION['UserName'];
$date=date('Y-m-d');
$gtype=$_REQUEST['gtype'];
$gid=$_REQUEST['gid'];
$id=$_REQUEST['id'];

$backUrl="http://{$_SERVER["SERVER_NAME"]}/app/agents/admin/query.php?uid=".$uid."&langx=".$langx."&lv=M";

$mysql="select ID,MID,Gtype,userid,Gwin,M_Name,BetTime,orderNo,OpenType,LineType,BetType,Middle,BetScore,OddsType,MID,Mtype,M_Rate,M_Place,ShowType,M_Result,Checked,Confirmed from ".DBPREFIX."web_report_data where ID=$id and LineType=8 and Checked=0 and cancel=0";
$result = mysqli_query($dbLink, $mysql);
$row = mysqli_fetch_assoc($result);

if(count($row)==0){
	echo '<center><b><font color=red>已结算,没有需要结算的注单！</font></b></center><br>';
	exit;
}

?>
<HTML>
<HEAD>
<TITLE></TITLE>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<link rel="stylesheet" href="/style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
<META content="Microsoft FrontPage 4.0" name=GENERATOR>
</HEAD>
<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0">
<table width="975" border="0" cellspacing="1" cellpadding="0" class="m_tab">
        <tr class="m_title"> 
          <td width="90" align="center">投注时间</td>
          <td width="80" align="center">用户名称</td>
          <td width="100" align="center">球赛种类</td>
          <td width="355" align="center">內容</td>
          <td width="70" align="center">投注金额</td>
          <td width="100" align="center">有效投注</td>
          <td width="40" align="center">可赢金额</td>
          <td width="100" align="center">会员结果</td>
        </tr>
<?php
$confirmArr=array();
$sendAwardTime='';
$mid=explode(',',$row['MID']);
$mtype=explode(',',$row['Mtype']);
$rate=explode(',',$row['M_Rate']);
$letb=explode(',',$row['M_Place']);
$show=explode(',',$row['ShowType']);
$confirmArr=explode(',',$row['Confirmed']);
$cou=sizeof($mid);
$count=0;
$sendAwardTime='';	
$notgraded=0;
$id=$row['ID'];
$userid=$row['userid'];
$user=$row['M_Name'];
$winrate=1;
for($i=0;$i<$cou;$i++){
		if(in_array($mid[$i],$confirmArr)){
				$graded=88;
		}
		elseif($row['Gtype']=='FT'){
				$sql="select MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='FT' and MID=".$mid[$i];
				$result1 = mysqli_query($dbLink, $sql);
				$rowr = mysqli_fetch_assoc($result1);
				$mb_in=$rowr['MB_Inball'];
				$tg_in=$rowr['TG_Inball'];
				$mb_in_v=$rowr['MB_Inball_HR'];
				$tg_in_v=$rowr['TG_Inball_HR'];

				if( (in_array($mtype[$i],array('MH','MC','MN','OUH','OUC','RH','RC','ODD','EVEN')) && ($mb_in==''|| $tg_in=='')) ||
                    (in_array($mtype[$i],array('VMH','VMC','VMN','VOUH','VOUC','VRH','VRC')) && ($mb_in_v=='' || $tg_in_v=='')) ){
					$graded="99";
					$notgraded=1;
					echo '<center><b><font color=red>赛事未完成！</font></b></center><br>';
					echo '<center><b><font color=red>'.$row['BetTime'].'-'.$row['M_Name'].'</font></b><center><br><br>';
					exit();
				}else if ($mb_in<0){
					$graded=88;
				}else{
					if ($mtype[$i]=='MH' or $mtype[$i]=='MC' or $mtype[$i]=='MN'){
						$graded=win_chk($mb_in,$tg_in,$mtype[$i]);
					}else if ($mtype[$i]=='VMH' or $mtype[$i]=='VMC' or $mtype[$i]=='VMN'){
						$graded=win_chk_v($mb_in_v,$tg_in_v,$mtype[$i]);
						// 取消的赛事跳过，跳出循环到下一个赛事
						if ($mb_in_v<0){
						    continue;
                        }
					}else if($mtype[$i]=='OUH' or $mtype[$i]=='OUC'){
					    $graded=odds_dime($mb_in,$tg_in,$letb[$i],$mtype[$i]);
					}else if($mtype[$i]=='VOUH' or $mtype[$i]=='VOUC'){
					    $graded=odds_dime_v($mb_in_v,$tg_in_v,$letb[$i],$mtype[$i]);
                        // 取消的赛事跳过，跳出循环到下一个赛事
                        if ($mb_in_v<0){
                            continue;
                        }
					}else if($mtype[$i]=='RH' or $mtype[$i]=='RC'){
						$graded=odds_letb($mb_in,$tg_in,$show[$i],$letb[$i],$mtype[$i]);
					}else if($mtype[$i]=='VRH' or $mtype[$i]=='VRC'){
						$graded=odds_letb_v($mb_in_v,$tg_in_v,$show[$i],$letb[$i],$mtype[$i]);
                        // 取消的赛事跳过，跳出循环到下一个赛事
                        if ($mb_in_v<0){
                            continue;
                        }
					}else if($mtype[$i]=='ODD' or $mtype[$i]=='EVEN'){
						$graded=odds_eo($mb_in,$tg_in,$mtype[$i]);
					}
				}
		}
		elseif($row['Gtype']=='BK'){
			$mtypeFirst='';
			$sql="select MB_Inball,TG_Inball from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and MID=".$mid[$i];
			$result1 = mysqli_query($dbLink, $sql);
			$rowr = mysqli_fetch_assoc($result1);
			$mb_in=$rowr['MB_Inball'];
			$tg_in=$rowr['TG_Inball'];

			if ($mb_in=='' or $tg_in==''){
				$graded="99";
				$notgraded=1;
				echo '<center><b><font color=red>赛事未完成！</font></b></center><br>';
				echo '<center><b><font color=red>'.$row['BetTime'].'-'.$row['M_Name'].'</font></b><center><br><br>';
				exit();
			}else if ($mb_in<0){
				$graded=88;
			}else{

                if ($mtype[$i]=='MH' or $mtype[$i]=='MC'){ // 篮球综合过关只有主队独赢与客队独赢
                    $graded=win_chk($mb_in,$tg_in,$mtype[$i]);
                }
                elseif ($mtype[$i]=='RH' or $mtype[$i]=='RC'){ // 让球
                    $graded=odds_letb($mb_in,$tg_in,$show[$i],$letb[$i],$mtype[$i]);
                }
                else if($mtype[$i]=='ODD' or $mtype[$i]=='EVEN'){
                    $graded=odds_eo($mb_in,$tg_in,$mtype[$i]);
                }
                else{ // 大小
                    $mtypeFirst=strtolower(substr($mtype[$i],0,1));
                    if($mtypeFirst=='t'){
                        $ouStr='';
                        $ouStr = substr($letb[$i],0,1);
                        if($ouStr=='U' || $ouStr=='O'){
                            $graded=team_score_ou($mb_in,$tg_in,$letb[$i],str_replace('T','',$mtype[$i]).$ouStr);
                        }else{
                            $graded=team_score_ou($mb_in,$tg_in,$letb[$i],str_replace('T','',$mtype[$i]));
                        }
                    }else{
                        $graded=odds_dime($mb_in,$tg_in,$letb[$i],$mtype[$i]);
                    }
                }

			}
//            echo $graded.'-';
		}

        switch ($graded){
            case "1":
                $winrate=$winrate*($rate[$i]);
                break;
            case "-1":
                $winrate=0;
                break;
            case "0":
                $winrate=$winrate;
                break;
            case "0.5":
                $winrate=$winrate*(($rate[$i]-1)*0.5+1);
                break;
            case "-0.5":
                $winrate=$winrate*0.5;
                break;
            case "99":
                $winrate=$winrate;
                break;
            case "88":
                $winrate=$winrate;
                break;
        }
        if ($graded==-1){
            $winrate=0;
            $notgraded=0;
            break;
        }
}

		if($notgraded==0){	
		    $g_res=$row['BetScore']*(abs($winrate)-1);	
			$vgold=$row['BetScore'];
			$d_point=$row['D_Point']/100;
			$c_point=$row['C_Point']/100;
			$b_point=$row['B_Point']/100;
			$a_point=$row['A_Point']/100;
			$members=$g_res;//和会员结帐的金额
			
			$agents=$g_res*(1-$d_point);//上缴总代理结帐的金额
			$world=$g_res*(1-$c_point-$d_point);//上缴股东结帐
			if (1-$b_point-$c_point-$d_point!=0){
				$corprator=$g_res*(1-$b_point-$c_point-$d_point);//上缴公司结帐
			}else{
				$corprator=$g_res*($b_point+$a_point);//和公司结帐
			}
			$super=$g_res*$a_point;//和公司结帐
			$agent=$g_res;//代理商退水总帐目
			
			$sendAwardTime=date('Y-m-d H:i:s',time());
			if( !mysqli_query($dbMasterLink, "START TRANSACTION")) {
				echo "<center><b><font color=red>足球手动派奖事务开启失败！</font></b></center><br>" ;
				exit();
			}
			$sql_for_update = "select checked from ".DBPREFIX."web_report_data where ID=$id for update ";	
			$query=mysqli_query($dbMasterLink,$sql_for_update);
		    $bill_count_flag=mysqli_fetch_array($query);
			//订单已结算
			if( $bill_count_flag['checked'] == 1 ) {
				echo "<center><b><font color=red>订单已结算，事务回滚！</font></b></center><br>";
				mysqli_query($dbMasterLink, "ROLLBACK");
				exit();
			}
			
			$userMoneyLock = mysqli_query($dbMasterLink,"select Money,test_flag from ".DBPREFIX.MEMBERTABLE." where ID=$userid for update");
			if(!$userMoneyLock){
				echo "<center><b><font color=red>用户资金锁添加失败！</font></b></center><br>";
				mysqli_query($dbMasterLink, "ROLLBACK");
				exit();
			}
		    
			$cash=$row['BetScore']+$members;
			$mysql="update ".DBPREFIX.MEMBERTABLE." set Money=Money+$cash where ID=$userid";
			if(!mysqli_query($dbMasterLink,$mysql)){
		   		echo "<center><b><font color=red>派奖更新用户金额失败！</font></b></center><br>";
				mysqli_query($dbMasterLink, "ROLLBACK");
				exit();
		    }
		    
		  	//生成资金账变记录
			if($mb_in<0){
				$moneyLogDesc="取消注单,退还本金{$row['BetScore']}";
			}else{
				if($members>0){
					$moneyLogDesc="赢:退还本金{$row['BetScore']},派奖$members";;
				}elseif($members<0){
					$moneyLogDesc="输";
				}elseif($members==0){
					$moneyLogDesc="和局:退还本金$cash";
				}else{
					$moneyLogDesc="";
				}
			}
		    $moneyLogDesc.=",{$row['Gtype']}综合过关手动结算";
			//添加用户资金账变记录
			$userMoneyRow=mysqli_fetch_array($userMoneyLock);
			$moneyLogRes=addAccountRecords(array($userid,$user,$userMoneyRow['test_flag'],$userMoneyRow['Money'],$cash,$userMoneyRow['Money']+$cash,3,9,$id,$moneyLogDesc));
			if(!$moneyLogRes){
		    	echo "<center><b><font color=red>用户自己账变日志写入失败！</font></b></center><br>";
				mysqli_query($dbMasterLink, "ROLLBACK");
				exit();
		    }
		  	$sql="update ".DBPREFIX."web_report_data set VGOLD='$vgold',M_Result='$members',D_Result='$agents',C_Result='$world',B_Result='$corprator',A_Result='$super',T_Result='$agent',sendAwardTime='$sendAwardTime',sendAwardIsAuto=1,Checked=1,updateTime='".date('Y-m-d H:i:s',time())."' where ID='$id'";
			if(mysqli_query($dbMasterLink,$sql)){
				mysqli_query($dbMasterLink, "COMMIT");
			}else{
				echo "<center><b><font color=red>派奖更新用户注单表失败！</font></b></center><br>";
				mysqli_query($dbMasterLink, "ROLLBACK");
				exit();	
			}
		}else{
			$sql="update ".DBPREFIX."web_report_data set VGOLD='',M_Result='',D_Result='',C_Result='',B_Result='',A_Result='',T_Result='',updateTime='".date('Y-m-d H:i:s',time())."' where ID='$id'";
			mysqli_query($dbMasterLink,$sql) or die ("error!!");
		}
	
		switch ($row['OddsType']){
			case 'H':
			    $Odds='<BR><font color =green>'.$Rep_HK.'</font>';
				break;
			case 'M':
			    $Odds='<BR><font color =green>'.$Rep_Malay.'</font>';
				break;
			case 'I':
			    $Odds='<BR><font color =green>'.$Rep_Indo.'</font>';
				break;
			case 'E':
			    $Odds='<BR><font color =green>'.$Rep_Euro.'</font>';
				break;
			case '':
			    $Odds='';
				break;
		}
		$time=strtotime($row['BetTime']);
		$times=date("Y-m-d",$time).'<br>'.date("H:i:s",$time);
?> 
        <tr class="m_cen"> 
          <td><font color="#cc0000"><?php echo $row['BetTime']?></font></td>
          <td><?php echo $row['M_Name']?></td>
          <td>
          	<?php echo $row['Gtype']?>
          	<br><font color="#cc0000"><?php echo $row['OpenType']?></font>&nbsp;&nbsp;
          	<br><?php echo $row['Gtype']=='FT'?$Mnu_Soccer:$Mnu_Bask;?><?php echo $row['BetType']?><?php echo $Odds?><br><font color="#0000CC"><?php echo $row['orderNo']?></font>
          </td>
          <td align="right"><?php echo $row['Middle']?></td>
          <td align="right"><?php echo $row['BetScore'],2?></td>
          <td><?php echo $vgold ?></td>
          <td><?php echo $row['Gwin']?></td>
          <td><?php echo $members?></td>
        </tr>
  </table>
<p align="center" style="margin-top:100px;"><b><font color=red><a class="za_button" href="<?php echo $backUrl;?>">返 回</a></font></b></p>
</BODY>
</html>
