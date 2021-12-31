<?php

/**
 * 数据刷新滚球 用于模板数据更新
 */

if(php_sapi_name() == "cli") {
    define("CONFIG_DIR", dirname(dirname(dirname(__FILE__))));
    define("COMMON_DIR", dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))));
    require(CONFIG_DIR."/include/config.inc.php");
    require_once(COMMON_DIR."/common/sportCenterData.php");
    require(CONFIG_DIR."/include/curl_http.php");
    require_once(CONFIG_DIR."/include/address.mem.php");
    require_once(CONFIG_DIR."/include/define_function_list.inc.php");
}else {
    require("../../include/config.inc.php");
    require_once("../../../../../common/sportCenterData.php");
    require("../../include/curl_http.php");
    require_once("../../include/address.mem.php");
    require_once("../../include/define_function_list.inc.php");
    /*判断IP是否在白名单*/
    if(CHECK_IP_SWITCH) {
        if(!checkip()) {
            exit('登录失败!!\\n未被授权访问的IP!!');
        }
    }
}

$langx="zh-cn";
$redisObj = new Ciredis();
$accoutArr = getFlushWaterAccount();
$matches = "";
$rtype = "FT_M_ROU_EO";
$result = $dataPage = $dataCount = $dataTotal = [];
$curl = new Curl_HTTP_Client();
$curl->set_user_agent("Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36");
$postdata = array(
    'p' => 'get_game_list',
    'ver' => date('Y-m-d-H').$value['Ver'],
    'langx' => 'zh-cn',
    'uid' => $value['Uid'],
    'gtype' => 'ft',
    'showtype' => 'live',
    'rtype' => 'rb',
    'ltype' => '4',
    'sorttype' => 'L',
);
$xml_data=$curl->send_post_data($value['Datasite']."/transform.php?ver=".date('Y-m-d-H').$value['Ver'], $postdata);
$aData = xmlToArray($xml_data);


foreach ($aData['ec'] as $k => $v){

    $datainfo=$v['game'];
    $STRONG=$datainfo['STRONG'];
    $RATIO_RE=$datainfo['RATIO_RE'];
    $IOR_REH=$datainfo['IOR_REH'];
    $IOR_REC=$datainfo['IOR_REC'];
    $RATIO_ROUO=$datainfo['RATIO_ROUO'];
    $RATIO_ROUU=$datainfo['RATIO_ROUU'];
    $IOR_ROUH=$datainfo['IOR_ROUH'];
    $IOR_ROUC=$datainfo['IOR_ROUC'];
    $HSTRONG=$datainfo['HSTRONG'];
    $RATIO_HRE=$datainfo['RATIO_HRE'];
    $IOR_HREH=$datainfo['IOR_HREH'];
    $IOR_HREC=$datainfo['IOR_HREC'];
    $RATIO_HROUO=$datainfo['RATIO_HROUO'];
    $RATIO_HROUU=$datainfo['RATIO_HROUU'];
    $IOR_HROUH=$datainfo['IOR_HROUH'];  //半场得分小 客
    $IOR_HROUC=$datainfo['IOR_HROUC'];  //半场得分大 主
    $SCORE_H=$datainfo['SCORE_H'];
    $SCORE_C=$datainfo['SCORE_C'];
    // MB_Card
    // TG_Card
    $REDCARD_H=$datainfo['REDCARD_H'];// MB_Red
    $REDCARD_C=$datainfo['REDCARD_C'];// TG_Red
    $IOR_RMH=$datainfo['IOR_RMH'];
    $IOR_RMC=$datainfo['IOR_RMC'];
    $IOR_RMN=$datainfo['IOR_RMN'];
    $IOR_HRMH=$datainfo['IOR_HRMH'];
    $IOR_HRMC=$datainfo['IOR_HRMC'];
    $IOR_HRMN=$datainfo['IOR_HRMN'];
    $IOR_REOO=$datainfo['IOR_REOO'];
    $IOR_REOE=$datainfo['IOR_REOE'];
    $EVENTID=$datainfo['EVENTID'];
    $HOT=$datainfo['HOT'];
    $PLAY=$datainfo['PLAY'];
    $DATETIME=$datainfo['DATETIME'];
    $GID=$datainfo['GID'];
    $MT_GTYPE=$datainfo['MT_GTYPE'];

    $sql = "update `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."`  set ShowTypeRB='$STRONG',M_LetB_RB='$RATIO_RE',MB_LetB_Rate_RB='$IOR_REH',TG_LetB_Rate_RB='$IOR_REC',MB_Dime_RB='$RATIO_ROUO',TG_Dime_RB='$RATIO_ROUU',
    MB_Dime_Rate_RB='$IOR_ROUH',TG_Dime_Rate_RB='$IOR_ROUC',ShowTypeHRB='$HSTRONG',M_LetB_RB_H='$RATIO_HRE',MB_LetB_Rate_RB_H='$IOR_HREH',TG_LetB_Rate_RB_H='$IOR_HREC',MB_Dime_RB_H='$RATIO_HROUO',TG_Dime_RB_H='$RATIO_HROUU',
    MB_Dime_Rate_RB_H='$IOR_HROUC',TG_Dime_Rate_RB_H='$IOR_HROUH',MB_Ball='$SCORE_H',TG_Ball='$SCORE_C',MB_Card='',TG_Card='',MB_Red='$REDCARD_H',TG_Red='$REDCARD_C',
    MB_Win_Rate_RB='$IOR_RMH',TG_Win_Rate_RB='$IOR_RMC',M_Flat_Rate_RB='$IOR_RMN',MB_Win_Rate_RB_H='$IOR_HRMH',TG_Win_Rate_RB_H='$IOR_HRMC',M_Flat_Rate_RB_H='$IOR_HRMN',S_Single_Rate_RB='$IOR_REOO',
    S_Double_Rate_RB='$IOR_REOE',Eventid='$EVENTID',Hot='$HOT',Play='$PLAY',M_Duration='$DATETIME',RB_Show=1,S_Show=0 where MID=$GID and `Type`='$MT_GTYPE'";


    if(mysqli_query($dbCenterMasterDbLink,$sql)){ echo "Success\n\r"; }else{ echo "Failed\n\r"; }
}




?>
