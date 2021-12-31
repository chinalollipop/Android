
if (""+top.cgTypebtn=="undefined"){
	top.cgTypebtn="re_class";
}

if (""+top.head_gtype=="undefined"){
	top.head_gtype="FT";
}
if (""+top.head_FU=="undefined"){
	top.head_FU="FT";
}
if (""+top.head_btn=="undefined"){
	top.head_btn="today";
}


function onloaded() {
	if (top.casino != "SI2") {
		try{
			document.getElementById("live").style.display = "none";
			document.getElementById("QA_row").style.display = "none";
		}catch(E){}
	}
	var obj= document.getElementById(top.cgTypebtn+"");
      obj.className="type_on";
	try{
		if ((navigator.appVersion).indexOf("MSIE 6")==-1){
			document.getElementById("download").style.visibility="visible";
		}
	}catch(E){}
	try{
		document.getElementById("today_btn").className="early";
	}catch(E){}
	try{
		document.getElementById("early_btn").className="early";
	}catch(E){}		
	try{
		document.getElementById("rb_btn").className="rb";
	}catch(E){}		
				
	try{
		//alert("onload==>"+top.head_btn);
		document.getElementById(top.head_btn+"_btn").className=top.head_btn+"_on";
	}catch(E){}	
		//更新信用額度   max---
	//reloadCrditFunction();
	//showTable();
  	//GameType();
  
}

