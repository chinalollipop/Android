<?php

/**
 *
 * 定时任务：
 * API 请求间隔时长，5秒钟
 * 请求数据最大时间跨度10分钟
 * 大于500条，多页面时，分多次请求
 * 多页处理
 * sleep5秒，请求下一页数据，直到最后一页结束
 *
 * 1，	只支持cli模式下的运行。
 * 2， 示例URL:
 *         php ag_get_zhenren_bet.php   //定时任务，捞取最近9分钟+1秒钟的数据
 *     或
 *         php ag_get_zhenren_bet.php 2018-03-27 07:20:00 2018-03-27 07:29:00   //定时任务，捞取指定时间段的数据
 *
 */

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

define("INCLUDE_DIR",  dirname(dirname(dirname(__FILE__))));
require INCLUDE_DIR."/common/config.php";
require INCLUDE_DIR."/include/agbetlog.php";

echo "--------------------------------------------------------------------------捞取真人视讯注单数据 START \n";


//只在CLI命令下有效
if (php_sapi_name() == "cli") {

    $data['cagent'] = $agsxInit['data_api_cagent'];
//    $data['startdate'] = '2018-03-30 07:50:00';
//    $data['enddate'] = '2018-03-30 04:59:00';
    if (isset($argv['1']) && isset($argv['2']) && isset($argv['3']) && isset($argv['4']) ) {
        $data['startdate'] = $argv['1'].' '.$argv['2'];
        $data['enddate'] = $argv['3'].' '.$argv['4'];
    } else {
        // 每隔4分钟抓取一次数据（减去5分钟，获取前面5分钟的注单）
        $time = time();
        $data['startdate'] = date('Y-m-d H:i:s', $time - 360);
        $data['enddate'] = date('Y-m-d H:i:s', $time - 60);
    }
    //$data['gametype'] ='0';
    $data['order'] = 'reckontime';
    $data['by'] = 'ASC';
    $data['page'] = '1'; // 默认为第1页
    $data['perpage'] = '500'; // 每页条目


    $oAg = new model_betlog($dbMasterLink);
    $getBetUrl = $oAg->getDataApiUrl($agsxInit['data_api_url'] . '/getorders.xml?', $data, $agsxInit['data_api_md5_key']);
//    print_r($getBetUrl); die;
    echo "--------------------------------------------------------------------------准备捞取真人视讯注单数据URL 链接 INIT SUCCESS \n";

    $data_xml = file_get_contents($getBetUrl);
    $object_xml = simplexml_load_string($data_xml);
    $xml_json = json_encode($object_xml);
    $xml_array = json_decode($xml_json, true);
//    var_dump( $xml_array ); die;
    echo "--------------------------------------------------------------------------捞取数据 SUCCESS \n";


    //实例化
    if ($xml_array['info'] == 0) {

        // 分页信息
        $data_page = $xml_array['addition'];
        $total = $data_page['total']; // 总共条目
        $num_per_page = $data_page['num_per_page']; // 每页条数
        $currentpage = $data_page['currentpage']; // 当前页号
        $totalpages = $data_page['totalpage']; // 总页数
        $perpage = $data_page['perpage']; // 当前页条目

        $rowCount = $total;
        $doneCount = 0;

        echo "----------------------- 数据总共条目 {$total}，总共分页数 {$totalpages}，当前页 {$currentpage} \n";

        echo "--------------------------------------------------------------------------\n";

        // 数据为空
        $total == 0 ? die : '';

        // 分页数大于1页时，循环页面处理数据
        if ($total > 0 and $total <= 500 && $totalpages == 1) {
            @error_log('视讯：'.$data['startdate'].'-'.$data['enddate'] . PHP_EOL, 3, '/tmp/group/AGcenterBets.log');
            $userLocalData=array();
            foreach ($xml_array['row'] as $k => $v){ // 获取自己平台用户数据
                if(isset($v['@attributes'])) $v = $v['@attributes'];
                $userLocalData[]=$v;
            }

            if(count($userLocalData)>0){
                $oAg->zrsxInDb($userLocalData, count($userLocalData));
            }else{
                echo "\n\r-----------没有本平台用户,不作处理！------------\n\r";
            }

        } else {
            /**
             * 多页处理
             * sleep5秒，按照分页依次请求，请求下一页数据，直到最后一页结束
             *      更改查询条件 page+1
             */
            @error_log(date("Y-m-d H:i:s") . PHP_EOL, 3, '/tmp/group/AGcenterBets.log');

            // 间隔15秒后重新抓取
            sleep(5);

            for ($i = 1; $i <= $totalpages; $i++) {

                echo "处理第 {$i}/{$totalpages} 页数据开始\n";
                $data['page'] = $i;
                $getBetUrl = $oAg->getDataApiUrl($agsxInit['data_api_url'] . '/getorders.xml?', $data, $agsxInit['data_api_md5_key']);

//                @error_log(date("Y-m-d H:i:s") . ' : ' . $getBetUrl . PHP_EOL, 3, '/tmp/group/AGcenterBets.log');

                $data_xml = file_get_contents($getBetUrl);
                $object_xml = simplexml_load_string($data_xml);
                $xml_json = json_encode($object_xml);
                $xml_array = json_decode($xml_json, true);
                $userLocalData=array();
                foreach ($xml_array['row'] as $k => $v){ // 获取自己平台用户数据
                    if(isset($v['@attributes'])) $v = $v['@attributes'];
                    $userLocalData[]=$v;
                }

                if(count($userLocalData)>0){
                    // 入库
                    $oAg->zrsxInDb($userLocalData, count($userLocalData));
                }else{
                    echo "\n\r-----------此页没有本平台用户,不作处理！------------\n\r";
                }

                // 处理完最后一页正常结束，无需sleep
                if ($i < $totalpages)
                    sleep(5);

                echo "-------------------------------------------\n";
            }

        }

    } else {

        switch ($xml_array['info']) {
            case '44000':
                exit('Key 错误,请检查明码及加密顺序是否正确');
                break;
            case '64010':
                exit('查询额度成功');
                break;
            case '61003':
                exit('产品 ID 或 CATENT 不存在');
                break;
            case '60001':
                exit('不存在该用户');
                break;
            case '61004':
                exit('指令执行错误');
                break;
            case '61001':
                exit('查詢請求時間被限制');
                break;
            case '61002':
                exit('参数缺失,请检查是否参数都有正确带入');
                break;
            case '61005':
                exit('代理商不存在');
                break;
        }

    }
}