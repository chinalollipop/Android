<?php
session_start();

include "../../../../app/member/include/config.inc.php";
require_once("../../../../../common/mg/config.php");
require_once("../../../../../common/ag/config.php");
require_once("../../../../../common/cq9/config.php");

// AG电子游戏列表
foreach ($agXinGames as $k => $v){
    $agGameList[$k]['gameid'] = $v['gameTypeW'];
    $agGameList[$k]['name'] = $v['name'];
    $agGameList[$k]['gameurl'] = '/images/game/ag/'.$v['gameurl'];
}
$agGameList = array_values($agGameList);

// WM电子游戏列表
foreach ($aWmGames as $k => $v){
    $mwGameList[$k]['gameId'] = $v['gameId'];
    $mwGameList[$k]['gameName'] = $v['gameName'];
    $mwGameList[$k]['gameIcon'] = $v['gameIcon'];
    $mwGameList[$k]['gameRuleUrl'] = $v['gameRuleUrl'];
}

// CQ9电子游戏列表
foreach ($aCqGames as $k => $v){
    $cqGameList[$k]['gameid'] = $v['gameid'];
    $cqGameList[$k]['name'] = $v['name'];
    $cqGameList[$k]['gameurl'] = '/images/game/cq9/'.$v['gameurl'];
}

// MG电子游戏列表
foreach ($mgGamesInfo as $k => $v){
    $mgGamesInfo[$k]['gameid'] = $v['item_id'];
}

$uid = $_SESSION['Oid'];

$test_username = explode('_',$agsxInit['tester']);
$test_username = $test_username[1]; // AG测试账号用户名
$yesday = date('Y-m-d',strtotime('-1 day'));

/* 奖池 */
$sumarr = array('22,912,279.31','21,412,279.71','32,710,269.92','23,152,701.10','25,541,691.81','5,341,791.01','29,171,740.51','9,318,095.15','8,016,678.91','19,268,651.60','21,578,711.15','25,171,071.43');
shuffle($sumarr); // 打乱数组