function chg_head(a,b,c){
    top.RB_id="";
    top.hot_game="";
    if(top.swShowLoveI)b=3;
    if(top.showtype=='hgft')b=3;
    var hot_str="";
    hot_str="&hot_game="+top.hot_game;
    parent.body.location=a+"&league_id="+b+hot_str;
}
function chg_type(a,b,c){

    // if(top.swShowLoveI)b=3;
    // if(top.showtype=='hgft')b=3;
    // parent.body.location=a+"&league_id="+b;

    top.RB_id="";
    top.hot_game="";
    if(top.swShowLoveI)b=3;
    if(top.showtype=='hgft')b=3;
    var hot_str="";
    if(top.head_gtype=="FT"){
        try{
            parent.mem_order.goEuro_HOT_btn("");
        }catch(E){}
    }

    var url = a;
    //加入hot_game參數值
    url += "&hot_game="+top.hot_game;
    if(b!=undefined) url += "&league_id="+b;
    parent.body.location = url;
}
function chg_index(obj,a,b,c,d,future){
    $(obj).addClass('second_title_active').parents('li').siblings().find('a').removeClass('second_title_active') ; // 选中类

    //top.keepGold="";
    top.swShowLoveI=false;
    top.cgTypebtn="re_class";
    parent.body.location.href=b;
    // self.location.href=a;
}
function chg_type_class(game_type){
//已選取：黃字 class="type_on"
//選取後離開：白字 class="type_out"
    if(game_type != top.cgTypebtn ){
        var obj= document.getElementById(game_type+"");
        var obj_laster= document.getElementById(top.cgTypebtn+"");
        // console.log(obj)
        obj.className="type_on";
        obj_laster.className="type_out";
        top.cgTypebtn=game_type;
    }


}
// g_type 当前游戏类型
function chg_button_bg(gtype,btn,g_type,uid){
    top.head_gtype=gtype;
    top.head_g_type=g_type;
    // console.log(top.head_gtype) ;
    //if (btn=="") return;
    if (btn=="early"||btn=="today" || btn =='rb'){
        chg_type_class("re_class");
    }
    sessionStorage.setItem('m_type',btn) ;
    sessionStorage.setItem('g_type',top.head_gtype) ;
    /*	if (btn=="rb"){
            chg_type_class("rb_class");
        }*/
    if (btn!="rb"){
        if(btn=="early"){
            top.head_FU="FU";
        }else{
            top.head_FU="FT";
        }
        $('#fs_class').show(); // 滚球没有冠军
    }
    try{
        document.getElementById(top.head_btn+"_btn").className=top.head_btn;
    }catch(E){}
    top.head_btn=btn;
    try{
        document.getElementById(btn+"_btn").className=btn+"_on";
    }catch(E){}

    if(g_type){ // 二级导航调用此参数
        if(gtype=='FT'){ // 足球
            $('#pd_class,#to_class,#hf_class').show();
        }else{ // 篮球
            $('#pd_class,#to_class,#hf_class').hide();
        }
        // 一级导航
        $('#rbyshow').attr('onclick','chg_second_tip(this,\'rb\',\''+uid+'\',\''+g_type+'\');chg_button_bg(\''+g_type+'\',\'rb\');chg_index(this,\' \',\'/app/member/'+g_type+'_browse/index.php?rtype=re&uid='+uid+'&langx=zh-cn&mtype=4&showtype=rb\',\'\',\'SI2\',\'rb\')') ; // 滚球链接
        $('#todayshow').attr('onclick','chg_second_tip(this,\'today\',\''+uid+'\',\''+g_type+'\');chg_button_bg(\''+g_type+'\',\'today\');chg_index(this,\' \',\'/app/member/'+g_type+'_browse/index.php?rtype=r&uid='+uid+'&langx=zh-cn&mtype=4&showtype=today\',\'\',\'SI2\',\'r\')') ; // 今日赛事链接
        $('#earlyshow').attr('onclick','chg_second_tip(this,\'early\',\''+uid+'\',\''+g_type+'\');chg_button_bg(\''+g_type+'\',\'early\');chg_index(this,\' \',\'/app/member/'+g_type+'_future/index.php?rtype=r&uid='+uid+'&langx=zh-cn&mtype=4&showtype=future\',\'\',\'SI2\',\'r\')') ; // 今日赛事链接
        var showtype = '' ;
        if (btn=="early"){ // 早盘
            showtype = 'future' ;
        }else if(btn=="rb"){ // 滚球
            showtype = 'rb' ;
        }
        // 三级导航
        $('#re_class').attr('onclick','chg_button_bg(\''+gtype+'\',\''+btn+'\');chg_type(\'/app/member/'+gtype+'_'+(btn=='early'?'future':'browse')+'/index.php?rtype='+(btn=='rb'?'re':'r')+'&uid='+uid+'&langx=zh-cn&mtype=4&showtype='+showtype+'\',\' \',\'SI2\');chg_type_class(\'re_class\');return false'); // 足球 全部
        $('#pd_class').attr('onclick','chg_button_bg(\''+gtype+'\',\''+btn+'\');chg_type(\'/app/member/'+gtype+'_'+(btn=='early'?'future':'browse')+'/index.php?rtype='+(btn=='rb'?'rpd':'pd')+'&uid='+uid+'&langx=zh-cn&mtype=4&showtype='+showtype+'\',\' \',\'SI2\');chg_type_class(\'pd_class\');return false'); // 足球 波胆
        $('#to_class').attr('onclick','chg_button_bg(\''+gtype+'\',\''+btn+'\');chg_type(\'/app/member/'+gtype+'_'+(btn=='early'?'future':'browse')+'/index.php?rtype='+(btn=='rb'?'rt':'t')+'&uid='+uid+'&langx=zh-cn&mtype=4&showtype='+showtype+'\',\' \',\'SI2\');chg_type_class(\'to_class\');return false'); // 足球 总入球
        $('#hf_class').attr('onclick','chg_button_bg(\''+gtype+'\',\''+btn+'\');chg_type(\'/app/member/'+gtype+'_'+(btn=='early'?'future':'browse')+'/index.php?rtype='+(btn=='rb'?'rf':'f')+'&uid='+uid+'&langx=zh-cn&mtype=4&showtype='+showtype+'\',\' \',\'SI2\');chg_type_class(\'hf_class\');return false'); // 足球 半场全场

        $('#hp3_class').attr('onclick','chg_button_bg(\''+gtype+'\',\''+btn+'\');chg_type(\'/app/member/'+gtype+'_'+(btn=='early'?'future':'browse')+'/index.php?rtype=p3&uid='+uid+'&langx=zh-cn&mtype=4&showtype='+showtype+'\',\' \',\'SI2\');chg_type_class(\'hp3_class\');return false'); //  综合过关

        $('#fs_class').attr('onclick','chg_button_bg(\''+gtype+'\',\''+btn+'\');chg_type(\'/app/member/browse_FS/loadgame_R.php?FStype='+gtype+'&uid='+uid+'&langx=zh-cn&mtype=4&showtype='+showtype+'\',\' \',\'SI2\');chg_type_class(\'fs_class\');return false'); // 足球 冠军
        // 赛果
        $('#result_class').attr({'href':'/app/member/result/result.php?game_type='+gtype+'&uid='+uid+'&langx=zh-cn','onclick':'chg_button_bg(\''+gtype+'\',\'today\');chg_type_class(\'result_class\');'}) ;

    }


}

/*  头部一级导航 函数
 * type, rb:滚球，today:今日赛事，early:早盘
 *  gtype FT,BK
 * */
