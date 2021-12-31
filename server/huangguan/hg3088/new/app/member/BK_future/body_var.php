<?php
session_start();
header("Expires: Mon, 26 Jul 1970 00:00:00 GMT");
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
$seachwhere = "(M_League LIKE '%$leaname%' or MB_Team  LIKE '%$leaname%' or TG_Team  LIKE '%$leaname%')"; // 用于模糊搜索
require ("../include/traditional.$langx.inc.php");

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
	echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
	exit;
}

$open=$_SESSION['OpenType'];
$memname=$_SESSION['UserName'];
$pay_type=$_SESSION['Pay_Type'];

if ($league_id==''){
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
    global $redisObj,$leaname;
	$returnData = $redisObj->getSimpleOne($type);
    $returnData = json_decode($returnData,true) ; // 有true 参数返回数组，没有返回对象 object
    if(isset($g_date) && $g_date=="ALL"){ // 全部日期
        if(isset($leaname)&&strlen($leaname)>0){
            foreach( $returnData as $key=>$val ){
                if(strpos($val['MB_Team'],$leaname)>-1 || strpos($val['TG_Team'],$leaname)>-1 || strpos($val['M_League'],$leaname)>-1){
                    $returnDataNew[]=$val;
                }
            }
            return $returnDataNew;
        }else{
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
        return $returnDataNew ;
    }
    // $returnData = json_decode($returnData,true) ;
    return $returnData ;
}

$gametitle = '早盘蓝球和美式足球';
$bk_dx_title ='球队得分：大/小' ;

?>
<head>
<TITLE>篮球變數值</TITLE>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
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
parent.minlimit_VAR='';
parent.maxlimit_VAR='';
parent.username='<?php echo $memname?>';

parent.code='人民幣(RMB)';
parent.uid='<?php echo $uid?>';

parent.ltype='3';
parent.str_even = '<?php echo $str_even?>';
parent.str_submit = '<?php echo $str_submit?>';
parent.str_reset = '<?php echo $str_reset?>';
parent.rtype='<?php echo $rtype?>';
parent.sel_lid='<?php echo $league_id?>';
parent.langx='<?php echo $langx?>';
parent.g_date = 'ALL';

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
case "r":
case "r_main":
    $table='<tr>
		    <th class="bk_future_h" colspan="2">'.$gametitle.'</th>	
		    <th nowrap class="h_1x2">'.$WIN.'</th>
		    <th  class="bk_h_r_rq">'.$Handicap.'</th>
		    <th  class="bk_h_r_dx">'.$Over_Under.'</th>
		    <th>'.$OE.'</th>  <!--单双-->
		    <th class="h_ouhc" colspan="2">'.$bk_dx_title.'</th>			
			</tr>';
    if($sorttype == 'league'){// 按照联盟排序
        $future_r_data_total = getFutureData('FUTURE_BK_ALL') ; // 数据
        foreach ($future_r_data_total as $k => $v){
            $future_r_data_total[$k]['M_League_Initials'] = _getFirstCharter(tradition2simple($v['M_League']));
        }
        $future_r_data = array_values(array_sort($future_r_data_total,'M_League_Initials',$type='asc'));
        // 联盟相同的归成一类
//    $resulTotal = group_same_key($resulTotal,'M_League');
    }
    else{
        $future_r_data = getFutureData('FUTURE_BK_ALL') ; // 数据
    }
    $length = count($future_r_data) ; // 长度
    $page_count=ceil($length/$page_size); // 总共多少页
    $offset=$page_no*60;
	echo "parent.retime=180;\n";
	echo "parent.str_renew = '$second_auto_update';\n";
	echo "parent.t_page=$page_count;\n";

//	echo "parent.GameHead = new Array('gid','datetime','league','gnum_h','gnum_c','team_h','team_c','strong','ratio','ior_RH','ior_RC','ratio_o','ratio_u','ior_OUH','ior_OUC','ior_MH','ior_MC',
//	'str_odd','str_even','ior_EOO','ior_EOE','ratio_ouho','ratio_ouhu','ior_OUHO','ior_OUHU','ratio_ouco','ratio_oucu','ior_OUCO','ior_OUCU','more','eventid','hot','play','all');";

    for($i=$offset;$i<($page_no+1)*$page_size;$i++){
        if($future_r_data[$i]['MB_MID']){ // 防止空数据
            $M_Flat_Rate=change_rate($open,$future_r_data[$i]["M_Flat_Rate"]); //全场和的赔率
            // MB_Dime_Rate主队全场赔率      TG_Dime_Rate客队全场赔率
            $ra_rate=get_other_ioratio(GAME_POSITION,$future_r_data[$i]["MB_Dime_Rate"],$future_r_data[$i]["TG_Dime_Rate"],100); // 默认都是香港盘
            $MB_Dime_Rate=$ra_rate[0]; // 主队
            $TG_Dime_Rate=$ra_rate[1]; // 客队

            // 全场让球单独处理
            $ra_rate=get_other_ioratio(GAME_POSITION,$future_r_data[$i]["MB_LetB_Rate"],$future_r_data[$i]["TG_LetB_Rate"],100); // 默认都是香港盘
            $MB_LetB_Rate=$ra_rate[0]; // 主队    主队让球赔率
            $TG_LetB_Rate=$ra_rate[1]; // 客队    客队让球赔率
            $ra_rate=get_other_ioratio(GAME_POSITION,$future_r_data[$i]["MB_Dime_Rate_H"],$future_r_data[$i]["MB_Dime_Rate_S_H"],100); // 默认都是香港盘
            $MB_Dime_Rate_H=$ra_rate[0]; // 主队半场大的赔率      主队半场赔率
            $MB_Dime_Rate_S_H=$ra_rate[1]; // 主队半场小的赔率    半场主队独赢小的赔率
            $ra_rate=get_other_ioratio(GAME_POSITION,$future_r_data[$i]["TG_Dime_Rate_H"],$future_r_data[$i]["TG_Dime_Rate_S_H"],100); // 默认都是香港盘
            $TG_Dime_Rate_H=$ra_rate[0]; // 客队半场大的赔率      客队半场赔率
            $TG_Dime_Rate_S_H=$ra_rate[1]; //客队半场小的赔率     半场客队独赢小的赔率

            if($S_Single_Rate==''){
                $Single='';
            }else{
                $Single=$o;
            }
            if($S_Double_Rate==''){
                $Double='';
            }else{
                $Double=$e;
            }

            if($future_r_data[$i]['ShowTypeR']=="H"){
                $ratio_mb_str=$future_r_data[$i]['M_LetB'];
                $ratio_tg_str='';
            }elseif($future_r_data[$i]['ShowTypeR']=="C"){
                $ratio_mb_str='';
                $ratio_tg_str=$future_r_data[$i]['M_LetB'];
            }

            $m_date=strtotime($future_r_data[$i]['M_Date']);
            $dates=date("m-d",$m_date);
            $MID = $future_r_data[$i]['MID'] ;
            $newDataArray[$MID]['gid']=$MID;
            $newDataArray[$MID]['datetime']=$future_r_data[$i]['M_Type']==1?$dates.'<br>'.$future_r_data[$i]['M_Time'].'<br><font color=red>滚球</font>':$dates.'<br>'.$future_r_data[$i]['M_Time'];
            $newDataArray[$MID]['dategh']=date('m-d').$future_r_data[$i]['MB_MID'];
            $newDataArray[$MID]['league']=$future_r_data[$i]['M_League'];
            $newDataArray[$MID]['gnum_h']=$future_r_data[$i]['MB_MID'];
            $newDataArray[$MID]['gnum_c']=$future_r_data[$i]['TG_MID'];
            $newDataArray[$MID]['team_h']=$future_r_data[$i]['MB_Team'];
            $newDataArray[$MID]['team_c']=$future_r_data[$i]['TG_Team'];
            $newDataArray[$MID]['strong']=$future_r_data[$i]['ShowTypeR'];
            $newDataArray[$MID]['ratio']=$future_r_data[$i]['M_LetB'];
            $newDataArray[$MID]['ratio_mb_str']=$ratio_mb_str;
            $newDataArray[$MID]['ratio_tg_str']=$ratio_tg_str;
            $newDataArray[$MID]['ior_RH']=change_rate($open,$MB_LetB_Rate);
            $newDataArray[$MID]['ior_RC']=change_rate($open,$TG_LetB_Rate);
            $newDataArray[$MID]['ratio_o']=$future_r_data[$i]['MB_Dime'];
            $newDataArray[$MID]['ratio_u']=$future_r_data[$i]['TG_Dime'];
            $newDataArray[$MID]['ratio_o_str']="大".str_replace('O','',$future_r_data[$i]['MB_Dime']);
            $newDataArray[$MID]['ratio_u_str']="小".str_replace('U','',$future_r_data[$i]['TG_Dime']);
            $newDataArray[$MID]['ior_OUH']=change_rate($open,$TG_Dime_Rate);
            $newDataArray[$MID]['ior_OUC']=change_rate($open,$MB_Dime_Rate);
            $newDataArray[$MID]['ior_MH']=change_rate($open,$future_r_data[$i]["MB_Win_Rate"]); //主队独赢赔率
            $newDataArray[$MID]['ior_MC']=change_rate($open,$future_r_data[$i]["TG_Win_Rate"]); //客队独赢赔率
            $newDataArray[$MID]['str_odd']=$Single;
            $newDataArray[$MID]['str_even']=$Double;
            $newDataArray[$MID]['ior_EOO']=change_rate($open,$future_r_data[$i]['S_Single_Rate']); // 主队单双赔率
            $newDataArray[$MID]['ior_EOE']=change_rate($open,$future_r_data[$i]['S_Double_Rate']); // 客队单双赔率
            $newDataArray[$MID]['ratio_ouho']=$future_r_data[$i]['MB_Dime_H'];
            $newDataArray[$MID]['ratio_ouhu']=$future_r_data[$i]['MB_Dime_S_H'];
            $newDataArray[$MID]['ratio_ouho_str']=str_replace('O','',$future_r_data[$i]['MB_Dime_H']);
            $newDataArray[$MID]['ratio_ouhu_str']=str_replace('U','',$future_r_data[$i]['MB_Dime_S_H']);
            $newDataArray[$MID]['ior_OUHO']=change_rate($open,$MB_Dime_Rate_H);
            $newDataArray[$MID]['ior_OUHU']=change_rate($open,$MB_Dime_Rate_S_H);
            $newDataArray[$MID]['ratio_ouco']=$future_r_data[$i]['TG_Dime_H'];
            $newDataArray[$MID]['ratio_oucu']=$future_r_data[$i]['TG_Dime_S_H'];
            $newDataArray[$MID]['ratio_ouco_str']=str_replace('O','',$future_r_data[$i]['TG_Dime_H']);
            $newDataArray[$MID]['ratio_oucu_str']=str_replace('U','',$future_r_data[$i]['TG_Dime_S_H']);
            $newDataArray[$MID]['ior_OUCO']=change_rate($open,$TG_Dime_Rate_H);
            $newDataArray[$MID]['ior_OUCU']=change_rate($open,$TG_Dime_Rate_S_H);
            $newDataArray[$MID]['eventid']=$future_r_data[$i]['Eventid'];
            $newDataArray[$MID]['hot']=$future_r_data[$i]['Hot'];
            $newDataArray[$MID]['play']=$future_r_data[$i]['Play'];
            $newDataArray[$MID]['all']=$future_r_data[$i]['more'];
            $newDataArray[$MID]['bet_Url']="gid={$MID}&uid={$uid}&odd_f_type=H&gnum={$future_r_data[$i]['MB_MID']}&langx={$langx}";

            $K=$K+1;
            $page_gamecount ++ ;
        }

    }
    echo "parent.gamount=$page_gamecount;\n";

    $leagueNameCur='';
	break;
    case "p3":  //综合过关
        $table='<tr>
		    <th class="bk_future_h_fu" colspan="2">'.$gametitle.'</th>
            <th nowrap class="h_1x2">'.$WIN.'</th>
		    <th class="bk_h_r_fu">'.$Handicap.'</th>
		    <th class="bk_h_r_fu">'.$Over_Under.'</th>
		    <th class="bk_h_r_ds">'.$OE.'</th>
		    <th class="h_oe" colspan="2">'.$bk_dx_title.'</th>
			</tr> <tr class="bet_correct_title">
                <td colspan="20">'.$U_10. ' <span class="maxbet">'.$U_11.'  ： RMB 3,000,000.00</span></td>   
			</tr>';

        $table_dif='bd_all' ;// 波胆table 类
        $future_r_data = getFutureData('FUTURE_BK_P3') ; // 数据
        $length = count($future_r_data) ; // 长度
        $page_count=ceil($length/$page_size); // 总共多少页
        $offset=$page_no*60;

        echo "parent.retime=0;\n";
        echo "parent.t_page=$page_count;\n";

//        echo "parent.GameHead = new Array('gid','datetime','league','gnum_h','gnum_c','team_h','team_c','strong','ratio','ior_PRH',
//        'ior_PRC','ratio_o','ratio_u','ior_POUC','ior_POUH','str_odd','str_even','ior_EOO','ior_EOE','ior_PO',
//        'ior_PE','ior_PMH','ior_PMC','ior_MN',
//        'hgid','hstrong','hratio','ior_HPRH','ior_HPRC','ior_HPOUH',
//        'ior_HPOUC',
//        'more','gidm','par_minlimit','par_maxlimit','ratio_pouho','ratio_pouhu','ior_POUHO','ior_POUHU','ratio_pouco',
//        'ratio_poucu','ior_POUCO','ior_POUCU');";

        for($i=$offset;$i<($page_no+1)*$page_size;$i++){
            if($future_r_data[$i]['MB_MID']){ // 防止空数据
                $S_Single_Rate=change_rate($open,$future_r_data[$i]['S_Single_Rate']); // 主队单双
                $S_Double_Rate=change_rate($open,$future_r_data[$i]['S_Double_Rate']); // 客队单双
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
                $m_date=strtotime($future_r_data[$i]['M_Date']);
                $date=date("m-d",$m_date);
                if (strlen($future_r_data[$i]['M_Time'])==5){
                    $pdate=$date.'<br>0'.$future_r_data[$i]['M_Time'];
                }else{
                    $pdate=$date.'<br>'.$future_r_data[$i]['M_Time'];
                }

                if($future_r_data[$i]['ShowTypeP']=="H"){
                    $ratio_mb_str=$future_r_data[$i]['M_P_LetB'];
                    $ratio_tg_str='';
                }elseif($future_r_data[$i]['ShowTypeP']=="C"){
                    $ratio_mb_str='';
                    $ratio_tg_str=$future_r_data[$i]['M_P_LetB'];
                }

                $MID = $future_r_data[$i]['MID'];
                $newDataArray[$MID]['gid']=$MID;
                $newDataArray[$MID]['datetime']=$pdate;
                $newDataArray[$MID]['dategh']=date('m-d').$future_r_data[$i]['MB_MID'];
                $newDataArray[$MID]['league']=$future_r_data[$i]['M_League'];
                $newDataArray[$MID]['gnum_h']=$future_r_data[$i]['MB_MID'];
                $newDataArray[$MID]['gnum_c']=$future_r_data[$i]['TG_MID'];
                $newDataArray[$MID]['team_h']=$future_r_data[$i]['MB_Team'];
                $newDataArray[$MID]['team_c']=$future_r_data[$i]['TG_Team'];
                $newDataArray[$MID]['strong']=$future_r_data[$i]['ShowTypeP'];
                $newDataArray[$MID]['ratio']=$future_r_data[$i]['M_P_LetB'];
                $newDataArray[$MID]['ratio_mb_str']=$ratio_mb_str;
                $newDataArray[$MID]['ratio_tg_str']=$ratio_tg_str;
                $newDataArray[$MID]['ior_PRH']=change_rate($open,$future_r_data[$i]['MB_P_LetB_Rate']);
                $newDataArray[$MID]['ior_PRC']=change_rate($open,$future_r_data[$i]['TG_P_LetB_Rate']);
                $newDataArray[$MID]['ratio_o']=$future_r_data[$i]['MB_P_Dime_Rate'];
                $newDataArray[$MID]['ratio_u']=$future_r_data[$i]['TG_P_Dime_Rate'];
                $newDataArray[$MID]['ratio_o_str']="大".str_replace('O','',$future_r_data[$i][MB_P_Dime]);
                $newDataArray[$MID]['ratio_u_str']="小".str_replace('U','',$future_r_data[$i][TG_P_Dime]);
                $newDataArray[$MID]['str_odd']=$Single;
                $newDataArray[$MID]['str_even']=$Double;
                $newDataArray[$MID]['ior_PO']=change_rate($open,$future_r_data[$i]['S_P_Single_Rate']);
                $newDataArray[$MID]['ior_PE']=change_rate($open,$future_r_data[$i]['S_P_Double_Rate']);
                $newDataArray[$MID]['ior_PMH']=change_rate($open,$future_r_data[$i]["MB_P_Win_Rate"]);
                $newDataArray[$MID]['ior_PMC']=change_rate($open,$future_r_data[$i]["TG_P_Win_Rate"]);
                $newDataArray[$MID]['hratio']=$future_r_data[$i]['M_LetB_H'];
                $newDataArray[$MID]['gidm']=$MID;
                $newDataArray[$MID]['par_minlimit']=3;
                $newDataArray[$MID]['par_maxlimit']=10;
                $newDataArray[$MID]['ratio_pouho']=$future_r_data[$i]['MB_Dime_H'];
                $newDataArray[$MID]['ratio_pouhu']=$future_r_data[$i]['MB_Dime_S_H'];
                $newDataArray[$MID]['ratio_ouho_str']=str_replace('O','',$future_r_data[$i]['MB_Dime_H']);
                $newDataArray[$MID]['ratio_ouhu_str']=str_replace('U','',$future_r_data[$i]['MB_Dime_S_H']);
                $newDataArray[$MID]['ior_POUHO']=change_rate($open,$future_r_data[$i]["MB_P_Dime_Rate_H"]);
                $newDataArray[$MID]['ior_POUHU']=change_rate($open,$future_r_data[$i]["MB_P_Dime_Rate_S_H"]);
                $newDataArray[$MID]['ratio_pouco']=$future_r_data[$i]['TG_Dime_H'];
                $newDataArray[$MID]['ratio_poucu']=$future_r_data[$i]['TG_Dime_S_H'];
                $newDataArray[$MID]['ratio_ouco_str']=str_replace('O','',$future_r_data[$i]['TG_Dime_H']);
                $newDataArray[$MID]['ratio_oucu_str']=str_replace('U','',$future_r_data[$i]['TG_Dime_S_H']);
                $newDataArray[$MID]['ior_POUCO']=change_rate($open,$future_r_data[$i]["TG_P_Dime_Rate_H"]);
                $newDataArray[$MID]['ior_POUCU']=change_rate($open,$future_r_data[$i]["TG_P_Dime_Rate_S_H"]);

                $K=$K+1;
                $page_gamecount ++ ;
            }
        }
        echo "parent.gamount=$page_gamecount;\n";

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
//}

</script>
</head>
<body onLoad="onLoad()" class="load_body_var <?php echo $table_dif?>" >
<!--<div id="controlscroll"><table border="0" cellspacing="0" cellpadding="0" class="loadBox"><tr><td> </td></tr></table></div>-->
<!-- 球赛展示区顶部 开始-->
<div class="bet_head">
    <!--左侧按钮-->
    <div class="bet_left">
        <?php

        if($rtype=='r' or $rtype=='r_main'){ // 全部才有
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

        if($rtype=='r' or $rtype=='r_main'){ // 全部才有
            ?>
            <span id="sel_Market" class="bet_view_btn" onclick="chgMarket('main');" style="display: <?php if($rtype=='r'){echo 'inline-block';}else{echo 'none';}?>;"><tt id="SpanMarket" class="bet_normal_text">主要盘口</tt></span>
            <span id="all_sel_Market" class="bet_view_btn" onclick="chgMarket('all');" style="display: <?php if($rtype=='r_main'){echo 'inline-block';}else{echo 'none';}?>;"><tt id="all_SpanMarket" class="bet_normal_text">全部盘口</tt></span>
            <span class="bet_Special_btn" ><tt id="SpanFilter" class="bet_normal_text">赛节投注</tt></span>
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
        if($rtype=='r'or $rtype =='r_main'){ // 只有全部才有
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
                                        case "all":
                                            include "../BK_browse/body_m_r_ou.php";break;

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

