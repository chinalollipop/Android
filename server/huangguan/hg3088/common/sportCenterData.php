<?php
/*
 * 数据中心库连接，只在用到的地方引用，
 * 防止过多请求数据中心
 * */

$dbCenterSlaveDbLink = Dbnew::getInstance('data_slave');
$dbCenterMasterDbLink  = Dbnew::getInstance('data_master');

$flushWay = SPORT_FLUSH_WAY; // 刷水渠道
$flushDoamin = SPORT_FLUSH_DOMAIN; // 刷水网址

/*
 * 6686 水源接口地址 开始
 * $type:
 * 1 足球
 * 2 篮球
 * 3 网球
 * 4 棒球
 *  */

$API_URL="/api/desktop/v1/";
// 赛事盘口数量
$SPORT_COUNT_API="menu/sportCount";
// 足球滚球更多玩法，接口拼接 id + 111968
$FT_MORESPORT_API=$flushDoamin.$API_URL."inplay/detailOdds/1/0011101101/";
// 足球今日/早盘 更多玩法，接口拼接 id + 111968
$FT_MORESPORT_TODAY_API=$flushDoamin.$API_URL."nonInplay/detailOdds/1/0011101101/";

// 足球滚球主盘口：
$FT_RB_API=$flushDoamin.$API_URL."inplay/menuOdds/1/main/main/0011101101";
// 足球滚球波胆
$FT_RB_PD_API=$flushDoamin.$API_URL."inplay/menuOdds/1/CorrectScore/CorrectScore/0011101101";
// 足球滚球综合过关分类，赛事、角球、罚牌、波胆、组合、上半场 https://cdav4-p1-g9.tixpszobaduh.miancangwuyu.com/api/desktop/v1/coupon/matchFilter/1/106/0011101101
$FT_RB_P3_MATCH_API=$flushDoamin.$API_URL."coupon/matchFilter/1/106/0011101101";
// 足球滚球综合过关 https://cdav4-p1-g9.tixpszobaduh.miancangwuyu.com/api/desktop/v1/parlay/menuOdds/coupon/1/main/main/0011101101/106
$FT_RB_P3_API=$flushDoamin.$API_URL."parlay/menuOdds/coupon/1/main/main/0011101101/106";
// 足球滚球角球，角球大小-下个角球-角球让球-角球独赢
$FT_RB_CORNERS_API=$flushDoamin.$API_URL."inplay/menuOdds/1/Corners/corners_ou,next_corner,corner_handicap,corner_1x2/0011101101";

// 足球今日联赛：
$FT_TODAY_API=$flushDoamin.$API_URL."today/competitionList/1";
// 足球今日详情 ，接口拼接 id + 111968，多个ID 以 , 分开
$FT_TODAY_SEC_API=$flushDoamin.$API_URL."today/menuOdds/1/main/main/0011101101/";
// 足球今日波胆 ，接口拼接 27938,28573，多个ID 以 , 分开
$FT_TODAY_PD_API=$flushDoamin.$API_URL."today/menuOdds/1/CorrectScore/CorrectScore/0011101101/";
// 足球今日综合过关分类，赛事、角球、罚牌、波胆、组合、上半场 https://cdav4-p1-g9.tixpszobaduh.miancangwuyu.com/api/desktop/v1/coupon/matchFilter/1/107/0011101101
$FT_TODAY_P3_MATCH_API=$flushDoamin.$API_URL."coupon/matchFilter/1/107/0011101101";
// 足球今日综合过关 https://cdav4-p1-g9.tixpszobaduh.miancangwuyu.com/api/desktop/v1/parlay/menuOdds/coupon/1/main/main/0011101101/107
$FT_TODAY_P3_API=$flushDoamin.$API_URL."parlay/menuOdds/coupon/1/main/main/0011101101/107";


// 足球早盘联赛：
$FT_FUTURE_API=$flushDoamin.$API_URL."future/competitionList/1/prestart";
// 足球早盘详情 ，接口拼接 id + 28895,36947,29360，多个ID 以 , 分开
$FT_FUTURE_SEC_API=$flushDoamin.$API_URL."future/menuOdds/1/main/main/0011101101/prestart/";
// 足球早盘波胆，接口拼接 28895,36947,29490 ，多个ID 以 , 分开
$FT_FUTURE_PD_API=$flushDoamin.$API_URL."future/menuOdds/1/CorrectScore/CorrectScore/0011101101/prestart/";
// 足球今日-足球早盘角球，角球大小【玩法多少不重要，随便什么玩法拿到角球盘口即可，方便最后结算】
$FT_TODAY_FUTURE_CORNERS_API=$flushDoamin.$API_URL."parlay/menuOdds/1/Corners/corners_ou/0011101101/";

// 足球综合过关联赛
$FT_P3_LEAGUE_API=$flushDoamin.$API_URL."parlay/competitionList/1";
// 足球综合过关根据联赛ID获取盘口，接口拼接 28895,36947,29490 ，多个ID 以 , 分开
$FT_P3_MATCH_API=$flushDoamin.$API_URL."parlay/menuOdds/1/main/main/0011101101/";

//足球冠军联赛：
$FT_FS_API=$flushDoamin.$API_URL."outright/competitionList/1";
//足球冠军详情  6686一次请求一个id 28895
$FT_FS_SEC_API=$flushDoamin.$API_URL."outright/detail/1/";


// 篮球更多玩法，接口拼接 id + 111968
$BK_MORESPORT_API=$flushDoamin.$API_URL."inplay/detailOdds/2/0011101101/";
// 蓝球今日/早盘 更多玩法，接口拼接 id + 111968
$BK_MORESPORT_TODAY_API=$flushDoamin.$API_URL."nonInplay/detailOdds/2/0011101101/";

// 篮球滚球主盘口：
$BK_RB_API=$flushDoamin.$API_URL."inplay/menuOdds/2/main/main/0011101101";
// 篮球滚球综合过关分类，赛事、队伍总分 https://cdav4-p1-g9.tixpszobaduh.miancangwuyu.com/api/desktop/v1/coupon/matchFilter/2/106/0011101101
$BK_RB_P3_MATCH_API=$flushDoamin.$API_URL."coupon/matchFilter/2/107/0011101101";
// 篮球滚球综合过关 https://cdav4-p1-g9.tixpszobaduh.miancangwuyu.com/api/desktop/v1/parlay/menuOdds/coupon/2/main/main/0011101101/106
$BK_RB_P3_API=$flushDoamin.$API_URL."parlay/menuOdds/coupon/2/main/main/0011101101/106";


// 篮球今日联赛：
$BK_TODAY_API=$flushDoamin.$API_URL."today/competitionList/2";
// 篮球今日详情 ，接口拼接 id + 111968，多个ID 以 , 分开
$BK_TODAY_SEC_API=$flushDoamin.$API_URL."today/menuOdds/2/main/main/0011101101/";
// 篮球今日综合过关分类，赛事、队伍总分、上半场、第一节 https://cdav4-p1-g9.tixpszobaduh.miancangwuyu.com/api/desktop/v1/coupon/matchFilter/2/107/0011101101
$BK_TODAY_P3_MATCH_API=$flushDoamin.$API_URL."coupon/matchFilter/2/107/0011101101";
// 篮球今日综合过关 https://cdav4-p1-g9.tixpszobaduh.miancangwuyu.com/api/desktop/v1/parlay/menuOdds/coupon/2/main/main/0011101101/107
$BK_TODAY_P3_API=$flushDoamin.$API_URL."parlay/menuOdds/coupon/2/main/main/0011101101/107";

// 篮球早盘联赛：
$BK_FUTURE_API=$flushDoamin.$API_URL."future/competitionList/2/prestart";
// 篮球早盘详情 ，接口拼接 id + 28895,36947,29360，多个ID 以 , 分开
$BK_FUTURE_SEC_API=$flushDoamin.$API_URL."future/menuOdds/2/main/main/0011101101/prestart/";

// 篮球综合过关联赛
$BK_P3_LEAGUE_API=$flushDoamin.$API_URL."parlay/competitionList/2";
// 篮球综合过关，接口拼接 28895,36947,29490 ，多个ID 以 , 分开
$BK_P3_MATCH_API=$flushDoamin.$API_URL."parlay/menuOdds/2/main/main/0011101101/";

//篮球冠军联赛：
$BK_FS_API=$flushDoamin.$API_URL."outright/competitionList/2";
//篮球冠军详情  6686一次请求一个id 28895
$BK_FS_SEC_API=$flushDoamin.$API_URL."outright/detail/2/";

/* * 6686 水源接口地址 结束 */


/**
 * 获取刷水账号
 * 返回随机账号数组
 * 每个IP仅有一条数据，其中语言有可能重复，仅用于前台
 * */
function getFlushWaterAccount(){
    global $dbCenterMasterDbLink;
    $accoutArr =$uniqueIpArray= array();
    $sql = "select ID,Datasite,Name,Uid,cookie,Ver,status from ".DATAHGPREFIX."web_getdata_account_expand where status=0";
    $result = mysqli_query($dbCenterMasterDbLink,$sql);
    while($dataCur = mysqli_fetch_assoc($result)){
        $accoutArr[$dataCur['Datasite']][] = $dataCur;
    }
    foreach($accoutArr as $key=>$val){
        $randkey=rand(0,count($val)-1);
        $uniqueIpArray[]=$val[$randkey];
    }
    return $uniqueIpArray;
}

function getDataFromInterface($langx,$gtype,$showtype,$gid,$ecid,$lid,$isrb){
    global $dbLink,$flushWay,$flushDoamin;
    global $FT_MORESPORT_API,$FT_MORESPORT_TODAY_API,$BK_MORESPORT_API,$BK_MORESPORT_TODAY_API;
    $date = date("Y-m-d");
    $showtype=$gtype.$showtype;
    $gtype=strtolower($gtype);
    switch ($showtype){
        case 'FTRB':
            $showtype='live';
            $postdata['ecid']=$ecid;
            $api_6686 = $FT_MORESPORT_API;
            break;
        case 'FTFT':
            $showtype='today';
            $postdata['ecid']=$ecid;
            $api_6686 = $FT_MORESPORT_TODAY_API;
            break;
        case 'FTFU':
            $showtype='early';
            $postdata['ecid']=$ecid;
            $api_6686 = $FT_MORESPORT_TODAY_API;
            break;
        case 'BKRB':
            $showtype='live';
            $postdata['gid']=$gid;
            $api_6686 = $BK_MORESPORT_API;
            break;
        case 'BKFT':
            $showtype='today';
            $postdata['gid']=$gid;
            $api_6686 = $BK_MORESPORT_TODAY_API;
            break;
        case 'BKFU':
            $showtype='early';
            $postdata['gid']=$gid;
            $api_6686 = $BK_MORESPORT_TODAY_API;
            break;
        default: break;
    }
    //获取抓数据账号
    $result=array();
    $accoutArr = array();
    $accoutArr=getFlushWaterAccount();//数组随机排序
    $curl = new Curl_HTTP_Client();
    $curl->store_cookies("/tmp/cookies.txt");
    $curl->set_user_agent("Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Maxthon/4.4.3.3000 Chrome/30.0.1599.101 Safari/537.36");
    if ($flushWay=='ra686'){

        //$jsonData = $curl->fetch_url("" . $flushDoamin . "/api/fn/matches/matchStatus/Live/closeSoccer/0011100100/fixtureId/".$gid."/isDetail/true/lang/zh/marketGroup/all/oddsType/HONG_KONG/orderBy/league/page/1/pageSize/30/sId/1/source/a/timezone/-4");
        if($gtype == 'bk' && strlen($gid) == 9) { //520923100 转 5209231
            $gid = substr($gid , 0, -2);
        }
        $jsonData = $curl->fetch_url($api_6686 . $gid);
        //@error_log('更多玩法: '. $api_6686 . $gid.PHP_EOL, 3, '/tmp/group/jj4.log');
        $arrData = changeFormat($jsonData, ($showtype=='live'?'Y':'N'), $gtype, false);
        /*for ($i = 0; $i < count($arrData); $i++){
            $gid_ary[$i] = $arrData[$i]['gid'];
            $tmp_Obj[$gid_ary[$i]] = $arrData[$i];
        }*/

//        $cou = count($arrData);
        $gidTmp=''; // 主盘口gid
        foreach($arrData as $key => $value) {
            if ($gtype=='ft'){
            if(isset($value['league'])) {   //主盘口
                $gidTmp = $value['gid'];
                $gid_ary[] = $gidTmp;
                $tmp_Obj[$gidTmp.'_0'] = $value;
                //$tmp_Obj[$key] = $value;
            }else{
//                $tmp_Obj[$key .'_'. $value['gid']] = $value;
                $tmp_Obj[$gidTmp.'_'.$key] = $value;
            }
            }
            else{
                $gid_ary[] = $value['gid'];
                $tmp_Obj[$value['gid']] = $value;
            }
        }

        if(isset($tmp_Obj)&&count($tmp_Obj)>0) $result['tmp_Obj']=$tmp_Obj;
        if(isset($gid_ary)&&count($gid_ary)>0) $result['gid_ary']=array_unique($gid_ary);
        $result['status']= 1;
//        $result=json_encode($result,JSON_UNESCAPED_UNICODE);
        //return $result;

    }
    else{
    foreach($accoutArr as $key=>$value){//在扩展表中获取账号重新刷水
        if($value['Uid']!='Array'){
            $uid = $value['Uid'];
            $urlRequset = $value['Datasite'];
            /*$param = array();
            $param['uid']=$value['Uid'];
            $param['langx']=$langx;
            $param['gtype']=$gtype;
            $param['showtype']=$showtype;
            $param['gid']=$gid;
            $param['ltype']=4;
            $param['date']=$date;*/
            $curl->set_referrer($value['Datasite']);

            $postdata['uid']=$uid;
            $postdata['ver']=date('Y-m-d-H').$value['Ver'];
            $postdata['langx']=$langx;
            $postdata['p']='get_game_more';
            $postdata['gtype']=$gtype;
            $postdata['showtype']=$showtype;
            $postdata['ltype']=4;
            $postdata['lid']=$lid;
            $postdata['isRB']=$isrb;

            $gameDataXml = $curl->send_post_data("".$value['Datasite']."/transform.php?ver=".date('Y-m-d-H').$value['Ver'],$postdata,"",10);
//            $gameDataXml=$curl->fetch_url($value['Datasite']."/app/member/get_game_allbets.php?&uid=".$value['Uid']."&langx=".$langx."&gtype=".$gtype."&showtype=".$showtype."&gid=".$gid."&ltype=4&date=".$date);
//            echo $value['Datasite']."/app/member/get_game_allbets.php?&uid=".$value['Uid']."&langx=".$langx."&gtype=".$gtype."&showtype=".$showtype."&gid=".$gid."&ltype=4&date=".$date;
            //echo "<br/>";
            $xml= xmlToArray(trim($gameDataXml));
//            print_r($xml); die;

            // code 足球更多玩法成功 617  ,篮球更多玩法成功返回 615
            if($xml['code']==617 || $xml['code']==615){
                // 角球所有玩法
                if (isset($xml['game']['gid'])){
                    $val = $xml['game'];
                    $result = getTmpObj($val);
                    $result = filterInvalidFields($result,($showtype=='live'?'Y':'N'), $gtype);
                }
                else{
                    foreach ($xml['game'] as $k => $val) {
                        if ($val['gopen']=='Y'){
                            $result = getTmpObj($val);
                            $result = filterInvalidFields($result,($showtype=='live'?'Y':'N'), $gtype);
                        }
                    }

                }

                // 篮球滚球到当前小节时，关闭对应的小节的盘口
                if ($gtype=='BK'){
                    $firstMid = current($result['gid_ary']);
                    $BKFirstMatch = $result['tmp_Obj'][$firstMid];
//                    if (strpos($BKFirstMatch['league'],'篮球联赛')===false){}
//                    else{
                        switch ($BKFirstMatch['se_now']){
                            case 'Q1':
                                $Q1 = $firstMid+3;
                                foreach ($result['gid_ary'] as $k => $v){
                                    if ($Q1==$v) unset($result['gid_ary'][$k]);
                                }
                                unset($result['tmp_Obj'][$Q1]);
                                break;
                            case 'Q2':
                                $H1 = $firstMid+1;
                                $Q2 = $firstMid+8;
                                foreach ($result['gid_ary'] as $k => $v){
                                    if ($H1==$v) unset($result['gid_ary'][$k]);
                                    if ($Q2==$v) unset($result['gid_ary'][$k]);
                                }
                                unset($result['tmp_Obj'][$H1]);
                                unset($result['tmp_Obj'][$Q2]);
                                break;
                            case 'Q3':
                                $Q3 = $firstMid+5;
                                foreach ($result['gid_ary'] as $k => $v){
                                    if ($Q3==$v) unset($result['gid_ary'][$k]);
                                }
                                unset($result['tmp_Obj'][$firstMid+5]);
                                break;
                            default: break;
                        }
                        $result['gid_ary']=array_values($result['gid_ary']);
//                    }
                }

                break;
            }
        }
    }
    }
    return $result;
}

