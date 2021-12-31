var DataFT = new Array();
var objAry = new Object();
objAry["Minute"] = new Array("HGM","GM","AGM","BGM","CGM","DGM","EGM","FGM");
objAry["Manual"] = new Array("BH","ARG","BRG","CRG","DRG","ERG","FRG","GRG","HRG","IRG","JRG","FG","F2G","F3G","T1G","T3G","TK","PA","RCD","MW","MQ","OG","OT");
objAry["SP"] = new Array("PGF","PGL","OSF","OSL","STF","STL","CNF","CNL","CDF","CDL","RCF","RCL","YCF","YCL","GAF","GAL");
objAry["SFS"] = new Array("RESULT_F","RESULT_L","RESULT_A");
objAry["Corner"] = new Array("RNC1","RNC2","RNC3","RNC4","RNC5","RNC6","RNC7","RNC8","RNC9","RNCA","RNCB","RNCC","RNCD","RNCE","RNCF","RNCG","RNCH","RNCI","RNCJ","RNCK","RNCL","RNCM","RNCN","RNCO","RNCP","RNCQ","RNCR","RNCS","RNCT","RNCU");
objAry["Booking"] = new Array("RNBA","RNBB","RNBC","RNBD","RNBE","RNBF","RNBG","RNBH","RNBI","RNBJ","RNBK","RNBL","RNBM","RNBN","RNBO");
objAry["Penalty"] = new Array("RSHA","RSHB","RSHC","RSHD","RSHE","RSHF","RSHG","RSHH","RSHI","RSHJ","RSHK","RSHL","RSHM","RSHN","RSHO");

var showAry = new Array("Minute","Manual","Corner","Booking","Penalty");
var dataExist = {"Minute":"N","Manual":"N","Corner":"N","Booking":"N","Penalty":"N"};
//var showAry = new Object();
//showAry["Minute"] = "";
//showAry["Manual"] = "";

function Loaded(){

    DataFT = lib_parseArray(heads,gdata);

    document.getElementById("tean_name_H").innerHTML = DataFT["team_h"];
    document.getElementById("tean_name_C").innerHTML = DataFT["team_c"];
    document.getElementById("tname_C").innerHTML = DataFT["team_h"];
    document.getElementById("tname_H").innerHTML = DataFT["team_c"];
    document.getElementById("leagues_name").innerHTML = DataFT["league"];
    document.getElementById("game_time").innerHTML = DataFT["date"]+" "+DataFT["time"];

    showreLoad();
    //fix_window();
    document.getElementById('div_result_data').style.display="";
}

//============================= init start =============================
function initMinuteObj(dataArr){
    var hasShow = false;

    for(var i=0; i<dataArr.length; i++){
        var thisTRObj = document.getElementById("tr_"+dataArr[i]);
        thisTRObj.style.display = "";

        var showResult = DataFT[dataArr[i]+"_result"];
        var openResult = DataFT[dataArr[i]+"_type"];
        var showScoreH = DataFT[dataArr[i]+"_h"];
        var showScoreC = DataFT[dataArr[i]+"_c"];

        //if(i>=2 && WtypeOpen["AR"]=="Y" ){
        //	thisTRObj.style.display = "none";
        //}else
        if( openResult==undefined || openResult == "DL" || (showScoreH*1 == -11 || showScoreC*1 == -11) ){
            thisTRObj.style.display = "none";
        }else{
            hasShow = true;

            if(showResult != ""){
                showScoreH = showResult;
                showScoreC = "";
                showResult = "";
                document.getElementById("score_h_"+dataArr[i]).colSpan = "2";
                document.getElementById("score_c_"+dataArr[i]).style.display="none";

            }

            document.getElementById("result_"+dataArr[i]).innerHTML = showResult;
            document.getElementById("score_h_"+dataArr[i]).innerHTML = showScoreH;
            document.getElementById("score_c_"+dataArr[i]).innerHTML = showScoreC;
        }
        tmpClassH = "";
        tmpClassC = "";
        if(dataArr[i] == "GM" && showScoreH >=0 && showScoreC >= 0){
            if(showScoreH > showScoreC) tmpClassH = "acc_cont_bold";
            if(showScoreH < showScoreC) tmpClassC = "acc_cont_bold";
            document.getElementById("score_h_"+dataArr[i]).className = tmpClassH;
            document.getElementById("score_c_"+dataArr[i]).className = tmpClassC;
        }
    }

    return hasShow;

}

function initManualObj(dataArr){
    var hasShow = false;

    for(var i=0; i<dataArr.length; i++){
        var thisTRObj = document.getElementById("tr_"+dataArr[i]);
        thisTRObj.style.display = "";

        var showResult = DataFT[dataArr[i]+"_result"];
        var openResult = DataFT[dataArr[i]+"_type"];
        var finalResult = openResult;
        var nowSubWtype = dataArr[i];

        if(openResult==undefined || openResult == "DL"){ //|| WtypeOpen[nowSubWtype]=="Y"){
            thisTRObj.style.display = "none";
        }else{
            hasShow = true;

            if(showResult != ""){
                document.getElementById("result_type"+dataArr[i]).innerHTML = showResult;
            }else{
                var newOpenResult = openResult;

                // 字串轉換
                if(dataArr[i] == "FG" || dataArr[i] == "T3G" || dataArr[i] == "T1G"){
                    newOpenResult = top.str_result[dataArr[i]+"_"+newOpenResult];

                }if(dataArr[i] == "MW" || dataArr[i] == "MQ" ){
                    var teamSide = openResult.substring(0,1);
                    newOpenResult = (teamSide=="H") ? DataFT["team_h"] : DataFT["team_c"];
                    newOpenResult+= top.str_result["MQ_"+openResult];

                }else if(dataArr[i]=="PA" || dataArr[i]=="RCD" || dataArr[i]=="OG" || dataArr[i]=="OT" ){
                    newOpenResult = top.str_result[newOpenResult];

                }else{
                    if(newOpenResult=="H" || newOpenResult=="Home"){
                        newOpenResult = DataFT["team_h"];
                    }else if(newOpenResult=="C" || newOpenResult=="Away"){
                        newOpenResult = DataFT["team_c"];
                    }else if(newOpenResult=="N" || newOpenResult=="No"){
                        newOpenResult = top.str_result["N"];
                    }

                }
                document.getElementById("result_type"+dataArr[i]).innerHTML = newOpenResult;
            }

        }
    }

    return hasShow;
}

