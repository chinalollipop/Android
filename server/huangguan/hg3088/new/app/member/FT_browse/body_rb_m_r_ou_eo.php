<?php
$k =0;
$j =0;
?>

<?php foreach($newDataArray as $key=>$match){?>	
									<!--SHOW LEGUAGE START-->
									<?php if($leagueNameCur!=$match['league']){?>
									<tr style="display:;">
										<td colspan="18" class="b_hline">
										<table border="0" cellpadding="0" cellspacing="0">
											<tbody>
												<tr>
													<td class="legicon" onclick="showLeg('<?php echo $match['league'];?>')">
													<span id="<?php echo $match['league'];?>" name="<?php echo $match['league'];?>" class="showleg">
														<span id="LegOpen"></span>
													</span></td>
													<td onclick="showLeg('<?php echo $match['league'];?>')" class="leg_bar"><?php echo $match['league'];?></td>
												</tr>
											</tbody>
										</table>
										</td>
									</tr>
									<?php 
										$leagueNameCur = $match['league'];
									} ?>
									<!--SHOW LEGUAGE END-->
									<!--SHOW MB START-->
									<tr class="bet_game_tr_top" id="TR_<?php echo $match['dategh'];?>"	onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
										<td rowspan="3" class="b_cen">
											<table class="rb_box">
												<tbody>
													<tr>
														<td class="rb_time"><?php echo $match['showretime'];?></td>
													</tr>
													<tr>
														<td class="rb_score"><?php echo $match['score_h'];?>&nbsp;-&nbsp;<?php echo $match['score_c'];?></td>
													</tr>
												</tbody>
											</table>
										</td>
										<td rowspan="2" class="team_name none"><?php echo $match['team_h'];?><br><?php echo $match['team_c'];?></td>
										<td class="b_cen"><a href="javascript://"
											onclick="parent.parent.mem_order.betOrder('FT','RM','<?php echo $match['bet_MH'];?>');"
											title="<?php echo $match['team_h'];?>"><font true=""><?php echo $match['ior_MH'];?></font></a>
										</td>
										<td class="b_rig"><span class="con"><?php echo $match['ratio_mb_str'];?></span> <span class="ratio"><a href="javascript://"
											onclick="parent.parent.mem_order.betOrder('FT','RE','<?php echo $match['bet_RH'];?>');"
											title="<?php echo $match['team_h'];?>"><font true=""><?php echo $match['ior_RH'];?></font></a></span>
										</td>
										<td class="b_rig"><span class="con"><?php if($match['ior_OUC']>0){echo $match['ratio_o_str'];}?></span> <span class="ratio"><a href="javascript://"
											onclick="parent.parent.mem_order.betOrder('FT','ROU','<?php echo $match['bet_OUH'];?>');"
											title="大"><font true=""><?php echo $match['ior_OUC'];?></font></a></span>
										</td>
										<td class="b_rig"><?php if($match['ior_EOO']>0){ echo $match['str_odd']; }?><a href="javascript://"
											onclick="parent.parent.mem_order.betOrder('FT','REO','<?php echo $match['bet_EOO'];?>');"
											title="<?php echo $match['str_odd'];?>"><font true=""><?php echo $match['ior_EOO'];?></font></a>
										</td>
										<td class="b_1st"><a href="javascript://" 
											onclick="parent.parent.mem_order.betOrder('FT','HRM','<?php echo $match['bet_MH'];?>');" 
											title="<?php echo $match['team_h'];?>"><font true=""><?php echo $match['ior_HMH'];?></font></a></td>
    									<td class="b_1stR h_r_main"><span class="con"><?php echo $match['hratio_mb_str'];?></span> <span class="ratio"><a href="javascript://" 
    										onclick="parent.parent.mem_order.betOrder('FT','HRE','<?php echo $match['bet_RH'];?>');" 
    										title="<?php echo $match['team_h'];?>"><font true=""><?php echo $match['ior_HRH'];?></font></a></span></td>
    									<td class="b_1stR"><span class="con"><?php if($match['ior_HOUC']>0){echo $match['hratio_o_str'];}?></span> <span class="ratio"><a href="javascript://" 
    										onclick="parent.parent.mem_order.betOrder('FT','HROU','<?php echo $match['bet_OUH'];?>');" 
    										title="大"><font true=""><?php echo $match['ior_HOUC'];?></font></a></span></td>
									</tr>
									<!--SHOW MB END-->
									<!--SHOW TG START-->
									<tr id="TR1_<?php echo $match['dategh'];?>" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
										<td class="b_cen"><a href="javascript://"
											onclick="parent.parent.mem_order.betOrder('FT','RM','<?php echo $match['bet_MC'];?>');"
											title="<?php echo $match['team_c'];?>"><font true=""><?php echo $match['ior_MC'];?></font></a>
										</td>
										<td class="b_rig"><span class="con"></span><?php echo $match['ratio_tg_str'];?><span class="ratio"><a
											href="javascript://"
											onclick="parent.parent.mem_order.betOrder('FT','RE','<?php echo $match['bet_RC'];?>');"
											title="<?php echo $match['team_c'];?>"><font true=""><?php echo $match['ior_RC'];?></font></a></span>
										</td>
										<td class="b_rig"><span class="con"><?php if($match['ior_OUH']>0){echo $match['ratio_u_str'];}?></span> <span
											class="ratio"><a href="javascript://"
											onclick="parent.parent.mem_order.betOrder('FT','ROU','<?php echo $match['bet_OUC'];?>');"
											title="小"><font true=""><?php echo $match['ior_OUH'];?></font></a></span>
										</td>
										<td class="b_rig"><?php if($match['ior_EOE']>0){echo $match['str_even'];}?><a href="javascript://"
											onclick="parent.parent.mem_order.betOrder('FT','REO','<?php echo $match['bet_EOE'];?>');"
											title="<?php echo $match['str_even'];?>"><font true=""><?php echo $match['ior_EOE'];?></font></a>
										</td>
										<td class="b_1st"><a href="javascript://" 
											onclick="parent.parent.mem_order.betOrder('FT','HRM','<?php echo $match['bet_MC'];?>');" 
											title="<?php echo $match['team_c'];?>"><font true=""><?php echo $match['ior_HMC'];?></font></a></td>
									    <td class="b_1stR"><span class="con"></span><?php echo $match['hratio_tg_str'];?><span class="ratio"><a href="javascript://" 
									    	onclick="parent.parent.mem_order.betOrder('FT','HRE','<?php echo $match['bet_RC'];?>');" 
									    	title="<?php echo $match['team_c'];?>"><font true=""><?php echo $match['ior_HRC'];?></font></a></span></td>
									    <td class="b_1stR"><span class="con"><?php if($match['ior_HOUH']>0){echo $match['hratio_u_str'];}?></span> <span class="ratio"><a href="javascript://" 
									    	onclick="parent.parent.mem_order.betOrder('FT','HROU','<?php echo $match['bet_OUC'];?>');" 
									    	title="小"><font true=""><?php echo $match['ior_HOUH'];?></font></a></span></td>
									</tr>
									<!--SHOW TG END-->
									<!--SHOW HJ START-->
									<tr id="TR2_<?php echo $match['dategh'];?>" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
										<td class="drawn_td">
										<table width="99%" border="0" cellpadding="0" cellspacing="0">
											<tbody>
												<tr>
													<td align="left">和局</td>
													<td class="hot_td"><span id="sp_<?php echo $match['dategh'];?>">
													<div id="<?php echo $match['dategh'];?>" class="fov_icon_out"
														style="cursor: pointer; display: none;" title="我的最爱"
														onclick="addShowLoveI('<?php echo $match['gnum_h'];?>','<?php echo $match['datetimelove'];?>','<?php echo $match['league'];?>','<?php echo $match['team_h'];?>','<?php echo $match['team_c'];?>'); "></div>
													</span></td>
												</tr>
											</tbody>
										</table>
										<!--星星符号--></td>
										<td class="b_cen"><a href="javascript://"
											onclick="parent.parent.mem_order.betOrder('FT','RM','<?php echo $match['bet_MN'];?>');"
											title="和"><font true=""><?php echo $match['ior_MN'];?></font></a>
										</td>
										<td colspan="3" valign="top" class="b_cen">
                                            <span class="more_txt ft_more_games">

                                                <?php  if($match['all']>4){ ?>
                                                    <a href="javascript:" onclick="parent.show_more('<?php echo $match['gid'];?>',event,'all');">
                                                        <font class="total_color"><?php echo "所有玩法(".$match['all'].")"; ?></font>
                                                    </a>
                                                <?php } ?>

                                            </span>
										</td>
										<td class="b_1st"><a href="javascript://" 
											onclick="parent.parent.mem_order.betOrder('FT','HRM','<?php echo $match['bet_MN'];?>');" 
											title="和"><font true=""><?php echo $match['ior_HMN'];?></font></a></td>
    									<td colspan="3" valign="top" class="b_1st">&nbsp;</td>
									</tr>
									<!--SHOW HJ END-->
    <?php
    $LeagueAry[] = $match['league'] ;
    $leaguetitle[$match['league']][] = $match['dategh'] ;
    ?>
<?php }?>
<?php
$LeagueAry = array_unique($LeagueAry); // 联赛
// var_dump($LeagueAry);
// var_dump($leaguetitle);

?>
<script language="JavaScript">
    <?php
    foreach ($LeagueAry as $key=>$League){
        if($League){
            echo "parent.LeagueAry[$j]= new Array('$League');\n"; // 联赛
            $j ++ ;
        }

    }
    foreach ($leaguetitle as $key=>$leatitle){
        if($leatitle){
            // echo $key.'--' ;var_dump($leatitle);
            // var_dump($leatitle);
            $leastr = implode(',',$leatitle) ;
            echo "parent.myLeg['$key']= new Array('$leastr') ;\n"; // 联赛
            $k ++ ;
        }

    }

    ?>
</script>
