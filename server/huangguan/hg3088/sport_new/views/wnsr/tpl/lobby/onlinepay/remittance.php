<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "../../../../../app/member/include/address.mem.php";
require ("../../../../../app/member/include/config.inc.php");
require ("../../../../../app/member/include/define_function_list.inc.php");

$uid=$_SESSION['Oid'];
$langx=$_SESSION['langx'];


$DepositTimes = $_SESSION['DepositTimes'] ; // 用于限制用户可见存款方式

$sUname = $_SESSION['UserName'];

$sSql = "select bank_name,bank_account,bank_addres,bank_user,bankcode,id from `".DBPREFIX."gxfcy_bank_data` where FIND_IN_SET('{$_SESSION['pay_class']}',class) AND `status` = 1 AND `issaoma`='0' AND `bank_name`!='支付宝' AND {$DepositTimes} >= `mindeposit` AND  {$DepositTimes} <= `maxdeposit`  ";
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

?>
<style>
    .incomeTable a{padding:1px 5px;background:#ffb400;border-radius:5px;color:#fff;margin-left:5px}
</style>
<link rel="stylesheet" type="text/css" href="<?php echo TPL_NAME;?>style/memberaccount.css?v=<?php echo AUTOVER; ?>" >

<div class="memberWrap">
    <div class="memberTit clearfix">
        <span class="account_icon fl titImg deposit_nav"></span>
        <a class="fr to_deposit" href="javascript:;"> <img class="backImg" src="/images/back.png" alt=""></a>
    </div>
    <div class="payWay">
        <div class="payWayTit">汇款详细账户资料</div>
        <table  class="incomeTable" id="show_bank_list" cellspacing="0" cellpadding="0">
            <?php foreach ( $aData as $k => $v ){  ?>
                <tr>
                    <td ><?php echo $v['bank_name']?></td>
                    <td > <span class="bank_useraccount_<?php echo $k;?>"> <?php echo $v['bank_account']?></span> <a href="javascript:;" data-clipboard-target=".bank_useraccount_<?php echo $k;?>"> 复制 </a> </td>
                    <td >开户名</td>
                    <td > <span class="bank_username_<?php echo $k;?>"><?php echo $v['bank_user']?></span> <a href="javascript:;" data-clipboard-target=".bank_username_<?php echo $k;?>"> 复制 </a></td>
                    <td >开户行所在城市</td>
                    <td><?php echo $v['bank_addres']?></td>
                </tr>
            <?php } ?>

        </table>
        <div class="warn" style="padding:15px 40px">
            <h2>温馨提示： </h2>
            <p>一、请在金额转出之后务必填写网页下方的汇款信息表格，以便我们财务人员能及时为您确认添加金额到您的会员账户。 <br>二、本公司最低存款金额为100元，每次存款赠送最高1%红利。 </p>
        </div>
    </div>
    <div class="payRemid">*此存款信息只是您汇款详情的提交，并非代表存款，您需要自己通过ATM或网银转帐到本公司提供的账户后，填写提交此信息，待工作人员审核充值！</div>
    <div class="payWay">
        <form class="deposit_form" onsubmit="return false;">
            <div class="payWayTit">汇款信息提交</div>
            <table class="tableSubmit" cellspacing="0" cellpadding="0">

                <tbody>
                <tr>
                    <td>用户帐号:</td>
                    <td><?php echo $sUname?></td>
                </tr>
                <tr>
                    <td><span class="red">*</span> 存款金额:</td>
                    <td ><input name="v_amount" type="number" step="0.01" class="fast_choose" id="v_amount" onkeyup="clearNoNum(this);" size="22"></td>
                </tr>
                <tr>
                    <td><span></span> 快速设置金额:</td>
                    <td class="moneyType">
                        <a href="javascript:change(100)" class="quickM">100</a>
                        <a href="javascript:change(500)" class="quickM">500</a>
                        <a href="javascript:change(1000)" class="quickM">1000</a>
                        <a href="javascript:change(5000)" class="quickM">5000</a>
                        <a href="javascript:change(10000)" class="quickM">10000</a>
                        <a href="javascript:change(50000)" class="quickM">50000</a>
                    </td>
                </tr>
                <tr>
                    <td><span class="red">*</span> 存款人姓名:</td>
                    <td> <input name="v_Name" type="text" id="v_Name"  onfocus="javascript:this.select();" size="34"></td>
                </tr>
                <tr>
                    <td><span class="red">*</span> 汇款银行:</td>
                    <td>
                        <select id="IntoBank" name="IntoBank">
                            <option value="">请选择转入银行</option>
                            <?php
                            foreach ($aData as $k => $v){
                                if($v['bankcode']  !='KSCZ'){
                                    echo '<option data_id="'.$v['id'].'" value="'.$v['bank_name'].'-'.$v['bank_user'].'">'.$v['bank_name'].$v['bank_user'].'</option>' ;
                                }
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><span class="red">*</span> 汇款日期:</td>
                    <td>
                        <input name="cn_date" type="text" id="cn_date" value="" size="22" readonly /> <!-- onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})" -->

                    </td>
                </tr>
                <tr>
                    <td><span class="red">*</span> 汇款方式:</td>
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
                    <td><span></span> 存款备注:</td>
                    <td>
                        <input type="text" id="remark" name="memo" placeholder="可填入银行转账单号等信息"  maxlength="50">
                    </td>
                </tr>

                </tbody>

            </table>

            <div class="btnWrap clearfix">
                <button class="nextBtn" id="btnReset"  onclick="$('.deposit_form')[0].reset()">重新填写</button>
                <button class="nextBtn" id="SubTran">提交信息</button>

            </div>
            <div class="warn" style="padding:15px 40px">
                <h2>汇款信息提交说明：  </h2>
                <p>(1).请按表格填写准确的汇款转账信息,确认提交后相关财务人员会即时为您查询入款情况! <br>
                    (2).请您在转账金额后面加个尾数,例如:转账金额 1000.66 或 1000.88 等,以便相关财务人员更快确认您的转账金额并充值!
                    <br>
                    (3).如有任何疑问,您可以联系在线客服,在线客服为您提供365天×24小时不间断的友善和专业客户咨询服务!
                </p>
            </div>
        </form>

    </div>
