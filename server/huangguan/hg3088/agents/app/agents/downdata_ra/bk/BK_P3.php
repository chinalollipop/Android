<?php

if(php_sapi_name() == "cli") {
    define("CONFIG_DIR", dirname(dirname(dirname(__FILE__))));
    define("COMMON_DIR", dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))));
    require(CONFIG_DIR."/include/config.inc.php");
    require_once(COMMON_DIR."/common/sportCenterData.php");
    require(CONFIG_DIR."/include/curl_http.php");
    require_once(CONFIG_DIR."/include/address.mem.php");
}else{
    require("../../include/config.inc.php");
    require_once("../../../../../common/sportCenterData.php");
    require("../../include/curl_http.php");
    require_once("../../include/address.mem.php");
    /*判断IP是否在白名单*/
    if(CHECK_IP_SWITCH) {
        if(!checkip()) {
            exit('登录失败!!\\n未被授权访问的IP!!');
        }
    }
}


$refurbishTimeData = refurbishTime();
$settime=$refurbishTimeData[0]['udp_ft_pr'];

$t_page=10;
$allcount=0;
$langx="zh-cn";
$accoutArr=getFlushWaterAccount();

$rtype = "p3";
$mtype = 4;
$cou_total=0;
$curl = new Curl_HTTP_Client();
$curl->store_cookies("cookies.txt");
$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
$dataArray= array() ; // 把需要的数据重新放在数组里面
foreach($accoutArr as $key=>$value){ //在扩展表中获取账号重新刷水
    for($page_no=0;$page_no<$t_page;$page_no++) {
        $curl->set_referrer("".$value['Datasite']."/app/member/BK_browse/index.php?rtype=$rtype&uid=".$value['Uid']."&langx=$langx&mtype=$mtype");
        $html_data=$curl->fetch_url("".$value['Datasite']."/app/member/BK_browse/body_var.php?rtype=$rtype&uid=".$value['Uid']."&langx=".$langx."&mtype=$mtype&showgtype=BU&g_date=ALL&page_no=".$page_no);
        /*$a = array(
            "if(self == top)",
            "<script>",
            "\n\n"
        );
        $b = array(
            "",
            "",
            ""
        );
        unset($matches);
        unset($datainfo);
        $msg = str_replace($a,$b,$html_data);
        preg_match_all("/]=new Array\((.+?)\);/is",$msg,$matches);*/
        $matches = get_content_deal($html_data);
        $cou=sizeof($matches);
        $cou_total = $cou_total+$cou;
        for($i=0;$i<$cou;$i++){
            /*
	    $messages=$matches[$i];
            $messages=str_replace("]=new Array(","",$messages);
            $messages=str_replace("'","",$messages);
            $messages=str_replace(");","",$messages);
            $datainfo=explode(",",$messages);
	    */
            $messages=$matches[$i];
            $messages=str_replace(");",")",$messages);
            $messages=str_replace("cha(9)","",$messages);
            $datainfo=eval("return $messages;");

            // 将从正网拉取的测试数据过滤掉
            // stripos 查找字符串首次出现的位置（不区分大小写）
            $pos_m = stripos($datainfo[2], 'test'); // 查找联赛名称是否含有 test
            $pos_m_cn = stripos($datainfo[2], '测试'); // 查找联赛名称是否含有 测试
            $pos_mb = stripos($datainfo[5], 'test'); // 检查主队名称是否含有 test
            $pos_mb_cn = stripos($datainfo[5], '测试'); // 检查主队名称是否含有 测试
            $pos_tg = stripos($datainfo[6], 'test'); // 检查客队名称是否含有 test
            $pos_tg_cn = stripos($datainfo[6], '测试'); // 检查客队名称是否含有 测试
            if ($pos_m !== false || $pos_mb !== false || $pos_tg !== false ||
                $pos_m_cn !== false || $pos_mb_cn !== false || $pos_tg_cn !== false){
                continue;
            }

            $dataArray[$datainfo[0]]=(array($datainfo[7],$datainfo[8],$datainfo[9],$datainfo[10],$datainfo[11],$datainfo[12],$datainfo[13],$datainfo[14],$datainfo[15],$datainfo[16],$datainfo[17],$datainfo[18],$datainfo[19],$datainfo[26],$datainfo[27],$datainfo[30],$datainfo[31])); // 把数据放在二维数组里面

        }
    }
    if($cou_total>0) break;
}

