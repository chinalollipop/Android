<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include ("../../agents/include/address.mem.php");
// echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
require_once ("../../agents/include/config.inc.php");
include_once ("../include/redis.php");
include_once ("../../../../common/mg/api.php");
include_once ("../../../../common/bbin/config.php"); // 搜索用户名

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$uid=$_REQUEST["uid"];
$langx=$_SESSION["langx"];
$loginname=$_SESSION['UserName'];
$lv=$_REQUEST["lv"];
require ("../../agents/include/traditional.$langx.inc.php");

$subUser=$_SESSION['SubUser'];
if ($subUser==0){
    $name=$_SESSION['UserName'];
}else{
    $name=$_SESSION['SubName'];
}

$order_code =trim($_REQUEST['order_code']); // 转账单号
$memname =trim($_REQUEST['user_account']); // 会员帐号
$gold=$_REQUEST['gold'];
$rtype=$_REQUEST['type']; // 彩票(cp)，真人(ag)，开元棋牌（ky）,og, mw, cq, fg
$date_start=$_REQUEST['date_start'];
$date_end=$_REQUEST['date_end'];
$page=$_REQUEST["page"];
if ($order_code==''){
    $o_code="";
}else{
    $o_code="and Order_Code='$order_code'";
}
if ($memname==''){ // 帐号
    $mem="";
}else{
    $mem="and UserName='$memname'";
}
if ($date_start==''){
    $date_start=date('Y-m-d');
}
if ($date_end==''){
    //$date_end=date('Y-m-d',time()+86400);
    $date_end=date('Y-m-d');
}
if ($rtype=='ag'){ // 真人
    $type_title ='AG';
    $type="and (`From`='ag' or `To`='ag') ";
}else if($rtype == 'og'){ // OG
    $type_title ='OG';
    $type="and (`From`='og' or `To`='og') ";
}else if($rtype == 'bbin'){ // BBIN
    $type_title ='BBIN';
    $type="and (`From`='bbin' or `To`='bbin') ";
}else if($rtype == 'cp'){ // 彩票
    $type_title ='彩票';
    $type="and (`From`='cp' or `To`='cp') ";
}else if($rtype == 'sc'){ // 皇冠体育
    $type_title ='皇冠体育';
    $type="and (`From`='sc' or `To`='sc') ";
}else if($rtype == 'gmcp'){ // 三方彩票
    $type_title ='国民彩票';
    $type="and (`From`='gmcp' or `To`='gmcp') ";
}else if($rtype == 'ky'){
    $type_title ='开元棋牌';
    $type="and (`From`='ky' or `To`='ky') ";
}else if($rtype == 'ff'){
    $type_title ='皇冠棋牌';
    $type="and (`From`='ff' or `To`='ff') ";
}else if($rtype == 'vg'){
    $type_title ='VG棋牌';
    $type="and (`From`='vg' or `To`='vg') ";
}else if($rtype == 'ly'){
    $type_title ='乐游棋牌';
    $type="and (`From`='ly' or `To`='ly') ";
}else if($rtype == 'kl'){
    $type_title ='快乐棋牌';
    $type="and (`From`='kl' or `To`='kl') ";
}else if($rtype == 'mg'){
    $type_title ='MG电子';
    $type="and (`From`='mg' or `To`='mg') ";
}else if($rtype == 'cq'){
    $type_title ='CQ9电子';
    $type="and (`From`='cq' or `To`='cq') ";
}else if($rtype == 'avia'){
    $type_title ='泛亚电竞';
    $type="and (`From`='avia' or `To`='avia') ";
}else if($rtype == 'fire'){
    $type_title ='雷火电竞';
    $type="and (`From`='fire' or `To`='fire') ";
}else if($rtype == 'mw'){
    $type_title ='MW电子';
    $type="and (`From`='mw' or `To`='mw') ";
}else if($rtype == 'fg'){
    $type_title ='FG电子';
    $type="and (`From`='fg' or `To`='fg') ";
}

if ($page==''){
    $page=0;
}
$date=date('Y-m-d H:i:s');