function chg_second_tip(obj,type,uid,gtype) {
    switch (type){
        case 'rb': // 滚球
            // 二级导航
            $('#ft_link').attr('onclick','chg_button_bg(\'FT\',\'today\',\'FT\',\''+uid+'\');chg_index(this,\' \',\'/app/member/FT_browse/index.php?rtype=r&uid='+uid+'&langx=zh-cn&mtype=4&showtype=\',\' \',\'SI2\');return false'); // 足球
            $('#bk_link').attr('onclick','chg_button_bg(\'BK\',\'today\',\'BK\',\''+uid+'\');chg_index(this,\' \',\'/app/member/BK_browse/index.php?rtype=all&uid='+uid+'&langx=zh-cn&mtype=4&showtype=\',\' \',\'SI2\');return false') ; // 篮球

            // 三级导航
            $('#re_class').attr('onclick','chg_button_bg(\''+gtype+'\',\''+type+'\');chg_type(\'/app/member/'+gtype+'_browse/index.php?rtype=re&uid='+uid+'&langx=zh-cn&mtype=4&showtype=rb\',\' \',\'SI2\');chg_type_class(\'re_class\');return false'); // 足球 全部
            $('#pd_class').attr('onclick','chg_button_bg(\''+gtype+'\',\''+type+'\');chg_type(\'/app/member/'+gtype+'_browse/index.php?rtype=rpd&uid='+uid+'&langx=zh-cn&mtype=4&showtype=rb\',\' \',\'SI2\');chg_type_class(\'pd_class\');return false'); // 足球 波胆
            $('#to_class').attr('onclick','chg_button_bg(\''+gtype+'\',\''+type+'\');chg_type(\'/app/member/'+gtype+'_browse/index.php?rtype=rt&uid='+uid+'&langx=zh-cn&mtype=4&showtype=rb\',\' \',\'SI2\');chg_type_class(\'to_class\');return false'); // 足球 总入球
            $('#hf_class').attr('onclick','chg_button_bg(\''+gtype+'\',\''+type+'\');chg_type(\'/app/member/'+gtype+'_browse/index.php?rtype=rf&uid='+uid+'&langx=zh-cn&mtype=4&showtype=rb\',\' \',\'SI2\');chg_type_class(\'hf_class\');return false'); // 足球 半场全场

            break ;
        case 'today': // 今日赛事
            // 二级导航
            $('#ft_link').attr('onclick','chg_button_bg(\'FT\',\'today\',\'FT\',\''+uid+'\');chg_index(this,\' \',\'/app/member/FT_browse/index.php?rtype=r&uid='+uid+'&langx=zh-cn&mtype=4&showtype=\',\' \',\'SI2\');return false'); // 足球
            $('#bk_link').attr('onclick','chg_button_bg(\'BK\',\'today\',\'BK\',\''+uid+'\');chg_index(this,\' \',\'/app/member/BK_browse/index.php?rtype=all&uid='+uid+'&langx=zh-cn&mtype=4&showtype=\',\' \',\'SI2\');return false') ; // 篮球

            // 三级导航
            $('#re_class').attr('onclick','chg_button_bg(\''+gtype+'\',\''+type+'\');chg_type(\'/app/member/'+gtype+'_browse/index.php?rtype=r&uid='+uid+'&langx=zh-cn&mtype=4&showtype=\',\' \',\'SI2\');chg_type_class(\'re_class\');return false'); // 足球 全部
            $('#pd_class').attr('onclick','chg_button_bg(\''+gtype+'\',\''+type+'\');chg_type(\'/app/member/'+gtype+'_browse/index.php?rtype=pd&uid='+uid+'&langx=zh-cn&mtype=4&showtype=\',\' \',\'SI2\');chg_type_class(\'pd_class\');return false'); // 足球 波胆
            $('#to_class').attr('onclick','chg_button_bg(\''+gtype+'\',\''+type+'\');chg_type(\'/app/member/'+gtype+'_browse/index.php?rtype=t&uid='+uid+'&langx=zh-cn&mtype=4&showtype=\',\' \',\'SI2\');chg_type_class(\'to_class\');return false'); // 足球 总入球
            $('#hf_class').attr('onclick','chg_button_bg(\''+gtype+'\',\''+type+'\');chg_type(\'/app/member/'+gtype+'_browse/index.php?rtype=f&uid='+uid+'&langx=zh-cn&mtype=4&showtype=\',\' \',\'SI2\');chg_type_class(\'hf_class\');return false'); // 足球 半场全场

            $('#hp3_class').attr('onclick','chg_button_bg(\''+gtype+'\',\''+type+'\');chg_type(\'/app/member/'+gtype+'_browse/index.php?rtype=p3&uid='+uid+'&langx=zh-cn&mtype=4&showtype=\',\' \',\'SI2\');chg_type_class(\'hp3_class\');return false'); // 足球 综合过关

            $('#fs_class').attr('onclick','chg_button_bg(\''+gtype+'\',\''+type+'\');chg_type(\'/app/member/browse_FS/loadgame_R.php?FStype='+gtype+'&uid='+uid+'&langx=zh-cn&mtype=4&showtype=\',\' \',\'SI2\');chg_type_class(\'fs_class\');return false'); // 足球 冠军

            break ;
        case 'early': // 早盘
            // 二级导航
            $('#ft_link').attr('onclick','chg_button_bg(\'FT\',\'early\',\'FT\',\''+uid+'\');chg_index(this,\' \',\'/app/member/FT_future/index.php?rtype=r&uid='+uid+'&langx=zh-cn&mtype=4&showtype=future\',\' \',\'SI2\');return false'); // 足球
            $('#bk_link').attr('onclick','chg_button_bg(\'BK\',\'early\',\'BK\',\''+uid+'\');chg_index(this,\' \',\'/app/member/BK_future/index.php?rtype=all&uid='+uid+'&langx=zh-cn&mtype=4&showtype=future\',\' \',\'SI2\');return false') ; // 篮球
            // 三级导航
            $('#re_class').attr('onclick','chg_button_bg(\''+gtype+'\',\''+type+'\');chg_type(\'/app/member/'+gtype+'_future/index.php?rtype=r&uid='+uid+'&langx=zh-cn&mtype=4&showtype=future\',\' \',\'SI2\');chg_type_class(\'re_class\');return false'); // 足球 全部
            $('#pd_class').attr('onclick','chg_button_bg(\''+gtype+'\',\''+type+'\');chg_type(\'/app/member/'+gtype+'_future/index.php?rtype=pd&uid='+uid+'&langx=zh-cn&mtype=4&showtype=future\',\' \',\'SI2\');chg_type_class(\'pd_class\');return false'); // 足球 波胆
            $('#to_class').attr('onclick','chg_button_bg(\''+gtype+'\',\''+type+'\');chg_type(\'/app/member/'+gtype+'_future/index.php?rtype=t&uid='+uid+'&langx=zh-cn&mtype=4&showtype=future\',\' \',\'SI2\');chg_type_class(\'to_class\');return false'); // 足球 总入球
            $('#hf_class').attr('onclick','chg_button_bg(\''+gtype+'\',\''+type+'\');chg_type(\'/app/member/'+gtype+'_future/index.php?rtype=f&uid='+uid+'&langx=zh-cn&mtype=4&showtype=future\',\' \',\'SI2\');chg_type_class(\'hf_class\');return false'); // 足球 半场全场
            $('#hp3_class').attr('onclick','chg_button_bg(\''+gtype+'\',\''+type+'\');chg_type(\'/app/member/'+gtype+'_future/index.php?rtype=p3&uid='+uid+'&langx=zh-cn&mtype=4&showtype=future\',\' \',\'SI2\');chg_type_class(\'hp3_class\');return false'); // 足球 综合过关
            $('#fs_class').attr('onclick','chg_button_bg(\''+gtype+'\',\''+type+'\');chg_type(\'/app/member/browse_FS/loadgame_R.php?FStype='+gtype+'&uid='+uid+'&langx=zh-cn&mtype=4&showtype=future\',\' \',\'SI2\');chg_type_class(\'fs_class\');return false'); // 足球 冠军

            break ;
    }
    if(gtype=='FT'){ // 足球
        $('#pd_class,#to_class,#hf_class').show();
        if(type=='rb'){ // 足球滚球
            $('#fs_class').hide(); // 没有 冠军
        }else{
            $('#hp3_class,#fs_class').show();
        }
    }else{ // 篮球
        $('#pd_class,#to_class,#hf_class').hide();
        if(type=='rb'){ // 蓝球滚球
            $('#fs_class').hide(); // 没有综合过关 和 冠军
        }else{
            $('#hp3_class,#fs_class').show();
        }
    }
}

