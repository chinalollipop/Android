<?php
session_start();

include "../../../../app/member/include/config.inc.php";

//  单页面维护功能
checkMaintain('thirdcp');

$cpUrl = $_SESSION['LotteryUrl'];
$uid = $_SESSION['Oid']; // 判断是否已登录
// to_lotterys_third

?>
<style>
    .game_banner{height:295px;background:url(<?php echo TPL_NAME;?>images/lottery/lottery_banner.png) center no-repeat}
    .lottery_mainBody ul,.lottery_list{position: relative;overflow: hidden;}
    .lottery_mainBody ul li{float:left;position:relative;cursor:pointer;}
    .lottery_mainBody{height:639px;background:url(<?php echo TPL_NAME;?>images/lottery/lottery_con_bg.png) center no-repeat}
    .lottery_mainBody .lottery_content_all{background:#161616;border:1px solid #595959;margin:10px 0;padding:30px 35px;overflow:hidden}
    .lottery_mainBody .nav_list li{width:321px;height:408px;margin-right:60px}
    .lottery_mainBody .nav_list li:hover:before{transition:.3s;display:inline-block;content:'';width:321px;height:406px;border:1px solid #d4b15a}
    .lottery_mainBody .nav_list li:nth-child(3n){margin-right:0}
    .lottery_mainBody .nav_list li.live_1{background:url(<?php echo TPL_NAME;?>images/lottery/lottery_xy.png) top center no-repeat}
    .lottery_mainBody .nav_list li.live_2{background:url(<?php echo TPL_NAME;?>images/lottery/lottery_gf.png) top center no-repeat}
    .lottery_mainBody .nav_list li.live_3{background:url(<?php echo TPL_NAME;?>images/lottery/lottery_qd.png) top center no-repeat}
    .lottery_mainBody .nav_list li .img{width: 128px;height: 30px;background: url(<?php echo TPL_NAME;?>images/lottery/lo_btn.png) center no-repeat;position: absolute;bottom: 20px;left: 50%;margin-left: -64px;}
    .lottery_mainBody .nav_list li:hover .img{background: url(<?php echo TPL_NAME;?>images/lottery/lo_btn_active.png) center no-repeat}
    .lottery_mainBody .nav_list li.live_3 .img{background: url(<?php echo TPL_NAME;?>images/lottery/lo_btn_not.png) center no-repeat;}
    .lottery_mainBody .lottery_list{margin-top:30px;height:100px}
    .lottery_mainBody .lottery_list span{display:inline-block;width:46px;height:49px;position:absolute;top:45px}
    .lottery_mainBody .lottery_list .left_icon{background:url(<?php echo TPL_NAME;?>images/lottery/left_lottery.png) center no-repeat}
    .lottery_mainBody .lottery_list .right_icon{background:url(<?php echo TPL_NAME;?>images/lottery/right_lottery.png) center no-repeat;right:0}
    .lottery_mainBody .lottery_list .list_content_all{width:944px;margin:0 auto;padding-left: 15px;overflow:hidden}
    .lottery_mainBody .lottery_list .list_content img{width:90px;cursor:pointer}

</style>
<div class="game_banner">
    <div class="noticeContent">
       <div class="w_1200">
           <span></span>
           <marquee behavior="" direction="">
               <?php echo $_SESSION['memberNotice']; ?>
           </marquee>
       </div>
    </div>

</div>

<div class="mainBody lottery_mainBody">
    <div class="clearfix w_1160">
        <div class="lottery_content_all">
            <ul class="nav_list">
                <li class="live_1" data-type="1">
                    <div class="to_lotterys_third img" data-to="1"></div>
                </li>
                <li class="live_2" data-type="0">
                    <div class="to_lotterys_third img"></div>
                </li>
                <li class="live_3" >
                    <div class="img"></div>
                </li>
            </ul>

            <!-- 彩种列表 -->
            <div class="lottery_list">
                <span class="swiper-button-prev left_icon"></span>
                <span class="swiper-button-next right_icon"></span>
                <div class="list_content_all list-swiper-container">
                    <div class="swiper-wrapper list_content">

                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<script type="text/javascript">
    $(function () {
        indexCommonObj.bannerSwiper();

        var gameSwiper = ''; // 彩种轮播
        // 信用盘
        var xy_list = [
            {name:'北京赛车',icon:'bjpk105fc',gameid:'76'},
            {name:'极速赛车',icon:'gwpk10',gameid:'51'},
            {name:'五分彩',icon:'gw5fc',gameid:'76'},
            {name:'幸运飞艇',icon:'xyft',gameid:'55'},
            {name:'重庆时时彩',icon:'xcqssc',gameid:'7'},
            {name:'香港六合彩',icon:'xglhc',gameid:'70'},
            {name:'五分快三',icon:'jsk35fc',gameid:'73'},
            {name:'三分快三',icon:'jsk33fc',gameid:'74'},
            {name:'三分彩',icon:'gw3fc',gameid:'5'},
            {name:'北京PK10',icon:'bjpk10',gameid:'50'},
        ];
        // 官方
        var gf_list = [
            {name:'北京赛车',icon:'bjpk105fc',gameid:'bjpk105fc'},
            {name:'极速赛车',icon:'gwpk10',gameid:'gwpk10'},
            {name:'重庆时时彩',icon:'xcqssc',gameid:'xcqssc'},
            {name:'五分彩',icon:'gw5fc',gameid:'gw5fc'},
            {name:'幸运飞艇',icon:'xyft',gameid:'xyft'},
            {name:'五分快三',icon:'jsk35fc',gameid:'jsk35fc'},
            {name:'三分快三',icon:'jsk33fc',gameid:'jsk33fc'},
            {name:'欢乐生肖',icon:'cqssc',gameid:'cqssc'},
            {name:'三分彩',icon:'gw3fc',gameid:'gw3fc'},
            {name:'北京PK10',icon:'bjpk10',gameid:'bjpk10'},
        ];

        // 彩种渲染,type 1 为信用，0 官方
        function getLotteryList(type) {
            var $list_content = $('.list_content');
            var str = '';
            var cur_list = xy_list;
            if(type==0){
                cur_list = gf_list;
            }
           // console.log(cur_list)
            for(var i=0;i<cur_list.length;i++){
                str += '<div class="swiper-slide to_lotterys_third" data-to="'+ type +'" data-gametype="'+ cur_list[i].gameid +'"> <img src="<?php echo TPL_NAME;?>images/lottery/icon/'+ cur_list[i].icon +'.png"></div>';
            }
            $list_content.html(str);
            //console.log(gameSwiper)
            if(!gameSwiper){
                gameSwiper = new Swiper('.list-swiper-container',{
                    autoplay : 1000, // 自动滚动
                    slidesPerView : 8,
                    spaceBetween : 15, // 图片间隔
                    speed:500,
                    loop : true ,
                    prevButton:'.swiper-button-prev',
                    nextButton:'.swiper-button-next',
                    autoplayDisableOnInteraction : false, // 点击切换后是否自动播放 (默认true 不播放)
                    //spaceBetween : '10%',按container的百分比
                })
            }else{
                gameSwiper.slideTo(0);
                gameSwiper.update();
                gameSwiper.reLoop();
            }
        }
        // 信用官方彩种选择
        function chooseGameType(){
            $('.nav_list li').on('click',function () {
                var type = $(this).attr('data-type');
                if(type){
                    getLotteryList(type)
                }
            })
        }
        chooseGameType();
        getLotteryList(1); // 默认信用

    })
</script>