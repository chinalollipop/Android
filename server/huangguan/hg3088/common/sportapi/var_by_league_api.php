<?php
/**
 * /var_by_league_api.php  联赛下面的盘口列表（让球、大小）
 *
 * @param  type   FT 足球，FU 足球早盘，BK 篮球，BU 篮球早盘
 * @param  more   s 今日赛事， r 滚球
 * @param  gid  3321118,3321062
 */

//include_once('include/config.inc.php');
//require ("include/curl_http.php");
//require ("include/define_function_list.inc.php");
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

$langx=$_SESSION['Language']?$_SESSION['Language']:'zh-cn';
$uid=$_SESSION['Oid'];
$open=$_SESSION['OpenType'];
$type = $_REQUEST['type'];
$more = $_REQUEST['more'];
$gid = $_REQUEST['gid'];
// 会员篮球滚球投注开关
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
if(in_array('DJFT',$gameArr)){
    $mem_djft_off = 'off';
}
if(in_array('DJBK',$gameArr)){
    $mem_djbk_off = 'off';
}
$isClosedH1 = in_array('BKH1', $gameArr); // 是否关闭篮球滚球上半场20200111
$redisObj = new Ciredis();
$flushWay = $redisObj->getSimpleOne('flush_way'); // 刷水渠道

