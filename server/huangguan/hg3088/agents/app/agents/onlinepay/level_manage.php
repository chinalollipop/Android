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
$loginname=$_SESSION["username"];
$lv=$_REQUEST['lv'];

require ("../include/traditional.$langx.inc.php");

//设置用户层级
if($_REQUEST["type"]=="editUserLevel"){
		$select = $_REQUEST['select'];
        $user = $_REQUEST['user'];
        if(empty($user)){
            echo "<script>alert('记录id不能为空！');history.go(-1);</script>";
            exit;
        }
        
 		if(count($select)!=count($user)){
	        $selectNew=array();
	        $lockOrder = explode(',',$_REQUEST['userLockOrder']);
	        
	        foreach($lockOrder as $k=>$v){
	        	if($v==1) $selectNew[]=$select[$k];
	        }	
	        unset($select);
	        $select=$selectNew;
        }
        
        $levelList = array_combine($user,$select);

        $sql="select ename from ".DBPREFIX."gxfcy_userlevel where level !=0 order by level ASC";
        $result = mysqli_query($dbLink,$sql);
        while($res = mysqli_fetch_assoc($result)){
			$levelListOrigin[] = $res;	
		}	
        
        $levelEname = array();
        $enameList = array();
        foreach($levelListOrigin as $list){
            $levelEname[] = $list['ename'];
            $enameList[$list['ename']] = $list;
        }
        
        $beginFrom = mysqli_query($dbMasterLink,"start transaction");
      
        if($beginFrom){
        	foreach($levelList as $k=>$val){
        		$sql="select deposit_num,deposit_money,max_deposit_money,withdraw_num,withdraw_money from ".DBPREFIX.MEMBERTABLE." where id=".$k;
	            $result = mysqli_query($dbMasterLink,$sql);
        		$userinfo = mysqli_fetch_assoc($result);
				$sql="update ".DBPREFIX.MEMBERTABLE." set pay_class='{$val}' where id = {$k}";
	            if(!mysqli_query($dbMasterLink,$sql)){
	                mysqli_query($dbMasterLink,"ROLLBACK");
	                echo "<script>alert('1修改层级失败！');history.go(-1);</script>"; exit;
	            }
	            if(in_array($val,$levelEname)&&$enameList[$userinfo['pay_class']]['level']>=$enameList[$val]['level']){
	                $moneyArr = array(
	                    'deposit_num'=>$enameList[$val]['deposit_num'],
	                    'deposit_money'=>$enameList[$val]['deposit_money'],
	                    'max_deposit_money'=>$enameList[$val]['max_deposit_money'],
	                    'withdraw_num'=>$enameList[$val]['withdraw_num'],
	                    'withdraw_money'=>$enameList[$val]['withdraw_money'],
	                    'update_time'=>date('Y-m-d H:i:s')
	                );
	                
	                foreach($moneyArr as $key=>$val){
		            	$tmp[]=$key.'=\''.$val.'\'';
		        	}

		        	$sql="update ".DBPREFIX."gxfcy_usermoney_statistics set ".implode(',',$tmp)." where userid = {$k}";
		        	
	                if(!mysqli_query($dbMasterLink,$sql)){
	                    mysqli_query($dbMasterLink,"ROLLBACK");
	                    echo "<script>alert('2修改层级失败！');history.go(-1);</script>"; exit;
	                }
	            }

                /* 插入系统日志 */
                $loginfo = $loginname.' 层级管理中 <font class="red">修改了</font>会员帐号id 为<font class="green">'.$k.'</font>  层级 ' ;
                innsertSystemLog($loginname,$lv,$loginfo);
        	}
	        mysqli_query($dbMasterLink,"COMMIT");

	        echo "<script>alert('修改层级成功！');</script>";			
        }else{
        	echo "<script>alert('修改层级失败！');history.go(-1);</script>"; exit;
        }
        
}

$sql="select id,sort,ename,name,deposit_num,deposit_num,max_deposit_money,withdraw_num,withdraw_money,remark,level,start_time,end_time from ".DBPREFIX."gxfcy_userlevel order by sort asc";
$result = mysqli_query($dbLink,$sql);
while($row = mysqli_fetch_assoc($result)){
	$results[] = $row;	
}
$info = array();
foreach($results as $list){
	$sql = "select count(*) as num from ".DBPREFIX.MEMBERTABLE." where pay_class = '{$list['ename']}'";
	$res = mysqli_query($dbLink,$sql);
	$data = mysqli_fetch_assoc($res);
	$list['num'] = isset($data['num']) ? $data['num'] : 0;
	$info[$list['id']] = $list;
}

?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
	<title>层级管理</title>
	<style>
        input[ type="text"]{ width: 60px;}
        input.input_date{ width: 100% ;}
	</style>