// 存款金额
$game_arr = array(
    array ('name' => 'bs****8','winnum' => 70030,'game' =>'水果拉霸'),
    array ('name' => 'tk****6','winnum' => 8081,'game' =>'杰克高手'),
    array ('name' => 'yt******1','winnum' => 35538,'game' =>'上海百乐门'),
    array ('name' => 'rg**9','winnum' => 26000,'game' =>'开心农场'),
    array ('name' => 'th***8','winnum' => 10560,'game' => '爱丽娜'),
    array ('name' => 'vb**3','winnum' => 10030,'game' => '爱丽娜'),
    array ('name' => 'rq****6','winnum' => 85700,'game' =>'杰克高手'),
    array ('name' => 'xc****3','winnum' => 8200,'game' =>'水果拉霸'),
    array ('name' => 'uy*****6','winnum' => 68260,'game' =>'篮球巨星'),
    array ('name' => 'gh***2','winnum' => 58428,'game' =>'5卷的驱动器'),
    array ('name' => 'xg****9','winnum' => 53472,'game' =>'5卷的驱动器'),
    array ('name' => 'rx******4','winnum' => 45568,'game' =>'比基尼派对'),
    array ('name' => 'eh****2','winnum' => 4353,'game' =>'比基尼派对'),
    array ('name' => 'hx****7','winnum' => 375286,'game' =>'上海百乐门'),
    array ('name' => 'gg***2','winnum' => 56275,'game' =>'上海百乐门'),
    array ('name' => 'wn****6','winnum' => 264930,'game' =>'开心农场'),
    array ('name' => 'kg****3','winnum' => 235620,'game' =>'开心农场'),
    array ('name' => 'fm****2','winnum' => 193570,'game' => '爱丽娜'),
    array ('name' => 'ka***5','winnum' => 176538,'game' => '爱丽娜'),
    array ('name' => 'yk***2','winnum' => 163579,'game' => '爱丽娜'),
    array ('name' => 'zb****2','winnum' => 223450,'game' =>'开心农场'),
    array ('name' => 'k2*****8','winnum' => 201101,'game' =>'开心农场'),
    array ('name' => 'j9******z','winnum' => 181000,'game' => '爱丽娜'),
    array ('name' => 'qf****4','winnum' => 141120,'game' => '爱丽娜'),
    array ('name' => 'zi***2','winnum' => 34418,'game' => '爱丽娜'),
    array ('name' => 'm1******5','winnum' => 20015,'game' => '爱丽娜'),
    array ('name' => 'f6****6','winnum' => 10030,'game' => '爱丽娜'),
    array ('name' => 'wd*****2','winnum' => 11040,'game' => '爱丽娜'),
    array ('name' => 'nx*****5','winnum' => 96554,'game' =>'足球明星'),
    array ('name' => 'ss****5','winnum' => 81210,'game' =>'杰克高手'),
    array ('name' => 'aa*******1','winnum' => 74057,'game' =>'水果拉霸'),
    array ('name' => 'qa******1','winnum' => 80990,'game' =>'杰克高手'),
    array ('name' => 'LL*******o','winnum' => 75001,'game' =>'水果拉霸'),
    array ('name' => 'p5****4','winnum' => 64100,'game' =>'篮球巨星'),
    array ('name' => 'm8*****6','winnum' => 56603,'game' =>'5卷的驱动器'),
    array ('name' => 'mm****2','winnum' => 32120,'game' =>'上海百乐门'),
    array ('name' => 'xz********7','winnum' => 21087,'game' =>'开心农场'),
    array ('name' => 'gg***1','winnum' => 12010,'game' => '爱丽娜'),
    array ('name' => 'tx******0','winnum' => 11810,'game' => '爱丽娜'),
    array ('name' => 'rh****1','winnum' => 8870,'game' =>'杰克高手'),
    array ('name' => 'fk*****22','winnum' => 321,'game' =>'上海百乐门'),
    array ('name' => 'ke*****a','winnum' => 352,'game' =>'上海百乐门'),
    array ('name' => 'wq******1','winnum' => 401,'game' =>'比基尼派对'),
    array ('name' => 'a3*****3','winnum' => 3822,'game' =>'上海百乐门'),
    array ('name' => 'bb*****q','winnum' => 6222,'game' =>'篮球巨星'),
    array ('name' => 'a5*****4','winnum' => 6333,'game' =>'篮球巨星'),
    array ('name' => 'de*****5','winnum' => 5544,'game' =>'5卷的驱动器'),
    array ('name' => 'q8******7','winnum' => 51654,'game' =>'5卷的驱动器'),
    array ('name' => 'd*****9','winnum' => 7956,'game' =>'水果拉霸'),
    array ('name' => 'aq*******4','winnum' => 4326,'game' =>'比基尼派对'),
    array ('name' => 'we*******5','winnum' => 4155,'game' =>'比基尼派对'),
    array ('name' => 'cq*****1','winnum' => 18,'game' => '爱丽娜'),
    array ('name' => 'c7******7','winnum' => 200,'game' =>'开心农场'),
    array ('name' => 'b9*******1','winnum' => 227,'game' =>'开心农场'),
    array ('name' => 'zz*******b','winnum' => 452,'game' =>'比基尼派对'),
    array ('name' => 'wc********6','winnum' => 267,'game' =>'开心农场'),
    array ('name' => 'az******4','winnum' => 307,'game' =>'上海百乐门'),
    array ('name' => 'fe*******1','winnum' => 183,'game' => '爱丽娜'),
    array ('name' => 'ht********4','winnum' => 762,'game' =>'水果拉霸'),
    array ('name' => 'fc*******8','winnum' => 891,'game' =>'杰克高手'),
    array ('name' => 'ht********4','winnum' => 62,'game' =>'篮球巨星'),
    array ('name' => 'fc*******8','winnum' => 891,'game' =>'杰克高手'),
    array ('name' => '7*****y ','winnum' => 22351,'game' =>'开心农场'),
    array ('name' => '6j*****h','winnum' => 15807,'game' => '爱丽娜'),
    array ('name' => '6a******7','winnum' => 1756243,'game' => '爱丽娜'),
    array ('name' => '7z*****j','winnum' => 46258,'game' =>'比基尼派对'),
    array ('name' => '7a******3','winnum' => 1762,'game' => '爱丽娜'),
    array ('name' => 'x5******m','winnum' => 45924,'game' =>'比基尼派对'),
    array ('name' => 'd*****a ','winnum' => 4620,'game' =>'比基尼派对'),
    array ('name' => 'k*******2 ','winnum' => 8562,'game' =>'杰克高手'),
    array ('name' => 'v********9 ','winnum' => 45612,'game' =>'比基尼派对'),
    array ('name' => '7******9','winnum' => 43520,'game' =>'比基尼派对'),
    array ('name' => 'p*******6','winnum' => 32045,'game' =>'上海百乐门'),
    array ('name' => 'h*******11 ','winnum' => 14552,'game' => '爱丽娜'),
    array ('name' => 'zx******49 ','winnum' => 40052,'game' =>'篮球巨星'),
    array ('name' => 'sd******32 ','winnum' => 195482,'game' => '爱丽娜'),
    array ('name' => 'rd******7','winnum' => 35002,'game' =>'上海百乐门'),
    array ('name' => 'qq*****2','winnum' => 24852,'game' =>'开心农场'),
    array ('name' => 'tt*****2','winnum' => 34755,'game' =>'上海百乐门'),
    array ('name' => 'qaz*****12 ','winnum' => 23140,'game' =>'开心农场'),
    array ('name' => 'zxc******32','winnum' => 65420,'game' =>'篮球巨星'),
    array ('name' => 'a****54','winnum' => 9845,'game' =>'足球明星'),
    array ('name' => 'ms*****0','winnum' => 51542,'game' =>'5卷的驱动器'),
    array ('name' => 'ma******2','winnum' => 421456,'game' =>'比基尼派对'),
    array ('name' => 'mz*****5','winnum' => 41675,'game' =>'比基尼派对'),
    array ('name' => 'm4********2','winnum' => 87128,'game' =>'上海百乐门'),
    array ('name' => 'm3**********8','winnum' => 15676,'game' =>'比基尼派对'),
    array ('name' => 'ml*****3','winnum' => 15891,'game' =>'上海百乐门'),
    array ('name' => 'mf****2','winnum' => 198572,'game' => '爱丽娜'),
    array ('name' => 'm9*******4','winnum' => 97573,'game' =>'上海百乐门'),
    array ('name' => 'm2*******2','winnum' => 32423,'game' =>'上海百乐门'),
    array ('name' => 'mg****','winnum' => 27538,'game' =>'开心农场'),
    array ('name' => 'm4********2','winnum' => 24832,'game' =>'开心农场'),
    array ('name' => 'mc*****3','winnum' => 14332,'game' => '爱丽娜'),
    array ('name' => 'ml****2','winnum' => 16273,'game' => '爱丽娜'),
    array ('name' => 'mn********5','winnum' => 12478,'game' => '爱丽娜'),
    array ('name' => 'mm********4','winnum' => 114564,'game' => '爱丽娜'),
    array ('name' => 'mm******2','winnum' => 95864,'game' =>'足球明星'),
    array ('name' => 'mh*****3','winnum' => 525675,'game' =>'5卷的驱动器'),
    array ('name' => 'm8*********2','winnum' => 575532,'game' =>'5卷的驱动器'),
    array ('name' => 'mq************3','winnum' => 63567,'game' =>'篮球巨星'),
    array ('name' => 'm2*********7','winnum' => 5433,'game' =>'5卷的驱动器'),
    array ('name' => 's***1m','winnum' => 6540,'game' =>'篮球巨星'),
    array ('name' => 'k***lf','winnum' => 5400,'game' =>'5卷的驱动器'),
    array ('name' => '9***67','winnum' => 3500,'game' =>'上海百乐门'),
    array ('name' => 'p***55','winnum' => 4700,'game' =>'比基尼派对'),
    array ('name' => 'q***32','winnum' => 6800,'game' =>'篮球巨星'),
    array ('name' => 'y***58','winnum' => 14000,'game' => '爱丽娜'),
    array ('name' => 'k***lf','winnum' => 15000,'game' => '爱丽娜'),
    array ('name' => '9***67','winnum' => 12000,'game' => '爱丽娜'),
    array ('name' => 's***1m','winnum' => 14785,'game' => '爱丽娜'),
    array ('name' => 'q***32','winnum' => 16520,'game' => '爱丽娜'),
    array ('name' => 'q***32','winnum' => 14780,'game' => '爱丽娜'),
    array ('name' => '9***67','winnum' => 15632,'game' => '爱丽娜'),
    array ('name' => 'k***lf','winnum' => 15632,'game' => '爱丽娜'),
    array ('name' => 's***1m','winnum' => 17589,'game' => '爱丽娜'),
    array ('name' => 's***1m','winnum' => 16547,'game' => '爱丽娜'),
    array ('name' => 'd***27','winnum' => 12358,'game' => '爱丽娜'),
    array ('name' => 'a***29','winnum' => 11456,'game' => '爱丽娜'),
    array ('name' => 'q***55 ','winnum' => 12365,'game' => '爱丽娜'),
    array ('name' => 'q***55 ','winnum' => 18590,'game' => '爱丽娜'),
    array ('name' => 'cl****1','winnum' => 35791,'game' =>'上海百乐门'),
    array ('name' => 'sf*****3','winnum' => 235874,'game' =>'开心农场'),
    array ('name' => 'pp***0','winnum' => 212648,'game' =>'开心农场'),
    array ('name' => 'qe******5','winnum' => 156165,'game' => '爱丽娜'),
    array ('name' => 'll*****2','winnum' => 12313,'game' => '爱丽娜'),
    array ('name' => 'ww****3','winnum' => 13213,'game' => '爱丽娜'),
    array ('name' => 'yy*****0','winnum' => 33201,'game' =>'上海百乐门'),
    array ('name' => 'ii***9','winnum' => 11106,'game' => '爱丽娜'),
    array ('name' => 'jh****4','winnum' => 95606,'game' =>'足球明星'),
    array ('name' => 'ri****0','winnum' => 85991,'game' =>'杰克高手'),
    array ('name' => 'li***1','winnum' => 38196,'game' =>'上海百乐门'),
    array ('name' => 'cv****3','winnum' => 523681,'game' =>'5卷的驱动器'),
    array ('name' => 'nn****t','winnum' => 15196,'game' => '爱丽娜'),
    array ('name' => 'f3***r','winnum' => 12136,'game' => '爱丽娜'),
    array ('name' => 'g6****1','winnum' => 19819,'game' =>'篮球巨星'),
    array ('name' => 'd1*****5','winnum' => 13163,'game' => '爱丽娜'),
    array ('name' => 'zb****h','winnum' => 11800,'game' => '爱丽娜'),
    array ('name' => 'm7******l','winnum' => 112380,'game' => '爱丽娜'),
    array ('name' => 'e9*****7','winnum' => 113280,'game' => '爱丽娜'),
    array ('name' => 'lj****0','winnum' => 13881,'game' => '爱丽娜'),
    array ('name' => 'zlf***66','winnum' => 28431,'game' =>'开心农场'),
    array ('name' => 'qw*****89','winnum' => 1892,'game' => '爱丽娜'),
    array ('name' => 'gjj**1 ','winnum' => 5976,'game' =>'5卷的驱动器'),
    array ('name' => 'kk**888 ','winnum' => 31725,'game' =>'上海百乐门'),
    array ('name' => 'w***168 ','winnum' => 16800,'game' => '爱丽娜'),
    array ('name' => '66***gq','winnum' => 2232,'game' =>'开心农场'),
    array ('name' => 'zhang** ','winnum' => 8627,'game' =>'杰克高手'),
    array ('name' => 'lufei**99','winnum' => 13930,'game' => '爱丽娜'),
    array ('name' => '130*******6 ','winnum' => 17831,'game' => '爱丽娜'),
    array ('name' => '177****855','winnum' => 28136,'game' =>'开心农场'),
    array ('name' => 'yang**11 ','winnum' => 261790,'game' =>'开心农场'),
    array ('name' => '138****3222','winnum' => 23189,'game' =>'开心农场'),
    array ('name' => 'qin**ong','winnum' => 53689,'game' =>'5卷的驱动器'),
    array ('name' => 'lu***33 ','winnum' => 24358,'game' =>'开心农场'),
    array ('name' => '45567***41','winnum' => 9841,'game' =>'足球明星'),
    array ('name' => '136****7688','winnum' => 3000,'game' =>'上海百乐门'),
    array ('name' => 'ta**ben ','winnum' => 8746,'game' =>'杰克高手'),
    array ('name' => 'xfc***23','winnum' => 22147,'game' =>'开心农场'),
    array ('name' => 'liuhai**9','winnum' => 26333,'game' =>'开心农场'),
    array ('name' => 'wangpe** ','winnum' => 22146,'game' =>'开心农场'),
    array ('name' => 'jkl****2 ','winnum' => 92536,'game' =>'足球明星'),
    array ('name' => 'zxc****8','winnum' => 65487,'game' =>'篮球巨星'),
    array ('name' => 'cyl****9','winnum' => 87987,'game' =>'杰克高手'),
    array ('name' => 'cha****l','winnum' => 59654,'game' =>'5卷的驱动器'),
    array ('name' => 'mg****88','winnum' => 36954,'game' =>'上海百乐门'),
    array ('name' => 'qt****aa','winnum' => 85569,'game' =>'杰克高手'),
    array ('name' => 'mv****31','winnum' => 123569,'game' => '爱丽娜'),
    array ('name' => 'we****87','winnum' => 45222,'game' =>'比基尼派对'),
    array ('name' => 'rng****1','winnum' => 12985,'game' => '爱丽娜'),
    array ('name' => 'sdf*****5 ','winnum' => 115236,'game' => '爱丽娜'),
    array ('name' => 'lta*****s ','winnum' => 9254,'game' =>'足球明星'),
    array ('name' => 'Q******41 ','winnum' => 98954,'game' =>'足球明星'),
    array ('name' => 'FG*****F','winnum' => 21369,'game' =>'开心农场'),
    array ('name' => 'FPX*****d ','winnum' => 15365,'game' => '爱丽娜'),
    array ('name' => 'dio*****a ','winnum' => 46325,'game' =>'比基尼派对'),
    array ('name' => 'mcv******5','winnum' => 10598,'game' => '爱丽娜'),
    array ('name' => 'zxc******m','winnum' => 20649,'game' =>'开心农场'),
    array ('name' => '184******45','winnum' => 9485,'game' =>'足球明星'),
    array ('name' => 'L*******d ','winnum' => 141800,'game' => '爱丽娜'),
    array ('name' => 'R4*******1','winnum' => 48256,'game' =>'比基尼派对'),
    array ('name' => 'zyx9***78 ','winnum' => 81425,'game' =>'杰克高手'),
    array ('name' => 'lw****444 ','winnum' => 1154501,'game' =>'足球明星'),
    array ('name' => 'ww***98 ','winnum' => 193155,'game' => '爱丽娜'),
    array ('name' => 'zy**58 ','winnum' => 410677,'game' =>'比基尼派对'),
    array ('name' => 'zzq***88','winnum' => 525003,'game' =>'杰克高手'),
    array ('name' => 'zxw****67','winnum' => 189599,'game' => '爱丽娜'),
    array ('name' => 'ljl1***19','winnum' => 11895,'game' => '爱丽娜'),
    array ('name' => 'W1****8 ','winnum' => 313825,'game' =>'上海百乐门'),
    array ('name' => 'wa****88 ','winnum' => 83033,'game' =>'杰克高手'),
    array ('name' => 'tt9*****8 ','winnum' => 9130166,'game' =>'足球明星'),
    array ('name' => 'dou*****147 ','winnum' => 3544995,'game' =>'5卷的驱动器'),
    array ('name' => 'dj****777','winnum' => 19408,'game' => '爱丽娜'),
    array ('name' => 'yjj55****88 ','winnum' => 174999,'game' => '爱丽娜'),
    array ('name' => '131****han','winnum' => 93333,'game' =>'足球明星'),
    array ('name' => '123****cheng','winnum' => 425501,'game' =>'比基尼派对'),
    array ('name' => 'xrf****6999 ','winnum' => 178195,'game' => '爱丽娜'),
    array ('name' => 'liu1******2525 ','winnum' => 22538,'game' =>'开心农场'),
    array ('name' => 'yl60*****704','winnum' => 448611,'game' =>'比基尼派对'),
    array ('name' => 'taom*****123','winnum' => 12676,'game' => '爱丽娜'),
    array ('name' => 'x192*****196 ','winnum' => 149937,'game' => '爱丽娜'),
    array ('name' => 'q****9 ','winnum' => 19785,'game' => '爱丽娜'),
    array ('name' => 'we******7 ','winnum' => 10808,'game' => '爱丽娜'),
    array ('name' => 'li******g ','winnum' => 481796,'game' =>'比基尼派对'),
    array ('name' => 'z***5 ','winnum' => 80325,'game' =>'杰克高手'),
    array ('name' => 'ch******3 ','winnum' => 79163,'game' =>'水果拉霸'),
    array ('name' => 'ni*******9 ','winnum' => 111478,'game' => '爱丽娜'),
    array ('name' => 'ba******i ','winnum' => 80589,'game' =>'杰克高手'),
    array ('name' => 'ri***8 ','winnum' => 28547,'game' =>'开心农场'),
    array ('name' => 'mi******f ','winnum' => 13587,'game' => '爱丽娜'),
    array ('name' => 'w****8 ','winnum' => 20578,'game' =>'开心农场'),
    array ('name' => '4*****7 ','winnum' => 78493,'game' =>'水果拉霸'),
    array ('name' => 'de****1 ','winnum' => 861185,'game' =>'杰克高手'),
    array ('name' => 'wu***9 ','winnum' => 11609,'game' => '爱丽娜'),
    array ('name' => 'yo*****6 ','winnum' => 117800,'game' => '爱丽娜'),
    array ('name' => '55***7 ','winnum' => 234071,'game' =>'开心农场'),
    array ('name' => 'ss****4 ','winnum' => 230100,'game' =>'开心农场'),
    array ('name' => 'a***9 ','winnum' => 83543,'game' =>'杰克高手'),
    array ('name' => '88*******4 ','winnum' => 112155,'game' => '爱丽娜'),
    array ('name' => 'xa****z ','winnum' => 175600,'game' => '爱丽娜'),
    array ('name' => 'za*****8 ','winnum' => 848700,'game' =>'杰克高手'),
    array ('name' => 'wq***1','winnum' => 8982,'game' =>'杰克高手'),
    array ('name' => 'sa**6','winnum' => 825,'game' =>'杰克高手'),
    array ('name' => 'sc****1 ','winnum' => 26365,'game' =>'开心农场'),
    array ('name' => 'fh****3 ','winnum' => 5318,'game' =>'5卷的驱动器'),
    array ('name' => 'cv****y','winnum' => 84333,'game' =>'杰克高手'),
    array ('name' => 'we****7','winnum' => 38218,'game' =>'上海百乐门'),
    array ('name' => 'sd****4 ','winnum' => 8487,'game' =>'杰克高手'),
    array ('name' => 'as****2','winnum' => 310253,'game' =>'上海百乐门'),
    array ('name' => 'uj****6 ','winnum' => 22325,'game' =>'开心农场'),
    array ('name' => 'zx****4 ','winnum' => 32215,'game' =>'上海百乐门'),
    array ('name' => 'lk****8 ','winnum' => 52352,'game' =>'5卷的驱动器'),
    array ('name' => 'sk****6','winnum' => 3324,'game' =>'上海百乐门'),
    array ('name' => 'vn****8 ','winnum' => 66596,'game' =>'篮球巨星'),
    array ('name' => 'lj****6 ','winnum' => 32321,'game' =>'上海百乐门'),
    array ('name' => 'lo****i ','winnum' => 10225,'game' => '爱丽娜'),
    array ('name' => 'wk****1 ','winnum' => 58298,'game' =>'5卷的驱动器'),
    array ('name' => 'lu****3','winnum' => 265213,'game' =>'开心农场'),
    array ('name' => 'po****2','winnum' => 53302,'game' =>'5卷的驱动器'),
    array ('name' => 'ly****8 ','winnum' => 8385,'game' =>'杰克高手'),
    array ('name' => 'oo****3','winnum' => 85986,'game' =>'杰克高手'),
    array ('name' => 'gghi***88','winnum' => 90054,'game' =>'足球明星'),
    array ('name' => 'dwe****878 ','winnum' => 23566,'game' =>'开心农场'),
    array ('name' => 'ggd***ada','winnum' => 15565,'game' => '爱丽娜'),
    array ('name' => 'uu****fd8','winnum' => 3512,'game' =>'上海百乐门'),
    array ('name' => 'eua***dvv','winnum' => 12502,'game' => '爱丽娜'),
    array ('name' => 'da***8ds','winnum' => 45644,'game' =>'比基尼派对'),
    array ('name' => 'hdad***99','winnum' => 3054,'game' =>'上海百乐门'),
    array ('name' => 'jjd***da','winnum' => 2232,'game' =>'开心农场'),
    array ('name' => 'da***u2311 ','winnum' => 1354,'game' => '爱丽娜'),
    array ('name' => 'aa***2','winnum' => 986550,'game' =>'足球明星'),
    array ('name' => 'ws***k ','winnum' => 48220,'game' =>'比基尼派对'),
    array ('name' => '15****6 ','winnum' => 175633,'game' => '爱丽娜'),
    array ('name' => 'sd***o','winnum' => 18650,'game' => '爱丽娜'),
    array ('name' => 'pf***60','winnum' => 5240,'game' =>'5卷的驱动器'),
    array ('name' => 'jk**l','winnum' => 67960,'game' =>'篮球巨星'),
    array ('name' => 'yk****58','winnum' => 76130,'game' =>'水果拉霸'),
    array ('name' => 'ty**0','winnum' => 8125,'game' =>'杰克高手'),
    array ('name' => 'qq*****99','winnum' => 4380,'game' =>'比基尼派对'),
    array ('name' => '13***a','winnum' => 12474,'game' => '爱丽娜'),
    array ('name' => 'as****4','winnum' => 384,'game' =>'上海百乐门'),
    array ('name' => 'qw*****e','winnum' => 335,'game' =>'上海百乐门'),
    array ('name' => 'ew*****a','winnum' => 296,'game' =>'开心农场'),
    array ('name' => 'd8****8','winnum' => 256,'game' =>'开心农场'),
    array ('name' => 'qw****5','winnum' => 200,'game' =>'开心农场'),
    array ('name' => 'we*****c','winnum' => 173,'game' => '爱丽娜'),
    array ('name' => 'qw***2','winnum' => 163,'game' => '爱丽娜'),
    array ('name' => 'wq*****3','winnum' => 146,'game' => '爱丽娜'),
    array ('name' => 'zx***2','winnum' => 113,'game' => '爱丽娜'),
    array ('name' => 'ad****3','winnum' => 103,'game' => '爱丽娜'),
    array ('name' => 'ds***g','winnum' => 98,'game' =>'足球明星'),
    array ('name' => 'sa****1','winnum' => 85,'game' =>'杰克高手'),
    array ('name' => 'sa***j','winnum' => 79,'game' =>'水果拉霸'),
    array ('name' => 'sd****4','winnum' => 76,'game' =>'水果拉霸'),
    array ('name' => 'ds****2','winnum' => 70,'game' =>'水果拉霸'),
    array ('name' => 'ew*****6','winnum' => 65,'game' =>'篮球巨星'),
    array ('name' => 'qw**e','winnum' => 50,'game' =>'5卷的驱动器'),
    array ('name' => 'ez*****w ','winnum' => 30,'game' =>'上海百乐门'),
    array ('name' => 'ds**n','winnum' => 20,'game' =>'开心农场'),
    array ('name' => 'zz****1','winnum' => 18,'game' => '爱丽娜'),
    array ('name' => '52****x','winnum' => 1117,'game' => '爱丽娜'),
    array ('name' => 'ch******1','winnum' => 3005,'game' =>'上海百乐门'),
    array ('name' => 'tg***5','winnum' => 400,'game' =>'比基尼派对'),
    array ('name' => 'cp***0','winnum' => 187,'game' => '爱丽娜'),
    array ('name' => '25***u','winnum' => 14956,'game' => '爱丽娜'),
    array ('name' => 'cz***3','winnum' => 2562,'game' =>'开心农场'),
    array ('name' => 'aa******1','winnum' => 497,'game' =>'比基尼派对'),
    array ('name' => 'L***0','winnum' => 2833,'game' =>'开心农场'),
    array ('name' => 'dh**8','winnum' => 3027,'game' =>'上海百乐门'),
    array ('name' => 'zj***7','winnum' => 5006,'game' =>'5卷的驱动器'),
    array ('name' => 'wa***9','winnum' => 23569,'game' =>'开心农场'),
    array ('name' => 'er***6','winnum' => 4578,'game' =>'比基尼派对'),
    array ('name' => 'xc*****6','winnum' => 7968,'game' =>'水果拉霸'),
    array ('name' => 'ww****5','winnum' => 13194,'game' => '爱丽娜'),
    array ('name' => 'pr*****2','winnum' => 6065,'game' =>'篮球巨星'),
    array ('name' => 'ow***9','winnum' => 39024,'game' =>'上海百乐门'),
    array ('name' => 'as******4','winnum' => 702,'game' =>'水果拉霸'),
    array ('name' => 'h1*****a','winnum' => 936,'game' =>'足球明星'),
    array ('name' => 'gx***6','winnum' => 508,'game' =>'5卷的驱动器'),
    array ('name' => 'xd*****sd12','winnum' => 32584,'game' =>'上海百乐门'),
    array ('name' => 'kl***56','winnum' => 52321,'game' =>'5卷的驱动器'),
    array ('name' => 'etl***kf ','winnum' => 6345,'game' =>'篮球巨星'),
    array ('name' => 'ui***da55','winnum' => 80056,'game' =>'杰克高手'),
    array ('name' => 'vb****tt ','winnum' => 603,'game' =>'篮球巨星'),
    array ('name' => 'iL***E','winnum' => 256,'game' =>'开心农场'),
    array ('name' => 'r****oc','winnum' => 10897,'game' => '爱丽娜'),
    array ('name' => 'oG***n','winnum' => 6402,'game' =>'篮球巨星'),
    array ('name' => 'a***cU','winnum' => 3245,'game' =>'上海百乐门'),
    array ('name' => 'u***a ','winnum' => 12550,'game' => '爱丽娜'),
    array ('name' => 'dd***45','winnum' => 230,'game' =>'开心农场'),
    array ('name' => 'dahi***88','winnum' => 90054,'game' =>'足球明星'),
    array ('name' => 'dwe****878 ','winnum' => 2356,'game' =>'开心农场'),
    array ('name' => 'das***ada','winnum' => 155659,'game' => '爱丽娜'),
    array ('name' => 'uu****898','winnum' => 3512,'game' =>'上海百乐门'),
    array ('name' => 'esda***dvv ','winnum' => 12502,'game' => '爱丽娜'),
    array ('name' => 'da***888 ','winnum' => 45644,'game' =>'比基尼派对'),
    array ('name' => 'hee***a','winnum' => 3054,'game' =>'上海百乐门'),
    array ('name' => 'dasd***da','winnum' => 22326,'game' =>'开心农场'),
    array ('name' => 'eq***ue11','winnum' => 1354,'game' => '爱丽娜'),
    array ('name' => 'w*****8','winnum' => 2560,'game' =>'开心农场'),
    array ('name' => 'l****0','winnum' => 32368,'game' =>'上海百乐门'),
    array ('name' => '5****9','winnum' => 8256,'game' =>'杰克高手'),
    array ('name' => 'm****8','winnum' => 725,'game' =>'水果拉霸'),
    array ('name' => 'g*****7','winnum' => 26908,'game' =>'开心农场'),
    array ('name' => 'p*****5','winnum' => 15897,'game' => '爱丽娜'),
    array ('name' => '8******7 ','winnum' => 6523,'game' =>'篮球巨星'),
    array ('name' => '0*****6','winnum' => 8970,'game' =>'杰克高手'),
    array ('name' => '8****0','winnum' => 2580,'game' =>'开心农场'),
    array ('name' => '7****6','winnum' => 24008,'game' =>'开心农场'),
    array ('name' => '8****0','winnum' => 56986,'game' =>'5卷的驱动器'),
    array ('name' => '0****0','winnum' => 5806,'game' =>'5卷的驱动器'),
    array ('name' => '9****1','winnum' => 1250,'game' => '爱丽娜'),
    array ('name' => '5*****8','winnum' => 7988,'game' =>'水果拉霸'),
    array ('name' => '4****5','winnum' => 74650,'game' =>'水果拉霸'),
    array ('name' => '7****3','winnum' => 4069,'game' =>'比基尼派对'),
    array ('name' => '8****9','winnum' => 9854,'game' =>'足球明星'),
    array ('name' => 'f****9','winnum' => 5808,'game' =>'5卷的驱动器'),
    array ('name' => '5****6','winnum' => 158007,'game' => '爱丽娜'),
    array ('name' => '9****4','winnum' => 26905,'game' =>'开心农场'),
    array ('name' => 'ab*****9','winnum' => 2896,'game' =>'开心农场'),
    array ('name' => 'as**5','winnum' => 1369,'game' => '爱丽娜'),
    array ('name' => 'me***6','winnum' => 5982,'game' =>'5卷的驱动器'),
    array ('name' => 'ni****1','winnum' => 10236,'game' => '爱丽娜'),
    array ('name' => 'yi****9','winnum' => 1258,'game' => '爱丽娜'),
    array ('name' => 'ty***1','winnum' => 208975,'game' =>'开心农场'),
    array ('name' => 'xu****8','winnum' => 4582,'game' =>'比基尼派对'),
    array ('name' => 'i**2','winnum' => 11936,'game' => '爱丽娜'),
    array ('name' => 'fa****7','winnum' => 53690,'game' =>'5卷的驱动器'),
    array ('name' => 'lk***3','winnum' => 3689,'game' =>'上海百乐门'),
    array ('name' => 'mj***5','winnum' => 59802,'game' =>'5卷的驱动器'),
    array ('name' => 'gh***4','winnum' => 3012,'game' =>'上海百乐门'),
    array ('name' => 'de***8','winnum' => 7652,'game' =>'水果拉霸'),
    array ('name' => 'fr****8','winnum' => 65238,'game' =>'篮球巨星'),
    array ('name' => 'we**7','winnum' => 103685,'game' => '爱丽娜'),
    array ('name' => 'qo***5','winnum' => 32189,'game' =>'上海百乐门'),
    array ('name' => 'mh*****0','winnum' => 532,'game' =>'5卷的驱动器'),
    array ('name' => 'bv***2','winnum' => 8652,'game' =>'杰克高手'),
    array ('name' => 'nh****8','winnum' => 23503,'game' =>'开心农场'),
    array ('name' => 'fj****5','winnum' => 68905,'game' =>'篮球巨星'),
);

