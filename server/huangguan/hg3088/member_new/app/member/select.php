<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "./include/address.mem.php";
require ("./include/config.inc.php");

$uid=(isset($_REQUEST['uid']) && $_REQUEST['uid'])? $_REQUEST['uid'] :$_SESSION['Oid'];
$langx=$_SESSION['langx']?$_SESSION['langx']:'zh-cn';
$live=$_REQUEST['live'];

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
	echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
	exit;
}else{
    require ("./include/traditional.$langx.inc.php");

	$Status=$_SESSION['Status'];
	$memname=$_SESSION['UserName'];
	$password=$_SESSION['password'];
	$logindate=date("Y-m-d");
	$datetime=date('Y-m-d h:i:s');

}

$show=$_REQUEST['show'];
if ($show==''){
	$show='N';
}

if($show=='Y'){
   $chk_fun='Show';
   $open='visible';
}else if($show=='N'){
   $chk_fun='None';
   $open='hidden';
}

$sel_line="$Sel_Cash_Line";

$afterurl = returnNewOldVersion('new'); // 随机取一个配置的域名
$newLogin = $afterurl;
$_SESSION['USER_MESSAGE_SESSION'] = getScrollMsg();


?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<link href="../../style/member/mem_order_sel.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../../js/jquery.js"></script>
<script type="text/javascript" src="../../js/common.js?v=<?php echo AUTOVER; ?>"></script>
<script class="language_choose" type="text/javascript" src="../../js/zh-cn.js?v=<?php echo AUTOVER; ?>"></script>
    <style>
        .left-game-add .euro_btn:first-child{margin-top: 5px;}
        .euro_btn_img{margin-bottom: 10px;}

    </style>
</head>

<script>
var chk_fun = '<?php echo $chk_fun?>';
var msg='<?php echo $_SESSION['USER_MESSAGE_SESSION'];?>';
top.uid = '<?php echo $uid?>';
var mtype='3';
</script>


<body id="OSEL" class="bodyset" onLoad="bodyLoad();" >

<div id="main" style="overflow-y:auto;overflow-x:hidden;width:224px;height:100%">


  <div id="menu">
    <div class="ord_on" id="order_button" onClick="showOrder();">交易单</div>

    <div class="record_btn" id="record_button" onClick="showRec();">最新十笔交易</div>

  </div>

  <div id="order_div" name="order_div" style="overflow-x:hidden;">
    <div id="pls_bet" name="pls_bet" style="background-color:#E3CFAA;left:0;top:0; display:none;">
     <img src="../../../images/member/order_none.jpg" width="216" height="22">
        <div style="width:216px; height:80px; text-align:center; padding-top:16px;">
            <font style="font:0.75em Arial, Helvetica, sans-serif; font-weight:bold;">点击赔率便可将<br>选项加到交易单里。</font>
            <div class="btn_money">
                <!--<a href="../../app/member/onlinepay/pay_type.php?uid=<?php /*echo $uid*/?>&langx=<?php /*echo $langx*/?>" target="body"  class="online_in">在线存款</a>-->
                <?php if($_SESSION['Agents']=='demoguest'){?>
	                <font style="font:0.75em Arial, Helvetica,sans-serif;font-weight:bold;height:20px;line-height:22px;width:120px;font-size:12px;display:inline-block;color:#333;background-color:#fff;text-decoration: none;">请注册真实用户</font>
                <?php }else{?>
                	<a href="../../app/member/onlinepay/deposit_withdraw.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>" target="body"  class="online_in">在线存款</a>
	                <a href="../../app/member/money/withdrawal.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>" target="body" class="online_out">提款</a>
                <?php }?>
            </div>
        </div>
  </div>
    <div id="bet_div" name="bet_div">
      <iframe id="bet_order_frame" name="bet_order_frame" scrolling="NO" frameborder="NO" border="0" height="0"></iframe>


   <!-- 2018 新增 -->
    <div class="left-game-add" id="left-game-add">
        <?php
        if(TPL_FILE_NAME =='0086' || TPL_FILE_NAME =='0086dj'){
            echo '<div class="euro_btn usdt" >
                    <a href="/tpl/promos.php?prokey=usdt_cz" target="body">充值活动</a>
                  </div>
                  <div class="euro_btn zhsj" >
                     <a href="'.$newLogin.'/'.TPL_NAME.'/tpl/lobby/middle_lives_upgraded.php?username='.$memname.'" target="_blank">账号升级</a>
                  </div>';
        }
        ?>
        <div class="euro_btn live" >
            <a href="../../app/member/zrsx/index_<?php echo TPL_FILE_NAME;?>.php?uid=<?php echo $uid;?>" target="body">真人娱乐</a>
        </div>

        <div class="euro_btn dianjing" >
            <a href="avia_dianjing_<?php echo TPL_FILE_NAME;?>.php?uid=<?php echo $uid;?>" target="body">电子竞技</a>
        </div>
        <div class="euro_btn lottery">
            <a  href="../../tpl/lottery<?php echo (TPL_FILE_NAME=='newhg'?'_newhg':'');?>.php?uid=<?php echo $uid;?>" target="body">彩票游戏</a>
        </div>
        <div class="euro_btn kyqp" >
            <a href="../../app/member/chess_game.php?uid=<?php echo $uid;?>" target="body">棋牌游戏</a>
        </div>
        <div class="euro_btn pt" >
        	<a href="middle_games.php?uid=<?php echo $uid;?>" target="body">电子游艺</a>
        </div>
        <div class="euro_btn fish" >
            <a href="zrsx/fishing.php?uid=<?php echo $uid;?>" target="body">捕鱼游戏</a>
        </div>

        <div class="euro_btn promos" style=" margin-bottom: 5px;">
            <a href="/tpl/promos.php" target="body">优惠活动</a>
        </div>
        <div class="euro_img" >
            <form action="<?php echo $newLogin;?>" method="post" name="old_to_new" id="old_to_new" target="_top"> <!-- '/login.php' -->
                <input type="hidden" name="sign" value="tonew">
                <!--<input type="hidden" name="username" value="<?php /*echo $memname*/?>">
                <input type="hidden" name="password" value="<?php /*echo $password*/?>">-->
                <input type="hidden" name="langx" value="<?php echo $langx?>">
	            <a href="javascript:;" style="border:none;height: 92px" onclick="document.getElementById('old_to_new').submit()">
                <img src="/images/newSport_<?php echo TPL_FILE_NAME;?>.jpg?v=<?php echo AUTOVER; ?>" style="border:none">
            </a>
            </form>
        </div>
    </div>


    <br>

  <!-- LIVE表格资料-->
  <div id=DataTR style="display:none;" >
    <xmp>
      <li><span class="time">*TIME*</span><span class="team">*TEAMS*</span></li>
    </xmp>
  </div>
  <!--公告-->

    <div id="info_div" name="info_div" class="left_gonggao" >
        <div class="msg_box">
        <h2><span style="float:left;">公告</span><span class="more"><a href="#" onClick="showMoreMsg();">更多</a></span> </h2>
            <div class="msg_main">
                <marquee height="70" scrollAmount="1" direction="up" onMouseOver="this.stop();" onMouseOut="this.start();">
                <span id="real_msg"></span>
                </marquee>
            </div>
        </div>
    </div>
    </div>
    <div id="rec5_div" name="rec5_div">
      <iframe id="rec_frame" name="rec_frame" scrolling='NO' frameborder="NO" border="0" height="0"></iframe>
    </div>
  </div>
</div>
<div id='showURL'></div>

<script type="text/javascript" src="../../js/select.js?v=<?php echo AUTOVER; ?>"></script>

</body>
</html>