var video_version = "";
var live_refresh_flage = 'Y' ; // live.php 刷新标志

// 选择体育
$("#select_gtype").click(function(){
    if($('#show_gtype').css('display') === 'none'){
        $("#show_gtype").css('display','block');
    }else{
        $("#show_gtype").css('display','none');
    }
});
$('#option_gtype').on('click','li',function () {
    var title = $(this).text();
    var type = $(this).data('value');
    // console.log(type);
    $('.live_listTB').find('tr').hide(); // 隐藏全部
    $('.'+type+'_LIST').show(); // 显示当前选择球类
    $('#select_gtype').text(title);
    $('#show_gtype').hide();
});

function showBetList(){
    //if(nolive_sw=="N") return;
    //視訊播放後會顯示異常  顯示betlist的時候再將視訊畫面打開
    if(video_version == "perform"){
        $("#dome_L").show() ;
    }
    else if (video_version == "unas"){
        $("#videoFrame").show() ;
    }
    else if (video_version == "img"){
        $("#dome_L").show() ;
    }else{}
    if(!top.playing)	document.getElementById("div_fake").style.display = "";
    setAD_position_class(false);
    //OpenTV(top.tv_now);
    top.select_type = "betlist";
    show_bet_list();
    $("#main_bet").show() ;
    document.getElementById("btn_game").className = "live_tvListBTN";
}

function showGameList(){
    if(!ctl_tv_status) ctlTVEvent();

    check_Gtype_bar();
    //trace("nolive_sw=====>"+nolive_sw);
    //if(nolive_sw=="N") return;
    if(nolive_sw=="N"&&(!top.playing || (Gplaying == 0 && top.select_type == "gamelist"))) return;
    if(top.select_type=="gamelist"){
        showBetList();
        return;
    }
    top.select_type = "gamelist";
    //setAD_position(tmp_sw, GameData);
    /*
    document.getElementById("main").style.display = "";
    document.getElementById("even_list").style.display = "";
    document.getElementById("div_gtype").style.display = "";
    //document.getElementById("FlahLayer").style.display = "none";
    //document.getElementById("div_fake").style.display = "none";
    document.getElementById("div_info").style.display = "none";
    document.getElementById("FlahLayer").style.display = "none";
    */

    live_refresh_flage ='Y' ; // live.php 刷新标志
    document.getElementById("time_list").style.display = "";
    document.getElementById("div_gtype").style.display = "";
    //document.getElementById("btn_game").className = "live_tvListBTN_on";
    if(Gplaying == 0 && top.select_type=="gamelist")
    {
        document.getElementById("btn_game").className = "live_tvListBTN";
    }else if(Gplaying != 0 && top.select_type=="gamelist")
    {
        document.getElementById("btn_game").className = "live_tvListBTN_on";
    }else{}

    document.getElementById("even_none").style.display = (GameData.length==0)?"":"none";
    $("#showX").css('display','');
    //在線直播和直播日程表要做固定在頂部  2016-05-16 William

    if(!is_init) initfixhead();
}

function check_Gtype_bar(){
    var SelGtype=document.getElementById("select_gtype").getAttribute("value");
    if(nolive_sw=="N"&&(SelGtype=="All"||SelGtype==null) ){
        document.getElementById("gtype_bar").style.display = "none";
        //document.getElementById("gtype_bar_bak").style.display = "none";
    }else{
        if(!is_init){
            document.getElementById("gtype_bar").style.display = "";
            //document.getElementById("gtype_bar_bak").style.display = "";
        }
    }
}
function checkOpenLiveExist(){
    var ret = false;
    if(top.newWinObj["Live"]){
        if(!top.newWinObj["Live"].closed){
            ret = true;
        }
    }
    ////trace("checkOpenLiveExist======>"+ret);
    return ret;
}

function OpenTV_chgType(i,eventid){
    var TV_gtype = i.split("_");
    //console.log(top);
    top.betGtype = TV_gtype[0];
    //2017-09-28 Ricky 右邊TV跟左邊下注若選擇不同球類會破圖 , 因為top.betGtype衝突到 , 所以改成用top._Gtype
    top._Gtype = TV_gtype[0];
    top.select_type="betlist";
    nowPerformHash = null;
    var ret = OpenTV(i,eventid);
    if(ret){
        if(!isOpen){
            //console.log("is_init==> "+is_init);
            if(is_init){
                is_init = false;
                checkInitView();
                //第一次登入不秀賠率
                if(!isOpen){
                    setAD_position(tmp_sw, GameData);
                }

            }
        }
    }

}

// 大窗口打开视频事件
function OpenTV_new(i,eventid){
    $('#DemoImgLayer').hide();
    OpenTV_chgType(i,eventid);
    document.getElementById("time_list").style.display = "";
    $('#even_list').removeClass('live_evenH');
    /*
    if(i==null&&eventid==null) return;

    if(nowPerformHash==null){
            nowPerformHash = new Object();
            if(performData[i]==null) return;
            if(performData[i][6]!="Y") return;

            nowPerformHash = performData[i];
    }



    go_betpage();
    playTV(i,eventid, true);
    reloadioratio();
    */
}

function OpenTV(i,eventid){

    if(i==null&&eventid==null) return false;
    if(nowPerformHash==null){
        nowPerformHash = new Object();
        if(performData[i]==null) return false;
        if(performData[i][6]!="Y") return false;
        nowPerformHash = performData[i];
    }
    show_bet_list();
    //trace("i===>"+i+",eventid==>"+eventid);
    playTV(i,eventid, true);
    //document.getElementById("btn_game").className = "live_tvListBTN";
    //右邊TV視訊播放後會顯示異常  顯示betlist的時候再將視訊畫面打開
    if(!isOpen){
        if(video_version == "perform"){
            $("#dome_L").show() ;
        }
        else if (video_version == "unas"){
            $("#videoFrame").show();
        }
        else if (video_version == "img"){
            // $("#dome_L").show() ;
        }else{}
    }

    reloadioratio(i);
    return true;
}