// 语言切换
function changeLangx(setlangx){
    top.cgTypebtn="re_class";
    top.langx=setlangx;
    top.head_gtype="FT";
    top.head_FU="FT";
    top.head_btn="today";
    top.FT_lid = new Array();
    top.FU_lid = new Array();
    top.FSFT_lid = new Array();
    top.FT_lid['FT_lid_ary']= FT_lid_ary='ALL';
    top.FT_lid['FT_lid_type']= FT_lid_type='';
    top.FT_lid['FT_lname_ary']= FT_lname_ary='ALL';
    top.FT_lid['FT_lid_ary_RE']= FT_lid_ary_RE='ALL';
    top.FT_lid['FT_lname_ary_RE']= FT_lname_ary_RE='ALL';
    top.FU_lid['FU_lid_ary']= FU_lid_ary='ALL';
    top.FU_lid['FU_lid_type']= FU_lid_type='';
    top.FU_lid['FU_lname_ary']= FU_lname_ary='ALL';
    top.FSFT_lid['FSFT_lid_ary']= FSFT_lid_ary='ALL';
    top.FSFT_lid['FSFT_lname_ary']= FSFT_lname_ary='ALL';

    top.BK_lid = new Array();
    top.BU_lid = new Array();
    top.FSBK_lid = new Array();
    top.BK_lid['BK_lid_ary']= BK_lid_ary='ALL';
    top.BK_lid['BK_lid_type']= BK_lid_type='';
    top.BK_lid['BK_lname_ary']= BK_lname_ary='ALL';
    top.BK_lid['BK_lid_ary_RE']= BK_lid_ary_RE='ALL';
    top.BK_lid['BK_lname_ary_RE']= BK_lname_ary_RE='ALL';
    top.BU_lid['BU_lid_ary']= BU_lid_ary='ALL';
    top.BU_lid['BU_lid_type']= BU_lid_type='';
    top.BU_lid['BU_lname_ary']= BU_lname_ary='ALL';
    top.FSBK_lid['FSBK_lid_ary']= FSBK_lid_ary='ALL';
    top.FSBK_lid['FSBK_lname_ary']= FSBK_lname_ary='ALL';

    top.BS_lid = new Array();
    top.BSFU_lid = new Array();
    top.FSBS_lid = new Array();
    top.BS_lid['BS_lid_ary']= BS_lid_ary='ALL';
    top.BS_lid['BS_lid_type']= BS_lid_type='';
    top.BS_lid['BS_lname_ary']= BS_lname_ary='ALL';
    top.BS_lid['BS_lid_ary_RE']= BS_lid_ary_RE='ALL';
    top.BS_lid['BS_lname_ary_RE']= BS_lname_ary_RE='ALL';
    top.BSFU_lid['BSFU_lid_ary']= BSFU_lid_ary='ALL';
    top.BSFU_lid['BSFU_lid_type']= BSFU_lid_type='';
    top.BSFU_lid['BSFU_lname_ary']= BSFU_lname_ary='ALL';
    top.FSBS_lid['FSBS_lid_ary']= FSBS_lid_ary='ALL';
    top.FSBS_lid['FSBS_lname_ary']= FSBS_lname_ary='ALL';

    top.TN_lid = new Array();
    top.TU_lid = new Array();
    top.FSTN_lid = new Array();
    top.TN_lid['TN_lid_ary']= TN_lid_ary='ALL';
    top.TN_lid['TN_lid_type']= TN_lid_type='';
    top.TN_lid['TN_lname_ary']= TN_lname_ary='ALL';
    top.TN_lid['TN_lid_ary_RE']= TN_lid_ary_RE='ALL';
    top.TN_lid['TN_lname_ary_RE']= TN_lname_ary_RE='ALL';
    top.TU_lid['TU_lid_ary']= TU_lid_ary='ALL';
    top.TU_lid['TU_lid_type']= TU_lid_type='';
    top.TU_lid['TU_lname_ary']= TU_lname_ary='ALL';
    top.FSTN_lid['FSTN_lid_ary']= FSTN_lid_ary='ALL';
    top.FSTN_lid['FSTN_lname_ary']= FSTN_lname_ary='ALL';

    top.VB_lid = new Array();
    top.VU_lid = new Array();
    top.FSVB_lid = new Array();
    top.VB_lid['VB_lid_ary']= VB_lid_ary='ALL';
    top.VB_lid['VB_lid_type']= VB_lid_type='';
    top.VB_lid['VB_lname_ary']= VB_lname_ary='ALL';
    top.VB_lid['VB_lid_ary_RE']= VB_lid_ary_RE='ALL';
    top.VB_lid['VB_lname_ary_RE']= VB_lname_ary_RE='ALL';
    top.VU_lid['VU_lid_ary']= VU_lid_ary='ALL';
    top.VU_lid['VU_lid_type']= VU_lid_type='';
    top.VU_lid['VU_lname_ary']= VU_lname_ary='ALL';
    top.FSVB_lid['FSVB_lid_ary']= FSVB_lid_ary='ALL';
    top.FSVB_lid['FSVB_lname_ary']= FSVB_lname_ary='ALL';
    top.OP_lid = new Array();
    top.OM_lid = new Array();
    top.FSOP_lid = new Array();
    top.OP_lid['OP_lid_ary']= OP_lid_ary='ALL';
    top.OP_lid['OP_lid_type']= OP_lid_type='';
    top.OP_lid['OP_lname_ary']= OP_lname_ary='ALL';
    top.OP_lid['OP_lid_ary_RE']= OP_lid_ary_RE='ALL';
    top.OP_lid['OP_lname_ary_RE']= OP_lname_ary_RE='ALL';
    top.OM_lid['OM_lid_ary']= OM_lid_ary='ALL';
    top.OM_lid['OM_lid_type']= OM_lid_type='';
    top.OM_lid['OM_lname_ary']= OM_lname_ary='ALL';
    top.FSOP_lid['FSOP_lid_ary']= FSOP_lid_ary='ALL';
    top.FSOP_lid['FSOP_lname_ary']= FSOP_lname_ary='ALL';
    top.head_btn="today";

    parent.location.href=((""+parent.location).replace("zh-tw",setlangx).replace("zh-cn",setlangx).replace("en-us",setlangx));
    //}

}
/* 流程 SetRB ---> reloadRB --->  showLayer */

