<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include "../include/address.mem.php";
require ("../include/config.inc.php");

// 判断早盘是否维护-单页面维护功能
checkMaintain($_REQUEST['showtype']);

$today_date=date("Y-m-d"); // 今日
$uid=$_SESSION['Oid'];
$langx=$_SESSION['langx'];
$open=$_SESSION['OpenType'];
$rtype=ltrim(strtolower($_REQUEST['rtype']));
$g_date=$_REQUEST['g_date'];
$league_id=$_REQUEST['league_id'];
$showtype=$_REQUEST['showtype'];
require ("../include/traditional.$langx.inc.php");

if ($rtype==""){
	$rtype="r";
}

//if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
//	echo "<script>top.location.href='/'</script>";
//	exit;
//}

$redisObj = new Ciredis();

if($g_date==''){ // 切换日期，默认今日
    if($showtype=='future'){
        $date='ALL';
    }else{
        $date=$today_date;
    }
}else{
   $date='$g_date';
}


?>

<html>
<head>
<title>下注分割畫面</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <script type="text/javascript" class="language_choose" src="../../../js/zh-cn.js?v=<?php echo AUTOVER; ?>"></script><script type="text/javascript" src="../../../js/betcommon.js?v=<?php echo AUTOVER; ?>"></script>
    <SCRIPT LANGUAGE="JAVASCRIPT">
        var langx='<?php echo $langx ?>';
        var liveid = '';
        var keepleg="";
        var legnum=0;
        var NoshowLeg=new Array();
        var myLeg=new Array(); // 联赛总数组
        var everymyLeg =new Array();
        var LeagueAry=new Array();
        var keepscroll=0;
        var step=1;

        var username='';
        var maxcredit='';
        var code='';
        var pg=0; // 当前第几页
        var sel_league='';	//選擇顯示聯盟
        var uid='<?php echo $uid ?>';		//user's session ID
        var loading = 'Y';	//是否正在讀取瀏覽頁面
        var loading_var = 'Y';	//是否正在讀取變數值頁面
        var showtype = '<?php echo $showtype?>' ; //目前顯示頁面
        var ltype = 1;		//目前顯示line
        var retime_flag = 'N';	//自動更新旗標
        var retime = 0;		//自動更新時間
        var str_even = '和局';
        var str_renew = '秒自動更新';
        var str_submit = '確認';
        var str_reset = '重設';

        var now_page = 1;	//目前顯示頁面
        var pages = 1;		//總頁數
        var msg = '';		//即時資訊
        var gamount = 0;	//目前顯示一般賽程數

        var sel_gtype='FU'; // 早盘 FU


    </SCRIPT>
<script> 

//====== 加入現場轉播功能 2009-04-09
// 開啟轉播
function OpenLive(eventid, gtype){
    if (liveid == undefined) {
        parent.self.location = "";
        return;
    }
    window.open("../live/live.php?langx="+langx+"&uid="+uid+"&liveid="+liveid+"&eventid="+eventid+"&gtype="+gtype,"Live","width=780,height=585,top=0,left=0,status=no,toolbar=no,scrollbars=no,resizable=no,personalbar=no");
}

function VideoFun(eventid, hot, play, gtype) {
    var tmpStr = "";
    if (play == "Y") {
        tmpStr+= "<img lowsrc=\"/images/member/video_1.gif\" onClick=\"parent.OpenLive('"+eventid+"','"+gtype+"')\" style=\"cursor:hand\">";
    } else {
        tmpStr+= "<img lowsrc=\"/images/member/video_2.gif\">";
    }
    return tmpStr;
}


</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8 ">

<?php

