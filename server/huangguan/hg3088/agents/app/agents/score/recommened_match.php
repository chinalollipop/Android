<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include ("../include/address.mem.php");
require_once ("../include/config.inc.php");
require_once("../../../../common/sportCenterData.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$langx=$_SESSION["langx"];
$loginname=$_SESSION['UserName'];
require ("../include/traditional.$langx.inc.php");

$gid=$_REQUEST['gid'];
$action=$_REQUEST['action'];
$mb_team_logo_url = $_REQUEST['mb_team_logo_url'];
$tg_team_logo_url = $_REQUEST['tg_team_logo_url'];

// 推荐赛事提交
$redisObj = new Ciredis();
$sRecommendedMatchs = $redisObj->getSimpleOne('recommended_match');
$aRecommendedMatchs = json_decode($sRecommendedMatchs,true);
$aRecommendedMatchsMid = array_column($aRecommendedMatchs, 'MID');


if ($action==5){
    foreach ($aRecommendedMatchs as $k => $v){
        if ($v['MID'] == $gid){
            $aRecommendedMatchs[$k]['mb_team_logo_url'] = $mb_team_logo_url;
            $aRecommendedMatchs[$k]['tg_team_logo_url'] = $tg_team_logo_url;
        }
    }
    $redisObj->setOne('recommended_match',json_encode($aRecommendedMatchs,JSON_UNESCAPED_UNICODE));
}
//print_r($aRecommendedMatchs);die;
$sRecommendedMatchsMid = implode(',',$aRecommendedMatchsMid);
$sql = "SELECT `MID`,`Type`,`MB_Team`,`TG_Team`,`M_Date`,`M_Time`,`M_Start`,`M_Duration`,`M_League`,`MB_Dime`,`TG_Dime`,`MB_Win_Rate_RB`,`TG_Win_Rate_RB`,`M_Flat_Rate_RB`,`MB_Dime_RB`,`MB_Dime_Rate_RB`,`TG_Dime_RB`,`TG_Dime_Rate_RB`,`S_Single_Rate_RB`,`S_Double_Rate_RB`,
`MB_Win_Rate`,`TG_Win_Rate`,`M_Flat_Rate`,`S_Single_Rate`,`S_Double_Rate`,`M_LetB`,`MB_LetB_Rate`,`TG_LetB_Rate`,`MB_Dime`,`TG_Dime`,`MB_Dime_Rate`,`TG_Dime_Rate`
FROM `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` WHERE MID in ($sRecommendedMatchsMid)";
$result = mysqli_query($dbCenterSlaveDbLink,$sql);
$now = date('Y-m-d H:i:s');
while ($row = mysqli_fetch_assoc($result)){

    $key = array_search($row['MID'], array_column($aRecommendedMatchs, 'MID'));
    $row['mb_team_logo_url'] = $aRecommendedMatchs[$key]['mb_team_logo_url'];
    $row['tg_team_logo_url'] = $aRecommendedMatchs[$key]['tg_team_logo_url'];
    if ($row['M_Start']>$now){
        $row['MB_Dime']="大" . str_replace('O', '', $row['MB_Dime_RB']);
        $row['TG_Dime']="小" . str_replace('U', '', $row['TG_Dime_RB']);
        $row['MB_Dime_Rate']=$row['MB_Dime_Rate_RB'];
        $row['TG_Dime_Rate']=$row['TG_Dime_Rate_RB'];
        $row['MB_Win_Rate']=$row['MB_Win_Rate_RB'];
        $row['TG_Win_Rate']=$row['TG_Win_Rate_RB'];
        $row['M_Flat_Rate']=$row['M_Flat_Rate_RB'];
        $row['S_Single_Rate']=$row['S_Single_Rate_RB'];
        $row['S_Double_Rate']=$row['S_Double_Rate_RB'];
    }
    else{
        $row['MB_Dime']="大" . str_replace('O', '', $row['MB_Dime']);
        $row['TG_Dime']="小" . str_replace('U', '', $row['TG_Dime']);
    }
    $data[] = $row;
}


?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <style>
        .m_tab .score_td {width:100px;}
    </style>
    <script charset="utf-8" src="../../../js/agents/common.js?v=<?php echo AUTOVER; ?>" ></script>
    <script charset="utf-8" src="../../../js/agents/jquery.js" ></script>
    <script language="javascript">

        function recommend_match_logo(gid) {
            var mb_team_logo_url = $("#MB_Team_Logo_"+gid).val();
            var tg_team_logo_url = $("#TG_Team_Logo_"+gid).val();
            var url = 'recommened_match.php';
            var form = $('<form></form>');
            form.attr('action',url);
            form.attr('method', 'post');
            form.attr('target', '_self');
            form.append("<input type='hidden' name='action' value='5'>");
            form.append("<input type='hidden' name='gid' value='"+gid+"'>");
            form.append("<input type='hidden' name='mb_team_logo_url' value='"+mb_team_logo_url+"'>");
            form.append("<input type='hidden' name='tg_team_logo_url' value='"+tg_team_logo_url+"'>");
            $(document.body).append(form);
            form.submit();
        }

    </script>
</head>
<body>
<div class="main-ui width_1300">
<table id="glist_table" class="m_tab" >
    <tr class="m_title">
        <td><?php echo $row['M_Date']?>--赛事</td>
        <td>时间</td>
        <td>主场队伍</td>
        <td>全场比分</td>
        <td>客场队伍</td>
        <td>半场比分</td>
        <td>操作</td>
    </tr>
    <?php
    foreach ($data as $k => $row){
    ?>
    <tr>
        <td><?php echo $row["M_League"]; ?></td>
        <td><?php echo $row["M_Time"]; ?></td>
        <td>
            <div align="right">
                <?php echo str_replace('[主]','',$row["MB_Team"])?>
                <br>
                主队LOGO<input style="width: 350px;" type="text" value="<?php echo $row['mb_team_logo_url']?>" id="MB_Team_Logo_<?php echo $row['MID'] ?>"/>
            </div>
        </td>
        <td><?php echo $row["MB_Inball"].'-'.$row["TG_Inball"];?></td>
        <td>
            <div align="left" >
                <?php echo $row["TG_Team"]?>
                <br>
                <input style="width: 350px;" type="text" value="<?php echo $row['tg_team_logo_url']?>" id="TG_Team_Logo_<?php echo $row['MID'] ?>"/>客队LOGO
            </div>
        </td>
        <td><?php echo $row["MB_Inball_HR"].'-'.$row["TG_Inball_HR"];?></td>
        <td><input type="button" onclick="recommend_match_logo(<?php echo $row['MID']?>)" value="保存LOGO"></td>
    </tr>
<?php } ?>
</table>
</div>
</body>
</html>
