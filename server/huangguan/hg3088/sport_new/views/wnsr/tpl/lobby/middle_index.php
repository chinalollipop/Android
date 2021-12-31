<?php
session_start();
include "../../../../app/member/include/config.inc.php";
$redisObj = new Ciredis();
$uid = $_SESSION['Oid']; // 判断是否已登录
$yesday = date('Y-m-d',strtotime('-1 day'));

/* 奖池 */
$sumarr = array('22,912,279.31','21,412,279.71','32,710,269.92','23,152,701.10','25,541,691.81','5,341,791.01','29,171,740.51','9,318,095.15','8,016,678.91','19,268,651.60','21,578,711.15','25,171,071.43');
shuffle($sumarr); // 打乱数组

/* 在线人数 */
$sumonline = array('22,919','21,419','32,719','23,151','25,541','15,341','29,170','9,315','18,018','19,261','21,571','25,171');
shuffle($sumonline); // 打乱数组

// 赢家榜
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
    array ('name' => 'yjj55****88 ','winnum' => 1749996,'game' => '爱丽娜'),
    array ('name' => '131****han','winnum' => 93333,'game' =>'足球明星'),
    array ('name' => '123****cheng','winnum' => 4255014,'game' =>'比基尼派对'),
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
    array ('name' => 'ty***1','winnum' => 2089753,'game' =>'开心农场'),
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
    'data' => array_slice($game_arr,1,12),
);
$game_af_str = json_encode($re_data);


