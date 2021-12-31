<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include ("../../agents/include/address.mem.php");
// echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
require_once ("../../agents/include/config.inc.php");
require ("../../agents/include/define_function_list.inc.php");


checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$uid=$_REQUEST["uid"];
$langx=$_SESSION["langx"];
$loginname=$_SESSION['UserName'];
$lv=$_REQUEST["lv"];
require ("../../agents/include/traditional.$langx.inc.php");


if($_SESSION['Level'] == 'M') {
	$web=DBPREFIX.'web_system_data';
}else{
	$web=DBPREFIX.'web_agents_data';
}
$sql = "select ID,Level,CurType,UserName,Admin from $web where Oid='$uid' and UserName='$loginname'";
$result = mysqli_query($dbLink,$sql);
$cou=mysqli_num_rows($result);
$row = mysqli_fetch_assoc($result);
$id=$row['ID'];
$passw=$row['Level'];
$curtype=$row['CurType'];
$name=$row['UserName'];
$admin=$row['Admin'];
switch ($lv){
case 'A':
    $Title=$Mem_Super;
	$Caption=$Mem_Manager;
	$level='M';
	$data=DBPREFIX.'web_system_data';
	$agents="UserName='$name'";
	break;
case 'B':
    $Title=$Mem_Corprator;
	$Caption=$Mem_Super;
	$level='A';
	$data=DBPREFIX.'web_agents_data';
	$agents="(UserName='$name' or Admin='$name' or Super='$name' or Corprator='$name' or World='$name') and subuser=0";
	break;
case 'C':
    $Title=$Mem_World;
	$Caption=$Mem_Corprator;
	$level='B';
	$data=DBPREFIX.'web_agents_data';
	$agents="(UserName='$name' or Admin='$name' or Super='$name' or Corprator='$name' or World='$name') and subuser=0";
	break;
case 'D':
    $Title=$Mem_Agents;
	$Caption=$Mem_World;
	$level='C';
	$data=DBPREFIX.'web_agents_data';
	$agents="(UserName='$name' or Admin='$name' or Super='$name' or Corprator='$name' or World='$name') and subuser=0";
	break;
}
$loginfo='新增'.$Title.'';
$parents_id=$_REQUEST['parents_id'];
if ($parents_id!=''){
$loginfo='选择'.$Title.'上线'.$Caption.':'.$parents_id.'';
$sql = "select * from $data where UserName='$parents_id'";
$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);

$Winloss_A=$row['A_Point'];
$Winloss_B=$row['B_Point'];
$Winloss_C=$row['C_Point'];
$Winloss_D=$row['D_Point'];
/*
$abcd=100-$Winloss_D-$Winloss_C-$Winloss_B;
if ($abcd>80){
	$abcd=80;
}
*/
if ($lv=='B'){
	$abcd=100;
}else if($lv=='D'){
	$abcd=80;
}
$sports=$row['Sports'];
$lottery=$row['Lottery'];
$world=$row['World'];
$corprator=$row['Corprator'];
$super=$row['Super'];
$admin=$row['Admin'];
$linetype=$row['LineType'];

}

switch ($lv){
case 'A':
	$add='a';
	$user="Level='A' and Admin='$parents_id'";
	$competence='0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,1,0,1,1,1,1,1,1,1,';
	$agent="World='$world',Corprator='$corprator',Super='$super',Admin='$parents_id'";
	break;
case 'B':
	$add='b';
	$competence='0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,1,0,0,1,1,1,1,1,1,';
	$user="Level='B' and Super='$parents_id'";
	$agent="World='$world',Corprator='$corprator',Super='$parents_id',Admin='$admin'";
	break;
case 'C':
	$add='c';
	$competence='0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,1,0,0,0,1,1,1,0,1,';
	$user="Level='C' and Corprator='$parents_id'";
	$agent="World='$world',Corprator='$parents_id',Super='$super',Admin='$admin'";
	break;
case 'D':
	$add='d';
	$competence='0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,1,0,0,0,0,1,1,0,1,';
	$user="Level='D' and World='$parents_id'";
	$agent="World='$parents_id',Corprator='$corprator',Super='$super',Admin='$admin'";
	break;
}

