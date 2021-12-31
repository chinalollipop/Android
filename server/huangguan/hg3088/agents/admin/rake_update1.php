<?php
require_once dirname(__FILE__).'/conjunction.php';
///��������   
  
  $Current_Kithe_Num=1;
  $result=mysqli_query($dbLink,"Select ID,NN,ND,NA,N1,N2,N3,N4,N5,N6,lx,kitm,kitm1,kizt,kizt1,kizm,kizm1,kizm6,kizm61,kigg,kigg1,kilm,kilm1,kisx,kisx1,kibb,kibb1,kiws,kiws1,zfb,zfbdate,zfbdate1,best From ka_Kithe where na=0 Order By ID Desc LIMIT 1"); 

$Current_KitheTable=mysqli_fetch_assoc($result);
	$Current_Kithe_Num=$Current_KitheTable[1];
///��ɫ  
 
function Get_bs_Color($i){   
   $result=mysqli_query($dbLink,"Select ID,color From ka_color where id=".$i." Order By ID"); 
$ka_configg=mysqli_fetch_assoc($result); 
return $ka_configg['color'];
   }
if ($admin_info!="1"){
echo "<script>alert('请先登录!');top.location.href='index.php';</script>";
exit;
}
 $text=date("Y-m-d H:i:s");
$commandName=$_GET['commandName'];
$class1=$_GET['class1'];
//$class2=$_GET['class2'];
$ids=$_GET['ids'];
//$sqq=$_GET['sqq'];
$class3=$_GET['class3'];
$qtqt=$_GET['qtqt'];
$lxlx=$_GET['lxlx'];

