<?php
session_start();
include "../../../../app/member/include/config.inc.php";
$prokeys = isset($_REQUEST['prokey'])?$_REQUEST['prokey']:'' ;  // 活动图片key
$lists = returnPromosList('',1);
$promotionList = json_encode($lists);

$categoryList = returnPromosType();

?>
<div class="discount-banner">

</div>
<div class="discount-nav">
    <div class="discount-nav-list">
        <span>游戏类型：</span>
        <ul>
            <li class="active"><a href="javascript:;" data-type="all">全部优惠</a></li>
            <?php foreach ($categoryList as $key => $category){?>
                <li><a href="javascript:;" data-id="<?php echo $category['id'];?>" data-type="<?php echo $category['tag'];?>"><?php echo $category['name'];?></a></li>
            <?php }?>
        </ul>
    </div>
</div>

<div class="news-list-panel">
    <div class="news-content-center">
        <ul>
          <!--  <li>
                <a class="imgbox to_promos_details" href="javascript:;" data-keys="pro_1">
                    <img src="../../images/deposit1-promotion-banner-detail.jpg" alt="">
                </a>
                <div class="news-wrap">
                    <h1 class="tit">新会员首次存款免费送30%</h1>
                    <p class="date">2018-01-01</p>
                    <a class="more to_promos_details" href="javascript:;" data-keys="pro_1">阅读全文</a>
                </div>
            </li>
            <li>
                <a class="imgbox to_promos_details" href="javascript:;" data-keys="pro_2">
                    <img src="../../images/deposit1-promotion-banner-detail.jpg" alt="">
                </a>
                <div class="news-wrap">
                    <h1 class="tit">新会员首次存款免费送30%</h1>
                    <p class="date">2018-01-01</p>
                    <a class="more to_promos_details" href="javascript:;">阅读全文</a>
                </div>
            </li>
            <li>
                <a class="imgbox to_promos_details" href="javascript:;">
                    <img src="../../images/deposit1-promotion-banner-detail.jpg" alt="">
                </a>
                <div class="news-wrap">
                    <h1 class="tit">新会员首次存款免费送30%</h1>
                    <p class="date">2018-01-01</p>
                    <a class="more to_promos_details" href="javascript:;">阅读全文</a>
                </div>
            </li>
            <li>
                <a class="imgbox to_promos_details" href="javascript:;">
                    <img src="../../images/deposit1-promotion-banner-detail.jpg" alt="">
                </a>
                <div class="news-wrap">
                    <h1 class="tit">新会员首次存款免费送30%</h1>
                    <p class="date">2018-01-01</p>
                    <a class="more to_promos_details" href="javascript:;">阅读全文</a>
                </div>
            </li>-->
        </ul>
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
                        '                <a class="imgbox to_promos_details" href="javascript:;" data-flag="'+ promosList[i].flag +'" data-api="'+ promosList[i].ajaxurl +'" data-type="'+ promosList[i].type + '" data-keys="'+ promosList[i].contenturl +'" data-title="'+ promosList[i].title +','+ promosList[i].title1 +'">' +
                        '                    <img class="lazy" src="/images/loading.png" data-original="'+ promosList[i].imgurl +'" alt="">' +
                        '                </a>' +
                        '                <div class="news-wrap">' +
                        '                    <h1 class="tit">'+ promosList[i].title +'</h1>' +
                        '                    <h1 class="tit">'+ promosList[i].title1 +'</h1>' +
                        // '                    <p class="date">'+ promosList[i].date +'</p>' +
                        '                    <a class="more to_promos_details promos_id_'+promosList[i].id+'" href="javascript:;" data-flag="'+ promosList[i].flag +'" data-api="'+ promosList[i].ajaxurl +'" data-type="'+ promosList[i].type + '" data-keys="'+ promosList[i].contenturl +'" data-title="'+ promosList[i].title +','+ promosList[i].title1 +'">查看详情</a>' +
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