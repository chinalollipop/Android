<?php

session_start();
header ("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");
require ("app/member/include/config.inc.php");

$toold = isset($_REQUEST['sign'])?$_REQUEST['sign']:''; // 旧站切换新站标志
$demoplay = isset($_REQUEST['demoplay'])?$_REQUEST['demoplay']:0; 
$yzm_input = isset($_REQUEST['yzm_input'])?$_REQUEST['yzm_input']:'' ; // 广告站进来需要拿验证码
$pctip= isset($_REQUEST['topc'] )?$_REQUEST['topc']:'' ; // 从手机端跳转到pc端标志
?>

<html>
<head>

<title>Welcome </title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<script>
top.casino = 'SI2';
top.game_alert = '';
 </script>
<frameset rows="*,0,0" frameborder="NO" border="0" framespacing="0">
<?php  if($toold ){ ?>
<frame name="SI2_mem_index" src="/app/member/login.php?topc=<?php echo $pctip?>&sign=<?php echo $toold?>&langx=<?php echo $_REQUEST['langx'] ?>&demoplay=<?php echo $demoplay?>&username=<?php echo $_REQUEST['username'] ?>&password=<?php echo $_REQUEST['password'] ?>&key=<?php echo $_REQUEST['key'] ?>&id=<?php echo $_REQUEST['id'] ?>">
<?php }else{?>
<frame name="SI2_mem_index" src="/app/member/login.php?topc=<?php echo $pctip?>&langx=<?php echo $_REQUEST['langx'] ?>&demoplay=<?php echo $demoplay?>&username=<?php echo $_REQUEST['username'] ?>&password=<?php echo $_REQUEST['password'] ?>&realname=<?php echo $_REQUEST['realname'] ?>&=code=first&yzm_input=<?php echo $yzm_input ?>">
<?php }?>

<!--<frame src="UntitledFrame-5"></frameset>-->
<noframes> 
<body >

</body>
</noframes> 
</html>

