<?php
/*
 * 冠军联赛数据接口
 * @param  gtype FT 足球 BK 篮球
 * @param  FStype   FT 足球 BK 篮球
 * @param  mtype   4
 * @param  showtype FU 早盘 FT 今日赛事 RB 滚球
 * sorttype  league（联盟排序） time(时间排序)
 *
 * M_League  欧洲冠军杯（显示此联赛全部冠军盘口，以及赔率）
 * lid      28728 (ra686 水源)
 */
//error_reporting(E_ALL);
//ini_set('display_errors','On');

// 判断滚球是否维护-单页面维护功能
//checkMobileMaintain($_REQUEST['showtype']);

$uid=$_SESSION['uid'];
$langx=$_SESSION['Language']?$_SESSION['Language']:'zh-cn';
$mtype=$_REQUEST['mtype'];
$fstype=$_REQUEST['FStype'];
$M_League=$_REQUEST['M_League'];
$rtype=isset($_REQUEST['rtype'])?$_REQUEST['rtype']:'FS';
$league_id=strval($_REQUEST['lid']);
$myleaArr=trim($_REQUEST['myleaArr']);  //myleaArr: 世界杯2018(在俄罗斯),亚足联冠军联赛,巴西甲组联赛
$sorttype = $_REQUEST['sorttype'];
//require ("include/traditional.$langx.inc.php");
//echo '<pre>';
//print_r($_REQUEST);

//if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
//    $status = '401.1';
//    $describe = '请重新登录!';
//    original_phone_request_response($status,$describe);
//}
$username=$_SESSION['UserName'];

//from loadgame_R.php
if ($rtype=='fs'){
	$type="and Gtype!='FI'";
}else if ($rtype=='fi'){
	$type="and Gtype='FI'";
}

if($fstype=="FT"){
	$matchType="足球冠军";
}elseif($fstype=="BK"){
	$matchType="篮球冠军";
}


$open=$_SESSION['OpenType'];
$memname=$_SESSION['UserName'];
$pay_type=$_SESSION['Pay_Type'];

$m_date=date('Y-m-d');
$time=date('H:i:s');
$K=0;

//$mysql = "select datasite,uid,uid_tw,uid_en from ".DBPREFIX."web_system_data where ID=1";
//$result = mysqli_query($dbMasterLink,$mysql);
//$row = mysqli_fetch_assoc($result);
//$site=$row['datasite'];
//switch($langx){
//	case "zh-tw":
//		$suid=$row['uid_tw'];
//		break;
//	case "zh-cn":
//		$suid=$row['uid'];
//		break;
//	case "en-us":
//		$suid=$row['uid_en'];
//		break;
//}

function getShampionMatches(){
    global $dbMasterLink,$fstype,$flushWay;
    if($flushWay == 'ra686'){
        $FT_M_ROU_EO_Time=RUNNING_REDIS_REFLUSH_TIME;   //5s
    }else{
        $FT_M_ROU_EO_Time=TODAY_REDIS_REFLUSH_TIME;     //40s
    }
    if($fstype=="FT"){
        $key="TODAY_FT_Champion";
    }elseif($fstype=="BK"){
        $key="TODAY_BK_Champion";
    }
    $redisObj = new Ciredis();
    $valReflushTime = $redisObj->getSimpleOne($key."_reflush_time");
    if($valReflushTime){//------------------------------------------存在数据
        if(time()-$valReflushTime>$FT_M_ROU_EO_Time){//数据过期
            $begin = mysqli_query($dbMasterLink,"start transaction");//开启事务$from
            $lockResult = mysqli_query($dbMasterLink,"select Type from ".DBPREFIX."match_sports_running_lock where `Type` = '".$key."' for update");
            if($begin && $lockResult->num_rows==1){
                $checkReflushTime1 = $redisObj->getSimpleOne($key."_reflush_time");
                //echo '<br/>';var_dump(time()-$checkReflushTime1);echo '<br/>';
                if(time()-$checkReflushTime1>$FT_M_ROU_EO_Time){//数据过期
                    //echo "==================== out ====================<br/>";
                    $matches=catchShampionByCurl();
                    $updateRes = $redisObj->getSET($key."_reflush_time",time());
                    if( $updateRes ){
                        //echo "<br/>update redis<br/>";
                        $setResult=$redisObj->setOne($key,json_encode($matches));
                        if($setResult) mysqli_query($dbMasterLink,"COMMIT");
                    }
                }else{//直接读取redis
                    //echo "==================== in1 ====================<br/>";
                    $matchesJson = $redisObj->getSimpleOne($key);
                    $matches = json_decode($matchesJson,true);
                }
            }
            mysqli_query($dbMasterLink,"ROLLBACK");
        }else{
            //echo "==================== in2 ====================<br/>";
            $matchesJson = $redisObj->getSimpleOne($key);
            $matches = json_decode($matchesJson,true);
        }
    }else{//------------------------------------------不存在,获取数据
        $begin = mysqli_query($dbMasterLink,"start transaction");//开启事务$from
        $lockResult = mysqli_query($dbMasterLink,"select Type from ".DBPREFIX."match_sports_running_lock where `Type` = '".$key."' for update");
        if($begin && $lockResult->num_rows==1){
            $checkReflushTime2 = $redisObj->getSimpleOne($key."_reflush_time");
            if($checkReflushTime2){
                //echo "==================== in3 ====================<br/>";
                $matchesJson = $redisObj->getSimpleOne($key);
                $matches = json_decode($matchesJson,true);
            }else{
                //echo "==================== new ====================<br/>";
                $matches=catchShampionByCurl();
                $updateRes = $redisObj->getSET($key."_reflush_time",time());
                if( $updateRes ){
                    $setResult=$redisObj->setOne($key,json_encode($matches));
                    if($setResult)  mysqli_query($dbMasterLink,"COMMIT");
                }
            }
            mysqli_query($dbMasterLink,"ROLLBACK");
        }
    }
    return $matches;
}