//过滤返回数据中的空字段
function filterInvalidFields($val, $isGunQiu, $gType){
    //滚球用到的字段名
    $r_fieldName = array(
        array('sw_RM', 'ior_RMH', 'ior_RMC', 'ior_RMN'),//1独赢
        array('sw_HRM', 'ior_HRMH', 'ior_HRMC', 'ior_HRMN'),//2独赢上半场
        array('sw_RE', 'ior_REH', 'ior_REC', 'ratio_re'),//3让球
        array('sw_HRE', 'ior_HREH', 'ior_HREC', 'ratio_hre'),//4让球上半场
        array('sw_ROU', 'ratio_rouo', 'ratio_rouu', 'ior_ROUH', 'ior_ROUC'),//5大小
        array('sw_HROU', 'ratio_hrouo', 'ratio_hrouu', 'ior_HROUH', 'ior_HROUC'),//6大小上半场
        array('sw_REO', 'ior_REOO', 'ior_REOE'),//7单双
        array('sw_HREO', 'ior_HREOO', 'ior_HREOE'),//8单双上半场
        array('sw_RT', 'ior_RT01', 'ior_RT23', 'ior_RT46', 'ior_ROVER'),//9总进球数
        array('sw_HRT', 'ior_HRT0', 'ior_HRT1', 'ior_HRT2', 'ior_HRTOV'),//10总进球数上半场
        array('sw_RPD', 'ior_RH1C0', 'ior_RH2C0', 'ior_RH2C1', 'ior_RH3C0', 'ior_RH3C1', 'ior_RH3C2', 'ior_RH4C0', 'ior_RH4C1', 'ior_RH4C2', 'ior_RH4C3',
            'ior_RH0C0', 'ior_RH1C1', 'ior_RH2C2', 'ior_RH3C3', 'ior_RH4C4',
            'ior_RH0C1', 'ior_RH0C2', 'ior_RH1C2', 'ior_RH0C3', 'ior_RH1C3', 'ior_RH2C3', 'ior_RH0C4', 'ior_RH1C4', 'ior_RH2C4', 'ior_RH3C4', 'ior_ROVH'),//11波胆
        array('sw_HRPD', 'ior_HRH1C0','ior_HRH2C0','ior_HRH2C1','ior_HRH3C0','ior_HRH3C1','ior_HRH3C2',
            'ior_HRH0C0','ior_HRH1C1','ior_HRH2C2','ior_HRH3C3',
            'ior_HRH0C1','ior_HRH0C2','ior_HRH1C2','ior_HRH0C3','ior_HRH1C3','ior_HRH2C3', 'ior_HROVH'),//12波胆上半场
        array('sw_RF', 'ior_RFHH', 'ior_RFHN', 'ior_RFHC', 'ior_RFNH', 'ior_RFNN', 'ior_RFNC', 'ior_RFCH', 'ior_RFCN', 'ior_RFCC',),//13半场/全场
        array('sw_RTS', 'ior_RTSY', 'ior_RTSN'),//14双方球队进球
        array('sw_HRTS', 'ior_HTSY', 'ior_HTSN'),//15双方球队进球上半场
        array(''),//16第一个进球
        array('sw_RWM', 'ior_RWMH1', 'ior_RWMH2', 'ior_RWMH3', 'ior_RWMHOV', 'ior_RWMC1', 'ior_RWMC2', 'ior_RWMC3', 'ior_RWMCOV', 'ior_RWM0', 'ior_RWMN'),//17净胜球数
        array('sw_RDC', 'ior_RDCHN', 'ior_RDCCN', 'ior_RDCHC'),//18双重机会
        array('sw_RCS', 'ior_RCSH', 'ior_RCSC'),//19零失球
        array('sw_RWN', 'ior_RWNH', 'ior_RWNC'),//20零失球获胜
        array('sw_RMUA', 'ior_RMUAHO', 'ior_RMUANO', 'ior_RMUACO', 'ior_RMUAHU', 'ior_RMUANU', 'ior_RMUACU'),//21"独赢 & 进球 大/小 1.5"
        array('sw_RMUB', 'ior_RMUBHO', 'ior_RMUBNO', 'ior_RMUBCO', 'ior_RMUBHU', 'ior_RMUBNU', 'ior_RMUBCU'),//22"独赢 & 进球 大/小 2.5"
        array('sw_RMUC', 'ior_RMUCHO', 'ior_RMUCNO', 'ior_RMUCCO', 'ior_RMUCHU', 'ior_RMUCNU', 'ior_RMUCCU'),//23"独赢 & 进球 大/小 3.5"
        array('sw_RMUD', 'ior_RMUDHO', 'ior_RMUDNO', 'ior_RMUDCO', 'ior_RMUDHU', 'ior_RMUDNU', 'ior_RMUDCU'),//24"独赢 & 进球 大/小 4.5"
        array('sw_RMTS', 'ior_RMTSHY', 'ior_RMTSNY', 'ior_RMTSCY', 'ior_RMTSHN', 'ior_RMTSNN', 'ior_RMTSCN'),//25独赢&双方球队进球
        array('sw_RUTA', 'ior_RUTAOY', 'ior_RUTAON', 'ior_RUTAUY', 'ior_RUTAUN'),//26"进球 大/小 1.5 & 双方球队进球"
        array('sw_RUTB', 'ior_RUTBOY', 'ior_RUTBON', 'ior_RUTBUY', 'ior_RUTBUN'),//27"进球 大/小 1.5 & 双方球队进球"
        array('sw_RUTC', 'ior_RUTCOY', 'ior_RUTCON', 'ior_RUTCUY', 'ior_RUTCUN'),//28"进球 大/小 1.5 & 双方球队进球"
        array('sw_RUTD', 'ior_RUTDOY', 'ior_RUTDON', 'ior_RUTDUY', 'ior_RUTDUN'),//29"进球 大/小 1.5 & 双方球队进球"
        array('sw_RHG', 'ior_RHGH', 'ior_RHGC'),//30"最多进球的半场"
        array('sw_RMG', 'ior_RMGH', 'ior_RMGC', 'ior_RMGN'),//31"最多进球的半场-独赢"
        array('sw_RSB', 'ior_RSBH', 'ior_RSBC'),//32双半场进球
        array(''),//33首颗入球时间-3项
        array(''),//34首个入球时间
        array('sw_RDUA', 'ior_RDUAHO', 'ior_RDUACO', 'ior_RDUASO', 'ior_RDUAHU', 'ior_RDUACU', 'ior_RDUASU'),//35"双重机会 & 进球 大/小 1.5"
        array('sw_RDUB', 'ior_RDUBHO', 'ior_RDUBCO', 'ior_RDUBSO', 'ior_RDUBHU', 'ior_RDUBCU', 'ior_RDUBSU'),//36"双重机会 & 进球 大/小 2.5"
        array('sw_RDUC', 'ior_RDUCHO', 'ior_RDUCCO', 'ior_RDUCSO', 'ior_RDUCHU', 'ior_RDUCCU', 'ior_RDUCSU'),//37"双重机会 & 进球 大/小 3.5"
        array('sw_RDUD', 'ior_RDUDHO', 'ior_RDUDCO', 'ior_RDUDSO', 'ior_RDUDHU', 'ior_RDUDCU', 'ior_RDUDSU'),//38"双重机会 & 进球 大/小 4.5"
        array('sw_RDS', 'ior_RDSHY', 'ior_RDSCY', 'ior_RDSSY', 'ior_RDSHN', 'ior_RDSCN', 'ior_RDSSN'),//39"双重机会 & 双方球队进球"
        array('sw_RUEA', 'ior_RUEAOO', 'ior_RUEAOE', 'ior_RUEAUO', 'ior_RUEAUE'),//40"进球 大/小 1.5 & 进球数 单 / 双"
        array('sw_RUEB', 'ior_RUEBOO', 'ior_RUEBOE', 'ior_RUEBUO', 'ior_RUEBUE'),//41"进球 大/小 2.5 & 进球数 单 / 双"
        array('sw_RUEC', 'ior_RUECOO', 'ior_RUECOE', 'ior_RUECUO', 'ior_RUECUE'),//42"进球 大/小 3.5 & 进球数 单 / 双"
        array('sw_RUED', 'ior_RUEDOO', 'ior_RUEDOE', 'ior_RUEDUO', 'ior_RUEDUE'),//43"进球 大/小 4.5 & 进球数 单 / 双"
        array('sw_RWE', 'ior_RWEH', 'ior_RWEC'),//44赢得任一半场
        array('sw_RWB', 'ior_RWBH', 'ior_RWBC'),//45赢得所有半场
        array('sw_ROUH', 'ratio_rouho', 'ratio_rouhu', 'ior_ROUHO', 'ior_ROUHU'),//46球队进球数  主队  大小
        array('sw_ROUC', 'ratio_rouco', 'ratio_roucu', 'ior_ROUCO', 'ior_ROUCU'),//47球队进球数  客队  大小
        array('sw_HOUH', 'ratio_houho', 'ratio_houhu', 'ior_HOUHO', 'ior_HOUHU'),//48球队进球数  主队  大小 上半场
        array('sw_HOUC', 'ratio_houco', 'ratio_houcu', 'ior_HOUCO', 'ior_HOUCU'),//49球队进球数  客队  大小 上半场

    );
    //非滚球用到的字段名
    $nr_fieldName = array(
        array('sw_M', 'ior_MH', 'ior_MC', 'ior_MN'),//1独赢
        array('sw_HM', 'ior_HMH', 'ior_HMC', 'ior_HMN'),//2独赢上半场
        array('sw_R', 'ior_RH', 'ior_RC', 'ratio'),//3让球
        array('sw_HR', 'ior_HRH', 'ior_HRC', 'hratio'),//4让球上半场
        array('sw_OU', 'ratio_o', 'ratio_u', 'ior_OUH', 'ior_OUC'),//5大小
        array('sw_HOU', 'ratio_ho', 'ratio_hu', 'ior_HOUH', 'ior_HOUC'),//6大小上半场
        array('sw_EO', 'ior_EOO', 'ior_EOE'),//7单双
        array('sw_HEO', 'ior_HEOO', 'ior_HEOE'),//8单双上半场
        array('sw_T', 'ior_T01', 'ior_T23', 'ior_T46', 'ior_OVER'),//9总进球数
        array('sw_HT', 'ior_HT0', 'ior_HT1', 'ior_HT2', 'ior_HTOV'),//10总进球数上半场
        array('sw_PD', 'ior_H1C0', 'ior_H2C0', 'ior_H2C1', 'ior_H3C0', 'ior_H3C1', 'ior_H3C2', 'ior_H4C0', 'ior_H4C1', 'ior_H4C2', 'ior_H4C3',
            'ior_H0C0', 'ior_H1C1', 'ior_H2C2', 'ior_H3C3', 'ior_H4C4',
            'ior_H0C1', 'ior_H0C2', 'ior_H1C2', 'ior_H0C3', 'ior_H1C3', 'ior_H2C3', 'ior_H0C4', 'ior_H1C4', 'ior_H2C4', 'ior_H3C4', 'ior_OVH'),//11波胆
        array('sw_HPD', 'ior_HH1C0','ior_HH2C0','ior_HH2C1','ior_HH3C0','ior_HH3C1','ior_HH3C2',
            'ior_HH0C0','ior_HH1C1','ior_HH2C2','ior_HH3C3',
            'ior_HH0C1','ior_HH0C2','ior_HH1C2','ior_HH0C3','ior_HH1C3','ior_HH2C3', 'ior_HOVH'),//12波胆上半场
        array('sw_F', 'ior_FHH', 'ior_FHN', 'ior_FHC', 'ior_FNH', 'ior_FNN', 'ior_FNC', 'ior_FCH', 'ior_FCN', 'ior_FCC',),//13半场/全场
        array('sw_TS', 'ior_TSY', 'ior_TSN'),//14双方球队进球
        array('sw_HTS', 'ior_HTSY', 'ior_HTSN'),//15双方球队进球上半场
        array(''),//16第一个进球
        array('sw_WM', 'ior_WMH1', 'ior_WMH2', 'ior_WMH3', 'ior_WMHOV', 'ior_WMC1', 'ior_WMC2', 'ior_WMC3', 'ior_WMCOV', 'ior_WM0', 'ior_WMN'),//17净胜球数
        array('sw_DC', 'ior_DCHN', 'ior_DCCN', 'ior_DCHC'),//18双重机会
        array('sw_CS', 'ior_CSH', 'ior_CSC'),//19零失球
        array('sw_WN', 'ior_WNH', 'ior_WNC'),//20零失球获胜
        array('sw_RMUA', 'ior_MOUAHO', 'ior_MOUACO', 'ior_MOUANO', 'ior_MOUAHU', 'ior_MOUACU', 'ior_MOUANU'),//21"独赢 & 进球 大/小 1.5"
        array('sw_RMUB', 'ior_MOUBHO', 'ior_MOUBCO', 'ior_MOUBNO', 'ior_MOUBHU', 'ior_MOUBCU', 'ior_MOUBNU'),//22"独赢 & 进球 大/小 1.5"
        array('sw_RMUC', 'ior_MOUCHO', 'ior_MOUCCO', 'ior_MOUCNO', 'ior_MOUCHU', 'ior_MOUCCU', 'ior_MOUCNU'),//23"独赢 & 进球 大/小 1.5"
        array('sw_RMUD', 'ior_MOUDHO', 'ior_MOUDCO', 'ior_MOUDNO', 'ior_MOUDHU', 'ior_MOUDCU', 'ior_MOUDNU'),//24"独赢 & 进球 大/小 1.5"
        array('sw_MTS', 'ior_MTSHY', 'ior_MTSNY', 'ior_MTSCY', 'ior_MTSHN', 'ior_MTSNN', 'ior_MTSCN'),//25独赢&双方球队进球
        array('sw_UTA', 'ior_UTAOY', 'ior_UTAON', 'ior_UTAUY', 'ior_UTAUN'),//26"进球 大/小 1.5 & 双方球队进球"
        array('sw_UTB', 'ior_UTBOY', 'ior_UTBON', 'ior_UTBUY', 'ior_UTBUN'),//27"进球 大/小 1.5 & 双方球队进球"
        array('sw_UTC', 'ior_UTCOY', 'ior_UTCON', 'ior_UTCUY', 'ior_UTCUN'),//28"进球 大/小 1.5 & 双方球队进球"
        array('sw_UTD', 'ior_UTDOY', 'ior_UTDON', 'ior_UTDUY', 'ior_UTDUN'),//29"进球 大/小 1.5 & 双方球队进球"
        array('sw_HG', 'ior_HGH', 'ior_HGC'),//30"最多进球的半场"
        array('sw_MG', 'ior_MGH', 'ior_MGC', 'ior_MGN'),//31"最多进球的半场-独赢"
        array('sw_SB', 'ior_SBH', 'ior_SBC'),//32双半场进球
        array(''),//33首颗入球时间-3项
        array(''),//34首个入球时间
        array('sw_DUA', 'ior_DUAHO', 'ior_DUACO', 'ior_DUASO', 'ior_DUAHU', 'ior_DUACU', 'ior_DUASU'),//35"双重机会 & 进球 大/小 1.5"
        array('sw_DUB', 'ior_DUBHO', 'ior_DUBCO', 'ior_DUBSO', 'ior_DUBHU', 'ior_DUBCU', 'ior_DUBSU'),//36"双重机会 & 进球 大/小 2.5"
        array('sw_DUC', 'ior_DUCHO', 'ior_DUCCO', 'ior_DUCSO', 'ior_DUCHU', 'ior_DUCCU', 'ior_DUCSU'),//37"双重机会 & 进球 大/小 3.5"
        array('sw_DUD', 'ior_DUDHO', 'ior_DUDCO', 'ior_DUDSO', 'ior_DUDHU', 'ior_DUDCU', 'ior_DUDSU'),//38"双重机会 & 进球 大/小 4.5"
        array('sw_DS', 'ior_DSHY', 'ior_DSCY', 'ior_DSSY', 'ior_DSHN', 'ior_DSCN', 'ior_DSSN'),//39"双重机会 & 双方球队进球"
        array('sw_UEA', 'ior_UEAOO', 'ior_UEAOE', 'ior_UEAUO', 'ior_UEAUE'),//40"进球 大/小 1.5 & 进球数 单 / 双"
        array('sw_UEB', 'ior_UEBOO', 'ior_UEBOE', 'ior_UEBUO', 'ior_UEBUE'),//41"进球 大/小 2.5 & 进球数 单 / 双"
        array('sw_UEC', 'ior_UECOO', 'ior_UECOE', 'ior_UECUO', 'ior_UECUE'),//42"进球 大/小 3.5 & 进球数 单 / 双"
        array('sw_UED', 'ior_UEDOO', 'ior_UEDOE', 'ior_UEDUO', 'ior_UEDUE'),//43"进球 大/小 4.5 & 进球数 单 / 双"
        array('sw_WE', 'ior_WEH', 'ior_WEC'),//44赢得任一半场
        array('sw_WB', 'ior_WBH', 'ior_WBC'),//45赢得所有半场
        array('sw_OUH', 'ratio_rouho', 'ratio_rouhu', 'ior_OUHO', 'ior_OUHU'),//46球队进球数  主队  大小
        array('sw_OUC', 'ratio_rouco', 'ratio_roucu', 'ior_OUCO', 'ior_OUCU'),//47球队进球数  客队  大小
        array('sw_HOUH', 'ratio_houho', 'ratio_houhu', 'ior_HOUHO', 'ior_HOUHU'),//48球队进球数  主队  大小 上半场
        array('sw_HOUC', 'ratio_houco', 'ratio_houcu', 'ior_HOUCO', 'ior_HOUCU'),//49球队进球数  客队  大小 上半场

    );

    if($gType == 'BK'){
        //滚球用到的字段名
        $r_fieldName = array(
            array('sw_RM', 'ior_RMH', 'ior_RMC', 'ior_RMN'),//1独赢
            array('sw_RE', 'ior_REH', 'ior_REC', 'ratio_re'),//2让球
            array('sw_ROU', 'ratio_rouo', 'ratio_rouu', 'ior_ROUC', 'ior_ROUH'),//3大小
            array('sw_REO', 'ior_REOO', 'ior_REOE'),//4单双

            array('sw_ROUH', 'ratio_rouho', 'ratio_rouhu', 'ior_ROUHO', 'ior_ROUHU'),//5球队进球数  主队  大小(也就是球队得分)
            array('sw_ROUC', 'ratio_rouco', 'ratio_roucu', 'ior_ROUCO', 'ior_ROUCU'),//6球队进球数  客队  大小
            array('sw_RPD', 'ior_RPDH0', 'ior_RPDH1', 'ior_RPDH2', 'ior_RPDH3', 'ior_RPDH4',
                'ior_RPDC0', 'ior_RPDC1', 'ior_RPDC2', 'ior_RPDC3', 'ior_RPDC4'),//7篮球球队得分最后一位数

        );
        //非滚球用到的字段名
        $nr_fieldName = array(
            array('sw_M', 'ior_MH', 'ior_MC', 'ior_MN'),//1独赢
            array('sw_R', 'ior_RH', 'ior_RC', 'ratio'),//2让球
            array('sw_OU', 'ratio_o', 'ratio_u', 'ior_OUC', 'ior_OUH'),//3大小 IOR_OUH 小 IOR_OUC 大
            array('sw_EO', 'ior_EOO', 'ior_EOE'),//4单双

            array('sw_OUH', 'ratio_ouho', 'ratio_ouhu', 'ior_OUHO', 'ior_OUHU'),//5球队进球数  主队  大小
            array('sw_OUC', 'ratio_ouco', 'ratio_oucu', 'ior_OUCO', 'ior_OUCU'),//6球队进球数  客队  大小
            array('sw_PD', 'ior_PDH0', 'ior_PDH1', 'ior_PDH2', 'ior_PDH3', 'ior_PDH4',
                'ior_PDC0', 'ior_PDC1', 'ior_PDC2', 'ior_PDC3', 'ior_PDC4'),//7篮球球队得分最后一位数

        );
    }

    $fieldName = &$r_fieldName;
    if($isGunQiu !== 'Y')
    {
        $fieldName = &$nr_fieldName;
    }

    $mid_result = $val;
//    @error_log('bef:    '.date("Y-m-d H:i:s").json_encode($val,JSON_UNESCAPED_UNICODE)."\n", 3, '/tmp/group/log_format.log');
    $space_rep = '#&nbsp';
    $zero_rep = '#&quot';
    foreach ($mid_result['tmp_Obj'] as $gid => $gidInfo){
//        @error_log('gid:    '.date("Y-m-d H:i:s").' '.$gid."\n", 3, '/tmp/group/log_format.log');
        foreach ($fieldName as $playInfo) {
            if (count($playInfo) > 1) {
                if ($gidInfo[$playInfo[0]] == 'N') {
//                    @error_log('clear:    '.date("Y-m-d H:i:s").' '.$playInfo[0]."\n", 3, '/tmp/group/log_format.log');
                    for($i = 1; $i < count($playInfo); $i++){
                        unset($mid_result['tmp_Obj'][$gid][$playInfo[$i]]);
                    }
                }else if ($gidInfo[$playInfo[0]] == 'Y'){
                    for($i = 1; $i < count($playInfo); $i++){
                        if(!array_key_exists($playInfo[$i], $mid_result['tmp_Obj'][$gid])){
                            $mid_result['tmp_Obj'][$gid][$playInfo[$i]] = $space_rep;
                        }else{
                            if($mid_result['tmp_Obj'][$gid][$playInfo[$i]] == ''){
                                $mid_result['tmp_Obj'][$gid][$playInfo[$i]] = $space_rep;
                            }else if($mid_result['tmp_Obj'][$gid][$playInfo[$i]] == '0'){
                                $mid_result['tmp_Obj'][$gid][$playInfo[$i]] = $zero_rep;
                            }
                        }
                    }
                }
            }
        }
    }

    foreach ($mid_result['tmp_Obj'] as $gid => $gidInfo){
        foreach ($gidInfo as $k => $values){
            if(((strpos($k, 'ior_') === 0) || (strpos($k, 'ratio_') === 0))
                && ($values == '' || $values == '0')){
                unset($mid_result['tmp_Obj'][$gid][$k]);
            }else if($values == $space_rep){
                $mid_result['tmp_Obj'][$gid][$k] = '';
            }else if($values == $zero_rep){
                $mid_result['tmp_Obj'][$gid][$k] = '0';
            }
        }
    }
//    @error_log('mid:    '.date("Y-m-d H:i:s").json_encode($mid_result,JSON_UNESCAPED_UNICODE)."\n", 3, '/tmp/group/log_format.log');
    return $mid_result;
}