function show_bet_list(){
    document.getElementById("time_list").style.display = "none";
    live_refresh_flage = 'N' ; // live.php 页面刷新标志
    top.bet_page = true;
}

function playTV(i,eventid, isClick){
    var ret = processTV(i,eventid);
    var tmp_i = ret["i"];
    if(isClick){
        if(ret["ret"]){
            //registVideo(performData[tmp_i][13],performData[tmp_i][1]);
            registVideo(nowPerformHash[13], nowPerformHash[1]);
            top.playing = true;
            if(isOpen) opener.top.playing=top.playing;
        }
    }
}
function processTV(i,eventid){
    //top.eventid = eventid || "";

    var retAry = new Object();
    retAry["i"] = "";
    retAry["ret"] = false;

    /*
    if(top.eventid != "" ){
        for (var j = 0; j < GameData.length; j++){
            ////trace(GameData[j][1]);
            if(GameData[j][1]==top.eventid){
                 if( j == 0 ) i = "0";
                 else		 i = j;
             }
        }
    }
    */

    if (i=="") return retAry;  //tv off
    if(nowPerformHash==null){
        nowPerformHash = new Object();
        if(performData[i]==null) return retAry;
        if(performData[i][6]!="Y") return retAry;
        //console.trace("tv_now----->"+i);
        nowPerformHash = performData[i];
    }
    //tv_now = i;
    //info("top.eventid====>"+i+","+top.eventid);

    top.eventid = nowPerformHash[1];
    top.tv_now = i;
    chgStatusTV(top.tv_now);
    retAry["i"] = top.tv_now;

    videoData = nowPerformHash[1]+","+nowPerformHash[3]+","+nowPerformHash[4]+","+nowPerformHash[9]+","+nowPerformHash[7]+","+nowPerformHash[8]+","+nowPerformHash[11]+","+nowPerformHash[12]+","+nowPerformHash[0];
    new_gtype = nowPerformHash[0];

    //Livegtype= new_gtype;
    // 2017-03-06 3042.新會員端-tv 當已在右邊tv觀看時點放大和上方的tv圖示到另開要是繼續剛觀看的場次
    if(!closeTV){
        clickgtype= new_gtype;
        Livegidm = nowPerformHash[10];
    }else{
        nowPerformHash = performData[i];
        closeTV = false;
    }

    SetClothesColor(nowPerformHash[7], nowPerformHash[8]);
    setTeamName(nowPerformHash[3], nowPerformHash[4]);
    chgFakePic(nowPerformHash[13]);
//	registVideo(performData[i][13],performData[i][1]);

    betpage_process();
    retAry["ret"] = true;

    return retAry;
}

function chgStatusTV(i){
    //info("chgStatusTV====>i:"+i);
    if(i=="") return;
    if(performData[i]==null) return;
    //console.table(performData);

    for(var keys in performData){
        //info("chgStatusTV====>keys:"+keys);
        var e = performData[keys][1];
        setClass(keys+"_"+e,false);
    }
    var eid = performData[i][1];
    setClass(i+"_"+eid,true);
}
function setClass(i, isOn){
    //info("setClass===>"+i+","+performData[i][6]+","+isOn);
    if(performData[i]==null) return;

    var before = "nomal";
    var after = "on";
    if(!isOn){
        before = "on";
        after = "nomal";
    }

    var txtObj = document.getElementById("live_txt_"+i);
    var tvObj = document.getElementById("live_tv_"+i);
    var gObj = document.getElementById("live_gtype_"+i);

    var txt_sty = util.getObjectClass(txtObj);
    var tv_sty = util.getObjectClass(tvObj);
    var g_sty = util.getObjectClass(gObj);

    util.setObjectClass(txtObj, txt_sty.replace(before,after));
    util.setObjectClass(tvObj, tv_sty.replace(before,after));
    util.setObjectClass(gObj, g_sty.replace(before,after));

}
function SetClothesColor(color_h, color_c) {
    if(clickgtype!='BS'){
        util.setObjectClass(document.getElementById(clickgtype+"_clothes_h"), getClothesClass(clickgtype, color_h));
        util.setObjectClass(document.getElementById(clickgtype+"_clothes_c"), getClothesClass(clickgtype, color_c));
    }
}


function setTeamName(team_h, team_c){
    //if(team_h) document.getElementById(clickgtype+"_team_h").innerHTML = team_h.replace("[Mid]","").replace("[中]","").replace(" ","");;
    //if(team_c) document.getElementById(clickgtype+"_team_c").innerHTML = team_c.replace("[Mid]","").replace("[中]","").replace(" ","");;
    // 2017-01-17 3005.右邊tv顯示的隊名字樣 會發生和盤面不一樣的清況
    if(team_h) document.getElementById(clickgtype+"_team_h").innerHTML = team_h.replace("[Mid]","").replace("[中]","");
    if(team_c) document.getElementById(clickgtype+"_team_c").innerHTML = team_c.replace("[Mid]","").replace("[中]","");
}