/*滾球提示--將值帶進去去開啟getrecRB.php程式,去抓取伺服器是否有滾球賽程*/
var record_RB = 0;
function reloadRB(gtype,uid){
	//alert("reloadphp===>"+uid)
	reloadPHP.location.href="./getrecRB.php?gtype="+gtype+"&uid="+top.uid;
	//alert("reloadphp end")
	chkMemOnline();
}
function chkMemOnline(){
	//memOnline.location.href="./mem_online.php?uid="+top.uid;
}
/*滾球提示--將getrecRB.php的結果帶進去,去判斷是否record_RB是否大於0,如果有會顯示滾球圖示*/

function showLayer(record_RB){
	
	document.getElementById('RB_games').innerHTML=record_RB;
	document.getElementById('FT_games').innerHTML=0;
	document.getElementById('BK_games').innerHTML=0;
	document.getElementById('TN_games').innerHTML=0;
	document.getElementById('BS_games').innerHTML=0;
	document.getElementById('VB_games').innerHTML=0;
	document.getElementById('OP_games').innerHTML=0;


reloadCrditFunction();

}

//-----------------時鍾------------------每秒顯示
var nowTimer=0;
var stimer=0;
function autoZero(val){
	if (val<10){
		return "0"+val;
		}
		return val;
	}
	