function initSPObj(dataArr){
    var hasShow = false;

    for(var i=0; i<dataArr.length; i++){
        var thisTRObj = document.getElementById("tr_"+dataArr[i]);
        thisTRObj.style.display = "";

        var showResult = DataFT[dataArr[i]+"_result"];
        var openResult = DataFT[dataArr[i]+"_type"];
        var finalResult = openResult;

        var nowSubWtype = dataArr[i];
        if(openResult==undefined || openResult == "DL" ){ //|| WtypeOpen[nowSubWtype] == "Y" ){
            thisTRObj.style.display = "none";
        }else{
            hasShow = true;

            if(showResult != ""){
                document.getElementById("result_type"+dataArr[i]).innerHTML = showResult;
            }else{
                var newOpenResult = openResult;

                if(newOpenResult=="H" || newOpenResult=="Home"){
                    newOpenResult = DataFT["team_h"];
                }else if(newOpenResult=="C" || newOpenResult=="Away"){
                    newOpenResult = DataFT["team_c"];
                }else if(newOpenResult=="N" || newOpenResult=="No"){
                    newOpenResult = top.str_result["N"];
                }else if(newOpenResult=="Both"){
                    newOpenResult = top.str_result[newOpenResult];
                }

                document.getElementById("result_type"+dataArr[i]).innerHTML = newOpenResult;
            }
        }
    }

    return hasShow;
}

function initNextXObj(dataArr){
    var hasShow = false;
    var swType = dataArr[0];
    var playType = dataArr[0].substring(0,3);

    for(var i=0; i<dataArr.length; i++){
        var thisTRObj = document.getElementById("tr_"+dataArr[i]);
        thisTRObj.style.display = "";

        var showResult = DataFT[dataArr[i]+"_result"];
        var openResult = DataFT[dataArr[i]+"_type"];

        if(openResult==undefined || openResult == "DL" ){ //|| WtypeOpen[swType] == "Y" ){
            thisTRObj.style.display = "none";
        }else{
            hasShow = true;

            var newOpenResult = openResult;

            if(showResult != ""){
                newOpenResult = showResult;
            }else if(newOpenResult=="H" ){
                newOpenResult = DataFT["team_h"];
            }else if(newOpenResult=="C" ){
                newOpenResult = DataFT["team_c"];
            }else if(newOpenResult=="P" ){
                newOpenResult = top.str_result[playType+"_"+newOpenResult];
            }

            document.getElementById("result_type"+dataArr[i]).innerHTML = newOpenResult;

        }
    }

    return hasShow;
}

function initPenaltyObj(dataArr){
    var hasShow = false;
    var swType = dataArr[0];
    var playType = dataArr[0].substring(0,2);
    var sideArr = ["H","C"];

    for(var i=0; i<dataArr.length; i++){
        var thisTRObj = document.getElementById("tr_"+dataArr[i]);
        thisTRObj.style.display = "";

        var nextType = dataArr[i].substring(3,4);
        var sideH = playType+"H"+nextType;
        var sideC = playType+"C"+nextType;

        var showResult = {"H":DataFT[sideH+"_result"],"C":DataFT[sideC+"_result"]};
        var openResult = {"H":DataFT[sideH+"_type"],"C":DataFT[sideC+"_type"]};

        if( openResult["H"]==undefined || openResult["C"]==undefined || openResult["H"] == "DL" || openResult["C"] == "DL" ){//|| WtypeOpen[swType] == "Y"){
            thisTRObj.style.display = "none";
        }else{
            hasShow = true;

            for(var ss=0;ss<sideArr.length;ss++){
                var newOpenResult = openResult[sideArr[ss]];

                if(showResult[sideArr[ss]] != ""){
                    newOpenResult = showResult[sideArr[ss]];
                }else{
                    newOpenResult = top.str_result[playType+"_"+newOpenResult];
                }

                document.getElementById("result_type"+playType+sideArr[ss]+nextType).innerHTML = newOpenResult;
            }
        }
    }

    return hasShow;
}

function initSFSObj(dataArr){
    var hasShow = false;

    for(var i=0; i<dataArr.length; i++){
        var thisTRObj = document.getElementById("tr_"+dataArr[i]);
        thisTRObj.style.display = "";

        var openResult = DataFT[dataArr[i]];
        if(openResult == "") thisTRObj.style.display = "none";
        else				 hasShow = true;

        document.getElementById(dataArr[i]).innerHTML = openResult;
    }

    return hasShow;
}

function initPlayShow(leg){
    var check;

    if(leg == "Manual"){
        var tmp1 = initManualObj(objAry["Manual"]);
        var tmp2 = initSPObj(objAry["SP"]);
        var tmp3 = initSFSObj(objAry["SFS"]);

        check = (tmp1 || tmp2 || tmp3);

    }else if(leg == "Minute"){
        check = initMinuteObj(objAry["Minute"]);

    }else if(leg == "Penalty"){
        check = initPenaltyObj(objAry["Penalty"]);

    }else{
        check = initNextXObj(objAry[leg]);
    }

    dataExist[leg] = check;
}

//============================= init end =============================

function closeIframe(){
    parent.document.body.style.overflowY="";
    document.body.innerHTML = "";
    parent.document.getElementById('result_new_Data').style.display = "none";
    top.showFTResultObj = new Object();

}

//將資料轉成物件
function lib_parseArray(gameHead,gameData){
    var gameObj = new Array();
    for (var i=0; i<gameHead.length; i++){
        gameObj[gameHead[i]] = gameData[i];
    }
    return gameObj;
}


function fix_window(){
    var iframe = parent.document.getElementById('result_new_Data');

    var child = document.body.children;
    var Width=0,Height=0;
    for(i=0;i<child.length;i++){

        if(child[i].nodeName =="TABLE"){
            Width += child[i].offsetWidth;
            Height += child[i].offsetHeight;
        }
    }

    iframe.width = Width;
    iframe.height = Height;
}

function showTR(leg){
    var openTR = top.showFTResultObj[leg];
    top.showFTResultObj[leg] = (openTR=="none")? "" : "none";

    showGame(leg,top.showFTResultObj[leg]);
}

function showGame(leg,openTR,isfirst){

    if (openTR == "none"){
        if(isfirst) initPlayShow(leg);

        var dataArr = objAry[leg];
        if(leg == "Manual") dataArr = dataArr.concat(objAry["SP"]).concat(objAry["SFS"]);
        for(var i=0; i<dataArr.length; i++){
            document.getElementById("tr_"+dataArr[i]).style.display = "none";
        }

    }else{
        initPlayShow(leg);
    }
}

function showreLoad(){
    if(top.showFTResultObj==undefined) top.showFTResultObj = new Object();
    //for(var key in top.showFTResultObj){
    for(var k=0;k<showAry.length;k++){
        var key = showAry[k];
        if(top.showFTResultObj[key]==undefined) top.showFTResultObj[key] = "";

        showGame(key,top.showFTResultObj[key],true);

        var tableObj = document.getElementById("table"+key);
        if(tableObj!=undefined){
            if(!dataExist[key]) tableObj.style.display="none";
        }
    }

}

