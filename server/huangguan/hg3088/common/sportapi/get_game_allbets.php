<?php
/*
 * /get_game_allbets.php  更多玩法接口
 *
 * @param gid
 * @param gtype FT 足球 BK 篮球
 * @param showtype FU 早盘 FT 今日赛事 RB 滚球
 * */

//error_reporting(E_ALL);
//ini_set('display_errors','On');

//include "include/address.mem.php";
//include_once('include/config.inc.php');
//require_once("../../common/sportCenterData.php");
//require ("include/curl_http.php");
//require ("include/define_function_list.inc.php");

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    $status='401.1';
    $describe = "请重新登录！";
    original_phone_request_response($status,$describe);
}
$gid   = $_REQUEST['gid'];
$langx= $_SESSION['Language'];
$gtype = $_REQUEST['gtype'];
$showtype = $_REQUEST['showtype'];

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
if(in_array('BKQ3',$gameArr)){ // 是否停用篮球滚球第3节
    $mem_bkq3_off = 'off';
}
$isClosedH1 = in_array('BKH1', $gameArr); // 是否关闭篮球滚球上半场20200111
$se_now='';
$showtypeArr = array('FTRB','FTFT','FTFU','BKRB','BKFT','BKFU');
if(!in_array($gtype.$showtype,$showtypeArr)){

    $status='401.1';
    $describe = "参数不合法！";
    original_phone_request_response($status,$describe);
}

//判断赛事是否存在
if(strlen($gid)>6 && intval($gid)>100000){
    $result = mysqli_query($dbLink,"SELECT MID,ECID,LID,ISRB FROM `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` WHERE MID=".$gid);
    $row = mysqli_fetch_assoc($result);
    $cou=mysqli_num_rows($result);
    if($cou!=1){
        $status='401.2';
        $describe = "赛事不存在！";
        original_phone_request_response($status,$describe);
    }
}else{
    $status='401.3';
    $describe = "参数不合法！";
    original_phone_request_response($status,$describe);
}

