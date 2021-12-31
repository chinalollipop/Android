<?php
session_start();

include "../../../../app/member/include/config.inc.php";

$type = isset($_REQUEST['type'])?$_REQUEST['type']:'' ; // 活动类型
$title = isset($_REQUEST['title'])?$_REQUEST['title']:'' ; // 优惠完整标题
$keys = isset($_REQUEST['key'])?$_REQUEST['key']:'' ;  // 活动图片链接地址
$ajaxUrl = (isset($_REQUEST['api']) && $type == 7)?$_REQUEST['api']:'' ;  // 自动领取接口地址
$flag = isset($_REQUEST['flag'])?$_REQUEST['flag']:''; // 自动领取唯一标识

// 新年活动是否开启
$sRedPocketset = $redisObj->getSimpleOne('red_pocket_open'); // 取redis 设置的值
$aRedPocketset = json_decode($sRedPocketset,true) ;

// 活动于北京时间1月24号（除夕）中午12:00-次日11：59开始，活动时间持续24小时
$newYearBeginTime= $aRedPocketset['newYearBeginTime']?$aRedPocketset['newYearBeginTime']:'2021-02-11 00:00:00'; // 活动二开始时间
$newYearEndTime = $aRedPocketset['newYearEndTime']?$aRedPocketset['newYearEndTime']:'2021-02-13 23:59:59'; // 活动二结束时间
$curtime = date("Y-m-d H:i:s",time()+12*60*60); // 北京时间

?>

<style >
     body{color:#6e6e6e;}
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
     .content .promos_btn,.content .ny_hb_btn{position:absolute;display:block;width:160px;height:40px;margin:685px 0 0 385px;background:transparent}
     .content .btn_attendance{ margin: 780px 0 0 390px;}
     .content .btn_chess{ margin: 558px 0 0 695px;}
     .content .btn_week{ margin: 745px 0 0 415px;}
     .content .btn_nation{ margin: 729px 0 0 385px;}
     .content .btn_king{ margin: 1205px 0 0 380px;}
     .content .btn_dragon{ margin: 1380px 0 0 383px;}
     .content .btn_sj_holiday{ margin: 706px 0 0 365px;}

     /*　新年活动*/
     .content .btn_2020_6668 {visibility: hidden;}
     .content .ny_hb_btn{display:none;transition:.3s;margin:360px 0 0 100px;width:170px;height:50px;background:url(/images/hongbao/new/hb_btn.png) center no-repeat;background-size:100%}
     .content .ny_hb_btn:hover,.content .ny_hb_btn:active{transform:scale(1.05)}
     .hb_mask_1{display:none;position:fixed;left:0;top:0;z-index:4;width:100%;height:100%;background-color:rgba(0,0,0,0.5)}
     .hb_close_1{transition:.3s;display:none;cursor:pointer;opacity:1;position:absolute;left: 50%;bottom: 9%;text-align: center;z-index: 11;width: 100px;height: 40px;line-height: 40px;background: #faec83;border-radius: 5px;margin-left: -50px;color: #c30202;font-size: 20px;}
     .hb_close_1:hover{transform: scale(1.05)}
     .receiveAfter_1{z-index:10;display:none;-webkit-transform:scale(0.1);transform:scale(0.1);position:fixed;width:552px;height:622px;background:url(/images/hongbao/new/hb_bg.png) center no-repeat;background-size:100%;left:50%;top:50%;margin:-336px -276px}
     .receiveAfter_1 .tip{padding-top:24rem}
     .receiveAfter_1 .tip p{color:#fff99a;font-size:2.4rem;text-align:center}
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
                if($flag == '2020_6668' || $flag=='newyear_hb'){ // 新年红包
                    // 红包显示
                    echo '                     
                          <div class="receiveAfter_1 receiveAfter_act">
                            <div class="tip">
                                <p>恭喜您获得</p>
                                <p> <span class="hb_mount">0</span><span>元红包</span> </p>
                            </div>
                            <!-- 关闭红包 -->
                            <span class="hb_close_1">领取</span>
                        </div>
                        <a class="ny_hb_btn" href="javascript:;"></a>';
                }

                echo '<a class="promos_btn btn_'.$flag.'" href="javascript:;" data-type="'.$flag.'"></a>';

            }

            ?>
            <img class="sub-wrap promotion-banner" src="<?php echo $keys; ?>">
        </div>
    </div>
</div>

<?php
if($flag =='2020_6668' || $flag=='newyear_hb'){
    echo '<script type="text/javascript" src="/js/newyear_hb.js"></script>';
}
?>
<script type="text/javascript">
    $(function () {
        var hb_flag = '<?php echo $flag;?>';

        if(hb_flag=='2020_6668' || hb_flag=='newyear_hb'){ // 新年活动
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

            $('.promos_btn').on('click',function () {
                var type = $(this).attr('data-type');

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

                var postData = {
                    type_flag:type,
                    action:'receive_red_envelope', //新春活动用到此参数，其他活动不用
                    platfrom:'hg<?php echo TPL_FILE_NAME;?>'
                }

                var url = '<?php echo $ajaxUrl;?>';
                proflage = true ;
                $.ajax({
                    type : 'POST',
                    url : url ,
                    data : postData,
                    dataType : 'json',
                    success:function(res) {
                        if(res){
                            proflage = false ;

                            if(res.info){
                                layer.msg(res.info,{time:alertTime});
                            }else {
                                layer.msg(res.describe,{time:alertTime});
                            }
                            if(type == '2020_6668' || type=='newyear_hb'){ // 新年活动
                                if(res.status=='200'){ // 领取成功 不需要弹出提示
                                    $receiveAfter_act.show().addClass('reback');
                                    $hb_close_1.show();
                                    $('.hb_mount').text(res.data.giftGold);
                                }else{
                                    $hb_mask_1.hide();
                                    $receiveAfter_act.hide();
                                    $hb_close_1.hide();
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

        // 点击红包
        function getRedBag(){
            var $hb_mask_1 = $('.hb_mask_1'); // 新年活动一
            var $receiveAfter_act = $('.receiveAfter_act');// 领取后显示
            var $hb_close_1 = $('.hb_close_1');// 关闭按钮

            if(hb_flag=='newyear_hb'){   /* 2021 新年活动 独有 */
                if(curtime > newYearBeginTime && curtime < newYearEndTime){
                    //autoGetPromos(); // 防止节点监听不了点击事件
                    $hb_mask_1.show();
                    // 红包雨 , 北京时间 ：2021/02/11 00:00:00 到 2021/02/13 23:59:59
                    hbInit(); // 红包雨
                }
            }

            $hb_close_1.on('click',function () { // 关闭新年红包
                $(this).hide();
                $hb_mask_1.hide();
                $receiveAfter_act.hide().removeClass('reback');
            });

            $('.ny_hb_btn').off().on('click',function () { // 召唤红包雨
                if(!uid){
                    layer.msg('请先登录',{time:alertTime});
                    return ;
                }
                    if(curtime < newYearBeginTime || curtime > newYearEndTime){
                        layer.msg('请于北京时间1月24号-1月28号期间领取红包!',{time:alertTime});
                        return false;
                    }
                    $hb_mask_1.show();
                    hbInit(); // 红包雨
            });
            $('#hongbao_animation').on('click','.hongbao_li img',function () {  // 点击红包，抢红包
                var type = $(this).attr('data-type');
                if(type=='hb'){
                    $('.promos_btn').click();
                }
            })
        }

        autoGetPromos();
        getRedBag();

        
    })
</script>