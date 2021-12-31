<?php
include "../app/member/include/config.inc.php";
include "../app/member/include/address.mem.php";

$userid = $_SESSION['userid'];
$username = $_SESSION['UserName'];
$langx=$_SESSION['langx'];

$prokeys = isset($_REQUEST['prokey'])?$_REQUEST['prokey']:'' ;  // 活动图片key,跳转到对应活动详情

$lists = returnPromosList('',2);
$promotionList = json_encode($lists);

?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>优惠活动</title>
    <style>
        html,body{background:#f7f1f1;overflow-y:auto;height:100%}
        .promos_content img{width:100%}
        a{text-decoration:none}
        .promos_content{width:1000px;overflow:hidden;margin-bottom:20px}
        .pro_title{width:100%;height:270px;background:url(../images/promos/pro_title_<?php echo TPL_FILE_NAME;?>.jpg) no-repeat;background-size:100%;border-bottom:1px solid #000}
        .promos_ul{margin:10px 0;padding:0}
        .promos_ul li{list-style:none;width:31%;float:left;display:inline-block;padding:8px;background:#fff;border-radius:5px}
        .promos_ul li:nth-child(3n+1),.promos_ul li:nth-child(3n+2){margin-right:10px}
        .promos_ul li:nth-child(n+3){margin-top:5px}
        .promos_ul p{color:#000}
        .promos_ul span{display:block;padding:5px 0;border-top:1px solid #ccc;color:#737171;position:relative}
        .promos_ul span:after{position:absolute;content:">>";right:0}
    </style>

</head>
<body >
<!-- 活动开关 -->
<!-- ACTIVITY_SWITCH -->
<!-- LUCKY_RED_ENVELOPE_SWITCH  幸运红包 -->

<div class="promos_content">
<div class="pro_title"></div>
    <div class="promos_list">
        <ul class="promos_ul">

        </ul>
    </div>

</div>
</body>
<script type="text/javascript" src="../../../js/jquery.js"></script>
<script type="text/javascript" src="/js/layer/layer.js"></script>
<script type="text/javascript" src="../../../js/common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript">
    $(function () {
        var promosList = <?php echo $promotionList;?>;

        // 优惠列表
        function showPromosList() {
            var str = '';
            var $promos_ul = $('.promos_ul');
            for(var i=0;i<promosList.length;i++){
                str +=  '<li>' +
                        '<img src="'+ promosList[i].imgurl +'" alt="'+ promosList[i].title +'"/>'+
                        '<a href="javascript:;" class="pro_bottom show_promo_details promos_id_'+promosList[i].flag+'" data-flag="'+ promosList[i].flag + '" data-api="'+ promosList[i].ajaxurl + '" data-keys="'+ promosList[i].contenturl +'" data-type="'+ promosList[i].type +'" data-title="'+ promosList[i].title +'">' +
                        '<p>'+ promosList[i].title +'</p>'+
                        '<span>查看详情</span>'+
                        '</a>'+
                        '</li>';
            }
            $promos_ul.html(str) ;

        }

        // 显示详情
        function showPromosDetails(){
            $('.promos_ul').on('click','.show_promo_details',function () {
                var pro_title = $(this).attr('data-title');
                var pro_type = $(this).attr('data-type');
                var pro_keys = $(this).attr('data-keys');
                var pro_api = $(this).attr('data-api');
                var pro_flag = $(this).attr('data-flag');
                var url = '/tpl/allpictem.php?to='+pro_type+'&title='+pro_title+'&api='+pro_api+'&keys='+pro_keys+'&flag='+pro_flag ;
                layer.open({
                    title: pro_title,
                    type: 2,
                    area: ['1000px', '95%'],
                    skin: 'layui-layer-promo', //样式类名
                    //closeBtn: 0, //不显示关闭按钮
                    anim: 2,
                    //scrollbar: false, // 父页面 滚动条 禁止
                    shadeClose: true, //开启遮罩关闭
                    content:[url, 'no']
                });
            })
        }

        // 跳转到对应的优惠活动详情
        function goToPromosDetail(){
            var key = '<?php echo $prokeys;?>';
            if(key){
                $('.promos_id_'+key).click();
            }
        }

        showPromosList();
        showPromosDetails();
        goToPromosDetail();

    });

</script>
</html>