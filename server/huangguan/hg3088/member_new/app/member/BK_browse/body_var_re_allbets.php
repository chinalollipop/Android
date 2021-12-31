<?php
session_start();
error_reporting(0);
ini_set('display_errors','Off');

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include "../include/address.mem.php";
require ("../include/config.inc.php");
require ("../include/curl_http.php");

$gid   = $_REQUEST['gid'];
$langx=$_SESSION['langx'];
$uid   = $_REQUEST['uid'];
$ltype = $_REQUEST['ltype'];
$date = date("Y-m-d");
require ("../include/traditional.$langx.inc.php");

$open =$_SESSION['OpenType']; // 当前会员盘口类型
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
	echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
<meta name="Robots" contect="none">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<link rel="stylesheet" href="../../../style/member/bet_maincortol.css?v=<?php echo AUTOVER; ?>" type="text/css">
<script charset="utf-8" src="../../../js/jquery.js" ></script>
<script charset="utf-8" src="../../../js/betcommon.js?v=<?php echo AUTOVER; ?>" ></script>
<script>
var _REQUEST = new Array();
 _REQUEST['gid']='<?php echo $gid;?>';
 _REQUEST['uid']='<?php echo $uid;?>';
 _REQUEST['ltype']='4';
 _REQUEST['langx']='zh-cn';
 _REQUEST['gtype']='BK';
 _REQUEST['showtype']='RB';
 _REQUEST['date']='<?php echo $date;?>';
</script>
<script>
var opentype='<?php echo $open?>';
var retime=30;
var iorpoints=2;
var show_ior = '100';	//parent
var langx = '<?php echo $langx;?>';
</script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8 "><script>
var R_Regex = new RegExp('\^\RE\?$');
var OU_Regex = new RegExp('\^R\?OU[HC]\?$');
var PD_Regex = new RegExp('\^R\?PD\[HC\]\[0\-5\]\?$');
var EO_Regex = new RegExp('\^R\?EO$');

var ObjDataFT = new Array();   //資料
var gid_ary = new Array();
var gid_rtype_ior = new Array();
var obj_ary = new Array("myMarkets","mainMarkets");

var team_RegExp = new RegExp(' - \\(\[\^\\)\]+\\)');


var open_movie = {"myMarkets":false,"mainMarkets":true};
//var open_mod = {"ALL_Markets":true,"Pop_Markets":true,"HDP_OU":true,"first_Half":true,"Socore":true,"Corners":true,"Specials":true,"Others":true};

var retime_flag;
var retime_run;
var mod="ALL_Markets";
var show_gid;
var systime="";

top.more_bgYalloW ="";
var TV_eventid = "";
var FavRevMap;
var mid = top.uid.match(/m\d*l\d*$/);
//mid = mid[0];
//mid =	mid.substring(1,mid.length).split("l")
//mid = mid[0];
// 2017-02-09 2.所有會員端- 足球，棒球、其他-內層計分板-當只有在控端的滾球勾拿掉時 計分板才能不秀比分其餘的狀況都要秀出比分 、手機所有球類同pc 規則顯示(CRM-195
var OuterOpen = false;
// 2017-03-10 (263)舊會員端-棒球-all bets- 當賽事關盤不要顯示加至我的盤口的畫面 (所有內層都有此問題)
var gameOpen = "";

function init(){
	document.getElementById("LoadLayer").style.display="";
	document.getElementById("LoadLayer").style.visibility = "visible";
	parent.document.getElementById('all_more_window').style.display = "";
//	sleep(1000);
	show_gid = _REQUEST['gid'];
	open_movieF();
	FavRevMap = make_FavRevMap();
	//reloadGameData();
	// 2017-02-09 2.所有會員端- 足球，棒球、其他-內層計分板-當只有在控端的滾球勾拿掉時 計分板才能不秀比分其餘的狀況都要秀出比分 、手機所有球類同pc 規則顯示(CRM-195
	reloadGameData(true);
	retime_run = retime;
	if(retime > 0){
		retime_flag='Y';
	}
	else{
		retime_flag='N';
	}
	if (retime_flag == 'Y'){
		count_down();
	}else{
		var rt=document.getElementById('refreshTime');
		rt.innerHTML=top.refreshTime;
	}
	btnClickEvent("BackToTop");
}


//reload game data
function reloadGameData(open){
	// 2017-02-09 2.所有會員端- 足球，棒球、其他-內層計分板-當只有在控端的滾球勾拿掉時 計分板才能不秀比分其餘的狀況都要秀出比分 、手機所有球類同pc 規則顯示(CRM-195
	OuterOpen = (!open ? false:true);
	retime_run = retime;
	var getHTML = new HttpRequestXML();
	getHTML.addEventListener("LoadComplete", reloadGameDataComplete);
  getHTML.loadURL("/app/member/get_game_allbets.php","POST", getUrlParam());

}

function getUrlParam(){

		var param = "";
		param="uid="+_REQUEST['uid'];
		param+="&langx="+_REQUEST['langx'];
		param+="&gtype="+_REQUEST['gtype'];
		param+="&showtype="+_REQUEST['showtype'];
		//param+="&testMode="+"1";
		//param+="&gtype="+"FT";
		param+="&gid="+_REQUEST['gid'];
		param+="&ltype="+_REQUEST['ltype'];
		param+="&date="+_REQUEST['date'];

		return param;

}

function getNodeVal(Node){
		return Node.childNodes[0].nodeValue;
}


function reloadGameDataComplete(objData){
				ObjDataFT=objData.tmp_Obj;
				gid_ary = objData.gid_ary;
			    if(!ObjDataFT){ // underfind
			        show_close_info('N') ;
			        document.getElementById("LoadLayer").style.visibility = "hidden";
			        // parent.refreshReload();
			        return;
			    }
			
				// 2017-03-10 (263)舊會員端-棒球-all bets- 當賽事關盤不要顯示加至我的盤口的畫面 (所有內層都有此問題)
				if(ObjDataFT == ""){
			        closeClickEvent();
			        return;
					if(old_ObjDataFT==""){
						closeClickEvent();
						// parent.refreshReload();
						return;
					}
					ObjDataFT = old_ObjDataFT;
				}else{
					old_ObjDataFT = ObjDataFT;
				}
				
				// 2017-03-10 (263)舊會員端-棒球-all bets- 當賽事關盤不要顯示加至我的盤口的畫面 (所有內層都有此問題)
				gameOpen = ObjDataFT[show_gid]["gopen"];

				show_gameInfo(show_gid,ObjDataFT);
				TV_title();
				var div_model = document.getElementById('div_model');
				//wtype
				for(var j=0; j<div_model.children.length; j++){
						var tab_model = div_model.children[j].cloneNode(true);
						if(tab_model.nodeName =="TABLE"&&tab_model.id.indexOf("model")!=-1){
								var wtype = tab_model.id.split("_")[1];
								//ms
								for(var ms_num=0;ms_num<=6;ms_num++){
									if(PD_Regex.test(wtype) && ms_num!=0)continue;
									document.getElementById('body_'+wtype+ms_num).innerHTML ="";
									var tmpDiv = document.createElement("div");
									tmpDiv.appendChild(tab_model);
									var tpl = new fastTemplate();
									tpl.init(tmpDiv);
									var tr_color = 0;
									tmpScreen ="";
									//gid
									tmp_gid=show_gid;
									for(var k=0; k<gid_ary.length; k++){
										var gid = gid_ary[k];
										var ior_arr = getIor(ObjDataFT[gid],wtype,ms_num);
										if(ior_arr=="nodata") continue;
										tmp_gid=gid;
										tpl.addBlock(wtype);
										tr_color++;
										tpl = alayer(tpl,ObjDataFT[gid],wtype,ior_arr,tr_color,ms_num);
									}

									team_h = getTeamName(ObjDataFT[tmp_gid]["team_h"],ms_num);
									team_c = getTeamName(ObjDataFT[tmp_gid]["team_c"],ms_num);
									tmpScreen = tpl.fastPrint();
									tmpScreen = tmpScreen.replace(/\*MS_STR\*/g, (ms_num!=0)?" - "+top.str_BK_MS[ms_num]:"");
									tmpScreen = tmpScreen.replace(/\*MS\*/g, ms_num);
									tmpScreen = tmpScreen.replace(/\*TEAM_H\*/g, team_h);
									tmpScreen = tmpScreen.replace(/\*TEAM_C\*/g, team_c);
									tmpScreen = tmpScreen.replace(/\*TITLE_TEAM_H\*/g, "title='"+team_h+"'");//IE tag裡是大寫
									tmpScreen = tmpScreen.replace(/\*TITLE_TEAM_C\*/g, "title='"+team_c+"'");
									tmpScreen = tmpScreen.replace(/\*title_team_h\*/g, "title='"+team_h+"'");//chrome..tag小寫
									tmpScreen = tmpScreen.replace(/\*title_team_c\*/g, "title='"+team_c+"'");
									document.getElementById('body_'+wtype+ms_num).innerHTML += tmpScreen;
									document.getElementById('body_'+wtype+ms_num).style.display = "";
								}
						}
				}
				document.getElementById("LoadLayer").style.display="none";
				document.getElementById("LoadLayer").style.visibility = "hidden";
			//最愛
				/*
				var tmp_arr = new (top.Array)();
				tmp_arr = top.more_fave_wtype;
				top.more_fave_wtype = new (top.Array)();
				for(var i=0; i< tmp_arr.length ; i++){
					if(FavMap[tmp_arr[i]]==undefined ){
						if(!in_array(tmp_arr[i],top.more_fave_wtype)){
							top.more_fave_wtype.push(tmp_arr[i]);
						}
						continue;
					}
					addFavorites(tmp_arr[i],"fromTop");
				}
				*/
		
	fix_body_wtype();
    show_close_info(ObjDataFT[show_gid]["gopen"]);
	//模式處理
	//fixMoreWindow();
}



//liveTV
function liveTVClickEvent(){
	var eventid = ObjDataFT[show_gid]["eventid"];
	if (eventid != "" && eventid != "null" && eventid != "undefined") {	//判斷是否有轉播
			parent.OpenLive(eventid,"FT");

	}
}

function liveTVClickEvent(){
	//alert("TV click");
	if (TV_eventid != "" && TV_eventid != "null" && TV_eventid != undefined) {	//判斷是否有轉播
			parent.OpenLive(TV_eventid,"FT");
	}
}
//refresh
function reFreshClickEvent(){
	//alert("refresh");
	//reloadGameData();
	// 2017-02-09 2.所有會員端- 足球，棒球、其他-內層計分板-當只有在控端的滾球勾拿掉時 計分板才能不秀比分其餘的狀況都要秀出比分 、手機所有球類同pc 規則顯示(CRM-195
	reloadGameData(OuterOpen);
}
//倒數自動更新時間
function count_down(){
	var rt=document.getElementById('refreshTime');
	setTimeout('count_down()',1000);
	if (retime_flag == 'Y'){
		if(retime_run <= 0){
				btnClickEvent("Refresh")
				return;
		}
		retime_run--;
		rt.innerHTML=retime_run;
	}
}


//close

function closeClickEvent(){
		//alert("close");
		parent.show_more_gid='';
		parent.document.getElementById('all_more_window').style.display='none';
		parent.body_browse.document.getElementById('MBK').className="bodyset BKRE";
		parent.body_browse.document.getElementById('box').style.display="";
		parent.body_browse.document.getElementById('right_div').style.display="";
		parent.body_browse.document.getElementById('refresh_down').style.display="";
		parent.body_browse.scrollTo(0,top.browse_ScrollY);
		retime_flag ="N";
    parent.parent.retime_flag = "Y";
}


