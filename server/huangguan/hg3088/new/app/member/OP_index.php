<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "include/address.mem.php";
require ("include/config.inc.php");
echo "<script>if(self == top) parent.location='".BROWSER_IP."'\n;</script>";
$uid=$_REQUEST['uid'];
$langx=$_SESSION['langx'];
$mtype=$_REQUEST['mtype'];

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
	echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
	exit;
}
$showtype=$_REQUEST['showtype'];
$uid=$_REQUEST['uid'];
$mtype=$_REQUEST['mtype'];
$langx=$_SESSION['langx'];
require ("./include/traditional.$langx.inc.php");
if ($showtype=="future"){
	$ShowType="OP_future";
	$header="future";
}else{
	$ShowType="OP_browse";
	$header="";
}
?>
<html>
<head>
<title>歡迎光臨投注</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<script>
    
try{    
    FT_lid_ary=top.FT_lid['FT_lid_ary'];
    FT_lid_type=top.FT_lid['FT_lid_type'];
    FT_lname_ary=top.FT_lid['FT_lname_ary'];
    FT_lid_ary_RE=top.FT_lid['FT_lid_ary_RE'];
    FT_lname_ary_RE=top.FT_lid['FT_lname_ary_RE'];
    FU_lid_ary=top.FU_lid['FU_lid_ary'];
    FU_lid_type=top.FU_lid['FU_lid_type'];
    FU_lname_ary=top.FU_lid['FU_lname_ary'];
    FSFT_lid_ary=top.FSFT_lid['FSFT_lid_ary'];
    FSFT_lname_ary=top.FSFT_lid['FSFT_lname_ary'];
}catch(E){
    initlid_FT();
}  
try{    
    BK_lid_ary=top.BK_lid['BK_lid_ary'];
    BK_lid_type=top.BK_lid['BK_lid_type'];
    BK_lname_ary=top.BK_lid['BK_lname_ary'];
    BK_lid_ary_RE=top.BK_lid['BK_lid_ary_RE'];
    BK_lname_ary_RE=top.BK_lid['BK_lname_ary_RE'];
    BU_lid_ary=top.BU_lid['BU_lid_ary'];
    BU_lid_type=top.BU_lid['BU_lid_type'];
    BU_lname_ary=top.BU_lid['BU_lname_ary'];
    FSBK_lid_ary=top.FSBK_lid['FSBK_lid_ary'];
    FSBK_lname_ary=top.FSBK_lid['FSBK_lname_ary'];  
}catch(E){
    initlid_BK();
}   
try{
    BS_lid_ary=top.BS_lid['BS_lid_ary'];
    BS_lid_type=top.BS_lid['BS_lid_type'];
    BS_lname_ary=top.BS_lid['BS_lname_ary'];
    BS_lid_ary_RE=top.BS_lid['BS_lid_ary_RE'];
    BS_lname_ary_RE=top.BS_lid['BS_lname_ary_RE'];
    BSFU_lid_ary=top.BSFU_lid['BSFU_lid_ary'];
    BSFU_lid_type=top.BSFU_lid['BSFU_lid_type'];
    BSFU_lname_ary=top.BSFU_lid['BSFU_lname_ary'];
    FSBS_lid_ary=top.FSBS_lid['FSBS_lid_ary'];  
    FSBS_lname_ary=top.FSBS_lid['FSBS_lname_ary'];  
}catch(E){
    initlid_BS();
}
try{
    TN_lid_ary=top.TN_lid['TN_lid_ary'];
    TN_lid_type=top.TN_lid['TN_lid_type'];
    TN_lname_ary=top.TN_lid['TN_lname_ary'];
    TN_lid_ary_RE=top.TN_lid['TN_lid_ary_RE'];
    TN_lname_ary_RE=top.TN_lid['TN_lname_ary_RE'];
    TU_lid_ary=top.TU_lid['TU_lid_ary'];
    TU_lid_type=top.TU_lid['TU_lid_type'];
    TU_lname_ary=top.TU_lid['TU_lname_ary'];
    FSTN_lid_ary=top.FSTN_lid['FSTN_lid_ary'];  
    FSTN_lname_ary=top.FSTN_lid['FSTN_lname_ary'];  
}catch(E){
    initlid_TN();
}  
try{
    VB_lid_ary=top.VB_lid['VB_lid_ary'];
    VB_lid_type=top.VB_lid['VB_lid_type'];
    VB_lname_ary=top.VB_lid['VB_lname_ary'];
    VB_lid_ary_RE=top.VB_lid['VB_lid_ary_RE'];
    VB_lname_ary_RE=top.VB_lid['VB_lname_ary_RE'];
    VU_lid_ary=top.VU_lid['VU_lid_ary'];
    VU_lid_type=top.VU_lid['VU_lid_type'];
    VU_lname_ary=top.VU_lid['VU_lname_ary'];
    FSVB_lid_ary=top.FSVB_lid['FSVB_lid_ary'];
    FSVB_lname_ary=top.FSVB_lid['FSVB_lname_ary'];  
}catch(E){
    initlid_VB();
}  
try{
    OP_lid_ary=top.OP_lid['OP_lid_ary'];
    OP_lid_type=top.OP_lid['OP_lid_type'];
    OP_lname_ary=top.OP_lid['OP_lname_ary'];
    OP_lid_ary_RE=top.OP_lid['OP_lid_ary_RE'];
    OP_lname_ary_RE=top.OP_lid['OP_lname_ary_RE'];
    OM_lid_ary=top.OM_lid['OM_lid_ary'];
    OM_lid_type=top.OM_lid['OM_lid_type'];
    OM_lname_ary=top.OM_lid['OM_lname_ary'];
    FSOP_lid_ary=top.FSOP_lid['FSOP_lid_ary'];
    FSOP_lname_ary=top.FSOP_lid['FSOP_lname_ary'];  
}catch(E){
    initlid_OP();
}       


