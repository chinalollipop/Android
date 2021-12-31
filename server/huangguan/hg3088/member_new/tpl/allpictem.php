<?php
session_start();
include "../app/member/include/config.inc.php";

$uid= $_SESSION['Oid'] ;
$userid = $_SESSION['userid'] ;
$username = $_SESSION['UserName'] ;
$langx=$_SESSION['langx'] ;

$to = $_REQUEST['to'] ; // 活动类型
$title = $_REQUEST['title'] ; // 活动标题
$keys = $_REQUEST['keys']; // 优惠活动内容图片
$ajaxUrl = $_REQUEST['api']; // 自动领取接口地址
$flag = $_REQUEST['flag']; // 自动领取活动唯一标识
$showbg = $_REQUEST['showbg']; // 6668 新年活动独有

$tplfilename = $_SESSION['TPL_FILE_NAME_SESSION'];

$companyName = COMPANY_NAME;

// 新年活动是否开启
$sRedPocketset = $redisObj->getSimpleOne('red_pocket_open'); // 取redis 设置的值
$aRedPocketset = json_decode($sRedPocketset,true) ;

// 活动于北京时间2月10号中午12:00-次日11:59期间领取红包，活动时间持续24小时
$newYearBeginTime= $aRedPocketset['newYearBeginTime']?$aRedPocketset['newYearBeginTime']:'2021-02-11 00:00:00'; // 活动二开始时间
$newYearEndTime = $aRedPocketset['newYearEndTime']?$aRedPocketset['newYearEndTime']:'2021-02-12 23:59:59'; // 活动二结束时间

$curtime = date("Y-m-d H:i:s",time()+12*60*60); // 北京时间
if($tplfilename=='6668'){
    $newYearBeginTime= $aRedPocketset['newYearBeginTime']?$aRedPocketset['newYearBeginTime']:'2021-02-11 00:00:00'; // 活动二开始时间
    $newYearEndTime = $aRedPocketset['newYearEndTime']?$aRedPocketset['newYearEndTime']:'2021-02-13 23:59:59'; // 活动二结束时间
}