function dataReload(){
    location.reload();
}

function goTop(){
    document.getElementById('div_result_data').scrollTop = "0";
}

/* Obj.e520 | Copyright 2014 ,Cvssp Inc. from this DBA Depts. | Gather you rosebuds while you may. | string
  bill TW_time:2014-11-11 17:06:39 file(112-300,301,302,303,304,224,212,321)
 */
top["str_submit"]="确定交易";
top["str_check_submit"]="接受变化 & 投注";
top["str_Quit_MailSet"]="取消注册？";
top["str_Quit_getPass"]="取消密码恢复？";
top["str_RM_getPass"]="删除密码恢复功能？";

// Ricky 2017-08-15 crm-221 所有會員端-登入頁會因為系統有誤出現的錯誤訊息 undefined 、null-要改訊息
top["str_err_login"]="现在我们的系统面临技术问题。请稍后再尝试登入。对于这样的不便我们深感抱歉，我们也正在全力的解决该问题。谢谢您的耐心等待。";
//Ricky 2017-11-28 更改錯誤訊息
top["connect_retry"] = "<p>网络不稳定，请重新更新。</p>";

/* conf_lvar_00 (160) */
top["str_input_pwd"]="请输入密码。";
top["str_input_repwd"]="请输入确认密码。";
top["str_err_pwd"]="密码确认错误, 请重新输入。";
top["str_pwd_limit"]="您输入的密码不符合要求：<br>1. 您的新密码必须由 6-15个字母和数字 (A-Z 或 0-9)组成. <br>2. 您的新密码不能和现用密码相同。";
top["str_pwd_limit2"]="您输入的密码不符合要求：<br>1. 您的新密码必须由 6-15个字母和数字 (A-Z 或 0-9)组成. <br>2. 您的新密码不能和现用密码相同。";
top["str_pwd_limit3"]="您的新密码必须由 6-15个字母和数字 (A-Z 或 0-9)组成.";
top["str_err_mail"]="请输入有效的电子邮件。";
top["str_pwd_NoChg"]="您的新密码必须和现用密码不一样。";
top["str_pwd_NowErr"]="您输入的密码不正确，请重试。";
top["str_pwd_OldErr"]="请输入现用密码。";
top["str_input_longin_id"]="请输入登录帐号。";
top["str_input_longin_id2"]="请输入 帐号 或 登录帐号。";
top["str_longin_limit1"]="您输入的登录帐号不符合要求：<br>1. 您的登入帐号必须由2个英文大小写字母(A-Z或a-z)和数字(0-9)组合, 输入限制 6-15字元.<br>2. 您的登入帐号不准许有空格.";
top["str_longin_limit2"]="您的登录帐号需使用字母加上数字!!";
top["str_refund"]="退还";
top["str_cancel"]="取消";
top["str_o"]="单";
top["str_e"]="双";
top["str_checknum"]="验证码错误,请重新输入";
top["str_irish_kiss"]="和";
top["str_draw"]="注单平局";
top["dPrivate"]="私域";
top["dPublic"]="公有";
top["grep"]="群组";
top["grepIP"]="群组IP";
top["IP_list"]="IP列表";
top["Group"]="组别";
top["choice"]="请选择";
top["account"]="请输入登录帐号。";
top["password"]="请输入密码。";
top["S_EM"]="特早";
top["alldata"]="全部";
top["date"]="所有日期";
top["webset"]="资讯网";
top["str_renew"]="更新";
top["outright"]="冠军";
top["financial"]="金融";
top["str_FT"]="足球";
top["str_BK"]="篮球";
top["str_TN"]="网球";
top["str_VB"]="排球";
top["str_BM"]="羽毛球";
top["str_TT"]="兵乓球";
top["str_BS"]="棒球";
top["str_OP"]="其他";
top["str_score"]="比分";
top["str_order_FT"]="足球";
top["str_order_BK"]="篮球 / 美式足球";
top["str_order_TN"]="网球";
top["str_order_VB"]="排球";
top["str_order_BM"]="羽毛球";
top["str_order_TT"]="兵乓球";
top["str_order_BS"]="棒球";
top["str_order_OP"]="其他";
top["str_order_SK"]="斯诺克/台球";
top["str_fs_FT"]="足球 : ";
top["str_fs_BK"]="篮球 / 美式足球 : ";
top["str_fs_TN"]="网球 : ";
top["str_fs_VB"]="排球 : ";/* No.50 */
top["str_fs_BM"]="羽毛球 : ";
top["str_fs_TT"]="兵乓球 : ";
top["str_fs_BS"]="棒球 : ";
top["str_fs_OP"]="其他体育 : ";
top["str_game_list"]="所有球类";
top["str_date_list"]="所有日期";
top["str_second"]="秒";
top["str_demo"]="样本播放";
top["str_alone"]="独立";
top["str_back"]="返回";
top["str_RB"]="滚球";
top["str_msAll"]="(全场)";
top["str_ShowMyFavorite"]="赛事收藏";
top["str_ShowAllGame"]="全部赛事";
top["str_delShowLoveI"]="删除收藏";
top["str_SortType"]="按时间排序";
top["str_SortTypeC"]="按联盟排序";
top["str_SortTypeT"]="按时间排序";
top["strOver"]="大";
top["strUnder"]="小";
top["yes"]="是";
top["no"]="不是";
top["team1"]="主";
top["team2"]="客";
top["team3"]="和局";
top["noGoal"]="无";
top["strOdd"]="单";
top["strEven"]="双";
top["message001"]="请输入下注金额。";
top["message002"]="只能输入数字!!";
top["message003"]="最低投注额是 ";
top["message004"]="本场有下注金額最高是 ";
top["message005"]=" 元限制!!";
top["message006"]="最高投注额设在";
top["message007"]="总下注金额已超过单场限额。";
top["message008"]="本场累积下注共: ";
top["message009"]="。\n\n总下注金额已超过单场限额。";
top["message010"]="下注金额不可大于信用额度。";
top["message011"]="可赢金额：";
top["message012"]="<br>确定进行下注吗?";
top["message013"]="确定进行下注吗?<br>";
top["message014"]="未输入下注金额!!!";
top["message015"]="下注金额只能输入数字。";
top["message016"]="\n\n确定进行下注吗?";
top["message017"]="串1";
top["message018"]="队联碰";
top["message019"]="您必须选择至少";
top["message020"]="个队伍,否则不能下注!!";
top["message021"]="不接受";
top["message022"]="串过关投注。";
top["message023"]="请输入欲下注金额!!";
top["message024"]="已超过某场次之过关注单限额!!";
top["message025"]="下注金额不可大于信用额度。";
top["message026"]="请选择下注队伍!!";
top["message027"]="单式投注请至单式下注页面下注!!";
top["message028"]="仅接受";/* No.100 */
top["message029"]="串投注!!";
top["message030"]="确定要进行交易吗？";
top["message031"]="请输入要搜寻的文字";
top["message032"]="找不到相符项目";
top["message033"]="你的浏览器不支援";
top["message034"]="接受变化 & 投注";
top["message035"]="单注最高可赢金额： 人民币 ";
top["message036"]="为上限";
top["message037"]="The minimum bet amount is ";
top["message038"]="The maximum bet amount is ";
top["message039"]="The market has closed.";
top["message040"]="The event is now In-Play.";
top["message041"]="Trading in this market is temporarily suspended.";
top["message042"]="The odds of one or more selections have changed.";
top["message043"]="One or more of the markets are closed.";
top["message044"]="Your bet is currently pending. Please refresh or check the ticket status in My Bets.";
top["page"]="页";
top["refreshTime"]="刷新";
top["showyear"]="年";
top["showmonth"]="月";
top["showday"]="日";
top["showtoday"]="今日";
top["showtomorrow"]="明日";
top["showfuture"]="未来";
top["Half1st"]="上半滚球";
top["Half2nd"]="下半滚球";
top["mem_logut"]="您的帐号已登出";
top["retime1H"]="上半场";
top["retime2H"]="下半场";
top["str_otb_close"]="赛事已关闭。";
top["no_oly"]="您选择的项目暂时没有赛事。请查看冠军玩法。";
top["conf_R"]="让球,大小,单双";
top["conf_RE"]="滚球让球,滚球大小,滚球单双";
top["conf_RE_BK"]="滚球让球,滚球大小,滚球单双";
top["conf_M"]="独赢,滚球独赢";
top["conf_M_BK"]="独赢,滚球独赢";
top["conf_DT"]="其他";
top["conf_RDT"]="滚球其他";
top["conf_FS"]="冠军";
top["str_more"]="更多玩法";
top["str_all_bets"]="所有玩法";
top["str_TV_RB"]="视频转播可使用";
top["str_TV_FT"]="视频转播将在滚球时提供";
top["addtoMyMarket"]='加到"我的盘口"';
top["deltoMyMarket"]='删除"我的盘口"';