</head>
    <body>
    <dl class="main-nav">
        <dt>层级管理</dt>
        <dd></dd>
    </dl>
  <div class="main-ui">
    <table class="m_tab" >
        <tr>
            <td class="m_tline" colspan="16">&nbsp;&nbsp;层级管理&nbsp;&nbsp;&nbsp;&nbsp;
                <div style="float:right;">
                    <input type="button" class="za_button" id="addLevel" onClick="add_level();"	value="新增层级">
                    <input type="button" 	class="za_button" id="searchMember"	onClick="search_member();"	value="会员查询">
                </div>
            </td>

        </tr>
                  <tr class="m_title">
                        <td  rowspan="2">序号</td>
                        <td  rowspan="2">编号</td>
                        <td  rowspan="2">英文标识</td>
                        <td  rowspan="2">层级名称</td>
                        <td  colspan="7">
                           	分层条件
                        </td>
                        <td rowspan="2">会员人数</td>
                        <td rowspan="2">备注</td>
                        <td rowspan="2">层级</td>
                        <td rowspan="2" width="60">功能设定</td>
                        <tr class=m_cen>
                            <td style="width:11.5%" >会员加入起始时间</td>
                            <td style="width:11.5%" >会员加入结束时间</td>
                            <td>存款次数</td>
                            <td>存款总额</td>
                            <td>最大存款额度</td>
                            <td>提款次数</td>
                            <td>提款总额</td>
                        </tr>
                    </tr>
                    <?php foreach($info as $key=>$val){?>
                        <tr class=m_cen>
                            <td><input type="text" id="sort_<?php echo $val['id'];?>" value="<?php echo $val['sort'];?>" /></td>
                            <td><?php echo $val['id'];?></td>
                            <td><input type="text" id="ename_<?php echo $val['id'];?>" value="<?php echo $val['ename'];?>" /></td>
                            <td><input type="text" id="name_<?php echo $val['id'];?>" value="<?php echo $val['name'];?>"  /></td>
                            <td><input type="text" class="input_date" id="start_time_<?php echo $val['id'];?>" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})" readonly value="<?php echo $val['start_time'];?>" /></td>
                            <td><input type="text" class="input_date" id="end_time_<?php echo $val['id'];?>" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})" readonly value="<?php echo $val['end_time'];?>" /></td>
                            <td><input type="text" id="deposit_num_<?php echo $val['id'];?>" value="<?php echo $val['deposit_num'];?>" /></td>
                            <td><input type="text" id="deposit_money_<?php echo $val['id'];?>" value="<?php echo $val['deposit_money'];?>" /></td>
                            <td><input type="text" id="max_deposit_money_<?php echo $val['id'];?>" value="<?php echo $val['max_deposit_money'];?>"	/></td>
                            <td><input type="text" id="withdraw_num_<?php echo $val['id'];?>" value="<?php echo $val['withdraw_num'];?>"	/></td>
                            <td><input type="text" id="withdraw_money_<?php echo $val['id'];?>" value="<?php echo $val['withdraw_money'];?>"	/></td>
                            <?php echo '<td><a class="a_link" href="../agents/user_browse.php?userlevel='.$val['ename'].'&uid='.$uid.'&lv=MEM&langx='.$langx.'">'.$val['num'].'</a></td>';?>
                            <td><input type="text" id="remark_<?php echo $val['id'];?>" value="<?php echo $val['remark'];?>" /></td>
                            <td><input type="text" id="level_<?php echo $val['id'];?>" value="<?php echo $val['level'];?>" /></td>
                            <td>
                                <a class="a_link" href="javascript:;" onclick="editUserLevel(<?php echo $val['id']?>)" >修改</a>
                                <?php if($val['ename'] !='a'){  ?>
                                <a class="a_link" href="javascript:;" onclick="delUserLevel(<?php echo $val['id']?>)" >删除</a>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </table>

<!-- 新增层级-->
<div id="addLevel_window"  class="line_type_width hide_window" >
<FORM name="addUSER" action="" method=post target="_self" >
	<table class="list-tab">
	      <tr >
	        <td id="r_title" colspan="2">
                    新增层级
                <a class="close_window" onClick="close_win()"><img src="/images/agents/top/edit_dot.gif" width="16" height="14"></a>
            </td>
	      </tr>
	      <tr>
	        <td><font color="Black">英文唯一标识码</font></td>
	        <td ><input type=text name=ename value="" id="ename" class="za_text time_width" ></td>
	      </tr>
	      <tr>
	        <td ><font color="Black">层级名称</font></td>
	        <td ><input type=text name=name value="" id="name" class="za_text time_width" ></td>
	      </tr>
	      <tr>
	        <td ><font color="Black">起始时间</font></td>
	        <td ><input type=text name=start_time value="" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})" readonly id="start_time" class="za_text time_width" ></td>
	      </tr>
	      <tr>
	        <td ><font color="Black">结束时间</font></td>
	        <td ><input type=text name=end_time value="" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})" readonly id="end_time" class="za_text time_width" ></td>
	      </tr>
	      <tr>
	        <td ><font color="Black">存款次数</font></td>
	        <td ><input type=text name=deposit_num value="" id="deposit_num" class="za_text time_width" ></td>
	      </tr>
	      <tr>
	        <td ><font color="Black">存款总额</font></td>
	        <td ><input type=text name=deposit_money value="" id="deposit_money" class="za_text time_width" ></td>
	      </tr>
	        <tr>
	        <td ><font color="Black">最大存款额度</font></td>
	        <td ><input type=text name=max_deposit_money value="" id="max_deposit_money" class="za_text time_width" ></td>
	      </tr>
	      <tr>
	        <td ><font color="Black">提款次数</font></td>
	        <td ><input type=text name=withdraw_num value="" id="withdraw_num" class="za_text time_width" ></td>
	      </tr>
	      <tr>
	        <td ><font color="Black">提款总额</font></td>
	        <td ><input type=text name=withdraw_money value="" id="withdraw_money" class="za_text time_width" ></td>
	      </tr>
	      <tr>
	        <td ><font color="Black">备注</font></td>
	        <td ><input type=text name=remark value="" id="remark" class="za_text time_width" ></td>
	      </tr>
	       <tr>
	        <td ><font color="Black">层级</font></td>
	        <td><input type=text name=level value="" id="level" class="za_text time_width" ></td>
	      </tr>
	      <tr>
	        <td ><font color="Black">序号</font></td>
	        <td ><input type=text name=sort value="" id="sort" class="za_text time_width" ></td>
	      </tr>
	      <tr >
	      	<td colspan="2"><input type="button" id="addLevelOK" name="acc_ok" value="确定" class="za_button"></td>
	      </tr>

	</table>
