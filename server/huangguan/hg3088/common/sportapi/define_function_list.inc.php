<?php

// 更多玩法效验
function gameFtVerify($line,$wtype,$rtype){
    $aWtype = $aRtype = [];
    switch ($line){
        case 1://独赢
            $aWtype = ['M'];
            $aRtype = ['MH','MN','MC'];
            break;
        case 11://独赢-上半场
            $aWtype = ['HM'];
            $aRtype = ['HMH','HMN','HMC'];
            break;
        case 2://让球
            $aWtype = ['R'];
            $aRtype = ['RH','RC'];
            break;
        case 12://让球-上半场
            $aWtype = ['HR'];
            $aRtype = ['HRH','HRC'];
            break;
        case 3://大小
            $aWtype = ['OU'];
            $aRtype = ['OUC','OUH'];
            break;
        case 13://大小-上半场
            $aWtype = ['HOU'];
            $aRtype = ['HOUC','HOUH'];
            break;
        case 4://波胆
            $aWtype = ['PD'];
            $aRtype = ['H1C0','H2C0','H0C0','H0C1','H0C2','H2C1','H3C0','H1C1','H1C2','H0C3','H3C1','H3C2','H2C2','H1C3','H2C3','H4C0','H4C1','H3C3','H0C4','H1C4','H4C2','H4C3','H4C4','H2C4','H3C4','OVH'];
            break;
        case 14://波胆-上半场
            $aWtype = ['HPD'];
            $aRtype = ['HH1C0','HH2C0','HH0C0','HH0C1','HH0C2','HH2C1','HH3C0','HH1C1','HH1C2','HH0C3','HH3C1','HH3C2','HH2C2','HH1C3','HH2C3','HH3C3','HH4C0','HH4C1','HH3C3','HH0C4','HH1C4','HH4C2','HH4C3','HH4C4','HH2C4','HH3C4','HOVH'];
            break;
        case 5://单双
            $aWtype = ['EO'];
            $aRtype = ['ODD','EVEN'];
            break;
        case 15://单双-上半场
            $aWtype = ['HEO'];
            $aRtype = ['HODD','HEVEN'];
            break;
        case 6://总进球数
            $aWtype = ['T'];
            $aRtype = ['0~1','2~3','4~6','OVER'];
            break;
        case 46://总进球数-上半场
            $aWtype = ['HT'];
            $aRtype = ['HT0','HT1','HT2','HTOV'];
            break;
        case 7://半场/全场
            $aWtype = ['F'];
            $aRtype = ['FHH','FNH','FCH','FHN','FNN','FCN','FHC','FNC','FCC'];
            break;
        case 18://净胜球数
            $aWtype = ['WM'];
            $aRtype = ['WMH1','WM0','WMC1','WMH2','WMN','WMC2','WMH3','WMC3','WMHOV','WMCOV'];
            break;
        case 65://双方球队进球
            $aWtype = ['TS'];
            $aRtype = ['TSY','TSN'];
            break;
        case 165://双方球队进球-上半场
            $aWtype = ['HTS'];
            $aRtype = ['HTSY','HTSN'];
            break;
        case 44://球队进球数- 大 / 小
            $aWtype = ['OUH','OUC'];
            if($wtype=='OUH'){
                $aRtype = ['OUHO','OUHU'];
            }elseif($wtype=='OUC'){
                $aRtype = ['OUCO','OUCU'];
            }
            break;
        case 144://球队进球数- 大 / 小-上半场
            $aWtype = ['HOUH','HOUC'];
            if($wtype=='HOUH'){
                $aRtype = ['HOUHO','HOUHU'];
            }elseif($wtype=='HOUC'){
                $aRtype = ['HOUCO','HOUCU'];
            }
            break;
        case 61://零失球获胜
            $aWtype = ['WN'];
            $aRtype = ['WNH','WNC'];
            break;
        case 62://零失球
            $aWtype = ['CS'];
            $aRtype = ['CSH','CSC'];
            break;
        case 23://独赢 & 双方球队进球
            $aWtype = ['MTS'];
            $aRtype = ['MTSHY','MTSNY','MTSCY','MTSHN','MTSNN','MTSCN'];
            break;
        case 28://最多进球的半场
            $aWtype = ['HG','RHGH'];
            $aRtype = ['HGH','HGC','RHG'];
            break;
        case 128://滚球-最多进球的半场
            $aWtype = ['RHG'];
            $aRtype = ['RHGH','RHGC'];
            break;
        case 29://最多进球的半场 - 独赢
            $aWtype = ['MG'];
            $aRtype = ['MGH','MGC','MGN'];
            break;
        case 129://滚球-最多进球的半场 - 独赢
            $aWtype = ['RMG'];
            $aRtype = ['RMGH','RMGC','RMGN'];
            break;
        case 30://双半场进球
            $aWtype = ['SB'];
            $aRtype = ['SBH','SBC'];
            break;
        case 130://滚球-双半场进球
            $aWtype = ['RSB'];
            $aRtype = ['RSBH','RSBC'];
            break;
        case 34://双重机会 & 进球 大 / 小
            $aWtype = ['DUA','DUB','DUC','DUD'];
            if($wtype=='DUA'){ $aRtype = ['DUAHO','DUACO','DUASO','DUAHU','DUACU','DUASU']; }
            if($wtype=='DUB'){ $aRtype = ['DUBHO','DUBCO','DUBSO','DUBHU','DUBCU','DUBSU']; }
            if($wtype=='DUC'){ $aRtype = ['DUCHO','DUCCO','DUCSO','DUCHU','DUCCU','DUCSU']; }
            if($wtype=='DUD'){ $aRtype = ['DUDHO','DUDCO','DUDSO','DUDHU','DUDCU','DUDSU']; }
            break;
        case 35://双重机会 & 双方球队进球
            $aWtype = ['DS'];
            $aRtype = ['DSHY','DSCY','DSSY','DSHN','DSCN','DSSN'];
            break;
        case 39://三项让球投注
            $aWtype = ['W3'];
            $aRtype = ['W3H','W3C','W3N'];
            break;
        case 41://赢得任一半场
            $aWtype = ['WE'];
            $aRtype = ['WEH','WEC'];
            break;
        case 42://赢得所有半场
            $aWtype = ['WB'];
            $aRtype = ['WBH','WBC'];
            break;
        case 69://双重机会
            $aWtype = ['DC'];
            $aRtype = ['DCHN','DCCN','DCHC'];
            break;
        //以下为滚球玩法
        case 9:	//让球
            $aWtype = ['RE'];
            $aRtype = ['REH','REC'];
            break;
        case 19://让球-上半场
            $aWtype = ['HRE'];
            $aRtype = ['HREH','HREC'];
            break;
        case 10://大小
            $aWtype = ['ROU'];
            $aRtype = ['ROUC','ROUH'];
            break;
        case 20://大小-上半场
            $aWtype = ['HROU'];
            $aRtype = ['HROUC','HROUH'];
            break;
        case 21://独赢
            $aWtype = ['RM'];
            $aRtype = ['RMH','RMN','RMC'];
            break;
        case 31://独赢-上半场
            $aWtype = ['HRM'];
            $aRtype = ['HRMH','HRMN','HRMC'];
            break;
        case 107://半场/全场
            $aWtype = ['RF'];
            $aRtype = ['RFHH','RFNH','RFCH','RFHN','RFNN','RFCN','RFHC','RFNC','RFCC'];
            break;
        case 204://波胆-上半场
            $aRtypeV=substr($rtype,1);
            if($wtype==$aRtypeV){
                $aWtype = ['RH1C0','RH2C0','RH0C0','RH0C1','RH0C2','RH2C1','RH3C0','RH1C1','RH1C2','RH0C3','RH3C1','RH3C2','RH2C2','RH1C3','RH2C3','RH3C3','RH4C0','RH4C1','RH3C3','RH0C4','RH1C4','RH4C2','RH4C3','RH4C4','RH2C4','RH3C4','ROVH'];
                $aRtype = ['HRH1C0','HRH2C0','HRH0C0','HRH0C1','HRH0C2','HRH2C1','HRH3C0','HRH1C1','HRH1C2','HRH0C3','HRH3C1','HRH3C2','HRH2C2','HRH1C3','HRH2C3','HRH3C3','HRH4C0','HRH4C1','HRH3C3','HRH0C4','HRH1C4','HRH4C2','HRH4C3','HRH4C4','HRH2C4','HRH3C4','HROVH'];
                if(in_array($wtype,$aWtype) && in_array($rtype,$aRtype)) return true;
                return false;
            }
            else{ // M、APP版本的参数校验
                $aWtype = ['HRPD'];
                $aRtype = ['HRH1C0','HRH2C0','HRH0C0','HRH0C1','HRH0C2','HRH2C1','HRH3C0','HRH1C1','HRH1C2','HRH0C3','HRH3C1','HRH3C2','HRH2C2','HRH1C3','HRH2C3','HRH3C3','HRH4C0','HRH4C1','HRH3C3','HRH0C4','HRH1C4','HRH4C2','HRH4C3','HRH4C4','HRH2C4','HRH3C4','HROVH'];
            }
            break;
        case 115://双方球队进球
            $aWtype = ['RTS'];
            $aRtype = ['RTSY','RTSN'];
            break;
        case 244://球队进球数-上半场
            $aWtype = ['HRUH','HRUC'];
            if($wtype=='HRUH'){ $aRtype = ['HRUHO','HRUHU']; }
            if($wtype=='HRUC'){ $aRtype = ['HRUCO','HRUCU']; }
            break;
        case 205://单双-上半场
            $aWtype = ['HREO'];
            $aRtype = ['HRODD','HREVEN'];
            break;
        case 118://净胜球数
            $aWtype = ['RWM'];
            $aRtype = ['RWMH1','RWM0','RWMC1','RWMH2','RWMN','RWMC2','RWMH3','RWMC3','RWMHOV','RWMCOV'];
            break;
        case 119://净胜球数
            $aWtype = ['RDC'];
            $aRtype = ['RDCHN','RDCCN','RDCHC'];
            break;
        case 120://零失球
            $aWtype = ['RCS'];
            $aRtype = ['RCSH','RCSC'];
            break;
        case 161://零失球获胜
            $aWtype = ['RWN'];
            $aRtype = ['RWNH','RWNC'];
            break;
        case 122://独赢 & 进球 大 / 小
            $aWtype = ['RMUA','RMUB','RMUC','RMUD'];
            if($wtype=='RMUA'){ $aRtype = ['RMUAHO','RMUANO','RMUACO','RMUAHU','RMUANU','RMUACU']; }
            if($wtype=='RMUB'){ $aRtype = ['RMUBHO','RMUBNO','RMUBCO','RMUBHU','RMUBNU','RMUBCU']; }
            if($wtype=='RMUC'){ $aRtype = ['RMUCHO','RMUCNO','RMUCCO','RMUCHU','RMUCNU','RMUCCU']; }
            if($wtype=='RMUD'){ $aRtype = ['RMUDHO','RMUDNO','RMUDCO','RMUDHU','RMUDNU','RMUDCU']; }
            break;
        case 123://独赢 & 双方球队进球
            $aWtype = ['RMTS'];
            $aRtype = ['RMTSHY','RMTSNY','RMTSCY','RMTSHN','RMTSNN','RMTSCN'];
            break;
        case 124://进球 大 / 小 & 双方球队进球
            $aWtype = ['RUTA','RUTB','RUTC','RUTD'];
            if($wtype=='RUTA'){ $aRtype = ['RUTAOY','RUTAON','RUTAUY','RUTAUN']; }
            if($wtype=='RUTB'){ $aRtype = ['RUTBOY','RUTBON','RUTBUY','RUTBUN']; }
            if($wtype=='RUTC'){ $aRtype = ['RUTCOY','RUTCON','RUTCUY','RUTCUN']; }
            if($wtype=='RUTD'){ $aRtype = ['RUTDOY','RUTDON','RUTDUY','RUTDUN']; }
            break;
        case 134://双重机会 & 进球 大 / 小
            $aWtype = ['RDUA','RDUB','RDUC','RDUD'];
            if($wtype=='RDUA'){ $aRtype = ['RDUAHO','RDUACO','RDUASO','RDUAHU','RDUACU','RDUASU']; }
            if($wtype=='RDUB'){ $aRtype = ['RDUBHO','RDUBCO','RDUBSO','RDUBHU','RDUBCU','RDUBSU']; }
            if($wtype=='RDUC'){ $aRtype = ['RDUCHO','RDUCCO','RDUCSO','RDUCHU','RDUCCU','RDUCSU']; }
            if($wtype=='RDUD'){ $aRtype = ['RDUDHO','RDUDCO','RDUDSO','RDUDHU','RDUDCU','RDUDSU']; }
            break;
        case 135://双重机会 & 双方球队进球
            $aWtype = ['RDS'];
            $aRtype = ['RDSHY','RDSCY','RDSSY','RDSHN','RDSCN','RDSSN'];
            break;
        case 137://进球 大 / 小 & 进球 单 / 双
            $aWtype = ['RUEA','RUEB','RUEC','RUED'];
            if($wtype=='RUEA'){ $aRtype = ['RUEAOO','RUEAOE','RUEAUO','RUEAUE']; }
            if($wtype=='RUEB'){ $aRtype = ['RUEBOO','RUEBOE','RUEBUO','RUEBUE']; }
            if($wtype=='RUEC'){ $aRtype = ['RUECOO','RUECOE','RUECUO','RUECUE']; }
            if($wtype=='RUED'){ $aRtype = ['RUEDOO','RUEDOE','RUEDUO','RUEDUE']; }
            break;
        case 141://赢得任一半场
            $aWtype = ['RWE'];
            $aRtype = ['RWEH','RWEC'];
            break;
        case 142://赢得所有半场
            $aWtype = ['RWB'];
            $aRtype = ['RWBH','RWBC'];
            break;
        case 154://球队进球数: 塔格雷斯 - 大 / 小
            $aWtype = ['ROUH','ROUC'];
            if($wtype=='ROUH'){ $aRtype = ['ROUHO','ROUHU']; }
            if($wtype=='ROUC'){ $aRtype = ['ROUCO','ROUCU']; }
            break;
        case 104: // 滚球波胆
            $aWtype = ['RPD'];
            $aRtype = ['RH1C0','RH2C1','RH3C1','RH4C0','RH4C2','RH2C0','RH3C0','RH3C2','RH4C1','RH4C3','RH0C0','RH1C1','RH2C2','RH3C3','RH4C4','RH0C1','RH1C2','RH1C3','RH0C4','RH2C4','RH0C2','RH0C3','RH2C3','RH1C4','RH3C4','ROVH'];
            break;
        case 106: // 总进球数
            $aWtype = ['RT'];
            $aRtype = ['R0~1','R2~3','R4~6','ROVER'];
            break;
        case 206: // 总进球数-半场
            $aWtype = ['HRT'];
            $aRtype = ['HRT0','HRT1','HRT2','HRTOV'];
            break;
        case 105://滚球 单双
            $aWtype = ['REO'];
            $aRtype = ['RODD','REVEN'];
            break;

    }

    if(in_array($wtype,$aWtype) && in_array($rtype,$aRtype)){
        return true;
    }else{
        return false;
    }
}

