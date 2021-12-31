<?php
session_start();
include_once('../../../include/config.inc.php');

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>alert('请重新登录!');window.location.href='../login.php';</script>";
    exit;
}

$uid=$_SESSION['Oid'];
$username = $_SESSION['UserName'];
$todaydate=date('Y-m-d'); // 当天日期
$yesdate=date('Y-m-d',time()-1*24*60*60) ;// 昨天
$weekdate=date('Y-m-d',time()-7*24*60*60) ;// 一周前
$firstDate=date('Y-m-01', strtotime(date("Y-m-d"))); // 当月第一天

?>

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="HandheldFriendly" content="true"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
        <meta name="apple-mobile-web-app-capable" content="yes"/>
        <meta name="apple-mobile-web-app-status-bar-style" content="black"/><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon"/>
        <!--<link href="../style/font-awesome.min.css" rel="stylesheet" type="text/css" media="screen"/>-->
        <link href="../style/iphone.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css" media="screen"/>
        <link rel="stylesheet" href="../../../style/icalendar.css?v=<?php echo AUTOVER; ?>">
        <title class="web-title"></title>
        <style type="text/css">

        </style>
    </head>
    <body>
    <div id="container">
            <!-- 头部 -->
            <div class="header ">

            </div>
        <!-- 中间主体部分 -->
            <div class="content-center">

                <div class="deposit-two" >
                    <div class="form">
                        <div class="form-item">
                              <span class="label">
                                    <span class="text">平台类型</span>
                                    <span class="line"></span>
                              </span>

                              <span class="dropdown textbox">
                                   <select id="select_type"  >
                                         <option value="ALL">全部</option>
                                         <option value="S">充值</option>
                                         <option value="T">提现</option>
                                         <option value="Q">额度转账</option>
                                         <option value="R">返水</option>
                                  </select>
                              </span>
                        </div>
                        <div class="form-item">
                              <span class="label">
                                    <span class="text">状态</span>
                                    <span class="line"></span>
                              </span>

                            <span class="dropdown textbox">
                                   <select id="select_status"  >
                                         <option value="ALL">全部</option>
                                         <option value="1">成功</option>
                                         <option value="-1">失败</option>
                                         <option value="0,2">处理中</option>
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
                <table border="0" id="table_record" class="table_record">
                    <thead>
                    <tr>
                        <th style="width: 25%">订单号</th>
                        <th style="width: 30%">日期/时间</th>
                        <th style="width: 15%">项目</th>
                        <th style="width: 15%">订单状态</th>
                        <th style="width: 15%">金额</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>

                <div class="get_more_data" style="display: none;"><a class="get_more_action" data-page="1">加载更多数据</a></div>

            </div>
        <!-- 底部 -->
        <div id="footer">

        </div>
    </div>
    <script type="text/javascript" src="../../../js/zepto.min.js"></script>
    <script type="text/javascript" src="../../../js/animate.js"></script>
    <script type="text/javascript" src="../../../js/zepto.animate.alias.js"></script>
    <script type="text/javascript" src="../../../js/icalendar.min.js"></script>
     <script type="text/javascript" src="../../../js/main.js?v=<?php echo AUTOVER; ?>"></script>
    <script type="text/javascript">
        var $get_more_action = $('.get_more_action') ;
        var uid = '<?php echo $uid?>' ;
        var usermon = getCookieAction('member_money') ; // 获取信息cookie
        var gamename = {
            'S':'存款记录',
            'T':'提款记录',
            'ALL':'存提记录',
            'Q':'额度转换记录',
            'R':'返水记录'
        };
        setLoginHeaderAction('','','',usermon,uid) ;
        setFooterAction(uid) ; // 在 addServerUrl 前调用
        addServerUrl() ;
        getMoreData();

        // 时间初始化
        var begincalendar = new lCalendar();   // 时间插件初始化 ，开始时间
        var endcalendar = new lCalendar();   // 时间插件初始化 ，结束时间
        begincalendar.init({
            'trigger': '#begin_time',
            'type': 'date',
            defaultValue:setAmerTime('#begin_time','dayoff'),
        });
        endcalendar.init({
            'trigger': '#end_time',
            'type': 'date',
            defaultValue:setAmerTime('#end_time','dayoff'),
        });
        // 存款记录数据获取
        var submitflag = false ; // 防止重复提交
        function getDepositRecord(page,type,status,begintime,endtime,more) {

            if(submitflag){
                return false ;
            }
            if(more){ // 加载更多数据
                var curpage = Number($get_more_action.data('page')) ; // 当前页面
            }

            var $recordslist = $('#table_record tbody') ;

            submitflag = true ;
            $.ajax({
                url: '/account/record_api.php' ,
                type: 'POST',
                dataType: 'json',
                data: {
                    thistype: type ,  // type S 存款，T 提款
                    uid : uid ,
                    page:page ,
                    type_status:status , // 审核状态: 0 首次提交订单 2 二次审核  1成功 -1失败
                    date_start:begintime , // 开始时间
                    date_end:endtime , // 结束时间
                    },
                success: function (res) {
                    if(res.status==200){ // 有数据返回
                        $get_more_action.attr('data-count',res.data.page_count) ; // 总页数

                        if(res.data.total==0){
                            submitflag = false ;
                            $('.get_more_data').hide() ;
                            var nodata = '<tr class="no-data"><td colspan="5">暂时没有'+gamename[type]+'</td></tr>' ;
                            $recordslist.html(nodata) ;
                        }else{ // 有数据
                            submitflag = false ;

                            var str = '' ;
                            for(var i=0;i<res.data.rows.length;i++){
                                str += '<tr>\n' +
                                    '<td>'+ res.data.rows[i].Order_code +'</td>\n' +
                                    ' <td>'+ res.data.rows[i].AuditDate +'</td>\n' ;
                                if(res.data.rows[i].Type=='T'){
                                    str += '<td>提款</td>\n' ;
                                }else if(res.data.rows[i].Type=='S'){
                                    if(res.data.rows[i].notes=='APP幸运红包活动'){
                                        str += '<td>红包</td>\n' ;
                                    }else if(res.data.rows[i].notes=='APP签到红包'){
                                        str += '<td>'+res.data.rows[i].notes+'</td>\n' ;
                                    }else{
                                        str += '<td>存款</td>\n' ;
                                    }
                                }else{ // 转账记录
                                    str += '<td>'+ returnChangeType(res.data.rows[i].From) +'</br><font color="red">转入</font></br>'+ returnChangeType(res.data.rows[i].To) +'</td>\n' ;
                                }

                                if(res.data.rows[i].Checked == 0 || res.data.rows[i].Checked == 2){ // 审核中 ,2 二次审核
                                    str += '<td class="font"><font color="green">审核中</font></td>\n';
                                }else if(res.data.rows[i].Checked == -1){ // 失败
                                    str += '<td class="font"><font color="#cd5c5c">失败</font></td>\n';
                                }else if(res.data.rows[i].Checked == 1){ // 成功
                                    str += '<td class="font"><font color="red">成功</font></td>\n';
                                }
                                str += '<td>'+ res.data.rows[i].Gold +'</td>\n' +
                                    '</tr>' ;

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
                    setPublicPop(config.errormsg);
                }
            });
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
            var status = $('#select_status').val() ;
            var begintime = $('#begin_time').val() ;
            var endtime = $('#end_time').val() ;

            getDepositRecord(0,type,status,begintime,endtime)
        }

        // 加载更多数据
        function getMoreData() {
            $get_more_action.on('click',function () {
                $get_more_action.html('加载中...') ;
                var curpage = $(this).data('page') ;
                var allcount = $(this).data('count') ; // 总页数
                var type = $('#select_type').val() ;
                var status = $('#select_status').val() ;
                var begintime = $('#begin_time').val() ;
                var endtime = $('#end_time').val() ;

                if(curpage<1 || curpage >= allcount){ // 没有数据
                    $get_more_action.html('没有更多数据了') ;
                    return false ;
                }
                getDepositRecord(curpage,type,status,begintime,endtime,'more')
            }) ;

        }

        // 日期快速选择
        function chooseDateAction(obj,datebegin,dateend) { //  2017/12/15 新加
                $('#begin_time').val(datebegin);
                $('#end_time').val(dateend);
                $(obj).parent('td').addClass('active').siblings('td').removeClass('active');
                $(obj).parents('tr').siblings('tr').find('td').removeClass('active');
                $(obj).addClass('active').siblings().removeClass('active');

        }

        // 额度转换类型处理 type
        function returnChangeType(type) {
            var str = '' ;
            switch (type){
                case 'hg':
                    str = '中心钱包';
                    break;
                case 'sc': // 皇冠体育
                    str ='皇冠体育' ;
                    break;
                case 'cp': // 彩票
                    str ='彩票平台' ;
                    break;
                case 'gmcp': // 三方彩票
                    str ='彩票平台' ;
                    break;
                case 'ag': // ag真人
                    str ='AG平台' ;
                    break;
                case 'bbin': // bbin真人
                    str ='BBIN视讯' ;
                    break;
                case 'ky': // 彩票
                    str ='开元棋牌' ;
                    break;
                case 'ly': // 乐游棋牌
                    str ='乐游棋牌' ;
                    break;
                case 'ff':
                    str ='皇冠棋牌' ;
                    break;
                case 'vg':
                    str ='VG棋牌' ;
                    break;
                case 'kl':
                    str ='快乐棋牌' ;
                    break;
                case 'cq': // CQ9真人
                    str ='CQ9平台' ;
                    break;
                case 'fg': // FG真人
                    str ='FG平台' ;
                    break;
                case 'fire': // 雷火电竞
                    str ='雷火电竞' ;
                    break;
            }
            return str ;
        }


    </script>

    </body>
</html>