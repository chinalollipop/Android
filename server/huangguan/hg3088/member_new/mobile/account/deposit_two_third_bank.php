<?php
// 第三方银行存款
// 输入金额，跳转第三方或者添加记录

include_once('../include/config.inc.php');

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    $status = '401.1';
    $describe = '请重新登录!';
    original_phone_request_response($status,$describe);
}

$uid=$_SESSION['Oid'];
$langx=$_SESSION['Language'];
$username = $_SESSION['UserName'];
$depositTimes = $_SESSION['DepositTimes']; //会员存款次数
// 第三方银行支付
$sSql = "SELECT id,thirdpay_code,url,minCurrency,maxCurrency,title FROM `".DBPREFIX."gxfcy_pay` WHERE `account_company` = 2 AND `depositNum` <= $depositTimes AND `status` = 1 AND FIND_IN_SET('{$_SESSION['pay_class']}',class)";
$oRes = mysqli_query($dbLink,$sSql);
$iCou=mysqli_num_rows($oRes);
if( $iCou==0 ){
    $status = '401.2';
    $describe = '支付方式有误，请重新选择~！';
    original_phone_request_response($status,$describe);
}

$aData = [];
while($aRow = mysqli_fetch_assoc($oRes)){
    $aData[]=$aRow;
}

