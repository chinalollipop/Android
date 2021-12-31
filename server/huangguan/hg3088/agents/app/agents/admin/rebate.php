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
// 安全校验最后处理
if( $_REQUEST['is_search'] == 'Y'){ // 搜索数据
    if($user_account){
        $ac_sql = "and username LIKE '%$user_account%'" ;
    }
    $nowYmd = strtotime(date('Y-m-d', time()));
    if(strtotime($_REQUEST['sdate']) == $nowYmd || strtotime($_REQUEST['edate']) == $nowYmd){
        echo ("<script>alert('今日数据未生成，只能查询之前的记录')</script>");
    }

    // 要统计的日期
    $every_date = [];
    if($_REQUEST['sdate'] <= $_REQUEST['edate']){

        $d = (strtotime($_REQUEST['edate']) - strtotime($_REQUEST['sdate'])) / (3600*24); // 天数
        $every_date[]=$_REQUEST['sdate'];

        for ($i=1;$i<$d;$i++){
            $every_date[$i]=date('Y-m-d',strtotime($every_date[$i-1]) + (3600*24));
        }

    }else{
        echo ("<script>alert('开始日期必须小于结束日期')</script>");
    }

    $nowtime=time();
    @$sdate=$_REQUEST['sdate']==""?date("Y-m-d ",$nowtime):$_REQUEST['sdate'];
    @$edate=$_REQUEST['edate']==""?date("Y-m-d ",$nowtime):$_REQUEST['edate'];


/*
    $result_data = mysqli_query($dbLink,"select username, R_date, R_period,COUNT(userid) as total_num, sum(count_pay) as count_pay, sum(total) as total, sum(total_hg) as total_hg, sum(total_ag) as total_ag, sum(total_ag_dianzi) as total_ag_dianzi, sum(total_ag_dayu) as total_ag_dayu,
sum(total_ky) as total_ky, sum(R_total) as R_total, sum(R_total_hg) as R_total_hg, sum(R_total_ag) as R_total_ag, sum(R_total_ag_dianzi) as R_total_ag_dianzi, sum(R_total_ag_dayu) as R_total_ag_dayu, sum(R_total_ky) as R_total_ky, operation_time 
from ".DBPREFIX."rebate_history_report where R_date BETWEEN '$sdate' and '$edate' and status = 1 ".$ac_sql." group by R_Date ");
 */
    $result_data = mysqli_query($dbLink,"select username, R_date, R_period,COUNT(userid) as total_num, sum(count_pay) as count_pay, sum(R_total) as R_total, 
sum(R_total_hg) as R_total_hg, sum(R_total_ag) as R_total_ag, sum(R_total_ag_dianzi) as R_total_ag_dianzi, sum(R_total_ag_dayu) as R_total_ag_dayu, sum(R_total_ky) as R_total_ky, 
sum(R_total_hgqp) as R_total_hgqp, sum(R_total_vgqp) as R_total_vgqp, sum(R_total_lyqp) as R_total_lyqp, sum(R_total_klqp) as R_total_klqp, sum(R_total_mg) as R_total_mg, sum(R_total_avia) as R_total_avia, sum(R_total_fire) as R_total_fire,
sum(R_total_og) as R_total_og, sum(R_total_mw) as R_total_mw, sum(R_total_cq) as R_total_cq, sum(R_total_fg) as R_total_fg, sum(R_total_bbin) as R_total_bbin, operation_time 
from ".DBPREFIX."rebate_history_report 
where R_date BETWEEN '$sdate' and '$edate' and status = 1 ".$ac_sql." group by R_Date ");
    $cou=mysqli_num_rows($result_data);
   // echo $cou; die;
    if ($cou>0){
        $data=[];
        $count_pay_all = 0;
        $R_total_all = 0;
        $R_total_hg_all = 0;
        $R_total_ag_all = 0;
        $R_total_ag_dianzi_all = 0;
        $R_total_ag_dayu_all = 0;
        $R_total_ky_all = 0;
        $R_total_hgqp_all = 0;
        $R_total_vgqp_all = 0;
        $R_total_lyqp_all = 0;
        $R_total_klqp_all = 0;
        $R_total_mg_all = 0;
        $R_total_avia_all = 0;
        $R_total_fire_all = 0;
        $R_total_og_all = 0;
        $R_total_mw_all = 0;
        $R_total_cq_all = 0;
        $R_total_fg_all = 0;
        $R_total_bbin_all = 0;
        while ($row = mysqli_fetch_assoc($result_data)){
            $data[]=$row;
            $count_pay_all += $row['count_pay'];
            $R_total_all += $row['R_total'];
            $R_total_hg_all += $row['R_total_hg'];
            $R_total_ag_all += $row['R_total_ag'];
            $R_total_ag_dianzi_all += $row['R_total_ag_dianzi'];
            $R_total_ag_dayu_all += $row['R_total_ag_dayu'];
            $R_total_ky_all += $row['R_total_ky'];
            $R_total_hgqp_all += $row['R_total_hgqp'];
            $R_total_vgqp_all += $row['R_total_vgqp'];
            $R_total_lyqp_all += $row['R_total_lyqp'];
            $R_total_klqp_all += $row['R_total_klqp'];
            $R_total_mg_all += $row['R_total_mg'];
            $R_total_avia_all += $row['R_total_avia'];
            $R_total_fire_all += $row['R_total_fire'];
            $R_total_og_all += $row['R_total_og'];
            $R_total_mw_all += $row['R_total_mw'];
            $R_total_cq_all += $row['R_total_cq'];
            $R_total_fg_all += $row['R_total_fg'];
            $R_total_bbin_all += $row['R_total_bbin'];
        }
    }

}

?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <title>返点查询</title>
    <style type="text/css">
        .rebate_user_search{width: 40px; height: 25px; margin: auto; text-align: center; line-height: 25px; background-color: #ffffbe; border: 1px solid #000000;}
    </style>
</head>
<body >
<dl class="main-nav">
    <dt>返点查询</dt>
    <dd>
        <form name="myform"  action="rebate.php?uid=<?php echo $uid?>" method="POST">

            <input type=HIDDEN name="is_search" value="Y">
            <div class="headers">
                <div class="headersleft">
                    <div class="headersconnect fl">
                        会员帐号:  <input type="text" class="za_text_auto" name="user_account" placeholder="请输入会员帐号" value="<?php echo $user_account?>">
                        时间区间:
                        <input type="text" class="za_text_auto" name="sdate"  placeholder="请选择开始时间" onclick="laydate({istime: false, format: 'YYYY-MM-DD'})" value="<?php echo $sdate;?>" readonly/>~~
                        <input type="text" class="za_text_auto" name="edate" placeholder="请选择结束时间" onclick="laydate({istime: false, format: 'YYYY-MM-DD'})" value="<?php echo $edate;?>" readonly/>
                        <input type="submit" class="za_button" value="查询">
                    </div>
                </div>
            </div>
        </form>
    </dd>
</dl>
<div class="">

    <table class="m_tab">
        <form name="myform" action="" method="post">
        <tr  class="m_title" style="font-weight: bold;" >
            <td>日期</td>
            <td>期数</td>
            <td>人数</td>
            <td>笔数</td>
            <td>总金额</td>
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
            <td>操作时间</td>
            <td>操作</td>
        </tr>
            <?php
            if (count($data) == 0){
                ?>
                <tr><td colspan="22"><br>没有数据<br></td></tr>
            <?php } ?>
            <?php
            foreach ($data as $k => $v){
            ?>
                <tr>
                    <td class="check_date"><?php echo $v['R_date'];?></td>
                    <td><?php echo $v['R_period'];?></td>
                    <td class="mem_count"><?php echo $v['total_num'] ;?></td>
                    <td><?php echo $v['count_pay'];?></td>
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
                    <td><?php echo $v['operation_time'];?></td>
                    <td>
                        <div style="text-align: center"> <!-- rebate_user_search -->
                            <a style="display: inline-block;width: 48px;line-height: 23px;" class="za_button" href="rebate_user.php?uid=<?php echo $uid;?>&sdate=<?php echo $v['R_date'];?>&edate=<?php echo $v['R_date'];?>&user_account=<?php echo $user_account?>" >查询</a>
                        </div>
                    </td>
                </tr>
            <?php } ?>
        </form>

        <tr  class="m_title" style="font-weight: bold;">
            <td colspan="2">总计：</td>
            <td><?php echo number_format($count_pay_all,2); ?></td>
            <td><?php echo number_format($R_total_all,2); ?></td>
            <td><?php echo number_format($R_total_hg_all,2); ?></td>
            <td><?php echo number_format($R_total_ag_all,2); ?></td>
            <td><?php echo number_format($R_total_ag_dianzi_all,2); ?></td>
            <td><?php echo number_format($R_total_ag_dayu_all,2); ?></td>
            <td><?php echo number_format($R_total_ky_all,2); ?></td>
            <td><?php echo number_format($R_total_hgqp_all,2); ?></td>
            <td><?php echo number_format($R_total_vgqp_all,2); ?></td>
            <td><?php echo number_format($R_total_lyqp_all,2); ?></td>
            <td><?php echo number_format($R_total_klqp_all,2); ?></td>
            <td><?php echo number_format($R_total_mg_all,2); ?></td>
            <td><?php echo number_format($R_total_avia_all,2); ?></td>
            <td><?php echo number_format($R_total_fire_all,2); ?></td>
            <td><?php echo number_format($R_total_og_all,2); ?></td>
            <td><?php echo number_format($R_total_bbin_all,2); ?></td>
            <td><?php echo number_format($R_total_mw_all,2); ?></td>
            <td><?php echo number_format($R_total_cq_all,2); ?></td>
            <td><?php echo number_format($R_total_fg_all,2); ?></td>
            <td colspan="2"></td>
        </tr>
    </table>
</div>
<script type="text/javascript" src="../../../js/agents/register/laydate.min.js"></script>

</body>
</html>


