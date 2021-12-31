<?php
session_start();
/**
 * 第三方彩票Api
 *
 */

include_once("../include/address.mem.php");
include_once("../include/config.inc.php");

$aData = [] ;
$uid = $_SESSION['Oid']; // 判断是否已登录
$actype = isset($_REQUEST['actype'])?$_REQUEST['actype']:''; // 请求类型(actype : login 登录)
$test_flag = $_SESSION['test_flag']; // 0 正式帐号，1 测试账号

if( !isset($uid) || $uid == "" ) {
    $status = '502';
    $describe = '你已退出登录，请重新登录';
    original_phone_request_response($status,$describe,$aData);
}

function desEncode($str,$key){
    $encrypt_str = openssl_encrypt($str, 'DES-ECB', $key, 0);
    return base64_encode($encrypt_str);
}

// 模拟登录 注册帐号
function login_post($url, $cookie, $post) {
    if(!$url){return false;}
    $curl = curl_init();//初始化curl模块
    curl_setopt($curl, CURLOPT_URL, $url);//登录提交的地址
    curl_setopt($curl, CURLOPT_HEADER, 0);//是否显示头信息
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (iPhone; CPU iPhone OS 11_0 like Mac OS X) AppleWebKit/604.1.38 (KHTML, like Gecko) Version/11.0 Mobile/15A372 Safari/604.1');//这里设置UserAgent (或者 Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.57 Safari/537.36)
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 0);//是否自动显示返回的信息
    curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie); //设置Cookie信息保存在指定的文件中
    curl_setopt($curl, CURLOPT_POST, 1);//post方式提交
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));//要提交的信息
    // curl_exec($curl);//执行cURL, 输出内容
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($curl); // 已经获取到内容，没有输出到页面上。
    curl_close($curl);//关闭cURL资源，并且释放系统资源
}


$redisObj = new Ciredis();
$datajson = $redisObj->getSimpleOne('thirdLottery_api_set'); // 取redis 设置的值
$datajson = json_decode($datajson,true) ; // 第三方配置信息

// 1.检测当前登录会员&查询会员信息
$stmt = $dbLink->prepare("SELECT `ID`, `Money`, `test_flag`, `UserName`, `Agents`, `World`, `Corprator`, `Super`, `Admin`, `Phone` FROM " . DBPREFIX.MEMBERTABLE." where `Oid` = ? and Status <= 1 LIMIT 1");
$stmt->bind_param('s', $uid);
$stmt->execute();
if(!$stmt->affected_rows) {
    $status = '500.1';
    $describe = '您的登录信息已过期，请您重新登录！';
    original_phone_request_response($status,$describe,$aData);
}
$aUser = $stmt->get_result()->fetch_assoc();
$now = date('Y-m-d H:i:s');
switch ($actype){
    case 'login': // 登录
        if($test_flag){ // 试玩帐号
            $third_cpUrl = $datajson['apiurl'].'/guest' ; // 彩票登录 需要 POST 请求
        }else{ // 正式登陆
            $third_cpUrl = $datajson['apiurl'].'/auth/signin' ; // 彩票登录 需要 POST 请求
        }
        // $third_cpUrl = $datajson['apiurl'].'/auth/signin?username='.$_SESSION['UserName'].'&password='.$_SESSION['thirdPassword'] ;
        $paramarr = http_build_query(array(
            'username' => $_SESSION['thirdUserName'],
            'password' => $_SESSION['originPassword'],
        ));
        $params = desEncode($paramarr,$datajson['agentid']);

        $result = mysqli_query($dbLink, "SELECT `id` FROM `" . DBPREFIX . "cp_member_data` WHERE `userid` = '{$aUser['ID']}' LIMIT 1");
        $row = mysqli_num_rows($result);
        if(!$row && !$test_flag) { // 未创建账号需要创建账号，试玩账号不需要插入
            // 导入过来的彩票平台老用户，是否增加代理前缀（以全新用户来登录），TRUE 需要前缀，FALSE 不需要前缀
//            if (USERNAME_NEED_CP_AGENT_PREFIX){
//                $third_UserName = $datajson['agentid'].'_'.$aUser['UserName'];
//            }else{
//                $third_UserName = $_SESSION['third_PassWord'] ? $aUser['UserName'] : $datajson['agentid'].'_'.$aUser['UserName'];
//            }
            $data = [
                'userid' => $aUser['ID'],
                'username' => $_SESSION['thirdUserName'],
                'line_code' => '',
                'agents' => $aUser['Agents'],
                'world' => $aUser['World'],
                'corporator' => $aUser['Corprator'],
                'super' => $aUser['Super'],
                'admin' => $aUser['Admin'],
                'register_time' => $now,
                'last_launch_time' => $now,
                'launch_times' => 1,
                'is_test' => $aUser['test_flag']
            ];
            $sInsData = '';
            foreach ($data as $key => $value) {
                $sInsData .= "`$key` = '{$value}'" . ($key == 'is_test' ? '' : ',');
            }
            $sql = "INSERT INTO `" . DBPREFIX . "cp_member_data` SET $sInsData";
            if (!mysqli_query($dbMasterLink, $sql)) {
                $status = '500.2';
                $describe = '彩票账号异常，请您稍后重试！';
                original_phone_request_response($status,$describe,$aData);
            }else{ // 新用户 创建成功，创建彩票帐号
//                $seturl = $third_cpUrl.'?params='.$params.'&thirdLotteryId='.$datajson['agentid'];
//                echo $seturl;
                $aData = array(
                    'third_cpUrl'=> $third_cpUrl,
                    'params'=> $params,
                    'thirdLotteryId'=> $datajson['agentid'],
                ) ;
                //设置cookie保存路径
                $cookie = '/tmp/gmcp_log/cookie_lottery_login.txt';
                //模拟登录
                login_post($third_cpUrl, $cookie, $aData);
            }

        }

        $aData = array(
            'third_cpUrl'=> $third_cpUrl,
            'params'=> $params,
            'thirdLotteryId'=> $datajson['agentid'],
        ) ;

        break;



}

$status = '200';
$describe = '链接请求成功';
original_phone_request_response($status,$describe,$aData);



