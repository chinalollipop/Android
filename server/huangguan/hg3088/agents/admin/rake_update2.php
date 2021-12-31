<?php
require_once dirname(__FILE__).'/conjunction.php';
if ($admin_info!="1"){
echo "<script>alert('请先登录!');top.location.href='index.php';</script>";
exit;
}
 $text=date("Y-m-d H:i:s");
$commandName=$_GET['commandName'];
$class1=$_GET['class1'];
$class2=$_GET['class2'];
$ids=$_GET['ids'];
//$sqq=$_GET['sqq'];
$class3=$_GET['class3'];
$qtqt=$_GET['qtqt'];
$lxlx=$_GET['lxlx'];
if ($commandName=="MODIFYRATE"){

if ($lxlx==1){
/*
//��1-6
if ($class1=="��1-6"){
$exe=mysqli_query($dbLink,"update ".DBPREFIX."mdrop set adddate='". $text."',rate=round(rate+".$qtqt.",2) where class1='".$class1."' and class2='".$ids."'  and   class3='".$class3."'");
}

//����
if ($class1=="����"){
$exe=mysqli_query($dbLink,"update ".DBPREFIX."mdrop set adddate='". $text."',rate=round(rate+".$qtqt.",2)  where class1='".$class1."' and class2='".$ids."'  and   class3='".$class3."'");
}

//����
if ($class1=="����"){
$exe=mysqli_query($dbLink,"update ".DBPREFIX."mdrop set adddate='". $text."',rate=round(rate+".$qtqt.",2)  where class1='".$class1."' and class2='".$ids."'  and   class3='".$class3."'");
}

//��ѡ
if ($class1=="��ѡ"){
$exe=mysqli_query($dbLink,"update ".DBPREFIX."mdrop set adddate='". $text."',rate=round(rate+".$qtqt.",2)  where class1='".$class1."' and class2='".$ids."'  and   class3='".$class3."'");
}
*/

if ($class1=="����" or $class1=="����"){
$exe=mysqli_query($dbLink,"update ".DBPREFIX."mdrop set adddate='". $text."',rate=round(rate+".$qtqt.",2)  where class1='".$class1."'  and  class2='".$class2."'  and   class3='".$class3."'");
}else{


if ($class1=="����"){

   if ($ids=="��1��"){ $class22="����1"; }
  if ($ids=="��2��"){ $class22="����2"; }
   if ($ids=="��3��"){ $class22="����3"; }
    if ($ids=="��4��"){ $class22="����4"; }
	 if ($ids=="��5��"){ $class22="����5"; }
	  if ($ids=="��6��"){ $class22="����6"; }
	  if ($class3=="��" || $class3=="˫" || $class3=="��" || $class3=="С" || $class3=="�첨" || $class3=="����" || $class3=="�̲�" ){
$exe=mysqli_query($dbLink,"update ".DBPREFIX."mdrop set adddate='". $text."',rate=round(rate+".$qtqt.",2)  where class1='����' and class2='".$class22."'  and   class3='".$class3."'");
$exe=mysqli_query($dbLink,"update ".DBPREFIX."mdrop set adddate='". $text."',rate=round(rate+".$qtqt.",2)  where class1='".$class1."' and class2='".$ids."'  and   class3='".$class3."'");
}else{

$exe=mysqli_query($dbLink,"update ".DBPREFIX."mdrop set adddate='". $text."',rate=round(rate+".$qtqt.",2)  where class1='".$class1."' and class2='".$ids."'  and   class3='".$class3."'");
}


}else{
$exe=mysqli_query($dbLink,"update ".DBPREFIX."mdrop set adddate='". $text."',rate=round(rate+".$qtqt.",2)  where class1='".$class1."' and class2='".$ids."'  and   class3='".$class3."'");}


}


}else{
/*
//��1-6
if ($class1=="��1-6"){
$exe=mysqli_query($dbLink,"update ".DBPREFIX."mdrop set adddate='". $text."',rate=round(rate-".$qtqt.",2) where class1='".$class1."' and class2='".$ids."'  and   class3='".$class3."'");
}

//����
if ($class1=="����"){
$exe=mysqli_query($dbLink,"update ".DBPREFIX."mdrop set adddate='". $text."',rate=round(rate-".$qtqt.",2) where class1='".$class1."' and class2='".$ids."'  and   class3='".$class3."'");
}

//����
if ($class1=="����"){
$exe=mysqli_query($dbLink,"update ".DBPREFIX."mdrop set adddate='". $text."',rate=round(rate-".$qtqt.",2) where class1='".$class1."' and class2='".$ids."'  and   class3='".$class3."'");
}

//��ѡ
if ($class1=="��ѡ"){
$exe=mysqli_query($dbLink,"update ".DBPREFIX."mdrop set adddate='". $text."',rate=round(rate-".$qtqt.",2) where class1='".$class1."' and class2='".$ids."'  and   class3='".$class3."'");
}
*/

if ($class1=="����" or $class1=="����"){
$exe=mysqli_query($dbLink,"update ".DBPREFIX."mdrop set adddate='". $text."',rate=round(rate-".$qtqt.",2) where class1='".$class1."'  and   class3='".$class3."'");
}else{



if ($class1=="����"){

   if ($ids=="��1��"){ $class22="����1"; }
  if ($ids=="��2��"){ $class22="����2"; }
   if ($ids=="��3��"){ $class22="����3"; }
    if ($ids=="��4��"){ $class22="����4"; }
	 if ($ids=="��5��"){ $class22="����5"; }
	  if ($ids=="��6��"){ $class22="����6"; }
	  if ($class3=="��" || $class3=="˫" || $class3=="��" || $class3=="С" || $class3=="�첨" || $class3=="����" || $class3=="�̲�" ){
$exe=mysqli_query($dbLink,"update ".DBPREFIX."mdrop set adddate='". $text."',rate=round(rate-".$qtqt.",2) where class1='����' and class2='".$class22."'  and   class3='".$class3."'");
$exe=mysqli_query($dbLink,"update ".DBPREFIX."mdrop set adddate='". $text."',rate=round(rate-".$qtqt.",2) where class1='".$class1."' and class2='".$ids."'  and   class3='".$class3."'");
}else{

$exe=mysqli_query($dbLink,"update ".DBPREFIX."mdrop set adddate='". $text."',rate=round(rate-".$qtqt.",2) where class1='".$class1."' and class2='".$ids."'  and   class3='".$class3."'");
}


}else{
$exe=mysqli_query($dbLink,"update ".DBPREFIX."mdrop set adddate='". $text."',rate=round(rate-".$qtqt.",2) where class1='".$class1."' and class2='".$ids."' and   class3='".$class3."'");}
}
}



$result3 = mysqli_query($dbLink,"select * from ".DBPREFIX."mdrop where  class1='".$class1."' and class2='".$ids."' and class3='".$class3."' order by id"); 
$image = mysqli_fetch_assoc($result3);
$rate=$image['rate'];
echo $rate;
exit;
}
if ($commandName=="LOCK"){
$lock=$_GET['lock'];
if ($lock=="true"){$lock1=1;}else{$lock1=0;}
$exe=mysqli_query($dbLink,"update ".DBPREFIX."mdrop set locked=".$lock1." where class1='".$class1."' and class2='".$ids."' and   class3='".$class3."'");
echo $lock1;
exit;


}


?>

