<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include ("../../agents/include/address.mem.php");
// echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
require ("../../agents/include/config.inc.php");


checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$uid=$_REQUEST["uid"];
$langx=$_REQUEST["langx"];
$id=$_REQUEST["id"];
$username=$_REQUEST['username'];
$type=$_REQUEST["type"];
$lv=$_REQUEST["lv"];

require ("../../agents/include/traditional.$langx.inc.php");
$name = $_SESSION['UserName'];

$loginfo='查看子帐号权限';
if ($type=='Y'){
	for($i=0;$i<=51;$i++){
		$no="OP".$i;
		$num=$_REQUEST[$no];
		if ($num!=1){
			$num=0;
		}
		$number.=$num.",";
	}

	// 会员存提款 层级权限控制  a-z标识层级英文标识码
     foreach(range('a','z') as $v){
         $no_ck_type=$v.'-0'; //分层存款
         $no_tk_type=$v.'-1'; //分层提款
         if(isset($_REQUEST[$no_ck_type])) {
             $checkLevel[] = $no_ck_type;
         }

         if(isset($_REQUEST[$no_tk_type])) {
             $checkLevel[] = $no_tk_type;
         }
     }
    $checkLevels = implode(',' , $checkLevel);
    $number.= $checkLevels.",";

	$loginfo='修改子帐号:'.$username.'权限成功!';
	$mysql="update ".DBPREFIX."web_system_data set Competence='$number' where ID='$id'";
	mysqli_query($dbMasterLink,$mysql);
	echo "<script language=javascript>document.location='competence.php?uid=$uid&langx=$langx&id=$id&username=$username';</script>";
}
if ($type=='S'){
	$style=$_REQUEST["style"];
	$loginfo='修改子帐号:'.$username.'样式成功!';
	$mysql="update ".DBPREFIX."web_system_data set Style='$style' where ID='$id'";
	mysqli_query($dbMasterLink,$mysql);
	echo "<script language=javascript>document.location='competence.php?uid=$uid&langx=$langx&id=$id&username=$username';</script>";		
}
$mysql = "select Competence,Style from ".DBPREFIX."web_system_data where ID='$id'";
$result = mysqli_query($dbLink,$mysql);
$row = mysqli_fetch_assoc($result);
$competence=$row['Competence'];
$style=$row['Style'];
$num=explode(",",$competence);

// print_r($num) ;

// Array ( [0] => 0 [1] => 0 [2] => 0 [3] => 0 [4] => 0 [5] => 0 [6] => 0 [7] => 0 [8] => 0 [9] => 0 [10] => 0 [11] => 0 [12] => 0 [13] => 0 [14] => 0 [15] => 0 [16] => 0 [17] => 0 [18] => 0 [19] => 0 [20] => 0 [21] => 0 [22] => 1 [23] => 1 [24] => 1 [25] => 0 [26] => 0 [27] => 0 [28] => 0 [29] => 0 [30] => 0 [31] => 0 [32] => 0 [33] => 0 [34] => 0 [35] => 0 [36] => 0 [37] => 0 [38] => 0 [39] => 0 [40] => 0 [41] => 0 [42] => 0 [43] => 1 [44] => 0 [45] => 0 [46] => 0 [47] => 0 [48] => 0 [49] => 0 [50] => [51] => )

// 当前层级
$levelmysql = "select id,ename,name from ".DBPREFIX."gxfcy_userlevel";
$levelresult = mysqli_query($dbLink,$levelmysql);


?>
<html>
<head>
<title>main</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
<style>
    .m_tab td{text-align:left;padding-left: 40px;}
    .m_tab .sec{padding-left: 65px;}
</style>
</head>
<body>
<dl class="main-nav"><dt>子账号权限设置</dt>
    <dd>
        <a href='../agents/subuser.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&lv=<?php echo $lv?>'>回上一页</a>
    </dd>
</dl>
<div class="main-ui" style="width: 1000px">

