<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include "../include/address.mem.php";
// echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
require_once ("../include/config.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

$uid=$_REQUEST["uid"];
$user_account = isset($_REQUEST['user_account'])?$_REQUEST['user_account']:'' ; // 增加用户名搜索
$nowtime=time();
@$sdate=$_REQUEST['sdate']==""?date("Y-m-d",$nowtime):$_REQUEST['sdate'];
@$edate=$_REQUEST['edate']==""?date("Y-m-d",$nowtime):$_REQUEST['edate'];

if($user_account){
    $ac_sql = "and username LIKE '%$user_account%'" ;
}

$result_data = mysqli_query($dbLink,"select R_date,operation_time, username, sum(R_total) as R_total, sum(R_total_hg) as R_total_hg, sum(R_total_ag) as R_total_ag, 
sum(R_total_ag_dianzi) as R_total_ag_dianzi, sum(R_total_ag_dayu) as R_total_ag_dayu,  
sum(R_total_ky) as R_total_ky, sum(R_total_hgqp) as R_total_hgqp, sum(R_total_vgqp) as R_total_vgqp, sum(R_total_lyqp) as R_total_lyqp, sum(R_total_klqp) as R_total_klqp, sum(R_total_mg) as R_total_mg , sum(R_total_avia) as R_total_avia, sum(R_total_fire) as R_total_fire,
sum(R_total_og) as R_total_og, sum(R_total_mw) as R_total_mw, sum(R_total_cq) as R_total_cq, sum(R_total_fg) as R_total_fg, sum(R_total_bbin) as R_total_bbin
from ".DBPREFIX."rebate_history_report 
where R_date BETWEEN '$sdate' and '$edate' and status = 1 ".$ac_sql." group by username ");
$cou=mysqli_num_rows($result_data);
$data=[];
while ($row = mysqli_fetch_assoc($result_data)){
    $data[]=$row;
}


?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <title>返点查询</title>
    <style type="text/css">
        .row_money{ width: 16%; text-align: left; float: left;margin-bottom: 5px;}
    </style>
</head>
<body >
<dl class="main-nav">
    <dt>返点查询</dt>
    <dd>
        <form name="myform"  action="rebate_user.php?uid=<?php echo $uid?>" method="POST">

            <input type=HIDDEN name="is_search" value="Y">
            <div class="headers">
                <div class="headersleft">
                    <div class="headersconnect fl">
                        会员帐号:  <input type="text" class="za_text_auto" name="user_account" placeholder="请输入会员帐号" value="<?php echo $user_account?>">
                        时间区间:
                        <input type="text" class="za_text_auto" name="sdate" onclick="laydate({istime: false, format: 'YYYY-MM-DD'})" value="<?php echo $sdate;?>" readonly/>~~
                        <input type="text" class="za_text_auto" name="edate" onclick="laydate({istime: false, format: 'YYYY-MM-DD'})" value="<?php echo $edate;?>" readonly/>
                        <input type="submit" class="za_button" value="查询">
                    </div>
                </div>
            </div>
        </form>
    </dd>
    <a class="a_link" href="javascript:history.go( -1 );">回上一页</a>
</dl>
<div class="main-ui">

    <table class="m_tab">
        <form name="myform" action="" method="post">
        <tr  class="m_title" style="font-weight: bold;" >
            <td>返水日期</td>
            <td>操作时间</td>
            <td>账号</td>
            <td>金额</td>
        </tr>
            <?php
            if ($cou == 0){
                ?>
                <tr><td colspan="4"><br>没有数据<br></td></tr>
            <?php } ?>
            <?php
            foreach ($data as $k => $v){
                // AG用户名 转成HG用户名后再显示
                if(strpos($v['username'],'_') !== false){
                    $aUsername=explode('_',$v['username'],2);
                    $v['username']=$aUsername[1];
                }
            ?>
                <tr>
                    <td><?php echo $v['R_date'];?></td>
                    <td><?php echo $v['operation_time'];?></td>
                    <td><?php echo $v['username'];?></td>
                    <td>
                        <div class="row_money">总返水：<?php echo number_format($v['R_total'],2);?></div>
                        <div class="row_money">体育：<?php echo number_format($v['R_total_hg'],2);?></div>
                        <div class="row_money">AG视讯：<?php echo number_format($v['R_total_ag'],2);?></div>
                        <div class="row_money">AG电子：<?php echo number_format($v['R_total_ag_dianzi'],2);?></div>
                        <div class="row_money">AG打鱼：<?php echo number_format($v['R_total_ag_dayu'],2);?></div>
                        <div class="row_money">开元棋牌：<?php echo number_format($v['R_total_ky'],2);?></div>
                        <!--<div class="row_money">皇冠棋牌：<?php /*echo number_format($v['R_total_hgqp'],2);*/?></div>-->
                        <div class="row_money">VG棋牌：<?php echo number_format($v['R_total_vgqp'],2);?></div>
                        <div class="row_money">乐游棋牌：<?php echo number_format($v['R_total_lyqp'],2);?></div>
                        <div class="row_money">快乐棋牌：<?php echo number_format($v['R_total_klqp'],2);?></div>
                        <div class="row_money">MG电子：<?php echo number_format($v['R_total_mg'],2);?></div>
                        <div class="row_money">泛亚电竞：<?php echo number_format($v['R_total_avia'],2);?></div>
                        <div class="row_money">雷火电竞：<?php echo number_format($v['R_total_fire'],2);?></div>
                        <div class="row_money">OG视讯：<?php echo number_format($v['R_total_og'],2);?></div>
                        <div class="row_money">BBIN视讯：<?php echo number_format($v['R_total_bbin'],2);?></div>
                        <div class="row_money">MW电子：<?php echo number_format($v['R_total_mw'],2);?></div>
                        <div class="row_money">CQ9电子：<?php echo number_format($v['R_total_cq'],2);?></div>
                        <div class="row_money">FG电子：<?php echo number_format($v['R_total_fg'],2);?></div>
                        <!-- <div class="row_money">彩票：--><?php //echo number_format($v['R_total_cp'],2);?><!--</div>-->

                    </td>
                </tr>
            <?php } ?>
        </form>
    </table>
</div>
<script type="text/javascript" src="../../../js/agents/register/laydate.min.js"></script>

</body>
</html>


