<?php
session_start();

include "../../../../app/member/include/config.inc.php";

$companyName = $_SESSION['COMPANY_NAME_SESSION'];
$type = isset($_REQUEST['type'])?$_REQUEST['type']:'' ; // 活动类型
$title = isset($_REQUEST['title'])?$_REQUEST['title']:'' ; // 优惠完整标题
$keys = isset($_REQUEST['key'])?$_REQUEST['key']:'' ;  // 活动图片链接地址
$ajaxUrl = (isset($_REQUEST['api']) && $type == 7)?$_REQUEST['api']:'' ;  // 自动领取接口地址
$flag = isset($_REQUEST['flag'])?$_REQUEST['flag']:''; // 自动领取唯一标识

// 新年活动是否开启
$sRedPocketset = $redisObj->getSimpleOne('red_pocket_open'); // 取redis 设置的值
$aRedPocketset = json_decode($sRedPocketset,true) ;

// 活动于北京时间1月24号（除夕）中午12:00-次日11：59开始，活动时间持续24小时
$newYearBeginTime= $aRedPocketset['newYearBeginTime']?$aRedPocketset['newYearBeginTime']:'2021-02-11 00:00:00'; // 活动二开始时间
$newYearEndTime = $aRedPocketset['newYearEndTime']?$aRedPocketset['newYearEndTime']:'2021-02-12 23:59:59'; // 活动二结束时间
$curtime = date("Y-m-d H:i:s",time()+12*60*60); // 北京时间

?>