//更多玩法新旧格式转换
function changeFormat($jsonData,$isGunQiu, $gType, $isZ){

    if($gType == 'bk'){
        return  changeFormat_bk($jsonData, $isGunQiu, $isZ);
    }
    //滚球用到的字段名
    $r_fieldName = array(
        array('sw_RM', 'ior_RMH', 'ior_RMC', 'ior_RMN'),//1独赢
        array('sw_HRM', 'ior_HRMH', 'ior_HRMC', 'ior_HRMN'),//2独赢上半场
        array('sw_RE', 'ior_REH', 'ior_REC', 'ratio_re'),//3让球
        array('sw_HRE', 'ior_HREH', 'ior_HREC', 'ratio_hre'),//4让球上半场
        array('sw_ROU', 'ratio_rouo', 'ratio_rouu', 'ior_ROUH', 'ior_ROUC'),//5大小
        array('sw_HROU', 'ratio_hrouo', 'ratio_hrouu', 'ior_HROUH', 'ior_HROUC'),//6大小上半场
        array('sw_REO', 'ior_REOO', 'ior_REOE'),//7单双
        array('sw_HREO', 'ior_HREOO', 'ior_HREOE'),//8单双上半场
        array('sw_RT', 'ior_RT01', 'ior_RT23', 'ior_RT46', 'ior_ROVER'),//9总进球数
        array('sw_HRT', 'ior_HRT0', 'ior_HRT1', 'ior_HRT2', 'ior_HRTOV'),//10总进球数上半场
        array('sw_RPD', 'ior_RH1C0', 'ior_RH2C0', 'ior_RH2C1', 'ior_RH3C0', 'ior_RH3C1', 'ior_RH3C2', 'ior_RH4C0', 'ior_RH4C1', 'ior_RH4C2', 'ior_RH4C3',
            'ior_RH0C0', 'ior_RH1C1', 'ior_RH2C2', 'ior_RH3C3', 'ior_RH4C4',
            'ior_RH0C1', 'ior_RH0C2', 'ior_RH1C2', 'ior_RH0C3', 'ior_RH1C3', 'ior_RH2C3', 'ior_RH0C4', 'ior_RH1C4', 'ior_RH2C4', 'ior_RH3C4', 'ior_ROVH'),//11波胆
        array('sw_HRPD', 'ior_HRH1C0','ior_HRH2C0','ior_HRH2C1','ior_HRH3C0','ior_HRH3C1','ior_HRH3C2',
            'ior_HRH0C0','ior_HRH1C1','ior_HRH2C2','ior_HRH3C3',
            'ior_HRH0C1','ior_HRH0C2','ior_HRH1C2','ior_HRH0C3','ior_HRH1C3','ior_HRH2C3', 'ior_HROVH'),//12波胆上半场
        array('sw_RF', 'ior_RFHH', 'ior_RFHN', 'ior_RFHC', 'ior_RFNH', 'ior_RFNN', 'ior_RFNC', 'ior_RFCH', 'ior_RFCN', 'ior_RFCC',),//13半场/全场
        array('sw_RTS', 'ior_RTSY', 'ior_RTSN'),//14双方球队进球
        array('sw_HRTS', 'ior_HTSY', 'ior_HTSN'),//15双方球队进球上半场
        array(''),//16第一个进球
        array('sw_RWM', 'ior_RWMH1', 'ior_RWMH2', 'ior_RWMH3', 'ior_RWMHOV', 'ior_RWMC1', 'ior_RWMC2', 'ior_RWMC3', 'ior_RWMCOV', 'ior_RWM0', 'ior_RWMN'),//17净胜球数
        array('sw_RDC', 'ior_RDCHN', 'ior_RDCCN', 'ior_RDCHC'),//18双重机会
        array('sw_RCS', 'ior_RCSH', 'ior_RCSC'),//19零失球
        array('sw_RWN', 'ior_RWNH', 'ior_RWNC'),//20零失球获胜
        array('sw_RMUA', 'ior_RMUAHO', 'ior_RMUANO', 'ior_RMUACO', 'ior_RMUAHU', 'ior_RMUANU', 'ior_RMUACU'),//21"独赢 & 进球 大/小 1.5"
        array('sw_RMUB', 'ior_RMUBHO', 'ior_RMUBNO', 'ior_RMUBCO', 'ior_RMUBHU', 'ior_RMUBNU', 'ior_RMUBCU'),//22"独赢 & 进球 大/小 2.5"
        array('sw_RMUC', 'ior_RMUCHO', 'ior_RMUCNO', 'ior_RMUCCO', 'ior_RMUCHU', 'ior_RMUCNU', 'ior_RMUCCU'),//23"独赢 & 进球 大/小 3.5"
        array('sw_RMUD', 'ior_RMUDHO', 'ior_RMUDNO', 'ior_RMUDCO', 'ior_RMUDHU', 'ior_RMUDNU', 'ior_RMUDCU'),//24"独赢 & 进球 大/小 4.5"
        array('sw_RMTS', 'ior_RMTSHY', 'ior_RMTSNY', 'ior_RMTSCY', 'ior_RMTSHN', 'ior_RMTSNN', 'ior_RMTSCN'),//25独赢&双方球队进球
        array('sw_RUTA', 'ior_RUTAOY', 'ior_RUTAON', 'ior_RUTAUY', 'ior_RUTAUN'),//26"进球 大/小 1.5 & 双方球队进球"
        array('sw_RUTB', 'ior_RUTBOY', 'ior_RUTBON', 'ior_RUTBUY', 'ior_RUTBUN'),//27"进球 大/小 1.5 & 双方球队进球"
        array('sw_RUTC', 'ior_RUTCOY', 'ior_RUTCON', 'ior_RUTCUY', 'ior_RUTCUN'),//28"进球 大/小 1.5 & 双方球队进球"
        array('sw_RUTD', 'ior_RUTDOY', 'ior_RUTDON', 'ior_RUTDUY', 'ior_RUTDUN'),//29"进球 大/小 1.5 & 双方球队进球"
        array('sw_RHG', 'ior_RHGH', 'ior_RHGC'),//30"最多进球的半场"
        array('sw_RMG', 'ior_RMGH', 'ior_RMGC', 'ior_RMGN'),//31"最多进球的半场-独赢"
        array('sw_RSB', 'ior_RSBH', 'ior_RSBC'),//32双半场进球
        array(''),//33首颗入球时间-3项
        array(''),//34首个入球时间
        array('sw_RDUA', 'ior_RDUAHO', 'ior_RDUACO', 'ior_RDUASO', 'ior_RDUAHU', 'ior_RDUACU', 'ior_RDUASU'),//35"双重机会 & 进球 大/小 1.5"
        array('sw_RDUB', 'ior_RDUBHO', 'ior_RDUBCO', 'ior_RDUBSO', 'ior_RDUBHU', 'ior_RDUBCU', 'ior_RDUBSU'),//36"双重机会 & 进球 大/小 2.5"
        array('sw_RDUC', 'ior_RDUCHO', 'ior_RDUCCO', 'ior_RDUCSO', 'ior_RDUCHU', 'ior_RDUCCU', 'ior_RDUCSU'),//37"双重机会 & 进球 大/小 3.5"
        array('sw_RDUD', 'ior_RDUDHO', 'ior_RDUDCO', 'ior_RDUDSO', 'ior_RDUDHU', 'ior_RDUDCU', 'ior_RDUDSU'),//38"双重机会 & 进球 大/小 4.5"
        array('sw_RDS', 'ior_RDSHY', 'ior_RDSCY', 'ior_RDSSY', 'ior_RDSHN', 'ior_RDSCN', 'ior_RDSSN'),//39"双重机会 & 双方球队进球"
        array('sw_RUEA', 'ior_RUEAOO', 'ior_RUEAOE', 'ior_RUEAUO', 'ior_RUEAUE'),//40"进球 大/小 1.5 & 进球数 单 / 双"
        array('sw_RUEB', 'ior_RUEBOO', 'ior_RUEBOE', 'ior_RUEBUO', 'ior_RUEBUE'),//41"进球 大/小 2.5 & 进球数 单 / 双"
        array('sw_RUEC', 'ior_RUECOO', 'ior_RUECOE', 'ior_RUECUO', 'ior_RUECUE'),//42"进球 大/小 3.5 & 进球数 单 / 双"
        array('sw_RUED', 'ior_RUEDOO', 'ior_RUEDOE', 'ior_RUEDUO', 'ior_RUEDUE'),//43"进球 大/小 4.5 & 进球数 单 / 双"
        array('sw_RWE', 'ior_RWEH', 'ior_RWEC'),//44赢得任一半场
        array('sw_RWB', 'ior_RWBH', 'ior_RWBC'),//45赢得所有半场
        array('sw_ROUH', 'ratio_rouho', 'ratio_rouhu', 'ior_ROUHO', 'ior_ROUHU'),//46球队进球数  主队  大小
        array('sw_ROUC', 'ratio_rouco', 'ratio_roucu', 'ior_ROUCO', 'ior_ROUCU'),//47球队进球数  客队  大小
        array('sw_HOUH', 'ratio_houho', 'ratio_houhu', 'ior_HOUHO', 'ior_HOUHU'),//48球队进球数  主队  大小 上半场
        array('sw_HOUC', 'ratio_houco', 'ratio_houcu', 'ior_HOUCO', 'ior_HOUCU'),//49球队进球数  客队  大小 上半场

        );
    //非滚球用到的字段名
    $nr_fieldName = array(
        array('sw_M', 'ior_MH', 'ior_MC', 'ior_MN'),//1独赢
        array('sw_HM', 'ior_HMH', 'ior_HMC', 'ior_HMN'),//2独赢上半场
        array('sw_R', 'ior_RH', 'ior_RC', 'ratio'),//3让球
        array('sw_HR', 'ior_HRH', 'ior_HRC', 'hratio'),//4让球上半场
        array('sw_OU', 'ratio_o', 'ratio_u', 'ior_OUH', 'ior_OUC'),//5大小
        array('sw_HOU', 'ratio_ho', 'ratio_hu', 'ior_HOUH', 'ior_HOUC'),//6大小上半场
        array('sw_EO', 'ior_EOO', 'ior_EOE'),//7单双
        array('sw_HEO', 'ior_HEOO', 'ior_HEOE'),//8单双上半场
        array('sw_T', 'ior_T01', 'ior_T23', 'ior_T46', 'ior_OVER'),//9总进球数
        array('sw_HT', 'ior_HT0', 'ior_HT1', 'ior_HT2', 'ior_HTOV'),//10总进球数上半场
        array('sw_PD', 'ior_H1C0', 'ior_H2C0', 'ior_H2C1', 'ior_H3C0', 'ior_H3C1', 'ior_H3C2', 'ior_H4C0', 'ior_H4C1', 'ior_H4C2', 'ior_H4C3',
            'ior_H0C0', 'ior_H1C1', 'ior_H2C2', 'ior_H3C3', 'ior_H4C4',
            'ior_H0C1', 'ior_H0C2', 'ior_H1C2', 'ior_H0C3', 'ior_H1C3', 'ior_H2C3', 'ior_H0C4', 'ior_H1C4', 'ior_H2C4', 'ior_H3C4', 'ior_OVH'),//11波胆
        array('sw_HPD', 'ior_HH1C0','ior_HH2C0','ior_HH2C1','ior_HH3C0','ior_HH3C1','ior_HH3C2',
            'ior_HH0C0','ior_HH1C1','ior_HH2C2','ior_HH3C3',
            'ior_HH0C1','ior_HH0C2','ior_HH1C2','ior_HH0C3','ior_HH1C3','ior_HH2C3', 'ior_HOVH'),//12波胆上半场
        array('sw_F', 'ior_FHH', 'ior_FHN', 'ior_FHC', 'ior_FNH', 'ior_FNN', 'ior_FNC', 'ior_FCH', 'ior_FCN', 'ior_FCC',),//13半场/全场
        array('sw_TS', 'ior_TSY', 'ior_TSN'),//14双方球队进球
        array('sw_HTS', 'ior_HTSY', 'ior_HTSN'),//15双方球队进球上半场
        array(''),//16第一个进球
        array('sw_WM', 'ior_WMH1', 'ior_WMH2', 'ior_WMH3', 'ior_WMHOV', 'ior_WMC1', 'ior_WMC2', 'ior_WMC3', 'ior_WMCOV', 'ior_WM0', 'ior_WMN'),//17净胜球数
        array('sw_DC', 'ior_DCHN', 'ior_DCCN', 'ior_DCHC'),//18双重机会
        array('sw_CS', 'ior_CSH', 'ior_CSC'),//19零失球
        array('sw_WN', 'ior_WNH', 'ior_WNC'),//20零失球获胜
        array('sw_RMUA', 'ior_MOUAHO', 'ior_MOUACO', 'ior_MOUANO', 'ior_MOUAHU', 'ior_MOUACU', 'ior_MOUANU'),//21"独赢 & 进球 大/小 1.5"
        array('sw_RMUB', 'ior_MOUBHO', 'ior_MOUBCO', 'ior_MOUBNO', 'ior_MOUBHU', 'ior_MOUBCU', 'ior_MOUBNU'),//22"独赢 & 进球 大/小 1.5"
        array('sw_RMUC', 'ior_MOUCHO', 'ior_MOUCCO', 'ior_MOUCNO', 'ior_MOUCHU', 'ior_MOUCCU', 'ior_MOUCNU'),//23"独赢 & 进球 大/小 1.5"
        array('sw_RMUD', 'ior_MOUDHO', 'ior_MOUDCO', 'ior_MOUDNO', 'ior_MOUDHU', 'ior_MOUDCU', 'ior_MOUDNU'),//24"独赢 & 进球 大/小 1.5"
        array('sw_MTS', 'ior_MTSHY', 'ior_MTSNY', 'ior_MTSCY', 'ior_MTSHN', 'ior_MTSNN', 'ior_MTSCN'),//25独赢&双方球队进球
        array('sw_UTA', 'ior_UTAOY', 'ior_UTAON', 'ior_UTAUY', 'ior_UTAUN'),//26"进球 大/小 1.5 & 双方球队进球"
        array('sw_UTB', 'ior_UTBOY', 'ior_UTBON', 'ior_UTBUY', 'ior_UTBUN'),//27"进球 大/小 1.5 & 双方球队进球"
        array('sw_UTC', 'ior_UTCOY', 'ior_UTCON', 'ior_UTCUY', 'ior_UTCUN'),//28"进球 大/小 1.5 & 双方球队进球"
        array('sw_UTD', 'ior_UTDOY', 'ior_UTDON', 'ior_UTDUY', 'ior_UTDUN'),//29"进球 大/小 1.5 & 双方球队进球"
        array('sw_HG', 'ior_HGH', 'ior_HGC'),//30"最多进球的半场"
        array('sw_MG', 'ior_MGH', 'ior_MGC', 'ior_MGN'),//31"最多进球的半场-独赢"
        array('sw_SB', 'ior_SBH', 'ior_SBC'),//32双半场进球
        array(''),//33首颗入球时间-3项
        array(''),//34首个入球时间
        array('sw_DUA', 'ior_DUAHO', 'ior_DUACO', 'ior_DUASO', 'ior_DUAHU', 'ior_DUACU', 'ior_DUASU'),//35"双重机会 & 进球 大/小 1.5"
        array('sw_DUB', 'ior_DUBHO', 'ior_DUBCO', 'ior_DUBSO', 'ior_DUBHU', 'ior_DUBCU', 'ior_DUBSU'),//36"双重机会 & 进球 大/小 2.5"
        array('sw_DUC', 'ior_DUCHO', 'ior_DUCCO', 'ior_DUCSO', 'ior_DUCHU', 'ior_DUCCU', 'ior_DUCSU'),//37"双重机会 & 进球 大/小 3.5"
        array('sw_DUD', 'ior_DUDHO', 'ior_DUDCO', 'ior_DUDSO', 'ior_DUDHU', 'ior_DUDCU', 'ior_DUDSU'),//38"双重机会 & 进球 大/小 4.5"
        array('sw_DS', 'ior_DSHY', 'ior_DSCY', 'ior_DSSY', 'ior_DSHN', 'ior_DSCN', 'ior_DSSN'),//39"双重机会 & 双方球队进球"
        array('sw_UEA', 'ior_UEAOO', 'ior_UEAOE', 'ior_UEAUO', 'ior_UEAUE'),//40"进球 大/小 1.5 & 进球数 单 / 双"
        array('sw_UEB', 'ior_UEBOO', 'ior_UEBOE', 'ior_UEBUO', 'ior_UEBUE'),//41"进球 大/小 2.5 & 进球数 单 / 双"
        array('sw_UEC', 'ior_UECOO', 'ior_UECOE', 'ior_UECUO', 'ior_UECUE'),//42"进球 大/小 3.5 & 进球数 单 / 双"
        array('sw_UED', 'ior_UEDOO', 'ior_UEDOE', 'ior_UEDUO', 'ior_UEDUE'),//43"进球 大/小 4.5 & 进球数 单 / 双"
        array('sw_WE', 'ior_WEH', 'ior_WEC'),//44赢得任一半场
        array('sw_WB', 'ior_WBH', 'ior_WBC'),//45赢得所有半场
        array('sw_OUH', 'ratio_rouho', 'ratio_rouhu', 'ior_OUHO', 'ior_OUHU'),//46球队进球数  主队  大小
        array('sw_OUC', 'ratio_rouco', 'ratio_roucu', 'ior_OUCO', 'ior_OUCU'),//47球队进球数  客队  大小
        array('sw_HOUH', 'ratio_houho', 'ratio_houhu', 'ior_HOUHO', 'ior_HOUHU'),//48球队进球数  主队  大小 上半场
        array('sw_HOUC', 'ratio_houco', 'ratio_houcu', 'ior_HOUCO', 'ior_HOUCU'),//49球队进球数  客队  大小 上半场

        );
    $fieldName = &$r_fieldName;
    //波胆盘口
    $bodanType = array('1-0', '2-0', '2-1', '3-0', '3-1', '3-2', '4-0', '4-1', '4-2', '4-3',
        '0-0', '1-1', '2-2', '3-3', '4-4',
        '0-1', '0-2', '1-2', '0-3', '1-3', '2-3', '0-4', '1-4', '2-4', '3-4', 'other');
    //波胆上半场盘口
    $h_bodantType = array('1-0', '2-0', '2-1', '3-0', '3-1', '3-2',
        '0-0', '1-1', '2-2', '3-3',
        '0-1', '0-2', '1-2', '0-3', '1-3', '2-3', 'other');

    if($isGunQiu !== 'Y')
    {
        $fieldName = &$nr_fieldName;
    }

    //返回的数据
    $readyArr = array();
    $aData = json_decode($jsonData,true);
    $match = $aData['data']['match'];
    $cou= $aData['data']['match']['totalMarkets'];
//    @error_log(date("Y-m-d H:i:s").$jsonData."\n", 3, '/tmp/group/log_format.log');

    if($cou>0){
        $curIndex = 0;
        $primeArray = &$readyArr[$curIndex];
        $gid = $match['matchId'];
        //对加时赛的处理
        if(!is_null($match['markets'][0]['eventId'])){
            $gid = $match['markets'][0]['eventId'];
        }
        if(($match['markets'][0]['ctidDescription'] != '')){
            $primeArray['description'] = $match['markets'][0]['ctidDescription'];
        }
        $primeArray['gopen'] = 'Y';
        $primeArray['hgopen'] = 'Y';
        $primeArray['Live'] = (($match['hasLiveMatch'] == 'true') ? 'Y' : 'N');
        $primeArray['gid'] = $gid;
        $primeArray['league'] = trim($match['seasonName']);
        $primeArray['datetime'] = str_replace('T', ' ', $match['startTime']);
        $primeArray['startTime'] = $primeArray['datetime'];
        $primeArray['re_time'] = $match['liveStatus'].'^'.$match['clock']; //2H^80:09
        if($isGunQiu !== 'Y'){
            $primeArray['re_time'] = substr($primeArray['datetime'], strpos($primeArray['datetime'], ' ') + 1);
        }
//                    $primeArray['description'] = $v3['description'];
        $primeArray['team_h'] = trim($match['competitors']['home']['name']);
        $primeArray['team_c'] = trim($match['competitors']['away']['name']);
        $primeArray['score_h'] = $match['competitors']['home']['score']; // 主队比分
        $primeArray['score_c'] = $match['competitors']['away']['score']; // 客队比分
        $primeArray['redcard_h'] = $match['competitors']['home']['redCard']; // 主队罚球数
        $primeArray['redcard_c'] = $match['competitors']['away']['redCard']; // 客队罚球数

        $tmpId = 0;

        foreach ($match['markets'] as $playType){
            $market = array();
            extractKeyV($playType, $market);
            $k4 = $playType['marketCode'];
//            if($k4 !== 'sp'){
//                @error_log('marketCode:    '.date("Y-m-d H:i:s").' '.$k4. ' '.$tmpId++."\n", 3, '/tmp/group/log_format.log');
//            }else{
//                @error_log('marketCode:    '.date("Y-m-d H:i:s").' '.$playType['ename']. ' '.$tmpId++."\n", 3, '/tmp/group/log_format.log');
//            }

            switch ($playType['marketCode'])
            {
                case '1x2': // 全场独赢
                case '1x21st':// 半场独赢
                    if(checkStatus($playType)){
                        break;
                    }
                    if ($k4=='1x2'){
                        $arrIndex = 0;
                    }else{
                        $arrIndex = 1;
                    }
                    $curArray = &$readyArr[getCurPanKou($readyArr, $fieldName, $arrIndex, $market)];
                    $curArray[$fieldName[$arrIndex][0]] = 'Y';
                    $curArray[$fieldName[$arrIndex][1]] = $market['outcomes']['h']['odds'];
                    $curArray[$fieldName[$arrIndex][2]] = $market['outcomes']['a']['odds'];
                    $curArray[$fieldName[$arrIndex][3]] = check_null($market['outcomes']['d']['odds']);
                    break;
                case 'ah':
                case 'ah1st':
                    if(checkStatus($playType)){
                        break;
                    }

//                @error_log('playType:    '.date("Y-m-d H:i:s").json_encode($playType,JSON_UNESCAPED_UNICODE)."\n", 3, '/tmp/group/log_format.log');
//                @error_log('$market:    '.date("Y-m-d H:i:s").json_encode($market,JSON_UNESCAPED_UNICODE)."\n", 3, '/tmp/group/log_format.log');

                    // 全场让球。让球数小于0  主队让 H， 让球数大于0 客队让 C
                    if ($k4=='ah'){
                        $arrIndex = 2;
                        $strongTyp = 'strong';
                    }else{// 让球上半场。让球数小于0  主队让 H， 让球数大于0 客队让 C
                        $arrIndex = 3;
                        $strongTyp = 'hstrong';
                    }
                    $curArray = &$readyArr[getCurPanKou($readyArr, $fieldName, $arrIndex, $market)];
                    $curArray[$fieldName[$arrIndex][0]]='Y';

                    $RATIO_RE = $market['ename'];
                    if (strlen($RATIO_RE)>1){
                        $jiajian=substr($RATIO_RE , 0 , 1);
                        $curArray[$fieldName[$arrIndex][3]]=substr($RATIO_RE,1);;
                        if ($jiajian=='-'){
                            $curArray[$strongTyp]='H';
                        }else{
                            $curArray[$strongTyp]='C';
                        }
                    }else{
                        $curArray[$fieldName[$arrIndex][3]]=$RATIO_RE;
                        $curArray[$strongTyp]='H';
                    }
                    $curArray[$fieldName[$arrIndex][1]] = $market['outcomes']['h']['odds'];
                    $curArray[$fieldName[$arrIndex][2]] = $market['outcomes']['a']['odds'];
                    break;
                case 'ou':
                case 'ou1st':
                    if(checkStatus($playType)){
                        break;
                    }
                    if($market['description'] == '球队进球数-主队')
                    {
                        if ($k4=='ou'){// 球队进球数  主队  大小
                            $arrIndex = 45;
                        }else{// 球队进球数  客队  大小
                            $arrIndex = 46;
                        }
                    }else if($market['description'] == '球队进球数-客队'){
                        if ($k4=='ou'){// 球队进球数  主队  大小 上半场
                            $arrIndex = 47;
                        }else{// 球队进球数  客队  大小 上半场
                            $arrIndex = 48;
                        }
                    }else{
                        if ($k4=='ou'){// 全场进球大小(最后两个可能反了)
                            $arrIndex = 4;
                        }else{// 进球大小上半场(最后两个可能反了)
                            $arrIndex = 5;
                        }
                    }
                    $curArray = &$readyArr[getCurPanKou($readyArr, $fieldName, $arrIndex, $market)];
                    $curArray[$fieldName[$arrIndex][0]]='Y';

                    $curArray[$fieldName[$arrIndex][0]] = 'Y';
                    $curArray[$fieldName[$arrIndex][1]] = $market['ename'];
                    $curArray[$fieldName[$arrIndex][2]] = $market['ename'];
                    $curArray[$fieldName[$arrIndex][3]] = $market['outcomes']['un']['odds'];
                    $curArray[$fieldName[$arrIndex][4]] = $market['outcomes']['ov']['odds'];
                    break;
                case 'oe':// 单双
                case 'oe1st':// 单双上半场
                    if(checkStatus($playType)){
                        break;
                    }
                    if ($k4=='oe'){
                        $arrIndex = 6;
                    }else{
                        $arrIndex = 7;
                    }
                    $curArray = &$readyArr[getCurPanKou($readyArr, $fieldName, $arrIndex, $market)];
                    $curArray[$fieldName[$arrIndex][0]] = 'Y';
                    $curArray[$fieldName[$arrIndex][1]] = $market['outcomes']['od']['odds'];  //欧洲盘
                    $curArray[$fieldName[$arrIndex][2]] = $market['outcomes']['ev']['odds'];
                    break;
                case 'tg'://总进球数
                    if(checkStatus($playType)){
                        break;
                    }
                    $arrIndex = 8;
                    $primeArray[$fieldName[$arrIndex][0]] = 'Y';
                    $primeArray[$fieldName[$arrIndex][1]] = check_null($market['outcomes']['f01']['odds']);
                    $primeArray[$fieldName[$arrIndex][2]] = check_null($market['outcomes']['f23']['odds']);
                    $primeArray[$fieldName[$arrIndex][3]] = check_null($market['outcomes']['f46']['odds']);
                    $primeArray[$fieldName[$arrIndex][4]] = check_null($market['outcomes']['f7']['odds']);
                    break;
                case 'tg1st'://总进球数上半场
                    if(checkStatus($playType)){
                        break;
                    }
                    $arrIndex = 9;
                    $primeArray[$fieldName[$arrIndex][0]] = 'Y';
                    $primeArray[$fieldName[$arrIndex][1]] = check_null($market['outcomes']['h0']['odds']);
                    $primeArray[$fieldName[$arrIndex][2]] = check_null($market['outcomes']['h1']['odds']);
                    $primeArray[$fieldName[$arrIndex][3]] = check_null($market['outcomes']['h2']['odds']);
                    $primeArray[$fieldName[$arrIndex][4]] = check_null($market['outcomes']['h3']['odds']);
                    break;
                case 'cs'://波胆
                    if(checkStatus($playType)){
                        break;
                    }
                    $arrIndex = 10;
                    $primeArray[$fieldName[$arrIndex][0]] = 'Y';
                    $typeCount = count($fieldName[$arrIndex]);
                    for($i = 1; $i < $typeCount; $i++){
                        if(array_key_exists($bodanType[$i-1], $market['outcomes'])){
                            $primeArray[$fieldName[$arrIndex][$i]] = $market['outcomes'][$bodanType[$i-1]]['odds'];
                        }else{
                            $primeArray[$fieldName[$arrIndex][$i]] = '';
                        }
                    }
                    break;
                case 'cs1st'://波胆上半场
                    if(checkStatus($playType)){
                        break;
                    }
                    $arrIndex = 11;
                    $primeArray[$fieldName[$arrIndex][0]] = 'Y';
                    for($i = 1; $i < count($fieldName[$arrIndex]); $i++){
                        if(array_key_exists($h_bodantType[$i-1], $market['outcomes'])){
                            $primeArray[$fieldName[$arrIndex][$i]] = $market['outcomes'][$h_bodantType[$i-1]]['odds'];
                        }else{
                            $primeArray[$fieldName[$arrIndex][$i]] = '';
                        }
                    }
                    break;
                case 'hf'://半场/全场
                    if(checkStatus($playType)){
                        break;
                    }
                    $arrIndex = 12;
                    $primeArray[$fieldName[$arrIndex][0]] = 'Y';
                    $primeArray[$fieldName[$arrIndex][1]] = $market['outcomes']['hh']['odds'];
                    $primeArray[$fieldName[$arrIndex][2]] = $market['outcomes']['hd']['odds'];
                    $primeArray[$fieldName[$arrIndex][3]] = $market['outcomes']['ha']['odds'];
                    $primeArray[$fieldName[$arrIndex][4]] = $market['outcomes']['dh']['odds'];
                    $primeArray[$fieldName[$arrIndex][5]] = $market['outcomes']['dd']['odds'];
                    $primeArray[$fieldName[$arrIndex][6]] = $market['outcomes']['da']['odds'];
                    $primeArray[$fieldName[$arrIndex][7]] = $market['outcomes']['ah']['odds'];
                    $primeArray[$fieldName[$arrIndex][8]] = $market['outcomes']['ad']['odds'];
                    $primeArray[$fieldName[$arrIndex][9]] = $market['outcomes']['aa']['odds'];
                    break;
                case 'sp'://特殊玩法
                    if(checkStatus($playType)){
                        break;
                    }
                    $k4 = $playType['ename'];
                    switch ($playType['ename'])
                    {
                        case 'BothTeamsToScore'://双方球队进球
                        case 'BothTeamsToScore_SecondHalf'://双方球队进球半场
                            if ($k4=='BothTeamsToScore'){
                                $arrIndex = 13;
                            }else{
                                $arrIndex = 14;
                            }
                            $primeArray[$fieldName[$arrIndex][0]] = 'Y';
                            foreach ($market['outcomes'] as $innerInfo)
                            {
                                if ($innerInfo['name'] == '是'){
                                    $primeArray[$fieldName[$arrIndex][1]] = $innerInfo['odds'];
                                }elseif ($innerInfo['name'] == '不是'){
                                    $primeArray[$fieldName[$arrIndex][2]] = $innerInfo['odds'];
                                }
                            }
                            break;
                        case '1stGoal'://第一个进球
                            $arrIndex = 15;
                            break;
                        case 'WinningMargin'://"净胜球数"
                            $arrIndex = 16;
                            $primeArray[$fieldName[$arrIndex][0]] = 'Y';
                            proc_WinningMargin($fieldName, $playType, $primeArray, $arrIndex);
                            break;
                        case 'DoubleChance'://"双重机会"
                            if(strpos($playType['betName'] , '角球')!==false) {break;}    //角球:双重机会
                            $arrIndex = 17;
                            $primeArray[$fieldName[$arrIndex][0]] = 'Y';
                            $primeArray[$fieldName[$arrIndex][1]] = '';
                            $primeArray[$fieldName[$arrIndex][2]] = '';
                            $primeArray[$fieldName[$arrIndex][3]] = '';
                            $tmpIndex = 1;
                            $tmphome = str_replace(' ', '', $primeArray['team_h'].'/'.'和局' );
                            $tmpaway = str_replace(' ', '', $primeArray['team_c'].'/'.'和局' );
                            foreach ($market['outcomes'] as $innerInfo)
                            {
                                $tmpstr = str_replace(' ', '', $innerInfo['name'] );
                                if($tmpstr == $tmphome){
                                    $primeArray[$fieldName[$arrIndex][1]] = $innerInfo['odds'];
                                }else if($tmpstr == $tmpaway){
                                    $primeArray[$fieldName[$arrIndex][2]] = $innerInfo['odds'];
                                }else{
                                    $primeArray[$fieldName[$arrIndex][3]] = $innerInfo['odds'];
                                }
//                                @error_log('playType:    '.date("Y-m-d H:i:s").$tmpstr."\n", 3, '/tmp/group/log_format.log');
                            }
//                            @error_log(':    '.date("Y-m-d H:i:s").$primeArray['team_h'].'/'.'和局'."\n", 3, '/tmp/group/log_format.log');
//                            @error_log(':    '.date("Y-m-d H:i:s").$primeArray['team_c'].'/'.'和局'."\n", 3, '/tmp/group/log_format.log');
                            break;
                        case 'CleanSheet'://"零失球"
                        case 'ToWinToNil'://"零失球获胜"
                            if ($k4=='CleanSheet'){
                                $arrIndex = 18;
                            }else{
                                $arrIndex = 19;
                            }
                            $primeArray[$fieldName[$arrIndex][0]] = 'Y';
                            foreach ($market['outcomes'] as $innerInfo)
                            {
                                if ($innerInfo['name'] == $primeArray['team_h']){
                                    $primeArray[$fieldName[$arrIndex][1]] = $innerInfo['odds'];
                                }elseif ($innerInfo['name'] == $primeArray['team_c']){
                                    $primeArray[$fieldName[$arrIndex][2]] = $innerInfo['odds'];
                                }
                            }
                            break;
                        case 'FT_1X2_And_FT_OU_1p5'://"独赢 & 进球 大/小 1.5"
                        case 'FT_1X2_And_FT_OU_2p5'://"独赢 & 进球 大/小 2.5"
                        case 'FT_1X2_And_FT_OU_3p5'://"独赢 & 进球 大/小 3.5"
                        case 'FT_1X2_And_FT_OU_4p5'://"独赢 & 进球 大/小 4.5"
                        case 'FT_1X2_And_BothTeamsToScore'://'独赢 & 双方球队进球"
                            proc_FT_1X2_And_FT_OU_1p5($k4, $fieldName, $market, $primeArray);
                            break;
                        case 'FT_OU_1p5_And_BothTeamsToScore'://"进球 大/小 1.5 & 双方球队进球"
                        case 'FT_OU_2p5_And_BothTeamsToScore'://"进球 大/小 2.5 & 双方球队进球"
                        case 'FT_OU_3p5_And_BothTeamsToScore'://"进球 大/小 3.5 & 双方球队进球"
                        case 'FT_OU_4p5_And_BothTeamsToScore'://"进球 大/小 4.5 & 双方球队进球"
                            proc_FT_OU_1p5_And_BothTeamsToScore($k4, $fieldName, $market, $primeArray);
                            break;
                        case 'HalfWithMostGoals'://"最多进球的半场"
                        case 'HalfWithMostGoals_1X2'://"最多进球的半场 - 独赢"
                            if($k4 == 'HalfWithMostGoals'){
                                $arrIndex = 29;
                            }else{
                                $arrIndex = 30;
                            }
                            $primeArray[$fieldName[$arrIndex][0]] = 'Y';
                            foreach ($market['outcomes'] as $innerInfo){
                                if (($innerInfo['name']) == '上半场'){
                                    $primeArray[$fieldName[$arrIndex][1]] = $innerInfo['odds'];
                                }else if (($innerInfo['name']) == '下半场'){
                                    $primeArray[$fieldName[$arrIndex][2]] = $innerInfo['odds'];
                                }else if ((($innerInfo['name']) == '和局') && $arrIndex == 30){
                                    $primeArray[$fieldName[$arrIndex][3]] = $innerInfo['odds'];
                                }
                            }
                            break;
                        case 'ToScoreInBothHalves'://双半场进球
                            $arrIndex = 31;
                            $primeArray[$fieldName[$arrIndex][0]] = 'Y';
                            foreach ($market['outcomes'] as $innerInfo){
                                if (($innerInfo['name']) == $primeArray['team_h']){
                                    $primeArray[$fieldName[$arrIndex][1]] = $innerInfo['odds'];
                                }else if (($innerInfo['name']) == $primeArray['team_c']){
                                    $primeArray[$fieldName[$arrIndex][2]] = $innerInfo['odds'];
                                }
                            }
                            break;
                        case 'TimeOfFirstGoal_ThreeWay'://"首颗入球时间-3项"
                            $arrIndex = 32;
                            break;
                        case 'TimeOfFirstGoal'://"首个进球时间"
                            $arrIndex = 33;
                            break;
                        case 'DoubleChance_And_FT_OU_1p5'://"双重机会 & 进球 大/小 1.5"
                        case 'DoubleChance_And_FT_OU_2p5'://"双重机会 & 进球 大/小 2.5"
                        case 'DoubleChance_And_FT_OU_3p5'://"双重机会 & 进球 大/小 3.5"
                        case 'DoubleChance_And_FT_OU_4p5'://"双重机会 & 进球 大/小 4.5"
                        case 'DoubleChance_And_BothTeamsToScore'://"双重机会 & 双方球队进球"
                            proc_DoubleChance_And_FT_OU_1p5($k4, $fieldName, $market, $primeArray);
                            break;
                        case 'FT_OU_1p5_And_FT_OE'://"进球 大/小 1.5 & 进球数 单 / 双"
                        case 'FT_OU_2p5_And_FT_OE'://"进球 大/小 2.5 & 进球数 单 / 双"
                        case 'FT_OU_3p5_And_FT_OE'://"进球 大/小 3.5 & 进球数 单 / 双"
                        case 'FT_OU_4p5_And_FT_OE'://"进球 大/小 4.5 & 进球数 单 / 双"
                            proc_FT_OU_1p5_And_FT_OE($k4, $fieldName, $market, $primeArray);
                            break;
                        case 'ToWinEitherHalf'://"赢得任一半场"
                        case 'ToWinBothHalves'://"赢得所有半场"
                            if (strpos($k4,'Half')!==false){
                                $arrIndex = 43;
                            }else{
                                $arrIndex = 44;
                            }
                            $primeArray[$fieldName[$arrIndex][0]] = 'Y';
                            foreach ($market['outcomes'] as $innerInfo){
                                if ((($innerInfo['name'] == $primeArray['team_h'])!==false)){
                                    $primeArray[$fieldName[$arrIndex][1]] = $innerInfo['odds'];
                                }else{
                                    $primeArray[$fieldName[$arrIndex][2]] = $innerInfo['odds'];
                                }
                            }
                            break;
                        default:
                            break;
                    }
                    break;
                default:
                    break;
            }
        }
    }

    for($i = 0; $i < count($readyArr); $i++){
        if(is_null($readyArr[$i]['team_h'])){
            for($j = $i - 1; $j >= 0; $j--){
                if(!is_null($readyArr[$j]['team_h'])){
//                    $readyArr[$i]['gid'] = $readyArr[$j]['gid'] + 2;
//                    $readyArr[$i]['league'] = $readyArr[$j]['league'];
                    $readyArr[$i]['datetime'] = $readyArr[$j]['datetime'];
                    $readyArr[$i]['startTime'] = $readyArr[$j]['startTime'];
                    $readyArr[$i]['re_time'] = $readyArr[$j]['re_time'];
//                    $readyArr[$i]['description']= $readyArr[$j]['description'];
                    $readyArr[$i]['team_h'] = $readyArr[$j]['team_h'];
                    $readyArr[$i]['team_c'] = $readyArr[$j]['team_c'];
                    $readyArr[$i]['score_h'] = $readyArr[$j]['score_h'];
                    $readyArr[$i]['score_c'] = $readyArr[$j]['score_c'];
                    $readyArr[$i]['redcard_h'] = $readyArr[$j]['redcard_h'];
                    $readyArr[$i]['redcard_c'] = $readyArr[$j]['redcard_c'];
                    $readyArr[$i]['gopen'] = 'Y';
                    $readyArr[$i]['hgopen'] = 'Y';
                    $readyArr[$i]['Live'] = $readyArr[0]['Live'];
                    break;
                }
            }
        }
    }
    $getJQScore = false;
    $hScore = 0;
    $aScore = 0;
    $getExScore = false;
    $exScore_h = 0;
    $exScore_a = 0;
    foreach($readyArr as $key => $tmpobj){
        if(!is_null($tmpobj['description'])){
            if(strpos($tmpobj['description'], '加时赛') !== false){
                $tmpobj['description'] = '加时赛';
            }
            !empty($tmpobj['description']) ? $tmpobj['description']  = ' -'.$tmpobj['description']:'';
            $readyArr[$key]['team_h'] = $tmpobj['team_h'].$tmpobj['description'];
            $readyArr[$key]['team_c'] = $tmpobj['team_c'].$tmpobj['description'];
            if($tmpobj['description'] == ' -角球'){
                $readyArr[$key]['team_h'] = $readyArr[$key]['team_h'].'数';
                $readyArr[$key]['team_c'] = $readyArr[$key]['team_c'].'数';
                if(!$getJQScore){
                    getJiaoQiuScore($hScore, $aScore, $match['ci'], $readyArr[$key]['gid']);
                    $getJQScore = true;
                }
                $readyArr[$key]['score_h'] = $hScore;
                $readyArr[$key]['score_c'] = $aScore;
                //$readyArr[$key]['gid'] = $primeArray['gid'];
            }else if($tmpobj['description'] == ' -加时赛'){
                if(!$getExScore){
                    getJiaoQiuScore($exScore_h, $exScore_a, $match['ci'], $readyArr[$key]['gid']);
                    $getExScore = true;
                }
                $readyArr[$key]['score_h'] = $exScore_h;
                $readyArr[$key]['score_c'] = $exScore_a;

            }
        }
    }

    for($i = 0; $i < count($readyArr); $i++){
        $readyArr[$i]['gid_fs'] = $primeArray['gid'].'_'.$i;
        if(is_null($readyArr[$i]['description'])){
            $readyArr[$i]['description'] = '';
        }
    }
    fillSwitchInfo($fieldName, $readyArr);

//    @error_log(date("Y-m-d H:i:s").'   endlog:    '."\n", 3, '/tmp/group/log_format.log');
    return $readyArr;
}

