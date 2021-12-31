<?php
//session_start();
/**
 * /FT_order_hre_finish_api.php
 * 足球滚球半场下注接口
 *
 * gid  比赛盘口唯一ID
 * active   1 足球滚球、今日赛事, 11 足球早餐，2 篮球滚球、今日赛事, 22 篮球早餐
 * line_type  玩法列号
 * odd_f_type      默认传参H。 H 香港盘，M 马来盘，I 印尼盘，E 欧洲盘
 * gold  金额
 * type   H 主队独赢 C 客队独赢 N 和局  C 滚球大小-小  H 滚球大小-大 C 球队得分大小-主队 H 球队得分大小-客队 H 主队让球 C 客队让球
 * pay_type   0 信用额投注  1 现金投注【此参数暂时不需要】
 * ioradio_r_h  赔率 （让球，大小，半场让球，半场大小 ）投注时，传参
 * rtype  单双玩法投注传参，让后赋值给mtype   ODD 单 EVEN 双
 * wtype 全场大小、半场大小、球队得分大小 OUH 大，OUC 小，ROUH 球队得分大小-大，ROUC 球队得分大小-小
 *
 * strong
 * gnum  投注的队伍ID
 * ioradio_r_h  赔率
 * randomNum 随机数
 * autoOdd 自动接收较佳赔率
 */
//error_reporting(E_ALL);
//ini_set('display_errors','On');
//include('../include/address.mem.php');
//include_once('../include/config.inc.php');
//require_once("../../../common/sportCenterData.php");
//require ("../include/curl_http.php");
//require ("../include/define_function_list.inc.php");

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {

        $status='401.1';
        $describe="请重新登录";
        original_phone_request_response($status,$describe);

}