if ($lxlx==1){  //��
if ($class1=="����" or $class1=="����"){

if ($class3=="ǰʮ��"){
if ($class1=="����"){require_once 's_zm.php';}else{
require_once 's_tm.php';}
$css=$_GET['css'];
$arr=explode(",",$css);

for($i=0;$i<15;$i++)
{
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate+".$qtqt." where class1='".$class1."' and  class3='".$arr[$i]."' ");
}

}elseif ($class3=="ǰʮ"){
if ($class1=="����"){require_once 's_zm.php';}else{
require_once 's_tm.php';}
$css=$_GET['css'];
$arr=explode(",",$css);

for($i=0;$i<10;$i++)
{
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate+".$qtqt." where class1='".$class1."' and  class3='".$arr[$i]."' ");
}

}elseif ($class3=="ǰ��"){
if ($class1=="����"){require_once 's_zm.php';}else{
require_once 's_tm.php';}
$css=$_GET['css'];
$arr=explode(",",$css);

for($i=0;$i<5;$i++)
{
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate+".$qtqt." where class1='".$class1."' and  class3='".$arr[$i]."' ");
}

}elseif ($class3=="ǰ��"){
if ($class1=="����"){require_once 's_zm.php';}else{
require_once 's_tm.php';}
$css=$_GET['css'];
$arr=explode(",",$css);

for($i=0;$i<3;$i++)
{
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate+".$qtqt." where class1='".$class1."' and  class3='".$arr[$i]."' ");
}

}
for ($tt=1; $tt<=49; $tt++) {
if ($class3=="all"){
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate+".$qtqt." where class1='".$class1."' and  class3='".$tt."' ");
}elseif ($class3=="��"){
if ($tt%2==1){
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate+".$qtqt." where class1='".$class1."' and  class3='".$tt."' ");
}
}elseif ($class3=="˫"){
if ($tt%2==0){
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate+".$qtqt." where class1='".$class1."' and  class3='".$tt."' ");
}
}elseif ($class3=="��"){
if ($tt>=25){
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate+".$qtqt." where class1='".$class1."' and  class3='".$tt."' ");
}
}elseif ($class3=="С"){
if ($tt<=24){
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate+".$qtqt." where class1='".$class1."' and  class3='".$tt."' ");
}
}elseif ($class3=="�ϵ�"){
if ((($tt%10)+intval($tt/10))%2==1){
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate+".$qtqt." where class1='".$class1."'  and  class3='".$tt."'");
}
}elseif ($class3=="��˫"){
if ((($tt%10)+intval($tt/10))%2==0){
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate+".$qtqt." where class1='".$class1."' and  class3='".$tt."' ");
}
}elseif ($class3=="��"){
if (Get_bs_Color($tt)=="r"){
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate+".$qtqt." where class1='".$class1."' and  class3='".$tt."' ");
}
}elseif ($class3=="��"){
if (Get_bs_Color($tt)=="b"){
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate+".$qtqt." where class1='".$class1."' and  class3='".$tt."' ");
}
}elseif ($class3=="��"){
if (Get_bs_Color($tt)=="g"){
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate+".$qtqt." where class1='".$class1."' and  class3='".$tt."' ");
}
}

}//for
}else{ ///����


if ($class3=="ǰʮ��"){
require_once 's_zt.php';
for($i=0;$i<15;$i++)
{
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate+".$qtqt." where class1='".$class1."' and  class3='".$sum_tm[$i]."' ");
}

}elseif ($class3=="ǰʮ"){
require_once 's_zt.php';
for($i=0;$i<10;$i++)
{
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate+".$qtqt." where class1='".$class1."'  and  class2='".$ids."' and  class3='".$sum_tm[$i]."' ");
}

}elseif ($class3=="ǰ��"){
require_once 's_zt.php';
for($i=0;$i<5;$i++)
{
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate+".$qtqt." where class1='".$class1."' and  class2='".$ids."' and  class3='".$sum_tm[$i]."' ");
}

}elseif ($class3=="ǰ��"){
require_once 's_zt.php';
for($i=0;$i<3;$i++)
{
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate+".$qtqt." where class1='".$class1."' and  class2='".$ids."' and  class3='".$sum_tm[$i]."' ");
}

}
for ($tt=1; $tt<=49; $tt++) {



if ($class3=="all"){
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate+".$qtqt." where class1='".$class1."' and  class2='".$ids."' and class3='".$tt."' ");
}elseif ($class3=="��"){
if ($tt%2==1){
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate+".$qtqt." where class1='".$class1."' and  class2='".$ids."' and  class3='".$tt."' ");
}
}elseif ($class3=="˫"){
if ($tt%2==0){
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate+".$qtqt." where class1='".$class1."' and  class2='".$ids."' and  class3='".$tt."' ");
}
}elseif ($class3=="��"){
if ($tt>=25){
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate+".$qtqt." where class1='".$class1."' and  class2='".$ids."' and  class3='".$tt."' ");
}
}
elseif ($class3=="С"){
if ($tt<=24){
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate+".$qtqt." where class1='".$class1."' and  class2='".$ids."' and  class3='".$tt."' ");
}
}elseif ($class3=="�ϵ�"){
if ((($tt%10)+intval($tt/10))%2==1){
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate+".$qtqt." where class1='".$class1."' and  class2='".$ids."'  and  class3='".$tt."'");
}
}elseif ($class3=="��˫"){
if ((($tt%10)+intval($tt/10))%2==0){
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate+".$qtqt." where class1='".$class1."'and  class2='".$ids."'  and  class3='".$tt."' ");
}
}elseif ($class3=="��"){
if (Get_bs_Color($tt)=="r"){
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate+".$qtqt." where class1='".$class1."' and  class2='".$ids."' and  class3='".$tt."' ");
}
}elseif ($class3=="��"){
if (Get_bs_Color($tt)=="b"){
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate+".$qtqt." where class1='".$class1."' and  class2='".$ids."' and  class3='".$tt."' ");
}
}elseif ($class3=="��"){
if (Get_bs_Color($tt)=="g"){
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate+".$qtqt." where class1='".$class1."' and  class2='".$ids."' and  class3='".$tt."' ");
}

}
}

}



}else{  //��

if ($class1=="����" or $class1=="����"){
if ($class3=="ǰʮ��"){
if ($class1=="����"){require_once 's_zm.php';}else{
require_once 's_tm.php';}
$css=$_GET['css'];
$arr=explode(",",$css);

for($i=0;$i<15;$i++)
{
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate-".$qtqt." where class1='".$class1."' and  class3='".$arr[$i]."' ");
}

}elseif ($class3=="ǰʮ"){
if ($class1=="����"){require_once 's_zm.php';}else{
require_once 's_tm.php';}
$css=$_GET['css'];
$arr=explode(",",$css);

for($i=0;$i<10;$i++)
{
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate-".$qtqt." where class1='".$class1."' and  class3='".$arr[$i]."' ");
}

}elseif ($class3=="ǰ��"){
if ($class1=="����"){require_once 's_zm.php';}else{
require_once 's_tm.php';}
$css=$_GET['css'];
$arr=explode(",",$css);

for($i=0;$i<5;$i++)
{
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate-".$qtqt." where class1='".$class1."' and  class3='".$arr[$i]."' ");
}

}elseif ($class3=="ǰ��"){
if ($class1=="����"){require_once 's_zm.php';}else{
require_once 's_tm.php';}
$css=$_GET['css'];
$arr=explode(",",$css);

for($i=0;$i<3;$i++)
{
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate-".$qtqt." where class1='".$class1."' and  class3='".$arr[$i]."' ");

}

}

for ($tt=1; $tt<=49; $tt++) {
if ($class3=="all"){

$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate-".$qtqt." where class1='".$class1."' and  class3='".$tt."' ");

}elseif ($class3=="��"){
if ($tt%2==1){
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate-".$qtqt." where class1='".$class1."' and  class3='".$tt."' ");
}

}elseif ($class3=="˫"){
if ($tt%2==0){
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate-".$qtqt." where class1='".$class1."' and  class3='".$tt."' ");
}
}elseif ($class3=="��"){
if ($tt>=25){
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate-".$qtqt." where class1='".$class1."' and  class3='".$tt."' ");
}
}
elseif ($class3=="С"){
if ($tt<=24){
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate-".$qtqt." where class1='".$class1."' and  class3='".$tt."' ");
}
}elseif ($class3=="�ϵ�"){
if ((($tt%10)+intval($tt/10))%2==1){
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate-".$qtqt." where class1='".$class1."'  and  class3='".$tt."'");
}
}elseif ($class3=="��˫"){
if ((($tt%10)+intval($tt/10))%2==0){
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate-".$qtqt." where class1='".$class1."' and  class3='".$tt."' ");
}
}elseif ($class3=="��"){
if (Get_bs_Color($tt)=="r"){
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate-".$qtqt." where class1='".$class1."' and  class3='".$tt."' ");
}
}elseif ($class3=="��"){
if (Get_bs_Color($tt)=="b"){
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate-".$qtqt." where class1='".$class1."' and  class3='".$tt."' ");
}
}elseif ($class3=="��"){
if (Get_bs_Color($tt)=="g"){
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate-".$qtqt." where class1='".$class1."' and  class3='".$tt."' ");
}

}
}

}else{ ///����


if ($class3=="ǰʮ��"){
require_once 's_zt.php';
for($i=0;$i<15;$i++)
{
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate-".$qtqt." where class1='".$class1."' and  class3='".$sum_tm[$i]."' ");
}

}elseif ($class3=="ǰʮ"){
require_once 's_zt.php';
for($i=0;$i<10;$i++)
{
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate-".$qtqt." where class1='".$class1."'  and  class2='".$ids."' and  class3='".$sum_tm[$i]."' ");
}

}elseif ($class3=="ǰ��"){
require_once 's_zt.php';
for($i=0;$i<5;$i++)
{
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate-".$qtqt." where class1='".$class1."' and  class2='".$ids."' and  class3='".$sum_tm[$i]."' ");
}

}elseif ($class3=="ǰ��"){
require_once 's_zt.php';
for($i=0;$i<3;$i++)
{
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate-".$qtqt." where class1='".$class1."' and  class2='".$ids."' and  class3='".$sum_tm[$i]."' ");
}

}
for ($tt=1; $tt<=49; $tt++) {
if ($class3=="all"){

$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate-".$qtqt." where class1='".$class1."' and  class2='".$ids."' and class3='".$tt."' ");

}elseif ($class3=="��"){
if ($tt%2==1){
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate-".$qtqt." where class1='".$class1."' and  class2='".$ids."' and  class3='".$tt."' ");
}

}elseif ($class3=="˫"){
if ($tt%2==0){
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate-".$qtqt." where class1='".$class1."' and  class2='".$ids."' and  class3='".$tt."' ");
}
}elseif ($class3=="��"){
if ($tt>=25){
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate-".$qtqt." where class1='".$class1."' and  class2='".$ids."' and  class3='".$tt."' ");
}
}
elseif ($class3=="С"){
if ($tt<=24){
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate-".$qtqt." where class1='".$class1."' and  class2='".$ids."' and  class3='".$tt."' ");
}
}elseif ($class3=="�ϵ�"){
if ((($tt%10)+intval($tt/10))%2==1){
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate-".$qtqt." where class1='".$class1."' and  class2='".$ids."'  and  class3='".$tt."'");
}
}elseif ($class3=="��˫"){
if ((($tt%10)+intval($tt/10))%2==0){
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate-".$qtqt." where class1='".$class1."'and  class2='".$ids."'  and  class3='".$tt."' ");
}
}elseif ($class3=="��"){
if (Get_bs_Color($tt)=="r"){
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate-".$qtqt." where class1='".$class1."' and  class2='".$ids."' and  class3='".$tt."' ");
}
}elseif ($class3=="��"){
if (Get_bs_Color($tt)=="b"){
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate-".$qtqt." where class1='".$class1."' and  class2='".$ids."' and  class3='".$tt."' ");
}
}elseif ($class3=="��"){
if (Get_bs_Color($tt)=="g"){
$exe=mysqli_query($dbLink,"update ka_bl set adddate='". $text."',rate=rate-".$qtqt." where class1='".$class1."' and  class2='".$ids."' and  class3='".$tt."' ");

}
}
}
}


}

echo "ok";



?>