//buttons
function btnClickEvent(eventName){
	//alert(eventName);
	if(eventName == "BackToTop" ){
		//parent.body_browse.scrollTo(0,0)
		document.getElementById("more_div").scrollTop = "0";
	}
	if(eventName == "Close" ) closeClickEvent();
	if(eventName == "Refresh" ) reloadGameData();
}


//playCssEvent
function playCssEvent(objName){
		//alert(objName);
		var obj = document.getElementById('movie_'+objName);
		if(obj.style.display==""){
			obj.style.display="none";
			open_movie[objName]=false;
		} else{
			obj.style.display="";
			open_movie[objName]=true;
		}

    if(ObjDataFT[gid_ary[0]] ==[] || ObjDataFT[gid_ary[0]] == '' || gameOpen=='' || gameOpen=='N'){ // 关盘不展示主要盘口
        show_close_info('N') ;
        obj.style.display="none";
    }

		setMark(objName);
}

//set mark
function setMark(_name){

		var obj = document.getElementById('movie_'+_name);
		var showObj = document.getElementById('mark_'+_name);
		//fixMoreWindow()
		if(obj.style.display==""){
				showObj.className = "more_up"; //open
		}else{
				showObj.className = "more_out"; //open
		}
}


//set all mark
function setAllMark(){
		for(var i=0; i<obj_ary.length; i++){
				setMark(obj_ary[i]);
		}
}
var titleDiv_Info,titleDiv_Halves;
function show_gameInfo(gid,ObjData){
	/*var gdatetime = ObjData[gid]["datetime"];

	var dtime = gdatetime.split(" ");
	var date = dtime[0].split("-");
	var time = dtime[1].split(":");
	tmp_repl = tmp_repl.replace('*DATE*',date[1]+"/"+date[2]);
	tmp_repl = tmp_repl.replace('*TIME*',time[0]+":"+time[1]);
	*/
	var gameHalves = document.getElementById("gameHalves");
	var gameInfo = document.getElementById("gameInfo");
	//20141215 add by bill 改兩種樣式的顯示
	var se_type = ObjData[gid]["se_type"]; //分Quarters 和 Halves

	if(titleDiv_Info == undefined){
		titleDiv_Info = document.createElement("div");
		titleDiv_Halves = document.createElement("div");
		titleDiv_Info.appendChild(gameInfo.cloneNode(true));
		titleDiv_Halves.appendChild(gameHalves.cloneNode(true));
	}

	var league_name = ObjData[gid]["league"];
	var team_name_h = ObjData[gid]["team_h"];
	var team_name_c = ObjData[gid]["team_c"];
	var midfield = ObjData[gid]["midfield"];
	var se_now = ObjData[gid]["se_now"];
	var HalfTime = ObjData[gid]["HalfTime"];

	if(se_now=="HT")se_now="H1";
	var t_count = ObjData[gid]["t_count"];
	if(isNaN(t_count)||t_count<0)t_count=0;
	var TimeM= Math.floor(t_count/60);
	var TimeS= t_count%60;
	if(TimeM<10)TimeM="0"+TimeM;
	if(TimeS<10)TimeS="0"+TimeS;
	t_count = TimeM+":"+TimeS

	//if(se_now=="H1"||se_now=="H2") t_count ="";

	if (se_type == "Halves") {
		var sc_data = new Array("FT","H1","H2","OT");
	}else{
		var sc_data = new Array("FT","H1","Q1","Q2","H2","Q3","Q4","OT");
	}
	var tmpDiv = "";
	if (se_type == "Halves") {
		tmpDiv = titleDiv_Halves.cloneNode(true);
	}else{
		tmpDiv = titleDiv_Info.cloneNode(true);
	}
	// 2017-01-25 所有會員端- 足球，棒球、其他-內層計分板-當只有在控端的滾球勾拿掉時 計分板才能不秀比分其餘的狀況都要秀出比分 、手機所有球類同pc 規則顯示(CRM-195)
	if( ObjData[gid]["Live"] == 'Y' ){
		var tmp_repl = tmpDiv.innerHTML;
		var repl_null = false;
		var temp_H2 = 0 ;
		var temp_A2 = 0 ;
		var HalfTime = ObjData[gid]["HalfTime"];
		if (HalfTime==""||HalfTime==null||HalfTime=="undefined")HalfTime ="N" ;
		for(key in sc_data){
			SC_H = "sc_"+sc_data[key]+"_H";
			SC_A = "sc_"+sc_data[key]+"_A";
			if(repl_null){
				tmp_repl = tmp_repl.replace(new RegExp('\\*'+SC_H.toUpperCase()+'\\*'),"");
				tmp_repl = tmp_repl.replace(new RegExp('\\*'+SC_A.toUpperCase()+'\\*'),"");
			// }else if (sc_data[key]=="H2"){
			// 	temp_H2 =  parseFloat(ObjData[gid]["sc_H2_H"]) ;// + parseFloat(ObjData[gid]["sc_OT_H"]);
			// 	tmp_repl = tmp_repl.replace(new RegExp('\\*'+SC_H.toUpperCase()+'\\*'),createSpanClass(temp_H2,"more_yellow"));
			// 	temp_A2 = parseFloat(ObjData[gid]["sc_H2_A"]);//+ parseFloat(ObjData[gid]["sc_OT_A"]);
			// 	tmp_repl = tmp_repl.replace(new RegExp('\\*'+SC_A.toUpperCase()+'\\*'),createSpanClass(temp_A2,"more_yellow"));
			}else if(sc_data[key]=="H1" && (se_now=="Q1" || se_now =="Q2") ){
				if(HalfTime=="N"){
					tmp_repl = tmp_repl.replace(new RegExp('\\*'+SC_H.toUpperCase()+'\\*'),createSpanClass(ObjData[gid][SC_H],"more_yellow"));
					tmp_repl = tmp_repl.replace(new RegExp('\\*'+SC_A.toUpperCase()+'\\*'),createSpanClass(ObjData[gid][SC_A],"more_yellow"));
				}else{
					if( parseFloat(ObjData[gid][SC_H])> parseFloat(ObjData[gid][SC_A])){
						tmp_repl = tmp_repl.replace(new RegExp('\\*'+SC_H.toUpperCase()+'\\*'),createSpanClass(ObjData[gid][SC_H],"bold") );
						tmp_repl = tmp_repl.replace(new RegExp('\\*'+SC_A.toUpperCase()+'\\*'),ObjData[gid][SC_A]);
					}else{
						tmp_repl = tmp_repl.replace(new RegExp('\\*'+SC_H.toUpperCase()+'\\*'),ObjData[gid][SC_H]);
						tmp_repl = tmp_repl.replace(new RegExp('\\*'+SC_A.toUpperCase()+'\\*'),createSpanClass(ObjData[gid][SC_A],"bold"));
					}
				}

			}else if( sc_data[key]=="H2" && (se_now=="Q3" || se_now =="Q4"||se_now =="OT") ){
				if(HalfTime=="N"){
					tmp_repl = tmp_repl.replace(new RegExp('\\*'+SC_H.toUpperCase()+'\\*'),createSpanClass(ObjData[gid][SC_H],"more_yellow"));
					tmp_repl = tmp_repl.replace(new RegExp('\\*'+SC_A.toUpperCase()+'\\*'),createSpanClass(ObjData[gid][SC_A],"more_yellow"));
				}else{
					if( parseFloat(ObjData[gid][SC_H])> parseFloat(ObjData[gid][SC_A])){
						tmp_repl = tmp_repl.replace(new RegExp('\\*'+SC_H.toUpperCase()+'\\*'),createSpanClass(ObjData[gid][SC_H],"bold") );
						tmp_repl = tmp_repl.replace(new RegExp('\\*'+SC_A.toUpperCase()+'\\*'),ObjData[gid][SC_A]);
					}else{
						tmp_repl = tmp_repl.replace(new RegExp('\\*'+SC_H.toUpperCase()+'\\*'),ObjData[gid][SC_H]);
						tmp_repl = tmp_repl.replace(new RegExp('\\*'+SC_A.toUpperCase()+'\\*'),createSpanClass(ObjData[gid][SC_A],"bold"));
					}
				}

			}else if(se_now!=sc_data[key]){
				// tmp_repl = tmp_repl.replace(new RegExp('\\*'+SC_H.toUpperCase()+'\\*'),ObjData[gid][SC_H]);
				// tmp_repl = tmp_repl.replace(new RegExp('\\*'+SC_A.toUpperCase()+'\\*'),ObjData[gid][SC_A]);
				//20150105 bill 加入換結時比大小
				if( parseFloat(ObjData[gid][SC_H])> parseFloat(ObjData[gid][SC_A])){
					tmp_repl = tmp_repl.replace(new RegExp('\\*'+SC_H.toUpperCase()+'\\*'),createSpanClass(ObjData[gid][SC_H],"bold") );
					tmp_repl = tmp_repl.replace(new RegExp('\\*'+SC_A.toUpperCase()+'\\*'),ObjData[gid][SC_A]);
				}else{
					tmp_repl = tmp_repl.replace(new RegExp('\\*'+SC_H.toUpperCase()+'\\*'),ObjData[gid][SC_H]);
					tmp_repl = tmp_repl.replace(new RegExp('\\*'+SC_A.toUpperCase()+'\\*'),createSpanClass(ObjData[gid][SC_A],"bold"));
				}
				if(se_now=="H2" && sc_data[key]=="Q4")repl_null = true;
			}else{
				if(HalfTime=="N"){
					tmp_repl = tmp_repl.replace(new RegExp('\\*'+SC_H.toUpperCase()+'\\*'),createSpanClass(ObjData[gid][SC_H],"bold  more_yellow"));
					tmp_repl = tmp_repl.replace(new RegExp('\\*'+SC_A.toUpperCase()+'\\*'),createSpanClass(ObjData[gid][SC_A],"bold  more_yellow"));
				}else{
					tmp_repl = tmp_repl.replace(new RegExp('\\*'+SC_H.toUpperCase()+'\\*'),ObjData[gid][SC_H]);
					tmp_repl = tmp_repl.replace(new RegExp('\\*'+SC_A.toUpperCase()+'\\*'),ObjData[gid][SC_A]);
				}
				repl_null = true;
			}
		}

		if(se_now=="OT")str_se_now=top.str_BK_OT;
		else if(se_now.charAt(0)=="H")str_se_now=top.str_BK_MS[se_now.charAt(1)*1];
		else {
			tmp_ms = se_now.charAt(1)*1+2;
			str_se_now = top.str_BK_MS[tmp_ms];
		}
		if(HalfTime=="Y"){
			str_se_now = top.statu["HT"];
			t_count = "";
		}
		tmp_repl = tmp_repl.replace('*SE_NOW*',str_se_now);
		tmp_repl = tmp_repl.replace('*T_COUNT*',t_count);
		tmp_repl = tmp_repl.replace('*TEAM_H*',team_name_h);
		tmp_repl = tmp_repl.replace('*TEAM_C*',team_name_c);
		tmp_repl = tmp_repl.replace('*TEAM_C*',team_name_c);
		tmp_repl = tmp_repl.replace('*mid_display*',(midfield=="Y")?"":"style='display:none'");
		
		// 2017-02-09  2.所有會員端- 足球，棒球、其他-內層計分板-當只有在控端的滾球勾拿掉時 計分板才能不秀比分其餘的狀況都要秀出比分 、手機所有球類同pc 規則顯示(CRM-195
		OuterOpen = false;
	// 2017-02-09  2.所有會員端- 足球，棒球、其他-內層計分板-當只有在控端的滾球勾拿掉時 計分板才能不秀比分其餘的狀況都要秀出比分 、手機所有球類同pc 規則顯示(CRM-195	
	}else if( ObjDataFT[gid]['Live'] == 'N' && OuterOpen){
		var tmp_repl = tmpDiv.innerHTML;
		var sc_data = new Array("FT","H1","Q1","Q2","H2","Q3","Q4","OT");
		for(key in sc_data){
			SC_H = "sc_"+sc_data[key]+"_H";
			SC_A = "sc_"+sc_data[key]+"_A";
			tmp_repl = tmp_repl.replace(new RegExp('\\*'+SC_H.toUpperCase()+'\\*'),"");
			tmp_repl = tmp_repl.replace(new RegExp('\\*'+SC_A.toUpperCase()+'\\*'),"");
		}
		if(se_now=="OT")str_se_now=top.str_BK_OT;
		else if(se_now.charAt(0)=="H")str_se_now=top.str_BK_MS[se_now.charAt(1)*1];
		else {
			tmp_ms = se_now.charAt(1)*1+2;
			str_se_now = top.str_BK_MS[tmp_ms];
		}
		if(HalfTime=="Y"){
			str_se_now = top.statu["HT"];
			t_count = "";
		}
		tmp_repl = tmp_repl.replace('*SE_NOW*',"");
		tmp_repl = tmp_repl.replace('*T_COUNT*',"");
		tmp_repl = tmp_repl.replace('*TEAM_H*',team_name_h);
		tmp_repl = tmp_repl.replace('*TEAM_C*',team_name_c);
		tmp_repl = tmp_repl.replace(/\*mid_display\*/gi,"style='display:none'");
	}
	tmpDiv.innerHTML = tmp_repl.replace('*MID_DISPLAY*',(midfield=="Y")?"":"style='display:none'");
	document.getElementById("title_league").innerHTML = league_name;
	if (se_type == "Halves") {
		gameHalves.parentNode.replaceChild(tmpDiv.children[0],gameHalves);
	}else{
		gameInfo.parentNode.replaceChild(tmpDiv.children[0],gameInfo);
	}

	if (se_type == "Halves") {
		//Halves的處理
		document.getElementById("gameInfo").style.display = "none";
		document.getElementById("gameHalves").style.display = "";
	}else{
		//Quarters的處理
		document.getElementById("gameInfo").style.display = "";
		document.getElementById("gameHalves").style.display = "none";
	}
}

