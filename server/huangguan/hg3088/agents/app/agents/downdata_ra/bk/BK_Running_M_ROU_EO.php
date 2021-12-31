<?php
/**
* 数据刷新滚球 用于模板生成
*/
if(php_sapi_name() == "cli"){
    define("CONFIG_DIR",dirname(dirname(dirname(__FILE__))));
    define("COMMON_DIR", dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))));
    require(CONFIG_DIR."/include/config.inc.php");
    require_once(COMMON_DIR."/common/sportCenterData.php");
    require(CONFIG_DIR."/include/curl_http.php");
    require_once(CONFIG_DIR."/include/address.mem.php");
    require_once(CONFIG_DIR."/include/define_function_list.inc.php");
}else{
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

$accoutArr = getFlushWaterAccount();
$matches = "";
$result=[];
$curl = new Curl_HTTP_Client();
$curl->store_cookies("/tmp/cookies.txt");
$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
foreach($accoutArr as $key=>$value){//在扩展表中获取账号重新刷水
    $curl->set_referrer("".$value['Datasite']."/app/member/BK_browse/index.php?rtype=re_all&uid=".$value['Uid']."&langx=zh-cn&mtype=4");
    $html_data=$curl->fetch_url("".$value['Datasite']."/app/member/BK_browse/body_var.php?rtype=re_all&uid=".$value['Uid']."&langx=zh-cn&mtype=4");
    $a = array(
        "if(self == top)",
        "<script>",
        "</script>",
        "new Array()",
        "parent.GameBK=new Array();",
        "\n\n",
        "_.",
        "g([",
        "])"
    );
    $b = array(
        "",
        "",
        "",
        "",
        "",
        "",
        "parent.",
        "Array(",
        ")"
    );
    unset($matches);
    unset($datainfo);
    $msg = str_replace($a,$b,$html_data);
    preg_match_all("/Array\((.+?)\);/is",$msg,$matches);
    if(is_array($matches[0]) && $matches[0]!=''){
        $cou=sizeof($matches[0]);
    }else{
        $cou=0;
    }
    if($cou>0){
        $result = $matches[0];
        break;
    }
}

for($i=0;$i<$cou;$i++){
    $messages=$result[$i];
    $messages=str_replace(");",")",$messages);
    $messages=str_replace("cha(9)","",$messages);
    $datainfo=eval("return $messages;");
    $M_duration = $datainfo[52].'-'.$datainfo[56]; // 比赛进度 【 Q2-80】 【 第二节-第80分钟】
    $sql = "update `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."`  set MB_Win_Rate='$datainfo[29]', TG_Win_Rate='$datainfo[30]',ShowTypeRB='$datainfo[7]',M_LetB_RB='$datainfo[8]',MB_LetB_Rate_RB='$datainfo[9]',TG_LetB_Rate_RB='$datainfo[10]',MB_Dime_RB='$datainfo[11]',TG_Dime_RB='$datainfo[12]',MB_Dime_Rate_RB='$datainfo[14]',TG_Dime_Rate_RB='$datainfo[13]',ShowTypeHRB='$datainfo[21]',M_LetB_RB_H='$datainfo[22]',MB_LetB_Rate_RB_H='$datainfo[23]',TG_LetB_Rate_RB_H='$datainfo[24]',MB_Dime_RB_H='$datainfo[35]',MB_Dime_RB_S_H='$datainfo[36]',TG_Dime_RB_H='$datainfo[39]',TG_Dime_RB_S_H='$datainfo[40]',MB_Dime_Rate_RB_H='$datainfo[37]',MB_Dime_Rate_RB_S_H='$datainfo[38]',TG_Dime_Rate_RB_H='$datainfo[41]',TG_Dime_Rate_RB_S_H='$datainfo[42]',Eventid='$datainfo[39]',Hot='$datainfo[40]',Play='$datainfo[41]',MB_Ball='$datainfo[53]',TG_Ball='$datainfo[54]',M_Duration='$M_duration',RB_Show=1,S_Show=0 where MID=$datainfo[0] and `Type`='BK'";
    if(mysqli_query($dbCenterMasterDbLink,$sql)){ echo "Success\n\r"; }else{ echo "Failed\n\r"; }
}


?>