function gameBkVerify($line,$wtype,$rtype){
    $aWtype = $aRtype = [];
    switch ($line){
        case 1://独赢
            $aWtype = ['M'];
            $aRtype = ['MH','MC'];
            break;
        case 2://让球
            $aWtype = ['R'];
            $aRtype = ['RH','RC'];
            break;
        case 3://总分 大/小
            $aWtype = ['OU'];
            $aRtype = ['OUC','OUH'];
            break;
        case 5://总分: 单 / 双
            $aWtype = ['EO'];
            $aRtype = ['ODD','EVEN'];
            break;
        case 13://球队得分:  休斯顿火箭 - 大 / 小
            $aWtype = ['OUH','OUC'];
            if($wtype=='OUH'){ $aRtype = ['OUHO','OUHU']; }
            if($wtype=='OUC'){ $aRtype = ['OUCO','OUCU']; }
            break;
        case 31://球队得分: 休斯顿火箭 - 最后一位数
            $aWtype = ['PD'];
            $aRtype = ['PDH0','PDH1','PDH2','PDH3','PDH4','PDC0','PDC1','PDC2','PDC3','PDC4'];
            break;
        case 131://滚球球队得分: 休斯顿火箭 - 最后一位数
            $aWtype = ['RPD'];
            $aRtype = ['RPDH0','RPDH1','RPDH2','RPDH3','RPDH4','RPDC0','RPDC1','RPDC2','RPDC3','RPDC4'];
            break;
        case 9://滚球让球
            $aWtype = ['RE'];
            $aRtype = ['REH','REC'];
            break;
        case 10://滚球大小
            $aWtype = ['ROU'];
            $aRtype = ['ROUC','ROUH'];
            return true;
            break;
        case 21://滚球 独赢
            $aWtype = ['RM'];
            $aRtype = ['RMH','RMC'];
            return true;
            break;
        case 23://滚球 球队得分:  休斯顿火箭 - 大 / 小
            $aWtype = ['ROUH','ROUC'];
            if($wtype=='ROUH'){ $aRtype = ['ROUHO','ROUHU']; }
            if($wtype=='ROUC'){ $aRtype = ['ROUCO','ROUCU']; }
            break;
        case 105://滚球 单双
            $aWtype = ['REO'];
            $aRtype = ['ODD','EVEN'];
            break;
    }

    if(in_array($wtype,$aWtype) && in_array($rtype,$aRtype)){
        return true;
    }else{
        return false;
    }
}



