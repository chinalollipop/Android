<?

$EC_private_key = '-----BEGIN RSA PRIVATE KEY-----
MIICXAIBAAKBgQC11ZcKv6y5uDUgu2pRZpAbOCClDaXd3qvcFanORegYIAC98I81
HZKqgE2QhIhJ6EfkT73yOVs5q+E+pOrMx06YOpYSp2T+EKOyboZyySu8MGR9+kIk
5YF5qDsvDk2x6IPxS5GIcPFXkmfUr/yjPu8xyIUA1H4mxUhtttUqX5sI2wIDAQAB
AoGACPkLRQqEWX3PkVfuPSoUfmNcqQhtYO92B5xCDt3AQECECNtwFazp6eP+79y6
ZPtDYO7WbreJ2uSybsbwlz5WkHL3xVzOe3RW6oj7zFpxl6WaLtGk3Je41k/wKPxZ
DzrjzhrZF7QvmPWeYkLwPwKzQU5Yomi9GE3PrY4Dbz/lixECQQD7qqB4OPRBUuJY
VZgS0NzowP2Xiq2Onw4NSnqpRNciXMNFnXsEPayxe+Fzycy2frVh2ayjjrp1lQ/q
NdhM/MZtAkEAuPciqbjrqhTmQlXLfqGiExcy0tS8emSODqIbGk4SqgyA7hxhO1yp
CnP0ZaM/bDzfsqqixDXKwV/TIyTXRWAfZwJBALqS0oiOYLaVBezK+ATrBvsfKGHS
k7yqOjasQqd+u6dj8fiIOYz5VC/4UToQH04kPcAfKhfPVniZW8UcyhC5TTkCQCrZ
3mbxl29U3i96DuXsbk777eNYM74rM5oCRkMm2T4aHXiMjJ4jDmqEiJdPZa8evzh2
PbU1PR7lo1WeAF1rMnsCQG2D2XFCwlIPBgCDkCl2gxG95w0qTtZ0vtjBNxGj0Rjq
NGKYawtxdzF6cB7dlhPYVLPJpv6wUvtzDmp67OTkGFQ=
-----END RSA PRIVATE KEY-----';

$EC_public_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC11ZcKv6y5uDUgu2pRZpAbOCCl
DaXd3qvcFanORegYIAC98I81HZKqgE2QhIhJ6EfkT73yOVs5q+E+pOrMx06YOpYS
p2T+EKOyboZyySu8MGR9+kIk5YF5qDsvDk2x6IPxS5GIcPFXkmfUr/yjPu8xyIUA
1H4mxUhtttUqX5sI2wIDAQAB
-----END PUBLIC KEY-----';

$MW_public_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCfbjwOVZgxd2jqwNirZpqXMISeHdShxFGvx9lC
jYX2PG2JliaExcloVec+ZfFubXb9MBsiuMCp112xVgEQBa2vPTu6XuqgKnGVg5EmkX3StE6chfLk
eJjxXDyXewrpUI1oXiFpjfEbTe6VCUQ6p7dZanDGVyW0/XHwpauUXa9IDwIDAQAB
-----END PUBLIC KEY-----';

$pi_key =  openssl_pkey_get_private($EC_private_key);
//这个函数可用来判断私钥是否是可用的，可用返回资源id Resource id
$pu_key =  openssl_pkey_get_public($EC_public_key);
//这个函数可用来判断公钥是否是可用的

$AES_key = "abc1234567812345";
$siteId = '10017900';
$siteName = 'ATF';
$toURL = "http://www.168at168.com/as-lobby/api/domain?"; // 通过这个接口获取最新可用的DomainUrl
$merchantId = 'dev'; // 代理商ID 小于50个字符
$mw_prefix = $merchantId.'_'; // 平台前缀
$utoken = "8e20pvdr4gry3sdxpfpvudztosad76ia"; // 入方平台用户授权码，一次授权之后不可变更，长度必须为32个字符

/**
 * 备注
1. beginTime 和 endTime 同时为 0 时，代表该游戏 24 小时开放
2. domain url 地址可通过访问 MWG 提供的《平台地址》接口获得。
3. gameId 介于 1~500 表示为 Flash 游戏，gameId 介于 501~1000 表示为 APP 游戏，gameId 介于1001~1500 表示为 H5 游戏。
 */
