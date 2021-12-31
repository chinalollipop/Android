<?php
function pdate(){
	$rq = array("2009-12-28","2010-01-25","2010-02-22","2010-03-22","2010-04-19","2010-05-17","2010-06-14","2010-07-12","2010-08-09","2010-09-06","2010-10-04","2010-11-01","2010-11-29","2010-12-26");
	$moon=date('n');
	if (date('Y-m-d')<$rq[$moon]){
	    $period[0]=date('Y_n');
		$period[1]=$rq[$moon-1];
		$lssue_0=explode("-",$rq[$moon]);
		$d_0=mktime(0,0,0,$lssue_0[1],$lssue_0[2],$lssue_0[0]);
		$period[2]=date('Y-m-d',$d_0-24*60*60);
		$period[3]=$rq[$moon-2];
		$lssue_1=explode("-",$rq[$moon-1]);
		$d_1=mktime(0,0,0,$lssue_1[1],$lssue_1[2],$lssue_1[0]);
		$period[4]=date('Y-m-d',$d_1-24*60*60);
	}else{
	    $period[0]=date('Y_n',time()+720*60*60);
		$period[1]=$rq[$moon];
		$lssue_0=explode("-",$rq[$moon+1]);
		$d_0=mktime(0,0,0,$lssue_0[1],$lssue_0[2],$lssue_0[0]);
		$period[2]=date('Y-m-d',$d_0-24*60*60);
		$period[3]=$rq[$moon-1];
		$lssue_1=explode("-",$rq[$moon]);
		$d_1=mktime(0,0,0,$lssue_1[1],$lssue_1[2],$lssue_1[0]);
		$period[4]=date('Y-m-d',$d_1-24*60*60);
	}
	return $period;
}
function wterror($msg){
	$test=$test."<html>";
	$test=$test."<head>";
	$test=$test."<title>error</title>";
	$test=$test."<meta http-equiv=Content-Type content=text/html; charset=utf-8>";
	$test=$test."<STYLE> A:visit { color=#6633cc; text-decoration: none ;}";
	$test=$test."tr {  font-family: Arial; font-size: 12px; color: #CC0000}";
	$test=$test.".b_13set {  font-size: 15px; font-family: Arial; color: #FFFFFF; padding-top: 2px; padding-left: 5px}";
	$test=$test.".b_tab {  border: 1px #000000 solid; background-color: #D2D2D2}";
	$test=$test.".b_back {  height: 20px; padding-top: 5px; color: #FFFFFF; cursor: pointer; padding-left: 50px}";
	$test=$test."a:link {  color: #0000FF}";
	$test=$test."a:hover {  color: #CC0000}";
	$test=$test."a:visited {  color: #0000FF}";
	$test=$test."</STYLE>";
	$test=$test."</head>";
	$test=$test."<body text=#000000 leftmargin=0 topmargin=10 bgcolor=535E63 vlink=#0000FF alink=#0000FF>";
	$test=$test."<table width=600 border=0 cellspacing=0 cellpadding=0 align=center>";
	$test=$test."  <tr>";
	$test=$test."    <td width=36><img src=/images/agents/control/error_p11.gif width=36 height=63></td>";
	$test=$test."    <td background=/images/agents/control/error_p12b.gif>&nbsp;</td>";
	$test=$test."    <td width=160><img src=/images/agents/control/error_p13.gif width=160 height=63></td>";
	$test=$test."  </tr>";
	$test=$test."</table>";
	$test=$test."<table width=598 border=0 cellspacing=0 cellpadding=0 align=center class=b_tab>";
	$test=$test."  <tr bgcolor=#000000> ";
	$test=$test."    <td ><img src=/images/agents/control/error_dot.gif width=23 height=22></td>";
	$test=$test."    <td class=b_13set width=573>错&nbsp;误&nbsp;讯&nbsp;息</td>";
	$test=$test."  </tr>";
	$test=$test."  <tr> ";
	$test=$test."    <td colspan=2 align=center><br>";
	$test=$test."      $msg<BR><br>";
	$test=$test."      &nbsp; </td>";
	$test=$test."  </tr>";
	$test=$test."  <tr> ";
	$test=$test."    <td colspan=2>";
	$test=$test."      <table width=598 border=0 cellspacing=0 cellpadding=0 bgcolor=A0A0A0>";
	$test=$test."        <tr>";
	$test=$test."          <td>&nbsp;</td>";
	$test=$test."          <td background=/images/agents/control/error_p3.gif width=120><a href='javascript:history.go(-1)';><span class=b_back>回上一页</span></a></td>";
	$test=$test."        </tr>";
	$test=$test."      </table>";
	$test=$test."    </td>";
	$test=$test."  </tr>";
	$test=$test."</table>";
	$test=$test."</body>";
	$test=$test."</html>";
//	exit();
	return $test;
}
function show_voucher($line,$id){
require_once 'config.inc.php';
$dbLink  = Dbnew::getInstance('slave');
$sql="select OUID,DTID,PMID from ".DBPREFIX."web_system_data";
$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);
$ouid=$row['OUID'];
$dtid=$row['DTID'];
$pmid=$row['PMID'];
switch($line){
	case 1:
		$show_voucher='OU'.($id+$ouid);
		break;
	case 2:
		$show_voucher='OU'.($id+$ouid);
		break;
	case 3:
		$show_voucher='OU'.($id+$ouid);
		break;
	case 4:
		$show_voucher='DT'.($id+$dtid);
		break;	
	case 5:
		$show_voucher='DT'.($id+$dtid);
		break;
	case 6:
		$show_voucher='DT'.($id+$dtid);
		break;
	case 7:
		$show_voucher='DT'.($id+$dtid);
		break;
	case 8:
		$show_voucher='PM'.($id+$pmid);
		break;				
	case 9:
		$show_voucher='OU'.($id+$ouid);
		break;
	case 10:
		$show_voucher='OU'.($id+$ouid);
		break;
	case 11:
		$show_voucher='OU'.($id+$ouid);
		break;
	case 12:
		$show_voucher='OU'.($id+$ouid);
		break;
	case 13:
		$show_voucher='OU'.($id+$ouid);
		break;
	case 14:
		$show_voucher='DT'.($id+$dtid);
		break;
	case 15:
		$show_voucher='OU'.($id+$ouid);
		break;
	case 16:
		$show_voucher='CS'.($id+$ouid);
		break;
	case 19:
		$show_voucher='OU'.($id+$ouid);
		break;
	case 20:
		$show_voucher='OU'.($id+$ouid);
		break;
	case 21:
		$show_voucher='OU'.($id+$ouid);
		break;
	case 31:
		$show_voucher='OU'.($id+$ouid);
		break;
	}
	return $show_voucher;
}

