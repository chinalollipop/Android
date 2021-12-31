// 当前美东时间 开始
function returnCurTime(par) {
    var restr = '';
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
    if(par == 'day'){ // 返回日期
        restr = nY + '-' + nM + '-' + nD;
    }else{
        restr = nH + ':' + nMi + ':' + nS;
    }
    return restr;
}

var today_gmt = returnCurTime('day') ;
var now_gmt = returnCurTime() ;
// 当前美东时间 结束

// 鼠标事件
var odd_f_str = 'H,M,I,E';
var ReloadTimeID;
var sel_gtype = parent.sel_gtype ;
var Format=new Array();
Format[0]=new Array( 'H','香港盘','Y');
// Format[1]=new Array( 'M','马来盘','Y');
// Format[2]=new Array( 'I','印尼盘','Y');
// Format[3]=new Array( 'E','欧洲盘','Y');

var keep_drop_layers;
var dragapproved=false;
var iex;
var iey;
var choice=""; // 冠军独有
var odd_f_type="H";
var swShowLoveI;
// console.log(today_gmt+'==='+now_gmt)

//網頁載入
function onLoad(){
    // swShowLoveI=false;

    if(parent.rtype=="re"){ // 滚球
        if((""+eval("parent."+sel_gtype+"_lname_ary_RE"))=="undefined"){
            eval("parent."+sel_gtype+"_lname_ary_RE='ALL'");
        }

        if((""+eval("parent."+sel_gtype+"_lid_ary_RE"))=="undefined"){
            eval("parent."+sel_gtype+"_lid_ary_RE='ALL'");
        }
    }else{
        if((""+eval("parent."+sel_gtype+"_lname_ary"))=="undefined"){
            eval("parent."+sel_gtype+"_lname_ary='ALL'");
        }

        if((""+eval("parent."+sel_gtype+"_lid_ary"))=="undefined"){
            eval("parent."+sel_gtype+"_lid_ary='ALL'");
        }
    }
    if(parent.ShowType==""||parent.rtype=="r") parent.ShowType = 'OU';
    if(parent.rtype=="hr") parent.ShowType = 'OU';
    if(parent.rtype=="re") parent.ShowType = 'RE';
    if(parent.rtype=="pd") parent.ShowType = 'PD';
    if(parent.rtype=="hpd") parent.ShowType = 'HPD';
    if(parent.rtype=="t") parent.ShowType = 'EO';
    if(parent.rtype=="f") parent.ShowType = 'F';
    if(parent.leg_flag=="Y"){
        parent.leg_flag="N";
        parent.pg=0;
        //reload_var();
    }
    parent.loading = 'N';
    parent.loading_var = 'N';

    if(parent.loading_var == 'N'){
        ShowGameList();

    }
    if(parent.retime>0){ // 刷新倒计时
        parent.retime_flag = 'Y' ;
    }
    if (parent.retime_flag == 'Y'){
        count_down();
    }else{
        var rt=document.getElementById('refreshTime');
        rt.innerHTML = refreshTime ;
    }

    // addAppDownLoad() ;


}

