
<!--SHOW LEGUAGE START-->
 <?php foreach($newDataArray as $key=>$match){?>
 <?php if($leagueNameCur!=$match['league']){?>
<tr style="display:;">
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
	$leagueNameCur = $match['league'];
} ?>
<!--SHOW LEGUAGE END-->
<tr class="bet_game_tr_top" id="TR_<?php echo $match['dategh'];?>" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
    <td rowspan="2" class="b_cen"><table><tbody><tr><td class="b_cen"><?php echo $match['datetime'];?></td></tr></tbody></table></td>
    <td rowspan="2" class="team_name"><?php echo $match['team_h'];?><br><span class="bk_team_c"><?php echo $match['team_c'];?></span><span class="more_txt bk_more"><a href="javascript:" 
      onclick="parent.show_more(<?php echo $match['gid'];?>,event,'all');"><font class="total_color"><?php if($match['all']>0) echo "所有玩法(".$match['all'].")"?></font></a></span></td>
    <td class="b_cen self_win"><a href="javascript://" 
    	onclick="parent.parent.mem_order.betOrder('BK','M','<?php echo $match['bet_Url'];?>&type=H');" 
    	title="<?php echo $match['team_h'];?>"><font true=""><?php echo $match['ior_MH'];?></font></a></td> <!-- 全部 独赢列 -->
    <td class="b_rig"><span class="con"><?php echo $match['ratio_mb_str'];?></span> <span class="ratio"><a href="javascript://" 
    	onclick="parent.parent.mem_order.betOrder('BK','R','<?php echo $match['bet_Url'];?>&type=H&strong=<?php echo $match['strong'];?>');" 
    	title="<?php echo $match['team_h'];?>"><font true=""><?php echo $match['ior_RH'];?></font></a></span></td>
    <td class="b_rig"><span class="con"><?php echo $match['ratio_o_str'];?></span> <span class="ratio"><a href="javascript://" 
    	onclick="parent.parent.mem_order.betOrder('BK','OU','<?php echo $match['bet_Url'];?>&type=C');" 
    	title="大"><font true=""><?php echo $match['ior_OUC'];?></font></a></span></td>
    <td class="b_rig"><span class="con"><span class="con_oe"><?php if($match['ior_EOO']>0) echo "单"; ?>&nbsp;</span><a href="javascript://" 
    	onclick="parent.parent.mem_order.betOrder('BK','EO','<?php echo $match['bet_Url'];?>&rtype=ODD');" 
    	title="单"><font true=""><?php echo $match['ior_EOO'];?></font></a></span></td>
    <td class="b_1stR df_big_1"><span class="con"><?php if($match['ior_OUHO']>0){?><font class="text_green">大</font><?php echo $match['ratio_ouho_str'];?><?php } ?></span><span class="ratio"><a href="javascript://" 
    	onclick="parent.parent.mem_order.betOrder('BK','OUH','<?php echo $match['bet_Url'];?>&wtype=OUH&type=O');" 
    	title="大"><font true=""><?php echo $match['ior_OUHO'];?></font></a></span></td>
    <td class="b_1stR df_small_1"><span class="con"><?php if($match['ior_OUHU']>0){?><font class="text_brown">小</font><?php echo $match['ratio_ouho_str'];?><?php } ?></span><span class="ratio"><a href="javascript://" 
    	onclick="parent.parent.mem_order.betOrder('BK','OUH','<?php echo $match['bet_Url'];?>&wtype=OUH&type=U');" 
    	title="小"><font true=""><?php echo $match['ior_OUHU'];?></font></a></span></td>
  </tr>
  <tr id="TR1_<?php echo $match['dategh'];?>" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
    <td class="b_cen self_win"><a href="javascript://" 
    	onclick="parent.parent.mem_order.betOrder('BK','M','<?php echo $match['bet_Url'];?>&type=C');" 
    	title="<?php echo $match['team_c'];?>"><font true=""><?php echo $match['ior_MC'];?></font></a></td> <!-- 独赢列 -->
    <td class="b_rig"><span class="con"><?php echo $match['ratio_tg_str'];?></span> <span class="ratio"><a href="javascript://" 
    	onclick="parent.parent.mem_order.betOrder('BK','R','<?php echo $match['bet_Url'];?>&type=C&strong=<?php echo $match['strong'];?>');" 
    	title="<?php echo $match['team_c'];?>"><font true=""><?php echo $match['ior_RC'];?></font></a></span></td>
    <td class="b_rig"><span class="con"><?php echo $match['ratio_u_str'];?></span> <span class="ratio"><a href="javascript://" 
    	onclick="parent.parent.mem_order.betOrder('BK','OU','<?php echo $match['bet_Url'];?>&type=H');" 
    	title="小"><font true=""><?php echo $match['ior_OUH'];?></font></a></span></td>
    <td class="b_rig"><span class="con"><span class="con_oe"><?php if($match['ior_EOO']>0) echo "双"; ?>&nbsp;</span><a href="javascript://" 
    	onclick="parent.parent.mem_order.betOrder('BK','EO','<?php echo $match['bet_Url'];?>&rtype=EVEN');" 
    	title="双"><font true=""><?php echo $match['ior_EOE'];?></font></a></span></td>
    <td class="b_1stR df_big_2"><span class="con"><?php if($match['ior_OUCO']>0){?><font class="text_green">大</font><?php echo $match['ratio_ouco_str'];?><?php } ?></span><span class="ratio"><a href="javascript://" 
    	onclick="parent.parent.mem_order.betOrder('BK','OUC','<?php echo $match['bet_Url'];?>&wtype=OUC&type=O');" 
    	title="大"><font true=""><?php echo $match['ior_OUCO'];?></font></a></span></td>
    <td class="b_1stR df_small_2"><span class="con"><?php if($match['ior_OUCU']>0){?><font class="text_brown">小</font><?php echo $match['ratio_ouco_str'];?><?php } ?></span><span class="ratio"><a href="javascript://" 
    	onclick="parent.parent.mem_order.betOrder('BK','OUC','<?php echo $match['bet_Url'];?>&wtype=OUC&type=U');" 
    	title="小"><font true=""><?php echo $match['ior_OUCU'];?></font></a></span></td>
  </tr>

<?php }?>