<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include "../include/address.mem.php";
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
$lv=$_REQUEST["lv"];

require ("../include/traditional.$langx.inc.php");

$date_start=$_REQUEST['date_start'];
$agents_id=$_REQUEST['agents_id'];
$uid=$_REQUEST['uid'];
$name=$_REQUEST['name'];
$active=$_REQUEST['active'];
$page=$_REQUEST["page"];
$search=$_REQUEST["search"];
$seconds=$_REQUEST["seconds"];

$datetime=date("Y-m-d H:i:s",time()-10*86400);//10天数
$sql = "delete from ".DBPREFIX."web_mem_log_data where LoginTime <'$datetime'";
mysqli_query($dbMasterLink,$sql);

if ($active==1){
	$sql = "update ".DBPREFIX."web_agents_data set Oid='logout',Online=0,LogoutTime=now() where UserName='$name'";
	mysqli_query($dbMasterLink,$sql);
    $agent_sql = "select ID,UserName from ".DBPREFIX."web_agents_data where UserName='$name'";
    $agent_query = mysqli_query($dbLink,$agent_sql);
    $agentinfo = mysqli_fetch_assoc($agent_query);
	$sql = "update ".DBPREFIX."web_system_data set Oid='logout',Online=0,LogoutTime=now() where UserName='$name'";
	mysqli_query($dbMasterLink,$sql);
    $admin_sql = "select ID,UserName from ".DBPREFIX."web_system_data where UserName='$name'";
    $admin_query = mysqli_query($dbLink,$admin_sql);
    $admininfo = mysqli_fetch_assoc($admin_query);
//	$sql = "delete from ".DBPREFIX."web_mem_log_data where UserName='$name'";
//	mysqli_query($dbMasterLink,$sql);
    // 清除管理员redis，便于后续判断会员登录标识
    $redisObj = new Ciredis();
    $agentOId = $redisObj->getSimpleOne('loginadmin_'.$agentinfo['ID']);
    if($agentOId){
        $redisObj->delete('loginadmin_'.$agentinfo['ID']);
    }
    $adminOId = $redisObj->getSimpleOne('loginadmin_'.$admininfo['ID']);
    if($adminOId){
        $redisObj->delete('loginadmin_'.$admininfo['ID']);
    }
}
if ($seconds==''){
	$seconds=180;
}
if ($date_start=='') {
	$date_start=date('Y-m-d');
}
if ($page==''){
	$page=0;
}
if ($search!=''){
    $search="and (UserName like '%$search%' or LoginTime like '%$search%' or Level like '%$search%')";
}
$parents_id=$_REQUEST['parents_id'];

if ($parents_id==''){
    $sql = "select * from ".DBPREFIX."web_mem_log_data where LoginTime like '%$date_start%' $search group by UserName order by ID desc";
}else{
    $sql = "select * from ".DBPREFIX."web_mem_log_data where LoginTime like '%$date_start%' and UserName='$parents_id' group by UserName order by ID desc";
}
$result = mysqli_query($dbLink,$sql);
$cou=mysqli_num_rows($result);
$page_size=20;
$page_count=ceil($cou/$page_size);
$offset=$page*$page_size;
$mysql=$sql."  limit $offset,$page_size;";
// echo $mysql;
$result = mysqli_query($dbLink,$mysql);
$cou=mysqli_num_rows($result);
if ($cou==0){
	$page_count=1;
}

$ipdatabase = '../include/ip.dat';
$reader = new IpSearch($ipdatabase);
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">

</head>
<body onLoad="onLoad();auto_refresh()">
<form name="myFORM" action="" method=POST>
    <dl class="main-nav">
        <dt>系统日志</dt>
        <dd>
        <table >
    <tr>
      <td class="m_tline" width="140">&nbsp;线上数据－<font color="#CC0000">日志</font></td>
      <td width="225" class="m_tline">
      <select class="za_select za_select_auto" onChange="document.myFORM.submit();" id="seconds" name="seconds">
        <option value="10">10秒</option>
        <option value="30">30秒</option>
        <option value="60">60秒</option>
        <option value="90">90秒</option>
        <option value="120">120秒</option>
        <option value="180">180秒</option>
      </select>&nbsp;&nbsp;&nbsp;<span id="ShowTime"></span></td>         
      <td class="m_tline" width="35">日期:</td>
      <td class="m_tline" width="100">

        <input class="za_text_auto" id="date_start" name="date_start" value="<?php echo $date_start;?>" onclick="laydate({istime: true,format: 'YYYY-MM-DD',choose:checkDate})"  readonly/>
      </td>
      <td class="m_tline" width="35">帐户:</td>
      <td class="m_tline" width="146">
	  <select name="parents_id" onChange="self.myFORM.submit()" class="za_select za_select_auto">
              <option label="全部" value="">全部</option>
			  <?php
	          $mysql = "select UserName,Level from ".DBPREFIX."web_mem_log_data where Level!='' and LoginTime like '%$date_start%' and UserName!='dan555' and UserName!='dan222' group by UserName order by ID desc";
	          $aresult = mysqli_query($dbLink,$mysql);
				while ($arow = mysqli_fetch_assoc($aresult)){
					if ($parents_id==$arow['UserName']){
						echo "<option value=".$arow['UserName']." selected>".$arow['UserName']."===".$arow['Level']."</option>";				
						$sel_agents=$arow['UserName'];
					}else{
						echo "<option value=".$arow['UserName'].">".$arow['UserName']."===".$arow['Level']."</option>";
					}
				}
				?>
      </select></td>
      <td class="m_tline"><input type="hidden" name="search" value=""></td>
      <td class="m_tline" width="75">
	    <input type=BUTTON name="btn_search" value="本页查询" onClick="showSearchDlg();" class="za_button" />
      </td>
        <td class="m_tline" width="75">
            <input type=BUTTON name="admin_btn_search" value="管理员操作查询" class="za_button" onclick="window.location.href='showlog.php?uid=<?php echo $uid?>&name=&langx=<?php echo $langx?>'" />
        </td>
      <td class="m_tline" width="55">总页数:</td>
      <td class="m_tline" width="40">
	  <select id="page" name="page" onChange="self.myFORM.submit()" class="za_select za_select_auto">
              <?php
		      for($i=0;$i<$page_count;$i++){
			      echo "<option value='$i'>".($i+1)."</option>";
		         }
		      ?>
      </select></td>
      <td class="m_tline" width="64"> / <?php echo $page_count?>  页</td>

    </tr> 
