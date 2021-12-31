<?php
session_start();

$uid = $_SESSION['Oid'];
$tplNmaeSession = $_SESSION['TPL_NAME_SESSION'];
$kytesturl = 'http://play.ky206.com/jump.do' ; // 开元试玩链接
$lytesturl = $_SESSION['LYTEST_PLAY_SESSION']; // 乐游试玩链接
$testuid = '3e3d444a6054eae7c22cra8' ;

?>
<style>
    /* 棋牌游戏 */
    .Card{box-sizing: border-box;-webkit-box-sizing: border-box;padding:30px 110px;width:100%;overflow:hidden;background:url(<?php echo $tplNmaeSession;?>images/chess/chess_bg.jpg) no-repeat;background-size:100% auto}
    .Card .bg_card{text-align:center;padding:20px 0}
    .Card .bg_card .bg_card_bg{width:310px;height:510px;line-height:510px;overflow:hidden;display:inline-block;background-size:auto 100%;background-position:50%;background-repeat:no-repeat;position:relative}
    .Card .bg_card .bg_card_bg .bg_card_btn{position:absolute;top:0;left:0;height:510px;width:310px;display:none;text-align:center}
    .Card .bg_card .bg_card_bg .bg_card_btn img{height:100%!important}
    .Card .bg_card .bg_card_bg .bg_card_btn .open_game{position:absolute;z-index:1;left:108px;bottom:233px}
    .Card .bg_card .bg_card_bg:hover .bg_card_btn{display:inline-block}
    .Card .bg_card .bg_card_bg img{height:100%}
    .el-button--primary {color: #FFF;background-color: #FEA219;border-color: #FEA219;padding: 10px 20px;}
</style>
<div class="Card router_view_mian">
    <div class="el-row">
        <div class="el-col el-col-6">
            <div class="bg_card">
                <div class="bg_card_bg">
                    <img src="<?php echo $tplNmaeSession;?>images/chess/ky.png"alt="">
                    <div class="bg_card_btn">
                        <img src="<?php echo $tplNmaeSession;?>images/chess/ky_hover.png"alt="">
                        <button type="button" class="el-button open_game el-button--primary el-button--medium is-round" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/ky/index.php?uid=<?php echo $uid;?>')">
                            <span>进入大厅</span>
                        </button>
                    </div>
                </div>

            </div>
        </div>
        <div class="el-col el-col-6">
            <div class="bg_card">
                <div class="bg_card_bg">
                    <img src="<?php echo $tplNmaeSession;?>images/chess/ly.png" alt="">
                    <div class="bg_card_btn">
                        <img src="<?php echo $tplNmaeSession;?>images/chess/ly_hover.png" alt="">
                        <button type="button" class="el-button open_game el-button--primary el-button--medium is-round" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/lyqp/index.php?uid=<?php echo $uid;?>')">
                            <span>进入大厅</span>
                        </button>
                    </div>
                </div>

            </div>
        </div>

        <div class="el-col el-col-6">
            <div class="bg_card">
                <div class="bg_card_bg">
                    <img src="<?php echo $tplNmaeSession;?>images/chess/slw_hover.png" alt="">
                    <div class="bg_card_btn">
                        <img src="<?php echo $tplNmaeSession;?>images/chess/slw_hover.png" alt="">
                        <button type="button" class="el-button open_game el-button--primary el-button--medium is-round" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/vgqp/index.php?uid=<?php echo $uid;?>')" >
                            <span>进入大厅</span>
                        </button>
                    </div>
                </div>

            </div>
        </div>
        <div class="el-col el-col-6">
            <div class="bg_card">
                <div class="bg_card_bg">
                    <img src="<?php echo $tplNmaeSession;?>images/chess/klqp_hover.png" alt="">
                    <div class="bg_card_btn">
                        <img src="<?php echo $tplNmaeSession;?>images/chess/klqp_hover.png" alt="">
                        <button type="button" class="el-button open_game el-button--primary el-button--medium is-round" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/klqp/index.php?uid=<?php echo $uid;?>')" >
                            <span>进入大厅</span>
                        </button>
                    </div>
                </div>

            </div>
        </div>
  
    </div>

</div>


<script type="text/javascript">
    $(function () {
        chessBanner();

        // 轮播
        function chessBanner() {
            var chessSwiper = new Swiper(".swiper-container",{
                autoplay : 500000,
                loop:true,
                effect: 'fade',
                prevButton:'.swiper-button-prev',
                nextButton:'.swiper-button-next',
                autoHeight: true,
                // 如果需要分页器
                pagination: '.swiper-pagination',
                paginationClickable :true, // 点击分页切换
                autoplayDisableOnInteraction : false, // 点击切换后是否自动播放 (默认true 不播放)

            });
            // 切换游戏
            $('.chessList li').off().hover(function () {
                var index = $(this).index();
                $('.swiper-pagination').find('.swiper-pagination-bullet').eq(index).click();
            })

        }



    })
</script>