function getClothesClass(gtype, color){

    if(color==null) return "";

    var hash = new Object();
    hash["FT"] = "live_SC live_Icon_";
    hash["BK"] = "live_BK live_Icon_";
    hash["TN"] = "live_TN live_Icon_";
    hash["VB"] = "live_TN live_Icon_";
    hash["BS"] = "live_BK live_Icon_";
    hash["OP"] = "live_BK live_Icon_";
    hash["BM"] = "live_TN live_Icon_";
    hash["TT"] = "live_TN live_Icon_";
    return hash[gtype]+color;
}

function chgFakePic(type){
    var fake = document.getElementById("div_fake");
    if(fake!=null){
        var org_sty = util.getObjectClass(fake);
        var new_sty = org_sty.substr(0,org_sty.length-2)+getStyleTV(type);
        util.setObjectClass(fake, new_sty);
    }
}

function betpage_process(){
    eventlive ="";
    //reloadioratio();
    //trace("goto_betpage end ");
}

function getStyleTV(type){
    var hash = new Object();
    hash["img"] = "01";
    hash["perform"] = "02";
    hash["unas"] = "03";
    var ret = hash[type];
    return (ret)?ret:hash["img"];
}

function registVideo(videoVersion,videoCode){
    //  var base_url = util.getNowDomain()+"/app/member/live/";
    var base_url = util.getProtocal()+"//"+top.performUrl+"/app/member/live/";
    video_version = videoVersion;
    if(videoVersion=="perform"){
        //document.getElementById("reloadgame").contentWindow.location.href = base_url+"RegistLive.php?uid="+uid+"&langx="+langx+"&gameary="+videoCode+"&liveid="+mtvid;
        url_perform = "uid="+uid+"&langx="+langx+"&gameary="+videoCode+"&liveid="+mtvid;
        loadPerformPlayer(url_perform);
    }else if(videoVersion == "unas"){
        //document.getElementById("reloadgame").contentWindow.location.href = url+"/app/member/live/unasVideo.php?uid="+uid+"&langx="+langx+"&gameary="+videoCode;
        //url_perform = "uid="+uid+"&langx="+langx+"&gameary="+videoCode;
        loadUnasPlayer(videoCode);
    }else if(videoVersion == "img"){
        // document.getElementById("reloadgame").contentWindow.location.href = base_url+"imgVideo.php?uid="+uid+"&langx="+langx+"&gameary="+videoCode;
        loadImgPlayer(videoCode);
    }
}

function loadImgPlayer(par){
    $.get('/app/member/live/imgVideo.php',{ type:'img',code:par},function(data) {
        if(data) {
            GetVideoImg(data);
        }
    });
}

function loadPerformPlayer(par){

    url = util.getProtocal()+"//"+top.performUrl;
    url+="/app/member/live/getVideoFMS.php?"+par;

    iframe_onload(document.getElementById("DefLive"), loadPerform);
    iframe_src(document.getElementById("DefLive"), url);
}
function loadUnasPlayer(par){
    $.get('/app/member/live/imgVideo.php',{ type:'unas',code:par},function(data) {
        if(data) {
            var urlJson=data.split('|');
            GetVideoUnas(urlJson[1],urlJson[0]);
        }
    });
}

function iframe_onload(iframe, fun){
    if(iframe.attachEvent){
        iframe.attachEvent("onload", function(){
            if(fun) fun();
        });
    }else{
        iframe.onload=function(){
            if(fun) fun();
        };
    }
}

function iframe_src(obj, url){
    if(obj!=null&&obj.tagName!=null&&url!=null){
        //2017.0112 johnson 斷線時記錄url
        obj.loadsrc = url;
        obj.contentWindow.location = url;
    }
}

function loadPerform(){

    unasURL="";
    document.getElementById("videoFrame").innerHTML = "";
    document.getElementById("videoFrame").style.display = "none";
    $("#FlahLayer").hide();

    var tmp = videoData.split(",");
//    console.log(tmp) ;

    if(!isOpen){
        registFinishPerform();

    }else{
        //iframe_onload(document.getElementById("DefLive"), registFinishPerform);
        //document.getElementById("DefLive").src = vurl;
    }

    document.getElementById("DefLive").style.display = "";
    $("#FlahLayer").show();
    document.getElementById("div_fake").style.display = "none";
    document.getElementById("DemoImgLayer").style.display = "none";
    //=== 隊名
    SetClothesColor(tmp[4], tmp[5]);
    //=== 比分
    document.getElementById("FT_sc_h").innerHTML = tmp[6]; // 主队比分
    document.getElementById("FT_sc_c").innerHTML = tmp[7]; // 客队比分
    //document.getElementById("league").innerHTML = tmp[3]+"<BR>";//暂时屏蔽
    if(tmp[8] == "FT"){
        //document.getElementById("team").innerHTML = tmp[1]+"&nbsp;"+tmp[6]+"&nbsp; - &nbsp;"+tmp[7]+"&nbsp;"+tmp[2];
    }else{
        //document.getElementById("team").innerHTML = tmp[1]+"&nbsp;&nbsp;VS&nbsp;&nbsp;"+tmp[2];
    }
}

function reloadioratio(i){

    console.log(performData[i]);

    var par = ""
    par+="uid="+uid;
    par+="&langx="+top.langx;
    par+="&gtype="+clickgtype;
    par+="&gidm="+Livegidm;
    par+="&gdate=All";
    par+="&code="+performData[i][1];

    var url = util.getNowDomain()+"/app/member/live/game_ioratio_view.php?"+par;

    resetFrameHeight("Live_mem");
    iframe_src(document.getElementById("Live_mem"), url);

}

