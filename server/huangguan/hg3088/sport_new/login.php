<?php

session_start();
header ("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");
require ("app/member/include/config.inc.php");

$toold = isset($_REQUEST['sign'])?$_REQUEST['sign']:''; // 旧站切换新站标志
$demoplay = isset($_REQUEST['demoplay'])?$_REQUEST['demoplay']:0; 

if($toold){ // 从旧版切换过来需要初始化
    echo '<script> localStorage.setItem(\'cp_url_num\',1);localStorage.setItem(\'third_cp_url_num\',1) </script>'; // 记录彩票登录次数,1 为初始化
}

?>

<html>
<head>

<title>Welcome </title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<script>


 </script>
<frameset rows="*,0,0" frameborder="NO" border="0" framespacing="0">
<?php  if( $toold ){ ?>
<frame name="SI2_mem_index" src="/app/member/login.php?sign=<?php echo $toold?>&langx=<?php echo $_REQUEST['langx'] ?>&demoplay=<?php echo $demoplay?>&username=<?php echo $_REQUEST['username'] ?>&password=<?php echo $_REQUEST['password'] ?>&key=<?php echo $_REQUEST['key'] ?>&id=<?php echo $_REQUEST['id'] ?>">
<?php }else{?>
<frame name="SI2_mem_index" src="/app/member/login.php?langx=<?php echo $_REQUEST['langx'] ?>&demoplay=<?php echo $demoplay?>&username=<?php echo $_REQUEST['username'] ?>&password=<?php echo $_REQUEST['password'] ?>&=code=first">
<?php }?>

<!--<frame src="UntitledFrame-5"></frameset>-->
<noframes> 
<body >

</body>
</noframes> 
</html>

