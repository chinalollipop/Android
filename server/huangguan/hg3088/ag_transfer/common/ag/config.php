<?php
if ( function_exists("date_default_timezone_set")) date_default_timezone_set ("Etc/GMT+4");

//主库
$database['gameDefault']['host'] = '192.168.1.80';
$database['gameDefault']['port'] = '3306';
$database['gameDefault']['user'] = 'root';
$database['gameDefault']['password'] = '123qwe';
$database['gameDefault']['dbname'] = 'agcenter';
$database['gameDefault']['prefix'] = 'hgty78_';
$database['gameDefault']['dbdriver'] = 'mysqli';

//从库1 - 主要从库，表的存储结构为myisam
$database['gameSlave'][1]['host'] = '192.168.1.80';
$database['gameSlave'][1]['port'] = '3306';
$database['gameSlave'][1]['user'] = 'root';
$database['gameSlave'][1]['password'] = '123qwe';
$database['gameSlave'][1]['dbname'] = 'agcenter';
$database['gameSlave'][1]['prefix'] = 'hgty78_';
$database['gameSlave'][1]['dbdriver'] = 'mysqli';

//从库2 - 主要从库，表的存储结构为myisam
$database['gameSlave'][2]['host'] = '192.168.1.80';
$database['gameSlave'][2]['port'] = '3306';
$database['gameSlave'][2]['user'] = 'root';
$database['gameSlave'][2]['password'] = '123qwe';
$database['gameSlave'][2]['dbname'] = 'agcenter';
$database['gameSlave'][2]['prefix'] = 'hgty78_';
$database['gameSlave'][2]['dbdriver'] = 'mysqli';

//定义数据库表的前缀
if(!defined("DBPREFIX")) {
    define("DBPREFIX", strtolower($database['gameDefault']['prefix']));
}

$agsxInit['domain_url'] = "";  // 返回的网站域名
$agsxInit['m_domain_url'] = "";  // 手机版返回的网站域名
$agsxInit['api_url'] = "http://gi.6668ag.com:81/doBusiness.do";
$agsxInit['game_api_url'] = "http://gci.6668ag.com:81/forwardGame.do";
$agsxInit['cagent'] = 'BT5_AGIN'; // 代理
$agsxInit['oddtype'] = 'A'; // 盘口, 设定新玩家可下注的范围
$agsxInit['cur'] = 'CNY'; // 默认人民币
$agsxInit['lang'] = 'zh-cn'; // 默认简体中文
$agsxInit['md5_key'] = 'HkJje0RrtcqC';
$agsxInit['des_key'] = '1N3X2Ms8';
$agsxInit['ftp_url'] = 'xml.agingames.com';
$agsxInit['ftp_user'] = 'BT5.jiao';
$agsxInit['ftp_pwd'] ='$7gBJH5QVp';
$agsxInit['data_api_md5_key'] = '2AA370448128F2F41DACDED9DD624C5A'; // 明码
$agsxInit['data_api_url'] = 'http://boh6t5.gdcapi.com:3333'; // 真人、電子遊戲(AGIN_Data_API)
$agsxInit['data_api_buyu_url'] = 'http://hboh6t5.gdcapi.com:7733'; // 捕魚遊戲(hunter gdc接口)
$agsxInit['language'] = 'lang_cns';
$agsxInit['data_api_cagent'] = 'BT5'; // AG前缀
$agsxInit['tester']='BT5_lllll02'; // 测试账号