//$sql="select ".DBPREFIX."web_sys800_data.*,".DBPREFIX.MEMBERTABLE.".money from ".DBPREFIX."web_sys800_data,".DBPREFIX.MEMBERTABLE." where ".DBPREFIX."web_sys800_data.userid=".DBPREFIX.MEMBERTABLE.".ID $o_code $mem $type and ".DBPREFIX."web_sys800_data.AddDate>='$date_start' and ".DBPREFIX."web_sys800_data.AddDate<='$date_end' and ".DBPREFIX."web_sys800_data.Checked=1 and ".DBPREFIX."web_sys800_data.Type ='Q' order by ID desc";
$sql="select * from ".DBPREFIX."web_sys800_data where 1 and Checked=1 and `Type` ='Q' $o_code $mem $type and AddDate>='$date_start' and AddDate<='$date_end' order by ID desc";
//echo $sql;
$result = mysqli_query($dbLink,$sql);
$cou=mysqli_num_rows($result);
$page_size=50;
$page_count=ceil($cou/$page_size);
$offset=$page*$page_size;
$mysql=$sql."  limit $offset,$page_size;";
// echo $mysql ;
$result = mysqli_query($dbLink,$mysql);
if ($cou==0){
    $page_count=1;
}

if($cou !=0 && $memname !=''){ // 只有该会员帐号存在时才需要查询会员的ag余额和彩票余额
    $acsql = "select ID,UserName,Money from ".DBPREFIX.MEMBERTABLE." where UserName='$memname'";
    $acresult = mysqli_query($dbLink,$acsql);
    $acrow = mysqli_fetch_assoc($acresult);
    $hgmoney = number_format($acrow['Money'],2); // 体育余额
    $hgId=$acrow['ID']; // 皇冠 id
    if($rtype=='cp'){ // 查询彩票余额
        $cpMasterDbLink = @mysqli_connect($database['cpDefault']['host'],$database['cpDefault']['user'],$database['cpDefault']['password'],$database['cpDefault']['dbname'],$database['cpDefault']['port']) or die("mysqli connect error".mysqli_connect_error()) ;
        $cpsql = "select lcurrency from ".$database['cpDefault']['prefix']."user where hguid=".$hgId;
        $cpresult = mysqli_query($cpMasterDbLink,$cpsql);
        $cprow = mysqli_fetch_assoc($cpresult);
        $cpcou = mysqli_num_rows($cpresult);
        $cpFund = $cprow['lcurrency']; // 彩票余额

        // 注单数	有效投注	总派彩
        $seachsql = "select sum(count_pay) as count_pay, sum(valid_money) as valid_money_total, sum(bonus) as bonus_total from ".$database['cpDefault']['prefix']."history_bill_report where username='".$memname."' and bet_time between '".strtotime($date_start)."' and '".strtotime($date_end)."'";
//        $cpresult = mysqli_query($cpMasterDbLink,$sql);
//        $cprow = mysqli_fetch_assoc($cpresult);
//        $cprow['count_pay'] = $cprow['count_pay']>0 ? $cprow['count_pay']:0;
//        $cprow['valid_money_total'] = number_format($cprow['valid_money_total'],2);
//        $cprow['bonus_total'] = number_format($cprow['bonus_total'],2);

    }else if($rtype == 'sc'){ // 皇冠体育
        $seachsql = "SELECT SUM(`count_pay`) AS count_pay, SUM(`total`) AS total, SUM(`valid_money`) AS valid_money, SUM(`user_win`) AS user_win FROM ".DBPREFIX."web_report_history_report_data WHERE `username` = '{$memname}' AND `M_Date` BETWEEN '{$date_start}' AND '{$date_end}'";
    }else if($rtype=='ag'){ // ag余额
        require "../include/agproxy.php";
        include "../include/aggame.php";

        $sPrefix = $agsxInitp['data_api_cagent']; // 新建账号增加AG代理前缀
        $userPrefix = $agsxInitp['data_api_user_prefix'];// 新建账号增加AG用户前缀
        // 注单数	有效投注	总派彩
        $agmemname = $sPrefix.$userPrefix.'_'.$memname;
        $seachsql = "select sum(count_pay) as count_pay, sum(valid_money) as valid_money_total, sum(bonus) as bonus_total from ".DBPREFIX."ag_projects_history_report where username='".$agmemname."' and bet_time between '".$date_start."' and '".$date_end."'";
    }else if($rtype == 'og'){ // OG余额&注单
        $seachsql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money_total`, SUM(`total_profit`) AS `bonus_total` FROM " . DBPREFIX . "og_history_report WHERE `username` = '{$memname}' AND `count_date` BETWEEN '{$date_start}' AND '{$date_end}'";
    }else if($rtype == 'bbin'){ // BBIN余额&注单
        $bbmemname = strtoupper($bbin_prefix.$memname);
        $seachsql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money_total`, SUM(`total_profit`) AS `bonus_total` FROM " . DBPREFIX . "jx_bbin_history_report WHERE `username` = '{$bbmemname}' AND `count_date` BETWEEN '{$date_start}' AND '{$date_end}'";
    }else if($rtype == 'ky'){ // ky余额&注单
        $seachsql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money_total`, SUM(`total_profit`) AS `bonus_total` FROM " . DBPREFIX . "ky_history_report WHERE `username` = '{$memname}' AND `count_date` BETWEEN '{$date_start}' AND '{$date_end}'";
    }else if($rtype == 'ff'){ // 皇冠棋牌余额&注单
        $seachsql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money_total`, SUM(`total_profit`) AS `bonus_total` FROM " . DBPREFIX . "ff_history_report WHERE `username` = '{$memname}' AND `count_date` BETWEEN '{$date_start}' AND '{$date_end}'";
    }else if($rtype == 'vg'){ // VG棋牌余额&注单
        $seachsql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money_total`, SUM(`total_profit`) AS `bonus_total` FROM " . DBPREFIX . "vg_history_report WHERE `username` = '{$memname}' AND `count_date` BETWEEN '{$date_start}' AND '{$date_end}'";
    }else if($rtype == 'ly'){ // 乐游棋牌 ly余额&注单
        $seachsql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money_total`, SUM(`total_profit`) AS `bonus_total` FROM " . DBPREFIX . "ly_history_report WHERE `username` = '{$memname}' AND `count_date` BETWEEN '{$date_start}' AND '{$date_end}'";
    }else if($rtype == 'kl'){ // 快乐棋牌
        $seachsql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money_total`, SUM(`total_profit`) AS `bonus_total` FROM " . DBPREFIX . "kl_history_report WHERE `username` = '{$memname}' AND `count_date` BETWEEN '{$date_start}' AND '{$date_end}'";
    }else if($rtype == 'mg'){ // MG电子 mg余额&注单
        $memname = $mg_prefix.$memname;
        $seachsql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money_total`, SUM(`total_profit`) AS `bonus_total` FROM " . DBPREFIX . "mg_history_report WHERE `username` = '{$memname}' AND `count_date` BETWEEN '{$date_start}' AND '{$date_end}'";
    }else if($rtype == 'avia'){ // 泛亚电竞 avia余额&注单
        $seachsql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money_total`, SUM(`total_profit`) AS `bonus_total` FROM " . DBPREFIX . "avia_history_report WHERE `username` = '{$memname}' AND `count_date` BETWEEN '{$date_start}' AND '{$date_end}'";
    }else if($rtype == 'fire'){ // 雷火电竞 fire余额&注单
        $seachsql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money_total`, SUM(`total_profit`) AS `bonus_total` FROM " . DBPREFIX . "fire_history_report WHERE `username` = '{$memname}' AND `count_date` BETWEEN '{$date_start}' AND '{$date_end}'";
    }else if($rtype == 'mw'){ // mw电子 mw余额&注单
        $seachsql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money_total`, SUM(`total_profit`) AS `bonus_total` FROM " . DBPREFIX . "mw_history_report WHERE `username` = '{$memname}' AND `count_date` BETWEEN '{$date_start}' AND '{$date_end}'";
    }else if($rtype == 'cq'){ // CQ9电子 CQ9余额&注单
        $seachsql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money_total`, SUM(`total_profit`) AS `bonus_total` FROM " . DBPREFIX . "cq9_history_report WHERE `username` = '{$memname}' AND `count_date` BETWEEN '{$date_start}' AND '{$date_end}'";
    }else if($rtype == 'fg'){ // FG电子 FG余额&注单
        $seachsql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money_total`, SUM(`total_profit`) AS `bonus_total` FROM " . DBPREFIX . "fg_history_report WHERE `username` = '{$memname}' AND `count_date` BETWEEN '{$date_start}' AND '{$date_end}'";
    }else if($rtype == 'gmcp'){ // 三方彩票
        // 官网注单
        $seachsql = "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money_total`, SUM(`total_profit`) AS `bonus_total` FROM " . DBPREFIX . "web_third_projects_history_report WHERE `username` = '{$memname}' AND `count_date` BETWEEN '{$date_start}' AND '{$date_end}'";
        // 信用注单
        $searchSsc= "SELECT SUM(`total_times`) AS `count_pay`, SUM(`total_cellscore`) AS `valid_money_total`, SUM(`total_profit`) AS `bonus_total` FROM " . DBPREFIX . "web_third_ssc_history_report WHERE `username` = '{$memname}' AND `count_date` BETWEEN '{$date_start}' AND '{$date_end}'";
        $searchSscRes = mysqli_query($dbLink, $searchSsc);
        $searchSscRow = mysqli_fetch_assoc($searchSscRes);
        $searchSscRow['count_pay'] = $searchSscRow['count_pay'] > 0 ? $searchSscRow['count_pay'] : 0;
        $searchSscRow['valid_money_total'] = number_format($searchSscRow['valid_money_total'], 2);
        $searchSscRow['bonus_total'] = number_format($searchSscRow['bonus_total'], 2);
    }
    $seachresult = mysqli_query($dbLink, $seachsql);
    $seachrow = mysqli_fetch_assoc($seachresult);
    $seachrow['count_pay'] = $seachrow['count_pay'] > 0 ? $seachrow['count_pay'] : 0;
    $seachrow['valid_money_total'] = number_format($seachrow['valid_money_total'], 2);
    $seachrow['bonus_total'] = number_format($seachrow['bonus_total'], 2);

    // 三方彩票官方&信用注单
    if(isset($searchSscRow['count_pay'])){
        $seachrow['count_pay'] += $searchSscRow['count_pay'];
        $seachrow['valid_money_total'] += $searchSscRow['valid_money_total'];
        $seachrow['bonus_total'] += $searchSscRow['bonus_total'];
    }

    // var_dump($seachrow);
}