function ShowGameList(){
    if(parent.loading == 'Y') return;

    keepscroll =document.body.scrollTop;

    //var conscroll= document.getElementById('controlscroll');
    dis_ShowLoveI(); // 选择联赛

    //顯示盤口
    // if(parent.rtype !='FS') { // 冠军没有盘口选择
    //     ChkOddfDiv();
    // }

    if(parent.showtype=='hgft'||parent.showtype=='hgfu'){
        obj_sel = document.getElementById('sel_league');
        obj_sel.style.display='none';
        try{
            var obj_date='';
            obj_date=document.getElementById("g_date").value;
            selgdate("",obj_date);
        }catch(E){}
    }else{
        if(parent.rtype !='FS') { // 冠军没有分页
            show_page(); // 分页
        }
    }
    //conscroll.style.display="none";
    coun_Leagues();
    if(parent.rtype !='FS') { // 冠军没有我的最爱，加入收藏
        showPicLove(); // 显示我的最爱
        innerMyLoveHtml() ;
    }
    loadingOK();
    lookGameResult();
    openGameRoul();
}
function loadingOK(){
    try{
        document.getElementById("refresh_btn").className="refresh_btn";
    }catch(E){}
    try{
        document.getElementById("refresh_right").className="refresh_M_btn";
    }catch(E){}

}
// 联赛展示条数
function coun_Leagues(){
    var coun=0;
    if(parent.rtype=='re'){ // 滚球
        var str_tmp ="|"+eval('parent.'+sel_gtype+'_lname_ary_RE');
    }else if(parent.rtype=='FS'){ // 冠军
        var str_tmp ="-"+eval("parent.FS"+parent.FStype+"_lid['FS"+parent.FStype+"_lid_ary']");
    }else {
        var str_tmp ="|"+eval('parent.'+sel_gtype+'_lname_ary');
    }
    // console.log(str_tmp);
    if(str_tmp=='|ALL' || str_tmp=='-ALL'){
        document.getElementById("str_num").innerHTML =alldata;
    }else{
        // console.log('有联赛');
        if(parent.rtype=='FS'){ // 冠军
            var larray=str_tmp.split('-');
        }else{
            var larray=str_tmp.split('|');
        }

        for(var i =0;i<larray.length;i++){
            if(larray[i]!=""){coun++}
        }
        // console.log(larray) ;
        // console.log(parent.LeagueAry);
        // coun = parent.LeagueAry.length;
        document.getElementById("str_num").innerHTML =coun;
    }
}
// 返回联盟名称
function returnLeaguesName() {
    if(parent.rtype=='re'){ // 滚球
        var str_tmp ="|"+eval('parent.'+sel_gtype+'_lname_ary_RE');
    }else if(parent.rtype=='FS'){ // 冠军
        var str_tmp ="-"+eval("parent.FS"+parent.FStype+"_lid['FS"+parent.FStype+"_lid_ary']");
    } else{
        var str_tmp ="|"+eval('parent.'+sel_gtype+'_lname_ary');
    }

    var arr = [] ;
    if(str_tmp=='|ALL' || str_tmp=='-ALL'){
        arr = [] ;
    }else{
        if(parent.rtype=='FS'){ // 冠军
            var larray=str_tmp.split('-');
        }else{
            var larray=str_tmp.split('|');
        }
        for(var i =0;i<larray.length;i++){
            if(larray[i]!=""){
                arr.push(larray[i]) ;
            }
        }
    }

    return arr ;
}

//  加入我的最爱，new array{球類 , new array {gid ,data time ,聯盟,H,C,sw}}
function addShowLoveI(gid,getDateTime,getLid,team_h,team_c){
    var getGtype =getGtypeShowLoveI();
    var getnum =parent.parent.ShowLoveIarray[getGtype].length;
    var sw =true;
    for (var i=0 ; i < parent.parent.ShowLoveIarray[getGtype].length ; i++){
        if(parent.parent.ShowLoveIarray[getGtype][i][0]==gid)
            sw = false;
    }
    if(sw){
        parent.parent.ShowLoveIarray[getGtype] = arraySort(parent.parent.ShowLoveIarray[getGtype] ,new Array(gid,getDateTime,getLid,team_h,team_c));
        chkOKshowLoveI();
    }
    document.getElementById("sp_"+MM_imgId(getDateTime,gid)).innerHTML = "<div class=\"fov_icon_on\" style=\"cursor:hand\" title=\""+str_delShowLoveI+"\" onClick=\"chkDelshowLoveI('"+getDateTime+"','"+gid+"');\"></div>";
}
function arraySort(array ,data){
    var outarray =new Array();
    var newarray =new Array();
    for(var i=0;i < array.length ;i++){
        if(array[i][1]<= data[1]){
            outarray.push(array[i]);
        }else{
            newarray.push(array[i]);
        }
    }
    outarray.push(data);
    for(var i=0;i < newarray.length ;i++){
        outarray.push(newarray[i]);
    }
    return  outarray;
}
function MM_imgId(time,gid){
    var tmp = time.split("<br>")[0];
    return tmp+gid;
}
function getGtypeShowLoveI(){
    var Gtype;
    var getGtype = parent.sel_gtype ;
    var getRtype =parent.rtype ;
    Gtype =getGtype;
    if(getRtype=="re"){
        Gtype +="RE";
    }
    return Gtype;
}

function chkOKshowLoveI(){
    var getGtype = getGtypeShowLoveI();
    var getnum =parent.parent.ShowLoveIOKarray[getGtype].length ;
    var ibj="" ;
    parent.parent.ShowLoveIOKarray[getGtype]="";
    for (var i=0 ; i < parent.parent.ShowLoveIarray[getGtype].length ; i++){
        tmp = parent.parent.ShowLoveIarray[getGtype][i][1].split("<br>")[0];
        parent.parent.ShowLoveIOKarray[getGtype]+=tmp+parent.parent.ShowLoveIarray[getGtype][i][0]+",";
    }
    showPicLove();
}

