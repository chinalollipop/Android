<?php

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "../include/address.mem.php";
require ("../include/config.inc.php");
require ("../include/define_function_list.inc.php");

$uid=$_REQUEST['uid'];
$langx=$_SESSION['langx'];

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
    exit;
}
$DepositTimes = $_SESSION['DepositTimes'] ; // 用于限制用户可见存款方式

$sUname = $_SESSION['UserName'];

$sSql = "select bank_name,bank_account,bank_addres,bank_user,bankcode,id from `".DBPREFIX."gxfcy_bank_data` where FIND_IN_SET('{$_SESSION['pay_class']}',class) AND `status` = 1 and `issaoma`='0' and `bank_name`!='支付宝' and {$DepositTimes} >= `mindeposit` and  {$DepositTimes} <= `maxdeposit`  ";
$oRes = mysqli_query($dbLink,$sSql);

$iCou=mysqli_num_rows($oRes);

if( $iCou == 0 ){
    exit('支付方式有误，请重新选择~！');
}
$aData = [];
while($aRow = mysqli_fetch_assoc($oRes)){
    $aData[]=$aRow;
}
//var_dump($aData); die;
$intnum = rand(10000,9999999); // 随机整数，防止重复提交
?>
<html ><head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>在线存款</title>
    <link rel="stylesheet" type="text/css" href="../../../style/onlinepay.css?v=<?php echo AUTOVER; ?>">
</head>
<body >
<div class="company_pay">
    <div id="__calendarPanel" style="position:absolute;visibility:hidden;z-index:9999;background-color:#FFFFFF;border:1px solid #666666;width:200px;height:216px;">
        <iframe name="__calendarIframe" id="__calendarIframe" width="100%" height="100%" scrolling="no" frameborder="0" style="margin:0px;"></iframe>
    </div>
    <table class="list-tab">
        <thead>
        <tr>
            <td colspan="6"><h1 class="c3">汇款详细账户资料</h1></td>
        </tr>
        </thead>
        <tbody>
        <?php foreach ( $aData as $k => $v ){  ?>
            <tr>
                <td width="90"><?php echo $v['bank_name']?></td>
                <td width="150"><?php echo $v['bank_account']?></td>
                <td width="150">开户名</td>
                <td width="150"><?php echo $v['bank_user']?></td>
                <td width="150">开户行所在城市</td>
                <td width="150"><?php echo $v['bank_addres']?></td>
            </tr>
        <?php } ?>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="6" style="line-height:20px;">
                <strong>温馨提示：</strong>
                <br>
                一、请在金额转出之后务必填写网页下方的汇款信息表格，以便我们财务人员能及时为您确认添加金额到您的会员账户。
                <br>
                二、本公司最低存款金额为100元，每次存款赠送最高1%红利。
            </td>
        </tr>
        </tfoot>
    </table>
    <div class="tips">
        此存款信息只是您汇款详情的提交，并非代表存款，您需要自己通过ATM或网银转帐到本公司提供的账户后，填写提交此信息，待工作人员审核充值！
    </div>
    <form id="form_company" name="form_company" action="remittance_save.php" method="post" onsubmit="return SubInfo();">
        <input id="uid" name="uid" value="<?php echo $uid?>" type="hidden">
        <input id="langx" name="langx" value="<?php echo $langx?>" type="hidden">
        <input id="payid" name="payid" value="" type="hidden">
        <input id="randomnum" name="randomnum" value="<?php echo $intnum?>" type="hidden">
        <table class="frm-tab">
            <thead>
            <tr>
                <th class="c3">汇款信息提交</th>
                <td><h4><a href="#">汇款信息回查</a></h4></td> <!--  onclick="url('save_list.php?s_time=2018-01-11&amp;e_time=2018-01-24&amp;uid=a026b242d3bc883d9ebcra0&amp;langx=zh-cn')" -->
            </tr>
            </thead>
            <tbody>
            <tr>
                <th>用户帐号:</th>
                <td><?php echo $sUname?></td>
            </tr>
            <tr>
                <th><span>*</span> 存款金额:</th>
                <td ><input name="v_amount" type="text" class="fast_choose" id="v_amount" onkeyup="clearNoNum(this);" size="22"></td>
            </tr>
            <tr>
                <th><span></span> 快速设置金额:</th>
                <td >
                    <a href="javascript:change(100)" class="quickM">100</a>
                    <a href="javascript:change(500)" class="quickM">500</a>
                    <a href="javascript:change(1000)" class="quickM">1000</a>
                    <a href="javascript:change(5000)" class="quickM">5000</a>
                    <a href="javascript:change(10000)" class="quickM">10000</a>
                    <a href="javascript:change(50000)" class="quickM">50000</a>
                </td>
            </tr>
            <tr>
                <th><span>*</span> 存款人姓名:</th>
                <td> <input name="v_Name" type="text" id="v_Name"  onfocus="javascript:this.select();" size="34"></td>
            </tr>
            <tr>
                <th><span>*</span> 汇款银行:</th>
                <td>
                    <select id="IntoBank" name="IntoBank"></select>
                </td>
            </tr>
            <tr>
                <th><span>*</span> 汇款日期:</th>
                <td>
                    <input name="cn_date" type="text" id="cn_date" value="" size="22" readonly /> <!-- onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})" -->

                </td>
            </tr>
            <tr>
                <th><span>*</span> 汇款方式:</th>
                <td>
                    <select id="InType" name="InType" onchange="showType()">
                        <option value="">请选择汇款方式</option>
                        <option value="银行柜台">银行柜台</option>
                        <option value="ATM现金">ATM现金</option>
                        <option value="网银转账">网银转账</option>
                        <option value="支付宝">支付宝</option>
                        <option value="0" data-val="other">其他[手动输入]</option>
                    </select>
                    <input id="v_type" name="v_type" type="text" class="other_in"  placeholder="请输入其他汇款方式" style="display: none">
                    <input type="hidden" id="IntoType" name="IntoType" value="">
                </td>
            </tr>
            <tr>
                <th><span></span> 存款备注:</th>
                <td>
                    <input type="text" name="memo" placeholder="可填入银行转账单号等信息" id="remark" maxlength="50">
                </td>
            </tr>
            <tr>
                <td></td>
                <td style="height:36px"><input type="submit" name="SubTran" value="提交信息" id="SubTran" class="btn2"> <input type="reset" name="btnReset" value="重新填写" id="btnReset" class="btn2"></td>
            </tr>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="2" style="line-height:20px;">
                    <strong>汇款信息提交说明：</strong>
                    <br>(1).请按表格填写准确的汇款转账信息,确认提交后相关财务人员会即时为您查询入款情况!
                    <br>(2).请您在转账金额后面加个尾数,例如:转账金额 1000.66 或 1000.88 等,以便相关财务人员更快确认您的转账金额并充值!
                    <br>(3).如有任何疑问,您可以联系在线客服,在线客服为您提供365天×24小时不间断的友善和专业客户咨询服务!
                </td>
            </tr>
            </tfoot>
        </table>
    </form>


