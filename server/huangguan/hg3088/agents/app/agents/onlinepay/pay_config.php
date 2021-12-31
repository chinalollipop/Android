<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include("../include/address.mem.php");
// echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
require_once("../include/config.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

$uid=$_REQUEST["uid"];
$langx=$_SESSION["langx"];
$loginname=$_SESSION['UserName'];


//@error_log(serialize($_REQUEST).PHP_EOL, 3, '/tmp/pay_config.log');
// 调通后编写功能  新增、修改、删除
$type=$_REQUEST['type'];  // add   edit  delete
$che=$_REQUEST['chk'];   // 要修改的内容

$id=$_REQUEST['id'];
$title=$_REQUEST['title'];
$account_company=$_REQUEST['adname'];
$business_code=$_REQUEST['code'];
$business_pwd=$_REQUEST['pwd'];
$url=$_REQUEST['url'];

//$minCurrency=bcmul(floatval($_REQUEST['minCurrency']), 1, 2);
$minCurrency=bcmul(floatval($_REQUEST['minCurrency']), 1);
$maxCurrency=bcmul(floatval($_REQUEST['maxCurrency']), 1);
$depositNum=intval($_REQUEST['depositNum']);
$status=$_REQUEST['sts'];
$has_company_youhui=$_REQUEST['has_company_youhui'];
$pay_id=$_REQUEST['pay_id'];
$thirdpay_code=$_REQUEST['thirdpay_code'];
//print_r($_REQUEST); die;

switch ($type){
    case 'del':
        $sql = "delete from `".DBPREFIX."gxfcy_pay` WHERE `id` = {$id}";
        $res = mysqli_query($dbMasterLink,$sql);
        $res ? false : true;
        break;
    case 'edit':
        $sql = "UPDATE `".DBPREFIX."gxfcy_pay` SET `business_code`='{$business_code}', `business_pwd`='{$business_pwd}', `url`='{$url}', `minCurrency`='{$minCurrency}', `maxCurrency`='{$maxCurrency}',`depositNum`='{$depositNum}',`pay_id`='{$pay_id}', `status`='{$status}', `has_company_youhui`='{$has_company_youhui}' WHERE `id` = {$id}";
        $res = mysqli_query($dbMasterLink,$sql);
        !$res ? false : true;
        break;
    case 'add':
        $sql = "insert `".DBPREFIX."gxfcy_pay` values ('','{$title}','{$account_company}','{$business_code}','{$business_pwd}','{$url}','{$minCurrency}','{$maxCurrency}','{$depositNum}','{$status}','a,d,e,f,b,c','{$pay_id}','{$thirdpay_code}',0,'{$has_company_youhui}')";
        $res=mysqli_query($dbMasterLink,$sql) or die(mysqli_connect_error());
        echo "<script> alert('添加成功！'); </script>";
        echo "<meta http-equiv='Refresh' content='0;URL=pay_config.php?uid=$uid'>";
        break;
    default: break;
}

$sql = "select *  from ".DBPREFIX."gxfcy_pay";
$res = mysqli_query($dbLink,$sql);
$lists = array();
while ($row = mysqli_fetch_assoc($res)){
    $row['class']=explode(',',$row['class']);
   $lists[$row['id']] = $row;
}
$listsJson = json_encode($lists);
$sqlUlevel = "select * from ".DBPREFIX."gxfcy_userlevel order by sort asc";
$resUlevel = mysqli_query($dbLink,$sqlUlevel);
while($row = mysqli_fetch_assoc($resUlevel)){
    $userLevelArr[$row['ename']] = $row;
}

?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <title>第三方支付配置</title>
<style>
    .list-tab td{line-height: 20px;}
    .list-tab input{ float: left;}
    input.za_text_auto {width: 150px;}
    .xz_table td{ width: 130px;}
</style>
</head>
<body>
<dl class="main-nav"><dt>第三方支付配置</dt><dd></dd></dl>
<div style="overflow: auto;">
    <table  class="m_tab">
        <tr  class="m_title" >
            <td width="100">名称</td>
            <td width="100">第三方支付公司名称</td>
            <td width="50">类型</td>
            <td width="150">商户号</td>
            <td width="150">商户密匙</td>
            <td width="150">终端号</td>
            <td width="80">送彩金比例</td>
            <td width="150">返回网址</td>
            <td width="80">最低额</td>
            <td width="80">最高额</td>
            <td width="80">存款次数</td>
            <td width="100">开放层级</td>
            <td width="40">状态</td>
            <td width="40">是否开启公司入款</td>
            <td width="100">操作</td>
        </tr>
    <?php
    $array_name=array(2=>"银行卡",4=>"微信",5=>"支付宝",6=>"QQ扫码");
    $thirdpay_code=array('xft'=>'信付通','jbf'=>'聚宝付','bf'=>'宝付','ddb'=>'多得宝','hfb'=>'汇付宝','lfu'=>'lfu','mb'=>'摩宝','mby'=>'魔宝云','mf'=>'秒付','rh'=>'融合','rx'=>'仁信','sf'=>'闪付','syx'=>'商银信','wfu'=>'w付','xfu'=>'星付','xh'=>'信汇',
        'yb'=>'易宝','yyh'=>'易云汇','zf'=>'智付','zhf'=>'智汇付','fkt'=>'福卡通','sft'=>'顺付通','db'=>'得宝','zrb'=>'智融宝','flg'=>'菲利谷','zb'=>'众宝','wdf'=>'维多付','clzldz'=>'村里最靓的仔','csj'=>'创世纪','ccx'=>'璀璨星','autopay'=>'AutoPay',
        'xingchen'=>'星辰',);
    foreach ($lists as $k=>$value){
    ?>
        <tr class=m_cen value="<?php echo $value['id']?>" style="text-align: center;">
            <td width="100"><?php echo $value['title'];?></td>
            <td><?php echo $thirdpay_code[$value['thirdpay_code']];?></td>
            <td>
                <?php
                foreach ($array_name as $key=>$val){
                    if($key == $value['account_company']) {
                        echo $val;
                    }
                }
                ?>
            </td>
            <td><input type="text" class="za_text_auto business_code_<?php echo $value['id']?>" value="<?php echo $value['business_code']?>" /></td>
            <td><input type="text" class="za_text_auto business_pwd_<?php echo $value['id']?>" value="<?php echo $value['business_pwd']?>" /></td>
            <td><input type="text" class="za_text_auto pay_id_<?php echo $value['id']?>" value="<?php echo $value['pay_id']?>" /></td>
            <td><?php echo $value['bonus_prize_percent']/100; ?></td>
            <td><input type="text" class="za_text_auto url_<?php echo $value['id']?>" value="<?php echo $value['url']?>" /></td>
            <td><input type="text" size="7"  class="minCurrency_<?php echo $value['id']?>" value="<?php echo bcmul(floatval($value['minCurrency']), 1)?>" /></td>
            <td><input type="text" size="7"  class="maxCurrency_<?php echo $value['id']?>" value="<?php echo bcmul(floatval($value['maxCurrency']), 1)?>" /></td>
            <td><input type="text" size="7"  class="depositNum_<?php echo $value['id']?>" value="<?php echo $value['depositNum']?>" /></td>
            <td>
                <?php
                    $classnum = array();
                    foreach($value['class'] as $val){ ?>
                    <p><?php
                        echo $userLevelArr[$val]['name'];
                        ?>
                    </p>
                <?php }?>
            </td>
            <td>
                <input type="checkbox" name="sts" ID="sts_<?php echo $value['id']?>" <?php echo $value['status']==1?"checked":"";?> >
            </td>
            <td>
                <input type="checkbox" name="has_company_youhui" ID="hcy_<?php echo $value['id']?>" <?php echo $value['has_company_youhui']==1?"checked":"";?> >
            </td>
            <td>
                <input type="button" data="<?php echo $value['id'];?>" class="levelmanage za_button" onClick="show_win(<?php echo $value['id'];?>);" value="层级管理" />
                <input type="button" class="za_button btn_edit_<?php echo $value['id']?>" onclick="btn_edit(<?php echo $value['id']?>,'<?php echo $uid?>','<?php echo $langx?>','<?php echo $loginname?>')" value="修改" />
                <input type="button" onclick="btn_del(<?php echo $value['id']?>,'<?php echo $uid?>')" value="删除" />
            </td>
        </tr>
    <?php
    }

    ?>
        <tr class=m_cen >
            <td colspan="14">
                <input type="button" value="取消" class="za_button btn2" onclick="javascript:history.go(-1)" />
                <input type="button" class="za_button" onclick="javascript:$('#adds').show();" value="新增" />
            </td>
        </tr>
    </table>

    <div id="adds" style="display: none;">
        <div class="connects">
            <form id="newsadd" method="post" action="">
                <input type="hidden" name="uid" value="<?php echo $uid?>" />
                <input type="hidden" name="langx" value="<?php echo $langx?>" />
                <input type="hidden" name="type" value="add" />
                <table class="m_tab xz_table">
                    <tbody><tr><td>名称</td><td>第三方支付名称</td><td>类型</td><td>商户号</td><td>商户密匙</td><td>终端号</td><td>返回网址</td>
                        <td >最低额 / 最高额</td><td>启用状态</td><td>公司入款优惠否</td></tr>
                    <tr>
                        <td><input class="inp1" type="text" name="title"></td>
                        <td>
                            <select name="thirdpay_code">
                                <?php
                                foreach ($thirdpay_code as $key=>$val){
                                    echo "<option value=\"$key\" >$val</option>";
                                }
                                ?>
                            </select>
                        </td>
                        <td>
                            <select name="adname">
                                <?php
                                foreach ($array_name as $key=>$val){
                                    echo "<option value=\"$key\" >$val</option>";
                                }
                                ?>
                            </select>
                        </td>
                        <td><input class="za_text_auto" type="text" name="code"></td>
                        <td><input class="za_text_auto" type="text" name="pwd"></td>
                        <td><input class="za_text_auto" type="text" name="pay_id"></td>
                        <td><input class="za_text_auto" type="text" name="url"></td>
                        <td><input class="za_text_auto" type="text" name="minCurrency">
                            <input class="za_text_auto" type="text" name="maxCurrency" style="margin-top: 10px;">
                        </td>
                        <td><input type="checkbox" name="sts" value="1"></td>
                        <td><input type="checkbox" name="has_company_youhui" value="1"></td>
                    </tr>
                    <tr class=m_cen >
                        <td colspan="11">
                            <input type="button" value="新增" class="za_button btn2" onclick="javascript:$('#newsadd').submit();">
                            <input type="button" value="取消" class="za_button btn2" onclick="javascript:$('#adds').hide();">
                        </td>
                    </tr>
                    </tbody>
                </table>

            </form>
        </div>
    </div>

    <div id=acc_window  class="line_type_width" style="display:none;position:absolute">
    <FORM name="addUSER" action="" method=post target="_self" >
        <table class="list-tab">
              <tr >
                <td id="r_title" colspan="2" >
                    层级管理
                    <a class="close_window" onClick="close_win()"><img src="/images/agents/top/edit_dot.gif" width="16" height="14"></a>
                </td>
              </tr>
              <tr>
                <td align="left"><input type=checkbox name=userLevelAll value="all" id="all" class="za_text" >全部</td>
              </tr>
              <?php
                foreach($userLevelArr as $k=>$v){
              ?>
              <tr>
                <td align="left"><input type=checkbox name="userLevel" flag="<?php echo $v['ename'];?>" value=<?php echo $v['ename'];?>  class="za_text" ><?php echo $v['name']; ?></td>
              </tr>
              <?php
                }
              ?>
              <tr>
              <td>
                    <input type="button" id="bankEditOK" name="acc_ok" value="确定" class="za_button">
                    <input type="hidden" name="bankId" />
              </td>
              </tr>

        </table>
    </FORM>
    </div>

</div>

<script type="text/javascript" src="../../../js/agents/jquery.js"></script>

<script type="text/javascript">
    function btn_del(id,uid) {
        var type = 'del';
        $.ajax({
            type: "POST",
            url:"pay_config.php",
            data:{id:id,uid: uid,type:type},
            success:function (data) {
                if (data){
                    alert('更新成功！');
                    window.location.href='pay_config.php?uid='+uid;
                }else{
                    alert('更新失败！！');
                }
            }
        });
    }

    function btn_edit(id,uid,langx,loginname) {
        var type = 'edit';
        var business_code = $(".business_code_"+id).val();
        var business_pwd = $(".business_pwd_"+id).val();
        var pay_id = $(".pay_id_"+id).val();
        var url = $(".url_"+id).val();
        var minCurrency = $(".minCurrency_"+id).val();
        var maxCurrency = $(".maxCurrency_"+id).val();
        var depositNum = $(".depositNum_"+id).val();
        var obj = document.getElementById("sts_"+id);
        var sts = 0;
        if(obj.checked){
            sts = 1;
        }
        var obj_hcy = document.getElementById("hcy_"+id);
        var has_company_youhui = 0;
        if(obj_hcy.checked){
            has_company_youhui = 1;
        }

        // 异步请求更新数据
        $.ajax({
            type:"POST",
            url:"pay_config.php",
            data:{
                id: id,
                uid: uid,
                langx: langx,
                loginname: loginname,
                type: type,
                code: business_code,
                pwd: business_pwd,
                pay_id: pay_id,
                url: url,
                minCurrency:minCurrency,
                maxCurrency:maxCurrency,
                depositNum:depositNum,
                sts: sts,
                has_company_youhui: has_company_youhui
            },
            success:function(data) {
                if (data){
                    alert('更新成功！');
                    window.location.href='pay_config.php?uid='+uid;
                }else{
                    alert('更新失败！！');
                }
            }
        })
    }

	function show_win(oid) {
		acc_window.style.top=document.body.scrollTop+event.clientY+15;
		acc_window.style.left=document.body.scrollLeft+event.clientX-233;
		document.all["acc_window"].style.display = "block";
		var data = <?php echo $listsJson;?>;

		$("input[name='userLevel']:checkbox").each(function() {     
            $(this).attr("checked", false);    
      	});
      	
		$.each(data[oid]['class'], function(index, val) {
			  $("input[flag='"+val+"']").attr("checked", true); 
		});
		
		$("input[name='bankId']").attr('value',oid);     
        
	}

	function close_win() {
		document.all["acc_window"].style.display = "none";
	}

	$('#bankEditOK').bind('click', function() {
		var kuang = new Array();
		$("input[name='userLevel']").each(function(){ 
			if(this.checked){
				kuang.push(this.value);
			}  
        }); 
		
		if(kuang.length>0){
			var bankId = $("input[name='bankId']").val();
			var uid = "<?php echo $uid;?>"; 
			kuangStr=JSON.stringify(kuang);
			
			$.ajax({
				  type: 'POST',
				  url: '../ajax.php',
				  data:{uid:uid,act:'bankLevelEdit',type:1,level:kuangStr,bid:bankId},
				  dataType: 'json',
				  success:function(res){
					alert(res.message);	
					if(res.status==0){
						close_win();
						window.location.reload();
					}
				} 
			});	
		}else{
			alert('请勾选用户层级！');
		}
	});

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