//----------------------------我的最愛  start----------------------------------
function chkDelshowLoveI(data2,data){
    var getGtype = getGtypeShowLoveI();
    var tmpdata = data2.split("<br>")[0]+data;
    var tmpdata1 ="";
    var ary = new Array();
    var tmp = new Array();
    tmp = parent.parent.ShowLoveIarray[getGtype];
    parent.parent.ShowLoveIarray[getGtype] = new Array();
    for (var i=0 ; i < tmp.length ; i++){
        tmpdata1 =tmp[i][1].split("<br>")[0]+tmp[i][0];
        if(tmpdata1 == tmpdata){
            ary = tmp[i];
            continue;
        }
        parent.parent.ShowLoveIarray[getGtype].push(tmp[i]);
    }
    chkOKshowLoveI();
    var gtypeNum= StatisticsGty(today_gmt,now_gmt,getGtypeShowLoveI());
    if(swShowLoveI){

        var sw=false;
        if(gtypeNum==0){
            swShowLoveI=false;
            eval("parent.parent."+parent.sel_gtype+"_lid_type=parent."+parent.sel_gtype+"_lid['"+parent.sel_gtype+"_lid_type']");
            reload_var();
        }else{
            ShowGameList();
        }
    }else{
        if(gtypeNum==0){
            reload_var();
        }else{
            document.getElementById("sp_"+MM_imgId(ary[1],ary[0])).innerHTML ="<div id='"+MM_imgId(ary[1],ary[0])+"' class=\"fov_icon_out\" style=\"cursor:hand;display:none;\" title=\""+str_ShowMyFavorite+"\" onClick=\"addShowLoveI('"+ary[0]+"','"+ary[1]+"','"+ary[2]+"','"+ary[3]+"','"+ary[4]+"'); \"></div>";
        }
    }
}
// 清空我的最爱
function chkDelAllShowLoveI(){
    var getGtype=getGtypeShowLoveI();
    parent.parent.ShowLoveIarray[getGtype]= new Array();
    parent.parent.ShowLoveIOKarray[getGtype]="";
    if(swShowLoveI){
        swShowLoveI=false;
        // eval("parent.parent."+parent.sel_gtype+"_lid_type=parent."+parent.sel_gtype+"_lid['"+parent.sel_gtype+"_lid_type']");
        eval("parent."+parent.sel_gtype+"_lid_type=parent."+parent.sel_gtype+"_lid['"+parent.sel_gtype+"_lid_type']");
        parent.pg =0;
        reload_var();
    }else{
        ShowGameList();
    }
}

// 如果有加入最爱赛事
function innerMyLoveHtml() {
    var getGtype =getGtypeShowLoveI();
    // console.log(parent.parent.ShowLoveIarray[getGtype]) ;
    for (var i=0 ; i < parent.parent.ShowLoveIarray[getGtype].length ; i++){
        if(parent.parent.ShowLoveIarray[getGtype][i][0]){
            var gid = parent.parent.ShowLoveIarray[getGtype][i][0] ;
            var getDateTime = parent.parent.ShowLoveIarray[getGtype][i][1] ;
            var getLid = parent.parent.ShowLoveIarray[getGtype][i][2] ;
            var team_h = parent.parent.ShowLoveIarray[getGtype][i][3] ;
            var team_c = parent.parent.ShowLoveIarray[getGtype][i][4] ;
            var str = MM_ShowLoveI(gid,getDateTime,getLid,team_h,team_c) ;
            var $td_love = document.getElementById('td_love_'+gid) ;
            if($td_love){
                $td_love.innerHTML = str ;
            }

        }

    }

}
// 判断是否有加入最爱赛事
function MM_ShowLoveI(gid,getDateTime,getLid,team_h,team_c){
    var txtout="";
    if(!swShowLoveI){
        if(!chkRepeat(gid)){
            txtout = "<span id='sp_"+MM_imgId(getDateTime,gid)+"'><div id='"+MM_imgId(getDateTime,gid)+"' class=\"fov_icon_out\" style=\"cursor:hand;display:none;\" title=\""+str_ShowMyFavorite+"\" onClick=\"addShowLoveI('"+gid+"','"+getDateTime+"','"+getLid+"','"+team_h+"','"+team_c+"'); \"></div></span>";
        }else{
            txtout = "<span id='sp_"+MM_imgId(getDateTime,gid)+"'><div class=\"fov_icon_on\" style=\"cursor:hand\" title=\""+str_delShowLoveI+"\" onClick=\"chkDelshowLoveI('"+getDateTime+"','"+gid+"'); \"></div></span>";
        }
    }else{
        txtout = "<div class=\"fov_icon_on\" style=\"cursor:hand\" title=\""+str_delShowLoveI+"\" onClick=\"chkDelshowLoveI('"+getDateTime+"','"+gid+"'); \"></div>";
    }
    return txtout;
}