?>
<html>
<head>
    <title>800系統</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <!--<link rel="stylesheet" href="../../../style/agents/control_calendar.css?v=<?php echo AUTOVER; ?>">-->

</head>
<body >
<dl class="main-nav">
    <dt>额度转换</dt>
    <dd>
        <table >
            <FORM id="myFORM" ACTION="" METHOD=POST  name="FrmData">
                <tr class="m_tline">
                    <td width="60" >转账单号: </td>
                    <td><input type="text" name="order_code" class="za_text_auto" value="<?php echo $order_code;?>"/></td>
                    <td width="68">&nbsp;--&nbsp;日期区间:</td>
                    <td>
                        <input type="text" name="date_start" size=10 maxlength=11 class="za_text_auto" value="<?php echo $date_start?>" onclick="laydate({istime: false, format: 'YYYY-MM-DD'})" readonly>
                        &nbsp;</td>
                    <td>
                    </td>
                    <td>~&nbsp;</td>
                    <td>
                        <input type="text" name="date_end" size=10 maxlength=11 class="za_text_auto" value="<?php echo $date_end?>" onclick="laydate({istime: false, format: 'YYYY-MM-DD'})" readonly>
                        &nbsp;</td>
                    <td>
                    </td>
                    <td width="70">&nbsp;&nbsp;会员帐号:</td>
                    <td>
                        <input type="text" name="user_account" class="za_text_auto" value="<?php echo $memname;?>" />
                    </td>
                    <td > &nbsp;
                        <input type=SUBMIT name="SUBMIT" value="查询" class="za_button">
                    </td>
                    <td width="58">&nbsp;--&nbsp;总页数:</td>
                    <td>
                        <select id="page" name="page"  class="za_select za_select_auto" onChange="self.myFORM.submit()">
                            <?php
                            if ($page_count==0){
                                $page_count=1;
                            }
                            for($i=0;$i<$page_count;$i++){
                                if($page == $i){
                                    echo "<option selected value='$i'>".($i+1)."</option>";
                                }else{
                                    echo "<option  value='$i'>".($i+1)."</option>";
                                }

                            }
                            ?>
                        </select>
                    </td>
                    <td> / <?php echo $page_count?> 页</td>
                </tr>

            </FORM>
        </table>
    </dd>
