<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");          
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include "../include/address.mem.php";
require ("../include/config.inc.php");

$uid=$_REQUEST['uid'];
$langx=$_SESSION['langx'];
$liveid=$_REQUEST['liveid'];
$eventid=$_REQUEST['eventid'];
$gtype=$_REQUEST['gtype'];
require ("../include/traditional.$langx.inc.php");

$m_date=date('Y-m-d');
$date1=date('Y-m-d',time());
$date2=date('Y-m-d',time()+1*24*60*60);
$date3=date('Y-m-d',time()+2*24*60*60);
$date4=date('Y-m-d',time()+3*24*60*60);
$date5=date('Y-m-d',time()+4*24*60*60);
$date6=date('Y-m-d',time()+5*24*60*60);
$date7=date('Y-m-d',time()+6*24*60*60);

$sql="select $mb_team as MB_Team,$tg_team as TG_Team,$m_league as M_League,Eventid,Hot,Play from ".DBPREFIX."match_sports where Type='$gtype' and `M_Date` ='$m_date' and Eventid='$eventid' and Play='Y' order by MID";
$result=mysqli_query($dbLink,$sql);
$row=mysqli_fetch_assoc($result);
?>
<script>
var uid = '<?php echo $uid?>';
var langx = '<?php echo $langx?>';
var mtvid = '97CBC6CEC39DCC9Cc9cdcecbc29dcdc6c9ccm16';
var eventid = '<?php echo $eventid?>';
var eventlive='' ; // 2018 新增
var videoData = '';
var GameDate = new Array('<?php echo $date1?>','<?php echo $date2?>','<?php echo $date3?>','<?php echo $date4?>','<?php echo $date5?>','<?php echo $date6?>','<?php echo $date7?>');
var o_path = '<?php echo BROWSER_IP?>/app/member/select.php?uid=<?php echo $uid?>&langx=<?php echo $langx?>&live=Live';
<?php
if ($row['Play']=='Y'){
?>
videoData = '<?php echo $row['Eventid']?>,<?php echo $row['MB_Team']?>,<?php echo $row['TG_Team']?>,<?php echo $row['M_League']?>,<?php echo $row['MB_Color ']?>,<?php echo $row['TG_Color ']?>';
<?php
}
?>
</script>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>live TV</title>
<link rel="stylesheet" href="/style/member/mem_live.css?v=<?php echo AUTOVER; ?>" type="text/css">
<SCRIPT language="javascript" src="/js/live.js?v=<?php echo AUTOVER; ?>"></SCRIPT>
<SCRIPT language="javascript" src="/js/<?php echo $langx?>.js?v=<?php echo AUTOVER; ?>"></SCRIPT>
</head>
<body onLoad="onload();" scrolling="no" onUnload="unLoad();" onSelectStart="self.event.returnValue=false" oncontextmenu="self.event.returnValue=false;window.event.returnValue=false;">
<!--Head-->
    <div id="top_div" class="head">
        <span id="DemoLink" onClick="ShowVideo();" class="demo"><a href="#">样本</a></span>
        <span onClick="GoToQAPage();" class="FQ"><a href="#">常见问题</a></span>	
    </div>
    <!--Demo img -->
    <div valign="top" id="DemoImgLayer" style="display:block">
		<div class="demoIMG"></div>	
     </div>	
    <!--Flash Start-->
      <div id="FlahLayer" style="display:none" class="FlahLayer">
          <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #FC0;">
              <tr>
                  <td align="center" valign="top" width="495" height="85" background="/images/member/live_Ltop.gif" style="background-repeat:no-repeat">
                      <img id="alone_btn" src="/images/member/live_Obut.gif" width="33" height="18" onClick="independent();" style="cursor:hand" class="Obut">

                      <div id="video_msg" style="display:none;"> <font id="league" class="mag_league"></font>
                          <table border="0" cellspacing="0" cellpadding="0">
                              <tr>

                                  <td valign="top"><img id="team_h" style="display:none;"></td>
                                  <td id="team" class="mag_team">&nbsp;</td>
                                  <td valign="top"><img id="team_c" style="display:none;"></td>
                              </tr>
                          </table>
                      </div>
                      <div id="demo_msg" style="display:none;"></div>
                  </td>
              </tr>
              <tr>
                  <td>
                      <div id="videoFrame" style="display:none;" class="dome_L"></div>
                      <div class="dome_L">
                          <iframe id="DefLive" width="480" height="408" src="" scrolling="no" frameborder="0" framespacing="0" cellspacing="0" cellpadding="0" style="display:none;"></iframe>
                      </div>
                  </td>
              </tr>

          </table>
      </div>
	<!--Flash End-->


	<!-- 右选单-日程表 Start -->
    <div id="main">
    
      <div id="menu">
        <div class="list_on" id="TVbut" onClick="go_livepage();">直播日程表</div>

        <div class="bet_out" id="BEbut" onClick="go_betpage();">立即投注</div>
      </div>
        <!-- 选择日期 -->
      <div id="sel_game">
            <span id="game_type"></span>
            <span id="date_list"></span>
            <span class="re_time"><span id="timer_str"></span><!-- 更新秒数 --></span>
        </div>

        
    
        
        <!-- 有赛事列表 外框 -->
        <div id="even_box" class="even_box">
            <!-- 无赛事列表 -->
            <div id="even_none" class="even_none" style="display:none">- 目前暂无现场赛事 -</div>
            <!-- 列表 -->
            <div id="even_list" class="even_list" style="display:none">
                <!--第1笔投注 -->
                <div id="showlayers" class="game_list"></div>

            </div>
            <!-- 列表 End-->
        </div>
        <!-- 有赛事列表 外框 End-->  
        </div>
 <!-- 右选单 End --> 
