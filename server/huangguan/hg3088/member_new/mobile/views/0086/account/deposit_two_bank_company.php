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
    <link href="../../../style/font-awesome.min.css" rel="stylesheet" type="text/css" media="screen"/>
    <link href="../style/iphone.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css" media="screen"/>
    <link rel="stylesheet" href="../../../style/icalendar.css?v=<?php echo AUTOVER; ?>">

    <title class="web-title"></title>
<style type="text/css">
    .content-center{color:#000;font-size:1.2rem}
    .payWayTit{text-align:left;padding:.5rem 5%}
    .payWay{display:none}
    .bank_list{overflow:hidden;border:1px solid #ddd;border-left:0;border-right:0}
    .bank_li{background:#fff;border-bottom:1px solid #ddd;display:flex;display:-webkit-flex;width:47.8%;float:left;color:#000;height:2.3rem;line-height:1rem;font-size:1rem;/*justify-content:center;*/padding:2rem 1%;border-right:1px solid #ddd}
    .bank_li:nth-child(2n+1){border-left:0}
    .bank_li:nth-child(2n){border-right:0}
    .bank_li span{font-size:1rem;text-align: left;}
    .bank_li .icon{display:inline-block;width:2.3rem;height:100%;background-position:center; background-repeat: no-repeat;background-size: 90%;flex: none;-webkit-flex: none;}
    .bank_deposit_bottom{margin:0 auto;width:100%}
    .banks_details{border:1px solid #ddd;color:#656565;font-size:1.1rem}
    .banks_details .bank_list_li {display: none;height: auto;padding: 0;}
    .banks_details>div,.banks_details .bank_list_li>div{display:flex;display:-webkit-flex;padding:5px 2%;border-bottom:1px solid #ddd;height:35px;align-items:center;text-align:left}
    .banks_details>div:last-child,.banks_details .bank_list_li>div:last-child{border-bottom:0}
    .banks_details>div span:first-child{width:100px}
    .banks_details>div span:nth-child(2){width:300px}
    .banks_details>div a{width:53px;height:33px;line-height:33px;background:#2A8FBD;border-radius:5px;text-align:center;padding:0 7px}
    .deposit_bank_next{display:block;width:94%;text-align:center;margin:15px auto}
    .warn{text-align:left;color:#656565}
    .warn h2{font-size:1.2rem}
    .warn p{font-size:1rem}
    .tip{text-align:left;padding:0 1%;font-size:1rem}
</style>
</head>
<body>
<div id="container">
    <!-- 头部 -->
    <div class="header ">

    </div>

    <!-- 中间内容 -->
    <div class="content-center deposit-two">
        <div class="payWay bank_deposit_1" style="display:block;padding-bottom: 20px;">
            <div class="payWayTit">选择银行</div>
            <div class="bank_list">

            </div>

        </div>

        <div class="payWay bank_deposit_2">
            <div class="payWayTit">
                汇款详细账户资料
            </div>
            <div class="bank_deposit_bottom">
                <input type="hidden" name="IntoBank" id="IntoBank">
                <div class="banks_details" id="show_bank_list">
                   <!-- <div> <span> 银行 </span> <span class="bank_name"></span></div>
                    <div> <span> 开户名 </span> <span class="bank_username"> </span> <a href="javascript:;" data-clipboard-target=".bank_username">复制</a></div>
                    <div> <span> 银行账号 </span> <span class="bank_account"></span> <a href="javascript:;" data-clipboard-target=".bank_account">复制</a></div>
                    <div> <span> 银行分行 </span> <span class="bank_address"></span> </div>-->
                </div>
                <div class="warn" style="padding:15px 5px">
                    <h2>温馨提示： </h2>
                    <p>一、请在金额转出之后务必填写网页下方的汇款信息表格，以便我们财务人员能及时为您确认添加金额到您的会员账户。 <br>
                        二、本公司最低存款金额为100元，每次存款赠送最高1%红利。
                    </p>
                </div>
                <a href="javascript:;" class="deposit_bank_next zx_submit"> 填写汇款信息表格 </a>
                <div class="tip">此存款信息只是您汇款详情的提交，并非代表存款，您需要自己通过ATM或网银转帐到本公司提供的账户后，填写提交此信息，待工作人员审核充值！</div>
            </div>
        </div>

        <!-- 公司入款开始 -->
        <div class="payWay bank_deposit_3" data-area="bank_pay">
            <div class="payWayTit">
                汇款信息提交
            </div>
                <div class="form-item">
                        <span class="label clearfix">
                            <span class="text">汇款金额</span>
                            <span class="line"></span>
                        </span>
                    <span class="textbox">
                            <input type="text" name="v_amount" class="deposit-input money-textbox" placeholder="请输入汇款金额" />
                            <a class="textbox-close" href="javascript:;">╳</a>
                        </span>
                </div>

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
                        <span class="text">汇款方式</span>
                        <span class="line"></span>
                    </span>
                    <span class="dropdown">
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
                <div class="btn-wrap">
                    <a href="javascript:;" class="zx_submit" onclick="depositeBankAction()">确认存款</a>
                </div>
                <input type="hidden" name="payid" id="payid" value=""/>

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
    // setPublicContact() ;
    addServerUrl() ;

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
    function copyBnakAction() {
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
    }
    /*
        *  获取银行信息
        *  type : list
        *  type : detail
        * */
    var $bank_list = $('.bank_list');
    var $bank_deposit_1 = $('.bank_deposit_1');
    var $bank_deposit_2 = $('.bank_deposit_2');
    var $bank_deposit_3 = $('.bank_deposit_3');
    var $deposit_bank_next = $('.deposit_bank_next');
    var $IntoBank = $('#IntoBank');
    function getBnakList(type,id) {
        var ajaxUrl ='/api/bankListApi.php';
        if(!id){
            id='';
        }
        var pars = {
            type:type,
            bankid:id
        };
        $.ajax({
            type : 'POST',
            url : ajaxUrl ,
            data : pars,
            dataType : 'json',
            success:function(res) {
                if(res){
                    var str ='';
                    var de_str ='';
                    for(var i=0;i<res.data.length;i++){
                        var ddd = (res.data[i].bank_name)+'-'+(res.data[i].bank_user);
                        str +='<a href="javascript:;" class="bank_li" data-bank="'+ddd+'" data-id="'+ res.data[i].id+'"> <span class="icon" style="background-image: url(/images/bank/icon_'+res.data[i].bankcode+'.png)"></span> <span> '+ res.data[i].bank_name+'<br>'+res.data[i].bank_user+' </span></a>';
                        de_str +='<div class="bank_list_li bank_list_li_'+res.data[i].id+'">' +
                            '                    <div> <span> 银行 </span> <span class="bank_name">'+ res.data[i].bank_name +'</span></div>' +
                            '                    <div> <span> 开户名 </span> <span class="bank_username_'+res.data[i].id+'">'+ res.data[i].bank_user +' </span> <a href="javascript:;" data-clipboard-target=".bank_username_'+res.data[i].id+'"><span class="icon"></span> 复制</a></div>' +
                            '                    <div> <span> 银行账号 </span> <span class="bank_account_'+res.data[i].id+'">'+ res.data[i].bank_account +'</span> <a href="javascript:;" data-clipboard-target=".bank_account_'+res.data[i].id+'"><span class="icon"></span> 复制</a></div>' +
                            '                    <div> <span> 银行分行 </span> <span class="bank_address">'+ res.data[i].bank_addres +'</span> </div>' +
                            '                </div>';

                    }
                    $bank_list.html(str);
                    $('#show_bank_list').append(de_str);
                    copyBnakAction();
                }

            },
            error:function(){

            }
        });
    }
    // 选择银行
    function chooseBnakType(){
        $bank_list.on('click','a',function () { // 选择银行第一步
            var bankId = $(this).attr('data-id');
            var bank = $(this).attr('data-bank');
            $bank_deposit_1.hide();
            $bank_deposit_2.show();
            $('.bank_list_li_'+bankId).show();
            $IntoBank.val(bank).attr({'data-id':bankId});
            $("#payid").val(bankId);
            //getBnakList('detail',bankId)
        })

        $deposit_bank_next.on('click',function () { // 填写汇款信息
            $('.list-tab thead td h1').text('汇款信息提交');
            $bank_deposit_2.hide();
            $bank_deposit_3.show();

        })
    }

    getBnakList('detail');
    chooseBnakType();

</script>

</body>
</html>