function resetFrameHeight(_id){
    try{
        var obj = document.getElementById(_id); // 赔率窗口
        // var parobj = parent.document.getElementById('live'); // 整个视频窗口
        // parobj.height = '650' ;
        // obj.height="423";

        var h = obj.contentWindow.document.body.scrollHeight;
    }catch(e){}
}

function checkInitView(){
    if(is_init){
        document.getElementById("div_title_nogame").style.display = "";
        document.getElementById("div_body").style.display = "";
        if(document.getElementById("DemoImgLayer").style.display=="none"){
            document.getElementById("FlahLayer").style.display = "";
        }
        document.getElementById("div_title").style.display = "none";
        document.getElementById("div_title_bak").style.display = "none";
        document.getElementById("even_none_nogame").style.display = "none";

        //2034. 新會員端-右邊tv-沒點過時間表上的賽事，當畫面停在時間表時，
        //按下內外層盤面的tv鈕時,右邊tv要播放賽事的畫面現在停在時間表上(BGM-289) time_list
        if(top.select_type!="betlist")document.getElementById("time_list").style.display = "";

        document.getElementById("btn_game").className = "live_tvListBTN_on";
        document.getElementById("div_gtype").style.display = "";
        document.getElementById("gtype_bar").style.display = "none";
        //document.getElementById("gtype_bar_bak").style.display = "none";

        return true;
    }else{
        // 2017-02-20 3028.新會員端-右邊tv-時間表的高度有修改過，但是內容資料和滾軸的長度都沒有依高度改變位置
        util.setObjectClass(document.getElementById("even_list"), "live_evenH");
        $("#div_title_nogame").hide() ;
        $("#div_title").show() ;
        return false;
    }

}

function setAD_position(canView, gData ){
    if(canView=="N"){
        if(top.select_type=="gamelist"){
            $("#main_bet").hide() ;
            setAD_position_class(true);
        }else{
            if(top.playing){
                $("#main_bet").show() ;
                setAD_position_class(false);
            }else{
                $("#main_bet").hide() ;
                setAD_position_class(false);
            }
        }

    }else{
        if(top.playing){
            $("#main_bet").show() ;
            setAD_position_class(false);
        }else{ //isFirst  第一次登入不秀賠率
            $("#main_bet").hide() ;
            setAD_position_class(true);
        }
    }
}

function setAD_position_class(t){
    //var _class = (t)?"live_adG live_NOTV_ad":"live_adG";
    //util.setObjectClass(document.getElementById("div_ad"), _class);

    //setClassAD("live_NOTV_ad", t);//没有广告位
}

function setClassAD(base, t){
    var cls = "";
    var adObj = document.getElementById("div_ad");

    var org_cls = util.getObjectClass(adObj);
    var b_cls = " "+base;

    cls = org_cls.replace(new RegExp(b_cls,"gi"),"");
    if(t) cls += b_cls;
    util.setObjectClass(adObj, cls);
}

function registFinishPerform(){

    if(isOpen) return;

    /*
    scaleAry = getPerformScale();
    try{

            document.getElementById("DefLive").contentWindow.resize(scaleAry);
    }catch(e){}
    return;
    */

    //w = sizeHash["w"+resize_w]||"310px";
    //h = sizeHash["h"+resize_w]||"289px";

    //setVideoSize(w,h);

    var hash = new Object();
    hash["w320"] = 310;
    hash["h320"] = 289;
    hash["w480"] = 480;
    hash["h480"] = 408;

    w = hash["w"+resize_w]||"310";
    h = hash["h"+resize_w]||"289";

    //trace("registFinishPerform====>"+w+","+h);

    try{

        document.getElementById("FlahLayer").style.width = w+"px";
        document.getElementById("FlahLayer").style.height = h+"px";
        document.getElementById("DefLive").width = w+"px";
        document.getElementById("DefLive").height = h+"px";

        //document.getElementById("DefLive").contentWindow.setResize(w,h);

    }catch(e){

    }

}


//util
var util = new Object();
util.classname = "[util.js]";
try{ util.HttpRequest = HttpRequest; }catch(e){}
try{ util.ParseHTML = ParseHTML; }catch(e){}
util.fail_count = new Object();
util.fail_limit = 10;
util.timeout_sec = 3000;
util.reload_sw = true;
var load_css = false;
var load_js = false;


//go to page
util.goToPage=function(filename, paramObj){
    util.trace(util.classname+"goToPage: "+filename);

    util.fail_count[filename] = 0;

    paramObj.targetWindow = paramObj.targetWindow || document.getElementsByTagName("body")[0];
    paramObj.targetHead = paramObj.targetHead || document.getElementsByTagName("head")[0];
    paramObj.loadComplete = paramObj.loadComplete || function(){};
    paramObj.param = paramObj.param||"";

    if(paramObj.filename.indexOf(".php")!=-1){
        paramObj.filepath = filename;
        paramObj.method = "POST";
    }else if(paramObj.filename.indexOf(".html")!=-1){
        paramObj.filepath = "/tpl/member/"+top.langx+"/"+filename+".html";
        paramObj.method = "GET";
    }else{

    }

    var getHttp = new util.HttpRequest();
    getHttp.addEventListener("LoadComplete", function(html){
        util.loadHtmlFinish(html, paramObj);
    });

    getHttp.addEventListener("onError", function(html){
        if(util.reload_sw){
            util.fail_count[filename]++;

            if(util.fail_count[filename]<util.fail_limit){
                window.setTimeout(function(){
                    getHttp.loadURL(paramObj.filepath, paramObj.method, paramObj.param);
                }, util.timeout_sec);
            }else{

            }
        }
    });

    getHttp.loadURL(paramObj.filepath, paramObj.method, paramObj.param);

}


