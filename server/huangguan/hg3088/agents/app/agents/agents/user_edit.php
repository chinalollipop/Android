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
$userlv= $_SESSION['Level'] ; // 当前管理员层级
$parents_id=$_REQUEST["parents_id"];
$parents_name=$_REQUEST["parents_name"];
$name=$_REQUEST["name"];
$keys=$_REQUEST['keys'];

require ("../../agents/include/traditional.$langx.inc.php");

$username=$_SESSION['UserName'];

$sql = "select * from ".DBPREFIX."web_agents_data where ID='$parents_id' and UserName='$name' ";
$result = mysqli_query($dbLink,$sql);
$cou=mysqli_num_rows($result);
$row = mysqli_fetch_assoc($result);

$admin=$row['Admin'];
$super=$row['Super'];
$corprator=$row['Corprator'];
$world=$row['World'];
$alias=$row['Alias'];
$credit=$row['Credit'];
$points=$row['Points'];
$curtype=$row['CurType'];
$password=$row['PassWord'];
$wager=$row['Wager'];
$usedate=$row['UseDate'];
$Bank_Address=$row['Bank_Address']; // 开户行地址
$Bank_Account=$row['Bank_Account']; // 开户行账号
$PassWord_Safe=$row['PassWord_Safe']; // 资金密码

if ($wager==0){
	$selected="selected='selected'";
}else{
	$selected="";
}
// echo '<pre>';
// var_Dump($_REQUEST);exit;
switch ($lv){
case 'A':
    $Title=$Mem_Super;
    $level='M';
	$webdata=DBPREFIX.'web_system_data';
	$data=DBPREFIX.'web_agents_data';
	$user="Level='B' and Super='$name'";
	$agents="(UserName='$username' or Admin='$admin' ro Super='$username' or Corprator='$username' or World='$username') and";
	$ag="UserName='$admin'";
	$wo="Admin='$admin' and UserName!='$name'";
	break;
case 'B': //股东
    $Title=$Mem_Corprator;
    $level='A';
	$webdata=DBPREFIX.'web_agents_data';
	$data=DBPREFIX.'web_agents_data';
	$user="Level='C' and Corprator='$name'";
	$agents="(UserName='$username' or Super='$username' or Corprator='$username' or World='$username') and";
	$ag="UserName='$super'";
	$wo="Super='$super' and UserName!='$name'";
	break;
case 'C': //总代理
    $Title=$Mem_World;
    $level='B';
	$webdata=DBPREFIX.'web_agents_data';
	$data=DBPREFIX.'web_agents_data';
	$user="Level='D' and World='$name'";
	$agents="(UserName='$username' or Super='$username' or Corprator='$username' or World='$username') and";
	$ag="UserName='$corprator'";
	$wo="Corprator='$corprator' and UserName!='$name'";
	break;
case 'D': // 代理商
    $Title=$Mem_Agents;
    $level='C';
	$webdata=DBPREFIX.'web_agents_data';
	$data=DBPREFIX.MEMBERTABLE;
	$user="Agents='$name'";
	$agents="(UserName='$username' or Super='$username' or Corprator='$username' or World='$username') and";
	$ag="UserName='$world'";
	$wo="World='$world' and UserName!='$name'";
	break;
case 'MEM':
    $Title=$Mem_Member;
    $level='D';
	$webdata=DBPREFIX.'web_agents_data';
    $data=DBPREFIX.MEMBERTABLE;
	$user="UserName='$name' and Level='D'";
	$ag="UserName='$name'";
	$wo="UserName='$name' and UserName!='$name'";
	break;	
}
$loginfo='修改'.$Title.':'.$name.'';
$wsql = "select * from ".DBPREFIX."web_agents_data where UserName='$corprator'";
$result = mysqli_query($dbLink,$wsql);
$wrow = mysqli_fetch_assoc($result);
$Point=$wrow['B_Point'];

