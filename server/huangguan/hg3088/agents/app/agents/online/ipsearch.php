<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include ("../include/address.mem.php");
require_once ("../include/config.inc.php");

include_once ("../include/IpSearch.php");
checkAdminLogin(); // 同一账号不能同时登陆

// 管理员登录
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

$langx=$_SESSION["langx"];
require ("../include/traditional.$langx.inc.php");

$uid=$_REQUEST["uid"];
$loginname=$_SESSION['UserName'];
$username=$_REQUEST['username'];
$level=$_REQUEST['level'];
$is_online=$_REQUEST['is_online'];
$lv=$_REQUEST['lv'];
$ipcheck=$_REQUEST['ipcheck'];
$name_ip=trim($_REQUEST['name_ip']);
$sdate=$_REQUEST['sdate'];
$edate=$_REQUEST['edate'];
$page=$_REQUEST['page'];
$tip = '登录成功！！';
$date=date('Y-m-d');


if($level==''){
   $level='0';
} 
if($level=='0' or $level==''){ // 默认会员
    $sec_data = DBPREFIX.MEMBERTABLE;
    $levelinfo = $Rep_Member;
}else if($level=='1'){ //代理
    $sec_data = DBPREFIX.'web_agents_data';
   $levelinfo = $Rep_Agent;
}

if(!$sdate){
    $sdate = $edate = $date ;
}

// 查询ip
if($ipcheck == TRUE) {
	$n_sql .=  "IpLoginIP = '$name_ip' and  ";
}else {
	if($name_ip!=''){
		$n_sql .=  "IpUserName = '$name_ip' and ";
	}
}

if (!empty($sdate) && !empty($edate)) {
	$n_sql .= "IpLoginDate >= '$sdate' and IpLoginDate <= '$edate'";
}
$data=DBPREFIX.'web_loginip_data';
// $sql="select a.IpUserName,a.IpAgents,a.IpType,a.IpWinLossCredit,a.IpAlias,a.IpLoginDate,a.IpLoginTime,a.IpLogin_Url,a.IpLoginIP,b.Online from $data AS a, $sec_data AS b where a.IpUserId=b.ID $n_sql order by Ipid desc";
$sql="select IpUserName,IpAgents,IpType,IpWinLossCredit,IpAlias,IpLoginDate,IpLoginTime,IpLogin_Url,IpLoginIP from $data  where $n_sql order by Ipid desc";
// echo $sql;
$result=mysqli_query($dbLink,$sql);
$cou=mysqli_num_rows($result);

$page_size=50;
$page_count=ceil($cou/$page_size);
$offset=$page*$page_size;
$sql=$sql."  limit $offset,$page_size";
$result = mysqli_query($dbLink, $sql);
// echo $sql ;
if($cou==0 && $name_ip){ // 此时查询注册 ip 或者 登录 ip
    if($ipcheck == TRUE) {
        $s_n_sql .=  "LoginIP = '$name_ip' or RegisterIP = '$name_ip'";
    }else {
        if($name_ip!=''){
            $s_n_sql .=  "UserName = '$name_ip'";
        }
    }
    $s_sql="select Agents,UserName,Alias,WinLossCredit,LoginTime,Url,RegisterIP from $sec_data where  $s_n_sql order by ID desc";
    $result = mysqli_query($dbLink, $s_sql);
    $cou=mysqli_num_rows($result);
   // echo $s_sql;
}

$ipdatabase = '../include/ip.dat';
$reader = new IpSearch($ipdatabase);

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>IP查询</title>
<link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
<STYLE type="text/css">
input{min-height: auto;}
</STYLE>
</head>

