var AutoRenewID,ChkUserTimerID,ChkUserTime=10,ReloadTime=60,TimerID=0,gamedate="",T_color_h="",T_color_c="",Livegtype="",Livegidm="",gameAry=new Array("FT","BK","TN","VB","BS","OP"),pages="TVbut";function onload(){onloadGame(),reloadioratio()}function reloadioratio(){Live_mem.self.location="./game_ioratio_view.php?uid="+uid+"&langx="+parent.top.langx+"&gtype="+Livegtype+"&gidm="+Livegidm+"&gdate="+document.getElementById("gdate").value}function onloadGame(){var tmp_opt="";tmp_opt="<option value='All' selected>"+top.str_game_list+"</option>\n";for(var i=0;i<gameAry.length;i++)tmp_opt+="<option value='"+gameAry[i]+"'>"+eval("top.str_"+gameAry[i])+"</option>\n";for(tmp_opt='<select id="gameOpt" name="gameOpt" onChange="chggype()" class="select">\n'+tmp_opt+"</select>",document.getElementById("game_type").innerHTML=tmp_opt,tmp_opt="",i=0;i<GameDate.length;i++)tmp_opt+="<option class='se_date' value='"+GameDate[i]+"'>"+GameDate[i]+"</option>\n";tmp_opt='<select class=\'se_date\' id="gdate" name="gdate" onChange="chggdate()">\n'+tmp_opt+"</select>",document.getElementById("date_list").innerHTML=tmp_opt,document.getElementById("gameOpt").value="All",Livegtype="All",reloadGame(),StartChkTimer(),""!=videoData&&(registLive.self.location="./RegistLive.php?uid="+uid+"&langx="+langx+"&gameary="+videoData+"&liveid="+mtvid)}function chggype(){var e=document.getElementById("gameOpt").value;Livegtype=e,reloadGame(),reloadioratio()}function chggdate(){check_gamelist(),reloadGame(),reloadioratio()}function reloadGame(){clearInterval(AutoRenewID),TimerID=0,reloadgame.self.location="./game_list.php?uid="+uid+"&langx="+parent.top.langx+"&gtype="+Livegtype+"&gdate="+document.getElementById("gdate").value}function ResetTimer(){document.getElementById("timer_str").innerHTML=ReloadTime+"&nbsp;",AutoRenewID=setInterval("RenewTimerStr()",1e3)}function RenewTimerStr(){if(document.getElementById("timer_str").innerHTML="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",ReloadTime-TimerID<=1)TimerID=0,reloadGame();else{var e=ReloadTime-++TimerID;e<10&&(e="&nbsp;&nbsp;"+e),document.getElementById("timer_str").innerHTML=e+"&nbsp;"}}function independent(){"none"==document.getElementById("top_div").style.display?(document.getElementById("top_div").style.display="",document.getElementById("main").style.display="",document.getElementById("main_bet").style.display="",document.getElementById("alone_btn").alt=top.str_alone,document.all?window.resizeTo(791,640):(window.outerHeight=640,window.outerWidth=791)):(document.all?window.resizeTo(510,570):(window.outerHeight=570,window.outerWidth=510),document.getElementById("top_div").style.display="none",document.getElementById("alone_btn").alt=top.str_back)}function StartChkTimer(){clearInterval(ChkUserTimerID),ChkUserTimerID=setInterval("ChkUid('"+mtvid+"','"+eventid+"')",60*ChkUserTime*1e3)}function ChkUid(e,t){try{reloadPHP.self.location="./chk_registid.php?uid="+uid+"&langx="+parent.top.langx+"&regist_id="+e+"&liveid="+window.opener.top.liveid+"&gid="+t}catch(e){self.location="http://"+document.domain}}function send_result(e){var t=e.split(",");t.length<=1&&(t[0]=e),"false"==t[0]&&self.location.reload(),1<t.length&&SetClothesColor(t[1],t[2])}function GoToQAPage(){window.open("/tpl/member/"+langx+"/QA.html","LiveQA","width=780,height=600,top=0,left=0,status=no,toolbar=no,scrollbars=yes,resizable=yes,personalbar=no")}function ShowVideo(){var e="liveTV_"+langx.substring(3)+".swf",t='<object id="liveTV" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"width="480" height="410" codebase=\'http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab#version=9,0,124,0\'><param name="movie" value="'+e+'" /><param name="quality" value="high" /><param name="bgcolor" value="#1C0D00" /><param name="allowScriptAccess" value="sameDomain" /><embed name="liveTV" id="liveTV" src="'+e+'" quality="high" bgcolor="#1C0D00"width="480" height="410" align="middle"play="true"loop="false"quality="high"allowScriptAccess="sameDomain"type="application/x-shockwave-flash"pluginspage=\'http://www.adobe.com/go/getflashplayer\'></embed></object>';videoFrame.innerHTML=t,videoFrame.style.display="",document.getElementById("FlahLayer").style.display="",document.getElementById("video_msg").style.display="",document.getElementById("DemoImgLayer").style.display="none",document.getElementById("demo_msg").style.display="",document.getElementById("demo_msg").innerHTML="<font class='mag_info'>"+top.str_demo+"</font>"}function appInit(){liveTV.FLashFunction(langx)}function unload_swf(){var e=document.getElementById("liveTV");try{e.unloadSWF()}catch(e){}for(var t in e)try{e[t]=null}catch(e){}}function unLoad(){clearInterval(AutoRenewID),clearInterval(ChkUserTimerID)}function reload_game(){check_gamelist();for(var e=document.getElementById("tb_layer").innerHTML,t="",a=0;a<GameData.length;a++)t+=showlayer(document.getElementById("tr_layer").innerHTML,a)+"\n";e=(e=e.replace("*GAMEDATE*",gamedate)).replace("*GAMELIST*",t),showlayers.innerHTML=e,parent.ResetTimer(),"Y"==eventlive&&OpenTVbet(eventid)}function showlayer(layers,i){return"Y"==GameData[i][6]?(layers=i%2==0?layers.replace("*list_color*",'class="even_detail_1"'):layers.replace("*list_color*",'class="even_detail_2"'),layers=layers.replace("*ID*",i),layers=layers.replace("*STYLE*","style='cursor:hand'")):(layers=i%2==0?layers.replace("*list_color*",'class="even_detail_1"'):layers.replace("*list_color*",'class="even_detail_2"'),layers=layers.replace("*ID*",""),layers=layers.replace("*STYLE*",'style="display:none"')),layers=layers.replace("*GTYPE*",eval("top.str_"+GameData[i][0])),layers=layers.replace("*TIME*",GameData[i][2]),layers=layers.replace("*TEAMH*",GameData[i][3]),layers=layers.replace("*TEAMC*",GameData[i][4]),layers=layers.replace("*LEAGUE*",GameData[i][9]),layers}function OpenTV(e){if(document.getElementById("DemoLink").style.display="none",""==e)return!1;eventid=GameData[e][1],StartChkTimer(),videoData=GameData[e][1]+","+GameData[e][3]+","+GameData[e][4]+","+GameData[e][9]+","+GameData[e][7]+","+GameData[e][8],registLive.self.location="./RegistLive.php?uid="+uid+"&langx="+langx+"&gameary="+GameData[e][1]+"&liveid="+mtvid,Livegtype=GameData[e][0],Livegidm=GameData[e][10],reloadioratio(),go_betpage(),document.getElementById("gameOpt").value=Livegtype,reloadGame()}function OpenTVbet(e){if(document.getElementById("DemoLink").style.display="none",""==e)return!1;StartChkTimer();for(var t=0;t<GameData.length;t++)GameData[t][1]==e&&(videoData=GameData[t][1]+","+GameData[t][3]+","+GameData[t][4]+","+GameData[t][9]+","+GameData[t][7]+","+GameData[t][8],Livegtype=GameData[t][0],Livegidm=GameData[t][10]);registLive.self.location="./RegistLive.php?uid="+uid+"&langx="+langx+"&gameary="+e+"&liveid="+mtvid,reloadioratio(),go_betpage(),document.getElementById("gameOpt").value=Livegtype,reloadGame()}function GetVideo(e){if(""!=e){var t=videoData.split(",");document.getElementById("DefLive").src=e,document.getElementById("DefLive").style.display="",document.getElementById("video_msg").style.display="",document.getElementById("FlahLayer").style.display="",document.getElementById("DemoImgLayer").style.display="none",SetClothesColor(t[4],t[5]),document.getElementById("league").innerHTML=t[3]+"<BR>",document.getElementById("team").innerHTML=t[1]+"&nbsp;&nbsp;VS&nbsp;&nbsp;"+t[2],document.getElementById("video_msg").style.display=""}}function SetClothesColor(e,t){""==e&&(document.getElementById("team_h").style.display="none"),""==t&&(document.getElementById("team_c").style.display="none"),T_color_h!=e&&""!=e&&(T_color_h=e,document.getElementById("team_h").src="/images/member/T_"+T_color_h+".gif",document.getElementById("team_h").style.display=""),T_color_c!=t&&""!=t&&(T_color_c=t,document.getElementById("team_c").src="/images/member/T_"+T_color_c+".gif",document.getElementById("team_c").style.display="")}function chg_page(e){chg_page_height()}function chg_page_images(e){"TVbut"==e?(document.getElementById("table_Live_order").style.display="none",document.getElementById("right_div").style.display="",document.getElementById("BEbut").src="/images/member/"+langx+"/live_BEbut3.gif",document.getElementById("TVbut").src="/images/member/"+langx+"/live_TVbut.gif"):"BEbut"==e?(document.getElementById("table_Live_order").style.display="",document.getElementById("right_div").style.display="none",document.getElementById("BEbut").src="/images/member/"+langx+"/live_BEbut.gif",document.getElementById("TVbut").src="/images/member/"+langx+"/live_TVbut3.gif"):(document.getElementById("table_Live_order").style.display="none",document.getElementById("right_div").style.display="",document.getElementById("BEbut").src="/images/member/"+langx+"/live_BEbut3.gif",document.getElementById("TVbut").src="/images/member/"+langx+"/live_TVbut.gif")}function chg_page_height(){live_game_heigth()}function mouseEnter_pointer(e){}function mouseOut_pointer(e){}function live_order_height(e){document.all("bet_order_frame").height=1*e+5}function live_game_heigth(){document.getElementById("Live_mem").height="478px"}function show_bet_ps(){document.getElementById("main_bet").style.display=""}function go_betpage(){eventlive="",document.getElementById("main_bet").style.display="",document.getElementById("main").style.display="none",chg_page_height();try{close_bet()}catch(e){}}function go_livepage(){eventlive="",document.getElementById("main").style.display="",document.getElementById("main_bet").style.display="none"}function close_bet(){bet_order_frame.location.replace(""),document.getElementById("bet_order_frame").height=0}function close_bet_finish(){bet_order_frame.location.replace(""),document.getElementById("bet_order_frame").height=0,document.getElementById("bet_ps").style.display="none"}function onloadSet(e,t,a){document.getElementById(a).width=240,document.getElementById(a).height=t}function check_gamelist(){0==GameData.length?(document.getElementById("even_none").style.display="",document.getElementById("even_list").style.display="none",document.getElementById("bet_none").style.display="",document.getElementById("bet_box").style.display="none"):(document.getElementById("even_list").style.display="",document.getElementById("even_none").style.display="none",document.getElementById("bet_box").style.display="",document.getElementById("bet_none").style.display="none")}top.mcurrency=opener.top.mcurrency,window.onbeforeunload=unload_swf;