//load html finish
util.loadHtmlFinish=function(html, paramObj){
    //util.trace(util.classname+"loadHtmlFinish");

    var tempHtml = new util.ParseHTML(html);

    //HTML
    dbody = tempHtml.getTag("div")[0];
    paramObj.targetWindow.innerHTML = "";
    if(dbody)paramObj.targetWindow.appendChild(dbody);




    //===== load JS =====
    var js_count = 0;
    jsAry = tempHtml.getTag("script");
    if(jsAry==0){

        //paramObj.loadComplete();


        //===== load CSS =====
        var css_count = 0;
        cssAry = tempHtml.getTag("link");
        if(cssAry.length==0){

            paramObj.loadComplete();

        }else{
            for(i=0;i<cssAry.length;i++) {
                var cssObj = cssAry[i];
                var _src = cssObj.href;

                //util.trace(_src);

                util.fail_count[_src] = 0;


                util.loadCSS(_src, paramObj, function(){
                    css_count++;

                    if(css_count>=cssAry.length){
                        //util.trace("[load css finish]");
                        //console.log("[load css finish]");
                        paramObj.loadComplete();

                    }

                });

            }
        }
        //===== load CSS =====

    }else{
        for(i=0;i<jsAry.length;i++) {
            var jsObj = jsAry[i];
            var _src = jsObj.src;

            util.fail_count[_src] = 0;
            //util.trace(_src);

            util.loadScript(_src, paramObj, function(){


                js_count++;
                //util.trace("load js: "+js_count);

                if(js_count>=jsAry.length){
                    //console.log("[load js finish]");
                    //paramObj.loadComplete();



                    //===== load CSS =====
                    var css_count = 0;
                    cssAry = tempHtml.getTag("link");
                    if(cssAry.length==0){

                        paramObj.loadComplete();

                    }else{
                        for(i=0;i<cssAry.length;i++) {
                            var cssObj = cssAry[i];
                            var _src = cssObj.href;

                            //util.trace(_src);

                            util.fail_count[_src] = 0;


                            util.loadCSS(_src, paramObj, function(){
                                css_count++;

                                if(css_count>=cssAry.length){
                                    //util.trace("[load css finish]");
                                    //console.log("[load css finish]");
                                    paramObj.loadComplete();

                                }

                            });

                        }
                    }
                    //===== load CSS =====

                }

            });
        }
    }
    //===== load JS =====








}

/*
function load_complete(_type, loadFun){

		//load_count++;

		switch(_type){
			case "css":
				load_css = true;
				break;
			case "js":
				load_js = true;
				break;
			default:
				break;
		}

		console.log("[load_complete]"+_type+",css="+load_css+",js="+load_js);

		if(load_css && load_js){
		//if(load_count>=2){
				console.log("[load_complete]");
				loadFun();
				load_css = false;
				load_js = false;
				//load_count = 0;
		}
}
*/

//load css
util.loadCSS=function(_src, paramObj, loadFun){
    //util.trace(util.classname+"loadCSS: "+_src);
    var css = document.createElement("link");
    css.setAttribute("rel", "stylesheet");
    css.setAttribute("type", "text/css");
    css.setAttribute("href", _src);

    css.onload=function(){
        //util.trace("load css finish: "+_src);
        //console.log("load css finish: "+_src);
        if(loadFun) loadFun();
    };

    //IE is not working
    css.onerror=function(){
        //util.trace("load css fail: "+_src);

        if(util.reload_sw){
            util.fail_count[_src]++;

            if(util.fail_count[_src]<util.fail_limit){

                window.setTimeout(function(){
                    paramObj.targetHead.removeChild(css);
                    util.loadCSS(_src, paramObj, loadFun);
                },util.timeout_sec);

            }else{
                var tmp_src = _src.split("/");
            }
        }
    };

    paramObj.targetHead.appendChild(css);


}

//load script
util.loadScript=function(_src, paramObj, loadFun){
    //util.trace(util.classname+"loadScript: "+_src);

    var getHttp = new util.HttpRequest();
    getHttp.addEventListener("LoadComplete",function(html){

        var script = document.createElement("script");
        script.setAttribute("type","text/javascript");
        script.text = html;
        paramObj.targetHead.appendChild(script);

        if(loadFun) loadFun();

    });

    getHttp.addEventListener("onError", function(html){

        if(util.reload_sw){
            util.fail_count[_src]++;

            if(util.fail_count[_src]<util.fail_limit){
                window.setTimeout(function(){getHttp.loadURL(_src,"GET","");}, util.timeout_sec);
            }else{
                var tmp_src = _src.split("/");
            }
        }
    });

    getHttp.loadURL(_src,"GET","");

}


//print stack trace
util.printStackTrace=function(code){
    /*
        var _this = arguments.callee.caller;
        var msg = "Stack trace:";
        var base = "\n";
        if(code) msg=code+base+msg;
        while(_this.caller){
                var param = util.getArguments(_this.caller.arguments);
                msg+=base+"function "+_this.caller.name+"("+param+")";
                //msg+=base+"function "+_this.caller.name;
                //msg+=base+"function "+_this.caller;
                _this = _this.caller;
        }

        console.log(msg);
    */
    console.trace();
}

//get arguments
util.getArguments=function(obj){
    var ret = new Array();
    for(var _key in obj){
        var content = obj[_key];
        if(content!=null){
            if(content.length > 10) content=content.substr(0,10)+"...";
        }
        ret.push(typeof(obj[_key])+" ["+content+"]");


        //ret.push(typeof(obj[_key]));
    }
    return ret.join(",");
}