//获取冠军数据
function catchShampionByCurl(){
	global $langx,$fstype,$flushWay,$FT_FS_SEC_API,$BK_FS_SEC_API,$league_id,$open;
	$result='';
	//获取刷水账号
	$accoutArr = getFlushWaterAccount();
	$curl = new Curl_HTTP_Client();
	$curl->store_cookies("cookies.txt"); 
	$curl->set_user_agent("Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36");
    if($flushWay == 'ra686'){
        $lid = $league_id; //26726
        if($fstype=="FT"){
            $flushDoamin = $FT_FS_SEC_API;
        }elseif($fstype=="BK"){
            $flushDoamin= $BK_FS_SEC_API;
        }

        //获取刷水账号
        $curl = new Curl_HTTP_Client();
        $curl->store_cookies("/tmp/cookies.txt");
        $curl->set_user_agent("Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36");
        $jsonData = $curl->fetch_url($flushDoamin.$lid);  // 请求冠军主盘口
        $aData = json_decode($jsonData,true);
        $cou= count($aData['data']['events']);

        if($cou>0 and $aData['success']) {
            $events = $aData['data']['events'];
            foreach ($events as $k => $aMatchs) {
                $gid = $aMatchs['eventId'];     // 赛事 ID
                if($fstype=="BK") {$gid = transGid($gid);}

                $MLeague_Name = $aMatchs['competitionName'];  // 联赛名称 世界杯2022欧洲外围赛
                $MB_Team = $aMatchs['name'];  // 赛事名称    //世界杯2022欧洲外围赛A组特别投注
                $M_Start = str_replace('T', ' ', $aMatchs['estimatedDeadlineTime']);
                $mcount = count($aMatchs['markets'][0]['outcomes']);

                // 拼凑跟插入和更新数据
                foreach ($aMatchs['markets'][0]['outcomes'] as $t => $outcome){
                    $marketName = $outcome['name'];   // 备注 A组冠军
                    $Gid = $t + 1;
                    $data[$k.'_'.$t]['LID']=$lid;
                    $data[$k.'_'.$t]['MID']=$gid;
                    $data[$k.'_'.$t]['M_Start']= $M_Start;
                    $data[$k.'_'.$t]['MB_Team']= trim($MB_Team);
                    $data[$k.'_'.$t]['M_League']= trim($MLeague_Name);
                    $data[$k.'_'.$t]['M_Item']= trim($outcome['name']);
                    $data[$k.'_'.$t]['M_Rate']=$outcome['euOdds'];  //赔率
                    //$data[$k.'_'.$t]['Gid']= 'FS0' . $Gid;    //参照正网 FT:FS01    BK: FS0C
                    $data[$k.'_'.$t]['Gid']=  strval($outcome['outcomeId']); // 暂时方便区分唯一
                    $data[$k.'_'.$t]['mcount']= intval($mcount);
                    $data[$k.'_'.$t]['Gtype']= strval($fstype);
                    $data[$k.'_'.$t]['mshow']='Y';      //参照正网 gopen=Y
                    $data[$k.'_'.$t]['mshow2']='N';     //参照正网 $rtype['result']=N
                }

                // 拼凑返回接口数据
                $key = $k;
                $aData2[$key]['gid'] = $gid;
                $aData2[$key]['M_League'] = trim($MLeague_Name);
                $aData2[$key]['M_time'] = $M_Start;
                $aData2[$key]['teamsname'] = trim($MB_Team);

                foreach ($aMatchs['markets'][0]['outcomes'] as $t2 => $outcome){
                    $item['team_name_fs'] = trim($outcome['name']);
                    $item['ratio'] = change_rate($open,$outcome['euOdds']);
                    $item['rtype'] = strval($outcome['outcomeId']);
                    $aData2[$key]['item'][]= $item;
                }
                $aData2[$key]['item'] = array_values($aData2[$key]['item']);
            }

            if(count($data) > 0) {  //插入和更新数据
                checkFsData($data);
            }

            return $aData2;
        }
    }else{
	foreach($accoutArr as $key=>$value){//在扩展表中获取账号重新刷水
	    /*$curl->set_referrer("" . $value['Datasite'] . "/app/member/browse_FS/loadgame_R.php?rtype=fs&uid=".$value['Uid']."&langx=$langx&mtype=3");
	    $html_data = $curl->fetch_url("" . $value['Datasite'] . "/app/member/browse_FS/reloadgame_R.php?uid=".$value['Uid']."&langx=$langx&rtype=fs&league_id=&FStype=$fstype");//redis缓存全部联赛,去掉联赛id参数,php进行筛选
	    $a = array(
	        "if(self == top)",
	        "<script>",
	        "</script>",
	        "new Array()",
	        "new Array();",
	        "\n\n"
	    );
	    $b = array(
	        "",
	        "",
	        "",
	        "",
	        "",
	        ""
	    );
	    $msg = str_replace($a,$b,$html_data);
	    preg_match_all("/new Array\((.+?)\);/is",$msg,$matches);
	    $cou_num=sizeof($matches[0]);
		if($cou_num>0){
			preg_match_all("/parent.areasarray=(.+?);/is",$html_data,$areasarray);
		    preg_match_all("/parent.itemsarray=(.+?);/is",$html_data,$itemsarray);
		    preg_match_all("/parent.leaguearray=(.+?);/is",$html_data,$leaguearray);
	        $result['data'] = $matches[0];
	        $result['areas'] = $areasarray;
	        $result['items'] = $itemsarray;
	        $result['league'] = $leaguearray;
			break;
	    }*/

        // 首先获取全部联赛的lid
        $postdata = array(
            'p' => 'get_league_list_FS',
            'ver' => date('Y-m-d-H').$value['Ver'],
            'langx' => $langx,
            'uid' => $value['Uid'],
            'gtype' => $fstype,
            'FS' => 'Y',
            'showtype' => 'fu',
        );
        $xml_data=$curl->send_post_data($value['Datasite']."/transform.php?ver=".date('Y-m-d-H').$value['Ver'], $postdata);
        $aData = xmlToArray($xml_data);
        if ($aData['status']=='success'){

            $couFT=0;
            $aLid=[];
            if (count($aData['classifier']['region'])>0){
                $lids = getLids($aData);
                $aLid = explode(',' , $lids);
                $couLeague = count($aLid);
                $couFT+=$couLeague;
            }else{
                return $aLid;
            }

            // 循环将每一个联赛下面的全部冠军玩法都捞出来
            unset($postdata);
            unset($aData);
            $postdata = array(
                'p' => 'get_game_list_FS',
                'ver' => date('Y-m-d-H').$value['Ver'],
                'langx' => $langx,
                'uid' => $value['Uid'],
                'gtype' => $fstype,
                'showtype' => 'FU',
                'rtype' => 'fs',
            );
            $aLidFs=[];
            foreach ($aLid as $k => $lid){

                $postdata['league_id'] = $lid;
                $xml_data=$curl->send_post_data($value['Datasite']."/transform.php?ver=".date('Y-m-d-H').$value['Ver'], $postdata);
                $aData = xmlToArray($xml_data);
                $aLidFs[$lid] = $aData['game'];

            }
            $result=$aLidFs;
        }
	}
    }
	return $result;
}

