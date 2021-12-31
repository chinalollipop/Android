<?php
session_start();

ini_set('display_errors','OFF');
include "../app/member/include/config.inc.php";
include "../app/member/include/address.mem.php";

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
    exit;
}
// 判断会员状态是否启用，否则退出
if ($_SESSION['Status'] != 0){
    echo "<script>alert('非常抱歉，您的账号已冻结或已停用，请您联系客服！')</script>";
    exit;
}

//  单页面维护功能
checkMaintain('thirdcp');
$test_flag = $_SESSION['test_flag']; // 0 正式帐号，1 测试账号
$type = isset($_REQUEST['type'])?$_REQUEST['type']:''; // 加载第三方彩票 toXinyong:是否跳转到信用盘,type: 不传即是默认官方，要到信用就传 1
$gametype = isset($_REQUEST['gametype'])?$_REQUEST['gametype']:''; //  gametype：进入到某个彩种，不传即是默认彩种，官方是欢乐生肖(buy/bet/xcqssc)，信用是北京赛车( userthirdplat/login/ssc/76 )
$redisObj = new Ciredis();
$datajson = $redisObj->getSimpleOne('thirdLottery_api_set'); // 取redis 设置的值
$datajson = json_decode($datajson,true) ; // 第三方配置信息

$af_cpurl = '/buy/bet/';
$game_type = 'xcqssc'; // 官方默认
if($gametype){
    $game_type = $gametype;
}
if($type){ // 到信用盘
    $af_cpurl = '/userthirdplat/login/ssc/';
    $game_type = '76'; // 信用默认
    if($gametype){
        $game_type = $gametype;
    }

}

$third_cpUrl = $datajson['apiurl'].$af_cpurl.$game_type.'?thirdLotteryId=yes' ; // 彩票登录 需要 POST 请求

?>
<link rel="shortcut icon" href="/images/favicon_<?php echo TPL_FILE_NAME;?>.ico" type="image/x-icon">

<style>
    body{background: #fff;margin: 0;padding: 0;}
    iframe{border:0;width: 100%;}
</style>
<!-- 第三方彩票地址 -->
<!-- 正式登陆POST ，测试登陆GET toXinyong:是否跳转到信用盘：是就传 1，要不就空 -->

<!--<form id="third_form"  target="third_cp_url" method="<?php /*echo $test_flag==1?'get':'post'; */?>">
    <input type="hidden" name="params" class="cp_params" />
    <input type="hidden" name="thirdLotteryId" class="cp_thirdlotteryid" />
    <input type="hidden" name="toXinyong" />

</form>-->

<!--<iframe name="third_cp_url" id="third_cp_url"  noresize src="" height="1000"></iframe>-->
<iframe name="third_cp_url" id="third_cp_url"  noresize src="<?php echo $third_cpUrl;?>" height="1000"></iframe>

<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/layer/layer.js"></script>


<script type="text/javascript">
    var alertTime = 2000 ; // 弹窗提示时间
    $(function () {
        var cpload = layer.load(0, { // 加载层
            shade: [0.5,'#000'],
            time:alertTime
        });


    })
</script>