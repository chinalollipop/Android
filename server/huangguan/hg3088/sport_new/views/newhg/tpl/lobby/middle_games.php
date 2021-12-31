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
$gametype = isset($_REQUEST['gametype'])?$_REQUEST['gametype']:''; // ag mg mw cq


// 赢钱榜
$game_arr = array(
    array ('name' => 'bs****8','winnum' => 70031,'game' =>'水果拉霸','city' =>'海南省'),
    array ('name' => 'tk****6','winnum' => 8081,'game' =>'杰克高手','city' =>'广东省'),
    array ('name' => 'yt******1','winnum' => 35535,'game' =>'上海百乐门','city' =>'北京市'),
    array ('name' => 'rg**9','winnum' => 26001,'game' =>'开心农场','city' =>'广东省'),
    array ('name' => 'th***8','winnum' => 10561,'game' => '爱丽娜','city' =>'海南省'),
    array ('name' => 'vb**3','winnum' => 10031,'game' => '爱丽娜','city' =>'河南省'),
    array ('name' => 'rq****6','winnum' => 85701,'game' =>'杰克高手','city' =>'广东省'),
    array ('name' => 'xc****3','winnum' => 8201,'game' =>'水果拉霸','city' =>'海南省'),
    array ('name' => 'uy*****6','winnum' => 68261,'game' =>'篮球巨星','city' =>'广东省'),
    array ('name' => 'gh***2','winnum' => 58425,'game' =>'5卷的驱动器','city' =>'北京市'),
    array ('name' => 'xg****9','winnum' => 53472,'game' =>'5卷的驱动器','city' =>'江苏省'),
    array ('name' => 'rx******4','winnum' => 45565,'game' =>'比基尼派对','city' =>'北京市'),
    array ('name' => 'eh****2','winnum' => 4353,'game' =>'比基尼派对','city' =>'安徽省'),
    array ('name' => 'hx****7','winnum' => 375283,'game' =>'上海百乐门','city' =>'天津市'),
    array ('name' => 'gg***2','winnum' => 56275,'game' =>'上海百乐门','city' =>'北京市'),
    array ('name' => 'wn****6','winnum' => 264931,'game' =>'开心农场','city' =>'广东省'),
    array ('name' => 'kg****3','winnum' => 235621,'game' =>'开心农场','city' =>'海南省'),
    array ('name' => 'fm****2','winnum' => 193571,'game' => '爱丽娜','city' =>'广东省'),
    array ('name' => 'ka***5','winnum' => 176535,'game' => '爱丽娜','city' =>'北京市'),
    array ('name' => 'yk***2','winnum' => 163579,'game' => '爱丽娜','city' =>'山东省'),
    array ('name' => 'zb****2','winnum' => 223451,'game' =>'开心农场','city' =>'河南省'),
    array ('name' => 'k2*****8','winnum' => 201101,'game' =>'开心农场','city' =>'广东省'),
    array ('name' => 'j9******z','winnum' => 181001,'game' => '爱丽娜','city' =>'河南省'),
    array ('name' => 'qf****4','winnum' => 141121,'game' => '爱丽娜','city' =>'广东省'),
    array ('name' => 'zi***2','winnum' => 34415,'game' => '爱丽娜','city' =>'北京市'),
    array ('name' => 'm1******5','winnum' => 20015,'game' => '爱丽娜','city' =>'北京市'),
    array ('name' => 'f6****6','winnum' => 10031,'game' => '爱丽娜','city' =>'广东省'),
    array ('name' => 'wd*****2','winnum' => 11041,'game' => '爱丽娜','city' =>'海南省'),
    array ('name' => 'nx*****5','winnum' => 96554,'game' =>'足球明星','city' =>'浙江省'),
    array ('name' => 'ss****5','winnum' => 81211,'game' =>'杰克高手','city' =>'广东省'),
    array ('name' => 'aa*******1','winnum' => 74057,'game' =>'水果拉霸','city' =>'河北省'),
    array ('name' => 'qa******1','winnum' => 80991,'game' =>'杰克高手','city' =>'河南省'),
    array ('name' => 'LL*******o','winnum' => 75001,'game' =>'水果拉霸','city' =>'广东省'),
    array ('name' => 'p5****4','winnum' => 64101,'game' =>'篮球巨星','city' =>'广东省'),
    array ('name' => 'm8*****6','winnum' => 56603,'game' =>'5卷的驱动器','city' =>'天津市'),
    array ('name' => 'mm****2','winnum' => 32121,'game' =>'上海百乐门','city' =>'广东省'),
    array ('name' => 'xz********7','winnum' => 21087,'game' =>'开心农场','city' =>'河北省'),
    array ('name' => 'gg***1','winnum' => 12011,'game' => '爱丽娜','city' =>'广东省'),
    array ('name' => 'tx******0','winnum' => 11811,'game' => '爱丽娜','city' =>'海南省'),
    array ('name' => 'rh****1','winnum' => 8871,'game' =>'杰克高手','city' =>'广东省'),
    array ('name' => 'fk*****22','winnum' => 321,'game' =>'上海百乐门','city' =>'海南省'),
    array ('name' => 'ke*****a','winnum' => 352,'game' =>'上海百乐门','city' =>'江苏省'),
    array ('name' => 'wq******1','winnum' => 401,'game' =>'比基尼派对','city' =>'广东省'),
    array ('name' => 'a3*****3','winnum' => 3822,'game' =>'上海百乐门','city' =>'黑龙江省'),
    array ('name' => 'bb*****q','winnum' => 6222,'game' =>'篮球巨星','city' =>'江苏省'),
    array ('name' => 'a5*****4','winnum' => 6333,'game' =>'篮球巨星','city' =>'天津市'),
    array ('name' => 'de*****5','winnum' => 5544,'game' =>'5卷的驱动器','city' =>'黑龙江省'),
    array ('name' => 'q8******7','winnum' => 51654,'game' =>'5卷的驱动器','city' =>'浙江省'),
    array ('name' => 'd*****9','winnum' => 7953,'game' =>'水果拉霸','city' =>'安徽省'),
    array ('name' => 'aq*******4','winnum' => 4323,'game' =>'比基尼派对','city' =>'天津市'),
    array ('name' => 'we*******5','winnum' => 4155,'game' =>'比基尼派对','city' =>'北京市'),
    array ('name' => 'cq*****1','winnum' => 15,'game' => '爱丽娜','city' =>'北京市'),
    array ('name' => 'c7******7','winnum' => 201,'game' =>'开心农场','city' =>'广东省'),
    array ('name' => 'b9*******1','winnum' => 227,'game' =>'开心农场','city' =>'河北省'),
    array ('name' => 'zz*******b','winnum' => 452,'game' =>'比基尼派对','city' =>'江苏省'),
    array ('name' => 'wc********6','winnum' => 267,'game' =>'开心农场','city' =>'河北省'),
    array ('name' => 'az******4','winnum' => 307,'game' =>'上海百乐门','city' =>'河北省'),
    array ('name' => 'fe*******1','winnum' => 183,'game' => '爱丽娜','city' =>'天津市'),
    array ('name' => 'ht********4','winnum' => 762,'game' =>'水果拉霸','city' =>'江苏省'),
    array ('name' => 'fc*******8','winnum' => 891,'game' =>'杰克高手','city' =>'广东省'),
    array ('name' => 'ht********4','winnum' => 62,'game' =>'篮球巨星','city' =>'江苏省'),
    array ('name' => 'fc*******8','winnum' => 891,'game' =>'杰克高手','city' =>'广东省'),
    array ('name' => '7*****y ','winnum' => 22351,'game' =>'开心农场','city' =>'湖南省'),
    array ('name' => '6j*****h','winnum' => 15807,'game' => '爱丽娜','city' =>'河北省'),
    array ('name' => '6a******7','winnum' => 175624,'game' => '爱丽娜','city' =>'浙江省'),
    array ('name' => '7z*****j','winnum' => 46255,'game' =>'比基尼派对','city' =>'北京市'),
    array ('name' => '7a******3','winnum' => 1762,'game' => '爱丽娜','city' =>'江苏省'),
    array ('name' => 'x5******m','winnum' => 45924,'game' =>'比基尼派对','city' =>'浙江省'),
    array ('name' => 'd*****a ','winnum' => 4621,'game' =>'比基尼派对','city' =>'广东省'),
    array ('name' => 'k*******2 ','winnum' => 8562,'game' =>'杰克高手','city' =>'江苏省'),
    array ('name' => 'v********9 ','winnum' => 45612,'game' =>'比基尼派对','city' =>'江苏省'),
    array ('name' => '7******9','winnum' => 43521,'game' =>'比基尼派对','city' =>'广东省'),
    array ('name' => 'p*******6','winnum' => 32045,'game' =>'上海百乐门','city' =>'北京市'),
    array ('name' => 'h*******11 ','winnum' => 14552,'game' => '爱丽娜','city' =>'江苏省'),
    array ('name' => 'zx******49 ','winnum' => 40052,'game' =>'篮球巨星','city' =>'天津市'),
    array ('name' => 'sd******32 ','winnum' => 195482,'game' => '爱丽娜','city' =>'广东省'),
    array ('name' => 'rd******7','winnum' => 35002,'game' =>'上海百乐门','city' =>'江苏省'),
    array ('name' => 'qq*****2','winnum' => 24852,'game' =>'开心农场','city' =>'江苏省'),
    array ('name' => 'tt*****2','winnum' => 34755,'game' =>'上海百乐门','city' =>'北京市'),
    array ('name' => 'qaz*****12 ','winnum' => 23141,'game' =>'开心农场','city' =>'湖南省'),
    array ('name' => 'zxc******32','winnum' => 65421,'game' =>'篮球巨星','city' =>'广东省'),
    array ('name' => 'a****54','winnum' => 9845,'game' =>'足球明星','city' =>'北京市'),
    array ('name' => 'ms*****0','winnum' => 51542,'game' =>'5卷的驱动器','city' =>'江苏省'),
    array ('name' => 'ma******2','winnum' => 421453,'game' =>'比基尼派对','city' =>'天津市'),
    array ('name' => 'mz*****5','winnum' => 41675,'game' =>'比基尼派对','city' =>'北京市'),
    array ('name' => 'm4********2','winnum' => 87125,'game' =>'上海百乐门','city' =>'北京市'),
    array ('name' => 'm3**********8','winnum' => 15673,'game' =>'比基尼派对','city' =>'天津市'),
    array ('name' => 'ml*****3','winnum' => 15891,'game' =>'上海百乐门','city' =>'广东省'),
    array ('name' => 'mf****2','winnum' => 198572,'game' => '爱丽娜','city' =>'江苏省'),
    array ('name' => 'm9*******4','winnum' => 97573,'game' =>'上海百乐门','city' =>'天津市'),
    array ('name' => 'm2*******2','winnum' => 32423,'game' =>'上海百乐门','city' =>'广东省'),
    array ('name' => 'mg****','winnum' => 27535,'game' =>'开心农场','city' =>'北京市'),
    array ('name' => 'm4********2','winnum' => 24832,'game' =>'开心农场','city' =>'江苏省'),
    array ('name' => 'mc*****3','winnum' => 14332,'game' => '爱丽娜','city' =>'江苏省'),
    array ('name' => 'ml****2','winnum' => 16273,'game' => '爱丽娜','city' =>'天津市'),
    array ('name' => 'mn********5','winnum' => 12475,'game' => '爱丽娜','city' =>'北京市'),
    array ('name' => 'mm********4','winnum' => 114564,'game' => '爱丽娜','city' =>'浙江省'),
    array ('name' => 'mm******2','winnum' => 95864,'game' =>'足球明星','city' =>'浙江省'),
    array ('name' => 'mh*****3','winnum' => 525675,'game' =>'5卷的驱动器','city' =>'北京市'),
    array ('name' => 'm8*********2','winnum' => 575532,'game' =>'5卷的驱动器','city' =>'江苏省'),
    array ('name' => 'mq************3','winnum' => 63567,'game' =>'篮球巨星','city' =>'河北省'),
    array ('name' => 'm2*********7','winnum' => 5433,'game' =>'5卷的驱动器','city' =>'天津市'),
    array ('name' => 's***1m','winnum' => 6541,'game' =>'篮球巨星','city' =>'云南省'),
    array ('name' => 'k***lf','winnum' => 5401,'game' =>'5卷的驱动器','city' =>'广东省'),
    array ('name' => '9***67','winnum' => 3501,'game' =>'上海百乐门','city' =>'湖南省'),
    array ('name' => 'p***55','winnum' => 4701,'game' =>'比基尼派对','city' =>'广东省'),
    array ('name' => 'q***32','winnum' => 6801,'game' =>'篮球巨星','city' =>'湖南省'),
    array ('name' => 'y***58','winnum' => 14001,'game' => '爱丽娜','city' =>'重庆市'),
    array ('name' => 'k***lf','winnum' => 15001,'game' => '爱丽娜','city' =>'广东省'),
    array ('name' => '9***67','winnum' => 12001,'game' => '爱丽娜','city' =>'湖南省'),
    array ('name' => 's***1m','winnum' => 14785,'game' => '爱丽娜','city' =>'北京市'),
    array ('name' => 'q***32','winnum' => 16521,'game' => '爱丽娜','city' =>'广东省'),
    array ('name' => 'q***32','winnum' => 14781,'game' => '爱丽娜','city' =>'湖南省'),
    array ('name' => '9***67','winnum' => 15632,'game' => '爱丽娜','city' =>'江苏省'),
    array ('name' => 'k***lf','winnum' => 15632,'game' => '爱丽娜','city' =>'江苏省'),
    array ('name' => 's***1m','winnum' => 17589,'game' => '爱丽娜','city' =>'山东省'),
    array ('name' => 's***1m','winnum' => 16547,'game' => '爱丽娜','city' =>'河北省'),
    array ('name' => 'd***27','winnum' => 12355,'game' => '爱丽娜','city' =>'北京市'),
    array ('name' => 'a***29','winnum' => 11453,'game' => '爱丽娜','city' =>'天津市'),
    array ('name' => 'q***55 ','winnum' => 12365,'game' => '爱丽娜','city' =>'北京市'),
    array ('name' => 'q***55 ','winnum' => 18591,'game' => '爱丽娜','city' =>'广东省'),
    array ('name' => 'cl****1','winnum' => 35791,'game' =>'上海百乐门','city' =>'湖南省'),
    array ('name' => 'sf*****3','winnum' => 235874,'game' =>'开心农场','city' =>'浙江省'),
    array ('name' => 'pp***0','winnum' => 212645,'game' =>'开心农场','city' =>'北京市'),
    array ('name' => 'qe******5','winnum' => 156165,'game' => '爱丽娜','city' =>'北京市'),
    array ('name' => 'll*****2','winnum' => 12313,'game' => '爱丽娜','city' =>'天津市'),
    array ('name' => 'ww****3','winnum' => 13213,'game' => '爱丽娜','city' =>'天津市'),
    array ('name' => 'yy*****0','winnum' => 33201,'game' =>'上海百乐门','city' =>'广东省'),
    array ('name' => 'ii***9','winnum' => 11103,'game' => '爱丽娜','city' =>'云南省'),
    array ('name' => 'jh****4','winnum' => 95603,'game' =>'足球明星','city' =>'天津市'),
    array ('name' => 'ri****0','winnum' => 85991,'game' =>'杰克高手','city' =>'广东省'),
    array ('name' => 'li***1','winnum' => 38193,'game' =>'上海百乐门','city' =>'天津市'),
    array ('name' => 'cv****3','winnum' => 523681,'game' =>'5卷的驱动器','city' =>'广东省'),
    array ('name' => 'nn****t','winnum' => 15193,'game' => '爱丽娜','city' =>'云南省'),
    array ('name' => 'f3***r','winnum' => 12133,'game' => '爱丽娜','city' =>'天津市'),
    array ('name' => 'g6****1','winnum' => 19819,'game' =>'篮球巨星','city' =>'山东省'),
    array ('name' => 'd1*****5','winnum' => 13163,'game' => '爱丽娜','city' =>'天津市'),
    array ('name' => 'zb****h','winnum' => 11801,'game' => '爱丽娜','city' =>'广东省'),
    array ('name' => 'm7******l','winnum' => 112381,'game' => '爱丽娜','city' =>'重庆市'),
    array ('name' => 'e9*****7','winnum' => 113281,'game' => '爱丽娜','city' =>'湖南省'),
    array ('name' => 'lj****0','winnum' => 13881,'game' => '爱丽娜','city' =>'广东省'),
    array ('name' => 'zlf***66','winnum' => 28431,'game' =>'开心农场','city' =>'重庆市'),
    array ('name' => 'qw*****89','winnum' => 1892,'game' => '爱丽娜','city' =>'江苏省'),
    array ('name' => 'gjj**1 ','winnum' => 5973,'game' =>'5卷的驱动器','city' =>'天津市'),
    array ('name' => 'kk**888 ','winnum' => 31725,'game' =>'上海百乐门','city' =>'北京市'),
    array ('name' => 'w***168 ','winnum' => 16801,'game' => '爱丽娜','city' =>'广东省'),
    array ('name' => '66***gq','winnum' => 2232,'game' =>'开心农场','city' =>'江苏省'),
    array ('name' => 'zhang** ','winnum' => 8627,'game' =>'杰克高手','city' =>'河北省'),
    array ('name' => 'lufei**99','winnum' => 13931,'game' => '爱丽娜','city' =>'重庆市'),
    array ('name' => '130*******6 ','winnum' => 17831,'game' => '爱丽娜','city' =>'广东省'),
    array ('name' => '177****855','winnum' => 28133,'game' =>'开心农场','city' =>'天津市'),
    array ('name' => 'yang**11 ','winnum' => 261791,'game' =>'开心农场','city' =>'广东省'),
    array ('name' => '138****3222','winnum' => 23189,'game' =>'开心农场','city' =>'山东省'),
    array ('name' => 'qin**ong','winnum' => 53689,'game' =>'5卷的驱动器','city' =>'山东省'),
    array ('name' => 'lu***33 ','winnum' => 24355,'game' =>'开心农场','city' =>'北京市'),
    array ('name' => '45567***41','winnum' => 9841,'game' =>'足球明星','city' =>'广东省'),
    array ('name' => '136****7688','winnum' => 3001,'game' =>'上海百乐门','city' =>'重庆市'),
    array ('name' => 'ta**ben ','winnum' => 8743,'game' =>'杰克高手','city' =>'天津市'),
    array ('name' => 'xfc***23','winnum' => 22147,'game' =>'开心农场','city' =>'河北省'),
    array ('name' => 'liuhai**9','winnum' => 26333,'game' =>'开心农场','city' =>'云南省'),
    array ('name' => 'wangpe** ','winnum' => 22143,'game' =>'开心农场','city' =>'重庆市'),
    array ('name' => 'jkl****2 ','winnum' => 92533,'game' =>'足球明星','city' =>'天津市'),
    array ('name' => 'zxc****8','winnum' => 65487,'game' =>'篮球巨星','city' =>'河北省'),
    array ('name' => 'cyl****9','winnum' => 87987,'game' =>'杰克高手','city' =>'重庆市'),
    array ('name' => 'cha****l','winnum' => 59654,'game' =>'5卷的驱动器','city' =>'浙江省'),
    array ('name' => 'mg****88','winnum' => 36954,'game' =>'上海百乐门','city' =>'浙江省'),
    array ('name' => 'qt****aa','winnum' => 85569,'game' =>'杰克高手','city' =>'吉林省'),
    array ('name' => 'mv****31','winnum' => 123569,'game' => '爱丽娜','city' =>'山东省'),
    array ('name' => 'we****87','winnum' => 45222,'game' =>'比基尼派对','city' =>'江苏省'),
    array ('name' => 'rng****1','winnum' => 12985,'game' => '爱丽娜','city' =>'北京市'),
    array ('name' => 'sdf*****5 ','winnum' => 115233,'game' => '爱丽娜','city' =>'天津市'),
    array ('name' => 'lta*****s ','winnum' => 9254,'game' =>'足球明星','city' =>'浙江省'),
    array ('name' => 'Q******41 ','winnum' => 98954,'game' =>'足球明星','city' =>'浙江省'),
    array ('name' => 'FG*****F','winnum' => 21369,'game' =>'开心农场','city' =>'山东省'),
    array ('name' => 'FPX*****d ','winnum' => 15365,'game' => '爱丽娜','city' =>'北京市'),
    array ('name' => 'dio*****a ','winnum' => 46325,'game' =>'比基尼派对','city' =>'北京市'),
    array ('name' => 'mcv******5','winnum' => 10595,'game' => '爱丽娜','city' =>'北京市'),
    array ('name' => 'zxc******m','winnum' => 20649,'game' =>'开心农场','city' =>'山东省'),
    array ('name' => '184******45','winnum' => 9485,'game' =>'足球明星','city' =>'北京市'),
    array ('name' => 'L*******d ','winnum' => 141801,'game' => '爱丽娜','city' =>'广东省'),
    array ('name' => 'R4*******1','winnum' => 48253,'game' =>'比基尼派对','city' =>'天津市'),
    array ('name' => 'zyx9***78 ','winnum' => 81425,'game' =>'杰克高手','city' =>'北京市'),
    array ('name' => 'lw****444 ','winnum' => 1154501,'game' =>'足球明星','city' =>'广东省'),
    array ('name' => 'ww***98 ','winnum' => 193155,'game' => '爱丽娜','city' =>'北京市'),
    array ('name' => 'zy**58 ','winnum' => 410677,'game' =>'比基尼派对','city' =>'河北省'),
    array ('name' => 'zzq***88','winnum' => 525003,'game' =>'杰克高手','city' =>'天津市'),
    array ('name' => 'zxw****67','winnum' => 189599,'game' => '爱丽娜','city' =>'山东省'),
    array ('name' => 'ljl1***19','winnum' => 11895,'game' => '爱丽娜','city' =>'北京市'),
    array ('name' => 'W1****8 ','winnum' => 313825,'game' =>'上海百乐门','city' =>'北京市'),
    array ('name' => 'wa****88 ','winnum' => 83033,'game' =>'杰克高手','city' =>'天津市'),
    array ('name' => 'tt9*****8 ','winnum' => 9130163,'game' =>'足球明星','city' =>'天津市'),
    array ('name' => 'dou*****147 ','winnum' => 3544995,'game' =>'5卷的驱动器','city' =>'北京市'),
    array ('name' => 'dj****777','winnum' => 19405,'game' => '爱丽娜','city' =>'北京市'),
    array ('name' => 'yjj55****88 ','winnum' => 1749993,'game' => '爱丽娜','city' =>'天津市'),
    array ('name' => '131****han','winnum' => 93333,'game' =>'足球明星','city' =>'天津市'),
    array ('name' => '123****cheng','winnum' => 4255014,'game' =>'比基尼派对','city' =>'浙江省'),
    array ('name' => 'xrf****6999 ','winnum' => 178195,'game' => '爱丽娜','city' =>'北京市'),
    array ('name' => 'liu1******2525 ','winnum' => 22535,'game' =>'开心农场','city' =>'北京市'),
    array ('name' => 'yl60*****704','winnum' => 448611,'game' =>'比基尼派对','city' =>'广东省'),
    array ('name' => 'taom*****123','winnum' => 12673,'game' => '爱丽娜','city' =>'天津市'),
    array ('name' => 'x192*****196 ','winnum' => 149937,'game' => '爱丽娜','city' =>'河北省'),
    array ('name' => 'q****9 ','winnum' => 19785,'game' => '爱丽娜','city' =>'北京市'),
    array ('name' => 'we******7 ','winnum' => 10805,'game' => '爱丽娜','city' =>'北京市'),
    array ('name' => 'li******g ','winnum' => 481793,'game' =>'比基尼派对','city' =>'天津市'),
    array ('name' => 'z***5 ','winnum' => 80325,'game' =>'杰克高手','city' =>'北京市'),
    array ('name' => 'ch******3 ','winnum' => 79163,'game' =>'水果拉霸','city' =>'天津市'),
    array ('name' => 'ni*******9 ','winnum' => 111475,'game' => '爱丽娜','city' =>'北京市'),
    array ('name' => 'ba******i ','winnum' => 80589,'game' =>'杰克高手','city' =>'山东省'),
    array ('name' => 'ri***8 ','winnum' => 28547,'game' =>'开心农场','city' =>'河北省'),
    array ('name' => 'mi******f ','winnum' => 13587,'game' => '爱丽娜','city' =>'河北省'),
    array ('name' => 'w****8 ','winnum' => 20575,'game' =>'开心农场','city' =>'北京市'),
    array ('name' => '4*****7 ','winnum' => 78493,'game' =>'水果拉霸','city' =>'天津市'),
    array ('name' => 'de****1 ','winnum' => 861185,'game' =>'杰克高手','city' =>'北京市'),
    array ('name' => 'wu***9 ','winnum' => 11609,'game' => '爱丽娜','city' =>'山东省'),
    array ('name' => 'yo*****6 ','winnum' => 117801,'game' => '爱丽娜','city' =>'广东省'),
    array ('name' => '55***7 ','winnum' => 234071,'game' =>'开心农场','city' =>'广东省'),
    array ('name' => 'ss****4 ','winnum' => 230101,'game' =>'开心农场','city' =>'广东省'),
    array ('name' => 'a***9 ','winnum' => 83543,'game' =>'杰克高手','city' =>'天津市'),
    array ('name' => '88*******4 ','winnum' => 112155,'game' => '爱丽娜','city' =>'北京市'),
    array ('name' => 'xa****z ','winnum' => 175601,'game' => '爱丽娜','city' =>'广东省'),
    array ('name' => 'za*****8 ','winnum' => 848701,'game' =>'杰克高手','city' =>'广东省'),
    array ('name' => 'wq***1','winnum' => 8982,'game' =>'杰克高手','city' =>'江苏省'),
    array ('name' => 'sa**6','winnum' => 825,'game' =>'杰克高手','city' =>'北京市'),
    array ('name' => 'sc****1 ','winnum' => 26365,'game' =>'开心农场','city' =>'北京市'),
    array ('name' => 'fh****3 ','winnum' => 5315,'game' =>'5卷的驱动器','city' =>'北京市'),
    array ('name' => 'cv****y','winnum' => 84333,'game' =>'杰克高手','city' =>'天津市'),
    array ('name' => 'we****7','winnum' => 38215,'game' =>'上海百乐门','city' =>'北京市'),
    array ('name' => 'sd****4 ','winnum' => 8487,'game' =>'杰克高手','city' =>'河北省'),
    array ('name' => 'as****2','winnum' => 310253,'game' =>'上海百乐门','city' =>'天津市'),
    array ('name' => 'uj****6 ','winnum' => 22325,'game' =>'开心农场','city' =>'北京市'),
    array ('name' => 'zx****4 ','winnum' => 32215,'game' =>'上海百乐门','city' =>'北京市'),
    array ('name' => 'lk****8 ','winnum' => 52352,'game' =>'5卷的驱动器','city' =>'江苏省'),
    array ('name' => 'sk****6','winnum' => 3324,'game' =>'上海百乐门','city' =>'浙江省'),
    array ('name' => 'vn****8 ','winnum' => 66593,'game' =>'篮球巨星','city' =>'天津市'),
    array ('name' => 'lj****6 ','winnum' => 32321,'game' =>'上海百乐门','city' =>'广东省'),
    array ('name' => 'lo****i ','winnum' => 10225,'game' => '爱丽娜','city' =>'北京市'),
    array ('name' => 'wk****1 ','winnum' => 58295,'game' =>'5卷的驱动器','city' =>'北京市'),
    array ('name' => 'lu****3','winnum' => 265213,'game' =>'开心农场','city' =>'天津市'),
    array ('name' => 'po****2','winnum' => 53302,'game' =>'5卷的驱动器','city' =>'江苏省'),
    array ('name' => 'ly****8 ','winnum' => 8385,'game' =>'杰克高手','city' =>'北京市'),
    array ('name' => 'oo****3','winnum' => 85983,'game' =>'杰克高手','city' =>'天津市'),
    array ('name' => 'gghi***88','winnum' => 90054,'game' =>'足球明星','city' =>'浙江省'),
    array ('name' => 'dwe****878 ','winnum' => 23563,'game' =>'开心农场','city' =>'天津市'),
    array ('name' => 'ggd***ada','winnum' => 15565,'game' => '爱丽娜','city' =>'北京市'),
    array ('name' => 'uu****fd8','winnum' => 3512,'game' =>'上海百乐门','city' =>'江苏省'),
    array ('name' => 'eua***dvv','winnum' => 12502,'game' => '爱丽娜','city' =>'江苏省'),
    array ('name' => 'da***8ds','winnum' => 45644,'game' =>'比基尼派对','city' =>'浙江省'),
    array ('name' => 'hdad***99','winnum' => 3054,'game' =>'上海百乐门','city' =>'浙江省'),
    array ('name' => 'jjd***da','winnum' => 2232,'game' =>'开心农场','city' =>'江苏省'),
    array ('name' => 'da***u2311 ','winnum' => 1354,'game' => '爱丽娜','city' =>'浙江省'),
    array ('name' => 'aa***2','winnum' => 986551,'game' =>'足球明星','city' =>'吉林省'),
    array ('name' => 'ws***k ','winnum' => 48221,'game' =>'比基尼派对','city' =>'广东省'),
    array ('name' => '15****6 ','winnum' => 175633,'game' => '爱丽娜','city' =>'天津市'),
    array ('name' => 'sd***o','winnum' => 18651,'game' => '爱丽娜','city' =>'广东省'),
    array ('name' => 'pf***60','winnum' => 5241,'game' =>'5卷的驱动器','city' =>'吉林省'),
    array ('name' => 'jk**l','winnum' => 67961,'game' =>'篮球巨星','city' =>'广东省'),
    array ('name' => 'yk****58','winnum' => 76131,'game' =>'水果拉霸','city' =>'广东省'),
    array ('name' => 'ty**0','winnum' => 8125,'game' =>'杰克高手','city' =>'北京市'),
    array ('name' => 'qq*****99','winnum' => 4381,'game' =>'比基尼派对','city' =>'广东省'),
    array ('name' => '13***a','winnum' => 12474,'game' => '爱丽娜','city' =>'浙江省'),
    array ('name' => 'as****4','winnum' => 384,'game' =>'上海百乐门','city' =>'浙江省'),
    array ('name' => 'qw*****e','winnum' => 335,'game' =>'上海百乐门','city' =>'北京市'),
    array ('name' => 'ew*****a','winnum' => 293,'game' =>'开心农场','city' =>'天津市'),
    array ('name' => 'd8****8','winnum' => 253,'game' =>'开心农场','city' =>'天津市'),
    array ('name' => 'qw****5','winnum' => 201,'game' =>'开心农场','city' =>'广东省'),
    array ('name' => 'we*****c','winnum' => 173,'game' => '爱丽娜','city' =>'天津市'),
    array ('name' => 'qw***2','winnum' => 163,'game' => '爱丽娜','city' =>'吉林省'),
    array ('name' => 'wq*****3','winnum' => 143,'game' => '爱丽娜','city' =>'四川省'),
    array ('name' => 'zx***2','winnum' => 113,'game' => '爱丽娜','city' =>'天津市'),
    array ('name' => 'ad****3','winnum' => 103,'game' => '爱丽娜','city' =>'天津市'),
    array ('name' => 'ds***g','winnum' => 95,'game' =>'足球明星','city' =>'四川省'),
    array ('name' => 'sa****1','winnum' => 85,'game' =>'杰克高手','city' =>'北京市'),
    array ('name' => 'sa***j','winnum' => 79,'game' =>'水果拉霸','city' =>'山东省'),
    array ('name' => 'sd****4','winnum' => 73,'game' =>'水果拉霸','city' =>'天津市'),
    array ('name' => 'ds****2','winnum' => 71,'game' =>'水果拉霸','city' =>'广东省'),
    array ('name' => 'ew*****6','winnum' => 65,'game' =>'篮球巨星','city' =>'北京市'),
    array ('name' => 'qw**e','winnum' => 51,'game' =>'5卷的驱动器','city' =>'四川省'),
    array ('name' => 'ez*****w ','winnum' => 31,'game' =>'上海百乐门','city' =>'广东省'),
    array ('name' => 'ds**n','winnum' => 21,'game' =>'开心农场','city' =>'广东省'),
    array ('name' => 'zz****1','winnum' => 15,'game' => '爱丽娜','city' =>'北京市'),
    array ('name' => '52****x','winnum' => 1117,'game' => '爱丽娜','city' =>'河北省'),
    array ('name' => 'ch******1','winnum' => 3005,'game' =>'上海百乐门','city' =>'北京市'),
    array ('name' => 'tg***5','winnum' => 401,'game' =>'比基尼派对','city' =>'广东省'),
    array ('name' => 'cp***0','winnum' => 187,'game' => '爱丽娜','city' =>'河北省'),
    array ('name' => '25***u','winnum' => 14953,'game' => '爱丽娜','city' =>'天津市'),
    array ('name' => 'cz***3','winnum' => 2562,'game' =>'开心农场','city' =>'江苏省'),
    array ('name' => 'aa******1','winnum' => 497,'game' =>'比基尼派对','city' =>'河北省'),
    array ('name' => 'L***0','winnum' => 2833,'game' =>'开心农场','city' =>'天津市'),
    array ('name' => 'dh**8','winnum' => 3027,'game' =>'上海百乐门','city' =>'河北省'),
    array ('name' => 'zj***7','winnum' => 5003,'game' =>'5卷的驱动器','city' =>'天津市'),
    array ('name' => 'wa***9','winnum' => 23569,'game' =>'开心农场','city' =>'山东省'),
    array ('name' => 'er***6','winnum' => 4575,'game' =>'比基尼派对','city' =>'北京市'),
    array ('name' => 'xc*****6','winnum' => 7965,'game' =>'水果拉霸','city' =>'北京市'),
    array ('name' => 'ww****5','winnum' => 13194,'game' => '爱丽娜','city' =>'浙江省'),
    array ('name' => 'pr*****2','winnum' => 6065,'game' =>'篮球巨星','city' =>'北京市'),
    array ('name' => 'ow***9','winnum' => 39024,'game' =>'上海百乐门','city' =>'浙江省'),
    array ('name' => 'as******4','winnum' => 702,'game' =>'水果拉霸','city' =>'江苏省'),
    array ('name' => 'h1*****a','winnum' => 933,'game' =>'足球明星','city' =>'天津市'),
    array ('name' => 'gx***6','winnum' => 505,'game' =>'5卷的驱动器','city' =>'北京市'),
    array ('name' => 'xd*****sd12','winnum' => 32584,'game' =>'上海百乐门','city' =>'浙江省'),
    array ('name' => 'kl***56','winnum' => 52321,'game' =>'5卷的驱动器','city' =>'广东省'),
    array ('name' => 'etl***kf ','winnum' => 6345,'game' =>'篮球巨星','city' =>'北京市'),
    array ('name' => 'ui***da55','winnum' => 80053,'game' =>'杰克高手','city' =>'天津市'),
    array ('name' => 'vb****tt ','winnum' => 603,'game' =>'篮球巨星','city' =>'天津市'),
    array ('name' => 'iL***E','winnum' => 253,'game' =>'开心农场','city' =>'天津市'),
    array ('name' => 'r****oc','winnum' => 10897,'game' => '爱丽娜','city' =>'河北省'),
    array ('name' => 'oG***n','winnum' => 6402,'game' =>'篮球巨星','city' =>'江苏省'),
    array ('name' => 'a***cU','winnum' => 3245,'game' =>'上海百乐门','city' =>'北京市'),
    array ('name' => 'u***a ','winnum' => 12551,'game' => '爱丽娜','city' =>'重庆市'),
    array ('name' => 'dd***45','winnum' => 231,'game' =>'开心农场','city' =>'广东省'),
    array ('name' => 'dahi***88','winnum' => 90054,'game' =>'足球明星','city' =>'浙江省'),
    array ('name' => 'dwe****878 ','winnum' => 2353,'game' =>'开心农场','city' =>'天津市'),
    array ('name' => 'das***ada','winnum' => 155659,'game' => '爱丽娜','city' =>'山东省'),
    array ('name' => 'uu****898','winnum' => 3512,'game' =>'上海百乐门','city' =>'江苏省'),
    array ('name' => 'esda***dvv ','winnum' => 12502,'game' => '爱丽娜','city' =>'江苏省'),
    array ('name' => 'da***888 ','winnum' => 45644,'game' =>'比基尼派对','city' =>'浙江省'),
    array ('name' => 'hee***a','winnum' => 3054,'game' =>'上海百乐门','city' =>'浙江省'),
    array ('name' => 'dasd***da','winnum' => 22323,'game' =>'开心农场','city' =>'天津市'),
    array ('name' => 'eq***ue11','winnum' => 1354,'game' => '爱丽娜','city' =>'浙江省'),
    array ('name' => 'w*****8','winnum' => 2561,'game' =>'开心农场','city' =>'广东省'),
    array ('name' => 'l****0','winnum' => 32365,'game' =>'上海百乐门','city' =>'北京市'),
    array ('name' => '5****9','winnum' => 8253,'game' =>'杰克高手','city' =>'天津市'),
    array ('name' => 'm****8','winnum' => 725,'game' =>'水果拉霸','city' =>'北京市'),
    array ('name' => 'g*****7','winnum' => 26905,'game' =>'开心农场','city' =>'北京市'),
    array ('name' => 'p*****5','winnum' => 15897,'game' => '爱丽娜','city' =>'河北省'),
    array ('name' => '8******7 ','winnum' => 6523,'game' =>'篮球巨星','city' =>'天津市'),
    array ('name' => '0*****6','winnum' => 8971,'game' =>'杰克高手','city' =>'广东省'),
    array ('name' => '8****0','winnum' => 2581,'game' =>'开心农场','city' =>'重庆市'),
    array ('name' => '7****6','winnum' => 24005,'game' =>'开心农场','city' =>'北京市'),
    array ('name' => '8****0','winnum' => 56983,'game' =>'5卷的驱动器','city' =>'天津市'),
    array ('name' => '0****0','winnum' => 5803,'game' =>'5卷的驱动器','city' =>'天津市'),
    array ('name' => '9****1','winnum' => 1251,'game' => '爱丽娜','city' =>'广东省'),
    array ('name' => '5*****8','winnum' => 7985,'game' =>'水果拉霸','city' =>'北京市'),
    array ('name' => '4****5','winnum' => 74651,'game' =>'水果拉霸','city' =>'广东省'),
    array ('name' => '7****3','winnum' => 4069,'game' =>'比基尼派对','city' =>'山东省'),
    array ('name' => '8****9','winnum' => 9854,'game' =>'足球明星','city' =>'浙江省'),
    array ('name' => 'f****9','winnum' => 5805,'game' =>'5卷的驱动器','city' =>'北京市'),
    array ('name' => '5****6','winnum' => 158007,'game' => '爱丽娜','city' =>'河北省'),
    array ('name' => '9****4','winnum' => 26905,'game' =>'开心农场','city' =>'北京市'),
    array ('name' => 'ab*****9','winnum' => 2893,'game' =>'开心农场','city' =>'天津市'),
    array ('name' => 'as**5','winnum' => 1369,'game' => '爱丽娜','city' =>'山东省'),
    array ('name' => 'me***6','winnum' => 5982,'game' =>'5卷的驱动器','city' =>'江苏省'),
    array ('name' => 'ni****1','winnum' => 10233,'game' => '爱丽娜','city' =>'天津市'),
    array ('name' => 'yi****9','winnum' => 1255,'game' => '爱丽娜','city' =>'北京市'),
    array ('name' => 'ty***1','winnum' => 2089753,'game' =>'开心农场','city' =>'天津市'),
    array ('name' => 'xu****8','winnum' => 4582,'game' =>'比基尼派对','city' =>'江苏省'),
    array ('name' => 'i**2','winnum' => 11933,'game' => '爱丽娜','city' =>'天津市'),
    array ('name' => 'fa****7','winnum' => 53691,'game' =>'5卷的驱动器','city' =>'广东省'),
    array ('name' => 'lk***3','winnum' => 3689,'game' =>'上海百乐门','city' =>'山东省'),
    array ('name' => 'mj***5','winnum' => 59802,'game' =>'5卷的驱动器','city' =>'江苏省'),
    array ('name' => 'gh***4','winnum' => 3012,'game' =>'上海百乐门','city' =>'江苏省'),
    array ('name' => 'de***8','winnum' => 7652,'game' =>'水果拉霸','city' =>'江苏省'),
    array ('name' => 'fr****8','winnum' => 65235,'game' =>'篮球巨星','city' =>'北京市'),
    array ('name' => 'we**7','winnum' => 103685,'game' => '爱丽娜','city' =>'北京市'),
    array ('name' => 'qo***5','winnum' => 32189,'game' =>'上海百乐门','city' =>'山东省'),
    array ('name' => 'mh*****0','winnum' => 532,'game' =>'5卷的驱动器','city' =>'江苏省'),
    array ('name' => 'bv***2','winnum' => 8652,'game' =>'杰克高手','city' =>'江苏省'),
    array ('name' => 'nh****8','winnum' => 23503,'game' =>'开心农场','city' =>'天津市'),
    array ('name' => 'fj****5','winnum' => 68905,'game' =>'篮球巨星','city' =>'北京市'),
);