<table class="m_tab">
  <tr>
    <td width="320">
        <table width="300"  class="m_tab">
      <form name="myFORM" action="" method=POST>
        <tr class="m_title">
          <td width="98">功能菜单</td>
          <td width="50">状态</td>
          <td width="97">功能菜单</td>
          <td width="50">状态</td>
        </tr>
        <tr class="m_cen" >
          <td>游戏管理</td>
          <td><input type="checkbox" name="OP1" value="1" <?php if($num[1]==1) echo "checked";?>></td>
          <td>其他管理/基础设置/活动管理</td>
          <td><input type="checkbox" name="OP33" value="1" <?php if($num[33]==1) echo "checked";?>></td>
        </tr>
        <tr class="m_cen" >
          <td>&nbsp;&nbsp;&nbsp;&nbsp;┊--系统参数</td>
          <td><input type="checkbox" name="OP28" value="1" <?php if($num[28]==1) echo "checked";?>></td>
          <td>&nbsp;&nbsp;&nbsp;&nbsp;┊--第三方支付</td>
          <td><input type="checkbox" name="OP25" value="1" <?php if($num[25]==1) echo "checked";?>></td>
        </tr>
        <tr class="m_cen" >
          <td>&nbsp;&nbsp;&nbsp;&nbsp;┊--系统公告</td>
          <td><input type="checkbox" name="OP2" value="1" <?php if($num[2]==1) echo "checked";?>></td>
          <td>&nbsp;&nbsp;&nbsp;&nbsp;┊--银行支付</td>
          <td><input type="checkbox" name="OP26" value="1" <?php if($num[26]==1) echo "checked";?>></td>
        </tr>
        <tr class="m_cen" >
          <td>&nbsp;&nbsp;&nbsp;&nbsp;┊--系统短信</td>
          <td><input type="checkbox" name="OP3" value="1" <?php if($num[3]==1) echo "checked";?>></td>
          <td>报表/查询</td>
          <td><input type="checkbox" name="OP32" value="1" <?php if($num[32]==1) echo "checked";?>></td>
        </tr>
        <tr class="m_cen" >
          <td>&nbsp;&nbsp;&nbsp;&nbsp;┊--系统消息</td>
          <td><input type="checkbox" name="OP4" value="1" <?php if($num[4]==1) echo "checked";?>></td>
          <td>&nbsp;&nbsp;&nbsp;&nbsp;┊--报表</td>
          <td><input type="checkbox" name="OP22" value="1" <?php if($num[22]==1) echo "checked";?>></td>
        </tr>
        <tr class="m_cen" >
          <td>&nbsp;&nbsp;&nbsp;&nbsp;┊--数据刷新</td>
          <td><input type="checkbox" name="OP5" value="1" <?php if($num[5]==1) echo "checked";?>></td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;┊--今日统计</td>
            <td><input type="checkbox" name="OP44" value="1" <?php if($num[44]==1) echo "checked";?>></td>

        </tr>
        <tr class="m_cen" >
          <td>&nbsp;&nbsp;&nbsp;&nbsp;┊--时时返水</td>
          <td><input type="checkbox" name="OP47" value="1" <?php if($num[47]==1) echo "checked";?>></td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;┊--系统日志</td>
            <td><input type="checkbox" name="OP14" value="1" <?php if($num[14]==1) echo "checked";?>></td>

        </tr>
        <tr class="m_cen" >

          <td>账号管理</td>
          <td><input type="checkbox" name="OP29" value="1" <?php if($num[29]==1) echo "checked";?>></td>
            <td>现金系统</td>
            <td><input type="checkbox" name="OP37" value="1" <?php if($num[37]==1) echo "checked";?>></td>


        </tr>
        <tr class="m_cen" >
          <td>&nbsp;&nbsp;&nbsp;&nbsp;┊--会员</td>
          <td><input type="checkbox" name="OP21" value="1" <?php if($num[21]==1) echo "checked";?>></td>
          <td>联盟限制</td>
          <td><input type="checkbox" name="OP7" value="1" <?php if($num[7]==1) echo "checked";?>></td>

        </tr>

          <tr class="m_cen" >
              <td class="sec">┊┊会员手机&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;</td>
              <td><input type="checkbox" name="OP38" value="1" <?php if($num[38]==1) echo "checked";?>></td>
              <td>注单操作</td>
              <td><input type="checkbox" name="OP40" value="1" <?php if($num[40]==1) echo "checked";?>></td>
          </tr>
          <tr class="m_cen" >
              <td class="sec"> ┊┊会员资金密码</td>
              <td><input type="checkbox" name="OP48" value="1" <?php if($num[48]==1) echo "checked";?>></td>
              <td>数据操盘</td>
              <td><input type="checkbox" name="OP8" value="1" <?php if($num[8]==1) echo "checked";?>></td>

          </tr>
          <tr class="m_cen" >
              <td class="sec"> ┊┊会员微信/QQ</td>
              <td><input type="checkbox" name="OP39" value="1" <?php if($num[39]==1) echo "checked";?>></td>
              <td>审核比分</td>
              <td><input type="checkbox" name="OP9" value="1" <?php if($num[9]==1) echo "checked";?>></td>

          </tr>
          <tr class="m_cen" >
              <td class="sec"> ┊┊会员真实姓名</td>
              <td> <input type="checkbox" name="OP41" value="1" <?php if($num[41]==1) echo "checked";?>> </td>
              <td>滚球注单</td>
              <td><input type="checkbox" name="OP10" value="1" <?php if($num[10]==1) echo "checked";?>></td>
          </tr>
          <tr class="m_cen" >
              <td class="sec">┊┊设置修改会员</td>
              <td><input type="checkbox" name="OP43" value="1" <?php if($num[43]==1) echo "checked";?>></td>
              <td>查询注单</td>
              <td><input type="checkbox" name="OP11" value="1" <?php if($num[11]==1) echo "checked";?>></td>
          </tr>
        <tr class="m_cen" >
            <td>&nbsp;&nbsp;&nbsp;&nbsp;┊--代理商</td>
            <td><input type="checkbox" name="OP20" value="1" <?php if($num[20]==1) echo "checked";?>></td>
            <td>赔率设置</td>
            <td><input type="checkbox" name="OP12" value="1" <?php if($num[12]==1) echo "checked";?>></td>

        </tr>
        <tr class="m_cen" >
          <td>常用管理</td>
          <td><input type="checkbox" name="OP30" value="1" <?php if($num[30]==1) echo "checked";?>></td>
            <td>分红明细</td>
            <td><input type="checkbox" name="OP13" value="1" <?php if($num[13]==1) echo "checked";?>></td>
        </tr>
        <tr class="m_cen" >
          <td>&nbsp;&nbsp;&nbsp;&nbsp;┊--总代理</td>
          <td><input type="checkbox" name="OP19" value="1" <?php if($num[19]==1) echo "checked";?>></td>

            <td>币值设置</td>
            <td><input type="checkbox" name="OP6" value="1" <?php if($num[6]==1) echo "checked";?>></td>
        </tr>
        <tr class="m_cen" >
          <td>&nbsp;&nbsp;&nbsp;&nbsp;┊--股东</td>
          <td><input type="checkbox" name="OP18" value="1" <?php if($num[18]==1) echo "checked";?>></td>
            <td>棋牌管理</td>
            <td><input type="checkbox" name="OP42" value="1" <?php if($num[42]==1) echo "checked";?>></td>

        </tr>
        <tr class="m_cen" >
          <td>&nbsp;&nbsp;&nbsp;&nbsp;┊--公司</td>
          <td><input type="checkbox" name="OP17" value="1" <?php if($num[17]==1) echo "checked";?>></td>
            <td>皇冠体育</td>
            <td><input type="checkbox" name="OP45" value="1" <?php if($num[45]==1) echo "checked";?>></td>
        </tr>
        <tr class="m_cen" >
          <td>&nbsp;&nbsp;&nbsp;&nbsp;┊--子帐号</td>
          <td><input type="checkbox" name="OP16" value="1" <?php if($num[16]==1) echo "checked";?>></td>
            <td>视讯管理</td>
            <td><input type="checkbox" name="OP34" value="1" <?php if($num[34]==1) echo "checked";?>></td>

        </tr>
        <tr class="m_cen" >
            <td>&nbsp;&nbsp;&nbsp;&nbsp;┊--基本资料</td>
            <td><input type="checkbox" name="OP15" value="1" <?php if($num[15]==1) echo "checked";?>></td>
            <td>彩票电子</td>
            <td><input type="checkbox" name="OP35" value="1" <?php if($num[35]==1) echo "checked";?>></td>
        </tr>
        <tr class="m_cen" >
            <td>金额转换</td>
            <td><input type="checkbox" name="OP23" value="1" <?php if($num[23]==1) echo "checked";?>></td>
          <td>球赛管理</td>
          <td><input type="checkbox" name="OP31" value="1" <?php if($num[31]==1) echo "checked";?>></td>

        </tr>
        <tr class="m_cen" >
            <td>在线人数</td>
            <td><input type="checkbox" name="OP0" value="1" <?php if($num[0]==1) echo "checked";?>></td>
          <td>层级管理</td>
          <td><input type="checkbox" name="OP27" value="1" <?php if($num[27]==1) echo "checked";?>></td>

        </tr>
        <tr class="m_cen" >
            <td>存取款明细</td>
            <td><input type="checkbox" name="OP36" value="1" <?php if($num[36]==1) echo "checked";?>></td>
            <td>软件下载</td>
            <td><input type="checkbox" name="OP24" value="1" <?php if($num[24]==1) echo "checked";?>></td>

        </tr>

          <tr class="m_cen" >
               <td>试玩参观手机号</td>
              <td><input type="checkbox" name="OP49" value="1" <?php if($num[49]==1) echo "checked";?>></td>
              <td>后台IP白名单</td>
              <td><input type="checkbox" name="OP46" value="1" <?php if($num[46]==1) echo "checked";?>></td>
          </tr>
        <!--
        Array ( [0] => 0 [1] => 1 [2] => 1 [3] => 0 [4] => 1 [5] => 1 [6] => 0 [7] => 0 [8] => 0 [9] => 0 [10] => 0 [11] => 0 [12] => 0 [13] => 0 [14] => 0 [15] => 0 [16] => 0 [17] => 0 [18] => 0 [19] => 0 [20] => 0 [21] => 0 [22] => 1 [23] => 1 [24] => 1 [25] => 0 [26] => 0 [27] => 0 [28] => 1 [29] => 1 [30] => 0 [31] => 0 [32] => 0 [33] => 0 [34] => 1 [35] => 1 [36] => 1 [37] => 1 [38] => 0 [39] => 0 [40] => 0 [41] => 0 [42] => 0 [43] => 0 [44] => 0 [45] => 0 [46] => 0 [47] => 0 [48] => 0 [49] => 0 [50] => a-0 [51] => a-1 [52] => c-0 [53] => c-1 [54] => e-0 [55] => e-1 [56] => f-0 [57] => f-1 [58] => h-0 [59] => h-1 [60] => )
        -->
      <!--显示层级 会员分配权限 a-0 a层 存款   a-1 层出款-->
      <?php
        while($levelrow = mysqli_fetch_assoc($levelresult)) {
            $levelename = $levelrow['ename'];
            $levelname = $levelrow['name'];
       ?>
            <tr class="m_cen" >
                <td><?php echo $levelename . "层级" . $levelname; ?>存款</td>
                <td><input type="checkbox" name="<?php echo $levelename.'-0'; ?>" value="1" <?php if(in_array($levelename.'-0',$num)) echo "checked";?>></td>
                <td><?php echo $levelename . "层级" . $levelname; ?>出款</td>
                <td><input type="checkbox" name="<?php echo $levelename.'-1'; ?>" value="1" <?php if(in_array($levelename.'-1',$num)) echo "checked";?>></td>
            </tr>
      <?php } ?>


        <tr class="m_cen" >
          <td colspan="4">
          <input type="hidden" name="id" id="id" value="<?php echo $id?>">
          <input type="hidden" name="type" id="type" value="Y">
          <input type="reset" name="Cancel" value="重置" class="za_button">
          <input type="submit" name="Submit" value="设置" class="za_button"></td>
        </tr>
      </form>
    </table></td>
    <td width="180" valign="top">
        <table width="180" class="m_tab">
      <form name="myFORM" action="" method=POST>
      <tr class="m_title">
        <td colspan="2">网站样式</td>
        </tr>
      <tr class="m_cen">
        <td width="100">皇冠</td>
        <td width="77"><input name="style" id="style" type="radio" <?php echo $style==1?"checked":""?> value="1"></td>
        </tr>
      <tr class="m_cen">
        <td>皇家</td>
        <td><input name="style" id="style" type="radio" <?php echo $style==2?"checked":""?> value="2"></td>
        </tr>
      <tr class="m_cen">
        <td>皇马</td>
        <td><input name="style" id="style" type="radio" <?php echo $style==3?"checked":""?> value="3"></td>
        </tr>
      <tr class="m_cen">
        <td>皇室</td>
        <td><input name="style" id="style" type="radio" <?php echo $style==4?"checked":""?> value="4"></td>
        </tr>
      <tr class="m_cen">
        <td colspan="2">
          <input type="hidden" name="id" id="id" value="<?php echo $id?>">
          <input type="hidden" name="type" id="type" value="S">
          <input type="reset" name="Cancel" value="重置" class="za_button">
          <input type="submit" name="Submit" value="设置" class="za_button"></td>
      </tr>
      </form>
    </table></td>
  </tr>
</table>

</div>

</body>
</html>
<?php
$ip_addr = get_ip();
$mysql="insert into ".DBPREFIX."web_mem_log_data(UserName,Logintime,ConText,Loginip,Url) values('$name',now(),'$loginfo','$ip_addr','".BROWSER_IP."')";
mysqli_query($dbMasterLink,$mysql);
?>