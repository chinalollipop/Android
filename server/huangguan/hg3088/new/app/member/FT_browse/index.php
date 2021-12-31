<?php
session_start();
header("Expires: Mon, 26 Jul 1970 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include "../include/address.mem.php";
echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";

require ("../include/config.inc.php");

// 判断滚球是否维护-单页面维护功能
checkMaintain($_REQUEST['showtype']);

$uid=$_REQUEST['uid'];
$langx=$_SESSION['langx'];
$rtype=ltrim(strtolower($_REQUEST['rtype']));
$league_id=$_REQUEST['league_id'];
$showtype=$_REQUEST['showtype'];
require ("../include/traditional.$langx.inc.php");

if ($rtype==""){ // $rtype 默认是 r
	$rtype="r";
}

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
	echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
	exit;
}
$Status=$_SESSION['Status'];
if ($Status==1){
exit;
}
$today_date = date("Y-m-d"); // 今日
$g_date = isset($_REQUEST['g_date'])?$_REQUEST['g_date']:'' ;
if($g_date==''){ // 切换日期，默认今日
    $p3_date = $today_date;
}else{
    $p3_date = '$g_date';
}


?>

<html>
<head>
<title>下注分割畫面</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <script type="text/javascript" src="../../../js/betcommon.js?v=<?php echo AUTOVER; ?>"></script>

<script type="text/javascript">

var lang='<?php echo $langx?>';

var r_type = ('<?php echo $rtype?>').toUpperCase() ; // 当前是全场或者上半场

var keepleg="";
var legnum=0;
var NoshowLeg=new Array();
var myLeg=new Array();
var LeagueAry=new Array();
var username='';
var maxcredit='';
var code='';
var pg=0; // 当前第几页
var sel_league='';	//選擇顯示聯盟
var uid='<?php echo $uid ?>';		//user's session ID
top.loading = 'Y';	//是否正在讀取瀏覽頁面
top.loading_var = 'Y';	//是否正在讀取變數值頁面
top.showtype = '<?php echo $showtype?>' ; //目前顯示頁面
var ltype = 1;		//目前顯示line
var retime_flag = 'N';	//自動更新旗標

var str_even = '和局';
var str_renew = '秒自動更新';
var str_submit = '確認';
var str_reset = '重設';

var msg = '';		//即時資訊
var gamount = 0;	//目前顯示一般賽程數

var sel_gtype='FT';

var GameHead=new Array();


