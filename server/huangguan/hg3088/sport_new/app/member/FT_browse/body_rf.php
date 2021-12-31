<?php
$k =0;
$j =0;
$navstr ='<tr class="play_tr_nav">
            <th class="time"> 时间 </th>
            <th class="team"> 赛事 </th>
			<th class="h_f">主/主</th>
			<th class="h_f">主/和</th>
			<th class="h_f">主/客</th>
			<th class="h_f">和/主</th>
			<th class="h_f">和/和</th>
			<th class="h_f">和/客</th>
			<th class="h_f">客/主</th>
			<th class="h_f">客/和</th>
			<th class="h_f">客/客</th>
        </tr>';
?>

<?php foreach($newDataArray as $key=>$match){?>	
 <!--SHOW LEGUAGE START-->
<?php if($leagueNameCur!=$match['league']){?>	
  <tr class="tr_league">
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
        echo $navstr;
		$leagueNameCur = $match['league'];
	} ?>
  <!--SHOW LEGUAGE END-->
	<tr id="TR_<?php echo $match['dategh'];?>">
	    <td class="b_cen"><?php echo $match['datetime'];?></td>
	    <td class="team_name"><span class="team_name_zd"><?php echo $match['team_h'];?></span><br><span class="team_name_kd"><?php echo $match['team_c'];?></span></td>
	    <td class="b_cen">
            <a class="<?php echo $oddsBackground[$match['gid']]['ior_FHH'];?>" href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RF','<?php echo $match['bet_Url']; ?>RFHH');" title="H/H">
              
                    <font><?php echo $match['ior_FHH'];?></font>
                
            </a></td>
	    <td class="b_cen">
            <a class="<?php echo $oddsBackground[$match['gid']]['ior_FHN'];?>" href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RF','<?php echo $match['bet_Url']; ?>RFHN');" title="H/D">

                    <font><?php echo $match['ior_FHN'];?></font>

            </a></td>
	    <td class="b_cen">
            <a class="<?php echo $oddsBackground[$match['gid']]['ior_FHC'];?>" href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RF','<?php echo $match['bet_Url']; ?>RFHC');" title="H/A">

                    <font><?php echo $match['ior_FHC'];?></font>

            </a></td>
	    <td class="b_cen">
            <a class="<?php echo $oddsBackground[$match['gid']]['ior_FNH'];?>" href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RF','<?php echo $match['bet_Url']; ?>RFNH');" title="D/H">

                    <font><?php echo $match['ior_FNH'];?></font>

            </a></td>
	    <td class="b_cen">
            <a class="<?php echo $oddsBackground[$match['gid']]['ior_FNN'];?>" href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RF','<?php echo $match['bet_Url']; ?>RFNN');" title="D/D">

                    <font><?php echo $match['ior_FNN'];?></font>

            </a></td>
	    <td class="b_cen">
            <a class="<?php echo $oddsBackground[$match['gid']]['ior_FNC'];?>" href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RF','<?php echo $match['bet_Url']; ?>RFNC');" title="D/A">

                    <font><?php echo $match['ior_FNC'];?></font>

            </a></td>
	    <td class="b_cen">
            <a class="<?php echo $oddsBackground[$match['gid']]['ior_FCH'];?>" href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RF','<?php echo $match['bet_Url']; ?>RFCH');" title="A/H">

                    <font><?php echo $match['ior_FCH'];?></font>

            </a></td>
	    <td class="b_cen">
            <a class="<?php echo $oddsBackground[$match['gid']]['ior_FCN'];?>" href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RF','<?php echo $match['bet_Url']; ?>RFCN');" title="A/D">

                    <font><?php echo $match['ior_FCN'];?></font>

            </a></td>
	    <td class="b_cen">
            <a class="<?php echo $oddsBackground[$match['gid']]['ior_FCC'];?>" href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RF','<?php echo $match['bet_Url']; ?>RFCC');" title="A/A">

                    <font><?php echo $match['ior_FCC'];?></font>

            </a></td>
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