// var_dump($dataArray);
if($cou_total>0 and count($dataArray)>0) { //可以抓到数据
    // var_dump($dataArray);
    $ids = implode(',', array_keys($dataArray));
    // echo $ids;
    $e_sql .= "END,";
    $sql = "update `".DATAHGPREFIX.SPORT_FLUSH_MATCH_TABLE."` set ";
    $m_sql .="ShowTypeP = CASE MID " ;
    $t_sql .="M_P_LetB = CASE MID ";
    $l_sql .="MB_P_LetB_Rate = CASE MID ";
    $tg_sql .="TG_P_LetB_Rate = CASE MID ";
    $mp_sql .="MB_P_Dime = CASE MID ";
    $tp_sql .="TG_P_Dime = CASE MID ";
    $mr_sql .="MB_P_Dime_Rate = CASE MID ";
    $tr_sql .="TG_P_Dime_Rate = CASE MID ";
    $sp_sql .="S_P_Single_Rate = CASE MID ";
    $spd_sql .="S_P_Double_Rate = CASE MID ";
    $mv_sql .="MB_P_Win_Rate = CASE MID ";
    $tv_sql .="TG_P_Win_Rate = CASE MID ";
    $mf_sql .="M_P_Flat_Rate = CASE MID ";
    $mh_sql .="MB_P_Dime_Rate_H = CASE MID ";
    $msh_sql .="MB_P_Dime_Rate_S_H = CASE MID ";
    $tpr_sql .="TG_P_Dime_Rate_H = CASE MID ";
    $tpsr_sql .="TG_P_Dime_Rate_S_H = CASE MID ";
    $p3_sql .="P3_Show = CASE MID ";
    foreach ($dataArray as $id => $ordinal) {
        $m_sql .= "WHEN $id THEN '$ordinal[0]' " ; // 拼接SQL语句
        $t_sql .= "WHEN $id THEN '$ordinal[1]' " ; // 拼接SQL语句
        $l_sql .= "WHEN $id THEN '$ordinal[2]' " ; // 拼接SQL语句
        $tg_sql .= "WHEN $id THEN '$ordinal[3]' " ; // 拼接SQL语句
        $mp_sql .= "WHEN $id THEN '$ordinal[4]' " ; // 拼接SQL语句
        $tp_sql .= "WHEN $id THEN '$ordinal[5]' " ; // 拼接SQL语句
        $mr_sql .= "WHEN $id THEN '$ordinal[6]' " ; // 拼接SQL语句
        $tr_sql .= "WHEN $id THEN '$ordinal[7]' " ; // 拼接SQL语句
        $sp_sql .= "WHEN $id THEN '$ordinal[8]' " ; // 拼接SQL语句
        $spd_sql .= "WHEN $id THEN '$ordinal[9]' " ; // 拼接SQL语句
        $mv_sql .= "WHEN $id THEN '$ordinal[10]' " ; // 拼接SQL语句
        $tv_sql .= "WHEN $id THEN '$ordinal[11]' " ; // 拼接SQL语句
        $mf_sql .= "WHEN $id THEN '$ordinal[12]' " ; // 拼接SQL语句
        $mh_sql .= "WHEN $id THEN '$ordinal[13]' " ; // 拼接SQL语句
        $msh_sql .= "WHEN $id THEN '$ordinal[14]' " ; // 拼接SQL语句
        $tpr_sql .= "WHEN $id THEN '$ordinal[15]' " ; // 拼接SQL语句
        $tpsr_sql .= "WHEN $id THEN '$ordinal[16]' " ; // 拼接SQL语句
        $p3_sql .= "WHEN $id THEN '1' " ; // 拼接SQL语句
    }
    $sql .= $m_sql.$e_sql.$t_sql.$e_sql.$l_sql.$e_sql.$tg_sql.$e_sql.$mp_sql.$e_sql.$tp_sql.$e_sql.$mr_sql.$e_sql.$tr_sql.$e_sql.$sp_sql.$e_sql.$spd_sql.$e_sql.$mv_sql.$e_sql.$tv_sql.$e_sql.$mf_sql.$e_sql.$mh_sql.$e_sql.$msh_sql.$e_sql.$tpr_sql.$e_sql.$tpsr_sql.$e_sql.$p3_sql ;
    // echo $sql ;
    $sql .="END WHERE MID IN ($ids)"; // 实现一次性更新数据库操作
    // echo $sql ;
    mysqli_query($dbCenterMasterDbLink,$sql) or die ("操作失败!!");

}

function get_content_deal($html_data){
    $a = array(
        "if(self == top)",
        "<script>",
        "</script>",
        "new Array()",
        "parent.GameFT=new Array();",
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

    $msg = str_replace($a,$b,$html_data);
    preg_match_all("/Array\((.+?)\);/is",$msg,$matches);
    return $matches[0];
}

?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
    <link href="/style/agents/control_down.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css">
</head>
<body>
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
                curtime=curmin+"秒后自动获取!"
            else
                curtime=cursec+"秒后自动获取!"
            timeinfo.innerText=curtime
            setTimeout("beginrefresh()",1000)
        }
    }

    window.onload=beginrefresh

</script>
<table width="100" height="70" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td width="100" height="70" align="center">
            综合过关数据接收<br>
            <span id="timeinfo"></span><br>
            <input type=button name=button value="刷新 <?php echo $cou_total?>" onClick="window.location.reload()"></td>
    </tr>
</table>
</body>
</html>
