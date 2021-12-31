<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");          
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include "../include/address.mem.php";
echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
require ("../include/config.inc.php");

// 判断今日赛事是否维护-单页面维护功能
checkMaintain($_REQUEST['showtype']);

$uid=$_REQUEST['uid'];
$langx=$_SESSION['langx'];
$open=$_SESSION['OpenType'];
$rtype=ltrim(strtolower($_REQUEST['rtype']));
$league_id=$_REQUEST['league_id'];
require ("../include/traditional.$langx.inc.php");
if ($rtype==""){
	$rtype="r";
}

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
	echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
	exit;
}

$redisObj = new Ciredis();

$dcRedisObj = new Ciredis('datacenter');

$ft_cou_num = $dcRedisObj->getSimpleOne("FT_Today_Num"); // 今日足球
$bk_cou_num = $dcRedisObj->getSimpleOne("BK_Today_Num");// 今日篮球
$FT_Running_Num = $dcRedisObj->getSimpleOne("FT_Running_Num"); // 足球滚球数量
$BK_Running_Num = $dcRedisObj->getSimpleOne("BK_Running_Num");// 篮球滚球数量

?>
<html>
<head>
<title>下注分割畫面</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8 ">
<script type="text/javascript" src="../../../js/betcommon.js?v=<?php echo AUTOVER; ?>"></script>
<script>
    var rtype= ('<?php echo $rtype?>'); // 当前是全场或者上半场
    var keepleg="";
    var legnum=0;
    var NoshowLeg=new Array();
    var myLeg=new Array();
    var LeagueAry=new Array();

    var username='';
    var maxcredit='';
    var code='';
    var pg=0; // 当前第几页
    var uid=''; //user's session ID
    var loading = 'Y'; //是否正在讀取瀏覽頁面
    var loading_var = 'Y'; //是否正在讀取變數值頁面
    var ShowType = ''; //目前顯示頁面
    var ltype = 1; //目前顯示line
    var retime_flag = 'N'; //自動更新旗標

    var str_even = '和局';
    var str_renew = '秒自動更新';
    var str_submit = '確認';
    var str_reset = '重設';
    var num_page = 20; //設定20筆賽程一頁
    var now_page = 1; //目前顯示頁面
    var pages = 1; //總頁數
    var msg = ''; //即時資訊
    var gamount = 0; //目前顯示一般賽程數
    var sel_gtype='BK';

    var ft_cou_num = '<?php echo $ft_cou_num;?>'; // 今日足球
    var bk_cou_num = '<?php echo $bk_cou_num;?>';// 今日篮球
    var bk_running_num = '<?php echo $BK_Running_Num;?>';// 足球滚球数量

    window.onload = function () {
        // 更新球的数量
        parent.frames.header.document.getElementById('FT_games').innerHTML = ft_cou_num ; // 足球数量
        parent.frames.header.document.getElementById('BK_games').innerHTML = bk_cou_num ; // 篮球数量
        parent.frames.header.document.getElementById('RB_games').innerHTML = bk_running_num ; // 滚球数量
    }


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
<?php switch($rtype){
	case "re":  // 滚球
?>
<script>


// 隐藏数据空的标题
function hiddeRbBasketball() {
    var $rb_bk_box = document.getElementsByClassName('rb_bk_box') ;
    var rb_bk_box = document.querySelectorAll('.rb_bk_box') ;
   // console.log($rb_bk_box) ;
    //console.log($rb_bk_box) ;
    for(var ii in $rb_bk_box){
        console.log(ii)
    }
   /* for(var i= 0; i < rb_bk_box.length; i++){

    }*/

}

function show_more(gid,evt,all){
	evt = evt ? evt : (window.event ? window.event : null);
    var mY = evt.pageY ? evt.pageY : evt.y;
    retime_flag='N' ; // 自动刷新标志
	if(all){
		body_var.document.getElementById('all_more_window').style.position='fixed';
        body_var.document.getElementById('all_more_window').style.top=0;
        body_var.document.getElementById('all_more_window').style.left=body_var.document.body.scrollLeft+10;
        body_var.document.getElementById('all_more_window').style.width='100%';
        body_var.document.getElementById('all_more_window').style.zIndex='10';
        var  url="body_var_re_allbets.php?gid="+gid+"&uid="+uid+"&ltype="+ltype+"&langx="+top.langx;
        body_var.all_showdata.location.href = url;
	}else{
	    var  url="body_var_r_more.php?gid="+gid+"&uid="+uid+"&ltype="+ltype;
	    body_var.showdata.location.href = url;
	}
}

function parseMyLove(GameData){
    var tmpStr="";
    //====== 加入現場轉播功能 2009-04-09, VideoFun 放在 flash_ior_mem.js
    tmpStr = "<table id='fav_box' width='100%' border='0' cellpadding='0' cellspacing='0'><tr>";//<td align='left'>"+str_even+"</td>";
    tmpStr+= "<td class='hot_td' >";
    tmpStr+=MM_ShowLoveI(GameData.gnum_h,GameData.datetime,GameData.league,GameData.team_h,GameData.team_c);
    tmpStr+= "</td>";
    tmpStr+= "</tr></table>";

    return  tmpStr;
}

</script>

<?php  break;
	case "p3": // 综合过关
?>
<script>

    var hotgdateArr =new Array();
    function hot_gdate(gdate){
        if((""+hotgdateArr).indexOf(gdate)==-1){
            hotgdateArr.push(gdate);
        }
    }


    function orderShowSelALL(){
        for(var i=0;i < top.ordergid.length;i++){
            var obj=top.orderArray["G"+top.ordergid[i]];
            try{
                var classary=(body_var.document.getElementById(obj.gid+"_"+obj.wtype).className).split("_");
                body_var.document.getElementById(obj.gid+"_"+obj.wtype).className="pr_"+classary[1];
            }catch(E){}
        }
    }

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
    	for(var i=0;i<top.ordergid.length;i++){
                orderRemoveGidBgcolor(top.ordergid[i]);     
        }
        top.orderArray=new Array();
        top.ordergid=new Array();
    }

