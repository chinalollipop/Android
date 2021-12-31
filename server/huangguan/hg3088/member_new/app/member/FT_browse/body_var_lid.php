<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

//if(!defined("ROOT_DIR")){
//    define("ROOT_DIR",  dirname(dirname(dirname(dirname(__FILE__)))));
//}


include "../include/address.mem.php";
require ("../include/config.inc.php");
require_once("../../../../common/sportCenterData.php");
$uid=$_REQUEST['uid'];
$langx=$_SESSION['langx'];
$mtype=$_REQUEST['mtype'];
$rtype=trim($_REQUEST['rtype']);
require ("../include/traditional.$langx.inc.php");

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
	echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
	exit;
}
?>
<?php if ($rtype=='re'){ ?>
<script>

var sel_gtype=parent.sel_gtype;
function onLoad(){
    if (""+eval("parent.parent."+sel_gtype+"_lid_ary_RE")=="undefined") eval("parent.parent."+sel_gtype+"_lid_ary_RE='ALL'");
    var len =lid_form.elements.length;
    parent.setleghi(document.body.scrollHeight);
    if(eval("parent.parent."+sel_gtype+"_lid_ary_RE")=='ALL'){
        lid_form.sall.checked='true';
        for (var i = 1; i < len; i++) {
            var e = lid_form.elements[i];
            if (e.id.substr(0,3)=="LID") e.checked = 'true';
        }
    }else{
        for (var i = 1; i < len; i++) {
            var e = lid_form.elements[i];
            if(e.id.substr(0,3)=="LID"&&e.type=='checkbox') {
                if(eval("parent.parent."+sel_gtype+"_lid_ary_RE").indexOf(e.id.substr(3,e.id.length)+"|",0)!=-1){
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


function select_all(b){
    var len =lid_form.elements.length;
    var does=b;
    lid_form.sall.checked=does;
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
    if(lid_form.sall.checked) {
        eval("top."+sel_gtype+"_lid['"+sel_gtype+"_lid_ary_RE']=parent.parent."+sel_gtype+"_lid_ary_RE='ALL'");
        eval("top."+sel_gtype+"_lid['"+sel_gtype+"_lname_ary_RE']=parent.parent."+sel_gtype+"_lname_ary_RE='ALL'");
    }else{
        for (var i = 1; i < len; i++) {
            var e = lid_form.elements[i];
            if (e.id.substr(0,3)=="LID"&&e.type=='checkbox'&&e.checked) {
                strlid+=e.id.substr(3,e.id.length)+'|';
                strlname+=e.value+'|';
                gcount++;
            }
        }
        if(gcount>0){
            eval("top."+sel_gtype+"_lid['"+sel_gtype+"_lid_ary_RE']=parent.parent."+sel_gtype+"_lid_ary_RE=strlid");
            eval("top."+sel_gtype+"_lid['"+sel_gtype+"_lname_ary_RE']=parent.parent."+sel_gtype+"_lname_ary_RE=strlname");
        }else{
            eval("top."+sel_gtype+"_lid['"+sel_gtype+"_lid_ary_RE']=parent.parent."+sel_gtype+"_lid_ary_RE='ALL'");
            eval("top."+sel_gtype+"_lid['"+sel_gtype+"_lname_ary_RE']=parent.parent."+sel_gtype+"_lname_ary_RE='ALL'");
        }   
    }
    back();
}
function back(){
    parent.parent.leg_flag="Y";
    //self.location.href=links;
    parent.LegBack();
}
</script>
<?php }else{ ?>
<script>

var sel_gtype=parent.parent.sel_gtype;
function onLoad(){
    if (""+eval("parent.parent."+sel_gtype+"_lid_ary")=="undefined") eval("parent.parent."+sel_gtype+"_lid_ary='ALL'");   
    var len =lid_form.elements.length;
    
        parent.setleghi(document.body.scrollHeight);
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
                if(eval("parent.parent."+sel_gtype+"_lid_ary").indexOf(e.id.substr(3,e.id.length)+"|",0)!=-1){
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
function select_all(b){
    var len =lid_form.elements.length;
    var does=b;
    lid_form.sall.checked=does;
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
    if(lid_form.sall.checked) {
        eval("top."+sel_gtype+"_lid['"+sel_gtype+"_lid_type']=parent.parent."+sel_gtype+"_lid_type='"+((top.swShowLoveI)?"3":"")+"'");
        eval("top."+sel_gtype+"_lid['"+sel_gtype+"_lid_ary']=parent.parent."+sel_gtype+"_lid_ary='ALL'");
        eval("top."+sel_gtype+"_lid['"+sel_gtype+"_lname_ary']=parent.parent."+sel_gtype+"_lname_ary='ALL'");
    }else{
        for (var i = 1; i < len; i++) {
            var e = lid_form.elements[i];
            if (e.id.substr(0,3)=="LID"&&e.type=='checkbox'&&e.checked) {
                strlid+=e.id.substr(3,e.id.length)+'|';
                strlname+=e.value+'|';
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
	//alert('asdfasdf');
    back();
}
function back(){
	//alert('asdfasdf');
    parent.parent.leg_flag="Y";
    //parent.location.href=links;
    parent.LegBack();
}
</script>
<?php } ?>
<html>
<head>
<title>Select League</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="../../../style/member/mem_body_ft.css?v=<?php echo AUTOVER; ?>" type="text/css">
</head>

<body id="LEG" onLoad="onLoad();" onSelectStart="self.event.returnValue=false" oncontextmenu="self.event.returnValue=false">
<form name='lid_form' onSubmit="return false;">
<table border="0" cellpadding="0" cellspacing="0" id="box">
  <tr>
    <td class="leg_top">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="30%"><h1><input type=checkbox value=all id=sall onClick="selall();">全选</h1></td>
            <td class="btn_td">
            <input type="submit" name="button" id="button" value="取消" class="enter_btn" onClick="back();">&nbsp;
            <input type="submit" name="button" id="button" value="提交" class="enter_btn" onClick="chk_league();">
            </td>
            <td class="close_td"><span class="close_box" onClick="back();">关闭</span></td>
          </tr>
        </table>
      
    </td>
  </tr>
  <tr>
    <td>
    <div class="leg_mem">
      <table border="0" cellspacing="1" cellpadding="0" class="leg_game">
<?php
switch ($rtype){
	case "r":
	    $type='S';
		break;
	case "hr":
	    $type='H';
		break;
	case "rpd":
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
if ($rtype=='re'){
	//$mysql = "select distinct $m_league as M_League FROM `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` WHERE `Type`='FT' and `M_Date` ='$m_date' and RB_Show=1 and MB_Inball=''";
    $mysql = "select distinct $m_league as M_League FROM `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` WHERE `Type`='FT' and `M_Date` ='$m_date' and RB_Show=1";
}else{
    if ($rtype=='rpd'){
        $mysql = "select distinct $m_league as M_League FROM `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` WHERE `Type`='FT' and `M_Date` ='$m_date' and ".$type."_Show=1";
    }else{
        $mysql = "select distinct $m_league as M_League FROM `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` WHERE `Type`='FT' and `M_Start` > now( ) and `M_Date` ='$m_date' and ".$type."_Show=1";
    }
}
$result = mysqli_query($dbCenterSlaveDbLink, $mysql);
$cou=mysqli_num_rows($result);
$i=0;
while ($league=mysqli_fetch_assoc($result)){
	$i=$i+3;
?>
        <tr>
          <td class="league"><div ><input type=checkbox value="<?php echo $league['M_League']?>" id="LID<?php echo $league['M_League']?>" onClick="chk_all(this.checked);"><font title="<?php echo $league['M_League']?>"><?php echo $league['M_League']?></font></div></td>
          <?php if($league=mysqli_fetch_assoc($result)){ ?>
          <td class="league"><div ><input type=checkbox value="<?php echo $league['M_League']?>" id="LID<?php echo $league['M_League']?>" onClick="chk_all(this.checked);"><font title="<?php echo $league['M_League']?>"><?php echo $league['M_League']?></font></div></td>
          <?php }else{ ?>
          <td class="league"><div style='display:none;'><input type=checkbox value="" id="LID" onClick="chk_all(this.checked);"><font title=""></font></div></td>
          <?php } ?>
          <?php if($league=mysqli_fetch_assoc($result)){ ?>
          <td class="league"><div ><input type=checkbox value="<?php echo $league['M_League']?>" id="LID<?php echo $league['M_League']?>" onClick="chk_all(this.checked);"><font title="<?php echo $league['M_League']?>"><?php echo $league['M_League']?></font></div></td>
          <?php }else{ ?>
          <td class="league"><div style='display:none;'><input type=checkbox value="" id="LID" onClick="chk_all(this.checked);"><font title=""></font></div></td>
          <?php } ?>          
        </tr>        
<?php
}
for($j=$i;$j<=30;$j=$j+3){
?>
        <tr>
          <td class="league"><div style='display:none;'><input type=checkbox value="" id="LID" onClick="chk_all(this.checked);"><font title=""></font></div></td>
          <td class="league"><div style='display:none;'><input type=checkbox value="" id="LID" onClick="chk_all(this.checked);"><font title=""></font></div></td>
          <td class="league"><div style='display:none;'><input type=checkbox value="" id="LID" onClick="chk_all(this.checked);"><font title=""></font></div></td>
        </tr>
<?php	
}
?>
<?php
if ($cou==0){
?>
        <tr>
          <td class="league"><div style='display:none;'><input type=checkbox value="" id="LID" onClick="chk_all(this.checked);"><font title=""></font></div></td>
          <td class="league"><div style='display:none;'><input type=checkbox value="" id="LID" onClick="chk_all(this.checked);"><font title=""></font></div></td>
          <td class="league"><div style='display:none;'><input type=checkbox value="" id="LID" onClick="chk_all(this.checked);"><font title=""></font></div></td>
        </tr>
        <tr>
          <td class="league"><div style='display:none;'><input type=checkbox value="" id="LID" onClick="chk_all(this.checked);"><font title=""></font></div></td>
          <td class="league"><div style='display:none;'><input type=checkbox value="" id="LID" onClick="chk_all(this.checked);"><font title=""></font></div></td>
          <td class="league"><div style='display:none;'><input type=checkbox value="" id="LID" onClick="chk_all(this.checked);"><font title=""></font></div></td>
        </tr>
        <tr>
          <td class="league"><div style='display:none;'><input type=checkbox value="" id="LID" onClick="chk_all(this.checked);"><font title=""></font></div></td>
          <td class="league"><div style='display:none;'><input type=checkbox value="" id="LID" onClick="chk_all(this.checked);"><font title=""></font></div></td>
          <td class="league"><div style='display:none;'><input type=checkbox value="" id="LID" onClick="chk_all(this.checked);"><font title=""></font></div></td>
        </tr>
        <tr>
          <td class="league"><div style='display:none;'><input type=checkbox value="" id="LID" onClick="chk_all(this.checked);"><font title=""></font></div></td>
          <td class="league"><div style='display:none;'><input type=checkbox value="" id="LID" onClick="chk_all(this.checked);"><font title=""></font></div></td>
          <td class="league"><div style='display:none;'><input type=checkbox value="" id="LID" onClick="chk_all(this.checked);"><font title=""></font></div></td>
        </tr>
        <tr>
          <td class="league"><div style='display:none;'><input type=checkbox value="" id="LID" onClick="chk_all(this.checked);"><font title=""></font></div></td>
          <td class="league"><div style='display:none;'><input type=checkbox value="" id="LID" onClick="chk_all(this.checked);"><font title=""></font></div></td>
          <td class="league"><div style='display:none;'><input type=checkbox value="" id="LID" onClick="chk_all(this.checked);"><font title=""></font></div></td>
        </tr>
        <tr>
          <td class="league"><div style='display:none;'><input type=checkbox value="" id="LID" onClick="chk_all(this.checked);"><font title=""></font></div></td>
          <td class="league"><div style='display:none;'><input type=checkbox value="" id="LID" onClick="chk_all(this.checked);"><font title=""></font></div></td>
          <td class="league"><div style='display:none;'><input type=checkbox value="" id="LID" onClick="chk_all(this.checked);"><font title=""></font></div></td>
        </tr>
        <tr>
          <td class="league"><div style='display:none;'><input type=checkbox value="" id="LID" onClick="chk_all(this.checked);"><font title=""></font></div></td>
          <td class="league"><div style='display:none;'><input type=checkbox value="" id="LID" onClick="chk_all(this.checked);"><font title=""></font></div></td>
          <td class="league"><div style='display:none;'><input type=checkbox value="" id="LID" onClick="chk_all(this.checked);"><font title=""></font></div></td>
        </tr>
        <tr>
          <td class="league"><div style='display:none;'><input type=checkbox value="" id="LID" onClick="chk_all(this.checked);"><font title=""></font></div></td>
          <td class="league"><div style='display:none;'><input type=checkbox value="" id="LID" onClick="chk_all(this.checked);"><font title=""></font></div></td>
          <td class="league"><div style='display:none;'><input type=checkbox value="" id="LID" onClick="chk_all(this.checked);"><font title=""></font></div></td>
        </tr>
        <tr>
          <td class="league"><div style='display:none;'><input type=checkbox value="" id="LID" onClick="chk_all(this.checked);"><font title=""></font></div></td>
          <td class="league"><div style='display:none;'><input type=checkbox value="" id="LID" onClick="chk_all(this.checked);"><font title=""></font></div></td>
          <td class="league"><div style='display:none;'><input type=checkbox value="" id="LID" onClick="chk_all(this.checked);"><font title=""></font></div></td>
        </tr>
        <tr>
          <td class="league"><div style='display:none;'><input type=checkbox value="" id="LID" onClick="chk_all(this.checked);"><font title=""></font></div></td>
          <td class="league"><div style='display:none;'><input type=checkbox value="" id="LID" onClick="chk_all(this.checked);"><font title=""></font></div></td>
          <td class="league"><div style='display:none;'><input type=checkbox value="" id="LID" onClick="chk_all(this.checked);"><font title=""></font></div></td>
        </tr>
<?php
}
?>      
      </table> 
      </div>
    </td>
  </tr>
</table>
<div class="btn_box">
  <input type="submit" name="button" id="button" value="取消" class="enter_btn" onClick="back();">&nbsp;
  <input type="submit" name="button" id="button" value="提交" class="enter_btn" onClick="chk_league();">
</div>

</form>


</body>
</html>