$aWmGames = array(
    array(
        'gameId' => '10001',
        'gameName' => '活动领奖专区',
        'gameIcon' => '10001_icon1_0.png', // 游戏小图标
        'gameState' => '0', // 维护状态 0 - 运营中, 1 - 维护
        'gameStatus' => '1', // 游戏类型状态  0 一般, 1 热门, 2 推荐, 3 新游戏
        'beginTime' => '0',
        'endTime' => '0',
        'gameIcon2' => '10001_icon1_2.png', // 悬停图标 大图标
        'jackpotStatus' => '0', // 是否显示彩金 1 显示，0 隐藏
        'gameRuleUrl' => 'rule/rule.html?gameId=10001&lang=cn',
    ),
    array(
        'gameId' => '1051',
        'gameName' => '千炮捕鱼',
        'gameIcon' => '1051_icon1_0.png', // 游戏小图标
        'gameState' => '0', // 维护状态 0 - 运营中, 1 - 维护
        'gameStatus' => '1', // 游戏类型状态  0 一般, 1 热门, 2 推荐, 3 新游戏
        'beginTime' => '0',
        'endTime' => '0',
        'gameIcon2' => '1051_icon2_0.png', // 悬停图标 大图标
        'jackpotStatus' => '0', // 是否显示彩金 1 显示，0 隐藏
        'gameRuleUrl' => 'rule/cn/game_1051.html',
    ),
    array(
        'gameId' => '1041',
        'gameName' => '森林舞会',
        'gameIcon' => '1041_icon1_0.png',
        'gameState' => '0',
        'gameStatus' => '1',
        'beginTime' => '0',
        'endTime' => '0',
        'gameIcon2' => '1041_icon2_0.png',
        'jackpotStatus' => '0',
        'gameRuleUrl' => 'rule/cn/game_1041.html',
    ),
    array(
        'gameId' => '6004',
        'gameName' => '发大财',
        'gameIcon' => '6004_icon1_0.png',
        'gameState' => '0',
        'gameStatus' => '3',
        'beginTime' => '0',
        'endTime' => '0',
        'gameIcon2' => '6004_icon2_0.png',
        'jackpotStatus' => '0',
        'gameRuleUrl' => 'rule/cn/game_6004.html',
    ),
    array(
        'gameId' => '6012',
        'gameName' => '金运熊猫',
        'gameIcon' => '6012_icon1_0.png',
        'gameState' => '0',
        'gameStatus' => '3',
        'beginTime' => '0',
        'endTime' => '0',
        'gameIcon2' => '6012_icon2_0.png',
        'jackpotStatus' => '0',
        'gameRuleUrl' => 'rule/cn/game_6012.html',
    ),
    array(
        'gameId' => '6018',
        'gameName' => '吉祥如意',
        'gameIcon' => '6018_icon1_0.png',
        'gameState' => '0',
        'gameStatus' => '3',
        'beginTime' => '0',
        'endTime' => '0',
        'gameIcon2' => '6018_icon2_0.png',
        'jackpotStatus' => '0',
        'gameRuleUrl' => 'rule/cn/game_6018.html',
    ),
    array(
        'gameId' => '6002',
        'gameName' => '鱼鱼鱼',
        'gameIcon' => '6002_icon1_0.png',
        'gameState' => '0',
        'gameStatus' => '3',
        'beginTime' => '0',
        'endTime' => '0',
        'gameIcon2' => '6002_icon2_0.png',
        'jackpotStatus' => '0',
        'gameRuleUrl' => 'rule/cn/game_6002.html',
    ),
    array(
        'gameId' => '6005',
        'gameName' => '招财猫',
        'gameIcon' => '6005_icon1_0.png',
        'gameState' => '0',
        'gameStatus' => '3',
        'beginTime' => '0',
        'endTime' => '0',
        'gameIcon2' => '6005_icon2_0.png',
        'jackpotStatus' => '0',
        'gameRuleUrl' => 'rule/cn/game_6005.html',
    ),
    array(
        'gameId' => '1149',
        'gameName' => '蜜糖甜心',
        'gameIcon' => '1149_icon1_0.png',
        'gameState' => '0',
        'gameStatus' => '2',
        'beginTime' => '0',
        'endTime' => '0',
        'gameIcon2' => '1149_icon2_0.png',
        'jackpotStatus' => '0',
        'gameRuleUrl' => 'rule/cn/game_1149.html',
    ),
    array(
        'gameId' => '1147',
        'gameName' => '炸金花',
        'gameIcon' => '1147_icon1_0.png',
        'gameState' => '0',
        'gameStatus' => '0',
        'beginTime' => '0',
        'endTime' => '0',
        'gameIcon2' => '1147_icon2_0.png',
        'jackpotStatus' => '0',
        'gameRuleUrl' => 'rule/cn/game_1147.html',
    ),
    array(
        'gameId' => '1145',
        'gameName' => '金鲨银鲨',
        'gameIcon' => '1145_icon1_0.png',
        'gameState' => '0',
        'gameStatus' => '1',
        'beginTime' => '0',
        'endTime' => '0',
        'gameIcon2' => '1145_icon2_0.png',
        'jackpotStatus' => '0',
        'gameRuleUrl' => 'rule/cn/game_1145.html',
    ),
    array(
        'gameId' => '1153',
        'gameName' => '五路财神',
        'gameIcon' => '1153_icon1_0.png',
        'gameState' => '0',
        'gameStatus' => '1',
        'beginTime' => '0',
        'endTime' => '0',
        'gameIcon2' => '1153_icon2_0.png',
        'jackpotStatus' => '0',
        'gameRuleUrl' => 'rule/cn/game_1153.html',
    ),
    array(
        'gameId' => '1146',
        'gameName' => '楚汉德州',
        'gameIcon' => '1146_icon1_0.png',
        'gameState' => '0',
        'gameStatus' => '0',
        'beginTime' => '0',
        'endTime' => '0',
        'gameIcon2' => '1146_icon2_0.png',
        'jackpotStatus' => '0',
        'gameRuleUrl' => 'rule/cn/game_1146.html',
    ),
    array(
        'gameId' => '1110',
        'gameName' => '水浒传',
        'gameIcon' => '1110_icon1_0.png',
        'gameState' => '0',
        'gameStatus' => '2',
        'beginTime' => '0',
        'endTime' => '0',
        'gameIcon2' => '1110_icon2_0.png',
        'jackpotStatus' => '0',
        'gameRuleUrl' => 'rule/cn/game_1110.html',
    ),
    array(
        'gameId' => '1112',
        'gameName' => '水浒英雄',
        'gameIcon' => '1112_icon1_0.png',
        'gameState' => '0',
        'gameStatus' => '2',
        'beginTime' => '0',
        'endTime' => '0',
        'gameIcon2' => '1112_icon2_0.png',
        'jackpotStatus' => '0',
        'gameRuleUrl' => 'rule/cn/game_1112.html',
    ),
    array(
        'gameId' => '1023',
        'gameName' => '超级斗地主',
        'gameIcon' => '1023_icon1_0.png',
        'gameState' => '0',
        'gameStatus' => '0',
        'beginTime' => '0',
        'endTime' => '0',
        'gameIcon2' => '1023_icon2_0.png',
        'jackpotStatus' => '0',
        'gameRuleUrl' => 'rule/cn/game_1023.html',
    ),
    array(
        'gameId' => '1143',
        'gameName' => '双龙抢珠',
        'gameIcon' => '1143_icon1_0.png',
        'gameState' => '0',
        'gameStatus' => '0',
        'beginTime' => '0',
        'endTime' => '0',
        'gameIcon2' => '1143_icon2_0.png',
        'jackpotStatus' => '0',
        'gameRuleUrl' => 'rule/cn/game_1143.html',
    ),
    array(
        'gameId' => '1111',
        'gameName' => '五龙争霸',
        'gameIcon' => '1111_icon1_0.png',
        'gameState' => '0',
        'gameStatus' => '0',
        'beginTime' => '0',
        'endTime' => '0',
        'gameIcon2' => '1111_icon2_0.png',
        'jackpotStatus' => '0',
        'gameRuleUrl' => 'rule/cn/game_1111.html',
    ),
    array(
        'gameId' => '1139',
        'gameName' => '百乐牛牛',
        'gameIcon' => '1139_icon1_0.png',
        'gameState' => '0',
        'gameStatus' => '1',
        'beginTime' => '0',
        'endTime' => '0',
        'gameIcon2' => '1139_icon2_0.png',
        'jackpotStatus' => '0',
        'gameRuleUrl' => 'rule/cn/game_1139.html',
    ),
    array(
        'gameId' => '1114',
        'gameName' => '天龙虎地',
        'gameIcon' => '1114_icon1_0.png',
        'gameState' => '0',
        'gameStatus' => '0',
        'beginTime' => '0',
        'endTime' => '0',
        'gameIcon2' => '1114_icon2_0.png',
        'jackpotStatus' => '0',
        'gameRuleUrl' => 'rule/cn/game_1114.html',
    ),
    array(
        'gameId' => '1141',
        'gameName' => '百家乐',
        'gameIcon' => '1141_icon1_0.png',
        'gameState' => '0',
        'gameStatus' => '2',
        'beginTime' => '0',
        'endTime' => '0',
        'gameIcon2' => '1141_icon2_0.png',
        'jackpotStatus' => '0',
        'gameRuleUrl' => 'rule/cn/game_1141.html',
    ),
    array(
        'gameId' => '1138',
        'gameName' => '皇家轮盘',
        'gameIcon' => '1138_icon1_0.png',
        'gameState' => '0',
        'gameStatus' => '0',
        'beginTime' => '0',
        'endTime' => '0',
        'gameIcon2' => '1138_icon2_0.png',
        'jackpotStatus' => '0',
        'gameRuleUrl' => 'rule/cn/game_1138.html',
    ),
    array(
        'gameId' => '1115',
        'gameName' => '黄金777',
        'gameIcon' => '1115_icon1_0.png',
        'gameState' => '0',
        'gameStatus' => '1',
        'beginTime' => '0',
        'endTime' => '0',
        'gameIcon2' => '1115_icon2_0.png',
        'jackpotStatus' => '0',
        'gameRuleUrl' => 'rule/cn/game_1115.html',
    ),
    array(
        'gameId' => '1120',
        'gameName' => '洪福齐天',
        'gameIcon' => '1120_icon1_0.png',
        'gameState' => '0',
        'gameStatus' => '0',
        'beginTime' => '0',
        'endTime' => '0',
        'gameIcon2' => '1120_icon2_0.png',
        'jackpotStatus' => '0',
        'gameRuleUrl' => 'rule/cn/game_1120.html',
    ),
    array(
        'gameId' => '1140',
        'gameName' => '太极',
        'gameIcon' => '1140_icon1_0.png',
        'gameState' => '0',
        'gameStatus' => '0',
        'beginTime' => '0',
        'endTime' => '0',
        'gameIcon2' => '1140_icon2_0.png',
        'jackpotStatus' => '0',
        'gameRuleUrl' => 'rule/cn/game_1140.html',
    ),
    array(
        'gameId' => '1144',
        'gameName' => '舞狮报喜',
        'gameIcon' => '1144_icon1_0.png',
        'gameState' => '0',
        'gameStatus' => '0',
        'beginTime' => '0',
        'endTime' => '0',
        'gameIcon2' => '1144_icon2_0.png',
        'jackpotStatus' => '0',
        'gameRuleUrl' => 'rule/cn/game_1144.html',
    ),
    array(
        'gameId' => '1022',
        'gameName' => '好运5扑克',
        'gameIcon' => '1022_icon1_0.png',
        'gameState' => '0',
        'gameStatus' => '0',
        'beginTime' => '0',
        'endTime' => '0',
        'gameIcon2' => '1022_icon2_0.png',
        'jackpotStatus' => '0',
        'gameRuleUrl' => 'rule/cn/game_1022.html',
    ),
    array(
        'gameId' => '1113',
        'gameName' => '魔豆',
        'gameIcon' => '1113_icon1_0.png',
        'gameState' => '0',
        'gameStatus' => '0',
        'beginTime' => '0',
        'endTime' => '0',
        'gameIcon2' => '1113_icon2_0.png',
        'jackpotStatus' => '0',
        'gameRuleUrl' => 'rule/cn/game_1113.html',
    ),
    array(
        'gameId' => '1021',
        'gameName' => '经典水果机',
        'gameIcon' => '1021_icon1_0.png',
        'gameState' => '0',
        'gameStatus' => '1',
        'beginTime' => '0',
        'endTime' => '0',
        'gameIcon2' => '1021_icon2_0.png',
        'jackpotStatus' => '0',
        'gameRuleUrl' => 'rule/cn/game_1021.html',
    ),
    array(
        'gameId' => '1125',
        'gameName' => 'HOOGA',
        'gameIcon' => '1125_icon1_0.png',
        'gameState' => '0',
        'gameStatus' => '0',
        'beginTime' => '0',
        'endTime' => '0',
        'gameIcon2' => '1125_icon2_0.png',
        'jackpotStatus' => '0',
        'gameRuleUrl' => 'rule/cn/game_1125.html',
    ),
    array(
        'gameId' => '1117',
        'gameName' => '明星97',
        'gameIcon' => '1117_icon1_0.png',
        'gameState' => '0',
        'gameStatus' => '0',
        'beginTime' => '0',
        'endTime' => '0',
        'gameIcon2' => '1117_icon2_0.png',
        'jackpotStatus' => '0',
        'gameRuleUrl' => 'rule/cn/game_1117.html',
    ),
    array(
        'gameId' => '6007',
        'gameName' => '聚财迎富-财源广进',
        'gameIcon' => '6007_icon1_0.png',
        'gameState' => '0',
        'gameStatus' => '0',
        'beginTime' => '0',
        'endTime' => '0',
        'gameIcon2' => '6007_icon2_0.png',
        'jackpotStatus' => '0',
        'gameRuleUrl' => 'rule/cn/game_6007.html',
    ),
);