$randomNum = $_REQUEST['randomNum']; // 随机整数
if(!$randomNum){
    $status='401.2';
    $describe="参数不对";
    original_phone_request_response($status,$describe);
}
if($randomNum == $_SESSION['randomNum']){ // 重复提交
    $status='401.3';
    $describe="请不要重复下注";
    original_phone_request_response($status,$describe);
}else { // 正常提交
    $_SESSION['randomNum'] = $randomNum;

    //接收传递过来的参数：其中赔率和位置需要进行判断
    $uid = $_SESSION['Oid'];
    $langx = $_SESSION['Language'];
    $gold = $_REQUEST['gold'];
    $active=1; // 强制足球滚球     //1 足球滚球、今日赛事, 11 足球早餐\r\n2 篮球滚球、今日赛事, 22 篮球早餐
    $strong = $_REQUEST['strong'];
    $line = $_REQUEST['line_type'];
    $gid = $_REQUEST['gid'];
    $gid_fs = $_REQUEST['gid_fs'];
    // 外面的玩法不需要传id（外面玩法只有大小、让球），更多玩法投注需要传入参数id
    if (strlen($_REQUEST['rtype'])==0 ){}
    else{
        $_REQUEST['id']=$_REQUEST['gid'];
    }
    $type = $_REQUEST['type'];
    $rtype = $_REQUEST['rtype'];
    $wtype = $_REQUEST['wtype'];
    $gnum = $_REQUEST['gnum'];
//    $ioradio_r_h=$_REQUEST['ioradio_r_h'];
    $odd_f_type = $_REQUEST['odd_f_type'];

    //require("../include/traditional.$langx.inc.php");
    //下注时的赔率：应该根据盘口进行转换后，与数据库中的赔率进行比较。若不相同，返回下注。
    $sql = "select ratio,Money,CurType,Status from ".DBPREFIX.MEMBERTABLE." where ID='{$_SESSION['userid']}'";
    $result = mysqli_query($dbMasterLink, $sql);
    $memrow = mysqli_fetch_assoc($result);
    $open = $_SESSION['OpenType'];
    $pay_type = $_SESSION['Pay_Type'];
    $memname = $_SESSION['UserName'];
    $agents = trim($_SESSION['Agents']); // 代理 D
    $world = $_SESSION['World']; // 总代 C
    $corprator = $_SESSION['Corprator']; // 股东 B
    $super = $_SESSION['Super']; // 公司 A
    $admin = $_SESSION['Admin']; // 管理员 （？子账号）
    $w_ratio = $memrow['ratio'];
    $HMoney = $Money = $memrow['Money'];
    if($HMoney < $gold || $gold<10 || $HMoney<=0){

        $status = '401.4';
        $describe = "下注金額不可大於信用額度。";
        original_phone_request_response($status, $describe);

    }

    if ($memrow['Status'] == 1) {

        $status = '403.5';
        $describe = "账户已冻结，请联系客服解冻";
        original_phone_request_response($status, $describe);

    }

    if ($memrow['Status'] == 2) {

        $status = '403.6';
        $describe = "账户已停用，请联系客服";
        original_phone_request_response($status, $describe);

    }
    $w_current = $memrow['CurType'];
    $memid = $_SESSION['userid'];
    $test_flag = $_SESSION['test_flag'];

    //------------------------------------------------------滚球效验 start
    $allcount=0;
    $accoutArr =$uniqueIpArray= array();
    if($flushWay == 'ra') { //正网
        $accoutArr = getFlushWaterAccount();//数组随机排序
    }
    $accoutArrNum = count($accoutArr);
    $curl = new Curl_HTTP_Client();
//    $curl->store_cookies("/tmp/cookies.txt");
    $dateCur = date('Y-m-d',time());
    $curl->store_cookies("/tmp/cookies.txt");
    $curl->set_user_agent("Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36");
    foreach($accoutArr as $key=>$value){//在扩展表中获取账号重新刷水
        $allcount = $allcount + 1;
        $site=$value['Datasite'];
        $suid=$value['Uid'];

        $curl->set_referrer("".$site."");
        $postdata = array(
            'p' => 'FT_order_view',
            'ver' => date('Y-m-d-H').$value['Ver'],
            'langx' => $langx,
            'uid' => $value['Uid'],
            'odd_f_type' => $odd_f_type,
            'gid' => $gid+1,
            'gtype' => 'FT',
            'wtype' => $wtype,
        );
        switch ($line){
            case '20':
//                $sgid=$gid+1;
//                $html_data=$curl->fetch_url("".$site."/app/member/FT_order/FT_order_all.php?gid=$sgid&uid=$suid&odd_f_type=$odd_f_type&type=$type&gnum=$gnum&langx=zh-cn&ptype=&imp=N&rtype=HROU{$type}&wtype=HROU");
                $postdata['chose_team']=$type;// 半场大小
                break;
            case '19':
//                $sgid=$gid+1;
//                $html_data=$curl->fetch_url("".$site."/app/member/FT_order/FT_order_all.php?gid=$sgid&uid=$suid&odd_f_type=$odd_f_type&type=$type&gnum=$gnum&strong=$strong&langx=zh-cn&ptype=&imp=N&rtype=HRE{$type}&wtype=HRE");
                $postdata['wtype']='HRE'; // 半场让球
                $strong=$type;
                $postdata['chose_team']=$strong;
                break;
            case '31':
//                $sgid=$gid+1;
//                $html_data=$curl->fetch_url("".$site."/app/member/FT_order/FT_order_all.php?gid=$sgid&uid=$suid&odd_f_type=$odd_f_type&type=$type&gnum=$gnum&langx=zh-cn&ptype=&imp=N&rtype=HRM{$type}&wtype=HRM");
                $postdata['chose_team']=$type;// 半场独赢
                break;
            case '204':
//                $sgid=$gid+1;
//                $rtypeSub=substr($rtype,1);
//                $html_data=$curl->fetch_url("".$site."/app/member/FT_order/FT_order_all.php?gid=$sgid&uid=$suid&odd_f_type=$odd_f_type&langx=zh-cn&rtype=$rtypeSub&wtype=HRPD&imp=N&ptype=");
                $postdata['chose_team']=substr($rtype,1);// 波胆/上半场
                $postdata['wtype']='HRPD';
                break;
            case '205':
                $postdata['chose_team']=$rtype;
//                print_r($postdata);
                break;
            case '206':
                $postdata['chose_team']=$wtype;
//                $sgid=$gid+1;
//                $html_data=$curl->fetch_url("".$site."/app/member/FT_order/FT_order_all.php?gid=$sgid&uid=$suid&odd_f_type=$odd_f_type&langx=".$langx."&rtype=".$rtype);
                break;
            case '244': // 半场-球队进球数-大小
                $postdata['chose_team']=$type;
//                $sgid=$gid+1;
//                $html_data=$curl->fetch_url($site."/app/member/FT_order/FT_order_all.php?gid=".$gid."&uid=".$suid."&odd_f_type=".$odd_f_type."&langx=".$langx."&rtype={$rtype}&gnum=".$gnum."&type=".$type."&wtype=ROU{$odd_f_type}&imp=N&ptype=");
                break;
        }
        $xml_data=$curl->send_post_data($value['Datasite']."/transform.php?ver=".date('Y-m-d-H').$value['Ver'], $postdata);
//	$msg_c=explode("@",$html_data);
        $aData = xmlToArray($xml_data);
//	if(sizeof($msg_c)>1){
        if($aData['ioratio']>0){
            break;
        }elseif($allcount==$accoutArrNum){
            $status='401.7';
            $describe=$Order_Odd_changed_please_game_again;
            original_phone_request_response($status,$describe);
        }
    }
    //------------------------------------------------------滚球效验 end
    $mysql = "select `ECID`,`LID`,`ISRB` from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `MID`='$gid' and Open=1 and MB_Team!=''";
    $result = mysqli_query($dbLink,$mysql);
    $row = mysqli_fetch_assoc($result);
    $cou = mysqli_num_rows($result);
    if($cou==0){
        echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
        exit();
    }
    $ecid=$row['ECID'];
    $lid=$row['LID'];
    $isrb=$row['ISRB'];


    // -------------------------------------------------------更多玩法刷水 Start
    $redisObj = new Ciredis();
    if($_REQUEST['id']&&$_REQUEST['id']>0) {
        $gtype = 'FT';
        $showtype = 'RB';
        $midLockSet = '';
        $midLockCheck = mysqli_query($dbLink, "select MID from " . DBPREFIX . "match_sports_more_midlock where `MID` = $gid");
        $cou = mysqli_num_rows($midLockCheck);
        if ($cou == 0) $midLockSet = mysqli_query($dbMasterLink, "INSERT INTO " . DBPREFIX . "match_sports_more_midlock(`MID`)VALUES({$gid})");
        if ($midLockSet || $cou == 1) {
            $valReflushTime = $redisObj->getSimpleOne($gid . "_reflush_time");
            if ($valReflushTime) { //存在赛事,更新数据库，redis
                if ($showtype == "RB") {
                    $reflushTime = 5;
                } elseif ($showtype == "FU") {
                    $reflushTime = 10;
                } else {
                    $reflushTime = 20;
                }
                if (time() - $valReflushTime > $reflushTime) { //数据过期,重新抓取更新数据库,redis
                    //echo "out date re get<br/>";
                    $begin = mysqli_query($dbMasterLink, "start transaction");//开启事务$from
                    $lockMid = mysqli_query($dbMasterLink, "select MID from " . DBPREFIX . "match_sports_more_midlock where `MID` = $gid for update");
                    $valReflushTime1 = $redisObj->getSimpleOne($gid . "_reflush_time");
                    if (time() - $valReflushTime1 > $reflushTime) {
                        if ($begin && $lockMid->num_rows == 1) {
                            $dataNew = getDataFromInterface($langx, $gtype, $showtype, $gid,$ecid,$lid,$isrb);
                            if ($dataNew['tmp_Obj'] && count($dataNew['tmp_Obj']) > 0 && $dataNew['gid_ary'] && count($dataNew['gid_ary']) > 0) {
                                $tmp_Obj = $dataNew['tmp_Obj'];
                                $gid_ary = $dataNew['gid_ary'];
                                $updateSt = $redisObj->getSET($gid . "_reflush_time", time());
                                if ($updateSt) {
                                    $details = json_encode($tmp_Obj,JSON_UNESCAPED_UNICODE);
                                    $details = str_replace('\'', '', $details);
                                    $setGames = mysqli_query($dbMasterLink, "replace into " . DBPREFIX . "match_sports_more(`MID`,`details`)VALUES($gid,'$details')");
                                    if ($setGames) {
                                        $comStatus = mysqli_query($dbMasterLink, "COMMIT");
                                        if ($comStatus) {
                                            $redisObj->getSET("gameMore_" . $gid, json_encode(array('status' => 1, 'tmp_Obj' => $tmp_Obj, 'gid_ary' => $gid_ary)));//写入redis
                                        } else {
                                            $redisObj->delete($gid . "_reflush_time");
                                            mysqli_query($dbMasterLink, "ROLLBACK");
                                        }
                                    } else {
                                        $redisObj->delete($gid . "_reflush_time");
                                        mysqli_query($dbMasterLink, "ROLLBACK");
                                    }
                                } else {
                                    $redisObj->delete($gid . "_reflush_time");
                                    mysqli_query($dbMasterLink, "ROLLBACK");
                                }
                            } else {
                                $redisObj->delete($gid . "_reflush_time");
                                mysqli_query($dbMasterLink, "ROLLBACK");
                            }
                        } else {
                            $redisObj->delete($gid . "_reflush_time");
                            mysqli_query($dbMasterLink, "ROLLBACK");
                        }
                    }
                }
                //echo "in date <br/>";
                $games = $redisObj->getSimpleOne("gameMore_" . $gid);//在redis取出数据
            } else {//不存在赛事：接口抓取数据，存入数据库，redis
                //echo 'new data<br/>';
                $begin = mysqli_query($dbMasterLink, "start transaction");//开启事务$from
                $lockMid = mysqli_query($dbMasterLink, "select MID from " . DBPREFIX . "match_sports_more_midlock where `MID` = $gid for update");
                $valReflushTime2 = $redisObj->getSimpleOne($gid . "_reflush_time");
                if (!$valReflushTime2) {
                    if ($begin && $lockMid->num_rows == 1) {
                        $dataNew = getDataFromInterface($langx, $gtype, $showtype, $gid,$ecid,$lid,$isrb);
                        if ($dataNew['tmp_Obj'] && count($dataNew['tmp_Obj']) > 0 && $dataNew['gid_ary'] && count($dataNew['gid_ary']) > 0) {
                            $tmp_Obj = $dataNew['tmp_Obj'];
                            $gid_ary = $dataNew['gid_ary'];
                            $rtStatus = $redisObj->setOne($gid . "_reflush_time", time());//写入刷新时间
                            if ($rtStatus) {
                                $details = json_encode($tmp_Obj,JSON_UNESCAPED_UNICODE);
                                $details = str_replace('\'', '', $details);
                                $exitResult = mysqli_query($dbLink, "select MID from " . DBPREFIX . "match_sports_more where MID=" . $gid);
                                $exitsNum = mysqli_fetch_assoc($exitResult);
                                if (isset($exitsNum['MID']) == $exitsNum['MID']) {//更新
                                    $setGames = mysqli_query($dbMasterLink, "replace into " . DBPREFIX . "match_sports_more(`MID`,`details`)VALUES($gid,'$details')");
                                } else {//存入
                                    $setGames = mysqli_query($dbMasterLink, "INSERT INTO " . DBPREFIX . "match_sports_more(`MID`,`details`)VALUES($gid,'$details')");
                                }
                                if ($setGames) {
                                    $comStatus = mysqli_query($dbMasterLink, "COMMIT");
                                    if ($comStatus) {
                                        $redisObj->setOne("gameMore_" . $gid, json_encode(array('status' => 1, 'tmp_Obj' => $tmp_Obj, 'gid_ary' => $gid_ary)));//写入redis
                                    } else {
                                        $redisObj->delete($gid . "_reflush_time");
                                        mysqli_query($dbMasterLink, "ROLLBACK");
                                    }
                                } else {
                                    $redisObj->delete($gid . "_reflush_time");
                                    mysqli_query($dbMasterLink, "ROLLBACK");
                                }
                            } else {
                                $redisObj->delete($gid . "_reflush_time");
                                mysqli_query($dbMasterLink, "ROLLBACK");
                            }
                        } else {
                            $redisObj->delete($gid . "_reflush_time");
                            mysqli_query($dbMasterLink, "ROLLBACK");
                        }
                    } else {
                        $redisObj->delete($gid . "_reflush_time");
                        mysqli_query($dbMasterLink, "ROLLBACK");
                    }
                } else {
                    mysqli_query($dbMasterLink, "ROLLBACK");
                    $games = $redisObj->getSimpleOne("gameMore_" . $gid);//在redis取出数据
//                echo $games;
//                exit();
                }
            }
        } else {
            $status = '401.9';
            $describe = "更多玩法数据为空3";
            original_phone_request_response($status, $describe);
        }
    }
    // -------------------------------------------------------更多玩法刷水 End

    if($gid%2 == 1){
        $mysql = "select * from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `MID`=$gid and Open=1 and MB_Team!=''";
    }else{
        $mysql = "select * from `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `MID`='$gid' and Open=1 and MB_Team!=''";
    }
    $result = mysqli_query($dbCenterSlaveDbLink,$mysql);
    $cou=mysqli_num_rows($result);
    $row = mysqli_fetch_assoc($result);
    if($cou==0){
        $status='401.8';
        $describe=$Order_This_match_is_closed_Please_try_again;
        original_phone_request_response($status,$describe);

    }
    else {

        // 电竞足球停用的中断投注投注
        if(strpos($_SESSION['gameSwitch'],'|')>0){
            $gameArr=explode('|',$_SESSION['gameSwitch']);
        }else{
            if(strlen($_SESSION['gameSwitch'])>0){
                $gameArr[]=$_SESSION['gameSwitch'];
            }else{
                $gameArr=array();
            }
        }
        $check_str = explode('电竞足球',$row['M_League']);
        if(in_array('DJFT',$gameArr) and count($check_str)>1){
            $status = '403.6';
            $describe = "账户电竞足球异常，请联系客服";
            original_phone_request_response($status, $describe);
        }

        if($_REQUEST['id'] && $_REQUEST['id']>0){
            $moreMethod = array(19,20,204,205,206,244,31);
            if(isset($_REQUEST['dataSou']) && $_REQUEST['dataSou']=="interface"){
                array_push($moreMethod,$line);
            }

            if($line==19) array_push($moreMethod,$line);

            if(in_array($line,$moreMethod)){
                $moreRes = mysqli_query($dbMasterLink,"select details from ".DBPREFIX."match_sports_more where `MID`='$gid'");
                $rowMore = mysqli_fetch_assoc($moreRes);
                $couMore = mysqli_num_rows($moreRes);
                $detailsArr = json_decode($rowMore['details'],true);
//                $detailsData =$detailsArr[$gid];
                if ($gid_fs>10000){$detailsData =$detailsArr[$gid_fs]; }else{$detailsData =current($detailsArr);}
                if ($flushWay=='ra' and $gid_fs>10000){ $gid=$gid_fs; }
                $showtypeH=$detailsData["hstrong"];

                if($detailsData['description'] == '角球' or $detailsData['description'] == '罚牌数' or $detailsData['description'] == '加时赛') { // 'ra686' 角球、加时赛  'ra'角球、罚牌
                    $gid = $detailsData['gid'];
                    if ($flushWay=='ra' and $gid_fs>10000){ $gid=$gid_fs; }
                    $row['MB_Team']=$detailsData['team_h'];
                    $row['TG_Team']=$detailsData['team_c'];
                    $row['MB_Ball']=$detailsData['score_h'];
                    $row['TG_Ball']=$detailsData['score_c'];
                }
                switch ($line){
                    case 204:	$keyY = 'sw_'.$wtype; $iorK="ior_".$rtype; break;
                    case 205:  if($rtype=='HRODD') {$keyY="sw_HREO"; $iorK = "ior_HREOO";break;}
                                if($rtype=='HREVEN'){$keyY="sw_HREO"; $iorK = "ior_HREOE";break;}
                    case 206:	if($rtype=='HRT0'){ $keyY="sw_HRT"; $iorK = "ior_HRT0";break;}
                                if($rtype=='HRT1'){ $keyY="sw_HRT"; $iorK = "ior_HRT1";break;}
                                if($rtype=='HRT2'){ $keyY="sw_HRT"; $iorK = "ior_HRT2";break;}
                                if($rtype=='HRTOV'){ $keyY="sw_HRT"; $iorK = "ior_HRTOV";break;}
                    case 31:    if($rtype=='HRMH'){ $keyY="sw_HRM"; $iorK = "ior_HRMH";break;}
                                if($rtype=='HRMC'){ $keyY="sw_HRM"; $iorK = "ior_HRMC";break;}
                                if($rtype=='HRMN'){ $keyY="sw_HRM"; $iorK = "ior_HRMN";break;}
                    default:	$keyY = 'sw_'.$wtype; break;
                }
                if($line==244){ $detailsData[$keyY]="Y"; }
                if ($line==205||$line==206){
                    if($detailsData[$keyY]=="Y" && $detailsData[$iorK]>0){
                        $ioradio_r_h = round_num($detailsData[$iorK]);
                        if(!$ioradio_r_h or $ioradio_r_h<=0){
                            $status='401.9';
                            $describe=$Order_This_match_is_closed_Please_try_again;
                            original_phone_request_response($status,$describe);

                        }
                    }
                }
                else{
                    if($detailsData[$keyY]=="Y" && $detailsData["ior_".$rtype]>0){
                        $ioradio_r_h = round_num($detailsData["ior_".$rtype]);
                        if(!$ioradio_r_h or $ioradio_r_h<=0){
                            $status='401.9';
                            $describe=$Order_This_match_is_closed_Please_try_again;
                            original_phone_request_response($status,$describe);

                        }
                    }
                }

                //更多玩法注入效验
                if(gameFtVerify($line,$wtype,$rtype)){
                    if($line==19){
                        $Sign = $detailsData['ratio_hre'];
                        $type = substr($rtype,-1,1);
                    }
                    if($line==20){
                        if($rtype=='HROUC'){
                            $m_place = 'O '.$detailsData['ratio_hrouo'];
                            if ($detailsData['ratio_hrouo']<=0){
                                $status='401.999';
                                $describe=$Order_Odd_changed_please_bet_again;
                                original_phone_request_response($status,$describe);
                            }
                        }
                        if($rtype=='HROUH'){
                            if ($detailsData['ratio_hrouu']<=0){
                                $status='401.9999';
                                $describe=$Order_Odd_changed_please_bet_again;
                                original_phone_request_response($status,$describe);
                            }
                            $m_place = 'U '.$detailsData['ratio_hrouu'];
                        }
                        $type = substr($rtype,-1,1);
                        $s_m_place = $w_m_place = $w_m_place_tw = $w_m_place_en =  $m_place;
                    }
                    if($line==31){ $type = substr($rtype,-1,1); }
                }else{
                    $status='401.9';
                    $describe="非法操作,请重新下注1!";
                    original_phone_request_response($status,$describe);
                }
            }
        }else{
            if(in_array($rtype,array('HRH1C0','HRH2C0','HRH0C1','HRH0C2','HRH2C1','HRH1C2','HRH3C0','HRH0C3','HRH3C1','HRH1C3','HRH3C2','HRH2C3','HRH4C0',
                'HRH0C4','HRH4C1','HRH1C4','HRH4C2','HRH2C4','HRH4C3','HRH3C4','HRH0C0','HRH2C2','HRH1C1','HRH3C3','HRH4C4','HROVH'))){
                $rtype=substr($rtype,1);
                if($rtype=="ROVH"){
                    $files = "RUP5";
                }else{
                    $files = str_replace('H','MB',$rtype);
                    $files = str_replace('C','TG',$files);
                }
                $files = "H".$files;
                $rbExpandRes = mysqli_query($dbLink,"select $files from ".DBPREFIX."match_sports_rb_expand where `MID`='$gid'");
                $rowExpandRes = mysqli_fetch_assoc($rbExpandRes);
                $ioradio_r_h = $rowExpandRes[$files];
                $rtype = "H".$rtype;
            }
        }

        //主客队伍名称
        $w_tg_team=$row['TG_Team'];
        $w_tg_team_tw=$row['TG_Team_tw'];
        $w_tg_team_en=$row['TG_Team_en'];

        $w_mb_team=$row['MB_Team'];
        $w_mb_team_tw=$row['MB_Team_tw'];
        $w_mb_team_en=$row['MB_Team_en'];

        $w_mb_team=filiter_team(trim($row['MB_Team']));
        $w_tg_team=filiter_team(trim($row['TG_Team']));
        $w_mb_team_tw=filiter_team(trim($row['MB_Team_tw']));
        $w_tg_team_tw=filiter_team(trim($row['TG_Team_tw']));
        $w_mb_team_en=filiter_team(trim($row['MB_Team_en']));
        $w_tg_team_en=filiter_team(trim($row['TG_Team_en']));

        //取出当前字库的主客队伍名称
        $s_mb_team=filiter_team($row[$mb_team]);
        $s_tg_team=filiter_team($row[$tg_team]);

        //下注时间
        $m_date=$row["M_Date"];
        if (!$showtypeH)$showtypeH=$row["ShowTypeHRB"];
        $bettime=date('Y-m-d H:i:s');
        $m_start=strtotime($row['M_Start']);
        $datetime=time();

        //联盟
        if ($row[$m_sleague]==''){
            $w_sleague=$row['M_League'];
            $w_sleague_tw=$row['M_League_tw'];
            $w_sleague_en=$row['M_League_en'];
            $s_sleague=$row[$m_league];
        }

        $inball=$row['MB_Ball'].":".$row['TG_Ball'];
        $inball1=$inball;
        $mb_ball = $row['MB_Ball'];
        $tg_ball = $row['TG_Ball'];

        //根据下注的类型进行处理：构建成新的数据格式，准备写入数据库
        switch ($line) {

            //-------------------------------- 足球滚球半场
            case 31:
                $bet_type='半场滚球独赢';
                $bet_type_tw="半場滾球獨贏";
                $bet_type_en="1st Half Running 1x2";
                $btype="- [$Order_1st_Half]";
                $caption=$Order_FT.$Order_1st_Half_Running_1_x_2_betting_order;
                switch ($type){
                    case "H":
                        $w_m_place=$w_mb_team;
                        $w_m_place_tw=$w_mb_team_tw;
                        $w_m_place_en=$w_mb_team_en;
                        $s_m_place=$row[$mb_team];
                        if(!$ioradio_r_h){$ioradio_r_h=$row["MB_Win_Rate_RB_H"];}
                        $w_m_rate=change_rate($open,$ioradio_r_h);
                        $turn_url="/app/member/FT_order/FT_order_hrm.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&odd_f_type=".$odd_f_type;
                        $w_gtype='VRMH';
                        break;
                    case "C":
                        $w_m_place=$w_tg_team;
                        $w_m_place_tw=$w_tg_team_tw;
                        $w_m_place_en=$w_tg_team_en;
                        $s_m_place=$row[$tg_team];
                        if(!$ioradio_r_h){$ioradio_r_h=$row["TG_Win_Rate_RB_H"];}
                        $w_m_rate=change_rate($open,$ioradio_r_h);
                        $turn_url="/app/member/FT_order/FT_order_hrm.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&odd_f_type=".$odd_f_type;
                        $w_gtype='VRMC';
                        break;
                    case "N":
                        $w_m_place="和局";
                        $w_m_place_tw="和局";
                        $w_m_place_en="Flat";
                        $s_m_place=$Draw;
                        if(!$ioradio_r_h){$ioradio_r_h=$row["M_Flat_Rate_RB_H"];}
                        $w_m_rate=change_rate($open,$ioradio_r_h);
                        $turn_url="/app/member/FT_order/FT_order_hrm.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&odd_f_type=".$odd_f_type;
                        $w_gtype='VRMN';
                        break;
                }
                $Sign="VS.";
                $grape=$type;
                $gwin=($w_m_rate-1)*$gold;
                $ptype='VRM';
                break;
            case 19:
                $bet_type='半场滚球让球';
                $bet_type_tw="半場滾球讓球";
                $bet_type_en="1st Half Running Ball";
                $btype="- [$Order_1st_Half]";
                $caption=$Order_FT.$Order_1st_Half_Running_Ball_betting_order;
                $rate=get_other_ioratio($odd_f_type,$row["MB_LetB_Rate_RB_H"],$row["TG_LetB_Rate_RB_H"],100);
                switch ($type){
                    case "H":
                        $w_m_place=$w_mb_team;
                        $w_m_place_tw=$w_mb_team_tw;
                        $w_m_place_en=$w_mb_team_en;
                        $s_m_place=$s_mb_team;
                        if(!$ioradio_r_h)$ioradio_r_h=$rate[0];
                        if ($flushWay=='ra'){
                            $w_m_rate=change_rate($open,$ioradio_r_h);
                        }else{
                            $w_m_rate = round_num($ioradio_r_h);
                        }
                        $turn_url="/app/member/FT_order/FT_order_hre.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&strong=".$strong."&odd_f_type=".$odd_f_type;
                        $w_gtype='VRRH';
                        break;
                    case "C":
                        $w_m_place=$w_tg_team;
                        $w_m_place_tw=$w_tg_team_tw;
                        $w_m_place_en=$w_tg_team_en;
                        $s_m_place=$s_tg_team;
                        if(!$ioradio_r_h)$ioradio_r_h=$rate[1];
                        if ($flushWay=='ra'){
                            $w_m_rate=change_rate($open,$ioradio_r_h);
                        }else{
                            $w_m_rate = round_num($ioradio_r_h);
                        }
                        $turn_url="/app/member/FT_order/FT_order_hre.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&strong=".$strong."&odd_f_type=".$odd_f_type;
                        $w_gtype='VRRC';
                        break;
                }

                if(!$Sign and $Sign!='0'){$Sign=$row['M_LetB_RB_H'];}
                if ($Sign=='' || $showtypeH=='' || !$showtypeH){
                    $status='401.23';
                    $describe="让球参数异常，请刷新赛事~~";
                    original_phone_request_response($status,$describe);
                }
                $grape=$Sign;

                if (strtoupper($showtypeH)=="H"){
                    $l_team=$s_mb_team;
                    $r_team=$s_tg_team;
                    $w_l_team=$w_mb_team;
                    $w_l_team_tw=$w_mb_team_tw;
                    $w_l_team_en=$w_mb_team_en;
                    $w_r_team=$w_tg_team;
                    $w_r_team_tw=$w_tg_team_tw;
                    $w_r_team_en=$w_tg_team_en;
                    $inball=$row['MB_Ball'].":".$row['TG_Ball'];
                }else{
                    $r_team=$s_mb_team;
                    $l_team=$s_tg_team;
                    $w_r_team=$w_mb_team;
                    $w_r_team_tw=$w_mb_team_tw;
                    $w_r_team_en=$w_mb_team_en;
                    $w_l_team=$w_tg_team;
                    $w_l_team_tw=$w_tg_team_tw;
                    $w_l_team_en=$w_tg_team_en;
                    $inball=$row['TG_Ball'].":".$row['MB_Ball'];

                }
                $s_mb_team=$l_team;
                $s_tg_team=$r_team;
                $w_mb_team=$w_l_team;
                $w_mb_team_tw=$w_l_team_tw;
                $w_mb_team_en=$w_l_team_en;
                $w_tg_team=$w_r_team;
                $w_tg_team_tw=$w_r_team_tw;
                $w_tg_team_en=$w_r_team_en;
                if ($odd_f_type=='H'){
                    $gwin=($w_m_rate)*$gold;
                }else if ($odd_f_type=='M' or $odd_f_type=='I'){
                    if ($w_m_rate<0){
                        $gwin=$gold;
                    }else{
                        $gwin=($w_m_rate)*$gold;
                    }
                }else if ($odd_f_type=='E'){
                    $gwin=($w_m_rate-1)*$gold;
                }
                $ptype='VRE';
                break;
            case 20:
                $bet_type='半场滚球大小';
                $bet_type_tw="半場滾球大小";
                $bet_type_en="1st Half Running Over/Under";
                $btype="- [$Order_1st_Half]";
                $caption=$Order_FT.$Order_1st_Half_Running_Ball_Over_Under_betting_order;
                $rate=get_other_ioratio($odd_f_type,$row["MB_Dime_Rate_RB_H"],$row["TG_Dime_Rate_RB_H"],100);
                switch ($type){
                    case "C":
                        if (!$w_m_place)$w_m_place=$row["MB_Dime_RB_H"];
                        $w_m_place=str_replace('O','大 ',$w_m_place);
                        if (!$w_m_place_tw)$w_m_place_tw=$row["MB_Dime_RB_H"];
                        $w_m_place_tw=str_replace('O','大 ',$w_m_place_tw);
                        if (!$w_m_place_en)$w_m_place_en=$row["MB_Dime_RB_H"];
                        $w_m_place_en=str_replace('O','over ',$w_m_place_en);
                        if (!$m_place)$m_place=$row["MB_Dime_RB_H"];
                        if (!$s_m_place)$s_m_place=$row["MB_Dime_RB_H"];
                        if ($langx=="zh-cn"){
                            $s_m_place=str_replace('O','大 ',$s_m_place);
                        }else if ($langx=="zh-cn"){
                            $s_m_place=str_replace('O','大 ',$s_m_place);
                        }else if ($langx=="en-us" or $langx=='th-tis'){
                            $s_m_place=str_replace('O','over ',$s_m_place);
                        }
                        if ($flushWay=='ra'){
                            if(!$ioradio_r_h){$ioradio_r_h=$rate[0];}
                            $w_m_rate=change_rate($open,$ioradio_r_h);
                        }else{
                            if(!$ioradio_r_h) {
                                $ioradio_r_h = $row["MB_Dime_Rate_RB_H"];
                            }
                            $w_m_rate = round_num($ioradio_r_h);
                        }
                        $turn_url="/app/member/FT_order/FT_order_hrou.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&odd_f_type=".$odd_f_type;
                        $w_gtype='VROUH';
                        break;
                    case "H":
                        if (!$w_m_place)$w_m_place=$row["TG_Dime_RB_H"];
                        $w_m_place=str_replace('U','小 ',$w_m_place);
                        if (!$w_m_place_tw)$w_m_place_tw=$row["TG_Dime_RB_H"];
                        $w_m_place_tw=str_replace('U','小 ',$w_m_place_tw);
                        if (!$w_m_place_en)$w_m_place_en=$row["TG_Dime_RB_H"];
                        $w_m_place_en=str_replace('U','under ',$w_m_place_en);
                        if (!$m_place)$m_place=$row["TG_Dime_RB_H"];
                        if (!$s_m_place)$s_m_place=$row["TG_Dime_RB_H"];
                        if ($langx=="zh-cn"){
                            $s_m_place=str_replace('U','小 ',$s_m_place);
                        }else if ($langx=="zh-cn"){
                            $s_m_place=str_replace('U','小 ',$s_m_place);
                        }else if ($langx=="en-us" or $langx=='th-tis'){
                            $s_m_place=str_replace('U','under ',$s_m_place);
                        }
                        if ($flushWay=='ra'){
                            if(!$ioradio_r_h){$ioradio_r_h=$rate[1];}
                            $w_m_rate=change_rate($open,$ioradio_r_h);
                        }else{
                            if(!$ioradio_r_h) {
                                $ioradio_r_h = $row["TG_Dime_Rate_RB_H"];
                            }
                            $w_m_rate = round_num($ioradio_r_h);
                        }
                        $turn_url="/app/member/FT_order/FT_order_hrou.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&odd_f_type=".$odd_f_type;
                        $w_gtype='VROUC';
                        break;
                }

                $Sign="VS.";
                $grape=$m_place;
                if ($odd_f_type=='H'){
                    $gwin=($w_m_rate)*$gold;
                }else if ($odd_f_type=='M' or $odd_f_type=='I'){
                    if ($w_m_rate<0){
                        $gwin=$gold;
                    }else{
                        $gwin=($w_m_rate)*$gold;
                    }
                }else if ($odd_f_type=='E'){
                    $gwin=($w_m_rate-1)*$gold;
                }
                $ptype='VROU';
                break;
            case 204:
                $bet_type='滚球  半场 - 波胆';
                $bet_type_tw='滾球 半场 -波膽';
                $bet_type_en="Running Half Correct Score";
                $caption=$Order_FT.' '.$Running_Ball.' '.$Res_Half.' '.$Order_Correct_Score_betting_order;
                $w_m_rate=change_rate($open,$ioradio_r_h);
                if($rtype=="HROVH"){
                    $s_m_place=$Order_Other_Score;
                    $w_m_place='其它比分';
                    $w_m_place_tw='其它比分';
                    $w_m_place_en='Other Score';
                    $Sign="VS.";
                }else{
//                    $s_m_place=$_REQUEST['concede'];
//                    $w_m_place=$_REQUEST['concede'];
//                    $w_m_place_tw=$_REQUEST['concede'];
//                    $w_m_place_en=$_REQUEST['concede'];
                    $s_m_place = returnBoDanBetContent($rtype) ;
                    $w_m_place = returnBoDanBetContent($rtype) ;
                    $M_Sign=$rtype;
                    $M_Sign=str_replace("MB","",$M_Sign);
                    $M_Sign=str_replace("TG",":",$M_Sign);
                    $Sign=$M_Sign."";
                }
                $Sign="VS.";
                $grape=$rtype;
                $gwin=($w_m_rate-1)*$gold;
                $ptype='RPD';
                $w_gtype=$rtype;
                break;
            case 205:
                $turn_url="/app/member/FT_order/FT_order_rt.php?gid=".$gid."&uid=".$uid."&rtype=".$rtype."&wtype=".$wtype."&odd_f_type=".$odd_f_type."&langx=".$langx;
                $bet_type='半场滚球单双';
                $bet_type_tw="半場滾球單雙";
                $bet_type_en="Half_Running_Ball Odd/Even";
                $caption=$Order_FT." ".$Running_Ball.$Order_Odd_Even_betting_order;
                switch ($rtype){
                    case "RODD":
                        $w_m_place='单';
                        $w_m_place_tw='單';
                        $w_m_place_en='odd';
                        $s_m_place='('.$Order_Odd.')';
                        if($ioradio_r_h>0){
                            $w_m_rate=change_rate($open,$ioradio_r_h);
                        }else{
                            $ioradio_r_h=$row['S_Single_Rate_RB'];
                            $w_m_rate=change_rate($open,$row['S_Single_Rate_RB']);
                        }
                        break;
                    case "REVEN":
                        $w_m_place='双';
                        $w_m_place_tw='雙';
                        $w_m_place_en='even';
                        $s_m_place='('.$Order_Even.')';
                        if($ioradio_r_h>0){
                            $w_m_rate=change_rate($open,$ioradio_r_h);
                        }else{
                            $ioradio_r_h=$row['S_Double_Rate_RB'];
                            $w_m_rate=change_rate($open,$row['S_Double_Rate_RB']);
                        }
                        break;
                    case "HRODD":
                        $btype="- [$Order_1st_Half]";
                        $w_m_place='单';
                        $w_m_place_tw='單';
                        $w_m_place_en='odd';
                        $s_m_place='('.$Order_Odd.')';
                        $w_m_rate=change_rate($open,$ioradio_r_h);
                        break;
                    case "HREVEN":
                        $btype="- [$Order_1st_Half]";
                        $w_m_place='双';
                        $w_m_place_tw='雙';
                        $w_m_place_en='even';
                        $s_m_place='('.$Order_Even.')';
                        $w_m_rate=change_rate($open,$ioradio_r_h);
                        break;
                }

                $Sign="VS.";
                $gwin=($w_m_rate-1)*$gold;
                $ptype='EO';
                $w_gtype=$rtype;
                break;
            case 206:
                $turn_url="/app/member/FT_order/FT_order_rsingle.php?gid=".$gid."&uid=".$uid."&rtype=".$rtype."&wtype=".$wtype."&odd_f_type=".$odd_f_type."&langx=".$langx."&flag=".$_REQUEST['flag']."&id=".$gid;
                $bet_type='滚球  半场- 总进球数';
                $bet_type_tw="滚球 半场 - 總进球数";
                $bet_type_en="Running_Ball Half Total Count";
                $caption=$Order_FT."".$Running_Ball.$Order_Total_Goals_betting_order;
                switch ($rtype){
                    case "HRT0":
                        $btype="- [$Order_1st_Half]";
                        $w_m_place='0';
                        $w_m_place_tw='0';
                        $w_m_place_en='0';
                        $s_m_place='0';
                        $w_m_rate=change_rate($open,$ioradio_r_h);
                        break;
                    case "HRT1":
                        $btype="- [$Order_1st_Half]";
                        $w_m_place='1';
                        $w_m_place_tw='1';
                        $w_m_place_en='1';
                        $s_m_place='1';
                        $w_m_rate=change_rate($open,$ioradio_r_h);
                        break;
                    case "HRT2":
                        $btype="- [$Order_1st_Half]";
                        $w_m_place='2';
                        $w_m_place_tw='2';
                        $w_m_place_en='2';
                        $s_m_place='2';
                        $w_m_rate=change_rate($open,$ioradio_r_h);
                        break;
                    case "HRTOV":
                        $btype="- [$Order_1st_Half]";
                        $w_m_place='3或以上';
                        $w_m_place_tw='3或以上';
                        $w_m_place_en='3或以上';
                        $s_m_place='3或以上';
                        $w_m_rate=change_rate($open,$ioradio_r_h);
                        break;
                }
                $Sign="VS.";
                if($w_m_rate>1){
                    $gwin=($w_m_rate-1)*$gold;
                }else{
                    $gwin=($w_m_rate)*$gold;
                }
                $ptype='T';
                $grape=$rtype;
                $w_gtype=$rtype;
                break;

            case 244: // 半场-球队进球数-大小
                $turn_url="/app/member/FT_order/FT_order_rsingle.php?gid=".$gid."&uid=".$uid."&rtype=".$rtype."&wtype=".$wtype."&odd_f_type=".$odd_f_type."&langx=".$langx."&flag=".$_REQUEST['flag']."&id=".$gid;
               // var_dump($detailsData);
//                if($wtype == "ROUH"){ // 主队
//                    if($rtype=='ROUHO'||$rtype=='ROUHU'){ // 全场
//                        $bet_type='球队进球数'.' '.$w_mb_team.' -大/小';
//                        $bet_type_tw="球队进球数".' '.$w_mb_team_tw.' -大/小';
//                        $bet_type_en="Order_Team_Ball_In".' '.$w_mb_team_en.' -大/小';
//                        $caption=$Order_FT." ".$Running_Ball." ".$Order_Team_Ball_In.' '.$w_mb_team.' -大/小 '.$Order_betting_order;
//                    }else{ // 半场
//                        $bet_type='半场球队进球数'.' '.$w_mb_team.' -大/小';
//                        $bet_type_tw="半场球队进球数".' '.$w_mb_team_tw.' -大/小';
//                        $bet_type_en="Half_Order_Team_Ball_In".' '.$w_mb_team_en.' -大/小';
//                        $caption=$Order_FT." ".$Running_Ball." 半场  ".$Order_Team_Ball_In.' '.$w_mb_team.' -大/小 '.$Order_betting_order;
//                    }
//                    $ptype='OUH';
//                }elseif($wtype == "ROUC"){ // 客队
//                    if($rtype=='ROUCO'||$rtype=='ROUCU'){
//                        $bet_type='球队进球数'.' '.$w_tg_team.' -大/小';
//                        $bet_type_tw="球队进球数".' '.$w_tg_team_tw.' -大/小';
//                        $bet_type_en="Order_Team_Ball_In".' '.$w_tg_team_en.' -大/小';
//                        $caption=$Order_FT." ".$Running_Ball." ".$Order_Team_Ball_In.' '.$w_tg_team.' -大/小 '.$Order_betting_order;
//                    }else{
//                        $bet_type='半场球队进球数'.' '.$w_tg_team.' -大/小';
//                        $bet_type_tw="半场球队进球数".' '.$w_tg_team_tw.' -大/小';
//                        $bet_type_en="Half_Order_Team_Ball_In".' '.$w_tg_team_en.' -大/小';
//                        $caption=$Order_FT." ".$Running_Ball." 半场    ".$Order_Team_Ball_In.' '.$w_tg_team.' -大/小 '.$Order_betting_order;
//                    }
//                    $ptype='OUC';
//                }


                $w_m_rate=change_rate($open,$ioradio_r_h);
                switch ($rtype){
                    case "ROUHO":
                        $w_m_place="大&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
                        $w_m_place_tw="大&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
                        $w_m_place_en="OVER&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
                        $s_m_place="大&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
                        break;
                    case "ROUHU":
                        $w_m_place="小&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
                        $w_m_place_tw="小&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
                        $w_m_place_en="UNDER&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
                        $s_m_place="小&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
                        break;
                    case "ROUCO":
                        $w_m_place="大&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
                        $w_m_place_tw="大&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
                        $w_m_place_en="OVER&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
                        $s_m_place="大&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
                        break;
                    case "ROUCU":
                        $w_m_place="小&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
                        $w_m_place_tw="小&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
                        $w_m_place_en="UNDER&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
                        $s_m_place="小&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
                        break;
                    case "HRUHO":
//                        echo $rtype.'==='.$Order_1st_Half;
//                        echo $detailsData['ratio_'.strtolower($rtype)];

                        $btype="-&nbsp;<font color=red><b>[$Order_1st_Half]</b></font>";
                        $w_m_place="大&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
                        $w_m_place_tw="大&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
                        $w_m_place_en="OVER&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
                        $s_m_place="大&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
                        $bet_type='半场球队进球数'.' '.$w_mb_team.' -大/小';
                        $bet_type_tw="半场球队进球数".' '.$w_mb_team_tw.' -大/小';
                        $bet_type_en="Half_Order_Team_Ball_In".' '.$w_mb_team_en.' -大/小';
                        $caption=$Order_FT." ".$Running_Ball." 半场  ".$Order_Team_Ball_In.' '.$w_mb_team.' -大/小 '.$Order_betting_order;

                        break;
                    case "HRUHU":
                        $btype="-&nbsp;<font color=red><b>[$Order_1st_Half]</b></font>";
                        $w_m_place="小&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
                        $w_m_place_tw="小&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
                        $w_m_place_en="UNDER&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
                        $s_m_place="小&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
                        $bet_type='半场球队进球数'.' '.$w_mb_team.' -大/小';
                        $bet_type_tw="半场球队进球数".' '.$w_mb_team_tw.' -大/小';
                        $bet_type_en="Half_Order_Team_Ball_In".' '.$w_mb_team_en.' -大/小';
                        $caption=$Order_FT." ".$Running_Ball." 半场  ".$Order_Team_Ball_In.' '.$w_mb_team.' -大/小 '.$Order_betting_order;
                        break;
                    case "HRUCO":
                        $btype="-&nbsp;<font color=red><b>[$Order_1st_Half]</b></font>";
                        $w_m_place="大&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
                        $w_m_place_tw="大&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
                        $w_m_place_en="OVER&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
                        $s_m_place="大&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
                        $bet_type='半场球队进球数'.' '.$w_tg_team.' -大/小';
                        $bet_type_tw="半场球队进球数".' '.$w_tg_team_tw.' -大/小';
                        $bet_type_en="Half_Order_Team_Ball_In".' '.$w_tg_team_en.' -大/小';
                        $caption=$Order_FT." ".$Running_Ball." 半场    ".$Order_Team_Ball_In.' '.$w_tg_team.' -大/小 '.$Order_betting_order;
                        break;
                    case "HRUCU":
                        $btype="-&nbsp;<font color=red><b>[$Order_1st_Half]</b></font>";
                        $w_m_place="小&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
                        $w_m_place_tw="小&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
                        $w_m_place_en="UNDER&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
                        $s_m_place="小&nbsp;".$detailsData['ratio_'.strtolower($rtype)];
                        $bet_type='半场球队进球数'.' '.$w_tg_team.' -大/小';
                        $bet_type_tw="半场球队进球数".' '.$w_tg_team_tw.' -大/小';
                        $bet_type_en="Half_Order_Team_Ball_In".' '.$w_tg_team_en.' -大/小';
                        $caption=$Order_FT." ".$Running_Ball." 半场    ".$Order_Team_Ball_In.' '.$w_tg_team.' -大/小 '.$Order_betting_order;
                        break;
                }
                $Sign="VS.";
                $gwin=($w_m_rate)*$gold;
                $grape = $detailsData['ratio_'.strtolower($rtype)];
                $w_gtype=$rtype;
                break;
        }

        if(in_array($line,array(154,244)) && (trim($grape) == "" || trim($grape) <= 0) ){
            $status='401.30';
            $describe="大小球数参数异常,请刷新赛事!";
            original_phone_request_response($status,$describe);

        }

        if ($gold<10){
            $status='401.11';
            $describe="金额最低不能小于10元~~";
            original_phone_request_response($status,$describe);

        }

        if (($w_m_rate != $ioradio_r_h) || $w_m_rate<='0.01') {

            $status='401.13';
            $describe="赔率不一致，请更新赔率后下注~~";
            original_phone_request_response($status,$describe);

        }

        if(!isset($_REQUEST['autoOdd']) || $_REQUEST['autoOdd']!="Y"){
            if($w_m_rate!=$_REQUEST['ioradio_r_h']){

                $status='401.14';
                $describe="赔率不一致，请更新赔率后下注~~";
                original_phone_request_response($status,$describe);

            }
        }

        if(strlen($w_m_rate)==0){
            $status='401.15';
            $describe=$Order_This_match_is_closed_Please_try_again;
            original_phone_request_response($status,$describe);

        }

        if ($s_m_place=='' or $w_m_place==''){
            $status='401.16';
            $describe=$Order_This_match_is_closed_Please_try_again;
            original_phone_request_response($status,$describe);

        }

        $oddstype=$odd_f_type;
        $bottom1="&nbsp;-&nbsp;<font color=#666666>[上半]</font>";
        $bottom1_tw="&nbsp;-&nbsp;<font color=#666666>[上半]</font>";
        $bottom1_en="&nbsp;-&nbsp;</font><font color=#666666>[1st Half]</font>";

        $w_mb_mid=$row['MB_MID'];
        $w_tg_mid=$row['TG_MID'];

        $lines=$row['M_League'].' '.$detailsData['description']."<br>[".$row['MB_MID'].']vs['.$row['TG_MID']."]<br>".$w_mb_team."&nbsp;&nbsp;<FONT COLOR=#0000BB><b>".$Sign."</b></FONT>&nbsp;&nbsp;".$w_tg_team."&nbsp;&nbsp;<FONT color=red><b>$inball</b></FONT><br>";
        $lines=$lines."<FONT color=#cc0000>$w_m_place</FONT>".$bottom1."&nbsp;@&nbsp;<FONT color=#cc0000><b>".$w_m_rate."</b></FONT>";

        $lines_tw=$row['M_League_tw'].' '.$detailsData['description']."<br>[".$row['MB_MID'].']vs['.$row['TG_MID']."]<br>".$w_mb_team_tw."&nbsp;&nbsp;<FONT COLOR=#0000BB><b>".$Sign."</b></FONT>&nbsp;&nbsp;".$w_tg_team_tw."&nbsp;&nbsp;<FONT color=red><b>$inball</b></FONT><br>";
        $lines_tw=$lines_tw."<FONT color=#cc0000>$w_m_place_tw</FONT>".$bottom1_tw."&nbsp;@&nbsp;<FONT color=#cc0000><b>".$w_m_rate."</b></FONT>";

        $lines_en=$row['M_League_en'].' '.$detailsData['description']."<br>[".$row['MB_MID'].']vs['.$row['TG_MID']."]<br>".$w_mb_team_en."&nbsp;&nbsp;<FONT COLOR=#0000BB><b>".$Sign."</b></FONT>&nbsp;&nbsp;".$w_tg_team_en."&nbsp;&nbsp;<FONT color=red><b>$inball</b></FONT><br>";
        $lines_en=$lines_en."<FONT color=#cc0000>$w_m_place_en</FONT>".$bottom1_en."&nbsp;@&nbsp;<FONT color=#cc0000><b>".$w_m_rate."</b></FONT>";

        $gtype = 'FT';
        $ip_addr = get_ip();

        $psql = "select A_Point,B_Point,C_Point,D_Point from ".DBPREFIX."web_agents_data where UserName='$agents'";
        $result = mysqli_query($dbMasterLink,$psql);
        $prow = mysqli_fetch_assoc($result);
        $a_point=$prow['A_Point']+0;
        $b_point=$prow['B_Point']+0;
        $c_point=$prow['C_Point']+0;
        $d_point=$prow['D_Point']+0;

        $showVoucher= show_voucher($wtype);
        //判断终端类型
        if($_REQUEST['appRefer']==13 || $_REQUEST['appRefer']==14) {
            $playSource=$_REQUEST['appRefer'];
        }
        else{
            if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')||strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')){
                $playSource=3;
            }else if(strpos($_SERVER['HTTP_USER_AGENT'], 'Android')){
                $playSource=4;
            }else{
                $playSource=5;
            }
        }

        $begin = mysqli_query($dbMasterLink,"start transaction");
        $lockResult = mysqli_query($dbMasterLink,"select Money from ".DBPREFIX.MEMBERTABLE." where ID = ".$memid." for update");
        if($begin && $lockResult) {
            $checkRow = mysqli_fetch_assoc($lockResult);
            $HMoney = $Money = $checkRow['Money'];
            $havemoney = $HMoney - $gold;
            if ($havemoney < 0 || $gold < 0 || $HMoney < 0) {
                mysqli_query($dbMasterLink, "ROLLBACK");

                $status = '401.18';
                $describe = "下注金額不可大於信用額度。" . rand(1, 199);
                original_phone_request_response($status, $describe);

            }

            $sql = "INSERT INTO ".DBPREFIX."web_report_data	(userid,Glost,playSource,testflag,QQ83068506,danger,MID,Active,orderNo,LineType,Mtype,M_Date,BetTime,BetScore,Middle,Middle_tw,Middle_en,BetType,BetType_tw,BetType_en,M_Place,M_Rate,M_Name,Gwin,OpenType,OddsType,ShowType,Agents,World,Corprator,Super,Admin,A_Point,B_Point,C_Point,D_Point,BetIP,Ptype,Gtype,CurType,Ratio,MB_MID,TG_MID,Pay_Type,Orderby,MB_Ball,TG_Ball) values ($memid,$Money,$playSource,$test_flag,'$inball1','1','$gid','1','$showVoucher','$line','$w_gtype','$m_date','$bettime','$gold','$lines','$lines_tw','$lines_en','$bet_type','$bet_type_tw','$bet_type_en','$grape','$w_m_rate','$memname','$gwin','$open','$oddstype','$showtypeH','$agents','$world','$corprator','$super','$admin','$a_point','$b_point','$c_point','$d_point','$ip_addr','$ptype','$gtype','$w_current','$w_ratio','$w_mb_mid','$w_tg_mid','$pay_type','$order','$mb_ball','$tg_ball')";

            $insertBet=mysqli_query($dbMasterLink,$sql);
            if($insertBet){
                $lastId=mysqli_insert_id($dbMasterLink);
                $moneyLogRes=addAccountRecords(array($memid,$memname,$test_flag,$Money,$gold*-1,$havemoney,1,$playSource,$lastId,$gtype."投注".$w_gtype));
                if($moneyLogRes){
                    $sql1 = "update ".DBPREFIX.MEMBERTABLE." set Money=".$havemoney." , Online=1 , OnlineTime=now() where ID=".$memid;
                    $updateMoney=mysqli_query($dbMasterLink,$sql1);
                    if($updateMoney){
                        mysqli_query($dbMasterLink,"COMMIT");
                    }else{
                        mysqli_query($dbMasterLink,"ROLLBACK");

                        $status='401.19';
                        $describe="操作失败!!" . rand(1, 199);
                        original_phone_request_response($status,$describe);

                    }
                }else{
                    mysqli_query($dbMasterLink,"ROLLBACK");

                    $status='401.20';
                    $describe="操作失败2!!" . rand(1, 199);
                    original_phone_request_response($status,$describe);

                }
            }else{
                mysqli_query($dbMasterLink,"ROLLBACK");

                $status='401.21';
                $describe="操作失败!" . rand(1, 199);
                original_phone_request_response($status,$describe);

            }

        }else{
            mysqli_query($dbMasterLink,"ROLLBACK");

            $status='401.22';
            $describe="操作失败0!" . rand(1, 199);
            original_phone_request_response($status,$describe);

        }

    }
}

