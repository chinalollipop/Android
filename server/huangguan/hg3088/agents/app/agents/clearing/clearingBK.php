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
<form name="myform" method="post" action="../score/finish_score.php?uid=<?php echo $uid?>&gid=<?php echo $gid?>&gtype=<?php echo $gtype?>&langx=<?php echo $langx?>">
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
          <td width="30">发布</td>
          <td width="90">投注时间</td>
          <td width="80">用户名称</td>
          <td width="100">球赛种类</td>
          <td width="355">內容</td>
          <td width="70">投注</td>
          <td width="100">占成结果</td>
          <td width="40">退水</td>
          <td width="100">实际金额</td>
        </tr>
<?php
$field_count=0;
$sendAwardTime='';
$mysql="select ID,Active,userid,M_Name,LineType,OpenType,BetTime,M_Date,OddsType,ShowType,Mtype,Gwin,BetType,M_Place,M_Rate,$middle as Middle,BetScore,A_Point,B_Point,C_Point,D_Point,Pay_Type,Checked from ".DBPREFIX."web_report_data where MID='$gid' and (Active=2 or Active=22) and Cancel=0 and Checked=0 order by linetype,mtype";
$result = mysqli_query($dbLink,$mysql);
	while ($row = mysqli_fetch_assoc($result)){
        if($row['LineType']!=8 && $row['LineType']!=16){
        $mtype = $row['Mtype'];
        $id = $row['ID'];
        $userid = $row['userid'];
        $user = $row['M_Name'];
        switch ($row['LineType']) {
            case 1://独赢
                $graded = win_chk($mb_in_score, $tg_in_score, $row['Mtype']);
                break;
            case 2:
                $graded = odds_letb($mb_in_score, $tg_in_score, $row['ShowType'], $row['M_Place'], $row['Mtype']);
                break;
            case 3:
                $graded = odds_dime($mb_in_score, $tg_in_score, $row['M_Place'], $row['Mtype']);
                break;
            case 5:
                $graded = odds_eo($mb_in_score, $tg_in_score, $row['Mtype']);
                break;
            case 9:
                $graded = odds_letb_rb($mb_in_score, $tg_in_score, $row['ShowType'], $row['M_Place'], $row['Mtype']);
                break;
            case 10:
                $graded = odds_dime_rb($mb_in_score, $tg_in_score, $row['M_Place'], $row['Mtype']);
                break;
            case 13://球队得分大小
                $ouStr = '';
                $ouStr = substr($row['M_Place'], 0, 1);
                if ($ouStr == 'U' || $ouStr == 'O') {
                    $graded = team_score_ou($mb_in_score, $tg_in_score, $row['M_Place'], $row['Mtype'] . $ouStr);
                } else {
                    $graded = team_score_ou($mb_in_score, $tg_in_score, $row['M_Place'], $row['Mtype']);
                }
                break;
            case 21://滚球独赢
                $graded = win_chk_rb($mb_in_score, $tg_in_score, $row['Mtype']);
                break;
            case 23://滚球球队得分大小
                $wMtype = substr($row['Mtype'], 1);
                $ouStr = '';
                $ouStr = substr($row['M_Place'], 0, 1);
                $graded = team_score_ou($mb_in_score, $tg_in_score, $row['M_Place'], $wMtype . $ouStr);
                break;
            case 31://球队得分最后一位数
                $graded = store_last_num($mb_in_score, $tg_in_score, $row['Mtype']);
                break;
            case 105://滚球单双
                $graded = odds_eo($mb_in_score, $tg_in_score, substr($row['Mtype'], 1));
                break;
            case 131://球队得分最后一位数 滚球
                $graded = store_last_num($mb_in_score, $tg_in_score, $row['Mtype']);
                break;
        }
        if ($row['M_Rate'] < 0) {
            $num = str_replace("-", "", $row['M_Rate']);
        } else if ($row['M_Rate'] > 0) {
            $num = 1;
        }
        switch ($graded) {
            case 1:
                $g_res = $row['Gwin'];
                break;
            case 0.5:
                $g_res = $row['Gwin'] * 0.5;
                break;
            case -0.5:
                $g_res = -$row['BetScore'] * 0.5 * $num;
                break;
            case -1:
                $g_res = -$row['BetScore'] * $num;
                break;
            case 0:
                $g_res = 0;
                break;
        }

        if (in_array($row['LineType'], array(2, 3, 9, 10, 13, 23))) {//让球大小不包含本金
            if ($row['M_Rate'] <= 0.5) {
                $vgold = 0;
            } else {
                $vgold = abs($graded) * $row['BetScore'];
            }
        } else {
            if ($row['M_Rate'] <= 1.5) {
                $vgold = 0;
            } else {
                $vgold = abs($graded) * $row['BetScore'];
            }
        }

        $betscore = number_format($row['BetScore'], 2);
        $d_point = $row['D_Point'] / 100;
        $c_point = $row['C_Point'] / 100;
        $b_point = $row['B_Point'] / 100;
        $a_point = $row['A_Point'] / 100;

        $members = $g_res;//和会员结帐的金额
        $agents = $g_res * (1 - $d_point);//上缴总代理结帐的金额
        $world = $g_res * (1 - $c_point - $d_point);//上缴股东结帐
        if (1 - $b_point - $c_point - $d_point != 0) {
            $corprator = $g_res * (1 - $b_point - $c_point - $d_point);//上缴公司结帐
        } else {
            $corprator = $g_res * ($b_point + $a_point);//和公司结帐
        }
        $super = $g_res * $a_point;//和公司结帐
        $agent = $g_res;//公司退水帐目

        if (!mysqli_query($dbMasterLink, "START TRANSACTION")) {
            echo "足球手动派奖事务开启失败！";
            continue;
        }
        $sql_for_update = "select checked from " . DBPREFIX . "web_report_data where ID='" . $row['ID'] . "' for update ";
        $query = mysqli_query($dbMasterLink, $sql_for_update);
        $bill_count_flag = mysqli_fetch_array($query);
        //订单已结算
        if ($bill_count_flag['checked'] == 1) {
            echo "订单已结算，事务回滚!";
            mysqli_query($dbMasterLink, "ROLLBACK");
            continue;
        }
        $userMoneyLock = mysqli_query($dbMasterLink, "select Money,test_flag from " . DBPREFIX . MEMBERTABLE . " where ID=$userid for update");
        if (!$userMoneyLock) {
            echo "用户资金锁添加失败!";
            mysqli_query($dbMasterLink, "ROLLBACK");
            continue;
        }
        $sendAwardTime = date('Y-m-d H:i:s', time());
        if ($mb_in_score < 0) {
            $cash = $row['BetScore'];
        } else {
            $cash = $row['BetScore'] + $members;
        }
        $mysql = "update " . DBPREFIX . MEMBERTABLE . " set Money=Money+$cash where ID=$userid";
        if (!mysqli_query($dbMasterLink, $mysql)) {
            echo "派奖更新用户金额失败!";
            mysqli_query($dbMasterLink, "ROLLBACK");
            continue;
        }

        //生成资金账变记录
        if ($mb_in_score < 0) {
            $moneyLogDesc = "取消注单,退还本金{$row['BetScore']}";
        } else {
            switch ($graded) {
                case 1:
                    $moneyLogDesc = "赢:退还本金{$row['BetScore']},派奖$members";
                    break;
                case 0.5:
                    $moneyLogDesc = "赢一半:退还本金{$row['BetScore']},派奖$members";
                    break;
                case -1:
                    $moneyLogDesc = "输";
                    break;
                case -0.5:
                    $moneyLogDesc = "输一半:退还一半本金$cash";
                    break;
                case 0:
                    $moneyLogDesc = "和局:退还本金$cash";
                    break;
            }
        }
        $moneyLogDesc .= ",BK人工结算,操作人:" . $_SESSION['UserName'];
        //添加用户资金账变记录
        $userMoneyRow = mysqli_fetch_array($userMoneyLock);
        $moneyLogRes = addAccountRecords(array($userid, $user, $userMoneyRow['test_flag'], $userMoneyRow['Money'], $cash, $userMoneyRow['Money'] + $cash, 3, 6, $id, $moneyLogDesc));
        if (!$moneyLogRes) {
            echo "用户自己账变日志写入失败!";
            mysqli_query($dbMasterLink, "ROLLBACK");
            continue;
        }

        if ($mb_in_score < 0 and $mb_in_score_v < 0) {
            $sql = "update " . DBPREFIX . "web_report_data set VGOLD='0',M_Result='0',D_Result='0',C_Result='0',B_Result='0',A_Result='0',T_Result='0',Cancel=1,Checked=1,sendAwardTime='$sendAwardTime',sendAwardIsAuto=2,sendAwardName='" . $_SESSION['UserName'] . "',Confirmed='$mb_in_score',updateTime='" . date('Y-m-d H:i:s', time()) . "' where ID='$id' and (active=2 or active=22) and LineType!=8";
        } else {
            $sql = "update " . DBPREFIX . "web_report_data set VGOLD='$vgold',M_Result='$members',D_Result='$agents',C_Result='$world',B_Result='$corprator',A_Result='$super',T_Result='$agent',sendAwardTime='$sendAwardTime',sendAwardIsAuto=2,sendAwardName='" . $_SESSION['UserName'] . "',Checked=1,updateTime='" . date('Y-m-d H:i:s', time()) . "' where ID='$id'";
        }

        if (mysqli_query($dbMasterLink, $sql)) {
            mysqli_query($dbMasterLink, "COMMIT");

            // 手工结算时，隔天结算时间超过2点30，需要重新生成此用户当前赛事的历史报表
            // 【如果结算时间与当前赛事日期相差大小超过1天又2小时30分钟，则重新生成历史报表】
            $basePoint = 60 * 60 * (24 + 2.5); // 相差基数，超过这个数则重新生成历史报表
            if ((time() - strtotime($row['M_Date'])) > $basePoint) {

                $update_bet_history_report_result = update_bet_history_report($userid, $row['M_Name'], $row['M_Date']);
                if ($update_bet_history_report_result['status']) {
                    mysqli_query($dbMasterLink, "COMMIT");
                } else {
                    echo "重新生成历史报表失败!" . $update_bet_history_report_result['msg'];
                    mysqli_query($dbMasterLink, "ROLLBACK");
                    continue;
                }
            }

        } else {
            echo "派奖更新用户注单表失败!";
            mysqli_query($dbMasterLink, "ROLLBACK");
            continue;
        }

        switch ($row['OddsType']) {
            case 'H':
                $Odds = '<BR><font color =green>' . $Rep_HK . '</font>';
                break;
            case 'M':
                $Odds = '<BR><font color =green>' . $Rep_Malay . '</font>';
                break;
            case 'I':
                $Odds = '<BR><font color =green>' . $Rep_Indo . '</font>';
                break;
            case 'E':
                $Odds = '<BR><font color =green>' . $Rep_Euro . '</font>';
                break;
            case '':
                $Odds = '';
                break;
        }
        $time = strtotime($row['BetTime']);
        $times = date("Y-m-d", $time) . '<br>' . date("H:i:s", $time);
        ?>
    <tr class="m_cen">
        <td><input type="checkbox" name="check<?php echo $field_count ?>" value="ON" checked></td>
        <td><font color="#cc0000"><?php echo $times ?></font></td>
        <td><?php echo $row['M_Name'] ?><br><font color="#cc0000"><?php echo $row['OpenType'] ?></font>&nbsp;&nbsp;</td>
        <td><?php echo $Mnu_BasketBall ?><?php echo $row['BetType'] ?><?php echo $Odds ?><br><font
                    color="#0000CC"><?php echo show_voucher($row['LineType'], $row['ID']) ?></font></td>
        <td align="right"><?php echo $row['Middle'] ?></td>
        <td align="right"><?php echo $row['BetScore'] ?></td>
        <td><?php echo $d_point ?>/<?php echo $c_point ?>/<?php echo $b_point ?>/<?php echo $a_point ?></td>
        <td><?php echo $turn ?></td>
        <td width="100" height="22"><p align="center">
                <input name="txtres<?php echo $field_count ?>" size="10" value="<?php echo $g_res + $turn ?>"
                       class="za_text">
                <input type="hidden" name="agents<?php echo $field_count ?>" size="8" value="<?php echo $agents ?>"
                       class="za_text">
                <input type="hidden" name="world<?php echo $field_count ?>" size="8" value="<?php echo $world ?>"
                       class="za_text">
                <input type="hidden" name="corprator<?php echo $field_count ?>" size="8"
                       value="<?php echo $corprator ?>" class="za_text">
                <input type="hidden" name="paytype<?php echo $field_count ?>" size="8"
                       value="<?php echo $row['pay_type'] ?>" class="za_text">
                <input type="hidden" name="username<?php echo $field_count ?>" size="8"
                       value="<?php echo $row['memname'] ?>" class="za_text">
                <input type="hidden" name="betscore<?php echo $field_count ?>" size="8"
                       value="<?php echo $row['BetScore'] ?>" class="za_text">
                <input type="hidden" name="txtnum<?php echo $field_count ?>" size="8" value="<?php echo $row['id'] ?>"
                       class="za_text">
            </p></td>
    </tr>
    <?php
    $field_count = $field_count + 1;
    }
}
$mysql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set Score=1 where Type='BK' and MID='$gid'";
mysqli_query($dbMasterLink,$mysql) or die ("error!!");
?>
  </table>
<p align="center">　<br>
<input type="submit" value=" 提 交 " name="subject" class="za_button">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;       
<input type="reset" value=" 返 回 " name="cancel" class="za_button">
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