if ($keys=='edit'){
	$id=$_REQUEST["id"];
    $pasd= str_replace(' ','',$_REQUEST["password"]) ;//密码
	$alias=$_REQUEST["alias"];
    $edit_sql = '' ;
	if($lv=='D'){ // 普通代理才有
        $Bank_Address_edit = str_replace(' ','',$_REQUEST["Bank_Address"]);
        $Bank_Account_edit = str_replace(' ','',$_REQUEST["Bank_Account"]) ; // 银行账号
        $PassWord_Safe_edit = str_replace(' ','',$_REQUEST["PassWord_Safe"]) ; // 取款密码
	    $edit_sql = ",Bank_Address='".$Bank_Address_edit."',Bank_Account='".$Bank_Account_edit."',PassWord_Safe='".$PassWord_Safe_edit."'" ;
    }

    if(!$pasd){ // 如果没有输入登录密码,用原来的密码
        $mdpasd = $password ;
    }else{
        $mdpasd = passwordEncryption($pasd,$name);
    }
			$mysql="update ".DBPREFIX."web_agents_data set PassWord='".$mdpasd."',Alias='$alias' $edit_sql where ID='$id'";

			mysqli_query($dbMasterLink,$mysql) or die ("操作失败!");
			$loginfo=$loginname.'修改'.$Title.':'.$name.' 密码,名称:'.$alias;
			$ip_addr = get_ip();
			$mysql="insert into ".DBPREFIX."web_mem_log_data(UserName,Logintime,ConText,Loginip,Url) values('$username',now(),'$loginfo','$ip_addr','".BROWSER_IP."')";
			mysqli_query($dbMasterLink,$mysql);
			echo "<Script Language=javascript>self.location='user_browse.php?uid=$uid&lv=$lv&userlv=$userlv&langx=$langx';</script>";
		
    }else{
		$ssql="select sum(credit) as credit,sum(points) as points from $data where $user and Status=0";
		$sresult = mysqli_query($dbLink,$ssql);
		$srow = mysqli_fetch_assoc($sresult);
		
		$esql="select sum(credit) as credit,sum(points) as points from $data where $user and (Status=1 or Status=2)";
		$eresult = mysqli_query($dbLink,$esql);
		$erow = mysqli_fetch_assoc($eresult);
?>
<html>
<head>
<title>main</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8 ">
<link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
<script>
function SubChk(){
	var Numflag = 0;
	var Letterflag = 0;
    var pwd = document.all.password.value;
    if(pwd) { // 密码有输入才需要验证
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
        if(document.all.password.value.length < 6 ){alert('<?php echo $Mem_NewPassword_6_Characters?>');return false;}
        if(document.all.password.value.length > 15 ){alert('<?php echo $Mem_NewPassword_12_CharactersMax?>');return false;}
    }

	if(document.all.password.value != document.all.passwd.value)
	{ document.all.password.focus(); alert("<?php echo $Mem_PasswordConfirmError?>"); return false; }
	if(document.all.alias.value==''){ 
		document.all.alias.focus(); alert("<?php echo $Mem_Input?> :<?php echo $Mem_Mame?> !!"); return false; 
	}

	document.all.OK.disabled = true;
	document.all.FormsButton2.disabled = true;
	//document.myFORM.submit();
	if(!confirm("<?php echo $Mem_Whether_Edit?> <?php echo $Title?> ?"))
	{
		document.all.OK.disabled = false;
		document.all.FormsButton2.disabled = false;
		return false;
	}
}

function CheckKey(){
	if(event.keyCode < 48 || event.keyCode > 57){alert("<?php echo $Mem_Enter_Numbers?>"); return false;}
}

function parents_reload(parents_id) {
	}
function sync2username(text,li) {
	document.myFORM.parents_id.value = li.id;
	parents_reload(li.id);
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

</script>
</head>
<body  >
<dl class="main-nav">
    <dt>
        <?php echo $Title.$Mem_Manager?>
    </dt>
</dl>
<div class="main-ui width_1000">
<FORM NAME="myFORM" ACTION="user_edit.php?uid=<?php echo $uid?>&lv=<?php echo $lv?>&parents_id=<?php echo $parents_id?>&name=<?php echo $name?>&langx=<?php echo $langx?> " METHOD=POST onSubmit="return SubChk()">
  <INPUT TYPE=HIDDEN NAME="id" VALUE="<?php echo $parents_id?>">
  <INPUT TYPE=HIDDEN NAME="keys" VALUE="edit">
  <INPUT TYPE=HIDDEN NAME="enable" VALUE="Y">
  <INPUT TYPE=HIDDEN NAME="winloss_no" VALUE="Y">
  <input type="hidden" name="s_low_order_gold" value="">
  <input type="hidden" name="s_low_order_gold_pc" value="">
  <table  class="m_tab_ed">
    <tr class="m_title_edit">
        <td colspan="2" ><h4><?php echo $Mem_Basic_data?><?php echo $Mem_Settings?></h4></td>
    </tr>
    <tr class="m_bc_ed">
      <td class="m_co_ed" width="140"><?php echo $Title?><?php echo $Mem_Account?> :</td>
      <td align="left"><?php echo $name?><input type=hidden name="username" value="<?php echo $name?>" size=10 maxlength="15" class="za_text"></td>
    </tr>
    <tr class="m_bc_ed">
          <td class="m_co_ed"><?php echo $Title?><?php echo $Mem_Name?> :</td>
          <td align="left"><input type=TEXT name="alias" value="<?php echo $alias?>" size=10 maxlength=10 class="za_text"></td>
    </tr>
    <tr class="m_bc_ed">
      <td class="m_co_ed"><?php echo $Mem_Password?> :</td>
      <td align="left"><input type=PASSWORD name="password" value="" size=12 maxlength="15" class="za_text">◎<?php echo $Mem_Password_Guidelines?>：<?php echo $Mem_Pasread?></td>
    </tr>
    <tr class="m_bc_ed">
      <td class="m_co_ed"><?php echo $Mem_Cofirm_Password?> :</td>
      <td align="left"><input type=PASSWORD name="passwd" value="" size=12 maxlength="15" class="za_text"></td>
    </tr>
<?php
if($lv=='D'){ // 普通代理商才有
?>
    <tr class="m_bc_ed">
        <td class="m_co_ed">收款银行:</td>
        <td align="left"><input type="text" name="Bank_Address" value="<?php echo $Bank_Address?>" size=25 class="za_text"></td>
    </tr>
    <tr class="m_bc_ed">
        <td class="m_co_ed">收款账号:</td>
        <td align="left"><input type="text" name="Bank_Account" value="<?php echo $Bank_Account?>" size=25 class="za_text"></td>
    </tr>
    <tr class="m_bc_ed">
        <td class="m_co_ed">取款密码:</td>
        <td align="left"><input type="password" name="PassWord_Safe" value="<?php echo $PassWord_Safe?>" class="za_text"></td>
    </tr>


<?php } ?>


	    <input type=hidden name="location" value="9999" size=10 maxlength=10 class="za_text">
   <tr class="m_bc_ed" align="center">
      <td colspan="2">
        <input type=SUBMIT name="OK" value="<?php echo $Mem_Confirm?>" class="za_button">
        &nbsp; &nbsp; &nbsp;
        <input type=BUTTON name="FormsButton2" value="<?php echo $Mem_Cancle?>" id="FormsButton2" onClick="window.location.replace('user_browse.php?uid=<?php echo $uid?>&lv=<?php echo $lv?>&userlv=<?php echo $userlv?>&langx=<?php echo $langx?>&enable=Y');" class="za_button">
      </td>
    </tr>
  </table>
</form>
</div>
</body>
</html>
<?php
}
?>