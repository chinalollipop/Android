<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include("../include/address.mem.php");
require("../include/config.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

$loginid = $_SESSION['ID'];
$loginname=$_SESSION['UserName'];
$langx=$_SESSION["langx"];
require ("../include/traditional.$langx.inc.php");

$uid=$_REQUEST["uid"];  //当前子账号uid
$lv=$_REQUEST["lv"]; //当前子账号Level = M
$id=$_REQUEST['id']; //当前子账号id

$upd_id=$_REQUEST['upd_id']; //当前修改子账号id

// 将设置的线下银行信息插入到管理员表中，用于存款()笔 页面显示
if($_REQUEST['act'] == 'bankSetingEdit' && !empty($_REQUEST['bankids']) && !empty($upd_id)){
    $bankids = implode(',',$_REQUEST['bankids']); //array 转成string
    $mysql="update ".DBPREFIX."web_system_data set Bank_competence='".$bankids."' WHERE ID ='$upd_id'";
//    echo $mysql;
    $result = mysqli_query($dbMasterLink,$mysql);
    if($result){
        echo json_encode(["status"=>1,"message"=>"线下银行分配成功！"]);
        //echo json_encode(array('status'=>1,'message'=>'线下银行分配成功!'),JSON_UNESCAPED_UNICODE);
    }else{
        echo json_encode(["status"=>0,"message"=>"系统繁忙,请重新分配!"]);
    }
    exit;
}


$array_name=array(2=>"银行卡",4=>"微信",5=>"支付宝",6=>"QQ扫码");
$bank_code=array("USDT"=>"USDT虚拟货币","KSCZ"=>"快速充值","NCSYYH" => "农村商业银行", "CSSYYH" => "城市商业银行", "NCXYS" => "农村信用社",
    "YLSMYSF" => "银联扫码|云闪付", "ZFB" => "支付宝", "WXSAOMA" => "微信扫码", "ALISAOMA" => "支付宝扫码", "ICBC" => "工商银行", "ABC" => "农业银行", "CCB" => "建设银行", "BOCO" => "交通银行", "BOC" => "中国银行", "CEBB" => "光大银行", "CMBC" => "民生银行", "POST" => "中国邮政", "CMB" => "招商银行", "CIB" => "兴业银行", "CCCB" => "中信银行", "GDB" => "广发银行", "SPDB" => "浦发银行", "HXB" => "华夏银行", "PAB" => "平安银行", "SHB" => "上海银行", "BJB" => "北京银行", "ZZB" => "郑州银行",
    "JSBC" => "江苏银行","JXBC" => "江西银行", "LZBC" => "兰州银行","GLB" => "桂林银行","TACCB" => "泰安银行","QLBANK" => "齐鲁银行","HFBank" => "恒丰银行","LZCCB" => "柳州银行",
);

// 获取当前子账号线下银行权限，赋值给页面
$bank_mysql = "select ID,Bank_competence from ".DBPREFIX."web_system_data where ID=$id";
//echo $bank_mysql;
$bank_result = mysqli_query($dbLink,$bank_mysql);
$bank_row = mysqli_fetch_assoc($bank_result);
$sub_bank_competence=explode(",",$bank_row['Bank_competence']);

$sql = "select *  from ".DBPREFIX."gxfcy_bank_data";
$res = mysqli_query($dbLink,$sql);
$lists = array();
while ($row = mysqli_fetch_assoc($res)){
    $row['class']=explode(',',$row['class']);
    $lists[$row['id']] = $row;
}


?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <title>子账号入款银行配置</title>
    <style>
        input.za_text_auto {width: 150px;}
    </style>
</head>
<body >
<dl class="main-nav">
    <dt>入款银行配置 <a href="?uid=<?php echo $uid?>&langx=<?php echo $langx?>&type=Y"></a></dt>
    <dd>
        <div class="header_info"><a href="javascript:history.back(-1);" >回上一页</a></div>
    </dd>
</dl>
<div class="main-ui">
    <table  class="m_tab">
        <form name="myform" action="" method="post">
        <tr class="m_title">
            <td>ID</td>
            <td>银行名称</td>
            <td>银行账号</td>
            <td>开户名</td>
            <td>支行地址</td>
            <td>状态</td>
        </tr>
    <?php
    foreach ($lists as $value){
    ?>
        <tr  class=m_cen>
            <td><?php echo $value['id'];?></td>
            <td><?php echo $bank_code[$value['bankcode']];?></td>
            <td><input type="text" class="za_text_auto bank_account_<?php echo $value['id']?>" value="<?php echo $value['bank_account'];?>"></td>
            <td><input type="text" class="za_text_auto bank_user_<?php echo $value['id']?>" value="<?php echo $value['bank_user'];?>"></td>
            <td><input type="text" class="za_text_auto bank_addres_<?php echo $value['id']?>" value="<?php echo $value['bank_addres'];?>"></td>
            <td><input type="checkbox" id="sts_<?php echo $value['id']?>" name="checkbank" value="<?php echo $value['id']?>"
                    <?php
                    // 如果子账号有线下银行，选中
                    if(in_array($value['id'],$sub_bank_competence)){ echo "checked"; }else{echo "";}
                    ?>>
            </td>
        </tr>
    <?php
    }
    ?>
        <tr class=m_cen >
            <td colspan="11">
                <input type="button" class="za_button" id="bankSetingOK"   name="Seting" value="设置" />
                <input type="button" class="za_button btn2" onclick="javascript:history.go(-1)" name="Reset" value="重置"/>
            </td>
        </tr>
    </form>
    </table>

    <div id=acc_window class="line_type_width" style="display:none;position:absolute">
    </div>
</div>
<script type="text/javascript" src="../../../js/agents/jquery.js"></script>
<script type="text/javascript">

    $('#bankSetingOK').bind('click', function() {
    // function bankSetingOK(uid) {
        var bankkuang = new Array();
        $("input[name='checkbank']").each(function(){
            if(this.checked){
                bankkuang.push(this.value);
            }
        });
        if(bankkuang.length>0){
            var uid = "<?php echo $uid;?>";
            var langx = "<?php echo $langx;?>";
            var lv = "<?php echo $lv;?>";
            // bankKuangStr=JSON.stringify(bankkuang);
            var upd_id = "<?php echo $id;?>";
            var bankids=bankkuang;

            $.ajax({
                type: 'POST',
                url: 'bank_setting.php',
                data:{uid:uid,act:'bankSetingEdit',upd_id:upd_id,bankids:bankids},
                dataType: 'json',
                success:function(res){
                    console.log(res);
                    if(res.status==1){
                        console.log('11');
                        // alertComing(res.message);
                        alert(res.message);
                        // window.location.href = 'subuser.php';
                        window.location.href = "./subuser.php?uid="+uid+"&langx="+langx+"&lv="+lv;
                    }else{

                        console.log('22');
                        alert(res.message);
                        // alertComing(res.message);
                    }
                }
            });
        }else{
            alert('请勾选线下银行信息！');
        }
    // }
    });

	function close_win() {
		document.all["acc_window"].style.display = "none";
	}

	$('#all').bind('click', function() {
		if (this.checked){    
	        $("input[name='userLevel']:checkbox").each(function(){   
	              $(this).attr("checked", true);    
	        });  
	    } else {     
	        $("input[name='userLevel']:checkbox").each(function() {     
	              $(this).attr("checked", false);    
	        });  
	    }   
	});
</script>
</body>
</html>