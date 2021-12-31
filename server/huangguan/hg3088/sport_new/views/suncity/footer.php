
    <!-- 公用底部 -->
    <div class="footer">

        <div class="clearfix">
            <div class="index_part3">
                <div class="pz_content">
                    <div class="coverflow-container">
                        <ol class="coverflow-list">
                            <!-- Cover item -->
                            <input type="radio" name="cover-item" id="cover-1">
                            <li class="coverflow-item">
                                <label for="cover-1">
                                    <figure class="album-cover">
                                        <img src="<?php echo TPL_NAME;?>images/index/macao.png">
                                    </figure>
                                </label>
                            </li>
                            <!-- Cover item -->
                            <input type="radio" name="cover-item" id="cover-2" checked>
                            <li class="coverflow-item">
                                <label for="cover-2">
                                    <figure class="album-cover">
                                        <img src="<?php echo TPL_NAME;?>images/index/suncity.png">
                                    </figure>
                                </label>
                            </li>
                            <!-- Cover item -->
                            <input type="radio" name="cover-item" id="cover-3">
                            <li class="coverflow-item">
                                <label for="cover-3">
                                    <figure class="album-cover">
                                        <img  src="<?php echo TPL_NAME;?>images/index/phl.png">
                                    </figure>
                                </label>
                            </li>

                        </ol>
                    </div>
                </div>

            </div>
            <div class="foot_zf_all">
                <span class="foot_zf"></span>
            </div>

        </div>
        <div class="w_1000">
            <div class="footer_bottom">
                <div class="footer_about">
                    <a href="javascript:;" class="to_aboutus" data-index="0">关于我们</a> &nbsp;|&nbsp;
                    <a href="javascript:;" class="to_aboutus" data-index="2">存款帮助</a> &nbsp;|&nbsp;
                    <a href="javascript:;" class="to_aboutus" data-index="2">取款帮助</a> &nbsp;|&nbsp;
                    <a href="javascript:;" class="to_aboutus" data-index="4">常见问题</a> &nbsp;|&nbsp;
                    <a href="javascript:;" class="to_aboutus" data-index="7">责任博彩</a> &nbsp;|&nbsp;
                    <a href="javascript:;" class="to_downloadapp" >手机APP</a> &nbsp;|&nbsp;
                    <a href="javascript:;" class="to_agentreg" >代理加盟</a>

                </div>
                <p class="footer_tip">
                    本站属于菲律宾卡格扬 (Cagayan) 授权和监管所有版权归<?php echo COMPANY_NAME;?>所有，违者必究。

                </p>
                <p class="footer_bottom_p"> Copyright © 2006-<?php echo date('Y');?> SANDS MACAU ALL RIGHT Reserved.</p>
            </div>
        </div>

    </div>


    <!-- 右侧底部客服 -->
    <!-- 右侧底部客服 -->
    <div class="kf-float flowLeft float_pic_left">
        <a class="gw" href="javascript:;"></a>
        <a class="app to_downloadapp" href="javascript:;"></a>
        <a class="dlqq" href="tencent://message/?uin=<?php echo getSysConfig('agents_service_qq');?>&amp;Site=web&amp;Menu=yes"></a>
        <a class="kscz to_usercenter_content" href="javascript:;" data-to="deposit"></a>
        <a class="close" href="javascript:;" onclick="$(this).parents('.kf-float').animate({width:'toggle'},200)"></a>
    </div>
    <div class="kf-float flowRight float_pic_right">
        <a class="gw" href="javascript:;"></a>
        <a class="kf to_livechat" href="javascript:;"></a>
        <a class="kfqq" href="tencent://message/?uin=<?php echo getSysConfig('service_qq');?>&amp;Site=web&amp;Menu=yes"></a>
        <a class="gjrx" href="javascript:;"></a>
        <a class="close" href="javascript:;" onclick="$(this).parents('.kf-float').animate({width:'toggle'},200)"></a>
    </div>

    <!-- 隐藏底部广告窗 -->
    <!--<div style="display: none;" class="hide-index-tg show_index_bg" onclick="parent.indexCommonObj.rightBottomAd(uid)">
        <span> 在线-即时帮助 </span>
        <span class="icon_bottom">  </span>
    </div>-->