$ecid=$row['ECID'];
$lid=$row['LID'];
$isrb=$row['ISRB']=='Y'?$row['ISRB']:'N';
// 足球滚球的附属盘口，需要从数据捞出数据返回
if ($_REQUEST['gtype']=='FT' and $_REQUEST['showtype']=='RB' and $_REQUEST['isMaster']=='N'){

    $sql = "select MID as gid, M_League as league, MB_Team as team_h, TG_Team as team_c, M_Duration as re_time,
ShowTypeRB as strong, ShowTypeHRB as hstrong, 
MB_Win_Rate_RB as ior_RMH, TG_Win_Rate_RB as ior_RMC, M_Flat_Rate_RB as ior_RMN,
M_LetB_RB as ratio_re, MB_LetB_Rate_RB as ior_REH, TG_LetB_Rate_RB as ior_REC,
MB_Dime_RB as ratio_rouo, TG_Dime_RB as ratio_rouu, MB_Dime_Rate_RB as ior_ROUC, TG_Dime_Rate_RB as ior_ROUH,
S_Single_Rate_RB as ior_REOO, S_Double_Rate_RB as ior_REOE,
MB_Win_Rate_RB_H as ior_HRMH, TG_Win_Rate_RB_H as ior_HRMC, M_Flat_Rate_RB_H as ior_HRMN, 
M_LetB_RB_H as ratio_hre, MB_LetB_Rate_RB_H as ior_HREH, TG_LetB_Rate_RB_H as ior_HREC,
MB_Dime_RB_H as ratio_hrouo, TG_Dime_RB_H as ratio_hrouu, MB_Dime_Rate_RB_H as ior_HROUC, TG_Dime_Rate_RB_H as ior_HROUH,
MB_Ball as score_h, TG_Ball as score_c
 from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where mid =$gid";
    $result = mysqli_query($dbLink,$sql);
    $row = mysqli_fetch_assoc($result);
    $cou = mysqli_num_rows($result);
    if ($cou>0){
        $aData[0]=$row;
        $odd_f_type='H'; // 香港盘
        // 让球赔率转换
        $rate = get_other_ioratio($odd_f_type, $row["ior_REH"], $row["ior_REC"], 100);
        $aData[0]['ior_REH']=$rate[0];
        $aData[0]['ior_REC']=$rate[1];
        // 全场大小赔率转换
        $rate = get_other_ioratio($odd_f_type, $row["ior_ROUC"], $row["ior_ROUH"], 100);
        $aData[0]['ior_ROUC']=$rate[0];
        $aData[0]['ior_ROUH']=$rate[1];
        // 让球半场赔率转换
        $rate = get_other_ioratio($odd_f_type, $row["ior_HREH"], $row["ior_HREC"], 100);
        $aData[0]['ior_HREH']=$rate[0];
        $aData[0]['ior_HREC']=$rate[1];
        // 全场大小赔率转换
        $rate = get_other_ioratio($odd_f_type, $row["ior_HROUC"], $row["ior_HROUH"], 100);
        $aData[0]['ior_HROUC']=$rate[0];
        $aData[0]['ior_HROUH']=$rate[1];
        // 单双赔率转换
        $aData[0]['ior_REOO'] = change_rate($open, $row['ior_REOO']);
        $aData[0]['ior_REOE'] = change_rate($open, $row['ior_REOE']);

        $aData[0]['sw_RE']='N'; //让球-全场的数据展示   滚球
        if ($row['ior_REH']>0) $aData[0]['sw_RE']='Y';
        $aData[0]['sw_HRE']='N'; //让球-上半场的数据展示   滚球
        if ($row['ior_HREH']>0) $aData[0]['sw_HRE']='Y';
        $aData[0]['sw_ROU']='N'; //大/小的数据展示
        if ($row['ior_ROUC']>0) $aData[0]['sw_ROU']='Y';
        $aData[0]['sw_HOU']='N'; //大/小-上半场的数据展示 安卓提供的参数
        $aData[0]['sw_HROU']='N'; //大/小-上半场的数据展示 ios提供的参数
        if ($row['ior_HROUC']>0) {$aData[0]['sw_HOU']='Y'; $aData[0]['sw_HROU']='Y';}
        $aData[0]['sw_ROUH']='N'; //球队进球数 主队 大/小的数据展示
        $aData[0]['sw_ROUC']='N'; //球队进球数 客队大/小的数据展示
        $aData[0]['sw_HRUH']='N'; //球队进球数 主队 大/小 上半场的数据展示
        $aData[0]['sw_HRUC']='N'; //球队进球数 客队 大/小 上半场的数据展示
        $aData[0]['sw_RM']='N'; //独赢的数据展示
        if ($row['ior_RMH']>0) $aData[0]['sw_RM']='Y';
        $aData[0]['sw_HRM']='N'; //独赢-上半场的数据展示
        if ($row['ior_HRMH']>0) $aData[0]['sw_HRM']='Y';
        $aData[0]['sw_RWE']='N'; //赢得任一半场
        $aData[0]['sw_RWB']='N'; //赢得所有半场
        $aData[0]['sw_RT']='N'; //总进球数
        $aData[0]['sw_HRT']='N'; //总进球数  上半场
        $aData[0]['sw_RTS']='N'; //双方球队进球
        $aData[0]['sw_RCS']='N'; //零失球
        $aData[0]['sw_RWN']='N'; //零失球获胜
        $aData[0]['sw_RHG']='N'; //最多进球的半场
        $aData[0]['sw_RMG']='N'; //最多进球的半场-独赢
        $aData[0]['sw_RSB']='N'; //双半场进球
        $aData[0]['sw_RF']='N'; //半场/全场  开关
        $aData[0]['sw_RWM']='N'; //净胜球数
        $aData[0]['sw_RDC']='N'; //双重机会
        $aData[0]['sw_RMTS']='N'; //独赢&双方球队进球
        $aData[0]['sw_RDS']='N'; //双重机会&双方球队进球
        $aData[0]['sw_RPD']='N'; //波胆
        $aData[0]['sw_HRPD']='N'; //波胆上半场
        $aData[0]['sw_RMU']='N'; //独赢 & 进球 大/小
        $aData[0]['sw_RUT']='N'; //进球 大/小 & 双方球队进球
        $aData[0]['sw_RUE']='N'; //进球 大/小 & 进球 单/双
        $aData[0]['sw_RDUA']='N'; //双重机会&进球 大/小
        $aData[0]['sw_RDUB']='N';
        $aData[0]['sw_RDUC']='N';
        $aData[0]['sw_RDUD']='N';
        // 单双有值就返回Y  没有N    半场 单双都返回 N
        $aData[0]['sw_REO']='N';
        $aData[0]['sw_HREO']='N';
        if ($row['ior_REOO']>0) $aData[0]['sw_REO']='Y';
        $aData[0]['ratio_rouo']=str_replace('O','',$row['ratio_rouo']);
        $aData[0]['ratio_rouu']=str_replace('U','',$row['ratio_rouu']);
        $aData[0]['ratio_hrouo']=str_replace('O','',$row['ratio_hrouo']);
        $aData[0]['ratio_hrouu']=str_replace('U','',$row['ratio_hrouu']);
    }


    $status='200';
    $describe = "success5";
    original_phone_request_response($status,$describe,$aData);
}

