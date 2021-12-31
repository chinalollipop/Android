<?php
session_start();
require ("../include/config.inc.php");
require_once("../../../../common/sportCenterData.php");
include ("../include/address.mem.php");
require ("../include/define_function_list.inc.php");
require ("../include/traditional.zh-cn.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

ini_set('display_errors','OFF');

if( (!isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG) && $_SESSION['admin_level'] != 'D' ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$uid=$_REQUEST["uid"];
$langx=$_REQUEST["langx"];
$loginname=$_SESSION['UserName'];
$lv = isset($_POST['lv'])? $_POST['lv']:""; // 管理员层级

$datatime=date('Y-m-d H:i:s');
$date_day = date('Y-m-d'); // 今日

/*$sql = "select website,Admin_Url from " . DBPREFIX. "web_system_data where ID=1";
$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);
$admin_url=explode(";",$row['Admin_Url']);
if (in_array($_SERVER['HTTP_HOST'],array($admin_url[0],$admin_url[1],$admin_url[2],$admin_url[3]))){*/
if($_SESSION['Level'] == 'M') {
    $web= DBPREFIX .'web_system_data';
}else{
    $web= DBPREFIX .'web_agents_data';
}
$c_sql = "select Competence from ".DBPREFIX."web_system_data where UserName='$loginname'";
$c_result = mysqli_query($dbLink,$c_sql);
$c_row = mysqli_fetch_assoc($c_result);
$competence = $c_row['Competence']; // 权限控制
$c_num = explode(",",$competence);

$sql = "select UserName,EditType,Level from $web where Oid='$uid' and UserName='$loginname'";
$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);
$user=$row['UserName'];
$edittype=$row['EditType'];
$level=$row['Level'];
if ($row['Level']=='M'){
    $n_name="";//总监
}else if ($row['Level']=='A'){
    $n_name="and Super='$user'";//公司
}else if ($row['Level']=='B'){
    $n_name="and Corprator ='$user'";//股东
}else if ($row['Level']=='C'){
    $n_name="and World='$user'";//总代理
}else if ($row['Level']=='D'){
    $n_name="and Agents='$user'";//代理商
}

$id=$_REQUEST['id'];
$gid=$_REQUEST['gid'];
$key=$_REQUEST['key'];
$confirmed=$_REQUEST['confirmed'];
$seconds=$_REQUEST["seconds"];
$date_start=$_REQUEST['date_start'];
$date_end=$_REQUEST['date_end'];
$username=$_REQUEST['username'];
$page=$_REQUEST['page'];
$sort=$_REQUEST['sort'];
$ball=$_REQUEST['ball'];
$type=$_REQUEST['type'];
$result_type=$_REQUEST['result_type'];
$actionname= isset($_REQUEST['actionname'])?$_REQUEST['actionname']:'' ; // 注单处理名称
$oder_number= isset($_REQUEST['oder_number'])?$_REQUEST['oder_number']:'' ; // 用于插入系统日志，注单 单号
$betordernumber= isset($_REQUEST['betordernumber'])?$_REQUEST['betordernumber']:'' ; // 用于查询，注单 单号
$show_detail_money= isset($_REQUEST['show_detail_money'])?$_REQUEST['show_detail_money']:'' ; // 是否显示详细金额 1 ，0

if($betordernumber==''){ // 查询注单单号
    $order_sql = '';
}else{
    $order_sql = " orderNo = '$betordernumber' and ";
}
if ($seconds==''){
    $seconds=180;
}
if ($username==""){
    $name='';
}else{
    $name=" M_Name='$username' and ";
}
if ($date_start==""){
    $date_start=date("Y-m-d 00:00:00");
}
if ($date_end==""){
    // $date_end=date("Y-m-d H:i:s",time()+86400);
    $date_end=date("Y-m-d 23:59:59");
}
if ($page==''){
    $page=0;
}
if ($sort==''){
    $sort='BetTime';
}
if ($sort=='Cancel'){
    $cancel='and Cancel=1';
}else if ($sort=='Danger'){
    $cancel='and Danger=1';
}
if($ball==''){
    $match='';
}else{
    //$match="Active='$ball' and";
    $match=" and Gtype='$ball'";
}
if ($orderby==''){
    $orderby='desc';
}
if ($result_type==''){
    $result="";
}else if ($result_type=='Y'){
    $result="and M_Result!=''";
}else if ($result_type=='N'){
    $result="and M_Result=''";
}

switch ($type){
    case "CS":
        $wtype=" and LineType=16";
        if($ball=='FT'){ $match=" and Gtype='FS'"; }//and Ptype='FT'
        if($ball=='BK'){ $match=" and Gtype='FS'"; }//and Ptype='BK'
        $Content='冠军赛';
        break;
    case "ZHGG":
        $wtype=" and LineType=8";
        //if($ball=='FT'){ $match="Gtype='FS' and Ptype='FT' and"; }
        //if($ball=='BK'){ $match="Gtype='FS' and Ptype='BK' and"; }
        $Content='综合过关';
        break;
    case "DJ":
        if($ball=='FT' || $ball==''){ $match=" and Gtype='FT' and Middle like '%电竞足球%'"; $Content='电竞足球';}
        if($ball=='BK'){ $match=" and Gtype='BK' and Middle like '%NBA2%'"; $Content='电竞蓝球';}
        break;
    default:
        if ($type!=''){
            $wtype=" and LineType=$type";
        }else{
            $wtype="";
            $Content='全部';
        }
        break;
}
if($key=='modify'){ // 对调
    $mysql="select * from ".DBPREFIX."web_report_data where id='$id'";
    $result = mysqli_query($dbLink,$mysql);
    $row = mysqli_fetch_assoc($result);

    switch ($row['LineType']){
        case 2:
            switch ($row['OpenType']){
                case "A":
                    $rate=1.84-$row['M_Rate'];
                    break;
                case "B":
                    $rate=1.88-$row['M_Rate'];
                    break;
                case "C":
                    $rate=1.92-$row['M_Rate'];
                    break;
                case "D":
                    $rate=1.94-$row['M_Rate'];
                    break;
            }
            $rate=number_format($rate,3);
            $gwin=$row['BetScore']*$rate;
            $info   =explode("<br>",$row['Middle']);
            $info_tw=explode("<br>",$row['Middle_tw']);
            $info_en=explode("<br>",$row['Middle_en']);

            $sid=$info[1];
            $middle=$info[0].'<br>'.$sid.'<br>'.$info[2].'<br>';
            $middle_tw=$info_tw[0].'<br>'.$sid.'<br>'.$info_tw[2].'<br>';
            $middle_en=$info_en[0].'<br>'.$sid.'<br>'.$info_en[2].'<br>';
            $team=explode("&nbsp;&nbsp;",$info[2]);
            $team_tw=explode("&nbsp;&nbsp;",$info_tw[2]);
            $team_en=explode("&nbsp;&nbsp;",$info_en[2]);
            if ($row['ShowType']=='H'){
                $mb_team=$team[0];
                $tg_team=$team[2];
                $mb_team_tw=$team_tw[0];
                $tg_team_tw=$team_tw[2];
                $mb_team_en=$team_en[0];
                $tg_team_en=$team_en[2];
                if ($row['Mtype']=='RH'){
                    $mtype='RC';
                    $m_place=$tg_team;
                    $m_place_tw=$tg_team_tw;
                    $m_place_en=$tg_team_en;
                }else{
                    $mtype='RH';
                    $m_place=$mb_team;
                    $m_place_tw=$mb_team_tw;
                    $m_place_en=$mb_team_en;
                }
            }else{
                $mb_team=$team[0];
                $tg_team=$team[2];
                $mb_team_tw=$team_tw[0];
                $tg_team_tw=$team_tw[2];
                $mb_team_en=$team_en[0];
                $tg_team_en=$team_en[2];
                if ($row['Mtype']=='RH'){
                    $mtype='RC';
                    $m_place=$mb_team;
                    $m_place_tw=$mb_team_tw;
                    $m_place_en=$mb_team_en;
                }else{
                    $mtype='RH';
                    $m_place=$tg_team;
                    $m_place_tw=$tg_team_tw;
                    $m_place_en=$tg_team_en;
                }
            }
            if ($row['MB_MID']<300000){
                $lines2=$middle.'<FONT color=#cc0000>'.$m_place.'</FONT>&nbsp;@&nbsp;<FONT color=#cc0000><b>'.$rate.'</b></FONT>';
                $lines2_tw=$middle_tw.'<FONT color=#cc0000>'.$m_place_tw.'</FONT>&nbsp;@&nbsp;<FONT color=#cc0000><b>'.$rate.'</b></FONT>';
                $lines2_en=$middle_en.'<FONT color=#cc0000>'.$m_place_en.'</FONT>&nbsp;@&nbsp;<FONT color=#cc0000><b>'.$rate.'</b></FONT>';
            }else if($row['MB_MID']>800000 and $row['MB_MID']<900000){
                $lines2=$middle.'<FONT color=#cc0000>'.$m_place.'</font>&nbsp;-&nbsp;<font color=#666666>[上半]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
                $lines2_tw=$middle_tw.'<FONT color=#cc0000>'.$m_place_tw.'</font>&nbsp;-&nbsp;<font color=#666666>[上半]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
                $lines2_en=$middle_en.'<FONT color=#cc0000>'.$m_place_en.'</font>&nbsp;-&nbsp;<font color=#666666>[1st]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
            }else if($row['MB_MID']>900000 and $row['MB_MID']<1000000){
                $lines2=$middle.'<FONT color=#cc0000>'.$m_place.'</font>&nbsp;-&nbsp;<font color=#666666>[下半]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
                $lines2_tw=$middle_tw.'<FONT color=#cc0000>'.$m_place_tw.'</font>&nbsp;-&nbsp;<font color=#666666>[下半]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
                $lines2_en=$middle_en.'<FONT color=#cc0000>'.$m_place_en.'</font>&nbsp;-&nbsp;<font color=#666666>[2nd]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
            }else if($row['MB_MID']>300000 and $row['MB_MID']<400000){
                $lines2=$middle.'<FONT color=#cc0000>'.$m_place.'</font>&nbsp;-&nbsp;<font color=#666666>[第一节]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
                $lines2_tw=$middle_tw.'<FONT color=#cc0000>'.$m_place_tw.'</font>&nbsp;-&nbsp;<font color=#666666>[第一節]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
                $lines2_en=$middle_en.'<FONT color=#cc0000>'.$m_place_en.'</font>&nbsp;-&nbsp;<font color=#666666>[Q1]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
            }else if ($row['MB_MID']>400000 and $row['MB_MID']<500000){
                $lines2=$middle.'<FONT color=#cc0000>'.$m_place.'</font>&nbsp;-&nbsp;<font color=#666666>[第二节]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
                $lines2_tw=$middle_tw.'<FONT color=#cc0000>'.$m_place_tw.'</font>&nbsp;-&nbsp;<font color=#666666>[第二節]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
                $lines2_en=$middle_en.'<FONT color=#cc0000>'.$m_place_en.'</font>&nbsp;-&nbsp;<font color=#666666>[Q2]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
            }else if ($row['MB_MID']>500000 and $row['MB_MID']<600000){
                $lines2=$middle.'<FONT color=#cc0000>'.$m_place.'</font>&nbsp;-&nbsp;<font color=#666666>[第三节]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
                $lines2_tw=$middle_tw.'<FONT color=#cc0000>'.$m_place_tw.'</font>&nbsp;-&nbsp;<font color=#666666>[第三節]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
                $lines2_en=$middle_en.'<FONT color=#cc0000>'.$m_place_en.'</font>&nbsp;-&nbsp;<font color=#666666>[Q3]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
            }else if ($row['MB_MID']>600000 and $row['MB_MID']<700000){
                $lines2=$middle.'<FONT color=#cc0000>'.$m_place.'</font>&nbsp;-&nbsp;<font color=#666666>[第四节]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
                $lines2_tw=$middle_tw.'<FONT color=#cc0000>'.$m_place_tw.'</font>&nbsp;-&nbsp;<font color=#666666>[第四節]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
                $lines2_en=$middle_en.'<FONT color=#cc0000>'.$m_place_en.'</font>&nbsp;-&nbsp;<font color=#666666>[Q4]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
            }
            $mysql="update ".DBPREFIX."web_report_data set Mtype='$mtype',Middle='$lines2',Middle_tw='$lines2_tw',Middle_en='$lines2_en',M_Rate='$rate',Gwin='$gwin',VGOLD='',M_Result='',A_Result='',B_Result='',C_Result='',D_Result='',T_Result='',Edit=1,Cancel=0,Confirmed=0,Checked=0,updateTime='".date('Y-m-d H:i:s',time())."' where ID='$id'";
            break;
        case 3:
            switch ($row['OpenType']){
                case "A":
                    $rate=1.84-$row['M_Rate'];
                    break;
                case "B":
                    $rate=1.88-$row['M_Rate'];
                    break;
                case "C":
                    $rate=1.92-$row['M_Rate'];
                    break;
                case "D":
                    $rate=1.94-$row['M_Rate'];
                    break;
            }
            $rate=number_format($rate,3);
            $gwin=$row['BetScore']*$rate;
            $info   =explode("<br>",$row['Middle']);
            $info_tw=explode("<br>",$row['Middle_tw']);
            $info_en=explode("<br>",$row['Middle_en']);

            $sid=$info[1];
            $middle=$info[0].'<br>'.$sid.'<br>'.$info[2].'<br>';
            $middle_tw=$info_tw[0].'<br>'.$sid.'<br>'.$info_tw[2].'<br>';
            $middle_en=$info_en[0].'<br>'.$sid.'<br>'.$info_en[2].'<br>';

            $pan=substr($row['M_Place'],1,strlen($row['M_Place']));
            if ($row['Mtype']=='OUC'){
                $mtype='OUH';
                $m_place='大&nbsp;'.$pan;
                $m_place_tw='大&nbsp;'.$pan;
                $m_place_en='over'.$pan;
                $place='O'.$pan;
            }else{
                $mtype='OUC';
                $m_place='小&nbsp;'.$pan;
                $m_place_tw='小&nbsp;'.$pan;
                $m_place_en='under'.$pan;
                $place='U'.$pan;
            }
            if ($row['MB_MID']<300000){
                $lines2=$middle.'<FONT color=#cc0000>'.$m_place.'</FONT>&nbsp;@&nbsp;<FONT color=#cc0000><b>'.$rate.'</b></FONT>';
                $lines2_tw=$middle_tw.'<FONT color=#cc0000>'.$m_place_tw.'</FONT>&nbsp;@&nbsp;<FONT color=#cc0000><b>'.$rate.'</b></FONT>';
                $lines2_en=$middle_en.'<FONT color=#cc0000>'.$m_place_en.'</FONT>&nbsp;@&nbsp;<FONT color=#cc0000><b>'.$rate.'</b></FONT>';
            }else if($row['MB_MID']>800000 and $row['MB_MID']<900000){
                $lines2=$middle.'<FONT color=#cc0000>'.$m_place.'</font>&nbsp;-&nbsp;<font color=#666666>[上半]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
                $lines2_tw=$middle_tw.'<FONT color=#cc0000>'.$m_place_tw.'</font>&nbsp;-&nbsp;<font color=#666666>[上半]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
                $lines2_en=$middle_en.'<FONT color=#cc0000>'.$m_place_en.'</font>&nbsp;-&nbsp;<font color=#666666>[1st]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
            }else if($row['MB_MID']>900000 and $row['MB_MID']<1000000){
                $lines2=$middle.'<FONT color=#cc0000>'.$m_place.'</font>&nbsp;-&nbsp;<font color=#666666>[下半]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
                $lines2_tw=$middle_tw.'<FONT color=#cc0000>'.$m_place_tw.'</font>&nbsp;-&nbsp;<font color=#666666>[下半]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
                $lines2_en=$middle_en.'<FONT color=#cc0000>'.$m_place_en.'</font>&nbsp;-&nbsp;<font color=#666666>[2nd]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
            }else if($row['MB_MID']>300000 and $row['MB_MID']<400000){
                $lines2=$middle.'<FONT color=#cc0000>'.$m_place.'</font>&nbsp;-&nbsp;<font color=#666666>[第一节]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
                $lines2_tw=$middle_tw.'<FONT color=#cc0000>'.$m_place_tw.'</font>&nbsp;-&nbsp;<font color=#666666>[第一節]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
                $lines2_en=$middle_en.'<FONT color=#cc0000>'.$m_place_en.'</font>&nbsp;-&nbsp;<font color=#666666>[Q1]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
            }else if ($row['MB_MID']>400000 and $row['MB_MID']<500000){
                $lines2=$middle.'<FONT color=#cc0000>'.$m_place.'</font>&nbsp;-&nbsp;<font color=#666666>[第二节]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
                $lines2_tw=$middle_tw.'<FONT color=#cc0000>'.$m_place_tw.'</font>&nbsp;-&nbsp;<font color=#666666>[第二節]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
                $lines2_en=$middle_en.'<FONT color=#cc0000>'.$m_place_en.'</font>&nbsp;-&nbsp;<font color=#666666>[Q2]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
            }else if ($row['MB_MID']>500000 and $row['MB_MID']<600000){
                $lines2=$middle.'<FONT color=#cc0000>'.$m_place.'</font>&nbsp;-&nbsp;<font color=#666666>[第三节]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
                $lines2_tw=$middle_tw.'<FONT color=#cc0000>'.$m_place_tw.'</font>&nbsp;-&nbsp;<font color=#666666>[第三節]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
                $lines2_en=$middle_en.'<FONT color=#cc0000>'.$m_place_en.'</font>&nbsp;-&nbsp;<font color=#666666>[Q3]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
            }else if ($row['MB_MID']>600000 and $row['MB_MID']<700000){
                $lines2=$middle.'<FONT color=#cc0000>'.$m_place.'</font>&nbsp;-&nbsp;<font color=#666666>[第四节]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
                $lines2_tw=$middle_tw.'<FONT color=#cc0000>'.$m_place_tw.'</font>&nbsp;-&nbsp;<font color=#666666>[第四節]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
                $lines2_en=$middle_en.'<FONT color=#cc0000>'.$m_place_en.'</font>&nbsp;-&nbsp;<font color=#666666>[Q4]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
            }
            $mysql="update ".DBPREFIX."web_report_data set Mtype='$mtype',Middle='$lines2',Middle_tw='$lines2_tw',Middle_en='$lines2_en',M_Place='$place',M_Rate='$rate',Gwin='$gwin',VGOLD='',M_Result='',A_Result='',B_Result='',C_Result='',D_Result='',T_Result='',Edit=1,Cancel=0,Confirmed=0,Checked=0,updateTime='".date('Y-m-d H:i:s',time())."' where ID='$id'";
            break;
        case 12:
            switch ($row['OpenType']){
                case "A":
                    $rate=1.84-$row['M_Rate'];
                    break;
                case "B":
                    $rate=1.88-$row['M_Rate'];
                    break;
                case "C":
                    $rate=1.92-$row['M_Rate'];
                    break;
                case "D":
                    $rate=1.94-$row['M_Rate'];
                    break;
            }
            $rate=number_format($rate,3);

            $gwin=$row['BetScore']*$rate;
            $info   =explode("<br>",$row['Middle']);
            $info_tw=explode("<br>",$row['Middle_tw']);
            $info_en=explode("<br>",$row['Middle_en']);

            $sid=$info[1];
            $middle=$info[0].'<br>'.$sid.'<br>'.$info[2].'<br>';
            $middle_tw=$info_tw[0].'<br>'.$sid.'<br>'.$info_tw[2].'<br>';
            $middle_en=$info_en[0].'<br>'.$sid.'<br>'.$info_en[2].'<br>';
            $team=explode("&nbsp;&nbsp;",$info[2]);
            $team_tw=explode("&nbsp;&nbsp;",$info[2]);
            $team_en=explode("&nbsp;&nbsp;",$info[2]);

            if ($row['ShowType']=='H'){
                $mb_team=$team[0];
                $tg_team=$team[2];
                $mb_team_tw=$team_tw[0];
                $tg_team_tw=$team_tw[2];
                $mb_team_en=$team_en[0];
                $tg_team_en=$team_en[2];
                if ($row['Mtype']=='VRH'){
                    $mtype='VRC';
                    $m_place=$tg_team;
                    $m_place_tw=$tg_team_tw;
                    $m_place_en=$tg_team_en;
                }else{
                    $mtype='VRH';
                    $m_place=$mb_team;
                    $m_place_tw=$mb_team_tw;
                    $m_place_en=$mb_team_en;
                }
            }else{
                $mb_team=$team[0];
                $tg_team=$team[2];
                $mb_team_tw=$team_tw[0];
                $tg_team_tw=$team_tw[2];
                $mb_team_en=$team_en[0];
                $tg_team_en=$team_en[2];
                if ($row['Mtype']=='VRH'){
                    $mtype='VRC';
                    $m_place=$mb_team;
                    $m_place_tw=$mb_team_tw;
                    $m_place_en=$mb_team_en;
                }else{
                    $mtype='VRH';
                    $m_place=$tg_team;
                    $m_place_tw=$tg_team_tw;
                    $m_place_en=$tg_team_en;
                }
            }
            $lines2=$middle.'<FONT color=#cc0000>'.$m_place.'</font>&nbsp;-&nbsp;<font color=#666666>[上半]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
            $lines2_tw=$middle_tw.'<FONT color=#cc0000>'.$m_place_tw.'</font>&nbsp;-&nbsp;<font color=#666666>[上半]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
            $lines2_en=$middle_en.'<FONT color=#cc0000>'.$m_place_en.'</font>&nbsp;-&nbsp;<font color=#666666>[1st]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
            $mysql="update ".DBPREFIX."web_report_data set Mtype='$mtype',Middle='$lines2',Middle_tw='$lines2_tw',Middle_en='$lines2_en',M_Rate='$rate',Gwin='$gwin',VGOLD='',M_Result='',A_Result='',B_Result='',C_Result='',D_Result='',T_Result='',Edit=1,Checked=0,Cancel=0,Confirmed=0,updateTime='".date('Y-m-d H:i:s',time())."' where ID='$id'";
            break;
        case 13:
            switch ($row['OpenType']){
                case "A":
                    $rate=1.84-$row['M_Rate'];
                    break;
                case "B":
                    $rate=1.88-$row['M_Rate'];
                    break;
                case "C":
                    $rate=1.90-$row['M_Rate'];
                    break;
                case "D":
                    $rate=1.92-$row['M_Rate'];
                    break;
            }
            $rate=number_format($rate,3);
            $gwin=$row['BetScore']*$rate;
            $info   =explode("<br>",$row['Middle']);
            $info_tw=explode("<br>",$row['Middle_tw']);
            $info_en=explode("<br>",$row['Middle_en']);
            $sid=$info[1];
            $middle=$info[0].'<br>'.$sid.'<br>'.$info[2].'<br>';
            $middle_tw=$info_tw[0].'<br>'.$sid.'<br>'.$info_tw[2].'<br>';
            $middle_en=$info_en[0].'<br>'.$sid.'<br>'.$info_en[2].'<br>';
            $data=explode("</font>",$info[3]);
            $data_tw=explode("</font>",$info_tw[3]);
            $data_en=explode("</font>",$info_en[3]);
            $team=explode("&nbsp;&nbsp;",$info[2]);
            $team_tw=explode("&nbsp;&nbsp;",$info_tw[2]);
            $team_en=explode("&nbsp;&nbsp;",$info_en[2]);
            $pan=substr($row['M_Place'],1,strlen($row['M_Place']));
            if ($row['Mtype']=='VOUC'){
                $mtype='VOUH';
                $m_place='大&nbsp;'.$pan;
                $m_place_tw='大&nbsp;'.$pan;
                $m_place_en='over'.$pan;
                $place='O'.$pan;
            }else{
                $mtype='VOUC';
                $m_place='小&nbsp;'.$pan;
                $m_place_tw='小&nbsp;'.$pan;
                $m_place_en='under'.$pan;
                $place='U'.$pan;
            }
            $lines2=$middle.'<FONT color=#cc0000>'.$m_place.'</font>&nbsp;-&nbsp;<font color=#666666>[上半]</font>&nbsp;@&nbsp;<FONT color=#cc0000><b>'.$rate.'</b></FONT>';
            $lines2_tw=$middle_tw.'<FONT color=#cc0000>'.$m_place_tw.'&nbsp;-&nbsp;<font color=#666666>[上半]</font>&nbsp;@&nbsp;<FONT color=#cc0000><b>'.$rate.'</b></FONT>';
            $lines2_en=$middle_en.'<FONT color=#cc0000>'.$m_place_en.'&nbsp;-&nbsp;<font color=#666666>[1st]</font>&nbsp;@&nbsp;<FONT color=#cc0000><b>'.$rate.'</b></FONT>';
            $mysql="update ".DBPREFIX."web_report_data set Middle='$lines2',Middle_tw='$lines2_tw',Middle_en='$lines2_en',M_Place='$place',M_Rate='$rate',VGOLD='',M_Result='',A_Result='',B_Result='',C_Result='',D_Result='',T_Result='',Edit=1,Confirmed=0,Cancel=0,Gwin='$gwin',Mtype='$mtype',updateTime='".date('Y-m-d H:i:s',time())."' where ID='$id'";
            break;
        case 9:
            switch ($row['OpenType']){
                case "A":
                    $rate=1.84-$row['M_Rate'];
                    break;
                case "B":
                    $rate=1.88-$row['M_Rate'];
                    break;
                case "C":
                    $rate=1.92-$row['M_Rate'];
                    break;
                case "D":
                    $rate=1.94-$row['M_Rate'];
                    break;
            }
            $rate=number_format($rate,3);
            $gwin=$row['BetScore']*$rate;
            $info   =explode("<br>",$row['Middle']);
            $info_tw=explode("<br>",$row['Middle_tw']);
            $info_en=explode("<br>",$row['Middle_en']);
            $sid=$info[1];
            $middle=$info[0].'<br>'.$sid.'<br>'.$info[2].'<br>';
            $middle_tw=$info_tw[0].'<br>'.$sid.'<br>'.$info_tw[2].'<br>';
            $middle_en=$info_en[0].'<br>'.$sid.'<br>'.$info_en[2].'<br>';
            $team=explode("&nbsp;&nbsp;",$info[2]);
            $team_tw=explode("&nbsp;&nbsp;",$info[2]);
            $team_en=explode("&nbsp;&nbsp;",$info[2]);

            if ($row['ShowType']=='H'){
                $mb_team=$team[0];
                $tg_team=$team[2];
                $mb_team_tw=$team_tw[0];
                $tg_team_tw=$team_tw[2];
                $mb_team_en=$team_en[0];
                $tg_team_en=$team_en[2];
                if ($row['Mtype']=='RRH'){
                    $otype='RRC';
                    $m_place=$tg_team;
                    $m_place_tw=$tg_team_tw;
                    $m_place_en=$tg_team_en;
                }else{
                    $otype='RRH';
                    $m_place=$mb_team;
                    $m_place_tw=$mb_team_tw;
                    $m_place_en=$mb_team_en;
                }
            }else{
                $mb_team=$team[0];
                $tg_team=$team[2];
                $mb_team_tw=$team_tw[0];
                $tg_team_tw=$team_tw[2];
                $mb_team_en=$team_en[0];
                $tg_team_en=$team_en[2];
                if ($row['Mtype']=='RRH'){
                    $otype='RRC';
                    $m_place=$mb_team;
                    $m_place_tw=$mb_team_tw;
                    $m_place_en=$mb_team_en;
                }else{
                    $otype='RRH';
                    $m_place=$tg_team;
                    $m_place_tw=$tg_team_tw;
                    $m_place_en=$tg_team_en;
                }
            }
            if ($row['MB_MID']<300000){
                $lines2=$middle.'<FONT color=#cc0000>'.$m_place.'</FONT>&nbsp;@&nbsp;<FONT color=#cc0000><b>'.$rate.'</b></FONT>';
                $lines2_tw=$middle_tw.'<FONT color=#cc0000>'.$m_place_tw.'</FONT>&nbsp;@&nbsp;<FONT color=#cc0000><b>'.$rate.'</b></FONT>';
                $lines2_en=$middle_en.'<FONT color=#cc0000>'.$m_place_en.'</FONT>&nbsp;@&nbsp;<FONT color=#cc0000><b>'.$rate.'</b></FONT>';
            }else if($row['MB_MID']>800000 and $row['MB_MID']<900000){
                $lines2=$middle.'<FONT color=#cc0000>'.$m_place.'</font>&nbsp;-&nbsp;<font color=#666666>[上半]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
                $lines2_tw=$middle_tw.'<FONT color=#cc0000>'.$m_place_tw.'</font>&nbsp;-&nbsp;<font color=#666666>[上半]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
                $lines2_en=$middle_en.'<FONT color=#cc0000>'.$m_place_en.'</font>&nbsp;-&nbsp;<font color=#666666>[1st]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
            }else if($row['MB_MID']>900000 and $row['MB_MID']<1000000){
                $lines2=$middle.'<FONT color=#cc0000>'.$m_place.'</font>&nbsp;-&nbsp;<font color=#666666>[下半]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
                $lines2_tw=$middle_tw.'<FONT color=#cc0000>'.$m_place_tw.'</font>&nbsp;-&nbsp;<font color=#666666>[下半]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
                $lines2_en=$middle_en.'<FONT color=#cc0000>'.$m_place_en.'</font>&nbsp;-&nbsp;<font color=#666666>[2nd]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
            }
            $mysql="update ".DBPREFIX."web_report_data set Mtype='$otype',Middle='$lines2',Middle_tw='$lines2_tw',Middle_en='$lines2_en',M_Rate='$rate',Gwin='$gwin',vgold='',M_Result='',A_Result='',B_Result='',C_Result='',D_Result='',T_Result='',Edit=1,Confirmed=0,Cancel=0,Checked=0,updateTime='".date('Y-m-d H:i:s',time())."' where ID='$id'";
            break;
        case 19:
            switch ($row['OpenType']){
                case "A":
                    $rate=1.84-$row['M_Rate'];
                    break;
                case "B":
                    $rate=1.88-$row['M_Rate'];
                    break;
                case "C":
                    $rate=1.92-$row['M_Rate'];
                    break;
                case "D":
                    $rate=1.94-$row['M_Rate'];
                    break;
            }
            $rate=number_format($rate,3);
            $gwin=$row['BetScore']*$rate;
            $info   =explode("<br>",$row['Middle']);
            $info_tw=explode("<br>",$row['Middle_tw']);
            $info_en=explode("<br>",$row['Middle_en']);
            $sid=$info[1];
            $middle=$info[0].'<br>'.$sid.'<br>'.$info[2].'<br>';
            $middle_tw=$info_tw[0].'<br>'.$sid.'<br>'.$info_tw[2].'<br>';
            $middle_en=$info_en[0].'<br>'.$sid.'<br>'.$info_en[2].'<br>';
            $team=explode("&nbsp;&nbsp;",$info[2]);
            $team_tw=explode("&nbsp;&nbsp;",$info_tw[2]);
            $team_en=explode("&nbsp;&nbsp;",$info_en[2]);

            if($row['Active']!=3){
                if ($row['ShowType']=='H'){
                    $mb_team=$team[0];
                    $tg_team=$team[2];
                    $mb_team_tw=$team_tw[0];
                    $tg_team_tw=$team_tw[2];
                    $mb_team_en=$team_en[0];
                    $tg_team_en=$team_en[2];
                    if ($row['Mtype']=='VRRH'){
                        $otype='VRRC';
                        $m_place=$tg_team;
                        $m_place_tw=$tg_team_tw;
                        $m_place_en=$tg_team_en;
                    }else{
                        $otype='VRRH';
                        $m_place=$mb_team;
                        $m_place_tw=$mb_team_tw;
                        $m_place_en=$mb_team_en;
                    }
                }else{
                    $mb_team=$team[0];
                    $tg_team=$team[2];
                    $mb_team_tw=$team_tw[0];
                    $tg_team_tw=$team_tw[2];
                    $mb_team_en=$team_en[0];
                    $tg_team_en=$team_en[2];
                    if ($row['Mtype']=='VRRH'){
                        $otype='VRRC';
                        $m_place=$mb_team;
                        $m_place_tw=$mb_team_tw;
                        $m_place_en=$mb_team_en;
                    }else{
                        $otype='VRRH';
                        $m_place=$tg_team;
                        $m_place_tw=$tg_team_tw;
                        $m_place_en=$tg_team_en;
                    }
                }
            }else{
                if ($row['ShowType']=='H'){
                    $mb_team=$team[0];
                    $tg_team=$team[2];
                    $mb_team_tw=$team_tw[0];
                    $tg_team_tw=$team_tw[2];
                    $mb_team_en=$team_en[0];
                    $tg_team_en=$team_en[2];
                    if ($row['Mtype']=='RRH'){
                        $otype='RRC';
                        $m_place=$tg_team;
                        $m_place_tw=$tg_team_tw;
                        $m_place_en=$tg_team_en;
                    }else{
                        $otype='RRH';
                        $m_place=$mb_team;
                        $m_place_tw=$mb_team_tw;
                        $m_place_en=$mb_team_en;
                    }
                }else{
                    $mb_team=$team[0];
                    $tg_team=$team[2];
                    $mb_team_tw=$team_tw[0];
                    $tg_team_tw=$team_tw[2];
                    $mb_team_en=$team_en[0];
                    $tg_team_en=$team_en[2];
                    if ($row['Mtype']=='RRH'){
                        $otype='RRC';
                        $m_place=$mb_team;
                        $m_place_tw=$mb_team_tw;
                        $m_place_en=$mb_team_en;
                    }else{
                        $otype='RRH';
                        $m_place=$tg_team;
                        $m_place_tw=$tg_team_tw;
                        $m_place_en=$tg_team_en;
                    }
                }
            }
            $lines2=$middle.'<FONT color=#cc0000>'.$m_place.'</font>&nbsp;-&nbsp;<font color=#666666>[上半]</font>&nbsp;@&nbsp;<FONT color=#cc0000><b>'.$rate.'</b></FONT>';
            $lines2_tw=$middle_tw.'<FONT color=#cc0000>'.$m_place_tw.'</font>&nbsp;-&nbsp;<font color=#666666>[上半]</font>&nbsp;@&nbsp;<FONT color=#cc0000><b>'.$rate.'</b></FONT>';
            $lines2_en=$middle_en.'<FONT color=#cc0000>'.$m_place_en.'</font>&nbsp;-&nbsp;<font color=gray>-[1st]</font>&nbsp;@&nbsp;<FONT color=#cc0000><b>'.$rate.'</b></FONT>';
            $mysql="update ".DBPREFIX."web_report_data set Mtype='$otype',Middle='$lines2',Middle_tw='$lines2_tw',Middle_en='$lines2_en',M_Rate='$rate',Gwin='$gwin',vgold='',M_Result='',A_Result='',B_Result='',C_Result='',D_Result='',T_Result='',Edit=1,Confirmed=0,Cancel=0,Checked=0,updateTime='".date('Y-m-d H:i:s',time())."' where ID='$id'";
            break;
        case 10:
            switch ($row['OpenType']){
                case "A":
                    $rate=1.84-$row['M_Rate'];
                    break;
                case "B":
                    $rate=1.88-$row['M_Rate'];
                    break;
                case "C":
                    $rate=1.90-$row['M_Rate'];
                    break;
                case "D":
                    $rate=1.92-$row['M_Rate'];
                    break;
            }
            $rate=number_format($rate,3);
            $gwin=$row['BetScore']*$rate;
            $info   =explode("<br>",$row['Middle']);
            $info_tw=explode("<br>",$row['Middle_tw']);
            $info_en=explode("<br>",$row['Middle_en']);
            $sid=$info[1];
            $middle=$info[0].'<br>'.$sid.'<br>'.$info[2].'<br>';
            $middle_tw=$info_tw[0].'<br>'.$sid.'<br>'.$info_tw[2].'<br>';
            $middle_en=$info_en[0].'<br>'.$sid.'<br>'.$info_en[2].'<br>';

            $pan=substr($row['M_Place'],1,strlen($row['M_Place']));
            if ($row['Mtype']=='ROUC'){
                $mtype='ROUH';
                $m_place='大&nbsp;'.$pan;
                $m_place_tw='大&nbsp;'.$pan;
                $m_place_en='over'.$pan;
                $place='O'.$pan;
            }else{
                $mtype='ROUC';
                $m_place='小&nbsp;'.$pan;
                $m_place_tw='小&nbsp;'.$pan;
                $m_place_en='under'.$pan;
                $place='U'.$pan;
            }
            if ($row['MB_MID']<300000){
                $lines2=$middle.'<FONT color=#cc0000>'.$m_place.'</FONT>&nbsp;@&nbsp;<FONT color=#cc0000><b>'.$rate.'</b></FONT>';
                $lines2_tw=$middle_tw.'<FONT color=#cc0000>'.$m_place_tw.'</FONT>&nbsp;@&nbsp;<FONT color=#cc0000><b>'.$rate.'</b></FONT>';
                $lines2_en=$middle_en.'<FONT color=#cc0000>'.$m_place_en.'</FONT>&nbsp;@&nbsp;<FONT color=#cc0000><b>'.$rate.'</b></FONT>';
            }else if($row['MB_MID']>800000 and $row['MB_MID']<900000){
                $lines2=$middle.'<FONT color=#cc0000>'.$m_place.'</font>&nbsp;-&nbsp;<font color=#666666>[上半]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
                $lines2_tw=$middle_tw.'<FONT color=#cc0000>'.$m_place_tw.'</font>&nbsp;-&nbsp;<font color=#666666>[上半]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
                $lines2_en=$middle_en.'<FONT color=#cc0000>'.$m_place_en.'</font>&nbsp;-&nbsp;<font color=#666666>[1st]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
            }else if($row['MB_MID']>900000 and $row['MB_MID']<1000000){
                $lines2=$middle.'<FONT color=#cc0000>'.$m_place.'</font>&nbsp;-&nbsp;<font color=#666666>[下半]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
                $lines2_tw=$middle_tw.'<FONT color=#cc0000>'.$m_place_tw.'</font>&nbsp;-&nbsp;<font color=#666666>[下半]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
                $lines2_en=$middle_en.'<FONT color=#cc0000>'.$m_place_en.'</font>&nbsp;-&nbsp;<font color=#666666>[2nd]</font>&nbsp;@&nbsp;<FONT color=#CC0000><b>'.$rate.'</b></FONT>';
            }
            $mysql="update ".DBPREFIX."web_report_data set Mtype='$mtype',Middle='$lines2',Middle_tw='$lines2_tw',Middle_en='$lines2_en',M_Place='$place',M_Rate='$rate',Gwin='$gwin',VGOLD='',M_Result='',A_Result='',B_Result='',C_Result='',D_Result='',T_Result='',Edit=1,Cancel=0,Confirmed=0,Checked=0,updateTime='".date('Y-m-d H:i:s',time())."' where ID='$id'";
            break;
        case 20:
            switch ($row['OpenType']){
                case "A":
                    $rate=1.84-$row['M_Rate'];
                    break;
                case "B":
                    $rate=1.88-$row['M_Rate'];
                    break;
                case "C":
                    $rate=1.90-$row['M_Rate'];
                    break;
                case "D":
                    $rate=1.92-$row['M_Rate'];
                    break;
            }
            $rate=number_format($rate,3);
            $gwin=$row['BetScore']*$rate;
            $info   =explode("<br>",$row['Middle']);
            $info_tw=explode("<br>",$row['Middle_tw']);
            $info_en=explode("<br>",$row['Middle_en']);
            $sid=$info[1];
            $middle=$info[0].'<br>'.$sid.'<br>'.$info[2].'<br>';
            $middle_tw=$info_tw[0].'<br>'.$sid.'<br>'.$info_tw[2].'<br>';
            $middle_en=$info_en[0].'<br>'.$sid.'<br>'.$info_en[2].'<br>';

            $pan=substr($row['M_Place'],1,strlen($row['M_Place']));
            if($row['Active']!=3){
                if ($row['Mtype']=='VROUC'){
                    $mtype='VROUH';
                    $m_place='大&nbsp;'.$pan;
                    $m_place_tw='大&nbsp;'.$pan;
                    $m_place_en='over'.$pan;
                    $place='O'.$pan;
                }else{
                    $mtype='VROUC';
                    $m_place='小&nbsp;'.$pan;
                    $m_place_tw='小&nbsp;'.$pan;
                    $m_place_en='under'.$pan;
                    $place='U'.$pan;
                }
            }else{
                if ($row['Mtype']=='ROUC'){
                    $mtype='ROUH';
                    $m_place='大&nbsp;'.$pan;
                    $m_place_tw='大&nbsp;'.$pan;
                    $m_place_en='over'.$pan;
                    $place='O'.$pan;
                }else{
                    $mtype='ROUC';
                    $m_place='小&nbsp;'.$pan;
                    $m_place_tw='小&nbsp;'.$pan;
                    $m_place_en='under'.$pan;
                    $place='U'.$pan;
                }
            }
            $lines2=$middle.'<FONT color=#cc0000>'.$m_place.'</font>&nbsp;-&nbsp;<font color=#666666>[上半]</font>&nbsp;@&nbsp;<FONT color=#cc0000><b>'.$rate.'</b></FONT>';
            $lines2_tw=$middle_tw.'<FONT color=#cc0000>'.$m_place_tw.'</font>&nbsp;-&nbsp;<font color=#666666>[上半]</font>&nbsp;@&nbsp;<FONT color=#cc0000><b>'.$rate.'</b></FONT>';
            $lines2_en=$middle_en.'<FONT color=#cc0000>'.$m_place_en.'</font>&nbsp;-&nbsp;<font color=gray>-[1st]</font>&nbsp;@&nbsp;<FONT color=#cc0000><b>'.$rate.'</b></FONT>';
            $mysql="update ".DBPREFIX."web_report_data set Mtype='$mtype',Middle='$lines2',Middle_tw='$lines2_tw',Middle_en='$lines2_en',M_Place='$place',M_Rate='$rate',Gwin='$gwin',VGOLD='',M_Result='',A_Result='',B_Result='',C_Result='',D_Result='',T_Result='',Edit=1,Cancel=0,Confirmed=0,Checked=0,updateTime='".date('Y-m-d H:i:s',time())."' where ID='$id'";
            break;

    }
    file_put_contents("edit.txt",$mysql.'/'.'['.$row['M_Name'].']'.'/'.$datatime,FILE_APPEND);
    mysqli_query($dbMasterLink,$mysql);
    $sql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set score=0 where mid=".$gid;
    mysqli_query($dbMasterLink,$sql);
    echo "<script languag='JavaScript'>self.location='query.php?uid=$uid&langx=$langx&&seconds=$seconds&username=$username&date_start=$date_start&date_end=$date_end&page=$page&sort=$sort&ball=$ball&type=$type'</script>";
}

//取消注单
if($key=='cancel'){
    $beginFrom = mysqli_query($dbMasterLink,"start transaction");	//开启事务$from
    if($beginFrom){
        $rsql = "select userid,M_Name,Pay_Type,BetScore,M_Result,Cancel from ".DBPREFIX."web_report_data where id=$id and mid='$gid' and Pay_Type=1 for update";
        $rresult = mysqli_query($dbMasterLink,$rsql);
        $rrow = mysqli_fetch_assoc($rresult);
        if($rrow['Cancel']==0){
            $userid=$rrow['userid'];
            $username=$rrow['M_Name'];
            $betscore=$rrow['BetScore'];
            $m_result=$rrow['M_Result'];
            $resultMem = mysqli_query($dbMasterLink,"select ID,UserName,test_flag,Money from  ".DBPREFIX.MEMBERTABLE." where ID=$userid for update");
            if($resultMem){
                $rowMem = mysqli_fetch_assoc($resultMem);
                $descCancel='Score'.$confirmed*-1;
                if($m_result==''){//未结算
                    $moneyLog=$betscore;
                    $moneyDesLog=$$descCancel.'：退回用户投注金额';
                    $u_sql = "update ".DBPREFIX.MEMBERTABLE." set Money=Money+$betscore where ID='$userid' and Pay_Type=1";
                }else{//已结算

                    if (intval($rowMem['Money']) < intval($m_result)){
                        mysqli_query($dbMasterLink,"ROLLBACK");
                        echo "<script>alert('会员资金不足，取消注单失败！');</script>";
                        echo "<script languag='JavaScript'>self.location='query.php?uid=$uid&langx=$langx&&seconds=$seconds&username=$username&date_start=$date_start&date_end=$date_end&page=$page&sort=$sort&ball=$ball&type=$type'</script>";
                        exit;
                    }

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
                    $u_sql = "update ".DBPREFIX.MEMBERTABLE." set Money=Money-$m_result where ID='$userid' and Pay_Type=1";
                }
                $updateRes = mysqli_query($dbMasterLink,$u_sql);
                if($updateRes){
                    $sql="update ".DBPREFIX."web_report_data set VGOLD=0,M_Result=0,A_Result=0,B_Result=0,C_Result=0,D_Result=0,T_Result=0,Cancel=1,Confirmed='$confirmed',Danger=0,Checked=1,updateTime='".date('Y-m-d H:i:s',time())."' where id='$id'";
                    if(mysqli_query($dbMasterLink,$sql)){
                        $moneyDesLog="[查询注单]".$moneyDesLog.",操作人:{$loginname}";
                        $moneyLogRes=addAccountRecords(array($userid,$username,$rowMem['test_flag'],$rowMem['Money'],$moneyLog,$rowMem['Money']+$moneyLog,2,6,$id,$moneyDesLog));
                        /* 插入系统日志 */
                        if($moneyLogRes){
                            mysqli_query($dbMasterLink,"COMMIT");
                            $loginfo = $loginname.' 对会员帐号 <font class="green">'.$username.'</font> 注单进行了 <font class="red">'.$actionname.'</font>操作,id 为 <font class="red">'.$id.'</font>,gid 为 <font class="red">'.$gid.'</font>,单号为 <font class="blue">'.$oder_number.'</font>' ;
                            innsertSystemLog($loginname,$lv,$loginfo);
                        }else{
                            mysqli_query($dbMasterLink,"ROLLBACK");
                            echo "<script>alert('用户资金账变添加失败！');</script>";
                        }
                    }else{
                        mysqli_query($dbMasterLink,"ROLLBACK");
                        echo "<script>alert('订单更新失败！');</script>";
                    }
                }else{
                    mysqli_query($dbMasterLink,"ROLLBACK");
                    echo "<script>alert('用户资金账户更新失败！');</script>";
                }
            }else{
                mysqli_query($dbMasterLink,"ROLLBACK");
                echo "<script>alert('用户资金锁定失败！');</script>";
            }
        }else{
            mysqli_query($dbMasterLink,"ROLLBACK");
            echo "<script>alert('订单已被取消,不能重复操作！');</script>";
        }
    }else{
        mysqli_query($dbMasterLink,"ROLLBACK");
        echo "<script>alert('事务开启失败！');</script>";
    }
    echo "<script languag='JavaScript'>self.location='query.php?uid=$uid&langx=$langx&&seconds=$seconds&username=$username&date_start=$date_start&date_end=$date_end&page=$page&sort=$sort&ball=$ball&type=$type'</script>";
}

//恢复注单 注单确认
if ($key=='resume'){
    $beginFrom = mysqli_query($dbMasterLink,"start transaction");	//开启事务$from
    if($beginFrom){
        $rsql = "select userid,M_Name,Pay_Type,BetScore,M_Result,Checked,Cancel from ".DBPREFIX."web_report_data where ID=$id and Pay_Type=1 for update";
        $rresult = mysqli_query($dbMasterLink,$rsql);
        if($rresult){
            $rrow = mysqli_fetch_array($rresult);
            $userid=$rrow['userid'];
            $username=$rrow['M_Name'];
            $betscore=$rrow['BetScore'];
            $m_result=$rrow['M_Result'];
            if($rrow['Checked']==1){
                $resultMem = mysqli_query($dbMasterLink,"select ID,UserName,test_flag,Money from  ".DBPREFIX.MEMBERTABLE." where ID=$userid for update");
                $rowMem = mysqli_fetch_assoc($resultMem);
                if($resultMem){
                    $cash=$betscore+$m_result;
                    if($cash>0 && $rowMem['Money'] < $cash){
                        mysqli_query($dbMasterLink,"ROLLBACK");
                        echo "<script>alert('用户资金不足,恢复订单失败！');</script>";
                        echo "<script languag='JavaScript'>self.location='query.php?uid=$uid&langx=$langx&&seconds=$seconds&username=$username&date_start=$date_start&date_end=$date_end&page=$page&sort=$sort&ball=$ball&type=$type'</script>";
                        exit;
                    }
                    if(mysqli_query($dbMasterLink,"update ".DBPREFIX.MEMBERTABLE." SET Money=Money-$cash where ID='$userid' and Pay_Type=1")){
                        $moneyLogRes=addAccountRecords(array($userid,$username,$rowMem['test_flag'],$rowMem['Money'],$cash*-1,$rowMem['Money']-$cash,5,6,$id,"[查询注单],操作人:{$loginname}"));
                        if($moneyLogRes){
                            $sql="update ".DBPREFIX."web_report_data set VGOLD='',M_Result='',A_Result='',B_Result='',C_Result='',D_Result='',T_Result='',Cancel=0,Confirmed=0,Danger=0,Checked=0,updateTime='".date('Y-m-d H:i:s',time())."' where id='$id'";
                            if(mysqli_query($dbMasterLink,$sql)){
                                mysqli_query($dbMasterLink,"COMMIT");
                                /* 插入系统日志 */
                                $loginfo = $loginname.' 对会员帐号 <font class="green">'.$username.'</font> 注单进行了 <font class="red">'.$actionname.'</font>操作,id 为 <font class="red">'.$id.'</font>,gid 为 <font class="red">'.$gid.'</font> ,单号为 <font class="blue">'.$oder_number.'</font>' ;
                                innsertSystemLog($loginname,$lv,$loginfo);
                            }else{
                                mysqli_query($dbMasterLink,"ROLLBACK");
                                echo "<script>alert('订单状态更新失败！');</script>";
                            }
                        }else{
                            mysqli_query($dbMasterLink,"ROLLBACK");
                            echo "<script>alert('用户资金账变添加失败！');</script>";
                        }
                    }else{
                        mysqli_query($dbMasterLink,"ROLLBACK");
                        echo "<script>alert('用户资金账户更新失败！');</script>";
                    }
                }else{
                    mysqli_query($dbMasterLink,"ROLLBACK");
                    echo "<script>alert('用户资金锁定失败！');</script>";
                }
            }else{
                $sql="update ".DBPREFIX."web_report_data set VGOLD='',M_Result='',A_Result='',B_Result='',C_Result='',D_Result='',T_Result='',Cancel=0,Confirmed=0,Danger=0,Checked=0,updateTime='".date('Y-m-d H:i:s',time())."' where id='$id'";
                if(mysqli_query($dbMasterLink,$sql)){
                    mysqli_query($dbMasterLink,"COMMIT");
                    /* 插入系统日志 */
                    $loginfo = $loginname.' 对会员帐号 <font class="green">'.$username.'</font> 注单进行了 <font class="red">'.$actionname.'</font>操作,id 为 <font class="red">'.$id.'</font>,gid 为 <font class="red">'.$gid.'</font> ,单号为 <font class="blue">'.$oder_number.'</font>' ;
                    innsertSystemLog($loginname,$lv,$loginfo);
                }else{
                    mysqli_query($dbMasterLink,"ROLLBACK");
                    echo "<script>alert('订单状态更新失败！');</script>";
                }
            }
        }else{
            mysqli_query($dbMasterLink,"ROLLBACK");
            echo "<script>alert('订单锁定失败！');</script>";
        }
    }else{
        mysqli_query($dbMasterLink,"ROLLBACK");
        echo "<script>alert('事务开启失败！');</script>";
    }
    echo "<script languag='JavaScript'>self.location='query.php?uid=$uid&langx=$langx&&seconds=$seconds&username=$username&date_start=$date_start&date_end=$date_end&page=$page&sort=$sort&ball=$ball&type=$type'</script>";
}


if($key=='sendPrizeFS'){//冠军单注结算
    $row=array();
    $mysql="select ID,Active,userid,M_Name,LineType,OpenType,BetTime,OddsType,ShowType,Mtype,Gwin,BetType,M_Place,M_Rate,$middle as Middle,BetScore,A_Point,B_Point,C_Point,D_Point,Pay_Type,Checked from ".DBPREFIX."web_report_data where id=".$_REQUEST['id']." and Cancel!=1 and Checked=0";
    $result = mysqli_query($dbLink,$mysql);
    $row = mysqli_fetch_assoc($result);
    $moneyLogDesc='';
    $mtype=$row['Mtype'];
    $id=$row['ID'];
    $userid=$row['userid'];
    $user=$row['M_Name'];
    if($row['M_Rate']<0){
        $num=str_replace("-","",$row['M_Rate']);
    }else if ($row['M_Rate']>0){
        $num=1;
    }
    $mysqlWin="SELECT win from `".DBPREFIX.SPORT_FLUSH_FS_MATCH_TABLE."` where MID=".$_REQUEST['gid']." and Gid='$mtype' and Score=1";
    $resultWin=mysqli_query($dbLink,$mysqlWin);
    $rowWin = mysqli_fetch_assoc($resultWin);
    if($rowWin['win']==1){//赢
        $graded=1;
        $g_res=$row['Gwin'];
    }else{//输
        $graded=-1;
        $g_res=-$row['BetScore']*$num;
    }

    $vgold=abs($graded)*$row['BetScore'];
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

    if(mysqli_query($dbMasterLink, "START TRANSACTION")){
        $sql_for_update = "select checked from ".DBPREFIX."web_report_data where ID='" . $row['ID'] ."' for update ";
        $query=mysqli_query($dbMasterLink,$sql_for_update);
        $bill_count_flag=mysqli_fetch_array($query);
        //订单已结算
        if( $bill_count_flag['checked'] == 1 ) {
            mysqli_query($dbMasterLink, "ROLLBACK");
            echo "<script>alert('订单已结算,事务回滚!');</script>";
            echo "<script languag='JavaScript'>self.location='query.php?uid=$uid&langx=$langx&&seconds=$seconds&username=$username&date_start=$date_start&date_end=$date_end&page=$page&sort=$sort&ball=$ball&type=$type'</script>";
            exit;
        }

        $userMoneyLock = mysqli_query($dbMasterLink,"select Money,test_flag from ".DBPREFIX.MEMBERTABLE." where ID=$userid for update");
        if(!$userMoneyLock){
            mysqli_query($dbMasterLink, "ROLLBACK");
            echo "<script>alert('用户资金锁添加失败!');</script>";
            echo "<script languag='JavaScript'>self.location='query.php?uid=$uid&langx=$langx&&seconds=$seconds&username=$username&date_start=$date_start&date_end=$date_end&page=$page&sort=$sort&ball=$ball&type=$type'</script>";
            exit;
        }
        $sendAwardTime=date('Y-m-d H:i:s',time());
        if($mb_in_score<0 and $mb_in_score_v<0){
            $cash=$row['BetScore'];
        }else{
            $cash=$row['BetScore']+$members;
        }

        $mysql="update ".DBPREFIX.MEMBERTABLE." set Money=Money+$cash where ID=$userid";
        if(!mysqli_query($dbMasterLink,$mysql)){
            mysqli_query($dbMasterLink, "ROLLBACK");
            echo "<script>alert('冠军派奖更新用户金额失败!');</script>";
            echo "<script languag='JavaScript'>self.location='query.php?uid=$uid&langx=$langx&&seconds=$seconds&username=$username&date_start=$date_start&date_end=$date_end&page=$page&sort=$sort&ball=$ball&type=$type'</script>";
            exit;
        }

        //生成资金账变记录
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

        $moneyLogDesc.=",冠军人工结算,操作人:".$_SESSION['UserName'];
        //添加用户资金账变记录
        $userMoneyRow=mysqli_fetch_array($userMoneyLock);
        $moneyLogRes=addAccountRecords(array($userid,$user,$userMoneyRow['test_flag'],$userMoneyRow['Money'],$cash,$userMoneyRow['Money']+$cash,3,6,$id,$moneyLogDesc));
        if(!$moneyLogRes){
            mysqli_query($dbMasterLink, "ROLLBACK");
            echo "<script>alert('用户资金账变日志写入失败!');</script>";
            echo "<script languag='JavaScript'>self.location='query.php?uid=$uid&langx=$langx&&seconds=$seconds&username=$username&date_start=$date_start&date_end=$date_end&page=$page&sort=$sort&ball=$ball&type=$type'</script>";
            exit;
        }

        $sql="update ".DBPREFIX."web_report_data set VGOLD='$vgold',M_Result='$members',D_Result='$agents',C_Result='$world',B_Result='$corprator',A_Result='$super',T_Result='$agent',sendAwardTime='$sendAwardTime',sendAwardIsAuto=2,sendAwardName='".$_SESSION['UserName']."',Checked=1,updateTime='".date('Y-m-d H:i:s',time())."' where ID='$id'";
        if(mysqli_query($dbMasterLink,$sql)){
            mysqli_query($dbMasterLink, "COMMIT");
        }else{
            mysqli_query($dbMasterLink, "ROLLBACK");
            echo "<script>alert('派奖更新用户注单表失败!');</script>";
            echo "<script languag='JavaScript'>self.location='query.php?uid=$uid&langx=$langx&&seconds=$seconds&username=$username&date_start=$date_start&date_end=$date_end&page=$page&sort=$sort&ball=$ball&type=$type'</script>";
            exit;
        }
    }else{
        echo "<script>alert('冠军手动派奖事务开启失败！');</script>";
    }

    echo "<script languag='JavaScript'>self.location='query.php?uid=$uid&langx=$langx&&seconds=$seconds&username=$username&date_start=$date_start&date_end=$date_end&page=$page&sort=$sort&ball=$ball&type=$type'</script>";
}

$checkout=$_REQUEST['checkout'];
if ($checkout=='0'){
    $check="and M_Result=''";
}else if ($checkout==''){
    $check="";
}

//$mysql="SELECT ID,MID,Userid,Active,orderNo,LineType,Mtype,Pay_Type,M_Date,BetTime,BetScore,CurType,Middle,Middle_tw,Middle_en,BetType,M_Place,M_Rate,M_Name,Gwin,Glost,VGOLD,M_Result,A_Result,B_Result,C_Result,D_Result,T_Result,OpenType,OddsType,ShowType,Cancel,Ptype,MB_ball,TG_ball,Edit,Confirmed,Gtype,Danger,Checked,sendAwardTime,sendAwardIsAuto,playSource,betid from ".DBPREFIX."web_report_data where $match $wtype ((BetTime>='$date_start' and BetTime<='$date_end') or (M_Date>='$date_start' and M_Date<='$date_end')) $order_sql $name $n_name $cancel $check $result order by $sort desc"; // 也需要根据开赛时间(M_Date)筛选
$mysql="SELECT ID,MID,Userid,Active,orderNo,LineType,Mtype,Pay_Type,M_Date,BetTime,BetScore,CurType,Middle,Middle_tw,Middle_en,BetType,M_Place,M_Rate,M_Name,Gwin,Glost,VGOLD,M_Result,A_Result,B_Result,C_Result,D_Result,T_Result,OpenType,OddsType,ShowType,Cancel,Ptype,MB_ball,TG_ball,Edit,Confirmed,Gtype,Danger,Checked,sendAwardTime,sendAwardIsAuto,playSource,betid from ".DBPREFIX."web_report_data where $order_sql $name BetTime>='$date_start' and BetTime<='$date_end' $match $wtype $n_name $cancel $check $result order by $sort desc"; // 也需要根据开赛时间(M_Date)筛选
//echo $mysql;
$result = mysqli_query($dbLink,$mysql);
// 输入用户名才需统计 会员全部的额度（输赢、下注、有效）
if($name!=''){
    $mem_data['all_win_and_lose'] = 0;
    $mem_data['all_bet'] = 0;
    $mem_data['all_valid_bet'] = 0;
    while ($row = mysqli_fetch_assoc($result)){
        $mem_data['all_bet']+=$row['BetScore'];
        $mem_data['all_valid_bet'] += $row['VGOLD'];
        $mem_data['all_win_and_lose'] += $row['M_Result'];
        //    print_r($row['M_Result']);
    }
}
//die;
//print_r($mem_data); die;

$cou=mysqli_num_rows($result);
$page_size=50;
$page_count=ceil($cou/$page_size);
$offset=$page*$page_size;
$mysql=$mysql."  limit $offset,$page_size;";
//echo $mysql;
$result = mysqli_query($dbLink,$mysql);
$data=$dataFS=array();
$mem_data['win_and_lose'] = 0;
$mem_data['bet'] = 0;
$mem_data['valid_bet'] = 0;
while ($row = mysqli_fetch_assoc($result)){
    if($name!='') { // 输入用户名查询时才需统计 会员此页额度（输赢、下注、有效）
        $mem_data['bet'] += $row['BetScore'];
        $mem_data['valid_bet'] += $row['VGOLD'];
        $mem_data['win_and_lose'] += $row['M_Result'];
    }
    if($row['LineType']==16&&!in_array($row['MID'],array_keys($dataFS))){ $dataFS[$row['MID']]=array(); }
    $data[]=$row;
}

if(count($dataFS)>0){
    $sqlFS = "SELECT MID,MB_Team,M_League,M_Item,M_Start,Cancel FROM `".DBPREFIX.SPORT_FLUSH_FS_MATCH_TABLE."` WHERE win=1 and score=1 and MID in(".implode(',',array_keys($dataFS)).") ";
    $resultFS = mysqli_query($dbLink,$sqlFS);
    while($rowFS = mysqli_fetch_assoc($resultFS)){
        $dataFS[$rowFS['MID']][]=$rowFS['M_Item'];
    }
}

/*echo '<pre>';
print_r($data);
echo '<br/>';*/

?>
<html>
<head>
    <title>query</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <style>
        @media screen and (max-width: 1400px) {
            .query_bet_title{display: none;}
        }
        .query_show_money{display: <?php if($show_detail_money=='1'){ echo 'inline-block';}else{echo 'none';}?>;}
    </style>
    <script type="text/javascript">
        function onLoad(){
            var obj_seconds = document.getElementById('seconds');
            obj_seconds.value = '<?php echo $seconds?>';
            var obj_page = document.getElementById('page');
            obj_page.value = '<?php echo $page?>';
            var obj_sort=document.getElementById('sort');
            obj_sort.value='<?php echo $sort?>';
            var obj_ball=document.getElementById('ball');
            obj_ball.value='<?php echo $ball?>';
            var obj_type=document.getElementById('type');
            obj_type.value='<?php echo $type?>';
            var result_type=document.getElementById('result_type');
            result_type.value='<?php echo $result_type?>';
        }
    </script>
</head>
<body  onLoad="onLoad();auto_refresh()">
<FORM id="myFORM" ACTION="" METHOD=POST name="myFORM" >
    <input type='hidden' name='uid' value='<?php echo $uid;?>'>
    <dl class="main-nav ce111">
        <dd>
            <table>
                <tr class="m_tline">
                    <td><div class="query_bet_title">查询注单</div></td>
                    <td >
                        <input name=button type=button class="za_button" onClick="location.reload()" value="手动更新" style="margin: 2px 0 0 3px;">
                        <select class="za_select za_select_auto" onChange="document.myFORM.submit();" id="seconds" name="seconds">
                            <option value="10">10秒</option>
                            <option value="30">30秒</option>
                            <option value="60">60秒</option>
                            <option value="90">90秒</option>
                            <option value="120">120秒</option>
                            <option value="180">180秒</option>
                        </select>
                        <span id=ShowTime></span>
                        注单查询:
                        <input type=TEXT name="betordernumber"  value="<?php echo $betordernumber?>" minlength="5" maxlength="30" class="za_text_auto" style="width: 140px">
                        注单日期:
                        <input type="text" name="date_start" onclick="laydate({istime: true,istoday: false, format: 'YYYY-MM-DD hh:mm:ss'})" value="<?php echo $date_start?>"  class="za_text_auto" style="width: 143px;"/> -
                        <input type="text" name="date_end" onclick="laydate({istime: true,istoday: false, format: 'YYYY-MM-DD hh:mm:ss'})" value="<?php echo $date_end?>"  class="za_text_auto" style="width: 143px;"/>
                        <?php $query_date_yesterday = date('Y-m-d',time()-86400); ?>
                        <input type="button" class="query_date_yesterday" value="昨日" onclick="query_date('<?php echo $query_date_yesterday;?>')" />
                        会员帐号:
                        <input type=TEXT name="username" size=10 value="<?php echo $username?>" minlength="5" maxlength="15" class="za_text_auto" style="width: 100px">
                        <input type=SUBMIT name="SUBMIT" value="确认" class="za_button">

                        <?php echo $Rep_Bet_State?>
                        <select name='result_type' id="result_type" onChange="self.myFORM.submit()">
                            <option value=""><?php echo $Rel_All?></option>
                            <option value="Y"><?php echo $Rep_Results?></option>
                            <option value="N"><?php echo $Rep_No_Results?></option>
                        </select>

                        共<?php echo $cou?>条
                        <select name='page' id="page" onChange="self.myFORM.submit()">
                            <?php
                            if ($page_count==0){
                                $page_count=1;
                            }
                            for($i=0;$i<$page_count;$i++){
                                if ($i==$page){
                                    echo "<option selected value='$i'>".($i+1)."</option>";
                                }else{
                                    echo "<option value='$i'>".($i+1)."</option>";
                                }
                            }
                            ?>
                        </select> 共<?php echo $page_count?> 页
                    </td>

                </tr>
            </table>
        </dd>
    </dl>

    <div class="main-ui">
        <div class="m_tab" style="position: fixed; right:0; width: 200px;">

            <table>
                <tr>
                    <td width="36"></td>
                    <td width="82">此页</td>
                    <td width="82">全部</td>
                </tr>
                <tr>
                    <td>输赢</td>
                    <td><?php echo number_format($mem_data['win_and_lose'],2);?></td>
                    <td><?php echo number_format($mem_data['all_win_and_lose'],2);?></td>
                </tr>
                <tr>
                    <td>下注</td>
                    <td><?php echo number_format($mem_data['bet'],2);?></td>
                    <td><?php echo number_format($mem_data['all_bet'],2);?></td>
                </tr>
                <tr>
                    <td>有效</td>
                    <td><?php echo number_format($mem_data['valid_bet'],2);?></td>
                    <td><?php echo number_format($mem_data['all_valid_bet'],2);?></td>
                </tr>

            </table>
        </div>
        <div class="width_1300">
            <table class="m_tab">
                <tr class="m_title">
                    <td align="center">
                        <select name="sort" id="sort" onChange="document.myFORM.submit();" class="za_select za_select_auto">
                            <option value="BetTime">投注时间</option>
                            <option value="Gwin">投注金额</option>
                            <option value="Cancel">取消注单</option>
                            <option value="Danger">危险注单</option>
                        </select>		  </td>
                    <td align="center">&nbsp;</td>
                    <td align="center" >
                        <select name="ball" id="ball" onChange="self.myFORM.submit()" class="za_select za_select_auto">
                            <option value="">全部</option>
                            <option value="FT">足球</option>
                            <option value="BK">篮球</option>
                            <!--
                            <option value="1">足球</option>
                            <option value="2">篮球</option>
                            <option value="3">棒球</option>
                            <option value="4">网球</option>
                            <option value="5">排球</option>
                            <option value="6">其它</option>
                            -->
                        </select></td>
                    <td colspan="3" align="center" class="bet_content" >
                        <select name="type" id="type" onChange="self.myFORM.submit()" class="za_select za_select_auto">
                            <option value="" SELECTED>全部</option>
                            <option value="CS">冠军赛</option>
                            <option value="ZHGG">综合过关</option>
                            <option value="DJ">电竞</option>
                            <?php

                            foreach ($hg_game_type as $k => $v){
                                echo '<option value="'.$k.'">'.$v.'</option>';
                            }

                            ?>
                        </select>
                    </td>
                    <td colspan="5" align="center">&nbsp;</td>
                </tr>
                <tr class="m_title">
                    <td width="7%"align="center">投注时间</td>
                    <td width="6%" align="center">用户名称</td>
                    <td width="8%" align="center">球赛种类</td>
                    <td width="18%" align="center">內容</td>
                    <td width="7%" align="center">投注金额 <input type="checkbox" name="show_detail_money" value="<?php echo $show_detail_money;?>" onclick="showDetailMoney(this)" style="float: right" <?php if($show_detail_money=='1'){ echo 'checked';} ?> > </td>
                    <td width="7%" class="true_bet">有效投注</td>
                    <td width="7%" align="center">可赢金额</td>
                    <td width="8%" align="center">会员结果</td>
                    <td width="8%" class="bet_result">即时比分</td>
                    <td width="5%" align="center">注单</td>
                    <?php if ($level=='M' && $c_num[40]==1){ ?><td width="10%" align="center">功能</td><?php } ?>
                </tr>
                <?php

                foreach ($data as $k => $row){
//while ($row = mysqli_fetch_assoc($result)){
                    // 1 足球滚球、今日赛事, 11 足球早餐 2 篮球滚球、今日赛事, 22 篮球早餐
                    switch($row['Active']){
                        case 1:
                            $active='1';
                            $Title=$Mnu_Soccer;
                            break;
                        case 11:
                            $active='11';
                            $Title=$Mnu_Soccer;
                            break;
                        case 2:
                            $active='2';
                            $Title=$Mnu_Bask;
                            break;
                        case 22:
                            $active='22';
                            $Title=$Mnu_Bask;
                            break;
                        case 3:
                            $active='3';
                            $Title=$Mnu_Base;
                            break;
                        case 33:
                            $active='33';
                            $Title=$Mnu_Base;
                            break;
                        case 4:
                            $active='4';
                            $Title=$Mnu_Tennis;
                            break;
                        case 44:
                            $active='44';
                            $Title=$Mnu_Tennis;
                            break;
                        case 5:
                            $active='5';
                            $Title=$Mnu_Voll;
                            break;
                        case 55:
                            $active='55';
                            $Title=$Mnu_Voll;
                            break;
                        case 6:
                            $active='6';
                            $Title=$Mnu_Other;
                            break;
                        case 66:
                            $active='66';
                            $Title=$Mnu_Other;
                            break;
                        case 7:
                            $active='7';
                            $Title=$Mnu_Stock;
                            break;
                        case 77:
                            $active='77';
                            $Title=$Mnu_Stock;
                            break;
                        case 8:
                            $active='8';
                            $Title=$Mnu_Guan;
                            break;
                        case 9:
                            $Title=$Mnu_MarkSix;
                            break;
                    }
// 盘口 H 香港盘 M 马来盘 I 印尼盘 E 欧洲盘
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

                    if($row['Danger']==1 or $row['Cancel']==1) {
                        $bettimes='<font color="#FFFFFF"><span style="background-color: #FF0000">'.$times.'</span></font>';
                    }else{
                        $bettimes=$times;
                    }

                    if ($row['ShowType']=='H' or $row['LineType']=='10' or $row['LineType']=='20'){
                        $matchball=$row['MB_ball'].':'.$row['TG_ball'];
                    }else{
                        $matchball=$row['TG_ball'].':'.$row['MB_ball'];
                    }
                    if ($row['Edit']==0 and $level=='M'){
                        $class='';
                    }else if ($row['Edit']==1 and $level=='M'){
                        $class='bgcolor=#00FF00';
                    }
                    ?>
                    <tr class="m_rig" onmouseover=sbar(this) onmouseout=cbar(this)>
                        <td class="bet_time">
                            <?php
                            echo $bettimes;
                            if($row['Gtype']=='BK'&&$row['LineType']!=8){
                                echo '<br/>'.$row['betid'];
                            }
                            ?>
                        </td>
                        <td><?php echo $row['M_Name']?><br><font color="#CC0000"><?php echo $row['OpenType']?></font><br>
                            <?php
                            //投注来源:0未知,1pc旧版,2pc新版,5综合新版(参照正网),3 苹果wap,4安卓wap,13原生苹果,14原生安卓,22综合版
                            switch ($row['playSource']){
                                case '0':
                                    echo '未知';
                                    break;
                                case '1':
                                    echo '旧版';
                                    break;
                                case '2':
                                    echo '新版';
                                    break;
                                case '3':
                                    echo 'ios';
                                    break;
                                case '4':
                                    echo 'android';
                                    break;
                                case '5':
                                    echo '综合新版';
                                    break;
                                case '13':
                                    echo 'ios原生';
                                    break;
                                case '14':
                                    echo 'android原生';
                                    break;
                                case '22':
                                    echo '综合版';
                                    break;
                            }
                            ?>
                        </td>
                        <td class="bet_game_type"><?php echo $Title?><?php echo $row['BetType']?><?php echo $Odds?><br><font color="#0000CC"><?php echo $row['orderNo']?></font></td>
                        <td align="right" data-cancel="<?php echo $row['Cancel']?>" data-bettype="<?php echo $row['BetType']?>" data-active="<?php echo $row['Active']?>" data-linetype="<?php echo $row['LineType']?>" data-middle="<?php echo $row['Middle']?>" data-mid="<?php echo $row['MID']?>" class="bet_content" <?php echo $class?>>
                            <?php
                            if($row['Cancel']==1){
                                echo "<span style=float:left;color=#0000FF>".$matchball."</span>";
                            }
                            ?>
                            <!--
                            *   Active : 1 足球滚球、今日赛事, 11 足球早餐，2 篮球滚球、今日赛事, 22 篮球早餐
                            *   LineType : 1 全场-独赢  ,2 全场-让球  , 3 全场-今日赛事-得分大小 ,4 足球今日赛事-波胆 ,4 半场(篮球)-滚球-得分大小，5 全场-今日赛事单双  ，6 足球今日赛事-总入球 ,7 足球今日赛事-全场/半场
                            *              8 综合过关，9 全场-滚球-让球，10 全场-滚球-得分大小，11 半场(足球)-独赢 ,12 半场(足球)-今日赛事-让球， 13 半场-足球得分大小，16 足球(篮球)今日赛事-冠军，19 半场(足球)-滚球-让球
                            *              20 半场(足球)-滚球-得分大小，21 全场-滚球-独赢，31 半场(足球)-滚球-独赢，33 半场-篮球得分大小
                            -->
                            <?php
                            if($row['Active']==$active){
                                if($row['LineType']==16){
                                    echo $row['M_Date'];
                                    echo '<br/>';
                                    echo $row['Middle'];
                                }elseif($row['LineType']==8){ // 综合过关
                                    $midd=explode('<br>',$row['Middle']);
                                    $mid=explode(',',$row['MID']);
                                    $show=explode(',',$row['ShowType']);

                                    for($t=0;$t<(sizeof($midd)-1)/3;$t++){
                                        // echo $midd[3*$t].'<br>';
                                        if($t==0){ // 明天和明天以后的赛事需要显示日期
                                           /* if($row['BetTime'] < $row['M_Date']){ // 明天和明天以后的赛事需要显示日期
                                                echo $midd[0].' &nbsp;'.substr($row['M_Date'],5).'<br>';
                                            }else{
                                                echo $midd[0].'<br>';
                                            }*/
                                            echo $midd[0].'<br>';
                                        }else{
                                            echo $midd[3*$t].'<br>';
                                        }

                                        $mysql="select MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where MID=".$mid[$t];
                                        $result1 = mysqli_query($dbLink,$mysql);
                                        $row1 = mysqli_fetch_assoc($result1);

                                        if ($row1["MB_Inball"]=='-1'){
                                            $font_a3='<font color="#009900"><b>'.$Score1.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score1.'</b></font>&nbsp;';
                                            if($row1["MB_Inball_HR"]=='-1'){
                                                $font_a1='<font color="#009900"><b>'.$Score1.'</b></font>&nbsp;';
                                                $font_a2='<font color="#009900"><b>'.$Score1.'</b></font>&nbsp;';
                                            }
                                        }else if ($row1["MB_Inball"]=='-2'){
                                            $font_a3='<font color="#009900"><b>'.$Score2.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score2.'</b></font>&nbsp;';
                                            if($row1["MB_Inball_HR"]=='-2'){
                                                $font_a1='<font color="#009900"><b>'.$Score2.'</b></font>&nbsp;';
                                                $font_a2='<font color="#009900"><b>'.$Score2.'</b></font>&nbsp;';
                                            }
                                        }else if ($row1["MB_Inball"]=='-3'){
                                            $font_a3='<font color="#009900"><b>'.$Score3.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score3.'</b></font>&nbsp;';
                                            if($row1["MB_Inball_HR"]=='-3'){
                                                $font_a1='<font color="#009900"><b>'.$Score3.'</b></font>&nbsp;';
                                                $font_a2='<font color="#009900"><b>'.$Score3.'</b></font>&nbsp;';
                                            }
                                        }else if ($row1["MB_Inball"]=='-4'){
                                            $font_a3='<font color="#009900"><b>'.$Score4.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score4.'</b></font>&nbsp;';
                                            if($row1["MB_Inball_HR"]=='-4'){
                                                $font_a1='<font color="#009900"><b>'.$Score4.'</b></font>&nbsp;';
                                                $font_a2='<font color="#009900"><b>'.$Score4.'</b></font>&nbsp;';
                                            }
                                        }else if ($row1["MB_Inball"]=='-5'){
                                            $font_a3='<font color="#009900"><b>'.$Score5.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score5.'</b></font>&nbsp;';
                                            if($row1["MB_Inball_HR"]=='-5'){
                                                $font_a1='<font color="#009900"><b>'.$Score5.'</b></font>&nbsp;';
                                                $font_a2='<font color="#009900"><b>'.$Score5.'</b></font>&nbsp;';
                                            }
                                        }else if ($row1["MB_Inball"]=='-6'){
                                            $font_a3='<font color="#009900"><b>'.$Score6.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score6.'</b></font>&nbsp;';
                                            if($row1["MB_Inball_HR"]=='-6'){
                                                $font_a1='<font color="#009900"><b>'.$Score6.'</b></font>&nbsp;';
                                                $font_a2='<font color="#009900"><b>'.$Score6.'</b></font>&nbsp;';
                                            }
                                        }else if ($row1["MB_Inball"]=='-7'){
                                            $font_a3='<font color="#009900"><b>'.$Score7.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score7.'</b></font>&nbsp;';
                                            if($row1["MB_Inball_HR"]=='-7'){
                                                $font_a1='<font color="#009900"><b>'.$Score7.'</b></font>&nbsp;';
                                                $font_a2='<font color="#009900"><b>'.$Score7.'</b></font>&nbsp;';
                                            }
                                        }else if ($row1["MB_Inball"]=='-8'){
                                            $font_a3='<font color="#009900"><b>'.$Score8.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score8.'</b></font>&nbsp;';
                                            if($row1["MB_Inball_HR"]=='-8'){
                                                $font_a1='<font color="#009900"><b>'.$Score8.'</b></font>&nbsp;';
                                                $font_a2='<font color="#009900"><b>'.$Score8.'</b></font>&nbsp;';
                                            }
                                        }else if ($row1["MB_Inball"]=='-9'){
                                            $font_a3='<font color="#009900"><b>'.$Score9.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score9.'</b></font>&nbsp;';
                                            if($row1["MB_Inball_HR"]=='-9'){
                                                $font_a1='<font color="#009900"><b>'.$Score9.'</b></font>&nbsp;';
                                                $font_a2='<font color="#009900"><b>'.$Score9.'</b></font>&nbsp;';
                                            }
                                        }else if ($row1["MB_Inball"]=='-10'){
                                            $font_a3='<font color="#009900"><b>'.$Score10.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score10.'</b></font>&nbsp;';
                                            if($row1["MB_Inball_HR"]=='-10'){
                                                $font_a1='<font color="#009900"><b>'.$Score10.'</b></font>&nbsp;';
                                                $font_a2='<font color="#009900"><b>'.$Score10.'</b></font>&nbsp;';
                                            }
                                        }else if ($row1["MB_Inball"]=='-11'){
                                            $font_a3='<font color="#009900"><b>'.$Score11.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score11.'</b></font>&nbsp;';
                                            if($row1["MB_Inball_HR"]=='-11'){
                                                $font_a1='<font color="#009900"><b>'.$Score11.'</b></font>&nbsp;';
                                                $font_a2='<font color="#009900"><b>'.$Score11.'</b></font>&nbsp;';
                                            }
                                        }else if ($row1["MB_Inball"]=='-12'){
                                            $font_a3='<font color="#009900"><b>'.$Score12.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score12.'</b></font>&nbsp;';
                                            if($row1["MB_Inball_HR"]=='-12'){
                                                $font_a1='<font color="#009900"><b>'.$Score12.'</b></font>&nbsp;';
                                                $font_a2='<font color="#009900"><b>'.$Score12.'</b></font>&nbsp;';
                                            }
                                        }else if ($row1["MB_Inball"]=='-13'){
                                            $font_a3='<font color="#009900"><b>'.$Score13.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score13.'</b></font>&nbsp;';
                                            if($row1["MB_Inball_HR"]=='-13'){
                                                $font_a1='<font color="#009900"><b>'.$Score13.'</b></font>&nbsp;';
                                                $font_a2='<font color="#009900"><b>'.$Score13.'</b></font>&nbsp;';
                                            }
                                        }else if ($row1["MB_Inball"]=='-14'){
                                            $font_a3='<font color="#009900"><b>'.$Score14.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score14.'</b></font>&nbsp;';
                                            if($row1["MB_Inball_HR"]=='-14'){
                                                $font_a1='<font color="#009900"><b>'.$Score14.'</b></font>&nbsp;';
                                                $font_a2='<font color="#009900"><b>'.$Score14.'</b></font>&nbsp;';
                                            }
                                        }else if ($row1["MB_Inball"]=='-15'){
                                            $font_a3='<font color="#009900"><b>'.$Score15.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score15.'</b></font>&nbsp;';
                                            if($row1["MB_Inball_HR"]=='-15'){
                                                $font_a1='<font color="#009900"><b>'.$Score15.'</b></font>&nbsp;';
                                                $font_a2='<font color="#009900"><b>'.$Score15.'</b></font>&nbsp;';
                                            }
                                        }else if ($row1["MB_Inball"]=='-16'){
                                            $font_a3='<font color="#009900"><b>'.$Score16.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score16.'</b></font>&nbsp;';
                                            if($row1["MB_Inball_HR"]=='-16'){
                                                $font_a1='<font color="#009900"><b>'.$Score16.'</b></font>&nbsp;';
                                                $font_a2='<font color="#009900"><b>'.$Score16.'</b></font>&nbsp;';
                                            }
                                        }else if ($row1["MB_Inball"]=='-17'){
                                            $font_a3='<font color="#009900"><b>'.$Score17.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score17.'</b></font>&nbsp;';
                                            if($row1["MB_Inball_HR"]=='-17'){
                                                $font_a1='<font color="#009900"><b>'.$Score17.'</b></font>&nbsp;';
                                                $font_a2='<font color="#009900"><b>'.$Score17.'</b></font>&nbsp;';
                                            }
                                        }else if ($row1["MB_Inball"]=='-18'){
                                            $font_a3='<font color="#009900"><b>'.$Score18.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score18.'</b></font>&nbsp;';
                                            if($row1["MB_Inball_HR"]=='-18'){
                                                $font_a1='<font color="#009900"><b>'.$Score18.'</b></font>&nbsp;';
                                                $font_a2='<font color="#009900"><b>'.$Score18.'</b></font>&nbsp;';
                                            }
                                        }else if ($row1["MB_Inball"]=='-19'){
                                            $font_a3='<font color="#009900"><b>'.$Score19.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score19.'</b></font>&nbsp;';
                                            if($row1["MB_Inball_HR"]=='-19'){
                                                $font_a1='<font color="#009900"><b>'.$Score19.'</b></font>&nbsp;';
                                                $font_a2='<font color="#009900"><b>'.$Score19.'</b></font>&nbsp;';
                                            }
                                        }else{
                                            $font_a3='<font color="#009900"><b>'.$row1["TG_Inball"].'</b> : <b>'.$row1["MB_Inball"].'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$row1["MB_Inball"].'</b> : <b>'.$row1["TG_Inball"].'</b></font>&nbsp;';
                                            $font_a1='<font color="#009900"><b>'.$row1["TG_Inball_HR"].'</b> : <b>'.$row1["MB_Inball_HR"].'</b></font>&nbsp; ';
                                            $font_a2='<font color="#009900"><b>'.$row1["MB_Inball_HR"].'</b> : <b>'.$row1["TG_Inball_HR"].'</b></font>&nbsp; ';
                                        }

                                        // echo $midd[3*$t+1].'<br>';
                                        if($t==0){ // 明天和明天以后的赛事需要显示日期
                                            /*if($row['BetTime'] < $row['M_Date']){ // 明天和明天以后的赛事需要显示日期
                                                echo $midd[1].' &nbsp;'.substr($row['M_Date'],5).'<br>';
                                            }else{
                                                echo $midd[1].'<br>';
                                            }*/
                                            echo $midd[1].'<br>';
                                        }else{
                                            echo $midd[3*$t+1].'<br>';
                                        }

                                        // 综合过关注单内容显示，全场投注显示全场比分，半场投注显示半场比分
                                        if ($show[$t]=='C' and $row['LineType']==8){
                                            $pos = strpos($midd[3*$t+2], '[上半]');
                                            if ($pos!==false){
                                                echo $font_a1;
                                            }else{
                                                echo $font_a3;
                                            }
                                        }else{
                                            $pos = strpos($midd[3*$t+2], '[上半]');
                                            if ($pos!==false){
                                                echo $font_a2;
                                            }else{
                                                echo $font_a4;
                                            }
                                        }
//             echo $midd[3*$t+2].'<br>';

                                        if($t==0){ // 明天和明天以后的赛事需要显示日期
                                            /*if($row['BetTime'] < $row['M_Date']){ // 明天和明天以后的赛事需要显示日期
                                                echo $midd[2].' &nbsp;'.substr($row['M_Date'],5).'<br>';
                                            }else{
                                                echo $midd[2].'<br>';
                                            }*/
                                            echo $midd[2].'<br>';
                                        }else{
                                            echo $midd[3*$t+2].'<br>';
                                        }
                                    }
                                }else{
                                    $midd=explode('<br>',$row['Middle']);
                                    // var_dump($midd) ;

                                    for($t=0;$t<sizeof($midd)-1;$t++){
                                        if($t==0){ // 明天和明天以后的赛事需要显示日期
                                            if($row['BetTime'] < $row['M_Date']){ // 明天和明天以后的赛事需要显示日期
                                                echo $midd[0].' &nbsp;'.substr($row['M_Date'],5).'<br>';
                                            }else{
                                                echo $midd[0].'<br>';
                                            }

                                        }else{
                                            echo $midd[$t].'<br>';
                                        }

                                    }

                                    $mysqlL="select MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where MID=".$row['MID'];
                                    $result1L = mysqli_query($dbLink,$mysqlL);
                                    $row1L = mysqli_fetch_assoc($result1L);

                                    $mysql="select MB_Team,TG_Team,M_League,M_Start,M_Time,M_Duration,MB_Ball,TG_Ball from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where MID=".$row['MID'];
                                    $result1 = mysqli_query($dbCenterSlaveDbLink,$mysql);
                                    $row1 = mysqli_fetch_assoc($result1);

                                    if ($row1L["MB_Inball"]=='-1'){
                                        if($row1L["MB_Inball_HR"]=='-1' and $row1L["MB_Inball"]=='-1'){
                                            $font_a1='<font color="#009900"><b>'.$Score1.'</b></font>&nbsp;';
                                            $font_a2='<font color="#009900"><b>'.$Score1.'</b></font>&nbsp;';
                                            $font_a3='<font color="#009900"><b>'.$Score1.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score1.'</b></font>&nbsp;';
                                        }else{
                                            $font_a2='<font color="#009900"><b>'.$row1L["MB_Inball_HR"].'</b> : <b>'.$row1L["TG_Inball_HR"].'</b></font>&nbsp;';
                                            $font_a1='<font color="#009900"><b>'.$row1L["TG_Inball_HR"].'</b> : <b>'.$row1L["MB_Inball_HR"].'</b></font>&nbsp;';
                                            $font_a3='<font color="#009900"><b>'.$Score1.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score1.'</b></font>&nbsp;';
                                        }
                                    }else if ($row1L["MB_Inball"]=='-2'){
                                        if($row1L["MB_Inball_HR"]=='-2' and $row1L["MB_Inball"]=='-2'){
                                            $font_a1='<font color="#009900"><b>'.$Score2.'</b></font>&nbsp;';
                                            $font_a2='<font color="#009900"><b>'.$Score2.'</b></font>&nbsp;';
                                            $font_a3='<font color="#009900"><b>'.$Score2.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score2.'</b></font>&nbsp;';
                                        }else{
                                            $font_a2='<font color="#009900"><b>'.$row1L["MB_Inball_HR"].'</b> : <b>'.$row1L["TG_Inball_HR"].'</b></font>&nbsp;';
                                            $font_a1='<font color="#009900"><b>'.$row1L["TG_Inball_HR"].'</b> : <b>'.$row1L["MB_Inball_HR"].'</b></font>&nbsp;';
                                            $font_a3='<font color="#009900"><b>'.$Score2.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score2.'</b></font>&nbsp;';
                                        }
                                    }else if ($row1L["MB_Inball"]=='-3'){
                                        if($row1L["MB_Inball_HR"]=='-3' and $row1L["MB_Inball"]=='-3'){
                                            $font_a1='<font color="#009900"><b>'.$Score3.'</b></font>&nbsp;';
                                            $font_a2='<font color="#009900"><b>'.$Score3.'</b></font>&nbsp;';
                                            $font_a3='<font color="#009900"><b>'.$Score3.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score3.'</b></font>&nbsp;';
                                        }else{
                                            $font_a2='<font color="#009900"><b>'.$row1L["MB_Inball_HR"].'</b> : <b>'.$row1L["TG_Inball_HR"].'</b></font>&nbsp;';
                                            $font_a1='<font color="#009900"><b>'.$row1L["TG_Inball_HR"].'</b> : <b>'.$row1L["MB_Inball_HR"].'</b></font>&nbsp;';
                                            $font_a3='<font color="#009900"><b>'.$Score3.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score3.'</b></font>&nbsp;';
                                        }
                                    }else if ($row1L["MB_Inball"]=='-4'){
                                        if($row1L["MB_Inball_HR"]=='-4' and $row1L["MB_Inball"]=='-4'){
                                            $font_a1='<font color="#009900"><b>'.$Score4.'</b></font>&nbsp;';
                                            $font_a2='<font color="#009900"><b>'.$Score4.'</b></font>&nbsp;';
                                            $font_a3='<font color="#009900"><b>'.$Score4.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score4.'</b></font>&nbsp;';
                                        }else{
                                            $font_a2='<font color="#009900"><b>'.$row1L["MB_Inball_HR"].'</b> : <b>'.$row1L["TG_Inball_HR"].'</b></font>&nbsp;';
                                            $font_a1='<font color="#009900"><b>'.$row1L["TG_Inball_HR"].'</b> : <b>'.$row1L["MB_Inball_HR"].'</b></font>&nbsp;';
                                            $font_a3='<font color="#009900"><b>'.$Score4.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score4.'</b></font>&nbsp;';
                                        }
                                    }else if ($row1L["MB_Inball"]=='-5'){
                                        if($row1L["MB_Inball_HR"]=='-5' and $row1L["MB_Inball"]=='-5'){
                                            $font_a1='<font color="#009900"><b>'.$Score5.'</b></font>&nbsp;';
                                            $font_a2='<font color="#009900"><b>'.$Score5.'</b></font>&nbsp;';
                                            $font_a3='<font color="#009900"><b>'.$Score5.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score5.'</b></font>&nbsp;';
                                        }else{
                                            $font_a2='<font color="#009900"><b>'.$row1L["MB_Inball_HR"].'</b> : <b>'.$row1L["TG_Inball_HR"].'</b></font>&nbsp;';
                                            $font_a1='<font color="#009900"><b>'.$row1L["TG_Inball_HR"].'</b> : <b>'.$row1L["MB_Inball_HR"].'</b></font>&nbsp;';
                                            $font_a3='<font color="#009900"><b>'.$Score5.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score5.'</b></font>&nbsp;';
                                        }
                                    }else if ($row1L["MB_Inball"]=='-6'){
                                        if($row1L["MB_Inball_HR"]=='-6' and $row1L["MB_Inball"]=='-6')
                                        {
                                            $font_a1='<font color="#009900"><b>'.$Score6.'</b></font>&nbsp;';
                                            $font_a2='<font color="#009900"><b>'.$Score6.'</b></font>&nbsp;';
                                            $font_a3='<font color="#009900"><b>'.$Score6.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score6.'</b></font>&nbsp;';
                                        }else{
                                            $font_a2='<font color="#009900"><b>'.$row1L["MB_Inball_HR"].'</b> : <b>'.$row1L["TG_Inball_HR"].'</b></font>&nbsp;';
                                            $font_a1='<font color="#009900"><b>'.$row1L["TG_Inball_HR"].'</b> : <b>'.$row1L["MB_Inball_HR"].'</b></font>&nbsp;';
                                            $font_a3='<font color="#009900"><b>'.$Score6.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score6.'</b></font>&nbsp;';
                                        }
                                    }else if ($row1L["MB_Inball"]=='-7'){
                                        if($row1L["MB_Inball_HR"]=='-7' and $row1L["MB_Inball"]=='-7'){
                                            $font_a1='<font color="#009900"><b>'.$Score7.'</b></font>&nbsp;';
                                            $font_a2='<font color="#009900"><b>'.$Score7.'</b></font>&nbsp;';
                                            $font_a3='<font color="#009900"><b>'.$Score7.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score7.'</b></font>&nbsp;';
                                        }else{
                                            $font_a2='<font color="#009900"><b>'.$row1L["MB_Inball_HR"].'</b> : <b>'.$row1L["TG_Inball_HR"].'</b></font>&nbsp;';
                                            $font_a1='<font color="#009900"><b>'.$row1L["TG_Inball_HR"].'</b> : <b>'.$row1L["MB_Inball_HR"].'</b></font>&nbsp;';
                                            $font_a3='<font color="#009900"><b>'.$Score7.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score7.'</b></font>&nbsp;';
                                        }
                                    }else if ($row1L["MB_Inball"]=='-8'){
                                        if($row1L["MB_Inball_HR"]=='-8' and $row1L["MB_Inball"]=='-8')
                                        {
                                            $font_a1='<font color="#009900"><b>'.$Score8.'</b></font>&nbsp;';
                                            $font_a2='<font color="#009900"><b>'.$Score8.'</b></font>&nbsp;';
                                            $font_a3='<font color="#009900"><b>'.$Score8.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score8.'</b></font>&nbsp;';
                                        }else{
                                            $font_a2='<font color="#009900"><b>'.$row1L["MB_Inball_HR"].'</b> : <b>'.$row1L["TG_Inball_HR"].'</b></font>&nbsp;';
                                            $font_a1='<font color="#009900"><b>'.$row1L["TG_Inball_HR"].'</b> : <b>'.$row1L["MB_Inball_HR"].'</b></font>&nbsp;';
                                            $font_a3='<font color="#009900"><b>'.$Score8.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score8.'</b></font>&nbsp;';
                                        }
                                    }else if ($row1L["MB_Inball"]=='-9'){
                                        if($row1L["MB_Inball_HR"]=='-9' and $row1L["MB_Inball"]=='-9')
                                        {
                                            $font_a1='<font color="#009900"><b>'.$Score9.'</b></font>&nbsp;';
                                            $font_a2='<font color="#009900"><b>'.$Score9.'</b></font>&nbsp;';
                                            $font_a3='<font color="#009900"><b>'.$Score9.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score9.'</b></font>&nbsp;';
                                        }else{
                                            $font_a2='<font color="#009900"><b>'.$row1L["MB_Inball_HR"].'</b> : <b>'.$row1L["TG_Inball_HR"].'</b></font>&nbsp;';
                                            $font_a1='<font color="#009900"><b>'.$row1L["TG_Inball_HR"].'</b> : <b>'.$row1L["MB_Inball_HR"].'</b></font>&nbsp;';
                                            $font_a3='<font color="#009900"><b>'.$Score9.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score9.'</b></font>&nbsp;';
                                        }
                                    }else if ($row1L["MB_Inball"]=='-10'){
                                        if($row1L["MB_Inball_HR"]=='-10' and $row1L["MB_Inball"]=='-10')
                                        {
                                            $font_a1='<font color="#009900"><b>'.$Score10.'</b></font>&nbsp;';
                                            $font_a2='<font color="#009900"><b>'.$Score10.'</b></font>&nbsp;';
                                            $font_a3='<font color="#009900"><b>'.$Score10.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score10.'</b></font>&nbsp;';
                                        }else{
                                            $font_a2='<font color="#009900"><b>'.$row1L["MB_Inball_HR"].'</b> : <b>'.$row1L["TG_Inball_HR"].'</b></font>&nbsp;';
                                            $font_a1='<font color="#009900"><b>'.$row1L["TG_Inball_HR"].'</b> : <b>'.$row1L["MB_Inball_HR"].'</b></font>&nbsp;';
                                            $font_a3='<font color="#009900"><b>'.$Score10.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score10.'</b></font>&nbsp;';
                                        }
                                    }else if ($row1L["MB_Inball"]=='-11'){
                                        if($row1L["MB_Inball_HR"]=='-11' and $row1L["MB_Inball"]=='-11'){
                                            $font_a1='<font color="#009900"><b>'.$Score11.'</b></font>&nbsp;';
                                            $font_a2='<font color="#009900"><b>'.$Score11.'</b></font>&nbsp;';
                                            $font_a3='<font color="#009900"><b>'.$Score11.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score11.'</b></font>&nbsp;';
                                        }else{
                                            $font_a2='<font color="#009900"><b>'.$row1L["MB_Inball_HR"].'</b> : <b>'.$row1L["TG_Inball_HR"].'</b></font>&nbsp;';
                                            $font_a1='<font color="#009900"><b>'.$row1L["TG_Inball_HR"].'</b> : <b>'.$row1L["MB_Inball_HR"].'</b></font>&nbsp;';
                                            $font_a3='<font color="#009900"><b>'.$Score11.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score11.'</b></font>&nbsp;';
                                        }
                                    }else if ($row1L["MB_Inball"]=='-12'){
                                        if($row1L["MB_Inball_HR"]=='-12' and $row1L["MB_Inball"]=='-12'){
                                            $font_a1='<font color="#009900"><b>'.$Score12.'</b></font>&nbsp;';
                                            $font_a2='<font color="#009900"><b>'.$Score12.'</b></font>&nbsp;';
                                            $font_a3='<font color="#009900"><b>'.$Score12.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score12.'</b></font>&nbsp;';
                                        }else{
                                            $font_a2='<font color="#009900"><b>'.$row1L["MB_Inball_HR"].'</b> : <b>'.$row1L["TG_Inball_HR"].'</b></font>&nbsp;';
                                            $font_a1='<font color="#009900"><b>'.$row1L["TG_Inball_HR"].'</b> : <b>'.$row1L["MB_Inball_HR"].'</b></font>&nbsp;';
                                            $font_a3='<font color="#009900"><b>'.$Score12.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score12.'</b></font>&nbsp;';
                                        }
                                    }else if ($row1L["MB_Inball"]=='-13'){
                                        if($row1L["MB_Inball_HR"]=='-13' and $row1L["MB_Inball"]=='-13'){
                                            $font_a1='<font color="#009900"><b>'.$Score13.'</b></font>&nbsp;';
                                            $font_a2='<font color="#009900"><b>'.$Score13.'</b></font>&nbsp;';
                                            $font_a3='<font color="#009900"><b>'.$Score13.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score13.'</b></font>&nbsp;';
                                        }else{
                                            $font_a2='<font color="#009900"><b>'.$row1L["MB_Inball_HR"].'</b> : <b>'.$row1L["TG_Inball_HR"].'</b></font>&nbsp;';
                                            $font_a1='<font color="#009900"><b>'.$row1L["TG_Inball_HR"].'</b> : <b>'.$row1L["MB_Inball_HR"].'</b></font>&nbsp;';
                                            $font_a3='<font color="#009900"><b>'.$Score13.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score13.'</b></font>&nbsp;';
                                        }
                                    }else if ($row1L["MB_Inball"]=='-14'){
                                        if($row1L["MB_Inball_HR"]=='-14' and $row1L["MB_Inball"]=='-14'){
                                            $font_a1='<font color="#009900"><b>'.$Score14.'</b></font>&nbsp;';
                                            $font_a2='<font color="#009900"><b>'.$Score14.'</b></font>&nbsp;';
                                            $font_a3='<font color="#009900"><b>'.$Score14.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score14.'</b></font>&nbsp;';
                                        }else{
                                            $font_a2='<font color="#009900"><b>'.$row1L["MB_Inball_HR"].'</b> : <b>'.$row1L["TG_Inball_HR"].'</b></font>&nbsp;';
                                            $font_a1='<font color="#009900"><b>'.$row1L["TG_Inball_HR"].'</b> : <b>'.$row1L["MB_Inball_HR"].'</b></font>&nbsp;';
                                            $font_a3='<font color="#009900"><b>'.$Score14.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score14.'</b></font>&nbsp;';
                                        }
                                    }else if ($row1L["MB_Inball"]=='-15'){
                                        if($row1L["MB_Inball_HR"]=='-15' and $row1L["MB_Inball"]=='-15'){
                                            $font_a1='<font color="#009900"><b>'.$Score15.'</b></font>&nbsp;';
                                            $font_a2='<font color="#009900"><b>'.$Score15.'</b></font>&nbsp;';
                                            $font_a3='<font color="#009900"><b>'.$Score15.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score15.'</b></font>&nbsp;';
                                        }else{
                                            $font_a2='<font color="#009900"><b>'.$row1L["MB_Inball_HR"].'</b> : <b>'.$row1L["TG_Inball_HR"].'</b></font>&nbsp;';
                                            $font_a1='<font color="#009900"><b>'.$row1L["TG_Inball_HR"].'</b> : <b>'.$row1L["MB_Inball_HR"].'</b></font>&nbsp;';
                                            $font_a3='<font color="#009900"><b>'.$Score15.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score15.'</b></font>&nbsp;';
                                        }
                                    }else if ($row1L["MB_Inball"]=='-16'){
                                        if($row1L["MB_Inball_HR"]=='-16' and $row1L["MB_Inball"]=='-16')
                                        {
                                            $font_a1='<font color="#009900"><b>'.$Score16.'</b></font>&nbsp;';
                                            $font_a2='<font color="#009900"><b>'.$Score16.'</b></font>&nbsp;';
                                            $font_a3='<font color="#009900"><b>'.$Score16.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score16.'</b></font>&nbsp;';
                                        }else{
                                            $font_a2='<font color="#009900"><b>'.$row1L["MB_Inball_HR"].'</b> : <b>'.$row1L["TG_Inball_HR"].'</b></font>&nbsp;';
                                            $font_a1='<font color="#009900"><b>'.$row1L["TG_Inball_HR"].'</b> : <b>'.$row1L["MB_Inball_HR"].'</b></font>&nbsp;';
                                            $font_a3='<font color="#009900"><b>'.$Score16.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score16.'</b></font>&nbsp;';
                                        }
                                    }else if ($row1L["MB_Inball"]=='-17'){
                                        if($row1L["MB_Inball_HR"]=='-17' and $row1L["MB_Inball"]=='-17'){
                                            $font_a1='<font color="#009900"><b>'.$Score17.'</b></font>&nbsp;';
                                            $font_a2='<font color="#009900"><b>'.$Score17.'</b></font>&nbsp;';
                                            $font_a3='<font color="#009900"><b>'.$Score17.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score17.'</b></font>&nbsp;';
                                        }else{
                                            $font_a2='<font color="#009900"><b>'.$row1L["MB_Inball_HR"].'</b> : <b>'.$row1L["TG_Inball_HR"].'</b></font>&nbsp;';
                                            $font_a1='<font color="#009900"><b>'.$row1L["TG_Inball_HR"].'</b> : <b>'.$row1L["MB_Inball_HR"].'</b></font>&nbsp;';
                                            $font_a3='<font color="#009900"><b>'.$Score17.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score17.'</b></font>&nbsp;';
                                        }
                                    }else if ($row1L["MB_Inball"]=='-18'){
                                        if($row1L["MB_Inball_HR"]=='-18' and $row1L["MB_Inball"]=='-18')
                                        {
                                            $font_a1='<font color="#009900"><b>'.$Score18.'</b></font>&nbsp;';
                                            $font_a2='<font color="#009900"><b>'.$Score18.'</b></font>&nbsp;';
                                            $font_a3='<font color="#009900"><b>'.$Score18.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score18.'</b></font>&nbsp;';
                                        }else{
                                            $font_a2='<font color="#009900"><b>'.$row1L["MB_Inball_HR"].'</b> : <b>'.$row1L["TG_Inball_HR"].'</b></font>&nbsp;';
                                            $font_a1='<font color="#009900"><b>'.$row1L["TG_Inball_HR"].'</b> : <b>'.$row1L["MB_Inball_HR"].'</b></font>&nbsp;';
                                            $font_a3='<font color="#009900"><b>'.$Score18.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score18.'</b></font>&nbsp;';
                                        }
                                    }else if ($row1L["MB_Inball"]=='-19'){
                                        if($row1L["MB_Inball_HR"]=='-19' and $row1L["MB_Inball"]=='-19')
                                        {
                                            $font_a1='<font color="#009900"><b>'.$Score19.'</b></font>&nbsp;';
                                            $font_a2='<font color="#009900"><b>'.$Score19.'</b></font>&nbsp;';
                                            $font_a3='<font color="#009900"><b>'.$Score19.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score19.'</b></font>&nbsp;';
                                        }else{
                                            $font_a2='<font color="#009900"><b>'.$row1L["MB_Inball_HR"].'</b> : <b>'.$row1L["TG_Inball_HR"].'</b></font>&nbsp;';
                                            $font_a1='<font color="#009900"><b>'.$row1L["TG_Inball_HR"].'</b> : <b>'.$row1L["MB_Inball_HR"].'</b></font>&nbsp;';
                                            $font_a3='<font color="#009900"><b>'.$Score19.'</b></font>&nbsp;';
                                            $font_a4='<font color="#009900"><b>'.$Score19.'</b></font>&nbsp;';
                                        }
                                    }else{
                                        $font_a3='<font color="#009900"><b>'.$row1L["TG_Inball"].'</b> : <b>'.$row1L["MB_Inball"].'</b></font> &nbsp;';
                                        $font_a4='<font color="#009900"><b>'.$row1L["MB_Inball"].'</b> : <b>'.$row1L["TG_Inball"].'</b></font>&nbsp; ';
                                        $font_a1='<font color="#009900"><b>'.$row1L["TG_Inball_HR"].'</b> : <b>'.$row1L["MB_Inball_HR"].'</b></font>&nbsp; ';
                                        $font_a2='<font color="#009900"><b>'.$row1L["MB_Inball_HR"].'</b> : <b>'.$row1L["TG_Inball_HR"].'</b></font>&nbsp; ';
                                    }

                                    //if ($row['LineType']==11 or $row['LineType']==12 or ($row['LineType']==13 && $row['Gtype']=="FT") or $row['LineType']==14 or $row['LineType']==204 or $row['LineType']==15 or $row['LineType']==16 or $row['LineType']==205 or $row['LineType']==206 or $row['LineType']==19 or $row['LineType']==20 or ($row['LineType']==31 && $row['Gtype']=="FT") or ($row['LineType']==165 && $row['Gtype']=="FT") or $row['LineType']==50){
                                    if( in_array($row['LineType'],array(11,12,13,14,15,16,19,20,31,46,50,165,204,205,206,244)) && $row['Gtype']=="FT"){
                                        if ($row['ShowType']=='C' and ($row['LineType']==12 or $row['LineType']==19)){
                                            echo $font_a1;
                                        }else{
                                            echo $font_a2;
                                        }
                                    }else{
                                        if ($row['ShowType']=='C' and ($row['LineType']==2 or $row['LineType']==9 or $row['LineType']==39 or $row['LineType']==139)){
                                            echo $font_a3;
                                        }else{
                                            echo $font_a4;
                                        }
                                    }
                                    echo $midd[sizeof($midd)-1];
                                }

                            }else{
                                echo $row['Middle'];
                            }
                            ?></td>
                        <td class="bet_money">
                            <?php
                            if($row['Cancel']==1){
                                echo "<font color=green class='query_show_money'>".floor($row['Glost'])."</font><br/>";
                                $betscore='<S><font color=#cc0000>'.floor($row['BetScore']).'</font></S>';
                                echo $betscore."<br/>";
                                echo "<span class='query_show_money'>".floor($row['Glost']-$row['BetScore'])."</span><br/>";
                            }else{
                                echo "<font color=green class='query_show_money'>".floor($row['Glost'])."</font><br/>";
                                $betscore=floor($row['BetScore']);
                                echo $betscore."<br/>";
                                echo "<font color=red class='query_show_money'>".floor($row['Glost']-$row['BetScore'])."</font><br/>";
                            }
                            ?>
                        </td>
                        <td class="true_bet"><?php echo ($row['VGOLD']=='0'?0:number_format($row['VGOLD'],2))?></td>
                        <td><?php echo number_format($row['Gwin'],2)?></td>
                        <!--会员结果  大于0 会员赢 页面显示红色 红色显示负数， 小于0 会员输 页面显示黑色 黑色不显示负数  -->
                        <td class="mem_result <?php echo ($row['M_Result']>0 || $row['M_Result']==0 ?'red':'')?>">
                            <?php
                            if($row['Cancel']==0){
                                ?>
                                <?php
                                //echo ($row['M_Result']=='0'?'':number_format($row['M_Result'],2))
                                if($row['LineType']==8){
                                    $midArr = $confirmedArr = $MidWZ= $MiddleArr = array();
                                    $MiddleArr =explode('<br>',$row['Middle']);
                                    $midArr =explode(',',$row['MID']);
                                    if($row['Confirmed']<=0){
                                        $confirmedArr = array();
                                    }else{
                                        $confirmedArr =explode(',',$row['Confirmed']);
                                    }
                                    $curWZ='';
                                    foreach($confirmedArr as $key=>$val){
                                        $curWZ=array_search($val,$midArr);
                                        $MidWZ[$curWZ]=$MiddleArr[(($curWZ+1)*3)-2];
                                    }

                                    ksort($MidWZ);

                                    if(count($MidWZ)>1){
                                        $MidWZStr = implode('<br><br>',$MidWZ);
                                    }else{
                                        $MidWZStr = $MidWZ[0];
                                    }
                                    echo '<s>'.$MidWZStr.'</s>';
                                    echo '<br><br>';
                                }

                                if($row['M_Result']<0){ //小于0 会员输 页面显示黑色 黑色不显示负数
                                    echo abs($row['M_Result']);
                                }elseif($row['M_Result']>0) { //大于0 会员赢 页面显示红色 红色显示负数
                                    echo number_format(-$row['M_Result'],2);
                                }else{
                                    echo $row['M_Result'];
                                }


                                ?>
                                <?php
                            }else{
                                ?>
                                <font color=red>
                                    <?php
                                    switch($row['Confirmed']){
                                        case 0:
                                            echo $zt=$Score20;
                                            break;
                                        case -1: // 取消
                                            echo $zt=$Score21;
                                            break;
                                        case -2: // 赛事腰斩
                                            echo $zt=$Score22;
                                            break;
                                        case -3: // 赛事改期
                                            echo $zt=$Score23;
                                            break;
                                        case -4: // 赛事延期
                                            echo $zt=$Score24;
                                            break;
                                        case -5: // 赛事延赛
                                            echo $zt=$Score25;
                                            break;
                                        case -6: // 赛事取消
                                            echo $zt=$Score26;
                                            break;
                                        case -7: // 赛事无PK加时
                                            echo $zt=$Score27;
                                            break;
                                        case -8: // 球员弃权
                                            echo $zt=$Score28;
                                            break;
                                        case -9: // 队名错误
                                            echo $zt=$Score29;
                                            break;
                                        case -10: // 主客场错误
                                            echo $zt=$Score30;
                                            break;
                                        case -11:
                                            echo $zt=$Score31;
                                            break;
                                        case -12:
                                            echo $zt=$Score32;
                                            break;
                                        case -13:
                                            echo $zt=$Score33;
                                            break;
                                        case -14:
                                            echo $zt=$Score34;
                                            break;
                                        case -15:
                                            echo $zt=$Score35;
                                            break;
                                        case -16:
                                            echo $zt=$Score36;
                                            break;
                                        case -17:
                                            echo $zt=$Score37;
                                            break;
                                        case -18:
                                            echo $zt=$Score38;
                                            break;
                                        case -19:
                                            echo $zt=$Score39;
                                            break;
                                        case -20:
                                            echo $zt=$Score40;
                                            break;
                                        case -21:
                                            echo $zt=$Score41;
                                            break;
                                    }
                                    ?>
                                </font>
                                <?php
                            }
                            ?>		  </td>
                        <td class="bet_result">
                            <?php
                            //              if (($row['RB_Show']) == 1) {
                            // 即时比分
                            if($row['LineType'] == 16){
                                if(count($dataFS[$row['MID']])==0){
                                    echo '/';
                                }elseif(count($dataFS[$row['MID']])==1){
                                    echo $dataFS[$row['MID']][0];
                                }else{
                                    foreach($dataFS[$row['MID']] as $kFS=>$vFS){
                                        echo $vFS;
                                        echo '<br/>';
                                    }
                                }
                            }elseif($row['LineType'] == 8){
                                for($t=0;$t<(sizeof($midd)-1)/3;$t++){
                                    $mysqlL="select MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where MID=".$mid[$t];
                                    $result1L = mysqli_query($dbLink,$mysqlL);
                                    $row1L = mysqli_fetch_assoc($result1L);
                                    $mysql="select M_Start,M_Time,MB_Ball,TG_Ball from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where MID=".$mid[$t];
                                    $result1 = mysqli_query($dbCenterSlaveDbLink,$mysql);
                                    $row1 = mysqli_fetch_assoc($result1);
                                    if (date('Y-m-d H:i:s') < $row1['M_Start']) {
                                        echo "<font color='blue'><b>未开赛</b></font> <br>";
                                    } else {
                                        $row1L['MB_Inball'] = $row1L['MB_Inball'] > 0 ? $row1L['MB_Inball'] : $row1['MB_Ball'];
                                        $row1L['TG_Inball'] = $row1L['TG_Inball'] > 0 ? $row1L['TG_Inball'] : $row1['TG_Ball'];
                                        echo "<font color='red'><b>" . $row1L['MB_Inball'] . ":" . $row1L['TG_Inball'] . "</b></font><br>";
                                    }
                                }
                            }else{
                                if(date('Y-m-d H:i:s') < $row1['M_Start']) {
                                    echo "<font color='blue'><b>未开赛</b></font> <br> <font color='red'><b>" . $row1['M_Time'] . "</b></font>";
                                }else{
                                    // 足球是上下两场，共90分钟+伤停补时+15分钟中场休息，
                                    // 篮球是48分钟
                                    if($row['Gtype']=="FT" ){
                                        $row1L['MB_Inball'] = $row1L['MB_Inball'] >= 0 ? $row1L['MB_Inball'] : $row1['MB_Ball'];
                                        $row1L['TG_Inball'] = $row1L['TG_Inball'] >= 0 ? $row1L['TG_Inball'] : $row1['TG_Ball'];
                                        if(time()-strtotime($row1['M_Start']) > 120*60){
                                            echo "<font color='red'><b>" . $row1L['MB_Inball'] . ":" . $row1L['TG_Inball'] . "</b></font>";
                                            echo "<font color='blue'><b>[完]</b></font>";
                                        }else{
                                            // 2H^26:05  赛程2小时，目前进行到 下半场已进行26分5秒
                                            $M_Duration = explode('^',$row1['M_Duration']);
                                            if ($M_Duration[1] > 1) {
                                                $team_active = '';
                                                switch ($M_Duration[0]) {
                                                    case '1H':
                                                        $team_active = '上半场';
                                                        break;
                                                    case '2H':
                                                        $team_active = '下半场';
                                                        break;
                                                    case 'OT':
                                                        $team_active = '加时';
                                                        break;
                                                    case 'HT':
                                                        $team_active = '半场';
                                                        break;
                                                    case 'MTIME':
                                                        $team_active = '中场';
                                                        break;
                                                }
                                            }
                                            echo "<font color='blue'><b>[".$team_active.$M_Duration[1]."]</b></font><br>";
                                            echo "<font color='red'><b>" . $row1['MB_Inball'] . ":" . $row1['TG_Inball'] . "</b></font>";
                                        }
                                    }elseif($row['Gtype']=="BK"){
                                        $MB_Inball=$row1['MB_Inball'];
                                        $row1L['MB_Inball'] = $row1L['MB_Inball'] > 0 ? $row1L['MB_Inball'] : $row1['MB_Ball'];
                                        $row1L['TG_Inball'] = $row1L['TG_Inball'] > 0 ? $row1L['TG_Inball'] : $row1['TG_Ball'];

                                        if (strlen($row1['M_Duration']) <= 1){
                                            $row1_MB_Team = explode('<font color=gray>',$row1['MB_Team'])[0];
                                            $row1_TG_Team = explode('<font color=gray>',$row1['TG_Team'])[0];
                                            $mysqlM_Duration = "select M_Duration from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and Open=1 and MB_Team='".$row1_MB_Team."' and TG_Team='".$row1_TG_Team."' and M_League='".$row1['M_League']."' and M_Start='".$row1['M_Start']."' limit 1";
                                            $resultM_Duration = mysqli_query($dbLink,$mysqlM_Duration);
                                            $rowM_Duration = mysqli_fetch_assoc($resultM_Duration);
                                            $M_Duration = explode('-',$rowM_Duration['M_Duration']);
                                        }else{
                                            $M_Duration = explode('-',$row1['M_Duration']);
                                        }
                                        if ($M_Duration[1] > 1){
                                            $team_active='' ;
                                            switch ($M_Duration[0]) {
                                                case 'Q1':
                                                    $team_active ='第一节';
                                                    break;
                                                case 'Q2':
                                                    $team_active ='第二节';
                                                    break;
                                                case 'Q3':
                                                    $team_active ='第三节';
                                                    break;
                                                case 'Q4':
                                                    $team_active ='第四节';
                                                    break;
                                                case 'H1':
                                                    $team_active ='上半场';
                                                    break;
                                                case 'H2':
                                                    $team_active ='下半场';
                                                    break;
                                                case 'OT':
                                                    $team_active ='加时';
                                                    break;
                                                case 'HT':
                                                    $team_active ='半场';
                                                    break;

                                            }
                                            $team_time ='';
                                            if($M_Duration[1] && $M_Duration[1] > 0){ // 转化时间
                                                $team_hour = floor($M_Duration[1]/3600); // 小时不要
                                                $team_minute = floor(($M_Duration[1]-3600 * $team_hour)/60);
                                                $team_second = floor((($M_Duration[1]-3600 * $team_hour) - 60 * $team_minute) % 60);
                                                $team_time = ($team_minute>9?$team_minute:"0".$team_minute).':'.($team_second>9?$team_second:"0".$team_second );
                                            }

                                            if((isset($row1['MB_Ball'])&&$row1['MB_Ball']<=0) && (isset($row1['TG_Ball'])&&$row1['TG_Ball']<=0)){
                                                $row1_MB_Team = explode('<font color=gray>',$row1['MB_Team'])[0];
                                                $row1_TG_Team = explode('<font color=gray>',$row1['TG_Team'])[0];
                                                $mysqlBall = "select MB_Ball,TG_Ball from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."`  where Type='BK' and Open=1 and MB_Team='".$row1_MB_Team."' and TG_Team='".$row1_TG_Team."' and M_League='".$row1['M_League']."' and M_Start='".$row1['M_Start']."' limit 1";
                                                $resultBall = mysqli_query($dbCenterSlaveDbLink,$mysqlBall);
                                                $rowBall = mysqli_fetch_assoc($resultBall);
                                                $row1['MB_Ball'] = $rowBall['MB_Ball'];
                                                $row1['TG_Ball'] = $rowBall['TG_Ball'];
                                            }

                                            echo "<font color='blue'><b>[" . $team_active.$team_time . "]</b></font><br>";
                                            echo "<font color='red'><b>" . $row1['MB_Ball'] . ":" . $row1['TG_Ball'] . "</b></font>";
                                        }else{

                                            if((isset($row1L['MB_Inball'])&&$row1L['MB_Inball']<=0) && (isset($row1L['TG_Inball'])&&$row1L['TG_Inball']<=0)){
                                                $row1_MB_Team = explode('<font color=gray>',$row1['MB_Team'])[0];
                                                $row1_TG_Team = explode('<font color=gray>',$row1['TG_Team'])[0];
                                                $mysqlBall = "select MB_Ball,TG_Ball from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."`  where Type='BK' and Open=1 and MB_Team='".$row1_MB_Team."' and TG_Team='".$row1_TG_Team."' and M_League='".$row1['M_League']."' and M_Start='".$row1['M_Start']."' limit 1";
                                                $resultBall = mysqli_query($dbCenterSlaveDbLink,$mysqlBall);
                                                $rowBall = mysqli_fetch_assoc($resultBall);
                                                $row1L['MB_Inball'] = $rowBall['MB_Ball'];
                                                $row1L['TG_Inball'] = $rowBall['TG_Ball'];
                                                echo "<font color='red'><b>" . $row1L['MB_Inball'] . ":" . $row1L['TG_Inball'] . "</b></font>";
                                            }else{
                                                echo "<font color='red'><b>" . $row1L['MB_Inball'] . ":" . $row1L['TG_Inball'] . "</b></font>";
                                                echo "<font color='blue'><b>[完]</b></font>";
                                            }
                                        }
                                    }
                                }
                            }
                            //              }else{
                            //
                            //              }

                            ?>


                        </td>
                        <td align="center">
                            <?php
                            if ($level=='M'){
                                ?>
                                <!-- <a class="a_link" href="javascript:CheckDEL('query.php?uid=<?php /*echo $uid*/?>&langx=<?php /*echo $langx*/?>&key=del&id=<?php /*echo $row['ID']*/?>&gid=<?php /*echo $row['MID']*/?>&seconds=<?php /*echo $seconds*/?>&username=<?php /*echo $username*/?>&date_start=<?php /*echo $date_start*/?>&page=<?php /*echo $page*/?>&sort=<?php /*echo $sort*/?>&ball=<?php /*echo $ball*/?>&type=<?php /*echo $type*/?>')"><font color=red><b>删除</b></font></a><br>-->
                                <?php
                            }
                            ?>
                            <?php
                            if ($row['Cancel']==1 || $row['Confirmed']==-51 || $row['Confirmed']==-52){
                                echo '<font color=red><b>已注销</b></font><br>';
                            }else{
                                echo '<font color=blue><b>正常</b></font><br>';
                            }
                            ?>
                            <?php
                            if ($edittype==1){
                                ?>
                                <!-- <a class="a_link" href="query_edit.php?uid=<?php /*echo $uid*/?>&langx=<?php /*echo $langx*/?>&id=<?php /*echo $row['ID']*/?>&gid=<?php /*echo $row['MID']*/?>&username=<?php /*echo $username*/?>&date_start=<?php /*echo $date_start*/?>">修改</a><br>
          <a class="a_link" href="query.php?uid=<?php /*echo $uid*/?>&langx=<?php /*echo $langx*/?>&key=modify&id=<?php /*echo $row['ID']*/?>&gid=<?php /*echo $row['MID']*/?>&seconds=<?php /*echo $seconds*/?>&username=<?php /*echo $username*/?>&date_start=<?php /*echo $date_start*/?>&page=<?php /*echo $page*/?>&sort=<?php /*echo $sort*/?>&ball=<?php /*echo $ball*/?>&type=<?php /*echo $type*/?>">对调</a>-->
                                <?php
                            }
                            ?>
                        </td>
                        <?php if ($level=='M' && $c_num[40]==1){ ?>
                            <td width="121" align="center" class="bet_order_edit">
                            <SELECT onchange=javascript:CheckSTOP(this) size=1 name=select1>
                                <option>注单处理</option>
                                <option value="query.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&key=resume&id=<?php echo $row['ID']?>&gid=<?php echo $row['MID']?>&seconds=<?php echo $seconds?>&username=<?php echo $username?>&date_start=<?php echo $date_start?>&page=<?php echo $page?>&sort=<?php echo $sort?>&ball=<?php echo $ball?>&type=<?php echo $type?>&actionname=<?php echo $Score20?>&oder_number=<?php echo $row['orderNo']?>&LineType=<?php echo $row['LineType']?>"><?php echo $Score20?></option>
                                <option value="query.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&key=cancel&id=<?php echo $row['ID']?>&gid=<?php echo $row['MID']?>&confirmed=-1&seconds=<?php echo $seconds?>&username=<?php echo $username?>&date_start=<?php echo $date_start?>&page=<?php echo $page?>&sort=<?php echo $sort?>&ball=<?php echo $ball?>&type=<?php echo $type?>&actionname=<?php echo $Score21?>&oder_number=<?php echo $row['orderNo']?>&LineType=<?php echo $row['LineType']?>" ><?php echo $Score21?></option>
                                <?php if($row['LineType'] == 8 && $row['Cancel'] == 0){ ?>
                                    <option value="query_cancel_sing_fs.php?uid=<?php echo $uid?>&id=<?php echo $row['ID']?>&username=<?php echo $username;?>&date_start=<?php echo $date_start;?>&date_end=<?php echo $date_end;?>&page=<?php echo $page;?>&sort=<?php echo $sort;?>&ball=<?php echo $ball;?>&type=<?php echo $type;?>">[单串取消]</option>
                                <?php } ?>
                                <?php if($row['LineType'] != 16) { ?>
                                    <option value="query.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&key=cancel&id=<?php echo $row['ID']?>&gid=<?php echo $row['MID']?>&confirmed=-2&seconds=<?php echo $seconds?>&username=<?php echo $username?>&date_start=<?php echo $date_start?>&page=<?php echo $page?>&sort=<?php echo $sort?>&ball=<?php echo $ball?>&type=<?php echo $type?>&actionname=<?php echo $Score22?>&oder_number=<?php echo $row['orderNo']?>"><?php echo $Score22?></option>
                                    <option value="query.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&key=cancel&id=<?php echo $row['ID']?>&gid=<?php echo $row['MID']?>&confirmed=-3&seconds=<?php echo $seconds?>&username=<?php echo $username?>&date_start=<?php echo $date_start?>&page=<?php echo $page?>&sort=<?php echo $sort?>&ball=<?php echo $ball?>&type=<?php echo $type?>&actionname=<?php echo $Score23?>&oder_number=<?php echo $row['orderNo']?>"><?php echo $Score23?></option>
                                    <option value="query.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&key=cancel&id=<?php echo $row['ID']?>&gid=<?php echo $row['MID']?>&confirmed=-4&seconds=<?php echo $seconds?>&username=<?php echo $username?>&date_start=<?php echo $date_start?>&page=<?php echo $page?>&sort=<?php echo $sort?>&ball=<?php echo $ball?>&type=<?php echo $type?>&actionname=<?php echo $Score24?>&oder_number=<?php echo $row['orderNo']?>"><?php echo $Score24?></option>
                                    <option value="query.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&key=cancel&id=<?php echo $row['ID']?>&gid=<?php echo $row['MID']?>&confirmed=-5&seconds=<?php echo $seconds?>&username=<?php echo $username?>&date_start=<?php echo $date_start?>&page=<?php echo $page?>&sort=<?php echo $sort?>&ball=<?php echo $ball?>&type=<?php echo $type?>&actionname=<?php echo $Score25?>&oder_number=<?php echo $row['orderNo']?>"><?php echo $Score25?></option>
                                    <option value="query.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&key=cancel&id=<?php echo $row['ID']?>&gid=<?php echo $row['MID']?>&confirmed=-6&seconds=<?php echo $seconds?>&username=<?php echo $username?>&date_start=<?php echo $date_start?>&page=<?php echo $page?>&sort=<?php echo $sort?>&ball=<?php echo $ball?>&type=<?php echo $type?>&actionname=<?php echo $Score26?>&oder_number=<?php echo $row['orderNo']?>"><?php echo $Score26?></option>
                                    <option value="query.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&key=cancel&id=<?php echo $row['ID']?>&gid=<?php echo $row['MID']?>&confirmed=-7&seconds=<?php echo $seconds?>&username=<?php echo $username?>&date_start=<?php echo $date_start?>&page=<?php echo $page?>&sort=<?php echo $sort?>&ball=<?php echo $ball?>&type=<?php echo $type?>&actionname=<?php echo $Score27?>&oder_number=<?php echo $row['orderNo']?>"><?php echo $Score27?></option>
                                    <option value="query.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&key=cancel&id=<?php echo $row['ID']?>&gid=<?php echo $row['MID']?>&confirmed=-8&seconds=<?php echo $seconds?>&username=<?php echo $username?>&date_start=<?php echo $date_start?>&page=<?php echo $page?>&sort=<?php echo $sort?>&ball=<?php echo $ball?>&type=<?php echo $type?>&actionname=<?php echo $Score28?>&oder_number=<?php echo $row['orderNo']?>"><?php echo $Score28?></option>
                                    <option value="query.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&key=cancel&id=<?php echo $row['ID']?>&gid=<?php echo $row['MID']?>&confirmed=-9&seconds=<?php echo $seconds?>&username=<?php echo $username?>&date_start=<?php echo $date_start?>&page=<?php echo $page?>&sort=<?php echo $sort?>&ball=<?php echo $ball?>&type=<?php echo $type?>&actionname=<?php echo $Score29?>&oder_number=<?php echo $row['orderNo']?>"><?php echo $Score29?></option>
                                    <option value="query.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&key=cancel&id=<?php echo $row['ID']?>&gid=<?php echo $row['MID']?>&confirmed=-10&seconds=<?php echo $seconds?>&username=<?php echo $username?>&date_start=<?php echo $date_start?>&page=<?php echo $page?>&sort=<?php echo $sort?>&ball=<?php echo $ball?>&type=<?php echo $type?>&actionname=<?php echo $Score30?>&oder_number=<?php echo $row['orderNo']?>"><?php echo $Score30?></option>
                                    <option value="query.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&key=cancel&id=<?php echo $row['ID']?>&gid=<?php echo $row['MID']?>&confirmed=-11&seconds=<?php echo $seconds?>&username=<?php echo $username?>&date_start=<?php echo $date_start?>&page=<?php echo $page?>&sort=<?php echo $sort?>&ball=<?php echo $ball?>&type=<?php echo $type?>&actionname=<?php echo $Score31?>&oder_number=<?php echo $row['orderNo']?>"><?php echo $Score31?></option>
                                    <option value="query.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&key=cancel&id=<?php echo $row['ID']?>&gid=<?php echo $row['MID']?>&confirmed=-12&seconds=<?php echo $seconds?>&username=<?php echo $username?>&date_start=<?php echo $date_start?>&page=<?php echo $page?>&sort=<?php echo $sort?>&ball=<?php echo $ball?>&type=<?php echo $type?>&actionname=<?php echo $Score32?>&oder_number=<?php echo $row['orderNo']?>"><?php echo $Score32?></option>
                                    <option value="query.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&key=cancel&id=<?php echo $row['ID']?>&gid=<?php echo $row['MID']?>&confirmed=-13&seconds=<?php echo $seconds?>&username=<?php echo $username?>&date_start=<?php echo $date_start?>&page=<?php echo $page?>&sort=<?php echo $sort?>&ball=<?php echo $ball?>&type=<?php echo $type?>&actionname=<?php echo $Score33?>&oder_number=<?php echo $row['orderNo']?>"><?php echo $Score33?></option>
                                    <option value="query.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&key=cancel&id=<?php echo $row['ID']?>&gid=<?php echo $row['MID']?>&confirmed=-14&seconds=<?php echo $seconds?>&username=<?php echo $username?>&date_start=<?php echo $date_start?>&page=<?php echo $page?>&sort=<?php echo $sort?>&ball=<?php echo $ball?>&type=<?php echo $type?>&actionname=<?php echo $Score34?>&oder_number=<?php echo $row['orderNo']?>"><?php echo $Score34?></option>
                                    <option value="query.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&key=cancel&id=<?php echo $row['ID']?>&gid=<?php echo $row['MID']?>&confirmed=-15&seconds=<?php echo $seconds?>&username=<?php echo $username?>&date_start=<?php echo $date_start?>&page=<?php echo $page?>&sort=<?php echo $sort?>&ball=<?php echo $ball?>&type=<?php echo $type?>&actionname=<?php echo $Score35?>&oder_number=<?php echo $row['orderNo']?>"><?php echo $Score35?></option>
                                    <option value="query.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&key=cancel&id=<?php echo $row['ID']?>&gid=<?php echo $row['MID']?>&confirmed=-16&seconds=<?php echo $seconds?>&username=<?php echo $username?>&date_start=<?php echo $date_start?>&page=<?php echo $page?>&sort=<?php echo $sort?>&ball=<?php echo $ball?>&type=<?php echo $type?>&actionname=<?php echo $Score36?>&oder_number=<?php echo $row['orderNo']?>"><?php echo $Score36?></option>
                                    <option value="query.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&key=cancel&id=<?php echo $row['ID']?>&gid=<?php echo $row['MID']?>&confirmed=-17&seconds=<?php echo $seconds?>&username=<?php echo $username?>&date_start=<?php echo $date_start?>&page=<?php echo $page?>&sort=<?php echo $sort?>&ball=<?php echo $ball?>&type=<?php echo $type?>&actionname=<?php echo $Score37?>&oder_number=<?php echo $row['orderNo']?>"><?php echo $Score37?></option>
                                    <option value="query.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&key=cancel&id=<?php echo $row['ID']?>&gid=<?php echo $row['MID']?>&confirmed=-18&seconds=<?php echo $seconds?>&username=<?php echo $username?>&date_start=<?php echo $date_start?>&page=<?php echo $page?>&sort=<?php echo $sort?>&ball=<?php echo $ball?>&type=<?php echo $type?>&actionname=<?php echo $Score38?>&oder_number=<?php echo $row['orderNo']?>"><?php echo $Score38?></option>
                                    <option value="query.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&key=cancel&id=<?php echo $row['ID']?>&gid=<?php echo $row['MID']?>&confirmed=-19&seconds=<?php echo $seconds?>&username=<?php echo $username?>&date_start=<?php echo $date_start?>&page=<?php echo $page?>&sort=<?php echo $sort?>&ball=<?php echo $ball?>&type=<?php echo $type?>&actionname=<?php echo $Score39?>&oder_number=<?php echo $row['orderNo']?>"><?php echo $Score39?></option>
                                    <option value="query.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&key=cancel&id=<?php echo $row['ID']?>&gid=<?php echo $row['MID']?>&confirmed=-20&seconds=<?php echo $seconds?>&username=<?php echo $username?>&date_start=<?php echo $date_start?>&page=<?php echo $page?>&sort=<?php echo $sort?>&ball=<?php echo $ball?>&type=<?php echo $type?>&actionname=<?php echo $Score40?>&oder_number=<?php echo $row['orderNo']?>"><?php echo $Score40?></option>
                                    <option value="query.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&key=cancel&id=<?php echo $row['ID']?>&gid=<?php echo $row['MID']?>&confirmed=-21&seconds=<?php echo $seconds?>&username=<?php echo $username?>&date_start=<?php echo $date_start?>&page=<?php echo $page?>&sort=<?php echo $sort?>&ball=<?php echo $ball?>&type=<?php echo $type?>&actionname=<?php echo $Score41?>&oder_number=<?php echo $row['orderNo']?>"><?php echo $Score41?></option>
                                <?php } ?>
                            </SELECT><br><br>
                            <?php if($row['LineType'] == 16) {
                                if(count($dataFS[$row['MID']])>0&&$row['M_Result']==''){
                                    ?>
                                    <a class="a_link" href="query.php?&key=sendPrizeFS&uid=<?php echo $uid?>&id=<?php echo $row['ID']?>&langx=<?php echo $langx?>&gtype=<?php echo $row['Gtype']?>&gid=<?php echo $row['MID']?>&date_start=<?php echo $date_start;?>&date_end=<?php echo $date_end?>">结算</a>
                                <?php 	}
                            }elseif($row['LineType'] == 8) { ?>
                                <a class="a_link" href="../clearing/clearingP3.php?uid=<?php echo $uid?>&id=<?php echo $row['ID']?>&langx=<?php echo $langx?>&gtype=<?php echo $row['Gtype']?>&gid=<?php echo $row['MID']?>&date_start=<?php echo $row['M_Date']?>">结算</a>
                            <?php  }else{  ?>
                                <a class="a_link" href="../score/set_score.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&gtype=<?php echo $row['Gtype']?>&gid=<?php echo $row['MID']?>&date_start=<?php echo $row['M_Date']?>">结算</a>
                            <?php  } ?>
                            <?php

                            // 当天盘口日期 后一天的2点30分，会自动生成历史报表。注单结算时间（自动结算、后台手工结算）超过这个时间则需要重新生成此会员的这一天的历史报表
                            $make_history_date = date("Y-m-d",strtotime("+1 day",strtotime($row['M_Date']))).' 02:30:00'; // 计划任务自动生成历史报表的时间
                            if (($row['M_Date'] < date('Y-m-d')) and ($row['sendAwardTime'] > $make_history_date ) ){
                                echo "<br>";
                                echo "<a class=\"a_link\" href=\"history_daily_report_general_hg_member_renew.php?userid={$row['Userid']}&username={$row['M_Name']}&StartTime={$row['M_Date']}\">重新生成此会员当天盘口历史报表</a>";
                            }

                            ?>

                            <?php
                            //原始图片地址
                            $betImgAddress = BROWSER_IP.'/images/order_image/'.date("Ymd",strtotime($row['BetTime'])).'/'.$row['Userid'].'/'.$row['orderNo'].'.jpg';
                            ?>
                            <!--<a class="a_link" href="<?php /*echo BROWSER_IP*/?>/images/order_image/<?php /*echo date("Ymd",strtotime($row['BetTime'])).'/'.$row['Userid'].'/'.$row['orderNo']*/?>.jpg" target="_blank">原始图片</a>-->
                            <a class="a_link" href="javascript:;" onclick="showOrderImages('<?php echo $betImgAddress?>')" >原始图片</a>
                            </td><?php } ?>
                    </tr>
                    <?php
                }
                ?>
            </table>
        </div>

    </div>
</FORM>
<script charset="utf-8" src="../../../js/agents/jquery.js" ></script>
<script type="text/javascript" src="../../../js/agents/common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../../js/agents/register/laydate.min.js"></script>
<script type="text/javascript" src="../../../js/agents/layer/layer.js" ></script>
<script language=javascript>
    // 昨日，选择时同步提交表单中的内容，并显示页面数据
    function query_date( str ) {
        var uid = '<?php echo $uid;?>';
        var langx = '<?php echo $langx;?>';
        var url = 'query.php';
        var date_start = str+' 00:00:00';
        var date_end = str+' 23:59:59';
        var username = myFORM.username.value;
        var result_type = myFORM.result_type.value;
        var sort = myFORM.sort.value;
        var ball = myFORM.ball.value;
        var type = myFORM.type.value;
        var seconds = myFORM.seconds.value;
        var page = myFORM.page.value;
        var show_detail_money = $('input[name="show_detail_money"]').val() ;
        var form = $('<form></form>');
        form.attr('action',url);
        form.attr('method', 'post');
        form.attr('target', '_self');
        form.append("<input type='hidden' name='uid' value='"+uid+"'>");
        form.append("<input type='hidden' name='langx' value='"+langx+"'>");
        form.append("<input type='hidden' name='date_start' value='"+date_start+"'>");
        form.append("<input type='hidden' name='date_end' value='"+date_end+"'>");
        form.append("<input type='hidden' name='username' value='"+username+"'>");
        form.append("<input type='hidden' name='result_type' value='"+result_type+"'>");
        form.append("<input type='hidden' name='sort' value='"+sort+"'>");
        form.append("<input type='hidden' name='ball' value='"+ball+"'>");
        form.append("<input type='hidden' name='type' value='"+type+"'>");
        form.append("<input type='hidden' name='page' value='"+page+"'>");
        form.append("<input type='hidden' name='seconds' value='"+seconds+"'>");
        form.append("<input type='hidden' name='show_detail_money' value='"+show_detail_money+"'>");
        $(document.body).append(form);
        form.submit();

    }
    // str 跳转链接，txt 提示文本
    function CheckSTOP(obj){
        var str =obj.options[obj.selectedIndex].value ; // this.options[this.selectedIndex].value
        var txt =obj.options[obj.selectedIndex].text ; // this.options[this.selectedIndex].text
        if(str.indexOf("query_cancel_sing_fs") == -1){
            if(confirm('确认对注单进行'+txt+'吗？')){
                document.location=str;
            }
        }else{
            document.location=str;
        }
    }
    function CheckDEL(str){
        if(confirm("是否要删除此注单 ?"))
            document.location=str;
    }
    function reload(){
        location.reload();
    }

    var second="<?php echo $seconds?>"
    function auto_refresh(){
        if (second==1){
            window.location.href='query.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&lv=<?php echo $lv?>&seconds=<?php echo $seconds?>&username=<?php echo $username?>&date_start=<?php echo $date_start?>&date_end=<?php echo $date_end?>&page=<?php echo $page?>&sort=<?php echo $sort?>&ball=<?php echo $ball?>&type=<?php echo $type?>&result_type=<?php echo $result_type?>&show_detail_money='+$('input[name="show_detail_money"]').val() ; //刷新页面
        }else{
            second-=1
            curmin=Math.floor(second)
            curtime=curmin+"秒"
            ShowTime.innerText=curtime
            setTimeout("auto_refresh()",1000)
        }
    }
    function showOrderImages(url) {
        str = '<img src="'+url+'">' ;
        layer.open({
            type: 1,
            title: false,
            closeBtn: 0,
            area:['800px', '600px'], //宽高
            shade: 0.5,
            skin: 'layui-layer-molv', //没有背景色
            shadeClose: true,
            content: str
        });
    }

    // 显示隐藏金额
    function showDetailMoney(obj) {
        var c_flag = $(obj).attr('checked');
        if(c_flag){
            $('.query_show_money').show();
            $(obj).val('1');
        }else{
            $('.query_show_money').hide();
            $(obj).val('0');
        }
    }
</script>
</body>
</html>