function setRefreshPos(){
		var refresh_right= body_browse.document.getElementById('refresh_right');
		refresh_right.style.left= body_browse.document.getElementById('myTable').clientWidth*1+20;
		//refresh_right.style.top= 39;
}

function addFavorites(fav_wtype,kind){
	kind = kind||"";
	if(kind=="")fav_wtype = FavRevMap[fav_wtype];
	for(var k=0;k<FavMap[fav_wtype].length;k++){
		try{
			wtype_str = FavMap[fav_wtype][k]
			var fave_cont;
			var favorites_ = document.getElementById("favorites_"+wtype_str);
			var body_ = document.getElementById("body_"+wtype_str);
			var cont_myMarket = document.getElementById("count_myMarkets");
			var movie_myMarkets = document.getElementById("movie_myMarkets");
			var tmp_repl = body_.innerHTML;
			tmp_repl = tmp_repl.replace("model_","f_table_");
			tmp_repl = tmp_repl.replace("addFavorites","delFavorites");
			tmp_repl = tmp_repl.replace("star_down","star_up");
			favorites_.innerHTML = tmp_repl;
			favorites_.style.display="";
			body_.innerHTML = "";
			body_.style.display="none";
		}catch(e){;}
	}
	if(!in_array(fav_wtype,top.more_fave_wtype))top.more_fave_wtype.push(fav_wtype);
	top.CM.set("more_fave_wtype@"+mid,top.more_fave_wtype);
	fave_cont = count_wtype("myMarkets","ALL_Markets");
	cont_myMarket.innerHTML = fave_cont;
	//if(fave_cont!=0){
		//document.getElementById("movie_myMarkets_nodata").style.display="none";
	//}
	// 2017-03-10 (263)舊會員端-棒球-all bets- 當賽事關盤不要顯示加至我的盤口的畫面 (所有內層都有此問題)
	var myMarkets = document.getElementById("movie_myMarkets_nodata");
	myMarkets.style.display = (fave_cont!=0 || gameOpen!='Y')? "none":"";

	if(movie_myMarkets.style.display =="none" ) playCssEvent('myMarkets');
	fix_body_wtype();
}
function delFavorites(fav_wtype){
	fav_wtype = FavRevMap[fav_wtype];
	for(var k=0;k<FavMap[fav_wtype].length;k++){
		try{
			wtype_str = FavMap[fav_wtype][k]
			var tmp_arr = new (top.Array)();
			var tmp_wtype ;
			var fave_cont;
			var favorites_ = document.getElementById("favorites_"+wtype_str);
			var body_ = document.getElementById("body_"+wtype_str);
			var cont_myMarket = document.getElementById("count_myMarkets");
			var tmp_repl = favorites_.innerHTML;
			tmp_repl = tmp_repl.replace("f_table_","model_");
			tmp_repl = tmp_repl.replace("delFavorites","addFavorites");
			tmp_repl = tmp_repl.replace("star_up","star_down");
			body_.innerHTML = tmp_repl ;
			favorites_.innerHTML = "";
			favorites_.style.display="none";
			if( mod== "ALL_Markets" || modeMap[wtype_str][mod]){
				body_.style.display ="";
			}
			else{
				body_.style.display ="none";
			}
		}catch(e){;}
	}
	for(var i=0, a=0;i < top.more_fave_wtype.length ; i++){
		tmp_wtype = top.more_fave_wtype[i]
		if(fav_wtype != tmp_wtype) tmp_arr[a++] = tmp_wtype ;
	}
	top.more_fave_wtype = tmp_arr;
	if(top.more_fave_wtype.length == 0 ) top.more_fave_wtype.push("fave_wtype");
	top.CM.set("more_fave_wtype@"+mid,top.more_fave_wtype);
	fave_cont = count_wtype("myMarkets","ALL_Markets");
	cont_myMarket.innerHTML = fave_cont;
	if(fave_cont == 0)document.getElementById("movie_myMarkets_nodata").style.display="";
	fix_body_wtype();
}




function betEvent(gid,rtype,ratio,wtype){
	//alert(gid+rtype+ratio+wtype);

	if(ratio*1==0)return;
    // if( gid != _REQUEST['gid']){
    //     gid = _REQUEST['gid'];
    // }
	if(wtype!='NFS'){
		 parent.parent.parent.mem_order.betOrder('BK',wtype,getParam(gid,wtype,rtype,ratio),gid);
	}else{
		var param = 'gametype=FT&gid='+gid+'&uid='+top.uid+'&rtype='+rtype+'&wtype=FS'+'&langx='+top.langx;
		 parent.parent.parent.mem_order.betOrder('FT',wtype,param);
	}
	if(rtype == "0~1" || rtype == "2~3" || rtype == "4~6" ){
		var index = rtype.substr(0,1) *1 /2 ;
		rtype = rtypeMap[wtype][index];
	}
	if(EO_Regex.test(wtype)){
		if(rtype.indexOf('ODD') != -1){
			rtype = wtype+"O";
		}else{
			rtype = wtype+"E";
		}
	}

	if(top.more_bgYalloW != ""){
		try{
		tar = document.getElementById(top.more_bgYalloW);
		setObjectClass(tar,"bg_white");
		}catch(e){}
	}
	top.more_bgYalloW = rtype+"_"+gid;
	tar = document.getElementById(top.more_bgYalloW);
	setObjectClass(tar,"bg_yellow");

}
function canclebet(){
	//alert("canclebet=="+top.more_bgYalloW);
	if(top.more_bgYalloW != ""){
                try{
                tar = document.getElementById(top.more_bgYalloW);
                setObjectClass(tar,"bg_white");
                }catch(e){}
        }
	top.more_bgYalloW="";
}

var setObjectClass = function(targetObj,classStr){
    var browserVar = navigator.userAgent.toLowerCase();
    //alert("browser:"+navigator.userAgent.toLowerCase());

    if(browserVar.indexOf("msie") > -1){
            targetObj.className = classStr;
            //targetObj.setAttribute("className", classStr);
    }else{
            targetObj.setAttribute("class", classStr);
    }
    return;
}

function getParam(gid,wtype,rtype,ratio){

	var GameFT = ObjDataFT[gid];

	var strong = GameFT["strong"];


	var type = rtype.substr(rtype.length-1,1).toUpperCase();
	if(wtype.indexOf('OU') != -1 ){
		if(type=='O')type='C';
		if(type=='U')type='H'
	}
	if( wtype=='M'||wtype=='RM' ){
		var new_type = (type=='H')?'H':'C';
	}
	var param = 'gid='+gid+'&uid='+top.uid+'&odd_f_type='+top.odd_f_type+'&langx='+top.langx+'&rtype='+rtype;

	if(wtype=='R'||wtype=='RE' ) {

		param += '&gnum='+GameFT['gnum_'+type.toLowerCase()]+'&strong='+strong+'&type='+type;

	}else if(wtype=='OU' ||wtype=='ROU' ) {

		param += '&gnum='+GameFT['gnum_'+type.toLowerCase()]+'&type='+type;

	}else if(wtype=='M'||wtype=='RM'){

		param += '&gnum='+GameFT['gnum_'+new_type.toLowerCase()]+'&type='+type;

	}else if(wtype=='OUH'|| wtype=='OUC'||wtype=='ROUH'|| wtype=='ROUC'){

		param += '&gnum='+GameFT['gnum_'+type.toLowerCase()]+'&type='+(type =='H'?'U':'O')+'&wtype='+wtype;

	}else if(wtype.indexOf("PD") != -1 || wtype.indexOf("EO") != -1 ) {

		param +='';

	}else param += '&wtype='+wtype;

	return param;
}



function fixMoreWindow(){
	var tab_show = document.getElementById('tab_show');
	var MBK = parent.document.getElementById('MBK');
	parent.document.getElementById('showdata').width = MBK.offsetWidth;
	parent.document.getElementById('showdata').height = get_max(tab_show.offsetHeight,MBK.offsetHeight);

}
function get_max(a,b){
	if(a>b)return a;
	else return b;
}

function open_movieF(){
	var market;
	for(var i=0;i<obj_ary.length ;i++){
		market = obj_ary[i];
		if(open_movie[market] == false) playCssEvent(market);
	}

}

function getRatioName(wtype,rtype){
	var ratio= "ratio";
		if(wtype.indexOf('RE') != -1) {
			ratio = "ratio_"+wtype;
		}else if(wtype=='HROU' || wtype=='ROU'){
			ratio = "ratio_"+wtype+(rtype.substr(rtype.length-1,1)=='C'?'o':'u');
		}else{
			ratio = "ratio_"+rtype;
		}
	return ratio.toLowerCase();
}

function getXML_TagValue(xmlnode,xmlnodeRoot,TagName){
	var ret_value="";
	if(xmlnode.Node(xmlnodeRoot,TagName).childNodes[0] != null && xmlnode.Node(xmlnodeRoot,TagName) != null) {
		ret_value = getNodeVal(xmlnode.Node(xmlnodeRoot,TagName));
	}
	return ret_value;
}