function chkRepeat(gid){
    var getGtype =getGtypeShowLoveI();
    var sw =false;
    for (var i=0 ; i < parent.parent.ShowLoveIarray[getGtype].length ; i++){
        if(parent.parent.ShowLoveIarray[getGtype][i][0]==gid){
            sw =true;
        }

    }
    return sw;
}
// 返回加入收藏 id
function returnMyLoveId() {
    var getGtype =getGtypeShowLoveI();
    var arrid = [] ;
    for (var i=0 ; i < parent.parent.ShowLoveIarray[getGtype].length ; i++){
        if(parent.parent.ShowLoveIarray[getGtype][i][0]){
            arrid.push(parent.parent.ShowLoveIarray[getGtype][i][0]) ;
        }

    }
    return arrid ;
}

// 选择联赛
function dis_ShowLoveI(){
    if(swShowLoveI){
        document.getElementById("sel_league").style.display="none";
    }else{
        document.getElementById("sel_league").style.display="";
    }

}

//檢查所選的最愛賽事是否已經進入滚球或是結束
function checkLoveCount(GameArray){
    var getGtype = getGtypeShowLoveI();
    var tmpdata = "";
    var tmpdata1 ="";
    var ary = new Array();
    var tmp = new Array();
    tmp = parent.parent.ShowLoveIarray[getGtype];
    parent.parent.ShowLoveIarray[getGtype] = new Array();
    for (s=0;s < GameArray.length;s++){
        tmpdata=GameArray[s].datetime.split("<br>")[0]+GameArray[s].gnum_h;
        for (var i=0;i < tmp.length; i++){
            tmpdata1 =tmp[i][1].split("<br>")[0]+tmp[i][0];
            if(tmpdata1 == tmpdata){
                parent.parent.ShowLoveIarray[getGtype].push(tmp[i]);
            }
        }
    }
    chkOKshowLoveI();
}
// 显示我的最爱
function showPicLove(){
    var gtypeNum= StatisticsGty(today_gmt,now_gmt,getGtypeShowLoveI());
    //console.log(gtypeNum);
    try{
        document.getElementById("fav_num").style.display = "none";
        document.getElementById("showNull").style.display = "none";
        document.getElementById("showAll").style.display = "none";
        document.getElementById("showMy").style.display = "none";
        if(gtypeNum!=0){
            document.getElementById("live_num").innerHTML =gtypeNum;
            document.getElementById("fav_num").style.display = "block";
            if(swShowLoveI){
                document.getElementById("showAll").style.display = "block";
            }else{
                document.getElementById("showMy").style.display = "block";
            }
        }else{
            document.getElementById("showNull").style.display = "block";
            swShowLoveI=false;
        }
    }catch(E){}
}
//我的最愛中的顯示全部
function showAllGame(gtype){
    swShowLoveI=false;
    eval("parent."+parent.sel_gtype+"_lid_type=parent."+parent.sel_gtype+"_lid['"+parent.sel_gtype+"_lid_type']");
    reload_var();
}

