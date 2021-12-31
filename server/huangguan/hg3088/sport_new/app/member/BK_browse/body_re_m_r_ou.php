<?php

$navstr ='<tr class="play_tr_nav">
            <th class="time"> 时间 </th>
            <th class="team"> 赛事 </th>
            <th class="h_1x2">独赢</th>
		    <th class="h_r">让球</th>
		    <th class="h_oe">大小</th>
		    <th class="h_ouhc" colspan="2">球队得分：大/小</th>
        </tr>';

// var_dump($oddsBackground);
?>

<!--SHOW LEGUAGE START-->
 <?php foreach($newDataArray as $key=>$match){?>
 <?php if($leagueNameCur!=$match['league']){?>
  <tr class="tr_league">
    <td colspan="8" class="b_hline">
        <table border="0" cellpadding="0" cellspacing="0">
	        <tbody>
		        <tr>
		        	<td class="legicon" onclick="parent.showLeg('<?php echo $match['league'];?>')">
				      <span id="<?php echo $match['league'];?>" name="<?php echo $match['league'];?>" class="showleg">
				        <span id="LegOpen"></span>
				       <!--展開聯盟-符號--><!--span id="LegOpen"></span-->
				       <!--收合聯盟-符號--><!--div id="LegClose"></div-->
				      </span>
		        	</td>
		        	<td onclick="parent.showLeg('<?php echo $match['league'];?>')" class="leg_bar"><?php echo $match['league'];?></td>
		        </tr>
	        </tbody>
        </table>
      </td>
  </tr>
<?php
        echo $navstr;
	$leagueNameCur = $match['league'];
} ?>
<!--SHOW LEGUAGE END-->
  <tr id="TR_<?php echo $match['dategh'];?>" >
    <td rowspan="2" class="b_cen">
    	<?php if($match['headShow']==1){?>
        <table border="0" cellpadding="0" cellspacing="0" class="rb_box rb_bk_box">
            <tbody><tr><td class="rb_time"><?php echo $match['team_info'];?></td></tr>
            <tr><td class="rb_score"><?php echo $match['score_info'];?></td></tr>
        	</tbody>
        </table>
        <?php }?>
    </td>
    <td rowspan="2" class="team_name">
      <span class='beforeLoveMb team_name_zd' ><?php echo $match['team_h'];?></span>
        <div class="hot_td" id="td_love_<?php echo $match['gnum_h'];?>"><span id="sp_<?php echo $match['dategh'];?>">
                <div id="<?php echo $match['dategh'];?>" class="fov_icon_out"
                        style="cursor: pointer; display: none;" title="我的最爱"
                        onclick="addShowLoveI('<?php echo $match['gnum_h'];?>','<?php echo $match['datetimelove'];?>','<?php echo $match['league'];?>','<?php echo $match['team_h'];?>','<?php echo $match['team_c'];?>'); "></div>
        </span></div>
      <table id="fav_box" width="100%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="hot_td"><span id="sp_1.9210002"><div id="1.9210002" class="fov_icon_out" style="cursor:hand;display:none;" title="我的最爱" onclick="addShowLoveI('10002','1.92','篮网球-ANZ超级联赛','中央脉搏','北方星'); "></div></span></td></tr></tbody></table> <br> <!--星星符號--><!--div class="fov_icon_on"></div--><!--星星符號-灰色--><!--div class="fov_icon_out"></div-->
      <span class="bk_team_c team_name_kd"><?php echo $match['team_c'];?></span><span class="more_txt bk_more">
                <?php  if($match['all']>1){  ?>
                    <a href="javascript:" onclick="parent.show_more(<?php echo $match['gid'];?>,event,'all');"><font class="total_color"><?php if($match['all']>1){ echo "所有玩法(".$match['all'].")"; }?></font>
                    </a>
                <?php  } ?>

        </span></td>
   	<td class="b_cen bc_h self_win_re">
        <?php  if($match['ior_MH']){  ?>
            <a class="<?php echo $oddsBackground[$match['gid']]['ior_MH'];?>" href="javascript://"
               onclick="parent.parent.mem_order.betOrder('BK','RM','<?php echo $match['bet_Url'];?>&type=H');"
               title="<?php echo $match['team_h'];?>">

                    <font ><?php echo $match['ior_MH'];?></font>

            </a>
        <?php  } ?>

    </td> <!-- 滚球独赢列 -->
    <td class="b_rig"><span class="con"><?php echo $match['ratio_mb_str'];?></span><span class="ratio">
             <?php  if($match['ior_RH']){  ?>
                 <a class="<?php echo $oddsBackground[$match['gid']]['ior_RH'];?>" href="javascript://"
                    onclick="parent.parent.mem_order.betOrder('BK','RE','<?php echo $match['bet_Url'];?>&type=H');"
                    title="<?php echo $match['team_h'];?>">

                         <font ><?php echo $match['ior_RH'];?></font>

            </a>
             <?php  } ?>

        </span></td>
    <td class="b_rig"><span class="con"><?php if($match['ior_OUH']>0){ echo $match['ratio_o_str']; } ?></span><span class="ratio">
              <?php  if($match['ior_OUH']){  ?>
                  <a class="<?php echo $oddsBackground[$match['gid']]['ior_OUH'];?>" href="javascript://"
                     onclick="parent.parent.mem_order.betOrder('BK','ROU','<?php echo $match['bet_Url'];?>&type=C');"
                     title="大">

                          <font ><?php echo $match['ior_OUH'];?></font>
            </a>
              <?php  } ?>

        </span></td>
    <td class="b_rig "> <!-- bg_light_bk -->
        <span class="con"><?php if($match['ior_OUHO']>0){ echo  "<font class='text_green'>大</font>";}?><?php echo $match['ratio_ouho_str'];?></span><span class="ratio">
              <?php  if($match['ior_OUHO']){  ?>
                  <a class="<?php echo $oddsBackground[$match['gid']]['ior_OUHO'];?>" href="javascript://"
                     onclick="parent.parent.mem_order.betOrder('BK','ROUH','<?php echo $match['bet_Url'];?>&wtype=ROUH&type=O');"
                     title="大">

                          <font ><?php echo $match['ior_OUHO'];?></font>

            </a>
              <?php  } ?>

        </span></td> <!-- 滚球得分大小主队大-->
    <td class="b_rig"> <!-- bg_light_bk -->
        <span class="con"><?php if($match['ior_OUHO']>0){ echo  "<font class='text_brown'>小</font>";}?><?php echo $match['ratio_ouhu_str'];?></span><span class="ratio">
              <?php  if($match['ior_OUHU']){  ?>
                  <a class="<?php echo $oddsBackground[$match['gid']]['ior_OUHU'];?>" href="javascript://"
                     onclick="parent.parent.mem_order.betOrder('BK','ROUH','<?php echo $match['bet_Url'];?>&wtype=ROUH&type=U');"
                     title="小">

                          <font ><?php echo $match['ior_OUHU'];?></font>

            </a>
              <?php  } ?>

        </span></td> <!-- 滚球得分大小主队小-->
  </tr>
  <tr id="TR1_<?php echo $match['gnum_h'];?>" >
    <td class="b_cen bc_h self_win_re">
        <?php  if($match['ior_MC']){  ?>
            <a class="<?php echo $oddsBackground[$match['gid']]['ior_MC'];?>" href="javascript://"
               onclick="parent.parent.mem_order.betOrder('BK','RM','<?php echo $match['bet_Url'];?>&type=C');"
               title="<?php echo $match['team_c'];?>">

                    <font ><?php echo $match['ior_MC'];?></font>

            </a>
        <?php  } ?>

    </td> <!-- 滚球独赢列 -->
    <td class="b_rig"><span class="con"><?php echo $match['ratio_tg_str'];?></span><span class="ratio">
             <?php  if($match['ior_RC']){  ?>
                 <a class="<?php echo $oddsBackground[$match['gid']]['ior_RC'];?>" href="javascript://"
                    onclick="parent.parent.mem_order.betOrder('BK','RE','<?php echo $match['bet_Url'];?>&type=C');"
                    title="<?php echo $match['team_c'];?>">

                         <font ><?php echo $match['ior_RC'];?></font>

            </a>
             <?php  } ?>

        </span></td>
    <td class="b_rig"><span class="con"><?php if($match['ior_OUC']>0){ echo $match['ratio_u_str']; } ?></span><span class="ratio">
              <?php  if($match['ior_OUC']){  ?>
                  <a class="<?php echo $oddsBackground[$match['gid']]['ior_OUC'];?>" href="javascript://"
                     onclick="parent.parent.mem_order.betOrder('BK','ROU','<?php echo $match['bet_Url'];?>&type=H');"
                     title="小">

                          <font ><?php echo $match['ior_OUC'];?></font>

            </a>
              <?php  } ?>

        </span></td>
    <td class="b_rig"> <!-- bg_dark_bk -->
        <span class="con"><?php if($match['ior_OUHO']>0){ echo  "<font class='text_green'>大</font>";}?><?php echo $match['ratio_ouco_str'];?></span><span class="ratio">
                         <?php  if($match['ior_OUCO']){  ?>
                             <a class="<?php echo $oddsBackground[$match['gid']]['ior_OUCO'];?>" href="javascript://"
                                onclick="parent.parent.mem_order.betOrder('BK','ROUC','<?php echo $match['bet_Url'];?>&wtype=ROUC&type=O');"
                                title="大">

                                     <font ><?php echo $match['ior_OUCO'];?></font>

                            </a>
                         <?php  } ?>

        </span></td> <!-- 滚球得分大小客队大-->
    <td class="b_rig"> <!-- bg_dark_bk -->
        <span class="con"><?php if($match['ior_OUHO']>0){ echo  "<font class='text_brown'>小</font>";}?><?php echo $match['ratio_oucu_str'];?></span><span class="ratio">
             <?php  if($match['ior_OUCU']){  ?>
                 <a class="<?php echo $oddsBackground[$match['gid']]['ior_OUCU'];?>" href="javascript://"
                    onclick="parent.parent.mem_order.betOrder('BK','ROUC','<?php echo $match['bet_Url'];?>&wtype=ROUC&type=U');"
                    title="小">

                         <font ><?php echo $match['ior_OUCU'];?></font>

            </a>
             <?php  } ?>

        </span></td> <!-- 滚球得分大小客队小-->
  </tr>
<?php }?>
