<?php
//session_start();
/**
 * /FT_order_nfs_finish_api.php
 * 冠军下注接口（篮球与足球公用）
 *
 * gid  比赛盘口唯一ID
 * active   1 足球滚球、今日赛事, 11 足球早餐，2 篮球滚球、今日赛事, 22 篮球早餐
 * line_type  玩法列号
 * odd_f_type      默认传参H。 H 香港盘，M 马来盘，I 印尼盘，E 欧洲盘
 * gold  金额
 * type   H 主队独赢 C 客队独赢 N 和局  C 滚球大小-小  H 滚球大小-大 C 球队得分大小-主队 H 球队得分大小-客队 H 主队让球 C 客队让球
 * pay_type   0 信用额投注  1 现金投注【此参数暂时不需要】
 * rtype  单双玩法投注传参，让后赋值给mtype   ODD 单 EVEN 双
 * wtype 全场大小、半场大小、球队得分大小 OUH 大，OUC 小，ROUH 球队得分大小-大，ROUC 球队得分大小-小
 *
 * strong
 * gnum  投注的队伍ID
 * randomNum 随机数
 * autoOdd 自动接收较佳赔率
 */
//error_reporting(E_ALL);
//ini_set('display_errors','On');
//include('../include/address.mem.php');
//include_once('../include/config.inc.php');
//require_once("../../../common/sportCenterData.php");
//require ("../include/define_function_list.inc.php");
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {

    $status='401.1';
    $describe="请重新登录";
    original_phone_request_response($status,$describe);

}

$randomNum = $_REQUEST['randomNum']; // 随机整数
if(!$randomNum){
    $status='401.2';
    $describe="参数不对";
    original_phone_request_response($status,$describe);
}
if($randomNum == $_SESSION['randomNum']){ // 重复提交
    $status='401.3';
    $describe="请不要重复下注";
    original_phone_request_response($status,$describe);
}else{ // 正常提交

    //接收传递过来的参数：其中赔率和位置需要进行判断
    $uid = $_SESSION['Oid'];
    $langx = $_SESSION['Language'];
    $gold = $_REQUEST['gold'];
    $active = 7; // 冠军
    $strong = $_REQUEST['strong'];
    $line = $_REQUEST['line_type'];
    $gid = $_REQUEST['gid'];
    $type = $_REQUEST['type'];
    $rtype = $_REQUEST['rtype'];
    $wtype = $_REQUEST['wtype'];
    $gnum = $_REQUEST['gnum'];
    $odd_f_type = $_REQUEST['odd_f_type'];

    //require("../include/traditional.$langx.inc.php");



}

//下注时的赔率：应该根据盘口进行转换后，与数据库中的赔率进行比较。若不相同，返回下注。
$sql = "select ratio,Money,CurType,Status from ".DBPREFIX.MEMBERTABLE." where ID='{$_SESSION['userid']}' ";
$result = mysqli_query($dbMasterLink, $sql);
$memrow = mysqli_fetch_assoc($result);
$open = $_SESSION['OpenType'];
$pay_type = $_SESSION['Pay_Type'];
$memname = $_SESSION['UserName'];
$agents = $_SESSION['Agents']; // 代理 D
$world = $_SESSION['World']; // 总代 C
$corprator = $_SESSION['Corprator']; // 股东 B
$super = $_SESSION['Super']; // 公司 A
$admin = $_SESSION['Admin']; // 管理员 （？子账号）
$w_ratio = $memrow['ratio'];
$HMoney = $Money = $memrow['Money'];
if($HMoney < $gold || $gold<10 || $HMoney<=0){

    $status = '401.4';
    $describe = "下注金額不可大於信用額度。";
    original_phone_request_response($status, $describe);

}

if($wtype=='FS' && $gold<50 ){
    $status = '401.5';
    $describe = "冠军单注最低50。";
    original_phone_request_response($status, $describe);
}


if ($memrow['Status'] == 1) {

    $status = '403.5';
    $describe = "账户已冻结，请联系客服解冻";
    original_phone_request_response($status, $describe);

}

if ($memrow['Status'] == 2) {

    $status = '403.6';
    $describe = "账户已停用，请联系客服";
    original_phone_request_response($status, $describe);

}
$w_current = $memrow['CurType'];
$memid = $_SESSION['userid'];
$test_flag = $_SESSION['test_flag'];

