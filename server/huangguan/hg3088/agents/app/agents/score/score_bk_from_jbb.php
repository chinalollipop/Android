<?php

if(php_sapi_name() == "cli") {
    define("CONFIG_DIR", dirname(dirname(__FILE__)));
    require(CONFIG_DIR."/include/config.inc.php");
    require(CONFIG_DIR."/include/curl_http.php");
    require(CONFIG_DIR."/include/traditional.zh-cn.inc.php");
    require_once(CONFIG_DIR."/include/address.mem.php");
}else{
    require ("../include/config.inc.php");
    require ('../include/curl_http.php');
    require ("../include/traditional.zh-cn.inc.php");
    require_once("../include/address.mem.php");
    /*判断IP是否在白名单*/
    if(CHECK_IP_SWITCH) {
        if(!checkip()) {
            exit('登录失败!!\\n未被授权访问的IP!!');
        }
    }
}


$redisObj = new Ciredis();
$list_date=date('Y-m-d',time());

$curl = new Curl_HTTP_Client();
$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
//$html_data=$curl->fetch_url("https://xj-sbr.prdasbbwla1.com/zh-cn/info-centre/sportsbook-info/results/2/normal/1?opcode=KZhing&tzoff=480&theme=KZing");
$html_data=$curl->fetch_url("https://sb-results.188sbk.com/zh-cn/info-centre/sportsbook-info/results/2/normal/1?tzoff=-240");
$html_data = mb_convert_encoding($html_data, 'utf-8', 'GBK,UTF-8,ASCII');
preg_match("/<body[^>]*?>(.*\s*?)<\/body>/is",$html_data,$matches);
$matches = explode('<div class="content data-grid hidden s2">',$matches[0]);
$matches = explode('<div id="dd-comp-menu">',$matches[1]);
$matches = rtrim(trim($matches[0]),'</div>');
$data = get_content_deal($matches);
$data = explode('<div class=rt-l-bar sportHasQuater id=cmp-', $data);
unset($data[0]);
$data = array_values($data);

$res = [];
$a = array(
    "</span>",
    "</div>",
    "\r\n",
    "年",
    "月",
    "日",
);
$b = array(
    "",
    "",
    "",
    "-",
    "-",
    "",
);
foreach ($data as $k => $v){

    $item = explode('<div class=rt-0 >',$v); // 盘口列表
    $sM_League = explode('</span><span class=qt1-txt>上半场</span>', $item[0])[0]; // 从数组$item第一个里面去除联赛名称
    $sM_League = explode('>',$sM_League)[1];
    $sM_League = tradition2simple($sM_League);
    array_shift($item);// 删除数组第一个元素

    foreach ($item as $k2 => $v2){
        $res[$k][$k2]['M_League'] = $sM_League; // 联赛名称
        // 筛选取出每个盘口的主队名称、客队名称，（第一节、第二节、第三节、第四节、加时赛）的主客队比分、全场的主客队比分
        $M_Start_others = explode('<div class=rt-event title=', $v2);
        $res[$k][$k2]['M_Start'] = date('Y-m-d H:i:s',strtotime(trim(str_replace($a,$b,explode("<span>", $M_Start_others[0])[1]))));
        if ($res[$k][$k2]['M_Start']<date('Y-m-d 00:00')) { unset($res[$k][$k2]); continue;}
        $aMB_TG_team_others = explode('<span class=pt >',$M_Start_others[1]);
        array_shift($aMB_TG_team_others); // 删除数组第一个元素
        $res[$k][$k2]['MB_Team']=str_replace('</span><span class=vs>v</span>','',$aMB_TG_team_others[0]);
        $res[$k][$k2]['MB_Team']=tradition2simple(trim($res[$k][$k2]['MB_Team']));
        $aTG_team_score = explode('</span>',$aMB_TG_team_others[1]);
        $res[$k][$k2]['TG_Team']=tradition2simple(trim($aTG_team_score[0]));
        $score = explode('<tbody>',$aMB_TG_team_others[1]);
        $sqt1_qt2_qft=str_replace('</div>','',$score[0]);
        $aqt1_qt2_qft=explode('<div class=rt-q',$sqt1_qt2_qft); // 从这里面取出 上半场、下半场、全场
        $divider=$score[2];

        $res[$k][$k2]['rt-qt1']=trim(str_replace('t1>','',$aqt1_qt2_qft[1])); // 上半场
        $res[$k][$k2]['rt-qt2']=trim(str_replace('t2>','',$aqt1_qt2_qft[2])); // 下半场
        $qft = explode('<div class=rt-sub rt-data-hide>',$aqt1_qt2_qft[3]);
        if (strpos($qft[0],'取消')){
            $res[$k][$k2]['rt-qft']='';
        }else{
            $res[$k][$k2]['rt-qft']=trim(str_replace('ft>','',$qft[0])); // 全场
        }
        $quaters = explode('<td class=r-odds>',str_replace('</td>','',$score[2]));
        array_shift($quaters);// 删除数组第一个元素

        $res[$k][$k2]['quater1']=trim($quaters[0]).'-'.trim($quaters[5]);
        $res[$k][$k2]['quater2']=trim($quaters[1]).'-'.trim($quaters[6]);
        $res[$k][$k2]['quater3']=trim($quaters[2]).'-'.trim($quaters[7]);
        $res[$k][$k2]['quater4']=trim($quaters[3]).'-'.trim($quaters[8]);
        // 篮球没有加时赛
//        $res[$k][$k2]['quater5']=trim($quaters[4]).':'.trim($quaters[9]);

    }
}

