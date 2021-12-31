<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "include/address.mem.php";
// echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";

require ("include/config.inc.php");
require ("include/google.authenticator.php");

$langx ="zh-cn";
$_SESSION['langx']=$langx;
$uid = isset($_REQUEST['uid'])?$_REQUEST['uid']:'';
$actionType = isset($_REQUEST['actionType'])?$_REQUEST['actionType']:'';
$level=$_REQUEST['level'];
$username=$_REQUEST["UserName"];
$or_password = trim($_REQUEST["PassWord"]);
$password=passwordEncryption($or_password,$username);
$captcha=$_REQUEST["captcha"];

if(in_array(TPL_FILE_NAME, ['0086','0086dj', '6668','jinsha'])){
    $_SESSION['template'] = 'old';
}else{
    $_SESSION['template'] = 'new'; // 新彩票
}

$redisObj = new Ciredis();
$resdata = array();
// 2018/03 更改，不需要从session 拿之前登录的数据

/*if(strlen($_SESSION['UserName'])<>'0' and strlen($_SESSION["password1"])<>'0'){
	$username=$_SESSION['UserName'];
	$password=$_SESSION["password1"];


}*/

if($actionType =='login' || $actionType == 'login_ad'){ // 登录  login_ad 在会员网站直接登录

    $sql = "select website,Admin_Url from ".DBPREFIX."web_system_data where ID=1";
    $result = mysqli_query($dbLink,$sql);
    $row = mysqli_fetch_assoc($result);

    $admin_url=explode(";",$row['Admin_Url']);
    // $_SERVER['SERVER_NAME'] 在 nginx 中 如果 配有多个域名会匹配第一个，改用 HTTP_HOST
    if (in_array($_SERVER['HTTP_HOST'],array($admin_url[0],$admin_url[1],$admin_url[2],$admin_url[3]))){ // 超级管理员
        $data=DBPREFIX.'web_system_data';
        $CurType =''; // 系统表没有这个字段
        $Bank_competence = ',Bank_competence' ;
    }else{ // 代理
        $data=DBPREFIX.'web_agents_data';
        $CurType = ',CurType,third_PassWord';
        $Bank_competence = '' ; // 代理表没有这个
    }
    $str = time('s');
    $uid=substr(md5($username),0,6).ucfirst(substr(md5($str),0,rand(50,50))).'ag'.rand(0,1);

    $ip_addr = get_ip();

    if(!empty($langx) && !empty($level) && !empty($username) && !empty($password)) {
        // 后台管理员登录验证ip
        if($level == 'M' && CHECK_IP_SWITCH) {
    //		$admin_ips = explode(',' , ADMIN_LIST_IP);
    //		foreach($admin_ips as $value) {
    //			// 去除空白字符
    //			$new_admin_ips[] = preg_replace("/(\s+)/",'',$value);
    //		}
            $admin_ips = '';
            $cacheFile = CACHE_DIR . '/agents/tmp/ipwhitelist.txt';
            if (file_exists($cacheFile)) {
                $admin_ips = file_get_contents($cacheFile);
            }
            $new_admin_ips = json_decode($admin_ips, true);
            if(!in_array($ip_addr , $new_admin_ips)) {
                $status = '400.01';
                $describe = '登录失败!!\\n未被授权访问的IP!!';
                original_phone_request_response($status,$describe,$resdata);
            }
        }

        // google身份验证码
        if(CHECK_CODE_SWITCH) {
            if($level == 'M' || !empty($captcha)) {
                if(!preg_match("/^[0-9]+$/",$captcha)){
                    $status = '400.02';
                    $describe = '请检查验证码!!';
                    original_phone_request_response($status,$describe,$resdata);
                }
                $ga = new PHPGangsta_GoogleAuthenticator();
                $checkResult = $ga->verifyCode(SecretKey, $captcha, 2);

                if(!$checkResult) {
                    $status = '400.03';
                    $describe = '验证码匹配失败，请重新输入!!';
                    original_phone_request_response($status,$describe,$resdata);
                }
            }
        }
    }

    $loginfo = $username.' 用户登入成功';

    //$mysql = "select * from `$data` where Level='$level' and UserName='$username' and PassWord='$password' and Status<2";
    //// echo $mysql;die();
    //$result = mysqli_query($dbLink,$mysql);
    //$cou=mysqli_num_rows($result);

    /*if ($cou==1){
        echo "<script>window.open('".BROWSER_IP."/app/agents/chg_ln.php?username=$username&password=$password&langx=$langx','_top')</script>";
        exit;
    }else{*/
    $mysql = "select ID,UserName,Alias,EditDate,PassWord,Level,Competence,Status,SubUser,SubName $CurType $Bank_competence from `$data` where Level='$level' and UserName ='$username' and Status<2";
    // echo $mysql;EXIT;
    $result = mysqli_query($dbMasterLink,$mysql);
    $row = mysqli_fetch_assoc($result);
    $cou=mysqli_num_rows($result);

    $third_pwd = passwordThird($username,$or_password) ; // 国民第三方密码加密

    if($row['PassWord']){ // 体育这边存在密码
        if($row['PassWord'] !=$password){
            $status = '400.04';
            $describe = '登录错误!请检查用户名或密码!!';
            original_phone_request_response($status,$describe,$resdata);
        }
    }else{ // 从第三方导入的会员
        if($row['third_PassWord']){ // 首次需要更新登录密码
            $upsql="update `$data` set PassWord='$password' where UserName='$username' and Status<2";
            $result = mysqli_query($dbMasterLink,$upsql);
        }
        if ( !passwordThirdCheck($third_pwd,$row['third_PassWord']) ){
            $status = '400.05';
            $describe = '登录错误!!请检查用户名或密码!!';
            original_phone_request_response($status,$describe,$resdata);
        }
    }

    //如果是管理员登录的话，那么再多置一个状态位
    if($level == 'M') {
        $_SESSION['is_admin']=ADMINLOGINFLAG;
    }
    $_SESSION['Oid']=$uid; // 把oid 放在session 里面
    $_SESSION['ID']= $row['ID']; // 把id 放在session 里面
    $_SESSION['admin_level']= $row['Level']; // Level 放在session 里面
    $_SESSION['Alias']=$row['Alias'];
    $_SESSION['UserName']=$username; // 把username 放在session 里面
    $_SESSION['password1']=$password;
    $_SESSION['Level'] = $row['Level'];

    $redisObj->setOne('loginadmin_'.$row['ID'] , $uid);// 写入redis,限制同时登陆

    $date=date("Y-m-d");
    $todaydate=strtotime(date("Y-m-d"));
    $editdate=strtotime($row['EditDate']);
    $time=($todaydate-$editdate)/86400;
    if ($time>30){ // 更改密码
        $sql="update `$data` set Oid='$uid' where UserName='$username' and Status<2";
        if(mysqli_query($dbMasterLink,$sql) ){
            $status = '300';
            $describe = '请更改密码!!';
            original_phone_request_response($status,$describe,$resdata);
           // echo "<script>top.bb_mem_index.location = '".$_SERVER['HTTP_ORIGIN']."/app/agents/chg_pw.php?&uid=$uid';</script>";
        }else{
            $status = '500';
            $describe = '操作失败!!';
            original_phone_request_response($status,$describe,$resdata);
        }

    }else{

        $_SESSION['flag'] = ",01,02,03,04,05,06,07,08,09,10,11,12,13";
        $_SESSION['SubUser'] = $row['SubUser'];
        $_SESSION['SubName'] = $row['SubName'];
        $_SESSION['Status'] = $row['Status'];
        $_SESSION['CurType'] = $row['CurType'];
        $_SESSION['Competence'] = $row['Competence'];
        $_SESSION['Bank_competence'] = $row['Bank_competence'];

        $uppwd = "";
        if($level=='D' && !$row['PassWord']) { // 代理商才需要，如果没有体育这边的登录密码
            $uppwd = "PassWord='$password',";
        }
        $sql="update $data set $uppwd Level='$level',Oid='$uid',LoginDate='$date',LoginTime=now(),OnlineTime=now(),Language='$langx',Online='1',Url='".BROWSER_IP."',LoginIP='$ip_addr' where UserName='".$username."'";

        if(mysqli_query($dbMasterLink,$sql)){ // 登录成功
            $status = '200';
            $describe = '登录成功!!';
            $resdata = array('level'=>$_SESSION['Level'],'uid'=>$uid,'username'=>$username);
        }else{
            $status = '500';
            $describe = '操作失败!!';
            original_phone_request_response($status,$describe,$resdata);
        }

        if($level=='D'){ // 代理商才需要
            $loginArr = array(
                0=>$_SESSION['ID'] ,
                1=>$_SESSION['UserName'] ,
                2=>'' ,
                3=>1 , // 0会员，1代理商
                4=>0 , // 信用额度
                5=>$_SESSION['Alias']  ,
            ) ;
            addLoginIpLog($loginArr) ; // 记录登录 ip记录
        }

        /* 插入系统日志 */
        innsertSystemLog($username,$level,$loginfo);
    }

    if($actionType =='login'){
        original_phone_request_response($status,$describe,$resdata); // 登录成功
    }

}

