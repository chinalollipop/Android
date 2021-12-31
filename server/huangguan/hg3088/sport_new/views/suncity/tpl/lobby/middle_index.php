<?php
session_start();
include "../../../../app/member/include/config.inc.php";
$redisObj = new Ciredis();
$uid = $_SESSION['Oid']; // 判断是否已登录
$yesday = date('Y-m-d',strtotime('-1 day'));

// 赢钱榜
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
    array ('name' => '6a******7','winnum' => 175624,'game' => '爱丽娜'),
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

?>
<style>

 /* 首页轮播 */
 .swiper-container-horizontal>.swiper-pagination-bullets{bottom:40px}
 .swiper-pagination-bullet{width:30px;height:8px;border-radius:0;background:rgba(0,0,0,.5)}
 .swiper-pagination-bullet-active{background:rgba(255,209,0,.5)}
 .news_all{height:110px;padding:7px 0}
 .news_all .news_logo{display:inline-block;width:72px;height:91px;background:url(<?php echo TPL_NAME;?>images/index/new_logo.png) no-repeat}
 .news_all .news_title{cursor:pointer;display:inline-block;width:220px;margin:0 10px}
 .news_all .news_title>p{display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;text-overflow:ellipsis}
 .news_all .big_title{font-size:18px;color:#dbce81;text-align:center;max-height:50px;line-height:26px}
 .news_all .small_title{color:#81765a;max-height:42px;line-height:20px;margin-top:5px}
 .camove{position:relative;width:100%;white-space:nowrap;-webkit-animation:moves 15s linear infinite;-moz-animation:moves 15s linear infinite;-o-animation:moves 15s linear infinite;animation:moves 15s linear infinite}
 .pause {animation-play-state: paused;}
 .slot-machine{width:880px;margin-top:15px;overflow:hidden}
 .slot-tab{position:relative;height:68px}
 .slot-tab>span{position:absolute;top:0;z-index:5;width:18px;height:68px;vertical-align:top;cursor:pointer}
 .slot-tab>span.prev{left:0;background:url(<?php echo TPL_NAME;?>images/index/left_icon.png) no-repeat}
 .slot-tab>span.lbnext{right:0;background:url(<?php echo TPL_NAME;?>images/index/right_icon.png) no-repeat}
 .slot-machine ul.game-list li{position:relative;display:inline-block;width:90px;height:100%;margin:2px 10px 0 0}
 .slot-machine ul.game-list li img{width: 100%;height: 100%;}
 .index_part1{height:522px;background:url(<?php echo TPL_NAME;?>images/index/part1_bg.png) center no-repeat;border-bottom:2px solid #302f2c;border-top:2px solid #302f2c}
 .index_part1>div{position:relative}
 .index_part1 .index_game_list{position:relative;width:910px;height:510px;display:none}
 .index_part1 .index_game_list:before{display:inline-block;content:'';width:440px;height:330px;background:url(<?php echo TPL_NAME;?>images/index/sun_bg.png) center no-repeat}
 .index_part1 .index_game_list.active{display:block}
 .index_part1 .index_game_list a{transition:.3s;display:inline-block;width:180px;height:43px;background:url(<?php echo TPL_NAME;?>images/index/btn_play.png) center no-repeat;position:absolute;bottom:160px;left:135px}
 .index_part1 .index_game_list a:hover{opacity:.8;transform:scale(1.07)}
 .index_part1 .index_game_solt{background:url(<?php echo TPL_NAME;?>images/index/index_game.png) center no-repeat;background-size:100%}
 .index_part1 .index_game_live{background:url(<?php echo TPL_NAME;?>images/index/index_live.png) center no-repeat;background-size:100%}
 .index_part1 .index_game_chess{background:url(<?php echo TPL_NAME;?>images/index/index_chess.png) center no-repeat;background-size:100%}
 .index_part1 .index_game_sport{background:url(<?php echo TPL_NAME;?>images/index/index_sport.png) center no-repeat;background-size:100%}
 .index_part1 .index_game_dzjj{background:url(<?php echo TPL_NAME;?>images/index/index_dj.png) center no-repeat;background-size:100%}
 .index_part1 .index_game_choose{position:absolute;width:340px;height:500px;right:0;top:10px;background:url(<?php echo TPL_NAME;?>images/index/game_hd.png) no-repeat}
 .index_game_choose li{cursor:pointer;width:125px;height:63px;line-height:63px;padding-left:50px;margin:26px 0 0 65px;text-align:center}
 .index_game_choose li.li_games{background:url(<?php echo TPL_NAME;?>images/index/game_title.png) no-repeat;margin-left:130px}
 .index_game_choose li.li_live{background:url(<?php echo TPL_NAME;?>images/index/live_title.png) no-repeat;}
 .index_game_choose li.li_chess{background:url(<?php echo TPL_NAME;?>images/index/chess_title.png) no-repeat;margin-left:140px}
 .index_game_choose li.li_sport{background:url(<?php echo TPL_NAME;?>images/index/sport_title.png) no-repeat;margin-left:115px}
 .index_game_choose li.li_dzjj{background:url(<?php echo TPL_NAME;?>images/index/dj_title.png) no-repeat;margin-left:45px}
 .index_game_choose li a{display:inline-block;width:100%;color:#ad9a7e;font-size:18px}
 .index_game_choose li.active{background-position:0 -63px}
 .index_part2{height:420px}
 .index_part2 .app_img{display:inline-block;width:510px;height:100%;background:url(<?php echo TPL_NAME;?>images/index/app_img.png) center no-repeat}
 .index_part2 .ewm_bg{position:relative;width:405px;height:100%;background:url(<?php echo TPL_NAME;?>images/index/ewm_bg.png) center no-repeat}
 .index_part2 .ewm_bg .ewm_li{display:inline-block;position:absolute;bottom:50px;left:55px}
 .index_part2 .ewm_bg .ewm_li:last-child{left:190px}
 .index_part2 .ewm_bg .ewm_li span{display:inline-block;width:110px;height:110px;background-size:100% !important}
 .index_part2 .ewm_bg .ewm_li img{width:110px;position:relative}
 .index_part2 .ewm_bg .ewm_li p{width:110px;height:21px;background:url(<?php echo TPL_NAME;?>images/index/ios.png) center no-repeat;margin-top:8px}
 .index_part2 .ewm_bg .an_ewm_li p{background:url(<?php echo TPL_NAME;?>images/index/android.png) center no-repeat;}
 .index_part2 .jjb_title{position:relative;width:284px;height:100%;background:url(<?php echo TPL_NAME;?>images/index/yjb.png) center no-repeat}
 .index_part2 .jjb_content_all{height:175px;overflow:hidden;position:absolute;top:189px;padding:0 8px}
 .index_part2 .jjb_content_all .jjb_content{position:relative;white-space:nowrap;-webkit-animation:movestop 8s linear infinite;animation:movestop 8s linear infinite}
 .index_part2 .winning_name{line-height:35px}
 .index_part2 .winning_name span{display:inline-block;text-align:center}
 .index_part2 .winning_name span:first-child{min-width:100px;color:#fdf5a0}
 .index_part2 .winning_name span:nth-child(2){min-width:65px;color:#b10c0c}
 .index_part2 .winning_name span:last-child{min-width:100px;color:#5f5f5f}
 .pause {animation-play-state: paused;}

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
          <div class="swiper-pagination swiper-pagination-index">

          </div>
      </div>

      <div class="noticeContent">
          <div class="w_1200">
              <span></span>
              <marquee behavior="" direction="">
                  <?php echo $_SESSION['memberNotice']; ?>
              </marquee>
          </div>

      </div>
 </div>
</div>

<div class="mainBody">

    <div class="w_1200">
        <!-- 新闻滚动 -->
        <div class="news_all">
            <div class="fl">
                <span class="news_logo"> </span>
                <div class="news_title show_news_content">
                    <p class="big_title"> 新闻标题 </p>
                    <p class="small_title"> 新闻简介 </p>
                </div>
            </div>
            <div class="fr">
                <section class="slot-games">
                    <div class="slot-machine">
                        <div class="slot-tab">
                            <span class="prev"></span>
                            <span class="lbnext"></span>
                            <div class="main-cell" style="padding-left: 25px;">

                                <ul class="camove game-list">
                                    <!--<li ><a href="javascript:;"><img src="/images/index/new_1.png"></a></li>
                                    <li ><a href="javascript:;"><img src="/images/index/new_2.png"></a></li>
                                    <li ><a href="javascript:;"><img src="/images/index/new_3.png"></a></li>
                                    <li ><a href="javascript:;"><img src="/images/index/new_1.png"></a></li>
                                    <li ><a href="javascript:;"><img src="/images/index/new_2.png"></a></li>
                                    <li ><a href="javascript:;"><img src="/images/index/new_3.png"></a></li>
                                    <li ><a href="javascript:;"><img src="/images/index/new_1.png"></a></li>
                                    <li ><a href="javascript:;"><img src="/images/index/new_2.png"></a></li>
                                    <li ><a href="javascript:;"><img src="/images/index/new_3.png"></a></li>
                                    <li ><a href="javascript:;"><img src="/images/index/new_1.png"></a></li>
                                    <li ><a href="javascript:;"><img src="/images/index/new_2.png"></a></li>
                                    <li ><a href="javascript:;"><img src="/images/index/new_3.png"></a></li>
                                    <li ><a href="javascript:;"><img src="/images/index/new_1.png"></a></li>
                                    <li ><a href="javascript:;"><img src="/images/index/new_2.png"></a></li>
                                    <li ><a href="javascript:;"><img src="/images/index/new_3.png"></a></li>
                                    <li ><a href="javascript:;"><img src="/images/index/new_1.png"></a></li>
                                    <li ><a href="javascript:;"><img src="/images/index/new_2.png"></a></li>
                                    <li ><a href="javascript:;"><img src="/images/index/new_3.png"></a></li>-->
                                </ul>
                            </div>
                        </div>

                    </div>
                </section>
            </div>
        </div>
    </div>
    <!-- 游戏切换 -->
    <div class="index_part1">
        <div class="w_1200">
            <!-- 电子游戏 -->
            <div class="index_game_list index_game_solt">
                <a href="javascript:;" class="to_games">　</a>
            </div>
            <!-- 真人 -->
            <div class="index_game_list index_game_live active">
                <a href="javascript:;" class="to_lives">　</a>
            </div>
            <!-- 棋牌 -->
            <div class="index_game_list index_game_chess">
                <a href="javascript:;" class="to_chess">　</a>
            </div>
            <!-- 体育 -->
            <div class="index_game_list index_game_sport">
                <a href="javascript:;" class="to_sports">　</a>
            </div>
            <!-- 电竞 -->
            <div class="index_game_list index_game_dzjj">
                <a href="javascript:;" class="to_dianjing">　</a>
            </div>
            <!-- 选择游戏 -->
            <div class="index_game_choose">
                <ul>
                    <li class="li_live active" data-type="live"> <a href="javascript:;" >真人视讯</a> </li>
                    <li class="li_games" data-type="solt"> <a href="javascript:;" >电子游戏</a> </li>
                    <li class="li_chess" data-type="chess"> <a href="javascript:;" >棋牌游戏</a> </li>
                    <li class="li_sport" data-type="sport"> <a href="javascript:;" >体育竞技</a> </li>
                    <li class="li_dzjj" data-type="dzjj"> <a href="javascript:;" >电子竞技</a> </li>
                </ul>
            </div>

        </div>
    </div>

    <!--  APP 下载 -->
    <div class="index_part2">
        <div class="w_1200">
            <div class="fl">
                <div class="fl">
                    <span class="app_img"></span>
                </div>
                <div class="fr ewm_bg">
                    <div class="ewm_li">
                        <span class="download_ios_app"> </span>
                        <p></p>
                    </div>
                    <div class="ewm_li an_ewm_li">
                        <span class="download_android_app"> </span>
                        <p></p>
                    </div>
                </div>
            </div>
            <div class="fr">
                <div class="jjb_title" >
                    <div class="jjb_content_all">
                        <div class="jjb_content " > <!-- movetop -->

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


</div>


<script type="text/javascript">
 $(function () {
     var game_af_str = '<?php echo $game_af_str;?>'; // 游戏奖金
     game_af_str = $.parseJSON(game_af_str);

      indexCommonObj.indexBannerAction();

     chooseGmaes();
     getGamePrize();
     indexCommonObj.getNewsRecommend('thumb');
     showNewsDetail();
     indexNewRunning();


     // 切换游戏
     function chooseGmaes() {
         $('.index_game_choose li').hover(function () {
             var type = $(this).attr('data-type');
             $(this).addClass('active').siblings().removeClass('active');
             $('.index_game_list').stop(true,true).hide();
             $('.index_game_'+type).stop(true,true).fadeIn();

         },function () {

         })
     }

     // 中奖信息
     function getGamePrize(){
         var $jjb_content = $('.jjb_content');
         var str ='';
         for(var k=0;k<game_af_str.data.length;k++){
           str += '<div class="winning_name"><span>'+ game_af_str.data[k].name +'</span><span>'+ game_af_str.data[k].winnum +'</span><span>'+ game_af_str.data[k].game +'</span></div>' ;

         }
         $jjb_content.html(str);

     }

     // 显示新闻详情
     function showNewsDetail() {
         $(document).off('click','.show_news_content').on('click','.show_news_content',function () { // 显示新闻
             var conId = $(this).attr('data-id');
             indexCommonObj.getNewsRecommend('content',conId);
         });
     }

     // 新闻滚动处理
     function indexNewRunning() {
         $('.game-list').on('mouseenter','li',function () {
             $(this).parent('.game-list').addClass('pause');
         })
         $('.game-list').on('mouseleave','li',function () {
             $(this).parent('.game-list').removeClass('pause');
         })

     }


 })



</script>