function headerShowTimer(obj){
    var nowDate = new Date(new Date().getTime() - 43200000),
        nY = nowDate.getFullYear(),
        nM = nowDate.getMonth() + 1,
        nD = nowDate.getDate(),
        nH = nowDate.getHours(),
        nMi = nowDate.getMinutes(),
        nS = nowDate.getSeconds();
    nM = nM < 10 ? '0' + nM : nM;
    nD = nD < 10 ? '0' + nD : nD;
    nH = nH < 10 ? '0' + nH : nH;
    nMi = nMi < 10 ? '0' + nMi : nMi;
    nS = nS < 10 ? '0' + nS : nS;
    var fullTime = nY + '年' + nM + '月' + nD +'日'+ ' ' + nH + ':' + nMi + ':' + nS;
    $(obj).text(fullTime);
}


/*function GameCount(games){
	console.log('执行这里啦')
	var countgames=games.split(",");
	var recordHash=new Array();
	recordHash["DATE"]=countgames[0];
	

	recordHash["RB"]=0;
	for( var i=1;i<countgames.length;i++){
		var detailgame=countgames[i].split("|");
		recordHash[detailgame[0]+"_"+detailgame[1]]=detailgame[2]*1;
		
	}
	try{
		if (top.head_FU=="FU"){	
     		document.getElementById('FT_games').innerHTML=recordHash["FT_"+top.head_FU];
			document.getElementById('BK_games').innerHTML=recordHash["BK_"+top.head_FU];
			document.getElementById('TN_games').innerHTML=recordHash["TN_"+top.head_FU];
			document.getElementById('BS_games').innerHTML=recordHash["BS_"+top.head_FU];
			document.getElementById('VB_games').innerHTML=recordHash["VB_"+top.head_FU];
			document.getElementById('OP_games').innerHTML=recordHash["OP_"+top.head_FU];
			
		}else{
			document.getElementById('RB_games').innerHTML=recordHash[top.head_gtype+"_RB"];
			document.getElementById('subRB_games').innerHTML=recordHash[top.head_gtype+"_RB"]; 
			document.getElementById('FT_games').innerHTML=recordHash["FT_"+top.head_FU]+recordHash["FT_RB"];
			document.getElementById('BK_games').innerHTML=recordHash["BK_"+top.head_FU]+recordHash["BK_RB"];
			document.getElementById('TN_games').innerHTML=recordHash["TN_"+top.head_FU]+recordHash["TN_RB"];
			document.getElementById('BS_games').innerHTML=recordHash["BS_"+top.head_FU]+recordHash["BS_RB"];
			document.getElementById('VB_games').innerHTML=recordHash["VB_"+top.head_FU]+recordHash["VB_RB"];
			document.getElementById('OP_games').innerHTML=recordHash["OP_"+top.head_FU]+recordHash["OP_RB"];
		}
	}catch(E){}

	if (top.head_FU=="FT"){
		if (rb_count*1 > 0){
			document.getElementById("rb_btn").style.visibility = "visible";
		}else{
			document.getElementById("rb_btn").style.visibility = "hidden";
		}
	}

  if (top.head_btn=="early"){	
    document.getElementById("early_btn").className="early_on";
  }else if(top.head_btn=="rb"){
  	document.getElementById("rb_btn").className="rb_on";
  }else{
  	document.getElementById("today_btn").className="today_on";
  }
	//chg_button_bg(top.head_gtype,top.head_FU);
	reloadCrditFunction();

}*/

