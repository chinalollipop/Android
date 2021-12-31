var count_win=false;
//alert(self.name);
//window.setTimeout("Win_Redirect()", 45000);
var winRedirectTimer=45000;
var winRedirect=0;

window.onload = function (){
	document.getElementById("gold").blur();
	document.getElementById("gold").focus();
	if(resetCheck){
		var reloadTime=document.getElementById("checkOrder");
		reloadTime.checked=resetCheck;
	}
	/*
	if(autoOddCheck){
		var reloadautoOdd=document.getElementById("autoOdd");
		reloadautoOdd.checked=autoOddCheck;
	}
	*/
	onclickReloadTime();
	resetGold();
	parent.onloadSet(document.body.scrollWidth,document.body.scrollHeight,"bet_order_frame");
	LoadSelect();
	check_sel_team();
	try{
		if (killgid!=""){
			parent.parent.body.killgid(killgid);
		}
	}catch(E){}
}
//檢查所選隊伍 是否變動過
function check_sel_team(){

	if(""+scripts!="undefined"){
		try{
			for (i=0;i < scripts.length;i++){
				//scripts[0]=new Array('505643','PRH','H','0','0','1.9');

				ms=get_ms(scripts[i][1]);
				gidm_new=parent.parent.body.get_gidm(scripts[i][0],ms);
				for (s=0;s < scripts.length;s++){
					//alert("1");
					ms=get_ms(scripts[s][1]);
					gidm_old=parent.parent.body.get_gidm(scripts[s][0],ms);
					//搜尋有沒出現在舊的ary裡面
					//alert(gidm_old+"----"+gidm_new);
					if (gidm_old==gidm_new&&gidm_old!=""){
						//如果找到同gid判斷是否有改變過 rtype
						//alert(scripts[i][1]+"...."+scripts[s][1]);
						if (scripts[i][1]!=scripts[s][1]||scripts[i][0]!=scripts[s][0]){
							//alert(scripts[i][1]+"---"+scripts[s][1]);
							document.getElementById("team"+(i+1)).className="team_ch";
						}
						break;
					}
				}
			}
		}catch(E){
			//alert("err");
		}
	}
	scripts=scripts;


}
function get_ms(tmp){
	//alert(tmp+"--"+tmp.length)
	if (tmp.length >= 4){
		if (tmp.substring(0,2)=="HH"||tmp.substring(0,2)=="HP"){
			return "H";
		}
	}else{
		if (tmp.substring(0,1)=="H"){
			return "H";
		}
	}
	return "";
}
//盤面自動重取賠率 start
function onclickReloadTime(){
	var reloadTime=document.getElementById("checkOrder");
	resetCheck=reloadTime.checked;
	window.clearTimeout(winRedirect);
	if (!reloadTime.checked){
		//winRedirect=window.setTimeout("Win_Redirect()", winRedirectTimer);
	}else{
		winRedirect=window.setTimeout("winReload()", 1000);
	}
}
function onclickReloadAutoOdd(){
	var reloadautoOdd=document.getElementById("autoOdd");
	autoOddCheck=reloadautoOdd.checked;
}
function orderReload(){
	window.location.href=window.location;
}
function resetTimer(){
	//回復reload時間
	onclickReloadTime();
}
function resetGold(){
	if (""+keepGold_PR!="undefined" && keepGold_PR!="" ){
		document.getElementById("gold").value=keepGold_PR;
		if(rtype=="P"){
		   var mode = 0;
		} else if(rtype=="PR") {
			var mode = 1;
		} else if(rtype=="P3"){
			var mode = 3;
		}
        ComCountWinGold(iorstr,mode);
	}
}
function winReload(){
	var showTimer=document.getElementById("ODtimer");
	if(showTimer){
        showTimer = showTimer.innerHTML ;
        showTimer=showTimer*1-1;
        document.getElementById("ODtimer").innerHTML=showTimer;
        if (showTimer<=0){
            //去執行過關盤面,FT_mem_showgame_p3.js

            try{
                if (parent.parent.body.length > 0){
                    parent.parent.body.orderParlayParam();
                }else{
                    document.getElementById("ODtimer").innerHTML="10";
                    winRedirect=window.setTimeout("winReload()", 1000);
                }
            }catch(E){
                document.getElementById("ODtimer").innerHTML="10";
                winRedirect=window.setTimeout("winReload()", 1000);
            }
            //window.location.reload();
        }else{
            winRedirect=window.setTimeout("winReload()", 1000);
        }
	}

}
//盤面自動重取賠率 end


