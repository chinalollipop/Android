<?php
$k =0;
$j =0;
$navstr ='<tr class="play_tr_nav">
            <th class="time"> 时间 </th>
            <th class="team"> 赛事 </th>
            <th class="h_1x2">全场 - 独赢</th>
            <th class="h_r">全场 - 让球</th>
            <th class="h_ou">全场 - 大小</th>
            <th>单双</th>
            <th class="h_1x2">半场 - 独赢</th>
            <th class="h_r">半场 - 让球</th>
            <th class="h_ou">半场 - 大小</th>
        </tr>';

?>


<?php foreach($newDataArray as $key=>$match){?>	
									<!--SHOW LEGUAGE START-->
									<?php if($leagueNameCur!=$match['league']){?>
									<tr class="tr_league">
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
                                        echo $navstr;
										$leagueNameCur = $match['league'];
									} ?>
									<!--SHOW LEGUAGE END-->
									<!--SHOW MB START-->
									<tr id="TR_<?php echo $match['dategh'];?>">
										<td rowspan="3" class="b_cen">
											<table>
												<tbody>
													<tr>
                                                        <td class="today_time"> <?php echo $match['datetime'];?> </td>
													</tr>
												</tbody>
											</table>
										</td>
                                        <td rowspan="2" class="team_name none"><span class="team_name_zd"><?php echo $match['team_h'];?></span><br><span class="team_name_kd"><?php echo $match['team_c'];?></span></td>
										<td class="b_cen">
                                            <?php  if($match['ior_MH']){  ?>
                                                <a href="javascript://"
                                                   onclick="parent.parent.mem_order.betOrder('FT','M','<?php echo $match['bet_MH'];?>');"
                                                   title="<?php echo $match['team_h'];?>"><font ><?php echo $match['ior_MH'];?></font></a>
                                          <?php  } ?>

										</td>
										<td class="b_rig"><span class="con"><?php echo $match['ratio_mb_str'];?></span>
                                            <span class="ratio">
                                                 <?php  if($match['ior_RH']){  ?>
                                                     <a href="javascript://"
                                                        onclick="parent.parent.mem_order.betOrder('FT','R','<?php echo $match['bet_RH'];?>');"
                                                        title="<?php echo $match['team_h'];?>"><font ><?php echo $match['ior_RH'];?></font></a>
                                                 <?php  } ?>

                                            </span>
										</td>
										<td class="b_rig"><span class="con"><?php if($match['ior_OUC']){echo $match['ratio_o_str'];}?></span> <span class="ratio">
                                                  <?php  if($match['ior_OUC']){  ?>
                                                      <a href="javascript://"
                                                         onclick="parent.parent.mem_order.betOrder('FT','OU','<?php echo $match['bet_OUH'];?>');"
                                                         title="大"><font ><?php echo $match['ior_OUC'];?></font></a>
                                                  <?php  } ?>

                                            </span>
										</td>
                                        <td class="b_rig"><span class="con"> <?php if($match['ior_EOO']>0){ echo $match['str_odd']; }?> </span>
                                            <?php  if($match['ior_EOO']){  ?>
                                                <a href="javascript://"
                                                   onclick="parent.parent.mem_order.betOrder('FT','EO','<?php echo $match['bet_EOO'];?>');"
                                                   title="<?php echo $match['str_odd'];?>"><font ><?php echo $match['ior_EOO'];?></font></a>
                                            <?php  } ?>

										</td>
										<td class="b_1st">
                                            <?php  if($match['ior_HMH']){  ?>
                                                <a href="javascript://"
                                                   onclick="parent.parent.mem_order.betOrder('FT','HM','<?php echo $match['bet_MH'];?>');"
                                                   title="<?php echo $match['team_h'];?>"><font ><?php echo $match['ior_HMH'];?></font></a>
                                            <?php  } ?>

                                        </td>
    									<td class="b_1stR h_r_main"><span class="con"><?php echo $match['hratio_mb_str'];?></span>
                                            <span class="ratio">
                                                 <?php  if($match['ior_HRH']){  ?>
                                                     <a href="javascript://"
                                                        onclick="parent.parent.mem_order.betOrder('FT','HR','<?php echo $match['bet_RH'];?>');"
                                                        title="<?php echo $match['team_h'];?>"><font ><?php echo $match['ior_HRH'];?></font></a>
                                                 <?php  } ?>

                                            </span></td>
    									<td class="b_1stR"><span class="con"><?php if($match['ior_HOUC']>0){echo $match['hratio_o_str'];}?></span> <span class="ratio">
                                                  <?php  if($match['ior_HOUC']){  ?>
                                                      <a href="javascript://"
                                                         onclick="parent.parent.mem_order.betOrder('FT','HOU','<?php echo $match['bet_OUH'];?>');"
                                                         title="大"><font ><?php echo $match['ior_HOUC'];?></font></a>
                                                  <?php  } ?>

                                            </span></td>
									</tr>
									<!--SHOW MB END-->
									<!--SHOW TG START-->
									<tr id="TR1_<?php echo $match['dategh'];?>" >
										<td class="b_cen">
                                            <?php  if($match['ior_MC']){  ?>
                                                <a href="javascript://"
                                                   onclick="parent.parent.mem_order.betOrder('FT','M','<?php echo $match['bet_MC'];?>');"
                                                   title="<?php echo $match['team_c'];?>"><font ><?php echo $match['ior_MC'];?></font></a>
                                            <?php  } ?>

										</td>
										<td class="b_rig">
                                            <span class="con"> <?php echo $match['ratio_tg_str'];?> </span>
                                            <span class="ratio">
                                                <?php  if($match['ior_RC']){  ?>
                                                    <a href="javascript://"
                                                       onclick="parent.parent.mem_order.betOrder('FT','R','<?php echo $match['bet_RC'];?>');"
                                                       title="<?php echo $match['team_c'];?>"><font ><?php echo $match['ior_RC'];?></font></a>
                                                <?php  } ?>

                                            </span>
										</td>
										<td class="b_rig"><span class="con"><?php if($match['ior_OUH']){echo $match['ratio_u_str'];}?></span> <span
											class="ratio">
                                                  <?php  if($match['ior_OUH']){  ?>
                                                      <a href="javascript://"
                                                         onclick="parent.parent.mem_order.betOrder('FT','OU','<?php echo $match['bet_OUC'];?>');"
                                                         title="小"><font ><?php echo $match['ior_OUH'];?></font></a>
                                                  <?php  } ?>

                                            </span>
										</td>
                                        <td class="b_rig"> <span class="con"> <?php if($match['ior_EOE']>0){echo $match['str_even'];}?> </span>
                                            <?php  if($match['ior_EOE']){  ?>
                                                <a href="javascript://"
                                                   onclick="parent.parent.mem_order.betOrder('FT','EO','<?php echo $match['bet_EOE'];?>');"
                                                   title="<?php echo $match['str_even'];?>"><font ><?php echo $match['ior_EOE'];?></font></a>
                                            <?php  } ?>

										</td>
										<td class="b_1st">
                                            <?php  if($match['ior_HMC']){  ?>
                                                <a href="javascript://"
                                                   onclick="parent.parent.mem_order.betOrder('FT','HM','<?php echo $match['bet_MC'];?>');"
                                                   title="<?php echo $match['team_c'];?>"><font ><?php echo $match['ior_HMC'];?></font></a>
                                            <?php  } ?>

                                        </td>
									    <td class="b_1stR"><span class="con"> <?php echo $match['hratio_tg_str'];?> </span>
                                            <span class="ratio">
                                                  <?php  if($match['ior_HRC']){  ?>
                                                      <a href="javascript://"
                                                         onclick="parent.parent.mem_order.betOrder('FT','HR','<?php echo $match['bet_RC'];?>');"
                                                         title="<?php echo $match['team_c'];?>"><font ><?php echo $match['ior_HRC'];?></font></a>
                                                  <?php  } ?>

                                            </span></td>
									    <td class="b_1stR"><span class="con"><?php if($match['ior_HOUH']>0){echo $match['hratio_u_str'];}?></span> <span class="ratio">
                                               <?php  if($match['ior_HOUH']){  ?>
                                                   <a href="javascript://"
                                                      onclick="parent.parent.mem_order.betOrder('FT','HOU','<?php echo $match['bet_OUC'];?>');"
                                                      title="小"><font ><?php echo $match['ior_HOUH'];?></font></a>
                                               <?php  } ?>

                                            </span></td>
									</tr>
									<!--SHOW TG END-->
									<!--SHOW HJ START-->
									<tr id="TR2_<?php echo $match['dategh'];?>" >
										<td class="drawn_td">
										<table width="99%" border="0" cellpadding="0" cellspacing="0">
											<tbody>
												<tr>
													<td align="left" class="team_name_hj">和局</td>
													<td class="hot_td" id="td_love_<?php echo $match['gnum_h'];?>"><span id="sp_<?php echo $match['dategh'];?>">
													<div id="<?php echo $match['dategh'];?>" class="fov_icon_out"
														style="cursor: pointer; display: none;" title="我的最爱"
														onclick="addShowLoveI('<?php echo $match['gnum_h'];?>','<?php echo $match['datetimelove'];?>','<?php echo $match['league'];?>','<?php echo $match['team_h'];?>','<?php echo $match['team_c'];?>'); "></div>
													</span></td>
													<?php if($match['event']=='on'){?><td class="hot_tv" onclick="showOpenLive();" ><span><div class="tv_icon_on"></div></span></td><?php }?>
													<?php if($match['event']=='out'){?><td class="hot_tv"><span><div class="tv_icon_out"></div></span></td><?php }?>
												</tr>
											</tbody>
										</table>
										</td>
										<td class="b_cen">
                                            <?php  if($match['ior_MN']){  ?>
                                                <a href="javascript://"
                                                   onclick="parent.parent.mem_order.betOrder('FT','M','<?php echo $match['bet_MN'];?>');"
                                                   title="和"><font ><?php echo $match['ior_MN'];?></font></a>
                                            <?php  } ?>

										</td>
										<td colspan="3" valign="top" class="b_cen"><span class="more_txt ft_more_games">
                                                  <?php  if($match['all']>4){  ?>
                                                      <a href="javascript:"
                                                         onclick="parent.show_more('<?php echo $match['gid'];?>',event,'all');"><font
                                                                  class="total_color"><?php if($match['all']>4){echo "所有玩法(".$match['all'].")";}  ?></font></a>
                                                  <?php  } ?>
                                              </span>
										</td>
										<td class="b_1st">
                                            <?php  if($match['ior_HMN']){  ?>
                                                <a href="javascript://"
                                                   onclick="parent.parent.mem_order.betOrder('FT','HM','<?php echo $match['bet_MN'];?>');"
                                                   title="和"><font ><?php echo $match['ior_HMN'];?></font></a>
                                            <?php  } ?>

                                        </td>
    									<td colspan="3" valign="top" class="b_1stR">&nbsp;</td>
									</tr>
									<!--SHOW HJ END-->
    <?php
       $LeagueAry[] = $match['league'] ; // 联赛
      $leaguetitle[$match['league']][] = $match['dategh'] ; // 联赛
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
