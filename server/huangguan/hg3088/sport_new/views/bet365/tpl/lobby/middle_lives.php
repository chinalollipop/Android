<?php
session_start();

include "../../../../app/member/include/config.inc.php";
$uid = $_SESSION['Oid'];

$test_username = explode('_',$agsxInit['tester']);
$test_username = $test_username[1]; // AG测试账号用户名

?>
<style>
    #visualbox li{float:left;width:326px;height:242px;margin-top:6px;margin-left:2.5px;margin-right:2.5px}
    #visualbox li p{line-height:26px}
    #visualbox a.v_img{display:block;width:326px;height:216px;margin-top:5px}
    #visualbox a.v_img:hover{background-position:left bottom;-webkit-transition:all ease 0.2s;-moz-transition:all ease 0.2s;-o-transition:all ease 0.2s;transition:all ease 0.2s}
    .video ul li a{color:#FFFFFF}
    #visualbox a.v_op {background:url(<?php echo TPL_NAME;?>images/live/op.png) no-repeat;}
    #visualbox a.v_ag{background:url(<?php echo TPL_NAME;?>images/live/ag.png) no-repeat}
    #visualbox a.v_bb{background:url(<?php echo TPL_NAME;?>images/live/bb.png) no-repeat}
    #visualbox a.v_dev {background:url(<?php echo TPL_NAME;?>images/live/st.png) no-repeat;}
    #rightsidebar { width:100%;}
    #sidebarbox{ width:1060px;}

</style>

<div class="live_main">
    <div id="new-banner">
        <div id="new-banner-box">
            <div id="banner"><img src="<?php echo TPL_NAME;?>images/live/6.jpg"></div>
            <div class="msg-connet">

                <div class="left" style="margin-lefT:8px;">
                    <div><a href="javascript:;" class="to_lives ylc_top"></a></div>
                    <div> <a href="javascript:;" class="to_lives ylc_left"></a>
                        <a href="javascript:;" class="to_lives ylc_right"></a> </div>
                </div>

            </div>
        </div>
    </div>

    <div id="sidebarwrap">
        <div id="sidebarbox">

            <div id="rightsidebar">
                <div id="main" class="video">

                    <ul id="visualbox">
                        <li>
                            <a href="javascript:;" class="v_img v_op" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/og/og_api.php?action=getLaunchGameUrl')"></a>
                            <p><a href="javascript:;">OG+东方馆简介</a></p>
                        </li>
                        <li>
                            <a href="javascript:;" class="v_img v_ag" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/login.php?uid=<?php echo $uid;?>')"></a>
                            <p>
                                <a href="javascript:;" class="start" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/login.php?uid=<?php echo $uid;?>&username=<?php echo $test_username;?>')">免费试玩</a>
                                <span> | </span><a href="javascript:;">AG国际馆简介</a>
                            </p>
                        </li>
                        <li>
                            <a href="javascript:;" class="v_img v_bb" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/bbin/bbin_api.php?action=getLaunchGameUrl')"></a>
                            <p>
                                <a href="javascript:;">BBIN馆简介</a>
                            </p>
                        </li>
                        <li class="doub doudeve">
                            <a href="javascript:;" class="v_img v_dev"></a>
                            <p><a href="javascript:;" class="start1">开发中...</a></p>
                        </li>
                        <li class="doub doudeve">
                            <a href="javascript:;" class="v_img v_dev"></a>
                            <p><a href="javascript:;" class="start1">开发中...</a></p>
                        </li>
                        <li class="doub doudeve">
                            <a href="javascript:;" class="v_img v_dev"></a>
                            <p><a href="javascript:;" class="start1">开发中...</a></p>
                        </li>
                    </ul>
                    <div class="clear"></div>
                </div>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {

        indexCommonObj.getUserQpBanlance(uid,'ag') ;
        $('.download_win_exe').attr('href',configbase.exeWinUrl);
        $('.download_mac_exe').attr('href',configbase.macWinUrl);

        changeLiveTab();
        // 视讯切换
        function changeLiveTab() {
            $('.live_right_top').on('click','a',function () {
               var type = $(this).attr('data-to');
               if(!type){
                   return false;
               }else{
                   $(this).addClass('active').siblings().removeClass('active');
                    $('.show_act').hide();
                    $('.show_'+type).fadeIn();
               }
            });
        }
    })
</script>