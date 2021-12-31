<?php
if(!defined('PHPYOU')) {
	exit('�Ƿ�����');
}
 if (strpos($_SESSION['flag'],'02') ){}else{ 
echo "<center>��û�и�Ȩ�޹���!</center>";
exit;}
function show_xxsx222($i){   
   $result4=mysqli_query($dbLink,"Select id,m_number From ka_sxnumber where id=".$i." Order By id"); 
$ka_configg4=mysqli_fetch_assoc($result4); 
return $ka_configg4['m_number'];
   }

if ($_GET['fen']=="fen"){
$locked=$_GET['fid'];
for ($tt=1; $tt<=49; $tt++) { 
$exe=mysqli_query($dbLink,"update ka_bl set locked=".$locked." where class1='����' and class3='".$tt."' ");
}

}

if ($_GET['ids']!="") {$ids=$_GET['ids'];}else{$ids="��A";}
if ($ids=="��A"){$z2color="494949";
$z1color="ff0000";}else{
$z1color="494949";
$z2color="ff0000";}
if ($_GET['save']=="save") {

if (empty($_POST['bl'])) {       
  echo "<script>alert('���ʲ���Ϊ��!');window.history.go(-1);</script>"; 
  exit;
}
for ($tt=1; $tt<=49; $tt++) { 
$num=$_POST['bl']+ka_config(3);
$num1=$_POST['bl']-ka_config(3);

///ȫ��
if ($_POST['dx']=="ȫ��") {

if ($_GET['ids']=="��A") {

$exe=mysqli_query($dbLink,"update ka_bl set rate=".$_POST['bl'].",blrate=".$_POST['bl']." where class2='".$ids."' and  class3='".$tt."'");
$exe=mysqli_query($dbLink,"update ka_bl set rate=".$num.",blrate=".$num." where class2='��B' and  class3='".$tt."'");

}else{
$exe=mysqli_query($dbLink,"update ka_bl set rate=".$_POST['bl'].",blrate=".$_POST['bl']." where class2='".$ids."' and  class3='".$tt."'");
$exe=mysqli_query($dbLink,"update ka_bl set rate=".$num1.",blrate=".$num1." where class2='��A' and  class3='".$tt."'");
}
}
///��
if ($_POST['dx']=="��") {
if ($tt%2==1){
if ($_GET['ids']=="��A") {
$exe=mysqli_query($dbLink,"update ka_bl set rate=".$_POST['bl'].",blrate=".$_POST['bl']." where class2='".$ids."' and  class3='".$tt."'");
$exe=mysqli_query($dbLink,"update ka_bl set rate=".$num.",blrate=".$num." where class2='��B' and  class3='".$tt."'");

}else{
$exe=mysqli_query($dbLink,"update ka_bl set rate=".$_POST['bl'].",blrate=".$_POST['bl']." where class2='".$ids."' and  class3='".$tt."'");
$exe=mysqli_query($dbLink,"update ka_bl set rate=".$num1.",blrate=".$num1." where class2='��A' and  class3='".$tt."'");
}
}
}
///˫
if ($_POST['dx']=="˫") {
if ($tt%2==0){
if ($_GET['ids']=="��A") {
$exe=mysqli_query($dbLink,"update ka_bl set rate=".$_POST['bl'].",blrate=".$_POST['bl']." where class2='".$ids."' and  class3='".$tt."'");
$exe=mysqli_query($dbLink,"update ka_bl set rate=".$num.",blrate=".$num." where class2='��B' and  class3='".$tt."'");

}else{
$exe=mysqli_query($dbLink,"update ka_bl set rate=".$_POST['bl'].",blrate=".$_POST['bl']." where class2='".$ids."' and  class3='".$tt."'");
$exe=mysqli_query($dbLink,"update ka_bl set rate=".$num1.",blrate=".$num1." where class2='��A' and  class3='".$tt."'");
}
}
}
///��
if ($_POST['dx']=="��") {
if ($tt>=25){
if ($_GET['ids']=="��A") {
$exe=mysqli_query($dbLink,"update ka_bl set rate=".$_POST['bl'].",blrate=".$_POST['bl']." where class2='".$ids."' and  class3='".$tt."'");
$exe=mysqli_query($dbLink,"update ka_bl set rate=".$num.",blrate=".$num." where class2='��B' and  class3='".$tt."'");

}else{
$exe=mysqli_query($dbLink,"update ka_bl set rate=".$_POST['bl'].",blrate=".$_POST['bl']." where class2='".$ids."' and  class3='".$tt."'");
$exe=mysqli_query($dbLink,"update ka_bl set rate=".$num1.",blrate=".$num1." where class2='��A' and  class3='".$tt."'");
}
}
}
///��
if ($_POST['dx']=="С") {
if ($tt<=24){
if ($_GET['ids']=="��A") {
$exe=mysqli_query($dbLink,"update ka_bl set rate=".$_POST['bl'].",blrate=".$_POST['bl']." where class2='".$ids."' and  class3='".$tt."'");
$exe=mysqli_query($dbLink,"update ka_bl set rate=".$num.",blrate=".$num." where class2='��B' and  class3='".$tt."'");

}else{
$exe=mysqli_query($dbLink,"update ka_bl set rate=".$_POST['bl'].",blrate=".$_POST['bl']." where class2='".$ids."' and  class3='".$tt."'");
$exe=mysqli_query($dbLink,"update ka_bl set rate=".$num1.",blrate=".$num1." where class2='��A' and  class3='".$tt."'");
}
}
}
///�첨
if ($_POST['dx']=="�첨") {
if (Get_bs_Color($tt)=="r"){
if ($_GET['ids']=="��A") {
$exe=mysqli_query($dbLink,"update ka_bl set rate=".$_POST['bl'].",blrate=".$_POST['bl']." where class2='".$ids."' and  class3='".$tt."'");
$exe=mysqli_query($dbLink,"update ka_bl set rate=".$num.",blrate=".$num." where class2='��B' and  class3='".$tt."'");

}else{
$exe=mysqli_query($dbLink,"update ka_bl set rate=".$_POST['bl'].",blrate=".$_POST['bl']." where class2='".$ids."' and  class3='".$tt."'");
$exe=mysqli_query($dbLink,"update ka_bl set rate=".$num1.",blrate=".$num1." where class2='��A' and  class3='".$tt."'");
}
}
}
///����
if ($_POST['dx']=="����") {
if (Get_bs_Color($tt)=="b"){
if ($_GET['ids']=="��A") {
$exe=mysqli_query($dbLink,"update ka_bl set rate=".$_POST['bl'].",blrate=".$_POST['bl']." where class2='".$ids."' and  class3='".$tt."'");
$exe=mysqli_query($dbLink,"update ka_bl set rate=".$num.",blrate=".$num." where class2='��B' and  class3='".$tt."'");

}else{
$exe=mysqli_query($dbLink,"update ka_bl set rate=".$_POST['bl'].",blrate=".$_POST['bl']." where class2='".$ids."' and  class3='".$tt."'");
$exe=mysqli_query($dbLink,"update ka_bl set rate=".$num1.",blrate=".$num1." where class2='��A' and  class3='".$tt."'");
}
}
}
///�̲�
if ($_POST['dx']=="�̲�") {
if (Get_bs_Color($tt)=="g"){
if ($_GET['ids']=="��A") {
$exe=mysqli_query($dbLink,"update ka_bl set rate=".$_POST['bl'].",blrate=".$_POST['bl']." where class2='".$ids."' and  class3='".$tt."'");
$exe=mysqli_query($dbLink,"update ka_bl set rate=".$num.",blrate=".$num." where class2='��B' and  class3='".$tt."'");

}else{
$exe=mysqli_query($dbLink,"update ka_bl set rate=".$_POST['bl'].",blrate=".$_POST['bl']." where class2='".$ids."' and  class3='".$tt."'");
$exe=mysqli_query($dbLink,"update ka_bl set rate=".$num1.",blrate=".$num1." where class2='��A' and  class3='".$tt."'");
}
}
}

}//FOR
}//����

if ($_GET['act']=="�޸�") {
for ($tt=1; $tt<=64; $tt++) {
if (empty($_POST['Num_'.$tt])) {       
  echo "<script>alert('���ʲ���Ϊ��:".$_POST['Num_'.$tt]."/".$tt."!');window.history.go(-1);</script>"; 
  exit;
}

 }
 
 
 for ($tt=1; $tt<=66; $tt++) {
 
 $num=$_POST['Num_'.$tt];
 $num1=$num+ka_config(3);
 $num2=$num-ka_config(3);
 
  $num3=$num+ka_config(4);
 $num4=$num-ka_config(4);
  $num5=$num+ka_config(5);
 $num6=$num-ka_config(5);
 
 $class3=$_POST['class3_'.$tt];
 
 
$exe=mysqli_query($dbLink,"update ka_bl  set rate=".$num.",blrate=".$num." where class2='".$ids."' and  class3='".$class3."'");

if ($_GET['ids']=="��A") {
if ($tt<=49){


$exe=mysqli_query($dbLink,"update ka_bl set rate=".$num1.",blrate=".$num1." where class2='��B' and  class3='".$_POST['class3_'.$tt]."'");}elseif ($tt<=55){
$exe=mysqli_query($dbLink,"update ka_bl set rate=".$num3.",blrate=".$num3." where class2='��B' and  class3='".$_POST['class3_'.$tt]."'");
}else{
$exe=mysqli_query($dbLink,"update ka_bl set rate=".$num5.",blrate=".$num5." where class2='��B' and  class3='".$_POST['class3_'.$tt]."'");

}



}else{
if ($tt<=49){
$exe=mysqli_query($dbLink,"update ka_bl set rate=".$num2.",blrate=".$num2." where class2='��A' and  class3='".$_POST['class3_'.$tt]."'");}elseif ($tt<=55){
$exe=mysqli_query($dbLink,"update ka_bl set rate=".$num4.",blrate=".$num4." where class2='��A' and  class3='".$_POST['class3_'.$tt]."'");
}else{
$exe=mysqli_query($dbLink,"update ka_bl set rate=".$num6.",blrate=".$num6." where class2='��A' and  class3='".$_POST['class3_'.$tt]."'");

}



}
 
 
 
 

 }//for
 
 
echo "<script>alert('�޸ĳɹ�!');window.location.href='index.php?action=rake_tm&ids=".$ids."';</script>"; 
exit;
	
}//����

