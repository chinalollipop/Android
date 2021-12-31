<?php
if(!defined('PHPYOU_VER')) {
	exit('�Ƿ�����');
}
//�޸���Ϣ
if ($_GET['act']=="���") {

if ($_POST['rs1']<=0) {
  echo "<script>alert('�Բ��𣬻�Ա����!');window.history.go(-1);</script>"; 
  exit;
    }

if (empty($_POST['kauser'])) {
       
  echo "<script>alert('�û�������Ϊ��!');window.history.go(-1);</script>"; 
  exit;
    }
	
	
if (empty($_POST['kapassword'])) {
       
  echo "<script>alert('���벻��Ϊ��!');window.history.go(-1);</script>"; 
  exit;
    }
if (empty($_POST['xy'])) {
       
  echo "<script>alert('����޶��Ϊ��!');window.history.go(-1);</script>"; 
  exit;
    }
	if ($_POST['cs']>$_POST['kyx']) {
       
  echo "<script>alert('���ö�ȳ����������ö�!');window.history.go(-1);</script>"; 
  exit;
    }
	
	if ($_POST['tv6']=="��") {$stat=0;}else{$stat=1;  }
	$result = mysqli_query($dbLink,"select count(*) from ka_guan  where kauser='".$_POST['kauser']."'  order by id desc");   
$num = mysql_result($result,"0");

if($num!=0){
   echo "<script>alert('��һ�û������ѱ�ռ�ã���������룡!');window.history.go(-1);</script>"; 
  exit;
}
$result = mysqli_query($dbLink,"select count(*) from ka_mem  where kauser='".$_POST['kauser']."'  order by id desc");   
$num = mysql_result($result,"0");

if($num!=0){
   echo "<script>alert('��һ�û������ѱ�ռ�ã���������룡!');window.history.go(-1);</script>"; 
  exit;
}

$result = mysqli_query($dbLink,"select count(*) from ka_zi  where kauser='".$_POST['kauser']."'  order by id desc");   
$num = mysql_result($result,"0");

if($num!=0){
   echo "<script>alert('��һ�û������ѱ�ռ�ã���������룡!');window.history.go(-1);</script>"; 
  exit;
}
$pass = md5($_POST['kapassword']);
 $text=date("Y-m-d H:i:s");
 $ip=$_SERVER["REMOTE_ADDR"];
$result=mysqli_query($dbLink,"select * from ka_guan where id=".$_POST['danid']."  order by id"); 
$row=mysqli_fetch_assoc($result);
$guan=$row['guan'];
$guanid=$row['guanid'];
$zongid=$row['zongid'];
$zong=$row['zong'];
$danid=$row['id'];
$dan=$row['kauser'];
$dan_zc=$row['sf']/10;
$zong_zc=$row['sj']/10;
$results=mysqli_query($dbLink,"select * from ka_guan where id=".$zongid."  order by id"); 
$rows=mysqli_fetch_assoc($results);
$guan_zc=$rows['sj']/10;

$dagu_zc=10-$guan_zc-$dan_zc-$zong_zc;

$sql="INSERT INTO  ka_mem set kapassword='".$pass."',kauser='".$_POST['kauser']."',xm='".$_POST['xm']."',cs='".$_POST['cs']."',ts='".$_POST['cs']."',guan='".$guan."',zong='".$zong."',dan='".$dan."',stat='".$stat."',xy='".$_POST['xy']."',guanid='".$guanid."',zongid='".$zongid."',danid='".$danid."',look=0,adddate='".$text."',slogin='".$text."',zlogin='".$text."',sip='".$ip."',zip='".$ip."',abcd='".$_POST['abcd']."',dan_zc='".$dan_zc."',guan_zc='".$guan_zc."',zong_zc='".$zong_zc."',dagu_zc='".$dagu_zc."' ,ops='".$_POST['ops']."',opd='".$_POST['opd']."',opp='".$_POST['opp']."' ";


	
$exe=mysqli_query($dbMasterLink,$sql) or  die("���ݿ��޸ĳ���");

$result=mysqli_query($dbLink,"select * from ka_mem where  kauser='".$_POST['kauser']."'  order by id desc"); 
$row=mysqli_fetch_assoc($result);
$SoftID=$row['id'];


$result = mysqli_query($dbLink,"select * from  ka_quota where lx=0 and userid=".$danid." and flag=0 order by id ");   
$t=0;
while($image = mysqli_fetch_assoc($result)){
if ($_POST['abcd']=="A"){$yg=$image['yg'];}
if ($_POST['abcd']=="B"){$yg=$image['ygb'];}
if ($_POST['abcd']=="C"){$yg=$image['ygc'];}
if ($_POST['abcd']=="D"){$yg=$image['ygd'];}

$exe=mysqli_query($dbMasterLink,"INSERT INTO ka_quota Set yg='".$yg."',ygb=0,ygc=0,ygd=0,xx='".$image['xx']."',xxx='".$image['xxx']."',username='".$_POST['kauser']."',userid='".$SoftID."',lx=0,flag=1,guanid='".$guanid."',zongid='".$zongid."',danid='".$danid."',memid='".$SoftID."',ds='".$image['ds']."',abcd='".$_POST['abcd']."',style='".$image['style']."'");

}


echo "<script>alert('��Ա��ӳɹ�!');window.location.href='index.php?action=mem_add';</script>"; 
exit;

}

