<?php
session_start();
include "../../../../app/member/include/config.inc.php";
$redisObj = new Ciredis();
$uid = $_SESSION['Oid']; // 判断是否已登录
$yesday = date('Y-m-d',strtotime('-1 day'));


?>
<style>
    .banner {margin-top: -13px;}
    .shouye_main{width:1002px;margin:0 auto;background:rgb(61,61,61)}
    .x_flex{display:flex;justify-content:space-around;flex-wrap:wrap}
    .liutu>a{width:320px;height:200px;display:inline-block;margin:5px}
    .fw{background:transparent}
    .liutu3{margin:12px auto 0;border:1px solid #2A2A2A;box-sizing:border-box;padding:19px 0 13px;background:#111;width:976px;text-align:center}
    .liutu3>a{width:221px;height:319px;display:inline-block;margin:0 7px}
    .za1{background:url(<?php echo TPL_NAME;?>images/index/za1.png) 50% 0 no-repeat}
    .za2{background:url(<?php echo TPL_NAME;?>images/index/za2.png) 50% 0 no-repeat}
    .za3{background:url(<?php echo TPL_NAME;?>images/index/za3.png) 50% 0 no-repeat}
    .za4{background:url(<?php echo TPL_NAME;?>images/index/za4.png) 50% 0 no-repeat}
    .za5{background:url(<?php echo TPL_NAME;?>images/index/za5.png) 50% 0 no-repeat}
    .za1:hover{background:url(<?php echo TPL_NAME;?>images/index/za1.png) 50% -319px no-repeat}
    .za2:hover{background:url(<?php echo TPL_NAME;?>images/index/za2.png) 50% -319px no-repeat}
    .za3:hover{background:url(<?php echo TPL_NAME;?>images/index/za3.png) 50% -319px no-repeat}
    .za4:hover{background:url(<?php echo TPL_NAME;?>images/index/za4.png) 50% -319px no-repeat}
    .za5:hover{background:url(<?php echo TPL_NAME;?>images/index/za5.png) 50% -319px no-repeat}
    .support{display:block;font-size:0;height:96px;margin-bottom:10px;padding-top:34px;background:url(<?php echo TPL_NAME;?>images/index/zf_bg.jpg) no-repeat center;margin-top:10px}
    .support li{position:relative;display:inline-block;width:200px;height: 34px;vertical-align:top}
    .support li.app a{background-image:url(<?php echo TPL_NAME;?>images/app.png)}
    .support li.ali a{background-image:url(<?php echo TPL_NAME;?>images/ali.png)}
    .support li.wechat a{background-image:url(<?php echo TPL_NAME;?>images/wechat.png)}
    .support li.qq a{background-image:url(<?php echo TPL_NAME;?>images/qq.png)}
    .support li.fast a{background-image:url(<?php echo TPL_NAME;?>images/fast.png)}
    .support li+li:before{content:'';position:absolute;width:1px;height:34px;background:url(<?php echo TPL_NAME;?>images/line.png) no-repeat center;background-size:cover}
    .support li a{display:block;height:100%;padding-left:60px;color:#dfd27f;font-size:14px;line-height:17px;text-decoration:none;background:no-repeat 18px center}
    .support li a span{display:block;color:#666;font-size:12px}
    .support li a span:hover{color:#dfd27f}

    .banner_base{text-align: center;}
    .banner_base a img{margin: 0 auto;width: 100px;animation: weuiLoading 1s steps(12) infinite;}
    @keyframes weuiLoading {
        0% {transform: rotate(0deg)}
        to {transform: rotate(1turn)}
    }

</style>

<!-- 轮播 -->
<div class="banner">
    <div class="jBanners banner">
  <div class="banner_l"></div>
  <div class="banner_r"></div>

  <div class="swiper-container" >
    <div class="swiper-wrapper">
        <div class="banner_base swiper-slide" >
            <a href="javascript:;" >
                <img src="/images/loading.svg">
            </a>
        </div>
    </div>
      <div class="swiper-pagination"></div>
  </div>
        
 </div>
</div>
<div class="ling">
    <div class="lunleft">
        <marquee onmouseout="this.start();" onmouseover="this.stop();" direction="left" scrolldelay="150" scrollamount="5">
            <?php echo $_SESSION['memberNotice']; ?>
        </marquee>
    </div>
</div>

<div class="fw w_1200">
    <div class="shouye_main">

        <div class="liutu3 x_flex">
            <a class="za4" rel="nofollow" href="javascript:;" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/login.php?uid=<?php echo $uid;?>')"></a>
            <a class="za1 to_lotterys_third" data-to="1" rel="nofollow" href="javascript:;"></a>
            <a class="za2 to_games" rel="nofollow" href="javascript:;"></a>
            <a class="za3 to_sports" data-rtype="r" data-showtype="today" rel="nofollow" href="javascript:;"></a>

        </div>
        <ul class="support">
            <li class="app"><a href="<?php echo getSysConfig('download_app_page');?>" target="_blank">APP下载<span>APP Download</span></a></li>
            <li class="ali"><a href="javascript:;" class="to_deposit">支付宝支付<span>Pay with Ali-Pay</span></a></li>
            <li class="wechat"><a href="javascript:;" class="to_deposit">微信支付<span>WeChat payment</span></a></li>
            <li class="qq"><a href="javascript:;" class="to_deposit">QQ钱包<span>QQ Purse payment</span></a></li>
            <li class="fast"><a href="javascript:;" class="to_deposit">快速充值中心<span>Fast recharge center</span></a></li>
        </ul>
        <div class="liutu x_flex">
            <a class="to_lives" rel="nofollow" href="javascript:;"><img src="<?php echo TPL_NAME;?>images/index/index_1.jpg?v=1" alt=""></a>
            <a class="to_sports" data-rtype="r" data-showtype="today" rel="nofollow" href="javascript:;"><img src="<?php echo TPL_NAME;?>images/index/index_2.jpg?v=1" alt=""></a>
            <a class="to_dianjing" rel="nofollow" href="javascript:;"><img src="<?php echo TPL_NAME;?>images/index/index_3.jpg?v=1" alt=""></a>
            <a class="to_lotterys" rel="nofollow" href="javascript:;"><img src="<?php echo TPL_NAME;?>images/index/index_4.jpg?v=1" alt=""></a>
            <a class="to_chess" rel="nofollow" href="javascript:;"><img src="<?php echo TPL_NAME;?>images/index/index_5.jpg?v=1" alt=""></a>
            <a class="to_promos" rel="nofollow" href="javascript:;"><img src="<?php echo TPL_NAME;?>images/index/index_6.jpg?v=1" alt=""></a>
        </div>
    </div>
</div>


<script type="text/javascript">
 $(function () {
     
      indexCommonObj.indexBannerAction();
     showNewsDetail();

     // 显示新闻详情
     function showNewsDetail() {
         $(document).off('click','.show_news_content').on('click','.show_news_content',function () { // 显示新闻
             var conId = $(this).attr('data-id');
             indexCommonObj.getNewsRecommend('content',conId);
         });
     }

 })



</script>