//单式盤面點下我的最愛
function showMyLove(gtype){
    swShowLoveI =true;
    parent.pg =0;
    // eval("parent.parent."+parent.sel_gtype+"_lid_type='3'");
    var myloveid = returnMyLoveId();
    myloveid = myloveid.toString() ;
    //console.log(myloveid);
    reload_var('','',myloveid);
}
function StatisticsGty(today,now_gmt,gtype){
    var out=0;
    var array =new Array(0,0,0);
    var tmp =today.split("-");
    var newtoday =tmp[1]+"-"+tmp[2];
    var Months =tmp[1]*1;
    tmp =now_gmt.split(":");
    var newgmt=tmp[0]+":"+tmp[1];
    var tmpgday = new Array(0,0);
    var bf = false;
    //console.log(parent.parent.ShowLoveIarray);

    for (var i=0 ; i < parent.parent.ShowLoveIarray[gtype].length ; i++){
        tmpday = parent.parent.ShowLoveIarray[gtype][i][1].split("<br>")[0];
        tmpgday = tmpday.split("-");
        tmpgmt =parent.parent.ShowLoveIarray[gtype][i][1].split("<br>")[1];
        tmpgmt=time_12_24(tmpgmt);
        if(++tmpgday[0] < Months){
            bf = true;
        }else{
            bf = false;
        }
        if(bf){
            array[2]++;
        }else{
            if(parent.sel_gtype=="FT"||parent.sel_gtype=="OP"||parent.sel_gtype=="BK"||parent.sel_gtype=="BS"||parent.sel_gtype=="VB"||parent.sel_gtype=="TN"){
                if(parent.rtype=="re"){
                    array[0]++;	//走地
                }else{
                    array[1]++;	//单式
                }
            }else if(parent.sel_gtype=="FU"||parent.sel_gtype=="OM"||parent.sel_gtype=="BU"||parent.sel_gtype=="BSFU"||parent.sel_gtype=="VU"||parent.sel_gtype=="TU"){
                array[2]++;	//早餐
            }
            /*
            if(newtoday >= tmpday ){
                if((newtoday+" "+newgmt) >= (tmpday+" "+tmpgmt)){
                    array[0]++;	//走地
                }else{
                    array[1]++;	//单式
                }
            }else if(newtoday < tmpday){
                array[2]++;	//早餐
            }*/
        }
    }

    if(parent.sel_gtype=="FT"||parent.sel_gtype=="OP"||parent.sel_gtype=="BK"||parent.sel_gtype=="BS"||parent.sel_gtype=="VB"||parent.sel_gtype=="TN"){
        if(parent.rtype=="re"){
            out=array[0];
        }else{
            out=array[1];
        }
    }else if(parent.sel_gtype=="FU"||parent.sel_gtype=="OM"||parent.sel_gtype=="BU"||parent.sel_gtype=="BSFU"||parent.sel_gtype=="VU"||parent.sel_gtype=="TU"){
        out=array[2];
    }

    return out;
}
function time_12_24(stTime){
    var out="";
    var shour =stTime.split(":")[0]*1;
    var smin=stTime.split(":")[1];
    var aop =smin.substr(smin.length-1,1);
    if(aop =="p"){
        if((shour*1)>0)
            shour += 12;
    }
    out=((shour < 10)?"0":"")+shour+":"+smin;
    return out;
}

// 重新刷新  leaname 联赛名字 myloveid 我的最爱 id
function reload_var(Level,leaname,myloveid){
    parent.loading_var = 'N';
    if(parent.rtype=='FS'){ // 冠军

        var gj_homepage = "./loadgame_R.php?rtype="+parent.rtype+"&FStype="+parent.FStype+"&showtype="+(parent.showtype?parent.showtype:'today') ;
        var leaarr = returnLeaguesName(); // 是否有选择联盟赛事
        if(leaarr){
            gj_homepage +="&myleaArr="+encodeURIComponent(leaarr.toString()) ;
        }
        self.location.href = gj_homepage;

        return ;
    }
    if(Level=="up"){
        var tmp = "./"+parent.sel_gtype+"_browse/body_var.php";
        if (parent.sel_gtype=="FU"){
            tmp = "./FT_future/body_var.php";
        }else if(parent.sel_gtype=="BU"){
            tmp = "./BK_future/body_var.php";
        }
    }else{
        var tmp = "./body_var.php";
    }

    var l_id =eval("parent.parent."+parent.sel_gtype+"_lid_type");
    if(parent.showtype=='hgft' && parent.sel_gtype=="FU"){
        l_id=3;
    }

    var homepage = tmp+"?uid="+parent.uid+"&rtype="+parent.rtype+"&langx="+langx+"&mtype="+parent.ltype+"&page_no="+parent.pg+"&league_id="+l_id ;
    if(leaname){
        homepage +="&leaname="+encodeURIComponent(leaname) ;
    }
    if (parent.sel_gtype=="FU" || parent.sel_gtype=="BU"){ // 早盘
        homepage +="&g_date="+parent.g_date+"&showtype=future";
    }
    if (parent.sel_gtype=="FT" && parent.rtype=="p3"){ // 今日足球综合过关
        homepage +="&g_date="+parent.g_date ;
    }
    if(myloveid){ // 我的最爱
        homepage +="&mylovegame="+myloveid ;
    }
    var leaarr = returnLeaguesName(); // 是否有选择联盟赛事
    if(leaarr && leaarr !='undefined'){
        homepage +="&myleaArr="+encodeURIComponent(leaarr.toString()) ;
    }

    // parent.body_var.location = homepage;
    $(".body_browse_set").load(homepage,function () {
        ShowGameList();
        // addAppDownLoad();
    }) ;
    if(parent.rtype=="r") document.getElementById('more_window').style.display='none';
}

