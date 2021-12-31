<?php
include_once('../../include/config.inc.php');
//include_once ('../../include/activity.class.php');
$prokeys = isset($_REQUEST['prokey'])?$_REQUEST['prokey']:'' ;  // 活动图片key
$uid = $_SESSION['Oid']?$_SESSION['Oid']:(isset($_REQUEST['Oid'])?$_REQUEST['Oid']:'') ;
$userid = $_SESSION['userid']?$_SESSION['userid']:(isset($_REQUEST['userid'])?$_REQUEST['userid']:'') ;
$UserName = $_SESSION['UserName']?$_SESSION['UserName']:(isset($_REQUEST['UserName'])?$_REQUEST['UserName']:'') ;
$Agents = $_SESSION['Agents']?$_SESSION['Agents']:(isset($_REQUEST['Agents'])?$_REQUEST['Agents']:'') ;

$tip = isset($_REQUEST['tip'])?$_REQUEST['tip']:'' ; // 用于app 跳转到这个页面 ?tip=app
$platfrom = 'hg0086' ; // hg0086 ,hg6668
$newyear2020_888w = false; // 是否有新年活动
$best_lucky = false; // 是否有幸运大转盘活动

// 活动于北京时间1月24号（除夕）中午12:00-次日11：59开始，活动时间持续24小时
$newYearBeginTime= '2020-02-10 00:00:00'; // 活动二开始时间
$newYearEndTime = '2020-02-11 23:59:59'; // 活动二结束时间
//$newYearBeginTime= '2020-01-13 00:00:00'; // 活动二开始时间
//$newYearEndTime = '2020-01-14 23:59:59'; // 活动二结束时间
$curtime = date("Y-m-d H:i:s",time());

$lists = returnPromosList('',3);
$categoryList = returnPromosType();

?>

