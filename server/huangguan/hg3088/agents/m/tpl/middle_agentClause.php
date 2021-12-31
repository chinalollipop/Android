<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Content-type: text/html; charset=utf-8");

require ("../../app/agents/include/config.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$navtitle = isset($_REQUEST['navtitle'])?$_REQUEST['navtitle']:'';


?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <META name="keywords" content="<?php echo COMPANY_NAME;?>,<?php echo COMPANY_NAME;?>登入,<?php echo COMPANY_NAME;?>平台">
    <meta name="HandheldFriendly" content="true"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="shortcut icon" href="/images/favicon_<?php echo TPL_FILE_NAME;?>.ico" type="image/x-icon"/>
    <link href="../css/main.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css" media="screen"/>

    <title> <?php echo COMPANY_NAME;?> </title>
    <style type="text/css">
        .contact_center table {width: 98%;margin: 1rem auto;}
        .contact_center .agentTip {background: #f1f6fb;color: #000;text-align: left;font-size: .8rem;padding: .5rem 2%;}
    </style>
</head>
<body>
<div class="contentAll flex">
    <?php include 'middle_header.php'; ?>

    <div class="contact_center">
        <table width="100%" class="tableContent">
            <tbody>
            <tr class="bg_tr">
                <td rowspan="2" align="middle">当月营利</td>
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

            </tbody>
        </table>
        <p class="agentTip"> 注：有效会员定义为当月有存款充值<?php echo (TPL_FILE_NAME=='6668'?'3000':'500');?>元以上且有效投注额在<?php echo ((TPL_FILE_NAME=='6668'||TPL_FILE_NAME=='0086')?'3000':'1000');?>以上。<br>
            例：每个代理商申请佣金，下线会员不可低于5个有效会员。
        </p>
    </div>

    <?php include 'middle_footer.php'; ?>

</div>

<script type="text/javascript" src="/js/agents/jquery.js"></script>
<script type="text/javascript" src="/js/agents/layer/layer.js"></script>
<script type="text/javascript" src="/js/agents/common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript">
    settingHeight('.contentAll');

</script>
</body>
</html>
