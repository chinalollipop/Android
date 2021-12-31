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
//$html_data=$curl->fetch_url("https://xj-sbr.prdasbbwla1.com/zh-cn/info-centre/sportsbook-info/results/1/normal/1?opcode=KZhing&tzoff=480&theme=KZing");
$html_data=$curl->fetch_url("https://sb-results.188sbk.com/zh-cn/info-centre/sportsbook-info/results?tzoff=-240");
$html_data = mb_convert_encoding($html_data, 'utf-8', 'GBK,UTF-8,ASCII');
preg_match("/<body[^>]*?>(.*\s*?)<\/body>/is",$html_data,$matches);
$matches = explode('<div class="content data-grid hidden s1">',$matches[0]);
$matches = explode('<div id="dd-comp-menu">',$matches[1]);
$matches = rtrim(trim($matches[0]),'</div>');
$data = get_content_deal($matches);
$data = explode('<div class=rt-l-bar football id=cmp-', $data);
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
$aM_league = [];
foreach ($data as $k => $v){

    $item = explode('</table>',$v); // 盘口列表
    $item_split_row = explode('</span></div>',$item[0]);
    array_pop($item); // 删除数组最后一个元素
    $sM_League = explode('>',$item_split_row[0])[1];
    $sM_League = tradition2simple($sM_League);
    if (strpos($sM_League,'电竞')===false and strpos($sM_League,'分钟'))continue;

    foreach ($item as $k2 => $v2){
        $res[$k][$k2]['M_League'] = $sM_League; // 联赛名称
        // 筛选取出每个盘口的主队名称、客队名称、半场的主客队比分、全场的主客队比分
        $M_Start_others = explode('<div class=rt-event title=', $v2);
        $res[$k][$k2]['M_Start'] = date('Y-m-d H:i:s',strtotime(trim(str_replace($a,$b,explode("<span>", $M_Start_others[0])[1]))));
        if ($res[$k][$k2]['M_Start']<date('Y-m-d 00:00')) { unset($res[$k][$k2]); continue;}
        $aMB_TG_team_others = explode('<span class=pt >',$M_Start_others[1]);
        array_shift($aMB_TG_team_others); // 删除数组第一个元素
        $sMB_Team=str_replace('</span><span class=vs>v</span>','',$aMB_TG_team_others[0]);
//        $sMB_Team = tradition2simple($sMB_Team);
        if (strpos($sMB_Team,'-角球数')) $sMB_Team=str_replace('-角球数',' -角球数',$sMB_Team);
        if (strpos($sMB_Team,'-罚牌数')) $sMB_Team=str_replace('-罚牌数',' -罚牌数',$sMB_Team);
        $res[$k][$k2]['MB_Team']=trim($sMB_Team);
        if (strpos($sMB_Team,'-角球数') || strpos($sMB_Team,'-罚牌数') ){
            $res[$k][$k2]['M_League']=trim(str_replace('-特别投注','',$sM_League));
        }
        $aTG_team_score = explode('</span>',$aMB_TG_team_others[1]);
        $sTG_Team=$aTG_team_score[0];
//        $sTG_Team = tradition2simple($sTG_Team);
        if (strpos($sTG_Team,'-角球数')) $sTG_Team=str_replace('-角球数',' -角球数',$sTG_Team);
        if (strpos($sTG_Team,'-罚牌数')) $sTG_Team=str_replace('-罚牌数',' -罚牌数',$sTG_Team);
        $res[$k][$k2]['TG_Team']=trim($sTG_Team);
        $score = explode('</tbody>',$aMB_TG_team_others[1])[0]; // tbody后面没用的去掉
        $score = str_replace("\r\n\r\n","",$score);
        $score = str_replace("<span>取消: <span class='e-cancel'>","",$score);
        $score = str_replace("cancelled","",$score);
        $score = explode('<div class=rt-ft >',explode('<div class=rt-ht >',$score)[1]);
        $res[$k][$k2]['rt-ht'] = trim(str_replace($a,$b,$score[0])); // 半场比分
        $res[$k][$k2]['rt-ft'] = trim(str_replace($a,$b,$score[1])); // 全场比分
    }
}
$res = array_values($res);

//print_r($res); die;

