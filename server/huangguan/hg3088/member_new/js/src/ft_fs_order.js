var count_win=false;
//if (self==top) 	self.location.href="http://"+document.domain;
var winRedirectTimer=45000;
var winRedirect=0;
window.onload = function (){
	//top.keepGold="";
	//window.setTimeout("Win_Redirect()", 45000);
	document.getElementById("gold").blur();
	document.getElementById("gold").focus();
	if(""+top.resetCheck!="undefined"){
		var reloadTime=document.getElementById("checkOrder");
		reloadTime.checked=top.resetCheck;
	}
	var reloadautoOdd=document.getElementById("autoOdd");
	
	if(""+top.autoOddCheck!="undefined"){
			reloadautoOdd.checked=top.autoOddCheck;
	}else{
		top.autoOddCheck=false;
		reloadautoOdd.checked=top.autoOddCheck;
	}	
	onclickReloadTime();
	//resetGold();
	parent.onloadSet(document.body.scrollWidth,document.body.scrollHeight,"bet_order_frame");
	//check_ioradio();
}

function Win_Redirect(){
	var i=document.all.uid.value;
	self.location='../select.php?uid='+i;
}
function CheckKey(){
	if(event.keyCode == 13) return false;
	if((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode > 95 || event.keyCode < 106)){alert("下注金額僅能輸入數字!!"); return false;}
	//if (isNaN(event.keyCode) == true)){alert("下注金額僅能輸入數字!!"); return false;}
}


function chaCountWinGold(){
	if(document.all.gold.value==''){
		document.all.gold.focus();
		document.all.pc.innerHTML="0";
		alert('未輸入下注金額!!!');
	}else{
		var tmp_var=document.all.gold.value * document.all.ioradio_fs.value-document.all.gold.value;
		tmp_var=Math.round(tmp_var*100);
		tmp_var=tmp_var/100;
		document.all.pc.innerHTML=tmp_var;
		count_win=true;
	}
}

function onclickReloadTime(){
	var reloadTime=document.getElementById("checkOrder");
	top.resetCheck=reloadTime.checked;
	window.clearTimeout(winRedirect);
	if (!reloadTime.checked){
		//winRedirect=window.setTimeout("Win_Redirect()", winRedirectTimer);
	}else{
		winRedirect=window.setTimeout("winReload()", 1000);
	}
}
function resetTimer(){
	//回復reload時間
//document.getElementById("checkOrder").checked	=top.resetCheck;


	onclickReloadTime();
	}
function clearAllTimer(){
	//keep住 reload
//	top.resetCheck=document.getElementById("checkOrder").checked;
//		reloadTime.checked=false;
		window.clearTimeout(winRedirect);
		winRedirect=window.setTimeout("Win_Redirect()", winRedirectTimer);
		//onclickReloadTime();
	}
function winReload(){
	var showTimer=document.getElementById("ODtimer");
	if(showTimer){
        showTimer = showTimer.innerHTML ;
        showTimer=showTimer*1-1;
        document.getElementById("ODtimer").innerHTML=showTimer;
        if (showTimer<=0){
            orderReload();
        }else{
            winRedirect=window.setTimeout("winReload()", 1000);
        }
	}

}
function orderReload(){
	window.location.href=window.location;
}

$(function () {
    // 2018 新增
    fastBetAction() ;
    setBetFastAction() ;
}) ;