function Win_Redirect(){

	//去執行過關盤面,FT_mem_showgame_p3.js
	try{
		parent.body.orderRemoveALL();
	}catch (E) {}
	scripts=new Array();
	parent.close_bet();
}
/*
function resort(ary){
	var tempary=new Array();
	for(var i=0;i<ary.length;i++){
		if (ary[i]!=0){
			tempary[tempary.length]=ary[i];
			}
		}
	return tempary;
	}

*/
function delteams(teamid){

//alert(parent.parent.body.name)
	//alert(teamid);
	//去執行過關盤面,FT_mem_showgame_p3.js
	try{
		parent.parent.body.orderRemoveGid(scripts[teamid-1][0]);
	}catch(E){}
	try{
		parent.parent.body.orderParlayParam();
	}catch(E){}
	eval("TR"+teamid+".style.display='none'");
	document.all.teamcount.value=document.all.teamcount.value-1;
	scripts[teamid-1][0]="0";
	LoadSelect();
}

function LoadSelect(){
	if(rtype=="P"){
	   var mode = 0;
	} else if(rtype=="PR") {
		var mode = 1;
	} else if(rtype=="P3"){
		var mode = 3;
	}

 	for (s=0;s < scripts.length ;s++){
 		for (i=0;i < parent.paramData.length ;i++){
 			//check GID
 			if (parent.paramData[i][0]==scripts[s][0]){
 				if (parent.paramData[i][3]!=scripts[s][3]||parent.paramData[i][4]!=scripts[s][4]||parent.paramData[i][5]!=scripts[s][5]){
 					//eval("document.getElementById('P"+(s+1)+"').style.background='#FFDFDF'");
 					document.getElementById("P"+(s+1)).className="lightOn";
 					//put gold
 					if(document.all.gold.value==""){
						document.all.gold.value=parent.goldData;
						document.all.gold.focus();
                        ComCountWinGold(iorstr,mode);
				 	}
 				}
 			}
 		}
 	}
 	parent.paramData=new Array();
 //}

	//document.all.wteam.style.display="none"
	//alert("===>"+minlimit);
	if(document.all.teamcount.value <= (minlimit*1-1)){
		//document.all.btnCancel.disabled = true;
		document.all.SUBMIT.disabled = true;
		//document.all.wkind.style.display="none"
		//document.all.wstar.style.display="none"
		document.all.gold.style.display="none"
	}
	if (document.all.teamcount.value <= 0){
		//alert(document.all.teamcount.value);
		Win_Redirect();
		//document.all.wstar.length = 1;
		//document.all.wstar.options[0]=new Option(document.all.teamcount.value+message017,document.all.teamcount.value);
	}

}

function chiang_wkind(){
	if(document.all.wkind.value == 'M'){
		document.all.wstar.length = document.all.teamcount.value-2;
		for(i=2; i<document.all.teamcount.value; i++)
			document.all.wstar.options[i-2]=new Option(i+message017,i);
			document.all.wteam.style.display="block";
			document.all.wteam.length = 1;
			var count=0;
			var start=eval(document.all.wstar.value)+1;
			document.all.wteam.options[count++]=new Option(document.all.teamcount.value+message018,document.all.teamcount.value)
	}else{
		document.all.wstar.length = 1;
		document.all.wstar.options[0]=new Option(document.all.teamcount.value+message017,document.all.teamcount.value);
		document.all.wteam.style.display="none";
	}
}

function chiang_wstar(){
	if(document.all.wkind.value == 'M'){
		document.all.wteam.style.display="block";
		document.all.wteam.length = 1;
		var count=0;
		var start=eval(document.all.wstar.value)+1;
		document.all.wteam.options[0]=new Option(document.all.teamcount.value+message018,document.all.teamcount.value)
	}else{
		document.all.wteam.style.display="none";
	}
}

function CheckKey(evt){
	var key = window.event ? evt.keyCode : evt.which;
	//alert(key)
	//var keychar = String.fromCharCode(key);
	//alert(keychar)

	if(key == 32){
		return false;
		}

	if(key == 13) {
        ComCountWinGold(iorstr,3);
		CheckSubmit();
	}
	else if((key < 48 || key > 57) && (key > 95 || key < 106)){alert(message015); return false;}


}


