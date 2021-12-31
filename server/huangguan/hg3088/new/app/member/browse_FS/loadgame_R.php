<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "../include/address.mem.php";
require ("../include/config.inc.php");
require_once("../../../../common/sportCenterData.php");
require ("../include/define_function_list.inc.php");
require ("../include/curl_http.php");

// 判断滚球是否维护-单页面维护功能
checkMaintain($_REQUEST['showtype']);

$uid=$_REQUEST['uid'];
$langx=$_SESSION['langx'];
$fstype=$_REQUEST['FStype'];
$rtype=isset($_REQUEST['rtype'])?$_REQUEST['rtype']:'';
// $league_id=trim($_REQUEST['league_id']);
$league_id=$_REQUEST['myleaArr'];
$league_id=='ALL'?$league_id='':$league_id='';
$mtype=$_REQUEST['mtype'];
require ("../include/traditional.$langx.inc.php");
if ($rtype==""){
    $rtype="FS";
}
switch ($fstype){
    case 'FT':
        $gametitle='足球';
        break;
    case 'BK':
        $gametitle='篮球';
        break;
    case 'TN':
        $gametitle='网球';
        break;
    case 'VB':
        $gametitle='排球';
        break;
    case 'BS':
        $gametitle='棒球';
        break;
    case 'OP':
        $gametitle='其他';
        break;
}
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
    exit;
}

$open=$_SESSION['OpenType'];
$username=$_SESSION['UserName'];
$pay_type=$_SESSION['Pay_Type'];

$m_date=date('Y-m-d');
$time=date('H:i:s');
$K=0;

$mysql = "select datasite,uid,uid_tw,uid_en from ".DBPREFIX."web_system_data where ID=1";
$result = mysqli_query($dbLink,$mysql);
$row = mysqli_fetch_assoc($result);
$site=$row['datasite'];
switch($langx){
    case "zh-cn":
        $suid=$row['uid_tw'];
        break;
    case "zh-cn":
        $suid=$row['uid'];
        break;
    case "en-us":
        $suid=$row['uid_en'];
        break;
}

function getShampionMatches(){
    global $dbMasterLink,$fstype,$league_id;
    $FT_M_ROU_EO_Time=TODAY_REDIS_REFLUSH_TIME;
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
    global $langx,$fstype;
    $result='';
    //获取刷水账号
    $accoutArr = getFlushWaterAccount();
    $curl = new Curl_HTTP_Client();
    $curl->store_cookies("cookies.txt");
    $curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
    foreach($accoutArr as $key=>$value){//在扩展表中获取账号重新刷水
        $curl->set_referrer("" . $value['Datasite'] . "/app/member/browse_FS/loadgame_R.php?rtype=fs&uid=".$value['Uid']."&langx=$langx&mtype=3");
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
        }
    }
    return $result;
}

$allcount = 0;
$reBallCountCur = 0;
$result = getShampionMatches();

$matchesTem = isset($result['data'])?$result['data']:'';
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
$cou_num=count($matches);
if(strlen($league_id)==0){
    $leagueIdNum='ALL';
}else{
    $leagueIdNum=count(explode(',',$league_id));
}


