<?php
include_once('../../include/config.inc.php');
//include_once ('../../include/activity.class.php');
$prokeys = isset($_REQUEST['prokey'])?$_REQUEST['prokey']:'' ;  // 活动图片key
$uid = $_SESSION['Oid']?$_SESSION['Oid']:(isset($_REQUEST['Oid'])?$_REQUEST['Oid']:'') ;
$userid = $_SESSION['userid']?$_SESSION['userid']:(isset($_REQUEST['userid'])?$_REQUEST['userid']:'') ;
$UserName = $_SESSION['UserName']?$_SESSION['UserName']:(isset($_REQUEST['UserName'])?$_REQUEST['UserName']:'') ;
$Agents = $_SESSION['Agents']?$_SESSION['Agents']:(isset($_REQUEST['Agents'])?$_REQUEST['Agents']:'') ;

$tip = isset($_REQUEST['tip'])?$_REQUEST['tip']:'' ; // 用于app 跳转到这个页面 ?tip=app
$type = isset($_REQUEST['type'])?$_REQUEST['type']:'' ; // 用于新年活动跳转 ， type=packet
$appRefer = isset($_REQUEST['appRefer'])?$_REQUEST['appRefer']:'' ; //app 标识
$platfrom = 'hg6668' ; // hg0086 ,hg6668

$lists = returnPromosList('',3);
$categoryList = returnPromosType();

// 活动于北京时间1月24号（除夕）中午12:00-次日11：59开始，活动时间持续24小时
$newYearBeginTime= '2020-01-21 00:00:00'; // 活动二开始时间
$newYearEndTime = '2020-01-28 23:59:59'; // 活动二结束时间
//$newYearBeginTime= '2020-01-13 00:00:00'; // 活动二开始时间
//$newYearEndTime = '2020-01-16 23:59:59'; // 活动二结束时间
$curtime = date("Y-m-d H:i:s",time()+12*60*60);
?>