</dl>
<div class="main-ui">
    <!-- 顶部表格 开始-->
    <table class="m_tab self_table" style="margin-bottom:20px;display: <?php echo ($memname !='' && $cou !=0)?'table':'none';?>">
        <tbody>
        <tr class="m_title">
            <td>会员帐号</td>
            <td>体育额度</td>
            <td><?php echo $type_title;?>额度</td>
            <td>注单数</td>
            <td>有效投注</td>
            <td>总派彩</td>
            <td>操作</td>
        </tr>
        <tr class="m_cen">
            <td><?php echo $memname;?></td>
            <td><?php echo $hgmoney;?></td>
            <td class="this_account red"><?php echo ($rtype=='cp'?$cpFund:'0.00')?></td>
            <td class="bet_mon"><?echo $seachrow['count_pay'];?></td>
            <td class="bet_yx"><?echo $seachrow['valid_money_total'];?></td>
            <td class="total_give red"><?echo $seachrow['bonus_total'];?></td>
            <td >
                <input type="submit" name="send" value="资金归集" data-monval="<?php echo ($rtype=='cp'?$cpFund:'0.00')?>" class="za_button change_to_hg" >
            </td>
        </tr>

        </tbody>
    </table>
    <!-- 顶部表格 结束-->

    <table class="m_tab">
        <tr class="m_title">
            <td >ID</td>
            <td >转账时间</td>
            <td >会员帐号</td>
            <td >转账类型</td>
            <td >转账金额</td>
            <td >转账单号</td>
        </tr>

        <?php
        if ($cou==0){
            ?>
            <tr class="m_cen">
                <td colspan="7">目前沒有记录</td>

            </tr>
            <?php
        }else{
            while ($row = mysqli_fetch_array($result)){
//                if($row['Type']=='T') {
//                    if($row['Checked']!=0 and $row['Gold']>0) {
//                        $gold-=$row['Gold'];
//                    }
//                    else {
//                        if($row['Checked']!=0) {
//                            $gold+=$row['Gold'];
//                        }
//                    }
//                } else {
//                    $gold+=$row['Gold'];
//                }

                ?>

                <tr class="m_cen">
                    <td><?php echo $row['ID']?></td>
                    <td><?php echo $row['AuditDate']?></td>
                    <td><?php echo $row['UserName'];?></td>
                    <td class="chg_type">
                        <?php
                        if($row['From'] !='hg'){ // 体育转其他平台
                            echo '转入系统(体育)';
                        }else{
                            echo '转入'.$type_title ;
                        }
                        ?>
                    </td>
                    <td class="add_mon" align="left">
                        <?php
                        echo '<p class="green">'.number_format($row['moneyf'],2).'</p>';
                        if($row['From'] !='hg'){
                            echo '<p>-'.number_format($row['Gold'],2).'</p>';
                        }else{
                            echo '<p>'.number_format($row['Gold'],2).'</p>';
                        }
                        echo '<p class="red">'.number_format($row['currency_after'],2).'</p>';
                        ?>
                    </td>
                    <td class="add_code" data-way="<?php echo $row['Payway']?>" data-type="<?php echo $row['discounType']?>"><?php echo $row['Order_Code'];?></td>
                </tr>

                <?php
            }
        }
        ?>

    </table>

