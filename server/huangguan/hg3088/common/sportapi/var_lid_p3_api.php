<?php
/**
 * /var_lid_p3_api.php  体育联赛数据接口_综合过关
 * @param  gtype   FT 足球，BK 篮球
 * @param  sorttype   league 联盟排序  time 时间排序
 * @param  mdate  日期 2018-09-15
 * @param  showtype
 * @param  M_League  欧洲冠军杯（显示此联赛全部冠军盘口，以及赔率）
 * @param  gid
 */
//error_reporting(E_ALL);
//ini_set('display_errors','On');
//include_once('include/config.inc.php');
//require ("include/curl_http.php");
//require ("include/define_function_list.inc.php");


//$langx=$_SESSION['Language']?$_SESSION['Language']:'zh-cn';
//require ("include/traditional.$langx.inc.php");

$uid=$_SESSION['Oid'];
$gtype = $_REQUEST['gtype'];
$m_date = $_REQUEST['mdate']; // 只有早盘数据需要传入日期，今日赛事不需要
$sorttype = $_REQUEST['sorttype'];
$M_League=$_REQUEST['M_League'];
$showtype=$_REQUEST['showtype'];
$gid = $_REQUEST['gid'];
$aData4 = [];
switch ($gtype){
    case 'FT':
        if($showtype=='future' || $showtype=='FU'){
            $key='FUTURE_FT_P3';
        }else{
            $key='TODAY_FT_P3';
        }
        break;
    case 'BK':
        if($showtype=='future' || $showtype=='FU'){
            $key='FUTURE_BK_P3';
        }else{
            $key='TODAY_BK_P3';
        }
        break;
}
$matchesJson = $redisObj->getSimpleOne($key);
$matches = json_decode($matchesJson,true);

if ($_REQUEST['M_League']=='' and $_REQUEST['appRefer']==13){
    unset($_REQUEST['M_League']);
}

if (empty($M_League) and count($_REQUEST['M_League'])==0){

    if(isset($matches)&&is_array($matches)&&count($matches)>0) {

        foreach($matches as $k=>$v){

            // 传入日期，只处理并返回对应日期的数据
            if (isset($m_date) && checkDateFormat($m_date)){ // 返回对应日期的数据

                if ($v['M_Date'] == $m_date){
                    $aData2[$k]['MID']=$v['MID'];
                    $aData2[$k]['M_League']=$v['M_League'];
                    $aData2[$k]['M_League_Initials']=_getFirstCharter($v['M_League']);
                    $aData2[$k]['M_Time']=$v['M_Time'];
                }

            }else{
                $aData2[$k]['MID']=$v['MID'];
                $aData2[$k]['M_League']=$v['M_League'];
                $aData2[$k]['M_League_Initials']=_getFirstCharter($v['M_League']);
                $aData2[$k]['M_Time']=$v['M_Time'];
            }
        }

        // 按照联盟排序
        if ($sorttype == 'league'){
            $aData2 = array_sort($aData2,'M_League_Initials',$type='asc');
        }
        // 联盟相同的归成一类
        $aData3 = group_same_key($aData2,'M_League');

        // 从联赛信息中获取地区/国家 FT_P3_LEAGUE_REGION、BK_P3_LEAGUE_REGION
        $leagueRegion = $redisObj->getSimpleOne(strval($gtype) . "_P3_LEAGUE_REGION");
        $aLeagueRegion = json_decode($leagueRegion,true);
//        print_r($aLeagueRegion);
        $i = 0;
        foreach ($aData3 as $k => $v){
            foreach ($v as $k2 => $v2){
                $aData4[$i]['gid'] .= $v2['MID'].',';
                $aData4[$i]['region']= isset($aLeagueRegion[$v2['M_League']]) ? $aLeagueRegion[$v2['M_League']] : '综合过关';
            }
            $aData4[$i]['gid']=rtrim($aData4[$i]['gid'] , ',');
            $aData4[$i]['M_League'] = $k;
            $aData4[$i]['num'] = count($v);
            $i=$i+1;
        }

    }

}
else{

    if(isset($matches)&&is_array($matches)&&count($matches)>0) {

        // 传入gid，只处理并返回对应盘口的数据，并返回
        if (isset($gid) and $gid!=''){

            foreach ($matches as $k => $v) {
                if($v["MID"]==$gid) {
                    switch ($gtype) {
                        case 'FT':
                            $aData2[$k] = dataOutFT($v);
                            break;
                        case 'BK':
                            $aData2[$k] = dataOutBK($v);
                            break;
                    }
                }

            }

        }
        else{

            $aLeague=explode(',',$M_League);
            // 传入日期，只处理并返回对应日期的数据，返回对应日期的数据
            if(isset($m_date) && checkDateFormat($m_date)) {

                foreach ($matches as $k => $v) {
                    if($v["M_Date"]==$m_date) {

//                        if ($v['M_League'] == $M_League) {
                        if (in_array($v['M_League'],$aLeague)) {

                            switch ($gtype) {
                                case 'FT':
                                    $aData2[$k] = dataOutFT($v);
                                    break;
                                case 'BK':
                                    $aData2[$k] = dataOutBK($v);
                                    break;
                            }

                        }
                    }
                }

            }else{

                $obtSelectionsToday = $redisObj->getSimpleOne("TODAY_FT_OBTSELECTIONS");
                $aObtSelectionsToday = json_decode($obtSelectionsToday,true) ;
                $obtSelectionsFuture = $redisObj->getSimpleOne("FUTURE_FT_OBTSELECTIONS");
                $aObtSelections = json_decode($obtSelectionsFuture,true) ;
                foreach ($aObtSelectionsToday as $k=> $v){
                    $aObtSelections[$k]=$v;
                }

                foreach ($matches as $k => $v) {

//                        if ($v['M_League'] == $M_League) {
                    if (in_array($v['M_League'],$aLeague)) {

                        switch ($gtype) {
                            case 'FT':
                                $aData2[$k] = dataOutFT($v);
                                break;
                            case 'BK':
                                $aData2[$k] = dataOutBK($v);
                                break;
                        }

                    }
                    elseif($_REQUEST['M_League']=='' and count($_REQUEST['M_League'])>0 and $_REQUEST['mdate']=='' and count($_REQUEST['mdate'])>0){ // M_League 为空串，mdate为空串时， 返回当日的综合过关的所有盘口

                        $m_date = date('Y-m-d');
                        if($v["M_Date"]==$m_date) {

                            switch ($gtype) {
                                case 'FT':
                                    $aData2[$k] = dataOutFT($v);
                                    break;
                                case 'BK':
                                    $aData2[$k] = dataOutBK($v);
                                    break;
                            }

                        }
                    }
                }
            }
        }
    }
    $aData4 = array_values($aData2);
}