// 将当前后台登录用户数据存入redis, 用于出存款分层显示
//        $redisObj = new Ciredis();
//        $subCompetences = array('ID' =>$row['ID'], 'SubUser' => $_SESSION['SubUser'], 'Competence'=>$_SESSION['Competence']);
//        //$redisObj->getSET('sub_'.$row['ID']."_Competence",json_encode($subCompetences));
//        $redisObj->insert('sub_'.$_SESSION['ID']."_Competence",$subCompetences , 12*3600);
//        $getSubComRedis = unserialize($redisObj->getSimpleOne('sub_'.$row['ID']."_Competence"));

// 2018 修改新增
require ("./include/traditional.$langx.inc.php");

$competence = $_SESSION['Competence'];
$num=explode(",",$competence);
//        var_dump($num);exit;

/**
 * 菜单顶部第一层
 */
if ($num[11]==1){
    $Item.="<a href=admin/query.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title=\"查询注单\" >查询注单</a>|";
    $Item.="<a href=admin/query_review.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title=\"注单回顾\" >注单回顾</a>|";
}
if ($num[9]==1){
    $Item.="<a href=score/match.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title=\"审核比分\" >审核比分</a>|";
    $Item.="<a href=score/recommened_match.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title=\"推荐赛事\" >推荐赛事</a>|";
}
if ($num[37]==1){
    $Item.="<a href=800/index.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title=\"现金系统\">$Mnu_System</a> |";
}

/**
 * 层级管理
 * 提款审核菜单和出款审核是同一个权限，存款审核是一个权限
 * 子账号有可能有多个层级权限
 */

//if($getSubComRedis['SubUser'] == '0') { // 管理员
if($_SESSION['admin_level']=='M'){ // 超级管理员才有
    if($_SESSION['SubUser'] == '0') { // 管理员
        $Item.="<a class='yellow_a' href=800/withdraw_list_800.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title=\"提款审核\">提款审核(<span class='withdraw_num'>0</span>)笔</a> |";
        $Item.="<a href=800/cash_out.php?uid=$uid&langx=$langx&lv=$level&action=T target=\"main\" title=\"出款\">出款(<span class='withdraw_num_1'>0</span>)笔</a> |";
        $Item.="<a href=800/deposit_audit.php?uid=$uid&langx=$langx&lv=$level&action=S target=\"main\" title=\"存款\">存款(<span class='deposit_num_1'>0</span>)笔</a> |";
    } elseif($_SESSION['SubUser'] == '1' && $competence) { // 子账号
        if(strpos($competence,'-1') !== false) { //出款
            $Item.="<a class='yellow_a' href=800/withdraw_list_800.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title=\"提款审核\">提款审核(<span class='withdraw_num'>0</span>)笔</a> |";
            $Item.="<a href=800/cash_out.php?uid=$uid&langx=$langx&lv=$level&action=T target=\"main\" title=\"出款\">出款(<span class='withdraw_num_1'>0</span>)笔</a> |";
        }
        if(strpos($competence,'-0') !== false) { //存款
            $Item.="<a href=800/deposit_audit.php?uid=$uid&langx=$langx&lv=$level&action=S target=\"main\" title=\"存款\">存款(<span class='deposit_num_1'>0</span>)笔</a> |";
        }
    }
}

if ($num[0]==1){	//在线人数
    $Item.="<a href=online/online.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title=\"在线人数\" >在线人数(<span id=\"online\" class='yellow_a'></span>)</a>|";
}

if ($num[23]==1){   // 金额转换
    $Item.="<a href=800/transfer.php?uid=$uid&langx=$langx&lv=$level target=\"main\">金额转换</a>|";
}

if ($num[27]==1) { //层级管理
    $Item.="<a href=onlinepay/level_manage.php?uid=$uid&langx=$langx&lv=$level target=\"main\">层级管理</a>";
}

if ($level=="M") {
    $Item.=" | <a href=transfer/speedcheck.php?uid=$uid&langx=$langx&lv=$level target=\"main\">中转站测速</a>|";
    $Item.="<a class='yellow_a' href=accounts/phoneCall.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title=\"客户回访\">客户回访(<span class='phone_call_num'>0</span>)笔</a> |";

    $sendMailTotalKey = 'USER_SENDMAIL_TOTAL';
    $userSendmailTotal = $redisObj->getSimpleOne($sendMailTotalKey);
    if($userSendmailTotal < 1 ) $userSendmailTotal = 0;
    $Item.="<a class='yellow_a' href=admin/usermails.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title=\"会员消息\">会员消息(<span class='mem_msg_num'>".$userSendmailTotal."</span>)条</a>";


}

/**
 * 菜单顶部第二层
 */
// 游戏管理
if ($num[1]==1){
    $Title.="<ul class='top_ul'><li><a href='javascript:;' class='hover_on' title='游戏管理'>游戏管理</a>|</li>
        <li><ul class='top_down_ul top_down_ul_yx'>";
    if ($num[28]==1) {
        $Title.="<li><a href=admin/system.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title=\"系统参数\" >系统参数</a></li>";
        $Title.="<li><a href=admin/1000wData.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title=\"1000w刷水\" >1000w刷水</a></li>";
    }
    if ($num[2]==1) {
        $Title.="<li><a href='admin/add_notice.php?uid=$uid&lv=$level&langx=$langx' title='系统公告' target=\"main\">系统公告</a></li>";
    }

    $Title.="<li><a href='other_set/show_marquee.php?uid=$uid&lv=MEM&langx=$langx&level=$level' title='公告' target=\"main\" >公告</a></li>";
    if($num[3]==1) {
        $Title.="<li><a class=\"a_link\" href=admin/news.php?uid=$uid&lv=$level&langx=$langx&action=opennews target=\"main\" title=\"系统短信\" >系统短信</a></li>";
    }
    if($num[4]==1) {
        $Title.="<li><a class=\"a_link\" href=admin/news.php?uid=$uid&lv=$level&langx=$langx&action=sitenews target=\"main\" title=\"系统消息\" >系统消息</a></li>";
    }
    if($num[7]==1) {
        $Title.="<li><a href=league/league.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title=\"联盟限制\" >联盟限制</a></li>";
    }
    if($num[5]==1) {
        $Title.="<li><a href=admin/data.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title=\"数据刷新\" >数据刷新</a></li>";
    }
    if($num[47]==1) {
        $Title.="<li><a href=admin/rebate_hour_user.php title=\"时时返水\" target=\"main\">时时返水</a></li>";
    }


    $Title.="<li><a class=\"a_link\" href=\"admin/credit.php?uid=$uid&langx=$langx\" target=\"main\">检查额度</a></li>
        </ul></li></ul>" ;
}