// 顯示賠率處理
function parse_ior(gid,rtype,ior_value,wtype){
	var red_word = true;
	var bgcolor = false

	if(ior_value *1 < 0 )red_word = false;
	if(typeof(gid_rtype_ior[gid+rtype]) != "undefined" && gid_rtype_ior[gid+rtype] != ior_value ){
		bgcolor = true;
	}
	gid_rtype_ior[gid+rtype] = ior_value;
	if(ior_value!=""){
		//ior_value=parent.Mathfloor(ior_value);
		ior_value=Mathfloor(ior_value);
		//ior_value=parent.printf(ior_value,2);
		// 2017-07-28 CRM-230 單盤（without spread）玩法賠率的四捨五入邏輯 (會員端)
		//Ricky 2017-11-15 雙盤不走四捨五入邏輯
		//ior_value=parent.chgForm_Single_ratio(ior_value,rtype,wtype);
		ior_value=chgForm_Single_ratio(ior_value,rtype,wtype);
		ior_value=js_change_rate(opentype,ior_value);
	}
	//if(ior_value*1 == 0 &&  PD_Regex.test(rtype)  )return "-";
	if(ior_value*1 == 0)return "-";

	if(red_word) {
	  ior_value = '<font color=\'#cc0000\'>'+ior_value+'</font>';
	}
	else{
		ior_value = '<font color=\'#1f497d\'>'+ior_value+'</font>';
  }
	if(bgcolor)ior_value = '<font style=\'background-color : yellow\'>'+ior_value+'</font>';
	return ior_value;

}
function undefined2space(val){
	if(val == 'undefined' || typeof(val) == 'undefined')return "";
	else return val;
}

function XML2Array(xmlnode,xmlnodeRoot){
	var tmp_Obj = new Array();
	var gameXML = xmlnode.Node(xmlnodeRoot,"game",false);
	gid_ary = new Array();

	for(var k=0; k<gameXML.length; k++){
		var tmp_game = gameXML[k];
		var gid = getNodeVal(xmlnode.Node(tmp_game,"gid"));
		var TagName = tmp_game.getElementsByTagName("*");
		gid_ary[gid_ary.length] = gid;
		tmp_Obj[gid] = new Array();
		for( var i=0;i<TagName.length;i++){
			try{
				tmp_Obj[gid][TagName[i].nodeName] =  getXML_TagValue(xmlnode,tmp_game,TagName[i].nodeName);
			}
			catch(e){
				//tmp_Obj[gid][TagName[i]] = "";
			}
		}
	}
	return tmp_Obj;
}

function getIor(gdata,wtype,ms_num){
	var gopen = gdata["gopen"];
	var sw = gdata["sw_"+wtype];
	if(gopen == "N") return "nodata" ;
	if(sw == "N") return "nodata" ;
	var ms = gdata["ms"].slice(-1)*1;
	if(ms!= ms_num)return "nodata";
	var map = rtypeMap[wtype];
	var ior = new Object();
	var rtype,ratio_str,type;
	var ior_all_zero = true;
  var strong = gdata['strong'];

	for(var i=0;i<map.length;i++){
		rtype =  map[i];
		if(!isNaN(gdata["ior_"+rtype]) && gdata["ior_"+rtype]*1 != 0) ior_all_zero= false;
		ior[rtype] = new Object();
		ior[rtype]["ior"] = gdata["ior_"+rtype];

		ratio_str = getRatioName(wtype,rtype);

		if(gdata[ratio_str]){
				ior[rtype]["ratio"] = gdata[ratio_str];

				type = rtype.substr(rtype.length-1,1);
				if(R_Regex.test(wtype) ){
						if(type != strong  || type=="N"){
							ior[rtype]["ratio"] = "";
						}
		  	}
	  }
	}
	if(ior_all_zero)return "nodata";
	if( R_Regex.test(wtype) || OU_Regex.test(wtype) || wtype == 'REO'|| wtype == 'EO'){
			var arry = new Array();
			if(wtype == 'REO' ) {
					arry[0] = (ior[map[0]]["ior"]*1000 - 1000) / 1000;
					arry[1] = (ior[map[1]]["ior"]*1000 - 1000) / 1000;
					arry = get_other_ioratio("H",arry[0],arry[1],show_ior);
					arry[0] =(arry[0]*1000 + 1000) / 1000;
					arry[1] =(arry[1]*1000 + 1000) / 1000;
			}else{
				 arry[0] = ior[map[0]]["ior"]*1;
				 arry[1] = ior[map[1]]["ior"]*1;
				// arry = get_other_ioratio(top.odd_f_type,arry[0],arry[1],show_ior); // show_ior 固定为100 ，这个函数会造成球队得分大小赔率转换，后端也需要这样处理(2018/09/23 改成后端统一处理)
			}
			ior[map[0]]["ior"] = arry[0];
			ior[map[1]]["ior"] = arry[1];
	}
	return ior;
}

// i=0 MyMarket 以外 cnt 等於0收head
function fix_body_wtype(){
	var cnt;
	for(var i=0;i<obj_ary.length;i++){
		var _name = obj_ary[i];
		cnt = count_wtype(_name,"ALL_Markets");
		document.getElementById("count_"+_name).innerHTML = cnt;
		if(i>0){
			if(cnt == 0){
				document.getElementById("head_"+_name).style.display = "none";
			}else{
				document.getElementById("head_"+_name).style.display = "";
			}
		}
	}
}
//
function count_wtype(_name,modName){
	var div_model = document.getElementById('movie_'+_name);
	var cnt = 0
	for(var j=0; j<div_model.children.length; j++){
		var child_model = div_model.children[j];
		if(child_model.nodeName =="DIV"&&( child_model.id.indexOf("body")!=-1 || child_model.id.indexOf("favorites")!=-1)){
			var wtype = child_model.id.split("body_")[1] || child_model.id.split("favorites_")[1] ;
			if(modName == "ALL_Markets" || modeMap[wtype][modName] ){
				if(child_model.innerHTML !="" ) {
					if(wtype.match(/^(ROU|RE|RM|REO)[HC]?[0-6]$/)){
						child_table = child_model.childNodes[0];
						cnt = cnt + child_table.rows.length -1;
					}
					else cnt++;
				}
				else child_model.style.display="none";
			}
		}
	}
	return cnt;
}



function TV_title(){
	var tv_bton = document.getElementById("live_tv");
	try{
		 TV_eventid = undefined2space( ObjDataFT[show_gid]["eventid"] ) ;
	}catch(e){
		tv_bton.style.display="none";
		return;
	}
	if (TV_eventid != "" && TV_eventid != "null" && TV_eventid != undefined) {	//判斷是否有轉播
		tv_bton.title = top.str_TV_RB;
		tv_bton.className = "more_tv_on";
	}
	else {
		tv_bton.style.display="none";
	}
}
function setStarTitle(wtype,TitleText){
	document.getElementById("star_"+wtype).title = TitleText;
}

function show_close_info(gopen){
	var dis_str = "";
	if(gopen=='N')dis_str = "none";
	document.getElementById("gameOver").style.display = (dis_str=="none")?"":"none" ;
	for(i=0;i<obj_ary.length;i++){
		var objName = obj_ary[i];
		var mark = document.getElementById("mark_"+objName);
		document.getElementById("head_"+objName).style.display = dis_str;
		if( gopen == 'Y') {
			document.getElementById("movie_"+objName).style.display = (mark.className == "more_up")?"":"none";
		}else{
			document.getElementById("movie_"+objName).style.display =  "none";
		}
	}
}


function alayer(tpl,adata,wtype,ior_arr,tr_color,ms_num){
	var gid = adata["gid"];
	var sw =adata["sw_"+wtype];
	var strong =adata['strong'];
	//team_h = adata["team_h"].split("-")[0];
	//team_c = adata["team_c"].split("-")[0];
	for(var t=0; t<rtypeMap[wtype].length; t++){
		var rtype = rtypeMap[wtype][t];
		var ior = ior_arr[rtype]["ior"];
		var ratio = ior_arr[rtype]["ratio"];
		//replace
		var IORATIO = "IORATIO_"+rtype;
		var RATIO = "RATIO_"+rtype;
		var td_class = "TD_CLASS_"+rtype;
		var RTYPE_GID = rtype+"_GID";
		//replace
		tpl.replace(new RegExp('\\*'+IORATIO+'\\*'), ior);
		//Ricky 2017-11-15 雙盤不走四捨五入邏輯
		tpl.replace(new RegExp('\\*'+IORATIO+'\\*'), parse_ior(gid,rtype,ior,wtype));
		tpl.replace(/\*GID\*/g, gid);
		tpl.replace("*TR_CLASS*",((t+tr_color)%2!=0)?"more_white":"more_color");
		tpl.replace(new RegExp('\\*'+RTYPE_GID+'\\*'), rtype+"_"+gid);
		tpl.replace(new RegExp('\\*'+RATIO+'\\*','g'), undefined2space(ratio));

		//背景黃色
		if(top.more_bgYalloW == rtype+"_"+gid ) tpl.replace(new RegExp('\\*'+td_class+'\\*'), "bg_yellow");
		else tpl.replace(new RegExp('\\*'+td_class+'\\*'), "bg_white");
	}
	team_h = getTeamName(adata["team_h"],ms_num);
	team_c = getTeamName(adata["team_c"],ms_num);
	tpl.replace(/\*TEAM_H\*/g, team_h);
	tpl.replace(/\*TEAM_C\*/g, team_c);
	tpl.replace(/\*TITLE_TEAM_H\*/g, "title='"+team_h+"'");//IE tag裡是大寫
	tpl.replace(/\*TITLE_TEAM_C\*/g, "title='"+team_c+"'");
	tpl.replace(/\*title_team_h\*/g, "title='"+team_h+"'");//chrome..tag小寫
	tpl.replace(/\*title_team_c\*/g, "title='"+team_c+"'");
	return tpl;
}


function createSpanClass(str,aclass){
	return "<span class='"+aclass+"'>"+str+"</span>";
}

function createFontColor(str,aColor){
	return "<font color='"+aColor+"'>"+str+"</font>";
}


function getTeamName(str,ms){
	if(ms==0)return str;
	var ans = str.replace(team_RegExp,"");
	return ans;
}
function make_FavRevMap(){
	var Map = new Object();
	for(var k in  FavMap ){
		for (var kk in FavMap[k]){
			t_value = FavMap[k][kk];
			t_key = k;
			Map[t_value] = t_key;
		}
	}
	return Map;
}
</script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8 "><script>
function HttpRequestXML(){
		var self=this;
		var req;
		var eventHandler=new Array();
		var parentClass;
		self.init=function(){
				self.addEventListener("LoadComplete",self.cmd_proc);
				//self.removeEventListener("LoadComplete");
				//alert("onload");
				}
		self.help=function(){
				var str="";
				str+="EventName:LoadComplete Method:function(html)\n";
				str+="Method:loadURL(url,post/get,pamam)\n";
				return str;
		}
		
		self.setParentclass=function(parentclass){
				parentClass=parentclass;
				//util=parentClass.util;
		}
		
		self.getThis=function(varible){
				return eval(varible);
		}
	
		
		
		self.loadURL=function(url,method,params) {
				req = false;
		    // branch for native XMLHttpRequest object
		   
		    if(window.XMLHttpRequest && !(window.ActiveXObject)) {
			    	try{
								req = new XMLHttpRequest();
			      }catch(e){
								req = false;
			      }
			    // branch for IE/Windows ActiveX version
		    }else if(window.ActiveXObject){
		       	try{
		        		req = new ActiveXObject("Msxml2.XMLHTTP");
		      	}catch(e){
			        	try{
			          		req = new ActiveXObject("Microsoft.XMLHTTP");
			        	}catch(e){
			          		req = false;
			        	}
						}
		    }
		    
		 
				if(req){
				
						req.onreadystatechange = self.processReqChange;
						if(method==undefined) method="POST";
						if(method.toUpperCase()=="POST"){
								req.open("POST", url, true);
								  //req.setRequestHeader("Content-Type","text/xml;charset=utf8");
								// xmlHttp.setRequestHeader("Content-Type","text/xml;charset="+charset);
								// params = "lorem=ipsum&name=binny";
					  		req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
								//php:·í½s½X¤£¬°utf-8®É­n¥[¦bphp  header('Content-Type:text/html;charset=big5');
								//	req.setRequestHeader("Content-length", params.length);
								//	if (params!="" && params!=undefined)
								req.send(params);
				
						}else{
								req.open("GET", url+"?"+params, true);
								req.send("");
						}
				}
		}
		
		self.processReqChange=function() {
				// only if req shows "loaded"
		 		//   alert(req.status);
		    if(req.readyState == 4){
		        // only if "OK"
		        //alert("req.status="+req.status);
		        if(req.status == 200){
		            // ...processing statements go here...
		            //self.cmd_proc(req.responseText);
		          //	self.eventhandler("LoadComplete",req.responseText);
                    if(req.response){ // ie 取不到 response
                        var gameDataObj=JSON.parse(req.response);
                    }else{
                        var gameDataObj=JSON.parse(req.responseText);
                    }
		           self.eventhandler("LoadComplete",gameDataObj);
		        }else{
		            //alert("There was a problem retrieving the XML data:\n" +req.statusText);
		        }
		    }
		}
		
		self.addEventListener=function(eventname,eventFunction){
				eventHandler[eventname]=eventFunction;
		}
		
		self.removeEventListener=function(eventname){
				EventHandler[eventname]=undefined;
		}
		
		self.eventhandler=function(eventname,param){
				if(eventHandler[eventname]!=undefined){
						eventHandler[eventname](param);
				}
		}
		
		self.cmd_proc=function(html){
				alert(html);
				//return html;
		}
		//self.addEventLisition("LoadComplete",self.cmd_proc);
		self.init();
}

