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
$username=$_SESSION['UserName'];

$navtype = isset($_REQUEST['type'])?$_REQUEST['type']:'';
$navtitle = isset($_REQUEST['navtitle'])?$_REQUEST['navtitle']:'';

$reportType = isset($_REQUEST['reportType'])?$_REQUEST['reportType']:''; // 报表类型
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
        .nav_top{height:3rem;line-height:3rem;background:#f1f6fb;color:#4e525e}
        .nav_top span,.list span{width:22%}
        .nav_top span:nth-child(2), .list span:nth-child(2) {width: 12%;}
        .reportAllCurrent{color: #4e525e;}
        .reportAllCurrent .list {
            height: 3rem;
            justify-content: center;
            align-items: center;
            border-bottom: 1px solid #f1f1f1;
        }
        .list span{font-size:.9rem}
    </style>
</head>
<body>
<div class="contentAll flex">
    <?php include 'middle_header.php'; ?>
    <div class="nav_top flex">
        <span> 用户名 </span>
        <span> 笔数 </span>
        <span> 下注金额 </span>
        <span> 实际投注 </span>
        <span> 盈利 </span>
    </div>
    <div class="contentCenterAll ">
        <!-- 当前游戏总计 -->
        <div class="reportAllCurrent">
            <div class="reportList">
                <!--<div class="list flex"><span> hy_001 </span><span> 11 </span><span> 110.2 </span><span> 100 </span><span> 100 </span></div>-->
            </div>
            <div class="reportListTotal"> </div> <!-- 底部小计 -->
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
        getReportDetail();

        // 获取会员信息
        var submitflag = false ; // 防止重复提交
        function getReportDetail() {
            if(submitflag){
                return false ;
            }
            var username = '<?php echo $_SESSION['UserName'];?>';
            var $reportList = $('.reportList');
            var $reportListTotal = $('.reportListTotal');
            var reportType= '<?php echo $reportType;?>'; // 类型
            var date_start= '<?php echo $date_start;?>';
            var date_end= '<?php echo $date_end;?>';

            submitflag = true ;
            var url = '/app/agents/report_new/report_top_ag_mem.php';

            var dataParams = {
                action:'api',
                agent:username,
                date_start:date_start,
                date_end:date_end
            };
            switch (reportType){
                case 'aglive': // AG 真人
                    dataParams.type = 'BR';
                break;
                case 'aggame': // AG 电子
                    dataParams.type = 'SLOT';
                    break;
                case 'agby': // AG 捕鱼
                    url = '/app/agents/report_new/report_top_ag_buyu_mem.php';
                    break;
                case 'sport': // 体育
                    url = '/app/agents/report_new/report_top_hg_mem.php';
                    break;
                case 'lottery': // 体育彩票
                    url = '/app/agents/report_new/report_top_cp_mem.php';
                    break;
                case 'kychess': // 开元棋牌
                    url = '/app/agents/report_new/report_top_ky_mem.php';
                    break;
                case 'lychess': // 乐游棋牌
                    url = '/app/agents/report_new/report_top_lyqp_mem.php';
                    break;
                case 'vgchess': // VG棋牌
                    url = '/app/agents/report_new/report_top_vgqp_mem.php';
                    break;
                case 'hgchess': // 皇冠棋牌
                    url = '/app/agents/report_new/report_top_hgqp_mem.php';
                    break;
                case 'klchess': // 快乐棋牌
                    url = '/app/agents/report_new/report_top_klqp_mem.php';
                    break;
                case 'fydj': // 泛亚电竞
                    url = '/app/agents/report_new/report_top_avia_mem.php';
                    break;
                case 'firedj': // 雷火电竞
                    url = '/app/agents/report_new/report_top_fire_mem.php';
                    break;
                case 'oglive': // OG视讯
                    url = '/app/agents/report_new/report_top_og_mem.php';
                    break;
                case 'bbinlive': // BBIN视讯
                    url = '/app/agents/report_new/report_top_bbin_mem.php';
                    break;
                case 'mggame': // MG电子
                    url = '/app/agents/report_new/report_top_mg_mem.php';
                    break;
                case 'mwgame': // MW电子
                    url = '/app/agents/report_new/report_top_mw_mem.php';
                    break;
                case 'cqgame': // CQ9电子
                    url = '/app/agents/report_new/report_top_cq_mem.php';
                    break;
                case 'fggame': // FG电子
                    url = '/app/agents/report_new/report_top_fg_mem.php';
                    break;
                case 'xyp': // 信用盘
                    url = '/app/agents/report_new/report_top_ssc_mem.php';
                    break;
                case 'gfp': // 官方盘
                    url = '/app/agents/report_new/report_top_project_mem.php';
                    break;
                case 'gfzh': // 官方盘追号
                    url = '/app/agents/report_new/report_top_trace_mem.php';
                    break;


            }
            $.ajax({
                type: 'POST',
                url:url,
                data:dataParams,
                dataType:'json',
                success:function(res){
                    var str = '';
                    var xjstr = ''; // 小计
                    if(res){ // 有结果返回
                        if(res.data.rows){ // 有数据
                            for(var i=0;i<res.data.rows.length;i++){
                                str += '<div class="list flex">' +
                                            '<span> '+ res.data.rows[i].username +' </span>' +
                                            '<span> '+ res.data.rows[i].count_pay +' </span>' +
                                            '<span> '+ keepTwoDecimal(res.data.rows[i].total) +' </span>' +
                                            '<span> '+ keepTwoDecimal(res.data.rows[i].valid_money) +' </span>' +
                                            '<span> '+ keepTwoDecimal(res.data.rows[i].user_win) +' </span>' +
                                        '</div>';
                            }
                            xjstr += '<div class="list flex"><span class="red">小计：</span><span>'+ res.data.count_pay +'</span><span>'+ keepTwoDecimal(res.data.total) +'</span><span>'+ keepTwoDecimal(res.data.valid_money) +'</span><span>'+ keepTwoDecimal(res.data.user_win) +'</span></div>';
                        }else{ // 没有数据
                            str += '<div class="list flex">暂无数据</div>';
                        }
                        $reportList.html(str);
                        $reportListTotal.html(xjstr);

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
