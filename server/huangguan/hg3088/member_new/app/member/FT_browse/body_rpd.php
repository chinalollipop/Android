<?php
$k =0;
$j =0;
?>
			<tr>
              <th class="time">时间</th>
              <th class="team">赛事</th>
               <th class="h_pd_ft">1:0</th>
               <th class="h_pd_ft">2:0</th>
               <th class="h_pd_ft">2:1</th>
               <th class="h_pd_ft">3:0</th>
               <th class="h_pd_ft">3:1</th>
               <th class="h_pd_ft">3:2</th>
               <th class="h_pd_ft">4:0</th>
               <th class="h_pd_ft">4:1</th>
               <th class="h_pd_ft">4:2</th>
               <th class="h_pd_ft">4:3</th>
               <th class="h_pd_ft">0:0</th>
               <th class="h_pd_ft">1:1</th>
               <th class="h_pd_ft">2:2</th>
               <th class="h_pd_ft">3:3</th>
               <th class="h_pd_ft">4:4</th>
               <th class="h_pd_ft">其它</th>
            </tr>      
<?php foreach($newDataArray as $key=>$match){?>	
<?php if($leagueNameCur!=$match['league']){?>
	<tr style="display: ;">
		<td colspan="18" class="b_hline">
			<table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="showLeg('<?php echo $match['league'];?>')">
				<span id="<?php echo $match['league'];?>" name="<?php echo $match['league'];?>" class="showleg">
					<span id="LegOpen"></span>
				       <!--展開聯盟-符號--><!--span id="LegOpen"></span-->
				       <!--收合聯盟-符號--><!--div id="LegClose"></div-->
				</span>
		</td><td onclick="showLeg('<?php echo $match['league'];?>')" class="leg_bar"><?php echo $match['league'];?></td></tr></tbody></table>
		</td>
	</tr>
	<?php 
		$leagueNameCur = $match['league'];
	} ?>
  <!--SHOW LEGUAGE END-->
	<tr id="TR_<?php echo $match['dategh'];?>" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
    <td rowspan="2" class="b_cen">
        <table class="rb_box">
            <tbody>
            <tr>
                <td class="rb_time"><?php echo $match['showretime'];?></td>
            </tr>
            <tr>
                <td class="rb_score"><?php if($match['lastestscore_h']=='H'){?><strong style="color: red"><?php }?><?php echo $match['score_h'];?><?php if($match['lastestscore_h']=='H'){?></strong><?php }?>&nbsp;-&nbsp;<?php if($match['lastestscore_c']=='C'){?><strong style="color: red"><?php }?><?php echo $match['score_c'];?><?php if($match['lastestscore_c']=='C'){?></strong><?php }?></td>
            </tr>
            </tbody>
        </table>
    </td>
    <td rowspan="2" class="team_name"><?php echo $match['team_h'];?><br><?php echo $match['team_c'];?></td>
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH1C0');" title="1:0"><?php if($oddsBackground[$key]['ior_H1C0']==1){ ?> <font style="background-color:yellow"> <?php } ?><font true=""><?php echo $match['ior_H1C0'];?></font><?php if($oddsBackground[$key]['ior_H1C0']==1){ ?> </font> <?php } ?></a></td>
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH2C0');" title="2:0"><?php if($oddsBackground[$key]['ior_H2C0']==1){ ?> <font style="background-color:yellow"> <?php } ?><font true=""><?php echo $match['ior_H2C0'];?></font><?php if($oddsBackground[$key]['ior_H2C0']==1){ ?> </font> <?php } ?></a></td>
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH2C1');" title="2:1"><?php if($oddsBackground[$key]['ior_H2C1']==1){ ?> <font style="background-color:yellow"> <?php } ?><font true=""><?php echo $match['ior_H2C1'];?></font><?php if($oddsBackground[$key]['ior_H2C1']==1){ ?> </font> <?php } ?></a></td>
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH3C0');" title="3:0"><?php if($oddsBackground[$key]['ior_H3C0']==1){ ?> <font style="background-color:yellow"> <?php } ?><font true=""><?php echo $match['ior_H3C0'];?></font><?php if($oddsBackground[$key]['ior_H3C0']==1){ ?> </font> <?php } ?></a></td>
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH3C1');" title="3:1"><?php if($oddsBackground[$key]['ior_H3C1']==1){ ?> <font style="background-color:yellow"> <?php } ?><font true=""><?php echo $match['ior_H3C1'];?></font><?php if($oddsBackground[$key]['ior_H3C1']==1){ ?> </font> <?php } ?></a></td>
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH3C2');" title="3:2"><?php if($oddsBackground[$key]['ior_H3C2']==1){ ?> <font style="background-color:yellow"> <?php } ?><font true=""><?php echo $match['ior_H3C2'];?></font><?php if($oddsBackground[$key]['ior_H3C2']==1){ ?> </font> <?php } ?></a></td>
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH4C0');" title="4:0"><?php if($oddsBackground[$key]['ior_H4C0']==1){ ?> <font style="background-color:yellow"> <?php } ?><font true=""><?php echo $match['ior_H4C0'];?></font><?php if($oddsBackground[$key]['ior_H4C0']==1){ ?> </font> <?php } ?></a></td>
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH4C1');" title="4:1"><?php if($oddsBackground[$key]['ior_H4C1']==1){ ?> <font style="background-color:yellow"> <?php } ?><font true=""><?php echo $match['ior_H4C1'];?></font><?php if($oddsBackground[$key]['ior_H4C1']==1){ ?> </font> <?php } ?></a></td>
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH4C2');" title="4:2"><?php if($oddsBackground[$key]['ior_H4C2']==1){ ?> <font style="background-color:yellow"> <?php } ?><font true=""><?php echo $match['ior_H4C2'];?></font><?php if($oddsBackground[$key]['ior_H4C2']==1){ ?> </font> <?php } ?></a></td>
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH4C3');" title="4:3"><?php if($oddsBackground[$key]['ior_H4C3']==1){ ?> <font style="background-color:yellow"> <?php } ?><font true=""><?php echo $match['ior_H4C3'];?></font><?php if($oddsBackground[$key]['ior_H4C3']==1){ ?> </font> <?php } ?></a></td>
    <td rowspan="2" class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH0C0');" title="0:0"><?php if($oddsBackground[$key]['ior_H0C0']==1){ ?> <font style="background-color:yellow"> <?php } ?><font true=""><?php echo $match['ior_H0C0'];?></font><?php if($oddsBackground[$key]['ior_H0C0']==1){ ?> </font> <?php } ?></a></td>
    <td rowspan="2" class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH1C1');" title="1:1"><?php if($oddsBackground[$key]['ior_H1C1']==1){ ?> <font style="background-color:yellow"> <?php } ?><font true=""><?php echo $match['ior_H1C1'];?></font><?php if($oddsBackground[$key]['ior_H1C1']==1){ ?> </font> <?php } ?></a></td>
    <td rowspan="2" class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH2C2');" title="2:2"><?php if($oddsBackground[$key]['ior_H2C2']==1){ ?> <font style="background-color:yellow"> <?php } ?><font true=""><?php echo $match['ior_H2C2'];?></font><?php if($oddsBackground[$key]['ior_H2C2']==1){ ?> </font> <?php } ?></a></td>
    <td rowspan="2" class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH3C3');" title="3:3"><?php if($oddsBackground[$key]['ior_H3C3']==1){ ?> <font style="background-color:yellow"> <?php } ?><font true=""><?php echo $match['ior_H3C3'];?></font><?php if($oddsBackground[$key]['ior_H3C3']==1){ ?> </font> <?php } ?></a></td>
    <td rowspan="2" class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH4C4');" title="4:4"><?php if($oddsBackground[$key]['ior_H4C4']==1){ ?> <font style="background-color:yellow"> <?php } ?><font true=""><?php echo $match['ior_H4C4'];?></font><?php if($oddsBackground[$key]['ior_H4C4']==1){ ?> </font> <?php } ?></a></td>
    <td rowspan="2" class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>ROVH');" title="Other Score"><?php if($oddsBackground[$key]['ior_OVH']==1){ ?> <font style="background-color:yellow"> <?php } ?><font true=""><?php echo $match['ior_OVH'];?></font><?php if($oddsBackground[$key]['ior_OVH']==1){ ?> </font> <?php } ?></a></td>
  </tr>
  <tr id="TR1_<?php echo $match['dategh'];?>" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH0C1');" title="0:1"><?php if($oddsBackground[$key]['ior_H0C1']==1){ ?> <font style="background-color:yellow"> <?php } ?><font true=""><?php echo $match['ior_H0C1'];?></font><?php if($oddsBackground[$key]['ior_H0C1']==1){ ?> </font> <?php } ?></a></td>
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH0C2');" title="0:2"><?php if($oddsBackground[$key]['ior_H0C2']==1){ ?> <font style="background-color:yellow"> <?php } ?><font true=""><?php echo $match['ior_H0C2'];?></font><?php if($oddsBackground[$key]['ior_H0C2']==1){ ?> </font> <?php } ?></a></td>
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH1C2');" title="1:2"><?php if($oddsBackground[$key]['ior_H1C2']==1){ ?> <font style="background-color:yellow"> <?php } ?><font true=""><?php echo $match['ior_H1C2'];?></font><?php if($oddsBackground[$key]['ior_H1C2']==1){ ?> </font> <?php } ?></a></td>
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH0C3');" title="0:3"><?php if($oddsBackground[$key]['ior_H0C3']==1){ ?> <font style="background-color:yellow"> <?php } ?><font true=""><?php echo $match['ior_H0C3'];?></font><?php if($oddsBackground[$key]['ior_H0C3']==1){ ?> </font> <?php } ?></a></td>
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH1C3');" title="1:3"><?php if($oddsBackground[$key]['ior_H1C3']==1){ ?> <font style="background-color:yellow"> <?php } ?><font true=""><?php echo $match['ior_H1C3'];?></font><?php if($oddsBackground[$key]['ior_H1C3']==1){ ?> </font> <?php } ?></a></td>
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH2C3');" title="2:3"><?php if($oddsBackground[$key]['ior_H2C3']==1){ ?> <font style="background-color:yellow"> <?php } ?><font true=""><?php echo $match['ior_H2C3'];?></font><?php if($oddsBackground[$key]['ior_H2C3']==1){ ?> </font> <?php } ?></a></td>
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH0C4');" title="0:4"><?php if($oddsBackground[$key]['ior_H0C4']==1){ ?> <font style="background-color:yellow"> <?php } ?><font true=""><?php echo $match['ior_H0C4'];?></font><?php if($oddsBackground[$key]['ior_H0C4']==1){ ?> </font> <?php } ?></a></td>
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH1C4');" title="1:4"><?php if($oddsBackground[$key]['ior_H1C4']==1){ ?> <font style="background-color:yellow"> <?php } ?><font true=""><?php echo $match['ior_H1C4'];?></font><?php if($oddsBackground[$key]['ior_H1C4']==1){ ?> </font> <?php } ?></a></td>
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH2C4');" title="2:4"><?php if($oddsBackground[$key]['ior_H2C4']==1){ ?> <font style="background-color:yellow"> <?php } ?><font true=""><?php echo $match['ior_H2C4'];?></font><?php if($oddsBackground[$key]['ior_H2C4']==1){ ?> </font> <?php } ?></a></td>
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH3C4');" title="3:4"><?php if($oddsBackground[$key]['ior_H3C4']==1){ ?> <font style="background-color:yellow"> <?php } ?><font true=""><?php echo $match['ior_H3C4'];?></font><?php if($oddsBackground[$key]['ior_H3C4']==1){ ?> </font> <?php } ?></a></td>
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