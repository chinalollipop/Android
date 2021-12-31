<?php
    $tplNmaeSession = $_SESSION['TPL_NAME_SESSION'];
    $companyName = $_SESSION['COMPANY_NAME_SESSION'];
?>
    <!-- 公用底部 -->
    <div class="footer">
        <div class="footer_top">
            <img src="<?php echo $tplNmaeSession;?>images/foot-icon1.png" alt="">
        </div>
        <p class="footer_tip">
            英属维京群岛合法注册的博彩公司，并持有菲律宾政府卡格扬经济特区<br>
            FIRST CAGAYAN LEISURE AND RESORT CORPORATION颁发的体育博彩执照，<?php echo $companyName;?>集团所提供的所有产品和服务由菲律宾政府卡格扬经济特区版权所有 © 2018 <?php echo $companyName;?>集团
        </p>
        <p>
            <img src="<?php echo $tplNmaeSession;?>images/foot-icon2.png" alt="">
        </p>
        <div class="footer_bottom">
            <a href="javascript:;" class="to_aboutus" data-index="0">关于我们</a> &nbsp;|&nbsp;
            <a href="javascript:;" class="to_aboutus" data-index="1">联系我们</a> &nbsp;|&nbsp;
            <a href="javascript:;" class="to_aboutus" data-index="2">博彩责任</a> &nbsp;|&nbsp;
            <a href="javascript:;" class="to_agentreg">代理合作</a> |
            <a href="javascript:;" class="to_aboutus" data-index="6">条款与规则</a> &nbsp;|&nbsp;
            <a href="javascript:;" class="to_aboutus" data-index="4">存款取款</a> &nbsp;|&nbsp;
            <a href="javascript:;" class="to_aboutus" data-index="5">常见问题</a>
        </div>
        <p class="footer_bottom_p"> COPYRIGHT = 2013- <?php echo date('Y');?> LUCKIA ALL RIGHT RESERVED LUCKIA.CN 版权所有</p>
    </div>
    
    <!-- 右侧在线客服 -->
    <div id="sideChatRight" class="float-box float-box-right">
        <div>
            <ul>
                <li class="box-tel">
                    <a href="javascript:void(0);" class="to_agentreg">
                        <i></i>
                        <span>代理加盟</span>
                    </a>
                </li>
                <li class="box-service">
                    <a href="javascript:void(0);" class="to_livechat">
                        <i></i>
                        <span>在线客服</span>
                    </a>
                </li>

                <li class="box-wechat">
                    <a href="javascript:void(0);">
                        <i></i>
                        <span class="server_wechat_img"></span>
                    </a>
                </li>
                <li class="box-suggestion">
                    <a href="javascript:void(0);" class="to_suggestion">
                        <i></i>
                        <span>意见/投诉</span>
                    </a>
                </li>
               <!-- <li class="box-top">
                    <a href="#" id="goTop">
                        <i></i>
                    </a>
                </li>-->
            </ul>

        </div>
    </div>

    <!-- 隐藏底部广告窗 -->
    <div style="display: none;" class="hide-index-tg show_index_bg" onclick="parent.indexCommonObj.rightBottomAd(uid)">
        <span> 在线-即时帮助 </span>
        <span class="icon_bottom">  </span>
    </div>


<!-- 雪花动画 -->
<!--<div class="snow-container">
    <div class="snow foreground"></div>
    <div class="snow foreground layered"></div>
    <div class="snow middleground"></div>
    <div class="snow middleground layered"></div>
    <div class="snow background"></div>
    <div class="snow background layered"></div>
</div>-->



