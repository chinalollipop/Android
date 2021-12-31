<?php
session_start();

$tplNmaeSession = $_SESSION['TPL_NAME_SESSION'];
$uid = $_SESSION['Oid'];

$companyName = $_SESSION['COMPANY_NAME_SESSION'];

?>
<style>
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
    .timeline .timelist .year li span,.fr{float:right;*display:inline}
    .timeline .content{padding-top:30px;overflow: hidden;}
    .timebox{float:left;width:585px;background:#201f1f;border-radius:5px;margin-bottom:20px;transition:all .5s ease}
    .timebox:nth-child(2n) {margin-left: 30px;}
    .timebox.new{animation:timeline 1s ease}
    .timebox h4{width:100%;height:40px;line-height:40px;color:#c4c4c4;text-indent:30px;font-size:18px;background:#161514}
    .timebox .timeMain{padding:20px}
    .timebox .timeMain .imgBox{border:1px solid #292929;overflow:hidden}
    .timebox .timeMain .url{cursor:pointer}
    .timebox .timeMain h3{height:38px;line-height:38px;color:#fff;margin-top:10px;border-bottom:1px dashed #363636;transition:all .5s ease;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;}
    .timebox .timeMain p{line-height:26px;height:52px;margin:5px 0;overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-line-clamp: 3;-webkit-box-orient: vertical;}
    .timebox .timeMain .imgBox .img{max-width:564px;height: 350px;margin-bottom:-3px;transition:all .5s ease;margin: 0 auto;}

    .game_banner{height: 400px;background:url(<?php echo $tplNmaeSession;?>images/fc.jpg) center no-repeat;}
    .paly_btn{cursor:pointer;background:url(<?php echo $tplNmaeSession;?>images/play.png) no-repeat;position:absolute;left:50%;bottom:65px;width:100px;height:100px;margin-left:235px}
    .paly_btn:hover{background:url(<?php echo $tplNmaeSession;?>images/play1.png) no-repeat}
    .timebox:hover{box-shadow:0 6px 12px #1a1a1a}
    .timebox:hover .img{transform:scale(1.02)}
    .timebox:hover h3{color:#f28149}@keyframes opacityShow{0%{opacity:0}to{opacity:1}}
    .ptaiyangcheng{ width:100%;; padding:0; margin-left:-460px;text-align:center; margin-top:-50px; margin-bottom:5px;}
    .ptaiyangcheng h4{ font-size:40px; color:#ffffff;}
    .gengduozixun img{ margin-right:0px; margin-bottom: -30px;}
    .layui-layer-news img{max-width: 100%;margin: 10px 0;}

</style>

    <div class="timeline">
    <!-- 视频 -->
    <div class="commonWin audioWin" style="left: 577.5px; top: 300px;">
        <div class="commonWin_close">
            <div class="commonWin_close_ico">x</div>
        </div>
        <video  id="play_video" controls="" src="<?php echo $tplNmaeSession;?>images/video/sp.mp4"></video>
    </div>
    <div class="game_banner">
        <div class="w_1200" style="width: 720px;">
            <div class="paly_btn"></div>
        </div>
    </div>

    <div class="w_1200">
        
        <div class="mainBody pr">
            <div class="warp clearfix">
                <div class="content grid stage_presence_content" style="position: relative;">
                    <div class="timebox grid-item grid-item-height new" >
                        <div class="timeMain">
                            <div class="imgBox url"><img class="img" src="<?php echo $tplNmaeSession;?>images/presence/0-0.jpg?v=1">
                            </div>
                            <h3 class="url">星光璀璨！“<?php echo $companyName;?>第66届澳门格兰披治大赛车即时开幕”</h3>
                            <p>澳门年度体育盛事一2019年11月14日至17日将会在东望洋跑到再次上演
                                <?php echo $companyName;?>已连续第六年成为此项盛事的冠名赞助商</p></div>

                    </div>
                    <div class="timebox grid-item grid-item-height new" >
                        <div class="timeMain">
                            <div class="imgBox url"><img class="img" src="<?php echo $tplNmaeSession;?>images/presence/0-1.jpg?v=1">
                            </div>
                            <h3 class="url">“<?php echo $companyName;?>第66届澳门格兰披治大赛车明星阵容 马上揭晓”</h3>
                            <p>超强巨星阵容还将于不同时间段惊喜现身为赛事助阵！
                                国际体坛传奇赛车手，全球车迷激情狂欢，全城欢庆热闹非凡，无限欢乐尽在“<?php echo $companyName;?>”</p></div>

                    </div>
                    <div class="timebox grid-item grid-item-height new">
                        <div class="timeMain">
                            <div class="imgBox url"><img class="img" src="<?php echo $tplNmaeSession;?>images/presence/1-1.jpg?v=1">
                            </div>
                            <h3 class="url">太阳城赛车队（捷凯）率先赢得澳门房车杯选拔赛三甲</h3>
                            <p><?php echo $companyName;?>赞助的太阳城赛车队（捷凯）再度与赛车场上扬威，于5月24日至26日期间的澳门房车杯1600CC及1950CC选拔赛第一及第二回合成功夺取佳绩。</p></div>

                    </div>
                    <div class="timebox grid-item grid-item-height new" >
                        <div class="timeMain">
                            <div class="imgBox url"><img class="img" src="<?php echo $tplNmaeSession;?>images/presence/2-2.jpg?v=1">
                            </div>
                            <h3 class="url"><?php echo $companyName;?>冠名赞助 "澳门小姐竞选2019"</h3>
                            <p>由<?php echo $companyName;?>冠名赞助，太阳娱乐文化协办 "澳门小姐竞选2019" 于6月5日在澳门旅游塔会展中心举办新闻发布会，宣布大赛正式启动，开始全澳招募参赛佳丽。</p></div>

                    </div>
                    <div class="timebox grid-item grid-item-height new" >
                        <div class="timeMain">
                            <div class="imgBox url"><img class="img" src="<?php echo $tplNmaeSession;?>images/presence/3-3.jpg?v=1">
                            </div>
                            <h3 class="url"><?php echo $companyName;?>首创全球至尊综合型会籍 "尊华会" 隆重登场</h3>
                            <p>在2019年全面革新会籍制度,运用集团多元化产业及多个海外业务的优势,把旅游 影视娱乐 餐饮 购物 度假村酒店项目发展等领域结合，创立全球至尊级综合型会籍"尊华会"。</p></div>

                    </div>
                    <div class="timebox grid-item grid-item-height new" >
                        <div class="timeMain">
                            <div class="imgBox url"><img class="img" src="<?php echo $tplNmaeSession;?>images/presence/4-4.jpg?v=1">
                            </div>
                            <h3 class="url">"<?php echo $companyName;?>第65届澳门格兰披治大赛车"首日精彩花絮</h3>
                            <p>"<?php echo $companyName;?>第65届澳门格兰披治大赛车" 正式开锣！<?php echo $companyName;?>已经连续五年冠名赞助此赛事，一如既往地支持澳门体育盛事，致力推动体育产业发展。</p></div>

                    </div>
                    <div class="timebox grid-item grid-item-height new" >
                        <div class="timeMain">
                            <div class="imgBox url"><img class="img" src="<?php echo $tplNmaeSession;?>images/presence/5-5.jpg?v=1">
                            </div>
                            <h3 class="url"><?php echo $companyName;?>全力支持"亚洲泰拳锦标赛2018"</h3>
                            <p>由澳门泰拳总会主办 澳门特别行政区政府体育局主赞助 <?php echo $companyName;?>全力支持的"亚洲泰拳锦标赛2018"将于12月5日至11日首次在澳门举办。届时将邀请来自多个国家及地区的参赛队伍,一起共襄盛举</p></div>

                    </div>
                    <div class="timebox grid-item grid-item-height new" >
                        <div class="timeMain">
                            <div class="imgBox url"><img class="img" src="<?php echo $tplNmaeSession;?>images/presence/6-6.jpg?v=1">
                            </div>
                            <h3 class="url"><?php echo $companyName;?>支持"2019武林群英会"</h3>
                            <p><?php echo $companyName;?>一直积极推动澳门体育事业发展，今年继续支持年度体育盛事"2019武林群英会",共襄盛举。主办单位于7月16日下午在澳门科学馆会议中心召开新闻发布会,介绍活动内容及筹备工作.</p></div>

                    </div>

                </div>

                <!-- 分页 -->
                <div class="pagination presence_pagination">

                </div>

            </div>
        </div>
        <div class="gengduozixun" style="text-align:center;"><img src="<?php echo $tplNmaeSession;?>images/presence/7-7.jpg?v=1"></div>
    </div>

</div>
<script type="text/javascript">


    $(function () {

        // 打开视频
        function showPresenceVideo() {
            $('.paly_btn').click(function () {
                $('.commonWin').show()
            })
            $('.commonWin_close').click(function () {
                $('.commonWin').hide();
                document.getElementById('play_video').pause();
            })
        }
        showPresenceVideo();

        indexCommonObj.getNewsRecommend('list','',0);

    })
</script>