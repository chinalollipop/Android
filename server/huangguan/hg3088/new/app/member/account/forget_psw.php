

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
    <!-- 在提交帐号前显示 -->
    <div class="txt-wrap before-setaccount">
        <div class="pw-info-wrap">
            <div class="pw-st-title">忘记密码</div>

            <div class="pw-input-wrap clearfix">
                <p class="pw-unit pw-unit-user">
                    <label name="user-acc" for="user-acc" class="pw-placeholder">会员帐号</label>
                    <input id="js-username" name="user-acc" type="text" autocomplete="new-password" class="user-acc unit-input" tabindex="1" size="12" maxlength="15">
                </p>
                <p class="pw-unit pw-unit-user" style="width:308px;">
                    <label name="verifycode" for="verifycode" class="pw-placeholder">验证码</label>
                    <input id="js-verifycode" name="verifycode" type="text" autocomplete="new-password" class="user-acc unit-input" tabindex="2" style="width:130px;" minlength="4" maxlength="4" >
                  <a href="javascript:;" class="new_code" onclick="getverifycode()">

                  </a>
                </p>
                <input type="hidden" class="action_to" value="t1s"/>
            </div>

        </div>
        <div id="JS-tips-wrap" class="pw-tips-wrap">
            <span>会员帐号、真实姓名、取款密码、生日核对正确后,会员可自行重置登陆密码!! </span>
        </div>
        <div class="pw-submit-wap">
            <input type="button" id="js-btn-submit" class="fgpw-submit" tabindex="4" value="提交" onclick="submitdata()">
        </div>
    </div>

</div>
<script src="../../../js/jquery.js"></script>
<script src="../../../js/forgetpwd.js?v=<?php echo AUTOVER; ?>"></script>

</body>
</html>