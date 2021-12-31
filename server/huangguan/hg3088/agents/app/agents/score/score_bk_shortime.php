<?php

if(php_sapi_name() == "cli") {
    define("CONFIG_DIR", dirname(dirname(__FILE__)));
    define("COMMON_DIR", dirname(dirname(dirname(dirname(dirname(__FILE__))))));
    require(CONFIG_DIR."/include/config.inc.php");
    require_once(COMMON_DIR."/common/sportCenterData.php");
    require(CONFIG_DIR."/include/curl_http.php");

    require(CONFIG_DIR."/include/traditional.zh-cn.inc.php");
    require_once(CONFIG_DIR."/include/address.mem.php");
}else{
    require ("../include/config.inc.php");
    require_once("../../../../common/sportCenterData.php");
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
$mysql = "select * from ".DBPREFIX."web_system_data";
$result = mysqli_query($dbLink,$mysql);
$row = mysqli_fetch_assoc($result);

$settime=$row['udp_bk_score'];
$list_date=date('Y-m-d',time());
$m=0;
$langx="zh-cn";
$accoutArr = array();
$accoutArr=getFlushWaterAccount();//数组随机排序

$curl = new Curl_HTTP_Client();
//$curl->store_cookies("/tmp/cookies.txt");
//$dateCur = date('Y-m-d',time());
//$curl->set_cookie("gamePoint_21059363={$dateCur}%2A0%2A0; gamePoint_21059364={$dateCur}%2A0%2A0; gamePoint_21059365={$dateCur}%2A0%2A0; gamePoint_21059366={$dateCur}%2A2%2A0; gamePoint_21059367={$dateCur}%2A2%2A0; gamePoint_21059368={$dateCur}%2A2%2A0; gamePoint_21059369={$dateCur}%2A2%2A0;");
$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
foreach($accoutArr as $key=>$value){//在扩展表中获取账号重新刷水

    /**
     * 篮球比分
    http://205.201.4.177/app/member/account/result/result.php?
    game_type=BK&today=2019-02-15&uid=vg0w2jp74sm21059365l225327&langx=zh-cn

     * 小节比分
    http://205.201.4.177/app/member/account/result/BK_result_new.php?
    uid=vg0w2jp74sm21059365l225327&gtype=BK&game_id=2872151&langx=zh-cn
     */

//    $last_date = date('Y-m-d',strtotime("-1 day"));
//    $curl->set_referrer("".$value['Datasite']."/app/member/account/result/result.php?game_type=BK&list_date=".$last_date."&uid=".$value['Uid']."&langx=$langx");
    $curl->set_referrer("".$value['Datasite']."/app/member/account/index.php?uid=".$value['Uid']."&langx=$langx");
    $html_data=$curl->fetch_url("".$value['Datasite']."app/member/account/result/result.php?game_type=BK&today=".$list_date."&uid=".$value['Uid']."&langx=".$langx);
    $aMyleg = get_content_deal($html_data);
    $cou=sizeof($aMyleg);

    if ($cou>0){
        // curl 获取全部赛事的所有赛果
        foreach ($aMyleg as $k => $v){

            $html_data_section=$curl->fetch_url($value['Datasite']."/app/member/account/result/BK_result_new.php?uid=".$value['Uid']."&gtype=BK&game_id=".$v['mid']."&langx=$langx");
            preg_match_all('/<table[^>]*>(.*?) <\/table>/si',$html_data_section,$match);
            $table_data = $match[1][0];
            $table_array = explode('<tr',$table_data);
            $table_array = array_diff_key($table_array, [0,1,2,3]);
            $table_array = array_values($table_array);
            $data_section = array();
            for($i=0;$i<count($table_array);$i++) {
                $data_section[$i] = explode('</td>', $table_array[$i]);
                for ($j = 0; $j < count($data_section[$i]); $j++) {
                    $data_section[$i][$j] = preg_replace('/\s(?=\s)/', '', trim(strip_tags($data_section[$i][$j])));
                }
                array_pop($data_section[$i]);
            }
            $mid = '';
            $data_section_arr = array();
            foreach ($data_section as $k1 => $v1){
                $data_section_arr[$k1]['section'] = explode('STYLE}> ', $v1[0])[1];
                $leagueid_mid = explode('TR_', explode('{RESULT_', $v1[0])[0] )[1];
                $leagueid_mid = explode( '_', $leagueid_mid );
                $leagueid = $leagueid_mid[1];
                $mid = str_replace('"', '', $leagueid_mid[2]);
                $data_section_arr[$k1]['leagueid'] = $leagueid;
                $data_section_arr[$k1]['mid'] = $mid;
                $data_section_arr[$k1]['mb_score'] = $v1[1];
                $data_section_arr[$k1]['tg_score'] = $v1[2];
            }

            // 准备比分数据更新到赛事表
            $sql="select * from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and MID=".(int)$mid." and M_Date='$list_date'";
            $result = mysqli_query($dbLink, $sql);
            $cou=mysqli_num_rows($result);
            $row = mysqli_fetch_assoc($result);
            if($cou>0){
                $MB_Team=$row['MB_Team'];
                $TG_Team=$row['TG_Team'];
                $M_League=$row["M_League"];
                if($MB_Team<>"" or $TG_Team<>""){

                    //第1节比分
                    $mb_inball1=$data_section_arr[0]['mb_score'];
                    $tg_inball1=$data_section_arr[0]['tg_score'];

                    //第2节比分
                    $mb_inball2=$data_section_arr[1]['mb_score'];
                    $tg_inball2=$data_section_arr[1]['tg_score'];

                    //第3节比分
                    $mb_inball3=$data_section_arr[2]['mb_score'];
                    $tg_inball3=$data_section_arr[2]['tg_score'];

                    //第4节比分
                    $mb_inball4=$data_section_arr[3]['mb_score'];
                    $tg_inball4=$data_section_arr[3]['tg_score'];

                    //上半比分
                    $mb_inball_hr=$data_section_arr[4]['mb_score'];
                    $tg_inball_hr=$data_section_arr[4]['tg_score'];

                    //下半比分
                    $mb_inball_xb=$data_section_arr[5]['mb_score'];
                    $tg_inball_xb=$data_section_arr[5]['tg_score'];

                    // 加时比分

                    //全场比分
                    $mb_inball=$data_section_arr[7]['mb_score'];
                    $tg_inball=$data_section_arr[7]['tg_score'];

                    if(preg_match("/$Score2/is",$tg_inball)){
                        $mb_inball1='-2';
                        $tg_inball1='-2';
                        $mb_inball2='-2';
                        $tg_inball2='-2';
                        $mb_inball3='-2';
                        $tg_inball3='-2';
                        $mb_inball4='-2';
                        $tg_inball4='-2';
                        $mb_inball_hr='-2';
                        $tg_inball_hr='-2';
                        $mb_inball_xb='-2';
                        $tg_inball_xb='-2';
                        $mb_inball='-2';
                        $tg_inball='-2';
                    }

                    if ($tg_inball==$Score1){
                        $mb_inball1='-1';
                        $tg_inball1='-1';
                        $mb_inball2='-1';
                        $tg_inball2='-1';
                        $mb_inball3='-1';
                        $tg_inball3='-1';
                        $mb_inball4='-1';
                        $tg_inball4='-1';
                        $mb_inball_hr='-1';
                        $tg_inball_hr='-1';
                        $mb_inball_xb='-1';
                        $tg_inball_xb='-1';
                        $mb_inball='-1';
                        $tg_inball='-1';
                    }else if ($tg_inball==$Score2){
                        $mb_inball1='-2';
                        $tg_inball1='-2';
                        $mb_inball2='-2';
                        $tg_inball2='-2';
                        $mb_inball3='-2';
                        $tg_inball3='-2';
                        $mb_inball4='-2';
                        $tg_inball4='-2';
                        $mb_inball_hr='-2';
                        $tg_inball_hr='-2';
                        $mb_inball_xb='-2';
                        $tg_inball_xb='-2';
                        $mb_inball='-2';
                        $tg_inball='-2';
                    }else if ($tg_inball==$Score3){
                        $mb_inball1='-3';
                        $tg_inball1='-3';
                        $mb_inball2='-3';
                        $tg_inball2='-3';
                        $mb_inball3='-3';
                        $tg_inball3='-3';
                        $mb_inball4='-3';
                        $tg_inball4='-3';
                        $mb_inball_hr='-3';
                        $tg_inball_hr='-3';
                        $mb_inball_xb='-3';
                        $tg_inball_xb='-3';
                        $mb_inball='-3';
                        $tg_inball='-3';
                    }else if ($tg_inball==$Score4){
                        $mb_inball1='-4';
                        $tg_inball1='-4';
                        $mb_inball2='-4';
                        $tg_inball2='-4';
                        $mb_inball3='-4';
                        $tg_inball3='-4';
                        $mb_inball4='-4';
                        $tg_inball4='-4';
                        $mb_inball_hr='-4';
                        $tg_inball_hr='-4';
                        $mb_inball_xb='-4';
                        $tg_inball_xb='-4';
                        $mb_inball='-4';
                        $tg_inball='-4';
                    }else if ($tg_inball==$Score5){
                        $mb_inball1='-5';
                        $tg_inball1='-5';
                        $mb_inball2='-5';
                        $tg_inball2='-5';
                        $mb_inball3='-5';
                        $tg_inball3='-5';
                        $mb_inball4='-5';
                        $tg_inball4='-5';
                        $mb_inball_hr='-5';
                        $tg_inball_hr='-5';
                        $mb_inball_xb='-5';
                        $tg_inball_xb='-5';
                        $mb_inball='-5';
                        $tg_inball='-5';
                    }else if ($tg_inball==$Score6){
                        $mb_inball1='-6';
                        $tg_inball1='-6';
                        $mb_inball2='-6';
                        $tg_inball2='-6';
                        $mb_inball3='-6';
                        $tg_inball3='-6';
                        $mb_inball4='-6';
                        $tg_inball4='-6';
                        $mb_inball_hr='-6';
                        $tg_inball_hr='-6';
                        $mb_inball_xb='-6';
                        $tg_inball_xb='-6';
                        $mb_inball='-6';
                        $tg_inball='-6';
                    }else if ($tg_inball==$Score7){
                        $mb_inball1='-7';
                        $tg_inball1='-7';
                        $mb_inball2='-7';
                        $tg_inball2='-7';
                        $mb_inball3='-7';
                        $tg_inball3='-7';
                        $mb_inball4='-7';
                        $tg_inball4='-7';
                        $mb_inball_hr='-7';
                        $tg_inball_hr='-7';
                        $mb_inball_xb='-7';
                        $tg_inball_xb='-7';
                        $mb_inball='-7';
                        $tg_inball='-7';
                    }else if ($tg_inball==$Score8){
                        $mb_inball1='-8';
                        $tg_inball1='-8';
                        $mb_inball2='-8';
                        $tg_inball2='-8';
                        $mb_inball3='-8';
                        $tg_inball3='-8';
                        $mb_inball4='-8';
                        $tg_inball4='-8';
                        $mb_inball_hr='-8';
                        $tg_inball_hr='-8';
                        $mb_inball_xb='-8';
                        $tg_inball_xb='-8';
                        $mb_inball='-8';
                        $tg_inball='-8';
                    }else if ($tg_inball==$Score9){
                        $mb_inball1='-9';
                        $tg_inball1='-9';
                        $mb_inball2='-9';
                        $tg_inball2='-9';
                        $mb_inball3='-9';
                        $tg_inball3='-9';
                        $mb_inball4='-9';
                        $tg_inball4='-9';
                        $mb_inball_hr='-9';
                        $tg_inball_hr='-9';
                        $mb_inball_xb='-9';
                        $tg_inball_xb='-9';
                        $mb_inball='-9';
                        $tg_inball='-9';
                    }else if ($tg_inball==$Score10){
                        $mb_inball1='-10';
                        $tg_inball1='-10';
                        $mb_inball2='-10';
                        $tg_inball2='-10';
                        $mb_inball3='-10';
                        $tg_inball3='-10';
                        $mb_inball4='-10';
                        $tg_inball4='-10';
                        $mb_inball_hr='-10';
                        $tg_inball_hr='-10';
                        $mb_inball_xb='-10';
                        $tg_inball_xb='-10';
                        $mb_inball='-10';
                        $tg_inball='-10';
                    }else if ($tg_inball==$Score11){
                        $mb_inball1='-11';
                        $tg_inball1='-11';
                        $mb_inball2='-11';
                        $tg_inball2='-11';
                        $mb_inball3='-11';
                        $tg_inball3='-11';
                        $mb_inball4='-11';
                        $tg_inball4='-11';
                        $mb_inball_hr='-11';
                        $tg_inball_hr='-11';
                        $mb_inball_xb='-11';
                        $tg_inball_xb='-11';
                        $mb_inball='-11';
                        $tg_inball='-11';
                    }else if ($tg_inball==$Score12){
                        $mb_inball1='-12';
                        $tg_inball1='-12';
                        $mb_inball2='-12';
                        $tg_inball2='-12';
                        $mb_inball3='-12';
                        $tg_inball3='-12';
                        $mb_inball4='-12';
                        $tg_inball4='-12';
                        $mb_inball_hr='-12';
                        $tg_inball_hr='-12';
                        $mb_inball_xb='-12';
                        $tg_inball_xb='-12';
                        $mb_inball='-12';
                        $tg_inball='-12';
                    }else if ($tg_inball=='联赛名称错误'){
                        $mb_inball1='-13';
                        $tg_inball1='-13';
                        $mb_inball2='-13';
                        $tg_inball2='-13';
                        $mb_inball3='-13';
                        $tg_inball3='-13';
                        $mb_inball4='-13';
                        $tg_inball4='-13';
                        $mb_inball_hr='-13';
                        $tg_inball_hr='-13';
                        $mb_inball_xb='-13';
                        $tg_inball_xb='-13';
                        $mb_inball='-13';
                        $tg_inball='-13';
                    }else if ($tg_inball==$Score14){
                        $mb_inball1='-14';
                        $tg_inball1='-14';
                        $mb_inball2='-14';
                        $tg_inball2='-14';
                        $mb_inball3='-14';
                        $tg_inball3='-14';
                        $mb_inball4='-14';
                        $tg_inball4='-14';
                        $mb_inball_hr='-14';
                        $tg_inball_hr='-14';
                        $mb_inball_xb='-14';
                        $tg_inball_xb='-14';
                        $mb_inball='-14';
                        $tg_inball='-14';
                    }else if ($tg_inball==$Score15){
                        $mb_inball1='-15';
                        $tg_inball1='-15';
                        $mb_inball2='-15';
                        $tg_inball2='-15';
                        $mb_inball3='-15';
                        $tg_inball3='-15';
                        $mb_inball4='-15';
                        $tg_inball4='-15';
                        $mb_inball_hr='-15';
                        $tg_inball_hr='-15';
                        $mb_inball_xb='-15';
                        $tg_inball_xb='-15';
                        $mb_inball='-15';
                        $tg_inball='-15';
                    }else if ($tg_inball==$Score16){
                        $mb_inball1='-16';
                        $tg_inball1='-16';
                        $mb_inball2='-16';
                        $tg_inball2='-16';
                        $mb_inball3='-16';
                        $tg_inball3='-16';
                        $mb_inball4='-16';
                        $tg_inball4='-16';
                        $mb_inball_hr='-16';
                        $tg_inball_hr='-16';
                        $mb_inball_xb='-16';
                        $tg_inball_xb='-16';
                        $mb_inball='-16';
                        $tg_inball='-16';
                    }else if ($tg_inball==$Score17){
                        $mb_inball1='-17';
                        $tg_inball1='-17';
                        $mb_inball2='-17';
                        $tg_inball2='-17';
                        $mb_inball3='-17';
                        $tg_inball3='-17';
                        $mb_inball4='-17';
                        $tg_inball4='-17';
                        $mb_inball_hr='-17';
                        $tg_inball_hr='-17';
                        $mb_inball_xb='-17';
                        $tg_inball_xb='-17';
                        $mb_inball='-17';
                        $tg_inball='-17';
                    }else if ($tg_inball==$Score18){
                        $mb_inball1='-18';
                        $tg_inball1='-18';
                        $mb_inball2='-18';
                        $tg_inball2='-18';
                        $mb_inball3='-18';
                        $tg_inball3='-18';
                        $mb_inball4='-18';
                        $tg_inball4='-18';
                        $mb_inball_hr='-18';
                        $tg_inball_hr='-18';
                        $mb_inball_xb='-18';
                        $tg_inball_xb='-18';
                        $mb_inball='-18';
                        $tg_inball='-18';
                    }else if ($tg_inball==$Score19){
                        $mb_inball1='-19';
                        $tg_inball1='-19';
                        $mb_inball2='-19';
                        $tg_inball2='-19';
                        $mb_inball3='-19';
                        $tg_inball3='-19';
                        $mb_inball4='-19';
                        $tg_inball4='-19';
                        $mb_inball_hr='-19';
                        $tg_inball_hr='-19';
                        $mb_inball_xb='-19';
                        $tg_inball_xb='-19';
                        $mb_inball='-19';
                        $tg_inball='-19';
                    }

                    if ($tg_inball_hr=='不显示赛程' or $tg_inball_xb=='不显示赛程'){
                        $mb_inball1=$mb_inball1;
                        $tg_inball1=$tg_inball1;
                        $mb_inball2=$mb_inball2;
                        $tg_inball2=$tg_inball2;
                        $mb_inball3=$mb_inball3;
                        $tg_inball3=$tg_inball3;
                        $mb_inball4=$mb_inball4;
                        $tg_inball4=$tg_inball4;
                        $mb_inball_hr='-1';
                        $tg_inball_hr='-1';
                        $mb_inball_xb='-1';
                        $tg_inball_xb='-1';
                        $mb_inball=$mb_inball;
                        $tg_inball=$tg_inball;
                    }

                    $sqlq="select MID,MB_Inball from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK'  and M_League='$M_League' and  TG_Team='$TG_Team' and MB_Team='$MB_Team' and M_Date='$list_date'";
                    $resultq = mysqli_query($dbLink, $sqlq);
                    while($rowq = mysqli_fetch_assoc($resultq)){
                        $mid='';
                        $mids=$rowq["MID"];
                        //第一节
                        $mid3=$mids+3; // +3 是本场比赛第一节
                        $sql="select MID,MB_Inball,Score_Source from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and MID=".(int)$mid3." and M_Date='$list_date'";
                        $result = mysqli_query($dbLink, $sql);
                        $cou=mysqli_num_rows($result);
                        $row = mysqli_fetch_assoc($result);
                        if($cou==1){
                            if($row["Score_Source"]!=3){
                                $mid=$row['MID'];
                                if( $mb_inball1<0 && $mb_inball1!=$row["MB_Inball"] ){
                                    //$mysql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='$mb_inball1',TG_Inball='$tg_inball1',MB_Inball_HR='$mb_inball_hr',TG_Inball_HR='$tg_inball_hr',Checked=1,Cancel=1,Score_Source=2 where Type='BK' and ($mb_inball1!=0 or $tg_inball1!=0) and M_Date='$list_date' and MID=".(int)$mid;
                                    //mysqli_query($dbMasterLink,$mysql) or die('abc1');
                                    matchAbnormalDeal($dbLink,$dbMasterLink,$mid,$mb_inball1,$tg_inball1,$mb_inball_hr,$tg_inball_hr,$list_date);
                                }else{
                                    if ($row['MB_Inball']==""){
                                        $mysql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='$mb_inball1',TG_Inball='$tg_inball1',TG_Inball_HR=0,MB_Inball_HR=0,Score_Source=2 where Type='BK' and ($mb_inball1!=0 or $tg_inball1!=0) and M_Date='$list_date' and MID=".(int)$mid;
                                        mysqli_query($dbMasterLink,$mysql) or die('abc1');
                                        $redisObj->pushMessage('MatchScorefinishList',(int)$mid);
                                    }else{
                                        $a_sql="select MID,MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and MID='".(int)$mid."' and M_Date='$list_date'";
                                        $a_result = mysqli_query($dbLink,$a_sql);
                                        $a_row = mysqli_fetch_assoc($a_result);
                                        $a=	$a_row['MB_Inball'].$a_row['TG_Inball'];
                                        $b=	trim($mb_inball1).trim($tg_inball1);
                                        if ($a!=$b){
                                            $check=1;
                                            $mysql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='$mb_inball1',TG_Inball='$tg_inball1',TG_Inball_HR=0,MB_Inball_HR=0,Checked='$check',Score_Source=2  where Type='BK' and ($mb_inball1!=0 or $tg_inball1!=0) and M_Date='$list_date' and MID=".(int)$mid;
                                            mysqli_query($dbMasterLink, $mysql) or die('abc1');
                                        }
                                    }
                                }
                                $m=$m+1;
                            }
                        }

                        //第二节
                        $mid4=$mids+4;
                        $sql="select MID,MB_Inball,Score_Source from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and MID=".(int)$mid4." and M_Date='$list_date'";
                        $result = mysqli_query($dbLink, $sql);
                        $cou=mysqli_num_rows($result);
                        $row = mysqli_fetch_assoc($result);
                        if ($cou==1){
                            if($row["Score_Source"]!=3){
                                $mid=$row['MID'];
                                if( $mb_inball2<0 && $mb_inball2!=$row["MB_Inball"] ){
                                        //$mysql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='$mb_inball2',TG_Inball='$tg_inball2',MB_Inball_HR='$mb_inball_hr',TG_Inball_HR='$tg_inball_hr',Checked=1,Cancel=1,Score_Source=2 where Type='BK' and ($mb_inball2!=0 or $tg_inball2!=0) and M_Date='$list_date' and MID=".(int)$mid;
                                        //mysqli_query($dbMasterLink,$mysql) or die('abc2');
                                        matchAbnormalDeal($dbLink,$dbMasterLink,$mid,$mb_inball2,$tg_inball2,$mb_inball_hr,$tg_inball_hr,$list_date);
                                }else{
                                    if ($row['MB_Inball']==""){
                                        $mysql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='$mb_inball2',TG_Inball='$tg_inball2',TG_Inball_HR=0,MB_Inball_HR=0,Score_Source=2 where Type='BK' and ($mb_inball2!=0 or $tg_inball2!=0) and M_Date='$list_date' and MID=".(int)$mid;
                                        mysqli_query($dbMasterLink, $mysql) or die('abc2');
                                        $redisObj->pushMessage('MatchScorefinishList',(int)$mid);
                                    }else{
                                        $a_sql="select MID,MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and MID='".(int)$mid."' and M_Date='$list_date'";
                                        $a_result = mysqli_query($dbLink,$a_sql);
                                        $a_row = mysqli_fetch_assoc($a_result);
                                        $a=	$a_row['MB_Inball'].$a_row['TG_Inball'];
                                        $b=	trim($mb_inball2).trim($tg_inball2);
                                        if ($a!=$b){
                                            $check=1;
                                            $mysql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='$mb_inball2',TG_Inball='$tg_inball2',TG_Inball_HR=0,MB_Inball_HR=0,Checked='$check',Score_Source=2  where Type='BK' and ($mb_inball2!=0 or $tg_inball2!=0) and M_Date='$list_date' and MID=".(int)$mid;
                                            mysqli_query($dbMasterLink, $mysql) or die('abc2');
                                        }
                                    }
                                }
                                $m=$m+1;
                            }
                        }

                        //第三节
                        $mid5=$mids+5;
                        $sql="select MID,MB_Inball,Score_Source from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and MID=".(int)$mid5." and M_Date='$list_date'";
                        $result = mysqli_query($dbLink, $sql);
                        $cou=mysqli_num_rows($result);
                        $row = mysqli_fetch_assoc($result);
                        if ($cou==1){
                            if($row["Score_Source"]!=3){
                                $mid=$row['MID'];
                                if( $mb_inball3<0 && $mb_inball3!=$row["MB_Inball"] ){
                                        //$mysql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='$mb_inball3',TG_Inball='$tg_inball3',MB_Inball_HR='$mb_inball_hr',TG_Inball_HR='$tg_inball_hr',Checked=1,Cancel=1,Score_Source=2 where Type='BK' and ($mb_inball3!=0 or $tg_inball3!=0) and M_Date='$list_date' and MID=".(int)$mid;
                                        //mysqli_query($dbMasterLink,$mysql) or die('abc3');
                                        matchAbnormalDeal($dbLink,$dbMasterLink,$mid,$mb_inball3,$tg_inball3,$mb_inball_hr,$tg_inball_hr,$list_date);
                                }else{
                                    if ($row['MB_Inball']==""){
                                        $mysql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='$mb_inball3',TG_Inball='$tg_inball3',TG_Inball_HR=0,MB_Inball_HR=0,Score_Source=2 where Type='BK' and ($mb_inball3!=0 or $tg_inball3!=0) and M_Date='$list_date' and MID=".(int)$mid;
                                        mysqli_query($dbMasterLink, $mysql) or die('abc3');
                                        $redisObj->pushMessage('MatchScorefinishList',(int)$mid);
                                    }else{
                                        $a_sql="select MID,MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and MID='".(int)$mid."' and M_Date='$list_date'";
                                        $a_result = mysqli_query($dbLink,$a_sql);
                                        $a_row = mysqli_fetch_assoc($a_result);
                                        $a=	$a_row['MB_Inball'].$a_row['TG_Inball'];
                                        $b=	trim($mb_inball3).trim($tg_inball3);
                                        if ($a!=$b){
                                            $check=1;
                                            $mysql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='$mb_inball3',TG_Inball='$tg_inball3',TG_Inball_HR=0,MB_Inball_HR=0,Checked='$check',Score_Source=2  where Type='BK' and ($mb_inball3!=0 or $tg_inball3!=0) and M_Date='$list_date' and MID=".(int)$mid;
                                            mysqli_query($dbMasterLink, $mysql) or die('abc3');
                                        }
                                    }
                                }
                                $m=$m+1;
                            }
                        }

                        //第四节
                        $mid6=$mids+6;
                        $sql="select MID,MB_Inball,Score_Source from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and MID=".(int)$mid6." and M_Date='$list_date'";
                        $result = mysqli_query($dbLink, $sql);
                        $cou=mysqli_num_rows($result);
                        $row = mysqli_fetch_assoc($result);
                        if ($cou==1){
                            if($row["Score_Source"]!=3){
                                $mid=$row['MID'];
                                if( $mb_inball4<0 && $mb_inball4!=$row["MB_Inball"] ){
                                    //$mysql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='$mb_inball4',TG_Inball='$tg_inball4',MB_Inball_HR='$mb_inball_hr',TG_Inball_HR='$tg_inball_hr',Checked=1,Cancel=1,Score_Source=2 where Type='BK' and ($mb_inball4!=0 or $tg_inball4!=0) and M_Date='$list_date' and MID=".(int)$mid;
                                    //mysqli_query($dbMasterLink,$mysql) or die('abc4');
                                    matchAbnormalDeal($dbLink,$dbMasterLink,$mid,$mb_inball4,$tg_inball4,$mb_inball_hr,$tg_inball_hr,$list_date);
                                }else{
                                    if ($row['MB_Inball']==""){
                                        $mysql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='$mb_inball4',TG_Inball='$tg_inball4',TG_Inball_HR=0,MB_Inball_HR=0,Score_Source=2 where Type='BK' and ($mb_inball4!=0 or $tg_inball4!=0) and M_Date='$list_date' and MID=".(int)$mid;
                                        mysqli_query($dbMasterLink, $mysql) or die('abc4');
                                        $redisObj->pushMessage('MatchScorefinishList',(int)$mid);
                                    }else{
                                        $a_sql="select MID,MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and MID='".(int)$mid."' and M_Date='$list_date'";
                                        $a_result = mysqli_query($dbLink,$a_sql);
                                        $a_row = mysqli_fetch_assoc($a_result);
                                        $a=	$a_row['MB_Inball'].$a_row['TG_Inball'];
                                        $b=	trim($mb_inball4).trim($tg_inball4);
                                        /*var_dump($a);
                                        var_dump($b);echo "<br/>";*/
                                        if ($a!=$b){
                                            $check=1;
                                            $mysql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='$mb_inball4',TG_Inball='$tg_inball4',TG_Inball_HR=0,MB_Inball_HR=0,Checked='$check',Score_Source=2  where Type='BK' and ($mb_inball4!=0 or $tg_inball4!=0) and M_Date='$list_date' and MID=".(int)$mid;
                                            mysqli_query($dbMasterLink, $mysql) or die('abc4');
                                        }
                                    }
                                }
                                $m=$m+1;
                            }
                        }


                        //上半
                        $mid1=$mids+1;
                        $sql="select MID,MB_Inball,Score_Source from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and MID=".(int)$mid1." and M_Date='$list_date'";
                        $result = mysqli_query($dbLink, $sql);
                        $cou=mysqli_num_rows($result);
                        $row = mysqli_fetch_assoc($result);
                        if($cou==1){
                            if($row["Score_Source"]!=3){
                                $mid=$row['MID'];
                                if( $mb_inball_hr<0 && $mb_inball_hr!=$row["MB_Inball"] ){
                                    //$mysql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='$mb_inball_hr',TG_Inball='$tg_inball_hr',MB_Inball_HR='$mb_inball_hr',TG_Inball_HR='$tg_inball_hr',Checked=1,Cancel=1,Score_Source=2 where Type='BK' and ($mb_inball_hr!=0 or $tg_inball_hr!=0) and M_Date='$list_date' and MID=".(int)$mid;
                                    //mysqli_query($dbMasterLink,$mysql) or die('abchr');
                                    matchAbnormalDeal($dbLink,$dbMasterLink,$mid,$mb_inball_hr,$tg_inball_hr,$mb_inball_hr,$tg_inball_hr,$list_date);
                                }else{
                                    if ($row['MB_Inball']==""){
                                        $mysql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='$mb_inball_hr',TG_Inball='$tg_inball_hr',TG_Inball_HR=0,MB_Inball_HR=0,Score_Source=2 where Type='BK' and ($mb_inball_hr!=0 or $tg_inball_hr!=0) and M_Date='$list_date' and MID=".(int)$mid;
                                        mysqli_query($dbMasterLink, $mysql) or die('abchr');
                                        $redisObj->pushMessage('MatchScorefinishList',(int)$mid);
                                    }else{
                                        $a_sql="select MID,MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and MID='".(int)$mid."' and M_Date='$list_date'";
                                        $a_result = mysqli_query($dbLink,$a_sql);
                                        $a_row = mysqli_fetch_assoc($a_result);
                                        $a=	$a_row['MB_Inball'].$a_row['TG_Inball'];
                                        $b=	trim($mb_inball_hr).trim($tg_inball_hr);
                                        /*var_dump($a);
                                        var_dump($b);echo "<br/>";*/
                                        if ($a!=$b){
                                            $check=1;
                                            $mysql="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='$mb_inball_hr',TG_Inball='$tg_inball_hr',TG_Inball_HR=0,MB_Inball_HR=0,Checked='$check',Score_Source=2  where Type='BK' and ($mb_inball_hr!=0 or $tg_inball_hr!=0) and M_Date='$list_date' and MID=".(int)$mid;
                                            mysqli_query($dbMasterLink, $mysql) or die('abchr');
                                        }
                                    }
                                }
                                $m=$m+1;
                            }
                        }


                        //下半
                        $mid2=$mids+2;
                        $sql="select MID,MB_Inball,Score_Source from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and MID=".(int)$mid2." and M_Date='$list_date'";
                        $result = mysqli_query($dbLink, $sql);
                        $cou=mysqli_num_rows($result);
                        $row = mysqli_fetch_assoc($result);
                        if ($cou==1){
                            if($row["Score_Source"]!=3) {
                                $mid = $row['MID'];
                                if( $mb_inball_xb<0 && $mb_inball_xb!=$row["MB_Inball"] ){
                                    //$mysql = "update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='$mb_inball_xb',TG_Inball='$tg_inball_xb',MB_Inball_HR='$mb_inball_hr',TG_Inball_HR='$tg_inball_hr',Checked=1,Cancel=1,Score_Source=2 where Type='BK' and ($mb_inball_xb!=0 or $tg_inball_xb!=0) and M_Date='$list_date' and MID=" . (int)$mid;
                                    //mysqli_query($dbMasterLink, $mysql) or die('abcxb');
                                    matchAbnormalDeal($dbLink,$dbMasterLink,$mid,$mb_inball_xb,$tg_inball_xb,$mb_inball_hr,$tg_inball_hr,$list_date);
                                }else{
                                    if($row['MB_Inball'] == ""){
                                        $mysql = "update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='$mb_inball_xb',TG_Inball='$tg_inball_xb',TG_Inball_HR=0,MB_Inball_HR=0,Score_Source=2 where Type='BK' and ($mb_inball_xb!=0 or $tg_inball_xb!=0) and M_Date='$list_date' and MID=" . (int)$mid;
                                        mysqli_query($dbMasterLink, $mysql) or die('abcxb');
                                        $redisObj->pushMessage('MatchScorefinishList', (int)$mid);
                                    }else{
                                        $a_sql = "select MID,MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and MID='" . (int)$mid . "' and M_Date='$list_date'";
                                        $a_result = mysqli_query($dbLink, $a_sql);
                                        $a_row = mysqli_fetch_assoc($a_result);
                                        $a = $a_row['MB_Inball'] . $a_row['TG_Inball'];
                                        $b = trim($mb_inball_xb) . trim($tg_inball_xb);
                                        /*var_dump($a);
                                        var_dump($b);echo "<br/>";*/
                                        if ($a != $b) {
                                            $check = 1;
                                            $mysql = "update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='$mb_inball_xb',TG_Inball='$tg_inball_xb',TG_Inball_HR=0,MB_Inball_HR=0,Checked='$check',Score_Source=2  where Type='BK' and ($mb_inball_xb!=0 or $tg_inball_xb!=0) and M_Date='$list_date' and MID=" . (int)$mid;
                                            mysqli_query($dbMasterLink, $mysql) or die('abcxb');
                                        }
                                    }
                                }
                                $m = $m + 1;
                            }
                        }

                        //全场
                        $pos = strpos($M_League, 'NBA');
                        if ( $pos !== false and ($mb_inball+$tg_inball)<100 ){ // NBA篮球联赛的全场比分之和小于100分，则忽略不更新

                        }
                        else{
                            $sql = "select MID,MB_Inball,Score_Source from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and MID=" . (int)$mids . " and M_Date='$list_date'";
                            $result = mysqli_query($dbLink, $sql);
                            $cou = mysqli_num_rows($result);
                            $row = mysqli_fetch_assoc($result);
                            if ($cou == 1) {
                                if ($row["Score_Source"] != 3) {
                                    $mid = $row['MID'];
                                    if ($mb_inball < 0 && $mb_inball != $row["MB_Inball"]) {
                                        //$mysql = "update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='$mb_inball',TG_Inball='$tg_inball',MB_Inball_HR='$mb_inball_hr',TG_Inball_HR='$tg_inball_hr',Checked=1,Cancel=1,Score_Source=2 where Type='BK' and ($mb_inball!=0 or $tg_inball!=0) and M_Date='$list_date' and MID=".(int)$mid;
                                        //mysqli_query($dbMasterLink,$mysql) or die('2abc');
                                        matchAbnormalDeal($dbLink, $dbMasterLink, $mid, $mb_inball, $tg_inball, $mb_inball_hr, $tg_inball_hr, $list_date);
                                    } else {
                                        if ($row['MB_Inball'] == "") {
                                            $mysql = "update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='$mb_inball',TG_Inball='$tg_inball',MB_Inball_HR='$mb_inball_hr',TG_Inball_HR='$tg_inball_hr',Score_Source=2 where Type='BK' and ($mb_inball!=0 or $tg_inball!=0) and M_Date='$list_date' and MID=" . (int)$mid;
                                            mysqli_query($dbMasterLink, $mysql) or die('1abc');
                                            $redisObj->pushMessage('MatchScorefinishList', (int)$mid);
                                        } else {
                                            $a_sql = "select MID,MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and MID='" . (int)$mid . "' and M_Date='$list_date'";
                                            $a_result = mysqli_query($dbLink, $a_sql);
                                            $a_row = mysqli_fetch_assoc($a_result);
                                            $a = $a_row['MB_Inball'] . $a_row['TG_Inball'];
                                            $b = trim($mb_inball) . trim($tg_inball);
                                            /*var_dump($a);
                                            var_dump($b);
                                            echo "<br/>";*/
                                            if ($a != $b) {
                                                $check = 1;
                                                $mysql = "update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='$mb_inball',TG_Inball='$tg_inball',MB_Inball_HR='$mb_inball_hr',TG_Inball_HR='$tg_inball_hr',Checked='$check',Score_Source=2  where Type='BK' and ($mb_inball!=0 or $tg_inball!=0) and M_Date='$list_date' and MID=" . (int)$mid;
                                                mysqli_query($dbMasterLink, $mysql) or die('3abc');
                                            }
                                        }
                                    }
                                    $m = $m + 1;
                                }
                            }

                        }
                    }
                }

            }
        }

    }
    if($m>0){ break; }
}

/*
 *篮球比分负数异常处理
 * */
function matchAbnormalDeal($dbLink,$dbMasterLink,$mid,$mb_inball,$tg_inball,$mb_inball_hr,$tg_inball_hr,$list_date){
    //-----------------取消注单---start
    $sql = "update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Inball='$mb_inball',TG_Inball='$tg_inball',MB_Inball_HR='$mb_inball_hr',TG_Inball_HR='$tg_inball_hr',Checked=1,Score_Source=2  where Type='BK' and ($mb_inball!=0 or $tg_inball!=0) and M_Date='$list_date' and MID=".(int)$mid;
    mysqli_query($dbMasterLink,$sql) or die ("操作失败1");
    $rsql = "select ID from ".DBPREFIX."web_report_data where MID='".$mid."' and Pay_Type=1";
    $rresult = mysqli_query($dbLink, $rsql);
    while ($rrow = mysqli_fetch_assoc($rresult)){
        $beginFrom = mysqli_query($dbMasterLink,"start transaction");
        if($beginFrom){
            $id=$rrow['ID'];
            $resultCheck = mysqli_query($dbMasterLink,"select userid,M_Name,Pay_Type,BetScore,M_Result,LineType,Cancel from ".DBPREFIX."web_report_data where ID=$id for update");
            $rowCheck = mysqli_fetch_assoc($resultCheck);
            if( $rowCheck['Cancel']==0 ){
                $userid=$rowCheck['userid'];
                $username=$rowCheck['M_Name'];
                $betscore=$rowCheck['BetScore'];
                $m_result=$rowCheck['M_Result'];
                $resultMem = mysqli_query($dbMasterLink,"select ID,UserName,test_flag,Money from  ".DBPREFIX.MEMBERTABLE." where ID=$userid for update");
                if($resultMem){
                    $rowMem = mysqli_fetch_assoc($resultMem);
                    if($m_result==''){//未结算
                        $u_sql = "update ".DBPREFIX.MEMBERTABLE." set Money=Money+$betscore where ID=".$userid;
                    }else{//已经结算
                        /*
                        if (intval($rowMem['Money']) < intval($m_result)){
                            mysqli_query($dbMasterLink,"ROLLBACK");
                            $errorArr[$id]="{$id}会员资金不足，取消赛事和注单失败！";
                            continue;
                        }
                        $u_sql = "update ".DBPREFIX.MEMBERTABLE." set Money=Money-$m_result where ID=".$userid;
                        */
                    }

                    if(strlen($u_sql)>0){
                        if(mysqli_query($dbMasterLink,$u_sql)){
                            $confirmed = $mb_inball;
                            $sql1="update ".DBPREFIX."web_report_data set VGOLD=0,M_Result=0,A_Result=0,B_Result=0,C_Result=0,D_Result=0,T_Result=0,Confirmed='$confirmed',Danger=0,Cancel=1,Checked=1,updateTime='".date('Y-m-d H:i:s',time())."' where `ID`='".$id."'";
                            if(mysqli_query($dbMasterLink,$sql1)){
                                $descCancel='Score'.$confirmed*-1;
                                if($m_result==''){
                                    $moneyLog=$betscore;
                                    $moneyDesLog=$$descCancel.'：退回用户投注金额';
                                }
                                $moneyDesLog="[正网抓比分]".$moneyDesLog;
                                $moneyLogRes=addAccountRecords(array($userid,$username,$rowMem['test_flag'],$rowMem['Money'],$moneyLog,$rowMem['Money']+$moneyLog,2,9,$id,$moneyDesLog));
                                if($moneyLogRes){
                                    mysqli_query($dbMasterLink,"COMMIT");
                                }else{
                                    mysqli_query($dbMasterLink,"ROLLBACK");
                                    $errorArr[$id]="{$id}用户资金账变添加失败！";
                                    continue;
                                }
                            }else{
                                mysqli_query($dbMasterLink,"ROLLBACK");
                                $errorArr[$id]="{$id}订单更新失败！";
                                continue;
                            }
                        }else{
                            mysqli_query($dbMasterLink,"ROLLBACK");
                            $errorArr[$id]="{$id}用户资金账户更新失败！";
                            continue;
                        }
                    }
                }else{
                    mysqli_query($dbMasterLink,"ROLLBACK");
                    $errorArr[$id]="{$id}用户资金锁定失败！";
                    continue;
                }
            }else{
                mysqli_query($dbMasterLink,"ROLLBACK");
                $errorArr[$id]="{$id}订单已被取消,不能重复操作！";
                continue;
            }
        }else{
            mysqli_query($dbMasterLink,"ROLLBACK");
            $errorArr[$id]="{$id}事务开启失败！";
            continue;
        }
    }

    if(count($errorArr)>0){
        $logContent='';
        $logContent="BK_zhengweb        ".$mid."\n\r";
        $logContent.="mbScore:".$mb_inball." tgScore:".$tg_inball."mb_in_score_v:".$mb_inball_hr."  tg_in_score_v:".$tg_inball_hr."\n\r";
        $logContent.=date('Y-m-d H:i:s',time())."\n\r\n\r";
        $logContent.=' '.implode(',',$errorArr);
        $file = "./resultSCore_BK_".date('Y-m-d',time()).".txt";
        $handle = fopen($file, 'a+');
        ob_start(); //打开缓冲区
        echo $logContent;//打印信息
        $info=ob_get_contents(); //得到缓冲区的内容并且赋值给$info
        ob_clean(); //关闭缓冲区
        fwrite($handle, $info);
        fclose($handle);
    }
    //-----------------取消注单---end
}


function get_content_deal($html_data){
    $html_data = strtolower($html_data);

    $myleg = explode("array",$html_data);
    $myleg = explode(";",$myleg[1]);
    $myleg = explode(",",$myleg[0]);
    array_shift($myleg);
    $aMyleg = [];
    foreach ($myleg as $k => $v){
        $v = str_replace("'",'',$v);
        $leagueid_mid = explode('_',str_replace(")","",$v));
        $aMyleg[$k]['league_id'] = $leagueid_mid[0];
        $aMyleg[$k]['mid'] = $leagueid_mid[1];
    }

//    $a = array(
//        "<script>",
//        "</script>",
//        '"',
//        "\n\n",
//        "<br>",
//        " ",
//        '</b></font>',
//        "<td>",
//        "<tdalign=left>",
//        "<fontcolor=#cc0000>",
//        "<fontcolor=red>",
//        "<b>",
//        "</b>",
//        "</a>",
//        "</font>",
//        "<spanstyle=overflow:hidden;width:50;height:15>",
//        "</span>",
//        "&nbsp;&nbsp;",
//        "invisiblematch",
//        " ",
//        "</strong></td>"
//    );
//    $b = array(
//        "",
//        "",
//        "",
//        "",
//        "",
//        "",
//        '',
//        "",
//        "",
//        "",
//        "",
//        "",
//        "",
//        "",
//        "",
//        "",
//        "",
//        "",
//        "",
//        ""
//    );
//
//    $html_data = explode("</table>",$html_data);
//    $msg = str_replace($a,$b,$html_data[0]);
//    $aMsg = explode("<!--table>", $msg);
//    array_shift($aMsg);
//    // $aMsg 每一个键值，都是一场联赛，从中抽取联赛标题、主队名称和全场比分、客队名称和全场比分
//    $data = [];
//    foreach ( $aMsg as $k => $v ){
//        $a = array(
//                "<!----------------------->",
//                "<!--/table-->",
//                "\t",
//                "\n",
//                "\r",
//        );
//        $b = array(
//            "",
//            "",
//            "",
//            "",
//        );
//        $msg = str_replace($a,$b,$v);
//        $aMsg1 = explode("</tr>",$msg);
//        $league = explode("showleg('",array_shift($aMsg1))[1];  // 弹出第一个数组，并清理其中的html标签，则为联赛信息
//        $league = explode("');><span>",$league);
//        $league_id = $league[0];  // 联赛id
//        $league = clear_html($league[1]);
//        $data[$league_id]['league_id'] = $league_id;
//        $data[$league_id]['league'] = $league;  // 联赛标题
//        $aMsg1 = array_filter($aMsg1); // 移除空数组
//
////        print_r($aMsg1); die;
//
//        foreach ($aMsg1 as $k1 => $v1){
//
//            // 对2取余等于0则为第1个队伍，否则为第2个队伍
//            if ($k1 % 2 == 0){
//
//                $aMsg2 = explode("</td>", $v1);
//
////                print_r($aMsg2); die;
//                $mid =  explode("><tdrowspan",$aMsg2[0]);
//                $mid =  explode("_",explode("=tr_",$mid[0])[1])[1];
//                $data[$league_id]['mid'] = $mid;
//                $data[$league_id]['mb_team'] = clear_html($aMsg2[1]);
//                $data[$league_id]['mb_score'] = clear_html($aMsg2[2]);
//                $data[$league_id]['mb_js_score'] = clear_html($aMsg2[3]);
//
//            }else{
//
//                $aMsg2 = explode("</td>", $v1);
//                $data[$league_id]['tg_team'] = clear_html($aMsg2[0]);
//                $data[$league_id]['tg_score'] = clear_html($aMsg2[1]);
//                $data[$league_id]['tg_js_score'] = clear_html($aMsg2[2]);
//            }
//        }
//
//    }

    return $aMyleg;
}

// 清理html
function clear_html($html_data){
    $search = array ("'<script[^>]*?>.*?</script>'si", // 去掉 javascript
        "'<[\/\!]*?[^<>]*?>'si", // 去掉 HTML 标记
        "'([\r\n])[\s]+'", // 去掉空白字符
        "'&(quot|#34);'i", // 替换 HTML 实体
        "'&(amp|#38);'i",
        "'&(lt|#60);'i",
        "'&(gt|#62);'i",
        "'&(nbsp|#160);'i"
    ); // 作为 PHP 代码运行
    $replace = array ("","","\\1","\"","&","<",">"," ");
    $html = preg_replace($search, $replace, $html_data);

    return $html;
}

echo '<br>目前比分以结算出'.$m;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>篮球接比分</title>
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
      <input type=button name=button value="篮球更新" onClick="window.location.reload()"></td>
  </tr>
</table>
</body>
</html>
