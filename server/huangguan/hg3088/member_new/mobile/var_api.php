<?php
/**
 * /var_api.php  体育接口
 *
 * @param  type   FT 足球，BK 篮球
 * @param  more   s 今日赛事， r 滚球
 */

include_once('include/config.inc.php');

require ("include/curl_http.php");
//if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
//    exit(json_encode( [ 'err'=>'-1','msg'=>'请重新登录' ] ) );
//}
$langx=$_SESSION['Language']?$_SESSION['Language']:'zh-cn';
$uid=$_SESSION['Oid'];
/*$sql = "select ID,UserName as uname,Pay_Type,Status from ".DBPREFIX.MEMBERTABLE." where oid='$uid' and Status<2";

$result = mysqli_query($dbLink,$sql);
$row=mysqli_fetch_array($result);
$cou=mysqli_num_rows($result);
if($cou==0){
    exit("请重新登录");
}
$open=$row['OpenType'];
$memname=$row['UserName'];
$credit=$row['Money'];*/

$type = $_REQUEST['type'];
$more = $_REQUEST['more'];
$m_date = date('Y-m-d');
$now = date('Y-m-d H:i:s');

$redisObj = new Ciredis();

switch ($type){
    case 'FU':// 足球早盘
    	$returnData = $redisObj->getSimpleOne("FUTURE_R");
    	$aData = json_decode($returnData,true);
		$aData2=[];
        foreach ($aData as $k => $v){
            $aData2[$k]['MID']=$v['MID'];
            $aData2[$k]['MB_Team']=$v['MB_Team'];
            $aData2[$k]['M_League']=$v['M_League'];
            $aData2[$k]['M_Time']=$v['M_Time'];
            $aData2[$k]['Ror']=$v['Ror'];
            $aData2[$k]['TG_Team']=$v['TG_Team'];
            $aData2[$k]['Type']="FU";
        }
        // 联赛中的比赛按照队伍名称归类 MID
        foreach ($aData2 as $k => $v){

            // 一次性检查下面的5个盘口是否相同
            if($v['M_League'] == $aData2[$k-1]['M_League'] && $v['MB_Team'] == $aData2[$k-1]['MB_Team'] && $v['TG_Team'] == $aData2[$k-1]['TG_Team']){

                $aData2[$k-1]['MID'] = $aData2[$k-1]['MID'].','.$v['MID'];
                unset($aData2[$k]);


                if($v['M_League'] == $aData2[$k+1]['M_League'] && $v['MB_Team'] == $aData2[$k+1]['MB_Team'] && $v['TG_Team'] == $aData2[$k+1]['TG_Team']){
                    $aData2[$k-1]['MID'] = $aData2[$k-1]['MID'].','.$aData2[$k+1]['MID'];
                    unset($aData2[$k+1]);
                }
                if($v['M_League'] == $aData2[$k+2]['M_League'] && $v['MB_Team'] == $aData2[$k+2]['MB_Team'] && $v['TG_Team'] == $aData2[$k+2]['TG_Team']){
                    $aData2[$k-1]['MID'] = $aData2[$k-1]['MID'].','.$aData2[$k+2]['MID'];
                    unset($aData2[$k+2]);
                }

                if($v['M_League'] == $aData2[$k+3]['M_League'] && $v['MB_Team'] == $aData2[$k+3]['MB_Team'] && $v['TG_Team'] == $aData2[$k+3]['TG_Team']){
                    $aData2[$k-1]['MID'] = $aData2[$k-1]['MID'].','.$aData2[$k+3]['MID'];
                    unset($aData2[$k+3]);
                }

                if($v['M_League'] == $aData2[$k+4]['M_League'] && $v['MB_Team'] == $aData2[$k+4]['MB_Team'] && $v['TG_Team'] == $aData2[$k+4]['TG_Team']){
                    $aData2[$k-1]['MID'] = $aData2[$k-1]['MID'].','.$aData2[$k+4]['MID'];
                    unset($aData2[$k+4]);
                }

            }

        }
        $cou=count($aData2);
        break;

    case 'FT':
        switch ($more){
            case 's': // 足球今日赛事
            	$returnData = $redisObj->getSimpleOne("TODAY_FT_M_ROU_EO");
    			$aData = json_decode($returnData,true) ;
            	/*
                $mysql="select MID,Type,M_Time,M_Type,MB_MID,TG_MID,MB_Team,TG_Team,M_League,ShowTypeR,MB_Win_Rate,TG_Win_Rate,M_Flat_Rate,M_LetB,MB_LetB_Rate,TG_LetB_Rate,MB_Dime,TG_Dime,MB_Dime_Rate,TG_Dime_Rate,S_Single_Rate,S_Double_Rate,ShowTypeHR,M_LetB_H,MB_LetB_Rate_H,TG_LetB_Rate_H,MB_Dime_H,TG_Dime_H,MB_Dime_Rate_H,TG_Dime_Rate_H,MB_Win_Rate_H,TG_Win_Rate_H,M_Flat_Rate_H,PD_Show,HPD_Show,T_Show,F_Show,Eventid,Hot,Play from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `Type`='FT' and `M_Start` > '$now' AND `M_Date` ='$m_date' and `S_Show`=1 and MB_TEAM!='' and `Open`=1 order by M_Start,M_League,MB_TEAM,MB_MID";
                $result = mysqli_query($dbLink, $mysql);
                $cou=mysqli_num_rows($result);
                $aData=[];
                while ($row=mysqli_fetch_assoc($result)){
                    $aData[] = $row;
                }*/

                $aData2=[];
                foreach ($aData as $k => $v){
                    $aData2[$k]['MID']=$v['MID'];
                    $aData2[$k]['MB_Team']=$v['MB_Team'];
                    $aData2[$k]['M_League']=$v['M_League'];
                    $aData2[$k]['M_Time']=$v['M_Time'];
                    $aData2[$k]['Ror']=$v['Ror'];
                    $aData2[$k]['TG_Team']=$v['TG_Team'];
                    $aData2[$k]['Type']="FT";
                }
                // 联赛中的比赛按照队伍名称归类 MID
                foreach ($aData2 as $k => $v){

                    // 一次性检查下面的5个盘口是否相同
                    if($v['M_League'] == $aData2[$k-1]['M_League'] && $v['MB_Team'] == $aData2[$k-1]['MB_Team'] && $v['TG_Team'] == $aData2[$k-1]['TG_Team']){

                        $aData2[$k-1]['MID'] = $aData2[$k-1]['MID'].','.$v['MID'];
                        unset($aData2[$k]);


                        if($v['M_League'] == $aData2[$k+1]['M_League'] && $v['MB_Team'] == $aData2[$k+1]['MB_Team'] && $v['TG_Team'] == $aData2[$k+1]['TG_Team']){
                            $aData2[$k-1]['MID'] = $aData2[$k-1]['MID'].','.$aData2[$k+1]['MID'];
                            unset($aData2[$k+1]);
                        }
                        if($v['M_League'] == $aData2[$k+2]['M_League'] && $v['MB_Team'] == $aData2[$k+2]['MB_Team'] && $v['TG_Team'] == $aData2[$k+2]['TG_Team']){
                            $aData2[$k-1]['MID'] = $aData2[$k-1]['MID'].','.$aData2[$k+2]['MID'];
                            unset($aData2[$k+2]);
                        }

                        if($v['M_League'] == $aData2[$k+3]['M_League'] && $v['MB_Team'] == $aData2[$k+3]['MB_Team'] && $v['TG_Team'] == $aData2[$k+3]['TG_Team']){
                            $aData2[$k-1]['MID'] = $aData2[$k-1]['MID'].','.$aData2[$k+3]['MID'];
                            unset($aData2[$k+3]);
                        }

                        if($v['M_League'] == $aData2[$k+4]['M_League'] && $v['MB_Team'] == $aData2[$k+4]['MB_Team'] && $v['TG_Team'] == $aData2[$k+4]['TG_Team']){
                            $aData2[$k-1]['MID'] = $aData2[$k-1]['MID'].','.$aData2[$k+4]['MID'];
                            unset($aData2[$k+4]);
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
					$K = 0;
					for($i=0;$i<$cou;$i++){
						$messages=$matches[$i];
						$messages=str_replace(");",")",$messages);
						$messages=str_replace("cha(9)","",$messages);
						$datainfo=eval("return $messages;");
						// var_dump($datainfo);
						$opensql = "select `Open`,`M_Time`,`M_Start` from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where  MID='$datainfo[0]' and `Type`='FT'";
						//echo $opensql;
						$openresult = mysqli_query($dbLink,$opensql);
						$openrow=mysqli_fetch_assoc($openresult);
						if ($openrow['Open']==1){
							//$sql = "update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set ShowTypeRB='$datainfo[7]',M_LetB_RB='$datainfo[8]',MB_LetB_Rate_RB='$datainfo[9]',TG_LetB_Rate_RB='$datainfo[10]',MB_Dime_RB='$datainfo[11]',TG_Dime_RB='$datainfo[12]',MB_Dime_Rate_RB='$datainfo[14]',TG_Dime_Rate_RB='$datainfo[13]',ShowTypeHRB='$datainfo[21]',M_LetB_RB_H='$datainfo[22]',MB_LetB_Rate_RB_H='$datainfo[23]',TG_LetB_Rate_RB_H='$datainfo[24]',MB_Dime_RB_H='$datainfo[25]',TG_Dime_RB_H='$datainfo[26]',MB_Dime_Rate_RB_H='$datainfo[28]',TG_Dime_Rate_RB_H='$datainfo[27]',MB_Ball='$datainfo[18]',TG_Ball='$datainfo[19]',MB_Card='$datainfo[29]',TG_Card='$datainfo[30]',MB_Red='$datainfo[31]',TG_Red='$datainfo[32]',MB_Win_Rate_RB='$datainfo[33]',TG_Win_Rate_RB='$datainfo[34]',M_Flat_Rate_RB='$datainfo[35]',MB_Win_Rate_RB_H='$datainfo[36]',TG_Win_Rate_RB_H='$datainfo[37]',M_Flat_Rate_RB_H='$datainfo[38]',Eventid='$datainfo[39]',Hot='$datainfo[40]',Play='$datainfo[41]',RB_Show=1,S_Show=0 where MID=$datainfo[0] and `Type`='FT'";
							//echo $sql;exit;
							//mysqli_query($dbMasterLink,$sql) or die("error");
							$datainfo[19]=$datainfo[19]+0;
							$datainfo[18]=$datainfo[18]+0;
							
							if (isset($datainfo[18]) && $datainfo[18]!=null){
								$aData2[$K]['MB_Ball']=$datainfo[18];
							}else{
								$aData2[$K]['MB_Ball']=0;
							}
							$aData2[$K]['MB_MID']=$datainfo[4];
							$aData2[$K]['MB_Team']=$datainfo[5];
							$aData2[$K]['MID']=$datainfo[0];
							$aData2[$K]['M_League']=$datainfo[2];
							$aData2[$K]['M_Time']=$openrow['M_Time'];
							$aData2[$K]['Ror']=$v['Ror'];
							if (isset($datainfo[19]) && $datainfo[19]!=null){
								$aData2[$K]['TG_Ball']=$datainfo[19];
							}else{
								$aData2[$K]['TG_Ball']=0;
							}
							$aData2[$K]['TG_MID']=$datainfo[3];
							$aData2[$K]['TG_Team']=$datainfo[6];
							$aData2[$K]['Type']=$type;
							// 46||1H^46:00
							$minute=floor((time()-strtotime($openrow['M_Start']))%86400/60);
							$aData2[$K]['now_play']=$minute.'||'.$datainfo[48];
							// 2H^26:23
							$aNowplay = explode('^',$datainfo[48]);
							$aMinuteSecond = explode(':',$aNowplay[1]);
							if(!strpos($aMinuteSecond[0],"半场")){
								$aData2[$K]['minute'] = $aMinuteSecond[0];
							}else{
								$aData2[$K]['minute'] = "";
							}

							$K=$K+1;
						}
					}

					// 联赛中的比赛按照队伍名称归类 MID
					foreach ($aData2 as $k => $v){

						// 一次性检查下面的5个盘口是否相同
						if($v['M_League'] == $aData2[$k-1]['M_League'] && $v['MB_Team'] == $aData2[$k-1]['MB_Team'] && $v['TG_Team'] == $aData2[$k-1]['TG_Team']){

							$aData2[$k-1]['MID'] = $aData2[$k-1]['MID'].','.$v['MID'];
							unset($aData2[$k]);


							if($v['M_League'] == $aData2[$k+1]['M_League'] && $v['MB_Team'] == $aData2[$k+1]['MB_Team'] && $v['TG_Team'] == $aData2[$k+1]['TG_Team']){
								$aData2[$k-1]['MID'] = $aData2[$k-1]['MID'].','.$aData2[$k+1]['MID'];
								unset($aData2[$k+1]);
							}
							if($v['M_League'] == $aData2[$k+2]['M_League'] && $v['MB_Team'] == $aData2[$k+2]['MB_Team'] && $v['TG_Team'] == $aData2[$k+2]['TG_Team']){
								$aData2[$k-1]['MID'] = $aData2[$k-1]['MID'].','.$aData2[$k+2]['MID'];
								unset($aData2[$k+2]);
							}

							if($v['M_League'] == $aData2[$k+3]['M_League'] && $v['MB_Team'] == $aData2[$k+3]['MB_Team'] && $v['TG_Team'] == $aData2[$k+3]['TG_Team']){
								$aData2[$k-1]['MID'] = $aData2[$k-1]['MID'].','.$aData2[$k+3]['MID'];
								unset($aData2[$k+3]);
							}

							if($v['M_League'] == $aData2[$k+4]['M_League'] && $v['MB_Team'] == $aData2[$k+4]['MB_Team'] && $v['TG_Team'] == $aData2[$k+4]['TG_Team']){
								$aData2[$k-1]['MID'] = $aData2[$k-1]['MID'].','.$aData2[$k+4]['MID'];
								unset($aData2[$k+4]);
							}
						}
					}
					$reBallCountCur = $cou;
					break;
				}
                break;	//----------------------------足球滚球
        }
        break;	//----------------------------整个足球

    case 'BU':// 篮球早盘
    	$returnData = $redisObj->getSimpleOne("FUTURE_BK_ALL");
    	$aData = json_decode($returnData,true);
    	/*
        $mysql = "select MID,Type,M_Time,M_Date,M_Type,MB_MID,TG_MID,MB_Team,TG_Team,M_League,ShowTypeR,MB_Win_Rate,TG_Win_Rate,M_Flat_Rate,M_LetB,MB_LetB_Rate,TG_LetB_Rate,MB_Dime,TG_Dime,MB_Dime_Rate,TG_Dime_Rate,S_Single_Rate,S_Double_Rate,ShowTypeHR,M_LetB_H,MB_LetB_Rate_H,TG_LetB_Rate_H,MB_Dime_H,MB_Dime_S_H,TG_Dime_H,TG_Dime_S_H,MB_Dime_Rate_H,MB_Dime_Rate_S_H,TG_Dime_Rate_H,TG_Dime_Rate_S_H,MB_Win_Rate_H,TG_Win_Rate_H,M_Flat_Rate_H,PD_Show,HPD_Show,T_Show,F_Show,more,Eventid,Hot,Play from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BU' and `M_Date` >'$m_date' and S_Show=1 and `MB_Team`!='' ".$date." order by M_Start,MB_Team,MB_MID";
        $result = mysqli_query($dbMasterLink, $mysql);
        $cou=mysqli_num_rows($result);
        while ($row=mysqli_fetch_assoc($result)){
            $aData[] = $row;
        }*/
        $aData2=[];
        foreach ($aData as $k => $v){
            $aData2[$k]['MID']=$v['MID'];
            $aData2[$k]['MB_Team']=$v['MB_Team'];
            $aData2[$k]['M_League']=$v['M_League'];
            $aData2[$k]['M_Time']=$v['M_Time'];
            $aData2[$k]['Ror']=$v['Ror'];
            $aData2[$k]['TG_Team']=$v['TG_Team'];
            $aData2[$k]['Type']="BU";
        }
        // 联赛中的比赛按照队伍名称归类 MID
        foreach ($aData2 as $k => $v){

            // 一次性检查下面的5个盘口是否相同
            if($v['M_League'] == $aData2[$k-1]['M_League'] && $v['MB_Team'] == $aData2[$k-1]['MB_Team'] && $v['TG_Team'] == $aData2[$k-1]['TG_Team']){

                $aData2[$k-1]['MID'] = $aData2[$k-1]['MID'].','.$v['MID'];
                unset($aData2[$k]);


                if($v['M_League'] == $aData2[$k+1]['M_League'] && $v['MB_Team'] == $aData2[$k+1]['MB_Team'] && $v['TG_Team'] == $aData2[$k+1]['TG_Team']){
                    $aData2[$k-1]['MID'] = $aData2[$k-1]['MID'].','.$aData2[$k+1]['MID'];
                    unset($aData2[$k+1]);
                }
                if($v['M_League'] == $aData2[$k+2]['M_League'] && $v['MB_Team'] == $aData2[$k+2]['MB_Team'] && $v['TG_Team'] == $aData2[$k+2]['TG_Team']){
                    $aData2[$k-1]['MID'] = $aData2[$k-1]['MID'].','.$aData2[$k+2]['MID'];
                    unset($aData2[$k+2]);
                }

                if($v['M_League'] == $aData2[$k+3]['M_League'] && $v['MB_Team'] == $aData2[$k+3]['MB_Team'] && $v['TG_Team'] == $aData2[$k+3]['TG_Team']){
                    $aData2[$k-1]['MID'] = $aData2[$k-1]['MID'].','.$aData2[$k+3]['MID'];
                    unset($aData2[$k+3]);
                }

                if($v['M_League'] == $aData2[$k+4]['M_League'] && $v['MB_Team'] == $aData2[$k+4]['MB_Team'] && $v['TG_Team'] == $aData2[$k+4]['TG_Team']){
                    $aData2[$k-1]['MID'] = $aData2[$k-1]['MID'].','.$aData2[$k+4]['MID'];
                    unset($aData2[$k+4]);
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
				/*
                $mysql = "select MID,M_Time,M_Type,MB_MID,TG_MID,MB_Team,TG_Team,M_League,ShowTypeR,MB_Win_Rate,TG_Win_Rate,M_Flat_Rate,M_LetB,MB_LetB_Rate,TG_LetB_Rate,MB_Dime,TG_Dime,MB_Dime_Rate,TG_Dime_Rate,S_Single_Rate,S_Double_Rate,ShowTypeHR,M_LetB_H,MB_LetB_Rate_H,TG_LetB_Rate_H,MB_Dime_H,MB_Dime_S_H,TG_Dime_H,TG_Dime_S_H,MB_Dime_Rate_H,MB_Dime_Rate_S_H,TG_Dime_Rate_H,TG_Dime_Rate_S_H,MB_Win_Rate_H,TG_Win_Rate_H,M_Flat_Rate_H,PD_Show,HPD_Show,T_Show,F_Show,Eventid,Hot,Play from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and  `M_Start` > '$now' AND `M_Date` ='$m_date' and S_Show=1 and MB_TEAM!='' order by M_Start,MB_MID";
                $result = mysqli_query($dbLink, $mysql);
                $cou=mysqli_num_rows($result);
                $aData=[];
                while ($row=mysqli_fetch_assoc($result)){
                    $aData[] = $row;
                }*/

                $aData2=[];
                foreach ($aData as $k => $v){
                    $aData2[$k]['MID']=$v['MID'];
                    $aData2[$k]['MB_Team']=$v['MB_Team'];
                    $aData2[$k]['M_League']=$v['M_League'];
                    $aData2[$k]['M_Time']=$v['M_Time'];
                    $aData2[$k]['Ror']=$v['Ror'];
                    $aData2[$k]['TG_Team']=$v['TG_Team'];
                    $aData2[$k]['Type']=$type;
                }
                // 联赛中的比赛按照队伍名称归类 MID
                foreach ($aData2 as $k => $v){

                    // 一次性检查下面的5个盘口是否相同
                    if($v['M_League'] == $aData2[$k-1]['M_League'] && $v['MB_Team'] == $aData2[$k-1]['MB_Team'] && $v['TG_Team'] == $aData2[$k-1]['TG_Team']){

                        $aData2[$k-1]['MID'] = $aData2[$k-1]['MID'].','.$v['MID'];
                        unset($aData2[$k]);


                        if($v['M_League'] == $aData2[$k+1]['M_League'] && $v['MB_Team'] == $aData2[$k+1]['MB_Team'] && $v['TG_Team'] == $aData2[$k+1]['TG_Team']){
                            $aData2[$k-1]['MID'] = $aData2[$k-1]['MID'].','.$aData2[$k+1]['MID'];
                            unset($aData2[$k+1]);
                        }
                        if($v['M_League'] == $aData2[$k+2]['M_League'] && $v['MB_Team'] == $aData2[$k+2]['MB_Team'] && $v['TG_Team'] == $aData2[$k+2]['TG_Team']){
                            $aData2[$k-1]['MID'] = $aData2[$k-1]['MID'].','.$aData2[$k+2]['MID'];
                            unset($aData2[$k+2]);
                        }

                        if($v['M_League'] == $aData2[$k+3]['M_League'] && $v['MB_Team'] == $aData2[$k+3]['MB_Team'] && $v['TG_Team'] == $aData2[$k+3]['TG_Team']){
                            $aData2[$k-1]['MID'] = $aData2[$k-1]['MID'].','.$aData2[$k+3]['MID'];
                            unset($aData2[$k+3]);
                        }

                        if($v['M_League'] == $aData2[$k+4]['M_League'] && $v['MB_Team'] == $aData2[$k+4]['MB_Team'] && $v['TG_Team'] == $aData2[$k+4]['TG_Team']){
                            $aData2[$k-1]['MID'] = $aData2[$k-1]['MID'].','.$aData2[$k+4]['MID'];
                            unset($aData2[$k+4]);
                        }

                    }

                }
				$cou=count($aData2);
                break;
            case 'r':// 篮球滚球
	        		$returnData = $redisObj->getSimpleOne("BK_M_ROU_EO");
    				$matches = json_decode($returnData,true) ;
					if(is_array($matches)){
						$cou=sizeof($matches);
					}else{
						$cou=0;
					}
                
                    if($cou>0){
                        $K=0;
                        for($i=0;$i<$cou;$i++){
                            $messages=$matches[$i];
                            $messages=str_replace(");",")",$messages);
                            $messages=str_replace("cha(9)","",$messages);
                            $datainfo=eval("return $messages;");
                            //var_dump($datainfo) ;
                            $opensql = "select `Open`,`M_Time`,`M_Start` from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where Type='BK' and  MID='$datainfo[0]'";
                            $openresult = mysqli_query($dbLink,$opensql);
                            $openrow=mysqli_fetch_assoc($openresult);
                            if($openrow['Open']==1){
                                //$sql = "update `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` set MB_Ball='$datainfo[53]',TG_Ball='$datainfo[54]',ShowTypeRB='$datainfo[7]',M_LetB_RB='$datainfo[8]',MB_LetB_Rate_RB='$datainfo[9]',TG_LetB_Rate_RB='$datainfo[10]',MB_Dime_RB='$datainfo[11]',TG_Dime_RB='$datainfo[12]',MB_Dime_Rate_RB='$datainfo[14]',TG_Dime_Rate_RB='$datainfo[13]',ShowTypeHRB='$datainfo[21]',M_LetB_RB_H='$datainfo[22]',MB_LetB_Rate_RB_H='$datainfo[23]',TG_LetB_Rate_RB_H='$datainfo[24]',MB_Dime_RB_H='$datainfo[35]',MB_Dime_RB_S_H='$datainfo[36]',TG_Dime_RB_H='$datainfo[39]',TG_Dime_RB_S_H='$datainfo[40]',MB_Dime_Rate_RB_H='$datainfo[37]',MB_Dime_Rate_RB_S_H='$datainfo[38]',TG_Dime_Rate_RB_H='$datainfo[41]',TG_Dime_Rate_RB_H='$datainfo[42]',Eventid='$datainfo[39]',Hot='$datainfo[40]',Play='$datainfo[41]',RB_Show=1,S_Show=0 where MID=$datainfo[0] and `Type`='BK'";
                                //mysqli_query($dbMasterLink,$sql) or die("error");

                                // $datainfo[52] 球队名称 Q1-Q4 第一节-第四节，H1 上半场，H2 下半场 ，OT 加时，HT 半场
                                $team_active='' ;
                                switch ($datainfo[52]) {
                                    case 'Q1':
                                        $team_active ='第一节';
                                        break;
                                    case 'Q2':
                                        $team_active ='第二节';
                                        break;
                                    case 'Q3':
                                        $team_active ='第三节';
                                        break;
                                    case 'Q4':
                                        $team_active ='第四节';
                                        break;
                                    case 'H1':
                                        $team_active ='上半场';
                                        break;
                                    case 'H2':
                                        $team_active ='下半场';
                                        break;
                                    case 'OT':
                                        $team_active ='加时';
                                        break;
                                    case 'HT':
                                        $team_active ='半场';
                                        break;

                                }
                                $team_time ='';
                                if($datainfo[56] && $datainfo[56] > 0){ // 转化时间
                                    $team_hour = floor($datainfo[56]/3600); // 小时不要
                                    $team_minute = floor(($datainfo[56]-3600 * $team_hour)/60);
                                    $team_second = floor((($datainfo[56]-3600 * $team_hour) - 60 * $team_minute) % 60);
                                    $team_time = ($team_minute>9?$team_minute:"0".$team_minute).':'.($team_second>9?$team_second:"0".$team_second );
                                }
                                // 球队名称处理
                                $datainfo_team = $team_active."<span class=\"rb_time_color\">".$team_time."</span>" ;
                                // 比分处理
                                $datainfo_score = " $datainfo[53]-<span style=\"color:#FF0000\">$datainfo[54]</span>";

                                // 全场滚球独赢主队 $datainfo[29]   全场滚球独赢客队 $datainfo[30]
                               /* echo "parent.GameFT[$K]=new Array('$datainfo[0]','$datainfo[1]','$datainfo[2]','$datainfo[3]','$datainfo[4]','$datainfo[5]','$datainfo[6]','$datainfo[7]','$datainfo[8]','$datainfo[9]','$datainfo[10]','$datainfo[11]','$datainfo[12]','$datainfo[13]','$datainfo[14]','$datainfo[15]','$datainfo[16]',
		                    '$datainfo[35]','$datainfo[36]','$datainfo[37]','$datainfo[38]',
		                    '$datainfo[39]','$datainfo[40]','$datainfo[41]','$datainfo[42]',
		                    '$datainfo[25]','$datainfo[26]','$datainfo[27]','$datainfo[28]','$datainfo[29]','$datainfo[30]','$datainfo_team','$datainfo_score','$datainfo[31]','$datainfo[32]','$datainfo[33]');\n";*/


                                $aData2[$K]['MB_Ball']=$datainfo[53]; // 篮球滚球主队进球数
                                $aData2[$K]['MB_MID']=$datainfo[4];
                                $aData2[$K]['MB_Team']=$datainfo[5];
                                $aData2[$K]['MID']=$datainfo[0];
                                $aData2[$K]['MB_Team']=$datainfo[5];
                                $aData2[$K]['M_League']=$datainfo[2];
                                $aData2[$K]['M_Time']=$openrow['M_Time'];
                                $aData2[$K]['Ror']=$v['Ror'];
                                $aData2[$K]['TG_Ball']=$datainfo[54]; // 篮球滚球客队进球数
                                $aData2[$K]['TG_MID']=$datainfo[3];
                                $aData2[$K]['TG_Team']=$datainfo[6];
                                $aData2[$K]['Type']=$type;
                                // 46||1H^46:00
                                $minute=floor((time()-strtotime($openrow['M_Start']))%86400/60);
                                $aData2[$K]['now_play']=$datainfo['52'];
                                $team_time ='';
                                if($datainfo[56] && $datainfo[56] > 0){ // 转化时间
                                    $team_hour = floor($datainfo[56]/3600); // 小时不要
                                    $team_minute = floor(($datainfo[56]-3600 * $team_hour)/60);
                                    $team_second = floor((($datainfo[56]-3600 * $team_hour) - 60 * $team_minute) % 60); // 秒数不要
                                    $team_time = $team_minute;
                                }
                                $aData2[$K]['minute'] = $team_minute;
                                $K=$K+1;
                            }
                        }

                        // 联赛中的比赛按照队伍名称归类 MID
                        foreach ($aData2 as $k => $v){

                            // 一次性检查下面的5个盘口是否相同
                            if($v['M_League'] == $aData2[$k-1]['M_League'] && $v['MB_Team'] == $aData2[$k-1]['MB_Team'] && $v['TG_Team'] == $aData2[$k-1]['TG_Team']){

                                $aData2[$k-1]['MID'] = $aData2[$k-1]['MID'].','.$v['MID'];
                                unset($aData2[$k]);

                                if($v['M_League'] == $aData2[$k+1]['M_League'] && $v['MB_Team'] == $aData2[$k+1]['MB_Team'] && $v['TG_Team'] == $aData2[$k+1]['TG_Team']){
                                    $aData2[$k-1]['MID'] = $aData2[$k-1]['MID'].','.$aData2[$k+1]['MID'];
                                    unset($aData2[$k+1]);
                                }
                                if($v['M_League'] == $aData2[$k+2]['M_League'] && $v['MB_Team'] == $aData2[$k+2]['MB_Team'] && $v['TG_Team'] == $aData2[$k+2]['TG_Team']){
                                    $aData2[$k-1]['MID'] = $aData2[$k-1]['MID'].','.$aData2[$k+2]['MID'];
                                    unset($aData2[$k+2]);
                                }

                                if($v['M_League'] == $aData2[$k+3]['M_League'] && $v['MB_Team'] == $aData2[$k+3]['MB_Team'] && $v['TG_Team'] == $aData2[$k+3]['TG_Team']){
                                    $aData2[$k-1]['MID'] = $aData2[$k-1]['MID'].','.$aData2[$k+3]['MID'];
                                    unset($aData2[$k+3]);
                                }

                                if($v['M_League'] == $aData2[$k+4]['M_League'] && $v['MB_Team'] == $aData2[$k+4]['MB_Team'] && $v['TG_Team'] == $aData2[$k+4]['TG_Team']){
                                    $aData2[$k-1]['MID'] = $aData2[$k-1]['MID'].','.$aData2[$k+4]['MID'];
                                    unset($aData2[$k+4]);
                                }
                            }
                        }
                        break;
                    }
                break;
        }
        break;
    default: break;
}

if($_REQUEST['appRefer']==13 || $_REQUEST['appRefer']==14) {

    $status = '200';
    $describe = 'success';
    if (count($aData2) == 0){
        original_phone_request_response($status, $describe, []);
    }else{
        original_phone_request_response($status, $describe, $aData2);
    }

}else{

    if ($cou==0){
        echo json_encode([]);
    }else{
        echo json_encode(array_values($aData2));
    }

}
