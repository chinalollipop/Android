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

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$uid=$_REQUEST["uid"];
$langx=$_SESSION["langx"];
$loginname=$_SESSION['UserName'];
$lv=$_SESSION['admin_level'];
$username=$_REQUEST['username'];
$active=$_REQUEST['active'];
$level=$_REQUEST['level'];
$name= str_replace(' ','',$_REQUEST["name"]);
// 13 苹果，14 安卓,1 移动端, 0 PC 端
$member_status = $_REQUEST['member_status'];

// 会员数
if ($_REQUEST['online']!=""){ // 统计在线人数
$memsql="select Online from ".DBPREFIX.MEMBERTABLE." where Online=1";
$memresult=mysqli_query($dbLink,$memsql);
$memcou=mysqli_num_rows($memresult);
echo $memcou;
exit;
}

if($level==''){
    $level='member';
}

$date=date('Y-m-d');
// 点击会员踢线
if ($active==1){
    if($level=='member'){ // 会员
        $sql = "update ".DBPREFIX.MEMBERTABLE." set Oid='logout',Online='0',LogoutTime=now() where UserName='$username'";
        mysqli_query($dbMasterLink,$sql) or die('操作失败!');
    }else{ // 代理
        $sql = "update ".DBPREFIX."web_agents_data set Oid='logout',Online='0',LogoutTime=now() where UserName='$username'";
        mysqli_query($dbMasterLink,$sql) or die('操作失败!!');
    }

    $loginfo = $loginname.' 对帐号 <font class="green">'.$username.'</font> 进行了 <font class="red">踢线</font>' ;
    innsertSystemLog($loginname,$lv,$loginfo);

}

$mem_sql_st= '';
if($level=='member' or $level==''){ // 在线会员
   $data=DBPREFIX.MEMBERTABLE;
   $mem_sql = ',Money,online_status,WinLossCredit' ;
   if($member_status !=''){ // 非全部
       $mem_sql_st = 'and online_status='.$member_status ;
   }

}else if($level=='agents'){ // 在线代理
   $data=DBPREFIX.'web_agents_data';
}
if ($name!=''){
$n_sql="and UserName like '%$name%'";
}else{
$n_sql='';
}

$ipdatabase = '../include/ip.dat';
$reader = new IpSearch($ipdatabase);

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>在线人数</title>
<link rel="stylesheet" href="../../../style/agents/control_main.css" type="text/css">

</head>

<body onLoad="onLoad()";>
<FORM NAME="myFORM" ACTION="" METHOD=POST>
    <dl class="main-nav">
        <dt>在线人数</dt>
        <dd>
        <table >
          <tr class="m_tline">
            <td >
                在线客户端&nbsp;&nbsp;
                <select class="za_select_auto" onchange=document.myFORM.submit(); id="member_status" name="member_status">
                    <option value="">全部</option>
                    <option value="0" <?php if($member_status=='0'){echo 'selected';} ?> >PC端在线</option>
                    <option value="22" <?php if($member_status=='22'){echo 'selected';} ?> >综合版在线</option>
                    <option value="1" <?php if($member_status=='1'){echo 'selected';} ?> >移动端在线</option>
                    <option value="13" <?php if($member_status=='13'){echo 'selected';} ?> >IOS平台在线</option>
                    <option value="14" <?php if($member_status=='14'){echo 'selected';} ?> >安卓平台在线</option>

                </select> &nbsp;&nbsp;
                在线会员/代理&nbsp;&nbsp;
                <select class="za_select_auto" onchange=document.myFORM.submit(); id="level" name="level">
                  　<option value="member">会员</option>
                    <option value="agents">代理</option>
                </select>
                &nbsp;&nbsp;&nbsp;在线账号&nbsp;&nbsp;&nbsp;
                <input name="name" type="text" id="name" value="<?php echo $name?>" size="10" placeholder="输入账号">&nbsp;&nbsp;&nbsp;
                <input class=za_button type="submit" name="Submit" value="提交">

                <span class="red">点击账号踢线</span>
            </td>

          </tr>

        </table>
   </dd>
 </dl>