$status = '200';
$describe = 'success';
original_phone_request_response($status, $describe, $aData4);

// 篮球综合过关玩法数据
function dataOutBK($v){
    global $open,$o,$e;
    $S_Single_Rate=change_rate($open,$v['S_Single_Rate']); // 主队单双
    $S_Double_Rate=change_rate($open,$v['S_Double_Rate']); // 客队单双
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
    $m_date=strtotime($v['M_Date']);
    $dates=date("m-d",$m_date);
    if (strlen($v['M_Time'])==5){
        $pdate=$dates.' 0'.$v['M_Time'];    //<br>
    }else{
        $pdate=$dates.' '.$v['M_Time'];     //<br>
    }

    if($v['ShowTypeP']=="H"){
        $ratio_mb_str=$v['M_P_LetB'];
        $ratio_tg_str='';
    }elseif($v['ShowTypeP']=="C"){
        $ratio_mb_str='';
        $ratio_tg_str=$v['M_P_LetB'];
    }

    $aData2['gid']=$v['MID'];
    $aData2['datetime']=$pdate;
//    $aData2['dategh']=date('m-d').$v['MB_MID'];
    $aData2['league']=$v['M_League'];
    $aData2['gnum_h']=$v['MB_MID'];
    $aData2['gnum_c']=$v['TG_MID'];
    $aData2['team_h']=$v['MB_Team'];
    $aData2['team_c']=$v['TG_Team'];
    $aData2['strong']=$v['ShowTypeP'];
    $aData2['ratio']=$v['M_P_LetB'];
    $aData2['ratio_mb_str']=$ratio_mb_str;
    $aData2['ratio_tg_str']=$ratio_tg_str;
    $aData2['ior_PRH']=change_rate($open,$v['MB_P_LetB_Rate']);
    $aData2['ior_PRC']=change_rate($open,$v['TG_P_LetB_Rate']);
    $aData2['ratio_o']=$v['MB_P_Dime'];
    $aData2['ratio_u']=$v['TG_P_Dime'];
    $aData2['ratio_o_str']="大".str_replace('O','',$v['MB_P_Dime']);
    $aData2['ratio_u_str']="小".str_replace('U','',$v['TG_P_Dime']);
    $aData2['ior_POUC']=change_rate($open,$v['MB_P_Dime_Rate']);
    $aData2['ior_POUH']=change_rate($open,$v['TG_P_Dime_Rate']);
    $aData2['str_odd'] =$Single;
    $aData2['str_even']=$Double;
    $aData2['ior_PO']=change_rate($open,$v['S_P_Single_Rate']);
    $aData2['ior_PE']=change_rate($open,$v['S_P_Double_Rate']);
    $aData2['ior_PMH']=change_rate($open,$v["MB_P_Win_Rate"]);
    $aData2['ior_PMC']=change_rate($open,$v["TG_P_Win_Rate"]);
    $aData2['hratio']=$v['M_LetB_H'];
    $aData2['gidm']=$v['MID'];
    $aData2['par_minlimit']=3;
    $aData2['par_maxlimit']=10;
    $aData2['ratio_pouho']=$v['MB_Dime_H'];
    $aData2['ratio_pouhu']=$v['MB_Dime_S_H'];
    $aData2['ratio_ouho_str']= !empty($v['MB_Dime_H'])? "大".str_replace('O','',$v['MB_Dime_H']):'';
    $aData2['ratio_ouhu_str']= !empty($v['MB_Dime_S_H'])? "小".str_replace('U','',$v['MB_Dime_S_H']):'';
    $aData2['ior_POUHO']=change_rate($open,$v["MB_P_Dime_Rate_H"]);
    $aData2['ior_POUHU']=change_rate($open,$v["MB_P_Dime_Rate_S_H"]);
    $aData2['ratio_pouco']=$v['TG_Dime_H'];
    $aData2['ratio_poucu']=$v['TG_Dime_S_H'];
    $aData2['ratio_ouco_str']= !empty($v['TG_Dime_H'])? "大".str_replace('O','',$v['TG_Dime_H']):'';
    $aData2['ratio_oucu_str']= !empty($v['TG_Dime_S_H'])? "小".str_replace('U','',$v['TG_Dime_S_H']):'';
    $aData2['ior_POUCO']=change_rate($open,$v["TG_P_Dime_Rate_H"]);
    $aData2['ior_POUCU']=change_rate($open,$v["TG_P_Dime_Rate_S_H"]);
    return $aData2;

}