//获取角球比分
function getJiaoQiuScore(&$hScore, &$aScore, &$ci, $gid){
    foreach ($ci as $innerInfo){
        if($innerInfo['eid'] == $gid){
            $hScore = $innerInfo['h'];
            $aScore = $innerInfo['a'];
            break;
        }
    }
}

//从outcomeCode中提取数组key值 (marketCode ov大 un小)
function extractKeyV(&$outcomes, &$retcomes){
    foreach ($outcomes['outcomes'] as $innData){
        if(!is_null($innData['outcomeCode'])){
            $tmpKey = $innData['outcomeCode'];
            $retcomes['outcomes'][$tmpKey]['odds'] = $innData['odds'];
            if(strpos($outcomes['marketCode'], 'oe') !== false){
                $retcomes['outcomes'][$tmpKey]['odds'] = $innData['euOdds'];    //euOdds
            }
            $retcomes['outcomes'][$tmpKey]['name'] = $innData['betName'];
        }
    }
    $retcomes['ename'] = $outcomes['ename'];
    $retcomes['description'] = $outcomes['ctidDescription'];
    $retcomes['gid'] = $outcomes['eventId'];
}

//篮球的格式转换
function changeFormat_bk($jsonData,$isGunQiu, $isZ){
    //滚球用到的字段名
    $r_fieldName = array(
        array('sw_RM', 'ior_RMH', 'ior_RMC', 'ior_RMN'),//1独赢
        array('sw_RE', 'ior_REH', 'ior_REC', 'ratio_re'),//2让球
        array('sw_ROU', 'ratio_rouo', 'ratio_rouu', 'ior_ROUC', 'ior_ROUH'),//3大小
        array('sw_REO', 'ior_REOO', 'ior_REOE'),//4单双

        array('sw_ROUH', 'ratio_rouho', 'ratio_rouhu', 'ior_ROUHO', 'ior_ROUHU'),//5球队进球数  主队  大小(也就是球队得分)
        array('sw_ROUC', 'ratio_rouco', 'ratio_roucu', 'ior_ROUCO', 'ior_ROUCU'),//6球队进球数  客队  大小
        array('sw_RPD', 'ior_RPDH0', 'ior_RPDH1', 'ior_RPDH2', 'ior_RPDH3', 'ior_RPDH4',
            'ior_RPDC0', 'ior_RPDC1', 'ior_RPDC2', 'ior_RPDC3', 'ior_RPDC4'),//7篮球球队得分最后一位数

    );
    //非滚球用到的字段名
    $nr_fieldName = array(
        array('sw_M', 'ior_MH', 'ior_MC', 'ior_MN'),//1独赢
        array('sw_R', 'ior_RH', 'ior_RC', 'ratio'),//2让球
        array('sw_OU', 'ratio_o', 'ratio_u', 'ior_OUC', 'ior_OUH'),//3大小 IOR_OUH 小 IOR_OUC 大
        array('sw_EO', 'ior_EOO', 'ior_EOE'),//4单双

        array('sw_OUH', 'ratio_ouho', 'ratio_ouhu', 'ior_OUHO', 'ior_OUHU'),//5球队进球数  主队  大小
        array('sw_OUC', 'ratio_ouco', 'ratio_oucu', 'ior_OUCO', 'ior_OUCU'),//6球队进球数  客队  大小
        array('sw_PD', 'ior_PDH0', 'ior_PDH1', 'ior_PDH2', 'ior_PDH3', 'ior_PDH4',
            'ior_PDC0', 'ior_PDC1', 'ior_PDC2', 'ior_PDC3', 'ior_PDC4'),//7篮球球队得分最后一位数

    );
    $fieldName = &$r_fieldName;
    if($isGunQiu !== 'Y')
    {
        $fieldName = &$nr_fieldName;
    }
    //篮球得分最后一位数种类
    $last_num = array('05', '16', '27', '38', '49');

    $readyArr = array();
    $primeArray=&$readyArr[0] ; // 把需要的数据重新放在数组里面
    $aData = json_decode($jsonData,true);

    $match = $aData['data']['match'];
    $cou= $aData['data']['match']['totalMarkets'];  //总玩法数量



    if($cou>0){
        $arrIndex = 0;

//                    //球队进球数玩法挂在主盘口
//                    if(strpos($v3['description'],'球队得分')!==false){
//
//                    }else{

        $gid = transGid($match['matchId']); //篮球主盘口gid
	$primeArray['matchId'] = $match['matchId'];  //原主盘口gid
        $primeArray['ms'] = '';
        $primeArray['gopen'] = 'Y';
        $primeArray['hgopen'] = 'Y';
        $primeArray['se_now'] = '';
        $primeArray['session'] = '';
        $primeArray['midfield'] = '';
        $primeArray['Live'] = (($match['hasLiveMatch'] == 'true') ? 'Y' : 'N');
        $primeArray['gid'] = $gid;
        $primeArray['gid_fs'] = $gid .'_0';
        $primeArray['league'] = $match['seasonName'];
        $primeArray['datetime'] = str_replace('T', ' ', $match['startTime']);
        $primeArray['startTime'] = $primeArray['datetime'];
        $primeArray['re_time'] = strtoupper($match['liveStatus']).'^'.$match['clock']; //2H^80:09
        $primeArray['clock'] = $match['clock'];
        if($isGunQiu !== 'Y'){
            $primeArray['re_time'] = substr($primeArray['datetime'], strpos($primeArray['datetime'], ' ') + 1);
        }else{
            //补充积分信息
            fillScoreInfo($primeArray, $match['scoreBarInfo']['points']);
            $primeArray['se_now'] = getliveStatus($match['liveStatus']);
            $primeArray['session'] = $match['liveStatusText'];
        }
        $primeArray['team_h'] = $match['competitors']['home']['name'];
        $primeArray['team_c'] = $match['competitors']['away']['name'];
        $primeArray['score_h'] = $match['competitors']['home']['score']; // 主队比分
        $primeArray['score_c'] = $match['competitors']['away']['score']; // 客队比分
        $primeArray['redcard_h'] = $match['competitors']['home']['redCard']; // 主队罚球数
        $primeArray['redcard_c'] = $match['competitors']['away']['redCard']; // 客队罚球数

        foreach ($match['markets'] as  $playType){
            $market = array();
            extractKeyV($playType, $market);
            $k4 = $playType['marketCode'];
//            @error_log('marketCode:    '.date("Y-m-d H:i:s").' '.$k4. ' '.$tmpId++."\n", 3, '/tmp/group/log_format.log');
            switch ($k4)
            {
                case 'ml':// 全场独赢(篮球)
                case 'ml1st':// 半场独赢(篮球)
                case 'mlq1':
                case 'mlq2':
                case 'mlq3':
                case 'mlq4':
                    if(checkStatus($playType)){
                        break;
                    }
                    $arrIndex = 0;
                    $tmpArray = &$readyArr[getBKPanKou($readyArr, $fieldName, $arrIndex, 'ml', $k4)];
                    $tmpArray[$fieldName[$arrIndex][0]] = 'Y';
                    $tmpArray[$fieldName[$arrIndex][1]] = $market['outcomes']['h']['odds'];
                    $tmpArray[$fieldName[$arrIndex][2]] = $market['outcomes']['a']['odds'];
                    $tmpArray[$fieldName[$arrIndex][3]] = check_null($market['outcomes']['d']['odds']);
                    break;
                case 'ah': //让球
                case 'ah1st'://让球上半场
                case 'ahq1':
                case 'ahq2':
                case 'ahq3':
                case 'ahq4':
                    // 全场让球。让球数小于0  主队让 H， 让球数大于0 客队让 C
                    if(checkStatus($playType)){
                        break;
                    }
                    $arrIndex = 1;
                    $curArr = &$readyArr[getBKPanKou($readyArr, $fieldName, $arrIndex, 'ah', $k4)];

                    $curArr[$fieldName[$arrIndex][0]]='Y';
                    $RATIO_RE = $market['ename'];
                    if (strlen($RATIO_RE)>1){
                        $jiajian=substr($RATIO_RE , 0 , 1);
                        $curArr[$fieldName[$arrIndex][3]]=substr($RATIO_RE,1);;
                        if ($jiajian=='-'){
                            $curArr['strong']='H';
                        }else{
                            $curArr['strong']='C';
                        }
                    }else{
                        $curArr[$fieldName[$arrIndex][3]]=$RATIO_RE;
                        $curArr['strong']='H';
                    }
                    $curArr[$fieldName[$arrIndex][1]] = $market['outcomes']['h']['odds'];   //让球主赔率
                    $curArr[$fieldName[$arrIndex][2]] = $market['outcomes']['a']['odds'];   //让球客赔率
                    break;
                case 'ou': //总得分大小
                case 'ou1st'://得分上半场
                case 'ouq1':
                case 'ouq2':
                case 'ouq3':
                case 'ouq4':
                    if(checkStatus($playType)){
                        break;
                    }
                    $arrIndex = 2;
                    if(strpos($market['description'], '球队得分-主队') === 0)
                    {
                        $arrIndex = 4;
                    }else if(strpos($market['description'], '球队得分-客队') === 0){
                        $arrIndex = 5;
                    }
                    $curArr = &$readyArr[getBKPanKou($readyArr, $fieldName, $arrIndex, 'ou', $k4)];

                    $curArr[$fieldName[$arrIndex][0]] = 'Y';
                    $curArr[$fieldName[$arrIndex][1]] = $market['ename'];
                    $curArr[$fieldName[$arrIndex][2]] = $market['ename'];
                    $curArr[$fieldName[$arrIndex][3]] = $market['outcomes']['ov']['odds'];  //大
                    $curArr[$fieldName[$arrIndex][4]] = $market['outcomes']['un']['odds'];  //小
                    break;
                case 'oe':// 单双
                case 'oe1st':// 单双 (上半场)
                case 'oeq1':
                case 'oeq2':
                case 'oeq3':
                case 'oeq4':
                    if(checkStatus($playType)){
                        break;
                    }
                    $arrIndex = 3;
                    $tmpArray = &$readyArr[getBKPanKou($readyArr, $fieldName, $arrIndex, 'oe', $k4)];
                    $tmpArray[$fieldName[$arrIndex][0]] = 'Y';
                    $tmpArray[$fieldName[$arrIndex][1]] = $market['outcomes']['od']['odds'];
                    $tmpArray[$fieldName[$arrIndex][2]] = $market['outcomes']['ev']['odds'];
                    break;
                case 'digitH_digitH_HomeFinalScore_LastDigit'://篮球得分最后一位数（主队）
                case 'digitA_digitA_AwayFinalScore_LastDigit'://篮球得分最后一位数（客队）
                    $offset = 0;
                    $arrIndex = 6;
                    if($k4 == 'digitA_digitA_AwayFinalScore_LastDigit'){
                        $offset += count($last_num);
                    }
                    $primeArray[$fieldName[$arrIndex][0]] = 'Y';
                    for($i = 0; $i < count($last_num); $i++){
                        $primeArray[$fieldName[$arrIndex][$i + $offset]] = $last_num[$i];
                    }
                    break;
                default:
                    break;
                }
            }
        }

    //补充gid信息
    $nodesNum = array(1, 0, 0, 0, 0, 0, 0);
    $gid = $primeArray['gid'];
    foreach ($readyArr as $k => $innerArr){
//        @error_log('bk_gid:    '.date("Y-m-d H:i:s").' '.$innerArr['gid'].' '."\n", 3, '/tmp/group/log_format.log');
        if(is_null($innerArr['gid'])){
            if(is_null($innerArr['description'])){
                $readyArr[$k]['gid'] = (string)($gid + ($nodesNum[0]++) * 7);
                if(substr($readyArr[$k]['gid'], -2 ) > 10) {    //MID+14
                    $readyArr[$k]['gid_fs'] = (string)($primeArray['gid'] .'_'. substr($readyArr[$k]['gid'], -2 ));
                }else{  //MID+7
                    $readyArr[$k]['gid_fs'] = (string)($primeArray['gid'] .'_'. substr($readyArr[$k]['gid'] , -1));
                }
            }else if($innerArr['description'] == '上半场'){
                $readyArr[$k]['gid'] = (string)($gid + ($nodesNum[1]++) * 7 + 1);
                $readyArr[$k]['gid_fs'] = (string)($gid .'_'. 1);
            }else if($innerArr['description'] == '第一节'){
                $readyArr[$k]['gid'] = (string)($gid + ($nodesNum[3]++) * 7 + 3);
                $readyArr[$k]['gid_fs'] = (string)($gid .'_'. 3);
            }else if($innerArr['description'] == '第二节'){
                $readyArr[$k]['gid'] = (string)($gid + ($nodesNum[4]++) * 7 + 4);
                $readyArr[$k]['gid_fs'] = (string)($gid .'_'. 4);
            }else if($innerArr['description'] == '第三节'){
                $readyArr[$k]['gid'] = (string)($gid + ($nodesNum[5]++) * 7 + 5);
                $readyArr[$k]['gid_fs'] = (string)($gid .'_'. 5);
            }else if($innerArr['description'] == '第四节'){
                $readyArr[$k]['gid'] = (string)($gid + ($nodesNum[6]++) * 7 + 6);
                $readyArr[$k]['gid_fs'] = (string)($gid .'_'. 6);
            }
//            $readyArr[$k]['gid'] = $gid;
        }
    }

//补充附属盘口字段
    foreach ($readyArr as $k => $innerArr){
        if($k ==0){
            continue;
        }
        $readyArr[$k]['matchId'] = $readyArr[0]['matchId']; //原主盘口gid
        $readyArr[$k]['midfield'] = '';
        $readyArr[$k]['Live'] = $readyArr[0]['Live'];
        $readyArr[$k]['re_time'] = $readyArr[0]['re_time'];
        $readyArr[$k]['clock'] = $readyArr[0]['clock'];
        $readyArr[$k]['league'] = $readyArr[0]['league'];
        $readyArr[$k]['datetime'] = $readyArr[0]['datetime'];
        $readyArr[$k]['startTime'] = $readyArr[0]['startTime'];
        $readyArr[$k]['se_now'] = $readyArr[0]['se_now'];
        $readyArr[$k]['session'] = check_null($readyArr[$k]['description']);
    }
    fillSwitchInfo($fieldName, $readyArr);

    for($i = 0; $i < count($readyArr); $i++){
//        $readyArr[$i]['gid_fs'] = $primeArray['gid'].'_'.$i;
        if(is_null($readyArr[$i]['description'])){
            $readyArr[$i]['description'] = '';
        }
    }
//    @error_log(date("Y-m-d H:i:s").'   bk trans end:    '."\n", 3, '/tmp/group/log_format.log');
    return $readyArr;
}

