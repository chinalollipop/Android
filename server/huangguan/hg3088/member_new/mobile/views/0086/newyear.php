<?php
include_once('../../include/config.inc.php');
$uid = $_SESSION['Oid']?$_SESSION['Oid']:(isset($_REQUEST['Oid'])?$_REQUEST['Oid']:'') ;
$userid = $_SESSION['userid']?$_SESSION['userid']:(isset($_REQUEST['userid'])?$_REQUEST['userid']:'') ;
$username=$_SESSION['UserName'];
$Alias= $_SESSION['Alias'];

 $newYearBeginTime=  mktime(0,0,0,2,4, date('Y'));    //2月4日 北京时间2月4号 12:00
//$newYearBeginTime=  mktime(0,0,0,1,23, date('Y'));    //测试 1月23日 北京时间1月23号 12:00
$newYearEndTime = mktime(23,59,59,2,10, date('Y'));  //2月10日 北京时间2月10号 12:00
$nowtime = time() ;

?>

<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="HandheldFriendly" content="true"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link href="../../style/font-awesome.min.css" rel="stylesheet" type="text/css" media="screen"/>
    <link href="style/iphone.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css" media="screen"/>
    <title>0086新春活动</title>
    <style>
        body{
            margin: 0;
            padding: 0;
        }
        img{
            border: 0;
            width: 100%;
            display: block;
        }
        .title{
            background: url(/images/newyear/0086/yearhb_0086_medium.jpg)no-repeat;
            padding: 0 0 0 5px;
            height: 7.45rem;
        }
        .lf_img{
            width: 23%;
            float: left;
        }
        .lr_inp{
            width: 75%;
            float: right;
            padding-top: 15px;
        }
        .qd_ipt input{
            border: 1px solid #f8e77d ;
            background: none;
            color: #fff;
            width: 35%;
            height: auto;
            padding: .2rem 0;
        }
        .qd_btn{
            background: url(/images/newyear/0086/qd.png) center no-repeat;
            padding: 14%;
            background-size: 100%;
        }
        .lq_btn{
            background: url(/images/newyear/0086/lq.png) no-repeat;
            padding: 15%;
            background-size: 100%;
            vertical-align:-32px;
            background-position: 3px 0;
        }
        .qd_ipt{
            margin-bottom: 10px;
        }
        @media (max-width: 320px){
            .qd_ipt input{
                width: 34% !important;
            }
            .qd_btn{
                padding: 12%;
            }
            .hb_img img{
                width: 1.6rem !important;
            }
        }
    </style>
</head>
<body>
<div class="all_content">
    <!-- 头部 -->
    <div class="header ">

    </div>
    <div>
        <img src="images/newyear/0086/yearhb_0086_top.jpg" alt="">
    </div>
    <div class="title">
        <div class="lf_img">
            <img src="images/newyear/0086/logo.png" alt="">
        </div>
        <div class="lr_inp">
            <div class="qd_ipt">
                <input id="mobile" name="mobile" value="" type="text" minlength="11" maxlength="11">
                <span style="color: #fff;font-size: .75rem"> 请输入手机号码</span>
                <span class="qd_btn" id="mobilesign"></span>
            </div>
            <div class="qd_ipt lq_ipt" style="margin-bottom: 0">
                <input id="cishu" name="cishu" value="" type="text" readonly="readonly"><span style="color: #fff;font-size: .75rem"> 剩余次数</span>
                <span class="hb_img"><img src="images/newyear/0086/hb.png" alt="" style="display:inline-block;width: 2rem;vertical-align: -9px;"></span>
                <span class="lq_btn" id="receivered"></span>
            </div>
        </div>
    </div>
    <div>
        <img src="images/newyear/0086/yearhb_0086_foot.jpg" alt="">
    </div>

    <!-- 红包效果 -->
    <div class="alert_bg"> </div>
    <div class="hongbao_animation shake">
        <div class="hb_text"> 领取红包 </div>
        <div class="hb_btn open_hb"> </div>
        <!-- <div class="hb_icon hb_icon_1"> </div>
         <div class="hb_icon hb_icon_2"> </div>
         <div class="hb_icon hb_icon_3"> </div>
         <div class="hb_icon hb_icon_4"> </div>
         <div class="hb_icon hb_icon_5"> </div>
         <div class="hb_gold"> </div>-->

    </div>