function orderRemoveGid(removeGid){
    for(var i=0;i<top.ordergid.length;i++){
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
        var classary=( body_var.document.getElementById(tmpobj.gid+"_"+tmpobj.wtype).className).split("_");
        body_var.document.getElementById(tmpobj.gid+"_"+tmpobj.wtype).className="b_"+classary[1];
    }catch(E){}
}

function orderParlay(gidm,gid,hgid,wtype,par_minlimit,par_maxlimit){
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
        var classary=(body_var.document.getElementById(gid+"_"+wtype).className).split("_");
        body_var.document.getElementById(gid+"_"+wtype).className="pr_"+classary[1];
    }catch(E){
    }
    var orderobj=new Object();
    orderobj.wtype=wtype;
    orderobj.gid=gid;
    orderobj.hgid=hgid;
    orderobj.par_minlimit=par_minlimit;
    orderobj.par_maxlimit=par_maxlimit;
    top.orderArray["G"+gidm]=orderobj;
    orderParlayParam();

}


//------------------------------------------------------------------------------------
function orderParlayParam(){
    var param="";
        for(var i=0;i<top.ordergid.length;i++){
            var obj=top.orderArray["G"+top.ordergid[i]];
            if (i!=0) param+="&";
             gameparam="game"+(i+1)+"="+obj.wtype+"&game_id"+(i+1)+"="+obj.gid+"&minlimit"+(i+1)+"="+obj.par_minlimit+"&maxlimit"+(i+1)+"="+obj.par_maxlimit;
            param+=gameparam;
            }
    parent.paramData=new Array();
    parent.mem_order.betOrder('BK','PR',"teamcount="+top.ordergid.length+"&uid="+top.uid+"&langx="+top.langx+"&"+param);
}