shuffle($game_arr); // 打乱数组
$re_data = array(
    'date' => $yesday,
    'data' => array_slice($game_arr,1,50),
);

$game_af_str = json_encode($re_data);


?>

<style>
    .w_960{width: 960px;}
    .game_bg{background: url(images/game/game_bg_1.png) no-repeat;padding-top: 30px;background-size: 100%;}
    .jackport {width: 480px;margin: 22px 0;text-align: center;}
    .jackport .t_num i{width: 38px;height: 52px;display:inline-block;background:url(images/game/number_1.png) no-repeat;background-position:0 0;}
    .jackport .t_num i:nth-child(12){display:none}
    .jackport .t_num span{color:#fff;font-size:20px;font-weight:800;margin: 0 -5px;}
    .jackpot{position:relative}
    .jackpot>div{display: inline-block;position: relative;}
    .jackpot .jackpot_left span,.jackpot .jackpot_right span{display: inline-block;position: absolute;}

    .qgx{height:150px;width:2px;display:inline-block}
    .winning_name{border-bottom: 1px solid #fff;}
    .winning_name span{margin-right:20px}
    .winning_name span.cs{color:#ffed0c}
    .slots .searchBar{padding:15px 30px}
    .slots .searchBar h3{width:144px;height:70px;line-height:70px;font-size:18px;color:#fff;text-align:center;font-weight:700}
    .slots .searchBar .inputBox{width:200px;height:37px;border-radius:5px;border:1px solid #ccc;position:relative}
    .slots .searchBar .inputBox .searchInput{height:30px;line-height:30px;background:none;border:none;margin:4px 0 0 0;padding-left:5px}
    .slots .searchBar .advance .typeOfGame .cbox-row .cbox-label,.slots .searchBar .advance .typeOfGame .cbox-row a,.slots .searchBar .advance .typeOfGame .cbox-row span,.slots .searchBar .btnBox,.slots .searchBar .btnBox .btn1,.slots .searchBar .keywordsBox,.slots .searchBar .keywordsBox h4,.slots .searchBar .keywordsBox ul,.slots .searchBar .keywordsBox ul li,.slots .searchBar h3,.slots .slotsGame .award span,.slots .slotsGame .gameBox,.slots .slotsGame .king .ico,.slots .slotsGame .star .ico,.slots .topList .part.textPart .likeList ul li .likeIco,.slots .topList .part.textPart .likeList ul li p,.slots .topList .part.textPart .likeList ul li span,.fl{float:left;*display:inline}
    .slots .searchBar .inputBox .ico{position:absolute;width:45px;height:37px;background:url(images/game/ss.png) #F5F7FA center no-repeat;cursor:pointer;transition:all .5s ease;top:0px;right:0;border-radius:0 5px 5px 0}
    .game_title_bottom{margin-top:15px;padding:30px 0 15px;width:230px;position:absolute;right:20px;top:50px;text-align:center}
    .game_title_bottom .game_yxfl a{position:relative;display:inline-block;color:#4c4b4b;padding:5px 0 5px 40px;font-size:12px;margin-right:25px}
    .game_title_bottom .game_yxfl a.active{color:#FFA51F}
/*    .game_title_bottom .game_yxfl a:before{position:absolute;content:'';display:inline-block;width:26px;height:30px;background:url(images/game/tip_icon.png) no-repeat;background-position:-31px 2px;transition:all 0.2s ease-in-out;left:5px;top:-5px}
    .game_title_bottom .game_yxfl a:nth-child(2):before{background-position:-169px 0px}
    .game_title_bottom .game_yxfl a:last-child:before{background-position:-97px 0px}
    .game_title_bottom .game_yxfl a.active:before{background-position-y:-35px}*/
    .game_choose{border-bottom:1px solid #FFA51F;padding-bottom:10px}
    .game_top{height:145px;overflow:hidden;margin-top:20px}
    .game_top>div{border-radius:10px}
    .game_top .title{border-bottom:1px solid hsla(0,0%,98%,.5);text-align:center;line-height:40px;margin:0 10px}
    .jjb_content_all{width:405px;height:100px;overflow:hidden;padding:0 8px}
    .jjb_content_all .jjb_content{position:relative;white-space:nowrap;-webkit-animation:movestop 8s linear infinite;animation:movestop 8s linear infinite}
    .winning_name{line-height:35px}
    .jjb_content_all .pause{animation-play-state:paused}
    .game_choose ul{margin-left:10px}
    .game_choose ul li.mw_li{padding-left:45px}
    .game_choose ul li.cq_li{padding-left:50px}
    .game_choose ul li{transition:.3s;position:relative;cursor:pointer;float:left;width:150px;height:48px;background:#fff;color:#FFA51F;margin:0 10px;border-radius:50px;text-align:center;line-height:48px;font-size:18px;box-shadow:0px 0px 0px 1px #eae5e5;padding-left:30px;border:1px solid #FFA51F}
    .game_choose ul li.active{background: #fa9602;background: linear-gradient(90deg,#FEA219,#d9813d);color:#fff}
    .game_choose ul li:before{position:absolute;content:'';display:inline-block;width:55px;height:40px;margin:4px 8px;left:5px;background:url(images/game/title_icon_1.png) no-repeat;background-position: -11px -3px;}
    .game_choose ul li.active:before{background-position-y: -40px !important;}
    .game_choose ul li.mg_li:before{background-position:-85px -3px}
    .game_choose ul li.mw_li:before{width: 60px;background-position:-248px -3px}
    .game_choose ul li.cq_li:before{width: 65px;background-position:-162px -3px}
    .game_choose ul li.fg_li:before{background-position:-328px -3px}
    .slots .slotsGame{min-height:360px}
    .slots .hot-game-list{overflow:hidden;padding:0 10px}
    .slots .slotsGame .gameBox{position:relative;width:165px;border-radius:5px;transition:all .5s ease;margin:0 10px 20px;height:194px;border: 1px solid #fff}
    .slots .slotsGame .gameBox:hover{border: 1px solid #FE9E10;}
    .slots .slotsGame .gameBox .imgBox img{width:100%;height:100%}
    .slots .slotsGame .gameBox .gameName{width:100%;height:46px}
    .slots .searchBar,.slots .slotsGame .gameBox .gameName,.slots .slotsGame .gameBox .imgBox,.slots .slotsTitle,.slots .topList,.slots .topList .part.textPart .likeList,.slots .topList .part h2,.slots .typeOfGame,.pr{position:relative}
    .slots .slotsGame .gameName h3{text-align:center;width:90%;height:45px;line-height:45px;font-size:12px;font-weight:400;color:#333;z-index:1;padding-left:5%}
    .slots .slotsGame .gameBox .imgBox{width:165px;height:149px;border-radius:5px 5px 0 0;overflow:hidden}
    .hr{width:100%;height:1px;border-bottom:1px dashed #fff}
    .slots .slotsGame .hr{width:165px;height:1px;margin:0}
    .slots .slotsGame .btn1{display:block;width:60%;height:35px;line-height:35px;font-size:14px;color:#fff;text-align:center;background:linear-gradient(to bottom,#f3af1a,#ffcb59);border-radius:3px;margin:15px auto;transition:.3s}
    .slots .slotsGame .btn1:first-child{margin-top:20px}
    .slots .slotsGame .btn1.btn_mg:first-child{margin-top:47px}
    .slots .slotsGame .btn1:hover{opacity:.9}
    .slots .slotsGame .gameBox .mask{position:absolute;width:100%;height:149px;left:0px;bottom:45px;z-index:1;transition:all 0.3s;background:rgba(0,0,0,.4)}
    .gameList_all{background:#fff;box-shadow: 0px 2px 10px 0px rgba(0, 0, 0, .1);border-radius: 15px 15px 0 0;}

    @keyframes leftToCenter {
        0% {opacity: .2;transform:  translate(-400px, 0);}
        100% {opacity: 1;transform: translate(0, 0);}
    }
    @keyframes rightToCenter {
        0% {opacity: .2;transform:  translate(400px, 0);}
        100% {opacity: 1;transform: translate(0, 0);}
    }

    @keyframes movestop{
        0%{top:0%}
        100%{top:-100%}
    }
    @-webkit-keyframes movestop{
        0%{top:0%}
        100%{top:-100%}
    }

</style>

<div class="game_bg">
    <div class="slots">

        <div class="gameList_all w_1200">

            <div class="searchBar">
                <div class="game_choose">
                    <ul>
                        <!--<li class="game_active">热门游戏</li>
                        <li>漫威热门系列</li>-->
                        <li class="ag_li <?php echo ($gametype=='ag' or $gametype=='')?'active':''; ?>" data-gametype="ag">AG电子</li>
                        <li class="mg_li <?php echo $gametype=='mg'?'active':''; ?>" data-gametype="mg">MG电子</li>
                        <li class="mw_li <?php echo $gametype=='mw'?'active':''; ?>" data-gametype="mw">MW电子</li>
			            <li class="cq_li <?php echo $gametype=='cq'?'active':''; ?>" data-gametype="cq">CQ9电子</li>
			            <li class="fg_li <?php echo $gametype=='fg'?'active':''; ?>" data-gametype="fg">FG电子</li>
                        <!--<li>电影老虎机</li>
                        <li>纸牌游戏</li>-->
                    </ul>
                    <div style="clear: both"></div>
                </div>
                <div class="w_960 game_top" style="width: 910px;">
                    <div class="left them_bg_color_gradient">
                        <div class="title">累计奖池</div>
                        <!-- 奖池 -->
                        <div class="jackport">
                        <span class="t_num t_num1">
                            <i style="background-position: 0px -208px;"></i>
                            <i style="background-position: 0px 0px;"></i>
                            <span>,</span>
                            <i style="background-position: 0px -417px;"></i>
                            <i style="background-position: 0px -53px;"></i>
                            <i style="background-position: 0px -208px;"></i>
                            <span>,</span>
                            <i style="background-position: 0px -53px;"></i>
                            <i style="background-position: 0px -260px;"></i>
                            <i style="background-position: 0px -105px;"></i>
                           <span>.</span>
                            <i style="background-position: 0px -174px;"></i>
                            <i style="background-position: 0px -261px;"></i>
                            <i style="background-position: 0px -53px;"></i>

                        </span>
                        </div>
                    </div>
                    <div class="right them_bg_color_gradient">
                        <div class="title">中奖记录</div>
                        <div class="jjb_content_all">
                            <div class="jjb_content " > <!-- movetop -->

                            </div>
                        </div>
                    </div>
                </div>

                <div class="game_title_bottom">
                    <div class="inputBox fr">
                        <label>
                            <input type="text" class="seachgame_input searchInput form-control search-game search-inpt tags" placeholder="搜索游戏">
                        </label>
                        <div class="submit-btn ico"></div>
                    </div>
                    <div class="fl game_yxfl">
                        <a href="javascript:;" class="active" data-type="all">全部游戏</a>
                        <a href="javascript:;" data-type="rm">热门游戏</a>
                        <a href="javascript:;" data-type="new">最新游戏</a>
                    </div>

                    <div style="clear: both"></div>
                </div>

            </div>
            <!--                游戏-->
            <div class="w_960 slotsGame" id="gameSearch11">
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

        </div>
    </div>
</div>


<script type="text/javascript">
    $(function () {

        clearInterval(gameJackPort);
        // var uid = '<?php echo $uid;?>' ;
        var fr_gametype = '<?php echo $gametype;?>' ;
        var test_username = '<?php echo $test_username;?>';

        var game_af_str = '<?php echo $game_af_str;?>'; // 游戏奖金
        game_af_str = $.parseJSON(game_af_str);

        indexCommonObj.getUserQpBanlance(uid,'ag') ;

        var sumarr = [61813152.31,41313552.16,72315192.25,41135157.71,42513151.75,52113152.51,47115112.75,41735117.41,63131117.90,42137110.81];
        var sum = sumarr[parseInt(Math.random()*10)];
        gameJackPort = setInterval(function(){
            show_num1(sum)
        },1500);
        // jackport 数字
        function show_num1(n) {
            //console.log(n);
            sum = Number(sum)+1.31;
            sum = Math.round(sum*100)/100 ;

            var it = $(".t_num1 i");
            var len = String(n).length;
            for(var i = 0; i < len; i++) {
                if(it.length <= i) {
                    $(".t_num1").append("<i class='no'></i>");
                }
                var num = String(n).charAt(i);
                //根据数字图片的高度设置相应的值
                var y = -parseInt(num) * 52;
                var obj = $(".t_num1 i").eq(i);
                obj.animate({
                    backgroundPosition: '(0 ' + String(y) + 'px)'
                }, 'slow', 'swing', function() {});
            }

        }
        var gameSwiper = ''; // 轮播
        var count = 24; // 每页展示数量
        var page_tt = 0; // 初始页码
        var game_type_c ='ag' ; // 默认游戏类型
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
                    if(game_type_c=='ag'  || game_type_c=='cq') { // ag cq
		            if(game_type_c=='cq'){
                                realurl = '../../app/member/cq9/cq9_api.php?action=getLaunchGameUrl&game_id='+game_list[j].gameid ;
                                tryurl = 'https://demo.cqgame.games/';
                            }
			    
                        gstr += '<img alt="" src="'+  gamelist[j].gameurl +'">' +
                            '</div>' +
                            '                        <div class="gameName"><h3>'+ gamelist[j].name  +'</h3>' +
                            '                            <div class="hr"></div>' +
                            '  </div>' ;
                        gstr += '<div class="mask hide">'+
                            '                            <a href="javascript:;" class="btn1 purple" onclick="indexCommonObj.openGameCommon(this,\''+uid+'\',\''+ tryurl +'\')">免费试用</a>' +
                            '                            <a href="javascript:;" class="btn1 purple" onclick="indexCommonObj.openGameCommon(this,\''+uid+'\',\''+ realurl +'\')" >开始游戏</a>'+
                           '</div>';
                    }else if(game_type_c=='mg'){ // mg
                        realurl = '../../app/member/mg/mg_api.php?action=getLaunchGameUrl&game_id='+gamelist[j].gameid ;
                        tryurl = '../../app/member/mg/mg_api.php?action=getDemoLaunchGameUrl&game_id='+gamelist[j].gameid ;
                        gstr += '<span class="mg_img" style="background: url(images/game/mg/more/'+gamelist[j].gameurl +') center no-repeat;background-size: 86%;display: block;height: 130px;margin: 0 auto;" ></span>' +
                            '</div>' +
                            '                        <div class="gameName"><h3>'+ gamelist[j].name  +'</h3>' +
                            '                            <div class="hr"></div>' +
                            '  </div>' ;
                        gstr += '<div class="mask hide">'+
                            ' <a href="javascript:;" class="btn1 purple btn_mg" onclick="indexCommonObj.openGameCommon(this,\''+uid+'\',\''+ realurl +'\')" >开始游戏</a>'+
                            '</div>';
                    }else if(game_type_c=='mw'){ // mw
                        realurl = '../../app/member/mw/mw_api.php?action=gameLobby&gameId='+gamelist[j].gameId ;
                        gstr += '<img src="images/game/mw/'+gamelist[j].gameIcon +'" >' +
                            '</div>' +
                            '                        <div class="gameName"><h3>'+ gamelist[j].gameName  +'</h3>' +
                            '                            <div class="hr"></div>' +
                            '  </div>' ;
                        gstr += '<div class="mask hide">'+
                            ' <a href="javascript:;" class="btn1 purple btn_mg" onclick="indexCommonObj.openGameCommon(this,\''+uid+'\',\''+ realurl +'\')" >开始游戏</a>'+
                            '</div>';
                    }else if(game_type_c=='fg'){ // fg
                        realurl = '../../app/member/fg/fg_api.php?action=getLaunchGameUrl&game_id='+gamelist[j].gameId ;
                        tryurl = '../../app/member/fg/fg_api.php?action=getDemoLaunchGameUrl&game_id='+gamelist[j].gameId ;

                        gstr += '<img src="images/game/fg/'+gamelist[j].gameIcon +'" >' +
                            '</div>' +
                            '                        <div class="gameName"><h3>'+ gamelist[j].gameName  +'</h3>' +
                            '                            <div class="hr"></div>' +
                            '  </div>' ;
                        gstr += '<div class="mask hide">'+
                            ' <a href="javascript:;" class="btn1 purple btn_mg" onclick="indexCommonObj.openGameCommon(this,\''+uid+'\',\''+ tryurl +'\')" >免费试用</a>'+
                            ' <a href="javascript:;" class="btn1 purple btn_mg" onclick="indexCommonObj.openGameCommon(this,\''+uid+'\',\''+ realurl +'\')" >开始游戏</a>'+
                            '</div>';
                    }

                    gstr += '  </div>' ;

                }

            $('.hot-game-list').html(gstr) ;
            hoverGmae();


        }
        // 推荐游戏
        function recommendGame() {

            var gstr ='' ;
            for (var i = 5 ; i < 20; i++) {
                var realurl = '../../app/member/zrsx/login.php?uid='+uid+'&gameid='+game_list[i].gameid;
                var tryurl = '../../app/member/zrsx/login.php?uid='+uid+'&username='+test_username+'&gameid='+game_list[i].gameid;
                gstr +='<div class="swiper-slide ">' +
                    '                                <div class="game-slide">' ;
                if(game_type_c=='ag' || game_type_c=='cq'){ // ag  cq9
                    if(game_type_c=='cq'){
                       realurl = '../../app/member/cq9/cq9_api.php?action=getLaunchGameUrl&game_id='+game_list[i].gameid ;
                       tryurl = 'https://demo.cqgame.games/';
                    }
                    gstr += '<img class="slide-img" src="'+ game_list[i].gameurl +'" alt="">' ;
                    gstr +='                        <p class="game-slide-tit">'+ game_list[i].name +'</p>' +
                        '                        <div class="mask hide">' +
                        '                            <a class="freeplay" href="javascript:;" onclick="indexCommonObj.openGameCommon(this,\''+uid+'\',\''+ tryurl +'\')" >免费试玩</a>' +
                        '                            <a class="comegame" href="javascript:;" onclick="indexCommonObj.openGameCommon(this,\''+uid+'\',\''+ realurl +'\')" >进入游戏</a>' +
                        '                        </div>' +
                        '                    </div>  '+
                        '</div>';
                }
                else if(game_type_c=='mg'){ // mg
                    realurl = '../../app/member/mg/mg_api.php?action=getLaunchGameUrl&game_id='+game_list[i].gameid ;
                    tryurl = '../../app/member/mg/mg_api.php?action=getDemoLaunchGameUrl&game_id='+game_list[i].gameid ;

                    gstr += '<span class="slide-img" style="background-image: url(images/game/mg/'+game_list[i].gameurl +'.png);" ></span>' ;
                    gstr +='                        <p class="game-slide-tit">'+ game_list[i].name +'</p>' +
                        '                        <div class="mask hide">' +
                        '                            <a class="comegame mg_comegame" href="javascript:;" onclick="indexCommonObj.openGameCommon(this,\''+uid+'\',\''+ realurl +'\')" >进入游戏</a>' +
                        '                        </div>' +
                        '                    </div>  '+
                        '</div>';
                }
                else if(game_type_c=='mw'){ // mw
                    // console.log(game_list)
                    realurl = '../../app/member/mw/mw_api.php?action=gameLobby&gameId='+game_list[i].gameId ;

                    gstr += '<img class="slide-img" src="images/game/mw/'+game_list[i].gameIcon +'" alt="">' ;
                    gstr +='                        <p class="game-slide-tit">'+ game_list[i].gameName +'</p>' +
                        '                        <div class="mask hide">' +
                        '                            <a class="comegame mg_comegame" href="javascript:;" onclick="indexCommonObj.openGameCommon(this,\''+uid+'\',\''+ realurl +'\')" >进入游戏</a>' +
                        '                        </div>' +
                        '                    </div>'+
                        '</div>';
                }

            }

            $('.recommend_Game').html(gstr) ;
            hoverGmae();
           // console.log(gameSwiper)
            if(!gameSwiper){
                gameSwiper = new Swiper('.swiper-container',{
                    autoplay : 2500, // 自动滚动
                    slidesPerView : 8,
                    spaceBetween : 10, // 图片间隔
                    speed:500,
                    loop : true ,
                    prevButton:'.swiper-button-prev',
                    nextButton:'.swiper-button-next',
                    autoplayDisableOnInteraction : false, // 点击切换后是否自动播放 (默认true 不播放)
                    //spaceBetween : '10%',按container的百分比
                })
            }else{
                gameSwiper.slideTo(0);
                gameSwiper.update();
                gameSwiper.reLoop();
            }


        }

        // 鼠标hover 游戏
        function hoverGmae() {
            $('.gameBox').each(function () {
                $(this).hover(function(){
                    $(this).find('.mask').removeClass('hide')
                }, function(){
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
                    } else{
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
                $('.game_yxfl a').removeClass('active').eq(0).addClass('active');
                var gametype = $(this).attr('data-gametype');
                game_type_c = gametype ;
                $(this).addClass('active').siblings('li').removeClass('active');
                int_page(1);
                setPageCount();
                // recommendGame();

            })
        }

        // 游戏筛选分类
        function changeGameType(){
            $('.game_yxfl').find('a').on('click',function () {
                var gametype = $(this).attr('data-type');
                $(this).addClass('active').siblings().removeClass('active');
                var choose_game_list = new Array();
                if(gametype == 'all'){
                    choose_game_list =  game_list;
                }else{
                    $.each(game_list,function (i,v) {
                        // console.log(i%2)
                        if(gametype == 'rm'){ // 热门游戏
                            if(i%2==0 && i<30){
                                // console.log(v.name)
                                if (game_type_c=='mw' || game_type_c=='fg'){
                                    choose_game_list.push(
                                        {
                                            gameName: v.gameName ,
                                            gameIcon: v.gameIcon,
                                            gameId: v.gameId,
                                        }
                                    )
                                }else{
                                    choose_game_list.push(
                                        {
                                            name: v.name ,
                                            gameurl: v.gameurl,
                                            gameid: v.gameid,
                                        }
                                    )
                                }
                            }
                        }else if(gametype == 'new'){ // 最新游戏
                            if(i%2==1 && i>20){
                                // console.log(v.name)
                                if (game_type_c=='mw' || game_type_c=='fg'){
                                    choose_game_list.push(
                                        {
                                            gameName: v.gameName ,
                                            gameIcon: v.gameIcon,
                                            gameId: v.gameId,
                                        }
                                    )
                                }else{
                                    choose_game_list.push(
                                        {
                                            name: v.name ,
                                            gameurl: v.gameurl,
                                            gameid: v.gameid,
                                        }
                                    )
                                }
                            }
                        }

                    })
                }

               // console.log(choose_game_list)
                int_page(1,choose_game_list);
                setPageCount();

            })
        }

        // 中奖信息
        function getGamePrize(){
            var $jjb_content = $('.jjb_content');
            var str ='';
            for(var k=0;k<game_af_str.data.length;k++){
                str += '<div class="winning_name"><span>来自 '+ game_af_str.data[k].city +'</span> <span class="cs">'+ game_af_str.data[k].name +'</span>在<span class="cs">'+ game_af_str.data[k].game +'</span> 游戏中赢得 <span class="cs">'+ '￥'+game_af_str.data[k].winnum +'</span></div>' ;

            }
            $jjb_content.html(str);

        }
        // 中奖滚动处理
        function gameNewRunning() {
            $('.jjb_content').on('mouseenter','.winning_name',function () {
                $(this).parent('.jjb_content').addClass('pause');
            })
            $('.jjb_content').on('mouseleave','.winning_name',function () {
                $(this).parent('.jjb_content').removeClass('pause');
            })

        }
        gameNewRunning();
        getGamePrize();

        int_page(1);
       // recommendGame();
        setPageCount() ;
        seachGameName();
        //enterSubmitAction();
        changeGameNav();
        changeGameType();

    })
</script>