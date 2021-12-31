<?php
if(!defined('PHPYOU')) {
	exit('�Ƿ�����');
}

$result=mysqli_query($dbLink,"select * from ".DBPREFIX."adad order by id"); 
$row=mysqli_fetch_assoc($result);

$best=$row['best'];	
	$zm=$row['zm'];
	$zm6=$row['zm6'];
	$lm=$row['lm'];	
	$zlm=$row['zlm'];
	$ys=$row['ys'];
	$ls=$row['ls'];
	$dx=$row['dx'];
	$tm=$row['tm'];
	$spx=$row['spx'];
	$bb=$row['bb'];
	$bbb=$row['bbb'];
	$zhx=$row['zhx'];
	$zmt=$row['zmt'];
	$qsb=$row['qsb'];
	$ws=$row['ws'];
	$zm1=$row['zm1'];
	$zm61=$row['zm61'];
	$lm1=$row['lm1'];	
	$zlm1=$row['zlm1'];
	$ys1=$row['ys1'];
	$ls1=$row['ls1'];
	$dx1=$row['dx1'];
	$tm1=$row['tm1'];
	$spx1=$row['spx1'];
	$bb1=$row['bb1'];
	$bbb1=$row['bbb1'];
	$zhx1=$row['zhx1'];
	$zmt1=$row['zmt1'];
	$qsb1=$row['qsb1'];
	$ws1=$row['ws1'];
	$ps1=$row['ps1'];
	$ps=$row['ps'];	
$ztm_tm=$bb;

$class1=$_GET['class1'];
$class2=$_GET['class2'];

if ($class1=="��벨")$ztm_tm=$bbb;
if ($class1=="��Ф")$ztm_tm=$zhx;
if ($class1=="��ɫ��")$ztm_tm=$qsb;
if ($_GET['kithe']!=""){$kithe=$_GET['kithe'];}else{$kithe=$Current_Kithe_Num;}



$z_re=0;
$z_sum=0;
$z_suma=0;
$z_sumb=0;
$z_ds=0;
$z_xx=0;
$z_pz=0;

$z7_sum=0;
$z7_ds=0;
//��������˫
$z_re_ds=0;
$z_sum_ds=0;
$z_suma_ds=0;
$z_sumb_ds=0;
$z_ds_ds=0;
$z_xx_ds=0;
$z_pz_ds=0;

$z7_sum_ds=0;
$z7_ds_ds=0;
//��������С
$z_re_dx=0;
$z_sum_dx=0;
$z_suma_dx=0;
$z_sumb_dx=0;
$z_ds_dx=0;
$z_xx_dx=0;
$z_pz_dx=0;

$z7_sum_dx=0;
$z7_ds_dx=0;
//�������ϵ�˫
$z_re_hds=0;
$z_sum_hds=0;
$z_suma_hds=0;
$z_sumb_hds=0;
$z_ds_hds=0;
$z_xx_hds=0;
$z_pz_hds=0;

$z7_sum_hds=0;
$z7_ds_hds=0;



