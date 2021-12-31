var AutoRenewID;
var ChkUserTimerID;
var ChkUserTime = 10;
var ReloadTime = 60;
var TimerID = 0;
var gamedate = "";
var T_color_h = "";
var T_color_c = "";
var Livegtype ="";
var Livegidm ="";
var gameAry = new Array("FT","BK","TN","VB","BS","OP");
var pages='TVbut';
top.mcurrency=opener.top.mcurrency;
function onload() {

	//chg_page_images("BEbut");
	//chg_page();
	onloadGame();
	reloadioratio();

}
function reloadioratio(){
	
	//document.getElementById("right_div").style.display = "";
	
	//mem_order.self.location =o_path;
	Live_mem.self.location  = "./game_ioratio_view.php?uid="+uid+"&langx="+parent.top.langx+"&gtype="+Livegtype+"&gidm="+Livegidm+"&gdate="+document.getElementById("gdate").value;
}
function onloadGame(){
	var tmp_opt = "";
	//====== 铏曠悊鐞冮閬稿柈
	tmp_opt = "<option value='All' selected>"+top.str_game_list+"</option>\n";
	for (var i = 0; i < gameAry.length; i++) {
		tmp_opt+= "<option value='"+gameAry[i]+"'>"+eval("top.str_"+gameAry[i])+"</option>\n";
	}
	tmp_opt = "<select id=\"gameOpt\" name=\"gameOpt\" onChange=\"chggype()\" class=\"select\">\n"+tmp_opt+"</select>";
	document.getElementById("game_type").innerHTML = tmp_opt;

	//====== 铏曠悊鏃ユ湡閬稿柈
	tmp_opt = "";
	for (i = 0; i < GameDate.length; i++) {
		tmp_opt+= "<option class='se_date' value='"+GameDate[i]+"'>"+GameDate[i]+"</option>\n";
	}
	tmp_opt = "<select class='se_date' id=\"gdate\" name=\"gdate\" onChange=\"chggdate()\">\n"+tmp_opt+"</select>";
	document.getElementById("date_list").innerHTML = tmp_opt;
	//document.getElementById("alone_btn").alt = top.str_alone;

	//====== 璁€鍙栬辰绋�
	document.getElementById("gameOpt").value = "All";
	Livegtype ="All";
	reloadGame();
	StartChkTimer();
	if (videoData != "") {
		//document.getElementById("DemoLink").style.display = "none";
		registLive.self.location = "./RegistLive.php?uid="+uid+"&langx="+langx+"&gameary="+videoData+"&liveid="+mtvid;
	}
}


function chggype(){
	var gameOpt =document.getElementById("gameOpt").value;
        Livegtype =gameOpt;
	reloadGame();
	reloadioratio();

}
function chggdate(){
	check_gamelist();
	reloadGame();
	reloadioratio();
}
function reloadGame() {

	clearInterval(AutoRenewID);
	TimerID = 0;
	reloadgame.self.location = "./game_list.php?uid="+uid+"&langx="+parent.top.langx+"&gtype="+Livegtype+"&gdate="+document.getElementById("gdate").value;
}

function ResetTimer() {
	document.getElementById("timer_str").innerHTML = ReloadTime+"&nbsp;";
	AutoRenewID = setInterval("RenewTimerStr()",1000);
}
function RenewTimerStr() {
	document.getElementById("timer_str").innerHTML = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	if ((ReloadTime - TimerID) <= 1) {
		TimerID = 0;
		reloadGame();
	} else {
		TimerID++;
		var tmp = (ReloadTime - TimerID);
		if (tmp < 10) { tmp = "&nbsp;&nbsp;"+tmp; }
		document.getElementById("timer_str").innerHTML = tmp+"&nbsp;";
	}
}

function independent() {
	if (document.getElementById("top_div").style.display == "none") {	//鍙栨秷鐛ㄧ珛椤ず
		document.getElementById("top_div").style.display = "";
		document.getElementById("main").style.display = "";
		document.getElementById("main_bet").style.display = "";
		
		document.getElementById("alone_btn").alt = top.str_alone;
		if (document.all) { // IE
		    window.resizeTo(791,640);
		} else { // NETSCAPE
		    window.outerHeight = 640;
		    window.outerWidth = 791;
		}
	} else {	//鐛ㄧ珛椤ず
		if (document.all) { // IE
		    window.resizeTo(510,570);
		} else { // NETSCAPE
		    window.outerHeight = 570;
		    window.outerWidth = 510;
		}
		//document.getElementById("right_div").style.display = "none";
		//chg_page(pages);
		document.getElementById("top_div").style.display = "none";
		document.getElementById("alone_btn").alt = top.str_back;
	}
}

