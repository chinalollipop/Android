<?php
session_start();
$tplNmaeSession = $_SESSION['TPL_NAME_SESSION'];

?>
<style>
    .banner_base{text-align: center;}
    .banner_base a img{margin: 0 auto;width: 100px;animation: weuiLoading 1s steps(12) infinite;}
    @keyframes weuiLoading {
        0% {transform: rotate(0deg)}
        to {transform: rotate(1turn)}
    }
</style>

<!-- 轮播 -->
<div class="swiper-container" >
    <div class="swiper-wrapper">
        <div class="banner_base swiper-slide" >
            <a href="javascript:;" >
                <img src="/images/loading.svg">
            </a>
        </div>
    </div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
</div>
<div class="cms_home_highlight">
    <div class="cms_hl_col">
        <a href="javascript:;" data-keys="sport"> <!-- class="to_lives_upgraded" -->
            <div class="cms_hlredbg">
                <div class="cms_active_hl" onmouseover="cms_hlHover(1)" onmouseout="cms_hlOut(1)">
                    <img src="<?php echo $tplNmaeSession;?>images/hom_hl_cover.png" width="100%">
                </div>
                <div class="cms_hl_box" id="cms_hlbox1" style="background-image: url(<?php  echo $tplNmaeSession;?>images/a3.jpg); ">
                    <div class="cms_hl_txt" id="cms_hlt1">
                        <div class="cms_hlt1" >体育升级</div>
                        <div class="cms_hl_moveline" ></div>
                        <div class="cms_hlt2" >独家晋级彩金，月俸禄最高119,999</div>
                    </div>

                </div>
            </div>
        </a>
    </div>
    <div class="cms_hl_col">
        <a href="javascript:;" data-keys="live"> <!-- class="to_lives_upgraded" -->
            <div class="cms_hlredbg">
                <div class="cms_active_hl" onmouseover="cms_hlHover(2)" onmouseout="cms_hlOut(2)">
                    <img src="<?php  echo $tplNmaeSession;?>images/hom_hl_cover.png" width="100%">
                </div>
                <div class="cms_hl_box" id="cms_hlbox2" style="background-image: url(<?php echo $tplNmaeSession;?>images/a4.jpg); ">
                    <div class="cms_hl_txt" id="cms_hlt2">
                        <div class="cms_hlt1" >真人升级</div>
                        <div class="cms_hl_moveline" ></div>
                        <div class="cms_hlt2" >独家晋级彩金，月俸禄最高80000</div>
                    </div>

                </div>
            </div>
        </a>
    </div>
    <div class="cms_hl_col">
        <a href="javascript:;" class="to_games">
            <div class="cms_hlredbg">
                <div class="cms_active_hl" onmouseover="cms_hlHover(3)" onmouseout="cms_hlOut(3)">
                    <img src="<?php echo $tplNmaeSession;?>images/hom_hl_cover.png" width="100%">
                </div>
                <div class="cms_hl_box" id="cms_hlbox3" style="background-image: url(<?php echo $tplNmaeSession;?>images/a2.jpg); ">
                    <div class="cms_hl_txt" id="cms_hlt3">
                        <div class="cms_hlt1" >电子游戏</div>
                        <div class="cms_hl_moveline" ></div>
                        <div class="cms_hlt2" >每周返水高达2.88%</div>
                    </div>

                </div>
            </div>
        </a>
    </div>

    <div class="cms_hl_col">
        <a href="javascript:;" class="to_lotterys">
            <div class="cms_hlredbg">
                <div class="cms_active_hl" onmouseover="cms_hlHover(4)" onmouseout="cms_hlOut(4)">
                    <img src="<?php echo $tplNmaeSession;?>images/hom_hl_cover.png" width="100%">
                </div>
                <div class="cms_hl_box" id="cms_hlbox4" style="background-image:url(<?php echo $tplNmaeSession;?>images/a1.jpg);">
                    <div class="cms_hl_txt" id="cms_hlt4">
                        <div class="cms_hlt1" >彩票游戏</div>
                        <div class="cms_hl_moveline"></div>
                        <div class="cms_hlt2" >六合彩 超高赔率</div>
                    </div>

                </div>
            </div>
        </a>
    </div>

</div>



<script type="text/javascript">
    $(function () {

        indexCommonObj.indexBannerAction();
        indexGameHeight(0.686) ;

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