$aPid = [];
$aBanklist = [];
$aUrl = [];
$aMinCurrency = [];
$aMaxCurrency = [];
foreach ($aData as $k => $v){
    $aPid[$k] = $v['id'];
    $aMinCurrency[$k] = bcmul(floatval($v['minCurrency']) , 1);
    $aMaxCurrency[$k] = bcmul(floatval($v['maxCurrency']) , 1);
    switch ( $v['thirdpay_code']){

        case 'sf':
            $aUrl[$k] =  $v['url'].'/sfpay.php';
            $aBanklist[$k] = array(
                '3001'=>'招商银行（借）','3002'=>'中国工商银行（借）','3003'=>'中国建设银行（借）','3004'=>'上海浦东发展银行（借）','3005'=>'中国农业银行（借）','3006'=>'中国民生银行（借）','3009'=>'兴业银行（借）','3020'=>'中国交通银行（借）','3022'=>'中国光大银行（借）','3026'=>'中国银行（借）','3032'=>'北京银行（借）','3035'=>'平安银行（借）','3036'=>'广发银行|CGB（借）','3037'=>'上海农商银行（借）','3038'=>'中国邮政储蓄银行（借）','3039'=>'中信银行（借）','3050'=>'华夏银行（借）','3059'=>'上海银行（借）','3060'=>'北京农商银行（借）',
            );
            break;

        case 'yb':
            $aUrl[$k] =  $v['url'].'/yeepay.php';
            $aBanklist[$k] = array(
                'ICBC-NET-B2C'=>'工商银行','CMBCHINA-NET-B2C'=>'招商银行','CCB-NET-B2C'=>'建设银行','BOCO-NET-B2C'=>'交通银行[借]','CIB-NET-B2C'=>'兴业银行','CMBC-NET-B2C'=>'中国民生银行','CEB-NET-B2C'=>'光大银行','BOC-NET-B2C'=>'中国银行','PINGANBANK-NET-B2C'=>'平安银行','ECITIC-NET-B2C'=>'中信银行','SDB-NET-B2C'=>'深圳发展银行','GDB-NET-B2C'=>'广发银行','SHB-NET-B2C'=>'上海银行','SPDB-NET-B2C'=>'上海浦东发展银行','HXB-NET-B2C'=>'华夏银行「借」','BCCB-NET-B2C'=>'北京银行','ABC-NET-B2C'=>'中国农业银行','POST-NET-B2C'=>'中国邮政储蓄银行「借」','BJRCB-NET-B2C'=>'北京农村商业银行「借」-暂不可用',
            );
            break;

        case 'rx':// 仁信
            $aUrl[$k] = $v['url'] . '/rxpay.php';
            $aBanklist[$k] = array(
                'ICBC'=>'工商银行','ABC'=>'农业银行','CCB'=>'建设银行','BOC'=>'中国银行','CMB'=>'招商银行','BCCB'=>'北京银行','BOCO'=>'交通银行','CIB'=>'兴业银行','NJCB'=>'南京银行','CMBC'=>'民生银行','CEB'=>'光大银行','PINGANBANK'=>'平安银行','CBHB'=>'渤海银行','HKBEA'=>'东亚银行','NBCB'=>'宁波银行','CTTIC'=>'中信银行','GDB'=>'广发银行','SHB'=>'上海银行','SPDB'=>'上海浦东发展银行','PSBS'=>'中国邮政','HXB'=>'华夏银行','BJRCB'=>'北京农村商业银行','SRCB'=>'上海农商银行','SDB'=>'深圳发展银行','CZB'=>'浙江稠州商业银行',
            );
            break;

        case 'fkt': //福卡通
            $aUrl[$k] = $v['url'] . '/fktpay.php';
            $aBanklist[$k] = array(
                'ABC' => '中国农业银行', 'BOC' => '中国银行','BOCOM' => '交通银行', 'CCB' => '中国建设银行', 'ICBC' => '中国工商银行','PSBC' => '中国邮政储蓄银行', 'CMBC' => '招商银行','SPDB' => '浦发银行', 'CEBBANK' => '中国光大银行','ECITIC' => '中信银行','PINGAN' => '平安银行', 'CMBCS' => '中国民生银行', 'HXB' => '华夏银行', 'CGB' => '广发银行','BCCB' => '北京银行','BOS' => '上海银行','CIB' => '兴业银行',
            );
            break;

        case 'sft': //顺付通
            $aUrl[$k] = $v['url'] . '/sftpay.php';
            $aBanklist[$k] = array(
                'ABC' => '中国农业银行','BCCB' => '北京银行','CCB' => '中国建设银行','CEB' => '中国光大银行','CMB' => '招商银行','ICBC' => '中国工商银行','PSBC' => '中国邮政储蓄银行','BOC' => '中国银行','COMM' => '交通银行','SPDB' => '浦发银行','CNCB' => '中信银行','PAB' => '平安银行','CMBC' => '中国民生银行','HXB' => '华夏银行','BOS' => '上海银行','CIB' => '兴业银行','CBHB' => '渤海银行','GDB' => '广发银行',
            );
            break;

        case 'db': // 得宝
            $aUrl[$k] = $v['url'] . '/dbpay.php';
            $aBanklist[$k] = array(
                'ABC'  => '农业银行','ICBC' => '工商银行','CCB'  => '建设银行','BCOM' => '交通银行','BOC'  => '中国银行','CMB'  => '招商银行','CMBC' => '民生银行','CEBB' => '光大银行','BOB'  => '北京银行','SHB'  => '上海银行','NBB'  => '宁波银行','HXB' => '华夏银行','CIB'  => '兴业银行','PSBC'  => '中国邮政银行','SPABANK' => '平安银行','SPDB'  => '浦发银行','ECITIC'  => '中信银行','HZB'  => '杭州银行','GDB'  => '广发银行',
            );
            break;
        case 'zrb': // 智融宝
            $aUrl[$k] = $v['url'] . '/zrbpay.php';
            $aBanklist[$k] = array(
                'BOC'  => '中国银行','ICBC' => '工商银行','CCB'  => '建设银行','CMBCHINA'  => '招商银行','GDB'  => '广发银行','POST'  => '中国邮政','ABC'  => '农业银行','CMBC' => '中国民生银行','CEB' => '光大银行','BOCO' => '交通银行',
            );
            break;
        case 'xft': // 信付通
            $aUrl[$k] = $v['url'] . '/xftpay.php';
            $aBanklist[$k] = array(
                'CMB' => '招商银行','ICBC' => '工商银行','CCB' => '建设银行','BOC' => '中国银行','ABC' => '农业银行','BOCM' => '交通银行','SPDB' => '浦发银行','CGB' => '广发银行','CITIC' => '中信银行','CEB' => '光大银行','CIB' => '兴业银行','PAYH' => '平安银行','CMBC' => '民生银行','HXB' => '华夏银行','PSBC' => '邮储银行','BCCB' => '北京银行','SHBANK' => '上海银行','WXPAY' => '微信支付','ALIPAY' => '支付宝支付', 'QQPAY' => 'QQ扫码','JDPAY' => '京东扫码','QUICKPAY' => '快捷支付','UNIONPAY' => '中国银联','BDPAY' => '百度钱包','UNIONQRPAY' => '银联扫码',
            );
            break;
        case 'zb': // 众宝
            $aUrl[$k] = $v['url'] . '/'.$v['thirdpay_code'] .'/zhongbaopay.php';
            $aBanklist[$k] = array(
                '962'=>'中信银行','963'=>'中国银行','964'=>'农业银行','965'=>'建设银行','967'=>'工商银行','970'=>'招商银行','971'=>'邮储银行','972'=>'兴业银行','977'=>'浦发银行','979'=>'南京银行','980'=>'民生银行','981'=>'交通银行','983'=>'杭州银行','985'=>'广发银行','986'=>'光大银行','987'=>'东亚银行','989'=>'北京银行','990'=>'平安银行','991'=>'华夏银行','992'=>'上海银行','1000'=>'微信扫码','1002'=>'微信直连','1003'=>'支付宝扫码','1004'=>'支付宝直连','1005'=>'QQ钱包扫码','1006'=>'QQ钱包直连','1007'=>'京东钱包扫码','1008'=>'京东钱包直连','1009'=>'银联扫码',
            );
            break;
        case 'wdf': // 维多付
            $aUrl[$k] = $v['url'] . '/'.$v['thirdpay_code'] .'/weiduofupay.php';
            $aBanklist[$k] = array(
                //'w_union'=>'银联闪付','w_alibank'=>'阿里网关','w_alipayqr'=>'支付宝转支',/*'w_alipay'=>'支付宝转卡','w_alipayh5'=>'支付宝H5','w_wechat'=>'微信扫码','w_wechath5'=>'微信转卡',*/
                '1'=>'银联闪付','2'=>'阿里网关','3'=>'支付宝转支',/*'4'=>'支付宝转卡','5'=>'支付宝H5','6'=>'微信扫码','7'=>'微信转卡',*/
            );
            break;
        case 'clzldz':
            $aUrl[$k] = $v['url'] . '/clzldzpay.php';
            $aBanklist[$k] = array(
                '920'=>'支付宝或网银',
            );
            break;
        case 'ccx':
            $aUrl[$k] = $v['url'] . '/'.$v['thirdpay_code'] .'/ccxpay.php';
            //支付类型有：（0：支付宝转卡;1:微信扫码【无可用】；2：银联扫码；3：综合支付;4：微信转账【维护】；5：支付宝转账【维护】；6：手机银行转账;7：银联快捷【维护】;8：支付宝个码【至少1000元】；9: 支付宝wap2/支付宝H5【无可用】）
            $aBanklist[$k] = array(
                '0'=>'支付宝转卡','2'=>'银联扫码','3'=>'综合支付','4'=>'微信转卡','6'=>'手机银行转账','7'=>'银联快捷',
            );
            break;
        case 'csj':  // 创世纪
            $aUrl[$k] = $v['url'] . '/'.$v['thirdpay_code'] .'/csjpay.php';
            // 支付类型 '0'=>'支付宝转卡','1'=>'微信扫码','2'=>'银联扫码','3'=>'网银支付','4'=>'微信转账','5'=>'支付宝转账','6'=>'手机银行转账','7'=>'银联快捷','8'=>'支付宝个码','9'=>'支付宝wap2/支付宝H5',
            $aBanklist[$k] = array(
                '0'=>'支付宝转卡','2'=>'银联扫码','3'=>'网银支付','8'=>'支付宝个码',
            );
            break;
        case 'autopay':  // autopay
            $aUrl[$k] = $v['url'] . '/'.$v['thirdpay_code'] .'/AutoPaypay.php';
            $aBanklist[$k] = array(
                '5'=>'网银支付',
            );
            break;
    }
}


$status = '200';
$describe = 'success';

$aBanklist2 = array(); // 声明新的银行列表给原生接口
foreach ($aBanklist as $k =>$v){

    foreach ($v as $k2 => $v2){
        $aBanklist2[$k][$k2]['bankcode'] = $k2;
        $aBanklist2[$k][$k2]['bankname'] = $v2;
    }
    $aBanklist2[$k] = array_values($aBanklist2[$k]); // 去掉key
}
$aBanklist = array(); // 注销旧的银行列表

foreach ($aData as $k =>$v){
    $aData[$k]['userid']=$_SESSION['userid'];
    $aData[$k]['url'] = $aUrl[$k];
    $aData[$k]['bankList'] = $aBanklist2[$k];
}

original_phone_request_response($status,$describe,$aData);



?>
