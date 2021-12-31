<?php
set_time_limit(0);
$timeStart = microtime(true);
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
require ("app/agents/include/config.inc.php");

$memberArr = array('915977278',
'gp2008',
'1044395428',
'jkl5201314',
'dushen2019',
'hjt138310',
'a89658890',
'li525216',
'Dis258369',
'wdy7632',
'lsy999',
'qiu19880304',
'dai188',
'shun1981',
'2018aa',
'112233tt',
'dengrui',
'z8217780',
'an82070447',
'pms59270',
'Ying12580',
'ylg388',
'tt88886666',
'q3214321868',
'ycm197821',
'shs123',
'fdc1234',
'xx1995',
'18250010775',
'qq941126',
'mt3327236531',
'XSJ021720',
'czl1994',
'8888mm',
'love521',
'wangling',
'lubl197812',
'123456ABCD',
'zrt999517',
'245834',
'hh6666',
'duwang666',
'wangqiao555',
'13394275050',
'18319225355',
'w289988',
'a251889388',
'lyh112',
'a200605016',
'puzhou111222',
'c654102704',
'yyy999000',
'1640771971',
'abc0905',
'zhuyingpei',
'wei20750611',
'am188104',
'gqj101',
'g187131811',
'leesamin',
'caicai11',
'lubenweinb',
'kmww888888',
'baobaozzz',
'41714',
'liang1127',
'gaoyuan111',
'xiaoyio',
'2570720856',
'q2890500863',
'lsjwbfh',
'qq156156',
'Jopeak556',
'13921894844',
'av12345',
'lcc666',
'LQGF',
'a223711965',
'shiyue666',
'xl321321',
'cc1990888',
'yaj1990',
'xt7328',
'yfb555mm',
'22336688',
'lx225317',
'790201121yu',
'zxc898989',
'kiko2017',
'lkj123',
'rzdgzj',
'mingtian88',
'a2631205847',
'fghjrtyu236',
'amigolwd',
'zth51234',
'lsg133',
'zhi1991',
'tkq888',
'w9022029',
'17376473442',
't271271',
'hpw882233',
'p885966',
'ly520',
'27989623a',
'a1194991785',
'lmyqzl',
'1783555',
'mmm43811597',
'zzzbaobao',
'gmcp666888',
'licongbao152',
'jl8888',
'575859',
'zy1998520',
'jun12345',
'WYZ65800',
'yudi9286',
'song1843',
'912867459',
'LIN305742',
'17608312307',
'g7075228',
'w244359',
'yangjing881008',
'ping071230',
'weiwei123',
'as888999',
'ltw888',
'xql123',
'kangpingjie',
'07970797',
'741852963',
'd564615300',
'15935939455',
'qiao',
'a04131226',
'qi6543210',
'1611676724',
'shuaishuaida88',
'lxg198411',
'AQS1568',
'mei1019',
'li940218',
'67117437',
'13815299088',
'ge198746',
'liujiang',
'gj971125',
'159186',
'luo0122',
'wa888999',
'xiaoqi',
'15889392686',
'meimei3',
'zz171717');

$resMess=array();


foreach($memberArr as $mk=>$v){
    $sRes=$sRow=$tRes=$sRow;
    $resMess[$v]=[];
    $sSql = "SELECT userid,username,SUM(currency_after)-SUM(moneyf) AS SS, TYPE FROM hgty78_web_sys800_data 
                    WHERE UserName = '{$v}'
                    AND Checked = 1 
                    AND TYPE ='S' 
                    AND discounType NOT IN (3,4) 
                    GROUP BY TYPE";
    $sRes = mysqli_query($dbMasterLink,$sSql);
    $sRow = mysqli_fetch_assoc($sRes);
    $resMess[$v]['s'] = $sRow['SS'];

    $tSql = "SELECT userid,username,SUM(moneyf) - SUM(currency_after) AS ST, TYPE FROM hgty78_web_sys800_data 
            WHERE UserName = '{$v}'
            AND Checked = 1 
            AND TYPE ='T' 
            AND discounType NOT IN (3,4) 
            GROUP BY TYPE";
    $tRes = mysqli_query($dbMasterLink,$tSql);
    $tRow = mysqli_fetch_assoc($tRes);
    $resMess[$v]['t'] = $tRow['ST'];
    $resMess[$v]['dii'] = $sRow['SS']-$tRow['ST'];

    echo $v."  s:".$sRow['SS']."  t:".$tRow['ST']."  diff:".$sRow['SS']-$tRow['ST'];
    echo "\n\r";
}











