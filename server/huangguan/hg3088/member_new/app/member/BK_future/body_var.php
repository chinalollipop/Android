<?php
session_start();
ini_set('display_errors','Off');
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

// 判断是否维护-单页面维护功能
checkMaintain($_REQUEST['showtype']);
$uid=$_REQUEST['uid'];
$langx=$_SESSION['langx'];
$mtype=$_REQUEST['mtype'];
$rtype=$_REQUEST['rtype'];
$league_id=$_REQUEST['league_id'];
$g_date=$_REQUEST['g_date'];
$page_no=$_REQUEST['page_no'];
$leaname = $_REQUEST['leaname'] ; // 搜索赛事
if($leaname=='undefined'){
    $leaname='' ;
}

require ("../include/traditional.$langx.inc.php");

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
	echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
	exit;
}

$sql = "select UserName,Money,OpenType from ".DBPREFIX.MEMBERTABLE." where Oid='$uid' and Status=0";
$result = mysqli_query($dbMasterLink,$sql);
$row = mysqli_fetch_assoc($result);
$open=$row['OpenType'];
$memname=$row['UserName'];
$credit=$row['Money'];

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

?>
<HEAD>
<TITLE>篮球變數值</TITLE>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
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


<?php
switch ($rtype){
case "all":
case "r":
        $future_r_data = getFutureData('FUTURE_BK_ALL') ; // 数据
        $page_size = 60;
        $length = count($future_r_data) ; // 长度
        $page_count=ceil($length/$page_size); // 总共多少页
        $offset=$page_no*$page_size;
        $resultArr=array_slice($future_r_data,$offset,$page_size);
        $cou = count($resultArr);
		echo "parent.retime=180;\n";
//		echo "parent.str_renew = '$second_auto_update';\n";
		echo "parent.t_page=$page_count;\n";
        echo "parent.gamount=$cou;\n";
        $newDataArray=[];
        foreach($resultArr as $key=>$row){
//		for($i=$offset;$i<($page_no+1)*$page_size;$i++){
//            if($future_r_data[$i]['MB_MID']){ // 防止空数据
                $M_Flat_Rate=change_rate($open,$row["M_Flat_Rate"]); //全场和的赔率
                // MB_Dime_Rate主队全场赔率      TG_Dime_Rate客队全场赔率
                $ra_rate=get_other_ioratio(GAME_POSITION,$row["MB_Dime_Rate"],$row["TG_Dime_Rate"],100); // 默认都是香港盘
                $MB_Dime_Rate=$ra_rate[0]; // 主队
                $TG_Dime_Rate=$ra_rate[1]; // 客队

                // 全场让球单独处理
                $ra_rate=get_other_ioratio(GAME_POSITION,$row["MB_LetB_Rate"],$row["TG_LetB_Rate"],100); // 默认都是香港盘
                $MB_LetB_Rate=$ra_rate[0]; // 主队    主队让球赔率
                $TG_LetB_Rate=$ra_rate[1]; // 客队    客队让球赔率
                $ra_rate=get_other_ioratio(GAME_POSITION,$row["MB_Dime_Rate_H"],$row["MB_Dime_Rate_S_H"],100); // 默认都是香港盘
                $MB_Dime_Rate_H=$ra_rate[0]; // 主队半场大的赔率      主队半场赔率
                $MB_Dime_Rate_S_H=$ra_rate[1]; // 主队半场小的赔率    半场主队独赢小的赔率
                $ra_rate=get_other_ioratio(GAME_POSITION,$row["TG_Dime_Rate_H"],$row["TG_Dime_Rate_S_H"],100); // 默认都是香港盘
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
                
	            if($row['ShowTypeR']=="H"){
					$ratio_mb_str=$row['M_LetB'];
					$ratio_tg_str='';
				}elseif($row['ShowTypeR']=="C"){
					$ratio_mb_str='';
					$ratio_tg_str=$row['M_LetB'];
				}
                
                $m_date=strtotime($row['M_Date']);
                $dates=date("m-d",$m_date);
                $MID = $row['MID'] ;
                 $newDataArray[$MID]['gid']=$MID;
				 $newDataArray[$MID]['datetime']=$row['M_Type']==1?$dates.'<br>'.$row['M_Time'].'<br><font color=red>滚球</font>':$dates.'<br>'.$row['M_Time'];
				 $newDataArray[$MID]['dategh']=date('m-d').$row['MB_MID'];
				 $newDataArray[$MID]['datetimelove']=$date."<br>".$row['M_Time'];
				 $newDataArray[$MID]['league']=$row['M_League'];
				 $newDataArray[$MID]['gnum_h']=$row['MB_MID'];
				 $newDataArray[$MID]['gnum_c']=$row['TG_MID'];
				 $newDataArray[$MID]['team_h']=$row['MB_Team'];
				 $newDataArray[$MID]['team_c']=$row['TG_Team'];
				 $newDataArray[$MID]['strong']=$row['ShowTypeR'];
				 $newDataArray[$MID]['ratio']=$row['M_LetB'];
				 $newDataArray[$MID]['ratio_mb_str']=$ratio_mb_str;
				 $newDataArray[$MID]['ratio_tg_str']=$ratio_tg_str;
				 $newDataArray[$MID]['ior_RH']=change_rate($open,$MB_LetB_Rate);
				 $newDataArray[$MID]['ior_RC']=change_rate($open,$TG_LetB_Rate);
				 $newDataArray[$MID]['ratio_o']=$row['MB_Dime'];
				 $newDataArray[$MID]['ratio_u']=$row['TG_Dime'];
                 $newDataArray[$MID]['ratio_o_str']=str_replace('O','大',$row['MB_Dime']);
                 $newDataArray[$MID]['ratio_u_str']=str_replace('U','小',$row['TG_Dime']);
				 $newDataArray[$MID]['ior_OUH']=change_rate($open,$TG_Dime_Rate);
				 $newDataArray[$MID]['ior_OUC']=change_rate($open,$MB_Dime_Rate);
				 $newDataArray[$MID]['ior_MH']=change_rate($open,$row["MB_Win_Rate"]); //主队独赢赔率
				 $newDataArray[$MID]['ior_MC']=change_rate($open,$row["TG_Win_Rate"]); //客队独赢赔率
				 $newDataArray[$MID]['str_odd']=$Single;
				 $newDataArray[$MID]['str_even']=$Double;
				 $newDataArray[$MID]['ior_EOO']=change_rate($open,$row['S_Single_Rate']); // 主队单双赔率
				 $newDataArray[$MID]['ior_EOE']=change_rate($open,$row['S_Double_Rate']); // 客队单双赔率
				 $newDataArray[$MID]['ratio_ouho']=$row['MB_Dime_H'];
				 $newDataArray[$MID]['ratio_ouhu']=$row['MB_Dime_S_H'];
				 $newDataArray[$MID]['ratio_ouho_str']=str_replace('O','',$row['MB_Dime_H']);
				 $newDataArray[$MID]['ratio_ouhu_str']=str_replace('U','',$row['MB_Dime_S_H']);
				 $newDataArray[$MID]['ior_OUHO']=change_rate($open,$MB_Dime_Rate_H);
				 $newDataArray[$MID]['ior_OUHU']=change_rate($open,$MB_Dime_Rate_S_H);
				 $newDataArray[$MID]['ratio_ouco']=$row['TG_Dime_H'];
				 $newDataArray[$MID]['ratio_oucu']=$row['TG_Dime_S_H'];
				 $newDataArray[$MID]['ratio_ouco_str']=str_replace('O','',$row['TG_Dime_H']);
				 $newDataArray[$MID]['ratio_oucu_str']=str_replace('U','',$row['TG_Dime_S_H']);
				 $newDataArray[$MID]['ior_OUCO']=change_rate($open,$TG_Dime_Rate_H);
				 $newDataArray[$MID]['ior_OUCU']=change_rate($open,$TG_Dime_Rate_S_H);
				 $newDataArray[$MID]['eventid']=$row['Eventid'];
				 $newDataArray[$MID]['hot']=$row['Hot'];
				 $newDataArray[$MID]['play']=$row['Play'];
				 $newDataArray[$MID]['all']=$row['more'];
				 $newDataArray[$MID]['bet_Url']="gid={$MID}&uid={$uid}&odd_f_type=H&gnum={$row['MB_MID']}&langx={$langx}";
				 
                $K=$K+1;
                $page_gamecount ++ ;
//            }
        }
        $listTitle="早盘篮球和美式足球 ";
		$leagueNameCur='';
	break;

case "p3":  //综合过关
    $future_r_data = getFutureData('FUTURE_BK_P3') ; // 数据
    $length = count($future_r_data) ; // 长度
    $page_count=ceil($length/$page_size); // 总共多少页
    $offset=$page_no*60;
	echo "parent.retime=0;\n";
	echo "parent.t_page=$page_count;\n";
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
				$newDataArray[$MID]['ratio_o']=$future_r_data[$i]['MB_P_Dime'];
				$newDataArray[$MID]['ratio_u']=$future_r_data[$i]['TG_P_Dime'];
				$newDataArray[$MID]['ratio_o_str']="大".str_replace('O','',$future_r_data[$i][MB_P_Dime]);
				$newDataArray[$MID]['ratio_u_str']="小".str_replace('U','',$future_r_data[$i][TG_P_Dime]);
				$newDataArray[$MID]['ior_POUC']=change_rate($open,$future_r_data[$i]['MB_P_Dime_Rate']);
				$newDataArray[$MID]['ior_POUH']=change_rate($open,$future_r_data[$i]['TG_P_Dime_Rate']);
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
        $listTitle="早盘篮球和美式足球 : 综合过关";
		$leagueNameCur='';
	break;
}
?>