?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
    <meta http-equiv='Page-Exit' content='revealTrans(Duration=0,Transition=5)'>
    <link rel="stylesheet" href="../../../style/member/common.css?v=<?php echo AUTOVER; ?>" type="text/css"><link rel="stylesheet" href="../../../style/member/mem_body_ft.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8 ">
    <script class="language_choose" type="text/javascript" src="../../../js/zh-cn.js?v=<?php echo AUTOVER; ?>"></script>
    <script>

        LeaguesName='';
        parent.legnum='<?php echo $leagueIdNum; ?>';
        parent.nowtime='<?php echo $time?>';
        parent.ch_leaguearray='<?php if($leagueIdNum!='All'){echo $league_id;}else{ echo 'ALL';}?>';
        parent.areasarray=<?php echo ($areasarray[0] == null)?'0':$areasarray[1][0] ?>;
        parent.itemsarray=<?php echo ($itemsarray[0] == null)?'0':$itemsarray[1][0] ?>;
        parent.leaguearray=<?php echo ($leaguearray[0] == null)?'0':$leaguearray[1][0] ?>;

        parent.FStype='<?php echo $fstype ?>';

        parent.uid='<?php echo $uid ?> ';
        parent.rtype='<?php echo $rtype ?>';
        parent.langx='<?php echo $langx ?>';
        parent.base_url='uid=<?php echo $uid ?>&langx=<?php echo $langx ?>';
        parent.sel_gtype='<?php echo $rtype ?><?php echo $fstype;?>';
        parent.retime=180;
        parent.retime_flag = 'N';	//自動更新旗標
        parent.defaultOpen = true;         // 預設盤面顯示全縮 或是 全打開
        parent.NoshowLeg=new Array();
        parent.myLeg=new Array();
        parent.LeagueAry=new Array();

        // function set_reloadtime(){
        //     document.getElementById("MNFS").className="FS"+FStype;
        //     document.getElementById("pg_txt").innerHTML ="&nbsp;";
        //     showtime=parent.retime;
        //     count_down();
        //     parent.sel_league=lidURL(eval("top.FS"+FStype+"_lid['FS"+FStype+"_lid_ary']"));
        //     if(parent.sel_league=="ALL")parent.sel_league="";
        // }
        // function lidURL(str){
        //     var showstr="";
        //     var strray=str.split('-');
        //     for(var i =0;i<strray.length;i++){
        //         if(strray[i]=="")continue;
        //         if(showstr!=""){
        //             showstr+="-";
        //         }
        //         showstr+=strray[i];
        //     }
        //     return showstr;
        // }

        /*
        鍵盤
        */
        document.onkeypress=checkfunc;
        function checkfunc(e) {
            switch(event.keyCode){
            }
        }

        function CheckKey(){
            if(event.keyCode == 13) return true;
            if (event.keyCode!=46){
                if((event.keyCode < 48 || event.keyCode > 57))
                {
                    alert(top.str_only_keyin_num);  /*僅能接受數字!!*/
                    return false;
                }
            }
        }
        function countdown(){
            if (keepsec!=""){
                if (Showtypes=="P1"||Showtypes=="P2"||Showtypes=="P3"){
                    reload_time.innerHTML=keepsec+"&nbsp"+top.str_sec+top.str_auto_upgrade+"&nbsp"+"--"+par_min+"~"+par_max;
                }else{
                    reload_time.innerHTML=keepsec+"&nbsp"+top.str_sec+top.str_auto_upgrade+"&nbsp";
                }
                keepsec--;
            }
        }

        var keepsec="";
        cc=setInterval("countdown()",1000);

    </script>

</head>
<body id="MNFS" class="load_body_var" onLoad="onLoad()"> <!-- onLoad="set_reloadtime();" -->

<!-- 球赛展示区顶部 开始-->
<div class="bet_head">
    <!--左侧按钮-->
    <div class="bet_left">

        <span id="sel_league" onclick="chg_league();" class="bet_league_btn">
            <tt class="bet_normal_text">
               <?php echo $U_19 ?> (<tt id="str_num" class="bet_yellow"></tt>)
            </tt>
        </span>

    </div>

    <!--右侧按钮-->
    <div class="bet_right">

        <span class="bet_time_btn" onclick="javascript:reload_var()">
            <tt id="refreshTime" class="bet_time_text"><?php echo $U_14 ?></tt>
        </span>

    </div>
</div>
<!-- 球赛展示区顶部 结束-->
<table class="bet_game_top">
    <tbody>
    <tr class="title_fixed">
        <th style="width: 60px"><?php echo $gametitle?>冠军</th>
        <th colspan="7"></th>

    </tr>
    </tbody>
