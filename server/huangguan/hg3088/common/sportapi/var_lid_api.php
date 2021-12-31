<?php
/**
 * /var_lid_api.php  体育联赛数据接口
 *
 * @param  gtype   FT 足球，BK 篮球
 * @param  showtype   RB 滚球 FT 今日赛事 FU 早盘
 * @param  sorttype   league 联盟排序  time 时间排序
 * @param  mdate  早盘日期
 */

//include_once('include/config.inc.php');
//require ("include/curl_http.php");
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
$langx=$_SESSION['Language']?$_SESSION['Language']:'zh-cn';
$uid=$_SESSION['Oid'];
$gtype = $_REQUEST['gtype'];
$showtype = $_REQUEST['showtype'];
$m_date = $_REQUEST['mdate']; // 只有早盘数据需要传入日期，今日赛事、滚球不需要
$sorttype = $_REQUEST['sorttype'];
$aData4 = [];
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
if(in_array('DJBK',$gameArr)){
    $mem_djbk_off = 'off';
}

//switch ($rtype){
//    case "r": // 单场
//        $type='S';
//        break;
//    case "hr":
//        $type='H';
//        break;
//    case "pd": // 波胆
//        $type='PD';
//        break;
//    case "hpd":
//        $type='HPD';
//        break;
//    case "t": // 总入球
//        $type='T';
//        break;
//    case "f": // 半场/全场
//        $type='F';
//        break;
//    case "p3": // 综合过关
//        $type='P3';
//        break;
//}
switch ($gtype){
    case 'FT':
        switch ($showtype){
            case 'RB':// 足球滚球
                $returnData = $redisObj->getSimpleOne("FT_M_ROU_EO");
                $matches = json_decode($returnData,true);
                if(is_array($matches)){
                    $cou=sizeof($matches);
                }else{
                    $cou=0;
                }
                if($cou>0) {
                    $k = 0;
                    $aData2=[];
                    for ($i = 0; $i < $cou; $i++) {
//                        $messages = $matches[$i];
//                        $messages = str_replace(");", ")", $messages);
//                        $messages = str_replace("cha(9)", "", $messages);
//                        $datainfo = eval("return $messages;");

                        $datainfo = $matches[$i];
                        $datainfo[0]=$datainfo['GID'];
                        $datainfo[2]=$datainfo['LEAGUE'];
                        $datainfo[47]=$datainfo['DATETIME'];

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
                        $aData2[$k]['MID']=$datainfo[0];
                        $aData2[$k]['M_League']=$datainfo[2];
                        $aData2[$k]['M_League_Initials']=_getFirstCharter($datainfo[2]);
                        $aData2[$k]['M_Time']= substr($datainfo[47],9,5);
                        $aData2[$k]['region']="滚球足球";
                        $k = $k+1;
                    }
                }

                break;
            case 'FT': // 足球今日赛事

                $returnData = $redisObj->getSimpleOne("TODAY_FT_M_ROU_EO");
                $aData = json_decode($returnData,true) ;

                // 从联赛信息中获取地区/国家
                $leagueRegion = $redisObj->getSimpleOne("TODAY_FT_LEAGUE_REGION");
                $aLeagueRegion = json_decode($leagueRegion,true);

                $aData2=[];
                foreach ($aData as $k => $v){
                    $pos = strpos($v['M_League'],'电竞足球');
                    $pos_zh_tw = strpos($v['M_League'],'電競足球');
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
                    $aData2[$k]['MID']=$v['MID'];
                    $aData2[$k]['M_League']=$v['M_League'];
                    $aData2[$k]['M_League_Initials']=_getFirstCharter($v['M_League']);
                    $aData2[$k]['M_Time']=$v['M_Time'];
                    $aData2[$k]['region']= isset($aLeagueRegion[$v['M_League']]) ? $aLeagueRegion[$v['M_League']] : '今日足球';
                }

                break;
            case 'FU': // 足球早盘

                $returnData = $redisObj->getSimpleOne("FUTURE_R");
                $aData = json_decode($returnData,true);

                // 从联赛信息中获取地区/国家
                $leagueRegion = $redisObj->getSimpleOne("FUTURE_FT_LEAGUE_REGION");
                $aLeagueRegion = json_decode($leagueRegion,true);

                $aData2=[];
                foreach ($aData as $k => $v){
                    $pos = strpos($v['M_League'],'电竞足球');
                    $pos_zh_tw = strpos($v['M_League'],'電競足球');
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

                    // 传入日期，只处理并返回对应日期的数据
                    if ($m_date){ // 返回对应日期的数据

                        if ($v['M_Date'] == $m_date){
                            $aData2[$k]['MID']=$v['MID'];
                            $aData2[$k]['M_League']=$v['M_League'];
                            $aData2[$k]['M_League_Initials']=_getFirstCharter($v['M_League']);
                            $aData2[$k]['M_Time']=$v['M_Time'];
                        }

                    }else{ // 返回早盘全部数据
                        $aData2[$k]['MID']=$v['MID'];
                        $aData2[$k]['M_League']=$v['M_League'];
                        $aData2[$k]['M_League_Initials']=_getFirstCharter($v['M_League']);
                        $aData2[$k]['M_Time']=$v['M_Time'];
                    }
                    $aData2[$k]['region']= isset($aLeagueRegion[$v['M_League']]) ? $aLeagueRegion[$v['M_League']] : '早盘足球';
                }

                break;
        }
        break;

    case 'BK':

        if(strpos($_SESSION['gameSwitch'],'|')>0){
            $gameArr=explode('|',$_SESSION['gameSwitch']);
        }else{
            if(strlen($_SESSION['gameSwitch'])>0){
                $gameArr[]=$_SESSION['gameSwitch'];
            }else{
                $gameArr=array();
            }
        }
        if(!in_array('BK',$gameArr)) {

            switch ($showtype) {
                case 'RB':// 篮球滚球

                    $returnData = $redisObj->getSimpleOne("BK_M_ROU_EO");
                    $matches = json_decode($returnData, true);
                    if (is_array($matches)) {
                        $cou = sizeof($matches);
                    } else {
                        $cou = 0;
                    }
                    if ($cou > 0) {
                        $k = 0;
                        $aData2 = [];
                        for ($i = 0; $i < $cou; $i++) {
//                            $messages = $matches[$i];
//                            $messages = str_replace(");", ")", $messages);
//                            $messages = str_replace("cha(9)", "", $messages);
//                            $datainfo = eval("return $messages;");
                            $datainfo = $matches[$i];
                            $datainfo[0]=$datainfo['gid'];
                            $datainfo[2]=$datainfo['league'];
                            $datainfo[47]=$datainfo['DATETIME'];

                            $pos = strpos($datainfo[2],'NBA2K');
                            if ($pos === false){}
                            else{
                                if ($mem_djbk_off == 'off'){
                                    continue;
                                }
                            }

                            $aData2[$k]['MID'] = $datainfo[0];
                            $aData2[$k]['M_League'] = $datainfo[2];
                            $aData2[$k]['M_League_Initials'] = _getFirstCharter($datainfo[2]);
                            $aData2[$k]['M_Time'] = substr($datainfo[47], 9, 5);
                            $aData2[$k]['region']="滚球蓝球";
                            $k = $k + 1;
                        }
                    }

                    break;
                case 'FT':// 篮球今日赛事

                    $returnData = $redisObj->getSimpleOne("TODAY_BK_M_ROU_EO");
                    $aData = json_decode($returnData, true);

                    // 从联赛信息中获取地区/国家
                    $leagueRegion = $redisObj->getSimpleOne("TODAY_BK_LEAGUE_REGION");
                    $aLeagueRegion = json_decode($leagueRegion,true);

                    $aData2 = [];
                    foreach ($aData as $k => $v) {
                        $aData2[$k]['MID'] = $v['MID'];
                        $aData2[$k]['M_League'] = $v['M_League'];
                        $aData2[$k]['M_League_Initials'] = _getFirstCharter($v['M_League']);
                        $aData2[$k]['M_Time'] = $v['M_Time'];
                        $aData2[$k]['region']= isset($aLeagueRegion[$v['M_League']]) ? $aLeagueRegion[$v['M_League']] : '今日蓝球';
                    }
                    break;
                case 'FU':// 篮球早盘

                    $returnData = $redisObj->getSimpleOne("FUTURE_BK_ALL");
                    $aData = json_decode($returnData, true);

                    // 从联赛信息中获取地区/国家
                    $leagueRegion = $redisObj->getSimpleOne("FUTURE_BK_LEAGUE_REGION");
                    $aLeagueRegion = json_decode($leagueRegion,true);

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

//                    echo json_encode($aData, JSON_UNESCAPED_UNICODE);die;

                    $aData2 = [];
                    foreach ($aData as $k => $v) {

                        // 传入日期，只处理并返回对应日期的数据
                        if ($m_date) { // 返回对应日期的数据

                            if ($v['M_Date'] == $m_date) {
                                $aData2[$k]['MID'] = $v['MID'];
                                $aData2[$k]['M_League'] = $v['M_League'];
                                $aData2[$k]['M_League_Initials'] = _getFirstCharter($v['M_League']);
                                $aData2[$k]['M_Time'] = $v['M_Time'];
                            }

                        } else { // 返回早盘全部数据
                            $aData2[$k]['MID'] = $v['MID'];
                            $aData2[$k]['M_League'] = $v['M_League'];
                            $aData2[$k]['M_League_Initials'] = _getFirstCharter($v['M_League']);
                            $aData2[$k]['M_Time'] = $v['M_Time'];
                        }
                        $aData2[$k]['region']= isset($aLeagueRegion[$v['M_League']]) ? $aLeagueRegion[$v['M_League']] : '早盘蓝球';
                    }
                    break;
            }

        }
        break;
    default: break;
}


// 按照联盟排序
if ($sorttype == 'league'){
    $aData2 = array_sort($aData2,'M_League_Initials',$type='asc');
}
// 联盟相同的归成一类
$aData3 = group_same_key($aData2,'M_League');

$i = 0;
foreach ($aData3 as $k => $v){
    foreach ($v as $k2 => $v2){
        $aData4[$i]['gid'] .= $v2['MID'].',';
        $aData4[$i]['region'] = isset($aLeagueRegion[$k]) ? $aLeagueRegion[$k] : $v2['region'];
    }
    $aData4[$i]['gid']=rtrim($aData4[$i]['gid'] , ',');
    $aData4[$i]['M_League'] = $k;
    //$aData4[$i]['region'] = isset($aLeagueRegion[$k]) ? $aLeagueRegion[$k] : $region;
    if ($k) {
        $aData4[$i]['num'] = count($v);
        $i = $i + 1;
    }
}

$status = '200';
$describe = 'success';

original_phone_request_response($status, $describe, $aData4);