// 插入更新冠军数据
function checkFsData($data) {
    global $dbMasterLink,$dbLink;

    // 捞出数据库已有的数据，与拉取数据比较，方便找到哪些是插入的数据、和更新的数据
    $dateToday=date('Y-m-d');
    $sql = "select ID,MID,Gid from ".DBPREFIX.SPORT_FLUSH_FS_MATCH_TABLE." where M_Start>='$dateToday' ";
    $checkresult = mysqli_query($dbLink,$sql);
    $aCrt=[];
    while ($crs=mysqli_fetch_assoc($checkresult)){
        $aCrt[$crs['MID'].'_'.$crs['Gid']] = $crs;
    }

    // 循环拉取到的冠军数据，将插入和更新的数据分开
    $start = 0;
    $insert_sql = "INSERT INTO ".DBPREFIX.SPORT_FLUSH_FS_MATCH_TABLE."(MID,M_Start,MB_Team_tw,M_League_tw,M_Item_tw,MB_Team,M_League,M_Item,M_Rate,Gid,mcount,Gtype,mshow,mshow2) VALUES" ;

    $uptime=date('Y-m-d H:i:s');
    $updateaccount =0 ; //用于判断是否有更新数据
    $e_sql = "END,";
    $update_sql = "update ".DBPREFIX.SPORT_FLUSH_FS_MATCH_TABLE." set "; // update
    $uptime_sql = "uptime = CASE ID ";
    $mshow_sql = "mshow = CASE ID ";
    $mshow2_sql = "mshow2 = CASE ID ";
    $MB_Team_sql = "MB_Team = CASE ID ";
    $M_League_sql = "M_League = CASE ID ";
    $M_Item_sql = "M_Item = CASE ID ";
    $Gid_sql = "Gid = CASE ID ";
    $M_Rate_sql = "M_Rate = CASE ID ";
    $mcount_sql = "mcount = CASE ID ";
    $M_Start_sql = "M_Start = CASE ID ";
    $ids='';

    foreach ($data as $k => $v){

        $MID=$v['MID'];
        $M_Start=$v['M_Start'];
        $MB_Team=$v['MB_Team'];
        $M_League=$v['M_League'];
        $M_Item=$v['M_Item'];
        $rate=$v['M_Rate'];
        $tid=$v['Gid'];
        $mcount=$v['mcount'];
        $gtype=$v['Gtype'];
        $mshow=$v['mshow'];
        $mshow2=$v['mshow2'];

        $sCheckKey = $MID.'_'.$tid;
        if (array_key_exists($sCheckKey, $aCrt)){
            $id = $aCrt[$sCheckKey]['ID'];
            $ids .= $id.',';

            // 存在则更新
            $uptime_sql .= "WHEN $id THEN '$uptime' ";
            $mshow_sql .= "WHEN $id THEN '$mshow' ";
            $mshow2_sql .= "WHEN $id THEN '$mshow2' ";
            $MB_Team_sql .= "WHEN $id THEN '$MB_Team' ";
            $M_League_sql .= "WHEN $id THEN '$M_League' ";
            $M_Item_sql .= "WHEN $id THEN '$M_Item' ";
            $Gid_sql .= "WHEN $id THEN '$tid' ";
            $M_Rate_sql .= "WHEN $id THEN '$rate' ";
            $mcount_sql .= "WHEN $id THEN '$mcount' ";
            $M_Start_sql .= "WHEN $id THEN '$M_Start' ";
            $updateaccount++;

        }
        else{
            // 不存在则插入
            if($start == 0) {
                $insert_sql .="('$MID','$M_Start','$MB_Team','$M_League','$M_Item','$MB_Team','$M_League','$M_Item','$rate','$tid','$mcount','$gtype','$mshow','$mshow2')" ;
            } else{
                $insert_sql .=",('$MID','$M_Start','$MB_Team','$M_League','$M_Item','$MB_Team','$M_League','$M_Item','$rate','$tid','$mcount','$gtype','$mshow','$mshow2')" ;
            }
            $start++;
        }
    }

    if($start>0){ // 有新增数据
        mysqli_query($dbMasterLink,$insert_sql) or die ("新入库操作失敗!");
    }

    if($updateaccount>0){
        $ids = rtrim($ids,',');
        $update_sql .= $uptime_sql.$e_sql.$mshow_sql.$e_sql.$mshow2_sql.$e_sql.$MB_Team_sql.$e_sql.$M_League_sql.$e_sql.$M_Item_sql.$e_sql.$MB_Team_sql.$e_sql.$M_League_sql.$e_sql.$M_Item_sql.$e_sql.$Gid_sql.$e_sql.$M_Rate_sql.$e_sql.$mcount_sql.$e_sql.$M_Start_sql;
        $update_sql .="END WHERE ID IN ($ids)"; // 实现一次性更新数据库操作
        if (mysqli_query($dbMasterLink,$update_sql)){}else{
            die ("更新操作失敗!!");
        }
    }
    return true;
}

