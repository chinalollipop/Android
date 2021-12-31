<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include("../include/address.mem.php");
// echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
require("../include/config.inc.php");


checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$langx=$_SESSION["langx"];
$loginname=$_SESSION['UserName'];
require ("../include/traditional.$langx.inc.php");

$uid=$_REQUEST["uid"];
$lv=$_REQUEST["lv"];
// 调通后编写功能  新增、修改、删除
$type=$_REQUEST['type'];  // add   edit  del
$che=$_REQUEST['chk'];   // 要修改的内容
$id=$_REQUEST['id'];
$bank_code_key = $_REQUEST['bank_code'];
$bank_context = $_REQUEST['bank_context'];
$class= $_REQUEST['class'];
$bank_user = $_REQUEST['bank_user'];
$bank_account = $_REQUEST['bank_account'];
$bank_addres = $_REQUEST['bank_addres'];
$photo_name = $_REQUEST['photo_name'];
$deposit_address = $_REQUEST['deposit_address'];
$notice = $_REQUEST['notice'];
$maxmoney = $_REQUEST['maxmoney'];
$status = $_REQUEST['sts'];
$issaoma = $_REQUEST['issaoma'];
$mindeposit = isset($_REQUEST['mindeposit'])?$_REQUEST['mindeposit']:'' ; // 最低存款次数
$maxdeposit = isset($_REQUEST['maxdeposit'])?$_REQUEST['maxdeposit']:'' ; // 最高存款次数
$sort = isset($_REQUEST['sort'])?$_REQUEST['sort']:1 ;

//print_r( $_REQUEST ); die;
if($mindeposit>$maxdeposit){
    echo "<script>alert('最低存款次数不能大于最高存款次数!');history.back();</script>";
    exit;
}

$array_name=array(2=>"银行卡",4=>"微信",5=>"支付宝",6=>"QQ扫码");
$bank_code=array("USDT"=>"USDT虚拟货币","KSCZ"=>"快速充值", "NCSYYH" => "农村商业银行", "CSSYYH" => "城市商业银行", "NCXYS" => "农村信用社","JYNSBC" => "江阴农商银行",
    "YLSMYSF" => "银联扫码|云闪付", "ZFB" => "支付宝", "WXSAOMA" => "微信扫码", "ALISAOMA" => "支付宝扫码", "ICBC" => "工商银行", "ABC" => "农业银行", "CCB" => "建设银行", "BOCO" => "交通银行", "BOC" => "中国银行", "CEBB" => "光大银行", "CMBC" => "民生银行", "POST" => "中国邮政", "CMB" => "招商银行", "CIB" => "兴业银行", "CCCB" => "中信银行", "GDB" => "广发银行", "SPDB" => "浦发银行", "HXB" => "华夏银行", "PAB" => "平安银行", "SHB" => "上海银行", "BJB" => "北京银行", "ZZB" => "郑州银行",
    "JSBC" => "江苏银行","JXBC" => "江西银行", "LZBC" => "兰州银行","GLB" => "桂林银行","TACCB" => "泰安银行","QLBANK" => "齐鲁银行","HFBank" => "恒丰银行","LZCCB" => "柳州银行",
    );


switch ($type){
    case 'del':
        $sql = "delete from `".DBPREFIX."gxfcy_bank_data` WHERE `id` = {$id}";
        $res = mysqli_query($dbMasterLink,$sql);
        $res ? false : true;
        break;
    case 'edit':
        $sql = "UPDATE `".DBPREFIX."gxfcy_bank_data` SET `bank_user`='{$bank_user}', `bank_context`='{$bank_context}', `bank_account`='{$bank_account}', `bank_addres`='{$bank_addres}', `photo_name`='{$photo_name}', `deposit_address`='{$deposit_address}', `notice`='{$notice}',`maxmoney`='{$maxmoney}',`status`='{$status}',`issaoma`='{$issaoma}',`mindeposit`='{$mindeposit}',`maxdeposit`='{$maxdeposit}',`sort`='{$sort}' WHERE `id` = {$id}";
        $res = mysqli_query($dbMasterLink,$sql);
        !$res ? false : true;
        break;
    case 'add':
        $sql = "insert `".DBPREFIX."gxfcy_bank_data` values ('','{$bank_code_key}','{$bank_code[$bank_code_key]}','{$bank_context}','{$bank_user}','{$bank_addres}','{$bank_account}','{$class}','{$status}','{$issaoma}','{$photo_name}','{$deposit_address}','{$maxmoney}','{$mindeposit}','{$maxdeposit}','{$notice}',1)";
        $res=mysqli_query($dbMasterLink,$sql);
        if(!$res){
            exit( "<script>alert('添加失败');parent.main.location.href='bank_config.php?uid=$uid&langx=$langx'</script>");
        }else{
            exit( "<script>alert('添加成功');parent.main.location.href='bank_config.php?uid=$uid&langx=$langx'</script>");
        }
        break;
    default: break;
}

