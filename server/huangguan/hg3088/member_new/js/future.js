var ReloadTimeID="",sel_gtype=parent.sel_gtype;function onLoad(){""+eval("parent."+sel_gtype+"_lname_ary")=="undefined"&&eval("parent."+sel_gtype+"_lname_ary='ALL'"),""+eval("parent."+sel_gtype+"_lid_ary")=="undefined"&&eval("parent."+sel_gtype+"_lid_ary='ALL'"),""!=parent.ShowType&&"r"!=rtype||(parent.ShowType="OU"),"hr"==rtype&&(parent.ShowType="OU"),"re"==rtype&&(parent.ShowType="RE"),"pd"==rtype&&(parent.ShowType="PD"),"hpd"==rtype&&(parent.ShowType="HPD"),"t"==rtype&&(parent.ShowType="EO"),"f"==rtype&&(parent.ShowType="F"),"Y"==parent.parent.leg_flag&&(parent.parent.leg_flag="N",parent.pg=0,reload_var()),parent.loading="N","N"==parent.loading_var&&(parent.ShowGameList(),obj_layer=document.getElementById("LoadLayer"),obj_layer.style.display="none"),"Y"==parent.retime_flag&&(ReloadTimeID=setInterval("reload_var()",1e3*parent.retime)),"FU"==sel_gtype&&"hgft"!=top.showtype&&selgdate(rtype),document.getElementById("odd_f_window").style.display="none",futureShowGtypeTable()}function selgdate(e,t){var a="",n=new Array;if("hgft"==top.showtype){var o=DateAry[0].split("-");for(i=0;i<parent.hotgdateArr.length;i++){var r=parent.hotgdateArr[i].split("-");n=arraySort(n,(1*o[1]>1*r[0]?1*o[0]+1:o[0])+"-"+parent.hotgdateArr[i])}for(""==t&&(t="ALL"),a='<select id="g_date" name="g_date" onChange="chg_gdate()">',a+='<option value="ALL" '+("ALL"==t?"selected":"")+">"+top.alldata+"</option>",i=0;i<n.length;i++)a+='<option value="'+n[i]+'" '+(t==n[i]?"selected":"")+">"+n[i]+"</option>";a+="</select>"}else{for(n=DateAry,a='<select id="g_date" name="g_date" onChange="chg_gdate()">',a+='<option value="ALL" selected>'+top.alldata+"</option>",i=0;i<n.length;i++)a+='<option value="'+n[i]+'" >'+n[i]+"</option>";a+="</select>"}document.getElementById("show_date_opt").innerHTML=a}function arraySort(e,t){for(var a=new Array,n=new Array,o=0;o<e.length;o++)e[o]<=t?a.push(e[o]):n.push(e[o]);a.push(t);for(o=0;o<n.length;o++)a.push(n[o]);return a}function count_down(){if(setTimeout("count_down()",1e3),"Y"==parent.retime_flag){if(parent.retime<=0)return void("N"==parent.loading_var&&reload_var());parent.retime--,obj_cd=document.getElementById("cd"),obj_cd.innerHTML=parent.retime}}function reload_var(Level){if(parent.loading_var="Y","up"==Level)var tmp="./FT_future/body_var.php";else var tmp="./body_var.php";var l_id=eval("parent.parent."+sel_gtype+"_lid_type");"hgft"==top.showtype&&(l_id=3);var homepage=tmp+"?uid="+parent.uid+"&rtype="+parent.rtype+"&langx="+parent.langx+"&mtype="+parent.ltype+"&page_no="+parent.pg+"&g_date="+parent.g_date+"&league_id="+l_id+"&showtype="+top.showtype;parent.body_var.location=homepage,"r"==rtype&&(document.all.line_window.style.visibility="hidden")}function chg_gdate(){var e=document.getElementById("g_date");parent.g_date=e.value,parent.pg=0,reload_var()}function chg_pg(e){e!=parent.pg&&(parent.pg=e,reload_var())}function chg_league(){self.location="./body_var_lid.php?uid="+parent.uid+"&rtype="+parent.rtype+"&langx="+parent.langx+"&mtype="+parent.ltype+"&g_date="+parent.g_date}function show_more(e){document.all.line_window.style.position="absolute",document.all.line_window.style.top=document.body.scrollTop+event.clientY+12,document.all.line_window.style.left=document.body.scrollLeft+5,line_form.gid.value=e,line_form.uid.value=parent.uid,line_form.ltype.value=parent.ltype,line_form.submit()}function show_detail(){show_team=document.getElementById("table_team"),show_pd=document.getElementById("table_pd"),show_t=document.getElementById("table_t"),show_f=document.getElementById("table_f"),show_hpd=document.getElementById("table_hpd"),parent.ShowData_Other(show_team,show_pd,show_t,show_f,show_hpd,GameOther,top.odd_f_type),document.all.line_window.style.visibility="visible",document.all.line_window.focus()}function unload(){clearInterval(ReloadTimeID)}window.onunload=unload;