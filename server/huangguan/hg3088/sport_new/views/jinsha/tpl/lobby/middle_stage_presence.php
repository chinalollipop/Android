<?php
session_start();

include "../../../../app/member/include/config.inc.php";
$uid = $_SESSION['Oid'];


?>
<style>
    .layui-layer-page .layui-layer-content{padding: 10px;}
    .timeline{background: #101010 url(<?php echo TPL_NAME;?>images/presence/bg.jpg?v=2) no-repeat;}
    .timeline .per_logo{width:630px;height:94px;background: url(<?php echo TPL_NAME;?>images/presence/title_logo.png?v=2) no-repeat;margin: 50px auto 20px;}
    .timebox:hover{box-shadow:0 6px 12px #1a1a1a}
    .timebox:hover .img{transform:scale(1.02)}
    .timebox:hover h3{color:#f28149}@keyframes opacityShow{0%{opacity:0}to{opacity:1}}
    .ptaiyangcheng{width:800px;text-align:center;margin:0 auto 20px;color:#fff;font-size:16px;line-height:26px}
    .gengduozixun img{ margin-right:0px; margin-bottom: -30px; margin-top:40px;}

    .timeline .timelist,.timeline .timelist .line,.timeline .timelist .year,.fl{float:left;*display:inline}
    .timeline .timelist .line,.timeline .timelist .year li{position:relative}
    .timeline .timelist{width:118px;margin-right:40px}
    .timeline .timelist h3{width:125px;height:36px;font-size:24px;color:#fff;margin-top:40px}
    .timeline .timelist .line{width:10px;border-radius:20px;margin-left:10px;height:267px}
    .timeline .timelist .year{width:98px;padding-top:16px}
    .timeline .timelist .year li{height:34px;line-height:34px;color:#666;border-bottom:1px solid #383234;text-align:center;transition:all .5s ease;cursor:pointer}
    .timeline .timelist .year li.active{color:#fdd35d}
    .timeline .timelist .year li .lineHolder,.pa{position:absolute}
    .timeline .timelist .year li .lineHolder{width:4px;height:17px;background:#f28149;top:9px;left:1px;display:none}
    .timeline .timelist .year li.active .lineHolder{display:block}
    .timeline .timelist .year li span{color:#2e2e33;margin-right:20px}
    .timeline .timelist .year li.active span{color:#f28149}
    .timeline .content,.timeline .timelist .year li span,.fr{float:right;*display:inline}
    .timeline .content{width:100%;padding-top:30px;}
    .timebox{width: 394px;background:#201f1f;margin-bottom:20px;transition:all .5s ease;float: left;margin-right: 9px;}
    .timebox:nth-child(3n) {margin-right: 0;}
    .timebox.new{animation:timeline 1s ease}
    .timebox h4{width:100%;height:40px;line-height:40px;color:#c4c4c4;text-indent:30px;font-size:18px;background:#161514}
    .timebox .timeMain .imgBox{overflow:hidden}
    .timebox .timeMain .url{cursor:pointer}
    .timebox .timeMain h3{height:38px;line-height:38px;color:#fff;margin-top:10px;border-bottom:1px dashed #363636;transition:all .5s ease;font-size: 16px;padding: 0 10px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;}
    .timebox .timeMain p{line-height:26px;height:52px;margin:5px 0;padding: 0 10px;color: #f2f2f2;overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-line-clamp: 3;-webkit-box-orient: vertical;}
    .timebox .timeMain .imgBox .img{width:100%;height:232px;margin-bottom:-3px;transition:all .5s ease}
    .timeline .pre_qd{width:300px;height:45px;line-height:45px;background:#d0d0d0;text-align:center;font-size:20px;font-weight:700;margin:10px auto 60px}
</style>

    <div class="timeline">
        <div class="w_1200">
            <div class="per_logo"></div>
            <div class="ptaiyangcheng">
                作为一家兼具社会责任感和公益情怀的企业，澳门线上娱乐不但拥有多元化的产品，其用心也随处可见，企业致力于创造高品质服务，成为客户信赖、社会尊重、最具有价值并具国际影响力的综合化娱乐平台。
            </div>
            <div class="mainBody pr">
                <div class="warp clearfix">
                    <div class="content grid stage_presence_content" style="position: relative; ">
                        <div class="timebox grid-item grid-item-height new" >
                            <div class="timeMain">
                                <div class="imgBox url"><img class="img" src="<?php echo TPL_NAME;?>images/presence/1-1.jpg?v=2">
                                </div>
                            </div>
                        </div>
                        <div class="timebox grid-item grid-item-height new" >
                            <div class="timeMain">
                                <div class="imgBox url"><img class="img" src="<?php echo TPL_NAME;?>images/presence/2-2.jpg?v=2">
                                </div>
                            </div>
                        </div>
                        <div class="timebox grid-item grid-item-height new">
                            <div class="timeMain">
                                <div class="imgBox url"><img class="img" src="<?php echo TPL_NAME;?>images/presence/3-3.jpg?v=2">
                                </div>
                            </div>
                        </div>
                        <div class="timebox grid-item grid-item-height new" >
                            <div class="timeMain">
                                <div class="imgBox url"><img class="img" src="<?php echo TPL_NAME;?>images/presence/4-4.jpg?v=2">
                                </div>
                            </div>
                        </div>
                        <div class="timebox grid-item grid-item-height new" >
                            <div class="timeMain">
                                <div class="imgBox url"><img class="img" src="<?php echo TPL_NAME;?>images/presence/5-5.jpg?v=2">
                                </div>
                            </div>
                        </div>
                        <div class="timebox grid-item grid-item-height new" >
                            <div class="timeMain">
                                <div class="imgBox url"><img class="img" src="<?php echo TPL_NAME;?>images/presence/6-6.jpg?v=2">
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- 分页 -->
                    <div class="pagination presence_pagination">

                    </div>

                </div>
                <div class="pre_qd"> 更多精彩！敬请期待...</div>
            </div>

    </div>

</div>
<script type="text/javascript">


    $(function () {
        indexCommonObj.getNewsRecommend('list','',0);


    })
</script>