// 帐号管理
if ($num[29]==1) {
    $Title .= "<ul class='top_ul'><li><a href='javascript:;' class='hover_on' title='帐号管理'>帐号管理</a>|</li>
	<li><ul class='top_down_ul top_down_ul_zh'>";
            if ($num[21]==1) { // 会员
                $Title .= "<li><a href=agents/user_browse.php?uid=$uid&langx=$langx&lv=MEM&userlv=$level target=\"main\" title=\"$Mnu_Member\" >$Mnu_Member</a></li>";
                $Title .= "<li><a href=agents/user_layer.php target=\"main\" title=\"会员分层活动\" >会员分层活动</a></li>";
            }
            if ($num[20]==1) { // 代理商
                $Title .= "<li><a href=agents/user_browse.php?uid=$uid&langx=$langx&lv=D&userlv=$level target=\"main\" title=\"$Mnu_Agents\" >$Mnu_Agents</a></li>";
                $Title .= "<li><a href=agents/agents_seach_url.php?uid=$uid&langx=$langx&userlv=$level target=\"main\" title=\"代理商域名\" >代理商域名</a></li>";
                $Title .= "<li><a href=agents/agents_commission.php?uid=$uid&langx=$langx&userlv=$level target=\"main\" title=\"代理商佣金\" >代理商佣金</a></li>";
                $Title .= "<li><a href=agents/commission_rate_list.php?uid=$uid&langx=$langx&userlv=$level target=\"main\" title=\"代理退佣设置\" >代理退佣设置</a></li>";
                $Title .= "<li><a href=agents/water_rate_list.php?uid=$uid&langx=$langx&userlv=$level target=\"main\" title=\"代理退水设置\" >代理退水设置</a></li>";
                $Title .= "<li><a href=agents/agents_fee_set.php?uid=$uid&langx=$langx&userlv=$level target=\"main\" title=\"代理手续费设置\" >代理手续费设置</a></li>";
                $Title .= "<li><a href=agents/third_water_list.php?uid=$uid&langx=$langx&userlv=$level target=\"main\" title=\"三方抽水设置\" >三方抽水设置</a></li>";
            }
            $Title .= "<li><a class='a_link' href='admin/access.php?uid=$uid&langx=$langx&action=S&lv=$level' target=\"main\">会员存款</a></li>
	<li><a class='a_link' href='admin/access.php?uid=$uid&langx=$langx&action=T&lv=$level' target=\"main\">会员提款</a></li>
	<li><a  href='javascript:;' title='会员信息'>会员信息</a></li>";
            $Title .= "<li><a class='a_link' href='agents/countDeposit.php?uid=$uid&langx=$langx&action=S&lv=$level' target=\"main\">会员充值统计</a></li>
	                   <li><a class='a_link' href='agents/countBet.php?uid=$uid&langx=$langx&action=T&lv=$level' target=\"main\">投注人数统计</a></li>";
            $Title .= "</ul></li></ul>";
        }
// 常用管理
if ($num[30]==1) {
    $Title .= "<ul class='top_ul'><li><a href='javascript:;' class='hover_on' title='常用管理'>常用管理</a>|</li>
	      <li><ul class='top_down_ul top_down_ul_cy' >";
    if ($num[19]==1) {  //总代理
        $Title .= "<li><a href=agents/user_browse.php?uid=$uid&langx=$langx&lv=C&userlv=$level target=\"main\" title=\"$Mnu_World\" >$Mnu_World</a></li>";
    }
    if ($num[18]==1) {	//股东
        $Title .= "<li><a href=agents/user_browse.php?uid=$uid&langx=$langx&lv=B&userlv=$level target=\"main\" title=\"$Mnu_Corprator\" >$Mnu_Corprator</a></li>";
    }
    if ($num[17]==1) {	//公司
        $Title .= "<li><a href=agents/user_browse.php?uid=$uid&langx=$langx&lv=A&userlv=$level target=\"main\" title=\"$Mnu_Super\" >$Mnu_Super</a></li>";
    }
    if ($num[16]==1) { // 子账号
        $Title .= "<li><a href=agents/subuser.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title=\"$Mnu_Sub_Account\" >$Mnu_Sub_Account</a></li>";
    }
    if ($num[15]==1) { //基本资料
        $Title .= "<li><a href=agents/self_data.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title=\"基本资料设定\" >基本资料设定</a></li>";
    }
    $Title .="</ul></li></ul>";
}


        // 球赛管理  普通代理不显示
        /*if($num[31]==1) {
            $Title .= "<ul class='top_ul'><li><a href='javascript:;' class='hover_on' title='球赛管理'>球赛管理</a>|</li>
            <li><ul class='top_down_ul top_down_ul_qs'>
            <li><a href=real_wager/index.php?uid=$uid&langx=$langx&gtype=FT&ptype=S target=\"main\" title=\"足球即時注單\" > $Mnu_EarlyMarket</a>]</li>
            <li><a href=real_wager/index.php?uid=$uid&langx=$langx&gtype=BK&ptype=S target=\"main\" title=\"籃球即時注單\" >$Mnu_EarlyMarket</a>]</li>
            <li> <a href=real_wager/index.php?uid=$uid&langx=$langx&gtype=BS&ptype=S target=\"main\" title=\"棒球即時注單\" >$Mnu_EarlyMarket</a>]</li>
            <li><a href=real_wager/index.php?uid=$uid&langx=$langx&gtype=TN&ptype=S target=\"main\" title=\"網球即時注單\" >$Mnu_EarlyMarket</a>]</li>
            <li> <a href=real_wager/index.php?uid=$uid&langx=$langx&gtype=VB&ptype=S target=\"main\" title=\"棒球即時注單\" >$Mnu_EarlyMarket</a>]</li>
            <li> <a href=real_wager/index.php?uid=$uid&langx=$langx&gtype=OP&ptype=S target=\"main\" title=\"其他即時注單\" > $Mnu_EarlyMarket</a>]</li>
            <li>[<a href=real_wager/real_result.php?uid=$uid&langx=$langx&gtype=FT target=\"main\" title=\"$Rel_Game_result\" >$Rel_Game_result</a>]</li>
            </ul></li></ul>";
        }*/


        // 报表查询
        if ($num[32]==1) {
            $Title .= "<ul class='top_ul'><li><a href='javascript:;' class='hover_on' title='$Mnu_Report.$Submit_search'>报表/查询</a>|</li><li><ul class='top_down_ul top_down_ul_bb' >";
        if ($num[22]==1) {// 报表
            $Title .= "<li><a href=report_new/report.php?uid=$uid&langx=$langx&lever=$level&casino=2 target=\"main\" title=\"$Mnu_Report\" >$Mnu_Report</a></li>";
            $Title .= "<li><a href=report_new/agent_report.php?uid=$uid&langx=$langx&lever=$level&casino=2 target=\"main\" title='代理报表'>代理报表</a></li>";
        }
        if ($num[44]==1) {// 今日统计
            $Title .= "<li><a href=report_new/report_today_statistic.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title=\"今日统计\" >今日统计</a></li>";
        }
        if ($num[14]==1) {// 系统日志
            $Title .= "<li><a href=admin/syslog.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title=\"系统日志\" >系统日志</a></li>";
        }
	 $Title .= "<li><a href=admin/rebate.php?uid=$uid target=\"main\" title='返点查询' >返点查询</a></li>
	<li><a  href=admin/rebate_hour.php target=\"main\" title='时时返点查询' >时时返点查询</a></li>
	<li><a  href=accounts/bill.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title='会员账单' >会员账单</a></li>
	<li><a  href='javascript:;' title='查询功能'>查询功能</a></li>
	<li><a  href=admin/statistics.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title='统计功能'>统计功能</a></li>
	<li><a  href='javascript:;' title='掉单查询'>掉单查询</a></li>
	<li><a href=online/ipsearch.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title='IP查询' >IP查询</a></li>
	<li><a href=online/iplimit.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title='IP限制管理' >IP限制管理</a></li>";
    if($num[46] == 1){
        $Title .= "<li><a href=online/adminiplimit.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title='IP后台管理' >IP后台管理</a></li>";
    }
    $Title .= "<li><a  href='javascript:;' title='银行存款'>银行存款</a></li>
	</ul></li></ul>";
}

