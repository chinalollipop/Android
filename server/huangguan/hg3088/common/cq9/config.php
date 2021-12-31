<?

//$cq_root_url = "https://apie.cqgame.cc";  // 生产
$cq_root_url = "http://api.cqgame.games"; //测试

$gamehall = 'CQ9';  //游戏厂商id


//测试
$api_token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyaWQiOiI1ZDlkOGUwNDAwNWIwODAwMDE1NWYxZGUiLCJhY2NvdW50Ijoic3l0Iiwib3duZXIiOiI1ZDlkOGUwNDAwNWIwODAwMDE1NWYxZGUiLCJwYXJlbnQiOiJzZWxmIiwiY3VycmVuY3kiOiJDTlkiLCJqdGkiOiI2Njk3MDEzOTIiLCJpYXQiOjE1NzA2MDY1OTYsImlzcyI6IkN5cHJlc3MiLCJzdWIiOiJTU1Rva2VuIn0.2Q8-iXlwZ_K0I5NC-TwZ0eQrHl1r-3gvVPXJFiQIbCE";
// 0086生产
//$api_token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyaWQiOiI1ZGFmZjVjMmVmMDYyNjAwMDFmOWM4MjYiLCJhY2NvdW50IjoiaGcwMDg2Iiwib3duZXIiOiI1ZGFmZGNkOWM1ZGNlNjAwMDE5YTQzODgiLCJwYXJlbnQiOiI1ZGFmZGNkOWM1ZGNlNjAwMDE5YTQzODgiLCJjdXJyZW5jeSI6IkNOWSIsImp0aSI6IjE5MjE0MjA2MCIsImlhdCI6MTU3MTgxMjgwMiwiaXNzIjoiQ3lwcmVzcyIsInN1YiI6IlNTVG9rZW4ifQ.H6F6BFQ82PH10tWOCCOpSh27966wx3-csUiVBp-owv4";
// 金沙宏发
//$api_token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyaWQiOiI1ZGFmZjYxYjA2ZjllOTAwMDEwZDkyYTMiLCJhY2NvdW50IjoianNoZiIsIm93bmVyIjoiNWRhZmRjZDljNWRjZTYwMDAxOWE0Mzg4IiwicGFyZW50IjoiNWRhZmRjZDljNWRjZTYwMDAxOWE0Mzg4IiwiY3VycmVuY3kiOiJDTlkiLCJqdGkiOiI4MDY0Nzc0MTYiLCJpYXQiOjE1NzE4MTI4OTEsImlzcyI6IkN5cHJlc3MiLCJzdWIiOiJTU1Rva2VuIn0.BWrEl_S7yWP7pUp7j8YFS_71uWYa3X3dy7ZS6ScLFMA";
// a365003
//$api_token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyaWQiOiI1ZmEwZjc1N2M2YTNjMjAwMDEyYzNjNzciLCJhY2NvdW50IjoiYTM2NTAwMyIsIm93bmVyIjoiNWRhZmRjZDljNWRjZTYwMDAxOWE0Mzg4IiwicGFyZW50IjoiNWRhZmRjZDljNWRjZTYwMDAxOWE0Mzg4IiwiY3VycmVuY3kiOiJDTlkiLCJqdGkiOiIxMzIxMDgyMTQiLCJpYXQiOjE2MDQzODQ1OTksImlzcyI6IkN5cHJlc3MiLCJzdWIiOiJTU1Rva2VuIn0.CP30wW88VIh2BwZD49lQIvqWeo1kzMhBLyG3j1Og1SE";


$member_ext_ref = 'dev'; // 平台标识（2-50） dev  jshf
$cq_prefix = ''; // 平台前缀  $member_ext_ref.'_'


$timezone = "UTC-4"; // UTC-4 美东时间，UTC+8 北京时间
$currency = "CNY"; // USD, CNY, GBP, EUR
$language = "zh-cn"; // 目前全額提供 zh-cn ,en 多數已支援 th

$status = 'ongoing';    //notbegin=未開始活動 、ongoing=進行中活動、ending=活動已結束、finish =活動結束

$page = 1;  //注單查詢
$pagesize = 20000;  //注單查詢