top["str_BK_OT"]="加时";
top["str_midfield"]="中";
top["str_BK_Market_Main"]="主要盘口";
top["str_BK_Market_All"]="所有盘口 ";
top["str_BK_Period_View"]="赛节投注";
top["str_BK_Period_Hide"]="隐藏赛节投注 ";
top["str_TN_Market_Main"]="主要盘口";
top["str_TN_Market_All"]="所有盘口";
top["str_TN_Period_View"]="赛盘投注";
top["str_TN_Period_Hide"]="隐藏赛盘投注";
top["str_BM_Market_Main"]="主要盘口";
top["str_BM_Market_All"]="所有盘口";
top["str_BM_Period_View"]="赛局投注";
top["str_BM_Period_Hide"]="隐藏赛局投注";
top["str_TT_Market_Main"]="主要盘口";
top["str_TT_Market_All"]="所有盘口";
top["str_TT_Period_View"]="赛局投注";
top["str_TT_Period_Hide"]="隐藏赛局投注";
top["str_VB_Market_Main"]="主要盘口";
top["str_VB_Market_All"]="所有盘口";
top["str_VB_Period_View"]="赛局投注";
top["str_VB_Period_Hide"]="隐藏赛局投注";
top["TN_set_1"]="第一盘";
top["TN_set_2"]="第二盘";
top["TN_set_3"]="第三盘";
top["TN_set_4"]="第四盘";
top["TN_set_5"]="第五盘";
top["BM_set_1"]="第一局";
top["BM_set_2"]="第二局";/* No.150 */
top["BM_set_3"]="第三局";
top["BM_set_4"]="第四局";
top["BM_set_5"]="第五局";
top["VB_set_1"]="第一局";
top["VB_set_2"]="第二局";/* No.150 */
top["VB_set_3"]="第三局";
top["VB_set_4"]="第四局";
top["VB_set_5"]="第五局";
top["VB_set_6"]="第六局";
top["VB_set_7"]="第七局";
top["TT_set_1"]="第一局";
top["TT_set_2"]="第二局";/* No.150 */
top["TT_set_3"]="第三局";
top["TT_set_4"]="第四局";
top["TT_set_5"]="第五局";
top["TT_set_6"]="第六局";
top["TT_set_7"]="第七局";

top["str_ARG"]="第一个进球";
top["str_BRG"]="第二个进球";
top["str_CRG"]="第三个进球";
top["str_DRG"]="第四个进球";
top["str_ERG"]="第五个进球";
top["str_FRG"]="第六个进球";
top["str_GRG"]="第七个进球";
top["str_HRG"]="第八个进球";
top["str_IRG"]="第九个进球";
top["str_JRG"]="第十个进球";

top["TN_game_A_set_01"] = " - 第一盘 第一局";
top["TN_game_A_set_02"] = " - 第一盘 第二局";
top["TN_game_A_set_03"] = " - 第一盘 第三局";
top["TN_game_A_set_04"] = " - 第一盘 第四局";
top["TN_game_A_set_05"] = " - 第一盘 第五局";
top["TN_game_A_set_06"] = " - 第一盘 第六局";
top["TN_game_A_set_07"] = " - 第一盘 第七局";
top["TN_game_A_set_08"] = " - 第一盘 第八局";
top["TN_game_A_set_09"] = " - 第一盘 第九局";
top["TN_game_A_set_10"] = " - 第一盘 第十局";
top["TN_game_A_set_11"] = " - 第一盘 第十一局";
top["TN_game_A_set_12"] = " - 第一盘 第十二局";
top["TN_game_A_set_13"] = " - 第一盘 第十三局";

top["TN_game_B_set_01"] = " - 第二盘 第一局";
top["TN_game_B_set_02"] = " - 第二盘 第二局";
top["TN_game_B_set_03"] = " - 第二盘 第三局";
top["TN_game_B_set_04"] = " - 第二盘 第四局";
top["TN_game_B_set_05"] = " - 第二盘 第五局";
top["TN_game_B_set_06"] = " - 第二盘 第六局";
top["TN_game_B_set_07"] = " - 第二盘 第七局";
top["TN_game_B_set_08"] = " - 第二盘 第八局";
top["TN_game_B_set_09"] = " - 第二盘 第九局";
top["TN_game_B_set_10"] = " - 第二盘 第十局";
top["TN_game_B_set_11"] = " - 第二盘 第十一局";
top["TN_game_B_set_12"] = " - 第二盘 第十二局";
top["TN_game_B_set_13"] = " - 第二盘 第十三局";

