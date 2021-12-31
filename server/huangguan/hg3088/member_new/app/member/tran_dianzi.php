<?php
/**
 * 电子（AG、MG、CQ9、MW、FG）
 *   查询余额
 *   预备转账
 *   额度转换
 *   查询订单状态
 *
 */
require ("include/config.inc.php");
$langx=$_SESSION['langx'];
$uid=$_REQUEST['uid'];
$ctr = isset($_REQUEST['ctr'])?$_REQUEST['ctr']:''; // ag mg
include "include/address.mem.php";

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
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
        <td width="70">体育余额</td>
        <td align="left" width="120"><span id="user_blance1"></span></td>
    </tr>
    <tr class="b_rig">
        <td>AG电子余额</td>
        <td align="left"><span id="video_blance1"></span></td>
    </tr>
    <tr class="b_rig">
        <td>MG电子余额</td>
        <td align="left"><span id="mg_blance"></span></td>
    </tr>
    <tr class="b_rig">
        <td>MW电子余额</td>
        <td align="left"><span id="mw_blance"></span></td>
    </tr>
    <tr class="b_rig">
        <td>CQ9电子余额</td>
        <td align="left"><span id="cq_blance"></span></td>
    </tr>
    <tr class="b_rig">
        <td>FG电子余额</td>
        <td align="left"><span id="fg_blance"></span></td>
    </tr>
    <tr class="b_rig">
        <td>转账</td>
        <td align="left">
            <select name="f_blance" id="f_blance" onchange="f_t('f','t');">
                <option value="hg">体育余额</option>
                <option value="ag">AG电子余额</option>
                <option value="mg">MG电子余额</option>
                <option value="cq">CQ9电子余额</option>
                <option value="mw">MW电子余额</option>
                <option value="fg">FG电子余额</option>
            </select>
            <i class="tran_logo"></i>
            <select name="t_blance" id="t_blance" onchange="f_t('t','f');">
                <option value="ag" <?php echo $ctr=='ag'?'selected':''; ?> >AG电子余额</option>
                <option value="mg" <?php echo $ctr=='mg'?'selected':''; ?> >MG电子余额</option>
                <option value="cq" <?php echo $ctr=='cq'?'selected':''; ?> >CQ9电子余额</option>
                <option value="mw" <?php echo $ctr=='mw'?'selected':''; ?> >MW电子余额</option>
                <option value="fg" <?php echo $ctr=='fg'?'selected':''; ?> >FG电子余额</option>
                <option value="hg">体育余额</option>
            </select><br/>

            <input type="text" name="blance" id="blance" value="" placeholder="金额：￥" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')"/>

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

        if ((dat.f == 'hg' && dat.t == 'ag') || (dat.f == 'ag' && dat.t == 'hg')){
            $.ajax({
                type: 'POST',
                url:'zrsx/ag_api.php?_='+Math.random(),
                data:dat,
                dataType:'json',
                success:function(ret){

                    if(ret.err != 0){
                        alert(ret.msg)
                    }else{
                        $('#trans_blance').attr('disabled',false);
                        $('#trans_blance').attr('value','提交转换');
                        if(ret.err==0){
                            $('#blance').val('');
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
        if ((dat.f == 'hg' && dat.t == 'mg') || (dat.f == 'mg' && dat.t == 'hg')){
            $.ajax({
                type: 'POST',
                url:'mg/mg_api.php?_='+Math.random(),
                data:dat,
                dataType:'json',
                success:function(ret){
                    // console.log(ret)
                    if(ret.err != 0){
                        alert(ret.msg)
                    }else{
                        $('#trans_blance').attr('disabled',false);
                        $('#trans_blance').attr('value','提交转换');
                        if(ret.err==0){
                            $('#blance').val('');
                            $('#user_blance1').html(ret.msg.hg_balance);
                            $('#mg_blance').html(ret.msg.mg_balance);
                            $('#mgmoney', window.parent.body.document).html(ret.msg.mg_balance) ; // 更新父级余额
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

        if ((dat.f == 'hg' && dat.t == 'cq') || (dat.f == 'cq' && dat.t == 'hg')){
            $.ajax({
                type: 'POST',
                url:'cq9/cq9_api.php?_='+Math.random(),
                data:dat,
                dataType:'json',
                success:function(ret){
                    //console.log(ret);
                    if(ret.err != 0){
                        alert(ret.msg)
                    }else{
                        $('#trans_blance').attr('disabled',false);
                        $('#trans_blance').attr('value','提交转换');
                        if(ret.err==0){
                            $('#blance').val('');
                            $('#user_blance1').html(ret.msg.hg_balance);
                            $('#cq_blance').html(ret.msg.cq_balance);
                            $('#cqmoney', window.parent.body.document).html(ret.msg.cq_balance) ; // 更新父级余额
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

        if ((dat.f == 'hg' && dat.t == 'mw') || (dat.f == 'mw' && dat.t == 'hg')){
            $.ajax({
                type: 'POST',
                url:'mw/mw_api.php?_='+Math.random(),
                data:dat,
                dataType:'json',
                success:function(ret){
                    //console.log(ret)
                    if(ret.err != 0){
                        alert(ret.msg)
                    }else{
                        $('#trans_blance').attr('disabled',false);
                        $('#trans_blance').attr('value','提交转换');
                        if(ret.err==0){
                            $('#blance').val('');
                            $('#user_blance1').html(ret.msg.hg_balance);
                            $('#mw_blance').html(ret.msg.mw_balance);
                            $('#mwmoney', window.parent.body.document).html(ret.msg.mw_balance) ; // 更新父级余额
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

        if ((dat.f == 'hg' && dat.t == 'fg') || (dat.f == 'fg' && dat.t == 'hg')){
            $.ajax({
                type: 'POST',
                url:'fg/fg_api.php?_='+Math.random(),
                data:dat,
                dataType:'json',
                success:function(ret){
                    //console.log(ret)
                    if(ret.err != 0){
                        alert(ret.msg)
                    }else{
                        $('#trans_blance').attr('disabled',false);
                        $('#trans_blance').attr('value','提交转换');
                        if(ret.err==0){
                            $('#blance').val('');
                            $('#user_blance1').html(ret.msg.hg_balance);
                            $('#fg_blance').html(ret.msg.fg_balance);
                            $('#fgmoney', window.parent.body.document).html(ret.msg.fg_balance) ; // 更新父级余额
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
    }

    function f_t(f,t){
        var h=$('#'+f+'_blance').val();
        if(h=='hg'){
            $('#'+t+'_blance').val('ag');
        }
        else{
            $('#'+t+'_blance').val('hg');
        }
    }
    get_blance1()
    function get_blance1(){

        $('#video_blance1').html('加载中');
        $('#user_blance1').html('加载中');
        var dat={};
        dat.uid=uid;
        dat.action='b';
        $.ajax({
            type: 'POST',
            url:'zrsx/ag_api.php?_='+Math.random(),
            data:dat,
            dataType:'json',
            success:function(ret){
                if(ret.err==0){
                    // console.log(ret)
                    $('#video_blance1').html(ret.balance_ag);
                    $('#user_blance1').html(ret.balance_hg);
                }
                else{
                    $('#video_blance1').html('0.00');
                    $('#user_blance1').html('0.00');
                }
            },
            error:function(ii,jj,kk){
                alert('网络错误，请稍后重试');
            }
        });
    }

    mg_blance()
    function mg_blance() {
        $('#mg_blance').html('加载中');
        var dat={};
        dat.uid=uid;
        dat.action='b';
        $.ajax({
            type: 'POST',
            url:'mg/mg_api.php?_='+Math.random(),
            data:dat,
            dataType:'json',
            success:function(ret){
                if(ret.err==0){
                    // console.log(ret)
                    $('#mg_blance').html(ret.balance_mg);
                }
                else{
                    $('#mg_blance').html('0.00');
                }
            },
            error:function(ii,jj,kk){
                alert('网络错误，请稍后重试');
            }
        });
    }

    cq_blance()
    function cq_blance() {
        $('#cq_blance').html('加载中');
        var dat={};
        dat.uid=uid;
        dat.action='b';
        $.ajax({
            type: 'POST',
            url:'cq9/cq9_api.php?_='+Math.random(),
            data:dat,
            dataType:'json',
            success:function(ret){
                //console.log(ret)
                if(ret.err==0){
                    $('#cq_blance').html(ret.msg.cq_balance);
                }
                else{
                    $('#cq_blance').html('0.00');
                }
            },
            error:function(ii,jj,kk){
                alert('网络错误，请稍后重试');
            }
        });
    }

    mw_blance()
    function mw_blance() {
        $('#mw_blance').html('加载中');
        var dat={};
        dat.uid=uid;
        dat.action='b';
        $.ajax({
            type: 'POST',
            url:'mw/mw_api.php?_='+Math.random(),
            data:dat,
            dataType:'json',
            success:function(ret){
                if(ret.err==0){
                    // console.log(ret)
                    $('#mw_blance').html(ret.msg.mw_balance);
                    $('#mwmoney', window.parent.body.document).html(ret.msg.mw_balance) ; // 更新父级余额
                }
                else{
                    $('#mw_blance').html('0.00');
                }
            },
            error:function(ii,jj,kk){
                alert('网络错误，请稍后重试');
            }
        });
    }

    fg_blance()
    function fg_blance() {
        $('#fg_blance').html('加载中');
        var dat={};
        dat.uid=uid;
        dat.action='b';
        $.ajax({
            type: 'POST',
            url:'fg/fg_api.php?_='+Math.random(),
            data:dat,
            dataType:'json',
            success:function(ret){
                if(ret.err==0){
                     //console.log(ret)
                    $('#fg_blance').html(ret.msg.fg_balance);
                    $('#fgmoney', window.parent.body.document).html(ret.msg.fg_balance) ; // 更新父级余额
                }
                else{
                    $('#fg_blance').html('0.00');
                }
            },
            error:function(ii,jj,kk){
                alert('网络错误，请稍后重试');
            }
        });
    }
</script>