<html class="zh-cn"><head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="HandheldFriendly" content="true"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link href="../../style/font-awesome.min.css" rel="stylesheet" type="text/css" media="screen"/>
    <link href="style/iphone.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css" media="screen"/>
    <title class="web-title"></title>
    <style type="text/css">
    html, body,#footer{max-width: 540px;}
    p,dl,dd{margin: 0;padding: 0;}
    .deposit .tab .item, .deposit .tab .expand{ margin-top: 0;border-top:none;}
    .promo_nav {overflow-x: auto; }
    .ProTab_nav {width: 170%;}
    .ProTab_nav li {width: 12%;margin: 5px 0 0;}
    /*优惠活动*/
    .ProTab_con{padding: 1% 2% 0 2%}
    .promotions_title_box {height:auto; }
    .promotions_title, .pro_btn {text-align: center;font-size: 15px; margin: 6px 0 8px 0; width: 100%; line-height: 26px; color: #757575; font-weight: 600;}
    .material-card-content {position:relative;max-height:0px;}
    .word_pro_sl b{color:#00b3e0;}
    .promotions_title_box img{width: 100%; height: auto; }
    .material-card-content img {width: 100%;}
    .material-card-content a:hover{background-color:transparent;}
    .material-card-content .pro_btn{position:absolute;display:block;width:34%;height:4rem;margin:80% 0 0 67%;background:transparent}
    .material-card-content .btn_attendance{ margin: 113% 0 0 35%;}
    .material-card-content .btn_chess{ margin: 75% 0 0 70%;}
    .material-card-content .btn_king{ margin: 125% 0 0 33%;}
    .material-card-content .btn_dragon{ margin: 150% 0 0 35%;}
    .material-card-content .btn_shuangdan{ margin: 62% 0 0 57%;}
    .material-card-content .btn_promotion{ margin: 76% 0 0 34%;}
    .material-card-content .btn_sport_dm{ margin: 115% 0 0 33%;}
    .material-card-content .btn_sj_holiday{ margin: 256% 0 0 68%;}

    /* 新年 活动一 中奖提示  开始 */
    .content .newyear_btn{position:absolute;width:100%;height:10rem;margin:49% 0 0 0}
    .content .btn_2020_288w{width:31%;height:100%;background:url(/images/hongbao/hb_off.png) center no-repeat;background-size:100%;margin:0;left:35%}
    .content .btn_2020_288w.active{background:url(/images/hongbao/hb_on.png) center no-repeat;background-size:100%}
    .content .btn_2020_288w.promos_newyear_1{left:3%}
    .content .btn_2020_288w:last-child{left:67%}
    .hb_mask{display:none;position:fixed;left:0;top:0;z-index:10;width:100%;height:100%;background-color:rgba(0,0,0,0.85)}
    .hb_mask .blin{width:100%;max-width:747px;height:100%;max-height:752px;margin:0 auto 0;background-image:url(/images/hongbao/gold.png);background-size:100%;background-repeat:no-repeat;background-position:center;-o-animation:circle 10s linear infinite;-ms-animation:circle 10s linear infinite;-moz-animation:circle 10s linear infinite;-webkit-animation:circle 10s linear infinite;animation:circle 10s linear infinite}
    .hb_mask .caidai{position:absolute;left:0;top:0;z-index:1;width:100%;height:100%;background-image:url(/images/hongbao/dianzhui.png);-o-transform:scale(1.2);-ms-transform:scale(1.2);-moz-transform:scale(1.2);-webkit-transform:scale(1.2);transform:scale(1.2);background-size:100%}
    .hb_mask .winning{position:absolute;left:50%;top:50%;z-index:1;width:198px;height:265px;margin:-35% -24%;-webkit-transform:scale(0.1);transform:scale(0.1)}
    .reback{-o-animation:reback .5s linear forwards;-ms-animation:reback .5s linear forwards;-moz-animation:reback .5s linear forwards;-webkit-animation:reback .5s linear forwards;animation:reback .5s linear forwards}
    /* .winning .red-head{position:relative;top:-0.33333333rem;width:100%;height:4.46666667rem;background-image:url("/images/hongbao/top.png")} */
    .winning .red-body{position:relative;z-index:2;width:100%;height:100%;background-image:url(/images/hongbao/hb_on.png);background-repeat:no-repeat}
    .hb_mount{color:#d6261e !important;font-size:48px !important;font-weight:bold;padding:42px 12px !important;width:84%;text-align:center !important;}
    .promos_btn .hb_mount{display: none;padding: 2rem 0 !important;font-size: 2.5rem !important;}
    .hb_title{font-size:20px !important;text-align:center !important;color:#fcd639 !important;margin-top:20px}
    .promos_btn .hb_title{display:none;margin-top:10px;font-size: 1rem !important;}
    .winning .pull{-o-animation:card .5s linear forwards;-webkit-animation:card .5s linear forwards;animation:card .5s linear forwards}
    .hb_close{cursor:pointer;opacity:1;position:absolute;right:6%;top:20%;z-index:10;width:40px;height:40px;background-image:url(/images/hongbao/close.png);background-size:100%}
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
    .machine{width:100%;height:24rem;position:absolute;top:50%;left:50%;-webkit-transform:translate(-50%,-72%);transform:translate(-50%,-72%)}
    .rotate_box{width:60%;margin:0 auto;padding-top:4rem}
    .rotate_box dd{position:relative;width:24.8%;height:7rem;margin-right:9%;float:left;background:url(/images/hongbao/prize.png);background-size:cover}
    .rotate_box dd:last-child{margin-right:0}
    .rotate_btn_css,.zw_hh{width:3rem;overflow:hidden;position:absolute;right:.2%;bottom:17rem;transform-origin:center bottom}
    .zw_hh{z-index:1}
    .poiner{width:.88rem;position:absolute;right:.4rem;top:-1.2rem}
    .btn_box{width:10rem;height:4rem;position:absolute;left:50%;margin-left:-5rem;bottom:1rem}
    .content .btn_box a{transition:.3s;width:100%;height:100%;margin:0;background:url(/images/hongbao/yj_btn.png) center no-repeat;background-size:100%}
    .content .btn_box a:hover,.content .btn_box a:active{background:url(/images/hongbao/yj_btn_hover.png) center no-repeat;background-size:100%}
    .newyear_num{position:absolute;width:4rem;bottom:29%;color:#f6bd2f;font-size:3.5rem;font-weight:bold;text-align:center;right:22%}
    .machine .receiveAfter{z-index:2;display:none;transform:scale(0.1);position:absolute;width:10rem;height:14rem;background:url(/images/hongbao/hb1_on.png) center no-repeat;background-size:100%;top:2%;left:27.5%;color:#d6261e;font-size:1.5rem}
    .machine .receiveAfter .tip{text-align:center;padding-top:2.8rem}
    .machine .receiveAfter .tip .hb_mount{padding:0 !important;font-size:36px !important}
    .machine .new_zfy{width:25%;position:absolute;font-size:1rem;color:#fdc731;left:15%;top:5.6rem;text-align:center;z-index:1;transform:scale(.8)}
    .machine .new_zfy.new_zfy_1{left:35%}

    /* 元宵 */
    .machine_2020_yx{-webkit-transform:translate(-50%,-62%);transform:translate(-50%,-62%);height:22rem}
    .machine_2020_yx .rotate_box dd{margin-right:8%;background:url(/images/hongbao/prize_1.png)}
    .machine_2020_yx .newyear_num{bottom:27%;right:30%;font-size:2.8rem}
    .machine_2020_yx .new_zfy{top: 8.1rem;}
    /* 幸运转盘 */
    .lucky_input{display:none;padding:1rem 0;position:absolute;z-index:295;background:#fff;border-radius:5px;width:80%;left:50%;margin:115% -40% 0}
    .lucky_input>div{color:#000;line-height:40px}
    .lucky_input>div input{border:1px solid #ccc;line-height:28px;padding:0 5px}
    .luck-bottom a{display:inline-block;padding: 0 10px;height:30px;line-height:30px;color:#333;border:1px solid #dedede;margin:1rem 1rem 0}
    .luck-bottom a:first-child{border-color:#1E9FFF;background-color:#1E9FFF;color:#fff}
    .box-lucky{position:absolute;left:50%;margin:47% -9.5rem 0;width:19rem;height:19rem;background:url(/images/hongbao/lucky/prize_bg.png) center no-repeat;background-size:100%}
    .lucky-wrap{position:relative;width:100%;height:100%;background:url('/images/hongbao/lucky/prize_bg_sec.png') center no-repeat;background-size:90%;transform:rotate(-25deg);-webkit-transform:rotate(-25deg)}
    .lucky-wrap img{display:inline-block;width:2.5rem;height:2.5rem}
    .lucky-wrap span{display:inline-block;position:absolute;top:0;left:30%;width:8rem;height:50%;color:#fff;-webkit-transform-origin:50% 100%;transform-origin:50% 100%;text-align:center}
    .lucky-wrap span.lucky-span1{-webkit-transform:rotate(22.5deg);transform:rotate(22.5deg)}
    .lucky-wrap span.lucky-span2{-webkit-transform:rotate(75.5deg);transform:rotate(75.5deg)}
    .lucky-wrap span.lucky-span3{-webkit-transform:rotate(128.5deg);transform:rotate(128.5deg)}
    .lucky-wrap span.lucky-span4{-webkit-transform:rotate(180.5deg);transform:rotate(180.5deg)}
    .lucky-wrap span.lucky-span5{-webkit-transform:rotate(232.5deg);transform:rotate(232.5deg)}
    .lucky-wrap span.lucky-span6{-webkit-transform:rotate(283.5deg);transform:rotate(283.5deg)}
    .lucky-wrap span.lucky-span7{-webkit-transform:rotate(331.5deg);transform:rotate(331.5deg)}
    .lucky-wrap i{display:block;width:100%;height:2rem;font-style:normal;font-size:.8rem;line-height:2rem;margin:1.2rem 0 0;transform:scale(.9);-webkit-transform:scale(.9)}
    .lucky-wrap img{max-width:100%}
    .lucky-btn{position:absolute;left:50%;top:50%;text-indent:-999em;z-index:11;-webkit-transform:translate(-50%,-50%);transform:translate(-50%,-50%);width:7rem;height:7rem;background:url(/images/hongbao/lucky/prize_btn.png) center no-repeat;background-size:80%}
    .luckyM{display:none;position:absolute;width:70%;height:2.8rem;line-height:2.8rem;left:50%;background:#f79d07;margin:135% -35% 0;border-radius:50px;text-align:center;color:#fff;font-size:1.3rem;}

    /*优惠活动*/
    </style>
</head>
<body  class="dedede" >
<div id="container" class="content">
    <!-- 头部 -->
    <div class="header <?php if($tip){echo 'hide-cont';}?>">
    </div>
    <!-- 中间内容 -->
    <div class="content-center deposit">
        <main class="main promo">
            <!--  标签 -->
            <div class="promo_nav">
                <ul class="ProTab_nav css_flex">
                    <li class="on"  data-type="all">全部</li>
                    <?php foreach ($categoryList as $key => $category){?>
                        <li data-type="<?php echo $category['id'];?>"><?php echo $category['name'];?></li>
                    <?php }?>
                </ul>
            </div>

            <!-- 幸运转盘活动 -->
            <div class="mask"> </div>
            <div class="flex lucky_input">
                <div>
                    <span>手机号</span><input type="text" class="phoneNumber" minlength="11" maxlength="11" placeholder="请输入手机号码获取验证码">
                </div>
                <div>
                    <span>验证码</span><input type="text" class="yzmNumber" minlength="2" maxlength="8" placeholder="请输入收到的验证码">
                </div>
                <div class="luck-bottom">
                    <a href="javascript:;" class="btn-yzm" data-type="sure"> 获取验证码 </a>
                    <a href="javascript:;" data-type="cancel"> 取消 </a>
                </div>
            </div>

            <!-- 新年活动 领取红包 开始-->
            <div class="hb_mask">
                <div class="blin"></div>
                <div class="caidai"></div>
                <div class="winning">
                    <div class="red-head"></div>
                    <div class="red-body">
                        <p class="hb_mount">0</p>
                        <p class="hb_title"><?php echo COMPANY_NAME;?></p>
                    </div>
                    <!--<div class="hb_card">
                        <a href="" target="_self" class="win"></a>
                    </div>
                    <a target="_self" class="btn"></a>-->
                </div>
                <span class="hb_close"></span>
            </div>
            <!-- 领取红包 结束-->
        <ul class="ProTab_con">
            <li class="ProTab_con_1" style="display:block">
                <?php
                foreach ($lists as $key => $activity){
                        if($activity['flag']=='2020_888w'){
                                $newyear2020_888w = true;
                        }
                        if($activity['flag']=='best_lucky'){
                            $best_lucky = true;
                        }
                    ?>


                <div class="material-card promos_<?php echo $activity['type']?>">
                    <div class="promotions_title_box promos_id_<?php echo $activity['id']?>" id="promos_id_<?php echo $activity['id']?>">
                        <img src="<?php echo $activity['imgurl'];?>">
                        <div class="promotions_title"><?php echo $activity['title']?></div>
                    </div>
                    <div class="material-card-content">
                        <div class="line"></div>
                        <?php
                        if($activity['type'] == 7){
                            $flag = $activity['flag'];
                            switch ($flag){
                                case '2020_288w': // 新年活动 0086 活动一
                                    echo '<div class="newYearFirst">
                                    <div class="newyear_btn"> 
                                        <a class="promos_btn pro_btn promos_newyear_1 btn_'.$flag.'" href="javascript:;" data-api="'.$activity['ajaxurl'].'" data-type="'.$flag.'">
                                            <p class="hb_mount">0</p>
                                            <p class="hb_title"> '.COMPANY_NAME.'</p>   
                                        </a>
                                        <a class="promos_btn pro_btn promos_newyear_2 btn_'.$flag.'" href="javascript:;" data-api="'.$activity['ajaxurl'].'" data-type="'.$flag.'">
                                            <p class="hb_mount">0</p>
                                            <p class="hb_title"> '.COMPANY_NAME.'</p>   
                                        </a>
                                        <a class="promos_btn pro_btn promos_newyear_3 btn_'.$flag.'" href="javascript:;" data-api="'.$activity['ajaxurl'].'" data-type="'.$flag.'">
                                            <p class="hb_mount">0</p>
                                            <p class="hb_title"> '.COMPANY_NAME.'</p>   
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
                                <span class="zw_hh"><img src="/images/hongbao/rocker.png" alt="" style="visibility: hidden"></span> <!-- 占位 -->
                                <a class="rotate_btn rotate_btn_css" >
                                    <img src="/images/hongbao/rocker.png" alt="" style="visibility: hidden">
                                </a>
                                <div class="newyear_num"> 0 </div>
                                <div class="btn_box">
                                   <a class="promos_btn pro_btn btn_'.$flag.'" href="javascript:;" data-api="'.$activity['ajaxurl'].'" data-type="'.$flag.'"></a>
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
                                    echo '<a class="promos_btn pro_btn btn_'.$flag.'" href="javascript:;" data-api="'.$activity['ajaxurl'].'" data-type="'.$flag.'"></a>';
                            }


                        }

                        ?>
                        <img src="<?php echo $activity['contenturl'];?>">
                    </div>
                </div>
                <?php }?>
            </li>
        </ul>
        </main>
    </div>
    <!-- 底部footer -->
    <div id="footer" class="<?php if($tip){echo 'hide-cont';}?>">
    </div>
</div>
<script type="text/javascript" src="../../js/zepto.min.js"></script>
<script type="text/javascript" src="../../js/animate.js"></script>
<script type="text/javascript" src="../../js/zepto.animate.alias.js"></script>
<?php
    if($best_lucky){
        echo '<script type="text/javascript" src="../../js/anime.min.js"></script>
            <script type="text/javascript" src="../../js/bestLucky.js"></script>';
    }
?>

<script type="text/javascript" src="../../js/mejs.js"></script>
<script type="text/javascript" src="../../js/main.js?v=<?php echo AUTOVER; ?>"></script>


<script type="text/javascript">
    var uid = '<?php echo $uid?>' ;
    var usermon = getCookieAction('member_money') ; // 获取信息cookie
    var userid = '<?php echo $userid;?>' ;
    var UserName = '<?php echo $UserName;?>' ;
    var userAgents='<?php echo $Agents;?>';
    var platfrom = '<?php echo $platfrom;?>' ;

    var newYearBeginTime = '<?php echo $newYearBeginTime;?>';
    var newYearEndTime  = '<?php echo $newYearEndTime;?>';
    var curtime = '<?php echo $curtime;?>';
    var newyear2020_888w = '<?php echo $newyear2020_888w;?>';
    var best_lucky = '<?php echo $best_lucky;?>';

    if(best_lucky){
        var ajaxurl ='/api/best_lucky.php';
        var lucky_par = {
            action:'check'
        };
        var $mask = $('.mask');
        var $luckyBox = $('.content').find('.lucky-wrap');
        var $luckyBtn = $('.content').find('.lucky-btn');
        var $luckyM = $('.content').find('.luckyM');
        var $luckyinput = $('.content').find('.lucky_input');
        var $luckbottom = $luckyinput.find('.luck-bottom');

        var Lucky = Turntable.create();
        getBestLucky(lucky_par);
        getLuckyGift();
    }

    if(newyear2020_888w){
        getNewYearTime();
    }

    autoGetPromos();
    setLoginHeaderAction('优惠活动','','',usermon,uid) ;
    setFooterAction(uid) ; // 在 addServerUrl 前调用
    ProTab_Js();
    unfoldPost();
    goToPromosDetail();
    // 优惠tab
    function ProTab_Js(){
        $('.ProTab_nav').on('click','li',function () {
            var type = $(this).attr('data-type');

            $(this).addClass('on').siblings().removeClass('on');
            if(type == 'all'){ // 全部
                $('.material-card').fadeIn(300) ;
            }else{
                $('.material-card').hide() ;
                $(".promos_"+type).fadeIn(300).siblings();
            }

        })
    }

    function unfoldPost() {
        var actionButton = $(".promotions_title_box");
        actionButton.on("click", function(e) {
            e.preventDefault();
            $(this).closest(".material-card").toggleClass("triggered");
            $(".no_count").fadeOut(350);//關閉優惠也關閉不计算名单
        });
    }
    // 跳转到对应的优惠活动详情
    function goToPromosDetail(){
        var key = '<?php echo $prokeys;?>';
        if(key){
            $('.promos_id_'+key).click();
        }
    }
    // 不计算名单
    $(document).on("click", '.no_count_toggle', function(){
        $(".no_count").toggle("fade", 350);
    });

    // 自动领取
    function autoGetPromos() {
        // 自动领取
        var proflage = false ;
        var $hb_mask = $('.hb_mask'); // 新年活动一
        var $winning = $('.winning'); // 新年活动一
        var $rotate_btn = $('.rotate_btn');// 摇杆按钮
        var $receiveAfter_act = $('.receiveAfter_act');// 领取后显示

        $('.hb_close').on('click',function () { // 关闭新年红包
            $hb_mask.hide();
            $winning.removeClass('reback');
        });

        $('.pro_btn').on('click',function () {

            var type = $(this).attr('data-type');
            var indexNum = Number($(this).index())+1;

            var postData = {
                type_flag: type ,
                uid: uid ,
                user_id: userid ,
                username: UserName ,
                platfrom: platfrom ,
                action:'receive_red_envelope'
            }

            if(!uid){
                setPublicPop("请先登录！");
                return false ;
            }
            if(userAgents=='demoguest'){
                setPublicPop("请注册真实用户！");
                return false ;
            }

            if(type =='2020_888w' || type =='2020_yx'){ // 新年活动二摇奖
                var g_num = Number($('.newyear_num').text());
                if(curtime < newYearBeginTime || curtime > newYearEndTime){
                    if(type =='2020_888w'){
                        setPublicPop('请于北京时间1月25号中午12:00-1月30日11:59期间领取红包!');
                    }else{
                        setPublicPop('请于美东时间02月11日期间领取红包!');
                    }

                    return false;
                }
                if(g_num < 1){
                    setPublicPop('可领次数不足');
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
            var url = $(this).data('api') ;
            proflage = true ;
            $.ajax({
                type: 'POST',
                url: url,
                data: postData,
                dataType: 'json',
                success:function(res){
                    if(res){
                        proflage = false ;
                        if(type == '2020_288w'){
                            if(res.status=='200'){ // 领取成功
                                $hb_mask.show();
                                $winning.addClass('reback');
                                $('.promos_newyear_'+indexNum).addClass('active');
                                $('.promos_newyear_'+indexNum).find('.hb_title').show();
                                $('.promos_newyear_'+indexNum).find('.hb_mount').show();
                                $('.hb_mount').text(res.data.giftGold);
                            }else{
                                setPublicPop(res.describe);
                            }
                        }else{
                            if(res.info){
                                setPublicPop(res.info);
                            }else{
                                setPublicPop(res.describe);
                            }
                        }

                    }
                },
                error:function(){
                    proflage = false ;
                    setPublicPop(config.errormsg) ;
                }
            });
        });
    }

        // 获取新年活动二红包次数
    function getNewYearTime(type){
            if(!uid){
                return false;
            }
            var receiveflage = false ;
            var $rotate_btn = $('.rotate_btn');// 摇杆按钮
            var $receiveAfter_act = $('.receiveAfter_act');// 领取后显示

            var url = '/api/newyear2020_888w.php';
            var postData = {
                user_id: userid ,
                username: UserName ,
                action:'getGrabTimes', // 查询可领取次数
                platfrom:'hg<?php echo TPL_FILE_NAME;?>'
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
                            setPublicPop(res.describe);
                        }

                    }

                },
                error:function(){
                    receiveflage = false;
                    setPublicPop('网络异常');
                }
            });

        }

    // 获取幸运大转盘活动配置
    function getBestLucky(params){
        var luckUrl = ajaxurl;
        if(!params.mem_yzm && params.action=='draw'){ // 获取验证码
            luckUrl ='/api/message_xsend.php';
        }
        params.user_id = userid;
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
                                setPublicPop(res.describe);
                            }
                        }else{
                            if(res.status=='200'){ // 发送验证码成功
                                $luckbottom.find('.btn-yzm').text('确定');
                            }
                            setPublicPop(res.describe);
                        }

                    }

                }

            },
            error:function(){

            }
        });
    }

    // 幸运大转盘红包领取
    function getLuckyGift(){
        $luckyBtn.on('click', function(){
            var lucktip = $(this).attr('data-luckyTip');
            if(!uid){
                setPublicPop('请先登录');
                return false;
            }
            if(lucktip==0){
                setPublicPop('当前不符合抽奖条件');
                return false;
            }else{
                $mask.show();
                $luckyinput.show();

                $luckyinput.off().on('click','a',function () {
                    var type = $(this).attr('data-type');
                    var phone = $('.phoneNumber').val();
                    var yzm = $('.yzmNumber').val();
                    var btnTxt = $luckbottom.find('.btn-yzm').text();
                    var luckData ={
                        action:'draw',
                        mem_phone:phone,
                        mem_yzm:yzm
                    };
                    // console.log(type)
                    if(type=='sure'){ // 确定
                        if(!phone){
                            setPublicPop('请输入手机号')
                            return false;
                        }
                        if(btnTxt=='确定' && !yzm){
                            setPublicPop('请输入验证码');
                            return false;
                        }
                        if(yzm){ // 输入验证码后请求数据
                            $mask.hide();
                            $luckyinput.hide();
                        }
                        getBestLucky(luckData);
                    }else{ // 取消
                        $mask.hide();
                        $luckyinput.hide();
                    }

                })


            }

        });
    }
    // 幸运抽奖动画
    function luckyAnime(num){
        // var num = Math.floor(Math.random() * 7); // 七个随机
        //console.log(num)
        Lucky.start(num, function(index){
            var luckyText = $luckyBox.find('span').eq(index).find('i').text();
            setPublicPop(luckyText);
            $luckyM.show();
            // console.log('index', index, 'lucky-span', 'lucky-span'+(index+1));
        });
    }

    // 发送验证码
    function luckyGetYzm() {

    }


</script>
</body>
</html>