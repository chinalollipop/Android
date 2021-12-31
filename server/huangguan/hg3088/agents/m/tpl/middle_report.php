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
$navtype = isset($_REQUEST['type'])?$_REQUEST['type']:'';
$navtitle = isset($_REQUEST['navtitle'])?$_REQUEST['navtitle']:'';

$dateNow = date('Y-m-d');// 日期

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
    <link href="/style/icalendar.css" rel="stylesheet" type="text/css" media="screen"/>
    <link href="../css/main.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css" media="screen"/>

    <title> <?php echo COMPANY_NAME;?> </title>
    <style type="text/css">
        .contentAll .report_top{height: 17rem;}
        .report_top .report_bottom{height: 13rem;}
        .topList>div {align-items: center;}
        .report_top .report_bottom .topList span {width: 5rem;height: 1px;background: #acacac;}
        .report_top input{text-align: center;}

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
                    <input class="beginDate" value="<?php echo $dateNow;?>" placeholder="开始时间" readonly />
                    <span> </span>
                    <input class="endDate" value="<?php echo $dateNow;?>" placeholder="结束时间" readonly />
                </div>
            </div>
            <div class="topList">
                <select name="enable" class="enable">
                    <option label="全部" selected >全部</option>
                </select>
            </div>
            <div class="topList">
                <select name="orderby" class="orderby">
                    <option value="A">总账</option>
                </select>
            </div>
            <div class="topList">
                <a class="seachBtn btn linear_1"> 确定 </a>
            </div>
        </div>
    </div>
    
    <?php include 'middle_footer.php'; ?>

</div>

<script type="text/javascript" src="/js/agents/jquery.js"></script>
<script type="text/javascript" src="/js/agents/layer/layer.js"></script>
<script type="text/javascript" src="/js/agents/icalendar.min.js"></script>
<script type="text/javascript" src="/js/agents/common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript">
    settingHeight('.contentAll');
    seachAgentReport();

    var calendar = new lCalendar();   // 时间插件初始化
    var calendar_1 = new lCalendar();   // 时间插件初始化
    calendar.init({
        'trigger': '.beginDate',
        'type': 'date',
        //defaultValue:setAmerTime('.birthDay'),
    });
    calendar_1.init({
        'trigger': '.endDate',
        'type': 'date',
        //defaultValue:setAmerTime('.birthDay'),
    });
    // 查询代理报表
    function seachAgentReport() {
        $('.seachBtn').off().on('click',function () {
            var url = 'middle_reportDetail.php?type=bb&navtitle=报表详情';
            var date_start = $('.beginDate').val()+' 00:00:00';
            var date_end = $('.endDate').val()+' 23:59:59';
            url +='&date_start='+date_start+'&date_end='+date_end;
            // console.log(url)
            parent.loadPageBox.location.href = url;

        })
    }
    
</script>
</body>
</html>