<body onLoad="onLoad()";>
<FORM NAME="myFORM" ACTION="" METHOD=POST>
    <dl class="main-nav">
        <dt>IP查询</dt>
        <dd>
        <table >
          <tr class="m_tline">
            <td >
            	<input type="checkbox" name="ipcheck" value="true" <?php echo $ipcheck?'checked':''?> >
                IP查询?&nbsp;&nbsp;&nbsp;
                <select class="za_select_auto" id="level" name="level">
                  　<option value="0" <?php if ($level=='0'){echo 'selected';}?>>会员</option>
                    <option value="1" <?php if ($level=='1'){echo 'selected';}?>>代理</option>
                </select>&nbsp;&nbsp;&nbsp;
                <input name="name_ip" type="text" id="name" value="<?php echo $name_ip?>" size="10">&nbsp;&nbsp;&nbsp;
             <!--   是否在线
                <select class="is_online" name="is_online">
                    <option value="all">全部</option>
                    <option value="on" <?php /*if ($is_online=='on'){echo 'selected';}*/?>>在线</option>
                    <option value="off" <?php /*if ($is_online=='off'){echo 'selected';}*/?>>下线</option>
                </select>-->
                	时间区间:
                    <input type="text" class="za_text_auto" name="sdate" onclick="laydate({istime: false, format: 'YYYY-MM-DD'})" value="<?php echo $sdate;?>" readonly/>~~
                    <input type="text" class="za_text_auto" name="edate" onclick="laydate({istime: false, format: 'YYYY-MM-DD'})" value="<?php echo $edate;?>" readonly/>

                共<?php echo $cou?>条
                <select name='page'>
                    <?php
                    if ($page_count==0){
                        $page_count=1;
                    }
                    for($i=0;$i<$page_count;$i++){
                        if ($i==$page){
                            echo "<option selected value='$i'>".($i+1)."</option>";
                        }else{
                            echo "<option value='$i'>".($i+1)."</option>";
                        }
                    }
                    ?>
                </select> 共<?php echo $page_count?> 页
                <input class=za_button type="submit" name="Submit" value="查询">
            </td>
          </tr>

        </table>
   </dd>
 </dl>
</FORM>
<div class="main-ui">
        <table class="m_tab">
          <tr  class="m_title">
            <td >代理商</td>
            <td >层 级</td>
            <td >账 号</td>
            <td >姓 名</td>
           <!-- <td >是否在线</td>-->
            <td >信 息</td>
            <td >输赢额度</td>
            <td >登陆时间</td>
            <td >登陆网址</td>
           <td width="281">IP位置</td>
          </tr>
<?php


	if($myrow=mysqli_fetch_array($result)){
		do{
?>
          <tr class="m_cen" onMouseOut="this.style.backgroundColor=''" onMouseOver="this.style.backgroundColor='#BFDFFF'" bgcolor="#FFFFFF">
            <td ><?php echo $myrow["IpAgents"]?$myrow["IpAgents"]:$myrow["Agents"] ?></td>
            <td ><?php echo $levelinfo;?></td>
            <td ><?php echo $myrow["IpUserName"]?$myrow["IpUserName"]:$myrow["UserName"] ?></td>
            <td ><?php echo $myrow["IpAlias"]?$myrow["IpAlias"]:$myrow["Alias"] ?></td>
          <!--  <td >
                <?php
/*                if ($myrow['Online'] == 1){
                    echo '<span style="color:red">在线</span>';
                }else{
                    echo '下线';
                }
                */?>
            </td>-->
            <td ><?php echo $tip; ?></td>
            <td  align="right"><?php echo number_format($myrow["IpWinLossCredit"]?$myrow["IpWinLossCredit"]:$myrow["WinLossCredit"],0)?></td>
            <td ><?php echo $myrow["IpLoginTime"]?$myrow["IpLoginTime"]:$myrow["LoginTime"] ?></td>
            <td ><font color="#ff0000"><?php echo $myrow["IpLogin_Url"]?$myrow["IpLogin_Url"]:$myrow["Url"] ?></font></td>
              <?php
              $ip = $myrow["IpLoginIP"]?$myrow["IpLoginIP"]:$myrow["RegisterIP"];

              $ipArea = $reader->get($ip);
//              echo $ipArea;
              $aIpArea = explode('|',$ipArea);
              $aIpArea = array_slice($aIpArea,0,6);
              $sIpArea = implode('|',$aIpArea);
              ?>
            <td ><?php echo $myrow["IpLoginIP"]?$myrow["IpLoginIP"]:$myrow["RegisterIP"]; ?>&nbsp;|&nbsp;<?php echo $sIpArea ;?> </td>
          </tr>
<?php

		}
		while ($myrow=mysqli_fetch_array($result));
	}else{
    	echo "<tr height=20><td colspan=12 ><div align=center>无人在线</div></td></tr>";
	}
?>
      </table>
</div>

<script type="text/javascript" src="../../../js/agents/register/laydate.min.js"></script>
<script>
    function onLoad(){
        var level = document.getElementById('level');
        level.value = '<?php echo $level?>';
    }
    function OpenIP(url) {
        window.open(url,'IP','width=300,height=200');
    }
</script>

</body>
</html>
