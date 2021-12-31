<?php
$k =0;
$j =0;
$navstr ='<tr class="play_tr_nav">
              <th class="time"> 时间 </th>
              <th class="team"> 赛事 </th>
              <th class="h_1x2">全场 - 独赢</th>
              <th class="h_r">全场 - 让球</th>
              <th class="h_ou">全场 - 大小</th>
              <th class="h_oe">单/双</th>
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
				<!--展開聯盟-符號--><!--span id="LegOpen"></span-->
				<!--收合聯盟-符號--><!--div id="LegClose"></div-->
				</span>
			</td>
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
<tr id="TR_<?php echo $match['dategh'];?>" >
	<td rowspan="3" class="b_cen">
		<table>
			<tbody>
				<tr>
					<td class="today_time"><?php echo $match['datetime'];?></td>
				</tr>
			</tbody>
		</table>
	</td>
	<td rowspan="2" class="team_name none"><?php echo $match['team_h'];?><br><?php echo $match['team_c'];?></td>
	<td class="b_cen" id="<?php echo $match['gid'];?>_MH">
        <?php  if($match['ior_MH']){  ?>
            <a href="javascript:void(0)"
               onclick="parent.orderParlay(<?php echo $match['gid'];?>,<?php echo $match['gid'];?>,0,'MH',<?php echo $match['par_minlimit'];?>,<?php echo $match['par_maxlimit'];?>)"
               title="<?php echo $match['team_h'];?>"><font true=""><?php echo $match['ior_MH'];?></font></a>
        <?php  } ?>

    </td> <!-- 全场独赢 -->
	<td class="b_rig" id="<?php echo $match['gid'];?>_PRH"><span class="con"><?php if($match['ior_PRH']>0) echo $match['ratio_mb_str'];?></span><span class="ratio">
            <?php  if($match['ior_PRH']){  ?>
                <a href="javascript:void(0)"
                   onclick="parent.orderParlay(<?php echo $match['gid'];?>,<?php echo $match['gid'];?>,0,'PRH',<?php echo $match['par_minlimit'];?>,<?php echo $match['par_maxlimit'];?>)"
                   title="<?php echo $match['team_h'];?>"><font true=""><?php echo $match['ior_PRH'];?></font></a>
            <?php  } ?>

        </span></td> <!-- 全场让球 -->
	<td class="b_rig" id="<?php echo $match['gid'];?>_POUC"><span class="con"><?php echo $match['ratio_o_str'];?></span> <span class="ratio">
            <?php  if($match['ior_POUC']){  ?>
                <a href="javascript:void(0)"
                   onclick="parent.orderParlay(<?php echo $match['gid'];?>,<?php echo $match['gid'];?>,0,'POUC',<?php echo $match['par_minlimit'];?>,<?php echo $match['par_maxlimit'];?>)"
                   title="大"><font true=""><?php echo $match['ior_POUC'];?></font></a>
            <?php  } ?>

        </span></td>
	<td class="b_cen" id="<?php echo $match['gid'];?>_PO">
        <?php  if($match['ior_PO']){  ?>
            单 <a href="javascript:void(0)"
                 onclick="parent.orderParlay(<?php echo $match['gid'];?>,<?php echo $match['gid'];?>,0,'PO',<?php echo $match['par_minlimit'];?>,<?php echo $match['par_maxlimit'];?>)"
                 title="单"><font true=""><?php echo $match['ior_PO'];?></font></a>
        <?php  } ?>

    </td>
	<td class="b_1st" id="<?php echo $match['gid'];?>_HPMH">
        <?php  if($match['ior_HPMH']){  ?>
            <a href="javascript:void(0)"
               onclick="parent.orderParlay(<?php echo $match['gid'];?>,<?php echo $match['gid'];?>,0,'HPMH',<?php echo $match['par_minlimit'];?>,<?php echo $match['par_maxlimit'];?>)"
               title="<?php echo $match['team_h'];?>"><font true=""><?php echo $match['ior_HPMH'];?></font></a>
        <?php  } ?>

    </td> <!-- 半场独赢主队 -->
	<td class="b_1stR" id="<?php echo $match['gid'];?>_HPRH"><span class="con"><?php if($match['ior_HPRH']>0) echo $match['hratio_mb_str'];?></span><span class="ratio">
             <?php  if($match['ior_HPRH']){  ?>
                 <a href="javascript:void(0)"
                    onclick="parent.orderParlay(<?php echo $match['gid'];?>,<?php echo $match['gid'];?>,0,'HPRH',<?php echo $match['par_minlimit'];?>,<?php echo $match['par_maxlimit'];?>)"
                    title="<?php echo $match['team_h'];?>"><font true=""><?php echo $match['ior_HPRH'];?></font></a>
             <?php  } ?>

        </span></td>  <!-- 半场让球主队 -->
	<td class="b_1stR" id="<?php echo $match['gid'];?>_HPOUC"><span class="con"><?php if($match['ior_HPOUC']>0) echo $match['hratio_o_str'];?></span> <span class="ratio">
             <?php  if($match['ior_HPOUC']){  ?>
                 <a href="javascript:void(0)"
                    onclick="parent.orderParlay(<?php echo $match['gid'];?>,<?php echo $match['gid'];?>,0,'HPOUC',<?php echo $match['par_minlimit'];?>,<?php echo $match['par_maxlimit'];?>)"
                    title="大"><font true=""><?php echo $match['ior_HPOUC'];?></font></a>
             <?php  } ?>

        </span></td>