/* 滾球提示--程式一開始值呼叫reloadRb,setInterval函式 多久會呼叫reloadRB函數預設 1分鐘 */
function SetRB(gttype,uid){
	//alert("setRB=>"+top.uid);
	reloadRB(gttype,top.uid);
	setInterval("reloadRB('"+gttype+"','"+top.uid+"')",60*1000);
}
/*function  getdomain(){
	var a = new Array();
	a[0]= document.domain;
	ESTime.setdomain(a);
	return a;
}*/
function OnMouseOverEvent() {
	//document.getElementById("informaction").style.display = "block";
}
function OnMouseOutEvent() {
	//document.getElementById("informaction").style.display = "none";
}

function Go_Chg_pass(){
	Real_Win=window.open("../../../app/member/account/chg_passwd.php?uid="+top.uid+"&langx="+top.langx,"Chg_pass","width=752,height=562,status=no,location=no");
}
function OpenLive(){
	if (top.liveid == undefined) {
		parent.self.location = "";
		return;
	}
	window.open("./live/live.php?langx="+top.langx+"&uid="+top.uid+"&liveid="+top.liveid,"Live","width=780,height=580,top=0,left=0,status=no,toolbar=no,scrollbars=yes,resizable=no,personalbar=no");
}

/*function chkLookGtypeShowLoveI(getgtype,gtype){
	var txtnum = StatisticsGty(top.today_gmt,gtype);	
	if(txtnum[0]==0)return ;
	top.swShowLoveI =true;
	if(getgtype != top.getNewGtype ){
		top.getNewGtype =getgtype;		
		parent.location=getgtype+"&league_id=3";
	}else{
		//alert("====>");
		eval("parent."+gtype+"_lid_type='3'");
		//parent.body.ShowGameList();
		//alert(parent.body.pg);
		parent.body.pg =0;
		parent.body.body_browse.reload_var("up");
	}
}*/

function chkDelAllShowLoveI(getGtype){
	top.ShowLoveIarray[getGtype]= new Array();
	top.ShowLoveIOKarray[getGtype]="";
	if(top.swShowLoveI){
		top.swShowLoveI=false;
		eval("parent."+parent.body.sel_gtype+"_lid_type=top."+parent.body.sel_gtype+"_lid['"+parent.body.sel_gtype+"_lid_type']");
		parent.body.pg =0;
		parent.body.body_browse.reload_var("up");
	}else{
		parent.body.ShowGameList();
	}
	showTable();
	parent.body.body_browse.futureShowGtypeTable();
}

try{
	showGtype = top.gtypeShowLoveI;
	var xx=showGtype.length;
}catch(E){
	initDate();
	showGtype = top.gtypeShowLoveI;
}
//top.swShowLoveI=false;
//window.onscroll =chkscrollShowLoveI;
function initDate(){
	
	top.gtypeShowLoveI =new Array("FTRE","FT","FU","BKRE","BK","BU","BSRE","BS","BSFU","TNRE","TN","TU","VBRE","VB","VU","OPRE","OP","OM");
	top.ShowLoveIarray = new Array();
	top.ShowLoveIOKarray = new Array();
	for (var i=0 ; i < top.gtypeShowLoveI.length ; i++){
		top.ShowLoveIarray[top.gtypeShowLoveI[i]]= new Array();
		top.ShowLoveIOKarray[top.gtypeShowLoveI[i]]= new Array();
	}
}
/*
function StatisticsGty(today,gtype){
	var array =new Array(0,0);
	var tmp =today.split("-");
	var newtoday =tmp[1]+"-"+tmp[2];
	var tmpgday = new Array(0,0);
	var bf = false;
	for (var i=0 ; i < top.ShowLoveIarray[gtype].length ; i++){
		tmpday = top.ShowLoveIarray[gtype][i][1].split("<br>")[0];
		tmpgday = tmpday.split("-");
		if(++tmpgday[0] < tmp[1]){ 
			bf = true;
		}else{
			bf = false;
		}
		if(bf){
			array[1]++;
		}else{
			if(newtoday >= tmpday ){
				array[0]++;	//單式	
			}else if(newtoday < tmpday){
				array[1]++;	//早餐
			}
		}
	}
	return array;
}
*/
/*function hrefs(){
	window.open("./getVworld.php?langx="+top.langx+"&uid="+top.uid,"Vworld","width=780,height=580,top=0,left=0,status=no,toolbar=no,scrollbars=yes,resizable=no,personalbar=no");
}*/
//更新信用額度max
function reloadCrditFunction(){
		reloadPHP1.location.href='reloadCredit.php?uid='+top.uid+'&langx='+top.langx;
	}