//====== 加入現場轉播功能 2009-04-09
// 開啟轉播
function OpenLive(eventid, gtype){
    if (top.liveid == undefined) {
        parent.self.location = "";
        return;
    }
    window.open("../live/live.php?langx="+top.langx+"&uid="+top.uid+"&liveid="+top.liveid+"&eventid="+eventid+"&gtype="+gtype,"Live","width=780,height=585,top=0,left=0,status=no,toolbar=no,scrollbars=no,resizable=no,personalbar=no");
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
//--------------------------------------------------------------------滚球---------------------
switch($rtype){
case 're': // 滚球
?>
<script> 


function show_more(gid,evt,all){

    evt = evt ? evt : (window.event ? window.event : null);
    var mY = evt.pageY ? evt.pageY : evt.y;
    retime_flag='N' ; // 自动刷新标志
    if(all){
        body_var.document.getElementById('all_more_window').style.position='fixed';
        body_var.document.getElementById('all_more_window').style.top=0;
        body_var.document.getElementById('all_more_window').style.width='100%';
        body_var.document.getElementById('all_more_window').style.zIndex='10';
        var  url="body_var_re_allbets.php?gid="+gid+"&uid="+uid+"&ltype="+ltype+"&langx="+top.langx;
        body_var.all_showdata.location.href = url;
    }else{ // 原来的
        body_var.document.getElementById('more_window').style.position='absolute';
        body_var.document.getElementById('more_window').style.top=mY+30;
        body_var.document.getElementById('more_window').style.left=body_var.document.body.scrollLeft+10;

        var  url="body_var_r_more.php?gid="+gid+"&uid="+uid+"&ltype="+ltype+"&langx="+top.langx;
        body_var.showdata.location.href = url;
    }

}

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
case 'r': // 全部
?>
<script>

var keepscroll=0;
var step=1;

// all 所有玩法
function show_more(gid,evt,all){
    evt = evt ? evt : (window.event ? window.event : null);
    var mY = evt.pageY ? evt.pageY : evt.y;
    retime_flag='N' ; // 自动刷新标志
    if(all){
        body_var.document.getElementById('all_more_window').style.position='fixed';
        body_var.document.getElementById('all_more_window').style.top=0;
        body_var.document.getElementById('all_more_window').style.width='100%';
        body_var.document.getElementById('all_more_window').style.zIndex='10';
        var  url="body_var_r_allbets.php?gid="+gid+"&uid="+uid+"&ltype="+ltype+"&langx="+top.langx;
        body_var.all_showdata.location.href = url;
    }else{ // 原来的
        body_var.document.getElementById('more_window').style.position='absolute';
        body_var.document.getElementById('more_window').style.top=mY+30;
        body_var.document.getElementById('more_window').style.left=body_var.document.body.scrollLeft+10;

        var  url="body_var_r_more.php?gid="+gid+"&uid="+uid+"&ltype="+ltype+"&langx="+top.langx;
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

//---------------------------------------------------------------------------------------------- 让球结束  --------------------------------------------------------------------------
</script>
<?php
break;
case 'pd': // 波胆全场
case 'hpd': // 波胆上半场 2018/01 新增
case 'rpd': // 波胆全场
case 'hrpd': // 波胆上半场 2018/01 新增	
?>
<script>



</script>
<?php
break;
case 't':
case 'rt':	
?>
<script>

</script>
<?php
break;
case 'f': // 今日赛事 半场/全场
case 'rf':	
?>
<script>

</script>
<?php
break;
case 'p3': // 综合过关
?>
<script>

//------------------------新過關變色直接新增功能-------------------max 2010/10
top.orderArray=new Array();
top.ordergid=new Array();
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
    //alert("orderRemoveALL===>"+top.ordergid.length);
    for(var i=0;i < top.ordergid.length;i++){
        orderRemoveGidBgcolor(top.ordergid[i]);     
    }
    top.orderArray=new Array();
    top.ordergid=new Array();
}

function orderShowSelALL(){
    for(var i=0;i < top.ordergid.length;i++){
        var obj=top.orderArray["G"+top.ordergid[i]];
        try{
            var classary=(body_browse.document.getElementById(obj.gid+"_"+obj.wtype).className).split("_");
            body_browse.document.getElementById(obj.gid+"_"+obj.wtype).className="pr_"+classary[1];
        }catch(E){} 
    }
}


function orderRemoveGid(removeGid){

        
        for(var i=0;i < top.ordergid.length;i++){
        //alert("gid==>"+top.ordergid[i]);
            var obj=top.orderArray["G"+top.ordergid[i]];
            if (obj.gid==removeGid || obj.hgid==removeGid){
                orderRemoveGidBgcolor(top.ordergid[i]);
                top.orderArray["G"+top.ordergid[i]]="undefined";
                top.ordergid[i]=0;
            
                } 
        }
    
        top.ordergid=resort(top.ordergid);

    
    }
function orderRemoveGidBgcolor(gidm){
        var tmpobj=top.orderArray["G"+gidm];
        try{
            var classary=( body_browse.document.getElementById(tmpobj.gid+"_"+tmpobj.wtype).className).split("_");
            body_browse.document.getElementById(tmpobj.gid+"_"+tmpobj.wtype).className="b_"+classary[1];
        }catch(E){}
    }


function orderParlay(gidm,gid,hgid,wtype,par_minlimit,par_maxlimit){
//   alert(gidm+"_"+gid+"_"+wtype);

    // body_browse.document.getElementById(gid+"_"+wtype).bgColor="gold";
    if (""+top.orderArray["G"+gidm]=="undefined"){
        top.ordergid[top.ordergid.length]=gidm;
    }else{
        orderRemoveGidBgcolor(gidm);
        
        var tmp_obj=top.orderArray["G"+gidm];
        if (tmp_obj.wtype==wtype&&tmp_obj.gid==gid){
            orderRemoveGid(gid);
            if (top.ordergid.length > 0){
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
    top.orderArray["G"+gidm]=orderobj;
    //alert(ordergid.length);
    orderParlayParam();
    
    }
//------------------------------------------------------------------------------------
function orderParlayParam(){
    var param="";
        for(var i=0;i < top.ordergid.length;i++){
            var obj=top.orderArray["G"+top.ordergid[i]];
            if (i!=0) param+="&";
            gameparam="game"+(i+1)+"="+obj.wtype+"&game_id"+(i+1)+"="+obj.gid+"&Hgame_id"+(i+1)+"="+obj.hgid+"&minlimit"+(i+1)+"="+obj.par_minlimit+"&maxlimit"+(i+1)+"="+obj.par_maxlimit;
            param+=gameparam;
        }
    parent.paramData=new Array();
    
    parent.mem_order.betOrder('FT','P3',"teamcount="+top.ordergid.length+"&uid="+top.uid+"&langx="+top.langx+"&"+param);
}

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


function killgid(gids){
    //alert(gids);
    var gidary=gids.split("|");
    for (var i=0;i<gidary.length;i++){
        orderRemoveGid(gidary[i]);  
    }
    alert(top.str_otb_close);
}</script>
<?php
break;
}
?>


</head>
<frameset rows="*" frameborder="NO" border="0" framespacing="0">
	<frame name="body_var" src="body_var.php?uid=<?php echo $uid?>&rtype=<?php echo $rtype?>&langx=<?php echo $langx?>&mtype=3&delay=<?php echo $delay?>&league_id=<?php echo $league_id?>&showtype=<?php echo $showtype?>&g_date=<?php echo $p3_date?>">
	<!--<frame name="body_browse" id="body_browse" src="body_browse.php?uid=<?php /*echo $uid*/?>&rtype=<?php /*echo $rtype*/?>&langx=<?php /*echo $langx*/?>&mtype=3&delay=<?php /*echo $delay*/?>&showtype=<?php /*echo $showtype*/?>&g_date=<?php /*echo $p3_date*/?>">-->
</frameset>
<noframes>
<body bgcolor="#000000">


</body>
</noframes>
</html>