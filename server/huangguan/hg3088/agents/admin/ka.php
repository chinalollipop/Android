<?php

require_once dirname(__FILE__).'/conjunction.php';

require_once dirname(__FILE__).'/config.php';


$resultbb=mysql_query("select * from ka_kithe where nn=".$_GET['kithe']." order by id desc LIMIT 1"); 
$row=mysql_fetch_array($resultbb);
$kithe=$row['nn'];
$na=$row['na'];
$n1=$row['n1'];
$n2=$row['n2'];
$n3=$row['n3'];
$n4=$row['n4'];
$n5=$row['n5'];
$n6=$row['n6'];
$sxsx=$row['sx'];


//结算合肖
if ($na<10){$naa="0".$na;}else{$naa=$na;}
$sxsx=Get_sx_Color($naa);
echo $na;exit;
if ($na==49) {
mysql_query("update ka_tan set bm=2 where kithe=".$kithe." and class1='生肖' and (class2='二肖'  or class2='三肖'  or class2='四肖' or class2='五肖'  or class2='六肖' or class2='七肖'  or class2='八肖' or class2='九肖'  or class2='十肖' or class2='十一肖' ) ");
$result1dd = mysql_query("Select sum(sum_m) as sum_m,count(*) as re from ka_tan where kithe=".$kithe." and class1='生肖' and (class2='二肖'  or class2='三肖'  or class2='四肖' or class2='五肖'  or class2='六肖'  or class2='七肖'  or class2='八肖' or class2='九肖'  or class2='十肖' or class2='十一肖' )");
$Rs5 = mysql_fetch_array($result1dd);
if ($Rs5!=""){$zwin=$Rs5['re'];}else{$zwin=0;}

}else{
mysql_query("update ka_tan set bm=0 where kithe=".$kithe." and class1='生肖' and (class2='二肖'  or class2='三肖'  or class2='四肖' or class2='五肖'  or class2='六肖'  or class2='七肖'  or class2='八肖' or class2='九肖'  or class2='十肖' or class2='十一肖' ) and bm<>0 ");
mysql_query("update ka_tan set bm=1 where kithe=".$kithe." and class1='生肖' and (class2='二肖'  or class2='三肖'  or class2='四肖' or class2='五肖'  or class2='六肖'  or class2='七肖'  or class2='八肖' or class2='九肖'  or class2='十肖' or class2='十一肖' ) and class3 LIKE '%$sxsx%' ");

$result1 = mysql_query("Select sum(sum_m) as sum_m,count(*) as re from ka_tan where kithe=".$kithe." and class1='生肖' and (class2='二肖'  or class2='三肖'  or class2='四肖' or class2='五肖'  or class2='六肖'  or class2='七肖'  or class2='八肖' or class2='九肖'  or class2='十肖' or class2='十一肖') and class3 LIKE '%$sxsx%' ");
$Rs5 = @mysql_fetch_array($result1);
if ($Rs5!=""){$zwin=$Rs5['re'];}else{$zwin=0;}
}

if ($zwin!=0){
echo "合肖结算成功：<font color=ff6600>".$zwin."注</font><br>";}
?>