$result = mysqli_query($dbLink,"select distinct(class3),class1,class2   from   ka_tan where Kithe='".$kithe."' and  class1='".$class1."'  and class2='".$class2."'  order by class3 desc");   
$ii=0;
while($rs = mysqli_fetch_assoc($result)){

$result1 = mysqli_query($dbLink,"Select sum(sum_m) as sum_m,count(*) as re,sum(sum_m*guan_ds/100*dagu_zc/10) as sum_ds,sum(0-sum_m*rate*dagu_zc/10) as sum_m3 from ka_tan   where Kithe='".$kithe."' and lx=0 and  class1='".$rs['class1']."' and  class2='".$rs['class2']."'  and class3='".$rs['class3']."'");
$Rs5 = mysqli_fetch_assoc($result1);

$result2 = mysqli_query($dbLink,"Select sum(sum_m*rate+sum_m*(user_ds/100)) as sum_money,count(*) as re,sum(0-sum_m*guan_ds/100*dagu_zc/10) as sum_ds,sum(0-sum_m) as sum_m3 from ka_tan   where   Kithe='".$kithe."' and  lx=1 and  class1='".$rs['class1']."' and  class2='".$rs['class2']."' and class3='".$rs['class3']."'");
$Rs7 = mysqli_fetch_assoc($result2);

$result3 = mysqli_query($dbLink,"Select sum(sum_m*dagu_zc/10) as sum_moneya from ka_tan   where  Kithe='".$kithe."' and  lx=0 and  class1='".$rs['class1']."' and   class2='".$rs['class2']."' and class3='".$rs['class3']."'");
$RsA = mysqli_fetch_assoc($result3);
$result4 = mysqli_query($dbLink,"Select sum(sum_m*dagu_zc/10) as sum_moneyb from ka_tan   where  Kithe='".$kithe."' and  lx=0 and  class1='".$rs['class1']."' and class2='��B' and class3='".$rs['class3']."'");
$RsB = mysqli_fetch_assoc($result4);


$result5 = mysqli_query($dbLink,"Select * from ka_bl   where   class1='".$rs['class1']."' and  class2='".$rs['class2']."' and class3='".$rs['class3']."'");


$Rsbl = mysqli_fetch_assoc($result5);


//һ����¼��"###"����.ÿ��������"@@@"����. ������ֻ�����������ݵ����.



if ($rs['class2']=="���" or $rs['class3']=="��С" or $rs['class3']=="�쵥" or $rs['class3']=="��˫"){$sum_color[$ii]="ff0000";}
else if ($rs['class3']=="����" or $rs['class3']=="��С" or $rs['class3']=="����" or $rs['class3']=="��˫"){$sum_color[$ii]="0000FF";}
else if ($rs['class3']=="�̴�" or $rs['class3']=="��С" or $rs['class3']=="�̵�" or $rs['class3']=="��˫"){$sum_color[$ii]="00dd00";}
else if ($rs['class3']=="��ϵ�" or $rs['class3']=="���˫"){$sum_color[$ii]="ff0000";}
else if ($rs['class3']=="�̺ϵ�" or $rs['class3']=="�̺�˫"){$sum_color[$ii]="00dd00";}
else if ($rs['class3']=="���ϵ�" or $rs['class3']=="����˫"){$sum_color[$ii]="0000FF";}
else if ($rs['class3']=="���" or $rs['class3']=="���˫" or $rs['class3']=="��С��" or $rs['class3']=="��С˫"){$sum_color[$ii]="ff0000";}
else if ($rs['class3']=="�̴�" or $rs['class3']=="�̴�˫" or $rs['class3']=="��С��" or $rs['class3']=="��С˫"){$sum_color[$ii]="00dd00";}
else if ($rs['class3']=="����" or $rs['class3']=="����˫" or $rs['class3']=="��С��" or $rs['class3']=="��С˫"){$sum_color[$ii]="0000FF";}
else{$sum_color[$ii]="";}




$sum_tm[$ii]=$rs['class3'];
$sum_re[$ii]=$Rs5['re'];
if ($Rs5['sum_m']!=""){
$sum_m[$ii] = $Rs5['sum_m'];}else{$sum_m[$ii] =0;}


if ($RsA['sum_moneya']!=""){$sum_ma[$ii] =$RsA['sum_moneya'];}else{$sum_ma[$ii] =0;}
if ($RsB['sum_moneyb']!=""){$sum_mb[$ii] =$RsB['sum_moneyb'];}else{$sum_mb[$ii] =0;}

$sum_ds[$ii]=$Rs5['sum_ds'];

$sum_xx[$ii]=$Rs5['sum_m3'];

if ($Rsbl['rate']!=""){
$sum_bl[$ii]="<a style=\"cursor:hand\" onClick=\"UpdateRate('MODIFYRATE','lm','bl".$ii."','class1=".$rs['class1']."&ids=".$rs['class2']."&sqq=sqq&lxlx=0&qtqt=0.01&class3=".$rs['class3']."');\"><font color=0000ff>��</font></a><span id=bl".$ii.">".$Rsbl['rate']."</span><a style=\"cursor:hand\" onClick=\"UpdateRate('MODIFYRATE','lm','bl".$ii."','class1=".$rs['class1']."&ids=".$rs['class2']."&sqq=sqq&lxlx=1&qtqt=0.01&class3=".$rs['class3']."');\"><font color=ff0000>��</font></a>";
}else{
$sum_bl[$ii]=0;
}
if ($Rsbl['rate']!=""){
$sum_mbl[$ii]=$Rsbl['rate'];
}else{
$sum_mbl[$ii]=0;
}

if($sum_tm[$ii]=="�쵥"||$sum_tm[$ii]=="��˫"||$sum_tm[$ii]=="�̵�"||$sum_tm[$ii]=="��˫"||$sum_tm[$ii]=="����"||$sum_tm[$ii]=="��˫"){
$z_re_ds+=$Rs5['re'];

$z_sum_ds+=$Rs5['sum_m'];


$z7_ds_ds+=$Rs7['sum_ds'];

$z_suma_ds+=$RsA['sum_moneya'];
$z_sumb_ds+=$RsB['sum_moneyb'];
$z_ds_ds+=$Rs5['sum_ds'];
$z_xx_ds+=$Rs5['sum_m3'];
$z_pz_ds+=$Rs7['sum_m3'];
}elseif($sum_tm[$ii]=="���"||$sum_tm[$ii]=="��С"||$sum_tm[$ii]=="�̴�"||$sum_tm[$ii]=="��С"||$sum_tm[$ii]=="����"||$sum_tm[$ii]=="��С"){
$z_re_dx+=$Rs5['re'];

$z_sum_dx+=$Rs5['sum_m'];


$z7_ds_dx+=$Rs7['sum_ds'];

$z_suma_dx+=$RsA['sum_moneya'];
$z_sumb_dx+=$RsB['sum_moneyb'];
$z_ds_dx+=$Rs5['sum_ds'];
$z_xx_dx+=$Rs5['sum_m3'];
$z_pz_dx+=$Rs7['sum_m3'];
}elseif($sum_tm[$ii]=="��ϵ�"||$sum_tm[$ii]=="���˫"||$sum_tm[$ii]=="�̺ϵ�"||$sum_tm[$ii]=="�̺�˫"||$sum_tm[$ii]=="���ϵ�"||$sum_tm[$ii]=="����˫"){
$z_re_hds+=$Rs5['re'];

$z_sum_hds+=$Rs5['sum_m'];


$z7_ds_hds+=$Rs7['sum_ds'];

$z_suma_hds+=$RsA['sum_moneya'];
$z_sumb_hds+=$RsB['sum_moneyb'];
$z_ds_hds+=$Rs5['sum_ds'];
$z_xx_hds+=$Rs5['sum_m3'];
$z_pz_hds+=$Rs7['sum_m3'];
}
$z_re+=$Rs5['re'];

$z_sum+=$Rs5['sum_m'];

$z_suma+=$RsA['sum_moneya'];
$z_sumb+=$RsB['sum_moneyb'];
$z_ds+=$Rs5['sum_ds'];
$z_xx+=$Rs5['sum_m3'];
$z_pz+=$Rs7['sum_m3'];
$sum_sx1[$ii]=$Rs7['sum_money'];
if ($Rs7['sum_m3']!=""){$sum_pz1[$ii]=$Rs7['sum_m3'];}else{
$sum_pz1[$ii]=0;}

$ii++;
}