function reloadCredit(cash){
	var tmp=cash.split(" ");
	top.mcurrency=tmp[0];
	document.getElementById("credit").innerHTML=cash;
}

/*function openOther(url){
	window.open(url);
}*/

// 打开在线客服
function openOnlineServer() {
	var url = 'http://kf1.learnsaas.com/chat/chatClient/chatbox.jsp?companyID=190220&jid=9896461100&configID=40908&lan=zh&chatType=1&accept=1&enterurl=http%3A%2F%2Fprofile%2Elive800%2Ecom%2Fprofile%2Fpreview%2EjspcompanyID=190220&jid=9896461100&configID=40908&lan=zh&chatType=1&accept=1&enterurl=http%3A%2F%2Fprofile%2Elive800%2Ecom%2Fprofile%2Fpreview%2Ejsp' ;
    window.open(url,'_blank');
}

// 头部公用菜单
function setHeaderAction(ip,id) {
	var str ='<ul>\n' +
        '\t\t  <li class="lang_top"><a href="#">简体<!--[if IE 7]><!--></a><!--<![endif]-->\n' +
        '<ul class="pd">\n' +
        ' <li class="cn" onClick="OnMouseOverEvent();"><a href="javascript:void(0);" onClick="changeLangx(\'zh-cn\')">简体</a></li>\n' +
        '<li class="tw" onClick="OnMouseOverEvent();"><a href="javascript:void(0);" onClick="changeLangx(\'zh-cn\')">繁體</a></li>\n' +
        '\t\t\t\t\t<li class="us" onClick="OnMouseOverEvent();"><a href="javascript:void(0);" onClick="changeLangx(\'en-cn\')">English</a></li>\n' +
        '\n' +
        ' </ul>\n' +
        '</li>\n' +
        '<li class="mail" onClick="OnMouseOverEvent();"><a href="#" id="chg_pwd" onClick="Go_Chg_pass();">更改密碼</a></li>\n' +
        '\t\t  <li class="qa" onClick="OnMouseOverEvent();"><a href="javascript:;" class="my_bet_online" >帮助</a>\n' +
		'<ul class="pd">\n' +
        ' <li class="qa_on"><a href="#">帮助</a></li>\n' +
        '<li class="msg"><a href="#" onclick="parent.mem_order.showMoreMsg();">公告栏</a></li>\n' +
        '<li class="roul"><a href="#" onclick="window.open(\'/tpl/QA_sport.html\',\'QA\',\'location=no,status=no,width=800,height=428,toolbar=no,top=0,left=0,scrollbars=yes,resizable=yes,personalbar=yes\');">体育规则</a></li>\n' +
        '<li class="wap"><a href="#" onclick="window.open(\'/tpl/downloadapp.php\',\'WAP\',\'location=no,status=no,width=1000,height=530,toolbar=no,top=0,left=0,scrollbars=no,resizable=no,personalbar=yes\');">Wap指南</a></li>\n' +
        '<li class="odd"><a href="#" onclick="window.open(\'/tpl/QA_way.html\',\'QA\',\'location=no,status=no,width=800,height=428,toolbar=no,top=0,left=0,scrollbars=yes,resizable=yes,personalbar=yes\');">赔率计算列表</a></li>\n' +
        ' \t </ul>'+
        '</li>\n' +
        '<li class="home" onMouseOver="OnMouseOutEvent()"><a href="'+ip+'/app/member/logout.php?uid='+id+'&langx=zh-cn" target="_top">退出</a></li>\n' +
        '\t  </ul>' ;

	var sel = document.getElementById("top_back") ;
	sel.innerHTML =str ;
}



// 导航栏添加公用 客服中心，赛果，app 下载 ,type 当前游戏类型，如足球FT，篮球BS
function addPublicList(uip,type,uid) {
	var str = '<li class="result"><a class="type_out" href="../../tpl/downloadapp.php" target="body" onclick="chg_button_bg(\''+type+'\',\'today\');chg_type_class(\'result_class\');">APP下载</a></li>\n' +
        '<li class="result"><a id="result_class" class="type_out" href="'+uip+'/app/member/result/result.php?game_type='+type+'&uid='+uid+'&langx=zh-cn" onClick="chg_button_bg(\''+type+'\',\'today\');chg_type_class(\'result_class\');" target="body">赛果</a></li>\n' +
        '<li class="result"><a  class="type_out" href="../../tpl/onlineserver.php" target="body"><font color="#FF0000">客服中心</font></a></li>' ;

	$('#type').find('ul').append(str) ;
}


