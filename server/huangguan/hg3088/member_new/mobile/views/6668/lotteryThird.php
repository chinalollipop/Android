<?php
session_start();

include_once('../../include/config.inc.php');

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
<html class="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="HandheldFriendly" content="true"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="style/iphone.css?v=<?php echo AUTOVER; ?>" >
    <style>
        iframe{height:100%;}
    </style>
</head>
<body>
<!-- 第三方彩票地址 -->
<!-- 正式登陆POST ，测试登陆GET-->
<!--<form id="third_form"  target="third_cp_url" method="<?php /*echo $test_flag==1?'get':'post'; */?>">
    <input type="hidden" name="params" class="cp_params" />
    <input type="hidden" name="thirdLotteryId" class="cp_thirdlotteryid" />
    <input type="hidden" name="toXinyong" />
</form>-->

<!--<iframe name="third_cp_url" id="third_cp_url"  noresize src="<?php /*echo $third_cpUrl;*/?>" ></iframe>-->

<script type="text/javascript" src="../../js/zepto.min.js"></script>
 <script type="text/javascript" src="../../js/main.js?v=<?php echo AUTOVER; ?>"></script>

<script type="text/javascript">
    $(function () {

        var to_type = '<?php echo $type; ?>' ;
        getThirdLotteryAction('login');
        /*
        *  获取彩票登录链接
        *  type : login 登录
        *
        * */
        function getThirdLotteryAction(type){
            var url = '/api/thirdLotteryApi.php';
            $.ajax({
                type: 'POST',
                url: url,
                data: {actype:type},
                dataType: 'json',
                success: function (res) {
                    if(res){
                        if(res.status =='200'){
                            window.location.href = res.data.third_cpUrl+'?params='+res.data.params+'&thirdLotteryId='+res.data.thirdLotteryId+'&toXinyong='+to_type ;
                            // switch (type){
                            //     case 'login': // 登录
                            //         $('#third_form').attr({'action':res.data.third_cpUrl});
                            //         $('.cp_params').val(res.data.params);
                            //         $('.cp_thirdlotteryid').val(res.data.thirdLotteryId);
                            //         document.getElementById('third_form').submit(); // 打开彩票链接
                            //         break;
                            // }

                        }else{ // 异常
                            setPublicPop(res.describe);
                        }

                    }
                },
                error: function (res) {
                    setPublicPop('数据获取失败，请稍后再试!');

                }
            });
        }


    })
</script>
</body>
</html>