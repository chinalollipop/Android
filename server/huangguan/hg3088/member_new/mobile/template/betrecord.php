<?php
	include_once('../include/config.inc.php');

	$username = $_SESSION['UserName'];
	$userid = $_SESSION['userid'];
	$uid = $_SESSION['Oid'];

    if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
        echo "<script>alert('请重新登录!');window.location.href='/".TPL_NAME."login.php';</script>";
        exit;
    }

$todaydate=date('Y-m-d'); // 当天日期
$yesdate=date('Y-m-d',time()-1*24*60*60) ;// 昨天
$weekdate=date('Y-m-d',time()-7*24*60*60) ;// 一周前
$firstDate=date('Y-m-01', strtotime(date("Y-m-d"))); // 当月第一天
$disClass = (TPL_FILE_NAME=='8msport'?'textbox':'');

?>

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="HandheldFriendly" content="true"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
        <link rel="shortcut icon" href="/<?php echo TPL_NAME;?>images/favicon.ico" type="image/x-icon"/>
        <meta name="apple-mobile-web-app-capable" content="yes"/>
        <meta name="apple-mobile-web-app-status-bar-style" content="black"/><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <?php
        if(TPL_NAME=='views/0086/'){
            echo '<link href="/style/font-awesome.min.css" rel="stylesheet" type="text/css" media="screen"/>';
        }
        ?>
        <link rel="stylesheet" href="../../style/icalendar.css?v=<?php echo AUTOVER; ?>">
        <link href="/<?php echo TPL_NAME;?>style/iphone.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css" media="screen"/>

        <title class="web-title"></title>

        <style>
            .content-center{margin-bottom: 0;}
		</style>
    </head>
    <body>
        <div id="container">
            <!-- 头部 -->
            <div class="header ">

            </div>

            <!-- 中间部分 -->
            <div class="content-center deposit">

                <div class="deposit-two" >
                    <div class="form">
                        <div class="form-item">
                              <span class="label">
                                    <span class="text">赛事类型</span>
                                    <span class="line"></span>
                              </span>

                            <span class="<?php echo $disClass;?> dropdown">
                                   <select id="select_type"  >
                                        <option value="FT">足球</option>
                                        <option value="BK">篮球</option>
                                        <option value="FS">冠军</option>
                                       <?php
                                       if(TPL_FILE_NAME=='0086' || TPL_FILE_NAME=='6668' || TPL_FILE_NAME=='0086dj'){
                                            echo ' <option value="lottery"> 彩票 </option>';
                                       }
                                       ?>

                                        <option value="aglive"> AG视讯 </option>
                                        <option value="aggame"> AG电子 </option>
                                        <option value="agby"> AG捕鱼 </option>
                                        <option value="kyqp"> 开元棋牌 </option>
                                        <option value="lyqp"> 乐游棋牌 </option>
                                        <option value="vgqp"> VG棋牌 </option>
                                        <option value="klqp"> 快乐棋牌 </option>
                                       <!-- <option value="hgqp"> 皇冠棋牌 </option>-->
                                        <option value="mgdz"> MG电子 </option>
                                        <option value="avia"> 泛亚电竞 </option>
                                        <option value="oglive"> OG视讯 </option>
                                        <option value="bbinlive"> BBIN视讯 </option>
                                        <option value="cq9dz"> CQ9电子 </option>
                                        <option value="mwdz"> MW电子 </option>
                                        <option value="fgdz"> FG电子 </option>

                                  </select>
                              </span>
                        </div>
                        <div class="form-item">
                              <span class="label">
                                    <span class="text">是否结算</span>
                                    <span class="line"></span>
                              </span>

                            <span class="<?php echo $disClass;?> dropdown">
                                   <select id="select_tip"  >
                                         <option value="">全部</option> <!-- 全部 -->
                                         <option value="N">未结注单</option> <!-- 未结注单 -->
                                         <option value="Y">已结注单</option> <!-- 已结注单 -->

                                  </select>
                              </span>
                        </div>
                        <div class="form-item">
                              <span class="label">
                                    <span class="text">是否取消</span>
                                    <span class="line"></span>
                              </span>

                            <span class="<?php echo $disClass;?> dropdown">
                                   <select id="select_status"  >
                                         <option value="N">有效注单</option> <!-- 有效注单 -->
                                         <option value="Y">无效注单</option> <!-- 无效注单 -->

                                  </select>
                              </span>
                        </div>
                        <div class="form-item">
                            <span class="label">
                                <span class="text">开始时间</span>
                                <span class="line"></span>
                            </span>
                            <span class="textbox">
                                <input class="deposit-input"  placeholder="选择日期" type="text" id="begin_time" readonly />
                            </span>
                        </div>
                        <div class="form-item">
                            <span class="label">
                                <span class="text">结束时间</span>
                                <span class="line"></span>
                            </span>
                            <span class="textbox">
                                <input class="deposit-input"  placeholder="选择日期" type="text" id="end_time" readonly />
                            </span>
                        </div>
                        <table class="money moneychoose" >
                            <tbody>
                            <tr>
                                <td class="active"><span onclick="chooseDateAction(this,'<?php echo $todaydate?>','<?php echo $todaydate?>')">今日</span></td>
                                <td><span onclick="chooseDateAction(this,'<?php echo $yesdate?>','<?php echo $yesdate?>')">昨日</span></td>
                                <td><span onclick="chooseDateAction(this,'<?php echo $weekdate?>','<?php echo $todaydate?>')">近一周</span></td>
                                <td><span onclick="chooseDateAction(this,'<?php echo $firstDate?>','<?php echo $todaydate?>')">本月</span></td>
                            </tr>
                            </tbody>
                        </table>

                        <div class="btn-wrap">
                            <a href="javascript:;" class="zx_submit" onclick="seachDataActtion(this)">查询</a>
                        </div>

                    </div>

                </div>

                <table width="100%" border="0" id="table_record" class="table_record" >
                    <thead>
                    <tr>
                        <th style="width: 22%">日期</th>
                        <th style="width: 50%">赛事</th>
                        <th style="width: 14%">金额</th>
                        <th style="width: 14%">输/赢</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>

                <div class="get_more_data" style="display: none;"><a class="get_more_action" data-page="1">加载更多数据</a></div>


	       		<div class="clear"></div>

            </div>


        </div>
        <script type="text/javascript" src="../../js/zepto.min.js"></script>
        <script type="text/javascript" src="../../js/animate.js"></script>
        <script type="text/javascript" src="../../js/zepto.animate.alias.js"></script>
        <script type="text/javascript" src="../../js/icalendar.min.js"></script>
         <script type="text/javascript" src="../../js/main.js?v=<?php echo AUTOVER; ?>"></script>
        <script type="text/javascript">
            var $get_more_action = $('.get_more_action') ;
            var uid = '<?php echo $uid?>' ;
            var usermon = getCookieAction('member_money') ; // 获取信息cookie
            setLoginHeaderAction('投注记录','','',usermon,uid) ;

            getMoreData() ;

            // 时间初始化
            var begincalendar = new lCalendar();   // 时间插件初始化 ，开始时间
            var endcalendar = new lCalendar();   // 时间插件初始化 ，结束时间
            begincalendar.init({
                'trigger': '#begin_time',
                'type': 'datetime',
               defaultValue:setAmerTime('#begin_time','daystart') ,
            });
            endcalendar.init({
                'trigger': '#end_time',
                'type': 'datetime',
                defaultValue:setAmerTime('#end_time','dayend') ,
            });

            // 日期快速选择
            function chooseDateAction(obj,datebegin,dateend) { //  2017/12/15 新加
                var begintime = datebegin+' 00:00' ;
                var endtime = dateend+' 23:59' ;
                $('#begin_time').val(begintime);
                $('#end_time').val(endtime);
                $(obj).parent('td').addClass('active').siblings('td').removeClass('active');
                $(obj).parents('tr').siblings('tr').find('td').removeClass('active');
                $(obj).addClass('active').siblings().removeClass('active');

            }

            // 加载更多数据
            function getMoreData() {
                $get_more_action.on('click',function () {
                    $get_more_action.html('加载中...') ;
                    var curpage = $(this).data('page') ;
                    var allcount = $(this).data('count') ; // 总页数
                    var type = $('#select_type').val() ;
                    var tip = $('#select_tip').val() ;
                    var status = $('#select_status').val() ;
                    var begintime = $('#begin_time').val() ;
                    var endtime = $('#end_time').val() ;

                    if(curpage<1 || curpage >= allcount){ // 没有数据
                        $get_more_action.html('没有更多数据了') ;
                        return false ;
                    }
                    getTodayWagers(curpage,type,tip,status,begintime,endtime,'more') ;
                }) ;

            }

            // 查询按钮
            var submitflag_1 = false ; // 防止重复提交
            function seachDataActtion(obj) {
                if(submitflag_1){
                    alertComing('1秒内请勿重复查询!') ;
                    return false ;
                }
                setTimeout(function () {
                    submitflag_1 = false ;
                },1000);
                submitflag_1 = true ;
                
                $get_more_action.attr('data-page',1) ;
                $('#table_record tbody').html('') ; // 每次查询需要清空

                var type = $('#select_type').val() ;
                var tip = $('#select_tip').val() ;
                var status = $('#select_status').val() ;
                var begintime = $('#begin_time').val() ;
                var endtime = $('#end_time').val() ;

                getTodayWagers(0,type,tip,status,begintime,endtime) ;
            }

            // 获取数据接口 tip: N 未结注单 Y 已结注单
            var submitflag = false ; // 防止重复提交
            function getTodayWagers(page,type,tip,status,begintime,endtime,more) {
                if(submitflag){
                    return false ;
                }
                if(more){ // 加载更多数据
                    var curpage = Number($get_more_action.data('page')) ; // 当前页面
                }
                var datapars ={
                    gtype :type , // 篮球 BK ，足球 FT
                    Checked :tip , // 是否结算 ，N 未结注单 Y 已结注单
                    Cancel :status , // 是否取消 , Y  无效注单 N 有效注单
                    date_start :begintime , // 是否取消 , Y  无效注单 N 有效注单
                    date_end :endtime , // 是否取消 , Y  无效注单 N 有效注单
                    page : page  // 页面
                } ;
                var ajaxurl = '/api/wagers_api.php' ;
                switch (type){
                    case 'lottery': // 彩票
                        ajaxurl = '/api/historyLotteryApi.php';
                        break;
                    case 'aglive': // AG 视讯
                    case 'aggame': // AG 电子
                    case 'agby': // AG 捕鱼
                        ajaxurl = '/api/historyAgGameApi.php';
                        break;
                    case 'kyqp': // 开元棋牌
                    case 'lyqp': // 乐游棋牌
                    case 'vgqp': // vg棋牌
                    case 'klqp': // 快乐棋牌
                    case 'hgqp': // hg棋牌
                    case 'mgdz': // mg电子
                    case 'avia': // 泛亚电竞
                    case 'oglive': // OG视讯
                    case 'bbinlive': // BBIN视讯
                    case 'cq9dz': // CQ9电子
                    case 'mwdz': // MW电子
                    case 'fgdz': // FG电子
                        if(tip==''){ // 默认
                            datapars.Checked = 'Y';
                        }
                        ajaxurl = '/api/betHistoryApi.php';
                        break;

                }
                var $recordslist = $('#table_record tbody') ;
                submitflag = true ;
                $.ajax({
                    url: ajaxurl ,
                    type: 'POST',
                    dataType: 'json',
                    data: datapars ,
                    success: function (res) {
                        if(res.status==200) { // 有数据返回
                            $get_more_action.attr('data-count', res.data.page_count); // 总页数

                            if(res.data.total==0){
                                submitflag = false ;
                                $('.get_more_data').hide() ;
                                var nodata = '<tr class="no-data"><td colspan="5">暂时没有注单记录</td></tr>' ;
                                $recordslist.html(nodata) ;
                            }else { // 有数据
                                var str = '' ;
                                submitflag = false ;
                                var text_color;
                                var text_tip;
                                var p3_result = '' ;
                                for(var i=0;i<res.data.rows.length;i++){

                                    if(Number(res.data.rows[i].M_Result) > 0){ // 赢
                                        text_tip = '赢' ;
                                        text_color = 'text-red' ;
                                    }else if(Number(res.data.rows[i].M_Result) < 0){ // 输
                                        text_tip = '输' ;
                                        text_color = 'text-lose' ;
                                    }else if(Number(res.data.rows[i].M_Result) == 0){ // 和局
                                        text_color = 'text-blue' ;
                                        if(type =='lottery'){  // 彩票 ( count : 0 未结算 1 已结算 ，cancel :0 未取消，1 已取消 )
                                            if(res.data.rows[i].cancel=='1'){
                                                text_tip = '已取消' ;
                                            }else if(res.data.rows[i].count=='0'){
                                                text_tip = '未结算' ;
                                            }else {
                                                text_tip = '和局' ;
                                            }

                                        }else{
                                            text_tip = '和局' ;
                                        }
                                    }
                                    var BetScore = parseFloat( res.data.rows[i].BetScore);
                                     BetScore = BetScore.toFixed(2);
                                    var M_Result = parseFloat( res.data.rows[i].M_Result);
                                    M_Result = M_Result.toFixed(2);
                                    str += '<tr class="wagers">' +
                                        '<td >' +
                                        '<p>'+res.data.rows[i].BetTime+'</p>' +
                                        '<p> '+res.data.rows[i].orderNo+'</p>' +
                                        '</td>' ;
                                    str += '<td class="bet_content">';
                                    if(type=='FT'||type=='BK'||type=='FS'){ // 体育

                                        if(res.data.rows[i].Middle.length > 0){ // 综合过关
                                            for(var j=0;j<res.data.rows[i].Middle.length;j++){
                                                str += '<div class="bet_content_detail"><p>'+res.data.rows[i].Middle[j].M_League+'</p>'+
                                                    '<p>'+ res.data.rows[i].Middle[j].vs_team_name1 +'&nbsp;&nbsp;<span class="text-blue">'+ res.data.rows[i].Middle[j].vs_or_let_ball_num +'</span>&nbsp;&nbsp;'+ res.data.rows[i].Middle[j].vs_team_name2 +'</p>'+
                                                    '<p><span class="text-blue">'+ res.data.rows[i].Middle[j].font_a +'</span>&nbsp;&nbsp;<span class="red_color">'+ res.data.rows[i].Middle[j].bet_content +'</span> @ <span class="red_color">'+ res.data.rows[i].Middle[j].bet_rate +'</span></p></div>';
                                                p3_result +='<p style="color:#ff0000"> '+ res.data.rows[i].Middle[j].font_a +'</p>' ;
                                            }
                                        }else{ // 非综合过关 ,res.data.rows[i].font_a 全场比分，res.data.rows[i].corner_num 投注时比分

                                            str += '<div class="bet_content_detail"><p>'+res.data.rows[i].M_League+'</p>'+
                                                '<p>'+ res.data.rows[i].Title +'</p>'+
                                                '<p>'+ res.data.rows[i].vs_team_name1 +'&nbsp;&nbsp;<span class="text-blue">'+ res.data.rows[i].vs_or_let_ball_num +'</span>&nbsp;&nbsp;'+ res.data.rows[i].vs_team_name2 +'&nbsp;&nbsp;<span class="red_color">'+res.data.rows[i].corner_num+'</span></p>'+
                                                '<p><span class="text-blue">'+ res.data.rows[i].font_a +'</span>&nbsp;&nbsp;<span class="red_color">'+ res.data.rows[i].bet_content +'</span> @ <span class="red_color">'+ res.data.rows[i].bet_rate +'</span></p>' +
                                                '<p><strong style="color:green">'+ res.data.rows[i].isDanger +'</strong></p>'
                                                '</div>';

                                        }

                                    }else{ // 其他
                                        str += '<p class="bet_title">'+res.data.rows[i].Title+'</p>';
                                    }

                                    str += '</td>';
                                    str +=  '<td class="bet_gold"><p>'+BetScore+'</p> </td>' ;
                                    if(M_Result && M_Result !='NaN'){ // 帐户历史
                                        if(res.data.rows[i].zt){ // 异常注单
                                            str += '<td class="'+text_color+'"><p><font color=#cc0000><b>'+ res.data.rows[i].zt +'</b></font></p> </td>' ;
                                        }else{
                                            str += '<td class="'+text_color+'">';
                                            if(res.data.rows[i].Middle.length > 0) { // 综合过关
                                                str += p3_result ;
                                            }else{ // 非综合过关
                                                str += '<p style="color:#ff0000">'+ res.data.rows[i].font_a  +'</p>' ;

                                            }
                                            str += '<p>'+ text_tip +'</p>'+ M_Result +'</td>' ;

                                        }

                                    }else{
                                        str += '<td ></td>';
                                    }
                                    str += '</tr>';
                                }

                                $recordslist.append(str) ;

                                if(res.data.page_count>1){
                                    $get_more_action.html('加载更多数据') ;
                                    $('.get_more_data').show() ;
                                }else{
                                    $('.get_more_data').hide() ;
                                }
                                if(more) { // 加载更多数据
                                    curpage++ ;
                                    $get_more_action.attr('data-page',curpage) ;
                                    if(curpage == res.data.page_count){
                                        $get_more_action.html('没有更多数据了') ;
                                    }
                                }

                            }

                        }



                    },
                    error: function (msg) {
                        submitflag = false ;
                        alertComing('请求数据失败，请重新加载');
                    }
                });
            }



        </script>
    </body>
</html>