for($i=0;$i<$ii;$i++)
{
if($sum_tm[$i]=="�쵥"||$sum_tm[$i]=="��˫"||$sum_tm[$i]=="�̵�"||$sum_tm[$i]=="��˫"||$sum_tm[$i]=="����"||$sum_tm[$i]=="��˫"){
$sum_sx[$i]=$z_suma_ds-$z_ds_ds-$z_pz_ds*((1-$bb1/100))-($sum_ma[$i]-$sum_pz1[$i])*$sum_mbl[$i];
}elseif($sum_tm[$i]=="���"||$sum_tm[$i]=="��С"||$sum_tm[$i]=="�̴�"||$sum_tm[$i]=="��С"||$sum_tm[$i]=="����"||$sum_tm[$i]=="��С"){
$sum_sx[$i]=$z_suma_dx-$z_ds_dx-$z_pz_dx*((1-$bb1/100))-($sum_ma[$i]-$sum_pz1[$i])*$sum_mbl[$i];
}elseif($sum_tm[$i]=="��ϵ�"||$sum_tm[$i]=="���˫"||$sum_tm[$i]=="�̺ϵ�"||$sum_tm[$i]=="�̺�˫"||$sum_tm[$i]=="���ϵ�"||$sum_tm[$i]=="����˫"){
$sum_sx[$i]=$z_suma_hds-$z_ds_hds-$z_pz_hds*((1-$bb1/100))-($sum_ma[$i]-$sum_pz1[$i])*$sum_mbl[$i];
}
//if ($i==0){
//$sum_sx[0]=($z_suma+$z_sumb+$z_ds)+$sum_xx[0]-$sum_sx1[0];
//}else{
//$sum_sx[$i]=($z_suma+$z_sumb+$z_ds)+$sum_xx[$i]-$sum_sx1[$i];}
}


