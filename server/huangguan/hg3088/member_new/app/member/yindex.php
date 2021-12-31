<?php
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "./include/address.mem.php";
echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
$str   = time();
$uid   = $_REQUEST['uid'];
$langx = $_REQUEST['langx'];
if ($uid==''){
	$uid=substr(md5($str),0,8);
}
if ($langx==''){
	$langx="zh-cn";
}
$swf="tw";
switch($langx){
case 'zh-cn':
	$a1="繁體版";
	$a2="<a href=".BROWSER_IP."/app/member/translate.php?set=zh-cn&url=".BROWSER_IP."/app/member/index.php&uid=$uid >简体版</a>";
	$a3="<a href=".BROWSER_IP."/app/member/translate.php?set=en-us&url=".BROWSER_IP."/app/member/index.php&uid=$uid >English</a>";
	$a4="<a href=".BROWSER_IP."/app/member/translate.php?set=th-tis&url=".BROWSER_IP."/app/member/index.php&uid=$uid >ภาษาไทย</a>";
	$size='250';
	$swf="tw";
	break;
case 'zh-cn':
	$a1="<a href=".BROWSER_IP."/app/member/translate.php?set=zh-cn&url=".BROWSER_IP."/app/member/index.php&uid=$uid >繁體版</a></td>";
	$a2="简体版";
	$a3="<a href=".BROWSER_IP."/app/member/translate.php?set=en-us&url=".BROWSER_IP."/app/member/index.php&uid=$uid >English</a>";
	$a4="<a href=".BROWSER_IP."/app/member/translate.php?set=th-tis&url=".BROWSER_IP."/app/member/index.php&uid=$uid >ภาษาไทย</a>";
	$size='250';
	$swf="cn";
	break;
case 'en-us':
	$a1="<a href=".BROWSER_IP."/app/member/translate.php?set=zh-cn&url=".BROWSER_IP."/app/member/index.php&uid=$uid >繁體版</a>";
	$a2="<a href=".BROWSER_IP."/app/member/translate.php?set=zh-cn&url=".BROWSER_IP."/app/member/index.php&uid=$uid >简体版</a>";
	$a3="English";
	$a4="<a href=".BROWSER_IP."/app/member/translate.php?set=th-tis&url=".BROWSER_IP."/app/member/index.php&uid=$uid >ภาษาไทย</a>";
	$size='300';
	$swf="us";
	break;
case 'th-tis':
	$a1="<a href=".BROWSER_IP."/app/member/translate.php?set=zh-cn&url=".BROWSER_IP."/app/member/index.php&uid=$uid >繁體版</a>";
	$a2="<a href=".BROWSER_IP."/app/member/translate.php?set=zh-cn&url=".BROWSER_IP."/app/member/index.php&uid=$uid >简体版</a>";
	$a3="<a href=".BROWSER_IP."/app/member/translate.php?set=en-us&url=".BROWSER_IP."/app/member/index.php&uid=$uid >English</a>"; 
	$a4="ภาษาไทย";
	$swf="us";
	$size='300';
	break;
}