// 确定交易生成图片开关
if(GENERATE_IMA_SWITCH) {
    // 需要参数
    $data = array(
        'caption' => $caption, //交易单标题
        'Order_Bet_success' => $Order_Bet_success, //交易成功单号
        'showVoucher' => $showVoucher, //单号
        's_sleague' => $s_sleague.' '.$detailsData['description'],  //联盟处理:联盟样式和显示的样式
        'btype' => $btype, // 在联赛名称后面显示
        'M_Date' => date('m-d',strtotime($row["M_Date"])), //日期
        'Sign' => $Sign, // 让球数
        's_mb_team' => $s_mb_team,   // 主队
        's_tg_team' => $s_tg_team,  // 客队
        's_m_place' => $s_m_place,  // 选择所属队
        'w_m_rate' => $w_m_rate,  // 赔率
        'gold' => $gold, // 下注金额
        'playSource' => $playSource,  // 投注来源:0未知,1pc旧版,2pc新版,3苹果,4安卓，13原生苹果,14原生安卓
        'gwin' => $gwin, // 可赢金额
        'havemoney' => $havemoney, // 账户余额
        'userid' => $memid,
        'inball' => $inball, // 滚球当前比分
    );

    $redisObj = new Ciredis();
    $redisObj->setOne($showVoucher,serialize($data));
    $redisObj->pushMessage('general_order_image',$showVoucher);

}

$aData=[];
$aData[0]['caption'] = $caption;
$aData[0]['Order_Bet_success'] = $Order_Bet_success;
$aData[0]['order'] = $showVoucher;
$aData[0]['s_sleague'] = $s_sleague.' '.$detailsData['description'];
$aData[0]['btype'] = $btype?$btype:'';
$aData[0]['M_Date'] = date('m-d',strtotime($row["M_Date"]));
$aData[0]['s_mb_team'] = $s_mb_team;
$aData[0]['Sign'] = $Sign;
$aData[0]['s_tg_team'] = $s_tg_team;
$aData[0]['s_m_place'] = $s_m_place;
$aData[0]['w_m_rate'] = $w_m_rate;
$aData[0]['gold'] = $gold; // 交易金额
$aData[0]['order_bet_amount'] = $gwin; // 可赢金额
$aData[0]['havemoney'] = $havemoney; // 账户余额
$aData[0]['inball'] = $inball; // 滚球当前比分

$status = '200';
$describe = '投注成功';
original_phone_request_response($status,$describe,$aData);
