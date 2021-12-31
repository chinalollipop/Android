<?php
/*
体育电竞 晋级彩金记录-月俸禄记录

 **/
//error_reporting(1);
//ini_set('display_errors','On');
session_start();
define("ROOT_DIR",  dirname(dirname(dirname(dirname(__FILE__)))));
require_once ROOT_DIR.'/common/activity/config.php';
include_once("../include/address.mem.php");
include_once("../include/config.inc.php");
include_once("../include/activity.class.php");

//$user_id = $_SESSION['userid'];
//$username = $_SESSION['UserName'];
$username = $_REQUEST['username'];

/*if ($_REQUEST['action'] != 'getZhenrenLevelSalaryInfo'){
    if(!$user_id) {
        $status = '502.2';
        $describe = '请重新登录!';
        original_phone_request_response($status,$describe);
    }
}*/

$sql="select ID,UserName from ".DBPREFIX.MEMBERTABLE." where UserName='$username'";
$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);
$cou = mysqli_num_rows($result);
//if ($cou==0){
//    $status = '502.2';
//    $describe = '没有此账号，请先注册账号';
//    original_phone_request_response($status,$describe);
//}
$user_id=$row['ID'];
$username=$row['UserName'];

switch ($_REQUEST['action']){
    case 'getZhenrenLevelSalaryInfo':

        $sportDjLevelSalaryInfo = array_values($sportDjLevelSalaryInfo);

        $status = 200;
        $describe = '';
        original_phone_request_response($status,$describe,$sportDjLevelSalaryInfo);

        break;
    case 'getSalaryRecords':

        $data=[];
        $sport_dj_level =$next_level_need_valid_money= $user_total_bet=$total_jinji_salary=$total_month_salary=0;

        // 累计有效投注
        $sql = "select userid, username, total, total_hg, total_avia, total_manual, `count_date_start`,`count_date_end`, is_free from ".DBPREFIX."sport_dj_week_report 
                where userid=$user_id order by `created_at` asc";
        $result = mysqli_query($dbLink,$sql);
        while ($row = mysqli_fetch_assoc($result)){
            $data[] = $row;
        }

        // 下面的表格按照每周的时间去统计显示级别还有晋级礼金
        $week_data = [];
        $until_current_total = 0;
        foreach ($data as $k => $v){
            $key = $v['count_date_start'].'-'.$v['count_date_end'];
            $until_current_total += $v['total'];
            if (isset($week_data[$key])){
                $week_data[$key]['total'] += $v['total'];
                $week_data[$key]['until_current_total'] = $until_current_total;
            }else{
                $v['until_current_total'] = $until_current_total;
                $week_data[$key] = $v;
            }
        }
        $week_data = array_values($week_data);
        $jinji_week_data = getCurrentLevel($week_data); // 匹配每周达到的级别
        $jinji_week_data = array_values(array_sort($jinji_week_data,'count_date_start',$type='desc'));

        // 码量分类（赠送码量、还是会员码量）
        $jinji_data = [];
        foreach ($data as $k => $v){
            $key = $v['userid'];
            $jinji_data[$key]['userid'] = $v['userid'];
            $jinji_data[$key]['username'] = $v['username'];
            $jinji_data[$key]['count_date_start'] = $v['count_date_start'];
            $jinji_data[$key]['count_date_end'] = $v['count_date_end'];
            $jinji_data[$key]['total'] += $v['total'];
            if ($v['is_free']==1){
                $jinji_data[$key]['free_total_bet'] += $v['total'];
            }
            else{
                $jinji_data[$key]['user_total_bet'] += $v['total'];
            }
        }

        $jinji_data = getCurrentInfo($jinji_data); // 上表格匹配级别


        // 上表格累计晋级礼金-累计月俸禄
        $sql = "select userid,EventName,`level`,`total`,`gift_gold`,`count_date_start`,`count_date_end`,`status` from ".DBPREFIX."sport_dj_jinji_salary where userid=$user_id order by id desc";
        $result = mysqli_query($dbLink, $sql);
        $cou=mysqli_num_rows($result); // 总数
        $jinji_records=[];
        while ($row = mysqli_fetch_assoc($result)){
            if ($row['EventName']=='体育电竞晋级礼金'){
                $total_jinji_salary += $row['gift_gold'];
            }
            else{
//                $total_month_salary += $row['gift_gold'];
            }
            $jinji_records[] = $row;
        }
//        print_r($jinji_records); die;

        $each_salary=[];
        foreach ($jinji_week_data as $k => $v){
            $jinji_week_data[$k]['all_jinji_gold'] = $each_salary[$k]['gift_gold'] = 0;
            foreach ($jinji_records as $k2 => $v2){
                if ($v['count_date_end'] == $v2['count_date_end']){
                    $jinji_week_data[$k]['all_jinji_gold'] += $v2['gift_gold'];
                    $each_salary[$k]['gift_gold'] += $v2['gift_gold'];
                    $each_salary[$k]['status'] = $v2['status'];
                }
            }
            $each_salary[$k]['level'] = $v['level'];
            $each_salary[$k]['total'] = $v['total'];
            $each_salary[$k]['count_date_start'] = $v['count_date_start'];
            $each_salary[$k]['count_date_end'] = $v['count_date_end'];
            if (!$each_salary[$k]['status']){
                $each_salary[$k]['status'] = '3';
//                if ($v['count_date_end'] <= date('Y-m-d')){
//                    $each_salary[$k]['status'] = 1;
//                }else{
//                    $each_salary[$k]['status'] = 2;
//                }
            }
        }

        $page=$_REQUEST['page'];
        $page_size=5;
        $cou = count($each_salary);//总条数
        $page_count=ceil($cou/$page_size); // 总页数
        $start=($page)*$page_size;//偏移量，当前页-1乘以每页显示条数
        $each_salary = array_slice($each_salary,$start,$page_size);
        $cou_current_page = count($each_salary); // 当前页条数

        $sport_dj_level = isset($jinji_data[$user_id]['level'])?$jinji_data[$user_id]['level']:0;
        $next_level_need_valid_money = isset($jinji_data[$user_id]['next_level_need_valid_money'])?$jinji_data[$user_id]['next_level_need_valid_money']:0;
        $user_total_bet = isset($jinji_data[$user_id]['user_total_bet'])?$jinji_data[$user_id]['user_total_bet']:0;
        $free_total_bet = isset($jinji_data[$user_id]['free_total_bet'])?$jinji_data[$user_id]['free_total_bet']:0;
        $month_salary = isset($jinji_data[$user_id]['month_salary'])?$jinji_data[$user_id]['month_salary']:0;
        $data = [
            'total'=>$cou, // 总条目
            'num_per_page'=>$page_size, // 每页条数
            'currentpage'=>$page, // 当前页号
            'page_count'=>$page_count, // 总页数
            'perpage'=> $cou_current_page, // 当前页条数
            'current_level'=>[
                'username'=>$username,
                'level'=>$sport_dj_level,
                'next_level_need_valid_money'=>$next_level_need_valid_money,
                'user_total_bet' => $user_total_bet,
                'free_total_bet' => $free_total_bet,
                'total_jinji_salary' => $total_jinji_salary,
                'total_month_salary' => $month_salary,
            ],
            'each_salary'=>$each_salary
        ];

        $status = 200;
        $describe = '';
        original_phone_request_response($status,$describe,$data);
        break;
    default: break;
}


