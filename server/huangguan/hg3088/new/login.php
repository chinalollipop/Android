<?php
session_start();
header ("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");

require ("app/member/include/config.inc.php");

$langx = $_REQUEST['langx'] ;
$username = $_REQUEST['username'] ;
$password = $_REQUEST['password'];
$tonew = isset($_REQUEST['sign']); // 旧站切换新站标志 $toold
$demoplay = isset($_REQUEST['demoplay'])?$_REQUEST['demoplay']:0; 
$yzm_input = isset($_REQUEST['yzm_input'])?$_REQUEST['yzm_input']:'';

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
    <?php  if($tonew){ ?>
        <frame name="SI2_mem_index" src="/app/member/login.php?langx=<?php echo $langx ?>&username=<?php echo $username ?>&password=<?php echo $password?>&sign=<?php echo $tonew?>">
    <?php  }else{ ?>
        <frame name="SI2_mem_index" src="/app/member/login.php?langx=<?php echo $langx ?>&username=<?php echo $username ?>&password=<?php echo $password?>&demoplay=<?php echo $demoplay?>&=code=first&yzm_input=<?php echo $yzm_input;?>">
    <?php } ?>


<frame name="SI2_func" scrolling="NO" noresize src="./ok.html">
<!--<frame src="UntitledFrame-5"></frameset>-->
<noframes> 
<body >

</body>
</noframes> 
</html>

