<?php

// 抽取0086新春红包，配置 TRUE 开  FALSE 关
if(!defined("NEWYEAR_RED_ENVELOPE_SWITCH")) {
    define("NEWYEAR_RED_ENVELOPE_SWITCH", TRUE);
}

// 抽取6668新春红包，配置 TRUE 开  FALSE 关
if(!defined("NEWYEAR_6668_RED_ENVELOPE_SWITCH")) {
    define("NEWYEAR_6668_RED_ENVELOPE_SWITCH", TRUE);
}

// 查看后台新春节日签到账号  admin
if(!defined("NEWYEAR_ACCOUNT")) {
    define("NEWYEAR_ACCOUNT", "admin");
}

// 新春活动享受优惠日期 大年三十
if(!defined("NEWYEAR_RECEIVE_GIFT_DATA")) {
    define("NEWYEAR_RECEIVE_GIFT_DATA", "2019-02-04");
    //define("NEWYEAR_RECEIVE_GIFT_DATA", "2019-01-20");
}

// 该注册时间前享受优惠一次
if(!defined("REGISTER_GIFT_TIME")) {
    define("REGISTER_GIFT_TIME", "2019-01-31 23:59:59");
}

// HISTORY_DEPOSIT 历史存款超过100元享受优惠一次
if(!defined("HISTORY_DEPOSIT")) {
    define("HISTORY_DEPOSIT", 100);
}

// 抽取新春红包，配置当日存款金额、以及可领取次数
$grab_newyear_red_envelope_times_level[0] = array('deposit_amount'=>1000,    'grab_red_envelope_times'=>1,  'valid_amount'=>3000);
$grab_newyear_red_envelope_times_level[1] = array('deposit_amount'=>5000,    'grab_red_envelope_times'=>3,  'valid_amount'=>15000);
$grab_newyear_red_envelope_times_level[2] = array('deposit_amount'=>10000,   'grab_red_envelope_times'=>5,  'valid_amount'=>30000);
$grab_newyear_red_envelope_times_level[3] = array('deposit_amount'=>50000,   'grab_red_envelope_times'=>8,  'valid_amount'=>150000);
$grab_newyear_red_envelope_times_level[4] = array('deposit_amount'=>100000,  'grab_red_envelope_times'=>12, 'valid_amount'=>300000);
$grab_newyear_red_envelope_times_level[5] = array('deposit_amount'=>500000,  'grab_red_envelope_times'=>18, 'valid_amount'=>1500000);
$grab_newyear_red_envelope_times_level[6] = array('deposit_amount'=>1000000, 'grab_red_envelope_times'=>28, 'valid_amount'=>3000000);
$grab_newyear_red_envelope_times_level[7] = array('deposit_amount'=>5000000, 'grab_red_envelope_times'=>58, 'valid_amount'=>15000000);

