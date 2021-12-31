<?php
session_start();
include ("../include/address.mem.php");
require ("../include/config.inc.php");
require_once("../../../../common/sportCenterData.php");
require ("../include/define_function_list.inc.php");
require ("../include/traditional.zh-cn.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

ini_set('display_errors','OFF');

if( (!isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG) && $_SESSION['admin_level'] != 'D' ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

//echo '<pre>';
//print_r($_REQUEST);
//echo '</pre>';

$uid=$_REQUEST["uid"];
$langx=$_REQUEST["langx"];
$loginname=$_SESSION['UserName'];
$lv = isset($_POST['lv'])? $_POST['lv']:""; // 管理员层级
$datatime=date('Y-m-d H:i:s');
$date_day = date('Y-m-d'); // 今日

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
    $order_sql = "orderNo = '$betordernumber' and ";
}
if ($seconds==''){
	$seconds=180;
}

if ($username=="" and $betordernumber==''){
    $name='';
    echo "<script>alert('请点填写注单号，或用户名，点击确认方可查询历史注单!');</script>";
}else{
    $name="M_Name='$username' and ";
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


$checkout=$_REQUEST['checkout'];
if ($checkout=='0'){
	$check="and M_Result=''";
}else if ($checkout==''){
	$check="";
}

// 判断查询哪张表，分3种情况（美东时间1点转移数据到历史注单）
//  1 如果开始时间、结束时间都是15天前的日期，则查历史注单表
//  2 如果开始时间是15天前的日期，结束时间为15内的日期，则查询2张表（组合查询）
//  3 开始时间、结束时间都是15天内的日期，则只查询注单表
if ($date_start==""){
    $date_start=date("Y-m-d 00:00:00");
}
if ($date_end==""){
    $date_end=date("Y-m-d 23:59:59");
}
$Last = time() - 15*24*60*60; // 15天前
$stop_time = date("Y-m-d 00:00:00", $Last);


if ($date_start < $stop_time && $date_end < $stop_time){ // 15天前的注单查询
    // 查询历史注单表，做限制
    // 最多只能查询从上个月的注单数据
    $last= strtotime("-1 month", time());
//    $last_lastday = date("Y-m-t", $last);//上个月最后一天
    $last_firstday = date('Y-m-01 00:00:00', $last);//上个月第一天
    if ($date_start < $last_firstday){
        echo "<script>alert('限制可查最早日期：".$last_firstday."，点击确定显示数据');</script>";
        $date_start = $last_firstday;
    }
    $mysql="SELECT ID,MID,Userid,Active,orderNo,LineType,Mtype,Pay_Type,M_Date,BetTime,BetScore,CurType,Middle,Middle_tw,Middle_en,BetType,M_Place,M_Rate,M_Name,Gwin,Glost,VGOLD,M_Result,A_Result,B_Result,C_Result,D_Result,T_Result,OpenType,OddsType,ShowType,Cancel,Ptype,MB_ball,TG_ball,Edit,Confirmed,Gtype,Danger,Checked,sendAwardTime,sendAwardIsAuto,playSource,betid from ".DBPREFIX."web_report_history_data where $order_sql $name ((BetTime>='$date_start' and BetTime<='$date_end') or (M_Date>='$date_start' and M_Date<='$date_end')) $match $wtype $n_name $cancel $check $result order by $sort desc"; // 也需要根据开赛时间(M_Date)筛选

}elseif($date_start > $stop_time && $date_end > $stop_time){ // 15天内的注单查询

    $mysql="SELECT ID,MID,Userid,Active,orderNo,LineType,Mtype,Pay_Type,M_Date,BetTime,BetScore,CurType,Middle,Middle_tw,Middle_en,BetType,M_Place,M_Rate,M_Name,Gwin,Glost,VGOLD,M_Result,A_Result,B_Result,C_Result,D_Result,T_Result,OpenType,OddsType,ShowType,Cancel,Ptype,MB_ball,TG_ball,Edit,Confirmed,Gtype,Danger,Checked,sendAwardTime,sendAwardIsAuto,playSource,betid from ".DBPREFIX."web_report_data where $order_sql $name ((BetTime>='$date_start' and BetTime<='$date_end') or (M_Date>='$date_start' and M_Date<='$date_end')) $match $wtype $n_name $cancel $check $result order by $sort desc"; // 也需要根据开赛时间(M_Date)筛选

}else{ // 历史注单表、注单表组合查询

    $last= strtotime("-1 month", time());
//    $last_lastday = date("Y-m-t", $last);//上个月最后一天
    $last_firstday = date('Y-m-01 00:00:00', $last);//上个月第一天
    if ($date_start < $last_firstday){
        echo "<script>alert('限制可查最早日期：".$last_firstday."，点击确定显示数据');</script>";
        $date_start = $last_firstday;
    }

    $mysql="
    (SELECT ID,MID,Userid,Active,orderNo,LineType,Mtype,Pay_Type,M_Date,BetTime,BetScore,CurType,Middle,Middle_tw,Middle_en,BetType,M_Place,M_Rate,M_Name,Gwin,Glost,VGOLD,M_Result,A_Result,B_Result,C_Result,D_Result,T_Result,OpenType,OddsType,ShowType,Cancel,Ptype,MB_ball,TG_ball,Edit,Confirmed,Gtype,Danger,Checked,sendAwardTime,sendAwardIsAuto,playSource,betid from ".DBPREFIX."web_report_history_data where $order_sql $name ((BetTime>='$date_start' and BetTime<='$date_end') or (M_Date>='$date_start' and M_Date<='$date_end')) $match $wtype $n_name $cancel $check $result)
    UNION
    (SELECT ID,MID,Userid,Active,orderNo,LineType,Mtype,Pay_Type,M_Date,BetTime,BetScore,CurType,Middle,Middle_tw,Middle_en,BetType,M_Place,M_Rate,M_Name,Gwin,Glost,VGOLD,M_Result,A_Result,B_Result,C_Result,D_Result,T_Result,OpenType,OddsType,ShowType,Cancel,Ptype,MB_ball,TG_ball,Edit,Confirmed,Gtype,Danger,Checked,sendAwardTime,sendAwardIsAuto,playSource,betid from ".DBPREFIX."web_report_data where $order_sql $name ((BetTime>='$date_start' and BetTime<='$date_end') or (M_Date>='$date_start' and M_Date<='$date_end')) $match $wtype $n_name $cancel $check $result)
    order by $sort desc 
    ";

}

if ($betordernumber != '' || $username!=''){

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
        }
    }

    $cou=mysqli_num_rows($result);
    $page_size=50;
    $page_count=ceil($cou/$page_size);
    $offset=$page*$page_size;
    $mysql=$mysql."  limit $offset,$page_size;";
//    echo '<pre>';
//    echo $mysql;
//    echo '</pre>';
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

}

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
        <td><div class="query_bet_title">注单回顾</div></td>
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
          <span style="color: red">*会员帐号:</span>
          <input type=TEXT name="username" size=10 value="<?php echo $username?>" minlength="5" maxlength="15" class="za_text_auto" style="width: 100px">
          <input type=SUBMIT name="btn_SUBMIT" value="确认" class="za_button">

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
        <div style="color: red; font-size: 14px; font-weight: bold; padding-left: 400px;">
            注意：<br>
            1、查询条件开始时间只能查到上个月1号<br>
            2、查询条件会员账号必填<br><br>
        </div>
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
              //投注来源:0未知,1pc旧版,2pc新版,3苹果,4安卓
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
                if($row['BetTime'] < $row['M_Date']){ // 明天和明天以后的赛事需要显示日期
                    echo $midd[0].' &nbsp;'.substr($row['M_Date'],5).'<br>';
                }else{
                    echo $midd[0].'<br>';
                }

            }else{
                echo $midd[3*$t].'<br>';
            }

			$mysql="select MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where MID=".$mid[$t];
			$result1 = mysqli_query($dbLink,$mysql);
			$row1 = mysqli_fetch_assoc($result1);

