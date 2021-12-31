<?php
/**
 *额度转换
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
<link type="text/css" rel="stylesheet" href="/style/member/game_page_common.css?v=<?php echo AUTOVER; ?>">
<style>
    .game{background-color:#B9B9A3;font-size:0.75em;width:350px}
    .b_rig{background-color:#FFF;text-align:right;white-space:nowrap}
</style>
<table border="0" cellspacing="1" cellpadding="0" class="game" width="350" style="width:350px;">
    <tr class="b_rig">
        <td width="70">中心钱包</td>
        <td align="left" width="120"><span class="hgmoney">加载中，请稍候...</span></td>
    </tr>
    <tr class="b_rig">
        <td>彩票额度</td>
        <td align="left"><span class="gmcpmoney">加载中，请稍候...</span></td>
    </tr>
    <tr class="b_rig">
        <td>转账</td>
        <td align="left">
            <select name="f_balance" id="f_balance" >
                <option value="hg">体育余额</option>
                <option value="gmcp">彩票额度</option>
            </select>
            <i class="tran_logo"></i>
            <select name="t_balance" id="t_balance" >
                <option value="gmcp">彩票额度</option>
                <option value="hg">体育余额</option>
            </select><br/>
            <input type="text" name="balance" id="balance" value="" placeholder="金额：￥" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')" />

            <input type="button" class="jbox-button jbox-button-focus" value="提交转账" id="trans_balance" onclick="exchange()" style=" padding:1px 10px; font-weight:bold; cursor:pointer;" />

        </td>
    </tr>
</table>

<script language="javascript">
    var uid ='<?php echo $_REQUEST['uid']?>';
    var agent = '<?php echo $_SESSION['Agents']?>';

    getUserBanlance(uid,'gmcp'); // 三方彩票

    function exchange() {
        if(agent != 'demoguest') {
            var data = {};
            data.f = $('#f_balance').val();
            data.t = $('#t_balance').val();
            data.b = $('#balance').val();
            data.uid = uid;
            if(data.f == data.t){
                alert('转出方和转入方不能相同');
                return false;
            }
            if(data.b<1){
                alert('请输入转换金额');
                return false;
            }
            getUserBanlance(uid,'gmcp',data); // 三方彩票

        }else{
            alert('您尚未注册真实账户，暂不允许进行额度转换！');
        }
    }
</script>