</div>



<script type="text/javascript" src="/js/common.js?v=<?php echo AUTOVER; ?>"></script>

<script>

    $(function () {
        // 银行卡列表json结构数据
        //var odata = <?php //echo json_encode($aData); ?>//;

        laydate.render({
            elem: '#cn_date',
            format: 'yyyy-MM-dd HH:mm:ss',
            istime: true ,
            defaultValue:setAmerTime('#cn_date'),
            done: function(value, date){ //时间改变回掉
                // console.log(value)
            }
        });

        function SubInfo(){
            $('#SubTran').on('click',function () {
                var $amount = $('#v_amount') ;
                var hk	=	$amount.val();
                var ty_val = $('#InType').val() ;
                var name = $('#v_Name').val() ;
                var v_val = $('#v_type').val() ;
                var in_type = $('#IntoType').val() ;
                var bankid = $('#IntoBank').find("option:selected").attr("data_id");
                var intobank = $('#IntoBank').val();
                var cndate = $('#cn_date').val();
                var remark = $('#remark').val();
                var randomnum = indexCommonObj.randomWord(false, 32) ;

                if(hk==''){
                    layer.msg('请输入转账金额',{time:alertTime});
                    $amount.focus();
                    return false;
                }else{
                    hk = hk*1;
                    if(hk<100){
                        layer.msg('转账金额最低为：100元',{time:alertTime});
                        $amount.select();
                        return false;
                    }
                }
                if(name !=''&& name!='请输入持卡人姓名' && name.length>1 && name.length<20){
                    var tName = name;
                    var yy = tName.length;
                    for(var xx=0;xx<yy; xx++){
                        var zz = tName.substring(xx,xx+1);
                        if(zz!='·'){
                            if(!isChinese(zz)){
                                layer.msg('请输入中文持卡人姓名,如有其他疑问,请联系在线客服',{time:alertTime});
                                $('#v_Name').focus() ;
                                return false;
                            }
                        }
                    }
                }else{
                    layer.msg('为了更快确认您的转账,网银转账请输入持卡人姓名',{time:alertTime});
                    $('#v_Name').focus() ;
                    return false;
                }

                if(intobank==''){
                    layer.msg('为了更快确认您的转账,请选择转入银行',{time:alertTime});
                    return false;
                }

                if(ty_val ==''){
                    layer.msg('为了更快确认您的转账,请选择汇款方式',{time:alertTime});
                    return false;
                }
                if(ty_val =='0'){
                    if(!v_val){
                        layer.msg('请输入其它汇款方式',{time:alertTime});
                        return false;
                    }

                }
                $('#SubTran').attr('disabled','disabled');
                // $('#form_company').submit();
                var actionurl = "/app/member/onlinepay/remittance_save.php" ;
                var pars = {
                    randomnum:randomnum,
                    payid:bankid,
                    v_amount:hk,
                    v_Name:name,
                    IntoBank:intobank,
                    cn_date:cndate,
                    InType:ty_val,
                    v_type:v_val,
                    IntoType:in_type,
                    memo:remark,

                };
                $.ajax({
                    type : 'POST',
                    url : actionurl ,
                    data : pars,
                    dataType : 'json',
                    success:function(res) {
                        if(res){
                            $('#SubTran').removeAttr('disabled');
                            layer.msg(res.describe,{time:alertTime});
                            if(res.status == '200'){
                               // window.location.href = '/' ;
                                indexCommonObj.loadUserPlatformPage() ; // 存款成功跳转到额度转换
                            }
                        }

                    },
                    error:function(){
                        $('#SubTran').removeAttr('disabled');
                        layer.msg('稍后请重试',{time:alertTime});
                    }
                });

            })

        }

        SubInfo();

        // 复制
        $('#show_bank_list').find('a').each(function (num) {
            // console.log(num);
            var clipboard = new ClipboardJS(this, {
                text: function () {
                    return $(this).prev().text();
                }
            });
            clipboard.on('success', function (e) {
                //console.log(e);
                layer.msg('复制成功!',{time:alertTime});
                e.clearSelection();
            });
            clipboard.on('error', function (e) {
                //console.log(e);
                layer.msg('请选择“拷贝”进行复制!',{time:alertTime});
            });
        });

    })


    function showType(){
        if($('#InType').val()=='0'){
            $('#v_type').css('display','');
            $('#tr_v').css('display','none');
        }/*else if($('InType').value=='网银转账'){
            $('tr_v').style.display='';
            $('v_Name').value='请输入持卡人姓名';
            $('v_type').style.display='none';

        }*/else{
            $('#v_type,#tr_v').css('display','none');
            $('#IntoType').val($('#InType').val());
        }
    }






</script>