function change_rate($c_type,$c_rate){
	$pat = '/(\d+\.\d{2})\d*/';
	switch($c_type){
		case 'A':
			$t_rate='0.03';
			break;
		case 'B':
			$t_rate='0.01';
			break;
		case 'C':
			$t_rate='0';
			break;
		case 'D': // 本平台默认用户盘口为D，玩法显示与投注 方便赔率转换
			$t_rate='-0.01';
			break;
	}
	if ($c_rate!='' and $c_rate!='0'){
	    //$change_rate=number_format($c_rate-$t_rate,2);
	    $change_rate=preg_replace($pat,"\${1}",$c_rate-$t_rate);
	    if ($change_rate<=0 and $change_rate>=-0.03){
		    $change_rate='';
	    }
	}else{
	    $change_rate='';
	}
	return $change_rate;
}

/*
 * 選擇多盤口時 轉換成該選擇賠率
 * @param odd_type 	選擇盤口
 * @param iorH		主賠率
 * @param iorC		客賠率
 * @param show		顯示位數
 * @return		回傳陣列 0-->H  ,1-->C
 */
function  get_other_ioratio($odd_type,$iorH,$iorC,$showior){
	$out=Array();
	if($iorH!="" ||$iorC!=""){
		$out =chg_ior($odd_type,$iorH,$iorC,$showior);
	}else{
		$out[0]=$iorH;
		$out[1]=$iorC;
	}
	return $out;
}

/**
 * 轉換賠率
 * @param odd_f
 * @param H_ratio
 * @param C_ratio
 * @param showior
 * @return
 */
function chg_ior($odd_f,$iorH,$iorC,$showior){
	$ior=Array();
	if($iorH < 3) $iorH *=1000;
	if($iorC < 3) $iorC *=1000;
	$iorH=$iorH;
	$iorC=$iorC;
	switch($odd_f){
	case "H":	//香港變盤(輸水盤)
		$ior = get_HK_ior($iorH,$iorC);
		break;
	case "M":	//馬來盤
		$ior = get_MA_ior($iorH,$iorC);
		break;
	case "I" :	//印尼盤
		$ior = get_IND_ior($iorH,$iorC);
		break;
	case "E":	//歐洲盤
		$ior = get_EU_ior($iorH,$iorC);
		break;
	default:	//香港盤
		$ior[0]=$iorH ;
		$ior[1]=$iorC ;
	}
	$ior[0]/=1000;
	$ior[1]/=1000;
	$ior[0]=Decimal_point($ior[0],$showior);
	$ior[1]=Decimal_point($ior[1],$showior);
	//$ior[0]=number(Decimal_point($ior[0],$showior),3);
	//$ior[1]=number(Decimal_point($ior[1],$showior),3);
	return $ior;
}
/**
 * 換算成輸水盤賠率
 * @param H_ratio
 * @param C_ratio
 * @return
 */