<style >
     body{color:#6e6e6e;}
     .back-bar{background:#e3e3e3;height:40px;max-width:970px;margin:10px auto}
     .btn-back{border:0;background:#404040;color:#fff;text-transform:uppercase;float:left;height: 24px;padding: 8px 20px;}
     .btn-back:hover{background:#525252;color:#fff}
     .article-box{max-width:930px;margin:0 auto}
     .article-title{font-family:inherit;margin:20px auto}
     .title{margin:0px;font-size:28px}
     .sub-wrap.promotion-banner{background-size:930px 930px;background-repeat:no-repeat;width: 930px}
     .details-wrap p{font-size:16px;padding: 5px 0;}
     .packetText > label{word-wrap:break-word;width:190px}
     .content{position:relative}
     .content a:hover{background-color:transparent;}
     .content .promos_btn, .btn_2020_888w,.btn_2020_yx{position:absolute;display:block;width:160px;height:40px;margin:620px 0 0 402px;background:transparent}
     .content .btn_attendance{ margin: 745px 0 0 380px;}
     .content .btn_chess{ margin: 550px 0 0 680px;}
     .content .btn_week{ margin: 725px 0 0 400px;}
     .content .btn_nation{ margin: 535px 0 0 715px;}
     .content .btn_king{ margin: 1031px 0 0 383px;}
     .content .btn_dragon{ margin: 945px 0 0 383px;}
     .content .btn_shuangdan{ margin: 844px 0 0 451px;}
     .content .btn_promotion{ margin: 646px 0 0 385px;}
     .content .btn_sport_dm{ margin: 695px 0 0 385px;}
     .content .btn_sj_holiday{ margin: 1843px 0 0 670px;}
     .content .btn_euro{ margin: 758px 0 0 492px;}

     /* 2021 新年活动 */
     .content .new_y_top{ position: absolute;width: 502px;left: 50%;height: 130px;margin: 92px 0 0 -242px;}
     .content .new_y_top .top_timer {height:40px;line-height:40px;margin:32px 0 0 18px;font-size: 26px;color: #ba000b;}
     .content .new_y_top .top_timer span{margin:0 2px;background: #ba000b;color: #e8c3c3;border-radius: 15px;display: inline-block;width: 60px;text-align: center;}
     .content .new_y_top .new_y_bottom{font-size: 32px;color: #ba000b;height: 60px;line-height: 72px;text-align: center;}

     .content .newyear_2021 .newyear_hby_btn{/*display:none;*/cursor:pointer;width: 304px;height: 104px;background: url(/images/hongbao/hb_btn.png) center no-repeat;position:absolute;margin: 900px 0 0 350px;transform: scale(.7);transition: .3s;}
     .content .newyear_2021 .newyear_hby_btn:hover{transform: scale(.75);}
     .content .newyear_2021 .n_hb_num{position: absolute;margin: 1023px 0 0 283px;font-size: 30px;color: #fff;}

     /* 新年 活动一 中奖提示  开始 */
     .content .newyear_btn{ position: absolute;width: 100%;height: 282px;margin: 440px 0 0 0;}
     .content .btn_2020_288w{ width: 222px;height: 100%;background: url(/images/hongbao/hb_off.png) center no-repeat;margin: 0;left: 350px;}
     .content .btn_2020_288w.active{background: url(/images/hongbao/hb_on.png) center no-repeat;}
     .content .btn_2020_288w.promos_newyear_1 {left:30px;}
     .content .btn_2020_288w:last-child{left: 670px;}

     .hb_mask{display:none;position:fixed;left:0;top:0;z-index:10;width:100%;height:100%;background-color:rgba(0,0,0,0.85)}
     .hb_mask .blin{width:100%;max-width:747px;height:100%;max-height:752px;margin:100px auto 0;background-image:url("/images/hongbao/gold.png");-webkit-animation:circle 10s linear infinite;animation:circle 10s linear infinite}
     .hb_mask .caidai{position:absolute;left:0;top:0;z-index:1;width:100%;height:100%;background-image:url("/images/hongbao/dianzhui.png");-webkit-transform:scale(1.2);transform:scale(1.2)}
     .hb_mask .winning{position:absolute;left:50%;top:50%;z-index:1;width:198px;height:265px;margin:-10% -7%;-webkit-transform:scale(0.1);transform:scale(0.1)}
     .reback{-o-animation:reback .5s linear forwards;-webkit-animation:reback .5s linear forwards;animation:reback .5s linear forwards}

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
     .rotate_box{width:60%;margin:0 auto;padding-top:15%}
     .rotate_box dd{position: relative;width:24.8%;height:250px;margin-right:9%;float:left;background:url(/images/hongbao/prize.png);background-size:cover}
     .rotate_box dd:last-child{margin-right:0}
     .rotate_btn_css,.zw_hh{width:92px;height:129px;overflow:hidden;position:absolute;right:14px;bottom:515px;transform-origin:center bottom}
     .zw_hh{z-index: 1}
     .poiner{width:.88rem;position:absolute;right:.4rem;top:-1.2rem}
     .btn_box{width:316px;height:84px;position:absolute;left:50%;margin-left:-158px;bottom:30px}
     .content .btn_box a{transition:.3s;width:100%;height:100%;margin:0;background:url(/images/hongbao/yj_btn.png) center no-repeat}
     .content .btn_box a:hover{background:url(/images/hongbao/yj_btn_hover.png) center no-repeat;}
     .newyear_num_st{position:absolute;width:112px;bottom:118px;color:#f6bd2f;font-size:90px;font-weight:bold;text-align:center;right:297px}
     .machine .receiveAfter{z-index:2;display:none;transform: scale(0.1);position:absolute;width: 323px;height: 462px;background: url(/images/hongbao/hb1_on.png) center no-repeat;top:20px;left: 285px;color: #d6261e;font-size: 24px;}
     .machine .receiveAfter .tip{text-align: center;padding-top: 88px;}
     .machine .receiveAfter .tip .hb_mount{padding: 0 !important;}
     .machine .new_zfy{width:138px;position:absolute;font-size:20px;color:#fdc731;left:186px;top:208px;text-align:center;z-index:1}
     .machine .new_zfy.new_zfy_1{left: 375px;}
     /* 元宵 */
     .machine_2020_yx{-webkit-transform:translate(-50%,64%);transform:translate(-50%,64%);height:500px}
     .machine_2020_yx .rotate_box{width:38%;padding-top:11%}
     .machine_2020_yx .rotate_box dd{height:160px;margin-right:8%;background:url(/images/hongbao/prize_1.png)}
     .machine_2020_yx .rotate_btn_css,.machine_2020_yx .zw_hh{right:148px;bottom:317px}
     .machine_2020_yx .newyear_num_st{width:80px;bottom:106px;right:346px;font-size:54px}
     .content .machine_2020_yx .btn_box a{background-size:60%}
     .machine_2020_yx .receiveAfter{top:-120px;left:278px}
     .machine_2020_yx .new_zfy{width:90px;font-size:14px;left:287px;top:204px}
     .machine_2020_yx .new_zfy.new_zfy_1{left:403px}
    /* 幸运转盘 */
     .lucky_input>div{line-height:40px}
     .lucky_input>div input{line-height:28px;padding: 0 5px;}
     .box-lucky{position:absolute;left:50%;margin:47% -360px 0;width:720px;height:720px;background:url(/images/hongbao/lucky/prize_bg.png) center no-repeat;background-size:100%}
     .lucky-wrap{position:relative;width:100%;height:100%;background:url('/images/hongbao/lucky/prize_bg_sec.png') center no-repeat;background-size:90%;transform:rotate(-25deg);-webkit-transform:rotate(-25deg)}
     .lucky-wrap img{display:inline-block;width:60px;height:60px}
     .lucky-wrap span{display:inline-block;position:absolute;top:0;left:30.9%;width:300px;height:50%;color:#fff;-webkit-transform-origin:50% 100%;transform-origin:50% 100%;text-align:center}
     .lucky-wrap span.lucky-span1{-webkit-transform:rotate(22.5deg);transform:rotate(22.5deg)}
     .lucky-wrap span.lucky-span2{-webkit-transform:rotate(75.5deg);transform:rotate(75.5deg)}
     .lucky-wrap span.lucky-span3{-webkit-transform:rotate(128.5deg);transform:rotate(128.5deg)}
     .lucky-wrap span.lucky-span4{-webkit-transform:rotate(180.5deg);transform:rotate(180.5deg)}
     .lucky-wrap span.lucky-span5{-webkit-transform:rotate(232.5deg);transform:rotate(232.5deg)}
     .lucky-wrap span.lucky-span6{-webkit-transform:rotate(283.5deg);transform:rotate(283.5deg)}
     .lucky-wrap span.lucky-span7{-webkit-transform:rotate(331.5deg);transform:rotate(331.5deg)}
     .lucky-wrap i{display:block;width:100%;height:60px;font-style:normal;font-size:22px;line-height:32px;margin:65px 0 12px}
     .lucky-wrap img{max-width:100%}
     .lucky-btn{position:absolute;left:50%;top:50%;text-indent:-999em;z-index:11;-webkit-transform:translate(-50%,-50%);transform:translate(-50%,-50%);width:262px;height:262px;background:url('/images/hongbao/lucky/prize_btn.png') center no-repeat}
     .luckyM{display:none;position:absolute;width:70%;height:3rem;line-height:3rem;left:50%;background:#f79d07;margin:135% -35% 0;border-radius:50px;text-align:center;color:#fff;font-size:1.3rem;}

    /* 红包雨 */
     .hb_mask_1{display:none;position:fixed;left:0;top:0;z-index:4;width:100%;height:100%;background-color:rgba(0,0,0,0.5)}
     .hb_close_1{transition:.3s;display:none;cursor:pointer;opacity:1;position:absolute;left: 50%;bottom: 9%;text-align: center;z-index: 11;width: 100px;height: 40px;line-height: 40px;background: #faec83;border-radius: 5px;margin-left: -50px;color: #c30202;font-size: 20px;}
     .hb_close_1:hover{transform: scale(1.05)}
     .receiveAfter_1{z-index:10;display:none;-webkit-transform:scale(0.1);transform:scale(0.1);position:fixed;width:552px;height:622px;background:url(/images/hongbao/new/hb_bg.png) center no-repeat;background-size:100%;left:50%;top:50%;margin:-336px -276px}
     .receiveAfter_1 .tip{padding-top:24rem}
     .receiveAfter_1 .tip p{color:#fff99a;font-size:2.4rem;text-align:center}
     .reback{-webkit-animation:reback .5s linear forwards;animation:reback .5s linear forwards}

     #hongbao_animation{position:fixed;top:0;left:50%;width:1080px;height:100%;margin-left:-540px;z-index:5;/*pointer-events:none*/ /* 禁止点击事件*/}
     #hongbao_animation>div{/*width:150px;height:150px;*/position:absolute;-webkit-animation-iteration-count:infinite;-webkit-animation-direction:normal,normal;-webkit-animation-timing-function:linear,ease-in;-webkit-backface-visibility:hidden;animation-iteration-count:infinite;animation-direction:normal,normal;animation-timing-function:linear,ease-in;backface-visibility:hidden}
     #hongbao_animation>div>img{cursor:pointer;/*height:3rem;width:3rem;*/position:absolute;-webkit-animation-iteration-count:infinite;-webkit-animation-direction:alternate;-webkit-animation-timing-function:linear;-webkit-backface-visibility:hidden;animation-iteration-count:infinite;animation-direction:alternate;animation-timing-function:linear;backface-visibility:hidden}
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

</style>

<!-- 2021 新年活动 背影 -->
<div class="hb_mask_1">
    <div id="hongbao_animation"> </div>
</div>

<div class="details-wrap">
    <div class="back-bar">
        <div class="btn-group btn-block">
            <div class="btn-grp pull-left clearfix">
                <a class="btn-back to_promos" href="javascript:;"  >
                    <span style="line-height: 21px;"> < 回到上一页</span>
                </a>
            </div>
        </div>
    </div>

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
        }
    ?>

    <!-- 领取红包 结束-->

    <div class="article-box">
        <div class="article-title">
            <h2 class="title"><strong><?php echo $title;?></strong></h2>
        </div>
        <div class="content">
            <?php
            if($type == 7){
                switch ($flag){
                    case 'newyear_hb': // 新年活动 2021
                        echo '<div class="newyear_2021">
                                 <div class="new_y_top">
                                     <div class="top_timer new_year_time_de">
                                        
                                      </div>
                                    <div class="new_y_bottom"> <span class="timer_d"> 00 </span> </div>
                                </div>   
                                <div class="newyear_hby_btn ny_hb_btn"> </div> <!-- 召唤红包雨按钮 -->       
                                <span class="n_hb_num newyear_num">0</span> <!-- 红包数量 -->      
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
                        echo '<a class="promos_btn btn_'.$flag.'" href="javascript:;" data-type="'.$flag.'"></a>';
                        break;
                    case '2020_288w': // 新年活动 0086 活动一
                        echo '<div class="newYearFirst">
                                    <div class="newyear_btn"> 
                                        <a class="promos_btn promos_newyear_1 btn_'.$flag.'" href="javascript:;" data-type="'.$flag.'">
                                            <p class="hb_mount">0</p>
                                            <p class="hb_title"> '.$companyName.'</p>   
                                        </a>
                                        <a class="promos_btn promos_newyear_2 btn_'.$flag.'" href="javascript:;" data-type="'.$flag.'">
                                            <p class="hb_mount">0</p>
                                            <p class="hb_title"> '.$companyName.'</p>   
                                        </a>
                                        <a class="promos_btn promos_newyear_3 btn_'.$flag.'" href="javascript:;" data-type="'.$flag.'">
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
                                   <a class="promos_btn btn_'.$flag.'" href="javascript:;" data-type="'.$flag.'"></a>
                                </div>
                            </div>';
                        break;
                    case 'best_lucky':
                        echo '<div class="box-lucky">
                                    <div class="lucky-wrap" >
                                        
                                    </div>
                                    <a class="lucky-btn" href="javascript:void(0);" data-luckyTip="0"><i></i>立即抽奖</a>
                                </div>
                                <div class="luckyM ">恭喜抽中赠送彩金<span class="lucky_hb_mount">0</span>元</div> ';
                        break;
                    default:
                        echo '<a class="promos_btn btn_'.$flag.'" href="javascript:;" data-type="'.$flag.'"></a>';
                }

            }
            ?>
            <img class="sub-wrap promotion-banner" src="<?php echo $keys; ?>">
        </div>
    </div>
</div>
<?php
// 新年活动二
if($flag=='2020_888w' || $flag=='2020_yx'){
    echo '
        <script type="text/javascript" src="js/jquery.easing.js"></script>
        <script type="text/javascript" src="js/mejs.js"></script>
    ';
}
if($flag=='best_lucky'){
    echo '
        <script type="text/javascript" src="js/anime.min.js"></script>
        <script type="text/javascript" src="js/bestLucky.js"></script>
    ';
}
if($flag =='newyear_hb'){ // 红包雨动画
    echo '<script type="text/javascript" src="/js/newyear_hb.js"></script>';
}
?>


<script type="text/javascript">
    $(function () {
        var hb_flag = '<?php echo $flag;?>';
        var ajaxurl = '<?php echo $ajaxUrl;?>';

        if(hb_flag=='2020_888w' || hb_flag=='2020_yx' || hb_flag=='newyear_hb'){ // 新年活动二,2021 新年活动
            var newYearBeginTime = '<?php echo $newYearBeginTime;?>';
            var newYearEndTime  = '<?php echo $newYearEndTime;?>';
            var curtime = '<?php echo $curtime;?>';

            setTimerAc('.new_year_time_de'); // 需要判断是否开启活动
            getNewYearTime();
        }

        if(hb_flag=='best_lucky'){ // 幸运转盘活动
            var lucky_par = {
                action:'check'
            };
            var $luckyBox = $('.content').find('.lucky-wrap');
            var $luckyBtn = $('.content').find('.lucky-btn');
            var $luckyM = $('.content').find('.luckyM');

            var Lucky = Turntable.create();
            getBestLucky(lucky_par);
            getLuckyGift();

        }

        // 自动领取
        function autoGetPromos() {
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

            $('.promos_btn').on('click',function () { // 领取
                var type = $(this).attr('data-type');
                var indexNum = Number($(this).index())+1;

                if(!uid){
                    layer.msg('请先登录',{time:alertTime});
                    return ;
                }
                if(userAgents=='demoguest'){
                    layer.msg('请注册真实用户',{time:alertTime});
                    return ;
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
                var postData = {
                    type_flag:type,
                    action:'receive_red_envelope' //新春活动用到此参数，其他活动不用
                }

                proflage = true ;
                $.ajax({
                    type : 'POST',
                    url : ajaxurl ,
                    data : postData,
                    dataType : 'json',
                    success:function(res) {
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
                            }
                            if(type == 'newyear_hb'){ // 2021 新年活动
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

                    },
                    error:function(){
                        layer.msg('网络异常',{time:alertTime});
                        proflage = false ;
                    }
                });


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

            var url = ajaxurl; //'/app/member/api/newyear2020_888w.php'
            var postData = {
                action:'getGrabTimes' // 查询可领取次数
            }
            if(type){ // 领取
                postData.action = 'receive_red_envelope'; // 领取
            }
            if(receiveflage){
                return false;
            }
            receiveflage = true;
            $.ajax({
                type : 'POST',
                url : url ,
                data : postData,
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

        // 获取幸运大转盘活动配置
        function getBestLucky(params){
            var luckUrl = ajaxurl;
            if(!params.mem_yzm && params.action=='draw'){ // 获取验证码
                luckUrl ='/app/member/api/message_xsend.php';
            }
            $.ajax({
                type : 'POST',
                url : luckUrl,
                data : params,
                dataType : 'json',
                success:function(res) {
                    if(res){
                        var str ='';
                        // console.log(res.data)
                        if(params.action=='check'){ // 检验资格
                            if(res.status=='200'){ // 符合抽奖条件
                                $luckyBtn.attr('data-luckyTip','1');
                            }else{
                                layer.msg(res.describe,{time:alertTime});
                                $luckyBtn.attr('data-luckyTip','0');
                            }
                            for(var i=0;i<res.data.length;i++){
                                str +=' <span class="lucky-span'+(i+1)+'" data-id="'+ res.data[i].id +'">' +
                                    '<i>'+ res.data[i].best_lucky_content +'</i>' +
                                    '<img src="/images/hongbao/lucky/prize_'+ (i+1) +'.png" alt="'+  res.data[i].best_lucky_content +'">' +
                                    '</span>';
                            }
                            $luckyBox.html(str);
                        }
                        if(params.action=='draw') { // 验证码获取，抽奖
                            if(params.mem_yzm){ // 最后抽奖步骤
                                if(res.status=='200') { // 符合抽奖条件,返回抽奖数据
                                    $luckyM.find('.lucky_hb_mount').text(res.data.gift_gold);
                                    $luckyBox.find('span').each(function (i,v) {
                                        var txt = $(this).find('i').text();
                                        //console.log(txt)
                                        if(txt==res.data.best_lucky_content){
                                            //console.log(i+'==')
                                            luckyAnime(i); // 抽奖动画
                                        }
                                    });

                                }else {
                                    alert(res.describe);
                                }
                            }else{
                                if(res.status=='200'){ // 发送验证码成功
                                    $('.layui-layer-btn0').text('确定');
                                }
                                alert(res.describe);
                            }

                        }

                    }

                },
                error:function(){
                    layer.msg('网络异常',{time:alertTime});
                }
            });
        }

        // 幸运大转盘红包领取
        function getLuckyGift(){
            $luckyBtn.on('click', function(){
                var phpntStr = '<div class="flex lucky_input"> ' +
                    '<div><span>手机号</span><input type="text" class="phoneNumber" minlength="11" maxlength="11" placeholder="请输入手机号码获取验证码"/></div>'+
                    '<div><span>验证码</span><input type="text" class="yzmNumber" minlength="2" maxlength="8" placeholder="请输入收到的验证码"/></div>'+
                    '</div>';
                var lucktip = $(this).attr('data-luckyTip');
                if(!uid){
                    layer.msg('请先登录',{time:alertTime});
                    return false;
                }
                if(lucktip==0){
                    layer.msg('当前不符合抽奖条件',{time:alertTime});
                    return false;
                }else{

                    layer.confirm(phpntStr, {
                        title:'请输入手机号码获取验证码',
                        btn: ['获取验证码','取消'], //按钮
                        yes: function(index, layero){
                            var phone = $('.phoneNumber').val();
                            var yzm = $('.yzmNumber').val();
                            var btnTxt = $('.layui-layer-btn0').text();
                            var luckData ={
                                action:'draw',
                                mem_phone:phone,
                                mem_yzm:yzm
                            };
                            if(!phone){
                                alert('请输入手机号');
                                return false;
                            }
                            if(btnTxt=='确定' && !yzm){
                                alert('请输入验证码');
                                return false;
                            }
                            if(yzm){ // 输入验证码后请求数据
                                layer.close(index); //按钮【按钮一】的回调
                            }
                            getBestLucky(luckData);

                        },
                        cancel: function(){
                            falg = false;
                            //右上角关闭回调
                        },
                    });

                }

            });
        }
        // 幸运抽奖动画
        function luckyAnime(num){
           // var num = Math.floor(Math.random() * 7); // 七个随机
            //console.log(num)
            Lucky.start(num, function(index){
                var luckyText = $luckyBox.find('span').eq(index).find('i').text();
                layer.msg(luckyText,{time:alertTime});
                $luckyM.show();
                // console.log('index', index, 'lucky-span', 'lucky-span'+(index+1));
            });
        }

        // 2021 新年活动 点击红包
        function getRedBag(){
            var $hb_mask_1 = $('.hb_mask_1'); // 新年活动一
            var $receiveAfter_act = $('.receiveAfter_act');// 领取后显示
            var $hb_close_1 = $('.hb_close_1');// 关闭按钮

            $hb_close_1.on('click',function () { // 关闭新年红包
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
                    $('.promos_btn').click();
                }
            })
        }
        autoGetPromos();
        getRedBag();

    })


</script>