shuffle($game_arr); // 打乱数组
$re_data = array(
    'date' => $yesday,
    'data' => array_slice($game_arr,1,50),
);
//$game_str = $redisObj->getSimpleOne('dz_game_arry'); // 取redis 设置的值
//$game_str = json_decode($game_str,true);
//
//if($game_str['date'] !=$yesday || !$game_str['date']){ // 每天更新
//    $redisObj->setOne('dz_game_arry',json_encode($re_data));
//}
//
//$game_af_str = $redisObj->getSimpleOne('dz_game_arry'); // 取redis 设置的值
//$game_af_str = json_decode($game_af_str,true);
//$game_af_str = json_encode($game_af_str);
$game_af_str = json_encode($re_data);
// var_dump($game_af_str);
$gametype = isset($_REQUEST['gametype'])?$_REQUEST['gametype']:''; // ag mg mw cq fg

?>

<style>
    .game_banner{height: 295px;}
    .game_mainBody{height:945px;background:url(/images/game/game_bg.jpg) center no-repeat}
    .game_mainBody .game_content{height:1120px;background:url(/images/game/content_bg.png) top center no-repeat}
    .game_choose{height: 100%;margin-left: 170px;}
    .game_choose ul{margin-left:10px;}
    .game_choose ul li{position:relative;cursor: pointer;float:left;width:100px;height:100%;text-align:center;line-height:30px}
    .game_choose ul li.active,.game_choose ul li:hover{background:url(/images/game/hover.png) center no-repeat}
    .game_choose ul li:before{display:block;content: '';width: 60px;height: 40px;margin: 5px auto 0;}
    .game_choose ul li:after{position:absolute;display:block;content: '';width: 21px;height: 25px;top:0;right: 6px;}
    .game_choose ul li.mg_li:before{background:url(/images/game/nav_mg_1.png) center no-repeat}
    .game_choose ul li.ag_li:before{background:url(/images/game/nav_ag_1.png) center no-repeat}
    .game_choose ul li.cq_li:before{background:url(/images/game/nav_cq9.png) center no-repeat;background-size: 100%;}
    .game_choose ul li.mw_li:before{background:url(/images/game/nav_mw.png) center no-repeat}
    .game_choose ul li.fg_li:before{background:url(/images/game/nav_fg.png) center no-repeat}
    .game_choose ul li.mg_li:after,.game_choose ul li.fg_li:after{background:url(/images/game/hot.png) center no-repeat}
    .game_right_content{position:absolute;width:186px;right:12px;top:67px}

    .jackpot_right{height:170px;overflow:hidden}
    .jackpot_right_con{position:relative;-webkit-animation:movestop 8s linear infinite;animation:movestop 8s linear infinite}
    .winning_name{margin:10px 0;display:flex;width:100%}
    .winning_name span{margin-right:0;flex:1;text-align:center;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:12px}
    .winning_name span:first-child{color:#7d7e7b}
    .winning_name span:nth-child(2){color:#ffcf2e}
    .winning_name span:last-child{color:#07b50a;margin-right:0}
    .mainBody .game_content .searchBar{width:100%;height:95px;padding-top:21px}
    .mainBody .game_content .searchBar h3{width:144px;height:70px;line-height:70px;font-size:18px;color:#fff;text-align:center;font-weight:700}
    .mainBody .game_content .searchBar .inputBox{position:relative;width:238px;height:40px;margin:24px 180px 0 0}
    .mainBody .game_content .searchBar .inputBox:before{position:absolute;display:block;content:'';width:25px;height:25px;background:url(/images/game/ss_tyc.png) center no-repeat;top:9px;left:6px}
    .mainBody .game_content .searchBar .inputBox .searchInput{font-size:16px;width:100%;height:44px;line-height:44px;background:url(/images/game/input_search.png) center no-repeat;border:none;color:#fff;padding:0 72px 0 35px}
    .mainBody .game_content .searchBar .inputBox .ico{position:absolute;width:60px;height:34px;background:url(/images/game/search_btn.png);margin:5px 0 0 10px;cursor:pointer;transition:all .5s ease;top:0;right:8px}
    .mainBody .hot-game-list{overflow:hidden;width:642px;height:612px;margin-left:5px}
    .mainBody .game_content .slotsGame{position:relative;width:850px;min-height:360px;margin:15px auto}
    .mainBody .game_content .slotsGame .gameBox{float:left;position:relative;width:150px;background:#292929;transition:all .5s ease;margin:0 5px 5px;height:148px}
    .mainBody .game_content .slotsGame .gameBox .gameName{width:100%;height:40px}
    .mainBody .game_content .slotsGame .gameName h3{width:143px;height:100%;line-height:40px;font-size:12px;font-weight:400;color:#fff;z-index:1;padding-left:7px}
    .mainBody .game_content .slotsGame .gameName h3 p{display:inline-block;font-size:12px;max-width:80px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
    .mainBody .game_content .slotsGame .gameName h3 span{float:right;display:inline-block;width:10px;height:10px;background:url(/images/game/start.png) no-repeat;margin:15px 2px 0 0}
    .mainBody .game_content .slotsGame .gameBox .imgBox{width:100%;height:110px;overflow:hidden}
    .mainBody .game_content .slotsGame .gameBox .imgBox span{display:block;width:100%;height:110px;margin:0 auto}
    .mainBody .game_content .game_btn{display:none;position:absolute;width:98%;height:110px;top:0;text-align:center}
    .mainBody .game_content .slotsGame .gameBox:hover .game_btn{display:block;background:rgba(0,0,0,.5);border:1px solid #fff100}
    .mainBody .game_content .slotsGame .btn1{width:100%;height:30px;line-height:30px;color:#fff100;margin:20px auto 0;transition:all 0.5s;display:inline-block}
    .mainBody .game_content .slotsGame .btn2{margin-top:10px}
    .mainBody .game_content .slotsGame .game_btn_one .btn1{margin-top:40px}
    .mainBody .game_content .slotsGame .pagination{width:642px;padding:5px 0}
    .cjcj_content {text-align: center;margin-top: 34px;font-size: 16px;}
    .game_bottom_ul{width:940px;margin:0 auto;overflow:hidden}
    .game_bottom_ul a{display:inline-block;cursor:pointer;float:left;width:12.5%;text-align:center;color:#a0a0a0}
    .game_bottom_ul img{margin:15px auto 10px;height:28px}
</style>

<div class="game_banner">

    <div class="game_top_img"></div>
    <div class="noticeContent">
        <div class="w_1200">
            <span></span>
            <marquee behavior="" direction="">
                <?php echo $_SESSION['memberNotice']; ?>
            </marquee>
        </div>
        
    </div>
</div>

<div class="mainBody pr game_mainBody">
    <div class="w_1200 slots" >
        <div class="game_content">

            <div class="searchBar">
                <div class="game_choose fl">
                    <ul>
                        <!--<li class="game_active">热门游戏</li>
                        <li>漫威热门系列</li>-->
                        <li class="mg_li <?php echo ($gametype=='mg' or $gametype=='')?'active':''; ?>" data-gametype="mg">MG电子</li>
                        <li class="ag_li <?php echo $gametype=='ag'?'active':''; ?>"  data-gametype="ag">AG电子</li>
                        <li class="cq_li <?php echo $gametype=='cq'?'active':''; ?>"  data-gametype="cq">CQ9电子</li>
                        <li class="mw_li <?php echo $gametype=='mw'?'active':''; ?>"  data-gametype="mw">MW电子</li>
                        <li class="fg_li <?php echo $gametype=='fg'?'active':''; ?>"  data-gametype="fg">FG电子</li>
                        <!--<li>电影老虎机</li>
                        <li>纸牌游戏</li>-->
                    </ul>
                </div>

                <div class="inputBox fr">
                    <label>
                        <input type="text" class="seachgame_input searchInput form-control search-game search-inpt tags" placeholder="游戏搜索...">
                    </label>
                    <div class="submit-btn ico"></div>
                </div>
            </div>
            <!-- 游戏-->
            <div class="slotsGame" id="gameSearch11">
                <div class="game_right_content">
                    <!-- 赢家榜 -->
                    <div class="jackpot_right">
                        <div class="jackpot_right_con">

                        </div>
                    </div>
                    <!-- 奖池 -->
                    <div class="cjcj_content">
                        <span>CNY</span>
                        <span class="cjcj_num"><?php echo $sumarr[0];?></span>
                    </div>
                </div>


                <div class="hot-game-list">

                </div>
                <div class="pagination">
                    <!--<span class="disabled" title="首页">上一页</span>
                    <span class="current">1</span>
                    <span>2</span>
                    <span>3</span>
                    <span>4</span>
                    <span>5</span>
                    <span>...</span>
                    <span>110</span>
                    <span>下一页</span>-->
                </div>
            </div>

            <!-- 底部菜单 -->
            <div class="game_bottom_ul">
                <a class="to_aboutus" data-index="1">
                    <img src="/images/game/dianhua.png"/>
                    电话回拨
                </a>
                <a class="to_agentreg">
                    <img src="/images/game/daili.png"/>
                    代理加盟
                </a>
                <a class="to_livechat">
                    <img src="/images/game/kefu.png"/>
                    在线客服
                </a>
                <?php
                if(!$uid){
                ?>
                    <a class="<?php echo (GUEST_LOGIN_MUST_INPUT_PHONE?'to_testphone':'to_testplaylogin');?>">
                        <img src="/images/game/shiwan.png"/>
                        免费试玩
                    </a>
                <?php
                }
                ?>

                <a class="to_promos">
                    <img src="/images/game/liwu.png"/>
                    优惠大厅
                </a>
                <a class="to_aboutus" data-index="0">
                    <img src="/images/game/bangzhu.png"/>
                    帮助中心
                </a>
                <a class="to_downloadapp">
                    <img src="/images/game/app.png"/>
                    APP下载
                </a>

                <?php
                if(!$uid){
                    ?>
                    <a class="to_memberreg">
                        <img src="/images/game/kaihu.png"/>
                        免费开户
                    </a>
                    <?php
                }
                ?>

            </div>
         </div>
    </div>
</div>

<script type="text/javascript">

    $(function () {
        var game_af_str = '<?php echo $game_af_str;?>'; // 游戏奖金
        game_af_str = $.parseJSON(game_af_str);
        // console.log(game_af_str)
        clearInterval(gameJackPort);
        // var uid = '<?php echo $uid;?>' ;
        var fr_gametype = '<?php echo $gametype;?>' ;


        var test_username = '<?php echo $test_username;?>';
        indexCommonObj.getUserQpBanlance(uid,'ag') ;
        indexCommonObj.addLiveUrl();

        var gameSwiper = ''; // 轮播
        var count = 16; // 每页展示数量
        var page_tt = 0; // 初始页码
        var game_type_c ='mg' ; // 默认游戏类型
        var game_list = {};

        if(fr_gametype != ''){
            game_type_c = fr_gametype ;
        }


        // 游戏列表渲染
        function int_page(cp,gamelist) {
            if(game_type_c=='ag'){ //ag
                game_list = <?php echo json_encode($agGameList, JSON_UNESCAPED_UNICODE);?> ;
            }else if(game_type_c=='mg'){ // mg
                game_list = <?php echo json_encode($mgGamesInfo, JSON_UNESCAPED_UNICODE);?> ;
            }else if(game_type_c=='cq'){ // cq
                //game_list = cq_list ;
                game_list = <?php echo json_encode($cqGameList, JSON_UNESCAPED_UNICODE);?> ;
            }else if(game_type_c=='mw'){ // mw
                game_list = <?php echo json_encode($mwGameList,JSON_UNESCAPED_UNICODE);?> ;
            }else if(game_type_c=='fg'){ // fg
                game_list = <?php echo json_encode($fgGameList,JSON_UNESCAPED_UNICODE);?> ;
            }
            if(!gamelist){
                gamelist = game_list;
            }

            var gstr ='' ;
                page_tt = Math.ceil(gamelist.length / count); // 总页数

                for (var j = (cp - 1) * count; j < (cp * count > gamelist.length ? gamelist.length : cp * count); j++) {
                    var realurl = '../../app/member/zrsx/login.php?uid='+uid+'&gameid='+gamelist[j].gameid; // 真钱
                    var tryurl = '../../app/member/zrsx/login.php?uid='+uid+'&username='+test_username+'&gameid='+gamelist[j].gameid; // 试玩

                    gstr += '  <div class="gameBox">' +
                        '                        <div class="imgBox">' ;
                    if(game_type_c=='ag' || game_type_c=='cq') { // ag cq9
		    	        if(game_type_c=='cq'){
                                realurl = '../../app/member/cq9/cq9_api.php?action=getLaunchGameUrl&game_id='+game_list[j].gameid ;
                                tryurl = 'https://demo.cqgame.games/';
                        }
                        gstr += '<span  style="background:url('+  gamelist[j].gameurl +') center no-repeat;background-size: '+(game_type_c=='ag'?'100%':'72%')+';"></span>' +
                            '</div>' +
                            ' <div class="game_btn"> <a href="javascript:;" class="btn1 purple" onclick="indexCommonObj.openGameCommon(this,\''+uid+'\',\''+ tryurl +'\')">免费试用</a>' +
                            '                         <a href="javascript:;" class="btn1 purple btn2" onclick="indexCommonObj.openGameCommon(this,\''+uid+'\',\''+ realurl +'\')" >进入游戏</a></div> '+
                            '                        <div class="gameName"><h3 title="'+gamelist[j].name+'"><p>'+ gamelist[j].name  +'</p><span></span><span></span><span></span><span></span><span></span></h3>';

                    }
                    else if(game_type_c=='mg') { // mg
                        realurl = '../../app/member/mg/mg_api.php?action=getLaunchGameUrl&game_id='+gamelist[j].gameid ;
                        tryurl = '../../app/member/mg/mg_api.php?action=getDemoLaunchGameUrl&game_id='+gamelist[j].gameid ;
                        gstr += '<span class="finsh_img" style="background:url(/images/game/mg/more/'+  gamelist[j].gameurl +') center no-repeat;background-size: 72%;"></span>' +
                            '</div>' +
                            '<div class="game_btn game_btn_one">  <a href="javascript:;" class="btn1 purple btn2" onclick="indexCommonObj.openGameCommon(this,\''+uid+'\',\''+ realurl +'\')" >进入游戏</a></div>'+
                            '                        <div class="gameName"><h3 title="'+gamelist[j].name+'"><p>'+ gamelist[j].name  +'</p><span></span><span></span><span></span><span></span><span></span></h3>';

                    }
                    else if(game_type_c=='mw'){ // mw
                        // realurl = '../../app/member/mw/mw_api.php?action=gameLobby&gameId='+gamelist[j].gameId ;
                        // gstr += '<span class="slide-img" style="background-image: url(../../images/game/mw/'+gamelist[j].gameIcon +');background-position: 0 -10px;" ></span>' +
                        //     '</div>' +
                        //     '                        <div class="gameName"><h3>'+ gamelist[j].gameName +'</h3>' +
                        //     '                            <a href="javascript:;" class="btn1 purple btn2" onclick="indexCommonObj.openGameCommon(this,\''+uid+'\',\''+ realurl +'\')" >进入游戏</a>' +
                        realurl = '../../app/member/mw/mw_api.php?action=gameLobby&gameId='+gamelist[j].gameId ;
                        gstr += '<span class="mw_img" style="background: url(/images/game/mw/'+gamelist[j].gameIcon +') center no-repeat;background-size: 90%;" ></span>' +
                            '</div>' +
                            ' <div class="game_btn game_btn_one"> <a href="javascript:;" class="btn1 purple btn2" onclick="indexCommonObj.openGameCommon(this,\''+uid+'\',\''+ realurl +'\')" >进入游戏</a></div>'+
                        '                        <div class="gameName"><h3 title="'+gamelist[j].gameName+'"><p>'+ gamelist[j].gameName  +'</p><span></span><span></span><span></span><span></span><span></span></h3>';

                    }
                    else if(game_type_c=='fg'){ // fg
                        realurl = '../../app/member/fg/fg_api.php?action=getLaunchGameUrl&game_id='+gamelist[j].gameId ;
                        tryurl = '../../app/member/fg/fg_api.php?action=getDemoLaunchGameUrl&game_id='+gamelist[j].gameId ;

                        gstr += '<span class="slide-img" style="background: url(/images/game/fg/'+gamelist[j].gameIcon +') center no-repeat;background-size: 78%;" ></span>' +
                            '</div>' +
                            '<div class="game_btn"> <a href="javascript:;" class="btn1 purple" onclick="indexCommonObj.openGameCommon(this,\''+uid+'\',\''+ tryurl +'\')">免费试用</a>' +
                            '                            <a href="javascript:;" class="btn1 purple btn2" onclick="indexCommonObj.openGameCommon(this,\''+uid+'\',\''+ realurl +'\')" >进入游戏</a></div>'+
                        '                        <div class="gameName"><h3 title="'+gamelist[j].gameName+'"><p>'+ gamelist[j].gameName  +'</p><span></span><span></span><span></span><span></span><span></span></h3>';
 ;
                    }
                            gstr += '                        </div>' +
                '                    </div>';

                }

            $('.hot-game-list').html(gstr) ;
            hoverGmae();


        }

        // 鼠标hover 游戏
        function hoverGmae() {
            $('.game-slide').each(function () {
                $(this).hover(function(){
                    $(this).find('.slide-img').addClass('blur');
                    $(this).find('.mask').removeClass('hide')
                }, function(){

                    $(this).find('.slide-img').removeClass('blur');
                    $(this).find('.mask').addClass('hide')
                })
            })
        }

    // 页码设置
    function setPageCount() {
        if (page_tt > 0) {
            var pstr = '';
               /*'<a href="javascript:void(0)"> << </a>' +*/
               // ' <a href="javascript:void(0)" topage="next"> < </a>' ;

            for (var j = 1; j <= page_tt; j++) {
                if (1 == j) {
                    pstr +='<a href="javascript:void(0)" class="swShowPage active" topage="1"> 1 </a>' ;
                } else {
                    pstr +='<a href="javascript:void(0)" class="swShowPage" topage="'+j+'">'+ j +'</a>' ;
                }
            }
           // pstr += ' <a href="javascript:void(0)" topage="pre"> > </a>';
                /*' <a href="javascript:void(0)"> << </a>'*/

            $('.pagination').html(pstr) ;

            $('.pagination').on('click','a',function () { // 绑定切换页码事件
                $(this).addClass('active').siblings().removeClass('active') ;
                int_page($(this).attr('toPage')) ;
            }) ;
        }
    }

    // 搜索游戏
        function seachGameName(){
            $('.submit-btn').on('click',function () {
                var txt = $('.seachgame_input').val();
                var seach_game_list = new Array();
                $.each(game_list,function (i,v) {
                    if (game_type_c=='mw' || game_type_c=='fg'){
                        if(v.gameName.indexOf(txt)>-1){ // 匹配搜索
                            // console.log(v.gameName)
                            seach_game_list.push(
                                {
                                    gameName: v.gameName ,
                                    gameIcon: v.gameIcon,
                                    gameId: v.gameId,
                                }
                            )
                        }
                    }
                    else{
                        if(v.name.indexOf(txt)>-1){ // 匹配搜索
                            // console.log(v.name)
                            seach_game_list.push(
                                {
                                    name: v.name ,
                                    gameurl: v.gameurl,
                                    gameid: v.gameid,
                                }
                            )
                        }
                    }
                })
                int_page(1,seach_game_list);
                setPageCount();

            })
        }

        // ag mg 游戏切换
        function changeGameNav(){
            $('.game_choose ul').find('li').on('click',function () {
                var gametype = $(this).attr('data-gametype');
                game_type_c = gametype ;
                $(this).addClass('active').siblings('li').removeClass('active');
                int_page(1);
                setPageCount();
            })
        }

            // 中奖信息
            function getGamePrize(){
                var $jackpot_right_con = $('.jackpot_right_con');
                var str ='';
                for(var k=0;k<game_af_str.data.length;k++){
                  str += '<div class="winning_name"><span>'+ game_af_str.data[k].name +'</span><span>'+ game_af_str.data[k].game +'</span><span>'+ game_af_str.data[k].winnum +'</span></div>' ;
                }
                $jackpot_right_con.html(str);

            }

        gameJackPort = setInterval(jackPortNumber,100) ;
        getGamePrize();
        int_page(1);
        setPageCount() ;
        seachGameName();
        enterSubmitAction();
        changeGameNav();

    })
</script>