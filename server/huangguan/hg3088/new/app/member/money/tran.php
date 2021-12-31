<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "../include/address.mem.php";
require ("../include/config.inc.php");
require ("../include/define_function_list.inc.php");

$uid=$_REQUEST["uid"];
$langx=$_SESSION["langx"];
require ("../include/traditional.$langx.inc.php");
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    exit(json_encode( [ 'err'=>'-1','msg'=>'请重新登录' ] ) );
}



$cpMasterDbLink = @mysqli_connect($database['cpDefault']['host'],$database['cpDefault']['user'],$database['cpDefault']['password'],$database['cpDefault']['dbname'],$database['cpDefault']['port']) or die("mysqli connect error".mysqli_connect_error()) ;
$sql = "select lcurrency from ".$database['cpDefault']['prefix']."user where hguid=".$_SESSION['userid'];

$result = mysqli_query($cpMasterDbLink,$sql);
$rowCp = mysqli_fetch_assoc($result);
$couCp = mysqli_num_rows($result);
if($couCp==0){
    echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
    exit;
}
$cpFund = $rowCp['lcurrency'];
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
    .game td, .more td {
        padding: 1px 4px;
        font-size: 12px;
        border-right: 1px solid #B9B9A3;
        border-bottom: 1px solid #B9B9A3;
        font-family: Arial, Helvetica, SimSun, sans-serif;
    }
</style>
<table border="0" cellspacing="1" cellpadding="0" class="game" style="width:100%;">
    <tr class="b_rig">
        <td width="70">转换类型</td>
        <td align="left" width="120">
            <select name="f_blance" id="f_blance">
                <option value="cp">彩票余额</option>
                <option value="gmcp">国民彩票余额</option>
                <option value="ag">真人视讯与电子余额</option>
                <option value="ky">开元棋牌余额</option>
                <option value="ff">皇冠棋牌余额</option>
                <option value="vg">VG棋牌余额</option>
                <option value="ly">乐游棋牌余额</option>
                <option value="mg">MG电子余额</option>
            </select>=>
            <select name="t_blance" id="t_blance">
                <option value="hg">体育余额</option>
            </select><br/>
        </td>
    </tr>
    <tr class="b_rig">
        <td>转换金额</td>
        <td align="left">
            ￥<input type="text" name="blance" id="blance" value="" />
        </td>
    </tr>

    <tr class="b_rig">
        <td colspan="2">
            <input type="button" class="jbox-button jbox-button-focus" value="提交转换" id="trans_blance" onclick="trans()" style=" padding:1px 10px; font-weight:bold; cursor:pointer;" />
            <input type="button" name="btnClose" id="btnClose" value="我不转了" onclick="javascript:$.jBox.close();">
        </td>

    </tr>

</table>
<script language="javascript">

    var uid='<?php echo $uid;?>';

    function trans() {

        var dat={};
        dat.f=$('#f_blance').val();
        dat.t=$('#t_blance').val();
        dat.b=$('#blance').val();
        dat.uid=uid;

        if(dat.b < 1){
            alert("转账金额错误，请重新输入！");
            return false;
        }

        var cpFund = "<?php echo $cpFund;?>";
        if($("#f_blance option:selected").val()=='cp' && (cpFund==0||cpFund<0)){	alert("转出方资金不足！");return false;	}

        if($("#f_blance option:selected").val()=='ky'){ // 开元棋牌
            $.ajax({
                type: 'POST',
                url: '../ky/ky_api.php?_=' + Math.random(),
                data: dat,
                dataType: 'json',
                success: function (item) {
                    if (item.code == 0) {
                        $('#blance').val('');
                        alert('转账成功，请查看余额！');
                        get_ky_balance();
                        $.jBox.close();
                    } else {
                        alert(item.message)
                    }
                },
                error: function () {
                    alert('网络错误，请稍后重试!');
                }
            });
        } else if($("#f_blance option:selected").val()=='ff'){ // 皇冠棋牌
            $.ajax({
                type: 'POST',
                url: '../hgqp/hg_api.php?_=' + Math.random(),
                data: dat,
                dataType: 'json',
                success: function (item) {
                    if (item.code == 0) {
                        $('#blance').val('');
                        alert('转账成功，请查看余额！');
                        get_ff_balance();
                        $.jBox.close();
                    } else {
                        alert(item.message)
                    }
                },
                error: function () {
                    alert('网络错误，请稍后重试!');
                }
            });
        } else if($("#f_blance option:selected").val()=='vg'){ // VG棋牌
            $.ajax({
                type: 'POST',
                url: '../vgqp/vg_api.php?_=' + Math.random(),
                data: dat,
                dataType: 'json',
                success: function (item) {
                    if (item.code == 0) {
                        $('#blance').val('');
                        alert('转账成功，请查看余额！');
                        get_vg_balance();
                        $.jBox.close();
                    } else {
                        alert(item.message)
                    }
                },
                error: function () {
                    alert('网络错误，请稍后重试!');
                }
            });
        }else if($("#f_blance option:selected").val()=='ly'){ // 乐游棋牌
            $.ajax({
                type: 'POST',
                url: '../lyqp/ly_api.php?_=' + Math.random(),
                data: dat,
                dataType: 'json',
                success: function (item) {
                    if (item.code == 0) {
                        $('#blance').val('');
                        alert('转账成功，请查看余额！');
                        get_ly_balance();
                        $.jBox.close();
                    } else {
                        alert(item.message)
                    }
                },
                error: function () {
                    alert('网络错误，请稍后重试!');
                }
            });
        }
        else if($("#f_blance option:selected").val()=='mg'){ // MG电子
            $.ajax({
                type: 'POST',
                url: '../mg/mg_api.php?_=' + Math.random(),
                data: dat,
                dataType: 'json',
                success: function (item) {
                    if (item.err == 0) {
                        $('#blance').val('');
                        alert('转账成功，请查看余额！');
                        get_mg_balance();
                        $.jBox.close();
                    } else {
                        alert(item.msg)
                    }
                },
                error: function () {
                    alert('网络错误，请稍后重试!');
                }
            });
        }else if($("#f_blance option:selected").val()=='gmcp'){ // 三方彩票
            $.ajax({
                type: 'POST',
                url: '../gmcp/cp_api.php?_=' + Math.random(),
                data: dat,
                dataType: 'json',
                success: function (item) {
                    if (item.code == 0) {
                        $('#blance').val('');
                        alert('转账成功，请查看余额！');
                        get_gmcp_balance();
                        $.jBox.close();
                    } else {
                        alert(item.message)
                    }
                },
                error: function () {
                    alert('网络错误，请稍后重试!');
                }
            });
        }else {
            $.ajax({
                type: 'POST',
                url: 'withdrawal_tran_api.php?_=' + Math.random(),
                data: dat,
                dataType: 'json',
                success: function (ret) {
                    if (ret) {
                        if (ret.err != 0) {
                            alert(ret.msg);
                            // $.jBox.close();
                        } else {
                            if (ret.err == 0) {
                                $('#blance').val('');
                                alert('转账成功，请查看余额');
                                get_blance();
                                $.jBox.close();
                            }
                        }
                    }

                },
                error: function (ii, jj, kk) {
                    alert('网络错误，请稍后重试!');
                }
            });
        }
    }
</script>