// 视讯管理
if($num[34]==1) {
            $Title .= "<ul class='top_ul'><li><a href='javascript:;' class='hover_on' title='视讯管理'>视讯管理</a>|</li>
	<li><ul class='top_down_ul top_down_ul_sx'>
	<li> <a href=admin/ag.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title=\"AG视讯/电子\" >AG视讯/电子</a></li>
	<li> <a href=admin/ag_buyu_sence.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title=\"AG捕鱼王\">AG捕鱼王</a></li>
	<li> <a href=admin/admin_tran.php?uid=$uid&langx=$langx&lv=$level&type=ag target=\"main\" title=\"AG额度转换\" >AG额度转换</a></li>
	<li> <a href=admin/og.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title=\"OG视讯\" >OG视讯</a></li>
	<li> <a href=admin/admin_tran.php?uid=$uid&langx=$langx&lv=$level&type=og target=\"main\" title=\"OG额度转换\" >OG额度转换</a></li>
	<li> <a href=admin/jx/bbin.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title=\"BBIN视讯\" >BBIN视讯</a></li>
	<li> <a href=admin/admin_tran.php?uid=$uid&langx=$langx&lv=$level&type=bbin target=\"main\" title=\"BBIN额度转换\" >BBIN额度转换</a></li>
	<li> <a href=admin/jx/jxagent.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title=\"金星代理\" >金星代理</a></li>
	</ul></li></ul>";
}

// 彩票电子电竞
if ($num[35]==1) {
    $Title .= "<ul class='top_ul'><li><a href='javascript:;' class='hover_on' title='彩票管理电竞'>彩票电子电竞</a>|</li>
	<li><ul class='top_down_ul top_down_ul_cp'>
	<li><a href=admin/hgcp.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title=\"体育彩票\" >体育彩票</a></li>
	<li> <a href=admin/admin_tran.php?uid=$uid&langx=$langx&lv=$level&type=cp target=\"main\" title=\"额度转换\" >额度转换</a></li>
	<li><a href=admin/mg.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title=\"MG电子\" >MG电子</a></li>
	<li> <a href=admin/admin_tran.php?uid=$uid&langx=$langx&lv=$level&type=mg target=\"main\" title=\"MG额度转换\">MG额度转换</a></li>
	<li><a href=admin/mw.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title=\"MW电子\" >MW电子</a></li>
	<li> <a href=admin/admin_tran.php?uid=$uid&langx=$langx&lv=$level&type=mw target=\"main\" title=\"MW额度转换\" >MW额度转换</a></li>
	<li><a href=admin/cq9.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title=\"CQ9电子\" >CQ9电子</a></li>
    <li><a href=admin/admin_tran.php?uid=$uid&langx=$langx&lv=$level&type=cq target=\"main\" title=\"额度转换\" >CQ9额度转换</a></li>
    <li><a href=admin/fg.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title=\"FG电子\" >FG电子</a></li>
    <li><a href=admin/admin_tran.php?uid=$uid&langx=$langx&lv=$level&type=fg target=\"main\" title=\"额度转换\" >FG额度转换</a></li>
	<li><a href=admin/avia.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title=\"泛亚电竞\">泛亚电竞</a></li>
	<li> <a href=admin/admin_tran.php?uid=$uid&langx=$langx&lv=$level&type=avia target=\"main\" title=\"额度转换\" >泛亚额度转换</a></li>
	<li><a href=admin/fire.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title=\"雷火电竞\" >雷火电竞</a></li>
	<li> <a href=admin/admin_tran.php?uid=$uid&langx=$langx&lv=$level&type=fire target=\"main\" title=\"额度转换\" >雷火额度转换</a></li>
	<li><a href=admin/thirdLotterySet.php?uid=$uid&type=third_cp&lv=$level target=\"main\" title=\"第三方彩票设置\">第三方彩票设置</a></li>
	<li><a href=admin/thirdSscCp.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title=\"彩票信用注单\" >彩票信用注单</a></li>
	<li><a href=admin/thirdProjectCp.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title=\"彩票官方注单\" >彩票官方注单</a></li>
	<li><a href=admin/thirdTraceCp.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title=\"彩票官方追号\" >彩票官方追号</a></li>
    <li><a href=admin/admin_tran.php?uid=$uid&langx=$langx&lv=$level&type=gmcp target=\"main\" title=\"额度转换\" >额度转换</a></li>
	</ul></li></ul>";
}

// 开元棋牌管理
if($num[42]==1) { // <li> <a href=admin/hgqp.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title=\"皇冠棋牌\" >皇冠棋牌额度转换</a></li>
    $Title .= "<ul class='top_ul'><li><a href='javascript:;' class='hover_on' title='棋牌管理'>棋牌管理</a>|</li>
        <li><ul class='top_down_ul top_down_ul_sx'>
        <li> <a href=admin/ky.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title=\"开元棋牌\" >开元棋牌</a></li>
        <li> <a href=admin/admin_tran.php?uid=$uid&langx=$langx&lv=$level&type=ky target=\"main\" title=\"额度转换\" >开元额度转换</a></li>
        
        <li> <a href=admin/vgqpSet.php?uid=$uid&type=vg&lv=$level target=\"main\" title=\"VG棋牌设置\" >VG棋牌设置</a></li>
        <li> <a href=admin/vgqp.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title=\"VG棋牌\" >VG棋牌</a></li>
        <li> <a href=admin/admin_tran.php?uid=$uid&langx=$langx&lv=$level&type=vg target=\"main\" title=\"额度转换\" >VG额度转换</a></li>
        <li> <a href=admin/lyqpSet.php?uid=$uid&type=ly&lv=$level target=\"main\" title=\"乐游棋牌设置\" >乐游棋牌设置</a></li>
        <li> <a href=admin/lyqp.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title=\"乐游棋牌\" >乐游棋牌</a></li>
        <li> <a href=admin/admin_tran.php?uid=$uid&langx=$langx&lv=$level&type=ly target=\"main\" title=\"乐游额度转换\" >乐游额度转换</a></li>
        <li> <a href=admin/klqp.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title=\"快乐棋牌\" >快乐棋牌</a></li>
        <li> <a href=admin/admin_tran.php?uid=$uid&langx=$langx&lv=$level&type=kl target=\"main\" title=\"快乐额度转换\" >快乐额度转换</a></li>
        </ul>
        </li>
        </ul>";
}

