<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include "../include/address.mem.php";
echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
require ("../include/config.inc.php");
require ("../include/define_function_list.inc.php");
$today_date=date("Y-m-d"); // 今日
$uid=$_REQUEST['uid'];
$langx=$_SESSION['langx'];
$mtype=$_REQUEST['mtype'];
$rtype=$_REQUEST['rtype'];
$league_id=$_REQUEST['league_id'];
$g_date=$_REQUEST['g_date'];
$page_no=$_REQUEST['page_no'];
$showtype=$_REQUEST['showtype'];
$leaname = isset($_REQUEST['leaname'])?$_REQUEST['leaname']:'' ; // 搜索赛事
$sorttype = $_REQUEST['sorttype'] ? $_REQUEST['sorttype'] : 'time';
if($leaname=='undefined'){
    $leaname='' ;
}

require ("../include/traditional.$langx.inc.php");

$open=$_SESSION['OpenType'];
$memname=$_SESSION['UserName'];
$pay_type=$_SESSION['Pay_Type'];

if ($league_id=='' and $showtype!='hgfu'){
	$num=60;
}else{
	$num=1024;
}
if($g_date=="ALL" or $g_date=="undefined" or $g_date==""){
   $date="";
}else{
   $date="and M_Date='$g_date'";
}
if ($page_no==''){
    $page_no=0;
}
$m_date=date('Y-m-d');
$K=0;
$page_size=60; // 每页展示条数
$page_gamecount=0; // 用于统计当前页共有多少数据

$redisObj = new Ciredis();

// 获取数据库数据 $type 当前类型
function getFutureData($type){
    global $redisObj,$leaname,$g_date;
    $returnData = $redisObj->getSimpleOne($type);
    $returnData = json_decode($returnData,true) ; // 有true 参数返回数组，没有返回对象 object
    if(isset($g_date) && $g_date=="ALL"){ // 全部日期
        if(isset($leaname)&&strlen($leaname)>0){
            // var_dump($returnData);
            foreach( $returnData as $key=>$val ){
                if(strpos($val['MB_Team'],$leaname)>-1 || strpos($val['TG_Team'],$leaname)>-1 || strpos($val['M_League'],$leaname)>-1){
                    $returnDataNew[]=$val;
                }
            }
            $returnDataNew=loveShaiXuan($returnDataNew);
            $returnDataNew=M_LeagueShaiXuan($returnDataNew);
            return $returnDataNew;
        }else{
            $returnData=loveShaiXuan($returnData);
            $returnData=M_LeagueShaiXuan($returnData);
            return $returnData ;
        }
    }else if(isset($g_date) && checkDateFormat($g_date)){ // 日期筛选
        // var_dump($returnData);
        foreach( $returnData as $key=>$val ){
            // echo $val["M_Date"] ;
            if($val["M_Date"]==$g_date){
                if(isset($leaname)&&strlen($leaname)>0){
                    if(strpos($val['MB_Team'],$leaname)>-1 || strpos($val['TG_Team'],$leaname)>-1 || strpos($val['M_League'],$leaname)>-1){
                        $returnDataNew[]=$val;
                    }
                }else{
                    $returnDataNew[]=$val;
                }
            }
        }
        $returnDataNew=loveShaiXuan($returnDataNew);
        $returnDataNew=M_LeagueShaiXuan($returnDataNew);
        return $returnDataNew ;
    }

    $returnData=loveShaiXuan($returnData);
    $returnData=M_LeagueShaiXuan($returnData);

    return $returnData ;

}