// 足球综合过关玩法数据
function dataOutFT($v){
    global $open,$aObtSelections;
    $mb_team = trim($v['MB_Team']);
    $m_date = strtotime($v['M_Date']);
    $dates = date("m-d", $m_date);
    if (strlen($v['M_Time']) == 5) {
        $pdate = $dates . ' 0' . $v['M_Time'];
    } else {
        $pdate = $dates . ' ' . $v['M_Time'];
    }
    if ($v['F_PD_Show'] == 1 and $v['F_T_Show'] == 1 and $v['F_F_Show'] == 1) {
        $show = 3;
    } else if ($v['F_HPD_Show'] == 1 and $v['F_PD_Show'] == 1 and $v['F_T_Show'] == 1 and $v['F_F_Show'] == 1) {
        $show = 4;
    } else {
        $show = 0;
    }
    if ($v['ShowTypeP'] == "H") {
        $ratio_mb_str = $v['M_P_LetB'];
        $ratio_tg_str = '';
        $hratio_mb_str = $v['M_LetB_H'];
        $hratio_tg_str = '';
    } elseif ($v['ShowTypeP'] == "C") {
        $ratio_mb_str = '';
        $ratio_tg_str = $v['M_P_LetB'];
        $hratio_mb_str = '';
        $hratio_tg_str = $v['M_LetB_H'];
    }
//                $v['MB_Team']=str_replace("[Mid]","<font color=\'#005aff\'>[N]</font>",$v['MB_Team']);
//                $v['MB_Team']=str_replace("[中]","<font color=\'#005aff\'>[中]</font>",$v['MB_Team']);
    $aData2['gid'] = $v['MID'];
//    $aData2['dategh'] = date('m-d') . $v['MB_MID'];
    $aData2['datetime'] = $pdate;
    $aData2['league'] = $v['M_League'];
    $aData2['gnum_h'] = $v['MB_MID'];
    $aData2['gnum_c'] = $v['TG_MID'];
    $aData2['team_h'] = $v['MB_Team'];
    $aData2['team_c'] = $v['TG_Team'];
    $aData2['strong'] = $v['ShowTypeP'];
    $aData2['ratio'] = $v['M_P_LetB'];
    $aData2['ratio_mb_str'] = $ratio_mb_str;
    $aData2['ratio_tg_str'] = $ratio_tg_str;
    $aData2['ior_PRH'] = change_rate($open, $v['MB_P_LetB_Rate']);
    $aData2['ior_PRC'] = change_rate($open, $v['TG_P_LetB_Rate']);
    $aData2['ratio_o'] = $v['MB_P_Dime'];
    $aData2['ratio_u'] = $v['TG_P_Dime'];
    $aData2['ratio_o_str'] = "大" . str_replace('O', '', $v['MB_P_Dime']);
    $aData2['ratio_u_str'] = "小" . str_replace('U', '', $v['TG_P_Dime']);
    $aData2['ior_POUC'] = change_rate($open, $v['MB_P_Dime_Rate']);
    $aData2['ior_POUH'] = change_rate($open, $v['TG_P_Dime_Rate']);
    $aData2['ior_PO'] = change_rate($open, $v['S_P_Single_Rate']);
    $aData2['ior_PE'] = change_rate($open, $v['S_P_Double_Rate']);
    $aData2['ior_MH'] = change_rate($open, $v["MB_P_Win_Rate"]);
    $aData2['ior_MC'] = change_rate($open, $v["TG_P_Win_Rate"]);
    $aData2['ior_MN'] = change_rate($open, $v["M_P_Flat_Rate"]);
    $aData2['hstrong'] = $v['ShowTypeP'];
    $aData2['hratio'] = $v['M_LetB_H'];
    $aData2['hratio_mb_str'] = $hratio_mb_str;
    $aData2['hratio_tg_str'] = $hratio_tg_str;
    $aData2['ior_HPRH'] = change_rate($open, $v["MB_P_LetB_Rate_H"]);  // 半场让球主队;
    $aData2['ior_HPRC'] = change_rate($open, $v["TG_P_LetB_Rate_H"]); // 半场让球客队;
    $aData2['hratio_o'] = $v['MB_Dime_H'];
    $aData2['hratio_u'] = $v['TG_Dime_H'];
    $aData2['hratio_o_str'] = "大" . str_replace('O', '', $v['MB_Dime_H']);
    $aData2['hratio_u_str'] = "小" . str_replace('U', '', $v['TG_Dime_H']);
    $aData2['ior_HPOUH'] = change_rate($open, $v['TG_P_Dime_Rate_H']); // 半场客队小;
    $aData2['ior_HPOUC'] = change_rate($open, $v['MB_P_Dime_Rate_H']); // 半场主队大
    $aData2['ior_HPMH'] = change_rate($open, $v["MB_Win_Rate_H"]); // RATIO_HMH ior_HPMH 独赢主队
    $aData2['ior_HPMC'] = change_rate($open, $v["TG_Win_Rate_H"]); // RATIO_HMC ior_HPMC 独赢客队
    $aData2['ior_HPMN'] = change_rate($open, $v["M_Flat_Rate_H"]);  // RATIO_HMN ior_HPMN 独赢和局
    $aData2['all'] = $v["more"];
    $aData2['gidm'] = $v['MID'];
    $aData2['par_minlimit'] = 3;
    $aData2['par_maxlimit'] = 10;
    // 标签开关 特优赔率、让球、进球大小、角球、罚球、会晋级
    $aData2['eps']=$aData2['handicaps']=$aData2['goalsou']=$aData2['corners']=$aData2['bookings']=$aData2['toqualify']='N';
    if (in_array('eps',$aObtSelections[$v['MID']])){
        $aData2['eps']='Y';
    }
    if (in_array('handicaps',$aObtSelections[$v['MID']])){
        $aData2['handicaps']='Y';
    }
    if (in_array('goalsou',$aObtSelections[$v['MID']])){
        $aData2['goalsou']='Y';
    }
    if (in_array('corners',$aObtSelections[$v['MID']])){
        $aData2['corners']='Y';
    }
    if (in_array('bookings',$aObtSelections[$v['MID']])){
        $aData2['bookings']='N';// 6686缺少主要的玩法，罚牌强制关闭
    }
    if (in_array('toqualify',$aObtSelections[$v['MID']])){
        $aData2['toqualify']='Y';
    }

    return $aData2;
}