function getCurrentInfo($jinji_data){
    global $sportDjLevelSalaryInfo;

    // 拼凑 level
    // 20万以上的，才有等级
    foreach ($jinji_data as $k => $v){
        if ($v['total']<200000){
//            unset($jinji_data[$k]);
            $jinji_data[$k]['level'] = 0; // 当前等级
        }
        else{
            if ($v['total']>=200000 and $v['total']<500000){
                $jinji_data[$k]['level'] = 1; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[1]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 500000-$v['total'];
                $jinji_data[$k]['month_salary']= $sportDjLevelSalaryInfo[1]['month_salary'];
            }
            if ($v['total']>=500000 and $v['total']<1000000){
                $jinji_data[$k]['level'] = 2; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[2]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 1000000-$v['total'];
                $jinji_data[$k]['month_salary']= $sportDjLevelSalaryInfo[2]['month_salary'];
            }
            if ($v['total']>=1000000 and $v['total']<2000000){
                $jinji_data[$k]['level'] = 3; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[3]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 2000000-$v['total'];
                $jinji_data[$k]['month_salary']= $sportDjLevelSalaryInfo[3]['month_salary'];
            }
            if ($v['total']>=2000000 and $v['total']<3000000){
                $jinji_data[$k]['level'] = 4; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[4]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 3000000-$v['total'];
                $jinji_data[$k]['month_salary']= $sportDjLevelSalaryInfo[4]['month_salary'];
            }
            if ($v['total']>=3000000 and $v['total']<5000000){
                $jinji_data[$k]['level'] = 5; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[5]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 5000000-$v['total'];
                $jinji_data[$k]['month_salary']= $sportDjLevelSalaryInfo[5]['month_salary'];
            }
            if ($v['total']>=5000000 and $v['total']<7000000){
                $jinji_data[$k]['level'] = 6; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[6]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 7000000-$v['total'];
                $jinji_data[$k]['month_salary']= $sportDjLevelSalaryInfo[6]['month_salary'];
            }
            if ($v['total']>=7000000 and $v['total']<10000000){
                $jinji_data[$k]['level'] = 7; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[7]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 10000000-$v['total'];
                $jinji_data[$k]['month_salary']= $sportDjLevelSalaryInfo[7]['month_salary'];
            }
            if ($v['total']>=10000000 and $v['total']<13000000){
                $jinji_data[$k]['level'] = 8; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[8]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 13000000-$v['total'];
                $jinji_data[$k]['month_salary']= $sportDjLevelSalaryInfo[8]['month_salary'];
            }
            if ($v['total']>=13000000 and $v['total']<16000000){
                $jinji_data[$k]['level'] = 9; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[9]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 16000000-$v['total'];
                $jinji_data[$k]['month_salary']= $sportDjLevelSalaryInfo[9]['month_salary'];
            }
            if ($v['total']>=16000000 and $v['total']<20000000){
                $jinji_data[$k]['level'] = 10; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[10]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 20000000-$v['total'];
                $jinji_data[$k]['month_salary']= $sportDjLevelSalaryInfo[10]['month_salary'];
            }
            if ($v['total']>=20000000 and $v['total']<25000000){
                $jinji_data[$k]['level'] = 11; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[11]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 25000000-$v['total'];
                $jinji_data[$k]['month_salary']= $sportDjLevelSalaryInfo[11]['month_salary'];
            }
            if ($v['total']>=25000000 and $v['total']<30000000){
                $jinji_data[$k]['level'] = 12; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[12]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 30000000-$v['total'];
                $jinji_data[$k]['month_salary']= $sportDjLevelSalaryInfo[12]['month_salary'];
            }
            if ($v['total']>=30000000 and $v['total']<45000000){
                $jinji_data[$k]['level'] = 13; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[13]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 45000000-$v['total'];
                $jinji_data[$k]['month_salary']= $sportDjLevelSalaryInfo[13]['month_salary'];
            }
            if ($v['total']>=45000000 and $v['total']<60000000){
                $jinji_data[$k]['level'] = 14; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[14]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 60000000-$v['total'];
                $jinji_data[$k]['month_salary']= $sportDjLevelSalaryInfo[14]['month_salary'];
            }
            if ($v['total']>=60000000 and $v['total']<80000000){
                $jinji_data[$k]['level'] = 15; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[15]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 80000000-$v['total'];
                $jinji_data[$k]['month_salary']= $sportDjLevelSalaryInfo[15]['month_salary'];
            }
            if ($v['total']>=80000000 and $v['total']<110000000){
                $jinji_data[$k]['level'] = 16; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[16]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 110000000-$v['total'];
                $jinji_data[$k]['month_salary']= $sportDjLevelSalaryInfo[16]['month_salary'];
            }
            if ($v['total']>=110000000 and $v['total']<130000000){
                $jinji_data[$k]['level'] = 17; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[17]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 130000000-$v['total'];
                $jinji_data[$k]['month_salary']= $sportDjLevelSalaryInfo[17]['month_salary'];
            }
            if ($v['total']>=130000000 and $v['total']<150000000){
                $jinji_data[$k]['level'] = 18; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[18]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 150000000-$v['total'];
                $jinji_data[$k]['month_salary']= $sportDjLevelSalaryInfo[18]['month_salary'];
            }
            if ($v['total']>=150000000 and $v['total']<200000000){
                $jinji_data[$k]['level'] = 19; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[19]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 200000000-$v['total'];
                $jinji_data[$k]['month_salary']= $sportDjLevelSalaryInfo[19]['month_salary'];
            }
            if ($v['total']>=200000000 and $v['total']<250000000){
                $jinji_data[$k]['level'] = 20; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[20]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 250000000-$v['total'];
                $jinji_data[$k]['month_salary']= $sportDjLevelSalaryInfo[20]['month_salary'];
            }
            if ($v['total']>=250000000 and $v['total']<300000000){
                $jinji_data[$k]['level'] = 21; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[21]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 300000000-$v['total'];
                $jinji_data[$k]['month_salary']= $sportDjLevelSalaryInfo[21]['month_salary'];
            }
            if ($v['total']>=300000000 and $v['total']<350000000){
                $jinji_data[$k]['level'] = 22; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[22]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 350000000-$v['total'];
                $jinji_data[$k]['month_salary']= $sportDjLevelSalaryInfo[22]['month_salary'];
            }
            if ($v['total']>=350000000 and $v['total']<550000000){
                $jinji_data[$k]['level'] = 23; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[23]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 550000000-$v['total'];
                $jinji_data[$k]['month_salary']= $sportDjLevelSalaryInfo[23]['month_salary'];
            }
            if ($v['total']>=550000000 and $v['total']<850000000){
                $jinji_data[$k]['level'] = 24; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[24]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 850000000-$v['total'];
                $jinji_data[$k]['month_salary']= $sportDjLevelSalaryInfo[24]['month_salary'];
            }
            if ($v['total']>=850000000 and $v['total']<1200000000){
                $jinji_data[$k]['level'] = 25; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[25]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 1200000000-$v['total'];
                $jinji_data[$k]['month_salary']= $sportDjLevelSalaryInfo[25]['month_salary'];
            }
            if ($v['total']>=1200000000 and $v['total']<1700000000){
                $jinji_data[$k]['level'] = 26; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[26]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 1700000000-$v['total'];
                $jinji_data[$k]['month_salary']= $sportDjLevelSalaryInfo[26]['month_salary'];
            }
            if ($v['total']>=1700000000 and $v['total']<2500000000){
                $jinji_data[$k]['level'] = 27; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[27]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 2500000000-$v['total'];
                $jinji_data[$k]['month_salary']= $sportDjLevelSalaryInfo[27]['month_salary'];
            }
            if ($v['total']>=2500000000 and $v['total']<3800000000){
                $jinji_data[$k]['level'] = 28; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[28]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 3800000000-$v['total'];
                $jinji_data[$k]['month_salary']= $sportDjLevelSalaryInfo[28]['month_salary'];
            }
            if ($v['total']>=3800000000 and $v['total']<6000000000){
                $jinji_data[$k]['level'] = 29; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[29]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 6000000000-$v['total'];
                $jinji_data[$k]['month_salary']= $sportDjLevelSalaryInfo[29]['month_salary'];
            }
            if ($v['total']>=6000000000){
                $jinji_data[$k]['level'] = 30; // 当前等级
                $jinji_data[$k]['gift_gold'] = $sportDjLevelSalaryInfo[30]['jinji_salary'];
                $jinji_data[$k]['next_level_need_valid_money']= 0;
                $jinji_data[$k]['month_salary']= $sportDjLevelSalaryInfo[30]['month_salary'];
            }

        }
    }
    return $jinji_data;
}

