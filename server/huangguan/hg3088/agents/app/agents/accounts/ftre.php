<?php

if(php_sapi_name() == "cli") {
    define("CONFIG_DIR", dirname(dirname(__FILE__)));
    define("COMMON_DIR", dirname(dirname(dirname(dirname(dirname(__FILE__))))));
    require(CONFIG_DIR."/include/config.inc.php");
    require_once(COMMON_DIR."/common/sportCenterData.php");
    require_once(CONFIG_DIR."/include/address.mem.php");
}else{
    require ("../include/config.inc.php");
    require_once("../../../../common/sportCenterData.php");
    require_once("../include/address.mem.php");
    /*判断IP是否在白名单*/
    if(CHECK_IP_SWITCH) {
        if(!checkip()) {
            exit('登录失败!!\\n未被授权访问的IP!!');
        }
    }
}


$mysql = "select udp_ft_time,udp_time_ft from ".DBPREFIX."web_system_data";
$result = mysqli_query($dbLink,$mysql);
$row = mysqli_fetch_assoc($result);
$fttiem=$row['udp_ft_time'];
$tiemft=$row['udp_time_ft'];
$time=date('Y-m-d H:i:s',time()-1*$fttiem);
$times=date('Y-m-d H:i:s',time()-1*$tiemft);
//$timeMin=date('Y-m-d H:i:s',time()-1*$fttiem-150);
//$timesMin=date('Y-m-d H:i:s',time()-1*$tiemft-150);
$datetime=date('Y-m-d H:i:s');
$date=date('Y-m-d');
//神游~ 如果(今日非早盘)滚球危险球投注时间    < $times 则直接更新Danger=0 9:滚球让球|19:半场滚球让球|10:滚球大小|20:半场滚球大小|21:滚球独赢|31:半场滚球独赢
$methodStr="LineType=9 or LineType=19 or LineType=10 or LineType=20 or LineType=21 or LineType=31 or ";
$methodStr .="LineType=104 or LineType=105 or LineType=106 or LineType=107 or LineType=115 or LineType=118 or ";//以下为新增玩法，请参见FT_order_hre/re_finsih.php
$methodStr .="LineType=119 or LineType=120 or LineType=161 or LineType=122 or LineType=123 or LineType=124 or LineType=128 or "; 
$methodStr .="LineType=129 or LineType=130 or LineType=134 or LineType=135 or LineType=137 or LineType=141 or ";
$methodStr .="LineType=144 or LineType=142 or LineType=204 or LineType=206 or LineType=154 or LineType=244 or ";
$methodStr .="LineType=205";
$sql = "update ".DBPREFIX."web_report_data set Danger=0,updateTime='".date('Y-m-d H:i:s',time())."' where Danger=1 and BetTime<'$times' and Cancel=0 and Active=1 and (".$methodStr.") and M_Result=''";
//mysqli_query($dbMasterLink,$sql);
//获取(今日非早盘)滚球危险球投注时间 	< $time (系统配置秒数30-60s)的投注时间 
$sqls = "select ID,Userid,MID,BetTime,Middle,BetScore,M_Name,M_Result,MB_Ball,TG_Ball from ".DBPREFIX."web_report_data where Danger=1 and BetTime<'$time' and Cancel=0 and Active=1 and (".$methodStr.") and M_Result=''";
$results = mysqli_query($dbLink, $sqls);
while ($rows = mysqli_fetch_array ($results)){
    if (strpos($rows['Middle'],'电竞足球')!==false) continue;

	$id=$rows['ID'];
	$mid=$rows['MID'];
	$userid=$rows['Userid'];
	$username=$rows['M_Name'];
	$betscore=$rows['BetScore'];
	$m_result=$rows['M_Result'];
	$mb_ball=$rows['MB_Ball'];
	$tg_ball=$rows['TG_Ball'];
	$sqlf = "select MB_Ball,TG_Ball from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='FT' and MID='$mid'";
	$resultf = mysqli_query($dbCenterSlaveDbLink, $sqlf);
	$rowf = mysqli_fetch_assoc ($resultf);
	$ft_mb_ball=$rowf['MB_Ball'];
	$ft_tg_ball=$rowf['TG_Ball'];

	if($mb_ball==$ft_mb_ball and  $tg_ball==$ft_tg_ball){//$time(系统设置时间30s-60s)内没有进球变化
	    $ft_sql = "update ".DBPREFIX."web_report_data set Danger=0,updateTime='".date('Y-m-d H:i:s',time())."' where ID=$id";
	    mysqli_query($dbMasterLink, $ft_sql) or die ("通过操作失败!"); 
	}else{
        $beginFrom = mysqli_query($dbMasterLink,"start transaction");	//开启事务$from
		if($beginFrom){
            $reSportBet = mysqli_query($dbMasterLink,"select Cancel from  ".DBPREFIX."web_report_data where ID=$id for update");
		    if($reSportBet){
                $rowSportBet = mysqli_fetch_assoc($reSportBet);
                if($rowSportBet['Cancel']==0){
                    $ft_sql = "update ".DBPREFIX."web_report_data set VGOLD=0,M_Result=0,A_Result=0,B_Result=0,C_Result=0,D_Result=0,T_Result=0,Cancel=1,Checked=1,MB_Ball='$ft_mb_ball',TG_Ball='$ft_tg_ball',Confirmed=-18,Danger=0,updateTime='".date('Y-m-d H:i:s',time())."' where ID=$id";
                    if(mysqli_query($dbMasterLink, $ft_sql)){
                        $resultMem = mysqli_query($dbMasterLink,"select ID,UserName,test_flag,Money from  ".DBPREFIX.MEMBERTABLE." where ID=$userid for update");
                        if($resultMem){
                            $rowMem = mysqli_fetch_assoc($resultMem);
                            $u_sql = "update ".DBPREFIX.MEMBERTABLE." SET Money=Money+$betscore where ID=$userid and Pay_Type=1";
                            if(mysqli_query($dbMasterLink,$u_sql)){
                                $moneyLogRes=addAccountRecords(array($userid,$username,$rowMem['test_flag'],$rowMem['Money'],$betscore,$rowMem['Money']+$betscore,2,9,$id,"[ftre]FT危险球,取消订单,操作ID:{$id}"));
                                if($moneyLogRes){
                                    mysqli_query($dbMasterLink,"COMMIT");
                                }else{
                                    mysqli_query($dbMasterLink,"ROLLBACK");
                                    echo "用户资金日志写入失败！<br/>";
                                }
                            }else{
                                mysqli_query($dbMasterLink,"ROLLBACK");
                                echo "现金恢复操作失败！";
                            }
                        }else{
                            mysqli_query($dbMasterLink,"ROLLBACK");
                            echo "用户资金锁定失败！";
                        }
                    }else{
                        mysqli_query($dbMasterLink,"ROLLBACK");
                        echo "进球注销操作失败！";
                    }
                }else{
                    mysqli_query($dbMasterLink,"ROLLBACK");
                    echo "已处理！";
                }
            }else{
                mysqli_query($dbMasterLink,"ROLLBACK");
                echo "危险球注单锁定失败！";
            }
		}else{
	    	mysqli_query($dbMasterLink,"ROLLBACK");	
			echo "事务开启失败！";
		}
	}
}
?>
<html>
<head>
<title>足球滚球注单审核</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="/style/agents/control_down.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css">
</head>
<script> 

var limit="10" 
if (document.images){ 
	var parselimit=limit
} 
function beginrefresh(){ 
if (!document.images) 
	return 
if (parselimit==1) 
	window.location.reload() 
else{ 
	parselimit-=1 
	curmin=Math.floor(parselimit) 
	if (curmin!=0) 
		curtime=curmin+" 秒后自动更新本页！" 
	else 
		curtime=cursec+" 秒后自动更新本页！" 
		timeinfo.innerText=curtime 
		setTimeout("beginrefresh()",1000) 
	} 
} 
window.onload=beginrefresh 

</script>
<body>
<table width="300" height="300" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="300" height="300" align="center"><?php echo $datetime?><br><br><font color="#FFFFFF"><span style="background-color: #FF0000">足球走地注单确认中，请勿关闭窗口...</span></font><br><br><span id="timeinfo"></span><br><br>
      <input type=button name=button value="足球更新" onClick="window.location.reload()"></td>
  </tr>
</table>
</body>
</html>