//检测状态是否有效
function checkStatus(&$playType){
    foreach ($playType['outcomes'] as $innerInfo){
        if(!is_null($innerInfo['status'])){
            return ($innerInfo['status'] !== 'available');
        }
    }
    return true;
}

//补充各玩法开关
function fillSwitchInfo(&$fieldName, &$readyArr){
    foreach ($readyArr as $k => $innerArr){
        foreach ($fieldName as $playInfo){
            if(count($playInfo) > 1){
                if(!array_key_exists($playInfo[0], $innerArr)){
                    $readyArr[$k][$playInfo[0]] = 'N';
                }
            }
        }
    }
}

//补充积分信息 info q1-q4 第一节-第四节 1h 上半场 2h 下半场 ot加时 ft 总场比分
function fillScoreInfo(&$primeArray, &$info){
    $scoreFiled = array('sc_FT_H', 'sc_FT_A', 'sc_OT_H', 'sc_OT_A', 'sc_H1_H', 'sc_H1_A', 'sc_H2_H', 'sc_H2_A',
                        'sc_Q1_H', 'sc_Q1_A', 'sc_Q2_H', 'sc_Q2_A', 'sc_Q3_H', 'sc_Q3_A', 'sc_Q4_H', 'sc_Q4_A');
    for($i = 0; $i < count($scoreFiled); $i++){
        $primeArray[$scoreFiled[$i]] = '0';
    }

    foreach ($info as $valueInfo){
        switch ($valueInfo['period'])
        {
            case 'ft':
                $index = 0;
                break;
            case 'ot':  //0t
                $index = 1;
                break;
            case '1h':
                $index = 2;
                break;
            case '2h':
                $index = 3;
                break;
            case 'q1':
                $index = 4;
                break;
            case 'q2':
                $index = 5;
                break;
            case 'q3':
                $index = 6;
                break;
            case 'q4':
                $index = 7;
                break;
            default:
                $index = -1;
                break;
        }
        if($index !== -1){
            $primeArray[$scoreFiled[$index*2]] = $valueInfo['homeScore'];
            $primeArray[$scoreFiled[$index*2 + 1]] = $valueInfo['awayScore'];
        }
    }
}