switch ($type){
    case 'FU':// 足球早盘
        $returnData = $redisObj->getSimpleOne("FUTURE_R");
        $aData = json_decode($returnData,true);
        $obtSelections = $redisObj->getSimpleOne("FUTURE_FT_OBTSELECTIONS");
        $aObtSelections = json_decode($obtSelections,true) ;
        $aData2=[];
        foreach ($aData as $k => $v){
            $pos = strpos($gid, $v['MID']);
            if ($pos!==false) {
//                if ($v['MB_MID']) {  // 防止空数据
                if (SPORT_FLUSH_WAY=='ra'){
                    // 全场让球单独处理
                    $ra_rate = get_other_ioratio(GAME_POSITION, $v["MB_LetB_Rate"], $v["TG_LetB_Rate"], 100); // 默认都是香港盘
                    $MB_LetB_Rate = $ra_rate[0]; // 主队
                    $TG_LetB_Rate = $ra_rate[1]; // 客队
                    $MB_LetB_Rate = change_rate($open, $MB_LetB_Rate);
                    $TG_LetB_Rate = change_rate($open, $TG_LetB_Rate);
                    // 全场大小单独处理
                    $dx_rate = get_other_ioratio(GAME_POSITION, $v["MB_Dime_Rate"], $v["TG_Dime_Rate"], 100); // 默认都是香港盘
                    $MB_Dime_Rate = $dx_rate[0]; // 主队
                    $TG_Dime_Rate = $dx_rate[1]; // 客队
                    $MB_Dime_Rate = change_rate($open, $MB_Dime_Rate);
                    $TG_Dime_Rate = change_rate($open, $TG_Dime_Rate);

                    // 半场让球单独处理
                    $h_ra_rate=get_other_ioratio(GAME_POSITION,$v["MB_LetB_Rate_H"],$v["TG_LetB_Rate_H"],100); // 默认都是香港盘
                    $MB_LetB_Rate_H=$h_ra_rate[0]; // 主队
                    $TG_LetB_Rate_H=$h_ra_rate[1]; // 客队
                    $MB_LetB_Rate_H=change_rate($open,$MB_LetB_Rate_H);  // 半场让球主队
                    $TG_LetB_Rate_H=change_rate($open,$TG_LetB_Rate_H); // 半场让球客队
                    // 半场大小处理
                    $h_ra_rate=get_other_ioratio(GAME_POSITION,$v["TG_Dime_Rate_H"],$v["MB_Dime_Rate_H"],100); // 默认都是香港盘
                    $TG_Dime_Rate_H=$h_ra_rate[0];
                    $MB_Dime_Rate_H=$h_ra_rate[1];
                    $TG_Dime_Rate_H=change_rate($open,$TG_Dime_Rate_H);  // 半场大小客队
                    $MB_Dime_Rate_H=change_rate($open,$MB_Dime_Rate_H); // 半场大小主队

                    $MB_Win_Rate=change_rate($open,$v["MB_Win_Rate"]);
                    $TG_Win_Rate=change_rate($open,$v["TG_Win_Rate"]);
                    $M_Flat_Rate=change_rate($open,$v["M_Flat_Rate"]);
                    $MB_Win_Rate_H=change_rate($open,$v["MB_Win_Rate_H"]);
                    $TG_Win_Rate_H=change_rate($open,$v["TG_Win_Rate_H"]);
                    $M_Flat_Rate_H=change_rate($open,$v["M_Flat_Rate_H"]);

                    $S_Single_Rate=change_rate($open,$v['S_Single_Rate']);
                    $S_Double_Rate=change_rate($open,$v['S_Double_Rate']);
                }
                else{
                        $MB_LetB_Rate=round_num($v["MB_LetB_Rate"]); // 主队
                        $TG_LetB_Rate=round_num($v["TG_LetB_Rate"]); // 客队
                        $TG_Dime_Rate=round_num($v["TG_Dime_Rate"]);
                        $MB_Dime_Rate=round_num($v["MB_Dime_Rate"]);
                        $MB_LetB_Rate_H=round_num($v["MB_LetB_Rate_H"]); // 主队
                        $TG_LetB_Rate_H=round_num($v["TG_LetB_Rate_H"]); // 客队
                        $TG_Dime_Rate_H=round_num($v["TG_Dime_Rate_H"]);
                        $MB_Dime_Rate_H=round_num($v["MB_Dime_Rate_H"]);
                        $MB_Win_Rate=round_num($v["MB_Win_Rate"]);
                        $TG_Win_Rate=round_num($v["TG_Win_Rate"]);
                        $M_Flat_Rate=round_num($v["M_Flat_Rate"]);
                        $MB_Win_Rate_H=round_num($v["MB_Win_Rate_H"]);
                        $TG_Win_Rate_H=round_num($v["TG_Win_Rate_H"]);
                        $M_Flat_Rate_H=round_num($v["M_Flat_Rate_H"]);
                        $S_Single_Rate=round_num($v['S_Single_Rate']);
                        $S_Double_Rate=round_num($v['S_Double_Rate']);
                    }
                    if ($v['HPD_Show'] == 1 and $v['PD_Show'] == 1 and $v['T_Show'] == 1 and $v['F_Show'] == 1) {
                        $show = 4;
                    } else if ($v['PD_Show'] == 1 and $v['T_Show'] == 1 and $v['F_Show'] == 1) {
                        $show = 3;
                    } else {
                        $show = 0;
                    }

                    $m_date=strtotime($v['M_Date']);
                    $dates=date("m-d",$m_date);
                    $allMethods = $v['more'] < 5 ? 0 : $v['more'];
                    $ShowTypeR = $v['ShowTypeR'];
                    if ($ShowTypeR == "H") {
                        $ratio_mb_str = $v['M_LetB'];
                        $ratio_tg_str = '';
                        $hratio_mb_str= $v['M_LetB_H'];
                        $hratio_tg_str='';
                    } elseif ($ShowTypeR == "C") {
                        $ratio_mb_str = '';
                        $ratio_tg_str = $v['M_LetB'];
                        $hratio_mb_str='';
                        $hratio_tg_str= $v['M_LetB_H'];
                    }

                    $aData2[$k]['gid'] = $v['MID']; // 盘口ID
                    $aData2[$k]['M_Type'] = $v['M_Type']; // 1 显示滚球字样
                    $aData2[$k]['M_Time'] = $v['M_Time']; // 比赛开始时间
                    $aData2[$k]['M_Date'] = $dates; // 比赛开始日期
                    $aData2[$k]['league'] = $v['M_League']; // 联盟名称
                    $aData2[$k]['gnum_h'] = $v['MB_MID']; // 主队ID
                    $aData2[$k]['gnum_c'] = $v['TG_MID']; // 客队ID
                    $aData2[$k]['team_h'] = $v['MB_Team']; // 主队名称
                    $aData2[$k]['team_c'] = $v['TG_Team']; // 客队名称
                    $aData2[$k]['strong'] = $v['ShowTypeR']?$v['ShowTypeR']:''; // 谁让球
                    $aData2[$k]['ratio'] = $v['M_LetB']; // 让球数
                    $aData2[$k]['ratio_mb_str'] = $ratio_mb_str ; // 主队让球数
                    $aData2[$k]['ratio_tg_str'] = $ratio_tg_str ; // 客队让球数
                    $aData2[$k]['ior_RH'] = $MB_LetB_Rate; // 主队让球赔率
                    $aData2[$k]['ior_RC'] = $TG_LetB_Rate; // 客队让球赔率
                    $aData2[$k]['ratio_o'] = $v['MB_Dime']; // O1.5 / 2
                    $aData2[$k]['ratio_u'] = $v['TG_Dime']; // U1.5 / 2
                    $aData2[$k]['ratio_o_str'] = "大" . str_replace('O', '', $v['MB_Dime']); // 大1.5 / 2
                    $aData2[$k]['ratio_u_str'] = "小" . str_replace('U', '', $v['TG_Dime']); // 小1.5 / 2
                    $aData2[$k]['ior_OUH'] = $TG_Dime_Rate; // 客队大小 赔率
                    $aData2[$k]['ior_OUC'] = $MB_Dime_Rate; // 主队大小 赔率
                    $aData2[$k]['redcard_h']=''; // 主队红牌数
                    $aData2[$k]['redcard_c']=''; // 客队红牌数
                    $aData2[$k]['more'] = $show?$show:'';
                    $aData2[$k]['all'] = $allMethods; // 更多玩法 总数，大于4时，显示更多玩法链接
                    $aData2[$k]['eventid'] = $v['Eventid'];
                    $aData2[$k]['hot'] = $v['Hot'];
                    $aData2[$k]['play'] = $v['Play'];
                    $aData2[$k]['showretime']=''; // 倒计时
                    $aData2[$k]['lastestscore_h'] ='';
                    $aData2[$k]['lastestscore_c'] ='';
                    $aData2[$k]['score_h']=''; // 主队得分
                    $aData2[$k]['score_c']=''; // 客队得分
                    $aData2[$k]['hgid']=$v[MID];
                    $aData2[$k]['hstrong']=$v[ShowTypeHR];
                    $aData2[$k]['hratio']=$v[M_LetB_H];
                    $aData2[$k]['hratio_mb_str']=$hratio_mb_str?$hratio_mb_str:'';
                    $aData2[$k]['hratio_tg_str']=$hratio_tg_str?$hratio_tg_str:'';
                    $aData2[$k]['ior_HRH']=$MB_LetB_Rate_H;
                    $aData2[$k]['ior_HRC']=$TG_LetB_Rate_H;
                    $aData2[$k]['hratio_o']=$v[MB_Dime_H];
                    $aData2[$k]['hratio_u']=$v[TG_Dime_H];
                    $aData2[$k]['hratio_o_str']="大".str_replace('O','',$v[MB_Dime_H]);
                    $aData2[$k]['hratio_u_str']="小".str_replace('U','',$v[TG_Dime_H]);
                    $aData2[$k]['ior_HOUH']=$TG_Dime_Rate_H;
                    $aData2[$k]['ior_HOUC']=$MB_Dime_Rate_H;
                    $aData2[$k]['ior_MH']=$MB_Win_Rate;
                    $aData2[$k]['ior_MC']=$TG_Win_Rate;
                    $aData2[$k]['ior_MN']=$M_Flat_Rate;
                    $aData2[$k]['ior_HMH']=$MB_Win_Rate_H;
                    $aData2[$k]['ior_HMC']=$TG_Win_Rate_H;
                    $aData2[$k]['ior_HMN']=$M_Flat_Rate_H;
                    $aData2[$k]['ior_EOO']=$S_Single_Rate;
                    $aData2[$k]['ior_EOE']=$S_Double_Rate;

                    // 标签开关 特优赔率、让球、进球大小、角球、罚球、会晋级
                    $aData2[$k]['eps']=$aData2[$k]['handicaps']=$aData2[$k]['goalsou']=$aData2[$k]['corners']=$aData2[$k]['bookings']=$aData2[$k]['toqualify']='N';
                    if (in_array('eps',$aObtSelections[$v['MID']])){
                        $aData2[$k]['eps']='Y';
                    }
                    if (in_array('handicaps',$aObtSelections[$v['MID']])){
                        $aData2[$k]['handicaps']='Y';
                    }
                    if (in_array('goalsou',$aObtSelections[$v['MID']])){
                        $aData2[$k]['goalsou']='Y';
                    }
                    if (in_array('corners',$aObtSelections[$v['MID']])){
                        $aData2[$k]['corners']='Y';
                    }
                    if (in_array('bookings',$aObtSelections[$v['MID']])){
                        if ($flushWay=='ra686'){
                            $aData2[$k]['bookings']='N'; // 6686缺少主要的玩法，罚牌强制关闭
                        }
                        elseif($flushWay=='ra'){
                            $aData2[$k]['bookings']='Y';
                        }
                    }
                    if (in_array('toqualify',$aObtSelections[$v['MID']])){
                        $aData2[$k]['toqualify']='Y';
                    }
//                }
            }

        }

        $cou=count($aData2);
        break;

    case 'FT':
        switch ($more){
            case 's': // 足球今日赛事
                $returnData = $redisObj->getSimpleOne("TODAY_FT_M_ROU_EO");
                $aData = json_decode($returnData,true) ;
                $obtSelections = $redisObj->getSimpleOne("TODAY_FT_OBTSELECTIONS");
                $aObtSelections = json_decode($obtSelections,true) ;

                $aData2=[];
                foreach ($aData as $k => $v){

//                    $pos = strpos($gid, $v['MID']);
                    // 如果是正网就根据gid 返回数据，如果是6686则全部返回
                    if ($flushWay=='ra686'){
                        if ($gid){$pos = strpos(trim($gid), trim($v['MID']));}
                        else{$pos = true;}
                    }else{
                        if ($gid){$pos = strpos(trim($gid), trim($v['MID']));}
                        else{$pos = true;}
                    }
                    if ($pos!==false) {
                        if (SPORT_FLUSH_WAY=='ra'){
                        // 全场让球单独处理
                        $ra_rate = get_other_ioratio(GAME_POSITION, $v["MB_LetB_Rate"], $v["TG_LetB_Rate"], 100); // 默认都是香港盘
                        $MB_LetB_Rate = $ra_rate[0]; // 主队
                        $TG_LetB_Rate = $ra_rate[1]; // 客队
                        $MB_LetB_Rate = change_rate($open, $MB_LetB_Rate);
                        $TG_LetB_Rate = change_rate($open, $TG_LetB_Rate);

                        // 全场大小处理
                        $ra_rate = get_other_ioratio(GAME_POSITION, $v["TG_Dime_Rate"], $v["MB_Dime_Rate"], 100); // 默认都是香港盘
                        $TG_Dime_Rate = $ra_rate[0];
                        $MB_Dime_Rate = $ra_rate[1];
                        $TG_Dime_Rate = change_rate($open, $TG_Dime_Rate);
                        $MB_Dime_Rate = change_rate($open, $MB_Dime_Rate);

                        // 半场让球单独处理
                        $h_ra_rate=get_other_ioratio(GAME_POSITION,$v["MB_LetB_Rate_H"],$v["TG_LetB_Rate_H"],100); // 默认都是香港盘
                        $MB_LetB_Rate_H=$h_ra_rate[0]; // 主队
                        $TG_LetB_Rate_H=$h_ra_rate[1]; // 客队
                        $MB_LetB_Rate_H=change_rate($open,$MB_LetB_Rate_H);  // 半场让球主队
                        $TG_LetB_Rate_H=change_rate($open,$TG_LetB_Rate_H); // 半场让球客队

                        // 半场大小处理
                        $h_ra_rate=get_other_ioratio(GAME_POSITION,$v["TG_Dime_Rate_H"],$v["MB_Dime_Rate_H"],100); // 默认都是香港盘
                        $TG_Dime_Rate_H=$h_ra_rate[0];
                        $MB_Dime_Rate_H=$h_ra_rate[1];
                        $TG_Dime_Rate_H=change_rate($open,$TG_Dime_Rate_H);  // 半场大小客队
                        $MB_Dime_Rate_H=change_rate($open,$MB_Dime_Rate_H); // 半场大小主队

                        $MB_Win_Rate=change_rate($open,$v["MB_Win_Rate"]);
                        $TG_Win_Rate=change_rate($open,$v["TG_Win_Rate"]);
                        $M_Flat_Rate=change_rate($open,$v["M_Flat_Rate"]);
                        $MB_Win_Rate_H=change_rate($open,$v["MB_Win_Rate_H"]);
                        $TG_Win_Rate_H=change_rate($open,$v["TG_Win_Rate_H"]);
                        $M_Flat_Rate_H=change_rate($open,$v["M_Flat_Rate_H"]);

                        $S_Single_Rate=change_rate($open,$v['S_Single_Rate']);
                        $S_Double_Rate=change_rate($open,$v['S_Double_Rate']);
                        }
                        else{
                            $MB_LetB_Rate=round_num($v["MB_LetB_Rate"]); // 主队
                            $TG_LetB_Rate=round_num($v["TG_LetB_Rate"]); // 客队
                            $TG_Dime_Rate=round_num($v["TG_Dime_Rate"]);
                            $MB_Dime_Rate=round_num($v["MB_Dime_Rate"]);
                            $MB_LetB_Rate_H=round_num($v["MB_LetB_Rate_H"]); // 主队
                            $TG_LetB_Rate_H=round_num($v["TG_LetB_Rate_H"]); // 客队
                            $TG_Dime_Rate_H=round_num($v["TG_Dime_Rate_H"]);
                            $MB_Dime_Rate_H=round_num($v["MB_Dime_Rate_H"]);
                            $MB_Win_Rate=round_num($v["MB_Win_Rate"]);
                            $TG_Win_Rate=round_num($v["TG_Win_Rate"]);
                            $M_Flat_Rate=round_num($v["M_Flat_Rate"]);
                            $MB_Win_Rate_H=round_num($v["MB_Win_Rate_H"]);
                            $TG_Win_Rate_H=round_num($v["TG_Win_Rate_H"]);
                            $M_Flat_Rate_H=round_num($v["M_Flat_Rate_H"]);
                            $S_Single_Rate=round_num($v['S_Single_Rate']);
                            $S_Double_Rate=round_num($v['S_Double_Rate']);
                        }

                        if ($v['HPD_Show'] == 1 and $v['PD_Show'] == 1 and $v['T_Show'] == 1 and $v['F_Show'] == 1) {
                            $show = 4;
                        } else if ($v['PD_Show'] == 1 and $v['T_Show'] == 1 and $v['F_Show'] == 1) {
                            $show = 3;
                        } else {
                            $show = 0;
                        }
                        $allMethods = $v['more'] < 5 ? 0 : $v['more'];
                        if ($v['ShowTypeR'] == "H") {
                            $ratio_mb_str = $v['M_LetB'];
                            $ratio_tg_str = '';
                            $hratio_mb_str=$row['M_LetB_H'];
                            $hratio_tg_str='';
                        } elseif ($v['ShowTypeR'] == "C") {
                            $ratio_mb_str = '';
                            $ratio_tg_str = $v['M_LetB'];
                            $hratio_mb_str='';
                            $hratio_tg_str=$row['M_LetB_H'];
                        }

                        $aData2[$k]['gid'] = $v['MID'];
                        $aData2[$k]['M_Type'] = $v['M_Type']; // 显示滚球字样
                        $aData2[$k]['M_Time'] = $v['M_Time']; // 比赛开始时间
                        $aData2[$k]['M_Date'] = date('m-d'); // 比赛开始日期
                        $aData2[$k]['league'] = $v['M_League']; // 联盟名称
                        $aData2[$k]['gnum_h'] = $v['MB_MID'];
                        $aData2[$k]['gnum_c'] = $v['TG_MID'];
                        $aData2[$k]['team_h'] = $v['MB_Team'];
                        $aData2[$k]['team_c'] = $v['TG_Team'];
                        $aData2[$k]['strong'] = $v['ShowTypeR']?$v['ShowTypeR']:''; // 谁让球
                        $aData2[$k]['ratio'] = $v['M_LetB']; // 让球数
                        $aData2[$k]['ratio_mb_str'] = $ratio_mb_str; // 主队让球数
                        $aData2[$k]['ratio_tg_str'] = $ratio_tg_str; // 客队让球数
                        $aData2[$k]['ior_RH'] = $MB_LetB_Rate;
                        $aData2[$k]['ior_RC'] = $TG_LetB_Rate;
                        $aData2[$k]['ratio_o'] = $v['MB_Dime'];
                        $aData2[$k]['ratio_u'] = $v['TG_Dime'];
                        $aData2[$k]['ratio_o_str'] = "大" . str_replace('O', '', $v['MB_Dime']);
                        $aData2[$k]['ratio_u_str'] = "小" . str_replace('U', '', $v['TG_Dime']);
                        $aData2[$k]['ior_OUH'] = $TG_Dime_Rate; // 全场得分小赔率
                        $aData2[$k]['ior_OUC'] = $MB_Dime_Rate; // 全场得分大赔率
                        $aData2[$k]['redcard_h']=''; // 主队红牌数
                        $aData2[$k]['redcard_c']=''; // 客队红牌数
                        $aData2[$k]['more'] = $show;
                        $aData2[$k]['all'] = $allMethods;
                        $aData2[$k]['eventid'] = $v['Eventid'];
                        $aData2[$k]['hot'] = $v['Hot'];
                        $aData2[$k]['play'] = $v['Play'];
                        $aData2[$k]['showretime']='';
                        $aData2[$k]['lastestscore_h'] ='';
                        $aData2[$k]['lastestscore_c'] ='';
                        $aData2[$k]['score_h']='';
                        $aData2[$k]['score_c']='';

                        $aData2[$k]['hgid']=$v[MID];
                        $aData2[$k]['hstrong']=$v[ShowTypeHR];
                        $aData2[$k]['hratio']=$v[M_LetB_H];
                        $aData2[$k]['hratio_mb_str']=$hratio_mb_str?$hratio_mb_str:'';
                        $aData2[$k]['hratio_tg_str']=$hratio_tg_str?$hratio_tg_str:'';
                        $aData2[$k]['ior_HRH']=$MB_LetB_Rate_H; // 半场让球主队大赔率
                        $aData2[$k]['ior_HRC']=$TG_LetB_Rate_H; // 半场让球客队小赔率
                        $aData2[$k]['hratio_o']=$v[MB_Dime_H];
                        $aData2[$k]['hratio_u']=$v[TG_Dime_H];
                        $aData2[$k]['hratio_o_str']="大".str_replace('O','',$v[MB_Dime_H]);
                        $aData2[$k]['hratio_u_str']="小".str_replace('U','',$v[TG_Dime_H]);
                        $aData2[$k]['ior_HOUH']=$TG_Dime_Rate_H;
                        $aData2[$k]['ior_HOUC']=$MB_Dime_Rate_H;
                        $aData2[$k]['ior_MH']=$MB_Win_Rate;
                        $aData2[$k]['ior_MC']=$TG_Win_Rate;
                        $aData2[$k]['ior_MN']=$M_Flat_Rate;
                        $aData2[$k]['ior_HMH']=$MB_Win_Rate_H;
                        $aData2[$k]['ior_HMC']=$TG_Win_Rate_H;
                        $aData2[$k]['ior_HMN']=$M_Flat_Rate_H;
                        $aData2[$k]['ior_EOO']=$S_Single_Rate;
                        $aData2[$k]['ior_EOE']=$S_Double_Rate;

                        // 标签开关 特优赔率、让球、进球大小、角球、罚球、会晋级
                        $aData2[$k]['eps']=$aData2[$k]['handicaps']=$aData2[$k]['goalsou']=$aData2[$k]['corners']=$aData2[$k]['bookings']=$aData2[$k]['toqualify']='N';
                        if (in_array('eps',$aObtSelections[$v['MID']])){
                            $aData2[$k]['eps']='Y';
                        }
                        if (in_array('handicaps',$aObtSelections[$v['MID']])){
                            $aData2[$k]['handicaps']='Y';
                        }
                        if (in_array('goalsou',$aObtSelections[$v['MID']])){
                            $aData2[$k]['goalsou']='Y';
                        }
                        if (in_array('corners',$aObtSelections[$v['MID']])){
                            $aData2[$k]['corners']='Y';
                        }
                        if (in_array('bookings',$aObtSelections[$v['MID']])){
                            if ($flushWay=='ra686'){
                                $aData2[$k]['bookings']='N'; // 6686缺少主要的玩法，罚牌强制关闭
                            }
                            elseif($flushWay=='ra'){
                                $aData2[$k]['bookings']='Y';
                            }
                        }
                        if (in_array('toqualify',$aObtSelections[$v['MID']])){
                            $aData2[$k]['toqualify']='Y';
                        }
                    }
                }

                $cou=count($aData2);
                break;
            case 'r':// 足球滚球
                $aData2 = [];
                $returnData = $redisObj->getSimpleOne("FT_M_ROU_EO");
                $matches = json_decode($returnData,true);
                if(is_array($matches)){
                    $cou=sizeof($matches);
                }else{
                    $cou=0;
                }
                if($cou>0){
                    for($i=0;$i<$cou;$i++){
//                        $messages = $matches[$i];
//                        $messages = str_replace(");", ")", $messages);
//                        $messages = str_replace("cha(9)", "", $messages);
//                        $datainfo = eval("return $messages;");

                        $datainfo = $matches[$i];
                        $datainfo[2]=$datainfo['LEAGUE'];
                        $datainfo[0]=$datainfo['GID'];
                        $datainfo[48]=$datainfo['RETIMESET'];
                        $datainfo[7]=$datainfo['STRONG'];
                        $datainfo[8]=$datainfo['RATIO_RE'];
                        $datainfo[49]=$datainfo['MORE'];
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
//                        print_r($datainfo); die;

                        // 如果是正网就根据gid 返回数据，如果是6686则全部返回
                        if ($flushWay=='ra686'){
                            if ($gid){$pos = strpos(trim($gid), trim($datainfo[0]));}
                            else{$pos = true;}
                        }else{
                            if ($gid){$pos = strpos(trim($gid), trim($datainfo[0]));}
                            else{$pos = true;}
                        }

                        if ($pos!==false){

                            // 电竞最后最后2分钟是否提前关闭
                            // 8分钟的电竞盘口   上半场第3分钟开始关闭赔率，下半场第6分钟开始关闭赔率
                            // 10分钟的电竞盘口   上半场第4分钟开始关闭赔率，下半场第8分钟开始关闭赔率
                            // 12分钟的电竞盘口   上半场第5分钟开始关闭赔率，下半场第10分钟开始关闭赔率
                            // $datainfo[48];  2H^06:56
                            // 电竞足球-FIFA 20英格兰网络明星联赛-10分钟比赛
                            $pos = strpos($datainfo[2],'电竞足球');
                            if ($pos === false){}
                            else{

                                    $pos8minute = strpos($datainfo[2],'8分钟比赛');
                                    if ($pos8minute===false){}
                                    else{
                                        $matchTotalMinites = 8;
                                        $currentMinuteIn8 = explode(':',explode('^',$datainfo[48])[1])[0];
                                        $retimeset0 = explode('^',$datainfo[48])[0];
                                    }

                                    $pos10minute = strpos($datainfo[2],'10分钟比赛');
                                    if ($pos10minute===false){}
                                    else{
                                        $matchTotalMinites = 10;
                                        $currentMinuteIn10 = explode(':',explode('^',$datainfo[48])[1])[0];
                                        $retimeset0 = explode('^',$datainfo[48])[0];
                                    }

                                    $pos12minute = strpos($datainfo[2],'12分钟比赛');
                                    if ($pos12minute===false){}
                                    else{
                                        $matchTotalMinites = 12;
                                        $currentMinuteIn12 = explode(':',explode('^',$datainfo[48])[1])[0];
                                        $retimeset0 = explode('^',$datainfo[48])[0];
                                    }

                                    $posYQminute = strpos($datainfo[2],'电竞邀请赛');
                                    if ($posYQminute===false){}
                                    else{
                                        $matchTotalMinites = 12;
                                        $currentMinuteIn12 = explode(':',explode('^',$datainfo[48])[1])[0];
                                        $retimeset0 = explode('^',$datainfo[48])[0];
                                    }

                                    // 上半场
                                    if(
                                        ($matchTotalMinites==8 and $currentMinuteIn8>=3 and $retimeset0=='1H') or
                                        ($matchTotalMinites==10 and $currentMinuteIn10>=4 and $retimeset0=='1H') or
                                        ($matchTotalMinites==12 and $currentMinuteIn12>=5 and $retimeset0=='1H') or $mem_djft_off == 'off'
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
                                        ($matchTotalMinites==12 and $currentMinuteIn12>=10 and $retimeset0=='2H') or $mem_djft_off == 'off'

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

                            $allMethods=$datainfo[49]<5 ? 0:$datainfo[49];
                            if($datainfo[7]=="H"){
                                $ratio_mb_str=$datainfo[8];
                                $ratio_tg_str='';
                            }elseif($datainfo[7]=="C"){
                                $ratio_mb_str='';
                                $ratio_tg_str=$datainfo[8];
                            }
                            if($datainfo['HSTRONG']=="H"){
                                $hratio_mb_str=$datainfo['RATIO_HRE'];
                                $hratio_tg_str='';
                            }elseif($datainfo['HSTRONG']=="C"){
                                $hratio_mb_str='';
                                $hratio_tg_str=$datainfo['RATIO_HRE'];
                            }
                            $show=0;

                            $aData2[$i]['gid']=$datainfo[0];
                            $aData2[$i]['league']=$datainfo[2];
                            $DATETIME=$datainfo['DATETIME'];
                            $m_date=explode(' ', $DATETIME)[0];
                            $m_time=getMtime($DATETIME);
                            $aData2[$i]['M_Type']='';
                            $aData2[$i]['M_Time']=$m_time;
                            $aData2[$i]['M_Date']=$m_date;
                            $aData2[$i]['gnum_h']=$datainfo['GNUM_H']?$datainfo['GNUM_H']:'';
                            $aData2[$i]['gnum_c']=$datainfo['GNUM_C']?$datainfo['GNUM_C']:'';
                            $aData2[$i]['team_h']=$datainfo['TEAM_H'];
                            $aData2[$i]['team_h_for_sort']=explode(' ',$datainfo['TEAM_H'])[0];
                            $aData2[$i]['team_c']=$datainfo['TEAM_C'];
                            $aData2[$i]['strong']=$datainfo['STRONG']?$datainfo['STRONG']:'';
                            $aData2[$i]['ratio']=$datainfo['RATIO_RE'];
                            $aData2[$i]['ratio_mb_str']=$ratio_mb_str;
                            $aData2[$i]['ratio_tg_str']=$ratio_tg_str;
                            if ($flushWay=='ra'){
                                $aData2[$i]['ior_RH']=$datainfo[9];; //让球大赔率
                                $aData2[$i]['ior_RC']=$datainfo[10];; //让球小赔率
                            }else{
                            $aData2[$i]['ior_RH']=$datainfo['IOR_REH']>0?round_num($datainfo['IOR_REH']):''; //让球大赔率
                            $aData2[$i]['ior_RC']=$datainfo['IOR_REC']>0?round_num($datainfo['IOR_REC']):''; //让球小赔率
                            }
                            $aData2[$i]['ratio_o']=$datainfo['RATIO_ROUO'];
                            $aData2[$i]['ratio_u']=$datainfo['RATIO_ROUU'];
                            $aData2[$i]['ratio_o_str']="大".str_replace('O','',$datainfo['RATIO_ROUO']);
                            $aData2[$i]['ratio_u_str']="小".str_replace('U','',$datainfo['RATIO_ROUU']);
                            if ($flushWay=='ra'){
                                $aData2[$i]['ior_OUH']=$datainfo[13];
                                $aData2[$i]['ior_OUC']=$datainfo[14];
                            }
                            else{
                            $aData2[$i]['ior_OUH']=$datainfo['IOR_ROUH']>0?round_num($datainfo['IOR_ROUH']):''; //小两位小数
                            $aData2[$i]['ior_OUC']=$datainfo['IOR_ROUC']>0?round_num($datainfo['IOR_ROUC']):''; //大两位小数
                            }
                            $aData2[$i]['redcard_h']=$datainfo['REDCARD_H']?$datainfo['REDCARD_H']:''; // 主队红牌数
                            $aData2[$i]['redcard_c']=$datainfo['REDCARD_C']?$datainfo['REDCARD_C']:''; // 客队红牌数
                            $aData2[$i]['eventid']=$datainfo['EVENTID']?$datainfo['EVENTID']:'';
                            $aData2[$i]['hot']=$datainfo['HOT']?$datainfo['HOT']:'';
                            $aData2[$i]['play']=$datainfo['PLAY']?$datainfo['PLAY']:'';
                            $aData2[$i]['more']=$show;
                            $aData2[$i]['all']=$allMethods;
                            $aData2[$i]['ior_MH']=$datainfo['IOR_RMH']>0?round_num($datainfo['IOR_RMH']):'';
                            $aData2[$i]['ior_MC']=$datainfo['IOR_RMC']>0?round_num($datainfo['IOR_RMC']):'';
                            $aData2[$i]['ior_MN']=$datainfo['IOR_RMN']>0?round_num($datainfo['IOR_RMN']):'';
                            $aData2[$i]['ior_HMH']=$datainfo['IOR_HRMH']>0?round_num($datainfo['IOR_HRMH']):'';
                            $aData2[$i]['ior_HMC']=$datainfo['IOR_HRMC']>0?round_num($datainfo['IOR_HRMC']):'';
                            $aData2[$i]['ior_HMN']=$datainfo['IOR_HRMN']>0?round_num($datainfo['IOR_HRMN']):'';
                            $aData2[$i]['ior_EOO']=$datainfo['IOR_REOO']>0?$datainfo['IOR_REOO']:'';
                            $aData2[$i]['ior_EOE']=$datainfo['IOR_REOE']>0?$datainfo['IOR_REOE']:'';
                            $aData2[$i]['hgid']  =$datainfo['HGID']?$datainfo['HGID']:'';
                            $aData2[$i]['hstrong']=$datainfo['HSTRONG']?$datainfo['HSTRONG']:'';
                            $aData2[$i]['hratio'] =$datainfo['RATIO_HRE'];
                            $aData2[$i]['hratio_mb_str']=$hratio_mb_str?$hratio_mb_str:'';
                            $aData2[$i]['hratio_tg_str']=$hratio_tg_str?$hratio_tg_str:'';
                            if ($flushWay=='ra'){
                                $aData2[$i]['ior_HRH']=$datainfo[23];
                                $aData2[$i]['ior_HRC']=$datainfo[24];
                            }
                            else{
                            $aData2[$i]['ior_HRH']=$datainfo['IOR_HREH']?$datainfo['IOR_HREH']:'';
                            $aData2[$i]['ior_HRC']=$datainfo['IOR_HREC']?$datainfo['IOR_HREC']:'';
                            }
                            $aData2[$i]['hratio_o']=$datainfo['RATIO_ROUHO']?$datainfo['RATIO_ROUHO']:'';
                            $aData2[$i]['hratio_u']=$datainfo['RATIO_ROUHU']?$datainfo['RATIO_ROUHU']:'';
                            $aData2[$i]['hratio_o_str']="大".str_replace('O','',$datainfo['RATIO_HROUO']);
                            $aData2[$i]['hratio_u_str']="小".str_replace('U','',$datainfo['RATIO_HROUU']);
                            if ($flushWay=='ra'){
                                $aData2[$i]['ior_HOUH']=$datainfo[27];
                                $aData2[$i]['ior_HOUC']=$datainfo[28];
                            }
                            else{
                            $aData2[$i]['ior_HOUH']=$datainfo['IOR_HROUH']?$datainfo['IOR_HROUH']:''; // 半场小 客队
                            $aData2[$i]['ior_HOUC']=$datainfo['IOR_HROUC']?$datainfo['IOR_HROUC']:''; // 半场大 主队
                            }

                            if ($datainfo['TIMER']=='半场'){
                                $showretime=$datainfo['TIMER'];
                            }else{
                            $tmpset=explode("^", $datainfo['RETIMESET']);
                            $tmpset[1]=str_replace("<font style=background-color=red>","",$tmpset[1]);
                            $tmpset[1]=str_replace("</font>","",$tmpset[1]);
                            $showretime="";
                            if($tmpset[0]=="Start"){
                                $showretime="-";
                            }else if($tmpset[0]=="MTIME" || $tmpset[0]=="196"){
                                $showretime=$tmpset[1];
                            }else{
                                if($tmpset[0]=="1H"){$showretime="上半场 ".$tmpset[1]."'";}
                                if($tmpset[0]=="2H"){$showretime="下半场 ".$tmpset[1]."'";}
                            }
                            }
                            $aData2[$i]['showretime']=$showretime;
                            $aData2[$i]['lastestscore_h'] =$datainfo['LASTESTSCORE_H']?$datainfo['LASTESTSCORE_H']:'';
                            $aData2[$i]['lastestscore_c'] =$datainfo['LASTESTSCORE_C']?$datainfo['LASTESTSCORE_C']:'';
                            $aData2[$i]['score_h']=$datainfo['SCORE_H'];
                            $aData2[$i]['score_c']=$datainfo['SCORE_C'];
                            if ($datainfo['GIDMASTER']>10000){ $aData2[$i]['gidMaster']=$datainfo['GIDMASTER']; }
                            if ($flushWay=='ra'){
                                $aData2[$i]['eps']=$aData2[$i]['handicaps']=$aData2[$i]['goalsou']=$aData2[$i]['corners']=$aData2[$i]['bookings']=$aData2[$i]['bookings']=$aData2[$i]['toqualify']=$aData2[$i]['penalty']=$aData2[$i]['extratime']='N';
                                // R_COUNT 让球，OU_COUNT 大小，CN_COUNT 角球，RN_COUNT 罚牌，WI_COUNT 会晋级，ET_COUNT 加时赛
                                // 标签开关 特优赔率、让球、进球大小、角球、罚球、会晋级
                                /*if ($datainfo['eps']){
                                    $aData2[$gid]['eps']='Y';
                                }*/
                                if ($datainfo['R_COUNT']>0){
                                    $aData2[$i]['handicaps']='Y';
                                }
                                if ($datainfo['OU_COUNT']>0){
                                    $aData2[$i]['goalsou']='Y';
                                }
                                if ($datainfo['CN_COUNT']>0){
                                    $aData2[$i]['corners']='Y';
                                }
                                if ($datainfo['RN_COUNT']>0){ // 罚牌
                                    $aData2[$i]['bookings']='Y';
                                }
                                if ($datainfo['WI_COUNT']>0){ // 会晋级
                                    $aData2[$i]['toqualify']='Y';
                                }
                                if ($datainfo['ET_COUNT']>0){ // 加时赛
                                    $aData2[$i]['extratime']='Y';
                                }
                                if ($datainfo['PK_COUNT']>0){ // 点球大战
                                    $aData2[$i]['penalty']='Y';
                                }

                            }
                            else{
                                $aData2[$i]['eps']=$datainfo['eps']; // 特优赔率开关
                                $aData2[$i]['handicaps']=$datainfo['handicaps']; // 让球开关
                                $aData2[$i]['goalsou']=$datainfo['goalsou']; // 大小开关
                                $aData2[$i]['corners']=$datainfo['corners']; // 角球开关
                                $aData2[$i]['bookings']=$datainfo['bookings']; // 罚牌开关
                                $aData2[$i]['bookings']='N'; // 罚牌开关
                                $aData2[$i]['toqualify']=$datainfo['toqualify']; // 会晋级开关
                                $aData2[$i]['penalty']=$datainfo['penalty']; // 点球大战开关
                                $aData2[$i]['extratime']=$datainfo['extratime']; // 加时的标签菜单开关
                            }

                        }

                    }

                    // 按照gid 从小到大排序
                    foreach ($aData2 as $k => $v){
                        $newDataArray[$v['gid']]=$v;
                    }

                    // 按照队伍，gid分组
                    $newDataArray = array_values(group_same_key($newDataArray,'team_h_for_sort'));
                    foreach ($newDataArray as $k => $v){
                        $val_sort = array_sort($v,'gid',$type='asc');
                        foreach ($val_sort as $k2=>$v2){
                            $newDataArray2[] = $v2;
                        }
                    }

                    $aData2=$newDataArray2;

                    $reBallCountCur = $cou;
                    break;
                }
                break;	//----------------------------足球滚球

            case 'rpd':  // 滚球波胆

                $key='FT_PD';
                $matchesJson = $redisObj->getSimpleOne($key);
                $matches = json_decode($matchesJson,true);
                $cou=sizeof($matches);
                foreach ($matches as $k => $v){
                    $datainfo=$v;
                    // 如果是正网就根据gid 返回数据，如果是6686则全部返回
                    if ($flushWay=='ra686'){
                        if ($gid){$pos = strpos(trim($gid), trim($datainfo['GID']));}
                        else{$pos = true;}
                    }else{
                        if ($gid){$pos = strpos(trim($gid), trim($datainfo['GID']));}
                        else{$pos = true;}
                    }

//                    $pos = strpos($gid, $v['GID']);
                    if ($pos!==false) {

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

                    }
                }
                $aData2 = $newDataArray;
                break;

            case 'spd': // 今日波胆

                $resulTotal=$redisObj->getSimpleOne("TODAY_FT_PD");
                $matches = json_decode($resulTotal,true);
                $cou=sizeof($matches);
                foreach ($matches as $k => $row){
//                    $pos = strpos($gid, $row['MID']);
                    // 如果是正网就根据gid 返回数据，如果是6686则全部返回
                    if ($flushWay=='ra686'){
                        if ($gid){$pos = strpos(trim($gid), trim($row['MID']));}
                        else{$pos = true;}
                    }else{
                        if ($gid){$pos = strpos(trim($gid), trim($row['MID']));}
                        else{$pos = true;}
                    }
                    if ($pos!==false) {
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
                }
                $aData2 = $newDataArray;
                break;

            case 'fupd':
                $resulTotal=$redisObj->getSimpleOne("FUTURE_PD");
                $future_r_data = json_decode($resulTotal,true);
                $cou=sizeof($future_r_data);
                foreach ($future_r_data as $i => $row){
                    $pos = strpos($gid, $row['MID']);
                    if ($pos!==false) {
                        $m_date=strtotime($future_r_data[$i]['M_Date']);
                        $dates=date("m-d",$m_date);
                        $MB_Team=$future_r_data[$i]['MB_Team'];
                        $MB_Team=str_replace("[Mid]","<font color=\'#005aff\'>[N]</font>",$MB_Team);
                        $MB_Team=str_replace("[中]","<font color=\'#005aff\'>[中]</font>",$MB_Team);
                        $MID=$future_r_data[$i]['MID'];
                        $pos = strpos($future_r_data[$i]['M_League'],'电竞足球');
                        $pos_zh_tw = strpos($future_r_data[$i]['M_League'],'電競足球');
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
                $aData2=$newDataArray;
                break;
        }
        break;	//----------------------------整个足球

    case 'BU':// 篮球早盘
        $returnData = $redisObj->getSimpleOne("FUTURE_BK_ALL");
        $aData = json_decode($returnData,true);

        // 有的时候刷水简体数据为空，则显示繁体的数据（联赛名称、主队队名、客队队名）
        foreach ($aData as $k => $v) {
            if ( strlen($v['M_League'])>0 ){
                $aData[$k]['M_League_tw'] = $aData[$k]['M_League'] = str_replace('<font color=gray>','',$aData[$k]['M_League']);
                $aData[$k]['M_League_tw'] = $aData[$k]['M_League'] = str_replace('</font>','',$aData[$k]['M_League']);
                $aData[$k]['MB_Team_tw'] = $aData[$k]['MB_Team'] = str_replace('<font color=gray>','',$aData[$k]['MB_Team']);
                $aData[$k]['MB_Team_tw'] = $aData[$k]['MB_Team'] = str_replace('</font>','',$aData[$k]['MB_Team']);
                $aData[$k]['TG_Team_tw'] = $aData[$k]['TG_Team'] = str_replace('<font color=gray>','',$aData[$k]['TG_Team']);
                $aData[$k]['TG_Team_tw'] = $aData[$k]['TG_Team'] = str_replace('</font>','',$aData[$k]['TG_Team']);
            }
            else{
                $aData[$k]['M_League_tw'] = $aData[$k]['M_League'] = str_replace('<font color=gray>','',$aData[$k]['M_League_tw']);
                $aData[$k]['M_League_tw'] = $aData[$k]['M_League'] = str_replace('</font>','',$aData[$k]['M_League_tw']);
                $aData[$k]['MB_Team_tw'] = $aData[$k]['MB_Team'] = str_replace('<font color=gray>','',$aData[$k]['MB_Team_tw']);
                $aData[$k]['MB_Team_tw'] = $aData[$k]['MB_Team'] = str_replace('</font>','',$aData[$k]['MB_Team_tw']);
                $aData[$k]['TG_Team_tw'] = $aData[$k]['TG_Team'] = str_replace('<font color=gray>','',$aData[$k]['TG_Team_tw']);
                $aData[$k]['TG_Team_tw'] = $aData[$k]['TG_Team'] = str_replace('</font>','',$aData[$k]['TG_Team_tw']);
            }
        }
        $aData2=[];
        foreach ($aData as $k => $v){
            //$pos = strpos($gid, $v['MID']);
            if ($flushWay=='ra686'){
                if ($gid){$pos = strpos(trim($gid), $v['MID']);}
                else{$pos = true;}
                if(strlen($v['MID']) == 9) {$matchId = substr($v['MID'] , 0, -2);}
            }else{
                $pos = strpos(trim($gid), $v['MID']);
            }
            if ($pos!==false) {
                if ($v['MB_MID']) {  // 防止空数据

                    $ra_rate=get_other_ioratio(GAME_POSITION,$v["MB_LetB_Rate"],$v["TG_LetB_Rate"],100); // 默认都是香港盘
                    $MB_LetB_Rate=$ra_rate[0];
                    $TG_LetB_Rate=$ra_rate[1];
                    $MB_LetB_Rate = change_rate($open, $MB_LetB_Rate);
                    $TG_LetB_Rate = change_rate($open, $TG_LetB_Rate);
                    $ra_rate=get_other_ioratio(GAME_POSITION,$v["MB_Dime_Rate"],$v["TG_Dime_Rate"],100); // 默认都是香港盘
                    $MB_Dime_Rate=$ra_rate[0];
                    $TG_Dime_Rate=$ra_rate[1];
                    $MB_Dime_Rate = change_rate($open, $MB_Dime_Rate);
                    $TG_Dime_Rate = change_rate($open, $TG_Dime_Rate);
                    $ra_rate=get_other_ioratio(GAME_POSITION,$v["MB_Dime_Rate_H"],$v["MB_Dime_Rate_S_H"],100); // 默认都是香港盘
                    $MB_Dime_Rate_H=$ra_rate[0];
                    $MB_Dime_Rate_S_H=$ra_rate[1];
                    $ra_rate=get_other_ioratio(GAME_POSITION,$v["TG_Dime_Rate_H"],$v["TG_Dime_Rate_S_H"],100); // 默认都是香港盘
                    $TG_Dime_Rate_H=$ra_rate[0];
                    $TG_Dime_Rate_S_H=$ra_rate[1];


                    if($v['ShowTypeR']=="H"){
                        $ratio_mb_str=$v['M_LetB'];
                        $ratio_tg_str='';
                    }elseif($v['ShowTypeR']=="C"){
                        $ratio_mb_str='';
                        $ratio_tg_str=$v['M_LetB'];
                    }

                    $aData2[$k]['gid'] = $v['MID'];
                    $aData2[$k]['matchId'] = $matchId;      //同一场赛事
                    $aData2[$k]['M_Type'] = $v['M_Type']; // 1 显示滚球字样
                    $aData2[$k]['M_Time'] = $v['M_Time']; // 比赛开始时间
                    $aData2[$k]['M_Date'] = $v['M_Date']; // 比赛开始日期
                    $aData2[$k]['league'] = $v['M_League']; // 联盟名称
                    $aData2[$k]['gnum_h'] = $v['MB_MID'];
                    $aData2[$k]['gnum_c'] = $v['TG_MID'];
                    $aData2[$k]['team_h'] = $v['MB_Team'];
                    $aData2[$k]['team_c'] = $v['TG_Team'];
                    $aData2[$k]['strong'] = $v['ShowTypeR']?$v['ShowTypeR']:''; // 谁让球
                    $aData2[$k]['ratio'] = $v['M_LetB']; // 让球数
                    $aData2[$k]['ratio_mb_str'] = $ratio_mb_str ; // 主队让球数
                    $aData2[$k]['ratio_tg_str'] = $ratio_tg_str ; // 客队让球数
                    $aData2[$k]['ior_RH'] = $MB_LetB_Rate;
                    $aData2[$k]['ior_RC'] = $TG_LetB_Rate;
                    $aData2[$k]['ratio_o'] = $v['MB_Dime'];
                    $aData2[$k]['ratio_u'] = $v['TG_Dime'];
                    $aData2[$k]['ratio_o_str'] = !empty($v['MB_Dime'])? "大".str_replace('O', '', $v['MB_Dime']):'';
                    $aData2[$k]['ratio_u_str'] = !empty($v['TG_Dime'])? "小".str_replace('U', '', $v['TG_Dime']):'';
                    $aData2[$k]['ior_OUH'] = $TG_Dime_Rate;
                    $aData2[$k]['ior_OUC'] = $MB_Dime_Rate;
                    $aData2[$k]['redcard_h']=''; // 主队红牌数
                    $aData2[$k]['redcard_c']=''; // 客队红牌数
                    $aData2[$k]['ior_EOO'] = change_rate($open,$v['S_Single_Rate']);  // 主队单
                    $aData2[$k]['ior_EOE'] = change_rate($open,$v['S_Double_Rate']);  // 客队双
                    $aData2[$k]['ratio_ouho'] = $v['MB_Dime_H'];  // 第1队
                    $aData2[$k]['ratio_ouhu'] = $v['MB_Dime_S_H'];
                    $aData2[$k]['ratio_ouho_str'] = !empty($v['MB_Dime_H'])? "大".str_replace('O','',$v['MB_Dime_H']):'';
                    $aData2[$k]['ratio_ouhu_str'] = !empty($v['MB_Dime_S_H'])? "小".str_replace('U','',$v['MB_Dime_S_H']):'';
                    $aData2[$k]['ior_OUHO'] = change_rate($open,$MB_Dime_Rate_H);
                    $aData2[$k]['ior_OUHU'] = change_rate($open,$MB_Dime_Rate_S_H);
                    $aData2[$k]['ratio_ouco'] =$v['TG_Dime_H'];  // 第2队
                    $aData2[$k]['ratio_oucu'] =$v['TG_Dime_S_H'];
                    $aData2[$k]['ratio_ouco_str'] = !empty($v['TG_Dime_H'])? "大".str_replace('O','',$v['TG_Dime_H']):'';
                    $aData2[$k]['ratio_oucu_str'] = !empty($v['TG_Dime_S_H'])? "小".str_replace('U','',$v['TG_Dime_S_H']):'';
                    $aData2[$k]['ior_OUCO'] = change_rate($open,$TG_Dime_Rate_H);
                    $aData2[$k]['ior_OUCU'] = change_rate($open,$TG_Dime_Rate_S_H);
                    $aData2[$k]['more'] = $show?$show:'';
                    $aData2[$k]['all'] = $v['more'];
                    $aData2[$k]['eventid'] = $v['Eventid'];
                    $aData2[$k]['hot'] = $v['Hot'];
                    $aData2[$k]['play'] = $v['Play'];
                    $aData2[$k]['showretime']='';
                    $aData2[$k]['lastestscore_h'] ='';
                    $aData2[$k]['lastestscore_c'] ='';
                    $aData2[$k]['score_h']='';
                    $aData2[$k]['score_c']='';
                    // 标签开关 特优赔率、让球、进球大小、角球、罚球、会晋级
                    $aData2[$k]['eps']='N';
                    $aData2[$k]['handicaps']='N';
                    $aData2[$k]['goalsou']='N';
                    $aData2[$k]['corners']='N';
                    $aData2[$k]['bookings']='N';
                    $aData2[$k]['toqualify']='N';

                }
            }
        }

        $cou=count($aData2);
        break;

    case 'BK':
        switch ($more){
            case 's':// 篮球今日赛事
                $returnData = $redisObj->getSimpleOne("TODAY_BK_M_ROU_EO");
                $aData = json_decode($returnData,true);

                $aData2=[];
                foreach ($aData as $k => $v){
                    //$pos = strpos($gid, $v['MID']);
                    if ($flushWay=='ra686'){
                        if ($gid){$pos = strpos(trim($gid), $v['MID']);}
                        else{$pos = true;}
                        if(strlen($v['MID']) == 9) {$matchId = substr($v['MID'] , 0, -2);}
                    }else{
                        $pos = strpos(trim($gid), $v['MID']);
                    }
                    if ($pos!==false) {
                        $ra_rate=get_other_ioratio(GAME_POSITION,$v["MB_LetB_Rate"],$v["TG_LetB_Rate"],100); // 默认都是香港盘
                        $MB_LetB_Rate=$ra_rate[0];
                        $TG_LetB_Rate=$ra_rate[1];
                        $MB_LetB_Rate = change_rate($open, $MB_LetB_Rate);
                        $TG_LetB_Rate = change_rate($open, $TG_LetB_Rate);
                        $ra_rate=get_other_ioratio(GAME_POSITION,$v["MB_Dime_Rate"],$v["TG_Dime_Rate"],100); // 默认都是香港盘
                        $MB_Dime_Rate=$ra_rate[0];
                        $TG_Dime_Rate=$ra_rate[1];
                        $MB_Dime_Rate = change_rate($open, $MB_Dime_Rate);
                        $TG_Dime_Rate = change_rate($open, $TG_Dime_Rate);
                        $ra_rate=get_other_ioratio(GAME_POSITION,$v["MB_Dime_Rate_H"],$v["MB_Dime_Rate_S_H"],100); // 默认都是香港盘
                        $MB_Dime_Rate_H=$ra_rate[0];
                        $MB_Dime_Rate_S_H=$ra_rate[1];
                        $ra_rate=get_other_ioratio(GAME_POSITION,$v["TG_Dime_Rate_H"],$v["TG_Dime_Rate_S_H"],100); // 默认都是香港盘
                        $TG_Dime_Rate_H=$ra_rate[0];
                        $TG_Dime_Rate_S_H=$ra_rate[1];

                        if($v['ShowTypeR']=="H"){
                            $ratio_mb_str=$v['M_LetB'];
                            $ratio_tg_str='';
                        }elseif($v['ShowTypeR']=="C"){
                            $ratio_mb_str='';
                            $ratio_tg_str=$v['M_LetB'];
                        }
                        $MB_Team = explode('<font color=gray>',$v['MB_Team']);
                        $TG_Team = explode('<font color=gray>',$v['TG_Team']);

                        $aData2[$k]['gid'] = $v['MID'];
                        $aData2[$k]['matchId'] = $matchId;      //同一场赛事
                        $aData2[$k]['M_Type'] = $v['M_Type']; // 1 显示滚球字样
                        $aData2[$k]['M_Time'] = $v['M_Time']; // 比赛开始时间
                        $aData2[$k]['M_Date'] = date('m-d'); // 比赛开始日期
                        $aData2[$k]['league'] = $v['M_League']; // 联盟名称
                        $aData2[$k]['gnum_h'] = $v['MB_MID'];
                        $aData2[$k]['gnum_c'] = $v['TG_MID'];
                        $aData2[$k]['team_h'] = $MB_Team[0].substr($MB_Team[1],0,-7);
                        $aData2[$k]['team_c'] = $TG_Team[0].substr($TG_Team[1],0,-7);
                        $aData2[$k]['strong'] = $v['ShowTypeR']?$v['ShowTypeR']:''; // 谁让球
                        $aData2[$k]['ratio'] = $v['M_LetB']; // 让球数
                        $aData2[$k]['ratio_mb_str'] = $ratio_mb_str ; // 主队让球数
                        $aData2[$k]['ratio_tg_str'] = $ratio_tg_str ; // 客队让球数
                        $aData2[$k]['ior_RH'] = $MB_LetB_Rate;
                        $aData2[$k]['ior_RC'] = $TG_LetB_Rate;
                        $aData2[$k]['ratio_o'] = $v['MB_Dime'];
                        $aData2[$k]['ratio_u'] = $v['TG_Dime'];
                        $aData2[$k]['ratio_o_str'] = !empty($v['MB_Dime'])? "大".str_replace('O', '', $v['MB_Dime']):'';
                        $aData2[$k]['ratio_u_str'] = !empty($v['TG_Dime'])? "小".str_replace('U', '', $v['TG_Dime']):'';
                        $aData2[$k]['ior_OUH'] = $TG_Dime_Rate;
                        $aData2[$k]['ior_OUC'] = $MB_Dime_Rate;
                        $aData2[$k]['redcard_h']=''; // 主队红牌数
                        $aData2[$k]['redcard_c']=''; // 客队红牌数
                        $aData2[$k]['ior_EOO'] = change_rate($open,$v['S_Single_Rate']);  // 主队单
                        $aData2[$k]['ior_EOE'] = change_rate($open,$v['S_Double_Rate']);  // 客队双
                        $aData2[$k]['ratio_ouho'] = $v['MB_Dime_H'];  // 第1队
                        $aData2[$k]['ratio_ouhu'] = $v['MB_Dime_S_H'];
                        $aData2[$k]['ratio_ouho_str'] = !empty($v['MB_Dime_H'])? "大".str_replace('O','',$v['MB_Dime_H']):'';
                        $aData2[$k]['ratio_ouhu_str'] = !empty($v['MB_Dime_S_H'])? "小".str_replace('U','',$v['MB_Dime_S_H']):'';
                        $aData2[$k]['ior_OUHO'] = change_rate($open,$MB_Dime_Rate_H);
                        $aData2[$k]['ior_OUHU'] = change_rate($open,$MB_Dime_Rate_S_H);
                        $aData2[$k]['ratio_ouco'] =$v['TG_Dime_H'];  // 第2队
                        $aData2[$k]['ratio_oucu'] =$v['TG_Dime_S_H'];
                        $aData2[$k]['ratio_ouco_str'] = !empty($v['TG_Dime_H'])? "大".str_replace('O','',$v['TG_Dime_H']):'';
                        $aData2[$k]['ratio_oucu_str'] = !empty($v['TG_Dime_S_H'])? "小".str_replace('U','',$v['TG_Dime_S_H']):'';
                        $aData2[$k]['ior_OUCO'] = change_rate($open,$TG_Dime_Rate_H);
                        $aData2[$k]['ior_OUCU'] = change_rate($open,$TG_Dime_Rate_S_H);
                        $aData2[$k]['more'] = $show?$show:'';
                        $aData2[$k]['all'] = $v['more'];
                        $aData2[$k]['eventid'] = $v['Eventid'];
                        $aData2[$k]['hot'] = $v['Hot'];
                        $aData2[$k]['play'] = $v['Play'];
                        $aData2[$k]['showretime']='';
                        $aData2[$k]['lastestscore_h'] ='';
                        $aData2[$k]['lastestscore_c'] ='';
                        $aData2[$k]['score_h']='';
                        $aData2[$k]['score_c']='';
                        // 标签开关 特优赔率、让球、进球大小、角球、罚球、会晋级
                        $aData2[$k]['eps']='N';
                        $aData2[$k]['handicaps']='N';
                        $aData2[$k]['goalsou']='N';
                        $aData2[$k]['corners']='N';
                        $aData2[$k]['bookings']='N';
                        $aData2[$k]['toqualify']='N';

                    }
                }

                $cou=count($aData2);
                break;
            case 'r':// 篮球滚球
                $returnData = $redisObj->getSimpleOne("BK_M_ROU_EO");
                $matches = json_decode($returnData,true) ;
                $datainfos = $matches;
                if(is_array($matches)){
                    $cou=sizeof($matches);
                }else{
                    $cou=0;
                }

                if($cou>0){



                    foreach ($datainfos as $i => $datainfo){
                        /*if ($flushWay=='ra686'){ //如果是6686则显示主盘口
                            if(!isset($datainfo['isMaster'])) {continue;}
                        }*/
//                        $messages = $matches[$i];
//                        $messages = str_replace(");", ")", $messages);
//                        $messages = str_replace("cha(9)", "", $messages);
//                        $datainfo = eval("return $messages;");

//                        $datainfo = $matches[$i];
                        $datainfo[0]=$datainfo['gid'];
                        $datainfo[2]=$datainfo['league'];
                        $datainfo[3]=$datainfo['gnum_h'];
                        $datainfo[4]=$datainfo['gnum_c'];
                        $datainfo[5]=$datainfo['team_h'];
                        $datainfo[6]=$datainfo['team_c'];
                        $datainfo[52]=$datainfo['se_now']?$datainfo['se_now']:'';
                        $datainfo[53]=$datainfo['SCORE_H']?$datainfo['SCORE_H']:'';
                        $datainfo[54]=$datainfo['SCORE_C']?$datainfo['SCORE_C']:'';
                        $datainfo[56]=$datainfo['LASTTIME']?$datainfo['LASTTIME']:'';
                        $datainfo[7]=$datainfo['strong'];
                        $datainfo[8]=$datainfo['ratio_re'];
                        $datainfo[35]=$datainfo['ratio_rouho']; //第1队
                        $datainfo[36]=$datainfo['ratio_rouhu'];
                        $datainfo[37]=$datainfo['ior_ROUHO'];
                        $datainfo[38]=$datainfo['ior_ROUHU'];
                        $datainfo[39]=$datainfo['ratio_rouco']; //第2队
                        $datainfo[40]=$datainfo['ratio_roucu'];
                        $datainfo[41]=$datainfo['ior_ROUCO'];
                        $datainfo[42]=$datainfo['ior_ROUCU'];
                        $datainfo[25]=$datainfo['MORE']?$datainfo['MORE']:'';

//                        $pos = strpos($gid, $datainfo[0]);
                        // 如果是正网就根据gid 返回数据，如果是6686则全部返回
                        if ($flushWay=='ra686'){
                            if ($gid){$pos = strpos(trim($gid), trim($datainfo[0]));}
                            else{$pos = true;}
                        }else{
                            if ($gid){$pos = strpos(trim($gid), trim($datainfo[0]));}
                            else{$pos = true;}
                        }
                        if ($pos!==false){
//                        if ($queryleague==$datainfo['league']){

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

                                // $allMethods=$datainfo[49]<5 ? 0:$datainfo[49]; // 篮球不需要这样处理
                                if($datainfo[7]=="H"){
                                    $ratio_mb_str=$datainfo[8];
                                    $ratio_tg_str='';
                                }elseif($datainfo[7]=="C"){
                                    $ratio_mb_str='';
                                    $ratio_tg_str=$datainfo[8];
                                }
                                $MB_Team = explode('<font color=gray>',$datainfo[5]);
                                $TG_Team = explode('<font color=gray>',$datainfo[6]);

                                $tmpObj[$i]['gid']=$datainfo[0];
                                $tmpObj[$i]['matchId']=$datainfo['matchId'];    //同一场赛事
                                $tmpObj[$i]['se_now']=$datainfo[52];
                                $tmpObj[$i]['t_count']=$datainfo[56];
                                $tmpObj[$i]['M_Type']='';
                                $tmpObj[$i]['M_Time']=$datainfo['M_Time']; // 比赛开始时间
                                $tmpObj[$i]['M_Date']=date('m-d'); // 比赛开始日期
                                $tmpObj[$i]['league']=$datainfo[2];
                                $tmpObj[$i]['gnum_h']=$datainfo[3];
                                $tmpObj[$i]['gnum_c']=$datainfo[4];
                                $tmpObj[$i]['team_h'] = $MB_Team[0].substr($MB_Team[1],0,-7);
                                $tmpObj[$i]['team_c'] = $TG_Team[0].substr($TG_Team[1],0,-7);
                                $tmpObj[$i]['strong']=$datainfo[7]?$datainfo[7]:'';
                                $tmpObj[$i]['ratio']=$datainfo[8];
                                $tmpObj[$i]['ratio_mb_str']=$ratio_mb_str;
                                $tmpObj[$i]['ratio_tg_str']=$ratio_tg_str;
                                $tmpObj[$i]['ior_RH']=$datainfo['ior_REH']>0?round_num($datainfo['ior_REH']):''; //让球大赔率
                                $tmpObj[$i]['ior_RC']=$datainfo['ior_REC']>0?round_num($datainfo['ior_REC']):''; //让球小赔率
                                $tmpObj[$i]['ratio_o']=$datainfo['ratio_rouo']?$datainfo['ratio_rouo']:'';
                                $tmpObj[$i]['ratio_u']=$datainfo['ratio_rouu']?$datainfo['ratio_rouu']:'';
                                $tmpObj[$i]['ratio_o_str']=!empty($datainfo['ratio_rouo'])? "大".str_replace('O','',$datainfo['ratio_rouo']):'';
                                $tmpObj[$i]['ratio_u_str']=!empty($datainfo['ratio_rouu'])? "小".str_replace('U','',$datainfo['ratio_rouu']):'';
                                $tmpObj[$i]['ior_OUH']=$datainfo['ior_ROUH']>0?round_num($datainfo['ior_ROUH']):''; //小两位小数
                                $tmpObj[$i]['ior_OUC']=$datainfo['ior_ROUC']>0?round_num($datainfo['ior_ROUC']):''; //大两位小数
                                $tmpObj[$i]['redcard_h']=''; // 主队红牌数
                                $tmpObj[$i]['redcard_c']=''; // 客队红牌数
                                $tmpObj[$i]['ratio_ouho'] = $datainfo[35];  // 第1队  ratio_rouho
                                $tmpObj[$i]['ratio_ouhu'] = $datainfo[36];   //ratio_rouhu
                                $tmpObj[$i]['ratio_ouho_str'] = !empty($datainfo[35])? "大".str_replace('O','',$datainfo[35]):'';
                                $tmpObj[$i]['ratio_ouhu_str'] = !empty($datainfo[36])? "小".str_replace('U','',$datainfo[36]):'';
                                $tmpObj[$i]['ior_OUHO'] = change_rate($open,$datainfo[37]);    //ior_ROUHO
                                $tmpObj[$i]['ior_OUHU'] = change_rate($open,$datainfo[38]);    //ior_ROUHU
                                $tmpObj[$i]['ratio_ouco'] = $datainfo[39];  // 第2队  ratio_rouco
                                $tmpObj[$i]['ratio_oucu'] = $datainfo[40];     //ratio_roucu
                                $tmpObj[$i]['ratio_ouco_str'] = !empty($datainfo[39])? "大".str_replace('O','',$datainfo[39]):'';
                                $tmpObj[$i]['ratio_oucu_str'] = !empty($datainfo[40])? "小".str_replace('U','',$datainfo[40]):'';
                                $tmpObj[$i]['ior_OUCO'] = change_rate($open,$datainfo[41]);    //ior_ROUCO
                                $tmpObj[$i]['ior_OUCU'] = change_rate($open,$datainfo[42]);    //ior_ROUCU
                                $tmpObj[$i]['eventid']=$datainfo[43];
                                $tmpObj[$i]['hot']=$datainfo[44];
                                $tmpObj[$i]['play']=$datainfo[46];
                                $tmpObj[$i]['more']=$datainfo[25];
                                //$tmpObj[$i]['all']=$allMethods;
                                $tmpObj[$i]['all']= $datainfo[25] ;
                                $tmpObj[$i]['ior_MH']=$datainfo['ior_RMH']>0?round_num($datainfo['ior_RMH']):'';
                                $tmpObj[$i]['ior_MC']=$datainfo['ior_RMC']>0?round_num($datainfo['ior_RMC']):'';
                                $tmpObj[$i]['ior_MN']=$datainfo['ior_RMN']>0?round_num($datainfo['ior_RMN']):'';

                                // $datainfo[52] 球队名称 Q1-Q4 第一节-第四节，H1 上半场，H2 下半场 ，OT 加时，HT 半场
                                $team_active = $team_time = '';
                                // 优久乐数据判断处理（篮球滚球没有比分和时间，另行处理）
                                $match_time=explode("^", $datainfo[48]);
                                if($match_time[0] == 196){
                                    $team_active = '';
                                    $team_time = $match_time[2];
                                }else {
                                    if($datainfo[56] && $datainfo[56] > 0){ // 转化时间
                                        $team_hour = floor($datainfo[56]/3600); // 小时不要
                                        $team_minute = floor(($datainfo[56]-3600 * $team_hour)/60);
                                        $team_second = floor((($datainfo[56]-3600 * $team_hour) - 60 * $team_minute) % 60);
                                        $team_time = ($team_minute>9?$team_minute:"0".$team_minute).':'.($team_second>9?$team_second:"0".$team_second );
                                    }
                                    $mbTeamArr = explode('-', $datainfo[5]);
                                    preg_match('/\d+/', $mbTeamArr[1], $mbTeamArrList);
                                    if ($mbTeamArrList[0] == 2) {
                                        $team_active = '第二节';
                                        $tmpObj[$i]['headShow'] = 0;
                                    } elseif ($mbTeamArrList[0] == 3) {
                                        $team_active = '第三节';
                                        $tmpObj[$i]['headShow'] = 0;
                                    } elseif ($mbTeamArrList[0] == 4) {
                                        $team_active = '第四节';
                                        $tmpObj[$i]['headShow'] = 0;
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
                                }
                                $tmpObj[$i]['showretime']=$team_active.$team_time;
                                $tmpObj[$i]['lastestscore_h'] ='';
                                $tmpObj[$i]['lastestscore_c'] ='';
                                $tmpObj[$i]['score_h']=$datainfo[53];
                                $tmpObj[$i]['score_c']=$datainfo[54];
                                $tmpObj[$i]['team_h_a_team_c']=$MB_Team[0].'_'.$TG_Team[0];
                                // 标签开关 特优赔率、让球、进球大小、角球、罚球、会晋级
                                $tmpObj[$i]['eps']='N';
                                $tmpObj[$i]['handicaps']='N';
                                $tmpObj[$i]['goalsou']='N';
                                $tmpObj[$i]['corners']='N';
                                $tmpObj[$i]['bookings']='N';
                                $tmpObj[$i]['toqualify']='N';

                        }

                    }

                    // 按照主队和客队名称归类，方便控制显示篮球第3节
                    $tmpObj2 = group_same_key($tmpObj,'team_h_a_team_c');
                    $i=0;
                    foreach ($tmpObj2 as $k => $v){
                        $t_count = $tmpObj2[$k][0]['t_count'];
                        $se_now = $tmpObj2[$k][0]['se_now'];
                        foreach ($v as $k2 => $v2){
                            $aData2[$i] = $v2;
                            $aData2[$i]['t_count'] = $t_count;
                            $aData2[$i]['se_now'] = $se_now;
                            $i++;
                        }
                    }

                    //$check_str = explode('NBA2K',$datainfo[2]);  //电竞篮球  count($check_str)
                    foreach ($aData2 as $k => $datainfo){
                        $check_str = strpos($datainfo['league'],'NBA2');  //电竞篮球
                        if ( ($mem_djbk_off == 'off' and $check_str !== false) ||
                            (strpos($datainfo['team_h'], '-') !== false and $isClosedH1) ||
                            ($datainfo['se_now'] == 'H2' and $datainfo['t_count'] <= 1200 and $mem_bkq3_off == 'off') ||
                            ($datainfo['se_now'] == 'HT' and $datainfo['t_count'] > 0 and $datainfo['t_count'] <= 1190 and $mem_bkq3_off == 'off') ||
                            (($datainfo['se_now'] == 'Q3' || $datainfo['se_now'] == 'Q4' || $datainfo['se_now'] == 'H2' || $datainfo['se_now'] == 'OT' || $datainfo['se_now'] == 'HT') and $mem_bkq3_off == 'off') ||
                            $datainfo['se_now'] == 'Q4' || $datainfo['se_now'] == 'OT' || ($datainfo['se_now'] == 'H2' and $datainfo['t_count'] <= 600)) {

                            $aData2[$k] = getTmpObjByLeague($datainfo);

                        }
                    }



                    break;
                }
                break;
        }
        break;
    default: break;
}

$status = '200';
$describe = 'success';
if (count($aData2) == 0){
    original_phone_request_response($status, $describe, []);
}else{
    $aData2 = array_values($aData2);
    original_phone_request_response($status, $describe, $aData2);
}

// 制造空数据
function getTmpObjByLeague($datainfo){

    $tmpObj['gid']=$datainfo['gid'];
    $tmpObj['matchId']=$datainfo['matchId'];
    $tmpObj['M_Type']='';
    $tmpObj['se_now']=$datainfo['se_now'];
    $tmpObj['M_Time']='';
    $tmpObj['M_Date']='';
    $tmpObj['league']=$datainfo['league'];
    $tmpObj['gnum_h']=$datainfo['gnum_h'];
    $tmpObj['gnum_c']=$datainfo['gnum_c'];
    $tmpObj['team_h']=$datainfo['team_h'];
    $tmpObj['team_c']=$datainfo['team_c'];
    $tmpObj['strong']=$datainfo['strong']?$datainfo['strong']:'';
    $tmpObj['ratio']='';
    $tmpObj['ratio_mb_str']='';
    $tmpObj['ratio_tg_str']='';
    $tmpObj['ior_RH']='';
    $tmpObj['ior_RC']='';
    $tmpObj['ratio_o']='';
    $tmpObj['ratio_u']='';
    $tmpObj['ratio_o_str']='';
    $tmpObj['ratio_u_str']='';
    $tmpObj['ior_OUH']='';
    $tmpObj['ior_OUC']='';
    $tmpObj['redcard_h']=''; // 主队红牌数
    $tmpObj['redcard_c']=''; // 客队红牌数
    $tmpObj['eventid']='';
    $tmpObj['hot']='';
    $tmpObj['play']='';
    $tmpObj['more']='';
    $tmpObj['all']='';

    $tmpObj['showretime']=$datainfo['showretime'];
    $tmpObj['lastestscore_h'] ='';
    $tmpObj['lastestscore_c'] ='';
    $tmpObj['score_h']=$datainfo['score_h'];
    $tmpObj['score_c']=$datainfo['score_c'];
    // 标签开关 特优赔率、让球、进球大小、角球、罚球、会晋级
    $tmpObj['eps']='N';
    $tmpObj['handicaps']='N';
    $tmpObj['goalsou']='N';
    $tmpObj['corners']='N';
    $tmpObj['bookings']='N';
    $tmpObj['toqualify']='N';

    return $tmpObj;
}
