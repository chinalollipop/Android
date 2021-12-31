<?php
session_start();
$tplfilename = $_SESSION['TPL_FILE_NAME_SESSION'];

?>
<html>
<head>

</head>
<body>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>客服中心</title>

<style>
    body,html{overflow-y:auto;height: 100%;}
    body{ margin:0; padding:0; text-align:center}
    .kf_content{position:relative;text-align: left; margin-left: 10px; width:100%; border:0px;}
    .ke_center,.dh_class{display:block;position:absolute;width:177px;height:38px;top:264px;left:686px}
    .dh_class{width:375px;top:210px;left:34px}
    .ke_center.df_ke_center{width: 213px;top: 436px;left: 318px;}
    .ke_center.ke_center_0086{left: 298px;}
    .online_wechat_img{display: inline-block;width: 160px;height: 160px;background-size: 100%;position: absolute;bottom: 85px;left: 349px;}
    .wechat_img_0086{width: 110px;height: 110px;bottom: 94px;left: 458px;}
    .kf_lxfs{position:absolute;top:99px;left:374px;font-size:17px;color:#c2a45e;line-height:33px}
    .kf_lxfs_0086{font-size:15px;color:#fff;left:230px;top:166px}
    .kf_lxfs_0086 .span_1,.kf_lxfs_0086 .span_2,.kf_lxfs_0086 .span_6{color:#d50909}
    .kf_lxfs span{display:inline-block;width:200px}
    .kf_lxfs_0086 span{width:160px}
    .kf_lxfs span.span_1,.kf_lxfs span.span_6{margin-left:30px}
    .kf_lxfs span.span_2{margin-left:78px}
    .kf_lxfs span.span_4{margin-left:128px}
    .kf_lxfs_0086 span.span_2{margin-left:0}
    .kf_lxfs_0086 span.span_3,.kf_lxfs_0086 span.span_4{margin-left:68px}
    .kf_lxfs_0086 span.span_5{margin-left:64px}
</style>

<div class="kf_content" >
    <div class="kf_lxfs kf_lxfs_<?php echo $tplfilename;?>">
        <?php
            if($tplfilename=='0086'){
                echo ' 
                        <span class="span_1 new_web_url"></span>
                        <span class="span_3 service_phone_phl"></span></br>
                        <span class="span_6 backup_web_url"></span>
                        <span class="span_4 service_phone_24"></span></br>
                        <span class="span_2 email_address"></span></br>
                        <span class="span_5 service_qq"></span>
                        ';
            }else{
                echo '
                    <span class="span_1 new_web_url"></span>
                    <span class="span_2 email_address"></span></br>
                    <span class="span_3 service_phone_phl"></span>
                    <span class="span_4 service_phone_24"></span>
                ';
            }
        ?>

    </div>
    <span class="online_wechat_img wechat_img_<?php echo $tplfilename;?>"></span>
    <img class="kf_img" style="border:0px;" >
    <a target="_blank" class="to_livechat ke_center ke_center_<?php echo $tplfilename;?>"></a>
</div>
<script type="text/javascript" src="/js/jquery.js"></script>
<script>

$(function () {
    if(top.tplfilename=='0086'){
        $('.to_livechat').addClass('df_ke_center');
    }
    $('.new_web_url').text(top.configbase.new_web_url); // 最新网址
    $('.email_address').text(top.configbase.service_email); // 邮箱
    $('.service_phone_phl').text(top.configbase.service_phone_phl); // 投诉电话
    $('.service_phone_24').text(top.configbase.service_phone_24); // 24小时电话
    $('.service_qq').text(top.configbase.service_qq); // qq
    $('.backup_web_url').text(top.configbase.backup_web_url); // 官方网址

    $('.to_livechat').attr({"href":top.configbase.service_meiqia}); // 在线客服
    $('.kf_img').attr({"src":"/images/kfzx_"+top.tplfilename+".png?v=3"});
    $('.online_wechat_img').css({"background-image":"url("+top.webPicConfig.server_wechat_code+")"});
})
</script>
</body>
</html>