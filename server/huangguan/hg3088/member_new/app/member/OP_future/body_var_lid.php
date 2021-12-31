<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "../include/address.mem.php";
require ("../include/config.inc.php");
$uid=$_REQUEST['uid'];
$langx=$_SESSION['langx'];
$mtype=$_REQUEST['mtype'];
$rtype=trim($_REQUEST['rtype']);
$g_date=$_REQUEST['g_date'];
require ("../include/traditional.$langx.inc.php");


if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
	echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
	exit;
}

?>
<script>var links='./body_browse.php?uid=<?php echo $uid?>&rtype=<?php echo $rtype?>&langx=<?php echo $langx?>&mtype=3&g_date=<?php echo $g_date?>';
</script>
<script>
<!--
var sel_gtype=parent.sel_gtype;
function onLoad(){
	if (""+eval("parent.parent."+sel_gtype+"_lid_ary")=="undefined") eval("parent.parent."+sel_gtype+"_lid_ary='ALL'");	
	var len =lid_form.elements.length;
	if(eval("parent.parent."+sel_gtype+"_lid_ary")=='ALL'){
		lid_form.sall.checked='true';
		for (var i = 1; i < len; i++) {
			var e = lid_form.elements[i];
			if (e.id.substr(0,3)=="LID") e.checked = 'true';
		}
	}else{
		for (var i = 1; i < len; i++) {
			var e = lid_form.elements[i];
			if(e.id.substr(0,3)=="LID"&&e.type=='checkbox') {
				if(eval("parent.parent."+sel_gtype+"_lid_ary").indexOf(e.id.substr(3,e.id.length)+"-",0)!=-1){
					e.checked='true';
				}
			}
		}		
	}
	
}
function selall(){
	var len =lid_form.elements.length;
	var does=true;
  	does=lid_form.sall.checked;
	for (var i = 1; i < len; i++) {
		var e = lid_form.elements[i];
		if (e.id.substr(0,3)=="LID") e.checked = does;
	} 
}
function chk_all(e){
	if(!e) lid_form.sall.checked=e;
}
function chk_league(){
	var len =lid_form.elements.length;
	var strlid='';
	var strlname='';
	var gcount=0;
	top.OM_lid='';
  	if(lid_form.sall.checked) {
  		eval("top."+sel_gtype+"_lid['"+sel_gtype+"_lid_type']=parent.parent."+sel_gtype+"_lid_type='"+((top.swShowLoveI)?"3":"")+"'");
  		eval("top."+sel_gtype+"_lid['"+sel_gtype+"_lid_ary']=parent.parent."+sel_gtype+"_lid_ary='ALL'");
  		eval("top."+sel_gtype+"_lid['"+sel_gtype+"_lname_ary']=parent.parent."+sel_gtype+"_lname_ary='ALL'");
  	}else{
		for (var i = 1; i < len; i++) {
			var e = lid_form.elements[i];
			if (e.id.substr(0,3)=="LID"&&e.type=='checkbox'&&e.checked) {
				strlid+=e.id.substr(3,e.id.length)+'-';
				strlname+=e.value+'-';
				gcount++;
			}
		}
		if(gcount>0){
			eval("top."+sel_gtype+"_lid['"+sel_gtype+"_lid_type']=parent.parent."+sel_gtype+"_lid_type='2'");
			eval("top."+sel_gtype+"_lid['"+sel_gtype+"_lid_ary']=parent.parent."+sel_gtype+"_lid_ary=strlid");
			eval("top."+sel_gtype+"_lid['"+sel_gtype+"_lname_ary']=parent.parent."+sel_gtype+"_lname_ary=strlname");
		}else{
			eval("top."+sel_gtype+"_lid['"+sel_gtype+"_lid_type']=parent.parent."+sel_gtype+"_lid_type='"+((top.swShowLoveI)?"3":"")+"'");
			eval("top."+sel_gtype+"_lid['"+sel_gtype+"_lid_ary']=parent.parent."+sel_gtype+"_lid_ary='ALL'");
			eval("top."+sel_gtype+"_lid['"+sel_gtype+"_lname_ary']=parent.parent."+sel_gtype+"_lname_ary='ALL'");
		}	
	}
	back();
}
function back(){
	parent.parent.leg_flag="Y";
	parent.g_date="ALL";
	self.location.href=links;
}
//--></script>
<html>
<head>
<title>Select League</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="/style/member/mem_body<?php echo $css?>.css?v=<?php echo AUTOVER; ?>" type="text/css">
</head>

<body id="MOM" onLoad="onLoad();" onSelectStart="self.event.returnValue=false" oncontextmenu="self.event.returnValue=false">
<form name='lid_form'>
<table border="0" cellpadding="0" cellspacing="0" id="box">
  <tr>
    <td id="ad">
      <span id="real_msg"><marquee scrolldelay=\"120\"><?php echo $mem_msg?></marquee></span>
	  <p><a href="javascript://" onClick="javascript: window.open('../scroll_history.php?uid=138ec074m436014l2ceb871&langx={LANGUAGE}','','menubar=no,status=yes,scrollbars=yes,top=150,left=200,toolbar=no,width=510,height=500')">News History</a></p>
	</td>
  </tr>
  <tr>
    <td class="top">
  	  <h1><em><?php echo $Body_Select_League?> :<a href="#" onClick="chk_league();"><?php echo $Body_Enter?></a><a href="#" onClick="back();"><?php echo $Body_Back?></a></em></h1>
	</td>
  </tr>
  <tr>
    <td class="mem">
      <table border="0" cellspacing="1" cellpadding="0" class="game">
        <tr> 
          <td class="league_all"><input type=checkbox value=all id=sall onClick="selall();"><?php echo $Body_Select_All?></td>
        </tr>
        <tr>
<?php
switch ($rtype){
	case "r":
	    $type='S';
		break;
	case "hr":
	    $type='H';
		break;
	case "pd":
	    $type='PD';
		break;
	case "hpd":
	    $type='HPD';
		break;
	case "t":
	    $type='T';
		break;
	case "f":
	    $type='F';
		break;
	case "p3":
	    $type='P3';
		break;
}
$m_date=date('Y-m-d');

$mysql = "select distinct $m_league as M_League FROM `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` WHERE `Type`='OM' and `M_Date` >'$m_date' and ".$type."_Show=1";
$result = mysqli_query($dbLink, $mysql);

$cou=mysqli_num_rows($result);
while ($league=mysqli_fetch_assoc($result)){
?>
        <tr>
          <td class="league"><input type=checkbox value="<?php echo $league['M_League']?>" id="LID<?php echo $league['M_League']?>" onClick="chk_all(this.checked);"><?php echo $league['M_League']?></td>
        </tr>
<?php
}
?>
<?php
if ($cou==0){
?>
        <tr>
          <td class="league"><input type=checkbox value="" id="LID{ID}" onClick="chk_all(this.checked);"></td>
        </tr>
<?php
}
?>
      </table> 
	</td>
  </tr>
  <tr><td id="foot"><b>&nbsp;</b></td></tr>
</table>
</form>

</body>
</html>


<div id="copyright"><?php echo $Copyright?></div>
