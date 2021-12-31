<?php
$companyName = $_SESSION['COMPANY_NAME_SESSION'];

?>
    <!-- 公用底部 -->
    <div class="foot_box">
        <table align="center" width="1200" border="0" cellspacing="0" cellpadding="0">
            <tbody>
            <tr>
                <td><a href="javascript:;" class="to_aboutus" data-index="0">关于我们</a></td>
                <td><a href="javascript:;" class="to_aboutus" data-index="1">联系我们</a></td>
                <td><a href="javascript:;" class="to_aboutus" data-index="2">存款帮助</a></td>
                <td><a href="javascript:;" class="to_aboutus" data-index="3">取款帮助</a></td>
                <td><a href="javascript:;" class="to_aboutus" data-index="4">常见问题</a></td>
                <td><a href="javascript:;" class="to_aboutus" data-index="5">规则说明</a></td>
                <td><a href="javascript:;" class="to_aboutus" data-index="6">使用条款</a></td>
                <td><a href="javascript:;" class="to_aboutus" data-index="7">博彩责任</a></td>
            </tr>
            </tbody>
        </table>
        <div class="foot_line"></div>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="height: 250px;">
            <tbody>
            <tr>
                <td valign="middle" style="padding: 0 160px ;">
                    <div class="foot_about">
                        <h3>关于我们</h3>
                        <p><?php echo $companyName;?>为尖端技术带了全新的体验和更高层次的创新与革新。我们的愿景是：成为市场领导者，为客户提供最好的游戏体验，最好的创新投注，以及最贴心的VIP服务 我们将不遗余力保证您在此网站的机密性，安全性和公平性</p>
                    </div>
                </td>
                <td valign="middle">
                    <div class="foot_about">
                        <h3 style="padding-top: 16px">合作伙伴</h3>
                        <img src="<?php echo TPL_NAME;?>images/footer_product.png" id="cms_footer_product">
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        <div class="cms_footer_red" style="color: #fff;text-align: center">
            Altemate Text <?php echo date('Y')?>|<?php echo $companyName;?>版权所有 All Rights Reserved.
        </div>
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

    <!-- 新年活动 -->
    <?php
    if($af_aRedPocketset){ // 是否开启新年活动
        echo '<div class="new_year_con" style="cursor:pointer;position:fixed;bottom:0;right:0;z-index:20;width: 330px;height: 360px;background: url(/images/hongbao/newy_btn_6668.gif) no-repeat;background-size: 100%;">
            <a class="close_new_year" onclick="$(this).parent().hide(200)" style="display: block; position: absolute; width: 40px; height: 40px; right: 13px; top: 14px;"></a>
            <a class="to_promos_details" data-flag="newyear_hb" data-api="/app/member/api/newyear2021HbApi.php" data-type="7" data-keys="/images/hongbao/hb_bg_6668.jpg" data-title="" style="display: block;height: 80%;width: 100%;margin-top: 60px;"></a>           
        </div>';
    }
    ?>