// CQ9电子游戏
$cqDianziGames = array(
    '1'=>'钻石水果王',
    '2'=>'棋圣',
    '3'=>'杰克高手',
    '4'=>'森林泰后',
    '5'=>'金大款',
    '6'=>'1945',
    '7'=>'跳起來',
    '8'=>'甜蜜蜜So',
    '9'=>'钟馗运财',
    '10'=>'五福临门',
    '11'=>'梦游仙境2',
    '12'=>'金玉满堂',
    '13'=>'樱花妹子',
    '14'=>'绝赢巫师',
    '15'=>'GuGuGu',
    '16'=>'五行',
    '17'=>'祥狮献瑞',
    '18'=>'雀王',
    '19'=>'风火轮',
    '20'=>'发发发',
    '21'=>'野狼传说',
    '22'=>'庶务西游二课',
    '23'=>'金元宝',
    '24'=>'跳起来2',
    '25'=>'扑克拉霸',
    '26'=>'777',
    '27'=>'魔法世界',
    '28'=>'食神',
    '29'=>'水世界',
    '30'=>'三国序',
    '31'=>'武圣',
    '32'=>'通天神探狄仁杰',
    '33'=>'火烧连环船',
    '34'=>'地鼠战役',
    '35'=>'疯狂哪吒',
    '36'=>'夜店大亨',
    '38'=>'舞力全开',
    '39'=>'飞天',
    '40'=>'镖王争霸',
    '41'=>'水球大战',
    '42'=>'福尔摩斯',
    '43'=>'恭贺新禧',
    '44'=>'豪华水果王',
    '45'=>'超级发',
    '46'=>'狼月',
    '47'=>'法老宝藏',
    '48'=>'莲',
    '49'=>'寂寞星球',
    '50'=>'鸿福齐天',
    '51'=>'嗨爆大马戏',
    '52'=>'跳高高',
    '53'=>'来电99',
    '54'=>'火草泥马',
    '55'=>'魔龙传奇',
    '56'=>'黯夜公爵',
    '57'=>'神兽争霸',
    '58'=>'金鸡报囍2',
    '59'=>'夏日猩情',
    '60'=>'丛林舞会',
    '61'=>'天天吃豆',
    '62'=>'非常钻',
    '63'=>'寻龙诀',
    '64'=>'宙斯',
    '65'=>'足球世界杯',
    '66'=>'火爆777',
    '67'=>'赚金蛋',
    '68'=>'悟空偷桃',
    '69'=>'發財神',
    '70'=>'万饱龙',
    '72'=>'好运年年',
    '74'=>'聚宝盆',
    '76'=>'旺旺旺',
    '77'=>'火凤凰',
    '78'=>'阿波罗',
    '79'=>'变色龙',
    '80'=>'传奇海神',
    '81'=>'金银岛',
    '83'=>'火之女王',
    '84'=>'奇幻魔术',
    '86'=>'牛逼快跑',
    '87'=>'集电宝',
    '88'=>'金喜鹊桥',
    '89'=>'雷神',
    '92'=>'2018世界杯',
    '93'=>'世界杯明星',
    '94'=>'世界杯球衣',
    '95'=>'世界杯球鞋',
    '96'=>'足球宝贝',
    '97'=>'世界杯球场',
    '98'=>'世界杯全明星',
    '99'=>'跳更高',
    '100'=>'宾果消消消',
    '101'=>'星星消消乐',
    '102'=>'水果派对',
    '103'=>'宝石配对',
    '104'=>'海滨消消乐',
    '105'=>'单手跳高高',
    '108'=>'直式跳更高',
    '109'=>'單手跳起來',
    '111'=>'飞起来',
    '112'=>'盗法老墓',
    '113'=>'飞天财神',
    '114'=>'钻饱宝',
    '115'=>'冰雪女王',
    '116'=>'梦游仙境',
    '117'=>'东方神起',
    '118'=>'老司机',
    '121'=>'直式跳起來2',
    '122'=>'印加祖瑪',
    '123'=>'直式五福臨門',
    '124'=>'锁象无敌',
    '125'=>'直式宙斯',
    '126'=>'轉珠豬',
    '127'=>'直式武圣',
    '128'=>'转大钱',
    '129'=>'直式金鸡报喜2',
    '130'=>'偷金妹子',
    '131'=>'直式发财神',
    '132'=>'再喵一个',
    '133'=>'直式洪福齐天',
    '134'=>'家裡有矿',
    '136'=>'奔跑吧猛兽',
    '137'=>'直式蹦迪',
    '138'=>'跳过來',
    '139'=>'火燒連環船',
    '140'=>'火烧连环船2',
    '141'=>'圣诞来啦',
    '142'=>'火神',
    '143'=>'发财福娃',
    '144'=>'钻更多',
    '145'=>'印金工厂',
    '146'=>'九莲宝灯',
    '147'=>'花开富贵',
    '148'=>'有如神柱',
    '149'=>'龙舟',
    '150'=>'寿星大发',
    '151'=>'龙虎水果机',
    '152'=>'双飞',
    '153'=>'六顆糖',
    '154'=>'宙斯他爹',
    '157'=>'五形拳',
    '159'=>'贏光派對',
    '160'=>'发財神2',
    '161'=>'大力神',
    '163'=>'哪吒再临',
    '164'=>'幸运星',
    '165'=>'豪运十倍',
    '168'=>'猪事大吉',
    '170'=>'中国锦鲤',
    '201'=>'拳霸',
    '202'=>'舞媚娘',
    '203'=>'嗨起来',
    '204'=>'百宝箱',
    '205'=>'蹦迪',
    '221'=>'狄仁杰',
    '1010'=>'五福临门JP',
    '1067'=>'赚金蛋JP',
    '1074'=>'聚宝盆JP',
    //'AB1'=>'皇金渔场2',
    'AB3'=>'皇金渔场',
    'AD02'=>'四川麻将',
    'AD03'=>'斗地主',
    'AMfish'=>'钓鱼高手',
    'AN01'=>'恶魔侦探',
    'AN02'=>'雷神托尔',
    'AN03'=>'财神',
    'AN04'=>'罗马竞技场',
    'AN05'=>'金银岛2',
    'AN06'=>'开心农场',
    'AR01'=>'妙笔生财',
    'AR02'=>'喵财进宝',
    'AR03'=>'此地无银',
    'AR04'=>'斗地主经典版',
    'AR05'=>'鱼跃龙门',
    'AR06'=>'丛林宝藏',
    'AR07'=>'功夫小神通',
    'AR08'=>'点石成金',
    'AR09'=>'隔壁王二',
    'AR11'=>'五鬼运财',
    'AR12'=>'8级台风',
    'AR13'=>'汪财进宝',
    'AR14'=>'囍',
    'AR15'=>'爱琴海',
    'AR16'=>'守株待兔',
    'AR17'=>'马戏团连连发',
    'AR18'=>'财运亨通',
    'AR20'=>'宝莲灯',
    'AR21'=>'鹊桥',
    'AR22'=>'妈祖传奇',
    'AR23'=>'五运',
    'AR24'=>'麻将小福星',
    'AR25'=>'八仙传奇',
    'AR26'=>'生肖传奇',
    'AR28'=>'贪吃猫',
    'AR29'=>'富贵金鸡',
    'AR37'=>'幸运传奇',
    'AR39'=>'僵尸的宝藏',
    'AR41'=>'斗地主升级版',
    'AS01'=>'运气靴',
    'AS02'=>'疯狂马戏团',
    'AS03'=>'驯龙高手',
    'AS04'=>'财富幽灵',
    'AS08'=>'黄金玛雅',
    'AS09'=>'好莱坞宠物',
    'AS10'=>'幸运3',
    'AS17'=>'塞特的宝藏',
    'AS18'=>'疯狂软糖',
    'AS19'=>'圣诞故事',
    'AS20'=>'死亡女王',
    'AS33'=>'猪的运气',
    'AT01'=>'一炮捕鱼',
    'AU01'=>'喵喵',
    'AU02'=>'丧尸大餐',
    'AU03'=>'火烧办公处',
    'AU05'=>'异外来客',
    'AU06'=>'厕所囧境',
    'BA202'=>'美女与野兽',
    'BN02'=>'五路财神',
    'BN03'=>'官将首',
    'BT01'=>'夺镖',
);

