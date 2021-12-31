var DataFT = new Array();
//取消原因 result_type
//輸贏結果 result_open
/*
var GameHead = new Array("gid","date","time","league","team_h","team_c","num_h","num_c","game_over",
"result_h1st","result_c1st","result_type1st","result_open1st",
"result_hfull","result_cfull","result_typefull","result_openfull",
"result_ha","result_ca","result_typea","result_opena","result_hb","result_cb","result_typeb","result_openb",
"result_hc","result_cc","result_typec","result_openc","result_hd","result_cd","result_typed","result_opend",
"result_he","result_ce","result_typee","result_opene","result_hf","result_cf","result_typef","result_openf",
"result_BH","BH",
"result_ARG","ARG","result_BRG","BRG","result_CRG","CRG","result_DRG","DRG","result_ERG","ERG",
"result_FRG","FRG","result_GRG","GRG","result_HRG","HRG","result_IRG","IRG","result_JRG","JRG",
"result_FG","FG","result_F2G","F2G","result_F3G","F3G","result_T1G","T1G","result_T3G","T3G","result_TK","TK","result_PA","PA",
"result_RCD","RCD","result_PGF","PGF","result_PGL","PGL","result_OSF","OSF","result_OSL","OSL","result_STF","STF","result_STL","STL",
"result_CNF","CNF","result_CNL","CNL","result_CDF","CDF","result_CDL","CDL","result_YCF","YCF","result_YCL","YCL","result_GAF","GAF",
"result_GAL","GAL","result_RCF","RCF","result_RCL","RCL");
*/
var objAry = new Object();
objAry["Minute"] = new Array("HGM","GM","AGM","BGM","CGM","DGM","EGM","FGM");
objAry["Manual"] = new Array("BH","ARG","BRG","CRG","DRG","ERG","FRG","GRG","HRG","IRG","JRG","FG","F2G","F3G","T1G","T3G","TK","PA","RCD","MQ","MW","OG","OT");
objAry["SP"] = new Array("PGF","PGL","OSF","OSL","STF","STL","CNF","CNL","CDF","CDL","RCF","RCL","YCF","YCL","GAF","GAL");
objAry["Corner"] = new Array("RNC1","RNC2","RNC3","RNC4","RNC5","RNC6","RNC7","RNC8","RNC9","RNCA","RNCB","RNCC","RNCD","RNCE","RNCF","RNCG","RNCH","RNCI","RNCJ","RNCK","RNCL","RNCM","RNCN","RNCO","RNCP","RNCQ","RNCR","RNCS","RNCT","RNCU");
objAry["Booking"] = new Array("RNBA","RNBB","RNBC","RNBD","RNBE","RNBF","RNBG","RNBH","RNBI","RNBJ","RNBK","RNBL","RNBM","RNBN","RNBO");
objAry["Penalty"] = new Array("RSHA","RSHB","RSHC","RSHD","RSHE","RSHF","RSHG","RSHH","RSHI","RSHJ","RSHK","RSHL","RSHM","RSHN","RSHO");


function Loaded(){

    DataFT = lib_parseArray(heads,gdata);

    document.getElementById("tean_name").innerHTML = DataFT["team_h"]+" VS "+DataFT["team_c"];
    document.getElementById("leagues_name").innerHTML = DataFT["league"];
    document.getElementById("game_time").innerHTML = DataFT["date"].substring(5)+" "+DataFT["time"];

    initMinuteObj(objAry["Minute"]);
    initManualObj(objAry["Manual"]);
    initSPObj(objAry["SP"]);
    initNextXObj(objAry["Corner"]);
    initNextXObj(objAry["Booking"]);
    initPenaltyObj(objAry["Penalty"]);

    fix_window();
}