</FORM>
</div>                

<!-- 会员查询 -->
<div id="serchmember_window" class="line_type_width hide_window" >
<FORM name="addUSER" action="" method=post target="_self" >
	<table class="list-tab">
	      <tr >
	        <td id="r_title" colspan="2">
                会员查询
                <a class="close_window" onClick="close_win()"><img src="/images/agents/top/edit_dot.gif" width="16" height="14"></a>
            </td>
	      </tr>
	      <tr>
	        <td colspan="2" align="left"><font color="Black">查询会员名单[以逗号，分隔多个账号]</font></td>
	      </tr>
	      <tr>
	        <td colspan="2" align="center"><textarea id="usernames" rows="3" cols="30" placeholder="例如:nick001,nick002"></textarea></td>
	      </tr>
	      <tr align="center">
				<td><input type="button" name="serchmemberSend" onclick="serchmember_Send();" value="确定" class="za_button"></td>
				<td><input type="button" name="serchmemberOff" onclick="close_win();" value="关闭" class="za_button"></td>
	      </tr>
	</table>
</FORM>
</div>
<!-- 会员查询结果 -->
<div id="serchmemberok_window"  class="line_type_width hide_window" style="width: 800px;" >
    <form action="" method="post" id="masterforms">
        <table class="list-tab" >

                  <tr >
                    <td id="r_title" colspan="10" >
                        会员查询结果
                        <a class="close_window" onClick="close_win()"><img src="/images/agents/top/edit_dot.gif" width="16" height="14"></a>
                    </td>
                  </tr>
                  <tr class="border_line" id="tableHeader">
                    <td>代理账号</td>
                    <td>会员账号</td>
                    <td>加入时间</td>
                    <td>存款次数</td>
                    <td>存款总额</td>
                    <td>最大存款额度</td>
                    <td>提款次数</td>
                    <td>提款总额</td>
                    <td>分层</td>
                    <td>锁定</td>
                  </tr>
                  <tr align="center">
                    <td colspan="10" >
                        <input type="button" name="serchmemberSend" onClick="serchmember_Start();" value="开启此次设定" class="za_button">
                        <input type="button" name="serchmemberOff" onClick="close_win();" value="关闭" class="za_button">
                     </td>
                    <input type="hidden" value="editUserLevel" name="type" />
                    <input type="hidden" value="" name="userLockOrder" />
                  </tr>


        </table>
    </form>
</div>

