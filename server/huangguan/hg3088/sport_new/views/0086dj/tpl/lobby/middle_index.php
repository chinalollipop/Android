<?php
session_start();
$tplNmaeSession = $_SESSION['TPL_NAME_SESSION'];
$uid = $_SESSION['Oid']; // 判断是否已登录

?>
<style>

</style>

<?php
    if(!$uid){ // 未登录
?>


<!-- 轮播 -->
<div class="swiper-container" >
    <div class="swiper-wrapper to_memberlogin">
        <div class="swiper-slide" >
          <a href="javascript:;" >
              <img src="<?php echo $tplNmaeSession;?>images/banner/banner_1.jpg" class="swiper-lazy" alt="">
            </a>
         </div>
        <div class="swiper-slide" >
            <a href="javascript:;" >
                <img src="<?php echo $tplNmaeSession;?>images/banner/banner_2.jpg" class="swiper-lazy" alt="">
            </a>
        </div>
        <div class="swiper-slide" >
            <a href="javascript:;" >
                <img src="<?php echo $tplNmaeSession;?>images/banner/banner_3.jpg" class="swiper-lazy" alt="">
            </a>
        </div>

    </div>
  <!--  <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>-->
</div>

        <?php
    }
?>

<script type="text/javascript">
    $(function () {
        if(!uid){
            indexCommonObj.bannerSwiper();
        }else{ // 默认雷火电竞
            $('.lhdj_btn').click();
        }

    })


    function cms_hlHover(no){
        $("#cms_hlbox"+no).css({'transform':'scale(0.9)','transition':'.5s'});

       //  $("#cms_hlbox"+no).delay(200).animate({
       //      height: '90%',
       //      width: '90%'
       //  },500);

        $("#cms_hlt"+no+" .cms_hlt1").stop().animate({
            fontSize : '20px'
        },500);

        $("#cms_hlt"+no+" .cms_hlt2").stop().animate({
            fontSize : '14px'
        },500);

        $("#cms_hlt"+no+" .cms_hl_moveline").stop().animate({
            top : '53px'
        },500);
    }
    function cms_hlOut(no){
        $("#cms_hlbox"+no).css({'transform':'unset','transition':'.5s'});

        // $("#cms_hlbox"+no).animate({
        //     height: '100%',
        //     width: '100%'
        // },500;

        $("#cms_hlt"+no+" .cms_hlt1").stop().animate({
            fontSize : '24px'
        },500);

        $("#cms_hlt"+no+" .cms_hlt2").stop().animate({
            fontSize : '18px'
        },500);

        $("#cms_hlt"+no+" .cms_hl_moveline").stop().animate({
            top : '33px'
        },500);
    }

</script>