top["TN_game_C_set_01"] = " - 第三盘 第一局";
top["TN_game_C_set_02"] = " - 第三盘 第二局";
top["TN_game_C_set_03"] = " - 第三盘 第三局";
top["TN_game_C_set_04"] = " - 第三盘 第四局";
top["TN_game_C_set_05"] = " - 第三盘 第五局";
top["TN_game_C_set_06"] = " - 第三盘 第六局";
top["TN_game_C_set_07"] = " - 第三盘 第七局";
top["TN_game_C_set_08"] = " - 第三盘 第八局";
top["TN_game_C_set_09"] = " - 第三盘 第九局";
top["TN_game_C_set_10"] = " - 第三盘 第十局";
top["TN_game_C_set_11"] = " - 第三盘 第十一局";
top["TN_game_C_set_12"] = " - 第三盘 第十二局";
top["TN_game_C_set_13"] = " - 第三盘 第十三局";
top["TN_game_C_set_14"] = " - 第三盘 第十四局";
top["TN_game_C_set_15"] = " - 第三盘 第十五局";
top["TN_game_C_set_16"] = " - 第三盘 第十六局";
top["TN_game_C_set_17"] = " - 第三盘 第十七局";
top["TN_game_C_set_18"] = " - 第三盘 第十八局";
top["TN_game_C_set_19"] = " - 第三盘 第十九局";
top["TN_game_C_set_20"] = " - 第三盘 第二十局";
top["TN_game_C_set_21"] = " - 第三盘 第二十一局";
top["TN_game_C_set_22"] = " - 第三盘 第二十二局";
top["TN_game_C_set_23"] = " - 第三盘 第二十三局";
top["TN_game_C_set_24"] = " - 第三盘 第二十四局";
top["TN_game_C_set_25"] = " - 第三盘 第二十五局";
top["TN_game_C_set_26"] = " - 第三盘 第二十六局";
top["TN_game_C_set_27"] = " - 第三盘 第二十七局";
top["TN_game_C_set_28"] = " - 第三盘 第二十八局";
top["TN_game_C_set_29"] = " - 第三盘 第二十九局";
top["TN_game_C_set_30"] = " - 第三盘 第三十局";
top["TN_game_C_set_31"] = " - 第三盘 第三十一局";
top["TN_game_C_set_32"] = " - 第三盘 第三十二局";
top["TN_game_C_set_33"] = " - 第三盘 第三十三局";
top["TN_game_C_set_34"] = " - 第三盘 第三十四局";
top["TN_game_C_set_35"] = " - 第三盘 第三十五局";
top["TN_game_C_set_36"] = " - 第三盘 第三十六局";
top["TN_game_C_set_37"] = " - 第三盘 第三十七局";
top["TN_game_C_set_38"] = " - 第三盘 第三十八局";
top["TN_game_C_set_39"] = " - 第三盘 第三十九局";
top["TN_game_C_set_40"] = " - 第三盘 第四十局";
top["TN_game_C_set_41"] = " - 第三盘 第四十一局";
top["TN_game_C_set_42"] = " - 第三盘 第四十二局";
top["TN_game_C_set_43"] = " - 第三盘 第四十三局";
top["TN_game_C_set_44"] = " - 第三盘 第四十四局";
top["TN_game_C_set_45"] = " - 第三盘 第四十五局";
top["TN_game_C_set_46"] = " - 第三盘 第四十六局";
top["TN_game_C_set_47"] = " - 第三盘 第四十七局";
top["TN_game_C_set_48"] = " - 第三盘 第四十八局";
top["TN_game_C_set_49"] = " - 第三盘 第四十九局";
top["TN_game_C_set_50"] = " - 第三盘 第五十局";

top["TN_game_D_set_01"] = " - 第四盘 第一局";
top["TN_game_D_set_02"] = " - 第四盘 第二局";
top["TN_game_D_set_03"] = " - 第四盘 第三局";
top["TN_game_D_set_04"] = " - 第四盘 第四局";
top["TN_game_D_set_05"] = " - 第四盘 第五局";
top["TN_game_D_set_06"] = " - 第四盘 第六局";
top["TN_game_D_set_07"] = " - 第四盘 第七局";
top["TN_game_D_set_08"] = " - 第四盘 第八局";
top["TN_game_D_set_09"] = " - 第四盘 第九局";
top["TN_game_D_set_10"] = " - 第四盘 第十局";
top["TN_game_D_set_11"] = " - 第四盘 第十一局";
top["TN_game_D_set_12"] = " - 第四盘 第十二局";
top["TN_game_D_set_13"] = " - 第四盘 第十三局";

top["TN_game_E_set_01"] = " - 第五盘 第一局";
top["TN_game_E_set_02"] = " - 第五盘 第二局";
top["TN_game_E_set_03"] = " - 第五盘 第三局";
top["TN_game_E_set_04"] = " - 第五盘 第四局";
top["TN_game_E_set_05"] = " - 第五盘 第五局";
top["TN_game_E_set_06"] = " - 第五盘 第六局";
top["TN_game_E_set_07"] = " - 第五盘 第七局";
top["TN_game_E_set_08"] = " - 第五盘 第八局";
top["TN_game_E_set_09"] = " - 第五盘 第九局";
top["TN_game_E_set_10"] = " - 第五盘 第十局";
top["TN_game_E_set_11"] = " - 第五盘 第十一局";
top["TN_game_E_set_12"] = " - 第五盘 第十二局";
top["TN_game_E_set_13"] = " - 第五盘 第十三局";
top["TN_game_E_set_14"] = " - 第五盘 第十四局";
top["TN_game_E_set_15"] = " - 第五盘 第十五局";
top["TN_game_E_set_16"] = " - 第五盘 第十六局";
top["TN_game_E_set_17"] = " - 第五盘 第十七局";
top["TN_game_E_set_18"] = " - 第五盘 第十八局";
top["TN_game_E_set_19"] = " - 第五盘 第十九局";
top["TN_game_E_set_20"] = " - 第五盘 第二十局";
top["TN_game_E_set_21"] = " - 第五盘 第二十一局";
top["TN_game_E_set_22"] = " - 第五盘 第二十二局";
top["TN_game_E_set_23"] = " - 第五盘 第二十三局";
top["TN_game_E_set_24"] = " - 第五盘 第二十四局";
top["TN_game_E_set_25"] = " - 第五盘 第二十五局";
top["TN_game_E_set_26"] = " - 第五盘 第二十六局";
top["TN_game_E_set_27"] = " - 第五盘 第二十七局";
top["TN_game_E_set_28"] = " - 第五盘 第二十八局";
top["TN_game_E_set_29"] = " - 第五盘 第二十九局";
top["TN_game_E_set_30"] = " - 第五盘 第三十局";
top["TN_game_E_set_31"] = " - 第五盘 第三十一局";
top["TN_game_E_set_32"] = " - 第五盘 第三十二局";
top["TN_game_E_set_33"] = " - 第五盘 第三十三局";
top["TN_game_E_set_34"] = " - 第五盘 第三十四局";
top["TN_game_E_set_35"] = " - 第五盘 第三十五局";
top["TN_game_E_set_36"] = " - 第五盘 第三十六局";
top["TN_game_E_set_37"] = " - 第五盘 第三十七局";
top["TN_game_E_set_38"] = " - 第五盘 第三十八局";
top["TN_game_E_set_39"] = " - 第五盘 第三十九局";
top["TN_game_E_set_40"] = " - 第五盘 第四十局";
top["TN_game_E_set_41"] = " - 第五盘 第四十一局";
top["TN_game_E_set_42"] = " - 第五盘 第四十二局";
top["TN_game_E_set_43"] = " - 第五盘 第四十三局";
top["TN_game_E_set_44"] = " - 第五盘 第四十四局";
top["TN_game_E_set_45"] = " - 第五盘 第四十五局";
top["TN_game_E_set_46"] = " - 第五盘 第四十六局";
top["TN_game_E_set_47"] = " - 第五盘 第四十七局";
top["TN_game_E_set_48"] = " - 第五盘 第四十八局";
top["TN_game_E_set_49"] = " - 第五盘 第四十九局";
top["TN_game_E_set_50"] = " - 第五盘 第五十局";