if ($row1["MB_Inball"]=='-1'){
	     $font_a3='<font color="#009900"><b>'.$Score1.'</b></font>&nbsp;';
         $font_a4='<font color="#009900"><b>'.$Score1.'</b></font>&nbsp;';
}else if ($row1["MB_Inball"]=='-2'){     
	     $font_a3='<font color="#009900"><b>'.$Score2.'</b></font>&nbsp;';
         $font_a4='<font color="#009900"><b>'.$Score2.'</b></font>&nbsp;';	
}else if ($row1["MB_Inball"]=='-3'){      
	     $font_a3='<font color="#009900"><b>'.$Score3.'</b></font>&nbsp;';
         $font_a4='<font color="#009900"><b>'.$Score3.'</b></font>&nbsp;';	
}else if ($row1["MB_Inball"]=='-4'){     
	     $font_a3='<font color="#009900"><b>'.$Score4.'</b></font>&nbsp;';
         $font_a4='<font color="#009900"><b>'.$Score4.'</b></font>&nbsp;';
}else if ($row1["MB_Inball"]=='-5'){     
	     $font_a3='<font color="#009900"><b>'.$Score5.'</b></font>&nbsp;';
         $font_a4='<font color="#009900"><b>'.$Score5.'</b></font>&nbsp;';	  
}else if ($row1["MB_Inball"]=='-6'){     
	     $font_a3='<font color="#009900"><b>'.$Score6.'</b></font>&nbsp;';
         $font_a4='<font color="#009900"><b>'.$Score6.'</b></font>&nbsp;';	  
}else if ($row1["MB_Inball"]=='-7'){     
	     $font_a3='<font color="#009900"><b>'.$Score7.'</b></font>&nbsp;';
         $font_a4='<font color="#009900"><b>'.$Score7.'</b></font>&nbsp;';	  
}else if ($row1["MB_Inball"]=='-8'){     
	     $font_a3='<font color="#009900"><b>'.$Score8.'</b></font>&nbsp;';
         $font_a4='<font color="#009900"><b>'.$Score8.'</b></font>&nbsp;';	  
}else if ($row1["MB_Inball"]=='-9'){     
	     $font_a3='<font color="#009900"><b>'.$Score9.'</b></font>&nbsp;';
         $font_a4='<font color="#009900"><b>'.$Score9.'</b></font>&nbsp;';	  
}else if ($row1["MB_Inball"]=='-10'){     
	     $font_a3='<font color="#009900"><b>'.$Score10.'</b></font>&nbsp;';
         $font_a4='<font color="#009900"><b>'.$Score10.'</b></font>&nbsp;';
}else if ($row1["MB_Inball"]=='-11'){
	     $font_a3='<font color="#009900"><b>'.$Score11.'</b></font>&nbsp;';
         $font_a4='<font color="#009900"><b>'.$Score11.'</b></font>&nbsp;';
}else if ($row1["MB_Inball"]=='-12'){     
	     $font_a3='<font color="#009900"><b>'.$Score12.'</b></font>&nbsp;';
         $font_a4='<font color="#009900"><b>'.$Score12.'</b></font>&nbsp;';	
}else if ($row1["MB_Inball"]=='-13'){      
	     $font_a3='<font color="#009900"><b>'.$Score13.'</b></font>&nbsp;';
         $font_a4='<font color="#009900"><b>'.$Score13.'</b></font>&nbsp;';	
}else if ($row1["MB_Inball"]=='-14'){     
	     $font_a3='<font color="#009900"><b>'.$Score14.'</b></font>&nbsp;';
         $font_a4='<font color="#009900"><b>'.$Score14.'</b></font>&nbsp;';
}else if ($row1["MB_Inball"]=='-15'){     
	     $font_a3='<font color="#009900"><b>'.$Score15.'</b></font>&nbsp;';
         $font_a4='<font color="#009900"><b>'.$Score15.'</b></font>&nbsp;';	  
}else if ($row1["MB_Inball"]=='-16'){     
	     $font_a3='<font color="#009900"><b>'.$Score16.'</b></font>&nbsp;';
         $font_a4='<font color="#009900"><b>'.$Score16.'</b></font>&nbsp;';	  
}else if ($row1["MB_Inball"]=='-17'){     
	     $font_a3='<font color="#009900"><b>'.$Score17.'</b></font>&nbsp;';
         $font_a4='<font color="#009900"><b>'.$Score17.'</b></font>&nbsp;';	  
}else if ($row1["MB_Inball"]=='-18'){     
	     $font_a3='<font color="#009900"><b>'.$Score18.'</b></font>&nbsp;';
         $font_a4='<font color="#009900"><b>'.$Score18.'</b></font>&nbsp;';	  
}else if ($row1["MB_Inball"]=='-19'){     
	     $font_a3='<font color="#009900"><b>'.$Score19.'</b></font>&nbsp;';
         $font_a4='<font color="#009900"><b>'.$Score19.'</b></font>&nbsp;';	  	 	  
}else{
	$font_a3='<font color="#009900"><b>'.$row1["TG_Inball"].'</b> : <b>'.$row1["MB_Inball"].'</b></font>&nbsp;';
	$font_a4='<font color="#009900"><b>'.$row1["MB_Inball"].'</b> : <b>'.$row1["TG_Inball"].'</b></font>&nbsp;';
    $font_a1='<font color="#009900"><b>'.$row1["TG_Inball_HR"].'</b> : <b>'.$row1["MB_Inball_HR"].'</b></font>&nbsp; ';
    $font_a2='<font color="#009900"><b>'.$row1["MB_Inball_HR"].'</b> : <b>'.$row1["TG_Inball_HR"].'</b></font>&nbsp; ';
}

            // echo $midd[3*$t+1].'<br>';
            if($t==0){ // 明天和明天以后的赛事需要显示日期
                if($row['BetTime'] < $row['M_Date']){ // 明天和明天以后的赛事需要显示日期
                    echo $midd[1].' &nbsp;'.substr($row['M_Date'],5).'<br>';
                }else{
                    echo $midd[1].'<br>';
                }

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

            if($t==0){ // 明天和明天以后的赛事需要显示日期
                if($row['BetTime'] < $row['M_Date']){ // 明天和明天以后的赛事需要显示日期
                    echo $midd[2].' &nbsp;'.substr($row['M_Date'],5).'<br>';
                }else{
                    echo $midd[2].'<br>';
                }

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

        $mysqlL="select Checked,MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where MID=".$row['MID'];
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
                          $mysqlL1="select MB_Ball,TG_Ball from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where MID=".$mid[$t];
                          $resultL1 = mysqli_query($dbCenterSlaveDbLink,$mysqlL1);
                          $rowL1 = mysqli_fetch_assoc($resultL1);
                          $mysql="select M_Start,M_Time,MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where MID=".$mid[$t];
                          $result1 = mysqli_query($dbLink,$mysql);
                          $row1 = mysqli_fetch_assoc($result1);
                          if (date('Y-m-d H:i:s') < $row1['M_Start']) {
                              echo "<font color='blue'><b>未开赛</b></font> <br>";
                          } else {
                              $row1['MB_Inball'] = $row1['MB_Inball'] > 0 ? $row1['MB_Inball'] : $rowL1['MB_Ball'];
                              $row1['TG_Inball'] = $row1['TG_Inball'] > 0 ? $row1['TG_Inball'] : $rowL1['TG_Ball'];
                              echo "<font color='red'><b>" . $row1['MB_Inball'] . ":" . $row1['TG_Inball'] . "</b></font><br>";
                          }
                      }
                  }else{
                      if(date('Y-m-d H:i:s') < $row1['M_Start']) {
                          echo "<font color='blue'><b>未开赛</b></font> <br> <font color='red'><b>" . $row1['M_Time'] . "</b></font>";
                      }else{
                          // 足球是上下两场，共90分钟+伤停补时+15分钟中场休息，
                          // 篮球是48分钟
                          if($row['Gtype']=="FT" ){
								$row1['MB_Inball'] = $row1['MB_Inball'] >= 0 ? $row1['MB_Inball'] : $row1['MB_Ball'];
								$row1['TG_Inball'] = $row1['TG_Inball'] >= 0 ? $row1['TG_Inball'] : $row1['TG_Ball'];
                              if(time()-strtotime($row1['M_Start']) > 120*60){
                                  echo "<font color='red'><b>" . $row1['MB_Inball'] . ":" . $row1['TG_Inball'] . "</b></font>";
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
                          		$row1['MB_Inball'] = $row1['MB_Inball'] > 0 ? $row1['MB_Inball'] : $row1['MB_Ball'];
                          		$row1['TG_Inball'] = $row1['TG_Inball'] > 0 ? $row1['TG_Inball'] : $row1['TG_Ball'];

                              if (strlen($row1['M_Duration']) <= 1){
                                  $row1_MB_Team = explode('<font color=gray>',$row1['MB_Team'])[0];
                                  $row1_TG_Team = explode('<font color=gray>',$row1['TG_Team'])[0];
                                  $mysqlM_Duration = "select M_Duration from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."`  where Type='BK' and Open=1 and MB_Team='".$row1_MB_Team."' and TG_Team='".$row1_TG_Team."' and M_League='".$row1['M_League']."' and M_Start='".$row1['M_Start']."' limit 1";
                                  $resultM_Duration = mysqli_query($dbCenterSlaveDbLink,$mysqlM_Duration);
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
                              		
                              	if((isset($row1['MB_Inball'])&&$row1['MB_Inball']<=0) && (isset($row1['TG_Inball'])&&$row1['TG_Inball']<=0)){
                                    $row1_MB_Team = explode('<font color=gray>',$row1['MB_Team'])[0];
                                    $row1_TG_Team = explode('<font color=gray>',$row1['TG_Team'])[0];
									$mysqlBall = "select MB_Ball,TG_Ball from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."`  where Type='BK' and Open=1 and MB_Team='".$row1_MB_Team."' and TG_Team='".$row1_TG_Team."' and M_League='".$row1['M_League']."' and M_Start='".$row1['M_Start']."' limit 1";
									$resultBall = mysqli_query($dbCenterSlaveDbLink,$mysqlBall);
									$rowBall = mysqli_fetch_assoc($resultBall);
									$row1['MB_Inball'] = $rowBall['MB_Ball'];
									$row1['TG_Inball'] = $rowBall['TG_Ball'];	
									echo "<font color='red'><b>" . $row1['MB_Inball'] . ":" . $row1['TG_Inball'] . "</b></font>";
                                }else{
									echo "<font color='red'><b>" . $row1['MB_Inball'] . ":" . $row1['TG_Inball'] . "</b></font>";
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
        var url = 'query_review.php';
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
            window.location.href='query_review.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&lv=<?php echo $lv?>&seconds=<?php echo $seconds?>&username=<?php echo $username?>&date_start=<?php echo $date_start?>&date_end=<?php echo $date_end?>&page=<?php echo $page?>&sort=<?php echo $sort?>&ball=<?php echo $ball?>&type=<?php echo $type?>&result_type=<?php echo $result_type?>&show_detail_money='+$('input[name="show_detail_money"]').val() ; //刷新页面
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