//获取篮球盘口序号
function getBKPanKou(&$readyArr, &$fieldName, $arrIndex, $prefix, $k4){
    $curIndex = -1;
    foreach ($readyArr as $index => $innerArr){
        if(is_null($innerArr[$fieldName[$arrIndex][0]])){
            if((strpos($k4,$prefix.'1st') !== false) && ($innerArr['description'] == '上半场')){
                $curIndex = $index;
                break;
            }else if((strpos($k4,$prefix.'q1') !== false) && ($innerArr['description'] == '第一节')){
                $curIndex = $index;
                break;
            }else if((strpos($k4,$prefix.'q2') !== false) && ($innerArr['description'] == '第二节')){
                $curIndex = $index;
                break;
            }else if((strpos($k4,$prefix.'q3') !== false) && ($innerArr['description'] == '第三节')){
                $curIndex = $index;
                break;
            }else if((strpos($k4,$prefix.'q4') !== false) && ($innerArr['description'] == '第四节')){
                $curIndex = $index;
                break;
            }else if(($k4 == $prefix) && (is_null($innerArr['description']))){
                $curIndex = $index;
                break;
            }
        }
    }

    //添加新盘口
    if($curIndex == -1){
        $curIndex = count($readyArr);
        if(strpos($k4,'1st') !== false){
            $readyArr[$curIndex]['description'] = '上半场';
        }else if (strpos($k4,'q1') !== false) {
            $readyArr[$curIndex]['description'] = '第一节';
        }else if (strpos($k4,'q2') !== false){
            $readyArr[$curIndex]['description'] = '第二节';
        }else if (strpos($k4,'q3') !== false){
            $readyArr[$curIndex]['description'] = '第三节';
        }else if (strpos($k4,'q4') !== false){
            $readyArr[$curIndex]['description'] = '第四节';
        }
        if(!is_null($readyArr[$curIndex]['description'])){
            $readyArr[$curIndex]['team_h'] = $readyArr[0]['team_h'].' - ('. $readyArr[$curIndex]['description'].')';
            $readyArr[$curIndex]['team_c'] = $readyArr[0]['team_c'].' - ('. $readyArr[$curIndex]['description'].')';
        }else{
            $readyArr[$curIndex]['team_h'] = $readyArr[0]['team_h'];
            $readyArr[$curIndex]['team_c'] = $readyArr[0]['team_c'];
        }
    }
    return $curIndex;
}

//获取盘口序号
function getCurPanKou(&$readyArr, &$fieldName, $arrIndex, &$market){
    for($i = 0; $i < count($readyArr); $i++){
        if(is_null($readyArr[$i][$fieldName[$arrIndex][0]]) && ($readyArr[$i]['gid'] == $market['gid'])){
            return $i;
        }
    }
    $nextIndex = count($readyArr);
    $readyArr[$nextIndex]['description'] = $market['description'];
    $readyArr[$nextIndex]['gid'] = $market['gid'];
    return $nextIndex;
}

//篮球小节转换
function getliveStatus(&$liveStatus){
    //转成 Q1-Q4 第一节-第四节， H1上半场 H2 下半场 OT 加时 HT 半场
    if($liveStatus == '1h'){
        $team_active = strtoupper(strrev($liveStatus));   //H1
    }elseif($liveStatus == '2h') {
        $team_active = strtoupper(strrev($liveStatus));   //H2
    }else{
        $team_active = strtoupper($liveStatus);
    }
    return $team_active;
}

//"净胜球数"
function proc_WinningMargin(&$fieldName, &$playType, &$primeArray, $arrIndex){
    $typeCount = count($fieldName[$arrIndex]);
    for($i = 1; $i < $typeCount; $i++){
        $primeArray[$fieldName[$arrIndex][$i]] = '';
    }
    $tmpIndex = 1;
    foreach ($playType['outcomes'] as $innerInfo)
    {
        switch ($innerInfo['sx'])
        {
            case 'h0':
                $tmpIndex = 1;
                break;
            case 'h1':
                $tmpIndex = 2;
                break;
            case 'h2':
                $tmpIndex = 3;
                break;
            case 'h3':
                $tmpIndex = 4;
                break;
            case 'a0':
                $tmpIndex = 5;
                break;
            case 'a1':
                $tmpIndex = 6;
                break;
            case 'a2':
                $tmpIndex = 7;
                break;
            case 'a3':
                $tmpIndex = 8;
                break;
            case 'd1':
                $tmpIndex = 9;
                break;
            case 'd0':
                $tmpIndex = 10;
                break;
            default:
                $tmpIndex = 0;
                break;
        }
        if($tmpIndex !== 0){
            $primeArray[$fieldName[$arrIndex][$tmpIndex]] = $innerInfo['odds'];
        }
    }
}

//"独赢 & 进球 大/小 1.5"
function proc_FT_1X2_And_FT_OU_1p5($k4, &$fieldName, &$market, &$primeArray){
    $suffix = '&大';
    if (strpos($k4,'1p5')!==false){
        $arrIndex = 20;
    }else if (strpos($k4,'2p5')!==false){
        $arrIndex = 21;
    }else if (strpos($k4,'3p5')!==false){
        $arrIndex = 22;
    }else if (strpos($k4,'4p5')!==false){
        $arrIndex = 23;
    }else{
        $arrIndex = 24;
        $suffix = '&是';
    }
    $primeArray[$fieldName[$arrIndex][0]] = 'Y';
    for($i = 1; $i < count($fieldName[$arrIndex]); $i++){
        $primeArray[$fieldName[$arrIndex][$i]] = '';
    }

    foreach ($market['outcomes'] as $innerInfo){
        $tmpName = str_replace(' ', '', $innerInfo['name']);
        if((strpos($tmpName, $suffix)!==false)){
            if ((strpos($tmpName,$primeArray['team_h'])!==false)){
                $primeArray[$fieldName[$arrIndex][1]] = $innerInfo['odds'];
            }else if ((strpos($tmpName,'和局')!==false)){
                $primeArray[$fieldName[$arrIndex][2]] = $innerInfo['odds'];
            }else{
                $primeArray[$fieldName[$arrIndex][3]] = $innerInfo['odds'];
            }
        }else{
            if ((strpos($tmpName,$primeArray['team_h'])!==false)){
                $primeArray[$fieldName[$arrIndex][4]] = $innerInfo['odds'];
            }else if ((strpos($tmpName,'和局')!==false)){
                $primeArray[$fieldName[$arrIndex][5]] = $innerInfo['odds'];
            }else{
                $primeArray[$fieldName[$arrIndex][6]] = $innerInfo['odds'];
            }
        }
//      @error_log(':    '.date("Y-m-d H:i:s").$tmpName.$innerInfo['odds']."\n", 3, '/tmp/group/log_format.log');
    }
}

//"进球 大/小 1.5 & 双方球队进球"
function proc_FT_OU_1p5_And_BothTeamsToScore($k4, &$fieldName, &$market, &$primeArray){
    $arrIndex = 0;
    if (strpos($k4,'1p5')!==false){
        $arrIndex = 25;
    }else if (strpos($k4,'2p5')!==false){
        $arrIndex = 26;
    }else if (strpos($k4,'3p5')!==false){
        $arrIndex = 27;
    }else if (strpos($k4,'4p5')!==false){
        $arrIndex = 28;
    }
    $primeArray[$fieldName[$arrIndex][0]] = 'Y';
    $nameArr = array(
        '大&是',
        '大&不是',
        '小&是',
        '小&不是');

    for ($i = 0; $i < count($nameArr); $i++){
        $primeArray[$fieldName[$arrIndex][$i+1]] = '';
    }

    for ($i = 0; $i < count($nameArr); $i++){
        foreach ($market['outcomes'] as $innerInfo){
            $tmpName = str_replace(' ', '', $innerInfo['name']);
            if ((strpos($tmpName, $nameArr[$i])!==false)){
                $primeArray[$fieldName[$arrIndex][$i+1]] = $innerInfo['odds'];
                break;
            }
        }
    }
}

//"双重机会 & 进球 大/小 1.5"
function proc_DoubleChance_And_FT_OU_1p5($k4, &$fieldName, &$market, &$primeArray){
    $sufix1 = '大';
    $sufix2 = '小';
    if (strpos($k4,'1p5')!==false){
        $arrIndex = 34;
    }else if (strpos($k4,'2p5')!==false){
        $arrIndex = 35;
    }else if (strpos($k4,'3p5')!==false){
        $arrIndex = 36;
    }else if (strpos($k4,'4p5')!==false){
        $arrIndex = 37;
    }else{
        $arrIndex = 38;
        $sufix1 = '是';
        $sufix2 = '不是';
    }
    $primeArray[$fieldName[$arrIndex][0]] = 'Y';
    $nameArr = array(
        $primeArray['team_h'].'/和局&'.$sufix1,$primeArray['team_c'].'/和局&'.$sufix1,
        $primeArray['team_h'].'/'.$primeArray['team_c'].'&'.$sufix1,
        $primeArray['team_h'].'/和局&'.$sufix2,$primeArray['team_c'].'/和局&'.$sufix2,
        $primeArray['team_h'].'/'.$primeArray['team_c'].'&'.$sufix2);

    for ($i = 0; $i < count($nameArr); $i++){
        $primeArray[$fieldName[$arrIndex][$i+1]] = '';
    }

    for ($i = 0; $i < count($nameArr); $i++){
        foreach ($market['outcomes'] as $innerInfo){
            $tmpName = str_replace(' ', '', $innerInfo['name']);
            if ((strpos($tmpName, $nameArr[$i])!==false)){
                $primeArray[$fieldName[$arrIndex][$i+1]] = $innerInfo['odds'];
                break;
            }
        }
    }
}

//"进球 大/小 1.5 & 进球数 单 / 双"
function proc_FT_OU_1p5_And_FT_OE($k4, &$fieldName, &$market, &$primeArray){
    $arrIndex = 0;
    if (strpos($k4,'1p5')!==false){
        $arrIndex = 39;
    }else if (strpos($k4,'2p5')!==false){
        $arrIndex = 40;
    }else if (strpos($k4,'3p5')!==false){
        $arrIndex = 41;
    }else if (strpos($k4,'4p5')!==false){
        $arrIndex = 42;
    }
    $primeArray[$fieldName[$arrIndex][0]] = 'Y';
    for($i = 1; $i < count($fieldName[$arrIndex]); $i++){
        $primeArray[$fieldName[$arrIndex][$i]] = '';
    }

    foreach ($market['outcomes'] as $innerInfo){
        if ((strpos($innerInfo['name'], '大')!==false)){
            if ((strpos($innerInfo['name'], '单')!==false)){
                $primeArray[$fieldName[$arrIndex][1]] = $innerInfo['odds'];
            }else{
                $primeArray[$fieldName[$arrIndex][2]] = $innerInfo['odds'];
            }
        }else{
            if ((strpos($innerInfo['name'], '单')!==false)){
                $primeArray[$fieldName[$arrIndex][3]] = $innerInfo['odds'];
            }else{
                $primeArray[$fieldName[$arrIndex][4]] = $innerInfo['odds'];
            }
        }
    }
}

//null转换
function check_null($data){
    if(is_null($data)){
        return '';
    }
    return $data;
}

//BK gid转换
function transGid($gid){
    if(!empty($gid)) {
        $gid = $gid . strval('00');
    }
    return $gid;
}

 /* 根据gid，以及传入的盘口的matkets，整理玩法需要的数据，【让球、大小、单双、独赢】
 *
 * @param $gid
 * @param $markets
 * @param $aGames
 * @return mixed
 * $type: re 滚球, 其他 今日和早盘
 */