// 其他管理
if ($num[33]==1) {
    $Title .= "<ul class='top_ul'><li><a href='javascript:;' class='hover_on' title='其他管理'>其他管理</a>|</li>
	<li><ul class='top_down_ul top_down_ul_qt'>";
    if ($num[43]==1) {
        $Title .= "<li><a  href='admin/guest_phone.php?uid=$uid&langx=$langx&lv=$level' target=\"main\" title='试玩参观手机号'>试玩参观手机号</a></li>";
    }
    if ($num[25]==1) {
        $Title .= "<li><a href=onlinepay/pay_config.php?uid=$uid&langx=$langx&lv=$level target=\"main\">第三方支付配置</a></li>";
        $Title .= "<li><a href=onlinepay/third_pay.php?uid=$uid&langx=$langx&lv=$level target=\"main\">第三方订单</a></li>";
    }
    if ($num[26]==1) {
        $Title .= "<li><a href=onlinepay/bank_config.php?uid=$uid&langx=$langx&lv=$level target=\"main\">线下银行</a></li>";
    }
    $Title .= "<li><a href=onlinepay/autopay_config.php?uid=$uid&langx=$langx&lv=$level target=\"main\">自动出款配置</a></li>";
    $Title .= "<!--<li><a  href='javascript:;' title='短信息'>短信息</a></li>
	<li><a  href='javascript:;' title='滚球UID'>滚球UID</a></li>
	<li><a  href='javascript:;' title='代理域名'>代理域名</a></li>
	<li><a  href='javascript:;' title='IP开放'>IP开放</a></li>
	<li><a  href='javascript:;' title='半场不结算'>半场不结算</a></li>
	<li><a  href='javascript:;' title='参数设置'>参数设置</a></li>
	<li><a  href='javascript:;' title='神秘彩金'>神秘彩金</a></li>-->
	<li><a href=admin/newVersionSet.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title='新版切换旧版域名配置' >非https域名/新旧版配置</a></li>
	<li><a href=admin/sysConfig.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title='系统配置管理' >系统配置管理</a></li>
	<li><a href=mobile/terminal.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title='终端列表' >终端管理</a></li>
    <li><a href=mobile/release.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title='发布版本' >发布管理</a></li>
	<li><a href=agents/promotion_code.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title='推广管理' >推广管理</a></li>
	<li><a href=promotion/promos.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title='优惠活动管理' >优惠活动管理</a></li>
	<li><a href=article/article.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title='新闻资讯管理'>新闻资讯管理</a></li>
    <li><a href=site/carousel.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title='轮播管理'>轮播管理</a></li>
    <li><a href=site/picture.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title='图片管理'>图片管理</a></li>";
    // $Title .="<li><a  href='activity/newyear_red_envelope_config.php?uid=$uid&langx=$langx&lv=$level' target=\"main\" title='新春红包配置'>新春红包配置</a></li>";

//        if ($row['UserName'] == NEWYEAR_ACCOUNT) {  //查看签到账号
//            $Title .= "<li><a  href='activity/newyear_red_envelope_signin.php?uid=$uid&langx=$langx&lv=$level' target=\"main\" title='新春签到记录'>新春签到记录</a></li>";
//        }

    // $Title .= "<li><a  href='activity/newyear_red_envelope_bill.php?uid=$uid&langx=$langx&lv=$level' target=\"main\" title='新春红包领取记录'>新春红包领取记录</a></li>";

    if ($num[43]==1) {
        $Title .= "<li><a  href='admin/opinion_complaint.php?uid=$uid&langx=$langx&lv=$level' target=\"main\" title='意见投诉'>意见投诉</a></li>";
    }
    $Title .="</ul></li></ul>";

}

// 活动管理
if($num[33]==1) {
    $Title .= "<ul class='top_ul'><li><a href='javascript:;' class='hover_on' title='活动管理'>活动管理</a>|</li>
            <li><ul class='top_down_ul top_down_ul_sx'>";
    $Title .="
    <li> <a href=admin/appsignin/attendanceSet.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title=\"签到基础设置\">签到基础设置</a></li> 
    <li> <a href=admin/appsignin/attendanceSinIn.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title=\"签到记录查询\">签到记录查询</a></li>
    <li> <a href=admin/appsignin/attendanceBill.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title=\"签到彩金领取查询\">签到彩金领取查询</a></li>
	<li><a  href='promotion/promosReview.php?uid=$uid&langx=$langx&lv=$level' target=\"main\" title='优惠活动审核'>优惠活动审核</a></li>
	<!--<li><a  href='activity/payout_hongbao.php?uid=$uid&langx=$langx&lv=$level' target=\"main\" title='双节红包'>双节红包</a></li>-->
	<li><a  href='activity/lucky_red_envelope_config.php?uid=$uid&langx=$langx&lv=$level' target=\"main\" title='APP幸运红包配置'>APP幸运红包配置</a></li>
	<li><a  href='activity/lucky_red_envelope_bill.php?uid=$uid&langx=$langx&lv=$level' target=\"main\" title='APP幸运红包记录'>APP幸运红包记录</a></li>
	<li><a  href='activity/download_app_gift_bill.php?uid=$uid&langx=$langx&lv=$level' target=\"main\" title='APP老会员领取彩金记录'>APP老会员领取彩金记录</a></li>
	 <!--<li><a  href='activity/vipup.php?uid=$uid&langx=$langx&lv=$level' target=\"main\" title='VIP晋升活动'>VIP晋升活动</a></li>
	<li><a  href='activity/audit.php?uid=$uid&langx=$langx&lv=$level' target=\"main\" title='投注VS全勤'>投注VS全勤</a></li>
    <li><a  href='activity/chessgame.php?uid=$uid&langx=$langx&lv=$level' target=\"main\" title='棋牌彩金'>棋牌彩金</a></li>
    <li><a  href='activity/weekTransfer.php?uid=$uid&langx=$langx&lv=$level' target=\"main\" title='周周转运金'>周周转运金</a></li>
    <li><a  href='activity/mouthSix.php?uid=$uid&langx=$langx&lv=$level' target=\"main\" title='每月逢6必发'>每月逢6必发</a></li>
    <li><a  href='activity/lotteryKing.php?uid=$uid&langx=$langx&lv=$level' target=\"main\" title='洗码之王'>洗码之王</a></li>-->
    <!--<li><a  href='activity/shuangdanGiftGold.php?uid=$uid&langx=$langx&lv=$level' target=\"main\" title='双旦迎春彩金'>双旦迎春彩金</a></li>-->
    <!--li><a  href='activity/moonFestival.php?uid=$uid&langx=$langx&lv=$level' target=\"main\" title='喜迎中秋'>喜迎中秋</a></li-->
    <!--<li><a  href='activity/nationalDay.php?uid=$uid&langx=$langx&lv=$level&gold_type=1' target=\"main\" title='国庆活动'>国庆活动</a></li>
    <li><a  href='activity/newyear2020_288w.php?uid=$uid&langx=$langx&lv=$level&gold_type=1' target=\"main\" title='288万红包任你拿'>288万红包任你拿</a></li>
    <li><a  href='activity/newyear2020_888w.php?uid=$uid&langx=$langx&lv=$level&gold_type=1' target=\"main\" title='888万红包任你拿'>888万红包任你拿</a></li>
    <li><a  href='activity/newyear2020_888w_config.php?uid=$uid&langx=$langx&lv=$level&gold_type=1' target=\"main\" title='888万红包任你拿'>888万红包任你拿几率配置</a></li>
    <li><a  href='activity/newyear2020_3366.php?uid=$uid&langx=$langx&lv=$level&gold_type=1' target=\"main\" title='3366新年活动'>3366新年活动</a></li>
    <li><a  href='activity/newyear2020_6668.php?uid=$uid&langx=$langx&lv=$level&gold_type=1' target=\"main\" title='6668新年活动'>6668新年活动</a></li>
    <li><a  href='activity/newyear2020_6668_config.php?uid=$uid&langx=$langx&lv=$level&gold_type=1' target=\"main\" title='6668新年活动几率设置'>6668新年活动几率设置</a></li>
    <li><a  href='activity/newyear2021_hb_config.php?uid=$uid&langx=$langx&lv=$level&gold_type=1' target=\"main\" title='2021新年新气象'>2021新年新气象配置</a></li>
    <li><a  href='activity/newyear2021_hb.php?uid=$uid&langx=$langx&lv=$level&gold_type=1' target=\"main\" title='2021新年新气象'>2021新年新气象</a></li>-->
    <li><a  href='activity/best_lucky_config.php?uid=$uid&langx=$langx&lv=$level&gold_type=1' target=\"main\" title='幸运大转盘几率设置'>幸运大转盘几率设置</a></li>
    <li><a  href='activity/best_lucky.php?uid=$uid&langx=$langx&lv=$level&gold_type=1' target=\"main\" title='幸运大转盘'>幸运大转盘</a></li>
    <li><a  href='activity/zhenren_week_report.php?uid=$uid&langx=$langx&lv=$level' target=\"main\" title='真人每周报表'>真人每周报表</a></li>
    <li><a  href='activity/zhenren_jinji_salary.php?uid=$uid&langx=$langx&lv=$level' target=\"main\" title='真人晋级礼金'>真人晋级礼金</a></li>
    <li><a  href='activity/sport_dj_week_report.php?uid=$uid&langx=$langx&lv=$level' target=\"main\" title='体育电竞每周报表'>体育电竞每周报表</a></li>
    <li><a  href='activity/sport_dj_jinji_salary.php?uid=$uid&langx=$langx&lv=$level' target=\"main\" title='体育电竞晋级礼金'>体育电竞晋级礼金</a></li>
    <!--<li><a  href='activity/0086_double_holiday_salary_2020.php?uid=$uid&langx=$langx&lv=$level&gold_type=1' target=\"main\" title='双节（中秋、国庆）俸禄'>双节（中秋、国庆）俸禄</a></li>-->
    ";
    $Title .="</ul>
            </li> 
            </ul>";
}

