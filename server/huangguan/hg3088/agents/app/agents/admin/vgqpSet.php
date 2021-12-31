<?php
/**
 * 棋牌管理-VG棋牌
 * Date: 2020/07/11
 */
session_start();
require_once '../include/config.inc.php';
include_once "../include/address.mem.php";
require_once '../include/redis.php';

// 验证同一账号不能同时登陆
checkAdminLogin();

// 验证管理员session
if( (!isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG) && $_SESSION['admin_level'] != 'D' ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

// 接收参数
$uid = isset($_REQUEST["uid"]) && $_REQUEST["uid"] ? $_REQUEST["uid"] : '';
$langx = isset($_REQUEST["langx"]) && $_REQUEST["langx"] ? $_REQUEST["langx"] : 'zh-cn';
$type = isset($_REQUEST["type"]) && $_REQUEST["type"] ? $_REQUEST["type"] : 'vg';
$level=$_REQUEST['level'];

$demourl = isset($_REQUEST["demourl"]) && $_REQUEST["demourl"] ? $_REQUEST["demourl"] : ''; // 试玩地址
$apiurl = isset($_REQUEST["apiurl"]) && $_REQUEST["apiurl"] ? $_REQUEST["apiurl"] : ''; // 接口地址
$ld_apiurl = isset($_REQUEST["ld_apiurl"]) && $_REQUEST["ld_apiurl"] ? $_REQUEST["ld_apiurl"] : ''; // 拉单接口地址
$agentid = isset($_REQUEST["agentid"]) && $_REQUEST["agentid"] ? $_REQUEST["agentid"] : ''; // 代理商ID
$linecode = isset($_REQUEST["lineCode"]) && $_REQUEST["lineCode"] ? $_REQUEST["lineCode"] : ''; // 代理商 lineCode
$deskey = isset($_REQUEST["deskey"]) && $_REQUEST["deskey"] ? $_REQUEST["deskey"] : ''; // deskey
$md5key = isset($_REQUEST["md5key"]) && $_REQUEST["md5key"] ? $_REQUEST["md5key"] : ''; // md5key
$setKey = 'vgqp_api_set';

$setdata = array(
    'demourl'=>$demourl,
    'apiurl'=>$apiurl,
    'ld_apiurl'=>$ld_apiurl,
    'agentid'=>$agentid,
    'lineCode'=>$linecode,
    'deskey'=>$deskey,
    'md5key'=>$md5key,
);

$redisObj = new Ciredis();
if($demourl || $apiurl || $ld_apiurl || $agentid || $deskey || $md5key){
    gameUpdateSetting();
}

$datastr = getVgQpSetting() ;

if(!$datastr){ // 没有redis 数据
    $sql = "select type,test_Url,interface_Url,record_Url,Agents,AgentsCode,Deskey,Md5key from ".DBPREFIX."web_gameSetting where type='$type'";
   // echo $sql;
    $result = mysqli_query($dbLink,$sql);
    $row = mysqli_fetch_assoc($result);

    $datastr['demourl'] = $row['test_Url'];
    $datastr['apiurl'] =$row['interface_Url'];
    $datastr['ld_apiurl'] =$row['record_Url'];
    $datastr['agentid'] =$row['Agents'];
    $datastr['lineCode'] =$row['AgentsCode'];
    $datastr['deskey'] = $row['Deskey'];
    $datastr['md5key'] = $row['Md5key'];
}

// 更新或插入数据
function gameUpdateSetting(){
    global $dbMasterLink,$dbLink,$type,$setdata,$redisObj,$setKey;
    $curtime = date('Y-m-d H:i:s');
    $table = DBPREFIX."web_gameSetting" ;
    $sql = "select id from ".$table." where type='$type'";
    $result = mysqli_query($dbLink,$sql);
    $count = mysqli_num_rows($result);
    $test_url = $setdata['demourl'];
    $interface_Url = $setdata['apiurl'];
    $record_Url = $setdata['ld_apiurl'];
    $agent_Id = $setdata['agentid'];
    $AgentsCode = $setdata['lineCode'];
    $des_key = $setdata['deskey'];
    $Md5_key = $setdata['md5key'];

    if($count>0){ // 已存在数据
        // 修改数据表
        $se_sql = "update ".$table." SET test_url='$test_url',interface_Url='$interface_Url',record_Url='$record_Url',Agents='$agent_Id',AgentsCode='$AgentsCode',Deskey='$des_key',Md5key='$Md5_key' where type='$type'";
    }else{
        $se_sql = "insert into ".$table." set type='$type',test_url='$test_url',interface_Url='$interface_Url',record_Url='$record_Url',Agents='$agent_Id',AgentsCode='$AgentsCode',Deskey='$des_key',Md5key='$Md5_key',keepTime='$curtime' ";
    }
    mysqli_query($dbMasterLink,$se_sql) or die ("操作失败!");
    $redisObj->setOne($setKey,json_encode($setdata)) ;   // 设置redis
}


?>
<html>
<head>
    <title>VG棋牌</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="/style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <style type="text/css">
        .m_title td input.za_text {width: auto;}
        .m_title td:nth-child(4) input.za_text,.m_title td:nth-child(5) input.za_text {width: 70px;}
    </style>
</head>
<body >
<dl class="main-nav">
    <dt>VG棋牌设置</dt>
    <dd>

    </dd>
</dl>

<div class="main-ui ">
    <div class="width_1300">

        <form action="vgqpSet.php?uid=<?php echo $uid?>&type=vg&lv=<?php echo $level?>"  method="post" name="form1" >
            <table class="m_tab">
                <tr class="m_title">
                    <td>试玩地址</td>
                    <td>接口地址</td>
                    <td>拉单独立接口</td>
                    <td>代理商ID</td>
                    <td>代理商linecode</td>
                    <td>Deskey</td>
                    <td>Md5key</td>
                    <td>操作</td>
                </tr>
                <tr class="m_title">
                    <td> <input class="za_text" type="text" name="demourl" value="<?php echo $datastr['demourl'];?>" > </td>
                    <td> <input class="za_text" type="text" name="apiurl" value="<?php echo $datastr['apiurl'];?>" > </td>
                    <td> <input class="za_text" type="text" name="ld_apiurl" value="<?php echo $datastr['ld_apiurl'];?>" > </td>
                    <td> <input class="za_text" type="text" name="agentid" value="<?php echo $datastr['agentid'];?>" readonly > </td>
                    <td> <input class="za_text" type="text" name="lineCode" value="<?php echo $datastr['lineCode'];?>" readonly > </td>
                    <td> <input class="za_text" type="text" name="deskey" value="<?php echo $datastr['deskey'];?>" readonly > </td>
                    <td> <input class="za_text" type="text" name="md5key" value="<?php echo $datastr['md5key'];?>" readonly > </td>
                    <td> <input type="submit" class="tj_btn za_button" value="提交" /> </td>
                </tr>

            </table>
        </form>
    </div>
</div>
</body>

</html>




