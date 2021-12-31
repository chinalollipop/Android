<?php
error_reporting(E_ALL);
ini_set('display_errors','Off');
/*今日赛事-足球-（独赢-让球-大小&单、双）*/

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

$m_date=date('Y-m-d');
$date=date('m-d');

$uid=$_REQUEST['uid'];
$langx=$_SESSION['langx']?$_SESSION['langx']:'zh-cn';
$mtype=$_REQUEST['mtype'];
$rtype=$_REQUEST['rtype'];
// 判断是否维护-单页面维护功能
if ($rtype=='re'){
    checkMaintain('rb');
}else{
    checkMaintain($_REQUEST['showtype']);
}
$league_id=$_REQUEST['league_id'];
$page_no=$_REQUEST['page_no'];
$showtype=$_REQUEST['showtype'];
$leaname = $_REQUEST['leaname'] ; // 搜索赛事
$sorttype = $_REQUEST['sorttype'] ? $_REQUEST['sorttype'] : 'time';
// $mylovegameid = isset($_REQUEST['mylovegame'])?$_REQUEST['mylovegame']:'' ; // 最爱赛事
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
    exit;
}else{

 $oddchange = returnOddsChangsStatus() ;

    require ("../include/traditional.$langx.inc.php");
    $g_date = isset($_REQUEST['g_date'])?$_REQUEST['g_date']:'' ; // 筛选日期

    if($leaname=='undefined'){
        $leaname='' ;
    }
    if($g_date=="" || $g_date=="undefined"){ // 默认不带日期
        if($rtype=='p3'){ // 综合过关 g_date
            if($showtype =='future'){ // 早盘
                $g_date = 'ALL';
            }else{ // 今日赛事
                $g_date = $m_date ; // 今日
            }
        }
    }

$open=$_SESSION['OpenType'];
$memname=$_SESSION['UserName'];

if ($league_id=='' and $showtype!='hgft'){
	$num=60;
}else{
	$num=1024;
}
if ($page_no==''){
    $page_no=0;
}

$K=0;

$redisObj = new Ciredis();

//根据玩法获取滚球数据
function getRunningDataByMethod($key){
	global $redisObj,$leaname;
	$matchesJson = $redisObj->getSimpleOne($key);
	$matches = json_decode($matchesJson,true);
    $mylovegame=$_REQUEST['mylovegame'];
    $myleaArr=$_REQUEST['myleaArr'];
    if(isset($mylovegame)&&strlen($mylovegame)>0){//收藏筛选
        $mylovegameArr=explode(',',$mylovegame);
        if(count($mylovegameArr)>0){
            foreach($matches as $key=>$val){
				$valArr=explode(',',$val);
                if(!in_array(str_replace('\'','',$valArr[3]),$mylovegameArr)){
                    unset($matches[$key]);
                }
				$valArr=array();
            }
        }
    }

    if(isset($myleaArr)&&strlen($myleaArr)>0){//联盟筛选
        $myleaArr=explode(',',$myleaArr);
        if(count($myleaArr)>0){
            foreach($matches as $key=>$val){
                if(!in_array($val['LEAGUE'],$myleaArr)){
                    unset($matches[$key]);
                }
            }
        }
    }

    $matches=array_values($matches);

	if(isset($leaname)&&strlen($leaname)>0){
        foreach( $matches as $key=>$val ){
            if(strpos($val['TEAM_H'],$leaname)>-1 || strpos($val['TEAM_C'],$leaname)>-1 || strpos($val['LEAGUE'],$leaname)>-1){
                $matchesNew[]=$val;
            }
        }
        return $matchesNew;
	}else{
		return $matches;
	}
}

//根据玩法获取滚球数据,2021新版刷水
function getRunningDataByMethod2021($key){
    global $redisObj,$leaname;
    $matchesJson = $redisObj->getSimpleOne($key);
    $matches = json_decode($matchesJson,true);

    return $matches;
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
    global $redisObj,$dbMasterLink,$showtype,$leaname,$g_date;
    if($showtype=='future'){
        $key='FUTURE_FT_P3';
    }else{
        $key='TODAY_FT_P3';
    }
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
        $match[47] = isset($match[47]) ?  $match[47] : $match['DATETIME'];
        $match[100] = str_replace('<br>', ' ', $match[47]); //02-28 01:35a

        $sAorP = substr($match[100],11);
        $match[100] = date('Y-m-d H:i:s',strtotime(date('Y').'-'.substr($match[100],0, -1)));
        if ($sAorP == 'p'){
            $match[100] = date('Y-m-d H:i:s',strtotime($match[100])+43200);
        }
        $datainfo_list[$key][100] = $match[100];
    }
//    $datainfo_list = array_sort($datainfo_list,0,$type='asc');
    $datainfo_list = array_sort($datainfo_list,100,$type='asc');
    return $datainfo_list;
}

// 今日盘口按照时间排序
function matchesSortByTime(){
    global $matches;
    foreach ($matches as $key => $match){
        // 转换时间 01:35a  -》  01:35:00
        // 转换时间 01:35p  -》  13:35:00
        $sAorP = substr($match['M_Time'],5);
        $match['M_Time'] = substr($match['M_Time'],0,-1);
        $match['M_Time'] = date('Y-m-d H:i:s',strtotime($match['M_Time']));
        if ($sAorP=='p'){
            $match['M_Time'] = date('Y-m-d H:i:s',strtotime($match['M_Time'])+43200);
        }
        $matches[$key]['M_Time'] = $match['M_Time'];
    }
    $matches = array_sort($matches,47,$type='asc');
    return $matches;
}

// 会员足球电竞开关
if(strpos($_SESSION['gameSwitch'],'|')>0){
    $gameArr=explode('|',$_SESSION['gameSwitch']);
}else{
    if(strlen($_SESSION['gameSwitch'])>0){
        $gameArr[]=$_SESSION['gameSwitch'];
    }else{
        $gameArr=array();
    }
}
if(in_array('DJFT',$gameArr)){
    $mem_djft_off = 'off';
}
$newDataArray = array();
?>
<HEAD>
<TITLE>足球变数值</TITLE>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
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

parent.retime = 60 ; // 今日赛事刷新倒计时
parent.gamount=0;
parent.t_page=0;

<?php
switch ($rtype){
case "r":  // 全部
	$page_size=60;
	$offset=$page_no*60;
	$resulTotal=getTodayMatches("TODAY_FT_M_ROU_EO");
//    if ($_REQUEST['sorttype'] == 'time'){
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
	
	$gameVideoNow = $redisObj->getSimpleOne('gameVideoNow');
	$gameVideoNowArr = json_decode($gameVideoNow,true);
	$gameVideoFuture = $redisObj->getSimpleOne('gameVideoFuture');
	$gameVideoFutureArr = json_decode($gameVideoFuture,true);
	
	echo "parent.str_renew = '$second_auto_update';\n";
	echo "parent.game_more=1;\n";
	echo "parent.str_more='$more';\n";
	echo "parent.t_page=$page_count;\n";	
	echo "parent.gamount=$cou;\n";
	//  hratio 半场让分 ， ior_HRH 半场让球主队 ， ior_HRC 半场让球客队
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
        $pos = strpos($row['M_League'],'电竞足球');
        $pos_zh_tw = strpos($row['M_League'],'電競足球');
        if ($pos === false){}
        else{
            if ($mem_djft_off == 'off'){
                continue;
            }
        }
        if ($pos_zh_tw === false){}
        else{
            if ($mem_djft_off == 'off'){
                continue;
            }
        }
		$newDataArray[$row[MID]]['gid']=$row[MID];
		$newDataArray[$row[MID]]['dategh']=$date.$row[MB_MID];
		$newDataArray[$row[MID]]['datetime']=$date."<br>".$row[M_Time].$Running;
		$newDataArray[$row[MID]]['datetimelove']=$date."<br>".$row[M_Time];
		$newDataArray[$row[MID]]['league']=$row[M_League];
		$newDataArray[$row[MID]]['gnum_h']=$row[MB_MID];
		$newDataArray[$row[MID]]['gnum_c']=$row[TG_MID];
        if ($row['Neutral']==1){
            $newDataArray[$row[MID]]['team_h']=$row[MB_Team]." <font color='#005aff'>[中]</font>";
        }else{
            $newDataArray[$row[MID]]['team_h']=$row[MB_Team];
        }
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
		$newDataArray[$row[MID]]['hgid']=$row[MID];
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
		if(in_array($row['Eventid'],$gameVideoNowArr)){
			$newDataArray[$row[MID]]['event']='on';	
		}elseif(in_array($row['Eventid'],$gameVideoFutureArr)){
			$newDataArray[$row[MID]]['event']='out';	
		}else{
			$newDataArray[$row[MID]]['event']='no';	
		}
		//var_dump($newDataArray);
		$K=$K+1;	
	}
	$listTitle="今日足球";
	$leagueNameCur='';
	break;
case "re": // 滚球----------------------------------------------------------		start	--------------------------------------------------------------------------------------
	//获取刷水账号
	$reBallCountCur = 0;
	$page_size=60;
	echo "parent.retime=20;\n"; // 滚球倒计时刷新时间
	echo "parent.game_more=1;\n";
	echo "parent.str_more='$more';\n";
	echo "parent.str_renew = '$second_auto_update';\n";
    
	$gameVideoNow = $redisObj->getSimpleOne('gameVideoNow');
	$gameVideoNowArr = json_decode($gameVideoNow,true);
	$gameVideoFuture = $redisObj->getSimpleOne('gameVideoFuture');
	$gameVideoFutureArr = json_decode($gameVideoFuture,true);
	
    $matches=getRunningDataByMethod("FT_M_ROU_EO");
	if(is_array($matches)){
		$cou=sizeof($matches);
	}else{
		$cou=0;
	}
	$gamecount =0 ;
	$page_count=ceil($cou/$page_size);
	echo "parent.t_page=$page_count;\n";
	
	$LastOdds = '';
	$currOdds = array();
	$LastOdds = $redisObj->getSimpleOne('FT_M_ROU_EO_LastOdds_'.$oddchange);
	$LastOddsArr = json_decode($LastOdds,true);
	/*for($i=0;$i<$cou;$i++){
	    if($matches[$i]!=''){
            $messages=$matches[$i];
            $messages=str_replace(");",")",$messages);
            $messages=str_replace("cha(9)","",$messages);
            $datainfo=eval("return $messages;");
            $datainfo_list[] = $datainfo;
        }
	}*/
    $datainfo_list = $matches;

    if ($sorttype == 'time'){
        $datainfo_list = runningMatchesSortByTime();
    }

    // 会员足球电竞滚球最后2分钟的开关
    if(strpos($_SESSION['gameSwitch'],'|')>0){
        $gameArr=explode('|',$_SESSION['gameSwitch']);
    }else{
        if(strlen($_SESSION['gameSwitch'])>0){
            $gameArr[]=$_SESSION['gameSwitch'];
        }else{
            $gameArr=array();
        }
    }
