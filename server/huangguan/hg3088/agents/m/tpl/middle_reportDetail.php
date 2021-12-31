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
$uid = $_SESSION['Oid'];
$Alias = $_SESSION['Alias'];
$navtype = isset($_REQUEST['type'])?$_REQUEST['type']:'';
$navtitle = isset($_REQUEST['navtitle'])?$_REQUEST['navtitle']:'';

$date_start =isset($_REQUEST['date_start'])?trim($_REQUEST['date_start']):'';// 开始时间
$date_end =isset($_REQUEST['date_end'])?trim($_REQUEST['date_end']):'';// 结束时间

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
        .contentAll .contentCenterAll{background:#f1f6fb}
        .reportList{background:#fff;color:#acacac;font-size:1rem;text-align:left;padding:.5rem 1%;overflow:hidden;margin-bottom:7px}
        .reportList .title,.reportList .title a{justify-content:space-between;color:#000;font-size:1.2rem}
        .reportList .title a{color:blue}
        .reportList .list{display:-webkit-flex;display:flex;width:50%;height:2.3rem;align-items:center;float:left}
    </style>
</head>
<body>
<div class="contentAll flex">
    <?php include 'middle_header.php'; ?>

    <div class="contentCenterAll ">
        <!-- 所有 游戏总计 -->
        <div class="reportList reportAll">
            <div class="title flex"> 所有下注 </div>
                <div class="list"> <span>笔数:</span><span class="totalNum">0</span> </div>
                <div class="list"> <span>下注金额:</span><span class="totalBet">0.00</span> </div>
                <div class="list"> <span>实际投注:</span><span class="totalValid">0.00</span> </div>
                <div class="list"> <span>盈利:</span><span class="totalResult">0.00</span> </div>
                <div class="list"> <span>代理商结果:</span><span class="totalResult">0.00</span> </div>
                <div class="list"> <span>总代交收:</span><span class="totalResult">0.00</span> </div>
        </div>
        <!-- AG视讯 游戏总计 -->
        <div class="reportList reportAgLive">
            <div class="title flex"> <span>AG视讯</span> <a data-type="aglive" target="loadPageBox" > 查看详情 </a> </div>
            <div class="list"> <span>笔数:</span><span class="totalNum">0</span> </div>
            <div class="list"> <span>下注金额:</span><span class="totalBet">0.00</span> </div>
            <div class="list"> <span>实际投注:</span><span class="totalValid">0.00</span> </div>
            <div class="list"> <span>盈利:</span><span class="totalResult">0.00</span> </div>
            <div class="list"> <span>代理商结果:</span><span class="totalResult">0.00</span> </div>
            <div class="list"> <span>总代交收:</span><span class="totalResult">0.00</span> </div>
        </div>
        <!-- AG电子 游戏总计 -->
        <div class="reportList reportAgGame">
            <div class="title flex"> <span>AG电子</span> <a data-type="aggame" target="loadPageBox" > 查看详情 </a> </div>
            <div class="list"> <span>笔数:</span><span class="totalNum">0</span> </div>
            <div class="list"> <span>下注金额:</span><span class="totalBet">0.00</span> </div>
            <div class="list"> <span>实际投注:</span><span class="totalValid">0.00</span> </div>
            <div class="list"> <span>盈利:</span><span class="totalResult">0.00</span> </div>
            <div class="list"> <span>代理商结果:</span><span class="totalResult">0.00</span> </div>
            <div class="list"> <span>总代交收:</span><span class="totalResult">0.00</span> </div>
        </div>
        <!-- AG捕鱼王打鱼 游戏总计 -->
        <div class="reportList reportAgBy">
            <div class="title flex"> <span>AG捕鱼王打鱼 </span> <a data-type="agby" target="loadPageBox" > 查看详情 </a></div>
            <div class="list"> <span>笔数:</span><span class="totalNum">0</span> </div>
            <div class="list"> <span>子弹价值:</span><span class="totalBet">0.00</span> </div>
            <div class="list"> <span>实际投注:</span><span class="totalValid">0.00</span> </div>
            <div class="list"> <span>盈利:</span><span class="totalResult">0.00</span> </div>
            <div class="list"> <span>代理商结果:</span><span class="totalResult">0.00</span> </div>
            <div class="list"> <span>总代交收:</span><span class="totalResult">0.00</span> </div>
        </div>
        <!-- AG捕鱼王养鱼 游戏总计 -->
       <!-- <div class="reportList reportAgYy">
            <div class="title flex"> <span>AG捕鱼王养鱼 </span> <a data-type="agyy" target="loadPageBox" > 查看详情 </a></div>
            <div class="list"> <span>笔数:</span><span class="totalNum">0</span> </div>
            <div class="list"> <span>下注金额:</span><span class="totalBet">0.00</span> </div>
            <div class="list"> <span>实际投注:</span><span class="totalValid">0.00</span> </div>
            <div class="list"> <span>盈利:</span><span class="totalResult">0.00</span> </div>
            <div class="list"> <span>代理商结果:</span><span class="totalResult">0.00</span> </div>
            <div class="list"> <span>总代交收:</span><span class="totalResult">0.00</span> </div>
        </div>-->
        <!-- 皇冠体育 游戏总计 -->
        <div class="reportList reportSport">
            <div class="title flex"> <span>皇冠体育</span> <a data-type="sport" target="loadPageBox" > 查看详情 </a> </div>
            <div class="list"> <span>笔数:</span><span class="totalNum">0</span> </div>
            <div class="list"> <span>下注金额:</span><span class="totalBet">0.00</span> </div>
            <div class="list"> <span>实际投注:</span><span class="totalValid">0.00</span> </div>
            <div class="list"> <span>盈利:</span><span class="totalResult">0.00</span> </div>
            <div class="list"> <span>代理商结果:</span><span class="totalResult">0.00</span> </div>
            <div class="list"> <span>总代交收:</span><span class="totalResult">0.00</span> </div>
        </div>
        <?php
            if(in_array(TPL_FILE_NAME, ['0086','0086dj', '6668','bet365','nbet365'])){
        ?>
                <!-- 体育彩票 游戏总计 -->
                <div class="reportList reportLottery">
                    <div class="title flex"> <span>彩票</span> <a data-type="lottery" target="loadPageBox" > 查看详情 </a> </div>
                    <div class="list"> <span>笔数:</span><span class="totalNum">0</span> </div>
                    <div class="list"> <span>下注金额:</span><span class="totalBet">0.00</span> </div>
                    <div class="list"> <span>实际投注:</span><span class="totalValid">0.00</span> </div>
                    <div class="list"> <span>盈利:</span><span class="totalResult">0.00</span> </div>
                    <div class="list"> <span>代理商结果:</span><span class="totalResult">0.00</span> </div>
                    <div class="list"> <span>总代交收:</span><span class="totalResult">0.00</span> </div>
                </div>
        <?php } ?>

        <!-- 开元棋牌 游戏总计 -->
        <div class="reportList reportKyChess">
            <div class="title flex"> <span>开元棋牌</span> <a data-type="kychess" target="loadPageBox" > 查看详情 </a> </div>
            <div class="list"> <span>笔数:</span><span class="totalNum">0</span> </div>
            <div class="list"> <span>下注金额:</span><span class="totalBet">0.00</span> </div>
            <div class="list"> <span>实际投注:</span><span class="totalValid">0.00</span> </div>
            <div class="list"> <span>盈利:</span><span class="totalResult">0.00</span> </div>
            <div class="list"> <span>代理商结果:</span><span class="totalResult">0.00</span> </div>
            <div class="list"> <span>总代交收:</span><span class="totalResult">0.00</span> </div>
        </div>
        <!-- 乐游棋牌 游戏总计 -->
        <div class="reportList reportLyChess">
            <div class="title flex"> <span>乐游棋牌</span> <a data-type="lychess" target="loadPageBox" > 查看详情 </a> </div>
            <div class="list"> <span>笔数:</span><span class="totalNum">0</span> </div>
            <div class="list"> <span>下注金额:</span><span class="totalBet">0.00</span> </div>
            <div class="list"> <span>实际投注:</span><span class="totalValid">0.00</span> </div>
            <div class="list"> <span>盈利:</span><span class="totalResult">0.00</span> </div>
            <div class="list"> <span>代理商结果:</span><span class="totalResult">0.00</span> </div>
            <div class="list"> <span>总代交收:</span><span class="totalResult">0.00</span> </div>
        </div>
        <!-- VG棋牌 游戏总计 -->
        <div class="reportList reportVgChess">
            <div class="title flex"> <span>VG棋牌</span> <a data-type="vgchess" target="loadPageBox" > 查看详情 </a> </div>
            <div class="list"> <span>笔数:</span><span class="totalNum">0</span> </div>
            <div class="list"> <span>下注金额:</span><span class="totalBet">0.00</span> </div>
            <div class="list"> <span>实际投注:</span><span class="totalValid">0.00</span> </div>
            <div class="list"> <span>盈利:</span><span class="totalResult">0.00</span> </div>
            <div class="list"> <span>代理商结果:</span><span class="totalResult">0.00</span> </div>
            <div class="list"> <span>总代交收:</span><span class="totalResult">0.00</span> </div>
        </div>
        <!-- 皇冠棋牌 游戏总计 -->
       <!-- <div class="reportList reportHgChess">
            <div class="title flex"> <span>皇冠棋牌</span> <a data-type="hgchess" target="loadPageBox" > 查看详情 </a> </div>
            <div class="list"> <span>笔数:</span><span class="totalNum">0</span> </div>
            <div class="list"> <span>下注金额:</span><span class="totalBet">0.00</span> </div>
            <div class="list"> <span>实际投注:</span><span class="totalValid">0.00</span> </div>
            <div class="list"> <span>盈利:</span><span class="totalResult">0.00</span> </div>
            <div class="list"> <span>代理商结果:</span><span class="totalResult">0.00</span> </div>
            <div class="list"> <span>总代交收:</span><span class="totalResult">0.00</span> </div>
        </div>-->
        <!-- 快乐棋牌 游戏总计 -->
        <div class="reportList reportKlChess">
            <div class="title flex"> <span>快乐棋牌</span> <a data-type="klchess" target="loadPageBox" > 查看详情 </a> </div>
            <div class="list"> <span>笔数:</span><span class="totalNum">0</span> </div>
            <div class="list"> <span>下注金额:</span><span class="totalBet">0.00</span> </div>
            <div class="list"> <span>实际投注:</span><span class="totalValid">0.00</span> </div>
            <div class="list"> <span>盈利:</span><span class="totalResult">0.00</span> </div>
            <div class="list"> <span>代理商结果:</span><span class="totalResult">0.00</span> </div>
            <div class="list"> <span>总代交收:</span><span class="totalResult">0.00</span> </div>
        </div>

        <!-- 泛亚电竞 游戏总计 -->
        <div class="reportList reportDj">
            <div class="title flex"> <span>泛亚电竞</span> <a data-type="fydj" target="loadPageBox" > 查看详情 </a> </div>
            <div class="list"> <span>笔数:</span><span class="totalNum">0</span> </div>
            <div class="list"> <span>下注金额:</span><span class="totalBet">0.00</span> </div>
            <div class="list"> <span>实际投注:</span><span class="totalValid">0.00</span> </div>
            <div class="list"> <span>盈利:</span><span class="totalResult">0.00</span> </div>
            <div class="list"> <span>代理商结果:</span><span class="totalResult">0.00</span> </div>
            <div class="list"> <span>总代交收:</span><span class="totalResult">0.00</span> </div>
        </div>
        <!-- 雷火电竞 游戏总计 -->
        <div class="reportList reportFireDj">
            <div class="title flex"> <span>雷火电竞</span> <a data-type="firedj" target="loadPageBox" > 查看详情 </a> </div>
            <div class="list"> <span>笔数:</span><span class="totalNum">0</span> </div>
            <div class="list"> <span>下注金额:</span><span class="totalBet">0.00</span> </div>
            <div class="list"> <span>实际投注:</span><span class="totalValid">0.00</span> </div>
            <div class="list"> <span>盈利:</span><span class="totalResult">0.00</span> </div>
            <div class="list"> <span>代理商结果:</span><span class="totalResult">0.00</span> </div>
            <div class="list"> <span>总代交收:</span><span class="totalResult">0.00</span> </div>
        </div>
        <!-- OG视讯 游戏总计 -->
        <div class="reportList reportOgLive">
            <div class="title flex"> <span>OG视讯</span> <a data-type="oglive" target="loadPageBox" > 查看详情 </a> </div>
            <div class="list"> <span>笔数:</span><span class="totalNum">0</span> </div>
            <div class="list"> <span>下注金额:</span><span class="totalBet">0.00</span> </div>
            <div class="list"> <span>实际投注:</span><span class="totalValid">0.00</span> </div>
            <div class="list"> <span>盈利:</span><span class="totalResult">0.00</span> </div>
            <div class="list"> <span>代理商结果:</span><span class="totalResult">0.00</span> </div>
            <div class="list"> <span>总代交收:</span><span class="totalResult">0.00</span> </div>
        </div>
        <!-- BBIN视讯 游戏总计 -->
        <div class="reportList reportBbinLive">
            <div class="title flex"> <span>BBIN视讯</span> <a data-type="bbinlive" target="loadPageBox" > 查看详情 </a> </div>
            <div class="list"> <span>笔数:</span><span class="totalNum">0</span> </div>
            <div class="list"> <span>下注金额:</span><span class="totalBet">0.00</span> </div>
            <div class="list"> <span>实际投注:</span><span class="totalValid">0.00</span> </div>
            <div class="list"> <span>盈利:</span><span class="totalResult">0.00</span> </div>
            <div class="list"> <span>代理商结果:</span><span class="totalResult">0.00</span> </div>
            <div class="list"> <span>总代交收:</span><span class="totalResult">0.00</span> </div>
        </div>
        <!-- MG电子 游戏总计 -->
        <div class="reportList reportMgGame">
            <div class="title flex"> <span>MG电子</span> <a data-type="mggame" target="loadPageBox" > 查看详情 </a> </div>
            <div class="list"> <span>笔数:</span><span class="totalNum">0</span> </div>
            <div class="list"> <span>下注金额:</span><span class="totalBet">0.00</span> </div>
            <div class="list"> <span>实际投注:</span><span class="totalValid">0.00</span> </div>
            <div class="list"> <span>盈利:</span><span class="totalResult">0.00</span> </div>
            <div class="list"> <span>代理商结果:</span><span class="totalResult">0.00</span> </div>
            <div class="list"> <span>总代交收:</span><span class="totalResult">0.00</span> </div>
        </div>
        <!-- MW电子 游戏总计 -->
        <div class="reportList reportMwGame">
            <div class="title flex"> <span>MW电子</span> <a data-type="mwgame" target="loadPageBox" > 查看详情 </a> </div>
            <div class="list"> <span>笔数:</span><span class="totalNum">0</span> </div>
            <div class="list"> <span>下注金额:</span><span class="totalBet">0.00</span> </div>
            <div class="list"> <span>实际投注:</span><span class="totalValid">0.00</span> </div>
            <div class="list"> <span>盈利:</span><span class="totalResult">0.00</span> </div>
            <div class="list"> <span>代理商结果:</span><span class="totalResult">0.00</span> </div>
            <div class="list"> <span>总代交收:</span><span class="totalResult">0.00</span> </div>
        </div>
        <!-- CQ9电子 游戏总计 -->
        <div class="reportList reportcQ9Game">
            <div class="title flex"> <span>CQ9电子</span> <a data-type="cqgame" target="loadPageBox" > 查看详情 </a> </div>
            <div class="list"> <span>笔数:</span><span class="totalNum">0</span> </div>
            <div class="list"> <span>下注金额:</span><span class="totalBet">0.00</span> </div>
            <div class="list"> <span>实际投注:</span><span class="totalValid">0.00</span> </div>
            <div class="list"> <span>盈利:</span><span class="totalResult">0.00</span> </div>
            <div class="list"> <span>代理商结果:</span><span class="totalResult">0.00</span> </div>
            <div class="list"> <span>总代交收:</span><span class="totalResult">0.00</span> </div>
        </div>
        <!-- FG电子 游戏总计 -->
        <div class="reportList reportFgGame">
            <div class="title flex"> <span>FG电子</span> <a data-type="fggame" target="loadPageBox" > 查看详情 </a> </div>
            <div class="list"> <span>笔数:</span><span class="totalNum">0</span> </div>
            <div class="list"> <span>下注金额:</span><span class="totalBet">0.00</span> </div>
            <div class="list"> <span>实际投注:</span><span class="totalValid">0.00</span> </div>
            <div class="list"> <span>盈利:</span><span class="totalResult">0.00</span> </div>
            <div class="list"> <span>代理商结果:</span><span class="totalResult">0.00</span> </div>
            <div class="list"> <span>总代交收:</span><span class="totalResult">0.00</span> </div>
        </div>
        <?php
            if( !(in_array(TPL_FILE_NAME, ['0086','0086dj', '6668','bet365','nbet365'])) ){
                ?>

                <!-- 彩票信用盘 游戏总计 -->
                <div class="reportList reportLotteryXy">
                    <div class="title flex"> <span>彩票信用盘</span> <a data-type="xyp" target="loadPageBox" > 查看详情 </a> </div>
                    <div class="list"> <span>笔数:</span><span class="totalNum">0</span> </div>
                    <div class="list"> <span>下注金额:</span><span class="totalBet">0.00</span> </div>
                    <div class="list"> <span>实际投注:</span><span class="totalValid">0.00</span> </div>
                    <div class="list"> <span>盈利:</span><span class="totalResult">0.00</span> </div>
                    <div class="list"> <span>代理商结果:</span><span class="totalResult">0.00</span> </div>
                    <div class="list"> <span>总代交收:</span><span class="totalResult">0.00</span> </div>
                </div>
                <!-- 彩票官方盘 游戏总计 -->
                <div class="reportList reportLotteryGf">
                    <div class="title flex"> <span>彩票官方盘</span> <a data-type="gfp" target="loadPageBox" > 查看详情 </a> </div>
                    <div class="list"> <span>笔数:</span><span class="totalNum">0</span> </div>
                    <div class="list"> <span>下注金额:</span><span class="totalBet">0.00</span> </div>
                    <div class="list"> <span>实际投注:</span><span class="totalValid">0.00</span> </div>
                    <div class="list"> <span>盈利:</span><span class="totalResult">0.00</span> </div>
                    <div class="list"> <span>代理商结果:</span><span class="totalResult">0.00</span> </div>
                    <div class="list"> <span>总代交收:</span><span class="totalResult">0.00</span> </div>
                </div>
                <!-- 彩票官方追号 游戏总计 -->
                <div class="reportList reportLotteryGfZh">
                    <div class="title flex"> <span>彩票官方追号</span> <a data-type="gfzh" target="loadPageBox" > 查看详情 </a> </div>
                    <div class="list"> <span>笔数:</span><span class="totalNum">0</span> </div>
                    <div class="list"> <span>下注金额:</span><span class="totalBet">0.00</span> </div>
                    <div class="list"> <span>实际投注:</span><span class="totalValid">0.00</span> </div>
                    <div class="list"> <span>盈利:</span><span class="totalResult">0.00</span> </div>
                    <div class="list"> <span>代理商结果:</span><span class="totalResult">0.00</span> </div>
                    <div class="list"> <span>总代交收:</span><span class="totalResult">0.00</span> </div>
                </div>


            <?php   }  ?>


    </div>

    <?php include 'middle_footer.php'; ?>

</div>

<script type="text/javascript" src="/js/agents/jquery.js"></script>
<script type="text/javascript" src="/js/agents/layer/layer.js"></script>
<script type="text/javascript" src="/js/agents/common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript">
    $(function () {
        var date_start= '<?php echo $date_start;?>';
        var date_end= '<?php echo $date_end;?>';

        settingHeight('.contentAll');
        getReportDetail();
        showReportDetail();

        // 查看报表详情
        function showReportDetail() {
            $('.reportList').on('click','a',function () {
                var url = 'middle_reportSecDetail.php?date_start='+date_start+'&date_end='+date_end+'&type=bb';
                var title = $(this).prev().text()+'-报表详情';
                var type = $(this).attr('data-type');
                url +='&reportType='+type+'&navtitle='+title;
                parent.loadPageBox.location.href = url;
            })
        }

        // 获取会员信息
        var submitflag = false ; // 防止重复提交
        function getReportDetail() {
            if(submitflag){
                return false ;
            }
            var $reportAll = $('.reportAll');
            var $reportAgLive = $('.reportAgLive');
            var $reportAgGame = $('.reportAgGame');
            var $reportAgBy = $('.reportAgBy');
            var $reportAgYy = $('.reportAgYy');
            var $reportSport = $('.reportSport');
            var $reportLottery = $('.reportLottery');
            var $reportKyChess = $('.reportKyChess');
            var $reportLyChess = $('.reportLyChess');
            var $reportVgChess = $('.reportVgChess');
            var $reportHgChess = $('.reportHgChess');
            var $reportKlChess = $('.reportKlChess');
            var $reportDj = $('.reportDj');
            var $reportFireDj = $('.reportFireDj');
            var $reportOgLive = $('.reportOgLive');
            var $reportBbinLive = $('.reportBbinLive');
            var $reportMgGame = $('.reportMgGame');
            var $reportMwGame = $('.reportMwGame');
            var $reportcQ9Game = $('.reportcQ9Game');
            var $reportFgGame = $('.reportFgGame');
            var $reportLotteryXy = $('.reportLotteryXy');
            var $reportLotteryGf = $('.reportLotteryGf');
            var $reportLotteryGfZh = $('.reportLotteryGfZh');

            submitflag = true ;
            var url = '/app/agents/report_new/report_top.php';
            var dataParams = {
                action:'api',
                date_start:date_start,
                date_end:date_end
            };
            $.ajax({
                type: 'POST',
                url:url,
                data:dataParams,
                dataType:'json',
                success:function(res){

                    if(res){ // 有结果返回
                        $reportAll.find('.totalNum').text(res.data.allBetNum);
                        $reportAll.find('.totalBet').text(res.data.allBetTotal);
                        $reportAll.find('.totalValid').text(res.data.allBetValid);
                        $reportAll.find('.totalResult').text(res.data.allBetWin);

                        $reportAgLive.find('.totalNum').text(res.data.ag_allBetNum);
                        $reportAgLive.find('.totalBet').text(res.data.ag_allBetTotal);
                        $reportAgLive.find('.totalValid').text(res.data.ag_allBetValid);
                        $reportAgLive.find('.totalResult').text(res.data.ag_allBetWin);

                        $reportAgGame.find('.totalNum').text(res.data.agGame_allBetNum);
                        $reportAgGame.find('.totalBet').text(res.data.agGame_allBetTotal);
                        $reportAgGame.find('.totalValid').text(res.data.agGame_allBetValid);
                        $reportAgGame.find('.totalResult').text(res.data.agGame_allBetWin);

                        $reportAgBy.find('.totalNum').text(res.data.agBy_allBetNum);
                        $reportAgBy.find('.totalBet').text(res.data.agBy_allBetTotal);
                        $reportAgBy.find('.totalValid').text(res.data.agBy_allBetValid);
                        $reportAgBy.find('.totalResult').text(res.data.agBy_allBetWin);

                        // $reportAgYy.find('.totalNum').text(res.data.agYy_allBetNum);
                        // $reportAgYy.find('.totalBet').text(res.data.agYy_allBetTotal);
                        // $reportAgYy.find('.totalValid').text(res.data.agYy_allBetValid);
                        // $reportAgYy.find('.totalResult').text(res.data.agYy_allBetWin);

                        $reportSport.find('.totalNum').text(res.data.sport_allBetNum);
                        $reportSport.find('.totalBet').text(res.data.sport_allBetTotal);
                        $reportSport.find('.totalValid').text(res.data.sport_allBetValid);
                        $reportSport.find('.totalResult').text(res.data.sport_allBetWin);

                        $reportLottery.find('.totalNum').text(res.data.lottery_allBetNum);
                        $reportLottery.find('.totalBet').text(res.data.lottery_allBetTotal);
                        $reportLottery.find('.totalValid').text(res.data.lottery_allBetValid);
                        $reportLottery.find('.totalResult').text(res.data.lottery_allBetWin);

                        $reportKyChess.find('.totalNum').text(res.data.kyChess_allBetNum);
                        $reportKyChess.find('.totalBet').text(res.data.kyChess_allBetTotal);
                        $reportKyChess.find('.totalValid').text(res.data.kyChess_allBetValid);
                        $reportKyChess.find('.totalResult').text(res.data.kyChess_allBetWin);

                        $reportLyChess.find('.totalNum').text(res.data.lyChess_allBetNum);
                        $reportLyChess.find('.totalBet').text(res.data.lyChess_allBetTotal);
                        $reportLyChess.find('.totalValid').text(res.data.lyChess_allBetValid);
                        $reportLyChess.find('.totalResult').text(res.data.lyChess_allBetWin);

                        $reportVgChess.find('.totalNum').text(res.data.vgChess_allBetNum);
                        $reportVgChess.find('.totalBet').text(res.data.vgChess_allBetTotal);
                        $reportVgChess.find('.totalValid').text(res.data.vgChess_allBetValid);
                        $reportVgChess.find('.totalResult').text(res.data.vgChess_allBetWin);

                        $reportHgChess.find('.totalNum').text(res.data.hgChess_allBetNum);
                        $reportHgChess.find('.totalBet').text(res.data.hgChess_allBetTotal);
                        $reportHgChess.find('.totalValid').text(res.data.hgChess_allBetValid);
                        $reportHgChess.find('.totalResult').text(res.data.hgChess_allBetWin);

                        $reportKlChess.find('.totalNum').text(res.data.klChess_allBetNum);
                        $reportKlChess.find('.totalBet').text(res.data.klChess_allBetTotal);
                        $reportKlChess.find('.totalValid').text(res.data.klChess_allBetValid);
                        $reportKlChess.find('.totalResult').text(res.data.klChess_allBetWin);

                        $reportDj.find('.totalNum').text(res.data.avia_allBetNum);
                        $reportDj.find('.totalBet').text(res.data.avia_allBetTotal);
                        $reportDj.find('.totalValid').text(res.data.avia_allBetValid);
                        $reportDj.find('.totalResult').text(res.data.avia_allBetWin);

                        $reportFireDj.find('.totalNum').text(res.data.fire_allBetNum);
                        $reportFireDj.find('.totalBet').text(res.data.fire_allBetTotal);
                        $reportFireDj.find('.totalValid').text(res.data.fire_allBetValid);
                        $reportFireDj.find('.totalResult').text(res.data.fire_allBetWin);

                        $reportOgLive.find('.totalNum').text(res.data.og_allBetNum);
                        $reportOgLive.find('.totalBet').text(res.data.og_allBetTotal);
                        $reportOgLive.find('.totalValid').text(res.data.og_allBetValid);
                        $reportOgLive.find('.totalResult').text(res.data.og_allBetWin);

                        $reportBbinLive.find('.totalNum').text(res.data.bbin_allBetNum);
                        $reportBbinLive.find('.totalBet').text(res.data.bbin_allBetTotal);
                        $reportBbinLive.find('.totalValid').text(res.data.bbin_allBetValid);
                        $reportBbinLive.find('.totalResult').text(res.data.bbin_allBetWin);

                        $reportMgGame.find('.totalNum').text(res.data.mg_allBetNum);
                        $reportMgGame.find('.totalBet').text(res.data.mg_allBetTotal);
                        $reportMgGame.find('.totalValid').text(res.data.mg_allBetValid);
                        $reportMgGame.find('.totalResult').text(res.data.mg_allBetWin);

                        $reportMwGame.find('.totalNum').text(res.data.mw_allBetNum);
                        $reportMwGame.find('.totalBet').text(res.data.mw_allBetTotal);
                        $reportMwGame.find('.totalValid').text(res.data.mw_allBetValid);
                        $reportMwGame.find('.totalResult').text(res.data.mw_allBetWin);

                        $reportcQ9Game.find('.totalNum').text(res.data.cq_allBetNum);
                        $reportcQ9Game.find('.totalBet').text(res.data.cq_allBetTotal);
                        $reportcQ9Game.find('.totalValid').text(res.data.cq_allBetValid);
                        $reportcQ9Game.find('.totalResult').text(res.data.cq_allBetWin);

                        $reportFgGame.find('.totalNum').text(res.data.fg_allBetNum);
                        $reportFgGame.find('.totalBet').text(res.data.fg_allBetTotal);
                        $reportFgGame.find('.totalValid').text(res.data.fg_allBetValid);
                        $reportFgGame.find('.totalResult').text(res.data.fg_allBetWin);

                        $reportLotteryXy.find('.totalNum').text(res.data.cpxy_allBetNum);
                        $reportLotteryXy.find('.totalBet').text(res.data.cpxy_allBetTotal);
                        $reportLotteryXy.find('.totalValid').text(res.data.cpxy_allBetValid);
                        $reportLotteryXy.find('.totalResult').text(res.data.cpxy_allBetWin);

                        $reportLotteryGf.find('.totalNum').text(res.data.cpgf_allBetNum);
                        $reportLotteryGf.find('.totalBet').text(res.data.cpgf_allBetTotal);
                        $reportLotteryGf.find('.totalValid').text(res.data.cpgf_allBetValid);
                        $reportLotteryGf.find('.totalResult').text(res.data.cpgf_allBetWin);

                        $reportLotteryGfZh.find('.totalNum').text(res.data.cpgfzh_allBetNum);
                        $reportLotteryGfZh.find('.totalBet').text(res.data.cpgfzh_allBetTotal);
                        $reportLotteryGfZh.find('.totalValid').text(res.data.cpgfzh_allBetValid);
                        $reportLotteryGfZh.find('.totalResult').text(res.data.cpgfzh_allBetWin);

                    }

                },
                error:function(){
                    submitflag = false ;
                    layer.msg('网络错误，请稍后重试',{time:alertComTime,shade: [0.2, '#000']});
                }
            });
        }


    })

</script>
</body>
</html>
