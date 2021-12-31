

    <div class="cms_main_nav">
        <div class="cms_black_top_nav">
        </div>
        <div class="cms_uti_navi">
            <div class="cms_top-menu cms_short_no">
                <div class="cms_greylink"><a href="javascript:;" class="to_downloadapp" >手机版</a></div>
            </div>
            <div class="cms_top-menu cms_short_no" >
                <div class="top_line" >&nbsp;</div>
            </div>
            <div class="cms_top-menu cms_short_no">
                <div class="cms_greylink"><a href="javascript:;" class="to_aboutus" >帮助中心</a></div>
            </div>
            <div class="cms_top-menu cms_short_no" >
                <div class="top_line" >&nbsp;</div>
            </div>
            <div class="cms_top-menu cms_short_no">
                <div class="cms_greylink"><a href="javascript:;" class="to_livechat">在线客服</a></div>
            </div>

            <?php
                if(!$uid){
                    echo ' <div class="cms_top-menu cms_short_no" >
                              <div class="top_line" >&nbsp;</div>
                           </div>
                           <div class="cms_top-menu cms_short_no">
                             <!--<div class="cms_greylink"><a href="javascript:;" class="to_forgetpassword">忘记密码</a></div>-->
                             <div class="cms_greylink"><a href="javascript:;" class="to_livechat">忘记密码</a></div>
                           </div>' ;
                }
            ?>

            <div class="cms_top-menu cms_short_no">
                <div style="width:20px;"></div>
            </div>
        </div>

        <div class="cms_navi_panel">
            <div class="cms_nav_small">
                <a ><div class="cms_main_logo"></div></a>
                <div class="cms_menu_holder">
                    <div class="cms_top-menu cms_main-menu active">
                        <a href="javascript:;" class="to_index">
                            首页
                        </a>

                    </div>
                    <div class="cms_top-menu cms_main-menu">
                        <a href="javascript:;" class="to_sports" data-rtype="r" data-showtype="today" >
                            体育竞技
                            <i class="hotIcon"></i>
                        </a>
                    </div>
                    <div class="cms_top-menu cms_main-menu">
                        <a href="javascript:;"  class="to_sports" data-rtype="re" data-showtype="rb" >滚球盘</a>
                    </div>
                   <!-- <div class="cms_top-menu cms_main-menu">
                        <a href="<?php /*echo $_SESSION['toOldLogin'];*/?>" >旧版体育</a>
                    </div>-->
                    <div class="nav-drop-ac cms_top-menu cms_main-menu">
                        <a href="javascript:;" class="to_lives">真人视讯</a>
                        <div class="nav-drop" >
                            <div class="w_900">
                                <a href="javascript:;" class="" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','/app/member/zrsx/login.php?uid=<?php echo $uid;?>')" > <img src="<?php echo TPL_NAME;?>images/navxl/ag_live.png"> </a>
                                <a href="javascript:;" class="" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','/app/member/zrsx/og/og_api.php?action=getLaunchGameUrl')" > <img src="<?php echo TPL_NAME;?>images/navxl/og_live.png"> </a>
                                <a href="javascript:;" class="" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','/app/member/zrsx/bbin/bbin_api.php?action=getLaunchGameUrl')" > <img src="<?php echo TPL_NAME;?>images/navxl/bbin_live.png"> </a>
                            </div>
                        </div>
                    </div>
                    <div class="cms_top-menu cms_main-menu">
                        <a href="javascript:;" class="to_lotterys">
                            彩票游戏
                            <i class="hotIcon"></i>
                        </a>
                    </div>
                    <div class="cms_top-menu cms_main-menu">
                        <a href="javascript:;" class="to_fish">
                            捕鱼游戏

                        </a>
                    </div>
                    <div class="nav-drop-ac cms_top-menu cms_main-menu">
                        <a href="javascript:;" class="to_games">电子游艺</a>
                        <div class="nav-drop" >
                            <div class="w_900">
                                <a href="javascript:;" class="to_games" > <img src="<?php echo TPL_NAME;?>images/navxl/ag_game.png"> </a>
                                <a href="javascript:;" class="to_games" data-type="mg"> <img src="<?php echo TPL_NAME;?>images/navxl/mg_game.png"> </a>
                                <a href="javascript:;" class="to_games" data-type="mw"> <img src="<?php echo TPL_NAME;?>images/navxl/mw_game.png"> </a>
                                <a href="javascript:;" class="to_games" data-type="cq"> <img src="<?php echo TPL_NAME;?>images/navxl/cq_game.png"> </a>
                                <a href="javascript:;" class="to_games" data-type="fg"> <img src="<?php echo TPL_NAME;?>images/navxl/fg_game.png"> </a>
                            </div>
                        </div>
                    </div>
                    <div class="nav-drop-ac cms_top-menu cms_main-menu">
                        <a href="javascript:;" class="to_dianjing">
                            电子竞技
                            <i class="hotIcon"></i>
                        </a>
			<div class="nav-drop">
                            <div class="w_900">
                                <a href="javascript:;" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','/app/member/avia/avia_api.php?action=getLaunchGameUrl')" > <img src="<?php echo TPL_NAME;?>images/navxl/dj_fa.png"> </a>
                                <a href="javascript:;" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','/app/member/thunfire/fire_api.php?action=getLaunchGameUrl')" > <img src="<?php echo TPL_NAME;?>images/navxl/dj_lh.png"> </a>
                            </div>
                        </div>
                    </div>
                    <div class="nav-drop-ac cms_top-menu cms_main-menu">
                        <a href="javascript:;" class="to_chess">
                            棋牌游戏
                            <i class="hotIcon"></i>
                        </a>
                        <div class="nav-drop" >
                            <div class="w_900">
                                <a href="javascript:;" class="" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','/app/member/ky/index.php?uid=<?php echo $uid;?>')" > <img src="<?php echo TPL_NAME;?>images/navxl/chess_ky.png"> </a>
                                <a href="javascript:;" class="" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','/app/member/lyqp/index.php?uid=<?php echo $uid;?>')" > <img src="<?php echo TPL_NAME;?>images/navxl/chess_ly.png"> </a>
                                <!--<a href="javascript:;" class="" onclick="indexCommonObj.openGameCommon(this,'<?php /*echo $uid;*/?>','/app/member/hgqp/index.php?uid=<?php /*echo $uid;*/?>')" > <img src="<?php /*echo TPL_NAME;*/?>images/navxl/chess_hg.png"> </a>-->
                                <a href="javascript:;" class="" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','/app/member/vgqp/index.php?uid=<?php echo $uid;?>')" > <img src="<?php echo TPL_NAME;?>images/navxl/chess_vg.png"> </a>
                                <a href="javascript:;" class="" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','/app/member/klqp/index.php?uid=<?php echo $uid;?>')" > <img src="<?php echo TPL_NAME;?>images/navxl/chess_kl.png"> </a>
                            </div>
                        </div>
                    </div>
                    <div class="cms_top-menu cms_main-menu ">
                        <a href="javascript:;" class="to_promos">
                            优惠活动
                            <i class="hotIcon"></i>
                        </a>
                    </div>
                    <div class="cms_top-menu cms_main-menu">
                        <a href="javascript:;" class="to_agentreg" data-index="0">代理加盟</a>
                    </div>

                    <div style="position:absolute; top:0; right:0;">
                        <div class="cms_top-menu top_user_nav" style="padding:0;">
                            <?php
                                if(!$uid){ // 未登录
                                    echo '<a href="javascript:;" class="to_memberreg cms_openacc_btn" style="float:right;">免费开户</a>
                                           <a href="javascript:;" class="to_memberlogin cms_logout_btn" style="float: right; ">登录</a>' ;
                                }else{
                                    echo '<div class="userWrap clearfix">
                                            <a href="javascript:;" class="cm_icon fl reload" onclick="indexCommonObj.getUserMoneyAction(uid)"></a>
                                            <div class="userName fl">
                                                <p class="p_name" title="'.$username.'">您好,'.$username.'</p>
                                                <p class="num"><span> 余额: </span><span class="user_member_amount member_amount" title="0">加载中...</span></p>
                                            </div>
                                            <a href="javascript:;" class="cm_icon fl topnav_add" ></a>
                                            <a href="javascript:;" class="cm_icon fl topnav_money" ></a>
                                            <a href="javascript:;" class="to_user_email cm_icon fl email" > <span class="for_email_mount css_email_mount" style="display: none;"> </span> </a>                                      
                                            <a href="javascript:;" class="cm_icon fl topnav_user" ></a>
                                              <div class="show_balance balance-info-all font_ch" >
                                                <span class=" top-sub-list-item"> 彩票余额: <b class="user_member_lottery_amount"> 0 </b> </span>
                                                <!--<span class=" top-sub-list-item"> 彩票余额: <b class="user_member_third_lottery_amount"> 0 </b> </span>-->
                                                <span class=" top-sub-list-item"> AG余额: <b class="user_member_ag_amount"> 0 </b> </span>                                        
                                                <span class=" top-sub-list-item"> OG余额: <b class="user_member_og_amount"> 0 </b> </span>
                                                <span class=" top-sub-list-item"> BBIN余额: <b class="user_member_bbin_amount"> 0 </b> </span>                                         
                                                <span class=" top-sub-list-item"> 开元余额: <b class="user_member_ky_amount"> 0 </b> </span>                                        
                                                <span class=" top-sub-list-item"> 乐游余额: <b class="user_member_ly_amount"> 0 </b> </span>                                        
                                                <span class=" top-sub-list-item"> VG余额: <b class="user_member_vg_amount"> 0 </b> </span>
                                                <span class=" top-sub-list-item"> 快乐棋牌余额: <b class="user_member_kl_amount"> 0 </b> </span>                                          
                                                <!--<span class=" top-sub-list-item"> 皇冠余额: <b class="user_member_hg_amount"> 0 </b> </span>-->                                        
                                                <span class=" top-sub-list-item"> MG余额: <b class="user_member_mg_amount"> 0 </b> </span>
                                                <span class=" top-sub-list-item"> CQ9余额: <b class="user_member_cq_amount"> 0 </b> </span>                                        
                                                <span class=" top-sub-list-item"> MW电子余额: <b class="user_member_mw_amount"> 0 </b> </span>
                                                <span class=" top-sub-list-item"> FG电子余额: <b class="user_member_fg_amount"> 0 </b> </span>
                                                <span class=" top-sub-list-item"> 泛亚电竞余额: <b class="user_member_avia_amount"> 0 </b> </span>
                                                <span class=" top-sub-list-item"> 雷火电竞余额: <b class="user_member_fire_amount"> 0 </b> </span>
                                             </div>
                                            <div class="show_operate operate font_ch" >
                                                <a href="javascript:;" class="to_deposit top-sub-list-item">存款</a>
                                                <a href="javascript:;" class="to_withdraw top-sub-list-item">提款</a>
                                                <a href="javascript:;" class="to_platform_tranfer top-sub-list-item">额度转换</a>
                                             </div>
                                             <div class="show_personal personal font_ch">
                                                <a href="javascript:;" class="to_userbetaccount top-sub-list-item">账户记录</a>
                                                <a href="javascript:;" class="to_usercenter top-sub-list-item">我的账户</a>
                                                <a href="/app/member/logout.php" class=" top-sub-list-item">退出</a>
                                              </div>
                                         </div>' ;
                                }
                            ?>


                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div style="height:85px;background: #333"></div>