//倒數自動更新時間
function count_down(){
    var rt=document.getElementById('refreshTime');
    setTimeout('count_down()',1000);
    if (parent.retime_flag == 'Y'){
        //console.log(parent.retime) ;
        if(parent.retime <= 0){
            if(parent.loading_var == 'N')
                reload_var();
            return;
        }
        parent.retime--;
        rt.innerHTML=parent.retime;
    }
}


//賽事換頁
function chg_pg(pg){
    if (pg==parent.pg) {return;}
    parent.pg=pg;
    reload_var();
}

function chg_wtype(wtype){
    var l_id =eval("parent.parent."+parent.sel_gtype+"_lid_type");
    if(swShowLoveI) l_id=3;
    // if(parent.showtype=='hgft'&&parent.sel_gtype=="FU"){
    //     l_id=3;
    // }
    parent.location.href="index.php?uid="+uid+"&langx="+langx+"&mtype="+parent.ltype+"&rtype="+wtype+"&showtype="+parent.showtype+"&league_id="+l_id;
}

function ChkOddfDiv(){
    var odd_show="<select id=myoddType onchange=chg_odd_type()>";
    var tmp_check="";
    for (i = 0; i < Format.length; i++) {
        //沒盤口選擇時，預設為H(香港變盤)
        if((odd_f_str.indexOf(Format[i][0])!=(-1))&&Format[i][2]=="Y"){

            if(odd_f_type==Format[i][0]){
                odd_show+="<option value="+Format[i][0]+tmp_check+" selected>"+Format[i][1]+"</option>";
            }else{
                odd_show+="<option value="+Format[i][0]+tmp_check+">"+Format[i][1]+"</option>";
            }
        }
    }
    odd_show+"</select>";
    document.getElementById("Ordertype").innerHTML=odd_show;

}

//切換盤口
function chg_odd_type(){
    var myOddtype=document.getElementById("myoddType");
    odd_f_type=myOddtype.options[myOddtype.selectedIndex].value;
    reload_var();
}

//分頁
function show_page(){
    pg_str='';
    obj_pg = document.getElementById('pg_txt');
    var t_page = parent.t_page ;
    var pg = parent.pg ;
    // console.log(t_page);
    if (t_page==0){
        t_page=1;
    }
    var tmp_lid="";
    if (parent.rtype=="re"){
        tmp_lid=eval("parent."+sel_gtype+"_lid_ary_RE");
    }else{
        tmp_lid=eval("parent."+sel_gtype+"_lid_ary");
    }
    if(tmp_lid=='ALL'&&!swShowLoveI){
        var disabled="";
        if (t_page==1){
            disabled="disabled";
        }
        var pghtml=(pg*1+1)+" / " +t_page+" "+page+"&nbsp;&nbsp; <select  onchange='chg_pg(this.options[this.selectedIndex].value)' "+disabled+">";
        for(var i=0;i<t_page;i++){
            if (pg==i){
                pghtml+="<option value='"+i+"' selected>"+(i+1)+"</option>";
            }else{
                pghtml+="<option value='"+i+"' >"+(i+1)+"</option>";
            }
        }
        pghtml+="</select>";
        obj_pg.innerHTML = pghtml;
    }else{
        obj_pg.innerHTML = "";
    }
}

// 搜索关键字  leg_bar
function seaGameList(obj) {
    var txt = $.trim($('#seachtext').val()) ;
    // console.log(txt) ;
    var seaArr=[] ;
    if(!txt){ // 没有输入内容
        reload_var();
        return ;
    }
    reload_var('',txt) ;

}

// 选择联赛=================start
function chg_league(){
    var legview =document.getElementById('legView');
    var $mytable = document.getElementById('myTable') ;
    try{
        legFrame.location.href="./body_var_lid.php?uid="+parent.uid+"&rtype="+parent.rtype+"&langx="+langx+"&mtype="+parent.ltype+"&FStype="+parent.FStype;
    }catch(e){
        legFrame.src="./body_var_lid.php?uid="+parent.uid+"&rtype="+parent.rtype+"&langx="+langx+"&mtype="+parent.ltype+"&FStype="+parent.FStype;
    }
    legview.style.display='';
    // legview.style.top=  document.body.scrollTop+82;
    // if($mytable){
    //     legview.style.left = $mytable.scrollLeft+10;
    // }

}