if ($_GET['savew']=="savew") {
if (empty($_POST['mf'])) {       
  echo "<script>alert('��ѡ����Ŀ!');window.history.go(-1);</script>"; 
  exit;
}
if (empty($_POST['money'])) {       
  echo "<script>alert('��������ֵ!');window.history.go(-1);</script>"; 
  exit;
}
$mv=$_POST['mv'];
$money=$_POST['money'];
	

$vvv=$_POST['mf'];

$ss=count($vvv);

for ($i=0;$i<=$ss-1;$i++){

$string.=$vvv[$i].",";}

$pc=explode(",",$string);
$ss1=count($pc);

for ($i=0;$i<=$ss1-2;$i++){

$string1.=intval($pc[$i]).",";}

$pc=explode(",",$string1);
$ss2=count($pc);



for ($i=0;$i<=$ss2-1;$i++){

if ($i==0){
$guolv=$pc[$i];
}else{
$uuu=$pc[$i];
$pc1=explode(",",$guolv);
$ss3=count($pc1);
$ff=0;

for ($f=0;$f<=$ss3;$f++){
if ($uuu==$pc1[$f]){
$ff=1;
}
} 
if ($ff==0){$guolv.=",".$uuu;}
}
}


$pc4=explode(",",$guolv);
$ss4=count($pc4);
for ($f=0;$f<$ss4;$f++){
if ($mv==0){
$exe=mysqli_query($dbLink,"update ka_bl set rate=rate-".$money." where class1='����'  and   class3='".$pc4[$f]."'");

}else{
$exe=mysqli_query($dbLink,"update ka_bl set rate=rate+".$money." where class1='����'  and   class3='".$pc4[$f]."'");

}


}







}//����



$result=mysqli_query($dbLink,"Select rate,class3,locked from ka_bl where class2='".$ids."' Order By id  LIMIT 66");
$ShowTable = array();
$y=0;
while($Image = mysqli_fetch_assoc($result)){
$y++;
array_push($ShowTable,$Image);

}

$drop_count=$y-1;




?>

<link rel="stylesheet" href="images/xp.css?v=<?php echo AUTOVER; ?>" type="text/css">
<SCRIPT language=JAVASCRIPT>

if(window.location.host!=top.location.host){top.location=window.location;} 
</SCRIPT>

<script>

function makeRequest(url) {

    http_request = false;
   
    if (window.XMLHttpRequest) {
   
        http_request = new XMLHttpRequest();
   
        if (http_request.overrideMimeType){
   
            http_request.overrideMimeType('text/xml');
   
        }
   
    } else if (window.ActiveXObject) {
   
        try{
       
            http_request = new ActiveXObject("Msxml2.XMLHTTP");
       
        } catch (e) {
       
            try {
           
                http_request = new ActiveXObject("Microsoft.XMLHTTP");
           
            } catch (e) {
       
            }
   
        }

     }
     if (!http_request) {
     
        alert("Your browser nonsupport operates at present, please use IE 5.0 above editions!");
       
        return false;
       
     }
 

//method init,no init();
 http_request.onreadystatechange = init;
 
 http_request.open('GET', url, true);

//Forbid IE to buffer memory
 http_request.setRequestHeader("If-Modified-Since","0");

//send count
 http_request.send(null);

//Updated every two seconds a page
 setTimeout("makeRequest('"+url+"')",<?php echo $ftime?>);

}


function init() {
 
    if (http_request.readyState == 4) {
   
        if (http_request.status == 0 || http_request.status == 200) {
       
            var result = http_request.responseText;
			
           
            if(result==""){
           
                result = "Access failure ";
           
            }
           
		   var arrResult = result.split("###");	
		   for(var i=0;i<66;i++)
{	   
		   arrTmp = arrResult[i].split("@@@");
		   


num1 = arrTmp[0]; //�ֶ�num1��ֵ
num2 = arrTmp[1]; //�ֶ�num2��ֵ
num3 = arrTmp[2]; //�ֶ�num1��ֵ
num4 = arrTmp[3]; //�ֶ�num2��ֵ
var bl,num;
bl="bl"+i;
num="Num_"+(i+1);
document.all[num].value=parseFloat(num2).toFixed(2);
document.all[bl].innerHTML=parseFloat(num2).toFixed(2);

var gold;
gold="gold"+i;
document.all[gold].innerHTML= "<font color=ff6600>"+num4+"</font>";
}
			
			
           
        } else {//http_request.status != 200
           
                alert("Request failed! ");
       
        }
   
    }
 
}


function UpdateRate(commandName,inputID,cellID,strPara)
{
	//���ܣ���strPara���������͸�rake_updateҳ�棬�������ؽ���ش�
	//���������	inputID,cellID:Ҫ��ʾ�������ݵ�ҳ��ؼ���
	//		strPara���������͸�rake_updateҳ��Ĳ���
	//class1:���1
	//ids:(��class2)�������Ϊ��A����B��qtqt:�������ȣ�lxlx��������1Ϊ�ӣ�����Ϊ��
	//class3:���������
	switch(commandName)
	{
		case "MODIFYRATE":	//��������
			{
				var strResult = sendCommand(commandName,"rake_update.php",strPara);
				
				if (strResult!="")
				{
					makeRequest('index.php?action=server&class1=����&class2=<?php echo $ids?>')
					document.all[inputID].value=parseFloat(strResult).toFixed(2);
					
				}
				break;
			}
		case "LOCK":		//�ر���Ŀ
			{


				var strResult=sendCommand(commandName,"rake_update.php",strPara);
				

				if (strResult!="")
				
				{
					if(strResult=='1')					
						document.all[inputID].checked=true;
					else
						document.all[inputID].checked=false;
				}else{
				
				
					document.all[inputID].checked=!document.all[inputID].checked;
				}
				break;
			}
		default:	//�������
	}
}
function sendCommand(commandName,pageURL,strPara)
{
	//���ܣ���pageURLҳ�淢�����ݣ�����ΪstrPara
	//���ش����������ص�����
	var oBao = new ActiveXObject("Microsoft.XMLHTTP");
	//�����ַ���+,%,&,=,?�ȵĴ������취.�ַ�������escape�����.
	oBao.open("GET",pageURL+"?commandName="+commandName+"&"+strPara,false);
	oBao.send();
	//�������˴����ص��Ǿ���escape������ַ���.
	var strResult = unescape(oBao.responseText);
	return strResult;
}


</script>


<SCRIPT language=javascript>


function adv_format(value,num) //��������
{
var a_str = formatnumber(value,num);
var a_int = parseFloat(a_str);
if (value.toString().length>a_str.length)
{
var b_str = value.toString().substring(a_str.length,a_str.length+1)
var b_int = parseFloat(b_str);
if (b_int<5)
{
return a_str
}
else
{
var bonus_str,bonus_int;
if (num==0)
{
bonus_int = 1;
}
else
{
bonus_str = "0."
for (var i=1; i<num; i++)
bonus_str+="0";
bonus_str+="1";
bonus_int = parseFloat(bonus_str);
}
a_str = formatnumber(a_int + bonus_int, num)
}
}
return a_str
}

function formatnumber(value,num) //ֱ��ȥβ
{
var a,b,c,i

a = value.toString();
b = a.indexOf('.');
c = a.length;
if (num==0)
{
if (b!=-1)
a = a.substring(0,b);
}
else
{
if (b==-1)
{
a = a + ".";
for (i=1;i<=num-1;i++)
a = a + "0";
}
else
{
a = a.substring(0,b+num+1);
for (i=c;i<=b+num-1;i++)
a = a + "0";
}
}
return a
}

var ball_color = Array(0,0,1,1,2,2,0,0,1,1,2,0,0,1,1,2,2,0,0,1,2,2,0,0,1,1,2,2,0,0,1,2,2,0,0,1,1,2,2,0,1,1,2,2,0,0,1,1,2);
var bcolor = Array('red','blue','green');

function sel_col_ball(color,sj)
{
	var c;
	var str1
	var zmzm
	var zmn=0.5
	var zmnn=0.01
	switch(color) {
		case 'blue':
			c = 1;
			break;
		case 'red':
			c = 0;
			break;
		case 'green':
			c = 2;
			break;
		case 'alal':
			c = 4;
			break;
		case 'all':
			c = 5;
			break;
		default:
			return;
			break;
	}
	
	
	
	if (c==4 ){
		
		
		
		 document.all.t_signle.value=adv_format(eval(document.all.t_signle.value+"+"+sj),2);
		 document.all.t_double.value=adv_format(eval(document.all.t_double.value+"+"+sj),2);
		 document.all.t_big.value=adv_format(eval(document.all.t_big.value+"+"+sj),2);
		 document.all.t_small.value=adv_format(eval(document.all.t_small.value+"+"+sj),2);
		 document.all.h_signle.value=adv_format(eval(document.all.h_signle.value+"+"+sj),2);
		 document.all.h_double.value=adv_format(eval(document.all.h_double.value+"+"+sj),2);
	
	
	}else{
	
	var m=0
	for(i=0; i<49 ;i++)
	{
				
		m++
		
	
			if (ball_color[i] == c)
		{
			
			str1="Num_"+m;
		zmzm=document.all[str1].value;
			zmzm=eval(zmzm+"+"+sj);
			
			 document.all[str1].value =zmzm ;
			
			
		}
}
		
		
		
		
	}
}


