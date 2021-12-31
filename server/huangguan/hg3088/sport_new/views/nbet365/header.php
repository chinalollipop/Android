<?php
    $loginVerifyRealname = getSysConfig('login_verify_realname');
?>
<div id="header">
    <div id="page-header">
        <div id="header-logo">
            <a href="/"><img src="<?php echo TPL_NAME;?>images/logo.png" width="160" height="69" border="0"></a>
        </div>
        <div id="headsp" class="hsp">
            <ul id="main-Menual">
                <li class="nav-index"><a href="javascript:;" class="to_index cur">首页</a></li>
                <li class="nav-sports"><a href="javascript:;" class="to_sports" data-rtype="r" data-showtype="today">体育投注</a></li>
                <li class="LS-es"><a href="javascript:;" >电子竞技</a></li>
                <li class="LS-live"><a href="javascript:;" class="to_lives">娱乐场</a></li>
                <li class="nav-ele"><a href="javascript:;" class="to_games">电子游戏</a></li>
                <li class="nav-lot"><a href="javascript:;" class="to_lotterys">彩票</a></li>
                <li class="nav-vg"><a href="javascript:;" class="to_chess">棋牌游戏</a></li>
                <li class="nav-pre"><a href="javascript:;" class="to_promos">促销</a></li>
            </ul>
            <div id="L-Sub">
                <div class="nav-sports" style="left: 47px; display: none;">
                    <span class="subbg-left"></span>
                    <a href="javascript:;" class="to_sports nsp" data-rtype="r" data-showtype="today">皇冠体育</a>
                    <span class="subbg-right"></span>
                </div>
                <div class="LS-live" style="left: 0px; display: none;">
                    <span class="subbg-left"></span>
                    <a href="javascript:;" class="nag" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/login.php?uid=<?php echo $uid;?>')">AG国际馆</a>
                    <a href="javascript:;" class="nop" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/og/og_api.php?action=getLaunchGameUrl')">OG馆</a>
                    <a href="javascript:;" class="nbbin" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/bbin/bbin_api.php?action=getLaunchGameUrl')" >BBIN馆</a>
                    <span class="subbg-right"></span>
                </div>


                <div class="nav-ele" style="left: 0px; display: none;">
                    <span class="subbg-left"></span>
                    <a href="javascript:;" class="to_games dzcq" data-type="cq">CQ9电子</a>
                    <a href="javascript:;" class="to_games dzmgn" data-type="mg">新MG电子</a>
                    <a href="javascript:;" class="to_games dzag" data-type="ag">AG电子</a>
                    <a href="javascript:;" class="to_games dzmw" data-type="mw">MW电子</a>
                    <a href="javascript:;" class="to_games dzfg" data-type="fg">FG电子</a>
                    <a href="javascript:;" class="qp_ag" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/login.php?uid=<?php echo $uid; ?>&gameid=6')">捕鱼游戏</a>
                    <span class="subbg-right"></span>
                </div>

                <div class="nav-lot" style="left: 288px; display: none;">
                    <span class="subbg-left"></span>
                    <a href="javascript:;" class="ntt" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','<?php echo $cpUrl;?>')">天天彩票</a>
                    <span class="subbg-right"></span>
                </div>
                <div class="LS-es" style="left: 95.5px; display: none;">
                    <a href="javascript:;" class="nfy" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../app/member/avia/avia_api.php?action=getLaunchGameUrl')">泛亚电竞</a>
                    <a href="javascript:;" class="nlh" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../app/member/thunfire/fire_api.php?action=getLaunchGameUrl')">雷火电竞</a>
                </div>

            </div>

        </div>
        <div id="headtop">
            <?php
                if(!$uid){ // 未登录
                    echo '
                    <div style="width:250px; height:auto; float:left; margin-left:20px;">
                    <div class="loginbox1">

                        <div class="login_ip">
                            <input name="username" type="text" tabindex="1" minlength="5" maxlength="15" class="top_username login_input01" placeholder="用户名">
                        </div>
                        <div class="login_ip">
                            <input name="password" type="password" tabindex="2" minlength="6" maxlength="15" class="top_password login_input01" placeholder="密码">
                        </div>
                        <input type="button" name="button" class="ibtnLogin login-submit-btn" value="登录">
                    </div>
                    <div class="clear"></div>';
                    if ($loginVerifyRealname==1) {
                        echo '<div class="loginbox_name loginbox1">
                            <div class="login_ip" style="padding-top: 0;width: 196px;padding-left: 4px;"><input type="text" style="width: 100%;" class="login_input01 top_realname" name="realname" maxlength="15" autocomplete="off" placeholder="请输入阁下账户名字"></div>
                            <!--<div style="color:red;font-size: 12px;">注：请输入阁下账户名字，以确保是本人操作！</div>-->
                        </div>
                        <div class="clear"></div>';
                    }
                    echo '<div id="loginbox2">
                        <div id="regbox"><a href="javascript:;" class="to_memberreg">立即注册</a></div>                       
                        <div id="fpwdbox"><a href="javascript:;" class="to_livechat" style="color: #85B9AB;">忘记密码?</a></div>
                        <div id="facbox"><a href="javascript:;" onclick="addUrlFavorite()">收藏本站</a></div>
                    </div>
                </div>
                <div id="loginbox3" style=" width:50px; float:left; height:auto;">
                    <div id="idxkefu">
                        <a class="to_livechat popupkefu" ></a>
                    </div>
                </div>';
                }else{
                    echo '<div id="userbox">
						<div id="userx1">您好,'.$username.' 余额：￥<span class="user_member_amount"> </span></div>
						<div id="userx2">
							  <a href="javascript:;" class="to_usercenter_content" data-to="usercenter">会员中心</a>
							  <a href="javascript:;" class="to_usercenter_content" data-to="tranfer">额度转换</a>
							  <a href="javascript:;" class="to_usercenter_content" data-to="deposit">在线存款</a>
							  <a href="javascript:;" class="to_usercenter_content" data-to="withdraw">在线取款</a>
							  <a href="/app/member/logout.php" style="color:#ffc810">退出登录</a>
						</div>
					</div>';

                }
            ?>

        </div>
    </div>
    <div id="page-header-bottom">
        <div class="data getAmericaTime" > </div>
        <div class="noticet">公告：</div>
        <div class="noticec">
            <marquee id="msgNews" scrollamount="5" scrolldelay="100" direction="left" onmouseover="this.stop();" onmouseout="this.start();" style="color: #FFF">
                <?php echo $_SESSION['memberNotice']; ?>
            </marquee>
        </div>

        <span class="head_menu">
                <ul>
                    <li><span class="contact_ico" style="margin: 2px 3px 0px 0px;"></span>
                        <a class="to_livechat" style="color: #85b9ab;">在线咨询</a>
                        <!-- 在线客服图标:在线咨询 结束-->
                    </li>
  <li class="lang" style="position:relative;">
      <span class="left">语言</span> <span class="huang lang_ico">
          <a href="javascript:;" style="color: #FF0">简体中文</a>
      </span>
      <div class="Language_fname">
        <span class="icondic"></span>
        <ul id="Language">
          <li><a href="#">English</a></li>
          <li><a href="#">Espa?ol</a></li>
          <li><a href="#">Deutsch</a></li>
          <li><a href="#">Italiano</a></li>
          <li><a href="#">Dansk</a></li>
          <li><a href="#">Svenska</a></li>
          <li><a href="#">Norsk</a></li>
          <li><a href="#">繁體中文</a></li>
          <li>简体中文</li>
          <li><a href="#">Български</a></li>
          <li><a href="#">Ελληνικ?</a></li>
          <li><a href="#">Português</a></li>
          <li><a href="#">Polski</a></li>
          <li><a href="#">Roman?</a></li>
          <li><a href="#">?esky</a></li>
          <li><a href="#">Magyar</a></li>
          <li><a href="#">Sloven?ina</a></li>
        </ul>
 </div></li>
 <li class="service" style="position:relative;">
     <span class="loss_ico"><a href="javascript:;" style="color: #85b9ab;">服务</a>
     </span>
      <div class="service_fname">
        <span class="icondic"></span>
        <ul id="service">
          <li><a href="javascript:;" class="to_aboutus" data-index="1">联系我们</a></li>
          <li><a href="javascript:;" class="to_aboutus" data-index="5">帮助</a></li>
          <li><a href="javascript:;" class="to_aboutus" data-index="2">博彩责任</a></li>
          <li><a href="javascript:;" class="to_aboutus" data-index="4">银行</a></li>
        </ul>
        </div>
      </li>
	 <!-- <li style="position:relative;">
          <a class="to_line_sense" href="javascript:;" style="color: #85b9ab;">备用网址</a>
      </li>-->
        <li style="position:relative;">
            <a class="to_downloadapp" href="javascript:;" id="cclo" style="font-weight: bold; color: blue;">APP下载</a>
        </li>
    </ul>
</span>

    </div>
</div>

<div class="clear"></div>