//--------------判斷聯盟顯示或隱藏----------------
function showLeg(leg){
    //console.log(parent.myLeg);
    for (var i=0;i<parent.myLeg[leg].length;i++){
        var newarr = parent.myLeg[leg][i].split(',') ;
        // console.log(newarr);
        for(var j=0;j<newarr.length;j++){
            if ( document.getElementById("TR_"+newarr[j]).style.display!="none"){
                showLegIcon(leg,"LegClose",newarr[j],"none");
            }else{
                showLegIcon(leg,"LegOpen",newarr[j],"");
            }
        }

    }
    if ((""+parent.NoshowLeg[leg])=="undefined"){
        parent.NoshowLeg[leg] = -1;
    }else{
        parent.NoshowLeg[leg] = parent.NoshowLeg[leg]*-1;
    }

}

// 冠军独有
// function showLEG(gid){
//     tmp_leg=GameFT[gidx[gid]][2];
//     for (x=0;x < GameFT.length;x++){
//         if (tmp_leg==GameFT[x][2]){
//             gid=GameFT[x][0];
//             if ((""+NoshowLeg[gid+"_"+tmp_leg])=="undefined"){
//                 NoshowLeg[gid+"_"+tmp_leg]=-1;
//             }else{
//                 NoshowLeg[gid+"_"+tmp_leg]=NoshowLeg[gid+"_"+tmp_leg]*-1;
//             }
//             if(document.getElementsByClassName('TR_'+gid)[0].style.display=="none"){
//                 document.getElementsByClassName('TR_'+gid)[0].style.display="";
//                 document.getElementsByClassName('TR_'+gid)[1].style.display="";
//                 document.getElementById('TR_1_'+gid).style.display="";
//                 document.getElementById(gid+"_"+tmp_leg).innerHTML="<span id='LegOpen'></span>";
//             }else{
//                 document.getElementById(gid+"_"+tmp_leg).innerHTML="<span id='LegClose'></span>";
//                 document.getElementsByClassName('TR_'+gid)[0].style.display="none";
//                 document.getElementsByClassName('TR_'+gid)[1].style.display="none";
//                 document.getElementById('TR_1_'+gid).style.display="none";
//             }
//         }
//     }
//
// }

function showLegIcon(leg,state,gnumH,display){
    var  ary = document.getElementsByName(leg);

    for (var j=0;j<ary.length;j++){
        ary[j].innerHTML="<span id='"+state+"'></span>";
    }
    try{
        document.getElementById("TR3_"+gnumH).style.display=display;
    }catch(E){}
    try{
        document.getElementById("TR2_"+gnumH).style.display=display;
    }catch(E){}
    try{
        document.getElementById("TR1_"+gnumH).style.display=display;
    }catch(E){}
    try{
        document.getElementById("TR_"+gnumH).style.display=display;
    }catch(E){}

    if(parent.rtype =='FS') { // 冠军
        $('.TR_'+gnumH).css('display',display) ;
    }
}



if (document.all){
    document.onmouseup=new Function("dragapproved=false;");
}

function initializedragie(drop_layers){
    return;
    keep_drop_layers=drop_layers;
    iex=event.clientX
    iey=event.clientY
    eval("tempx="+drop_layers+".style.pixelLeft")
    eval("tempy="+drop_layers+".style.pixelTop")
    dragapproved=true;
    document.onmousemove=drag_dropie;
}
function drag_dropie(){
    if (dragapproved==true){
        eval("document.all."+keep_drop_layers+".style.pixelLeft=tempx+event.clientX-iex");
        eval("document.all."+keep_drop_layers+".style.pixelTop=tempy+event.clientY-iey");
        return false
    }
}
function setleghi(leghight){
    var legview =document.getElementById('legFrame');
    if((leghight*1) > 95){
        legview.height = leghight;
    }else{

        legview.height = 95;
    }
}
// 关闭联盟
function LegBack(){
    var legview =document.getElementById('legView');
    legview.style.display='none';
    reload_var();
}
// 选择联赛=================end

