<?php

// 老会员下载APP免费领取彩金日期配置，此日期前注册的为老会员
if(!defined("DOWNLOAD_APP_GIFT_DATE")) {
    define("DOWNLOAD_APP_GIFT_DATE", "2018-11-01");
}

// 老会员下载APP免费领取彩金金额
if(!defined("DOWNLOAD_APP_GIFT_GOLD")) {
    define("DOWNLOAD_APP_GIFT_GOLD", 108);
}

// 老会员下载APP免费领取彩金，累计存款达到1000元
if(!defined("DOWNLOAD_APP_GIFT_DEPOSIT")) {
    define("DOWNLOAD_APP_GIFT_DEPOSIT", 1000);
}

// 抽取幸运红包，配置 TRUE 开  FALSE 关
if(!defined("LUCKY_RED_ENVELOPE_SWITCH")) {
    define("LUCKY_RED_ENVELOPE_SWITCH", FALSE);
}

// 抽取幸运红包关闭，配置提示语
if(!defined("LUCKY_RED_ENVELOPE_CLOSE_MESSAGE")) {
    define("LUCKY_RED_ENVELOPE_CLOSE_MESSAGE", "幸运红包活动已暂停，谢谢使用！");  //幸运红包活动即将上线，敬请期待！
}

// 抽取幸运红包，配置有效金额、以及可领取次数
$grab_red_envelope_times_level[0] = array('valid_amount'=>1000, 'grab_red_envelope_times'=>1);
$grab_red_envelope_times_level[1] = array('valid_amount'=>3000, 'grab_red_envelope_times'=>2);
$grab_red_envelope_times_level[2] = array('valid_amount'=>5000, 'grab_red_envelope_times'=>3);
$grab_red_envelope_times_level[3] = array('valid_amount'=>10000, 'grab_red_envelope_times'=>5);
$grab_red_envelope_times_level[4] = array('valid_amount'=>50000, 'grab_red_envelope_times'=>7);
$grab_red_envelope_times_level[5] = array('valid_amount'=>100000, 'grab_red_envelope_times'=>9);
$grab_red_envelope_times_level[6] = array('valid_amount'=>500000, 'grab_red_envelope_times'=>18);
$grab_red_envelope_times_level[7] = array('valid_amount'=>1000000, 'grab_red_envelope_times'=>28);
$grab_red_envelope_times_level[8] = array('valid_amount'=>5000000, 'grab_red_envelope_times'=>38);
$grab_red_envelope_times_level[9] = array('valid_amount'=>10000000, 'grab_red_envelope_times'=>68);