//============================= init start =============================
function initMinuteObj(dataArr){
    for(var i=0; i<dataArr.length; i++){
        var thisTRObj = document.getElementById("tr_"+dataArr[i]);
        thisTRObj.style.display = "";

        var showResult = DataFT[dataArr[i]+"_result"];
        var openResult = DataFT[dataArr[i]+"_type"];
        var showScoreH = DataFT[dataArr[i]+"_h"];
        var showScoreC = DataFT[dataArr[i]+"_c"];

        if( openResult==undefined || openResult == "DL" || (showScoreH*1 == -11 || showScoreC*1 == -11) ){
            thisTRObj.style.display = "none";
        }else{
            hasShow = true;

            if(showResult != ""){
                //showScoreH = showResult;
                showScoreH = "";
                showScoreC = "";
                //showResult = "";
                document.getElementById("score_h_"+dataArr[i]).colSpan = "2";
                document.getElementById("score_c_"+dataArr[i]).style.display="none";

            }

            document.getElementById("result_"+dataArr[i]).innerHTML = showResult;
            document.getElementById("score_h_"+dataArr[i]).innerHTML = showScoreH;
            document.getElementById("score_c_"+dataArr[i]).innerHTML = showScoreC;

        }
    }
}

function initManualObj(dataArr){
    var showNumber = 0;
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
            showNumber++;

            if(showResult != ""){
                document.getElementById("result_type"+dataArr[i]).innerHTML = showResult;
            }else{
                var newOpenResult = openResult;

                // 字串轉換
                if(dataArr[i] == "FG" || dataArr[i] == "T3G" || dataArr[i] == "T1G"){
                    newOpenResult = str_result[dataArr[i]+"_"+newOpenResult];

                }if(dataArr[i] == "MW" || dataArr[i] == "MQ" ){
                    var teamSide = openResult.substring(0,1);
                    newOpenResult = (teamSide=="H") ? DataFT["team_h"] : DataFT["team_c"];
                    newOpenResult+= str_result["MQ_"+openResult];


                }else if(dataArr[i]=="PA" || dataArr[i]=="RCD" || dataArr[i]=="OG" || dataArr[i]=="OT" ){
                    //2017-0215-johnson-修正30.舊會員端-,1st goal,2nd goal系列的賽果NO也幫改No
                    if(newOpenResult=="N")newOpenResult="NA";
                    newOpenResult = str_result[newOpenResult];

                }else{
                    if(newOpenResult=="H" || newOpenResult=="Home"){

                        newOpenResult = DataFT["team_h"];
                    }else if(newOpenResult=="C" || newOpenResult=="Away"){

                        newOpenResult = DataFT["team_c"];
                    }else if(newOpenResult=="N" || newOpenResult=="No"){
                        //2017-0215-johnson-修正30.舊會員端-,1st goal,2nd goal系列的賽果NO也幫改No
                        newOpenResult = str_result["NA"];

                    }

                }

                document.getElementById("result_type"+dataArr[i]).innerHTML = newOpenResult;

                // 2017-03-07 3041.舊會員端-內層賽果-其他賽果非正式比分以外的結果,粗體都要拿掉(crm-207)
                document.getElementById("result_type"+dataArr[i]).className = "res_bold";
            }


            //2017-0214-johnson30.舊會員端-足球內層賽果-位置統一移到中間(CRM-203)
            if( showNumber%2==1){
                document.getElementById("tr_"+dataArr[i]).className="res_dg";
            }else{
                document.getElementById("tr_"+dataArr[i]).className="res_lg";
            }
        }
    }
}

function initSPObj(dataArr){
    var showNumber = 0;

    for(var i=0; i<dataArr.length; i++){
        var thisTRObj = document.getElementById("tr_"+dataArr[i]);
        thisTRObj.style.display = "";

        var showResult = DataFT[dataArr[i]+"_result"];
        var openResult = DataFT[dataArr[i]+"_type"];
        var finalResult = openResult;
        //console.log(dataArr[i]+"    "+DataFT[dataArr[i]+"_type"]+"____"+showResult);
        var nowSubWtype = dataArr[i];
        if(openResult==undefined || openResult == "DL" ){ //|| WtypeOpen[nowSubWtype] == "Y" ){
            thisTRObj.style.display = "none";
        }else{
            showNumber++;
            if(showResult != ""){
                document.getElementById("result_type"+dataArr[i]).innerHTML = showResult;

            }else{
                var newOpenResult = openResult;

                if(newOpenResult=="H" || newOpenResult=="Home"){
                    newOpenResult = DataFT["team_h"];
                }else if(newOpenResult=="C" || newOpenResult=="Away"){
                    newOpenResult = DataFT["team_c"];
                }else if(newOpenResult=="N" || newOpenResult=="No"){
                    //2017-0215-johnson-修正30.舊會員端-,1st goal,2nd goal系列的賽果NO也幫改No
                    newOpenResult = str_result["NA"];

                }else if(newOpenResult=="Both"){

                    //2017-0215-johnson-修正30.舊會員端-,1st goal,2nd goal系列的賽果NO也幫改No
                    if(newOpenResult=="N")newOpenResult="NA";
                    newOpenResult = str_result[newOpenResult];
                }
                document.getElementById("result_type"+dataArr[i]).innerHTML = newOpenResult;

                // 2017-03-07 3041.舊會員端-內層賽果-其他賽果非正式比分以外的結果,粗體都要拿掉(crm-207)
                document.getElementById("result_type"+dataArr[i]).className = "res_bold";
            }



            //2017-0214-johnson30.舊會員端-足球內層賽果-位置統一移到中間(CRM-203)
            if( showNumber%2==1){
                document.getElementById("tr_"+dataArr[i]).className="res_dg";
            }else{
                document.getElementById("tr_"+dataArr[i]).className="res_lg";
            }
        }
    }
}