$datainfo = [];
foreach ($res as $k => $v){
    foreach ($v as $k2 => $v2){
        // 将没有比分的赛事从赛果中移除
        if ($v2['rt-ht']=='' and $v2['rt-ft']==''){ continue; }
        $sql="select MID,MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR,M_Start,Score_Source,M_League,TG_Team,MB_Team from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='FT' and M_Date='".$list_date."' and M_Start='".$v2["M_Start"]."' and M_League='".$v2["M_League"]."' and MB_Team='".$v2["MB_Team"]."' and TG_Team='".$v2['TG_Team']."' and MB_Inball='' and TG_Inball=''";
        $result = mysqli_query($dbLink,$sql);
        $mcou = mysqli_num_rows($result);
        if ($mcou>0) {
            $row = mysqli_fetch_assoc($result);
            if( $row['MB_Inball']<0 || $row['TG_Inball']<0 || $row['MB_Inball_HR']<0 || $row['TG_Inball_HR']<0 ){ continue; }//如果其中有一项小于0则认为管理员已做赛事处理，跳出当前赛事，继续下一个赛事
            if( $row['Score_Source']>0){ continue; }//如果悠久乐、皇冠或管理员已经处理过，则跳出当前赛事，继续下一个赛事
            $datainfo[] = $v2;
        }
    }

}