function killgid(gids){
    //alert(gids);
    var gidary=gids.split("|");
    for (var i=0;i<gidary.length;i++){
        orderRemoveGid(gidary[i]);
    }
    alert(top.str_otb_close);
}

function parseMyLove(GameData){

    var tmpStr="";
    //====== 加入現場轉播功能 2009-04-09, VideoFun 放在 flash_ior_mem.js
    tmpStr = "<table id='fav_box' width='100%' border='0' cellpadding='0' cellspacing='0'><tr>";                
    tmpStr+= "<td class='hot_td' >";
//  tmpStr+= "<table><tr align='right'><td>";
    tmpStr+=MM_ShowLoveI(GameData.gnum_h,GameData.datetime,GameData.league,GameData.team_h,GameData.team_c);
//  tmpStr+= "</td></tr></table>";
    tmpStr+= "</td>";
    tmpStr+= "</tr></table>";

    return  tmpStr;
}


</script>
<?php  break;
	default: // 默认
?>
<script>

function show_more(gid,evt,all){
    evt = evt ? evt : (window.event ? window.event : null);
    var mY = evt.pageY ? evt.pageY : evt.y;
    retime_flag='N' ; // 自动刷新标志
    if(all){
    	body_var.document.getElementById('all_more_window').style.position='fixed';
        body_var.document.getElementById('all_more_window').style.top=0;
        body_var.document.getElementById('all_more_window').style.left=body_var.document.body.scrollLeft+10;
        body_var.document.getElementById('all_more_window').style.width='100%';
        body_var.document.getElementById('all_more_window').style.zIndex='10';
        var  url="body_var_r_allbets.php?gid="+gid+"&uid="+uid+"&ltype="+ltype+"&langx="+top.langx;
        body_var.all_showdata.location.href = url;
    }else{
        var  url="body_var_r_more.php?gid="+gid+"&uid="+uid+"&ltype="+ltype;
        body_var.showdata.location.href = url;
    }
}

function parseMyLove(GameData){
    var tmpStr="";
    //====== 加入現場轉播功能 2009-04-09, VideoFun 放在 flash_ior_mem.js
    tmpStr = "<table id='fav_box' width='99%' border='0' cellpadding='0' cellspacing='0'><tr>";//<td align='left'>"+str_even+"</td>";
    tmpStr+= "<td class='hot_td'>";
    tmpStr+=MM_ShowLoveI(GameData.gnum_h,GameData.datetime,GameData.league,GameData.team_h,GameData.team_c);
    tmpStr+= "</td>";
    tmpStr+= "</tr></table>";

    return  tmpStr;
}

</script>

<?php
	break;
}