$sql = "select *  from ".DBPREFIX."gxfcy_bank_data order by sort asc";
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
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <title>银行支付配置</title>
    <style>
        /*input.za_text_auto {width: 150px;}*/
        .za_text_width80 {width: 60px;}
        input.za_text_auto{width: 100%}
    </style>
</head>
<body >
<dl class="main-nav"><dt>银行支付配置 <a href="?uid=<?php echo $uid?>&langx=<?php echo $langx?>&type=Y"></a></dt><dd> </dd></dl>
<div class="main-ui" style="width: 100%;overflow-x:auto;">
    <table  class="list-tab">
        <form name="myform" action="" method="post">
        <tr class="m_title">
            <th>银行名称</th>
            <th>开户名</th>
            <th>银行账号</th>
            <th>支行地址</th>
            <th>开放层级</th>
            <th>启用状态</th>
            <th>是否扫码</th>
            <th>图片名称</th>
            <th>充值地址</th>
            <th>提示</th>
            <th>最大充值金额</th>
            <th>最低/最高存款次数</th>
            <th>排序</th>
            <th>操作</th>
        </tr>
    <?php
    foreach ($lists as $value){
    ?>
        <tr  class=m_cen>
            <td>
                <?php echo $bank_code[$value['bankcode']];?>
                <input type="text" class="za_text_auto bank_context_<?php echo $value['id']?>" value="<?php echo $value['bank_context'];?>">
            </td>
            <td><input type="text" class="za_text_auto bank_user_<?php echo $value['id']?>" value="<?php echo $value['bank_user'];?>"></td>
            <td><input type="text" class="za_text_auto bank_account_<?php echo $value['id']?>" value="<?php echo $value['bank_account'];?>"></td>
            <td><input type="text" class="za_text_auto bank_addres_<?php echo $value['id']?>" value="<?php echo $value['bank_addres'];?>"></td>
            <td>
                <?php foreach($value['class'] as $val){ ?>
                    <p><?php echo $userLevelArr[$val]['name'];?></p>
                <?php } ?>
            </td>
            <td>
                <input type="checkbox" id="sts_<?php echo $value['id']?>" value="1" <?php echo $value['status']==1?"checked":"";?>>
            </td>
            <td>
                <input type="checkbox" id="issaoma_<?php echo $value['id']?>" value="1" <?php echo $value['issaoma']==1?"checked":"";?>>
            </td>
            <td><input type="text" class="za_text_auto photo_name_<?php echo $value['id']?>" value="<?php echo $value['photo_name'];?>"></td>
            <td><input type="text" class="za_text_auto deposit_address_<?php echo $value['id']?>" value="<?php echo $value['deposit_address'];?>"></td>
            <td><input type="text" class="za_text_width100 notice_<?php echo $value['id']?>" value="<?php echo $value['notice'];?>"></td>
            <td><input type="text" class="za_text_width100 maxmoney_<?php echo $value['id']?>" value="<?php echo $value['maxmoney'];?>"></td>
            <td class="deposit_time">
                最低：<input type="text" class="za_text_width80 mindeposit_<?php echo $value['id']?>" value="<?php echo $value['mindeposit'];?>"> <br><br>
                最高：<input type="text" class="za_text_width80 maxdeposit_<?php echo $value['id']?>" value="<?php echo $value['maxdeposit'];?>">
            </td>
            <td>
                <input type="text" class="za_text_width80 sort_<?php echo $value['id']?>" value="<?php echo $value['sort'];?>">
            </td>
            <td>
                <input type="button" class="za_button" value="修改" onclick="btn_edit('<?php echo $value['id']?>','<?php echo $uid?>')"><br/>
                <input type="button" data="<?php echo $value['id'];?>" class="za_button levelmanage" onClick="show_win(<?php echo $value['id'];?>);" value="层级管理" /><br/>
                <input type="button" onclick="btn_del(<?php echo $value['id']?>,'<?php echo $uid?>')" value="删除" />
            </td>
        </tr>
    <?php
    }
    ?>
        <tr class=m_cen >
            <td colspan="16">
                <input type="button" class="za_button" onclick="javascript:$('#adds').show();" value="新增" />
            </td>
        </tr>
    </form>
    </table>

    <div id="adds" style="display: none;">
        <div class="connects">
            <form id="newsadd" method="post" action="">
                <input type="hidden" name="uid" value="<?php echo $uid?>" />
                <input type="hidden" name="langx" value="<?php echo $langx?>" />
                <input type="hidden" name="type" value="add" />
                <table class="list-tab">
                    <tbody><tr><th>银行名称</th><th>开户名</th><th>银行卡号</th><th>支行地址</th><th>启用状态</th><th>是否扫码</th><th>图片名称</th><th>充值地址</th><th>提示</th><th>最大充值</th><th>支付类别</th></tr>
                    <tr>
                        <td>
                            <select id="bank_code" name="bank_code">
                                <?php
                                foreach ($bank_code as $key=>$val){
                                    echo "<option value=\"$key\" >$val</option>";
                                }
                                ?>
                            </select>
                            <input class="za_text_auto" type="text" name="bank_context" >
                        </td>
                        <td><input class="za_text_auto" type="text" name="bank_user" ></td>
                        <td><input class="za_text_auto" type="text" name="bank_account" ></td>
                        <td><input class="za_text_auto" type="text" name="bank_addres" ></td>
                        <td><input type="checkbox" name="sts" value="1"></td>
                        <td><input type="checkbox" name="issaoma" value="1"></td>
                        <td><input class="za_text_auto" type="text" name="photo_name" ></td>
                        <td><input class="za_text_auto" type="text" name="deposit_address" ></td>
                        <td><input class="za_text_auto" type="text" name="notice" ></td>
                        <td><input class="za_text_auto" type="text" name="maxmoney" ></td>
                        <td>
                            <select name="class">
                                <option value="a">a</option>
                                <option value="b">b</option>
                                <option value="b">c</option>
                                <option value="b">e</option>
                                <option value="b">e</option>
                            </select>
                        </td>
                    </tr>


                    <tr class=m_cen >
                        <td colspan="16">
                            <input type="button" value="新增" class="za_button btn2" onclick="javascript:$('#newsadd').submit();">
                            <input type="button" value="取消" class="za_button btn2" onclick="javascript:$('#adds').hide();">
                        </td>
                    </tr>
                    </tbody>
                </table>

            </form>
        </div>
    </div>

    <div id=acc_window class="line_type_width" style="display:none;position:absolute">
    <FORM name="addUSER" action="" method=post target="_self" >
        <table class="list-tab">
              <tr >
                <td id=r_title width="116" >
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
                    <input type="hidden" name="bankId"  />
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
            url:"bank_config.php",
            data:{id:id,uid: uid,type:type},
            success:function (data) {
                if (data){
                    alert('更新成功！');
                    window.location.href='bank_config.php?uid='+uid;
                }else{
                    alert('更新失败！！');
                }
            }
        });
    }

    function btn_edit(id,uid) {
        var type = 'edit';
        var bank_user = $(".bank_user_"+id).val();
        var bank_context = $(".bank_context_"+id).val();
        var bank_account = $(".bank_account_"+id).val();
        var bank_addres = $(".bank_addres_"+id).val();
        var photo_name = $(".photo_name_"+id).val();
        var deposit_address = $(".deposit_address_"+id).val();
        var notice = $(".notice_"+id).val();
        var maxmoney = $(".maxmoney_"+id).val();
        var mindeposit = $(".mindeposit_"+id).val(); // 最低存款次数
        var maxdeposit = $(".maxdeposit_"+id).val(); // 最高存款次数
        var sort = $(".sort_"+id).val();

        var obj_sts = document.getElementById("sts_"+id);
        var obj_issaoma = document.getElementById("issaoma_"+id);
        var sts = 0;
        if (obj_sts.checked) sts=1;
        var issaoma = 0;
        if (obj_issaoma.checked) issaoma=1;

        if(mindeposit =='' || maxdeposit ==''){
            alert('请输入最低/最高存款次数');
            return false ;
        }
        if(Number(mindeposit)>Number(maxdeposit)){
            alert('最低存款次数不能大于最高存款次数!');
            return false ;
        }

        // console.log(bank_user+'-'+bank_account+'-'+bank_addres+'-'+issaoma+'-'+photo_name+'-'+notice+'-'+maxmoney);
        // console.log(sts+'-'+issaoma);

        // 异步请求更新数据
        $.ajax({
            type:"POST",
            url:"bank_config.php",
            data:{
                id: id,
                uid: uid,
                type: type,
                bank_user: bank_user,
                bank_context: bank_context,
                bank_account: bank_account,
                bank_addres: bank_addres,
                photo_name: photo_name,
                deposit_address: deposit_address,
                notice: notice,
                maxmoney: maxmoney,
                sts: sts,
                issaoma: issaoma,
                mindeposit: mindeposit,
                maxdeposit: maxdeposit,
                sort: sort,
            },
            success:function(data) {
                if (data){
                    alert('更新成功！');
                    location.reload();
                }else{
                    alert('更新失败！！');
                }
            }
        })
    }

    function show_win(oid) {
		acc_window.style.top=document.body.scrollTop+event.clientY+15;
		acc_window.style.left=document.body.scrollLeft+event.clientX-300;
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
				  data:{uid:uid,act:'bankLevelEdit',type:2,level:kuangStr,bid:bankId},
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