//====== 鍟熷嫊 user 瀹氭檪妾㈡煡瑷堟檪鍣�
function StartChkTimer() {
	clearInterval(ChkUserTimerID);
	ChkUserTimerID = setInterval("ChkUid('"+mtvid+"','"+eventid+"')",ChkUserTime * 60 *1000);
}

//=== 妾㈡煡 user id
function ChkUid(id, gid) {
	try{
		reloadPHP.self.location = "./chk_registid.php?uid="+uid+"&langx="+parent.top.langx+"&regist_id="+id+"&liveid="+window.opener.top.liveid+"&gid="+gid;
	} catch (E) {
//alert("./chk_registid.php?uid="+uid+"&langx="+parent.top.langx+"&regist_id="+id+"&liveid="+window.opener.top.liveid+"&gid="+gid);
		self.location = "http://"+document.domain;
	}
}

function send_result(datas) {
	var tmp = datas.split(",");
	if (tmp.length <= 1) {
		tmp[0] = datas;
	}
	if (tmp[0] == "false") {
		self.location.reload();
	}
	if (tmp.length > 1) {
		SetClothesColor(tmp[1], tmp[2]);
	}
}

//=== QA
function GoToQAPage() {
	window.open("/tpl/member/"+langx+"/QA.html","LiveQA","width=780,height=600,top=0,left=0,status=no,toolbar=no,scrollbars=yes,resizable=yes,personalbar=no");
}

function ShowVideo() {
	var swf_name = "liveTV_"+langx.substring(3)+".swf";
	var swf_str = "<object id=\"liveTV\" classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\""+
	              "width=\"480\" height=\"410\" codebase='http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab#version=9,0,124,0'>"+
	              "<param name=\"movie\" value=\""+swf_name+"\" />"+
	              "<param name=\"quality\" value=\"high\" />"+
	              "<param name=\"bgcolor\" value=\"#1C0D00\" />"+
	              "<param name=\"allowScriptAccess\" value=\"sameDomain\" />"+
	              "<embed name=\"liveTV\" id=\"liveTV\" src=\""+swf_name+"\" quality=\"high\" bgcolor=\"#1C0D00\""+
	                  "width=\"480\" height=\"410\" align=\"middle\""+
	                  "play=\"true\""+
	                  "loop=\"false\""+
	                  "quality=\"high\""+
	                  "allowScriptAccess=\"sameDomain\""+
	                  "type=\"application/x-shockwave-flash\""+
	                  "pluginspage='http://www.adobe.com/go/getflashplayer'>"+
	              "</embed>"+
	          "</object>";
	videoFrame.innerHTML = swf_str;
	videoFrame.style.display = "";
	document.getElementById("FlahLayer").style.display = "";
	document.getElementById("video_msg").style.display = "";
	document.getElementById("DemoImgLayer").style.display = "none";
	document.getElementById("demo_msg").style.display = "";
	document.getElementById("demo_msg").innerHTML = "<font class='mag_info'>"+top.str_demo+"</font>";
}

//=== 鍌抽仦鍙冩暩
function appInit() {
	liveTV.FLashFunction(langx);
}

window.onbeforeunload = unload_swf;
function unload_swf() {
	var obj=document.getElementById("liveTV");
	try {
		obj.unloadSWF();
	} catch (e) {}
	for (var x in obj){
		try{
			obj[x]=null;
		}catch(e){}
	}
}

function unLoad() {
	clearInterval(AutoRenewID);
	clearInterval(ChkUserTimerID);
}
/**
 * 璩界▼鍒楄〃
 */
function reload_game() {
	check_gamelist();
	var shows = document.getElementById("tb_layer").innerHTML;
	var tr_data = "";
	for (var i = 0; i < GameData.length; i++) {
		tr_data+= showlayer(document.getElementById("tr_layer").innerHTML,i)+"\n";
	//	tr_data = tr_data.replace("*GAMEDATE*",gamedate);
	}
	shows = shows.replace("*GAMEDATE*",gamedate);
	shows = shows.replace("*GAMELIST*",tr_data);
	showlayers.innerHTML = shows;
	parent.ResetTimer();
//	alert(eventlive);
	if(eventlive=="Y"){
		OpenTVbet(eventid);
	}

}

