<?php

/*今日赛事-足球-（独赢-让球-大小&单、双）*/
ini_set('display_errors','OFF');
session_start();
header("Expires: Mon, 26 Jul 1970 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

require ("../include/address.mem.php");
echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
require ("../include/config.inc.php");
require ("../include/define_function_list.inc.php");
require ("../include/curl_http.php");

$uid=$_REQUEST['uid'];
$langx=$_SESSION['langx'];
$mtype=$_REQUEST['mtype'];
$rtype=$_REQUEST['rtype'];
$league_id=$_REQUEST['league_id'];
$page_no=$_REQUEST['page_no'];
$showtype=$_REQUEST['showtype'];
$leaname = $_REQUEST['leaname'] ; // 搜索赛事
$sorttype = $_REQUEST['sorttype'] ? $_REQUEST['sorttype'] : 'time';
if($leaname=='undefined'){
    $leaname='' ;
}

require ("../include/traditional.$langx.inc.php");

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
	echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
	exit;
}else{
	
$open=$_SESSION['OpenType'];
$memname=$_SESSION['UserName'];

$g_date = isset($_REQUEST['g_date'])?$_REQUEST['g_date']:'' ;
if($g_date=="ALL" or $g_date=="undefined" or $g_date==""){ // 综合过关
    $p3_date="";
}else{
    $p3_date="and M_Date='$g_date'";
}

if ($league_id=='' and $showtype!='hgft'){
	$num=60;
}else{
	$num=1024;
}
if ($page_no==''){
    $page_no=0;
}
$m_date=date('Y-m-d');
$date=date('m-d');
$K=0;

$redisObj = new Ciredis();

//根据玩法获取滚球数据
function getRunningDataByMethod($key){
	global $redisObj,$leaname;
	$matchesJson = $redisObj->getSimpleOne($key);
	$matches = json_decode($matchesJson,true);

    if(isset($_REQUEST['mylovegame'])&&strlen($_REQUEST['mylovegame'])>0){//收藏筛选
        $mylovegame=$_REQUEST['mylovegame'];
        $mylovegameArr=explode(',',$mylovegame);
        if(count($mylovegameArr)>0){
            foreach($matches as $key=>$val){
				$valArr=explode(',',$val);
                if(!in_array(str_replace('\'','',$valArr[5]),$mylovegameArr)){
                    unset($matches[$key]);
                }
                $valArr=array();
            }
        }
    }

    if(isset($_REQUEST['myleaArr'])&&strlen($_REQUEST['myleaArr'])>0){//联盟筛选
        $myleaArr=$_REQUEST['myleaArr'];
        $myleaArr=explode(',',$myleaArr);
        if(count($myleaArr)>0){
            foreach($matches as $key=>$val){
				$valArr=explode(',',$val);
                if(!in_array(str_replace('\'','',$valArr[2]),$myleaArr)){
                    unset($matches[$key]);
                }
                $valArr=array();
            }
        }
    }

	$matches=array_values($matches);

	if(isset($leaname)&&strlen($leaname)>0){
		foreach( $matches as $key=>$val ){
			if(strpos($val,$leaname)>-1 || strpos($val,$leaname)>-1 || strpos($val,$leaname)>-1){
				$matchesNew[]=$val;	
			}	
		}
		return $matchesNew;
	}else{
		return $matches;
	}
}

function getTodayMatches($key){
	global $redisObj,$leaname;
	$matchesJson = $redisObj->getSimpleOne($key);
	$matches = json_decode($matchesJson,true);
	
	if(isset($_REQUEST['mylovegame'])&&strlen($_REQUEST['mylovegame'])>0){//收藏筛选
		$mylovegame=$_REQUEST['mylovegame'];
	    $mylovegameArr=explode(',',$mylovegame);
	    if(count($mylovegameArr)>0){
	    	foreach($matches as $key=>$val){
	    		if(!in_array($val['MB_MID'],$mylovegameArr)){
	    			unset($matches[$key]);
	    		}
	    	}
	    }
	}
	
	if(isset($_REQUEST['myleaArr'])&&strlen($_REQUEST['myleaArr'])>0){//联盟筛选
		$myleaArr=$_REQUEST['myleaArr'];
	    $myleaArr=explode(',',$myleaArr);
	    if(count($myleaArr)>0){
	    	foreach($matches as $key=>$val){
	    		if(!in_array($val['M_League'],$myleaArr)){
	    			unset($matches[$key]);
	    		}
	    	}
 	    }
	}
	
	if(isset($leaname)&&strlen($leaname)>0){
		foreach( $matches as $key=>$val ){
			if(strpos($val['MB_Team'],$leaname)>-1 || strpos($val['TG_Team'],$leaname)>-1 || strpos($val['M_League'],$leaname)>-1){
				$matchesNew[]=$val;	
			}	
		}
		return $matchesNew;
	}else{
		return $matches;
	}
}

function getP3Matches(){
	global $redisObj,$g_date;
    $key='TODAY_FT_P3';
	$matchesJson = $redisObj->getSimpleOne($key);
	$matches = json_decode($matchesJson,true);
    
    if(isset($g_date) && $g_date=="ALL"){
        if(isset($leaname)&&strlen($leaname)>0){
            foreach( $matches as $key=>$val ){
                if(strpos($val['MB_Team'],$leaname)>-1 || strpos($val['TG_Team'],$leaname)>-1 || strpos($val['M_League'],$leaname)>-1){
                    $matchesNew[]=$val;
                }
            }
			$matchesNew=M_LeagueShaiXuan($matchesNew);
            return $matchesNew;
        }else{
	        $matches=M_LeagueShaiXuan($matches);
            return $matches;
        }
    }elseif(isset($g_date) && checkDateFormat($g_date)){
        //var_dump($matches);
        foreach( $matches as $key=>$val ){
            if($val["M_Date"]==$g_date){
                if(isset($leaname)&&strlen($leaname)>0){
                    if(strpos($val['MB_Team'],$leaname)>-1 || strpos($val['TG_Team'],$leaname)>-1 || strpos($val['M_League'],$leaname)>-1){
                        $matchesNew[]=$val;
                    }
                }else{
                    $matchesNew[]=$val;
                }
            }
        }
        if(isset($matchesNew)){
        	$matchesNew=M_LeagueShaiXuan($matchesNew);
        	return $matchesNew;
        }else{ 
        	return array(); 
        }
    }
    return array();
}

function loveShaiXuan($returnData){
	if(isset($_REQUEST['mylovegame'])&&strlen($_REQUEST['mylovegame'])>0){//收藏筛选
		$mylovegame=$_REQUEST['mylovegame'];
	    $mylovegameArr=explode(',',$mylovegame);
	    if(count($mylovegameArr)>0){
	    	foreach($returnData as $key=>$val){
	    		if(!in_array($val['MB_MID'],$mylovegameArr)){
	    			unset($returnData[$key]);
	    		}
	    	}
	    }
	}
	return $returnData;
}

function M_LeagueShaiXuan($matchesNew){
	if(isset($_REQUEST['myleaArr'])&&strlen($_REQUEST['myleaArr'])>0){//联盟筛选
		$myleaArr=$_REQUEST['myleaArr'];
	    $myleaArr=explode(',',$myleaArr);
	    if(count($myleaArr)>0){
	    	foreach($matchesNew as $key=>$val){
	    		if(!in_array($val['M_League'],$myleaArr)){
	    			unset($matchesNew[$key]);
	    		}
	    	}
 	    }
	}
	return $matchesNew;
}

// 滚球盘口按照时间排序
function runningMatchesSortByTime(){
    global $datainfo_list;
    foreach ($datainfo_list as $key => $match){
        // 转换时间 02-28<br>01:35a  -》  2019-02-28 01:35:00
        // 转换时间 02-28<br>01:35p  -》  2019-02-28 13:35:00
        $match[100] = str_replace('<br>', ' ', $match[47]); //02-28 01:35a
        $sAorP = substr($match[100],11);
        $match[100] = date('Y-m-d H:i:s',strtotime('2019-'.substr($match[100],0, -1)));
        if ($sAorP == 'p'){
            $match[100] = date('Y-m-d H:i:s',strtotime($match[100])+43200);
        }
        $datainfo_list[$key][100] = $match[100];
    }
//    $datainfo_list = array_sort($datainfo_list,0,$type='asc');
    $datainfo_list = array_sort($datainfo_list,100,$type='asc');
    return $datainfo_list;
}

$newDataArray = array();
?>
<head>
<TITLE>足球变数值</TITLE>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
    <link rel="stylesheet" href="../../../style/member/common.css?v=<?php echo AUTOVER; ?>" type="text/css"><link rel="stylesheet" href="../../../style/member/mem_body_ft.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <?php
    if ($rtype=='p3'){
        ?>
        <link rel="stylesheet" href="../../../style/member/mem_body_p3.css?v=<?php echo AUTOVER; ?>" type="text/css">
        <?php
    }
    ?>
<SCRIPT language=JavaScript>
parent.flash_ior_set='Y';
parent.minlimit_VAR='0';
parent.maxlimit_VAR='0';
parent.username='<?php echo $memname?>';
parent.code='人民幣(RMB)';
parent.uid='<?php echo $uid?>';

parent.ltype='3';
parent.str_even = '<?php echo $str_even?>';
parent.str_submit = '<?php echo $str_submit?>';
parent.str_reset = '<?php echo $str_reset?>';
parent.langx='<?php echo $langx?>';
parent.rtype='<?php echo $rtype?>';
parent.sel_lid='<?php echo $league_id?>';
top.today_gmt = '<?php echo $m_date ?>';
top.now_gmt = '<?php echo date("H:i:s") ?>';
parent.retime=60 ; // 刷新倒计时
parent.gamount=0;
parent.t_page=0;
function mouseEnter_pointer(tmp){
    try{
        document.getElementById(tmp.split("_")[1]).style.display ="block";
    }catch(E){}
}

function mouseOut_pointer(tmp){
    try{
        document.getElementById(tmp.split("_")[1]).style.display ="none";
    }catch(E){}
}

<?php 
switch ($rtype){
case "r":  // 全部
case "r_main":  // 主要盘口
    $table='<tr class="title_fixed">
              <th nowrap class="h_1x1">'.$U_06.'</th>
              <th class="h_1x170"> </th>
   
              <th nowrap class="h_1x2">'.$WIN.'</th>
              <th nowrap class="h_r">'.$U_02.'</th>
              <th nowrap class="h_ou">'.$U_03.'</th>
              <th class="h_ds">'.$OE.'</th>
              <th nowrap class="h_1x2">'.$WIN.'</th>
              <th nowrap class="h_r">'.$U_04.'</th>
              <th nowrap class="h_ou">'.$U_05.'</th>
            </tr>';

    $page_size=60;
	$offset=$page_no*60;
    $resulTotal=getTodayMatches("TODAY_FT_M_ROU_EO");
//    if ($sorttype == 'time'){
//        $resulTotal = matchesSortByTime($resulTotal);
//    }
    if($sorttype == 'league'){// 按照联盟排序
        foreach ($resulTotal as $k => $v){
            $resulTotal[$k]['M_League_Initials'] = _getFirstCharter($v['M_League']);
        }
        $resulTotal = array_sort($resulTotal,'M_League_Initials',$type='asc');
        // 联盟相同的归成一类
//    $resulTotal = group_same_key($resulTotal,'M_League');
    }
	$cou_num=count($resulTotal);
	$page_count=ceil($cou_num/$page_size);
	$resultArr=array_slice($resulTotal,$offset,$num);
	$cou=count($resultArr);

	echo "parent.str_renew = '$second_auto_update';\n";
	echo "parent.game_more=1;\n";
	echo "parent.str_more='$more';\n";
	echo "parent.t_page=$page_count;\n";	
	echo "parent.gamount=$cou;\n";
	//  hratio 半场让分 ， ior_HRH 半场让球主队 ， ior_HRC 半场让球客队
	//echo "parent.GameHead = new Array('gid','datetime','league','gnum_h','gnum_c','team_h','team_c','strong','ratio','ior_RH','ior_RC','ratio_o','ratio_u','ior_OUH','ior_OUC','ior_MH','ior_MC','ior_MN','str_odd','str_even','ior_EOO','ior_EOE','hgid','hstrong','hratio','ior_HRH','ior_HRC','hratio_o','hratio_u','ior_HOUH','ior_HOUC','ior_HMH','ior_HMC','ior_HMN','more','all','eventid','hot','play');";
	foreach($resultArr as $key=>$row){
	    $MB_Win_Rate=change_rate($open,$row["MB_Win_Rate"]);
		$TG_Win_Rate=change_rate($open,$row["TG_Win_Rate"]);
		$M_Flat_Rate=change_rate($open,$row["M_Flat_Rate"]);

        // 全场让球单独处理
        $ra_rate=get_other_ioratio(GAME_POSITION,$row["MB_LetB_Rate"],$row["TG_LetB_Rate"],100); // 默认都是香港盘
        $MB_LetB_Rate=$ra_rate[0]; // 主队
        $TG_LetB_Rate=$ra_rate[1]; // 客队
        $MB_LetB_Rate=change_rate($open,$MB_LetB_Rate);
        $TG_LetB_Rate=change_rate($open,$TG_LetB_Rate);

        // 全场大小处理
        $ra_rate=get_other_ioratio(GAME_POSITION,$row["TG_Dime_Rate"],$row["MB_Dime_Rate"],100); // 默认都是香港盘
        $TG_Dime_Rate=$ra_rate[0];
        $MB_Dime_Rate=$ra_rate[1];
        $TG_Dime_Rate=change_rate($open,$TG_Dime_Rate);
        $MB_Dime_Rate=change_rate($open,$MB_Dime_Rate);

        $S_Single_Rate=change_rate($open,$row['S_Single_Rate']);
		$S_Double_Rate=change_rate($open,$row['S_Double_Rate']);
		
		$MB_Win_Rate_H=change_rate($open,$row["MB_Win_Rate_H"]); // 全部独赢主队
		$TG_Win_Rate_H=change_rate($open,$row["TG_Win_Rate_H"]); // 全部独赢客队
		$M_Flat_Rate_H=change_rate($open,$row["M_Flat_Rate_H"]); // 全部独赢和局

        // 半场让球单独处理
        $h_ra_rate=get_other_ioratio(GAME_POSITION,$row["MB_LetB_Rate_H"],$row["TG_LetB_Rate_H"],100); // 默认都是香港盘
        $MB_LetB_Rate_H=$h_ra_rate[0]; // 主队
        $TG_LetB_Rate_H=$h_ra_rate[1]; // 客队
        $MB_LetB_Rate_H=change_rate($open,$MB_LetB_Rate_H);  // 半场让球主队
        $TG_LetB_Rate_H=change_rate($open,$TG_LetB_Rate_H); // 半场让球客队

        // 半场大小处理
        $h_ra_rate=get_other_ioratio(GAME_POSITION,$row["TG_Dime_Rate_H"],$row["MB_Dime_Rate_H"],100); // 默认都是香港盘
        $TG_Dime_Rate_H=$h_ra_rate[0];
        $MB_Dime_Rate_H=$h_ra_rate[1];
        $TG_Dime_Rate_H=change_rate($open,$TG_Dime_Rate_H);  // 半场大小客队
        $MB_Dime_Rate_H=change_rate($open,$MB_Dime_Rate_H); // 半场大小主队

        if ($row['HPD_Show']==1 and $row['PD_Show']==1 and $row['T_Show']==1 and $row['F_Show']==1){
		    $show=4;
		}else if ($row['PD_Show']==1 and $row['T_Show']==1 and $row['F_Show']==1){
		    $show=3;
		}else{
		    $show=0;
		}
		if ($row['M_Type']==1){
            $Running="<br><font color=red>滾球</font>";
		}else{	
			$Running="";
		}
		$allMethods=$row[more]<5 ? 0:$row[more];
        if($row[ShowTypeR]=="H"){
            $ratio_mb_str=$row[M_LetB];
            $ratio_tg_str='';
            $hratio_mb_str=$row[M_LetB_H];
            $hratio_tg_str='';
        }elseif($row[ShowTypeR]=="C"){
            $ratio_mb_str='';
            $ratio_tg_str=$row[M_LetB];
            $hratio_mb_str='';
            $hratio_tg_str=$row[M_LetB_H];
        }

        $row[MB_Team]=str_replace("[Mid]","<font color=\'#005aff\'>[N]</font>",$row[MB_Team]);
        $row[MB_Team]=str_replace("[中]","<font color=\'#005aff\'>[中]</font>",$row[MB_Team]);
        $newDataArray[$row[MID]]['gid']=$row[MID];
        $newDataArray[$row[MID]]['dategh']=$date.$row[MB_MID];
        $newDataArray[$row[MID]]['datetime']=$date."<br>".$row[M_Time].$Running;
        $newDataArray[$row[MID]]['datetimelove']=$date."<br>".$row[M_Time];
        $newDataArray[$row[MID]]['league']=$row[M_League];
        $newDataArray[$row[MID]]['gnum_h']=$row[MB_MID];
        $newDataArray[$row[MID]]['gnum_c']=$row[TG_MID];
        $newDataArray[$row[MID]]['team_h']=$row[MB_Team];
        $newDataArray[$row[MID]]['team_c']=$row[TG_Team];
        $newDataArray[$row[MID]]['strong']=$row[ShowTypeR];
        $newDataArray[$row[MID]]['ratio' ]=$row[M_LetB];
        $newDataArray[$row[MID]]['ratio_mb_str']=$ratio_mb_str;
        $newDataArray[$row[MID]]['ratio_tg_str']=$ratio_tg_str;
        $newDataArray[$row[MID]]['ior_RH']=$MB_LetB_Rate;
        $newDataArray[$row[MID]]['ior_RC']=$TG_LetB_Rate;
        $newDataArray[$row[MID]]['bet_RH']="gid={$row[MID]}&uid={$uid}&odd_f_type=H&type=H&gnum={$row[MB_MID]}&strong={$row[ShowTypeR]}&langx={$langx}";
        $newDataArray[$row[MID]]['bet_RC']="gid={$row[MID]}&uid={$uid}&odd_f_type=H&type=C&gnum={$row[TG_MID]}&strong={$row[ShowTypeR]}&langx={$langx}";
        $newDataArray[$row[MID]]['ratio_o']=$row[MB_Dime];
        $newDataArray[$row[MID]]['ratio_u']=$row[TG_Dime];
        $newDataArray[$row[MID]]['ratio_o_str']="大".str_replace('O','',$row[MB_Dime]);
        $newDataArray[$row[MID]]['ratio_u_str']="小".str_replace('U','',$row[TG_Dime]);
        $newDataArray[$row[MID]]['ior_OUH']=$TG_Dime_Rate;
        $newDataArray[$row[MID]]['ior_OUC']=$MB_Dime_Rate;
        $newDataArray[$row[MID]]['bet_OUH']="gid={$row[MID]}&uid={$uid}&odd_f_type=H&type=C&gnum={$row[MB_MID]}&langx={$langx}";
        $newDataArray[$row[MID]]['bet_OUC']="gid={$row[MID]}&uid={$uid}&odd_f_type=H&type=H&gnum={$row[TG_MID]}&langx={$langx}";
        $newDataArray[$row[MID]]['ior_MH']=$MB_Win_Rate;
        $newDataArray[$row[MID]]['ior_MC']=$TG_Win_Rate;
        $newDataArray[$row[MID]]['ior_MN']=$M_Flat_Rate;
        $newDataArray[$row[MID]]['bet_MH']="gid={$row[MID]}&uid={$uid}&odd_f_type=H&type=H&gnum={$row[MB_MID]}&strong={$row[ShowTypeR]}&langx={$langx}";
        $newDataArray[$row[MID]]['bet_MC']="gid={$row[MID]}&uid={$uid}&odd_f_type=H&type=C&gnum={$row[TG_MID]}&strong={$row[ShowTypeR]}&langx={$langx}";
        $newDataArray[$row[MID]]['bet_MN']="gid={$row[MID]}&uid={$uid}&odd_f_type=H&type=N&gnum={$row[TG_MID]}&strong={$row[ShowTypeR]}&langx={$langx}";
        $newDataArray[$row[MID]]['str_odd']=$o;
        $newDataArray[$row[MID]]['str_even']=$e;
        $newDataArray[$row[MID]]['ior_EOO']=$S_Single_Rate;
        $newDataArray[$row[MID]]['ior_EOE']=$S_Double_Rate;
        $newDataArray[$row[MID]]['bet_EOO']="gid={$row[MID]}&uid={$uid}&odd_f_type=H&rtype=ODD&langx={$langx}";
        $newDataArray[$row[MID]]['bet_EOE']="gid={$row[MID]}&uid={$uid}&odd_f_type=H&rtype=EVEN&langx={$langx}";
        $newDataArray[$row[MID]]['hgid']='$row[MID]';
        $newDataArray[$row[MID]]['hstrong']=$row[ShowTypeHR];
        $newDataArray[$row[MID]]['hratio']=$row[M_LetB_H];
        $newDataArray[$row[MID]]['hratio_mb_str']=$hratio_mb_str;
        $newDataArray[$row[MID]]['hratio_tg_str']=$hratio_tg_str;
        $newDataArray[$row[MID]]['ior_HRH']=$MB_LetB_Rate_H;
        $newDataArray[$row[MID]]['ior_HRC']=$TG_LetB_Rate_H;
        $newDataArray[$row[MID]]['hratio_o']=$row[MB_Dime_H];
        $newDataArray[$row[MID]]['hratio_u']=$row[TG_Dime_H];
        $newDataArray[$row[MID]]['hratio_o_str']="大".str_replace('O','',$row[MB_Dime_H]);
        $newDataArray[$row[MID]]['hratio_u_str']="小".str_replace('U','',$row[TG_Dime_H]);
        $newDataArray[$row[MID]]['ior_HOUH']=$TG_Dime_Rate_H;
        $newDataArray[$row[MID]]['ior_HOUC']=$MB_Dime_Rate_H;
        $newDataArray[$row[MID]]['ior_HMH']=$MB_Win_Rate_H;
        $newDataArray[$row[MID]]['ior_HMC']=$TG_Win_Rate_H;
        $newDataArray[$row[MID]]['ior_HMN']=$M_Flat_Rate_H;
        $newDataArray[$row[MID]]['more']=$show;
        $newDataArray[$row[MID]]['all']=$allMethods;
        $newDataArray[$row[MID]]['eventid'] =$row[Eventid];
        $newDataArray[$row[MID]]['hot']=$row[Hot];
        $newDataArray[$row[MID]]['play']=$row[Play];

//		echo "parent.GameFT[$K]=new Array('$row[MID]','$date<br>$row[M_Time]$Running','$row[M_League]','$row[MB_MID]','$row[TG_MID]','$row[MB_Team]','$row[TG_Team]','$row[ShowTypeR]','$row[M_LetB]','$MB_LetB_Rate','$TG_LetB_Rate','$row[MB_Dime]','$row[TG_Dime]',
//                '$TG_Dime_Rate','$MB_Dime_Rate','$MB_Win_Rate','$TG_Win_Rate','$M_Flat_Rate','$o','$e','$S_Single_Rate','$S_Double_Rate','$row[MID]','$row[ShowTypeHR]','$row[M_LetB_H]','$MB_LetB_Rate_H','$TG_LetB_Rate_H','$row[MB_Dime_H]','$row[TG_Dime_H]',
//                '$TG_Dime_Rate_H','$MB_Dime_Rate_H','$MB_Win_Rate_H','$TG_Win_Rate_H','$M_Flat_Rate_H','$show','$allMethods','$row[Eventid]','$row[Hot]','$row[Play]');\n";

		$K=$K+1;
	}
    $listTitle="今日足球";
    $leagueNameCur='';
	break;
case "re": // 滚球----------------------------------------------------------		start	--------------------------------------------------------------------------------------
case "re_main": // 滚球主要盘口
    $table='<tr class="title_fixed">
              <th nowrap class="h_1x1" >'.$Mem_Soccer.':'.$Running_Ball.'</th>
              <th class="h_1x170_re"></th>
           
              <th nowrap class="h_1x2">'.$WIN.'</th>
              <th nowrap class="h_r">'.$U_02.'</th>
              <th nowrap class="h_ou">'.$U_03.'</th>
              <th>'.$OE.'</th>
              <th nowrap class="h_1x2">'.$WIN.'</th>
              <th nowrap class="h_r">'.$U_04.'</th>
              <th nowrap class="h_ou">'.$U_05.'</th>
            </tr>';

    //获取刷水账号
	$reBallCountCur = 0;
	$page_size=60;
	echo "parent.retime=20;\n"; // 倒计时刷新时间
	echo "parent.game_more=1;\n";
	echo "parent.str_more='$more';\n";
	echo "parent.str_renew = '$second_auto_update';\n";
	//echo "parent.GameHead = new Array('gid','timer','league','gnum_h','gnum_c','team_h','team_c','strong','ratio','ior_RH','ior_RC','ratio_o','ratio_u','ior_OUH','ior_OUC','no1','no2','no3','score_h','score_c','hgid','hstrong','hratio','ior_HRH','ior_HRC','hratio_o','hratio_u','ior_HOUH','ior_HOUC','redcard_h','redcard_c','lastestscore_h','lastestscore_c','ior_MH','ior_MC','ior_MN','ior_HMH','ior_HMC','ior_HMN','str_odd','str_even','ior_EOO','ior_EOE','eventid','hot','play','datetime','retimeset','more','all');\n";

    $matches=getRunningDataByMethod("FT_M_ROU_EO");

	if(is_array($matches)){
		$cou=sizeof($matches);
	}else{
		$cou=0;
	}
	$gamecount =0 ;
	$page_count=ceil($cou/$page_size);
	echo "parent.t_page=$page_count;\n";
	for($i=0;$i<$cou;$i++) {
        $messages = $matches[$i];
        $messages = str_replace(");", ")", $messages);
        $messages = str_replace("cha(9)", "", $messages);
        $datainfo = eval("return $messages;");
        $datainfo_list[] = $datainfo;
    }

    if ($sorttype == 'time'){
        $datainfo_list = runningMatchesSortByTime();
    }

    $gamecount = sizeof($datainfo_list);
    foreach ($datainfo_list as $k => $datainfo){
		
			if ($datainfo[9]!=''){
                        // 全场让球单独处理
                        $ra_rate=get_other_ioratio(GAME_POSITION,$datainfo[9],$datainfo[10],100); // 默认都是香港盘
                        $datainfo[9]=$ra_rate[0]; // 主队
                        $datainfo[10]=$ra_rate[1]; // 客队
                        $datainfo[9]=change_rate($open,$datainfo[9]);
                        $datainfo[10]=change_rate($open,$datainfo[10]);
					}
					if ($datainfo[13]!=''){
                        $ra_rate=get_other_ioratio(GAME_POSITION,$datainfo[13],$datainfo[14],100); // 默认都是香港盘
                        $datainfo[13]=$ra_rate[0]; // 全场大小 大
                        $datainfo[14]=$ra_rate[1]; // 全场大小 小
					    $datainfo[13]=change_rate($open,$datainfo[13]);
						$datainfo[14]=change_rate($open,$datainfo[14]);
					}			
					if ($datainfo[23]!=''){
                        // 半场让球单独处理
                        $ra_rate=get_other_ioratio(GAME_POSITION,$datainfo[23],$datainfo[24],100); // 默认都是香港盘
                        $datainfo[23]=$ra_rate[0]; // 主队
                        $datainfo[24]=$ra_rate[1]; // 客队
                        $datainfo[23]=change_rate($open,$datainfo[23]);
                        $datainfo[24]=change_rate($open,$datainfo[24]);
					}
					if ($datainfo[28]!=''){
                        $ra_rate=get_other_ioratio(GAME_POSITION,$datainfo[28],$datainfo[27],100); // 默认都是香港盘
                        $datainfo[28]=$ra_rate[0]; // 半场大小 大
                        $datainfo[27]=$ra_rate[1]; // 半场大小 小
					    $datainfo[28]=change_rate($open,$datainfo[28]);
						$datainfo[27]=change_rate($open,$datainfo[27]);
					}
					
					if ($datainfo[33]!=''){
					    $datainfo[33]=change_rate($open,$datainfo[33]);
					}
					if ($datainfo[34]!=''){
					    $datainfo[34]=change_rate($open,$datainfo[34]);
					}
					if ($datainfo[35]!=''){
					    $datainfo[35]=change_rate($open,$datainfo[35]);
					}
					if ($datainfo[36]!=''){
					    $datainfo[36]=change_rate($open,$datainfo[36]);
					}
					if ($datainfo[37]!=''){
					    $datainfo[37]=change_rate($open,$datainfo[37]);
					}
					if ($datainfo[38]!=''){
					    $datainfo[38]=change_rate($open,$datainfo[38]);
					}

					$datainfo[41]=change_rate($open,$datainfo[41]);
					$datainfo[42]=change_rate($open,$datainfo[42]);
					$show=0;
					
					$allMethods=$datainfo[49]<5 ? 0:$datainfo[49];
                    if($datainfo[7]=="H"){
                        $ratio_mb_str=$datainfo[8];
                        $ratio_tg_str='';
                        $hratio_mb_str=$datainfo[22];
                        $hratio_tg_str='';
                    }elseif($datainfo[7]=="C"){
                        $ratio_mb_str='';
                        $ratio_tg_str=$datainfo[8];
                        $hratio_mb_str='';
                        $hratio_tg_str=$datainfo[22];
                    }
        $datainfo[5]=str_replace("[Mid]","<font color='#005aff'>[N]</font>",$datainfo[5]);
        $datainfo[5]=str_replace("[中]","<font color='#005aff'>[中]</font>",$datainfo[5]);
        $newDataArray[$datainfo[0]]['gid']=$datainfo[0];
        $newDataArray[$datainfo[0]]['timer'] =$datainfo[1];
        $newDataArray[$datainfo[0]]['dategh']=$date.$datainfo[3];
        $newDataArray[$datainfo[0]]['datetimelove']=$datainfo[47];
        $newDataArray[$datainfo[0]]['league']=$datainfo[2];
        $newDataArray[$datainfo[0]]['gnum_h']=$datainfo[3];
        $newDataArray[$datainfo[0]]['gnum_c']=$datainfo[4];
        $newDataArray[$datainfo[0]]['team_h']=$datainfo[5];
        $newDataArray[$datainfo[0]]['team_h_for_sort']=explode(' -',$datainfo[5])[0];
        $newDataArray[$datainfo[0]]['team_c']=$datainfo[6];
        $newDataArray[$datainfo[0]]['strong']=$datainfo[7];
        $newDataArray[$datainfo[0]]['ratio']=$datainfo[8];
        $newDataArray[$datainfo[0]]['ratio_mb_str']=$ratio_mb_str;
        $newDataArray[$datainfo[0]]['ratio_tg_str']=$ratio_tg_str;
        $newDataArray[$datainfo[0]]['ior_RH']=$datainfo[9];
        $newDataArray[$datainfo[0]]['ior_RC']=$datainfo[10];
        $newDataArray[$datainfo[0]]['bet_RH']="gid={$datainfo[0]}&uid={$uid}&odd_f_type=H&type=H&gnum={$datainfo[3]}&strong={$datainfo[7]}&langx={$langx}";
        $newDataArray[$datainfo[0]]['bet_RC']="gid={$datainfo[0]}&uid={$uid}&odd_f_type=H&type=C&gnum={$datainfo[4]}&strong={$datainfo[7]}&langx={$langx}";
        $newDataArray[$datainfo[0]]['ratio_o']=$datainfo[11];
        $newDataArray[$datainfo[0]]['ratio_u']=$datainfo[12];
        $newDataArray[$datainfo[0]]['ratio_o_str']="大".str_replace('O','',$datainfo[11]);
        $newDataArray[$datainfo[0]]['ratio_u_str']="小".str_replace('U','',$datainfo[12]);
        $newDataArray[$datainfo[0]]['ior_OUH']=$datainfo[13];
        $newDataArray[$datainfo[0]]['ior_OUC']=$datainfo[14];
        $newDataArray[$datainfo[0]]['bet_OUH']="gid={$datainfo[0]}&uid={$uid}&odd_f_type=H&type=C&gnum={$datainfo[3]}&langx={$langx}";
        $newDataArray[$datainfo[0]]['bet_OUC']="gid={$datainfo[0]}&uid={$uid}&odd_f_type=H&type=H&gnum={$datainfo[4]}&langx={$langx}";
        $newDataArray[$datainfo[0]]['no1']=$datainfo[15];
        $newDataArray[$datainfo[0]]['no2']=$datainfo[16];
        $newDataArray[$datainfo[0]]['no3']=$datainfo[17];
        $newDataArray[$datainfo[0]]['score_h']=$datainfo[18];
        $newDataArray[$datainfo[0]]['score_c']=$datainfo[19];
        $newDataArray[$datainfo[0]]['hgid']  =$datainfo[20];
        $newDataArray[$datainfo[0]]['hstrong']=$datainfo[21];
        $newDataArray[$datainfo[0]]['hratio'] =$datainfo[22];
        $newDataArray[$datainfo[0]]['hratio_mb_str']=$hratio_mb_str;
        $newDataArray[$datainfo[0]]['hratio_tg_str']=$hratio_tg_str;
        $newDataArray[$datainfo[0]]['ior_HRH']=$datainfo[23];
        $newDataArray[$datainfo[0]]['ior_HRC']=$datainfo[24];
        $newDataArray[$datainfo[0]]['hratio_o']=$datainfo[25];
        $newDataArray[$datainfo[0]]['hratio_u']=$datainfo[26];
        $newDataArray[$datainfo[0]]['hratio_o_str']="大".str_replace('O','',$datainfo[25]);
        $newDataArray[$datainfo[0]]['hratio_u_str']="小".str_replace('U','',$datainfo[26]);
        $newDataArray[$datainfo[0]]['ior_HOUH']=$datainfo[27];
        $newDataArray[$datainfo[0]]['ior_HOUC']=$datainfo[28];
        $newDataArray[$datainfo[0]]['redcard_h']=$datainfo[29];
        $newDataArray[$datainfo[0]]['redcard_c']=$datainfo[30];
        $newDataArray[$datainfo[0]]['lastestscore_h'] =$datainfo[31];
        $newDataArray[$datainfo[0]]['lastestscore_c'] =$datainfo[32];
        $newDataArray[$datainfo[0]]['ior_MH']=$datainfo[33];
        $newDataArray[$datainfo[0]]['ior_MC']=$datainfo[34];
        $newDataArray[$datainfo[0]]['ior_MN']=$datainfo[35];
        $newDataArray[$datainfo[0]]['ior_HMH']=$datainfo[36];
        $newDataArray[$datainfo[0]]['ior_HMC']=$datainfo[37];
        $newDataArray[$datainfo[0]]['ior_HMN']=$datainfo[38];
        $newDataArray[$datainfo[0]]['bet_MH']="gid={$datainfo[0]}&uid={$uid}&odd_f_type=H&type=H&gnum={$datainfo[3]}&langx={$langx}";
        $newDataArray[$datainfo[0]]['bet_MC']="gid={$datainfo[0]}&uid={$uid}&odd_f_type=H&type=C&gnum={$datainfo[4]}&langx={$langx}";
        $newDataArray[$datainfo[0]]['bet_MN']="gid={$datainfo[0]}&uid={$uid}&odd_f_type=H&type=N&gnum={$datainfo[4]}&langx={$langx}";
        $newDataArray[$datainfo[0]]['str_odd']=$o;
        $newDataArray[$datainfo[0]]['str_even']=$e;
        $newDataArray[$datainfo[0]]['ior_EOO']=$datainfo[41];
        $newDataArray[$datainfo[0]]['ior_EOE']=$datainfo[42];
        $newDataArray[$datainfo[0]]['bet_EOO']="gid={$datainfo[0]}&uid={$uid}&odd_f_type=H&rtype=RODD&langx={$langx}";
        $newDataArray[$datainfo[0]]['bet_EOE']="gid={$datainfo[0]}&uid={$uid}&odd_f_type=H&rtype=REVEN&langx={$langx}";
        $newDataArray[$datainfo[0]]['eventid']=$datainfo[43];
        $newDataArray[$datainfo[0]]['hot']=$datainfo[44];
        $newDataArray[$datainfo[0]]['play']=$datainfo[46];
        $newDataArray[$datainfo[0]]['datetime']=$datainfo[47];
        $newDataArray[$datainfo[0]]['retimeset']=$datainfo[48];
        $newDataArray[$datainfo[0]]['more']=$show;
        $newDataArray[$datainfo[0]]['all']=$allMethods;

        $tmpset=explode("^", $datainfo[48]);
        $tmpset[1]=str_replace("<font style=background-color=red>","",$tmpset[1]);
        $tmpset[1]=str_replace("</font>","",$tmpset[1]);
        $showretime="";
        if($tmpset[0]=="Start"){
            $showretime="-";
        }else if($tmpset[0]=="MTIME" || $tmpset[0]=="196"){
            $showretime=$tmpset[1];
        }else{
            if($tmpset[0]=="1H"){$showretime="上  ".$tmpset[1]."'";}
            if($tmpset[0]=="2H"){$showretime="下  ".$tmpset[1]."'";}
        }
        $newDataArray[$datainfo[0]]['showretime']=$showretime;
//					echo "parent.GameFT[$K]=new Array('$datainfo[0]','$datainfo[1]','$datainfo[2]','$datainfo[3]','$datainfo[4]','$datainfo[5]','$datainfo[6]','$datainfo[7]','$datainfo[8]','$datainfo[9]','$datainfo[10]','$datainfo[11]','$datainfo[12]',
//					    '$datainfo[13]','$datainfo[14]','$datainfo[15]','$datainfo[16]','$datainfo[17]','$datainfo[18]','$datainfo[19]','$datainfo[20]','$datainfo[21]','$datainfo[22]','$datainfo[23]','$datainfo[24]','$datainfo[25]','$datainfo[26]',
//					    '$datainfo[27]','$datainfo[28]','$datainfo[29]','$datainfo[30]','$datainfo[31]','$datainfo[32]','$datainfo[33]','$datainfo[34]','$datainfo[35]','$datainfo[36]','$datainfo[37]','$datainfo[38]','$o','$e','$datainfo[41]','$datainfo[42]','$datainfo[43]','$datainfo[44]','$datainfo[46]','$datainfo[47]','$datainfo[48]','$show','$allMethods');\n";

					$K=$K+1;
					if ($gmid==''){
						$gmid=$datainfo[0];
					}else{
						$gmid=$gmid.','.$datainfo[0];
					}
			}
            // 按照队伍，gid分组
            $newDataArray = array_values(group_same_key($newDataArray,'team_h_for_sort'));
            foreach ($newDataArray as $k => $v){
                $val_sort = array_sort($v,'gid',$type='asc');
                foreach ($val_sort as $k2=>$v2){
                    $newDataArray2[] = $v2;
                }
            }
            $newDataArray = $newDataArray2;
            echo "parent.gamount=$gamecount;\n"; // 总数量
			$reBallCountCur = $cou;
            $listTitle="足球：滾球";
            $leagueNameCur='';
	break;
	// 滚球------------------------------------------------------------------------------------	end	--------------------------------------------------------------------------------------
case "pd":  //波胆
    $table='<tr class="title_fixed">
              <th class="ft_pd_h" >'.$U_06.'</th>
               <th class="h_pd_ft">1:0</th>
               <th class="h_pd_ft">2:0</th>
               <th class="h_pd_ft">2:1</th>
               <th class="h_pd_ft">3:0</th>
               <th class="h_pd_ft">3:1</th>
               <th class="h_pd_ft">3:2</th>
               <th class="h_pd_ft">4:0</th>
               <th class="h_pd_ft">4:1</th>
               <th class="h_pd_ft">4:2</th>
               <th class="h_pd_ft">4:3</th>
               <th class="h_pd_ft">0:0</th>
               <th class="h_pd_ft">1:1</th>
               <th class="h_pd_ft">2:2</th>
               <th class="h_pd_ft">3:3</th>
               <th class="h_pd_ft">4:4</th>
               <th class="h_pd_ft">'.$Others.'</th>
            </tr>
            <tr class="bet_correct_title">
            <td colspan="20">'.$U_07.'<span class="maxbet">'.$U_11.' RMB 1,000,000.00</span></td>
              </tr>';
    $table_dif='bd_all pd_table' ;// 波胆table 类
	$page_size=60;
	$offset=$page_no*60;
	$resulTotal=getTodayMatches("TODAY_FT_PD");
	$cou_num=count($resulTotal);
	$page_count=ceil($cou_num/$page_size);
	$resultArr=array_slice($resulTotal,$offset,$num);
	$cou=count($resultArr);	

	echo "parent.retime=0;\n";
	echo "parent.t_page=$page_count;\n";
	echo "parent.gamount=$cou;\n";
	//echo "parent.GameHead = new Array('gid','datetime','league','gnum_h','gnum_c','team_h','team_c','strong','ior_H1C0','ior_H2C0','ior_H2C1','ior_H3C0','ior_H3C1','ior_H3C2','ior_H4C0','ior_H4C1','ior_H4C2','ior_H4C3','ior_H0C0','ior_H1C1','ior_H2C2','ior_H3C3','ior_H4C4','ior_OVH','ior_H0C1','ior_H0C2','ior_H1C2','ior_H0C3','ior_H1C3','ior_H2C3','ior_H0C4','ior_H1C4','ior_H2C4','ior_H3C4','ior_OVC');";
	foreach($resultArr as $key=>$row){
        $row[MB_Team]=str_replace("[Mid]","<font color=\'#005aff\'>[N]</font>",$row[MB_Team]);
        $row[MB_Team]=str_replace("[中]","<font color=\'#005aff\'>[中]</font>",$row[MB_Team]);
        $newDataArray[$row[MID]]['gid']=$row[MID];
        $newDataArray[$row[MID]]['dategh']=$date.$row[MB_MID];
        $newDataArray[$row[MID]]['datetime']="$date<br>$row[M_Time]";
        $newDataArray[$row[MID]]['datetimelove']=$date."<br>".$row[M_Time];
        $newDataArray[$row[MID]]['league']=$row[M_League];
        $newDataArray[$row[MID]]['gnum_h']=$row[MB_MID];
        $newDataArray[$row[MID]]['gnum_c']=$row[TG_MID];
        $newDataArray[$row[MID]]['team_h']=$row[MB_Team];
        $newDataArray[$row[MID]]['team_c']=$row[TG_Team];
        $newDataArray[$row[MID]]['strong']=$row[ShowTypeR];
        $newDataArray[$row[MID]]['ior_H1C0']=change_rate($open,$row['MB1TG0']);
        $newDataArray[$row[MID]]['ior_H2C0']=change_rate($open,$row['MB2TG0']);
        $newDataArray[$row[MID]]['ior_H2C1']=change_rate($open,$row['MB2TG1']);
        $newDataArray[$row[MID]]['ior_H3C0']=change_rate($open,$row['MB3TG0']);
        $newDataArray[$row[MID]]['ior_H3C1']=change_rate($open,$row['MB3TG1']);
        $newDataArray[$row[MID]]['ior_H3C2']=change_rate($open,$row['MB3TG2']);
        $newDataArray[$row[MID]]['ior_H4C0']=change_rate($open,$row['MB4TG0']);
        $newDataArray[$row[MID]]['ior_H4C1']=change_rate($open,$row['MB4TG1']);
        $newDataArray[$row[MID]]['ior_H4C2']=change_rate($open,$row['MB4TG2']);
        $newDataArray[$row[MID]]['ior_H4C3']=change_rate($open,$row['MB4TG3']);
        $newDataArray[$row[MID]]['ior_H0C0']=change_rate($open,$row['MB0TG0']);
        $newDataArray[$row[MID]]['ior_H1C1']=change_rate($open,$row['MB1TG1']);
        $newDataArray[$row[MID]]['ior_H2C2']=change_rate($open,$row['MB2TG2']);
        $newDataArray[$row[MID]]['ior_H3C3']=change_rate($open,$row['MB3TG3']);
        $newDataArray[$row[MID]]['ior_H4C4']=change_rate($open,$row['MB4TG4']);
        $newDataArray[$row[MID]]['ior_OVH']= change_rate($open,$row['UP5']);
        $newDataArray[$row[MID]]['ior_H0C1']=change_rate($open,$row['MB0TG1']);
        $newDataArray[$row[MID]]['ior_H0C2']=change_rate($open,$row['MB0TG2']);
        $newDataArray[$row[MID]]['ior_H1C2']=change_rate($open,$row['MB1TG2']);
        $newDataArray[$row[MID]]['ior_H0C3']=change_rate($open,$row['MB0TG3']);
        $newDataArray[$row[MID]]['ior_H1C3']=change_rate($open,$row['MB1TG3']);
        $newDataArray[$row[MID]]['ior_H2C3']=change_rate($open,$row['MB2TG3']);
        $newDataArray[$row[MID]]['ior_H0C4']=change_rate($open,$row['MB0TG4']);
        $newDataArray[$row[MID]]['ior_H1C4']=change_rate($open,$row['MB1TG4']);
        $newDataArray[$row[MID]]['ior_H2C4']=change_rate($open,$row['MB2TG4']);
        $newDataArray[$row[MID]]['ior_H3C4']=change_rate($open,$row['MB3TG4']);
        $newDataArray[$row[MID]]['bet_Url']="gid={$row[MID]}&uid={$uid}&odd_f_type=H&langx={$langx}&rtype=";
		$K=$K+1;
	}
    $listTitle="今日足球：波胆";
    $leagueNameCur='';
	break;
case "rpd"://全场滚球波胆
    $table='<tr class="title_fixed">
              <th class="ft_pd_h" >'.$U_06.'</th>
               <th class="h_pd_ft">1:0</th>
               <th class="h_pd_ft">2:0</th>
               <th class="h_pd_ft">2:1</th>
               <th class="h_pd_ft">3:0</th>
               <th class="h_pd_ft">3:1</th>
               <th class="h_pd_ft">3:2</th>
               <th class="h_pd_ft">4:0</th>
               <th class="h_pd_ft">4:1</th>
               <th class="h_pd_ft">4:2</th>
               <th class="h_pd_ft">4:3</th>
               <th class="h_pd_ft">0:0</th>
               <th class="h_pd_ft">1:1</th>
               <th class="h_pd_ft">2:2</th>
               <th class="h_pd_ft">3:3</th>
               <th class="h_pd_ft">4:4</th>
               <th class="h_pd_ft">'.$Others.'</th>
            </tr>
            <tr class="bet_correct_title">
            <td colspan="20">'.$U_07.'<span class="maxbet">'.$U_11.' RMB 1,000,000.00</span></td>
              </tr>';
    $table_dif='bd_all pd_table' ;// 波胆table 类
	$reBallCountCur = 0;
	$page_size=60;
	echo "parent.retime=20;\n"; // 倒计时刷新时间
	//echo "parent.GameHead = new Array('gid','datetime','league','gnum_h','gnum_c','team_h','team_c','strong','ior_H1C0','ior_H2C0','ior_H2C1','ior_H3C0','ior_H3C1','ior_H3C2','ior_H4C0','ior_H4C1','ior_H4C2','ior_H4C3','ior_H0C0','ior_H1C1','ior_H2C2','ior_H3C3','ior_H4C4','ior_OVH','ior_H0C1','ior_H0C2','ior_H1C2','ior_H0C3','ior_H1C3','ior_H2C3','ior_H0C4','ior_H1C4','ior_H2C4','ior_H3C4','ior_OVC');";

	$matches=getRunningDataByMethod("FT_PD");
	$cou=sizeof($matches);
	if(is_array($matches)){
		$cou=sizeof($matches);
	}else{
		$cou=0;
	}
	$page_count=ceil($cou/$page_size);
	echo "parent.gamount=$cou;\n";
	echo "parent.t_page=$page_count;\n";
	for($i=0;$i<$cou;$i++){
		$messages=$matches[$i];
		$messages=str_replace(");",")",$messages);
		$messages=str_replace("cha(9)","",$messages);
		$datainfo=eval("return $messages;");
        $newDataArray[$datainfo[0]]['gid']=$datainfo[0];
        $newDataArray[$datainfo[0]]['datetime']=$datainfo[45];
        $newDataArray[$datainfo[0]]['dategh']=$date.$datainfo[3];
        $newDataArray[$datainfo[0]]['datetimelove']=$datainfo[45];
        $newDataArray[$datainfo[0]]['league']=$datainfo[2];
        $newDataArray[$datainfo[0]]['gnum_h']=$datainfo[3];
        $newDataArray[$datainfo[0]]['gnum_c']=$datainfo[4];
        $newDataArray[$datainfo[0]]['team_h']=$datainfo[5];
        $newDataArray[$datainfo[0]]['team_c']=$datainfo[6];
        $newDataArray[$datainfo[0]]['strong']=$datainfo[7];
        $newDataArray[$datainfo[0]]['ior_H1C0']=change_rate($open,$datainfo[8]);
        $newDataArray[$datainfo[0]]['ior_H2C0']=change_rate($open,$datainfo[9]);
        $newDataArray[$datainfo[0]]['ior_H2C1']=change_rate($open,$datainfo[10]);
        $newDataArray[$datainfo[0]]['ior_H3C0']=change_rate($open,$datainfo[11]);
        $newDataArray[$datainfo[0]]['ior_H3C1']=change_rate($open,$datainfo[12]);
        $newDataArray[$datainfo[0]]['ior_H3C2']=change_rate($open,$datainfo[13]);
        $newDataArray[$datainfo[0]]['ior_H4C0']=change_rate($open,$datainfo[14]);
        $newDataArray[$datainfo[0]]['ior_H4C1']=change_rate($open,$datainfo[15]);
        $newDataArray[$datainfo[0]]['ior_H4C2']=change_rate($open,$datainfo[16]);
        $newDataArray[$datainfo[0]]['ior_H4C3']=change_rate($open,$datainfo[17]);
        $newDataArray[$datainfo[0]]['ior_H0C0']=change_rate($open,$datainfo[18]);
        $newDataArray[$datainfo[0]]['ior_H1C1']=change_rate($open,$datainfo[19]);
        $newDataArray[$datainfo[0]]['ior_H2C2']=change_rate($open,$datainfo[20]);
        $newDataArray[$datainfo[0]]['ior_H3C3']=change_rate($open,$datainfo[21]);
        $newDataArray[$datainfo[0]]['ior_H4C4']=change_rate($open,$datainfo[22]);
        $newDataArray[$datainfo[0]]['ior_OVH']=change_rate($open,$datainfo[23]);
        $newDataArray[$datainfo[0]]['ior_H0C1']=change_rate($open,$datainfo[24]);
        $newDataArray[$datainfo[0]]['ior_H0C2']=change_rate($open,$datainfo[25]);
        $newDataArray[$datainfo[0]]['ior_H1C2']=change_rate($open,$datainfo[26]);
        $newDataArray[$datainfo[0]]['ior_H0C3']=change_rate($open,$datainfo[27]);
        $newDataArray[$datainfo[0]]['ior_H1C3']=change_rate($open,$datainfo[28]);
        $newDataArray[$datainfo[0]]['ior_H2C3']=change_rate($open,$datainfo[29]);
        $newDataArray[$datainfo[0]]['ior_H0C4']=change_rate($open,$datainfo[30]);
        $newDataArray[$datainfo[0]]['ior_H1C4']=change_rate($open,$datainfo[31]);
        $newDataArray[$datainfo[0]]['ior_H2C4']=change_rate($open,$datainfo[32]);
        $newDataArray[$datainfo[0]]['ior_H3C4']=change_rate($open,$datainfo[33]);
        $newDataArray[$datainfo[0]]['bet_Url']="gid={$datainfo[0]}&uid={$uid}&odd_f_type=H&langx={$langx}&rtype=";

		//echo "parent.GameFT[$K]=new Array('$datainfo[0]','$datainfo[45]','$datainfo[2]','$datainfo[3]','$datainfo[4]','$datainfo[5]','$datainfo[6]','$datainfo[7]','$datainfoA[8]','$datainfoA[9]','$datainfoA[10]','$datainfoA[11]','$datainfoA[12]','$datainfoA[13]','$datainfoA[14]','$datainfoA[15]','$datainfoA[16]','$datainfoA[17]','$datainfoA[18]','$datainfoA[19]','$datainfoA[20]','$datainfoA[21]','$datainfoA[22]','$datainfoA[23]','$datainfoA[24]','$datainfoA[25]','$datainfoA[26]','$datainfoA[27]','$datainfoA[28]','$datainfoA[29]','$datainfoA[30]','$datainfoA[31]','$datainfoA[32]','$datainfoA[33]','$datainfo[34]');\n";
		$K=$K+1;
		
	}
	$reBallCountCur = $cou;
    $listTitle="滚球足球：波胆";
    $leagueNameCur='';
	break;
case "hpd":
    $table='<tr class="title_fixed">
			<th class="ft_pd_h ft_hpd_h" >'.$U_06.' </th>	
			<th >1:0</th>
			<th >2:0</th>
			<th >2:1</th>
			<th >3:0</th>
			<th >3:1</th>
			<th >3:2</th>
			<th >0:0</th>
			<th >1:1</th>
			<th >2:2</th>
			<th >3:3</th>
			<th >'.$Others.'</th>
		    <tr>   <tr class="bet_correct_title">
            <td colspan="20">'.$U_12.'<span class="maxbet">'.$U_11.' RMB 1,000,000.00</span> </td>
              </tr>  ';
    $table_dif='bd_all pd_table' ;// 波胆table 类
	$page_size=60;
	$offset=$page_no*60;
	$resulTotal=getTodayMatches("TODAY_FT_HPD");
	$cou_num=count($resulTotal);
	$page_count=ceil($cou_num/$page_size);
	$resultArr=array_slice($resulTotal,$offset,$num);
	$cou=count($resultArr);

	echo "parent.t_page=$page_count;\n";
	echo "parent.gamount=$cou;\n";
	//echo "parent.GameHead = new Array('gid','datetime','league','gnum_h','gnum_c','team_h','team_c','strong','ior_H1C0','ior_H2C0','ior_H2C1','ior_H3C0','ior_H3C1','ior_H3C2','ior_H4C0','ior_H4C1','ior_H4C2','ior_H4C3','ior_H0C0','ior_H1C1','ior_H2C2','ior_H3C3','ior_H4C4','ior_OVH','ior_H0C1','ior_H0C2','ior_H1C2','ior_H0C3','ior_H1C3','ior_H2C3','ior_H0C4','ior_H1C4','ior_H2C4','ior_H3C4','ior_OVC');";
	foreach($resultArr as $key=>$row){
        $newDataArray[$row[MID]]['gid']=$row[MID];
        $newDataArray[$row[MID]]['datetime']=$date."<br>".$row[M_Time];
        $newDataArray[$row[MID]]['dategh']=$date.$row[MB_MID];
        $newDataArray[$row[MID]]['league']=$row[M_League];
        $newDataArray[$row[MID]]['datetimelove']=$date."<br>".$row[M_Time];
        $newDataArray[$row[MID]]['gnum_h']=$row[MB_MID];
        $newDataArray[$row[MID]]['gnum_c']=$row[TG_MID];
        $newDataArray[$row[MID]]['team_h']=$row[MB_Team]."<font color=gray> - [$Order_1st_Half]</font>";
        $newDataArray[$row[MID]]['team_c']=$row[TG_Team]."<font color=gray> - [$Order_1st_Half]</font>";
        $newDataArray[$row[MID]]['strong']=$row[ShowTypeR];
        $newDataArray[$row[MID]]['ior_H1C0']=change_rate($open,$row[MB1TG0H]);
        $newDataArray[$row[MID]]['ior_H2C0']=change_rate($open,$row[MB2TG0H]);
        $newDataArray[$row[MID]]['ior_H2C1']=change_rate($open,$row[MB2TG1H]);
        $newDataArray[$row[MID]]['ior_H3C0']=change_rate($open,$row[MB3TG0H]);
        $newDataArray[$row[MID]]['ior_H3C1']=change_rate($open,$row[MB3TG1H]);
        $newDataArray[$row[MID]]['ior_H3C2']=change_rate($open,$row[MB3TG2H]);
        $newDataArray[$row[MID]]['ior_OVH' ]=change_rate($open,$row[UP5H]);
        $newDataArray[$row[MID]]['ior_H0C1']=change_rate($open,$row[MB0TG1H]);
        $newDataArray[$row[MID]]['ior_H0C2']=change_rate($open,$row[MB0TG2H]);
        $newDataArray[$row[MID]]['ior_H1C2']=change_rate($open,$row[MB1TG2H]);
        $newDataArray[$row[MID]]['ior_H0C3']=change_rate($open,$row[MB0TG3H]);
        $newDataArray[$row[MID]]['ior_H1C3']=change_rate($open,$row[MB1TG3H]);
        $newDataArray[$row[MID]]['ior_H2C3']=change_rate($open,$row[MB2TG3H]);
        $newDataArray[$row[MID]]['ior_H0C0']=change_rate($open,$row[MB0TG0H]);
        $newDataArray[$row[MID]]['ior_H1C1']=change_rate($open,$row[MB1TG1H]);
        $newDataArray[$row[MID]]['ior_H2C2']=change_rate($open,$row[MB2TG2H]);
        $newDataArray[$row[MID]]['ior_H3C3']=change_rate($open,$row[MB3TG3H]);
        $newDataArray[$row[MID]]['bet_Url']="gid={$row[MID]}&uid={$uid}&odd_f_type=H&langx={$langx}&rtype=";
		//echo "parent.GameFT[$K]=new Array('$row[MID]','$date<br>$row[M_Time]','$row[M_League]','$row[MB_MID]','$row[TG_MID]','$row[MB_Team]<font color=gray> - [$Order_1st_Half]</font>','$row[TG_Team]<font color=gray> - [$Order_1st_Half]</font>','$row[ShowTypeR]','$row[MB1TG0H]','$row[MB2TG0H]','$row[MB2TG1H]','$row[MB3TG0H]','$row[MB3TG1H]','$row[MB3TG2H]','$row[MB4TG0H]','$row[MB4TG1H]','$row[MB4TG2H]','$row[MB4TG3H]','$row[MB0TG0H]','$row[MB1TG1H]','$row[MB2TG2H]','$row[MB3TG3H]','$row[MB4TG4H]','$row[UP5H]','$row[MB0TG1H]','$row[MB0TG2H]','$row[MB1TG2H]','$row[MB0TG3H]','$row[MB1TG3H]','$row[MB2TG3H]','$row[MB0TG4H]','$row[MB1TG4H]','$row[MB2TG4H]','$row[MB3TG4H]');\n";
		$K=$K+1;	
	}
    $reBallCountCur = $cou;
    $listTitle="今日足球：波胆";
    $leagueNameCur='';
	break;
case "hrpd"://半场滚球波胆
    $table='<tr class="title_fixed">
			<th class="ft_pd_h ft_hpd_h" >'.$U_06.' </th>	
			<th >1:0</th>
			<th >2:0</th>
			<th >2:1</th>
			<th >3:0</th>
			<th >3:1</th>
			<th >3:2</th>
			<th >0:0</th>
			<th >1:1</th>
			<th >2:2</th>
			<th >3:3</th>
			<th >'.$Others.'</th>
		    <tr>   <tr class="bet_correct_title">
            <td colspan="20">'.$U_12.'<span class="maxbet">'.$U_11.' RMB 1,000,000.00</span> </td>
              </tr>  ';
    $table_dif='bd_all pd_table' ;// 波胆table 类
	$reBallCountCur = 0;
	$page_size=60;

	//echo "parent.GameHead = new Array('gid','datetime','league','gnum_h','gnum_c','team_h','team_c','strong','ior_H1C0','ior_H2C0','ior_H2C1','ior_H3C0','ior_H3C1','ior_H3C2','ior_H4C0','ior_H4C1','ior_H4C2','ior_H4C3','ior_H0C0','ior_H1C1','ior_H2C2','ior_H3C3','ior_H4C4','ior_OVH','ior_H0C1','ior_H0C2','ior_H1C2','ior_H0C3','ior_H1C3','ior_H2C3','ior_H0C4','ior_H1C4','ior_H2C4','ior_H3C4','ior_OVC');";
	$matches=getRunningDataByMethod("FT_HPD");
	$cou=sizeof($matches);
	if(is_array($matches)){
		$cou=sizeof($matches);
	}else{
		$cou=0;
	}
	$page_count=ceil($cou/$page_size);
	echo "parent.gamount=$cou;\n";
	echo "parent.t_page=$page_count;\n";
	for($i=0;$i<$cou;$i++){
		$messages=$matches[$i];
		$messages=str_replace(");",")",$messages);
		$messages=str_replace("cha(9)","",$messages);
		$datainfo=eval("return $messages;");
		$midNew = $datainfo[0]-1;

        $newDataArray[$midNew]['gid']=$midNew;
        $newDataArray[$midNew]['datetime']=$datainfo[43];
        $newDataArray[$midNew]['dategh']=$date.$datainfo[3];
        $newDataArray[$midNew]['datetimelove']=$datainfo[43];
        $newDataArray[$midNew]['league']=$datainfo[2];
        $newDataArray[$midNew]['gnum_h']=$datainfo[3];
        $newDataArray[$midNew]['gnum_c']=$datainfo[4];
        $newDataArray[$midNew]['team_h']=$datainfo[5];
        $newDataArray[$midNew]['team_c']=$datainfo[6];
        $newDataArray[$midNew]['strong']=$datainfo[7];
        $newDataArray[$midNew]['ior_H1C0']=change_rate($open,$datainfo[8]);
        $newDataArray[$midNew]['ior_H2C0']=change_rate($open,$datainfo[9]);
        $newDataArray[$midNew]['ior_H2C1']=change_rate($open,$datainfo[10]);
        $newDataArray[$midNew]['ior_H3C0']=change_rate($open,$datainfo[11]);
        $newDataArray[$midNew]['ior_H3C1']=change_rate($open,$datainfo[12]);
        $newDataArray[$midNew]['ior_H3C2']=change_rate($open,$datainfo[13]);
        $newDataArray[$midNew]['ior_H4C0']=change_rate($open,$datainfo[14]);
        $newDataArray[$midNew]['ior_H4C1']=change_rate($open,$datainfo[15]);
        $newDataArray[$midNew]['ior_H4C2']=change_rate($open,$datainfo[16]);
        $newDataArray[$midNew]['ior_H4C3']=change_rate($open,$datainfo[17]);
        $newDataArray[$midNew]['ior_H0C0']=change_rate($open,$datainfo[18]);
        $newDataArray[$midNew]['ior_H1C1']=change_rate($open,$datainfo[19]);
        $newDataArray[$midNew]['ior_H2C2']=change_rate($open,$datainfo[20]);
        $newDataArray[$midNew]['ior_H3C3']=change_rate($open,$datainfo[21]);
        $newDataArray[$midNew]['ior_H4C4']=change_rate($open,$datainfo[22]);
        $newDataArray[$midNew]['ior_OVH']=change_rate($open,$datainfo[23]);
        $newDataArray[$midNew]['ior_H0C1']=change_rate($open,$datainfo[24]);
        $newDataArray[$midNew]['ior_H0C2']=change_rate($open,$datainfo[25]);
        $newDataArray[$midNew]['ior_H1C2']=change_rate($open,$datainfo[26]);
        $newDataArray[$midNew]['ior_H0C3']=change_rate($open,$datainfo[12]);
        $newDataArray[$midNew]['ior_H1C3']=change_rate($open,$datainfo[28]);
        $newDataArray[$midNew]['ior_H2C3']=change_rate($open,$datainfo[29]);
        $newDataArray[$midNew]['ior_H0C4']=change_rate($open,$datainfo[30]);
        $newDataArray[$midNew]['ior_H1C4']=change_rate($open,$datainfo[31]);
        $newDataArray[$midNew]['ior_H2C4']=change_rate($open,$datainfo[32]);
        $newDataArray[$midNew]['ior_H3C4']=change_rate($open,$datainfo[33]);
        $newDataArray[$midNew]['bet_Url']="gid={$midNew}&uid={$uid}&wtype=HRPD&odd_f_type=H&langx={$langx}&rtype=";
		$K=$K+1;

	}
	$reBallCountCur = $cou;
    $listTitle="滚球足球：波胆";
    $leagueNameCur='';
	break;
case "t"://总入球
    $table='<tr class="title_fixed">
              <th class="h_1x1" >'.$U_06.'</th>
              <th class="h_1x170"> </th>
             
              <th class="h_oe">0 - 1</th>
              <th class="h_oe">2 - 3</th>
              <th class="h_oe">4 - 6</th>
              <th class="h_oe">7up</th>
            </tr>
            <tr class="bet_correct_title">
            <td colspan="20">'.$U_08.'</td>
              </tr> ';
    $table_dif='bd_all' ;// 波胆table 类
	$page_size=60;
	$offset=$page_no*60;
	$resulTotal=getTodayMatches("TODAY_FT_T");
	$cou_num=count($resulTotal);
	$page_count=ceil($cou_num/$page_size);
	$resultArr=array_slice($resulTotal,$offset,$num);
	$cou=count($resultArr);

	echo "parent.t_page=$page_count;\n";
	echo "parent.gamount=$cou;\n";
	//echo "parent.GameHead = new Array('gid','datetime','league','gnum_h','gnum_c','team_h','team_c','strong','ior_ODD','ior_EVEN','ior_T01','ior_T23','ior_T46','ior_OVER','ior_MH','ior_MC','ior_MN');";
	foreach($resultArr as $key=>$row){
        $row[MB_Team]=str_replace("[Mid]","<font color=\'#005aff\'>[N]</font>",$row[MB_Team]);
        $row[MB_Team]=str_replace("[中]","<font color=\'#005aff\'>[中]</font>",$row[MB_Team]);
        $newDataArray[$row[MID]]['gid']=$row[MID];
        $newDataArray[$row[MID]]['dategh']=$date.$row[MB_MID];
        $newDataArray[$row[MID]]['datetimelove']=$date."<br>".$row[M_Time];
        $newDataArray[$row[MID]]['datetime']="$date<br>$row[M_Time]";
        $newDataArray[$row[MID]]['league']=$row[M_League];
        $newDataArray[$row[MID]]['gnum_h']=$row[MB_MID];
        $newDataArray[$row[MID]]['gnum_c']=$row[TG_MID];
        $newDataArray[$row[MID]]['team_h']=$row[MB_Team];
        $newDataArray[$row[MID]]['team_c']=$row[TG_Team];
        $newDataArray[$row[MID]]['strong']=$row[ShowTypeR];
        $newDataArray[$row[MID]]['ior_T01']=change_rate($open,$row['S_0_1']);
        $newDataArray[$row[MID]]['ior_T23']=change_rate($open,$row['S_2_3']);
        $newDataArray[$row[MID]]['ior_T46']=change_rate($open,$row['S_4_6']);
        $newDataArray[$row[MID]]['ior_OVER']=change_rate($open,$row['S_7UP']);
        $newDataArray[$row[MID]]['bet_Url']="gid={$row[MID]}&uid={$uid}&odd_f_type=H&langx={$langx}&rtype=";
        $K=$K+1;
    }
    $listTitle="今日足球:全场-总入球数";
    $leagueNameCur='';
	break;
case "rt"://滚球总入球
    $table='<tr class="title_fixed">
              <th class="h_1x1" >'.$U_06.'</th>
              <th class="h_1x170"> </th>
             
              <th class="h_oe">0 - 1</th>
              <th class="h_oe">2 - 3</th>
              <th class="h_oe">4 - 6</th>
              <th class="h_oe">7up</th>
            </tr>
            <tr class="bet_correct_title">
            <td colspan="20">'.$U_08.'</td>
              </tr> ';
    $table_dif='bd_all' ;// 波胆table 类
	$reBallCountCur = 0;
	$page_size=60;
	echo "parent.retime=20;\n"; // 倒计时刷新时间

	$matches=getRunningDataByMethod("FT_T");
	$cou=sizeof($matches);
	if(is_array($matches)){
		$cou=sizeof($matches);
	}else{
		$cou=0;
	}

    $matches=array();//滚球总进球数暂时关闭
    $cou=0;//滚球总进球数暂时关闭

	$page_count=ceil($cou/$page_size);
	echo "parent.gamount=$cou;\n";
	echo "parent.t_page=$page_count;\n";
	for($i=0;$i<$cou;$i++){
        $messages=$matches[$i];
        $messages=str_replace(");",")",$messages);
        $messages=str_replace("cha(9)","",$messages);
        $datainfo=eval("return $messages;");
        $datainfoA[8]=change_rate($open,$datainfo[8]);
        $datainfoA[9]=change_rate($open,$datainfo[9]);
        $datainfoA[10]=change_rate($open,$datainfo[10]);
        $datainfoA[11]=change_rate($open,$datainfo[11]);
        /*
        $opensql = "select MID,Open,M_League,MB_MID,TG_MID,TG_Team,MB_Team,ShowTypeR from `".DBPREFIX."match_sports` where  MID='$datainfo[0]' and `Type`='FT' and `Cancel`=0";
        $openresult = mysqli_query($dbMasterLink,$opensql);
        $openrow=mysqli_fetch_assoc($openresult);
        */
        //if($openrow['Open']==1){
        $newDataArray[$datainfo[0]]['gid']=$datainfo[0];
        $newDataArray[$datainfo[0]]['datetime']=$datainfo[22];
        $newDataArray[$datainfo[0]]['dategh']=$date.$datainfo[3];
        $newDataArray[$datainfo[0]]['datetimelove']=$datainfo[22];
        $newDataArray[$datainfo[0]]['league']=$datainfo[2];
        $newDataArray[$datainfo[0]]['gnum_h']=$datainfo[3];
        $newDataArray[$datainfo[0]]['gnum_c']=$datainfo[4];
        $newDataArray[$datainfo[0]]['team_h']=$datainfo[5];
        $newDataArray[$datainfo[0]]['team_c']=$datainfo[6];
        $newDataArray[$datainfo[0]]['strong']=$datainfo[16];
        $newDataArray[$datainfo[0]]['ior_T01']=$datainfoA[8];
        $newDataArray[$datainfo[0]]['ior_T23']=$datainfoA[9];
        $newDataArray[$datainfo[0]]['ior_T46']=$datainfoA[10];
        $newDataArray[$datainfo[0]]['ior_OVER']=$datainfoA[11];
        $newDataArray[$datainfo[0]]['bet_Url']="gid={$datainfo[0]}&uid={$uid}&odd_f_type=H&langx={$langx}&rtype=";
        $K=$K+1;
	}
    $listTitle="滚球足球：全场总入球数";
    $reBallCountCur = $cou;
    $leagueNameCur='';
	break;	
case "f":  //半场/全场
    $table='<tr class="title_fixed">
			<th class="h_1x1" >'.$U_06.'</th>
			<th class="h_1x170"> </th>
	
			<th class="h_f">'.$HH.'</th>
			<th class="h_f">'.$HD.'</th>
			<th class="h_f">'.$HA.'</th>
			<th class="h_f">'.$DH.'</th>
			<th class="h_f">'.$DD.'</th>
			<th class="h_f">'.$DA.'</th>
			<th class="h_f">'.$AH.'</th>
			<th class="h_f">'.$AD.'</th>
			<th class="h_f">'.$AA.'</th>
		    </tr>
		    <tr class="bet_correct_title">
                <td colspan="20">'.$U_09. ' <span class="maxbet">'.$U_11.'  ： RMB 1,000,000.00</span></td>
            </tr>';
    $table_dif='bd_all' ;// 波胆table 类
	$page_size=60;
	$offset=$page_no*60;
	$resulTotal=getTodayMatches("TODAY_FT_F");
	$cou_num=count($resulTotal);
	$page_count=ceil($cou_num/$page_size);
	$resultArr=array_slice($resulTotal,$offset,$num);
	$cou=count($resultArr);

	echo "parent.t_page=$page_count;\n";
	echo "parent.gamount=$cou;\n";
	//echo "parent.GameHead = new Array('gid','datetime','league','gnum_h','gnum_c','team_h','team_c','strong','ior_FHH','ior_FHN','ior_FHC','ior_FNH','ior_FNN','ior_FNC','ior_FCH','ior_FCN','ior_FCC');";
	foreach($resultArr as $key=>$row){
        $row[MB_Team]=str_replace("[Mid]","<font color=\'#005aff\'>[N]</font>",$row[MB_Team]);
        $row[MB_Team]=str_replace("[中]","<font color=\'#005aff\'>[中]</font>",$row[MB_Team]);
        $newDataArray[$row[MID]]['gid']=$row[MID];
        $newDataArray[$row[MID]]['dategh']=$date.$row[MB_MID];
        $newDataArray[$row[MID]]['datetimelove']=$date."<br>".$row[M_Time];
        $newDataArray[$row[MID]]['datetime']="$date<br>$row[M_Time]";
        $newDataArray[$row[MID]]['league']=$row[M_League];
        $newDataArray[$row[MID]]['gnum_h']=$row[MB_MID];
        $newDataArray[$row[MID]]['gnum_c']=$row[TG_MID];
        $newDataArray[$row[MID]]['team_h']=$row[MB_Team];
        $newDataArray[$row[MID]]['team_c']=$row[TG_Team];
        $newDataArray[$row[MID]]['strong']=$row[ShowTypeR];
        $newDataArray[$row[MID]]['ior_FHH']=change_rate($open,$row[MBMB]);
        $newDataArray[$row[MID]]['ior_FHN']=change_rate($open,$row[MBFT]);
        $newDataArray[$row[MID]]['ior_FHC']=change_rate($open,$row[MBTG]);
        $newDataArray[$row[MID]]['ior_FNH']=change_rate($open,$row[FTMB]);
        $newDataArray[$row[MID]]['ior_FNN']=change_rate($open,$row[FTFT]);
        $newDataArray[$row[MID]]['ior_FNC']=change_rate($open,$row[FTTG]);
        $newDataArray[$row[MID]]['ior_FCH']=change_rate($open,$row[TGMB]);
        $newDataArray[$row[MID]]['ior_FCN']=change_rate($open,$row[TGFT]);
        $newDataArray[$row[MID]]['ior_FCC']=change_rate($open,$row[TGTG]);
        $newDataArray[$row[MID]]['bet_Url']="gid={$row[MID]}&uid={$uid}&odd_f_type=H&langx={$langx}&rtype=";
        $K=$K+1;
    }
    $listTitle="今日足球:半场 /全场";
    $leagueNameCur='';
	break;

case "rf":
    $table='<tr class="title_fixed">
			<th class="h_1x1" >'.$U_06.'</th>
			<th class="h_1x170"> </th>
	
			<th class="h_f">'.$HH.'</th>
			<th class="h_f">'.$HD.'</th>
			<th class="h_f">'.$HA.'</th>
			<th class="h_f">'.$DH.'</th>
			<th class="h_f">'.$DD.'</th>
			<th class="h_f">'.$DA.'</th>
			<th class="h_f">'.$AH.'</th>
			<th class="h_f">'.$AD.'</th>
			<th class="h_f">'.$AA.'</th>
		    </tr>
		    <tr class="bet_correct_title">
                <td colspan="20">'.$U_09. ' <span class="maxbet">'.$U_11.'  ： RMB 1,000,000.00</span></td>
            </tr>';
    $table_dif='bd_all' ;// 波胆table 类
	$reBallCountCur = 0;
	$page_size=60;
	echo "parent.retime=20;\n"; // 倒计时刷新时间
	//echo "parent.GameHead = new Array('gid','datetime','league','gnum_h','gnum_c','team_h','team_c','strong','ior_FHH','ior_FHN','ior_FHC','ior_FNH','ior_FNN','ior_FNC','ior_FCH','ior_FCN','ior_FCC');";
	
	$matches=getRunningDataByMethod("FT_F");
	$cou=sizeof($matches);
	if(is_array($matches)){
		$cou=sizeof($matches);
	}else{
		$cou=0;
	}
	
	$page_count=ceil($cou/$page_size);
	echo "parent.gamount=$cou;\n";
	echo "parent.t_page=$page_count;\n";
	for($i=0;$i<$cou;$i++){
		$messages=$matches[$i];
		$messages=str_replace(");",")",$messages);
		$messages=str_replace("cha(9)","",$messages);
		$datainfo=eval("return $messages;");
		$datainfoA[8]=change_rate($open,$datainfo[8]);
		$datainfoA[9]=change_rate($open,$datainfo[9]);
		$datainfoA[10]=change_rate($open,$datainfo[10]);
		$datainfoA[11]=change_rate($open,$datainfo[11]);
		$datainfoA[12]=change_rate($open,$datainfo[12]);
		$datainfoA[13]=change_rate($open,$datainfo[13]);
		$datainfoA[14]=change_rate($open,$datainfo[14]);
		$datainfoA[15]=change_rate($open,$datainfo[15]);
		$datainfoA[16]=change_rate($open,$datainfo[16]);

        $newDataArray[$datainfo[0]]['gid']=$datainfo[0];
        $newDataArray[$datainfo[0]]['datetime']=$datainfo[27];
        $newDataArray[$datainfo[0]]['dategh']=$date.$datainfo[3];
        $newDataArray[$datainfo[0]]['datetimelove']=$datainfo[27];
        $newDataArray[$datainfo[0]]['league']=$datainfo[2];
        $newDataArray[$datainfo[0]]['gnum_h']=$datainfo[3];
        $newDataArray[$datainfo[0]]['gnum_c']=$datainfo[4];
        $newDataArray[$datainfo[0]]['team_h']=$datainfo[5];
        $newDataArray[$datainfo[0]]['team_c']=$datainfo[6];
        $newDataArray[$datainfo[0]]['strong']=$datainfo[21];
        $newDataArray[$datainfo[0]]['ior_FHH']=$datainfoA[8];
        $newDataArray[$datainfo[0]]['ior_FHN']=$datainfoA[9];
        $newDataArray[$datainfo[0]]['ior_FHC']=$datainfoA[10];
        $newDataArray[$datainfo[0]]['ior_FNH']=$datainfoA[11];
        $newDataArray[$datainfo[0]]['ior_FNN']=$datainfoA[12];
        $newDataArray[$datainfo[0]]['ior_FNC']=$datainfoA[13];
        $newDataArray[$datainfo[0]]['ior_FCH']=$datainfoA[14];
        $newDataArray[$datainfo[0]]['ior_FCN']=$datainfoA[15];
        $newDataArray[$datainfo[0]]['ior_FCC']=$datainfoA[16];
        $odd_f_type=($datainfo[21]=="H" || $datainfo[21]=='C') ? $datainfo[21]:'H'; 
        $newDataArray[$datainfo[0]]['bet_Url']="gid={$datainfo[0]}&uid={$uid}&odd_f_type={$odd_f_type}&wtype=RF&langx={$langx}&rtype=";
		$K=$K+1;
		
	}
    $reBallCountCur = $cou;
    $listTitle="足球滚球:半场 /全场";
    $leagueNameCur='';
	break;
    case "p3": // 综合过关
        $table='<tr class="title_fixed">
              <th nowrap class="h_1x1" >'.$U_06.'</th>
              <th class="h_1x170"> </th>
              <th nowrap class="h_1x2">'.$WIN.'</th>
              <th nowrap class="h_r">'.$U_02.'</th>
              <th nowrap class="h_ou">'.$U_03.'</th>
              <th class="h_oe">'.$O_E.'</th>
              <th nowrap class="h_1x2">'.$WIN.'</th>
              <th nowrap class="h_r">'.$U_04.'</th>
              <th nowrap class="h_ou">'.$U_05.'</th>
             </tr> <tr class="bet_correct_title">
                <td colspan="20">'.$U_10. ' <span class="maxbet">'.$U_11.'  ： RMB 1,000,000.00</span></td>
            </tr>';
        $table_dif='bd_all' ;// 波胆table 类
        $table_p3='bk_top' ;// 综合过关独有

        $rows=getP3Matches("TODAY_FT_P3");
        $cou=count($rows);
        $page_size=60;
        echo "parent.retime=0;\n";
        echo "parent.game_more=1;\n";
        echo "parent.str_more='$more';\n";
        echo "parent.gamount=$cou;\n";
        $page_count=intval($cou/$page_size);
        echo "parent.t_page=$page_count;";
        // 综合过关  RATIO_HMH ior_HPMH 独赢主队 ， RATIO_HMC ior_HPMC 独赢客队 ，RATIO_HMN ior_HPMN 独赢和局
       // echo "parent.GameHead = new Array('gid','datetime','league','gnum_h','gnum_c','team_h','team_c','strong','ratio','ior_PRH','ior_PRC','ratio_o','ratio_u','ior_POUC','ior_POUH','ior_PO','ior_PE','ior_MH','ior_MC','ior_MN','ior_H1C0','ior_H2C0','ior_H2C1','ior_H3C0','ior_H3C1','ior_H3C2','ior_H4C0','ior_H4C1','ior_H4C2','ior_H4C3','ior_H0C0','ior_H1C1','ior_H2C2','ior_H3C3','ior_H4C4','ior_OVH','ior_H0C1','ior_H0C2','ior_H1C2','ior_H0C3','ior_H1C3','ior_H2C3','ior_H0C4','ior_H1C4','ior_H2C4','ior_H3C4','ior_OVC','ior_T01','ior_T23','ior_T46','ior_OVER','ior_FHH','ior_FHN','ior_FHC','ior_FNH','ior_FNN','ior_FNC','ior_FCH','ior_FCN','ior_FCC','hgid','hstrong','hratio','ior_HPRH','ior_HPRC','hratio_o','hratio_u','ior_HPOUH','ior_HPOUC','ior_HH1C0','ior_HH2C0','ior_HH2C1','ior_HH3C0','ior_HH3C1','ior_HH3C2','ior_HH4C0','ior_HH4C1','ior_HH4C2','ior_HH4C3','ior_HH0C0','ior_HH1C1','ior_HH2C2','ior_HH3C3','ior_HH4C4','ior_HOVH','ior_HH0C1','ior_HH0C2','ior_HH1C2','ior_HH0C3','ior_HH1C3','ior_HH2C3','ior_HH0C4','ior_HH1C4','ior_HH2C4','ior_HH3C4','ior_HOVC','ior_HPMH','ior_HPMC','ior_HPMN','more','gidm','par_minlimit','par_maxlimit');";
        foreach($rows as $key=>$row){
            $mb_team=trim($row['MB_Team']);
            $m_date=strtotime($row['M_Date']);
            $dates=date("m-d",$m_date);
            if (strlen($row['M_Time'])==5){
                $pdate=$dates.'<br>0'.$row[M_Time];
            }else{
                $pdate=$dates.'<br>'.$row[M_Time];
            }
            if ($row['F_PD_Show']==1 and $row['F_T_Show']==1 and $row['F_F_Show']==1){
                $show=3;
            }else if ($row['F_HPD_Show']==1 and $row['F_PD_Show']==1 and $row['F_T_Show']==1 and $row['F_F_Show']==1){
                $show=4;
            }else{
                $show=0;
            }
            if($row[ShowTypeP]=="H"){
                $ratio_mb_str=$row[M_P_LetB];
                $ratio_tg_str='';
                $hratio_mb_str=$row[M_LetB_H];
                $hratio_tg_str='';
            }elseif($row[ShowTypeP]=="C"){
                $ratio_mb_str='';
                $ratio_tg_str=$row[M_P_LetB];
                $hratio_mb_str='';
                $hratio_tg_str=$row[M_LetB_H];
            }
            $row[MB_Team]=str_replace("[Mid]","<font color=\'#005aff\'>[N]</font>",$row[MB_Team]);
            $row[MB_Team]=str_replace("[中]","<font color=\'#005aff\'>[中]</font>",$row[MB_Team]);
            $newDataArray[$row[MID]]['gid']=$row[MID];
            $newDataArray[$row[MID]]['dategh']=date('m-d').$row[MB_MID];
            $newDataArray[$row[MID]]['datetime']=$pdate;
            $newDataArray[$row[MID]]['league']=$row[M_League];
            $newDataArray[$row[MID]]['gnum_h']=$row[MB_MID];
            $newDataArray[$row[MID]]['gnum_c']=$row[TG_MID];
            $newDataArray[$row[MID]]['team_h']=$row[MB_Team];
            $newDataArray[$row[MID]]['team_c']=$row[TG_Team];
            $newDataArray[$row[MID]]['strong']=$row[ShowTypeP];
            $newDataArray[$row[MID]]['ratio']=$row[M_P_LetB];
            $newDataArray[$row[MID]]['ratio_mb_str']=$ratio_mb_str;
            $newDataArray[$row[MID]]['ratio_tg_str']=$ratio_tg_str;
            $newDataArray[$row[MID]]['ior_PRH']=change_rate($open,$row['MB_P_LetB_Rate']);
            $newDataArray[$row[MID]]['ior_PRC']=change_rate($open,$row['TG_P_LetB_Rate']);
            $newDataArray[$row[MID]]['ratio_o']=$row[MB_P_Dime];
            $newDataArray[$row[MID]]['ratio_u']=$row[TG_P_Dime];
            $newDataArray[$row[MID]]['ratio_o_str']="大".str_replace('O','',$row[MB_P_Dime]);
            $newDataArray[$row[MID]]['ratio_u_str']="小".str_replace('U','',$row[TG_P_Dime]);
            $newDataArray[$row[MID]]['ior_POUC']=change_rate($open,$row['MB_P_Dime_Rate']);
            $newDataArray[$row[MID]]['ior_POUH']=change_rate($open,$row['TG_P_Dime_Rate']);
            $newDataArray[$row[MID]]['ior_PO']=change_rate($open,$row['S_P_Single_Rate']);
            $newDataArray[$row[MID]]['ior_PE']=change_rate($open,$row['S_P_Double_Rate']);
            $newDataArray[$row[MID]]['ior_MH']=change_rate($open,$row["MB_P_Win_Rate"]);
            $newDataArray[$row[MID]]['ior_MC']=change_rate($open,$row["TG_P_Win_Rate"]);
            $newDataArray[$row[MID]]['ior_MN']=change_rate($open,$row["M_P_Flat_Rate"]);
            $newDataArray[$row[MID]]['hstrong']=$row[ShowTypeP];
            $newDataArray[$row[MID]]['hratio']=$row[M_LetB_H];
            $newDataArray[$row[MID]]['hratio_mb_str']=$hratio_mb_str;
            $newDataArray[$row[MID]]['hratio_tg_str']=$hratio_tg_str;
            $newDataArray[$row[MID]]['ior_HPRH']=change_rate($open,$row["MB_P_LetB_Rate_H"]);  // 半场让球主队;
            $newDataArray[$row[MID]]['ior_HPRC']=change_rate($open,$row["TG_P_LetB_Rate_H"]); // 半场让球客队;
            $newDataArray[$row[MID]]['hratio_o']=$row[MB_Dime_H];
            $newDataArray[$row[MID]]['hratio_u']=$row[TG_Dime_H];
            $newDataArray[$row[MID]]['hratio_o_str']="大".str_replace('O','',$row[MB_Dime_H]);
            $newDataArray[$row[MID]]['hratio_u_str']="小".str_replace('U','',$row[TG_Dime_H]);
            $newDataArray[$row[MID]]['ior_HPOUH']=change_rate($open,$row['TG_P_Dime_Rate_H']); // 半场客队小;
            $newDataArray[$row[MID]]['ior_HPOUC']=change_rate($open,$row['MB_P_Dime_Rate_H']); // 半场主队大
            $newDataArray[$row[MID]]['ior_HPMH']=change_rate($open,$row["MB_Win_Rate_H"]); // RATIO_HMH ior_HPMH 独赢主队
            $newDataArray[$row[MID]]['ior_HPMC']=change_rate($open,$row["TG_Win_Rate_H"]); // RATIO_HMC ior_HPMC 独赢客队
            $newDataArray[$row[MID]]['ior_HPMN']=change_rate($open,$row["M_Flat_Rate_H"]);  // RATIO_HMN ior_HPMN 独赢和局
            $newDataArray[$row[MID]]['more']=$show;
            $newDataArray[$row[MID]]['gidm']=$row[MID];
            $newDataArray[$row[MID]]['par_minlimit']=3;
            $newDataArray[$row[MID]]['par_maxlimit']=10;
            $K=$K+1;
        }
        $listTitle= '今日足球 : 综合过关';
        $leagueNameCur='';
        break;
}
?>
//function onLoad(){
//	if(parent.parent.mem_order.location == 'about:blank'){
//		parent.parent.mem_order.location = '<?php //echo BROWSER_IP?>///app/member/select.php?uid=<?php //echo $uid?>//&langx=<?php //echo $langx?>//';
//	}
//	if(parent.retime > 0)
//		parent.retime_flag='Y';
//	else
//		parent.retime_flag='N';
//	parent.loading_var = 'N';
//	if(parent.loading == 'N' && parent.ShowType != ''){
//		parent.ShowGameList();
//	}
//}
//
//function onUnLoad(){
//	x = parent.body_browse.pageXOffset;
//	y = parent.body_browse.pageYOffset;
//	parent.body_browse.scroll(x,y);
//
//}
//重置滚球数量
window.defaultStatus="Wellcome.................";
</script>
</head>
<body  onLoad="onLoad();"  class="load_body_var <?php echo $table_dif?>">
<!--<div id="controlscroll"><table border="0" cellspacing="0" cellpadding="0" class="loadBox"><tr><td> </td></tr></table></div>-->
<!-- 球赛展示区顶部 开始-->
<div class="bet_head">
    <!--左侧按钮-->
    <div class="bet_left">
        <?php

        if($rtype=='r' or $rtype=='re'){ // 全部,滚球才有
            ?>
            <span id="showNull" title="<?php echo $U_16 ?>" class="bet_star_btn_out fav_null" >
            <tt class="bet_star_text">
               0
            </tt>
        </span>

            <span id="showAll" title="<?php echo $U_17 ?>" onClick="showAllGame('FT');" style="display:none;" class="bet_star_btn_all fav_out">
            <tt class="bet_star_All">全部</tt>
            <tt id="live_num_all" class="bet_star_text" style="display: none;"> </tt>
        </span>
            <span id="showMy" title="<?php echo $U_18 ?>" onClick="showMyLove('FT');" style="display:none;" class="bet_star_btn_on">
            <!--我的最爱场数-->
            <tt id="live_num" class="bet_star_text" ></tt>
        </span>

            <?php
        }
        ?>
        <!-- 选择联赛 -->
        <span id="sel_league" onclick="chg_league();" class="bet_league_btn">
            <tt class="bet_normal_text">
               <?php echo $U_19 ?> (<tt id="str_num" class="bet_yellow"></tt>)
            </tt>
        </span>
        <?php
        if($rtype=='pd'){ // 只有波胆上半才有
            echo '<span class="bet_game_btn" onclick="chg_wtype(\'hpd\');"><tt class="bet_normal_text" >半场</tt></span>' ;
        }else if($rtype=='hpd'){ // 波胆下半场
            echo '<span class="bet_game_btn" onclick="chg_wtype(\'pd\');"><tt class="bet_normal_text" >全场</tt></span>' ;
        }
        ?>
        <?php
        if($rtype=='r' or $rtype=='re' or $rtype=='r_main' or $rtype=='re_main'){ // 全部盘口、主要盘口
            ?>
            <span id="sel_Market" class="bet_view_btn" onclick="chgMarket('main');" style="display: <?php if($rtype=='r' or $rtype=='re'){echo 'inline-block';}else{echo 'none';}?>;"><tt id="SpanMarket" class="bet_normal_text">主要盘口</tt></span>
            <span id="all_sel_Market" class="bet_view_btn" onclick="chgMarket('all');" style="display: <?php if($rtype=='r_main' or $rtype=='re_main'){echo 'inline-block';}else{echo 'none';}?>;"><tt id="all_SpanMarket" class="bet_normal_text">全部盘口</tt></span>
            <span id="sel_filters" class="bet_Special_btn" onclick="show_filters();"><tt id="SpanFilter" class="bet_normal_text">隐藏特殊</tt></span>
            <span id="show_pg_chk" style="display:none;" class="bet_paging"><label><input id="pg_chk" onclick="clickChkbox();" type="checkbox" class="bet_selsect_box" value="C"><span></span><span class="bet_more_chk">分页</span></label></span>
            <div id="show_pg_chk_msg" style="display:none;" class="bet_game_head_i"><div class="bet_head_i_bg"><span class="bet_head_iarrow_text">如您觉得网页运行缓慢,请选分页，<br>这会限制每页显示的比赛场数。</span></div></div>
            <?php
        }
        ?>

    </div>

    <!--右侧按钮-->
    <div class="bet_right">
        <!--<span id="pg_txt" class="bet_page_btn" style="display:none;">
       </span>-->
        <span id="sel_sort" class="bet_sort_time_btn"><tt class="bet_sort_text">排序</tt>
             <div id="show_sort"  class="bet_sort_bg" style="display:none;" tabindex="100">
                <span class="bet_arrow"></span>
                <span class="bet_arrow_text">赛事排序</span>
                <ul id="SortSel" selvalue="T">
                <li id="sort_time" onclick="chgSortValue('T');" class="bet_sort_time_choose">按时间排序</li>
                <li id="sort_leg" onclick="chgSortValue('C');" class="bet_sort_comp">按联盟排序</li>
                </ul>
             </div>
         </span>

        <!--盘口选择 -->
        <?php
        if($rtype=='r' or $rtype=='re'){ // 只有全部才有
            echo '<span id="sel_odd" class="bet_odds_btn"><tt id="chose_odd" class="bet_normal_text">香港盘</tt></span>' ;
        }
        ?>

        <span class="bet_time_btn" onclick="javascript:reload_var()">
            <tt id="refreshTime" class="bet_time_text"><?php echo $U_14 ?></tt>
        </span>

    </div>

</div>
<!-- 球赛展示区顶部 结束-->

<!-- 日期 -->
<?php
// 只有综合过关才有 时间
if($rtype=='p3'){ ?>
    <div id="show_date_opt" class="bet_title_date">
        <?php
        $date2=date('Y-m-d',time()+24*60*60);
        $date3=date('Y-m-d',time()+2*24*60*60);
        $date4=date('Y-m-d',time()+3*24*60*60);
        $date5=date('Y-m-d',time()+4*24*60*60);
        $date6=date('Y-m-d',time()+5*24*60*60);
        $date7=date('Y-m-d',time()+6*24*60*60);
        $date8=date('Y-m-d',time()+7*24*60*60);
        $date9=date('Y-m-d',time()+8*24*60*60);

        $todayDate=date('Y-m-d',time());
        echo "<span value='$todayDate' onclick='chg_gdate(this)' class='".($g_date==$todayDate?'bet_date_color':'')."'>今日</span>";
        for($datei=1;$datei<10;$datei++){
            $dateNowValue=date('Y-m-d',time()+$datei*24*60*60);
            $dateNowStr=date('m'.'月'.'d'.'日',time()+$datei*24*60*60);
            echo "<span value='$dateNowValue' onclick='chg_gdate(this)' class='".($g_date==$dateNowValue?'bet_date_color':'')."'>$dateNowStr</span>";
        }
        echo "<span value='ALL' onclick='chg_gdate(this)' class='".($g_date=='ALL'?'bet_date_color':'')."'>全部</span>";
        ?>
    </div>
<?php } ?>

<table border="0" cellpadding="0" cellspacing="0" id="myTable" class="bet_game_table <?php echo $table_p3?>" >
    <tr>
        <td>
            <table border="0" cellpadding="0" cellspacing="0" id="box">

                <tr>
                    <td class="mem">
                        <!--     资料显示的layer     -->
                        <div id=showtable>

                            <table class="bet_game_top">
                                <?php echo $table ?>
                            </table>

                            <table id="game_table"  cellspacing="0" cellpadding="0" class="game ">
                                <tbody>
                                <?php
                                if(count($newDataArray)==0){
                                    echo "<tr><td colspan=20 class='no_game'>您选择的项目暂时没有赛事。请修改您的选项或迟些再返回。</td></tr>";
                                }else{
                                    switch ($rtype){
                                        case "r":
                                        case "r_main":
                                            include "body_m_r_ou_eo.php";
                                            break;
                                        case "re":
                                        case "re_main":
                                            include "body_rb_m_r_ou_eo.php";
                                            break;
                                        case "pd":	include "body_pd.php";break;
                                        case "rpd":include "body_rpd.php";break;
                                        case "hpd":include "body_hpd.php";break;
                                        case "hrpd":include "body_hrpd.php";break;
                                        case "rt":	include "body_rt.php";break;
                                        case "t":	include "body_t.php";break;
                                        case "rf":	include "body_rf.php";break;
                                        case "f":	include "body_f.php";break;
                                        case "p3":	include "../FT_future/body_p3.php";break;
                                    }
                                }
                                ?>
                                </tbody>
                            </table>

                        </div>
                    </td>
                </tr>

            </table>

        </td>
    </tr>

</table>

<!-- 分页 -->
<div id="show_page_txt" class="bet_page_bot_rt">

</div>

<div class="more" id="more_window" name="more_window" >
    <iframe id=showdata name=showdata scrolling='no' frameborder="NO" border="0" framespacing="0" noresize topmargin="0" leftmargin="0" marginwidth=0 marginheight=0 ></iframe>
</div>

<!-- 所有玩法弹窗 -->
<div class="all_more" id="all_more_window" name="all_more_window" style="position:absolute; display:none; ">
    <iframe id="all_showdata" name="all_showdata" scrolling='no' frameborder="NO" border="0" framespacing="0" noresize topmargin="0" leftmargin="0" marginwidth=0 marginheight=0 height="100%" width="100%"></iframe>
</div>

<!--选择联赛-->
<div id="legView" style="display:none;" class="legView" >
    <div class="leg_head" onMousedown="initializedragie('legView')"></div>

    <div><iframe id="legFrame" frameborder="no" border="0" allowtransparency="true"></iframe></div>


    <div class="leg_foot"></div>
</div>

<script type="text/javascript" src="../../../js/jquery.js"></script>
<script type="text/javascript" src="../../../js/common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../../js/layer/layer.js"></script>
<script type="text/javascript" src="../../../js/src/common_body_var.js?v=<?php echo AUTOVER; ?>"></script>
<script>
    // 会员提示信息
    if(top.memberNum=='1'){
        if (top.game_alert.indexOf('Message')==-1){
            layer.alert(top.memberMsg, {
                title: '会员信息',
                icon: false , // 0,1
                skin: 'layer-ext-moon'
            }) ;

            top.game_alert +='Message,' ;
        }
    }

    setBodyScroll();

</script>
</body>
</html>
<?php
}
?>