?>
<style>
    .camove{position: relative;width: 100%;white-space: nowrap;-webkit-animation:moves 10s linear infinite;-moz-animation:moves 10s linear infinite;-o-animation:moves 10s linear infinite;animation:moves 10s linear infinite}
    @keyframes moves{
        0%{left:0%}
        100%{left:-100%}
    }
    @-moz-keyframes moves{
        0%{left:0%}
        100%{left:-100%}
    }
    @-webkit-keyframes moves{
        0%{left:0%}
        100%{left:-100%}
    }
    @-moz-keyframes moves{
        0%{left:0%}
        100%{left:-100%}
    }
    .slot-machine{margin-bottom:12px;vertical-align:top}
    .slot-tab{position:relative;height:81px;background:#242424;border-top:1px solid #3f3f3f;border-bottom:1px solid #3f3f3f}
    .slot-tab>span{position:absolute;top:0;z-index:5;width:36px;height:80px;vertical-align:top;cursor:pointer}
    .slot-tab>span.prev{left:0;background-image:url(<?php echo TPL_NAME;?>images/lbindex/prev.png)}
    .slot-tab>span.lbnext{right:0;background-image:url(<?php echo TPL_NAME;?>images/lbindex/next.png)}
    .slot-machine .main-cell{margin:0 36px}
    .slot-machine ul.game-list li{position:relative;display:inline-block;width:92.8px;height:84px;padding-top:9px;color:#fff;font-size:12px;text-align:center;white-space:nowrap}
    .slot-machine ul.game-list li:hover:after,.slot-machine ul.game-list li.active:after{content:'';position:absolute;top:0;left:0;width:92.8px;height:84px;background-image:url(<?php echo TPL_NAME;?>images/lbindex/tab_h.png)}
    .slot-machine ul.game-list li:before{content:'';display:block;height:30px;margin-bottom:3px;background:no-repeat center;background-size:contain}
    .slot-machine ul.game-list li[game-box="mg"]:before{background-image:url(<?php echo TPL_NAME;?>images/lbindex/mg.png)}
    .slot-machine ul.game-list li[game-box="ag"]:before,.slot-machine ul.game-list li[game-box="agca"]:before{background-image:url(<?php echo TPL_NAME;?>images/lbindex/ag.png)}
    .slot-machine ul.game-list li[game-box="kg"]:before{background-image:url(<?php echo TPL_NAME;?>images/lbindex/kg.png)}
    .slot-machine ul.game-list li[game-box="ly"]:before{background-image:url(<?php echo TPL_NAME;?>images/lbindex/ly.png)}
    .slot-machine ul.game-list li[game-box="hg"]:before{background-image:url(<?php echo TPL_NAME;?>images/lbindex/hg.png)}
    .slot-machine ul.game-list li[game-box="vg"]:before{background-image:url(<?php echo TPL_NAME;?>images/lbindex/vg.png)}
    .slot-machine ul.game-list li[game-box="og"]:before{background-image:url(<?php echo TPL_NAME;?>images/lbindex/og.png)}
    .slot-machine ul.game-list li[game-box="cq9"]:before{background-image:url(<?php echo TPL_NAME;?>images/lbindex/cq9.png)}
    .slot-machine ul.game-list li[game-box="mw"]:before{background-image:url(<?php echo TPL_NAME;?>images/lbindex/mw.png)}
    .slot-machine ul.game-list li[game-box="fish"]:before{background-image:url(<?php echo TPL_NAME;?>images/lbindex/fish.png)}
    .slot-machine ul.game-list li>a{position:absolute;top:0;left:0;z-index:1;width:100px;height:80px;color:inherit;text-decoration:none}
    .slot-content{display:none;position:absolute;top:94px;left:50%;margin-left:-277px;width:490px;font-size:0}
    .slot-content p{margin:0;padding:23px 21px 18px;color:#8d8c8c;font-size:14px;line-height:24px;text-align:center;background-color:#242424}
    .slot-content.rtg p{padding-top:8px}
    .slot-content .jackpot{height:72px;color:#ffcf2e;font-size:24px;line-height:72px;text-align:center;background-image:url(<?php echo TPL_NAME;?>images/lbindex/title_bg.png)}
    .slot-content ul{display:block;width:477px;margin-top:10px;padding:10px 5px 0 8px;background:#242424}
    .slot-content li{cursor:pointer;position:relative;display:inline-block;width:136px;/*height:140px;*/margin:0 4px 10px 2px;padding:98px 7px 0;vertical-align:top;color:#fff;font-size:12px;line-height:42px;text-align:left;background:#2f2f2f no-repeat center top;-moz-transition:transform 1s;-o-transition:transform 1s;-webkit-transition:transform 1s;transition:transform 1s}
    .slot-content li:hover{-moz-transform:translateX(5px);-ms-transform:translateX(5px);-o-transform:translateX(5px);-webkit-transform:translateX(5px);transform:translateX(5px)}
    .slot-content li:after{content:'';float:right;width:55px;height:42px;background:url(<?php echo TPL_NAME;?>images/lbindex/stars.png) no-repeat center}
    .slot-content li[slot-game="mg01"]{background-image:url(images/game/mg/28114.png);background-position: 9px -13px;background-size: 181%;}
    .slot-content li[slot-game="mg02"]{background-image:url(images/game/mg/44751.png);background-position: 9px -13px;background-size: 181%;}
    .slot-content li[slot-game="mg03"]{background-image:url(images/game/mg/28800.png);background-position: 9px -13px;background-size: 181%;}
    .slot-content li[slot-game="mg04"]{background-image:url(images/game/mg/28772.png);background-position: 9px -13px;background-size: 181%;}
    .slot-content li[slot-game="mg05"]{background-image:url(images/game/mg/28546.png);background-position: 9px -13px;background-size: 181%;}
    .slot-content li[slot-game="mg06"]{background-image:url(images/game/mg/28794.png);background-position: 9px -13px;background-size: 181%;}
    .slot-content li[slot-game="ag01"]{background-image:url(images/game/ag/SB08_ZH.png);background-size: 76%;}
    .slot-content li[slot-game="ag02"]{background-image:url(images/game/ag/SB30_ZH.png);background-size: 76%;}
    .slot-content li[slot-game="ag03"]{background-image:url(images/game/ag/FRU_ZH.png);background-size: 76%;}
    .slot-content li[slot-game="ag04"]{background-image:url(images/game/ag/SB02_ZH.png);background-size: 76%;}
    .slot-content li[slot-game="ag05"]{background-image:url(images/game/ag/AV01_ZH.png);background-size: 76%;}
    .slot-content li[slot-game="ag06"]{background-image:url(images/game/ag/SB12_ZH.png);background-size: 76%;}
    .slot-content li[slot-game="ag_1"]{background-image:url(images/game/ag_1.png);}
    .slot-content li[slot-game="ag_2"]{background-image:url(images/game/ag_2.png);}
    .slot-content li[slot-game="og_1"]{background-image:url(images/game/og_1.png);}
    .slot-content li[slot-game="og_2"]{background-image:url(images/game/og_2.png);}
    .slot-content li[slot-game="fish01"]{background-image:url(images/game/fish/01.png)}
    .slot-content li[slot-game="fish02"]{padding:100px 4px 0;background-image:url(images/game/fish/02.png)}
    .slot-content li[slot-game="fish03"]{background-image:url(images/game/fish/03.png)}
    .slot-content li[slot-game="fish04"]{background-image:url(images/game/fish/04.png)}
    .slot-content li[slot-game="fish05"]{background-image:url(images/game/fish/05.png)}
    .slot-content li[slot-game="fish06"]{background-image:url(images/game/fish/06.png)}
    .slot-content li[slot-game="mw01"]{background-image:url(images/game/mw.png)}
    .slot-content li[slot-game="cq01"]{background-image:url(images/game/cq9/2_GodofChess.png);background-size: 70%;}
    .slot-content li[slot-game="cq02"]{background-image:url(images/game/cq9/3_VampireKiss.png);background-size: 70%;}
    .slot-content li[slot-game="cq03"]{background-image:url(images/game/cq9/10_LuckyBats.png);background-size: 70%;}
    .slot-content li[slot-game="cq04"]{background-image:url(images/game/cq9/17_GreatLion.png);background-size: 70%;}
    .slot-content li[slot-game="cq05"]{background-image:url(images/game/cq9/25_PokerSLOT.png);background-size: 70%;}
    .slot-content li[slot-game="cq06"]{background-image:url(images/game/cq9/30_Warriorlegend.png);background-size: 70%;}
    .slot-content li[slot-game="ky_830"]{background-image:url(<?php echo TPL_NAME;?>images/chess/ky/ky_830.png);background-size: 70%;}
    .slot-content li[slot-game="ky_220"]{background-image:url(<?php echo TPL_NAME;?>images/chess/ky/ky_220.png);background-size: 70%;}
    .slot-content li[slot-game="ky_910"]{background-image:url(<?php echo TPL_NAME;?>images/chess/ky/ky_910.png);background-size: 70%;}
    .slot-content li[slot-game="ky_600"]{background-image:url(<?php echo TPL_NAME;?>images/chess/ky/ky_600.png);background-size: 70%;}
    .slot-content li[slot-game="ky_720"]{background-image:url(<?php echo TPL_NAME;?>images/chess/ky/ky_720.png);background-size: 70%;}
    .slot-content li[slot-game="ky_860"]{background-image:url(<?php echo TPL_NAME;?>images/chess/ky/ky_860.png);background-size: 70%;}
    .slot-content li[slot-game="ly_830"]{background-image:url(<?php echo TPL_NAME;?>images/chess/ly/ly_830.png);background-size: 70%;}
    .slot-content li[slot-game="ly_220"]{background-image:url(<?php echo TPL_NAME;?>images/chess/ly/ly_220.png);background-size: 70%;}
    .slot-content li[slot-game="ly_910"]{background-image:url(<?php echo TPL_NAME;?>images/chess/ly/ly_910.png);background-size: 70%;}
    .slot-content li[slot-game="ly_600"]{background-image:url(<?php echo TPL_NAME;?>images/chess/ly/ly_600.png);background-size: 70%;}
    .slot-content li[slot-game="ly_720"]{background-image:url(<?php echo TPL_NAME;?>images/chess/ly/ly_720.png);background-size: 70%;}
    .slot-content li[slot-game="ly_860"]{background-image:url(<?php echo TPL_NAME;?>images/chess/ly/ly_860.png);background-size: 70%;}
    .slot-content li[slot-game="vg_1"]{background-image:url(<?php echo TPL_NAME;?>images/chess/vg/vg_1.png);background-size: 70%;}
    .slot-content li[slot-game="vg_3"]{background-image:url(<?php echo TPL_NAME;?>images/chess/vg/vg_3.png);background-size: 70%;}
    .slot-content li[slot-game="vg_4"]{background-image:url(<?php echo TPL_NAME;?>images/chess/vg/vg_4.png);background-size: 70%;}
    .slot-content li[slot-game="vg_6"]{background-image:url(<?php echo TPL_NAME;?>images/chess/vg/vg_6.png);background-size: 70%;}
    .slot-content li[slot-game="vg_7"]{background-image:url(<?php echo TPL_NAME;?>images/chess/vg/vg_7.png);background-size: 70%;}
    .slot-content li[slot-game="vg_8"]{background-image:url(<?php echo TPL_NAME;?>images/chess/vg/vg_8.png);background-size: 70%;}
    .slot-content li[slot-game="hg_3012"]{background-image:url(<?php echo TPL_NAME;?>images/chess/hg/hg_3012.png);background-size: 70%;}
    .slot-content li[slot-game="hg_3014"]{background-image:url(<?php echo TPL_NAME;?>images/chess/hg/hg_3014.png);background-size: 70%;}
    .slot-content li[slot-game="hg_3015"]{background-image:url(<?php echo TPL_NAME;?>images/chess/hg/hg_3015.png);background-size: 70%;}
    .slot-content li[slot-game="hg_3016"]{background-image:url(<?php echo TPL_NAME;?>images/chess/hg/hg_3016.png);background-size: 70%;}
    .slot-content li[slot-game="hg_3017"]{background-image:url(<?php echo TPL_NAME;?>images/chess/hg/hg_3017.png);background-size: 70%;}
    .slot-content li[slot-game="hg_3018"]{background-image:url(<?php echo TPL_NAME;?>images/chess/hg/hg_3018.png);background-size: 70%;}
    .slot-content.ng-hide-remove{-webkit-transition:all linear .4s;-moz-transition:all linear .4s;-o-transition:all linear .4s;transition:all linear .4s}
    .slot-content.ng-hide-add.ng-hide-add-active,.slot-content.ng-hide-remove{opacity:0}
    .slot-content.ng-hide-add,.slot-content.ng-hide-remove.ng-hide-remove-active{opacity:1}
    .home-bot{display:inline-block;width:100%;height:666px}
    .home-bot li a{position:relative;z-index:2;display:block;height:100%}
    .home-bot li[data-img]{position:relative}
    .home-bot li[data-img]:before{content:'';position:absolute;top:0;right:0;bottom:0;left:0;background-color:rgba(0,0,0,.5);opacity:0;filter:alpha(opacity=0);-moz-transition:1s;-o-transition:1s;-webkit-transition:1s;transition:1s}
    .home-bot li[data-img]:hover:before{opacity:1;filter:alpha(opacity=100)}
    .home-bot li[data-img]:after{content:'进入游戏';position:absolute;top:50%;left:50%;width:130px;height:35px;color:#eabe2d;font-size:16px;line-height:31px;text-align:center;border:2px solid #eabe2d;opacity:0;filter:alpha(opacity=0);-moz-border-radius:50px;-webkit-border-radius:50px;border-radius:50px;-moz-transform:translate(-50%,-50%);-ms-transform:translate(-50%,-50%);-o-transform:translate(-50%,-50%);-webkit-transform:translate(-50%,-50%);transform:translate(-50%,-50%);-moz-transition:1s;-o-transition:1s;-webkit-transition:1s;transition:1s;opacity:0;filter:alpha(opacity=0)}
    .home-bot li[data-img]:hover:after{opacity:1;filter:alpha(opacity=100)}
    .home-bot .left-area{float:left}
    .home-bot .left-area li{width:213px;height:249px;background:no-repeat center bottom}
    .home-bot .left-area li[data-img="live"]{margin-top:-12px;margin-bottom:14px;background-image:url(<?php echo TPL_NAME;?>images/lbindex/btn_live.png)}
    .home-bot .left-area li[data-img="fish"]{height:232px;background-image:url(<?php echo TPL_NAME;?>images/lbindex/btn_fish.jpg)}
    .home-bot .right-area{float:right}
    .home-bot .right-area .winner{width:278px;height:207px;margin-bottom:11px;padding-top:61px;vertical-align:top;background:url(<?php echo TPL_NAME;?>images/lbindex/winner_bg.jpg) no-repeat center top}
    .home-bot .right-area .winner .winner-list{padding:35px 0 0 13px}
    .home-bot .right-area .winner .winner-list .item{font-size:0}
    .home-bot .right-area .winner .winner-list .item span{display:inline-block;height:20px;padding-left:2px;vertical-align:top;color:#afafaf;font-size:12px;line-height:20px;text-align:center}
    .home-bot .right-area .winner .winner-list .item span.spa-1{width:70px;color:#e2da6a}
    .home-bot .right-area .winner .winner-list .item span.spa-2{width:94px;color:#f13131}
    .home-bot .right-area .winner .winner-list .item span.spa-3{width:85px;color:#16f80b}
    .home-bot .right-area .winner .winner-list .tempWrap{overflow:hidden; position:relative; height:170px}
    .home-bot .right-area .winner .winner-list .tempWrap ul{height: 350px;position: relative;}
    .home-bot .right-area .winner .winner-list .tempWrap ul li{height: 30px;}
    .home-bot .right-area .mobile{position:relative;width:278px;height:204px;background:url(<?php echo TPL_NAME;?>images/lbindex/mobile_bg.png) no-repeat center top}
    .home-bot .right-area .mobile>ol{position:absolute;top:70px;right:8px}
    .home-bot .right-area .mobile>ol>li{display:inline-block;width:69px;height:21px;vertical-align:top;color:#807f7f;font-size:12px;line-height:21px;text-align:center;border-radius:20px;border:1px solid #3d3d3d;cursor:pointer}
    .home-bot .right-area .mobile>ol>li.active{color:#ffcf2e;border-color:#ffcf2e}
    .home-bot .right-area .mobile>.mobile-qr{position:absolute;top:110px;right:35px;width:78px;height:78px;}
    .home-bot .right-area .mobile>.mobile-qr span{display:inline-block;width:100%;height:100%;background-size:100% !important}
    .home-bot .right-area .mobile>.mobile-qr img{width:80px}
    .home-bot .bottom-area{position:absolute;bottom:0;left:0;width:100%}
    .home-bot .bottom-area li{display:inline-block;width:492px;height:177px;vertical-align:top}
    .home-bot .bottom-area li[data-img="sport"]{margin-right:11px;background-image:url(<?php echo TPL_NAME;?>images/lbindex/btn_sport.jpg)}
    .home-bot .bottom-area li[data-img="board"]{background-image:url(<?php echo TPL_NAME;?>images/lbindex/btn_board.jpg)}

    .banner_base{text-align: center;}
    .banner_base a img{margin: 0 auto;width: 100px;animation: weuiLoading 1s steps(12) infinite;}
    @keyframes weuiLoading {
        0% {transform: rotate(0deg)}
        to {transform: rotate(1turn)}
    }

</style>

<!-- 轮播 -->
<div class="banner">
    <div class="jBanners banner">
      <div class="swiper-container" >
          <div class="swiper-wrapper">
              <div class="banner_base swiper-slide" >
                  <a href="javascript:;" >
                      <img src="/images/loading.svg">
                  </a>
              </div>
          </div>

          <!-- 分页器 -->
          <div class="swiper-pagination">

          </div>
          <!-- Add Arrows -->
         <!-- <div class="swiper-button-next" tabindex="0" role="button" aria-label="Next slide" aria-disabled="false"></div>
          <div class="swiper-button-prev swiper-button-disabled" tabindex="0" role="button" aria-label="Previous slide" aria-disabled="true"></div>-->

      </div>

  <div class="noticeContent">
      <div class="w_1000">
          <span></span>
          <marquee behavior="" direction="">
              <?php echo $_SESSION['memberNotice']; ?>
          </marquee>
      </div>

  </div>
 </div>
</div>

<div class="mainBody">
    <div class="w_1000">
        <div style="position: relative">
            <section class="slot-games">
                <div class="slot-machine" >
                    <div class="slot-tab" >
                        <span class="prev"></span>
                        <span class="lbnext"></span>
                        <div class="main-cell">

                            <ul class="camove game-list">
                                <li game-box="kg" >KY CHESS<a href="javascript:;"></a></li>
                                <li game-box="ly" >LY CHESS<a href="javascript:;"></a></li>
                                <li game-box="vg" >VG CHESS<a href="javascript:;"></a></li>
                                <li game-box="hg" >HG CHESS<a href="javascript:;"></a></li>
                                <li game-box="agca" >AG Casino<a href="javascript:;"></a></li>
                                <li game-box="og" >OG Casino<a href="javascript:;"></a></li>
                                <li game-box="ag">AG GAMES<a href="javascript:;"></a></li>
                                <li game-box="cq9">CQ9 GAMES<a href="javascript:;"></a></li>
                                <li game-box="mw" >MW GAMES<a href="javascript:;"></a></li>
                                <li game-box="mg" >MG GAMES<a href="javascript:;"></a></li>
                                <li game-box="fish">Fish Hunter<a href="javascript:;"></a></li>

                                <li game-box="kg" >KY CHESS<a href="javascript:;"></a></li>
                                <li game-box="ly" >LY CHESS<a href="javascript:;"></a></li>
                                <li game-box="vg" >VG CHESS<a href="javascript:;"></a></li>
                                <!--<li game-box="hg" >HG CHESS<a href="javascript:;"></a></li>-->
                                <li game-box="agca" >AG Casino<a href="javascript:;"></a></li>
                                <li game-box="og" >OG Casino<a href="javascript:;"></a></li>
                                <li game-box="ag">AG GAMES<a href="javascript:;"></a></li>
                                <li game-box="cq9">CQ9 GAMES<a href="javascript:;"></a></li>
                                <li game-box="mw" >MW GAMES<a href="javascript:;"></a></li>
                                <li game-box="mg" >MG GAMES<a href="javascript:;"></a></li>
                                <li game-box="fish">Fish Hunter<a href="javascript:;"></a></li>
                            </ul>
                        </div>
                    </div>

                    <!-- 开元 棋牌-->
                    <div class="slot-content-kg slot-content " style="display: block;">
                        <div class="jackpot">
                            KY棋牌在线人数 ：<span ><?php echo $sumonline[0];?></span>
                            </div>
                        <ul>
                            <li slot-game="ky_830" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/ky/index.php?uid=<?php echo $uid;?>')">抢庄牛牛</li>
                            <li slot-game="ky_220" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/ky/index.php?uid=<?php echo $uid;?>')">炸金花</li>
                            <li slot-game="ky_910" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/ky/index.php?uid=<?php echo $uid;?>')">百家乐</li>
                            <li slot-game="ky_600" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/ky/index.php?uid=<?php echo $uid;?>')">21点</li>
                            <li slot-game="ky_720" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/ky/index.php?uid=<?php echo $uid;?>')">二八杠</li>
                            <li slot-game="ky_860" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/ky/index.php?uid=<?php echo $uid;?>')">三公</li>

                        </ul>
                        <p>KY棋牌是一款非常好玩的棋牌休闲手游平台,平台集合了多款热门棋牌游戏，真实玩家在线，实时联网，随机匹配入场参加对战。</p>
                    </div>
                    <!-- 乐游 棋牌 -->
                    <div class="slot-content-ly slot-content ">
                        <div class="jackpot">
                            LY棋牌在线人数 ：<span ><?php echo $sumonline[1];?></span>
                        </div>
                        <ul>
                            <li slot-game="ly_830" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/lyqp/index.php?uid=<?php echo $uid;?>')">抢庄牛牛</li>
                            <li slot-game="ly_220" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/lyqp/index.php?uid=<?php echo $uid;?>')">炸金花</li>
                            <li slot-game="ly_910" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/lyqp/index.php?uid=<?php echo $uid;?>')">百家乐</li>
                            <li slot-game="ly_600" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/lyqp/index.php?uid=<?php echo $uid;?>')">21点</li>
                            <li slot-game="ly_720" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/lyqp/index.php?uid=<?php echo $uid;?>')">二八杠</li>
                            <li slot-game="ly_860" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/lyqp/index.php?uid=<?php echo $uid;?>')">三公</li>

                        </ul>
                        <p>LEG棋牌是竞技棋牌游戏开发商，主打多款热门网络棋牌游戏，德州扑克,斗地主,二八杠,抢庄牛牛,通比牛牛,炸金花,二十一点,三公等多种游戏。</p>
                    </div>
                    <!-- vg 棋牌 -->
                    <div class="slot-content-vg slot-content ">
                        <div class="jackpot">
                            VG棋牌在线人数 ：<span ><?php echo $sumonline[2];?></span>
                        </div>
                        <ul>
                            <li slot-game="vg_1" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/vgqp/index.php?uid=<?php echo $uid;?>')">斗地主</li>
                            <li slot-game="vg_3" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/vgqp/index.php?uid=<?php echo $uid;?>')">抢庄牛牛</li>
                            <li slot-game="vg_4" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/vgqp/index.php?uid=<?php echo $uid;?>')">百人牛牛</li>
                            <li slot-game="vg_6" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/vgqp/index.php?uid=<?php echo $uid;?>')">多财多福</li>
                            <li slot-game="vg_7" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/vgqp/index.php?uid=<?php echo $uid;?>')">竞咪楚汉德州</li>
                            <li slot-game="vg_8" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/vgqp/index.php?uid=<?php echo $uid;?>')">推筒子</li>

                        </ul>
                        <p>VG棋牌游戏是一款多人线上棋牌游戏平台，画面精美，伴随清新悠扬的游戏背景音效，带给你沉浸式的棋牌体验，收录丰富的经典棋牌玩法。</p>
                    </div>
                    <!-- hg 棋牌 -->
                   <!-- <div class="slot-content-hg slot-content ">
                        <div class="jackpot">
                            HG棋牌在线人数 ：<span ><?php /*echo $sumonline[3];*/?></span>
                        </div>
                        <ul>
                            <li slot-game="hg_3012" onclick="indexCommonObj.openGameCommon(this,'<?php /*echo $uid;*/?>','../../app/member/hgqp/index.php?uid=<?php /*echo $uid;*/?>')">斗地主</li>
                            <li slot-game="hg_3014" onclick="indexCommonObj.openGameCommon(this,'<?php /*echo $uid;*/?>','../../app/member/hgqp/index.php?uid=<?php /*echo $uid;*/?>')">百人炸金花</li>
                            <li slot-game="hg_3015" onclick="indexCommonObj.openGameCommon(this,'<?php /*echo $uid;*/?>','../../app/member/hgqp/index.php?uid=<?php /*echo $uid;*/?>')">抢庄牛牛</li>
                            <li slot-game="hg_3016" onclick="indexCommonObj.openGameCommon(this,'<?php /*echo $uid;*/?>','../../app/member/hgqp/index.php?uid=<?php /*echo $uid;*/?>')">龙虎斗</li>
                            <li slot-game="hg_3017" onclick="indexCommonObj.openGameCommon(this,'<?php /*echo $uid;*/?>','../../app/member/hgqp/index.php?uid=<?php /*echo $uid;*/?>')">二八杠</li>
                            <li slot-game="hg_3018" onclick="indexCommonObj.openGameCommon(this,'<?php /*echo $uid;*/?>','../../app/member/hgqp/index.php?uid=<?php /*echo $uid;*/?>')">德州扑克</li>

                        </ul>
                        <p>HG棋牌是由顶级团队倾力打造的棋牌游戏平台，一局荣登富豪榜，创新自由底分模式，超越经典的游戏玩法，让玩家在游戏中摸索玩牌技巧。</p>
                    </div>-->

                    <div class="slot-content-ag slot-content " >
                        <div class="jackpot">
                            AG累积奖池 ：<span class="cjcj_num"><?php echo $sumarr[2];?></span>
                        </div>
                        <ul>
                            <li slot-game="ag01" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/login.php?uid=<?php echo $uid;?>&gameid=115')">麻将老虎机</li>
                            <li slot-game="ag02" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/login.php?uid=<?php echo $uid;?>&gameid=139')">灵猴献瑞</li>
                            <li slot-game="ag03" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/login.php?uid=<?php echo $uid;?>&gameid=101')">水果拉霸</li>
                            <li slot-game="ag04" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/login.php?uid=<?php echo $uid;?>&gameid=109')">复古花园</li>
                            <li slot-game="ag05" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/login.php?uid=<?php echo $uid;?>&gameid=137')">性感女仆</li>
                            <li slot-game="ag06" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/login.php?uid=<?php echo $uid;?>&gameid=119')">海底漫游</li>
                        </ul>
                        <p>Asia Gaming平台是最流行的电子游艺平台之一，提供超百款游戏，以丰富玩法、视觉及声光效果提供顶级娱乐享受，只为你提供极致的游戏体验。</p>
                    </div>
                    <div class="slot-content-mg slot-content " >
                        <div class="jackpot">
                            MG累积奖池 ：<span class="cjcj_num"><?php echo $sumarr[0];?></span>
                        </div>
                        <ul>
                            <li slot-game="mg01" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/mg/mg_api.php?action=getLaunchGameUrl&game_id=1035')">5卷的驱动器</li>
                            <li slot-game="mg02" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/mg/mg_api.php?action=getLaunchGameUrl&game_id=1159')">篮球巨星</li>
                            <li slot-game="mg03" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/mg/mg_api.php?action=getLaunchGameUrl&game_id=1126')">招财鞭炮</li>
                            <li slot-game="mg04" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/mg/mg_api.php?action=getLaunchGameUrl&game_id=1286')">凯蒂卡巴拉</li>
                            <li slot-game="mg05" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/mg/mg_api.php?action=getLaunchGameUrl&game_id=1160')">持枪王者</li>
                            <li slot-game="mg06" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/mg/mg_api.php?action=getLaunchGameUrl&game_id=1060')">幸运的锦鲤</li>
                        </ul>
                        <p>MG Gaming电子游戏平台，为每一位玩家带来极为畅快的精彩街机竞技体验，丰富的棋牌玩法，畅快自由选择，让你享受精彩的互动棋牌魅力对决。</p>
                    </div>
                    <!-- CQ9 -->
                    <div class="slot-content-cq9 slot-content " >
                        <div class="jackpot">
                            CQ9累积奖池 ：<span class="cjcj_num"><?php echo $sumarr[0];?></span>
                        </div>
                        <ul>
                            <li slot-game="cq01" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/cq9/cq9_api.php?action=getLaunchGameUrl&game_id=2')">棋圣</li>
                            <li slot-game="cq02" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/cq9/cq9_api.php?action=getLaunchGameUrl&game_id=3')">杰克高手</li>
                            <li slot-game="cq03" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/cq9/cq9_api.php?action=getLaunchGameUrl&game_id=10')">五福临门</li>
                            <li slot-game="cq04" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/cq9/cq9_api.php?action=getLaunchGameUrl&game_id=17')">祥狮献瑞</li>
                            <li slot-game="cq05" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/cq9/cq9_api.php?action=getLaunchGameUrl&game_id=25')">扑克拉霸</li>
                            <li slot-game="cq06" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/cq9/cq9_api.php?action=getLaunchGameUrl&game_id=30')">三国序</li>

                        </ul>
                        <p>CQ9 Gaming平台是一款全新、能够兑换现金电子游戏，游戏中玩家能够体验到现金兑换金币的快感，通过不同的倍数，能够赢得不同数量的金币。</p>
                    </div>
                    <!-- MW -->
                    <div class="slot-content-mw slot-content " >
                        <div class="jackpot">
                            MW累积奖池 ：<span class="cjcj_num"><?php echo $sumarr[0];?></span>
                        </div>
                        <ul>
                            <li slot-game="mw01" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/mw/index.php')">大满贯</li>

                        </ul>
                        <p>MW Gaming大满贯电子平台提供约20款游戏，举凡千炮捕魚、五路财神、明星97、超级斗地主、百乐牛牛、皇家轮盘、双龙抢珠、太极、百家乐、森林舞会等。</p>
                    </div>

                    <div class="slot-content-fish slot-content " >
                        <div class="jackpot">
                            捕鱼累积奖池 ：<span class="cjcj_num"><?php echo $sumarr[3];?></span>
                        </div>
                        <ul>
                            <li slot-game="fish04" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/login.php?uid=<?php echo $uid;?>&gameid=6')">AG捕鱼王</li>
                        </ul>
                        <p>捕鱼达人游戏操作简单玩法丰富，多人捕鱼场景，增添了游戏的互动性，绚烂刺激的捕捉画面，超爽快的疯狂射击体验。</p>
                    </div>

                    <!-- AG 视讯 -->
                    <div class="slot-content-agca slot-content ">
                        <div class="jackpot">
                            AG视讯在线人数 ：<span ><?php echo $sumonline[4];?></span>
                        </div>
                        <ul>
                            <li slot-game="ag_1" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/login.php?uid=<?php echo $uid;?>')">AG视讯</li>
                            <li slot-game="ag_2" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/login.php?uid=<?php echo $uid;?>')">AG视讯</li>
                        </ul>
                        <p>AG真人视讯娱乐最受欢迎的棋牌资讯网站，同时也是信誉最好、使用率最高的真人视讯娱乐平台, 为您提供高档的线上游戏及性感荷官。</p>
                    </div>
                    <!-- OG 视讯 -->
                    <div class="slot-content-og slot-content ">
                        <div class="jackpot">
                            OG视讯在线人数 ：<span ><?php echo $sumonline[5];?></span>
                        </div>
                        <ul>
                            <li slot-game="og_1" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/og/og_api.php?action=getLaunchGameUrl')">OG视讯</li>
                            <li slot-game="og_2" onclick="indexCommonObj.openGameCommon(this,'<?php echo $uid;?>','../../app/member/zrsx/og/og_api.php?action=getLaunchGameUrl')">OG视讯</li>
                        </ul>
                        <p>OG真人视讯娱乐为您提供高品质、高赔率的娱乐游戏及所有线上投注的优惠,我们致力提供全球客户最有价值的游戏和各项优惠服务。</p>
                    </div>

                </div>
            </section>
            <section class="home-bot">
                <ul class="left-area">
                    <li data-img="live"><a href="javascript:;" class="to_lives"></a></li>
                    <li data-img="fish"><a href="javascript:;" class="to_fish"></a></li>
                </ul>
                <ul class="right-area">
                    <li class="winner">
                        <div class="winner-list" >
                            <div class="home-winners">
                                <div class="tempWrap" id="colee">
                                    <ul id="colee1">
                                        <!--<li ><div class="item"><span class="spa-1">cq***25</span><span class="spa-2">65664元</span><span class="spa-3">华夏祥瑞</span></div></li>
                                        <li ><div class="item"><span class="spa-1">AB***12</span><span class="spa-2">95982元</span><span class="spa-3">太空漫游</span></div></li>
                                        <li ><div class="item"><span class="spa-1">ci***34</span><span class="spa-2">74373元</span><span class="spa-3">抢庄牛牛</span></div></li>
                                        <li ><div class="item"><span class="spa-1">pi3**991</span><span class="spa-2">209181元</span><span class="spa-3">财神宾果</span></div></li>
                                        <li ><div class="item"><span class="spa-1">hon**122</span><span class="spa-2">61400元</span><span class="spa-3">幸运双星</span></div></li>
                                        <li ><div class="item"><span class="spa-1">zz***zz</span><span class="spa-2">96795元</span><span class="spa-3">洪福齐天</span></div></li>

                                        <li ><div class="item"><span class="spa-1">bo***06</span><span class="spa-2">71620元</span><span class="spa-3">橄榄球明星H5</span></div></li>
                                        <li ><div class="item"><span class="spa-1">wl***en</span><span class="spa-2">81375元</span><span class="spa-3">五福临门</span></div></li>
                                        <li ><div class="item"><span class="spa-1">ch***20</span><span class="spa-2">68990元</span><span class="spa-3">看牌抢庄牛牛</span></div></li>
                                        <li ><div class="item"><span class="spa-1">ofr1**3</span><span class="spa-2">74316元</span><span class="spa-3">马上有钱</span></div></li>
                                        <li ><div class="item"><span class="spa-1">xi***07</span><span class="spa-2">62000元</span><span class="spa-3">六颗糖</span></div></li>
                                        <li ><div class="item"><span class="spa-1">wh***08</span><span class="spa-2">95360元</span><span class="spa-3">凯洛琳夫人</span></div></li>-->

                                    </ul>
                                    <ul id="colee2"> </ul>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="mobile">
                        <ol class="tab">
                            <li >苹果下载</li>
                            <li >安卓下载</li>
                        </ol>
                        <div class="mobile-qr apple" >
                            <span class="download_ios_app"></span>
                        </div>
                        <div class="mobile-qr android" style="display: none">
                            <span class="download_android_app"></span>
                        </div>
                    </li>
                </ul>
                <ul class="bottom-area">
                    <li data-img="sport"><a href="javascript:;" class="to_sports" data-rtype="r" data-showtype="today"></a></li>
                    <li data-img="board"><a href="javascript:;" class="to_chess"></a></li>
                </ul>
            </section>
        </div>
    </div>

</div>


<script type="text/javascript">
 $(function () {
     var cjyjb = '<?php echo $game_af_str;?>';
     cjyjb = $.parseJSON(cjyjb);

      indexCommonObj.indexBannerAction();

     indexTopBanner();
     hoverChangeGame();
     winnerList();

      // hover 切换
     function hoverChangeGame() {
         $('.game-list a').mouseover(function () {
             var sel = $(this).parent('li').attr('game-box');
             $(this).parent('li').addClass('active').siblings().removeClass('active');
             $('.slot-content').hide();
             $('.slot-content-'+sel).fadeIn();
         })
     }

     // 首页顶部滚动
     function indexTopBanner() {
         var lb_speed = 25;
         var colee2= document.getElementById('colee2');
         var colee1= document.getElementById('colee1');
         var colee= document.getElementById('colee');
         colee2.innerHTML=colee1.innerHTML; //克隆colee1为colee2

        // console.log(colee.clientHeight)
         var gdLbInter=setInterval(Marquee1,lb_speed)//设置定时器
         function Marquee1(){
             //当滚动至colee1与colee2交界时
             if(colee2.offsetTop-colee.scrollTop<=0){
                 clearInterval(gdLbInter);
                 colee.scrollTop-=colee1.offsetHeight; //colee跳到最顶端
             }else if(colee.scrollTop== colee.clientHeight){ // 循环滚动 clearInterval(gdLbInter);
                 clearInterval(gdLbInter);
                 colee.scrollTop =0;
             }else{
                 colee.scrollTop ++;
             }
             if(colee.scrollTop %60 ==0){ // 等于当前图片高度
                 clearInterval(gdLbInter);
                 setTimeout(function () { // 停留一秒
                     gdLbInter=setInterval(Marquee1,lb_speed)
                 },1000)
             }
             //console.log(colee.scrollTop)
         }

         //鼠标移上时清除定时器达到滚动停止的目的
         // colee.onmouseover=function() {clearInterval(gdLbInter)}
         // //鼠标移开时重设定时器
         // colee.onmouseout=function(){gdLbInter=setInterval(Marquee1,lb_speed)}
     }
     
     // 赢家榜
    function winnerList() {
         var str = '';
        for(var i=0;i<cjyjb.data.length;i++){
            str += '<li ><div class="item"><span class="spa-1">'+ cjyjb.data[i].name +'</span><span class="spa-2">'+ cjyjb.data[i].winnum +'元</span><span class="spa-3">'+ cjyjb.data[i].game +'</span></div></li>';
        }
        $('#colee1').html(str);
    }

 })



</script>