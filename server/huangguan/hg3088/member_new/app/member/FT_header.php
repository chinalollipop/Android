<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "./include/address.mem.php";
include "./include/config.inc.php";
require ("./include/curl_http.php");
include "./ip.php";
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>window.open('".BROWSER_IP."/tpl/logout_warn.html','_top')</script>";
    exit;
}

$uid=$_REQUEST['uid'];
$mtype=$_REQUEST['mtype'];
$showtype=$_REQUEST['showtype'];

$langx=$_SESSION['langx'];
$userName=$_SESSION['UserName'];
$userid = $_SESSION['userid'];

require ("./include/traditional.$langx.inc.php");

$redisObj = new Ciredis();
$curl = new Curl_HTTP_Client();
$username=$_SESSION['UserName'];

switch ($showtype){
case "future": // 早盘
	$style='HFU';
	$Mtype="early";
    $rtype='r' ; // 足球
    $bk_rtype ='all' ; // 篮球
    $gamefile ='future' ; // 访问文件路径
    $click_bg='early';
    $header_showtype='future';
	break;
case "rb": // 滚球
    $style='HFT';
    $Mtype="rb";
    $rtype='re' ; // 足球滚球
    $bk_rtype ='re' ; // 篮球
    $re_r='r'; //只有滚球有
    $gamefile ='browse' ; // 访问文件路径
    $click_bg='today';
    $header_showtype='';
    break;
case "":
case "today":
	$style='HFT';
	$Mtype="today";
    $rtype='r' ;  // 足球
    $bk_rtype ='all' ; // 篮球
    $gamefile ='browse' ;// 访问文件路径
    $click_bg='today';
    $header_showtype='';
	break;
}

$showtime = date("Y/m/d H:i:s");
// 默认查询当天的数据
$m_date=date('Y-m-d');

//收件箱未读消息的数量

