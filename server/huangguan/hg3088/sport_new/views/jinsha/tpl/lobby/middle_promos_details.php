<?php
session_start();

include "../../../../app/member/include/config.inc.php";

$type = isset($_REQUEST['type'])?$_REQUEST['type']:'' ; // 活动类型
$title = isset($_REQUEST['title'])?$_REQUEST['title']:'' ; // 优惠完整标题
$keys = isset($_REQUEST['key'])?$_REQUEST['key']:'' ;  // 活动图片链接地址
$ajaxUrl = (isset($_REQUEST['api']) && $type == 7)?$_REQUEST['api']:'' ;  // 自动领取接口地址
$flag = isset($_REQUEST['flag'])?$_REQUEST['flag']:''; // 自动领取唯一标识

?>

<style >
    body{color:#6e6e6e;}
    .back-bar{background:#404040;height:40px;max-width:970px;margin:10px auto}
    .btn-back{border:0;background:#18191d;color:#fff;text-transform:uppercase;float:left;height: 24px;padding: 8px 20px;}
    .btn-back:hover{background:#525252;color:#fff}
    .article-box{max-width:970px;margin:0 auto;background:url(<?php echo TPL_NAME;?>images/about_box.png) center no-repeat;background-size:cover;padding:10px 20px;color:#fff}
    .article-title{font-family:inherit;margin:20px auto}
    .details-wrap .title,.details-wrap .title strong{margin:0px;font-size:28px}
    .details-wrap .sub-wrap.promotion-banner{background-size:930px 930px;background-repeat:no-repeat;width: 930px}
    .details-wrap p{font-size:16px;padding: 5px 0;}
    .details-wrap ol{font-size:16px}
    .details-wrap ol > li{float:initial}
    .details-wrap li{margin:8px 0}
    .title{margin:0px;font-size:28px}
    .content{position:relative}
    .content a:hover{background-color:transparent;}
</style>

<div class="details-wrap">
    <div class="back-bar">
        <div class="btn-group btn-block">
            <div class="btn-grp pull-left clearfix">
                <a class="btn-back to_promos" href="javascript:;"  >
                    <span style="line-height: 21px;"> < 回到上一页</span>
                </a>
            </div>
        </div>
    </div>
    <div class="article-box">
        <div class="article-title">
            <h2 class="title"><strong><?php echo $title;?></strong></h2>
        </div>
        <div class="content">
            <?php
            if($type == 7)
                echo '<a class="promos_btn btn_'.$flag.'" href="javascript:;" data-type="'.$flag.'"></a>';
            ?>
            <img class="sub-wrap promotion-banner" src="<?php echo $keys; ?>">
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        // 自动领取
        function autoGetPromos() {
            var proflage = false ;
            $('.promos_btn').on('click',function () {
                if(!uid){
                    layer.msg('请先登录',{time:alertTime});
                    return ;
                }
                if(userAgents=='demoguest'){
                    layer.msg('请注册真实用户',{time:alertTime});
                    return ;
                }
                if(proflage){
                    return ;
                }
                var url = '<?php echo $ajaxUrl;?>';
                proflage = true ;
                $.ajax({
                    type : 'POST',
                    url : url ,
                    data : {action:'receive_red_envelope'},
                    dataType : 'json',
                    success:function(res) {
                        if(res){
                            proflage = false ;
                            layer.msg(res.info,{time:alertTime});
                        }

                    },
                    error:function(){
                        layer.msg('网络异常',{time:alertTime});
                        proflage = false ;
                    }
                });


            })
        }

        autoGetPromos();

        
    })
</script>