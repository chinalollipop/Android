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
$navtype = isset($_REQUEST['type'])?$_REQUEST['type']:'';
if(in_array(TPL_FILE_NAME, ['0086','0086dj', '6668','jinsha'])){
    $logoUrl = '';
    $logo = "logo_".TPL_FILE_NAME."png";
}else{
    $logoUrl = TPL_FILE_NAME;
    $logo = TPL_FILE_NAME."/logo.png";
}

$dateNow = date('Y-m-d');// 当前日期
$monthFirst = date('Y-m-01'); // 本月第一天

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
        .contentAll .index_top{position:relative;height:11.5rem}
        .index_top .top_content{border-radius:0;padding:3% 2%;height:7.5rem}
        .index_top .top_content>div{justify-content:space-between}
        .index_top .top_content>div .icon{display:inline-block;width:1.2rem;height:1.2rem;background-size:100%}
        .index_top .top_content>div .icon_wd{background-image:url(../images/index/icon_wd.png)}
        .index_top .top_content>div .icon_kf{background-image:url(../images/index/icon_lxwm.png)}
        .index_top .top_content .sy_con{margin:1rem auto;line-height:1.5rem}
        .index_top .top_content .sy_con p:last-child{font-weight:bold;font-size:1.5rem}
        .index_top .bottom{position:absolute;background:#fff;width:80%;height:4rem;left:50%;margin-left:-40%;bottom:.3rem;border-radius:5px;box-shadow:0 2px 3px rgba(0,0,0,0.2);color:#4e525e}
        .index_top .bottom .topList{-webkit-flex:auto;flex:auto}
        .index_top .bottom .topList span{display:block;line-height:2rem}
        .index_top .bottom .topList span:first-child{color:#496dd5;font-weight:bold;font-size:1.2rem}
        .contentAll .index_center{padding:.5rem 10% 3rem;}
        .index_center a{display:inline-block;width:49%;padding:1.5rem 0;color:#4e525e}
        .index_center a span{display:block;width:4rem;height:4rem;margin:0 auto .8rem;background-size:100%;background-repeat:no-repeat}
        .index_center a .icon_dlyj{background-image:url(../images/index/li_dlyj.png)}
        .index_center a .icon_dljs{background-image:url(../images/index/li_dljs.png)}
        .index_center a .icon_lsxx{background-image:url(../images/index/li_lsjl.png)}
        .index_center a .icon_tgwz{background-image:url(../images/index/li_tgwz.png)}
        .index_center a .icon_xzhy{background-image:url(../images/index/li_xzhy.png)}
        .index_center a .icon_zxwz{background-image:url(../images/index/li_zxwz.png)}
    </style>
</head>
<body>
<div class="contentAll flex">

    <div class="index_top">
        <div class="top_content linear_2">
            <div class="flex">
                <div class="flex"> <span class="icon icon_wd"> </span> &nbsp;&nbsp;代理商- <?php echo $_SESSION['UserName'];?> </div>
                <a class="flex" href="middle_contact.php?navtitle=联系中心" target="loadPageBox">客服&nbsp;&nbsp; <span class="icon icon_kf"> </span> </a>
            </div>
            <div class="sy_con">
                <p> 当月净输赢 (元)</p>
                <p class="jsy_amount"> 0.00 </p>
            </div>
        </div>
        <div class="bottom flex">
           <!-- <div class="topList">
                <span class="yjbl"> 0% </span>
                <span>佣金比例</span>
            </div>-->
            <div class="topList">
                <span class="xxyh_ac"> 0 </span>
                <span>下线用户</span>
            </div>
            <div class="topList">
                <span class="xzc_ac"> 0 </span>
                <span>新注册</span>
            </div>
            <div class="topList">
                <span class="hyyh_ac"> 0 </span>
                <span>活跃用户</span>
            </div>
        </div>
    </div>

    <div class="index_center contentCenterAll">
        <a href="middle_commission.php?navtitle=佣金查询" target="loadPageBox">
            <span class="icon icon_dlyj"></span>
            代理商佣金
        </a>
        <a href="middle_agentClause.php?navtitle=代理结算" target="loadPageBox">
            <span class="icon icon_dljs"></span>
            代理结算
        </a>
        <a href="middle_information.php?navtitle=历史讯息" target="loadPageBox">
            <span class="icon icon_lsxx"></span>
            历史信息
        </a>
        <a href="middle_extend.php?navtitle=推广网址" target="loadPageBox">
            <span class="icon icon_tgwz"></span>
            推广网址
        </a>
        <a href="middle_addMember.php?navtitle=新增会员" target="loadPageBox">
            <span class="icon icon_xzhy"></span>
            新增会员
        </a>
       <a href="javascript:;">
           <!-- <span class="icon icon_zxwz"></span>
            最新网址-->
        </a>
    </div>

    <?php include 'middle_footer.php'; ?>

</div>

<script type="text/javascript" src="/js/agents/jquery.js"></script>
<script type="text/javascript" src="/js/agents/layer/layer.js"></script>
<script type="text/javascript" src="/js/agents/common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript">
    settingHeight('.contentAll');
    getMemberUser();
    // 获取下线用户
    function getMemberUser() {
        var url = '/api/seachMemberApi.php';
        var $jsy_amount = $('.jsy_amount');
        var $xxyh = $('.xxyh_ac');
        var $xzc = $('.xzc_ac');
        var $hyyh = $('.hyyh_ac');
        var onlineNum = 0; // 在线人数
        var newNum = 0; // 新注册人数
        var monthFirst = '<?php echo $monthFirst;?>';
        var todayDay = '<?php echo $dateNow;?>';
        $jsy_amount.text('加载中...');
        $xxyh.text('加载中...');
        $xzc.text('加载中...');
        $hyyh.text('加载中...');

        $.ajax({
            type: 'POST',
            url:url,
            data:{indextype:'index'},
            dataType:'json',
            success:function(res){ // Status :0 启用,1 冻结, 2 停用,Online : 1 在线 其他离线
                if(res){ // 有结果返回
                    $xxyh.html(res.data.total);
                    for(var i=0;i<res.data.rows.length;i++){
                        if(res.data.rows[i].Online=='1'){ // 在线人数
                            onlineNum++;
                        }
                        if(res.data.rows[i].AddDate.substr(0,10)==todayDay){ // 新注册
                            newNum++;
                        }
                    }
                    $xzc.html(newNum);
                    $hyyh.html(onlineNum);
                }

            },
            error:function(){
                layer.msg('网络错误，请稍后重试',{time:alertComTime,shade: [0.2, '#000']});
            }
        });

        // 获取净输赢
        var url = '/app/agents/report_new/report_top.php';
        var dataParams = {
            indextype:'index',
            action:'api',
            date_start:monthFirst,
            date_end:todayDay
        };
        $.ajax({
            type: 'POST',
            url:url,
            data:dataParams,
            dataType:'json',
            success:function(res){
                if(res){ // 有结果返回
                    $jsy_amount.text(res.data.allBetWin);
                }

            },
            error:function(){
                layer.msg('网络错误，请稍后重试',{time:alertComTime,shade: [0.2, '#000']});
            }
        });
    }
    
</script>
</body>
</html>