//if ($num[17]==1){ // 公司
//    $Item.="<a href=agents/user_browse.php?uid=$uid&langx=$langx&lv=A&userlv=$level target=\"main\" title=\"$Mnu_Super\" >$Mnu_Super</a>|";
//}
//if ($num[18]==1){ // 股东
//    $Item.="<a href=agents/user_browse.php?uid=$uid&langx=$langx&lv=B&userlv=$level target=\"main\" title=\"$Mnu_Corprator\" >$Mnu_Corprator</a>|";
//}
//if ($num[19]==1){ // 总代
//    $Item.="<a href=agents/user_browse.php?uid=$uid&langx=$langx&lv=C&userlv=$level target=\"main\" title=\"$Mnu_World\" >$Mnu_World</a>|";
//}
//if ($num[20]==1){ // 代理
//    $Item.="<a href=agents/user_browse.php?uid=$uid&langx=$langx&lv=D&userlv=$level target=\"main\" title=\"$Mnu_Agents\" >$Mnu_Agents</a>|";
//}
//  目前只有普通代理且不是子账号
//if ($num[20]==0){
if ($level=='D' && $_SESSION['SubUser'] !== 1){
    $Title.="<a href=body_home.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title=\"首页\" >首页</a>|";
    $Title.="<a href=agents/user_browse.php?uid=$uid&langx=$langx&lv=MEM&userlv=$level target=\"main\" title=\"$Mnu_Member\" >$Mnu_Member</a>|";
    $Title.="<a href=agents/agents_commission.php?uid=$uid&langx=$langx&userlv=$level target=\"main\" title=\"代理商佣金\" >代理商佣金</a>|";
    $Title.="<a href=report_new/report.php?uid=$uid&langx=$langx&lever=$level&casino=2 target=\"main\" title=\"$Mnu_Report\" >$Mnu_Report</a>|";
}

//if ($num[7]==1){
//	$Title.="<a href=league/league.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title=\"联盟限制\" >联盟限制</a>|";
//}

        //if ($num[5]==1){
        //	$Title.="<a href=admin/data.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title=\"数据刷新\" >数据刷新</a>|";
        //}
        //基础设置
        if($num[33]==1) { // 权限跟 其他管理 一样
            $Title .= "<ul class='top_ul'><li><a href='javascript:;' class='hover_on' title='基础设置'>基础设置</a>|</li>
            <li><ul class='top_down_ul top_down_ul_sx'>";
            if ($num[6] == 1) {   // 币值设置
                $Title .= "<li> <a href=admin/show_currency.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title=\"币值设置\" >币值设置</a></li>";
            }
            $Title .= "<li> <a href=admin/register_set.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title=\"会员注册设置\" >会员注册设置</a></li>
            </ul>
            </li> 
            </ul>";
        }

        //if ($num[7]==1){
        //	$Title.="<a href=league/league.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title=\"联盟限制\" >联盟限制</a>|";
        //}
        //数据操盘
        /*if ($num[8]==1){
            $Title.="<a href=admin/play_game.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title=\"数据操盘\" >数据操盘</a>|";
        }*/
        //滚球注单
        if ($num[10]==1){
            $Title.="<a href=accounts/re_list.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title=\"滚球注单\" >滚球注单</a>|";
        }


if ($num[13]==1){
    $Title.="<a href=agents/ag_details.php?uid=$uid&langx=$langx target=\"main\" title=\"分红明细\" >分红明细</a>";
}

// 皇冠体育管理
if($num[45]==1) {
//    $Title .= "<ul class='top_ul'><li><a href='javascript:;' class='hover_on' title='皇冠体育'>皇冠体育</a>|</li>
//                <li>
//                <ul class='top_down_ul top_down_ul_sx'>
//                <li> <a href=admin/sportCenterSet.php?uid=$uid&langx=$langx&lv=$level target=\"main\" title=\"中心参数\" >中心参数</a></li>
//                <li> <a href=admin/admin_tran.php?uid=$uid&langx=$langx&lv=$level&type=sc target=\"main\" title=\"额度转换\" >额度转换</a></li>
//                </ul>
//                </li>
//                </ul>";
}

        /*if ($num[13]==1){
            $Title.="<a href=agents/ag_details.php?uid=$uid&langx=$langx target=\"main\" title=\"分红明细\" >分红明细</a>";
        }*/
        // 安全登出
    if($level=='M'){
        $Title .= "<a href=\"logout.php?uid=$uid\" target=\"_top\" >安全退出</a>" ;
    }



