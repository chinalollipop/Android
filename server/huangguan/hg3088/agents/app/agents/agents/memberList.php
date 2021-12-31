<?php

$path = pathinfo(__FILE__);

session_save_path($path['dirname']."/../../");
session_start();
?>


<?php
require_once ("../../member/include/config.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$uid=$_REQUEST["uid"];
$sql = "select id,level,agname from web_sytnet where uid='$uid' and status=1";
$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_array($result);
$cou=mysqli_num_rows($result);

	$agname=$row['Agname'];
	$agid=$row['ID'];
	$langx='zh-tw';
	require ("../../member/include/traditional.$langx.inc.php");
	$enable=$_REQUEST["enable"];
	$enabled=$_REQUEST["enabled"];
	$sort=$_REQUEST["sort"];
	$orderby=$_REQUEST["orderby"];
	$mid=$_REQUEST["id"];
	$sel_agents=$_REQUEST['super_agents_id'];
	$page=$_REQUEST["page"];
	$memname=$_REQUEST['memname'];
	if ($page==''){
		$page=0;
	}
	$active=$_REQUEST["active"];
	if ($enable==""){
		$enable='Y';
	}
	
	if ($sort==""){
		$sort='web_member.adddate';
	}
	
	if ($orderby==""){
		$orderby='desc';
	}
	
	switch($enable){
	case "Y":
		$enabled=1;
		$memstop='N';
		$stop=1;
		$start_font="";
		$end_font="";
		$caption1=$mem_disable;
		$caption2=$mem_enable;
		break;
	case "N":
		//$enable='N';
		$memstop='Y';
		$enabled=0;
		$stop=0;
		//$start_font="<font color=#999999>";
		$start_font="";
		$end_font="</font>";
		$caption2="<SPAN STYLE='background-color: rgb(255,0,0);'>$mem_disable</SPAN>";
		$caption1=$mem_enable;
		break;
	default:
		//$enable='S';
		$memstop='Y';
		$enabled=2;
		$stop=2;
		$start_font="";
		$end_font="</font>";
		$caption2="<SPAN STYLE='background-color: rgb(0,255,0);'>暫停</SPAN>";
		$caption1=$mem_enable;
		break;
	}
	switch ($active){
	case 2:
		$mysql="update web_member set oid='',Status=$stop where id=$mid";
		mysqli_query( $dbMasterLink,$mysql);
	
		$mysql="select agents from web_member where ID=$mid";
		$result = mysqli_query( $dbLink,$mysql);
		$row = mysqli_fetch_array($result);
		$a11=$row['agents'];
		if ($stop==0){
			$mysql="update web_agents set mcount=mcount-1 where agname='$a11'";
		}else{
			$mysql="update web_agents set mcount=mcount+1 where agname='$a11'";
		}
		mysqli_query( $dbMasterLink,$mysql);
		break;
	case 3:
		$mysql="select memname as agname from web_member where ID=$mid";
		$result = mysqli_query( $dbLink,$mysql);
		$row = mysqli_fetch_array($result);
		$agent_name=$row['agname'];
	
		$sql="delete from web_member where id=$mid";
		mysqli_query(  $dbMasterLink,$sql);
	
		//$mysql="delete from web_db_io where m_name='".$agent_name."'";
		///mysqli_query( $mysql);
	
		break;
	case 8:
		$mysql="update web_member set oid='',Status=$stop where ID=$mid";
		mysqli_query( $dbMasterLink,$mysql);
	}
	
	?>
	<html>
	<head>
	<title>main</title>
	<meta http-equiv="Content-Type" content="text/html; charset=big5">
	<style type="text/css">
	<!--
	.m_title {  background-color: #FEF5B5; text-align: center}
	-->
	</style>
	<link rel="stylesheet" href="/style/control/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
	<SCRIPT language="javascript" src="/js/member.js?v=<?php echo AUTOVER; ?>"></script>
	<SCRIPT>
	
	 function onLoad()
	 {
	  var obj_sagent_id = document.getElementById('agent_id');
	  obj_sagent_id.value = '<?php echo $super_agents_id?>';
	  var obj_enable = document.getElementById('enable');
	  obj_enable.value = '<?php echo $enable?>';
	  var obj_page = document.getElementById('page');
	  obj_page.value = '<?php echo $page?>';
	  //var obj_sort=document.getElementById('sort');
	  //obj_sort.value='<?php echo $sort?>';
	  //var obj_orderby=document.getElementById('orderby');
	  //obj_orderby.value='<?php echo $orderby?>';
	 }
	// -->
	</SCRIPT>
	</head>
	<body oncontextmenu="window.event.returnValue=false" bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0" vlink="#0000FF" alink="#0000FF" onLoad="onLoad()";>
	<FORM NAME="myFORM" id="myFORM" ACTION="./memberList.php?uid=<?php echo $uid?>" METHOD=POST>
	<input type="hidden" name="agent_id" value="<?php echo $agid?>">
	<?php
	if(strlen($memname)>0)
	{
		$where=" and web_member.Memname like '%".$memname."%'";
	}else{
		$where="";
	}
	
	if ($sel_agents==''){
		$sql = "select ID,Memname,passwd,Alias,Money,ratio,date_format(AddDate,'%m-%d / %H:%i') as AddDate,pay_type,Agents,OpenType,MEMBER_TEL from web_member where MEMBER_ISSUE!='' and Status=$enabled ".$where."    order by ".$sort." ".$orderby;
	}else{
		$sql = "select ID,Memname,passwd,Alias,Money,ratio,date_format(AddDate,'%m-%d / %H:%i') as AddDate,pay_type,Agents,OpenType,MEMBER_TEL from web_member where MEMBER_ISSUE!='' and Status=$enabled  and Agents='$sel_agents' ".$where."  order by ".$sort." ".$orderby;
	}
	
	$result = mysqli_query(  $dbLink,$sql);
	$cou=mysqli_num_rows($result);
	$result = mysqli_query ( $dbLink, $sql);
	$cou=mysqli_num_rows($result);
	$page_size=30;
	$page_count=ceil($cou/$page_size);
	$offset=$page*$page_size;
	$mysql=$sql."  limit $offset,$page_size;";
	//echo $mysql;
	//exit;
	$result = mysqli_query($dbLink,$mysql);
	if ($cou==0){
		$page_count=1;
	}
	?>
	<table width="880" border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td class="m_tline">
	        <table border="0" cellspacing="0" cellpadding="0" >
	          <tr>
	            <td width="60">選擇代理商</td>
	            <td>
				<select class=za_select id=super_agents_id onchange="this.form.submit();" name=super_agents_id style="width:70px">
					<option value="" selected><?php echo $rep_pay_type_all?></option>
					<?php
					$mysql="select ID,Agname from web_agents where Status<>0 and subuser=0 ";
					$ag_result = mysqli_query( $dbLink, $mysql);
					while ($ag_row = mysqli_fetch_array($ag_result)){
						if ($sel_agents==$ag_row['Agname']){
							echo "<option value=".$ag_row['Agname']." selected>".$ag_row['Agname']."</option>";
							//$sel_agents=$ag_row['Agname'];
						}else{
							echo "<option value=".$ag_row['Agname'].">".$ag_row['Agname']."</option>";
	
						}
					}
					?>
				</select>
	
								<select id="enable" name="enable" onChange="self.myFORM.submit()" class="za_select">
									<option value="Y">啟用</option>
									<option value="N">停用</option>
									<option value="S">暫停</option>
	            </select>
	            </td>
	            <td width="90"> -- 以會員名查詢</td>
	            <td>
	              <input type="text" name="memname" id="memname" style="width:70px;" >
	              <input type=submit name="submit" value="查詢" class="za_button">
	            </td>
	            <td width="52"> -- <?php echo $mem_pages?></td>
	            <td>
	              <select id="page" name="page" onChange="self.myFORM.submit()" class="za_select">
			<?php
			for($i=0;$i<$page_count;$i++){
				echo "<option value='$i'>".($i+1)."</option>";
			}
			?>
	
	              </select>
	            </td>
	            <td> / <?php echo $page_count?> <?php echo $mem_page?></td>
	                        <td>
	              <input type=BUTTON name="append" value="<?php echo $mem_add?>" onClick="document.location='./ag_mem_add.php?uid=<?php echo $uid?>'" class="za_button">
	              <input type=BUTTON name="notice_send" value="發送短信" onClick="document.location='./notice_send.php?uid=<?php echo $uid?>'" class="za_button">
	              <input type=BUTTON name="notice_send" value="注冊公告" onClick="document.location='./notice_config.php?uid=<?php echo $uid?>'" class="za_button">
	              <input type=BUTTON name="notice_del" value="清空公告名單" onClick="document.location='./notice_send.php?uid=<?php echo $uid?>&act=delall'" class="za_button">
	            </td>
	          </tr>
	        </table>
		</td>
	    <td width="30"><img src="/images/control/zh-tw/top_04.gif" width="30" height="24"></td>
	</tr>
	<tr>
		<td colspan="2" height="4"></td>
	</tr>
	</table>
	<?php
	
	if ($cou==0){
	?>
	  <table width="840" border="0" cellspacing="1" cellpadding="0"  bgcolor="E3D46E" class="m_tab">
	    <tr class="m_title">
	      <td height="30" ><?php echo $mem_nomem?></td>
	    </tr>
	  </table>
	<?php
	}else{
	 ?>
	  <script src="../../js/jquery-1.3.2.js"></script>
	  <table width="880" border="0" cellspacing="1" cellpadding="0"  bgcolor="E3D46E" class="m_tab">
	    <tr class="m_title">
	      <td width="100" ><input name="checkall_top" id="checkall_top" type="checkbox" value="" onClick="$('input[id^=check_]').each(function (){var idd = this.id.split('_');this.checked = $('#checkall_top').attr('checked');var actt = '';if(this.checked)  actt= 'add';else actt='del';$.get('../ajax.php',{uid:idd[1],act:actt},function (){},'text');});"></td>
	      <td width="100" ><?php echo $mem_agents?></td>
	      <td width="76"><?php echo $mem_name?></td>
	      <td width="94"><?php echo $mem_uid?></td>
	      <td width="86">密碼</td>
		  <td width="91"><?php echo $mem_credit?></td>
		  <td width="91">手機</td>
		  <td width="60"><?php echo $mem_otypes?></td>
	      <td width="89"><?php echo $mem_adddate?></td>
	      <td width="59"><?php echo $mem_status?></td>
	      <td width="285"><?php echo $mem_option?></td>
	      <!--<td width="70">押碼跳動</td>-->
	    </tr>
	<?php
		while ($row = mysqli_fetch_array($result)){
		?> <tr class="m_cen">
		  <td align="center"><input name="checkall_top" id="check_<?php echo $row['ID']?>" type="checkbox" value="" onClick="var idd = this.id.split('_');var actt = '';if(this.checked)  actt= 'add';else actt='del';$.get('../ajax.php',{uid:idd[1],act:actt},function (){},'text');"
		  <?php
		   if(!empty($_SESSION['notice_idstic'])) {
		   	if(in_array($row['ID'],$_SESSION['notice_idstic'])) {
		   		echo " checked ";
		   	}
		   }
		  ?>
		  ></td>
	      <td><?php echo $start_font?><?php echo $row['Agents'];?><?php echo $end_font?></td>
	      <td><?php echo $start_font?><?php echo iconv('gb2312','big5',$row['Alias'])?><?php echo $end_font?></td>
	      <td><?php echo $start_font?><?php echo $row['Memname'];?><?php echo $end_font?></td>
	      <td align="center"><font color=#cc00000><?php echo $row['passwd']?></font></td>
			  <td align="right">
	      <p align="right"><?php echo $start_font?><?php echo $row['Money'];?><?php echo $end_font?></td>
	       <td><?php echo $start_font?><?php echo $row['MEMBER_TEL'];?><?php echo $end_font?></td>
	      <td><?php echo $start_font?><?php echo $row['OpenType']?><?php echo $end_font?></td>
			  <td><?php echo $row['AddDate'];?></td>
		  	<td><?php echo $caption2?></td>
	      <td align="left"><font color="#0000FF"><a style="cursor: hand">
			&nbsp;&nbsp;
			<?php
			if($enable=='Y'){
			?>
				<a href="javascript:CheckSTOP('./memberList.php?uid=<?php echo $uid?>&active=8&id=<?php echo $row['ID']?>&enable=S','S')">暫停</a> /
			<?php
			}
			?>
	 		&nbsp; <a href="javascript:CheckSTOP('./memberList.php?uid=<?php echo $uid?>&active=2&id=<?php echo $row['ID']?>&enable=<?php echo $memstop?>','<?php echo $memstop?>')"><?php echo $caption1?></a> / <a href="./memberModify.php?uid=<?php echo $uid?>&mid=<?php echo $row['ID']?>&username=<?php echo $row['Memname'];?>">修改資料</a>/
                     <!-- <a href="ag_mem_set.php?uid=<?php /*echo $uid*/?>&pay_type=0&mid=<?php /*echo $row['ID']*/?>&aid=<?php /*echo $row['Agents']*/?>&username=<?php /*echo $row['Memname'];*/?>">詳細設定</a> /-->
	    &nbsp; <a href="javascript:CheckDEL('./memberList.php?uid=<?php echo $uid?>&active=3&id=<?php echo $row['ID']?>')"><?php echo $mem_delete?></a></td>
	    </tr>
	<?php
	}
	}

?>
</table>
</form>
</body>
</html>