<html class="zh-cn"><head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="HandheldFriendly" content="true"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <!--<link href="../../style/font-awesome.min.css" rel="stylesheet" type="text/css" media="screen"/>-->
    <link href="style/iphone.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css" media="screen"/>
    <title class="web-title"></title>
    <style type="text/css">
    .deposit .tab .item, .deposit .tab .expand{ margin-top: 0;border-top:none;}
    /*优惠活动*/
    .promo_nav {overflow-x: auto;background: #fff;}
    .promo{padding: 3% 3% 0 3%}
    .promotions_title_box {height:auto; }
    .promotions_title, .pro_btn {text-align: center;font-size: 15px; margin: 6px 0 8px 0; width: 100%; line-height: 26px; color: #757575; font-weight: 600;}
    .material-card-content {position:relative;max-height:0px;}
    .word_pro_sl b{color:#00b3e0;}
    .promotions_title_box img{width: 100%; height: auto; }
    .material-card-content img {width: 100%;}
    .material-card-content a:hover{background-color:transparent;}
    .material-card-content .pro_btn,.content .ny_hb_btn{position:absolute;display:block;width:32%;height:4rem;margin:85% 0 0 80%;background:transparent}
    .material-card-content .btn_attendance,.material-card-content .btn_chess{ margin: 73% 0 0 70%;}
    .material-card-content .btn_week{ margin: 89% 0 0 69%;}
    .material-card-content .btn_king{ margin: 126% 0 0 35%;}
    .material-card-content .btn_dragon{ margin: 150% 0 0 35%;}
    .material-card-content .btn_appLuck{ margin: 117% 0 0 34%;}
    .material-card-content .btn_sj_holiday{ margin: 120% 0 0 34%;}
    .material-card{padding: 0;}
    .ProTab_nav{width: 170%;height: 35px;}
    .ProTab_nav li{width: 12%;margin: 0;color: #000;border-radius: 0;border: 0;}
    .ProTab_nav li.on{background: none;color: #000;border-bottom: 2px solid #02a0e8;}

    .material-card-content .appLuckRed{position:absolute;width:100%;height:6rem;margin:31% 0 0}
    .material-card-content .btn_appDownload{margin:0 0 0 21%}
    .material-card-content .valid_num{position:absolute;width:100%;height:2rem;bottom:0}
    .material-card-content .valid_num span{display:inline-block;width:30%;line-height:2rem;padding-left:29%;color:#fff;font-size:1rem;overflow:hidden;text-overflow:ellipsis}
    .material-card-content .valid_num span:last-child{width:10%;padding-left:18.5%}

    /*优惠活动*/

    /* 新年活动 开始*/
    .content .btn_2020_6668 {visibility: hidden;}
    .content .ny_hb_btn{transition:.3s;margin:118% 0 0 30%;width:40%;height:50px;background:url(/images/hongbao/new/hb_btn.png) center no-repeat;background-size:100%}
    .content .ny_hb_btn:hover{transform:scale(1.05)}
    .hb_mask_1{display:none;position:fixed;left:0;top:0;z-index:4;width:100%;height:100%;background-color:rgba(0,0,0,0.5)}
    .hb_close_1{display:none;cursor:pointer;opacity:1;position:absolute;right:5%;top:5%;z-index:11;width:3rem;height:3rem;background-image:url("/images/hongbao/close.png");background-size: 100%;}
    .receiveAfter_1{ z-index:10;display:none;-webkit-transform:scale(0.1);transform:scale(0.1);position:absolute;width:100%;height:30rem;background:url(/images/hongbao/new/hb_bg.png) center no-repeat;background-size:100%;left:50%;top:50%;margin:-80% -50%;}
    .receiveAfter_1 .tip{padding-top:19rem}
    .receiveAfter_1 .tip p{color:#fff99a;font-size:2rem;text-align:center;line-height: 2.5rem;}
    .receiveAfter_1 .tip p span{color:#fff99a;}
    .reback{-webkit-animation:reback .5s linear forwards;animation:reback .5s linear forwards}
    @keyframes reback {
        100% {
            -webkit-transform: scale(1);
            transform: scale(1);
        }
    }
    /* 新年活动 结束*/


    </style>
</head>
<body >
<div id="container" class="content">
    <!-- 头部 -->
    <div class="header <?php if($tip){echo 'hide-cont';}?>">
    </div>
    <!-- 中间内容 -->
    <div class="content-center deposit">
        <!--  标签 -->
        <div class="promo_nav">
            <ul class="ProTab_nav css_flex">
                <li class="on"  data-type="all">全部</li>
                <?php foreach ($categoryList as $key => $category){?>
                    <li data-type="<?php echo $category['id'];?>"><?php echo $category['name'];?></li>
                <?php }?>
            </ul>
        </div>

        <!-- 背影 -->
        <div class="hb_mask_1">
            <div id="hongbao_animation"> </div>
        </div>

        <main class="main promo">

        <ul class="ProTab_con">
            <li class="ProTab_con_1" style="display:block">
                <?php foreach ($lists as $key => $activity){?>
                <div class="material-card promos_<?php echo $activity['type']?>">
                    <div class="promotions_title_box promos_id_<?php echo $activity['id']?> <?php echo ($activity['flag'] == '2020_6668'?'promotions_title_packet':'');?>" id="promos_id_<?php echo $activity['id']?>">
                        <img src="<?php echo $activity['imgurl'];?>">
                        <!--<div class="promotions_title"><?php /*echo $activity['title']*/?></div>-->
                    </div>
                    <div class="material-card-content">
                        <div class="line"></div>
                        <?php
                        if(TPL_FILE_NAME=='6668' && $activity['flag']=='appLuck'){ // app 幸运红包
                            echo '
                        <div class="appLuckRed">
                            <a class="pro_btn btn_appDownload" href="javascript:;" data-api="/download_app_gift_api.php" data-type="appDownload"></a>
                            <div class="valid_num">
                                <span class="valid_money">0</span>
                                <span class="last_times">0</span>
                            </div>
                        </div>
                        ';
                        }

                        if($activity['type'] == 7){

                            if($activity['flag'] == '2020_6668'){ // 新年红包
                                echo '  
                                     <!-- 关闭红包 -->
                                     <span class="hb_close_1"></span>
                                      <div class="receiveAfter_1 receiveAfter_act">
                                        <div class="tip">
                                            <p>恭喜您获得</p>
                                            <p> <span class="hb_mount">0</span><span>元红包</span> </p>
                                        </div>
                                    </div>
                                    <a class="ny_hb_btn" href="javascript:;"></a>';
                            }

                            echo '<a class="pro_btn btn_'.$activity['flag'].'" href="javascript:;" data-api="'.$activity['ajaxurl'].'"data-type="'.$activity['flag'].'"></a>';
                        }

                        ?>
                        <img src="<?php echo $activity['contenturl'];?>">
                    </div>
                </div>
                <?php } ?>

            </li>
        </ul>
        </main>
    </div>
    <!-- 底部footer -->
    <div id="footer" class="<?php if($tip){echo 'hide-cont';}?>">
    </div>
</div>
<script type="text/javascript" src="../../js/zepto.min.js"></script>
<script type="text/javascript" src="../../js/animate.js"></script>
<script type="text/javascript" src="../../js/zepto.animate.alias.js"></script>
<script type="text/javascript" src="../../js/src/hb.js"></script>
<script type="text/javascript" src="../../js/main.js?v=<?php echo AUTOVER; ?>"></script>

<script type="text/javascript">
    var uid = '<?php echo $uid?>' ;
    var usermon = getCookieAction('member_money') ; // 获取信息cookie
    var userid = '<?php echo $userid;?>' ;
    var UserName = '<?php echo $UserName;?>' ;
    var userAgents='<?php echo $Agents;?>';
    var platfrom = '<?php echo $platfrom;?>' ;
    var tipapp = '<?php echo $appRefer;?>';
    var cl_type = '<?php echo $type;?>';
    var tplfilename= '<?php echo TPL_FILE_NAME;?>';

    var newYearBeginTime = '<?php echo $newYearBeginTime;?>';
    var newYearEndTime  = '<?php echo $newYearEndTime;?>';
    var curtime = '<?php echo $curtime;?>';

    $(function () {
        if(cl_type == 'packet'){ // 展示新年红包
            $('.promotions_title_packet').click();
        }

    });

    var postData = {
        uid: uid ,
        user_id: userid ,
        username: UserName ,
        platfrom: platfrom
    };
    if(tipapp && tplfilename=='6668' && uid){
        postData.appRefer = tipapp;
        postData.AddDate = '<?php echo $_SESSION['AddDate'];?>';
        postData.Alias = '<?php echo $_SESSION['Alias'];?>';
        checkReceive();
    }
    setLoginHeaderAction('优惠活动','','',usermon,uid) ;
    setFooterAction(uid) ; // 在 addServerUrl 前调用

    unfoldPost();
    ProTab_Js();
    autoGetReceive();
    getRedBag();
    goToPromosDetail();
    // 优惠tab
    function ProTab_Js(){
        $('.ProTab_nav').on('click','li',function () {
            var type = $(this).attr('data-type');

            $(this).addClass('on').siblings().removeClass('on');
            if(type == 'all'){ // 全部
                $('.material-card').fadeIn(300) ;
            }else{
                $('.material-card').hide() ;
                $(".promos_"+type).fadeIn(300).siblings();
            }

        })
    }

    function unfoldPost() {
        var actionButton = $(".promotions_title_box");
        actionButton.on("click", function(e) {
            e.preventDefault();
            $(this).closest(".material-card").toggleClass("triggered");
            $(".no_count").fadeOut(350);//關閉優惠也關閉不计算名单
        });
    }
    // 跳转到对应的优惠活动详情
    function goToPromosDetail(){
        var key = '<?php echo $prokeys;?>';
        if(key){
            $('.promos_id_'+key).click();
        }
    }
    // 不计算名单
    $(document).on("click", '.no_count_toggle', function(){
        $(".no_count").toggle("fade", 350);
    });

    // 自动领取
    function autoGetReceive() {
        var proflage = false ;
        var $hb_mask_1 = $('.hb_mask_1'); // 新年活动一
        var $receiveAfter_act = $('.receiveAfter_act');// 领取后显示
        var $hb_close_1 = $('.hb_close_1');// 关闭按钮

        $('.pro_btn').on('click',function () {
            var type = $(this).attr('data-type');
            postData.action = 'receive_red_envelope';
            postData.type_flag = type;

            if(!uid){
                setPublicPop("请先登录!!");
                return false ;
            }
            if(userAgents=='demoguest'){
                setPublicPop("请注册真实用户！");
                return false ;
            }
            if(!tipapp){
                if(type =='appLuck' || type=='appDownload') { // app 幸运红包
                    setPublicPop('请在APP优惠活动领取');
                    return false;
                }
            }
            if(proflage){
                return ;
            }
            var url = $(this).data('api') ;
            proflage = true ;
            $.ajax({
                type: 'POST',
                url: url,
                data: postData,
                dataType: 'json',
                success:function(res){
                    if(res){
                        proflage = false ;
                        if(type =='appLuck' || type=='appDownload'){ // app 幸运红包
                            setPublicPop(res.describe);
                        }else if(type == '2020_6668'){ // 新年活动
                            if(res.status=='200'){ // 领取成功 不需要弹出提示
                                $receiveAfter_act.show().addClass('reback');
                                $hb_close_1.show();
                                $('.hb_mount').text(res.data.giftGold);
                            }else{
                                $hb_mask_1.hide();
                                $receiveAfter_act.hide();
                                $hb_close_1.hide();
                                setPublicPop(res.describe);
                            }
                        }else{
                            if(res.info){
                                setPublicPop(res.info);
                            }else{
                                setPublicPop(res.describe);
                            }
                        }

                    }
                },
                error:function(){
                    proflage = false ;
                    setPublicPop(config.errormsg) ;
                }
            });
        });
    }

    // 查询可领取次数
    function checkReceive() {
        var apiUrl = '/lucky_red_envelope_api.php';
        postData.action = 'get_valid';
        $.ajax({
            type: 'POST',
            url: apiUrl,
            data: postData,
            dataType: 'json',
            success:function(res){
                if(res.data){
                    $('.valid_money').text(res.data[0].valid_money);
                    $('.last_times').text(res.data[0].last_times);
                }
            },
            error:function(){

            }
        });
    }

    // 点击红包
    function getRedBag(){
        var $hb_mask_1 = $('.hb_mask_1'); // 新年活动一
        var $receiveAfter_act = $('.receiveAfter_act');// 领取后显示
        var $hb_close_1 = $('.hb_close_1');// 关闭按钮

        $hb_close_1.on('click',function () { // 关闭新年红包
            $(this).hide();
            $hb_mask_1.hide();
            $receiveAfter_act.hide().removeClass('reback');
        });

        $('.ny_hb_btn').off().on('click',function () { // 召唤红包雨
            if(!uid){
                setPublicPop('请先登录');
                return ;
            }
            if(curtime < newYearBeginTime || curtime > newYearEndTime){
                setPublicPop('请于北京时间1月24号-1月28号期间领取红包!');
                return false;
            }
            $hb_mask_1.show();
            hbInit(); // 红包雨
        });
        $('#hongbao_animation').on('click','.hongbao_li img',function () {  // 点击红包，抢红包
            var type = $(this).attr('data-type');
            if(type=='hb'){
                $('.btn_2020_6668').click();
            }
        })
    }


</script>
</body>
</html>