$staticHtmlUrl='';
$staticurl='/app/member/BK_browse/';
switch($rtype)
{
	case 're': // 滚球
        $closedGame = explode('|', $_SESSION['gameSwitch']);
        $isClosedQ3 = in_array('BKQ3', $closedGame);
        $isClosedH1 = in_array('BKH1', $closedGame);
        $isClosedDJBK = in_array('DJBK',$closedGame);

        if ($isClosedDJBK){
            $getRedisUrl=$redisObj->getSimpleOne("BK_M_ROU_EO_NODJBK_{$open}_URL");
            if ($getRedisUrl && file_exists($getRedisUrl)) {
                $staticHtmlUrl = $getRedisUrl;
            }
            break;
        }

        if ($isClosedQ3 && !$isClosedH1) { // 关闭会员篮球滚球第三节
            $getRedisUrl=$redisObj->getSimpleOne("BK_M_ROU_EO_NOQ3_{$open}_URL");
            if ($getRedisUrl && file_exists($getRedisUrl)) {
                $staticHtmlUrl = $getRedisUrl;
            }
        } elseif (!$isClosedQ3 && $isClosedH1) { // 关闭会员篮球滚球上半场
            $getRedisUrl=$redisObj->getSimpleOne("BK_M_ROU_EO_NOH1_{$open}_URL");
            if ($getRedisUrl && file_exists($getRedisUrl)) {
                $staticHtmlUrl = $getRedisUrl;
            }
        } elseif($isClosedQ3 && $isClosedH1) { // 同时关闭会员篮球滚球上半场、第三节
            $getRedisUrl=$redisObj->getSimpleOne("BK_M_ROU_EO_NOQ3H1_{$open}_URL");
            if ($getRedisUrl && file_exists($getRedisUrl)) {
                $staticHtmlUrl = $getRedisUrl;
            }
        } else {
            $getRedisUrl=$redisObj->getSimpleOne("BK_M_ROU_EO_{$open}_URL");
            if ($getRedisUrl && file_exists($getRedisUrl)) {
                //$staticHtmlUrl = RUNNING_STATIC_SERVER.$staticurl.$getRedisUrl;
                $staticHtmlUrl = $getRedisUrl;
            }
        }
		break;
	case 'all': // 全部
		$getRedisUrl=$redisObj->getSimpleOne("TODAY_BK_M_ROU_EO_{$open}_URL");
		if($getRedisUrl && file_exists($getRedisUrl)){
			//$staticHtmlUrl = TODAYFUTURE_STATIC_SERVER.$staticurl.$getRedisUrl;
			$staticHtmlUrl = $getRedisUrl;
		}
		break;
	case 'p3': // 综合过关
		$getRedisUrl=$redisObj->getSimpleOne("TODAY_BK_P3_{$open}_URL");
		if($getRedisUrl && file_exists($getRedisUrl)){
			//$staticHtmlUrl = TODAYFUTURE_STATIC_SERVER.$staticurl.$getRedisUrl;
			$staticHtmlUrl = $getRedisUrl;
		}
		break;
}

?>
<script>

function chgForm_Single_ratio(odds,rtype,wtype){
    rtype = rtype.toUpperCase();
    //return odds;
    odds = odds*1;
    //Ricky 2017-11-15 四捨五入邏輯
    //因為讓球大小玩法目前開出賠率5以上會有問題 , 所以先讓這兩種玩法不要走進四捨五入邏輯裡
    var isRorOU = chkIsRorOU(wtype);
    var isM = chkIsM(rtype);
    var isFS = chkIsFS(rtype);
    var isEven = chkIsEven(rtype);

    //alert("isM->"+", isFS->"+isFS+", wtype->"+wtype+" , odds->"+odds);
    //Ricky 2017-11-15 四捨五入邏輯
    //因為讓球大小玩法目前開出賠率5以上會有問題 , 所以先讓這兩種玩法不要走進四捨五入邏輯裡
    if(isRorOU)
    {
        return formatNumber(odds,2,2);
    }
    else if(isEven)
    {
        return formatNumber(odds,2,2);
    }
    else
    {
        if(!(isM || isFS) && odds == 0){
            return formatNumber(odds,0);
        }else if((isM || isFS) && 10 <= odds && odds < 98.5){		//獨贏or冠軍賠率10~98.5 秀小數點後一位
            return formatNumber(odds,1,1);
        }else if(!(isM || isFS) && 5 <= odds && odds < 20){	//非獨贏賠率5~20 秀小數點後一位
            return formatNumber(odds,1,1);
        }else if(!(isM || isFS) && 20 <= odds ){							//非獨贏賠率大於等於20秀整數
            return formatNumber(odds,0);
        }else if((isM || isFS) && 101 <= odds ){						//獨贏or冠軍賠率大於等於101秀整數
            return formatNumber(odds,0);
        }

        return formatNumber(odds,2,2);
    }

}

function chkIsFS(wtype){
    var isFS = false;

    var ary = new Array();
    ary["FS"] = true;
    ary["SFS"] = true;

    if(ary[wtype] || wtype.indexOf("FS")!= -1){
        isFS = true;
    }

    return isFS;
}

