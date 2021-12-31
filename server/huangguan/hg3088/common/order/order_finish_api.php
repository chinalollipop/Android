<?php
//session_start();
/**
 * 手机版，下注接口
 *
 * gid  比赛盘口唯一ID
 * active  1
 * line_type  玩法列号
 * odd_f_type  H 香港盘
 * gold  金额
 * type   H 主队独赢 C 客队独赢 N 和局  （ 篮球： O 大  U 小）
 * pay_type   0 信用额投注  1 现金投注
 * ioradio_r_h  赔率 （让球，大小，半场让球，半场大小 ）投注时，传参
 * rtype  单双玩法投注传参，让后赋值给mtype   ODD 单 EVEN 双
 * wtype 篮球滚球半场得分大小下注传参  主队  ROUH   客队 ROUC
 *
 * strong
 * gnum
 * concede_h
 * radio_h
 *
 */
//include('../include/address.mem.php');
//include_once('../include/config.inc.php');
//require_once("../../../common/sportCenterData.php");
//require ("../include/define_function_list.inc.php");
//require ("../include/curl_http.php");

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    exit(json_encode(["err"=>-1,"msg"=>"请重新登录"]));
}
$randomNum = $_REQUEST['randomNum']; // 随机整数
if(!$randomNum){
    exit(json_encode(["err"=>-101,"msg"=>"参数不对"]));
}
if($randomNum == $_SESSION['randomNum']){ // 重复提交
    exit(json_encode(["err"=>-100,"msg"=>"请不要重复下注"]));
}else{ // 正常提交
    $_SESSION['randomNum']= $randomNum ;

    //接收传递过来的参数：其中赔率和位置需要进行判断
    $uid=$_SESSION['Oid'];
    $langx=$_SESSION['Language'];
    $gold=$_REQUEST['gold'];
    $active=$_REQUEST['active'];
    $line=$_REQUEST['line_type'];
    $gid=$_REQUEST['gid'];
    $type=$_REQUEST['type'];
    $rtype=$_REQUEST['rtype'];
    $wtype=$_REQUEST['wtype'];
    $gnum=$_REQUEST['gnum'];
    $cate=$_REQUEST['cate'];
    $ioradio_r_h=$_REQUEST['ioradio_r_h'];
    $odd_f_type=$_REQUEST['odd_f_type'];

    $gameswitch = judgeBetSwitch('BK') ; // 篮球投注开关
    if($cate=='BK' || $cate=='BK_RB'){ // 限制篮球投注
        if($gameswitch){ // 停用 篮球
            exit(json_encode(["err"=>-1001,"msg"=>"$Order_This_match_is_closed_Please_try_again"]));
        }
    }

    //require ("../include/traditional.$langx.inc.php");
    //下注时的赔率：应该根据盘口进行转换后，与数据库中的赔率进行比较。若不相同，返回下注。

    $sql = "select ratio,Money,CurType from ".DBPREFIX.MEMBERTABLE." where ID='{$_SESSION['userid']}' for update";
    $result = mysqli_query($dbMasterLink,$sql);
    $memrow = mysqli_fetch_assoc($result);
    $open=$_SESSION['OpenType'];
    $pay_type =$_SESSION['Pay_Type'];
    $memname=$_SESSION['UserName'];
    $agents=$_SESSION['Agents']; // 代理 D
    $world=$_SESSION['World']; // 总代 C
    $corprator=$_SESSION['Corprator']; // 股东 B
    $super=$_SESSION['Super']; // 公司 A
    $admin=$_SESSION['Admin']; // 管理员 （？子账号）
    $w_ratio=$memrow['ratio'];
    $HMoney=$Money=$memrow['Money'];
    if ($HMoney < $gold || $HMoney<0 || $gold<0){
        exit(json_encode(["err"=>-2,"msg"=>"下注金額不可大於信用額度。".rand(1,199)]));
    }
    $w_current=$memrow['CurType'];
    $memid=$_SESSION['userid'];
    $test_flag=$_SESSION['test_flag'];

    //_____________________________________________滚球刷赔率 Start
    if ($cate == 'FT_RB' || $cate == 'BK_RB'){
        $allcount=0;
        $accoutArr = array();
        if($flushWay == 'ra') { //正网
            $accoutArr = getFlushWaterAccount();//数组随机排序
        }
        $accoutArrNum = count($accoutArr);
        $curl = new Curl_HTTP_Client();
        $curl->store_cookies("/tmp/cookies.txt");
        $curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
        foreach($accoutArr as $key=>$value){//在扩展表中获取账号重新刷水
            $allcount = $allcount + 1;
            $site=$value['Datasite'];
            $suid=$value['Uid'];

            // 篮球滚球
            if ($cate == 'BK_RB'){

                $curl->set_referrer("".$site."/app/member/BK_index.php?rtype=re&uid=$suid&langx=zh-cn&mtype=3");
                switch ($line){
                    case '10': // 滚球大小
                        $html_data=$curl->fetch_url("".$site."/app/member/BK_order/BK_order_rou.php?gid=$gid&uid=$suid&type=$type&gnum=$gnum&odd_f_type=$odd_f_type");
                        break;
                    case '9': // 滚球让球
                        $html_data=$curl->fetch_url("".$site."/app/member/BK_order/BK_order_re.php?gid=$gid&uid=$suid&type=$type&gnum=$gnum&strong=$strong&odd_f_type=$odd_f_type");
                        break;

                    case '21': // 滚球独赢
                        $html_data = $curl->fetch_url("" . $site . "/app/member/BK_order/BK_order_rm.php?gid=$gid&uid=$suid&type=$type&odd_f_type=$odd_f_type");
                        break;
                    case '20': // 滚球得分大小
                        $html_data = $curl->fetch_url("" . $site . "/app/member/BK_order/BK_order_rouhc.php?gid=$gid&uid=$suid&wtype=$wtype&type=O&odd_f_type=$odd_f_type&langx=$langx");
                        break;
                }
            }

            // 足球滚球
            if ($cate == 'FT_RB'){

                $curl->set_referrer("".$site."/app/member/FT_index.php?rtype=re&uid=$suid&langx=zh-cn&mtype=3");
                switch ($line){

                    case '10': // 滚球独赢
                        $html_data=$curl->fetch_url("".$site."/app/member/FT_order/FT_order_rou.php?gid=$gid&uid=$suid&type=$type&gnum=$gnum&odd_f_type=$odd_f_type");
                        break;
                    case '9': // 滚球让球
                        $html_data=$curl->fetch_url("".$site."/app/member/FT_order/FT_order_re.php?gid=$gid&uid=$suid&type=$type&gnum=$gnum&strong=$strong&odd_f_type=$odd_f_type");
                        break;
                    case '21': // 滚球大小
                        $html_data=$curl->fetch_url("".$site."/app/member/FT_order/FT_order_rm.php?gid=$gid&uid=$suid&type=$type&gnum=$gnum&odd_f_type=$odd_f_type");
                        break;
                    case '20':
                        $sgid=$gid+1;
                        $html_data=$curl->fetch_url("".$site."/app/member/FT_order/FT_order_hrou.php?gid=$sgid&uid=$suid&type=$type&gnum=$gnum&odd_f_type=$odd_f_type");
                        break;
                    case '19':
                        $sgid=$gid+1;
                        $html_data=$curl->fetch_url("".$site."/app/member/FT_order/FT_order_hre.php?gid=$sgid&uid=$suid&type=$type&gnum=$gnum&strong=$strong&odd_f_type=$odd_f_type");
                        break;
                    case '31':
                        $sgid=$gid+1;
                        $html_data=$curl->fetch_url("".$site."/app/member/FT_order/FT_order_hrm.php?gid=$sgid&uid=$suid&type=$type&gnum=$gnum&odd_f_type=$odd_f_type");
                        break;

                }
            }
//print_r($html_data); die;
            $msg_c=explode("@",$html_data);
            if(sizeof($msg_c)>1){
                break;
            }elseif($allcount==$accoutArrNum){
                exit(json_encode(["err"=>-10,"msg"=>"$Order_Odd_changed_please_game_again"]));
            }
        }
    }

    //_____________________________________________滚球刷赔率 End

//    $fields = "MB_Team,TG_Team,MB_Team_tw,TG_Team_tw,MB_Team_en,TG_Team_en,M_Date,ShowTypeR,ShowTypeRB,M_League,M_League_tw,M_League_en,MB_Ball,TG_Ball,MB_Win_Rate,TG_Win_Rate,M_Flat_Rate,MB_Dime_Rate_RB,TG_Dime_Rate_RB,MB_Dime_Rate_RB_H,TG_Dime_Rate_RB_H,MB_Dime_RB,TG_Dime_RB,MB_Dime_RB_H,TG_Dime_RB_H,MB_Dime_RB_S_H,MB_Dime_Rate_RB_S_H,TG_Dime_RB_S_H,TG_Dime_Rate_RB_S_H,S_Single_Rate,S_Double_Rate,MB_LetB_Rate_RB,TG_LetB_Rate_RB,M_LetB_RB,MB_MID,TG_MID";
    if ($cate == 'BK_RB' || $cate == 'FT_RB'){
        if($sgid%2 == 1){
            $mysql = "select * from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `MID`=$sgid-1 and Open=1 and MB_Team!='' and MB_Team_tw!=''";
        }else{
            $mysql = "select * from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."` where `MID`='$gid' and Open=1 and MB_Team!='' and MB_Team_tw!=''";
        }
    }else{
        //判断此赛程是否已经关闭：取出此场次信息
        $mysql = "select * from `".DBPREFIX.SPORT_FLUSH_MATCH_TABLE."`  where `M_Start`>now() and `MID`=$gid and Cancel=0 and Open=1 and MB_Team!='' and MB_Team_tw!=''";
    }

    $result = mysqli_query($dbLink,$mysql);
    $cou=mysqli_num_rows($result);
    if($cou==0){
        exit(json_encode(["err"=>-3,"msg"=>"赛程已关闭,无法进行交易!!".rand(1,199)]));
    }
    $row = mysqli_fetch_assoc($result);

    $mb_ball = $row['MB_Ball'];
    $tg_ball = $row['TG_Ball'];
    $betid = '';
    $order_btype ='' ; // 上半场赛事标志

    if($cate == 'BK_RB'){
        if($row['MB_Team']||$row['M_Duration']){
            $team_active=$team_time=$M_Duration='' ;
            $M_Duration = explode('-',$row['M_Duration']);
            $mbTeamArr = explode('-',$row['MB_Team']);
            preg_match('/\d+/',$mbTeamArr[1],$mbTeamArrList);
            if($mbTeamArrList[0]==2){
                $team_active ='第二节';
                $newDataArray[$MID]['headShow']=0;
            }elseif($mbTeamArrList[0]==3){
                $team_active ='第三节';
                $newDataArray[$MID]['headShow']=0;
            }elseif($mbTeamArrList[0]==4){
                $team_active ='第四节';
                $newDataArray[$MID]['headShow']=0;
            }else{
                switch ($M_Duration[0]) {
                    case 'Q1':
                        $team_active ='第一节';
                        break;
                    case 'Q2':
                        $team_active ='第二节';
                        break;
                    case 'Q3':
                        $team_active ='第三节';
                        break;
                    case 'Q4':
                        $team_active ='第四节';
                        break;
                    case 'H1':
                        $team_active ='上半场';
                        break;
                    case 'H2':
                        $team_active ='下半场';
                        break;
                    case 'OT':
                        $team_active ='加时';
                        break;
                    case 'HT':
                        $team_active ='半场';
                        break;
                }
            }

            $team_time ='';
            if($M_Duration[1] && $M_Duration[1] > 0){ // 转化时间
                $team_hour = floor($M_Duration[1]/3600); // 小时不要
                $team_minute = floor(($M_Duration[1]-3600 * $team_hour)/60);
                $team_second = floor((($M_Duration[1]-3600 * $team_hour) - 60 * $team_minute) % 60);
                $team_time = ($team_minute>9?$team_minute:"0".$team_minute).':'.($team_second>9?$team_second:"0".$team_second );
            }
            $betid = $team_active.$team_time;
        }
    }


    if($cate == 'BK_RB' && in_array($line,array(9,10,20,21)) || $cate == 'FT_RB' && in_array($line,array(9,10,19,20,21,31))){ // 非滚球下注不需要显示比分
        $inball=$row['MB_Ball'].":".$row['TG_Ball'];
        //$scoresStr="&nbsp;&nbsp;<FONT color=red><b>".$row['MB_Ball'].":".$row['TG_Ball']."</b></FONT>";

    }else{
        // $scoresStr="";
        $inball='';
    }

    //取出写入数据库的四种语言的客队名称
    $w_tg_team=$row['TG_Team'];
    $w_tg_team_tw=$row['TG_Team_tw'];
    $w_tg_team_en=$row['TG_Team_en'];

    //取出四种语言的主队名称，并去掉其中的“主”和“中”字样
    $w_mb_team=filiter_team(trim($row['MB_Team']));
    $w_mb_team_tw=filiter_team(trim($row['MB_Team_tw']));
    $w_mb_team_en=filiter_team(trim($row['MB_Team_en']));

    $w_mb_mid=$row['MB_MID'];
    $w_tg_mid=$row['TG_MID'];

    //取出当前字库的主客队伍名称
    $s_mb_team=filiter_team($row[$mb_team]);
    $s_tg_team=filiter_team($row[$tg_team]);

    //联盟处理:生成写入数据库的联盟样式和显示的样式，二者有区别
    $s_sleague=$row[$m_league];

    //下注时间
    $m_date=$row["M_Date"];

    // --------------------------------------------------- 匹配showtype的值，方便结算派彩使用 Start
    if ($cate == 'BK_RB' || $cate == 'FT_RB') {
        // 足球滚球半场 linetype 11半场独赢，12半场让球，13半场大小，31半场滚球独赢，19半场滚球让球，20半场滚球大小
        if ( $cate == 'FT_RB' and ($line==11 or $line==12 or $line==13 or $line==31 or $line==19 or $line==20)){
            $showtype = $row['ShowTypeHRB'];
        }elseif($cate == 'BK_RB' and ($line==9 or $line==10 or $line==20 or $line==21)){ // 篮球滚球半场 linetype 9滚球让球，10滚球大小，21滚球独赢，20滚球球队得分大小
            $showtype = $row["ShowTypeRB"];
        }else{ // 足球滚球全场
            $showtype = $row["ShowTypeRB"];
        }
    }else{ // 足球今日赛事，足球早盘，篮球今日赛事、篮球早盘
        $showtype = $row["ShowTypeR"];
    }
    // --------------------------------------------------- 匹配showtype的值，方便结算派彩使用 End

    $bettime=date('Y-m-d H:i:s');
    $m_start=strtotime($row['M_Start']);
    $datetime=time();

//	if($cate == 'FT' || $cate == 'BK' ){
//        if ($m_start-$datetime<120){
//            exit(json_encode(["err"=>-4,"msg"=>"赛程已关闭,无法进行交易!!".rand(1,199)]));
//        }
//
//    }elseif( $cate == 'FT_RB' || $cate == 'BK_RB' ){
//        if ($datetime-$m_start<120){
//            exit(json_encode(["err"=>-4,"msg"=>"赛程已关闭,无法进行交易!!".rand(1,199)]));
//        }
//    }

    //根据下注的类型进行处理：构建成新的数据格式，准备写入数据库
    if($cate=='FT'||$cate=='FT_RB' ){

        switch ($line){

            case 1:
                $bet_type='独赢';
                $bet_type_tw='獨贏';
                $bet_type_en="1x2";
                $caption=$Order_FT.$Order_1_x_2_betting_order;
                switch ($type){
                    case "H":
                        $w_m_place=$w_mb_team;
                        $w_m_place_tw=$w_mb_team_tw;
                        $w_m_place_en=$w_mb_team_en;
                        $s_m_place=$s_mb_team;
                        $w_m_rate=change_rate($open,$row["MB_Win_Rate"]);
                        $mtype='MH';
                        break;
                    case "C":
                        $w_m_place=$w_tg_team;
                        $w_m_place_tw=$w_tg_team_tw;
                        $w_m_place_en=$w_tg_team_en;
                        $s_m_place=$s_tg_team;
                        $w_m_rate=change_rate($open,$row["TG_Win_Rate"]);
                        $mtype='MC';
                        break;
                    case "N":
                        $w_m_place="和局";
                        $w_m_place_tw="和局";
                        $w_m_place_en="Flat";
                        $s_m_place=$Draw;
                        $w_m_rate=change_rate($open,$row["M_Flat_Rate"]);
                        $mtype='MN';
                        break;
                }
                $Sign="VS.";
                $grape="";
                $gwin=($w_m_rate-1)*$gold;
                $ptype='M';
                break;
            case 2:
                $bet_type='让球';
                $bet_type_tw="讓球";
                $bet_type_en="Handicap";
                $caption=$Order_FT.$Order_Handicap_betting_order;
                $rate=get_other_ioratio($odd_f_type,$row["MB_LetB_Rate"],$row["TG_LetB_Rate"],100);
                switch ($type){
                    case "H":
                        $w_m_place=$w_mb_team;
                        $w_m_place_tw=$w_mb_team_tw;
                        $w_m_place_en=$w_mb_team_en;
                        $s_m_place=$s_mb_team;
                        $w_m_rate=change_rate($open,$rate[0]);
                        $mtype='RH';
                        break;
                    case "C":
                        $w_m_place=$w_tg_team;
                        $w_m_place_tw=$w_tg_team_tw;
                        $w_m_place_en=$w_tg_team_en;
                        $s_m_place=$s_tg_team;
                        $w_m_rate=change_rate($open,$rate[1]);
                        $mtype='RC';
                        break;
                }
                $Sign=$row['M_LetB'];
                $grape=$Sign;
                if ($showtype=="H"){
                    $l_team=$s_mb_team;
                    $r_team=$s_tg_team;
                    $w_l_team=$w_mb_team;
                    $w_l_team_tw=$w_mb_team_tw;
                    $w_l_team_en=$w_mb_team_en;
                    $w_r_team=$w_tg_team;
                    $w_r_team_tw=$w_tg_team_tw;
                    $w_r_team_en=$w_tg_team_en;
                }else{
                    $r_team=$s_mb_team;
                    $l_team=$s_tg_team;
                    $w_r_team=$w_mb_team;
                    $w_r_team_tw=$w_mb_team_tw;
                    $w_r_team_en=$w_mb_team_en;
                    $w_l_team=$w_tg_team;
                    $w_l_team_tw=$w_tg_team_tw;
                    $w_l_team_en=$w_tg_team_en;
                }
                $s_mb_team=$l_team;
                $s_tg_team=$r_team;
                $w_mb_team=$w_l_team;
                $w_mb_team_tw=$w_l_team_tw;
                $w_mb_team_en=$w_l_team_en;
                $w_tg_team=$w_r_team;
                $w_tg_team_tw=$w_r_team_tw;
                $w_tg_team_en=$w_r_team_en;

                if ($odd_f_type=='H'){
                    $gwin=($w_m_rate)*$gold;
                }else if ($odd_f_type=='M' or $odd_f_type=='I'){
                    if ($w_m_rate<0){
                        $gwin=$gold;
                    }else{
                        $gwin=($w_m_rate)*$gold;
                    }
                }else if ($odd_f_type=='E'){
                    $gwin=($w_m_rate-1)*$gold;
                }
                $ptype='R';
                break;
            case 3:
                $bet_type='大小';
                $bet_type_tw="大小";
                $bet_type_en="Over/Under";
                $caption=$Order_FT.$Order_Over_Under_betting_order;
                $rate=get_other_ioratio($odd_f_type,$row["MB_Dime_Rate"],$row["TG_Dime_Rate"],100);
                switch ($wtype){
                    case "OUH":
                        $w_m_place=$row["MB_Dime"];
                        $w_m_place=str_replace('O','大&nbsp;',$w_m_place);
                        $w_m_place_tw=$row["MB_Dime"];
                        $w_m_place_tw=str_replace('O','大&nbsp;',$w_m_place_tw);
                        $w_m_place_en=$row["MB_Dime"];
                        $w_m_place_en=str_replace('O','over&nbsp;',$w_m_place_en);
                        $m_place=$row["MB_Dime"];
                        $s_m_place=$row["MB_Dime"];
                        if ($langx=="zh-cn"){
                            $s_m_place=str_replace('O','大&nbsp;',$s_m_place);
                        }else if ($langx=="zh-cn"){
                            $s_m_place=str_replace('O','大&nbsp;',$s_m_place);
                        }else if ($langx=="en-us" or $langx=="th-tis"){
                            $s_m_place=str_replace('O','over&nbsp;',$s_m_place);
                        }
                        $w_m_rate=change_rate($open,$rate[0]);
                        $mtype='OUH';
                        break;
                    case "OUC":
                        $w_m_place=$row["TG_Dime"];
                        $w_m_place=str_replace('U','小&nbsp;',$w_m_place);
                        $w_m_place_tw=$row["TG_Dime"];
                        $w_m_place_tw=str_replace('U','小&nbsp;',$w_m_place_tw);
                        $w_m_place_en=$row["TG_Dime"];
                        $w_m_place_en=str_replace('U','under&nbsp;',$w_m_place_en);
                        $m_place=$row["TG_Dime"];
                        $s_m_place=$row["TG_Dime"];
                        if ($langx=="zh-cn"){
                            $s_m_place=str_replace('U','小&nbsp;',$s_m_place);
                        }else if ($langx=="zh-cn"){
                            $s_m_place=str_replace('U','小&nbsp;',$s_m_place);
                        }else if ($langx=="en-us" or $langx=="th-tis"){
                            $s_m_place=str_replace('U','under&nbsp;',$s_m_place);
                        }

                        $w_m_rate=change_rate($open,$rate[1]);
                        $mtype='OUC';
                        break;
                }
                $Sign="VS.";
                $grape=$m_place;
                if ($odd_f_type=='H'){
                    $gwin=($w_m_rate)*$gold;
                }else if ($odd_f_type=='M' or $odd_f_type=='I'){
                    if ($w_m_rate<0){
                        $gwin=$gold;
                    }else{
                        $gwin=($w_m_rate)*$gold;
                    }
                }else if ($odd_f_type=='E'){
                    $gwin=($w_m_rate-1)*$gold;
                }
                $ptype='OU';
                break;
            case 5:
                $bet_type='单双';
                $bet_type_tw="單雙";
                $bet_type_en="Odd/Even";
                $caption=$Order_FT.$Order_Odd_Even_betting_order;
                switch ($rtype){
                    case "ODD":
                        $w_m_place='单';
                        $w_m_place_tw='單';
                        $w_m_place_en='odd';
                        $s_m_place='('.$Order_Odd.')';
                        $w_m_rate=change_rate($open,$row["S_Single_Rate"]);
                        break;
                    case "EVEN":
                        $w_m_place='双';
                        $w_m_place_tw='雙';
                        $w_m_place_en='even';
                        $s_m_place='('.$Order_Even.')';
                        $w_m_rate=change_rate($open,$row["S_Double_Rate"]);
                        break;
                }
                $Sign="VS.";
                $gwin=($w_m_rate-1)*$gold;
                $ptype='EO';
                $mtype=$rtype;
                break;
            case 11:
                $bet_type='半场独赢';
                $bet_type_tw="半場獨贏";
                $bet_type_en="1st Half 1x2";
                $btype="-&nbsp;<font color=red><b>[$Order_1st_Half]</b></font>";
                $caption=$Order_FT.$Order_1st_Half_1_x_2_betting_order;
                $order_btype = "-&nbsp;<font color=#666666>[$Order_1st_Half]</font>&nbsp;" ;
                switch ($type){
                    case "H":
                        $w_m_place=$w_mb_team;
                        $w_m_place_tw=$w_mb_team_tw;
                        $w_m_place_en=$w_mb_team_en;
                        $s_m_place=$row[$mb_team];
                        $w_m_rate=change_rate($open,$row["MB_Win_Rate_H"]);
                        $mtype='VMH';
                        break;
                    case "C":
                        $w_m_place=$w_tg_team;
                        $w_m_place_tw=$w_tg_team_tw;
                        $w_m_place_en=$w_tg_team_en;
                        $s_m_place=$row[$tg_team];
                        $w_m_rate=change_rate($open,$row["TG_Win_Rate_H"]);
                        $mtype='VMC';
                        break;
                    case "N":
                        $w_m_place="和局";
                        $w_m_place_tw="和局";
                        $w_m_place_en="Flat";
                        $s_m_place=$Draw;
                        $w_m_rate=change_rate($open,$row["M_Flat_Rate_H"]);
                        $mtype='VMN';
                        break;
                }
                $Sign="VS.";
                $grape="";
                $gwin=($w_m_rate-1)*$gold;
                $ptype='VM';
                break;
            case 12:
                $bet_type='半场让球';
                $bet_type_tw="半場讓球";
                $bet_type_en="1st Half Handicap";
                $btype="-&nbsp;<font color=red><b>[$Order_1st_Half]</b></font>";
                $caption=$Order_FT.$Order_1st_Half_Handicap_betting_order;
                $rate=get_other_ioratio($odd_f_type,$row["MB_LetB_Rate_H"],$row["TG_LetB_Rate_H"],100);
                $order_btype = "-&nbsp;<font color=#666666>[$Order_1st_Half]</font>&nbsp;" ;
                switch ($type){
                    case "H":
                        $w_m_place=$w_mb_team;
                        $w_m_place_tw=$w_mb_team_tw;
                        $w_m_place_en=$w_mb_team_en;
                        $s_m_place=$row[$mb_team];
                        $w_m_rate=change_rate($open,$rate[0]);
                        $turn_url="/app/member/FT_order/FT_order_hr.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&strong=".$strong."&odd_f_type=".$odd_f_type;
                        $mtype='VRH';
                        break;
                    case "C":
                        $w_m_place=$w_tg_team;
                        $w_m_place_tw=$w_tg_team_tw;
                        $w_m_place_en=$w_tg_team_en;
                        $s_m_place=$row[$tg_team];
                        $w_m_rate=change_rate($open,$rate[1]);
                        $turn_url="/app/member/FT_order/FT_order_hr.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&strong=".$strong."&odd_f_type=".$odd_f_type;
                        $mtype='VRC';
                        break;
                }
                $Sign=$row['M_LetB_H'];
                $grape=$Sign;
                if ($showtype=="H"){
                    $l_team=$s_mb_team;
                    $r_team=$s_tg_team;

                    $w_l_team=$w_mb_team;
                    $w_l_team_tw=$w_mb_team_tw;
                    $w_l_team_en=$w_mb_team_en;
                    $w_r_team=$w_tg_team;
                    $w_r_team_tw=$w_tg_team_tw;
                    $w_r_team_en=$w_tg_team_en;
                }else{
                    $r_team=$s_mb_team;
                    $l_team=$s_tg_team;
                    $w_r_team=$w_mb_team;
                    $w_r_team_tw=$w_mb_team_tw;
                    $w_r_team_en=$w_mb_team_en;
                    $w_l_team=$w_tg_team;
                    $w_l_team_tw=$w_tg_team_tw;
                    $w_l_team_en=$w_tg_team_en;
                }
                $s_mb_team=$l_team;
                $s_tg_team=$r_team;
                $w_mb_team=$w_l_team;
                $w_mb_team_tw=$w_l_team_tw;
                $w_mb_team_en=$w_l_team_en;
                $w_tg_team=$w_r_team;
                $w_tg_team_tw=$w_r_team_tw;
                $w_tg_team_en=$w_r_team_en;
                if ($odd_f_type=='H'){
                    $gwin=($w_m_rate)*$gold;
                }else if ($odd_f_type=='M' or $odd_f_type=='I'){
                    if ($w_m_rate<0){
                        $gwin=$gold;
                    }else{
                        $gwin=($w_m_rate)*$gold;
                    }
                }else if ($odd_f_type=='E'){
                    $gwin=($w_m_rate-1)*$gold;
                }
                $ptype='VR';
                break;
            case 13:
                $bet_type='半场大小';
                $bet_type_tw="半場大小";
                $bet_type_en="1st Half Over/Under";
                $caption=$Order_FT.$Order_1st_Half_Over_Under_betting_order;
                $btype="-&nbsp;<font color=red><b>[$Order_1st_Half]</b></font>";
                $rate=get_other_ioratio($odd_f_type,$row["MB_Dime_Rate_H"],$row["TG_Dime_Rate_H"],100);
                $order_btype = "-&nbsp;<font color=#666666>[$Order_1st_Half]</font>&nbsp;" ;
                switch ($wtype){
                    case "OUH":
                        $w_m_place=$row["MB_Dime_H"];
                        $w_m_place=str_replace('O','大&nbsp;',$w_m_place);
                        $w_m_place_tw=$row["MB_Dime_H"];
                        $w_m_place_tw=str_replace('O','大&nbsp;',$w_m_place_tw);
                        $w_m_place_en=$row["MB_Dime_H"];
                        $w_m_place_en=str_replace('O','over&nbsp;',$w_m_place_en);
                        $m_place=$row["MB_Dime_H"];
                        $s_m_place=$row["MB_Dime_H"];
                        if ($langx=="zh-cn"){
                            $s_m_place=str_replace('O','大&nbsp;',$s_m_place);
                        }else if ($langx=="zh-cn"){
                            $s_m_place=str_replace('O','大&nbsp;',$s_m_place);
                        }else if ($langx=="en-us" or $langx=="th-tis"){
                            $s_m_place=str_replace('O','over&nbsp;',$s_m_place);
                        }
                        $w_m_rate=change_rate($open,$rate[0]);
                        $mtype='VOUH';
                        break;
                    case "OUC":
                        $w_m_place=$row["TG_Dime_H"];
                        $w_m_place=str_replace('U','小&nbsp;',$w_m_place);
                        $w_m_place_tw=$row["TG_Dime_H"];
                        $w_m_place_tw=str_replace('U','小&nbsp;',$w_m_place_tw);
                        $w_m_place_en=$row["TG_Dime_H"];
                        $w_m_place_en=str_replace('U','under&nbsp;',$w_m_place_en);
                        $m_place=$row["TG_Dime_H"];
                        $s_m_place=$row["TG_Dime_H"];
                        if ($langx=="zh-cn"){
                            $s_m_place=str_replace('U','小&nbsp;',$s_m_place);
                        }else if ($langx=="zh-cn"){
                            $s_m_place=str_replace('U','小&nbsp;',$s_m_place);
                        }else if ($langx=="en-us" or $langx=="th-tis"){
                            $s_m_place=str_replace('U','under&nbsp;',$s_m_place);
                        }
                        $w_m_rate=change_rate($open,$rate[1]);
                        $mtype='VOUC';
                        break;
                }
                $Sign="VS.";
                $grape=$m_place;
                if ($odd_f_type=='H'){
                    $gwin=($w_m_rate)*$gold;
                }else if ($odd_f_type=='M' or $odd_f_type=='I'){
                    if ($w_m_rate<0){
                        $gwin=$gold;
                    }else{
                        $gwin=($w_m_rate)*$gold;
                    }
                }else if ($odd_f_type=='E'){
                    $gwin=($w_m_rate-1)*$gold;
                }
                $ptype='VOU';
                break;
            case 21:
                $bet_type='滚球独赢';
                $bet_type_tw='滾球獨贏';
                $bet_type_en="Running 1x2";
                $caption=$Order_FT.$Order_Running_1_x_2_betting_order;
                switch ($type){
                    case "H":
                        $w_m_place=$w_mb_team;
                        $w_m_place_tw=$w_mb_team_tw;
                        $w_m_place_en=$w_mb_team_en;
                        $s_m_place=$s_mb_team;
                        $w_m_rate=change_rate($open,$row["MB_Win_Rate_RB"]);
                        $mtype='RMH';
                        break;
                    case "C":
                        $w_m_place=$w_tg_team;
                        $w_m_place_tw=$w_tg_team_tw;
                        $w_m_place_en=$w_tg_team_en;
                        $s_m_place=$s_tg_team;
                        $w_m_rate=change_rate($open,$row["TG_Win_Rate_RB"]);
                        $mtype='RMC';
                        break;
                    case "N":
                        $w_m_place="和局";
                        $w_m_place_tw="和局";
                        $w_m_place_en="Flat";
                        $s_m_place=$Draw;
                        $w_m_rate=change_rate($open,$row["M_Flat_Rate_RB"]);
                        $mtype='RMN';
                        break;
                }

                $Sign="VS.";
                $grape=$type;
                $gwin=($w_m_rate-1)*$gold;
                $ptype='RM';
                break;
            case 9:
                $bet_type='滚球让球';
                $bet_type_tw="滾球讓球";
                $bet_type_en="Running Ball";
                $caption=$Order_FT.$Order_Running_Ball_betting_order;
                $rate=get_other_ioratio($odd_f_type,$row["MB_LetB_Rate_RB"],$row["TG_LetB_Rate_RB"],100);
                switch ($type){
                    case "H":
                        $w_m_place=$w_mb_team;
                        $w_m_place_tw=$w_mb_team_tw;
                        $w_m_place_en=$w_mb_team_en;
                        $s_m_place=$s_mb_team;
                        $w_m_rate=change_rate($open,$rate[0]);
                        $turn_url="/app/member/FT_order/FT_order_re.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&strong=".$strong."&odd_f_type=".$odd_f_type;
                        $mtype='RRH';
                        break;
                    case "C":
                        $w_m_place=$w_tg_team;
                        $w_m_place_tw=$w_tg_team_tw;
                        $w_m_place_en=$w_tg_team_en;
                        $s_m_place=$s_tg_team;
                        $w_m_rate=change_rate($open,$rate[1]);
                        $turn_url="/app/member/FT_order/FT_order_re.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&strong=".$strong."&odd_f_type=".$odd_f_type;
                        $mtype='RRC';
                        break;
                }
                $Sign=$row['M_LetB_RB'];
                $grape=$Sign;
                if (strtoupper($showtype)=="H"){
                    $l_team=$s_mb_team;
                    $r_team=$s_tg_team;
                    $w_l_team=$w_mb_team;
                    $w_l_team_tw=$w_mb_team_tw;
                    $w_l_team_en=$w_mb_team_en;
                    $w_r_team=$w_tg_team;
                    $w_r_team_tw=$w_tg_team_tw;
                    $w_r_team_en=$w_tg_team_en;
                    $inball=$row['MB_Ball'].":".$row['TG_Ball'];
                }else{
                    $r_team=$s_mb_team;
                    $l_team=$s_tg_team;
                    $w_r_team=$w_mb_team;
                    $w_r_team_tw=$w_mb_team_tw;
                    $w_r_team_en=$w_mb_team_en;
                    $w_l_team=$w_tg_team;
                    $w_l_team_tw=$w_tg_team_tw;
                    $w_l_team_en=$w_tg_team_en;
                    $inball=$row['TG_Ball'].":".$row['MB_Ball'];

                }
                $s_mb_team=$l_team;
                $s_tg_team=$r_team;
                $w_mb_team=$w_l_team;
                $w_mb_team_tw=$w_l_team_tw;
                $w_mb_team_en=$w_l_team_en;
                $w_tg_team=$w_r_team;
                $w_tg_team_tw=$w_r_team_tw;
                $w_tg_team_en=$w_r_team_en;
                if ($odd_f_type=='H'){
                    $gwin=($w_m_rate)*$gold;
                }else if ($odd_f_type=='M' or $odd_f_type=='I'){
                    if ($w_m_rate<0){
                        $gwin=$gold;
                    }else{
                        $gwin=($w_m_rate)*$gold;
                    }
                }else if ($odd_f_type=='E'){
                    $gwin=($w_m_rate-1)*$gold;
                }
                $ptype='RE';
                break;
            case 10:
                $bet_type='滚球大小';
                $bet_type_tw="滾球大小";
                $bet_type_en="Running Over/Under";
                $caption=$Order_FT.$Order_Running_Ball_Over_Under_betting_order;
                $rate=get_other_ioratio($odd_f_type,$row["MB_Dime_Rate_RB"],$row["TG_Dime_Rate_RB"],100);
                switch ($type){
                    case "C":
                        $w_m_place=$row["MB_Dime_RB"];
                        $w_m_place=str_replace('O','大&nbsp;',$w_m_place);
                        $w_m_place_tw=$row["MB_Dime_RB"];
                        $w_m_place_tw=str_replace('O','大&nbsp;',$w_m_place_tw);
                        $w_m_place_en=$row["MB_Dime_RB"];
                        $w_m_place_en=str_replace('O','over&nbsp;',$w_m_place_en);

                        $m_place=$row["MB_Dime_RB"];

                        $s_m_place=$row["MB_Dime_RB"];
                        if ($langx=="zh-cn"){
                            $s_m_place=str_replace('O','大&nbsp;',$s_m_place);
                        }else if ($langx=="zh-cn"){
                            $s_m_place=str_replace('O','大&nbsp;',$s_m_place);
                        }else if ($langx=="en-us" or $langx=='th-tis'){
                            $s_m_place=str_replace('O','over&nbsp;',$s_m_place);
                        }
                        $w_m_rate=change_rate($open,$rate[0]);
                        $turn_url="/app/member/FT_order/FT_order_rou.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&odd_f_type=".$odd_f_type;
                        $mtype='ROUH';
                        break;
                    case "H":
                        $w_m_place=$row["TG_Dime_RB"];
                        $w_m_place=str_replace('U','小&nbsp;',$w_m_place);
                        $w_m_place_tw=$row["TG_Dime_RB"];
                        $w_m_place_tw=str_replace('U','小&nbsp;',$w_m_place_tw);
                        $w_m_place_en=$row["TG_Dime_RB"];
                        $w_m_place_en=str_replace('U','under&nbsp;',$w_m_place_en);

                        $m_place=$row["TG_Dime_RB"];

                        $s_m_place=$row["TG_Dime_RB"];
                        if ($langx=="zh-cn"){
                            $s_m_place=str_replace('U','小&nbsp;',$s_m_place);
                        }else if ($langx=="zh-cn"){
                            $s_m_place=str_replace('U','小&nbsp;',$s_m_place);
                        }else if ($langx=="en-us" or $langx=='th-tis'){
                            $s_m_place=str_replace('U','under&nbsp;',$s_m_place);
                        }
                        $w_m_rate=change_rate($open,$rate[1]);
                        $turn_url="/app/member/FT_order/FT_order_rou.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&odd_f_type=".$odd_f_type;
                        $mtype='ROUC';
                        break;
                }
                $Sign="VS.";
                $grape=$m_place;
                if ($odd_f_type=='H'){
                    $gwin=($w_m_rate)*$gold;
                }else if ($odd_f_type=='M' or $odd_f_type=='I'){
                    if ($w_m_rate<0){
                        $gwin=$gold;
                    }else{
                        $gwin=($w_m_rate)*$gold;
                    }
                }else if ($odd_f_type=='E'){
                    $gwin=($w_m_rate-1)*$gold;
                }
                $ptype='ROU';
                break;
            case 31:
                $bet_type='半场滚球独赢';
                $bet_type_tw="半場滾球獨贏";
                $bet_type_en="1st Half Running 1x2";
                $btype="-&nbsp;<font color=red><b>[$Order_1st_Half]</b></font>";
                $caption=$Order_FT.$Order_1st_Half_Running_1_x_2_betting_order;
                $order_btype = "-&nbsp;<font color=#666666>[$Order_1st_Half]</font>&nbsp;" ;
                switch ($type){
                    case "H":
                        $w_m_place=$w_mb_team;
                        $w_m_place_tw=$w_mb_team_tw;
                        $w_m_place_en=$w_mb_team_en;
                        $s_m_place=$row[$mb_team];
                        $w_m_rate=change_rate($open,$row["MB_Win_Rate_RB_H"]);
                        $turn_url="/app/member/FT_order/FT_order_hrm.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&odd_f_type=".$odd_f_type;
                        $mtype='VRMH';
                        break;
                    case "C":
                        $w_m_place=$w_tg_team;
                        $w_m_place_tw=$w_tg_team_tw;
                        $w_m_place_en=$w_tg_team_en;
                        $s_m_place=$row[$tg_team];
                        $w_m_rate=change_rate($open,$row["TG_Win_Rate_RB_H"]);
                        $turn_url="/app/member/FT_order/FT_order_hrm.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&odd_f_type=".$odd_f_type;
                        $mtype='VRMC';
                        break;
                    case "N":
                        $w_m_place="和局";
                        $w_m_place_tw="和局";
                        $w_m_place_en="Flat";
                        $s_m_place=$Draw;
                        $w_m_rate=change_rate($open,$row["M_Flat_Rate_RB_H"]);
                        $turn_url="/app/member/FT_order/FT_order_hrm.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&odd_f_type=".$odd_f_type;
                        $mtype='VRMN';
                        break;
                }
                $Sign="VS.";
                $grape=$type;
                $gwin=($w_m_rate-1)*$gold;
                $ptype='VRM';
                break;
            case 19:
                $bet_type='半场滚球让球';
                $bet_type_tw="半場滾球讓球";
                $bet_type_en="1st Half Running Ball";
                $btype="-<font color=red><b>[$Order_1st_Half]</b></font>";
                $caption=$Order_FT.$Order_1st_Half_Running_Ball_betting_order;
                $rate=get_other_ioratio($odd_f_type,$row["MB_LetB_Rate_RB_H"],$row["TG_LetB_Rate_RB_H"],100);
                $order_btype = "-&nbsp;<font color=#666666>[$Order_1st_Half]</font>&nbsp;" ;
                switch ($type){
                    case "H":
                        $w_m_place=$w_mb_team;
                        $w_m_place_tw=$w_mb_team_tw;
                        $w_m_place_en=$w_mb_team_en;
                        $s_m_place=$s_mb_team;
                        $w_m_rate=change_rate($open,$rate[0]);
                        $turn_url="/app/member/FT_order/FT_order_hre.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&strong=".$strong."&odd_f_type=".$odd_f_type;
                        $mtype='VRRH';
                        break;
                    case "C":
                        $w_m_place=$w_tg_team;
                        $w_m_place_tw=$w_tg_team_tw;
                        $w_m_place_en=$w_tg_team_en;
                        $s_m_place=$s_tg_team;
                        $w_m_rate=change_rate($open,$rate[1]);
                        $turn_url="/app/member/FT_order/FT_order_hre.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&strong=".$strong."&odd_f_type=".$odd_f_type;
                        $mtype='VRRC';
                        break;
                }

                $Sign=$row['M_LetB_RB_H'];
                $grape=$Sign;

                if (strtoupper($showtype)=="H"){
                    $l_team=$s_mb_team;
                    $r_team=$s_tg_team;
                    $w_l_team=$w_mb_team;
                    $w_l_team_tw=$w_mb_team_tw;
                    $w_l_team_en=$w_mb_team_en;
                    $w_r_team=$w_tg_team;
                    $w_r_team_tw=$w_tg_team_tw;
                    $w_r_team_en=$w_tg_team_en;
                    $inball=$row['MB_Ball'].":".$row['TG_Ball'];
                }else{
                    $r_team=$s_mb_team;
                    $l_team=$s_tg_team;
                    $w_r_team=$w_mb_team;
                    $w_r_team_tw=$w_mb_team_tw;
                    $w_r_team_en=$w_mb_team_en;
                    $w_l_team=$w_tg_team;
                    $w_l_team_tw=$w_tg_team_tw;
                    $w_l_team_en=$w_tg_team_en;
                    $inball=$row['TG_Ball'].":".$row['MB_Ball'];

                }
                $s_mb_team=$l_team;
                $s_tg_team=$r_team;
                $w_mb_team=$w_l_team;
                $w_mb_team_tw=$w_l_team_tw;
                $w_mb_team_en=$w_l_team_en;
                $w_tg_team=$w_r_team;
                $w_tg_team_tw=$w_r_team_tw;
                $w_tg_team_en=$w_r_team_en;
                if ($odd_f_type=='H'){
                    $gwin=($w_m_rate)*$gold;
                }else if ($odd_f_type=='M' or $odd_f_type=='I'){
                    if ($w_m_rate<0){
                        $gwin=$gold;
                    }else{
                        $gwin=($w_m_rate)*$gold;
                    }
                }else if ($odd_f_type=='E'){
                    $gwin=($w_m_rate-1)*$gold;
                }
                $ptype='VRE';
                break;
            case 20:
                $bet_type='半场滚球大小';
                $bet_type_tw="半場滾球大小";
                $bet_type_en="1st Half Running Over/Under";
                $btype="- <font color=red><b>[$Order_1st_Half]</b></font>";
                $caption=$Order_FT.$Order_1st_Half_Running_Ball_Over_Under_betting_order;
                $rate=get_other_ioratio($odd_f_type,$row["MB_Dime_Rate_RB_H"],$row["TG_Dime_Rate_RB_H"],100);
                $order_btype = "-&nbsp;<font color=#666666>[$Order_1st_Half]</font>&nbsp;" ;
                switch ($type){
                    case "C":
                        $w_m_place=$row["MB_Dime_RB_H"];
                        $w_m_place=str_replace('O','大&nbsp;',$w_m_place);
                        $w_m_place_tw=$row["MB_Dime_RB_H"];
                        $w_m_place_tw=str_replace('O','大&nbsp;',$w_m_place_tw);
                        $w_m_place_en=$row["MB_Dime_RB_H"];
                        $w_m_place_en=str_replace('O','over&nbsp;',$w_m_place_en);

                        $m_place=$row["MB_Dime_RB_H"];

                        $s_m_place=$row["MB_Dime_RB_H"];
                        if ($langx=="zh-cn"){
                            $s_m_place=str_replace('O','大&nbsp;',$s_m_place);
                        }else if ($langx=="zh-cn"){
                            $s_m_place=str_replace('O','大&nbsp;',$s_m_place);
                        }else if ($langx=="en-us" or $langx=='th-tis'){
                            $s_m_place=str_replace('O','over&nbsp;',$s_m_place);
                        }
                        $w_m_rate=change_rate($open,$rate[0]);
                        $turn_url="/app/member/FT_order/FT_order_hrou.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&odd_f_type=".$odd_f_type;
                        $mtype='VROUH';
                        break;
                    case "H":
                        $w_m_place=$row["TG_Dime_RB_H"];
                        $w_m_place=str_replace('U','小&nbsp;',$w_m_place);
                        $w_m_place_tw=$row["TG_Dime_RB_H"];
                        $w_m_place_tw=str_replace('U','小&nbsp;',$w_m_place_tw);
                        $w_m_place_en=$row["TG_Dime_RB_H"];
                        $w_m_place_en=str_replace('U','under&nbsp;',$w_m_place_en);

                        $m_place=$row["TG_Dime_RB_H"];

                        $s_m_place=$row["TG_Dime_RB_H"];
                        if ($langx=="zh-cn"){
                            $s_m_place=str_replace('U','小&nbsp;',$s_m_place);
                        }else if ($langx=="zh-cn"){
                            $s_m_place=str_replace('U','小&nbsp;',$s_m_place);
                        }else if ($langx=="en-us" or $langx=='th-tis'){
                            $s_m_place=str_replace('U','under&nbsp;',$s_m_place);
                        }
                        $w_m_rate=change_rate($open,$rate[1]);
                        $turn_url="/app/member/FT_order/FT_order_hrou.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&odd_f_type=".$odd_f_type;
                        $mtype='VROUC';
                        break;
                }

                $Sign="VS.";
                $grape=$m_place;
                if ($odd_f_type=='H'){
                    $gwin=($w_m_rate)*$gold;
                }else if ($odd_f_type=='M' or $odd_f_type=='I'){
                    if ($w_m_rate<0){
                        $gwin=$gold;
                    }else{
                        $gwin=($w_m_rate)*$gold;
                    }
                }else if ($odd_f_type=='E'){
                    $gwin=($w_m_rate-1)*$gold;
                }
                $ptype='VROU';
                break;
        }
    }

    // 篮球 今日赛事
    if($cate=='BK' ){

        switch ($line){
            case 1: // 全场独赢 让球
                $bet_type='独赢';
                $bet_type_tw='獨贏';
                $bet_type_en="1x2";
                $caption=$Order_Basketball.$Order_1_x_2_betting_order;
                switch ($type){
                    case "H": // 独赢
                        $w_m_place=$w_mb_team;
                        $w_m_place_tw=$w_mb_team_tw;
                        $w_m_place_en=$w_mb_team_en;
                        $s_m_place=$s_mb_team;
                        $w_m_rate=change_rate($open,$row["MB_Win_Rate"]);
                        $mtype='MH';
                        break;
                    case "C": // 让球
                        $w_m_place=$w_tg_team;
                        $w_m_place_tw=$w_tg_team_tw;
                        $w_m_place_en=$w_tg_team_en;
                        $s_m_place=$s_tg_team;
                        $w_m_rate=change_rate($open,$row["TG_Win_Rate"]);
                        $mtype='MC';
                        break;
                    case "N":
                        $w_m_place="和局";
                        $w_m_place_tw="和局";
                        $w_m_place_en="Flat";
                        $s_m_place=$Draw;
                        $w_m_rate=change_rate($open,$row["M_Flat_Rate"]);
                        $mtype='MN';
                        break;
                }
                $Sign="VS.";
                $grape="";
                $gwin=($w_m_rate-1)*$gold;
                $ptype='M';
                break;
            case 2:
                $bet_type='让球';
                $bet_type_tw="讓球";
                $bet_type_en="Handicap";
                $caption=$Order_Basketball.$Order_Handicap_betting_order;
                $rate=get_other_ioratio($odd_f_type,$row["MB_LetB_Rate"],$row["TG_LetB_Rate"],100);
                switch ($type){
                    case "H":
                        $w_m_place=$w_mb_team;
                        $w_m_place_tw=$w_mb_team_tw;
                        $w_m_place_en=$w_mb_team_en;
                        $s_m_place=$s_mb_team;
                        $w_m_rate=change_rate($open,$rate[0]);
                        $mtype='RH';
                        break;
                    case "C":
                        $w_m_place=$w_tg_team;
                        $w_m_place_tw=$w_tg_team_tw;
                        $w_m_place_en=$w_tg_team_en;
                        $s_m_place=$s_tg_team;
                        $w_m_rate=change_rate($open,$rate[1]);
                        $mtype='RC';
                        break;
                }
                $Sign=$row['M_LetB'];
                $grape=$Sign;
                if ($showtype=="H"){
                    $l_team=$s_mb_team;
                    $r_team=$s_tg_team;
                    $w_l_team=$w_mb_team;
                    $w_l_team_tw=$w_mb_team_tw;
                    $w_l_team_en=$w_mb_team_en;
                    $w_r_team=$w_tg_team;
                    $w_r_team_tw=$w_tg_team_tw;
                    $w_r_team_en=$w_tg_team_en;
                }else{
                    $r_team=$s_mb_team;
                    $l_team=$s_tg_team;
                    $w_r_team=$w_mb_team;
                    $w_r_team_tw=$w_mb_team_tw;
                    $w_r_team_en=$w_mb_team_en;
                    $w_l_team=$w_tg_team;
                    $w_l_team_tw=$w_tg_team_tw;
                    $w_l_team_en=$w_tg_team_en;
                }
                $s_mb_team=$l_team;
                $s_tg_team=$r_team;
                $w_mb_team=$w_l_team;
                $w_mb_team_tw=$w_l_team_tw;
                $w_mb_team_en=$w_l_team_en;
                $w_tg_team=$w_r_team;
                $w_tg_team_tw=$w_r_team_tw;
                $w_tg_team_en=$w_r_team_en;

                if ($odd_f_type=='H'){
                    $gwin=($w_m_rate)*$gold;
                }else if ($odd_f_type=='M' or $odd_f_type=='I'){
                    if ($w_m_rate<0){
                        $gwin=$gold;
                    }else{
                        $gwin=($w_m_rate)*$gold;
                    }
                }else if ($odd_f_type=='E'){
                    $gwin=($w_m_rate-1)*$gold;
                }
                $ptype='R';
                break;
            case 3: // 全场大小
                $bet_type='大小';
                $bet_type_tw="大小";
                $bet_type_en="Over/Under";
                $caption=$Order_Basketball.$Order_Over_Under_betting_order;
                $rate=get_other_ioratio($odd_f_type,$row["MB_Dime_Rate"],$row["TG_Dime_Rate"],100);
                switch ($wtype){
                    case "OUH":  // 全场大小 主队
                        $w_m_place=$row["MB_Dime"];
                        $w_m_place=str_replace('O','大&nbsp;',$w_m_place);
                        $w_m_place_tw=$row["MB_Dime"];
                        $w_m_place_tw=str_replace('O','大&nbsp;',$w_m_place_tw);
                        $w_m_place_en=$row["MB_Dime"];
                        $w_m_place_en=str_replace('O','over&nbsp;',$w_m_place_en);
                        $m_place=$row["MB_Dime"];
                        $s_m_place=$row["MB_Dime"];
                        if ($langx=="zh-cn"){
                            $s_m_place=str_replace('O','大&nbsp;',$s_m_place);
                        }else if ($langx=="zh-cn"){
                            $s_m_place=str_replace('O','大&nbsp;',$s_m_place);
                        }else if ($langx=="en-us" or $langx=="th-tis"){
                            $s_m_place=str_replace('O','over&nbsp;',$s_m_place);
                        }
                        $w_m_rate=change_rate($open,$rate[0]);
                        $mtype='OUH';
                        break;
                    case "OUC":   // 全场大小 客队
                        $w_m_place=$row["TG_Dime"];
                        $w_m_place=str_replace('U','小&nbsp;',$w_m_place);
                        $w_m_place_tw=$row["TG_Dime"];
                        $w_m_place_tw=str_replace('U','小&nbsp;',$w_m_place_tw);
                        $w_m_place_en=$row["TG_Dime"];
                        $w_m_place_en=str_replace('U','under&nbsp;',$w_m_place_en);
                        $m_place=$row["TG_Dime"];
                        $s_m_place=$row["TG_Dime"];
                        if ($langx=="zh-cn"){
                            $s_m_place=str_replace('U','小&nbsp;',$s_m_place);
                        }else if ($langx=="zh-cn"){
                            $s_m_place=str_replace('U','小&nbsp;',$s_m_place);
                        }else if ($langx=="en-us" or $langx=="th-tis"){
                            $s_m_place=str_replace('U','under&nbsp;',$s_m_place);
                        }

                        $w_m_rate=change_rate($open,$rate[1]);
                        $mtype='OUC';
                        break;
                }
                $Sign="VS.";
                $grape=$m_place;
                if ($odd_f_type=='H'){
                    $gwin=($w_m_rate)*$gold;
                }else if ($odd_f_type=='M' or $odd_f_type=='I'){
                    if ($w_m_rate<0){
                        $gwin=$gold;
                    }else{
                        $gwin=($w_m_rate)*$gold;
                    }
                }else if ($odd_f_type=='E'){
                    $gwin=($w_m_rate-1)*$gold;
                }
                $ptype='OU';
                break;
            case 5: // 单双
                $bet_type='单双';
                $bet_type_tw="單雙";
                $bet_type_en="Odd/Even";
                $caption=$Order_Basketball.$Order_Odd_Even_betting_order;
                switch ($rtype){
                    case "ODD": // 单
                        $w_m_place='单';
                        $w_m_place_tw='單';
                        $w_m_place_en='odd';
                        $s_m_place='('.$Order_Odd.')';
                        $w_m_rate=change_rate($open,$row["S_Single_Rate"]);
                        break;
                    case "EVEN": // 双
                        $w_m_place='双';
                        $w_m_place_tw='雙';
                        $w_m_place_en='even';
                        $s_m_place='('.$Order_Even.')';
                        $w_m_rate=change_rate($open,$row["S_Double_Rate"]);
                        break;
                }
                $Sign="VS.";
                $order='B';
                $gwin=($w_m_rate-1)*$gold;
                $ptype='EO';
                $mtype=$rtype;
                break;
            case 13: //球队得分大小
                $caption=$Order_BK.$Order_1st_Half_Over_Under_betting_order;
                $order_btype = "-&nbsp;<font color=#666666>[$Order_1st_Half]</font>&nbsp;" ;
                switch ($type){
                    case "C": // 主队
                        $rate=get_other_ioratio($odd_f_type,$row["MB_Dime_Rate_H"],$row["MB_Dime_Rate_S_H"],100);
                        $bet_type='球队得分大小：主队 ';
                        $bet_type_tw="球队得分大小：主队 ";
                        $bet_type_en="Order_Ball_Score Over/Under";
                        switch ($wtype){
                            case 'OUH': // 主队 大 OUH
                                $w_m_place=$row["MB_Dime_H"];
                                $w_m_place_tw=$row["MB_Dime_H"];
                                $w_m_place_en=$row["MB_Dime_H"];
                                $m_place=$row["MB_Dime_H"];
                                $s_m_place=$row["MB_Dime_H"];
                                $w_m_bet_name = $row['MB_Team'];
                                $w_m_rate=change_rate($open,$rate[0]);

                                $w_m_place=str_replace('O','大&nbsp;',$w_m_place);
                                $w_m_place_tw=str_replace('O','大&nbsp;',$w_m_place_tw);
                                $w_m_place_en=str_replace('O','over&nbsp;',$w_m_place_en);


                                if ($langx=="zh-cn"){
                                    $s_m_place=str_replace('O','大&nbsp;',$s_m_place);
                                }else if ($langx=="en-us" or $langx=="th-tis"){
                                    $s_m_place=str_replace('O','over&nbsp;',$s_m_place);
                                }
                                break;
                            case 'OUC': // 主队小 OUC
                                $w_m_place=$row["MB_Dime_H"];
                                $w_m_place_tw=$row["MB_Dime_S_H"];
                                $w_m_place_en=$row["MB_Dime_S_H"];
                                $m_place=$row["MB_Dime_S_H"];
                                $s_m_place=$row["MB_Dime_S_H"];
                                $w_m_bet_name = $row['MB_Team'];
                                $w_m_rate=change_rate($open,$rate[1]);

                                $w_m_place=str_replace('O','小&nbsp;',$w_m_place);
                                $w_m_place_tw=str_replace('O','小&nbsp;',$w_m_place_tw);
                                $w_m_place_en=str_replace('O','under&nbsp;',$w_m_place_en);

                                if ($langx=="zh-cn"){
                                    $s_m_place=str_replace('O','小&nbsp;',$s_m_place);
                                }else if ($langx=="en-us" or $langx=="th-tis"){
                                    $s_m_place=str_replace('O','under&nbsp;',$s_m_place);
                                }
                                break;
                        }

                        $mtype='OUH';
                        break;
                    case "H": // 客队
                        $bet_type='球队得分大小：客队 ';
                        $bet_type_tw="球队得分大小：客队 ";
                        $bet_type_en="Order_Ball_Score Over/Under";
                        $rate=get_other_ioratio($odd_f_type,$row["TG_Dime_Rate_H"],$row["TG_Dime_Rate_S_H"],100);
                        switch ($wtype){
                            case 'OUH':
                                $w_m_place=$row["TG_Dime_H"];
                                $m_place=$row["TG_Dime_H"];
                                $w_m_place_tw=$row["TG_Dime_H"];
                                $s_m_place=$row["TG_Dime_H"];
                                $w_m_place_en=$row["TG_Dime_H"];
                                $w_m_bet_name = $row['TG_Team'];
                                $w_m_rate=change_rate($open,$rate[0]);
                                $w_m_place=str_replace('O','大&nbsp;',$w_m_place);
                                $w_m_place_tw=str_replace('O','大&nbsp;',$w_m_place_tw);
                                $w_m_place_en=str_replace('O','over&nbsp;',$w_m_place_en);
                                if ($langx=="zh-cn"){
                                    $s_m_place=str_replace('O','大&nbsp;',$s_m_place);
                                }else if ($langx=="en-us" or $langx=="th-tis"){
                                    $s_m_place=str_replace('O','over&nbsp;',$s_m_place);
                                }
                                break;
                            case 'OUC':
                                $w_m_place=$row["TG_Dime_S_H"];
                                $m_place=$row["TG_Dime_S_H"];
                                $w_m_place_tw=$row["TG_Dime_S_H"];
                                $s_m_place=$row["TG_Dime_S_H"];
                                $w_m_place_en=$row["TG_Dime_S_H"];
                                $w_m_bet_name = $row['TG_Team'];
                                $w_m_rate=change_rate($open,$rate[1]);

                                $w_m_place=str_replace('U','小&nbsp;',$w_m_place);
                                $w_m_place_tw=str_replace('U','小&nbsp;',$w_m_place_tw);
                                $w_m_place_en=str_replace('U','under&nbsp;',$w_m_place_en);

                                if ($langx=="zh-cn"){
                                    $s_m_place=str_replace('U','小&nbsp;',$s_m_place);
                                }else if ($langx=="en-us" or $langx=="th-tis"){
                                    $s_m_place=str_replace('U','under&nbsp;',$s_m_place);
                                }
                                break;
                        }

                        $mtype='OUC';
                        break;
                }
                $Sign="VS.";
                $grape=$m_place;
                if ($odd_f_type=='H'){
                    $gwin=($w_m_rate)*$gold;
                }else if ($odd_f_type=='M' or $odd_f_type=='I'){
                    if ($w_m_rate<0){
                        $gwin=$gold;
                    }else{
                        $gwin=($w_m_rate)*$gold;
                    }
                }else if ($odd_f_type=='E'){
                    $gwin=($w_m_rate-1)*$gold;
                }
                $ptype='VOU';
                break;
        }
    }

    // 篮球滚球
    if ($cate=='BK_RB'){

        switch ($line){

            case 9:
                $bet_type='滚球让球';
                $bet_type_tw="滾球讓球";
                $bet_type_en="Running Ball";
                $caption=$Order_Basketball.$Order_Running_Ball_betting_order;
                $rate=get_other_ioratio($odd_f_type,$row["MB_LetB_Rate_RB"],$row["TG_LetB_Rate_RB"],100);
                if ($rate[0]-$r_num>1.5 or $rate[1]-$r_num>1.5){
                    echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
                    exit();
                }
                switch ($type){
                    case "H":
                        $w_m_place=$w_mb_team;
                        $w_m_place_tw=$w_mb_team_tw;
                        $w_m_place_en=$w_mb_team_en;
                        $s_m_place=$s_mb_team;
                        $w_m_rate=change_rate($open,$rate[0]);
                        $turn_url="/app/member/BK_order/BK_order_re.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&strong=".$strong."&odd_f_type=".$odd_f_type;
                        $mtype='RRH';
                        break;
                    case "C":
                        $w_m_place=$w_tg_team;
                        $w_m_place_tw=$w_tg_team_tw;
                        $w_m_place_en=$w_tg_team_en;
                        $s_m_place=$s_tg_team;
                        $w_m_rate=change_rate($open,$rate[1]);
                        $turn_url="/app/member/BK_order/BK_order_re.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&strong=".$strong."&odd_f_type=".$odd_f_type;
                        $mtype='RRC';
                        break;
                }
                $Sign=$row['M_LetB_RB'];
                $grape=$Sign;
                if (strtoupper($showtype)=="H"){
                    $l_team=$s_mb_team;
                    $r_team=$s_tg_team;
                    $w_l_team=$w_mb_team;
                    $w_l_team_tw=$w_mb_team_tw;
                    $w_l_team_en=$w_mb_team_en;
                    $w_r_team=$w_tg_team;
                    $w_r_team_tw=$w_tg_team_tw;
                    $w_r_team_en=$w_tg_team_en;
                    $inball=$row['MB_Ball'].":".$row['TG_Ball'];
                }else{
                    $r_team=$s_mb_team;
                    $l_team=$s_tg_team;
                    $w_r_team=$w_mb_team;
                    $w_r_team_tw=$w_mb_team_tw;
                    $w_r_team_en=$w_mb_team_en;
                    $w_l_team=$w_tg_team;
                    $w_l_team_tw=$w_tg_team_tw;
                    $w_l_team_en=$w_tg_team_en;
                    $inball=$row['TG_Ball'].":".$row['MB_Ball'];

                }
                $s_mb_team=$l_team;
                $s_tg_team=$r_team;
                $w_mb_team=$w_l_team;
                $w_mb_team_tw=$w_l_team_tw;
                $w_mb_team_en=$w_l_team_en;
                $w_tg_team=$w_r_team;
                $w_tg_team_tw=$w_r_team_tw;
                $w_tg_team_en=$w_r_team_en;
                if ($odd_f_type=='H'){
                    $gwin=($w_m_rate)*$gold;
                }else if ($odd_f_type=='M' or $odd_f_type=='I'){
                    if ($w_m_rate<0){
                        $gwin=$gold;
                    }else{
                        $gwin=($w_m_rate)*$gold;
                    }
                }else if ($odd_f_type=='E'){
                    $gwin=($w_m_rate-1)*$gold;
                }
                $ptype='RE';
                $w_wtype='R';
                break;
            case 10:
                $bet_type='滚球大小';
                $bet_type_tw="滾球大小";
                $bet_type_en="Running Over/Under";
                $caption=$Order_Basketball.$Order_Running_Ball_Over_Under_betting_order;
                $rate=get_other_ioratio($odd_f_type,$row["MB_Dime_Rate_RB"],$row["TG_Dime_Rate_RB"],100);
                if ($rate[0]-$r_num>1.5 or $rate[1]-$r_num>1.5){
                    echo attention("$Order_This_match_is_closed_Please_try_again",$uid,$langx);
                    exit();
                }
                switch ($type){
                    case "C":
                        $ioradio_r_h=$rate[0];
                        $w_m_place=$row["MB_Dime_RB"];
                        $w_m_place=str_replace('O','大&nbsp;',$w_m_place);
                        $w_m_place_tw=$row["MB_Dime_RB"];
                        $w_m_place_tw=str_replace('O','大&nbsp;',$w_m_place_tw);
                        $w_m_place_en=$row["MB_Dime_RB"];
                        $w_m_place_en=str_replace('O','over&nbsp;',$w_m_place_en);

                        $m_place=$row["MB_Dime_RB"];

                        $s_m_place=$row["MB_Dime_RB"];
                        if ($langx=="zh-cn"){
                            $s_m_place=str_replace('O','大&nbsp;',$s_m_place);
                        }else if ($langx=="zh-cn"){
                            $s_m_place=str_replace('O','大&nbsp;',$s_m_place);
                        }else if ($langx=="en-us" or $langx=='th-tis'){
                            $s_m_place=str_replace('O','over&nbsp;',$s_m_place);
                        }
                        $w_m_rate=change_rate($open,$ioradio_r_h);
                        $turn_url="/app/member/BK_order/BK_order_rou.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&odd_f_type=".$odd_f_type;
                        $mtype='ROUH';
                        break;
                    case "H":
                        $ioradio_r_h=$rate[1];
                        $w_m_place=$row["TG_Dime_RB"];
                        $w_m_place=str_replace('U','小&nbsp;',$w_m_place);
                        $w_m_place_tw=$row["TG_Dime_RB"];
                        $w_m_place_tw=str_replace('U','小&nbsp;',$w_m_place_tw);
                        $w_m_place_en=$row["TG_Dime_RB"];
                        $w_m_place_en=str_replace('U','under&nbsp;',$w_m_place_en);

                        $m_place=$row["TG_Dime_RB"];

                        $s_m_place=$row["TG_Dime_RB"];
                        if ($langx=="zh-cn"){
                            $s_m_place=str_replace('U','小&nbsp;',$s_m_place);
                        }else if ($langx=="zh-cn"){
                            $s_m_place=str_replace('U','小&nbsp;',$s_m_place);
                        }else if ($langx=="en-us" or $langx=='th-tis'){
                            $s_m_place=str_replace('U','under&nbsp;',$s_m_place);
                        }
                        $w_m_rate=change_rate($open,$ioradio_r_h);
                        $turn_url="/app/member/BK_order/BK_order_rou.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&odd_f_type=".$odd_f_type;
                        $mtype='ROUC';
                        break;
                }
                $Sign="VS.";
                $grape=$m_place;
                if ($odd_f_type=='H'){
                    $gwin=($w_m_rate)*$gold;
                }else if ($odd_f_type=='M' or $odd_f_type=='I'){
                    if ($w_m_rate<0){
                        $gwin=$gold;
                    }else{
                        $gwin=($w_m_rate)*$gold;
                    }
                }else if ($odd_f_type=='E'){
                    $gwin=($w_m_rate-1)*$gold;
                }
                $ptype='ROU';
                $w_wtype='R';
                break;

            case 21:
                $bet_type='滚球独赢';
                $bet_type_tw='滾球獨贏';
                $bet_type_en="Running 1x2";
                $caption=$BK_NFL.$Order_Running_1_x_2_betting_order;
                switch ($type){
                    case "H":
                        $w_m_place=$w_mb_team;
                        $w_m_place_tw=$w_mb_team_tw;
                        $w_m_place_en=$w_mb_team_en;
                        $s_m_place=$s_mb_team;
                        $w_m_rate=change_rate($open,$row["MB_Win_Rate"]);
                        $turn_url="/app/member/BK_order/BK_order_rm.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&odd_f_type=".$odd_f_type;
                        $mtype='RMH';
                        break;
                    case "C":
                        $w_m_place=$w_tg_team;
                        $w_m_place_tw=$w_tg_team_tw;
                        $w_m_place_en=$w_tg_team_en;
                        $s_m_place=$s_tg_team;
                        $w_m_rate=change_rate($open,$row["TG_Win_Rate"]);
                        $turn_url="/app/member/BK_order/BK_order_rm.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&odd_f_type=".$odd_f_type;
                        $mtype='RMC';
                        break;
                    case "N":
                        $w_m_place="和局";
                        $w_m_place_tw="和局";
                        $w_m_place_en="Flat";
                        $s_m_place=$Draw;
                        $w_m_rate=change_rate($open,$row["M_Flat_Rate"]);
                        $turn_url="/app/member/BK_order/BK_order_rm.php?gid=".$gid."&uid=".$uid."&type=".$type."&gnum=".$gnum."&odd_f_type=".$odd_f_type;
                        $mtype='RMN';
                        break;
                }

                $Sign="VS.";
                $grape=$type;
                $gwin=($w_m_rate-1)*$gold;
                $ptype='RM';
                break;
            case 20: // 滚球半场球队得分大小
                $bet_type='滚球 全场-球队得分大小';
                $bet_type_tw="滚球 全场-球队得分大小";
                $bet_type_en="running 1st Over/Under";
                $caption=$Running_Ball.$BK_NFL.$Order_1st_Half_Over_Under_betting_order;
                $order_btype = "-&nbsp;<font color=#666666>[$Order_1st_Half]</font>&nbsp;" ;
                switch ($type){
                    case "C": // 主队大/小
                        if($wtype =='ROUH'){ // 大
                            $w_m_place=$row["MB_Dime_RB_H"];
                            $w_m_place_tw=$row["MB_Dime_RB_H"];
                            $w_m_place_en=$row["MB_Dime_RB_H"];
                            $m_place=$row["MB_Dime_RB_H"];
                            $s_m_place=$row["MB_Dime_RB_H"];
                            $w_m_rate=change_rate($open,$row["MB_Dime_Rate_RB_H"]); // 主队半场大的赔率
                            $w_m_bet_name = $row['MB_Team']; // 主队
                        }else{
                            $w_m_place=$row["MB_Dime_RB_S_H"];
                            $m_place=$row["MB_Dime_RB_S_H"];
                            $w_m_place_tw=$row["MB_Dime_RB_S_H"];
                            $s_m_place=$row["MB_Dime_RB_S_H"];
                            $w_m_place_en=$row["MB_Dime_RB_S_H"];
                            $w_m_rate=change_rate($open,$row["MB_Dime_Rate_RB_S_H"]); // 主队半场小的赔率
                            $w_m_bet_name = $row['MB_Team'];
                        }
                        // $w_m_rate=change_rate($open,$rate[0]); // 赔率
                        $turn_url="/app/member/BK_order/BK_order_rouhc.php?gid=$gid&uid=$suid&wtype=$wtype&type=$type&odd_f_type=$odd_f_type&langx=$langx";
                        $mtype='ROUH';
                        break;
                    case "H": // 客队大/小
                        if($wtype =='ROUH'){ // 大
                            $w_m_place=$row["TG_Dime_RB_H"];
                            $w_m_place_tw=$row["TG_Dime_RB_H"];
                            $w_m_place_en=$row["TG_Dime_RB_H"];
                            $m_place=$row["TG_Dime_RB_H"];
                            $s_m_place=$row["TG_Dime_RB_H"];
                            $w_m_rate=change_rate($open,$row["TG_Dime_Rate_RB_H"]); // 客队半场大的赔率
                            $w_m_bet_name = $row['TG_Team']; // 客队
                        }else{ // 小
                            $w_m_place=$row["TG_Dime_RB_S_H"];
                            $m_place=$row["TG_Dime_RB_S_H"];
                            $w_m_place_tw=$row["TG_Dime_RB_S_H"];
                            $s_m_place=$row["TG_Dime_RB_S_H"];
                            $w_m_place_en=$row["TG_Dime_RB_S_H"];
                            $w_m_rate=change_rate($open,$row["TG_Dime_Rate_RB_S_H"]); //客队半场小的赔率
                            $w_m_bet_name = $row['TG_Team'];
                        }
                        // $w_m_rate=change_rate($open,$rate[1]); // 赔率
                        $mtype='ROUC';
                        break;
                }

                if($wtype =='ROUH'){
                    $w_m_place=str_replace('O','大&nbsp;',$w_m_place);
                    $w_m_place_tw=str_replace('O','大&nbsp;',$w_m_place_tw);
                    $w_m_place_en=str_replace('O','over&nbsp;',$w_m_place_en);


                    if ($langx=="zh-cn"){
                        $s_m_place=str_replace('O','大&nbsp;',$s_m_place);
                    }else if ($langx=="en-us" or $langx=="th-tis"){
                        $s_m_place=str_replace('O','over&nbsp;',$s_m_place);
                    }
                }elseif($wtype =='ROUC'){
                    $w_m_place=str_replace('U','小&nbsp;',$w_m_place);
                    $w_m_place_tw=str_replace('U','小&nbsp;',$w_m_place_tw);
                    $w_m_place_en=str_replace('U','under&nbsp;',$w_m_place_en);

                    if ($langx=="zh-cn"){
                        $s_m_place=str_replace('U','小&nbsp;',$s_m_place);
                    }else if ($langx=="en-us" or $langx=="th-tis"){
                        $s_m_place=str_replace('U','under&nbsp;',$s_m_place);
                    }
                }

                $Sign="VS.";
                $grape=$m_place;
                if ($odd_f_type=='H'){
                    $gwin=($w_m_rate)*$gold;
                }else if ($odd_f_type=='M' or $odd_f_type=='I'){
                    if ($w_m_rate<0){
                        $gwin=$gold;
                    }else{
                        $gwin=($w_m_rate)*$gold;
                    }
                }else if ($odd_f_type=='E'){
                    $gwin=($w_m_rate-1)*$gold;
                }
                $ptype='VOU';
                $line=23;
                break;
        }
    }

    if ($gold<10){
        exit(json_encode(["err"=>"-12","msg"=>"金额最低不能小于10元~~"]));
    }

    if($cate != 'BK'&& ($line == 21 or $line == 13)){
        if ($w_m_rate=='' or $grape==''){
            exit(json_encode(["err"=>"-13","msg"=>"赔率有误~~"]));
        }
    }

//    if ($cate != 'BK'&& ($line==11 or $line==12 or $line==13)){
//        $bottom1_cn="-&nbsp;<font color=#666666>[上半]</font>&nbsp;";
//        $bottom1_tw="-&nbsp;<font color=#666666>[上半]</font>&nbsp;";
//        $bottom1_en="-&nbsp;<font color=#666666>[1st Half]</font>&nbsp;";
//    }

    $oddstype='';
    switch ($cate){
        case 'FT': // 全场让球、全场大小、半场让球、半场大小
//            if ($line==2 or $line==3 or $line==12 or $line==13) {

            if ($w_m_rate != $ioradio_r_h) {
                exit(json_encode(["err" => "-11", "msg" => "赔率不一致，请更新赔率后下注~~"]));
            }
            $oddstype=$odd_f_type;
//            }
            break;
        case 'FT_RB': // 滚球让球、滚球大小、半场滚球让球、半场滚球大小
            // 滚球赔率更新太快，赔率比较暂时关闭
            if ($w_m_rate != $ioradio_r_h) {
                exit(json_encode(["err" => "-11", "msg" => "赔率不一致，请更新赔率后下注~~"]));
            }

            if ($line==9 or $line==10 or $line==19 or $line==20) {
                $oddstype=$odd_f_type;
            }
            break;
        case 'BK': // 全场让球、全场大小、半场大小
//            if ($line==2 or $line==3 or $line==13) {
            if ($w_m_rate != $ioradio_r_h) {
                exit(json_encode(["err" => "-11", "msg" => "赔率不一致，请更新赔率后下注~~"]));
            }
            $oddstype=$odd_f_type;
//            }
            break;
        case 'BK_RB': // 滚球让球、滚球大小、球队得分大小
            // 滚球赔率更新太快，赔率比较暂时关闭
            if ($w_m_rate != $_REQUEST['ioradio_r_h']) {
                exit(json_encode(["err" => "-11", "msg" => "赔率不一致，请更新赔率后下注~~"]));
            }
            if ($line==9 or $line==10 or $line==13) {
                $oddstype=$odd_f_type;
            }
            break;

    }

    $s_m_place=filiter_team(trim($s_m_place));

    if ($s_m_place=='' or $w_m_place=='' or $w_m_place_tw==''){
        exit(json_encode(["err"=>"-14","msg"=>"本场比赛队伍名称有误~~"]));
    }

    $w_mid="<br>[".$row['MB_MID']."]vs[".$row['TG_MID']."]<br>";
    $lines=$row['M_League'].$w_mid.$w_mb_team."&nbsp;&nbsp;<FONT COLOR=#0000BB><b>".$Sign."</b></FONT>&nbsp;&nbsp;".$w_tg_team."&nbsp;&nbsp;<FONT color=red><b>$inball</b></FONT><br>";
    $lines=$lines."<FONT color=#cc0000>".$w_m_bet_name."&nbsp;&nbsp;".$w_m_place."</FONT>&nbsp;".$order_btype."@&nbsp;<FONT color=#cc0000><b>".$w_m_rate."</b></FONT>";
    $lines_tw=$row['M_League_tw'].$w_mid.$w_mb_team_tw."&nbsp;&nbsp;<FONT COLOR=#0000BB><b>".$Sign."</b></FONT>&nbsp;&nbsp;".$w_tg_team_tw."&nbsp;&nbsp;<FONT color=red><b>$inball</b></FONT><br>";
    $lines_tw=$lines_tw."<FONT color=#cc0000>".$w_m_bet_name."&nbsp;&nbsp;".$w_m_place_tw."</FONT>&nbsp;".$order_btype."@&nbsp;<FONT color=#cc0000><b>".$w_m_rate."</b></FONT>";
    $lines_en=$row['M_League_en'].$w_mid.$w_mb_team_en."&nbsp;&nbsp;<FONT COLOR=#0000BB><b>".$Sign."</b></FONT>&nbsp;&nbsp;".$w_tg_team_en."&nbsp;&nbsp;<FONT color=red><b>$inball</b></FONT><br>";
    $lines_en=$lines_en."<FONT color=#cc0000>".$w_m_bet_name."&nbsp;&nbsp;".$w_m_place_en."</FONT>&nbsp;".$order_btype."@&nbsp;<FONT color=#cc0000><b>".$w_m_rate."</b></FONT>";

    $ip_addr = get_ip();

    $psql = "select A_Point,B_Point,C_Point,D_Point from ".DBPREFIX."web_agents_data where UserName='$agents'";
    $result = mysqli_query($dbLink,$psql);
    $prow = mysqli_fetch_assoc($result);
    $a_point=$prow['A_Point']+0;
    $b_point=$prow['B_Point']+0;
    $c_point=$prow['C_Point']+0;
    $d_point=$prow['D_Point']+0;

    if($cate == 'FT' || $cate == 'FT_RB') $gtype = 'FT';
    if($cate == 'BK' || $cate == 'BK_RB') $gtype = 'BK';

//判断终端类型
    if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')||strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')){
        $playSource=3;
    }else if(strpos($_SERVER['HTTP_USER_AGENT'], 'Android')){
        $playSource=4;
    }else{
        $playSource=5;
    }
    $showVoucher= show_voucher($wtype);

    $begin = mysqli_query($dbMasterLink,"start transaction");
    $lockResult = mysqli_query($dbMasterLink,"select Money from ".DBPREFIX.MEMBERTABLE." where ID = ".$memid." for update");
    if($begin && $lockResult){
        $checkRow = mysqli_fetch_assoc($lockResult);
        $HMoney=$Money=$checkRow['Money'];
        $havemoney=$HMoney-$gold;
        if($havemoney < 0 || $gold<0 || $HMoney<0){
            mysqli_query($dbMasterLink,"ROLLBACK");
            exit(json_encode(["err"=>-2,"msg"=>"下注金額不可大於信用額度。".rand(1,199)]));
            exit();
        }
        // 足球滚球投注，需要加危险球确认   Danger 为1
        if($cate=='FT_RB'){
            $sql = "INSERT INTO ".DBPREFIX."web_report_data	(MID,Glost,playSource,userid,testflag,Active,orderNo,LineType,Mtype,M_Date,BetTime,BetScore,Middle,Middle_tw,Middle_en,BetType,BetType_tw,BetType_en,M_Place,M_Rate,M_Name,Gwin,OpenType,OddsType,ShowType,Agents,World,Corprator,Super,Admin,A_Point,B_Point,C_Point,D_Point,BetIP,Ptype,Gtype,CurType,Ratio,MB_MID,TG_MID,Pay_Type,MB_Ball,TG_Ball,Danger) values ('$gid',$Money,'$playSource','$memid',$test_flag,'$active','$showVoucher','$line','$mtype','$m_date','$bettime','$gold','$lines','$lines_tw','$lines_en','$bet_type','$bet_type_tw','$bet_type_en','$grape','$w_m_rate','$memname','$gwin','$open','$oddstype','$showtype','$agents','$world','$corprator','$super','$admin','$a_point','$b_point','$c_point','$d_point','$ip_addr','$ptype','$gtype','$w_current','$w_ratio','$w_mb_mid','$w_tg_mid','$pay_type','$mb_ball','$tg_ball',1)";
        }else{
            $sql = "INSERT INTO ".DBPREFIX."web_report_data	(MID,Glost,playSource,userid,testflag,Active,orderNo,LineType,Mtype,M_Date,BetTime,BetScore,Middle,Middle_tw,Middle_en,BetType,BetType_tw,BetType_en,M_Place,M_Rate,M_Name,Gwin,OpenType,OddsType,ShowType,Agents,World,Corprator,Super,Admin,A_Point,B_Point,C_Point,D_Point,BetIP,Ptype,Gtype,CurType,Ratio,MB_MID,TG_MID,Pay_Type,MB_Ball,TG_Ball,betid) values ('$gid',$Money,'$playSource','$memid',$test_flag,'$active','$showVoucher','$line','$mtype','$m_date','$bettime','$gold','$lines','$lines_tw','$lines_en','$bet_type','$bet_type_tw','$bet_type_en','$grape','$w_m_rate','$memname','$gwin','$open','$oddstype','$showtype','$agents','$world','$corprator','$super','$admin','$a_point','$b_point','$c_point','$d_point','$ip_addr','$ptype','$gtype','$w_current','$w_ratio','$w_mb_mid','$w_tg_mid','$pay_type','$mb_ball','$tg_ball','$betid')";
        }
        $insertBet=mysqli_query($dbMasterLink,$sql);
        if($insertBet){
            $lastId=mysqli_insert_id($dbMasterLink);
            $moneyLogRes=addAccountRecords(array($memid,$memname,$test_flag,$Money,$gold*-1,$havemoney,1,$playSource,$lastId,$gtype."投注".$mtype));
            if($moneyLogRes){
                $sql1 = "update ".DBPREFIX.MEMBERTABLE." set Money=".$havemoney." , Online=1 , OnlineTime=now() where ID=".$memid;
                $updateMoney=mysqli_query($dbMasterLink,$sql1);
                if($updateMoney){
                    mysqli_query($dbMasterLink,"COMMIT");
                }else{
                    mysqli_query($dbMasterLink,"ROLLBACK");
                    exit(json_encode(["err"=>-6,"msg"=>"操作失败!!".rand(1,199)]));
                }
            }else{
                mysqli_query($dbMasterLink,"ROLLBACK");
                exit(json_encode(["err"=>-5,"msg"=>"操作失败2!".rand(1,199)]));
            }
        }else{
            exit(json_encode(["err"=>-5,"msg"=>"操作失败!".rand(1,199)]));
            die("操作失败1");
        }
    }else{
        mysqli_query($dbMasterLink,"ROLLBACK");
        exit(json_encode(["err"=>-5,"msg"=>"操作失败0!".rand(1,199)]));
    }

    if ($active==11){
        $caption=str_replace($Order_FT,$Order_FT.$Order_Early_Market,$caption);
    }

    $aData['caption'] = $caption;
    $aData['Order_Bet_success'] = $Order_Bet_success;
    $aData['order'] = $showVoucher;
    $aData['s_sleague'] = $s_sleague;
    isset($btype) ? $aData['btype'] = $btype:'';
    $aData['M_Date'] = date('m-d',strtotime($row["M_Date"]));
    $aData['s_mb_team'] = $s_mb_team;
    $aData['Sign'] = $Sign;
    $aData['s_tg_team'] = $s_tg_team;
    $aData['s_m_place'] = $s_m_place;
    $aData['w_m_rate'] = $w_m_rate;
    $aData['gold'] = $gold; // 交易金额
    $aData['order_bet_amount'] = $gwin; // 可赢金额
    $aData['havemoney'] = $havemoney; // 账户余额
    $aData['w_current'] = $w_current; // 货币种类
    exit( json_encode( $aData ) );

}
