<?php
session_start();
include("../include/address.mem.php");
require_once("../include/config.inc.php");
require("../include/define_function_list.inc.php");


checkAdminLogin(); // 同一账号不能同时登陆

if( (!isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG) && $_SESSION['admin_level'] != 'D' ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$uid=$_REQUEST["uid"];
$loginname=$_SESSION['UserName'];
$demourl = isset($_REQUEST["demourl"]) && $_REQUEST["demourl"] ? $_REQUEST["demourl"] : ''; // 试玩地址
$apiurl = isset($_REQUEST["apiurl"]) && $_REQUEST["apiurl"] ? $_REQUEST["apiurl"] : ''; // 接口地址
$ld_apiurl = isset($_REQUEST["ld_apiurl"]) && $_REQUEST["ld_apiurl"] ? $_REQUEST["ld_apiurl"] : ''; // 拉单接口地址
$agentid = isset($_REQUEST["agentid"]) && $_REQUEST["agentid"] ? $_REQUEST["agentid"] : ''; // 代理商ID
$type = isset($_REQUEST["type"]) && $_REQUEST["type"] ? $_REQUEST["type"] : 'third_cp';
$linecode = isset($_REQUEST["lineCode"]) && $_REQUEST["lineCode"] ? $_REQUEST["lineCode"] : ''; // 代理商 lineCode
$deskey = isset($_REQUEST["deskey"]) && $_REQUEST["deskey"] ? $_REQUEST["deskey"] : ''; // deskey
$md5key = isset($_REQUEST["md5key"]) && $_REQUEST["md5key"] ? $_REQUEST["md5key"] : ''; // md5key
$setKey = 'thirdLottery_api_set';

$datatime=date('Y-m-d H:i:s');

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
if($demourl || $apiurl || $ld_apiurl || $agentid){
    gameUpdateSetting();
}

$datajson = $redisObj->getSimpleOne($setKey); // 取redis 设置的值
$datajson = json_decode($datajson,true) ;


if($datajson){ // 取redis
    $af_demourl = $datajson['demourl'];
    $af_apiurl =$datajson['apiurl'];
    $af_ld_apiurl =$datajson['ld_apiurl'];
    $af_agentid =$datajson['agentid'];
    $af_deskey =$datajson['deskey'];
}else{ // 读取数据库
    $sql = "select type,test_Url,interface_Url,record_Url,Agents,Deskey from ".DBPREFIX."web_gameSetting where type='$type'";
    // echo $sql;
    $result = mysqli_query($dbLink,$sql);
    $row = mysqli_fetch_assoc($result);

    $af_demourl = $row['test_Url'];
    $af_apiurl =$row['interface_Url'];
    $af_ld_apiurl =$row['record_Url'];
    $af_agentid =$row['Agents'];
    $af_deskey = $row['Deskey'];

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
        <title>第三方彩票设置</title>
        <meta http-equiv="Content-Type" content="text/html; charset=gbk">
        <link rel="stylesheet" href="/style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
        <style type="text/css">

        </style>
    </head>
    <body >

    <dl class="main-nav">
        <dt>第三方彩票设置</dt>
        <dd>

        </dd>
    </dl>

    <div class="main-ui ">
        <div class="width_1300">

            <form action="thirdLotterySet.php?uid=<?php echo $uid?>&type=third_cp&lv=<?php echo $level?>"  method="post" name="form1" >
                <table class="m_tab">
                    <tr class="m_title">
                        <td>试玩地址</td>
                        <td>接口地址(如：http://52070887.com/buy/bet/bjpk10 默认跳转到北京赛车)</td>
                        <td>拉单独立接口</td>
                        <td>代理商ID</td>
                        <td>Md5key</td>
                        <td>操作</td>
                    </tr>
                    <tr class="m_title">
                        <!--<input class="za_text" type="hidden" name="type" value="<?php /*echo $row['type'];*/?>" >-->
                        <td> <input class="za_text" type="text" name="demourl" value="<?php echo $af_demourl;?>" > </td>
                        <td> <input class="za_text" type="text" name="apiurl" value="<?php echo $af_apiurl;?>" > </td>
                        <td> <input class="za_text" type="text" name="ld_apiurl" value="<?php echo $af_ld_apiurl;?>" > </td>
                        <td> <input class="za_text" type="text" name="agentid" value="<?php echo $af_agentid;?>" readonly > </td>
                        <td> <input class="za_text" type="text" name="deskey" value="<?php echo $af_deskey;?>" readonly > </td>
                        <td> <input type="submit" class="tj_btn za_button" value="提交" /> </td>
                    </tr>

                </table>
            </form>
        </div>
    </div>
    <script type="text/javascript" src="../../../js/agents/register/laydate.min.js"></script>
    </body>
    </html>