require ("./include/traditional.$langx.inc.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link href="/style/member/index_new2_MX.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css">
<script>
if(self == top) location='/';
top.game_alert='';

</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8 "><script>

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
function show(){
    document.forms.LoginForm.username.focus(); 

    document.forms.LoginForm.username.value=top.nametop;
}

function close_flash(){
    document.all.show_flash.style.visibility='hidden';
}


function chk_acc(){
    top.nametop=document.all.username.value;
    
    document.all.JE.value = navigator.javaEnabled();
    if(document.all.username.value==""){
        hr_info.innerHTML=top.account;
        document.all.username.focus();
        return false;
    }
    if(document.all.passwd.value==""){
        hr_info.innerHTML=top.password;
        document.all.passwd.focus();
        return false;
    }
    
    
    
    //document.all.mac.value=top.aps.mac;
    //document.all.ver.value=top.aps.ver;
    return true;
            
}
/*
function set_img(){
    strChk_Number = (""+Math.random()).substr(2,4);
    intImg.innerHTML=strChk_Number;
    intDa= (1*strChk_Number) % 3 + 1;
    eval("document.getElementById('img_pic').background ='/images/member/chk_img0"+intDa+".gif'");
}
*/

</script>

    <script class="language_choose" type="text/javascript" src="../../js/zh-cn.js?v=<?php echo AUTOVER; ?>"></script>
</head>

<body onLoad="show();">
<div class="title"><img src="/images/member/index_title_<?php echo $swf?>.jpg" width="406" height="53" />
  <table class="table" cellpadding="0" cellspacing="0" border="0"><tr>
      <td class="lang2"><?php echo $a1?></td>
     <td class="lang"><?php echo $a2?></td>
      <td class="lang"><?php echo $a3?></td>
      <!--td class="lang"><a  href='http://hg3088.com/app/member/translate.php?set=th-tis&url=http://hg3088.com/app/member/index.php' >ภาษาไทย</a></td-->
    </tr>
</table>
</div>
<div class="mem"><div class="img"><font><?php if($swf=='us'){ ?>Try Our New Mobile Website<?php }else{ ?>全新掌上通<?php } ?></font></div></div>

<div class="log">
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><table width="250"  border="0" cellpadding="2" cellspacing="2" class="bord">
<form action="./login.php" method="post" name="LoginForm" onSubmit="return chk_acc();" >
                    <input type=HIDDEN name="uid" value="<?php echo $uid?>">
                    <input type=HIDDEN name="langx" value="<?php echo $langx?>">
            <input type=HIDDEN name="mac" value="">
            <input type=HIDDEN name="ver" value="">
                    <input type="hidden" name="JE" value="">
                    <tr>
                      <td height="40" colspan="3" align="left" ><!--font id="hr_info" color="#CC0000"--><img src="/images/member/index_logo2.png" width="134" height="31" /><span class="virus"><a href="/tpl/member/zh-cn/virus_site01.html" target="_blank">
                      <?php if($swf=='us'){ ?>
                      Anti-virus software
                      <?php }elseif($swf=='tw'){ ?>
                      防毒軟件設置說明
                      <?php }else{ ?>
                      防毒软件设置说明
                      <?php } ?>
                      </a></span></td>
            </tr>
                    <tr>
                      <td colspan="2" align="center" class="txt"><font id="hr_info" color="#CC0000"></font><br>
            You must sign in to use our service.</td>
            </tr>
                    <tr>
                      <td width="35%" align="right"><li><?php echo $username?></li></td>
                      <td align="left"><input type="text" name="username" size="15" class="za_text"></td>
                    </tr>
                    <tr>
                      <td align="right"><li><?php echo $password?></li></td>
                      <td align="left"><input type="password" name="password" size="15" class="za_text"> </td>
                    </tr>
                    <tr>
                      <td height="27" align="right">&nbsp;</td>
                      <td align="left"><input type="submit" value="<?php echo $submitok?>" class="za_text" ></td>
                    </tr>
          </form>
          </table>
<br />
          <table width="250" border="0" cellpadding="5" cellspacing="5" class="bord">
  <tr class="sub">
    <td colspan="2" align="center"><a href="#"><?php echo $index6 ?> </a></td>
    </tr>
  <tr class="txt">
    <td align="center"><a href="#"><?php echo $index7?>  </a></td>
    <td align="center"><a href="#"><?php echo $index8?> </a> </td>
  </tr>
</table>          </td>
  </tr>
</table>
</div>
<div class="foot"><?php echo $index9?> - <a href="#\"><?php echo $index10?></a> - <a href="#"><?php echo $index11?> </a>- <a href="#"><?php echo $index12?></a> - <a href="#"><?php echo $index13?></a>
</div>
</body>
</html>