switch($rtype){
case 're':
?>
<script>
function parseMyLove(GameData){

    var tmpStr="";
    //====== 加入現場轉播功能 2009-04-09, VideoFun 放在 flash_ior_mem.js
    tmpStr = "<table width='100%'   border='0' cellpadding='0' cellspacing='0'><tr><td align='left'>"+str_even+"</td>";
    tmpStr+= "<td class='hot_td' >";
//  tmpStr+= "<table><tr align='right'><td>";
    tmpStr+=MM_ShowLoveI(GameData.gnum_h,GameData.datetime,GameData.league,GameData.team_h,GameData.team_c);
//  tmpStr+= "</td></tr></table>";
    tmpStr+= "</td>";
    tmpStr+= "</tr></table>";

    return  tmpStr;
}
</script>
<?php
break;
case 'r': // 默认全部(独赢，让球，大小，单双)
?>
<script>

function show_more(gid,evt,all){

    if(!uid.replace(/\s+/g,"")){ // 去除空字符串，未登录
        parent.body.body_var.bodyVarAlert('请先登录');
        return;
    }

    evt = evt ? evt : (window.event ? window.event : null);
    var mY = evt.pageY ? evt.pageY : evt.y;
    retime_flag='N' ; // 自动刷新标志
    if(all){
        body_var.document.getElementById('all_more_window').style.position='fixed';
        body_var.document.getElementById('all_more_window').style.top=0;
        body_var.document.getElementById('all_more_window').style.left=body_var.document.body.scrollLeft+10;
        body_var.document.getElementById('all_more_window').style.width='100%';
        body_var.document.getElementById('all_more_window').style.zIndex='10';
        var  url="body_var_r_allbets.php?gid="+gid+"&uid="+uid+"&ltype="+ltype+"&langx="+langx;
        body_var.all_showdata.location.href = url;
    }else{ // 原来的
        body_var.document.getElementById('more_window').style.position='absolute';
        body_var.document.getElementById('more_window').style.top=mY+30;
        body_var.document.getElementById('more_window').style.left=body_var.document.body.scrollLeft+10;
        var  url="body_var_r_more.php?gid="+gid+"&uid="+uid+"&ltype="+ltype+"&langx="+langx;
        body_var.showdata.location.href = url;
    }

}


function parseMyLove(GameData){

    var tmpStr="";
    //====== 加入現場轉播功能 2009-04-09, VideoFun 放在 flash_ior_mem.js
    tmpStr = "<table width='99%'   border='0' cellpadding='0' cellspacing='0'><tr><td align='left'>"+str_even+"</td>";              
    tmpStr+= "<td class='hot_td' >";
//  tmpStr+= "<table><tr align='right'><td>";
    tmpStr+=MM_ShowLoveI(GameData.gnum_h,GameData.datetime,GameData.league,GameData.team_h,GameData.team_c);
//  tmpStr+= "</td></tr></table>";
    tmpStr+= "</td>";
    tmpStr+= "</tr></table>";

    return  tmpStr;
}


</script>
<?php
break;
case 'pd': // 波胆全场
?>
<script>


</script>
<?php
break;
case 'p3': // 综合过关
?>
<script>

//------------------------新過關變色直接新增功能-------------------max 2010/10
var orderArray=new Array();
var ordergid=new Array();
function resort(ary){
    var tempary=new Array();
    for(var i=0;i<ary.length;i++){
        if (ary[i]!=0){
            tempary[tempary.length]=ary[i];
            }
        }
    return tempary;
    }


function orderRemoveALL(){
    //alert("orderRemoveALL===>"+ordergid.length);
    for(var i=0;i < ordergid.length;i++){
        orderRemoveGidBgcolor(ordergid[i]);     
    }
   orderArray=new Array();
   ordergid=new Array();
}

function orderShowSelALL(){
    for(var i=0;i < ordergid.length;i++){
        var obj=orderArray["G"+ordergid[i]];
        try{
            var classary=(body_browse.document.getElementById(obj.gid+"_"+obj.wtype).className).split("_");
            body_browse.document.getElementById(obj.gid+"_"+obj.wtype).className="pr_"+classary[1];
        }catch(E){} 
    }
}


function orderRemoveGid(removeGid){
        for(var i=0;i < ordergid.length;i++){
        //alert("gid==>"+ordergid[i]);
            var obj=orderArray["G"+ordergid[i]];
            if (obj.gid==removeGid || obj.hgid==removeGid){
                orderRemoveGidBgcolor(ordergid[i]);
                orderArray["G"+ordergid[i]]="undefined";
                ordergid[i]=0;
            
                } 
        }
    
        ordergid=resort(ordergid);

    
    }
function orderRemoveGidBgcolor(gidm){
        var tmpobj=orderArray["G"+gidm];
        try{
            var classary=( body_browse.document.getElementById(tmpobj.gid+"_"+tmpobj.wtype).className).split("_");
            body_browse.document.getElementById(tmpobj.gid+"_"+tmpobj.wtype).className="b_"+classary[1];
        }catch(E){}
    }


function orderParlay(gidm,gid,hgid,wtype,par_minlimit,par_maxlimit){
    if (""+orderArray["G"+gidm]=="undefined"){
        ordergid[ordergid.length]=gidm;
    }else{
        orderRemoveGidBgcolor(gidm);
        
        var tmp_obj=orderArray["G"+gidm];
        if (tmp_obj.wtype==wtype&&tmp_obj.gid==gid){
            orderRemoveGid(gid);
            if (ordergid.length > 0){
                orderParlayParam();
            }else{
                
                    try{
                        parent.mem_order.close_bet();   
                    }catch(E){}
            }
            return;
        }   
    }
            
    try{
        //alert(gid+"_"+wtype+","+body_browse.document.getElementById(gid+"_"+wtype).className);
        var classary=(body_browse.document.getElementById(gid+"_"+wtype).className).split("_");
        //alert(classary.length);
        body_browse.document.getElementById(gid+"_"+wtype).className="pr_"+classary[1];
    }catch(E){
        //alert("找不到標籤")    
    }
    //var gameparam="game1="+wtype+"&game_id1="+gid+"&Hgame_id1="+hgid;
    var orderobj=new Object();
    orderobj.wtype=wtype;
    orderobj.gid=gid;
    orderobj.hgid=hgid;
    orderobj.par_minlimit=par_minlimit;
    orderobj.par_maxlimit=par_maxlimit;
    //orderobj.gameparam=gameparam;
    orderArray["G"+gidm]=orderobj;
    //alert(ordergid.length);
    orderParlayParam();
    
    }
//------------------------------------------------------------------------------------
function orderParlayParam(){
    var param="";
        for(var i=0;i < ordergid.length;i++){
            var obj=orderArray["G"+ordergid[i]];
            if (i!=0) param+="&";
            gameparam="game"+(i+1)+"="+obj.wtype+"&game_id"+(i+1)+"="+obj.gid+"&Hgame_id"+(i+1)+"="+obj.hgid+"&minlimit"+(i+1)+"="+obj.par_minlimit+"&maxlimit"+(i+1)+"="+obj.par_maxlimit;
            param+=gameparam;
        }
    parent.paramData=new Array();
    
    parent.mem_order.betOrder('FT','P3',"teamcount="+ordergid.length+"&uid="+uid+"&langx="+langx+"&"+param);
}
//--------------------------public function --------------------------------

function parseMyLove(GameData){

    var tmpStr="";
    //====== 加入現場轉播功能 2009-04-09, VideoFun 放在 flash_ior_mem.js
    tmpStr = "<table width='100%'   border='0' cellpadding='0' cellspacing='0'><tr><td align='left'>"+str_even+"</td>";             
    tmpStr+= "<td class='hot_td' >";
//  tmpStr+= "<table><tr align='right'><td>";
    //tmpStr+=MM_ShowLoveI(GameData.gnum_h,GameData.datetime,GameData.league,GameData.team_h,GameData.team_c);
//  tmpStr+= "</td></tr></table>";
    tmpStr+= "</td>";
    tmpStr+= "</tr></table>";

    return  tmpStr;
}

</script>
<?php
break;
}
$staticurl='/app/member/FT_future/';
$staticHtmlUrl='';
switch ($rtype){
	case "r":
		$getRedisUrl=$redisObj->getSimpleOne("FUTURE_R_{$open}_URL");
		if($getRedisUrl && file_exists($getRedisUrl)){
			//$staticHtmlUrl = TODAYFUTURE_STATIC_SERVER.$staticurl.$getRedisUrl;
			$staticHtmlUrl = $getRedisUrl;
		}
		break;
	case "pd":
		$getRedisUrl=$redisObj->getSimpleOne("FUTURE_PD_{$open}_URL");
		if($getRedisUrl && file_exists($getRedisUrl) ){
			//$staticHtmlUrl = TODAYFUTURE_STATIC_SERVER.$staticurl.$getRedisUrl;
			$staticHtmlUrl = $getRedisUrl;
		}
		break;
	case "hpd":
		$getRedisUrl=$redisObj->getSimpleOne("FUTURE_HPD_{$open}_URL");
		if($getRedisUrl && file_exists($getRedisUrl)){
			//$staticHtmlUrl = TODAYFUTURE_STATIC_SERVER.$staticurl.$getRedisUrl;
			$staticHtmlUrl = $getRedisUrl;
		}
		break;
	case "t":
		$getRedisUrl=$redisObj->getSimpleOne("FUTURE_T_{$open}_URL");
		if($getRedisUrl && file_exists($getRedisUrl)){
			//$staticHtmlUrl = TODAYFUTURE_STATIC_SERVER.$staticurl.$getRedisUrl;
			$staticHtmlUrl = $getRedisUrl;
		}
		break;
	case "f":
		$getRedisUrl=$redisObj->getSimpleOne("FUTURE_F_{$open}_URL");
		if($getRedisUrl && file_exists($getRedisUrl)){
			//$staticHtmlUrl = TODAYFUTURE_STATIC_SERVER.$staticurl.$getRedisUrl;
			$staticHtmlUrl = $getRedisUrl;
		}
		break;
	case "p3":
		$getRedisUrl=$redisObj->getSimpleOne("FUTURE_FT_P3_{$open}_URL");
		if($getRedisUrl && file_exists($getRedisUrl)){
			//$staticHtmlUrl = TODAYFUTURE_STATIC_SERVER.$staticurl.$getRedisUrl;
			$staticHtmlUrl = $getRedisUrl;
		}
		break;
}

?>

</head>
<frameset border="0" framespacing="0">
	<?php 
		if($staticHtmlUrl==""){ // scrolling="NO"
	?>
	<frame name="body_var"  noresize src="body_var.php?uid=<?php echo $uid?>&rtype=<?php echo $rtype?>&langx=<?php echo $langx?>&g_date=<?php echo $date?>&mtype=3&league_id=<?php echo $league_id?>&showtype=<?php echo $showtype?>">
	<?php
		}else{
	?>
	<frame name="body_var" noresize src="<?php echo $staticHtmlUrl;?>">
	<?php
		}
	?>
</frameset>
<noframes>
	<body bgcolor="#FFFFFF"></body>
</noframes>
</html>
