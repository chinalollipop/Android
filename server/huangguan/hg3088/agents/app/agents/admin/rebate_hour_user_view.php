<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include "../include/address.mem.php";
require_once ("../include/config.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

$user_account = isset($_REQUEST['user_account'])?$_REQUEST['user_account']:'' ; // 增加用户名搜索
$nowtime=time();
@$sdate=$_REQUEST['sdate']==""?date("Y-m-d",$nowtime):$_REQUEST['sdate'];
@$edate=$_REQUEST['edate']==""?date("Y-m-d",$nowtime):$_REQUEST['edate'];

if($user_account){
    $ac_sql = "and username LIKE '%$user_account%'" ;
}

$result_data = mysqli_query($dbLink,"select R_date_hour,operation_time, username, sum(R_total) as R_total, sum(R_total_hg) as R_total_hg, sum(R_total_ag) as R_total_ag, 
sum(R_total_ag_dianzi) as R_total_ag_dianzi, sum(R_total_ag_dayu) as R_total_ag_dayu,  
sum(R_total_ky) as R_total_ky, sum(R_total_hgqp) as R_total_hgqp, sum(R_total_vgqp) as R_total_vgqp, sum(R_total_lyqp) as R_total_lyqp, sum(R_total_klqp) as R_total_klqp, sum(R_total_mg) as R_total_mg , sum(R_total_avia) as R_total_avia, sum(R_total_fire) as R_total_fire, 
sum(R_total_og) as R_total_og, sum(R_total_mw) as R_total_mw, sum(R_total_cq) as R_total_cq, sum(R_total_fg) as R_total_fg, sum(R_total_bbin) as R_total_bbin
from ".DBPREFIX."rebate_hour_hour_report 
where R_date_hour BETWEEN '$sdate' and '$edate' and status = 1 ".$ac_sql." group by userid, R_date_hour ");

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
    <title>时时返点查询</title>
    <style type="text/css">
        .row_money{ width: 16%; text-align: left; float: left;margin-bottom: 5px;}
    </style>
</head>
<body >
<dl class="main-nav">
    <dt>时时返点查询</dt>
    <dd>
        <form name="myform"  action="rebate_hour_user_view.php" method="POST">

            <input type=HIDDEN name="is_search" value="Y">
            <div class="headers">
                <div class="headersleft">
                    <div class="headersconnect fl">
                        会员帐号:  <input type="text" class="za_text_auto" name="user_account" placeholder="请输入会员帐号" value="<?php echo $user_account?>">
                        时间区间:
                        <input type="text" class="za_text_auto" name="sdate" placeholder="请选择开始时间" onclick="laydate({istime: true,istoday: false, format: 'YYYY-MM-DD hh:mm:ss'})" value="<?php echo $sdate;?>" readonly/>~~
                        <input type="text" class="za_text_auto" name="edate" placeholder="请选择结束时间" onclick="laydate({istime: true,istoday: false, format: 'YYYY-MM-DD hh:mm:ss'})" value="<?php echo $edate;?>" readonly/>
                        <input type="submit" class="za_button" value="查询">
                    </div>
                </div>
            </div>
        </form>
    </dd>
    <a class="a_link" href="javascript:history.go( -1 );">回上一页</a>
</dl>
<div>

    <table class="m_tab">
        <form name="myform" action="" method="post">
        <tr  class="m_title" style="font-weight: bold;" >
            <td>返水日期</td>
            <td>操作时间</td>
            <td>账号</td>
            <td>总返水</td>
            <td>体育</td>
            <td>AG视讯</td>
            <td>AG电子</td>
            <td>AG打鱼</td>
            <td>开元棋牌</td>
            <!--<td>皇冠棋牌</td>-->
            <td>VG棋牌</td>
            <td>乐游棋牌</td>
            <td>快乐棋牌</td>
            <td>MG电子</td>
            <td>泛亚电竞</td>
            <td>雷火电竞</td>
            <td>OG视讯</td>
            <td>BBIN视讯</td>
            <td>MW电子</td>
            <td>CQ9电子</td>
            <td>FG电子</td>
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
                    <td><?php echo $v['R_date_hour'];?></td>
                    <td><?php echo $v['operation_time'];?></td>
                    <td><?php echo $v['username'];?></td>
                    <td><?php echo number_format($v['R_total'],2);?></td>
                    <td><?php echo number_format($v['R_total_hg'],2);?></td>
                    <td><?php echo number_format($v['R_total_ag'],2);?></td>
                    <td><?php echo number_format($v['R_total_ag_dianzi'],2);?></td>
                    <td><?php echo number_format($v['R_total_ag_dayu'],2);?></td>
                    <td><?php echo number_format($v['R_total_ky'],2);?></td>
                    <!--<td><?php /*echo number_format($v['R_total_hgqp'],2);*/?></td>-->
                    <td><?php echo number_format($v['R_total_vgqp'],2);?></td>
                    <td><?php echo number_format($v['R_total_lyqp'],2);?></td>
                    <td><?php echo number_format($v['R_total_klqp'],2);?></td>
                    <td><?php echo number_format($v['R_total_mg'],2);?></td>
                    <td><?php echo number_format($v['R_total_avia'],2);?></td>
                    <td><?php echo number_format($v['R_total_fire'],2);?></td>
                    <td><?php echo number_format($v['R_total_og'],2);?></td>
                    <td><?php echo number_format($v['R_total_bbin'],2);?></td>
                    <td><?php echo number_format($v['R_total_mw'],2);?></td>
                    <td><?php echo number_format($v['R_total_cq'],2);?></td>
                    <td><?php echo number_format($v['R_total_fg'],2);?></td>
                </tr>
            <?php } ?>
        </form>
    </table>
</div>
<script type="text/javascript" src="../../../js/agents/register/laydate.min.js"></script>

</body>
</html>