//print_r($res);die;

$datainfo = [];
// 移除数组的空值
foreach ($res as $k=> $v){
    if (!$v){
        unset( $res[$k] ); continue;
    }
    foreach ($v as $k2 => $v2) {
        // 没有比分的赛事从赛果中移除
        if ($v2['rt-qt1']=='' && $v2['rt-qt2']=='' && $v2['rt-qft']=='' && $v2['quater1']=='-' && $v2['quater2']=='-' && $v2['quater3']=='-' && $v2['quater4']=='-' ){
            continue;
        }

        $sql = "select MID,M_League,MB_Team,TG_Team,MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR,M_Start,Score_Source from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and  M_Date='".$list_date."' and M_Start='".$v2["M_Start"]."' and M_League='".$v2["M_League"]."' and MB_Team='".$v2["MB_Team"]."' and TG_Team='".$v2['TG_Team']."'";
        $result = mysqli_query($dbLink, $sql);
        $mcou = mysqli_num_rows($result);
        // 比分小于0，赛事异常（取消或者其他），跳过不更新
        if ($mcou > 0) {
            $row = mysqli_fetch_assoc($result);
            if ($row['MB_Inball'] < 0 && $row['TG_Inball'] < 0 && $row['MB_Inball_HR'] < 0 && $row['TG_Inball_HR'] < 0) continue;
            if( $row['Score_Source']>0){ continue; }//如果悠久乐、皇冠或管理员已经处理过，则跳出当前赛事，继续下一个赛事
            $datainfo[] = $v2;
        }
    }
}

//print_r($datainfo); die;