top["str_VB_Game"]="总局数 : ";
top["str_VB_allPoint"]="球员总分 : ";
top["str_VB_point"]="分数 : ";
top["str_VB_more_r0"]="让局";
top["str_VB_more_r"]="让分";
top["str_VB_more_re0"]="让局";
top["str_VB_more_re"]="让分";/* No.160 */
top["point"]=".";//點

top["TN_Best3"]="三盘两胜";//best of xx
top["TN_Best5"]="五盘三胜";
top["TN_Best7"]="七盘四胜";
top["PAGE"]="页";
top["PAGE_NUM"]="页数";
top["OVH"]="其他比分";

top["str_RSHA"]="第一个点球";
top["str_RSHB"]="第二个点球";
top["str_RSHC"]="第三个点球";
top["str_RSHD"]="第四个点球";
top["str_RSHE"]="第五个点球";
top["str_RSHF"]="第六个点球";
top["str_RSHG"]="第七个点球";
top["str_RSHH"]="第八个点球";
top["str_RSHI"]="第九个点球";
top["str_RSHJ"]="第十个点球";
top["str_RSHK"]="第十一个点球";
top["str_RSHL"]="第十二个点球";
top["str_RSHM"]="第十三个点球";
top["str_RSHN"]="第十四个点球";
top["str_RSHO"]="第十五个点球";
top["str_RNC1"]="第一个角球";
top["str_RNC2"]="第二个角球";
top["str_RNC3"]="第三个角球";
top["str_RNC4"]="第四个角球";
top["str_RNC5"]="第五个角球";
top["str_RNC6"]="第六个角球";
top["str_RNC7"]="第七个角球";
top["str_RNC8"]="第八个角球";
top["str_RNC9"]="第九个角球";
top["str_RNCA"]="第十个角球";
top["str_RNCB"]="第十一个角球";
top["str_RNCC"]="第十二个角球";
top["str_RNCD"]="第十三个角球";
top["str_RNCE"]="第十四个角球";
top["str_RNCF"]="第十五个角球";
top["str_RNCG"]="第十六个角球";
top["str_RNCH"]="第十七个角球";
top["str_RNCI"]="第十八个角球";
top["str_RNCJ"]="第十九个角球";
top["str_RNCK"]="第二十个角球";
top["str_RNCL"]="第二一个角球";
top["str_RNCM"]="第二二个角球";
top["str_RNCN"]="第二三个角球";
top["str_RNCO"]="第二四个角球";
top["str_RNCP"]="第二五个角球";
top["str_RNCQ"]="第二六个角球";
top["str_RNCR"]="第二七个角球";
top["str_RNCS"]="第二八个角球";
top["str_RNCT"]="第二九个角球";
top["str_RNCU"]="第三十个角球";
top["str_RNBA"]="第一张罚牌";
top["str_RNBB"]="第二张罚牌";
top["str_RNBC"]="第三张罚牌";
top["str_RNBD"]="第四张罚牌";
top["str_RNBE"]="第五张罚牌";
top["str_RNBF"]="第六张罚牌";
top["str_RNBG"]="第七张罚牌";
top["str_RNBH"]="第八张罚牌";
top["str_RNBI"]="第九张罚牌";
top["str_RNBJ"]="第十张罚牌";
top["str_RNBK"]="第十一张罚牌";
top["str_RNBL"]="第十二张罚牌";
top["str_RNBM"]="第十三张罚牌";
top["str_RNBN"]="第十四张罚牌";
top["str_RNBO"]="第十五张罚牌";
top["str_AO"] = "大 1.5";
top["str_BO"] = "大 2.5";
top["str_CO"] = "大 3.5";
top["str_DO"] = "大 4.5";
top["str_AU"] = "小 1.5";
top["str_BU"] = "小 2.5";
top["str_CU"] = "小 3.5";
top["str_DU"] = "小 4.5";
top["goAllbets"]="点击 '确定' 以让所有玩法页成为活跃窗口.";
top["goodmybets"]="点击 '确定' 以让我的注单页成为活跃窗口.";
top["ET_str"]="加时在0-0开始";
top["PK_istr"]="大小盘口会根据点球中的前十个罚球";
top["PK_head"]="下一个点球";

// 2017-05-05 PMO-51 危險球狀態字樣改變+十秒自動更新注單狀況
top["str_bet_sucess"] = "下注成功";
top["str_bet_reject"] = "您的投注已失敗";
top["str_bet_pending"] = "注单待确认。请在我的注单处查看状态";

// 2017-12-12 82.新會員端-滾球危險球的待確認的單子下“確認”扭請改為中文：繼續交易，英文Continue（CRM-248)
top["chkBet_continue"] = "继续交易";
top["chkBet_confirm"] = "确认";

//RT Name
top.str_RT=["0 - 1","2 - 3","4 - 6","7或以上"];

/* conf_lvar_01  (3) */
top.str_HCN=["主","客","无"];

