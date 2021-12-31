<?php
include_once ("../app/member/include/config.inc.php");
include_once ("../app/member/include/address.mem.php");
include_once ("../app/member/include/activity.class.php");


$uid=$_REQUEST['uid'];
$userid = $_SESSION['userid'];
$username = $_SESSION['UserName'];
$langx=$_SESSION['langx'];

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
    exit;
}

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>中秋国庆</title>
    <style>
        html,body{height: 100%;overflow-y: auto;}
        header,body,section,div,ul,li,dl,dt,dd,h1,h2,h3,span,b,i,p,em,a,input,
        button {
            margin: 0;
            padding: 0
        }
        body {
            font-family: arial, PingFangSC-Regular, "Microsoft Yahei", Helvetica, sans-serif;
            position: relative;
        }
        .bg_div{width:1100px;background: url("../../images/national/0086/1647.jpg") no-repeat; background-size: 100%;padding: 560px 0 80px;}
        ul,
        li {
            list-style: none
        }

        h1,
        h2,
        h3 {
            font-weight: 500;
            font-size: 15px
        }

        img,
        button {
            border: 0
        }
        input{
            outline: none;
        }
        .fl{float: left;}
        .fr{float: right;}
        a {
            -webkit-tap-highlight-color: rgba(255, 255, 255, 0);
            -webkit-user-select: none;
            -moz-user-focus: none;
            -moz-user-select: none;
        }
        a{text-decoration: none;}

        .title{
            text-align: center;
        }

        table{
            font-size: 14px;
            margin: auto;
            width: 50%;
            color: #fff;
            text-align: center;
        }
        table th{
            padding: 10px 0;
            width: 20%;
            background: #ffff00;
            color: #9c2222;
        }
        td{
            padding: 5px 0;
            border: 1px solid #b28d1f;
            background: #9c2222;
        }
        .btn_lq {
            position: absolute;
            right: -25px;
            top: 200px;
        }
        .main{
            width: 50%;
            margin: auto;
            color: #fff;
        }
        .main_text p{
            padding-left: 20px;
            line-height: 35px;
            color: #9c2222;
            font-weight: 700;
            font-size: 16px;
        }
        #one{
            padding-top: 22% ;
        }
        #two{
            padding-top: 19%;
        }
        #two p {
             font-size: 14px;
             line-height: 20px;
         }

        #yt{
            padding-top: 10%;
        }
        #lq{
            padding-top: 3%;
        }
       /* @media (max-width: 1200px) {
            #one {
                padding-top: 22% !important;
            }
            #two{
                padding-top: 22% !important;
            }
            .main_text p{
                font-size: 14px !important;
                line-height: 23px;
            }
            table{
                font-size: 12px !important;
            }
            .title{
                margin-top: 50% !important;
            }
            #lq{
                padding-top: 1% !important;
            }
        }
        @media (min-width: 1300px) and (max-width: 1700px){
            #one {
                padding-top: 19% !important;
            }
            #two{
                padding-top: 40% !important;
            }
            .main_text p{
                font-size: 16px !important;
            }
            #lq{
                padding-top: 5% !important;
            }
        }*/

    </style>