// 重复提交页面
function resubmitAction($msg){
    $test ='';
    $test=$test."<html>";
    $test=$test."<head>";
    $test=$test."<title>重复提交</title>";
    $test=$test."<meta http-equiv=Content-Type content=text/html; charset=utf-8>";
    $test=$test."<link rel=stylesheet href=/style/member/mem_order_ft.css type=text/css>";
    $test=$test."</head>";
    $test=$test."<body style='margin: 0;padding: 0;'>";
    $test=$test."<div class=\"ord\">";
    $test=$test."<div class=\"title\"><h1>重复提交</h1></div>";
    $test=$test."<div class=\"main\">";
    $test=$test."<div class=\"fin_title\">";
    $test=$test."<p class=\"error\">您的注单已提交，可到交易状况查看，请不要重复提交注单谢谢！！</p>";
    $test=$test."</div>";
    $test=$test."</div>";
    $test=$test."</div>";
    $test=$test."</body>";
    $test=$test."</html>";
    return $test;
}
function attention($msg,$uid,$langx){

	if ($langx=='zh-cn'){
		$confirm='确定';
	}else if ($langx=='zh-tw'){
		$confirm='確定';
	}else if ($langx=='en-us' or $langx=='th-tis'){
		$confirm=' OK ';
	}
    $test ='';
	$test=$test."<html>";
	$test=$test."<head>";
	$test=$test."<title>Attention</title>";
	$test=$test."<meta http-equiv=Content-Type content=text/html; charset=utf-8>";
	$test=$test."<link rel=stylesheet href=/style/member/mem_order_ft.css type=text/css>";
	$test=$test."<script language=\"JavaScript\" src=\"/js/order_finish.js\"></script>";
	$test=$test."</head>";

	$test=$test."<body >";
	$test=$test."<div class='not_enough'>";
	$test=$test."<p>$msg</p>";
	$test=$test.'<p><input type=button name="check" value="'.$confirm.'" onClick="parent.close_bet();" height="20" class="yes"></p>';
	$test=$test."</div>";

	$test=$test."</body>";
	$test=$test."</html>";
	return $test;
}
function wterror($msg){
	$test=$test."<html>";
	$test=$test."<head>";
	$test=$test."<title>error</title>";
	$test=$test."<meta http-equiv=Content-Type content=text/html; charset=utf-8>";
	$test=$test."<STYLE>";
	$test=$test."<!--";
	$test=$test."body { text-align:center; background-color:#535E63;}";
	$test=$test."div { width:230px; font:12px Arial, Helvetica, sans-serif; border:1px solid #333; margin:auto;}";
	$test=$test."p { color:#C00; background-color:#CCC; margin:0; padding:15px 6px;}";
	$test=$test."h1 { font-size:1.2em; margin:0; padding:4px; background-color:#000; color:#FFF;letter-spacing: 0.5em;}";
	$test=$test."span { display:block; background-color:#A0A0A0; padding:4px; margin:0;}";
	$test=$test."a:link, a:visited {  color: #FFF; text-decoration: none;}";
	$test=$test."a:hover {  color: #FF0}";
	$test=$test."-->";
	$test=$test."</STYLE>";
	$test=$test."</head>";
	$test=$test."<body text=#000000 leftmargin=0 topmargin=10 bgcolor=535E63 vlink=#0000FF alink=#0000FF>";
	$test=$test."<div>";
	$test=$test."<h1>错误讯息</h1>";
	$test=$test."<p>$msg</p>";
	$test=$test."<span><a href=javascript:history.go(-1)>&raquo; 回上一页</a></span>";
	$test=$test."</div>";
	$test=$test."</body>";
	$test=$test."</html>";
//	exit();
	return $test;
}
function show_voucher($wtype){
	if(isset($wtype)&&$wtype){
		$show_voucher=$wtype.generalOrderNo();
	}else{
		$show_voucher="NO".generalOrderNo();
	}		
	return $show_voucher;
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
    $out=[$iorH, $iorC];
    if(($iorH!="" ||$iorC!="") && SPORT_FLUSH_WAY!='ujl'){ // 优久乐不做转换
        $out =chg_ior($odd_type,$iorH,$iorC,$showior);
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
    //	if($iorH < 3) $iorH *=1000; // 原来的
    //	if($iorC < 3) $iorC *=1000; // 原来的
    if($iorH < 11) $iorH *=1000;
    if($iorC < 11) $iorC *=1000;
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
function num_rate($c_type,$c_rate){
	switch($c_type){
	case 'A':
		$t_rate='0';
		break;
	case 'B':
		$t_rate='0';
		break;
	case 'C':
		$t_rate='0';
		break;
	case 'D':
		$t_rate='0';
		break;
	}
	if ($c_rate!=''){
	$num_rate=number_format($c_rate-$t_rate,2);
	if ($num_rate<=0){
		$num_rate='';
	}
	}else{
	$num_rate='';
	}
	return $num_rate;
}
function filiter_team($repteam){
	//$repteam=trim(str_replace(" ","",$repteam));
	$repteam=trim(str_replace("[H]","",$repteam));
	$repteam=trim(str_replace("[主]","",$repteam));
	$repteam=trim(str_replace("[中]","",$repteam));
	$repteam=trim(str_replace("[主]","",$repteam));
	$repteam=trim(str_replace("[中]","",$repteam));
	$repteam=trim(str_replace("[Home]","",$repteam));
	$repteam=trim(str_replace("[Mid]","",$repteam));
	$repteam=trim(str_replace("<font color=#990000> - [上半场]</font>","",$repteam));
	$repteam=trim(str_replace("<font color=#990000> - [下半场]</font>","",$repteam));
	$repteam=trim(str_replace("<font color=#990000> - [上半場]</font>","",$repteam));
	$repteam=trim(str_replace("<font color=#990000> - [下半場]</font>","",$repteam));
	$repteam=trim(str_replace("<font color=#990000> - [1st]</font>","",$repteam));
	$repteam=trim(str_replace("<font color=#990000> - [2nd]</font>","",$repteam));
	
	$repteam=trim(str_replace("<font color=gray> - [上半]</font>","",$repteam));
	$repteam=trim(str_replace("<font color=gray> - [下半]</font>","",$repteam));
	$repteam=trim(str_replace("<font color=gray> - [第1节]</font>","",$repteam));
	$repteam=trim(str_replace("<font color=gray> - [第2节]</font>","",$repteam));
	$repteam=trim(str_replace("<font color=gray> - [第3节]</font>","",$repteam));
	$repteam=trim(str_replace("<font color=gray> - [第4节]</font>","",$repteam));
	$repteam=trim(str_replace("<font color=gray> - [上半]</font>","",$repteam));
	$repteam=trim(str_replace("<font color=gray> - [下半]</font>","",$repteam));
	$repteam=trim(str_replace("<font color=gray> - [第1節]</font>","",$repteam));
	$repteam=trim(str_replace("<font color=gray> - [第2節]</font>","",$repteam));
	$repteam=trim(str_replace("<font color=gray> - [第3節]</font>","",$repteam));
	$repteam=trim(str_replace("<font color=gray> - [第4節]</font>","",$repteam));
	$repteam=trim(str_replace("<font color=gray> - [1st Half]</font>","",$repteam));
	$repteam=trim(str_replace("<font color=gray> - [2nd Half]</font>","",$repteam));
	$repteam=trim(str_replace("<font color=gray> - [Q1]</font>","",$repteam));	
	$repteam=trim(str_replace("<font color=gray> - [Q2]</font>","",$repteam));	
	$repteam=trim(str_replace("<font color=gray> - [Q3]</font>","",$repteam));	
	$repteam=trim(str_replace("<font color=gray> - [Q4]</font>","",$repteam));	

	$filiter_team=$repteam;
	return $filiter_team;
}
function fileter0($rate){
	for($i=1;$i<strlen($rate);$i++){
		if (substr($rate, -$i, 1)<>'0'){
			if (substr($rate, -$i, 1)=='.'){
				$fileter0=substr($rate,0,strlen($rate)-$i);
			}else{
				$fileter0=substr($rate,0,strlen($rate)-$i+1);
			}
			break;
		}
	}
	return $fileter0;
}

function singleset($ptype){
	//require ("config.inc.php");
	$dbLink  = Dbnew::getInstance('slave');
	$sql="select $ptype as P3,R,MAX from ".DBPREFIX."web_system_data where ID=1";
	$result = mysqli_query($dbLink,$sql);
	if($result){
		$row = mysqli_fetch_array($result);
		$p=$row['P3'];
		$pmax=$row['MAX'];
		return array($p,$pmax);	
	}else{
		return array();
	}
}

//生成非自增的订单号
function generalOrderNo() {
	$character =['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
	$rand1= rand(0,25);
	$rand2= rand(0,25);
	return $character[$rand1].$character[$rand2].substr(date("ymd"),1).rand(10000,99999).substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
}


/*
 * 为了保持跟前端js 代码处理一致
 * 全场单双，半场单双赔率处理
 * $type:plus ,prep
 * */
function returnOddEvenRate($rate,$type){
    if(!$type){$type='prep';}
    if($type=='plus'){
        $res_rate=($rate*1000 + 1000) / 1000;
    }else{
        $res_rate=($rate*1000 - 1000) / 1000;
    }
    return $res_rate;
}

/*
 *  波胆返回投注内容
 * */
function returnBoDanBetContent($rtype){
	$bd_str = '' ;
	switch ($rtype){
		case 'H1C0' : // 1-0
		case 'HH1C0' : // 1-0
			$bd_str = '1:0' ;
			break ;
		case 'RH1C0' : // 1-0
		case 'HRH1C0' : // 1-0
        	$bd_str = 'R(1:0)' ;
			break ;
        case 'H2C1' : // 2-1
        case 'HH2C1' : // 2-1
			$bd_str = '2:1' ;
			break ;
        case 'RH2C1' : // 2-1
        case 'HRH2C1' : // 2-1
            $bd_str = 'R(2:1)' ;
            break ;
        case 'H3C1' : // 3-1
        case 'HH3C1' : // 3-1
			$bd_str = '3:1' ;
			break ;
        case 'RH3C1' : // 3-1
        case 'HRH3C1' : // 3-1
            $bd_str = 'R(3:1)' ;
            break ;
        case 'H4C0' : // 4-0
        case 'HH4C0' : // 4-0
			$bd_str = '4:0' ;
			break ;
        case 'RH4C0' : // 4-0
        case 'HRH4C0' : // 4-0
            $bd_str = 'R(4:0)' ;
            break ;
        case 'H4C2' : // 4-2
        case 'HH4C2' : // 4-2
			$bd_str = '4:2' ;
			break ;
        case 'RH4C2' : // 4-2
        case 'HRH4C2' : // 4-2
            $bd_str = 'R(4:2)' ;
            break ;
        case 'H2C0' : // 2-0
        case 'HH2C0' : // 2-0
			$bd_str = '2:0' ;
			break ;
        case 'RH2C0' : // 2-0
        case 'HRH2C0' : // 2-0
            $bd_str = 'R(2:0)' ;
            break ;
        case 'H3C0' : // 3-0
        case 'HH3C0' : // 3-0
			$bd_str = '3:0' ;
			break ;
        case 'RH3C0' : // 3-0
        case 'HRH3C0' : // 3-0
            $bd_str = 'R(3:0)' ;
            break ;
        case 'H3C2' : // 3-2
        case 'HH3C2' : // 3-2
			$bd_str = '3:2' ;
			break ;
        case 'RH3C2' : // 3-2
        case 'HRH3C2' : // 3-2
            $bd_str = 'R(3:2)' ;
            break ;
        case 'H4C1' : // 4-1
        case 'HH4C1' : // 4-1
			$bd_str = '4:1' ;
			break ;
        case 'RH4C1' : // 4-1
        case 'HRH4C1' : // 4-1
            $bd_str = 'R(4:1)' ;
            break ;
        case 'H4C3' : // 4-3
        case 'HH4C3' : // 4-3
			$bd_str = '4:3' ;
			break ;
        case 'RH4C3' : // 4-3
        case 'HRH4C3' : // 4-3
            $bd_str = 'R(4:3)' ;
            break ;
        case 'H0C0' : // 0-0
        case 'HH0C0' : // 0-0
			$bd_str = '0:0' ;
			break ;
        case 'RH0C0' : // 0-0
        case 'HRH0C0' : // 0-0
            $bd_str = 'R(0:0)' ;
            break ;
        case 'H1C1' : // 1-1
        case 'HH1C1' : // 1-1
			$bd_str = '1:1' ;
			break ;
        case 'RH1C1' : // 1-1
        case 'HRH1C1' : // 1-1
            $bd_str = 'R(1:1)' ;
            break ;
        case 'H2C2' : // 2-2
        case 'HH2C2' : // 2-2
			$bd_str = '2:2' ;
			break ;
        case 'RH2C2' : // 2-2
        case 'HRH2C2' : // 2-2
            $bd_str = 'R(2:2)' ;
            break ;
        case 'H3C3' : // 3-3
        case 'HH3C3' : // 3-3
			$bd_str = '3:3' ;
			break ;
        case 'RH3C3' : // 3-3
        case 'HRH3C3' : // 3-3
            $bd_str = 'R(3:3)' ;
            break ;
        case 'H4C4' : // 4-4
        case 'HH4C4' : // 4-4
			$bd_str = '4:4' ;
			break ;
        case 'RH4C4' : // 4-4
        case 'HRH4C4' : // 4-4
            $bd_str = 'R(4:4)' ;
            break ;
        case 'H0C1' : // 0-1
        case 'HH0C1' : // 0-1
			$bd_str = '0:1' ;
			break ;
        case 'RH0C1' : // 0-1
        case 'HRH0C1' : // 0-1
            $bd_str = 'R(0:1)' ;
            break ;
        case 'H1C2' : // 1-2
        case 'HH1C2' : // 1-2
			$bd_str = '1:2' ;
			break ;
        case 'RH1C2' : // 1-2
        case 'HRH1C2' : // 1-2
            $bd_str = 'R(1:2)' ;
            break ;
        case 'H1C3' : // 1-3
        case 'HH1C3' : // 1-3
			$bd_str = '1:3' ;
			break ;
        case 'RH1C3' : // 1-3
        case 'HRH1C3' : // 1-3
            $bd_str = 'R(1:3)' ;
            break ;
        case 'H0C4' : // 0-4
        case 'HH0C4' : // 0-4
			$bd_str = '0:4' ;
			break ;
        case 'RH0C4' : // 0-4
        case 'HRH0C4' : // 0-4
            $bd_str = 'R(0:4)' ;
            break ;
        case 'H2C4' : // 2-4
        case 'HH2C4' : // 2-4
			$bd_str = '2:4' ;
			break ;
        case 'RH2C4' : // 2-4
        case 'HRH2C4' : // 2-4
            $bd_str = 'R(2:4)' ;
            break ;
        case 'H0C2' : // 0-2
        case 'HH0C2' : // 0-2
			$bd_str = '0:2' ;
			break ;
        case 'RH0C2' : // 0-2
        case 'HRH0C2' : // 0-2
            $bd_str = 'R(0:2)' ;
            break ;
        case 'H0C3' : // 0-3
        case 'HH0C3' : // 0-3
			$bd_str = '0:3' ;
			break ;
        case 'RH0C3' : // 0-3
        case 'HRH0C3' : // 0-3
            $bd_str = 'R(0:3)' ;
            break ;
        case 'H2C3' : // 2-3
        case 'HH2C3' : // 2-3
			$bd_str = '2:3' ;
			break ;
        case 'RH2C3' : // 2-3
        case 'HRH2C3' : // 2-3
            $bd_str = 'R(2:3)' ;
            break ;
        case 'H1C4' : // 1-4
        case 'HH1C4' : // 1-4
			$bd_str = '1:4' ;
			break ;
        case 'RH1C4' : // 1-4
        case 'HRH1C4' : // 1-4
            $bd_str = 'R(1:4)' ;
            break ;
        case 'H3C4' : // 3-4
        case 'HH3C4' : // 3-4
			$bd_str = '3:4' ;
			break ;
        case 'RH3C4' : // 3-4
        case 'HRH3C4' : // 3-4
            $bd_str = 'R(3:4)' ;
            break ;
	}
	return '('.$bd_str.')' ;
}

function closeDJFT($v2){

    $isRBpos = strpos($v2['re_time'],'^');
    if ($isRBpos===false){}
    else{
        // 电竞最后最后2分钟是否提前关闭
        // 8分钟的电竞盘口   上半场第3分钟开始关闭赔率，下半场第6分钟开始关闭赔率
        // 10分钟的电竞盘口   上半场第4分钟开始关闭赔率，下半场第8分钟开始关闭赔率
        // 12分钟的电竞盘口   上半场第5分钟开始关闭赔率，下半场第10分钟开始关闭赔率
        // $datainfo[48];  2H^06:56
        // 电竞足球-FIFA 20英格兰网络明星联赛-10分钟比赛
        $pos = strpos($v2['league'],'电竞足球');
        if ($pos === false){}
        else{
            $pos8minute = strpos($v2['league'],'8分钟比赛');
            if ($pos8minute===false){}
            else{
                $matchTotalMinites = 8;
                $currentMinuteIn8 = explode(':',explode('^',$v2['re_time'])[1])[0];
                $retimeset0 = explode('^',$v2['re_time'])[0];
            }

            $pos10minute = strpos($v2['league'],'10分钟比赛');
            if ($pos10minute===false){}
            else{
                $matchTotalMinites = 10;
                $currentMinuteIn10 = explode(':',explode('^',$v2['re_time'])[1])[0];
                $retimeset0 = explode('^',$v2['re_time'])[0];
            }

            $pos12minute = strpos($v2['league'],'12分钟比赛');
            if ($pos12minute===false){}
            else{
                $matchTotalMinites = 12;
                $currentMinuteIn12 = explode(':',explode('^',$v2['re_time'])[1])[0];
                $retimeset0 = explode('^',$v2['re_time'])[0];
            }

            $posYQminute = strpos($v2['league'],'电竞邀请赛');
            if ($posYQminute===false){}
            else{
                $matchTotalMinites = 12;
                $currentMinuteIn12 = explode(':',explode('^',$v2['re_time'])[1])[0];
                $retimeset0 = explode('^',$v2['re_time'])[0];
            }

            if (
                ($matchTotalMinites==8 and $currentMinuteIn8>=3 and $retimeset0=='1H') or
                ($matchTotalMinites==8 and $currentMinuteIn8>=6 and $retimeset0=='2H') or
                ($matchTotalMinites==10 and $currentMinuteIn10>=4 and $retimeset0=='1H') or
                ($matchTotalMinites==10 and $currentMinuteIn10>=8 and $retimeset0=='2H') or
                ($matchTotalMinites==12 and $currentMinuteIn12>=5 and $retimeset0=='1H') or
                ($matchTotalMinites==12 and $currentMinuteIn12>=10 and $retimeset0=='2H')

            ){
                return 1;
            }
        }
    }
}