?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>皇冠体育</title>
    <style>
        html,body{overflow-y:auto;height:100%;padding-bottom:10px;margin:0}
        .kf_right{display: none;}
        div,p,dl,dd{margin: 0;padding: 0;}
        a,a:hover,a:visited{text-decoration: none;}
        img,.pro_content img{width:100%}
        .pro_content img{max-width: 980px;}
        .ios_content{background:#996701;padding:50px 10px;text-align: center;}
        .pro_content{position:relative}
        .pro_content .pro_btn,.content .ny_hb_btn{position:absolute;display:block;width:160px;height:60px;margin:655px 0 0 430px}
        .pro_content .btn_app, .pro_content .btn_hphct{display: none;}
        .pro_content .btn_attendance{ margin: 790px 0 0 410px;}
        .pro_content .btn_chess{ margin: 580px 0 0 730px;}
        .pro_content .btn_week{ margin: 770px 0 0 430px;}
        .pro_content .btn_king{ margin: 1100px 0 0 435px;}
        .pro_content .btn_dragon{ margin: 995px 0 0 435px;}
        .pro_content .btn_shuangdan {margin: 880px 0 0 481px;}
        .pro_content .btn_promotion {margin: 673px 0 0 412px;}
        .pro_content .btn_sport_dm{ margin: 725px 0 0 410px;}
        .pro_content .btn_sj_holiday{ margin: 1940px 0 0 715px;}
        .pro_content .btn_euro{ margin: 788px 0 0 522px;}
        .phoneNumber {position: absolute;margin: 606px 0 0 764px;width: 135px;border: 0;background: transparent;padding-left: 5px;}

        <?php
             if($tplfilename=='6668'){
                 echo '.pro_content .pro_btn{margin:715px 0 0 410px}
                 .pro_content .btn_attendance{margin: 815px 0 0 418px;}
                 .pro_content .btn_week {margin: 775px 0 0 442px;}
                 .pro_content .btn_king {margin: 1270px 0 0 405px;}
                 .pro_content .btn_chess {margin: 580px 0 0 738px;}
                 .pro_content .btn_dragon{ margin: 1460px 0 0 435px;}
                 .pro_content .btn_sj_holiday{ margin: 735px 0 0 390px;}
                 .pro_content .newyear_sec{ display:none;}';

             }else if($tplfilename=='newhg') {
                   echo '.pro_content .pro_btn{margin:427px 0 0 413px;}
                 .pro_content .btn_attendance{margin: 520px 0 0 407px;}
                 .pro_content .btn_week {margin: 417px 0 0 752px;}
                 .pro_content .btn_king {margin: 962px 0 0 410px;}
                 .pro_content .btn_chess {margin: 465px 0 0 790px;}';
             }
         ?>

        /* 2021 新年活动 */
        .content .new_y_top{ position: absolute;width: 502px;left: 50%;height: 140px;margin: 99px 0 0 -235px;}
        .content .new_y_top .top_timer {height:40px;line-height:40px;margin:32px 0 0 5px;font-size: 26px;color: #ba000b;}
        .content .new_y_top .top_timer span{margin:0 2px;background: #ba000b;color: #e8c3c3;border-radius: 15px;display: inline-block;width: 60px;text-align: center;}
        .content .new_y_top .new_y_bottom{font-size: 32px;color: #ba000b;height: 60px;line-height: 72px;text-align: center;}

        .content .newyear_2021 .newyear_hby_btn{/*display:none;*/cursor:pointer;width: 304px;height: 104px;background: url(/images/hongbao/hb_btn.png) center no-repeat;position:absolute;margin: 955px 0 0 390px;transform: scale(.7);transition: .3s;}
        .content .newyear_2021 .newyear_hby_btn:hover{transform: scale(.75);}
        .content .newyear_2021 .n_hb_num{position: absolute;margin: 1082px 0 0 300px;font-size: 30px;color: #fff;}


        /* 新年 活动一 中奖提示  开始 */
        .content .newyear_btn{ position: absolute;width: 100%;height: 282px;margin: 470px 0 0 0;}
        .content .btn_2020_288w{ width: 222px;height: 100%;background: url(/images/hongbao/hb_off.png) center no-repeat;margin: 0;left: 360px;}
        .content .btn_2020_288w.active{background: url(/images/hongbao/hb_on.png) center no-repeat;}
        .content .btn_2020_288w.promos_newyear_1 {left:30px;}
        .content .btn_2020_288w:last-child{left: 690px;}

        .hb_mask{display:none;position:fixed;left:0;top:0;z-index:10;width:100%;height:100%;background-color:rgba(0,0,0,0.85)}
        .hb_mask .blin{width:100%;max-width:747px;height:100%;max-height:752px;margin:100px auto 0;background-image:url("/images/hongbao/gold.png");-o-animation:circle 10s linear infinite;-ms-animation:circle 10s linear infinite;-moz-animation:circle 10s linear infinite;-webkit-animation:circle 10s linear infinite;animation:circle 10s linear infinite}
        .hb_mask .caidai{position:absolute;left:0;top:0;z-index:1;width:100%;height:100%;background-image:url("/images/hongbao/dianzhui.png");-o-transform:scale(1.2);-ms-transform:scale(1.2);-moz-transform:scale(1.2);-webkit-transform:scale(1.2);transform:scale(1.2)}
        .hb_mask .winning{position:absolute;left:50%;top:50%;z-index:1;width:198px;height:265px;margin:-10% -7%;-webkit-transform:scale(0.1);transform:scale(0.1)}
        .reback{-o-animation:reback .5s linear forwards;-ms-animation:reback .5s linear forwards;-moz-animation:reback .5s linear forwards;-webkit-animation:reback .5s linear forwards;animation:reback .5s linear forwards}

        /* .winning .red-head{position:relative;top:-0.33333333rem;width:100%;height:4.46666667rem;background-image:url("/images/hongbao/top.png")} */
        .winning .red-body{position:relative;z-index:2;width:100%;height:100%;background-image:url(/images/hongbao/hb_on.png);background-repeat:no-repeat}
        .hb_mount{color:#d6261e;font-size:48px !important;font-weight:bold;padding:38px 12px !important;width:84%;text-align:center}
        .promos_btn .hb_mount{display:none;padding:48px 12px !important}
        .hb_title{font-size:20px !important;text-align:center;color:#fcd639;margin-top:20px}
        .promos_btn .hb_title{display:none;margin-top:10px}
        .winning .pull{-o-animation:card .5s linear forwards;-webkit-animation:card .5s linear forwards;animation:card .5s linear forwards}
        .hb_close{cursor:pointer;opacity:1;position:absolute;right:14%;top:20%;z-index:10;width:64px;height:64px;background-image:url("/images/hongbao/close.png")}
        /* .winning .hb_card{position:absolute;left:50%;top:50%;z-index:1;margin-left:-3.2rem;margin-top:-1.06666667rem;width:80%;height:4.26666667rem;background-image:url("/images/hongbao/middle.png");-o-transition:top .5s;-ms-transition:top .5s;-moz-transition:top .5s;-webkit-transition:top .5s;transition:top .5s}
         .hb_card .win{display:block;margin:0.13333333rem auto;width:92%;height:3.86666667rem;background-image:url("/images/hongbao/prize2.png")}
         .winning .btn{position:absolute;left:50%;bottom:10%;z-index:2;width:4.85333333rem;height:0.94666667rem;margin-left:-2.42666667rem;background-image:url("/images/hongbao/button.png");-o-animation:shake .5s 2 linear alternate;-ms-animation:shake .5s 2 linear alternate;-moz-animation:shake .5s 2 linear alternate;-webkit-animation:shake .5s 2 linear alternate;animation:shake .5s 2 linear alternate}
         */

        @keyframes reback {
            100% {
                -o-transform: scale(1);
                -ms-transform: scale(1);
                -moz-transform: scale(1);
                -webkit-transform: scale(1);
                transform: scale(1);
            }
        }
        @keyframes circle {
            0% {
                -o-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -o-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }
        @keyframes card {
            100% {
                margin-top: -3.2rem;
            }
        }
        @keyframes shake {
            50% {
                -o-transform: rotate(-5deg);
                -ms-transform: rotate(-5deg);
                -moz-transform: rotate(-5deg);
                -webkit-transform: rotate(-5deg);
                transform: rotate(-5deg);
            }
            100% {
                -o-transform: rotate(5deg);
                -ms-transform: rotate(5deg);
                -moz-transform: rotate(5deg);
                -webkit-transform: rotate(5deg);
                transform: rotate(5deg);
            }
        }

        @keyframes fadein {
            100% {
                opacity: 1;
                -o-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }
        /* 新年 活动一 中奖提示  结束 */

        /* 新年 活动二 */
        .machine{width:100%;height:756px;position:absolute;top:50%;left:50%;-webkit-transform:translate(-50%,-67%);transform:translate(-50%,-67%)}
        .rotate_box{width:60%;margin:0 auto;padding-top:12.3%}
        .rotate_box dd{position: relative;width:24.8%;height:250px;margin-right:9%;float:left;background:url(/images/hongbao/prize.png);background-size:cover}
        .rotate_box dd:last-child{margin-right:0}
        .rotate_btn_css,.zw_hh{width:92px;height:129px;overflow:hidden;position:absolute;right:17px;bottom:529px;transform-origin:center bottom}
        .zw_hh{z-index: 1}
        .poiner{width:.88rem;position:absolute;right:.4rem;top:-1.2rem}
        .btn_box{width:316px;height:84px;position:absolute;left:50%;margin-left:-158px;bottom:30px}
        .content .btn_box a{transition:.3s;width:100%;height:100%;margin:0;background:url(/images/hongbao/yj_btn.png) center no-repeat}
        .content .btn_box a:hover{background:url(/images/hongbao/yj_btn_hover.png) center no-repeat;}
        .newyear_num_st{position:absolute;width:112px;bottom:118px;color:#f6bd2f;font-size:90px;font-weight:bold;text-align:center;right:297px}
        .machine .receiveAfter{z-index:2;display:none;transform: scale(0.1);position:absolute;width: 323px;height: 462px;background: url(/images/hongbao/hb1_on.png) center no-repeat;top:20px;left: 285px;color: #d6261e;font-size: 24px;}
        .machine .receiveAfter .tip{text-align: center;padding-top: 88px;}
        .machine .receiveAfter .tip .hb_mount{padding: 0 !important;}
        .machine .new_zfy{width:138px;position:absolute;font-size:20px;color:#fdc731;left:200px;top:190px;text-align:center;z-index:1}
        .machine .new_zfy.new_zfy_1{left: 400px;}
        /* 元宵 */
        .machine_2020_yx{-webkit-transform:translate(-50%,64%);transform:translate(-50%,64%);height:540px}
        .machine_2020_yx .rotate_box{width:38%;padding-top:11%}
        .machine_2020_yx .rotate_box dd{height:170px;margin-right:8%;background:url(/images/hongbao/prize_1.png)}
        .machine_2020_yx .rotate_btn_css,.machine_2020_yx .zw_hh{right:164px;bottom:345px}
        .machine_2020_yx .rotate_btn_css img, .machine_2020_yx .zw_hh img{width: auto;}
        .machine_2020_yx .newyear_num_st{width:80px;bottom:122px;right:374px;font-size:54px}
        .content .machine_2020_yx .btn_box a{background-size:60%}
        .machine_2020_yx .receiveAfter{top:-100px;left:320px}
        .machine_2020_yx .new_zfy{width:90px;font-size:14px;left:312px;top:216px}
        .machine_2020_yx .new_zfy.new_zfy_1{left:437px}
        .machine_2020_yx .btn_box{bottom: 40px;}

        /*　新年活动 开始*/
        .content .btn_2020_6668 {visibility: hidden;}
        .content .ny_hb_btn{transition:.3s;margin:390px 0 0 113px;width:170px;height:50px;background:url(/images/hongbao/new/hb_btn.png) center no-repeat;background-size:100%}
        .content .ny_hb_btn:hover,.content .ny_hb_btn:active{transform:scale(1.05)}
        .hb_mask_1{display:none;position:fixed;left:0;top:0;z-index:4;width:100%;height:100%;background-color:rgba(0,0,0,0.5)}
        /*.hb_close_1{display:none;cursor:pointer;opacity:1;position:absolute;right:5%;top:5%;z-index:11;width:64px;height:64px;background-image:url("/images/hongbao/close.png")}*/
        .hb_close_1{transition:.3s;display:none;cursor:pointer;opacity:1;position:absolute;left: 50%;bottom: 9%;text-align: center;z-index: 11;width: 100px;height: 40px;line-height: 40px;background: #faec83;border-radius: 5px;margin-left: -50px;color: #c30202;font-size: 20px;}
        .hb_close_1:hover{transform: scale(1.05)}
        .receiveAfter_1{z-index:10;display:none;-webkit-transform:scale(0.1);transform:scale(0.1);position:fixed;width:552px;height:622px;background:url(/images/hongbao/new/hb_bg.png) center no-repeat;background-size:100%;left:50%;top:50%;margin:-360px -276px}
        .receiveAfter_1 .tip{padding-top:24rem}
        .receiveAfter_1 .tip p, .receiveAfter_1 .tip .hb_mount{color:#fff99a !important;font-size:2.4rem !important;text-align:center;padding: 0 !important;font-weight: normal !important;}
        .reback{-webkit-animation:reback .5s linear forwards;animation:reback .5s linear forwards}
        #hongbao_animation{position:fixed;top:0;left:50%;width:1080px;height:100%;margin-left:-540px;z-index:5;/*pointer-events:none*/ /* 禁止点击事件*/}
        #hongbao_animation>div{/*width:150px;height:150px;*/position:absolute;-webkit-animation-iteration-count:infinite;-webkit-animation-direction:normal,normal;-webkit-animation-timing-function:linear,ease-in;-webkit-backface-visibility:hidden;animation-iteration-count:infinite;animation-direction:normal,normal;animation-timing-function:linear,ease-in;backface-visibility:hidden}
        #hongbao_animation>div>img{cursor:pointer;/*height:3rem;*/width:auto;position:absolute;-webkit-animation-iteration-count:infinite;-webkit-animation-direction:alternate;-webkit-animation-timing-function:linear;-webkit-backface-visibility:hidden;animation-iteration-count:infinite;animation-direction:alternate;animation-timing-function:linear;backface-visibility:hidden}
        .shake{animation:shake .5s linear;-webkit-animation:shake .5s linear;}

        @keyframes reback {
            100% {
                -webkit-transform: scale(1);
                transform: scale(1);
            }
        }
        /* 红包下落动画 */
        @-webkit-keyframes fade{
            0%,90%{opacity:1}
            100%{opacity:0}
        }
        @keyframes fade{
            0%,90%{opacity:1}
            100%{opacity:0}
        }
        @-webkit-keyframes drop{
            0%{-webkit-transform:translate3d(0,0,0)}
            100%{-webkit-transform:translate3d(0,1100px,0)}
        }
        @keyframes drop{
            0%{transform:translate3d(0,0,0)}
            100%{transform:translate3d(0,1100px,0)}
        }
        @-webkit-keyframes clockwiseSpin{
            0%{-webkit-transform:none}
            100%{-webkit-transform:rotate(480deg)}
        }
        @keyframes clockwiseSpin{
            0%{transform:none}
            100%{transform:rotate(480deg)}
        }
        @-webkit-keyframes counterclockwiseSpinAndFlip{
            0%{-webkit-transform:none}
            100%{-webkit-transform:rotate(-480deg)}
        }
        @keyframes counterclockwiseSpinAndFlip{
            0%{transform:none}
            100%{transform:rotate(-480deg)}
        }
        /* 红包闪动 */
        @keyframes shake{
            0%{transform:rotate(5deg) translate3d(0,0,0)}
            50%{transform:rotate(-5deg) translate3d(0,0,0)}
            100%{transform:rotate(5deg) translate3d(0,0,0)}
        }
        @-webkit-keyframes shake{
            0%{-webkit-transform:rotate(5deg) translate3d(0,0,0)}
            50%{-webkit-transform:rotate(-5deg) translate3d(0,0,0)}
            100%{-webkit-transform:rotate(5deg) translate3d(0,0,0)}
        }
        /*　新年活动 结束*/

    </style>
    <script type="text/javascript" src="../../../js/jquery.js"></script>
    <script type="text/javascript" src="../../../js/common.js?v=<?php echo AUTOVER; ?>"></script>
</head>
<body >
<div class="all_content content">
    <!-- 新年活动 领取红包 开始-->
    <?php
    if($flag=='2020_288w'){
        echo ' <div class="hb_mask">
                    <div class="blin"></div>
                    <div class="caidai"></div>
                    <div class="winning">
                        <div class="red-head"></div>
                        <div class="red-body">
                            <p class="hb_mount">0</p>
                            <p class="hb_title"> '.$companyName.'</p>
                        </div>
                        <!--<div class="hb_card">
                            <a href="" target="_self" class="win"></a>
                        </div>
                        <a target="_self" class="btn"></a>-->
                    </div>
                     <span class="hb_close"></span>
                </div>';
    }else if($flag=='2020_6668' || $flag=='newyear_hb'){ // 新年活动 2021
        echo '<div class="hb_mask_1">
                <div id="hongbao_animation"> </div>
              </div>';
    }
    ?>

    <!-- 领取红包 结束-->

<?php
switch ($to){
    case 'ios': // IOS信任教程
        echo '<div class="ios_content"> <img src="/images/iosold_'.$tplfilename.'.gif?v=3" alt="IOS信任教程" style="width:1000px"></div>' ;
        break ;
    default:
        echo '<div class="pro_content">';
        if($to == 7){
            switch ($flag){
                case 'newyear_hb': // 新年活动 2021
                    echo '<div class="newyear_2021">
                            <div class="newyear_sec">
                                 <div class="new_y_top">
                                     <div class="top_timer new_year_time_de">
                                        
                                      </div>
                                    <div class="new_y_bottom"> <span class="timer_d"> 00 </span> </div>
                                </div>   
                                <div class="newyear_hby_btn ny_hb_btn"> </div> <!-- 召唤红包雨按钮 -->       
                                <span class="n_hb_num newyear_num">0</span> <!-- 红包数量 -->   
                             </div>   
                                 <!-- 领取红包成功 -->
                                  <div class="receiveAfter_1 receiveAfter_act">                                 
                                    <div class="tip">
                                        <p>恭喜您获得</p>
                                        <p> <span class="hb_mount">0</span><span>元红包</span> </p>
                                    </div>
                                       <!-- 关闭红包 -->
                                    <span class="hb_close_1">领取</span>
                                </div>         
                            </div>';
                    echo '<a class="pro_btn btn_'.$flag.' auto_get" href="javascript:;" data-type="'.$flag.'"></a>';
                    break;
                case '2020_288w': // 新年活动 0086 活动一
                    echo '<div class="newYearFirst">
                                    <div class="newyear_btn"> 
                                        <a class="auto_get pro_btn promos_btn promos_newyear_1 btn_'.$flag.'" href="javascript:;" data-type="'.$flag.'">
                                            <p class="hb_mount">0</p>
                                            <p class="hb_title"> '.$companyName.'</p>   
                                        </a>
                                        <a class="auto_get pro_btn promos_btn promos_newyear_2 btn_'.$flag.'" href="javascript:;" data-type="'.$flag.'">
                                            <p class="hb_mount">0</p>
                                            <p class="hb_title"> '.$companyName.'</p>   
                                        </a>
                                        <a class="auto_get pro_btn promos_btn promos_newyear_3 btn_'.$flag.'" href="javascript:;" data-type="'.$flag.'">
                                            <p class="hb_mount">0</p>
                                            <p class="hb_title"> '.$companyName.'</p>   
                                        </a>
                                    </div>
                              </div>';
                    break;
                case '2020_888w': // 新年活动 0086 活动二
                case '2020_yx': // 元宵活动 0086 活动二
                    echo '<div class="machine machine_'.$flag.'">
                                <div class="receiveAfter receiveAfter_act">
                                    <div class="tip"> <span>￥</span><span class="hb_mount">0</span><span>元</span> </div>
                                </div>
                                <div class="new_zfy new_zfy_0"> </div>
                                <div class="new_zfy new_zfy_1"> </div>
                                <dl class="rotate_box">
                                    <dd ></dd>
                                    <dd ></dd>
                                    <dd ></dd>
                                </dl>
                                <!--<a class="poiner">
                                    <img src="/images/hongbao/poiner.png" alt="">
                                </a>-->
                                <span class="zw_hh"></span> <!-- 占位 -->
                                <a class="rotate_btn rotate_btn_css" >
                                    <img src="/images/hongbao/rocker'.($flag=='2020_yx'?'_1':'').'.png" alt="">
                                </a>
                                <div class="newyear_num newyear_num_st"> 0 </div>
                                <div class="btn_box">
                                   <a class="pro_btn btn_'.$flag.' auto_get" href="javascript:;" data-type="'.$flag.'"></a>
                                </div>
                            </div>';
                    break;
                case '2020_6668': // 6668 新年活动
                    echo '<span class="hb_close_1"></span>
                          <div class="receiveAfter_1 receiveAfter_act">
                            <div class="tip">
                                <p>恭喜您获得</p>
                                <p> <span class="hb_mount">0</span><span>元红包</span> </p>
                            </div>
                        </div>
                        <a class="ny_hb_btn" href="javascript:;"></a>';
                    echo '<a class="pro_btn btn_'.$flag.' auto_get" href="javascript:;" data-type="'.$flag.'"></a>';
                    break;
                default:
                    echo '<a class="pro_btn btn_'.$flag.' auto_get" href="javascript:;" data-type="'.$flag.'"></a>';
            }


        }
        echo '<img src="'.$keys.'" alt="'.$title.'">';
        echo '</div>' ;
        break;
}

?>

<input id="uid" name="uid" value="<?php echo $uid?>" type="hidden" />
<input id="userid" name="user_id" value="<?php echo $userid?>" type="hidden" />
<input id="username" name="username" value="<?php echo $username?>" type="hidden" />
</div>
<script type="text/javascript" src="/js/layer/layer.js"></script>
<?php
if($flag=='2020_888w'  || $flag=='2020_yx'){ // 0086 新年活动
echo '<script type="text/javascript" src="/js/jquery.easing.js"></script>
       <script type="text/javascript" src="/js/mejs.js"></script>';
}else if($flag=='2020_6668' || $flag=='newyear_hb'){ // 6668 新年活动 ，0086 2021 新年活动
    echo '<script type="text/javascript" src="/js/newyear_hb.js"></script>';
}
?>

<script type="text/javascript" src="/js/register/validate.js?v=<?php echo AUTOVER; ?>"></script>

<script type="text/javascript">

    $(function () {
        var show_bg = '<?php echo $showbg;?>';
        var hb_flag = '<?php echo $flag;?>';
        var uid = '<?php echo $uid;?>';
        var ajaxurl = '<?php echo $ajaxUrl;?>';
        var alertTime = 2000 ; // 弹窗提示时间

        if(hb_flag=='2020_888w' || hb_flag=='2020_6668' || hb_flag=='2020_yx' || hb_flag=='newyear_hb'){ // 新年活动二
            var newYearBeginTime = '<?php echo $newYearBeginTime;?>';
            var newYearEndTime  = '<?php echo $newYearEndTime;?>';
            var curtime = '<?php echo $curtime;?>';
            if(hb_flag=='2020_888w' || hb_flag=='2020_yx' || hb_flag=='newyear_hb'){ // 独有， 2021 新年活动
                if(!show_bg){ // 6668 新年活动没有
                    setTimerAc('.new_year_time_de'); // 需要判断是否开启活动
                    getNewYearTime();
                }
            }

        }

        // 点击申请
        function getPromosAction() {
            var proflage = false ;
            var $hb_mask = $('.hb_mask'); // 新年活动一
            var $winning = $('.winning'); // 新年活动一
            var $rotate_btn = $('.rotate_btn');// 摇杆按钮
            var $receiveAfter_act = $('.receiveAfter_act');// 领取后显示
            var $hb_mask_1 = $('.hb_mask_1'); // 新年活动一
            var $hb_close_1 = $('.hb_close_1');// 关闭按钮

            $('.hb_close').on('click',function () { // 关闭新年红包
                $hb_mask.hide();
                $winning.removeClass('reback');
            });

            $('.auto_get').on('click',function () {
                var userAgents='<?php echo $_SESSION['Agents'];?>';
                var type = $(this).attr('data-type');
                var indexNum = Number($(this).index())+1;

                var postData = {
                    type_flag:type,
                    uid:$('#uid').val(),
                    user_id:$('#userid').val(),
                    username:$('#username').val(),
                    action:'receive_red_envelope', //新春活动用到此参数，其他活动不用
                };
                if(userAgents=='demoguest'){
                    layer.msg('请注册真实用户',{time:alertTime});
                    return false;
                }

                if(type =='2020_888w' || type=='2020_yx'){ // 新年活动二摇奖
                    var g_num = Number($('.newyear_num').text());
                    if(curtime < newYearBeginTime || curtime > newYearEndTime){
                        if(type =='2020_888w'){
                            layer.msg('请于北京时间1月25号中午12:00-1月30日11:59期间领取红包!',{time:alertTime});
                        }else{
                            layer.msg('请于美东时间02月11日期间领取红包!',{time:alertTime});
                        }

                        return false;
                    }
                    if(g_num < 1){
                        layer.msg('可领次数不足',{time:alertTime});
                        return false;
                    }
                    if($rotate_btn.hasClass('act')){ // 正在摇奖中
                        return false;
                    }
                    $('.new_zfy').text('');// 清空祝福语
                    $receiveAfter_act.hide().removeClass('reback'); // 初始化金额弹窗

                    setTimeout(function () {
                        getNewYearTime('receive');
                    },5000)
                    $rotate_btn.click();
                    return false ; // 不需要执行下面了
                }

                if(proflage){
                    return ;
                }
                proflage = true ;

                var url = ajaxurl;
                $.post(url, postData, function(res) {
                  if(res){
                      proflage = false ;
                      if(res.info){
                          layer.msg(res.info,{time:alertTime});
                      }else{
                          layer.msg(res.describe,{time:alertTime});
                      }

                      if(type == '2020_288w'){
                          if(res.status=='200'){ // 领取成功
                              $hb_mask.show();
                              $winning.addClass('reback');
                              $('.promos_newyear_'+indexNum).addClass('active');
                              $('.promos_newyear_'+indexNum).find('.hb_title').show();
                              $('.promos_newyear_'+indexNum).find('.hb_mount').show();
                              $('.hb_mount').text(res.data.giftGold);
                          }
                      }else if(type == '2020_6668' || type=='newyear_hb'){ // 2021 新年红包
                          if(res.status=='200'){ // 领取成功 不需要弹出提示
                              $receiveAfter_act.show().addClass('reback');
                              $hb_close_1.show();
                              $('.hb_mount').text(res.data.giftGold);
                              $('.newyear_num').text(0); // 红包只能领取一次
                          }else{
                              $hb_mask_1.hide();
                              $receiveAfter_act.hide();
                              $hb_close_1.hide();
                          }
                      }

                  }

                }, 'json')

            })
        }

        // 获取新年活动二红包次数
        function getNewYearTime(type){
            if(!uid){
                return false;
            }
            var receiveflage = false ;
            var $rotate_btn = $('.rotate_btn');// 摇杆按钮
            var $receiveAfter_act = $('.receiveAfter_act');// 领取后显示

            var url = ajaxurl; // '/app/member/activity/newyear2020_888w.php'
            var newpostData = {
                action:'getGrabTimes' // 查询可领取次数
            }
            if(type){ // 领取
                newpostData.action = 'receive_red_envelope'; // 领取
            }
            if(receiveflage){
                return false;
            }
            receiveflage = true;
            $.ajax({
                type : 'POST',
                url : url ,
                data : newpostData,
                dataType : 'json',
                success:function(res) {
                    if(res){
                        receiveflage = false;
                        if(res.status =='200'){ // 成功
                            $('.newyear_num').text(res.data.lastTimes);
                            if(type =='receive'){ // 领取成功
                                $('.hb_mount').text(res.data.giftGold);
                                $receiveAfter_act.show().addClass('reback');
                            }
                            if(res.data.lastTimes < 1){ // 次数不足,不能摇奖
                                $rotate_btn.addClass('act');
                            }
                        }else{
                            layer.msg(res.describe,{time:alertTime});
                        }

                    }

                },
                error:function(){
                    receiveflage = false;
                    layer.msg('网络异常',{time:alertTime});
                }
            });

        }

        // 点击红包
        function getRedBag(){
            var $hb_mask_1 = $('.hb_mask_1'); // 新年活动一
            var $receiveAfter_act = $('.receiveAfter_act');// 领取后显示
            var $hb_close = $('.hb_close_1');// 关闭按钮

            if(hb_flag=='newyear_hb' && show_bg=='bg'){   /* 2021 新年活动 独有 */
                if(curtime > newYearBeginTime && curtime < newYearEndTime){
                    //autoGetPromos(); // 防止节点监听不了点击事件
                    $hb_mask_1.show();
                    // 红包雨 , 北京时间 ：2021/02/11 00:00:00 到 2021/02/13 23:59:59
                    hbInit(); // 红包雨
                }
            }

            $hb_close.on('click',function () { // 关闭新年红包
                $(this).hide();
                $hb_mask_1.hide();
                $receiveAfter_act.hide().removeClass('reback');
            });

            $('.ny_hb_btn').off().on('click',function () { // 召唤红包雨
                if(!uid){
                    layer.msg('请先登录',{time:alertTime});
                    return ;
                }

                if(curtime < newYearBeginTime || curtime > newYearEndTime){
                    layer.msg('请于北京时间02月11号-02月12号期间领取红包!',{time:alertTime});
                    return false;
                }
                if($('.newyear_num').text()==0){
                    layer.msg('没有可领取次数',{time:alertTime});
                    return false;
                }
                $hb_mask_1.show();
                hbInit(); // 红包雨
            });
            $('#hongbao_animation').on('click','.hongbao_li img',function () {  // 点击红包，抢红包
                var type = $(this).attr('data-type');
                if(type=='hb'){
                    $('.auto_get').click();
                }
            })
        }

        getPromosAction();
        getRedBag();


    });
</script>

</body>
</html>
