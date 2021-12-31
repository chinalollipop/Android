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

$sUname = $_SESSION['UserName'];


?>
<style>
    .payWay{display:none}
    .bank_list{overflow:hidden;border:1px solid #ddd}
    .bank_li{border-bottom:1px solid #ddd;width:30.2%;float:left;color:#000;height:80px;line-height:25px;font-size:16px;padding:30px 1.5%;border-right:1px solid #dddddd}
    .bank_li .top_li{height:100%;display:flex;display:-webkit-flex;align-items: center;}
    .bank_li:nth-child(3n+3){border-right: 0;}
    .bank_li .icon{display:inline-block;width:60px;height:100%;background-position:center; background-repeat: no-repeat;background-size: 90%;}
    .bank_li span{white-space: normal;}
    .bank_li span:nth-child(2){width: calc(100% - 60px)}
    .bank_deposit_top{padding:20px 50px}
    .bank_deposit_top .top{display:flex;display:-webkit-flex;justify-content:space-between}
    .bank_deposit_top .line{height:15px;position:relative;border-bottom:2px solid #ddd;width:95%;margin:0 auto}
    .bank_deposit_top .line:before,.bank_deposit_top .line:after{position:absolute;content:'';width:30px;height:30px;background:url(/images/bank/gou_d.png) center no-repeat;background-size:100%}
    .bank_deposit_top .line:after{right:0;background-image:url(/images/bank/gou_c.png)}
    .bank_deposit_bottom{margin:0 auto;width:800px;padding:20px}
    .banks_details{border:1px solid #ddd}
    .banks_details .bank_list_li {display: none;height: auto;padding: 0;}
    .banks_details>div,.banks_details .bank_list_li>div{display:flex;display:-webkit-flex;padding:10px;border-bottom:1px solid #ddd;height:35px;align-items:center}
    .banks_details>div:first-child{background:#cdcdcd}
    .banks_details>div:last-child,.banks_details .bank_list_li>div:last-child{border-bottom:0}
    .banks_details>div span:first-child{width:100px}
    .banks_details>div span:nth-child(2){width:300px}
    .banks_details>div a{width:53px;height:33px;line-height:33px;background:rgba(0,0,0,.6);color:#fff;border-radius:5px;text-align:right;padding-right:7px}
    .banks_details>div a span{float:left;margin-top:4px;display:inline-block;width:26px !important;height:26px;background:url(/images/bank/copy_icon.png) center no-repeat;background-size:100%}
    .tip{color:#ffa100}
    .deposit_bank_next{display:block;width:100%;height:40px;line-height:40px;text-align:center;font-size:16px;color:#fff;background:#404040;border-radius:5px;margin-bottom:15px}

</style>
<link rel="stylesheet" type="text/css" href="<?php echo TPL_NAME;?>style/memberaccount.css?v=<?php echo AUTOVER; ?>" >

<div class="memberWrap">
    <div class="memberTit clearfix">
        <span class="account_icon fl titImg deposit_nav"></span>
        <a class="fr to_deposit" href="javascript:;"> <img class="backImg" src="/images/back.png" alt=""></a>
    </div>
    <div class="payWay bank_deposit_1" style="display:block;padding-bottom: 20px;">
        <div class="payWayTit">选择银行</div>
        <div class="bank_list">

        </div>

    </div>
    <div class="payWay bank_deposit_2">
        <div class="bank_deposit_top">
            <div class="top"> <span>1.转账信息</span> <span>2.操作转账</span></div>
            <div class="line"></div>
        </div>
        <div class="bank_deposit_bottom">
            <input type="hidden" name="IntoBank" id="IntoBank">
            <div class="banks_details" id="show_bank_list">
                <div> 汇款详细账户资料</div>
               <!-- <div> <span> 银行 </span> <span class="bank_name"></span></div>
                <div> <span> 开户名 </span> <span class="bank_username"> </span> <a href="javascript:;" data-clipboard-target=".bank_username"><span class="icon"></span> 复制</a></div>
                <div> <span> 银行账号 </span> <span class="bank_account"></span> <a href="javascript:;" data-clipboard-target=".bank_account"><span class="icon"></span> 复制</a></div>
                <div> <span> 银行分行 </span> <span class="bank_address"></span> </div>-->
            </div>
            <div class="warn" style="padding:15px 5px">
                <h2>温馨提示： </h2>
                <p>一、请在金额转出之后务必填写网页下方的汇款信息表格，以便我们财务人员能及时为您确认添加金额到您的会员账户。 <br>
                    二、本公司最低存款金额为100元，每次存款赠送最高1%红利。
                </p>
            </div>
            <a href="javascript:;" class="deposit_bank_next"> 填写汇款信息表格 </a>
            <div class="tip">此存款信息只是您汇款详情的提交，并非代表存款，您需要自己通过ATM或网银转帐到本公司提供的账户后，填写提交此信息，待工作人员审核充值！</div>
        </div>
    </div>
    <div class="payWay bank_deposit_3">
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
                var bankid = $('#IntoBank').attr("data-id");
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
                    layer.msg('复制成功!', {time: alertTime});
                    e.clearSelection();
                });
                clipboard.on('error', function (e) {
                    //console.log(e);
                    layer.msg('请选择“拷贝”进行复制!', {time: alertTime});
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
            var ajaxUrl ='/app/member/api/bankListApi.php';
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
                            str +='<a href="javascript:;" class="bank_li" data-bank="'+ddd+'" data-id="'+ res.data[i].id+'"><div class="top_li"> <span class="icon" style="background-image: url(/images/bank/icon_'+res.data[i].bankcode+'.png)"></span> <span> '+ res.data[i].bank_name+'<br><span class="red_color">'+res.data[i].bank_context+'</span>'+(res.data[i].bank_context?"<br>":"")+res.data[i].bank_user+' </span></div><p class="red_color tip_note">'+res.data[i].notice+'</p></a>';
                            de_str +='<div class="bank_list_li bank_list_li_'+res.data[i].id+'">' +
                                '                    <div> <span> 银行 </span> <span class="bank_name">'+ res.data[i].bank_name +' <span class="red_color">'+res.data[i].bank_context+'</span></span></div>' +
                                '                    <div> <span> 开户名 </span> <span class="bank_username_'+res.data[i].id+'">'+ res.data[i].bank_user +' </span> <a href="javascript:;" data-clipboard-target=".bank_username_'+res.data[i].id+'"><span class="icon"></span> 复制</a></div>' +
                                '                    <div> <span> 银行账号 </span> <span class="bank_account_'+res.data[i].id+'">'+ res.data[i].bank_account +'</span> <a href="javascript:;" data-clipboard-target=".bank_account_'+res.data[i].id+'"><span class="icon"></span> 复制</a></div>' +
                                '                    <div> <span> 银行分行 </span> <span class="bank_address red_color">'+ res.data[i].bank_addres +'</span> </div>' +
                                '                </div>';

                        }
                        $bank_list.html(str);
                        $('#show_bank_list').append(de_str);
                        copyBnakAction();
                    }

                },
                error:function(){
                    layer.msg('稍后请重试',{time:alertTime});
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