</div>
<script type="text/javascript" src="../../js/zepto.min.js"></script>
<script type="text/javascript" src="../../js/animate.js"></script>
<script type="text/javascript" src="../../js/zepto.animate.alias.js"></script>
 <script type="text/javascript" src="../../js/main.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../js/validate.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../js/src/hb.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript">

    var uid = '<?php echo $uid?>' ;
    var usermon = getCookieAction('member_money') ; // 获取信息cookie
    var userid = '<?php echo $userid;?>' ;
    var username = '<?php echo $username;?>' ;
    var newYearBeginTime=  '<?php echo $newYearBeginTime;?>' ;
    var newYearEndTime = '<?php echo $newYearEndTime;?>';
    var nowtime = '<?php echo $nowtime;?>' ;


    var last_times = ''; // 剩余次数
    var postData = {
        uid:uid ,
        user_id:userid ,
        username:username ,
    }

    postData.action =  "get_remain_num",
    loadnewyear_0086_num(postData); // 第一次进来请求，不弹框提示更新次数

    setLoginHeaderAction('新春活动','','',usermon,uid) ;
    // 签到
    $("#mobilesign").click(function() {
        var nummobile = $('#mobile').val() ;
        postData.mobile = nummobile ;
        postData.action =  "mobilesign" ;
        if(nummobile =='' || !isMobel(nummobile)){
            alertComing('请输入正确的手机号码') ;
            return false ;
        }
        loadnewyear_0086(postData);
    });

    // 领取红包
    $("#receivered").click(function() {

        var $alert_bg = $('.alert_bg') ;
        var $hongbao_animation = $('.hongbao_animation') ;
        if( nowtime < newYearBeginTime || nowtime > newYearEndTime ){
            alert('请于美东时间2月4号-10号期间签到领取红包哦!') ;
            return false ;
        }
        /* 红包雨 */
        var hgstr = '<div id="hongbao_animation"> </div>' ;
        $('.all_content').append(hgstr) ;
        hbInit();
        setTimeout(function () {
            $alert_bg.show();
            $hongbao_animation.removeClass('removehb').addClass('shake').show() ;
        },1000) ;


    });

    /* 红包信封 */
    $('.open_hb').click(function () {
        var open = $(this).hasClass('main_jb2') ;
        if(open){
            return false ;
        }
        $(this).addClass('main_jb2') ;

        /* 红包消失 */
        postData.action =  "receive_red_envelope",
            loadnewyear_0086(postData);

    });

    // 默认新春活动0086接口请求
    function loadnewyear_0086(postData){
        var userAgents='<?php echo $_SESSION['Agents'];?>';
        var $hg_money = $('.hg_money') ;

        if(userAgents=='demoguest'){
            alert("请注册真实用户！");
            $('.hb_text').text('请注册真实用户') ;
            /* 红包消失 */
            setTimeout(function () {
                hbRemove() ;
            },1500) ;

        }else{
            $.post('/newyearapi.php', postData, function(res) {
                if (res.data[0]) {
                    last_times = res.data[0].last_times;
                }
                $("#cishu").val(last_times);
                if (res.status = 200) {
                    if(postData.action == 'mobilesign'){ // 签到
                        alert(res.describe);
                    }else{
                        $('.hb_text').text(res.describe) ;
                        if(res.data[0]){ // 金额更新
                            var totalMoney = Number($hg_money.text())+Number(res.data[0].data_gold) ;
                            $('.header-right').addClass('shake') ;
                            setCookieAction('member_money',totalMoney,1) ; // 用户金额，cookie 有效期 1天
                            $hg_money.html(totalMoney) ;
                        }
                    }


                } else {
                    alert(res.describe);
                }
            }, 'json');
            /* 红包消失 */
            setTimeout(function () {
                hbRemove() ;
            },2000) ;

        }
    }


    function loadnewyear_0086_num(postData){
        var userAgents='<?php echo $_SESSION['Agents'];?>';
        if(userAgents=='demoguest'){
            alert("请注册真实用户！");
        }else{
            $.post('/newyearapi.php', postData, function(res) {
                if (res.data[0]) {
                    last_times = res.data[0].last_times;
                }

                $("#cishu").val(last_times);
                if (res.status == 200) {
                    //alertComing(res.describe) ;
                } else {
                    alertComing(res.describe) ;
                }
            }, 'json')

        }
    }

    /* 红包消失 */
    function hbRemove() {
        $('.header-right').removeClass('shake') ;
        $('.open_hb').removeClass('main_jb2') ;
        $('.hongbao_animation ').removeClass('shake').addClass('removehb') ;
        $('.alert_bg').hide() ;
    }
</script>
</body>
</html>