function showlayer(layers,i){
	if (GameData[i][6] == "Y") {	//鍒ゆ柗鏄惁闁嬭辰
		
		if ((i)%2==0){
			layers = layers.replace("*list_color*",'class="even_detail_1"');
			}else{
			layers = layers.replace("*list_color*",'class="even_detail_2"');
			}
			
		layers = layers.replace("*ID*",i);
		layers = layers.replace("*STYLE*","style='cursor:hand'");
		//layers = layers.replace("*STYLE_TIME*","time");
		//layers = layers.replace("*STYLE_GTYPE*","gtype");
		//layers = layers.replace("*STYLE_TEAM*","team");
	} else {
		
		if ((i)%2==0){
			layers = layers.replace("*list_color*",'class="even_detail_1"');
			}else{
			layers = layers.replace("*list_color*",'class="even_detail_2"');
			}
		
		layers = layers.replace("*ID*","");
		layers = layers.replace("*STYLE*",'style="display:none"');
		//layers = layers.replace("*STYLE_TIME*","time_2");
		//layers = layers.replace("*STYLE_GTYPE*","gtype_2");
		//layers = layers.replace("*STYLE_TEAM*","team_2");
	}
/*	
	if (GameData[i][5] == "Y" && GameData[i][6] == "Y") {	//鍒ゆ柗鏄惁鐐虹啽闁€璩�
		layers = layers.replace("*HOT_PIC*","<img src='/images/member/hot_1.gif' class=\"hot\">");
	} else if (GameData[i][5] == "Y") {
		layers = layers.replace("*HOT_PIC*","<img src='/images/member/hot_2.gif' class=\"hot\">");
	} else {
		layers = layers.replace("*HOT_PIC*","");
	}
*/	
	layers = layers.replace("*GTYPE*",eval("top.str_"+GameData[i][0]));
	layers = layers.replace("*TIME*",GameData[i][2]);
	layers = layers.replace("*TEAMH*",GameData[i][3]);
	layers = layers.replace("*TEAMC*",GameData[i][4]);
	layers = layers.replace("*LEAGUE*",GameData[i][9]);
	return layers;
}

function OpenTV(i) {
	/*
	if (videoFrame.style.display == "") {
		videoFrame.style.display = "none";
		document.getElementById("DemoImgLayer").style.display = "";
		document.getElementById("FlahLayer").style.display = "none";
		document.getElementById("demo_msg").style.display = "none";
		unload_swf();
	}
	*/

	document.getElementById("DemoLink").style.display = "none";
	
	if (i == "") { return false; }
	eventid = GameData[i][1];
	StartChkTimer();
	videoData = GameData[i][1]+","+GameData[i][3]+","+GameData[i][4]+","+GameData[i][9]+","+GameData[i][7]+","+GameData[i][8];
	registLive.self.location = "./RegistLive.php?uid="+uid+"&langx="+langx+"&gameary="+GameData[i][1]+"&liveid="+mtvid;
	Livegtype= GameData[i][0];
	Livegidm = GameData[i][10];
	reloadioratio();
	go_betpage();
	document.getElementById("gameOpt").value=Livegtype;
	reloadGame();
}

function OpenTVbet(eventid) {
	document.getElementById("DemoLink").style.display = "none";
	if (eventid == "") { return false; }
	StartChkTimer();
	for (var i = 0; i < GameData.length; i++){
		if(GameData[i][1]==eventid){
			videoData = GameData[i][1]+","+GameData[i][3]+","+GameData[i][4]+","+GameData[i][9]+","+GameData[i][7]+","+GameData[i][8];
			Livegtype= GameData[i][0];
			Livegidm = GameData[i][10];
			}
		}
	
	registLive.self.location = "./RegistLive.php?uid="+uid+"&langx="+langx+"&gameary="+eventid+"&liveid="+mtvid;
	reloadioratio();
	go_betpage();
	document.getElementById("gameOpt").value=Livegtype;
	reloadGame();
}

function GetVideo(vurl) {
	if (vurl != "") {
		var tmp = videoData.split(",");
		document.getElementById("DefLive").src = vurl;
		document.getElementById("DefLive").style.display = "";
		document.getElementById("video_msg").style.display = "";
		document.getElementById("FlahLayer").style.display = "";
		document.getElementById("DemoImgLayer").style.display = "none";
		//=== 闅婂悕
		SetClothesColor(tmp[4], tmp[5]);
		document.getElementById("league").innerHTML = tmp[3]+"<BR>";
		document.getElementById("team").innerHTML = tmp[1]+"&nbsp;&nbsp;VS&nbsp;&nbsp;"+tmp[2];
		document.getElementById("video_msg").style.display = "";
	}
}
function SetClothesColor(color_h, color_c) {
	if (color_h == "") { document.getElementById("team_h").style.display = "none"; }
	if (color_c == "") { document.getElementById("team_c").style.display = "none"; }
	if (T_color_h != color_h && color_h != "") {
		T_color_h = color_h;
		document.getElementById("team_h").src = "/images/member/T_"+T_color_h+".gif";
		document.getElementById("team_h").style.display = "";
	}
	if (T_color_c != color_c && color_c != "") {
		T_color_c = color_c;
		document.getElementById("team_c").src = "/images/member/T_"+T_color_c+".gif";
		document.getElementById("team_c").style.display = "";
	}
}