/*}*/
?>
<html>
<head>
    <title> <?php echo COMPANY_NAME;?> </title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="../../style/agents/control_header.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <?php
    if($_SESSION['template'] == 'new'){
        echo '<link href="../../style/'.TPL_FILE_NAME.'.css?v='.AUTOVER.'" rel="stylesheet" type="text/css">';
    }
    ?>
    <style>
        .top_ul {display: inline-block;float: left;list-style: none;}
        body {overflow-x: hidden;}
    </style>
</head>

<!--<noframes>-->
<body  <?php if($level=='M'){?> onLoad="autoGetData()"<?php } ?>>
<frameset rows="71,*" frameborder="NO" border="0" framespacing="0">

    <!-- 2018 修改 -->
    <!--　头部 -->

    <div class="ui-header <?php echo ($level=='D'?'ag-ui-header':'');?>">
        <div class="top_header_left">
            <div class="logo">
                <?php
                if( $_SESSION['template']=='old'){
                    echo '<img src="../../images/logo_'.TPL_FILE_NAME.'.png?v=2" height="45px">';
                }else{
                    echo '<img src="../../images/'.TPL_FILE_NAME.'/logo.png?v=2" height="45px">';
                }
                ?>

            </div>
            <span class="user-name">
                <?php
                if($level=='D'){ // 只有普通代理有
                    echo '代理商-' ;
                }
                ?>
                <?php echo $_SESSION['UserName']?>
            </span>
        </div>
        <!-- 右侧 -->
        <div class="top_header_right">
            <!-- 右侧顶部 -->
            <div class="top_header_right_top">
                <div class="memu1">
                    <?php echo $Item?>
                </div>
                <?php

                if($level=='D'){ // 只有普通代理有
                    $agent_qq = getSysConfig('agents_service_qq'); // 代理 qq
                    $mq_server = getSysConfig('service_meiqia'); // 美洽客服
                    $new_url = getSysConfig('new_web_url'); // 最新网址
                    if(TPL_FILE_NAME=='wnsr'){
                        $agent_qq = getSysConfig('vns_agents_service_qq'); // 代理 qq
                        $mq_server = getSysConfig('vns_service_meiqia'); // 美洽客服
                        $new_url = getSysConfig('vns_new_web_url'); // 最新网址
                    }
                    ?>
                    <span class="top-nav">
                        <a href="javascript:window.open('http://wpa.qq.com/msgrd?v=3&uin=<?php echo $agent_qq;?>&site=web&menu=yes');">代理QQ: <?php echo $agent_qq;?> </a> |
                        <a href="<?php echo $new_url;?>" target="_blank">最新网址</a> |
                        <a href="javascript:void(0);" onclick="window.open('<?php echo $mq_server;?>','Service','location=no,status=no,width=600,height=500,toolbar=no,top=0,left=0,scrollbars=no,resizable=yes,personalbar=yes');" class="customer">在线客服</a> |
                        <a href="javascript:;" onclick="window.open('<?php echo $mq_server;?>','Service','location=no,status=no,width=600,height=500,toolbar=no,top=0,left=0,scrollbars=no,resizable=yes,personalbar=yes');">联系我们</a> |
                        <a href="chg_pw.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>" target="main">变更密码</a> |
                        <a href="logout.php?uid=<?php echo $uid?>" target="_top" ><?php echo $Mnu_Logout?></a>
                    </span>
                    <?php
                }
                ?>

            </div>
            <!-- 右侧底部 -->
            <div class="top_header_right_bottom <?php echo($level=='M')?'top_header_m':'' ?>">
                <div class="memu1">
                    <?php
                    if ($_SESSION['Status']==0){
                        ?>
                        <?php echo $Title?>
                        <?php
                        if($level=='D'){ // 普通代理才有
                            ?>
                            <a href="other_set/show_marquee.php?uid=<?php echo $uid?>&lv=MEM&langx=<?php echo $langx?>&level=<?php echo $level ?>" target="main">历史信息</a>
                            <a href="agents/ag_spread.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>" target="main" title="推广网址" >推广网址</a>
                            <a href="agents/mem_add.php?uid=<?php echo $uid?>&action=browse_add&lv=MEM&userlv=<?php echo $level?>&langx=<?php echo $langx?>" target="main"><?php echo $Mem_Add.$Rep_Member ?></a>
                            <?php
                        }
                        ?>
                        <?php
                    }
                    ?>
                </div>

            </div>
        </div>
        <div class="withdraw_chk_sound" style="display: none;"></div> <!-- 提款审核声音提示 -->
        <div class="deposit_sound" style="display: none;"></div> <!-- 入款声音提示 -->
        <div class="withdraw_sound" style="display: none;"></div> <!-- 出款声音提示 -->

    </div>
    <!-- 头部结束 -->

    <!-- 中间内容 -->
    <div class="main_content <?php echo ($level=='D'?'ag_main_content':'');?>">
        <iframe name="main" src="body_home.php?uid=<?php echo $uid?>&lv=<?php echo $level?>&langx=<?php echo $langx?>" width="100%" height="100%" frameborder="0"></iframe>
    </div>

</frameset>