$keys=$_REQUEST['keys'];
if ($keys=='add'){
	$AddDate=date('Y-m-d H:i:s');//新增日期
	$username=$_REQUEST['username'];//帐号
	$password=$_REQUEST['password'];//密码
	$maxcredit=$_REQUEST['maxcredit'];//总信用额度
	$wager=$_REQUEST['wager'];// 即时注单
	$CurType=$_REQUEST['CurType'];//币别
	$alias=$_REQUEST['alias'];//名称
	$usedate=$_REQUEST['usedate'];
	if ($lv=='B'){
	    $winloss_b=$_REQUEST['winloss_b'];
	}else if($lv=='D'){	
        $winloss_d=$_REQUEST['winloss_d'];
	    $winloss_c=$_REQUEST['winloss_c'];
	    $winloss_b=$Winloss_B-$winloss_d-$winloss_c;
	    $winloss_a=100-$winloss_d-$winloss_c-$winloss_b;
	}		
	$parents_id=$_REQUEST['parents_id'];
	if ($parents_id==''){
		echo wterror("帐号名称不能为空，请回上一面重新输入");
		exit();
	}
	
$mysql="select Credit from $data where UserName='$parents_id'";
$result = mysqli_query($dbLink,$mysql);
$row = mysqli_fetch_assoc($result);
$credit=$row['Credit'];
$points=$row['Points'];
$mysql="select sum(Credit) as credit from ".DBPREFIX."web_agents_data where $user";
$result = mysqli_query($dbLink,$mysql);	
$row = mysqli_fetch_assoc($result);

	if ($row['credit']+$maxcredit>$credit){
		echo wterror("目前代理商 最大信用额度为".number_format($maxcredit,0)."<br>目前总代理商 最大信用额度为".number_format($credit,0)."<br>,所属代理商累计信用额度为".number_format($row[credit],0)."<br>已超过总代理商信用额度或储蓄点数，请回上一面重新输入！");
		$loginfo='新增'.$Title.'失败';
		$ip_addr = get_ip();
$mysql="insert into ".DBPREFIX."web_mem_log_data(UserName,Logintime,ConText,Loginip,Url) values('$name',now(),'$loginfo','$ip_addr','".BROWSER_IP."')";
		mysqli_query($dbMasterLink,$mysql);
		exit();
	}
	
$mysql="select * from ".DBPREFIX."web_agents_data where UserName='$username'";
$result = mysqli_query($dbLink,$mysql);
$count=mysqli_num_rows($result);
    if ($count>0){
		echo wterror("您输入的帐号 $username 已经有人使用了，请回上一页重新输入");
    }else{
$sql="insert into ".DBPREFIX."web_agents_data set ";
$sql.="Level='".$lv."',";
$sql.="UserName='".$username."',";
$sql.="PassWord='".$password."',";
$sql.="Credit='".$maxcredit."',";
$sql.="Alias='".$alias."',";
$sql.="AddDate='".$AddDate."',";
$sql.="Status='0',";
$sql.="regSource='2',";
//$sql.="CurType='".$curtype."',";
$sql.="LineType='".$linetype."',";
$sql.="wager='".$wager."',";
$sql.="Competence='".$competence."',";
$sql.="UseDate='".$usedate."',";
if($lv=='C'){
   $sql.="A_Point='".$Winloss_A."',";
   $sql.="B_Point='".$Winloss_B."',";
   $sql.="C_Point='".$Winloss_C."',";
   $sql.="D_Point='".$Winloss_D."',";
}else{
   $sql.="A_Point='".$winloss_a."',";
   $sql.="B_Point='".$winloss_b."',";
   $sql.="C_Point='".$winloss_c."',";
   $sql.="D_Point='".$winloss_d."',";
}
$sql.="$agent,";

mysqli_query($dbMasterLink,$sql) or die ("操作失败!!!");
$loginfo='新增'.$Title.':'.$username.' 密码:'.$password.' 名称:'.$alias.' 信用额度:'.$maxcredit.' 儲值點數:'.$maxpoints.' 上线'.$Caption.':'.$parents_id.'';
$mysql="update ".DBPREFIX."web_agents_data set Count=Count+1 where UserName='$parents_id'";
mysqli_query($dbMasterLink,$mysql) or die ("操作失败!!");
echo "<script languag='JavaScript'>self.location='user_browse.php?uid=$uid&lv=$lv&langx=$langx'</script>";	
}	
}else{
$ssql="select sum(credit) as credit,sum(Points) as Points from ".DBPREFIX."web_agents_data where Status=0 and $user  ";
$sresult = mysqli_query($dbLink,$ssql);
$srow = mysqli_fetch_assoc($sresult);
$esql="select sum(credit) as credit,sum(Points) as Points from ".DBPREFIX."web_agents_data where Status>0 and $user ";
$eresult = mysqli_query($dbLink,$esql);
$erow = mysqli_fetch_assoc($eresult);
?>
<html>
<head>
<title>main</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="/style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
<style type="text/css">

.m_tline {  background-image:    url(/images/agents/top/top_03b.gif)}
.m_co_ed {  background-color: #D3C9CB; text-align: right}

</style>
<link rel="stylesheet" href="/tpl/style/agents/autocomplete.css?v=<?php echo AUTOVER; ?>" type="text/css">
<script src="/js/lib/prototype.js" type="text/javascript"></script>
<script src="/js/lib/scriptaculous.js" type="text/javascript"></script>
<SCRIPT>
<!--
function LoadBody(){
document.all.num_1.selectedIndex=document.all.num_1[0];
document.all.num_2.selectedIndex=document.all.num_2[0];
document.all.num_3.selectedIndex=document.all.num_3[0];
}
function SubChk(){
    if(document.all.parents_id.value==''){ 
	document.all.parents_id.focus(); alert("<?php echo $Mem_Input?>:<?php echo $Caption?><?php echo $Mem_Account?>!!"); return false; }
    if(document.all.num_1.value==''){ 
	document.all.num_1.focus(); alert("<?php echo $Mem_alert0?>!!"); return false; }
    if(document.all.num_2.value==''){ 
	document.all.num_2.focus(); alert("<?php echo $Mem_alert0?>!!"); return false; }
    if(document.all.num_3.value==''){ 
	document.all.num_3.focus(); alert("<?php echo $Mem_alert0?>!!"); return false; }
	if(document.all.password.value=='' ){ 
	document.all.password.focus(); alert("<?php echo $Mem_Input?> :<?php echo $Mem_Password?> !!"); return false; }
	if(document.all.password.value.length < 6 ){alert('<?php echo $Mem_NewPassword_6_Characters?>');return false;}
	if(document.all.password.value.length > 12 ){alert('<?php echo $Mem_NewPassword_12_CharactersMax?>');return false;}
	var Numflag = 0;
	var Letterflag = 0;
    var pwd = document.all.password.value;
	for (idx = 0; idx < pwd.length; idx++) {
		//====== 密碼只可使用字母(不分大小寫)與數字
		if(!((pwd.charAt(idx)>= "a" && pwd.charAt(idx) <= "z") || (pwd.charAt(idx)>= 'A' && pwd.charAt(idx) <= 'Z') || (pwd.charAt(idx)>= '0' && pwd.charAt(idx) <= '9'))){
			alert("<?php echo $Mem_PasswordEnglishNumber_6_Characters_12_CharactersMax?>");
			return false;
		}
		if ((pwd.charAt(idx)>= "a" && pwd.charAt(idx) <= "z") || (pwd.charAt(idx)>= 'A' && pwd.charAt(idx) <= 'Z')){
			Letterflag++;
		}
		if ((pwd.charAt(idx)>= "0" && pwd.charAt(idx) <= "9")){
			Numflag++;
		}
	}
		var msg = "";
	if (Numflag == 0 || Letterflag == 0) { alert('<?php echo $Mem_PasswordEnglishNumber?>');return false;
	} else if (Letterflag >= 1 && Letterflag <= 3) {
		msg = "1";
	} else if (Letterflag >= 4 && Letterflag <= 8) {
		msg = "2";
	} else if (Letterflag >= 9 && Letterflag <= 11) {
		msg = "3";
	} else {
		return false;
	}
	if(document.all.passwd.value==''){ 
	document.all.passwd.focus(); alert("<?php echo $Mem_Input?> :<?php echo $Mem_Cofirm_Password?> !!"); return false; }
	if(document.all.password.value != document.all.passwd.value){ 
	document.all.password.focus(); alert("<?php echo $Mem_PasswordConfirmError?> !!"); return false; }
	if(document.all.alias.value==''){ 
	document.all.alias.focus(); alert("<?php echo $Mem_Input?> :<?php echo $Mem_Name?> !!"); return false; }
	if(document.all.maxcredit.value=='' || document.all.maxcredit.value=='0'){ 
	document.all.maxcredit.focus(); alert("<?php echo $Mem_Input?> :<?php echo $Mem_Credit_Amount?> !!"); return false; }
<?php
if($lv=='D'){
?>
	if(document.all.winloss_d.value=='' ){ 
	document.all.winloss_d.focus(); alert("<?php echo $wld_percent3?>"); return false; } 
	var winloss_d,winloss_c;
	winloss_c=eval(document.all.winloss_c.value);
	winloss_d=eval(document.all.winloss_d.value); 
	if ((winloss_c+winloss_d)>80){
       alert("<?php echo $Mem_alert14?>");
       document.all.winloss_d.focus();
       return false;
	}
	if ((winloss_c+winloss_d)<50){
       alert("<?php echo $Mem_alert14?>");
       document.all.winloss_d.focus();
       return false;
	}
<?php
}
?>
    document.all.keys.value = 'add';
	document.all.OK.disabled = true;
	document.all.FormsButton2.disabled = true;
	//document.myFORM.submit();
	if(!confirm("<?php echo $Mem_Whether_Write?> <?php echo $Title?> ?")){
		document.all.OK.disabled = false;
		document.all.FormsButton2.disabled = false;
		return false;
	}
	 document.all.username.value = document.all.user_count.innerHTML;
}

function CheckKey(){
	if(event.keyCode < 48 || event.keyCode > 57){alert("<?php echo $Mem_Enter_Numbers?>"); return false;}
}

function get_name(selectvalue)
{
	str=selectvalue.split("==",2);
	strtmp=str[0].substring(1,3);
	var user_count=document.all.user_count;
	user_count.innerHTML='<?php echo $add?>'+strtmp;
	}

function parents_reload(parents_id)
{
		self.location='user_add.php?uid=<?php echo $uid?>&lv=<?php echo $lv?>&langx=<?php echo $langx?>&action=browse_add&parents_id='+parents_id;
}
function ChkMem(){
	username=document.all.user_count.innerHTML;
	//strtmp=username.substring(0,3);
	//if(username==strtmp){
	if(username.length<5){
		document.all.user_count.focus(); alert("<?php echo $Mem_Input?> :<?php echo $Mem_Full_Account?>!!!"); return false;
	}else{
	    document.getElementById('getData').src='check_id.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&lv=<?php echo $lv?>&username='+username;
    }
}
function sync2username(text,li) {
	document.myFORM.parents_id.value = li.id;
	parents_reload(li.id);
}
function onload() {
		get_name(myFORM.parents_id.options[myFORM.parents_id.selectedIndex].text);
		}
//建議帳號用
function chg_username(newname) {
	document.myFORM.username.value=newname;
}
function selchg(s1,s2) {
    if (s1.selectedIndex==(s1.length-1)) {
        s2.selectedIndex = s2.length-1;
    }
}

//佔成制下拉霸更換時頁面更新
function winloss_type_change() {
//不做動作
}
function show_count(w,s) {
	//alert(w+' - '+s);
	var org_str=document.all.user_count.innerHTML;//org_str.substr(1,5)
	if (s!=''){
		switch(w){
			case 0:	document.all.user_count.innerHTML = s.substr(1,3)+org_str.substr(3,4);break;
			case 1:document.all.user_count.innerHTML = org_str.substr(0,3)+s+org_str.substr(4,3);break;
			case 2:document.all.user_count.innerHTML = org_str.substr(0,4)+s+org_str.substr(5,2);break;
			case 3:document.all.user_count.innerHTML = org_str.substr(0,5)+s+org_str.substr(6,1);break;
		}
	}
}
// -->
</SCRIPT>
</head>

<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0" vlink="#0000FF" alink="#0000FF" onLoad="onload(),LoadBody();" oncontextmenu="self.event.returnValue=false;window.event.returnValue=false;">
<FORM NAME="myFORM" ACTION="user_add.php?uid=<?php echo $uid?>&lv=<?php echo $lv?>&langx=<?php echo $langx?> " METHOD=POST onSubmit="return SubChk()">
  <INPUT TYPE=HIDDEN NAME="id" VALUE="<?php echo $id?>">
  <INPUT TYPE=HIDDEN NAME="username" VALUE="">
  <INPUT TYPE=HIDDEN NAME="keys" VALUE="add">
  <INPUT TYPE=HIDDEN NAME="enable" VALUE="">
  <INPUT TYPE=HIDDEN NAME="winloss_no" VALUE="Y">
  <input type="hidden" name="s_low_order_gold" value="100">
  <input type="hidden" name="s_low_order_gold_pc" value="50">

  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td class="m_tline">&nbsp;&nbsp;<?php echo $Caption?><?php echo $Mem_Manager?> --<?php echo $Mem_Add?> ,<?php echo $Mem_Edit?>
        <select name="parents_id" class="za_select" onChange="parents_reload(this.options[this.selectedIndex].value)">
        <option label="" value="" selected="selected"></option>
		<?php
	    $mysql = "select UserName,Alias from $data where $agents and Status=0 and Level='$level' order by ID desc";
		//echo $mysql;
		//$loginfo='选者上线'.$Title.'';
		$aresult = mysqli_query($dbLink,$mysql);
		while ($arow = mysqli_fetch_assoc($aresult)){
					if ($parents_id==$arow['UserName']){
						echo "<option value=".$arow['UserName']." selected>".$arow['UserName']."==".$arow['Alias']."</option>";				
						$sel_agents=$arow['UserName'];
					}else{
						echo "<option value=".$arow['UserName'].">".$arow['UserName']."==".$arow['Alias']."</option>";
					}
		}
		?>
        </select>
	    <input type='hidden' name='line' value='ND'>
		<input type='hidden' name='cha' value='N'>
		<input type='hidden' name='rent' value='N'>
		<input type='hidden' name='sp_upper' value=''>
        &nbsp;&nbsp;
        <span style="display:none">
        <b style="color:blue">佔成制: </b>
        <select name="winloss_type" onChange="winloss_type_change();" class="za_select">
          <option label="91佔成制" value="91" selected="selected">91佔成制</option>
          <option label="58佔成制" value="58">58佔成制</option>
          <option label="大股東制" value="SC58">大股東制</option>
          <option label="91A佔成制" value="91A">91A佔成制</option>
          <option label="基本佔成制" value="BASE">基本佔成制</option>
	    </select>
	    </span>
      </td>

    </tr>
    <tr>
      <td colspan="2" height="4"></td>
    </tr>
  </table>
  <table width="780" border="0" cellspacing="1" cellpadding="0" class="m_tab_ed">
    <tr class="m_title_edit">
      <td colspan="2" ><?php echo $Mem_Basic_data?><?php echo $Mem_Settings?> </td>
    </tr>
    <tr class="m_bc_ed">
      <td class="m_co_ed" width="140"> <?php echo $Title?> <?php echo $Mem_Account?> :&nbsp;<span id=user_count></span></td>
            <td>
			  <select size="1" name="num_1" style="border-style: solid; border-width: 0" onChange="show_count(1,this.value);" class="za_select_t">
                <option value=""></option>
				<option value="0">0</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
				<option value="6">6</option>
				<option value="7">7</option>
				<option value="8">8</option>
				<option value="9">9</option>
			</select>
              <select size="1" name="num_2" style="border-style: solid; border-width: 0" onChange="show_count(2,this.value);" class="za_select_t">
                <option value=""></option>
                <option value="0">0</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
              </select>
              <select size="1" name="num_3" style="border-style: solid; border-width: 0" onChange="show_count(3,this.value);" class="za_select_t">
                <option value=""></option>
                <option value="0">0</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
              </select>&nbsp;◎<?php echo $Mem_Account_Rules?>：<?php echo $Mem_Input?><?php echo $Mem_Four_Number?><font color='red'><b>０～９</b></font> 
			  <input type=button name="chk" value="<?php echo $Mem_Check?><?php echo $Mem_Account?>" class="za_button" onclick='ChkMem();'></td>
          </tr>
    </tr>
    <tr class="m_bc_ed">
      <td class="m_co_ed"><?php echo $Mem_Password?> :</td>
      <td>
        <input type=PASSWORD name="password" value="" size=12 maxlength="15" class="za_text">
         ◎<?php echo $Mem_Password_Guidelines?>：<?php echo $Mem_Pasread?></td>
    </tr>
    <tr class="m_bc_ed">
      <td class="m_co_ed"><?php echo $Mem_Cofirm_Password?> :</td>
      <td>
        <input type=PASSWORD name="passwd" value="" size=12 maxlength="15" class="za_text">      </td>
    </tr>
    <tr class="m_bc_ed">
      <td class="m_co_ed"><?php echo $Title?> <?php echo $Mem_Name?> :</td>
      <td><input type=TEXT name="alias" value="" size=10 maxlength=10 class="za_text"></td>
    </tr>
  </table>

  <table width="780" border="0" cellspacing="1" cellpadding="0" class="m_tab_ed">
    <tr class="m_title_edit">
      <td colspan="2" ><?php echo $Mem_Betting_data?><?php echo $Mem_Settings?> </td>
    </tr>	
    <tr class="m_bc_ed">
      <td class="m_co_ed" width="140"><?php echo $Mem_Credit_Amount?> :</td>
      <td>
        <input type=TEXT name="maxcredit" value="0" size=10 maxlength=15 class="za_text" onKeyPress="return CheckKey();">
<?php
$parents_id=$_REQUEST['parents_id'];
if ($parents_id!=''){
?>
        <?php echo $Mem_Credits_Status?> / <?php echo $Mem_Enable?> : <?php echo number_format($srow['credit'],0)?>&nbsp;&nbsp;&nbsp; <?php echo $Mem_Disable?> : <?php echo number_format($erow['credit'],0)?>&nbsp;&nbsp;&nbsp; <?php echo $Mem_Available?> :<?php echo number_format($row['Credit']-$erow['credit']-$srow['credit'],0)?>        
<?php
}else{
?>
		<?php echo $Mem_Credits_Status?> / <?php echo $Mem_Enable?> : 0&nbsp;&nbsp;&nbsp; <?php echo $Mem_Disable?> : 0&nbsp;&nbsp;&nbsp; <?php echo $Mem_Available?> :0 
<?php
}
?>
	 </td>
    </tr>
    <!--tr class="m_bc_ed">
      <td class="m_co_ed">使用币别 :</td>
      <td><select name="type" class="za_select" disabled>
       <option label="<?php echo $curtype?>" value="<?php echo $curtype?>" ><?php echo $curtype?></option>
       </select></td>
    </tr-->	
    <tr class="m_bc_ed">
      <td class="m_co_ed" width="140"><?php echo $Mem_Instant_order?> :</td>
      <td>
        <select name="wager" class="za_select">
        <option label="<?php echo $Mem_Enable?>" value="1" selected="selected"><?php echo $Mem_Enable?></option>
        <option label="<?php echo $Mem_Disable?>" value="0"><?php echo $Mem_Disable?></option>
	    </select>      </td>
    </tr>
<?php
if($lv=='B'){
?>	
        <tr class="m_bc_ed">
      <td class="m_co_ed" width="140"><?php echo $Mem_Corprator?> <?php echo $Mem_Share?> :</td>
      <td>
       <select name="winloss_b" class="za_select">
	   <?php
	    for($i=$abcd;$i>=0;$i=$i-5){
		    $abc=$i;
		    if ($i==100){
			echo "<option value=$abc selected>".($i/10).$wor_percent."</option>\n";
		    }else{
			echo "<option value=$abc>".($i/10).$wor_percent."</option>\n";
		    }
	    }
	   ?>
	</select></td>
    </tr>
<?php
}else if($lv=='D'){
?>	
        <tr class="m_bc_ed">
      <td class="m_co_ed" width="140"><?php echo $Mem_World?> <?php echo $Mem_Share?> :</td>
      <td>
       <select name="winloss_c" class="za_select">
	   <?php
	    for($i=$abcd;$i>=0;$i=$i-5){
		    $abc=$i;
		    if ($i==0){
			echo "<option value=$abc selected>".($i/10).$wor_percent."</option>\n";
		    }else{
			echo "<option value=$abc>".($i/10).$wor_percent."</option>\n";
		    }
	    }
	   ?>
	</select>      </td>
    </tr>
    <tr class="m_bc_ed">
      <td class="m_co_ed" width="140"><?php echo $Mem_Agents?> <?php echo $Mem_Share?> :</td>
      <td>
       <select name="winloss_d" class="za_select">
	   <?php
	    for($i=$abcd;$i>=0;$i=$i-5){
		    $abc=$i;
		    if ($i==0){
			echo "<option value=$abc selected>".($i/10).$wor_percent."</option>\n";
		    }else{
			echo "<option value=$abc>".($i/10).$wor_percent."</option>\n";
		    }
	    }
	   ?>
	</select></td>
    </tr>
<?php
}
?>
<?php
if ($lv=='A'){
?>
    <tr class="m_bc_ed">
      <td class="m_co_ed" width="140">使用天数 :</td>
      <td><input type=TEXT name="usedate" value="0" size=10 maxlength=15 class="za_text" onKeyPress="return CheckKey();"> （0为不限制，1为一天。）</td>
    </tr>
<?php
}
?>   
          <input type=hidden name="location" value="" size=10 maxlength=10 class="za_text">
        <tr class="m_bc_ed" align="center">
      <td colspan="2">
        <input type=SUBMIT name="OK" value="<?php echo $Mem_Confirm?>" class="za_button">
        &nbsp; &nbsp; &nbsp;
        <input type=BUTTON name="FormsButton2" value="<?php echo $Mem_Cancle?>" id="FormsButton2" onClick="window.location.replace('user_browse.php?uid=<?php echo $uid?>&lv=<?php echo $lv?>&enable=Y&langx=<?php echo $langx?>');" class="za_button">      </td>
    </tr>
  </table>

</form>
<!--iframe name="check_frame" src="" width="0" height="0" style="display:none"></iframe-->
<iframe id="getData" src="" width=0 height=0></iframe>

</body>
</html>
<?php
}
$ip_addr = get_ip();
$mysql="insert into ".DBPREFIX."web_mem_log_data(UserName,Logintime,ConText,Loginip,Url) values('$name',now(),'$loginfo','$ip_addr','".BROWSER_IP."')";
mysqli_query($dbMasterLink,$mysql);
?>