// 匹配每周达到的级别
function getCurrentLevel($jinji_data){

    // 拼凑 level
    // 20万以上的，才有等级
    foreach ($jinji_data as $k => $v){
        if ($v['until_current_total']<200000){
//            unset($jinji_data[$k]);
            $jinji_data[$k]['level'] = 0; // 当前等级
        }
        else{
            if ($v['until_current_total']>=200000 and $v['until_current_total']<500000){
                $jinji_data[$k]['level'] = 1; // 当前等级
            }
            if ($v['until_current_total']>=500000 and $v['until_current_total']<1000000){
                $jinji_data[$k]['level'] = 2; // 当前等级
            }
            if ($v['until_current_total']>=1000000 and $v['until_current_total']<2000000){
                $jinji_data[$k]['level'] = 3; // 当前等级
            }
            if ($v['until_current_total']>=2000000 and $v['until_current_total']<3000000){
                $jinji_data[$k]['level'] = 4; // 当前等级
            }
            if ($v['until_current_total']>=3000000 and $v['until_current_total']<5000000){
                $jinji_data[$k]['level'] = 5; // 当前等级
            }
            if ($v['until_current_total']>=5000000 and $v['until_current_total']<7000000){
                $jinji_data[$k]['level'] = 6; // 当前等级
            }
            if ($v['until_current_total']>=7000000 and $v['until_current_total']<10000000){
                $jinji_data[$k]['level'] = 7; // 当前等级
            }
            if ($v['until_current_total']>=10000000 and $v['until_current_total']<13000000){
                $jinji_data[$k]['level'] = 8; // 当前等级
            }
            if ($v['until_current_total']>=13000000 and $v['until_current_total']<16000000){
                $jinji_data[$k]['level'] = 9; // 当前等级
            }
            if ($v['until_current_total']>=16000000 and $v['until_current_total']<20000000){
                $jinji_data[$k]['level'] = 10; // 当前等级
            }
            if ($v['until_current_total']>=20000000 and $v['until_current_total']<25000000){
                $jinji_data[$k]['level'] = 11; // 当前等级
            }
            if ($v['until_current_total']>=25000000 and $v['until_current_total']<30000000){
                $jinji_data[$k]['level'] = 12; // 当前等级
            }
            if ($v['until_current_total']>=30000000 and $v['until_current_total']<45000000){
                $jinji_data[$k]['level'] = 13; // 当前等级
            }
            if ($v['until_current_total']>=45000000 and $v['until_current_total']<60000000){
                $jinji_data[$k]['level'] = 14; // 当前等级
            }
            if ($v['until_current_total']>=60000000 and $v['until_current_total']<80000000){
                $jinji_data[$k]['level'] = 15; // 当前等级
            }
            if ($v['until_current_total']>=80000000 and $v['until_current_total']<110000000){
                $jinji_data[$k]['level'] = 16; // 当前等级
            }
            if ($v['until_current_total']>=110000000 and $v['until_current_total']<130000000){
                $jinji_data[$k]['level'] = 17; // 当前等级
            }
            if ($v['until_current_total']>=130000000 and $v['until_current_total']<150000000){
                $jinji_data[$k]['level'] = 18; // 当前等级
            }
            if ($v['until_current_total']>=150000000 and $v['until_current_total']<200000000){
                $jinji_data[$k]['level'] = 19; // 当前等级
            }
            if ($v['until_current_total']>=200000000 and $v['until_current_total']<250000000){
                $jinji_data[$k]['level'] = 20; // 当前等级
            }
            if ($v['until_current_total']>=250000000 and $v['until_current_total']<300000000){
                $jinji_data[$k]['level'] = 21; // 当前等级
            }
            if ($v['until_current_total']>=300000000 and $v['until_current_total']<350000000){
                $jinji_data[$k]['level'] = 22; // 当前等级
            }
            if ($v['until_current_total']>=350000000 and $v['until_current_total']<550000000){
                $jinji_data[$k]['level'] = 23; // 当前等级
            }
            if ($v['until_current_total']>=550000000 and $v['until_current_total']<850000000){
                $jinji_data[$k]['level'] = 24; // 当前等级
            }
            if ($v['until_current_total']>=850000000 and $v['until_current_total']<1200000000){
                $jinji_data[$k]['level'] = 25; // 当前等级
            }
            if ($v['until_current_total']>=1200000000 and $v['until_current_total']<1700000000){
                $jinji_data[$k]['level'] = 26; // 当前等级
            }
            if ($v['until_current_total']>=1700000000 and $v['until_current_total']<2500000000){
                $jinji_data[$k]['level'] = 27; // 当前等级
            }
            if ($v['until_current_total']>=2500000000 and $v['until_current_total']<3800000000){
                $jinji_data[$k]['level'] = 28; // 当前等级
            }
            if ($v['until_current_total']>=3800000000 and $v['until_current_total']<6000000000){
                $jinji_data[$k]['level'] = 29; // 当前等级
            }
            if ($v['until_current_total']>=6000000000){
                $jinji_data[$k]['level'] = 30; // 当前等级
            }

        }
    }
    return $jinji_data;
}