</table>
<table border="0" cellpadding="0" cellspacing="0" id="box" class="bet_game_table">
    <tr>
        <td class="mem">

            <!--     資料顯示的layer     -->
            <div id="showgames" class="game-div">

                <?php
                if(isset($matches)&&is_array($matches)&&count($matches)>0){
                    foreach($matches as $key=>$match){
                        $match=str_replace('new Array(', '', $match);
                        $match=str_replace(');', '', $match);
                        $match=str_replace('\'', '', $match);
                        $match=explode(',',$match);
                        $matchNum=count($match);
                        ?>
                        <table border="0" cellpadding="0" cellspacing="0" class="fs_leg b_hline">
                            <tbody>
                            <tr>
                                <td class="legicon" onclick="showLeg('<?php echo $match[2];?>');">
									<span id="<?php echo $match[0].'_'.$match[2];?>" class="showleg">
									<span id="LegOpen"></span>
                                        <!--展開聯盟-符號-->
                                        <!--span id="LegOpen"></span-->
                                        <!--收合聯盟-符號-->
                                        <!--div id="LegClose"></div-->
									</span>
                                </td>
                                <td onclick="showLeg('<?php echo $match[2];?>');" class="leg_bar"><?php echo $match[2];?></td>
                                <td nowrap="" align="right"><?php echo $match[1];?></td>
                            </tr>
                            </tbody>
                        </table>
                        <table cellpadding=0 cellspacing=0 class='table-title'>
                            <tbody>
                           <!-- <tr id='TR_1_<?php /*echo $match[0];*/?>'>-->
                            <tr id='TR_<?php echo $match[0];?>'>
                                <td nowrap="" align='left'><?php echo $match[3];?></td>
                            </tr>
                            </tbody>
                        </table>
                        <?php
                        for($i=6;$i<count($match);$i=$i+4){
                            if((($i-6)/4)%2==0){
                                ?>
                                <?php if($match[$i+3]>0){?>
                                    <table class="gj_table_content TR_<?php echo $match[0];?>" cellpadding="0" cellspacing="0" border="0">
                                        <tbody>
                                        <tr>
                                            <td  class="gj_team_name"><?php echo $match[$i+2];?></td>
                                            <td width="120" class="gj_r_bold" ><font class="b_cen" title="<?php echo $match[$i+2];?>"
                                                                                                style="cursor:pointer"
                                                                                                onclick="parent.mem_order.betOrder('FT','NFS','gametype=<?php echo $match[count($match)-1];?>&gid=<?php echo $match[0];?>&uid=<?php echo $uid;?>&rtype=<?php echo $match[$i+1];?>&wtype=FS&langx=<?php echo $langx;?>');"><?php echo change_rate($open,$match[$i+3]);?></font>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                <?php }?>
                            <?php }else{
                                if(strlen($match[$i+2])>0){
                                    ?>
                                    <?php if($match[$i+3]>0){?>
                                        <table class="gj_table_content TR_<?php echo $match[0];?>" cellpadding="0" cellspacing="0" border="0">
                                            <tbody>
                                            <tr>
                                                <td class="gj_team_name"><?php echo $match[$i+2];?></td>

                                                <td width="120" class="gj_r_bold" ><font class="b_cen" title="<?php echo $match[$i+2];?>"
                                                                                                    style="cursor:pointer"
                                                                                                    onclick="parent.mem_order.betOrder('FT','NFS','gametype=<?php echo $match[count($match)-1];?>&gid=<?php echo $match[0];?>&uid=<?php echo $uid;?>&rtype=<?php echo $match[$i+1];?>&wtype=FS&langx=<?php echo $langx;?>');"><?php echo change_rate($open,$match[$i+3]);?></font>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    <?php }?>
                                <?php 	}
                            }
                        }?>

                        <?php

                        $leaguetitle[$match[2]][] = $match[0] ; // 联赛
                        ?>

                    <?php } ?>
                <?php }else{
                    echo "<table id=\"game_table\" cellspacing=\"0\" cellpadding=\"0\" class=\"\">
                                <tbody>
                                <tr><td colspan=\"20\" class=\"no_game\">您选择的项目暂时没有赛事。请修改您的选项或迟些再返回。</td></tr>                                </tbody>
                            </table>";
                }

                $LeagueAry[] = $match[2] ; // 联赛
                $LeagueAry = array_unique($LeagueAry);
                //$leaguetitle[$match['league']][] = $match['dategh'] ; // 联赛

                ?>
                <div style="margin-bottom:60px"></div>
            </div>
        </td>
    </tr>

</table>

<script language="JavaScript">
    <?php
    foreach ($leaguetitle as $key=>$leatitle){
        if($leatitle){
            // echo $key.'--' ;var_dump($leatitle);
            // var_dump($leatitle);
            $leastr = implode(',',$leatitle) ;
            echo "parent.myLeg['$key']= new Array('$leastr') ;\n"; // 联赛
            $k ++ ;
        }

    }

    ?>
</script>

<!--选择联赛-->
<div id="legView" style="display:none;" class="legView">
    <div class="leg_head" onMousedown="initializedragie('legView')"></div>

    <div><iframe id="legFrame" frameborder="no" border="0" allowtransparency="true"></iframe></div>


    <div class="leg_foot"></div>
</div>

<!--<iframe id=reloadPHP name=reloadPHP width=0 href="reloadgame_R.php?mid=6686359&uid=d913988335c653619da7ra7&langx=zh-cn&choice=ALL&LegGame=ALL&pages=1&records=40&FStype=FT&area_id=&league_id=&rtype=FS" height=0 ></iframe>-->

<!--<div id="controlscroll" ><table border="0" cellspacing="0" cellpadding="0" class="loadBox"><tr><td> </td></tr></table></div>-->

<!-- 分页 -->
<!--<div id="show_page_txt" class="bet_page_bot_rt">

</div>-->

<script type="text/javascript" src="../../../js/jquery.js"></script>
<script type="text/javascript" src="../../../js/common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript" src="../../../js/src/common_body_var.js?v=<?php echo AUTOVER; ?>"></script>
<script language="JavaScript" src="../../../js/betcommon.js?v=<?php echo AUTOVER; ?>"></script>
<script>
    setBodyScroll();
</script>
</body>
</html>