function chg_page(tmppage){
	//chg_page_images(tmppage);
	chg_page_height();
}
function chg_page_images(tmppage){
	if(tmppage =='TVbut'){		
		document.getElementById("table_Live_order").style.display = "none";
		document.getElementById("right_div").style.display = "";
		document.getElementById("BEbut").src ="/images/member/"+langx+"/live_BEbut3.gif";
		document.getElementById("TVbut").src ="/images/member/"+langx+"/live_TVbut.gif";	
	}else if(tmppage =='BEbut'){
		document.getElementById("table_Live_order").style.display = "";
		document.getElementById("right_div").style.display = "none";
		document.getElementById("BEbut").src ="/images/member/"+langx+"/live_BEbut.gif";
		document.getElementById("TVbut").src ="/images/member/"+langx+"/live_TVbut3.gif";
	}else{
		document.getElementById("table_Live_order").style.display = "none";
		document.getElementById("right_div").style.display = "";
		document.getElementById("BEbut").src ="/images/member/"+langx+"/live_BEbut3.gif";
		document.getElementById("TVbut").src ="/images/member/"+langx+"/live_TVbut.gif";
	}
}
function chg_page_height(){
	live_game_heigth();
}
function mouseEnter_pointer(tmp){
	try{
		//document.getElementById(tmp).src ="";
	}catch(E){}
}

function mouseOut_pointer(tmp){
	try{
		//document.getElementById(tmp).src ="none";
	}catch(E){}
}


function live_order_height(tmppage){
	document.all("bet_order_frame").height = tmppage * 1+5;
}

function live_game_heigth(){
	// var tmpEnd = Live_mem.frames("Live_mem").document.body.scrollHeight+5;
	document.getElementById("Live_mem").height='478px';
}

/*function live_game_heigth(){
    try{
        document.getElementById("Live_mem").height = 478;
        var h = document.getElementById("Live_mem").contentWindow.document.getElementById("right_div").clientHeight;
        document.getElementById("Live_mem").contentWindow.document.body.style.height = h+"px";

    }catch(e){
        systemMsg(e.toString());
    }

    /!*if(document.getElementById("top_tv").style.display=="none"){
        top. select_type="betlist";
        top.bet_page=true;
    }*!/
    closename="";

    if(top.select_type=="gamelist"){
        document.getElementById("main_bet").style.display = "none";
        document.getElementById("main").style.display = "";
        document.getElementById("even_list").style.display = "";
        document.getElementById("btn_bet_main").focus();
    }else{
        try{
            document.getElementById("main_bet").style.display = "";
            document.getElementById("Live_mem").contentWindow.initfix("main_bet_head");//立即下注上方head
            document.getElementById("main").style.display = "none";
            document.getElementById("Live_mem").contentWindow.document.getElementById("bet_list_main").focus();
        }catch(e){
           // document.getElementById("btn_bet").focus();
        }
        if(document.getElementById("even_none").style.display=="none" || document.getElementById("wtype_close").style.display=="none"){
        	try {
                document.getElementById("no_bet_head").style.display="none";
                document.getElementById("no_bet_head_bak").style.display="none";
			}catch (e){ }

        }

    }


}*/

function show_bet_ps(){
	document.getElementById("main_bet").style.display = "";
	}

function go_betpage(){
	eventlive ="";
	document.getElementById("main_bet").style.display = "";
	document.getElementById("main").style.display = "none";
	chg_page_height();
	try{
		close_bet();
	}catch(E){}
}
	
function go_livepage(){
	eventlive ="";
	document.getElementById("main").style.display = "";
	document.getElementById("main_bet").style.display = "none";

}	
function close_bet(){
	bet_order_frame.location.replace("");
	document.getElementById('bet_order_frame').height =0;
}

function close_bet_finish(){
	bet_order_frame.location.replace("");
	document.getElementById('bet_order_frame').height =0;
	document.getElementById("bet_ps").style.display = "none";
}

function onloadSet(w,h,frameName){
	document.getElementById(frameName).width  =240;
	document.getElementById(frameName).height =h;

}

function check_gamelist() {
	if(GameData.length==0){
		document.getElementById("even_none").style.display ="";
		document.getElementById("even_list").style.display ="none";
		
		document.getElementById("bet_none").style.display ="";
		document.getElementById("bet_box").style.display ="none";		
		}else{
		document.getElementById("even_list").style.display ="";
		document.getElementById("even_none").style.display ="none";	
		
		document.getElementById("bet_box").style.display ="";
		document.getElementById("bet_none").style.display ="none";		
		}	
}	
