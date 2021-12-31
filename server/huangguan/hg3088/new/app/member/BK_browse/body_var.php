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
$league_id=$_REQUEST['league_id'];
$g_date=$_REQUEST['g_date'];
$page_no=$_REQUEST['page_no'];
$leaname = $_REQUEST['leaname'] ; // 搜索赛事
$sorttype = $_REQUEST['sorttype'] ? $_REQUEST['sorttype'] : 'time';
if($leaname=='undefined'){
    $leaname='' ;
}

require ("../include/traditional.$langx.inc.php");

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    setcookie('login_uid','');
    echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
    exit;
}

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
function getRunningDataByMethod(){
    $key='BK_M_ROU_EO';
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

$table_title='蓝球和美式足球' ;
$bk_dx_title ='球队得分：大/小' ;

?>
<head>
    <TITLE>篮球變數值</TITLE>
    <META http-equiv=Content-Type content="text/html; charset=utf-8">
    <link rel="stylesheet" href="../../../style/member/common.css?v=<?php echo AUTOVER; ?>" type="text/css"><link rel="stylesheet" href="../../../style/member/mem_body_ft.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <?php if($rtype=='p3'){?>
        <link rel="stylesheet" href="/style/member/mem_body_p3.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <?php } ?>
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
        top.today_gmt = '<?php echo $m_date ?>';
        top.now_gmt = '<?php echo date("H:i:s") ?>';
        parent.retime=60 ; // 刷新倒计时

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
        case "all":
        case "r": // 从滚球标签切换过今日赛事
        case "r_main":
            $table='<tr>
		    <th class="bk_h_1x1" colspan="2">'.$table_title .'</th>
            <th nowrap class="h_1x2">'.$WIN.'</th>
		    <th class="bk_h_rq" >'.$Handicap.'</th>
		    <th  class="bk_h_dx">'.$Over_Under.'</th>
		    <th>'.$OE.'</th>  <!--单双-->
		    <th class="h_ouhc" colspan="2">'.$bk_dx_title.'</th>
			</tr>';
            $resulTotal=getTodayMatches("TODAY_BK_M_ROU_EO");
            if($sorttype == 'league'){// 按照联盟排序
                foreach ($resulTotal as $k => $v){
                    $resulTotal[$k]['M_League_Initials'] = _getFirstCharter($v['M_League']);
                }
                $resulTotal = array_sort($resulTotal,'M_League_Initials',$type='asc');
                // 联盟相同的归成一类
                //    $resulTotal = group_same_key($resulTotal,'M_League');
            }
            $cou_num=count($resulTotal);
            $page_size=40;
            $page_count=ceil($cou_num/$page_size);
            $offset=$page_no*40;
            $resultArr=array_slice($resulTotal,$offset,$num);
			$cou=count($resultArr);
           // echo "parent.GameHead = new Array('gid','datetime','league','gnum_h','gnum_c','team_h','team_c','strong','ratio','ior_RH','ior_RC','ratio_o','ratio_u','ior_OUH','ior_OUC','ior_MH','ior_MC','ior_MN','str_odd','str_even','ior_EOO','ior_EOE','ratio_ouho','ratio_ouhu','ior_OUHO','ior_OUHU','ratio_ouco','ratio_oucu','ior_OUCO','ior_OUCU','more','eventid','hot','center_tv','play','gidm','isMaster','all');";
            
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
                $newDataArray[$MID]['ratio_o_str']="大".str_replace('O','',$row['MB_Dime']);
                $newDataArray[$MID]['ratio_u_str']="小".str_replace('U','',$row['TG_Dime']);
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
                $newDataArray[$MID]['bet_Url']="gid={$MID}&uid={$uid}&odd_f_type=H&gnum={$future_r_data[$i]['MB_MID']}&langx={$langx}";
            }

            $leagueNameCur='';
            break;
        case "re": // 从今日赛事切换到滚球 滚球
        case "re_main":
            $table='<tr>
		    <th class="bk_h_1x1" colspan="2">'.$table_title.':'.$Running_Ball.'</th>
            <th nowrap class="h_1x2">'.$WIN.'</th>
		    <th  class="bk_h_rq">'.$Handicap.'</th>
		    <th  class="bk_h_dx">'.$Over_Under.'</th>
		    <th class="h_ouhc" colspan="2">'.$bk_dx_title.'</th>
			</tr>';
            //echo "parent.GameHead = new Array('gid','timer','league','gnum_h','gnum_c','team_h','team_c','strong','ratio','ior_RH','ior_RC','ratio_o','ratio_u','ior_OUH','ior_OUC','ior_EOO','ior_EOE','ratio_ouho','ratio_ouhu','ior_OUHO','ior_OUHU','ratio_ouco','ratio_oucu','ior_OUCO','ior_OUCU','more','eventid','hot','','ior_MH','ior_MC','team_info','score_info','center_tv','play','datetime','all');";
            echo "parent.retime=20;\n"; // 倒计时刷新时间
            echo "parent.str_renew = '$second_auto_update';\n";
            $page_size=40;
            $page_count=0;
            $gamecount=0;
            echo "parent.t_page=0\n";
            echo "parent.gamount=0;\n";
            $matches=getRunningDataByMethod();
            if(is_array($matches)){
            	$cou=count($matches);	
            }else{
            	$cou=0;
            }
            for($i=0;$i<$cou;$i++){
                $messages=$matches[$i];
                $messages=str_replace(");",")",$messages);
                $messages=str_replace("cha(9)","",$messages);
                $datainfo=eval("return $messages;");
                $MID=$datainfo[0];
                $datainfos[$MID]=$datainfo;
                $gamecount ++;
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
                if( (strpos($datainfo[5], '-') !== false and $isClosedH1) ||
                    ($datainfo[52]=='Q3' and $mem_bkq3_off=='off') ||
                    ($datainfo[52]=='H2' and $datainfo[56]<=1200 and $mem_bkq3_off=='off') ||
                    ($datainfo[52]=='HT' and $datainfo[56]>0 and $datainfo[56]<=1190 and $mem_bkq3_off=='off') ||
                    $datainfo[52]=='Q4' || $datainfo[52]=='OT' ||
                    ($datainfo[52]=='H2' and $datainfo[56]<=600) ||
                    ($datainfo[52] == "Q3" && $datainfo[56]<=180)){

                    $datainfos[$MID]=array();
                    $datainfos[$MID][0] = $datainfo[0];
                    $datainfos[$MID][1] = $datainfo[1];
                    $datainfos[$MID][2] = $datainfo[2];
                    $datainfos[$MID][3] = $datainfo[3];
                    $datainfos[$MID][4] = $datainfo[4];
                    $datainfos[$MID][5] = $datainfo[5];
                    $datainfos[$MID][6] = $datainfo[6];
                    $datainfos[$MID][7] = $datainfo[7];
                    $datainfos[$MID][47] = $datainfo[47];
                    $datainfos[$MID][48] = $datainfo[48];
                    $datainfos[$MID][52] = $datainfo[52];
                    $datainfos[$MID][56] = $datainfo[56];
                    $datainfos[$MID][53] = $datainfo[53];
                    $datainfos[$MID][54] = $datainfo[54];

                    // 其他盘口，赔率等投注信息不显示（无视美式足球）
                    if (strpos($datainfo[2],'美式足球')===false){

                        if (isset($datainfos[$MID+7])){
                            if ($datainfo[2]==$datainfos[$MID+7][2] and $datainfo[5]==$datainfos[$MID+7][5] and $datainfo[6]==$datainfos[$MID+7][6]){
                                $datainfos[$MID+7]=array();
                                $datainfos[$MID+7][0] = $datainfo[0];
                                $datainfos[$MID+7][1] = $datainfo[1];
                                $datainfos[$MID+7][2] = $datainfo[2];
                                $datainfos[$MID+7][3] = $datainfo[3];
                                $datainfos[$MID+7][4] = $datainfo[4];
                                $datainfos[$MID+7][5] = $datainfo[5];
                                $datainfos[$MID+7][6] = $datainfo[6];
                                $datainfos[$MID+7][7] = $datainfo[7];
                                $datainfos[$MID+7][47] = $datainfo[47];
                                $datainfos[$MID+7][48] = $datainfo[48];
                                $datainfos[$MID+7][52] = $datainfo[52];
                                $datainfos[$MID+7][56] = $datainfo[56];
                                $datainfos[$MID+7][53] = $datainfo[53];
                                $datainfos[$MID+7][54] = $datainfo[54];
                            }
                        }
                        if (isset($datainfos[$MID+14])) {
                            if ($datainfo[2] == $datainfos[$MID + 14][2] and $datainfo[5] == $datainfos[$MID + 14][5] and $datainfo[6] == $datainfos[$MID + 14][6]) {
                                $datainfos[$MID+14]=array();
                                $datainfos[$MID+14][0] = $datainfo[0];
                                $datainfos[$MID+14][1] = $datainfo[1];
                                $datainfos[$MID+14][2] = $datainfo[2];
                                $datainfos[$MID+14][3] = $datainfo[3];
                                $datainfos[$MID+14][4] = $datainfo[4];
                                $datainfos[$MID+14][5] = $datainfo[5];
                                $datainfos[$MID+14][6] = $datainfo[6];
                                $datainfos[$MID+14][7] = $datainfo[7];
                                $datainfos[$MID+14][47] = $datainfo[47];
                                $datainfos[$MID+14][48] = $datainfo[48];
                                $datainfos[$MID+14][52] = $datainfo[52];
                                $datainfos[$MID+14][56] = $datainfo[56];
                                $datainfos[$MID+14][53] = $datainfo[53];
                                $datainfos[$MID+14][54] = $datainfo[54];
                            }
                        }
                    }

                }
            }

            if ($sorttype == 'time'){ // 按开始时间排序
                foreach ($datainfos as $key => $match){
                    // 转换时间 02-28<br>01:35a  -》  2019-02-28 01:35:00
                    // 转换时间 02-28<br>01:35p  -》  2019-02-28 13:35:00
                    $match[100] = str_replace('<br>', ' ', $match[47]); //02-28 01:35a
                    $sAorP = substr($match[100],11);
                    $match[100] = date('Y-m-d H:i:s',strtotime('2019-'.substr($match[100],0, -1)));
                    if ($sAorP == 'p'){
                        $match[100] = date('Y-m-d H:i:s',strtotime($match[100])+43200);
                    }
                    $datainfos[$key][100] = $match[100];
                }
                $datainfos = array_sort($datainfos,0,$type='desc');
                $datainfos = array_values(array_sort($datainfos,100,$type='asc'));
            }

            foreach ($datainfos as $k => $datainfo){
//                $M_duration = $datainfo[52].'-'.$datainfo[56]; // 比赛进度 【 Q2-80】 【 第二节-第80分钟】
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
                /**
                 * MB_Dime_Rate_RB_H='$datainfo[37]',   // 主队 得分大小 大
                MB_Dime_Rate_RB_S_H='$datainfo[38]', // 主队 得分大小 小
                TG_Dime_Rate_RB_H='$datainfo[41]',  // 客队 得分大小 大
                TG_Dime_Rate_RB_S_H='$datainfo[42]'  // 客队 得分大小 小
                 */
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
                $team_active = $team_time ='';
                // 优久乐数据判断处理（篮球滚球没有比分和时间，另行处理）
                $match_time=explode("^", $datainfo[48]);
                if($match_time[0] == 196){
                    $team_active = '';
                    $team_time = $match_time[1];
                    $datainfo_score = $match_time[2];
                }else {
                    $mbTeamArr = explode('-', $datainfo[5]);
                    preg_match('/\d+/', $mbTeamArr[1], $mbTeamArrList);
                    if ($mbTeamArrList[0] == 2) {
                        $team_active = '第二节';
                    } elseif ($mbTeamArrList[0] == 3) {
                        $team_active = '第三节';
                    } elseif ($mbTeamArrList[0] == 4) {
                        $team_active = '第四节';
                    } else {
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
                    }
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
                    if($datainfo[7]=="H"){
                        $ratio_mb_str=$datainfo[8];
                        $ratio_tg_str='';
                    }elseif($datainfo[7]=="C"){
                        $ratio_mb_str='';
                        $ratio_tg_str=$datainfo[8];
                    }
                    $datainfo[5]=str_replace("[Mid]","<font color=\'#005aff\'>[N]</font>",$datainfo[5]);
                    $datainfo[5]=str_replace("[中]","<font color=\'#005aff\'>[中]</font>",$datainfo[5]);
					
					$MID=$datainfo[0];
                    $newDataArray[$MID]['gid']=$datainfo[0];
                    $newDataArray[$MID]['timer']=$datainfo[1];
                    $newDataArray[$MID]['league']=$datainfo[2];
                    $newDataArray[$MID]['gnum_h']=$datainfo[3];
                    $newDataArray[$MID]['gnum_c']=$datainfo[4];
                    $newDataArray[$MID]['team_h']=$datainfo[5];
                    $newDataArray[$MID]['team_h_score']=$datainfo[53];
                    $newDataArray[$MID]['team_h_for_sort']=explode('<font color=gray>',$datainfo[5])[0];
                    $newDataArray[$MID]['team_c']=$datainfo[6];
                    $newDataArray[$MID]['team_c_score']=$datainfo[54];
                    $newDataArray[$MID]['strong']=$datainfo[7];
                    $newDataArray[$MID]['ratio']=$datainfo[8];
                    $newDataArray[$MID]['ratio_mb_str']=$ratio_mb_str;
                    $newDataArray[$MID]['ratio_tg_str']=$ratio_tg_str;
                    $newDataArray[$MID]['ior_RH']=$datainfo[9];
                    $newDataArray[$MID]['ior_RC']=$datainfo[10];
                    $newDataArray[$MID]['ratio_o']=$datainfo[11];
                    $newDataArray[$MID]['ratio_u']=$datainfo[12];
                    $newDataArray[$MID]['ratio_o_str']="大".str_replace('O','',$datainfo[11]);
                    $newDataArray[$MID]['ratio_u_str']="小".str_replace('U','',$datainfo[12]);
                    $newDataArray[$MID]['ior_OUH']=$datainfo[14];
                    $newDataArray[$MID]['ior_OUC']=$datainfo[13];
                    $newDataArray[$MID]['ior_EOO']=$datainfo[15];
                    $newDataArray[$MID]['ior_EOE']=$datainfo[16];
//                    $newDataArray[$MID]['ratio_ouho']=$datainfo[35];
//                    $newDataArray[$MID]['ratio_ouhu']=$datainfo[36];
//                    $newDataArray[$MID]['ratio_ouho_str']=str_replace('O','',$datainfo[35]);
//                    $newDataArray[$MID]['ratio_ouhu_str']=str_replace('U','',$datainfo[36]);
//                    $newDataArray[$MID]['ior_OUHO']=$datainfo[37];
//                    $newDataArray[$MID]['ior_OUHU']=$datainfo[38];
                    unset($newDataArray[$MID]['ratio_ouho']);
                    unset($newDataArray[$MID]['ratio_ouhu']);
                    unset($newDataArray[$MID]['ratio_ouho_str']);
                    unset($newDataArray[$MID]['ratio_ouhu_str']);
                    unset($newDataArray[$MID]['ior_OUHO']);
                    unset($newDataArray[$MID]['ior_OUHU']);
//                    $newDataArray[$MID]['ratio_ouco']=$datainfo[39];
//                    $newDataArray[$MID]['ratio_oucu']=$datainfo[40];
//                    $newDataArray[$MID]['ratio_ouco_str']=str_replace('O','',$datainfo[39]);
//                    $newDataArray[$MID]['ratio_oucu_str']=str_replace('U','',$datainfo[40]);
//                    $newDataArray[$MID]['ior_OUCO']=$datainfo[41];
//                    $newDataArray[$MID]['ior_OUCU']=$datainfo[42];
                    unset($newDataArray[$MID]['ratio_ouco']);
                    unset($newDataArray[$MID]['ratio_oucu']);
                    unset($newDataArray[$MID]['ratio_ouco_str']);
                    unset($newDataArray[$MID]['ratio_oucu_str']);
                    unset($newDataArray[$MID]['ior_OUCO']);
                    unset($newDataArray[$MID]['ior_OUCU']);
                    $newDataArray[$MID]['more']=$datainfo[25];
                    $newDataArray[$MID]['eventid']=$datainfo[26];
                    $newDataArray[$MID]['hot']=$datainfo[27];
                    $newDataArray[$MID]['ior_MH']=$datainfo[29];
                    $newDataArray[$MID]['ior_MC']=$datainfo[30];
                    $newDataArray[$MID]['team_info']=$datainfo_team;
                    $newDataArray[$MID]['score_info']=$datainfo_score;
                    $newDataArray[$MID]['center_tv']=$datainfo[31];
                    $newDataArray[$MID]['play']=$datainfo[32];
                    $newDataArray[$MID]['datetime']=$datainfo[47];
                    $newDataArray[$MID]['all']=$datainfo[49];
                    $newDataArray[$MID]['bet_Url']="gid={$datainfo[0]}&uid={$uid}&gnum={$datainfo[3]}&langx={$langx}&odd_f_type=H&strong=".$datainfo[7];

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

            $page_count=ceil($K/$page_size);
            echo "parent.t_page=$page_count;\n";
            echo "parent.gamount=$gamecount;\n"; // 总数量

            $leagueNameCur='';
            break;
        case "p3": // 综合过关
            $table_dif='bd_all' ;// 波胆table 类
            $table='<tr>
                <th class="bk_future_h" colspan="2">'.$table_title.'</th>
                <th nowrap class="h_1x2">'.$WIN.'</th>
                <th class="bk_h_r">'.$Handicap.'</th>
                <th class="bk_h_r">'.$Over_Under.'</th>
                <th class="h_oe">'.$O_E.'</th>
                <th class="h_oe" colspan="2">'.$bk_dx_title.'</th>
                </tr> <tr class="bet_correct_title">
                    <td colspan="20">'.$U_10. ' <span class="maxbet">'.$U_11.'  ： RMB 3,000,000.00</span></td>   
                </tr>';
	        if($g_date=="ALL" or $g_date=="undefined" or $g_date==""){
	            $date="";
	        }else{
	            $date="and M_Date='$g_date'";
	        }
	        if ($page_no==''){
	            $page_no=0;
	        }
	        $resulTotal=getTodayMatches("TODAY_BK_P3");
	        $cou=count($resulTotal);
			$page_size=40;
	        $page_count=ceil($cou/$page_size);
	        echo "parent.t_page=$page_count;\n";
	        echo "parent.gamount=$cou;\n";

	        //echo "parent.GameHead = new Array('gid','datetime','league','gnum_h','gnum_c','team_h','team_c','strong','ratio','ior_PRH','ior_PRC','ratio_o','ratio_u','ior_POUC','ior_POUH','gidm','par_minlimit','par_maxlimit');";
	        // ior_POEO 主队单   ior_POEE 客队单   ior_PMH 独赢主队   ior_PMC 独赢客队
	        //echo "parent.GameHead = new Array('gid','datetime','league','gnum_h','gnum_c','team_h','team_c','strong','ratio','ior_PRH','ior_PRC','ratio_o','ratio_u','ior_POUC','ior_POUH','ior_POEO','ior_POEE','ior_PMH','ior_PMC','gidm','par_minlimit','par_maxlimit');";
//	        echo "parent.GameHead = new Array('gid','datetime','league','gnum_h','gnum_c','team_h','team_c','strong','ratio','ior_PRH','ior_PRC','ratio_o','ratio_u','ior_POUC','ior_POUH',
//	        'str_odd','str_even','ior_EOO','ior_EOE',
//	        'ior_PO','ior_PE','ior_PMH','ior_PMC','ior_MN','ior_H1C0','ior_H2C0','ior_H2C1','ior_H3C0','ior_H3C1','ior_H3C2','ior_H4C0','ior_H4C1','ior_H4C2','ior_H4C3','ior_H0C0','ior_H1C1','ior_H2C2','ior_H3C3','ior_H4C4','ior_OVH','ior_H0C1','ior_H0C2','ior_H1C2','ior_H0C3','ior_H1C3','ior_H2C3','ior_H0C4','ior_H1C4','ior_H2C4','ior_H3C4','ior_OVC','ior_T01','ior_T23','ior_T46','ior_OVER','ior_FHH','ior_FHN','ior_FHC','ior_FNH','ior_FNN','ior_FNC','ior_FCH','ior_FCN','ior_FCC','hgid','hstrong','hratio','ior_HPRH','ior_HPRC','ior_HPOUH','ior_HPOUC','ior_HH1C0','ior_HH2C0','ior_HH2C1','ior_HH3C0','ior_HH3C1','ior_HH3C2','ior_HH4C0','ior_HH4C1','ior_HH4C2','ior_HH4C3','ior_HH0C0','ior_HH1C1','ior_HH2C2','ior_HH3C3','ior_HH4C4','ior_HOVH','ior_HH0C1','ior_HH0C2','ior_HH1C2','ior_HH0C3','ior_HH1C3','ior_HH2C3','ior_HH0C4','ior_HH1C4','ior_HH2C4','ior_HH3C4','ior_HOVC','ior_HPMH','ior_HPMC','ior_HPMN','more','gidm','par_minlimit','par_maxlimit','ratio_pouho','ratio_pouhu','ior_POUHO','ior_POUHU','ratio_pouco','ratio_poucu','ior_POUCO','ior_POUCU');
//				";
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

        $leagueNameCur='';

        ?>

        <?php
        break;
        }

        ?>
        //function onLoad(){
        //    if(parent.parent.mem_order.location == 'about:blank'){
        //        parent.parent.mem_order.location = '<?php //echo  BROWSER_IP?>///app/member/select.php?uid=<?php //echo  $uid?>//&langx=<?php //echo  $langx?>//';
        //    }
        //    if(parent.retime > 0)
        //        parent.retime_flag='Y';
        //    else
        //        parent.retime_flag='N';
        //    parent.loading_var = 'N';
        //    if(parent.loading == 'N' && parent.ShowType != ''){
        //        parent.ShowGameList();
        //    }
        //}
        //
        //function onUnLoad(){
        //    x = parent.pageXOffset;
        //    y = parent.pageYOffset;
        //    parent.scroll(x,y);
        //}
    </script>
</head>
<body  onLoad="onLoad()" class="load_body_var <?php echo $table_dif?>">
<!--<div id="controlscroll"><table border="0" cellspacing="0" cellpadding="0" class="loadBox"><tr><td> </td></tr></table></div>-->
<!-- 球赛展示区顶部 开始-->
<div class="bet_head">
    <!--左侧按钮-->
    <div class="bet_left">
        <?php

        if($rtype=='r' or $rtype=='re' or $rtype =='r_main' or $rtype =='re_main' ){ // 主要盘口、全部盘口才有
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

        if($rtype=='r' or $rtype=='re' or $rtype=='r_main' or $rtype=='re_main'){ // 全部才有、主要盘口
            ?>
            <span id="sel_Market" class="bet_view_btn" onclick="chgMarket('main');" style="display: <?php if($rtype=='r' or $rtype=='re'){echo 'inline-block';}else{echo 'none';}?>;" ><tt id="SpanMarket" class="bet_normal_text">主要盘口</tt></span>
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
        if($rtype=='r' or $rtype=='re' or $rtype =='r_main' or $rtype =='re_main'){ // 只有全部才有
            echo '<span id="sel_odd" class="bet_odds_btn"><tt id="chose_odd" class="bet_normal_text">香港盘</tt></span>' ;
        }
        ?>

        <span class="bet_time_btn" onclick="javascript:reload_var()">
            <tt id="refreshTime" class="bet_time_text"><?php echo $U_14 ?></tt>
        </span>

    </div>

</div>
<!-- 球赛展示区顶部 结束-->

<table border="0" cellpadding="0" cellspacing="0" id="myTable" class="bet_game_table " >
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
                                        case "all":
                                            include "body_m_r_ou.php";
                                            break;
                                        case "re":
                                        case "re_main":
                                            include "body_re_m_r_ou.php";
                                            break;
                                        case "p3":	include "../BK_future/body_p3.php";break;
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
<script type="text/javascript" src="../../../js/src/common_body_var.js?v=<?php echo AUTOVER; ?>"></script>
<script>
    setBodyScroll();
</script>

</body>
</html>