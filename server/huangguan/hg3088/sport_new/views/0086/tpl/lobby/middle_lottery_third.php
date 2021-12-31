<?php
session_start();

include "../../../../app/member/include/config.inc.php";

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
<link rel="shortcut icon" href="<?php echo TPL_NAME;?>images/favicon.ico" type="image/x-icon">

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
<script type="text/javascript" src="/js/loadpage_common.js?v=<?php echo AUTOVER; ?>"></script>

<script type="text/javascript">
    $(function () {
        var cpload = layer.load(0, { // 加载层
            shade: [0.5,'#000'],
            time:alertTime
        });
       // getThirdLotteryAction('login');
        /*
        *  获取彩票登录链接
        *  type : login 登录
        *
        * */
        function getThirdLotteryAction(type){
            var url = '/app/member/api/thirdLotteryApi.php';
            $.ajax({
                type: 'POST',
                url: url,
                data: {actype:type},
                dataType: 'json',
                success: function (res) {
                    if(res){
                        if(res.status =='200'){
                            switch (type){
                                case 'login': // 登录
                                    $('#third_form').attr({'action':res.data.third_cpUrl});
                                    $('.cp_params').val(res.data.params);
                                    $('.cp_thirdlotteryid').val(res.data.thirdLotteryId);
                                    document.getElementById('third_form').submit(); // 打开彩票链接
                                break;
                            }

                        }else{ // 异常
                            layer.msg(res.describe,{time:alertTime});
                        }

                    }
                },
                error: function (res) {
                    layer.msg('数据获取失败，请稍后再试!',{time:alertTime});

                }
            });
        }


    })
</script>