function chkIsM(rtype){
    try{
        rtype = rtype.toUpperCase();
    }catch(e){}
    var isM = false;

    var M_wtype = new Array("A","B","C","D","E","F");
    var F_wtype = new Array("01","02");
    var RF_wtype = new Array("01","02","03","04","05","06","07","08","09","10",
        "11","12","13","14","15","16","17","18","19","20",
        "21","22","23","24","25","26","27","28","29","30",
        "31","32","33","34","35");

    var ary = new Array();
    ary["MH"] = true;
    ary["MC"] = true;
    ary["MN"] = true;
    ary["HMH"] = true;
    ary["HMC"] = true;
    ary["HMN"] = true;
    ary["RMH"] = true;
    ary["RMC"] = true;
    ary["RMN"] = true;
    ary["HRMH"] = true;
    ary["HRMC"] = true;
    ary["HRMN"] = true;

    for(var i = 0;i < M_wtype.length;i++){
        ary[M_wtype[i]+"MH"] = true;
        ary[M_wtype[i]+"MC"] = true;
        ary[M_wtype[i]+"MN"] = true;
    }
    for(var i = 0;i < F_wtype.length;i++){
        ary["F"+F_wtype[i]+"H"] = true;
        ary["F"+F_wtype[i]+"C"] = true;
    }
    for(var i = 0;i < RF_wtype.length;i++){
        ary["RF"+RF_wtype[i]+"H"] = true;
        ary["RF"+RF_wtype[i]+"C"] = true;
    }

    if(ary[rtype]){
        isM = true;
    }

    return isM;
}

function chkIsEven(wtype) {
    var isEven = false;

    var OUHC = new Array("OUH","OUC","HOUH","HOUC");
    //Ricky 2018-01-26 PJB-176 CRM-229世界盃新玩法 (19)新、手機會員端-內層盤面/所有注單-5分鐘進球，不走四捨五入邏輯
    var DOUBLE = new Array(
        "TARU","TARUO","TARUU",
        "TBRU","TBRUO","TBRUU",
        "TDRU","TDRUO","TDRUU",
        "TERU","TERUO","TERUU",
        "EOH","EOC","HEOH","HEOC"
        ,"RSH1","RSH2","RSH3","RSH4","RSH5","RSH6","RSH7","RSH8","RSH9","RSHA","RSHB","RSHC","RSHD","RSHE","RSHF","RSHG","RSHH","RSHI","RSHJ","RSHK"
        ,"RSHL","RSHM","RSHN","RSHO","RSHP","RSHQ","RSHR","RSHS","RSHT","RSHU"
        ,"RSC1","RSC2","RSC3","RSC4","RSC5","RSC6","RSC7","RSC8","RSC9","RSCA","RSCB","RSCC","RSCD","RSCE","RSCF","RSCG","RSCH","RSCI","RSCJ","RSCK"
        ,"RSCL","RSCM","RSCN","RSCO","RSCP","RSCQ","RSCR","RSCS","RSCT","RSCU"
        ,"RNB1","RNB2","RNB3","RNB4","RNB5","RNB6","RNB7","RNB8","RNB9","RNBA","RNBB","RNBC","RNBD","RNBE","RNBF","RNBG","RNBH","RNBI","RNBJ","RNBK"
        ,"RNBL","RNBM","RNBN","RNBO","RNBP","RNBQ","RNBR","RNBS","RNBT","RNBU"
        ,"RNC1","RNC2","RNC3","RNC4","RNC5","RNC6","RNC7","RNC8","RNC9","RNCA","RNCB","RNCC","RNCD","RNCE","RNCF","RNCG","RNCH","RNCI","RNCJ","RNCK"
        ,"RNCL","RNCM","RNCN","RNCO","RNCP","RNCQ","RNCR","RNCS","RNCT","RNCU"
    );
    var OU15 = new Array("AOU","BOU","COU","DOU","EOU","FOU");
    var R15 = new Array("AR","BR","CR","DR","ER","FR");
    var ROU15 = new Array("AROU","BROU","CROU","DROU","EROU","FROU");
    var ROUHC = new Array("ROUH","ROUC","HRUH","HRUC");


    var ary = new Array();
    ary["HR"] = true;
    ary["R"] = true;
    ary["HRE"] = true;
    ary["RE"] = true;
    ary["HOU"] = true;
    ary["OU"] = true;
    ary["HROU"] = true;
    ary["ROU"] = true;

    for(var i=0;i<OUHC.length;i++){
        ary[OUHC[i]] = true;
    }

    for(var i=0;i<DOUBLE.length;i++){
        ary[DOUBLE[i]] = true;
    }

    for(var i=0;i<OU15.length;i++){
        ary[OU15[i]] = true;
    }

    for(var i=0;i<R15.length;i++){
        ary[R15[i]] = true;
    }

    for(var i=0;i<ROU15.length;i++){
        ary[ROU15[i]] = true;
    }

    for(var i=0;i<ROUHC.length;i++){
        ary[ROUHC[i]] = true;
    }

    if(ary[wtype])
    {
        isEven = true;
    }

    return isEven;

}

