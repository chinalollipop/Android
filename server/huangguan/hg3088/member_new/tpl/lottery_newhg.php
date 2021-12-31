<?php

ini_set('display_errors','OFF');
include "../app/member/include/config.inc.php";
include "../app/member/include/address.mem.php";

// 判断今日赛事是否维护-单页面维护功能
checkMaintain('lottery');

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
    exit;
}
// 判断会员状态是否启用，否则退出
if ($_SESSION['Status'] != 0){
    echo "<script>alert('非常抱歉，您的账号已冻结或已停用，请您联系客服！')</script>";
    exit;
}
$langx=$_SESSION['langx'];
$uid=$_SESSION['Oid'];
require ("../app/member/include/traditional.$langx.inc.php");


$test_flag = $_SESSION['test_flag']; // 0 正式帐号，1 测试账号

?>
<html xmlns="http://www.w3.org/1999/xhtml"><head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width">
    <title>彩票游戏 </title>
    <link type="text/css" rel="stylesheet" href="../style/member/game_page_common.css?v=<?php echo AUTOVER; ?>">
    <link type="text/css" rel="stylesheet" href="../style/member/jbox_skin2.css?v=<?php echo AUTOVER; ?>">
    <style type="text/css">
        .click_on {-webkit-transition: .1s;transition: .1s;cursor: pointer;}
        .lottery_wrapper{background:url(/images/member/2018/lottery/newhg/lottery_bg.jpg) top center no-repeat;height: 76%;min-height: 500px;background-size: cover;position:relative}
        .lottery_wrapper .left_women{float:left}
        .lottery_wrapper .left_women .zijia_women{height:100%}
        .lottery_wrapper .left_women img{width:44%;position:absolute;bottom:0}
        .lottery_wrapper .platform_box{width:100%}

        .lottery_wrapper .platform_box .lottery_platform li{display:inline-block;width:193px;height:86px;border-radius:15px;border:1px solid #666768;text-align:center;margin-right:57px}
        .lottery_wrapper .platform_box .lottery_platform li:hover .platform_logo{background-position-y:-53px}
        .lottery_wrapper .platform_box .lottery_platform li[data-v-5b24bf1f]:last-child{margin-right:0}
        .lottery_wrapper .platform_box .lottery_platform li .platform_name{font-size:25px;line-height:23px;color:#9d9d9d}
        .lottery_wrapper .platform_box .lottery_platform li .menhuan{line-height:40px;font-size:19px;color:#9d9d9d;}
        .lottery_wrapper .platform_box .lottery_platform li .platform_logo{height:40px;text-align:center;margin-bottom:5px;margin-top:6px;background-repeat:no-repeat;background-position-x:center}
        .lottery_wrapper .platform_box .lottery_platform li .platform_logo img{height:100%}
        .lottery_wrapper .right_content{position: relative;float:right;margin-right:100px;text-align:center}
        .lottery_wrapper .right_content .my_lottery_wrapper{width:56%;margin-top:80px;position:relative;z-index:1;float:right}
        .lottery_wrapper .right_content .my_lottery_wrapper .my_lottery_box{width:100%}
        .lottery_wrapper .right_content .my_lottery_wrapper .my_lottery_box li{color:#333;text-align:center;width:14%;float:left;margin-right:3%;margin-bottom:36px;min-height:140px}
        .lottery_wrapper .right_content .my_lottery_wrapper .my_lottery_box li img{width:100%;margin-bottom:13px;border-radius:50%}
        .lottery_wrapper .right_content .my_lottery_wrapper .my_lottery_box li img:hover{animation:abc-data-v-5b24bf1f 1s linear infinite;-moz-animation:abc-data-v-5b24bf1f 1s infinite linear;-webkit-animation:abc-data-v-5b24bf1f 1s linear infinite;-o-animation:abc-data-v-5b24bf1f 1s infinite linear}
        .lottery_wrapper .right_content .my_lottery_wrapper .my_lottery_box li:nth-child(6n){margin-right:0}
        .tran_btn{display:inline-block;margin-left:25px;padding:0 5px;width:120px;height:30px;line-height:30px;border-radius:50px;background:#d1601a;box-shadow:1px 2px 1px rgba(0,0,0,.3);color:#fff;font-size:16px;cursor:pointer;transition:background .3s ease;position:absolute;bottom:-20px;left:455px}
        .tran_btn i{float:left;display:inline-block;margin:5px 5px 0 0;width:21px;height:21px;background-size:contain}
        .tran_btn .tran_logo{background-image:url(../../images/chess/tran_logo.png)}
        /* jquerybox */
        .game td, .more td {color: #6e5842;}
        div.jbox .jbox-button{background: url(/images/jquerybox/qp_btn.png) no-repeat;background-size: 100%;}

        @-webkit-keyframes abc-data-v-5b24bf1f{
            0%{-webkit-box-shadow:-1px 2px 13px 3px #2652ba;box-shadow:-1px 2px 13px 3px #2652ba}
            50%{-webkit-box-shadow:0 0 0 0 #2652ba;box-shadow:0 0 0 0 #2652ba}
            to{-webkit-box-shadow:-1px 2px 13px 3px #2652ba;box-shadow:-1px 2px 13px 3px #2652ba}
        }
        @keyframes abc-data-v-5b24bf1f {
            0% {
                -webkit-box-shadow: -1px 2px 13px 3px #2652ba;
                box-shadow: -1px 2px 13px 3px #2652ba
            }
            50% {
                -webkit-box-shadow: 0 0 0 0 #2652ba;
                box-shadow: 0 0 0 0 #2652ba
            }
            to {
                -webkit-box-shadow: -1px 2px 13px 3px #2652ba;
                box-shadow: -1px 2px 13px 3px #2652ba
            }
        }
    </style>


</head>
<body>

<div class="lottery_wrapper cl router_view_mian" >
    <div class="left_women">
        <img src="/images/member/2018/lottery/newhg/lottery_rw.png"
             alt="" class="zijia_women">
        <div style="display: none;">
        </div>
    </div>
    <div class="platform_box cl"></div>
    <div class="right_content">
        <div>
            <div class="my_lottery_wrapper">
                <ul class="my_lottery_box cl">

                    <li class="to_lotterys_third click_on" data-to="1" data-gametype="7">
                        <div>
                            <img src="/images/member/2018/lottery/newhg/cqssc.png" alt="">
                        </div>
                        <p>
                            重庆时时彩
                        </p>
                    </li>

                    <li class="to_lotterys_third click_on" data-to="1" data-gametype="76">
                        <div>
                            <img src="/images/member/2018/lottery/newhg/bjpk10.png" alt="">
                        </div>
                        <p>
                            北京赛车
                        </p>
                    </li>
                    <li class="to_lotterys_third click_on" data-to="1" data-gametype="70">
                        <div>
                            <img src="/images/member/2018/lottery/newhg/xglhc.png" alt="">
                        </div>
                        <p>
                            六合彩
                        </p>
                    </li>
                    <li class="to_lotterys_third click_on" data-to="1" data-gametype="51">
                        <div>
                            <img src="/images/member/2018/lottery/newhg/jspk10.png" alt="">
                        </div>
                        <p>
                            极速赛车
                        </p>
                    </li>

                    <li class="to_lotterys_third click_on" data-to="1" data-gametype="72">
                        <div>
                            <img src="/images/member/2018/lottery/newhg/wflhc.png" alt="">
                        </div>
                        <p>
                            五分六合彩
                        </p>
                    </li>
                    <li class="to_lotterys_third click_on" data-to="1" data-gametype="2">
                        <div>
                            <img src="/images/member/2018/lottery/newhg/ffc.png" alt="">
                        </div>
                        <p>
                            分分彩
                        </p>
                    </li>
                    <li class="to_lotterys_third click_on" data-to="1" data-gametype="76">
                        <div>
                            <img src="/images/member/2018/lottery/newhg/wfc.png" alt="">
                        </div>
                        <p>
                            五分彩
                        </p>
                    </li>
                    <li class="to_lotterys_third click_on" data-to="1" data-gametype="61">
                        <div>
                            <img src="/images/member/2018/lottery/newhg/cqklsf.png" alt="">
                        </div>
                        <p>
                            重庆快乐十分
                        </p>
                    </li>

                </ul>
            </div>

            <!-- 额度转换 -->
            <a class="money-change tran_btn" href="javascript:;" onclick="Cptransaction()">
                <i class="tran_logo"></i>
                额度转换
            </a>


        </div>
    </div>

</div>

<!-- 三方彩票登录 -->
<iframe name="third_cp_login_url" id="third_cp_login_url" scrolling="NO" noresize src="" style="display: none;" ></iframe>
<!-- 正式登陆POST ，测试登陆GET-->
<!-- name="toXinyong" 是否跳转到信用盘：是就传 1，要不就空 -->
<form id="index_third_form"  target="third_cp_login_url" method="<?php echo $test_flag==1?'get':'post'; ?>">
    <input type="hidden" name="params" class="cp_params" />
    <input type="hidden" name="thirdLotteryId" class="cp_thirdlotteryid" />
    <input type="hidden" name="toXinyong" />

</form>


<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/layer/layer.js"></script>
<script type="text/javascript" src="/js/jbox/jquery.jBox-2.3.min.js"></script>
<script type="text/javascript" src="/js/jbox/jquery.jBox-zh-CN.js"></script>
<script type="text/javascript" src="/js/common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript">
    var alertTime = 2000 ; // 弹窗提示时间
    var uid = '<?php echo $uid;?>';
    var third_cp_url_num = localStorage.getItem('third_cp_url_num');
    if(uid && (!third_cp_url_num || third_cp_url_num ==1 )){ // 三方彩票
        getThirdLotteryAction('login');
    }
    goToThirdLottery();

    // 额度转换
    function Cptransaction(){
        $.jBox('get:/app/member/gmcp/exchange.php?uid='+uid, {
            title: "彩票额度转换",
            buttons: { '关闭': true }
        });
    }
    /*
*  获取彩票登录链接
*  type : login 登录
*
* */
    function getThirdLotteryAction(type){
        var url = '/app/member/api/thirdLotteryApi.php';
        $.ajax({
            type: 'POST',
            url: url,
            data: {actype:type},
            dataType: 'json',
            success: function (res) {
                if(res){
                    if(res.status =='200'){
                        switch (type){
                            case 'login': // 登录
                                $('#index_third_form').attr({'action':res.data.third_cpUrl});
                                $('.cp_params').val(res.data.params);
                                $('.cp_thirdlotteryid').val(res.data.thirdLotteryId);
                                localStorage.setItem('third_cp_url_num',2) ; // 记录彩票登录次数,1 为初始化
                                document.getElementById('index_third_form').submit(); // 打开彩票链接
                                break;
                        }

                    }else{ // 异常
                        layer.msg(res.describe,{time:alertTime});
                    }

                }
            },
            error: function (res) {
                layer.msg('数据获取失败，请稍后再试!',{time:alertTime});

            }
        });
    }

    // 彩票跳转
    function goToThirdLottery() {
        $('.to_lotterys_third').on('click',function () {
            var type = $(this).attr('data-to')?$(this).attr('data-to'):'';
            var gametype = $(this).attr('data-gametype')?$(this).attr('data-gametype'):'';
            window.open('middle_lottery_third.php?type='+type+'&gametype='+gametype);
        })
    }
</script>



</body>
</html>