<div class="main-ui">
    <table class="m_tab">
  <tr> 
    <td valign="top"> 
	
        <table width="975" border="0" cellpadding="0" cellspacing="1" class="m_tab">
          <tr  class="m_title"> 
            <td width="34">序 号</td>
            <td width="60">账 号</td>
            <?php
            if($level!='agents'){ // 会员才有
                echo '<td width="60">上级代理</td>' ;
            }
            ?>
            <td width="60">名 称</td>
            <td width="75">信用额度</td>
            <td width="75">输赢额度</td>
              <?php
               if($level!='agents'){ // 会员才有
                   echo '<td width="75">在线状态</td>' ;
               }
              ?>
            <td width="125">登陆时间</td>
            <td width="125">活动时间</td>
            <td width="130">登陆网址</td>
           <td width="281">登陆IP和地区</td>
          </tr>
<?php
$i=1;
$sql="select UserName,Alias".$mem_sql.",Credit,LoginTime,OnlineTime,Url,LoginIP,Agents from $data where Online=1 and Oid!='logout' $n_sql $mem_sql_st order by id desc";
// echo $sql;
$result=mysqli_query($dbLink,$sql);
if($myrow=mysqli_fetch_array($result)){
do
{
// $mamname=$myrow['Memname'];

?>
          <tr class="m_cen" onMouseOut="this.style.backgroundColor=''" onMouseOver="this.style.backgroundColor='#BFDFFF'" bgcolor="#FFFFFF"> 
            <td ><?php echo $i?></td>
            <td ><a class="a_link" href="online.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&active=1&username=<?php echo $myrow["UserName"]?>&level=<?php echo $level?>"><?php echo $myrow["UserName"]?></a></td>
            <?php
                if($level!='agents'){ // 会员才有
                    echo '<td>' . $myrow['Agents'] . '</td>';
                }
            ?>
            <td ><?php echo $myrow["Alias"]?></td>
            <td  align="right">
                <?php
                if($level=='agents'){ // 代理
                    echo "0" ;
                }else{ // 会员
                    echo number_format($myrow["Money"],0) ;
                }

                ?>
            </td>
            <td  align="right"><?php echo number_format($myrow["WinLossCredit"],0)?></td>
              <?php
              if($level!='agents'){ // 会员才有
                  switch ($myrow['online_status']){
                      case '0': // pc 端
                          $statusstr = 'PC端在线' ;
                          break ;
                      case '1': // 移动端在线
                          $statusstr = '移动端在线' ;
                          break ;
                      case '13': // IOS平台在线
                          $statusstr = 'IOS平台在线' ;
                          break ;
                      case '14': // 安卓平台在线
                          $statusstr = '安卓平台在线' ;
                          break ;
                      case '22': // 综合版在线
                          $statusstr = '综合版在线' ;
                          break ;
                  }

                  echo "<td ><font class='red'>".$statusstr ."</font></td>" ;
              }
              ?>
            <td ><?php echo $myrow["LoginTime"]?></td>
            <td ><?php echo $myrow["OnlineTime"]?></td>
            <td ><font color="#ff0000"><?php echo $myrow["Url"]?></font></td>
              <?php
              $ipArea = $reader->get($myrow["LoginIP"]);
              $aIpArea = explode('|',$ipArea);
              $aIpArea = array_slice($aIpArea,0,6);
              $sIpArea = implode('|',$aIpArea);
              ?>
            <td ><?php echo $myrow["LoginIP"]?>&nbsp;|&nbsp;<?php echo $sIpArea ;?></td>
          </tr>
<?php
$i++;
}
while ($myrow=mysqli_fetch_array($result));
}else{
    	echo "<tr height=20><td colspan=12 ><div align=center>无人在线</div></td></tr>";
}
?>
      </table>
    </td>
  </tr>
</table>
</div>

</form>

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