$cou = count($datainfo);
if($cou>0){//可以抓到数据
    log_note("score_bk_from_jbb"."\r\n");
    log_note("\r\n".date('Y-m-d H:i:s')."\r\n");
    for($i=0;$i<count($datainfo);$i++){
        log_note("------------------------------------------------------------------------------------\r\n");
        $data['quater1']=$data['quater2']=$data['quater3']=$data['quater4']=$data['rt-qt1']=$data['rt-qt2']=$data['rt-qft']='';
        $data = $datainfo[$i];

        $MB_Team=$data['MB_Team'];
        $TG_Team=$data['TG_Team'];
        $M_League=$data["M_League"];
        $mb_inball1 = trim(explode('-',$data['quater1'])[0]);	//主队第一节比分
        $tg_inball1 = trim(explode('-',$data['quater1'])[1]);	//客队第一节比分

        $mb_inball2 = trim(explode('-',$data['quater2'])[0]);	//主队第二节比分
        $tg_inball2 = trim(explode('-',$data['quater2'])[1]);	//客队第二节比分

        $mb_inball3 = trim(explode('-',$data['quater3'])[0]);	//主队第三节比分
        $tg_inball3 = trim(explode('-',$data['quater3'])[1]);	//客队第三节比分

        $mb_inball4 = trim(explode('-',$data['quater4'])[0]);	//主队第四节比分
        $tg_inball4 = trim(explode('-',$data['quater4'])[1]);	//客队第四节比分

        $mb_inball_hr = trim(explode('-',$data['rt-qt1'])[0]); //主队第上半场比分
        $tg_inball_hr = trim(explode('-',$data['rt-qt1'])[1]); //客队第上半场比分

        $mb_inball_xb = trim(explode('-', $data['rt-qt2'])[0]); //主队第下半场比分
        $tg_inball_xb = trim(explode('-', $data['rt-qt2'])[1]); //客队第下半场比分

        $mb_inball = trim(explode('-', $data['rt-qft'])[0]); //主队全场比分
        $tg_inball = trim(explode('-', $data['rt-qft'])[1]); //客队全场比分

        $sqlq="select MID,MB_Inball from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK'  and M_League='$M_League' and  TG_Team='$TG_Team' and MB_Team='$MB_Team' and M_Date='$list_date' and M_Start='".$data["M_Start"]."'";
        $resultq = mysqli_query($dbLink, $sqlq);
        while($rowq = mysqli_fetch_assoc($resultq)){
            $mids=$rowq["MID"];
            //第一节
            $mid3=$mids+3; // +3 是本场比赛第一节
            $sql="select MID,MB_Inball,Score_Source from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and MID=".(int)$mid3." and M_Date='$list_date'";
            $result = mysqli_query($dbLink, $sql);
            $cou=mysqli_num_rows($result);
            $row = mysqli_fetch_assoc($result);
            if($cou==1){
                if( $row['Score_Source']>0){  }//如果悠久乐、皇冠或管理员已经处理过，则跳过当前盘口，继续下一个
                else {
//                    $mid = $row['MID'];
                    if ($mb_inball1 < 0 && $mb_inball1 != $row["MB_Inball"]) {
                        matchAbnormalDeal($dbLink, $dbMasterLink, $mid3, $mb_inball1, $tg_inball1, $mb_inball_hr, $tg_inball_hr, $list_date);
                    } else {
                        if ($mb_inball1=='' && $tg_inball1==''){ continue; }
                        $a_sql = "select MID,MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and MID='" . (int)$mid3 . "' and M_Date='$list_date'";
                        $a_result = mysqli_query($dbLink, $a_sql);
                        $a_row = mysqli_fetch_assoc($a_result);
                        $a = $a_row['MB_Inball'] . $a_row['TG_Inball'];
                        $b = trim($mb_inball1) . trim($tg_inball1);
                        if (($a != $b) and is_numeric($mb_inball1) and is_numeric($tg_inball1) and $mb_inball1>0 and $mb_inball1>0 and $tg_inball1>0) {
                            $check = 1;
                            $mysql = "update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='$mb_inball1',TG_Inball='$tg_inball1',TG_Inball_HR=0,MB_Inball_HR=0,Checked='$check',Score_Source=4  where Type='BK' and ($mb_inball1!=0 or $tg_inball1!=0) and M_Date='$list_date' and MID=" . (int)$mid3;
                            mysqli_query($dbMasterLink, $mysql) or die('abc1');
                            $redisObj->pushMessage('MatchScorefinishList', (int)$mid3);
                            log_note('$mid3='.$mid3."\r\n\r\n");
                            $m = $m + 1;
                        }
                    }
                }
            }

            //第二节
            $mid4=$mids+4;
            $sql="select MID,MB_Inball,Score_Source from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and MID=".(int)$mid4." and M_Date='$list_date'";
            $result = mysqli_query($dbLink, $sql);
            $cou=mysqli_num_rows($result);
            $row = mysqli_fetch_assoc($result);
            if ($cou==1){
                if( $row['Score_Source']>0){  }//如果悠久乐、皇冠或管理员已经处理过，则跳过当前盘口，继续下一个
                else {
                    if ($mb_inball2 < 0 && $mb_inball2 != $row["MB_Inball"]) {
                        matchAbnormalDeal($dbLink, $dbMasterLink, $mid4, $mb_inball2, $tg_inball2, $mb_inball_hr, $tg_inball_hr, $list_date);
                    } else {
                        if ($mb_inball2=='' && $tg_inball2==''){ continue; }
                        $a_sql = "select MID,MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and MID='" . (int)$mid4 . "' and M_Date='$list_date'";
                        $a_result = mysqli_query($dbLink, $a_sql);
                        $a_row = mysqli_fetch_assoc($a_result);
                        $a = $a_row['MB_Inball'] . $a_row['TG_Inball'];
                        $b = trim($mb_inball2) . trim($tg_inball2);
                        if (($a != $b) and is_numeric($mb_inball2) and is_numeric($tg_inball2) and $mb_inball2>2 and $tg_inball2>2) {
                            $check = 1;
                            $mysql = "update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='$mb_inball2',TG_Inball='$tg_inball2',TG_Inball_HR=0,MB_Inball_HR=0,Checked='$check',Score_Source=4  where Type='BK' and ($mb_inball2!=0 or $tg_inball2!=0) and M_Date='$list_date' and MID=" . (int)$mid4;
                            mysqli_query($dbMasterLink, $mysql) or die('abc2');
                            $redisObj->pushMessage('MatchScorefinishList', (int)$mid4);
                            log_note('$mid4='.$mid4."\r\n\r\n");
                            $m = $m + 1;
                        }
                    }
                }
            }


            //上半
            $mid1=$mids+1;
            $sql="select MID,MB_Inball,Score_Source from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and MID=".(int)$mid1." and M_Date='$list_date'";
            $result = mysqli_query($dbLink, $sql);
            $cou=mysqli_num_rows($result);
            $row = mysqli_fetch_assoc($result);
            if($cou==1){
                if( $row['Score_Source']>0){  }//如果悠久乐、皇冠或管理员已经处理过，则跳过当前盘口，继续下一个
                else {
                    if ($mb_inball_hr < 0 && $mb_inball_hr != $row["MB_Inball"]) {
                        matchAbnormalDeal($dbLink, $dbMasterLink, $mid1, $mb_inball_hr, $tg_inball_hr, $mb_inball_hr, $tg_inball_hr, $list_date);
                    } else {
                        if ($mb_inball_hr=='' && $tg_inball_hr==''){ continue; }
                        $a_sql = "select MID,MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and MID='" . (int)$mid1 . "' and M_Date='$list_date'";
                        $a_result = mysqli_query($dbLink, $a_sql);
                        $a_row = mysqli_fetch_assoc($a_result);
                        $a = $a_row['MB_Inball'] . $a_row['TG_Inball'];
                        $b = trim($mb_inball_hr) . trim($tg_inball_hr);
                        /*var_dump($a);
                        var_dump($b);echo "<br/>";*/
                        if (($a != $b) and is_numeric($mb_inball_hr) and is_numeric($tg_inball_hr) and $mb_inball_hr>0 and $tg_inball_hr>0) {
                            $check = 1;
                            $mysql = "update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='$mb_inball_hr',TG_Inball='$tg_inball_hr',TG_Inball_HR=0,MB_Inball_HR=0,Checked='$check',Score_Source=4  where Type='BK' and ($mb_inball_hr!=0 or $tg_inball_hr!=0) and M_Date='$list_date' and MID=" . (int)$mid1;
                            mysqli_query($dbMasterLink, $mysql) or die('abchr');
                            $redisObj->pushMessage('MatchScorefinishList', (int)$mid1);
                            log_note('$mid1='.$mid1."\r\n\r\n");
                            $m = $m + 1;
                        }
                    }
                }
            }

            //第三节
            $mid5=$mids+5;
            $sql="select MID,MB_Inball,Score_Source from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and MID=".(int)$mid5." and M_Date='$list_date'";
            $result = mysqli_query($dbLink, $sql);
            $cou=mysqli_num_rows($result);
            $row = mysqli_fetch_assoc($result);
            if ($cou==1){
                if( $row['Score_Source']>0){  }//如果悠久乐、皇冠或管理员已经处理过，则跳过当前盘口，继续下一个
                else {
                    if ($mb_inball3 < 0 && $mb_inball3 != $row["MB_Inball"]) {
                        matchAbnormalDeal($dbLink, $dbMasterLink, $mid5, $mb_inball3, $tg_inball3, $mb_inball_hr, $tg_inball_hr, $list_date);
                    } else {
                        if ($mb_inball3=='' && $tg_inball3==''){ continue; }
                        $a_sql = "select MID,MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and MID='" . (int)$mid5 . "' and M_Date='$list_date'";
                        $a_result = mysqli_query($dbLink, $a_sql);
                        $a_row = mysqli_fetch_assoc($a_result);
                        $a = $a_row['MB_Inball'] . $a_row['TG_Inball'];
                        $b = trim($mb_inball3) . trim($tg_inball3);
                        if (($a != $b) and is_numeric($mb_inball3) and is_numeric($tg_inball3) and $mb_inball3>0 and $tg_inball3>0) {
                            $check = 1;
                            $mysql = "update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='$mb_inball3',TG_Inball='$tg_inball3',TG_Inball_HR=0,MB_Inball_HR=0,Checked='$check',Score_Source=4  where Type='BK' and ($mb_inball3!=0 or $tg_inball3!=0) and M_Date='$list_date' and MID=" . (int)$mid5;
                            mysqli_query($dbMasterLink, $mysql) or die('abc3');
                            $redisObj->pushMessage('MatchScorefinishList', (int)$mid5);
                            log_note('$mid5='.$mid5."\r\n\r\n");
                            $m = $m + 1;
                        }
                    }
                }
            }

            //第四节
            $mid6=$mids+6;
            $sql="select MID,MB_Inball,Score_Source from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and MID=".(int)$mid6." and M_Date='$list_date'";
            $result = mysqli_query($dbLink, $sql);
            $cou=mysqli_num_rows($result);
            $row = mysqli_fetch_assoc($result);
            if ($cou==1){
                if( $row['Score_Source']>0){  }//如果悠久乐、皇冠或管理员已经处理过，则跳过当前盘口，继续下一个
                else {
                    if ($mb_inball4 < 0 && $mb_inball4 != $row["MB_Inball"]) {
                        matchAbnormalDeal($dbLink, $dbMasterLink, $mid6, $mb_inball4, $tg_inball4, $mb_inball_hr, $tg_inball_hr, $list_date);
                    } else {
                        if ($mb_inball4=='' && $tg_inball4==''){ continue; }
                        $a_sql = "select MID,MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and MID='" . (int)$mid6 . "' and M_Date='$list_date'";
                        $a_result = mysqli_query($dbLink, $a_sql);
                        $a_row = mysqli_fetch_assoc($a_result);
                        $a = $a_row['MB_Inball'] . $a_row['TG_Inball'];
                        $b = trim($mb_inball4) . trim($tg_inball4);
                        /*var_dump($a);
                        var_dump($b);echo "<br/>";*/
                        if (($a != $b) and is_numeric($mb_inball4) and is_numeric($tg_inball4) and $mb_inball4>0 and $tg_inball4>0) {
                            $check = 1;
                            $mysql = "update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='$mb_inball4',TG_Inball='$tg_inball4',TG_Inball_HR=0,MB_Inball_HR=0,Checked='$check',Score_Source=4  where Type='BK' and ($mb_inball4!=0 or $tg_inball4!=0) and M_Date='$list_date' and MID=" . (int)$mid6;
//                                    echo $mysql;
                            mysqli_query($dbMasterLink, $mysql) or die('abc44');
                            $redisObj->pushMessage('MatchScorefinishList', (int)$mid6);
                            log_note('$mid6='.$mid6."\r\n\r\n");
                            $m = $m + 1;
                        }
                    }
                }
            }


            //下半
            $mid2=$mids+2;
            $sql="select MID,MB_Inball,Score_Source from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and MID=".(int)$mid2." and M_Date='$list_date'";
            $result = mysqli_query($dbLink, $sql);
            $cou=mysqli_num_rows($result);
            $row = mysqli_fetch_assoc($result);
            if ($cou==1){
                if( $row['Score_Source']>0){  }//如果悠久乐、皇冠或管理员已经处理过，则跳过当前盘口，继续下一个
                else {
                    if ($mb_inball_xb < 0 && $mb_inball_xb != $row["MB_Inball"]) {
                        matchAbnormalDeal($dbLink, $dbMasterLink, $mid2, $mb_inball_xb, $tg_inball_xb, $mb_inball_hr, $tg_inball_hr, $list_date);
                    } else {
                        if ($mb_inball_xb=='' && $tg_inball_xb==''){ continue; }
                        $a_sql = "select MID,MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and MID='" . (int)$mid2 . "' and M_Date='$list_date'";
                        $a_result = mysqli_query($dbLink, $a_sql);
                        $a_row = mysqli_fetch_assoc($a_result);
                        $a = $a_row['MB_Inball'] . $a_row['TG_Inball'];
                        $b = trim($mb_inball_xb) . trim($tg_inball_xb);
                        /*var_dump($a);
                        var_dump($b);echo "<br/>";*/
                        if (($a != $b) and is_numeric($mb_inball_xb) and is_numeric($tg_inball_xb) and $mb_inball_xb>0 and $tg_inball_xb>0) {
                            $check = 1;
                            $mysql = "update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='$mb_inball_xb',TG_Inball='$tg_inball_xb',TG_Inball_HR=0,MB_Inball_HR=0,Checked='$check',Score_Source=4  where Type='BK' and ($mb_inball_xb!=0 or $tg_inball_xb!=0) and M_Date='$list_date' and MID=" . (int)$mid2;
                            mysqli_query($dbMasterLink, $mysql) or die('abcxb');
                            $redisObj->pushMessage('MatchScorefinishList', (int)$mid2);
                            log_note('$mid2='.$mid2."\r\n\r\n");
                            $m = $m + 1;
                        }
                    }
                }
            }

            //全场
            $sql = "select MID,MB_Inball,Score_Source from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and MID=" . (int)$mids . " and M_Date='$list_date'";
            $result = mysqli_query($dbLink, $sql);
            $cou = mysqli_num_rows($result);
            $row = mysqli_fetch_assoc($result);
            if ($cou == 1) {
                if ($row['Score_Source'] > 0) {
                }//如果金宝博、悠久乐、皇冠或管理员已经处理过，则跳过当前盘口，继续下一个
                else {
                    if ($mb_inball < 0 && $mb_inball != $row["MB_Inball"]) {
                        matchAbnormalDeal($dbLink, $dbMasterLink, $mids, $mb_inball, $tg_inball, $mb_inball_hr, $tg_inball_hr, $list_date);
                    } else {
                        if ($mb_inball=='' && $tg_inball=='' ){ continue; }
                        $a_sql = "select MID,MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK'  and M_League='$M_League' and  TG_Team='$TG_Team' and MB_Team='$MB_Team' and M_Date='$list_date' and M_Start='".$data["M_Start"]."'";
                        $a_result = mysqli_query($dbLink, $a_sql);
                        while ($a_row = mysqli_fetch_assoc($a_result)) {
                            $a = $a_row['MB_Inball'] . $a_row['TG_Inball'];
                            $b = trim($mb_inball) . trim($tg_inball);
                            if (($a != $b) and is_numeric($mb_inball) and is_numeric($tg_inball) and $mb_inball>0 and $tg_inball>0) {
                                $check = 1;
                                $mysql = "update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='$mb_inball',TG_Inball='$tg_inball',MB_Inball_HR='$mb_inball_hr',TG_Inball_HR='$tg_inball_hr',Checked='$check',Score_Source=4  where ($mb_inball!=0 or $tg_inball!=0) and Type='BK' and MID = ".$a_row['MID'];
                                mysqli_query($dbMasterLink, $mysql) or die('3abc');
                                $redisObj->pushMessage('MatchScorefinishList', (int)$a_row['MID']);
                                log_note('$mid='.$a_row['MID']."\r\n\r\n");
                                $m = $m + 1;
                            }
                        }
                    }
                }
            }

        }


    }

}

