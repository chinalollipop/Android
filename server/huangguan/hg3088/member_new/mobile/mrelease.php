<?php
/**
 * 手机客户端升级接口
 * Date: 2018/8/3
 */

include_once('include/config.inc.php');
$appRefer = isset($_REQUEST['appRefer'])?$_REQUEST['appRefer']:'';
$terminalId = intval($_REQUEST['appRefer']);

//if(!isset($_REQUEST['appRefer']))
//    exit(json_encode(['status' => 0, 'describe' => '缺少参数！']));

//$aTerminal = mysqli_query($dbLink, 'SELECT id FROM ' . DBPREFIX . 'web_terminals WHERE id = ' . $terminalId);
//if(!mysqli_num_rows($aTerminal))
//    exit(json_encode(['status' => 0, 'describe' => '非法终端！']));
$data = array();
if($appRefer){ // M 版不需要
    $sql = 'SELECT `id`, `version`, `file_size`, `file_path`, `description`, `is_force` FROM ' . DBPREFIX . 'web_releases WHERE `status` = 1 AND terminal_id =' . $terminalId . ' ORDER BY `id` DESC';
    $oResult = mysqli_query($dbLink, $sql);
    $aRow = mysqli_fetch_assoc($oResult);

    $data = [
        'version' => $aRow['version'],
        'file_size' => $aRow['file_size'],
        'file_path' => $aRow['file_path'],
        'description' => $aRow['description'],
        'is_force' => $aRow['is_force'],
    ];
}
// 后台客户端设置
$aSysData = getSysConfig();  // 读取缓存
if(empty($aSysData)) {
    $sql = "SELECT `id`, `key`, `value` FROM " . DBPREFIX . "web_mconfig WHERE status = 1";
    $result = mysqli_query($dbMasterLink, $sql);
    while ($row = mysqli_fetch_assoc($result)){
        $aSysData[$row['key']] = $row['value'];
    }
}
if (!empty($aSysData)){
    $aSysData['newcomer_guide'] = ( (bHttps() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] ).'/'. $aSysData['newcomer_guide'];
    $aSysData['service_wechat_url'] = ( (bHttps() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] ).'/'.TPL_NAME. $aSysData['service_wechat_url'];
    if(TPL_FILE_NAME=='wnsr'){
        $aSysData['service_meiqia'] = $aSysData['vns_service_meiqia'];
        $aSysData['service_qq'] = $aSysData['vns_service_qq'];
        $aSysData['service_wechat'] = '';
        $aSysData['service_wechat_url'] = '';
    }
    $data = array_merge($data , $aSysData);
}
//var_dump($aSysData);
//后台图片配置
$picConData = getPicConfig();  //获取Redis图片数据
if(empty($picConData)) { // redis不存在
    $sql = "SELECT `id`, `key`, `pic_url`, `category_id`, `remark` FROM " . DBPREFIX . "web_pconfig WHERE status = 1";
    $result = mysqli_query($dbLink, $sql);
    $picConData=array();
    while ($row = mysqli_fetch_assoc($result)){
        $picConData[$row['key']] = !empty($row['pic_url']) ? PROMOS_PIC_DOMAIN . $row['pic_url'] : '';
    }
}
if (!empty($picConData)){
    foreach($picConData as $key => $value) {
        $data[$key] = $value;
    }
    $data['service_wechat_url'] = $picConData['server_wechat_code'];    // app客服地址
}

$host = getMainHost();
$weburl= HTTPS_HEAD.'://'.$host.'?topc=yes'; // 电脑版网址
$data['pc_url'] = $weburl;
// 返回模板名称
$data['tpl_name'] = TPL_NAME;
$data['company_name'] = COMPANY_NAME;
$data['code_open_switch'] = LOGIN_IS_VERIFY_CODE; // 前台验证码开关
// APP，点击试玩参观判断是否输入手机号  true 输入 false 无需输入
$data['guest_login_must_input_phone'] = GUEST_LOGIN_MUST_INPUT_PHONE;

// 判断是否开启签到，默认不开启
$standard = $redisObj->getSimpleOne('attendance_set_standard'); // 取redis 设置的值
$standard = json_decode($standard,true) ;
if(!$standard){
    $standard['standardmoney'] = 1000;
    $standard['standardswitch'] = 'not';
}
$data['signSwitch'] = $standard['standardswitch']=='open'?TRUE:FALSE;

// APP的2021新年活动开关 活动开始结束时间
$sRedPocketset = $redisObj->getSimpleOne('red_pocket_open'); // 取redis 设置的值
$aRedPocketset = json_decode($sRedPocketset,true) ;
if(!$aRedPocketset){
    $aRedPocketset['redPocketOpen'] = '';
    $aRedPocketset['newYearBeginTime'] = '';
    $aRedPocketset['newYearEndTime'] = '';
}
$data['redPocketOpen'] = $aRedPocketset['redPocketOpen']=='open'?TRUE:FALSE;
$data['newYearBeginTime'] = isset($aRedPocketset['newYearBeginTime'])?$aRedPocketset['newYearBeginTime']:'';
$data['newYearEndTime'] = isset($aRedPocketset['newYearEndTime'])?$aRedPocketset['newYearEndTime']:'';
$data['newSystemTime'] = date("Y-m-d H:i:s",time());    // 系统美东时间

// 会员注册控制必填字段-20200114
$redisObj = new Ciredis();
$registerConf = $redisObj->getSimpleOne('member_register_set');
$registerSet = json_decode($registerConf, true);
if(empty($registerSet)){ // 若未缓存，默认电话必填
    $registerSet = [
        'telOn' => 1,
        'chatOn' => 0,
        'qqOn' => 0,
        'aliasOn' => 0
    ];
}
$data['telOn'] = $registerSet['telOn'] ? TRUE : FALSE;
$data['chatOn'] = $registerSet['chatOn'] ? TRUE : FALSE;
$data['qqOn'] = $registerSet['qqOn'] ? TRUE : FALSE;
$data['aliasOn'] = $registerSet['aliasOn'] ? TRUE : FALSE;

$data['agentLoginUrl'] = returnAgentUrl();

original_phone_request_response(200, 'success', $data);
