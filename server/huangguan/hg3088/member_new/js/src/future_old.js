
//-----------------------------future------------------------
function selgdate(rtype,cdate,showtype){
    //賽事分日期
    var date_opt = "";
    var arrDate =new Array();
    var year ='';
    var nowDate="";
    if(top.showtype=='hgft'){
        var tmpdate=DateAry[0].split("-");
        for (i = 0; i < parent.hotgdateArr.length; i++) {
            var tmpd =parent.hotgdateArr[i].split("-");
            if(tmpdate[1]*1 > tmpd[0]*1){
                year =tmpdate[0]*1+1;
            }else{
                year =tmpdate[0];
            }
            arrDate =arraySort1(arrDate,year+'-'+parent.hotgdateArr[i]);
        }
        if(cdate=='')cdate ='ALL';
        // date_opt = '<select id="g_date" name="g_date" onChange="chg_gdate()">';  day_text_red
        // date_opt+= '<option value="ALL" '+((cdate =='ALL')?'selected':'')+'>'+top.alldata+'</option>';
        for (i = 0; i < arrDate.length; i++) {
            nowDate=showdate(arrDate[i]);
            // date_opt+= '<option value="'+arrDate[i]+'" '+((cdate ==arrDate[i])?'selected':'')+'>'+nowDate+'</option>';
            if(i==0){ // 今日
                date_opt +='<span value="'+arrDate[0]+'" onclick="chg_gdate(this)" class="choose_select">'+今日+'</span> ' ;
            }else{
                date_opt +='<span value="'+arrDate[i]+'" onclick="chg_gdate(this)" >'+nowDate+'</span> ' ;
            }
        }
        //  date_opt+= "</select>";
    }else{
        arrDate=DateAry ;
       // console.log(DateAry);
        // date_opt = "<select id=\"g_date\" name=\"g_date\" onChange=\"chg_gdate()\">";
        // date_opt+= "<option value=\"ALL\">"+top.alldata+"</option>";
        /*if (rtype == "r" || rtype == "all") {
            // date_opt+= "<option value=\"1\" >"+top.S_EM+"</option>";
            date_opt +='<span value="1" onclick="chg_gdate(this)" class="choose_select">'+top.S_EM+'</span> ' ; // 特早
        }*/
        for (i = 0; i < arrDate.length; i++) {
            nowDate=showdate(arrDate[i]);
            if(i==0 && (top.showtype=='' || top.showtype=='today')){ // 今日
                date_opt +='<span value="'+arrDate[0]+'" onclick="chg_gdate(this)" class="choose_select">今日</span> ' ;
            }else{
                date_opt +='<span value="'+arrDate[i]+'" onclick="chg_gdate(this)" >'+nowDate+'</span> ' ;
            }
            // date_opt+= "<option value=\""+arrDate[i]+"\" >"+nowDate+"</option>";

        }
        // date_opt+= "</select>";
    }
    if(top.showtype=='future'){
        date_opt +='<span value="ALL" onclick="chg_gdate(this)" class="choose_select">'+top.alldata+'</span> ' ;
    }else{ // 今日赛事
        date_opt +='<span value="ALL" onclick="chg_gdate(this)" >'+top.alldata+'</span> ' ;
    }
    document.getElementById("show_date_opt").innerHTML = date_opt;
}
function showdate(sdate){
    var showgdate=sdate.split("-");
    tmpsdate=showgdate[1]+"-"+showgdate[2];
    if(top.langx=="zh-cn"||top.langx=="zh-cn") {
        if((showgdate[1]*1)< 10) showgdate[1]=showgdate[1]*1;
        if((showgdate[2]*1)< 10) showgdate[2]=showgdate[2]*1;
        tmpsdate=showgdate[1]+top.showmonth+showgdate[2]+top.showday;
    }
    return tmpsdate;
}
function arraySort1(array ,data){
    var outarray =new Array();
    var newarray =new Array();
    for(var i=0;i < array.length ;i++){
        if(array[i]<= data){
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

//切換日期
function chg_gdate(obj){
    // var obj_gdate = document.getElementById("g_date");
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

//====== 取表格 TD 的x軸
function GetTD_X(TD_lay,GetTableID){
    alert(GetTableID);
    alert(document.getElementById(GetTableID))
    var TBar = document.getElementById(GetTableID);
    var td_x = TD_lay;
    for(var i=0; i < TBar.rows[0].children.length; i++){
        if (i == TD_lay) { break; }
        td_x += TBar.rows[0].children[i].clientWidth;
    }
    return td_x;
}
//====== 取表格 TD 的y軸
function GetTD_Y(AryIndex,GetTableID){
    var TBar = document.getElementById(GetTableID);
    var td_y = parseInt(AryIndex)+2;

    for(var i=0; i <= parseInt(AryIndex)+1; i++){
        try{
            td_y += TBar.rows[i].clientHeight;
        } catch (E){
            td_y += TBar.rows[i-1].clientHeight;
        }
    }
    return td_y;
}


