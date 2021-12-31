<?php
session_start();
$tplNmaeSession = $_SESSION['TPL_NAME_SESSION'];
$uid = $_SESSION['Oid'];
$host = $_SESSION['HOST_SESSION'];

$key = isset($_REQUEST['key'])?$_REQUEST['key']:'' ;

$companyName = $_SESSION['COMPANY_NAME_SESSION'];

?>
<style>
    .fullpage-wrapper{color: color:#666;}
    dl,form,p{margin:0 0 10px;color:#666}
    .basic-inverse,.basic-white{background-color:#fff;color:#666}
    .panel{margin:0 auto;width:1000px;height:580px;background-color:#fff;-webkit-box-shadow:0 2px 18px 0 rgba(0,0,0,.05);box-shadow:0 2px 18px 0 rgba(0,0,0,.05);border-radius:3px;position:relative;top:33px;z-index:1}
    #fullpage .panel-main{margin-top:0;height:100%}
    .panel .ac-reg-body .reg-info{display:inline-block;width:495px;border-right:1px solid #e6e6e6;margin-top:23px;padding-left:65px}
    .ac-reg-body .reg-title{display:flex;justify-content:start;width:380px;margin-bottom: 20px;}
    .ac-reg-body .reg-title .reg-title-text{width:50%;text-align:center;font-size:20px;color:#666;line-height:50px;position:relative;cursor:pointer}
    .ac-reg-body .reg-title .reg-title-text.active{color:#ffab02}
    .ac-reg-body .reg-title .reg-title-text.active:after{content:'';width:80px;height:2px;background:#ffab02;position:absolute;bottom:0;left:50%;transform:translateX(-50%)}
    .login-Wrap{width:430px;overflow:hidden;padding-bottom:50px}
    .login-box-wrap{width:860px;transition: .3s;}
    .login-box-wrap .login-box{width:430px;transition:all 0.5s}
    /*.login-box-wrap .login-box:last-child{transform: translateX(0px);}*/
    .jsRegisterForm{margin-top:20px}
    .panel .ac-reg-body .input-control{width:380px;height:48px;background-color:#f9f9f9;border-radius:3px;border:solid 1px #e6e6e6;position:relative;margin-bottom: 10px;}
    .panel .ac-reg-body .agent_reg .input-control{width:48%;float: left;}
    .panel .ac-reg-body .agent_reg .input-control:nth-child(2n){margin-left: 3%;}
    .panel .ac-reg-body .input-control .input-icon{width:50px;height:50px;display:inline-block}
    .panel .ac-reg-body .input-control .input-icon.name{background:url(<?php echo $tplNmaeSession;?>images/agent/l4.png) center no-repeat}
    .panel .ac-reg-body .input-control .input-icon.pwd{background:url(<?php echo $tplNmaeSession;?>images/agent/l2.png) center no-repeat}
    .panel .ac-reg-body .input-control .input-icon.qrmm{background:url(<?php echo $tplNmaeSession;?>images/agent/l3.png) center no-repeat}
    .panel .ac-reg-body .input-control .input-icon.dh{background:url(<?php echo $tplNmaeSession;?>images/agent/l5.png) center no-repeat}
    .panel .ac-reg-body .input-control .input-icon.wxh{background:url(<?php echo $tplNmaeSession;?>images/agent/l6.png) center no-repeat}
    .panel .ac-reg-body .input-control .input-icon.yh{background:url(<?php echo $tplNmaeSession;?>images/agent/l9.png) center no-repeat}
    .panel .ac-reg-body .input-control .input-icon.kh{background:url(<?php echo $tplNmaeSession;?>images/agent/l7.png) center no-repeat}
    .panel .ac-reg-body .input-control .input-icon.dz{background:url(<?php echo $tplNmaeSession;?>images/agent/l8.png) center no-repeat}

    .panel .ac-reg-body .input-control .register-input{vertical-align:top;border:0;height:48px;width:81%;background-color: transparent;}
    .panel .ac-reg-body .agent_reg .input-control .register-input{width: 72%;}
    .panel .ac-reg-body .btn-div{text-align:left;margin-top:30px}
    .panel .ac-reg-body .btn-div .ac-reg-btn{width:380px;height:54px}
    .reg-ad{display:inline-block;width:332px;overflow:hidden;margin-top:55px;margin-right:50px;min-height:400px}
    .reg-ad .banner{width:340px;height:155px;background:url(<?php echo $tplNmaeSession;?>images/agent/pic.jpg) no-repeat}
    .reg-ad .title{font-size:16px;margin-top:20px}
    .reg-ad .title2{line-height:25px;padding-top:10px}
    .reg-ad .line{width:28px;height:3px;margin-top:15px;background:#ffab02}
    .reg-ad .text{margin-top:15px;font-size:12px;}
    .reg-ad .text .btn {width: 310px;height: 40px;line-height: 40px;font-size: 22px;}
    .panel .codetyle{width:100px;position:absolute;top:0;right:-110px}
    .panel .codetyle .tit{line-height:40px;text-align:center;color:red}
    .panel .codetyle .android,.panel .codetyle .ios{width:100px;height:100px;background-size:cover !important;}
    .panel .codetyle .tit_{text-align:center}
    #fullpage{-webkit-transition:opacity 1s;-o-transition:opacity 1s;transition:opacity 1s;width:100%;min-width:1100px}
    .section:nth-of-type(odd){background-color:#fafafa}
    .section:nth-of-type(even){background-color:#fff}
    #section1,#section2,#section2 .section-content p.p-one,#section3,#section4,#section5{position:relative}
    #section1:before,#section2:before,#section3:before,#section4:before,#section5:before{content:'';position:absolute;width:398px;height:248px;background:url('<?php echo $tplNmaeSession;?>images/agent/jp_left.png') no-repeat;display:block;top:90px;left:0;z-index:0}
    #section1:after,#section2:after,#section3:after,#section4:after,#section5:after{content:'';position:absolute;display:block;width:741px;height:411px;right:0;bottom:0;background:url('<?php echo $tplNmaeSession;?>images/agent/jp_right.png') no-repeat;z-index:0}
    #section1 .vertical-center,#section2 .vertical-center,#section3 .vertical-center,#section4 .vertical-center,#section5 .vertical-center{position:relative;top:41%;-webkit-transform:translateY(-50%);-ms-transform:translateY(-50%);transform:translateY(-50%);z-index:1}
    #section1 .section-header,#section2 .section-header,#section3 .section-header{position:relative;margin:0 auto;-webkit-transform:translateY(50px);-ms-transform:translateY(50px);transform:translateY(50px)}
    #section4 .section-header,#section5 .section-header{position:relative;margin:0 auto}
    #section1 .section-header .title,#section2 .section-header .title,#section3 .section-header .title,#section4 .section-header .title,#section5 .section-header .title{font-size:36px;color:#ffab02;width:100%;height:111px;line-height:111px;text-align:center;opacity:0}
    #section1 .section-header .line,#section2 .section-header .line,#section3 .section-header .line,#section4 .section-header .line,#section5 .section-header .line{width:112px;height:6px;background-color:#ffab02;margin:0 auto;-webkit-transform:scale(0,0);-ms-transform:scale(0,0);transform:scale(0,0)}
    #section1 .section-header .sub-title,#section2 .section-header .sub-title,#section3 .section-header .sub-title,#section4 .section-header .sub-title,#section5 .section-header .sub-title{font-size:24px;color:#666;margin-top:37px;text-align:center;opacity:0}
    #section1 .section-header.active .title,#section2 .section-header.active .title,#section3 .section-header.active .title,#section4 .section-header.active .title,#section5 .section-header.active .title{-webkit-animation:fadeBottomIn .5s .5s forwards;animation:fadeBottomIn .5s .5s forwards}
    #section1 .section-header.active .sub-title,#section2 .section-header.active .sub-title,#section3 .section-header.active .sub-title,#section4 .section-header.active .sub-title,#section5 .section-header.active .sub-title{-webkit-animation:fadeTopIn .5s .5s forwards;animation:fadeTopIn .5s .5s forwards}
    #section1 .section-header.active .line,#section2 .section-header.active .line,#section3 .section-header.active .line,#section4 .section-header.active .line,#section5 .section-header.active .line{-webkit-transform:scale(1,1);-ms-transform:scale(1,1);transform:scale(1,1);-webkit-transition:all .5s;-o-transition:all .5s;transition:all .5s}
    #section1 .section-content,#section2 .section-content,#section3 .section-content{position:relative;margin:0 auto;left:0;right:0}
    #section4 .section-content{margin:0 auto;left:0;right:0}
    #section5 .section-content{position:relative;left:0;right:0}
    #section1 .tutorial{width:1000px;height:73px;margin:0 auto;position:relative;top:130px;line-height:73px}
    #section1 .tutorial .tutorial-arrow,#section1 .tutorial .tutorial-title{opacity:0;-webkit-animation:fadeInUp 1s forwards;animation:fadeInUp 1s forwards}
    #section1 .tutorial .tutorial-title{display:inline-block;text-align:right;line-height:42px;font-size:18px;color:#ffab02;background:url() no-repeat;width:115px;height:42px}
    #section1 .tutorial .tutorial-arrow{display:inline-block;width:29px;height:18px;background:url("<?php echo $tplNmaeSession;?>images/agent/tight.png") no-repeat;margin-left:3px}
    #section1 .tutorial .tutorial-arrow:first-child{-webkit-animation-delay:.3s;animation-delay:.3s}
    #section1 .tutorial .tutorial-arrow:nth-child(1){-webkit-animation-delay:.5s;animation-delay:.5s}
    #section1 .tutorial .tutorial-arrow:last-child{-webkit-animation-delay:.8s;animation-delay:.8s}
    #section1 .tutorial .tutorial-text{color: #666;width:170px;height:73px;display:inline-block;margin-left:3px;vertical-align:top;line-height:73px;padding-left:80px;opacity:0;-webkit-animation:fadeInUp 1s forwards;animation:fadeInUp 1s forwards}
    #section1 .tutorial .tutorial-text.tutorial-one{background:url('<?php echo $tplNmaeSession;?>images/agent/guide_1.png') no-repeat;-webkit-animation-delay:.3s;animation-delay:.3s}
    #section1 .tutorial .tutorial-text.tutorial-two{background:url('<?php echo $tplNmaeSession;?>images/agent/guide_2.png') no-repeat;-webkit-animation-delay:.6s;animation-delay:.6s}
    #section1 .tutorial .tutorial-text.tutorial-three{background:url('<?php echo $tplNmaeSession;?>images/agent/guide_3.png') no-repeat;-webkit-animation-delay:.9s;animation-delay:.9s}
    #section1 .tutorial .tutorial-text .num{font-size:48px;color:#ccc}
    #section1 .tutorial .tutorial-text .text{vertical-align:top;font-size:16px}
    #section2 .section-header{width:1099px;height:180px}
    #section2 .section-header .title{background:url('<?php echo $tplNmaeSession;?>images/agent/brand.png') no-repeat center}
    #section2 .section-content{width:1165px;height:457px;margin-top:60px;-webkit-transform:translateX(50px);-ms-transform:translateX(50px);transform:translateX(50px)}
    #section2 .section-content p.p-one:before{content:'';width:6px;height:6px;background-color:#ffab02;border-radius:50%;display:block;position:absolute;top:7px;left:-15px}
    #section2 .section-content .left{opacity:0}
    #section2 .section-content .right{width:687px;height:359px;background:url("<?php echo $tplNmaeSession;?>images/agent/sport.png") no-repeat;opacity:0}
    #section2 .section-content.active .left{-webkit-animation:fadeInLeft .5s forwards;animation:fadeInLeft .5s forwards}
    #section2 .section-content.active .right{-webkit-animation:fadeInRight .5s forwards;animation:fadeInRight .5s forwards}
    #section3 .section-header{width:1099px;height:182px}
    #section3 .section-header .title{background:url('<?php echo $tplNmaeSession;?>images/agent/enter.png') no-repeat center}
    #section3 .section-content{width:1202px;height:405px;margin-top:70px;-webkit-transform:translateY(-50px);-ms-transform:translateY(-50px);transform:translateY(-50px);opacity:0;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-pack:center;-webkit-justify-content:center;-ms-flex-pack:center;justify-content:center;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;z-index:1}
    #section3 .section-content .game-info{width:240px;height:173px;-webkit-transition:height .5s;-o-transition:height .5s;transition:height .5s;cursor:pointer;overflow:hidden;position:relative}
    #section3 .section-content .game-info.active{height:405px}
    #section3 .section-content .game-info.active .game-logo.one{background:url('<?php echo $tplNmaeSession;?>images/agent/icon11.png') no-repeat}
    #section3 .section-content .game-info.active .game-logo.two{background:url('<?php echo $tplNmaeSession;?>images/agent/icon22.png') no-repeat}
    #section3 .section-content .game-info.active .game-logo.three{background:url('<?php echo $tplNmaeSession;?>images/agent/icon33.png') no-repeat}
    #section3 .section-content .game-info.active .game-logo.four{background:url('<?php echo $tplNmaeSession;?>images/agent/icon44.png') no-repeat}
    #section3 .section-content .game-info.active .game-logo.five{background:url('<?php echo $tplNmaeSession;?>images/agent/icon55.png') no-repeat}
    #section3 .section-content .game-info.active .game-text{-webkit-transition:opacity .5s;-o-transition:opacity .5s;transition:opacity .5s;opacity:1}
    #section3 .section-content .game-info .game-title{width:100%;text-align:center;color:#fff;position:absolute;font-size:20px;top:49%;z-index:2}
    #section3 .section-content .game-info .game-logo{width:240px;height:173px;position:absolute;-webkit-transition:background .5s;-o-transition:background .5s;transition:background .5s}
    #section3 .section-content .game-info .game-logo.one{background:url('<?php echo $tplNmaeSession;?>images/agent/icon1.png') no-repeat}
    #section3 .section-content .game-info .game-logo.two{background:url('<?php echo $tplNmaeSession;?>images/agent/icon2.png') no-repeat}
    #section3 .section-content .game-info .game-logo.three{background:url('<?php echo $tplNmaeSession;?>images/agent/icon3.png') no-repeat}
    #section3 .section-content .game-info .game-logo.four{background:url('<?php echo $tplNmaeSession;?>images/agent/icon4.png') no-repeat}
    #section3 .section-content .game-info .game-logo.five{background:url('<?php echo $tplNmaeSession;?>images/agent/icon5.png') no-repeat}
    #section3 .section-content .game-info .game-text{position:absolute;top:145px;width:240px;height:227px;background:url('<?php echo $tplNmaeSession;?>images/agent/arrow.png') no-repeat;opacity:0}
    #section3 .section-content .game-info .game-text ul{padding-top:89px}
    #section3 .section-content .game-info .game-text ul li{width:40%;font-size:14px;text-align:center;margin:0 auto;line-height:25px}
    #section3 .section-content.active{-webkit-animation:fadeTopIn .5s ease forwards;animation:fadeTopIn .5s ease forwards}
    #section4 .section-header{width:1265px;height:180px;-webkit-transform:translateY(90px);-ms-transform:translateY(90px);transform:translateY(90px)}
    #section4 .section-header .title{background:url('<?php echo $tplNmaeSession;?>images/agent/mobile.png') no-repeat center}
    #section4 .section-content{width:1347px;height:495px;margin-top:150px;-webkit-transform:translateY(50px);-ms-transform:translateY(50px);transform:translateY(50px);opacity:0;position:relative}
    #section4 .section-content .btn-list{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;width:656px;margin:0 auto}
    #section4 .section-content .btn-list .info{width:160px;height:40px;background-color:#fff;border-radius:20px;border:solid 1px #999;line-height:40px;text-align:center;margin-left:24px;color:#666;cursor:pointer;-webkit-transition:background-color .5s;-o-transition:background-color .5s;transition:background-color .5s}
    #section4 .section-content .btn-list .info:hover{background-color:#ffab02;border-color:#ffab02;color:#fff}
    #section4 .section-content .btn-list .info:hover .icon.apple{background:url('<?php echo $tplNmaeSession;?>images/agent/iphone.png') no-repeat}
    #section4 .section-content .btn-list .info:hover .icon.android{background:url('<?php echo $tplNmaeSession;?>images/agent/andrion.png') no-repeat}
    #section4 .section-content .btn-list .info:hover .icon.windows{background:url('<?php echo $tplNmaeSession;?>images/agent/win.png') no-repeat}
    #section4 .section-content .btn-list .info:hover .icon.web-phone{background:url('<?php echo $tplNmaeSession;?>images/agent/mobile_s.png') no-repeat}
    #section4 .section-content .btn-list .icon{display:inline-block;vertical-align:middle;-webkit-transform:translateY(-3px);-ms-transform:translateY(-3px);transform:translateY(-3px);margin-right:5px;-webkit-transition:background .5s;-o-transition:background .5s;transition:background .5s}
    #section4 .section-content .btn-list .icon.apple{width:21px;height:25px;background:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABUAAAAZCAYAAADe1WXtAAABhElEQVRIia3VO2gUURQA0LMDIaCIgmgK24BgI6SwEvw0WghRQcRKLdwpQiwFURAsREUL7a7BRizEpBDED9hoIK0hhRBII2hQNAQT/EBCTIpxYBx2xc2+W70HlzPvd+80ms2mDiPDcZzGlYh42yqhk9iMVxjFEXxv99VO4iEO/hl/wky36F7F6sq4hdVu0aOV8UvcaZfYCboLS7iLQaxgY6vERu32D+E8BtDAtOIcH2A7vmADhjGEPizgOa5GxHQdvYaLbVb5GS+wFQewqUXOL5yMiKcleg73/rH1/4132J0pzuVmAvADDkfESoYT2JIAPRURHyluf38C8E1ETJSTDDsToK+rk0yarf+ooz8ToP119GsCdDDP894qOpkA7cOlKjqeAIXLeZ4PUZRpD2axLRE+kmEZ9xOBMF62vttqz2Kd8R6PS3QONxKgFyJiqdqkr2OqC/BJRIzyd+dfVjSXuXWAUzhbTuq/kxnswTP8xjwe4Rh2KCrnDMYURbOIEeyLiG8lsga3HFzoPeWcJQAAAABJRU5ErkJggg==) no-repeat}
    #section4 .section-content .btn-list .icon.android{width:23px;height:24px;background:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABcAAAAYCAYAAAARfGZ1AAABdklEQVRIie3VPWgUURSG4SfrphREkxBsFGysFQlCigg2ksY0IjZBwV2IYCNYp7RUu0MQ4g8IWqnBKmCiKcTCIsHC1kqJRoSAGvCnmBFuhrnLZhNI4wcXzrnfmXcOc8/M9LVaLRldwDmczRXgARYjYqbObHa48Clu4Dx+4wQGsYrX+IFTmMoBOsHX8QgPO9Q8xs+c2cjs9+EWruEMnlT8NezDccy32+29W4FfLRdcxrGKvx/3cQijiNoOaw50D95hOHPjnEYi4n26UffMf+Fot8SI2qZR3znMdgtPdDsi3lbhR7CEA7ikmN0/PcAnsIxXipG90lAcyDD6cboHaKpRHCxZ4w3F2O2UGtlkp/UfvjvwtSRf3ybvSxJ/a+IZLmIId7YJn0tYd5uKt3G2UvQZA1sEf42ITazcz2IcN3GyzBexkPiHMVnGn3AdL6uQHPxNuf7BFzCd+GMJ/GNE3KuD7Noo9vJl7Bo+jw18x4uKt4IPZfw8B/gLviBOuahj3BoAAAAASUVORK5CYII=) no-repeat}
    #section4 .section-content .btn-list .icon.windows{width:25px;height:24px;background:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAYCAYAAAAPtVbGAAAA0UlEQVRIie3WPW7CQBAF4C/gBhpQFAWIDSeBG+QcziUQTS4R7sAx6FOkSsMd0qSgovCKGORIa/FTgJ802pH27bwdrbTzHvI8dwJ6eMEQKQalNcMzsuSfwx1MQoFxyFdo4wOjsNeJuUmCeVDPSoWfKrif+ME0pvCxyHvdQ3XRurRAI9KIXBYJ3iK5X9jW4O9xtU6WkdxXxbcSy9/jdh6+EblTkQQLh+M3xeO5RarGb1dhILIQY2wUna8VPmCkhpGowi++QxxjVsr7/ixRlTUaIt0BsgoUpFU6clsAAAAASUVORK5CYII=) no-repeat}
    #section4 .section-content .btn-list .icon.web-phone{width:15px;height:25px;background:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA8AAAAZCAYAAADuWXTMAAAA0ElEQVQ4je3UMUtCYRTG8Z/5Ijg4RIsgNLg1309Qc205OLhecGxrCtx0MHFt6Bu0iEPQ2OxHaGrwfgIHG3LwSpe6N1Ac7wOH8x6e8+eFA+dU4jiu4h59nKGCE3z6q1Os8IhpwEMa0EMdV+jmwDeYYYwQEGfMYZrreMuBW5n3XUATCa5zmos0RzOkxRqLPeA128EcrBIu4RI+Nrzb53N8H/PnJW7x/B+cFHiveMGowE8Cnvxcz6w6+MBlATypRlH0ji9coJExa7YnuP0LWmKAyQYxDh7UTUjELAAAAABJRU5ErkJggg==) no-repeat}
    #section4 .section-content .content{width:689px;height:360px;background:url('<?php echo $tplNmaeSession;?>images/agent/qiu.png') no-repeat;position:absolute;top:140px;left:50%;margin-left:-345px}
    #section4 .section-content .content .hand{width:793px;height:381px;background:url('<?php echo $tplNmaeSession;?>images/agent/hand.png') no-repeat;position:absolute;top:78px;left:152px;z-index:3}
    #section4 .section-content .content .phone{width:544px;height:373px;background:url('<?php echo $tplNmaeSession;?>images/agent/hold.png') no-repeat;position:absolute;z-index:1;top:43px;left:171px}
    #section4 .section-content .content .banner{width:409px;height:233px;position:absolute;-webkit-transform:rotate(-17deg);-ms-transform:rotate(-17deg);transform:rotate(-17deg);top:112px;z-index:2;left:240px;background:none}
    #section4 .section-content.active{-webkit-animation:fadeBottomIn .5s ease forwards;animation:fadeBottomIn .5s ease forwards}
    #section5 .section-header{width:1099px;height:180px;-webkit-transform:translateY(140px);-ms-transform:translateY(140px);transform:translateY(140px)}
    #section5 .section-header .title{background:url('<?php echo $tplNmaeSession;?>images/agent/5d699a27334fcd2393d46675ae00525f.png') no-repeat center}
    #section5 .section-content{width:1200px;height:280px;margin:0 auto;padding-top:250px;opacity:0}
    #fp-nav ul li,#section5 .section-content .info-list,.fp-slidesNav ul li{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex}
    #section5 .section-content .info-list .info{width:280px;height:226px;margin-left:20px;cursor:pointer;padding-top:54px}
    #section5 .section-content .info-list .info .logo{width:69px;height:69px;margin:0 auto}
    #section5 .section-content .info-list .info .title{font-size:16px;color:#fff;text-align:center;margin-top:30px}
    #section5 .section-content .info-list .info .sub-title{font-size:12px;color:#fff;text-align:center;margin-top:20px}
    #section5 .section-content .info-list .info.one{background:url('<?php echo $tplNmaeSession;?>images/agent/jp.png') no-repeat}
    #section5 .section-content .info-list .info.one .logo{background:url('<?php echo $tplNmaeSession;?>images/agent/b26fa7f800ebe462bedfa65c7c77167f.png') no-repeat}
    #section5 .section-content .info-list .info.two{background:url('<?php echo $tplNmaeSession;?>images/agent/ee8a4e951b7e77982cbdb5dd4d73529e.png') no-repeat}
    #section5 .section-content .info-list .info.two .logo{background:url('<?php echo $tplNmaeSession;?>images/agent/bbf10f223af6e3429bd432a66d29920e.png') no-repeat}
    #section5 .section-content .info-list .info.three{background:url('<?php echo $tplNmaeSession;?>images/agent/3d6503d1e551b65ee1ac0a663836be9e.png') no-repeat}
    #section5 .section-content .info-list .info.three .logo{background:url('<?php echo $tplNmaeSession;?>images/agent/f0319e6824afe201a51896d61b70a98d.png') no-repeat}
    #section5 .section-content .info-list .info.four{background:url('<?php echo $tplNmaeSession;?>images/agent/7771190763934b0a8fd4cc467488c1f4.png') no-repeat}
    #section5 .section-content .info-list .info.four .logo{background:url('<?php echo $tplNmaeSession;?>images/agent/bbf10f223af6e3429bd432a66d29920e.png') no-repeat}
    #section5 .section-content .info-list .info.active{-webkit-animation:flipInY .8s;animation:flipInY .8s;background:#ffab02}
    #section5 .section-content.active{-webkit-animation:show .5s ease forwards;animation:show .5s ease forwards}
    #fp-nav{position:fixed;z-index:100;margin-top:-32px;top:40%;opacity:1;-webkit-transform:translate3d(0,0,0)}
    #fp-nav.right{right:0}
    #fp-nav.left{left:17px}
    #fp-nav .mouse{width:28px;height:39px;display:block;background-size:contain}
    #fp-nav .fa{margin-left:6px;color:#333;font-size:24px}
    #fp-nav ul li .fp-tooltip{position:absolute;top:-2px;color:#fff;font-size:14px;font-family:arial,helvetica,sans-serif;white-space:nowrap;max-width:220px;overflow:hidden;display:block;opacity:0;width:0}
    #fp-nav ul li .fp-tooltip.right{right:20px}
    #fp-nav ul li:hover .fp-tooltip,#fp-nav.fp-show-active a.active+.fp-tooltip{-webkit-transition:opacity .2s ease-in;-o-transition:opacity .2s ease-in;transition:opacity .2s ease-in;width:auto;opacity:1}
    #fp-nav ul li,.fp-slidesNav ul li{display:block;width:140px;height:auto;position:relative;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;margin:20px 0 0}
    #fp-nav ul li a,.fp-slidesNav ul li a{display:block;position:relative;z-index:1;width:100%;height:100%;cursor:pointer;text-decoration:none}
    #fp-nav ul li a.active,.fp-slidesNav ul li a.active{width:140px;height:100px}
    #fp-nav ul li a span,.fp-slidesNav ul li a span{border-radius:50%;position:relative;z-index:1;height:10px;width:10px;border:0;background-color:#000;background-color:rgba(0,0,0,.1);margin:0 0 0 8px;display:block}
    #fp-nav ul li a.active span,.fp-slidesNav ul li a.active span{position:relative;width:140px;height:100px;margin:0;left:0;top:0;background-color:#ffab02;border-radius:0;border-top-left-radius:50%;border-bottom-left-radius:50%;-webkit-animation:fadeInRight .8s;animation:fadeInRight .8s}
    #fp-nav ul li a.active span:before,.fp-slidesNav ul li a.active span:before{content:'';width:16px;height:28px;background:url(<?php echo $tplNmaeSession;?>images/agent/qieh.png) no-repeat;display:block;position:relative;top:29px;left:74px}
    #fp-nav ul li a.active span:after,.fp-slidesNav ul li a.active span:after{content:'';width:15px;height:10px;background:url(<?php echo $tplNmaeSession;?>images/agent/xl.png) no-repeat;display:block;position:relative;top:40px;left:75px}

    @-webkit-keyframes jumpDown{
        0%{opacity:0;-webkit-transform:translate3d(0,-10%,0);transform:translate3d(0,-10%,0)}
        40%,to{opacity:1;-webkit-transform:translate3d(0,0,0);transform:translate3d(0,0,0)}
        70%{-webkit-transform:translate3d(0,-15px,0);transform:translate3d(0,-15px,0)}
    }
    @keyframes jumpDown{
        0%{opacity:0;-webkit-transform:translate3d(0,-10%,0);transform:translate3d(0,-10%,0)}
        40%,to{opacity:1;-webkit-transform:translate3d(0,0,0);transform:translate3d(0,0,0)}
        70%{-webkit-transform:translate3d(0,-15px,0);transform:translate3d(0,-15px,0)}
    }
    @-webkit-keyframes show{
        0%{opacity:0}
        to{opacity:1}
    }
    @keyframes show{
        0%{opacity:0}
        to{opacity:1}
    }
    @-webkit-keyframes fadeRightIn{
        0%{-webkit-transform:translateX(50px);transform:translateX(50px);opacity:0}
        to{-webkit-transform:translateX(0);transform:translateX(0);opacity:1}
    }
    @keyframes fadeRightIn{
        0%{-webkit-transform:translateX(50px);transform:translateX(50px);opacity:0}
        to{-webkit-transform:translateX(0);transform:translateX(0);opacity:1}
    }
    @-webkit-keyframes fadeTopIn{
        0%{-webkit-transform:translateY(-50px);transform:translateY(-50px);opacity:0}
        to{-webkit-transform:translateY(0);transform:translateY(0);opacity:1}
    }
    @keyframes fadeTopIn{
        0%{-webkit-transform:translateY(-50px);transform:translateY(-50px);opacity:0}
        to{-webkit-transform:translateY(0);transform:translateY(0);opacity:1}
    }
    @-webkit-keyframes fadeBottomIn{
        0%{-webkit-transform:translateY(50px);transform:translateY(50px);opacity:0}
        to{-webkit-transform:translateY(0);transform:translateY(0);opacity:1}
    }
    @keyframes fadeBottomIn{
        0%{-webkit-transform:translateY(50px);transform:translateY(50px);opacity:0}
        to{-webkit-transform:translateY(0);transform:translateY(0);opacity:1}
    }
    @-webkit-keyframes infinite-move{
        0%,to{-webkit-transform:translateY(0);transform:translateY(0)}
        50%{-webkit-transform:translateY(20%);transform:translateY(20%)}
    }
    @keyframes infinite-move{
        0%,to{-webkit-transform:translateY(0);transform:translateY(0)}
        50%{-webkit-transform:translateY(20%);transform:translateY(20%)}
    }
    @-webkit-keyframes fadeInRight{
        0%{-webkit-transform:translate3d(100%,0,0);transform:translate3d(100%,0,0)}
        to{-webkit-transform:none;transform:none}
    }
    @-webkit-keyframes fadeInUp{
        0%{opacity:0;-webkit-transform:translate3d(0,100%,0);transform:translate3d(0,100%,0)}
        to{opacity:1;-webkit-transform:none;transform:none}
    }
    @keyframes fadeInUp{
        0%{opacity:0;-webkit-transform:translate3d(0,100%,0);transform:translate3d(0,100%,0)}
        to{opacity:1;-webkit-transform:none;transform:none}
    }
    @-webkit-keyframes fadeInLeft{
        0%{opacity:0;-webkit-transform:translate3d(-100%,0,0);transform:translate3d(-100%,0,0)}
        to{opacity:1;-webkit-transform:none;transform:none}
    }
    @keyframes fadeInLeft{
        0%{opacity:0;-webkit-transform:translate3d(-100%,0,0);transform:translate3d(-100%,0,0)}
        to{opacity:1;-webkit-transform:none;transform:none}
    }
    @keyframes fadeInRight{
        0%{opacity:0;-webkit-transform:translate3d(100%,0,0);transform:translate3d(100%,0,0)}
        to{opacity:1;-webkit-transform:none;transform:none}
    }
    @-webkit-keyframes rotateRed{
        0%,to{-webkit-transform:rotate(-15deg);transform:rotate(-15deg)}
        50%{-webkit-transform:rotate(15deg);transform:rotate(15deg)}
    }
    @keyframes rotateRed{
        0%,to{-webkit-transform:rotate(-15deg);transform:rotate(-15deg)}
        50%{-webkit-transform:rotate(15deg);transform:rotate(15deg)}
    }
</style>

<div id="fullpage">
    <div id="section1" class="section ">
        <div class="panel basic-white">
            <div class="panel-main clearfix">
                <div class="clearfix ac-reg-body">
                    <div class="reg-info">
                        <div class="reg-title clearfix">
                            <div class="reg-title-text active" data-to="login">代理登录</div>
                            <div class="reg-title-text" data-to="reg">代理注册</div>
                        </div>
                        <div class="login-Wrap">
                            <div class="login-box-wrap clearfix">
                                <div class="agent_login login-box fl">
                                    <form method="post" autocomplete="off" class="jsRegisterForm js-re-registerForm form-horizontal" action="<?php echo $_SESSION['AGENT_LOGIN_URL'].'/app/agents/chk_login.php' ;?>" target="_blank" onsubmit="return checkAgent()" >
                                        <input type="hidden" name="actionType" value="login_ad">
                                        <input type="hidden" name="langx" value="zh-cn">
                                        <input type="hidden" name="level" value="D">
                                        <div class="input-control">
                                            <div class="input-icon name"></div>
                                            <input type="text" name="UserName" id="login_username" placeholder="请输入您的代理账号" autocomplete="off" class="register-input" minlength="4" maxlength="15">
                                        </div>

                                        <div class="input-control">
                                            <div class="input-icon pwd"></div>
                                            <input type="password" name="PassWord" id="login_password" autocomplete="off" placeholder="请输入您的代理密码" class="register-input" minlength="6" maxlength="15">
                                        </div>

                                        <p class="red">代理商专属链接地址：<?php echo $_SESSION['HTTPS_HEAD_SESSION'].'://'.$host?>?intr=代理ID </p>
                                        <div class="btn-div">
                                            <button data-loading-text="保存中"
                                                    class="btn btn-cool ac-reg-btn">立即登录
                                            </button>
                                        </div>

                                    </form>
                                </div>
                                <div class="agent_reg login-box fl">
                                    <form action="javascript:void(0);" id=""  autocomplete="off"
                                          class="js-re-registerForm form-horizontal">

                                        <div class="input-control">
                                            <div class="input-icon name"></div>
                                            <input type="text" name="userName" id="username" placeholder="请输入您的代理账号" autocomplete="off" class="register-input" minlength="5" maxlength="15">
                                        </div>

                                        <div class="input-control">
                                            <div class="input-icon pwd"></div>
                                            <input type="password" name="password" id="password" autocomplete="off" placeholder="请输入您的代理密码" class="register-input" minlength="6" maxlength="15">
                                        </div>

                                        <div class="input-control">
                                            <div class="input-icon qrmm"></div>
                                            <input type="password" name="password2" id="password2" placeholder="请确认您的代理密码" autocomplete="off" class="register-input" minlength="6" maxlength="15">
                                        </div>

                                        <div class="input-control">
                                            <div class="input-icon name"></div>
                                            <input type="text" name="alias" id="alias" placeholder="请填写您的真实姓名" autocomplete="off" class="register-input">
                                        </div>

                                        <div class="input-control">
                                            <div class="input-icon dh"></div>
                                            <input type="text" name="phone" id="phone" placeholder="请填写您的联系电话" autocomplete="off" class="register-input" minlength="11" maxlength="11">
                                        </div>

                                        <div class="input-control">
                                            <div class="input-icon wxh"></div>
                                            <input type="text" name="wechat" id="wechat" placeholder="请填写您的微信号" autocomplete="off" class="register-input">
                                        </div>

                                        <div class="input-control">
                                            <div class="input-icon yh"></div>
                                            <select name="bank_name" id="bank_name" style="width: 72%;">
                                                <option value="工商银行">工商银行</option>
                                                <option value="交通银行">交通银行</option>
                                                <option value="农业银行">农业银行</option>
                                                <option value="建设银行">建设银行</option>
                                                <option value="招商银行">招商银行</option>
                                                <option value="民生银行总行">民生银行总行</option>
                                                <option value="中信银行">中信银行</option>
                                                <option value="光大银行">光大银行</option>
                                                <option value="华夏银行">华夏银行</option>
                                                <option value="广东发展银行">广东发展银行</option>
                                                <option value="深圳平安银行">深圳平安银行</option>
                                                <option value="中国邮政">中国邮政</option>
                                                <option value="中国银行">中国银行</option>
                                                <option value="农村信用合作社">农村信用合作社</option>
                                                <option value="兴业银行">兴业银行</option>
                                            </select>

                                        </div>

                                        <div class="input-control">
                                            <div class="input-icon kh"></div>
                                            <input type="text" name="bank_account" id="bank_account" placeholder="请填写您的银行卡号" autocomplete="off" class="register-input">
                                        </div>

                                        <div class="input-control">
                                            <div class="input-icon dz"></div>
                                            <input type="text" name="bank_address" id="bank_address" placeholder="请填写您的开户地址" autocomplete="off" class="register-input">
                                        </div>

                                        <div class="m-bottom-sm clearfix promise-div">
                                            <div class="left">
                                                <span  class="custom-checkbox">
                                                    <input checked type="checkbox" id="3" value=""> <label class="checkbox-label"></label>
                                                </span>
                                            </div>
                                            <span class="promise-hint">　*我已届满合法博彩年龄,且同意<a class="js-promise-open promise-link">各项开户条约,开户协议。</a></span>
                                        </div>

                                        <p class="red">代理商专属链接地址：<?php echo $_SESSION['HTTPS_HEAD_SESSION'].'://'.$host?>?intr=代理ID</p>
                                        <div class="btn-div">
                                            <button class="agents_submit btn btn-cool ac-reg-btn">立即注册</button>
                                        </div>

                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="reg-ad right">
                        <div class="banner"></div>
                        <div class="content">
                            <div class="title">代理加盟</div>
                            <div class="title2">
                                我们将以最优秀的专业团队协助您的每一步发展，我们
                                只用事实说话，携手共创巅峰
                            </div>
                            <div class="line"></div>
                            <div class="text">
                                <p>1、提供两种佣金收入方式同时盈利</p>
                                <p>2、更及时快速的佣金支付</p>
                                <p>3、最杰出的网站创意</p>
                                <a href="javascript:;" class="btn to_aboutus" data-index="8"> 代理合作 </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="codetyle">
                <div class="tit">&gt;扫码下载&lt;</div>
                <div class="android download_android_app"></div>
                <div class="tit_">安卓手机版本</div>
                <div class="ios download_ios_app"></div>
                <div class="tit_">苹果手机版本</div>
            </div>
        </div>

        <div class="tutorial">
            <div class="tutorial-title">新手引导</div>
            <div class="tutorial-arrow"></div>
            <div class="tutorial-text tutorial-one"><span class="text">注册账号</span></div>
            <div class="tutorial-arrow"></div>
            <div class="tutorial-text tutorial-two"><span class="text">登录平台享新手优惠</span></div>
            <div class="tutorial-arrow"></div>
            <div class=" tutorial-text tutorial-three"><span class="text">下载手机端畅玩无限</span></div>
        </div>
    </div>
    <div id="section2" class="section">
        <div class="vertical-center">
            <div class="section-header ">
                <div class="title"><?php echo $companyName;?>，品牌至上</div>
                <div class="line"></div>
                <div class="sub-title">新力量，最具潜力的娱乐平台</div>
            </div>
            <div class="section-content clearfix ">
                <div class="left">
                    <div class="m-top-lg"><p class="p-one">凭借尖端前沿的技术实力与行业信誉度，始终坚持将<span class="font-md text-cool">用户体验放在第一</span>，
                        </p>
                        <p>历经五年成长，皇冠娱乐一路高歌猛进，</p>
                        <p>现已成为东南亚地区最具竞争力和行业潜力的娱乐品牌。</p></div>
                    <div class="m-top-lg"><p class="p-one"><?php echo $companyName;?>已经成为东南亚地区最佳彩票运营专家级平台，</p>
                        <p>并将于2017年正式开放 <span class="font-md text-cool">“真人、捕鱼、体育、电子游艺”</span>等业务体系，</p>
                        <p>通过良好的口碑和关系网，</p>
                        <p>皇冠娱乐正致力打造成为亚洲最佳的在线娱乐平台。</p></div>
                </div>
                <div class="right"></div>
            </div>
        </div>
    </div>
    <div id="section3" class="section ">
        <div class="vertical-center">
            <div class="section-header ">
                <div class="title">站内精彩导航平台</div>
                <div class="line"></div>
                <div class="sub-title">玩法更新最快，平台衔接最稳</div>
            </div>
            <div id="new" class="section-content ">
                <div class="to_lotterys game-info active">
                    <div class="game-logo one"></div>
                    <div class="game-title">彩票平台</div>
                    <div class="game-text">
                        <ul>
                            <li>香港六合彩</li>
                            <li>极速赛车 </li>
                            <li>北京赛车 </li>
                            <li>幸运飞艇</li>
                            <li>江苏快三 </li>
                        </ul>
                    </div>
                </div>
                <div class="to_lives game-info">
                    <div class="game-logo two"></div>
                    <div class="game-title">真人视讯</div>
                    <div class="game-text">
                        <ul>
                            <li>AG视讯</li>
                            <li>OG视讯</li>
                            <li>BBIN视讯</li>
                        </ul>
                    </div>
                </div>
                <div class="to_games game-info">
                    <div class="game-logo three"></div>
                    <div class="game-title">电子游戏</div>
                    <div class="game-text">
                        <ul>
                            <li>AG电子</li>
                            <li>MG电子</li>
                            <li>CQ9电子</li>
                            <li>MW电子</li>
                            <li>FG电子</li>
                        </ul>
                    </div>
                </div>
                <div class="to_sports game-info">
                    <div class="game-logo four"></div>
                    <div class="game-title">体育平台</div>
                    <div class="game-text">
                        <ul>
                            <li>皇冠体育</li>
                            <li>新皇冠体育</li>
                        </ul>
                    </div>
                </div>
                <div class="to_chess game-info">
                    <div class="game-logo five"></div>
                    <div class="game-title">棋牌游戏</div>
                    <div class="game-text">
                        <ul>
                            <li>VG棋牌</li>
                            <li>开元棋牌</li>
                            <li>乐游棋牌</li>
                            <li>快乐棋牌</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="section4" class="section">
        <div class="vertical-center">
            <div class="section-header ">
                <div class="title">在手机上也可以娱乐</div>
                <div class="line"></div>
                <div class="sub-title">买大买小，投多投少，无限想象</div>
            </div>
            <div class="section-content ">
                <div class="btn-list">
                    <div class="info"><span class="icon windows"></span> <span>PC客户端</span></div>
                    <div class="info"><span class="icon web-phone"></span> <span>手机网页版</span></div>
                    <div class="info"><span class="icon apple"></span> <span>苹果手机</span></div>
                    <div class="info"><span class="icon android"></span> <span>安卓手机</span></div>
                </div>
                <div class="content">
                    <div class="phone"></div>
                    <div class="hand"></div>
                    <div class="banner">
                        <div class="swiper-container">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide"><img src="<?php echo $tplNmaeSession;?>images/agent/s1.png" alt=""></div>
                                <div class="swiper-slide"><img src="<?php echo $tplNmaeSession;?>images/agent/s2.png" alt=""></div>
                                <div class="swiper-slide"><img src="<?php echo $tplNmaeSession;?>images/agent/s3.png" alt=""></div>
                                <div class="swiper-slide"><img src="<?php echo $tplNmaeSession;?>images/agent/s4.png" alt=""></div>
                                <div class="swiper-slide"><img src="<?php echo $tplNmaeSession;?>images/agent/s5.png" alt=""></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<script type="text/javascript">
    // 代理登录验证
    function checkAgent(){
        var username = $('#login_username').val();
        var pwd = $('#login_password').val();
        if(!username || !isNum(username)){
            layer.msg('请输入正确的用户名!',{time:alertTime});
            return false;
        }
        if(!pwd){
            layer.msg('请输入登录密码!',{time:alertTime});
            return false;
        }
    }

    $(function () {
        var index = '<?php echo $key;?>';

        // 标签切换
        // $('.about-nav li a').on('click',function () {
        //     var ii = $(this).parents('li').index();
        //     var tx = $(this).text();
        //     // console.log(ii);
        //     $('.bzzx_title').text(tx);
        //     $(this).addClass('active').parents('li').siblings().find('a').removeClass('active');
        //     $('.textWrap .textBox:eq('+ii+')').removeClass('hide').siblings().addClass('hide');
        //
        // });
        //
        // $('.addBtn').on('click',function () { // 立即加入
        //     $('.about-nav li a').eq(3).click();
        // });

        var clipboard = new ClipboardJS('.copyButton');
        //优雅降级:safari 版本号>=10,提示复制成功;否则提示需在文字选中后，手动选择“拷贝”进行复制
        clipboard.on('success', function(e) {
            layer.msg('复制成功!',{time:alertTime})
            e.clearSelection();
        });
        clipboard.on('error', function(e) {
            layer.msg('请选择“拷贝”进行复制!',{time:alertTime})
        });

        function agentsReg() { // 代理注册
            var actionurl = '/app/member/api/reg_agent.php' ;
            var agregflage = false ;
            $('.agents_submit').on('click',function () {
                if(agregflage){
                    return false ;
                }
                var username = $("#username").val();
                var passwd = $("#password").val();
                var passwd2 =$("#password2").val();
                var phone =$("#phone").val();
                var alias =$("#alias").val();
                var wechat =$("#wechat").val();
                var bank_name =$("#bank_name").val();
                var bank_address =$("#bank_address").val();
                var bank_account =$("#bank_account").val();
                var title = '' ;

                if (username == "" ) {
                    title = '账号不能为空!';
                    layer.msg(title,{time:alertTime});
                    return false;
                }
                if (!isNum(username)){
                    title = '请输入正确的账号！格式：以英文+数字,长度5-15!';
                    layer.msg(title,{time:alertTime});
                    return false;
                }
                if (username.length < 5 || username.length > 15) {
                    title = '账号需在5-15位之间!';
                    layer.msg(title,{time:alertTime});
                    return false;
                }
                if ( passwd == "" ) {
                    title = '密码不能为空！';
                    layer.msg(title,{time:alertTime});
                    return false;
                }
                if (passwd.length < 6 || passwd.length > 15) {
                    title = '密码需在6-15位之间！';
                    layer.msg(title,{time:alertTime});
                    return false;
                }
                if ( passwd2 != passwd ) {
                    title = '密码与确认密码不一致！';
                    layer.msg(title,{time:alertTime});
                    return false;
                }
                if(!alias){
                    title = '请输入真实姓名！';
                    layer.msg(title,{time:alertTime});
                    return false;
                }
                if(phone=='' || !isMobel(phone)){
                    title = '请输入正确的手机号码！';
                    layer.msg(title,{time:alertTime});
                    return false;
                }
                if(wechat=='' || !isWechat(wechat)){
                    title = '请输入正确的微信号码！';
                    layer.msg(title,{time:alertTime});
                    return false;
                }
                if(!bank_address){
                    title = '请输入银行地址！';
                    layer.msg(title,{time:alertTime});
                    return false;
                }
                if(bank_account=='' || !isBankAccount(bank_account)){
                    title = '请输入正确银行账号！';
                    layer.msg(title,{time:alertTime});
                    return false;
                }

                agregflage = true ;
                $.ajax({
                    type : 'POST',
                    dataType : 'json',
                    url : actionurl ,
                    data : {
                        keys:'add',
                        username:username,
                        password:passwd,
                        password2:passwd2,
                        phone:phone,
                        alias:alias,
                        wechat:wechat,
                        bank_name:bank_name,
                        bank_address:bank_address,
                        bank_account:bank_account,
                    },
                    success:function(res) {
                        if(res){
                            agregflage = false ;
                            layer.msg(res.describe,{time:alertTime});
                            if(res.status ==200){
                                window.location.href = res.data.agentUrl ;
                            }
                        }

                    },
                    error:function(){
                        agregflage = false ;
                        layer.msg('稍后请重试',{time:alertTime});
                    }
                });


            })
        }

        agentsReg();
        indexCommonObj.bannerSwiper();
        changeAgentTab();

        // 登录注册切换
        function changeAgentTab(){
            $('.reg-title .reg-title-text').on('click',function () {
                var type = $(this).attr('data-to');
                var ot_type = $(this).siblings().attr('data-to');
                // console.log(type);
                // console.log(ot_type);
                $(this).addClass('active').siblings().removeClass('active');
                if(type=='reg'){
                    $('.login-box-wrap').css({'transform':'translateX(-430px)'});
                }else{
                    $('.login-box-wrap').css({'transform':'translateX(0)'});
                }
            })
        }

        // game hover
        $('#section3 .game-info').hover(function () {
            $(this).addClass('active').siblings().removeClass('active');
            clearInterval(timer);
        })

        var game_i = 0;
        function timerActive() {
            var html = $('#new .game-info')[game_i];
            $(html).addClass('active').siblings().removeClass('active')
            game_i = game_i > 3 ? 0 : game_i += 1
        }
        var timer= setInterval(function () {
            timerActive()
        }, 2500)


    })
</script>