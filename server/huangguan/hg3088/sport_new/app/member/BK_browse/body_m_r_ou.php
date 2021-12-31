<?php

$navstr ='<tr class="play_tr_nav">
            <th class="time"> 时间 </th>
            <th class="team"> 赛事 </th>
		    <th class="h_1x2">独赢</th>
		    <th class="h_r">让球</th>
		    <th class="h_ou">大小</th>
		    <th class="h_oe">单/双</th>
		    <th class="h_oe" colspan="2">球队得分：大/小</th>
        </tr>';

?>

<!--SHOW LEGUAGE START-->
 <?php foreach($newDataArray as $key=>$match){?>
 <?php if($leagueNameCur!=$match['league']){?>
<tr class="tr_league">
	<td colspan="10" class="b_hline">
		<table border="0" cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
					<td class="legicon" onclick="parent.showLeg('<?php echo $match['league'];?>')">
				      	<span id="<?php echo $match['league'];?>" class="showleg">
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
        <table><tbody><tr><td class="today_time"><?php echo $match['datetime'];?></td></tr></tbody>
        </table>
    </td>
    <td rowspan="2" class="team_name">
    	<span class='beforeLoveMb team_name_zd' ><?php echo $match['team_h'];?></span>
        <div class="hot_td" id="td_love_<?php echo $match['gnum_h'];?>"><span id="sp_<?php echo $match['dategh'];?>">
                <div id="<?php echo $match['dategh'];?>" class="fov_icon_out"
                        style="cursor: pointer; display: none;" title="我的最爱"
                        onclick="addShowLoveI('<?php echo $match['gnum_h'];?>','<?php echo $match['datetimelove'];?>','<?php echo $match['league'];?>','<?php echo $match['team_h'];?>','<?php echo $match['team_c'];?>'); "></div>
        </span></div>
    	<br><span class="bk_team_c team_name_kd"><?php echo $match['team_c'];?></span><span class="more_txt bk_more">
              <?php  if($match['all']>0){  ?>
                  <a href="javascript:"
                     onclick="parent.show_more(<?php echo $match['gid'];?>,event,'all');"><font class="total_color"><?php if($match['all']>0) echo "所有玩法(".$match['all'].")"?></font>
                  </a>
              <?php  } ?>

        </span></td>
    <td class="b_cen self_win">
        <?php  if($match['ior_MH']){  ?>
            <a href="javascript://"
               onclick="parent.parent.mem_order.betOrder('BK','M','<?php echo $match['bet_Url'];?>&type=H');"
               title="<?php echo $match['team_h'];?>"><font ><?php echo $match['ior_MH'];?></font></a>
        <?php  } ?>
       
    </td> <!-- 全部 独赢列 -->
    <td class="b_rig"><span class="con"><?php echo $match['ratio_mb_str'];?></span> <span class="ratio">
               <?php  if($match['ior_RH']){  ?>
                   <a href="javascript://"
                      onclick="parent.parent.mem_order.betOrder('BK','R','<?php echo $match['bet_Url'];?>&type=H&strong=<?php echo $match['strong'];?>');"
                      title="<?php echo $match['team_h'];?>"><font ><?php echo $match['ior_RH'];?></font></a>
               <?php  } ?>
          
        </span></td>
    <td class="b_rig"><span class="con"><?php echo $match['ratio_o_str'];?></span> <span class="ratio">
               <?php  if($match['ior_OUC']){  ?>
                   <a href="javascript://"
                      onclick="parent.parent.mem_order.betOrder('BK','OU','<?php echo $match['bet_Url'];?>&type=C');"
                      title="大"><font ><?php echo $match['ior_OUC'];?></font></a>
               <?php  } ?>

        </span></td>
    <td class="b_rig"><span class="con"><span class="con_oe"><?php if($match['ior_EOO']>0) echo "单"; ?>&nbsp;</span>
            <?php  if($match['ior_EOO']){  ?>
                <a href="javascript://"
                   onclick="parent.parent.mem_order.betOrder('BK','EO','<?php echo $match['bet_Url'];?>&rtype=ODD');"
                   title="单"><font ><?php echo $match['ior_EOO'];?></font></a>
            <?php  } ?>
           
        </span></td>
    <td class="b_1stR df_big_1"><span class="con"><?php if($match['ior_OUHO']>0){?><font class="text_green">大</font><?php echo $match['ratio_ouho_str'];?><?php } ?></span>
        <span class="ratio">
             <?php  if($match['ior_OUHO']){  ?>
                 <a href="javascript://"
                    onclick="parent.parent.mem_order.betOrder('BK','OUH','<?php echo $match['bet_Url'];?>&wtype=OUH&type=O');"
                    title="大"><font ><?php echo $match['ior_OUHO'];?></font></a>
             <?php  } ?>
           
        </span></td>
    <td class="b_1stR df_small_1"><span class="con"><?php if($match['ior_OUHU']>0){?><font class="text_brown">小</font><?php echo $match['ratio_ouho_str'];?><?php } ?></span><span class="ratio">
               <?php  if($match['ior_OUHU']){  ?>
                   <a href="javascript://"
                      onclick="parent.parent.mem_order.betOrder('BK','OUH','<?php echo $match['bet_Url'];?>&wtype=OUH&type=U');"
                      title="小"><font ><?php echo $match['ior_OUHU'];?></font></a>
               <?php  } ?>

        </span></td>
  </tr>
  <tr id="TR1_<?php echo $match['dategh'];?>" >
    <td class="b_cen self_win">
        <?php  if($match['ior_MC']){  ?>
            <a href="javascript://"
               onclick="parent.parent.mem_order.betOrder('BK','M','<?php echo $match['bet_Url'];?>&type=C');"
               title="<?php echo $match['team_c'];?>"><font ><?php echo $match['ior_MC'];?></font></a>
        <?php  } ?>

    </td> <!-- 独赢列 -->
    <td class="b_rig"><span class="con"><?php echo $match['ratio_tg_str'];?></span> <span class="ratio">
             <?php  if($match['ior_RC']){  ?>
                 <a href="javascript://"
                    onclick="parent.parent.mem_order.betOrder('BK','R','<?php echo $match['bet_Url'];?>&type=C&strong=<?php echo $match['strong'];?>');"
                    title="<?php echo $match['team_c'];?>"><font ><?php echo $match['ior_RC'];?></font></a>
             <?php  } ?>

        </span></td>
    <td class="b_rig"><span class="con"><?php echo $match['ratio_u_str'];?></span> <span class="ratio">
           
              <?php  if($match['ior_OUH']){  ?>
                  <a href="javascript://"
                     onclick="parent.parent.mem_order.betOrder('BK','OU','<?php echo $match['bet_Url'];?>&type=H');"
                     title="小"><font ><?php echo $match['ior_OUH'];?></font></a>
              <?php  } ?>
          
        </span></td>
    <td class="b_rig"><span class="con"><span class="con_oe"><?php if($match['ior_EOO']>0) echo "双"; ?>&nbsp;</span>
            <?php  if($match['ior_EOE']){  ?>
                <a href="javascript://"
                   onclick="parent.parent.mem_order.betOrder('BK','EO','<?php echo $match['bet_Url'];?>&rtype=EVEN');"
                   title="双"><font ><?php echo $match['ior_EOE'];?></font></a>
            <?php  } ?>
           
        </span></td>
    <td class="b_1stR df_big_2"><span class="con"><?php if($match['ior_OUCO']>0){?><font class="text_green">大</font><?php echo $match['ratio_ouco_str'];?><?php } ?></span><span class="ratio">
                <?php  if($match['ior_OUCO']){  ?>
                    <a href="javascript://"
                       onclick="parent.parent.mem_order.betOrder('BK','OUC','<?php echo $match['bet_Url'];?>&wtype=OUC&type=O');"
                       title="大"><font ><?php echo $match['ior_OUCO'];?></font></a>
                <?php  } ?>
            
        </span></td>
    <td class="b_1stR df_small_2"><span class="con"><?php if($match['ior_OUCU']>0){?><font class="text_brown">小</font><?php echo $match['ratio_ouco_str'];?><?php } ?></span><span class="ratio">
             <?php  if($match['ior_OUCU']){  ?>
                 <a href="javascript://"
                    onclick="parent.parent.mem_order.betOrder('BK','OUC','<?php echo $match['bet_Url'];?>&wtype=OUC&type=U');"
                    title="小"><font ><?php echo $match['ior_OUCU'];?></font></a>
             <?php  } ?>

        </span></td>
  </tr>

<?php }?>