function sel_col_ball1(color,sj)
{
	var c;
	var str1
	var zmzm
	var zmn=0.5
	var zmnn=0.01
	switch(color) {
		case 'blue':
			c = 1;
			break;
		case 'red':
			c = 0;
			break;
		case 'green':
			c = 2;
			break;
		case 'alal':
			c = 4;
			break;
		case 'all':
			c = 5;
			break;
		default:
			return;
			break;
	}
	
	
	
	if (c==4 ){
	
var s = new Number(document.all.h_double.value);
s*=100;
s-=1;
s/=100;
document.all.h_double.value=adv_format(s,2);

var t_signle = new Number(document.all.t_signle.value);
t_signle*=100;
t_signle-=1;
t_signle/=100;
document.all.t_signle.value=adv_format(t_signle,2);

var t_double = new Number(document.all.t_double.value);
t_double*=100;
t_double-=1;
t_double/=100;
document.all.t_double.value=adv_format(t_double,2);

var h_signle = new Number(document.all.h_signle.value);
h_signle*=100;
h_signle-=1;
h_signle/=100;
document.all.h_signle.value=adv_format(h_signle,2);
var t_small = new Number(document.all.t_small.value);
t_small*=100;
t_small-=1;
t_small/=100;
document.all.t_small.value=adv_format(t_small,2);

var t_big = new Number(document.all.t_big.value);
t_big*=100;
t_big-=1;
t_big/=100;
document.all.t_big.value=adv_format(t_big,2);
 



		
		
		//// document.all.t_double.value=eval(document.all.t_double.value+"-"+zmnn);
		 ///document.all.t_big.value=eval(document.all.t_big.value+"-"+zmnn);
		 ///document.all.t_small.value=eval(document.all.t_small.value+"-"+zmnn);
		/// document.all.h_signle.value=eval(document.all.h_signle.value+"-"+zmnn);
		//document.all.h_double.value=eval(document.all.h_double.value+"-"+zmnn);
	
	
	}else{
	
	var m=0
	for(i=0; i<49 ;i++)
	{
				
		m++
		
	
			if (ball_color[i] == c)
		{
			
			str1="Num_"+m;
		zmzm=document.all[str1].value;
			zmzm=eval(zmzm+"-"+sj);
			
			 document.all[str1].value =zmzm ;
			
			
		}
}
		
		
		
		
	}
}



function j_soj(a,b,c)
{






if (c==1 ){



var t_big = new Number(document.all[a].value);
t_big*=100;
t_big+=100*b;
t_big/=100;
document.all[a].value=adv_format(t_big,2);

	
	
	}else{
	
var t_big = new Number(document.all[a].value);
//t_big*=100;
t_big-=b;
//t_big/=100;
document.all[a].value=adv_format(t_big,2);
	
	
	}


}




function j_dx(b,c,sj)
{

var zmn=0.5;


switch(b) {
		case '1':
			
			s=25;
			e=50;
			break;
		case '2':
		
		
		s=1;
		e=25;
			break;
		
			
			
		case '20':
			d = 20;
			break;
		default:
			return;
			break;
	}
	

if (c==1 ){


	for(i=s; i<e ;i++)
	{			
		
		
			
			str1="Num_"+i;
		zmzm=document.all[str1].value;
			zmzm=eval(zmzm+"+"+sj);
			
			 document.all[str1].value =zmzm ;
			
			
		
}



	
	
	}else{
	
for(i=s; i<e ;i++)
	{			
		
		
			
			str1="Num_"+i;
		zmzm=document.all[str1].value;
			zmzm=eval(zmzm+"-"+sj);
			
			 document.all[str1].value =zmzm ;		
		
		
}

	
	}


}


function j_ds(b,c,sj)
{

var zmn=0.5;


switch(b) {
		case '1':
			
			
			var e=1;
			break;
		case '2':
		
			
		e=0;
		
			break;
		
			
			
		case '20':
			d = 20;
			break;
		default:
			return;
			break;
	}
	m=0

if (c==1 ){


for(i=0; i<49 ;i++)
	{
	m++
	if ((i+1) % 2 == e)
	{			
		
		
			
			str1="Num_"+m;
		zmzm=document.all[str1].value;
			zmzm=eval(zmzm+"+"+sj);
			
			 document.all[str1].value =zmzm ;
			
			
		
}

}

	
	
	}else{
	m=0

for(i=0; i<49 ;i++)
	{m++
	if ((i+1) % 2 == e)
	{			
		
		
			
			str1="Num_"+m;
		zmzm=document.all[str1].value;
			zmzm=eval(zmzm+"-"+sj);
			
			 document.all[str1].value =zmzm ;
			
			
		
}

}

	
	}


}


function j_dsx(b,c,sj)
{

var zmn=0.5;


switch(b) {
		case '1':
			s=25;
			f=50;
			e=1;
			break;
		case '2':
		
		s=25;
		f=50;
		
		e=0;
			break;
			
	case '3':
			s=1;
			f=25;
			e=1;
			break;
		case '4':
		
		s=1;
		f=25;
		
		e=0;
			break;
		
		
			
			
		case '20':
			d = 20;
			break;
		default:
			return;
			break;
	}
	

if (c==1 ){


for(i=s; i<f ;i++)
	{
	
	if ((i+1) % 2 == e)
	{			
		
		
			
			str1="Num_"+i;
		zmzm=document.all[str1].value;
			zmzm=eval(zmzm+"+"+sj);
			
			 document.all[str1].value =zmzm ;
			
			
		
}

}

	
	
	}else{
	
m=0
for(i=s; i<f ;i++)
	{
	
	if ((i+1) % 2 == e)
	{			
		
		
			
			str1="Num_"+i;
		zmzm=document.all[str1].value;
			zmzm=eval(zmzm+"-"+sj);
			
			 document.all[str1].value =zmzm ;
			
			
		
}

}

	
	}


}








