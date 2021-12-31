<?php
session_start();

include "../../../../app/member/include/config.inc.php";
include_once "../../../../../common/bankNameList.php";
$uid = $_SESSION['Oid'];
if( !isset($uid) || $uid == "" ) {
    echo "<script>window.location.href='/'</script>";
    exit;
}
$username=$_SESSION['UserName'];
$onlinetime=$_SESSION['OnlineTime'];
$Alias=$_SESSION['Alias'];
$birthday=$_SESSION['birthday'];
$type = isset($_REQUEST['type'])?$_REQUEST['type']:'';

$bankList = returnBnakName();


?>

<link rel="stylesheet" type="text/css" href="../../style/common.css?v=<?php echo AUTOVER; ?>" >
<link rel="stylesheet" type="text/css" href="../../style/index_login.css?v=<?php echo AUTOVER; ?>" >
<link rel="stylesheet" type="text/css" href="../../style/memberaccount.css?v=<?php echo AUTOVER; ?>" >
<style>
    body, html{background: none;min-width: 200px}
</style>

<form class="BankCardInfo input-group clearfix flex-col">
    <div class="form-group">
        <label for="username">真实姓名 (不可修改)</label>
        <select type="text" id="username" class="" maxlength="10">
            <option value="<?php echo returnRealName($Alias);?>"><?php echo returnRealName($Alias);?></option>
        </select>
    </div>
    <div class="form-group bankInput">
        <div class="forValidations">
            <div class="custom-select-bankname">
                <div class="select-trigger"></div>
                <select class="bankname form-control">
                    <option value="1" selected="selected">***选择银行***</option>
                    <?php
                        foreach ($bankList as $key=>$v){
                           echo '<option value="'.$v.'" '.($v==$_SESSION['Bank_Name']?'selected':'').'>'.$v.'</option>';
                        }
                    ?>
                </select>
                <label class="titleLabel" for="bankname">选择银行 <span class="subTitle">(建议选用您易记银行卡)</span></label>
            </div>
        </div>
    </div>
    <div class="form-group input_same">
        <div class="forValidations">
            <input type="text" onkeyup="this.value=this.value.replace(/\D/g,'')" class="bankaccount " maxlength="24" name="bank-account" value="<?php echo returnBankAccount($_SESSION['Bank_Account']);?>">
            <label class="titleLabel backaccount" for="bankaccount">银行卡号  <span class="subTitle">(您的真实姓名对应银行帐户卡号)</span></label>
        </div>
    </div>
    <div class="form-group input_same">
        <input type="text" class="bankaddress" maxlength="20" value="<?php echo $_SESSION['Bank_Address'];?>">
        <label class="titleLabel" for="bankaddress">开户网点 </label>
    </div>
    <div class="form-group input_same">
        <div class="forValidations">
            <input type="text" onkeyup="this.value=this.value.replace(/[^\w\.\/]/ig,'')" class="usdtaddress " maxlength="150" name="usdt-address" value="<?php echo returnBankAccount($_SESSION['Usdt_Address']);?>" readonly>
            <label class="titleLabel" for="usdtaddress">USDT提币地址<span class="subTitle">（TRC20的提币地址）</span></label>
            <p class="red_color" style="margin-top: -15px;">如需修改提币地址，请联系客服</p>
        </div>
    </div>
    <!-- 原银行账号 -->
    <!--<input type="hidden" class="y_bankaccount " maxlength="24" value="<?php /*echo $_SESSION['Bank_Account'];*/?>" readonly>-->
</form>
<div class="modalFooter" style="margin-bottom: 10px;"><button type="button" class="btn-add change_bank_submit">确认添加</button></div>


<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/layer/layer.js"></script>
<script type="text/javascript" src="/js/loadpage_common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript">


    $(function () {

        // 更换银行卡
        function changeUserBank(){
                // 提交更改
                var bankloadfalg = false ;
                $('body').on('click','.change_bank_submit',function () {
                    if(bankloadfalg){
                        return ;
                    }
                    var bank_acc = $(".bankaccount").val(); // 银行账号
                    //var y_bank_acc = $(".y_bankaccount").val(); // 原银行账号
                    var hide_account = '<?php echo returnBankAccount($_SESSION['Bank_Account']);?>';
                    var usdt_add = $(".usdtaddress").val(); // usdt地址

                    var dat = {};
                    dat.uid = '<?php echo $uid;?>';
                    dat.bank_name = $(".bankname").val(); // 银行名称
                    dat.bank_address = $(".bankaddress").val(); // 银行地址
                    dat.bank_account = bank_acc;
                    //dat.usdt_address = usdt_add;
                    // console.log(dat.bank_name)
                    if(bank_acc==hide_account){ // 没有变化
                        //dat.bank_account = y_bank_acc;
                        layer.msg('未更换银行账号!',{time:alertTime});
                        return;
                    }
                    if(!usdt_add){
                        if(dat.bank_name==1 || dat.bank_name==''){
                            layer.msg('请选择银行!',{time:alertTime});
                            return ;
                        }
                        if(!dat.bank_account || dat.bank_account==''){
                            layer.msg('请输入银行卡号!',{time:alertTime});
                            return ;
                        }
                        if(!dat.bank_address || dat.bank_address==''){
                            layer.msg('请输入银行地址!',{time:alertTime});
                            return ;
                        }
                    }

                    bankloadfalg = true ;
                    $.ajax({
                        type: 'POST',
                        url: '/app/member/money/updatebank.php',
                        data: dat,
                        dataType: 'json',
                        success: function (res) {
                            if(res){

                                layer.msg(res.msg,{time:alertTime});
                                if (res.code ==1) { // 更换成功
                                    parent.$(".show_bank_name").html(res.resdata.Bank_Name); // 银行名称
                                    parent.$("#Bank_Name").val(res.resdata.Bank_Name); // 银行名称
                                    parent.$(".show_bank_account").html(res.resdata.Bank_Account_hide);  // 银行帐号
                                   // parent.$("#Bank_Account").val(res.resdata.Bank_Account);  // 银行帐号
                                    parent.$(".show_bank_address").html(res.resdata.Bank_Address);  // 银行帐号
                                    parent.$("#Bank_Address").val(res.resdata.Bank_Address);  // 银行帐号
                                    //parent.$("#Usdt_Address").val(res.resdata.Usdt_Address);  // USDT帐号
                                    parent.$(".show_usdt_account").html(res.resdata.Usdt_Address_hide);  // USDT帐号
                                    if(res.resdata.Usdt_Address){
                                        parent.$(".has_usdt").show();  // USDT金额显示
                                    }

                                   setTimeout(function () {
                                       bankloadfalg = false ;
                                       parent.layer.closeAll() ;
                                   },alertTime)

                                }else{
                                    bankloadfalg = false ;
                                }
                            }
                        },
                        error: function (res) {
                            bankloadfalg = false ;
                            layer.msg('数据更新失败，请稍后再试!',{time:alertTime});

                        }
                    });

                })


        }

        changeUserBank();


    })



</script>