<?php
include_once ("../app/member/include/config.inc.php");
include_once ("../app/member/include/address.mem.php");
include_once ("../app/member/include/activity.class.php");

$uid=$_REQUEST['uid'];
$userid = $_SESSION['userid'];
$username = $_SESSION['UserName'];
$langx=$_SESSION['langx'];

// 活动类
$activity= new Activity();

//获取上周起始时间戳和结束时间戳
$begin_time=mktime(0,0,0,date('m'),date('d')-date('w')+1-7,date('Y'));
$end_time=mktime(23,59,59,date('m'),date('d')-date('w')+7-7,date('Y'));
$lastWeekTime['begin_time'] = date('Y-m-d H:i:s',$begin_time);
$lastWeekTime['end_time'] = date('Y-m-d H:i:s',$end_time);  //2018-07-23 00:00:00----2018-07-29 23:59:59

// 会员上周下注投注总额(体育)
$lastNumBets = $activity->lastDayBet($userid,$username,$lastWeekTime,'','','vip');
// 上周总投注
$lastWeekBet = $lastNumBets;
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>VIP晋升活动</title>
    <style>
        /*basic*/
        html, body{overflow-y: auto;height: 100%;padding-bottom: 10px;}
        .all_content{background:#878f16 url(../images/vip_bg.jpg) center top no-repeat;padding: 260px 0;}
        html, body, ul, ol, li, dl, dt, dd, p, h1, h2, h3, h4, h5, h6, a, img, th, td, form, fieldset, iframe, object, pre, code, legend, blockquote {
            border:0 none;
            margin:0;
            outline:0 none;
            padding:0;
            list-style-type:none;
        }
        .vip_bg{color:#7d7d7d;font-size:12px;font-family:"Microsoft YaHei"; }
        /*elements*/
        a{color:#7d7d7d;text-decoration: none;}
        a:hover{text-decoration:underline;color:#0f89e8;}
        h2{font:14px "Microsoft YaHei";}
        img{border:0px;}
        input{font-size:12px;}
        table{border-collapse:collapse;border-spacing: 0;}
        .clear{clear:both; height:1px;overflow:hidden;margin-top:-1px;}
        .clearfix:before,.clearfix:after{content:"";display:table;}
        .clearfix:after{clear:both;}
        .clearfix{zoom:1;}
        .fr{float:right}
        .fl{float:left;}


        .vip{width:1000px;margin:0 auto 0;font-size:17px;color:#000;line-height: 1.6;}
        .vip p strong{font-size:26px;}
        .vip .yellow{color:#fcff00;font-weight:600;}
        /**列表*/
        table{
            width: 1000px;
            margin-left: 5px;
            margin-top: 10px;
        }
        table tr th, table tr td{
            border: 1px solid #333;
            height: 25px;
            text-align:center;
        }

        .vip .fenh{width:1000px;margin:0 auto 20px;}

    </style>
    <script type="text/javascript" src="../../../js/jquery.js"></script>
</head>
<body class="vip_bg">
<div class="all_content">


<div class="vip">
    <p>上周打码量：<?php  if(!empty($lastWeekBet)){ echo sprintf("%.2f",$lastWeekBet);}else{ echo '0'; }?></p>
    <p>本周打码量：</p>

    <br>
    <table border="1" cellspacing="0" cellpadding="0" class="fenh" style="color:black">
        <tbody>
        <tr>
            <td>会员等级</td>
            <td>周有效投注</td>
            <td>晋升彩金</td>
        </tr>
        <tr>
            <td>青铜</td>
            <td>≥30,000</td>
            <td>88元</td>
        </tr>
        <tr>
            <td>白银</td>
            <td>≥100,000</td>
            <td>188元</td>
        </tr>
        <tr>
            <td>黄金</td>
            <td>≥30,0000</td>
            <td>288元</td>
        </tr>
        <tr>
            <td>铂金</td>
            <td>≥500,000</td>
            <td>588元</td>
        </tr>
        <tr>
            <td>钻石</td>
            <td>≥1000,000</td>
            <td>888元</td>
        </tr>
        <tr>
            <td>星耀</td>
            <td>≥3000,000</td>
            <td>1888元</td>
        </tr>
        <tr>
            <td>王者</td>
            <td>≥5000,000</td>
            <td>3888元</td>
        </tr>

        <tr>
            <td>皇冠</td>
            <td>≥10000,000</td>
            <td>15888元 </td>
        </tr>
        </tbody>
    </table>
    <!--没有达到申请条件的不显示申请按钮-->
    <p align="center"><a class="get_quanq"  href="javascript:;" onclick="show()"><font color="red">点击申请</font></a></p>
    <p class="moon_rule">
        1、周计算区间：北京时间每周一中午12：00：00至下周一12：00：00期间的体育打码量（输赢一半计算一半打码量，和局不计算打码量）。<br>
        2、此优惠需要会员在此活动页面点击【点击申请】进行申请，活动申请时间为每周一中午12：00至次日12：00之前，晋升礼金将于申请后24小时内自动添加到各会员账号中，未点击申请的视为自动放弃领取彩金。<br>
        3、晋升礼金1倍流水即可提款。<br>
        4、领取礼金之后将重新计算新一轮的周打码量！<br><br>
        <b>活动条款：</b><br>
        1、每位会员每个等级每周晋升礼金仅限领取一次；本晋升礼金可跨级领取，例如青铜会员，若当周直接晋级到为黄金，那么可以直接领取黄金等级晋升奖金；<br>
        2、任何个人或团体以不诚实方式套取红利，皇冠7557保留取消其活动资格，收回优惠红利和活动产生盈利以及关闭会员账号的权利。<br>
        3、皇冠7557保留在任何时候都可以更改，停止该活动的权利，并不做提前通知。<br>
        4、本活动最终解释权归属皇冠7557所有。
    </p>
</div>

<input id="uid" name="uid" value="<?php echo $uid?>" type="hidden" />
<input id="userid" name="user_id" value="<?php echo $userid?>" type="hidden" />
<input id="username" name="username" value="<?php echo $username?>" type="hidden" />
<input id="lastweekbet" name="lastweekbet" value="<?php echo $lastWeekBet?>" type="hidden" />
</div>
</body>
<script type="text/javascript">
    var postData = {
        uid:$('#uid').val(),
        user_id:$('#userid').val(),
        username:$('#username').val(),
        lastWeekBet:$('#lastweekbet').val(),
    }
    function show(){
    	var userAgents='<?php echo $_SESSION['Agents'];?>';
        if(userAgents=='demoguest'){
    		alert("请注册真实用户！");
        }else{
            $.post('../../../app/member/activity/promotion.php', postData, function(json) {
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