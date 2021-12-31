
// 赛事分日期
function selgdate(rtype,cdate,showtype){
    var date_opt = "";
    var arrDate =new Array();
    var year ='';
    var nowDate="";
    var $show_date_opt = document.getElementById("show_date_opt");

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
        if(cdate=='') { cdate ='ALL';}
        for (i = 0; i < arrDate.length; i++) {
            nowDate=showdate(arrDate[i]);
            if(showtype=='future'){ // 早盘
                date_opt += '<span value="'+arrDate[i]+'" onclick="chg_gdate(this)">'+nowDate+'</span>';
            }else{ // 今日赛事
                if(i == 0){
                    date_opt += '<span value="'+arrDate[0]+'" class="bet_date_color" onclick="chg_gdate(this)">'+nowDate+'</span>';
                }else{
                    date_opt += '<span value="'+arrDate[i]+'" onclick="chg_gdate(this)">'+nowDate+'</span>';
                }
            }
        }
        if(showtype=='future') { // 早盘
            date_opt+= '<span value="ALL" class="bet_date_color" onclick="chg_gdate(this)">'+top.alldata+'</span>';
        }else{
            date_opt+= '<span value="ALL" onclick="chg_gdate(this)">'+top.alldata+'</span>';
        }

    }else{
        arrDate=DateAry ;
        if(!cdate) {cdate ='ALL';}

        for (i = 0; i < arrDate.length; i++) {
            nowDate=showdate(arrDate[i]);
            if(showtype=='future'){ // 早盘
                date_opt+= '<span value="'+arrDate[i]+'" onclick="chg_gdate(this)">'+nowDate+'</span>';
            }else{ // 今日赛事
                if(i == 0){
                    date_opt+= '<span value="'+arrDate[0]+'" class="bet_date_color"  onclick="chg_gdate(this)">'+nowDate+'</span>';
                }else{
                    date_opt+= '<span value="'+arrDate[i]+'" onclick="chg_gdate(this)">'+nowDate+'</span>';
                }
            }
        }
        if(showtype=='future') { // 早盘
            date_opt += '<span value="ALL" class="bet_date_color" onclick="chg_gdate(this)">'+top.alldata+'</span>';
        }else{
            date_opt += '<span value="ALL" onclick="chg_gdate(this)">'+top.alldata+'</span>';
        }

    }
    if($show_date_opt){
        $show_date_opt.innerHTML = date_opt;
    }

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

//切換日期
function chg_gdate(obj){
    var parentnode =obj.parentNode; // 找父类
    var childlist = parentnode.children ;  // 节点集合
    for(var i=0;i<childlist.length;i++){
        if(childlist[i] !=obj){
            childlist[i].className='' ;
        }
    }
    obj.className='bet_date_color'; // 当前对象添加类
    parent.g_date=obj.getAttribute('value');
    parent.pg=0;
    reload_var();
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

