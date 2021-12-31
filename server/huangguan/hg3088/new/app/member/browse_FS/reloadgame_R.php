<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
require ("../include/config.inc.php");
require_once("../../../../common/sportCenterData.php");
require ("../include/curl_http.php");
require ("../include/address.mem.php");
$uid=$_REQUEST['uid'];
$langx=$_SESSION['langx'];
$mtype=$_REQUEST['mtype'];
$rtype=$_REQUEST['rtype'];
$league_id=trim($_REQUEST['league_id']);
require ("../include/traditional.$langx.inc.php");
if ($rtype=='fs'){
    $type="and Gtype!='FI'";
}else if ($rtype=='fi'){
    $type="and Gtype='FI'";
}
$fstype=$_REQUEST['FStype'];

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
	echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
	exit;
}


$open=$_SESSION['OpenType'];
$memname=$_SESSION['UserName'];
$pay_type=$_SESSION['Pay_Type'];

$m_date=date('Y-m-d');
$time=date('H:i:s');
$K=0;
?>

<?php
$mysql = "select datasite,uid,uid_tw,uid_en from ".DBPREFIX."web_system_data where ID=1";
$result = mysqli_query($dbLink,$mysql);
$row = mysqli_fetch_assoc($result);
$site=$row['datasite'];
switch($langx)	{
    case "zh-cn":
        $suid=$row['uid_tw'];
        break;
    case "zh-cn":
        $suid=$row['uid'];
        break;
    case "en-us":
        $suid=$row['uid_en'];
        break;
    case "th-tis":
        $suid=$row['uid_en'];
        break;
}

//获取刷水账号
$reBallCountCur = 0;
$accoutArr = array();
$accoutArr=getFlushWaterAccount();//数组随机排序
$allcount = 0;
$curl = new Curl_HTTP_Client();
$curl->store_cookies("cookies.txt");
$curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
foreach($accoutArr as $key=>$value) {//在扩展表中获取账号重新刷水
    $curl->set_referrer("" . $value['Datasite'] . "/app/member/browse_FS/loadgame_R.php?rtype=fs&uid=".$value['Uid']."&langx=$langx&mtype=3");
    $html_data = $curl->fetch_url("" . $value['Datasite'] . "/app/member/browse_FS/reloadgame_R.php?uid=".$value['Uid']."&langx=$langx&rtype=fs&league_id=$league_id&FStype=$fstype");
//print_r("" . $value['Datasite'] . "/app/member/browse_FS/reloadgame_R.php?uid=".$value['Uid']."&langx=$langx&rtype=fs&league_id=$league_id&FStype=$fstype");
    // echo $html_data ;
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
    preg_match_all("/parent.areasarray=(.+?);/is",$html_data,$areasarray);
    preg_match_all("/parent.itemsarray=(.+?);/is",$html_data,$itemsarray);
    preg_match_all("/parent.leaguearray=(.+?);/is",$html_data,$leaguearray);
    // var_dump($leaguearray);die;
    //	var_dump($areasarray[0]) ;
    //	var_dump($areasarray[0] ==null) ;
    //	exit;
    ?>

    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
    <script>
        parent.sessions='2';
        parent.nowtime='<?php echo $time?>';
        parent.records=40;
        parent.gamount=<?php echo $cou_num?>;
        parent.areasarray=<?php echo ($areasarray[0] == null)?'0':$areasarray[1][0] ?>;
        parent.itemsarray=<?php echo ($itemsarray[0] == null)?'0':$itemsarray[1][0] ?>;
        parent.leaguearray=<?php echo ($leaguearray[0] == null)?'0':$leaguearray[1][0] ?>;

        var ordersR=new Array();
        var gidx=new Array();
        var GameFT=new Array();
        <?php
        for($i=0;$i<$cou_num;$i++){
            $messages=$matches[0][$i];
            $messages=str_replace("new Array(","",$messages);
            $messages=str_replace(");","",$messages);
            echo "GameFT[$i] = new Array(".$messages.");"."\n";
            $datainfo=explode(",",$messages);
            echo "gidx[".$datainfo[0]."]=$i;"."\n";

            $allcount++;
        }
        ?>
        parent.GameFT=GameFT;
        parent.gidx=gidx;
        parent.ordersR=ordersR;
        parent.showgame_table();
    </script>

    <?php
    if ($allcount>0){
        break;
    }
}
?>

