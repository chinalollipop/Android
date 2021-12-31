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
$page=$_REQUEST['page'];

require ("../include/traditional.$langx.inc.php");
$loginname=$_SESSION['UserName'];
$lv = $_REQUEST['lv'];
$date=date('Y-m-d');
$gtype=$_REQUEST['gtype'];
$gid=$_REQUEST['gid'];
$mb_in_score=isset($_REQUEST['mb_inball'])?$_REQUEST['mb_inball']:'';
$tg_in_score=isset($_REQUEST['tg_inball'])?$_REQUEST['tg_inball']:'';
$mb_in_score_v=isset($_REQUEST['mb_inball_v'])?$_REQUEST['mb_inball_v']:'';
$tg_in_score_v=isset($_REQUEST['tg_inball_v'])?$_REQUEST['tg_inball_v']:'';
//需直接传递过来比分：上半和全场，可根据实际情况分别分批传递
?>
<HTML>
<HEAD>
<TITLE></TITLE>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<link rel="stylesheet" href="/style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
<META content="Microsoft FrontPage 4.0" name=GENERATOR>
</HEAD>
<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0">
<form name="myform" method="post" action="../score/finish_score.php?uid=<?php echo $uid?>&gid=<?php echo $gid?>&gtype=<?php echo $gtype?>&langx=<?php echo $langx?>&lv=<?php echo $lv?>">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="m_tline" width="150">&nbsp;&nbsp;日期：<?php echo $date?></td>
    <td class="m_tline" width="200">主队：上半场:<font color=red>(<?php echo $mb_in_score_v?>)</font>全场:<font color=red>(<?php echo $mb_in_score?>)</font></td>
    <td class="m_tline" width="613">客队：上半场:<font color=red>(<?php echo $tg_in_score_v?>)</font>全场:<font color=red>(<?php echo $tg_in_score?>)</font></td>
  </tr>
  <tr>
    <td colspan="3" height="4"></td>
  </tr>
</table>
<table width="975" border="0" cellspacing="1" cellpadding="0" class="m_tab">
        <tr class="m_title"> 
          <td width="30" align="center">发布</td>
          <td width="90" align="center">投注时间</td>
          <td width="80" align="center">用户名称</td>
          <td width="100" align="center">球赛种类</td>
          <td width="355" align="center">內容</td>
          <td width="70" align="center">投注</td>
          <td width="100" align="center">占成结果</td>
          <td width="40" align="center">退水</td>
          <td width="100" align="center">实际金额</td>
        </tr>
