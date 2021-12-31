<?php
session_start();

$companyName = $_SESSION['COMPANY_NAME_SESSION'];
$tplNmaeSession = $_SESSION['TPL_NAME_SESSION'];
$uid = $_SESSION['Oid'];


?>
<style>
    .timebox:hover{box-shadow:0 6px 12px #1a1a1a}
    .timebox:hover .img{transform:scale(1.02)}
    .timebox:hover h3{color:#f28149}@keyframes opacityShow{0%{opacity:0}to{opacity:1}}
    .ptaiyangcheng{ width:100%;; padding:0; margin-left:-460px;text-align:center; margin-top:-50px; margin-bottom:5px;}
    .ptaiyangcheng h4{ font-size:40px; color:#ffffff;}
    .gengduozixun img{ margin-right:0px; margin-bottom: -30px; margin-top:40px;}

</style>

    <div class="timeline">
    <!-- 视频 -->
    <div class="commonWin audioWin" style="left: 577.5px; top: 300px;">
        <div class="commonWin_close">
            <div class="commonWin_close_ico">x</div>
        </div>
        <video autoplay="" controls="" src="<?php echo $tplNmaeSession;?>images/video/sp.mp4"></video>
    </div>

    <div class="banner">
        <div class="jBanners banner">
            <div class="banner_l"></div>
            <div class="banner_r"></div>
            <div class="slider">
                <div class="mod-home">
                    <div class="mod-banner">
                        <div style="height: 450px;" class="slide-wrap" >
                            <div class="hd num">
                                <ul>
                                    <li class="on"></li>
                                </ul>
                            </div>
                            <div class="bd">
                                <ul style="position: relative; width: 1200px; height: 392px;">
                                    <li style="position: absolute; width: 1200px; left: 0px; top: 0px;">
                                        <a href="" target="_blank"><img src="<?php echo $tplNmaeSession;?>images/fc.jpg"></a>
                                    </li>
                                </ul>
                            </div>
                            <!--end 焦点图-->
                            <div class="banner-mask"></div>
                        </div>

                    </div>
                </div>

            </div>
            <div class="paly_btn"></div>
        </div>
    </div>
    <div class="w_1200">
        <div class="ptaiyangcheng"><h4><?php echo $companyName;?>动态</h4></div>
        <div class="mainBody pr">
            <div class="warp clearfix">
                <div class="content grid" style="position: relative; height: 1450px; ">
                    <div class="timebox grid-item grid-item-height new" style="position: absolute; left: -160px; top: 0px;width:585px;">
                        <div class="timeMain">
                            <div class="imgBox url"><img class="img" src="<?php echo $tplNmaeSession;?>images/presence/1-1.jpg">
                            </div>
                            <h3 class="url"><?php echo $companyName;?>赛车队（捷凯）率先赢得澳门房车杯选拔赛三甲</h3>
                            <p><?php echo $companyName;?>赞助的赛车队（捷凯）再度与赛车场上扬威，于5月24日至26日期间的澳门房车杯1600CC及1950CC选拔赛第一及第二回合成功夺取佳绩。</p></div>

                    </div><!--for197734675266--><!--ms-for-end:-->
                    <div class="timebox grid-item grid-item-height new" style="position: absolute; left: 455px; top: 0px; width:585px;">
                        <div class="timeMain">
                            <div class="imgBox url"><img class="img" src="<?php echo $tplNmaeSession;?>images/presence/2-2.jpg">
                            </div>
                            <h3 class="url"><?php echo $companyName;?>冠名赞助 "澳门小姐竞选2019"</h3>
                            <p>由<?php echo $companyName;?>冠名赞助，太阳娱乐文化协办 "澳门小姐竞选2019" 于6月5日在澳门旅游塔会展中心举办新闻发布会，宣布大赛正式启动，开始全澳招募参赛佳丽。</p></div>

                    </div>
                    <div class="timebox grid-item grid-item-height new" style="position: absolute; left: -160px; top:500px; width:585px;">
                        <div class="timeMain">
                            <div class="imgBox url"><img class="img" src="<?php echo $tplNmaeSession;?>images/presence/3-3.jpg">
                            </div>
                            <h3 class="url"><?php echo $companyName;?>首创全球至尊综合型会籍 "尊华会" 隆重登场</h3>
                            <p>在2019年全面革新会籍制度,运用集团多元化产业及多个海外业务的优势,把旅游 影视娱乐 餐饮 购物 度假村酒店项目发展等领域结合，创立全球至尊级综合型会籍"尊华会"。</p></div>

                    </div>
                    <div class="timebox grid-item grid-item-height new" style="position: absolute; left: 455px; top:500px; width:585px;">
                        <div class="timeMain">
                            <div class="imgBox url"><img class="img" src="<?php echo $tplNmaeSession;?>images/presence/4-4.jpg">
                            </div>
                            <h3 class="url">"<?php echo $companyName;?>第65届澳门格兰披治大赛车"首日精彩花絮</h3>
                            <p>"<?php echo $companyName;?>第65届澳门格兰披治大赛车" 正式开锣！<?php echo $companyName;?>已经连续五年冠名赞助此赛事，一如既往地支持澳门体育盛事，致力推动体育产业发展。</p></div>

                    </div>
                    <div class="timebox grid-item grid-item-height new" style="position: absolute; left: -160px; top:1000px; width:585px;">
                        <div class="timeMain">
                            <div class="imgBox url"><img class="img" src="<?php echo $tplNmaeSession;?>images/presence/5-5.jpg">
                            </div>
                            <h3 class="url"><?php echo $companyName;?>全力支持"亚洲泰拳锦标赛2018"</h3>
                            <p>由澳门泰拳总会主办 澳门特别行政区政府体育局主赞助 <?php echo $companyName;?>全力支持的"亚洲泰拳锦标赛2018"将于12月5日至11日首次在澳门举办。届时将邀请来自多个国家及地区的参赛队伍,一起共襄盛举</p></div>

                    </div>
                    <div class="timebox grid-item grid-item-height new" style="position: absolute; left: 455px; top:1000px; width:585px;">
                        <div class="timeMain">
                            <div class="imgBox url"><img class="img" src="<?php echo $tplNmaeSession;?>images/presence/6-6.jpg">
                            </div>
                            <h3 class="url"><?php echo $companyName;?>支持"2019武林群英会"</h3>
                            <p><?php echo $companyName;?>一直积极推动澳门体育事业发展，今年继续支持年度体育盛事"2019武林群英会",共襄盛举。主办单位于7月16日下午在澳门科学馆会议中心召开新闻发布会,介绍活动内容及筹备工作.</p></div>

                    </div>

                </div>
            </div>
        </div>
        <div class="gengduozixun" style="text-align:center;"><img src="<?php echo $tplNmaeSession;?>images/presence/7-7.jpg"></div>
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
                $('.commonWin').hide()
            })
        }
        showPresenceVideo();

    })
</script>