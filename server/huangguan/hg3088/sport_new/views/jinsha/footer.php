<?php
/* 超级彩金 */
$sumarr = array('22,912,279.31','21,412,279.71','32,710,269.92','23,152,701.10','25,541,691.81');
shuffle($sumarr); // 打乱数组
$ess_kf_phone =  getSysConfig('service_phone_24');
$backup_web_url =  getSysConfig('backup_web_url');
?>
    <!-- 公用底部 -->
    <div class="footer">
        <div class="caijin">
            <span class="jcje cjcj_td">
                <span class="cjcj_num"> <?php echo $sumarr[0];?></span>
            </span>
        </div>
        <div class="footer_main">
            <div class="footer_inner">
                <div class="tb">
                    <img src="<?php echo TPL_NAME;?>images/footer_top.png" alt="">
                </div>
                <div class="footer_first">
                    <img src="<?php echo TPL_NAME;?>images/footer_02.png" alt="" class="tuxiang">
                </div>
                <!-- 底部 -->
                <div id="footer_logo"></div>
                <div class="footer_infos">
                    <ul>
                        <li class="left">
                            <span  ><?php echo str_replace('https://','',$backup_web_url);?></span>的许可证、所有服务和游戏由位于China Macao Sun
                            City Group的澳门线上娱乐授予使用权以及提供，并在澳门博彩监督委员会监管下严格遵循《网络博彩监管法案2001》营运。<br>
                            <br>
                            2014年8月8日获颁许可证。<br>
                            <br>
                            澳门线上娱乐的营业许可证由澳门博彩监督委员会颁发并受其监管及授权在澳门提供服务。
                        </li>
                        <li class="center">
                            <div class="mail ng-binding" > <span class="sz_service_email"> </span> </div>
                            <div class="mei-don">美东时间：<span id="currentDate" class="getAmericaTime"> </span></div>
                            <div>© <span  >澳门线上娱乐</span>版权所有</div>
                        </li>
                        <li class="right">
                            <img src="<?php echo TPL_NAME;?>images/age_limit.png"><br>
                            澳门博彩监督委员会可实行强制收取债款。法定年龄18岁以下禁止登录
                            <span >澳门线上娱乐</span>。未成年人赌博是违法的。
                        </li>
                    </ul>
                </div>
                <div class="footer_second">
                    <div class="second">
                        <a href="javascript:;" class="to_aboutus" data-index="0">关于我们</a>
                        <a href="javascript:;" class="to_usercenter hyzxf" style="color: rgb(255, 255, 255);">会员中心</a>
                        <a href="javascript:;" class="to_withdraw" >线上取款</a>
                        <a href="javascript:;" class="to_deposit">在线支付</a>
                        <a href="javascript:;" class="to_aboutus" data-index="2">存款帮助</a>
                        <a href="javascript:;" class="to_agentreg">代理加盟</a>
                    </div>
                    <div class="bq">
                        Copyright © 澳门线上娱乐CASINO Reserved
                    </div>
                </div>

            </div>
        </div>

    </div>

    <!-- 左侧客服-->
    <div class="float_pic_left flowLeft fd_index left_fixed">
        <a href="<?php echo $backup_web_url;?>" target="_blank"></a>
        <a href="<?php echo $backup_web_url;?>" target="_blank"></a>
        <a href="<?php echo getSysConfig('download_app_page');?>" class="appload" target="_blank"></a>
        <a href="http://am529.com" target="_blank"></a>
        <a href="javascript:;" class="close" onclick="$(this).parents('.fd_index').animate({width:'toggle'},350)"></a>
    </div>
    <!-- 右侧底部客服 -->
    <div class="float_pic_right flowRight fd_index right_fixed">
        <a href="<?php echo $backup_web_url;?>" target="_blank"></a>
        <a href="tencent://message/?uin=<?php echo getSysConfig('service_qq');?>&amp;Menu=yes" class="qqkf"></a>
        <a href="javascript:;" class="kfdh" title="<?php echo $ess_kf_phone;?>" onclick=" layer.tips('<?php echo $ess_kf_phone;?>', '.kfdh', {tips: [4, 'rgb(66, 60, 60)'], skin: 'layui-msg-tip', time: 5000, area: ['150px', '36px'],});"></a>
        <a href="javascript:;" class="to_livechat"></a>
        <a href="javascript:;" class="close" onclick="$(this).parents('.fd_index').animate({width:'toggle'},350)"></a>
    </div>





