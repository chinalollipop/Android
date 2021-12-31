
function MM_swapImgRestore() { //v3.0
    var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
    var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
        var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
            if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.0
    var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
        d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
    if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
    for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
    if(!x && document.getElementById) x=document.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
    var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
        if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

var strChk_Number="";
if ((""+top.nametop)=="undefined") top.nametop="";
if ((""+top.selLang)=="undefined") top.selLang="web_new";
function show(){
    var strDomain = (document.domain).split(".");
    var strChkDomain="";
    var showVersion="N";
    for( var i=0;i<strDomain.length;i++){
        if(isNaN(strDomain[i])){
            showVersion="Y";
            break;
        }
    }
    if(showVersion=="N"){
        click_old();
        document.getElementById("vs_new").style.display="none";
        document.getElementById("vs_old").style.display="none";
    }
    window.onresize = scrollFun;
    document.forms.LoginForm.username.focus();
    document.forms.LoginForm.username.value=top.nametop;



}

// 判断是否有记住帐号
function getRmemberMe() {
    var username = getCookieAction('username') ;
    var password = getCookieAction('password') ;
    var ifremeber = getCookieAction('ifremeberme') ;
    // console.log(username) ;
    if(ifremeber){
        document.all.checkbox.checked = true;
    }
    if(username){
        document.all.username.value = username ;
    }
    if(password){
        document.all.password.value = password ;
    }

}

function chk_acc(){
    document.all.JE.value = navigator.javaEnabled();
    if(document.all.username.value==""){
        hr_info.innerHTML=top.account;
        document.all.username.focus();
        return false;
    }
    if(document.all.password.value==""){
        hr_info.innerHTML=top.password;
        document.all.password.focus();
        return false;
    }
    rememberMe() ;
    sessionStorage.setItem('m_type','') ; // 每次登录后清空
    sessionStorage.setItem('g_type','') ; // 每次登录后清空
    return true;
}

/*function click_new(){
    top.selLang="web_new";
    document.getElementById("lang_tis").style.display = "none";

    if(document.all.langx.value=="th-tis"){
        self.location.href="http://"+document.domain+"/app/member/translate.php?set=en-us&url=app/member/index.php";
    }
}*/

function click_old(){
    top.selLang="web_old";
    document.getElementById("lang_tis").style.display = "";
}

function scrollFun(){
    window.scroll(document.body.scrollWidth,0);
}

// 2018新增忘记密码
function openforgetpwd(){
    window.open('account/forget_psw.php','_blank','width=390px,height=650px,top=0,left=0px,titlebar=0,toolbar=0');
}

// 记住我的帐号
function rememberMe() {
    var ifremeber = document.all.checkbox.checked ;
    var username = document.all.username.value ;
    var password = document.all.password.value ;
    // console.log(ifremeber) ;
    // console.log(document.all.username.value) ;
    // console.log(document.all.password.value) ;
    if(ifremeber){
        setCookieAction('ifremeberme',ifremeber) ;
        setCookieAction('username',username) ;
        setCookieAction('password',password) ;
    }else {
        delCookieAction('ifremeberme') ;
        delCookieAction('username') ;
        delCookieAction('password') ;

    }
}

// 切换新旧版
function changeVersion(obj,htp) {
    var val = obj.getAttribute('value') ; // 1 旧版，2 新版
    var cls =obj.className ;
    var oldcls = cls.split(' ')[0] ;
    obj.classList.add('index_new_btn_on') ;
    obj.classList.remove('index_old_btn_out') ;
    var parlist =obj.parentNode ;
    var child = parlist.children ;
    for(var i=0;i<child.length;i++){
        if(child[i] !=obj){
            child[i].classList.remove('index_new_btn_on') ;
            child[i].classList.add('index_old_btn_out') ;
        }
    }
    // 域名处理
    var urlarr = window.location.host.split('.') ;
    var turl ;
    var lurl ;
    if(urlarr.length <3){ // 不带www 域名
        turl = urlarr[0] ; // 取第一位
        lurl = urlarr[1];
    }else{
        turl = urlarr[1] ;  // 取第二位
        lurl = urlarr[2] ;
    }
    if(val==1){ // 旧版
        //document.forms[0].setAttribute('action','http://www.'+turl+'.com/app/member/login.php') ;
        document.forms[0].setAttribute('action',htp+'://'+turl+'.'+lurl+'/login.php') ;
        document.forms[0].setAttribute('target','_parent') ;
    }else{ // 新版
        document.forms[0].setAttribute('action',htp+'://new.'+turl+'.'+lurl+'/app/member/login.php') ;
        document.forms[0].setAttribute('target','_self') ;
    }

}