function initNextXObj(dataArr){
    var hasShow = false;
    var swType = dataArr[0];
    var playType = dataArr[0].substring(0,3);
    var showNumber = 0;

    for(var i=0; i<dataArr.length; i++){
        var thisTRObj = document.getElementById("tr_"+dataArr[i]);
        thisTRObj.style.display = "";

        var showResult = DataFT[dataArr[i]+"_result"];
        var openResult = DataFT[dataArr[i]+"_type"];

        if(openResult==undefined || openResult == "DL" ){ //|| WtypeOpen[swType] == "Y" ){
            thisTRObj.style.display = "none";
        }else{
            showNumber++;
            hasShow = true;

            var newOpenResult = openResult;

            if(showResult != ""){
                newOpenResult = showResult;
            }else if(newOpenResult=="H" ){
                newOpenResult = DataFT["team_h"];
            }else if(newOpenResult=="C" ){
                newOpenResult = DataFT["team_c"];
            }else if(newOpenResult=="P" ){
                newOpenResult = str_result[playType+"_"+newOpenResult];
            }

            document.getElementById("result_type"+dataArr[i]).innerHTML = newOpenResult;

            // 2017-03-07 3041.舊會員端-內層賽果-其他賽果非正式比分以外的結果,粗體都要拿掉(crm-207)
            if(showResult == "") document.getElementById("result_type"+dataArr[i]).className = "res_bold";

            //2017-0214-johnson30.舊會員端-足球內層賽果-位置統一移到中間(CRM-203)
            if( showNumber%2==1){
                document.getElementById("tr_"+dataArr[i]).className="res_dg";
            }else{
                document.getElementById("tr_"+dataArr[i]).className="res_lg";
            }
        }
    }

    return hasShow;
}

function initPenaltyObj(dataArr){
    var hasShow = false;
    var swType = dataArr[0];
    var playType = dataArr[0].substring(0,2);
    var sideArr = ["H","C"];
    var showNumber = 0;

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
            showNumber++;
            hasShow = true;

            for(var ss=0;ss<sideArr.length;ss++){
                var newOpenResult = openResult[sideArr[ss]];

                if(showResult[sideArr[ss]] != ""){
                    newOpenResult = showResult[sideArr[ss]];
                }else{
                    newOpenResult = str_result[playType+"_"+newOpenResult];
                }

                document.getElementById("result_type"+playType+sideArr[ss]+nextType).innerHTML = newOpenResult;

                // 2017-03-07 3041.舊會員端-內層賽果-其他賽果非正式比分以外的結果,粗體都要拿掉(crm-207)
                if(showResult[sideArr[ss]] == "") document.getElementById("result_type"+playType+sideArr[ss]+nextType).className = "res_bold";

                //2017-0214-johnson30.舊會員端-足球內層賽果-位置統一移到中間(CRM-203)
                if( showNumber%2==1){
                    document.getElementById("tr_"+dataArr[i]).className="res_dg";
                }else{
                    document.getElementById("tr_"+dataArr[i]).className="res_lg";
                }
            }
        }
    }

    return hasShow;
}


//============================= init end =============================

function closeIframe(){
    parent.document.getElementById('result_new_Data').style.display = "none";
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