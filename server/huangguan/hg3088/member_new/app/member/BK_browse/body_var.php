<?php
//error_reporting(E_ALL);
//ini_set('display_errors','On');
session_start();
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include "../include/address.mem.php";
echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
require ("../include/config.inc.php");
require ("../include/define_function_list.inc.php");
require ("../include/curl_http.php");

$uid=$_REQUEST['uid'];
$langx=$_SESSION['langx'];
$mtype=$_REQUEST['mtype'];
$rtype=$_REQUEST['rtype'];
// 判断是否维护-单页面维护功能
if ($rtype=='re'){
    checkMaintain('rb');
}else{
    checkMaintain($_REQUEST['showtype']);
}
$league_id=$_REQUEST['league_id'];
$g_date=$_REQUEST['g_date'];
$page_no=$_REQUEST['page_no'];
if ($page_no==''){
    $page_no=0;
}
$leaname = $_REQUEST['leaname'] ; // 搜索赛事
if($leaname=='undefined'){
    $leaname='' ;
}
require ("../include/traditional.$langx.inc.php");

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    setcookie('login_uid','');
    echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
    exit;
}

$oddchange = returnOddsChangsStatus() ;

$open    = $_SESSION['OpenType'];
$memname = $_SESSION['UserName'];

if ($league_id==''){
    $num=60;
}else{
    $num=1024;
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
                if(!in_array(str_replace('\'','',$valArr[3]),$mylovegameArr)){
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

// 会员篮球电竞开关
if(strpos($_SESSION['gameSwitch'],'|')>0){
    $gameArr=explode('|',$_SESSION['gameSwitch']);
}else{
    if(strlen($_SESSION['gameSwitch'])>0){
        $gameArr[]=$_SESSION['gameSwitch'];
    }else{
        $gameArr=array();
    }
}
if(in_array('DJBK',$gameArr)){
    $mem_djbk_off = 'off';
}
?>
<HEAD>
    <TITLE>篮球變數值</TITLE>
    <META http-equiv=Content-Type content="text/html; charset=utf-8">
    <SCRIPT language=JavaScript>
        parent.flash_ior_set='Y';
        parent.minlimit_VAR='';
        parent.maxlimit_VAR='';
        parent.username='<?php echo  $memname?>';
        parent.code='人民幣(RMB)';
        parent.uid='<?php echo  $uid?>';

        parent.ltype='3';
        parent.str_even = '<?php echo  $str_even?>';
        parent.str_submit = '<?php echo  $str_submit?>';
        parent.str_reset = '<?php echo  $str_reset?>';
        parent.g_date = '';
        parent.rtype='<?php echo  $rtype?>';
        parent.sel_lid='<?php echo  $league_id?>';
        parent.langx='<?php echo  $langx?>';

        parent.retime=60 ; // 刷新倒计时

        <?php
        switch ($rtype){
        case "all":
        case "r": // 从滚球标签切换过今日赛事
            $page_size=60;
			$offset=$page_no*$page_size;
			$resulTotal=getTodayMatches("TODAY_BK_M_ROU_EO");
			$cou_num=count($resulTotal);
			$page_count=ceil($cou_num/$page_size);
			$resultArr=array_slice($resulTotal,$offset,$page_size);
			$cou=count($resultArr);
            echo "parent.str_renew = '$second_auto_update';\n";
            echo "parent.t_page=$page_count;\n";
            echo "parent.gamount=$cou;\n";
            foreach($resultArr as $key=>$row){
                // 全场让球单独处理
                $ra_rate=get_other_ioratio(GAME_POSITION,$row["MB_LetB_Rate"],$row["TG_LetB_Rate"],100); // 默认都是香港盘
                $MB_LetB_Rate=$ra_rate[0]; // 主队
                $TG_LetB_Rate=$ra_rate[1]; // 客队
                $ra_rate=get_other_ioratio(GAME_POSITION,$row["MB_Dime_Rate"],$row["TG_Dime_Rate"],100); // 默认都是香港盘
                $MB_Dime_Rate=$ra_rate[0];
                $TG_Dime_Rate=$ra_rate[1];
                $ra_rate=get_other_ioratio(GAME_POSITION,$row["MB_Dime_Rate_H"],$row["MB_Dime_Rate_S_H"],100); // 默认都是香港盘
                $MB_Dime_Rate_H=$ra_rate[0];
                $MB_Dime_Rate_S_H=$ra_rate[1];
                $ra_rate=get_other_ioratio(GAME_POSITION,$row["TG_Dime_Rate_H"],$row["TG_Dime_Rate_S_H"],100); // 默认都是香港盘
                $TG_Dime_Rate_H=$ra_rate[0];
                $TG_Dime_Rate_S_H=$ra_rate[1];
              
                if ($S_Single_Rate==''){
                    $Single='';
                }else{
                    $Single=$o;
                }
                if ($S_Double_Rate==''){
                    $Double='';
                }else{
                    $Double=$e;
                }

                if ($row['M_Type']==1){
                    $Running='<br><font color=red>滚球</font>';
                }else{
                    $Running='';
                }
                
             	if($row['ShowTypeR']=="H"){
					$ratio_mb_str=$row['M_LetB'];
					$ratio_tg_str='';
				}elseif($row['ShowTypeR']=="C"){
					$ratio_mb_str='';
					$ratio_tg_str=$row['M_LetB'];
				}
                
				$row[MB_Team]=str_replace("[Mid]","<font color=\'#005aff\'>[N]</font>",$row[MB_Team]);
			    $row[MB_Team]=str_replace("[中]","<font color=\'#005aff\'>[中]</font>",$row[MB_Team]);
				
                $MID=$row[MID];
                $newDataArray[$MID]['gid']=$row[MID];
				$newDataArray[$MID]['datetime']=$date."<br>".$row[M_Time].$Running;
				$newDataArray[$MID]['dategh']=date('m-d').$row['MB_MID'];
				$newDataArray[$MID]['datetimelove']=$date."<br>".$row[M_Time];
				$newDataArray[$MID]['league']=$row[M_League];
				$newDataArray[$MID]['gnum_h']=$row[MB_MID];
				$newDataArray[$MID]['gnum_c']=$row[TG_MID];
				$newDataArray[$MID]['team_h']=$row[MB_Team];
				$newDataArray[$MID]['team_c']=$row[TG_Team];
				$newDataArray[$MID]['strong']=$row[ShowTypeR];
				$newDataArray[$MID]['ratio' ]=$row[M_LetB];
				$newDataArray[$MID]['ratio_mb_str']=$ratio_mb_str;
				$newDataArray[$MID]['ratio_tg_str']=$ratio_tg_str;
				$newDataArray[$MID]['ior_RH']=change_rate($open,$MB_LetB_Rate);
				$newDataArray[$MID]['ior_RC']=change_rate($open,$TG_LetB_Rate);
				$newDataArray[$MID]['ratio_o']=$row[MB_Dime];
				$newDataArray[$MID]['ratio_u']=$row[TG_Dime];
                $newDataArray[$MID]['ratio_o_str']=!empty($row['MB_Dime'])?"大".str_replace('O','',$row['MB_Dime']):'';
                $newDataArray[$MID]['ratio_u_str']=!empty($row['TG_Dime'])?"小".str_replace('U','',$row['TG_Dime']):'';
				$newDataArray[$MID]['ior_OUH']=change_rate($open,$TG_Dime_Rate);
				$newDataArray[$MID]['ior_OUC']=change_rate($open,$MB_Dime_Rate);
				$newDataArray[$MID]['ior_MH']=change_rate($open,$row["MB_Win_Rate"]); 
				$newDataArray[$MID]['ior_MC']=change_rate($open,$row["TG_Win_Rate"]); 
				$newDataArray[$MID]['str_odd']=$Single;
				$newDataArray[$MID]['str_even']=$Double;
				$newDataArray[$MID]['ior_EOO']=change_rate($open,$row['S_Single_Rate']); // 主队单双
				$newDataArray[$MID]['ior_EOE']=change_rate($open,$row['S_Double_Rate']); // 客队单双
				$newDataArray[$MID]['ratio_ouho']=$row[MB_Dime_H];
				$newDataArray[$MID]['ratio_ouhu']=$row[MB_Dime_S_H];
				$newDataArray[$MID]['ratio_ouho_str']=str_replace('O','',$row['MB_Dime_H']);
				$newDataArray[$MID]['ratio_ouhu_str']=str_replace('U','',$row['MB_Dime_S_H']);
				$newDataArray[$MID]['ior_OUHO']=change_rate($open,$MB_Dime_Rate_H); 
				$newDataArray[$MID]['ior_OUHU']=change_rate($open,$MB_Dime_Rate_S_H); 
				$newDataArray[$MID]['ratio_ouco']=$row[TG_Dime_H];
				$newDataArray[$MID]['ratio_oucu']=$row[TG_Dime_S_H];
				$newDataArray[$MID]['ratio_ouco_str']=str_replace('O','',$row['TG_Dime_H']);
				$newDataArray[$MID]['ratio_oucu_str']=str_replace('U','',$row['TG_Dime_S_H']);
				$newDataArray[$MID]['ior_OUCO']=change_rate($open,$TG_Dime_Rate_H); 
				$newDataArray[$MID]['ior_OUCU']=change_rate($open,$TG_Dime_Rate_S_H);
				$newDataArray[$MID]['center_tv']=$show;
				$newDataArray[$MID]['play']=$row[Eventid];
				$newDataArray[$MID]['gidm']=$row[Hot];
				$newDataArray[$MID]['isMaster']=$row[Play];
				$newDataArray[$MID]['all']=$row[more];
				$newDataArray[$MID]['bet_Url']="gid={$MID}&uid={$uid}&odd_f_type=H&gnum={$row['MB_MID']}&langx={$langx}";
            }
            $listTitle="今日篮球和美式足球";
			$leagueNameCur='';
            break;
        case "re": // 从今日赛事切换到滚球 滚球
            echo "parent.retime=20;\n"; // 倒计时刷新时间
            echo "parent.str_renew = '$second_auto_update';\n";
            $page_size=40;
            $page_count=0;
            $gamecount=0;

//            echo "parent.t_page=0\n";
//            echo "parent.gamount=0;\n";
            $matches=getRunningDataByMethod("BK_M_ROU_EO");
            if(is_array($matches)){
				$cou=sizeof($matches);
			}else{
				$cou=0;
			}
			$mbteamLast='';
			$tgteamLast='';
			$leagueLast='';
			$LastOdds = '';
			$currOdds = array();
			$LastOdds = $redisObj->getSimpleOne('BK_M_ROU_EO_LastOdds_'.$oddchange);
			$LastOddsArr = json_decode($LastOdds,true);
            /*for($i=0;$i<$cou;$i++){
                if($matches[$i]!=''){
                    $messages=$matches[$i];
                    $messages=str_replace(");",")",$messages);
                    $messages=str_replace("cha(9)","",$messages);
                    $datainfo=eval("return $messages;");
                    $MID=$datainfo[0];
                    $datainfos[$MID]=$datainfo;
                    $gamecount ++;
                }
            }*/

            foreach ($matches as $k => $datainfo){
                $datainfos[$datainfo['gid']] =$datainfo;
            }

            // 会员篮球滚球第3节开关
            if(strpos($_SESSION['gameSwitch'],'|')>0){
                $gameArr=explode('|',$_SESSION['gameSwitch']);
            }else{
                if(strlen($_SESSION['gameSwitch'])>0){
                    $gameArr[]=$_SESSION['gameSwitch'];
                }else{
                    $gameArr=array();
                }
            }
            if(in_array('BKQ3',$gameArr)){
                $mem_bkq3_off = 'off';
            }
            $isClosedH1 = in_array('BKH1', $gameArr); // 是否关闭篮球滚球上半场20200111

            //篮球从第三节结束前的三分钟，以及第四节开始时不允许下注，以及下半场倒计时10分钟
            // 若关闭某会员篮球滚球上半场（所有带括号全关掉-20200111）
            // Q1 第一节 Q2 第二节 Q3 第三节 Q4 第四节 H1 上半场 H2 下半场 OT 加时 HT 半场
            foreach ($datainfos as $MID => $datainfo){
                $datainfo[2]=$datainfo['league'];
                $datainfo[5]=$datainfo['team_h'];
                $datainfo[6]=$datainfo['team_c'];
                $datainfo[52]=$datainfo['se_now'];
                $datainfo[56]=$datainfo['LASTTIME'];
                if( (strpos($datainfo[5], '-') !== false and $isClosedH1) ||
                    ($datainfo[52]=='H2' and $datainfo[56]<=1200 and $mem_bkq3_off=='off') ||
                    ($datainfo[52]=='HT' and $datainfo[56]>0 and $datainfo[56]<=1190 and $mem_bkq3_off=='off') ||
                    $datainfo[52]=='Q4' || $datainfo[52]=='OT' ||
                    ($datainfo[52]=='H2' and $datainfo[56]<=600) ||
                    ($datainfo[52] == "Q3" and $datainfo[56]<=180) ||
                    (($datainfo[52]=='Q3' || $datainfo[52]=='Q4' || $datainfo[52]=='H2' || $datainfo[52]=='OT' || $datainfo[52]=='HT') and $mem_bkq3_off=='off')){

//                    $datainfos[$MID]=array();
                    $datainfos[$MID]['strong']='';
                    $datainfos[$MID]['ior_REH']='';
                    $datainfos[$MID]['ior_REC']='';
                    $datainfos[$MID]['ratio_re']='';
                    $datainfos[$MID]['ratio_rouo']='';
                    $datainfos[$MID]['ratio_rouu']='';
                    $datainfos[$MID]['ior_ROUC']='';
                    $datainfos[$MID]['ior_ROUH']='';
                    $datainfos[$MID]['ratio_rouho']='';
                    $datainfos[$MID]['ratio_rouhu']='';
                    $datainfos[$MID]['ior_ROUHO']='';
                    $datainfos[$MID]['ior_ROUHU']='';
                    $datainfos[$MID]['ratio_rouco']='';
                    $datainfos[$MID]['ratio_roucu']='';
                    $datainfos[$MID]['ior_ROUCO']='';
                    $datainfos[$MID]['ior_ROUCU']='';
                    $datainfos[$MID]['MORE']='';

                    // 其他盘口，赔率等投注信息不显示（无视美式足球）
                    if (strpos($datainfo[2],'美式足球')===false){

                        if (isset($datainfos[$MID+7])){
//                            if ($datainfo[2]==$datainfos[$MID+7][2] and $datainfo[5]==$datainfos[$MID+7][5] and $datainfo[6]==$datainfos[$MID+7][6]){
                            if ($datainfo['league']==$datainfos[$MID+7]['league'] and $datainfo['team_h']==$datainfos[$MID+7]['team_h'] and $datainfo['team_c']==$datainfos[$MID+7]['team_c']){
//                                $datainfos[$MID+7]=array();
                                $datainfos[$MID+7]['strong']='';
                                $datainfos[$MID+7]['ior_REH']='';
                                $datainfos[$MID+7]['ior_REC']='';
                                $datainfos[$MID+7]['ratio_re']='';
                                $datainfos[$MID+7]['ratio_rouo']='';
                                $datainfos[$MID+7]['ratio_rouu']='';
                                $datainfos[$MID+7]['ior_ROUC']='';
                                $datainfos[$MID+7]['ior_ROUH']='';
                                $datainfos[$MID+7]['ratio_rouho']='';
                                $datainfos[$MID+7]['ratio_rouhu']='';
                                $datainfos[$MID+7]['ior_ROUHO']='';
                                $datainfos[$MID+7]['ior_ROUHU']='';
                                $datainfos[$MID+7]['ratio_rouco']='';
                                $datainfos[$MID+7]['ratio_roucu']='';
                                $datainfos[$MID+7]['ior_ROUCO']='';
                                $datainfos[$MID+7]['ior_ROUCU']='';
                                $datainfos[$MID+7]['MORE']='';
                            }
                        }
                        if (isset($datainfos[$MID+14])) {
//                            if ($datainfo[2] == $datainfos[$MID + 14][2] and $datainfo[5] == $datainfos[$MID + 14][5] and $datainfo[6] == $datainfos[$MID + 14][6]) {
                            if ($datainfo['league']==$datainfos[$MID+14]['league'] and $datainfo['team_h']==$datainfos[$MID+14]['team_h'] and $datainfo['team_c']==$datainfos[$MID+14]['team_c']){
                             // $datainfos[$MID+14]=array();
                                $datainfos[$MID+14]['strong']='';
                                $datainfos[$MID+14]['ior_REH']='';
                                $datainfos[$MID+14]['ior_REC']='';
                                $datainfos[$MID+14]['ratio_re']='';
                                $datainfos[$MID+14]['ratio_rouo']='';
                                $datainfos[$MID+14]['ratio_rouu']='';
                                $datainfos[$MID+14]['ior_ROUC']='';
                                $datainfos[$MID+14]['ior_ROUH']='';
                                $datainfos[$MID+14]['ratio_rouho']='';
                                $datainfos[$MID+14]['ratio_rouhu']='';
                                $datainfos[$MID+14]['ior_ROUHO']='';
                                $datainfos[$MID+14]['ior_ROUHU']='';
                                $datainfos[$MID+14]['ratio_rouco']='';
                                $datainfos[$MID+14]['ratio_roucu']='';
                                $datainfos[$MID+14]['ior_ROUCO']='';
                                $datainfos[$MID+14]['ior_ROUCU']='';
                                $datainfos[$MID+14]['MORE']='';
                            }
                        }
                    }

                }
            }
            // 篮球滚球盘口默认按照时间排序
            foreach ($datainfos as $key => $match){
                // 转换时间 02-28<br>01:35a  -》  2019-02-28 01:35:00
                // 转换时间 02-28<br>01:35p  -》  2019-02-28 13:35:00
//                $match[100] = str_replace('<br>', ' ', $match[47]); //02-28 01:35a
                $match[100] = $match['datetime']; //02-28 01:35a
                $sAorP = substr($match[100],11); // a 或者 p
                $match[100] = date('Y-m-d H:i:s',strtotime(date('Y-m-d').'-'.substr($match[100],0, -1)));
                if ($sAorP == 'p'){
                    $match[100] = date('Y-m-d H:i:s',strtotime($match[100])+43200);
                }
                $datainfos[$key][100] = $match[100];
            }

//            @error_log('after delete'.date('Y-m-d H:i:s').PHP_EOL, 3, '/tmp/group/body_var.php.log');
//            @error_log(json_encode($datainfos, JSON_UNESCAPED_UNICODE).PHP_EOL, 3, '/tmp/group/body_var.php.log');
            // 滚球到第几节，如果有这一节的盘口就关闭此盘口
            if(in_array(TPL_FILE_NAME, TPL_FILE_NAMES)) {
                foreach ($datainfos as $MID => $datainfo){
                    if ($datainfo['isMaster']!='Y'){continue;}
                    $datainfo[5]=$datainfo['team_h'];
                    $datainfo[6]=$datainfo['team_c'];
                    $datainfo[52]=$datainfo['se_now'];
                    $datainfo[56]=$datainfo['LASTTIME']; //只有主盘口有

                    if (strpos($datainfo['league'],'(4x5分钟)')){ //电竞篮球联赛
                        $BkTypeTime ='300';  //1-4每小节5分钟*60=300s
                    }elseif (strpos($row['M_League'],'美国职业篮球联赛')){ //NBA美国职业篮球联赛
                        $BkTypeTime ='720';  //每小节12分钟  NBA比赛一节12分钟
                    }else {
                        $BkTypeTime ='600';  //每小节10分钟
                    }
                    switch ($datainfo[52]) {    // 只有主盘口
                        case 'Q1':
                            if ($datainfo[56]>0 and $datainfo[56]<$BkTypeTime) {
                                unset($datainfos[$MID + 3]);    // 移除第一节盘口
                            }
                            break;
                        case 'Q2':
                            if ($datainfo[56]>0 and $datainfo[56]<$BkTypeTime) {
                                unset($datainfos[$MID + 1]);    // 移除上半场盘口
                                //unset($datainfos[$MID + 8]);
                                unset($datainfos[$MID + 4]);    // 移除第二节盘口 电竞篮球
                            }
                            break;
                        case 'Q3':
                            if ($datainfo[56]>0 and $datainfo[56]<$BkTypeTime) {
                                unset($datainfos[$MID + 5]);    // 移除第三节盘口
                            }
                            break;
                        default:
                            break;
                    }

                }
            }
//            @error_log(date('Y-m-d H:i:s').PHP_EOL, 3, '/tmp/group/body_var.php.log');
//            @error_log('show'.PHP_EOL, 3, '/tmp/group/body_var.php.log');
//            @error_log(json_encode($datainfos, JSON_UNESCAPED_UNICODE).PHP_EOL, 3, '/tmp/group/body_var.php.log');
//            $datainfos = array_sort($datainfos,0,$type='desc');
//            $datainfos = array_values(array_sort($datainfos,100,$type='asc'));

            foreach ($datainfos as $k => $datainfo){
//                    $M_duration = $datainfo[52].'-'.$datainfo[56]; // 比赛进度 【 Q2-80】 【 第二节-第80分钟】

                $MID=$datainfo['gid'];
                $datainfo[0]=$datainfo['gid'];
                $datainfo[2]=$datainfo['league'];
                $datainfo[3]=$datainfo['gnum_h'];
                $datainfo[4]=$datainfo['gnum_c'];
                $datainfo[5]=$datainfo['team_h'];
                $datainfo[6]=$datainfo['team_c'];
                $datainfo[52]=$datainfo['se_now'];
                $datainfo[53]=$datainfo['SCORE_H'];
                $datainfo[54]=$datainfo['SCORE_C'];
                $datainfo[56]=$datainfo['LASTTIME'];

                    $pos = strpos($datainfo[2],'NBA2K');
                    if ($pos === false){}
                    else{
                        if ($mem_djbk_off == 'off'){
                            continue;
                        }
                    }

                    if ($datainfo[9]<>''){ // 全场让球
                        // 全场让球单独处理
                        $ra_rate=get_other_ioratio(GAME_POSITION,$datainfo[9],$datainfo[10],100); // 默认都是香港盘
                        $datainfo[9]=$ra_rate[0]; // 主队
                        $datainfo[10]=$ra_rate[1]; // 客队
                        $datainfo[9]=change_rate($open,$datainfo[9]);
                        $datainfo[10]=change_rate($open,$datainfo[10]);
                    }
                    if ($datainfo[13]<>''){
                        $ra_rate=get_other_ioratio(GAME_POSITION,$datainfo[13],$datainfo[14],100); // 默认都是香港盘
                        $datainfo[13]=$ra_rate[0]; // 全场大小 大
                        $datainfo[14]=$ra_rate[1]; // 全场大小 小
                        $datainfo[13]=change_rate($open,$datainfo[13]);
                        $datainfo[14]=change_rate($open,$datainfo[14]);
                    }
                    if ($datainfo[37]<>''){
                        $ra_rate=get_other_ioratio(GAME_POSITION,$datainfo[37],$datainfo[38],100); // 默认都是香港盘
                        $datainfo[37]=$ra_rate[0];
                        $datainfo[38]=$ra_rate[1];
                        $datainfo[37]=change_rate($open,$datainfo[37]);
                        $datainfo[38]=change_rate($open,$datainfo[38]);
                    }
                    if ($datainfo[41]<>''){
                        $ra_rate=get_other_ioratio(GAME_POSITION,$datainfo[41],$datainfo[42],100); // 默认都是香港盘
                        $datainfo[41]=$ra_rate[0];
                        $datainfo[42]=$ra_rate[1];
                        $datainfo[41]=change_rate($open,$datainfo[41]);
                        $datainfo[42]=change_rate($open,$datainfo[42]);
                    }
                    if ($datainfo[33]<>''){
                        $datainfo[33]=change_rate($open,$datainfo[33]);
                    }
                    if ($datainfo[34]<>''){
                        $datainfo[34]=change_rate($open,$datainfo[34]);
                    }
                    if ($datainfo[28]<>''){
                        $datainfo[28]=change_rate($open,$datainfo[28]);
                    }
                    if ($datainfo[29]<>''){
                        $datainfo[29]=change_rate($open,$datainfo[29]);
                    }
                    if ($datainfo[30]<>''){
                        $datainfo[30]=change_rate($open,$datainfo[30]);
                    }
                    // $datainfo[52] 球队名称 Q1-Q4 第一节-第四节，H1 上半场，H2 下半场 ，OT 加时，HT 半场
                    $team_active = $team_time = '';
                    // 优久乐数据判断处理（篮球滚球没有比分和时间，另行处理）
                    $match_time=explode("^", $datainfo[48]);
                    if($match_time[0] == 196){
                        $team_active = '';
                        $team_time = $match_time[1];
                        $datainfo_score = $match_time[2];
                    }else {
//                        $mbTeamArr = explode('-', $datainfo[5]);
//                        preg_match('/\d+/', $mbTeamArr[1], $mbTeamArrList);
//                        if ($mbTeamArrList[0] == 2) {
//                            $team_active = '第二节';
//                            $newDataArray[$MID]['headShow'] = 0;
//                        } elseif ($mbTeamArrList[0] == 3) {
//                            $team_active = '第三节';
//                            $newDataArray[$MID]['headShow'] = 0;
//                        } elseif ($mbTeamArrList[0] == 4) {
//                            $team_active = '第四节';
//                            $newDataArray[$MID]['headShow'] = 0;
//                        } else {
                            switch ($datainfo[52]) {
                                case 'Q1':
                                    $team_active = '第一节';
                                    break;
                                case 'Q2':
                                    $team_active = '第二节';
                                    break;
                                case 'Q3':
                                    $team_active = '第三节';
                                    break;
                                case 'Q4':
                                    $team_active = '第四节';
                                    break;
                                case 'H1':
                                    $team_active = '上半场';
                                    break;
                                case 'H2':
                                    $team_active = '下半场';
                                    break;
                                case 'OT':
                                    $team_active = '加时';
                                    break;
                                case 'HT':
                                    $team_active = '半场';
                                    break;
                            }
//                        }
                        if ($datainfo[56] && $datainfo[56] > 0) { // 转化时间
                            $team_hour = floor($datainfo[56] / 3600); // 小时不要
                            $team_minute = floor(($datainfo[56] - 3600 * $team_hour) / 60);
                            $team_second = floor((($datainfo[56] - 3600 * $team_hour) - 60 * $team_minute) % 60);
                            $team_time = ($team_minute > 9 ? $team_minute : "0" . $team_minute) . ':' . ($team_second > 9 ? $team_second : "0" . $team_second);
                        }
                        $datainfo_score = " $datainfo[53]-<span style=\"color:#FF0000\">$datainfo[54]</span>";// 比分处理
                    }
                    $datainfo_team = $team_active."<span class=\"rb_time_color\">".$team_time."</span>" ;// 球队名称处理


                    	// 全场滚球独赢主队 $datainfo[29]   全场滚球独赢客队 $datainfo[30]
                        $datainfo[7]=$datainfo['strong'];
                        $datainfo[8]=$datainfo['ratio_re'];
                        $datainfo[35]=$datainfo['ratio_rouho'];
                        $datainfo[36]=$datainfo['ratio_rouhu'];
                        $datainfo[37]=$datainfo['ior_ROUHO'];
                        $datainfo[38]=$datainfo['ior_ROUHU'];
                        $datainfo[39]=$datainfo['ratio_rouco'];
                        $datainfo[40]=$datainfo['ratio_roucu'];
                        $datainfo[41]=$datainfo['ior_ROUCO'];
                        $datainfo[42]=$datainfo['ior_ROUCU'];
                        $datainfo[25]=$datainfo['MORE'];

	                    if($datainfo[7]=="H"){
							$ratio_mb_str=$datainfo[8];
							$ratio_tg_str='';
						}elseif($datainfo[7]=="C"){
							$ratio_mb_str='';
							$ratio_tg_str=$datainfo[8];
						}
						$datainfo[5]=str_replace("[Mid]","<font color=\'#005aff\'>[N]</font>",$datainfo[5]);
			    		$datainfo[5]=str_replace("[中]","<font color=\'#005aff\'>[中]</font>",$datainfo[5]);
	                    $newDataArray[$MID]['gid']=$datainfo[0];
						$newDataArray[$MID]['timer']=$datainfo[1];
						$newDataArray[$MID]['league']=$datainfo[2];
						$newDataArray[$MID]['dategh']=date('m-d').$datainfo[3];
						$newDataArray[$MID]['datetimelove']=$datainfo[47];
						$newDataArray[$MID]['gnum_h']=$datainfo[3];
						$newDataArray[$MID]['gnum_c']=$datainfo[4];
						$newDataArray[$MID]['team_h']=$datainfo[5];
						$newDataArray[$MID]['team_h_score']=$datainfo[53];
						$newDataArray[$MID]['team_h_for_sort']=explode('<font color=gray>',$datainfo[5])[0];
						$newDataArray[$MID]['team_c']=$datainfo[6];
						$newDataArray[$MID]['team_c_score']=$datainfo[54];
						$newDataArray[$MID]['strong']=$datainfo[7];
						$newDataArray[$MID]['ratio']=$datainfo['ratio_re'];
						$newDataArray[$MID]['ratio_mb_str']=$ratio_mb_str;
						$newDataArray[$MID]['ratio_tg_str']=$ratio_tg_str;
						$newDataArray[$MID]['ior_RH']=$datainfo['ior_REH'];
						$newDataArray[$MID]['ior_RC']=$datainfo['ior_REC'];
						$newDataArray[$MID]['ratio_o']=$datainfo[11];
						$newDataArray[$MID]['ratio_u']=$datainfo[12];
                        $newDataArray[$MID]['ratio_o_str']= !empty($datainfo['ratio_rouo'])?"大".str_replace('O','',$datainfo['ratio_rouo']):'';
                        $newDataArray[$MID]['ratio_u_str']= !empty($datainfo['ratio_rouu'])?"小".str_replace('U','',$datainfo['ratio_rouu']):'';
						$newDataArray[$MID]['ior_OUH']=$datainfo['ior_ROUC'];
						$newDataArray[$MID]['ior_OUC']=$datainfo['ior_ROUH'];
						$newDataArray[$MID]['ior_EOO']=$datainfo[15];
						$newDataArray[$MID]['ior_EOE']=$datainfo[16];
						$newDataArray[$MID]['ratio_ouho']=$datainfo[35];
						$newDataArray[$MID]['ratio_ouhu']=$datainfo[36];
						$newDataArray[$MID]['ratio_ouho_str']=str_replace('O','',$datainfo[35]);
						$newDataArray[$MID]['ratio_ouhu_str']=str_replace('U','',$datainfo[36]);
						$newDataArray[$MID]['ior_OUHO']=$datainfo[37];
						$newDataArray[$MID]['ior_OUHU']=$datainfo[38];
//                        unset($newDataArray[$MID]['ratio_ouho']);
//                        unset($newDataArray[$MID]['ratio_ouhu']);
//                        unset($newDataArray[$MID]['ratio_ouho_str']);
//                        unset($newDataArray[$MID]['ratio_ouhu_str']);
//                        unset($newDataArray[$MID]['ior_OUHO']);
//                        unset($newDataArray[$MID]['ior_OUHU']);
						$newDataArray[$MID]['ratio_ouco']=$datainfo[39];
						$newDataArray[$MID]['ratio_oucu']=$datainfo[40];
						$newDataArray[$MID]['ratio_ouco_str']=str_replace('O','',$datainfo[39]);
						$newDataArray[$MID]['ratio_oucu_str']=str_replace('U','',$datainfo[40]);
						$newDataArray[$MID]['ior_OUCO']=$datainfo[41];
						$newDataArray[$MID]['ior_OUCU']=$datainfo[42];
//                        unset($newDataArray[$MID]['ratio_ouco']);
//                        unset($newDataArray[$MID]['ratio_oucu']);
//                        unset($newDataArray[$MID]['ratio_ouco_str']);
//                        unset($newDataArray[$MID]['ratio_oucu_str']);
//                        unset($newDataArray[$MID]['ior_OUCO']);
//                        unset($newDataArray[$MID]['ior_OUCU']);
						$newDataArray[$MID]['more']=$datainfo[25];
						$newDataArray[$MID]['eventid']=$datainfo[26];
						$newDataArray[$MID]['hot']=$datainfo[27];
						$newDataArray[$MID]['ior_MH']=$datainfo[29];
						$newDataArray[$MID]['ior_MC']=$datainfo[30];
						$newDataArray[$MID]['datetime']=$datainfo[47];
						$newDataArray[$MID]['team_info']=$datainfo_team;
						$newDataArray[$MID]['score_info']=$datainfo_score;
						$newDataArray[$MID]['center_tv']=$datainfo[31];
						$newDataArray[$MID]['play']=$datainfo[32];
						$newDataArray[$MID]['all']=$datainfo[25];
						$newDataArray[$MID]['bet_Url']="gid={$datainfo[0]}&uid={$uid}&gnum={$datainfo[3]}&langx={$langx}&odd_f_type=H&strong=".$datainfo[7];
//	                    if( $mbteamLast==$datainfo[5] && $tgteamLast==$datainfo[6] && $leagueLast==$datainfo[2] ){
	                    if( $datainfo[53]=='' && $datainfo[54]==''){
							$newDataArray[$MID]['headShow']=0;
						}else{
							$newDataArray[$MID]['headShow']=1;
						}
						$mbteamLast=$datainfo[5];
	            		$tgteamLast=$datainfo[6];
						$leagueLast=$datainfo[2];

//						if(isset($LastOddsArr[$datainfo[0]])){
//							$oddsBackground[$MID]['ior_RH'] = $LastOddsArr[$MID]['ior_RH']==$datainfo[9] ? 0 : 1;
//							$oddsBackground[$MID]['ior_RC'] = $LastOddsArr[$MID]['ior_RC']==$datainfo[10] ? 0 : 1;
//							$oddsBackground[$MID]['ior_OUH'] = $LastOddsArr[$MID]['ior_OUH']==$datainfo[14] ? 0 : 1;
//							$oddsBackground[$MID]['ior_OUC'] = $LastOddsArr[$MID]['ior_OUC']==$datainfo[13] ? 0 : 1;
//							$oddsBackground[$MID]['ior_OUHO'] = $LastOddsArr[$MID]['ior_OUHO']==$datainfo[37] ? 0 : 1;
//							$oddsBackground[$MID]['ior_OUHU'] = $LastOddsArr[$MID]['ior_OUHU']==$datainfo[38] ? 0 : 1;
//							$oddsBackground[$MID]['ior_OUCO'] = $LastOddsArr[$MID]['ior_OUCO']==$datainfo[41] ? 0 : 1;
//							$oddsBackground[$MID]['ior_OUCU'] = $LastOddsArr[$MID]['ior_OUCU']==$datainfo[42] ? 0 : 1;
//							$oddsBackground[$MID]['ior_MH'] = $LastOddsArr[$MID]['ior_MH']==$datainfo[29] ? 0 : 1;
//                            $oddsBackground[$MID]['ior_MC'] = $LastOddsArr[$MID]['ior_MC']==$datainfo[30] ? 0 : 1;
//						}
//							$oddsBackground[$MID]['ior_RH'] = $datainfo[9];
//							$oddsBackground[$MID]['ior_RC'] = $datainfo[10];
//							$oddsBackground[$MID]['ior_OUH'] = $datainfo[14];
//							$oddsBackground[$MID]['ior_OUC'] = $datainfo[13];
//							$oddsBackground[$MID]['ior_OUHO'] = $datainfo[37];
//							$oddsBackground[$MID]['ior_OUHU'] = $datainfo[38];
//							$oddsBackground[$MID]['ior_OUCO'] = $datainfo[41];
//							$oddsBackground[$MID]['ior_OUCU'] = $datainfo[42];
//							$oddsBackground[$MID]['ior_MH'] = $datainfo[29];
//                            $oddsBackground[$MID]['ior_MC'] = $datainfo[30];


                    $K=$K+1;
            }

            // 篮球滚球盘口默认按照时间排序
            $newDataArray = group_same_key($newDataArray,'team_h_for_sort');
            foreach ($newDataArray as $k => $v){
                $val_sort = array_sort($v,'team_h_score',$type='desc');
                foreach ($val_sort as $k2=>$v2){
                    $newDataArray2[] = $v2;
                }
            }
            $newDataArray = $newDataArray2;


        	if(count($currOdds)>0 && $LastOdds!=$currOdds){
				$setResult=$redisObj->setOne('BK_M_ROU_EO_LastOdds_'.$oddchange,json_encode($currOdds));
			}
            $page_count=ceil($K/$page_size);
            echo "parent.t_page=$page_count;\n";
            echo "parent.gamount=$gamecount;\n"; // 总数量
            $listTitle="篮球和美式足球 :滚球";
			$leagueNameCur='';
            break;
        case "p3": // 综合过关
        	if($g_date=="ALL" or $g_date=="undefined" or $g_date==""){
	            $date="";
	        }else{
	            $date="and M_Date='$g_date'";
	        }
	        if ($page_no==''){
	            $page_no=0;
	        }

            $page_size=40;
            $offset=$page_no*$page_size;
            $resulTotalA=getTodayMatches("TODAY_BK_P3");
			$cou=count($resulTotalA);
            $page_count=ceil($cou/$page_size);
            $resulTotal=array_slice($resulTotalA,$offset,$page_size);

	        echo "parent.t_page=$page_count;\n";
	        echo "parent.gamount=$cou;\n";
	        foreach($resulTotal as $key=>$row){
	    		if ($S_Single_Rate==''){
                    $Single='';
                }else{
                    $Single=$o;
                }
                if ($S_Double_Rate==''){
                    $Double='';
                }else{
                    $Double=$e;
                }
	            $m_date=strtotime($row['M_Date']);
	            $dates=date("m-d",$m_date);
	            if (strlen($row['M_Time'])==5){
	                $pdate=$dates.'<br>0'.$row['M_Time'];
	            }else{
	                $pdate=$dates.'<br>'.$row['M_Time'];
	            }
	            
	       		 if($row['ShowTypeP']=="H"){
					$ratio_mb_str=$row['M_P_LetB'];
					$ratio_tg_str='';
				}elseif($row['ShowTypeP']=="C"){
					$ratio_mb_str='';
					$ratio_tg_str=$row['M_P_LetB'];
				}
	            
				$MID=$row[MID];
	            $newDataArray[$MID]['gid']=$row[MID];
				$newDataArray[$MID]['datetime']=$pdate;
				$newDataArray[$MID]['dategh']=date('m-d').$row['MB_MID'];
				$newDataArray[$MID]['league']=$row[M_League];
				$newDataArray[$MID]['gnum_h']=$row[MB_MID];
				$newDataArray[$MID]['gnum_c']=$row[TG_MID];
				$newDataArray[$MID]['team_h']=$row[MB_Team];
				$newDataArray[$MID]['team_c']=$row[TG_Team];
				$newDataArray[$MID]['strong']=$row[ShowTypeP];
				$newDataArray[$MID]['ratio']=$row[M_P_LetB];
				$newDataArray[$MID]['ratio_mb_str']=$ratio_mb_str;
				$newDataArray[$MID]['ratio_tg_str']=$ratio_tg_str;
				$newDataArray[$MID]['ior_PRH']=change_rate($open,$row['MB_P_LetB_Rate']);
				$newDataArray[$MID]['ior_PRC']=change_rate($open,$row['TG_P_LetB_Rate']);
				$newDataArray[$MID]['ratio_o']=$row[MB_P_Dime];
				$newDataArray[$MID]['ratio_u']=$row[TG_P_Dime];
				$newDataArray[$MID]['ratio_o_str']="大".str_replace('O','',$row['MB_P_Dime']);
				$newDataArray[$MID]['ratio_u_str']="小".str_replace('U','',$row['TG_P_Dime']);
				$newDataArray[$MID]['ior_POUC']=change_rate($open,$row['MB_P_Dime_Rate']);
				$newDataArray[$MID]['ior_POUH']=change_rate($open,$row['TG_P_Dime_Rate']);
				$newDataArray[$MID]['str_odd'] =$Single;
				$newDataArray[$MID]['str_even']=$Double;
				$newDataArray[$MID]['ior_PO']=change_rate($open,$row['S_P_Single_Rate']);
				$newDataArray[$MID]['ior_PE']=change_rate($open,$row['S_P_Double_Rate']);
				$newDataArray[$MID]['ior_PMH']=change_rate($open,$row["MB_P_Win_Rate"]);
				$newDataArray[$MID]['ior_PMC']=change_rate($open,$row["TG_P_Win_Rate"]);
				$newDataArray[$MID]['hratio']=$row[M_LetB_H];
				$newDataArray[$MID]['gidm']=$row[MID];
				$newDataArray[$MID]['par_minlimit']=3;
				$newDataArray[$MID]['par_maxlimit']=10;
				$newDataArray[$MID]['ratio_pouho']=$row[MB_Dime_H];
				$newDataArray[$MID]['ratio_pouhu']=$row[MB_Dime_S_H]; 
				$newDataArray[$MID]['ratio_ouho_str']=str_replace('O','',$row['MB_Dime_H']);
				$newDataArray[$MID]['ratio_ouhu_str']=str_replace('U','',$row['MB_Dime_S_H']);
				$newDataArray[$MID]['ior_POUHO']=change_rate($open,$row["MB_P_Dime_Rate_H"]);
				$newDataArray[$MID]['ior_POUHU']=change_rate($open,$row["MB_P_Dime_Rate_S_H"]);
				$newDataArray[$MID]['ratio_pouco']=$row[TG_Dime_H];
				$newDataArray[$MID]['ratio_poucu']=$row[TG_Dime_S_H];
				$newDataArray[$MID]['ratio_ouco_str']=str_replace('O','',$row['TG_Dime_H']);
				$newDataArray[$MID]['ratio_oucu_str']=str_replace('U','',$row['TG_Dime_S_H']);
				$newDataArray[$MID]['ior_POUCO']=change_rate($open,$row["TG_P_Dime_Rate_H"]);
				$newDataArray[$MID]['ior_POUCU']=change_rate($open,$row["TG_P_Dime_Rate_S_H"]); 
	            
	            $K=$K+1;
	        }

//	        echo '<pre>';
//	        print_r($newDataArray);
//	        echo '<br/>';
	        
	        
        $listTitle="今日篮球和美式足球：综合过关";
		$leagueNameCur='';
        break;
        }

        ?>
        // function onLoad(){
        //     if(parent.retime > 0)
        //         parent.retime_flag='Y';
        //     else
        //         parent.retime_flag='N';
        //    		parent.loading_var = 'N';
        //     if(parent.loading == 'N' && parent.ShowType != ''){
        //         parent.ShowGameList();
        //     }
        // }
        //
        // function onUnLoad(){
        //     x = parent.pageXOffset;
        //     y = parent.pageYOffset;
        //     parent.scroll(x,y);
        // }
    </script>
    <link rel="stylesheet" href="/style/member/mem_body_ft.css?v=<?php echo AUTOVER; ?>" type="text/css" media="screen">
    <?php if($rtype=='p3'){?>
        <link rel="stylesheet" href="/style/member/mem_body_p3.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <?php } ?>
</head>
<body i1d="MFT" class="bodyset FTR body_browse_set" onload="onLoad();">
<!-- 加载层 -->
<!--<div id="controlscroll"><table border="0" cellspacing="0" cellpadding="0" class="loadBox"><tr><td><!--loading--><!--</td></tr></table></div>-->
<div class="ss_table" style="display: inline-block">
    <table border="0" cellpadding="0" cellspacing="0" id="myTable">
	<tbody>
		<tr>
			<td>
			 <table border="0" cellpadding="0" cellspacing="0" id="box" class="">
				<tbody>
					<tr>
						<td class="top">
							<h1 class="top_h1">
                                <em><?php echo $listTitle; ?></em>
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
										<span id="pg_txt"></span>
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
											case "r":	include "body_m_r_ou.php";break;
											case "all":	include "body_m_r_ou.php";break;
											case "re":	include "body_re_m_r_ou.php";break;
											case "p3":	include "../BK_future/body_p3.php";break;
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
<script type="text/javascript" src="../../../js/src/common_body_var.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript">
    // 侧边栏游戏选项处理，在当前游戏中不显示当前游戏
    var g_type = sessionStorage.getItem('g_type') ;
    var m_type = sessionStorage.getItem('m_type') ;
    if(m_type == 'rb'){
        document.getElementsByClassName('today_bet_basketball_move')[0].style.display='none' ;
        document.getElementsByClassName('today_bet_basketball')[0].style.display='' ;

    }
    setBodyScroll();
</script>
</body>
</html>
