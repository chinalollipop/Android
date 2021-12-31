<?php

/**
 *
 * 定时任务：
 * API 请求间隔时长，最少5秒钟
 * 请求数据最大时间跨度10分钟
 * 大于500条，多页面时，分多次请求
 * 多页处理
 * sleep15秒，请求下一页数据，直到最后一页结束
 *
 * 1，	只支持cli模式下的运行。
 * 2， 示例URL:
 *         php ag_get_buyu_scene.php   //定时任务，每4分钟捞取最近5分钟的数据
 *     或
 *         php ag_get_buyu_scene.php 2018-03-27 07:20:00 2018-03-27 07:29:00   //定时任务，捞取指定时间段的数据
 *
 */

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

define("INCLUDE_DIR",  dirname(dirname(dirname(__FILE__))));
require INCLUDE_DIR."/common/config.php";
require INCLUDE_DIR."/include/agbetlog.php";

echo "--------------------------------------------------------------------------捞取真人捕鱼王注单数据 START \n";

//只在CLI命令下有效
if (php_sapi_name() == "cli") {

    $data['cagent'] = $agsxInit['data_api_cagent'];
//    $data['startdate'] = '2018-03-30 07:50:00';
//    $data['enddate'] = '2018-03-30 04:59:00';
    if (isset($argv['1']) && isset($argv['2']) && isset($argv['3']) && isset($argv['4']) ) {
        $data['startdate'] = strtotime($argv['1'].' '.$argv['2']);
        $data['enddate'] = strtotime($argv['3'].' '.$argv['4']);
    } else {
        // 每隔4分钟抓取一次数据（减去5分钟，获取前面5分钟的注单）
        $time = time();
        $data['startdate'] = strtotime(date('Y-m-d H:i:s', $time - 360));
        $data['enddate'] = strtotime(date('Y-m-d H:i:s', $time - 60));
    }
   // $data['sceneid'] = ''; // 场景号
//    $data['pidtoken'] = $agsxInit['data_api_md5_key']; // 明码
//    $data['productid'] = $agsxInitp['data_api_cagent']; // AG前缀
    //$data['username'] = $agsxInitp['data_api_cagent'].$userPrefix."_"; // 当有值时开启模糊查询模式（当前支持 username）
   // $data['username'] = ''; // 当有值时开启模糊查询模式（当前支持 username）
//    $data['gametype'] = 'HM2D'; // 游戏类型: 捕魚王2D：HM2D  捕魚王3D：HM3D，为空则全部拉取
   // $data['order'] = 'time'; // 排序字段:可选项为场景开始时间（time）,（count）,发射子弹额度(cost)、捕鱼收入额度(earn)。在字段前面用符号“-”表示降序，默认为升序。
    $data['order'] = 'billtime'; // 排序字段:starttime  endtime  billtime
    $data['by'] = 'ASC'; // 排序字段:ASC 或 DESC 。
    $data['page'] = '1'; // 默认为第1页
    // $data['numperpage'] = '500'; // 每页条目
    $data['perpage'] = '500'; // 每页条目
   // $data['fuzzyquery'] = 0; // 当有值时开启模糊查询模式（当前支持 username）

//    print_r($data); die;

    $oAg = new model_betlog($dbMasterLink);
    // $getBetUrl = $oAg->getBuyuDataApiUrl($agsxInit['data_api_buyu_url'] . '/api?act=getscenesofuserreportExt&', $data, $agsxInit['data_api_md5_key']);
   // $getBetUrl = $oAg->getBuyuDataApiUrl($agsxInit['data_api_url'] . '/gethunterscene.xml?startdate='.$data['startdate'].'&enddate='.$data['enddate'].'&cagent='.$agsxInitp['data_api_cagent'].'&', $data, $agsxInit['data_api_md5_key']);
    $getBetUrl = $oAg->getBuyuDataApiUrl($agsxInit['data_api_url'] . '/gethunterscene.xml?', $data, $agsxInit['data_api_md5_key']);
//    print_r($getBetUrl); die;
    echo "--------------------------------------------------------------------------准备捞取真人捕鱼王注单数据URL 链接 INIT SUCCESS \n";

    $data_xml = file_get_contents($getBetUrl);
    $object_xml = simplexml_load_string($data_xml);
    $xml_json = json_encode($object_xml);
    $xml_array = json_decode($xml_json, true);
//    var_dump( $xml_array ); die;
    echo "--------------------------------------------------------------------------捞取数据 SUCCESS \n";


    //实例化
    if ($xml_array['info'] == 0) {

        // 分页信息
//        $data_page = $xml_array['addition']; // 子数组
        $total = $xml_array['addition']['total']; // 总共条目
        $num_per_page = $xml_array['addition']['perpage']; // 每页条数
        $currentpage = $xml_array['addition']['currentpage']; // 当前页号
        $totalpages = $xml_array['addition']['totalpage']; //总页数
        $perpage = $xml_array['addition']['perpage']; // 当前页条数
//        if ($total<=0){
//            $totalpages=0;
//        }else{
//            $totalpages = ceil($total / $num_per_page); // 总页数 = (总共数目/每页条数)  ceil进一法取整
//        }

        $rowCount = $total;
        $doneCount = 0;

        echo "----------------------- 数据总共条目 {$total}，总共分页数 {$totalpages}，当前页 {$currentpage} \n";

        echo "--------------------------------------------------------------------------\n";

        // 数据为空
        $total == 0 ? die : '';

        // 分页数大于1页时，循环页面处理数据
        if ($total > 0 and $total <= 500 && $totalpages == 1) {

//            @error_log('捕鱼王用户场景捕鱼结算数据：'.date('Y-m-d H:i:s',$data['startdate']).'-'.date('Y-m-d H:i:s',$data['enddate']) . PHP_EOL, 3, '/tmp/group/AGbuyuScene.log');

            // 入库
            if (!isset($xml_array['row'][0])){// 总共1页，请求一次只获取到1条数据

                $tmp['row'][0]=$xml_array['row'];
                $oAg->buyuDayuSceneInDb($tmp['row'], $rowCount);
            }else{

                $oAg->buyuDayuSceneInDb($xml_array['row'], $rowCount);
            }

        } else {
            /**
             * 多页处理
             * sleep15秒，按照分页依次请求，请求下一页数据，直到最后一页结束
             *      更改查询条件 page+1
             */
//            @error_log(date("Y-m-d H:i:s") . PHP_EOL, 3, '/tmp/group/AGbuyuScene.log');

            // 间隔15秒后重新抓取
            sleep(15);

            for ($i = 1; $i <= $totalpages; $i++) {

                echo "处理第 {$i}/{$totalpages} 页数据开始\n";
                $data['page'] = $i;
               // $getBetUrl = $oAg->getBuyuDataApiUrl($agsxInit['data_api_buyu_url'] . '/api?act=getscenesofuserreportExt&', $data, $agsxInit['data_api_md5_key']);
                $getBetUrl = $oAg->getBuyuDataApiUrl($agsxInit['data_api_url'] . '/gethunterscene.xml?', $data, $agsxInit['data_api_md5_key']);

//                @error_log(date("Y-m-d H:i:s") . ' : ' . $getBetUrl . PHP_EOL, 3, '/tmp/group/AGbuyuScene.log');

                $data_xml = file_get_contents($getBetUrl);
                $object_xml = simplexml_load_string($data_xml);
                $xml_json = json_encode($object_xml);
                $xml_array = json_decode($xml_json, true);
                // 入库
                if (!isset($xml_array['row'][0])){// 分页数大于1页，请求一次只获取到1条数据
                    $tmp['row'][0]=$xml_array['row'];
                    $oAg->buyuDayuSceneInDb($tmp['row'], 1);
                }else{
                    $oAg->buyuDayuSceneInDb($xml_array['row'], count($xml_array['row']));
                }

                // 处理完最后一页正常结束，无需sleep
                if ($i < $totalpages)
                    sleep(15);

                echo "-------------------------------------------\n";
            }

        }

    }
    else {
        exit('返回数据有误');
    }
}