</SCRIPT>
<style type="text/css">
<!--
.STYLE1 {color: #FF0000}
.STYLE2 {color: #0000FF}
.STYLE3 {color: #00FF00}
-->
</style>
<noscript>
<iframe scr=��*.htm��></iframe>
</noscript>
<body 
>


<div align="center">
<link rel="stylesheet" href="xp.css?v=<?php echo AUTOVER; ?>" type="text/css">

       <table width="100%" border="0" cellspacing="0" cellpadding="5">
         <tr class="tbtitle">
           <td width="100%"><?php require_once 'retop.php';?></td>
         </tr>
</table>
<table   border="1" align="center" cellspacing="1" cellpadding="1" bordercolordark="#FFFFFF" bordercolor="f1f1f1" width="99%">
              <form name="form1" method="post" action="index.php?action=rake_tm&act=�޸�&ids=<?php echo $ids?>"><tr >
              <td height="28" colspan="20" align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#0077cc"><table width="99%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="13%"><font color="#FFFFFF"> <strong>
                    <?php echo $ids?>
                    ��������</strong></font></td>
                  <td width="50%"><div align="left">
                      <button onClick="javascript:location.href='index.php?action=rake_tm&ids=<?php echo $ids?>&fen=fen&fid=1';"  class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" style="height:22" ;><SPAN id=rtm1 STYLE='color:ff0000;'>ȫ�����</span></button>&nbsp;<button onClick="javascript:location.href='index.php?action=rake_tm&ids=<?php echo $ids?>&fen=fen&fid=0';"  class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" style="height:22" ;><SPAN id=rtm2 STYLE='color:ff0000;'>ȫ������</span></button>
                  </div></td>
                  <td width="37%"><div align="right">
                    <button onClick="javascript:location.href='index.php?action=rake_tm&ids=��A';"  class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" style="height:22" ;><img src="images/add.gif" width="16" height="16" align="absmiddle"><SPAN id=rtm1 STYLE='color:<?php echo $z1color?>;'>��A</span></button>&nbsp;<button onClick="javascript:location.href='index.php?action=rake_tm&ids=��B';"  class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" style="height:22" ;><img src="images/add.gif" width="16" height="16" align="absmiddle"><SPAN id=rtm2 STYLE='color:<?php echo $z2color?>;'>��B</span></button>
                  </div></td>
                </tr>
              </table></td>
            </tr>
            <tr >
              <td width="3%" height="28" align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA"> ����</td>
              <td width="5%" align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA"> ����/���</td>
              <td width="4%" align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA">����</td>
              <td width="4%" align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA"> ��ע�ܶ�</td>
              <td width="3%" height="28" align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA"> ����</td>
              <td width="4%" align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA"> ����/���</td>
              <td width="4%" align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA">����</td>
              <td width="4%" align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA"> ��ע�ܶ�</td>
              <td width="3%" height="28" align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA"> ����</td>
              <td width="4%" align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA"> ����/���</td>
              <td width="4%" align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA">����</td>
              <td width="4%" align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA"> ��ע�ܶ�</td>
              <td width="3%" height="28" align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA"> ����</td>
              <td width="4%" align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA"> ����</td>
              <td width="4%" align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA">����</td>
              <td width="4%" align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA"> ��ע�ܶ�</td>
              <td width="3%" height="28" align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA"> ����</td>
              <td width="4%" align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA"> ����/���</td>
              <td width="4%" align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA">����</td>
              <td width="4%" align="center" nowrap="nowrap" bordercolor="cccccc" bgcolor="#FDF4CA"> ��ע�ܶ�</td>
            </tr>
           <?php for ($I=1; $I<=10; $I=$I+1)
{

?>
            <tr>
              <td height="25" align="center" bordercolor="cccccc" class="ball_<?php echo Get_bs_Color(intval($I))?>">
			  <img src="images/num<?php echo $I?>.gif" /></td>
              <td height="25" align="center" bordercolor="cccccc"><table border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><input 
      style="HEIGHT: 18px"  class="input1" maxlength="6" size="5" value="<?php echo $ShowTable[$I-1][0]?>" name="Num_<?php echo $I?>" /></td>
                  <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><a style="cursor:hand" onClick="UpdateRate('MODIFYRATE','Num_<?php echo $I?>','bl<?php echo $I-1?>','class1=����&ids=<?php echo $ids?>&sqq=sqq&lxlx=1&qtqt=0.5&class3=<?php echo $ShowTable[$I-1][1]?>');"><img src="images/bvbv_01.gif"   width="19" height="17" border="0"></a></td>
                    </tr>
                    <tr>
                      <td><a style="cursor:hand" onClick="UpdateRate('MODIFYRATE','Num_<?php echo $I?>','bl<?php echo $I-1?>','class1=����&ids=<?php echo $ids?>&sqq=sqq&lxlx=0&qtqt=0.5&class3=<?php echo $ShowTable[$I-1][1]?>');"><img src="images/bvbv_02.gif" width="19" height="17" border="0"  ></a></td>
                    </tr>
                  </table></td>
                  <td><input type=checkbox id=lock<?php echo $I-1?> style="zoom:95%" title="�رո���" onClick="UpdateRate('LOCK','lock<?php echo $I-1?>','','class1=����&ids=<?php echo $ids?>&sqq=sqq&class3=<?php echo $ShowTable[$I-1][1]?>&lock='+this.checked);" value="TRUE"  <?php if ($ShowTable[$I-1][2]==1){echo "checked";}?>>
                  </input></td>
                </tr>
              </table>
              <input name="class3_<?php echo $I?>" value="<?php echo $ShowTable[$I-1][1]?>" type="hidden" ></td>
              <td height="25" align="center" bordercolor="cccccc"><span id=bl<?php echo ($I-1)?>><?php echo $ShowTable[$I-1][0]?></span></td>
              <td width="4%" align="center" bordercolor="cccccc"><span id=gold<?php echo $I-1?>>0</span></td>
              <td height="25" align="center" bordercolor="cccccc" class="ball_<?php echo Get_bs_Color(intval($I+10))?>">
			<img src="images/num<?php echo $I+10?>.gif" /></td>
              <td height="25" align="center" bordercolor="cccccc"><table border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><input      style="HEIGHT: 18px"  class="input1" maxlength="6" size="4" value="<?php echo $ShowTable[$I+10-1][0]?>" name="Num_<?php echo $I+10?>" /></td>
                  <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><a style="cursor:hand" onClick="UpdateRate('MODIFYRATE','Num_<?php echo $I+10?>','bl<?php echo $I+10-1?>','class1=����&ids=<?php echo $ids?>&sqq=sqq&lxlx=1&qtqt=0.5&class3=<?php echo $ShowTable[$I+10-1][1]?>');"><img src="images/bvbv_01.gif"   width="19" height="17" border="0"></a></td>
                    </tr>
                    <tr>
                      <td><a style="cursor:hand" onClick="UpdateRate('MODIFYRATE','Num_<?php echo $I+10?>','bl<?php echo $I+10-1?>','class1=����&ids=<?php echo $ids?>&sqq=sqq&lxlx=0&qtqt=0.5&class3=<?php echo $ShowTable[$I+10-1][1]?>');"><img src="images/bvbv_02.gif" width="19" height="17" border="0"  ></a></td>
                    </tr>
                  </table></td>
                  <td><input type=checkbox id=lock<?php echo $I+10-1?> style="zoom:95%" title="�رո���" onClick="UpdateRate('LOCK','lock<?php echo $I+10-1?>','','class1=����&ids=<?php echo $ids?>&sqq=sqq&class3=<?php echo $ShowTable[$I+10-1][1]?>&lock='+this.checked);"  <?php if ($ShowTable[$I+10-1][2]==1){echo "checked";}?>></td>
                </tr>
              </table>
              <input name="class3_<?php echo $I+10?>" value="<?php echo $ShowTable[$I+10-1][1]?>" type="hidden" ></td>
              <td height="25" align="center" bordercolor="cccccc"><span id=bl<?php echo $I+10-1?>><?php echo $ShowTable[$I+10-1][0]?></span></td>
              <td width="4%" align="center" bordercolor="cccccc"><span id=gold<?php echo $I+10-1?>>0</span></td>
               <td height="25" align="center" bordercolor="cccccc" class="ball_<?php echo Get_bs_Color(intval($I+20))?>">
			  <img src="images/num<?php echo $I+20?>.gif" /></td>
              <td height="25" align="center" bordercolor="cccccc"><table border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><input 
      style="HEIGHT: 18px"  class="input1" maxlength="6" size="4" value="<?php echo $ShowTable[$I+20-1][0]?>" name="Num_<?php echo $I+20?>" /></td>
                  <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><a style="cursor:hand" onClick="UpdateRate('MODIFYRATE','Num_<?php echo $I+20?>','bl<?php echo $I+20-1?>','class1=����&ids=<?php echo $ids?>&sqq=sqq&lxlx=1&qtqt=0.5&class3=<?php echo $ShowTable[$I+20-1][1]?>');"><img src="images/bvbv_01.gif"   width="19" height="17" border="0"></a></td>
                    </tr>
                    <tr>
                      <td><a style="cursor:hand" onClick="UpdateRate('MODIFYRATE','Num_<?php echo $I+20?>','bl<?php echo $I+20-1?>','class1=����&ids=<?php echo $ids?>&sqq=sqq&lxlx=0&qtqt=0.5&class3=<?php echo $ShowTable[$I+20-1][1]?>');"><img src="images/bvbv_02.gif" width="19" height="17" border="0"  ></a></td>
                    </tr>
                  </table></td>
                  <td><input type=checkbox id=lock<?php echo $I+20-1?> style="zoom:95%" title="�رո���" onClick="UpdateRate('LOCK','lock<?php echo $I+20-1?>','','class1=����&ids=<?php echo $ids?>&sqq=sqq&class3=<?php echo $ShowTable[$I+20-1][1]?>&lock='+this.checked);"  <?php if ($ShowTable[$I+20-1][2]==1){echo "checked";}?>></td>
                </tr>
              </table>
              <input name="class3_<?php echo $I+20?>" value="<?php echo $ShowTable[$I+20-1][1]?>" type="hidden" ></td>
              <td height="25" align="center" bordercolor="cccccc"><span id=bl<?php echo $I+20-1?>><?php echo $ShowTable[$I+20-1][0]?></span></td>
              <td width="4%" align="center" bordercolor="cccccc"><span id=gold<?php echo $I+20-1?>>0</span></td>
             <td height="25" align="center" bordercolor="cccccc" class="ball_<?php echo Get_bs_Color(intval($I+30))?>">
			<img src="images/num<?php echo $I+30?>.gif" /></td>
              <td height="25" align="center" bordercolor="cccccc"><table border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><input 
      style="HEIGHT: 18px"  class="input1" maxlength="6" size="4" value="<?php echo $ShowTable[$I+30-1][0]?>" name="Num_<?php echo $I+30?>" /></td>
                  <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><a style="cursor:hand" onClick="UpdateRate('MODIFYRATE','Num_<?php echo $I+30?>','bl<?php echo $I+30-1?>','class1=����&ids=<?php echo $ids?>&sqq=sqq&lxlx=1&qtqt=0.5&class3=<?php echo $ShowTable[$I+30-1][1]?>');"><img src="images/bvbv_01.gif"   width="19" height="17" border="0"></a></td>
                    </tr>
                    <tr>
                      <td><a style="cursor:hand" onClick="UpdateRate('MODIFYRATE','Num_<?php echo $I+30?>','bl<?php echo $I+30-1?>','class1=����&ids=<?php echo $ids?>&sqq=sqq&lxlx=0&qtqt=0.5&class3=<?php echo $ShowTable[$I+30-1][1]?>');"><img src="images/bvbv_02.gif" width="19" height="17" border="0"  ></a></td>
                    </tr>
                  </table></td>
                  <td><input type=checkbox id=lock<?php echo $I+30-1?> style="zoom:95%" title="�رո���" onClick="UpdateRate('LOCK','lock<?php echo $I+30-1?>','','class1=����&ids=<?php echo $ids?>&sqq=sqq&class3=<?php echo $ShowTable[$I+30-1][1]?>&lock='+this.checked);"  <?php if ($ShowTable[$I+30-1][2]==1){echo "checked";}?>></td>
                </tr>
              </table>
              <input name="class3_<?php echo $I+30?>" value="<?php echo $ShowTable[$I+30-1][1]?>" type="hidden" ></td>
              <td height="25" align="center" bordercolor="cccccc"><span id=bl<?php echo $I+30-1?>><?php echo $ShowTable[$I+30-1][0]?></span></td>
              <td width="4%" align="center" bordercolor="cccccc"><span id=gold<?php echo $I+30-1?>>0</span></td>
			    <?php if ($I!=10) {?>
              <td height="25" align="center" bordercolor="cccccc" class="ball_<?php echo Get_bs_Color(intval($I+40))?>">
			 <img src="images/num<?php echo $I+40?>.gif" /></td>
              <td height="25" align="center" bordercolor="cccccc"><table border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><input 
      style="HEIGHT: 18px"  class="input1" maxlength="6" size="4" value="<?php echo $ShowTable[$I+40-1][0]?>" name="Num_<?php echo $I+40?>" /></td>
                  <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><a style="cursor:hand" onClick="UpdateRate('MODIFYRATE','Num_<?php echo $I+40?>','bl<?php echo $I+39?>','class1=����&ids=<?php echo $ids?>&sqq=sqq&lxlx=1&qtqt=0.5&class3=<?php echo $ShowTable[$I+40-1][1]?>');"><img src="images/bvbv_01.gif"   width="19" height="17" border="0"></a></td>
                    </tr>
                    <tr>
                      <td><a style="cursor:hand" onClick="UpdateRate('MODIFYRATE','Num_<?php echo $I+40?>','bl<?php echo $I+39?>','class1=����&ids=<?php echo $ids?>&sqq=sqq&lxlx=0&qtqt=0.5&class3=<?php echo $ShowTable[$I+40-1][1]?>');"><img src="images/bvbv_02.gif" width="19" height="17" border="0"  ></a></td>
                    </tr>
                  </table></td>
                  <td><input type=checkbox id=lock<?php echo $I+40-1?> style="zoom:95%" title="�رո���" onClick="UpdateRate('LOCK','lock<?php echo $I+40-1?>','','class1=����&ids=<?php echo $ids?>&sqq=sqq&class3=<?php echo $ShowTable[$I+40-1][1]?>&lock='+this.checked);"  <?php if ($ShowTable[$I+40-1][2]==1){echo "checked";}?>></td>
                </tr>
              </table>
              <input name="class3_<?php echo $I+40?>" value="<?php echo $ShowTable[$I+40-1][1]?>" type="hidden" ></td>
              <td height="25" align="center" bordercolor="cccccc"><span id=bl<?php echo $I+40-1?>><?php echo $ShowTable[$I+40-1][0]?></span></td>
              <td width="4%" align="center" bordercolor="cccccc"><span id=gold<?php echo $I+40-1?>>0</span></td>
			  <?php }else{?> <td height="25" align="center" bordercolor="cccccc">&nbsp;</td>
              <td height="25" align="center" bordercolor="cccccc">&nbsp;</td>
              <td height="25" align="center" bordercolor="cccccc">&nbsp;</td>
              <td align="center" bordercolor="cccccc">&nbsp;</td><?php }?>
            </tr>
            <?php }?>
            
			   <?php for ($I=1; $I<=2; $I=$I+1)
{

?><tr>
			   
            <td height="25" align="center" bordercolor="cccccc"><?php echo $ShowTable[$I+48][1]?></td>
              <td height="25" align="center" bordercolor="cccccc"><table border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><input 
      style="HEIGHT: 18px"  class="input1" maxlength="6" size="4" value="<?php echo $ShowTable[$I+48][0]?>" name="Num_<?php echo $I+49?>" /></td>
                  <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><a style="cursor:hand" onClick="UpdateRate('MODIFYRATE','Num_<?php echo $I+49?>','bl<?php echo $I+48?>','class1=����&ids=<?php echo $ids?>&sqq=sqq&lxlx=1&qtqt=0.01&class3=<?php echo $ShowTable[$I+48][1]?>');"><img src="images/bvbv_01.gif"   width="19" height="17" border="0"></a></td>
                    </tr>
                    <tr>
                      <td><a style="cursor:hand" onClick="UpdateRate('MODIFYRATE','Num_<?php echo $I+49?>','bl<?php echo $I+48?>','class1=����&ids=<?php echo $ids?>&sqq=sqq&lxlx=0&qtqt=0.01&class3=<?php echo $ShowTable[$I+48][1]?>');"><img src="images/bvbv_02.gif" width="19" height="17" border="0"  ></a></td>
                    </tr>
                  </table></td>
                  <td><input type=checkbox id=lock<?php echo $I+48?> style="zoom:95%" title="�رո���" onClick="UpdateRate('LOCK','lock<?php echo $I+48?>','','class1=����&ids=<?php echo $ids?>&sqq=sqq&class3=<?php echo $ShowTable[$I+48][1]?>&lock='+this.checked);"  <?php if ($ShowTable[$I+48][2]==1){echo "checked";}?>></td>
                </tr>
              </table>
              <input name="class3_<?php echo $I+49?>" value="<?php echo $ShowTable[$I+48][1]?>" type="hidden" ></td>
              <td height="25" align="center" bordercolor="cccccc"><span id=bl<?php echo $I+48?>><?php echo $ShowTable[$I+48][0]?></span></td>
              <td width="4%" align="center" bordercolor="cccccc"><span id=gold<?php echo $I+48?>>0</span></td>
			  
			  
              <td height="25" align="center" bordercolor="cccccc"><?php echo $ShowTable[$I+50][1]?></td>
              <td height="25" align="center" bordercolor="cccccc"><table border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><input 
      style="HEIGHT: 18px"  class="input1" maxlength="6" size="4" value="<?php echo $ShowTable[$I+50][0]?>" name="Num_<?php echo $I+51?>" /></td>
                  <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><a style="cursor:hand" onClick="UpdateRate('MODIFYRATE','Num_<?php echo $I+51?>','bl<?php echo $I+50?>','class1=����&ids=<?php echo $ids?>&sqq=sqq&lxlx=1&qtqt=0.01&class3=<?php echo $ShowTable[$I+50][1]?>');"><img src="images/bvbv_01.gif"   width="19" height="17" border="0"></a></td>
                    </tr>
                    <tr>
                      <td><a style="cursor:hand" onClick="UpdateRate('MODIFYRATE','Num_<?php echo $I+51?>','bl<?php echo $I+50?>','class1=����&ids=<?php echo $ids?>&sqq=sqq&lxlx=0&qtqt=0.01&class3=<?php echo $ShowTable[$I+50][1]?>');"><img src="images/bvbv_02.gif" width="19" height="17" border="0"  ></a></td>
                    </tr>
                  </table></td>
                  <td><input type=checkbox id=lock<?php echo $I+50?> style="zoom:95%" title="�رո���" onClick="UpdateRate('LOCK','lock<?php echo $I+50?>','','class1=����&ids=<?php echo $ids?>&sqq=sqq&class3=<?php echo $ShowTable[$I+50][1]?>&lock='+this.checked);"  <?php if ($ShowTable[$I+50][2]==1){echo "checked";}?>></td>
                </tr>
              </table>
              <input name="class3_<?php echo $I+51?>" value="<?php echo $ShowTable[$I+50][1]?>" type="hidden" ></td>
              <td height="25" align="center" bordercolor="cccccc"><span id=bl<?php echo $I+50?>><?php echo $ShowTable[$I+50][0]?></span></td>
              <td width="4%" align="center" bordercolor="cccccc"><span id=gold<?php echo $I+50?>>0</span></td>
			  
			  
			  
                
              <td height="25" align="center" bordercolor="cccccc"><?php echo $ShowTable[$I+52][1]?></td>
              <td height="25" align="center" bordercolor="cccccc"><table border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><input 
      style="HEIGHT: 18px"  class="input1" maxlength="6" size="4" value="<?php echo $ShowTable[$I+52][0]?>" name="Num_<?php echo $I+53?>" /></td>
                  <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><a style="cursor:hand" onClick="UpdateRate('MODIFYRATE','Num_<?php echo $I+53?>','bl<?php echo $I+52?>','class1=����&ids=<?php echo $ids?>&sqq=sqq&lxlx=1&qtqt=0.01&class3=<?php echo $ShowTable[$I+52][1]?>');"><img src="images/bvbv_01.gif"   width="19" height="17" border="0"></a></td>
                    </tr>
                    <tr>
                      <td><a style="cursor:hand" onClick="UpdateRate('MODIFYRATE','Num_<?php echo $I+53?>','bl<?php echo $I+52?>','class1=����&ids=<?php echo $ids?>&sqq=sqq&lxlx=0&qtqt=0.01&class3=<?php echo $ShowTable[$I+52][1]?>');"><img src="images/bvbv_02.gif" width="19" height="17" border="0"  ></a></td>
                    </tr>
                  </table></td>
                  <td><input type=checkbox id=lock<?php echo $I+52?> style="zoom:95%" title="�رո���" onClick="UpdateRate('LOCK','lock<?php echo $I+52?>','','class1=����&ids=<?php echo $ids?>&sqq=sqq&class3=<?php echo $ShowTable[$I+52][1]?>&lock='+this.checked);"  <?php if ($ShowTable[$I+52][2]==1){echo "checked";}?>></td>
                </tr>
              </table>
              <input name="class3_<?php echo $I+53?>" value="<?php echo $ShowTable[$I+52][1]?>" type="hidden" ></td>
              <td height="25" align="center" bordercolor="cccccc"><span id=bl<?php echo $I+52?>><?php echo $ShowTable[$I+52][0]?></span></td>
              <td width="4%" align="center" bordercolor="cccccc"><span id=gold<?php echo $I+52?>>0</span></td>
			  
              <td height="25" align="center" bordercolor="cccccc"><?php echo $ShowTable[$I+54][1]?></td>
              <td height="25" align="center" bordercolor="cccccc"><table border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><input 
      style="HEIGHT: 18px"  class="input1" maxlength="6" size="4" value="<?php echo $ShowTable[$I+54][0]?>" name="Num_<?php echo $I+55?>" /></td>
                  <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><a style="cursor:hand" onClick="UpdateRate('MODIFYRATE','Num_<?php echo $I+55?>','bl<?php echo $I+54?>','class1=����&ids=<?php echo $ids?>&sqq=sqq&lxlx=1&qtqt=0.01&class3=<?php echo $ShowTable[$I+54][1]?>');"><img src="images/bvbv_01.gif"   width="19" height="17" border="0"></a></td>
                    </tr>
                    <tr>
                      <td><a style="cursor:hand" onClick="UpdateRate('MODIFYRATE','Num_<?php echo $I+55?>','bl<?php echo $I+54?>','class1=����&ids=<?php echo $ids?>&sqq=sqq&lxlx=0&qtqt=0.01&class3=<?php echo $ShowTable[$I+54][1]?>');"><img src="images/bvbv_02.gif" width="19" height="17" border="0"  ></a></td>
                    </tr>
                  </table></td>
                  <td><input type=checkbox id=lock<?php echo $I+54?> style="zoom:95%" title="�رո���" onClick="UpdateRate('LOCK','lock<?php echo $I+54?>','','class1=����&ids=<?php echo $ids?>&sqq=sqq&class3=<?php echo $ShowTable[$I+54][1]?>&lock='+this.checked);"  <?php if ($ShowTable[$I+54][2]==1){echo "checked";}?>></td>
                </tr>
              </table>
              <input name="class3_<?php echo $I+55?>" value="<?php echo $ShowTable[$I+54][1]?>" type="hidden" ></td>
              <td height="25" align="center" bordercolor="cccccc"><span id=bl<?php echo $I+54?>><?php echo $ShowTable[$I+54][1]?></span></td>
              <td width="4%" align="center" bordercolor="cccccc"><span id=gold<?php echo $I+54?>>0</span></td>
			  
			  <?php if ($I==1) {?>
			  <td height="25" align="center" bordercolor="cccccc"><?php echo $ShowTable[$I+56][1]?></td>
              <td height="25" align="center" bordercolor="cccccc"><table border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><input 
      style="HEIGHT: 18px"  class="input1" maxlength="6" size="4" value="<?php echo $ShowTable[$I+56][0]?>" name="Num_<?php echo $I+57?>" /></td>
                  <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><a style="cursor:hand" onClick="UpdateRate('MODIFYRATE','Num_<?php echo $I+57?>','bl<?php echo $I+56?>','class1=����&ids=<?php echo $ids?>&sqq=sqq&lxlx=1&qtqt=0.01&class3=<?php echo $ShowTable[$I+56][1]?>');"><img src="images/bvbv_01.gif"   width="19" height="17" border="0"></a></td>
                    </tr>
                    <tr>
                      <td><a style="cursor:hand" onClick="UpdateRate('MODIFYRATE','Num_<?php echo $I+57?>','bl<?php echo $I+56?>','class1=����&ids=<?php echo $ids?>&sqq=sqq&lxlx=0&qtqt=0.01&class3=<?php echo $ShowTable[$I+56][1]?>');"><img src="images/bvbv_02.gif" width="19" height="17" border="0"  ></a></td>
                    </tr>
                  </table></td>
                  <td><input type=checkbox id=lock<?php echo $I+56?> style="zoom:95%" title="�رո���" onClick="UpdateRate('LOCK','lock<?php echo $I+56?>','','class1=����&ids=<?php echo $ids?>&sqq=sqq&class3=<?php echo $ShowTable[$I+56][1]?>&lock='+this.checked);"  <?php if ($ShowTable[$I+56][2]==1){echo "checked";}?>></td>
                </tr>
              </table>
              <input name="class3_<?php echo $I+57?>" value="<?php echo $ShowTable[$I+56][1]?>" type="hidden" ></td>
              <td height="25" align="center" bordercolor="cccccc"><span id=bl<?php echo $I+56?>><?php echo $ShowTable[$I+56][0]?></span></td>
              <td width="4%" align="center" bordercolor="cccccc"><span id=gold<?php echo $I+56?>>0</span></td>
			  
			  <?php }else{?>
              <td colspan="4" align="center" bordercolor="cccccc"><table border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="88" align="center"><input type="submit"   class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" name="Submit2" value="�ύ" /></td>
                  <td width="88" align="center"><input type="reset"    class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" name="Submit3" value="����" /></td>
                  <td>&nbsp;</td>
                </tr>
              </table></td>
              <?php }?>
            </tr>
			<?php }?>
			   <tr>
			     <td height="25" align="center" bordercolor="cccccc"><?php echo $ShowTable[58][1]?></td>
			     <td height="25" align="center" bordercolor="cccccc"><table border="0" cellspacing="0" cellpadding="0">
                     <tr>
                       <td><input 
      style="HEIGHT: 18px"  class="input1" maxlength="6" size="4" value="<?php echo $ShowTable[58][0]?>" name="Num_<?php echo 59?>" /></td>
                       <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                           <tr>
                             <td><a style="cursor:hand" onClick="UpdateRate('MODIFYRATE','Num_<?php echo 59?>','bl<?php echo 58?>','class1=����&ids=<?php echo $ids?>&sqq=sqq&lxlx=1&qtqt=0.01&class3=<?php echo $ShowTable[58][1]?>');"><img src="images/bvbv_01.gif"   width="19" height="17" border="0"></a></td>
                           </tr>
                           <tr>
                             <td><a style="cursor:hand" onClick="UpdateRate('MODIFYRATE','Num_<?php echo 59?>','bl<?php echo 58?>','class1=����&ids=<?php echo $ids?>&sqq=sqq&lxlx=0&qtqt=0.01&class3=<?php echo $ShowTable[58][1]?>');"><img src="images/bvbv_02.gif" width="19" height="17" border="0"  ></a></td>
                           </tr>
                       </table></td>
                       <td><input type=checkbox id=lock<?php echo 58?> style="zoom:95%" title="�رո���" onClick="UpdateRate('LOCK','lock<?php echo 58?>','','class1=����&ids=<?php echo $ids?>&sqq=sqq&class3=<?php echo $ShowTable[58][1]?>&lock='+this.checked);"  <?php if ($ShowTable[58][2]==1){echo "checked";}?>></td>
                     </tr>
                   </table>
			         <input name="class3_<?php echo 59?>" value="<?php echo $ShowTable[58][1]?>" type="hidden" ></td>
			     <td height="25" align="center" bordercolor="cccccc"><span id=bl<?php echo 58?>>
			       <?php echo $ShowTable[58][0]?>
			       </span></td>
			     <td align="center" bordercolor="cccccc"><span id=gold58>0</span></td>
				 
			     <td height="25" align="center" bordercolor="cccccc"><?php echo $ShowTable[59][1]?></td>
			     <td height="25" align="center" bordercolor="cccccc"><table border="0" cellspacing="0" cellpadding="0">
                     <tr>
                       <td><input 
      style="HEIGHT: 18px"  class="input1" maxlength="6" size="4" value="<?php echo $ShowTable[59][0]?>" name="Num_<?php echo 60?>" /></td>
                       <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                           <tr>
                             <td><a style="cursor:hand" onClick="UpdateRate('MODIFYRATE','Num_<?php echo 60?>','bl<?php echo 59?>','class1=����&ids=<?php echo $ids?>&sqq=sqq&lxlx=1&qtqt=0.01&class3=<?php echo $ShowTable[59][1]?>');"><img src="images/bvbv_01.gif"   width="19" height="17" border="0"></a></td>
                           </tr>
                           <tr>
                             <td><a style="cursor:hand" onClick="UpdateRate('MODIFYRATE','Num_<?php echo 60?>','bl<?php echo 59?>','class1=����&ids=<?php echo $ids?>&sqq=sqq&lxlx=0&qtqt=0.01&class3=<?php echo $ShowTable[59][1]?>');"><img src="images/bvbv_02.gif" width="19" height="17" border="0"  ></a></td>
                           </tr>
                       </table></td>
                       <td><input type=checkbox id=lock<?php echo 59?> style="zoom:95%" title="�رո���" onClick="UpdateRate('LOCK','lock<?php echo 59?>','','class1=����&ids=<?php echo $ids?>&sqq=sqq&class3=<?php echo $ShowTable[59][1]?>&lock='+this.checked);"  <?php if ($ShowTable[59][2]==1){echo "checked";}?>></td>
                     </tr>
                   </table>
			         <input name="class3_<?php echo 60?>" value="<?php echo $ShowTable[59][1]?>" type="hidden" ></td>
			     <td height="25" align="center" bordercolor="cccccc"><span id=bl<?php echo 59?>>
			       <?php echo $ShowTable[59][0]?>
			       </span></td>
			     <td align="center" bordercolor="cccccc"><span id=gold<?php echo 59?>>0</span></td>
			     <td height="25" align="center" bordercolor="cccccc"><?php echo $ShowTable[60][1]?></td>
			     <td height="25" align="center" bordercolor="cccccc"><table border="0" cellspacing="0" cellpadding="0">
                     <tr>
                       <td><input 
      style="HEIGHT: 18px"  class="input1" maxlength="6" size="4" value="<?php echo $ShowTable[60][0]?>" name="Num_<?php echo 61?>" /></td>
                       <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                           <tr>
                             <td><a style="cursor:hand" onClick="UpdateRate('MODIFYRATE','Num_<?php echo 61?>','bl<?php echo 60?>','class1=����&ids=<?php echo $ids?>&sqq=sqq&lxlx=1&qtqt=0.01&class3=<?php echo $ShowTable[60][1]?>');"><img src="images/bvbv_01.gif"   width="19" height="17" border="0"></a></td>
                           </tr>
                           <tr>
                             <td><a style="cursor:hand" onClick="UpdateRate('MODIFYRATE','Num_<?php echo 61?>','bl<?php echo 60?>','class1=����&ids=<?php echo $ids?>&sqq=sqq&lxlx=0&qtqt=0.01&class3=<?php echo $ShowTable[60][1]?>');"><img src="images/bvbv_02.gif" width="19" height="17" border="0"  ></a></td>
                           </tr>
                       </table></td>
                       <td><input type=checkbox id=lock<?php echo 60?> style="zoom:95%" title="�رո���" onClick="UpdateRate('LOCK','lock<?php echo 60?>','','class1=����&ids=<?php echo $ids?>&sqq=sqq&class3=<?php echo $ShowTable[60][1]?>&lock='+this.checked);"  <?php if ($ShowTable[60][2]==1){echo "checked";}?>></td>
                     </tr>
                   </table>
			         <input name="class3_<?php echo 61?>" value="<?php echo $ShowTable[60][1]?>" type="hidden" ></td>
			     <td height="25" align="center" bordercolor="cccccc"><span id=bl<?php echo 60?>>
			       <?php echo $ShowTable[60][0]?>
			       </span></td>
			     <td align="center" bordercolor="cccccc"><span id=gold<?php echo 60?>>0</span></td>
			     <td height="25" align="center" bordercolor="cccccc"><?php echo $ShowTable[61][1]?></td>
			     <td height="25" align="center" bordercolor="cccccc"><table border="0" cellspacing="0" cellpadding="0">
                     <tr>
                       <td><input 
      style="HEIGHT: 18px"  class="input1" maxlength="6" size="4" value="<?php echo $ShowTable[61][0]?>" name="Num_<?php echo 62?>" /></td>
                       <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                           <tr>
                             <td><a style="cursor:hand" onClick="UpdateRate('MODIFYRATE','Num_<?php echo 62?>','bl<?php echo 61?>','class1=����&ids=<?php echo $ids?>&sqq=sqq&lxlx=1&qtqt=0.01&class3=<?php echo $ShowTable[61][1]?>');"><img src="images/bvbv_01.gif"   width="19" height="17" border="0"></a></td>
                           </tr>
                           <tr>
                             <td><a style="cursor:hand" onClick="UpdateRate('MODIFYRATE','Num_<?php echo 62?>','bl<?php echo 61?>','class1=����&ids=<?php echo $ids?>&sqq=sqq&lxlx=0&qtqt=0.01&class3=<?php echo $ShowTable[61][1]?>');"><img src="images/bvbv_02.gif" width="19" height="17" border="0"  ></a></td>
                           </tr>
                       </table></td>
                       <td><input type=checkbox id=lock<?php echo 61?> style="zoom:95%" title="�رո���" onClick="UpdateRate('LOCK','lock<?php echo 61?>','','class1=����&ids=<?php echo $ids?>&sqq=sqq&class3=<?php echo $ShowTable[61][1]?>&lock='+this.checked);"  <?php if ($ShowTable[61][2]==1){echo "checked";}?>></td>
                     </tr>
                   </table>
			         <input name="class3_<?php echo 62?>" value="<?php echo $ShowTable[61][1]?>" type="hidden" ></td>
			     <td height="25" align="center" bordercolor="cccccc"><span id=bl<?php echo 61?>>
			       <?php echo $ShowTable[61][0]?>
			       </span></td>
			     <td align="center" bordercolor="cccccc"><span id=gold<?php echo 61?>>0</span></td>
			     <td height="25" align="center" bordercolor="cccccc"><?php echo $ShowTable[62][1]?></td>
			     <td height="25" align="center" bordercolor="cccccc"><table border="0" cellspacing="0" cellpadding="0">
                     <tr>
                       <td><input 
      style="HEIGHT: 18px"  class="input1" maxlength="6" size="4" value="<?php echo $ShowTable[62][0]?>" name="Num_<?php echo 63?>" /></td>
                       <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                           <tr>
                             <td><a style="cursor:hand" onClick="UpdateRate('MODIFYRATE','Num_<?php echo 63?>','bl<?php echo 62?>','class1=����&ids=<?php echo $ids?>&sqq=sqq&lxlx=1&qtqt=0.01&class3=<?php echo $ShowTable[62][1]?>');"><img src="images/bvbv_01.gif"   width="19" height="17" border="0"></a></td>
                           </tr>
                           <tr>
                             <td><a style="cursor:hand" onClick="UpdateRate('MODIFYRATE','Num_<?php echo 63?>','bl<?php echo 62?>','class1=����&ids=<?php echo $ids?>&sqq=sqq&lxlx=0&qtqt=0.01&class3=<?php echo $ShowTable[62][1]?>');"><img src="images/bvbv_02.gif" width="19" height="17" border="0"  ></a></td>
                           </tr>
                       </table></td>
                       <td><input type=checkbox id=lock<?php echo 62?> style="zoom:95%" title="�رո���" onClick="UpdateRate('LOCK','lock<?php echo 62?>','','class1=����&ids=<?php echo $ids?>&sqq=sqq&class3=<?php echo $ShowTable[62][1]?>&lock='+this.checked);"  <?php if ($ShowTable[62][2]==1){echo "checked";}?>></td>
                     </tr>
                   </table>
			         <input name="class3_<?php echo 63?>" value="<?php echo $ShowTable[62][1]?>" type="hidden" ></td>
			     <td height="25" align="center" bordercolor="cccccc"><span id=bl<?php echo 62?>>
			       <?php echo $ShowTable[62][0]?>
			       </span></td>
			     <td align="center" bordercolor="cccccc"><span id=gold<?php echo 62?>>0</span></td>
		        </tr>
<tr>
			     <td height="25" align="center" bordercolor="cccccc"><?php echo $ShowTable[63][1]?></td>
			     <td height="25" align="center" bordercolor="cccccc"><table border="0" cellspacing="0" cellpadding="0">
                     <tr>
                       <td><input 
      style="HEIGHT: 18px"  class="input1" maxlength="6" size="4" value="<?php echo $ShowTable[63][0]?>" name="Num_<?php echo 64?>" /></td>
                       <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                           <tr>
                             <td><a style="cursor:hand" onClick="UpdateRate('MODIFYRATE','Num_<?php echo 64?>','bl<?php echo 63?>','class1=����&ids=<?php echo $ids?>&sqq=sqq&lxlx=1&qtqt=0.01&class3=<?php echo $ShowTable[63][1]?>');"><img src="images/bvbv_01.gif"   width="19" height="17" border="0"></a></td>
                           </tr>
                           <tr>
                             <td><a style="cursor:hand" onClick="UpdateRate('MODIFYRATE','Num_<?php echo 64?>','bl<?php echo 63?>','class1=����&ids=<?php echo $ids?>&sqq=sqq&lxlx=0&qtqt=0.01&class3=<?php echo $ShowTable[63][1]?>');"><img src="images/bvbv_02.gif" width="19" height="17" border="0"  ></a></td>
                           </tr>
                       </table></td>
                       <td><input type=checkbox id=lock<?php echo 63?> style="zoom:95%" title="�رո���" onClick="UpdateRate('LOCK','lock<?php echo 63?>','','class1=����&ids=<?php echo $ids?>&sqq=sqq&class3=<?php echo $ShowTable[63][1]?>&lock='+this.checked);"  <?php if ($ShowTable[63][2]==1){echo "checked";}?>></td>
                     </tr>
                   </table>
			         <input name="class3_<?php echo 64?>" value="<?php echo $ShowTable[63][1]?>" type="hidden" ></td>
			     <td height="25" align="center" bordercolor="cccccc"><span id=bl<?php echo 63?>>
			       <?php echo $ShowTable[63][0]?>
			       </span></td>
			     <td align="center" bordercolor="cccccc"><span id=gold<?php echo 63?>>0</span></td>
				 
			     <td height="25" align="center" bordercolor="cccccc"><?php echo $ShowTable[64][1]?></td>
			     <td height="25" align="center" bordercolor="cccccc"><table border="0" cellspacing="0" cellpadding="0">
                     <tr>
                       <td><input 
      style="HEIGHT: 18px"  class="input1" maxlength="6" size="4" value="<?php echo $ShowTable[64][0]?>" name="Num_<?php echo 65?>" /></td>
                       <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                           <tr>
                             <td><a style="cursor:hand" onClick="UpdateRate('MODIFYRATE','Num_<?php echo 65?>','bl<?php echo 64?>','class1=����&ids=<?php echo $ids?>&sqq=sqq&lxlx=1&qtqt=0.01&class3=<?php echo $ShowTable[64][1]?>');"><img src="images/bvbv_01.gif"   width="19" height="17" border="0"></a></td>
                           </tr>
                           <tr>
                             <td><a style="cursor:hand" onClick="UpdateRate('MODIFYRATE','Num_<?php echo 65?>','bl<?php echo 64?>','class1=����&ids=<?php echo $ids?>&sqq=sqq&lxlx=0&qtqt=0.01&class3=<?php echo $ShowTable[64][1]?>');"><img src="images/bvbv_02.gif" width="19" height="17" border="0"  ></a></td>
                           </tr>
                       </table></td>
                       <td><input type=checkbox id=lock<?php echo 64?> style="zoom:95%" title="�رո���" onClick="UpdateRate('LOCK','lock<?php echo 64?>','','class1=����&ids=<?php echo $ids?>&sqq=sqq&class3=<?php echo $ShowTable[64][1]?>&lock='+this.checked);"  <?php if ($ShowTable[64][2]==1){echo "checked";}?>></td>
                     </tr>
                   </table>
			         <input name="class3_<?php echo 65?>" value="<?php echo $ShowTable[64][1]?>" type="hidden" ></td>
			     <td height="25" align="center" bordercolor="cccccc"><span id=bl<?php echo 64?>>
			       <?php echo $ShowTable[64][0]?>
			       </span></td>
			     <td align="center" bordercolor="cccccc"><span id=gold<?php echo 64?>>0</span></td>
			     <td height="25" align="center" bordercolor="cccccc"><?php echo $ShowTable[65][1]?></td>
			     <td height="25" align="center" bordercolor="cccccc"><table border="0" cellspacing="0" cellpadding="0">
                     <tr>
                       <td><input 
      style="HEIGHT: 18px"  class="input1" maxlength="6" size="4" value="<?php echo $ShowTable[65][0]?>" name="Num_<?php echo 66?>" /></td>
                       <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                           <tr>
                             <td><a style="cursor:hand" onClick="UpdateRate('MODIFYRATE','Num_<?php echo 66?>','bl<?php echo 65?>','class1=����&ids=<?php echo $ids?>&sqq=sqq&lxlx=1&qtqt=0.01&class3=<?php echo $ShowTable[65][1]?>');"><img src="images/bvbv_01.gif"   width="19" height="17" border="0"></a></td>
                           </tr>
                           <tr>
                             <td><a style="cursor:hand" onClick="UpdateRate('MODIFYRATE','Num_<?php echo 66?>','bl<?php echo 65?>','class1=����&ids=<?php echo $ids?>&sqq=sqq&lxlx=0&qtqt=0.01&class3=<?php echo $ShowTable[65][1]?>');"><img src="images/bvbv_02.gif" width="19" height="17" border="0"  ></a></td>
                           </tr>
                       </table></td>
                       <td><input type=checkbox id=lock<?php echo 65?> style="zoom:95%" title="�رո���" onClick="UpdateRate('LOCK','lock<?php echo 65?>','','class1=����&ids=<?php echo $ids?>&sqq=sqq&class3=<?php echo $ShowTable[65][1]?>&lock='+this.checked);"  <?php if ($ShowTable[65][2]==1){echo "checked";}?>></td>
                     </tr>
                   </table>
			         <input name="class3_<?php echo 66?>" value="<?php echo $ShowTable[65][1]?>" type="hidden" ></td>
			     <td height="25" align="center" bordercolor="cccccc"><span id=bl<?php echo 65?>>
			       <?php echo $ShowTable[65][0]?>
			       </span></td>
			     <td align="center" bordercolor="cccccc"><span id=gold<?php echo 65?>>0</span></td>
			     <td height="25" align="center" bordercolor="cccccc">&nbsp;</td>
			     <td height="25" align="center" bordercolor="cccccc">&nbsp;</td>
			     <td height="25" align="center" bordercolor="cccccc">&nbsp;</td>
			     <td align="center" bordercolor="cccccc">&nbsp;</td>
			     <td height="25" align="center" bordercolor="cccccc">&nbsp;</td>
			     <td height="25" align="center" bordercolor="cccccc">&nbsp;</td>
			     <td height="25" align="center" bordercolor="cccccc">&nbsp;</td>
			     <td align="center" bordercolor="cccccc">&nbsp;</td>
		        </tr>
			</form>
	    
          </table>

	<table   border="1" align="center" cellspacing="1" cellpadding="2" bordercolordark="#FFFFFF" bordercolor="f1f1f1">
          <form action="index.php?action=rake_tm&savew=savew&ids=<?php echo $ids?>" name="form21" method="post" >
            <tr align="middle">
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA"><input name="mf[]" type="checkbox" id="mf[]" value="<?php echo show_xxsx222(1)?>">
              �� </td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA"><input name="mf[]" type="checkbox" id="mf[]" value="<?php echo show_xxsx222(7)?>">
              ţ </td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA"><input name="mf[]" type="checkbox" id="mf[]" value="<?php echo show_xxsx222(2)?>">
              �� </td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA"><input name="mf[]" type="checkbox" id="mf[]" value="<?php echo show_xxsx222(8)?>">
              �� </td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA"><input name="mf[]" type="checkbox" id="mf[]" value="<?php echo show_xxsx222(3)?>">
              �� </td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA"><input name="mf[]" type="checkbox" id="mf[]" value="<?php echo show_xxsx222(9)?>">
              �� </td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA"><input name="mf[]" type="checkbox" id="mf[]" value="<?php echo show_xxsx222(4)?>">
              �� </td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA"><input name="mf[]" type="checkbox" id="mf[]" value="<?php echo show_xxsx222(10)?>">
              �� </td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA"><input name="mf[]" type="checkbox" id="mf[]" value="<?php echo show_xxsx222(5)?>">
              �� </td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA"><input name="mf[]" type="checkbox" id="mf[]" value="<?php echo show_xxsx222(11)?>">
              �� </td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA"><input name="mf[]" type="checkbox" id="mf[]" value="<?php echo show_xxsx222(6)?>">
              �� </td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA"><input name="mf[]" type="checkbox" id="mf[]" value="<?php echo show_xxsx222(12)?>">
              �� </td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA">&nbsp;</td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA">&nbsp;</td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA">&nbsp;</td>
            </tr>
            <tr align="middle">
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA"><input name="mf[]" type="checkbox" id="mf[]" value="<?php echo show_xxsx222(13)?>">
              <span class="STYLE1">�쵥</span></td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA"><input name="mf[]" type="checkbox" id="mf[]" value="<?php echo show_xxsx222(14)?>">
              <span class="STYLE1">��˫</span></td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA"><input name="mf[]" type="checkbox" id="mf[]" value="<?php echo show_xxsx222(15)?>">
              <span class="STYLE1">���</span></td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA"><input name="mf[]" type="checkbox" id="mf[]" value="<?php echo show_xxsx222(16)?>">
              <span class="STYLE1">��С</span></td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA"><input name="mf[]" type="checkbox" id="mf[]" value="<?php echo show_xxsx222(21)?>">
              <span class="STYLE2">����</span></td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA"><input name="mf[]" type="checkbox" id="mf[]" value="<?php echo show_xxsx222(22)?>">
              <span class="STYLE2">��˫</span></td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA"><input name="mf[]" type="checkbox" id="mf[]" value="<?php echo show_xxsx222(23)?>">
              <span class="STYLE2">����</span></td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA"><input name="mf[]" type="checkbox" id="mf[]" value="<?php echo show_xxsx222(24)?>">
              <span class="STYLE2">��С</span></td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA"><input name="mf[]" type="checkbox" id="mf[]" value="<?php echo show_xxsx222(17)?>,49">
              <span class="STYLE4 STYLE3">�̵�</span></td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA"><input name="mf[]" type="checkbox" id="mf[]" value="<?php echo show_xxsx222(18)?>">
              <span class="STYLE4 STYLE3">��˫</span></td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA"><input name="mf[]" type="checkbox" id="mf[]" value="<?php echo show_xxsx222(19)?>,49">
              <span class="STYLE4 STYLE3">�̴�</span></td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA"><input name="mf[]" type="checkbox" id="mf[]" value="<?php echo show_xxsx222(20)?>">
              <span class="STYLE4 STYLE3">��С</span></td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA"><input name="mf[]" type="checkbox" id="mf[]" value="<?php echo show_xxsx222(16)?>,<?php echo show_xxsx222(15)?>">
              <span class="STYLE1">�첨</span></td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA"><input name="mf[]" type="checkbox" id="mf[]" value="<?php echo show_xxsx222(24)?>,<?php echo show_xxsx222(23)?>">
              <span class="STYLE2">����</span></td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA"><input name="mf[]" type="checkbox" id="mf[]" value="<?php echo show_xxsx222(20)?>,<?php echo show_xxsx222(19)?>,49">
              <span class="STYLE4 STYLE3">�̲�</span></td>
            </tr>
            <tr align="middle">
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA"><input name="mf[]" type="checkbox" id="mf[]" value="1,3,5,7,9,11,13,15,17,19,21,23,25,27,29,31,33,35,37,39,41,43,45,47,49">
              ����</td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA"><input name="mf[]" type="checkbox" id="mf[]" value="2,4,6,8,10,12,14,16,18,20,22,24,26,28,30,32,34,36,38,40,42,44,46,48">
              ˫��</td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA"><input name="mf[]" type="checkbox" id="mf[]" value="25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49">
              ���</td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA"><input name="mf[]" type="checkbox" id="mf[]" value="1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24">
              С��</td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA"><input name="mf[]" type="checkbox" id="mf[]" value="1,3,5,7,9,10,12,14,16,18,21,23,25,27,29,30,32,34,36,38,41,43,45,47,49">
              �ϵ�</td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA"><input name="mf[]" type="checkbox" id="mf[]" value="2,4,6,8,11,13,15,17,19,20,22,24,26,28,31,33,35,37,39,40,42,44,46,48">
              ��˫</td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA">&nbsp;</td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA"><input name="mf[]" type="checkbox" id="mf[]" value="1,2,3,4,5,6,7,8,9">
              0ͷ</td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA"><input name="mf[]" type="checkbox" id="mf[]" value="10,11,12,13,14,15,16,17,18,19">
              1ͷ</td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA"><input name="mf[]" type="checkbox" id="mf[]" value="20,21,22,23,24,25,26,27,28,29">
              2ͷ</td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA"><input name="mf[]" type="checkbox" id="mf[]" value="30,31,32,33,34,35,36,37,38,39">
              3ͷ</td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA"><input name="mf[]" type="checkbox" id="mf[]" value="40,41,42,43,44,45,46,47,48,49">
              4ͷ</td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA">&nbsp;</td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA">&nbsp;</td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA">&nbsp;</td>
            </tr>
            <tr align="middle">
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA"><input name="mf[]" type="checkbox" id="mf[]" value="10,20,30,40">
              0β</td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA"><input name="mf[]" type="checkbox" id="mf[]" value="1,11,21,31,41">
              1β</td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA"><input name="mf[]" type="checkbox" id="mf[]" value="2,12,22,32,42">
              2β</td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA"><input name="mf[]" type="checkbox" id="mf[]" value="3,13,23,33,43">
              3β</td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA"><input name="mf[]" type="checkbox" id="mf[]" value="4,14,24,34,44">
              4β</td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA"><input name="mf[]" type="checkbox" id="mf[]" value="5,15,25,35,45">
              5β</td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA"><input name="mf[]" type="checkbox" id="mf[]" value="6,16,26,36,46">
              6β</td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA"><input name="mf[]" type="checkbox" id="mf[]" value="7,17,27,37,47">
              7β</td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA"><input name="mf[]" type="checkbox" id="mf[]" value="8,18,28,38,48">
              8β</td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA"><input name="mf[]" type="checkbox" id="mf[]" value="9,19,29,39,49">
              9β</td>
              <td align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA">&nbsp;</td>
              <td colspan="4" align="left" nowrap bordercolor="cccccc" bgcolor="#FDF4CA"><table border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td>��</td>
                    <td><input name="mv" type="radio" value="0" checked></td>
                    <td><INPUT name=money class="input1" value="0.5" size=4></td>
                    <td><input type="radio" name="mv" value="1"></td>
                    <td>��</td>
                    <td>&nbsp;
                        <INPUT name="button2" class="button_c" type=submit value=�ͳ�></td>
                    <td>&nbsp;
                        <INPUT type=reset class="button_c"  value="ȡ��" name="reset"></td>
                  </tr>
              </table></td>
            </tr>
          </form>
</table>
	 <table width="100%" border="0" cellspacing="0" cellpadding="0">
   <form action="index.php?action=rake_tm&save=save&ids=<?php echo $ids?>" name="form21" method="post" ><tr>
    <td height="25" align="center"><span class="STYLE1">ͳһ�޸ģ�</span>
      <input type="radio" name="dx" value="��">
      �� 
        <input type="radio" name="dx" value="˫">
        ˫
        <input type="radio" name="dx" value="��">
        ��
        <input type="radio" name="dx" value="С">
        С
        <input type="radio" name="dx" value="�첨">
        �첨 
        <input type="radio" name="dx" value="�̲�">
        �̲�
        <input type="radio" name="dx" value="����">
        ����
<input name="dx" type="radio" value="ȫ��" checked>
ȫ�� <span class="STYLE1">����</span>
<input name="bl"  class="input1" id="bl" 
      style="HEIGHT: 18px" value="0" size="6" /> 
<input type="submit"   class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'" name="Submit22" value="ͳһ�޸�" /></td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
  </form>
</table>
      

<SCRIPT language=javascript>
 makeRequest('index.php?action=server&class1=����&class2=<?php echo $ids?>')</script>
