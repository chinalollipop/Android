<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include "./include/address.mem.php";
// echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
require ("./include/config.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!!!');top.location.href='/';</script>";
    exit;
}

$uid=$_REQUEST['uid'];
$lv=$_REQUEST['lv'];
$langx=$_SESSION['langx']?$_SESSION['langx']:"zh-cn";  // 默认简体

require ("./include/traditional.$langx.inc.php");

$username = $_SESSION['UserName'];

?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <?php
    if($_SESSION['template'] == 'new'){
        echo '<link href="../../style/'.TPL_FILE_NAME.'.css?v='.AUTOVER.'" rel="stylesheet" type="text/css">';
    }
    ?>
    <style>
        td{padding: 7px 0;}
    </style>
</head>
<body class="<?php echo ($lv=='D'?'ag-body':'');?>">
<div class="main-shadow"></div>
<table class=" home-notice" >

    <tr>
        <th>公告通知</th>
        <td >
            <span style="float: left;text-indent: -9999px;">.</span>
            <div style="float: right;width: 100%">
            <marquee scrollDelay="80" scrollamount="4" direction="left" onMouseOver='this.stop()' onMouseOut='this.start()'>
                   <?php echo getScrollMsg(); ?>
            </marquee>
            </div>
        </td>
        <td class="more">
            <a href="other_set/show_marquee.php?uid=<?php echo $uid?>&lv=MEM&langx=<?php echo $langx?>&level=<?php echo $lv ?>">历史信息</a>
        </td>

    </tr>
</table>

<?php
if($lv =='D'){ // 只有普通代理才有
?>
    <dl class="home-tips"><dt>温馨提示</dt>
        <dd>
            <p>1. 为迎合市场的需求从7月13日（第8期）起本公司将会推出其他盘口(马来盘,印尼盘,欧洲盘)， 请多加应用符合你所需求的盘口。</p><p>2. 为了公平起见和符合所有盘口的一致性，『退水』及『货量』的计算将由原先的投注金额改为以赢输金额计算。</p><p style="color:#ff0000;">*注意: 波胆, 总入球, 半全场, 冠军和综合过关依然以投注金额计算。
            </p>
            <br>
        </dd>
    </dl>
    <dl class="home-tips">
        <dt>代理结算</dt>
        <dd>
            <table id="table2" border="1" cellspacing="0" bordercolorlight="#333333" cellpadding="5" width="100%" class="table2">
                <tbody>
                <tr class="bg_tr"><td height="20" rowspan="2" align="middle">当月营利</td>
                    <td rowspan="2" align="middle">当月最低有效会员</td>
                    <td align="middle" colspan="3" >当月退佣比例</td>
                </tr>
                <tr class="bg_tr">
                    <td align="middle">体育博弈</td>
                    <td align="middle">真人视讯棋牌</td>
                    <td align="middle">彩票游戏</td>
                </tr>
                <?php if(TPL_FILE_NAME !='3366'){
                    echo '
                        <tr align="middle"><td>100-50000</td><td>5或以上</td><td>30%</td><td>20%</td><td>20%</td></tr>
                        <tr align="middle"><td>50001-500000</td><td>10或以上</td><td>35%</td><td>25%</td><td>25%</td></tr>
                        <tr align="middle"><td>500001-3000000</td><td>25或以上</td><td>40%</td><td>30%</td><td>30%</td></tr>
                        <tr align="middle"><td>3000001-5000000</td><td>50或以上</td><td>45%</td><td>35%</td><td>35%</td></tr>
                        <tr align="middle"><td>5000001以上</td><td>100或以上</td><td>50%</td><td>40%</td><td>40%</td></tr>';
                }else{
                    echo ' <tr align="middle"><td>1-50000</td><td>5或以上</td><td>30%</td><td>30%</td><td>20%</td></tr>
                            <tr align="middle"><td>50001-300000</td><td>10或以上</td><td>35%</td><td>35%</td><td>25%</td></tr>
                            <tr align="middle"><td>300001-800000</td><td>50或以上</td><td>40%</td><td>40%</td><td>30%</td></tr>
                            <tr align="middle"><td>800001-1000000</td><td>50或以上</td><td>45%</td><td>45%</td><td>35%</td></tr>
                            <tr align="middle"><td>2000001以上</td><td>100或以上</td><td>50%</td><td>50%</td><td>40%</td></tr>';
                }
                ?>

                </tbody></table><p>注：有效会员定义为当月有存款充值<?php echo (TPL_FILE_NAME=='6668'?'3000':'500');?>元以上且有效投注额在<?php echo ((TPL_FILE_NAME=='6668'||TPL_FILE_NAME=='0086')?'3000':'1000');?>以上。<br> 例：每个代理商申请佣金，下线会员不可低于5个有效会员。
            </p>
        </dd>
    </dl>
<?php
}
?>

<script type="text/javascript" src="../../js/agents/jquery.js"></script>
</body>
</html>
