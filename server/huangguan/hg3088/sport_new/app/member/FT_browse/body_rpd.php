<?php
$k =0;
$j =0;
$navstr ='<tr class="play_tr_nav">
               <th class="time"> 时间 </th>
               <th class="team"> 赛事 </th>
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
        </tr>';
?>

<?php foreach($newDataArray as $key=>$match){ ?>	
<?php if($leagueNameCur!=$match['league']){ ?>
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
    <td rowspan="2" class="b_cen"><?php echo $match['datetime'];?></td>
    <td rowspan="2" class="team_name"><span class="team_name_zd"><?php echo $match['team_h'];?></span><br><span class="team_name_kd"><?php echo $match['team_c'];?></span></td>
    <td class="b_cen">
        <?php if($match['ior_H1C0']){ ?>
        <a class="<?php echo $oddsBackground[$match['gid']]['ior_H1C0'];?>" href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH1C0');" title="1:0">
                <font><?php echo $match['ior_H1C0'];?></font>
        </a>
        <?php } ?>
    </td>
    <td class="b_cen">
        <?php if($match['ior_H2C0']){ ?>
        <a class="<?php echo $oddsBackground[$match['gid']]['ior_H2C0'];?>" href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH2C0');" title="2:0">
                <font><?php echo $match['ior_H2C0'];?></font>
        </a>
    <?php } ?>
    </td>
    <td class="b_cen">
        <?php if($match['ior_H2C1']){ ?>
        <a class="<?php echo $oddsBackground[$match['gid']]['ior_H2C1'];?>" href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH2C1');" title="2:1">

                <font><?php echo $match['ior_H2C1'];?></font>
        </a>
        <?php } ?>
    </td>
    <td class="b_cen">
        <?php if($match['ior_H3C0']){ ?>
        <a class="<?php echo $oddsBackground[$match['gid']]['ior_H3C0'];?>" href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH3C0');" title="3:0">

                <font><?php echo $match['ior_H3C0'];?></font>
        </a>
        <?php } ?>
    </td>
    <td class="b_cen">
        <?php if($match['ior_H3C1']){ ?>
        <a class="<?php echo $oddsBackground[$match['gid']]['ior_H3C1'];?>" href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH3C1');" title="3:1">

                <font><?php echo $match['ior_H3C1'];?></font>
        </a>
        <?php } ?>
    </td>
    <td class="b_cen">
        <?php if($match['ior_H3C2']){ ?>
        <a class="<?php echo $oddsBackground[$match['gid']]['ior_H3C2'];?>" href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH3C2');" title="3:2">

                <font><?php echo $match['ior_H3C2'];?></font>

        </a>
        <?php } ?>
    </td>
    <td class="b_cen">
        <?php if($match['ior_H4C0']){ ?>
        <a class="<?php echo $oddsBackground[$match['gid']]['ior_H4C0'];?>" href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH4C0');" title="4:0">

                <font><?php echo $match['ior_H4C0'];?></font>
        </a>
        <?php } ?>
    </td>
    <td class="b_cen">
        <?php if($match['ior_H4C1']){ ?>
        <a class="<?php echo $oddsBackground[$match['gid']]['ior_H4C1'];?>" href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH4C1');" title="4:1">

                <font><?php echo $match['ior_H4C1'];?></font>
        </a>
        <?php } ?>
    </td>
    <td class="b_cen">
        <?php if($match['ior_H4C2']){ ?>
        <a class="<?php echo $oddsBackground[$match['gid']]['ior_H4C2'];?>" href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH4C2');" title="4:2">

                <font><?php echo $match['ior_H4C2'];?></font>
        </a>
        <?php } ?>
    </td>
    <td class="b_cen">
        <?php if($match['ior_H4C3']){ ?>
        <a class="<?php echo $oddsBackground[$match['gid']]['ior_H4C3'];?>" href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH4C3');" title="4:3">

                <font><?php echo $match['ior_H4C3'];?></font>
        </a>
        <?php } ?>
    </td>
    <td rowspan="2" class="b_cen">
        <?php if($match['ior_H0C0']){ ?>
        <a class="<?php echo $oddsBackground[$match['gid']]['ior_H0C0'];?>" href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH0C0');" title="0:0">

                <font><?php echo $match['ior_H0C0'];?></font>
        </a>
        <?php } ?>
    </td>
    <td rowspan="2" class="b_cen">
        <?php if($match['ior_H1C1']){ ?>
        <a class="<?php echo $oddsBackground[$match['gid']]['ior_H1C1'];?>" href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH1C1');" title="1:1">

                <font><?php echo $match['ior_H1C1'];?></font>
        </a>
        <?php } ?>
    </td>
    <td rowspan="2" class="b_cen">
        <?php if($match['ior_H2C2']){ ?>
        <a class="<?php echo $oddsBackground[$match['gid']]['ior_H2C2'];?>" href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH2C2');" title="2:2">

                <font><?php echo $match['ior_H2C2'];?></font>
        </a>
        <?php } ?>
    </td>
    <td rowspan="2" class="b_cen">
        <?php if($match['ior_H3C3']){ ?>
        <a class="<?php echo $oddsBackground[$match['gid']]['ior_H3C3'];?>" href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH3C3');" title="3:3">

                <font><?php echo $match['ior_H3C3'];?></font>
        </a>
        <?php } ?>
    </td>
    <td rowspan="2" class="b_cen">
        <?php if($match['ior_H4C4']){ ?>
        <a class="<?php echo $oddsBackground[$match['gid']]['ior_H4C4'];?>" href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH4C4');" title="4:4">

                <font><?php echo $match['ior_H4C4'];?></font>
        </a>
        <?php } ?>
    </td>
    <td rowspan="2" class="b_cen">
        <?php if($match['ior_OVH']){ ?>
        <a class="<?php echo $oddsBackground[$match['gid']]['ior_OVH'];?>" href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>ROVH');" title="Other Score">

                <font><?php echo $match['ior_OVH'];?></font>
        </a>
        <?php } ?>
    </td>
  </tr>
  <tr id="TR1_<?php echo $match['dategh'];?>">
    <td class="b_cen">
        <?php if($match['ior_H0C1']){ ?>
        <a class="<?php echo $oddsBackground[$match['gid']]['ior_H0C1'];?>" href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH0C1');" title="0:1">

                <font><?php echo $match['ior_H0C1'];?></font>
        </a>
        <?php } ?>
    </td>
    <td class="b_cen">
        <?php if($match['ior_H0C2']){ ?>
        <a class="<?php echo $oddsBackground[$match['gid']]['ior_H0C2'];?>" href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH0C2');" title="0:2">

                <font><?php echo $match['ior_H0C2'];?></font>
        </a>
        <?php } ?>
    </td>
    <td class="b_cen">
        <?php if($match['ior_H1C2']){ ?>
        <a class="<?php echo $oddsBackground[$match['gid']]['ior_H1C2'];?>" href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH1C2');" title="1:2">

                <font><?php echo $match['ior_H1C2'];?></font>
        </a>
        <?php } ?>
    </td>
    <td class="b_cen">
        <?php if($match['ior_H0C3']){ ?>
        <a class="<?php echo $oddsBackground[$match['gid']]['ior_H0C3'];?>" href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH0C3');" title="0:3">

                <font><?php echo $match['ior_H0C3'];?></font>
        </a>
        <?php } ?>
    </td>
    <td class="b_cen">
        <?php if($match['ior_H1C3']){ ?>
        <a class="<?php echo $oddsBackground[$match['gid']]['ior_H1C3'];?>" href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH1C3');" title="1:3">

                <font><?php echo $match['ior_H1C3'];?></font>
        </a>
        <?php } ?>
    </td>
    <td class="b_cen">
        <?php if($match['ior_H2C3']){ ?>
        <a class="<?php echo $oddsBackground[$match['gid']]['ior_H2C3'];?>" href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH2C3');" title="2:3">

                <font><?php echo $match['ior_H2C3'];?></font>
        </a>
        <?php } ?>
    </td>
    <td class="b_cen">
        <?php if($match['ior_H0C4']){ ?>
        <a class="<?php echo $oddsBackground[$match['gid']]['ior_H0C4'];?>" href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH0C4');" title="0:4">

                <font><?php echo $match['ior_H0C4'];?></font>
        </a>
        <?php } ?>
    </td>
    <td class="b_cen">
        <?php if($match['ior_H1C4']){ ?>
        <a class="<?php echo $oddsBackground[$match['gid']]['ior_H1C4'];?>" href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH1C4');" title="1:4">

                <font><?php echo $match['ior_H1C4'];?></font>
        </a>
        <?php } ?>
    </td>
    <td class="b_cen">
        <?php if($match['ior_H2C4']){ ?>
        <a class="<?php echo $oddsBackground[$match['gid']]['ior_H2C4'];?>" href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH2C4');" title="2:4">

                <font><?php echo $match['ior_H2C4'];?></font>
        </a>
        <?php } ?>
    </td>
    <td class="b_cen">
        <?php if($match['ior_H3C4']){ ?>
        <a class="<?php echo $oddsBackground[$match['gid']]['ior_H3C4'];?>" href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RPD','<?php echo $match['bet_Url']; ?>RH3C4');" title="3:4">

                <font><?php echo $match['ior_H3C4'];?></font>
        </a>
        <?php } ?>
    </td>
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