//print Hash
util.printHash=function(obj, _title){

    var count = 0;
    var str = "";

    if(_title!=null) str+="["+_title+"]\n";

    for(key in obj){
        str+=key+"======>"+obj[key]+"\n";
        count++;
    }
    str+="length======>"+count+"\n";
    util.trace(util.classname+str);
}


//http or https
util.getProtocal=function(){
    return document.location.protocol;
}


util.getWebDomain=function(){
    return document.domain;
}


util.getNowDomain=function(){
    return util.getProtocal()+"//"+util.getWebDomain();
}


//system msg
// util.systemMsg=function(msg, isStack){
//     console.warn(msg);
//     if(isStack!=false) util.printStackTrace();
// }

//trace
util.trace=function(msg, isStack){
    if(top.isTestSite){
        console.log(msg);
        //isStack = true;
        if(isStack) util.printStackTrace();
    }
}

util.showTxt=function(txt){
    if(txt+""=="undefined"||txt+""=="null"||txt+""=="NaN")  return "";
    return txt;
}

util.isIPad=function(){
    var agent = navigator.userAgent;
    if(agent.indexOf("iPad")!=-1){
        return true;
    }
    return false;
}

//含IE8以下
util.isIE8=function(){
    var ret = false;
    var agent = navigator.userAgent;
    var ie = "MSIE";
    var pos = agent.indexOf(ie);
    //Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; .NET4.0C)

    if(pos!=-1){
        var tmp_agent = agent.substring(pos+ie.length,agent.length);
        var str = tmp_agent.indexOf(".");
        var version = tmp_agent.substring(0, str);
        if(version*1<=8) ret = true;
    }
    return ret;
}
util.checkBrowser=function (){
    var ret = false;
    var agent = navigator.userAgent;

    if(agent.indexOf("rv:11")!=-1||agent.indexOf("Firefox")!=-1||agent.indexOf("Edge")!=-1){
        //if(agent.indexOf("Firefox")!=-1){
        ret=true;
    }
    return ret;
}
util.isIE11=function(){//ie11 edge
    var ret = true;
    var agent = navigator.userAgent;
    var ie = "MSIE";
    var pos = agent.indexOf(ie);
    var brows = new Array("Chrome","Safari","Firefox");
    if(agent.indexOf("Edge")== -1){
        for(var i=0;i<brows.length;i++){
            if(agent.indexOf(brows[i]) != -1){
                ret = false;
                break;
            }
        }
    }
    return ret;
}

//set obj class
util.setObjectClass=function(targetObj,classStr){
    if(targetObj.className!=undefined){
        targetObj.className = classStr;
    }else{
        try{
            targetObj.setAttribute("class", classStr);
        }catch(e){}
    }
}


//get obj class
util.getObjectClass=function(targetObj){
    if(targetObj.className!=undefined){
        return targetObj.className;
    }else{
        return targetObj.getAttribute("class");
    }
}

util.reachBottom=function(DOC){
    var scrollTop = 0;
    var clientHeight = 0;
    var scrollHeight = 0;
    if (DOC.documentElement && DOC.documentElement.scrollTop) {
        scrollTop = DOC.documentElement.scrollTop;
    } else if (DOC.body) {
        scrollTop = DOC.body.scrollTop;
    }
    if (DOC.body.clientHeight && DOC.documentElement.clientHeight) {
        clientHeight = (DOC.body.clientHeight < DOC.documentElement.clientHeight) ? DOC.body.clientHeight: DOC.documentElement.clientHeight;
    } else {
        clientHeight = (DOC.body.clientHeight > DOC.documentElement.clientHeight) ? DOC.body.clientHeight: DOC.documentElement.clientHeight;
    }
    scrollHeight = Math.max(DOC.body.scrollHeight, DOC.documentElement.scrollHeight);
    if (scrollTop + clientHeight == scrollHeight) {
        return true;
    } else {
        return false;
    }
}

util.getObjAbsolute_new=function(obj,stop_name){
    var abs = new Object();

    abs["left"] = obj.offsetLeft;
    abs["top"] = obj.offsetTop;

    while(obj = obj.offsetParent){
        ////console.log(obj);
        ////console.log(obj.offsetLeft+" >> "+obj.offsetTop);
        if(util.getStyle(obj,"position") == "relative"){
            ////console.log(obj.id+"|"+obj.offsetParent.id+"|"+_self.getStyle(obj,"top")+"|"+_self.getStyle(obj,"margin-top")+"|"+obj.offsetTop);
            if((obj.id!="" && obj.offsetParent.id!="") && util.getStyle(obj,"top")!="auto" && util.getStyle(obj,"margin-top")!="auto" && util.getStyle(obj,"margin-top")!="0px"){
                abs["top"] += -obj.offsetTop;
                continue;
            }
        }

        if(stop_name!=undefined && obj.id==stop_name){
            break;
        }else if(util.getStyle(obj,"position") == "absolute"){
            break;
        }

        abs["left"] += obj.offsetLeft;
        abs["top"] += obj.offsetTop;
    }

    return abs;
}


util.getObjAbsolute=function(obj){
    var _abs = new Object();

    _abs["left"] = obj.offsetLeft;
    _abs["top"] = obj.offsetTop;

    while (obj = obj.offsetParent) {
        _abs["left"] += obj.offsetLeft;
        _abs["top"] += obj.offsetTop;
    }

    return _abs;
}


