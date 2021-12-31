<?php
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

require ("../../app/agents/include/config.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$username=$_SESSION['UserName'];
$navtype = isset($_REQUEST['type'])?$_REQUEST['type']:'';
$navtitle = isset($_REQUEST['navtitle'])?$_REQUEST['navtitle']:'';

$dateRange = monthRange(3); // 最近三个月
$date=date('Y-m'); // 当前月份


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
        .contentAll .report_top{height: 15rem;}
        .report_top .report_bottom{height: 11rem;}
        .topList>div {align-items: center;border-bottom: 1px solid #f1f1f1;}
        .topList>div>div {width: 8rem;text-align: left;}
        .report_top .report_bottom .topList span {width: 5rem;height: 1px;background: #acacac;}
        .report_top input, .report_top select{border-bottom: none;}
        .notice {color: #acacac;text-align: left;line-height: 2rem;}
        .notice .tip{background:#f1f6fb;color: #000;padding: 2px 2%;margin: 1rem auto; }
        .notice p{padding: 0 2%; }
    </style>
</head>
<body>
<div class="contentAll flex">

    <div class="report_top">
        <div class="report_content">
            <div class="topHeader reportTopHeader">
                <a href="javascript:;" onclick="history.go(-1)"> </a>
                <span> <?php echo $navtitle;?> </span>
            </div>
        </div>
        <div class="report_bottom flex">
            <div class="topList">
                <div class="flex">
                    <div>代理商：</div><input class="ag_username" value="<?php echo $username;?>" placeholder="账号"  />
                </div>
            </div>
            <div class="topList">
                <div class="flex">
                    <div>选择日期：</div>
                    <select name="date" class="seadate">
                        <?php
                        foreach ($dateRange as $value){?>
                            <option value="<?php echo $value?>" <?php if($value == $date) echo "selected";?> ><?php echo $value?></option>
                        <?php }?>
                    </select>
                </div>
            </div>
            <div class="topList">
                <a class="seachBtn btn linear_1"> 确定 </a>
            </div>
        </div>
    </div>
    <div class="contentCenterAll ">
        <div class="notice">
            <div class="tip"> 注意：本月统计数据，于每日美东时间03:30更新 </div>
            <p>会员输赢和代理佣金计算说明：</p>
            <p class="red">会员输赢：</p>
            <p>红色(正数)：代表玩家赢的钱。绿色(负数)：代表玩家输的钱</p>

            <p class="red">可获佣金：</p>
            <p>红色(正数)：代表要支付代理费给代理商。绿色(亏损)：代表无需支付</p>

            <p class="red">佣金计算公式：</p>
            <p>(0 - 会员输赢 - 返水总额 - 行政费 - 平台抽成) X 退佣比例 + (有效投注x退水比例) = 厅室佣金</p>
            <p>行政费：厅室输赢(取正数) X 行政费比例</p>
            <p>平台抽成：厅室输赢(取正数) X 抽水比例</p>
            <p>可获佣金：各厅室佣金相加抵扣总和 - 存款手续费 - 取款手续费 = 可获佣金</p>

            <p class="red">入款总量和出款总量：</p>
            <p>只用于计算本月代理下属会员的资金流水统计和计算手续费(手续费按每笔计算，并不按照总量计算)，所以仅供参考</p>
        </div>

    </div>
    
    <?php include 'middle_footer.php'; ?>

</div>

<script type="text/javascript" src="/js/agents/jquery.js"></script>
<script type="text/javascript" src="/js/agents/layer/layer.js"></script>
<script type="text/javascript" src="/js/agents/common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript">
    settingHeight('.contentAll');
    seachCommission();

    // 查询代理报表
    function seachCommission() {
        $('.seachBtn').off().on('click',function () {
            var url = 'middle_commissionDetail.php?navtitle=佣金详情';
            var agent = $('.ag_username').val();
            var date = $('.seadate').val();
            url +='&agent='+agent+'&date='+date;
            // console.log(url)
            parent.loadPageBox.location.href = url;

        })
    }
    
</script>
</body>
</html>
