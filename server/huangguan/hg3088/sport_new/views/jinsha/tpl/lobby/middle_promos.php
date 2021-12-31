<?php
session_start();
include "../../../../app/member/include/config.inc.php";
$prokeys = isset($_REQUEST['prokey'])?$_REQUEST['prokey']:'' ;  // 活动图片key
$lists = returnPromosList('',1);
$promotionList = json_encode($lists);

$categoryList = returnPromosType();

?>
<style>

    /* 优惠 */
    .discount-nav{height:52px;line-height:52px;background: #18191d;margin: 20px 0 15px;}
    .discount-nav-list{max-width:1200px;margin:0 auto;overflow:hidden}
    .discount-nav-list span{float:left}
    .discount-nav-list ul{float:left;overflow:hidden;display:-webkit-flex;display:flex;width: 1110px;}
    .discount-nav-list ul li{-webkit-flex:1;flex: 1;margin-right: 10px;}
    .discount-nav-list ul li.active a,.discount-nav-list ul li a:hover{background: url(<?php echo TPL_NAME;?>images/promos/active.png) no-repeat;background-size: 100%;color: #ce4a09;}
    .discount-nav-list ul li a{color: #dbdbdb;display: inline-block;width: 100%;height: 33px;line-height: 33px;text-align: center;}
    .discount-nav-list span{color:#fff;margin-left:15px;}
    .news-content-center{max-width: 1200px;margin:0 auto;overflow-y: hidden;padding: 10px 0;}
    .news-content-center ul li{height: 330px;overflow:hidden;padding: 6px 0px;width: 33%;float: left;text-align: center;background: #27272d;margin: 5px 0;}
    .news-content-center ul li:nth-child(3n+2),.news-content-center ul li:nth-child(3n+3) {margin-left: 5px;}
    .news-content-center ul li:last-child{border:none}
    .news-content-center ul li .imgbox{display:block;height: 190px;}
    .news-content-center ul li .imgbox img{width: 96%;height:100%;display:block;margin: 0 auto;}
    .news-content-center ul li .news-wrap{}
    .news-content-center ul li .news-wrap .tit{font-size:16px;margin: 10px 0;color: #fff;}
    .news-content-center ul li .news-wrap .date{font-size:16px;color:#000000;padding:5px 0px}
    .news-content-center ul li .news-wrap .more{font-size:16px;color:#000000;background: url(<?php echo TPL_NAME;?>images/promos/ck1.png) no-repeat;background-size: 100%;display: inline-block;padding: 7px 47px;color: #ce4a09;margin-top: 10px;}
    .news-content-center ul li .news-wrap .more:hover{background: url(<?php echo TPL_NAME;?>images/promos/ck2.png) no-repeat;background-size: 100%;}

</style>
<div class="w_1200 slots">
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

            </ul>
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