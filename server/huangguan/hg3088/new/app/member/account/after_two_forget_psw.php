<?php
$username = $_REQUEST['username'] ;
?>
<html class="zh-cn"><head>
    <meta charset="UTF-8">
    <title>更新密码</title>
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
            <div class="pw-st-title">更新密码</div>
            <div class="pw-input-wrap clearfix">
                <p class="pw-unit pw-unit-user">
                    <label name="real-name" for="real-name" class="pw-placeholder">新密码</label>
                    <input id="new_password" name="new_password" type="password" class="user-acc unit-input" tabindex="1" size="12" minlength="6" maxlength="15">
                </p>
                <p class="pw-unit pw-unit-user">
                    <label name="wd-pwd" for="wd-pwd" class="pw-placeholder">确认密码</label>
                    <input id="confirm_password" name="confirm_password" type="password"  class="user-acc unit-input" tabindex="1" size="12" minlength="6" maxlength="15">

                </p>
                <p class="pw-unit pw-unit-user" style="width:308px;">
                    <label name="verifycode" for="verifycode" class="pw-placeholder">验证码</label>
                    <input id="js-verifycode" name="verifycode" type="text" autocomplete="new-password" class="user-acc unit-input" tabindex="2" style="width:130px;" maxlength="4" >
                    <a href="javascript:;" class="new_code" onclick="getverifycode()"> </a>
                </p>
                <input type="hidden" class="action_to" value="t3s"/>
                <input type="hidden" id="js-username" class="username" value="<?php echo $username?>"/>
            </div>

        </div>
        <div id="JS-tips-wrap" class="pw-tips-wrap">
            <span> 确认后系统将为您更新密码，请牢记。此次验证30分钟内有效！！ </span>
        </div>
        <div class="pw-submit-wap"><button id="js-btn-submit" class="fgpw-submit" tabindex="4" onclick="submitdata()">确认送出</button></div>
    </div>

</div>
<script src="../../../js/jquery.js"></script>
<script src="../../../js/forgetpwd.js?v=<?php echo AUTOVER; ?>"></script>

</body>
</html>