function getP3Matches(){
	global $redisObj,$g_date,$leaname;
    $key='FUTURE_FT_P3';
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
$newDataArray = array();

?>
<head><title>足球變數值</title>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
    <link rel="stylesheet" href="../../../style/member/common.css?v=<?php echo AUTOVER; ?>" type="text/css"><link rel="stylesheet" href="../../../style/member/mem_body_ft.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <?php
    if ($rtype=='p3'){
        ?>
        <link rel="stylesheet" href="/style/member/mem_body_p3.css?v=<?php echo AUTOVER; ?>" type="text/css">
        <?php
    }
    ?>
<SCRIPT language=JavaScript>

parent.flash_ior_set='Y';
parent.minlimit_VAR='3';
parent.maxlimit_VAR='10';
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
parent.g_date = 'ALL';
parent.retime = 180; // 刷新时间
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
    $table='<tr>
              <th nowrap class="h_1x1" style="width: 48px" >'.$U_061.'</th>
              <th class="h_1x170"></th>
              <th nowrap class="h_1x2">'.$WIN.'</th>
              <th nowrap class="h_r">'.$U_02.'</th>
              <th nowrap class="h_ou">'.$U_03.'</th>
              <th class="h_ds">'.$OE.'</th>
              <th nowrap class="h_1x2">'.$WIN.'</th>
              <th nowrap class="h_r">'.$U_04.'</th>
              <th nowrap class="h_ou">'.$U_05.'</th>
            </tr>';
    if($sorttype == 'league'){// 按照联盟排序
        $future_r_data_total = getFutureData('FUTURE_R') ; // 数据
        foreach ($future_r_data_total as $k => $v){
            $future_r_data_total[$k]['M_League_Initials'] = _getFirstCharter($v['M_League']);
        }
        $future_r_data = array_values(array_sort($future_r_data_total,'M_League_Initials',$type='asc'));
        // 联盟相同的归成一类
//    $resulTotal = group_same_key($resulTotal,'M_League');
    }
    else{
        $future_r_data = getFutureData('FUTURE_R') ; // 数据
    }
    $length = count($future_r_data) ; // 长度
    $page_count=ceil($length/$page_size); // 总共多少页
    $offset=$page_no*60;
	echo "parent.str_renew = '$manual_update';\n";
	echo "parent.game_more=1;\n";
	echo "parent.str_more='$more';\n";
	echo "parent.t_page=$page_count;\n";

    for($i=$offset;$i<($page_no+1)*$page_size;$i++){
        if($future_r_data[$i]['MB_MID']){ // 防止空数据
            $MB_Win_Rate=change_rate($open,$future_r_data[$i]["MB_Win_Rate"]);
            $TG_Win_Rate=change_rate($open,$future_r_data[$i]["TG_Win_Rate"]);
            $M_Flat_Rate=change_rate($open,$future_r_data[$i]["M_Flat_Rate"]);

            // 全场让球单独处理
            $ra_rate=get_other_ioratio(GAME_POSITION,$future_r_data[$i]["MB_LetB_Rate"],$future_r_data[$i]["TG_LetB_Rate"],100); // 默认都是香港盘
            $MB_LetB_Rate=$ra_rate[0]; // 主队
            $TG_LetB_Rate=$ra_rate[1]; // 客队
            $MB_LetB_Rate=change_rate($open,$MB_LetB_Rate);
            $TG_LetB_Rate=change_rate($open,$TG_LetB_Rate);
            // 全场大小单独处理
            $dx_rate=get_other_ioratio(GAME_POSITION,$future_r_data[$i]["MB_Dime_Rate"],$future_r_data[$i]["TG_Dime_Rate"],100); // 默认都是香港盘
            $MB_Dime_Rate=$dx_rate[0]; // 主队
            $TG_Dime_Rate=$dx_rate[1]; // 客队
            $MB_Dime_Rate=change_rate($open,$MB_Dime_Rate);
            $TG_Dime_Rate=change_rate($open,$TG_Dime_Rate);

            $S_Single_Rate=change_rate($open,$future_r_data[$i]['S_Single_Rate']);
            $S_Double_Rate=change_rate($open,$future_r_data[$i]['S_Double_Rate']);

            $MB_Win_Rate_H=change_rate($open,$future_r_data[$i]["MB_Win_Rate_H"]); // 独赢主队
            $TG_Win_Rate_H=change_rate($open,$future_r_data[$i]["TG_Win_Rate_H"]); // 独赢客队
            $M_Flat_Rate_H=change_rate($open,$future_r_data[$i]["M_Flat_Rate_H"]); // 独赢和局

            // 半场让球单独处理
            $h_ra_rate=get_other_ioratio(GAME_POSITION,$future_r_data[$i]["MB_LetB_Rate_H"],$future_r_data[$i]["TG_LetB_Rate_H"],100); // 默认都是香港盘
            $MB_LetB_Rate_H=$h_ra_rate[0]; //半场-让球 主队赢
            $TG_LetB_Rate_H=$h_ra_rate[1]; //半场-让球 客队赢
            $MB_LetB_Rate_H=change_rate($open,$MB_LetB_Rate_H);
            $TG_LetB_Rate_H=change_rate($open,$TG_LetB_Rate_H);
            // 半场大小单独处理
            $h_dx_rate=get_other_ioratio(GAME_POSITION,$future_r_data[$i]["MB_Dime_Rate_H"],$future_r_data[$i]["TG_Dime_Rate_H"],100); // 默认都是香港盘
            $MB_Dime_Rate_H=$h_dx_rate[0]; // 主队
            $TG_Dime_Rate_H=$h_dx_rate[1]; // 客队
            $MB_Dime_Rate_H=change_rate($open,$MB_Dime_Rate_H);
            $TG_Dime_Rate_H=change_rate($open,$TG_Dime_Rate_H);

            if ($future_r_data[$i]['HPD_Show']==1 and $future_r_data[$i]['PD_Show']==1 and $future_r_data[$i]['T_Show']==1 and $future_r_data[$i]['F_Show']==1){
                $show=4;
            }else if ($future_r_data[$i]['PD_Show']==1 and $future_r_data[$i]['T_Show']==1 and $future_r_data[$i]['F_Show']==1){
                $show=3;
            }else{
                $show=0;
            }
            $m_date=strtotime($future_r_data[$i]['M_Date']);
            $dates=date("m-d",$m_date);
            if ($future_r_data[$i]['M_Type']==1){
                $Running="<br><font color=red>滾球</font>";
            }else{
                $Running="";
            }
            $allMethods=$future_r_data[$i][more]<5 ? 0:$future_r_data[$i][more];
            $MID = $future_r_data[$i]['MID'];
            $MB_Team = $future_r_data[$i]['MB_Team'];
            $ShowTypeR = $future_r_data[$i]['ShowTypeR'];
            $MB_Dime = $future_r_data[$i]['MB_Dime'];
            $TG_Dime = $future_r_data[$i]['TG_Dime'];
            $M_LetB_H = $future_r_data[$i]['M_LetB_H'];
            $MB_Dime_H = $future_r_data[$i]['MB_Dime_H'];
            $TG_Dime_H = $future_r_data[$i]['TG_Dime_H'];

            if($ShowTypeR=="H"){
                $ratio_mb_str=$M_LetB;
                $ratio_tg_str='';
                $hratio_mb_str=$M_LetB_H;
                $hratio_tg_str='';
            }elseif($ShowTypeR=="C"){
                $ratio_mb_str='';
                $ratio_tg_str=$M_LetB;
                $hratio_mb_str='';
                $hratio_tg_str=$M_LetB_H;
            }
            $MB_Team=str_replace("[Mid]","<font color=\'#005aff\'>[N]</font>",$MB_Team);
            $MB_Team=str_replace("[中]","<font color=\'#005aff\'>[中]</font>",$MB_Team);
            $pos = strpos($future_r_data['M_League'],'电竞足球');
            $pos_zh_tw = strpos($future_r_data['M_League'],'電競足球');
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
            $newDataArray[$MID]['gid']=$MID;
            $newDataArray[$MID]['dategh']=date('m-d').$future_r_data[$i]['MB_MID'];
            $newDataArray[$MID]['datetime']=$dates.'<br>'.$future_r_data[$i]['M_Time'].$Running;
            $newDataArray[$MID]['datetimelove']=date('m-d')."<br>".$future_r_data[$i][M_Time];
            $newDataArray[$MID]['league']=$future_r_data[$i]['M_League'];
            $newDataArray[$MID]['gnum_h']=$future_r_data[$i]['MB_MID'];
            $newDataArray[$MID]['gnum_c']=$future_r_data[$i]['TG_MID'];
            $newDataArray[$MID]['team_h']=$MB_Team;
            $newDataArray[$MID]['team_c']=$future_r_data[$i]['TG_Team'];
            $newDataArray[$MID]['strong']=$ShowTypeR;
            $newDataArray[$MID]['ratio'] =$future_r_data[$i]['M_LetB'];
            $newDataArray[$MID]['ratio_mb_str']=$ratio_mb_str;
            $newDataArray[$MID]['ratio_tg_str']=$ratio_tg_str;
            $newDataArray[$MID]['ior_RH']= $MB_LetB_Rate;
            $newDataArray[$MID]['ior_RC']=$TG_LetB_Rate;
            $newDataArray[$MID]['bet_RH']="gid={$MID}&uid={$uid}&odd_f_type=H&type=H&gnum={$MB_MID}&strong={$ShowTypeR}&langx={$langx}";
            $newDataArray[$MID]['bet_RC']="gid={$MID}&uid={$uid}&odd_f_type=H&type=C&gnum={$TG_MID}&strong={$ShowTypeR}&langx={$langx}";
            $newDataArray[$MID]['ratio_o']=$MB_Dime;
            $newDataArray[$MID]['ratio_u']=$TG_Dime;
            $newDataArray[$MID]['ratio_o_str']="大".str_replace('O','',$MB_Dime);
            $newDataArray[$MID]['ratio_u_str']="小".str_replace('U','',$TG_Dime);
            $newDataArray[$MID]['ior_OUH']=$TG_Dime_Rate;
            $newDataArray[$MID]['ior_OUC']=$MB_Dime_Rate;
            $newDataArray[$MID]['bet_OUH']="gid={$MID}&uid={$uid}&odd_f_type=H&type=C&gnum={$MB_MID}&langx={$langx}";
            $newDataArray[$MID]['bet_OUC']="gid={$MID}&uid={$uid}&odd_f_type=H&type=H&gnum={$TG_MID}&langx={$langx}";
            $newDataArray[$MID]['ior_MH']=$MB_Win_Rate;
            $newDataArray[$MID]['ior_MC']=$TG_Win_Rate;
            $newDataArray[$MID]['ior_MN']=$M_Flat_Rate;
            $newDataArray[$MID]['bet_MH']="gid={$MID}&uid={$uid}&odd_f_type=H&type=H&gnum={$MB_MID}&strong={$ShowTypeR}&langx={$langx}";
            $newDataArray[$MID]['bet_MC']="gid={$MID}&uid={$uid}&odd_f_type=H&type=C&gnum={$TG_MID}&strong={$ShowTypeR}&langx={$langx}";
            $newDataArray[$MID]['bet_MN']="gid={$MID}&uid={$uid}&odd_f_type=H&type=N&gnum={$TG_MID}&strong={$ShowTypeR}&langx={$langx}";
            $newDataArray[$MID]['str_odd']=$o;
            $newDataArray[$MID]['str_even']=$e;
            $newDataArray[$MID]['ior_EOO']=$S_Single_Rate;
            $newDataArray[$MID]['ior_EOE']=$S_Double_Rate;
            $newDataArray[$MID]['bet_EOO']="gid={$MID}&uid={$uid}&odd_f_type=H&rtype=ODD&langx={$langx}";
            $newDataArray[$MID]['bet_EOE']="gid={$MID}&uid={$uid}&odd_f_type=H&rtype=EVEN&langx={$langx}";
            $newDataArray[$MID]['hgid']= $MID;
            $newDataArray[$MID]['hstrong']=$future_r_data[$i]['ShowTypeHR'];
            $newDataArray[$MID]['hratio']=$M_LetB_H;
            $newDataArray[$MID]['hratio_mb_str']=$hratio_mb_str;
            $newDataArray[$MID]['hratio_tg_str']=$hratio_tg_str;
            $newDataArray[$MID]['ior_HRH']=$MB_LetB_Rate_H;
            $newDataArray[$MID]['ior_HRC']=$TG_LetB_Rate_H;
            $newDataArray[$MID]['hratio_o']=$MB_Dime_H;
            $newDataArray[$MID]['hratio_u']=$TG_Dime_H;
            $newDataArray[$MID]['hratio_o_str']="大".str_replace('O','',$MB_Dime_H);
            $newDataArray[$MID]['hratio_u_str']="小".str_replace('U','',$TG_Dime_H);
            $newDataArray[$MID]['ior_HOUH']=$TG_Dime_Rate_H;
            $newDataArray[$MID]['ior_HOUC']=$MB_Dime_Rate_H;
            $newDataArray[$MID]['ior_HMH']=$MB_Win_Rate_H;
            $newDataArray[$MID]['ior_HMC']=$TG_Win_Rate_H;
            $newDataArray[$MID]['ior_HMN']=$M_Flat_Rate_H;
            $newDataArray[$MID]['more']=$show;
            $newDataArray[$MID]['all']=$allMethods;
            $newDataArray[$MID]['eventid']=$future_r_data[$i]['Eventid'];
            $newDataArray[$MID]['hot'] = $future_r_data[$i]['Hot'];
            $newDataArray[$MID]['play'] = $future_r_data[$i]['Play'];
            $K=$K+1;
            $page_gamecount ++ ;
        }

    }
    echo "parent.gamount=$page_gamecount;\n";
    $listTitle="早盘足球";
    $leagueNameCur='';
	break;
case "pd": // 波胆全场
    $table='<tr>
              <th nowrap class="ft_pd_h">'.$U_061.'</th>
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
                    <td colspan="20">'.$U_07.'<span class="maxbet">'.$U_11.' RMB 1,000,000.00</span> </td>
                </tr>';
        $table_dif='bd_all pd_table' ;// 波胆table 类

        $future_r_data = getFutureData('FUTURE_PD') ; // 数据
        $length = count($future_r_data) ; // 长度
        $page_count=ceil($length/$page_size); // 总共多少页
        $offset=$page_no*60;
	echo "parent.retime=0;\n";
	echo "parent.t_page=$page_count;\n";
	//echo "parent.GameHead = new Array('gid','datetime','league','gnum_h','gnum_c','team_h','team_c','strong','ior_H1C0','ior_H2C0','ior_H2C1','ior_H3C0','ior_H3C1','ior_H3C2','ior_H4C0','ior_H4C1','ior_H4C2','ior_H4C3','ior_H0C0','ior_H1C1','ior_H2C2','ior_H3C3','ior_H4C4','ior_OVH','ior_H0C1','ior_H0C2','ior_H1C2','ior_H0C3','ior_H1C3','ior_H2C3','ior_H0C4','ior_H1C4','ior_H2C4','ior_H3C4','ior_OVC');";
        for($i=$offset;$i<($page_no+1)*$page_size;$i++){
            if($future_r_data[$i]['MB_MID']){ // 防止空数据
                $m_date=strtotime($future_r_data[$i]['M_Date']);
                $dates=date("m-d",$m_date);
                $MB_Team=$future_r_data[$i]['MB_Team'];
                $MB_Team=str_replace("[Mid]","<font color=\'#005aff\'>[N]</font>",$MB_Team);
                $MB_Team=str_replace("[中]","<font color=\'#005aff\'>[中]</font>",$MB_Team);
                $MID=$future_r_data[$i]['MID'];
                $newDataArray[$MID]['gid']=$MID;
                $newDataArray[$MID]['dategh']=date('m-d').$future_r_data[$i]['MB_MID'];
                $newDataArray[$MID]['datetime']=$dates.'<br>'.$future_r_data[$i]['M_Time'];
                $newDataArray[$MID]['league']=$future_r_data[$i]['M_League'];
                $newDataArray[$MID]['gnum_h']=$future_r_data[$i]['MB_MID'];
                $newDataArray[$MID]['gnum_c']=$future_r_data[$i]['TG_MID'];
                $newDataArray[$MID]['team_h']=$MB_Team;
                $newDataArray[$MID]['team_c']=$future_r_data[$i]['TG_Team'];
                $newDataArray[$MID]['strong']=$future_r_data[$i]['ShowTypeR'];
                $newDataArray[$MID]['ior_H1C0']=change_rate($open,$future_r_data[$i]['MB1TG0']);
                $newDataArray[$MID]['ior_H2C0']=change_rate($open,$future_r_data[$i]['MB2TG0']);
                $newDataArray[$MID]['ior_H2C1']=change_rate($open,$future_r_data[$i]['MB2TG1']);
                $newDataArray[$MID]['ior_H3C0']=change_rate($open,$future_r_data[$i]['MB3TG0']);
                $newDataArray[$MID]['ior_H3C1']=change_rate($open,$future_r_data[$i]['MB3TG1']);
                $newDataArray[$MID]['ior_H3C2']=change_rate($open,$future_r_data[$i]['MB3TG2']);
                $newDataArray[$MID]['ior_H4C0']=change_rate($open,$future_r_data[$i]['MB4TG0']);
                $newDataArray[$MID]['ior_H4C1']=change_rate($open,$future_r_data[$i]['MB4TG1']);
                $newDataArray[$MID]['ior_H4C2']=change_rate($open,$future_r_data[$i]['MB4TG2']);
                $newDataArray[$MID]['ior_H4C3']=change_rate($open,$future_r_data[$i]['MB4TG3']);
                $newDataArray[$MID]['ior_H0C0']=change_rate($open,$future_r_data[$i]['MB0TG0']);
                $newDataArray[$MID]['ior_H1C1']=change_rate($open,$future_r_data[$i]['MB1TG1']);
                $newDataArray[$MID]['ior_H2C2']=change_rate($open,$future_r_data[$i]['MB2TG2']);
                $newDataArray[$MID]['ior_H3C3']=change_rate($open,$future_r_data[$i]['MB3TG3']);
                $newDataArray[$MID]['ior_H4C4']=change_rate($open,$future_r_data[$i]['MB4TG4']);
                $newDataArray[$MID]['ior_OVH' ]=change_rate($open,$future_r_data[$i]['UP5']);
                $newDataArray[$MID]['ior_H0C1']=change_rate($open,$future_r_data[$i]['MB0TG1']);
                $newDataArray[$MID]['ior_H0C2']=change_rate($open,$future_r_data[$i]['MB0TG2']);
                $newDataArray[$MID]['ior_H1C2']=change_rate($open,$future_r_data[$i]['MB1TG2']);
                $newDataArray[$MID]['ior_H0C3']=change_rate($open,$future_r_data[$i]['MB0TG3']);
                $newDataArray[$MID]['ior_H1C3']=change_rate($open,$future_r_data[$i]['MB1TG3']);
                $newDataArray[$MID]['ior_H2C3']=change_rate($open,$future_r_data[$i]['MB2TG3']);
                $newDataArray[$MID]['ior_H0C4']=change_rate($open,$future_r_data[$i]['MB0TG4']);
                $newDataArray[$MID]['ior_H1C4']=change_rate($open,$future_r_data[$i]['MB1TG4']);
                $newDataArray[$MID]['ior_H2C4']=change_rate($open,$future_r_data[$i]['MB2TG4']);
                $newDataArray[$MID]['ior_H3C4']=change_rate($open,$future_r_data[$i]['MB3TG4']);
                $newDataArray[$MID]['bet_Url']="gid={$future_r_data[$i]['MID']}&uid={$uid}&odd_f_type=H&langx={$langx}&rtype=";
                $K=$K+1;
                $page_gamecount ++ ;
            }

        }
        echo "parent.gamount=$page_gamecount;\n";
        $listTitle="早盘足球 : 波胆";
        $leagueNameCur='';
	break;
case "hpd": // 波胆半场
    $table='<tr>
			<th nowrap class="ft_pd_h ft_hpd_h">'.$U_061.'</th>
			<th >1:0</th>
			<th >2:0</th>
			<th >2:1</th>
			<th >3:0</th>
			<th >3:1</th>
			<th >3:2</th>
			<th >4:0</th>
			<th >4:1</th>
			<th >4:2</th>
			<th >4:3</th>
			<th >0:0</th>
			<th >1:1</th>
			<th >2:2</th>
			<th >3:3</th>
			<th >4:4</th>
			<th >'.$Others.'</th>
		    <tr>
            <tr class="bet_correct_title">
                <td colspan="20">'.$U_07.'<span class="maxbet">'.$U_11.' RMB 1,000,000.00</span></td>
            </tr>';
    $table_dif='bd_all pd_table' ;// 波胆table 类

        $future_r_data = getFutureData('FUTURE_HPD') ; // 数据
        $length = count($future_r_data) ; // 长度
        $page_count=ceil($length/$page_size); // 总共多少页
        $offset=$page_no*60;
	echo "parent.t_page=$page_count;\n";
	//echo "parent.gamount=$cou;\n";
	//echo "parent.GameHead = new Array('gid','datetime','league','gnum_h','gnum_c','team_h','team_c','strong','ior_H1C0','ior_H2C0','ior_H2C1','ior_H3C0','ior_H3C1','ior_H3C2','ior_H4C0','ior_H4C1','ior_H4C2','ior_H4C3','ior_H0C0','ior_H1C1','ior_H2C2','ior_H3C3','ior_H4C4','ior_OVH','ior_H0C1','ior_H0C2','ior_H1C2','ior_H0C3','ior_H1C3','ior_H2C3','ior_H0C4','ior_H1C4','ior_H2C4','ior_H3C4','ior_OVC');" ;
        for($i=$offset;$i<($page_no+1)*$page_size;$i++){
            if($future_r_data[$i]['MB_MID']){ // 防止空数据
                $m_date=strtotime($future_r_data[$i]['M_Date']);
                $dates=date("m-d",$m_date);
                $MID = $future_r_data[$i]['MID'] ;
                $newDataArray[$MID]['gid']=$MID;
                $newDataArray[$MID]['datetime']=$dates.'<br>'.$future_r_data[$i]['M_Time'];
                $newDataArray[$MID]['dategh']=date('m-d').$future_r_data[$i]['MB_MID'];
                $newDataArray[$MID]['league']=$future_r_data[$i]['M_League'];
                $newDataArray[$MID]['gnum_h']=$future_r_data[$i]['MB_MID'];
                $newDataArray[$MID]['gnum_c']=$future_r_data[$i]['TG_MID'];
                $newDataArray[$MID]['team_h']=$future_r_data[$i]['MB_Team'].'<font color=gray> - ['.$Order_1st_Half.']</font>';
                $newDataArray[$MID]['team_c']=$future_r_data[$i]['TG_Team'].'<font color=gray> - ['.$Order_1st_Half.']</font>';
                $newDataArray[$MID]['strong']=$future_r_data[$i]['ShowTypeR'];
                $newDataArray[$MID]['ior_H1C0'] =change_rate($open,$future_r_data[$i]['MB1TG0H']);
                $newDataArray[$MID]['ior_H2C0'] =change_rate($open,$future_r_data[$i]['MB2TG0H']);
                $newDataArray[$MID]['ior_H2C1'] =change_rate($open,$future_r_data[$i]['MB2TG1H']);
                $newDataArray[$MID]['ior_H3C0'] =change_rate($open,$future_r_data[$i]['MB3TG0H']);
                $newDataArray[$MID]['ior_H3C1'] =change_rate($open,$future_r_data[$i]['MB3TG1H']);
                $newDataArray[$MID]['ior_H3C2'] =change_rate($open,$future_r_data[$i]['MB3TG2H']);
                $newDataArray[$MID]['ior_H0C0'] =change_rate($open,$future_r_data[$i]['MB0TG0H']);
                $newDataArray[$MID]['ior_H1C1'] =change_rate($open,$future_r_data[$i]['MB1TG1H']);
                $newDataArray[$MID]['ior_H2C2'] =change_rate($open,$future_r_data[$i]['MB2TG2H']);
                $newDataArray[$MID]['ior_H3C3'] =change_rate($open,$future_r_data[$i]['MB3TG3H']);
                $newDataArray[$MID]['ior_OVH']  = change_rate($open,$future_r_data[$i]['UP5H']);
                $newDataArray[$MID]['ior_H0C1'] =change_rate($open,$future_r_data[$i]['MB0TG1H']);
                $newDataArray[$MID]['ior_H0C2'] =change_rate($open,$future_r_data[$i]['MB0TG2H']);
                $newDataArray[$MID]['ior_H1C2'] =change_rate($open,$future_r_data[$i]['MB1TG2H']);
                $newDataArray[$MID]['ior_H0C3'] =change_rate($open,$future_r_data[$i]['MB0TG3H']);
                $newDataArray[$MID]['ior_H1C3'] =change_rate($open,$future_r_data[$i]['MB1TG3H']);
                $newDataArray[$MID]['ior_H2C3'] =change_rate($open,$future_r_data[$i]['MB2TG3H']);
                $newDataArray[$MID]['bet_Url']="gid={$future_r_data[$i]['MID']}&uid={$uid}&odd_f_type=H&langx={$langx}&rtype=";
                $K=$K+1;
                $page_gamecount ++ ;
            }

        }
        echo "parent.gamount=$page_gamecount;\n";
        $listTitle="早盘足球 : 波胆";
        $leagueNameCur='';
	break;

case "t": // 总入球
    $table='<tr>
              <th nowrap class="h_1x1">'.$U_061.'</th>
              <th class="h_1x170"></th>
             
              <th class="h_oe">0 - 1</th>
              <th class="h_oe">2 - 3</th>
              <th class="h_oe">4 - 6</th>
              <th class="h_oe">7up</th>
            </tr>
              <tr class="bet_correct_title">
              <td colspan="20">'.$U_08.'</td>
              </tr>';
    $table_dif='bd_all' ;// 波胆table 类

        $future_r_data = getFutureData('FUTURE_T') ; // 数据
        $length = count($future_r_data) ; // 长度
        $page_count=ceil($length/$page_size); // 总共多少页
        $offset=$page_no*60;
	echo "parent.retime=0;\n";
	echo "parent.t_page=$page_count;\n";
	//echo "parent.gamount=$cou;\n";
  // echo "parent.GameHead = new Array('gid','datetime','league','gnum_h','gnum_c','team_h','team_c','strong','ior_ODD','ior_EVEN','ior_T01','ior_T23','ior_T46','ior_OVER','ior_MH','ior_MC','ior_MN');";
        for($i=$offset;$i<($page_no+1)*$page_size;$i++){
            if($future_r_data[$i]['MB_MID']){ // 防止空数据
                $MB_Team=$future_r_data[$i]['MB_Team'];
                $MB_Team=str_replace("[Mid]","<font color=\'#005aff\'>[N]</font>",$MB_Team);
                $MB_Team=str_replace("[中]","<font color=\'#005aff\'>[中]</font>",$MB_Team);
                $MID = $future_r_data[$i]['MID'] ;
                $m_date=strtotime($future_r_data[$i]['M_Date']);
                $dates=date("m-d",$m_date);
                $newDataArray[$MID]['gid']=$MID;
                $newDataArray[$MID]['dategh']=date('m-d').$future_r_data[$i]['MB_MID'];
                $newDataArray[$MID]['datetime']=$dates.'<br>'.$future_r_data[$i]['M_Time'];
                $newDataArray[$MID]['league']=$future_r_data[$i]['M_League'];
                $newDataArray[$MID]['gnum_h']=$future_r_data[$i]['MB_MID'];
                $newDataArray[$MID]['gnum_c']=$future_r_data[$i]['TG_MID'];
                $newDataArray[$MID]['team_h']=$MB_Team;
                $newDataArray[$MID]['team_c']=$future_r_data[$i]['TG_Team'];
                $newDataArray[$MID]['strong']=$future_r_data[$i]['ShowTypeR'];
                $newDataArray[$MID]['ior_T01']=change_rate($open,$future_r_data[$i]['S_0_1']);
                $newDataArray[$MID]['ior_T23']=change_rate($open,$future_r_data[$i]['S_2_3']);
                $newDataArray[$MID]['ior_T46']=change_rate($open,$future_r_data[$i]['S_4_6']);
                $newDataArray[$MID]['ior_OVER']=change_rate($open,$future_r_data[$i]['S_7UP']);
                $newDataArray[$MID]['bet_Url']="gid={$MID}&uid={$uid}&odd_f_type=H&langx={$langx}&rtype=";
                $K=$K+1;
                $page_gamecount ++ ;
            }

        }
        echo "parent.gamount=$page_gamecount;\n";
        $listTitle="早盘足球 : 总入球";
        $leagueNameCur='';
	break;	
case "f": // 半场/全场
    $table='<tr>
			<th nowrap class="h_1x1">'.$U_061.'</th>
			<th class="h_1x170"></th>
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

        $future_r_data = getFutureData('FUTURE_F') ; // 数据
        $length = count($future_r_data) ; // 长度
        $page_count=ceil($length/$page_size); // 总共多少页
        $offset=$page_no*60;
	echo "parent.retime=0;\n";
	echo "parent.t_page=$page_count;\n";
	//echo "parent.gamount=$cou;\n";
//	echo "parent.GameHead = new Array('gid','datetime','league','gnum_h','gnum_c','team_h','team_c','strong','ior_FHH','ior_FHN','ior_FHC','ior_FNH','ior_FNN','ior_FNC','ior_FCH','ior_FCN','ior_FCC');";
        for($i=$offset;$i<($page_no+1)*$page_size;$i++){
            if($future_r_data[$i]['MB_MID']){ // 防止空数据
                $MID = $future_r_data[$i]['MID'] ;
                $m_date=strtotime($future_r_data[$i]['M_Date']);
                $dates=date("m-d",$m_date);
                $MB_Team=$future_r_data[$i]['MB_Team'];
                $MB_Team=str_replace("[Mid]","<font color=\'#005aff\'>[N]</font>",$MB_Team);
                $MB_Team=str_replace("[中]","<font color=\'#005aff\'>[中]</font>",$MB_Team);
                $newDataArray[$MID]['gid']=$MID;
                $newDataArray[$MID]['dategh']=date('m-d').$future_r_data[$i]['MB_MID'];
                $newDataArray[$MID]['datetime']=$dates.'<br>'.$future_r_data[$i]['M_Time'];
                $newDataArray[$MID]['league']=$future_r_data[$i]['M_League'];
                $newDataArray[$MID]['gnum_h']=$future_r_data[$i]['MB_MID'];
                $newDataArray[$MID]['gnum_c']=$future_r_data[$i]['TG_MID'];
                $newDataArray[$MID]['team_h']=$MB_Team;
                $newDataArray[$MID]['team_c']=$future_r_data[$i]['TG_Team'];
                $newDataArray[$MID]['strong']=$future_r_data[$i]['ShowTypeR'];
                $newDataArray[$MID]['ior_FHH']=change_rate($open,$future_r_data[$i]['MBMB']);
                $newDataArray[$MID]['ior_FHN']=change_rate($open,$future_r_data[$i]['MBFT']);
                $newDataArray[$MID]['ior_FHC']=change_rate($open,$future_r_data[$i]['MBTG']);
                $newDataArray[$MID]['ior_FNH']=change_rate($open,$future_r_data[$i]['FTMB']);
                $newDataArray[$MID]['ior_FNN']=change_rate($open,$future_r_data[$i]['FTFT']);
                $newDataArray[$MID]['ior_FNC']=change_rate($open,$future_r_data[$i]['FTTG']);
                $newDataArray[$MID]['ior_FCH']=change_rate($open,$future_r_data[$i]['TGMB']);
                $newDataArray[$MID]['ior_FCN']=change_rate($open,$future_r_data[$i]['TGFT']);
                $newDataArray[$MID]['ior_FCC']=change_rate($open,$future_r_data[$i]['TGTG']);
                $newDataArray[$MID]['bet_Url']="gid={$MID}&uid={$uid}&odd_f_type=H&langx={$langx}&rtype=";
                $K=$K+1;
                $page_gamecount ++ ;
            }

        }
        echo "parent.gamount=$page_gamecount;\n";
        $listTitle="早盘足球 : 半场 /全场";
        $leagueNameCur='';
	break;

case "p3": // 综合过关
    $table='<tr>
              <th nowrap class="h_1x1">'.$U_061.'</th>
              <th class="h_1x170"></th>
              <th nowrap class="h_1x2">'.$WIN.'</th>
              <th nowrap class="h_r">'.$U_02.'</th>
              <th nowrap class="h_ou">'.$U_03.'</th>
              <th class="h_oe">'.$O_E.'</th>
              <th nowrap class="h_1x2">'.$WIN.'</th>
              <th nowrap class="h_r">'.$U_04.'</th>
              <th nowrap class="h_ou">'.$U_05.'</th>
            </tr>
             <tr class="bet_correct_title">
                <td colspan="20">'.$U_10. ' <span class="maxbet">'.$U_11.'  ： RMB 1,000,000.00</span></td>
            </tr>';
    $table_dif='bd_all pd_table' ;// 波胆table 类

    $resulTotal=getP3Matches("FUTURE_FT_P3");
    $cou=count($resulTotal);
    echo "parent.retime=0;\n";
	echo "parent.game_more=1;\n";
	echo "parent.str_more='$more';\n";
	echo "parent.gamount=$cou;\n";
	$page_count=intval($cou/$page_size);
	echo "parent.t_page=$page_count;";
	// 综合过关  RATIO_HMH ior_HPMH 独赢主队 ， RATIO_HMC ior_HPMC 独赢客队 ，RATIO_HMN ior_HPMN 独赢和局
   // echo "parent.GameHead = new Array('gid','datetime','league','gnum_h','gnum_c','team_h','team_c','strong','ratio','ior_PRH','ior_PRC','ratio_o','ratio_u','ior_POUC','ior_POUH','ior_PO','ior_PE','ior_MH','ior_MC','ior_MN','ior_H1C0','ior_H2C0','ior_H2C1','ior_H3C0','ior_H3C1','ior_H3C2','ior_H4C0','ior_H4C1','ior_H4C2','ior_H4C3','ior_H0C0','ior_H1C1','ior_H2C2','ior_H3C3','ior_H4C4','ior_OVH','ior_H0C1','ior_H0C2','ior_H1C2','ior_H0C3','ior_H1C3','ior_H2C3','ior_H0C4','ior_H1C4','ior_H2C4','ior_H3C4','ior_OVC','ior_T01','ior_T23','ior_T46','ior_OVER','ior_FHH','ior_FHN','ior_FHC','ior_FNH','ior_FNN','ior_FNC','ior_FCH','ior_FCN','ior_FCC','hgid','hstrong','hratio','ior_HPRH','ior_HPRC','hratio_o','hratio_u','ior_HPOUH','ior_HPOUC','ior_HH1C0','ior_HH2C0','ior_HH2C1','ior_HH3C0','ior_HH3C1','ior_HH3C2','ior_HH4C0','ior_HH4C1','ior_HH4C2','ior_HH4C3','ior_HH0C0','ior_HH1C1','ior_HH2C2','ior_HH3C3','ior_HH4C4','ior_HOVH','ior_HH0C1','ior_HH0C2','ior_HH1C2','ior_HH0C3','ior_HH1C3','ior_HH2C3','ior_HH0C4','ior_HH1C4','ior_HH2C4','ior_HH3C4','ior_HOVC','ior_HPMH','ior_HPMC','ior_HPMN','more','gidm','par_minlimit','par_maxlimit');";
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
    $listTitle= ($showtype=='future')?'早盘足球 : 综合过关':'今日足球 : 综合过关';
    $leagueNameCur='';
	break;
}
?>

