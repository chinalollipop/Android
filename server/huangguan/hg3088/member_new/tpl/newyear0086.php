<?php

include_once ("../app/member/include/config.inc.php");
include_once ("../app/member/include/address.mem.php");
include_once ("../app/member/include/activity.class.php");

$uid=$_REQUEST['uid'];
$userid = $_SESSION['userid'];
$username = $_SESSION['UserName'];
$langx=$_SESSION['langx'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <style>
        html, body{overflow-y: auto;height: 100%;padding-bottom: 10px;margin: 0;}
        body{
            margin: 0;
            padding: 0;
        }
        img{
            width: 1100px;
            border: 0;
            margin: 0;
        }
        input,textarea,select,a:focus {
            outline: none;
        }
        .bg_text{
            width:1100px;
            background: url(../images/yearhb_0086_medium.jpg) no-repeat;
            background-size: 100% 100%;
            padding-bottom: 7.69rem;
        }
        .qd_box{
            padding-top: 55px;
            width: 90%;
            margin: auto;
        }
        .qd_box p{
            color: #ffd38f;
            margin-bottom: 10px;
            font-size: 25px;
        }
        .qd_ipt{
            width: 100%;
            background: #ffd38f;
        }
        .qd_ipt input{
            height: 2rem;
            border: 2px solid #ac2232;
            background: none;
            margin: 3% 0;
            border-radius: 5px;
            margin-left: 2%;
            width: 50%;
            padding-left: 5px;
            font-size: 20px;
        }
        .lq_ipt input{
            border: none;
        }
        p{
            margin: 0;
            padding: 0;
        }
        .qd_btn{
            cursor: pointer;
            background: #ac2232;
            color: #ffd38f;
            font-size: 22px;
            padding: 2% 6%;
            border-radius: 10px;
            margin-left: 15%;
        }
        .qd{
            width: 40%;
            float: left;
        }
        .lq{
            width: 40%;
            float: right;
        }
        .lq_btn{
            cursor: pointer;
            background: #ac2232;
            color: #ffd38f;
            font-size: 22px;
            padding: 2% 6%;
            border-radius: 10px;
            margin-left: 10%;
        }
    </style>
    <script type="text/javascript" src="../js/jquery.js"></script>
    <script type="text/javascript" src="../js/register/validate.js"></script>
</head>
<body>
<div class="all_content">
    <div>
        <img src="../images/yearhb_0086_top.jpg" alt="" style="display: block">
    </div>
    <div class="bg_text">
        <div class="qd_box">
            <div class="qd">
                <p>填写手机号码：</p>
                <div class="qd_ipt">
                    <input id="mobile" name="mobile" value="" type="text" minlength="11" maxlength="11">
                    <span class="qd_btn" id="mobilesign">签 到</span>
                </div>
            </div>
            <div class="lq">
                <p>剩余次数：</p>
                <div class="qd_ipt lq_ipt">
                    <input id="cishu" name="cishu" value="" type="text" readonly="readonly">
                    <span class="lq_btn" id="receivered">领取红包</span>
                </div>
            </div>
        </div>
    </div>
    <div>
        <img src="../images/yearhb_0086_foot.jpg" alt="" style="display: block">
    </div>

    <input id="uid" name="uid" value="<?php echo $uid?>" type="hidden" />
    <input id="userid" name="user_id" value="<?php echo $userid?>" type="hidden" />
    <input id="username" name="username" value="<?php echo $username?>" type="hidden" />
</div>
</body>

<script type="text/javascript">
    $(function() {
        var postData = {
            uid:$('#uid').val(),
            user_id:$('#userid').val(),
            username:$('#username').val(),
        }

        postData.action =  "get_remain_num";
        loadnewyear_0086_num(postData); // 第一次进来请求，不弹框提示更新次数

        // 签到
        $("#mobilesign").click(function() {
            var mobilenum = $('#mobile').val() ;
            postData.mobile = mobilenum ;
            postData.action =  "mobilesign" ;
            if(mobilenum =='' || !isMobel(mobilenum)){
                alert('请输入正确的手机号码');
                return false ;
            }
            loadnewyear_0086(postData);
        });

        // 领取红包
        $("#receivered").click(function() {
            postData.action =  "receive_red_envelope";
            loadnewyear_0086(postData);
            // 领取成功后，更新剩余可领取次数
            //postData.action =  "get_remain_num",
            //loadnewyear_0086_num(postData);
            //console.log(postData);
        });

    });
    // 默认新春活动0086接口请求
    function loadnewyear_0086(postData){
        var userAgents='<?php echo $_SESSION['Agents'];?>';
        if(userAgents=='demoguest'){
            alert("请注册真实用户！");
        }else{
            $.post('../../../app/member/activity/newyearapi.php', postData, function(json) {
                //console.log(json);
                 $("#cishu").val(json.last_times);
                if (json.status = 1) {
                    alert(json.info);
                    // location.reload(true);
                } else {
                    alert(json.info);
                }
            }, 'json')
        }
    }

    function loadnewyear_0086_num(postData){
        var userAgents='<?php echo $_SESSION['Agents'];?>';
        if(userAgents=='demoguest'){
            alert("请注册真实用户！");
        }else{
            $.post('../../../app/member/activity/newyearapi.php', postData, function(json) {
                $("#cishu").val(json.last_times);
                if (json.status = 1) {
                    // alert(json.info);
                    // location.reload(true);
                } else {
                    alert(json.info);
                }
            }, 'json')
        }
    }

</script>
</html>