//-------------------------parent start
/*
CRM-230 單盤（without spread）玩法賠率的四捨五入邏輯 (會員端)
*/

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

function chkIsEven(wtype)
{
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


</script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8 "><script>
 var _=new Object();
 var dataClass=new Object();
	var debug=true;
function maxlog(obj,message){

	if (!debug) return;
	if (document.getElementById(obj)==null){
                    document.body.innerHTML=document.body.innerHTML+"<div id="+obj+"></div>";
                }
  	if (message==""){
  			document.getElementById(obj).innerHTML="";
  		}else{
  			document.getElementById(obj).innerHTML=document.getElementById(obj).innerHTML+message;
			}
	}
//��������O����(_)
function removeMem(obj){
	 
	
  	 for(var variable in obj){
  	 	try{
  	 		
  	 		//alert(variable+"===>"+typeof obj[variable]);
  	 		if (typeof obj[variable]=="object"){
  	 			maxlog("removeMC_log","NEXT->"+variable);
  	 			//document.getElementById("rm").innerHTML=document.getElementById("rm").innerHTML+"NEXT->"+variable+"<br>";
  	 			obj[variable].removeMC();
  	 			removeMem(obj[variable]);
  	 				maxlog("removeMC_log","[Next Remove]"+variable);
  	 			obj[variable]=null;
  	 		}else{
  	 				//document.getElementById("rm").innerHTML=document.getElementById("rm").innerHTML+"[Remove]"+variable+"<br>";
  	 		 				maxlog("removeMC_log","[Remove]"+variable);
  	 		 		obj[variable]=null;
  	 			}
  	 		
  	 		}catch(e){
  	 				maxlog("removeMC_log","[Try Remove]"+variable);
  	 			obj[variable]=null;
  	 			}
  	 		}
  	 	}

 function showObj(Obj){
            	//alert(obj)
               maxlog("showobj_log","");
                
                for(var variable in Obj){
                    try{
                         maxlog("showobj_log","name:"+variable+"==== value:"+Obj[variable]);
                    }catch(e){
                    	//alert(e);
                    	}
                }
                
            }
//���Jhtml���(��js)
 function loadHtml(Url,autoClearBody,loadComplete){	
  	urls=Url.split("/");
  	filename=urls[urls.length-1].split(".")[0];
  	urlpath=Url.replace(urls[urls.length-1],"");
  
		url=Url;
		//jsurl=urlpath+"js/"+filename+".js";
		jsurl="/js/"+filename+".js";
		if (autoClearBody!=false) try{ removeMem(_);}catch(e){}
		loaderProc(url,jsurl,autoClearBody,loadComplete);
  		
  }

function loaderProc(url,jsurl,autoClearBody,loadComplete){
		var getHTML=new HttpRequest();
		getHTML.addEventListener("LoadComplete",function(html){
				//alert(html);
			if (autoClearBody!=false) document.body.innerHTML="";
					var tempHtml=new parseHTML(html);
					dbody=tempHtml.getTag("div")[0];
					//dbody=tempHtml.getTag("body")[0];
					//dhead=tempHtml.getTag("head")[0];
					//document.body.innerHTML="";
	
					//CSS
					alink=tempHtml.getTag("link");

					for(i=0;i<alink.length;i++) {
							document.body.appendChild(alink[i]);

						//document.getElementsByTagName("head")[0].appendChild(alink[i]);
					}
					
					 document.body.appendChild(dbody);
					//alert("header=>"+dhead.innterHTML);
					

					/*
					var s=tempHtml.getTag("script")[0];
					if (s!=null){
							//document.getElementsByTagName("head")[0].appendChild(s);
							alert(s.tagName+"==>"+s.innerHTML);
							(document.getElementsByTagName("head")[0] || document.body).appendChild(s.cloneNode(true));
						}
						*/
						
					//document.body.replaceChild(document.body,dbody);
					tempHtml.remove();
					//script
					//alert(jsurl);
					loadscript(jsurl,loadComplete);
				})
	
			getHTML.loadURL(url,"GET","");
	
}

	
//�ѪRxml
function parseXml(xml){
		var tempHtml=new parseHTML(xml);
  		var xml=tempHtml.getChildren();
  		alert("-==>"+xml[0].tagName);
  		var firstNode=xml[0].tagName;
  	
  		//game=xml["server"].node["group"].children["game"].children;
					root=tempHtml.getTag(firstNode);

					
					xmlnode=new xmlNode(root);
					tempHtml.remove();
					return xmlnode;
	
	}


//�ѪRxml Class	
function xmlNodeMax(root){
  				_self=this;
  				_self.Root=root;
  				parentNode=_self.Root[0];
  				_self.getParentNode=function(){
  					return parentNode;
  					}
  				_self.getNode=function(node,auto){
  					retNode=parentNode.getElementsByTagName(node);
  					parentNode=retNode[0];
  					if (auto==false) return retNode[0].childNode;
  					if (retNode.length==1) return retNode[0].childNodes[0];
  					else return retNode[0].childNodes;
  				//	return retNode;
  					}
	  		_self.Node=function(parentNode,node,auto){
	  				if (parentNode.length>1){
	  					alert("DataNode error!!");
	  					return;
	  					}
  					retNode=parentNode.getElementsByTagName(node);
  					if (auto==false) return retNode;
  					if (retNode.length==1) return retNode[0];
  					else return retNode;
  					//return retNode;
  					}
  				_self.removeMC=function(){}
  			}	

//�ѪRxml Class	
function xmlNode(root){
  				_self=this;
  				_self.Root=root;
  				parentNode=_self.Root[0];
  				_self.getParentNode=function(){
  					return parentNode;
  					}
  				_self.getNode=function(node,auto){
  					retNode=parentNode.getElementsByTagName(node);
  					parentNode=retNode[0];
  					if (auto==false) return retNode;
  					if (retNode.length==1) return retNode[0];
  					else return retNode;
  				//	return retNode;
  					}
	  		_self.Node=function(parentNode,node,auto){
	  				if (parentNode.length>1){
	  					alert("DataNode error!!");
	  					return;
	  					}
  					retNode=parentNode.getElementsByTagName(node);
  					if (auto==false) return retNode;
  					if (retNode.length==1) return retNode[0];
  					else return retNode;
  					//return retNode;
  					}
  				_self.removeMC=function(){}
  			}	
//�ѪRhtml Class
function parseHTML(html){
	//alert("parseHTML==>"+html);
	var _self=this;
	var divObj=document.createElement("div");
	//var divObj=document.createDocumentFragment();
	//	var divObj=document.createDocumentFragment();
	//document.body.appendChild(divObj);

	//document.appendChild(divObj);
		
	alert("parseHTML==>"+html);
		
	divObj.innerHTML=html;
	//divObj.innerHTML="<xmp>"+html+"</xmp>"
	//divObj.innerHTML = divObj.innerHTML.replace("<xmp>","").replace("</xmp>","").replace("<XMP>","").replace("</XMP>","");
	
	//divObj.appendChild(divObj1);
	//divObj1=null;
	alert(divObj.innerHTML);

	document.getElementById("test_look").innerHTML ="<xmp>"+divObj.innerHTML+"</xmp>";	
	//alert(divObj.parentNode);
	_self.getTag=function(tagID,divobj){
		
		if (divobj==undefined) divobj=divObj;
		var retobj=new Array();
		for(var i=0;i<divobj.children.length;i++){
		//	alert(divobj.children[i].tagName+"==>"+ divobj.children[i].id);
			if (divobj.children[i].tagName.toUpperCase()==tagID.toUpperCase()){
				retobj.push(divobj.children[i]);
				}
			}
		return retobj;
	
		}
	_self.getChildren=function(){
		return divObj.children;
		}
	//document.body.appChild(divObj);
 	_self.getObj=function(tagID,divobj){
 		if (divobj==undefined) divobj=divObj;
				var obj=null;
				try{
					obj=divobj.children[tagID];
				}catch(e){
					obj=null;
				}
				
				return obj;
      }
    //return _self;
     _self.remove=function(){
	   	//�ۤv��@�����C�@��div�U��������
	   	//��@����@���^�ӵ���
	   	divObj=null;
	   	
   	}
    _self.removeMC=function(){}
 }
 
 //���Jscript
 function loadscript(url,loadComplete){
		
		scriptAry=document.getElementsByTagName("script");
		for(i=scriptAry.length-1;i>=0;i--){
			//if (scriptAry[i].src==url) return;
			scriptAry[i].parentNode.removeChild(scriptAry[i]);
		}
		
	var src=document.createElement("script");	
	(document.getElementsByTagName("head")[0] || document.body).appendChild(src);
	src.id=url;
	src.src=url;
	if(loadComplete!=null) src.onload=loadComplete;
	
}




function removeScript(url){
	//alert(url+"==>"+document.getElementById(url));
	var obj=document.getElementById(url);
	if (obj!=null)
		document.getElementById(url).parentNode.removeChild(document.getElementById(url));
	}
function runJS(js){
	return new Function("return "+js)();
	}
//CSS�ʹ�class	
function cssAni(divObj){
  			var _self=this;
  		 _self.play=function(ms,times){
			 			playtime=ms/1000;                                          
						divObj.style["-webkit-animation-duration"]=playtime+"s";       
						divObj.style["-webkit-animation-iteration-count"]=1; 
					 	_self.finishTimer=setTimeout(_self.finishAni,ms,divObj);  
				}   
			  _self.showXY=function(x,y){
  					divObj.style.top=y;
  					divObj.style.left=x;
  				}
  			_self.finishAni=function(divObj){
  				divObj.style["-webkit-animation-iteration-count"]=0;
  				csstag=_self.getCssTag(divObj.style["-webkit-animation-name"]);
  	  		lastPos=csstag[csstag.length-1].style.cssText.replace(";","").split(":");
					divObj.style[lastPos[0]]=lastPos[1];
  				_self.finish(divObj);
  				}
  		  _self.finish=function(divObj){
  					alert("override finish(divObj)");
  				
  				}
  			//���ocss keyframe size
  			_self.KeyFrameSize=function(){
  				csstag=_self.getCssTag(divObj.style["-webkit-animation-name"]);
  				return csstag.length;
  				}
  				
  			//�]�w��@��css keyframe
        _self.setKeyFrame=function(keyframe,value){
        	csstag=_self.getCssTag(divObj.style["-webkit-animation-name"]);
        	if (keyframe>csstag.length-1) alert("error=>keyframe>size");
        	csstag[keyframe].style.cssText=csstag[0].style[0]+":"+value;
        	}      
        //���ocss�Ҧ�keyframe����                                             
				_self.getCssTag=function(findTagName){	
    			for (var i=0;i<document.styleSheets[0].rules.length;i++){
    			//alert(document.styleSheets[0].rules[i].name);
    				if (document.styleSheets[0].rules[i].name==findTagName){
    					return document.styleSheets[0].rules[i].cssRules;
    			//alert(i)
    					}
    				}
    				
    			}
}</script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8 "><script>
var rtypeMap = new Object();

//走地
rtypeMap["RE"] = ["REH","REC"];
rtypeMap["ROU"] = ["ROUH","ROUC"];
rtypeMap["RM"] = ["RMH","RMC"];
rtypeMap["RPDH"] = ["RPDH0","RPDH1","RPDH2","RPDH3","RPDH4"];
rtypeMap["RPDC"] = ["RPDC0","RPDC1","RPDC2","RPDC3","RPDC4"];
rtypeMap["ROUH"] = ["ROUHO","ROUHU"];
rtypeMap["ROUC"] = ["ROUCO","ROUCU"];
rtypeMap["REO"] = ["REOO","REOE"];

//單式
rtypeMap["R"] = ["RH","RC"];
rtypeMap["OU"] = ["OUH","OUC"];
rtypeMap["M"] = ["MH","MC","MN"];
rtypeMap["PDH"] = ["PDH0","PDH1","PDH2","PDH3","PDH4"];
rtypeMap["PDC"] = ["PDC0","PDC1","PDC2","PDC3","PDC4"];
rtypeMap["OUH"] = ["OUHO","OUHU"];
rtypeMap["OUC"] = ["OUCO","OUCU"];
rtypeMap["EO"] = ["EOO","EOE"];


//過關
rtypeMap["PR"] = ["PRH","PRC"];
rtypeMap["POU"] = ["POUH","POUC"];
rtypeMap["POUH"] = ["POUHO","POUHU"];
rtypeMap["POUC"] = ["POUCO","POUCU"];
rtypeMap["PEO"] = ["PEOO","PEOE"];


var FavMap = new Object();

/*
MS
1.上半
2.下半
3.第一節
4.第二節
5.第三節
6.第四節
*/


FavMap["R"] = ["R0","RE0","PR0"];
FavMap["HR"] = ["R1","RE1","PR1"];
FavMap["OU"] = ["OU0","ROU0","POU0"];
FavMap["HOU"] = ["OU1","ROU1","POU1"];
FavMap["EO"] = ["EO0","REO0","PEO0"];
FavMap["HEO"] = ["EO1","REO1","PEO1"];
FavMap["OTM"] = ["M0","RM0"];
FavMap["OTM1"] = ["M3","RM3"];
FavMap["OTOUH"] = ["OUH0","ROUH0","POUH0"];
FavMap["OTOUC"] = ["OUC0","ROUC0","POUC0"];
FavMap["OTR1"] = ["R3","RE3","PR3"];
FavMap["OTOU1"] = ["OU3","ROU3","POU3"];
FavMap["OTEO1"] = ["EO3","REO3","PEO3"];
FavMap["OTM2"] = ["M4","RM4"];
FavMap["OTR2"] = ["R4","RE4","PR4"];
FavMap["OTOU2"] = ["OU4","ROU4","POU4"];
FavMap["OTEO2"] = ["EO4","REO4","PEO4"];
FavMap["OTM3"] = ["M5","RM5"];
FavMap["OTR3"] = ["R5","RE5","PR5"];
FavMap["OTOU3"] = ["OU5","ROU5","POU5"];
FavMap["OTEO3"] = ["EO5","REO5","PEO5"];
FavMap["OTM4"] = ["M6","RM6"];
FavMap["OTR4"] = ["R6","RE6","PR6"];
FavMap["OTOU4"] = ["OU6","ROU6","POU6"];
FavMap["OTEO4"] = ["EO6","REO6","PEO6"];
FavMap["OTOUH1"] = ["OUH3","ROUH3","POUH3"];
FavMap["OTOUC1"] = ["OUC3","ROUC3","POUC3"];
FavMap["OTOUH2"] = ["OUH4","ROUH4","POUH4"];
FavMap["OTOUC2"] = ["OUC4","ROUC4","POUC4"];
FavMap["OTOUH3"] = ["OUH5","ROUH5","POUH5"];
FavMap["OTOUC3"] = ["OUC5","ROUC5","POUC5"];
FavMap["OTOUH4"] = ["OUH6","ROUH6","POUH6"];
FavMap["OTOUC4"] = ["OUC6","ROUC6","POUC6"];
FavMap["OT2HR"] = ["R2","RE2","PR2"];
FavMap["OT1HM"] = ["M1","RM1"];
FavMap["OT2HM"] = ["M2","RM2"];
FavMap["OT1HOUH"] = ["OUH1","ROUH1","POUH1"];
FavMap["OT1HOUC"] = ["OUC1","ROUC1","POUC1"];
FavMap["PDH"] = ["PDH0","RPDH0"];
FavMap["PDC"] = ["PDC0","RPDC0"];
FavMap["OT2HOU"] = ["OU2","ROU2","POU2"];
FavMap["OT2HOUH"] = ["OUH2","ROUH2","POUH2"];
FavMap["OT2HOUC"] = ["OUC2","ROUC2","POUC2"];
FavMap["OT2HEO"] = ["EO2","REO2","PEO2"];</script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8 "><script>
function fastTemplate(){
		var _self=this;
		var parentClass;
		var Hashtabl=new Array();
		var dataHash=new Array();
		var keyHash=new Array();
		var SampleTable;
		var samplelayer;
		var tempTag;
		_self.init=function(obj){
			SampleTable=obj.innerHTML;
			samplelayer="";
		}
		
		_self.setParentclass=function(parentclass){
			parentClass=parentclass;
		}
		
		_self.getThis=function(varible){
			return eval(varible);
		}
		_self.setPrivate=function(varible,val){
  				eval(varible+"='"+val+"'");
  			}
		_self.addBlock=function(tag){
			var s_srt="<!-- START DYNAMIC BLOCK: "+tag+" -->";
			var e_srt="<!-- END DYNAMIC BLOCK: "+tag+" -->";
			var n_start =SampleTable.indexOf(s_srt,0);
			var n_end   =SampleTable.lastIndexOf(e_srt,SampleTable.length);
			var sampleTag =SampleTable.substring(n_start,n_end);
			
			sampleTag =sampleTag.replace(s_srt,"");
			samplelayer=SampleTable.replace(s_srt+sampleTag+e_srt,"*TAG_"+tag+"*");

			if (dataHash[tag]==undefined){
				dataHash[tag]=new Array();
				keyHash[keyHash.length]=tag;
			}
			tempTag=tag;
			dataHash[tag][dataHash[tag].length]=sampleTag;
		}
			
		_self.replace=function(oldTag,newTag){
				 dataHash[tempTag][dataHash[tempTag].length-1]=dataHash[tempTag][dataHash[tempTag].length-1].replace(oldTag,newTag);
			}
		
		_self.fastPrint=function(){
			var output=samplelayer;
			for (var i=0;i<keyHash.length;i++){
				allLayer="";
				for (var j=0;j<dataHash[keyHash[i]].length;j++){
					allLayer+=dataHash[keyHash[i]][j];
					}
				output=output.replace("*TAG_"+keyHash[i]+"*",allLayer);
				}
				return output;
			}
}
</script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8 "><script>
	
if (onloadQue==null){
	var browse="";
	//注意:此js必須放在最後一個js
	if (document.all){//IE getElementsByName只能用在form改為用document上
			browse="IE";
			document.getElementsByName=function(objName){
		 		var children=new Array();
		 	var obj=document.getElementsByTagName("*");
		 		for (var i=0;i<obj.length;i++){
		 			if (obj[i].name==objName){
		 				children[obj[i].id]=obj[i];
		 				 children.push(obj[i]);
		 				}
		 			}
		 		return children;
		 		}
	 	}

	if (!document.all){
		 document.all=function(){
		return document.getElementsByTagName("*");}();
	}
	function autoAddID(){ //自動把沒有加到id而有name的tag補上id同名為name
	  
		 var all=document.getElementsByTagName("*");
		 for (var i=0;i<all.length;i++){
				 	try{
					 	if (all[i].attributes["name"]!=null && all[i].id=="") all[i].id=all[i].attributes["name"].value;
					}catch(e){}
					//新增textContent
					//try{
						//alert(all[i].innerText);
					 	//if (all[i].innerText!=null && all[i].innerText!="") all[i].textContent=all[i].innerText;
					// if (all[i].textContent==null && all[i].innerText!="" && all[i].innerText!=null) all[i].textContent=all[i].innerText;
					//}catch(e){}
					//新增innertext
					try{
							if (all[i].innerText==null && all[i].textContent!="" && all[i].textContent!=null) all[i].innerText=all[i].textContent;
					}catch(e){}
					try{
						if(all[i].tagName.toUpperCase()=="IFRAME"){
							all[i].location=all[i].contentWindow.location;
							}
					}catch(e){}
			 	}
			 	/*
			 	 var xmp=document.getElementsByTagName("xmp");
			 	 var tmpDoc=document.createElement("div");
			 	 var len=xmp.length;
			 	 for (j=0;j<len;j++){
			 	 	tmpDoc.innerHTML=xmp[j].innerHTML;
			 	 	//alert(xmp[i].innerHTML);
			 	 	 var tall=tmpDoc.getElementsByTagName("*");
			 	 	 for (var i=0;i<tall.length;i++){
			 	 	 	//alert(tall[i].id);
						 	try{
							 	if (tall[i].attributes["name"]!=null && tall[i].id=="") tall[i].id=tall[i].attributes["name"].value;
							}catch(e){}
						
			 	 		}
			 	 		tmpDoc.innerHTML="<xmp>"+tmpDoc.innerHTML+"</xmp>";
			 	 	xmp[j].parentNode.replaceChild(tmpDoc.children[0],xmp[j]);
				
			 	 }
			 	 */
			 	 
		}	
		
	 var onloadQue=new Array();
	 function addonloadQue(func){
	 	onloadQue[onloadQue.length]=func;
	 	}
	 function autoStartLoad(){
	 	for(var i=0;i<onloadQue.length;i++){
						onloadQue[i]();
					}
					onloadQue=new Array();
	 	}
	
	 
	 
document.onreadystatechange = function (e) {
	//alert(document.readyState+"\n"+e.target.body.innerHTML+'ready')
	if (document.readyState=="complete"){
		//alert("-->"+document.body.attributes["ONLOAD"]);
		//alert(document.body.onload.toString());
		//showObj(document.body);
		try{
			if (document.body.onload!=null && document.body.onload!="") addonloadQue(document.body.onload);
		//document.body.removeAttribute("onload",0);
		document.body.onload=function(){};
	  if(window.onload!=null) addonloadQue(window.onload);
		window.onload=autoStartLoad;
	}catch(e){alert(e)}
		}
	//alert(document.readyState+" ready");
	}

	
	 addonloadQue(autoAddID);
	 //if(window.onload!=null) addonloadQue(window.onload);
	 //window.onload=autoStartLoad;
	 
	 function GetEvent(caller){ 
	 	var obj=new Object;
	 	caller=GetEvent.caller;
		if(browse=="IE"){ 
			//event.keycode=event.keyCode;
			var eventobj= window.event; //For IE.
			  for(var variable in eventobj){
			  	try{
			  		obj[variable]=eventobj[variable];
			  	}catch(e){}
			  	}
			  	obj.clientX=window.event.x;
			  	obj.clientY=window.event.y;
			  	obj.returnValue=function (){window.event.returnValue = false;} 
			  	obj.keyCode=window.event.keyCode;
			  	obj.keycode=window.event.keyCode;
			return obj;
			
		} 
		if(caller == null || typeof(caller) != "function") 
		return null; 
		while(caller.caller != null){ 
		caller = caller.caller; 
		} 
		var event=caller.arguments[0];
		
	//	if (event.keyCode==0){
	//		event.keycode=event.which;
	//		}
	
			  for(var variable in event){
			  	try{
			  		obj[variable]=event[variable];
			  	}catch(e){}
			  	}
			 if (event.keyCode==0){
			  	obj.keyCode=event.which;
			 }
			 obj.keycode=obj.keyCode;
			 obj.returnValue=function (){event.preventDefault();}
			 obj.x=event.clientX;
			 obj.y=event.clientY;
			 //alert(event.x+":"+event.y);
			return obj;	
			
		return obj; 
	} 
	 function GetEventTarget(event){ 
		if(browse=="IE") return event.srcElement; 
		return event.target; 
		} 
	}
</script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8 "><script>
</script>




</head>
<body id="MFT" class="BODYMORE" onLoad="init();">

<div id="div_show" class="more_b_bg">
<div id="LoadLayer" class="more_load" style="display:none;"><img src="/images/member/loading_pic.gif" width="38" height="38"></div>
<div class="more_three_btn">
	<!------------------------ right buttons ------------------------>
	<table class="more_right_btn">
			<tr><td><input type="button" class="more_btn_re_cn" value="" onClick="btnClickEvent('Refresh');"></td></tr>
			<tr><td><input type="button" class="more_btn_close_cn" value="" onClick="btnClickEvent('Close');"></td></tr>
			<tr><td><input type="button" class="more_btn_back_cn" value="" onClick="btnClickEvent('BackToTop');"></td></tr>
	</table>
	<!------------------------ right buttons ------------------------>
</div>

<div id="more_div" class="more_over">
<table id="tab_show" border="0" cellspacing="0" cellpadding="0" class="more_table">
 	<tr class="mo_bg_h"><td>
	<table id="" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="more_title">
				<span id="title_league" name="title_league" class="more_long">*LEAGUE_NAME*</span>
				<input type="button" class="more_x" value="" onClick="closeClickEvent();">
				<span  class="more_re" onClick="reFreshClickEvent();"><span id="refreshTime" class="more_re_t">refresh</span></span>
        <input id="live_tv" type="button" class="more_tv_on" value="" onClick="liveTVClickEvent();" >
			</td>
		</tr>
	</table>			
	<table id="gameInfo" width="100%" border="0" cellspacing="0" cellpadding="0" class="more_bg_table_bkr"  style="display:none;">
		<tr class="more_bg1_bk">
			<td class="more_bk_td">
            <span class="mbk_ytitle">*SE_NOW*</span>
			<span class="mbk_white">*T_COUNT*</span>
			<span class="mbk_left"><img src="/images/member/zh-tw/more_n.jpg" class="more_n" *MID_DISPLAY*/></span>
            </td>
			<td class="bk_w">1</td>
			<td class="bk_w2">2</td>
			<td class="bk_w3">3</td>
			<td class="bk_w">4</td>
			<td class="bk_extra">加时</td>
			<td class="bk_ht">上半场</td>
			<td class="bk_ht">下半场</td>
			<td class="bk_total">总计</td>
			<!--td colspan="3"><span id="title_date" name="title_date">*DATE*</span>&nbsp;@&nbsp;<span id="title_time" name="title_time">*TIME*</span></td-->
		</tr>
		<tr class="more_bg2_bk">
			<td class="mbk_title">*TEAM_H*</td>
			<td>*SC_Q1_H*</td>
			<td class="bk_w2">*SC_Q2_H*</td>
			<td class="bk_w3">*SC_Q3_H*</td>
			<td>*SC_Q4_H*</td>
			<td>*SC_OT_H*</td>
			<td class="more_bk_h">*SC_H1_H*</td>
			<td class="more_bk_h">*SC_H2_H*</td>
			<td class="more_bk_h more_yellow_2">*SC_FT_H*</td>
		</tr>
		<tr class="more_bg2_bk">
			<td class="mbk_title">*TEAM_C*</td>
			<td>*SC_Q1_A*</td>
			<td class="bk_w2">*SC_Q2_A*</td>
			<td class="bk_w3">*SC_Q3_A*</td>
			<td>*SC_Q4_A*</td>
			<td>*SC_OT_A*</td>
			<td class="more_bk_h">*SC_H1_A*</td>
			<td class="more_bk_h">*SC_H2_A*</td>
			<td class="more_bk_h more_yellow_2">*SC_FT_A*</td>
		</tr>
	</table>
	<table id="gameHalves" width="100%" border="0" cellspacing="0" cellpadding="0" class="more_bg_table_bkr"  style="display:none;">
		<tr class="more_bg1_bk">
			<td class="more_bk_td">
            <span class="mbk_ytitle">*SE_NOW*</span>
			<span class="mbk_white">*T_COUNT*</span>
			<span class="mbk_left"><img src="/images/member/zh-tw/more_n.jpg" class="more_n" *MID_DISPLAY*/></span>
            </td>
			<td class="bk_ht">上半场</td>
			<td class="bk_ht">下半场</td>
			<td class="bk_extra">加时</td>
			<td class="bk_total">总计</td>
			<!--td colspan="3"><span id="title_date" name="title_date">*DATE*</span>&nbsp;@&nbsp;<span id="title_time" name="title_time">*TIME*</span></td-->
		</tr>
		<tr class="more_bg2_bk">
			<td class="mbk_title">*TEAM_H*</td>
			<td class="more_bk_h">*SC_H1_H*</td>
			<td class="more_bk_h">*SC_H2_H*</td>
			<td>*SC_OT_H*</td>
			<td class="more_bk_h more_yellow_2">*SC_FT_H*</td>
		</tr>
		<tr class="more_bg2_bk">
			<td class="mbk_title">*TEAM_C*</td>
			<td class="more_bk_h">*SC_H1_A*</td>
			<td class="more_bk_h">*SC_H2_A*</td>
			<td>*SC_OT_A*</td>
			<td class="more_bk_h more_yellow_2">*SC_FT_A*</td>
		</tr>
	</table>
	
	<div id="gameOver" style="display:none;">
		<table border="0" cellpadding="0" cellspacing="0" class="">
			<tr class="more_no2"><td>
				此赛事暂时停止收注或已关闭
			</td></tr>
		</table>
	</div>


	<!------------------------ my markets ------------------------>
	<table id="head_myMarkets" width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr onClick="playCssEvent('myMarkets',this);" class="more_hand">
			<td class="more_title2">
					<span id="mark_myMarkets" class="more_up"></span>
					<span style="float: left;">我的盘口</span>
					<span class="more_star"></span>
					<span class="more_black"><span id="count_myMarkets" class="more_num">0</span></span>
			</td>
		</tr>
	</table>	
	<div id="movie_myMarkets">
			<div id="movie_myMarkets_nodata">
				<table border="0" cellpadding="0" cellspacing="0" class="">
					<tr class="more_no"><td>
						点击 ★ 图示添加赛事至我的盘口
					</td></tr>
				</table>
			</div>
			<div id="favorites_RE0"></div>
			<div id="favorites_ROU0"></div>
			<div id="favorites_ROUH0"></div>
			<div id="favorites_ROUC0"></div>
			<div id="favorites_RPDH0"></div>
			<div id="favorites_RPDC0"></div>
			<div id="favorites_RM0"></div>
			<div id="favorites_REO0"></div>
			
			<div id="favorites_RE1"></div>
			<div id="favorites_ROU1"></div>
			<div id="favorites_ROUH1"></div>
			<div id="favorites_ROUC1"></div>
			<div id="favorites_RM1"></div>
			<div id="favorites_REO1"></div>
			
			<div id="favorites_RE2"></div>
			<div id="favorites_ROU2"></div>
			<div id="favorites_ROUH2"></div>
			<div id="favorites_ROUC2"></div>
			<div id="favorites_RM2"></div>
			<div id="favorites_REO2"></div>
			
			<div id="favorites_RE3"></div>
			<div id="favorites_ROU3"></div>
			<div id="favorites_ROUH3"></div>
			<div id="favorites_ROUC3"></div>
			<div id="favorites_RM3"></div>
			<div id="favorites_REO3"></div>
			
			<div id="favorites_RE4"></div>
			<div id="favorites_ROU4"></div>
			<div id="favorites_ROUH4"></div>
			<div id="favorites_ROUC4"></div>
			<div id="favorites_RM4"></div>
			<div id="favorites_REO4"></div>
			
			<div id="favorites_RE5"></div>
			<div id="favorites_ROU5"></div>
			<div id="favorites_ROUH5"></div>
			<div id="favorites_ROUC5"></div>
			<div id="favorites_RM5"></div>
			<div id="favorites_REO5"></div>
			
			<div id="favorites_RE6"></div>
			<div id="favorites_ROU6"></div>
			<div id="favorites_ROUH6"></div>
			<div id="favorites_ROUC6"></div>
			<div id="favorites_RM6"></div>
			<div id="favorites_REO6"></div>
			
	</div>
	<!------------------------ my markets ------------------------>
	
	
	
	
	

	<!------------------------ main markets ------------------------>
	<table id="head_mainMarkets" width="100%" border="0" cellspacing="0" cellpadding="0" class="more_ma">
		<tr onClick="playCssEvent('mainMarkets',this);" class="more_hand">
			<td class="more_title3">
					<span id="mark_mainMarkets" class="more_up"></span>
					<span style="float: left;">主盘口</span>
					<span class="more_black"><span id="count_mainMarkets" class="more_num">0</span></span>
			</td>
		</tr>
	</table>	
	
	
	<div id="movie_mainMarkets">
			<div id="body_RE0"></div>
			<div id="body_ROU0"></div>
			<div id="body_ROUH0"></div>
			<div id="body_ROUC0"></div>
			<div id="body_RPDH0"></div>
			<div id="body_RPDC0"></div>
			<div id="body_RM0"></div>
			<div id="body_REO0"></div>
			
			<div id="body_RE1"></div>
			<div id="body_ROU1"></div>
			<div id="body_ROUH1"></div>
			<div id="body_ROUC1"></div>
			<div id="body_RM1"></div>
			<div id="body_REO1"></div>
			
			<div id="body_RE2"></div>
			<div id="body_ROU2"></div>
			<div id="body_ROUH2"></div>
			<div id="body_ROUC2"></div>
			<div id="body_RM2"></div>
			<div id="body_REO2"></div>
			
			<div id="body_RE3"></div>
			<div id="body_ROU3"></div>
			<div id="body_ROUH3"></div>
			<div id="body_ROUC3"></div>
			<div id="body_RM3"></div>
			<div id="body_REO3"></div>
			
			<div id="body_RE4"></div>
			<div id="body_ROU4"></div>
			<div id="body_ROUH4"></div>
			<div id="body_ROUC4"></div>
			<div id="body_RM4"></div>
			<div id="body_REO4"></div>
			
			<div id="body_RE5"></div>
			<div id="body_ROU5"></div>
			<div id="body_ROUH5"></div>
			<div id="body_ROUC5"></div>
			<div id="body_RM5"></div>
			<div id="body_REO5"></div>
			
			<div id="body_RE6"></div>
			<div id="body_ROU6"></div>
			<div id="body_ROUH6"></div>
			<div id="body_ROUC6"></div>
			<div id="body_RM6"></div>
			<div id="body_REO6"></div>
	</div>

	<!------------------------ main markets ------------------------>
	
	
	</td></tr>
</table>
</div>


<div id="div_model" style="display:none;" >
	
			<!---------- RE ---------->
		 	<table id="model_RE" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th colspan="2" class="more_title4">
							<span style="float: left;">让球&nbsp;</span>
							<span class="more_og2">*MS_STR*</span>
							<span class="more_star_bg"><span id="star_RE*MS*" name="star_RE*MS*" onClick="addFavorites('RE*MS*');" class="star_down" title="加到"我的盘口""></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: RE -->
				<tr class="*TR_CLASS*">
						<td id="*REH_GID*" onClick="betEvent('*GID*','REH','*IORATIO_REH*','RE');" style="cursor:pointer"  class="*TD_CLASS_RH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_middle">*RATIO_REH*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_REH*</span></div></td>
						<td id="*REC_GID*" onClick="betEvent('*GID*','REC','*IORATIO_REC*','RE');" style="cursor:pointer"  class="*TD_CLASS_RC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_middle">*RATIO_REC*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_REC*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: RE -->
	
			</table>
			<!---------- RE ---------->

			<!---------- ROU ---------->
			<table id="model_ROU" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">总分: 大 / 小&nbsp;</span>
							<span class="more_og2">*MS_STR*</span>
							<span class="more_star_bg"><span id="star_OU*MS*" name="star_ROU*MS*" onClick="addFavorites('ROU*MS*');" class="star_down" title="加到"我的盘口""></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: ROU -->
				<tr class="*TR_CLASS*">
						<td id="*ROUC_GID*" onClick="betEvent('*GID*','ROUC','*IORATIO_ROUC*','ROU');" style="cursor:pointer"  class="*TD_CLASS_ROUC*" width="50%"><div class="more_font"><span class="m_team">大</span><span class="m_middle">*RATIO_ROUC*</span><span class="m_red_bet" title="大">*IORATIO_ROUC*</span></div></td>
						<td id="*ROUH_GID*" onClick="betEvent('*GID*','ROUH','*IORATIO_ROUH*','ROU');" style="cursor:pointer"  class="*TD_CLASS_ROUH*" width="50%"><div class="more_font"><span class="m_team">小</span><span class="m_middle">*RATIO_ROUH*</span><span class="m_red_bet" title="小">*IORATIO_ROUH*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: ROU -->
	
			</table>
			<!---------- ROU ---------->
	

			
			<!---------- ROUH ---------->      
		 	<table id="model_ROUH" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">球队得分: </span>
							<span class="more_og4">&nbsp;<span class="more_og6">*TEAM_H*</span> - 大 / 小<span class="more_og5">*MS_STR*</span></span>
							<span class="more_star_bg"><span id="star_ROUH*MS*" name="star_ROUH*MS*" onClick="addFavorites('ROUH*MS*');" class="star_down" title="加到"我的盘口""></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: ROUH -->
				<tr class="*TR_CLASS*">
						<td id="*ROUHO_GID*" onClick="betEvent('*GID*','ROUHO','*IORATIO_ROUHO*','ROUH');" style="cursor:pointer"  class="*TD_CLASS_ROUHO*" width="50%"><div class="more_font"><span class="m_team">大</span><span class="m_middle">*RATIO_ROUHO*</span><span class="m_red_bet" title="大">*IORATIO_ROUHO*</span></div></td>
						<td id="*ROUHU_GID*" onClick="betEvent('*GID*','ROUHU','*IORATIO_ROUHU*','ROUH');" style="cursor:pointer"  class="*TD_CLASS_ROUHU*" width="50%"><div class="more_font"><span class="m_team">小</span><span class="m_middle">*RATIO_ROUHU*</span><span class="m_red_bet" title="小">*IORATIO_ROUHU*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: ROUH -->
	
			</table>
			<!---------- ROUH ---------->
			
			
			
			<!---------- ROUC ---------->   
		 	<table id="model_ROUC" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">球队得分: </span>
							<span class="more_og4">&nbsp;<span class="more_og6">*TEAM_C*</span> - 大 / 小<span class="more_og5">*MS_STR*</span></span>
							<span class="more_star_bg"><span id="star_ROUC*MS*" name="star_ROUC*MS*" onClick="addFavorites('ROUC*MS*');" class="star_down" title="加到"我的盘口""></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: ROUC -->
				<tr class="*TR_CLASS*">
						<td id="*ROUCO_GID*" onClick="betEvent('*GID*','ROUCO','*IORATIO_ROUCO*','ROUC');" style="cursor:pointer"  class="*TD_CLASS_ROUCO*" width="50%"><div class="more_font"><span class="m_team">大</span><span class="m_middle">*RATIO_ROUCO*</span><span class="m_red_bet" title="大">*IORATIO_ROUCO*</span></div></td>
						<td id="*ROUCU_GID*" onClick="betEvent('*GID*','ROUCU','*IORATIO_ROUCU*','ROUC');" style="cursor:pointer"  class="*TD_CLASS_ROUCU*" width="50%"><div class="more_font"><span class="m_team">小</span><span class="m_middle">*RATIO_ROUCU*</span><span class="m_red_bet" title="小">*IORATIO_ROUCU*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: ROUC -->
	
			</table>
			<!---------- ROUC ---------->
			<!---------- RPDH ---------->
		 	<table id="model_RPDH" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="5">
							<span style="float: left;">球队得分:<span class="more_og6"> *TEAM_H*</span> - 最后一位数</span>
							<!--span class="more_og2">*MS_STR*</span-->
							<span class="more_star_bg"><span id="star_RPDH*MS*" name="star_RPDH*MS*" onClick="addFavorites('RPDH*MS*');" class="star_down" title="加到"我的盘口""></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: RPDH -->
				
				<tr class="more_white">
						<td id="*RPDH0_GID*" onClick="betEvent('*GID*','RPDH0','*IORATIO_RPDH0*','RPD');" style="cursor:pointer"  class="*TD_CLASS_RPDH0*" width="20%"><span style="float: left;">0 或 5</span><span class="m_red2" title="0 或 5">*IORATIO_RPDH0*</span></td>
						<td id="*RPDH1_GID*" onClick="betEvent('*GID*','RPDH1','*IORATIO_RPDH1*','RPD');" style="cursor:pointer"  class="*TD_CLASS_RPDH1*" width="20%"><span style="float: left;">1 或 6</span><span class="m_red2" title="1 或 6">*IORATIO_RPDH1*</span></td>
						<td id="*RPDH2_GID*" onClick="betEvent('*GID*','RPDH2','*IORATIO_RPDH2*','RPD');" style="cursor:pointer"  class="*TD_CLASS_RPDH2*" width="20%"><span style="float: left;">2 或 7</span><span class="m_red2" title="2 或 7">*IORATIO_RPDH2*</span></td>
						<td id="*RPDH3_GID*" onClick="betEvent('*GID*','RPDH3','*IORATIO_RPDH3*','RPD');" style="cursor:pointer"  class="*TD_CLASS_RPDH3*" width="20%"><span style="float: left;">3 或 8</span><span class="m_red2" title="3 或 8">*IORATIO_RPDH3*</span></td>
						<td id="*RPDH4_GID*" onClick="betEvent('*GID*','RPDH4','*IORATIO_RPDH4*','RPD');" style="cursor:pointer"  class="*TD_CLASS_RPDH4*" width="20%"><span style="float: left;">4 或 9</span><span class="m_red2" title="4 或 9">*IORATIO_RPDH4*</span></td>
				</tr>                                                                                                       

				
				<!-- END DYNAMIC BLOCK: RPDH -->
	
			</table>
			<!---------- RPDH ---------->
			

			<!---------- RPDC ---------->
		 	<table id="model_RPDC" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="5">
							<span style="float: left;">球队得分:<span class="more_og6"> *TEAM_C*</span> - 最后一位数</span>
							<!--span class="more_og2">*MS_STR*</span-->
							<span class="more_star_bg"><span id="star_RPDC*MS*" name="star_RPDC*MS*" onClick="addFavorites('RPDC*MS*');" class="star_down" title="加到"我的盘口""></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: RPDC -->
				
				<tr class="more_white">
						<td id="*RPDC0_GID*" onClick="betEvent('*GID*','RPDC0','*IORATIO_RPDC0*','RPD');" style="cursor:pointer"  class="*TD_CLASS_RPDC0*" width="20%"><span style="float: left;">0 或 5</span><span class="m_red2" title="0 或 5">*IORATIO_RPDC0*</span></td>
						<td id="*RPDC1_GID*" onClick="betEvent('*GID*','RPDC1','*IORATIO_RPDC1*','RPD');" style="cursor:pointer"  class="*TD_CLASS_RPDC1*" width="20%"><span style="float: left;">1 或 6</span><span class="m_red2" title="1 或 6">*IORATIO_RPDC1*</span></td>
						<td id="*RPDC2_GID*" onClick="betEvent('*GID*','RPDC2','*IORATIO_RPDC2*','RPD');" style="cursor:pointer"  class="*TD_CLASS_RPDC2*" width="20%"><span style="float: left;">2 或 7</span><span class="m_red2" title="2 或 7">*IORATIO_RPDC2*</span></td>
						<td id="*RPDC3_GID*" onClick="betEvent('*GID*','RPDC3','*IORATIO_RPDC3*','RPD');" style="cursor:pointer"  class="*TD_CLASS_RPDC3*" width="20%"><span style="float: left;">3 或 8</span><span class="m_red2" title="3 或 8">*IORATIO_RPDC3*</span></td>
						<td id="*RPDC4_GID*" onClick="betEvent('*GID*','RPDC4','*IORATIO_RPDC4*','RPD');" style="cursor:pointer"  class="*TD_CLASS_RPDC4*" width="20%"><span style="float: left;">4 或 9</span><span class="m_red2" title="4 或 9">*IORATIO_RPDC4*</span></td>
				</tr>                                                                                                       

				
				<!-- END DYNAMIC BLOCK: RPDC -->
	
			</table>
			<!---------- RPDC ---------->
			<!---------- RM ---------->
			<table id="model_RM" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="3">
							<span style="float: left;">独赢&nbsp;</span>
							<span class="more_og2">*MS_STR*</span>
							<span class="more_star_bg"><span id="star_RM*MS*" name="star_RM*MS*" onClick="addFavorites('RM*MS*');" class="star_down" title="加到"我的盘口""></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: RM -->
				<tr class="*TR_CLASS*">
						<td id="*RMH_GID*" onClick="betEvent('*GID*','RMH','*IORATIO_RMH*','RM');" style="cursor:pointer"  class="*TD_CLASS_RMH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_RMH*</span></div></td>
						<td id="*RMC_GID*" onClick="betEvent('*GID*','RMC','*IORATIO_RMC*','RM');" style="cursor:pointer"  class="*TD_CLASS_RMC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_RMC*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: RM -->
	
			</table>
			<!---------- RM ---------->
			<!---------- REO ---------->     
		 	<table id="model_REO" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">总分: 单 / 双&nbsp;</span>
							<span class="more_og2">*MS_STR*</span>
							<span class="more_star_bg"><span id="star_REO*MS*" name="star_REO*MS*" onClick="addFavorites('REO*MS*');" class="star_down" title="加到"我的盘口""></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: REO -->
				<tr class="*TR_CLASS*">
						<td id="*REOO_GID*" onClick="betEvent('*GID*','ODD','*IORATIO_REOO*','REO');" style="cursor:pointer"  class="*TD_CLASS_REOO*" width="50%"><div class="more_font"><span class="m_team">单</span><span class="m_red_bet" title="单">*IORATIO_REOO*</span></div></td>
						<td id="*REOE_GID*" onClick="betEvent('*GID*','EVEN','*IORATIO_REOE*','REO');" style="cursor:pointer"  class="*TD_CLASS_REOE*" width="50%"><div class="more_font"><span class="m_team">双</span><span class="m_red_bet" title="双">*IORATIO_REOE*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: REO -->
	
			</table>
			<!---------- REO ---------->
</div>
</div>
</body>
</html>
