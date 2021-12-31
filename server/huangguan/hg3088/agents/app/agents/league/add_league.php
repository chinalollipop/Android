<?php
session_start();
include ("../include/address.mem.php");
require_once ("../include/config.inc.php");
checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$uid=$_REQUEST['uid'];
$langx=$_SESSION["langx"];
$loginname=$_SESSION['UserName'];
$tpye=$_REQUEST['tpye'];
require ("../include/traditional.$langx.inc.php");

?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <script type="text/javascript" src="../../../js/agents/common.js?v=<?php echo AUTOVER; ?>"></script>
</head>
<body >
<dl class="main-nav"><dt>新增联盟</dt>
    <dd> 新增联盟&nbsp;--&nbsp;<a href="javascript:history.go( -1 );">回上一页</a> </dd>
</dl>
<div class="main-ui">
        <?php

        $i=1;
        $date=date("Y-m-d",time()+10*24*60*60);
        $mysql = "select distinct M_League,M_League_tw,M_League_en FROM `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='".$tpye."' and M_Date<='".$date."'";
        $result = mysqli_query($dbLink,$mysql);
        while($row=mysqli_fetch_assoc($result)){
        $league=$row['M_League'];
        $league_tw=$row['M_League_tw'];
        $league_en=$row['M_League_en'];
        $m_sql="select * from ".DBPREFIX."match_league where Type='".$tpye."' and M_League='".$league."'";
        $m_result = mysqli_query($dbLink,$m_sql);

       if($m_cou=mysqli_fetch_assoc($m_result)){ // 执行到这里了，需要调整

        }else{ // 添加新联盟
              if ($league!=""){
              $sql="insert into ".DBPREFIX."match_league set M_League='".$league."',M_League_tw='".$league_tw."',M_League_en='".$league_en."',Type ='".$tpye ."'";
              mysqli_query($dbMasterLink,$sql);
              echo "&nbsp;&nbsp;成功添加".$i."条".$league."<br>";
              $i++;
              }
        }
        }
        if ($i==1){
            echo "&nbsp;&nbsp;无新联盟";
        }
        ?>

</div>

</body>
</html>