<?php
$field_count=0;
$sendAwardTime='';
$mysql="select ID,MID,Active,userid,M_Name,LineType,OpenType,BetTime,M_Date,OddsType,ShowType,Mtype,Gwin,BetType,M_Place,M_Rate,$middle as Middle,BetScore,A_Point,B_Point,C_Point,D_Point,Pay_Type,Checked from ".DBPREFIX."web_report_data where MID='$gid' and (Active=1 or Active=11) and Cancel=0 and Checked=0 order by linetype,mtype";
$result = mysqli_query($dbLink,$mysql);
while ($row = mysqli_fetch_assoc($result)){
        if($row['LineType']!=8 && $row['LineType']!=16){
            $mmysql = "select MB_Team,TG_Team from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where MID=".$row['MID'];
            $resultm = mysqli_query($dbMasterLink,$mmysql);
            $mrow = mysqli_fetch_assoc($resultm);
            $sourceLog='';
            $moneyLogDesc='';
            $flag=true;
            $mtype=$row['Mtype'];
            $id=$row['ID'];
            $userid=$row['userid'];
            $user=$row['M_Name'];
            if(in_array($row['LineType'],array(11,12,13,14,204,15,16,205,206,19,20,31,50,244,144,165))){
                if( trim($tg_in_score_v)=='' || trim($mb_in_score_v)=='' ) {
                    continue;
                }
            }else{
                if( trim($tg_in_score)=='' || trim($mb_in_score)=='' ) {
                    continue;
                }
            }
            switch ($row['LineType']){
                case 1:
                    $graded=win_chk($mb_in_score,$tg_in_score,$row['Mtype']);
                    break;
                case 2:
                    $graded=odds_letb($mb_in_score,$tg_in_score,$row['ShowType'],$row['M_Place'],$row['Mtype']);
                    break;
                case 3:
                    $graded=odds_dime($mb_in_score,$tg_in_score,$row['M_Place'],$row['Mtype']);
                    break;
                case 4:
                    $graded=odds_pd($mb_in_score,$tg_in_score,$row['Mtype']);
                    break;
                case 5:
                    $graded=odds_eo($mb_in_score,$tg_in_score,$row['Mtype']);
                    break;
                case 6:
                    $graded=odds_t($mb_in_score,$tg_in_score,$row['Mtype']);
                    break;
                case 7:
                    $graded=odds_half($mb_in_score_v,$tg_in_score_v,$mb_in_score,$tg_in_score,$row['Mtype']);
                    break;
                case 9:
                    $score=explode('<FONT color=red><b>',$row['Middle']);
                    $msg=explode("</b></FONT><br>",$score[1]);
                    $bcd=explode(":",$msg[0]);
                    $m_in=$bcd[0];
                    $t_in=$bcd[1];
                    if ($row['ShowType']=='H'){
                        $mbinscore1=$mb_in_score-$m_in;
                        $tginscore1=$tg_in_score-$t_in;
                    }else{
                        $mbinscore1=$mb_in_score-$t_in;
                        $tginscore1=$tg_in_score-$m_in;
                    }
                    $graded=odds_letb_rb($mbinscore1,$tginscore1,$row['ShowType'],$row['M_Place'],$row['Mtype']);
                    break;
                case 10:
                    $graded=odds_dime_rb($mb_in_score,$tg_in_score,$row['M_Place'],$row['Mtype']);
                    break;
                case 11:
                    $graded=win_chk_v($mb_in_score_v,$tg_in_score_v,$row['Mtype']);
                    break;
                case 12:
                    $graded=odds_letb_v($mb_in_score_v,$tg_in_score_v,$row['ShowType'],$row['M_Place'],$row['Mtype']);
                    break;
                case 13:
                    $graded=odds_dime_v($mb_in_score_v,$tg_in_score_v,$row['M_Place'],$row['Mtype']);
                    break;
                case 14:
                    $graded=odds_pd_v($mb_in_score_v,$tg_in_score_v,$row['Mtype']);
                    break;
                case 15://半场单双
                    $wMtype= substr($row['Mtype'],1);
                    $graded=odds_eo($mb_in_score_v,$tg_in_score_v,$wMtype);
                    break;
                case 46://半场总入球
                    $graded=odds_t_v($mb_in_score_v,$tg_in_score_v,$row['Mtype']);
                    break;
                case 18://净胜球数
                    $graded=team_net_profit($mb_in_score,$tg_in_score,$row['Mtype']);
                    break;
                case 19://半场滚球让球
                    $score=explode('<FONT color=red><b>',$row['Middle']);
                    $msg=explode("</b></FONT><br>",$score[1]);
                    $bcd=explode(":",$msg[0]);
                    $m_in=$bcd[0];
                    $t_in=$bcd[1];
                    if ($row['ShowType']=='H'){
                        $mbinscore1=$mb_in_score_v-$m_in;
                        $tginscore1=$tg_in_score_v-$t_in;
                    }else{
                        $mbinscore1=$mb_in_score_v-$t_in;
                        $tginscore1=$tg_in_score_v-$m_in;
                    }
                    $graded=odds_letb_vrb($mbinscore1,$tginscore1,$row['ShowType'],$row['M_Place'],$row['Mtype']);
                    break;
                case 20://半场滚球大小
                    $graded=odds_dime_vrb($mb_in_score_v,$tg_in_score_v,$row['M_Place'],$row['Mtype']);
                    break;
                case 21:
                    $graded=win_chk_rb($mb_in_score,$tg_in_score,$row['Mtype']);
                    break;
                case 22://独赢 & 进球 大 /小
                    $graded=win_and_ou($mb_in_score,$tg_in_score,$row['Mtype'],$row['M_Place']);
                    break;
                case 23://独赢 & 双方球队进球
                    $graded=win_and_doublein($mb_in_score,$tg_in_score,$row['Mtype']);
                    break;
                case 24:	//进球 大 /小 & 双方球队进球
                    $graded=ou_and_doublein($mb_in_score,$tg_in_score,$row['Mtype'],$row['M_Place']);
                    break;
                case 25:	//独赢 & 最先进球
                    $graded=win_and_firstin($mb_in_score,$tg_in_score,$mb_time,$tg_time,$row['Mtype']);
                    break;
                case 28:	//最多进球的半场
                    $graded=most_half_ballin($mb_in_score,$tg_in_score,$mb_in_score_v,$tg_in_score_v,$row['Mtype']);
                    break;
                case 29:	//最多进球的半场 - 独赢
                    $graded=win_most_half_ballin($mb_in_score,$tg_in_score,$mb_in_score_v,$tg_in_score_v,$row['Mtype']);
                    break;
                case 30:	//双半场进球
                    $wType = substr($row['Mtype'],2,1);
                    $graded=double_half_in($mb_in_score,$tg_in_score,$mb_in_score_v,$tg_in_score_v,$wType);
                    break;
                case 31://半场滚球独赢
                    $graded=win_chk_vrb($mb_in_score_v,$tg_in_score_v,$row['Mtype']);
                    break;
                case 32:	//首个进球时间-3项
                    $wType = substr($row['Mtype'],3,1);
                    $graded=time3_first_in($mb_time,$tg_time,$M_Start,$wType);
                    break;
                case 33:	//首个进球时间
                    $wType = substr($row['Mtype'],3,1);
                    $graded=time_first_in($mb_time,$tg_time,$M_Start,$wType);
                    break;
                case 34:	//双重机会 & 进球 大 / 小
                    $graded=changeDouble_and_ou($mb_in_score,$tg_in_score,$row['Mtype'],$row['M_Place']);
                    break;
                case 35:	//双重机会 & 双方球队进球
                    $graded=change_and_in_double($mb_in_score,$tg_in_score,$row['Mtype']);
                    break;
                case 37:	//进球大小 && 进球单双
                    $graded=ou_and_oe_in($mb_in_score,$tg_in_score,$row['Mtype'],$row['M_Place']);
                    break;
                case 39:	//三项让球
                    $MiddleStr = explode('<br>',$row['Middle']);
                    $rangArr = explode('@',$MiddleStr[3]);
                    $rangStr = trim($rangArr[0]);
                    if($row['Mtype']=='W3H'){ $rangStr=str_replace($mrow['MB_Team'],'',$rangStr); }
                    if($row['Mtype']=='W3C'){ $rangStr=str_replace($mrow['TG_Team'],'',$rangStr);  }
                    if($row['Mtype']=='W3N'){ $rangStr=str_replace('让球和局','',$rangStr); }
                    $rangStr=str_replace('&nbsp;','',$rangStr);
                    $rangStr = trim(strip_tags($rangStr));
                    if( strpos($rangStr,'-')<0 && strpos($rangStr,'+')<0 ){ continue; }
                    $graded = rb_three_bet($mb_in_score,$tg_in_score,$row['Mtype'],$rangStr);
                    break;
                case 41:	//赢得任一半场
                    $graded=win_any_half($mb_in_score,$tg_in_score,$mb_in_score_v,$tg_in_score_v,$row['Mtype']);
                    break;
                case 42:	//赢得所有半场
                    $graded=win_all_half($mb_in_score,$tg_in_score,$mb_in_score_v,$tg_in_score_v,$row['Mtype']);
                    break;
                case 44://球队进球数大小
                    $graded=teamballin_odds_dime($mb_in_score,$tg_in_score,$row['M_Place'],$row['Mtype']);
                    break;
                case 61:	//零失球获胜
                    $graded=win_lost_inzero($mb_in_score,$tg_in_score,$row['Mtype']);
                    break;
                case 62:	//零失球
                    $graded=lost_inzero($mb_in_score,$tg_in_score,$row['Mtype']);
                    break;
                case 65://双方球队进球
                    $mypeNew = str_replace('TS','',$row['Mtype']);
                    $graded=doublein($mb_in_score,$tg_in_score,$mypeNew);
                    break;
                case 69:	//双重机会
                    $wType = substr($row['Mtype'],2,2);
                    $graded=change_double($mb_in_score,$tg_in_score,$wType);
                    break;
                case 104://滚球波胆
                    $mtypeSub = $row['Mtype'];
                    $mtypeSub = substr($mtypeSub,1);
                    $mtypeSub = str_replace('H','MB',$mtypeSub);
                    $mtypeSub = str_replace('C','TG',$mtypeSub);
                    $graded=odds_pd($mb_in_score,$tg_in_score,$mtypeSub);
                    break;
                case 105://滚球单双
                    $mtypeSub = substr($row['Mtype'],1);
                    $graded=odds_eo($mb_in_score,$tg_in_score,$mtypeSub);
                    break;
                case 106://滚球总入球
                    $mtypeSub = substr($row['Mtype'],1);
                    $graded=odds_t($mb_in_score,$tg_in_score,$mtypeSub);
                    break;
                case 107://滚球半/全场
                    $mtypeSub = substr($row['Mtype'],1);
                    $graded=odds_half($mb_in_score_v,$tg_in_score_v,$mb_in_score,$tg_in_score,$mtypeSub);
                    break;
                case 115://滚球双方球队进球
                    $mypeNew = str_replace('RTS','',$row['Mtype']);
                    $graded=doublein($mb_in_score,$tg_in_score,$mypeNew);
                    break;
                case 118:	//净胜球数
                    $wType = str_replace('R','',$row['Mtype']);
                    $graded=team_net_profit($mb_in_score,$tg_in_score,$wType);
                    break;
                case 119:	//双重机会
                    $wType = substr($row['Mtype'],3,2);
                    $graded=change_double($mb_in_score,$tg_in_score,$wType);
                    break;
                case 120:	//零失球
                    $graded=lost_inzero($mb_in_score,$tg_in_score,str_replace("R", "",$row['Mtype']));
                    break;
                case 122:	//独赢 & 进球 大 /小
                    $graded=win_and_ou($mb_in_score,$tg_in_score,str_replace("R","",$row['Mtype']),$row['M_Place']);
                    break;
                case 123:	//独赢 & 双方球队进球
                    $graded=win_and_doublein($mb_in_score,$tg_in_score,str_replace("R","",$row['Mtype']));
                    break;
                case 124:	//进球 大 /小 & 双方球队进球
                    $graded=ou_and_doublein($mb_in_score,$tg_in_score,str_replace("R","O",$row['Mtype']),$row['M_Place']);
                    break;
                case 128:	//最多进球的半场
                    $graded=most_half_ballin($mb_in_score,$tg_in_score,$mb_in_score_v,$tg_in_score_v,str_replace("R","",$row['Mtype']));
                    break;
                case 129:	//最多进球的半场 - 独赢
                    $graded=win_most_half_ballin($mb_in_score,$tg_in_score,$mb_in_score_v,$tg_in_score_v,str_replace("R","",$row['Mtype']));
                    break;
                case 130:	//双半场进球
                    $wType = substr($row['Mtype'],3,1);
                    $graded=double_half_in($mb_in_score,$tg_in_score,$mb_in_score_v,$tg_in_score_v,$wType);
                    break;
                case 134://双重机会 & 进球 大 / 小
                    $graded=changeDouble_and_ou($mb_in_score,$tg_in_score,str_replace("R","",$row['Mtype']),$row['M_Place']);
                    break;
                case 135:	//双重机会 & 双方球队进球
                    $graded=change_and_in_double($mb_in_score,$tg_in_score,str_replace("R","",$row['Mtype']));
                    break;
                case 137:	//进球大小 && 进球单双
                    $graded=ou_and_oe_in($mb_in_score,$tg_in_score,str_replace("R","O",$row['Mtype']),$row['M_Place']);
                    break;
                case 139:	//滚球足球三项让球
                    $graded=rb_three_bet($mb_in_score,$tg_in_score,str_replace("R","",$row['Mtype']),$row['ShowType']);
                    break;
                case 144://半场球队进球数大小
                    $wType = substr($row['Mtype'],1);
                    $graded=teamballin_odds_dime($mb_in_score_v,$tg_in_score_v,$row['M_Place'],$wType);
                    break;
                case 141:	//赢得任一半场
                    $graded=win_any_half($mb_in_score,$tg_in_score,$mb_in_score_v,$tg_in_score_v,str_replace("R","",$row['Mtype']));
                    break;
                case 142:	//赢得所有半场
                    $graded=win_all_half($mb_in_score,$tg_in_score,$mb_in_score_v,$tg_in_score_v,str_replace("R","",$row['Mtype']));
                    break;
                case 154://滚球球队进球数大小
                    $mypeNew = $row['Mtype'];
                    $mypeNew = substr($mypeNew,1);
                    $graded=teamballin_odds_dime($mb_in_score,$tg_in_score,$row['M_Place'],$mypeNew);
                    break;
                case 161:	//零失球获胜
                    $wType = str_replace("R","",$row['Mtype']);
                    $graded=win_lost_inzero($mb_in_score,$tg_in_score,$wType);
                    break;
                case 165:	//半场双方球队进球
                    $mypeNew = str_replace('HTS','',$row['Mtype']);
                    $graded=doublein($mb_in_score_v,$tg_in_score_v,$mypeNew);
                    break;
                case 204://半场滚球波胆
                    $wType = str_replace('HR','',$row['Mtype']);
                    $wType = str_replace('H','MB',$wType);
                    $wType = str_replace('C','TG',$wType);
                    $graded=odds_pd_v($mb_in_score_v,$tg_in_score_v,$wType);
                    break;
                case 205://半场单双
                    $wMtype= substr($row['Mtype'],2);
                    $graded=odds_eo($mb_in_score_v,$tg_in_score_v,$wMtype);
                    break;
                case 206://滚球半场总入球
                    $mtypeSub = str_replace("R","",$row['Mtype']);
                    $graded=odds_t_v($mb_in_score_v,$tg_in_score_v,$mtypeSub);
                    break;
                case 244://滚球半场球队进球数大小
                    $wType = str_replace('R','',$row['Mtype']);
                    $wType = substr($wType,1);
                    $graded=teamballin_odds_dime($mb_in_score_v,$tg_in_score_v,$row['M_Place'],$wType);
                    break;
                default: $flag=false;
            }
            if($flag==false) {
                continue;
            }
            if ($row['M_Rate']<0){
                $num=str_replace("-","",$row['M_Rate']);
            }else if ($row['M_Rate']>0){
                $num=1;
            }
            switch ($graded){
                case 1:
                    $g_res=$row['Gwin'];
                    break;
                case 0.5:
                    $g_res=$row['Gwin']*0.5;
                    break;
                case -0.5:
                    $g_res=-$row['BetScore']*0.5*$num;
                    break;
                case -1:
                    $g_res=-$row['BetScore']*$num;
                    break;
                case 0:
                    $g_res=0;
                    break;
            }

            if(in_array($row['LineType'],array(2,3,9,10,12,13,19,20,44,144,154,244))){//让球、大小、球队进球数大小，不包含本金
                if($row['M_Rate']<=0.5){
                    $vgold=0;
                }else{
                    $vgold=abs($graded)*$row['BetScore'];
                }
            }else{
                if($row['M_Rate']<=1.5){
                    $vgold=0;
                }else{
                    $vgold=abs($graded)*$row['BetScore'];
                }
            }

            $betscore=number_format($row['BetScore'],2);
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
            $agent=$g_res*1;//公司退水帐目

            if( !mysqli_query($dbMasterLink, "START TRANSACTION")) {
                echo "足球手动派奖事务开启失败！" ;
                continue;
            }
            $sql_for_update = "select checked from ".DBPREFIX."web_report_data where ID='" . $row['ID'] ."' for update ";
            $query=mysqli_query($dbMasterLink,$sql_for_update);
            $bill_count_flag=mysqli_fetch_array($query);
            //订单已结算
            if( $bill_count_flag['checked'] == 1 ) {
                echo "订单已结算，事务回滚!";
                mysqli_query($dbMasterLink, "ROLLBACK");
                continue;
            }

            $userMoneyLock = mysqli_query($dbMasterLink,"select Money,test_flag from ".DBPREFIX.MEMBERTABLE." where ID=$userid for update");
            if(!$userMoneyLock){
                echo "用户资金锁添加失败!";
                mysqli_query($dbMasterLink, "ROLLBACK");
                continue;
            }
            $sendAwardTime=date('Y-m-d H:i:s',time());
            if($mb_in_score<0 and $mb_in_score_v<0){
                $cash=$row['BetScore'];
            }else{
                $cash=$row['BetScore']+$members;
            }

            $mysql="update ".DBPREFIX.MEMBERTABLE." set Money=Money+$cash where ID=$userid";
            if(!mysqli_query($dbMasterLink,$mysql)){
                echo "派奖更新用户金额失败!";
                mysqli_query($dbMasterLink, "ROLLBACK");
                continue;
            }

            //生成资金账变记录
            if($mb_in_score<0 and $mb_in_score_v<0){
                $moneyLogDesc="取消注单,退还本金{$row['BetScore']}";
            }else{
                switch ($graded){
                    case 1:
                        $moneyLogDesc="赢:退还本金{$row['BetScore']},派奖$members";
                        break;
                    case 0.5:
                        $moneyLogDesc="赢一半:退还本金{$row['BetScore']},派奖$members";
                        break;
                    case -1:
                        $moneyLogDesc="输";
                        break;
                    case -0.5:
                        $moneyLogDesc="输一半:退还一半本金$cash";
                        break;
                    case 0:
                        $moneyLogDesc="和局:退还本金$cash";
                        break;
                }
            }
            $moneyLogDesc.=",FT人工结算,操作人:".$_SESSION['UserName'];
            //添加用户资金账变记录
            $userMoneyRow=mysqli_fetch_array($userMoneyLock);
            $moneyLogRes=addAccountRecords(array($userid,$user,$userMoneyRow['test_flag'],$userMoneyRow['Money'],$cash,$userMoneyRow['Money']+$cash,3,6,$id,$moneyLogDesc));
            if(!$moneyLogRes){
                echo "用户自己账变日志写入失败!";
                mysqli_query($dbMasterLink, "ROLLBACK");
                continue;
            }

            if($mb_in_score<0 and $mb_in_score_v<0){
                $sql="update ".DBPREFIX."web_report_data set VGOLD='0',M_Result='0',D_Result='0',C_Result='0',B_Result='0',A_Result='0',T_Result='0',Cancel=1,Checked=1,Confirmed='$mb_in_score',sendAwardTime='$sendAwardTime',sendAwardIsAuto=2,sendAwardName='".$_SESSION['UserName']."',updateTime='".date('Y-m-d H:i:s',time())."' where ID='$id' and (active=1 or active=11) and LineType!=8";
            }else{
                $sql="update ".DBPREFIX."web_report_data set VGOLD='$vgold',M_Result='$members',D_Result='$agents',C_Result='$world',B_Result='$corprator',A_Result='$super',T_Result='$agent',sendAwardTime='$sendAwardTime',sendAwardIsAuto=2,sendAwardName='".$_SESSION['UserName']."',Checked=1,updateTime='".date('Y-m-d H:i:s',time())."' where ID='$id'";
            }

            if(mysqli_query($dbMasterLink,$sql)){
                    mysqli_query($dbMasterLink, "COMMIT");

                // 手工结算时，隔天结算时间超过2点30，需要重新生成此用户当前赛事的历史报表
                // 【如果结算时间与当前赛事日期相差大小超过1天又2小时30分钟，则重新生成历史报表】
                $basePoint = 60*60*(24+2.5); // 相差基数，超过这个数则重新生成历史报表
                if ( (time()-strtotime($row['M_Date'])) > $basePoint ){

                    echo '生成历史报表开始：<br>';

                    $update_bet_history_report_result = update_bet_history_report($userid, $row['M_Name'],$row['M_Date']);
                    if (!$update_bet_history_report_result['status']){
                        echo "重新生成历史报表失败!".$update_bet_history_report_result['msg'];
                        mysqli_query($dbMasterLink, "ROLLBACK");
                        continue;
                    }
                }
            }else{
                echo "派奖更新用户注单表失败!";
                mysqli_query($dbMasterLink, "ROLLBACK");
                continue;
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
              <td><input type="checkbox" name="check<?php echo $field_count?>" value="ON" checked></td>
              <td><font color="#cc0000"><?php echo $times?></font></td>
              <td><?php echo $row['M_Name']?><br><font color="#cc0000"><?php echo $row['OpenType']?></font>&nbsp;&nbsp;</td>
              <td><?php echo $Mnu_Soccer?><?php echo $row['BetType']?><?php echo $Odds?><br><font color="#0000CC"><?php echo show_voucher($row['LineType'],$row['ID'])?></font></td>
              <td align="right"><?php echo $row['Middle']?></td>
              <td align="right"><?php echo number_format($row['BetScore'],2)?></td>
              <td><?php echo $d_point?>/<?php echo $c_point?>/<?php echo $b_point?>/<?php echo $a_point?></td>
              <td><?php echo $turn?></td>
              <td width="100" height="22"><p align="center">
              <input name="txtres<?php echo $field_count?>" size="10" value="<?php echo $g_res+$turn?>" class="za_text">
              <input type="hidden" name="agents<?php echo $field_count?>" size="8" value="<?php echo $agents?>" class="za_text">
              <input type="hidden" name="world<?php echo $field_count?>" size="8" value="<?php echo $world?>" class="za_text">
              <input type="hidden" name="corprator<?php echo $field_count?>" size="8" value="<?php echo $corprator?>" class="za_text">
              <input type="hidden" name="paytype<?php echo $field_count?>" size="8" value="<?php echo $row['pay_type']?>" class="za_text">
              <input type="hidden" name="username<?php echo $field_count?>" size="8" value="<?php echo $row['memname']?>" class="za_text">
              <input type="hidden" name="betscore<?php echo $field_count?>" size="8" value="<?php echo number_format($row['BetScore'],2)?>" class="za_text">
              <input type="hidden" name="txtnum<?php echo $field_count?>" size="8" value="<?php echo $row['id']?>" class="za_text">
              </p></td>
            </tr>
            <?php
            $field_count=$field_count+1;
        }
}
            $mysql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set Score=1 where Type='FT' and MID='$gid'";
            mysqli_query($dbMasterLink,$mysql);
            ?>
</table>
<p align="center">　<br>
<input type="submit" value=" 提 交 " name="subject" class="za_button">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;       
<input type="reset" value=" 返 回 " name="cancel" class="za_button" onclick="javascript:history.go(-1);">
<input type="hidden" name="mb_inball" value="<?php echo $mb_in_score?>">   
<input type="hidden" name="tg_inball" value="<?php echo $tg_in_score?>">   
<input type="hidden" name="tg_inball_v" value="<?php echo $tg_in_score_v?>">   
<input type="hidden" name="mb_inball_v" value="<?php echo $mb_in_score_v?>">   
<input type="hidden" name="gtype" value="<?php echo $gtype?>">
<input type="hidden" name="gid" value="<?php echo $gid?>">
<input type="hidden" name="page" value="<?php echo $page?>">
</form>
</BODY>
</html>