</tr>
<tr id="TR1_<?php echo $match['dategh'];?>" >
	<td class="b_cen" id="<?php echo $match['gid'];?>_MC">
        <?php  if($match['ior_MC']){  ?>
            <a href="javascript:void(0)"
               onclick="parent.orderParlay(<?php echo $match['gid'];?>,<?php echo $match['gid'];?>,0,'MC',<?php echo $match['par_minlimit'];?>,<?php echo $match['par_maxlimit'];?>)" title="<?php echo $match['team_c'];?>"><font true=""><?php echo $match['ior_MC'];?></font>
            </a>
        <?php  } ?>

    </td>
	<td class="b_rig" id="<?php echo $match['gid'];?>_PRC"><span class="con"><?php if($match['ior_PRC']>0) echo $match['ratio_tg_str'];?></span><span class="ratio">
            <?php  if($match['ior_PRC']){  ?>
                <a href="javascript:void(0)"
                   onclick="parent.orderParlay(<?php echo $match['gid'];?>,<?php echo $match['gid'];?>,0,'PRC',<?php echo $match['par_minlimit'];?>,<?php echo $match['par_maxlimit'];?>)" title="<?php echo $match['team_c'];?>"><font true=""><?php echo $match['ior_PRC'];?></font>
                </a>
            <?php  } ?>

        </span></td>
	<td class="b_rig" id="<?php echo $match['gid'];?>_POUH"><span class="con"><?php echo $match['ratio_u_str'];?></span><span class="ratio">
               <?php  if($match['ior_POUH']){  ?>
                   <a href="javascript:void(0)"
                      onclick="parent.orderParlay(<?php echo $match['gid'];?>,<?php echo $match['gid'];?>,0,'POUH',<?php echo $match['par_minlimit'];?>,<?php echo $match['par_maxlimit'];?>)" title="小"><font true=""><?php echo $match['ior_POUH'];?></font>
                   </a>
               <?php  } ?>

        </span></td>
	<td class="b_cen" id="<?php echo $match['gid'];?>_PE">
        <?php  if($match['ior_PE']){  ?>
            双 <a href="javascript:void(0)"
                 onclick="parent.orderParlay(<?php echo $match['gid'];?>,<?php echo $match['gid'];?>,0,'PE',<?php echo $match['par_minlimit'];?>,<?php echo $match['par_maxlimit'];?>)" title="双"><font true=""><?php echo $match['ior_PE'];?></font>
            </a>
        <?php  } ?>

    </td>
	<td class="b_1st" id="<?php echo $match['gid'];?>_HPMC">
        <?php  if($match['ior_HPMC']){  ?>
            <a href="javascript:void(0)"
               onclick="parent.orderParlay(<?php echo $match['gid'];?>,<?php echo $match['gid'];?>,0,'HPMC',<?php echo $match['par_minlimit'];?>,<?php echo $match['par_maxlimit'];?>)" title="<?php echo $match['team_c'];?>"><font true=""><?php echo $match['ior_HPMC'];?></font>
            </a>
        <?php  } ?>

    </td> <!-- 半场独赢客队 -->
	<td class="b_1stR" id="<?php echo $match['gid'];?>_HPRC"><span class="con"><?php if($match['ior_HPRC']>0) echo $match['hratio_tg_str'];?></span> <span class="ratio">
               <?php  if($match['ior_HPRC']){  ?>
                   <a href="javascript:void(0)"
                      onclick="parent.orderParlay(<?php echo $match['gid'];?>,<?php echo $match['gid'];?>,0,'HPRC',<?php echo $match['par_minlimit'];?>,<?php echo $match['par_maxlimit'];?>)" title="<?php echo $match['team_c'];?>"><font true=""><?php echo $match['ior_HPRC'];?></font>
                   </a>
               <?php  } ?>

        </span></td> <!-- 半场让球客队 -->
	<td class="b_1stR" id="<?php echo $match['gid'];?>_HPOUH"><span class="con"><?php if($match['ior_HPOUH']>0) echo $match['hratio_u_str'];?></span> <span class="ratio">
             <?php  if($match['ior_HPOUH']){  ?>
                 <a href="javascript:void(0)"
                    onclick="parent.orderParlay(<?php echo $match['gid'];?>,<?php echo $match['gid'];?>,0,'HPOUH',<?php echo $match['par_minlimit'];?>,<?php echo $match['par_maxlimit'];?>)" title="小"><font true=""><?php echo $match['ior_HPOUH'];?></font>
                </a>
             <?php  } ?>

        </span></td>
</tr>
<tr id="TR2_<?php echo $match['dategh'];?>" >
	<td class="drawn_td">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tbody>
				<tr><td align="left">和局</td><td class="hot_td"></td></tr>
			</tbody>
		</table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div--></td>
	<td class="b_cen" id="<?php echo $match['gid'];?>_MN">
        <?php  if($match['ior_MN']){  ?>
            <a href="javascript:void(0)"
               onclick="parent.orderParlay(<?php echo $match['gid'];?>,<?php echo $match['gid'];?>,0,'MN',<?php echo $match['par_minlimit'];?>,<?php echo $match['par_maxlimit'];?>)" title="和"><font true=""><?php echo $match['ior_MN'];?></font>
            </a>
        <?php  } ?>

    </td>
	<td colspan="3" valign="top" class="b_cen"><span class="more_txt"></span></td>
	<td class="b_1st" id="<?php echo $match['gid'];?>_HPMN">
        <?php  if($match['ior_HPMN']){  ?>
            <a href="javascript:void(0)"
               onclick="parent.orderParlay(<?php echo $match['gid'];?>,<?php echo $match['gid'];?>,0,'HPMN',<?php echo $match['par_minlimit'];?>,<?php echo $match['par_maxlimit'];?>)" title="和"><font true=""><?php echo $match['ior_HPMN'];?></font>
            </a>
        <?php  } ?>

    </td> <!-- 半场独赢和局 -->
	<td colspan="2" valign="top" class="b_cen">&nbsp;</td>
</tr>

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
