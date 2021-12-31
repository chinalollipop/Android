<?php
session_start();
include "../../../../app/member/include/config.inc.php";
$prokeys = isset($_REQUEST['prokey'])?$_REQUEST['prokey']:'' ;  // 活动图片key
$lists = returnPromosList('',1);
$promotionList = json_encode($lists);

$categoryList = returnPromosType();

?>
<style>
    .slots{background: #252525;}
    .news-content-center ul li .imgbox {text-align:center;display: block;height: 145px;margin: 5px 0;}
    .news-content-center ul li .imgbox img {width: 960px;height: 100%;margin: 0 auto;}
    .news-content-center .news-wrap{display: none;}
</style>
<div class="game_banner">

</div>
<div class="slots">
    <div id="new-banner" style="background:url(<?php echo TPL_NAME;?>images/nav_promo.jpg) no-repeat center top; height:213px;">
    </div>

    <div class="w_1000">

        <div class="news-list-panel">
            <div class="news-content-center">
                <ul>

                </ul>
            </div>
        </div>

    </div>

</div>
<script type="text/javascript" src="js/jquery.lazyload.min.js"></script>
<script type="text/javascript">
    $(function () {

        // 优惠列表
        var promosList = <?php echo $promotionList;?>; // 新的活动（后台上传的活动）

        function showPromosList() {
            var liststr = '';
            if(promosList.length > 0){
                for(var i=0;i<promosList.length;i++){
                    liststr += '     <li class="promos_'+  promosList[i].type +' promos_'+ promosList[i].typesec +'">' +
                        '                <a class="imgbox to_promos_details promos_id_'+promosList[i].id+'" href="javascript:;" data-flag="'+ promosList[i].flag +'" data-api="'+ promosList[i].ajaxurl +'" data-type="'+ promosList[i].type + '" data-keys="'+ promosList[i].contenturl +'" data-title="'+ promosList[i].title +','+ promosList[i].title1 +'">' +
                        '                    <img class="lazy" src="/images/loading.png" data-original="'+ promosList[i].imgurl +'" alt="">' +
                        '                </a>' +
                        '                <div class="news-wrap">' +
                        '                    <h1 class="tit">'+ promosList[i].title +'</h1>' +
                        '                    <h1 class="tit sec_tit">'+ promosList[i].title1 +'</h1>' +
                        // '                    <p class="date">'+ promosList[i].date +'</p>' +
                        '                    <a class="more " > 长期优惠 </a>' +
                        '                </div>' +
                        '            </li>'
                }
            }

            $('.news-content-center ul').html(liststr) ;
            $('img.lazy').lazyload(); // 懒加载
            goToPromosDetail();
        }

        // 标签切换
        function changePromoNav(){
            $('.discount-nav-list').on('click','a',function () {
                var categoryId = $(this).data('id') ;
                var type = $(this).data('type') ;
                $(this).parents('li').addClass('active').siblings('li').removeClass('active');
                if(type == 'all'){ // 全部
                    $('.news-content-center li').show() ;
                }else{
                    $('.news-content-center li').hide() ;
                    $('.news-content-center .promos_'+categoryId).show() ;
                }

            })
        }
        // 跳转到对应的优惠活动详情
        function goToPromosDetail(){
            var key = '<?php echo $prokeys;?>';
            if(key){
                $('.promos_id_'+key).click();
            }
        }

        changePromoNav() ;
        showPromosList() ;


    })
</script>