<iframe id="reloadPHP" name="reloadPHP" src="/ok.html" width="0" height="0" frameborder="NO" border="0"></iframe>
<iframe id="reloadgame" name="reloadgame" src="/ok.html" width="0" height="0" frameborder="NO" border="0"></iframe>
<iframe id="registLive" name="registLive" src="/ok.html" width="0" height="0" frameborder="NO" border="0"></iframe>

 
 <!-- ---------- game list ----------- -->
<div id="tb_layer" style="display:none">
       <table border="0" cellspacing="0" cellpadding="0" id="even_detail" class="even_detail_1">
		<tr>
			<td class="even_date">*GAMEDATE*</td>
			</tr>
            *GAMELIST*
            <!--td valign="top">
					
			</td>
		</tr-->
	</table>

</div>
<div id="tr_layer" style="display:none">
	<!--tr>
		<td>
			<div-->

              <tr id="list_color"  *list_color*>
                <td class="even_info">
                	<div class="even_time">*TIME* <span class="even_type">*GTYPE*</span><span id="tv_icon" class="tv_icon" onClick="OpenTV('*ID*');" *STYLE*></span></div>
                    <div class="even_leag">*LEAGUE*<br>
              		  <span class="even_team">*TEAMH*&nbsp;vs.&nbsp;*TEAMC*</span></div>                     
                </td>

              </tr>
              <!--tr>
                <td class="even_leag">
               *LEAGUE*<br>
                <span class="even_team">*TEAMH*&nbsp;vs.&nbsp;*TEAMC*</span>
                </td>
              </tr-->
				
			</div>
		<!--/td>
	</tr-->
</div>
 
 

<!-- 右选单-立即投注 Start -->
<div id="main_bet" style="display:none">
  <div id="menu_bet">
    <div class="list_out" id="TVbut" onClick="go_livepage();">直播日程表</div>

    <div class="bet_on" id="BEbut" onClick="go_betpage();">立即投注</div>
  </div>

    <!-- 无赛下注画面 -->
    <div id="bet_none" class="bet_none" style="display:none">
    	<table border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td>-</td>

            <td>您所选的赛事暂时无法投注</td>
            <td>-</td>
          </tr>
        </table>
  	</div>
    


    <!-- 盘面 外框 Start-->
    <div id="bet_box" class="bet_box" >
    
    	<!-- 注解 -->

        <!--div id="bet_ps" class="bet_ps" style="display:none">请点选下列赔率并加到您的投注单中</div-->
    	
    	
        <!-- 盘面 Start -->
      	<div id="bet_mem" class="bet_mem">
      	
            <div id="bet_div" style="display:none">
                <iframe id="bet_order_frame" src="/ok.html" scrolling="NO" frameborder="NO" border="0"></iframe>		
            </div>
            <div>
                <iframe id="Live_mem" name="Live_mem" src="/ok.html" scrolling="NO" frameborder="NO" border="0" width="240"></iframe>
            </div>  		
        </div>

        <!-- 盘面 End -->	
            
        	<!-- <td align="right" valign="top"  id="table_Live_order"> -->
				<!--<table border="0" cellspacing="0" cellpadding="0">-->
				 	<!--<tr><td><iframe id="mem_order" src="/ok.html" frameborder=0 width="300"scrolling="NO"  class="Live_order" ></iframe></td></tr> -->
					<!--<tr><td><iframe id="Live_mem" src="/ok.html" scrolling="NO" frameborder="NO" border="0"></iframe></td></tr>-->
				<!--</table>-->
			<!--</td>-->
        

    
    </div>
    <!-- 盘面 外框 End -->


 
</div>
 <!-- 右选单--立即投注 End --> 
 

</body>
</html>