function get_HK_ior($H_ratio,$C_ratio){
	$out_ior=Array();
	$line="";
	$lowRatio="";
	$nowRatio="";
	$highRatio="";
    $nowType="";
	if ($H_ratio <= 1000 && $C_ratio <= 1000){
		$out_ior[0]=$H_ratio;
		$out_ior[1]=$C_ratio;
		return $out_ior;
	}
	$line=2000 - ( $H_ratio + $C_ratio );
	if ($H_ratio > $C_ratio){ 
		$lowRatio=$C_ratio;
		$nowType = "C";
	}else{
		$lowRatio = $H_ratio;
		$nowType = "H";
	}
	if (((2000 - $line) - $lowRatio) > 1000){
		//對盤馬來盤
		$nowRatio = ($lowRatio + $line) * (-1);
	}else{
		//對盤香港盤
		$nowRatio=(2000 - $line) - $lowRatio;	
	}
	if ($nowRatio < 0){
		$highRatio = (abs(1000 / $nowRatio) * 1000) ;
	}else{
		$highRatio = (2000 - $line - $nowRatio) ;
	}
	if ($nowType == "H"){
		$out_ior[0]=$lowRatio;
		$out_ior[1]=$highRatio;
	}else{
		$out_ior[0]=$highRatio;
		$out_ior[1]=$lowRatio;
	}
	return $out_ior;
}
/**
 * 換算成馬來盤賠率
 * @param H_ratio
 * @param C_ratio
 * @return
 */
function get_MA_ior( $H_ratio, $C_ratio){
	$out_ior=Array();
	$line="";
	$lowRatio="";
	$highRatio="";
    $nowType="";
	if (($H_ratio <= 1000 && $C_ratio <= 1000)){
		$out_ior[0]=$H_ratio;
		$out_ior[1]=$C_ratio;
		return $out_ior;
	}
	$line=2000 - ( $H_ratio + $C_ratio );
	if ($H_ratio > $C_ratio){ 
		$lowRatio = $C_ratio;
		$nowType = "C";
	}else{
		$lowRatio = $H_ratio;
		$nowType = "H";
	}
	$highRatio = ($lowRatio + $line) * (-1);
	if ($nowType == "H"){
		$out_ior[0]=$lowRatio;
		$out_ior[1]=$highRatio;
	}else{
		$out_ior[0]=$highRatio;
		$out_ior[1]=$lowRatio;
	}
	return $out_ior;
}
/**
 * 換算成印尼盤賠率
 * @param H_ratio
 * @param C_ratio
 * @return
 */
function get_IND_ior( $H_ratio, $C_ratio){
	$out_ior=Array();
	$out_ior = get_HK_ior($H_ratio,$C_ratio);
	$H_ratio=$out_ior[0];
	$C_ratio=$out_ior[1];
	$H_ratio /= 1000;
	$C_ratio /= 1000;
	if($H_ratio < 1){
		$H_ratio=(-1) / $H_ratio;
	}
	if($C_ratio < 1){
		$C_ratio=(-1) / $C_ratio;
	}
	$out_ior[0]=$H_ratio*1000;
	$out_ior[1]=$C_ratio*1000;
	return $out_ior;
}
/**
 * 換算成歐洲盤賠率
 * @param H_ratio
 * @param C_ratio
 * @return
 */
function get_EU_ior($H_ratio, $C_ratio){
	$out_ior=Array();
	$out_ior = get_HK_ior($H_ratio,$C_ratio);
	$H_ratio=$out_ior[0];
	$C_ratio=$out_ior[1];       
	$out_ior[0]=$H_ratio+1000;
	$out_ior[1]=$C_ratio+1000;
	return $out_ior;
}

/*
去正負號做小數第幾位捨去
進來的值是小數值
*/
function Decimal_point($tmpior,$show){
	$sign="";
	$sign =(($tmpior < 0)?"Y":"N");
	$tmpior = (floor(abs($tmpior) * $show + 1 / $show )) / $show;
	return ($tmpior * (($sign =="Y")? -1:1));
}
/*
 公用 FUNC
*/
function number($vals,$points){ //小數點位數
	$cmd=Array();
	$cmd=split(".",$vals);
	$length=strlen($cmd[1]);
	if (count($cmd)>1){
		for ($ii=0;$ii<($points-$length);$ii++) $vals=$vals."0";
	}else{
		$vals=$vals+".";
		for ($ii=0;$ii<$points;$ii++) $vals=$vals."0";
	}
	return $vals;
}

?>
