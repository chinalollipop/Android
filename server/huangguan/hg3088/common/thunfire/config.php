<?

$operator_id = '312'; // 平台ID

$thunfireTryDomain = 'http://www.tfgaming.net/?demo=true'; //试玩网址

$api_url_main = 'https://api-test.diaoq.com'; //测试地址 只有第一次开启游戏用到, 创建账号
//$api_url_main = 'https://api.diaoq.com'; // 生产地址, 只有第一次开启游戏用到, 创建账号


$public_token = '75b7e803aaf542b99d45e235a1ad5050';  // public_token，用在iframe
//$iframe_url = "https://gc.zly889.com"; // iframe url 进大厅
$iframe_url = "https://gc-test.r4espt.com"; // iframe url 进大厅


$root_url = "https://spi-test.r4espt.com"; // 测试API网关地址
//$root_url = "https://spi.r4espt.com"; // 正式API网关地址

$auth = $private_token = 'f01b39f7-9049-465e-b7ec-3db93e1f9c24';// 测试代理商授权秘钥
//$auth = $private_token = '16978c13-1818-4787-be7e-9854a9a000b7';// 0086代理商授权秘钥



$default_partner_member_token = '98f822db709a672ad2787d76cb3eb6e33c736db5746a525b11aF24ba2ff26e01';    // 会员默认token

// thunfire电竞游戏
$thunFireCategory = array(
    '1' => '反恐精英',
    '2' => '刀塔2',
    '3' => '英雄联盟',
    '4' => '星际争霸II',
    '5' => '守望先锋',
    '6' => 'NBA 2K18',
    '7' => '街头霸王V',
    '8' => '炉石传说',
    '9' => '风暴英雄',
    '10' => '星际争霸I',
    '11' => '使命召唤',
    '12' => '彩虹6号',
    '13' => '绝地求生',
    '14' => '王者荣耀',
    '15' => '魔兽争霸3',
    '16' => '传说对决',
    '17' => '篮球',
    '18' => '火箭联盟',
    '19' => '堡垒之夜',
    '20' => '和平精英',
    '21' => '无尽对决',
    '22' => 'FIFA',
    '23' => 'Quake',
);

$thunFireMemberOddsStyle = [
    'euro' => '欧盘',
    'hongkong' => '香港盘',
    'indo' => '印度盘',
    'malay' => '马来盘',
];

// 第几局
$thunFireMapNum = [
    'MAP 1' => '第一局',
    'Q1' => '第一节',
    'FIRST HALF' => '上半场',
    'SECOND HALF' => '下半场',
    ];

// 盘口类型
$thunFireBetTypeName = [
    'WIN'=> '主盘口独赢',
    '1X2'=>'独赢',
    'AH'=> '让分局',
    'OU'=>'大小',
    'OE'=> '单双',
    'SPWINMAP'=>'局独赢',
    'WINMAP'=> '局独赢比分',
    'SPHA'=>'特别主客',
    'SPYN'=> '特别是否',
    'SPOE'=>'特别单双',
    'SPOU'=> '特别大小',
    'SP1X2'=>'特别1X2',
    'OR'=> '赢',
    'SPOR'=>'特别多项',
    'SPXX'=>'特别双项',
    ];

// 注单状况
$thunFireSettlementStatus = [ 'confirmed' => '未结算', 'settled' => '已结算', 'cancelled' => '取消', ];
// 注单结果(已有结果)
$thunFireResultStatus = ['WIN' => '赢','LOSS' => '输','DRAW' => '和','CANCELLED' => '取消',];

$thunFireBetSelection = ['home' => '主队', 'away' => '客队', 'under' => '小', 'even' => '双'];

$ticket_type = ['db' => '早盘', 'live'=> '滚球直播'];

$thunFireSearchTime = ['1' => '按下注时间：', '2' => '按比赛时间：', '3' => '按结算时间：', '4' => '按更改时间：',];