$b=0;

for($b=0;$b<$ii;$b++)
{
$i=0;
for($i=0;$i<$ii-1;$i++)
{
if($sum_sx[$i]>$sum_sx[$i+1]){

         $tmp=$sum_tm[$i+1];
	$sum_tm[$i+1]=$sum_tm[$i];
		 $sum_tm[$i]=$tmp;
		 
		   $tmp=$sum_m[$i+1];
	$sum_m[$i+1]=$sum_m[$i];
		 $sum_m[$i]=$tmp;
		 
		  $tmp=$sum_re[$i+1];
	$sum_re[$i+1]=$sum_re[$i];
		 $sum_re[$i]=$tmp;
		 
		 $tmp=$sum_ma[$i+1];
	$sum_ma[$i+1]=$sum_ma[$i];
		 $sum_ma[$i]=$tmp;
		 
		  $tmp=$sum_mb[$i+1];
	$sum_mb[$i+1]=$sum_mb[$i];
		 $sum_mb[$i]=$tmp;
		 
		 $tmp=$sum_ds[$i+1];
	$sum_ds[$i+1]=$sum_ds[$i];
		 $sum_ds[$i]=$tmp;
		 
		 $tmp=$sum_xx[$i+1];
	$sum_xx[$i+1]=$sum_xx[$i];
		 $sum_xx[$i]=$tmp;
		 
		 $tmp=$sum_bl[$i+1];
	$sum_bl[$i+1]=$sum_bl[$i];
		 $sum_bl[$i]=$tmp;
		 
		   $tmp=$sum_mbl[$i+1];
	$sum_mbl[$i+1]=$sum_mbl[$i];
		 $sum_mbl[$i]=$tmp;
		 
		 $tmp=$sum_sx[$i+1];
	$sum_sx[$i+1]=$sum_sx[$i];
		 $sum_sx[$i]=$tmp;

  $tmp=$sum_pz1[$i+1];
	$sum_pz1[$i+1]=$sum_pz1[$i];
		 $sum_pz1[$i]=$tmp;


 $tmp=$sum_color[$i+1];
	$sum_color[$i+1]=$sum_color[$i];
		 $sum_color[$i]=$tmp;
   



}


}


}


$fg=0;