if ($_GET['id']!="") {


$result=mysqli_query($dbLink,"select id,kauser,sf,cs,tmb,rs   from ka_guan where  id=".$_GET['id']." and lx=3"); 
$row=mysqli_fetch_assoc($result);
if ($row!=""){

$result1 = mysqli_query($dbLink,"Select SUM(cs) As sum_m  From ka_mem Where    danid=".$row['id']." order by id desc");
	$rsw = mysqli_fetch_assoc($result1);
	if ($rsw[0]<>""){$mumu=$rsw[0];}else{$mumu=0;}
	
	 $result1 = mysqli_query($dbLink,"Select SUM(sum_m) As sum_m   From ka_tan Where kithe=".$Current_Kithe_Num." and   username='".$row['kauser']."' order by id desc");
	$rsw = mysqli_fetch_assoc($result1);
	if ($rsw[0]<>""){$mkmk=$rsw[0];}else{$mkmk=0;}
	$result1 = mysqli_query($dbLink,"Select Count(ID) As memnum2 From ka_mem Where danid=".$row['id']." order by id desc");
	$rsw = mysqli_fetch_assoc($result1);
	if ($rsw[0]<>""){$rs1=$rsw[0];}else{$rs1=0;}
	$rs1=$row['rs']-$rs1;
	
$tmb=$row['tmb'];
	

$danid=$row['id'];
$maxnum=$row['cs']-$mumu-$mkmk;
$istar=0;
$iend=$row['sf'];


}else{
$maxnum=2000000000;
$istar=0;
$iend=100;
$tmb=0;

}







}

?>




<link rel="stylesheet" href="images/xp.css?v=<?php echo AUTOVER; ?>" type="text/css">
<script language="javascript" type="text/javascript" src="js_admin.js?v=<?php echo AUTOVER; ?>"></script>
<script language="JavaScript" src="tip.js?v=<?php echo AUTOVER; ?>"></script>

