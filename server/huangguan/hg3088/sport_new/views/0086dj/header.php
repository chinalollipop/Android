
    <div class="cms_main_nav_df">

        <div class="cms_navi_panel">
            <div class="cms_nav_small">
                <a ><div class="top_logo"></div></a>
                <div class="cms_menu_holder">

                    <div class="cms_top-menu cms_main-menu">
                        <a href="javascript:;" class="lhdj_btn" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','/app/member/thunfire/fire_api.php?action=getLaunchGameUrl')" >
                            雷火电竞
                        </a>

                    </div>

                    <div class="cms_top-menu cms_main-menu">
                        <a href="javascript:;" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','/app/member/avia/avia_api.php?action=getLaunchGameUrl')" >
                            泛亚电竞
                        </a>
                    </div>
                    <div class="cms_top-menu cms_main-menu">
                        <a href="javascript:;" class="to_sports" data-rtype="r" data-showtype="today" >
                            体育竞技
                            <!--<i class="hotIcon"></i>-->
                        </a>
                    </div>

                    <div class="cms_top-menu cms_main-menu">
                        <a href="javascript:;" class="to_lives">真人视讯</a>
                    </div>

                    <div class="cms_top-menu cms_main-menu ">
                        <a href="javascript:;" class="to_promos">
                            优惠活动
                            <!--<i class="hotIcon"></i>-->
                        </a>
                    </div>
                    <div class="cms_top-menu cms_main-menu">
                        <a href="javascript:;" class="to_agentreg" data-index="0">代理加盟</a>
                    </div>
                    <div class="cms_top-menu cms_main-menu">
                        <a href="javascript:;" class="to_lives_upgraded"  data-keys="live">账号升级</a>
                    </div>
                    <div class="cms_top-menu cms_main-menu">
                        <a href="javascript:;" class="to_livechat" >在线客服</a>
                    </div>
                    <div class="cms_top-menu cms_main-menu">
                        <a href="javascript:;" class="to_deposit" >在线存款 <i class="hotIcon"></i> </a>
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
    <div style="height:55px;"></div>

