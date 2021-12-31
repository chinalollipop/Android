<?php
    $testPlayFlag = GUEST_LOGIN_MUST_INPUT_PHONE?'to_top_testphone':'to_testplaylogin';
    $display ='style="display: none"';
    $intr= isset($_REQUEST['intr'])?$_REQUEST['intr']:'';  // 介绍人
    if($intr){
        $_SESSION['agent_account'] = $intr;
    }
?>
  <?php
      if(!$uid) { // 未登录
  ?>
          <!-- 登录注册框 -->
          <div class="login-box-index top-login-box">
              <a href="javascript:;" class="close-login"> </a>
              <div class="login-logo"><img src="<?php echo TPL_NAME; ?>images/LOGO.png" alt="8M体育"></div>
              <div class="formBoxAll">
                  <!-- 登录与注册 开始-->
                  <div class="loginRegBox inline-box">
                      <p class="sub-text">最优质人性化的投注体验</p>
                      <div class="login-form">
                          <div class="input-box">
                              <span class="label">账号</span>
                              <div class="login-input el-input">
                                  <input type="text" autocomplete="off"  placeholder="" class="top_username el-input__inner"
                                         minlength="5" maxlength="15">
                              </div>
                          </div>
                          <div class="input-box">
                              <span class="label">密码</span>
                              <div class="login-input el-input">
                                  <input type="password" autocomplete="off" placeholder="" class="top_password el-input__inner"
                                         minlength="6" maxlength="15">
                              </div>
                              <!--<a href="javascript:;" class="show_login extra changeBoxBtn" data-type="forget">忘记密码</a>-->
                              <a href="javascript:;" class="to_forgetpassword extra changeBoxBtn" >忘记密码</a>
                          </div>
                          <!-- 注册 开始-->
                          <input type="hidden" class="top_introducer" value="<?php echo $_SESSION['agent_account'];?>" minlength="4" maxlength="15" autocomplete="off" placeholder="介绍人">
                          <div class="show_register input-box" <?php echo $display;?> >
                              <span class="label">确认密码</span>
                              <div class="login-input el-input">
                                  <input type="password" autocomplete="off" placeholder=""
                                         class="top_password_confirm el-input__inner" minlength="6" maxlength="15">
                              </div>
                          </div>
                          <div class="show_register input-box" <?php echo $display;?> >
                              <span class="label">手机号码</span>
                              <div class="login-input el-input">
                                  <input type="text" autocomplete="off" placeholder=""
                                         class="top_phone el-input__inner" minlength="11" maxlength="11">
                              </div>
                          </div>

                        <!--  <div class="label-login show_register" <?php /*echo $display;*/?> >
                              <input type="checkbox" name="checkbox" id="checkbox_nl" class="rememberme" checked>
                              <label for="checkbox_nl"></label>
                              <span class="login-remember-me"> 我已届满合法博彩年龄，且同意 8M体育各项开户条约，
                                  <span class="account-terms open_agreement">开户协议。</span>
                              </span>
                          </div>-->
                          <!-- 注册 结束-->

                          <div class="label-login show_login">
                              <input type="checkbox" name="checkbox" id="checkbox_a1" class="rememberme">
                              <label for="checkbox_a1"></label>
                              <span class="login-remember-me">记住账号</span>
                          </div>

                          <a href="javascript:;" class="login-submit-btn btn_game login-btn" data-type="login">立即登入</a>
                          <a href="javascript:;" class="show_login btn_game login-btn <?php echo $testPlayFlag; ?>">免费试玩</a>

                      </div>
                  </div>
                  <!-- 登录与注册 结束-->

                  <!--　忘记密码 开始-->
                  <div class="forgetPwdBox inline-box">
                      <ul class="index-forget-Nav clearfix">
                          <li> <span class="btn_game">1</span> 信息验证</li>
                          <li> <span class="btn_game">2</span> 设置新密码</li>
                          <li> <span class="btn_game">3</span> 设置成功</li>
                      </ul>
                      <div class="login-form formWrap_one">
                          <div class="input-box">
                              <span class="label">真实姓名</span>
                              <div class="login-input el-input">
                                  <input type="text" autocomplete="off" placeholder="*须与提款银行户口姓名一致" class="top_forget_zzxm el-input__inner">
                              </div>
                          </div>
                          <div class="input-box">
                              <span class="label">账号</span>
                              <div class="login-input el-input">
                                  <input type="text" autocomplete="off"  placeholder="*须为5~15位英文或数字夹杂" class="top_forget_name el-input__inner" minlength="5" maxlength="15">
                              </div>
                          </div>
                          <div class="input-box">
                              <span class="label">提款密码</span>
                              <div class="login-input el-input">
                                  <input type="password" autocomplete="off" placeholder="*须为六位数字密码" class="top_forget_paypwd el-input__inner" minlength="4" maxlength="6">
                              </div>
                          </div>

                          <div class="input-box">
                              <span class="label">验证码</span>
                              <div class="login-input el-input">
                                  <input type="text" autocomplete="off" placeholder="*请输入验证码" class="top_forget_yzm el-input__inner" minlength="4" maxlength="6">
                              </div>
                              <a href="javascript:;" class="extra" title="点击切换验证码"> <img class="codeImg fl" src="/app/member/include/validatecode/captcha.php" alt="验证码" onclick="this.src='/app/member/include/validatecode/captcha.php?v='+Math.random();"></a>
                          </div>

                          <a href="javascript:;" class="forget-submit-btn btn_game login-btn" data-step="one">提交</a>

                      </div>
                      <div class="login-form formWrap_two" <?php echo $display;?> >
                          <div class="input-box">
                              <span class="label">新密码</span>
                              <div class="login-input el-input">
                                  <input type="password" autocomplete="off"  placeholder="*须为6~15位英文或数字夹杂" class="top_forget_pwd el-input__inner" minlength="6" maxlength="15">
                              </div>
                          </div>
                          <div class="input-box">
                              <span class="label">确认密码</span>
                              <div class="login-input el-input">
                                  <input type="password" autocomplete="off"  placeholder="*须为6~15位英文或数字夹杂" class="top_forget_pwd2 el-input__inner" minlength="6" maxlength="15">
                              </div>
                          </div>
                          <a href="javascript:;" class="forget-submit-btn btn_game login-btn" data-step="two">提交</a>
                      </div>
                  </div>
                  <!-- 忘记密码 结束-->

              </div>
              <!-- 试玩填写手机号 -->
              <div class="formBoxTestAll" <?php echo $display;?> >
                  <p class="sub-text">最优质人性化的投注体验</p>
                  <div class="login-form">
                      <div class="input-box">
                          <span class="label">手机号码</span>
                          <div class="login-input el-input">
                              <input type="text" autocomplete="off" placeholder="请填写试玩手机号码"
                                     class="top_testPlay_phone el-input__inner" minlength="11" maxlength="11">
                          </div>
                      </div>
                      <div class="input-box">
                          <span class="label">验证码</span>
                          <div class="login-input el-input">
                              <input type="text" autocomplete="off" placeholder="*请输入验证码" class="top_testPlay_yzm el-input__inner" minlength="4" maxlength="6">
                          </div>
                          <a href="javascript:;" class="extra" title="点击切换验证码"> <img class="codeImg fl" src="/app/member/include/validatecode/captcha.php" alt="验证码" onclick="this.src='/app/member/include/validatecode/captcha.php?v='+Math.random();"></a>
                      </div>

                      <a href="javascript:;" class="testPlay-submit-btn btn_game login-btn" data-type="login">提交登入试玩</a>
                  </div>
              </div>
              <div class="register-bottom loginReg-btn">
                  还没有<span class="show_login">账号</span><span class="show_register" <?php echo $display;?> >登录</span> ？
                 请 <a href="javascript:;" class="show_reg_btn show_login red bold" data-type="register">注册</a>
                  <a href="javascript:;" class="show_login_btn show_register red bold" data-type="login" <?php echo $display;?> > 登录</a>
              </div>
              <!-- 忘记密码-->
              <div class="register-bottom forget-btn" <?php echo $display;?> >
                  返回<a href="javascript:;" class="changeBoxBtn red" data-type="loginReg">登录</a>？
              </div>

          </div>

   <?php
        }
   ?>

    <!-- 头部 -->
    <div class="header">

        <div class="top clearfix w_1200">
                <div class="left fl">
                   <p> GMT +8 <span class="getAmericaTime"> </span> </p>
                    <p class="top_left_a"> <a href="javascript:;" class="to_index" > 网站首页 </a> <a href="javascript:;" class="to_livechat" > 在线客服 </a> </p>&nbsp;&nbsp;
                </div>
                <a class="to_index logo" href="javascript:;"> </a>
                <div class="right fr">
                    <?php
                    if(!$uid){ // 未登录
                        echo '<a href="javascript:;" class="btn_game login-reg-btn show-top-login-box">登录/注册</a>';
                    }else{
                        echo '<div class="userWrap">
                                            <div class="user_img left" onclick="$(this).next().toggle()">
                                                <span class="dis_for_email_mount_1 css_email_mount" >0</span>
                                             </div>
                                             <div class="show_detail">
                                                  <a href="javascript:;" class="to_usercenter_content" data-to="usercenter" onclick="$(this).parent().hide()">
                                                   个人信息                                    
                                                </a> 
                                                 <a href="javascript:;" class="to_usercenter_content" data-to="email" onclick="$(this).parent().hide()"> 
                                                   信息中心
                                                  </a>   
                                                   <a href="/app/member/logout.php" > 
                                                   退出
                                                  </a>    
                                             </div>
                                             <span class="user_top_right">
                                                 <span > 账号 </span> <span class="color_7387e8" title="'.$username.'"> '.$username.' </span><br>
                                                 <div class="bottom"> <span > 钱包 ￥</span><span class="user_member_amount member_amount"> 加载中...</span> <span class="reload_icon" onclick="indexCommonObj.getUserMoneyAction(uid)"> </span> </div>
                                             </span>
                                            
                                                                   
                                   </div>' ;
                    }
                    ?>

                </div>
        </div>

        <div class="middle">
            <div class="clearfix w_1200">
                <div class="top_user_nav ">
                    <ul class="nav">
                        <li class="nav-drop-ac">
                            <a href="javascript:;" class="to_sports" data-rtype="r" data-showtype="today">体育赛事<!--<p class="en_name">SPORT</p>--></a>

                            <div class="nav-drop">
                                <div class="w_1200">
                                    <div class="left gameTip">
                                        <div class="title">8M体育投注</div>
                                        <div class="tip">国内外多场精彩赛事直播</div>
                                        <p>业内最高返水</p>
                                        <p class="rate">1.2%</p>
                                        <a href="javascript:;" class="btn_game to_promos">了解详情</a>
                                    </div>
                                    <div class="right gameLi">
                                        <a href="javascript:;" class="sport to_sports" data-rtype="r" data-showtype="today">

                                        </a>
                                    </div>

                                </div>
                            </div>
                        </li>
                        <li class="nav-drop-ac">
                            <a href="javascript:;" class="to_dianjing">电竞投注 <!--<p class="en_name">E-SPORT</p>--> </a>

                            <div class="nav-drop">
                                <div class="w_1200">
                                    <div class="left gameTip">
                                        <div class="title">电竞投注</div>
                                        <div class="tip">赛事最全 实时掌握刺激电竞</div>
                                        <p>业内最高返水</p>
                                        <p class="rate">1.1%</p>
                                        <a href="javascript:;" class="btn_game to_promos">了解详情</a>
                                    </div>
                                    <div class="right gameLi">
                                        <a href="javascript:;" class="dzjj" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','/app/member/avia/avia_api.php?action=getLaunchGameUrl')">
                                            <div class="bottom">
                                                <span class="icon"></span>
                                                <span>泛亚电竞</span>
                                            </div>
                                        </a>
                                        <a href="javascript:;" class="dzjj_lh" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','/app/member/thunfire/fire_api.php?action=getLaunchGameUrl')">
                                            <div class="bottom">
                                                <span class="icon"></span>
                                                <span>雷火电竞</span>
                                            </div>
                                        </a>
                                        <a class="none"> </a>
                                        <a class="none"> </a>
                                        <a class="none"> </a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="nav-drop-ac">
                            <a href="javascript:;" class=" to_lives" >真人娱乐 <!--<p class="en_name">CASINO</p>--></a>

                            <div class="nav-drop">
                                <div class="w_1200">
                                    <div class="left gameTip">
                                        <div class="title">真人娱乐</div>
                                        <div class="tip">美女主播游戏娱乐化一体</div>
                                        <p>业内最高返水</p>
                                        <p class="rate">1.0%</p>
                                        <a href="javascript:;" class="btn_game to_promos">了解详情</a>
                                    </div>
                                    <div class="right gameLi">
                                        <a href="javascript:;" class="ag" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/login.php?uid=<?php echo $uid;?>')" >
                                            <div class="bottom">
                                                <span class="icon"></span>
                                                <span>AG真人视讯</span>
                                            </div>
                                        </a>
                                        <a href="javascript:;" class="og" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/og/og_api.php?action=getLaunchGameUrl')" >
                                            <div class="bottom">
                                                <span class="icon"></span>
                                                <span>OG真人视讯</span>
                                            </div>
                                        </a>
                                        <a href="javascript:;" class="bbin" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/bbin/bbin_api.php?action=getLaunchGameUrl')" >
                                            <div class="bottom">
                                                <span class="icon"></span>
                                                <span>BBIN真人视讯</span>
                                            </div>
                                        </a>
                                        <a class="none"> </a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="nav-drop-ac">
                            <a href="javascript:;" class="to_lotterys">彩票投注 <!--<p class="en_name">LOTTERY</p>--></a>

                            <div class="nav-drop">
                                <div class="w_1200">
                                    <div class="left gameTip">
                                        <div class="title">8M彩票游戏</div>
                                        <div class="tip">彩票新玩法，颠覆传统</div>
                                        <p>业内最高返水</p>
                                        <p class="rate">1.1%</p>
                                        <a href="javascript:;" class="btn_game to_promos">了解详情</a>
                                    </div>
                                    <div class="right gameLi">
                                        <a href="javascript:;" class="gfcp to_lotterys">
                                            <div class="bottom">
                                                <span class="icon"></span>
                                                <span>官方彩票</span>
                                            </div>
                                        </a>
                                        <a href="javascript:;" class="xycp to_lotterys">
                                            <div class="bottom">
                                                <span class="icon"></span>
                                                <span>信用彩票</span>
                                            </div>
                                        </a>
                                        <a class="none"> </a>
                                        <a class="none"> </a>
                                    </div>

                                </div>
                            </div>
                        </li>

                        <li class="nav-drop-ac">
                            <a href="javascript:;" class=" to_games">电子游艺<!--<p class="en_name">SLOTS</p>--></a>

                            <div class="nav-drop">
                                <div class="w_1200">
                                    <div class="left gameTip">
                                        <div class="title">8M电子游戏</div>
                                        <div class="tip">一拉即中，幸运夺彩金</div>
                                        <p>业内最高返水</p>
                                        <p class="rate">2.0%</p>
                                        <a href="javascript:;" class="btn_game to_promos">了解详情</a>
                                    </div>
                                    <div class="right gameLi">
                                        <a href="javascript:;" class="agdz to_games" >
                                            <div class="bottom">
                                                <span class="icon"></span>
                                                <span>AG电子</span>
                                            </div>
                                        </a>
                                        <a href="javascript:;" class="fgdz to_games" data-type="fg">
                                            <div class="bottom">
                                                <span class="icon"></span>
                                                <span>FG电子</span>
                                            </div>
                                        </a>
                                        <a href="javascript:;" class="mgdz to_games" data-type="mg">
                                            <div class="bottom">
                                                <span class="icon"></span>
                                                <span>MG电子</span>
                                            </div>
                                        </a>
                                        <a href="javascript:;" class="cqdz to_games" data-type="cq">
                                            <div class="bottom">
                                                <span class="icon"></span>
                                                <span>CQ9电子</span>
                                            </div>
                                        </a>
                                        <a href="javascript:;" class="mwdz to_games" data-type="mw">
                                            <div class="bottom">
                                                <span class="icon"></span>
                                                <span>大满贯电子</span>
                                            </div>
                                        </a>

                                    </div>

                                </div>
                            </div>
                        </li>

                        <li class="nav-drop-ac">
                            <a href="javascript:;" class=" to_chess">棋牌游戏 <!--<p class="en_name">CHESS</p>--></a>

                            <div class="nav-drop">
                                <div class="w_1200">
                                    <div class="left gameTip">
                                        <div class="title">专业棋牌游戏</div>
                                        <div class="tip">棋乐无穷，自然乐在棋中</div>
                                        <p>业内最高返水</p>
                                        <p class="rate">0.8%</p>
                                        <a href="javascript:;" class="btn_game to_promos">了解详情</a>
                                    </div>
                                    <div class="right gameLi">
                                        <a href="javascript:;" class="chess_ly" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/lyqp/index.php?uid=<?php echo $uid;?>')" >
                                            <div class="bottom">
                                                <span class="icon"></span>
                                                <span>LEG棋牌</span>
                                            </div>
                                        </a>
                                        <a href="javascript:;" class="chess_vg" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/vgqp/index.php?uid=<?php echo $uid;?>')" >
                                            <div class="bottom">
                                                <span class="icon"></span>
                                                <span>VG棋牌</span>
                                            </div>
                                        </a>
                                        <a href="javascript:;" class="chess_ky" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/ky/index.php?uid=<?php echo $uid;?>')" >
                                            <div class="bottom">
                                                <span class="icon"></span>
                                                <span>KY棋牌</span>
                                            </div>
                                        </a>
                                        <a href="javascript:;" class="chess_kl" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/klqp/index.php?uid=<?php echo $uid;?>')" >
                                            <div class="bottom">
                                                <span class="icon"></span>
                                                <span>快乐棋牌</span>
                                            </div>
                                        </a>
                                        <a class="none"> </a>
                                    </div>

                                    <!--<a href="javascript:;" class="" onclick="indexCommonObj.openGameCommon(this,'<?php /*echo $uid;*/?>','../../app/member/hgqp/index.php?uid=<?php /*echo $uid;*/?>')" > <img src="<?php /*echo TPL_NAME;*/?>images/navxl/chess_hg.png"> </a>-->

                                </div>
                            </div>
                        </li>

                        <li class="nav-drop-ac">
                            <a href="javascript:;" class="to_fish">捕鱼游戏 <!--<p class="en_name">FISHING</p>--></a>

                            <div class="nav-drop">
                                <div class="w_1200">
                                    <div class="left gameTip">
                                        <div class="title">AG捕鱼游戏</div>
                                        <div class="tip">千枪万炮齐捕鱼，称霸海洋世界</div>
                                        <p>业内最高返水</p>
                                        <p class="rate">2.0%</p>
                                        <a href="javascript:;" class="btn_game to_promos">了解详情</a>
                                    </div>
                                    <div class="right gameLi">
                                        <a href="javascript:;" class="agby to_fish">
                                            <div class="bottom">
                                                <span class="icon"></span>
                                                <span>AG捕鱼游戏</span>
                                            </div>
                                        </a>
                                        <a class="none"> </a>
                                        <a class="none"> </a>
                                        <a class="none"> </a>
                                    </div>

                                </div>
                            </div>
                        </li>
                        <li>
                            <a href="javascript:;" class="to_promos">优惠活动 <!--<p class="en_name">PROMOS</p>--> </a>
                        </li>
                        <li><a href="javascript:;" class="to_agentreg">代理加盟 <!--<p class="en_name">PROXY</p>--> </a></li>
                        <li>
                            <a href="javascript:;" class="to_downloadapp">APP下载 <!--<p class="en_name">MOBILE</p>--> </a>
                          <!--  <div class="nav-drop">
                                <div class="w_900">
                                    <a href="javascript:;" class="to_downloadapp"> <img src="<?php /*echo TPL_NAME;*/?>images/navxl/app.png"> </a>
                                </div>
                            </div>-->
                        </li>

                    </ul>

                </div>
            </div>
        </div>

    </div>
    <div style="height:128px"></div>




