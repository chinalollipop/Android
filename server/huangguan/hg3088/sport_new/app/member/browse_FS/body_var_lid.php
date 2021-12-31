<?php
session_start();
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include "../include/address.mem.php";
require ("../include/config.inc.php");

$uid=$_SESSION['Oid'];
$langx=$_SESSION['langx'];
$mtype=$_REQUEST['mtype'];
$rtype=trim($_REQUEST['rtype']);
$FStype=trim($_REQUEST['FStype']);
require ("../include/traditional.$langx.inc.php");

//if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
//	echo "<script>top.location.href='/'</script>";
//	exit;
//}
?>

<html>
<head>
<title>Select League</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="../../../style/member/mem_body_ft.css?v=<?php echo AUTOVER; ?>" type="text/css">
</head>

<body id="LEG" class="var_lid_<?php echo TPL_FILE_NAME;?>"  onLoad="onLoad();" onSelectStart="self.event.returnValue=false" oncontextmenu="self.event.returnValue=false">
<form name='lid_form' onSubmit="return false;">
<table border="0" cellpadding="0" cellspacing="0" id="box">
  <tr>
    <td class="leg_top">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="30%"><h1><input type=checkbox value=all id=sall onClick="selall();">全选</h1></td>
            <td class="btn_td">
            <!--<input type="submit" name="button" id="button" value="取消" class="enter_btn" onClick="back();">&nbsp;-->
            <input type="submit" name="button" id="button" value="提交" class="enter_btn" onClick="chk_league();">
            </td>
            <td class="close_td"><span class="close_box" onClick="back();">取消</span></td>
          </tr>
        </table>
      
    </td>
  </tr>
  <tr>
    <td>
    <div class="leg_mem">
      <table border="0" cellspacing="1" cellpadding="0" class="leg_game">  
<?php
$m_date=date('Y-m-d');
$mysql = "select *,count(distinct M_League) FROM `".DBPREFIX.SPORT_FLUSH_FS_MATCH_TABLE."` WHERE `GType`='$FStype' and `M_Start` > now( ) and M_League!='' group by M_League";
//echo $mysql; die;
$result = mysqli_query($dbLink, $mysql);
$cou=mysqli_num_rows($result);
$i=0;
while ($league=mysqli_fetch_assoc($result)) {
   $i = $i + 3;
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
          <td class="league"><div style='display:none;'><input type=checkbox value="" id="LID<?php echo $league['MID']?>" onClick="chk_all(this.checked);"><font title=""></font></div></td>
          <td class="league"><div style='display:none;'><input type=checkbox value="" id="LID<?php echo $league['MID']?>" onClick="chk_all(this.checked);"><font title=""></font></div></td>
          <td class="league"><div style='display:none;'><input type=checkbox value="" id="LID<?php echo $league['MID']?>" onClick="chk_all(this.checked);"><font title=""></font></div></td>
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
  <!--<input type="submit" name="button" id="button" value="取消" class="enter_btn" onClick="back();">-->&nbsp;
  <input type="submit" name="button" id="button" value="提交" class="enter_btn" onClick="chk_league();">
</div>

</form>

<script>
	function in_array(val,ary){
	    for(var k=0;k<=ary.length;k++){
	        if(val==ary[k])return true;
	    }
	    return false;
	}

    function onLoad(){
        var len =lid_form.elements.length;
        parent.setleghi(document.body.scrollHeight);
        if(parent.parent.ch_leaguearray=='ALL' || parent.parent.ch_leaguearray==''){
            lid_form.sall.checked='true';
            for (var i = 1; i < len; i++) {
                var e = lid_form.elements[i];
                if (e.id.substr(0,3)=="LID") e.checked = 'true';
            }
        }else{
        	var M_League = parent.parent.ch_leaguearray.split(',');
        	for (var i = 1; i < len; i++) {
                var e = lid_form.elements[i];
                if(e.id.substr(0,3)=="LID"&&e.type=='checkbox') {
                    if(in_array(e.value,M_League)){
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
            for (var i = 1; i < len; i++) {
                var e = lid_form.elements[i];
                if (e.id.substr(0,3)=="LID"&&e.type=='checkbox'&&e.checked) {
                    strlid+=e.id.substr(3,e.id.length)+'-';
                    strlname+=e.value+'-';
                    gcount++;
                }
            }
       if(lid_form.sall.checked==false){ 
    	   parent.LeaguesName=strlid;
    	}else{
    		parent.LeaguesName='ALL';
        }
        back();
    }
    function back(){
        parent.LegBack();
    }
    
</script>
</body>
</html>