//    if(in_array('DJFT',$gameArr)){
//        $mem_djft_off = 'off';
//    }

    $gamecount = sizeof($datainfo_list);
    foreach ($datainfo_list as $k => $datainfo){
		//if ($openrow['Open']==1){

        $datainfo[8] = isset($datainfo[8])?$datainfo[8]:$datainfo['RATIO_RE'];     //让球数
        $datainfo[9] = isset($datainfo[9])?$datainfo[9]:$datainfo['IOR_REH'];      //滚球主队让球的赔率
        $datainfo[10] = isset($datainfo[10])?$datainfo[10]:$datainfo['IOR_REC'];   //滚球客队让球的赔率
        $datainfo[13] = isset($datainfo[13])?$datainfo[13]:$datainfo['IOR_ROUH'];  //滚球客队全场赔率
        $datainfo[14] = isset($datainfo[14])?$datainfo[14]:$datainfo['IOR_ROUC'];  //滚球主队全场赔率
        $datainfo[22] = isset($datainfo[22])?$datainfo[22]:$datainfo['RATIO_HRE']; //半场滚球让球数
        $datainfo[23] = isset($datainfo[23])?$datainfo[23]:$datainfo['IOR_HREH'];  //半场滚球主队让球的赔率
        $datainfo[24] = isset($datainfo[24])?$datainfo[24]:$datainfo['IOR_HREC'];  //半场滚球客队让球的赔率
        $datainfo[27] = isset($datainfo[27])?$datainfo[27]:$datainfo['IOR_HROUH']; //滚球客队半场小的赔率
        $datainfo[28] = isset($datainfo[28])?$datainfo[28]:$datainfo['IOR_HROUC']; //滚球主队半场大的赔率
        $datainfo[33] = isset($datainfo[33])?$datainfo[33]:$datainfo['IOR_RMH'];   //滚球主队独赢赔率
        $datainfo[34] = isset($datainfo[34])?$datainfo[34]:$datainfo['IOR_RMC'];   //滚球客队独赢赔率
        $datainfo[35] = isset($datainfo[35])?$datainfo[35]:$datainfo['IOR_RMN'];   //滚球和的赔率
        $datainfo[36] = isset($datainfo[36])?$datainfo[36]:$datainfo['IOR_HRMH'];  //半场滚球主队独赢赔率
        $datainfo[37] = isset($datainfo[37])?$datainfo[37]:$datainfo['IOR_HRMC'];  //半场滚球客队独赢赔率
        $datainfo[38] = isset($datainfo[38])?$datainfo[38]:$datainfo['IOR_HRMN'];  //半场滚球和的赔率

        // 电竞最后最后2分钟是否提前关闭拆解一下：
        // 8分钟的电竞盘口   上半场第3分钟开始关闭赔率，下半场第6分钟开始关闭赔率
        // 10分钟的电竞盘口   上半场第4分钟开始关闭赔率，下半场第8分钟开始关闭赔率
        // 12分钟的电竞盘口   上半场第5分钟开始关闭赔率，下半场第10分钟开始关闭赔率
        // $datainfo[48];  2H^06:56
        // 电竞足球-FIFA 20英格兰网络明星联赛-10分钟比赛
        $pos = strpos($datainfo['LEAGUE'],'电竞足球');
        if ($pos === false){}
        else{
//            if ($mem_djft_off == 'off'){

                $pos8minute = strpos($datainfo['LEAGUE'],'8分钟比赛');
                if ($pos8minute===false){}
                else{
                    $matchTotalMinites = 8;
                    $currentMinuteIn8 = explode(':',explode('^',$datainfo['RETIMESET'])[1])[0];
                    $retimeset0 = explode('^',$datainfo['RETIMESET'])[0];
                }

                $pos10minute = strpos($datainfo['LEAGUE'],'10分钟比赛');
                if ($pos10minute===false){}
                else{
                    $matchTotalMinites = 10;
                    $currentMinuteIn10 = explode(':',explode('^',$datainfo['RETIMESET'])[1])[0];
                    $retimeset0 = explode('^',$datainfo['RETIMESET'])[0];
                }

                $pos12minute = strpos($datainfo['LEAGUE'],'12分钟比赛');
                if ($pos12minute===false){}
                else{
                    $matchTotalMinites = 12;
                    $currentMinuteIn12 = explode(':',explode('^',$datainfo['RETIMESET'])[1])[0];
                    $retimeset0 = explode('^',$datainfo['RETIMESET'])[0];
                }

                $posYQminute = strpos($datainfo['LEAGUE'],'电竞邀请赛');
                if ($posYQminute===false){}
                else{
                    $matchTotalMinites = 12;
                    $currentMinuteIn12 = explode(':',explode('^',$datainfo['RETIMESET'])[1])[0];
                    $retimeset0 = explode('^',$datainfo['RETIMESET'])[0];
                }

                // 上半场
                if(
                    ($matchTotalMinites==8 and $currentMinuteIn8>=3 and $retimeset0=='1H') or
                    ($matchTotalMinites==10 and $currentMinuteIn10>=4 and $retimeset0=='1H') or
                    ($matchTotalMinites==12 and $currentMinuteIn12>=5 and $retimeset0=='1H')
                ){
                    // 半场大小
                    $datainfo[22]='';
                    // 半场让球
                    $datainfo[23]='';
                    $datainfo[24]='';
                    $datainfo[27]='';
                    $datainfo[28]='';
                    // 半场独赢
                    $datainfo[36]='';
                    $datainfo[37]='';
                    $datainfo[38]='';
                    // 所有玩法
                    $datainfo[49]='';
                }

                // 全场
                if (
                    ($matchTotalMinites==8 and $currentMinuteIn8>=6 and $retimeset0=='2H') or
                    ($matchTotalMinites==10 and $currentMinuteIn10>=8 and $retimeset0=='2H') or
                    ($matchTotalMinites==12 and $currentMinuteIn12>=10 and $retimeset0=='2H')

                ){
                    $datainfo[8]='';
                    $datainfo[22]='';
                    $datainfo[9]='';
                    $datainfo[10]='';
                    $datainfo[13]='';
                    $datainfo[14]='';
                    $datainfo[23]='';
                    $datainfo[24]='';
                    $datainfo[27]='';
                    $datainfo[28]='';
                    $datainfo[33]='';
                    $datainfo[34]='';
                    $datainfo[35]='';
                    $datainfo[36]='';
                    $datainfo[37]='';
                    $datainfo[38]='';
                    $datainfo[41]='';
                    $datainfo[42]='';
                    $datainfo[49]='';
                }

//            }
        }

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
			/*
			if($openrow['HPD_Show']==1 and $openrow['PD_Show']==1 and $openrow['T_Show']==1 and $openrow['F_Show']==1){
	    		$show=4;
			}else if ($openrow['PD_Show']==1 and $openrow['T_Show']==1 and $openrow['F_Show']==1){
	    		$show=3;
			}else{
			    $show=0;
			}*/
			$allMethods=$datainfo['MORE']<5 ? 0:$datainfo['MORE'];

        if($datainfo['STRONG']=="H"){
            $ratio_mb_str=$datainfo['RATIO_RE'];
            $ratio_tg_str='';
        }elseif($datainfo['STRONG']=="C"){
            $ratio_mb_str='';
            $ratio_tg_str=$datainfo['RATIO_RE'];
        }
        if($datainfo['HSTRONG']=="H"){
            $hratio_mb_str=$datainfo['RATIO_HRE'];
            $hratio_tg_str='';
        }elseif($datainfo['HSTRONG']=="C"){
            $hratio_mb_str='';
            $hratio_tg_str=$datainfo['RATIO_HRE'];
        }
        $datainfo['TEAM_H']=str_replace("[Mid]","<font color='#005aff'>[N]</font>",$datainfo['TEAM_H']);
        $datainfo['TEAM_H']=str_replace("[中]","<font color='#005aff'>[中]</font>",$datainfo['TEAM_H']);
        $pos = strpos($datainfo['LEAGUE'],'电竞足球');
        $pos_zh_tw = strpos($datainfo['LEAGUE'],'電競足球');
        if ($pos === false){}
        else{
            if ($mem_djft_off == 'off'){
                continue;
            }
        }
        if ($pos_zh_tw === false){}
        else{
            if ($mem_djft_off == 'off'){
                continue;
            }
        }
            $newDataArray[$datainfo['GID']]['gid']=$datainfo['GID'];
            $newDataArray[$datainfo['GID']]['timer'] =$datainfo['TIMER'];
            $newDataArray[$datainfo['GID']]['dategh']=$date.$datainfo['GNUM_H'];
            $newDataArray[$datainfo['GID']]['datetimelove']=$datainfo['DATETIME'];
            $newDataArray[$datainfo['GID']]['league']=$datainfo['LEAGUE'];
            $newDataArray[$datainfo['GID']]['gnum_h']=$datainfo['GNUM_H'];
            $newDataArray[$datainfo['GID']]['gnum_c']=$datainfo['GNUM_C'];
            if ($datainfo['Neutral']==1){
                $newDataArray[$datainfo['GID']]['team_h']=$datainfo['TEAM_H']." <font color='#005aff'>[中]</font>";
            }else{
                $newDataArray[$datainfo['GID']]['team_h']=$datainfo['TEAM_H'];
            }
            $newDataArray[$datainfo['GID']]['team_h_for_sort']=explode(' ',$datainfo['TEAM_H'])[0];
            $newDataArray[$datainfo['GID']]['team_c']=$datainfo['TEAM_C'];
            $newDataArray[$datainfo['GID']]['strong']=$datainfo['STRONG'];
            $newDataArray[$datainfo['GID']]['ratio']=$datainfo['RATIO_RE']=$datainfo[8];
            $newDataArray[$datainfo['GID']]['ratio_mb_str']=$ratio_mb_str;
            $newDataArray[$datainfo['GID']]['ratio_tg_str']=$ratio_tg_str;
            $newDataArray[$datainfo['GID']]['ior_RH']=$datainfo['IOR_REH']=$datainfo[9];
            $newDataArray[$datainfo['GID']]['ior_RC']=$datainfo['IOR_REC']=$datainfo[10];
            $newDataArray[$datainfo['GID']]['bet_RH']="gid={$datainfo['GID']}&uid={$uid}&odd_f_type=H&type=H&gnum={$datainfo['GNUM_H']}&strong={$datainfo['STRONG']}&langx={$langx}";
            $newDataArray[$datainfo['GID']]['bet_RC']="gid={$datainfo['GID']}&uid={$uid}&odd_f_type=H&type=C&gnum={$datainfo['GNUM_C']}&strong={$datainfo['STRONG']}&langx={$langx}";
            $newDataArray[$datainfo['GID']]['ratio_o']=$datainfo['RATIO_ROUO'];
            $newDataArray[$datainfo['GID']]['ratio_u']=$datainfo['RATIO_ROUU'];
            $newDataArray[$datainfo['GID']]['ratio_o_str']="大".str_replace('O','',$datainfo['RATIO_ROUO']);
            $newDataArray[$datainfo['GID']]['ratio_u_str']="小".str_replace('U','',$datainfo['RATIO_ROUU']);
            $newDataArray[$datainfo['GID']]['ior_OUH']=$datainfo['IOR_ROUH']=$datainfo[13]; // 全场大小 客队
            $newDataArray[$datainfo['GID']]['ior_OUC']=$datainfo['IOR_ROUC']=$datainfo[14]; // 全场大小 主队
            $newDataArray[$datainfo['GID']]['bet_OUH']="gid={$datainfo['GID']}&uid={$uid}&odd_f_type=H&type=C&gnum={$datainfo['GNUM_H']}&langx={$langx}";
            $newDataArray[$datainfo['GID']]['bet_OUC']="gid={$datainfo['GID']}&uid={$uid}&odd_f_type=H&type=H&gnum={$datainfo['GNUM_C']}&langx={$langx}";
            $newDataArray[$datainfo['GID']]['no1']=$datainfo['NO1'];
            $newDataArray[$datainfo['GID']]['no2']=$datainfo['NO2'];
            $newDataArray[$datainfo['GID']]['no3']=$datainfo['NO3'];
            $newDataArray[$datainfo['GID']]['score_h']=$datainfo['SCORE_H'];
            $newDataArray[$datainfo['GID']]['score_c']=$datainfo['SCORE_C'];
            $newDataArray[$datainfo['GID']]['hgid']  =$datainfo['HGID'];
            $newDataArray[$datainfo['GID']]['hstrong']=$datainfo['HSTRONG'];
            $newDataArray[$datainfo['GID']]['hratio'] =$datainfo['RATIO_HRE']=$datainfo[22];
            $newDataArray[$datainfo['GID']]['hratio_mb_str']=$hratio_mb_str;
            $newDataArray[$datainfo['GID']]['hratio_tg_str']=$hratio_tg_str;
            $newDataArray[$datainfo['GID']]['ior_HRH']=$datainfo['IOR_HREH']=$datainfo[23];
            $newDataArray[$datainfo['GID']]['ior_HRC']=$datainfo['IOR_HREC']=$datainfo[24];
            $newDataArray[$datainfo['GID']]['hratio_o']=$datainfo['RATIO_ROUHO'];
            $newDataArray[$datainfo['GID']]['hratio_u']=$datainfo['RATIO_ROUHU'];
            $newDataArray[$datainfo['GID']]['hratio_o_str']="大".str_replace('O','',$datainfo['RATIO_HROUO']);
            $newDataArray[$datainfo['GID']]['hratio_u_str']="小".str_replace('U','',$datainfo['RATIO_HROUU']);
            $newDataArray[$datainfo['GID']]['ior_HOUH']=$datainfo['IOR_HROUH']=$datainfo[27]; // 半场小 客队
            $newDataArray[$datainfo['GID']]['ior_HOUC']=$datainfo['IOR_HROUC']=$datainfo[28]; // 半场大 主队
            $newDataArray[$datainfo['GID']]['redcard_h']=$datainfo['REDCARD_H'];
            $newDataArray[$datainfo['GID']]['redcard_c']=$datainfo['REDCARD_C'];
            $newDataArray[$datainfo['GID']]['lastestscore_h'] =$datainfo['LASTESTSCORE_H'];
            $newDataArray[$datainfo['GID']]['lastestscore_c'] =$datainfo['LASTESTSCORE_C'];
            $newDataArray[$datainfo['GID']]['ior_MH']=$datainfo['IOR_RMH']=$datainfo[33];
            $newDataArray[$datainfo['GID']]['ior_MC']=$datainfo['IOR_RMC']=$datainfo[34];
            $newDataArray[$datainfo['GID']]['ior_MN']=$datainfo['IOR_RMN']=$datainfo[35];
            $newDataArray[$datainfo['GID']]['ior_HMH']=$datainfo['IOR_HRMH']=$datainfo[36];
            $newDataArray[$datainfo['GID']]['ior_HMC']=$datainfo['IOR_HRMC']=$datainfo[37];
            $newDataArray[$datainfo['GID']]['ior_HMN']=$datainfo['IOR_HRMN']=$datainfo[38];
            $newDataArray[$datainfo['GID']]['bet_MH']="gid={$datainfo['GID']}&uid={$uid}&odd_f_type=H&type=H&gnum={$datainfo['GNUM_H']}&langx={$langx}";
            $newDataArray[$datainfo['GID']]['bet_MC']="gid={$datainfo['GID']}&uid={$uid}&odd_f_type=H&type=C&gnum={$datainfo['GNUM_C']}&langx={$langx}";
            $newDataArray[$datainfo['GID']]['bet_MN']="gid={$datainfo['GID']}&uid={$uid}&odd_f_type=H&type=N&gnum={$datainfo['GNUM_C']}&langx={$langx}";
            $newDataArray[$datainfo['GID']]['str_odd']=$o;
            $newDataArray[$datainfo['GID']]['str_even']=$e;
            $newDataArray[$datainfo['GID']]['ior_EOO']=$datainfo['IOR_REOO']>0?$datainfo['IOR_REOO']:'';
            $newDataArray[$datainfo['GID']]['ior_EOE']=$datainfo['IOR_REOE']>0?$datainfo['IOR_REOE']:'';
            $newDataArray[$datainfo['GID']]['bet_EOO']="gid={$datainfo['GID']}&uid={$uid}&odd_f_type=H&rtype=RODD&langx={$langx}";
            $newDataArray[$datainfo['GID']]['bet_EOE']="gid={$datainfo['GID']}&uid={$uid}&odd_f_type=H&rtype=REVEN&langx={$langx}";
            $newDataArray[$datainfo['GID']]['eventid']=$datainfo['EVENTID'];
            $newDataArray[$datainfo['GID']]['hot']=$datainfo['HOT']=$datainfo[44];
            $newDataArray[$datainfo['GID']]['play']=$datainfo['PLAY']=$datainfo[46];
            $newDataArray[$datainfo['GID']]['datetime']=$datainfo['DATETIME']=$datainfo[47];
            $newDataArray[$datainfo['GID']]['retimeset']=$datainfo['RETIMESET'];
            $newDataArray[$datainfo['GID']]['more']=$show;
            $newDataArray[$datainfo['GID']]['all']=$allMethods;
			if(in_array($datainfo[43],$gameVideoNowArr)){
				$newDataArray[$datainfo['GID']]['event']='on';
			}elseif(in_array($datainfo[43],$gameVideoFutureArr)){
				$newDataArray[$datainfo['GID']]['event']='out';
			}else{
				$newDataArray[$datainfo['GID']]['event']='no';
			}
				
			$tmpset=explode("^", $datainfo['RETIMESET']); // 足球滚球的倒计时
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
		        if($tmpset[0]=="HT"){$showretime=$tmpset[1];}
		    }
		    $newDataArray[$datainfo['GID']]['showretime']=$showretime;
			$K=$K+1;
			if ($gmid==''){
				$gmid=$datainfo['GID'];
			}else{
				$gmid=$gmid.','.$datainfo['GID'];
			}
		//}
        //var_dump($LastOddsArr[$datainfo['GID']]);
		if(isset($LastOddsArr[$datainfo['GID']])){
			$oddsBackground[$datainfo['GID']]['ior_RH'] = $LastOddsArr[$datainfo['GID']]['ior_RH']==$datainfo[9] ? 0 : 1;
			$oddsBackground[$datainfo['GID']]['ior_RC'] = $LastOddsArr[$datainfo['GID']]['ior_RC']==$datainfo[10] ? 0 : 1;
			$oddsBackground[$datainfo['GID']]['ior_OUH'] = $LastOddsArr[$datainfo['GID']]['ior_OUH']==$datainfo[13] ? 0 : 1;
			$oddsBackground[$datainfo['GID']]['ior_OUC'] = $LastOddsArr[$datainfo['GID']]['ior_OUC']==$datainfo[14] ? 0 : 1;
			$oddsBackground[$datainfo['GID']]['ior_HRH'] = $LastOddsArr[$datainfo['GID']]['ior_HRH']==$datainfo[23] ? 0 : 1;
			$oddsBackground[$datainfo['GID']]['ior_HRC'] = $LastOddsArr[$datainfo['GID']]['ior_HRC']==$datainfo[24] ? 0 : 1;
			$oddsBackground[$datainfo['GID']]['ior_HOUH'] = $LastOddsArr[$datainfo['GID']]['ior_HOUH']==$datainfo[27] ? 0 : 1;
			$oddsBackground[$datainfo['GID']]['ior_HOUC'] = $LastOddsArr[$datainfo['GID']]['ior_HOUC']==$datainfo[28] ? 0 : 1;
			$oddsBackground[$datainfo['GID']]['ior_MH'] = $LastOddsArr[$datainfo['GID']]['ior_MH']==$datainfo[33] ? 0 : 1;
			$oddsBackground[$datainfo['GID']]['ior_MC'] = $LastOddsArr[$datainfo['GID']]['ior_MC']==$datainfo[34] ? 0 : 1;
			$oddsBackground[$datainfo['GID']]['ior_MN'] = $LastOddsArr[$datainfo['GID']]['ior_MN']==$datainfo[35] ? 0 : 1;
			$oddsBackground[$datainfo['GID']]['ior_HMH'] = $LastOddsArr[$datainfo['GID']]['ior_HMH']==$datainfo[36] ? 0 : 1;
			$oddsBackground[$datainfo['GID']]['ior_HMC'] = $LastOddsArr[$datainfo['GID']]['ior_HMC']==$datainfo[37] ? 0 : 1;
			$oddsBackground[$datainfo['GID']]['ior_HMN'] = $LastOddsArr[$datainfo['GID']]['ior_HMN']==$datainfo[38] ? 0 : 1;
			$oddsBackground[$datainfo['GID']]['ior_EOO'] = $LastOddsArr[$datainfo['GID']]['ior_EOO']==$datainfo[41] ? 0 : 1;
			$oddsBackground[$datainfo['GID']]['ior_EOE'] = $LastOddsArr[$datainfo['GID']]['ior_EOE']==$datainfo[42] ? 0 : 1;
		}
			$currOdds[$datainfo['GID']]['ior_RH']=$datainfo[9];//让球 主队
			$currOdds[$datainfo['GID']]['ior_RC']=$datainfo[10];//让球 客队
			$currOdds[$datainfo['GID']]['ior_OUH']=$datainfo[13];//主队全场大小
			$currOdds[$datainfo['GID']]['ior_OUC']=$datainfo[14];//客队全场大小
			$currOdds[$datainfo['GID']]['ior_HRH']=$datainfo[23];//让球 主队 半场
			$currOdds[$datainfo['GID']]['ior_HRC']=$datainfo[24];//让球 客队 半场
			$currOdds[$datainfo['GID']]['ior_HOUH']=$datainfo[27];//客队半场大小
			$currOdds[$datainfo['GID']]['ior_HOUC']=$datainfo[28];//主队半场大小
			$currOdds[$datainfo['GID']]['ior_MH']=$datainfo[33];//独赢 主
			$currOdds[$datainfo['GID']]['ior_MC']=$datainfo[34];//独赢 客
			$currOdds[$datainfo['GID']]['ior_MN']=$datainfo[35];//独赢 和
			$currOdds[$datainfo['GID']]['ior_HMH']=$datainfo[36];//独赢 主 半场
			$currOdds[$datainfo['GID']]['ior_HMC']=$datainfo[37];//独赢 客 半场
			$currOdds[$datainfo['GID']]['ior_HMN']=$datainfo[38];//独赢 和 半场
			$currOdds[$datainfo['GID']]['ior_EOO']=$datainfo[41];//单双
			$currOdds[$datainfo['GID']]['ior_EOE']=$datainfo[42];//单双
	}
	if($LastOddsArr!=$currOdds){
		$redisObj->setOne('FT_M_ROU_EO_LastOdds_'.$oddchange,json_encode($currOdds));
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
    $offset=$page_no*$page_size;
    $newDataArray=array_slice($newDataArray,$offset,$page_size);
	echo "parent.gamount=$gamecount;\n"; // 总数量
	$reBallCountCur = $cou;
	$listTitle="足球：滾球";
	$leagueNameCur='';
	/*echo '<pre>';
	print_r($newDataArray);
	echo '<br/>';*/
	//die();
	break;
	// 滚球------------------------------------------------------------------------------------	end	--------------------------------------------------------------------------------------
case "pd":  //波胆
	$page_size=60;
	$offset=$page_no*60;
	$resulTotal=getTodayMatches("TODAY_FT_PD");
	$cou_num=count($resulTotal);
	$page_count=ceil($cou_num/$page_size);
	$resultArr=array_slice($resulTotal,$offset,$num);
	$cou=count($resultArr);
    $today_bet_floatright ='today_bet_floatright_pd' ;
    $box_pd ='box_pd' ;
	echo "parent.t_page=$page_count;\n";
	echo "parent.gamount=$cou;\n";
	foreach($resultArr as $key=>$row){
		$row[MB_Team]=str_replace("[Mid]","<font color=\'#005aff\'>[N]</font>",$row[MB_Team]);
		$row[MB_Team]=str_replace("[中]","<font color=\'#005aff\'>[中]</font>",$row[MB_Team]);
        $pos = strpos($row['M_League'],'电竞足球');
        $pos_zh_tw = strpos($row['M_League'],'電競足球');
        if ($pos === false){}
        else{
            if ($mem_djft_off == 'off'){
                continue;
            }
        }
        if ($pos_zh_tw === false){}
        else{
            if ($mem_djft_off == 'off'){
                continue;
            }
        }
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
	$reBallCountCur = 0;
	$page_size=60;
	echo "parent.retime=20;\n"; // 滚球倒计时刷新时间
    $today_bet_floatright ='today_bet_floatright_pd' ;
    $box_pd ='box_pd' ;
	$matches=getRunningDataByMethod("FT_PD");
	$cou=sizeof($matches);
//	if(is_array($matches)){
//        $matches=array();
//	}else{
//		$cou=0;
//	}
	$page_count=ceil($cou/$page_size);
	echo "parent.gamount=$cou;\n";
	echo "parent.t_page=$page_count;\n";
	$LastOdds = '';
	$currOdds = array();
	$LastOdds = $redisObj->getSimpleOne('FT_PD_LastOdds_'.$oddchange);
	$LastOddsArr = json_decode($LastOdds,true);
//	for($i=0;$i<$cou;$i++){
		/*$messages=$matches[$i];
		$messages=str_replace(");",")",$messages);
		$messages=str_replace("cha(9)","",$messages);
		$datainfo=eval("return $messages;");*/

    foreach ($matches as $k => $v){
        $datainfo=$v;
        $datainfo[0]=$datainfo['GID'];
        $datainfo[2]=$datainfo['LEAGUE'];
        $pos = strpos($datainfo[2],'电竞足球');
        $pos_zh_tw = strpos($datainfo[2],'電競足球');
        if ($pos === false){}
        else{
            if ($mem_djft_off == 'off'){
                continue;
            }
        }
        if ($pos_zh_tw === false){}
        else{
            if ($mem_djft_off == 'off'){
                continue;
            }
        }
		/*
		$opensql = "select Open,M_League,MB_MID,TG_MID,MB_Team,TG_Team from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where  MID='$datainfo[0]' and `Type`='FT' and `Cancel`=0";
		$openresult = mysqli_query($dbMasterLink,$opensql);
	    $openrow=mysqli_fetch_assoc($openresult);*/
	    //if ($openrow['Open']==1){
        $tmpset=explode("^", $datainfo['RETIMESET']); // 足球滚球的倒计时
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
            if($tmpset[0]=="HT"){$showretime=$tmpset[1];}
        }
        $newDataArray[$datainfo['GID']]['showretime']=$showretime;

        $newDataArray[$datainfo['GID']]['gid']=$datainfo[0];
			$newDataArray[$datainfo['GID']]['datetime']=$datainfo['DATETIME'];
			//$newDataArray[$datainfo['GID']]['datetimelove']=$date."<br>".$datainfo[45];
			$newDataArray[$datainfo['GID']]['datetimelove']=$datainfo['DATETIME'];
			$newDataArray[$datainfo['GID']]['dategh']=$date.$datainfo[3];
			$newDataArray[$datainfo['GID']]['league']=$datainfo[2];
			$newDataArray[$datainfo['GID']]['gnum_h']=$datainfo['GNUM_H'];
			$newDataArray[$datainfo['GID']]['gnum_c']=$datainfo['GNUM_C'];
			$newDataArray[$datainfo['GID']]['team_h']=$datainfo['TEAM_H'];
			$newDataArray[$datainfo['GID']]['team_c']=$datainfo['TEAM_C'];
			$newDataArray[$datainfo['GID']]['strong']=$datainfo['STRONG'];
            $newDataArray[$datainfo['GID']]['score_h']=$datainfo['SCORE_H'];    //  主 比分
            $newDataArray[$datainfo['GID']]['score_c']=$datainfo['SCORE_C'];    //  客 比分
            $newDataArray[$datainfo['GID']]['ior_H1C0']=change_rate($open,$datainfo['IOR_RH1C0']);
            $newDataArray[$datainfo['GID']]['ior_H2C0']=change_rate($open,$datainfo['IOR_RH2C0']);
            $newDataArray[$datainfo['GID']]['ior_H2C1']=change_rate($open,$datainfo['IOR_RH2C1']);
            $newDataArray[$datainfo['GID']]['ior_H3C0']=change_rate($open,$datainfo['IOR_RH3C0']);
            $newDataArray[$datainfo['GID']]['ior_H3C1']=change_rate($open,$datainfo['IOR_RH3C1']);
            $newDataArray[$datainfo['GID']]['ior_H3C2']=change_rate($open,$datainfo['IOR_RH3C2']);
            $newDataArray[$datainfo['GID']]['ior_H4C0']=change_rate($open,$datainfo['IOR_RH4C0']);
            $newDataArray[$datainfo['GID']]['ior_H4C1']=change_rate($open,$datainfo['IOR_RH4C1']);
            $newDataArray[$datainfo['GID']]['ior_H4C2']=change_rate($open,$datainfo['IOR_RH4C2']);
            $newDataArray[$datainfo['GID']]['ior_H4C3']=change_rate($open,$datainfo['IOR_RH4C3']);
            $newDataArray[$datainfo['GID']]['ior_H0C0']=change_rate($open,$datainfo['IOR_RH0C0']);
            $newDataArray[$datainfo['GID']]['ior_H1C1']=change_rate($open,$datainfo['IOR_RH1C1']);
            $newDataArray[$datainfo['GID']]['ior_H2C2']=change_rate($open,$datainfo['IOR_RH2C2']);
            $newDataArray[$datainfo['GID']]['ior_H3C3']=change_rate($open,$datainfo['IOR_RH3C3']);
            $newDataArray[$datainfo['GID']]['ior_H4C4']=change_rate($open,$datainfo['IOR_RH4C4']);
            $newDataArray[$datainfo['GID']]['ior_OVH']=change_rate($open,$datainfo['IOR_ROVH']);
            $newDataArray[$datainfo['GID']]['ior_H0C1']=change_rate($open,$datainfo['IOR_RH0C1']);
            $newDataArray[$datainfo['GID']]['ior_H0C2']=change_rate($open,$datainfo['IOR_RH0C2']);
            $newDataArray[$datainfo['GID']]['ior_H1C2']=change_rate($open,$datainfo['IOR_RH1C2']);
            $newDataArray[$datainfo['GID']]['ior_H0C3']=change_rate($open,$datainfo['IOR_RH0C3']);
            $newDataArray[$datainfo['GID']]['ior_H1C3']=change_rate($open,$datainfo['IOR_RH1C3']);
            $newDataArray[$datainfo['GID']]['ior_H2C3']=change_rate($open,$datainfo['IOR_RH2C3']);
            $newDataArray[$datainfo['GID']]['ior_H0C4']=change_rate($open,$datainfo['IOR_RH0C4']);
            $newDataArray[$datainfo['GID']]['ior_H1C4']=change_rate($open,$datainfo['IOR_RH1C4']);
            $newDataArray[$datainfo['GID']]['ior_H2C4']=change_rate($open,$datainfo['IOR_RH2C4']);
            $newDataArray[$datainfo['GID']]['ior_H3C4']=change_rate($open,$datainfo['IOR_RH3C4']);
            $newDataArray[$datainfo['GID']]['bet_Url']="gid={$datainfo['GID']}&uid={$uid}&odd_f_type=H&langx={$langx}&rtype=";
		    //echo "parent.GameFT[$K]=new Array('$datainfo[0]','$datainfo[45]','$openrow[M_League]','$openrow[MB_MID]','$openrow[TG_MID]','$openrow[MB_Team]','$openrow[TG_Team]','$datainfo[7]','$datainfoA[8]','$datainfoA[9]','$datainfoA[10]','$datainfoA[11]','$datainfoA[12]','$datainfoA[13]','$datainfoA[14]','$datainfoA[15]','$datainfoA[16]','$datainfoA[17]','$datainfoA[18]','$datainfoA[19]','$datainfoA[20]','$datainfoA[21]','$datainfoA[22]','$datainfoA[23]','$datainfoA[24]','$datainfoA[25]','$datainfoA[26]','$datainfoA[27]','$datainfoA[28]','$datainfoA[29]','$datainfoA[30]','$datainfoA[31]','$datainfoA[32]','$datainfoA[33]','$datainfo[34]');\n";
			$K=$K+1;
		//}
			if(isset($LastOddsArr[$datainfo['GID']])){
                $oddsBackground[$datainfo['GID']]['ior_H1C0'] = $LastOddsArr[$datainfo['GID']]['ior_H1C0']==$datainfo['IOR_RH1C0'] ? 0 : 1;
                $oddsBackground[$datainfo['GID']]['ior_H2C0'] = $LastOddsArr[$datainfo['GID']]['ior_H2C0']==$datainfo['IOR_RH2C0'] ? 0 : 1;
                $oddsBackground[$datainfo['GID']]['ior_H2C1'] = $LastOddsArr[$datainfo['GID']]['ior_H2C1']==$datainfo['IOR_RH2C1'] ? 0 : 1;
                $oddsBackground[$datainfo['GID']]['ior_H3C0'] = $LastOddsArr[$datainfo['GID']]['ior_H3C0']==$datainfo['IOR_RH3C0'] ? 0 : 1;
                $oddsBackground[$datainfo['GID']]['ior_H3C1'] = $LastOddsArr[$datainfo['GID']]['ior_H3C1']==$datainfo['IOR_RH3C1'] ? 0 : 1;
                $oddsBackground[$datainfo['GID']]['ior_H3C2'] = $LastOddsArr[$datainfo['GID']]['ior_H3C2']==$datainfo['IOR_RH3C2'] ? 0 : 1;
                $oddsBackground[$datainfo['GID']]['ior_H4C0'] = $LastOddsArr[$datainfo['GID']]['ior_H4C0']==$datainfo['IOR_RH4C0'] ? 0 : 1;
                $oddsBackground[$datainfo['GID']]['ior_H4C1'] = $LastOddsArr[$datainfo['GID']]['ior_H4C1']==$datainfo['IOR_RH4C1'] ? 0 : 1;
                $oddsBackground[$datainfo['GID']]['ior_H4C2'] = $LastOddsArr[$datainfo['GID']]['ior_H4C2']==$datainfo['IOR_RH4C2'] ? 0 : 1;
                $oddsBackground[$datainfo['GID']]['ior_H4C3'] = $LastOddsArr[$datainfo['GID']]['ior_H4C3']==$datainfo['IOR_RH4C3'] ? 0 : 1;
                $oddsBackground[$datainfo['GID']]['ior_H0C0'] = $LastOddsArr[$datainfo['GID']]['ior_H0C0']==$datainfo['IOR_RH0C0'] ? 0 : 1;
                $oddsBackground[$datainfo['GID']]['ior_H1C1'] = $LastOddsArr[$datainfo['GID']]['ior_H1C1']==$datainfo['IOR_RH1C1'] ? 0 : 1;
                $oddsBackground[$datainfo['GID']]['ior_H2C2'] = $LastOddsArr[$datainfo['GID']]['ior_H2C2']==$datainfo['IOR_RH2C2'] ? 0 : 1;
                $oddsBackground[$datainfo['GID']]['ior_H3C3'] = $LastOddsArr[$datainfo['GID']]['ior_H3C3']==$datainfo['IOR_RH3C3'] ? 0 : 1;
                $oddsBackground[$datainfo['GID']]['ior_H4C4'] = $LastOddsArr[$datainfo['GID']]['ior_H4C4']==$datainfo['IOR_RH4C4'] ? 0 : 1;
                $oddsBackground[$datainfo['GID']]['ior_OVH']  = $LastOddsArr[$datainfo['GID']]['ior_OVH'] ==$datainfo['IOR_ROVH'] ? 0 : 1;
                $oddsBackground[$datainfo['GID']]['ior_H0C1'] = $LastOddsArr[$datainfo['GID']]['ior_H0C1']==$datainfo['IOR_RH0C1'] ? 0 : 1;
                $oddsBackground[$datainfo['GID']]['ior_H0C2'] = $LastOddsArr[$datainfo['GID']]['ior_H0C2']==$datainfo['IOR_RH0C2'] ? 0 : 1;
                $oddsBackground[$datainfo['GID']]['ior_H1C2'] = $LastOddsArr[$datainfo['GID']]['ior_H1C2']==$datainfo['IOR_RH1C2'] ? 0 : 1;
                $oddsBackground[$datainfo['GID']]['ior_H0C3'] = $LastOddsArr[$datainfo['GID']]['ior_H0C3']==$datainfo['IOR_RH0C3'] ? 0 : 1;
                $oddsBackground[$datainfo['GID']]['ior_H1C3'] = $LastOddsArr[$datainfo['GID']]['ior_H1C3']==$datainfo['IOR_RH1C3'] ? 0 : 1;
                $oddsBackground[$datainfo['GID']]['ior_H2C3'] = $LastOddsArr[$datainfo['GID']]['ior_H2C3']==$datainfo['IOR_RH2C3'] ? 0 : 1;
                $oddsBackground[$datainfo['GID']]['ior_H0C4'] = $LastOddsArr[$datainfo['GID']]['ior_H0C4']==$datainfo['IOR_RH0C4'] ? 0 : 1;
                $oddsBackground[$datainfo['GID']]['ior_H1C4'] = $LastOddsArr[$datainfo['GID']]['ior_H1C4']==$datainfo['IOR_RH1C4'] ? 0 : 1;
                $oddsBackground[$datainfo['GID']]['ior_H2C4'] = $LastOddsArr[$datainfo['GID']]['ior_H2C4']==$datainfo['IOR_RH2C4'] ? 0 : 1;
                $oddsBackground[$datainfo['GID']]['ior_H3C4'] = $LastOddsArr[$datainfo['GID']]['ior_H3C4']==$datainfo['IOR_RH3C4'] ? 0 : 1;
			}
        $currOdds[$datainfo['GID']]['ior_H1C0'] = $datainfo['IOR_RH1C0'];
        $currOdds[$datainfo['GID']]['ior_H2C0'] = $datainfo['IOR_RH2C0'];
        $currOdds[$datainfo['GID']]['ior_H2C1'] = $datainfo['IOR_RH2C1'];
        $currOdds[$datainfo['GID']]['ior_H3C0'] = $datainfo['IOR_RH3C0'];
        $currOdds[$datainfo['GID']]['ior_H3C1'] = $datainfo['IOR_RH3C1'];
        $currOdds[$datainfo['GID']]['ior_H3C2'] = $datainfo['IOR_RH3C2'];
        $currOdds[$datainfo['GID']]['ior_H4C0'] = $datainfo['IOR_RH4C0'];
        $currOdds[$datainfo['GID']]['ior_H4C1'] = $datainfo['IOR_RH4C1'];
        $currOdds[$datainfo['GID']]['ior_H4C2'] = $datainfo['IOR_RH4C2'];
        $currOdds[$datainfo['GID']]['ior_H4C3'] = $datainfo['IOR_RH4C3'];
        $currOdds[$datainfo['GID']]['ior_H0C0'] = $datainfo['IOR_RH0C0'];
        $currOdds[$datainfo['GID']]['ior_H1C1'] = $datainfo['IOR_RH1C1'];
        $currOdds[$datainfo['GID']]['ior_H2C2'] = $datainfo['IOR_RH2C2'];
        $currOdds[$datainfo['GID']]['ior_H3C3'] = $datainfo['IOR_RH3C3'];
        $currOdds[$datainfo['GID']]['ior_H4C4'] = $datainfo['IOR_RH4C4'];
        $currOdds[$datainfo['GID']]['ior_OVH']  = $datainfo['IOR_ROVH'];
        $currOdds[$datainfo['GID']]['ior_H0C1'] = $datainfo['IOR_RH0C1'];
        $currOdds[$datainfo['GID']]['ior_H0C2'] = $datainfo['IOR_RH0C2'];
        $currOdds[$datainfo['GID']]['ior_H1C2'] = $datainfo['IOR_RH1C2'];
        $currOdds[$datainfo['GID']]['ior_H0C3'] = $datainfo['IOR_RH0C3'];
        $currOdds[$datainfo['GID']]['ior_H1C3'] = $datainfo['IOR_RH1C3'];
        $currOdds[$datainfo['GID']]['ior_H2C3'] = $datainfo['IOR_RH2C3'];
        $currOdds[$datainfo['GID']]['ior_H0C4'] = $datainfo['IOR_RH0C4'];
        $currOdds[$datainfo['GID']]['ior_H1C4'] = $datainfo['IOR_RH1C4'];
        $currOdds[$datainfo['GID']]['ior_H2C4'] = $datainfo['IOR_RH2C4'];
        $currOdds[$datainfo['GID']]['ior_H3C4'] = $datainfo['IOR_RH3C4'];
	}
	if($LastOddsArr!=$currOdds){
        $redisObj->setOne('FT_PD_LastOdds_'.$oddchange,json_encode($currOdds));
	}
	$reBallCountCur = $cou;
    $listTitle="滚球足球：波胆";
    $leagueNameCur='';
	break;
case "hpd":
	$page_size=60;
	$offset=$page_no*60;
	$resulTotal=getTodayMatches("TODAY_FT_HPD");
	$cou_num=count($resulTotal);
	$page_count=ceil($cou_num/$page_size);
	$resultArr=array_slice($resulTotal,$offset,$num);
	$cou=count($resultArr);
    $today_bet_floatright ='today_bet_floatright_pd' ;
    $box_pd ='box_pd' ;
	echo "parent.t_page=$page_count;\n";
	echo "parent.gamount=$cou;\n";
	foreach($resultArr as $key=>$row){
		//echo "parent.GameFT[$K]=new Array('$row[MID]','$date<br>$row[M_Time]','$row[M_League]','$row[MB_MID]','$row[TG_MID]','$row[MB_Team]<font color=gray> - [$Order_1st_Half]</font>','$row[TG_Team]<font color=gray> - [$Order_1st_Half]</font>','$row[ShowTypeR]','$row[MB1TG0H]','$row[MB2TG0H]','$row[MB2TG1H]','$row[MB3TG0H]','$row[MB3TG1H]','$row[MB3TG2H]','$row[MB4TG0H]','$row[MB4TG1H]','$row[MB4TG2H]','$row[MB4TG3H]','$row[MB0TG0H]','$row[MB1TG1H]','$row[MB2TG2H]','$row[MB3TG3H]','$row[MB4TG4H]','$row[UP5H]','$row[MB0TG1H]','$row[MB0TG2H]','$row[MB1TG2H]','$row[MB0TG3H]','$row[MB1TG3H]','$row[MB2TG3H]','$row[MB0TG4H]','$row[MB1TG4H]','$row[MB2TG4H]','$row[MB3TG4H]');\n";
        $pos = strpos($datainfo[2],'电竞足球');
        $pos_zh_tw = strpos($datainfo[2],'電競足球');
        if ($pos === false){}
        else{
            if ($mem_djft_off == 'off'){
                continue;
            }
        }
        if ($pos_zh_tw === false){}
        else{
            if ($mem_djft_off == 'off'){
                continue;
            }
        }
		$newDataArray[$row[MID]]['gid']=$row[MID];
		$newDataArray[$row[MID]]['datetime']=$date."<br>".$row[M_Time];
		$newDataArray[$row[MID]]['datetimelove']=$date."<br>".$row[M_Time];
		$newDataArray[$row[MID]]['dategh']=$date.$row[MB_MID];
		$newDataArray[$row[MID]]['league']=$row[M_League];
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
		$K=$K+1;	
	}
	$reBallCountCur = $cou;
    $listTitle="今日足球：波胆";
    $leagueNameCur='';
	break;
case "hrpd"://半场滚球波胆	
	$reBallCountCur = 0;
	$page_size=60;
    $today_bet_floatright ='today_bet_floatright_pd' ;
    $box_pd ='box_pd' ;
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
	$LastOdds = '';
	$currOdds = array();
	$LastOdds = $redisObj->getSimpleOne('FT_HPD_LastOdds_'.$oddchange);
	$LastOddsArr = json_decode($LastOdds,true);
	for($i=0;$i<$cou;$i++){
		$messages=$matches[$i];
		$messages=str_replace(");",")",$messages);
		$messages=str_replace("cha(9)","",$messages);
		$datainfo=eval("return $messages;");
		$midNew = $datainfo[0]-1;$pos = strpos($datainfo[2],'电竞足球');
        $pos_zh_tw = strpos($datainfo[2],'電競足球');
        if ($pos === false){}
        else{
            if ($mem_djft_off == 'off'){
                continue;
            }
        }
        if ($pos_zh_tw === false){}
        else{
            if ($mem_djft_off == 'off'){
                continue;
            }
        }
		/*
		$opensql = "select league,Open,gnum_h,gnum_c,team_h,team_c from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where  MID=$midNew and `Type`='FT' and `Cancel`=0";
		$openresult = mysqli_query($dbMasterLink,$opensql);
	    $openrow=mysqli_fetch_assoc($openresult);
	    */
	    /*echo '<pre>';
	    print_r($openrow);
	    echo '<br/>';
	    echo '<pre>';
	    print_r($datainfo);
	    echo '<br/>';*/
	    
	    //if ($openrow['Open']==1){
	    	$newDataArray[$midNew]['gid']=$midNew;
			$newDataArray[$midNew]['datetime']=$datainfo[43];
			$newDataArray[$midNew]['datetimelove']=$datainfo[43];
			$newDataArray[$midNew]['dategh']=$date.$datainfo[3];
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
	    	//echo "parent.GameFT[$K]=new Array('$midNew','$datainfo[43]','$openrow[M_League]','$openrow[MB_MID]','$openrow[TG_MID]','$openrow[MB_Team]','$openrow[TG_Team]','$datainfo[7]','$datainfoA[8]','$datainfoA[9]','$datainfoA[10]','$datainfoA[11]','$datainfoA[12]','$datainfoA[13]','$datainfoA[14]','$datainfoA[15]','$datainfoA[16]','$datainfoA[17]','$datainfoA[18]','$datainfoA[19]','$datainfoA[20]','$datainfoA[21]','$datainfoA[22]','$datainfoA[23]','$datainfoA[24]','$datainfoA[25]','$datainfoA[26]','$datainfoA[27]','$datainfoA[28]','$datainfoA[29]','$datainfoA[30]','$datainfoA[31]','$datainfoA[32]','$datainfoA[33]','$datainfo[34]');\n";
			$K=$K+1;
		//}
		
		if(isset($LastOddsArr[$datainfo[0]])){
				$oddsBackground[$midNew]['ior_H1C0'] = $LastOddsArr[$datainfo[0]]['ior_H1C0']==$datainfo[8] ? 0 : 1;
				$oddsBackground[$midNew]['ior_H2C0'] = $LastOddsArr[$datainfo[0]]['ior_H2C0']==$datainfo[9] ? 0 : 1;
				$oddsBackground[$midNew]['ior_H2C1'] = $LastOddsArr[$datainfo[0]]['ior_H2C1']==$datainfo[10] ? 0 : 1;
				$oddsBackground[$midNew]['ior_H3C0'] = $LastOddsArr[$datainfo[0]]['ior_H3C0']==$datainfo[11] ? 0 : 1;
				$oddsBackground[$midNew]['ior_H3C1'] = $LastOddsArr[$datainfo[0]]['ior_H3C1']==$datainfo[12] ? 0 : 1;
				$oddsBackground[$midNew]['ior_H3C2'] = $LastOddsArr[$datainfo[0]]['ior_H3C2']==$datainfo[13] ? 0 : 1;
				$oddsBackground[$midNew]['ior_H4C0'] = $LastOddsArr[$datainfo[0]]['ior_H4C0']==$datainfo[14] ? 0 : 1;
				$oddsBackground[$midNew]['ior_H4C1'] = $LastOddsArr[$datainfo[0]]['ior_H4C1']==$datainfo[15] ? 0 : 1;
				$oddsBackground[$midNew]['ior_H4C2'] = $LastOddsArr[$datainfo[0]]['ior_H4C2']==$datainfo[16] ? 0 : 1;
				$oddsBackground[$midNew]['ior_H4C3'] = $LastOddsArr[$datainfo[0]]['ior_H4C3']==$datainfo[17] ? 0 : 1;
				$oddsBackground[$midNew]['ior_H0C0'] = $LastOddsArr[$datainfo[0]]['ior_H0C0']==$datainfo[18] ? 0 : 1;
				$oddsBackground[$midNew]['ior_H1C1'] = $LastOddsArr[$datainfo[0]]['ior_H1C1']==$datainfo[19] ? 0 : 1;
				$oddsBackground[$midNew]['ior_H2C2'] = $LastOddsArr[$datainfo[0]]['ior_H2C2']==$datainfo[20] ? 0 : 1;
				$oddsBackground[$midNew]['ior_H3C3'] = $LastOddsArr[$datainfo[0]]['ior_H3C3']==$datainfo[21] ? 0 : 1;
				$oddsBackground[$midNew]['ior_H4C4'] = $LastOddsArr[$datainfo[0]]['ior_H4C4']==$datainfo[22] ? 0 : 1;
				$oddsBackground[$midNew]['ior_OVH']  = $LastOddsArr[$datainfo[0]]['ior_OVH'] ==$datainfo[23] ? 0 : 1;
			    $oddsBackground[$midNew]['ior_H0C1'] = $LastOddsArr[$datainfo[0]]['ior_H0C1']==$datainfo[24] ? 0 : 1;
			    $oddsBackground[$midNew]['ior_H0C2'] = $LastOddsArr[$datainfo[0]]['ior_H0C2']==$datainfo[25] ? 0 : 1;
			    $oddsBackground[$midNew]['ior_H1C2'] = $LastOddsArr[$datainfo[0]]['ior_H1C2']==$datainfo[26] ? 0 : 1;
			    $oddsBackground[$midNew]['ior_H0C3'] = $LastOddsArr[$datainfo[0]]['ior_H0C3']==$datainfo[12] ? 0 : 1;
			    $oddsBackground[$midNew]['ior_H1C3'] = $LastOddsArr[$datainfo[0]]['ior_H1C3']==$datainfo[28] ? 0 : 1;
			    $oddsBackground[$midNew]['ior_H2C3'] = $LastOddsArr[$datainfo[0]]['ior_H2C3']==$datainfo[29] ? 0 : 1;
			    $oddsBackground[$midNew]['ior_H0C4'] = $LastOddsArr[$datainfo[0]]['ior_H0C4']==$datainfo[30] ? 0 : 1;
			    $oddsBackground[$midNew]['ior_H1C4'] = $LastOddsArr[$datainfo[0]]['ior_H1C4']==$datainfo[31] ? 0 : 1;
			    $oddsBackground[$midNew]['ior_H2C4'] = $LastOddsArr[$datainfo[0]]['ior_H2C4']==$datainfo[32] ? 0 : 1;
			    $oddsBackground[$midNew]['ior_H3C4'] = $LastOddsArr[$datainfo[0]]['ior_H3C4']==$datainfo[33] ? 0 : 1;
			}
				$currOdds[$midNew]['ior_H1C0'] = $datainfo[8];
				$currOdds[$midNew]['ior_H2C0'] = $datainfo[9];
				$currOdds[$midNew]['ior_H2C1'] = $datainfo[10];
				$currOdds[$midNew]['ior_H3C0'] = $datainfo[11];
				$currOdds[$midNew]['ior_H3C1'] = $datainfo[12];
				$currOdds[$midNew]['ior_H3C2'] = $datainfo[13];
				$currOdds[$midNew]['ior_H4C0'] = $datainfo[14];
				$currOdds[$midNew]['ior_H4C1'] = $datainfo[15];
				$currOdds[$midNew]['ior_H4C2'] = $datainfo[16];
				$currOdds[$midNew]['ior_H4C3'] = $datainfo[17];
				$currOdds[$midNew]['ior_H0C0'] = $datainfo[18];
				$currOdds[$midNew]['ior_H1C1'] = $datainfo[19];
				$currOdds[$midNew]['ior_H2C2'] = $datainfo[20];
				$currOdds[$midNew]['ior_H3C3'] = $datainfo[21];
				$currOdds[$midNew]['ior_H4C4'] = $datainfo[22];
				$currOdds[$midNew]['ior_OVH']  = $datainfo[23];
			    $currOdds[$midNew]['ior_H0C1'] = $datainfo[24];
			    $currOdds[$midNew]['ior_H0C2'] = $datainfo[25];
			    $currOdds[$midNew]['ior_H1C2'] = $datainfo[26];
			    $currOdds[$midNew]['ior_H0C3'] = $datainfo[12];
			    $currOdds[$midNew]['ior_H1C3'] = $datainfo[28];
			    $currOdds[$midNew]['ior_H2C3'] = $datainfo[29];
			    $currOdds[$midNew]['ior_H0C4'] = $datainfo[30];
			    $currOdds[$midNew]['ior_H1C4'] = $datainfo[31];
			    $currOdds[$midNew]['ior_H2C4'] = $datainfo[32];
			    $currOdds[$midNew]['ior_H3C4'] = $datainfo[33];	
	}
	if($LastOddsArr!=$currOdds){
		$redisObj->setOne('FT_HPD_LastOdds_'.$oddchange,json_encode($currOdds));
	}
	$reBallCountCur = $cou;
    $listTitle="滚球足球：波胆";
    $leagueNameCur='';
	break;
case "t"://总入球
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
        $pos = strpos($row['M_League'],'电竞足球');
        $pos_zh_tw = strpos($row['M_League'],'電競足球');
        if ($pos === false){}
        else{
            if ($mem_djft_off == 'off'){
                continue;
            }
        }
        if ($pos_zh_tw === false){}
        else{
            if ($mem_djft_off == 'off'){
                continue;
            }
        }
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
	$LastOdds = '';
	$currOdds = array();
	$LastOdds = $redisObj->getSimpleOne('FT_T_LastOdds_'.$oddchange);
	$LastOddsArr = json_decode($LastOdds,true);
	for($i=0;$i<$cou;$i++){
		$messages=$matches[$i];
		$messages=str_replace(");",")",$messages);
		$messages=str_replace("cha(9)","",$messages);
		$datainfo=eval("return $messages;");
        $pos = strpos($datainfo[2],'电竞足球');
        $pos_zh_tw = strpos($datainfo[2],'電競足球');
        if ($pos === false){}
        else{
            if ($mem_djft_off == 'off'){
                continue;
            }
        }
        if ($pos_zh_tw === false){}
        else{
            if ($mem_djft_off == 'off'){
                continue;
            }
        }
		$datainfoA[8]=change_rate($open,$datainfo[8]);
		$datainfoA[9]=change_rate($open,$datainfo[9]);
		$datainfoA[10]=change_rate($open,$datainfo[10]);
		$datainfoA[11]=change_rate($open,$datainfo[11]);
		/*
		$opensql = "select MID,Open,M_League,MB_MID,TG_MID,TG_Team,MB_Team,ShowTypeR from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where  MID='$datainfo[0]' and `Type`='FT' and `Cancel`=0";
		$openresult = mysqli_query($dbMasterLink,$opensql);
	    $openrow=mysqli_fetch_assoc($openresult);
	    */
	    //if($openrow['Open']==1){
	    	$newDataArray[$datainfo[0]]['gid']=$datainfo[0];
			$newDataArray[$datainfo[0]]['datetime']=$datainfo[22];
			$newDataArray[$datainfo[0]]['datetimelove']=$datainfo[22];
			$newDataArray[$datainfo[0]]['dategh']=$date.$datainfo[3];
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
		//}
			if(isset($LastOddsArr[$datainfo[0]])){
				$oddsBackground[$datainfo[0]]['ior_T01'] = $LastOddsArr[$datainfo[0]]['ior_T01']==$datainfo[9] ? 0 : 1;
				$oddsBackground[$datainfo[0]]['ior_T23'] = $LastOddsArr[$datainfo[0]]['ior_T23']==$datainfo[10] ? 0 : 1;
				$oddsBackground[$datainfo[0]]['ior_T46'] = $LastOddsArr[$datainfo[0]]['ior_T46']==$datainfo[13] ? 0 : 1;
				$oddsBackground[$datainfo[0]]['ior_OVER'] = $LastOddsArr[$datainfo[0]]['ior_OVER']==$datainfo[14] ? 0 : 1;
			}
				$currOdds[$datainfo[0]]['ior_T01']=$datainfo[9];
				$currOdds[$datainfo[0]]['ior_T23']=$datainfo[10];
				$currOdds[$datainfo[0]]['ior_T46']=$datainfo[13];
				$currOdds[$datainfo[0]]['ior_OVER']=$datainfo[14];
	}
	if($LastOdds!=$currOdds){
		$setResult=$redisObj->setOne('FT_T_LastOdds_'.$oddchange,json_encode($currOdds));
	}
	$listTitle="滚球足球：全场总入球数";
    $reBallCountCur = $cou;
	$leagueNameCur='';
	break;	
case "f":  //半场/全场
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
        $pos = strpos($row['M_League'],'电竞足球');
        $pos_zh_tw = strpos($row['M_League'],'電競足球');
        if ($pos === false){}
        else{
            if ($mem_djft_off == 'off'){
                continue;
            }
        }
        if ($pos_zh_tw === false){}
        else{
            if ($mem_djft_off == 'off'){
                continue;
            }
        }
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
	$LastOdds = '';
	$currOdds = array();
	$LastOdds = $redisObj->getSimpleOne('FT_F_LastOdds_'.$oddchange);
	$LastOddsArr = json_decode($LastOdds,true);
	for($i=0;$i<$cou;$i++){
		$messages=$matches[$i];
		$messages=str_replace(");",")",$messages);
		$messages=str_replace("cha(9)","",$messages);
		$datainfo=eval("return $messages;");
        $pos = strpos($datainfo[2],'电竞足球');
        $pos_zh_tw = strpos($datainfo[2],'電競足球');
        if ($pos === false){}
        else{
            if ($mem_djft_off == 'off'){
                continue;
            }
        }
        if ($pos_zh_tw === false){}
        else{
            if ($mem_djft_off == 'off'){
                continue;
            }
        }
		$datainfoA[8]=change_rate($open,$datainfo[8]);
		$datainfoA[9]=change_rate($open,$datainfo[9]);
		$datainfoA[10]=change_rate($open,$datainfo[10]);
		$datainfoA[11]=change_rate($open,$datainfo[11]);
		$datainfoA[12]=change_rate($open,$datainfo[12]);
		$datainfoA[13]=change_rate($open,$datainfo[13]);
		$datainfoA[14]=change_rate($open,$datainfo[14]);
		$datainfoA[15]=change_rate($open,$datainfo[15]);
		$datainfoA[16]=change_rate($open,$datainfo[16]);
		/*
		$opensql = "select Open,M_League,MB_MID,TG_MID,MB_Team,TG_Team,ShowTypeR from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where  MID='$datainfo[0]' and `Type`='FT' and `Cancel`=0";
		$openresult = mysqli_query($dbMasterLink,$opensql);
	    $openrow=mysqli_fetch_assoc($openresult);
	    */
	    
	    //if ($openrow['Open']==1){
			$newDataArray[$datainfo[0]]['gid']=$datainfo[0];
			$newDataArray[$datainfo[0]]['datetime']=$datainfo[27];
			//$newDataArray[$datainfo[0]]['datetimelove']=$date."<br>".$datainfo[27];
			$newDataArray[$datainfo[0]]['datetimelove']=$datainfo[27];
			$newDataArray[$datainfo[0]]['dategh']=$date.$datainfo[3];
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
		//}
		if(isset($LastOddsArr[$datainfo[0]])){
				$oddsBackground[$datainfo[0]]['ior_FHH'] = $LastOddsArr[$datainfo[0]]['ior_FHH']==$datainfoA[8] ? 0 : 1;
				$oddsBackground[$datainfo[0]]['ior_FHN'] = $LastOddsArr[$datainfo[0]]['ior_FHN']==$datainfoA[9] ? 0 : 1;
				$oddsBackground[$datainfo[0]]['ior_FHC'] = $LastOddsArr[$datainfo[0]]['ior_FHC']==$datainfoA[10] ? 0 : 1;
				$oddsBackground[$datainfo[0]]['ior_FNH'] = $LastOddsArr[$datainfo[0]]['ior_FNH']==$datainfoA[11] ? 0 : 1;
				$oddsBackground[$datainfo[0]]['ior_FNN'] = $LastOddsArr[$datainfo[0]]['ior_FNN']==$datainfoA[12] ? 0 : 1;
				$oddsBackground[$datainfo[0]]['ior_FNC'] = $LastOddsArr[$datainfo[0]]['ior_FNC']==$datainfoA[13] ? 0 : 1;
				$oddsBackground[$datainfo[0]]['ior_FCH'] = $LastOddsArr[$datainfo[0]]['ior_FCH']==$datainfoA[14] ? 0 : 1;
				$oddsBackground[$datainfo[0]]['ior_FCN'] = $LastOddsArr[$datainfo[0]]['ior_FCN']==$datainfoA[15] ? 0 : 1;
				$oddsBackground[$datainfo[0]]['ior_FCC'] = $LastOddsArr[$datainfo[0]]['ior_FCC']==$datainfoA[16] ? 0 : 1;
			}
				$currOdds[$datainfo[0]]['ior_FHH'] = $datainfoA[8];
				$currOdds[$datainfo[0]]['ior_FHN'] = $datainfoA[9];
				$currOdds[$datainfo[0]]['ior_FHC'] = $datainfoA[10];
				$currOdds[$datainfo[0]]['ior_FNH'] = $datainfoA[11];
				$currOdds[$datainfo[0]]['ior_FNN'] = $datainfoA[12];
				$currOdds[$datainfo[0]]['ior_FNC'] = $datainfoA[13];
				$currOdds[$datainfo[0]]['ior_FCH'] = $datainfoA[14];
				$currOdds[$datainfo[0]]['ior_FCN'] = $datainfoA[15];
				$currOdds[$datainfo[0]]['ior_FCC'] = $datainfoA[16];
	}
	if($LastOdds!=$currOdds){
		$setResult=$redisObj->setOne('FT_F_LastOdds_'.$oddchange,json_encode($currOdds));
	}
	$reBallCountCur = $cou;
	$listTitle="足球滚球:半场 /全场";
	$leagueNameCur='';
	break;
case "p3": // 综合过关
                $resulTotal=getP3Matches();
                $cou=count($resulTotal);
                echo "parent.retime=0;\n";
                echo "parent.game_more=1;\n";
                echo "parent.str_more='$more';\n";
                echo "parent.gamount=$cou;\n";
                $page_size=60;
                $page_count=intval($cou/$page_size);
                echo "parent.t_page=$page_count;";
                // 综合过关  RATIO_HMH ior_HPMH 独赢主队 ， RATIO_HMC ior_HPMC 独赢客队 ，RATIO_HMN ior_HPMN 独赢和局
                
                foreach($resulTotal as $key=>$row){
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
                    $pos = strpos($row['M_League'],'电竞足球');
                    $pos_zh_tw = strpos($row['M_League'],'電競足球');
                    if ($pos === false){}
                    else{
                        if ($mem_djft_off == 'off'){
                            continue;
                        }
                    }
                    if ($pos_zh_tw === false){}
                    else{
                        if ($mem_djft_off == 'off'){
                            continue;
                        }
                    }
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

//重置滚球数量
window.defaultStatus="Wellcome.................";
</script>
<link rel="stylesheet" href="/style/member/mem_body_ft.css?v=<?php echo AUTOVER; ?>" type="text/css" media="screen">
</head>
<body i1d="MFT" class="bodyset FTR body_browse_set" onload="onLoad();">
<!-- 加载层 -->
<!-- <div id="controlscroll"><table border="0" cellspacing="0" cellpadding="0" class="loadBox"><tr><td><!--loading--><!-- </td></tr></table></div>-->
<?php 
	if($rtype=='p3'){
		$date1=date('Y-m-d',time()+24*60*60);
		$date2=date('Y-m-d',time()+2*24*60*60);
		$date3=date('Y-m-d',time()+3*24*60*60);
		$date4=date('Y-m-d',time()+4*24*60*60);
		$date5=date('Y-m-d',time()+5*24*60*60);
		$date6=date('Y-m-d',time()+6*24*60*60);
		$date7=date('Y-m-d',time()+7*24*60*60);
		$date8=date('Y-m-d',time()+8*24*60*60);
		$date9=date('Y-m-d',time()+9*24*60*60);	
	
		echo "<div class='div_date_title'><span id='show_date_opt'>";
		if($rtype=="p3" && $showtype!="future"){	
			$todayDate=date('Y-m-d',time());
			echo "<span value='$todayDate' onclick='chg_gdate(this)' class='".($g_date==$todayDate?'choose_select':'')."'>今日</span>";
		}
		for($datei=1;$datei<10;$datei++){
			$dateNowValue=date('Y-m-d',time()+$datei*24*60*60);
			$dateNowStr=date('m'.'月'.'d'.'日',time()+$datei*24*60*60);
			echo "<span value='$dateNowValue' onclick='chg_gdate(this)' class='".($g_date==$dateNowValue?'choose_select':'')."'>$dateNowStr</span>";
		}
		echo "<span value='ALL'        onclick='chg_gdate(this)' class='".($g_date=='ALL'?'choose_select':'')."'>全部</span>";
		echo "</span></div>";
	}
?>
<div class="ss_table" style="display: inline-block">
    <table border="0" cellpadding="0" cellspacing="0" id="myTable">
	<tbody>
		<tr>
			<td>
			 <table border="0" cellpadding="0" cellspacing="0" id="box" class="<?php echo $box_pd?>">
				<tbody>
					<tr>
						<td class="top">
							<h1 class="top_h1">
                                <em><?php echo $listTitle; ?></em>
                                <?php
                                  if($rtype=='pd' || $rtype=='hpd'|| $rtype=='rpd' || $rtype=='hrpd'){ // 波胆才有
                                      switch ($rtype){
                                          case 'pd': // 今日赛事全场
                                              $pd_tip = 'pd' ;
                                              $hpd_tip = 'hpd' ;
                                              break;
                                          case 'hpd': // 今日赛事半场
                                              $pd_tip = 'pd' ;
                                              $hpd_tip = 'hpd' ;
                                              $select = 'selected' ;
                                              break;
                                          case 'rpd': //  滚球全场
                                              $pd_tip = 'rpd' ;
                                              $hpd_tip = 'hrpd' ;
                                              break;
                                          case 'hrpd': // 滚球半场
                                              $pd_tip = 'rpd' ;
                                              $hpd_tip = 'hrpd' ;
                                              $select = 'selected' ;
                                              break;
                                      }

                                      echo ' <select id="selwtype" onChange="chg_wtype(selwtype.value);">
                                                <option value="'.$pd_tip.'" >'.$U_21.'</option>
                                                <option value="'.$hpd_tip.'" '.$select.'>'.$U_22.'</option>
                                             </select>' ;
                                  }

                                  if($rtype=='pd' || $rtype=='hpd'|| $rtype=='rpd' || $rtype=='rhpd' || $rtype=='f'|| $rtype=='p3'){
                                      echo '<span class="maxbet">'.$U_11.'  ： RMB 1,000,000.00</span>' ;
                                  }
                                ?>

                            </h1>
							<div id="skin" class="zoomChange">字体显示：<a id="skin_0" data-val="1" class="zoom zoomSmaller" href="javascript:;" title="点击切换原始字体">小</a><a id="skin_1" data-val="1.2" class="zoom zoomMed" href="javascript:;" title="点击切换中号字体">中</a><a id="skin_2" data-val="1.35" class="zoom zoomBigger" href="javascript:;" title="点击切换大号字体">大</a></div>
						</td>
					</tr>
					<tr>
						<td class="mem">
						<h2>
						<table width="100%" border="0" cellpadding="0" cellspacing="0" id="fav_bar">
							<tbody>
								<tr>
									<td id="page_no">
										<span id="pg_txt">
											
										</span>
										<div class="search_box">
											<input type="text" id="seachtext" placeholder="输入关键字查询" value="<?php echo $leaname;?>" class="select_btn">
											<input type="button" id="btnSearch" value="搜索" class="seach_submit" onclick="seaGameList()">
										</div>
									</td>
									<td id="tool_td"><!-- 滚球 -->
										<table border="0" cellspacing="0" cellpadding="0"
											class="tool_box">
											<tbody>
												<tr>
													<td id="fav_btn">
														<div id="fav_num" title="清空" onclick="chkDelAllShowLoveI();" style="display: none;"><!--我的最爱场数--><span id="live_num"></span></div>
														<div id="showNull" title="无资料" class="fav_null" style="display: block;"></div>
														<div id="showAll" title="所有赛事" onclick="showAllGame('FT');" style="display: none;" class="fav_on"></div>
														<div id="showMy" title="我的最爱" onclick="showMyLove('FT');" class="fav_out" style="display: none;"></div>
													</td>
													<td class="refresh_btn" id="refresh_btn" onclick="this.className='refresh_on';"><!--秒数更新-->
														<div onclick="javascript:reload_var()"><font id="refreshTime">刷新</font></div>
													</td>
													<td class="leg_btn">
														<div onclick="javascript:chg_league();" id="sel_league">选择联赛(<span id="str_num">全部</span>)</div>
													</td>
													<td class="OrderType" id="Ordertype">
														<select id="myoddType" onchange="chg_odd_type()">
															<option value="H" selected="">香港盘</option>
														</select>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
						</h2>
						<!-- 资料显示的layer -->
						<div id="showtable">
							<table id="game_table" cellspacing="0" cellpadding="0" class="game">
								<tbody>
									<?php
									if(count($newDataArray)==0){
										echo "<tr><td colspan=20 class='no_game'>您选择的项目暂时没有赛事。请修改您的选项或迟些再返回。</td></tr>";
									}else{
										switch ($rtype){
											case "r":	include "body_m_r_ou_eo.php";break;
											case "re":	include "body_rb_m_r_ou_eo.php";break;
											case "pd":	include "body_pd.php";break;
											case "rpd":	include "body_rpd.php";break;
											case "hpd":	include "body_hpd.php";break;
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
					<tr>
						<td id="foot"><b>&nbsp;</b></td>
					</tr>
				</tbody>
			</table>
				<center><!--下方刷新钮--><div id="refresh_down" class="refresh_M_btn" onclick="this.className='refresh_M_on';javascript:reload_var()"><span>刷新</span></div></center>
			</td>
		</tr>
	</tbody>
</table>
</div>
<!-- 原来的显示更多玩法 -->
<div class="more" id="more_window" name="more_window" style="position:absolute; display:none; ">
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

<!-- 2018 新增 右侧游戏 -->
<div class="today_bet_floatright <?php echo $today_bet_floatright?>" >
    <!-- <iframe id="live" name="live" src="<?php echo BROWSER_IP?>/app/member/live/live.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>"></iframe> -->
    <a href="javascript:;" class="today_bet_refresh" onClick="javascript:reload_var()"></a>
    <a title="足球滚球" class="today_bet_football_move" href="javascript:parent.parent.header.chg_second_tip(this,'rb','<?php echo $uid?>','FT');parent.parent.header.chg_button_bg('FT','rb');parent.parent.header.chg_index(this,' ','<?php echo BROWSER_IP?>/app/member/FT_browse/index.php?rtype=re&uid=<?php echo $uid?>&langx=zh-cn&mtype=4&showtype=<?php echo  $showtype ?>',parent.FT_lid_type,'SI2','rb');" ></a>
    <a title="足球赛事" style="display: none" class="today_bet_football" href="javascript:parent.parent.header.chg_button_bg('FT','<?php echo $Mtype ?>');parent.parent.header.chg_index(this,' ','<?php echo BROWSER_IP?>/app/member/FT_<?php echo ($showtype=='future')?"future":"browse" ?>/index.php?rtype=r&uid=<?php echo $uid?>&langx=zh-cn&mtype=4&showtype=<?php echo  $showtype ?>',parent.FT_lid_type,'SI2');"></a>
    <?php
    if(strpos($_SESSION['gameSwitch'],'|')>0){
        $gameArr=explode('|',$_SESSION['gameSwitch']);
    }else{
        if(strlen($_SESSION['gameSwitch'])>0){
            $gameArr[]=$_SESSION['gameSwitch'];
        }else{
            $gameArr=array();
        }
    }
    if(!in_array('BK',$gameArr)){
        ?>
    <a title="篮球赛事" class="today_bet_basketball" href="javascript:parent.parent.header.chg_button_bg('BK','today','BK','<?php echo $uid?>');parent.parent.header.chg_index(this,' ','<?php echo BROWSER_IP?>/app/member/BK_<?php echo ($showtype=='future')?"future":"browse" ?>/index.php?rtype=all&uid=<?php echo $uid?>&langx=zh-cn&mtype=4',parent.BK_lid_type,'SI2');"></a>
    <a title="蓝球滚球" class="today_bet_basketball_move" href="javascript:parent.parent.header.chg_second_tip(this,'rb','<?php echo $uid?>','BK');parent.parent.header.chg_button_bg('BK','rb');parent.parent.header.chg_index(this,' ','<?php echo BROWSER_IP?>/app/member/BK_browse/index.php?rtype=re&uid=<?php echo $uid?>&langx=zh-cn&mtype=4&showtype=<?php echo  $showtype ?>',parent.BK_lid_type,'SI2','rb');" ></a>
    <?php } ?>
    <a title="真人娱乐" href="../zrsx/index_<?php echo TPL_FILE_NAME;?>.php?uid=<?php echo $uid;?>" target="body" class="today_bet_live"></a>
    <a title="电子游艺" href="../games.php?uid=<?php echo $uid;?>" target="_blank" class="today_bet_game"></a>
    <a title="彩票游戏" href="../../../tpl/lottery.php?uid=<?php echo $uid;?>" target="body" class="today_bet_lottery"></a>
</div>

<script type="text/javascript" src="../../../js/jquery.js"></script>
<script type="text/javascript" src="../../../js/common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../../js/layer/layer.js"></script>
<script type="text/javascript" src="../../../js/src/common_body_var.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript">
    // 侧边栏游戏选项处理，在当前游戏中不显示当前游戏
    var g_type = sessionStorage.getItem('g_type') ;
    var m_type = sessionStorage.getItem('m_type') ;
    if(m_type == 'rb'){
        document.getElementsByClassName('today_bet_football_move')[0].style.display='none' ;
        document.getElementsByClassName('today_bet_football')[0].style.display='' ;

    }
    setBodyScroll();
    // 放大直播视频
    function showOpenLive() {
        var url = "../../member/live/live_max.php?langx="+top.langx+"&uid="+top.uid+"&liveid="+top.liveid ;
        top.tvwin = window.open(url,"win","width=805,height=526,top=0,left=0,status=no,toolbar=no,scrollbars=yes,resizable=no,personalbar=no");
    }
</script>
</body>
</html>
<?php
}
?>
