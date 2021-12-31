<?php
session_start();
require ("../include/config.inc.php");
// 会员注册控制必填字段
$redisObj = new Ciredis();
$registerConf = $redisObj->getSimpleOne('member_register_set');
$registerSet = json_decode($registerConf, true);
?>
<style>
    .khxy_content{display:none;width:600px;position:absolute;left:50%;margin:0 -300px 0;background:#fff;border-radius:10px;padding:20px}
    .terms-content li{margin:6px 0;line-height:1.5;color:#6e6e6e}
    .terms-button-wrapper{text-align: center;}
    .terms-button-wrapper .terms-button{color:#fff;background-color:#404040;border-radius:5px;display:inline-block;padding:15px;margin:20px auto;cursor:pointer;}
</style>

<!-- 主体 -开始 -->
<!-- 开户协议 -->
<div class="khxy_content">
<ul class="terms-content">
    <li>                <span class="brand-name-txt"></span>
        只接受合法博彩年龄的客户申请。同时我们保留要求客户提供其年龄证明的权利。
    </li>
    <li>在进行注册时所提供的全部信息必须在各个方面都是准确和完整的。在使用借记卡或信用卡时，持卡人的姓名必须与在网站上注册时的一致。            </li>
    <li>在开户后进行一次有效存款，恭喜您成为有效会员!            </li>
    <li>存款免手续费，开户最低入款金额100人民币</li>
    <li>成为有效会员后，客户有责任以电邮、联系在线客服、在网站上留言等方式，随时向本公司提供最新的个人资料。            </li>
    <li>经发现会员有重复申请账号行为时，有权将这些账户视为一个联合账户。我们保留取消、收回会员所有优惠红利，以及优惠红利所产生的盈利之权利。每位玩家、每一住址、每一电子邮箱、 每一电话号码、相同支付卡/信用卡号码，以及共享计算机环境 (例如:网吧、其他公共用计算机等)只能够拥有一个会员账号，各项优惠只适用于每位客户在 唯一的账户。            </li>
    <li>                <span class="brand-name-txt"></span>是提供互联网投注服务的机构。请会员在注册前参考当地政府的法律，在博彩不被允许的地区，如有会员在 注册、下注，为会员个人行为， 不负责、承担任何相关责任。            </li>
    <li>无论是个人或是团体，如有任何威胁、滥用优惠的行为，保留权利取消、收回由优惠产生的红利，并保留权利追讨最高50%手续费。            </li>
    <li>所有的优惠是特别为玩家而设，在玩家注册信息有争议时，为确保双方利益、杜绝身份盗用行为，保留权利要求客户向我们提供充足有效的证件， 并以各种方式辨别客户是否符合资格享有我们的任何优惠。            </li>
    <li>客户一经注册开户，将被视为接受所有颁布在 网站上的规则与条例。            </li>
    <li>本公司是使用 现金网所提供的在线娱乐软件，若发现您在同系统的娱乐城上开设多个会员账户，并进行套利下注；本公司有权取消您的会员账号及下注盈利所得！            </li>
</ul>
    <div class="terms-button-wrapper" >
        <span class="terms-button" onclick="$('.khxy_content').hide()">          我已满合法博彩年龄並同意各项开户条约        </span>
    </div>
</div>
<!-- 开户协议结束 -->
<div style="clear:both;"></div>
<div class="gywm_m">
    <div class="gywm_mn">
        <!-- 主体左侧热门游戏列表 -开始 -->
        <div class="gywm_mnbl">

        </div>
        <!-- 主体左侧热门游戏列表 -结束 -->

        <!-- 主体右侧列表 -开始 -->
        <form method="post" name="regreg" action="add_reg_mem.php?keys=add" onsubmit="return VerifyData('reg');" class="join-form" target="_blank" >
            <input name="website" type="hidden" class="setwebsite" value="">
            <input name="loginwebsite" type="hidden" class="loginwebsite" value="">

             <div class="gywm_mnbr">
            <div class="gywm_mnbr_hyzc">
                <div class="gywm_mnbr_rperson">皇冠现金网：A级品牌公司给予的不止是心动，更是一份信誉的保障，500万名会员共同的选择。</div>
            </div>

            <div class="gywm_mnbr_field">
                <fieldset>
                    <legend>注册账号:</legend>
                    <table>
                        <tr>
                            <td class="tdr"> 介绍人：</td><td width="511">
                            <input type="text" name="introducer" id="introducer"  minlength="4" maxlength="15"/>
                            没有可不填写</td>
                        </tr>
                        <tr>
                            <td class="tdr"><font class="red">*</font> 账号：</td><td width="511">
                            <input type="text" name="username" id="username"  minlength="5" maxlength="15"/>
                            请输入<font class="yellow">5-15个字元</font>, 仅可输入英文字母以及数字的组合!!</td>
                        </tr>
                        <tr>
                            <td class="tdr"><font class="red">*</font> 会员密码：</td>
                            <td><input type="password" name="password" id="password"  minlength="6" maxlength="15"/>
                            须为<font class="yellow">6-15位英文或数字</font>且符合0~9或a~z字元</td></tr>
                        <tr><td class="tdr"><font class="red">*</font> 确认密码：</td>
                            <td>
                            <input type="password" name="password2" id="password2"  minlength="6" maxlength="15"/>
                                请再次输入正确的登陆密码</td>
                        </tr>

                        <!--<tr><td class="tdr">性别：</td>
                            <td><input type="radio" class="radio" name="radio" value="0" checked="checked" />男
                                <input type="radio" class="radio" name="radio" value="1" />女</td></tr>
                         <tr>
                            <td class="tdr"><font class="red">*</font>  取款密码：</td>
                            <td width="511">
                                <input type="password" name="paypassword" id="paypassword" size="25" minlength="4" maxlength="6" class="inp-txt" >
                            <!-- 取款时使用到的密码，非常重要，请务必牢记！ 请输入4-6位纯数字</td>
                         </tr>-->
                        <?php if(empty($registerSet) || $registerSet['aliasOn'] == 1) { ?>
                        <tr>
                            <td class="tdr"><font class="red">*</font> 真实姓名：</td><td>
                                <input type="text" name="alias" id="alias"  />
                                <font class="yellow">必须与您的银行帐户名称相同，否则不能出款!</font>
                            </td>
                        </tr>
                        <?php }if($registerSet['telOn'] == 1) { ?>
                        <tr>
                            <td class="tdr"><font class="red">*</font>  手机号码：</td>
                            <td width="511">
                                <input type="text" name="phone" id="phone" size="25" minlength="11" maxlength="11" class="inp-txt" >
                                <span style="color: #FFEB3B;">*请认真填写，以便有优惠活动可以及时通知您参与！</span>
                            </td>
                        </tr>
                        <?php } if($registerSet['chatOn'] == 1) { ?>
                        <tr>
                            <td class="tdr"><font class="red">*</font> 微信号码：</td>
                            <td width="511">
                                <input type="text" name="wechat" id="wechat" size="25" class="inp-txt" minlength="4" >
                            </td>
                        </tr>
                        <?php } if($registerSet['qqOn'] == 1) { ?>
                            <tr>
                                <td class="tdr"><font class="red">*</font>  QQ号码：</td>
                                <td width="511">
                                    <input type="text" name="qq" id="qq" size="25" class="inp-txt" minlength="4" >
                                </td>
                            </tr>
                        <?php } ?>
                        <!--<tr><td class="tdr"><font class="red">*</font> 生日：</td><td>
                                <input type="text" name="birthday" id="birthday"  onclick="WdatePicker({readOnly:true,isShowClear:false})" readonly/>
                                用于取回密码的答案和生日礼金，需谨记！</td>
                        </tr>-->
                        <tr>
                            <td class="tdr"><font class="red">*</font> 验证码：</td><td width="511">
                                <input id="verifycode" name="verifycode" type="text" tabindex="2" style="width:100px; height:30px" minlength="4" maxlength="4" >
                                <img title="点击刷新" border='1' src="../include/validatecode/captcha.php" align="absbottom" onclick="this.src='../include/validatecode/captcha.php?'+Math.random();"/>
                        </tr>
                    </table>
                </fieldset>
<!-- 
                <fieldset>
                    <legend>会员资料:</legend>
                    <table>
                        <tr>
                            <td class="tdr"><font class="red">*</font>  取款密码：</td>
                            <td width="511">
                                <input type="password" name="paypassword" id="paypassword" size="25" minlength="4" maxlength="6" class="inp-txt" >
                            取款时使用到的密码，非常重要，请务必牢记！</td></tr>
                        <tr>
                            <td class="tdr"><font class="red">*</font> 密码提示问题：</td>
                            <td>
                                <select id="question" name="question">
                                    <option value="">请选择</option>
                                    <option value="您的车牌号码">您的车牌号码</option>
                                    <option value="您所在的城市">您所在的城市</option>
                                    <option value="您的生日">您的生日</option>
                                    <option value="您的名字">您的名字</option>
                                    <option value="您父亲的名字">您父亲的名字</option>
                                    <option value="您母亲的名字">您母亲的名字</option>
                                    <option value="您儿女的名字">您儿女的名字</option>
                                    <option value="您妻子的名字">您妻子的名字</option>
                                    <option value="您喜欢的数字">您喜欢的数字</option>
                                    <option value="您喜欢的品牌">您喜欢的品牌</option>
                                    <option value="您喜欢的运动">您喜欢的运动</option>
                                    <option value="您喜欢的颜色">您喜欢的颜色</option>
                                    <option value="您喜欢的球队">您喜欢的球队</option>
                                    <option value="您喜欢的球星">您喜欢的球星</option>
                                </select>
                            密码提示问题密码找回时需要，请谨记！
                            </td>
                        </tr>
                        <tr><td class="tdr"><font class="red">*</font> 密码提示答案：</td><td>
                            <input type="text" name="answer" id="answer"  />
                            用于找回取款密码的答案，请谨记！ </td></tr>
                    </table>
                </fieldset>
 -->
                <div class="gywm_mnbr_fieldbt">
                    <div class="gywm_mnbr_fieldbtck"><input type="checkbox" class="checkbox" name="check" checked="checked" value="1" />我已届满合法博彩年龄，且同意各项开户条约。
                        <a class="show_khxy" href="javascript:;" style="color:#ffea00; text-decoration:underline;">“开户协议”</a>
                    </div>
                    <div class="gywm_mnbr_fieldbtbt">
                        <input name="提交" type="submit" class="btn" value="确认提交"  />
                        <input name="重置" type="reset" class="btn" value="重新设置" />
                    </div>
                    <div class="gywm_mnbr_fieldbtbz">备注：<br/>1.标记有 * 者为必填项目。<br/>2.手机与取款密码为取款金额时的凭证,请会员务必填写详细资料。<br/>3.若公司有其它活动会E-MAIL通知，请客户填写清楚。
                    </div>
                </div>
            </div>
        </div>
        </form>
        <!-- 主体右侧列表 -结束 -->

    </div>
</div>
<!-- 主体 -结束 -->
<script type="text/javascript" src="../js/validate.js"></script>
<script type="text/javascript">
    loadLeftNav() ;

    setWebsite(HTTPS_HEAD,FETCH_NUM) ;
    urlSetAction(HTTPS_HEAD,FETCH_NUM);
    setUserMsg(usermessage,'nottip') ;
    showKhXy();
    function setWebsite(ad_login_http,num) {
        // 注册成功后跳转到登录网址
        var urlStr = '<?php echo $ulrarr?>';
        var urlArray = urlStr.split(',') ;
        var urllen = urlArray.length ;
        $('.loginwebsite').val(ad_login_http+'://'+urlArray[num]) ;

        // 注册来源网址
        var url = window.location.hostname ;
        $('.setwebsite').val(url) ;

    }
    
    // 开户协议
    function showKhXy() {
        $('.show_khxy').on('click',function () {
            $('.khxy_content').show();
        })
    }



</script>