<style type="text/css">
<!--
.style1 {
	color: #666666;
	font-weight: bold;
}
.style2 {color: #FF0000}
.STYLE3 {
	color: #FFFFFF;
	font-weight: bold;
}
-->
</style>
<div align="center">
<link rel="stylesheet" href="xp.css?v=<?php echo AUTOVER; ?>" type="text/css">
<script language="javascript" type="text/javascript" src="js_admin.js?v=<?php echo AUTOVER; ?>"></script>
<script src="inc/forms.js?v=<?php echo AUTOVER; ?>"></script>
<script language="JavaScript" type="text/JavaScript">
function SelectAllPub() {
	for (var i=0;i<document.form1.flag.length;i++) {
		var e=document.form1.flag[i];
		e.checked=!e.checked;
	}
}
function SelectAllAdm() {
	for (var i=0;i<document.form1.flag.length;i++) {
		var e=document.form1.flag[i];
		e.checked=!e.checked;
	}
}
</script>
<SCRIPT>
function LoadBody(){

}
function SubChk()
{
	
 	if(document.all.temppid.value=='')
 		{ document.all.temppid.focus(); alert("��ѡ���ϼ�!!"); return false; }
		
			if(document.all.xy.value=='')
 		{ document.all.xy.focus(); alert("����������޶�!!"); return false; }
		
		
		if(document.all.kapassword.value=='')
 		{ document.all.kapassword.focus(); alert("�������������!!"); return false; }
		
		if(document.all.xm.value=='')
 		{ document.all.xm.focus(); alert("�������������!!"); return false; }
  	
 	
 	if(document.all.kauser.value=='')
 		{ document.all.alias.focus(); alert("�û������������!!"); return false; }
		
		
  	if(document.all.cs.value=='')
		{ document.all.maxcredit.focus(); alert("�����ö�����������!!"); return false; }
 	
	if(!confirm("�Ƿ�ȷ��д���Ա?")){
  		return false;
 	}
}

function roundBy(num,num2) {
	return(Math.floor((num)*num2)/num2);
}
function show_count(w,s) {
	//alert(w+' - '+s);
	var org_str=document.all.ag_count.innerHTML
	if (s!=''){
		switch(w){
			//case 0:document.all.ag_count.innerHTML = s+org_str.substr(1,4);break;
			case 1:document.all.ag_count.innerHTML = org_str.substr(0,0)+s+org_str.substr(1,7);break;
			case 2:document.all.ag_count.innerHTML = org_str.substr(0,1)+s+org_str.substr(2,7);break;
			case 3:document.all.ag_count.innerHTML = org_str.substr(0,2)+s+org_str.substr(3,7);break;
			case 4:document.all.ag_count.innerHTML = org_str.substr(0,3)+s+org_str.substr(4,7);break; 
			case 5:document.all.ag_count.innerHTML = org_str.substr(0,4)+s+org_str.substr(5,7);break;
			case 6:document.all.ag_count.innerHTML = org_str.substr(0,5)+s+org_str.substr(6,7);break;
			case 7:document.all.ag_count.innerHTML = org_str.substr(0,6)+s+org_str.substr(7,7);break; }
	}
}
function changelocation(locationid,result)
{
var onecount;
subcat = new Array();
   
    document.testFrm.zc.length = 1; 
	    var locationid=locationid;
    var i;
		var k
	   for (j=10;j.toFixed(3)<=(result-locationid).toFixed(3);j=j+10)
   {
   		document.testFrm.zc.options[document.testFrm.zc.length] = new Option((j).toFixed(0)+"%");
	}
    
}
function changep(pid)
{
	var pp=pid.split(",");
	document.testFrm.guanid.value = pp[0];
	document.testFrm.kyx.value = pp[2];
	t=parseInt(pp[1]);
    document.testFrm.zc.length = 1; 
	for (j=10;j<=(t).toFixed(3);j=j+10)
   {
   		document.testFrm.zc.options[document.testFrm.zc.length] = new Option((j).toFixed(0)+"%");
	}
    document.testFrm.fei_max.length = 1; 
	for (j=10;j<=(t).toFixed(3);j=j+10)
   {
   		document.testFrm.fei_max.options[document.testFrm.fei_max.length] = new Option((j).toFixed(0)+"%");
	}
}

function changep1(pid)
{
var pp=pid.split(",");

	document.testFrm.winloss.value = pp[0];
	document.testFrm.bank.value = pp[1];
document.all.ag_count.innerHTML =pp[1];
}



function CountGold(gold,type,rtype){

goldvalue = gold.value;

str1="kyx";
zmzm=document.all[str1].value;
if (goldvalue=='') goldvalue=0;

if (rtype=='SP' && (eval(goldvalue) > eval(zmzm))) {gold.focus(); alert("�Բ���,�ܴ��������ö��������� : "+eval(zmzm)+"!!"); return false;}



}


function CountGold1(gold,type,rtype,bb){

goldvalue = gold.value;


if (goldvalue=='') goldvalue=0;

if (rtype=='SP' && (eval(goldvalue) > eval(bb))) {gold.focus(); alert("�Բ���,ֹ����߲��ܳ����ϼ��޶�: "+eval(bb)+"!!"); return false;}



}
</SCRIPT>
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr class="tbtitle">
    <td width="29%"><span class="STYLE3">��ӻ�Ա</span></td>
    <td width="34%">&nbsp;</td>
    <td width="37%">&nbsp;</td>
  </tr>
  <tr >
    <td height="5" colspan="3"></td>
  </tr>
</table>
<table width="99%"  border="1" cellpadding="2" cellspacing="1" bordercolor="#ECE9D8">
  <form name=testFrm onSubmit="return SubChk()" method="post" action="index.php?action=mem_add&act=���&id=<?php echo $_GET['id']?>">
  <tr>
    <td height="30" align="right" bordercolor="#CCCCCC" bgcolor="#FDF4CA">�ϼ������û���</td>
    <td height="30" bordercolor="#CCCCCC"><input name="danid" type="hidden" value="<?php echo $danid?>" />
        <select class="zaselect_ste" name="temppid" onchange="var jmpURL=this.options[this.selectedIndex].value ; if(jmpURL!='') {window.location=jmpURL;} else {this.selectedIndex=0 ;}">
          <option value="" ></option>
          <?php
		$result = mysqli_query($dbLink,"select id,kauser,sf,cs  from ka_guan  where lx=3");   
while($image = mysqli_fetch_assoc($result)){


$result1 = mysqli_query($dbLink,"Select SUM(cs) As sum_m  From ka_mem Where    danid=".$image['id']." order by id desc");
	$rsw = mysqli_fetch_assoc($result1);
	if ($rsw[0]<>""){$mumul=$rsw[0];}else{$mumul=0;}
	
	 $result1 = mysqli_query($dbLink,"Select SUM(sum_m) As sum_m   From ka_tan Where kithe=".$Current_Kithe_Num." and   username=".$image['kauser']." order by id desc");
	$rsw = mysqli_fetch_assoc($result1);
	if ($rsw[0]<>""){$mkmkl=$rsw[0];}else{$mkmkl=0;}
	
	$cscs=$image['cs']-$mumul-$mkmkl;
	
			   
			     echo "<OPTION value=index.php?action=mem_add&id=".$image['id'];
				
				
				 if ($danid!="") {
				 if ($danid==$image['id']) {
				  echo " selected=selected ";
				  }				
				}
				
				 echo ">".$image['kauser']."--".$cscs."</OPTION>";
				 
				 
			  }
		?>
        </select>
        <span class="STYLE2">*(����ѡ����ϼ�)</span></td>
    <td height="30" align="right" bordercolor="#CCCCCC" bgcolor="#FDF4CA">��Ա�̿ڣ�</td>
    <td height="30" bordercolor="#CCCCCC"><select name="abcd" id="abcd">
      <option value="A" selected="selected">A��</option>
      <option value="B">B��</option>
      <option value="C">C��</option>
      <option value="D">D��</option>
    </select> &nbsp;&nbsp;&nbsp;</td>
  </tr>
  <tr>
    <td width="11%" height="30" align="right" bordercolor="#CCCCCC" bgcolor="#FDF4CA">�˺ţ�</td>
    <td width="27%" bordercolor="#CCCCCC"><input name="kauser" type="text" class="input1"  id="kauser" />
        <span class="STYLE2"> *</span></td>
    <td width="9%" height="30" align="right" bordercolor="#CCCCCC" bgcolor="#FDF4CA">������</td>
    <td width="53%" bordercolor="#CCCCCC"><input name="xm" type="text" class="input1"  id="xm" />
        <span class="STYLE2">*</span> </td>
  </tr>
  <tr>
    <td height="30" align="right" bordercolor="#CCCCCC" bgcolor="#FDF4CA">���룺</td>
    <td bordercolor="#CCCCCC"><input name="kapassword" type="password" class="input1"  id="kapassword" />
        <span class="STYLE2">*</span> </td>
    <td align="right" bordercolor="#CCCCCC" bgcolor="#FDF4CA">�����ö</td>
    <td bordercolor="#CCCCCC"><input 
					  onblur="return CountGold(this,'blur','SP');"
					    
					    onkeyup="return CountGold(this,'keyup');" 
						
					  name="cs" type="text" class="input1"  id="cs" value="0" />
      �������ö�ȣ�
      <input type="text" name="kyx" class="input1"  readonly="readonly" value="<?php echo $maxnum?>" /></td>
  </tr>
  <tr>
    <td height="30" align="right" bordercolor="#CCCCCC" bgcolor="#FDF4CA">����޶</td>
    <td bordercolor="#CCCCCC"><span class="STYLE2">
      <input name="xy" type="text" class="input1"  id="xy" size="8" />
      *����ע����޶</span></td>
    <td height="30" align="right" bordercolor="#CCCCCC" bgcolor="#FDF4CA">״̬��</td>
    <td bordercolor="#CCCCCC"><input type="hidden" name="tv6" value="��" />
        <img src="images/icon_21x21_selectboxon.gif" name="tv6_b" align="absmiddle" class="cursor" id="tv6_b" onclick="javascript:ra_select('tv6')" />(����/��ֹ)<span class="STYLE2">*</span></td>
  </tr>
  <tr>
    <td height="30" align="right" bordercolor="#CCCCCC" bgcolor="#FDF4CA">�Է�������B��</td>
    <td height="30" bordercolor="#CCCCCC"><select name="tmb" id="tmb">
        <?php if ($tmb!=1){?>
        <option value="0" selected="selected">����</option>
        <?php }?>
        <option value="1">������</option>
      </select>
      <span class="STYLE2">
      <input name="rs1" type="hidden" id="rs1" value="<?php echo $rs1?>" />
      </span>    <?php echo $rs1?></td>
    <td bordercolor="#CCCCCC">&nbsp;</td>
    <td bordercolor="#CCCCCC">&nbsp;</td>
  </tr>
  <tr>
    <td height="30" bordercolor="#CCCCCC" bgcolor="#FDF4CA">&nbsp;</td>
    <td colspan="3" bordercolor="#CCCCCC"><br />
        <table width="100" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="6"></td>
          </tr>
        </table>
      <input  class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" type="submit" name="Submit" value="�����Ա" />
        <br />
        <table width="100" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="10"></td>
          </tr>
      </table></td>
  </tr>
  </form>
</table>
</div>