</table>
 </dd>
</dl>
<div class="main-ui">
    <table id="glist_table"  class="m_tab" >
      <tr class="m_title">
        <td width="50">序 号</td>
        <td width="70">帐 号</td>
        <td width="70">级 别</td>
        <td width="140">登陆时间</td>
        <td width="247">登陆网址</td>
        <td width="290">登陆IP和地区</td>
        <td width="100">操 作</td>
      </tr>
    <?php
    $i=1;
    if ($row = mysqli_fetch_array($result)){
    do
    {
    ?>
      <tr class="m_cen" onmouseover=sbar(this) onmouseout=cbar(this)>
        <td width="50"><?php echo $i?></td>
        <td width="70"><?php echo $row["UserName"]?></td>
        <td width="70"><?php echo $row['Level']?></td>
        <td width="140"><font color="#CC0000"><?php echo $row["LoginTime"]?></font></td>
        <td width="247" align="center"><?php echo $row["Url"]?></td>
          <?php
          $ipArea = $reader->get($row["LoginIP"]);
          $aIpArea = explode('|',$ipArea);
          $aIpArea = array_slice($aIpArea,0,6);
          $sIpArea = implode('|',$aIpArea);
          ?>
        <td><?php echo $row["LoginIP"]?>&nbsp;|&nbsp;<?php echo $sIpArea ;?></td>
        <td width="100"><div align="center">
        <a class="a_link" href="showlog.php?uid=<?php echo $uid?>&name=<?php echo $row["UserName"]?>&langx=<?php echo $langx?>&date_start=<?php echo $date_start?>">查看</a>&nbsp;/
        <a class="a_link" href="syslog.php?uid=<?php echo $uid?>&active=1&name=<?php echo $row["UserName"]?>&level=<?php echo $row["Level"]?>&langx=<?php echo $langx?>">&nbsp;踢线</a>
        </div></td>
      </tr>
    <?php
    $i++;
    }
    while ($row=mysqli_fetch_array($result));
    }else{
            echo "<tr height=20><td colspan=9 ><font color=#FFFFFF><div align=center>现在没有代理在线</div></font></td></tr>";
    }
    ?>
    </table>
</div>
</form>

<!--快速查询跳出視窗-->
    <div id="searchDlg" class="line_type_width" style="display: none;position: absolute;">
    <table class="list-tab">
      <tr>
         <td colspan="2">快速查询<span id="eo_title"></span>
             <a class="close_window" onClick="closeSearchDlg();"><img src="/images/agents/top/edit_dot.gif" width="16" height="14"></a>
         </td>
      </tr>
 	   <tr>
          <td>查询条件</td>
          <td>
            <select name="dlg_option" class="za_select za_select_auto">
                <option label="管理员级别" value="ALIAS">管理员级别</option>
                <option label="管理员帐号" value="USERNAME" selected="selected">管理员帐号</option>
                <option label="登陆日期" value="NEW_DATE">登陆日期</option>

            </select>
          </td>
        </tr>
        <tr >
        <td>关键字</td>
          <td>
            <input type=text id="dlg_text" value="" class="za_text za_text_auto" maxlength="20">
          </td>
        </tr>
        <tr>
          <td align="center" colspan="2">
            <input type="submit" id="dlg_ok" value="查询" class="za_button" onClick="submitSearchDlg();">
          </td>
        </tr>
    </table>
</div>
<script type="text/javascript" src="../../../js/agents/register/laydate.min.js"></script>
<script type="text/javascript" src="../../../js/agents/common.js?v=<?php echo AUTOVER; ?>" ></script>
<script type="text/javascript" src="../../../js/agents/user_search.js?v=<?php echo AUTOVER; ?>" ></script>
<script>
    function checkDate() {
        document.myFORM.submit();
    }
    function OpenIP(url) {
        window.open(url,'IP','width=300,height=200');
    }
    function onLoad() {
        var obj_seconds = document.getElementById('seconds');
        obj_seconds.value = '<?php echo $seconds?>';
        var obj_date_start = document.getElementById('date_start');
        obj_date_start.value = '<?php echo $date_start?>';
        var obj_page = document.getElementById('page');
        obj_page.value = '<?php echo $page?>';
    }

    var second="<?php echo $seconds?>"
    function auto_refresh(){
        if (second==1){
            window.location.href='syslog.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&lv=<?php echo $lv?>&date_start=<?php echo $date_start?>&parents_id=<?php echo $parents_id?>&seconds=<?php echo $seconds?>&page=<?php echo $page?>'; //刷新页面
        }else{
            second-=1
            curmin=Math.floor(second)
            curtime=curmin+"秒自动更新"
            ShowTime.innerText=curtime
            setTimeout("auto_refresh()",1000)
        }
    }
</script>

</body>
</html>