function CheckSubmit(){

	if (parent.paramData.length == 0||(""+parent.paramData.length=="undefined")){
		parent.paramData=scripts;
		//alert("aaa==>"+parent.paramData+"==bbb===>"+scripts);
	}
	if(document.all.gold.value==''){
		document.all.gold.focus();
		alert(message001);
		return false;
 	}else if(document.all.teamcount.value <= (minlimit*1-1)){
		alert(message019+minlimit+message020);
		return false;
	}else if(document.all.teamcount.value > maxlimit*1){
		alert(message021 + document.all.teamcount.value + message022);
		return false;
	}else if(document.all.gold.value==''){
	  alert(message023);
	  document.all.gold.focus();
	  return false;
	 }else if(document.all.gold.value*1 < (document.all.gmin_single.value.replace(",",'')*1)){
 	  document.all.gold.focus();
	  alert(message003+" "+mcurrency+" "+document.all.gmin_single.value);
	  return false;
	 //檢查現金顧客
	  }else if (document.all.pay_type.value =='1') {
		 if(eval(document.all.gold.value*1) > eval(document.all.gmax_single.value)){
			  document.all.gold.focus();
			  alert(message024);
			  return false;
		  }
	}else if(eval(document.all.gold.value*1) > eval(document.all.restcredit.value)){
	  document.all.gold.focus();
	  alert(message025);
	  return false;
	 }else if(!confirm(message011+document.all.pc.innerHTML+message016)){return false;}
  document.all.btnCancel.disabled = true;
  document.all.SUBMIT.disabled = true;
  document.all.gold.readOnly=true;
  document.all.gold.blur();
  document.all.wagerDatas.value="";
  for (kk=0;kk<scripts.length;kk++){
  	if (scripts[kk][0]!="0")  document.all.wagerDatas.value+=scripts[kk].toString()+"|";
  }
 	//將金額放到暫存
	parent.goldData=document.all.gold.value;

  document.forms[0].submit();
  parent.body.orderRemoveALL();
}

// 综合过关玩法单独计算彩金
function ComCountWinGold(chk,mode){
   /*------------------------------------------------
   * edit date --- 2005/7/14						*
   * From anson										*
   * Content --- from 202 to 206					*
   -------------------------------------------------*/

	chk=chk.split(' ');
	var iortmp="";
	if(document.all.gold.value==''){
		document.getElementById("gold").blur();
		document.getElementById("gold").focus();
		document.all.pc.innerHTML="0";
		keepGold_PR="";
	}else{
		keepGold_PR=document.getElementById("gold").value;
		counttmp=document.all.tcount.value;
		gold1=document.all.gold.value;
		if(counttmp>1){
			tmp=1;
			for(q=0;q<counttmp;q++){
				if (scripts[q][0] != "0"){
					if(mode=="3"||mode=="1"){
						//20090203本金已計算
						//if(scripts[q][1]=="PRH"||scripts[q][1]=="PRC"||scripts[q][1]=="HPRH"||scripts[q][1]=="HPRC"||scripts[q][1]=="POUH"||scripts[q][1]=="POUC"||scripts[q][1]=="HPOUH"||scripts[q][1]=="HPOUC"){

						//	tmp*=(1+(parseFloat(chk[q])));
						//}else{
							tmp*=((parseFloat(chk[q])));
						//}
					}else{
						tmp*=(mode+(parseFloat(chk[q])));
					}
				}
			}
    	var tmp_var=gold1*tmp-gold1;
    	tmp_var=Math.round(tmp_var*100);
    	tmp_var=tmp_var/100;
	    if (tmp_var*1 > document.getElementById("maxgold").value*1){
	    	document.getElementById("err_div").style.display="";
	    }else{
	    	document.getElementById("err_div").style.display="none";
	    }

	    parent.onloadSet(document.body.scrollWidth,document.body.scrollHeight,"bet_order_frame");
	    tmp_var=addComma(tmp_var);
	    tmp_var=printf(tmp_var,2);
			document.all.pc.innerHTML=tmp_var;
		}
	}
}

$(function () {
    // 2018 新增
    fastBetAction() ;
    setBetFastAction() ;
}) ;