<?php
if(!defined('PHPYOU')) {
	exit('�Ƿ�����');
}

$class1=$_GET['class1'];
$class2=$_GET['class2'];


if ($class1=="��1-6" or $class1=="����" or $class1=="����" or $class1=="�벨" or $class1=="��벨" or $class1=="ͷ��" or $class1=="β��" or $class1=="����β��" or $class1=="��ɫ��" or $class1=="��Ф"){

$sql="select * from ka_bl where  class1='".$class1."'";

if ($class1=="����β��")$sql.=" or (class1='��Ф' and class2='һФ')";

if ($class1=="����")
$sql.="  order by id";
else
$sql.="  order by class2,id";
$result = mysqli_query($dbLink,$sql); //

}else{
if ($class1=="ͷβ��"){
$sql_ts="Select * from (Select * from ka_bl where class1='ͷ��'   Order By ID) as a";
$sql_ws="Select * from (Select * from ka_bl where class1='β��'   Order By ID) as b";
$sql="Select c.* from (".$sql_ts." union all ".$sql_ws.") as c";
$result = mysqli_query($dbLink,$sql);

}elseif ($class1=="��Ф"){
$result = mysqli_query($dbLink,"Select * from ka_bl where (class1='��Ф' and class2='".$class2."') Order By ID");

}elseif ($class1=="��Ф��" ){
$result = mysqli_query($dbLink,"Select * from ka_bl where (class1='��Ф��' and class2='".$class2."') Order By ID");

}elseif ($class1=="��ɫ��"){
$result = mysqli_query($dbLink,"Select rate,class3,class2,locked,class1 from ka_bl where class1='��ɫ��' or class1='��Ф'   Order By ID Desc");

}else{
$result = mysqli_query($dbLink,"select * from ka_bl where  class1='".$class1."' and class2='".$class2."' order by id"); }

}

while($image = mysqli_fetch_assoc($result)){


if ($class1=="��Ф"  or $class1=="��Ф��" ){

$result1=mysqli_query($dbLink,"Select SUM(sum_m) As sum_m from ka_tan where kithe='".$Current_Kithe_Num."' and  class1='".$image['class1']."' and  class2='".$image['class2']."' and class3='".$image['class3']."' "); 
$rs5=mysqli_fetch_assoc($result1);
}else{
$result2=mysqli_query($dbLink,"Select SUM(sum_m) As sum_m from ka_tan where kithe='".$Current_Kithe_Num."' and  class1='".$image['class1']."' and  class2='".$image['class2']."' and class3='".$image['class3']."' "); 
$rs5=mysqli_fetch_assoc($result2);
}
if ($rs5['sum_m']==""){$sum_m=0;}else{$sum_m=$rs5['sum_m'];}
$rate=$image['rate'];
if ($image['rate']!=$image['blrate']){
$blbl.=$image['class3']."@@@".$rate."@@@".$image['rate']."@@@".$sum_m."###";}else{
$blbl.=$image['class3']."@@@".$rate."@@@".$image['rate']."@@@".$sum_m."###";

}


}




echo $blbl;
$ddf=date( "Y-m-d H:i:s",time()-20);
if ($class1=="ͷβ��"){
$exe=mysqli_query($dbLink,"update ka_bl set blrate=rate where class1='ͷ��' and blrate<>rate and adddate<'".$ddf."'");
$exe=mysqli_query($dbLink,"update ka_bl set blrate=rate where class1='β��' and blrate<>rate and adddate<'".$ddf."'");
}else{
$exe=mysqli_query($dbLink,"update ka_bl set blrate=rate where class1='".$class1."' and blrate<>rate and adddate<'".$ddf."'");
}
?>