//將時間 轉回 24小時//04:00p
function  change_time(get_time){
    if (get_time.indexOf("font") > 0 ) return get_time;
    if (get_time.indexOf("p")>0 || get_time.indexOf("a")>0){
        gtime=get_time.split(":");
        if (gtime[1].indexOf("p")>0){

            if (gtime[0]!="12"){
                gtime[0]=gtime[0]*1+12;
            }
        }
        gtime[1]=gtime[1].replace("a","").replace("p","");

    }else{
        return get_time;
    }
    return gtime[0]+":"+gtime[1];

}

function changeTitleStr(s,at){
    if (s.charAt(at)=="H"){
        return "H";
    }else if(s.charAt(at)=="C"){
        return "A";
    }else if(s.charAt(at)=="N"){
        return "D";
    }
    return "";
}

//切換日期
function chg_gdate(obj){
    var obj_gdate = obj.getAttribute("value") ;
    var parentnode =obj.parentNode; // 获取父级
    var childlist = parentnode.children ;  // 获取子节点
    for(var i=0;i<childlist.length;i++){
        if(childlist[i] !=obj){
            childlist[i].className='' ;
        }
    }
    obj.className='choose_select' ;
    //parent.g_date=obj_gdate.value;
    parent.g_date=obj_gdate ;
    parent.pg=0;
    reload_var();
}

function lidURL(str){
    var showstr="";
    var strray=str.split('-');
    for(var i =0;i<strray.length;i++){
        if(strray[i]=="")continue;
        if(showstr!=""){
            showstr+="-";
        }
        showstr+=strray[i];
    }
    return showstr;
}
function get_pageparam(){
    if (choice=="") choice="ALL";
    if (!parent.LegGame) parent.LegGame="";
    if (!parent.pages) parent.pages=1;
    if (!parent.records) parent.records=-1;
    if ((parent.sel_league=="") || (""+parent.sel_league=="undefined"))parent.sel_league="";
    if ((parent.sel_item=="") || (""+parent.sel_item=="undefined"))parent.sel_item="";
    if ((parent.sel_area=="") || (""+parent.sel_area=="undefined"))parent.sel_area="";
    return parent.base_url+"&choice="+choice+"&LegGame="+parent.LegGame+"&pages="+parent.pages+"&records="+parent.records+"&FStype="+parent.FStype+"&area_id="+parent.sel_area+"&league_id="+parent.sel_league+"&rtype="+parent.rtype; //+"&item_id="+parent.sel_item
}

// 投注区域增加APP下载链接
function addAppDownLoad() {
    // console.log(parent.rtype)
    var margin_left = 850 ;
    if(parent.rtype=='pd' || parent.rtype=='rpd'){ // 波胆
        margin_left = 1045
    }
    var str = '<a href="https://hg6668.app" class="app_download" target="_blank" style="position: absolute;margin-left: '+margin_left+'; top: 10px;"><img src="../../../images/right_6668.jpg" alt="app下载"></a>' ;
    $('body').append(str) ;
}

// 查看赛果
function lookGameResult(gtype) {
    $('.to_game_result').on('click',function () {
        var result_url = '/app/member/result/result.php?game_type='+(parent.parent.mem_order?parent.parent.mem_order.head_gtype:gtype) ;
        window.open(result_url,"saiguo","width=800,height=700,status=no,location=no");
    });
}

// layer 弹窗提示
function bodyVarAlert(title) {
    // layer.msg(title,{time:2000});

    var str =  '<div class="game_play_all">' +
        '<span class="game_logo"></span>'+
        '<p > 请先登录账号，以便进行真钱投注。 </p>'+
        '<div class="game_center"> <a href="javascript:;" onclick="top.indexCommonObj.loadMemberLogin()"> 马上登录 </a> ' ;
    str +=  '</div>'+
        '<div class="game_bottom" onclick="top.indexCommonObj.loadMemberReg()"><span class="user_icon"></span> 没有账号？马上注册</div>'+
        '</div>' ;
    layer.open({
        type: 1
        ,skin: 'layui-layer-gameplay'
        // ,closeBtn:0
        , anim: 2 // 动画风格
        // ,offset: 'auto' //具体配置参考：offset参数项
        ,area: ['560px', '278px']
        ,content: str
        ,shadeClose: true
        ,shade: 0.5
        ,yes: function(){
            layer.closeAll();
        }
    });
}

// 加载注册
function loadMemberRegSport(val) {
    if(!val){
        val='';
    }
    layer.open({
        type: 2,
        skin: 'layui-layer-login', //样式类名
        title: '快速注册',
        shadeClose: true,
        shade:0.5,
        area: ['400px', '500px'],
        content: '/tpl/lobby/middle_member_reg.php?intr='+val // url
    });
}

