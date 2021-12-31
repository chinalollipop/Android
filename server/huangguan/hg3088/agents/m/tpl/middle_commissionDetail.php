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

$agent =isset($_REQUEST['agent'])?trim($_REQUEST['agent']):'';// 代理商
$date =isset($_REQUEST['date'])?trim($_REQUEST['date']):'';// 时间

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
        .tableContent{width: 98%;margin: 8px auto;color: #3c3941;border-bottom: 1px solid #acacac;border-right: 1px solid #acacac;}
        .tableContent .bg_tr{background: #f8fafd;}
        .tableContent .bg_tr td{justify-content: center;}
        tr,td{display:-webkit-flex;display:flex;}
        .tableContent td{padding:8px 5px;width:50%;flex:1;justify-content:space-between;border-bottom:0;border-right:0}
        .tableContent td.top span{text-align:center;display:inline-block;width:32.5%;margin:0}

    </style>
</head>
<body>
<div class="contentAll flex">
    <?php include 'middle_header.php'; ?>

    <div class="contentCenterAll ">
        <div class="commissionContent">
            <table class="tableContent">
                <tbody>
                <tr class="bg_tr">
                    <td colspan="2" class="top">
                        <span> 代理商会员数： </span>
                        <span> <?php echo $agent;?> </span>
                        <span class="agNumber"> 0 </span>
                    </td>
                </tr>
                <tr class="bg_tr">
                    <td>体育赛事</td>
                    <td>彩票游戏</td>
                </tr>

                <?php
                    if(in_array(TPL_FILE_NAME, ['0086','0086dj', '6668','bet365','nbet365'])){
                        echo ' <tr>
                                    <td><span class="title">皇冠体育：</span> <span class="hgSportNumber"> 0.00 </span> </td>
                                    <td><span class="title">体育彩票：</span> <span class="lotteryNumber"> 0.00 </span> </td>
                               </tr>';
                    }else{
                        echo '<tr>
                                    <td ><span class="title">皇冠体育：</span> <span class="hgSportNumber"> 0.00 </span> </td> <!-- rowspan="3" -->
                                    <td><span class="title">彩票官方盘：</span> <span class="lotteryGfNumber"> 0.00 </span> </td>
                               </tr>
                               <tr>
                                    <td></td>
                                    <td><span class="title">彩票信用盘：</span> <span class="lotteryXyNumber"> 0.00 </span> </td>
                               </tr>
                                <tr>
                                    <td></td>
                                    <td><span class="title">彩票追号：</span> <span class="lotteryGfzhNumber"> 0.00 </span> </td>
                               </tr>';
                    }
                ?>

                <tr>
                    <td><span class="title">体育赛事合计：</span> <span class="hgSportTotal"> 0.00 </span> </td>
                    <td><span class="title">彩票游戏合计：</span> <span class="lotteryTotal"> 0.00 </span> </td>
                </tr>
                </tbody>
            </table>

            <table class="tableContent">
                <tbody>
                <tr class="bg_tr">
                    <td>真人视讯</td>
                    <td>电子竞技</td>
                </tr>
                <tr>
                    <td><span class="title">AG视讯：</span> <span class="agLiveNumber"> 0.00 </span> </td>
                    <td><span class="title">泛亚电竞：</span> <span class="fydjNumber"> 0.00 </span> </td> <!-- rowspan="3" -->
                </tr>
                <tr>
                    <td><span class="title">OG视讯：</span> <span class="ogLiveNumber"> 0.00 </span> </td>
                    <td><span class="title">雷火电竞：</span> <span class="firedjNumber"> 0.00 </span></td>
                </tr>
                <tr>
                    <td><span class="title">BBIN视讯：</span> <span class="bbinLiveNumber"> 0.00 </span> </td>
                    <td></td>
                </tr>
                <tr>
                    <td><span class="title">真人视讯合计：</span> <span class="liveTotal"> 0.00 </span> </td>
                    <td><span class="title">电子竞技合计：</span> <span class="fydjTotal"> 0.00 </span> </td>
                </tr>
                </tbody>
            </table>
            <table class="tableContent">
                <tbody>
                <tr class="bg_tr">
                    <td>电子游艺</td>
                    <td>棋牌游戏</td>
                </tr>
                <tr>
                    <td><span class="title">AG电子：</span> <span class="agGameNumber"> 0.00 </span> </td>
                    <td><span class="title">开元棋牌：</span> <span class="kyChessNumber"> 0.00 </span> </td>
                </tr>
                <tr>
                    <td><span class="title">MG电子：</span> <span class="mgGameNumber"> 0.00 </span> </td>
                    <td><span class="title">VG棋牌：</span> <span class="vgChessNumber"> 0.00 </span> </td>
                </tr>
                <tr>
                    <td><span class="title">CQ9电子：</span> <span class="cqGameNumber"> 0.00 </span> </td>
                    <td><span class="title">乐游棋牌：</span> <span class="lyChessNumber"> 0.00 </span> </td>
                </tr>
                <tr>
                    <td><span class="title">MW电子：</span> <span class="mwGameNumber"> 0.00 </span> </td>
                    <td><span class="title">快乐棋牌：</span> <span class="klChessNumber"> 0.00 </span> </td>
                </tr>
                <tr>
                    <td><span class="title">FG电子：</span> <span class="fgGameNumber"> 0.00 </span> </td>
                    <td> <!--<span class="title">皇冠棋牌：</span> <span class="hgChessNumber"> 0.00 </span>--> </td>
                </tr>
                <tr>
                    <td><span class="title">AG捕鱼王：</span> <span class="agByNumber"> 0.00 </span> </td>
                    <td></td>
                </tr>
                <tr>
                    <td><span class="title">电子游艺合计：</span> <span class="gameTotal"> 0.00 </span> </td>
                    <td><span class="title">棋牌游戏合计：</span> <span class="chessTotal"> 0.00 </span> </td>
                </tr>
                </tbody>
            </table>

            <table class="tableContent">
                <tbody>
                <tr class="bg_tr">
                    <td>佣金手续费</td>
                    <td>手续汇总</td>
                </tr>
                <tr>
                    <td><span class="title">可获佣金：</span> <span class="khyjNumber"> 0.00 </span> </td>
                    <td><span class="title">返水总量：</span> <span class="fszlNumber"> 0.00 </span> </td>
                </tr>
                <tr>
                    <td><span class="title">存款手续费：</span> <span class="cksxfNumber"> 0.00 </span> </td>
                    <td><span class="title">入款总量：</span> <span class="rkzlNumber"> 0.00 </span> </td>
                </tr>
                <tr>
                    <td><span class="title">取款手续费：</span> <span class="qksxfNumber"> 0.00 </span> </td>
                    <td><span class="title">出款总量：</span> <span class="ckzlNumber"> 0.00 </span> </td>
                </tr>
                <tr>
                    <td><span class="title">行政费用：</span> <span class="xzfyNumber"> 0.00 </span> </td>
                    <td><span class="title">优惠总量：</span> <span class="yhzlNumber"> 0.00 </span> </td>
                </tr>
                <tr>
                    <td><span class="title">各馆总输赢：</span> <span class="allTotalNumber"> 0.00 </span> </td>
                    <td><span class="title">彩金总量：</span> <span class="cjTotalNumber"> 0.00 </span> </td>
                </tr>

                </tbody>
            </table>

        </div>

    </div>

    <?php include 'middle_footer.php'; ?>

</div>

<script type="text/javascript" src="/js/agents/jquery.js"></script>
<script type="text/javascript" src="/js/agents/layer/layer.js"></script>
<script type="text/javascript" src="/js/agents/common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript">
    $(function () {
        settingHeight('.contentAll');
        getCommissioDetail();

        function getCommissioDetail() {
            var agent = '<?php echo $agent;?>';
            var date = '<?php echo $date;?>';
            var url = '/app/agents/agents/agents_commission.php';
            var $agNumber = $('.agNumber'); // 会员数
            var $hgSportNumber = $('.hgSportNumber'); // 体育输赢
            var $hgSportTotal = $('.hgSportTotal'); // 体育总输赢
            var $lotteryNumber = $('.lotteryNumber'); // 体育彩票输赢
            var $lotteryGfNumber = $('.lotteryGfNumber'); // 彩票官方输赢
            var $lotteryXyNumber = $('.lotteryXyNumber'); // 彩票信用输赢
            var $lotteryGfzhNumber = $('.lotteryGfzhNumber'); // 彩票追号输赢
            var $lotteryTotal = $('.lotteryTotal'); // 彩票总输赢
            var $agLiveNumber = $('.agLiveNumber'); // AG真人输赢
            var $ogLiveNumber = $('.ogLiveNumber'); // OG真人输赢
            var $bbinLiveNumber = $('.bbinLiveNumber'); // BBIN真人输赢
            var $liveTotal = $('.liveTotal'); // 真人总输赢
            var $fydjNumber = $('.fydjNumber'); // 泛亚电竞输赢
            var $firedjNumber = $('.firedjNumber'); // 雷火电竞输赢
            var $fydjTotal = $('.fydjTotal'); // 电竞总输赢
            var $agGameNumber = $('.agGameNumber'); // AG电子输赢
            var $mgGameNumber = $('.mgGameNumber'); // MG电子输赢
            var $cqGameNumber = $('.cqGameNumber'); // CQ9电子输赢
            var $mwGameNumber = $('.mwGameNumber'); // MW电子输赢
            var $fgGameNumber = $('.fgGameNumber'); // FG电子输赢
            var $agByNumber = $('.agByNumber'); // AG捕鱼输赢
            var $gameTotal = $('.gameTotal'); // 电子游艺总输赢
            var $kyChessNumber = $('.kyChessNumber'); // 开元棋牌输赢
            var $lyChessNumber = $('.lyChessNumber'); // 乐游棋牌输赢
            var $vgChessNumber = $('.vgChessNumber'); // VG棋牌输赢
            var $hgChessNumber = $('.hgChessNumber'); // 皇冠棋牌输赢
            var $klChessNumber = $('.klChessNumber'); // 快乐棋牌输赢
            var $chessTotal = $('.chessTotal'); // 棋牌总输赢
            var $khyjNumber = $('.khyjNumber'); // 可获总佣金
            var $cksxfNumber = $('.cksxfNumber'); // 存款手续费
            var $qksxfNumber = $('.qksxfNumber'); // 取款手续费
            var $xzfyNumber = $('.xzfyNumber'); // 行政费用
            var $allTotalNumber = $('.allTotalNumber'); // 各馆总输赢
            var $fszlNumber = $('.fszlNumber'); // 返水总量
            var $rkzlNumber = $('.rkzlNumber'); // 入款总量
            var $ckzlNumber = $('.ckzlNumber'); // 出款总量
            var $yhzlNumber = $('.yhzlNumber'); // 优惠总量
            var $cjTotalNumber = $('.cjTotalNumber'); // 彩金总量

            var dataParams = {
                action:'api',
                agent:agent,
                date:date
            };
            $.ajax({
                type: 'POST',
                url:url,
                data:dataParams,
                dataType:'json',
                success:function(res){
                    /*
                    *  member_num 会员数，ommission_agent 总佣金，
                    * */
                    if(res){ // 有结果返回
                        $agNumber.text(res.data.member_num);
                        $hgSportNumber.text(keepTwoDecimal(res.data.user_win.hg));
                        $hgSportTotal.text(keepTwoDecimal(res.data.total_sports));
                        $lotteryNumber.text(keepTwoDecimal(res.data.user_win.cp));
                        $lotteryGfNumber.text(keepTwoDecimal(res.data.user_win.project));
                        $lotteryXyNumber.text(keepTwoDecimal(res.data.user_win.ssc));
                        $lotteryGfzhNumber.text(keepTwoDecimal(res.data.user_win.trace));
                        $lotteryTotal.text(keepTwoDecimal(res.data.total_lottery));
                        $agLiveNumber.text(keepTwoDecimal(res.data.user_win.ag));
                        $ogLiveNumber.text(keepTwoDecimal(res.data.user_win.og));
                        $bbinLiveNumber.text(keepTwoDecimal(res.data.user_win.bbin));
                        $liveTotal.text(keepTwoDecimal(res.data.total_video));
                        $fydjNumber.text(keepTwoDecimal(res.data.user_win.avia));
                        $firedjNumber.text(keepTwoDecimal(res.data.user_win.fire));
                        $fydjTotal.text(keepTwoDecimal(res.data.total_eSport));
                        $agGameNumber.text(keepTwoDecimal(res.data.user_win.ag_dianzi));
                        $mgGameNumber.text(keepTwoDecimal(res.data.user_win.mg));
                        $cqGameNumber.text(keepTwoDecimal(res.data.user_win.cq));
                        $mwGameNumber.text(keepTwoDecimal(res.data.user_win.mw));
                        $fgGameNumber.text(keepTwoDecimal(res.data.user_win.fg));
                        $agByNumber.text(keepTwoDecimal(res.data.user_win.ag_dayu));
                        $gameTotal.text(keepTwoDecimal(res.data.total_games));
                        $kyChessNumber.text(keepTwoDecimal(res.data.user_win.ky));
                        $lyChessNumber.text(keepTwoDecimal(res.data.user_win.lyqp));
                        $vgChessNumber.text(keepTwoDecimal(res.data.user_win.vgqp));
                        $hgChessNumber.text(keepTwoDecimal(res.data.user_win.hgqp));
                        $klChessNumber.text(keepTwoDecimal(res.data.user_win.klqp));
                        $chessTotal.text(keepTwoDecimal(res.data.total_chess));
                        $khyjNumber.text(keepTwoDecimal(res.data.commission_agent));
                        $cksxfNumber.text(keepTwoDecimal(res.data.total_deposit_fee));
                        $qksxfNumber.text(keepTwoDecimal(res.data.total_withdraw_fee));
                        $xzfyNumber.text(keepTwoDecimal(res.data.company_agent));
                        $allTotalNumber.text(keepTwoDecimal(res.data.total_win));
                        $fszlNumber.text(keepTwoDecimal(res.data.mem_rebate.total));
                        $rkzlNumber.text(keepTwoDecimal(res.data.total_deposit));
                        $ckzlNumber.text(keepTwoDecimal(res.data.total_withdraw));
                        $yhzlNumber.text(keepTwoDecimal(res.data.total_extra));
                        $cjTotalNumber.text(keepTwoDecimal(res.data.total_gift));
                    }

                },
                error:function(){
                    layer.msg('网络错误，请稍后重试',{time:alertComTime,shade: [0.2, '#000']});
                }
            });
        }



    })

</script>
</body>
</html>