//判断此赛程是否已经关闭：取出此场次信息and inball=''
$mysql = "select M_Start,Gid,M_League,M_League_tw,M_League_en,M_Item,M_Item_tw,M_Item_en,mshow,MB_Team,MB_Team_tw,MB_Team_en,M_Rate from ".DBPREFIX.SPORT_FLUSH_FS_MATCH_TABLE." where `M_Start`>now() and MID='$gid' and  Gid='$rtype'";
$result = mysqli_query($dbMasterLink,$mysql);
$cou=mysqli_num_rows($result);
if ($cou==0){
    $status='401.7';
    $describe=$Order_This_match_is_closed_Please_try_again;
    original_phone_request_response($status,$describe);

}
else {

    $row = mysqli_fetch_assoc($result);
    $turn_url = "/app/member/FT_order/FT_order_nfs.php?gid=" . $gid . "&uid=" . $uid . "&rtype=" . $rtype . "&wtype=" . $wtype . "&gametype=" . $gametype . "&langx=" . $langx;
    //下注时间Date('Y').'-'.   $row["ShowType"]
    $m_date = date("Y-m-d", strtotime($row["M_Start"]));
    $showtype = $row["ShowTypeR"];
    $bettime = date('Y-m-d H:i:s');

    //联盟处理:生成写入数据库的联盟样式和显示的样式，二者有区别
    $w_gtype = $row['Gid'];
    $w_sleague = $row['M_League'];
    $w_sleague_tw = $row['M_League_tw'];
    $w_sleague_en = $row['M_League_en'];
    $s_sleague = $row[$m_league];

    $w_sitem = $row['M_Item'];
    $w_sitem_tw = $row['M_Item_tw'];
    $w_sitem_en = $row['M_Item_en'];
    $s_sitem = $gametype . "冠军";
    $M_Item = $row[$m_item];

    //根据下注的类型进行处理：构建成新的数据格式，准备写入数据库

    $bet_type = '冠军';
    $bet_type_tw = "冠軍";
    $bet_type_en = "Outright";

    $ftype = $row['mshow'];
    $w_mb_team = $row['MB_Team'];
    $w_mb_team_tw = $row['MB_Team_tw'];
    $w_mb_team_en = $row['MB_Team_en'];
    $s_mb_team = $team[$i];
    $s_m_rate = change_rate($open, $row['M_Rate']);

    $gwin = ($s_m_rate - 1) * $gold;
    $wtype = $gametype;

    $lines = $row['M_League'] . "&nbsp;-&nbsp;" . $w_mb_team . "<br>" . $row['M_Item'] . "&nbsp;&nbsp;@&nbsp;<FONT color=#CC0000><b>" . $s_m_rate . "</b></FONT>";
    $lines_tw = $row['M_League_tw'] . "&nbsp;-&nbsp;" . $w_mb_team_tw . "<br>" . $row['M_Item_tw'] . "&nbsp;&nbsp;@&nbsp;<FONT color=#CC0000><b>" . $s_m_rate . "</b></FONT>";
    $lines_en = $row['M_League_en'] . "&nbsp;-&nbsp;" . $w_mb_team_en . "<br>" . $row['M_Item_en'] . "&nbsp;&nbsp;@&nbsp;<FONT color=#CC0000><b>" . $s_m_rate . "</b></FONT>";
    //echo $lines_tw;exit;
    $ip_addr = get_ip();

//    if (!isset($_REQUEST['autoOdd']) || $_REQUEST['autoOdd'] != "Y") {
//        if ($w_m_rate != $_REQUEST['ioradio_r_h']) {
//            $status = '401.14';
//            $describe = "赔率不一致，请更新赔率后下注~~";
//            original_phone_request_response($status, $describe);
//        }
//    }

    $psql = "select A_Point,B_Point,C_Point,D_Point from " . DBPREFIX . "web_agents_data where UserName='$agents'";
    $result = mysqli_query($dbMasterLink, $psql);
    $prow = mysqli_fetch_assoc($result);
    $a_point = $prow['A_Point'] + 0;
    $b_point = $prow['B_Point'] + 0;
    $c_point = $prow['C_Point'] + 0;
    $d_point = $prow['D_Point'] + 0;

    $showVoucher = show_voucher($wtype);
    //判断终端类型
    if ($_REQUEST['appRefer'] == 13 || $_REQUEST['appRefer'] == 14) {
        $playSource = $_REQUEST['appRefer'];
    } else {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') || strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')) {
            $playSource = 3;
        } else if (strpos($_SERVER['HTTP_USER_AGENT'], 'Android')) {
            $playSource = 4;
        } else {
            $playSource = 5;
        }
    }

    $begin = mysqli_query($dbMasterLink, "start transaction");
    $lockResult = mysqli_query($dbMasterLink, "select Money from ".DBPREFIX.MEMBERTABLE." where ID = " . $memid . " for update");
    if ($begin && $lockResult) {
        $checkRow = mysqli_fetch_assoc($lockResult);
        $HMoney = $Money = $checkRow['Money'];
        $havemoney = $HMoney - $gold;
        if ($havemoney < 0 || $gold <= 0 || $HMoney <= 0) {
            mysqli_query($dbMasterLink, "ROLLBACK");

            $status = '401.18';
            $describe = $User_insufficient_balance . rand(1, 199);
            original_phone_request_response($status, $describe);

        }
        $sql = "INSERT INTO " . DBPREFIX . "web_report_data	(QQ83068506,Glost,playSource,danger,MID,Userid,testflag,Active,orderNo,LineType,Mtype,M_Date,BetTime,BetScore,Middle,Middle_tw,Middle_en,BetType,BetType_tw,BetType_en,M_Place,M_Rate,M_Name,Gwin,OpenType,OddsType,ShowType,Agents,World,Corprator,Super,Admin,A_Point,B_Point,C_Point,D_Point,BetIP,Ptype,Gtype,CurType,Ratio,MB_MID,TG_MID,Pay_Type,Orderby,MB_Ball,TG_Ball) values ('$inball1',$Money,$playSource,'0','$gid',$memid,$test_flag,'$active','$showVoucher','$line','$w_gtype','$m_date','$bettime','$gold','$lines','$lines_tw','$lines_en','$bet_type','$bet_type_tw','$bet_type_en','$grape','$s_m_rate','$memname','$gwin','$open','$oddstype','$showtype','$agents','$world','$corprator','$super','$admin','$a_point','$b_point','$c_point','$d_point','$ip_addr','$wtype','FS','$w_current','$w_ratio','$w_mb_mid','$w_tg_mid','$pay_type','$order','$mb_ball','$tg_ball')";
        $insertBet = mysqli_query($dbMasterLink, $sql);
        if ($insertBet) {
            $lastId=mysqli_insert_id($dbMasterLink);
            $moneyLogRes = addAccountRecords(array($memid, $memname, $test_flag, $Money, $gold * -1, $havemoney, 1, $playSource, $lastId, "冠军投注"));
            if ($moneyLogRes) {
                $sql1 = "update ".DBPREFIX.MEMBERTABLE." set Money=" . $havemoney . " , Online=1 , OnlineTime=now() where ID=" . $memid;
                $updateMoney = mysqli_query($dbMasterLink, $sql1);
                if ($updateMoney) {
                    mysqli_query($dbMasterLink, "COMMIT");
                } else {
                    mysqli_query($dbMasterLink, "ROLLBACK");
                    $status = '401.19';
                    $describe = "操作失败!!" . rand(1, 199);
                    original_phone_request_response($status, $describe);
                }
            } else {
                mysqli_query($dbMasterLink, "ROLLBACK");

                $status = '401.20';
                $describe = "操作失败2!!" . rand(1, 199);
                original_phone_request_response($status, $describe);
            }
        } else {
            mysqli_query($dbMasterLink, "ROLLBACK");
            $status = '401.21';
            $describe = "操作失败!" . rand(1, 199);
            original_phone_request_response($status, $describe);
        }
    } else {
        mysqli_query($dbMasterLink, "ROLLBACK");
        $status = '401.22';
        $describe = "操作失败0!" . rand(1, 199);
        original_phone_request_response($status, $describe);
    }
}