// function onLoad(){
// 	if(parent.retime > 0)
// 		parent.retime_flag='Y';
// 	else
// 		parent.retime_flag='N';
// 	parent.loading_var = 'N';
// 	if(parent.loading == 'N' && parent.ShowType != ''){
// 		parent.ShowGameList();
// 	}
// }
//
// function onUnLoad(){
// 	x = parent.body_browse.pageXOffset;
// 	y = parent.body_browse.pageYOffset;
// 	parent.body_browse.scroll(x,y);
// }

</script>
 <link rel="stylesheet" href="/style/member/mem_body_ft.css?v=<?php echo AUTOVER; ?>" type="text/css" media="screen">
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
											case "r":	include "../BK_browse/body_m_r_ou.php";break;
											case "all":	include "../BK_browse/body_m_r_ou.php";break;
											case "p3":	include "body_p3.php";break;
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
<!-- 
<div class="today_bet_floatright <?php echo $today_bet_floatright?>" >
    <iframe id="live" name="live"  src="<?php echo BROWSER_IP?>/app/member/live/live.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>"></iframe>
</div>
-->

<script type="text/javascript" src="../../../js/jquery.js"></script>
<script type="text/javascript" src="../../../js/common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../../js/src/common_body_var.js?v=<?php echo AUTOVER; ?>"></script>
<script>
    setBodyScroll();
</script>
</body>
</html>

