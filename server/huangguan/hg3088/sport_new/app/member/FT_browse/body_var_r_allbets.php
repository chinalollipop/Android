<?php
session_start();
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
$uid   = $_SESSION['Oid'];
$ltype = $_REQUEST['ltype'];
$date = date("Y-m-d");
require ("../include/traditional.$langx.inc.php");

$open =$_SESSION['OpenType']; // 当前会员盘口类型
//if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
//	echo "<script>top.location.href='/'</script>";
//	exit;
//}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
<meta name="Robots" contect="none">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
    <link rel="stylesheet" href="../../../style/member/bet_maincortol.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <script type="text/javascript" class="language_choose" src="../../../js/zh-cn.js?v=<?php echo AUTOVER; ?>"></script><script type="text/javascript" src="../../../js/betcommon.js?v=<?php echo AUTOVER; ?>"></script>
<script>
var odd_f_type = '<?php echo GAME_POSITION;?>';
var _REQUEST = new Array();
 _REQUEST['gid']='<?php echo $gid;?>';
 _REQUEST['uid']='<?php echo $uid;?>';
 _REQUEST['ltype']='4';
 _REQUEST['langx']='zh-cn';
 _REQUEST['gtype']='FT';
 _REQUEST['showtype']='FT';
 _REQUEST['date']='<?php echo $date;?>';
 
var more_fave_wtype = new Array(); 
var opentype='<?php echo $open?>';
var retime=90;
var iorpoints=2; // 保留2位小数;
var show_ior = '100';	//parent
var langx = '<?php echo $langx;?>';
</script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8 "><script>
var R_Regex = new RegExp('\^\[A-FH\]\?R$');
var OU_Regex = new RegExp('\^\[A-FH\]\?OU[HC]\?$');
var AR_Regex = new RegExp('\[A-F\]R');
var AOU_Regex = new RegExp('\[A-F\]OU');
var AM_Regex = new RegExp('\[A-F\]M');
var PD_Regex = new RegExp('\^H\?R\?H\[0-9\]C\[0-9\]$');
var SFS_Regex = new RegExp('\^FS\[0\-9A\-F\]\[0\-9A\-F\]$');
var EO_Regex = new RegExp('\^H\?R\?EO$');


var ObjDataFT=new Array();   //資料
var gid_ary = new Array();

var more_window_display_none = false;
var gid_rtype_ior = new Array();

//var obj_ary = new Array("myMarkets","mainMarkets","goalMarkets","specials","corners","otherMarkets");
var obj_ary = new Array();

var mod_ary = new Array("ALL_Markets","Pop_Markets","HDP_OU","first_Half","Socore","Corners","Specials","Others");

//var open_movie = {"myMarkets":false,"mainMarkets":true,"goalMarkets":true,"specials":true,"corners":true,"otherMarkets":true};
var open_movie = new Object()

var open_mod = {"ALL_Markets":true,"Pop_Markets":true,"HDP_OU":true,"first_Half":true,"Socore":true,"Corners":true,"Specials":true,"Others":true};

var retime_flag;
var retime_run;
var mod="ALL_Markets";
var show_more_sfs = false;    //特殊冠軍 more less
var show_gid;

var allwtype_ary = new Array();

var more_bgYalloW ="";
var TV_eventid = "";
var FavRevMap;
//var mid = _REQUEST['uid'].match(/m\d*l\d*$/);
//mid = mid[0];
//mid =	mid.substring(1,mid.length).split("l")
//mid = mid[0];
// 2017-03-10 (263)舊會員端-棒球-all bets- 當賽事關盤不要顯示加至我的盤口的畫面 (所有內層都有此問題)
var gameOpen = "";

function init(){
	document.getElementById("LoadLayer").style.display="";
	document.getElementById("LoadLayer").style.visibility = "visible";

	parent.document.getElementById('all_more_window').style.display = "";
	show_gid = _REQUEST['gid'];
	obj_ary = get_obj_ary();
	open_movie = get_open_movie(obj_ary);
	allwtype_ary = create_map_array(mod_ary[0],obj_ary[0]);
	open_movieF();
	FavRevMap = make_FavRevMap();
	reloadGameData();
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
		rt.innerHTML=refreshTime;
	}
	btnClickEvent("BackToTop");
}


//reload game data
function reloadGameData(){
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
		//param+="&testMode="+"2";
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
                   // closeClickEvent();
                    show_close_info('N') ;
                    document.getElementById("LoadLayer").style.visibility = "hidden";
                    // parent.refreshReload();
                    return;
                }
				if(ObjDataFT == ""){
					if(old_ObjDataFT==""){
						closeClickEvent();
						// parent.refreshReload();
						return;
					}
					ObjDataFT = old_ObjDataFT;
				}else{
					old_ObjDataFT = ObjDataFT;

				}
                    // console.log(ObjDataFT);
                    // console.log(ObjDataFT[show_gid]);

                    // 2017-03-10 (263)舊會員端-棒球-all bets- 當賽事關盤不要顯示加至我的盤口的畫面 (所有內層都有此問題)
                    gameOpen = ObjDataFT[show_gid]["gopen"]; // 有数据是 Y
                    //console.log(gameOpen)
                    show_close_info(ObjDataFT[show_gid]["gopen"]);
                    show_gameInfo(gid_ary[0],ObjDataFT);


				TV_title();

				var tpl = new fastTemplate();
				var tmpScreen = "";
				var newWtypeOU = new Array("MOU","DU","OUE","OUT","OUP");
				var temporaryNo = new Array("AR","AOU","AM","BR","BOU","BM",
											"CR","COU","CM","DR","DOU","DM",
											"ER","EOU","EM","FR","FOU","FM",
											"SP","MPG","F2G","F3G","FG","T3G",
											"T1G","DG","OUPA","OUPB","OUPC",
											"OUPD","BH","TK","TS2","SP","PG",
											"SFS","OUP","RCD","PA","DRG","RHG","RMG",
											"PG","RC","RCD","CN","CD","YC","GA","ST","OS",
											"EOH","EOC","HEOH","HEOC","MQ","OG","OT");
				var div_model = document.getElementById('div_model');
				for(var j=0; j<div_model.children.length; j++){
						var tab_model = div_model.children[j].cloneNode(true);
						if(tab_model.nodeName =="TABLE"&&tab_model.id.indexOf("model")!=-1){
								var wtype = tab_model.id.split("_")[1];
								//console.log(wtype);
								if(in_array(wtype,temporaryNo))	continue;
								document.getElementById('body_'+wtype).innerHTML ="";
								var tmpDiv = document.createElement("div");
								tmpDiv.appendChild(tab_model);
								tpl.init(tmpDiv);
								var tr_color = 0;
								tmpScreen ="";
								var show_wtype = false;
								for(var k=0; k<gid_ary.length; k++){
										var gid = gid_ary[k];
										var hgid = ObjDataFT[gid]["hgid"];
									  if(wtype!="SFS"){
											//alert("for head");
											var ior_arr;
											var sw;

											//為了複合玩法，多回圈做新的樣式(Title底下有多個Wtype)
											var wtypeAndOU;
											if(in_array(wtype,newWtypeOU)){
												wtypeAndOU = new Array();
												var wtypeNewDelOu = wtype;
												var wtypeNewDelOuStr = wtypeNewDelOu.replace('O','');
												if(ObjDataFT[gid]["sw_"+wtypeNewDelOuStr+"A"]=='Y'){wtypeAndOU.push("A");}
												if(ObjDataFT[gid]["sw_"+wtypeNewDelOuStr+"B"]=='Y'){wtypeAndOU.push("B");}
												if(ObjDataFT[gid]["sw_"+wtypeNewDelOuStr+"C"]=='Y'){wtypeAndOU.push("C");}
												if(ObjDataFT[gid]["sw_"+wtypeNewDelOuStr+"D"]=='Y'){wtypeAndOU.push("D");}
											}else{
												wtypeAndOU = new Array("");
											}

											for(var s = 0;s < wtypeAndOU.length;s++){
												var tmpWtype = wtype+wtypeAndOU[s];
												var strong =ObjDataFT[gid]['strong'];
												//該wtype第一次且有值才做addBlock
												if(wtypeAndOU.length != 1){
													if(k == 0){
														tpl.addBlock(wtype);
														tr_color++;					//或許要搬
													}

													var sw =ObjDataFT[gid]["sw_"+tmpWtype];
													var DISPLAY = "DISPLAY_"+wtype;
													
													ior_arr = getIor(ObjDataFT[gid],tmpWtype);
													if(sw == "N" || ior_arr=="nodata"){
														tpl.replace(new RegExp('\\*'+DISPLAY+'\\*'), "bet_display");
														tpl.replace(new RegExp('\\*'+DISPLAY+'\\*'), "bet_display");
														tpl.replace(new RegExp('\\*'+DISPLAY+'\\*'), "bet_display");
														continue;
													}
													else{
														tpl.replace(new RegExp('\\*'+DISPLAY+'\\*'), "");
														tpl.replace(new RegExp('\\*'+DISPLAY+'\\*'), "");
														tpl.replace(new RegExp('\\*'+DISPLAY+'\\*'), "");
														show_wtype = true;
													}
												}
												else{
													ior_arr = getIor(ObjDataFT[gid],wtype);
													if(ior_arr=="nodata") continue;
													tpl.addBlock(wtype);
													tr_color++;
													sw =ObjDataFT[gid]["sw_"+wtype];
													show_wtype = true;
												}

												for(var t=0; t<rtypeMap[tmpWtype].length; t++){
													var rtype = rtypeMap[tmpWtype][t];
													var ior = ior_arr[rtype]["ior"];
													var ratio = ior_arr[rtype]["ratio"];
													var IORATIO = "IORATIO_"+rtype;
													var RATIO = "RATIO_"+rtype;
													var td_class = "TD_CLASS_"+rtype;
													var RTYPE_GID = rtype+"_GID";
													var RTYPE_HGID = rtype+"_HGID";
													var RTYPE,WTYPE,tmpWtype1,STR_RTYPE,str_rtype;
													if(wtypeAndOU.length != 1){
														tmpWtype1 = rtype.substr(0,rtype.length-2);
														var htmlRtype = rtype.substr(0,rtype.length-3)+rtype.substr(rtype.length-2);
														var htmlWtype = htmlRtype.substr(0,htmlRtype.length-2);
														IORATIO = "IORATIO_"+htmlRtype;
														RATIO = "RATIO_"+htmlRtype;
														RTYPE_GID = htmlRtype+"_GID";
														RTYPE_HGID = htmlRtype+"_HGID";
														RTYPE = htmlRtype+"_RTYPE";
														WTYPE = htmlWtype+"_WTYPE";
														STR_RTYPE = "STR_"+htmlRtype;
														td_class = "TD_CLASS_"+htmlRtype;
														str_rtype = rtype.substr(rtype.length-1);
														if(rtype.substr(0,2) == "OU")	str_rtype = rtype.substr(rtype.length-2,1);
														str_rtype = "str_"+tmpWtype1.substr(tmpWtype1.length-1)+str_rtype;
                                                        str_rtype = str_ABCD_OU[str_rtype];
													}
													//var ratio = xmlnode.Node(tmp_game,getRatioName(wtype,rtype));
													tpl.replace(new RegExp('\\*'+IORATIO+'\\*'), ior);
													//Ricky 2017-11-16 CRM-230 雙盤不走四捨五入邏輯
													tpl.replace(new RegExp('\\*'+IORATIO+'\\*'), parse_ior(gid,rtype,ior,wtype));
													tpl.replace(/\*GID\*/g, gid);
													tpl.replace(/\*HGID\*/g, hgid);
													tpl.replace("*TR_CLASS*",((t+tr_color)%2!=0)?"more_white":"more_color");
													tpl.replace(new RegExp('\\*'+RTYPE_GID+'\\*'), rtype+"_"+gid);
													tpl.replace(new RegExp('\\*'+RTYPE_HGID+'\\*'), rtype+"_"+hgid);
													tpl.replace(new RegExp('\\*'+RTYPE+'\\*'),rtype);
													tpl.replace(new RegExp('\\*'+WTYPE+'\\*'),tmpWtype1);
													tpl.replace(new RegExp('\\*'+STR_RTYPE+'\\*'),str_rtype);
													if(more_bgYalloW == rtype+"_"+gid || more_bgYalloW == rtype+"_"+hgid){
														tpl.replace(new RegExp('\\*'+td_class+'\\*'), "bg_yellow");
													}
													else{
														tpl.replace(new RegExp('\\*'+td_class+'\\*'), "bg_white");
													}

													tpl.replace(new RegExp('\\*'+RATIO+'\\*','g'), undefined2space(ratio));
												}
											}
										}
										else{
											max_FS = ObjDataFT[gid]["MAXSFS"];
											SFSGAME= ObjDataFT[gid]["SFS"];
											S_LIST = ObjDataFT[gid]["STYPE_LIST"];
											H_LIST = ObjDataFT[gid]["H_LIST"];
											C_LIST = ObjDataFT[gid]["C_LIST"];

											//alert(gid+"||||||"+max_FS);
											for(var p=0;p<max_FS;p++){
												if( !show_more_sfs && p>4) continue;
												tpl.addBlock(wtype);
												tpl.replace("*TR_CLASS*",((p+1)%2!=0)?"more_white":"more_color");
												for(var key in S_LIST){
													var stype = S_LIST[key];
													var td_class = "TD_CLASS_"+key;
													var FS_str = (stype.indexOf("H") < 0)? C_LIST[p]:H_LIST[p];

													//alert(stype+"===>"+FS_str);

													var ior_val = undefined2space(SFSGAME[stype]["SFS_IOR_"+FS_str]);
													var sgid = SFSGAME[stype]["SFS_GID"]

													var RTYPE_SGID = "RTYPE_SGID"+key;

													tpl.replace("*SFS_GID_"+key+"*",sgid);
													tpl.replace("*SFS_IOR_"+key+"*",ior_val);
													tpl.replace("*SFS_IOR_"+key+"*",parse_ior(sgid,FS_str,ior_val));
													tmp_SFS_NAME = undefined2space(SFSGAME[stype]["SFS_NAME_"+FS_str]);
													tpl.replace("*SFS_NAME_"+key+"*",tmp_SFS_NAME);
													tpl.replace(new RegExp('\\*TITLE_SFS_NAME_'+key+'\\*','g'),"title='"+tmp_SFS_NAME+"'");
													tpl.replace(new RegExp('\\*title_sfs_name_'+key.toLowerCase()+'\\*','g'),"title='"+tmp_SFS_NAME+"'");
													//new RegExp('\\*SFS_NAME_'+key+'\\*','g')
													tpl.replace("*SFS_RTYPE_"+key+"*",FS_str);
													tpl.replace(new RegExp('\\*'+RTYPE_SGID+'\\*'), FS_str+"_"+sgid);
													if(more_bgYalloW == FS_str+"_"+sgid ){
														tpl.replace(new RegExp('\\*'+td_class+'\\*'), "bg_yellow");
													}
													else{
														tpl.replace(new RegExp('\\*'+td_class+'\\*'), "bg_white");
													}
												}
											}
											if(max_FS >0 ){
												tmpScreen = tpl.fastPrint();
												if(max_FS>5){
													if(show_more_sfs){
														tmpScreen = tmpScreen.replace("*dis_play_more_sfs*","style=\"display:none\"");
														tmpScreen = tmpScreen.replace("*dis_play_less_sfs*","style=\"display:\"");
													}else{
														tmpScreen = tmpScreen.replace("*dis_play_more_sfs*","style=\"display:\"");
														tmpScreen = tmpScreen.replace("*dis_play_less_sfs*","style=\"display:none\"");
													}
												}else{
														tmpScreen = tmpScreen.replace("*dis_play_more_sfs*","style=\"display:none\"");
														tmpScreen = tmpScreen.replace("*dis_play_less_sfs*","style=\"display:none\"");
												}

												for(var key in S_LIST){
													var stype = S_LIST[key];
													var name = SFSGAME[stype]["SFS_TITLE"];
													var tmpName = name.replace(/-/gi,"");
													//tmpScreen = tmpScreen.replace("*TITLE_"+key+"*",name.substr(1,name.length-1));
													if(_REQUEST['langx'] != "en-us"){
														if(key=="A0" || key=="A1" || key=="A2" || key=="B0" || key=="B1" || key=="B2")
														{
															var tmp_str = tmpName.split("");
															tmpName = tmp_str[1]+tmp_str[2]+"<br>"+tmp_str[3]+tmp_str[4];
														}
													}
													tmpScreen = tmpScreen.replace("*TITLE_"+key+"*",tmpName);
												}
											}
										}
								}
								if(wtype!="SFS" ){
									tmpScreen = tpl.fastPrint();
									tmpScreen = (show_wtype)?tmpScreen:"";
								}
									tmpScreen = tmpScreen.replace(/\*TEAM_H\*/g, ObjDataFT[gid]["team_h"]);
									tmpScreen = tmpScreen.replace(/\*TEAM_C\*/g, ObjDataFT[gid]["team_c"]);
									tmpScreen = tmpScreen.replace(/\*TITLE_TEAM_H\*/g, "title='"+ObjDataFT[gid]["team_h"]+"'");//IE tag裡是大寫
									tmpScreen = tmpScreen.replace(/\*TITLE_TEAM_C\*/g, "title='"+ObjDataFT[gid]["team_c"]+"'");
									tmpScreen = tmpScreen.replace(/\*title_team_h\*/g, "title='"+ObjDataFT[gid]["team_h"]+"'");//chrome..tag小寫
									tmpScreen = tmpScreen.replace(/\*title_team_c\*/g, "title='"+ObjDataFT[gid]["team_c"]+"'");
									document.getElementById('body_'+wtype).innerHTML += tmpScreen;
									document.getElementById('body_'+wtype).style.display = "";
									//if(wtype=="SFS"){document.getElementById("title_league").innerHTML ="<xmp>"+tmpScreen+"</xmp>"}
						}


				}
				document.getElementById("LoadLayer").style.display="none";
				document.getElementById("LoadLayer").style.visibility = "hidden";
				/*
				if(more_window_display_none){
						parent.document.getElementById('more_window').style.display='none';
						show_gid='';
				}
				*/
				//最愛
				var tmp_arr = new Array();
				tmp_arr = more_fave_wtype;
				more_fave_wtype = new Array();
                if(tmp_arr) {
                    for (var i = 0; i < tmp_arr.length; i++) {
                        if (FavMap[tmp_arr[i]] == undefined) {
                            if (!in_array(tmp_arr[i], more_fave_wtype)) {
                                more_fave_wtype.push(tmp_arr[i]);
                            }
                            continue;
                        }
                        addFavorites(tmp_arr[i], "fromTop");
                    }
                }
				//mod_sel(mod);
	fix_body_wtype();
	//模式處理
	mod_class_close();
	mod_sel(mod);
	//fixMoreWindow();
}



//liveTV
function liveTVClickEvent(){
	var eventid = ObjDataFT[show_gid]["eventid"];
	if (eventid != "" && eventid != "null" && eventid != "undefined") {	//判斷是否有轉播
			//parent.OpenLive(eventid,"FT");

	}
}