<script type="text/javascript" src="../../js/agents/jquery.js"></script>
<script type="text/javascript" src="../../../js/agents/layer/layer.js"></script>
<script>

    window.clearInterval(setInter) ; // 清除定时器
    var uid = '<?php echo $uid ?>' ;
    var lv = '<?php echo $level ?>' ;
    var tplfilename = '<?php echo TPL_FILE_NAME; ?>' ;

    if(lv=='M'){ // 普通代理没有
        var setInter = setInterval(autoGetData,5000);
    }
    function subwin(){
        window.open("/app/agents/other_set/readme.php","readme","width=320,height=160,scrollbars=yes");
    }
    // ajax
    function createXMLHttpRequest(http) {
        if(window.ActiveXObject) {
            eval(http+" = new ActiveXObject(\"Microsoft.XMLHTTP\")");
        }
        else if(window.XMLHttpRequest) {
            eval(http+" = new XMLHttpRequest()");
        }
    }

    // 获取在线人数ajax
    function online(){
        createXMLHttpRequest("cHttp");
        cHttp.onreadystatechange = cChange;
        cHttp.open("post", "online/online.php?online=1", true);
        cHttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded;");
        cHttp.send(null);
        function cChange(){
            if (cHttp.readyState == 4) {
                if (cHttp.status == 200){
                    var cDoc = cHttp.responseText;
                    document.getElementById("online").innerHTML=cDoc;
                }
            }
        }
    }
    // 获取提款未审核笔数
    function withdrawNum(uid,lv){
        createXMLHttpRequest("wHttp");
        wHttp.onreadystatechange = wChange;
        wHttp.open("post", "800/withdraw_list_800.php?withdrawcheck=0&uid="+uid+"&lv="+lv, true);
        wHttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded;");
        wHttp.send(null);
        function wChange(){
            if (wHttp.readyState == 4) {
                if (wHttp.status == 200){
                    var wDoc = wHttp.responseText ;
                    wDoc =wDoc.split("{")[1].split("}");
                    wDoc="{"+wDoc[0]+"}";
                    wDoc=JSON.parse(wDoc) ;

                    var winchknum = wDoc.withdraw_num ; // 提款审核
                    var denum = wDoc.deposit_num_1 ; // 入款
                    var winum = wDoc.withdraw_num_1 ; // 出款
                    // console.log(wDoc.withdraw_num_1);
                    // console.log(typeof (wDoc.withdraw_num_1));
                    $('.withdraw_num').html(winchknum);
                    $('.withdraw_num_1').html(winum);
                    $('.deposit_num_1').html(denum);
                    if(winchknum>0){ // 提款审核
                        addCheckSound();
                    }else{
                        $('.wi_chk_sound').remove();
                    }
                    if(denum>0){ // 入款
                        addCheckSound('des');
                    }else{
                        $('.de_sound').remove();
                    }
                    if(winum>0){ // 出款
                        addCheckSound('wit');
                    }else{
                        $('.wi_sound').remove();
                    }


                }
            }
        }
    }
    /*
    * 获取客户回访电话数量
    * */
    function getPhoneCallNum() {
        $.ajax({
            type:"POST",
            url:"accounts/phoneCall.php",
            data:{
                phonecallcheck: 'call',
            },
            success:function(data) {
                if (data){
                    data = JSON.parse(data) ;
                    // console.log(typeof (data))
                    $('.phone_call_num').text(data.data.phonecall_num) ;
                }
            },
            error:function () {

            }
        })
    }

    /**
     * 获取会员未回复的消息
     */
    function getMemMsgNum() {
        $.ajax({
            type:"POST",
            url:"accounts/getMemMsgNum.php",
            data:{
                type: 'mem_msg_num',
            },
            success:function(data) {
                if (data){
                    data = JSON.parse(data) ;
                    // console.log(data)
                    $('.mem_msg_num').text(data.data.mem_msg_num) ;
                }
            },
            error:function () {

            }
        })
    }

    // 出入款声音
    function addCheckSound(dep) {
        var soundgile = '';
        var soundloop = 'loop';
        if(tplfilename=='suncity'){ // 太阳城独有
            soundgile = tplfilename+'/';
            soundloop = '';
        }
        var witchkstr = '<audio '+soundloop+' class="wi_chk_sound" autoplay> <source src="../../images/'+soundgile+'withdraw.mp3" type="audio/mp3" /> </audio>'; // 提款审核
        var destr = '<audio '+soundloop+' class="de_sound" autoplay> <source src="../../images/'+soundgile+'deposit.mp3" type="audio/mp3" /> </audio>'; // 入款
        var wistr = '<audio '+soundloop+' class="wi_sound" autoplay> <source src="../../images/'+soundgile+'withdraw.mp3" type="audio/mp3" /> </audio>'; // 出款
        if(dep =='des'){ // 入款
            $('.deposit_sound').html(destr);
        }else if(dep =='wit'){
            $('.withdraw_sound').html(wistr);
        }else{ // 提款审核
            $('.withdraw_chk_sound').html(witchkstr);
        }

    }

    // 需要定时请求任务
    function autoGetData() {
        if('<?php echo $num[0]?>'==1){
            online() ;
        }

        withdrawNum(uid,lv) ;
        getPhoneCallNum() ;
        getMemMsgNum();
    }

    // 下拉选单
    function headerDownSelect() {
        $('.hover_on').on('mouseover',function () {
            $(this).parent('li').siblings().find('.top_down_ul').show() ;

        }).on('mouseout',function () {
            $(this).parent('li').siblings().find('.top_down_ul').hide() ;
        });
        $('.top_down_ul').on('mouseover',function () {
            $(this).show() ;

        }).on('mouseout',function () {
            $(this).hide() ;
        });

    }
    headerDownSelect() ;


    function Go_Down_accounts(){
        // re=window.open("/app/agents/accounts/download.php","re","width=1020,height=950,status=no");
        //iframe窗
        layer.open({
            title:'滚球注单审核',
            type: 2,
            //shadeClose: true,
            shade: false,
            area: ['1000px', '600px'],
            fixed: false, //不固定
            maxmin: true, //开启最大化最小化按钮
            content: '/app/agents/accounts/download.php'
        });
    }
    function Go_Down_match(type){
        // Download=window.open("/app/agents/downdata_ra/download.php","download","width=1020,height=950,status=no");
        //iframe窗
        var url = '/app/agents/downdata_ra/download.php';
        if(type == 2){
            url = '/app/agents/downdata_ujl/download.php';
        }
        layer.open({
            title:'今日数据刷新',
            type: 2,
            //shadeClose: true,
            shade: false,
            area: ['1000px', '600px'],
            fixed: false, //不固定
            maxmin: true, //开启最大化最小化按钮
            content: url
        });
    }
    function Go_Down_future(type){
        // Download_future=window.open("/app/agents/downdata_ra/download_future.php","download_future","width=1020,height=950,status=no");
        //iframe窗
        var url = '/app/agents/downdata_ra/download_future.php';
        if(type == 2){
            url = '/app/agents/downdata_ujl/download_future.php';
        }
        layer.open({
            title:'早餐数据刷新',
            type: 2,
            //shadeClose: true,
            shade: false,
            area: ['1000px', '600px'],
            fixed: false, //不固定
            maxmin: true, //开启最大化最小化按钮
            content: url
        });
    }
    function Go_Down_score(){
        // score=window.open("/app/agents/score/download.php","score","width=1020,height=950,status=no");
        //iframe窗
        layer.open({
            title:'体育比分',
            type: 2,
            //shadeClose: true,
            shade: false,
            area: ['1000px', '600px'],
            fixed: false, //不固定
            maxmin: true, //开启最大化最小化按钮
            content: '/app/agents/score/download.php'
        });
    }
    function Go_Down_scoreYjl(){
        // score=window.open("/app/agents/score/downloadYjl.php","score","width=1020,height=950,status=no");
        //iframe窗
        layer.open({
            title:'体育比分',
            type: 2,
            //shadeClose: true,
            shade: false,
            area: ['1000px', '600px'],
            fixed: false, //不固定
            maxmin: true, //开启最大化最小化按钮
            content: '/app/agents/score/downloadYjl.php'
        });
    }
    function Go_Down_clearing(){
        // clearing=window.open("/app/agents/clearing/download.php","clearing","width=1020,height=950,status=no");
        //iframe窗
        layer.open({
            title:'体育结算',
            type: 2,
            //shadeClose: true,
            shade: false,
            area: ['1000px', '600px'],
            fixed: false, //不固定
            maxmin: true, //开启最大化最小化按钮
            content: '/app/agents/clearing/download.php'
        });
    }
    function Go_Down_data(){
        // uid=window.open("/app/agents/downdata_ra/uid/reload.php","uid","width=400,height=220,status=no");
        //iframe窗
        layer.open({
            title:'UID接收',
            type: 2,
            //shadeClose: true,
            shade: false,
            area: ['400px', '220px'],
            fixed: false, //不固定
            maxmin: true, //开启最大化最小化按钮
            content: '/app/agents/downdata_ra/uid/reload.php'
        });
    }
    function Go_Down_msdata(){
        //uid=window.open("/app/agents/downdata_ra/uid/reloadMatchScore.php","uid","width=400,height=220,status=no");
        //iframe窗
        layer.open({
            title:'比分UID接收',
            type: 2,
            //shadeClose: true,
            shade: false,
            area: ['400px', '220px'],
            fixed: false, //不固定
            maxmin: true, //开启最大化最小化按钮
            content: '/app/agents/downdata_ra/uid/reloadMatchScore.php'
        });

    }
    function Go_Down_url(){
        //uid=window.open("/app/agents/downdata_ra/uid/url.php","uid","width=800,height=600,status=no");

        //iframe窗
        layer.open({
            title:'皇冠刷水时间测试',
            type: 2,
            //shadeClose: true,
            shade: false,
            area: ['800px', '600px'],
            fixed: false, //不固定
            maxmin: true, //开启最大化最小化按钮
            content: '/app/agents/downdata_ra/uid/url.php'
        });

    }

</script>

</body>
<!--</noframes>-->
</html>
