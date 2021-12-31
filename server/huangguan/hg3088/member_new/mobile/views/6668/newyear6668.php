<?php
include_once('../../include/config.inc.php');
$uid = $_SESSION['Oid']?$_SESSION['Oid']:(isset($_REQUEST['Oid'])?$_REQUEST['Oid']:'') ;
$userid = $_SESSION['userid']?$_SESSION['userid']:(isset($_REQUEST['userid'])?$_REQUEST['userid']:'') ;
$username=$_SESSION['UserName'];
$Alias= $_SESSION['Alias'];

$newYearBeginTime=  mktime(0,0,0,2,4, date('Y'));    //2月4日 北京时间2月4号 12:00
//$newYearBeginTime=  mktime(0,0,0,1,23, date('Y'));    //测试 1月23日 北京时间1月23号 12:00
$newYearEndTime = mktime(23,59,59,2,6, date('Y'));  //2月6日 北京时间2月7号 12:00
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
    <!--<link href="../../style/font-awesome.min.css" rel="stylesheet" type="text/css" media="screen"/>-->
    <link href="style/iphone.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css" media="screen"/>
    <title><?php echo COMPANY_NAME;?>新春活动</title>
    <style>
        body{margin:0;padding:0}
        .all_content{width:100%;height:100%;background:url(/images/newyear/6668/bg.jpg) center no-repeat;background-size:100% 100%;padding-bottom:9rem}
        img{width:100%;display:block}
        .table-bordered tr td{border:1px solid #ef9005}
        .table-bordered{border-collapse:collapse;text-align:center;width:100%}
        .table-bordered tr{background:transparent !important;color:#fff;font-size:16px;line-height:32px}
        .main{width:90%;margin:0 auto;margin-top:25px}
        .hg_header{position:relative}
        .bt_one{position:absolute;width:10%;bottom:-20px;left:10px}
        #receivered_6668{width:30%;margin:auto;margin-top:5px}
        .gz{color:#fff;font-size:14px}
        p{margin:5px 0}
    </style>
</head>
<body>
<div class="all_content">
    <!-- 头部 -->
    <div class="header ">

    </div>

    <div class="hg_header">
        <img src="images/newyear/6668/01.png" alt="" style="width: 90%">
        <img src="images/newyear/6668/1.png" alt="" class="bt_one">
    </div>
    <div class="main">
        <table class="table table-bordered">
            <tbody>
            <tr><td style="color: #f9df54;font-weight: 700;">单次存款</td><td style="color: #f9df54;font-weight: 700;">抢红包次数</td><td style="color: #f9df54;font-weight: 700;">提款要求</td></tr>
            <tr>
                <td>≥1000</td>
                <td>1</td>
                <td rowspan="8">一倍流水</td>
            </tr>
            <tr>
                <td>≥5000</td>
                <td>3</td>
            </tr>

            <tr>
                <td>≥10000</td>
                <td>5</td>
            </tr>
            <tr>
                <td>≥50000</td>
                <td>8</td>
            </tr>

            <tr>
                <td>≥100000</td>
                <td>12</td>
            </tr>
            <tr>
                <td>≥500000</td>
                <td>18</td>
            </tr>

            <tr>
                <td>≥1000000</td>
                <td>28</td>
            </tr>
            <tr>
                <td>≥5000000</td>
                <td>58</td>
            </tr>
            </tbody>
        </table>
        <div id="receivered_6668">
            <img src="images/newyear/6668/btn.png"  alt="">
        </div>
        <div class="gz">
            <img src="images/newyear/6668/2.png" alt="" style="width: 40%;">
            <p>⒈玩家在2019年2月4日至2019年2月6日，存款金额＞1000即可获得红包</p>
            <p>⒉本次红包金额将随机赠送，符合要求的玩家只需点击【自动领取】即可获取红包。</p>
            <p>⒊本活动所获得彩金完成一倍流水要求即可提款。</p>
        </div>
        <div class="gz">
            <img src="images/newyear/6668/3.png" alt="" style="width: 40%;">
            <p>⒈本公司的所有优惠特为玩家而设，如果发现任何团体或个人，以不诚实方式套取红利或任何威胁，滥用公司等优惠等行为，公司保留冻结，取消该团体或个人账户结余盈利。</p>
            <p>⒉若会员对活动有争议时，为了确保双方权益，杜绝身份盗用行为，本公司有权利要求会员向我们提供充足的文件，用以确认是否享有该优惠资质。</p>
            <p>⒊本公司保留对活动最终解释权；以及在无通知的情况下修改，终止活动的权利（使用于所有优惠）。</p>
        </div>
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
<script type="text/javascript" src="../../js/src/hb.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript">
    var uid = '<?php echo $uid?>' ;
    var usermon = getCookieAction('member_money') ; // 获取信息cookie
    var userid = '<?php echo $userid;?>' ;
    var username = '<?php echo $username;?>' ;
    var newYearBeginTime=  '<?php echo $newYearBeginTime;?>' ;
    var newYearEndTime = '<?php echo $newYearEndTime;?>';
    var nowtime = '<?php echo $nowtime;?>' ;

    var postData = {
        uid: uid ,
        user_id: userid ,
        username: username ,
    }

    setLoginHeaderAction('新春活动','','',usermon,uid) ;

    // 签到
    $("#receivered_6668").click(function() {
        var $alert_bg = $('.alert_bg') ;
        var $hongbao_animation = $('.hongbao_animation') ;
        if( nowtime < newYearBeginTime || nowtime > newYearEndTime ){
            alert('请于美东时间2月4号-6号期间签到领取红包哦!') ;
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
        postData.action =  "receive_red_envelope";
        loadnewyear(postData);
    });


    // 默认新春活动6668接口请求
    function loadnewyear(postData){
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
            $.post('/newyear_6668_api.php', postData, function(res) {
                if (res.data[0]) {
                    last_times = res.data[0].last_times;
                }
                //console.log(res);
                if (res.status = 200) {
                    $('.hb_text').text(res.describe) ;
                    if(res.data[0]){ // 金额更新
                        var totalMoney = Number($hg_money.text())+Number(res.data[0].data_gold) ;
                        $('.header-right').addClass('shake') ;
                        setCookieAction('member_money',totalMoney,1) ; // 用户金额，cookie 有效期 1天
                        $hg_money.html(totalMoney) ;
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