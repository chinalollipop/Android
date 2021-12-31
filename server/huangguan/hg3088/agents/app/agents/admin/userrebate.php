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
$langx=$_SESSION["langx"];
$lv=$_REQUEST["lv"];

//根据语言引入对应文字信息
require ("../include/traditional.$langx.inc.php");

$nowtime=time();
@$startdate=$_REQUEST['sdate']==""?date("Y-m-d",$nowtime):$_REQUEST['sdate'];
@$enddate=$_REQUEST['edate']==""?date("Y-m-d",$nowtime):$_REQUEST['edate'];


/**
 * 返回查询
 * 待确认需求后在做调整
 * M_Date,MID,Active,BetScore,tiyu，zhenren,BetTime,
 */
/* $sql = "select * from web_report_data where M_date between '$startdate' and '$enddate'";
$res = mysqli_query($dbLink,$sql); */

?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <title>返点查询</title>
</head>
<body >
<dl class="main-nav">
    <dt>返点查询</dt>
    <dd></dd>
</dl>
<div class="main-ui">
    <form name="myform"  action="userrebate.php?uid=<?php echo $uid?>" method="POST">
        <!-- <input type=HIDDEN name="uid" value="<?php echo $uid?>">
        <input type=HIDDEN name="langx" value="<?php echo $langx?>">
        <input type=HIDDEN name="lv" value="<?php echo $lv?>">
        <input type=HIDDEN name="first" value="Y">-->
        <div class="headers">
            <div class="headersleft">
                <div class="headersconnect fl">
                 	时间区间:
                    <input type="text" class="za_text_auto" name="sdate" onclick="laydate({istime: false, format: 'YYYY-MM-DD'})" value="<?php echo $sdate;?>" readonly/>~~
                    <input type="text" class="za_text_auto" name="edate" onclick="laydate({istime: false, format: 'YYYY-MM-DD'})" value="<?php echo $edate;?>" readonly/>
                    <input type="submit" class="za_button" value="查询">
                </div>
            </div>
        </div>
    </form>

    <table class="m_tab">
        <form name="myform" action="" method="post">
        <tr  class="m_title" >
            <td>日期</td>
            <td>期数</td>
            <td>笔数</td>
            <td>总金额</td>
            <td>体育</td>
            <td>真人</td>
            <td>操作时间</td>
            <td>操作</td>
        </tr>
        </form>
    </table>
</div>
<script type="text/javascript" src="../../../js/agents/register/laydate.min.js"></script>

</body>
</html>