</div>

<script type="text/javascript" src="../../../js/jquery.js"></script>
<script type="text/javascript" src="../../../js/common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../../js/popup.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../../js/register/laydate.min.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../../js/depositwithdraw/ZeroClipboard.js?v=<?php echo AUTOVER; ?>"></script>
<script>

    /* 公司入款 开始*/
    var IntoBank = document.form_company.IntoBank;
    // 银行卡列表json结构数据
    var odata = <?php echo json_encode($aData); ?>;
    var str='';
    // console.log(odata)
    str +='<option value="">请选择转入银行</option>';
    $.each(odata, function (i,v) {
        if(v['bankcode'] !='KSCZ'){
            str +='<option data_id="'+v['id']+'" value="'+v['bank_name']+'-'+v['bank_user']+'">'+v['bank_name']+v['bank_user']+'</option>' ;
        }

    });
    IntoBank.innerHTML=str;
    $("#IntoBank").change(function () {
        var bankid = $(this).find("option:selected").attr("data_id");
        $("#payid").val(bankid);
    })



    function next_checkNum_img(){
        document.getElementById("checkNum_img").src = "yzm.php?"+Math.random();
        return false;
    }

    // 时间配置
    var beginTime = {
        elem: '#cn_date',
        format: 'YYYY-MM-DD hh:mm:ss',
        istime: true ,
        istoday: false ,
        defaultValue:setAmerTime('#cn_date'),
        choose: function (datas) {

        }
    };
    laydate(beginTime);

    function showType(){
        if($('#InType').val()=='0'){
            $('#v_type').css('display','');
            $('#tr_v').css('display','none');
        }/*else if($('InType').value=='网银转账'){
            $('tr_v').style.display='';
            $('v_Name').value='请输入持卡人姓名';
            $('v_type').style.display='none';
            $('IntoType').value=$('InType').value;
        }*/else{
            $('#v_type,#tr_v').css('display','none');
            $('#IntoType').val($('#InType').val());
        }
    }
    function SubInfo(){
        var hk	=	$('#v_amount').val();
        var ty_val = $('#InType').val() ;
        var $amount = $('#v_amount') ;
        var name = $('#v_Name').val() ;
        var v_val = $('#v_type').val() ;
        var in_type = $('#IntoType').val() ;
        if(hk==''){
            alert('请输入转账金额');
            $amount.focus();
            return false;
        }else{
            hk = hk*1;
            if(hk<100){
                alert('转账金额最低为：100元');
                $amount.select();
                return false;
            }
        }
        /* if(ty_val =='网银转账'){*/
        if(name !=''&& name!='请输入持卡人姓名' && name.length>1 && name.length<20){
            var tName = name;
            var yy = tName.length;
            for(var xx=0;xx<yy; xx++){
                var zz = tName.substring(xx,xx+1);
                if(zz!='·'){
                    if(!isChinese(zz)){
                        alert('请输入中文持卡人姓名,如有其他疑问,请联系在线客服');
                        $('#v_Name').focus() ;
                        return false;
                    }
                }
            }
        }else{
            alert('为了更快确认您的转账,网银转账请输入持卡人姓名');
            $('#v_Name').focus() ;
            return false;
        }
        /* }*/
        if($('#IntoBank').val()==''){
            alert('为了更快确认您的转账,请选择转入银行');
            return false;
        }
        /* if($('#cn_date').val()==''){
             alert('请选择汇款日期');
             return false;
         }*/

        if(ty_val ==''){
            alert('为了更快确认您的转账,请选择汇款方式');
            return false;
        }
        if(ty_val =='0'){
            if(v_val !=''){
                in_type = v_val ;
            }else{
                alert('请输入其它汇款方式');
                return false;
            }
        }

        $('#SubTran').attr('disabled','disabled');
        $('#form_company').submit();
    }

    /*    function alertMsg(i){
            if(i==1){
                alert('阁下,您好:\n您已经成功提交一笔 汇款信息 未处理,请等待处理后再提交新的汇款信息! ');
                LoadValImg();
            }
            if(i==2){
                alert('汇款信息提交成功，请等待处理');
                window.location.href='ScanTrans.aspx';
            }
        }*/


    /* 公司入款 结束*/
</script>

</body></html>