/* conf_lvar_02  (24) */
top.strRtypeSP={"PGF":"最先进球","OSF":"最先越位","STF":"最先替补","CNF":"最先角球","CDF":"第一张罚牌","RCF":"最先任意球","YCF":"最先界外球","GAF":"最先球门球","PGL":"最后进球","OSL":"最后越位","STL":"最后替补","CNL":"最后角球","CDL":"最后一张罚牌","RCL":"最后任意球","YCL":"最后界外球","GAL":"最后球门球","PG":"最先/最后进球球队","OS":"最先/最后越位球队","ST":"最先/最后替补球员球队","CN":"最先/最后角球","CD":"第一张/最后一张罚牌","RC":"最先/最后任意球","YC":"最先界外球/最后界外球","GA":"最先/最后球门球"};

/* conf_lvar_03  (3) */
top.statu={"HT":"半场","1H":"上半场","2H":"下半场"};

/* conf_lvar_04  (7) */
top.str_BK_MS=["","上半场","下半场","第一节","第二节","第三节","第四节"];

/* conf_session  (41) */
top._session={"FTi0":"全场","FTi1":"上半","BKi0":"全场","BKi8":"上半","BKi9":"下半","BKi3":"第1节","BKi4":"第2节","BKi5":"第3节","BKi6":"第4节","BSi0":"全场","FSi0":"全场","OPi0":"全场","TNi0":"全场","TNi1":"第一盘","TNi2":"第二盘","TNi3":"第三盘","TNi4":"第四盘","TNi5":"第五盘","TNi6":"让局","TNi7":"主队局数","TNi8":"客队局数","VBi0":"全场","VBi1":"局数","VBi2":"分数","VBi3":"第一局","VBi4":"第二局","VBi5":"第三局","VBi6":"第四局","VBi7":"第五局","VBi8":"第六局","VBi9":"第七局","BMi0":"全场","BMi1":"分数","BMi2":"第一局","BMi3":"第二局","BMi4":"第三局","BMi5":"第四局","BMi6":"第五局","BMi7":"第六局","BMi8":"第七局","TTi0":"全场"};

/* conf_gtype  (9) */
top._gtype={"FT":"足球","BK":"篮球","BS":"棒球","FS":"冠军","OP":"其他","TN":"网球","VB":"排球","BM":"羽毛球","TT":"乒乓球"};

//Ricky 2018-02-05 PJB-176 CRM-229世界盃新玩法 新增最後結束回合賽果語系
/* conf_lvar_21  (19) */
top.str_result={"No":"无","Y":"是","N":"否","FG_S":"射门","FG_H":"头球","FG_N":"无进球","FG_P":"点球","FG_F":"任意球","FG_O":"乌龙球","T3G_1":"26分钟以下","T3G_2":"27分钟+","T3G_N":"无进球","T1G_N":"无进球","T1G_1":"0 - 14:59","T1G_2":"15 - 29:59","T1G_3":"30 – 半场","T1G_4":"45 – 59:59","T1G_5":"60 – 74:59","T1G_6":"75 – 全场","Both":"双方","MQ_H":" - 90分钟","MQ_C":" - 90分钟","MQ_HOT":" - 加时赛","MQ_COT":" - 加时赛","MQ_HPK":" - 点球","MQ_CPK":" - 点球","RNB_P":"没有罚牌","RNC_P":"没有角球","RS_Y":"进球","RS_N":"无进球","RS_P":"没有点球","FG_":"未定","T3G_":"未定","T1G_":"未定","":"未定","1":"第三轮","2":"第四轮","3":"第五轮","OV":"第六轮或之后"};

/* conf_date_21  (20) */
top._date={"m1":"01月","m2":"02月","m3":"03月","m4":"04月","m5":"05月","m6":"06月","m7":"07月","m8":"08月","m9":"09月","m10":"10月","m11":"11月","m12":"12月","Mon":"星期一","Tue":"星期二","Wed":"星期三","Thu":"星期四","Fri":"星期五","Sat":"星期六","Sun":"星期日"};

/*conf_top._session_sk(6)*/
top._session_sk={"A":" - 1-5局","B":" - 6-8局","C":" - 10-14局","D":" - 15-17局","E":" - 19-23局","F":" - 24-26局"};

/*conf_top._session_sk_rf(35)*/
top._session_sk_rf={"01":" - 第1局","02":" - 第2局","03":" - 第3局","04":" - 第4局","05":" - 第5局","06":" - 第6局","07":" - 第7局","08":" - 第8局","09":" - 第9局","10":" - 第10局","11":" - 第11局","12":" - 第12局","13":" - 第13局","14":" - 第14局","15":" - 第15局","16":" - 第16局","17":" - 第17局","18":" - 第18局","19":" - 第19局","20":" - 第20局","21":" - 第21局","22":" - 第22局","23":" - 第23局","24":" - 第24局","25":" - 第25局","26":" - 第26局","27":" - 第27局","28":" - 第28局","29":" - 第29局","30":" - 第30局","31":" - 第31局","32":" - 第32局","33":" - 第33局","34":" - 第34局","35":" - 第35局"};

/*conf_top._best_sk(35)*/
top._best_sk={"1":"一盘制","2":"两盘制","3":"三盘两胜","4":"四盘制","5":"五盘三胜","6":"六盘制","7":"七盘四胜","8":"八盘制","9":"九盘五胜","10":"十盘制","11":"十一盘六胜","12":"十二盘制","13":"十三盘制","14":"十四盘制","15":"十五盘制","16":" 十六盘制","17":"十七盘九胜","18":"十八盘制","19":"十九盘十胜","20":"二十盘制","21":"二十一盘制","22":"二十二盘制","23":"二十三盘制","24":"二十四盘制","25":"二十五盘十三胜","26":"二十六盘制","27":"二十七盘制","28":"二十八盘制","29":"二十九盘制","30":"三十盘制","31":"三十一盘制","32":"三十二盘制","33":"三十三盘十七胜","34":"三十四盘制","35":"三十五盘十八胜"};