</div>


<script type="text/javascript" src="../../../js/agents/jquery.js" ></script>
<script type="text/javascript" src="../../../js/agents/common.js?v=<?php echo AUTOVER; ?>" ></script>
<script type="text/javascript" src="../../../js/agents/register/laydate.min.js" ></script>
<script language="JavaScript">
    var lv = '<?php echo $lv?>' ; // 当前管理员层级
    var loginname = '<?php echo $loginname?>' ; // 当前管理员账号
    function MM_reloadPage(init) {  //reloads the window if Nav4 resized
        if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
            document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
        else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
    }
    MM_reloadPage(true);

    var first_cou = '<?php echo $cou;?>' ; // 是否有数据
    var rtype = '<?php echo $rtype;?>' ; // cp ag ky ff vg ly mg avia gmcp og
    var username = '<?php echo $memname;?>' ; // 查询用户名
    var hgId = '<?php echo $hgId;?>' ;
    if(username && first_cou !=0 && rtype=='sc') {
        getQpBalance(username,rtype);
        changeToHg(username,'change_to_hg','sc') ;
    }
    if(username && first_cou !=0 && rtype=='ag'){ // 只有输入用户名查询才调用 ag 条件
        agDoAction(username,'this_account','change_to_hg') ; // 获取ag 余额
        changeToHg(username,'change_to_hg','ag') ;
    }
    if(username && first_cou !=0 && rtype=='cp') { // 只有输入用户名查询才调用 cp 条件
        changeToHg(username,'change_to_hg','cp',hgId) ;
    }
    if(username && first_cou !=0 && rtype=='gmcp') { // 只有输入用户名查询才调用 gmcp 条件
        getQpBalance(username,rtype);
        changeToHg(username,'change_to_hg','gmcp') ;
    }
    if(username && first_cou !=0 && rtype=='ky') {
        getQpBalance(username,rtype);
        changeToHg(username,'change_to_hg','ky') ;
    }
    if(username && first_cou !=0 && rtype=='ff') {
        getQpBalance(username,rtype);
        changeToHg(username,'change_to_hg','ff') ;
    }
    if(username && first_cou !=0 && rtype=='vg') {
        getQpBalance(username,rtype);
        changeToHg(username,'change_to_hg','vg') ;
    }
    if(username && first_cou !=0 && rtype=='ly') { // 乐游
        getQpBalance(username,rtype);
        changeToHg(username,'change_to_hg','ly') ;
    }
    if(username && first_cou !=0 && rtype=='kl') { // 快乐
        getQpBalance(username,rtype);
        changeToHg(username,'change_to_hg','kl') ;
    }
    if(username && first_cou !=0 && rtype=='mg') { // MG电子
        getQpBalance(username,rtype);
        changeToHg(username,'change_to_hg','mg') ;
    }
    if(username && first_cou !=0 && rtype=='avia') { // 泛亚电竞
        getQpBalance(username,rtype);
        changeToHg(username,'change_to_hg','avia') ;
    }
    if(username && first_cou !=0 && rtype=='fire') { // 雷火电竞
        getQpBalance(username,rtype);
        changeToHg(username,'change_to_hg','fire') ;
    }
    if(username && first_cou !=0 && rtype=='og') { // OG
        getQpBalance(username,rtype);
        changeToHg(username,'change_to_hg','og') ;
    }
    if(username && first_cou !=0 && rtype=='bbin') { // BBIN
        getQpBalance(username,rtype);
        changeToHg(username,'change_to_hg','bbin') ;
    }
    if(username && first_cou !=0 && rtype=='mw') { // MW
        getQpBalance(username,rtype);
        changeToHg(username,'change_to_hg','mw') ;
    }
    if(username && first_cou !=0 && rtype=='cq') { // CQ9电子
        getQpBalance(username,rtype);
        changeToHg(username,'change_to_hg','cq') ;
    }
    if(username && first_cou !=0 && rtype=='fg') { // FG电子
        getQpBalance(username,rtype);
        changeToHg(username,'change_to_hg','fg') ;
    }

    // 棋牌余额
    // ky 余额
    function getQpBalance(username,type) {
        var ajaxurl = '/app/agents/include/ky/ky_api.php';
        switch (type){
            case 'sc': // sc
                ajaxurl = '/app/agents/include/sportcenter/sport_api.php'
                break;
            case 'ky': // 开元
                ajaxurl = '/app/agents/include/ky/ky_api.php'
                break;
            case 'vg': // vg
                ajaxurl = '/app/agents/include/vgqp/vg_api.php'
                break;
            case 'ff': // ff
                ajaxurl = '/app/agents/include/hgqp/hg_api.php'
                break;
            case 'ly': // ly
                ajaxurl = '/app/agents/include/lyqp/ly_api.php'
                break;
            case 'kl': // kl
                ajaxurl = '/app/agents/include/klqp/kl_api.php'
                break;
            case 'mg': // mg
                ajaxurl = '/app/agents/include/mg/mg_api.php'
                break;
            case 'cq': // cq9电子
                ajaxurl = '/app/agents/include/cq9/cq9_api.php'
                break;
            case 'avia': // avia
                ajaxurl = '/app/agents/include/avia/avia_api.php'
                break;
            case 'fire': // fire
                ajaxurl = '/app/agents/include/thunfire/fire_api.php'
                break;
            case 'og': // og
                ajaxurl = '/app/agents/include/og/og_api.php'
                break;
            case 'bbin': // bbin
                ajaxurl = '/app/agents/include/bbin/bbin_api.php'
                break;
            case 'mw': // mw
                ajaxurl = '/app/agents/include/mw/mw_api.php'
                break;
            case 'fg': // fg
                ajaxurl = '/app/agents/include/fg/fg_api.php'
                break;
            case 'gmcp': // gmcp
                ajaxurl = '/app/agents/include/gmcp/cp_api.php'
                break;
        }
        var data = {
            action : 'b',
            username : username,
        };
        $.ajax({
            type : 'POST',
            url : ajaxurl+'?_='+ Math.random(),
            data : data,
            dataType : 'json',
            success:function(item) {
                console.log(item);
                if(item.code == 0) {
                    switch (type){
                        case 'sc': // sc
                            $('.this_account').html(item.data.sc_balance);
                            $('.change_to_hg').attr('data-monval',item.data.sc_balance);
                            break;
                        case 'ky': // 开元
                            $('.this_account').html(item.data.ky_balance);
                            $('.change_to_hg').attr('data-monval',item.data.ky_balance);
                            break;
                        case 'vg': // vg
                            $('.this_account').html(item.data.vg_balance);
                            $('.change_to_hg').attr('data-monval',item.data.vg_balance);
                            break;
                        case 'ff': // ff
                            $('.this_account').html(item.data.ff_balance);
                            $('.change_to_hg').attr('data-monval',item.data.ff_balance);
                            break;
                        case 'ly': // ly
                            $('.this_account').html(item.data.ly_balance);
                            $('.change_to_hg').attr('data-monval',item.data.ly_balance);
                            break;
                        case 'kl': // kl
                            $('.this_account').html(item.data.kl_balance);
                            $('.change_to_hg').attr('data-monval',item.data.kl_balance);
                            break;
                        case 'mg': // mg
                            $('.this_account').html(item.data.mg_balance);
                            $('.change_to_hg').attr('data-monval',item.data.mg_balance);
                            break;
                        case 'cq': // cq
                            $('.this_account').html(item.data.cq_balance);
                            $('.change_to_hg').attr('data-monval',item.data.cq_balance);
                            break;
                        case 'avia': // avia
                            $('.this_account').html(item.data.avia_balance);
                            $('.change_to_hg').attr('data-monval',item.data.avia_balance);
                            break;
                        case 'fire': // fire
                            $('.this_account').html(item.data.fire_balance);
                            $('.change_to_hg').attr('data-monval',item.data.fire_balance);
                            break;
                        case 'og': // og
                            $('.this_account').html(item.data.og_balance);
                            $('.change_to_hg').attr('data-monval',item.data.og_balance);
                            break;
                        case 'bbin': // bbin
                            $('.this_account').html(item.data.bbin_balance);
                            $('.change_to_hg').attr('data-monval',item.data.bbin_balance);
                            break;
                        case 'mw': // mw
                            $('.this_account').html(item.data.mw_balance);
                            $('.change_to_hg').attr('data-monval',item.data.mw_balance);
                            break;
                        case 'fg': // fg
                            $('.this_account').html(item.data.fg_balance);
                            $('.change_to_hg').attr('data-monval',item.data.fg_balance);
                            break;
                        case 'gmcp': // gmcp
                            $('.this_account').html(item.data.gmcp_balance);
                            $('.change_to_hg').attr('data-monval',item.data.gmcp_balance);
                            break;
                    }

                } else {
                    alert(item.message);
                    $('.this_account').html('0.00');
                    $('.change_to_hg').attr('data-monval','0.00');
                }
            },
            error:function(){
                alert('网络异常，请稍后重试！');
            }
        });
    }


    // ag  余额，转账 api ，pars 参数
    function agDoAction(username,cla,hgcla,pars,tip) {
        var data ;
        var ajaxurl = '/app/agents/include/ag_api.php?_='+Math.random() ; // ag
        if(pars){
            data = pars ;
        }else{
            data={
                action:'b',
                username:username,
            }
        }
        if(tip == 'sc'){ // sc
            ajaxurl = '/app/agents/include/sportcenter/sport_api.php?_='+Math.random() ; // sc ;
        }
        if(tip == 'cp'){ // cp
            ajaxurl = '/app/agents/include/cp_ajaxTran.php?_='+Math.random() ; // cp ;
        }
        if(tip == 'gmcp'){ // gmcp
            ajaxurl = '/app/agents/include/gmcp/cp_api.php?_='+Math.random() ; // gmcp ;
        }
        if(tip == 'ky'){ // ky
            ajaxurl = '/app/agents/include/ky/ky_api.php?_='+Math.random();
        }
        if(tip == 'ff'){ // ff
            ajaxurl = '/app/agents/include/hgqp/hg_api.php?_='+Math.random();
        }
        if(tip == 'vg'){ // vg
            ajaxurl = '/app/agents/include/vgqp/vg_api.php?_='+Math.random();
        }
        if(tip == 'ly'){ // ly
            ajaxurl = '/app/agents/include/lyqp/ly_api.php?_='+Math.random();
        }
        if(tip == 'kl'){ // kl
            ajaxurl = '/app/agents/include/klqp/kl_api.php?_='+Math.random();
        }
        if(tip == 'mg'){ // mg
            ajaxurl = '/app/agents/include/mg/mg_api.php?_='+Math.random();
        }
        if(tip == 'cq'){ // cq
            ajaxurl = '/app/agents/include/cq9/cq9_api.php?_='+Math.random();
        }
        if(tip == 'avia'){ // avia
            ajaxurl = '/app/agents/include/avia/avia_api.php?_='+Math.random();
        }
        if(tip == 'fire'){ // fire
            ajaxurl = '/app/agents/include/thunfire/fire_api.php?_='+Math.random();
        }
        if(tip == 'og'){ // og
            ajaxurl = '/app/agents/include/og/og_api.php?_='+Math.random();
        }
        if(tip == 'bbin'){ // bbin
            ajaxurl = '/app/agents/include/bbin/bbin_api.php?_='+Math.random();
        }
        if(tip == 'mw'){ // mw
            ajaxurl = '/app/agents/include/mw/mw_api.php?_='+Math.random();
        }
        if(tip == 'fg'){ // fg
            ajaxurl = '/app/agents/include/fg/fg_api.php?_='+Math.random();
        }

        $.ajax({
            type: 'POST',
            url: ajaxurl ,
            data:data,
            dataType:'json',
            success:function(ret){
                 //console.log(ret);
                if(ret.err==0){ // 获取数据成功
                    $('.'+cla).html(ret.balance_ag);
                    $('.'+hgcla).attr('data-monval',ret.balance_ag);
                }
                else{
                    $('.'+cla).html('0.00');
                    $('.'+hgcla).attr('data-monval','0.00');
                }
                if(ret.status ==0 || ret.code == 0){
                    alert('资金归集成功') ;
                }
            },
            error:function(){
                alert('网络错误，请稍后重试');
            }
        });
    }

    // 资金归集
    function changeToHg(uername,cla,tip,hgid) {
        var $change_to_hg = $('.'+cla) ;
        $change_to_hg.on('click',function () {
            var value = $(this).data('monval');
            value = parseInt(returnMoney(value.toString()));
            var datapars ;

            if(value==0){ // 当前ag余额为 0
                return ;
            }
            if(tip=='ag'){ // ag 归集
                datapars={
                    username:uername, // 用户名
                    value:value, // 转账金额
                    b:value, // 转账金额
                    f:'ag',
                    t:'hg',
                    lv:lv,
                    loginname:loginname,
                }
                agDoAction(uername,'','',datapars) ;
            }else if(tip == 'cp'){ // 彩票归集
                datapars={
                    id:hgid , // id
                    username:uername, // 用户名
                    fund:value, // 转账金额
                    action:'fundLimitTrans',
                    from:'cp',
                    to:'hg',
                    lv:lv,
                    loginname:loginname,
                }
                agDoAction(uername,'','',datapars,'cp') ;

            }else if(tip == 'sc' || tip == 'ky' || tip == 'ff' || tip == 'vg' || tip == 'ly' || tip == 'kl' || tip == 'mg' || tip == 'avia' || tip == 'fire' || tip == 'og' || tip == 'mw' || tip == 'cq'  || tip == 'fg' || tip == 'gmcp' || tip == 'bbin'){

                datapars={
                    username:uername, // 用户名
                    b:value, // 转账金额
                    f:tip,
                    t:'hg',
                    lv:lv,
                    loginname:loginname,
                }
                agDoAction(uername,'','',datapars,tip) ;
            }

            self.location.reload();

        });
    }


</script>
</body>
</html>