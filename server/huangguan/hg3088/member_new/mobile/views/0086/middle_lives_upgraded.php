<?php
session_start();

include_once('../../include/config.inc.php');
$uid=$_SESSION["Oid"]?$_SESSION['Oid']:$_REQUEST['Oid'];
// $user_id = $_SESSION['userid']?$_SESSION['userid']:$_REQUEST['user_id'];
$username = $_SESSION['UserName']?$_SESSION['UserName']:$_REQUEST['username'] ;
$tip = isset($_REQUEST['tip'])?$_REQUEST['tip']:'' ; // 用于app 跳转到这个页面
$game_Type = isset($_REQUEST['game_Type'])?$_REQUEST['game_Type']:'' ;
if(!$game_Type){ // 默认真人
    $game_Type = 'live';
}

?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="HandheldFriendly" content="true"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon"/>
    <link href="../../style/font-awesome.min.css" rel="stylesheet" type="text/css" media="screen"/>
    <link href="style/iphone.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css" media="screen"/>

    <title class="web-title"></title>
    <style>
        select{color: #fff;height: 2rem;border-radius: 5px;}
        select option{background: #2c3232;}
        .pagination {padding: 8px 0;position: absolute;width: 100%;bottom: 0;}
        .df_1{color: #d09300;}
        .df_2{color: #ff0000;}

        .banner_top{width: 100%;height: 10rem;background: url(/<?php echo TPL_NAME;?>images/upgraded/banner_<?php echo $game_Type;?>.jpg) top center no-repeat;position: relative;background-size: cover;}
        .newSection {position: absolute;width: 100%;height: 2rem;bottom: 0;background: rgba(16, 12, 12, 0.82);}
        .newSection dl{margin: 0;}
        .newSection dt{padding-left: 2rem;color: #f0cf66;line-height: 2rem;font-size: 1rem;font-weight: bold;float: left;background: url(/<?php echo TPL_NAME;?>images/upgraded/icon_gg.png) center left no-repeat;}
        .newSection dd{height: 100%;padding: 0 2%;}
        .newSection dd a:hover{text-decoration: underline;}
        .newSection dd li{height: 2rem;line-height: 2rem; width:auto!important; font-size: 12px;color: #cfcbcb; }
        .newSection dd li:hover{ cursor:pointer; }
        .newSection marquee{color: #fff;line-height: 2rem;width: 86%;}
        .up_seachBox{width: 14rem;max-width: 300px;height: 3rem;position: absolute;background: url(/<?php echo TPL_NAME;?>images/upgraded/search_bg.png) center no-repeat;bottom: 0;right: 3%;background-size: contain;}
        .up_seach_btn{transition:.3s;display:inline-block;width:4rem;height:3rem;background: url(/<?php echo TPL_NAME;?>images/upgraded/btn_check.png) center no-repeat;float: right;margin: 0 2px 0 0;background-size: 100%;}
        .up_seach_btn:hover{transform: scale(1.05);}
        .up_seachBox input {padding: 0 .6rem;width: 9rem;height: 2rem;line-height: 2rem;border: none;background-color: transparent;color: #EFDC60;font-size: 1rem;margin: .5rem 0 0 0;}
        .up_content{color:#fff;background:#0f0f0f;border-top:1px solid #d09300;border-bottom:1px solid #d09300;padding:1rem 2% 5rem}
        .up_content table td{color:#fff;font-size: .9rem;}
        .up_content .icon{width:90%;height:3rem;margin:0 auto}
        .up_content .top-icon{background:url(/<?php echo TPL_NAME;?>images/upgraded/title_top.png) center no-repeat;background-size: 100%;}
        .up_content .bottom-icon{background:url(/<?php echo TPL_NAME;?>images/upgraded/title_bottom.png) center no-repeat;background-size: 100%;}
        .table_bg{width:100%;margin:1rem auto;background:#1b1b1b;}
        .up_content p{line-height: 1.5rem;padding: 0 2%;text-align: left;font-size: .9rem;}
        .up_table{width: 100%;line-height: 2.5rem;text-align: center;}
        .up_table .tr_bg{background: url(/<?php echo TPL_NAME;?>images/upgraded/table_bg.png) center no-repeat;background-size: cover;}
        .up_table tr th{color: #b00101;line-height: normal;}
        .up_table tr th,.up_table tr td{border: 1px solid #fff;}
        .up_footer{background: #070707;}
        .up_footer .footer_icon{width: 100%;height: 200px;background: url(/<?php echo TPL_NAME;?>images/upgraded/up_icon.png) center no-repeat;}
        .copyright {line-height: 60px;color: #4c4c4c;text-align: center;border-top: 1px solid #4c4c4c;}

        /*消息框*/
        .dialog {position:fixed;_position:absolute;top:7%;left:50%;margin-left:-49%;padding:0;border-radius:2px;animation-fill-mode:both;animation-duration:.3s;z-index:99999;background:none;display:none;}
        .dialog-container {width:98%;height:100%;display:block;position:relative;background:#2c3232;border:3px solid #f0a844;border-radius:8px;overflow:hidden;}
        .dialog .closebtn {opacity:1;position:absolute;background:url(/<?php echo TPL_NAME;?>images/upgraded/closebtn.png) no-repeat;width:64px;height:64px;right:-4px;top:-4px;display:block;cursor:pointer;}
        .dialog .dialog-container > .title {margin:1.5rem 0;text-align:center;line-height:2rem;}
        .dialog .dialog-container > .title p{color:#fff;font-size:1.5rem;}
        .dialog .content {position:relative;height:31rem;min-height:450px;width:98%;border-top:1px solid #333;margin:0 auto;}
        .dialog .content .warp {padding:10px;}
        .animate-enter .bounceInDown {-webkit-animation-name:bounceInDown;animation-name:bounceInDown;-webkit-animation-duration: 1s;animation-duration: 1s;z-index: 999;}
        .animate-enter {display:block;}

        .dialog-container > .title{ color: #ffcf0d; font-weight:normal; }
        .dialog-container > .title span{ color:#fff; font-weight:bold;}
        .result table{width: 100%; margin: 0 auto;}
        .result table tr td,.result table tr th{  height: 3rem; /*line-height: 3rem; */color: #f2f2f2; font-size: 1rem; padding-left:0px;border: 1px solid #f0a844; text-align:center; }
        .result table tr td{font-size: .9rem;}
        .result table tr th{border: 1px solid #f0a844; color: #f0a844;}
        .dianjing-container table tr td{position: relative;}
        .mask-bg {background-color: #000;opacity: 0.6;filter: alpha(opacity=60);top: 0;left: 0;width: 100%;height: 100%;z-index: 1;position: fixed;}

        @-webkit-keyframes bounceInDown {
            0% {
                opacity: 0;
                -webkit-transform: translateY(-2000px);
                transform: translateY(-2000px);
            }
            60% {
                opacity: 1;
                -webkit-transform: translateY(30px);
                transform: translateY(30px);
            }
            80% {
                -webkit-transform: translateY(-10px);
                transform: translateY(-10px);
            }
            100% {
                -webkit-transform: translateY(0);
                transform: translateY(0);
            }
        }
        @keyframes bounceInDown {
            0% {
                opacity: 0;
                -webkit-transform: translateY(-2000px);
                -ms-transform: translateY(-2000px);
                transform: translateY(-2000px);
            }
            60% {
                opacity: 1;
                -webkit-transform: translateY(30px);
                -ms-transform: translateY(30px);
                transform: translateY(30px);
            }
            80% {
                -webkit-transform: translateY(-10px);
                -ms-transform: translateY(-10px);
                transform: translateY(-10px);
            }
            100% {
                -webkit-transform: translateY(0);
                -ms-transform: translateY(0);
                transform: translateY(0);
            }
        }

    </style>
</head>
<body >

    <!-- 头部 -->
    <div class="header <?php if($tip){echo 'hide-cont';}?>">

    </div>

    <div class="container" >
        <div class="banner_top">
            <!-- 搜索框 -->
            <div class="w_1200">
                <div class="up_seachBox">
                    <input type="text" class="up_seach_input" placeholder="请输入会员账号" value="<?php echo $username?>" minlength="4" maxlength="20" >
                    <a href="javascript:;" class="up_seach_btn"></a>
                </div>
            </div>
            <!-- 公告 -->
         <!--   <div class="newSection">
                <div class="newsBox w_1200">
                    <dl>
                        <dt>最新公告</dt>
                        <dd class="bd">
                            <marquee onmouseout="this.start();" onmouseover="this.stop();" direction="left" scrolldelay="150" scrollamount="5">
                                <?php /*echo getScrollMsg(); */?>
                            </marquee>
                        </dd>
                    </dl>
                </div>
            </div>-->
        </div>

        <!-- 表格内容 -->
        <div class="w_1200 up_content" >
            <div class="top-icon icon"> </div>
            <!-- 顶部文字 -->
            <p class="up_tip" style="text-indent: 2rem;">

            </p>
            <div class="table_bg">
                <table class="up_table up_level_table" cellpadding="0" cellspacing="0">
                    <thead>
                    <tr class="tr_bg">
                        <th width="20%" class="back-yell">晋升标准等级</th>
                        <th width="20%" class="back-yell">有效投注</th>
                        <th width="20%" class="back-yell">晋级礼金</th>
                        <th width="20%" class="back-yell">月俸禄</th>
                        <!--<th width="20%" class="back-yell">时时返水</th>-->
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>

            <p class="center_tip">

            </p>
            <div class="bottom-icon icon"> </div>
            <p class="bottom_tip">

            </p>
        </div>


    </div>

    <!-- 底部footer -->
    <div id="footer" class="<?php if($tip){echo 'hide-cont';}?>">

</div>
    <!-- 查询数据 -->
    <div class="dialog animated bounce result">
        <div class="dialog-container animated bounceInDown" >
            <span class="closebtn" onclick="$('.dialog').removeClass('animate-enter');"></span>
            <h2 class="title">
                <p>会员账号：<span class="seach_name"> </span></p>
            </h2>
            <div class="content">
                <table cellpadding="0" cellspacing="0">
                    <tbody>
                    <tr>
                        <th width="10%" class="back-yell">当前等级</th>
                        <th width="20%" class="back-yell">累计有效投注</th>
                        <th width="20%" class="back-yell">累计赠送码量</th>
                        <th width="15%" class="back-yell">累计晋级礼金</th>
                        <th width="10%" class="back-yell">月俸禄</th>
                        <th width="20%" class="back-yell">距离下一等级<br>所需有效投注</th>
                    </tr>
                    <tr>
                        <td><span class="level"></span>级</td>
                        <td class="user_total_bet"></td>
                        <td class="free_total_bet"></td>
                        <td class="total_jinji_salary"></td>
                        <td class="total_month_salary"></td>
                        <td class="next_level_need_valid_money"></td>
                    </tr>
                    </tbody>
                </table>
                <table cellpadding="0" cellspacing="0" class="level_each" style=" margin-top:1.5rem;">
                    <thead>
                        <tr>
                            <th width="10%" class="back-yell">当周等级</th>
                            <th width="25%" class="back-yell">当周投注</th>
                            <th width="15%" class="back-yell">晋级彩金</th>
                            <th width="25%" class="back-yell">投注周期</th>
                            <th width="10%" class="back-yell">状态</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                <!-- 页码 -->
                <div class="page_upload pagination" >

                </div>

            </div>

        </div>
        <div class="mask-bg"></div>
    </div>


<script type="text/javascript" src="../../js/zepto.min.js"></script>
<script type="text/javascript" src="../../js/main.js?v=<?php echo AUTOVER; ?>"></script>

    <script type="text/javascript">
        $(function () {
            var tipStrData ;
            var liveData = {
                'title_1':'即日起，在<span class="df_1">HG0086</span>，投注真人视讯，每一笔有效投注将永久累计，只要达到一定的晋级标准，即可领取晋级礼金、月俸禄。达到一定等级后，即使您当月没有投注，也能躺着月月领俸禄，高达80,000元。等级30级，累计晋级礼金高达<span class="df_1">748,300</span>元，月俸禄高达<span class="df_1">80,000</span>元, 会员账号享有至高无上的价值体验，终身有效！ <span class="df_2">【派送时间通知】</span>晋级礼金每周一20:00更新数据后统一派送；月俸禄以最高等级对应的金额派送，每月<span class="df_1">10号00:00</span>系统准时自动派送！玩游戏，还能获得高收益，聪明的您还在等什么呢？抓紧时间注册吧！',
                'title_2':'<span class="df_1"> 注：</span>每周一20:00更新数据后，即可查看晋级礼金。若跨越多个等级，则晋级礼金进行累计派送，月俸禄以最高等级进行派送。<br> <span class="df_1">例：</span>会员累计真人视讯有效投注20万，【等级1，晋级礼金30元，9元周俸禄】；当会员累计投注达到200万，等级4，跨越了3个等级，晋级礼金为： 70+100+200=370元，月俸禄70元。',
                'title_3':'1.活动所得奖金，无需打码，即可提款；<br>2.每周一20:00更新，届时可查看【最新等级，晋级礼金，月俸禄】；<br> 3.晋级礼金每周一累积派送；月俸禄以最高等级对应的金额派送，每月<span class="df_1">10号00:00</span>系统自动派送!<br> 4.<span class="df_1">HG0086</span>保留对活动的最终解释权,以及在无通知的情况下修改、终止活动的权利。'
            };
            var sportData = {
                'title_1':'即日起，在<span class="df_1">HG0086</span>，投注<span class="df_2">体育竞技（包含电竞）</span>，每一笔有效投注将永久累计，只要达到一定的晋级标准，即可领取晋级礼金、月俸禄。达到一定等级后，即使您当月没有投注，也能躺着月月领俸禄，高达358888元。等级30级，累计晋级礼金高达6998886元，月俸禄高达358,888元, 会员账号享有至高无上的价值体验，终身有效！ <br> <span class="df_2">【派送时间通知】</span>晋级礼金每周一20:00更新数据后统一派送；月俸禄以最高  等级对应的金额派送，每月10号00:00系统准时自动派送！ <br>玩游戏，还能获得高收益，聪明的您还在等什么呢？抓紧时间注册吧！',
                'title_2':'<span class="df_1">注：</span>每周一20:00更新数据后，即可查看晋级礼金。若跨越多个等级，则晋级礼金进行累计派送，月俸禄以最高等级进行派送。<br> <span class="df_1">例：</span>会员累计体育竞技有效投注20万，【等级1，晋级礼金188元，18元周俸禄】；当会员累计投注达到200万，等级4，跨越了3个等级，晋级礼金为： 388+888+1888=3164元，月俸禄252元。',
                'title_3':'1.活动所得奖金，无需打码，即可提款；<br>2.每周一20:00更新，届时可查看【最新等级，晋级礼金，月俸禄】；<br>3.晋级礼金每周一累积派送；月俸禄以最高等级对应的金额派送，<span class="df_1">每月10号00:00</span>系统自动派送!<br>4.<span class="df_1">HG0086</span>保留对活动的最终解释权,以及在无通知的情况下修改、终止活动的权利。'
            };
            var imgUrl = '/<?php echo TPL_NAME;?>images/upgraded/';
            var game_Type = '<?php echo $game_Type;?>'; // 默认真人
            var uid = '<?php echo $uid?>' ;
            var usermon = getCookieAction('member_money') ; // 获取信息cookie
            setLoginHeaderAction('账号升级','','',usermon,uid) ;
            setFooterAction(uid) ; // 在 addServerUrl 前调用


            checkUpgraded();
            checkMemberLevel();
            upLevelChangePage();
            changeUpGradedType();
            tipTitleStr(game_Type);

            /*
            * 获取等级数据，查询等级
            *  type :
            *  getZhenrenLevelSalaryInfo 查询等级数据
            *  getSalaryRecords
            *
            * */
            function checkUpgraded(type,curPage) {
                var ajaxurl = '' ; // 真人
                switch(game_Type){
                    case 'live':
                        ajaxurl = '/api/zhenren_salary.php' ; // 真人
                        break;
                    case 'sport':
                        ajaxurl = '/api/sport_dj_salary.php';
                        break;
                }
                var username = $('.up_seach_input').val();
                if(!type){
                    type = 'getZhenrenLevelSalaryInfo';
                }
                if(!curPage){curPage=0}
                var pars ={
                    action:type,
                    username:username,
                    page:curPage
                };
                $.ajax({
                    type: 'POST',
                    url: ajaxurl,
                    data: pars,
                    dataType: 'json',
                    success: function (res) {
                        if(res.status ==200){
                            switch (type){
                                case 'getZhenrenLevelSalaryInfo':
                                    returnLevelStr(res.data);
                                    break;
                                case 'getSalaryRecords':
                                    showLevelData(res.data,curPage);
                                    break;
                            }

                        }else{
                            setPublicPop(res.describe);
                        }
                    },
                    error: function () {
                        setPublicPop('网络错误，请稍后重试!');
                    }
                });
            }

            // 文字数据处理
            function tipTitleStr(gameType) {
                var $up_tip = $('.up_tip');
                var $center_tip = $('.center_tip');
                var $bottom_tip = $('.bottom_tip');
                switch (gameType){
                    case 'live':
                        tipStrData = liveData;
                        break;
                    case 'sport':
                        tipStrData = sportData;
                        break;
                }
                $up_tip.html(tipStrData.title_1);
                $center_tip.html(tipStrData.title_2);
                $bottom_tip.html(tipStrData.title_3);
            }

            /*
            * 等级节点处理
            * */
            function returnLevelStr(resData) {
                var str ='';
                for(var i=0;i<resData.length;i++){
                    str += '  <tr>'+
                                '<td>'+resData[i].level+'</td>'+
                                '<td>'+resData[i].valid_bet+'</td>'+
                                '<td>'+resData[i].jinji_salary+'元</td>'+
                                '<td> '+resData[i].month_salary+'元</td>'+
                            '</tr>';
                }
                $('.up_level_table tbody').html(str);
            }

            /*
            *  查询会员等级
            * */
            function checkMemberLevel() {
                $('.up_seach_btn').off().on('click',function () {
                    var username = $('.up_seach_input').val();
                    if(!username){
                        setPublicPop('请输入账号');
                        return false;
                    }
                    checkUpgraded('getSalaryRecords');
                })
            }

            // 显示查询数据
            function showLevelData(resData,curPage) {
                var $dialog = $('.dialog');
                var $level_each = $('.level_each tbody');
                var $page_upload = $('.page_upload');
                if(curPage==0){ // 首页
                    $dialog.addClass('animate-enter');
                    $('.seach_name').html(resData.current_level.username); // 用户名
                    $('.level').html(resData.current_level.level); // 等级
                    $('.user_total_bet').html(resData.current_level.user_total_bet); // 累计有效投注
                    $('.free_total_bet').html(resData.current_level.free_total_bet); // 累计赠送投注
                    $('.total_jinji_salary').html(resData.current_level.total_jinji_salary); // 累计彩金
                    $('.total_month_salary').html(resData.current_level.total_month_salary);
                    $('.next_level_need_valid_money').html(resData.current_level.next_level_need_valid_money);
                }

                var l_str = '';
                if(resData.each_salary.length>0){
                    for(var i=0;i<resData.each_salary.length;i++){
                        l_str +=' <tr class="rank-tr" >'+
                            '<td>'+resData.each_salary[i].level+'级</td>'+
                            '<td>'+resData.each_salary[i].total+'</td>'+
                            '<td>'+resData.each_salary[i].gift_gold+'</td>'+
                            '<td>'+resData.each_salary[i].count_date_start+'--'+resData.each_salary[i].count_date_end+'</td>'+
                            '<td>'+returnUpStaus(resData.each_salary[i].status)+'</td>'+
                            '</tr>';
                    }
                }else{ // 没有数据
                    l_str = '<tr class="rank-tr" ><td colspan="6">暂无数据</td></tr>';
                }


                $level_each.html(l_str);

                // 分页
                $page_upload.html(returnPage(curPage,resData.page_count)) ;

            }

            function returnPage(page,pagecount) { // 返回页码
                var pagestr = '' ;
                if(pagecount>0){
                   pagestr +='<span>共 '+pagecount+' 页</span> <select class="select_page">';
                }
                for(var j=0;j<pagecount;j++){ // 分页
                    pagestr += ' <option class="swShowPage" topage="'+ (j+1) +'" '+(page==j?'selected':'')+'> 第'+ (j+1) +'页 </option>';
                }
                if(pagecount>0){
                    pagestr +='</select>';
                }
                return pagestr ;
            }
            // 页码切换
            function upLevelChangePage() {
                $('.page_upload').on('change','.select_page',function () {
                    var thispage = Number($(this).find('option').not(function(){ return !this.selected }).attr('topage'))-1 ;
                    checkUpgraded('getSalaryRecords',thispage);

                })
            }

             /*
             * 状态（1：已派发；2：未审核；3：不符合；4：已拒绝）
             **/
            function returnUpStaus(type) {
                var str;
                switch (type){
                    case '1':
                        str = '已派发';
                        break;
                    case '2':
                        str = '未审核';
                        break;
                    case '3':
                        str = '不符合';
                        break;
                    case '4':
                        str = '已拒绝';
                        break;
                }
                return str;
            }

            /*
            * 切换升级活动
            * */
            function changeUpGradedType() {
                $('.chooseType').on('click','a',function () {
                    var type = $(this).attr('data-type');
                    if(type =='live' || type=='sport'){
                        $(this).addClass('cur-a').siblings().removeClass('cur-a');
                        game_Type = type;
                        $('.banner_top').css({'background-image':'url('+imgUrl+'banner_'+type+'.jpg)'});
                        checkUpgraded(); // 重新获取等级数据
                        tipTitleStr(game_Type);
                    }else{
                        if(!type){
                            return;
                        }
                        setPublicPop('活动筹备中');
                    }

                })
            }


        })
    </script>
</body>
</html>