$midLockSet='';
$midLockCheck = mysqli_query($dbLink,"select MID from ".DBPREFIX."match_sports_more_midlock where `MID` = $gid");
$cou=mysqli_num_rows($midLockCheck);
if($cou==0)	$midLockSet = mysqli_query($dbMasterLink,"INSERT INTO ".DBPREFIX."match_sports_more_midlock(`MID`)VALUES({$gid})");

if($midLockSet || $cou==1){
    $redisObj = new Ciredis();
    $valReflushTime = $redisObj->getSimpleOne($gid."_reflush_time");
    if($valReflushTime){//存在赛事,更新数据库，redis
        //echo 'exit data read<br/>';
        if($showtype=="RB"){ $reflushTime=5;}elseif($showtype=="FU"){$reflushTime=10;}else{$reflushTime=20;}
        if( time()-$valReflushTime > $reflushTime ){ //数据过期,重新抓取更新数据库,redis
            //echo "out date re get<br/>";
            $begin = mysqli_query($dbMasterLink,"start transaction");//开启事务$from
            $lockMid = mysqli_query($dbMasterLink,"select MID from ".DBPREFIX."match_sports_more_midlock where `MID` = $gid for update");
            $valReflushTime1 = $redisObj->getSimpleOne($gid."_reflush_time");
            if(time()-$valReflushTime1 > $reflushTime){
                if($begin&&$lockMid->num_rows==1){
                    $dataNew= getDataFromInterface($langx,$gtype,$showtype,$gid,$ecid,$lid,$isrb);
                    if($dataNew['tmp_Obj']&&count($dataNew['tmp_Obj'])>0 && $dataNew['gid_ary']&&count($dataNew['gid_ary'])>0 ){
                        $tmp_Obj=$dataNew['tmp_Obj'];
                        $gid_ary=$dataNew['gid_ary'];
                        $updateSt = $redisObj->getSET($gid."_reflush_time",time());
                        if($updateSt){
                            $details = json_encode($tmp_Obj,JSON_UNESCAPED_UNICODE);
                            $details=str_replace('\'','',$details);
                            $setGames = mysqli_query($dbMasterLink,"replace into ".DBPREFIX."match_sports_more(`MID`,`details`)VALUES($gid,'$details')");
                            if($setGames){
                                $comStatus = mysqli_query($dbMasterLink,"COMMIT");
                                if($comStatus){
                                    $redisObj->getSET("gameMore_".$gid,json_encode(array('status'=>1,'tmp_Obj'=>$tmp_Obj,'gid_ary'=>$gid_ary)));//写入redis

                                    if ($gid){
                                        foreach ($tmp_Obj as $k => $v){
                                            $tmp_Obj[$k]['@attributes'] = '';
                                            foreach ($v as $k2 => $v2){
                                                if (is_array($v2)){
                                                    if (count($v2)==0){
                                                        $tmp_Obj[$k][$k2]='';
                                                    }
                                                }
                                                elseif ($_REQUEST['isP3']=='Y' and strpos($k2,'ior')!==false){ // 综合过关的赔率+1
                                                    if(in_array($k2 , ['ior_EOO','ior_EOE','ior_HEOO','ior_HEOE'])) { continue;}
                                                    // 综合过关-角球独赢不需要加1
                                                    if($tmp_Obj[$k]['description']=='角球' and ($k2=='ior_MH' or $k2=='ior_MC' or $k2=='ior_MN' or $k2=='ior_HMH' or $k2=='ior_HMC' or $k2=='ior_HMN')){ $tmp_Obj[$k][$k2]=$v2; }
                                                    else{ $tmp_Obj[$k][$k2]=$v2+1; }
                                                }
                                            }
//                                                $aData2[$k] = emptyArrToString($tmp_Obj[$k]);
                                            $aData2[$k] = $tmp_Obj[$k];
                                            // 正网刷水时，足球的附属盘口，从这里插入到数据库，方便结算。准备入库需要的附属盘口的数据
                                            $dataFs=[];
                                            if (trim($flushWay)=='ra' and $gtype=='FT'){
                                                if ($v['gid']>10000 and $v['gid']!=$v['gid_fs']){ // 主盘口跳过，只处理附属盘口

                                                    $dataFs['gid']=$v['gid_fs'];
                                                    $dataFs['league']=$v['league'];
                                                    $dataFs['team_h']=$v['team_h'];
                                                    $dataFs['team_c']=$v['team_c'];
                                                    $dataFs['M_Date']=explode(' ',$v['datetime'])[0];
                                                    $dataFs['M_Time']=getMtime($v['datetime']);
                                                    $dataFs['M_Start']=$v['datetime'];
                                                    $dataFs['gnum_h']=$v['gnum_h'];
                                                    $dataFs['gnum_c']=$v['gnum_c'];

                                                    $dataFsArr[] = $dataFs; // 附属盘数据
                                                }
                                            }
                                        }

                                        // 正网刷水时，足球的附属盘口，从这里插入到数据库，方便结算。准备入库需要的附属盘口的数据
                                        if (trim($flushWay)=='ra' and $gtype=='FT' and count($dataFsArr)>0) {
                                            // 判断是否已经插入
                                            $gidFs = implode(',', array_column($dataFsArr, 'gid'));
                                            $gidFsResult = mysqli_query($dbLink, "select MID from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where MID in ($gidFs)");
                                            while ($gidFsRow = mysqli_fetch_assoc($gidFsResult)){
                                                //unset($dataFs[$gidFsRow['MID']]); // 检查附属盘口是否存在，存在则不插入，将此盘口从准备的数据集合中移除
                                                $isExitGid[] = $gidFsRow['MID'];
                                            }

                                            if (count($dataFsArr)>0){
                                                $start=0;
                                                $insert_sql = "INSERT INTO `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` (MID,Type,M_Start,M_Date,M_Time,MB_Team_tw,MB_Team,TG_Team_tw,TG_Team,M_League_tw,M_League,MB_MID,TG_MID,RB_Show)VALUES";
                                                foreach ($dataFsArr as $k => $v){
                                                    if(in_array($v['gid'] , $isExitGid)) { continue; } // 已存在
                                                    $GID=$v['gid'];
                                                    $timestamp=$v['M_Start'];
                                                    $m_date=$v['M_Date'];
                                                    $m_time=$v['M_Time'];
                                                    $TEAM_H=$v['team_h'];
                                                    $TEAM_C=$v['team_c'];
                                                    $LEAGUE=$v['league'];
                                                    $GNUM_H=$v['gnum_h'];
                                                    $GNUM_C=$v['gnum_c'];
                                                    if($start == 0) {
                                                        $insert_sql .= "('$GID','$gtype','$timestamp','$m_date','$m_time','$TEAM_H','$TEAM_H','$TEAM_C','$TEAM_C','$LEAGUE','$LEAGUE','$GNUM_H','$GNUM_C','1')" ;

                                                    }else{
                                                        $insert_sql .= ",('$GID','$gtype','$timestamp','$m_date','$m_time','$TEAM_H','$TEAM_H','$TEAM_C','$TEAM_C','$LEAGUE','$LEAGUE','$GNUM_H','$GNUM_C','1')" ;
                                                    }
                                                    $start++;
                                                }
                                                if($start>0){ // 有新增数据
                                                    mysqli_query($dbMasterLink,$insert_sql) or die ("操作失敗!");
                                                }
                                            }
                                        }

                                        if ($gtype == 'BK'){
                                            $se_now=array_shift($tmp_Obj)['se_now'];
                                            if (($se_now=='Q3' || $se_now=='Q4' || $se_now=='H2' || $se_now=='OT' || $se_now=='HT') and $mem_bkq3_off=='off'){
                                                $status='401.20';
                                                $describe = "数据为空！1 $se_now $mem_bkq3_off";
                                                original_phone_request_response($status,$describe);
                                            }
                                        }
                                        $aData2=array_values($aData2);

                                        if (count($aData2)==0){
                                            $status='401.10';
                                            $describe = "数据为空！";
                                            original_phone_request_response($status,$describe);
                                        }else{

                                            $status='200';
                                            $describe = "success1";
                                            original_phone_request_response($status,$describe,$aData2);
                                        }

                                    }else{

                                        $status='401.9';
                                        $describe = "数据为空！";
                                        original_phone_request_response($status,$describe);
                                    }

                                }
                            }
                        }
                    }
                }
                $redisObj->delete($gid."_reflush_time");
                $redisObj->delete("gameMore_".$gid);
                mysqli_query($dbMasterLink,"ROLLBACK");
                $status='401.4';
                $describe = "数据为空！";
                original_phone_request_response($status,$describe);
            }
        }
        //echo "in date <br/>";
        $games = $redisObj->getSimpleOne("gameMore_".$gid);//在redis取出数据
        if ($gid){
            $games = json_decode($games,true);

            foreach ($games['tmp_Obj'] as $k => $v){
                $games['tmp_Obj'][$k]['@attributes'] = '';
                foreach ($v as $k2 => $v2){
                    if (is_array($v2)){
                        if (count($v2)==0){
                            $games['tmp_Obj'][$k][$k2]='';
                        }
                    }
                    elseif ($_REQUEST['isP3']=='Y' and strpos($k2,'ior')!==false){ // 综合过关的赔率+1
                        if(in_array($k2 , ['ior_EOO','ior_EOE','ior_HEOO','ior_HEOE'])) { continue;}
                        // 综合过关-角球独赢不需要加1
                        if($games['tmp_Obj'][$k]['description']=='角球' and ($k2=='ior_MH' or $k2=='ior_MC' or $k2=='ior_MN' or $k2=='ior_HMH' or $k2=='ior_HMC' or $k2=='ior_HMN')){ $games['tmp_Obj'][$k][$k2]=$v2; }
                        else{ $games['tmp_Obj'][$k][$k2]=$v2+1; }
                    }
                }
                $result = $games['tmp_Obj'][$k];
                $aData2[$k] = $result;
            }
            if ($gtype == 'BK'){
                $se_now=array_shift($games['tmp_Obj'])['se_now'];
                if (($se_now=='Q3' || $se_now=='Q4' || $se_now=='H2' || $se_now=='OT' || $se_now=='HT') and $mem_bkq3_off=='off'){
                    $status='401.20';
                    $describe = "数据为空！2 $se_now $mem_bkq3_off";
                    original_phone_request_response($status,$describe);
                }
            }
            $aData2=array_values($aData2);


            if (count($aData2)==0){
                $status='401.11';
                $describe = "数据为空！";
                original_phone_request_response($status,$describe);
            }else{

                $status='200';
                $describe = "success2";
                original_phone_request_response($status,$describe,$aData2);
            }
        }else{

            $status='401.8';
            $describe = "数据为空！";
            original_phone_request_response($status,$describe);
        }
    }else{ //不存在赛事：接口抓取数据，存入数据库，redis
        //echo 'new data<br/>';
        $begin = mysqli_query($dbMasterLink,"start transaction");//开启事务$from
        $lockMid = mysqli_query($dbMasterLink,"select MID from ".DBPREFIX."match_sports_more_midlock where `MID` = $gid for update");
        $valReflushTime2 = $redisObj->getSimpleOne($gid."_reflush_time");
        if(!$valReflushTime2){
            if($begin && $lockMid->num_rows==1 ){
                $dataNew= getDataFromInterface($langx,$gtype,$showtype,$gid,$ecid,$lid,$isrb);
                if( $dataNew['tmp_Obj'] && count($dataNew['tmp_Obj'])>0 && $dataNew['gid_ary'] && count($dataNew['gid_ary'])>0 ){
                    $tmp_Obj=$dataNew['tmp_Obj'];
                    $gid_ary=$dataNew['gid_ary'];
                    $rtStatus=$redisObj->setOne($gid."_reflush_time",time());//写入刷新时间
                    if($rtStatus){
                        $details = json_encode($tmp_Obj,JSON_UNESCAPED_UNICODE);
                        $details=str_replace('\'','',$details);
                        $exitResult = mysqli_query($dbLink,"select MID from ".DBPREFIX."match_sports_more where MID=".$gid);
                        $exitsNum = mysqli_fetch_assoc($exitResult);
                        if(isset($exitsNum['MID'])==$exitsNum['MID']){//更新
                            $setGames = mysqli_query($dbMasterLink,"replace into ".DBPREFIX."match_sports_more(`MID`,`details`)VALUES($gid,'$details')");
                        }else{//存入
                            $setGames = mysqli_query($dbMasterLink,"INSERT INTO ".DBPREFIX."match_sports_more(`MID`,`details`)VALUES($gid,'$details')");
                        }
                        if($setGames){
                            $comStatus = mysqli_query($dbMasterLink,"COMMIT");
                            if($comStatus){
                                $redisObj->setOne("gameMore_".$gid,json_encode(array('status'=>1,'tmp_Obj'=>$tmp_Obj,'gid_ary'=>$gid_ary)));//写入redis


                                if ($gid){

                                    foreach ($tmp_Obj as $k => $v){
                                        $tmp_Obj[$k]['@attributes'] = '';
                                        foreach ($v as $k2 => $v2){
                                            if (is_array($v2)){
                                                if (count($v2)==0){
                                                    $tmp_Obj[$k][$k2]='';
                                                }
                                            }
                                            elseif ($_REQUEST['isP3']=='Y' and strpos($k2,'ior')!==false){ // 综合过关的赔率+1
                                                if(in_array($k2 , ['ior_EOO','ior_EOE','ior_HEOO','ior_HEOE'])) { continue;}
                                                // 综合过关-角球独赢不需要加1
                                                if($tmp_Obj[$k]['description']=='角球' and ($k2=='ior_MH' or $k2=='ior_MC' or $k2=='ior_MN' or $k2=='ior_HMH' or $k2=='ior_HMC' or $k2=='ior_HMN')){ $tmp_Obj[$k][$k2]=$v2; }
                                                else{ $tmp_Obj[$k][$k2]=$v2+1; }
                                            }

                                        }
                                        $aData2[$k] = $tmp_Obj[$k];
                                        // 正网刷水时，足球的附属盘口，从这里插入到数据库，方便结算。准备入库需要的附属盘口的数据
                                        $dataFs=[];
                                        if (trim($flushWay)=='ra' and $gtype=='FT'){
                                            if ($v['gid']>10000 and $v['gid']!=$v['gid_fs']){ // 主盘口跳过，只处理附属盘口

                                                $dataFs['gid']=$v['gid_fs'];
                                                $dataFs['league']=$v['league'];
                                                $dataFs['team_h']=$v['team_h'];
                                                $dataFs['team_c']=$v['team_c'];
                                                $dataFs['M_Date']=explode(' ',$v['datetime'])[0];
                                                $dataFs['M_Time']=getMtime($v['datetime']);
                                                $dataFs['M_Start']=$v['datetime'];
                                                $dataFs['gnum_h']=$v['gnum_h'];
                                                $dataFs['gnum_c']=$v['gnum_c'];

                                                $dataFsArr[] = $dataFs; // 附属盘数据
                                            }
                                        }

                                    }

                                    // 正网刷水时，足球的附属盘口，从这里插入到数据库，方便结算。准备入库需要的附属盘口的数据
                                    if (trim($flushWay)=='ra' and $gtype=='FT' and count($dataFsArr)>0) {
                                        // 判断是否已经插入
                                        $gidFs = implode(',', array_column($dataFsArr, 'gid'));
                                        $gidFsResult = mysqli_query($dbLink, "select MID from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where MID in ($gidFs)");
                                        while ($gidFsRow = mysqli_fetch_assoc($gidFsResult)){
                                            //unset($dataFs[$gidFsRow['MID']]); // 检查附属盘口是否存在，存在则不插入，将此盘口从准备的数据集合中移除
                                            $isExitGid[] = $gidFsRow['MID'];
                                        }

                                        if (count($dataFsArr)>0){
                                            $start=0;
                                            $insert_sql = "INSERT INTO `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` (MID,Type,M_Start,M_Date,M_Time,MB_Team_tw,MB_Team,TG_Team_tw,TG_Team,M_League_tw,M_League,MB_MID,TG_MID,RB_Show)VALUES";
                                            foreach ($dataFsArr as $k => $v){
                                                if(in_array($v['gid'] , $isExitGid)) { continue; } // 已存在
                                                $GID=$v['gid'];
                                                $timestamp=$v['M_Start'];
                                                $m_date=$v['M_Date'];
                                                $m_time=$v['M_Time'];
                                                $TEAM_H=$v['team_h'];
                                                $TEAM_C=$v['team_c'];
                                                $LEAGUE=$v['league'];
                                                $GNUM_H=$v['gnum_h'];
                                                $GNUM_C=$v['gnum_c'];
                                                if($start == 0) {
                                                    $insert_sql .= "('$GID','$gtype','$timestamp','$m_date','$m_time','$TEAM_H','$TEAM_H','$TEAM_C','$TEAM_C','$LEAGUE','$LEAGUE','$GNUM_H','$GNUM_C','1')" ;

                                                }else{
                                                    $insert_sql .= ",('$GID','$gtype','$timestamp','$m_date','$m_time','$TEAM_H','$TEAM_H','$TEAM_C','$TEAM_C','$LEAGUE','$LEAGUE','$GNUM_H','$GNUM_C','1')" ;
                                                }
                                                $start++;
                                            }
                                            if($start>0){ // 有新增数据
                                                mysqli_query($dbMasterLink,$insert_sql) or die ("操作失敗!");
                                            }
                                        }
                                    }

                                    if ($gtype == 'BK'){
                                        $se_now=array_shift($tmp_Obj)['se_now'];
                                        if (($se_now=='Q3' || $se_now=='Q4' || $se_now=='H2' || $se_now=='OT' || $se_now=='HT') and $mem_bkq3_off=='off'){
                                            $status='401.20';
                                            $describe = "数据为空！1 $se_now $mem_bkq3_off";
                                            original_phone_request_response($status,$describe);
                                        }
                                    }
                                    $aData2=array_values($aData2);

                                    if (count($aData2)==0){
                                        $status='401.12';
                                        $describe = "数据为空！";
                                        original_phone_request_response($status,$describe);
                                    }else{

                                        $status='200';
                                        $describe = "success3";
                                        original_phone_request_response($status,$describe,$aData2);
                                    }
                                }else{

                                    $status='401.7';
                                    $describe = "数据为空！";
                                    original_phone_request_response($status,$describe);
                                }

                            }
                        }
                        $redisObj->delete($gid."_reflush_time");
                        $redisObj->delete("gameMore_".$gid);
                    }
                }
            }
            mysqli_query($dbMasterLink,"ROLLBACK");

            $status='401.5';
            $describe = "数据为空！";
            original_phone_request_response($status,$describe);
        }else{
            mysqli_query($dbMasterLink,"ROLLBACK");
            $games = $redisObj->getSimpleOne("gameMore_".$gid);//在redis取出数据
            if ($gid){
                $games = json_decode($games,true);
                $games['tmp_Obj'][$gid]['@attributes']='';

                foreach ($games['tmp_Obj'] as $k => $v){
                    $games['tmp_Obj'][$k]['@attributes'] = '';
                    foreach ($v as $k2 => $v2){
                        if (is_array($v2)){
                            if (count($v2)==0){
                                $games['tmp_Obj'][$k][$k2]='';
                            }else{
                                if ($showtype='RB'){
                                    $games['tmp_Obj'][$k]['sw_ROUH']='N';
                                    $games['tmp_Obj'][$k]['sw_ROUC']='N';
                                }
                            }
                        }
                        elseif ($_REQUEST['isP3']=='Y' and strpos($k2,'ior')!==false){ // 综合过关的赔率+1
                            if(in_array($k2 , ['ior_EOO','ior_EOE','ior_HEOO','ior_HEOE'])) { continue;}
                            // 综合过关-角球独赢不需要加1
                            if($games['tmp_Obj'][$k]['description']=='角球' and ($k2=='ior_MH' or $k2=='ior_MC' or $k2=='ior_MN' or $k2=='ior_HMH' or $k2=='ior_HMC' or $k2=='ior_HMN')){ $games['tmp_Obj'][$k][$k2]=$v2; }
                            else{ $games['tmp_Obj'][$k][$k2]=$v2+1; }
                        }
                    }
                    $result = $games['tmp_Obj'][$k];
                    $aData2[$k] = $result;
                }
                if ($gtype == 'BK'){
                    $se_now=array_shift($games['tmp_Obj'])['se_now'];
                    if (($se_now=='Q3' || $se_now=='Q4' || $se_now=='H2' || $se_now=='OT' || $se_now=='HT') and $mem_bkq3_off=='off'){
                        $status='401.20';
                        $describe = "数据为空！4  $se_now $mem_bkq3_off";
                        original_phone_request_response($status,$describe);
                    }
                }
                $aData2=array_values($aData2);

                if (count($aData2)==0){
                    $status='401.13';
                    $describe = "数据为空！";
                    original_phone_request_response($status,$describe);
                }else{

                    $status='200';
                    $describe = "success4";
                    original_phone_request_response($status,$describe,$aData2);
                }
            }else{

                $status='401.8';
                $describe = "数据为空！";
                original_phone_request_response($status,$describe);
            }

        }
    }
}else{
    $status='401.6';
    $describe = "数据为空！";
    original_phone_request_response($status,$describe);
}

function getGameDate($Datasite,$param){
    $curl = new Curl_HTTP_Client();
    $curl->store_cookies("cookies.txt");
    $curl->set_user_agent("Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Maxthon/4.4.3.3000 Chrome/30.0.1599.101 Safari/537.36");
    $curl->set_referrer($Datasite);
    $gameDataXml = $curl->send_post_data("".$Datasite."/app/member/get_game_allbets.php?",$param,"",10);
    $xml= xmlToArray(trim($gameDataXml));
    return $xml;
}

// 足球滚球的即时时间显示，转上半场,下半场 文字
function reTimeShow($re_time){
    $tmpset = explode("^",$re_time) ;
    // 2017-09-21 64.足球滾球-上半場 計時器00:00暫停時   會員端記分板和時節部分請幫秀比分（原顯示上半場00”)
    $showretime= '' ;
    if($tmpset[1]){
        $showretime = str_replace("'",'',$tmpset[1]);
    }
    switch ($tmpset[0]){
        case "HT":
            $status = '半场';
            break;
        case "1H":
            $status = '上半场';
            break;
        case "2H":
            $status = '下半场';
            break;
        default:
            $status = $tmpset[0];
    }
    return $status.' '.$showretime ;
}
