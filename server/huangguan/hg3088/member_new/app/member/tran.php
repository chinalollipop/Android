<?php
session_start();
require ("./include/config.inc.php");
$langx=$_SESSION['langx'];
$uid=$_REQUEST['uid'];
include "./include/address.mem.php";
require ("./include/traditional.$langx.inc.php");

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
	echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
	exit;
}

$sql = "select ID,Money,UserName from ".DBPREFIX.MEMBERTABLE." where Oid='$uid' and Status<=1";
$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);
$hgFund = $row['Money'];
$hgId = $row['ID'];
$UserName = $row['UserName'];

$cpMasterDbLink = @mysqli_connect($database['cpDefault']['host'],$database['cpDefault']['user'],$database['cpDefault']['password'],$database['cpDefault']['dbname'],$database['cpDefault']['port']) or die("mysqli connect error".mysqli_connect_error()) ;
$sql = "select lcurrency from ".$database['cpDefault']['prefix']."user where hguid=".$hgId;

$result = mysqli_query($cpMasterDbLink,$sql);
$row = mysqli_fetch_assoc($result);
//$cou = mysqli_num_rows($result);
//
//if($cou==0){
//	echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
//	exit;
//}
$cpFund = $row['lcurrency'];

?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="Robots" contect="none">
	<title></title>
	<link href="../../../style/member/index_login.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8 ">
    <style>
        .btn {width: 80%;}
        div.jbox .jbox-close, div.jbox .jbox-close-hover{background-position: 5px 2px;}
        #creditsChangeBox { padding-top:15px;overflow:hidden; }
        #creditsChangeBox p { margin:0px;padding:5px 15px;font-size:14px;font-weight:bold;color:#666;overflow:hidden; }
        #creditsChangeBox p input { width:50%;height:26px;text-indent:5px;border:1px solid #c6c6c6;font-size:18px;box-shadow:inset 1px 1px 4px rgba(0,0,0,0.1);border-radius:4px; }
        #creditsChangeBox p input:hover { border-color:#b10000; }
        #creditsChangeBox p select { padding:3px;height:28px;width:100px;text-indent:5px;border:1px solid #c6c6c6;font-size:14px;box-shadow:inset 1px 1px 4px rgba(0,0,0,0.1);border-radius:4px; }
        #creditsChangeBox span { color:#b10000;font-size:16px; }
        #creditsChangeBox div { margin-top:15px;padding:10px 15px;overflow:hidden; }
        #creditsChangeBox div input { transition:background 0.3s ease;float:left;cursor:pointer;border:0px;width:48%;height:34px;background:#b98e2f;font-size:14px;font-weight:bold;color:#fff;box-shadow:inset 1px 1px 1px rgba(0,0,0,0.3);border-radius:35px; }
        #creditsChangeBox div input:hover { background:#b10000; }
        #creditsChangeBox div input#btnClose { float:right;background:#676767; }
        #creditsChangeBox div input#btnClose:hover { background:#b10000; }
    </style>
</head>
<body  onLoad="show();">
<div id="jbox-states">
    <div id="jbox-state-state0" class="jbox-state" style="display: block;">
        <div style="min-width:50px;width:400px; height:auto;">
            <div id="jbox-content-loading" class="jbox-content-loading" style="min-height: 70px; height: auto; text-align: center; display: none;">
                <div class="jbox-content-loading-image" style="display:block; margin:auto; width:220px; height:19px; padding-top: 25px;">

                </div>
            </div>
            <div id="jbox-content" class="jbox-content" style="height: auto; overflow-x: hidden; overflow-y: auto; position: static; left: -10000px;">

                <!--额度转换-->
<div id="creditsChangeBox">
		<p>帐号额度：<span id="user_blance"><?php echo $hgFund;?></span></p>
		<p>彩票余额：<span id="cp_blance"><?php echo $cpFund;?></span></p>
		<p><label>转换类型：</label>
			<select name="f_blance" id="f_blance" onchange="f_t('f_blance');">
				<option value="hg" selected="selected">体育余额</option>
				<option value="cp">彩票余额</option>
			</select>转 <select name="t_blance" id="t_blance" onchange="f_t('t_blance');">
				<option value="hg">体育余额</option>
				<option value="cp" selected="selected">彩票余额</option>
			</select>
		</p>
		<p><label>转换金额：</label><input type="number" name="blance" id="blance" value="" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')"></p>
		<div class="btn">
			<input type="button" name="trans_blance" id="trans_blance" value="提交转换">
			<input type="button" name="btnClose" id="btnClose" value="我不转了" onclick="$('.jbox-body').remove()" >
		</div>
</div>
</div>
        </div>
        <div class="jbox-button-panel" style="height:25px;padding:5px 0 5px 0;text-align: right;display:none;"><span class="jbox-bottom-text" style="float:left;display:block;line-height:25px;"></span></div>
    </div>
</div>

<script class="language_choose" type="text/javascript" src="../../js/zh-cn.js?v=<?php echo AUTOVER; ?>"></script>
<script charset="utf-8" type="text/javascript" src="../../js/jquery.js" ></script>
<script type="text/javascript" src="../js/jbox/jquery.jBox-2.3.min.js"></script>
<script type="text/javascript" src="../js/jbox/jquery.jBox-zh-CN.js"></script>
<script>
$(function(){
	$("#trans_blance").click(function(){
		var f_blance = $("#f_blance option:selected").val(); 
		var t_blance = $("#t_blance option:selected").val(); 
		var blance = $("input[name='blance']").val();
				
		if(f_blance.length==0 || t_blance.length==0 || blance.length==0 ){
			alert('参数不能为空');
			return false;
		}
		if( f_blance == t_blance ){
			alert("参数错误，转换失败：出款方与入款方相同");
			return false;
		}
		
		//缺少转换金额效验
		var id = "<?php echo $hgId;?>";
		var uid = "<?php echo $uid;?>";
		var userName = "<?php echo $UserName;?>";

		var hgFund = "<?php echo $hgFund;?>";
		var cpFund = "<?php echo $cpFund;?>"; 

		if(f_blance=='hg' && (hgFund==0||hgFund<0)){	alert("转出方资金不足！");return false;	}
		if(f_blance=='cp' && (cpFund==0||cpFund<0)){	alert("转出方资金不足！");return false;	}
		
		$.ajax({
			  type: 'POST',
			  url: '/app/member/ajaxTran.php',
			  data:{id:id,uid:uid,userName:userName,action:'fundLimitTrans',from:f_blance,to:t_blance,fund:blance},
			  dataType: 'json',
			  success:function(res){
				if(res.status==0){
					alert("转账成功,请查看余额！");
                    $('.jbox-body').remove();
					window.location.reload();
				}else{
                    alert(res.message);
                    $('.jbox-body').remove();
                }
			  } 
		});
		
	})
})

function f_t(obj){
	if(obj=='f_blance'){
		var valCur = $("#f_blance").find("option:selected").val();
		if(valCur=="hg"){	$("#t_blance").find("option[value='cp']").attr("selected",true); 	}
		if(valCur=="cp"){	$("#t_blance").find("option[value='hg']").attr("selected",true); 	}
	}
	if(obj=='t_blance'){
		var valCur = $("#t_blance").find("option:selected").val();
		if(valCur=="hg"){	$("#f_blance").find("option[value='cp']").attr("selected",true); 	}
		if(valCur=="cp"){	$("#f_blance").find("option[value='hg']").attr("selected",true); 	}
	}
}

</script>  
</body>
</html>