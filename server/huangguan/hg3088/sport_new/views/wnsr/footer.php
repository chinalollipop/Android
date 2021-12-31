<?php
$companyName = COMPANY_NAME;
?>
<!-- 公用底部 -->
<div class="footer">
    <hr class="sdk" />
    <div class="bottomDiv1" >
        <div class="w_1000">
            <div class="stepDiv fl">
                <ul>
                    <li>
                        <img src="<?php echo TPL_NAME;?>images/step_1.png" class="fl">
                        <div class="textDiv fl">
                            <span>成为</span>
                            <p><?php echo $companyName;?>会员</p>
                        </div>
                    </li>
                    <img src="<?php echo TPL_NAME;?>images/jt.png" alt="" class="jtImg fl">
                    <li>
                        <img src="<?php echo TPL_NAME;?>images/step_2.png" class="fl">
                        <div class="textDiv fl">
                            <span>领取</span>
                            <p>充值礼金</p>
                        </div>
                    </li>
                    <img src="<?php echo TPL_NAME;?>images/jt.png" alt="" class="jtImg fl">
                    <li>
                        <img src="<?php echo TPL_NAME;?>images/step_3.png" class="fl">
                        <div class="textDiv fl">
                            <span>畅游</span>
                            <p><?php echo $companyName;?></p>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="btnDiv fr" >
                <?php
                if(!$uid){
                    echo '<a href="javascript:;" class="to_memberreg reg_a fl">免费开户</a>
                                       <a href="javascript:;" class="to_testplaylogin start_a fl aLoginCheck-lo">免费试玩</a>';
                }
                ?>

            </div>
        </div>
    </div>
    <div class="bottomDiv">
        <div class="w_1000">
            <div class="hdDiv">
                <div class="item"><img src="<?php echo TPL_NAME;?>images/icon1.png"><span>客服QQ：<span class="qq_service_number"> </span></span></div>
                <div class="item"><img src="<?php echo TPL_NAME;?>images/icon2.png"><span>国际热线：<span class="ess_service_phone"> </span></span></div>
                <div class="item"><img src="<?php echo TPL_NAME;?>images/icon3.png"><span>邮箱：<span class="sz_service_email"> </span> </span></div>
                <div class="item"><img src="<?php echo TPL_NAME;?>images/icon4.png"><a class="to_livechat">7X24小时：在线客服</a></div>
            </div>
            <div class="bdDiv">
                <img src="<?php echo TPL_NAME;?>images/line.png">
                <div class="rowDiv">
                    <div class="leftDiv fl">
                        <div class="itemDiv fl">
                            <img src="<?php echo TPL_NAME;?>images/icon5.png">
                            <div class="text">
                                <span>国际顶级品牌</span>
                                <p>亚洲博彩龙头企业</p>
                            </div>
                        </div>
                        <div class="itemDiv fl">
                            <img src="<?php echo TPL_NAME;?>images/icon6.png">
                            <div class="text">
                                <span>官方认证、大额无忧</span>
                                <p>拥有多国颁发正规执照</p>
                            </div>
                        </div>
                    </div>
                    <img src="<?php echo TPL_NAME;?>images/index-bottom-img.png" class="fl">
                    <div class="rightDiv fr">
                        <div class="itemDiv fl">
                            <img src="<?php echo TPL_NAME;?>images/icon7.png">
                            <div class="text">
                                <span>玩家保密系统</span>
                                <p>千人技术团队为您保驾护航</p>
                            </div>
                        </div>
                        <div class="itemDiv fl">
                            <img src="<?php echo TPL_NAME;?>images/icon8.png">
                            <div class="text">
                                <span>VIP尊享最高优惠</span>
                                <p>加入vip即可获得尊贵特权</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer_bottom">
        <div class="centre clearFix">
            <div class="footerLogo">
                <img src="<?php echo TPL_NAME;?>images/plat_icon.png" alt="">
            </div>
            <div class="footerNav">
                <div class="footerLink">
                    <a href="javascript:;" class="to_aboutus" data-index="0">关于我们</a>
                    <span>|</span>
                    <a href="javascript:;" class="to_aboutus" data-index="1">联系我们</a>
                    <span>|</span>
                    <a href="javascript:;" class="to_agentreg">联盟方案</a>
                    <span>|</span>
                    <a href="javascript:;" class="to_aboutus" data-index="2">存款帮助</a>
                    <span>|</span>
                    <a href="javascript:;" class="to_aboutus" data-index="2">取款帮助</a>
                    <span>|</span>
                    <a href="javascript:;" class="to_aboutus" data-index="4">常见问题</a>
                    <span>|</span>
                    <a href="javascript:;" class="to_aboutus" data-index="7">负责任博彩</a>
                </div>
                <div class="cp">Copyright © 2006-<?php echo date('Y');?> <?php echo $companyName;?> Reserved</div>
            </div>
        </div>
    </div>

</div>

<!-- 两侧对联 -->
<ul class="float_pic flowLeft float_pic_left" >
    <li></li>
    <li class="left1">
        <a href="javascript:;" class="to_memberreg ">

        </a>
    </li>
    <li>
        <a href="tencent://message/?uin=<?php echo getSysConfig('vns_agents_service_qq');?>&amp;Site=web&amp;Menu=yes">

        </a>
    </li>
    <li>
        <a href="javascript:;"  class="to_downloadapp">

        </a>
    </li>
    <li>
        <a href="<?php echo getSysConfig('vns_backup_web_url')?>" target="_blank">

        </a>
    </li>
    <li class="close" onclick="$(this).parents('.flowLeft').animate({width:'toggle'},200)">
        <a href="javascript:;">

        </a>
    </li>
</ul>
<ul class="float_pic flowRight float_pic_right" >
    <li></li>
    <li>
        <a href="javascript:;" class="to_livechat">

        </a>
    </li>
    <li>
        <a href="tencent://message/?uin=<?php echo getSysConfig('vns_service_qq');?>&amp;Site=web&amp;Menu=yes">

        </a>
    </li>
    <li class="left1">
        <a href="javascript:;" class="to_testplaylogin ">

        </a>
    </li>
    <li>
        <a >

        </a>
    </li>
    <li class="close" onclick="$(this).parents('.flowRight').animate({width:'toggle'},200)">
        <a href="javascript:;">

        </a>
    </li>
</ul>

<!-- 隐藏底部广告窗 -->
<div style="display: none;" class="hide-index-tg show_index_bg" onclick="parent.indexCommonObj.rightBottomAd(uid)">
    <span> 在线-即时帮助 </span>
    <span class="icon_bottom">  </span>
</div>