//refresh
function reFreshClickEvent(){
	//alert("refresh");
	reloadGameData();
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
		parent.show_more_gid='';
    try{
        //parent.document.getElementById('more_window').style.display='none';
        parent.document.getElementById('all_more_window').style.display='none';
        parent.body_browse.document.getElementById('MFT').className="bodyset FTR";
        parent.body_browse.document.getElementById('box').style.display="";
        parent.body_browse.document.getElementById('right_div').style.display="";
        parent.body_browse.scrollTo(0,top.browse_ScrollY);
    }catch (E){

    }
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
		}
		else{
			obj.style.display="";
			open_movie[objName]=true;
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
var titleDiv;
function show_gameInfo(gid,ObjDataFT){

	//var gameInfo = document.getElementById("gameInfo");

	var league_name = ObjDataFT[gid]["league"];
	var gdatetime = ObjDataFT[gid]["datetime"];
	var team_name_h = ObjDataFT[gid]["team_h"];
	var team_name_c = ObjDataFT[gid]["team_c"];
	var live = ObjDataFT[gid]["Live"];
	var midfield = ObjDataFT[gid]["midfield"];
	var dtime = gdatetime.split(" ");
	var date = dtime[0].split("-");
	var time = dtime[1].split(":");

	if(titleDiv == undefined){
		titleDiv = document.createElement("div");
		titleDiv.appendChild(gameInfo.cloneNode(true));
	}
	var tmpDiv = titleDiv.cloneNode(true);
	var tmp_repl = tmpDiv.innerHTML;
	//Ricky 2017-12-22 [401]所有會員端-今日全球類-all bets 多秀日期
	//tmp_repl = tmp_repl.replace('*DATE*',date[2]+" / "+date[1]);
	tmp_repl = tmp_repl.replace('*DATE*',"");
	tmp_repl = tmp_repl.replace('*TIME*',time[0]+":"+time[1]);
	tmp_repl = tmp_repl.replace('*TEAM_H*',team_name_h);
	tmp_repl = tmp_repl.replace('*TEAM_C*',team_name_c);
	tmp_repl = tmp_repl.replace('*MID_DISPLAY*',(midfield=="Y")?"":"style='display:none'");
	tmp_repl = tmp_repl.replace('*mid_display*',(midfield=="Y")?"":"style='display:none'");

	//tmpDiv.innerHTML = tmp_repl.replace('*LIVE*',(live == 'Y')?"<span class='more_ln'>LIVE</span>":"");
	// 2017-03-27 309.info & UAT & 線上-舊會員端-所有球類-今日、過關、早餐-內層記分板，”滾球”字眼顯示成英文
	tmpDiv.innerHTML = tmp_repl.replace('*LIVE*',(live == 'Y')?str_RB:"");

	document.getElementById("title_league").innerHTML = league_name;
	gameInfo.parentNode.replaceChild(tmpDiv.children[0],gameInfo);
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
	if(!in_array(fav_wtype,more_fave_wtype))more_fave_wtype.push(fav_wtype);
	top.CM.set("more_fave_wtype@"+mid,more_fave_wtype);
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
	mod_sel(mod);
}
function delFavorites(fav_wtype){
	fav_wtype = FavRevMap[fav_wtype];
	for(var k=0;k<FavMap[fav_wtype].length;k++){
		try{
			wtype_str = FavMap[fav_wtype][k]
			var tmp_arr = new Array();
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
	for(var i=0, a=0;i < more_fave_wtype.length ; i++){
		tmp_wtype = more_fave_wtype[i]
		if(fav_wtype != tmp_wtype) tmp_arr[a++] = tmp_wtype ;
	}
	more_fave_wtype = tmp_arr;
	if(more_fave_wtype.length == 0 ) more_fave_wtype.push("fave_wtype");
	top.CM.set("more_fave_wtype@"+mid,more_fave_wtype);
	fave_cont = count_wtype("myMarkets","ALL_Markets");
	cont_myMarket.innerHTML = fave_cont;
	if(fave_cont == 0)document.getElementById("movie_myMarkets_nodata").style.display="";
	fix_body_wtype();
	mod_sel(mod);
}



function betEvent(gid,rtype,ratio,wtype){
	//alert(gid+rtype+ratio+wtype);
	if(ratio*1==0)return;
    var old_gid = _REQUEST['gid'];
    // if( gid != _REQUEST['gid']){
    //     gid = _REQUEST['gid'];
    // }

	if(wtype!='NFS'){
		//parent.mem_order.betOrder('FT',wtype,getParam(gid,wtype,rtype,ratio));
        parent.parent.parent.mem_order.betOrder('FT',wtype,getParam(old_gid,wtype,rtype,ratio),old_gid);
	}
	else{
		var param = 'gametype=FT&gid='+old_gid+'&uid='+_REQUEST['uid']+'&rtype='+rtype+'&wtype=FS'+'&langx='+_REQUEST['langx'];
		//parent.mem_order.betOrder('FT',wtype,param);
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

	if(more_bgYalloW != ""){
		try{
		tar = document.getElementById(more_bgYalloW);
		setObjectClass(tar,"bg_white");
		}catch(e){}
	}
	more_bgYalloW = rtype+"_"+gid;
	tar = document.getElementById(more_bgYalloW);

	setObjectClass(tar,"bg_yellow");

}
function canclebet(){
	//alert("canclebet=="+more_bgYalloW);
	if(more_bgYalloW != ""){
                try{
                tar = document.getElementById(more_bgYalloW);
                setObjectClass(tar,"bg_white");
                }catch(e){}
        }
	more_bgYalloW="";
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
	// if(gid%2==1) gid = gid-1;;
	var GameFT = ObjDataFT[gid];
	var strong = GameFT["strong"];
	var compoundWtype = new Array("MOUA","MOUB","MOUC","MOUD","MPG","MTS","DUA","DUB","DUC","DUD","DS","DG","OUTA","OUTB","OUTC","OUTD","OUPA","OUPB","OUPC","OUPD","OUEA","OUEB","OUEC","OUED");


	var type = rtype.substr(rtype.length-1,1).toUpperCase();
	if(wtype.indexOf('OU') != -1 ){
		if(type=='O')type='C';
		if(type=='U')type='H'
	}
	if( wtype=='M' || wtype=='HM' || AM_Regex.test(wtype) ||wtype =='W3' ){
		var new_type = (type=='H')?'H':'C';
	}
	if(wtype == "HPD" || wtype == "HRPD"){
		rtype = rtype.substr(1,rtype.length);
	}
	var param = 'gid='+gid+'&uid='+_REQUEST['uid']+'&odd_f_type='+odd_f_type+'&langx='+_REQUEST['langx']+'&rtype='+rtype;

	if(wtype=='R' ||wtype=='HR') {

		param += '&gnum='+GameFT['gnum_'+type.toLowerCase()]+'&strong='+strong+'&type='+type;

	}else if(wtype=='OU' || wtype =='HOU') {

		param += '&gnum='+GameFT['gnum_'+type.toLowerCase()]+'&type='+type;

	}else if(wtype=='M'  || wtype =='HM' ){

		param += '&gnum='+GameFT['gnum_'+new_type.toLowerCase()]+'&type='+type;

	}else if(wtype=='OUH'|| wtype =='HOUH' || wtype=='OUC' || wtype =='HOUC'){

		param += '&gnum='+GameFT['gnum_'+type.toLowerCase()]+'&type='+(type =='H'?'U':'O')+'&wtype='+wtype;

	}else if(AR_Regex.test(wtype)) {

		param += '&gnum='+GameFT['gnum_'+type.toLowerCase()]+'&strong='+strong+'&type='+type+'&wtype='+wtype;

	}else if(AOU_Regex.test(wtype)) {

		param += '&gnum='+GameFT['gnum_'+type.toLowerCase()]+'&type='+(type =='H'?'U':'O')+'&wtype='+wtype;

	}else if(AM_Regex.test(wtype)){

		param += '&gnum='+GameFT['gnum_'+new_type.toLowerCase()]+'&type='+type+'&wtype='+wtype;

	}else if(wtype=='EOH' || wtype == 'EOC' || wtype == 'HEOH' || wtype == 'HEOC') {
		param += '&wtype='+wtype;

	}else if(in_array(wtype,compoundWtype)) {
		//有OUPD這wtype，會與下面的判斷相符，故拉前做
		param += '&wtype='+wtype;

	}else if(wtype.indexOf("PD") != -1 || wtype == 'T' || wtype == 'HT' || wtype == 'F' || wtype.indexOf("EO") != -1 || wtype == "SP") {

		param +='';

	}else if(wtype=='W3') {

		param += '&gnum='+GameFT['gnum_'+new_type.toLowerCase()]+'&strong='+strong+'&type='+type+'&wtype='+wtype;

	}else param += '&wtype='+wtype;


	/*
	var thisRegex = new RegExp('\[ABDE\]RE');
	if(thisRegex.test(wtype)){
	}
	*/
	//preg_match("/([A-F]RE)/",$rtype)


	return param;
}



function fixMoreWindow(){
	var tab_show = document.getElementById('tab_show');
	var MFT = parent.document.getElementById('MFT');
	parent.document.getElementById('showdata').width = MFT.offsetWidth;
	parent.document.getElementById('showdata').height = get_max(tab_show.offsetHeight,MFT.offsetHeight);

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
function mod_sel(mod_Name){
	if(mod_Name != "ALL_Markets"){

		var ret = true
		for(var i=1; i<obj_ary.length; i++){
			var cnt = count_wtype(obj_ary[i],mod_Name);
			if(cnt != 0)ret = false;
		}
		if(ret)return ;

	}
	mod = mod_Name;

	//模式 up down
	for(var i=0 ;i<mod_ary.length;i++){
		if(open_mod[mod_ary[i]]){
			if(mod == mod_ary[i])document.getElementById(mod_ary[i]).className="mod_up";
			else document.getElementById(mod_ary[i]).className="mod_down";
		}

	}

	for(var i=1; i<obj_ary.length; i++){
		var head_display = false;
		var _name = obj_ary[i];
		var div_model = document.getElementById('movie_'+_name);
		for(var j=0; j<div_model.children.length; j++){
			var child_model = div_model.children[j];

			if(child_model.nodeName =="DIV"&& child_model.id.indexOf("body")!=-1 ){
				var wtype = child_model.id.split("body_")[1];
				if(mod_Name == "ALL_Markets" || modeMap[wtype][mod_Name] ){
					if(document.getElementById('body_'+wtype).innerHTML != "") {
						document.getElementById('body_'+wtype).style.display = "";
						setStarTitle(wtype,addtoMyMarket);
						head_display = true;
					}
					else document.getElementById('body_'+wtype).style.display = "none";
				}
				else{
					document.getElementById('body_'+wtype).style.display ="none";
				}
				//把家到我的最愛的star title清掉
				if(document.getElementById('favorites_'+wtype).innerHTML != "") {
					setStarTitle(wtype,"");
				}
			}


		}
		document.getElementById("head_"+_name).style.display = (head_display)?"":"none";
	}
}
//球頭字串
function getRatioName(wtype,rtype){
	var ratio_str= "ratio";
	switch(wtype){
		case 'R':
			break;
		case 'HR':
		ratio_str = 'h'+ratio_str;
			break;
		case 'OU':
			ratio_str+= (rtype.substr(rtype.length-1,1) =='H')?'_o':'_u';
			break;
		case 'HOU':
			ratio_str+= (rtype.substr(rtype.length-1,1) =='H')?'_ho':'_hu';
			break;
		//case 'W3':
		case 'AR':
		case 'BR':
		case 'CR':
		case 'DR':
		case 'ER':
		case 'FR':
			ratio_str+= "_"+wtype;
			break;
		default:
			ratio_str+= "_"+rtype;
			break;
	}
	return ratio_str.toLowerCase();
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
		//Ricky 2017-11-16 CRM-230 雙盤不走四捨五入邏輯
		//ior_value=parent.chgForm_Single_ratio(ior_value,rtype,wtype);
		ior_value=chgForm_Single_ratio(ior_value,rtype,wtype);
		ior_value=js_change_rate(opentype,ior_value);
	}
	//if(ior_value*1 == 0 && ( PD_Regex.test(rtype) || SFS_Regex.test(rtype)  || rtype =="OVH" || rtype =="HOVH" ) )return "-";
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
function SFS_show(show_str){
	if(show_more_sfs){
		show_more_sfs=false;
	}
	else {
		show_more_sfs=true;
	}
	reloadGameData();
}



function XML2Array(xmlnode,xmlnodeRoot){
	var tmp_Obj = new Array();
	var gameXML = xmlnode.Node(xmlnodeRoot,"game",false);
	gid_ary = new Array();
	for(var k=0; k<gameXML.length; k++){
		var tmp_game = gameXML[k];
		var gid = getNodeVal(xmlnode.Node(tmp_game,"gid"));
		var hgid = getNodeVal(xmlnode.Node(tmp_game,"hgid"));
		var TagName = tmp_game.getElementsByTagName("*");

		gid_ary[gid_ary.length] = gid;
		//gid_ary[gid_ary.length] = hgid;
		tmp_Obj[gid] = new Array();
		tmp_Obj[hgid] = new Array();
		for( var i=0;i<TagName.length;i++){
			try{
				tmp_Obj[gid][TagName[i].nodeName] =  getXML_TagValue(xmlnode,tmp_game,TagName[i].nodeName);
				tmp_Obj[hgid][TagName[i].nodeName] =  getXML_TagValue(xmlnode,tmp_game,TagName[i].nodeName);
			}
			catch(e){
				//tmp_Obj[gid][TagName[i]] = "";
				//tmp_Obj[hgid][TagName[i]] = "";
			}
		}


		try{
			var max_FS=0;
			var SFSGAMEXML = xmlnode.Node(tmp_game,"SFSGAME",false);
			var SFSXML = xmlnode.Node(tmp_game,"SFS",false);
			var LS = _REQUEST['langx']!="zh-tw"?(_REQUEST['langx']!="zh-cn"?"E":"G"):"C";
			var SFSGAME = new Array();
			var S_LIST = new Array();
			var cnt_H = new Array();
			var cnt_C = new Array();
			var RTYPE_H = new Array();
			var RTYPE_C = new Array();

			//alert(gid+"|"+SFSXML.length);
			for(var m=0;m<SFSXML.length;m++){
				var tmp_sfs = SFSXML[m];
				SFStype = getNodeVal(xmlnode.Node(tmp_sfs,"SFS_ID"));
				S_LIST[S_LIST.length] = SFStype;

				SFSGAME[SFStype] = new Array();
				SFSGAME[SFStype]["SFS_GID"] = getNodeVal(xmlnode.Node(tmp_sfs,"SFS_GID"));
				SFSGAME[SFStype]["SFS_TITLE"] = getNodeVal(xmlnode.Node(tmp_sfs,"SFS_PICTHER_"+LS));

				var RTYPES = xmlnode.Node(SFSXML[m],"RTYPES",false);
				for(var n=0;n<RTYPES.length;n++){
		  		var tmp_rtype = RTYPES[n];
		  		var FSrtype = getNodeVal(xmlnode.Node(tmp_rtype,"SFS_RTYPE"));
		  		SFSGAME[SFStype]["SFS_IOR_"+FSrtype] = getNodeVal(xmlnode.Node(tmp_rtype,"SFS_IOR"));
		  		SFSGAME[SFStype]["SFS_NAME_"+FSrtype] = getNodeVal(xmlnode.Node(tmp_rtype,"SFS_NAME_"+LS));

		  		if(SFStype.indexOf("H")!=-1){
		  			 if(cnt_H[FSrtype]==undefined) cnt_H[FSrtype] = 0;
		  			 //alert(n+"==>"+cnt_H[FSrtype]);
		  			 cnt_H[FSrtype] += getNodeVal(xmlnode.Node(tmp_rtype,"SFS_IOR"))*1;
					}
					if(SFStype.indexOf("C")!=-1){
						 if(cnt_C[FSrtype]==undefined) cnt_C[FSrtype] = 0;
						 cnt_C[FSrtype] += getNodeVal(xmlnode.Node(tmp_rtype,"SFS_IOR"))*1;
					}
				}
			}
			var r_key;
			for(r_key in cnt_H){
				//alert(r_key+" ===>"+cnt_H[r_key]);
				if(cnt_H[r_key] > 0) RTYPE_H[RTYPE_H.length] = r_key;
			}

			for(r_key in cnt_C){
				//alert(r_key+" ===>"+cnt_C[r_key]);
				if(cnt_C[r_key] > 0) RTYPE_C[RTYPE_C.length] = r_key;
			}

			max_FS = (RTYPE_C.length > RTYPE_H.length)?RTYPE_C.length:RTYPE_H.length;

			//alert(RTYPE_H.toString());
			//alert(RTYPE_C.toString());

			tmp_Obj[gid]["STYPE_LIST"] = sortStype(S_LIST);
			tmp_Obj[gid]["H_LIST"] = RTYPE_H;
			tmp_Obj[gid]["C_LIST"] = RTYPE_C;
			tmp_Obj[gid]["MAXSFS"] = max_FS
			tmp_Obj[gid]["SFS"] = SFSGAME;

			// ObjDataFT[show_gid]["SFS_TITLE"];
		}catch(e){
			//alert(e.toString());
			//tmp_Obj[gid]["STYPE_LIST"] = "";
			//tmp_Obj[gid]["MAXSFS"] = "";
			//tmp_Obj[gid]["SFS"] = "";
		}
	}
	return tmp_Obj;
}

function sortStype(S_LIST){
	S_LIST.sort();
	var outObj = new Object();
	var match = {"H":"A","C":"B"};
	var cnt = {"H":0,"C":0};
	var tmp;

	for(var i=0 ;i<S_LIST.length;i++){
			tmp = S_LIST[i].substr(0,1);

			outObj[match[tmp]+(cnt[tmp]++)] = S_LIST[i];
	}

	return outObj;
}

function getIor(gdata,wtype){
//	var wtype_chk_ary = [];
	var map = rtypeMap[wtype];
	var ior = new Object();
	var rtype,ratio_str,type;
	var sw = gdata["sw_"+wtype];
	var gopen = gdata["gopen"];
	var ior_all_zero = true;
        var strong;
        (wtype=="HR")?
        strong = gdata['hstrong']:
        strong = gdata['strong'];
	if(gopen == "N") return "nodata" ;
	if(sw == "N") return "nodata" ;
	for(var i=0;i<map.length;i++){
		rtype =  map[i];
	//	if( wtype_chk_ary.indexOf(wtype)  && gdata["ior_"+rtype]==0 ) return "nodata" ;
		if(!isNaN(gdata["ior_"+rtype]) && gdata["ior_"+rtype]*1 != 0) ior_all_zero= false;
		ior[rtype] = new Object();
		ior[rtype]["ior"] = gdata["ior_"+rtype];

		ratio_str = getRatioName(wtype,rtype);

		if(gdata[ratio_str]){
				ior[rtype]["ratio"] = gdata[ratio_str];

				type = rtype.substr(rtype.length-1,1);
				//if(R_Regex.test(wtype) || wtype=="W3" ){
				if(R_Regex.test(wtype) ){
						if(type != strong  || type=="N"){
							ior[rtype]["ratio"] = "";
						}
		  	}
		  	if(wtype=="W3"){
		  		ior[rtype]["ratio"] = ior[rtype]["ratio"]*1
		  		if(ior[rtype]["ratio"] > 0) ior[rtype]["ratio"] = "+ "+ior[rtype]["ratio"];
		  		if(ior[rtype]["ratio"] < 0) ior[rtype]["ratio"] = "- "+ior[rtype]["ratio"]*-1;
		  	}
	  }
	}
	if(ior_all_zero)return "nodata";
	//2017-07-20 CRB-101 足球玩法原雙盤改單盤  (會員三端) Ricky
	//將 "OG","OT","TS","HTS" 從判斷式中拿掉
	if( R_Regex.test(wtype) || OU_Regex.test(wtype) || wtype == 'EO' || wtype == 'HEO' ||
			wtype == 'EOH' || wtype == 'EOC' || wtype == 'HEOH' || wtype == 'HEOC'
			){
			var arry = new Array();
			if(wtype == 'EO' || wtype=='HEO' || wtype == 'EOH' || wtype == 'EOC' || wtype == 'HEOH' || wtype == 'HEOC') {
					arry[0] = (ior[map[0]]["ior"]*1000 - 1000) / 1000;
					arry[1] = (ior[map[1]]["ior"]*1000 - 1000) / 1000;

					arry = get_other_ioratio("H",arry[0],arry[1],show_ior);
					arry[0] =(arry[0]*1000 + 1000) / 1000;
					arry[1] =(arry[1]*1000 + 1000) / 1000;
			}else{
				 arry[0] = ior[map[0]]["ior"]*1;
				 arry[1] = ior[map[1]]["ior"]*1;

				// arry = get_other_ioratio(odd_f_type,arry[0],arry[1],show_ior);  // show_ior 固定为100 ，这个函数会造成球队得分大小赔率转换，后端也需要这样处理(2018/09/23 改成后端统一处理)
			}

			ior[map[0]]["ior"] = arry[0];
			ior[map[1]]["ior"] = arry[1];
	}

	return ior;
}

function mod_OnOver(this_mod){
	if(open_mod[this_mod]){
		document.getElementById(this_mod).className="mod_up";
	}
}
function mod_OnOut(this_mod){
	if(open_mod[this_mod]){
		if(mod == this_mod)document.getElementById(this_mod).className="mod_up";
		else document.getElementById(this_mod).className="mod_down";
	}
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
					if(wtype.match(/^[A-FH]?(OU|R|M)$/)){
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
		tv_bton.title = str_TV_FT;
	}
	else {
		tv_bton.style.display="none";
	}

	tv_bton.className = "more_tv_out";
}

function setStarTitle(wtype,TitleText){
	document.getElementById("star_"+wtype).title = TitleText;
}

function show_close_info(gopen){
	var dis_str = "";
	if(gopen=='N')dis_str = "none";
	document.getElementById("gameOver").style.display = (dis_str=="none")?"":"none" ;
	document.getElementById("mod_table").style.display = dis_str;
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

//
function create_map_array(modName,objName){
	var ret_arry = new Array();
	var div_model = document.getElementById('movie_'+objName);
	for(var j=0; j<div_model.children.length; j++){
		var child_model = div_model.children[j];
		if(child_model.nodeName =="DIV"&& ( child_model.id.indexOf("body")!=-1 || child_model.id.indexOf("favorites")!=-1 ) ){
			var wtype = child_model.id.split("body_")[1] || child_model.id.split("favorites_")[1] ;
			if(modName == "ALL_Markets" || modeMap[wtype][modName] ){
				ret_arry.push(wtype);
			}
		}
	}
	return ret_arry;
}

//var obj_ary = new Array("myMarkets","mainMarkets","goalMarkets","specials","corners","otherMarkets");
//var mod_ary = new Array("ALL_Markets","Pop_Markets","HDP_OU","first_Half","Socore","Corners","Specials","Others");

//模式反灰 產生 open_mod
function mod_class_close(){
	for(k=1;k<mod_ary.length;k++){
		modName = mod_ary[k];
		var open_it = false
		for(var i=1; i<obj_ary.length; i++){
			var cnt = count_wtype(obj_ary[i],modName);
			if(cnt != 0)open_it = true;
		}
		open_mod[modName] = open_it;
		//alert(open_it)
		if(open_it == false){
			document.getElementById(modName).className = "mod_none";
		}else if(mod == modName){
			 document.getElementById(modName).className = "mod_up";
		}
		else{
			document.getElementById(modName).className = "mod_down";
		}
	}
}

function get_obj_ary(){
	var retAry = new Array();
	var t = document.getElementById("tab_show");
	var td = t.children[0].children[0].children[0];
	for(keys=0 ;keys<td.children.length;keys++){
		htmlObj = td.children[keys];
		if(htmlObj.id.indexOf("head_")!= -1 && htmlObj.nodeName == "TABLE"){
			retAry.push(htmlObj.id.split("_")[1]);
		}
	}
	return retAry
}

function get_open_movie(objAry){
	var retObj = new Object();
	for(var i=0;i<objAry.length;i++){
		objName = objAry[i];
		var obj = document.getElementById('movie_'+objName);
		if(obj != null){
			//if(obj.style.display=="")	retObj[objName]=false;
			// 2017-03-13 300.新舊會員端-足球,棒球-all bet-我的盤口要是預設關的
			if(objName=="myMarkets")	retObj[objName]=false;
			else retObj[objName]=true;
		}
	}
	//return open_movie
	// 2017-03-13 300.新舊會員端-足球,棒球-all bet-我的盤口要是預設關的
	return retObj;
}

function clog(str){
	try{
		console.log(str);
	}catch(e){;}
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
//parent-------------------------------------start
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
rtypeMap["HRE"] = ["HREH","HREC"];
rtypeMap["ROU"] = ["ROUH","ROUC"];
rtypeMap["HROU"] = ["HROUH","HROUC"];
rtypeMap["RM"] = ["RMH","RMC","RMN"];
rtypeMap["HRM"] = ["HRMH","HRMC","HRMN"];
rtypeMap["ARE"] = ["AREH","AREC"];
rtypeMap["AROU"] = ["AROUU","AROUO"];
rtypeMap["ARM"] = ["ARMH","ARMC","ARMN"];
rtypeMap["BRE"] = ["BREH","BREC"];
rtypeMap["BROU"] = ["BROUU","BROUO"];
rtypeMap["BRM"] = ["BRMH","BRMC","BRMN"];
rtypeMap["DRE"] = ["DREH","DREC"];
rtypeMap["DROU"] = ["DROUU","DROUO"];
rtypeMap["DRM"] = ["DRMH","DRMC","DRMN"];
rtypeMap["ERE"] = ["EREH","EREC"];
rtypeMap["EROU"] = ["EROUU","EROUO"];
rtypeMap["ERM"] = ["ERMH","ERMC","ERMN"];
rtypeMap["RPD"] = ["RH1C0","RH2C0","RH2C1","RH3C0","RH3C1","RH3C2","RH4C0","RH4C1","RH4C2","RH4C3","RH0C0","RH1C1","RH2C2","RH3C3","RH4C4","ROVH","RH0C1","RH0C2","RH1C2","RH0C3","RH1C3","RH2C3","RH0C4","RH1C4","RH2C4","RH3C4","ROVC"];
rtypeMap["HRPD"] = ["HRH1C0","HRH2C0","HRH2C1","HRH3C0","HRH3C1","HRH3C2","HRH4C0","HRH4C1","HRH4C2","HRH4C3","HRH0C0","HRH1C1","HRH2C2","HRH3C3","HRH4C4","HROVH","HRH0C1","HRH0C2","HRH1C2","HRH0C3","HRH1C3","HRH2C3","HRH0C4","HRH1C4","HRH2C4","HRH3C4","HROVC"];
rtypeMap["RT"] = ["RT01","RT23","RT46","ROVER"];
rtypeMap["HRT"] = ["HRT0","HRT1","HRT2","HRTOV"];
rtypeMap["RF"] = ["RFHH","RFHN","RFHC","RFNH","RFNN","RFNC","RFCH","RFCN","RFCC"];
rtypeMap["RWM"] = ["RWMH1","RWMH2","RWMH3","RWMHOV","RWMC1","RWMC2","RWMC3","RWMCOV","RWM0","RWMN"];
rtypeMap["RDC"] = ["RDCHN","RDCCN","RDCHC"];
rtypeMap["RWE"] = ["RWEH","RWEC"];
rtypeMap["RWB"] = ["RWBH","RWBC"];
rtypeMap["ARG"] = ["ARGH","ARGC","ARGN"];
rtypeMap["BRG"] = ["BRGH","BRGC","BRGN"];
rtypeMap["CRG"] = ["CRGH","CRGC","CRGN"];
rtypeMap["DRG"] = ["DRGH","DRGC","DRGN"];
rtypeMap["ERG"] = ["ERGH","ERGC","ERGN"];
rtypeMap["FRG"] = ["FRGH","FRGC","FRGN"];
rtypeMap["GRG"] = ["GRGH","GRGC","GRGN"];
rtypeMap["HRG"] = ["HRGH","HRGC","HRGN"];
rtypeMap["IRG"] = ["IRGH","IRGC","IRGN"];
rtypeMap["JRG"] = ["JRGH","JRGC","JRGN"];
rtypeMap["RTS"] = ["RTSY","RTSN"];
rtypeMap["ROUH"] = ["ROUHO","ROUHU"];
rtypeMap["ROUC"] = ["ROUCO","ROUCU"];
rtypeMap["HRUH"] = ["HRUHO","HRUHU"];
rtypeMap["HRUC"] = ["HRUCO","HRUCU"];
rtypeMap["REO"] = ["REOO","REOE"];
rtypeMap["HREO"] = ["HREOO","HREOE"];
rtypeMap["RCS"] = ["RCSH","RCSC"];
rtypeMap["RWN"] = ["RWNH","RWNC"];
rtypeMap["RHG"] = ["RHGH","RHGC"];
rtypeMap["RMG"] = ["RMGH","RMGC","RMGN"];
rtypeMap["RSB"] = ["RSBH","RSBC"];
rtypeMap["RT3G"] = ["RT3G1","RT3G2","RT3GN"];
rtypeMap["RT1G"] = ["RT1G1","RT1G2","RT1G3","RT1G4","RT1G5","RT1G6","RT1GN"];
rtypeMap["RTS2"] = ["RTS2Y","RTS2N"];
rtypeMap["RMUA"] = ["RMUAHO","RMUANO","RMUACO","RMUAHU","RMUANU","RMUACU"];
rtypeMap["RMUB"] = ["RMUBHO","RMUBNO","RMUBCO","RMUBHU","RMUBNU","RMUBCU"];
rtypeMap["RMUC"] = ["RMUCHO","RMUCNO","RMUCCO","RMUCHU","RMUCNU","RMUCCU"];
rtypeMap["RMUD"] = ["RMUDHO","RMUDNO","RMUDCO","RMUDHU","RMUDNU","RMUDCU"];
rtypeMap["RMPG"] = ["RMPGHH","RMPGNH","RMPGCH","RMPGHC","RMPGNC","RMPGCC"];
rtypeMap["RMTS"] = ["RMTSHY","RMTSNY","RMTSCY","RMTSHN","RMTSNN","RMTSCN"];
rtypeMap["RDUA"] = ["RDUAHO","RDUACO","RDUASO","RDUAHU","RDUACU","RDUASU"];
rtypeMap["RDUB"] = ["RDUBHO","RDUBCO","RDUBSO","RDUBHU","RDUBCU","RDUBSU"];
rtypeMap["RDUC"] = ["RDUCHO","RDUCCO","RDUCSO","RDUCHU","RDUCCU","RDUCSU"];
rtypeMap["RDUD"] = ["RDUDHO","RDUDCO","RDUDSO","RDUDHU","RDUDCU","RDUDSU"];
rtypeMap["RDS"] = ["RDSHY","RDSCY","RDSSY","RDSHN","RDSCN","RDSSN"];
rtypeMap["RDG"] = ["RDGHH","RDGCH","RDGSH","RDGHC","RDGCC","RDGSC"];
rtypeMap["RUEA"] = ["RUEAOO","RUEAOE","RUEAUO","RUEAUE"];
rtypeMap["RUEB"] = ["RUEBOO","RUEBOE","RUEBUO","RUEBUE"];
rtypeMap["RUEC"] = ["RUECOO","RUECOE","RUECUO","RUECUE"];
rtypeMap["RUED"] = ["RUEDOO","RUEDOE","RUEDUO","RUEDUE"];
rtypeMap["RUTA"] = ["RUTAOY","RUTAON","RUTAUY","RUTAUN"];
rtypeMap["RUTB"] = ["RUTBOY","RUTBON","RUTBUY","RUTBUN"];
rtypeMap["RUTC"] = ["RUTCOY","RUTCON","RUTCUY","RUTCUN"];
rtypeMap["RUTD"] = ["RUTDOY","RUTDON","RUTDUY","RUTDUN"];
rtypeMap["RUPA"] = ["RUPAOH","RUPAOC","RUPAUH","RUPAUC"];
rtypeMap["RUPB"] = ["RUPBOH","RUPBOC","RUPBUH","RUPBUC"];
rtypeMap["RUPC"] = ["RUPCOH","RUPCOC","RUPCUH","RUPCUC"];
rtypeMap["RUPD"] = ["RUPDOH","RUPDOC","RUPDUH","RUPDUC"];
rtypeMap["ROT"] = ["ROTY","ROTN"];
rtypeMap["RSHA"] = ["RSHAY","RSHAN","RSCAY","RSCAN"];
rtypeMap["RSHB"] = ["RSHBY","RSHBN","RSCBY","RSCBN"];
rtypeMap["RSHC"] = ["RSHCY","RSHCN","RSCCY","RSCCN"];
rtypeMap["RSHD"] = ["RSHDY","RSHDN","RSCDY","RSCDN"];
rtypeMap["RSHE"] = ["RSHEY","RSHEN","RSCEY","RSCEN"];
rtypeMap["RSHF"] = ["RSHFY","RSHFN","RSCFY","RSCFN"];
rtypeMap["RSHG"] = ["RSHGY","RSHGN","RSCGY","RSCGN"];
rtypeMap["RSHH"] = ["RSHHY","RSHHN","RSCHY","RSCHN"];
rtypeMap["RSHI"] = ["RSHIY","RSHIN","RSCIY","RSCIN"];
rtypeMap["RSHJ"] = ["RSHJY","RSHJN","RSCJY","RSCJN"];
rtypeMap["RSHK"] = ["RSHKY","RSHKN","RSCKY","RSCKN"];
rtypeMap["RSHL"] = ["RSHLY","RSHLN","RSCLY","RSCLN"];
rtypeMap["RSHM"] = ["RSHMY","RSHMN","RSCMY","RSCMN"];
rtypeMap["RSHN"] = ["RSHNY","RSHNN","RSCNY","RSCNN"];
rtypeMap["RSHO"] = ["RSHOY","RSHON","RSCOY","RSCON"];
rtypeMap["RNBA"] = ["RNBAH","RNBAC"];
rtypeMap["RNBB"] = ["RNBBH","RNBBC"];
rtypeMap["RNBC"] = ["RNBCH","RNBCC"];
rtypeMap["RNBD"] = ["RNBDH","RNBDC"];
rtypeMap["RNBE"] = ["RNBEH","RNBEC"];
rtypeMap["RNBF"] = ["RNBFH","RNBFC"];
rtypeMap["RNBG"] = ["RNBGH","RNBGC"];
rtypeMap["RNBH"] = ["RNBHH","RNBHC"];
rtypeMap["RNBI"] = ["RNBIH","RNBIC"];
rtypeMap["RNBJ"] = ["RNBJH","RNBJC"];
rtypeMap["RNBK"] = ["RNBKH","RNBKC"];
rtypeMap["RNBL"] = ["RNBLH","RNBLC"];
rtypeMap["RNBM"] = ["RNBMH","RNBMC"];
rtypeMap["RNBN"] = ["RNBNH","RNBNC"];
rtypeMap["RNBO"] = ["RNBOH","RNBOC"];
rtypeMap["RNC1"] = ["RNC1H","RNC1C"];
rtypeMap["RNC2"] = ["RNC2H","RNC2C"];
rtypeMap["RNC3"] = ["RNC3H","RNC3C"];
rtypeMap["RNC4"] = ["RNC4H","RNC4C"];
rtypeMap["RNC5"] = ["RNC5H","RNC5C"];
rtypeMap["RNC6"] = ["RNC6H","RNC6C"];
rtypeMap["RNC7"] = ["RNC7H","RNC7C"];
rtypeMap["RNC8"] = ["RNC8H","RNC8C"];
rtypeMap["RNC9"] = ["RNC9H","RNC9C"];
rtypeMap["RNCA"] = ["RNCAH","RNCAC"];
rtypeMap["RNCB"] = ["RNCBH","RNCBC"];
rtypeMap["RNCC"] = ["RNCCH","RNCCC"];
rtypeMap["RNCD"] = ["RNCDH","RNCDC"];
rtypeMap["RNCE"] = ["RNCEH","RNCEC"];
rtypeMap["RNCF"] = ["RNCFH","RNCFC"];
rtypeMap["RNCG"] = ["RNCGH","RNCGC"];
rtypeMap["RNCH"] = ["RNCHH","RNCHC"];
rtypeMap["RNCI"] = ["RNCIH","RNCIC"];
rtypeMap["RNCJ"] = ["RNCJH","RNCJC"];
rtypeMap["RNCK"] = ["RNCKH","RNCKC"];
rtypeMap["RNCL"] = ["RNCLH","RNCLC"];
rtypeMap["RNCM"] = ["RNCMH","RNCMC"];
rtypeMap["RNCN"] = ["RNCNH","RNCNC"];
rtypeMap["RNCO"] = ["RNCOH","RNCOC"];
rtypeMap["RNCP"] = ["RNCPH","RNCPC"];
rtypeMap["RNCQ"] = ["RNCQH","RNCQC"];
rtypeMap["RNCR"] = ["RNCRH","RNCRC"];
rtypeMap["RNCS"] = ["RNCSH","RNCSC"];
rtypeMap["RNCT"] = ["RNCTH","RNCTC"];
rtypeMap["RNCU"] = ["RNCUH","RNCUC"];


//單式

rtypeMap["R"] = ["RH","RC"];
rtypeMap["HR"] = ["HRH","HRC"];
rtypeMap["OU"] = ["OUH","OUC"];
rtypeMap["HOU"] = ["HOUH","HOUC"];
rtypeMap["M"] = ["MH","MC","MN"];
rtypeMap["HM"] = ["HMH","HMC","HMN"];
rtypeMap["AR"] = ["ARH","ARC"];
rtypeMap["AOU"] = ["AOUO","AOUU"];
rtypeMap["AM"] = ["AMH","AMC","AMN"];
rtypeMap["BR"] = ["BRH","BRC"];
rtypeMap["BOU"] = ["BOUO","BOUU"];
rtypeMap["BM"] = ["BMH","BMC","BMN"];
rtypeMap["CR"] = ["CRH","CRC"];
rtypeMap["COU"] = ["COUO","COUU"];
rtypeMap["CM"] = ["CMH","CMC","CMN"];
rtypeMap["DR"] = ["DRH","DRC"];
rtypeMap["DOU"] = ["DOUO","DOUU"];
rtypeMap["DM"] = ["DMH","DMC","DMN"];
rtypeMap["ER"] = ["ERH","ERC"];
rtypeMap["EOU"] = ["EOUO","EOUU"];
rtypeMap["EM"] = ["EMH","EMC","EMN"];
rtypeMap["FR"] = ["FRH","FRC"];
rtypeMap["FOU"] = ["FOUO","FOUU"];
rtypeMap["FM"] = ["FMH","FMC","FMN"];
rtypeMap["PD"] = ["H1C0","H2C0","H2C1","H3C0","H3C1","H3C2","H4C0","H4C1","H4C2","H4C3","H0C0","H1C1","H2C2","H3C3","H4C4","OVH","H0C1","H0C2","H1C2","H0C3","H1C3","H2C3","H0C4","H1C4","H2C4","H3C4","OVC"];
rtypeMap["HPD"] = ["HH1C0","HH2C0","HH2C1","HH3C0","HH3C1","HH3C2","HH4C0","HH4C1","HH4C2","HH4C3","HH0C0","HH1C1","HH2C2","HH3C3","HH4C4","HOVH","HH0C1","HH0C2","HH1C2","HH0C3","HH1C3","HH2C3","HH0C4","HH1C4","HH2C4","HH3C4","HOVC"];
rtypeMap["T"] = ["T01","T23","T46","OVER"];
rtypeMap["HT"] = ["HT0","HT1","HT2","HTOV"];
rtypeMap["F"] = ["FHH","FHN","FHC","FNH","FNN","FNC","FCH","FCN","FCC"];
rtypeMap["WM"] = ["WMH1","WMH2","WMH3","WMHOV","WMC1","WMC2","WMC3","WMCOV","WM0","WMN"];
rtypeMap["DC"] = ["DCHN","DCCN","DCHC"];
rtypeMap["W3"] = ["W3H","W3C","W3N"];
rtypeMap["BH"] = ["BHH","BHC"];
rtypeMap["WE"] = ["WEH","WEC"];
rtypeMap["WB"] = ["WBH","WBC"];
rtypeMap["PG"] = ["PGFH","PGLH","PGFN","PGFC","PGLC"];
rtypeMap["RC"] = ["RCFH","RCLH","RCFC","RCLC"];
rtypeMap["TS"] = ["TSY","TSN"];
rtypeMap["OUH"] = ["OUHO","OUHU"];
rtypeMap["OUC"] = ["OUCO","OUCU"];
rtypeMap["HOUH"] = ["HOUHO","HOUHU"];
rtypeMap["HOUC"] = ["HOUCO","HOUCU"];
rtypeMap["EO"] = ["EOO","EOE"];
rtypeMap["HEO"] = ["HEOO","HEOE"];
rtypeMap["SFS"] = ["H19","C19","H20","C20","H21","C21"];
rtypeMap["CS"] = ["CSH","CSC"];
rtypeMap["WN"] = ["WNH","WNC"];
rtypeMap["F2G"] = ["F2GH","F2GC","F2GN"];
rtypeMap["F3G"] = ["F3GH","F3GC","F3GN"];
rtypeMap["HG"] = ["HGH","HGC"];
rtypeMap["MG"] = ["MGH","MGC","MGN"];
rtypeMap["SB"] = ["SBH","SBC"];
rtypeMap["FG"] = ["FGS","FGH","FGN","FGP","FGF","FGO"];
rtypeMap["T3G"] = ["T3G1","T3G2","T3GN"];
rtypeMap["T1G"] = ["T1G1","T1G2","T1G3","T1G4","T1G5","T1G6","T1GN"];
rtypeMap["TK"] = ["TKH","TKC"];
rtypeMap["PA"] = ["PAH","PAC"];
rtypeMap["RCD"] = ["RCDH","RCDC"];
rtypeMap["CN"] = ["CNFH","CNLH","CNFN","CNFC","CNLC"];
rtypeMap["CD"] = ["CDFH","CDLH","CDFN","CDFC","CDLC"];
rtypeMap["YC"] = ["YCFH","YCLH","YCFN","YCFC","YCLC"];
rtypeMap["ST"] = ["STFH","STLH","STFN","STFC","STLC"];
rtypeMap["GA"] = ["GAFH","GALH","GAFN","GAFC","GALC"];
rtypeMap["OS"] = ["OSFH","OSLH","OSFN","OSFC","OSLC"];


rtypeMap["HTS"] = ["HTSY","HTSN"];
rtypeMap["EOH"] = ["EOHO","EOHE"];
rtypeMap["EOC"] = ["EOCO","EOCE"];
rtypeMap["HEOH"] = ["HEOHO","HEOHE"];
rtypeMap["HEOC"] = ["HEOCO","HEOCE"];
rtypeMap["MW"] = ["MWH","MWC","MWHOT","MWCOT","MWHPK","MWCPK"];
rtypeMap["MQ"] = ["MQH","MQC","MQHOT","MQCOT","MQHPK","MQCPK"];
rtypeMap["MOUA"] = ["MOUAHO","MOUANO","MOUACO","MOUAHU","MOUANU","MOUACU"];
rtypeMap["MOUB"] = ["MOUBHO","MOUBNO","MOUBCO","MOUBHU","MOUBNU","MOUBCU"];
rtypeMap["MOUC"] = ["MOUCHO","MOUCNO","MOUCCO","MOUCHU","MOUCNU","MOUCCU"];
rtypeMap["MOUD"] = ["MOUDHO","MOUDNO","MOUDCO","MOUDHU","MOUDNU","MOUDCU"];
rtypeMap["MPG"] = ["MPGHH","MPGNH","MPGCH","MPGHC","MPGNC","MPGCC"];
rtypeMap["MTS"] = ["MTSHY","MTSNY","MTSCY","MTSHN","MTSNN","MTSCN"];
rtypeMap["DUA"] = ["DUAHO","DUACO","DUASO","DUAHU","DUACU","DUASU"];
rtypeMap["DUB"] = ["DUBHO","DUBCO","DUBSO","DUBHU","DUBCU","DUBSU"];
rtypeMap["DUC"] = ["DUCHO","DUCCO","DUCSO","DUCHU","DUCCU","DUCSU"];
rtypeMap["DUD"] = ["DUDHO","DUDCO","DUDSO","DUDHU","DUDCU","DUDSU"];
rtypeMap["DS"] = ["DSHY","DSCY","DSSY","DSHN","DSCN","DSSN"];
rtypeMap["DG"] = ["DGHH","DGCH","DGSH","DGHC","DGCC","DGSC"];
rtypeMap["OUEA"] = ["OUEAOO","OUEAOE","OUEAUO","OUEAUE"];
rtypeMap["OUEB"] = ["OUEBOO","OUEBOE","OUEBUO","OUEBUE"];
rtypeMap["OUEC"] = ["OUECOO","OUECOE","OUECUO","OUECUE"];
rtypeMap["OUED"] = ["OUEDOO","OUEDOE","OUEDUO","OUEDUE"];
rtypeMap["OUTA"] = ["OUTAOY","OUTAON","OUTAUY","OUTAUN"];
rtypeMap["OUTB"] = ["OUTBOY","OUTBON","OUTBUY","OUTBUN"];
rtypeMap["OUTC"] = ["OUTCOY","OUTCON","OUTCUY","OUTCUN"];
rtypeMap["OUTD"] = ["OUTDOY","OUTDON","OUTDUY","OUTDUN"];
rtypeMap["OUPA"] = ["OUPAOH","OUPAOC","OUPAUH","OUPAUC"];
rtypeMap["OUPB"] = ["OUPBOH","OUPBOC","OUPBUH","OUPBUC"];
rtypeMap["OUPC"] = ["OUPCOH","OUPCOC","OUPCUH","OUPCUC"];
rtypeMap["OUPD"] = ["OUPDOH","OUPDOC","OUPDUH","OUPDUC"];
rtypeMap["OT"] = ["OTY","OTN"];
rtypeMap["OG"] = ["OGY","OGN"];


//過關
rtypeMap["PR"] = ["PRH","PRC"];
rtypeMap["HPR"] = ["HPRH","HPRC"];
rtypeMap["POU"] = ["POUH","POUC"];
rtypeMap["HPOU"] = ["HPOUH","HPOUC"];
rtypeMap["PAR"] = ["PARH","PARC"];
rtypeMap["PAOU"] = ["PAOUO","PAOUU"];
rtypeMap["PBR"] = ["PBRH","PBRC"];
rtypeMap["PBOU"] = ["PBOUO","PBOUU"];
rtypeMap["PCR"] = ["PCRH","PCRC"];
rtypeMap["PCOU"] = ["PCOUO","PCOUU"];
rtypeMap["PDR"] = ["PDRH","PDRC"];
rtypeMap["PDOU"] = ["PDOUO","PDOUU"];
rtypeMap["PER"] = ["PERH","PERC"];
rtypeMap["PEOU"] = ["PEOUO","PEOUU"];
rtypeMap["PFR"] = ["PFRH","PFRC"];
rtypeMap["PFOU"] = ["PFOUO","PFOUU"];
rtypeMap["POUH"] = ["POUHO","POUHU"];
rtypeMap["POUC"] = ["POUCO","POUCU"];
rtypeMap["HPOUH"] = ["HPOUHO","HPOUHU"];
rtypeMap["HPOUC"] = ["HPOUCO","HPOUCU"];
rtypeMap["PEO"] = ["PEOO","PEOE"];
rtypeMap["HPEO"] = ["HPEOO","HPEOE"];

//new Array("myMarkets","mainMarkets","goalMarkets","corners","otherMarkArray;
modeMap = new Array()
modeMap["R"] = {"Pop_Markets":true,"HDP_OU":true,"first_Half":false,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["HR"] = {"Pop_Markets":true,"HDP_OU":true,"first_Half":true,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["OU"] = {"Pop_Markets":true,"HDP_OU":true,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["HOU"] = {"Pop_Markets":true,"HDP_OU":true,"first_Half":true,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["M"] = {"Pop_Markets":true,"HDP_OU":false,"first_Half":false,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["HM"] = {"Pop_Markets":true,"HDP_OU":false,"first_Half":true,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["AR"] = {"Pop_Markets":true,"HDP_OU":true,"first_Half":false,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["AOU"] = {"Pop_Markets":true,"HDP_OU":true,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["AM"] = {"Pop_Markets":true,"HDP_OU":false,"first_Half":false,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["BR"] = {"Pop_Markets":false,"HDP_OU":true,"first_Half":false,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["BOU"] = {"Pop_Markets":false,"HDP_OU":true,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["BM"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["CR"] = {"Pop_Markets":false,"HDP_OU":true,"first_Half":false,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["COU"] = {"Pop_Markets":false,"HDP_OU":true,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["CM"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["DR"] = {"Pop_Markets":false,"HDP_OU":true,"first_Half":false,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["DOU"] = {"Pop_Markets":false,"HDP_OU":true,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["DM"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["ER"] = {"Pop_Markets":false,"HDP_OU":true,"first_Half":false,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["EOU"] = {"Pop_Markets":false,"HDP_OU":true,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["EM"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["FR"] = {"Pop_Markets":false,"HDP_OU":true,"first_Half":false,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["FOU"] = {"Pop_Markets":false,"HDP_OU":true,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["FM"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":false,"Corners":false,"Specials":false,"Others":false};

modeMap["PD"] = {"Pop_Markets":true,"HDP_OU":false,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["HPD"] = {"Pop_Markets":true,"HDP_OU":false,"first_Half":true,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["T"] = {"Pop_Markets":true,"HDP_OU":false,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["HT"] = {"Pop_Markets":true,"HDP_OU":false,"first_Half":true,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["F"] = {"Pop_Markets":true,"HDP_OU":false,"first_Half":true,"Socore":false,"Corners":false,"Specials":false,"Others":false};

modeMap["WM"] = {"Pop_Markets":true,"HDP_OU":false,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["DC"] = {"Pop_Markets":true,"HDP_OU":false,"first_Half":false,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["W3"] = {"Pop_Markets":false,"HDP_OU":true,"first_Half":false,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["BH"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["WE"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":true,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["WB"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":true,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["PG"] = {"Pop_Markets":true,"HDP_OU":false,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["RC"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":false,"Corners":false,"Specials":true,"Others":true};
modeMap["TS"] = {"Pop_Markets":true,"HDP_OU":false,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["OUH"] = {"Pop_Markets":true,"HDP_OU":true,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["OUC"] = {"Pop_Markets":true,"HDP_OU":true,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["HOUH"] = {"Pop_Markets":true,"HDP_OU":true,"first_Half":true,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["HOUC"] = {"Pop_Markets":true,"HDP_OU":true,"first_Half":true,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["EO"] = {"Pop_Markets":true,"HDP_OU":false,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["HEO"] = {"Pop_Markets":true,"HDP_OU":false,"first_Half":true,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["SFS"] = {"Pop_Markets":true,"HDP_OU":false,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["CS"] = {"Pop_Markets":true,"HDP_OU":false,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["WN"] = {"Pop_Markets":true,"HDP_OU":false,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};

modeMap["F2G"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["F3G"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};

modeMap["HG"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":true,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["MG"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":true,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["SB"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":true,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["FG"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["T3G"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["T1G"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["TK"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":false,"Corners":false,"Specials":true,"Others":false};
modeMap["PA"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":true,"Corners":false,"Specials":true,"Others":false};
modeMap["RCD"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":false,"Corners":false,"Specials":true,"Others":true};
modeMap["CN"] = {"Pop_Markets":true,"HDP_OU":false,"first_Half":false,"Socore":false,"Corners":true,"Specials":true,"Others":false};
modeMap["CD"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":false,"Corners":false,"Specials":true,"Others":true};
modeMap["YC"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":false,"Corners":false,"Specials":true,"Others":true};
modeMap["ST"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":false,"Corners":false,"Specials":true,"Others":true};
modeMap["GA"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":false,"Corners":false,"Specials":true,"Others":true};
modeMap["OS"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":false,"Corners":false,"Specials":true,"Others":true};



modeMap["HTS"] = {"Pop_Markets":true,"HDP_OU":false,"first_Half":true,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["EOH"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["EOC"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["HEOH"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":true,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["HEOC"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":true,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["MW"] = {"Pop_Markets":true,"HDP_OU":false,"first_Half":false,"Socore":false,"Corners":false,"Specials":true,"Others":false};
modeMap["MQ"] = {"Pop_Markets":true,"HDP_OU":false,"first_Half":false,"Socore":false,"Corners":false,"Specials":true,"Others":false};
modeMap["MOU"] = {"Pop_Markets":true,"HDP_OU":true,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["MPG"] = {"Pop_Markets":true,"HDP_OU":false,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["MTS"] = {"Pop_Markets":true,"HDP_OU":false,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["DU"] = {"Pop_Markets":false,"HDP_OU":true,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["DS"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["DG"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["OUE"] = {"Pop_Markets":false,"HDP_OU":true,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["OUT"] = {"Pop_Markets":true,"HDP_OU":true,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["OUP"] = {"Pop_Markets":false,"HDP_OU":true,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["OT"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":false,"Corners":false,"Specials":true,"Others":false};
modeMap["OG"] = {"Pop_Markets":false,"HDP_OU":false,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};


modeMap["PR"] = {"Pop_Markets":true,"HDP_OU":true,"first_Half":false,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["HPR"] = {"Pop_Markets":true,"HDP_OU":true,"first_Half":true,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["POU"] = {"Pop_Markets":true,"HDP_OU":true,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["HPOU"] = {"Pop_Markets":true,"HDP_OU":true,"first_Half":true,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["PAR"] = {"Pop_Markets":true,"HDP_OU":true,"first_Half":false,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["PAOU"] = {"Pop_Markets":true,"HDP_OU":true,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["PBR"] = {"Pop_Markets":false,"HDP_OU":true,"first_Half":false,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["PBOU"] = {"Pop_Markets":false,"HDP_OU":true,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["PCR"] = {"Pop_Markets":false,"HDP_OU":true,"first_Half":false,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["PCOU"] = {"Pop_Markets":false,"HDP_OU":true,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["PDR"] = {"Pop_Markets":false,"HDP_OU":true,"first_Half":false,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["PDOU"] = {"Pop_Markets":false,"HDP_OU":true,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["PER"] = {"Pop_Markets":false,"HDP_OU":true,"first_Half":false,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["PEOU"] = {"Pop_Markets":false,"HDP_OU":true,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["PFR"] = {"Pop_Markets":false,"HDP_OU":true,"first_Half":false,"Socore":false,"Corners":false,"Specials":false,"Others":false};
modeMap["PFOU"] = {"Pop_Markets":false,"HDP_OU":true,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["POUH"] = {"Pop_Markets":true,"HDP_OU":true,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["POUC"] = {"Pop_Markets":true,"HDP_OU":true,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["HPOUH"] = {"Pop_Markets":true,"HDP_OU":true,"first_Half":true,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["HPOUC"] = {"Pop_Markets":true,"HDP_OU":true,"first_Half":true,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["PEO"] = {"Pop_Markets":true,"HDP_OU":false,"first_Half":false,"Socore":true,"Corners":false,"Specials":false,"Others":false};
modeMap["HPEO"] = {"Pop_Markets":true,"HDP_OU":false,"first_Half":true,"Socore":true,"Corners":false,"Specials":false,"Others":false};

var Pwtype = new Array ("PR","HPR","POU","HPOU","PAR","PAOU","PBR","PBOU","PCR","PCOU","PDR","PDOU","PER","PEOU","PFR","PFOU","POUH","POUC","HPOUH","HPOUC","PEO","HPEO")


var FavMap = new Object();
FavMap["R"]  = ["R","RE","PR"] ;
FavMap["HR"] = ["HR","HRE","HPR"] ;
FavMap["OU"] = ["OU","ROU","POU"] ;
FavMap["HOU"] = ["HOU","HROU","HPOU"] ;
FavMap["M"] = ["M","RM"] ;
FavMap["HM"] = ["HM","HRM"] ;
FavMap["AR"] = ["AR","ARE","PAR"] ;
FavMap["AOU"] = ["AOU","AROU","PAOU"] ;
FavMap["AM"] = ["AM","ARM"] ;
FavMap["BR"] = ["BR","BRE","PBR"] ;
FavMap["BOU"] = ["BOU","BROU","PBOU"] ;
FavMap["BM"] = ["BM","BRM"] ;
FavMap["CR"] = ["CR","PCR"] ;
FavMap["COU"] = ["COU","PCOU"] ;
FavMap["CM"] = ["CM"] ;
FavMap["DR"] = ["DR","DRE","PDR"] ;
FavMap["DOU"] = ["DOU","DROU","POU"] ;
FavMap["DM"] = ["DM","DRM"] ;
FavMap["ER"] = ["ER","ERE","PER"] ;
FavMap["EOU"] = ["EOU","EROU","PEOU"] ;
FavMap["EM"] = ["EM","ERM"] ;
FavMap["FR"] = ["FR","PFR"] ;
FavMap["FOU"] = ["FOU","PFOU"] ;
FavMap["FM"] = ["FM","PFM"] ;
FavMap["PD"] = ["PD","RPD"] ;
FavMap["HPD"] = ["HPD","HRPD"] ;
FavMap["T"] = ["T","RT"] ;
FavMap["HT"] = ["HT","HRT"] ;
FavMap["F"] = ["F","RF"] ;
FavMap["WM"] = ["WM","RWM"] ;
FavMap["DC"] = ["DC","RDC"] ;
FavMap["W3"] = ["W3"] ;
FavMap["BH"] = ["BH","RBH"] ;
FavMap["WE"] = ["WE","RWE"] ;
FavMap["WB"] = ["WB","RWB"] ;
FavMap["PG"] = ["PG"] ;
FavMap["RC"] = ["RC"] ;
FavMap["TS"] = ["TS","RTS"] ;
FavMap["OUH"] = ["OUH","ROUH","POUH"] ;
FavMap["OUC"] = ["OUC","ROUC","POUC"] ;
FavMap["HOUH"] = ["HOUH","HRUH","HPOUH"] ;
FavMap["HOUC"] = ["HOUC","HRUC","HPOUC"] ;
FavMap["EO"] = ["EO","REO","PEO"] ;
FavMap["HEO"] = ["HEO","HREO","HPEO"] ;
FavMap["SFS"] = ["SFS"] ;
FavMap["CS"] = ["CS"] ;
FavMap["WN"] = ["WN","RWN"] ;
FavMap["F2G"] = ["F2G"] ;
FavMap["F3G"] = ["F3G"] ;
FavMap["HG"] = ["HG","RHG"] ;
FavMap["MG"] = ["MG","RMG"] ;
FavMap["SB"] = ["SB","RSB"] ;
FavMap["FG"] = ["FG"] ;
FavMap["T3G"] = ["T3G","RT3G"] ;
FavMap["T1G"] = ["T1G","RT1G"] ;
FavMap["TK"] = ["TK"] ;
FavMap["PA"] = ["PA"] ;
FavMap["RCD"] = ["RCD"] ;
FavMap["CN"] = ["CN"] ;
FavMap["CD"] = ["CD"] ;
FavMap["YC"] = ["YC"] ;
FavMap["ST"] = ["ST"] ;
FavMap["GA"] = ["GA"] ;
FavMap["OS"] = ["OS"] ;
FavMap["ARG"] = ["ARG"] ;
FavMap["BRG"] = ["BRG"] ;
FavMap["CRG"] = ["CRG"] ;
FavMap["DRG"] = ["DRG"] ;
FavMap["ERG"] = ["ERG"] ;
FavMap["FRG"] = ["FRG"] ;
FavMap["GRG"] = ["GRG"] ;
FavMap["HRG"] = ["HRG"] ;
FavMap["IRG"] = ["IRG"] ;
FavMap["JRG"] = ["JRG"] ;
FavMap["HTS"] = ["HTS"];
FavMap["RTS2"] = ["RTS2"];
FavMap["EOH"] = ["EOH"];
FavMap["EOC"] = ["EOC"];
FavMap["HEOH"] = ["HEOH"];
FavMap["HEOC"] = ["HEOC"];
FavMap["MW"] = ["MW"];
FavMap["MQ"] = ["MQ"];
FavMap["MOU"] = ["MOU","RMOU"];
FavMap["MPG"] = ["MPG","RMPG"];
FavMap["MTS"] = ["MTS","RMTS"];
FavMap["DU"] = ["DU","RDU"];
FavMap["DS"] = ["DS","RDS"];
FavMap["DG"] = ["DG","RDG"];
FavMap["OUE"] = ["OUE","ROUE"];
FavMap["OUT"] = ["OUT","ROUT"];
FavMap["OUP"] = ["OUP","ROUP"];
FavMap["OT"] = ["OT","ROT"];
FavMap["OG"] = ["OG"];
FavMap["RSHA"] = ["RSHA"];
FavMap["RSHB"] = ["RSHB"];
FavMap["RSHC"] = ["RSHC"];
FavMap["RSHD"] = ["RSHD"];
FavMap["RSHE"] = ["RSHE"];
FavMap["RSHF"] = ["RSHF"];
FavMap["RSHG"] = ["RSHG"];
FavMap["RSHH"] = ["RSHH"];
FavMap["RSHI"] = ["RSHI"];
FavMap["RSHJ"] = ["RSHJ"];
FavMap["RSHK"] = ["RSHK"];
FavMap["RSHL"] = ["RSHL"];
FavMap["RSHM"] = ["RSHM"];
FavMap["RSHN"] = ["RSHN"];
FavMap["RSHO"] = ["RSHO"];
FavMap["RNBA"] = ["RNBA"];
FavMap["RNBB"] = ["RNBB"];
FavMap["RNBC"] = ["RNBC"];
FavMap["RNBD"] = ["RNBD"];
FavMap["RNBE"] = ["RNBE"];
FavMap["RNBF"] = ["RNBF"];
FavMap["RNBG"] = ["RNBG"];
FavMap["RNBH"] = ["RNBH"];
FavMap["RNBI"] = ["RNBI"];
FavMap["RNBJ"] = ["RNBJ"];
FavMap["RNBK"] = ["RNBK"];
FavMap["RNBL"] = ["RNBL"];
FavMap["RNBM"] = ["RNBM"];
FavMap["RNBN"] = ["RNBN"];
FavMap["RNBO"] = ["RNBO"];
FavMap["RNC1"] = ["RNC1"];
FavMap["RNC2"] = ["RNC2"];
FavMap["RNC3"] = ["RNC3"];
FavMap["RNC4"] = ["RNC4"];
FavMap["RNC5"] = ["RNC5"];
FavMap["RNC6"] = ["RNC6"];
FavMap["RNC7"] = ["RNC7"];
FavMap["RNC8"] = ["RNC8"];
FavMap["RNC9"] = ["RNC9"];
FavMap["RNCA"] = ["RNCA"];
FavMap["RNCB"] = ["RNCB"];
FavMap["RNCC"] = ["RNCC"];
FavMap["RNCD"] = ["RNCD"];
FavMap["RNCE"] = ["RNCE"];
FavMap["RNCF"] = ["RNCF"];
FavMap["RNCG"] = ["RNCG"];
FavMap["RNCH"] = ["RNCH"];
FavMap["RNCI"] = ["RNCI"];
FavMap["RNCJ"] = ["RNCJ"];
FavMap["RNCK"] = ["RNCK"];
FavMap["RNCL"] = ["RNCL"];
FavMap["RNCM"] = ["RNCM"];
FavMap["RNCN"] = ["RNCN"];
FavMap["RNCO"] = ["RNCO"];
FavMap["RNCP"] = ["RNCP"];
FavMap["RNCQ"] = ["RNCQ"];
FavMap["RNCR"] = ["RNCR"];
FavMap["RNCS"] = ["RNCS"];
FavMap["RNCT"] = ["RNCT"];
FavMap["RNCU"] = ["RNCU"];
</script>

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
<div id="LoadLayer" class="more_load" style="display:none;"><img src="/images/sports/moregame/loading_pic.gif" width="38" height="38"></div>
<div class="more_three_btn">
	<!------------------------ right buttons ------------------------>
	<table class="more_right_btn">
			<tr><td><input type="button" class="more_btn_re_cn" value="刷新" onClick="btnClickEvent('Refresh');"></td></tr>
			<tr><td><input type="button" class="more_btn_close_cn" value="关闭" onClick="btnClickEvent('Close');"></td></tr>
			<tr><td><input type="button" class="more_btn_back_cn" value="返回顶部" onClick="btnClickEvent('BackToTop');"></td></tr>
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
	<table id="gameInfo" width="100%" border="0" cellspacing="0" cellpadding="0" class="more_bg_table">
		<tr class="more_bg1">
            <td id="title_showtype" name="title_showtype" class="more_live">*LIVE*<img src="/images/sports/moregame/more_n.jpg" class="more_n" *MID_DISPLAY*/></td>
            <td></td>
			<td class="more_date"><span id="title_date" name="title_date">*DATE*</span>&nbsp;&nbsp;&nbsp;<span id="title_time" name="title_time">*TIME*</span></td>
		</tr>
		<tr class="more_bg2">	
			<td class="mo_bg_1"><span id="title_team_h" name="title_team_h">*TEAM_H*</span></td>
            <td class="mo_bg_2">&nbsp;V&nbsp;</td>
            <td class="mo_bg_3"><span id="title_team_c" name="title_team_c">*TEAM_C*</span></td>
		</tr>
	</table>
	
	<div id="gameOver" style="display:none;">
		<table border="0" cellpadding="0" cellspacing="0" class="">
			<tr class="more_no2"><td>
				此赛事暂时停止收注或已关闭
			</td></tr>
		</table>
	</div>
	
	<table id="mod_table" class="more_btn8" border="0" cellpadding="0" cellspacing="0">
		<tr class="more_h">
			<td id="ALL_Markets" class="" onClick="mod_sel('ALL_Markets',this);" onMouseOver="mod_OnOver('ALL_Markets');" onMouseOut="mod_OnOut('ALL_Markets');" >所有盘口</td>
            <td width="5px">&nbsp;</td>
			<td id="Pop_Markets" class="" onClick="mod_sel('Pop_Markets',this);" onMouseOver="mod_OnOver('Pop_Markets');" onMouseOut="mod_OnOut('Pop_Markets');" >热门盘口</td>
            <td width="5px">&nbsp;</td>
			<td id="HDP_OU" class="" onClick="mod_sel('HDP_OU',this);" onMouseOver="mod_OnOver('HDP_OU');" onMouseOut="mod_OnOut('HDP_OU');" >让球&大小</td>
		</tr>
        <tr><td colspan="5" class="more_8_tr"></td></tr>
		<tr class="more_h">
			<td id="first_Half" class="" onClick="mod_sel('first_Half',this);" onMouseOver="mod_OnOver('first_Half');" onMouseOut="mod_OnOut('first_Half');" >上半场</td>
            <td width="5px">&nbsp;</td>
			<td height="16" class="" id="Socore" onClick="mod_sel('Socore',this);" onMouseOver="mod_OnOver('Socore');" onMouseOut="mod_OnOut('Socore');" >比分盘口</td>
            <td width="5px">&nbsp;</td>
			<td id="Specials" class="" onClick="mod_sel('Specials',this);" onMouseOver="mod_OnOver('Specials');" onMouseOut="mod_OnOut('Specials');" >特别玩法</td>
            <td width="5px" style="display:none;">&nbsp;</td>
			<td id="Corners" class="" onClick="mod_sel('Corners',this);" onMouseOver="mod_OnOver('Corners');" onMouseOut="mod_OnOut('Corners');" style="display:none;">角球</td>
            <td width="5px" style="display:none;">&nbsp;</td>
			<td id="Others" class="" onClick="mod_sel('Others',this);" onMouseOver="mod_OnOver('Others');" onMouseOut="mod_OnOut('Others');" style="display:none;">其他盘口</td>
		</tr> 
	</table>

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
			<div id="favorites_R"></div>
			<div id="favorites_HR"></div>
			<div id="favorites_OU"></div>
			<div id="favorites_HOU"></div>
			<div id="favorites_M"></div>
			<div id="favorites_HM"></div>
			<div id="favorites_PD"></div>
			<div id="favorites_HPD"></div>
			<div id="favorites_AR"></div>
			<div id="favorites_AOU"></div>
			<div id="favorites_AM"></div>
			<div id="favorites_T"></div>
			<div id="favorites_HT"></div>
			<div id="favorites_TS"></div>
			<div id="favorites_HTS"></div>
			<div id="favorites_OUH"></div>
			<div id="favorites_OUC"></div>
			<div id="favorites_HOUH"></div>
			<div id="favorites_HOUC"></div>
			<div id="favorites_EO"></div>
			<div id="favorites_HEO"></div>
			<div id="favorites_EOH"></div>
			<div id="favorites_EOC"></div>
			<div id="favorites_HEOH"></div>
			<div id="favorites_HEOC"></div>
			<div id="favorites_PG"></div>
			<div id="favorites_RC"></div>
			<div id="favorites_F"></div>
			<div id="favorites_WM"></div>
			<div id="favorites_DC"></div>
			<div id="favorites_MW"></div>
			<div id="favorites_MQ"></div>
			<div id="favorites_SFS"></div>
			<div id="favorites_CS"></div>
			<div id="favorites_WN"></div>
			<div id="favorites_MOU"></div>
			<div id="favorites_MTS"></div>
			<div id="favorites_OUT"></div>
			<div id="favorites_MPG"></div>
			<div id="favorites_CN"></div>
			<div id="favorites_YC"></div>
			<div id="favorites_GA"></div>
			<div id="favorites_CD"></div>
			<div id="favorites_RCD"></div>
			<div id="favorites_F2G"></div>
			<div id="favorites_F3G"></div>
			<div id="favorites_HG"></div>
			<div id="favorites_MG"></div>
			<div id="favorites_SB"></div>
			<div id="favorites_FG"></div>
			<div id="favorites_T3G"></div>
			<div id="favorites_T1G"></div>
			<div id="favorites_OG"></div>
			<div id="favorites_DU"></div>
			<div id="favorites_DS"></div>
			<div id="favorites_DG"></div>
			<div id="favorites_OUE"></div>
			<div id="favorites_OUP"></div>
			<div id="favorites_W3"></div>
			<div id="favorites_BH"></div>
			<div id="favorites_WE"></div>
			<div id="favorites_WB"></div>
			<div id="favorites_TK"></div>
			<div id="favorites_PA"></div>
			<div id="favorites_OT"></div>
			<div id="favorites_ST"></div>
			<div id="favorites_OS"></div>
			<div id="favorites_BR"></div>
			<div id="favorites_BOU"></div>
			<div id="favorites_BM"></div>
			<div id="favorites_CR"></div>
			<div id="favorites_COU"></div>
			<div id="favorites_CM"></div>
			<div id="favorites_DR"></div>
			<div id="favorites_DOU"></div>
			<div id="favorites_DM"></div>
			<div id="favorites_ER"></div>
			<div id="favorites_EOU"></div>
			<div id="favorites_EM"></div>
			<div id="favorites_FR"></div>
			<div id="favorites_FOU"></div>
			<div id="favorites_FM"></div>


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
			<div id="body_R"></div>
			<div id="body_HR"></div>
			<div id="body_OU"></div>
			<div id="body_HOU"></div>
			<div id="body_M"></div>
			<div id="body_HM"></div>
			<div id="body_PD"></div>
			<div id="body_HPD"></div>
			<div id="body_AR"></div>
			<div id="body_AOU"></div>
			<div id="body_AM"></div>
			<div id="body_T"></div>
			<div id="body_HT"></div>
			<div id="body_TS"></div>
			<div id="body_HTS"></div>
			<div id="body_OUH"></div>
			<div id="body_OUC"></div>
			<div id="body_HOUH"></div>
			<div id="body_HOUC"></div>
			<div id="body_EO"></div>
			<div id="body_HEO"></div>
			<div id="body_EOH"></div>
			<div id="body_EOC"></div>
			<div id="body_HEOH"></div>
			<div id="body_HEOC"></div>
			<div id="body_PG"></div>
			<div id="body_F"></div>
			<div id="body_WM"></div>
			<div id="body_DC"></div>
			<div id="body_MW"></div>
			<div id="body_MQ"></div>
			<div id="body_SFS"></div>
			<div id="body_CS"></div>
			<div id="body_WN"></div>
			<div id="body_MOU"></div>
			<div id="body_MTS"></div>
			<div id="body_OUT"></div>
			<div id="body_MPG"></div>
	</div>

	<!------------------------ main markets ------------------------>
	
	
	
	
	
	<!------------------------ corners ------------------------>
	<table id="head_corners" width="100%" border="0" cellspacing="0" cellpadding="0" class="more_ma">
		<tr onClick="playCssEvent('corners',this);" class="more_hand">
			<td class="more_title3">
					<span id="mark_corners" class="more_up"></span>
					<span style="float: left;">角球</span>
					<span class="more_black"><span id="count_corners" class="more_num">0</span></span>
			</td>
		</tr>
	</table>	
	

	<div id="movie_corners" >
			<div id="body_CN"></div>
	</div>

	<!------------------------ corners ------------------------>
	
	
	
	
    
	<!------------------------ bookings ------------------------>
	<table id="head_bookings" width="100%" border="0" cellspacing="0" cellpadding="0" class="more_ma">
		<tr onClick="playCssEvent('bookings',this);" class="more_hand">
			<td class="more_title3">
					<span id="mark_bookings" class="more_up"></span>
					<span style="float: left;">罚牌</span>
					<span class="more_black"><span id="count_bookings" class="more_num">0</span></span>
			</td>
		</tr>
	</table>	
	

	<div id="movie_bookings" >
			<div id="body_CD"></div>
			<div id="body_RCD"></div>

	</div>

	<!------------------------ bookings ------------------------>





	<!------------------------ goal markets ------------------------>
	<table id="head_goalMarkets" width="100%" border="0" cellspacing="0" cellpadding="0" class="more_ma">
		<tr onClick="playCssEvent('goalMarkets',this);" class="more_hand">
			<td class="more_title3">
					<span id="mark_goalMarkets" class="more_up"></span>
					<span style="float: left;">进球盘口</span>
					<span class="more_black"><span id="count_goalMarkets" class="more_num">0</span></span>
			</td>
		</tr>
	</table>	
	
	
	
	<div id="movie_goalMarkets">
			<div id="body_F2G"></div>
			<div id="body_F3G"></div>
			<div id="body_HG"></div>
			<div id="body_MG"></div>
			<div id="body_SB"></div>
			<div id="body_FG"></div>
			<div id="body_T3G"></div>
			<div id="body_T1G"></div>
			<div id="body_OG"></div>
			<div id="body_DU"></div>
			<div id="body_DS"></div>
			<div id="body_DG"></div>
			<div id="body_OUE"></div>
			<div id="body_OUP"></div>
	</div>
	<!------------------------ goal markets ------------------------>

		
	<!------------------------ specials ------------------------>
	<table id="head_specials" width="100%" border="0" cellspacing="0" cellpadding="0" class="more_ma">
		<tr onClick="playCssEvent('specials',this);" class="more_hand">
			<td class="more_title3">
					<span id="mark_specials" class="more_up"></span>
					<span style="float: left;">特别玩法</span>
					<span class="more_black"><span id="count_specials" class="more_num">0</span></span>
			</td>
		</tr>
	</table>	
	
	
	<div id="movie_specials" >
	</div>

	<!------------------------ specials ------------------------>
		




	<!------------------------ other markets ------------------------>
	<table id="head_otherMarkets" width="100%" border="0" cellspacing="0" cellpadding="0" class="more_ma">
		<tr onClick="playCssEvent('otherMarkets',this);" class="more_hand">
			<td class="more_title3">					
					<span id="mark_otherMarkets" class="more_up"></span>
					<span style="float: left;">其他盘口</span>
					<span class="more_black"><span id="count_otherMarkets" class="more_num">0</span></span>
			</td>
		</tr>
	</table>	
	
	
	<div id="movie_otherMarkets" >
			<div id="body_W3"></div>
			<div id="body_BH"></div>
			<div id="body_WE"></div>
			<div id="body_WB"></div>
			<div id="body_TK"></div>
			<div id="body_PA"></div>
			<div id="body_OT"></div>
			<div id="body_ST"></div>
			<div id="body_OS"></div>
			<div id="body_RC"></div>
			<div id="body_YC"></div>
			<div id="body_GA"></div>
			<div id="body_BR"></div>
			<div id="body_BOU"></div>
			<div id="body_BM"></div>
			<div id="body_CR"></div>
			<div id="body_COU"></div>
			<div id="body_CM"></div>
			<div id="body_DR"></div>
			<div id="body_DOU"></div>
			<div id="body_DM"></div>
			<div id="body_ER"></div>
			<div id="body_EOU"></div>
			<div id="body_EM"></div>
			<div id="body_FR"></div>
			<div id="body_FOU"></div>
			<div id="body_FM"></div>
	</div>
	<!------------------------ other markets ------------------------>
	
	
	
	</td></tr>
</table>
</div>


<div id="div_model" style="display:none;" >
	
			<!---------- R ---------->
		 	<table id="model_R" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th colspan="2" class="more_title4">
							<span style="float: left;">让球</span>
							<span class="more_star_bg"><span id="star_R" name="star_R" onClick="addFavorites('R');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: R -->
				<tr class="*TR_CLASS*">
						<td id="*RH_GID*" onClick="betEvent('*GID*','RH','*IORATIO_RH*','R');" style="cursor:pointer"  class="*TD_CLASS_RH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_middle">*RATIO_RH*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_RH*</span></div></td>
						<td id="*RC_GID*" onClick="betEvent('*GID*','RC','*IORATIO_RC*','R');" style="cursor:pointer"  class="*TD_CLASS_RC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_middle">*RATIO_RC*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_RC*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: R -->
	
			</table>
			<!---------- R ---------->
			
			
			
			<!---------- HR ---------->
<table id="model_HR" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">让球</span>
							<span class="more_og2">&nbsp;- 上半场</span>
							<span class="more_star_bg"><span id="star_HR" name="star_HR" onClick="addFavorites('HR');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: HR -->
				<tr class="*TR_CLASS*">
						<td id="*HRH_HGID*" onClick="betEvent('*HGID*','HRH','*IORATIO_HRH*','HR');" style="cursor:pointer"  class="*TD_CLASS_HRH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_middle">*RATIO_HRH*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_HRH*</span></div></td>
						<td id="*HRC_HGID*" onClick="betEvent('*HGID*','HRC','*IORATIO_HRC*','HR');" style="cursor:pointer"  class="*TD_CLASS_HRC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_middle">*RATIO_HRC*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_HRC*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: HR -->
	
			</table>
			<!---------- HR ---------->
			
			
			
			<!---------- OU ---------->
<table id="model_OU" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">大 / 小</span>
							<span class="more_star_bg"><span id="star_OU" name="star_OU" onClick="addFavorites('OU');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: OU -->
				<tr class="*TR_CLASS*">
						<td id="*OUC_GID*" onClick="betEvent('*GID*','OUC','*IORATIO_OUC*','OU');" style="cursor:pointer"  class="*TD_CLASS_OUC*" width="50%"><div class="more_font"><span class="m_team">大</span><span class="m_middle">*RATIO_OUC*</span><span class="m_red_bet" title="大">*IORATIO_OUC*</span></div></td>
						<td id="*OUH_GID*" onClick="betEvent('*GID*','OUH','*IORATIO_OUH*','OU');" style="cursor:pointer"  class="*TD_CLASS_OUH*" width="50%"><div class="more_font"><span class="m_team">小</span><span class="m_middle">*RATIO_OUH*</span><span class="m_red_bet" title="小">*IORATIO_OUH*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: OU -->
	
			</table>
			<!---------- OU ---------->
			
			
			
			
			<!---------- HOU ---------->
<table id="model_HOU" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">大 / 小</span>
							<span class="more_og2">&nbsp;- 上半场</span>
							<span class="more_star_bg"><span id="star_HOU" name="star_HOU" onClick="addFavorites('HOU');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: HOU -->
				<tr class="*TR_CLASS*">
						<td id="*HOUC_HGID*" onClick="betEvent('*HGID*','HOUC','*IORATIO_HOUC*','HOU');" style="cursor:pointer"  class="*TD_CLASS_HOUC*" width="50%"><div class="more_font"><span class="m_team">大</span><span class="m_middle">*RATIO_HOUC*</span><span class="m_red_bet" title="大">*IORATIO_HOUC*</span></div></td>
						<td id="*HOUH_HGID*" onClick="betEvent('*HGID*','HOUH','*IORATIO_HOUH*','HOU');" style="cursor:pointer"  class="*TD_CLASS_HOUH*" width="50%"><div class="more_font"><span class="m_team">小</span><span class="m_middle">*RATIO_HOUH*</span><span class="m_red_bet" title="小">*IORATIO_HOUH*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: HOU -->
	
			</table>
			<!---------- HOU ---------->
			
			
			
			
			<!---------- M ---------->
<table id="model_M" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="3">
							<span style="float: left;">独赢</span>
							<span class="more_star_bg"><span id="star_M" name="star_M" onClick="addFavorites('M');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: M -->
				<tr class="*TR_CLASS*">
						<td id="*MH_GID*" onClick="betEvent('*GID*','MH','*IORATIO_MH*','M');" style="cursor:pointer"  class="*TD_CLASS_MH*" width="40%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_MH*</span></div></td>
						<td id="*MN_GID*" onClick="betEvent('*GID*','MN','*IORATIO_MN*','M');" style="cursor:pointer"  class="*TD_CLASS_MN*" width="20%"><div class="more_font"><span class="m_team">和局</span><span class="m_red_bet" title="和局">*IORATIO_MN*</span></div></td>
						<td id="*MC_GID*" onClick="betEvent('*GID*','MC','*IORATIO_MC*','M');" style="cursor:pointer"  class="*TD_CLASS_MC*" width="40%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_MC*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: M -->
	
			</table>
			<!---------- M ---------->
			
			
			
			
			<!---------- HM ---------->
<table id="model_HM" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="3">
							<span style="float: left;">独赢</span>
							<span class="more_og2">&nbsp;- 上半场</span>
							<span class="more_star_bg"><span id="star_HM" name="star_HM" onClick="addFavorites('HM');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: HM -->
				<tr class="*TR_CLASS*">
						<td id="*HMH_HGID*" onClick="betEvent('*HGID*','HMH','*IORATIO_HMH*','HM');" style="cursor:pointer"  class="*TD_CLASS_HMH*" width="195"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_HMH*</span></div></td>
						<td id="*HMN_HGID*" onClick="betEvent('*HGID*','HMN','*IORATIO_HMN*','HM');" style="cursor:pointer"  class="*TD_CLASS_HMN*" width="20%"><div class="more_font"><span class="m_team">和局</span><span class="m_red_bet" title="和局">*IORATIO_HMN*</span></div></td>
						<td id="*HMC_HGID*" onClick="betEvent('*HGID*','HMC','*IORATIO_HMC*','HM');" style="cursor:pointer"  class="*TD_CLASS_HMC*" width="40%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_HMC*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: HM -->
	
			</table>
			<!---------- HM ---------->
			
			<!---------- AR ---------->
<table id="model_AR" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">15 分钟盘口: 开场&nbsp;- 14:59 分钟 - 让球</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_AR" name="star_AR" onClick="addFavorites('AR');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: AR -->
				<tr class="*TR_CLASS*">
						<td id="*ARH_GID*" onClick="betEvent('*GID*','ARH','*IORATIO_ARH*','AR');" style="cursor:pointer"  class="*TD_CLASS_ARH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_middle">*RATIO_ARH*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_ARH*</span></div></td>
						<td id="*ARC_GID*" onClick="betEvent('*GID*','ARC','*IORATIO_ARC*','AR');" style="cursor:pointer"  class="*TD_CLASS_ARC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_middle">*RATIO_ARC*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_ARC*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: AR -->
	
			</table>
			<!---------- AR ---------->
			
			
			
			
			
			
			<!---------- AOU ---------->
<table id="model_AOU" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">15 分钟盘口: 开场&nbsp;- 14:59 分钟 - 大 / 小</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_AOU" name="star_AOU" onClick="addFavorites('AOU');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: AOU -->
				<tr class="*TR_CLASS*">
						<td id="*AOUO_GID*" onClick="betEvent('*GID*','AOUO','*IORATIO_AOUO*','AOU');" style="cursor:pointer"  class="*TD_CLASS*_AOUO" width="50%"><div class="more_font"><span class="m_team">大</span><span class="m_middle">*RATIO_AOUO*</span><span class="m_red_bet" title="大">*IORATIO_AOUO*</span></div></td>
						<td id="*AOUU_GID*" onClick="betEvent('*GID*','AOUU','*IORATIO_AOUU*','AOU');" style="cursor:pointer"  class="*TD_CLASS*_AOUU" width="50%"><div class="more_font"><span class="m_team">小</span><span class="m_middle">*RATIO_AOUU*</span><span class="m_red_bet" title="小">*IORATIO_AOUU*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: AOU -->
	
			</table>
			<!---------- AOU ---------->
			
			
			
			
			
			
			<!---------- AM ---------->
<table id="model_AM" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="3">
							<span style="float: left;">15 分钟盘口: 开场&nbsp;- 14:59 分钟 - 独赢</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_AM" name="star_AM" onClick="addFavorites('AM');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: AM -->
				<tr class="*TR_CLASS*">
						<td id="*AMH_GID*" onClick="betEvent('*GID*','AMH','*IORATIO_AMH*','AM');" style="cursor:pointer"  class="*TD_CLASS_AMH*" width="40%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_AMH*</span></div></td>
						<td id="*AMN_GID*" onClick="betEvent('*GID*','AMN','*IORATIO_AMN*','AM');" style="cursor:pointer"  class="*TD_CLASS_AMN*" width="20%"><div class="more_font"><span class="m_team">和局</span><span class="m_red_bet" title="和局">*IORATIO_AMN*</span></div></td>
						<td id="*AMC_GID*" onClick="betEvent('*GID*','AMC','*IORATIO_AMC*','AM');" style="cursor:pointer"  class="*TD_CLASS_AMC*" width="40%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_AMC*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: AM -->
	
			</table>
			<!---------- AM ---------->
			

			<!---------- BR ---------->
<table id="model_BR" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">15 分钟盘口: 15:00 - 29:59 分钟&nbsp;- 让球</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_BR" name="star_BR" onClick="addFavorites('BR');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: BR -->
				<tr class="*TR_CLASS*">
						<td id="*BRH_GID*" onClick="betEvent('*GID*','BRH','*IORATIO_BRH*','BR');" style="cursor:pointer"  class="*TD_CLASS_BRH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_middle">*RATIO_BRH*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_BRH*</span></div></td>
						<td id="*BRC_GID*" onClick="betEvent('*GID*','BRC','*IORATIO_BRC*','BR');" style="cursor:pointer"  class="*TD_CLASS_BRC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_middle">*RATIO_BRC*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_BRC*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: BR -->
	
			</table>
			<!---------- BR ---------->
			
			
			
			
			<!---------- BOU ---------->
<table id="model_BOU" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">15 分钟盘口: 15:00 - 29:59 分钟&nbsp;- 大 / 小</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_BOU" name="star_BOU" onClick="addFavorites('BOU');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: BOU -->
				<tr class="*TR_CLASS*">
						<td id="*BOUO_GID*" onClick="betEvent('*GID*','BOUO','*IORATIO_BOUO*','BOU');" style="cursor:pointer"  class="*TD_CLASS_BOUO*" width="50%"><div class="more_font"><span class="m_team">大</span><span class="m_middle">*RATIO_BOUO*</span><span class="m_red_bet" title="大">*IORATIO_BOUO*</span></div></td>
						<td id="*BOUU_GID*" onClick="betEvent('*GID*','BOUU','*IORATIO_BOUU*','BOU');" style="cursor:pointer"  class="*TD_CLASS_BOUU*" width="50%"><div class="more_font"><span class="m_team">小</span><span class="m_middle">*RATIO_BOUU*</span><span class="m_red_bet" title="小">*IORATIO_BOUU*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: BOU -->
	
			</table>
			<!---------- BOU ---------->
			
			
			
			<!---------- BM ---------->
<table id="model_BM" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="3">
							<span style="float: left;">15 分钟盘口: 15:00 - 29:59 分钟&nbsp;- 独赢</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_BM" name="star_BM" onClick="addFavorites('BM');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: BM -->
				<tr class="*TR_CLASS*">
						<td id="*BMH_GID*" onClick="betEvent('*GID*','BMH','*IORATIO_BMH*','BM');" style="cursor:pointer"  class="*TD_CLASS_BMH*" width="40%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_BMH*</span></div></td>
						<td id="*BMN_GID*" onClick="betEvent('*GID*','BMN','*IORATIO_BMN*','BM');" style="cursor:pointer"  class="*TD_CLASS_BMN*" width="20%"><div class="more_font"><span class="m_team">和局</span><span class="m_red_bet" title="和局">*IORATIO_BMN*</span></div></td>
						<td id="*BMC_GID*" onClick="betEvent('*GID*','BMC','*IORATIO_BMC*','BM');" style="cursor:pointer"  class="*TD_CLASS_BMC*" width="40%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_BMC*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: BM -->
	
			</table>
			<!---------- BM ---------->
			
			
			
			<!---------- CR ---------->
<table id="model_CR" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">15 分钟盘口: 30:00 分钟 - 半场&nbsp;- 让球</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_CR" name="star_CR" onClick="addFavorites('CR');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: CR -->
				<tr class="*TR_CLASS*">
						<td id="*CRH_GID*" onClick="betEvent('*GID*','CRH','*IORATIO_CRH*','CR');" style="cursor:pointer"  class="*TD_CLASS_CRH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_middle">*RATIO_CRH*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_CRH*</span></div></td>
						<td id="*CRC_GID*" onClick="betEvent('*GID*','CRC','*IORATIO_CRC*','CR');" style="cursor:pointer"  class="*TD_CLASS_CRC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_middle">*RATIO_CRC*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_CRC*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: CR -->
	
			</table>
			<!---------- CR ---------->
			
			
			
			
			<!---------- COU ---------->
<table id="model_COU" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">15 分钟盘口: 30:00 分钟 - 半场&nbsp;- 大 / 小</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_COU" name="star_COU" onClick="addFavorites('COU');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: COU -->
				<tr class="*TR_CLASS*">
						<td id="*COUO_GID*" onClick="betEvent('*GID*','COUO','*IORATIO_COUO*','COU');" style="cursor:pointer"  class="*TD_CLASS_COUO*" width="50%"><div class="more_font"><span class="m_team">大</span><span class="m_middle">*RATIO_COUO*</span><span class="m_red_bet" title="大">*IORATIO_COUO*</span></div></td>
						<td id="*COUU_GID*" onClick="betEvent('*GID*','COUU','*IORATIO_COUU*','COU');" style="cursor:pointer"  class="*TD_CLASS_COUU*" width="50%"><div class="more_font"><span class="m_team">小</span><span class="m_middle">*RATIO_COUU*</span><span class="m_red_bet" title="小">*IORATIO_COUU*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: COU -->
	
			</table>
			<!---------- COU ---------->
			
			
			
			<!---------- CM ---------->
<table id="model_CM" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="3">
							<span style="float: left;">15 分钟盘口: 30:00 分钟 - 半场&nbsp;- 独赢</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_CM" name="star_CM" onClick="addFavorites('CM');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: CM -->
				<tr class="*TR_CLASS*">
						<td id="*CMH_GID*" onClick="betEvent('*GID*','CMH','*IORATIO_CMH*','CM');" style="cursor:pointer"  class="*TD_CLASS_CMH*" width="40%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_CMH*</span></div></td>
						<td id="*CMN_GID*" onClick="betEvent('*GID*','CMN','*IORATIO_CMN*','CM');" style="cursor:pointer"  class="*TD_CLASS_CMN*" width="20%"><div class="more_font"><span class="m_team">和局</span><span class="m_red_bet" title="和局">*IORATIO_CMN*</span></div></td>
						<td id="*CMC_GID*" onClick="betEvent('*GID*','CMC','*IORATIO_CMC*','CM');" style="cursor:pointer"  class="*TD_CLASS_CMC*" width="40%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_CMC*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: CM -->
	
			</table>
			<!---------- CM ---------->
			
			
			<!---------- DR ---------->
<table id="model_DR" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">15 分钟盘口: 下半场开始&nbsp;- 59:59 分钟 - 让球</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_DR" name="star_DR" onClick="addFavorites('DR');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: DR -->
				<tr class="*TR_CLASS*">
						<td id="*DRH_GID*" onClick="betEvent('*GID*','DRH','*IORATIO_DRH*','DR');" style="cursor:pointer"  class="*TD_CLASS_DRH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_middle">*RATIO_DRH*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_DRH*</span></div></td>
						<td id="*DRC_GID*" onClick="betEvent('*GID*','DRC','*IORATIO_DRC*','DR');" style="cursor:pointer"  class="*TD_CLASS_DRC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_middle">*RATIO_DRC*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_DRC*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: DR -->
	
			</table>
			<!---------- DR ---------->
			
			
			
			
			
			<!---------- DOU ---------->
<table id="model_DOU" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">15 分钟盘口: 下半场开始&nbsp;- 59:59 分钟 - 大 / 小</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_DOU" name="star_DOU" onClick="addFavorites('DOU');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: DOU -->
				<tr class="*TR_CLASS*">
						<td id="*DOUO_GID*" onClick="betEvent('*GID*','DOUO','*IORATIO_DOUO*','DOU');" style="cursor:pointer"  class="*TD_CLASS_DOUO*" width="50%"><div class="more_font"><span class="m_team">大</span><span class="m_middle">*RATIO_DOUO*</span><span class="m_red_bet" title="大">*IORATIO_DOUO*</span></div></td>
						<td id="*DOUU_GID*" onClick="betEvent('*GID*','DOUU','*IORATIO_DOUU*','DOU');" style="cursor:pointer"  class="*TD_CLASS_DOUU*" width="50%"><div class="more_font"><span class="m_team">小</span><span class="m_middle">*RATIO_DOUU*</span><span class="m_red_bet" title="小">*IORATIO_DOUU*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: DOU -->
	
			</table>
			<!---------- DOU ---------->
			
			
			
			
			<!---------- DM ---------->
<table id="model_DM" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="3">
							<span style="float: left;">15 分钟盘口: 下半场开始&nbsp;- 59:59 分钟 - 独赢</span>
							<span class="more_og"></span>
						<span class="more_star_bg">	<span id="star_DM" name="star_DM" onClick="addFavorites('DM');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: DM -->
				<tr class="*TR_CLASS*">
						<td id="*DMH_GID*" onClick="betEvent('*GID*','DMH','*IORATIO_DMH*','DM');" style="cursor:pointer"  class="*TD_CLASS_DMH*" width="40%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_DMH*</span></div></td>
						<td id="*DMN_GID*" onClick="betEvent('*GID*','DMN','*IORATIO_DMN*','DM');" style="cursor:pointer"  class="*TD_CLASS_DMN*" width="20%"><div class="more_font"><span class="m_team">和局</span><span class="m_red_bet" title="和局">*IORATIO_DMN*</span></div></td>
						<td id="*DMC_GID*" onClick="betEvent('*GID*','DMC','*IORATIO_DMC*','DM');" style="cursor:pointer"  class="*TD_CLASS_DMC*" width="40%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_DMC*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: DM -->
	
			</table>
			<!---------- DM ---------->
			
			
			<!---------- ER ---------->
<table id="model_ER" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">15 分钟盘口: 60:00 - 74:59 分钟&nbsp;- 让球</span>
							<span class="more_og"></span>
						<span class="more_star_bg">	<span id="star_ER" name="star_ER" onClick="addFavorites('ER');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: ER -->
				<tr class="*TR_CLASS*">
						<td id="*ERH_GID*" onClick="betEvent('*GID*','ERH','*IORATIO_ERH*','ER');" style="cursor:pointer"  class="*TD_CLASS_ERH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_middle">*RATIO_ERH*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_ERH*</span></div></td>
						<td id="*ERC_GID*" onClick="betEvent('*GID*','ERC','*IORATIO_ERC*','ER');" style="cursor:pointer"  class="*TD_CLASS_ERC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_middle">*RATIO_ERC*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_ERC*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: ER -->
	
			</table>
			<!---------- ER ---------->
			
			
			
			
			<!---------- EOU ---------->
		 	<table id="model_EOU" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">15 分钟盘口: 60:00 - 74:59 分钟&nbsp;- 大 / 小</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_EOU" name="star_EOU" onClick="addFavorites('EOU');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: EOU -->
				<tr class="*TR_CLASS*">
						<td id="*EOUO_GID*" onClick="betEvent('*GID*','EOUO','*IORATIO_EOUO*','EOU');" style="cursor:pointer"  class="*TD_CLASS_EOUO*" width="50%"><div class="more_font"><span class="m_team">大</span><span class="m_middle">*RATIO_EOUO*</span><span class="m_red_bet" title="大">*IORATIO_EOUO*</span></div></td>
						<td id="*EOUU_GID*" onClick="betEvent('*GID*','EOUU','*IORATIO_EOUU*','EOU');" style="cursor:pointer"  class="*TD_CLASS_EOUU*" width="50%"><div class="more_font"><span class="m_team">小</span><span class="m_middle">*RATIO_EOUU*</span><span class="m_red_bet" title="小">*IORATIO_EOUU*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: EOU -->
	
			</table>
			<!---------- EOU ---------->
			
			
			
			
			<!---------- EM ---------->
		 	<table id="model_EM" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="3">
							<span style="float: left;">15 分钟盘口: 60:00 - 74:59 分钟&nbsp;- 独赢</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_EM" name="star_EM" onClick="addFavorites('EM');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: EM -->
				<tr class="*TR_CLASS*">
						<td id="*EMH_GID*" onClick="betEvent('*GID*','EMH','*IORATIO_EMH*','EM');" style="cursor:pointer"  class="*TD_CLASS_EMH*" width="40%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_EMH*</span></div></td>
						<td id="*EMN_GID*" onClick="betEvent('*GID*','EMN','*IORATIO_EMN*','EM');" style="cursor:pointer"  class="*TD_CLASS_EMN*" width="20%"><div class="more_font"><span class="m_team">和局</span><span class="m_red_bet" title="和局">*IORATIO_EMN*</span></div></td>
						<td id="*EMC_GID*" onClick="betEvent('*GID*','EMC','*IORATIO_EMC*','EM');" style="cursor:pointer"  class="*TD_CLASS_EMC*" width="40%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_EMC*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: EM -->
	
  </table>
			<!---------- EM ---------->


			
			<!---------- FR ---------->
		 	<table id="model_FR" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">15 分钟盘口: 75:00 分钟 - 全场&nbsp;- 让球</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_FR" name="star_FR" onClick="addFavorites('FR');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: FR -->
				<tr class="*TR_CLASS*">
						<td id="*FRH_GID*" onClick="betEvent('*GID*','FRH','*IORATIO_FRH*','FR');" style="cursor:pointer"  class="*TD_CLASS_FRH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_middle">*RATIO_FRH*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_FRH*</span></div></td>
						<td id="*FRC_GID*" onClick="betEvent('*GID*','FRC','*IORATIO_FRC*','FR');" style="cursor:pointer"  class="*TD_CLASS_FRC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_middle">*RATIO_FRC*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_FRC*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: FR -->
	
			</table>
			<!---------- FR ---------->
			
			
			
			
			
			<!---------- FOU ---------->
		 	<table id="model_FOU" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">15 分钟盘口: 75:00 分钟 - 全场&nbsp;- 大 / 小</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_FOU" name="star_FOU" onClick="addFavorites('FOU');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: FOU -->
				<tr class="*TR_CLASS*">
						<td id="*FOUO_GID*" onClick="betEvent('*GID*','FOUO','*IORATIO_FOUO*','FOU');" style="cursor:pointer"  class="*TD_CLASS_FOUO*" width="50%"><div class="more_font"><span class="m_team">大</span><span class="m_middle">*RATIO_FOUO*</span><span class="m_red_bet" title="大">*IORATIO_FOUO*</span></div></td>
						<td id="*FOUU_GID*" onClick="betEvent('*GID*','FOUU','*IORATIO_FOUU*','FOU');" style="cursor:pointer"  class="*TD_CLASS_FOUU*" width="50%"><div class="more_font"><span class="m_team">小</span><span class="m_middle">*RATIO_FOUU*</span><span class="m_red_bet" title="小">*IORATIO_FOUU*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: FOU -->
	
			</table>
			<!---------- FOU ---------->
			
			
			
		

						
			<!---------- FM ---------->
		 	<table id="model_FM" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="3">
							<span style="float: left;">15 分钟盘口: 75:00 分钟 - 全场&nbsp;- 独赢</span>
							<span class="more_og"></span>
						<span class="more_star_bg">	<span id="star_FM" name="star_FM" onClick="addFavorites('FM');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: FM -->
				<tr class="*TR_CLASS*">
						<td id="*FMH_GID*" onClick="betEvent('*GID*','FMH','*IORATIO_FMH*','FM');" style="cursor:pointer"  class="*TD_CLASS_FMH*" width="40%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_FMH*</span></div></td>
						<td id="*FMN_GID*" onClick="betEvent('*GID*','FMN','*IORATIO_FMN*','FM');" style="cursor:pointer"  class="*TD_CLASS_FMN*" width="20%"><div class="more_font"><span class="m_team">和局</span><span class="m_red_bet" title="和局">*IORATIO_FMN*</span></div></td>
						<td id="*FMC_GID*" onClick="betEvent('*GID*','FMC','*IORATIO_FMC*','FM');" style="cursor:pointer"  class="*TD_CLASS_FMC*" width="40%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_FMC*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: FM -->
	
			</table>
			<!---------- FM ---------->

	
			
			<!---------- PD ---------->
		 	<table id="model_PD" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="5">
							<span style="float: left;">波胆</span>
							<span class="more_star_bg"><span id="star_PD" name="star_PD" onClick="addFavorites('PD');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: PD -->
				<tr class="*TR_CLASS*">
						<td colspan="2" class="game_team"><span>*TEAM_H*</span></td>
						<td colspan="1" class="game_team"><span>和局</span></td>
						<td colspan="2" class="game_team"><span>*TEAM_C*</span></td>
				</tr>
				
				
				<tr class="more_white">
						<td id="*H1C0_GID*" onClick="betEvent('*GID*','H1C0','*IORATIO_H1C0*','PD');" style="cursor:pointer"  class="*TD_CLASS_H1C0*" width="20%"><span style="float: left;">1 - 0</span><span class="m_red2" title="1 - 0">*IORATIO_H1C0*</span></td>
						<td id="*H2C0_GID*" onClick="betEvent('*GID*','H2C0','*IORATIO_H2C0*','PD');" style="cursor:pointer"  class="*TD_CLASS_H2C0*" width="20%"><span style="float: left;">2 - 0</span><span class="m_red2" title="2 - 0">*IORATIO_H2C0*</span></td>
						<td id="*H0C0_GID*" onClick="betEvent('*GID*','H0C0','*IORATIO_H0C0*','PD');" style="cursor:pointer"  class="*TD_CLASS_H0C0*" width="20%"><span style="float: left;">0 - 0</span><span class="m_red2" title="0 - 0">*IORATIO_H0C0*</span></td>
						<td id="*H0C1_GID*" onClick="betEvent('*GID*','H0C1','*IORATIO_H0C1*','PD');" style="cursor:pointer"  class="*TD_CLASS_H0C1*" width="20%"><span style="float: left;">0 - 1</span><span class="m_red2" title="0 - 1">*IORATIO_H0C1*</span></td>
						<td id="*H0C2_GID*" onClick="betEvent('*GID*','H0C2','*IORATIO_H0C2*','PD');" style="cursor:pointer"  class="*TD_CLASS_H0C2*" width="20%"><span style="float: left;">0 - 2</span><span class="m_red2" title="0 - 2">*IORATIO_H0C2*</span></td>
				</tr>                                                                                                       
				                                                                                                            
				<tr class="more_color">                                                                                     
						<td id="*H2C1_GID*" onClick="betEvent('*GID*','H2C1','*IORATIO_H2C1*','PD');" style="cursor:pointer"  class="*TD_CLASS_H2C1*" ><span style="float: left;">2 - 1</span><span class="m_red2" title="2 - 1">*IORATIO_H2C1*</span></td>
						<td id="*H3C0_GID*" onClick="betEvent('*GID*','H3C0','*IORATIO_H3C0*','PD');" style="cursor:pointer"  class="*TD_CLASS_H3C0*" ><span style="float: left;">3 - 0</span><span class="m_red2" title="3 - 0">*IORATIO_H3C0*</span></td>
						<td id="*H1C1_GID*" onClick="betEvent('*GID*','H1C1','*IORATIO_H1C1*','PD');" style="cursor:pointer"  class="*TD_CLASS_H1C1*" ><span style="float: left;">1 - 1</span><span class="m_red2" title="1 - 1">*IORATIO_H1C1*</span></td>
						<td id="*H1C2_GID*" onClick="betEvent('*GID*','H1C2','*IORATIO_H1C2*','PD');" style="cursor:pointer"  class="*TD_CLASS_H1C2*" ><span style="float: left;">1 - 2</span><span class="m_red2" title="1 - 2">*IORATIO_H1C2*</span></td>
						<td id="*H0C3_GID*" onClick="betEvent('*GID*','H0C3','*IORATIO_H0C3*','PD');" style="cursor:pointer"  class="*TD_CLASS_H0C3*" ><span style="float: left;">0 - 3</span><span class="m_red2" title="0 - 3">*IORATIO_H0C3*</span></td>
				</tr>                                                                                                       
				                                                                                                            
				<tr class="more_white">                                                                                     
						<td id="*H3C1_GID*" onClick="betEvent('*GID*','H3C1','*IORATIO_H3C1*','PD');" style="cursor:pointer"  class="*TD_CLASS_H3C1*" ><span style="float: left;">3 - 1</span><span class="m_red2" title="3 - 1">*IORATIO_H3C1*</span></td>
						<td id="*H3C2_GID*" onClick="betEvent('*GID*','H3C2','*IORATIO_H3C2*','PD');" style="cursor:pointer"  class="*TD_CLASS_H3C2*" ><span style="float: left;">3 - 2</span><span class="m_red2" title="3 - 2">*IORATIO_H3C2*</span></td>
						<td id="*H2C2_GID*" onClick="betEvent('*GID*','H2C2','*IORATIO_H2C2*','PD');" style="cursor:pointer"  class="*TD_CLASS_H2C2*" ><span style="float: left;">2 - 2</span><span class="m_red2" title="2 - 2">*IORATIO_H2C2*</span></td>
						<td id="*H1C3_GID*" onClick="betEvent('*GID*','H1C3','*IORATIO_H1C3*','PD');" style="cursor:pointer"  class="*TD_CLASS_H1C3*" ><span style="float: left;">1 - 3</span><span class="m_red2" title="1 - 3">*IORATIO_H1C3*</span></td>
						<td id="*H2C3_GID*" onClick="betEvent('*GID*','H2C3','*IORATIO_H2C3*','PD');" style="cursor:pointer"  class="*TD_CLASS_H2C3*" ><span style="float: left;">2 - 3</span><span class="m_red2" title="2 - 3">*IORATIO_H2C3*</span></td>
				</tr>                                                                                                       
				                                                                                                            
				<tr class="more_color">                                                                                     
						<td id="*H4C0_GID*" onClick="betEvent('*GID*','H4C0','*IORATIO_H4C0*','PD');" style="cursor:pointer"  class="*TD_CLASS_H4C0*" ><span style="float: left;">4 - 0</span><span class="m_red2" title="4 - 0">*IORATIO_H4C0*</span></td>
						<td id="*H4C1_GID*" onClick="betEvent('*GID*','H4C1','*IORATIO_H4C1*','PD');" style="cursor:pointer"  class="*TD_CLASS_H4C1*" ><span style="float: left;">4 - 1</span><span class="m_red2" title="4 - 1">*IORATIO_H4C1*</span></td>
						<td id="*H3C3_GID*" onClick="betEvent('*GID*','H3C3','*IORATIO_H3C3*','PD');" style="cursor:pointer"  class="*TD_CLASS_H3C3*" ><span style="float: left;">3 - 3</span><span class="m_red2" title="3 - 3">*IORATIO_H3C3*</span></td>
						<td id="*H0C4_GID*" onClick="betEvent('*GID*','H0C4','*IORATIO_H0C4*','PD');" style="cursor:pointer"  class="*TD_CLASS_H0C4*" ><span style="float: left;">0 - 4</span><span class="m_red2" title="0 - 4">*IORATIO_H0C4*</span></td>
						<td id="*H1C4_GID*" onClick="betEvent('*GID*','H1C4','*IORATIO_H1C4*','PD');" style="cursor:pointer"  class="*TD_CLASS_H1C4*" ><span style="float: left;">1 - 4</span><span class="m_red2" title="1 - 4">*IORATIO_H1C4*</span></td>
				</tr>
				
				<tr class="more_white">
						<td id="*H4C2_GID*" onClick="betEvent('*GID*','H4C2','*IORATIO_H4C2*','PD');" style="cursor:pointer"  class="*TD_CLASS_H4C2*" ><span style="float: left;">4 - 2</span><span class="m_red2" title="4 - 2">*IORATIO_H4C2*</span></td>
						<td id="*H4C3_GID*" onClick="betEvent('*GID*','H4C3','*IORATIO_H4C3*','PD');" style="cursor:pointer"  class="*TD_CLASS_H4C3*" ><span style="float: left;">4 - 3</span><span class="m_red2" title="4 - 3">*IORATIO_H4C3*</span></td>
						<td id="*H4C4_GID*" onClick="betEvent('*GID*','H4C4','*IORATIO_H4C4*','PD');" style="cursor:pointer"  class="*TD_CLASS_H4C4*" ><span style="float: left;">4 - 4</span><span class="m_red2" title="4 - 4">*IORATIO_H4C4*</span></td>
						<td id="*H2C4_GID*" onClick="betEvent('*GID*','H2C4','*IORATIO_H2C4*','PD');" style="cursor:pointer"  class="*TD_CLASS_H2C4*" ><span style="float: left;">2 - 4</span><span class="m_red2" title="2 - 4">*IORATIO_H2C4*</span></td>
						<td id="*H3C4_GID*" onClick="betEvent('*GID*','H3C4','*IORATIO_H3C4*','PD');" style="cursor:pointer"  class="*TD_CLASS_H3C4*" ><span style="float: left;">3 - 4</span><span class="m_red2" title="3 - 4">*IORATIO_H3C4*</span></td>
				</tr>
				
				<tr class="more_color">
						<td colspan="5">
                             <table border="0" cellpadding="0" cellspacing="0" class="mo_bor_bom">
                               <tr class="more_color">
                                 <td width="30%">&nbsp;</td>
                                 <td width="40%" id="*OVH_GID*" onClick="betEvent('*GID*','OVH','*IORATIO_OVH*','PD');" class="*TD_CLASS_OVH*" style=" border-left:1px solid #C5B0A3; border-right:1px solid #C5B0A3; cursor:pointer;"><span class="m_left">其他比分</span><span class="m_red2" title="其他比分">*IORATIO_OVH*</span></td>
                                 <td width="30%" class="more_other">&nbsp;</td>
                              </tr>
                            </table>
                        </td>
				</tr>
				
				<!-- END DYNAMIC BLOCK: PD -->
	
			</table>
			<!---------- PD ---------->
			
			
			
			
			<!---------- HPD ---------->
		 	<table id="model_HPD" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="5">
							<span style="float: left;">波胆</span>
							<span class="more_og2">&nbsp;- 上半场</span>
							<span class="more_star_bg"><span id="star_HPD" name="star_HPD" onClick="addFavorites('HPD');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: HPD -->
				<tr class="*TR_CLASS*">
						<td colspan="2" class="game_team"><span>*TEAM_H*</span></td>
						<td colspan="1" class="game_team"><span>和局</span></td>
						<td colspan="2" class="game_team"><span>*TEAM_C*</span></td>
				</tr>
				
				
				<tr class="more_white">
						<td id="*HH1C0_HGID*" onClick="betEvent('*HGID*','HH1C0','*IORATIO_HH1C0*','HPD');" style="cursor:pointer"  class="*TD_CLASS_HH1C0*" width="20%"><span style="float: left;">1 - 0</span><span class="m_red2" title="1 - 0">*IORATIO_HH1C0*</span></td>
						<td id="*HH2C0_HGID*" onClick="betEvent('*HGID*','HH2C0','*IORATIO_HH2C0*','HPD');" style="cursor:pointer"  class="*TD_CLASS_HH2C0*" width="20%"><span style="float: left;">2 - 0</span><span class="m_red2" title="2 - 0">*IORATIO_HH2C0*</span></td>
						<td id="*HH0C0_HGID*" onClick="betEvent('*HGID*','HH0C0','*IORATIO_HH0C0*','HPD');" style="cursor:pointer"  class="*TD_CLASS_HH0C0*" width="20%"><span style="float: left;">0 - 0</span><span class="m_red2" title="0 - 0">*IORATIO_HH0C0*</span></td>
						<td id="*HH0C1_HGID*" onClick="betEvent('*HGID*','HH0C1','*IORATIO_HH0C1*','HPD');" style="cursor:pointer"  class="*TD_CLASS_HH0C1*" width="20%"><span style="float: left;">0 - 1</span><span class="m_red2" title="0 - 1">*IORATIO_HH0C1*</span></td>
						<td id="*HH0C2_HGID*" onClick="betEvent('*HGID*','HH0C2','*IORATIO_HH0C2*','HPD');" style="cursor:pointer"  class="*TD_CLASS_HH0C2*" width="20%"><span style="float: left;">0 - 2</span><span class="m_red2" title="0 - 2">*IORATIO_HH0C2*</span></td>
				</tr>                                                                                                           
				                                                                                                                
				<tr class="more_color">                                                                                         
						<td id="*HH2C1_HGID*" onClick="betEvent('*HGID*','HH2C1','*IORATIO_HH2C1*','HPD');" style="cursor:pointer"  class="*TD_CLASS_HH2C1*" ><span style="float: left;">2 - 1</span><span class="m_red2" title="2 - 1">*IORATIO_HH2C1*</span></td>
						<td id="*HH3C0_HGID*" onClick="betEvent('*HGID*','HH3C0','*IORATIO_HH3C0*','HPD');" style="cursor:pointer"  class="*TD_CLASS_HH3C0*" ><span style="float: left;">3 - 0</span><span class="m_red2" title="3 - 0">*IORATIO_HH3C0*</span></td>
						<td id="*HH1C1_HGID*" onClick="betEvent('*HGID*','HH1C1','*IORATIO_HH1C1*','HPD');" style="cursor:pointer"  class="*TD_CLASS_HH1C1*" ><span style="float: left;">1 - 1</span><span class="m_red2" title="1 - 1">*IORATIO_HH1C1*</span></td>
						<td id="*HH1C2_HGID*" onClick="betEvent('*HGID*','HH1C2','*IORATIO_HH1C2*','HPD');" style="cursor:pointer"  class="*TD_CLASS_HH1C2*" ><span style="float: left;">1 - 2</span><span class="m_red2" title="1 - 2">*IORATIO_HH1C2*</span></td>
						<td id="*HH0C3_HGID*" onClick="betEvent('*HGID*','HH0C3','*IORATIO_HH0C3*','HPD');" style="cursor:pointer"  class="*TD_CLASS_HH0C3*" ><span style="float: left;">0 - 3</span><span class="m_red2" title="0 - 3">*IORATIO_HH0C3*</span></td>
				</tr>                                                                                                           
				                                                                                                                
				<tr class="more_white">                                                                                         
						<td id="*HH3C1_HGID*" onClick="betEvent('*HGID*','HH3C1','*IORATIO_HH3C1*','HPD');" style="cursor:pointer"  class="*TD_CLASS_HH3C1*" ><span style="float: left;">3 - 1</span><span class="m_red2" title="3 - 1">*IORATIO_HH3C1*</span></td>
						<td id="*HH3C2_HGID*" onClick="betEvent('*HGID*','HH3C2','*IORATIO_HH3C2*','HPD');" style="cursor:pointer"  class="*TD_CLASS_HH3C2*" ><span style="float: left;">3 - 2</span><span class="m_red2" title="3 - 2">*IORATIO_HH3C2*</span></td>
						<td id="*HH2C2_HGID*" onClick="betEvent('*HGID*','HH2C2','*IORATIO_HH2C2*','HPD');" style="cursor:pointer"  class="*TD_CLASS_HH2C2*" ><span style="float: left;">2 - 2</span><span class="m_red2" title="2 - 2">*IORATIO_HH2C2*</span></td>
						<td id="*HH1C3_HGID*" onClick="betEvent('*HGID*','HH1C3','*IORATIO_HH1C3*','HPD');" style="cursor:pointer"  class="*TD_CLASS_HH1C3*" ><span style="float: left;">1 - 3</span><span class="m_red2" title="1 - 3">*IORATIO_HH1C3*</span></td>
						<td id="*HH2C3_HGID*" onClick="betEvent('*HGID*','HH2C3','*IORATIO_HH2C3*','HPD');" style="cursor:pointer"  class="*TD_CLASS_HH2C3*" ><span style="float: left;">2 - 3</span><span class="m_red2" title="2 - 3">*IORATIO_HH2C3*</span></td>
				</tr>                                                                                                           
				                                                                                                                
                <tr class="more_color">                                                                                         
						<td colspan="2">&nbsp;</td>
						<td id="*HH3C3_HGID*" onClick="betEvent('*HGID*','HH3C3','*IORATIO_HH3C3*','HPD');" style="cursor:pointer"  class="*TD_CLASS_HH3C3*" ><span style="float: left;">3 - 3</span><span class="m_red2" title="3 - 3">*IORATIO_HH3C3*</span></td>
						<td colspan="2">&nbsp;</td>
				</tr>
				
                <!--<tr class="more_color">                                                                                         
						<td id="*HH4C0_HGID*" onClick="betEvent('*HGID*','HH4C0','*IORATIO_HH4C0*','HPD');" style="cursor:pointer"  class="*TD_CLASS_HH4C0*" ><span style="float: left;">4 - 0</span><span class="m_red2" title="4 - 0">*IORATIO_HH4C0*</span></td>
						<td id="*HH4C1_HGID*" onClick="betEvent('*HGID*','HH4C1','*IORATIO_HH4C1*','HPD');" style="cursor:pointer"  class="*TD_CLASS_HH4C1*" ><span style="float: left;">4 - 1</span><span class="m_red2" title="4 - 1">*IORATIO_HH4C1*</span></td>
						<td id="*HH3C3_HGID*" onClick="betEvent('*HGID*','HH3C3','*IORATIO_HH3C3*','HPD');" style="cursor:pointer"  class="*TD_CLASS_HH3C3*" ><span style="float: left;">3 - 3</span><span class="m_red2" title="3 - 3">*IORATIO_HH3C3*</span></td>
						<td id="*HH0C4_HGID*" onClick="betEvent('*HGID*','HH0C4','*IORATIO_HH0C4*','HPD');" style="cursor:pointer"  class="*TD_CLASS_HH0C4*" ><span style="float: left;">0 - 4</span><span class="m_red2" title="0 - 4">*IORATIO_HH0C4*</span></td>
						<td id="*HH1C4_HGID*" onClick="betEvent('*HGID*','HH1C4','*IORATIO_HH1C4*','HPD');" style="cursor:pointer"  class="*TD_CLASS_HH1C4*" ><span style="float: left;">1 - 4</span><span class="m_red2" title="1 - 4">*IORATIO_HH1C4*</span></td>
				</tr>
				
				<tr class="more_white">
						<td id="*HH4C2_HGID*" onClick="betEvent('*HGID*','HH4C2','*IORATIO_HH4C2*','HPD');" style="cursor:pointer"  class="*TD_CLASS_HH4C2*" ><span style="float: left;">4 - 2</span><span class="m_red2" title="4 - 2">*IORATIO_HH4C2*</span></td>
						<td id="*HH4C3_HGID*" onClick="betEvent('*HGID*','HH4C3','*IORATIO_HH4C3*','HPD');" style="cursor:pointer"  class="*TD_CLASS_HH4C3*" ><span style="float: left;">4 - 3</span><span class="m_red2" title="4 - 3">*IORATIO_HH4C3*</span></td>
						<td id="*HH4C4_HGID*" onClick="betEvent('*HGID*','HH4C4','*IORATIO_HH4C4*','HPD');" style="cursor:pointer"  class="*TD_CLASS_HH4C4*" ><span style="float: left;">4 - 4</span><span class="m_red2" title="4 - 4">*IORATIO_HH4C4*</span></td>
						<td id="*HH2C4_HGID*" onClick="betEvent('*HGID*','HH2C4','*IORATIO_HH2C4*','HPD');" style="cursor:pointer"  class="*TD_CLASS_HH2C4*" ><span style="float: left;">2 - 4</span><span class="m_red2" title="2 - 4">*IORATIO_HH2C4*</span></td>
						<td id="*HH3C4_HGID*" onClick="betEvent('*HGID*','HH3C4','*IORATIO_HH3C4*','HPD');" style="cursor:pointer"  class="*TD_CLASS_HH3C4*" ><span style="float: left;">3 - 4</span><span class="m_red2" title="3 - 4">*IORATIO_HH3C4*</span></td>
				</tr>&nbsp;-->
               		
				<tr class="more_white">
						<td colspan="5">
                             <table border="0" cellpadding="0" cellspacing="0" class="mo_bor_bom">
                               <tr class="more_white">
                                 <td width="30%">&nbsp;</td>
                                 <td width="40%" id="*HOVH_HGID*" onClick="betEvent('*HGID*','HOVH','*IORATIO_HOVH*','HPD');" class="*TD_CLASS_HOVH*" style=" border-left:1px solid #C5B0A3; border-right:1px solid #C5B0A3; cursor:pointer;"><span class="m_left">其他比分</span><span class="m_red2" title="其他比分">*IORATIO_HOVH*</span></td>
                                 <td width="30%" class="more_other">&nbsp;</td>
                              </tr>
                            </table>
                        </td>
				</tr>
				<!-- END DYNAMIC BLOCK: HPD -->
	
			</table>
			<!---------- HPD ---------->
			
	
			
			
			<!---------- T ---------->
		 	<table id="model_T" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="4">
							<span style="float: left;">总进球数</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_T" name="star_T" onClick="addFavorites('T');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: T -->
				<tr class="more_white">
						<td id="*T01_GID*" onClick="betEvent('*GID*','0~1','*IORATIO_T01*','T');" style="cursor:pointer"  class="*TD_CLASS_T01*" width="25%"><span class="m_left">0 - 1</span><span class="m_red" title="0 - 1">*IORATIO_T01*</span></td>
						<td id="*T23_GID*" onClick="betEvent('*GID*','2~3','*IORATIO_T23*','T');" style="cursor:pointer"  class="*TD_CLASS_T23*" width="25%"><span class="m_left">2 - 3</span><span class="m_red" title="2 - 3">*IORATIO_T23*</span></td>
						<td id="*T46_GID*" onClick="betEvent('*GID*','4~6','*IORATIO_T46*','T');" style="cursor:pointer"  class="*TD_CLASS_T46*" width="25%"><span class="m_left">4 - 6</span><span class="m_red" title="4 - 6">*IORATIO_T46*</span></td>
						<td id="*OVER_GID*" onClick="betEvent('*GID*','OVER','*IORATIO_OVER*','T');" style="cursor:pointer"  class="*TD_CLASS_OVER*" width="25%"><span class="m_left">7或以上</span><span class="m_red" title="7或以上">*IORATIO_OVER*</span></td>
				</tr>
				<!-- END DYNAMIC BLOCK: T -->
	
			</table>
			<!---------- T ---------->
			
			
			
			
			
			<!---------- HT ---------->
		 	<table id="model_HT" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="4">
							<span style="float: left;">总进球数</span>
							<span class="more_og2">&nbsp;- 上半场</span>
						    <span class="more_star_bg"><span id="star_HT" name="star_HT" onClick="addFavorites('HT');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: HT -->
				<tr class="more_white">
						<td id="*HT0_HGID*" onClick="betEvent('*HGID*','HT0','*IORATIO_HT0*','HT');" style="cursor:pointer"  class="*TD_CLASS_HT0*" width="25%"><span class="m_left">0</span><span class="m_red" title="0">*IORATIO_HT0*</span></td>
						<td id="*HT1_HGID*" onClick="betEvent('*HGID*','HT1','*IORATIO_HT1*','HT');" style="cursor:pointer"  class="*TD_CLASS_HT1*" width="25%"><span class="m_left">1</span><span class="m_red" title="1">*IORATIO_HT1*</span></td>
						<td id="*HT2_HGID*" onClick="betEvent('*HGID*','HT2','*IORATIO_HT2*','HT');" style="cursor:pointer"  class="*TD_CLASS_HT2*" width="25%"><span class="m_left">2</span><span class="m_red" title="2">*IORATIO_HT2*</span></td>
						<td id="*HTOV_HGID*" onClick="betEvent('*HGID*','HTOV','*IORATIO_HTOV*','HT');" style="cursor:pointer"  class="*TD_CLASS_HTOV*" width="25%"><span class="m_left">3或以上</span><span class="m_red" title="3或以上">*IORATIO_HTOV*</span></td>
				</tr>
				<!-- END DYNAMIC BLOCK: HT -->
	
			</table>
			<!---------- HT ---------->
			
			
			
			
			
			<!---------- F ---------->
		 	<table id="model_F" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="3">
							<span style="float: left;">半场 / 全场</span>
							<span class="more_og"></span>
						    <span class="more_star_bg"><span id="star_F" name="star_F" onClick="addFavorites('F');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: F -->
				<tr>
						<td class="game_team"><span>*TEAM_H*</span></td>
						<td class="game_team"><span>和局</span></td>
						<td class="game_team"><span>*TEAM_C*</span></td>	
				</tr>
				
				
				<tr class="more_white">
						<td id="*FHH_GID*" onClick="betEvent('*GID*','FHH','*IORATIO_FHH*','F');" style="cursor:pointer"  class="*TD_CLASS_FHH*" width="35%"><span class="m_left">主队 / 主队</span><span class="m_red" title="主队 / 主队">*IORATIO_FHH*</span></td>
						<td id="*FNH_GID*" onClick="betEvent('*GID*','FNH','*IORATIO_FNH*','F');" style="cursor:pointer"  class="*TD_CLASS_FNH*" width="30%"><span class="m_left">和局 / 主队</span><span class="m_red" title="和局 / 主队">*IORATIO_FNH*</span></td>
						<td id="*FCH_GID*" onClick="betEvent('*GID*','FCH','*IORATIO_FCH*','F');" style="cursor:pointer"  class="*TD_CLASS_FCH*" width="35%"><span class="m_left">客队 / 主队</span><span class="m_red" title="客队 / 主队">*IORATIO_FCH*</span></td>
                                                                                                                
				</tr>                                                                                                   
				                                                                                                        
				<tr class="more_color">                                                                                 
						<td id="*FHN_GID*" onClick="betEvent('*GID*','FHN','*IORATIO_FHN*','F');" style="cursor:pointer"  class="*TD_CLASS_FHN*" ><span class="m_left">主队 / 和局</span><span class="m_red" title="主队 / 和局">*IORATIO_FHN*</span></td>
						<td id="*FNN_GID*" onClick="betEvent('*GID*','FNN','*IORATIO_FNN*','F');" style="cursor:pointer"  class="*TD_CLASS_FNN*" ><span class="m_left">和局 / 和局</span><span class="m_red" title="和局 / 和局">*IORATIO_FNN*</span></td>
						<td id="*FCN_GID*" onClick="betEvent('*GID*','FCN','*IORATIO_FCN*','F');" style="cursor:pointer"  class="*TD_CLASS_FCN*" ><span class="m_left">客队 / 和局</span><span class="m_red" title="客队 / 和局">*IORATIO_FCN*</span></td>
                                                                                                                
				</tr>                                                                                                   
				                                                                                                        
				<tr class="more_white">                                                                                 
						<td id="*FHC_GID*" onClick="betEvent('*GID*','FHC','*IORATIO_FHC*','F');" style="cursor:pointer"  class="*TD_CLASS_FHC*" ><span class="m_left">主队 / 客队</span><span class="m_red" title="主队 / 客队">*IORATIO_FHC*</span></td>
						<td id="*FNC_GID*" onClick="betEvent('*GID*','FNC','*IORATIO_FNC*','F');" style="cursor:pointer"  class="*TD_CLASS_FNC*" ><span class="m_left">和局 / 客队</span><span class="m_red" title="和局 / 客队">*IORATIO_FNC*</span></td>
						<td id="*FCC_GID*" onClick="betEvent('*GID*','FCC','*IORATIO_FCC*','F');" style="cursor:pointer"  class="*TD_CLASS_FCC*" ><span class="m_left">客队 / 客队</span><span class="m_red" title="客队 / 客队">*IORATIO_FCC*</span></td>

				</tr>
				<!-- END DYNAMIC BLOCK: F -->
	
			</table>
			<!---------- F ---------->
			
			
			
			<!---------- WM ---------->
		 	<table id="model_WM" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="3">
							<span style="float: left;">净胜球数</span>
							<span class="more_og"></span>
						    <span class="more_star_bg"><span id="star_WM" name="star_WM" onClick="addFavorites('WM');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: WM -->
				<tr>
						<td class="game_team"><span>*TEAM_H*</span></td>
						<td class="game_team"><span>和局</span></td>
						<td class="game_team"><span>*TEAM_C*</span></td>	
				</tr>
				
				
				<tr class="more_white">
						<td id="*WMH1_GID*" onClick="betEvent('*GID*','WMH1','*IORATIO_WMH1*','WM');" style="cursor:pointer"  class="*TD_CLASS_WMH1*" width="35%"><span class="m_left">净胜1球</span><span class="m_red" title="净胜1球">*IORATIO_WMH1*</span></td>
						<td id="*WM0_GID*" onClick="betEvent('*GID*','WM0','*IORATIO_WM0*','WM');" style="cursor:pointer"  class="*TD_CLASS_WM0*" width="30%"><span class="m_left">0 - 0 和局</span><span class="m_red" title="0 - 0 和局">*IORATIO_WM0*</span></td>
						<td id="*WMC1_GID*" onClick="betEvent('*GID*','WMC1','*IORATIO_WMC1*','WM');" style="cursor:pointer"  class="*TD_CLASS*" width="35%"><span class="m_left">净胜1球</span><span class="m_red" title="净胜1球">*IORATIO_WMC1*</span></td>

				</tr>
				
				<tr class="more_color">
						<td id="*WMH2_GID*" onClick="betEvent('*GID*','WMH2','*IORATIO_WMH2*','WM');" style="cursor:pointer"  class="*TD_CLASS*" ><span class="m_left">净胜2球</span><span class="m_red" title="净胜2球">*IORATIO_WMH2*</span></td>
						<td id="*WMN_GID*" onClick="betEvent('*GID*','WMN','*IORATIO_WMN*','WM');" style="cursor:pointer"  class="*TD_CLASS_WMN*" ><span class="m_left">任何进球和局</span><span class="m_red" title="任何进球和局">*IORATIO_WMN*</span></td>
						<td id="*WMC2_GID*" onClick="betEvent('*GID*','WMC2','*IORATIO_WMC2*','WM');" style="cursor:pointer"  class="*TD_CLASS_WMC2*" ><span class="m_left">净胜2球</span><span class="m_red" title="净胜2球">*IORATIO_WMC2*</span></td>

				</tr>
				
				<tr class="more_white">
						<td id="*WMH3_GID*" onClick="betEvent('*GID*','WMH3','*IORATIO_WMH3*','WM');" style="cursor:pointer"  class="*TD_CLASS_WMH3*" ><span class="m_left">净胜3球</span><span class="m_red" title="净胜3球">*IORATIO_WMH3*</span></td>
						<td><span></span><span></span></td>
						<td id="*WMC3_GID*" onClick="betEvent('*GID*','WMC3','*IORATIO_WMC3*','WM');" style="cursor:pointer"  class="*TD_CLASS_WMC3*" ><span class="m_left">净胜3球</span><span class="m_red" title="净胜3球">*IORATIO_WMC3*</span></td>

				</tr>
				
				<tr class="more_color">
						<td id="*WMHOV_GID*" onClick="betEvent('*GID*','WMHOV','*IORATIO_WMHOV*','WM');" style="cursor:pointer"  class="*TD_CLASS_WMHOV*" ><span class="m_left">净胜4球或更多</span><span class="m_red" title="净胜4球或更多">*IORATIO_WMHOV*</span></td>
						<td><span></span><span></span></td>
						<td id="*WMCOV_GID*" onClick="betEvent('*GID*','WMCOV','*IORATIO_WMCOV*','WM');" style="cursor:pointer"  class="*TD_CLASS_WMCOV*" ><span class="m_left">净胜4球或更多</span><span class="m_red" title="净胜4球或更多">*IORATIO_WMCOV*</span></td>

				</tr>
				<!-- END DYNAMIC BLOCK: WM -->
	
			</table>
			<!---------- WM ---------->
			
			
			
			
			<!---------- MOU ---------->
			<table id="model_MOU" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			     <th class="more_title4" colspan="3">
			     	<span style="float: left;">独赢 & 进球 大 / 小</span>
			     	<span class="more_og"></span>
			     	<span class="more_star_bg"><span id="star_MOU" name="star_MOU" onClick="addFavorites('MOU');" class="star_down"></span></span>
			     </th>
			  </tr>
			
			
			  <!-- START DYNAMIC BLOCK: MOU -->
			  <tr class="*DISPLAY_MOU*">
						<td class="game_team"><span>*TEAM_H*</span></td>
						<td class="game_team"><span>和局</span></td>
						<td class="game_team"><span>*TEAM_C*</span></td>	
				</tr>

			  <tr class="more_white *DISPLAY_MOU*">
			     <td id="*MOUHO_GID*" onClick="betEvent('*GID*','*MOUHO_RTYPE*','*IORATIO_MOUHO*','*MOU_WTYPE*');" style="cursor:pointer" class="*TD_CLASS_MOUHO*" width="35%"><span class="m_left">*STR_MOUHO*</span><span class="m_red" title="*STR_MOUHO*">*IORATIO_MOUHO*</span></td>
			     <td id="*MOUNO_GID*" onClick="betEvent('*GID*','*MOUNO_RTYPE*','*IORATIO_MOUNO*','*MOU_WTYPE*');" style="cursor:pointer" class="*TD_CLASS_MOUNO*" width="30%"><span class="m_left">*STR_MOUNO*</span><span class="m_red" title="*STR_MOUNO*">*IORATIO_MOUNO*</span></td>
			     <td id="*MOUCO_GID*" onClick="betEvent('*GID*','*MOUCO_RTYPE*','*IORATIO_MOUCO*','*MOU_WTYPE*');" style="cursor:pointer" class="*TD_CLASS_MOUCO*" width="35%"><span class="m_left">*STR_MOUCO*</span><span class="m_red" title="*STR_MOUCO*">*IORATIO_MOUCO*</span></td>
			  </tr>
			  <tr class="more_color *DISPLAY_MOU*">
			     <td id="*MOUHU_GID*" onClick="betEvent('*GID*','*MOUHU_RTYPE*','*IORATIO_MOUHU*','*MOU_WTYPE*');" style="cursor:pointer" class="*TD_CLASS_MOUHU*" width="35%"><span class="m_left">*STR_MOUHU*</span><span class="m_red" title="*STR_MOUHU*">*IORATIO_MOUHU*</span></td>
			     <td id="*MOUNU_GID*" onClick="betEvent('*GID*','*MOUNU_RTYPE*','*IORATIO_MOUNU*','*MOU_WTYPE*');" style="cursor:pointer" class="*TD_CLASS_MOUNU*" width="30%"><span class="m_left">*STR_MOUNU*</span><span class="m_red" title="*STR_MOUNU*">*IORATIO_MOUNU*</span></td>
			     <td id="*MOUCU_GID*" onClick="betEvent('*GID*','*MOUCU_RTYPE*','*IORATIO_MOUCU*','*MOU_WTYPE*');" style="cursor:pointer" class="*TD_CLASS_MOUCU*" width="35%"><span class="m_left">*STR_MOUCU*</span><span class="m_red" title="*STR_MOUCU*">*IORATIO_MOUCU*</span></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: MOU -->
			</table>
			<!---------- MOU ---------->
			
      
      
      
      <!---------- MTS ---------->
			<table id="model_MTS" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="3">
			     <span style="float: left;">独赢 & 双方球队进球</span>
			     <span class="more_og"></span>
			     <span class="more_star_bg"><span id="star_MTS" name="star_MTS" onClick="addFavorites('MTS');" class="star_down"></span></span>
			    </th>
			  </tr>
			
			  <!-- START DYNAMIC BLOCK: MTS -->
			  <tr>
			      <td class="game_team"><span>*TEAM_H*</span></td>
			      <td class="game_team"><span>和局</span></td>
			      <td class="game_team"><span>*TEAM_C*</span></td>
			  </tr>
			
			  <tr class="more_white">
			     <td id="*MTSHY_GID*" onClick="betEvent('*GID*','MTSHY','*IORATIO_MTSHY*','MTS');" style="cursor:pointer" class="*TD_CLASS_MTSHY*" width="35%"><span class="m_left">是</span><span class="m_red" title="是">*IORATIO_MTSHY*</span></td>
			     <td id="*MTSNY_GID*" onClick="betEvent('*GID*','MTSNY','*IORATIO_MTSNY*','MTS');" style="cursor:pointer" class="*TD_CLASS_MTSNY*" width="30%"><span class="m_left">是</span><span class="m_red" title="是">*IORATIO_MTSNY*</span></td>
			     <td id="*MTSCY_GID*" onClick="betEvent('*GID*','MTSCY','*IORATIO_MTSCY*','MTS');" style="cursor:pointer" class="*TD_CLASS_MTSCY*" width="35%"><span class="m_left">是</span><span class="m_red" title="是">*IORATIO_MTSCY*</span></td>
			  </tr>
			  <tr class="more_color">
			     <td id="*MTSHN_GID*" onClick="betEvent('*GID*','MTSHN','*IORATIO_MTSHN*','MTS');" style="cursor:pointer" class="*TD_CLASS_MTSHN*" width="35%"><span class="m_left">不是</span><span class="m_red" title="不是">*IORATIO_MTSHN*</span></td>
			     <td id="*MTSNN_GID*" onClick="betEvent('*GID*','MTSNN','*IORATIO_MTSNN*','MTS');" style="cursor:pointer" class="*TD_CLASS_MTSNN*" width="30%"><span class="m_left">不是</span><span class="m_red" title="不是">*IORATIO_MTSNN*</span></td>
			     <td id="*MTSCN_GID*" onClick="betEvent('*GID*','MTSCN','*IORATIO_MTSCN*','MTS');" style="cursor:pointer" class="*TD_CLASS_MTSCN*" width="35%"><span class="m_left">不是</span><span class="m_red" title="不是">*IORATIO_MTSCN*</span></td>
			  </tr>
			
				<!-- END DYNAMIC BLOCK: MTS -->
			</table>
			<!---------- MTS ---------->
			
			
			
			
			<!---------- OUT ---------->
			<table id="model_OUT" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			     <span style="float: left;">进球 大 / 小 & 双方球队进球</span>
			     <span class="more_og"></span>
			     <span class="more_star_bg"><span id="star_OUT" name="star_OUT" onClick="addFavorites('OUT');" class="star_down"></span></span>
					</th>
			  </tr>
			
			
			  <!-- START DYNAMIC BLOCK: OUT -->
			  <tr class="*DISPLAY_OUT*">
		      <td class="game_team"><span>是</span></td>
		      <td class="game_team"><span>不是</span></td>
			  </tr>

			  <tr class="more_white *DISPLAY_OUT*">
			     <td id="*OUTOY_GID*" onClick="betEvent('*GID*','*OUTOY_RTYPE*','*IORATIO_OUTOY*','*OUT_WTYPE*');" style="cursor:pointer" class="*TD_CLASS_OUTOY*" width="50%"><span class="m_left">*STR_OUTOY*</span><span class="m_red" title="*STR_OUTOY*">*IORATIO_OUTOY*</span></td>
			     <td id="*OUTON_GID*" onClick="betEvent('*GID*','*OUTON_RTYPE*','*IORATIO_OUTON*','*OUT_WTYPE*');" style="cursor:pointer" class="*TD_CLASS_OUTON*" width="50%"><span class="m_left">*STR_OUTON*</span><span class="m_red" title="*STR_OUTON*">*IORATIO_OUTON*</span></td>
			  </tr>
			  <tr class="more_color *DISPLAY_OUT*">
			     <td id="*OUTUY_GID*" onClick="betEvent('*GID*','*OUTUY_RTYPE*','*IORATIO_OUTUY*','*OUT_WTYPE*');" style="cursor:pointer" class="*TD_CLASS_OUTUY*" width="50%"><span class="m_left">*STR_OUTUY*</span><span class="m_red" title="*STR_OUTUY*">*IORATIO_OUTUY*</span></td>
			     <td id="*OUTUN_GID*" onClick="betEvent('*GID*','*OUTUN_RTYPE*','*IORATIO_OUTUN*','*OUT_WTYPE*');" style="cursor:pointer" class="*TD_CLASS_OUTUN*" width="50%"><span class="m_left">*STR_OUTUN*</span><span class="m_red" title="*STR_OUTUN*">*IORATIO_OUTUN*</span></td>
			  </tr>
			  <!-- END DYNAMIC BLOCK: OUT -->
			
			</table>
			<!---------- OUT ---------->
			
			
			
			
			<!---------- MPG ---------->
			<table id="model_MPG" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="3">
			     <span style="float: left;">独赢 & 最先进球</span>
			     <span class="more_og"></span>
			     <span class="more_star_bg"><span id="star_MPG" name="star_MPG" onClick="addFavorites('MPG');" class="star_down"></span></span>
			    </th>
			  </tr>
			
			  <!-- START DYNAMIC BLOCK: MPG -->
			  <tr>
			      <td class="game_team"><span>*TEAM_H*</span></td>
			      <td class="game_team"><span>和局</span></td>
			      <td class="game_team"><span>*TEAM_C*</span></td>
			  </tr>
			
			  <tr class="more_white">
			     <td id="*MPGHH_GID*" onClick="betEvent('*GID*','MPGHH','*IORATIO_MPGHH*','MPG');" style="cursor:pointer"  class="*TD_CLASS_MPGHH*" width="35%"><div class="more_font"><span class="m_team">*TEAM_H* (最先进球)</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_MPGHH*</span></div></td>
			     <td id="*MPGNH_GID*" onClick="betEvent('*GID*','MPGNH','*IORATIO_MPGNH*','MPG');" style="cursor:pointer"  class="*TD_CLASS_MPGNH*" width="30%"><div class="more_font"><span class="m_team">*TEAM_H* (最先进球)</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_MPGNH*</span></div></td>
			     <td id="*MPGCH_GID*" onClick="betEvent('*GID*','MPGCH','*IORATIO_MPGCH*','MPG');" style="cursor:pointer"  class="*TD_CLASS_MPGCH*" width="35%"><div class="more_font"><span class="m_team">*TEAM_H* (最先进球)</span><span class="m_red_bet" title="*TEAM_H*">*IORATIO_MPGCH*</span></div></td>
			  </tr>
			  <tr class="more_color">
			     <td id="*MPGHC_GID*" onClick="betEvent('*GID*','MPGHC','*IORATIO_MPGHC*','MPG');" style="cursor:pointer"  class="*TD_CLASS_MPGHC*" width="35%"><div class="more_font"><span class="m_team">*TEAM_C* (最先进球)</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_MPGHC*</span></div></td>
			     <td id="*MPGNC_GID*" onClick="betEvent('*GID*','MPGNC','*IORATIO_MPGNC*','MPG');" style="cursor:pointer"  class="*TD_CLASS_MPGNC*" width="30%"><div class="more_font"><span class="m_team">*TEAM_C* (最先进球)</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_MPGNC*</span></div></td>
			     <td id="*MPGCC_GID*" onClick="betEvent('*GID*','MPGCC','*IORATIO_MPGCC*','MPG');" style="cursor:pointer"  class="*TD_CLASS_MPGCC*" width="35%"><div class="more_font"><span class="m_team">*TEAM_C* (最先进球)</span><span class="m_red_bet" title="*TEAM_C*">*IORATIO_MPGCC*</span></div></td>
			  </tr>
			
				<!-- END DYNAMIC BLOCK: MPG -->
			</table>
			<!---------- MPG ---------->
			
			
			

			<!---------- DC ---------->
		 	<table id="model_DC" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
					<th class="more_title4" colspan="4">
						<span style="float: left;">双重机会</span>
						<span class="more_og"></span>
				    	<span class="more_star_bg"><span id="star_DC" name="star_DC" onClick="addFavorites('DC');" class="star_down" ></span></span>
					</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: DC -->
				<tr class="more_white">
						<td id="*DCHN_GID*" onClick="betEvent('*GID*','DCHN','*IORATIO_DCHN*','DC');" style="cursor:pointer"  class="*TD_CLASS_DCHN*" ><div class="more_font"><span class="m_team">*TEAM_H* / 和局</span><span class="m_red_bet" title="*TEAM_H* / 和局">*IORATIO_DCHN*</span></div></td>
				</tr>                                                                                                       
				                                                                                                            
				<tr class="more_color">                                                                                     
						<td id="*DCCN_GID*" onClick="betEvent('*GID*','DCCN','*IORATIO_DCCN*','DC');" style="cursor:pointer"  class="*TD_CLASS_DCCN*" ><div class="more_font"><span class="m_team">*TEAM_C* / 和局</span><span class="m_red_bet" title="*TEAM_C* / 和局">*IORATIO_DCCN*</span></div></td>
				</tr>                                                                                                       
				                                                                                                            
				<tr class="more_white">                                                                                     
						<td id="*DCHC_GID*" onClick="betEvent('*GID*','DCHC','*IORATIO_DCHC*','DC');" style="cursor:pointer"  class="*TD_CLASS_DCHC*" ><div class="more_font"><span class="m_team">*TEAM_H* / *TEAM_C*</span><span class="m_red_bet" title="*TEAM_H* / *TEAM_C*">*IORATIO_DCHC*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: DC -->
	
			</table>
			<!---------- DC ---------->
			
			
			
			<!---------- MW ---------->
			<table id="model_MW" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="4">
			     <span style="float: left;">胜出方法</span>
			     <span class="more_og"></span>
			     <span class="more_star_bg"><span id="star_MW" name="star_MW" onClick="addFavorites('MW');" class="star_down"></span></span>
			    </th>
			  </tr>
			
			
			  <!-- START DYNAMIC BLOCK: MW -->
			  <tr>
		      <td class="game_team" colspan="2"><span>*TEAM_H*</span></td>
		      <td class="game_team" colspan="2"><span>*TEAM_C*</span></td>
			  </tr>
			
			
			  <tr class="more_white">
			     <td id="*MWH_GID*" onClick="betEvent('*GID*','MWH','*IORATIO_MWH*','MW');" style="cursor:pointer"  class="*TD_CLASS_MWH*" width="25%"><span class="m_left">90分钟</span><span class="m_red" title="90分钟">*IORATIO_MWH*</span></td>
			     <td id="*MWHOT_GID*" onClick="betEvent('*GID*','MWHOT','*IORATIO_MWHOT*','MW');" style="cursor:pointer"  class="*TD_CLASS_MWHOT*" width="25%"><span class="m_left">加时赛</span><span class="m_red" title="加时赛">*IORATIO_MWHOT*</span></td>
			     <td id="*MWC_GID*" onClick="betEvent('*GID*','MWC','*IORATIO_MWC*','MW');" style="cursor:pointer"  class="*TD_CLASS_MWC*" width="25%"><span class="m_left">90分钟</span><span class="m_red" title="90分钟">*IORATIO_MWC*</span></td>
			     <td id="*MWCOT_GID*" onClick="betEvent('*GID*','MWCOT','*IORATIO_MWCOT*','MW');" style="cursor:pointer"  class="*TD_CLASS_MWCOT*" width="25%"><span class="m_left">加时赛</span><span class="m_red" title="加时赛">*IORATIO_MWCOT*</span></td>
			  </tr>
			
			  <tr class="more_white">
			     <td id="*MWHPK_GID*" onClick="betEvent('*GID*','MWHPK','*IORATIO_MWHPK*','MW');" style="cursor:pointer"  class="*TD_CLASS_MWHPK*" width="25%"><span class="m_left">点球大战</span><span class="m_red" title="点球大战">*IORATIO_MWHPK*</span></td>
			     <td class="" width="25%"></td>
			     <td id="*MWCPK_GID*" onClick="betEvent('*GID*','MWCPK','*IORATIO_MWCPK*','MW');" style="cursor:pointer"  class="*TD_CLASS_MWCPK*" width="25%"><span class="m_left">点球大战</span><span class="m_red" title="点球大战">*IORATIO_MWCPK*</span></td>
			     <td class="" width="25%"></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: MW -->
			
			
			</table>
			<!---------- MW ---------->
			
			
			
			<!---------- MQ ---------->
			<table id="model_MQ" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="4">
			    	<span style="float: left;">晋级方法</span>
			    	<span class="more_og"></span>
			    	<span class="more_star_bg"><span id="star_MQ" name="star_MQ" onClick="addFavorites('MQ');" class="star_down"></span></span>
			    </th>
			  </tr>
			
			
			  <!-- START DYNAMIC BLOCK: MQ -->
			  <tr>
		      <td class="game_team" colspan="2"><span>*TEAM_H*</span></td>
		      <td class="game_team" colspan="2"><span>*TEAM_C*</span></td>
			  </tr>
			
			
			  <tr class="more_white">
			     <td id="*MQH_GID*" onClick="betEvent('*GID*','MQH','*IORATIO_MQH*','MQ');" style="cursor:pointer"  class="*TD_CLASS_MQH*" width="25%"><span class="m_left">90分钟</span><span class="m_red" title="90分钟">*IORATIO_MQH*</span></td>
			     <td id="*MQHOT_GID*" onClick="betEvent('*GID*','MQHOT','*IORATIO_MQHOT*','MQ');" style="cursor:pointer"  class="*TD_CLASS_MQHOT*" width="25%"><span class="m_left">加时赛</span><span class="m_red" title="加时赛">*IORATIO_MQHOT*</span></td>
			     <td id="*MQC_GID*" onClick="betEvent('*GID*','MQC','*IORATIO_MQC*','MQ');" style="cursor:pointer"  class="*TD_CLASS_MQC*" width="25%"><span class="m_left">90分钟</span><span class="m_red" title="90分钟">*IORATIO_MQC*</span></td>
			     <td id="*MQCOT_GID*" onClick="betEvent('*GID*','MQCOT','*IORATIO_MQCOT*','MQ');" style="cursor:pointer"  class="*TD_CLASS_MQCOT*" width="25%"><span class="m_left">加时赛</span><span class="m_red" title="加时赛">*IORATIO_MQCOT*</span></td>
			  </tr>
			
			  <tr class="more_white">
			     <td id="*MQHPK_GID*" onClick="betEvent('*GID*','MQHPK','*IORATIO_MQHPK*','MQ');" style="cursor:pointer"  class="*TD_CLASS_MQHPK*" width="25%"><span class="m_left">点球大战</span><span class="m_red" title="点球大战">*IORATIO_MQHPK*</span></td>
			     <td class="" width="25%"></td>
			     <td id="*MQCPK_GID*" onClick="betEvent('*GID*','MQCPK','*IORATIO_MQCPK*','MQ');" style="cursor:pointer"  class="*TD_CLASS_MQCPK*" width="25%"><span class="m_left">点球大战</span><span class="m_red" title="点球大战">*IORATIO_MQCPK*</span></td>
			     <td class="" width="25%"></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: MQ -->
			
			
			</table>
			<!---------- MQ ---------->
			
			
			

			<!---------- W3 ---------->
		 	<table id="model_W3" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="4">
							<span style="float: left;">三项让球投注</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_W3" name="star_W3" onClick="addFavorites('W3');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: W3 -->
				<tr class="more_white">
						<td id="*W3H_GID*" onClick="betEvent('*GID*','W3H','*IORATIO_W3H*','W3');" style="cursor:pointer"  class="*TD_CLASS_W3H*" ><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_middle">*RATIO_W3H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_W3H*</span></div></td>
				</tr>                                                                                                    
				                                                                                                         
				<tr class="more_color">                                                                                  
						<td id="*W3C_GID*" onClick="betEvent('*GID*','W3C','*IORATIO_W3C*','W3');" style="cursor:pointer"  class="*TD_CLASS_W3C*" ><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_middle">*RATIO_W3C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_W3C*</span></div></td>
				</tr>                                                                                                    
				                                                                                                         
				<tr class="more_white">                                                                                  
						<td id="*W3N_GID*" onClick="betEvent('*GID*','W3N','*IORATIO_W3N*','W3');" style="cursor:pointer"  class="*TD_CLASS_W3N*" ><div class="more_font"><span class="m_team">让球和局</span><span class="m_middle">*RATIO_W3N*</span><span class="m_red_bet" title="让球和局">*IORATIO_W3N*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: W3 -->
	
			</table>
			<!---------- W3 ---------->			
			
			<!---------- BH ---------->
		 	<table id="model_BH" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">落后反超获胜</span>
							<span class="more_star_bg"><span id="star_BH" name="star_BH" onClick="addFavorites('BH');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: BH -->
				<tr class="more_white">
						<td id="*BHH_GID*" onClick="betEvent('*GID*','BHH','*IORATIO_BHH*','BH');" style="cursor:pointer"  class="*TD_CLASS_BHH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_BHH*</span></div></td>
						<td id="*BHC_GID*" onClick="betEvent('*GID*','BHC','*IORATIO_BHC*','BH');" style="cursor:pointer"  class="*TD_CLASS_BHC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_BHC*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: BH -->
	
			</table>
			<!---------- BH ---------->
			
			
			<!---------- WE ---------->
		 	<table id="model_WE" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">赢得任一半场</span>
							<span class="more_star_bg"><span id="star_WE" name="star_WE" onClick="addFavorites('WE');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: WE -->
				<tr class="more_white">
						<td id="*WEH_GID*" onClick="betEvent('*GID*','WEH','*IORATIO_WEH*','WE');" style="cursor:pointer"  class="*TD_CLASS_WEH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_WEH*</span></div></td>
						<td id="*WEC_GID*" onClick="betEvent('*GID*','WEC','*IORATIO_WEC*','WE');" style="cursor:pointer"  class="*TD_CLASS_WEC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_WEC*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: WE -->
	
			</table>
			<!---------- WE ---------->
			
			
			
			
			
			<!---------- WB ---------->
		 	<table id="model_WB" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">赢得所有半场</span>
							<span class="more_star_bg"><span id="star_WB" name="star_WB" onClick="addFavorites('WB');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: WB -->
				<tr class="more_white">
						<td id="*WBH_GID*" onClick="betEvent('*GID*','WBH','*IORATIO_WBH*','WB');" style="cursor:pointer"  class="*TD_CLASS_WBH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_WBH*</span></div></td>
						<td id="*WBC_GID*" onClick="betEvent('*GID*','WBC','*IORATIO_WBC*','WB');" style="cursor:pointer"  class="*TD_CLASS_WBC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_WBC*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: WB -->
	
			</table>
			<!---------- WB ---------->
			
			
			<!---------- PG ---------->
		 	<table id="model_PG" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="3">
							<span style="float: left;">最先 / 最后进球</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_PG" name="star_PG" onClick="addFavorites('PG');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: PG -->
				<tr>
						<td class="game_team"><span>最先进球</span></td>
						<td class="game_team"><span>最后进球</span></td>
						<td class="game_team"><span>无进球</span></td>	
				</tr>
				
				
				<tr class="more_white">
						<td id="*PGFH_GID*" onClick="betEvent('*GID*','PGFH','*IORATIO_PGFH*','SP');" style="cursor:pointer"  class="*TD_CLASS_PGFH*" width="35%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_PGFH*</span></div></td>
						<td id="*PGLH_GID*" onClick="betEvent('*GID*','PGLH','*IORATIO_PGLH*','SP');" style="cursor:pointer"  class="*TD_CLASS_PGLH*" width="35%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_PGLH*</span></div></td>
						<td rowspan="2" id="*PGFN_GID*" onClick="betEvent('*GID*','PGFN','*IORATIO_PGFN*','SP');" style="cursor:pointer; border-bottom:none; border-bottom:1px solid #C2B1A1\9;"  class="*TD_CLASS_PGFN*" width="30%"><div class="more_font"><span class="m_team">无进球</span><span class="m_red_bet" title="无进球">*IORATIO_PGFN*</span></div></td>

				</tr>
				
				<tr class="more_white">
						<td id="*PGFC_GID*" onClick="betEvent('*GID*','PGFC','*IORATIO_PGFC*','SP');" style="cursor:pointer"  class="*TD_CLASS_PGFC*" ><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_PGFC*</span></div></td>
						<td id="*PGLC_GID*" onClick="betEvent('*GID*','PGLC','*IORATIO_PGLC*','SP');" style="cursor:pointer"  class="*TD_CLASS_PGLC*" ><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_PGLC*</span></div></td>
				</tr>

				<!-- END DYNAMIC BLOCK: PG -->
	
			</table>
			<!---------- PG ---------->
			
			
			<!---------- RC ---------->
		 	<table id="model_RC" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="3">
							<span style="float: left;">最先 / 最后任意球</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_RC" name="star_RC" onClick="addFavorites('RC');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: RC -->
				<tr>
						<td class="game_team"><span>最先任意球</span></td>
						<td class="game_team"><span>最后任意球</span></td>
				</tr>
				
				
				<tr class="more_white">
						<td id="*RCFH_GID*" onClick="betEvent('*GID*','RCFH','*IORATIO_RCFH*','SP');" style="cursor:pointer"  class="*TD_CLASS_RCFH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_RCFH*</span></div></td>
						<td id="*RCLH_GID*" onClick="betEvent('*GID*','RCLH','*IORATIO_RCLH*','SP');" style="cursor:pointer"  class="*TD_CLASS_RCLH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_RCLH*</span></div></td>

				</tr>
				
				<tr class="more_color">
						<td id="*RCFC_GID*" onClick="betEvent('*GID*','RCFC','*IORATIO_RCFC*','SP');" style="cursor:pointer"  class="*TD_CLASS_RCFC*" ><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_RCFC*</span></div></td>
						<td id="*RCLC_GID*" onClick="betEvent('*GID*','RCLC','*IORATIO_RCLC*','SP');" style="cursor:pointer"  class="*TD_CLASS_RCLC*" ><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_RCLC*</span></div></td>
				</tr>

				<!-- END DYNAMIC BLOCK: RC -->
	
			</table>
			<!---------- RC ---------->			
			
			
			<!---------- TS ---------->   
		 	<table id="model_TS" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="3">
							<span style="float: left;">双方球队进球</span>
							<span class="more_star_bg"><span id="star_TS" name="star_TS" onClick="addFavorites('TS');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: TS -->
				<tr class="more_white">
						<td id="*TSY_GID*" onClick="betEvent('*GID*','TSY','*IORATIO_TSY*','TS');" style="cursor:pointer"  class="*TD_CLASS_TSY*" width="50%"><div class="more_font"><span class="m_team">是</span><span class="m_red_bet" title="是">*IORATIO_TSY*</span></div></td>
						<td id="*TSN_GID*" onClick="betEvent('*GID*','TSN','*IORATIO_TSN*','TS');" style="cursor:pointer"  class="*TD_CLASS_TSN*" width="50%"><div class="more_font"><span class="m_team">不是</span><span class="m_red_bet" title="不是">*IORATIO_TSN*</span></div></td>
				</tr>		
				<!-- END DYNAMIC BLOCK: TS -->
	
			</table>
			<!---------- TS ---------->
			
			
			
			
			<!---------- HTS ---------->
			<table id="model_HTS" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">双方球队进球</span>
			    	<span class="more_og2"><span class="more_og6">&nbsp;</span><span class="more_og7"></span> - 上半场</span>
			    	<span class="more_star_bg"><span id="star_HTS" name="star_HTS" onClick="addFavorites('HTS');" class="star_down"></span></span>
			    </th>
			  </tr>

			  <!-- START DYNAMIC BLOCK: HTS -->

			  <tr class="more_white">
			     <td id="*HTSY_HGID*" onClick="betEvent('*HGID*','HTSY','*IORATIO_HTSY*','HTS');" style="cursor:pointer"  class="*TD_CLASS_HTSY*" width="50%"><div class="more_font"><span class="m_team">是</span><span class="m_red_bet" title="是">*IORATIO_HTSY*</span></div></td>
			     <td id="*HTSN_HGID*" onClick="betEvent('*HGID*','HTSN','*IORATIO_HTSN*','HTS');" style="cursor:pointer"  class="*TD_CLASS_HTSN*" width="50%"><div class="more_font"><span class="m_team">不是</span><span class="m_red_bet" title="不是">*IORATIO_HTSN*</span></div></td>
			  </tr>
			  <!-- END DYNAMIC BLOCK: HTS -->

			</table>
			<!---------- HTS ---------->
						
						
						
						
			<!---------- OUH ---------->      
		 	<table id="model_OUH" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">球队进球数:</span>
                            <span class="more_og2"><span class="more_og6">&nbsp;*TEAM_H*</span><span class="more_og7"> - 大 / 小</span></span>
							<span class="more_star_bg"><span id="star_OUH" name="star_OUH" onClick="addFavorites('OUH');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: OUH -->
				<tr class="more_white">
						<td id="*OUHO_GID*" onClick="betEvent('*GID*','OUHO','*IORATIO_OUHO*','OUH');" style="cursor:pointer"  class="*TD_CLASS_OUHO*" width="50%"><div class="more_font"><span class="m_team">大</span><span class="m_middle">*RATIO_OUHO*</span><span class="m_red_bet" title="大">*IORATIO_OUHO*</span></div></td>
						<td id="*OUHU_GID*" onClick="betEvent('*GID*','OUHU','*IORATIO_OUHU*','OUH');" style="cursor:pointer"  class="*TD_CLASS_OUHU*" width="50%"><div class="more_font"><span class="m_team">小</span><span class="m_middle">*RATIO_OUHU*</span><span class="m_red_bet" title="小">*IORATIO_OUHU*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: OUH -->
	
			</table>
			<!---------- OUH ---------->
			
			
			
			<!---------- OUC ---------->   
		 	<table id="model_OUC" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">球队进球数:</span>
                            <span class="more_og2"><span class="more_og6">&nbsp;*TEAM_C*</span><span class="more_og7"> - 大 / 小</span></span>
							<span class="more_star_bg"><span id="star_OUC" name="star_OUC" onClick="addFavorites('OUC');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: OUC -->
				<tr class="more_white">
						<td id="*OUCO_GID*" onClick="betEvent('*GID*','OUCO','*IORATIO_OUCO*','OUC');" style="cursor:pointer"  class="*TD_CLASS_OUCO*" width="50%"><div class="more_font"><span class="m_team">大</span><span class="m_middle">*RATIO_OUCO*</span><span class="m_red_bet" title="大">*IORATIO_OUCO*</span></div></td>
						<td id="*OUCU_GID*" onClick="betEvent('*GID*','OUCU','*IORATIO_OUCU*','OUC');" style="cursor:pointer"  class="*TD_CLASS_OUCU*" width="50%"><div class="more_font"><span class="m_team">小</span><span class="m_middle">*RATIO_OUCU*</span><span class="m_red_bet" title="小">*IORATIO_OUCU*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: OUC -->
	
			</table>
			<!---------- OUC ---------->
			
			
			
			
			<!---------- HOUH ---------->   
		 	<table id="model_HOUH" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">球队进球数:</span>
							<span class="more_og2"><span class="more_og6">&nbsp;*TEAM_H*</span><span class="more_og7"> - 大 / 小</span> - 上半场</span>
							<span class="more_star_bg"><span id="star_HOUH" name="star_HOUH" onClick="addFavorites('HOUH');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: HOUH -->
				<tr class="more_white">
						<td id="*HOUHO_HGID*" onClick="betEvent('*HGID*','HOUHO','*IORATIO_HOUHO*','HOUH');" style="cursor:pointer"  class="*TD_CLASS_HOUHO*" width="50%"><div class="more_font"><span class="m_team">大</span><span class="m_middle">*RATIO_HOUHO*</span><span class="m_red_bet" title="大">*IORATIO_HOUHO*</span></div></td>
						<td id="*HOUHU_HGID*" onClick="betEvent('*HGID*','HOUHU','*IORATIO_HOUHU*','HOUH');" style="cursor:pointer"  class="*TD_CLASS_HOUHU*" width="50%"><div class="more_font"><span class="m_team">小</span><span class="m_middle">*RATIO_HOUHU*</span><span class="m_red_bet" title="小">*IORATIO_HOUHU*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: HOUH -->
	
			</table>
			<!---------- HOUH ---------->
			
			
			
			<!---------- HOUC ---------->   
		 	<table id="model_HOUC" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">球队进球数:</span>
							<span class="more_og2"><span class="more_og6">&nbsp;*TEAM_C*</span><span class="more_og7"> - 大 / 小</span> - 上半场</span>
							<span class="more_star_bg"><span id="star_HOUC" name="star_HOUC" onClick="addFavorites('HOUC');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: HOUC -->
				<tr class="more_white">
						<td id="*HOUCO_HGID*" onClick="betEvent('*HGID*','HOUCO','*IORATIO_HOUCO*','HOUC');" style="cursor:pointer"  class="*TD_CLASS_HOUCO*" width="50%"><div class="more_font"><span class="m_team">大</span><span class="m_middle">*RATIO_HOUCO*</span><span class="m_red_bet" title="大">*IORATIO_HOUCO*</span></div></td>
						<td id="*HOUCU_HGID*" onClick="betEvent('*HGID*','HOUCU','*IORATIO_HOUCU*','HOUC');" style="cursor:pointer"  class="*TD_CLASS_HOUCU*" width="50%"><div class="more_font"><span class="m_team">小</span><span class="m_middle">*RATIO_HOUCU*</span><span class="m_red_bet" title="小">*IORATIO_HOUCU*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: HOUC -->
	
			</table>
			<!---------- HOUC ---------->
			

			<!---------- EO ---------->     
		 	<table id="model_EO" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">单 / 双</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_EO" name="star_EO" onClick="addFavorites('EO');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: EO -->
				<tr class="more_white">
						<td id="*EOO_GID*" onClick="betEvent('*GID*','ODD','*IORATIO_EOO*','EO');" style="cursor:pointer"  class="*TD_CLASS_EOO*" width="50%"><div class="more_font"><span class="m_team">单</span><span class="m_red_bet" title="单">*IORATIO_EOO*</span></div></td>
						<td id="*EOE_GID*" onClick="betEvent('*GID*','EVEN','*IORATIO_EOE*','EO');" style="cursor:pointer"  class="*TD_CLASS_EOE*" width="50%"><div class="more_font"><span class="m_team">双</span><span class="m_red_bet" title="双">*IORATIO_EOE*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: EO -->
	
			</table>
			<!---------- EO ---------->			


			<!---------- HEO ---------->     
		 	<table id="model_HEO" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">单 / 双</span>
                            <span class="more_og2">&nbsp;- 上半场</span>
							<span class="more_star_bg"><span id="star_HEO" name="star_HEO" onClick="addFavorites('HEO');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: HEO -->
				<tr class="more_white">
						<td id="*HEOO_HGID*" onClick="betEvent('*HGID*','HODD','*IORATIO_HEOO*','HEO');" style="cursor:pointer"  class="*TD_CLASS_HEOO*" width="50%"><div class="more_font"><span class="m_team">单</span><span class="m_red_bet" title="单">*IORATIO_HEOO*</span></div></td>
						<td id="*HEOE_HGID*" onClick="betEvent('*HGID*','HEVEN','*IORATIO_HEOE*','HEO');" style="cursor:pointer"  class="*TD_CLASS_HEOE*" width="50%"><div class="more_font"><span class="m_team">双</span><span class="m_red_bet" title="双">*IORATIO_HEOE*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: HEO -->
	
			</table>
			<!---------- HEO ---------->
			
			
			
			<!---------- EOH ---------->
			<table id="model_EOH" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th	class="more_title4" colspan="2">
			    	<span style="float: left;">球队进球数:</span>
			    	<span class="more_og2"><span class="more_og6">&nbsp;*TEAM_H*</span><span class="more_og7"> -  单 / 双</span></span>
			    	<span class="more_star_bg"><span id="star_EOH" name="star_EOH" onClick="addFavorites('EOH');" class="star_down"></span></span>
			    </th>
			  </tr>
			
			  <!-- START DYNAMIC BLOCK: EOH -->
			
			  <tr class="more_white">
			     <td id="*EOHO_GID*" onClick="betEvent('*GID*','EOHO','*IORATIO_EOHO*','EOH');" style="cursor:pointer"  class="*TD_CLASS_EOHO*" width="50%"><div class="more_font"><span class="m_team">单</span><span class="m_red_bet" title="单">*IORATIO_EOHO*</span></div></td>
			     <td id="*EOHE_GID*" onClick="betEvent('*GID*','EOHE','*IORATIO_EOHE*','EOH');" style="cursor:pointer"  class="*TD_CLASS_EOHE*" width="50%"><div class="more_font"><span class="m_team">双</span><span class="m_red_bet" title="双">*IORATIO_EOHE*</span></div></td>
			  </tr>
			  <!-- END DYNAMIC BLOCK: EOH -->
			
			</table>
			<!---------- EOH ---------->
			
			<!---------- EOC ---------->
			<table id="model_EOC" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th	class="more_title4" colspan="2">
			    	<span style="float: left;">球队进球数:</span>
			    	<span class="more_og2"><span class="more_og6">&nbsp;*TEAM_C*</span><span class="more_og7"> -  单 / 双</span></span>
			    	<span class="more_star_bg"><span id="star_EOC" name="star_EOC" onClick="addFavorites('EOC');" class="star_down"></span></span>
			    </th>
			  </tr>
			
			  <!-- START DYNAMIC BLOCK: EOC -->
			
			  <tr class="more_white">
			     <td id="*EOCO_GID*" onClick="betEvent('*GID*','EOCO','*IORATIO_EOCO*','EOC');" style="cursor:pointer"  class="*TD_CLASS_EOCO*" width="50%"><div class="more_font"><span class="m_team">单</span><span class="m_red_bet" title="单">*IORATIO_EOCO*</span></div></td>
			     <td id="*EOCE_GID*" onClick="betEvent('*GID*','EOCE','*IORATIO_EOCE*','EOC');" style="cursor:pointer"  class="*TD_CLASS_EOCE*" width="50%"><div class="more_font"><span class="m_team">双</span><span class="m_red_bet" title="双">*IORATIO_EOCE*</span></div></td>
			  </tr>
			  <!-- END DYNAMIC BLOCK: EOC -->
			
			</table>
			<!---------- EOC ---------->
			
			
			<!---------- HEOH ---------->
			<table id="model_HEOH" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">球队进球数:</span>
			    	<span class="more_og2"><span class="more_og6">&nbsp;*TEAM_H*</span><span class="more_og7"> - 单 / 双</span> - 上半场</span>
			    	<span class="more_star_bg"><span id="star_HEOH" name="star_HEOH" onClick="addFavorites('HEOH');" class="star_down"></span></span>
			    </th>
			  </tr>
			
			  <!-- START DYNAMIC BLOCK: HEOH -->
			
			  <tr class="more_white">
			     <td id="*HEOHO_HGID*" onClick="betEvent('*HGID*','HEOHO','*IORATIO_HEOHO*','HEOH');" style="cursor:pointer"  class="*TD_CLASS_HEOHO*" width="50%"><div class="more_font"><span class="m_team">单</span><span class="m_red_bet" title="单">*IORATIO_HEOHO*</span></div></td>
			     <td id="*HEOHE_HGID*" onClick="betEvent('*HGID*','HEOHE','*IORATIO_HEOHE*','HEOH');" style="cursor:pointer"  class="*TD_CLASS_HEOHE*" width="50%"><div class="more_font"><span class="m_team">双</span><span class="m_red_bet" title="双">*IORATIO_HEOHE*</span></div></td>
			  </tr>
			  <!-- END DYNAMIC BLOCK: HEOH -->
			
			</table>
			<!---------- HEOH ---------->
			
			<!---------- HEOC ---------->
			<table id="model_HEOC" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">球队进球数:</span>
			    	<span class="more_og2"><span class="more_og6">&nbsp;*TEAM_C*</span><span class="more_og7"> - 单 / 双</span> - 上半场</span>
			    	<span class="more_star_bg"><span id="star_HEOC" name="star_HEOC" onClick="addFavorites('HEOC');" class="star_down"></span></span>
			    </th>
			  </tr>
			
			  <!-- START DYNAMIC BLOCK: HEOC -->
			
			  <tr class="more_white">
			     <td id="*HEOCO_HGID*" onClick="betEvent('*HGID*','HEOCO','*IORATIO_HEOCO*','HEOC');" style="cursor:pointer"  class="*TD_CLASS_HEOCO*" width="50%"><div class="more_font"><span class="m_team">单</span><span class="m_red_bet" title="单">*IORATIO_HEOCO*</span></div></td>
			     <td id="*HEOCE_HGID*" onClick="betEvent('*HGID*','HEOCE','*IORATIO_HEOCE*','HEOC');" style="cursor:pointer"  class="*TD_CLASS_HEOCE*" width="50%"><div class="more_font"><span class="m_team">双</span><span class="m_red_bet" title="双">*IORATIO_HEOCE*</span></div></td>
			  </tr>
			  <!-- END DYNAMIC BLOCK: HEOC -->
			
			</table>
			<!---------- HEOC ---------->
			
			

			<!---------- SFS ---------->     
		 	<table id="model_SFS" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="8">
							<span style="float: left;">进球球员</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_SFS" name="star_SFS" onClick="addFavorites('SFS');" class="star_down" ></span></span>
						</th>
				</tr>
				<tr class="game_team">
					<td colspan=4 width="50%">*TEAM_H*</td>
					<td colspan=4 width="50%">*TEAM_C*</td>
				</tr>
				<tr class="more_color2">
					<td>球员</td>
					<!--td>第一</td>
					<td>最后</td>
					<td>任意时间</td-->
					<td>*TITLE_A0*</td>
					<td>*TITLE_A1*</td>
					<td>*TITLE_A2*</td>					
					<td>球员</td>
					<!--td>第一</td>
					<td>最后</td>
					<td>任意时间</td-->
					<td>*TITLE_B0*</td>
					<td>*TITLE_B1*</td>
					<td>*TITLE_B2*</td>						
				</tr>
				<!-- START DYNAMIC BLOCK: SFS -->
				<tr class="*TR_CLASS*">
						<td class="more_te_left"><span>*SFS_NAME_A0*</span></td>
						<td id="*RTYPE_SGIDA0*" onClick="betEvent('*SFS_GID_A0*','*SFS_RTYPE_A0*','*SFS_IOR_A0*','NFS');" style="cursor:pointer; text-align:center;"  class="*TD_CLASS_A0*" ><span class="m_red" *TITLE_SFS_NAME_A0*>*SFS_IOR_A0*</span></td>
						<td id="*RTYPE_SGIDA1*" onClick="betEvent('*SFS_GID_A1*','*SFS_RTYPE_A1*','*SFS_IOR_A1*','NFS');" style="cursor:pointer; text-align:center;"  class="*TD_CLASS_A1*" ><span class="m_red" *TITLE_SFS_NAME_A0*>*SFS_IOR_A1*</span></td>
						<td id="*RTYPE_SGIDA2*" onClick="betEvent('*SFS_GID_A2*','*SFS_RTYPE_A2*','*SFS_IOR_A2*','NFS');" style="cursor:pointer; text-align:center;"  class="*TD_CLASS_A2*" ><span class="m_red" *TITLE_SFS_NAME_A0*>*SFS_IOR_A2*</span></td>
						<td class="more_te_left"><span>*SFS_NAME_B0*</span></td>                                                                                         
						<td id="*RTYPE_SGIDB0*" onClick="betEvent('*SFS_GID_B0*','*SFS_RTYPE_B0*','*SFS_IOR_B0*','NFS');" style="cursor:pointer; text-align:center;"  class="*TD_CLASS_B0*" ><span class="m_red" *TITLE_SFS_NAME_B0*>*SFS_IOR_B0*</span></td>
						<td id="*RTYPE_SGIDB1*" onClick="betEvent('*SFS_GID_B1*','*SFS_RTYPE_B1*','*SFS_IOR_B1*','NFS');" style="cursor:pointer; text-align:center;"  class="*TD_CLASS_B1*" ><span class="m_red" *TITLE_SFS_NAME_B0*>*SFS_IOR_B1*</span></td>
						<td id="*RTYPE_SGIDB2*" onClick="betEvent('*SFS_GID_B2*','*SFS_RTYPE_B2*','*SFS_IOR_B2*','NFS');" style="cursor:pointer; text-align:center;"  class="*TD_CLASS_B2*" ><span class="m_red" *TITLE_SFS_NAME_B0*>*SFS_IOR_B2*</span></td>
				</tr>
				<!-- END DYNAMIC BLOCK: SFS -->
				<tr id="show_more_SFS" class="more_color"><td onClick="SFS_show('more')" *dis_play_more_sfs* colspan="8" class="show_more">显示更多</td></tr>
				<tr id="show_less_SFS" class="more_color"><td onClick="SFS_show('less')" *dis_play_less_sfs* colspan="8" class="show_more">显示精简</td></tr>
			</table>
			<!---------- SFS ---------->
			
			<!---------- CS ---------->     
		 	<table id="model_CS" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">零失球</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_CS" name="star_CS" onClick="addFavorites('CS');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: CS -->
				<tr class="more_white">
						<td id="*CSH_GID*" onClick="betEvent('*GID*','CSH','*IORATIO_CSH*','CS');" style="cursor:pointer"  class="*TD_CLASS_CSH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_CSH*</span></div></td>
						<td id="*CSC_GID*" onClick="betEvent('*GID*','CSC','*IORATIO_CSC*','CS');" style="cursor:pointer"  class="*TD_CLASS_CSC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_CSC*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: CS -->
	
			</table>
			<!---------- CS ---------->
			
			
			
			
			<!---------- WN ---------->    
		 	<table id="model_WN" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">零失球获胜</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_WN" name="star_WN" onClick="addFavorites('WN');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: WN -->
				<tr class="more_white">
						<td id="*WNH_GID*" onClick="betEvent('*GID*','WNH','*IORATIO_WNH*','WN');" style="cursor:pointer"  class="*TD_CLASS_WNH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_WNH*</span></div></td>
						<td id="*WNC_GID*" onClick="betEvent('*GID*','WNC','*IORATIO_WNC*','WN');" style="cursor:pointer"  class="*TD_CLASS_WNC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_WNC*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: WN -->
			</table>
			<!---------- WN ---------->
			

			
			
			<!---------- F2G ---------->     
		 	<table id="model_F2G" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="3">
							<span style="float: left;">先进2球的一方</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_F2G" name="star_F2G" onClick="addFavorites('F2G');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: F2G -->
				<tr class="more_white">
						<td id="*F2GH_GID*" onClick="betEvent('*GID*','F2GH','*IORATIO_F2GH*','F2G');" style="cursor:pointer"  class="*TD_CLASS_F2GH*" width="100%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_F2GH*</span></div></td>
				</tr>                                                                                                        
				                                                                                                             
				<tr class="more_color">                                                                                      
						<td id="*F2GC_GID*" onClick="betEvent('*GID*','F2GC','*IORATIO_F2GC*','F2G');" style="cursor:pointer"  class="*TD_CLASS_F2GC*" width="100%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_F2GC*</span></div></td>
				</tr>                                                                                                        
				                                                                                                             
				<!--<tr class="more_white">                                                                                      
						<td id="*F2GN_GID*" onClick="betEvent('*GID*','F2GN','*IORATIO_F2GN*','F2G');" style="cursor:pointer"  class="*TD_CLASS_F2GN*" width="100%"><div class="more_font"><span class="m_team">两队都无</span><span class="m_red_bet" title="两队都无"</span></div></td>
				</tr>-->
				<!-- END DYNAMIC BLOCK: F2G -->
	
			</table>
			<!---------- F2G ---------->
			
			<!---------- F3G ---------->     
		 	<table id="model_F3G" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="3">
							<span style="float: left;">先进3球的一方</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_F3G" name="star_F3G" onClick="addFavorites('F3G');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: F3G -->
				<tr class="more_white">
						<td id="*F3GH_GID*" onClick="betEvent('*GID*','F3GH','*IORATIO_F3GH*','F3G');" style="cursor:pointer"  class="*TD_CLASS_F3GH*" width="100%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_F3GH*</span></div></td>
				</tr>                                                                                                        
				                                                                                                             
				<tr class="more_color">                                                                                      
						<td id="*F3GC_GID*" onClick="betEvent('*GID*','F3GC','*IORATIO_F3GC*','F3G');" style="cursor:pointer"  class="*TD_CLASS_F3GC*" width="100%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_F3GC*</span></div></td>
				</tr>                                                                                                        
				                                                                                                             
				<!--<tr class="more_white">                                                                                      
						<td id="*F3GN_GID*" onClick="betEvent('*GID*','F3GN','*IORATIO_F3GN*','F3G');" style="cursor:pointer"  class="*TD_CLASS_F3GN*" width="100%"><div class="more_font"><span class="m_team">两队都无</span><span class="m_red_bet" title="两队都无">*IORATIO_F3GN*</span></div></td>
				</tr>-->
				<!-- END DYNAMIC BLOCK: F3G -->
	
			</table>
			<!---------- F3G ---------->
			
			<!---------- HG ---------->         
		 	<table id="model_HG" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">最多进球的半场</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_HG" name="star_HG" onClick="addFavorites('HG');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: HG -->
				<tr class="more_white">
						<td id="*HGH_GID*" onClick="betEvent('*GID*','HGH','*IORATIO_HGH*','HG');" style="cursor:pointer"  class="*TD_CLASS_HGH*" width="50%"><div class="more_font"><span class="m_team">上半场</span><span class="m_red_bet" title="上半场">*IORATIO_HGH*</span></div></td>
						<td id="*HGC_GID*" onClick="betEvent('*GID*','HGC','*IORATIO_HGC*','HG');" style="cursor:pointer"  class="*TD_CLASS_HGC*" width="50%"><div class="more_font"><span class="m_team">下半场</span><span class="m_red_bet" title="下半场">*IORATIO_HGC*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: HG -->
	
			</table>
			<!---------- HG ---------->
			
			
			
			
			<!---------- MG ---------->    
		 	<table id="model_MG" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="3">
							<span style="float: left;">最多进球的半场 - 独赢</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_MG" name="star_MG" onClick="addFavorites('MG');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: MG -->
				<tr class="more_white">
						<td id="*MGH_GID*" onClick="betEvent('*GID*','MGH','*IORATIO_MGH*','MG');" style="cursor:pointer"  class="*TD_CLASS_MGH*" width="35%"><div class="more_font"><span class="m_team">上半场</span><span class="m_red_bet" title="上半场">*IORATIO_MGH*</span></div></td>
						<td id="*MGC_GID*" onClick="betEvent('*GID*','MGC','*IORATIO_MGC*','MG');" style="cursor:pointer"  class="*TD_CLASS_MGC*" width="30%"><div class="more_font"><span class="m_team">下半场</span><span class="m_red_bet" title="下半场">*IORATIO_MGC*</span></div></td>
						<td id="*MGN_GID*" onClick="betEvent('*GID*','MGN','*IORATIO_MGN*','MG');" style="cursor:pointer"  class="*TD_CLASS_MGN*" width="35%"><div class="more_font"><span class="m_team">和局</span><span class="m_red_bet" title="和局">*IORATIO_MGN*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: MG -->
	
			</table>
			<!---------- MG ---------->
			
			

			
			<!---------- SB ---------->       
		 	<table id="model_SB" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">双半场进球</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_SB" name="star_SB" onClick="addFavorites('SB');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: SB -->
				<tr class="more_white">
						<td id="*SBH_GID*" onClick="betEvent('*GID*','SBH','*IORATIO_SBH*','SB');" style="cursor:pointer"  class="*TD_CLASS_SBH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_SBH*</span></div></td>
						<td id="*SBC_GID*" onClick="betEvent('*GID*','SBC','*IORATIO_SBC*','SB');" style="cursor:pointer"  class="*TD_CLASS_SBC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_SBC*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: SB -->
	
			</table>
			<!---------- SB ---------->
			
			
			<!---------- FG ---------->    
		 	<table id="model_FG" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="3">
							<span style="float: left;">首个进球方式</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_FG" name="star_FG" onClick="addFavorites('FG');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: FG -->
				<tr class="more_white">
						<td id="*FGS_GID*" onClick="betEvent('*GID*','FGS','*IORATIO_FGS*','FG');" style="cursor:pointer"  class="*TD_CLASS_FGS*" width="35%"><div class="more_font"><span class="m_team">射门</span><span class="m_red_bet" title="射门">*IORATIO_FGS*</span></div></td>
						<td id="*FGH_GID*" onClick="betEvent('*GID*','FGH','*IORATIO_FGH*','FG');" style="cursor:pointer"  class="*TD_CLASS_FGH*" width="30%"><div class="more_font"><span class="m_team">头球</span><span class="m_red_bet" title="头球">*IORATIO_FGH*</span></div></td>
						<td id="*FGN_GID*" onClick="betEvent('*GID*','FGN','*IORATIO_FGN*','FG');" style="cursor:pointer"  class="*TD_CLASS_FGN*" width="35%"><div class="more_font"><span class="m_team">无进球</span><span class="m_red_bet" title="无进球">*IORATIO_FGN*</span></div></td>
				</tr>                                                                                                    
				<tr class="more_color">                                                                                  
						<td id="*FGP_GID*" onClick="betEvent('*GID*','FGP','*IORATIO_FGP*','FG');" style="cursor:pointer"  class="*TD_CLASS_FGP*" ><div class="more_font"><span class="m_team">点球</span><span class="m_red_bet" title="点球">*IORATIO_FGP*</span></div></td>
						<td id="*FGF_GID*" onClick="betEvent('*GID*','FGF','*IORATIO_FGF*','FG');" style="cursor:pointer"  class="*TD_CLASS_FGF*" ><div class="more_font"><span class="m_team">任意球</span><span class="m_red_bet" title="任意球">*IORATIO_FGF*</span></div></td>
						<td id="*FGO_GID*" onClick="betEvent('*GID*','FGO','*IORATIO_FGO*','FG');" style="cursor:pointer"  class="*TD_CLASS_FGO*" ><div class="more_font"><span class="m_team">乌龙球</span><span class="m_red_bet" title="乌龙球">*IORATIO_FGO*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: FG -->
	
			</table>
			<!---------- FG ---------->
			


			
			<!---------- T3G ---------->     
		 	<table id="model_T3G" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="3">
							<span style="float: left;">首个进球时间-3项</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_T3G" name="star_T3G" onClick="addFavorites('T3G');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: T3G -->
				<tr class="more_white">
						<td id="*T3G1_GID*" onClick="betEvent('*GID*','T3G1','*IORATIO_T3G1*','T3G');" style="cursor:pointer"  class="*TD_CLASS_T3G1*" ><div class="more_font"><span class="m_team">第26分钟或之前</span><span class="m_red_bet" title="第26分钟或之前">*IORATIO_T3G1*</span></div></td>
				</tr>                                                                                                        
				                                                                                                             
				<tr class="more_color">                                                                                      
						<td id="*T3G2_GID*" onClick="betEvent('*GID*','T3G2','*IORATIO_T3G2*','T3G');" style="cursor:pointer"  class="*TD_CLASS_T3G2*" ><div class="more_font"><span class="m_team">第27分钟或之后</span><span class="m_red_bet" title="第27分钟或之后">*IORATIO_T3G2*</span></div></td>
				</tr>                                                                                                        
				                                                                                                             
				<tr class="more_white">                                                                                      
						<td id="*T3GN_GID*" onClick="betEvent('*GID*','T3GN','*IORATIO_T3GN*','T3G');" style="cursor:pointer"  class="*TD_CLASS_T3GN*" ><div class="more_font"><span class="m_team">无进球</span><span class="m_red_bet" title="无进球">*IORATIO_T3GN*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: T3G -->
	
			</table>
			<!---------- T3G ---------->
			
			
			
			
			
			<!---------- T1G ---------->                                                                                                     
		 	<table id="model_T1G" border="0" cellpadding="0" cellspacing="0" class="more_table2">                                                                                  
				<tr>                                                                                                                           
						<th class="more_title4" colspan="2">                                                                   
							<span style="float: left;">首个进球时间</span>                                                                                 
							<span class="more_og"></span>                                                                           
							<span class="more_star_bg"><span id="star_T1G" name="star_T1G" onClick="addFavorites('T1G');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: T1G -->
				<tr class="more_white">
						<td id="*T1G1_GID*" onClick="betEvent('*GID*','T1G1','*IORATIO_T1G1*','T1G');" style="cursor:pointer"  class="*TD_CLASS_T1G1*" width="50%"><div class="more_font"><span class="m_team">上半场开场 - 14:59分钟</span><span class="m_red_bet" title="上半场开场 - 14:59分钟">*IORATIO_T1G1*</span></div></td>
						<td id="*T1G2_GID*" onClick="betEvent('*GID*','T1G2','*IORATIO_T1G2*','T1G');" style="cursor:pointer"  class="*TD_CLASS_T1G2*" width="50%"><div class="more_font"><span class="m_team">15:00分钟 - 29:59分钟</span><span class="m_red_bet" title="15:00分钟 - 29:59分钟">*IORATIO_T1G2*</span></div></td>
				</tr>                                                                                                        
				                                                                                                             
				<tr class="more_color">                                                                                      
						<td id="*T1G3_GID*" onClick="betEvent('*GID*','T1G3','*IORATIO_T1G3*','T1G');" style="cursor:pointer"  class="*TD_CLASS_T1G3*" ><div class="more_font"><span class="m_team">30:00分钟 - 半场</span><span class="m_red_bet" title="30:00分钟 - 半场">*IORATIO_T1G3*</span></div></td>
						<td id="*T1G4_GID*" onClick="betEvent('*GID*','T1G4','*IORATIO_T1G4*','T1G');" style="cursor:pointer"  class="*TD_CLASS_T1G4*" ><div class="more_font"><span class="m_team">下半场开场 - 59:59分钟</span><span class="m_red_bet" title="下半场开场 - 59:59分钟">*IORATIO_T1G4*</span></div></td>
				</tr>                                                                                                        
				                                                                                                             
				<tr class="more_white">                                                                                      
						<td id="*T1G5_GID*" onClick="betEvent('*GID*','T1G5','*IORATIO_T1G5*','T1G');" style="cursor:pointer"  class="*TD_CLASS_T1G5*" ><div class="more_font"><span class="m_team">60:00分钟 - 74:59分钟</span><span class="m_red_bet" title="60:00分钟 - 74:59分钟">*IORATIO_T1G5*</span></div></td>
						<td id="*T1G6_GID*" onClick="betEvent('*GID*','T1G6','*IORATIO_T1G6*','T1G');" style="cursor:pointer"  class="*TD_CLASS_T1G6*" ><div class="more_font"><span class="m_team">75:00分钟 - 全场完场</span><span class="m_red_bet" title="75:00分钟 - 全场完场">*IORATIO_T1G6*</span></div></td>
				</tr>                                                                                                        
				                                                                                                             
				<tr class="more_color">                                                                                      
						<td id="*T1GN_GID*" onClick="betEvent('*GID*','T1GN','*IORATIO_T1GN*','T1G');" style="cursor:pointer"  class="*TD_CLASS_T1GN*" ><div class="more_font"><span class="m_team">无进球</span><span class="m_red_bet" title="无进球">*IORATIO_T1GN*</span></div></td>
						<td><span></span></td>
				</tr>
				<!-- END DYNAMIC BLOCK: T1G -->
	
			</table>
			<!---------- T1G ---------->
			
			
			
			
			<!---------- OG ---------->
			<table id="model_OG" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">乌龙球</span>
			    	<span class="more_og"></span>
			    	<span class="more_star_bg"><span id="star_OG" name="star_OG" onClick="addFavorites('OG');" class="star_down"></span></span>
			    </th>
			  </tr>
			
			  <!-- START DYNAMIC BLOCK: OG -->
			
			  <tr class="more_white">
			     <td id="*OGY_GID*" onClick="betEvent('*GID*','OGY','*IORATIO_OGY*','OG');" style="cursor:pointer"  class="*TD_CLASS_OGY*" width="50%"><div class="more_font"><span class="m_team">是</span><span class="m_red_bet" title="是">*IORATIO_OGY*</span></div></td>
			     <td id="*OGN_GID*" onClick="betEvent('*GID*','OGN','*IORATIO_OGN*','OG');" style="cursor:pointer"  class="*TD_CLASS_OGN*" width="50%"><div class="more_font"><span class="m_team">不是</span><span class="m_red_bet" title="不是">*IORATIO_OGN*</span></div></td>
			  </tr>
			  <!-- END DYNAMIC BLOCK: OG -->
			
			</table>
			<!---------- OG ---------->
			
			
			
			 <!---------- DU ---------->
			<table id="model_DU" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="3">
				    <span style="float: left;">双重机会 & 进球 大 / 小</span>
				    <span class="more_og"></span>
				    <span class="more_star_bg"><span id="star_DU" name="star_DU" onClick="addFavorites('DU');" class="star_down"></span></span>
			    </th>
			  </tr>
				
			  
			  <!-- START DYNAMIC BLOCK: DU -->
				<tr class="*DISPLAY_DU*">
		      <td class="game_team"><span>*TEAM_H* / 和局</span></td>
		      <td class="game_team"><span>*TEAM_C* / 和局</span></td>
		      <td class="game_team"><span>*TEAM_H* / *TEAM_C*</span></td>
			  </tr>

			  <tr class="more_white *DISPLAY_DU*">
			     <td id="*DUHO_GID*" onClick="betEvent('*GID*','*DUHO_RTYPE*','*IORATIO_DUHO*','*DU_WTYPE*');" style="cursor:pointer"  class="*TD_CLASS_DUHO*" width="35%"><div class="more_font"><span class="m_team">*STR_DUHO*</span><span class="m_red_bet" title="*STR_DUHO*">*IORATIO_DUHO*</span></div></td>
			     <td id="*DUCO_GID*" onClick="betEvent('*GID*','*DUCO_RTYPE*','*IORATIO_DUCO*','*DU_WTYPE*');" style="cursor:pointer"  class="*TD_CLASS_DUCO*" width="30%"><div class="more_font"><span class="m_team">*STR_DUCO*</span><span class="m_red_bet" title="*STR_DUCO*">*IORATIO_DUCO*</span></div></td>
			     <td id="*DUSO_GID*" onClick="betEvent('*GID*','*DUSO_RTYPE*','*IORATIO_DUSO*','*DU_WTYPE*');" style="cursor:pointer"  class="*TD_CLASS_DUSO*" width="35%"><div class="more_font"><span class="m_team">*STR_DUSO*</span><span class="m_red_bet" title="*STR_DUNO*">*IORATIO_DUSO*</span></div></td>
			  </tr>
			  <tr class="more_color *DISPLAY_DU*">
			     <td id="*DUHU_GID*" onClick="betEvent('*GID*','*DUHU_RTYPE*','*IORATIO_DUHU*','*DU_WTYPE*');" style="cursor:pointer"  class="*TD_CLASS_DUHU*" width="35%"><div class="more_font"><span class="m_team">*STR_DUHU*</span><span class="m_red_bet" title="*STR_DUHU*">*IORATIO_DUHU*</span></div></td>
			     <td id="*DUCU_GID*" onClick="betEvent('*GID*','*DUCU_RTYPE*','*IORATIO_DUCU*','*DU_WTYPE*');" style="cursor:pointer"  class="*TD_CLASS_DUCU*" width="30%"><div class="more_font"><span class="m_team">*STR_DUCU*</span><span class="m_red_bet" title="*STR_DUCU*">*IORATIO_DUCU*</span></div></td>
			     <td id="*DUSU_GID*" onClick="betEvent('*GID*','*DUSU_RTYPE*','*IORATIO_DUSU*','*DU_WTYPE*');" style="cursor:pointer"  class="*TD_CLASS_DUSU*" width="35%"><div class="more_font"><span class="m_team">*STR_DUSU*</span><span class="m_red_bet" title="*STR_DUNU*">*IORATIO_DUSU*</span></div></td>
			  </tr>
				<!-- END DYNAMIC BLOCK: DU -->
			</table>
			<!---------- DU ---------->
			
			
			
			
			<!---------- DS ---------->
			<table id="model_DS" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="3">
			    	<span style="float: left;">双重机会 & 双方球队进球</span>
			    	<span class="more_og"></span>
			    	<span class="more_star_bg"><span id="star_DS" name="star_DS" onClick="addFavorites('DS');" class="star_down"></span></span>
			    </th>
			  </tr>
			
			  <!-- START DYNAMIC BLOCK: DS -->
			  <tr>
			      <td class="game_team"><span>*TEAM_H* / 和局</span></td>
			      <td class="game_team"><span>*TEAM_C* / 和局</span></td>
			      <td class="game_team"><span>*TEAM_H* / *TEAM_C*</span></td>
			  </tr>
			
			  <tr class="more_white">
			     <td id="*DSHY_GID*" onClick="betEvent('*GID*','DSHY','*IORATIO_DSHY*','DS');" style="cursor:pointer"  class="*TD_CLASS_DSHY*" width="35%"><div class="more_font"><span class="m_team">是</span><span class="m_red_bet" title="是">*IORATIO_DSHY*</span></div></td>
			     <td id="*DSCY_GID*" onClick="betEvent('*GID*','DSCY','*IORATIO_DSCY*','DS');" style="cursor:pointer"  class="*TD_CLASS_DSCY*" width="30%"><div class="more_font"><span class="m_team">是</span><span class="m_red_bet" title="是">*IORATIO_DSCY*</span></div></td>
			     <td id="*DSSY_GID*" onClick="betEvent('*GID*','DSSY','*IORATIO_DSSY*','DS');" style="cursor:pointer"  class="*TD_CLASS_DSSY*" width="35%"><div class="more_font"><span class="m_team">是</span><span class="m_red_bet" title="是">*IORATIO_DSSY*</span></div></td>
			  </tr>
			  <tr class="more_color">
			     <td id="*DSHN_GID*" onClick="betEvent('*GID*','DSHN','*IORATIO_DSHN*','DS');" style="cursor:pointer"  class="*TD_CLASS_DSHN*" width="35%"><div class="more_font"><span class="m_team">不是</span><span class="m_red_bet" title="不是">*IORATIO_DSHN*</span></div></td>
			     <td id="*DSCN_GID*" onClick="betEvent('*GID*','DSCN','*IORATIO_DSCN*','DS');" style="cursor:pointer"  class="*TD_CLASS_DSCN*" width="30%"><div class="more_font"><span class="m_team">不是</span><span class="m_red_bet" title="不是">*IORATIO_DSCN*</span></div></td>
			     <td id="*DSSN_GID*" onClick="betEvent('*GID*','DSSN','*IORATIO_DSSN*','DS');" style="cursor:pointer"  class="*TD_CLASS_DSSN*" width="35%"><div class="more_font"><span class="m_team">不是</span><span class="m_red_bet" title="不是">*IORATIO_DSSN*</span></div></td>
			  </tr>
			
				<!-- END DYNAMIC BLOCK: DS -->
			</table>
			<!---------- DS ---------->
			
			
			
			
			<!---------- DG ---------->
			<table id="model_DG" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="3">
			    	<span style="float: left;">双重机会 & 最先进球</span>
			    	<span class="more_og"></span>
			    	<span class="more_star_bg"><span id="star_DG" name="star_DG" onClick="addFavorites('DG');" class="star_down"></span></span>
			    </th>
			  </tr>
			
			  <!-- START DYNAMIC BLOCK: DG -->
			  <tr>
			      <td class="game_team"><span>*TEAM_H* / 和局</span></td>
			      <td class="game_team"><span>*TEAM_C* / 和局</span></td>
			      <td class="game_team"><span>*TEAM_H* / *TEAM_C*</span></td>
			  </tr>
			
			  <tr class="more_white">
			     <td id="*DGHH_GID*" onClick="betEvent('*GID*','DGHH','*IORATIO_DGHH*','DG');" style="cursor:pointer"  class="*TD_CLASS_DGHH*" width="35%"><div class="more_font"><span class="m_team">*TEAM_H* (最先进球)</span><span class="m_red_bet" title="*TEAM_H* (最先进球)">*IORATIO_DGHH*</span></div></td>
			     <td id="*DGCH_GID*" onClick="betEvent('*GID*','DGCH','*IORATIO_DGCH*','DG');" style="cursor:pointer"  class="*TD_CLASS_DGCH*" width="30%"><div class="more_font"><span class="m_team">*TEAM_H* (最先进球)</span><span class="m_red_bet" title="*TEAM_H* (最先进球)">*IORATIO_DGCH*</span></div></td>
			     <td id="*DGSH_GID*" onClick="betEvent('*GID*','DGSH','*IORATIO_DGSH*','DG');" style="cursor:pointer"  class="*TD_CLASS_DGSH*" width="35%"><div class="more_font"><span class="m_team">*TEAM_H* (最先进球)</span><span class="m_red_bet" title="*TEAM_H* (最先进球)">*IORATIO_DGSH*</span></div></td>
			  </tr>
			  <tr class="more_color">
			     <td id="*DGHC_GID*" onClick="betEvent('*GID*','DGHC','*IORATIO_DGHC*','DG');" style="cursor:pointer"  class="*TD_CLASS_DGHC*" width="35%"><div class="more_font"><span class="m_team">*TEAM_C* (最先进球)</span><span class="m_red_bet" title="*TEAM_C* (最先进球)">*IORATIO_DGHC*</span></div></td>
			     <td id="*DGCC_GID*" onClick="betEvent('*GID*','DGCC','*IORATIO_DGCC*','DG');" style="cursor:pointer"  class="*TD_CLASS_DGCC*" width="30%"><div class="more_font"><span class="m_team">*TEAM_C* (最先进球)</span><span class="m_red_bet" title="*TEAM_C* (最先进球)">*IORATIO_DGCC*</span></div></td>
			     <td id="*DGSC_GID*" onClick="betEvent('*GID*','DGSC','*IORATIO_DGSC*','DG');" style="cursor:pointer"  class="*TD_CLASS_DGSC*" width="35%"><div class="more_font"><span class="m_team">*TEAM_C* (最先进球)</span><span class="m_red_bet" title="*TEAM_C* (最先进球)">*IORATIO_DGSC*</span></div></td>
			  </tr>
			
				<!-- END DYNAMIC BLOCK: DG -->
			</table>
			<!---------- DG ---------->
			
			
			
			
			<!---------- OUE ---------->
			<table id="model_OUE" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">进球 大 / 小 & 进球 单 / 双</span>
			    	<span class="more_og"></span>
			    	<span class="more_star_bg"><span id="star_OUE" name="star_OUE" onClick="addFavorites('OUE');" class="star_down"></span></span>
			    </th>
			  </tr>
			
			
			  <!-- START DYNAMIC BLOCK: OUE -->
			  <tr class="*DISPLAY_OUE*">
			      <td class="game_team"><span>单</span></td>
			      <td class="game_team"><span>双</span></td>
			  </tr>

			  <tr class="more_white *DISPLAY_OUE*">
			     <td id="*OUEOO_GID*" onClick="betEvent('*GID*','*OUEOO_RTYPE*','*IORATIO_OUEOO*','*OUE_WTYPE*');" style="cursor:pointer"  class="*TD_CLASS_OUEOO*" width="50%"><div class="more_font"><span class="m_team">*STR_OUEOO*</span><span class="m_red_bet" title="*STR_OUEOO*">*IORATIO_OUEOO*</span></div></td>
			     <td id="*OUEOE_GID*" onClick="betEvent('*GID*','*OUEOE_RTYPE*','*IORATIO_OUEOE*','*OUE_WTYPE*');" style="cursor:pointer"  class="*TD_CLASS_OUEOE*" width="50%"><div class="more_font"><span class="m_team">*STR_OUEOE*</span><span class="m_red_bet" title="*STR_OUEOE*">*IORATIO_OUEOE*</span></div></td>
			  </tr>
			  <tr class="more_color *DISPLAY_OUE*">
			     <td id="*OUEUO_GID*" onClick="betEvent('*GID*','*OUEUO_RTYPE*','*IORATIO_OUEUO*','*OUE_WTYPE*');" style="cursor:pointer"  class="*TD_CLASS_OUEUO*" width="50%"><div class="more_font"><span class="m_team">*STR_OUEUO*</span><span class="m_red_bet" title="*STR_OUEUO*">*IORATIO_OUEUO*</span></div></td>
			     <td id="*OUEUE_GID*" onClick="betEvent('*GID*','*OUEUE_RTYPE*','*IORATIO_OUEUE*','*OUE_WTYPE*');" style="cursor:pointer"  class="*TD_CLASS_OUEUE*" width="50%"><div class="more_font"><span class="m_team">*STR_OUEUE*</span><span class="m_red_bet" title="*STR_OUEUE*">*IORATIO_OUEUE*</span></div></td>
			  </tr>
			  <!-- END DYNAMIC BLOCK: OUE -->
			
			</table>
			<!---------- OUE ---------->
			
			
			
			
			<!---------- OUP ---------->
			<table id="model_OUP" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">进球 大 / 小 & 最先进球</span>
			    	<span class="more_og"></span>
			    	<span class="more_star_bg"><span id="star_OUP" name="star_OUP" onClick="addFavorites('OUP');" class="star_down"></span></span>
			    </th>
			  </tr>
			
			
			  <!-- START DYNAMIC BLOCK: OUP -->
			  <tr class="*DISPLAY_OUP*">
			      <td class="game_team"><span>*TEAM_H* (最先进球)</span></td>
			      <td class="game_team"><span>*TEAM_C* (最先进球)</span></td>
			  </tr>

			  <tr class="more_white *DISPLAY_OUP*">
			     <td id="*OUPOH_GID*" onClick="betEvent('*GID*','*OUPOH_RTYPE*','*IORATIO_OUPOH*','*OUP_WTYPE*');" style="cursor:pointer"  class="*TD_CLASS_OUPOH*" width="50%"><div class="more_font"><span class="m_team">*STR_OUPOH*</span><span class="m_red_bet" title="*STR_OUPOH*">*IORATIO_OUPOH*</span></div></td>
			     <td id="*OUPOC_GID*" onClick="betEvent('*GID*','*OUPOC_RTYPE*','*IORATIO_OUPOC*','*OUP_WTYPE*');" style="cursor:pointer"  class="*TD_CLASS_OUPOC*" width="50%"><div class="more_font"><span class="m_team">*STR_OUPOC*</span><span class="m_red_bet" title="*STR_OUPOC*">*IORATIO_OUPOC*</span></div></td>
			  </tr>
			  <tr class="more_color *DISPLAY_OUP*">
			     <td id="*OUPUH_GID*" onClick="betEvent('*GID*','*OUPUH_RTYPE*','*IORATIO_OUPUH*','*OUP_WTYPE*');" style="cursor:pointer"  class="*TD_CLASS_OUPUH*" width="50%"><div class="more_font"><span class="m_team">*STR_OUPUH*</span><span class="m_red_bet" title="*STR_OUPUH*">*IORATIO_OUPUH*</span></div></td>
			     <td id="*OUPUC_GID*" onClick="betEvent('*GID*','*OUPUC_RTYPE*','*IORATIO_OUPUC*','*OUP_WTYPE*');" style="cursor:pointer"  class="*TD_CLASS_OUPUC*" width="50%"><div class="more_font"><span class="m_team">*STR_OUPUC*</span><span class="m_red_bet" title="*STR_OUPUC*">*IORATIO_OUPUC*</span></div></td>
			  </tr>
			  <!-- END DYNAMIC BLOCK: OUP -->
			
			</table>
			<!---------- OUP ---------->
			


			<!---------- TK ---------->       
		 	<table id="model_TK" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">开球球队</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_TK" name="star_TK" onClick="addFavorites('TK');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: TK -->
				<tr class="more_white">
						<td id="*TKH_GID*" onClick="betEvent('*GID*','TKH','*IORATIO_TKH*','TK');" style="cursor:pointer"  class="*TD_CLASS_TKH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_TKH*</span></div></td>
						<td id="*TKC_GID*" onClick="betEvent('*GID*','TKC','*IORATIO_TKC*','TK');" style="cursor:pointer"  class="*TD_CLASS_TKC*" width="50%"><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_TKC*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: TK -->
	
			</table>
			<!---------- TK ---------->

			<!---------- PA ---------->       
		 	<table id="model_PA" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">点球荣获（除开点球大战）</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_PA" name="star_PA" onClick="addFavorites('PA');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: PA -->
				<tr class="more_white">
						<td id="*PAH_GID*" onClick="betEvent('*GID*','PAH','*IORATIO_PAH*','PA');" style="cursor:pointer"  class="*TD_CLASS_PAH*" width="50%"><div class="more_font"><span class="m_team">是</span><span class="m_red_bet" title="是">*IORATIO_PAH*</span></div></td>
						<td id="*PAC_GID*" onClick="betEvent('*GID*','PAC','*IORATIO_PAC*','PA');" style="cursor:pointer"  class="*TD_CLASS_PAC*" width="50%"><div class="more_font"><span class="m_team">不是</span><span class="m_red_bet" title="不是">*IORATIO_PAC*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: PA -->
	
			</table>
			<!---------- PA ---------->			
			
			
			
			
			<!---------- OT ---------->
			<table id="model_OT" cellpadding="0" cellspacing="0" border="0" class="more_table2">
			  <tr>
			  	<th class="more_title4" colspan="2">
			    	<span style="float: left;">加时赛</span>
			    	<span class="more_og"></span>
			    	<span class="more_star_bg"><span id="star_OT" name="star_OT" onClick="addFavorites('OT');" class="star_down"></span></span>
			    </th>
			  </tr>

			  <!-- START DYNAMIC BLOCK: OT -->

			  <tr class="more_white">
			     <td id="*OTY_GID*" onClick="betEvent('*GID*','OTY','*IORATIO_OTY*','OT');" style="cursor:pointer"  class="*TD_CLASS_OTY*" width="50%"><div class="more_font"><span class="m_team">是</span><span class="m_red_bet" title="是">*IORATIO_OTY*</span></div></td>
			     <td id="*OTN_GID*" onClick="betEvent('*GID*','OTN','*IORATIO_OTN*','OT');" style="cursor:pointer"  class="*TD_CLASS_OYN*" width="50%"><div class="more_font"><span class="m_team">不是</span><span class="m_red_bet" title="不是">*IORATIO_OTN*</span></div></td>
			  </tr>
			  <!-- END DYNAMIC BLOCK: OT -->

			</table>
			<!---------- OT ---------->
			
								
			
			
			<!---------- RCD ---------->       
		 	<table id="model_RCD" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="2">
							<span style="float: left;">红卡（球员）</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_RCD" name="star_RCD" onClick="addFavorites('RCD');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: RCD -->
				<tr class="more_white">
						<td id="*RCDH_GID*" onClick="betEvent('*GID*','RCDH','*IORATIO_RCDH*','RCD');" style="cursor:pointer"  class="*TD_CLASS_RCDH*" width="50%"><div class="more_font"><span class="m_team">是</span><span class="m_red_bet" title="是">*IORATIO_RCDH*</span></div></td>
						<td id="*RCDC_GID*" onClick="betEvent('*GID*','RCDC','*IORATIO_RCDC*','RCD');" style="cursor:pointer"  class="*TD_CLASS_RCDC*" width="50%"><div class="more_font"><span class="m_team">不是</span><span class="m_red_bet" title="不是">*IORATIO_RCDC*</span></div></td>
				</tr>
				<!-- END DYNAMIC BLOCK: RCD -->
	
			</table>
			<!---------- RCD ---------->
			

			<!---------- CN ---------->
		 	<table id="model_CN" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="3">
							<span style="float: left;">最先 / 最后角球</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_CN" name="star_CN" onClick="addFavorites('CN');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: CN -->
				<tr>
						<td class="game_team"><span>最先角球</span></td>
						<td class="game_team"><span>最后角球</span></td>
						<!--<td class="game_team"><span>无角球</span></td>-->	
				</tr>
				
				
				<tr class="more_white">
						<td id="*CNFH_GID*" onClick="betEvent('*GID*','CNFH','*IORATIO_CNFH*','SP');" style="cursor:pointer"  class="*TD_CLASS_CNFH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_CNFH*</span></div></td>
						<td id="*CNLH_GID*" onClick="betEvent('*GID*','CNLH','*IORATIO_CNLH*','SP');" style="cursor:pointer"  class="*TD_CLASS_CNLH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_CNLH*</span></div></td>
						<!--<td rowspan="2" id="*CNFN_GID*" onClick="betEvent('*GID*','CNFN','*IORATIO_CNFN*','SP');" style="cursor:pointer"  class="*TD_CLASS_CNFN*" width="30%"><div class="more_font"><span class="m_team">无</span><span class="m_red_bet" title="无">*IORATIO_CNFN*</span></div></td>-->

				</tr>
				
				<tr class="more_white">
						<td id="*CNFC_GID*" onClick="betEvent('*GID*','CNFC','*IORATIO_CNFC*','SP');" style="cursor:pointer"  class="*TD_CLASS_CNFC*" ><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_CNFC*</span></div></td>
						<td id="*CNLC_GID*" onClick="betEvent('*GID*','CNLC','*IORATIO_CNLC*','SP');" style="cursor:pointer"  class="*TD_CLASS_CNLC*" ><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_CNLC*</span></div></td>
				</tr>

				<!-- END DYNAMIC BLOCK: CN -->
	
			</table>
			<!---------- CN ---------->
			
			
			<!---------- CD ---------->
		 	<table id="model_CD" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="3">
							<span style="float: left;">第一张 / 最后一张罚牌</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_CD" name="star_CD" onClick="addFavorites('CD');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: CD -->
				<tr>
						<td class="game_team"><span>第一张罚牌</span></td>
						<td class="game_team"><span>最后一张罚牌</span></td>
						<!--<td class="game_team"><span>没有罚牌</span></td>	-->
				</tr>
				
				
				<tr class="more_white">
						<td id="*CDFH_GID*" onClick="betEvent('*GID*','CDFH','*IORATIO_CDFH*','SP');" style="cursor:pointer"  class="*TD_CLASS_CDFH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_CDFH*</span></div></td>
						<td id="*CDLH_GID*" onClick="betEvent('*GID*','CDLH','*IORATIO_CDLH*','SP');" style="cursor:pointer"  class="*TD_CLASS_CDLH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_CDLH*</span></div></td>
						<!--<td rowspan="2" id="*CDFN_GID*" onClick="betEvent('*GID*','CDFN','*IORATIO_CDFN*','SP');" style="cursor:pointer"  class="*TD_CLASS_CDFN*" width="30%"><div class="more_font"><span class="m_team">无</span><span class="m_red_bet" title="无">*IORATIO_CDFN*</span></div></td>-->

				</tr>
				
				<tr class="more_white">
						<td id="*CDFC_GID*" onClick="betEvent('*GID*','CDFC','*IORATIO_CDFC*','SP');" style="cursor:pointer"  class="*TD_CLASS_CDFC*" ><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_CDFC*</span></div></td>
						<td id="*CDLC_GID*" onClick="betEvent('*GID*','CDLC','*IORATIO_CDLC*','SP');" style="cursor:pointer"  class="*TD_CLASS_CDLC*" ><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_CDLC*</span></div></td>
				</tr>

				<!-- END DYNAMIC BLOCK: CD -->
	
			</table>
			<!---------- CD ---------->


			
			<!---------- YC ---------->
		 	<table id="model_YC" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="3">
							<span style="float: left;">最先 / 最后界外球</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_YC" name="star_YC" onClick="addFavorites('YC');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: YC -->
				<tr>
						<td class="game_team"><span>最先界外球</span></td>
						<td class="game_team"><span>最后界外球</span></td>
						<!--<td class="game_team"><span>没发卡</span></td>-->	
				</tr>
				
				
				<tr class="more_white">
						<td id="*YCFH_GID*" onClick="betEvent('*GID*','YCFH','*IORATIO_YCFH*','SP');" style="cursor:pointer"  class="*TD_CLASS_YCFH*" width="35%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_YCFH*</span></div></td>
						<td id="*YCLH_GID*" onClick="betEvent('*GID*','YCLH','*IORATIO_YCLH*','SP');" style="cursor:pointer"  class="*TD_CLASS_YCLH*" width="35%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_YCLH*</span></div></td>
						<!--<td rowspan="2" id="*YCFN_GID*" onClick="betEvent('*GID*','YCFN','*IORATIO_YCFN*','SP');" style="cursor:pointer; border-bottom:none; border-bottom:1px solid #C2B1A1\9;"  class="*TD_CLASS_YCFN*" width="30%"><div class="more_font"><span class="m_team">无</span><span class="m_red_bet" title="无">*IORATIO_YCFN*</span></div></td>-->

				</tr>
				
				<tr class="more_white">
						<td id="*YCFC_GID*" onClick="betEvent('*GID*','YCFC','*IORATIO_YCFC*','SP');" style="cursor:pointer"  class="*TD_CLASS_YCFC*" ><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_YCFC*</span></div></td>
						<td id="*YCLC_GID*" onClick="betEvent('*GID*','YCLC','*IORATIO_YCLC*','SP');" style="cursor:pointer"  class="*TD_CLASS_YCLC*" ><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_YCLC*</span></div></td>
				</tr>

				<!-- END DYNAMIC BLOCK: YC -->
	
			</table>
			<!---------- YC ---------->
			
			
			<!---------- GA ---------->
			<table id="model_GA" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="3">
							<span style="float: left;">最先 / 最后球门球</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_GA" name="star_GA" onClick="addFavorites('GA');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: GA -->
				<tr>
						<td class="game_team"><span>最先球门球</span></td>
						<td class="game_team"><span>最后球门球</span></td>
						<!--<td class="game_team"><span>没发卡</span></td>-->	
				</tr>
				
				
				<tr class="more_white">
						<td id="*GAFH_GID*" onClick="betEvent('*GID*','GAFH','*IORATIO_GAFH*','SP');" style="cursor:pointer"  class="*TD_CLASS_GAFH*" width="35%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_GAFH*</span></div></td>
						<td id="*GALH_GID*" onClick="betEvent('*GID*','GALH','*IORATIO_GALH*','SP');" style="cursor:pointer"  class="*TD_CLASS_GALH*" width="35%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_GALH*</span></div></td>
						<!--<td rowspan="2" id="*GAFN_GID*" onClick="betEvent('*GID*','GAFN','*IORATIO_GAFN*','SP');" style="cursor:pointer; border-bottom:none; border-bottom:1px solid #C2B1A1\9;"  class="*TD_CLASS_GAFN*" width="30%"><div class="more_font"><span class="m_team">无</span><span class="m_red_bet" title="无">*IORATIO_GAFN*</span></div></td>-->

				</tr>
				
				<tr class="more_white">
						<td id="*GAFC_GID*" onClick="betEvent('*GID*','GAFC','*IORATIO_GAFC*','SP');" style="cursor:pointer"  class="*TD_CLASS_GAFC*" ><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_GAFC*</span></div></td>
						<td id="*GALC_GID*" onClick="betEvent('*GID*','GALC','*IORATIO_GALC*','SP');" style="cursor:pointer"  class="*TD_CLASS_GALC*" ><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_GALC*</span></div></td>
				</tr>

				<!-- END DYNAMIC BLOCK: GA -->
	
			</table>
			<!---------- GA ---------->
			
			
			<!---------- ST ---------->
		 	<table id="model_ST" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="3">
							<span style="float: left;">最先 / 最后替补</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_ST" name="star_ST" onClick="addFavorites('ST');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<!-- START DYNAMIC BLOCK: ST -->
				<tr>
						<td class="game_team"><span>最先替补</span></td>
						<td class="game_team"><span>最后替补</span></td>
						<!--<td class="game_team"><span>无替补</span></td>-->
				</tr>
				
				
				<tr class="more_white">
						<td id="*STFH_GID*" onClick="betEvent('*GID*','STFH','*IORATIO_STFH*','SP');" style="cursor:pointer"  class="*TD_CLASS_STFH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_STFH*</span></div></td>
						<td id="*STLH_GID*" onClick="betEvent('*GID*','STLH','*IORATIO_STLH*','SP');" style="cursor:pointer"  class="*TD_CLASS_STLH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_STLH*</span></div></td>
						<!--<td rowspan="2" id="*STFN_GID*" onClick="betEvent('*GID*','STFN','*IORATIO_STFN*','SP');" style="cursor:pointer"  class="*TD_CLASS_STFN*" width="30%"><div class="more_font"><span class="m_team">无</span><span class="m_red_bet" title="无">*IORATIO_STFN*</span></div></td>-->

				</tr>
				
				<tr class="more_white">
						<td id="*STFC_GID*" onClick="betEvent('*GID*','STFC','*IORATIO_STFC*','SP');" style="cursor:pointer"  class="*TD_CLASS_STFC*" ><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_STFC*</span></div></td>
						<td id="*STLC_GID*" onClick="betEvent('*GID*','STLC','*IORATIO_STLC*','SP');" style="cursor:pointer"  class="*TD_CLASS_STLC*" ><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_STLC*</span></div></td>
				</tr>

				<!-- END DYNAMIC BLOCK: ST -->
	
			</table>
			<!---------- ST ---------->
			
			
			
			<!---------- OS ---------->
		 	<table id="model_OS" border="0" cellpadding="0" cellspacing="0" class="more_table2">
				<tr>
						<th class="more_title4" colspan="3">
							<span style="float: left;">最先 / 最后越位</span>
							<span class="more_og"></span>
							<span class="more_star_bg"><span id="star_OS" name="star_OS" onClick="addFavorites('OS');" class="star_down" ></span></span>
						</th>
				</tr>
				
				<tr>
						<td class="game_team"><span>最先越位</span></td>
						<td class="game_team"><span>最后越位</span></td>
						<!--<td class="game_team"><span>无越位</span></td>-->
				</tr>
				<!-- START DYNAMIC BLOCK: OS -->
				
				
				<tr class="more_white">
						<td id="*OSFH_GID*" onClick="betEvent('*GID*','OSFH','*IORATIO_OSFH*','SP');" style="cursor:pointer"  class="*TD_CLASS_OSFH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_OSFH*</span></div></td>
						<td id="*OSLH_GID*" onClick="betEvent('*GID*','OSLH','*IORATIO_OSLH*','SP');" style="cursor:pointer"  class="*TD_CLASS_OSLH*" width="50%"><div class="more_font"><span class="m_team">*TEAM_H*</span><span class="m_red_bet" *TITLE_TEAM_H*>*IORATIO_OSLH*</span></div></td>
						<!--<td rowspan="2" id="*OSFN_GID*" onClick="betEvent('*GID*','OSFN','*IORATIO_OSFN*','SP');" style="cursor:pointer"  class="*TD_CLASS_OSFN*" width="30%"><div class="more_font"><span class="m_team">无</span><span class="m_red_bet" title="无">*IORATIO_OSFN*</span></div></td>-->

				</tr>
				
				<tr class="more_white">
						<td id="*OSFC_GID*" onClick="betEvent('*GID*','OSFC','*IORATIO_OSFC*','SP');" style="cursor:pointer"  class="*TD_CLASS_OSFC*" ><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_OSFC*</span></div></td>
						<td id="*OSLC_GID*" onClick="betEvent('*GID*','OSLC','*IORATIO_OSLC*','SP');" style="cursor:pointer"  class="*TD_CLASS_OSLC*" ><div class="more_font"><span class="m_team">*TEAM_C*</span><span class="m_red_bet" *TITLE_TEAM_C*>*IORATIO_OSLC*</span></div></td>
				</tr>

				<!-- END DYNAMIC BLOCK: OS -->
	
			</table>
			<!---------- OS ---------->
</div>

</div>

</body>
</html>