//print_r($datainfo); die;

	$m=0;
	$cou = count($datainfo);
	if($cou>0){//可以抓到数据
//            log_note("score_ft_from_jbb"."\r\n");
//            log_note("\r\n".date('Y-m-d H:i:s')."\r\n");
			for($i=0;$i<count($datainfo);$i++){
//                log_note("------------------------------------------------------------------------------------\r\n");
//				$data = eval("return $dataInfo[$i];");
				$data = $datainfo[$i];

                $mb_inball=$tg_inball=$mb_inball_hr=$tg_inball_hr='';

				$dataHalf = explode('-',$data['rt-ht']);//半场
				$dataAll = explode('-',$data['rt-ft']);//全场
				$mb_inball = trim($dataAll[0]);		//主队全场比分
				$tg_inball = trim($dataAll[1]);		//客队全场比分
				$mb_inball_hr = trim($dataHalf[0]);	//主队半场比分
				$tg_inball_hr = trim($dataHalf[1]);	//主队半场比分

//                echo $mb_inball.'-'.$tg_inball.'-'.$mb_inball_hr.'-'.$tg_inball_hr.'---------------';

					//赛事特殊情况处理
					if ($tg_inball==$Score1){$mb_inball='-1';$tg_inball='-1';}
					if ($tg_inball_hr==$Score1){$mb_inball_hr='-1';$tg_inball_hr='-1';}
					if ($tg_inball==$Score2){$mb_inball='-2';$tg_inball='-2';}
					if ($tg_inball_hr==$Score2){$mb_inball_hr='-2';$tg_inball_hr='-2';}
					if ($tg_inball==$Score3){$mb_inball='-3';$tg_inball='-3';}
					if ($tg_inball_hr==$Score3){$mb_inball_hr='-3';$tg_inball_hr='-3';}
					if ($tg_inball==$Score4){$mb_inball='-4';$tg_inball='-4';}
					if ($tg_inball_hr==$Score4){$mb_inball_hr='-4';$tg_inball_hr='-4';}
					if ($tg_inball==$Score5){$mb_inball='-5';$tg_inball='-5';}
					if ($tg_inball_hr==$Score5){$mb_inball_hr='-5';$tg_inball_hr='-5';}
					if ($tg_inball==$Score6){$mb_inball='-6';$tg_inball='-6';}
					if ($tg_inball_hr==$Score6){$mb_inball_hr='-6';$tg_inball_hr='-6';}
					if ($tg_inball=='赛事无pk/加时'){$mb_inball='-7';$tg_inball='-7';}
					if ($tg_inball_hr=='赛事无pk/加时'){$mb_inball_hr='-7';$tg_inball_hr='-7';}
					if ($tg_inball==$Score8){$mb_inball='-8';$tg_inball='-8';}
					if ($tg_inball_hr==$Score8){$mb_inball_hr='-8';$tg_inball_hr='-8';}
					//if ($tg_inball==$Score9){$mb_inball='-9';$tg_inball='-9';}
					//if ($tg_inball_hr==$Score9){$mb_inball_hr='-9';$tg_inball_hr='-9';}
					if ($tg_inball==$Score10){$mb_inball='-10';$tg_inball='-10';}
					if ($tg_inball_hr==$Score10){$mb_inball_hr='-10';$tg_inball_hr='-10';}
					if ($tg_inball==$Score11){$mb_inball='-11';$tg_inball='-11';}
					if ($tg_inball_hr==$Score11){$mb_inball_hr='-11';$tg_inball_hr='-11';}
					if ($tg_inball==$Score12){$mb_inball='-12';$tg_inball='-12';}
					if ($tg_inball_hr==$Score12){$mb_inball_hr='-12';$tg_inball_hr='-12';}
					if ($tg_inball==$Score13){$mb_inball='-13';$tg_inball='-13';}
					if ($tg_inball_hr==$Score13){$mb_inball_hr='-13';$tg_inball_hr='-13';}
					/* 暂时取消掉此类情况
					if ($tg_inball==$Score14){$mb_inball='-14';$tg_inball='-14';}
					if ($tg_inball_hr==$Score14){$mb_inball_hr='-14';$tg_inball_hr='-14';}
					if ($tg_inball==$Score15){$mb_inball='-15';$tg_inball='-15';}
					if ($tg_inball_hr==$Score15){$mb_inball_hr='-15';$tg_inball_hr='-15';}
					if ($tg_inball==$Score16){$mb_inball='-16';$tg_inball='-16';}
					if ($tg_inball_hr==$Score16){$mb_inball_hr='-16';$tg_inball_hr='-16';}
					if ($tg_inball==$Score17){$mb_inball='-17';$tg_inball='-17';}
					if ($tg_inball_hr==$Score17){$mb_inball_hr='-17';$tg_inball_hr='-17';}
					if ($tg_inball==$Score18){$mb_inball='-18';$tg_inball='-18';}
					if ($tg_inball_hr==$Score18){$mb_inball_hr='-18';$tg_inball_hr='-18';}
					if ($tg_inball==$Score19){$mb_inball='-19';$tg_inball='-19';}
					if ($tg_inball_hr==$Score19){$mb_inball_hr='-19';$tg_inball_hr='-19';}
					*/
					//MB_Inball_Time       text          utf8_general_ci  YES             (NULL)                       select,insert,update,references  主队进球时间                               
					//TG_Inball_Time       text          utf8_general_ci  YES             (NULL)                       select,insert,update,references  客队进球时间 

					$sql="select MID,MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR,M_Start,Score_Source,M_League,TG_Team,MB_Team from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='FT' and  M_Date='".$list_date."' and  M_Start='".$data['M_Start']."' and M_League='".$data["M_League"]."' and MB_Team='".$data["MB_Team"]."' and TG_Team='".$data['TG_Team']."' and MB_Inball='' and TG_Inball=''";
//					echo $sql;
					$result = mysqli_query($dbLink,$sql);
					$mcou = mysqli_num_rows($result);

					if($mcou >0 ){
                        $row = mysqli_fetch_assoc($result);
						if( $row['MB_Inball']<0 || $row['TG_Inball']<0 || $row['MB_Inball_HR']<0 || $row['TG_Inball_HR']<0 ){ continue; }//如果其中有一项小于0则认为管理员已做赛事处理，跳出当前赛事，继续下一个赛事
                        if( $row['Score_Source']>0){ continue; }//如果悠久乐、皇冠或管理员已经处理过，则跳出当前赛事，继续下一个赛事

						$MB_Inball_O = $row['MB_Inball'];
						$TG_Inball_O = $row['TG_Inball'];
						$MB_Inball_HR_O = $row['MB_Inball_HR'];
						$TG_Inball_HR_O = $row['TG_Inball_HR'];

						$updateArr=array();
						if( $mb_inball != $MB_Inball_O and $mb_inball != '' and is_numeric($mb_inball)){
						//if( $mb_inball != $MB_Inball_O && ($mb_inball > 0 || $mb_inball===0 || $mb_inball < 0) ){
							$updateArr['MB_Inball'] = $mb_inball;
						} 
						if( $tg_inball != $TG_Inball_O and $tg_inball != '' and is_numeric($tg_inball)){
						//if( $tg_inball != $TG_Inball_O && ($tg_inball > 0 || $tg_inball===0 || $tg_inball < 0) ){
							$updateArr['TG_Inball'] = $tg_inball;
						}

						//金宝博 半场
//                        if( $mb_inball_hr != $MB_Inball_HR_O && $mb_inball_hr < 0 ){
						if( $mb_inball_hr != $MB_Inball_HR_O and $mb_inball_hr != '' and is_numeric($mb_inball_hr)){
							$updateArr['MB_Inball_HR'] = $mb_inball_hr;
						}
//                        if( $tg_inball_hr != $TG_Inball_HR_O && $tg_inball_hr < 0 ){
						if( $tg_inball_hr != $TG_Inball_HR_O and $tg_inball_hr != '' and is_numeric($tg_inball_hr)){
							$updateArr['TG_Inball_HR'] = $tg_inball_hr;
						}
//                        print_r($updateArr);
						if( count($updateArr)==0 ){ continue; }
						$tmp=array();
						foreach($updateArr as $key=>$val){
							if( ( $key=='MB_Inball' || $key=='TG_Inball' ) && $val >= 0 ){
									$tmp[]=$key.'=\''.$val.'\'';
							}else{
								$tmp[]=$key.'=\''.$val.'\'';
							}
						}
						if( count($tmp)==0 ){ continue; }
						if( $mb_inball<0 || $tg_inball<0 || $mb_inball_hr<0 || $tg_inball_hr<0 ){
							$mysql3="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set ".implode(',',$tmp).",Cancel=1,Score_Source=4 where Type='FT' and M_Date='".$list_date."' and  M_Start='".$data['M_Start']."' and M_League='".$data["M_League"]."' and MB_Team='".$data["MB_Team"]."' and TG_Team='".$data['TG_Team']."' ";
                            //echo $mysql;
							mysqli_query($dbMasterLink, $mysql3) or die('abc');
					    }else{
                            $a =	$row['MB_Inball'].$row['TG_Inball'].$row['MB_Inball_HR'].$row['TG_Inball_HR'];
                            $b =	trim($mb_inball).trim($tg_inball).trim($mb_inball_hr).trim($tg_inball_hr);
                            $sum_of_score = $mb_inball+$tg_inball+$mb_inball_hr+$tg_inball_hr;
                            if ($sum_of_score>=0){
							if(strcmp($a,$b)!=0 && $row['Score_Source']==0){
								$check=0;
								$score_Source=0;
								if($row['MB_Inball']!='' && trim($mb_inball)!=$row['MB_Inball'])	{$check=1; $score_Source=4;}
								if($row['TG_Inball']!='' && trim($tg_inball)!=$row['TG_Inball'])	{$check=1; $score_Source=4;}
                                if($row['MB_Inball_HR']!='' && trim($mb_inball_hr)!=$row['MB_Inball_HR'])	$check=1;
                                if($row['TG_Inball_HR']!='' && trim($tg_inball_hr)!=$row['TG_Inball_HR'])	$check=1;


                                    // 半场结算和全场结算分开结算
                                    // 半场结算，如果半场比分的字段为空，且全场拉取的比分同时也为空，说明是第一次结算半场，则更新半场比分
                                    if ($row['MB_Inball_HR']=='' && $row['TG_Inball_HR']=='' && $mb_inball=='' && $tg_inball==''){
                                        $mysql1="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set ".implode(',',$tmp).",Checked='".$check."',Score_Source=$score_Source where Type='FT' and M_Date='".$list_date."' and  M_Start='".$data['M_Start']."' and M_League='".$row['M_League']."' and TG_Team='".$row['TG_Team']."' and MB_Team='".$row['MB_Team']."' and MB_Inball='' and TG_Inball=''";
                                        //@error_log('11'.$mysql1.PHP_EOL, 3, '/tmp/score_ft_from_jbb.log');
                                        mysqli_query($dbMasterLink, $mysql1) or die('abc');

                                        $sql="select MID,M_League,TG_Team,MB_Team from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='FT' and  M_Date='".$list_date."' and  M_Start='".$data['M_Start']."' and M_League='".$row['M_League']."' and TG_Team='".$row['TG_Team']."' and MB_Team='".$row['MB_Team']."'";
                                        $result = mysqli_query($dbMasterLink,$sql);
                                        while ($row = mysqli_fetch_assoc($result)){
                                            $mid_m = $row['MID'];
                                            $redisObj->pushMessage('MatchScorefinishList',(int)$mid_m);//加入派奖队列
                                            log_note('mid_m='.$mid_m."\r\n\r\n");
                                        }
                                    }
                                    else{

                                        // 判断半场是否更新，是则只更新全场，否则半场全场一起更新
                                        if ($row['MB_Inball_HR']!='' && $row['TG_Inball_HR']!=''){ // 只更新半场

                                            $updateArr2['MB_Inball'] = $updateArr['MB_Inball'];
                                            $updateArr2['TG_Inball'] = $updateArr['TG_Inball'];

                                            $tmp2=array();
                                            foreach($updateArr2 as $key=>$val){
                                                if( ( $key=='MB_Inball' || $key=='TG_Inball' ) && $val >= 0 ){
                                                    $tmp2[]=$key.'=\''.$val.'\'';
                                                }else{
                                                    $tmp2[]=$key.'=\''.$val.'\'';
                                                }
                                            }

                                            //@error_log('tmp2'.serialize($tmp2).PHP_EOL, 3, '/tmp/score_ft_from_jbb.log');

                                            $mysql2="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set ".implode(',',$tmp2).",Checked='".$check."',Score_Source=$score_Source where Type='FT' and M_Date='".$list_date."' and  M_Start='".$data['M_Start']."' and M_League='".$row['M_League']."' and TG_Team='".$row['TG_Team']."' and MB_Team='".$row['MB_Team']."' and MB_Inball='' and TG_Inball=''";
                                            //@error_log('22'.$mysql2.PHP_EOL, 3, '/tmp/score_ft_from_jbb.log');
                                            mysqli_query($dbMasterLink, $mysql2) or die('abc');

                                            $sql="select MID,M_League,TG_Team,MB_Team from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='FT' and  M_Date='".$list_date."' and  M_Start='".$data['M_Start']."' and M_League='".$row['M_League']."' and TG_Team='".$row['TG_Team']."' and MB_Team='".$row['MB_Team']."'";
                                            $result = mysqli_query($dbMasterLink,$sql);
                                            while ($row = mysqli_fetch_assoc($result)){
                                                $mid_m = $row['MID'];
                                                $redisObj->pushMessage('MatchScorefinishList',(int)$mid_m);//加入派奖队列
                                                log_note('mid_m='.$mid_m."\r\n\r\n");
                                            }

                                        }
                                        else{ // 半场、全场一起更新

                                            $mysql2="update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set ".implode(',',$tmp).",Checked='".$check."',Score_Source=$score_Source where Type='FT' and M_Date='".$list_date."' and  M_Start='".$data['M_Start']."' and M_League='".$row['M_League']."' and TG_Team='".$row['TG_Team']."' and MB_Team='".$row['MB_Team']."' and MB_Inball='' and TG_Inball=''";
                                            mysqli_query($dbMasterLink, $mysql2) or die('abc');

                                            $sql="select MID,M_League,TG_Team,MB_Team from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='FT' and  M_Date='".$list_date."' and  M_Start='".$data['M_Start']."' and M_League='".$row['M_League']."' and TG_Team='".$row['TG_Team']."' and MB_Team='".$row['MB_Team']."'";
                                            $result = mysqli_query($dbMasterLink,$sql);
                                            while ($row = mysqli_fetch_assoc($result)){
                                                $mid_m = $row['MID'];
                                                $redisObj->pushMessage('MatchScorefinishList',(int)$mid_m);//加入派奖队列
                                                log_note('mid_m='.$mid_m."\r\n\r\n");
                                            }
                                        }

                                    }

							}
                            }
						}
						$m=$m+1;
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
    $file = $dir."/score_ft_from_jbb_paijiang_note".date("ymd").".txt";
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
        " class='btn-toggle'",
        '<div class=rt-sub rt-data-hide>',
        '<table class=rt border=0 cellspacing=0 cellpadding=0>',
        '<colgroup>',
        '<col width=66 />',
        '<col width=300/>',
        '<col width=166 />',
        '<col width=167 />',
        '</colgroup>',
        '<!--Fix IE 9 first row with colspan bug: clarify the col-->',
        '<tbody class=empty-row>',
        '<tr>',
        '<td></td>',
        '<td></td>',
        '<td></td>',
        '<td></td>',
        '</tr>',
        "\r\n\r\n",
    );
    $b = array(
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

//	print_r($msg); die;

	return $msg;
}

echo '<br>目前比分以结算出'.$m;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>金宝博足球接比分</title>
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