util.getStyle=function(oElm,strCssRule){
    var strValue = "";
    if(document.defaultView && document.defaultView.getComputedStyle){
        strValue = document.defaultView.getComputedStyle(oElm,"").getPropertyValue(strCssRule);
    }else if(oElm.currentStyle){
        strCssRule = strCssRule.replace(/\-(\w)/g, function (strMatch, p1){
            return p1.toUpperCase();
        });
        strValue = oElm.currentStyle[strCssRule];
    }else{
        return "error";
    }
    return strValue;
}


util.clearObject=function(obj){
    for(var key in obj){
        delete obj[key];
    }
    return obj;
}

util.clearArray=function(ary){
    ary.length = 0;
    return ary;
}

// 放大直播视频
/*function showOpenLive() {
    parent.document.getElementById('live').height='0' ;
    var url = "live_max.php?langx="+top.langx+"&uid="+top.uid+"&liveid="+top.liveid ;
    top.tvwin = window.open(url,"win","width=805,height=526,top=0,left=0,status=no,toolbar=no,scrollbars=yes,resizable=no,personalbar=no");
}*/

// 检测是视频窗口是否被关闭
function checkTvWinClose() {
    var loop = setInterval(function() {
        if(top.tvwin) { // 是否有打开tv 新窗口
            if (top.tvwin.closed) {
                // clearInterval(loop);
                parent.document.getElementById('live').height='430' ;
            }
        }
    }, 1000);


}

function GetVideoImg(vurl){
    //trace("GetVideoImg");
    clearVideo();
    if (vurl != "") {
        var tmp = videoData.split(",");
        //trace(vurl);

        iframe_onload(document.getElementById("DefLive"), registFinishImg);
        document.getElementById("DefLive").src = vurl;
        document.getElementById("DefLive").style.display = "";
        //document.getElementById("DefLive").style.height = "360px";
        //document.getElementById("DefLive").style.width = "640px";
        document.getElementById("FlahLayer").style.display = "";
        document.getElementById("div_fake").style.display = "none";
        document.getElementById("DemoImgLayer").style.display = "none";
        //=== 隊名
        SetClothesColor(tmp[4], tmp[5]);
        if(document.getElementById("league")){
            document.getElementById("league").innerHTML = tmp[3]+"<BR>";
        }

        if(tmp[8] == "FT"){
            document.getElementById("team").innerHTML = tmp[1]+"&nbsp;"+tmp[6]+"&nbsp; - &nbsp;"+tmp[7]+"&nbsp;"+tmp[2];

        }else{
            document.getElementById("team").innerHTML = tmp[1]+"&nbsp;&nbsp;VS&nbsp;&nbsp;"+tmp[2];
        }

        //var param = {gtype:Livegtype,team_h:tmp[1],team_c:tmp[2],score_h:tmp[6],score_c:tmp[7]};
        //setGameInfo(param);

        //ChkGameDataTimerFun();
        document.getElementById("DemoImgLayer").style.display = "none";
    }else{
        document.getElementById("DemoImgLayer").style.display = "";
    }
}

//unas
function GetVideoUnas(url,officeUrl){
    //alert("GetVideoUnas clearVideo");
    clearVideo();
    unasURL=url;
    if (url != "") {
        var tmp = videoData.split(",");
        //vurl="./unasVideo.php?uid="+uid+"&langx="+langx+"&gameary="+eventID;
        objstr='<OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" id = "myFlashPlayer" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" width="480" height="408">';
        //objstr='<OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" id = "myFlashPlayer" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" width="100%" height="100%">';
        objstr+='<param name = movie      value="unasplayer.swf?src='+unasURL+'">';
        objstr+='<param name = quality    value="high">';
        objstr+='<param name = bgcolor    value="black">';
        objstr+='<param name = loop       value="false">';
        objstr+='<param name = wmode      value="transparent">';
        objstr+='<param name = FlashVars  value="secretdebug=false">';
        objstr+='<EMBED	';
        objstr+='src         = '+officeUrl+'/app/member/live/unasplayer.swf?src='+unasURL;
        objstr+='id          = "myFlashPlayer_em"';
        //objstr+='width       = "480"';
        //objstr+='height      = "408"';
        // 2017-02-21 第二張圖會一下就又變正常
        if( resize_w == "320" ){
            objstr+='width       = "310"';
            objstr+='height      = "289"';
        }else{
            objstr+='width       = "480"';
            objstr+='height      = "408"';
        }
        objstr+='allowscale  = "true"';
        //objstr+='width       = "100%"';
        //objstr+='height      = "100%"';
        objstr+='quality     = "high" ';
        objstr+='bgcolor     = "black" ';
        objstr+='loop        = "false"';
        objstr+='wmode      = "transparent"';
        objstr+='FlashVars   = "secretdebug=false"';
        objstr+='type        = "application/x-shockwave-flash"';
        objstr+='pluginspage = "http://www.macromedia.com/go/getflashplayer">';
        objstr+='</EMBED>';
        objstr+='</OBJECT>';

        //var flashvars = {};
        //flashvars.src=unasURL;
        //datas="+code+"&firstOpen="+top.firstOpen

        // var params = {};
        //params.bgcolor="#000000";
        //var attributes = {};
        //swfobject.embedSWF("unasplayer.swf", "videoFrame", "480", "408", "9,0,124,0", false, flashvars, params, attributes);




        //iframe_onload(document.getElementById("DefLive"), registFinishUnas);
        //document.getElementById("DefLive").contentWindow.document.body.innerHTML = objstr;
        //document.getElementById("DefLive").style.display = "";

        document.getElementById("videoFrame").innerHTML = objstr;
        document.getElementById("videoFrame").style.display = "";
        $("#FlahLayer").show() ;
        document.getElementById("div_fake").style.display = "none";
        document.getElementById("DemoImgLayer").style.display = "none";

        //setTimeout(registFinishUnas, 1000);
        // 2017-02-21 第二張圖會一下就又變正常
        registFinishUnas();

        //=== 隊名
        SetClothesColor(tmp[4], tmp[5]);
        if(document.getElementById("league")){
            document.getElementById("league").innerHTML = tmp[3]+"<BR>";
        }

        try {
            if(tmp[8] == "FT"){
                document.getElementById("team").innerHTML = tmp[1]+"&nbsp;"+tmp[6]+"&nbsp; - &nbsp;"+tmp[7]+"&nbsp;"+tmp[2];
            }else{
                document.getElementById("team").innerHTML = tmp[1]+"&nbsp;&nbsp;VS&nbsp;&nbsp;"+tmp[2];
            }
        }catch (e) {

        }

        //var param = {gtype:Livegtype,team_h:tmp[1],team_c:tmp[2],score_h:tmp[6],score_c:tmp[7]};
        //setGameInfo(param);
        //ChkGameDataTimerFun();
        document.getElementById("DemoImgLayer").style.display = "none";
    }else{
        document.getElementById("DemoImgLayer").style.display = "";
    }

}

