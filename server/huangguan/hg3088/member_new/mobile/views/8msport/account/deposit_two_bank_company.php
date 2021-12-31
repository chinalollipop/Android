<?php
session_start();
// 公司卡号入款
// 输入金额，添加记录入库
include_once('../../../include/config.inc.php');

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    if($_REQUEST['appRefer']==13 || $_REQUEST['appRefer']==14) {
        $status = '401.1';
        $describe = '请重新登录!';
        original_phone_request_response($status,$describe);
    }else {
        echo "<script>alert('请重新登录!');window.location.href='../login.php';</script>";
        exit;
    }
}

$uid=$_SESSION['Oid'];
$langx=$_SESSION['Language'];
$username = $_SESSION['UserName'];
$DepositTimes = $_SESSION['DepositTimes'] ; // 用于限制用户可见存款方式
$sSql = "select bankcode,bank_account,bank_name,bank_user,id,bank_addres from `".DBPREFIX."gxfcy_bank_data` WHERE `status` = 1 AND `issaoma`='0' AND FIND_IN_SET('{$_SESSION['pay_class']}',class) AND `bank_name`!='支付宝' AND {$DepositTimes} >= `mindeposit` AND  {$DepositTimes} <= `maxdeposit` ";
$oRes = mysqli_query($dbLink,$sSql);
$iCou = mysqli_num_rows($oRes);

if( $iCou==0 ){
    if($_REQUEST['appRefer']==13 || $_REQUEST['appRefer']==14) {
        $status = '401.2';
        $describe = '支付方式有误，请重新选择';
        original_phone_request_response($status,$describe);
    }else {
        echo "<script>alert('支付方式有误，请重新选择');history.go(-1)</script>";
        exit;
    }
}
$aData = [];
while($aRow = mysqli_fetch_assoc($oRes)){
    if ($aRow['bankcode'] != 'KSCZ'){
        $aData[]=$aRow;
    }
}

if($_REQUEST['appRefer']==13 || $_REQUEST['appRefer']==14) {
    $status = '200';
    $describe = 'success';
    original_phone_request_response($status,$describe,$aData);
}

$membermessage = getMemberMessage($username,'1'); // 存款短信

?>
<html class="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="HandheldFriendly" content="true"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <!--<link href="../style/font-awesome.min.css" rel="stylesheet" type="text/css" media="screen"/>-->
    <link href="../style/iphone.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css" media="screen"/>
    <link rel="stylesheet" href="../../../style/icalendar.css?v=<?php echo AUTOVER; ?>">

    <title class="web-title"></title>