/*conf_top._best_sk(35)*/
//Ricky 2017-10-24 PJB-184 新會員端tv盤面顯示
//top._play_sk={"1":"Play All ","2":"Play All ","3":"Play All ","4":"Play All ","5":"Play All ","6":"Play All ","7":"Play All ","8":"Play All ","9":"Play All ","10":"Play All ","11":"Play All ","12":"Play All ","13":"Play All ","14":"Play All ","15":"Play All ","16":"Play All ","17":"Play All ","18":"Play All ","19":"Play All ","20":"Play All ","21":"Play All ","22":"Play All ","23":"Play All ","24":"Play All ","25":"Play All ","26":"Play All ","27":"Play All ","28":"Play All ","29":"Play All ","30":"Play All ","31":"Play All ","32":"Play All ","33":"Play All ","34":"Play All ","35":"Play All "};
//top._play_sk={"1":"打满","2":"打满","3":"打满","4":"打满","5":"打满","6":"打满","7":"打满","8":"打满","9":"打满","10":"打满","11":"打满","12":"打满","13":"打满","14":"打满","15":"打满","16":"打满","17":"打满","18":"打满","19":"打满","20":"打满","21":"打满","22":"打满","23":"打满","24":"打满","25":"打满","26":"打满","27":"打满","28":"打满","29":"打满","30":"打满","31":"打满","32":"打满","33":"打满","34":"打满","35":"打满"};
top._play_sk={"1":"打满一局","2":"打满二局","3":"打满三局","4":"打满四局","5":"打满五局","6":"打满六局","7":"打满七局","8":"打满八局","9":"打满九局","10":"打满十局","11":"打满十一局","12":"打满十二局","13":"打满十三局","14":"打满十四局","15":"打满十五局","16":"打满十六局","17":"打满十七局","18":"打满十八局","19":"打满十九局","20":"打满二十局","21":"打满二十一局","22":"打满二十二局","23":"打满二十三局","24":"打满二十四局","25":"打满二十五局","26":"打满二十六局","27":"打满二十七局","28":"打满二十八局","29":"打满二十九局","30":"打满三十局","31":"打满三十一局","32":"打满三十二局","33":"打满三十三局","34":"打满三十四局","35":"打满三十五局"};
//top["game"] = "局";
var gdata = Array('3171396','2018-04-22','00:01a','奥甲','红牛萨尔斯堡','亚塔奇','60558','60557','Y','1','0','','','','H','0','0','','','不显示赛程','DL','不显示赛程','DL','取消','DL','取消','DL','0','0','','','取消','DL','取消','DL','不显示赛程','DL','1','1','','','不显示赛程','DL','0','0','','','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','1','0','','','不显示赛程','DL','取消','DL','取消','DL','3','1','','','不显示赛程','DL','1','0','','','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','取消','DL','取消','DL','不显示赛程','DL','不显示赛程','DL','','Home','','Home','不显示赛程','DL','取消','DL','取消','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','不显示赛程','DL','取消','DL','取消','DL','','1','','1','不显示赛程','DL','取消','DL','取消','DL','','','');
var heads = Array('gid','date','time','league','team_h','team_c','num_h','num_c','game_over','AGM_h','AGM_c','AGM_result','AGM_type','ARG_result','ARG_type','BGM_h','BGM_c','BGM_result','BGM_type','BH_result','BH_type','BRG_result','BRG_type','CDF_result','CDF_type','CDL_result','CDL_type','CGM_h','CGM_c','CGM_result','CGM_type','CNF_result','CNF_type','CNL_result','CNL_type','CRG_result','CRG_type','DGM_h','DGM_c','DGM_result','DGM_type','DRG_result','DRG_type','EGM_h','EGM_c','EGM_result','EGM_type','ERG_result','ERG_type','F2G_result','F2G_type','F3G_result','F3G_type','FG_result','FG_type','FGM_h','FGM_c','FGM_result','FGM_type','FRG_result','FRG_type','GAF_result','GAF_type','GAL_result','GAL_type','GM_h','GM_c','GM_result','GM_type','GRG_result','GRG_type','HGM_h','HGM_c','HGM_result','HGM_type','HRG_result','HRG_type','IRG_result','IRG_type','JRG_result','JRG_type','MQ_result','MQ_type','MW_result','MW_type','OG_result','OG_type','OSF_result','OSF_type','OSL_result','OSL_type','OT_result','OT_type','PA_result','PA_type','PGF_result','PGF_type','PGL_result','PGL_type','RCD_result','RCD_type','RCF_result','RCF_type','RCL_result','RCL_type','RNBA_result','RNBA_type','RNBB_result','RNBB_type','RNBC_result','RNBC_type','RNBD_result','RNBD_type','RNBE_result','RNBE_type','RNBF_result','RNBF_type','RNBG_result','RNBG_type','RNBH_result','RNBH_type','RNBI_result','RNBI_type','RNBJ_result','RNBJ_type','RNBK_result','RNBK_type','RNBL_result','RNBL_type','RNBM_result','RNBM_type','RNBN_result','RNBN_type','RNBO_result','RNBO_type','RNC1_result','RNC1_type','RNC2_result','RNC2_type','RNC3_result','RNC3_type','RNC4_result','RNC4_type','RNC5_result','RNC5_type','RNC6_result','RNC6_type','RNC7_result','RNC7_type','RNC8_result','RNC8_type','RNC9_result','RNC9_type','RNCA_result','RNCA_type','RNCB_result','RNCB_type','RNCC_result','RNCC_type','RNCD_result','RNCD_type','RNCE_result','RNCE_type','RNCF_result','RNCF_type','RNCG_result','RNCG_type','RNCH_result','RNCH_type','RNCI_result','RNCI_type','RNCJ_result','RNCJ_type','RNCK_result','RNCK_type','RNCL_result','RNCL_type','RNCM_result','RNCM_type','RNCN_result','RNCN_type','RNCO_result','RNCO_type','RNCP_result','RNCP_type','RNCQ_result','RNCQ_type','RNCR_result','RNCR_type','RNCS_result','RNCS_type','RNCT_result','RNCT_type','RNCU_result','RNCU_type','RSCA_result','RSCA_type','RSCB_result','RSCB_type','RSCC_result','RSCC_type','RSCD_result','RSCD_type','RSCE_result','RSCE_type','RSCF_result','RSCF_type','RSCG_result','RSCG_type','RSCH_result','RSCH_type','RSCI_result','RSCI_type','RSCJ_result','RSCJ_type','RSCK_result','RSCK_type','RSCL_result','RSCL_type','RSCM_result','RSCM_type','RSCN_result','RSCN_type','RSCO_result','RSCO_type','RSHA_result','RSHA_type','RSHB_result','RSHB_type','RSHC_result','RSHC_type','RSHD_result','RSHD_type','RSHE_result','RSHE_type','RSHF_result','RSHF_type','RSHG_result','RSHG_type','RSHH_result','RSHH_type','RSHI_result','RSHI_type','RSHJ_result','RSHJ_type','RSHK_result','RSHK_type','RSHL_result','RSHL_type','RSHM_result','RSHM_type','RSHN_result','RSHN_type','RSHO_result','RSHO_type','STF_result','STF_type','STL_result','STL_type','T1G_result','T1G_type','T3G_result','T3G_type','TK_result','TK_type','YCF_result','YCF_type','YCL_result','YCL_type','RESULT_F','RESULT_L','RESULT_A');