function initlid_FT(){
    top.FT_lid = new Array();
    top.FU_lid = new Array();
    top.FSFT_lid = new Array();
    top.FT_lid['FT_lid_ary']= FT_lid_ary='ALL';
    top.FT_lid['FT_lid_type']= FT_lid_type='';
    top.FT_lid['FT_lname_ary']= FT_lname_ary='ALL';
    top.FT_lid['FT_lid_ary_RE']= FT_lid_ary_RE='ALL';
    top.FT_lid['FT_lname_ary_RE']= FT_lname_ary_RE='ALL';
    top.FU_lid['FU_lid_ary']= FU_lid_ary='ALL';
    top.FU_lid['FU_lid_type']= FU_lid_type='';
    top.FU_lid['FU_lname_ary']= FU_lname_ary='ALL';
    top.FSFT_lid['FSFT_lid_ary']= FSFT_lid_ary='ALL';
    top.FSFT_lid['FSFT_lname_ary']= FSFT_lname_ary='ALL';
}
function initlid_BK(){
    top.BK_lid = new Array();
    top.BU_lid = new Array();
    top.FSBK_lid = new Array();
    top.BK_lid['BK_lid_ary']= BK_lid_ary='ALL';
    top.BK_lid['BK_lid_type']= BK_lid_type='';
    top.BK_lid['BK_lname_ary']= BK_lname_ary='ALL';
    top.BK_lid['BK_lid_ary_RE']= BK_lid_ary_RE='ALL';
    top.BK_lid['BK_lname_ary_RE']= BK_lname_ary_RE='ALL';
    top.BU_lid['BU_lid_ary']= BU_lid_ary='ALL';
    top.BU_lid['BU_lid_type']= BU_lid_type='';
    top.BU_lid['BU_lname_ary']= BU_lname_ary='ALL';
    top.FSBK_lid['FSBK_lid_ary']= FSBK_lid_ary='ALL';
    top.FSBK_lid['FSBK_lname_ary']= FSBK_lname_ary='ALL';       
}
function initlid_BS(){
    top.BS_lid = new Array();
    top.BSFU_lid = new Array();
    top.FSBS_lid = new Array(); 
    top.BS_lid['BS_lid_ary']= BS_lid_ary='ALL';
    top.BS_lid['BS_lid_type']= BS_lid_type='';
    top.BS_lid['BS_lname_ary']= BS_lname_ary='ALL';
    top.BS_lid['BS_lid_ary_RE']= BS_lid_ary_RE='ALL';
    top.BS_lid['BS_lname_ary_RE']= BS_lname_ary_RE='ALL';
    top.BSFU_lid['BSFU_lid_ary']= BSFU_lid_ary='ALL';
    top.BSFU_lid['BSFU_lid_type']= BSFU_lid_type='';
    top.BSFU_lid['BSFU_lname_ary']= BSFU_lname_ary='ALL';
    top.FSBS_lid['FSBS_lid_ary']= FSBS_lid_ary='ALL';
    top.FSBS_lid['FSBS_lname_ary']= FSBS_lname_ary='ALL';   
}
function initlid_TN(){
    top.TN_lid = new Array();
    top.TU_lid = new Array();
    top.FSTN_lid = new Array(); 
    top.TN_lid['TN_lid_ary']= TN_lid_ary='ALL';
    top.TN_lid['TN_lid_type']= TN_lid_type='';
    top.TN_lid['TN_lname_ary']= TN_lname_ary='ALL';
    top.TN_lid['TN_lid_ary_RE']= TN_lid_ary_RE='ALL';
    top.TN_lid['TN_lname_ary_RE']= TN_lname_ary_RE='ALL';
    top.TU_lid['TU_lid_ary']= TU_lid_ary='ALL';
    top.TU_lid['TU_lid_type']= TU_lid_type='';
    top.TU_lid['TU_lname_ary']= TU_lname_ary='ALL';
    top.FSTN_lid['FSTN_lid_ary']= FSTN_lid_ary='ALL';   
    top.FSTN_lid['FSTN_lname_ary']= FSTN_lname_ary='ALL';   
}
function initlid_VB(){
    top.VB_lid = new Array();
    top.VU_lid = new Array();
    top.FSVB_lid = new Array(); 
    top.VB_lid['VB_lid_ary']= VB_lid_ary='ALL';
    top.VB_lid['VB_lid_type']= VB_lid_type='';
    top.VB_lid['VB_lname_ary']= VB_lname_ary='ALL';
    top.VB_lid['VB_lid_ary_RE']= VB_lid_ary_RE='ALL';
    top.VB_lid['VB_lname_ary_RE']= VB_lname_ary_RE='ALL';
    top.VU_lid['VU_lid_ary']= VU_lid_ary='ALL';
    top.VU_lid['VU_lid_type']= VU_lid_type='';
    top.VU_lid['VU_lname_ary']= VU_lname_ary='ALL';
    top.FSVB_lid['FSVB_lid_ary']= FSVB_lid_ary='ALL';
    top.FSVB_lid['FSVB_lname_ary']= FSVB_lname_ary='ALL'    
}
function initlid_OP(){
    top.OP_lid = new Array();
    top.OM_lid = new Array();
    top.FSOP_lid = new Array(); 
    top.OP_lid['OP_lid_ary']= OP_lid_ary='ALL';
    top.OP_lid['OP_lid_type']= OP_lid_type='';
    top.OP_lid['OP_lname_ary']= OP_lname_ary='ALL';
    top.OP_lid['OP_lid_ary_RE']= OP_lid_ary_RE='ALL';
    top.OP_lid['OP_lname_ary_RE']= OP_lname_ary_RE='ALL';
    top.OM_lid['OM_lid_ary']= OM_lid_ary='ALL';
    top.OM_lid['OM_lid_type']= OM_lid_type='';
    top.OM_lid['OM_lname_ary']= OM_lname_ary='ALL';
    top.FSOP_lid['FSOP_lid_ary']= FSOP_lid_ary='ALL';
    top.FSOP_lid['FSOP_lname_ary']= FSOP_lname_ary='ALL';   
}
</script>
<frameset rows="118,*" cols="*" frameborder="NO" border="0" framespacing="0"> 
  <frame name="header" scrolling="NO" noresize src="<?php echo BROWSER_IP?>/app/member/OP_header.php?uid=<?php echo $uid?>&showtype=<?php echo $header?>&langx=<?php echo $langx?>&mtype=<?php echo $mtype?>" >
  <frameset cols="240,1*" frameborder="NO" border="0" framespacing="0"> 
    <frame name="mem_order" noresize scrolling="AUTO" src="<?php echo BROWSER_IP?>/app/member/select.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&mtype=<?php echo $mtype?>">
    <frame name="body" src="<?php echo BROWSER_IP?>/app/member/<?php echo $ShowType?>/index.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&mtype=<?php echo $mtype?>&league_id=">
  </frameset>
</frameset><noframes></noframes>
<noframes><body bgcolor="#FFFFFF">

</body></noframes>
</html>
