<?php
/**
 *   开元棋牌-额度转换
 */

require ("../include/config.inc.php");
include "../include/address.mem.php";

// 判断会员是否登录，否则跳转登出页面
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>top.location.href='/'</script>";
    exit;
}

// 判断会员是否是试玩账号，如果是试玩则提示注册真实账号
if($_SESSION['Agents'] == 'demoguest'){
    echo "<script>alert('非常抱歉，请您注册真实会员！')</script>";
    exit;
}

// 判断会员状态是否启用，否则退出
if ($_SESSION['Status'] != 0){
    echo "<script>alert('非常抱歉，您的账号已冻结或已停用，请您联系客服！')</script>";
    exit;
}
?>

<style>
    .game {
        background-color: #B9B9A3;
        font-size: 0.75em;
        width: 350px;
    }
    .b_rig {
        background-color: #FFF;
        text-align: right;
        white-space: nowrap;
    }

</style>
<table border="0" cellspacing="1" cellpadding="0" class="game" width="350" style="width:350px;">
    <tr class="b_rig">
        <td width="70">中心钱包</td>
        <td align="left" width="120"><span id="hg_balance">加载中，请稍候...</span></td>
    </tr>
    <tr class="b_rig">
        <td>开元棋牌</td>
        <td align="left"><span id="ky_balance">加载中，请稍候...</span></td>
    </tr>
    <tr class="b_rig">
        <td>转账</td>
        <td align="left">
            <select name="f_balance" id="f_balance" onchange="f_t('f','t');">
                <option value="hg">中心钱包</option>
                <option value="ky">开元棋牌</option>
            </select>
            <i class="tran_logo"></i>
            <select name="t_balance" id="t_balance" onchange="f_t('t','f');">
                <option value="ky">开元棋牌</option>
                <option value="hg">中心钱包</option>
            </select>
            <br/>

            <input type="text" name="balance" id="balance" value="" placeholder="金额：￥" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')" />

            <input type="button" class="jbox-button jbox-button-focus" value="提交转换" id="trans_balance" onclick="exchange()" style=" padding:1px 10px; font-weight:bold; cursor:pointer;" />

        </td>
    </tr>
</table>
<script language="javascript">
    var uid ='<?php echo $_REQUEST['uid']?>';
    var agent = '<?php echo $_SESSION['Agents']?>';
    $(function () {
        if(agent != 'demoguest') {
            var data = {};
            data.uid = uid;
            data.action = 'b';
            $.ajax({
                type: 'POST',
                url: '/app/member/ky/ky_api.php?_=' + Math.random(),
                data: data,
                dataType: 'json',
                success: function (item) {
                    if (item.code == 0) {
                        $('#hg_balance').html(item.data.hg_balance);
                        $('#ky_balance').html(item.data.ky_balance);

                    } else {
                        alert(item.message);
                    }
                },
                error: function () {
                    alert('网络异常，请稍后重试！');
                }
            });
        }
    });

    // 切换-FT
    function f_t(f, t){
        var h = $('#' + f + '_balance').val();
        if(h =='hg'){
            $('#' + t + '_balance').val('ky');
        }else{
            $('#' + t + '_balance').val('hg');
        }
    }

    function exchange() {
        if(agent != 'demoguest') {
            var data = {};
            data.f = $('#f_balance').val();
            data.t = $('#t_balance').val();
            data.b = $('#balance').val();
            data.uid = uid;
            $.ajax({
                type: 'POST',
                url: '/app/member/ky/ky_api.php?_=' + Math.random(),
                data: data,
                dataType: 'json',
                success: function (item) {
                    if (item.code == 0) {
                        $('#trans_balance').attr('disabled', false);
                        $('#trans_balance').attr('value', '提交转换');
                        $('#balance').val('');
                        $('#hg_balance').html(item.data.hg_balance);
                        $('#ky_balance').html(item.data.ky_balance);
                        $('#ky_money', window.parent.body.document).html(item.data.ky_balance) ; // 更新父级余额
                        alert('转账成功，请查看余额！');
                    } else {
                        alert(item.message)
                    }
                },
                error: function () {
                    $('#trans_balance').attr('disabled', false);
                    $('#trans_balance').attr('value', '提交转换');
                    alert('网络错误，请稍后重试!');
                }
            });
        }else{
            alert('您尚未注册真实账户，暂不允许进行额度转换！');
        }
    }
</script>