//function onLoad(){
//	if(parent.parent.mem_order.location == 'about:blank')
//		parent.parent.mem_order.location = '<?php //echo BROWSER_IP?>///app/member/select.php?uid=<?php //echo $uid?>//&langx=<?php //echo $langx?>//';
//
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
//}
 

</script>
</head>
<body onLoad="onLoad()" class="load_body_var <?php echo $table_dif?>">
<!--<div id="controlscroll"><table border="0" cellspacing="0" cellpadding="0" class="loadBox"><tr><td> </td></tr></table></div>-->

<!-- 球赛展示区顶部 开始-->
<div class="bet_head">
    <!--左侧按钮-->
    <div class="bet_left">
        <?php

        if($rtype=='r'){ // 全部才有
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
        if($rtype=='pd'){ // 只有波胆才有
            echo '<span class="bet_game_btn" onclick="chg_wtype(\'hpd\');"><tt class="bet_normal_text" >半场</tt></span>' ;
        }else if($rtype=='hpd'){
            echo '<span class="bet_game_btn" onclick="chg_wtype(\'pd\');"><tt class="bet_normal_text" >全场</tt></span>' ;
        }
        ?>

        <?php
        if($rtype=='r' or $rtype=='r_main'){ // 全部盘口、主要盘口
            ?>
            <span id="sel_Market" class="bet_view_btn" onclick="chgMarket('main');" style="display: <?php if($rtype=='r'){echo 'inline-block';}else{echo 'none';}?>;"><tt id="SpanMarket" class="bet_normal_text">主要盘口</tt></span>
            <span id="all_sel_Market" class="bet_view_btn" onclick="chgMarket('all');" style="display: <?php if($rtype=='r_main'){echo 'inline-block';}else{echo 'none';}?>;"><tt id="all_SpanMarket" class="bet_normal_text">全部盘口</tt></span>
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
        if($rtype=='r'){ // 只有全部才有
            echo '<span id="sel_odd" class="bet_odds_btn"> <tt id="chose_odd" class="bet_normal_text">香港盘</tt></span>' ;
        }
        ?>

        <span class="bet_time_btn" onclick="javascript:reload_var()">
            <tt id="refreshTime" class="bet_time_text"><?php echo $U_14 ?></tt>
        </span>

    </div>

</div>
<!-- 球赛展示区顶部 结束-->

<!-- 日期 -->
<div id="show_date_opt" class="bet_title_date">
    <?php
        $date=date("Y-m-d");
        $date1=date('Y-m-d',time()+24*60*60);
        $date2=date('Y-m-d',time()+2*24*60*60);
        $date3=date('Y-m-d',time()+3*24*60*60);
        $date4=date('Y-m-d',time()+4*24*60*60);
        $date5=date('Y-m-d',time()+5*24*60*60);
        $date6=date('Y-m-d',time()+6*24*60*60);
        $date7=date('Y-m-d',time()+7*24*60*60);
        $date8=date('Y-m-d',time()+8*24*60*60);
        $date9=date('Y-m-d',time()+9*24*60*60);
        $date10=date('Y-m-d',time()+10*24*60*60);

    for($datei=1;$datei<10;$datei++){
        $dateNowValue=date('Y-m-d',time()+$datei*24*60*60);
        $dateNowStr=date('m'.'月'.'d'.'日',time()+$datei*24*60*60);
        echo "<span value='$dateNowValue' onclick='chg_gdate(this)' class='".($g_date==$dateNowValue?'bet_date_color':'')."'>$dateNowStr</span>";
    }
    echo "<span value='ALL' onclick='chg_gdate(this)' class='".($g_date=='ALL'?'bet_date_color':'')."'>全部</span>";

    ?>
</div>

<table border="0" cellpadding="0" cellspacing="0" id="myTable" class="bet_game_table bk_top">
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
                                            include "../FT_browse/body_m_r_ou_eo.php";
                                            break;
                                        case "pd":	include "../FT_browse/body_pd.php";break;
                                        case "hpd":	include "../FT_browse/body_hpd.php";break;
                                        case "t":	include "../FT_browse/body_t.php";break;
                                        case "f":	include "../FT_browse/body_f.php";break;
                                        case "p3":	include "body_p3.php";break;
                                    }
                                }
                                ?>
                                </tbody>
                            </table>

                        </div>
                    </td>
                </tr>

            </table>

        </td></tr>
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