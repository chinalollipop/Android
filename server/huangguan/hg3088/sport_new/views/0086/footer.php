<?php

?>
    <!-- 公用底部 -->
    <div class="cms_footer_grey">
        <?php if(!$uid) { ?>
            <div class="cms_footer_container">
                <table border="0" cellspacing="0" cellpadding="0" class="cms_footer_nav">
                    <tbody><tr>
                        <td valign="middle"><a href="javascript:;" class="to_memberreg cms_footer-link01">我要成为会员</a></td>
                    </tr>
                    </tbody>
                </table>

                <table border="0" cellspacing="0" cellpadding="0" class="cms_footer_nav">
                    <tbody><tr>
                        <td valign="middle"><a href="javascript:;" class="to_agentreg cms_footer-link01" data-index="2">我要加入联盟计划</a></td>
                    </tr>
                    </tbody></table>

                <table border="0" cellspacing="0" cellpadding="0" class="cms_footer_nav">
                    <tbody><tr>
                        <td valign="middle"><a href="javascript:;" class="to_promos cms_footer-link01">我要成为 VIP</a></td>
                    </tr>
                    </tbody></table>
            </div>
        <?php }?>
        <div style="width:100%; height:1px; background-color:#151515; margin:0px 0px 10px;"></div>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:0 40px;">
            <tbody><tr>
                <td valign="middle">
                    <div class="cms_footer_pro">
                        <a href="javascript:;" ><span class="cms_footer_pro_title">HG0086的独特之处</span></a>

                        <br>
                        <a href="javascript:;" class="to_sports" data-rtype="r" data-showtype="today"><span class="cms_footer_pro_title">体育与滚球</span></a>
                    </div>

                    <div class="cms_footer_pro">
                        <a href="javascript:;"><span class="cms_footer_pro_title">赢得业界和玩家高度认同</span></a>
                        <br>
                        <a href="javascript:;" class="to_lives"><span class="cms_footer_pro_title">真人娱乐场</span></a>
                    </div>

                    <div class="cms_footer_pro">
                        <a href="javascript:;"><span class="cms_footer_pro_title">信誉和安全保障</span></a>
                        <br>
                        <a href="javascript:;" class="to_games"><span class="cms_footer_pro_title">电子游戏</span></a>
                    </div>

                    <div class="cms_footer_pro">
                        <a href="javascript:;"><span class="cms_footer_pro_title">品质服务</span></a>
                        <br>
                        <a href="javascript:;" class="to_lotterys"><span class="cms_footer_pro_title">快乐彩</span></a>
                    </div>

                    <div class="cms_footer_pro">
                        <a href="javascript:;" ><span class="cms_footer_pro_title">独有VIP服务</span></a>
                        <br>
                        <a href="javascript:;" class="to_promos"><span class="cms_footer_pro_title">赛事推荐和优惠</span></a>
                    </div>
                </td>
                <td valign="middle"><img src="<?php echo TPL_NAME;?>images/footer_product.png" id="cms_footer_product"></td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="cms_footer_red">
        <table border="0" cellspacing="0" cellpadding="0" style="font-family:'Microsoft YaHei UI'; font-size:13px; color:#ffffff;">
            <tbody><tr>
                <td width="40">&nbsp;</td>
                <td align="left" style="font-size:12px; font-weight:normal;">HG0086 © 版权所有<!--2008 - --><?php /*echo date('Y')*/?> </td>
                <td width="30">&nbsp;</td>
                <td><a href="javascript:;" class="to_aboutus" data-index="0">关于我们</a></td>
                <td width="15">&nbsp;</td>
                <td><a href="javascript:;" class="to_aboutus" data-index="1">联系我们</a></td>
                <td width="15">&nbsp;</td>
                <td><a href="javascript:;" class="to_aboutus" data-index="2">存款帮助</a></td>
                <td width="15">&nbsp;</td>
                <td><a href="javascript:;" class="to_aboutus" data-index="3">取款帮助</a></td>
                <td width="15">&nbsp;</td>
                <td><a href="javascript:;" class="to_aboutus" data-index="4">常见问题</a></td>
                <td width="15">&nbsp;</td>
                <td><a href="javascript:;" class="to_aboutus" data-index="5">规则说明</a></td>
                <td width="15">&nbsp;</td>
                <td><a href="javascript:;" class="to_aboutus" data-index="6">使用条款</a></td>
                <td width="15">&nbsp;</td>
                <td><a href="javascript:;" class="to_aboutus" data-index="7">博彩责任</a></td>
                <td width="45">&nbsp;</td>
                <td class="foot_icon"><a class="cms_footer_qq cm_icon"></a></td>
                <td class="foot_icon"> <a class="cms_footer_wc cm_icon"></a> </td>
                <td class="foot_icon"><a class="cms_footer_wb cm_icon"></a> </td>
                <td class="foot_icon"> <a class="cms_footer_sj cm_icon" > </a> </td>
                <td width="40">&nbsp;</td>
            </tr>
            </tbody>
        </table>
    </div>

    <!-- 右侧底部客服 -->
    <div class="kf_right">
        <a href="javascript:;" class="whkf whkf_off"> </a>
        <a class="to_livechat zxkf" > </a>
        <div class="wc_logo" >
            <p >扫一扫</p>
            <span class="server_wechat_img"> </span>
            <p >微信客服</p>
        </div>
    </div>

    <!-- 隐藏底部广告窗 -->
    <div style="display: none;" class="hide-index-tg show_index_bg" onclick="parent.indexCommonObj.rightBottomAd(uid)">
        <span> 在线-即时帮助 </span>
        <span class="icon_bottom">  </span>
    </div>

    <!-- 幸运大转盘 -->
    <div class="lucky_icon "
       style="position: fixed;z-index:9;width: 200px;height: 200px;bottom: 100px; display: none;">
        <span class="to_promos" onclick="$(this).parent().hide()" style="cursor:pointer;position:absolute;display: inline-block;width: 100%;height: 100%;background: url(/images/hongbao/lucky/lucky_icon.jpg) no-repeat;"></span>
        <div style="position: relative"> <a class="close_lucky" onclick="$(this).parents('.lucky_icon').hide()" href="javascript:;" style="display: inline-block;position: absolute;width: 20px;height: 20px;top: 5px;right:5px;background: #9f9f9f;z-index: 10;text-align: center; color: #fff;border-radius: 50%;">x</a> </div>
    </div>

    <!-- 新年活动 -->
<?php
if($af_aRedPocketset){ // 是否开启新年活动
  echo '<div class="new_year_con" style="cursor:pointer;position:fixed;bottom:0;right:0;z-index:20;width: 360px;height: 453px;background: url(/images/hongbao/newy_btn.png) no-repeat;background-size: 100%;">
        <a class="close_new_year" onclick="$(this).parent().hide(200)" style="display: block; position: absolute; width: 40px; height: 40px; right: 45px; top: 32px;"></a>
        <a class="to_promos" data-keys="newyear_hb" style="display: block;height: 80%;width: 100%;margin-top: 100px;"></a>
        <div class="new_year_time" style="font-size:14px;text-align:center;width: 100%;height: 40px;line-height:40px;position: absolute;bottom: 64px;color: #c30202;">红包活动开启中</div>
    </div>';
}
?>

