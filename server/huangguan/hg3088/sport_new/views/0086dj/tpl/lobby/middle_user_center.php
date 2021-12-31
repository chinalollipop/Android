<?php
session_start();

include "../../../../app/member/include/config.inc.php";
$uid = $_SESSION['Oid'];
if( !isset($uid) || $uid == "" ) {
    echo "<script>window.location.href='/'</script>";
    exit;
}
$username=$_SESSION['UserName'];
$onlinetime=$_SESSION['OnlineTime'];
$Alias=$_SESSION['Alias'];
$birthday=$_SESSION['birthday'];
$payPassword = $_SESSION['payPassword']; // 提款密码
//  单页面维护功能

$nobandtip = '未绑定';

?>

<link rel="stylesheet" type="text/css" href="<?php echo TPL_NAME;?>style/memberaccount.css?v=<?php echo AUTOVER; ?>" >

<div class="memberWrap">
    <div class="memberTit clearfix">
        <span class="account_icon fl titImg user_nav"></span>
    </div>
    <div class="payWay">
        <div class="static-content">
            <div class="box-border profile-quicklook row">
                <div class="member-name">
                    <strong>
                        <span id="label-username"><?php echo $username;?></span>
                    </strong>
                </div>
                <div class="last-login">
                    <span>上次登录:</span>
                    <span id="label-lastlogintime"><?php echo $onlinetime;?></span>
                </div>
            </div>
            <div class="profile-info">
                <!-- 银行资料 开始-->
                <div class="bank_details">
                    <ul >
                        <li> USDT账号：<span class="show_bank_account"> <?php echo $_SESSION['Usdt_Address']?$_SESSION['Usdt_Address']:$nobandtip ;?> </span></li>
                        <li> 银行卡账号：<span class="show_bank_account"> <?php echo $_SESSION['Bank_Account']?returnBankAccount($_SESSION['Bank_Account']):$nobandtip ;?> </span></li>
                        <li> 银行名称：<span class="show_bank_name"> <?php echo $_SESSION['Bank_Name']?$_SESSION['Bank_Name']:$nobandtip ;?> </span></li>
                        <li> 开户行地址：<span class="show_bank_address"> <?php echo $_SESSION['Bank_Address']?$_SESSION['Bank_Address']:$nobandtip ;?> </span></li>
                    </ul>
                    <div class="band_btn_all">
                        <button class="change_user_bank nextBtn"> 重新添加银行卡 </button>
                        <?php
                            if(!$Alias){
                                echo '<button class="change_user_details nextBtn" onclick="indexCommonObj.ifBindRealName(\''.$Alias.'\')">绑定个人资料</button>';
                            }
                        ?>
                    </div>

                </div>
                <!-- 银行资料 结束-->


                <form id="mainForm" name="mainForm" class="form-horizontal">

                    <div class="form-row">
                        <div class="form-group">
                            <label class="col-lg-2 label-left-sm">姓名</label>
                            <div class="col-lg-4">
                                <input class="form-control" value="<?php echo $Alias?returnRealName($Alias):$nobandtip;?>" type="text" id="realName" name="realName" disabled="" >
                            </div>

                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="col-lg-2 label-left-sm">账户名</label>
                            <div class="col-lg-4">
                                <input class="form-control" value="<?php echo $username;?>" type="text" id="username" name="username" disabled="">
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="col-lg-2 label-left-sm">密码</label>
                            <div class="col-lg-4">
                                <input id="password" class="form-control password" type="text" value="********" disabled>
                            </div>
                            <div class="col-lg-6">
                                <a class="to_change_password grey-u-anchor" href="javascript:;" data-type="loginpwd">更改密码</a>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="col-lg-2 label-left-sm">提款密码</label>
                            <div class="col-lg-4">
                                <input id="withdraw-pw" class="form-control password" type="text" value="<?php echo $payPassword?'******':'未设置'?>" disabled>
                            </div>
                            <div class="col-lg-6">
                                <a  class="to_change_password grey-u-anchor" href="javascript:;" data-type="paypwd">更改提款密码</a>
                            </div>
                        </div>
                    </div>
                  <!--  <div class="form-row">
                        <div class="form-group">
                            <label class="col-lg-2 label-left-sm">密码提示问题</label>
                            <div class="col-lg-4">
                                <input id="empty-tipsPassword" class="form-control hidden" type="text" value="未填写" disabled="">
                                <select class="form-control" id="tipsPassword" name="tipsPassword" disabled="">
                                    <option value="您喜欢的食物">您喜欢的食物</option>
                                    <option value="您喜欢的品牌">您喜欢的品牌</option>
                                    <option value="您喜欢的运动">您喜欢的运动</option>
                                    <option value="您喜欢的颜色">您喜欢的颜色</option>
                                    <option value="您喜欢的球队">您喜欢的球队</option>
                                    <option value="您喜欢的球星">您喜欢的球星</option>
                                    <option value="您的第一辆车的颜色">您的第一辆车的颜色</option>
                                    <option value="您的小学班主任名字">您的小学班主任名字</option>
                                    <option value="您的初中班主任名字">您的初中班主任名字</option>
                                    <option value="您的高中班主任名字">您的高中班主任名字</option>
                                    <option value="您最喜欢的儿时玩伴名字">您最喜欢的儿时玩伴名字</option>
                                    <option value="您的终极梦想假期的国家">您的终极梦想假期的国家</option>
                                </select>
                            </div>

                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="col-lg-2 label-left-sm">密码提示答案</label>
                            <div class="col-lg-4">
                                <input class="form-control" type="text" id="tipsAnswer" name="tipsAnswer" disabled="disabled">
                            </div>


                        </div>
                    </div>-->
                    <div class="form-row">
                        <div class="form-group">
                            <label class="col-lg-2 label-left-sm">微信号</label>
                            <div class="col-lg-4">
                                <input  class="form-control" value="<?php echo $_SESSION['E_Mail'];?>" type="text" name="gender" disabled="">
                            </div>

                        </div>
                    </div>
                    <div class="form-row">
                        <div id="bd-form-group" class="form-group">
                            <label class="col-lg-2 label-left-sm">出生日期</label>
                            <div class="col-lg-4">
                                <input id="birth" class="form-control password" type="text" value="<?php echo ($birthday=='0000-00-00 00:00:00')?'':$birthday;?>" disabled>
                            </div>

                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

</div>


<script type="text/javascript">


    $(function () {
        var uid = '<?php echo $uid;?>' ;

        // 更改密码
        function changePassWOrd() {
            $('.to_change_password').on('click',function () {
                var type = $(this).data('type') ;
                var url = '<?php echo TPL_NAME;?>tpl/lobby/middle_change_password.php?type='+type ;
                var $title = '更改登录密码' ;
                if(type=='paypwd'){
                    $title = '更改支付密码' ;
                }
                layer.open({
                    type: 2,
                    title: $title,
                    shadeClose: true,
                    area: ['500px', '310px'],
                    content: url
                });

            });
        }


        changePassWOrd();

    })
</script>