$caption = $s_sitem.$Order_betting_order;
$w_m_rate = $s_m_rate;
$s_m_place = $row[$mb_team].'- '.$M_Item;

// 确定交易生成图片开关
if(GENERATE_IMA_SWITCH) {
    // 需要参数
    $data = array(
        'caption' => $caption, //交易单标题
        'Order_Bet_success' => $Order_Bet_success, //交易成功单号
        'showVoucher' => $showVoucher, //单号
        's_sleague' => $s_sleague,  //联盟处理:联盟样式和显示的样式
        'btype' => $btype, // 在联赛名称后面显示
        'M_Date' => date('m-d',strtotime($row["M_Date"])), //日期
        'Sign' => $Sign?$Sign:'', // 让球数
        's_mb_team' => $s_mb_team?$s_mb_team:'',   // 主队
        's_tg_team' => $s_tg_team?$s_tg_team:'',  // 客队
        's_m_place' => $s_m_place,  // 选择所属队
        'w_m_rate' => $w_m_rate,  // 赔率
        'gold' => $gold, // 下注金额
        'playSource' => $playSource,  // 投注来源:0未知,1pc旧版,2pc新版,3苹果,4安卓，13原生苹果,14原生安卓
        'gwin' => $gwin, // 可赢金额
        'havemoney' => $havemoney, // 账户余额
        'userid' => $memid,
        'inball' => '', // 占位
    );

    $redisObj = new Ciredis();
    $redisObj->setOne($showVoucher,serialize($data));
    $redisObj->pushMessage('general_order_image',$showVoucher);

}

$aData[0]['caption'] = $caption;
$aData[0]['Order_Bet_success'] = $Order_Bet_success;
$aData[0]['order'] = $showVoucher;
$aData[0]['s_sleague'] = $s_sleague;
$aData[0]['btype'] = $btype ? $btype : '';
$aData[0]['M_Date'] = date('m-d', strtotime($row["M_Date"]));
$aData[0]['s_mb_team'] = $s_mb_team?$s_mb_team:'';
$aData[0]['Sign'] = $Sign?$Sign:'';
$aData[0]['s_tg_team'] = $s_tg_team?$s_tg_team:'';
$aData[0]['s_m_place'] = $s_m_place;
$aData[0]['w_m_rate'] = $w_m_rate;
$aData[0]['gold'] = $gold; // 交易金额
$aData[0]['order_bet_amount'] = $gwin; // 可赢金额
$aData[0]['havemoney'] = $havemoney; // 账户余额
$aData[0]['inball'] = ''; // 占位

$status = '200';
$describe = '投注成功';
original_phone_request_response($status, $describe, $aData);
