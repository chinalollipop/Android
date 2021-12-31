<?php
$username = $_REQUEST['username'] ;
?>
<html class="zh-cn"><head>
    <meta charset="UTF-8">
    <title>忘记密码</title>
    <link rel="stylesheet" href="../../../style/forgetpwd.css?v=<?php echo AUTOVER; ?>">

</head>
<body>
<div id="fgPg-wrap" class="fgpw-wrap">
    <div class="lock-pic-wrap">
        <div class="icon-safe"></div>
    </div>

    <!-- 在提交帐号后显示 -->
    <div class="txt-wrap before-setaccount">
        <div class="pw-info-wrap">
            <div class="pw-st-title">忘记密码</div>
            <div class="pw-input-wrap clearfix">
                <p class="pw-unit pw-unit-user">
                    <label name="real-name" for="real-name" class="pw-placeholder">真实姓名</label>
                    <input id="js-realname" name="real-name" type="text" autocomplete="new-password" class="user-acc unit-input" tabindex="1" size="12" maxlength="15">
                </p>
                <p class="pw-unit pw-unit-user">
                    <label name="wd-pwd" for="wd-pwd" class="pw-placeholder">取款密码</label>
                    <input id="pay-password" name="pay-password" type="password" autocomplete="new-password" class="user-acc unit-input" tabindex="1" size="12" minlength="6" maxlength="6">
               <!--     <select id="js-wdpwd1" class="txt6" name="js-wdpwd1">
                        <option selected="" value="">&nbsp;-</option>
                        <option value="0">0</option> <option value="1">1</option>
                        <option value="2">2</option> <option value="3">3</option>
                        <option value="4">4</option> <option value="5">5</option>
                        <option value="6">6</option> <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                    </select>

                    <select id="js-wdpwd2" class="txt6" name="js-wdpwd2">
                        <option selected="" value="">&nbsp;-</option>
                        <option value="0">0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                    </select>

                    <select id="js-wdpwd3" class="txt6" name="js-wdpwd3">
                        <option selected="" value="">&nbsp;-</option>
                        <option value="0">0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                    </select>

                    <select id="js-wdpwd4" class="txt6" name="js-wdpwd4">
                        <option selected="" value="">&nbsp;-</option>
                        <option value="0">0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                    </select>

                    <select id="js-wdpwd5" class="txt6" name="js-wdpwd5">
                        <option selected="" value="">&nbsp;-</option>
                        <option value="0">0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                    </select>

                    <select id="js-wdpwd6" class="txt6" name="js-wdpwd6">
                        <option selected="" value="">&nbsp;-</option>
                        <option value="0">0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                    </select>-->
                </p>

                <p class="pw-unit pw-unit-user" style="width:308px;">
                    <label name="verifycode" for="verifycode" class="pw-placeholder">验证码</label>
                    <input id="js-verifycode" name="verifycode" type="text" autocomplete="new-password" class="user-acc unit-input" tabindex="2" style="width:130px;" maxlength="4" >
                    <a href="javascript:;" class="new_code" onclick="getverifycode()"> </a>
                </p>
                <input type="hidden" class="action_to" value="t2s"/>
                <input type="hidden" id="js-username" class="username" value="<?php echo $username?>"/>
            </div>

        </div>
        <div id="JS-tips-wrap" class="pw-tips-wrap">
            <span>输入会员帐号-真实姓名-取款密码,自助重置登陆密码！ </span>
        </div>
        <div class="pw-submit-wap"><button id="js-btn-submit" class="fgpw-submit" tabindex="4" onclick="submitdata()">确认修改</button></div>
    </div>

</div>
<script src="../../../js/jquery.js"></script>
<script type="text/javascript" src="../../../js/register/laydate.min.js?v=<?php echo AUTOVER; ?>"></script>
<script src="../../../js/forgetpwd.js?v=<?php echo AUTOVER; ?>"></script>

</body>
</html>