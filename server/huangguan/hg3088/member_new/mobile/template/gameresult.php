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
        if(TPL_FILE_NAME=='0086' || TPL_FILE_NAME=='0086dj'){
            echo '<link href="/style/font-awesome.min.css" rel="stylesheet" type="text/css" media="screen"/>';
        }
        ?>
        <link rel="stylesheet" href="/style/icalendar.css?v=<?php echo AUTOVER; ?>">
        <link href="/<?php echo TPL_NAME;?>style/iphone.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css" media="screen"/>

        <title class="web-title"></title>

        <style>
            .moneychoose td { width: 25%;}
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

                                  </select>
                              </span>
                        </div>

                        <div class="form-item">
                            <span class="label">
                                <span class="text">日期</span>
                                <span class="line"></span>
                            </span>
                            <span class="textbox">
                                <input class="deposit-input"  placeholder="选择日期" type="text" id="begin_time" readonly />
                            </span>
                        </div>

                        <table class="money moneychoose" >
                            <tbody>
                            <tr>
                                <td class="active"><span onclick="chooseDateAction(this,'<?php echo $todaydate?>','<?php echo $todaydate?>')">今日</span></td>
                                <td><span onclick="chooseDateAction(this,'<?php echo $yesdate?>','<?php echo $yesdate?>')">昨日</span></td>
                                <td> </td>
                                <td> </td>
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
                        <th style="width: 14%">半场</th>
                        <th style="width: 14%">全场</th>
                    </tr>
                    </thead>
                    <tbody>

                        <!--<tr class="league_name" data-league="日本J3联赛"><td colspan="6" class="b_hline">日本J3联赛</td></tr>

                        <tr class="wagers">
                            <td> 10-13<br>00:01a </td>
                            <td class="bet_content"> 藤枝MYFC<br>大阪飞脚U23 </td>
                            <td > <b class="red_color"><span >2</span><br><span >1</span></b>  </td>
                            <td >  <b class="red_color"><span >3</span><br><span >1</span></b>  </td>
                        </tr>

                        <tr class="wagers">
                            <td> 10-13<br>00:01a </td>
                            <td class="bet_content"> 藤枝MYFC<br>大阪飞脚U23 </td>
                            <td > <b class="red_color"><span >2</span><br><span >1</span></b>  </td>
                            <td >  <b class="red_color"><span >2</span><br><span >2</span></b>  </td>
                        </tr>-->


                    </tbody>
                </table>


	       		<div class="clear"></div>

            </div>
            <!-- 底部footer -->
           <!-- <div id="footer">

            </div>-->

        </div>
        <script type="text/javascript" src="../../js/zepto.min.js"></script>
        <script type="text/javascript" src="../../js/animate.js"></script>
        <script type="text/javascript" src="../../js/zepto.animate.alias.js"></script>
        <script type="text/javascript" src="../../js/icalendar.min.js"></script>
         <script type="text/javascript" src="../../js/main.js?v=<?php echo AUTOVER; ?>"></script>
        <script type="text/javascript">

            var uid = '<?php echo $uid?>' ;
            var usermon = getCookieAction('member_money') ; // 获取信息cookie
            setLoginHeaderAction('赛果','','',usermon,uid) ;
            setFooterAction(uid);  // 在 addServerUrl 前调用

            // 时间初始化
            var begincalendar = new lCalendar();   // 时间插件初始化 ，开始时间

            begincalendar.init({
                'trigger': '#begin_time',
                'type': 'date',
               defaultValue:setAmerTime('#begin_time','dayoff') ,
            });



            // 日期快速选择
            function chooseDateAction(obj,datebegin,dateend) { //  2017/12/15 新加
                var begintime = datebegin ;

                $('#begin_time').val(begintime);
                $(obj).parent('td').addClass('active').siblings('td').removeClass('active');
                $(obj).parents('tr').siblings('tr').find('td').removeClass('active');
                $(obj).addClass('active').siblings().removeClass('active');

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

                var type = $('#select_type').val() ;
                var begintime = $('#begin_time').val() ;

                getGameResult(type,begintime) ;
            }

            // 获取数据接口 tip: N 未结注单 Y 已结注单
            var submitflag = false ; // 防止重复提交
            function getGameResult(type,begintime,more) {
                if(submitflag){
                    return false ;
                }

                var datapars ={
                    game_type :type , // 篮球 BK ，足球 FT
                    list_date :begintime
                } ;
                var ajaxurl = '/result.php' ;
                var $recordslist = $('#table_record tbody') ;
                submitflag = true ;
                $.ajax({
                    url: ajaxurl ,
                    type: 'POST',
                    dataType: 'json',
                    data: datapars ,
                    success: function (res) {
                        if(res.status==200) { // 有数据返回

                            if(res.data==''){
                                submitflag = false ;
                                var nodata = '<tr class="no-data"><td colspan="5">暂时没有赛果记录</td></tr>' ;
                                $recordslist.html(nodata) ;
                            }else { // 有数据
                                var str = '' ;
                                submitflag = false ;
                                $.each(res.data,function (n,v) {
                                    // console.log(n);
                                    // console.log(v);
                                    str +=' <tr class="league_name" data-league="'+ v.name +'"><td colspan="6" class="b_hline">'+ v.name +'</td></tr>';
                                    for(var i=0;i<v.result.length;i++){

                                           str += ' <tr class="wagers">' +
                                                ' <td> '+ v.result[i].M_Date +'<br> '+ v.result[i].M_Time +' </td>' +
                                                ' <td class="bet_content"> ' + v.result[i].MB_Team + '<br>'+ v.result[i].TG_Team +' </td>' +
                                                ' <td > <b class="red_color"><span >'+ v.result[i].MB_Inball_HR +'</span><br><span >'+ v.result[i].TG_Inball_HR +'</span></b>  </td>' +
                                                ' <td >  <b class="red_color"><span >'+ v.result[i].MB_Inball +'</span><br><span >'+ v.result[i].TG_Inball +'</span></b>  </td>' +
                                                ' </tr>' ;
                                    }
                                });

                                $recordslist.html(str) ;


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