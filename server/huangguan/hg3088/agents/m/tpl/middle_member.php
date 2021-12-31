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

    </style>
</head>
<body>
<div class="contentAll flex">

    <div class="report_top">
        <div class="report_content">
            <div class="topHeader reportTopHeader">
                <a href="javascript:;" onclick="history.go(-1)"> </a>
                <span> <?php echo $navtitle;?></span>
            </div>
        </div>
        <div class="report_bottom flex">
            <div class="topList">
                <select name="enable" class="enable">
                    <option label="全部" value="ALL" selected >全部</option>
                    <option label="启用" value="Y">启用</option>
                    <option label="冻结" value="S">冻结</option>
                    <option label="停用" value="N">停用</option>
                </select>
            </div>
            <div class="topList">
                <select name="sort" class="sort" onChange="changeWinLose()">
                    <option label="新增日期" value="AddDate" selected >新增日期</option>
                    <option label="会员帐号" value="UserName">会员帐号</option>
                    <option label="会员名称" value="Alias">会员名称</option>
                    <option label="登录时间" value="LoginTime">登录时间</option>
                    <option label="会员额度" value="Money"> 会员额度</option>
                    <option label="输赢额度" value="WinLossCredit"> 输赢额度</option>
                    <option label="输赢大于" value="WinLossCreditBigger"> 输赢大于</option>
                    <option label="输赢小于" value="WinLossCreditSmaller"> 输赢小于</option>
                    <option label="存款次数" value="DepositTimes"> 存款次数</option>
                    <option label="取款次数" value="WithdrawalTimes"> 取款次数</option>
                </select>
            </div>
            <div class="topList">
                <select name="orderby" class="orderby">
                    <option label="降冥(由大到小)" value="DESC">降冥(由大到小)</option>
                    <option label="升冥(由小到大)" value="ASC">升冥(由小到大)</option>
                </select>
            </div>
            <div class="topList topList_syed" style="display: none;">
                <input type="text" name="WinLossText" class="WinLossText" placeholder="输赢大于/小于">
            </div>
            <div class="topList">
                <input type="text" name="dlg_text" class="dlg_text" placeholder="请输入关键字">
            </div>
            <div class="topList">
                <a class="seachBtn btn linear_1"> 快速查询 </a>
            </div>
        </div>
    </div>
    
    <?php include 'middle_footer.php'; ?>

</div>

<script type="text/javascript" src="/js/agents/jquery.js"></script>
<script type="text/javascript" src="/js/agents/layer/layer.js"></script>
<script type="text/javascript" src="/js/agents/common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript">
    settingHeight('.contentAll');
    seachMember();
    changeWinLose();

    // 查询会员
    function seachMember() {
        $('.seachBtn').off().on('click',function () {
            var url = 'middle_memberDetail.php?type=user&navtitle=会员信息';
            var enable = $('.enable').val(); // 状态
            var sort = $('.sort').val(); // 类型
            var orderby = $('.orderby').val(); // 升幂
            var winlosstext = $('.WinLossText').val(); // 输赢额度搜索
            var sea_text = $('.dlg_text').val(); // 搜索关键字
            url +='&enable='+enable+'&sort='+sort+'&orderby='+orderby+'&sea_text='+sea_text+'&winlosstext='+winlosstext;
            // console.log(url)
            parent.loadPageBox.location.href = url;

        })
    }
    
    // 输赢额度选择
    function changeWinLose() {
        $('.sort').on('change',function () {
            var val = $(this).val();
            var $topList_syed = $('.topList_syed');
            if(val=='WinLossCreditBigger' || val=='WinLossCreditSmaller'){
                $topList_syed.show();
            }else{
                $topList_syed.hide();
            }
        })

    }
    
</script>
</body>
</html>