function getMethodData($gid, $markets, $aGames,$type){
    if(!$type){
        $type='notRe';
    }
    // 整理附属盘口
    $aGames_tmp = $aGames[$gid];
    $market_ah=$market_ah1st=$market_ou=$market_ou1st=$market_oe=$market_1x2=$market_1x21st=[];
    foreach ($markets as $k3 => $market){

        if ($market['eventId']==$gid){ // 正常的附属盘口的处理
            if ($market['marketCode'] == 'ah') { // 全场让球
                $market_ah[] = $market;
            }
            if ($market['marketCode'] == 'ah1st') { // 让球-上半场
                $market_ah1st[] = $market;
            }
            if ($market['marketCode'] == 'ou') { // 全场大小
                $market_ou[] = $market;
            }
            if ($market['marketCode'] == 'ou1st') { // 半场大小
                $market_ou1st[] = $market;
            }
            if ($market['marketCode'] == 'oe') { // 全场单双
                $market_oe[] = $market;
            }
            if ($market['marketCode'] == '1x2') { // 全场独赢
                $market_1x2[] = $market;
            }
            if ($market['marketCode'] == '1x21st') { // 半场独赢
                $market_1x21st[] = $market;
            }

            // BK
            // mlq1 独赢盘-第一节
            // ml1st 独赢盘-上半场
            // ml 独赢盘
            // ouq1 总得分:大 / 小-第一节
            // ou1st 总得分:大 / 小-上半场
            // ahq1 让球-第一节
        }
        else{

            // 特殊盘口的处理，
            //角球、
            //罚牌、
            //会晋级、
            //加时赛、
            //加时赛 - 球队进球数-主队、
            //加时赛 - 球队进球数-客队
            //角球加时赛、
            //加时赛 - 罚牌数
            if ($market['ctidDescription']=='角球' || $market['ctidDescription']=='加时赛'){

                if ($market['marketCode'] == 'ah') {
                    $market_cn_ah[] = $market;
                }
                if ($market['marketCode'] == 'ah1st') {
                    $market_cn_ah1st[] = $market;
                }
                if ($market['marketCode'] == 'ou') {
                    $market_cn_ou[] = $market;
                }
                if ($market['marketCode'] == 'ou1st') {
                    $market_cn_ou1st[] = $market;
                }
                if ($market['marketCode'] == 'oe') {
                    $market_cn_oe[] = $market;
                }
                if ($market['marketCode'] == '1x2') {
                    $market_cn_1x2[] = $market;
                }
                if ($market['marketCode'] == '1x21st') {
                    $market_cn_1x21st[] = $market;
                }

            }
        }

    }

    // 附属盘口的数据整理
    foreach ($market_ah as $k_ah => $market){
        $gidFs=$market['eventId']+$k_ah;

        $aGames[$gidFs] = $aGames_tmp;
        $aGames[$gidFs]['GID']=$gidFs;
        if ($k_ah!=0){ $aGames[$gidFs]['MORE']='';}

        // 全场让球数
        $RATIO_RE = $market['ename'];
        if (strlen($RATIO_RE)>1){
            $jiajian=substr($RATIO_RE , 0 , 1);
            if($type=='re'){ // 滚球
                $aGames[$gidFs]['RATIO_RE']=substr($RATIO_RE,1);
            }else{
                $aGames[$gidFs]['RATIO_R']=substr($RATIO_RE,1);
            }
            if ($jiajian=='-'){
                $aGames[$gidFs]['STRONG']='H';
            }else{
                $aGames[$gidFs]['STRONG']='C';
            }
        }
        else{
            if($type=='re'){ // 滚球
                $aGames[$gidFs]['RATIO_RE']=$RATIO_RE;
            }else{
                $aGames[$gidFs]['RATIO_R']=$RATIO_RE;
            }
            $aGames[$gidFs]['STRONG']='H';
        }
        $outcomes=$market['outcomes'];
        // 全场让球
        foreach ($outcomes as $k4 => $v4){
            if ($v4['outcomeCode']=='h'){ // 主队赔率
                if($type=='re'){ // 滚球
                    $aGames[$gidFs]['IOR_REH'] = $v4['odds'];
                }else{
                    $aGames[$gidFs]['IOR_RH'] = $v4['odds'];
                }

            }
            if ($v4['outcomeCode']=='a'){ // 客队赔率
                if($type=='re'){ // 滚球
                    $aGames[$gidFs]['IOR_REC'] = $v4['odds'];
                }else{
                    $aGames[$gidFs]['IOR_RC'] = $v4['odds'];
                }
            }
        }

        // 让球上半场。让球数小于0  主队让 H， 让球数大于0 客队让 C
        if ($market_ah1st[$k_ah]['marketCode']=='ah1st'){
            $RATIO_HRE = $market_ah1st[$k_ah]['ename'];
            if (strlen($RATIO_HRE)>1){
                $jiajian=substr($RATIO_HRE , 0 , 1);
                if($type=='re'){ // 滚球
                    $aGames[$gidFs]['RATIO_HRE']=substr($RATIO_HRE,1);
                }else{
                    $aGames[$gidFs]['RATIO_HR']=substr($RATIO_HRE,1);
                }

                if ($jiajian=='-'){
                    $aGames[$gidFs]['HSTRONG']='H';
                }else{
                    $aGames[$gidFs]['HSTRONG']='C';
                }
            }
            else{
                if($type=='re'){ // 滚球
                    $aGames[$gidFs]['RATIO_HRE']=$RATIO_HRE;
                }else{
                    $aGames[$gidFs]['RATIO_HE']=$RATIO_HRE;
                }
                $aGames[$gidFs]['HSTRONG']='H';
            }

            $outcomes=$market_ah1st[$k_ah]['outcomes'];
            foreach ($outcomes as $k4 => $v4){
                if ($v4['outcomeCode']=='h'){ // 主队赔率
                    if($type=='re'){ // 滚球
                        $aGames[$gidFs]['IOR_HREH'] = $v4['odds'];
                    }else{
                        $aGames[$gidFs]['IOR_HRH'] = $v4['odds'];
                    }
                }
                if ($v4['outcomeCode']=='a'){ // 客队赔率
                    if($type=='re'){ // 滚球
                        $aGames[$gidFs]['IOR_HREC'] = $v4['odds'];
                    }else{
                        $aGames[$gidFs]['IOR_HRC'] = $v4['odds'];
                    }

                }
            }
        }

        // 全场大小
        if ($market_ou[$k_ah]['marketCode']=='ou'){

            if($type=='re'){ // 滚球
                $aGames[$gidFs]['RATIO_ROUO']='O'.$market_ou[$k_ah]['ename'];
                $aGames[$gidFs]['RATIO_ROUU']='U'.$market_ou[$k_ah]['ename'];
            }else{
                $aGames[$gidFs]['RATIO_OUO']='O'.$market_ou[$k_ah]['ename'];
                $aGames[$gidFs]['RATIO_OUU']='U'.$market_ou[$k_ah]['ename'];
            }

            $outcomes=$market_ou[$k_ah]['outcomes'];
            foreach ($outcomes as $k4 => $v4){
                if ($v4['outcomeCode']=='ov'){ // 大的赔率
                    if($type=='re'){ // 滚球
                        $aGames[$gidFs]['IOR_ROUH'] = $v4['odds'];
                    }else{
                        $aGames[$gidFs]['IOR_OUH'] = $v4['odds'];
                    }

                }
                if ($v4['outcomeCode']=='un'){ // 小的赔率
                    if($type=='re'){ // 滚球
                        $aGames[$gidFs]['IOR_ROUC'] = $v4['odds'];
                    }else{
                        $aGames[$gidFs]['IOR_OUC'] = $v4['odds'];
                    }

                }
            }
        }

        // 进球大小上半场
        if ($market_ou1st[$k_ah]['marketCode']=='ou1st'){
            if($type=='re'){ // 滚球
                $aGames[$gidFs]['RATIO_HROUO']='O'.$market_ou1st[$k_ah]['ename'];
                $aGames[$gidFs]['RATIO_HROUU']='U'.$market_ou1st[$k_ah]['ename'];
            }else{
                $aGames[$gidFs]['RATIO_HOUO']='O'.$market_ou1st[$k_ah]['ename'];
                $aGames[$gidFs]['RATIO_HOUU']='U'.$market_ou1st[$k_ah]['ename'];
            }

            $outcomes=$market_ou1st[$k_ah]['outcomes'];
            foreach ($outcomes as $k4 => $v4){
                if ($v4['outcomeCode']=='ov'){ // 大的赔率
                    if($type=='re'){ // 滚球
                        $aGames[$gidFs]['IOR_ROUH'] = $v4['odds'];
                    }else{
                        $aGames[$gidFs]['IOR_HOUH'] = $v4['odds'];
                    }

                }
                if ($v4['outcomeCode']=='un'){ // 小的赔率
                    if($type=='re'){ // 滚球
                        $aGames[$gidFs]['IOR_ROUC'] = $v4['odds'];
                    }else{
                        $aGames[$gidFs]['IOR_HOUC'] = $v4['odds'];
                    }

                }
            }
        }
        // 单双
        if ($market_oe[$k_ah]['marketCode']=='oe'){
            $outcomes=$market_oe[$k_ah]['outcomes'];
            foreach ($outcomes as $k4 => $v4){
                if ($v4['outcomeCode']=='od'){
                    if($type=='re'){ // 滚球
                        $aGames[$gidFs]['IOR_REOO'] = $v4['odds'];    //euOdds
                    }else{
                        $aGames[$gidFs]['IOR_EOO'] = $v4['odds'];
                    }

                }
                if ($v4['outcomeCode']=='ev'){
                    if($type=='re'){ // 滚球
                        $aGames[$gidFs]['IOR_REOE'] = $v4['odds'];
                    }else{
                        $aGames[$gidFs]['IOR_EOE'] = $v4['odds'];
                    }

                }
            }
        }
        // 全场独赢
        if ($market_1x2[$k_ah]['marketCode']=='1x2'){
            $outcomes=$market_1x2[$k_ah]['outcomes'];
            foreach ($outcomes as $k4 => $v4){
                if ($v4['outcomeCode']=='h'){ // 主队赔率
                    if($type=='re'){ // 滚球
                        $aGames[$gidFs]['IOR_RMH'] = $v4['odds'];
                    }else{
                        $aGames[$gidFs]['IOR_MH'] = $v4['odds'];
                    }

                }
                if ($v4['outcomeCode']=='a'){ // 客队赔率
                    if($type=='re'){ // 滚球
                        $aGames[$gidFs]['IOR_RMC'] = $v4['odds'];
                    }else{
                        $aGames[$gidFs]['IOR_MC'] = $v4['odds'];
                    }

                }
                if ($v4['outcomeCode']=='d'){ // 和赔率
                    if($type=='re'){ // 滚球
                        $aGames[$gidFs]['IOR_RMN'] = $v4['odds'];
                    }else{
                        $aGames[$gidFs]['IOR_MN'] = $v4['odds'];
                    }

                }
            }
        }

        // 半场独赢
        if ($market_1x21st[$k_ah]['marketCode']=='1x21st'){
            $outcomes=$market_1x21st[$k_ah]['outcomes'];
            foreach ($outcomes as $k4 => $v4){
                if ($v4['outcomeCode']=='h'){ // 主队赔率
                    if($type=='re'){ // 滚球
                        $aGames[$gidFs]['IOR_HRMH'] = $v4['odds'];
                    }else{
                        $aGames[$gidFs]['IOR_HMH'] = $v4['odds'];
                    }

                }
                if ($v4['outcomeCode']=='a'){ // 客队赔率
                    if($type=='re'){ // 滚球
                        $aGames[$gidFs]['IOR_HRMC'] = $v4['odds'];
                    }else{
                        $aGames[$gidFs]['IOR_HMC'] = $v4['odds'];
                    }

                }
                if ($v4['outcomeCode']=='d'){ // 和赔率
                    if($type=='re'){ // 滚球
                        $aGames[$gidFs]['IOR_HRMN'] = $v4['odds'];
                    }else{
                        $aGames[$gidFs]['IOR_HMN'] = $v4['odds'];
                    }

                }
            }
        }
    }

    // 角球/加时赛 附属盘口的数据整理
    foreach ($market_cn_ah as $k_ah => $market){
        $gidFs=$market['eventId']+$k_ah;

        $aGames[$gidFs] = $aGames_tmp;
        if ($market['ctidDescription']=='角球'){
            $aGames[$gidFs]['TEAM_H'] .= ' -'.$market['ctidDescription'].'数';
            $aGames[$gidFs]['TEAM_C'] .= ' -'.$market['ctidDescription'].'数';
        }
        elseif ($market['ctidDescription']=='加时赛'){
            $aGames[$gidFs]['TEAM_H'] .= ' -'.$market['ctidDescription'];
            $aGames[$gidFs]['TEAM_C'] .= ' -'.$market['ctidDescription'];
        }
        $aGames[$gidFs]['GID']=$gidFs;
        $aGames[$gidFs]['MORE']='';

        // 全场让球
        $RATIO_RE = $market['ename'];
        if (strlen($RATIO_RE)>1){
            $jiajian=substr($RATIO_RE , 0 , 1);
            $aGames[$gidFs]['RATIO_RE']=substr($RATIO_RE,1);
            if ($jiajian=='-'){
                $aGames[$gidFs]['STRONG']='H';
            }else{
                $aGames[$gidFs]['STRONG']='C';
            }
        }
        else{
            $aGames[$gidFs]['RATIO_RE']=$RATIO_RE;
            $aGames[$gidFs]['STRONG']='H';
        }
        $outcomes=$market['outcomes'];
        foreach ($outcomes as $k4 => $v4){
            if ($v4['outcomeCode']=='h'){ // 主队赔率
                $aGames[$gidFs]['IOR_REH'] = $v4['odds'];
            }
            if ($v4['outcomeCode']=='a'){ // 客队赔率
                $aGames[$gidFs]['IOR_REC'] = $v4['odds'];
            }
        }

        // 让球上半场。让球数小于0  主队让 H， 让球数大于0 客队让 C
        if ($market_cn_ah1st[$k_ah]['marketCode']=='ah1st'){
            $RATIO_HRE = $market_cn_ah1st[$k_ah]['ename'];
            if (strlen($RATIO_HRE)>1){
                $jiajian=substr($RATIO_HRE , 0 , 1);
                $aGames[$gidFs]['RATIO_HRE']=substr($RATIO_HRE,1);
                if ($jiajian=='-'){
                    $aGames[$gidFs]['HSTRONG']='H';
                }else{
                    $aGames[$gidFs]['HSTRONG']='C';
                }
            }
            else{
                $aGames[$gidFs]['RATIO_HRE']=$RATIO_HRE;
                $aGames[$gidFs]['HSTRONG']='H';
            }
            $outcomes=$market_cn_ah1st[$k_ah]['outcomes'];
            foreach ($outcomes as $k4 => $v4){
                if ($v4['outcomeCode']=='h'){ // 主队赔率
                    $aGames[$gidFs]['IOR_HREH'] = $v4['odds'];
                }
                if ($v4['outcomeCode']=='a'){ // 客队赔率
                    $aGames[$gidFs]['IOR_HREC'] = $v4['odds'];
                }
            }
        }

        // 全场大小
        if ($market_cn_ou[$k_ah]['marketCode']=='ou'){
            $aGames[$gidFs]['RATIO_ROUO']='O'.$market_cn_ou[$k_ah]['ename'];
            $aGames[$gidFs]['RATIO_ROUU']='U'.$market_cn_ou[$k_ah]['ename'];
            $outcomes=$market_cn_ou[$k_ah]['outcomes'];
            foreach ($outcomes as $k4 => $v4){
                if ($v4['outcomeCode']=='ov'){ // 大的赔率
                    $aGames[$gidFs]['IOR_ROUH'] = $v4['odds'];
                }
                if ($v4['outcomeCode']=='un'){ // 小的赔率
                    $aGames[$gidFs]['IOR_ROUC'] = $v4['odds'];
                }
            }
        }

        // 进球大小上半场
        if ($market_cn_ou1st[$k_ah]['marketCode']=='ou1st'){
            $aGames[$gidFs]['RATIO_HROUO']='O'.$market_cn_ou1st[$k_ah]['ename'];
            $aGames[$gidFs]['RATIO_HROUU']='U'.$market_cn_ou1st[$k_ah]['ename'];
            $outcomes=$market_cn_ou1st[$k_ah]['outcomes'];
            foreach ($outcomes as $k4 => $v4){
                if ($v4['outcomeCode']=='ov'){ // 大的赔率
                    $aGames[$gidFs]['IOR_ROUH'] = $v4['odds'];
                }
                if ($v4['outcomeCode']=='un'){ // 小的赔率
                    $aGames[$gidFs]['IOR_ROUC'] = $v4['odds'];
                }
            }
        }
        // 单双
        if ($market_cn_oe[$k_ah]['marketCode']=='oe'){
            $outcomes=$market_cn_oe[$k_ah]['outcomes'];
            foreach ($outcomes as $k4 => $v4){
                if ($v4['outcomeCode']=='od'){
                    $aGames[$gidFs]['IOR_REOO'] = $v4['euOdds'];
                }
                if ($v4['outcomeCode']=='ev'){
                    $aGames[$gidFs]['IOR_REOE'] = $v4['euOdds'];
                }
            }
        }
        // 全场独赢
        if ($market_cn_1x2[$k_ah]['marketCode']=='1x2'){
            $outcomes=$market_cn_1x2[$k_ah]['outcomes'];
            foreach ($outcomes as $k4 => $v4){
                if ($v4['outcomeCode']=='h'){ // 主队赔率
                    $aGames[$gidFs]['IOR_RMH'] = $v4['odds'];
                }
                if ($v4['outcomeCode']=='a'){ // 客队赔率
                    $aGames[$gidFs]['IOR_RMC'] = $v4['odds'];
                }
                if ($v4['outcomeCode']=='d'){ // 客队赔率
                    $aGames[$gidFs]['IOR_RMN'] = $v4['odds'];
                }
            }
        }

        // 半场独赢
        if ($market_cn_1x21st[$k_ah]['marketCode']=='1x21st'){
            $outcomes=$market_cn_1x21st[$k_ah]['outcomes'];
            foreach ($outcomes as $k4 => $v4){
                if ($v4['outcomeCode']=='h'){ // 主队赔率
                    $aGames[$gidFs]['IOR_HRMH'] = $v4['odds'];
                }
                if ($v4['outcomeCode']=='a'){ // 客队赔率
                    $aGames[$gidFs]['IOR_HRMC'] = $v4['odds'];
                }
                if ($v4['outcomeCode']=='d'){ // 客队赔率
                    $aGames[$gidFs]['IOR_HRMN'] = $v4['odds'];
                }
            }
        }
    }

    return $aGames;
}

/**
 * 6686主盘口玩法数据转换
 *
 * @param $gid
 * @param $markets
 * @param $type
 * @return mixed
 */
function masterMethodsTrans($markets, $type){
    if(!$type){
        $type='notRe';
    }

    foreach ($markets as $k3 => $market){
        $gid=$market['eventId'];
        $aGames[$gid]['GID']=$gid;
        if ($market['marketCode'] == 'ah' and ($market['ctid']==0 or $market['ctid']==1 or $market['ctid']==3)) { // 全场让球
            // 全场让球数
            $RATIO_RE = $market['ename'];
            if (strlen($RATIO_RE)>1){
                $jiajian=substr($RATIO_RE , 0 , 1);
                if($type=='re'){ // 滚球
                    $aGames[$gid]['RATIO_RE']=substr($RATIO_RE,1);
                }else{
                    $aGames[$gid]['RATIO_R']=substr($RATIO_RE,1);
                }
                if ($jiajian=='-'){
                    $aGames[$gid]['STRONG']='H';
                }else{
                    $aGames[$gid]['STRONG']='C';
                }
            }
            else{
                if($type=='re'){ // 滚球
                    $aGames[$gid]['RATIO_RE']=$RATIO_RE;
                }else{
                    $aGames[$gid]['RATIO_R']=$RATIO_RE;
                }
                $aGames[$gid]['STRONG']='H';
            }
            $outcomes=$market['outcomes'];
            // 全场让球
            foreach ($outcomes as $k4 => $v4){
                if ($v4['outcomeCode']=='h' and $v4['status']=='available'){ // 主队赔率
                    if($type=='re'){ // 滚球
                        $aGames[$gid]['IOR_REH'] = $v4['odds'];
                    }else{
                        $aGames[$gid]['IOR_RH'] = $v4['odds'];
                    }

                }
                if ($v4['outcomeCode']=='a' and $v4['status']=='available'){ // 客队赔率
                    if($type=='re'){ // 滚球
                        $aGames[$gid]['IOR_REC'] = $v4['odds'];
                    }else{
                        $aGames[$gid]['IOR_RC'] = $v4['odds'];
                    }
                }
            }
        }
        if ($market['marketCode'] == 'ah1st' and $market['ctid']==0) { // 让球-上半场
            $RATIO_HRE = $market['ename'];
            if (strlen($RATIO_HRE)>1){
                $jiajian=substr($RATIO_HRE , 0 , 1);
                if($type=='re'){ // 滚球
                    $aGames[$gid]['RATIO_HRE']=substr($RATIO_HRE,1);
                }else{
                    $aGames[$gid]['RATIO_HR']=substr($RATIO_HRE,1);
                }

                if ($jiajian=='-'){
                    $aGames[$gid]['HSTRONG']='H';
                }else{
                    $aGames[$gid]['HSTRONG']='C';
                }
            }
            else{
                if($type=='re'){ // 滚球
                    $aGames[$gid]['RATIO_HRE']=$RATIO_HRE;
                }else{
                    $aGames[$gid]['RATIO_HR']=$RATIO_HRE;
                }
                $aGames[$gid]['HSTRONG']='H';
            }

            $outcomes=$market['outcomes'];
            foreach ($outcomes as $k4 => $v4){
                if ($v4['outcomeCode']=='h' and $v4['status']=='available'){ // 主队赔率
                    if($type=='re'){ // 滚球
                        $aGames[$gid]['IOR_HREH'] = $v4['odds'];
                    }else{
                        $aGames[$gid]['IOR_HRH'] = $v4['odds'];
                    }
                }
                if ($v4['outcomeCode']=='a' and $v4['status']=='available'){ // 客队赔率
                    if($type=='re'){ // 滚球
                        $aGames[$gid]['IOR_HREC'] = $v4['odds'];
                    }else{
                        $aGames[$gid]['IOR_HRC'] = $v4['odds'];
                    }

                }
            }
        }
        if ($market['marketCode'] == 'ou' and ($market['ctid']==0 or $market['ctid']==1 or $market['ctid']==3)) { // 全场大小 ctid 0 ，足球、篮球通用
            if($type=='re'){ // 滚球
                $aGames[$gid]['RATIO_ROUO']='O'.$market['ename'];
                $aGames[$gid]['RATIO_ROUU']='U'.$market['ename'];
            }else{
                $aGames[$gid]['RATIO_OUO']='O'.$market['ename'];
                $aGames[$gid]['RATIO_OUU']='U'.$market['ename'];
            }

            $outcomes=$market['outcomes'];
            foreach ($outcomes as $k4 => $v4){
                if ($v4['outcomeCode']=='ov' and $v4['status']=='available'){ // 大的赔率
                    if($type=='re'){ // 滚球
                        $aGames[$gid]['IOR_ROUC'] = $v4['odds'];
                    }else{
                        $aGames[$gid]['IOR_OUC'] = $v4['odds'];
                    }

                }
                if ($v4['outcomeCode']=='un' and $v4['status']=='available'){ // 小的赔率
                    if($type=='re'){ // 滚球
                        $aGames[$gid]['IOR_ROUH'] = $v4['odds'];
                    }else{
                        $aGames[$gid]['IOR_OUH'] = $v4['odds'];
                    }

                }
            }
        }
        if ($market['marketCode'] == 'ou1st' and $market['ctid']==0) { // 半场大小
            if($type=='re'){ // 滚球
                $aGames[$gid]['RATIO_HROUO']='O'.$market['ename'];
                $aGames[$gid]['RATIO_HROUU']='U'.$market['ename'];
            }else{
                $aGames[$gid]['RATIO_HOUO']='O'.$market['ename'];
                $aGames[$gid]['RATIO_HOUU']='U'.$market['ename'];
            }

            $outcomes=$market['outcomes'];
            foreach ($outcomes as $k4 => $v4){
                if ($v4['outcomeCode']=='ov' and $v4['status']=='available'){ // 大的赔率
                    if($type=='re'){ // 滚球
                        $aGames[$gid]['IOR_HROUC'] = $v4['odds'];
                    }else{
                        $aGames[$gid]['IOR_HOUC'] = $v4['odds'];
                    }

                }
                if ($v4['outcomeCode']=='un' and $v4['status']=='available'){ // 小的赔率
                    if($type=='re'){ // 滚球
                        $aGames[$gid]['IOR_HROUH'] = $v4['odds'];
                    }else{
                        $aGames[$gid]['IOR_HOUH'] = $v4['odds'];
                    }

                }
            }
        }
        if ($market['marketCode'] == 'oe' and $market['ctid']==0) { // 全场单双
            $outcomes=$market['outcomes'];
            foreach ($outcomes as $k4 => $v4){
                if ($v4['outcomeCode']=='od' and $v4['status']=='available'){  //单
                    if($type=='re'){ // 滚球
                        $aGames[$gid]['IOR_REOO'] = $v4['euOdds'];  //欧洲盘
                    }else{
                        $aGames[$gid]['IOR_EOO'] = $v4['euOdds'];
                    }

                }
                if ($v4['outcomeCode']=='ev' and $v4['status']=='available'){  //双
                    if($type=='re'){ // 滚球
                        $aGames[$gid]['IOR_REOE'] = $v4['euOdds'];
                    }else{
                        $aGames[$gid]['IOR_EOE'] = $v4['euOdds'];
                    }

                }
            }
        }
        if ($market['marketCode'] == '1x2' and ($market['ctid']==0 or $market['ctid']==1 or $market['ctid']==3)) { // 全场独赢
            $outcomes=$market['outcomes'];
            foreach ($outcomes as $k4 => $v4){
                if ($v4['outcomeCode']=='h' and $v4['status']=='available'){ // 主队
                    if($type=='re'){ // 滚球
                        $aGames[$gid]['IOR_RMH'] = $v4['odds'];
                    }else{
                        $aGames[$gid]['IOR_MH'] = $v4['odds'];
                    }

                }
                if ($v4['outcomeCode']=='a' and $v4['status']=='available'){ // 客队
                    if($type=='re'){ // 滚球
                        $aGames[$gid]['IOR_RMC'] = $v4['odds'];
                    }else{
                        $aGames[$gid]['IOR_MC'] = $v4['odds'];
                    }

                }
                if ($v4['outcomeCode']=='d' and $v4['status']=='available'){ // 和
                    if($type=='re'){ // 滚球
                        $aGames[$gid]['IOR_RMN'] = $v4['odds'];
                    }else{
                        $aGames[$gid]['IOR_MN'] = $v4['odds'];
                    }

                }
            }
        }
        if ($market['marketCode'] == '1x21st' and $market['ctid']==0) { // 半场独赢
            $outcomes=$market['outcomes'];
            foreach ($outcomes as $k4 => $v4){
                if ($v4['outcomeCode']=='h' and $v4['status']=='available'){ // 主队
                    if($type=='re'){ // 滚球
                        $aGames[$gid]['IOR_HRMH'] = $v4['odds'];
                    }else{
                        $aGames[$gid]['IOR_HMH'] = $v4['odds'];
                    }

                }
                if ($v4['outcomeCode']=='a' and $v4['status']=='available'){ // 客队
                    if($type=='re'){ // 滚球
                        $aGames[$gid]['IOR_HRMC'] = $v4['odds'];
                    }else{
                        $aGames[$gid]['IOR_HMC'] = $v4['odds'];
                    }

                }
                if ($v4['outcomeCode']=='d' and $v4['status']=='available'){ // 和
                    if($type=='re'){ // 滚球
                        $aGames[$gid]['IOR_HRMN'] = $v4['odds'];
                    }else{
                        $aGames[$gid]['IOR_HMN'] = $v4['odds'];
                    }

                }
            }
        }

        // 波胆
        if ($market['marketCode'] == 'cs'){
            //波胆盘口
            $bodanType = array('1-0', '2-0', '2-1', '3-0', '3-1', '3-2', '4-0', '4-1', '4-2', '4-3',
                '0-0', '1-1', '2-2', '3-3', '4-4',
                '0-1', '0-2', '1-2', '0-3', '1-3', '2-3', '0-4', '1-4', '2-4', '3-4', 'other');
            //波胆上半场盘口
//            $h_bodantType = array('1-0', '2-0', '2-1', '3-0', '3-1', '3-2',
//                '0-0', '1-1', '2-2', '3-3',
//                '0-1', '0-2', '1-2', '0-3', '1-3', '2-3', 'other');
            if ($type=='re'){ $pre='IOR_R'; } else{ $pre='IOR_'; }
            foreach ($market['outcomes'] as $k4 => $v4){
                if (in_array($v4['outcomeCode'], $bodanType)){
                    if ($v4['sx'] == 'aos'){ $aGames[$gid][$pre.'OVH'] = $v4['euOdds']; }
                    else{ $aGames[$gid][$pre.str_replace('A','C',strtoupper($v4['sx']))] = $v4['euOdds']; }
                }
            }
        }

        //  篮球 第1球队得分-主队 - 大 / 小 ，ctid 41，sectionName(为空""时是全场)marketCode=ou,marketType=FT_OU
        if ($market['marketCode'] == 'ou' and $market['ctid']==41 and $market['sectionName']==''){
            if($type=='re'){ // 滚球
                $aGames[$gid]['ratio_rouho']= !empty($market['ename']) ? 'O'.$market['ename'] : '';
                $aGames[$gid]['ratio_rouhu']= !empty($market['ename']) ? 'U'.$market['ename'] : '';
            }else{
                $aGames[$gid]['ratio_ouho']= !empty($market['ename']) ? 'O'.$market['ename'] : '';
                $aGames[$gid]['ratio_ouhu']= !empty($market['ename']) ? 'U'.$market['ename'] : '';
            }
            $outcomes=$market['outcomes'];
            foreach ($outcomes as $k4 => $v4){
                if ($v4['outcomeCode']=='ov' and $v4['status']=='available'){ // 大的赔率
                    if($type=='re'){ // 滚球
                        $aGames[$gid]['ior_ROUHO'] = $v4['odds'];
                    }else{
                        $aGames[$gid]['ior_OUHO'] = $v4['odds'];
                    }
                }
                if ($v4['outcomeCode']=='un' and $v4['status']=='available'){ // 小的赔率
                    if($type=='re'){ // 滚球
                        $aGames[$gid]['ior_ROUHU'] = $v4['odds'];
                    }else{
                        $aGames[$gid]['ior_OUHU'] = $v4['odds'];
                    }
                }
            }
        }
        //  篮球 第2球队得分-客队 - 大 / 小 ，ctid 42，sectionName(为空""时是全场)marketCode=ou,marketType=FT_OU
        if ($market['marketCode'] == 'ou' and $market['ctid']==42 and $market['sectionName']==''){
            if($type=='re'){ // 滚球
                $aGames[$gid]['ratio_rouco']= !empty($market['ename']) ? 'O'.$market['ename'] : '';
                $aGames[$gid]['ratio_roucu']= !empty($market['ename']) ? 'U'.$market['ename'] : '';
            }else{
                $aGames[$gid]['ratio_ouco']= !empty($market['ename']) ? 'O'.$market['ename'] : '';
                $aGames[$gid]['ratio_oucu']= !empty($market['ename']) ? 'U'.$market['ename'] : '';
            }
            $outcomes=$market['outcomes'];
            foreach ($outcomes as $k4 => $v4){
                if ($v4['outcomeCode']=='ov' and $v4['status']=='available'){ // 大的赔率
                    if($type=='re'){ // 滚球
                        $aGames[$gid]['ior_ROUCO'] = $v4['odds'];
                    }else{
                        $aGames[$gid]['ior_OUCO'] = $v4['odds'];
                    }
                }
                if ($v4['outcomeCode']=='un' and $v4['status']=='available'){ // 小的赔率
                    if($type=='re'){ // 滚球
                        $aGames[$gid]['ior_ROUCU'] = $v4['odds'];
                    }else{
                        $aGames[$gid]['ior_OUCU'] = $v4['odds'];
                    }
                }
            }
        }
        // BK
        // mlq1 独赢盘-第一节
        // ml1st 独赢盘-上半场
        // ml 独赢盘
        // marketCode = ouq1 总得分:大 / 小-第一节
        //  篮球 第1球队得分-主队 - 大 / 小 ，ctid 41，sectionName=上半场,marketCode=ou1st,marketType=HT_OU
        //  篮球 第2球队得分-客队 - 大 / 小 ，ctid 42，sectionName=上半场,marketCode=ou1st,marketType=HT_OU
        // marketCode = ahq1 让球-第一节

    }
    return $aGames;
}

