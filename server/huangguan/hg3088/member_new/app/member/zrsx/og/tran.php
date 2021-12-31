<?php
/**
 * OG真人
 *   查询余额（体育余额、真人余额）
 *   预备转账
 *   额度转换
 *   查询订单状态
 *
 */
require ("../../include/config.inc.php");
$langx=$_SESSION['langx'];
$uid=$_REQUEST['uid'];
include "../../include/address.mem.php";

// 判断OG视讯是否维护-单页面维护功能
checkMaintain('og');

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
    exit;
}

$sql = "select Money from ".DBPREFIX.MEMBERTABLE." where Oid='$uid' and Status<=1";
$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);
$row['Money'] = number_format($row["Money"], 2, '.', ',');

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
        <td width="70">体育余额</td>
        <td align="left" width="120"><span id="user_blance1"><?php echo $row['Money'];?></span></td>
    </tr>
    <tr class="b_rig">
        <td>OG视讯余额</td>
        <td align="left"><span id="video_blance1"></span></td>
    </tr>
    <tr class="b_rig">
        <td>转账</td>
        <td align="left">
            <select name="f_blance" id="f_blance" onchange="f_t('f','t');">
                <option value="hg">体育余额</option>
                <option value="og">OG视讯余额</option>
            </select>
            <i class="tran_logo"></i>
            <select name="t_blance" id="t_blance" onchange="f_t('t','f');">
                <option value="og">OG视讯余额</option>
                <option value="hg">体育余额</option>
            </select><br/>

            <input type="text" name="blance" id="blance" value="" placeholder="金额：￥" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')" />

            <input type="button" class="jbox-button jbox-button-focus" value="提交转账" id="trans_blance" onclick="trans()" style=" padding:1px 10px; font-weight:bold; cursor:pointer;" />

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

        $.ajax({
            type: 'POST',
            url:'og/og_api.php?_='+Math.random(),
            data:dat,
            dataType:'json',
            success:function(ret){

                if(ret.err != 0){
                    alert(ret.msg)
                }else{
                    $('#trans_blance').attr('disabled',false);
                    $('#trans_blance').attr('value','提交转换');
                    if(ret.err==0){
                        $('#blance').val('');// 清空金额
                        get_blance1();
                        alert('转账成功，请查看余额')
                    }
                }
            },
            error:function(ii,jj,kk){
                $('#trans_blance').attr('disabled',false);
                $('#trans_blance').attr('value','提交转换');
                alert('网络错误，请稍后重试!');
            }
        });

    }

    function f_t(f,t){
        var h=$('#'+f+'_blance').val();
        if(h=='hg'){
            $('#'+t+'_blance').val('og');
        }
        else{
            $('#'+t+'_blance').val('hg');
        }
    }
    get_blance1()
    function get_blance1(){

        $('#video_blance1').html('加载中，请稍候');
        // $('#user_blance1').html('加载中，请稍后');
        var dat={};
        dat.uid=uid;
        dat.action='b';
        $.ajax({
            type: 'POST',
            url:'og/og_api.php?_='+Math.random(),
            data:dat,
            dataType:'json',
            success:function(ret){
                if(ret.err==0){
                    // console.log(ret)
                    $('#video_blance1').html(ret.balance_og);
                    $('#og_blance', window.parent.body.document).html(ret.balance_og) ; // 更新父级余额
                }
                else{
                    $('#video_blance1').html('0.00');
                }
            },
            error:function(ii,jj,kk){
                alert('网络错误，请稍后重试');
            }
        });
    }

</script>