$dcRedisObj = new Ciredis('datacenter');
$datetimeCur = date('Y-m-d H:i:s',time());
$sqlMessageCur = "select id from ".DBPREFIX."web_message_data where ( UserName='".$userName."' or UserName='ALL' ) and BeginTime < '$datetimeCur'  and EndTime > '$datetimeCur' ";
$resultMessageCur = mysqli_query($dbLink,$sqlMessageCur);
$numMessageCur = mysqli_num_rows($resultMessageCur);
if( $numMessageCur > 0 ){
    $readCountkey = 'Message_readinbox_'.$userid;
    $userMailRead = $redisObj->getSimpleOne($readCountkey);
    $userMailReadArr=[];
    if( strlen($userMailRead)>0 ){
        $userMailReadArr = explode(':',$userMailRead);
    }
    while ($rowUnRead = mysqli_fetch_assoc($resultMessageCur)) {
        if(in_array($rowUnRead['id'],$userMailReadArr)){ $numMessageCur = $numMessageCur-1; }
    }
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
echo "<script>if(self == top) parent.location='".BROWSER_IP."'\n;</script>";
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<link rel="stylesheet" href="../../style/member/mem_header_ft_cn.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <script type="text/javascript" src="../../js/jquery.js"></script><script type="text/javascript" src="../../js/common.js?v=<?php echo AUTOVER; ?>"></script><script type="text/javascript" src="../../js/header.js?v=<?php echo AUTOVER; ?>"></script>

<script>
document.oncontextmenu=new Function("event.returnValue=false");
document.onselectstart=new Function("event.returnValue=false");

</script>

</head>
<body id="<?php echo $style ?>" class="bodyset" onLoad="SetRB('FT','<?php echo $uid?>');onloaded();" >
<div style="z-index:3000;float: left; display:none;">
	<iframe id="memOnline" name="memOnline" scrolling="NO" frameborder="NO" border="0" height="500" width="800" ></iframe>
</div>
<div id="container">
  <input type="hidden" id="uid" name="uid" value="<?php echo $uid?>">
  <input type="hidden" id="langx" name="langx" value="<?php echo $langx?>">

  <div id="header"><span><h1>&nbsp;</h1></span></div>
  <div id="welcome">
	<ul>
  <!--會員帳號-->

      <li class="name">您好, <strong id="userid"><?php if($_SESSION['Agents']=='demoguest'){ echo "试玩玩家"; }else{ echo $username; } ?></strong>
      	<div id="head_date"><span id="head_year"></span></div>
      </li>

      <li class="<?php echo  ($showtype =='rb')?'rb_on':'rb' ?>" id="rb_btn" >
          <span id="rbType"></span>
          <a onclick="chg_second_tip(this,'rb','<?php echo $uid?>','FT');chg_button_bg('FT','rb');chg_index(this,'<?php echo BROWSER_IP?>/app/member/FT_header.php?uid=<?php echo $uid?>&showtype=rb&langx=zh-cn&mtype=4','<?php echo BROWSER_IP?>/app/member/FT_browse/index.php?rtype=re&uid=<?php echo $uid?>&langx=zh-cn&mtype=4&showtype=rb',parent.FT_lid_type,'SI2','rb')"  target="body" id="rbyshow" style="display:;">滚球<span class="rb_sum"> (<span class="game_sum" id="RB_games">
            0
	  </span>)</span></a>
      </li>

        <li class="<?php echo ($showtype =='rb')?'today':'today_on' ?>" id="today_btn"><a onclick="chg_second_tip(this,'today','<?php echo $uid?>','FT');chg_button_bg('FT','today');chg_index(this,'<?php echo BROWSER_IP?>/app/member/FT_header.php?uid=<?php echo $uid ?>&showtype=<?php echo  $showtype ?>&langx=zh-cn&mtype=4','<?php echo BROWSER_IP?>/app/member/FT_browse/index.php?rtype=r&uid=<?php echo $uid ?>&langx=zh-cn&mtype=4&showtype=<?php echo $showtype;?>',parent.FT_lid_type,'SI2');" target="body" id="todayshow" style="display:;">今日赛事</a></li>
        <li class="early" id="early_btn"> <a onclick="chg_second_tip(this,'early','<?php echo $uid?>','FT');chg_button_bg('FT','early');chg_index(this,'<?php echo BROWSER_IP?>/app/member/FT_header.php?uid=<? echo $uid?>&showtype=future&langx=zh-cn&mtype=4','<?php echo BROWSER_IP?>/app/member/FT_future/index.php?rtype=r&uid=<?php echo $uid?>&langx=zh-cn&mtype=4&showtype=future',parent.FU_lid_type,'SI2','future')" target="body" id="earlyshow" style="cursor:hand;" >早盘</a></li>

        <li class="early"><a href="../../app/member/zrsx/index_<?php echo TPL_FILE_NAME;?>.php?uid=<?php echo $uid;?>" target="body">真人视讯</a></li>
        <li class="early"><a href="account/message.php?uid=<?php echo $uid;?>&langx=<?php echo $langx ?>" target="body">消息中心(<span id="message_num" style="color:#c00;font-weight:800;"><?php echo $numMessageCur; ?></span>)</a></li>
        <?php if($_SESSION['Agents']!='demoguest'){?>
        <li class="early_on"><a href="<?php echo BROWSER_IP?>/app/member/onlinepay/deposit_withdraw.php?uid=<?php echo $uid ?>&langx=<?php echo $langx ?>&username=<?php echo $username?>" target="body" >存取款中心</a></li>
        <?php }?>
        <li class="early"><a href="<?php echo BROWSER_IP?>/app/member/onlinepay/record.php?uid=<?php echo $uid ?>&langx=<?php echo $langx ?>&username=<?php echo $username?>&thistype=S&date_start=<?php echo $m_date?>&date_end=<?php echo $m_date?>" target="body" >账号历史记录</a></li>
    	<li style="margin-left:2px;width:28px;height:29px;background: url(../../images/member/live_tv_m.gif) 0 0 no-repeat;"><a href="#" onclick="showOpenLive();">&nbsp;</a></li>
    </ul>
  </div>
  <div id="nav">
    <ul class="level1">

      <li class="ft"><span class="ball football_ball">
              <a id="ft_link" class="second_title_active" href="javascript:void(0);" onclick="chg_button_bg('FT','<?php echo $click_bg?>','FT','<?php echo $uid ?>');chg_index(this,'<?php echo BROWSER_IP?>/app/member/FT_header.php?uid=<?php echo $uid?>&showtype=<?php echo  $header_showtype ?>&langx=zh-cn&mtype=4','<?php echo BROWSER_IP?>/app/member/FT_<?php echo $gamefile?>/index.php?rtype=r&uid=<?php echo $uid?>&langx=zh-cn&mtype=4&showtype=<?php echo $showtype?>',parent.FT_lid_type,'SI2');return false"  target="body" >足球
(<strong class="game_sum" id="FT_games"><?php echo $dcRedisObj->getSimpleOne("FT_Today_Num");?></strong>)</a></span></li>
      <?php
      if(strpos($_SESSION['gameSwitch'],'|')>0){
			$gameArr=explode('|',$_SESSION['gameSwitch']);	
      }else{
      		if(strlen($_SESSION['gameSwitch'])>0){
      			$gameArr[]=$_SESSION['gameSwitch'];	
      		}else{
      			$gameArr=array();	
      		}
      }
      if(!in_array('BK',$gameArr)){ 
      ?>
      <li class="bk">
          <span class="ball">
               <a id="bk_link" href="javascript:void(0);" onclick="chg_button_bg('BK','<?php echo $click_bg?>','BK','<?php echo $uid ?>');chg_index(this,'<?php echo BROWSER_IP?>/app/member/BK_header.php?uid=<?php echo $uid?>&showtype=<?php echo  $header_showtype ?>&langx=zh-cn&mtype=4','<?php echo BROWSER_IP?>/app/member/BK_<?php echo $gamefile?>/index.php?rtype=all&uid=<?php echo $uid?>&langx=zh-cn&mtype=4',parent.BK_lid_type,'SI2');return false" target="body" >篮球
                  <span class="ball_nf"><img src="/images/member/head_ball_nf.gif" class="nf_icon"></span> 美式足球 (<strong class="game_sum" id="BK_games"><?php echo $dcRedisObj->getSimpleOne("BK_Today_Num");?></strong>)
              </a>
          </span>
      </li>
	<?php } ?>
      <li class="tn">
          <span class="ball">
<!--              <a href="javascript:chg_button_bg('TN','--><?php //echo $click_bg?><!--');chg_index('--><?php //echo BROWSER_IP?><!--/app/member/TN_header.php?uid=--><?php //echo $uid?><!--&showtype=--><?php //echo  $header_showtype ?><!--&langx=zh-cn&mtype=4','--><?php //echo BROWSER_IP?><!--/app/member/TN_--><?php //echo $gamefile?><!--/index.php?rtype=r&uid=--><?php //echo $uid?><!--&langx=zh-cn&mtype=4',parent.TN_lid_type,'SI2');" target="body_browse" >网球-->
<!--                  (<strong class="game_sum" id="TN_games">--><?php //echo $cou_num?><!--</strong>)</a>-->
              <a href="static_page.php?gamename=TN&showtype=<?php echo $showtype ?>"  target="body" >网球
                  (<strong class="game_sum" id="TN_games">0</strong>)
              </a>
          </span>
      </li>

      <li class="vb">
          <span class="ball">
<!--              <a href="javascript:chg_button_bg('VB','--><?php //echo $click_bg?><!--');chg_index('--><?php //echo BROWSER_IP?><!--/app/member/VB_header.php?uid=--><?php //echo $uid?><!--&showtype=--><?php //echo  $header_showtype ?><!--&langx=zh-cn&mtype=4','--><?php //echo BROWSER_IP?><!--/app/member/VB_--><?php //echo $gamefile?><!--/index.php?rtype=r&uid=--><?php //echo $uid?><!--&langx=zh-cn&mtype=4',parent.VB_lid_type,'SI2');" target="body_browse" >排球-->
<!--                  (<strong class="game_sum" id="VB_games">--><?php //echo $cou_num?><!--</strong>)</a>-->
                <a href="static_page.php?gamename=VB&showtype=<?php echo $showtype ?>"  target="body" >排球
                  (<strong class="game_sum" id="VB_games">0</strong>)
              </a>
          </span>
      </li>

	  <li class="bs"><span class="ball">

<!--              <a href="javascript:chg_button_bg('BS','--><?php //echo $click_bg?><!--');chg_index('--><?php //echo BROWSER_IP?><!--/app/member/BS_header.php?uid=--><?php //echo $uid?><!--&showtype=--><?php //echo  $header_showtype ?><!--&langx=zh-cn&mtype=4','--><?php //echo BROWSER_IP?><!--/app/member/BS_--><?php //echo $gamefile?><!--/index.php?rtype=r&uid=--><?php //echo $uid?><!--&langx=zh-cn&mtype=4',parent.BS_lid_type,'SI2');" target="body_browse" >棒球-->
<!--                  (<strong class="game_sum" id="BS_games">--><?php //echo $cou_num?><!--</strong>)</a>-->
               <a href="static_page.php?gamename=BS&showtype=<?php echo $showtype ?>"  target="body" >棒球
                  (<strong class="game_sum" id="BS_games">0</strong>)
              </a>
          </span>
      </li>
      <li class="op"><span class="ball">
<!--              <a href="javascript:chg_button_bg('OP','--><?php //echo $click_bg?><!--');chg_index('--><?php //echo BROWSER_IP?><!--/app/member/OP_header.php?uid=--><?php //echo $uid?><!--&showtype=--><?php //echo  $header_showtype ?><!--&langx=zh-cn&mtype=4','--><?php //echo BROWSER_IP?><!--/app/member/OP_--><?php //echo $gamefile?><!--/index.php?rtype=r&uid=--><?php //echo $uid?><!--&langx=zh-cn&mtype=4',parent.OP_lid_type,'SI2');" target="body_browse" >其他-->
<!--                  (<strong class="game_sum" id="OP_games">--><?php //echo $cou_num?><!--</strong>)</a>-->
               <a href="static_page.php?gamename=OP&showtype=<?php echo $showtype ?>"  target="body" >其他
                  (<strong class="game_sum" id="OP_games">0</strong>)
              </a>
          </span>
      </li>

    </ul>
      </li>      
    </ul>
  </div>
  <div id="type">
    <ul>
        <?php if($showtype=='future') { // 早盘 ?>
            <li class="re"><a id="re_class" class="type_on" href="javascript:void(0);" onClick="chg_button_bg('FT','<?php echo $Mtype?>');chg_type('<?php echo BROWSER_IP?>/app/member/FT_future/index.php?rtype=r&uid=<?php echo $uid?>&langx=zh-cn&mtype=4&showtype=<?php echo  $showtype ?>',parent.FU_lid_type,'SI2');chg_type_class('re_class');return false">独赢 ＆ 让球 ＆ 大小 ＆ 单 / 双</a></li>
            <li class="pd"><a id="pd_class" class="type_out" href="javascript:void(0);" onClick="chg_button_bg('FT','<?php echo $Mtype?>');chg_type('<?php echo BROWSER_IP?>/app/member/FT_future/index.php?rtype=<?php echo $re_r?>pd&uid=<?php echo $uid?>&langx=zh-cn&mtype=4&showtype=<?php echo  $showtype ?>',parent.FU_lid_type,'SI2');chg_type_class('pd_class');return false">波胆</a></li>
            <!--<li class="to"><a id="to_class" class="type_out" href="javascript:void(0);" onClick="chg_button_bg('FT','<?php /*echo $Mtype*/?>');chg_type('<?php /*echo BROWSER_IP*/?>/app/member/FT_future/index.php?rtype=<?php /*echo $re_r*/?>t&uid=<?php /*echo $uid*/?>&langx=zh-cn&mtype=4&showtype=<?php /*echo  $showtype */?>',parent.FU_lid_type,'SI2');chg_type_class('to_class');return false">总入球</a></li>
            <li class="hf"><a id="hf_class" class="type_out" href="javascript:void(0);" onClick="chg_button_bg('FT','<?php /*echo $Mtype*/?>');chg_type('<?php /*echo BROWSER_IP*/?>/app/member/FT_future/index.php?rtype=<?php /*echo $re_r*/?>f&uid=<?php /*echo $uid*/?>&langx=zh-cn&mtype=4&showtype=<?php /*echo  $showtype */?>',parent.FU_lid_type,'SI2');chg_type_class('hf_class');return false">半场 / 全场</a></li>-->

        <?php  }else{ ?>
            <li class="re"><a id="re_class" class="type_on" href="javascript:void(0);" onClick="chg_button_bg('FT','<?php echo $Mtype?>');chg_type('<?php echo BROWSER_IP?>/app/member/FT_browse/index.php?rtype=<?php echo $rtype?>&uid=<?php echo $uid?>&langx=zh-cn&mtype=4&showtype=<?php echo  $showtype ?>',parent.FT_lid_type,'SI2');chg_type_class('re_class');return false">独赢 ＆ 让球 ＆ 大小 ＆ 单 / 双</a></li>
            <li class="pd"><a id="pd_class" class="type_out" href="javascript:void(0);" onClick="chg_button_bg('FT','<?php echo $Mtype?>');chg_type('<?php echo BROWSER_IP?>/app/member/FT_browse/index.php?rtype=<?php echo $re_r?>pd&uid=<?php echo $uid?>&langx=zh-cn&mtype=4&showtype=<?php echo  $showtype ?>',parent.FT_lid_type,'SI2');chg_type_class('pd_class');return false">波胆</a></li>
            <!--<li class="to"><a id="to_class" class="type_out" href="javascript:void(0);" onClick="chg_button_bg('FT','<?php /*echo $Mtype*/?>');chg_type('<?php /*echo BROWSER_IP*/?>/app/member/FT_browse/index.php?rtype=<?php /*echo $re_r*/?>t&uid=<?php /*echo $uid*/?>&langx=zh-cn&mtype=4&showtype=<?php /*echo  $showtype */?>',parent.FT_lid_type,'SI2');chg_type_class('to_class');return false">总入球</a></li>
            <li class="hf"><a id="hf_class" class="type_out" href="javascript:void(0);" onClick="chg_button_bg('FT','<?php /*echo $Mtype*/?>');chg_type('<?php /*echo BROWSER_IP*/?>/app/member/FT_browse/index.php?rtype=<?php /*echo $re_r*/?>f&uid=<?php /*echo $uid*/?>&langx=zh-cn&mtype=4&showtype=<?php /*echo  $showtype */?>',parent.FT_lid_type,'SI2');chg_type_class('hf_class');return false">半场 / 全场</a></li>-->

        <?php  } ?>

          <?php if($showtype !='rb'){ ?>
          <li class="hp3"><a id="hp3_class" class="type_out" href="javascript:void(0);" onClick="chg_button_bg('FT','<?php echo $Mtype?>');chg_type('<?php echo BROWSER_IP?>/app/member/FT_browse/index.php?rtype=p3&uid=<?php echo $uid?>&langx=zh-cn&mtype=4&showtype=<?php echo  $showtype ?>',parent.FT_lid_type,'SI2');chg_type_class('hp3_class');return false"  >综合过关</a></li>
          <li class="fs"><a id="fs_class" class="type_out" href="javascript:void(0);" onClick="chg_button_bg('FT','<?php echo $Mtype?>');chg_type('<?php echo BROWSER_IP?>/app/member/browse_FS/loadgame_R.php?uid=<?php echo $uid?>&langx=zh-cn&FStype=FT&mtype=4',parent.FT_lid_type,'SI2');chg_type_class('fs_class');parent.sel_league='';parent.sel_area='';return false " >冠军</a></li>
 	      <?php } ?>
    </ul>
  </div>
  
  
</div>
<!--input  id=downloadBTN type=button style="width:80px;visibility:'hidden'"  onclick="onclickDown()" value="下載"-->
    <!-- 头部菜单 -->
    <div id="top_back" class="header_menu">

  </div>   


<div id="mem_box">
  <div id="mem_main"><span class="his"><a href="<?php echo BROWSER_IP?>/app/member/history/history_data.php?uid=<?php echo $uid?>&langx=zh-cn" target="body">帐户历史</a></span> | <span class="wag"><a href="<?php echo BROWSER_IP?>/app/member/today/today_wagers.php?uid=<?php echo $uid?>&langx=zh-cn" target="body">交易狀況</a></span></div>
  <div id="credit_main"><span id="credit"></span><input type="button" class="re_credit" onClick="javascript:reloadCrditFunction();"></div>
</div>


<!--幫助視窗-->
<div id="qaView" style="display:none;" class="qaView">
    <!--div class="leg_head" onMousedown="initializedragie('legView')"></div-->
    <div><iframe id="qaFrame" frameborder="no" border="0" allowtransparency="true"></iframe></div>
    <div class="qa_foot"></div>
</div>

<iframe id="reloadPHP" name="reloadPHP"  width="0" height="0"></iframe>
<iframe id="reloadPHP" name="reloadPHP1"  width="0" height="0"></iframe>
<script type="text/javascript" language="javascript">
    reloadCrditFunction();
    setInterval("headerShowTimer('#head_date')",1000);
    setInterval("reloadCrditFunction()",8000);
    var gametype = 'FT';
    var br_ip = <?php echo  '\''.BROWSER_IP.'\'' ?> ; var u_id = <?php echo '\''.$uid.'\'' ?> ; var u_lang = <?php echo '\''.$langx.'\'' ?> ; var u_type = <?php echo '\''.$showtype.'\'' ?> ; var u_name = <?php echo '\''.$username.'\'' ?> ;setHeaderAction(br_ip,u_id) ;

    addPublicList(br_ip,gametype,u_id) ;
 // 放大直播视频
    function showOpenLive() {
        var url = "live/live_max.php?langx="+top.langx+"&uid="+top.uid+"&liveid="+top.liveid ;
        top.tvwin = window.open(url,"win","width=805,height=526,top=0,left=0,status=no,toolbar=no,scrollbars=yes,resizable=no,personalbar=no");
    }
</script>
</body>
</html>