//Ricky 2017-11-15 四捨五入邏輯
//因為讓球大小玩法目前開出賠率5以上會有問題 , 所以先讓這兩種玩法不要走進四捨五入邏輯裡
function chkIsRorOU(wtype){
    var isRorOU = false;

    var OU = new Array("OU","ROU","HOU","HROU","OUH","OUC","ROUH","ROUC","HOUH","HOUC","HROUH","HROUC","POU","HPOU","POUH","POUC","HPOUH","HPOUC");
    var R = new Array("R","RE","HR","HRE","RH","RC","REH","REC","HRH","HRC","HREH","HREC","PR","HPR","PRH","PRC","HPRH","HPRC");
    //Ricky 2018-01-26 PJB-176 CRM-229世界盃新玩法 (19)新、手機會員端-內層盤面/所有注單-5分鐘進球，不走四捨五入邏輯
    var DOUBLE = new Array(
        "TARU","TARUO","TARUU",
        "TBRU","TBRUO","TBRUU",
        "TDRU","TDRUO","TDRUU",
        "TERU","TERUO","TERUU",
        "EO","HEO","REO","HREO","EOH","EOC","HEOH","HEOC","EOO","EOE","HEOO","HEOE","REOO","REOE","HREOO","HREOE"
        ,"AEO","BEO","CEO","DEO","EEO","FEO","GEO","AREO","BREO","CREO","DREO","EREO","FREO","GREO"
        ,"RSH1","RSH2","RSH3","RSH4","RSH5","RSH6","RSH7","RSH8","RSH9","RSHA","RSHB","RSHC","RSHD","RSHE","RSHF","RSHG","RSHH","RSHI","RSHJ","RSHK"
        ,"RSHL","RSHM","RSHN","RSHO","RSHP","RSHQ","RSHR","RSHS","RSHT","RSHU"
        ,"RSC1","RSC2","RSC3","RSC4","RSC5","RSC6","RSC7","RSC8","RSC9","RSCA","RSCB","RSCC","RSCD","RSCE","RSCF","RSCG","RSCH","RSCI","RSCJ","RSCK"
        ,"RSCL","RSCM","RSCN","RSCO","RSCP","RSCQ","RSCR","RSCS","RSCT","RSCU"
        ,"RNB1","RNB2","RNB3","RNB4","RNB5","RNB6","RNB7","RNB8","RNB9","RNBA","RNBB","RNBC","RNBD","RNBE","RNBF","RNBG","RNBH","RNBI","RNBJ","RNBK"
        ,"RNBL","RNBM","RNBN","RNBO","RNBP","RNBQ","RNBR","RNBS","RNBT","RNBU"
        ,"RNC1","RNC2","RNC3","RNC4","RNC5","RNC6","RNC7","RNC8","RNC9","RNCA","RNCB","RNCC","RNCD","RNCE","RNCF","RNCG","RNCH","RNCI","RNCJ","RNCK"
        ,"RNCL","RNCM","RNCN","RNCO","RNCP","RNCQ","RNCR","RNCS","RNCT","RNCU"
        ,"PEO","HPEO","PREO","HPREO","PEOH","PEOC","HPEOH","HPEOC","PEOO","PEOE","HPEOO","HPEOE"
        ,"APEO","BPEO","CPEO","DPEO","EPEO","FPEO","GPEO"
    );
    var OU15 = new Array("AOU","BOU","COU","DOU","EOU","FOU","GOU"
        ,"APOU","BPOU","CPOU","DPOU","EPOU","FPOU","GPOU"
        ,"PAOU","PBOU","PCOU","PDOU","PEOU","PFOU","PGOU"
        ,"AOUH","BOUH","COUH","DOUH","EOUH","FOUH","GOUH"
        ,"APOUH","BPOUH","CPOUH","DPOUH","EPOUH","FPOUH","GPOUH"
        ,"PAOUH","PBOUH","PCOUH","PDOUH","PEOUH","PFOUH","PGOUH"
        ,"AOUC","BOUC","COUC","DOUC","EOUC","FOUC","GOUC"
        ,"APOUC","BPOUC","CPOUC","DPOUC","EPOUC","FPOUC","GPOUC"
        ,"PAOUC","PBOUC","PCOUC","PDOUC","PEOUC","PFOUC","PGOUC"
        ,"AROUH","BROUH","CROUH","DROUH","EROUH","FROUH","GROUH"
        ,"AROUC","BROUC","CROUC","DROUC","EROUC","FROUC","GROUC"
    );
    var R15 = new Array("AR","BR","CR","DR","ER","FR","GR"
        ,"APR","BPR","CPR","DPR","EPR","FPR","GPR"
        ,"PAR","PBR","PCR","PDR","PER","PFR","PGR"
        ,"ARE","BRE","CRE","DRE","ERE","FRE","GRE"
    );
    var ROU15 = new Array("AROU","BROU","CROU","DROU","EROU","FROU");
    var ROUHC = new Array("ROUH","ROUC","HRUH","HRUC");

    var ary = new Array();

    for(var i=0;i<R.length;i++){
        ary[R[i]] = true;
    }
    for(var i=0;i<OU.length;i++){
        ary[OU[i]] = true;
    }

    for(var i=0;i<DOUBLE.length;i++){
        ary[DOUBLE[i]] = true;
    }

    for(var i=0;i<OU15.length;i++){
        ary[OU15[i]] = true;
    }

    for(var i=0;i<R15.length;i++){
        ary[R15[i]] = true;
    }

    for(var i=0;i<ROU15.length;i++){
        ary[ROU15[i]] = true;
    }

    for(var i=0;i<ROUHC.length;i++){
        ary[ROUHC[i]] = true;
    }

    if(ary[wtype])
    {
        isRorOU = true;
    }

    return isRorOU;
}


/*
CRM-230 單盤（without spread）玩法賠率的四捨五入邏輯 (會員端)
*/

    
</script>

</head>
<frameset  border="0" framespacing="0">
	<?php 
		if($staticHtmlUrl==""){ // scrolling="NO"
	?>
  	<frame name="body_var"  noresize src="body_var.php?uid=<?php echo $uid?>&rtype=<?php echo $rtype?>&langx=<?php echo $langx?>&league_id=<?php echo $league_id?>">
  	<?php
		}else{
	?>
		<frame name="body_var"  noresize src="<?php echo $staticHtmlUrl;?>">
	<?php
		}
	?>
</frameset>
<noframes>
<body></body>
</noframes>
</html>