$allcount = 0;
$reBallCountCur = 0;

if($flushWay == 'ra686'){   //6686水源
    if(empty($M_League) && empty($league_id)) {  //联盟列表
        switch ($fstype){
            case 'FT':
                $returnData = $redisObj->getSimpleOne("FT_FS_COMPETITION_LIST");
                $aData = json_decode($returnData,true);

                // 从联赛信息中获取地区/国家
                //$leagueRegion = $redisObj->getSimpleOne("FT_FS_LEAGUE_REGION");
                //$aLeagueRegion = json_decode($leagueRegion,true);

                $aData2=[];
                foreach ($aData as $k => $region) {
                    $id = $region['id']; // 地区id
                    $regionname = $region['name'];
                    $popular = $region['popular']; // boolean
                    $cou = count($region['seasons']); // 当前赛事场数
                    if($cou>0) {
                        foreach ($region['seasons'] as $k2 => $aLeagues) {
                            $aData2[$id][$k2]['lid']=$aLeagues['id']; //联赛id
                            $aData2[$id][$k2]['M_League']=$aLeagues['name'];
                            $aData2[$id][$k2]['M_League_Initials']=_getFirstCharter($aLeagues['name']);
                            $aData2[$id][$k2]['region_id']=$id;
                            $aData2[$id][$k2]['region']=$regionname;
                            $aData2[$id][$k2]['num']=$aLeagues['count'];
                        }
                    }
                }

                break;
            case 'BK':
                $returnData = $redisObj->getSimpleOne("BK_FS_COMPETITION_LIST");
                $aData = json_decode($returnData,true);

                // 从联赛信息中获取地区/国家
                //$leagueRegion = $redisObj->getSimpleOne("BK_FS_LEAGUE_REGION");
                //$aLeagueRegion = json_decode($leagueRegion,true);

                $aData2=[];
                foreach ($aData as $k => $region) {

                    $id = $region['id']; // 地区id
                    $regionname = $region['name'];
                    $popular = $region['popular'];
                    $cou = count($region['seasons']); // 当前赛事场数
                    if($cou>0) {
                        foreach ($region['seasons'] as $k2 => $aLeagues) {

                            $aData2[$id][$k2]['lid']=$aLeagues['id']; //联赛id
                            $aData2[$id][$k2]['M_League']=$aLeagues['name'];
                            $aData2[$id][$k2]['M_League_Initials']=_getFirstCharter($aLeagues['name']);
                            $aData2[$id][$k2]['region_id']=$id;
                            $aData2[$id][$k2]['region']=$regionname;
                            $aData2[$id][$k2]['num']=$aLeagues['count'];

                        }
                    }
                }

                break;
            default: break;
        }

        // 处理地区数据
        $i=0;
        foreach ($aData2 as $key => $match) {   // 地区有多个联赛
            foreach($match as $key2 => $value2) {
                $aData3[$i]['lid'] = $value2['lid'];
                $aData3[$i]['M_League'] = $value2['M_League'];
                $aData3[$i]['M_League_Initials'] = $value2['M_League_Initials'];
                $aData3[$i]['region_id'] = $value2['region_id'];
                $aData3[$i]['region'] = $value2['region'];
                $aData3[$i]['num'] = $value2['num'];
                $i=$i+1;
            }
        }

        // 按照联盟排序
        if ($sorttype == 'league'){
            $aData4 = array_sort($aData3,'M_League_Initials',$type='asc');
            $i=0;
            foreach ($aData4 as $key3 => $value3) {
                $aData5[$i]['lid'] = $value3['lid'];
                $aData5[$i]['M_League'] = $value3['M_League'];
                $aData5[$i]['region_id'] = $value3['region_id'];
                $aData5[$i]['region'] = $value3['region'];
                $aData5[$i]['num'] = $value3['num'];
                $i=$i+1;
            }
            $aData3 = $aData5;
        }
    }else{ //联盟详情
        $aData2 = getShampionMatches();
        $aData3=array_values($aData2);
    }
}else{  //正网水源
    $result = getShampionMatches();

    /*$matchesTem = isset($result['data'])?$result['data']:'';
    $areasarray = isset($result['areas'])?$result['areas']:'';
    $itemsarray = isset($result['items'])?$result['items']:'';
    $leaguearray = isset($result['league'])?$result['league']:'';

    $leagueSearchName=$matcheNew=array();
    if(isset($league_id) && strlen($league_id)>2 && count($matchesTem)>0){
        $leagueSearchName = explode(',',$league_id);
        if(count($leagueSearchName>0)){
            foreach($matchesTem as $mk=>$mv){
                $mvStr=str_replace('\'','',$mv);
                $mvStrArr=explode(',',$mvStr);
                if(in_array($mvStrArr[2],$leagueSearchName)){
                    $matches[]=$mv;
                }
            }
        }else{
            $matches=$matchesTem;
        }
    }else{
        $matches=$matchesTem;
    }
    $cou_num=count($matches);*/
    if(strlen($league_id)==0){
        $leagueIdNum='ALL';
    }else{
        $leagueIdNum=count(explode(',',$league_id));
    }


    foreach ($result as $lid => $league){
        if (!isset($league['gamecount'])){
            foreach ($league as $k => $match){
                $leagues[]=$match;
            }
        }else{
            $leagues[]=$league;
        }
    }

    // 联盟为空时，显示全部联盟数量，
    // 联盟不为空时，显示此联盟相关信息（gid、联盟名称、冠军标题、联赛时间、赔率）
    if (empty($M_League)){

        if(isset($leagues)&&is_array($leagues)&&count($leagues)>0) {
            $aData = array();
            foreach ($leagues as $key => $match) {
                /*$match=str_replace('new Array(', '', $match);
                $match=str_replace(');', '', $match);
                $match=str_replace('\'', '', $match);
                $match=explode(',',$match);*/
                $aData[$key]['gid'] = $match['gid'];
                $aData[$key]['M_League'] = $match['league'];
                $aData[$key]['M_time'] = $match['datetime'];
                $aData[$key]['teamsname'] = $match['teamsname'];
        //        $aData[$key]=$match;
            }
        }

        // 联盟相同的归成一类
        $aData2 = group_same_key($aData,'M_League');
        $i=0;
        foreach ($aData2 as $k => $v){
        //    foreach ($v as $k2 => $v2){
        //        $aData3[$i]['gid'] .= $v2['gid'].',';
        //    }
        //    $aData3[$i]['gid']=rtrim($aData3[$i]['gid'] , ',');
            $aData3[$i]['M_League'] = $k;
            $aData3[$i]['region'] = '地区';
            $aData3[$i]['num'] = count($v);
            $i=$i+1;
        }

    }else{

        if(isset($leagues)&&is_array($leagues)&&count($leagues)>0) {
            $aData = array();
            foreach ($leagues as $key => $match) {
                /*$match=str_replace('new Array(', '', $match);
                $match=str_replace(');', '', $match);
                $match=str_replace('\'', '', $match);
                $match=explode(',',$match);*/

                if ($match['league'] == $M_League){
                    $aData[$key]['gid'] = $match['gid'];
                    $aData[$key]['M_League'] = $match['league'];
                    $aData[$key]['M_time'] = $match['datetime'];
                    $aData[$key]['teamsname'] = $match['teamsname'];

                    foreach ($match['rtypes'] as $k => $type){

    //                    if ($match[$i + 3] > 0) {
                            $item['team_name_fs'] = $type['teams'];
                            $item['ratio'] = change_rate($open,$type['ioratio']);
                            $item['rtype'] = $type['rtype'];
                            $aData[$key]['item'][]= $item;
    //                    }

                    }
                    $aData[$key]['item'] = array_values($aData[$key]['item']);
                }
            }
            $aData3=array_values($aData);
        }
    }
}
$aData3 = $aData3?$aData3:[] ; // 没有数据返回空数组，不要返回 null
$status = '200';
$describe = 'success';
original_phone_request_response($status, $describe, $aData3);