$aCqGames = array(
    array(
        'name'=>'钻石水果王', 'gameid'=>'1',
        'gameurl'=>'1_FruitKing.png',
    ),
    array(
        'name'=>'棋圣', 'gameid'=>'2',
        'gameurl'=>'2_GodofChess.png',
    ),
    array(
        'name'=>'杰克高手', 'gameid'=>'3',
        'gameurl'=>'3_VampireKiss.png',
    ),
    array(
        'name'=>'森林泰后', 'gameid'=>'4',
        'gameurl'=>'4_WildTarzan.png',
    ),
    array(
        'name'=>'金大款', 'gameid'=>'5',
        'gameurl'=>'5_MrRich.png',
    ),
    array(
        'name'=>'1945', 'gameid'=>'6',
        'gameurl'=>'6_1945.png',
    ),
    array(
        'name'=>'跳起來', 'gameid'=>'7',
        'gameurl'=>'7_RaveJump.png',
    ),
    array(
        'name'=>'甜蜜蜜So', 'gameid'=>'8',
        'gameurl'=>'8_SoSweet.png',
    ),
    array(
        'name'=>'钟馗运财', 'gameid'=>'9',
        'gameurl'=>'9_ZhongKui.png',
    ),
    array(
        'name'=>'五福临门', 'gameid'=>'10',
        'gameurl'=>'10_LuckyBats.png',
    ),
    array(
        'name'=>'梦游仙境2', 'gameid'=>'11',
        'gameurl'=>'11_Wonderland.png',
    ),
    array(
        'name'=>'金玉满堂', 'gameid'=>'12',
        'gameurl'=>'12_TreasureHouse.png',
    ),
    array(
        'name'=>'樱花妹子', 'gameid'=>'13',
        'gameurl'=>'13_SakuraLegend.png',
    ),
    array(
        'name'=>'绝赢巫师', 'gameid'=>'14',
        'gameurl'=>'14_RichWitch.png',
    ),
    array(
        'name'=>'GuGuGu', 'gameid'=>'15',
        'gameurl'=>'15_GuGuGu.png',
    ),
    array(
        'name'=>'五行', 'gameid'=>'16',
        'gameurl'=>'16_Super5.png',
    ),
    array(
        'name'=>'祥狮献瑞', 'gameid'=>'17',
        'gameurl'=>'17_GreatLion.png',
    ),
    array(
        'name'=>'雀王', 'gameid'=>'18',
        'gameurl'=>'18_MahjongKing.png',
    ),
    array(
        'name'=>'风火轮', 'gameid'=>'19',
        'gameurl'=>'19_HotSpin.png',
    ),
    array(
        'name'=>'发发发', 'gameid'=>'20',
        'gameurl'=>'20_888.png',
    ),
    array(
        'name'=>'野狼传说', 'gameid'=>'21',
        'gameurl'=>'21_Legendofthewolf.png',
    ),
    array(
        'name'=>'庶务西游二课', 'gameid'=>'22',
        'gameurl'=>'22_Monkeyofficelegend.png',
    ),
    array(
        'name'=>'金元宝', 'gameid'=>'23',
        'gameurl'=>'23_YuanBao.png',
    ),
    array(
        'name'=>'跳起来2', 'gameid'=>'24',
        'gameurl'=>'24_2.png',
    ),
    array(
        'name'=>'扑克拉霸', 'gameid'=>'25',
        'gameurl'=>'25_PokerSLOT.png',
    ),
    array(
        'name'=>'777', 'gameid'=>'26',
        'gameurl'=>'26_777.png',
    ),
    array(
        'name'=>'魔法世界', 'gameid'=>'27',
        'gameurl'=>'27_MagicWorld.png',
    ),
    array(
        'name'=>'食神', 'gameid'=>'28',
        'gameurl'=>'28_GodofCookery.png',
    ),
    array(
        'name'=>'水世界', 'gameid'=>'29',
        'gameurl'=>'29_WaterWorld.png',
    ),
    array(
        'name'=>'三国序', 'gameid'=>'30',
        'gameurl'=>'30_Warriorlegend.png',
    ),
    array(
        'name'=>'武圣', 'gameid'=>'31',
        'gameurl'=>'31_GodofWar.png',
    ),
    array(
        'name'=>'通天神探狄仁杰', 'gameid'=>'32',
        'gameurl'=>'32_DetectiveDee.png',
    ),
    array(
        'name'=>'火烧连环船', 'gameid'=>'33',
        'gameurl'=>'33_FireChibi.png',
    ),
    array(
        'name'=>'地鼠战役', 'gameid'=>'34',
        'gameurl'=>'34_GophersWar.png',
    ),
    array(
        'name'=>'疯狂哪吒', 'gameid'=>'35',
        'gameurl'=>'35_CrazyNaza.png',
    ),
    array(
        'name'=>'夜店大亨', 'gameid'=>'36',
        'gameurl'=>'36_PubTycoon.png',
    ),
    array(
        'name'=>'舞力全开', 'gameid'=>'38',
        'gameurl'=>'38_AllWilds.png',
    ),
    array(
        'name'=>'飞天', 'gameid'=>'39',
        'gameurl'=>'39_Apsaras.png',
    ),
    array(
        'name'=>'镖王争霸', 'gameid'=>'40',
        'gameurl'=>'40_DartsChampion.png',
    ),
    array(
        'name'=>'水球大战', 'gameid'=>'41',
        'gameurl'=>'41_WaterBalloons.png',
    ),
    array(
        'name'=>'福尔摩斯', 'gameid'=>'42',
        'gameurl'=>'42_holmes.png',
    ),
    array(
        'name'=>'恭贺新禧', 'gameid'=>'43',
        'gameurl'=>'43_gonghe.png',
    ),
    array(
        'name'=>'豪华水果王', 'gameid'=>'44',
        'gameurl'=>'44_FruitKingII.png',
    ),
    array(
        'name'=>'超级发', 'gameid'=>'45',
        'gameurl'=>'45_Super8.png',
    ),
    array(
        'name'=>'狼月', 'gameid'=>'46',
        'gameurl'=>'46_wolfmoon.png',
    ),
    array(
        'name'=>'法老宝藏', 'gameid'=>'47',
        'gameurl'=>'47_pharoahtreasures.png',
    ),
    array(
        'name'=>'莲', 'gameid'=>'48',
        'gameurl'=>'48_LOTUS.png',
    ),
    array(
        'name'=>'寂寞星球', 'gameid'=>'49',
        'gameurl'=>'49_LonelyPlanet.png',
    ),
    array(
        'name'=>'鸿福齐天', 'gameid'=>'50',
        'gameurl'=>'50_GoodFortune.png',
    ),
    array(
        'name'=>'嗨爆大马戏', 'gameid'=>'51',
        'gameurl'=>'51_EcstaticCircus.png',
    ),
    array(
        'name'=>'跳高高', 'gameid'=>'52',
        'gameurl'=>'52_JumpObsession.png',
    ),
    array(
        'name'=>'来电99', 'gameid'=>'53',
        'gameurl'=>'53_LoveNight.png',
    ),
    array(
        'name'=>'火草泥马', 'gameid'=>'54',
        'gameurl'=>'54_FunnyAlpaca.png',
    ),
    array(
        'name'=>'魔龙传奇', 'gameid'=>'55',
        'gameurl'=>'55_Dragonheart.png',
    ),
    array(
        'name'=>'黯夜公爵', 'gameid'=>'56',
        'gameurl'=>'56_Dracula.png',
    ),
    array(
        'name'=>'神兽争霸', 'gameid'=>'57',
        'gameurl'=>'57_TheBeastWar.png',
    ),
    array(
        'name'=>'金鸡报囍2', 'gameid'=>'58',
        'gameurl'=>'58_HappyRooster.png',
    ),
    array(
        'name'=>'夏日猩情', 'gameid'=>'59',
        'gameurl'=>'59_RadiantQueen.png',
    ),
    array(
        'name'=>'丛林舞会', 'gameid'=>'60',
        'gameurl'=>'60_JungleParty.png',
    ),
    array(
        'name'=>'天天吃豆', 'gameid'=>'61',
        'gameurl'=>'61_Mr.Bean.png',
    ),
    array(
        'name'=>'非常钻', 'gameid'=>'62',
        'gameurl'=>'62_SuperDiamonds.png',
    ),
    array(
        'name'=>'寻龙诀', 'gameid'=>'63',
        'gameurl'=>'63_TheGhouls.png',
    ),
    array(
        'name'=>'宙斯', 'gameid'=>'64',
        'gameurl'=>'64_Zeus.png',
    ),
    array(
        'name'=>'足球世界杯', 'gameid'=>'65',
        'gameurl'=>'65_GoldenKick.png',
    ),
    array(
        'name'=>'火爆777', 'gameid'=>'66',
        'gameurl'=>'66_Fire777.png',
    ),
    array(
        'name'=>'赚金蛋', 'gameid'=>'67',
        'gameurl'=>'67_Goldeneggs.png',
    ),
    array(
        'name'=>'悟空偷桃', 'gameid'=>'68',
        'gameurl'=>'68_5Dragons.png',
    ),
    array(
        'name'=>'發財神', 'gameid'=>'69',
        'gameurl'=>'69_facaishen.png',
    ),
    array(
        'name'=>'万饱龙', 'gameid'=>'70',
        'gameurl'=>'70_wbl.png',
    ),
    array(
        'name'=>'好运年年', 'gameid'=>'72',
        'gameurl'=>'72_WildWays.png',
    ),
    array(
        'name'=>'聚宝盆', 'gameid'=>'74',
        'gameurl'=>'74_jubaopen.png',
    ),
    array(
        'name'=>'旺旺旺', 'gameid'=>'76',
        'gameurl'=>'76_Won_Won_Won.png',
    ),
    array(
        'name'=>'火凤凰', 'gameid'=>'77',
        'gameurl'=>'77_huofenghuang.png',
    ),
    array(
        'name'=>'阿波罗', 'gameid'=>'78',
        'gameurl'=>'78_aboluo.png',
    ),
    array(
        'name'=>'变色龙', 'gameid'=>'79',
        'gameurl'=>'79_WinningMask.png',
    ),
    array(
        'name'=>'传奇海神', 'gameid'=>'80',
        'gameurl'=>'80_chuanqihaishen.png',
    ),
    array(
        'name'=>'金银岛', 'gameid'=>'81',
        'gameurl'=>'81_TreasureIsland.png',
    ),
    array(
        'name'=>'火之女王', 'gameid'=>'83',
        'gameurl'=>'83_FireQueen.png',
    ),
    array(
        'name'=>'奇幻魔术', 'gameid'=>'84',
        'gameurl'=>'84_WildMagic.png',
    ),
    array(
        'name'=>'牛逼快跑', 'gameid'=>'86',
        'gameurl'=>'86_RunningToro.png',
    ),
    array(
        'name'=>'集电宝', 'gameid'=>'87',
        'gameurl'=>'87_ChilliHeat.png',
    ),
    array(
        'name'=>'金喜鹊桥', 'gameid'=>'88',
        'gameurl'=>'88_HappyMagpies.png',
    ),
    array(
        'name'=>'雷神', 'gameid'=>'89',
        'gameurl'=>'89_BigRedWay.png',
    ),
    array(
        'name'=>'2018世界杯', 'gameid'=>'92',
        'gameurl'=>'92_2018.png',
    ),
    array(
        'name'=>'世界杯明星', 'gameid'=>'93',
        'gameurl'=>'93_wordstar.png',
    ),
    array(
        'name'=>'世界杯球衣', 'gameid'=>'94',
        'gameurl'=>'94_wordCup.png',
    ),
    array(
        'name'=>'世界杯球鞋', 'gameid'=>'95',
        'gameurl'=>'95_wordSneakers.png',
    ),
    array(
        'name'=>'足球宝贝', 'gameid'=>'96',
        'gameurl'=>'96_wordBaby.png',
    ),
    array(
        'name'=>'世界杯球场', 'gameid'=>'97',
        'gameurl'=>'97_wordCourt.png',
    ),
    array(
        'name'=>'世界杯全明星', 'gameid'=>'98',
        'gameurl'=>'98_wordStar.png',
    ),
    array(
        'name'=>'跳更高', 'gameid'=>'99',
        'gameurl'=>'99_jump.png',
    ),
    array(
        'name'=>'宾果消消消', 'gameid'=>'100',
        'gameurl'=>'100_Bingo.png',
    ),
    array(
        'name'=>'星星消消乐', 'gameid'=>'101',
        'gameurl'=>'101_Eliminate.png',
    ),
    array(
        'name'=>'水果派对', 'gameid'=>'102',
        'gameurl'=>'102_FruitParty.png',
    ),
    array(
        'name'=>'宝石配对', 'gameid'=>'103',
        'gameurl'=>'103_Gempairing.png',
    ),
    array(
        'name'=>'海滨消消乐', 'gameid'=>'104',
        'gameurl'=>'104_Beachside.png',
    ),
    array(
        'name'=>'单手跳高高', 'gameid'=>'105',
        'gameurl'=>'105_One-handed.png',
    ),
    array(
        'name'=>'直式跳更高', 'gameid'=>'108',
        'gameurl'=>'108_StraightJump.png',
    ),
    array(
        'name'=>'單手跳起來', 'gameid'=>'109',
        'gameurl'=>'109_JumpWith.png',
    ),
    array(
        'name'=>'飞起来', 'gameid'=>'111',
        'gameurl'=>'111_Fly.png',
    ),
    array(
        'name'=>'盗法老墓', 'gameid'=>'112',
        'gameurl'=>'112_PirateOld.png',
    ),
    array(
        'name'=>'飞天财神', 'gameid'=>'113',
        'gameurl'=>'113_FlyingGod.png',
    ),
    array(
        'name'=>'钻饱宝', 'gameid'=>'114',
        'gameurl'=>'114_Drilling.png',
    ),
    array(
        'name'=>'冰雪女王', 'gameid'=>'115',
        'gameurl'=>'115_SnowQueen.png',
    ),
    array(
        'name'=>'梦游仙境', 'gameid'=>'116',
        'gameurl'=>'116_Sleepwalking.png',
    ),
    array(
        'name'=>'东方神起', 'gameid'=>'117',
        'gameurl'=>'117_DongBang.png',
    ),
    array(
        'name'=>'老司机', 'gameid'=>'118',
        'gameurl'=>'118_OldDriver.png',
    ),
    array(
        'name'=>'直式跳起來2', 'gameid'=>'121',
        'gameurl'=>'121_StraightJump2.png',
    ),
    array(
        'name'=>'印加祖瑪', 'gameid'=>'122',
        'gameurl'=>'122_IncaZuma.png',
    ),
    array(
        'name'=>'直式五福臨門', 'gameid'=>'123',
        'gameurl'=>'123_fiveBlessings.png',
    ),
    array(
        'name'=>'锁象无敌', 'gameid'=>'124',
        'gameurl'=>'124_LockIcon.png',
    ),
    array(
        'name'=>'直式宙斯', 'gameid'=>'125',
        'gameurl'=>'125_StraightZeus.png',
    ),
    array(
        'name'=>'轉珠豬', 'gameid'=>'126',
        'gameurl'=>'126_TurningPig.png',
    ),
    array(
        'name'=>'直式武圣', 'gameid'=>'127',
        'gameurl'=>'127_StraightWusheng.png',
    ),
    array(
        'name'=>'转大钱', 'gameid'=>'128',
        'gameurl'=>'128_Turnbigmoney.png',
    ),
    array(
        'name'=>'直式金鸡报喜2', 'gameid'=>'129',
        'gameurl'=>'129_StraightGolden2.png',
    ),
    array(
        'name'=>'偷金妹子', 'gameid'=>'130',
        'gameurl'=>'130_Stealing.png',
    ),
    array(
        'name'=>'直式发财神', 'gameid'=>'131',
        'gameurl'=>'131_Straightfortune.png',
    ),
    array(
        'name'=>'再喵一个', 'gameid'=>'132',
        'gameurl'=>'132_OneMore.png',
    ),
    array(
        'name'=>'直式洪福齐天', 'gameid'=>'133',
        'gameurl'=>'133_StraightHongfu.png',
    ),
    array(
        'name'=>'家裡有矿', 'gameid'=>'134',
        'gameurl'=>'134_mines.png',
    ),
    array(
        'name'=>'奔跑吧猛兽', 'gameid'=>'136',
        'gameurl'=>'136_Run.png',
    ),
    array(
        'name'=>'直式蹦迪', 'gameid'=>'137',
        'gameurl'=>'137_Straight.png',
    ),
    array(
        'name'=>'跳过來', 'gameid'=>'138',
        'gameurl'=>'138_Jump.png',
    ),
    array(
        'name'=>'火燒連環船', 'gameid'=>'139',
        'gameurl'=>'139_StraightFire.png',
    ),
    array(
        'name'=>'火烧连环船2', 'gameid'=>'140',
        'gameurl'=>'140_FireBurning.png',
    ),
    array(
        'name'=>'圣诞来啦', 'gameid'=>'141',
        'gameurl'=>'141_Christmas.png',
    ),
    array(
        'name'=>'火神', 'gameid'=>'142',
        'gameurl'=>'142_Hephaestus.png',
    ),
    array(
        'name'=>'发财福娃', 'gameid'=>'143',
        'gameurl'=>'143_FortuneFuwa.png',
    ),
    array(
        'name'=>'钻更多', 'gameid'=>'144',
        'gameurl'=>'144_Drillmore.png',
    ),
    array(
        'name'=>'印金工厂', 'gameid'=>'145',
        'gameurl'=>'145_Yinjin.png',
    ),
    array(
        'name'=>'九莲宝灯', 'gameid'=>'146',
        'gameurl'=>'146_Jiulian.png',
    ),
    array(
        'name'=>'花开富贵', 'gameid'=>'147',
        'gameurl'=>'147_Blossoming.png',
    ),
    array(
        'name'=>'有如神柱', 'gameid'=>'148',
        'gameurl'=>'148_pillar.png',
    ),
    array(
        'name'=>'龙舟', 'gameid'=>'149',
        'gameurl'=>'149_Dragon.png',
    ),
    array(
        'name'=>'寿星大发', 'gameid'=>'150',
        'gameurl'=>'150_Shouxing.png',
    ),
    array(
        'name'=>'龙虎水果机 ', 'gameid'=>'151',
        'gameurl'=>'151_FruitMachine.png',
    ),
    array(
        'name'=>'双飞', 'gameid'=>'152',
        'gameurl'=>'152_Doubleflight.png',
    ),
    array(
        'name'=>'六顆糖', 'gameid'=>'153',
        'gameurl'=>'153_Sixsugar.png',
    ),
    array(
        'name'=>'宙斯他爹', 'gameid'=>'154',
        'gameurl'=>'154_Kronos.png',
    ),
    array(
        'name'=>'五形拳', 'gameid'=>'157',
        'gameurl'=>'157_Boxing.png',
    ),
    array(
        'name'=>'贏光派對', 'gameid'=>'159',
        'gameurl'=>'159_NeonBoozeUp8.png',
    ),
    array(
        'name'=>'发財神2', 'gameid'=>'160',
        'gameurl'=>'160_FaCaiShen.png',
    ),
    array(
        'name'=>'大力神', 'gameid'=>'161',
        'gameurl'=>'161_Hercules.png',
    ),
    array(
        'name'=>'哪吒再临', 'gameid'=>'163',
        'gameurl'=>'163_NeZhaAdvent.png',
    ),
    array(
        'name'=>'幸运星', 'gameid'=>'164',
        'gameurl'=>'164_LuckStar.png',
    ),
    array(
        'name'=>'豪运十倍', 'gameid'=>'165',
        'gameurl'=>'165_TenfoldLottery.png',
    ),
    array(
        'name'=>'猪事大吉', 'gameid'=>'168',
        'gameurl'=>'168_GoldenPigs.png',
    ),
    array(
        'name'=>'中国锦鲤', 'gameid'=>'170',
        'gameurl'=>'170_ChinaKoi.png',
    ),
    array(
        'name'=>'拳霸', 'gameid'=>'201',
        'gameurl'=>'201_MuayThai.png',
    ),
    array(
        'name'=>'舞媚娘', 'gameid'=>'202',
        'gameurl'=>'202_OrientalBeauty.png',
    ),
    array(
        'name'=>'嗨起来', 'gameid'=>'203',
        'gameurl'=>'203_RaveHigh.png',
    ),
    array(
        'name'=>'百宝箱', 'gameid'=>'204',
        'gameurl'=>'204_LuckyBoxes.png',
    ),
    array(
        'name'=>'蹦迪', 'gameid'=>'205',
        'gameurl'=>'205_DiscoNight.png',
    ),
    array(
        'name'=>'狄仁杰', 'gameid'=>'221',
        'gameurl'=>'221_Detective.png',
    ),
    array(
        'name'=>'五福临门JP', 'gameid'=>'1010',
        'gameurl'=>'1010_LuckyBatsJP.png',
    ),
    array(
        'name'=>'赚金蛋JP', 'gameid'=>'1067',
        'gameurl'=>'1067_GoldenEggs.png',
    ),
    array(
        'name'=>'聚宝盆JP', 'gameid'=>'1074',
        'gameurl'=>'1074_TreasureBowl.png',
    ),
    /*array(
        'name'=>'皇金渔场2', 'gameid'=>'AB1',
        'gameurl'=>'AB1_Paradise2.png',
    ),*/
    array(
        'name'=>'皇金渔场', 'gameid'=>'AB3',
        'gameurl'=>'AB3_Paradise.png',
    ),
    array(
        'name'=>'四川麻将', 'gameid'=>'AD02',
        'gameurl'=>'AD02_ScMahjong.png',
    ),
    array(
        'name'=>'斗地主', 'gameid'=>'AD03',
        'gameurl'=>'AD03_Fight.png',
    ),
    array(
        'name'=>'钓鱼高手', 'gameid'=>'AMfish',
        'gameurl'=>'AMfish_FishingMaster.png',
    ),
    array(
        'name'=>'恶魔侦探', 'gameid'=>'AN01',
        'gameurl'=>'AN01_DemonArchive.png',
    ),
    array(
        'name'=>'雷神托尔', 'gameid'=>'AN02',
        'gameurl'=>'AN02_PowerfulThor.png',
    ),
    array(
        'name'=>'财神', 'gameid'=>'AN03',
        'gameurl'=>'AN03_GodofFortune.png',
    ),
    array(
        'name'=>'罗马竞技场', 'gameid'=>'AN04',
        'gameurl'=>'AN04_Colosseum.png',
    ),
    array(
        'name'=>'金银岛2', 'gameid'=>'AN05',
        'gameurl'=>'AN05_TreasureIsland2.png',
    ),
    array(
        'name'=>'开心农场', 'gameid'=>'AN06',
        'gameurl'=>'AN06_HappyFarm.png',
    ),
    array(
        'name'=>'妙笔生财', 'gameid'=>'AR01',
        'gameurl'=>'AR01_TheMagicBrush.png',
    ),
    array(
        'name'=>'喵财进宝', 'gameid'=>'AR02',
        'gameurl'=>'AR02_FortuneCats.png',
    ),
    array(
        'name'=>'此地无银', 'gameid'=>'AR03',
        'gameurl'=>'AR03_BackyardGold.png',
    ),
    array(
        'name'=>'斗地主经典版', 'gameid'=>'AR04',
        'gameurl'=>'AR04_DouDiZhu.png',
    ),
    array(
        'name'=>'鱼跃龙门', 'gameid'=>'AR05',
        'gameurl'=>'AR05_DragonGate.png',
    ),
    array(
        'name'=>'丛林宝藏', 'gameid'=>'AR06',
        'gameurl'=>'AR06_JungleTreasure.png',
    ),
    array(
        'name'=>'功夫小神通', 'gameid'=>'AR07',
        'gameurl'=>'AR07_KickinKash.png',
    ),
    array(
        'name'=>'点石成金', 'gameid'=>'AR08',
        'gameurl'=>'AR08_StonetoGold.png',
    ),
    array(
        'name'=>'隔壁王二', 'gameid'=>'AR09',
        'gameurl'=>'AR09_HuGotLucky.png',
    ),
    array(
        'name'=>'五鬼运财', 'gameid'=>'AR11',
        'gameurl'=>'AR11_FiveGhosts.png',
    ),
    array(
        'name'=>'8级台风', 'gameid'=>'AR12',
        'gameurl'=>'AR12_TyphoonCash.png',
    ),
    array(
        'name'=>'汪财进宝', 'gameid'=>'AR13',
        'gameurl'=>'AR13_Doggone.png',
    ),
    array(
        'name'=>'囍', 'gameid'=>'AR14',
        'gameurl'=>'AR14_DoubleHappy.png',
    ),
    array(
        'name'=>'爱琴海', 'gameid'=>'AR15',
        'gameurl'=>'AR15_LoveStory.png',
    ),
    array(
        'name'=>'守株待兔', 'gameid'=>'AR16',
        'gameurl'=>'AR16_RabbitRampage.png',
    ),
    array(
        'name'=>'马戏团连连发', 'gameid'=>'AR17',
        'gameurl'=>'AR17_ChinaKoi.png',
    ),
    array(
        'name'=>'财运亨通', 'gameid'=>'AR18',
        'gameurl'=>'AR18_EternalFortune.png',
    ),
    array(
        'name'=>'宝莲灯', 'gameid'=>'AR20',
        'gameurl'=>'AR20_LotusLantern.png',
    ),
    array(
        'name'=>'鹊桥', 'gameid'=>'AR21',
        'gameurl'=>'AR21_MagpieBridge.png',
    ),
    array(
        'name'=>'妈祖传奇', 'gameid'=>'AR22',
        'gameurl'=>'AR22_Mazu.png',
    ),
    array(
        'name'=>'五运', 'gameid'=>'AR23',
        'gameurl'=>'AR23_FiveLucky.png',
    ),
    array(
        'name'=>'麻将小福星', 'gameid'=>'AR24',
        'gameurl'=>'AR24_XiaoFuXing.png',
    ),
    array(
        'name'=>'八仙传奇', 'gameid'=>'AR25',
        'gameurl'=>'AR25_BaXianChuanQi.png',
    ),
    array(
        'name'=>'生肖传奇', 'gameid'=>'AR26',
        'gameurl'=>'AR26_ShengXiaoChuanQi.png',
    ),
    array(
        'name'=>'贪吃猫', 'gameid'=>'AR28',
        'gameurl'=>'AR28_HungryCats.png',
    ),
    array(
        'name'=>'富贵金鸡', 'gameid'=>'AR29',
        'gameurl'=>'AR29_WealthyChicken.png',
    ),
    array(
        'name'=>'幸运传奇', 'gameid'=>'AR37',
        'gameurl'=>'AR37_LuckyLegend.png',
    ),
    array(
        'name'=>'僵尸的宝藏', 'gameid'=>'AR39',
        'gameurl'=>'AR39_ZombieFortune.png',
    ),
    array(
        'name'=>'斗地主升级版', 'gameid'=>'AR41',
        'gameurl'=>'AR41_DouPlus.png',
    ),
    /*array(
        'name'=>'运气靴', 'gameid'=>'AS01',
        'gameurl'=>'AS01_BootsofLuck.png',
    ),
    array(
        'name'=>'疯狂马戏团', 'gameid'=>'AS02',
        'gameurl'=>'AS02_Cirquedefous.png',
    ),
    array(
        'name'=>'驯龙高手', 'gameid'=>'AS03',
        'gameurl'=>'AS03_DragonHunters.png',
    ),
    array(
        'name'=>'财富幽灵', 'gameid'=>'AS04',
        'gameurl'=>'AS04_FortuneSpirits.png',
    ),
    array(
        'name'=>'黄金玛雅', 'gameid'=>'AS08',
        'gameurl'=>'AS08_GoldenMayan.png',
    ),
    array(
        'name'=>'好莱坞宠物', 'gameid'=>'AS09',
        'gameurl'=>'AS09_HollywoodPets.png',
    ),
    array(
        'name'=>'幸运3', 'gameid'=>'AS10',
        'gameurl'=>'AS10_Lucky3.png',
    ),
    array(
        'name'=>'塞特的宝藏', 'gameid'=>'AS17',
        'gameurl'=>'AS17_TreasureofSeti.png',
    ),
    array(
        'name'=>'疯狂软糖', 'gameid'=>'AS18',
        'gameurl'=>'AS18_WildFudge.png',
    ),
    array(
        'name'=>'圣诞故事', 'gameid'=>'AS19',
        'gameurl'=>'AS19_XmasTales.png',
    ),*/
    array(
        'name'=>'死亡女王', 'gameid'=>'AS20',
        'gameurl'=>'AS20_QueenOfDead.png',
    ),
    array(
        'name'=>'猪的运气', 'gameid'=>'AS33',
        'gameurl'=>'AS33_PigOfLuck.png',
    ),
    array(
        'name'=>'一炮捕鱼', 'gameid'=>'AT01',
        'gameurl'=>'AT01_Oneshot.png',
    ),
    array(
        'name'=>'喵喵', 'gameid'=>'AU01',
        'gameurl'=>'AU01_MeowMeow.png',
    ),
    array(
        'name'=>'丧尸大餐', 'gameid'=>'AU02',
        'gameurl'=>'AU02_FeedZombie.png',
    ),
    array(
        'name'=>'火烧办公处', 'gameid'=>'AU03',
        'gameurl'=>'AU03_BurnOffice.png',
    ),
    array(
        'name'=>'异外来客', 'gameid'=>'AU05',
        'gameurl'=>'AU05_StrangeEncounter.png',
    ),
    array(
        'name'=>'厕所囧境', 'gameid'=>'AU06',
        'gameurl'=>'AU06_OhCrap.png',
    ),
    array(
        'name'=>'美女与野兽', 'gameid'=>'BA202',
        'gameurl'=>'BA202_BeautyBeast.png',
    ),
    array(
        'name'=>'五路财神', 'gameid'=>'BN02',
        'gameurl'=>'BN02_FiveGodWealth.png',
    ),
    array(
        'name'=>'官将首', 'gameid'=>'BN03',
        'gameurl'=>'BN03_LeadGenerals.png',
    ),
    array(
        'name'=>'夺镖', 'gameid'=>'BT01',
        'gameurl'=>'BT01_LastRoll.png',
    ),
);
