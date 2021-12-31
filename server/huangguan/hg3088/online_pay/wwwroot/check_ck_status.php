<?php
// 30秒倒计时，检查判断对应的出款订单是否出款成功，成功则更新订单状态、否则继续30秒轮询

require("../class/config.inc.php");
require("../class/jiutong/util.php");
require("../class/huitong/helper.php");

$reviewer = $_REQUEST['reviewer'];

// 检查汇通出款是否开启，未开启则关闭窗口
$sql=" select * from ".DBPREFIX."gxfcy_autopay where status = 1 ";
$result = mysqli_query($dbLink,$sql);
$htinfo = mysqli_fetch_assoc($result);
if ($htinfo['method'] != 'htpay_cash_autock'){
    echo '汇通未启用<br>';
//    echo '<script>alert("汇通未启用，请关闭此窗口");</script>';
}

// 捞取出款中的订单（从昨天到今天的）
$date_start=date('Y-m-d', time()-86400);
$date_end=date('Y-m-d');
$sql="select ID,userid,Checked,Order_Code,Gold,`Type`,Locked from ".DBPREFIX."web_sys800_data where AddDate>='$date_start' and AddDate<='$date_end' and Type='T' and Checked=2 and is_auto=1 and is_auto_flag=2 and Locked=0 and reviewer='{$reviewer}' ORDER BY ID Desc";
$result800 = mysqli_query($dbLink,$sql);
$cou=mysqli_num_rows($result800);
if ($cou==0){
    echo '没有记录<br>';
//    echo '<script>alert("没有记录，请关闭此窗口");</script>';
}

$md5key = $htinfo['business_pwd'];
$gatewayUrl = 'https://api.huitongvip.com/remit_query.html';
// 发出请求，获取每单的出款结果
while ($row = mysqli_fetch_assoc($result800)) {
    $rows[] = $row;
}


foreach ($rows as $key => $value){
    $beginFrom = mysqli_query($dbMasterLink,"start transaction");	//开启事务$from
    $sql_check = "select ID,userid,Checked,Order_Code,Gold,`Type`,Locked from ".DBPREFIX."web_sys800_data WHERE `ID` = {$value['ID']} for update";
    $res_check = mysqli_query($dbMasterLink,$sql_check);
    $user_record_info = mysqli_fetch_assoc($res_check);

    if ($user_record_info['Type']=='T' and $user_record_info['Checked']==2 and $user_record_info['Locked']==0) {

        $now_date = date('Y-m-d H:i:s');
        $kvs = new KeyValues();
        $kvs->setkey($md5key);
        $kvs->add(AppConstants::$MERCHANT_CODE, $htinfo['business_code']);
        $kvs->add(AppConstants::$NOW_DATE, $now_date);
        $kvs->add(AppConstants::$TRADE_NO, $user_record_info['Order_Code']);
        $sign = $kvs->sign();

        $param = 'merchant_code='.$htinfo['business_code'].'&now_date='.$now_date.'&trade_no='.$value['Order_Code'].'&sign='.$sign;
//        echo $param;
        //发起请求
        $result = wx_post($gatewayUrl,$param);
        $array=json_decode($result,true);

        if ($array['is_success']){

            // bank_status 0 未处理，1 银行处理中 2 已打款 3 失败
            if($array['bank_status'] == 0){
                $msg = '汇通->未处理';
            }elseif($array['bank_status'] == 1){
                $msg = '汇通->银行处理中';
            }elseif($array['bank_status'] == 3){
                $msg = '汇通->失败';
            }elseif($array['bank_status'] == 2){

                // 更新状态
                // 更新订单状态
                $reviewDate=date('Y-m-d H:i:s');
                $is_auto = 1;
                $is_auto_flag = 1;
                $sql_update = "update ".DBPREFIX."web_sys800_data set Checked=1,is_auto={$is_auto},is_auto_flag={$is_auto_flag},reviewer='{$reviewer}',reviewDate='{$reviewDate}' WHERE `ID` = {$value['ID']}";
                if(mysqli_query($dbMasterLink,$sql_update)){
                    $res = level_deal($user_record_info['userid'],$user_record_info['Gold'],1);//用户层级关系处理
                    if($res){
                        mysqli_query($dbMasterLink,"COMMIT");
                        $msg = '汇通->已打款';

                    }else{
                        mysqli_query($dbMasterLink,"ROLLBACK");
                        $msg = '用户层级关系处理失败!';
                    }
                }else{
                    mysqli_query($dbMasterLink,"ROLLBACK");
                    $msg = '提款成功，更新提款状态失败!';
                }
            }
        }else{
            mysqli_query($dbMasterLink,"ROLLBACK");
            $msg = '请求汇通失败';
        }
    }
    else{
        mysqli_query($dbMasterLink,"ROLLBACK");
        $msg = '订单已经被处理，不要重复提交！';
    }

    echo '订单号：'.$value['Order_Code'].'   状态处理结果：'.$msg.'<br>';

}




$settime=30;

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
    <style type="text/css">
        BODY,TD{font-size:8.5pt}BODY,INPUT,TD{COLOR:#3e4859;font-family:Verdana}BODY{background-color:#ACC;margin-left:0;margin-top:0}INPUT{font-size:12px}
    </style>
</head>
<body>
<script> 

var limit="<?php echo $settime?>" 
if (document.images){ 
	var parselimit=limit
} 
function beginrefresh(){ 
if (!document.images) 
	return 
if (parselimit==1) 
	window.location.reload() 
else{ 
	parselimit-=1 
	curmin=Math.floor(parselimit) 
	if (curmin!=0) 
		curtime=curmin+"秒后自动获取汇通自动出款结果，并更新状态!"
	else 
		curtime=cursec+"秒后自动获取汇通自动出款结果，并更新状态!"
		timeinfo.innerText=curtime 
		setTimeout("beginrefresh()",1000) 
	} 
} 

window.onload=beginrefresh 

</script>
<table width="100" height="70" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="100" height="70" align="center">
      <span id="timeinfo"></span><br>
      <input type=button name=button value="点击重新获取结果，并更新状态" onClick="window.location.reload()"></td>
  </tr>
</table>
</body>
</html>