function clearVideo(){
    //clearInterval(ChkGameDataTimer);
    //gtype="";
    //gidm="";
    unasURL="";
    document.getElementById("videoFrame").innerHTML = "";
    document.getElementById("videoFrame").style.display = "none";
    //document.getElementById("FlahLayer").style.display = "none";
    //document.getElementById("DemoImgLayer").style.display = "";

    iframe_onload(document.getElementById("DefLive"), null);
    document.getElementById("DefLive").style.display = "none";
    document.getElementById("DefLive").src = "about:blank";
}


//img
function registFinishImg(){

    if(isOpen) return;

    //w = sizeHash["w"+resize_w]||"310px";
    //h = sizeHash["h"+resize_w]||"289px";


    var hash = new Object();
    hash["w320"] = 310;
    hash["h320"] = 175;
    hash["w480"] = 480;
    hash["h480"] = 270;

    w = hash["w"+resize_w]||"310";
    h = hash["h"+resize_w]||"175";


    //trace("registFinishImg====>"+w+","+h);


    try{

        document.getElementById("FlahLayer").style.width = w+"px";
        document.getElementById("FlahLayer").style.height = h+"px";
        document.getElementById("DefLive").width = w+"px";
        document.getElementById("DefLive").height = h+"px";
    }catch(e){

    }


    //setVideoSize(w,h);
}

//unas
function registFinishUnas(){
    if(isOpen) return;
    //w = sizeHash["w"+resize_w]||"310px";
    //h = sizeHash["h"+resize_w]||"289px";
    var hash = new Object();
    hash["w320"] = 310;
    hash["h320"] = 289;
    hash["w480"] = 480;
    hash["h480"] = 408;
    w = hash["w"+resize_w]||"310";
    h = hash["h"+resize_w]||"289";

    //trace("registFinishUnas====>"+w+","+h);
    try{
        document.getElementById("FlahLayer").style.height = h+"px";
        document.getElementById("FlahLayer").style.width = w+"px";
        document.getElementById("myFlashPlayer_em").height = h;
        document.getElementById("myFlashPlayer_em").width = w;
    }catch(e){

    }
}

function onloads(){
    document.getElementById("live_refresh").innerHTML = ReloadTime+"&nbsp;";
    ResetTimer();
    document.getElementById("live_refresh").onclick=function (){
        document.getElementById("live_refresh").innerHTML = ReloadTime+"&nbsp;";
    }

    //if(top.lastidName != "")
    if( typeof( top.lastidName ) != "undefined" && top.lastidName != "")
    {
        var temp = 'Bright(top.lastidName)';
        setTimeout(temp, 200);
    }

    //initfix("right_div","main_bet_head");
    //initfix();
    if(parent.isOpen){
        mainbetObj = document.getElementById("bet_list_main");
        mainbetObj.focus();

        document.getElementById("right_div").className = "live_scrollBar";
        mainbetObj.onkeydown=parent.TabCheck;
        mainbetObj.onclick=function() {
            parent.go_livepage();
        }
    }else{
        document.getElementById("live_refresh").style.top=0;
    }
}
// 倒计时结束后刷新当前页面
function reloadLive(){
    parent.live.location.reload();
    /*
    TimerID = 0;
    iframe_src(document.getElementById("reloadPHP"), o_path);
    if( typeof( top.lastidName ) != "undefined" && top.lastidName != "")
    {
        var temp = 'Bright(top.lastidName)';
        setTimeout(temp, 100);
    }
    if(parent.isOpen){
        document.getElementById("main_bet_head").style.display="";
        document.getElementById("main_bet_head_bak").style.display="";
    }*/
}
function ResetTimer() {
    AutoRenewID = setInterval("RenewTimerStr()",1000);
}
function RenewTimerStr() {
    if(live_refresh_flage=='Y'){
        try{
            var timer_str="timer_"+gtype;
        }catch(E){}
        if ((ReloadTime - TimerID) <= 1) {
            TimerID = 0;
            reloadLive();

        } else {
            TimerID++;
            var tmp = (ReloadTime - TimerID);
            if (tmp < 10) { tmp = "&nbsp;&nbsp;"+tmp; }
            try{
                document.getElementById("live_refresh").innerHTML = tmp+"&nbsp;";
            }catch(E){}

        }
    }


}