</head>
<body>
<div class="bg_div">
    <div class="title">
        <img src="../../images/national/0086/012.png" alt="" width="40%" >
        <div id="yt" style="width: 50%;margin: auto;text-align: left;">
            <img src="../../images/national/0086/0.15.png" alt="" width="25%">
            <p style="color: #9c2222;font-weight: 700;padding-bottom: 10px;">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;中秋拍拍手， 福运跟您走，为了感谢新老会员长期的支持， 凡9月22日起凡当日总存款满1000元，次日即可参与HG0086.com再次为您精心准备的一份贺礼！
            </p>
        </div>
        <table align='center' style="border-collapse:collapse;">
            <thead>
            <tr>
                <th style="border-radius: 10px  0 0 0">活动日期</th><th>总存款金额</th><th>赠送彩金</th><th style="border-radius: 0 10px 0 0">提款要求</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td rowspan="11" style="border-radius:0 0 0 10px;border-bottom:none;border-left:none; ">美东时间<br>9月22日至10月<br>7日截止</span></td>
                <td>1000+</td>
                <td>18</td>
                <td rowspan="11" style="border-radius:0 0 10px 0;border-bottom:none;border-right:none;">一倍流水</td>
            </tr>
            <tr>
                <td>3000+</td>
                <td>38</td>
            </tr>
            <tr>
                <td>5000+</td>
                <td>58</td>
            </tr>
            <tr>
                <td>1 万+</td>
                <td>88</td>
            </tr>
            <tr>
                <td>3 万+</td>
                <td>188</td>
            </tr>
            <tr>
                <td>5 万+</td>
                <td>388</td>
            </tr>
            <tr>
                <td>10 万+</td>
                <td>888</td>
            </tr>
            <tr>
                <td>20 万+</td>
                <td>1888</td>
            </tr>
            <tr>
                <td>50 万+</td>
                <td>3888</td>
            </tr>
            <tr>
                <td>100 万+</td>
                <td>8888</td>
            </tr>
            <tr>
                <td>200 万+</td>
                <td>18888</td>
            </tr>
            </tbody>
        </table>
        <div style="width: 52%;margin: auto;text-align: center;" id="lq">
            <a href="javascript:;" onclick="show()">
                <img src="../../images/national/0086/btn.png" alt="" width="30%">
            </a>
            <p style="font-size: 14px;color: #9c2222;font-weight: 700;">
                例如：会员当日总存款1万元，次日即可参与申请88，以此类推，彩金最高上限为：18888
            </p>
        </div>
    </div>
    <div class="main">
        <div class="main_text" id="one">
            <img src="../../images/national/0086/013.png" alt="" width="25%">
            <p>1.通过此优惠所获得的彩金只需达到一倍流水即可申请提款。</p>
            <p>2.此优惠每位会员每天仅限申请一次，天数按照美东时间进行计算，活动结束时间以公告为准。</p>
            <p>3.符合条件的会员，请在美东时间次日24小时内提交申请，逾期视为放弃。</p>
            <p>4.部分套利、违反公司条例的会员不在活动名单之内。</p>
            <p>4.参与该优惠，即表示您同意《优惠规则与条款》。</p>
        </div>
        <div class="main_text" id="two">
            <img src="../../images/national/0086/011.png" alt="" width="30%">
            <p>1.所有优惠以人民币(CNY)为结算金额，以美东时间(EDT)为计时区间。</p>
            <p>2.每位玩家、每户、每一住址、每一电子邮箱地址、每一电话号码、相同支付方式(相同借记卡/信用卡/银行账户)及IP地址每天仅限申请一次优惠；若会员有重复申请账号行为，公司保留取消或收回会员优惠彩金的权利。</p>
            <p>3.HG0086的所有优惠特为玩家而设，如发现任何团体或个人，以不诚实方式套取红利或任何威胁、滥用公司优惠等行为，公司保留冻结、取消该团体或个人账户及账户结余的权利。</p>
            <p>4.若会员对活动有争议时，为确保双方利益，杜绝身份盗用行为，HG0086有权要求会员向我们提供充足有效的文件，用以确认是否享有该优惠的资质。</p>
            <p>5.当参与优惠会员未能完全遵守、或违反、或滥用任何有关公司优惠的推广的条款，又或我们有任何证据有任何团队或个人投下一连串关联赌注，籍以造成无论赛果怎样都可以确保可以从该存款红利或其他推广活动提供的优惠获利，HG0086保留权利向此团队或个人停止、取消优惠或索回已支付的全部优惠红利。此外，公司亦保留权利向这些客户扣取相当于优惠红利价值的行政费用，以补偿我们的行政成本</p>
            <p>6.HG0086保留对活动的最终解释权；以及在无通知的情况下修改、终止活动的权利；适用于所有优惠。</p>
        </div>
    </div>
    <input id="uid" name="uid" value="<?php echo $uid?>" type="hidden" />
    <input id="userid" name="user_id" value="<?php echo $userid?>" type="hidden" />
    <input id="username" name="username" value="<?php echo $username?>" type="hidden" />
</div>
</body>
<script type="text/javascript" src="../../../js/jquery.js"></script>
<script type="text/javascript">
    var postData = {
        uid:$('#uid').val(),
        user_id:$('#userid').val(),
        username:$('#username').val(),
    }
    function show(){
        var userAgents='<?php echo $_SESSION['Agents'];?>';
        if(userAgents=='demoguest'){
            alert("请注册真实用户！");
        }else{
            $.post('../../../app/member/activity/national.php', postData, function(json) {
                if (json.status = 1) {
                    alert(json.info);
                } else {
                    alert(json.info);
                }
            }, 'json')
        }
    }
</script>
</html>