</div>
    <script type="text/javascript" src="../../../js/agents/jquery.js"></script>
    <script type="text/javascript" src="../../../js/agents/register/laydate.min.js"></script>
    <script type="text/javascript">

        function add_level(){
            document.all["addLevel_window"].style.display = "block";
        }
        function close_win() {
            document.all["addLevel_window"].style.display = "none";
            document.all["serchmember_window"].style.display = "none";
            document.all["serchmemberok_window"].style.display = "none";
        }
        // 新增层级
        $("#addLevelOK").bind('click',function(){
            var user = {
                act:'editUserLevel',
                ename:$("#ename").val(),
                name:$("#name").val(),
                start_time:$("#start_time").val(),
                end_time:$("#end_time").val(),
                deposit_num:$("#deposit_num").val(),
                deposit_money:$("#deposit_money").val(),
                max_deposit_money:$("#max_deposit_money").val(),
                withdraw_num:$("#withdraw_num").val(),
                withdraw_money:$("#withdraw_money").val(),
                sort:$("#sort").val(),
                level:$("#level").val(),
                remark:$("#remark").val(),
            };
            $.ajax({
                url:'../ajax.php',
                data:user,
                type:'post',
                dataType:'json',
                success:function(res){
                    alert(res.message);
                    if(res.status==0){
                        window.location.reload();
                    }
                }
            })
        })

        function search_member(){
            document.all["serchmember_window"].style.display = "block";
        }

        function serchmember_Send(){
            var usernames = $("#usernames").val() ;
            if(usernames.length<0 || usernames ==''){
                alert("请输入查询内容！");
                return false ;
            }
            $.ajax({
                type: "post",
                url: "../ajax.php",
                data: {
                    act:'serchMemberSend',
                    usernames:usernames
                },
                dataType: "json",
                success: function(data){
                    if(data.status==0){
                        document.all["serchmember_window"].style.display = "none";
                        document.all["serchmemberok_window"].style.display = "block";
                        $(".searchResult").remove();
                        var html='';
                        $.each(data.user, function (i, item) {
                            html += '<tr class="border_line searchResult" ><td>'+item.Agents+'</td><td>'+item.username+'</td><td >'+item.AddDate+'</td><td>'+item.deposit_num+'</td><td>'+item.deposit_money+'</td><td>'+item.max_deposit_money+'</td><td>'+item.withdraw_num+'</td><td>'+item.withdraw_money+'</td><td><select name="select[]">';
                            $.each(data.level, function (i, val) {
                                html += '<option ';
                                if(item.pay_class == val.ename){
                                    html += 'selected = "selected"';
                                }
                                html += ' value ="'+val.ename+'">'+val.name+'</option>';
                            });
                            html += '</select></td><td><input type="checkbox" value="'+item.id+'" name="user[]" /></td></tr>';
                        });
                        $("#tableHeader").after(html);
                    }
                }
            });

        }

        function serchmember_Start(){
                var checklength = 0 ;
                var lockOrder = new Array();
                $("#masterforms input[type='checkbox']").each(function(){
                    if(this.checked){
                        lockOrder.push(1);
                        checklength ++ ;
                    }else{
                        lockOrder.push(0);
                    }
                });
                if(checklength>0){
                    $('input[name=userLockOrder]').val(lockOrder);
                    $('#masterforms').submit();
                    document.all["serchmemberok_window"].style.display = "none";
                }else{
                    alert("请先锁定数据！");
                }

        }

        // 修改会员层级
        function editUserLevel(id){
            var user = {
                id:id,
                act:'editUserLevel',
                ename:$("#ename_"+id).val(),
                name:$("#name_"+id).val(),
                start_time:$("#start_time_"+id).val(),
                end_time:$("#end_time_"+id).val(),
                deposit_num:$("#deposit_num_"+id).val(),
                deposit_money:$("#deposit_money_"+id).val(),
                max_deposit_money:$("#max_deposit_money_"+id).val(),
                withdraw_num:$("#withdraw_num_"+id).val(),
                withdraw_money:$("#withdraw_money_"+id).val(),
                sort:$("#sort_"+id).val(),
                level:$("#level_"+id).val(),
                remark:$("#remark_"+id).val()
            };
            $.ajax({
                url:'../ajax.php',
                data:user,
                type:'post',
                dataType:'json',
                success:function(res){
                    alert(res.message);
                    window.location.reload();
                }
            })
        }
        // 删除会员层级
        function delUserLevel(id){
            if(confirm("删除后将不能恢复，确认要删除吗？")){
                $.ajax({
                    url:'../ajax.php',
                    data:{
                        id:id,
                        act:"delUserLevel"
                    },
                    type:'post',
                    dataType:'json',
                    success:function(res){
                        alert(res.message);
                        window.location.reload();
                    }
                })
            }
        }

    </script>
  
</body>
</html>