function log_note($info) {
    //ob_start(); //打开缓冲区
    //var_dump($database);
    //$info=ob_get_contents(); //得到缓冲区的内容并且赋值给$info
    //ob_clean(); //关闭缓冲区
    //log_note($info."\r\n");
    $dir = dirname(__FILE__);
    $file = $dir."/score_bk_from_jbb_paijiang_note".date("ymd").".txt";
    $handle = fopen($file, 'a+');
    fwrite($handle, $info);
    fclose($handle);
}

function get_content_deal($html_data){
    $a = array(
        '"',
        "<span class=HT-txt>上半场</span><span class=FT-txt>全场</span>",
        '&#x200b;',
        '<span class=btn-toggle expand><span class=toggle-arrow></span></span><span class=comp-txt>',
        'rt-1',
        " class='btn-toggle'",
        '<colgroup>',
        '<col width=66/>',
        '<col width=300 />',
        '<col width=116 />',
        '<col width=117 />',
        '<col width=100 />',
        '</colgroup>',
        '<!--Fix IE 9 first row with colspan bug: clarify the col-->',
        '<tbody class=empty-row>',
        '<tr>',
        '<td></td>',
        '<td></td>',
        '<td></td>',
        '<td></td>',
        '</tr>',
        '</tbody>',
    );
    $b = array(
        "",
        "",
        "",
        "",
        'rt-0',
        "",
        "",
        "",
        "",
        "",
        "",
        "",
        "",
        "",
        "",
        "",
        "",
        "",
        "",
        "",
        "",
        "",
    );
	$msg = str_replace($a,$b,$html_data);
	return $msg;
}

echo '<br>目前比分以结算出'.$m;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>金宝博篮球接比分</title>
<link href="/style/agents/control_down.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css">
</head>
<script> 
var limit="<?php echo $settime?>" 
if (document.images){ 
	var parselimit=limit
} 
function beginrefresh(){ 
	if (!document.images) 
		return 
	if (parselimit==1) 
		window.location.reload() 
	else{ 
		parselimit-=1 
		curmin=Math.floor(parselimit) 
		if (curmin!=0) 
			curtime=curmin+"秒后自动本页获取最新数据！" 
		else 
			curtime=cursec+"秒后自动本页获取最新数据！" 
			timeinfo.innerText=curtime 
			setTimeout("beginrefresh()",1000) 
		} 
} 

window.onload=beginrefresh 

</script>
<body>
<table width="100" height="70" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="100" height="70" align="center"><br><?php echo $list_date?><br><br><span id="timeinfo"></span><br>
      <input type=button name=button value="足球更新" onClick="window.location.reload()"></td>
  </tr>
</table>
</body>
</html>
