<?php
session_start();

include "../../../../app/member/include/config.inc.php";

$type = isset($_REQUEST['type'])?$_REQUEST['type']:'' ; // 活动类型
$title = isset($_REQUEST['title'])?$_REQUEST['title']:'' ; // 优惠完整标题
$keys = isset($_REQUEST['key'])?$_REQUEST['key']:'' ;  // 活动图片链接地址
$ajaxUrl = (isset($_REQUEST['api']) && $type == 7)?$_REQUEST['api']:'' ;  // 自动领取接口地址
$flag = isset($_REQUEST['flag'])?$_REQUEST['flag']:''; // 自动领取唯一标识

// 美东时间2020年1月24日，截止至2020年1月31日
$newYearBeginTime= '2020-01-24 00:00:00';
$newYearEndTime = '2020-01-26 23:59:59';
//$newYearBeginTime= '2020-01-13 00:00:00';
//$newYearEndTime = '2020-01-31 23:59:59';
$curtime = date("Y-m-d H:i:s");

?>

<style >

    .back-bar{background:#e3e3e3;height:40px;max-width:970px;margin:10px auto}
    .btn-back{border:0;background:#404040;color:#fff;text-transform:uppercase;float:left;height: 24px;padding: 8px 20px;}
    .btn-back:hover{background:#525252;color:#fff}
    .article-box{max-width:930px;margin:0 auto}
    .article-title{font-family:inherit;margin:20px auto}
    .title{margin:0px;font-size:28px}
    .sub-wrap.promotion-banner{background-size:930px 930px;background-repeat:no-repeat;width: 930px}
    .details-wrap p{font-size:16px;padding: 5px 0;}
    .packetText > label{word-wrap:break-word;width:190px}
    .content{position:relative}
    .content a:hover{background-color:transparent;}
    .content .css_btn{transition:.3s;position:absolute;display:block;width:160px;height:40px;margin:685px 0 0 385px;background:transparent}
    .content .css_btn:hover{transform: scale(1.1)}
    /* 新年活动 开始*/
    .content .btn_2020_3366{margin: 59px 0 0 170px;}
    .content .promos_btn_zh{width: 250px;height: 60px;background: url(/images/hongbao/3366_zh_btn.png) center no-repeat;margin: 1108px 0 0 470px;}
    .hb_mask_1{display:none;position:fixed;left:0;top:0;z-index:4;width:100%;height:100%;background-color:rgba(0,0,0,.2)}
    .hb_close_1{cursor:pointer;opacity:1;position:absolute;right: 48%;bottom: 15%;z-index:11;width:64px;height:64px;background:url("/images/hongbao/close_1.png") center no-repeat;}
    .receiveAfter_1{z-index:10;display:none;-webkit-transform:scale(0.1);transform:scale(0.1);position:absolute;width:500px;height:652px;background:url(/images/hongbao/3366hb_bg.png) center no-repeat;background-size:100%;left:50%;top:50%;margin:-456px -250px}
    .receiveAfter_1:before{content: '';position: absolute;display: inline-block;width:100%;height:100%;background: url(/images/hongbao/gold.png) center no-repeat;background-size: 100%;-webkit-animation:circle 10s linear infinite;animation:circle 10s linear infinite}
    .receiveAfter_1 .tip{position: relative;width: 100%;height: 464px;background: url(/images/hongbao/sec_bg.png) center no-repeat;padding-top: 180px;}
    .receiveAfter_1 .tip_third{display:none;background: url("/images/hongbao/third_bg.png") center no-repeat;}
    .receiveAfter_1 .tip p{color: #ffe6a2;font-size: 2.4rem;text-align: center;padding-top: 60px;font-weight: 600;text-shadow: 5px 2px 6px rgba(0,0,0,.4);}
    .receiveAfter_1 .tip.tip_third p{padding-top: 135px;}
    .receiveAfter_1 .tip p span:first-child{font-size: 4rem;}
    .reback{-webkit-animation:reback .5s linear forwards;animation:reback .5s linear forwards}
    #hongbao_animation{position:fixed;top:0;left:50%;width:1080px;height:100%;margin-left:-540px;z-index:5;/*pointer-events:none*/ /* 禁止点击事件*/}
    #hongbao_animation>div{/*width:150px;height:150px;*/position:absolute;-webkit-animation-iteration-count:infinite;-webkit-animation-direction:normal,normal;-webkit-animation-timing-function:linear,ease-in;-webkit-backface-visibility:hidden;animation-iteration-count:infinite;animation-direction:normal,normal;animation-timing-function:linear,ease-in;backface-visibility:hidden}
    #hongbao_animation>div>img{cursor:pointer;/*height:3rem;width:3rem;*/position:absolute;-webkit-animation-iteration-count:infinite;-webkit-animation-direction:alternate;-webkit-animation-timing-function:linear;-webkit-backface-visibility:hidden;animation-iteration-count:infinite;animation-direction:alternate;animation-timing-function:linear;backface-visibility:hidden}
    .shake{animation:shake .5s linear;-webkit-animation:shake .5s linear;}

    @keyframes reback {
        100% {
            -webkit-transform: scale(1);
            transform: scale(1);
        }
    }
    @keyframes circle {
        0% {
            -o-transform: rotate(0deg);
            -ms-transform: rotate(0deg);
            -moz-transform: rotate(0deg);
            -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
        }
        100% {
            -o-transform: rotate(360deg);
            -ms-transform: rotate(360deg);
            -moz-transform: rotate(360deg);
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }
    /* 红包下落动画 */
    @-webkit-keyframes fade{
        0%,90%{opacity:1}
        100%{opacity:0}
    }
    @keyframes fade{
        0%,90%{opacity:1}
        100%{opacity:0}
    }
    @-webkit-keyframes drop{
        0%{-webkit-transform:translate3d(0,0,0)}
        100%{-webkit-transform:translate3d(0,1100px,0)}
    }
    @keyframes drop{
        0%{transform:translate3d(0,0,0)}
        100%{transform:translate3d(0,1100px,0)}
    }
    @-webkit-keyframes clockwiseSpin{
        0%{-webkit-transform:none}
        100%{-webkit-transform:rotate(480deg)}
    }
    @keyframes clockwiseSpin{
        0%{transform:none}
        100%{transform:rotate(480deg)}
    }
    @-webkit-keyframes counterclockwiseSpinAndFlip{
        0%{-webkit-transform:none}
        100%{-webkit-transform:rotate(-480deg)}
    }
    @keyframes counterclockwiseSpinAndFlip{
        0%{transform:none}
        100%{transform:rotate(-480deg)}
    }
    /* 红包闪动 */
    @keyframes shake{
        0%{transform:rotate(5deg) translate3d(0,0,0)}
        50%{transform:rotate(-5deg) translate3d(0,0,0)}
        100%{transform:rotate(5deg) translate3d(0,0,0)}
    }
    @-webkit-keyframes shake{
        0%{-webkit-transform:rotate(5deg) translate3d(0,0,0)}
        50%{-webkit-transform:rotate(-5deg) translate3d(0,0,0)}
        100%{-webkit-transform:rotate(5deg) translate3d(0,0,0)}
    }
    /* 新年活动 结束*/

</style>

<!-- 背影 -->
<div class="hb_mask_1">
    <div id="hongbao_animation"> </div>
</div>

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
            if($type == 7){
                if($flag=='2020_3366'){
                    // 红包显示
                    echo '  
                          <div class="receiveAfter_1 receiveAfter_act">
                            <div class="tip">
                                <p> <span class="hb_time">0</span><span>次</span> </p>
                                <a class="css_btn promos_btn btn_'.$flag.'" href="javascript:;" data-type="'.$flag.'"></a>
                            </div>
                            <div class="tip tip_third">
                                <p> <span class="hb_mount">0</span><span>元</span> </p>
                            </div>
                            <span class="hb_close_1"></span>
                        </div>
                        ';
                    echo '<a class="css_btn promos_btn promos_btn_zh " href="javascript:;" data-type="getGrabTimes"></a>';
                }else{
                    echo '<a class="css_btn promos_btn btn_'.$flag.'" href="javascript:;" data-type="'.$flag.'"></a>';
                }

            }

            ?>
            <img class="sub-wrap promotion-banner" src="<?php echo $keys; ?>">
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        var hb_flag = '<?php echo $flag;?>';
        if(hb_flag=='2020_3366'){ // 新年活动
            var newYearBeginTime = '<?php echo $newYearBeginTime;?>';
            var newYearEndTime  = '<?php echo $newYearEndTime;?>';
            var curtime = '<?php echo $curtime;?>';
        }

        // 自动领取
        function autoGetPromos() {
            var proflage = false ;
            var $hb_mask_1 = $('.hb_mask_1'); // 新年活动一
            var $receiveAfter_act = $('.receiveAfter_act');// 领取后显示
            var $hb_close_1 = $('.hb_close_1');// 关闭按钮
            var $hb_time = $('.hb_time');// 红包次数
            var $hb_mount = $('.hb_mount');// 红包金额
            var $tip_third = $('.tip_third');// 领取红包区域

            $hb_close_1.on('click',function () { // 关闭新年红包
                $hb_mask_1.hide();
                $receiveAfter_act.hide().removeClass('reback');
            });
            $('.promos_btn').on('click',function () {
                var type = $(this).attr('data-type');
                var dataParam = {action:'receive_red_envelope'};
                if(!uid){
                    layer.msg('请先登录',{time:alertTime});
                    return ;
                }
                if(userAgents=='demoguest'){
                    layer.msg('请注册真实用户',{time:alertTime});
                    return ;
                }

                if(type=='getGrabTimes'){ // 获取次数
                    dataParam = {action:'getGrabTimes'};
                    $receiveAfter_act.find('.tip').show();
                    $tip_third.hide();
                }
                if(type == '2020_3366'){ // 领取新年红包
                    var hbTime = Number($hb_time.text());
                    if(curtime < newYearBeginTime || curtime > newYearEndTime){
                        layer.msg('请于美东时间1月24号-1月26号领取红包!',{time:alertTime});
                        return false;
                    }
                    if(hbTime<1){
                        layer.msg('可领次数不足',{time:alertTime});
                        return ;
                    }

                }
                if(proflage){
                    return ;
                }
                var url = '<?php echo $ajaxUrl;?>';
                proflage = true ;
                $.ajax({
                    type : 'POST',
                    url : url ,
                    data : dataParam,
                    dataType : 'json',
                    success:function(res) {
                        if(res){
                            proflage = false ;
                            if(res.describe){
                                layer.msg(res.describe,{time:alertTime});
                            }else{
                                layer.msg(res.info,{time:alertTime});
                            }
                            if(type=='getGrabTimes'){ // 获取新年活动领取次数
                                if(res.status == '200'){
                                    $hb_time.text(res.data.lastTimes);
                                    $hb_mask_1.show();
                                    $receiveAfter_act.show().addClass('reback');
                                }
                            }
                            if(type=='2020_3366'){ // 领取新年活动
                                if(res.status == '200'){
                                    $receiveAfter_act.find('.tip').hide();
                                    $tip_third.show();
                                    $hb_mount.text(res.data.giftGold);
                                    $hb_time.text(res.data.lastTimes);
                                }
                            }
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