$i=0;
for($i=0;$i<$ii;$i++)
{
if(($sum_sx[$i]+$ztm_tm)>=0 || $sum_mbl[$i]==0 ){
$ffxx=0;}else{

if($sum_tm[$i]=="�쵥"||$sum_tm[$i]=="��˫"||$sum_tm[$i]=="�̵�"||$sum_tm[$i]=="��˫"||$sum_tm[$i]=="����"||$sum_tm[$i]=="��˫"){
$ffxx=(-$sum_sx[$i]-$ztm_tm)/($sum_mbl[$i]+$bb1/100-1);
}elseif($sum_tm[$i]=="���"||$sum_tm[$i]=="��С"||$sum_tm[$i]=="�̴�"||$sum_tm[$i]=="��С"||$sum_tm[$i]=="����"||$sum_tm[$i]=="��С"){
$ffxx=(-$sum_sx[$i]-$ztm_tm)/($sum_mbl[$i]+$bb1/100-1);
}elseif($sum_tm[$i]=="��ϵ�"||$sum_tm[$i]=="���˫"||$sum_tm[$i]=="�̺ϵ�"||$sum_tm[$i]=="�̺�˫"||$sum_tm[$i]=="���ϵ�"||$sum_tm[$i]=="����˫"){
$ffxx=(-$sum_sx[$i]-$ztm_tm)/($sum_mbl[$i]+$bb1/100-1);
}
/*
if ($i==0){
if((($sum_ma[0]-$sum_pz1[0])-$ztm_tm)==0 or $sum_mbl[0]==0 ){
$ffxx=0;
}else{$ffxx=((($sum_ma[0]-$sum_pz1[0])-$ztm_tm));}

}else{
if((($sum_ma[$i]-$sum_pz1[$i])-$ztm_tm)==0 or $sum_mbl[$i]==0 ){
$ffxx=0;}else{$ffxx=((($sum_ma[$i]-$sum_pz1[$i])-$ztm_tm));}
}
*/

}
$bl=round($ffxx,0);//intval($ffxx);
 if($ffxx>=1){
$fg=$fg+1;


if ($i==0){
$sum_pz[0]="<button class=headtd4  onmouseover=this.className='headtd3';window.status='����'; return true; onMouseOut=this.className='headtd4';window.status='����';return true; onclick=show_win('".$sum_tm[0]."','".$bl."','".$sum_mbl[0]."','".$class1."','".$class2."')    ><font color=ff6600>�߷�</font>  ".$bl."</button>";

}else{
$sum_pz[$i]="<button class=headtd4  onmouseover=this.className='headtd3';window.status='����'; return true; onMouseOut=this.className='headtd4';window.status='����';return true; onclick=show_win('".$sum_tm[$i]."','".$bl."','".$sum_mbl[$i]."','".$class1."','".$class2."')    ><font color=ff6600>�߷�</font>  ".$bl."</button>";}



}else{
$sum_pz[$i]="0";
$sum_pz[$i]="<button class=headtd4  onmouseover=this.className='headtd3';window.status='����'; return true; onMouseOut=this.className='headtd4';window.status='����';return true; onclick=show_win('".$sum_tm[$i]."','".$bl."','".$sum_mbl[$i]."','".$class1."','".$class2."')    ><font color=ff6600>�߷�</font>  ".$bl."</button>";
}
}


$i=0;
for($i=0;$i<$ii;$i++)
{



$blbl.=$class2." ".$sum_tm[$i]."@@@". $sum_re[$i]. "ע@@@" . $sum_m[$i]. "@@@" . $sum_ma[$i]. "@@@" .$sum_mb[$i]. "@@@" . round($sum_ds[$i],2). "@@@" .round($sum_xx[$i],2). "@@@" . round($sum_sx[$i],2). "@@@" . $sum_pz[$i]. "@@@" . $sum_pz1[$i]. "@@@" .$sum_bl[$i]. "@@@" .$fg."@@@".$sum_tm[$i]."@@@".$sum_color[$i]."###";
}
$blbl.= "0@@@<font color=ff6600>".$z_re."ע</font>@@@<font color=ff6600>".$z_sum."</font>@@@<font color=ff6600>".$z_suma."</font>@@@<font color=ff6600>".$z_sumb."</font>@@@<font color=ff6600>".$z_ds."</font>@@@<font color=ff6600>".$z_xx."</font>@@@&nbsp;@@@&nbsp;@@@<font color=ff6600>".$z_pz."</font>@@@<b><font color=ff0000>".$ztm_tm."</font></b>@@@".$fg."###";



echo $blbl;


?>