/**
 * 6686综合过关主盘口玩法数据转换，综合过关是欧洲盘赔率
 *
 * @param $gid
 * @param $markets
 * @param $type
 * @return mixed
 */
function masterP3MethodsTrans($markets, $type){

    if(!$type){
        $type='notRe';
    }

    foreach ($markets as $k3 => $market){
        $gid=$market['eventId'];
        $aGames[$gid]['GID']=$gid;
        if ($market['marketCode'] == 'ah' and ($market['ctid']==0 or $market['ctid']==1)) { // 全场让球
            // 全场让球数
            $RATIO_RE = $market['ename'];
            if (strlen($RATIO_RE)>1){
                $jiajian=substr($RATIO_RE , 0 , 1);
                if($type=='re'){ // 滚球
                    $aGames[$gid]['RATIO_RE']=substr($RATIO_RE,1);
                }else{
                    $aGames[$gid]['RATIO_R']=substr($RATIO_RE,1);
                }
                if ($jiajian=='-'){
                    $aGames[$gid]['STRONG']='H';
                }else{
                    $aGames[$gid]['STRONG']='C';
                }
            }
            else{
                if($type=='re'){ // 滚球
                    $aGames[$gid]['RATIO_RE']=$RATIO_RE;
                }else{
                    $aGames[$gid]['RATIO_R']=$RATIO_RE;
                }
                $aGames[$gid]['STRONG']='H';
            }
            $outcomes=$market['outcomes'];
            // 全场让球
            foreach ($outcomes as $k4 => $v4){
                if ($v4['outcomeCode']=='h'){ // 主队赔率
                    if($type=='re'){ // 滚球
                        $aGames[$gid]['IOR_REH'] = $v4['euOdds'];
                    }else{
                        $aGames[$gid]['IOR_RH'] = $v4['euOdds'];
                    }

                }
                if ($v4['outcomeCode']=='a'){ // 客队赔率
                    if($type=='re'){ // 滚球
                        $aGames[$gid]['IOR_REC'] = $v4['euOdds'];
                    }else{
                        $aGames[$gid]['IOR_RC'] = $v4['euOdds'];
                    }
                }
            }
        }
        if ($market['marketCode'] == 'ah1st' and $market['ctid']==0) { // 让球-上半场
            $RATIO_HRE = $market['ename'];
            if (strlen($RATIO_HRE)>1){
                $jiajian=substr($RATIO_HRE , 0 , 1);
                if($type=='re'){ // 滚球
                    $aGames[$gid]['RATIO_HRE']=substr($RATIO_HRE,1);
                }else{
                    $aGames[$gid]['RATIO_HR']=substr($RATIO_HRE,1);
                }

                if ($jiajian=='-'){
                    $aGames[$gid]['HSTRONG']='H';
                }else{
                    $aGames[$gid]['HSTRONG']='C';
                }
            }
            else{
                if($type=='re'){ // 滚球
                    $aGames[$gid]['RATIO_HRE']=$RATIO_HRE;
                }else{
                    $aGames[$gid]['RATIO_HR']=$RATIO_HRE;
                }
                $aGames[$gid]['HSTRONG']='H';
            }

            $outcomes=$market['outcomes'];
            foreach ($outcomes as $k4 => $v4){
                if ($v4['outcomeCode']=='h'){ // 主队赔率
                    if($type=='re'){ // 滚球
                        $aGames[$gid]['IOR_HREH'] = $v4['euOdds'];
                    }else{
                        $aGames[$gid]['IOR_HRH'] = $v4['euOdds'];
                    }
                }
                if ($v4['outcomeCode']=='a'){ // 客队赔率
                    if($type=='re'){ // 滚球
                        $aGames[$gid]['IOR_HREC'] = $v4['euOdds'];
                    }else{
                        $aGames[$gid]['IOR_HRC'] = $v4['euOdds'];
                    }

                }
            }
        }
        if ($market['marketCode'] == 'ou' and ($market['ctid']==0 or $market['ctid']==1)) { // 全场大小 ctid 0 ，足球、篮球通用
            if($type=='re'){ // 滚球
                $aGames[$gid]['RATIO_ROUO']='O'.$market['ename'];
                $aGames[$gid]['RATIO_ROUU']='U'.$market['ename'];
            }else{
                $aGames[$gid]['RATIO_OUO']='O'.$market['ename'];
                $aGames[$gid]['RATIO_OUU']='U'.$market['ename'];
            }

            $outcomes=$market['outcomes'];
            foreach ($outcomes as $k4 => $v4){
                if ($v4['outcomeCode']=='ov'){ // 大的赔率
                    if($type=='re'){ // 滚球
                        $aGames[$gid]['IOR_ROUC'] = $v4['euOdds'];
                    }else{
                        $aGames[$gid]['IOR_OUC'] = $v4['euOdds'];
                    }

                }
                if ($v4['outcomeCode']=='un'){ // 小的赔率
                    if($type=='re'){ // 滚球
                        $aGames[$gid]['IOR_ROUH'] = $v4['euOdds'];
                    }else{
                        $aGames[$gid]['IOR_OUH'] = $v4['euOdds'];
                    }

                }
            }
        }
        if ($market['marketCode'] == 'ou1st' and $market['ctid']==0) { // 半场大小
            if($type=='re'){ // 滚球
                $aGames[$gid]['RATIO_HROUO']='O'.$market['ename'];
                $aGames[$gid]['RATIO_HROUU']='U'.$market['ename'];
            }else{
                $aGames[$gid]['RATIO_HOUO']='O'.$market['ename'];
                $aGames[$gid]['RATIO_HOUU']='U'.$market['ename'];
            }

            $outcomes=$market['outcomes'];
            foreach ($outcomes as $k4 => $v4){
                if ($v4['outcomeCode']=='ov'){ // 大的赔率
                    if($type=='re'){ // 滚球
                        $aGames[$gid]['IOR_HROUC'] = $v4['euOdds'];
                    }else{
                        $aGames[$gid]['IOR_HOUC'] = $v4['euOdds'];
                    }

                }
                if ($v4['outcomeCode']=='un'){ // 小的赔率
                    if($type=='re'){ // 滚球
                        $aGames[$gid]['IOR_HROUH'] = $v4['euOdds'];
                    }else{
                        $aGames[$gid]['IOR_HOUH'] = $v4['euOdds'];
                    }

                }
            }
        }
        if ($market['marketCode'] == 'oe' and $market['ctid']==0) { // 全场单双
            $outcomes=$market['outcomes'];
            foreach ($outcomes as $k4 => $v4){
                if ($v4['outcomeCode']=='od'){  //单
                    if($type=='re'){ // 滚球
                        $aGames[$gid]['IOR_REOO'] = $v4['euOdds'];
                    }else{
                        $aGames[$gid]['IOR_EOO'] = $v4['euOdds'];
                    }

                }
                if ($v4['outcomeCode']=='ev'){  //双
                    if($type=='re'){ // 滚球
                        $aGames[$gid]['IOR_REOE'] = $v4['euOdds'];
                    }else{
                        $aGames[$gid]['IOR_EOE'] = $v4['euOdds'];
                    }

                }
            }
        }
        if ($market['marketCode'] == '1x2' and ($market['ctid']==0 or $market['ctid']==1)) { // 全场独赢
            $outcomes=$market['outcomes'];
            foreach ($outcomes as $k4 => $v4){
                if ($v4['outcomeCode']=='h'){ // 主队
                    if($type=='re'){ // 滚球
                        $aGames[$gid]['IOR_RMH'] = $v4['euOdds'];
                    }else{
                        $aGames[$gid]['IOR_MH'] = $v4['euOdds'];
                    }

                }
                if ($v4['outcomeCode']=='a'){ // 客队
                    if($type=='re'){ // 滚球
                        $aGames[$gid]['IOR_RMC'] = $v4['euOdds'];
                    }else{
                        $aGames[$gid]['IOR_MC'] = $v4['euOdds'];
                    }

                }
                if ($v4['outcomeCode']=='d'){ // 和
                    if($type=='re'){ // 滚球
                        $aGames[$gid]['IOR_RMN'] = $v4['euOdds'];
                    }else{
                        $aGames[$gid]['IOR_MN'] = $v4['euOdds'];
                    }

                }
            }
        }
        if ($market['marketCode'] == '1x21st' and $market['ctid']==0) { // 半场独赢
            $outcomes=$market['outcomes'];
            foreach ($outcomes as $k4 => $v4){
                if ($v4['outcomeCode']=='h'){ // 主队
                    if($type=='re'){ // 滚球
                        $aGames[$gid]['IOR_HRMH'] = $v4['euOdds'];
                    }else{
                        $aGames[$gid]['IOR_HMH'] = $v4['euOdds'];
                    }

                }
                if ($v4['outcomeCode']=='a'){ // 客队
                    if($type=='re'){ // 滚球
                        $aGames[$gid]['IOR_HRMC'] = $v4['euOdds'];
                    }else{
                        $aGames[$gid]['IOR_HMC'] = $v4['euOdds'];
                    }

                }
                if ($v4['outcomeCode']=='d'){ // 和
                    if($type=='re'){ // 滚球
                        $aGames[$gid]['IOR_HRMN'] = $v4['euOdds'];
                    }else{
                        $aGames[$gid]['IOR_HMN'] = $v4['euOdds'];
                    }

                }
            }
        }

        //  篮球 第1球队得分-主队 - 大 / 小 ，ctid 41，sectionName(为空""时是全场)marketCode=ou,marketType=FT_OU
        if ($market['marketCode'] == 'ou' and $market['ctid']==41 and $market['sectionName']==''){
            if($type=='re'){ // 滚球
                $aGames[$gid]['ratio_rouho']= !empty($market['ename']) ? 'O'.$market['ename'] : '';
                $aGames[$gid]['ratio_rouhu']= !empty($market['ename']) ? 'U'.$market['ename'] : '';
            }else{
                $aGames[$gid]['ratio_ouho']= !empty($market['ename']) ? 'O'.$market['ename'] : '';
                $aGames[$gid]['ratio_ouhu']= !empty($market['ename']) ? 'U'.$market['ename'] : '';
            }
            $outcomes=$market['outcomes'];
            foreach ($outcomes as $k4 => $v4){
                if ($v4['outcomeCode']=='ov'){ // 大的赔率
                    if($type=='re'){ // 滚球
                        $aGames[$gid]['ior_ROUHO'] = $v4['euOdds'];
                    }else{
                        $aGames[$gid]['ior_OUHO'] = $v4['euOdds'];
                    }
                }
                if ($v4['outcomeCode']=='un'){ // 小的赔率
                    if($type=='re'){ // 滚球
                        $aGames[$gid]['ior_ROUHU'] = $v4['euOdds'];
                    }else{
                        $aGames[$gid]['ior_OUHU'] = $v4['euOdds'];
                    }
                }
            }
        }
        //  篮球 第2球队得分-客队 - 大 / 小 ，ctid 42，sectionName(为空""时是全场)marketCode=ou,marketType=FT_OU
        if ($market['marketCode'] == 'ou' and $market['ctid']==42 and $market['sectionName']==''){
            if($type=='re'){ // 滚球
                $aGames[$gid]['ratio_rouco']= !empty($market['ename']) ? 'O'.$market['ename'] : '';
                $aGames[$gid]['ratio_roucu']= !empty($market['ename']) ? 'U'.$market['ename'] : '';
            }else{
                $aGames[$gid]['ratio_ouco']= !empty($market['ename']) ? 'O'.$market['ename'] : '';
                $aGames[$gid]['ratio_oucu']= !empty($market['ename']) ? 'U'.$market['ename'] : '';
            }
            $outcomes=$market['outcomes'];
            foreach ($outcomes as $k4 => $v4){
                if ($v4['outcomeCode']=='ov'){ // 大的赔率
                    if($type=='re'){ // 滚球
                        $aGames[$gid]['ior_ROUCO'] = $v4['euOdds'];
                    }else{
                        $aGames[$gid]['ior_OUCO'] = $v4['euOdds'];
                    }
                }
                if ($v4['outcomeCode']=='un'){ // 小的赔率
                    if($type=='re'){ // 滚球
                        $aGames[$gid]['ior_ROUCU'] = $v4['euOdds'];
                    }else{
                        $aGames[$gid]['ior_OUCU'] = $v4['euOdds'];
                    }
                }
            }
        }
        // BK
        // mlq1 独赢盘-第一节
        // ml1st 独赢盘-上半场
        // ml 独赢盘
        // marketCode = ouq1 总得分:大 / 小-第一节
        //  篮球 第1球队得分-主队 - 大 / 小 ，ctid 41，sectionName=上半场,marketCode=ou1st,marketType=HT_OU
        //  篮球 第2球队得分-客队 - 大 / 小 ，ctid 42，sectionName=上半场,marketCode=ou1st,marketType=HT_OU
        // marketCode = ahq1 让球-第一节

    }
    return $aGames;
}

// 篮球球队大小转化
function getRatioData($datainfo , $isGunQiu){
    if($isGunQiu == 'Y') {
        if (!empty($datainfo['ratio_rouo'])) {  //滚球主队全场大小
            $datainfo['ratio_rouo'] = 'O' . $datainfo['ratio_rouo'];
        }
        if (!empty($datainfo['ratio_rouu'])) {  //滚球客队全场大小
            $datainfo['ratio_rouu'] = 'U' . $datainfo['ratio_rouu'];
        }
        if (!empty($datainfo['ratio_rouho'])) { //主队
            $datainfo['ratio_rouho'] = 'O' . $datainfo['ratio_rouho'];
        }
        if (!empty($datainfo['ratio_rouhu'])) {
            $datainfo['ratio_rouhu'] = 'U' . $datainfo['ratio_rouhu'];
        }
        if (!empty($datainfo['ratio_rouco'])) { //客队
            $datainfo['ratio_rouco'] = 'O' . $datainfo['ratio_rouco'];
        }
        if (!empty($datainfo['ratio_roucu'])) {
            $datainfo['ratio_roucu'] = 'U' . $datainfo['ratio_roucu'];
        }

    }
    if (!empty($datainfo['ratio_o'])) { // ratio_o 同主盘口的全场大球数 RATIO_OUO
        $datainfo['ratio_o'] = 'O' . $datainfo['ratio_o'];
    }
    if (!empty($datainfo['ratio_u'])) { // ratio_u 同主盘口的全场小球数 RATIO_OUU
        $datainfo['ratio_u'] = 'U' . $datainfo['ratio_u'];
    }
    if (!empty($datainfo['ratio_ouho'])) {
        $datainfo['ratio_ouho'] = 'O' . $datainfo['ratio_ouho'];
    }
    if (!empty($datainfo['ratio_ouhu'])) {
        $datainfo['ratio_ouhu'] = 'U' . $datainfo['ratio_ouhu'];
    }
    if (!empty($datainfo['ratio_ouco'])) {
        $datainfo['ratio_ouco'] = 'O' . $datainfo['ratio_ouco'];
    }
    if (!empty($datainfo['ratio_oucu'])) {
        $datainfo['ratio_oucu'] = 'U' . $datainfo['ratio_oucu'];
    }
    return $datainfo;
}

?>