<style type="text/css">
    .bank_list{text-align: left;padding: 0 3%;}
    .bank_list a {padding: 3px 6px;border-radius: 5px;margin: 0 3px;}
    .bank_list .bank_list_line {padding-bottom: 10px;border-bottom: 1px solid #EEE;margin-bottom: .5rem;font-size: 1.1rem;color: #5ea0ea;}
</style>
</head>
<body>
<div id="container">
    <!-- 头部 -->
    <div class="header ">

    </div>

    <!-- 中间内容 -->
    <div class="content-center deposit-two deposit-different">
        <!-- 公司入款开始 -->
        <div class="" data-area="bank_pay">
           <!-- <form method="post" name="companypay" id="companypay" action="deposit_two_bank_company_save.php">-->
            <div class="bg_yy">
                <div class="tip_title"><span class="linear-color-1">2</span>填写存款金额</div>
                <table class="money moneychoose" >

                    <tbody>
                    <tr>
                        <td><span>100</span></td>
                        <td><span>300</span></td>
                        <td><span>500</span></td>
                        <td><span>800</span></td>
                    </tr>
                    <tr>
                        <td><span>1000</span></td>
                        <td><span>2000</span></td>
                        <td><span>3000</span></td>
                        <td><span>5000</span></td>
                    </tr>
                    </tbody>
                </table>
                <div class="form-item">
                    <span class="label clearfix">
                        <span class="text">汇款金额</span>
                        <span class="line"></span>
                    </span>
                    <span class="textbox">
                            <input type="text" name="v_amount" class="deposit-input money-textbox" placeholder="￥请输入汇款金额" />
                            <a class="textbox-close" href="javascript:;">╳</a>
                    </span>
                </div>
            </div>
            <div class="bg_yy">
                <div class="tip_title"><span class="linear-color-1">3</span>填写存款人信息</div>
                <div id="seebank" class="seebank-div linear-color-1" >查看银行账号</div>
                <div id="show_bank_list" class="bank_list" >
                    <?php
                    foreach ( $aData as $k => $v ){
                        echo '<div class="bank_list_line">'.$v['bank_name'].'<br><span class="bank_username_'.$k.'">'.$v['bank_user'].'</span><a class="linear-color-1 cp_bank_username_'.$k.'" data-clipboard-target=".bank_username_'.$k.'">复制</a><br><span class="bank_useraccount_'.$k.'">'.$v['bank_account'].'</span><a class="linear-color-1 cp_bank_username_'.$k.'" data-clipboard-target=".bank_useraccount_'.$k.'">复制</a> </div> ';
                    }
                    ?>
                    <!--                    中国招商银行|饶金志|621483271*******<br><br>中国建设银行|华笃锋|621700014002*******<br><br>中国农业银行|袁字英|622848078974*******<br><br>-->

                </div>
                <div class="form-item form-select">
                        <span class="label">
                            <span class="text">存款人姓名</span>
                            <span class="line"></span>
                        </span>
                    <span class="textbox">
                            <input type="text" name="v_Name" id="v_Name" class="deposit-input" placeholder="请输入存款人姓名" />
                        <a class="textbox-close" href="javascript:;">╳</a>
                    </span>
                </div>

                <div class="form-item form-select">
                    <span class="label">
                        <span class="text">转入银行</span>
                        <span class="line"></span>
                    </span>
                    <span class="dropdown textbox">
                            <select name="IntoBank" id="IntoBank"></select>
                        </span>
                    <!-- <span class="arrow"></span> -->
                </div>
                <div class="form-item form-select">
                   <span class="label">
                        <span class="text">汇款方式</span>
                        <span class="line"></span>
                    </span>
                    <span class="dropdown textbox">
                            <select name="InType" id="InType">
                                <option value="">请选择汇款方式</option>
                                <option value="银行柜台">银行柜台</option>
                                <option value="ATM现金">ATM现金</option>
                                <option value="ATM卡转">ATM卡转</option>
                                <option value="网银转账">网银转账</option>
                                <option value="其他">其他</option>
                            </select>
                        </span>
                </div>

                <div class="form-item">
                        <span class="label">
                            <span class="text">汇款时间</span>
                            <span class="line"></span>
                        </span>
                    <span class="textbox">
                            <input class="deposit-input" name="cn_date" placeholder="选择日期" type="text" id="time_textbox" readonly />
                        </span>
                </div>

                <div id="other" >
                    <div class="form-item">
                            <span class="label">
                                <span class="text">备注</span>
                                <span class="line"></span>
                            </span>
                        <span class="textbox">
                                <input type="text" name="memo" placeholder="可填入银行转账单号等信息" id="remark" maxlength="50">
                            </span>
                    </div>

                </div>
            </div>

                <div class="btn-wrap">
                    <a href="javascript:;" class="zx_submit" onclick="depositeBankAction()">确认存款</a>
                </div>
                <input type="hidden" name="payid" id="payid" value=""/>
           <!-- </form>-->

        </div>


    </div>

    <!-- 底部 -->
    <div id="footer">

    </div>
</div>


<script type="text/javascript" src="../../../js/zepto.min.js"></script>
<script type="text/javascript" src="../../../js/animate.js"></script>
<script type="text/javascript" src="../../../js/zepto.animate.alias.js"></script>
 <script type="text/javascript" src="../../../js/main.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../../js/icalendar.min.js"></script>
<script type="text/javascript" src="../../../js/clipboard.min.js"></script>
<!--<script type="text/javascript" src="../../../js/layer/mobile/layer.js"></script>-->
<script type="text/javascript">
    /* 公司入款 开始*/
    var uid = '<?php echo $uid?>' ;
    var usermon = getCookieAction('member_money') ; // 获取信息cookie
    var alerttitle ={ // 配置提示信息
        int:'汇款金额必须为数字(最多两位小数)！' ,
        mon:'汇款金额不能小于100元！' ,
        bank:'请输入转入银行！' ,
        banktype:'请选择汇款方式！' ,
        time:'请选择汇款时间！' ,
        realname:'请输入存款人姓名！',
        remark:'请输入存款备注！',
    }
    // 进来判断属于哪种支付方式才需要显示
    var urltype = getStrParam().type ; // 当前支付类型
    var urltitle = getStrParam().title ; // 支付方式名称

    var checkedMoney = '',
        $reset = $('.reset') ;

    var calendar = new lCalendar();   // 时间插件初始化 ，公司入款
    var zfbcalendar = new lCalendar();   // 时间插件初始化 ，支付宝支付
    calendar.init({
        'trigger': '#time_textbox',
        'type': 'datetime',
        defaultValue:setAmerTime('#time_textbox'),
    });

    // 转入银行数据渲染
    function IntoBankList() {
        // 银行卡列表json结构数据
        var odata = <?php echo json_encode($aData); ?>;
        var IntoBank = $("#IntoBank");
        var str='';
       // console.log(odata)
        str +='<option value="">请选择转入银行</option>';
        $.each(odata, function (i,v) {
            if(v['bankcode'] !='KSCZ'){
                str +='<option data-code="'+v['bankcode']+'" data_id="'+v['id']+'" value="'+v['bank_name']+'-'+v['bank_user']+'">'+v['bank_name']+v['bank_user']+'</option>' ;
            }

        });
        IntoBank.html(str);
        IntoBank.change(function () {
            //var bankid = $(this).find("option:selected").attr("data_id");
            var bankid = $('select[name="IntoBank"] option').not(function(){ return !this.selected }).attr("data_id");
           // console.log(bankid) ;
            $("#payid").val(bankid);
        })
    }

    // 公司入款查看账号
    function seeBank() {
        $('#seebank').on('click',function () {
            $('#show_bank_list').toggle() ;
        })
    }

    // 重置金额
    $reset.click(function() {
        $('.textbox-close').click() ;
    });

    function chooseTypeAction() {
        $('.pay-type').each(function () {
            var paytype = $(this).data('area') ;
            //console.log(urltype) ;
            if(urltype == paytype){
                $(this).removeClass('hide-cont') ;
            }
        }) ;

    }

    // 公司入款输入验证
    var submitflag = false ; // 防止重复提交
    function depositeBankAction(){
        if(submitflag){
            return false ;
        }
        var payid = $('#payid').val(); // 银行卡 id
        var mon = $('.money-textbox').val() ; // 存款金额
        var v_Name = $('#v_Name').val() ; //  真实姓名
        var InType = $('#InType').val() ; //  转入银行
        var IntoBank = $('#IntoBank').val() ; //  汇款方式
        var save_time =$('#time_textbox').val() ;  // 时间
        var memo = $('#remark').val() ; //  备注

        if(!checkInputFloat(mon)){
            setPublicPop(alerttitle.int);
            $('.money-textbox').focus();
            return false;
        }else if(mon < 100){
            setPublicPop(alerttitle.mon);
            $('.money-textbox').focus();
            return false;
        }else if( v_Name ==''){
            setPublicPop(alerttitle.realname);
            $('#v_Name').focus();
            return false;
        }else if( payid ==''){
            setPublicPop(alerttitle.bank);
            return false;
        }
        else if(InType == ''){
            setPublicPop(alerttitle.banktype);
            return false;
        }else if(save_time == ''){
            setPublicPop(alerttitle.time);
            return false;
        }else if(memo == ''){
            setPublicPop(alerttitle.remark);
            $('#remark').focus();
            return false;
        }
        var datapars ={
            payid: payid , // 银行卡 id
            v_Name: v_Name , // 真实姓名
            InType: InType ,
            IntoBank: IntoBank ,
            v_amount: mon ,
            cn_date: save_time ,
            memo: memo
        }
        submitflag = true ;
        $.ajax({
            url: '/account/deposit_two_bank_company_save.php' ,
            type: 'POST',
            dataType: 'json',
            data: datapars ,
            success: function (res) {
                if(res.status=='200'){
                    submitflag = false ;
                    alertComing(res.describe);
                    window.location.href ='depositrecord.php' ;
                }else{ // 失败
                    submitflag = false ;
                    alertComing(res.describe);
                }
            },
            error: function (msg) {
                submitflag = false ;
                alertComing(config.errormsg);
            }
        });



    }


    setLoginHeaderAction('公司入款','','',usermon,uid) ; // 充值方式标题
    chooseAction(checkedMoney) ;
    deleteMoney(checkedMoney) ;
    // chooseTypeAction() ;
    setFooterAction(uid) ; // 在 addServerUrl 前调用

    addServerUrl() ;
    seeBank() ;
    IntoBankList() ;

    var depositNum = '<?php echo $membermessage['mcou']?>' ; // 是否有会员信息
    var depositMsg = '<?php echo $membermessage['mem_message']?>' ; // 会员信息
    // 弹窗信息
    if(depositNum>0){ // 有弹窗短信
        alert(depositMsg);
       /* layer.open({
            content: depositMsg
            ,btn: '确定'
        });*/
    }

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
            alertComing('复制成功!')
            e.clearSelection();
        });
        clipboard.on('error', function (e) {
            //console.log(e);
            alertComing('请选择“拷贝”进行复制!')
        });
    });


</script>

</body>
</html>