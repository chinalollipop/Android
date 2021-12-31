<?php

/**
 * AG 注单抓取
 *
 */
class model_betlog {

    // 默认初始开始获取记录的日期
    private $time_start = '2018-03-12';
    // ag xml目录
    private $ag_filepath = '../_data/ag/';
    // ag 补单目录
    private $ag_lostpath = '../_data/ag/lostAndfound/';

    private static $_mysqli = null;
    /**
     * [main go controller]
     *
     * @return [type] [description]
     */
    function __construct($mysqli) {

        self::$_mysqli = $mysqli;
    }

    public function main($local_path = NULL) {
        if (!empty($local_path)) {
            $this->ag_filepath = $local_path;
            $this->ag_lostpath = $local_path . 'lostAndfound/';
        }
        
        echo "***************投注订单数据写入数据库开始***************\n";
        $this->runNcftp();
        echo "***************投注订单数据写入数据库结束***************\n";
        
        //因为没有测试资料，补单(lostAndFound目录)暂不处理
        //注意目前ftp下载功能也没有支援下载lostAndFound目录，须补
        //echo "***************投注补单数据写入数据库开始***************\n";
//        $this->aglost();
        //echo "***************投注补单数据写入数据库结束***************\n";

        echo "run success!\n";
        exit();
    }

    public function delete_agcrawlog() {
        $sql = "DELETE FROM ".DBPREFIX."ag_craw_log WHERE filename LIKE '%20150907%'";
        return mysqli_query(self::$_mysqli, $sql);
    }

    public function update_bonus() {
        $sql = "UPDATE ".DBPREFIX."ag_projects SET bonus = amount + profit";
        return mysqli_query(self::$_mysqli, $sql);
    }

    public function sync_folders($host, $username, $password, $remote_dir, $local_dir, $passive_mode = true) {

        if (!file_exists($local_dir)) {
            self::create_folders($local_dir);
        }

        $conn_id = ftp_connect($host);
        if (!$conn_id) {
            return false;  # fail to connect
        }
        if (!ftp_login($conn_id, $username, $password)) {
            ftp_close($conn_id); # fail to login
            return false;
        }
        ftp_pasv($conn_id, $passive_mode);
        if (!ftp_chdir($conn_id, $remote_dir)) {
            ftp_close($conn_id);
            return false; # fail to change dir
        }
        if (substr($local_dir, -1) != '/') {
            $local_dir .= '/';
        }
        $list = ftp_nlist($conn_id, '.');
        $num = count($list);
        for ($i = $num - 1; $i > $num - 4; $i--) { 
            $dir = $list[$i];
            echo "处理[{$dir}]...\n";
            if ($dir == 'lostAndfound'){
                echo "跳过lostAndfound目录...\n";
                continue;                
            }
            if (!file_exists($local_dir . $dir)) {
                self::create_folders($local_dir . $dir);
            }
            $lists = ftp_nlist($conn_id, $dir);
            foreach ($lists as $file) {
                $localPath = $local_dir . $file;
                $needDownload = true;
                if (file_exists($localPath)){
                    $ftpSize = ftp_size($conn_id, $file);
                    $localSize = filesize($localPath);
                    echo "|{$ftpSize}|{$localSize}|";
                    if ($ftpSize == $localSize) 
                    {
                        $needDownload = false;
                        echo "档案大小相同，不下载{$localPath}\n";
                    }
                }
                if ($needDownload) {
                    $is_copied = ftp_get($conn_id, $localPath, $file, FTP_BINARY);
                    echo "下载{$file} => {$localPath}:{$is_copied}\n";
                }
            }
        }
        ftp_close($conn_id);
        return true;
    }

    public function runNcftp() {

//        $day = date('Ymd', strtotime('-1 day'));

        $day = date('Ymd');
        $this->ag($day);
    }

    public function ag($today) {

        if (!$today)
            $today = date('Ymd', time());
        $path = $this->ag_filepath . $today;

        echo "扫描: {$path}\n";

        $list = scandir($path);

        // 由于AG 最后12个文件总数处于随时更新的状态,所以最后写进去的12个文件必须在下一次的时候进行再次读取,所以最后12个文件不保存
        // 下一次遍历的话还会存在
        $tlist = array();
        // 获取本次从第多少个开始进行插入操作
        $num = count($list);
        $startnum = 2;
        if ($num > 14)
            $startnum = $num - 12;
//        @error_log(json_encode($list).PHP_EOL, 3, '/tmp/group/AGbet.log');

        // 写入数据
        foreach ($list as $key => $value) {
            if ($key >= $startnum)
                array_push($tlist, $value);

            if ($value == '.' || $value == '..')
                continue;

//            $res = $this->checkfiles($value);
//
//            if ($res == false)
//                continue;


            echo "=== 读取: {$value} ===\n";

            $content = '<?xml version="1.0" encoding="UTF-8"?>
<result>
  <betDetail>';
            $content .= file_get_contents($path . '/' . $value);
            $content .= '  </betDetail>
</result>
';
            $objSimpleXML = simplexml_load_string($content);
            $ar = (array) $objSimpleXML->betDetail;
            unset($content);
            unset($objSimpleXML);
             
            if (!$ar) {
                echo "betDetail读取失败，跳过\n======\n{$content}\n======\n";
                continue;
            }
            $ar = $ar['row'];

            if (!is_array($ar)) //如果只有1笔，会直接以object回传，所以要手动转成array
            {
                $ar = array($ar);
            }
            $rowCount = count($ar);
            $doneCount = 0;
            $currentIndex = 0;

//            @error_log($ar.PHP_EOL, 3, '/tmp/group/AGbet.log');


            // 加上try catch 防止程序出错
            try {
                mysqli_autocommit(self::$_mysqli,false);// 关闭本次数据库连接的自动命令提交事务模式
                foreach ($ar as $k => $v) {
                    $v = (array) $v;
                    $v = $v['@attributes'];
                    
                    $currentIndex = $currentIndex +1;
                    echo "解析第 {$currentIndex} / {$rowCount} 笔: billno[{$v['billNo']}], gamecode[{$v['gameCode']}]\n";

                    if ($v['dataType'] != 'BR' && $v['dataType'] != 'EBR')
                    {
                        echo "dateType不正确({$v['dataType']})，跳过\n";
                        continue;
                    }
                    $sql = "";
                    $v['betTime'] = date('Y-m-d H:i:s', strtotime($v['betTime']));
                    $id = $this->getuserid($v['playerName']);

                    if ($id <= 0) {
                        echo "找不到user({$v['playerName']})，解析失败!，跳过\n";
                        continue;
                    }
                    
                    // 排除flag为空的
                    if (!$v['flag'])
                    {
                        echo "flag不正确({$v['flag']})，解析失败!，跳过\n";
                        continue;
                    }

                    // insert into user_bet_log
                    $tmp = array();
                    $tmp['projectid'] = '';
                    $tmp['userid'] = $this->getuserid($v['playerName']);
                    $tmp['username'] = $v['playerName'];
                    $tmp['platform'] = 'AG';
                    $tmp['amount'] = $v['betAmount'];
                    $tmp['bonus'] = $v['netAmount'] + $v['betAmount'];
                    $tmp['iswin'] = $v['netAmount'] > 0 ? 1 : 0;
                    $tmp['bettime'] = $v['betTime'];
                    $tmp['createtime'] = date("Y-m-d H:i:s");
                    $tmp['return_point'] = 0;
                    $tmp['gamename'] = $v['gameType'];
                    $tmp['originalbetsid'] = '';
                    $tmp['gamecode'] = $v['gameCode'];
                    $tmp['thirdprojectid'] = $v['billNo'];
                    $tmp['type'] = $v['dataType'];
                    $tmp['profit'] = $v['netAmount'];
                    if (!isset($v['mainbillno'])) {
                        $tmp['mainbillno'] = 0;
                    } else {
                        $tmp['mainbillno'] = $v['mainbillno'];
                    }


                    $strKeys = join(',', array_keys($tmp));
                    $strValues = join("','", array_values($tmp));
                    // 使用SQL语句 屏蔽AG重复记录的问题 2014/07/14 18:23
                    $sql = "REPLACE INTO ".DBPREFIX."ag_projects (" . $strKeys . ") VALUES ('" . $strValues . "') ";

                    if (!empty($sql)) {
                        $id = mysqli_query(self::$_mysqli, $sql);
                        $doneCount = $doneCount + 1;
                    }
                    if (!isset($id) || $id <= 0) {
                        echo DBPREFIX."ag_projects纪录写入失败，回滚\n";
                        mysqli_rollback(self::$_mysqli);
                        break;
                    }
                }
                
                echo "笔数: {$rowCount} 笔，写入 {$doneCount} 笔\n";
                
                // insert into platform_crawl_log
                $logdata = array();
                $logdata['platform'] = 'AG';
                $logdata['filename'] = $value;
                $logdata['createtime'] = date('Y-m-d H:i:s');
                // 不在忽略列表才进行插入操作
                if (!in_array($value, $tlist))
                    $sql = "insert into ".DBPREFIX."ag_craw_log (platform,filename,creattime) values('" . $logdata['platform'] . "','" . $logdata['filename'] . "','" . $logdata['createtime'] . "')";
                if (!empty($sql)) {
                    $id = mysqli_query(self::$_mysqli, $sql);
                }

                if (!isset($id) || $id <= 0) {
                    mysqli_rollback(self::$_mysqli);
                    continue;
                }
                mysqli_commit(self::$_mysqli);
            } catch (Exception $e) {
                mysqli_rollback(self::$_mysqli);
            }
        }
    }

    private function filterOrigin($function, $data) {
        /* 各个表的字段 */
        $field = array(
            'ag' => array(
                'billNo',
                'playerName',
                'agentCode',
                'gameCode',
                'netAmount',
                'betTime',
                'gameType',
                'betAmount',
                'validBetAmount',
                'flag',
                'playType',
                'currency',
                'tableCode',
                'loginIP',
                'platformId',
                'platformType',
                'stringex',
                'remark',
                'round',
                'slottype',
                'result',
                'mainbillno',
                'teamcode',
                'handicap1',
                'handicap2',
                'odds'
            )
        );
        $data = (array) $data;
        $tmp = array();
        foreach ($field[$function] as $key => $value) {
            if ($data[$value])
                $tmp[$value] = $data[$value];
        }
        return $tmp;
    }

    private function getAGLostFiles() {
        $root = $this->ag_lostpath;
        $list = scandir($root);
        $ar = array();
        foreach ($list as $key => $value) {
            if ($value == '.' || $value == '..')
                continue;
            $tmp = scandir($root . $value);
            foreach ($tmp as $k => $v) {
                if ($v == '.' || $v == '..')
                    continue;
                $ar[] = "/" . $value . "/" . $v;
            }
        }
        return $ar;
    }

    private function getuserid($username) {
        $sql = "select userid from ".DBPREFIX."ag_users where username='{$username}' limit 1";
        $result = mysqli_query(self::$_mysqli, $sql);
        $cou=mysqli_num_rows($result);
        if ($cou==0)
            return false;
        else
            $row = mysqli_fetch_assoc($result);
        return $row['userid'];
    }

    private function checkfiles($filename) {

        $sql = "select id from ".DBPREFIX."ag_craw_log where filename='{$filename}' limit 1";
        $result = mysqli_query(self::$_mysqli, $sql);
        $row = mysqli_fetch_assoc($result);
        if (!empty($result)) {
            return false;
        }
        return true;
    }

    public function aglost() {
        $path = $this->ag_lostpath;
        $list = $this->getAGLostFiles();
        // 写入数据
        foreach ($list as $key => $value) {
            if ($value == '.' || $value == '..')
                continue;
            $content = '<?xml version="1.0" encoding="UTF-8"?>
<result>
  <betDetail totalRecords="7">';
            $content .= file_get_contents($path . '/' . $value);
            $content .= '  </betDetail>
</result>
';
            $objSimpleXML = simplexml_load_string($content);
            $ar = (array) $objSimpleXML->betDetail;
            if (!$ar)
                continue;
            $list = explode("/", $value);
            if (empty($list[2]))
                continue;
            $filename = $list[2];
            $res = $this->checkfiles($filename); //已读取过，不再读取
            if ($res == false)
                continue;
            if ($ar['@attributes']['totalRecords'] == 1) {
                $tmp = array();
                $tmp['row']['0'] = $ar['row'];
                $ar = $tmp;
            }
            $ar = $ar['row'];
            // 加上try catch 防止程序出错
            try {
                mysqli_autocommit(self::$_mysqli,false);// 关闭本次数据库连接的自动命令提交事务模式
                foreach ($ar as $k => $v) {
                    $v = (array) $v;
                    $v = $v['@attributes'];
                    // insert into original_bets_parlay_ag
                    if ($v['dataType'] != 'BR' && $v['dataType'] != 'EBR')
                        continue;
                    $v['betTime'] = date('Y-m-d H:i:s', strtotime($v['betTime']));
                    // 排除flag为空的
                    if (!$v['flag'])
                        continue;
                    // insert into user_bet_log
                    $tmp = array();
                    $tmp['projectid'] = '';
                    $tmp['userid'] = $this->getuserid($v['playerName']);
                    $tmp['username'] = $v['playerName'];
                    $tmp['platform'] = 'AG';
                    $tmp['amount'] = $v['betAmount'];
                    $tmp['bonus'] = $v['netAmount'] > 0 ? $v['netAmount'] : 0;
                    $tmp['iswin'] = $v['netAmount'] > 0 ? 1 : 0;
                    $tmp['bettime'] = $v['betTime'];
                    $tmp['createtime'] = date("Y-m-d H:i:s");
                    $tmp['return_point'] = 0;
                    $tmp['gamename'] = $v['gameType'];
                    $tmp['originalbetsid'] = '';
                    $tmp['gamecode'] = $v['gameCode'];
                    $tmp['thirdprojectid'] = $v['billNo'];
                    $tmp['type'] = $v['dataType'];
                    $tmp['profit'] = $v['netAmount'];
                    if (!isset($v['mainbillno'])) {
                        $tmp['mainbillno'] = 0;
                    } else {
                        $tmp['mainbillno'] = $v['mainbillno'];
                    }

                    // 使用SQL语句 屏蔽AG重复记录的问题 2014/07/14 18:23
                    $sql = "REPLACE INTO ".DBPREFIX."ag_projects (" . join(',', array_keys($tmp)) . ") VALUES ('" . join("','", array_values($tmp)) . "') ";
                    $id = mysqli_query(self::$_mysqli, $sql);
                    if ($id <= 0) {
                        mysqli_rollback(self::$_mysqli);
                        break;
                    }
                }
                // insert into platform_crawl_log
                $logdata = array();
                $logdata['platform'] = 'AG';
                $logdata['filename'] = $filename;
                $logdata['createtime'] = date('Y-m-d H:i:s');
                $sql = "insert into ".DBPREFIX."ag_craw_log (platform,filename,creattime) values('" . $logdata['platform'] . "','" . $logdata['filename'] . "','" . $logdata['createtime'] . "')";
                $id = mysqli_query(self::$_mysqli, $sql);
                if ($id <= 0) {
                    mysqli_rollback(self::$_mysqli);
                    continue;
                }
                mysqli_commit(self::$_mysqli);
            } catch (Exception $e) {
                mysqli_rollback(self::$_mysqli);
            }
        }
    }
    /**
     * 创建多级目录，方便保存日志
     * @param $dir
     * @return bool
     */
    public function create_folders($dir){
        return is_dir($dir) or ($this->create_folders(dirname($dir)) and mkdir($dir, 0777));
    }

    /**
     * 生成获取数据的链接（AG视讯、AG电子）
     * @param $data_api_url
     * @param $data
     * @param $md5key
     * @return string
     */
    public function getDataApiUrl($data_api_url, $data, $md5key){
        $strMd5='';
        foreach ($data as $v){
            $strMd5.=$v;
        }

        $Sign=md5($strMd5.$md5key);

        $strParams='';
        foreach ($data as $k => $v){
            $strParams.=$k.'='.$v.'&';
        }
        $url = $data_api_url.$strParams.'key='.$Sign;
        return $url;
    }

    /**
     * 生成获取数据的链接（捕鱼王）
     * @param $data_api_url
     * @param $data
     * @param $md5key
     * @return string
     */
    public function getBuyuDataApiUrl($data_api_url, $data, $md5key){
        $strMd5='';
        foreach ($data as $v){
            $strMd5.=$v;
        }

        $Sign=md5($md5key.$strMd5);

        $strParams='';
        foreach ($data as $k => $v){
            $strParams.=$k.'='.$v.'&';
        }
        $url = $data_api_url.$strParams.'sessionkey='.$Sign;
        return $url;
    }

    /**
     * Ag视讯数据入库
     * @param $rows
     * @param $rowCount
     * @param $usefix
     */
    public function zrsxInDb($rows, $rowCount, $usefix){

        @error_log('真人注单共拉取到：'.$rowCount . PHP_EOL, 3, '/tmp/group/AGbet.log');

        // 加上try catch 防止程序出错
        try {
            $strValues_all = '';
            mysqli_autocommit(self::$_mysqli,false);// 关闭本次数据库连接的自动命令提交事务模式
            foreach ($rows as $k => $v){ // 更新条数
//                @error_log(json_encode($v) . PHP_EOL, 3, '/tmp/group/AGbet.log');
                if(isset($v['@attributes'])) $v = $v['@attributes'];
//                @error_log(json_encode($v) . PHP_EOL, 3, '/tmp/group/AGbet.log');

                $v['betTime'] = date('Y-m-d H:i:s', strtotime($v['betTime']));

                echo "$k -------------- 确认会员Start \n";
                if(strpos($v['playName'],$usefix)===false) continue;

                $result = mysqli_query(self::$_mysqli, "select userid from ".DBPREFIX."ag_users where username='{$v['playName']}' limit 1");
                $cou=mysqli_num_rows($result);
                if ($cou==0) {continue; @error_log("$k 确认会员 跳过" . PHP_EOL, 3, '/tmp/group/AGbet.log');}
                $row = mysqli_fetch_assoc($result);
                $id = $row['userid'];

                if ($id <= 0) {
                    echo "$k 找不到user({$v['playName']})，解析失败!，跳过\n";
                    @error_log("$k 找不到user({$v['playName']})，解析失败!，跳过" . PHP_EOL, 3, '/tmp/group/AGbet.log');
                    continue;
                }

                // 排除flag为空的
                if (!$v['flag']) // flag :訂單狀態,0 為未派彩 ,1 為已派彩
                {
                    echo "$k flag不正确({$v['flag']})，解析失败!，跳过\n";
                    @error_log("$k flag不正确({$v['flag']})，解析失败!，跳过" . PHP_EOL, 3, '/tmp/group/AGbet.log');
                    continue;
                }

                // 代理级别信息
                $result = mysqli_query(self::$_mysqli, "select Agents,World,Corprator,Super,Admin from ".DBPREFIX.MEMBERTABLE." where ID = $id");
                $cou = mysqli_num_rows($result);
                if ($cou==0) {continue; @error_log("代理级别 跳过" . PHP_EOL, 3, '/tmp/group/AGbet.log');}
                $row = mysqli_fetch_assoc($result);
                $aUser=$row;

                // insert into user_bet_log
                $tmp = array();
                $tmp['projectid'] = '';
                $tmp['userid'] = $id;
                $tmp['username'] = $v['playName'];
                $tmp['Agents'] = $aUser['Agents'];
                $tmp['World'] = $aUser['World'];
                $tmp['Corprator'] = $aUser['Corprator'];
                $tmp['Super'] = $aUser['Super'];
                $tmp['Admin'] = $aUser['Admin'];
//                $tmp['platform'] = 'AG';
                $tmp['amount'] = $v['betAmount'];
                $tmp['valid_money'] = $v['validBetAmount'];
                $tmp['bonus'] = $v['netAmount'] + $v['betAmount'];
                $tmp['iswin'] = $v['netAmount'] > 0 ? 1 : 0;
                $tmp['bettime'] = $v['betTime'];
                $tmp['createtime'] = date("Y-m-d H:i:s");
                $tmp['return_point'] = 0;
                $tmp['gamename'] = $v['gameType'];
                $tmp['originalbetsid'] = '';
                $tmp['gamecode'] = $v['gameCode'];
                $tmp['playType'] = $v['playType'];
                $tmp['thirdprojectid'] = $v['billNo'];
                $tmp['type'] = 'BR'; // BR AG真人数据
                $tmp['profit'] = $v['netAmount'];
                if (!isset($v['mainbillno'])) {
                    $tmp['mainbillno'] = 0;
                } else {
                    $tmp['mainbillno'] = $v['mainbillno'];
                }
                $tmp['devicetype'] = $v['devicetype'];
                $tmp['flag'] = $v['flag'];

                $strKeys = join(',', array_keys($tmp));
                $strValues = join("','", array_values($tmp));

                $strValues_all .= "('".$strValues."'),";

            }
            $strValues_all = chop($strValues_all, ","); //删除字符串末端的空白字符（或者其他字符）
            $sql = "REPLACE INTO ".DBPREFIX."ag_projects (" . $strKeys . ") VALUES $strValues_all ";
            $res = mysqli_query(self::$_mysqli, $sql);
            if ($res){
                echo DBPREFIX."ag_projects纪录写入成功\n";
            }else{
                echo DBPREFIX."ag_projects纪录写入失败\n";
            }

            mysqli_commit(self::$_mysqli);

        } catch (Exception $e) {
            @error_log($e . PHP_EOL, 3, '/tmp/group/AGbet.log');
        }

    }

    /**
     * @param $rows
     * @param $rowCount
     * @param $usefix
     */
    public function dianziInDb($rows, $rowCount, $usefix){

        @error_log('电子注单共拉取到：'.$rowCount . PHP_EOL, 3, '/tmp/group/AGbet.log');

        // 加上try catch 防止程序出错
        try {
            $strValues_all = '';
            mysqli_autocommit(self::$_mysqli,false);// 关闭本次数据库连接的自动命令提交事务模式
            foreach ($rows as $k => $v){ // 更新条数
                //$v = $v['@attributes'];
                if(isset($v['@attributes'])) $v = $v['@attributes'];
//                @error_log(json_encode($v) . PHP_EOL, 3, '/tmp/group/AGbet.log');

                $v['billtime'] = date('Y-m-d H:i:s', strtotime($v['billtime']));

                echo "$k -------------- 确认会员Start \n";

                if(strpos($v['username'],$usefix)===false) continue;

                $result = mysqli_query(self::$_mysqli, "select userid from ".DBPREFIX."ag_users where username='{$v['username']}' limit 1");
                $cou=mysqli_num_rows($result);
                if ($cou==0) continue;
                $row = mysqli_fetch_assoc($result);
                $id = $row['userid'];

                if ($id <= 0) {
                    echo "$k 找不到user({$v['username']})，解析失败!，跳过\n";
                    continue;
                }

                // 排除flag为空的
                if (!$v['flag']) // flag :訂單狀態,0 為未派彩 ,1 為已派彩
                {
                    echo "$k flag不正确({$v['flag']})，解析失败!，跳过\n";
                    continue;
                }

                // 代理级别信息
                $result = mysqli_query(self::$_mysqli, "select Agents,World,Corprator,Super,Admin from ".DBPREFIX.MEMBERTABLE." where ID = $id");
                $cou = mysqli_num_rows($result);
                if ($cou==0) continue;
                $row = mysqli_fetch_assoc($result);
                $aUser=$row;

                // insert into user_bet_log
                $tmp = array();
                $tmp['projectid'] = $v['productid'];
                $tmp['userid'] = $id;
                $tmp['username'] = $v['username'];
                $tmp['Agents'] = $aUser['Agents'];
                $tmp['World'] = $aUser['World'];
                $tmp['Corprator'] = $aUser['Corprator'];
                $tmp['Super'] = $aUser['Super'];
                $tmp['Admin'] = $aUser['Admin'];
//                $tmp['platform'] = 'AG';
                $tmp['amount'] = $v['account'];
                $tmp['valid_money'] = $v['valid_account'];
                $tmp['bonus'] = $v['account'] + $v['cus_account'];
                $tmp['iswin'] = $v['cus_account'] > 0 ? 1 : 0;
                $tmp['bettime'] = $v['billtime'];
                $tmp['createtime'] = date("Y-m-d H:i:s");
                $tmp['slottype'] = $v['slottype'];
                $tmp['return_point'] = 0;
                $tmp['gamename'] = $v['gametype'];
                $tmp['originalbetsid'] = '';
                $tmp['gamecode'] = '';
                $tmp['thirdprojectid'] = $v['billno'];
                $tmp['type'] = 'SLOT'; // BR 真人数据  SLOT  電子游戲廳
                $tmp['profit'] = $v['cus_account'];
                if (!isset($v['mainbillno'])) {
                    $tmp['mainbillno'] = 0;
                } else {
                    $tmp['mainbillno'] = $v['mainbillno'];
                }
                $tmp['flag'] = $v['flag'];

                $strKeys = join(',', array_keys($tmp));
                $strValues = join("','", array_values($tmp));
                $strValues_all .= "('".$strValues."'),";

            }

            $strValues_all = chop($strValues_all, ","); //删除字符串末端的空白字符（或者其他字符）
            $sql = "REPLACE INTO ".DBPREFIX."ag_projects (" . $strKeys . ") VALUES $strValues_all ";
            $res = mysqli_query(self::$_mysqli, $sql);
            if ($res){
                echo DBPREFIX."ag_projects纪录写入成功\n";
            }else{
                echo DBPREFIX."ag_projects纪录写入失败\n";
            }
            mysqli_commit(self::$_mysqli);
        } catch (Exception $e) {
            mysqli_rollback(self::$_mysqli);
        }

    }

    /**
     * 获取用户场景捕鱼结算数据入库
     * @param $rows
     * @param $rowCount
     * @param $usefix AG用户名前缀
     */
    public function buyuDayuSceneInDb($rows, $rowCount, $usefix){

        @error_log('捕鱼王用户场景捕鱼结算数据共拉取到：'.$rowCount . PHP_EOL, 3, '/tmp/group/AGbuyuScene.log');

        // 加上try catch 防止程序出错
        try {
            $strValues_all = '';
            mysqli_autocommit(self::$_mysqli,false);// 关闭本次数据库连接的自动命令提交事务模式
            foreach ($rows as $k => $v) {

                $v['StartTime'] = date('Y-m-d H:i:s', $v['StartTime']);
                $v['EndTime'] = date('Y-m-d H:i:s', $v['EndTime']);

                echo "$k -------------- 确认会员Start \n";

                if(strpos($v['UserName'],$usefix)===false) continue;

                $result = mysqli_query(self::$_mysqli, "select userid from ".DBPREFIX."ag_users where username='{$v['UserName']}' limit 1");
                $cou=mysqli_num_rows($result);
                if ($cou==0) continue;
                $row = mysqli_fetch_assoc($result);
                $id = $row['userid'];

                if ($id <= 0) {
                    echo "$k 找不到user({$v['username']})，解析失败!，跳过\n";
                    continue;
                }

                // 代理级别信息
                $result = mysqli_query(self::$_mysqli, "select Agents,World,Corprator,Super,Admin from ".DBPREFIX.MEMBERTABLE." where ID = $id");
                $cou = mysqli_num_rows($result);
                if ($cou==0) continue;
                $row = mysqli_fetch_assoc($result);
                $aUser=$row;

                $tmp = array();
                $tmp['BillId'] = $v['BillId'];
                $tmp['SceneId'] = $v['SceneId'];
                $tmp['StartTime'] = $v['StartTime'];
                $tmp['EndTime'] = $v['EndTime'];
                $tmp['BulletOutNum'] = $v['BulletOutNum'];
                $tmp['RoomBet'] = $v['RoomBet'];
                $tmp['Cost'] = $v['Cost'];
                $tmp['Earn'] = $v['Earn'];
                $tmp['RoomId'] = $v['RoomId'];
                $tmp['userid'] = $id;
                $tmp['UserName'] = $v['UserName'];
                $tmp['Agents'] = $aUser['Agents'];
                $tmp['JpDraw'] = $v['JpDraw'];
                $tmp['SceneEx'] = $v['SceneEx'];
                $tmp['JackPotComm'] = $v['JackPotComm'];
                $tmp['Remark'] = $v['Remark'][0];
                $tmp['OperType'] = $v['OperType'];
                $tmp['BillHitNum'] = $v['BillHitNum'];
                $tmp['BillType'] = $v['BillType'];

                $strKeys = join(',', array_keys($tmp));
                $strValues = join("','", array_values($tmp));
                $strValues_all .= "('".$strValues."'),";

            }
            $strValues_all = chop($strValues_all, ","); //删除字符串末端的空白字符（或者其他字符）
            $sql = "REPLACE INTO ".DBPREFIX."ag_buyu_scene (" . $strKeys . ") VALUES $strValues_all ";
            $res = mysqli_query(self::$_mysqli, $sql);
            if ($res){
                echo DBPREFIX."ag_buyu_scene纪录写入成功\n";
            }else{
                echo DBPREFIX."ag_buyu_scene纪录写入失败\n";
            }
            mysqli_commit(self::$_mysqli);

        } catch (Exception $e) {
            mysqli_rollback(self::$_mysqli);
        }
    }

    /**
     * 获取游戏注单信息入库
     * @param $rows
     * @param $rowCount
     * @param $usefix AG用户名前缀
     */
    public function buyuDayuBetInDb($rows, $rowCount, $usefix){

        @error_log('捕鱼王游戏注单信息共拉取到：'.$rowCount . PHP_EOL, 3, '/tmp/group/AGbuyubet.log');

        // 加上try catch 防止程序出错
        try {
            $strValues_all = '';
            mysqli_autocommit(self::$_mysqli,false);// 关闭本次数据库连接的自动命令提交事务模式
            foreach ($rows as $k => $v) {

                $v['Time'] = date('Y-m-d H:i:s', $v['Time']);

                echo "$k -------------- 确认会员Start \n";

                if(strpos($v['UserName'],$usefix)===false) continue;

                $result = mysqli_query(self::$_mysqli, "select userid from ".DBPREFIX."ag_users where username='{$v['UserName']}' limit 1");
                $cou=mysqli_num_rows($result);
                if ($cou==0) continue;
                $row = mysqli_fetch_assoc($result);
                $id = $row['userid'];

                if ($id <= 0) {
                    echo "$k 找不到user({$v['username']})，解析失败!，跳过\n";
                    continue;
                }

                // 代理级别信息
                $result = mysqli_query(self::$_mysqli, "select Agents,World,Corprator,Super,Admin from ".DBPREFIX.MEMBERTABLE." where ID = $id");
                $cou = mysqli_num_rows($result);
                if ($cou==0) continue;
                $row = mysqli_fetch_assoc($result);
                $aUser=$row;

                $tmp = array();
                $tmp['BillId'] = $v['BillId'];
                $tmp['userid'] = $id;
                $tmp['UserName'] = $v['UserName'];
                $tmp['Agents'] = $aUser['Agents'];
                $tmp['RoomId'] = $v['RoomId'];
                $tmp['RoomBet'] = $v['RoomBet'];
                $tmp['BuyuUserId'] = $v['BuyuUserId'];
                $tmp['BombFish'] = $v['BombFish'];
                $tmp['Hunted'] = $v['Hunted'];
                $tmp['FishType'] = $v['FishType'];
                $tmp['FishLife'] = $v['FishLife'];
                $tmp['FishId'] = $v['FishId'];
                $tmp['CannonBoost'] = $v['CannonBoost'];
                $tmp['CannonCost'] = $v['CannonCost'];
                $tmp['FishCost'] = $v['FishCost'];
                $tmp['UserCashBefore'] = $v['UserCashBefore'];
                $tmp['UserCashDelta'] = $v['UserCashDelta'];
                $tmp['UserCashCurrent'] = $v['UserCashCurrent'];
                $tmp['SceneId'] = $v['SceneId'];
                $tmp['Time'] = $v['Time'];
                $tmp['UserCashPay'] = $v['UserCashPay'];
                $tmp['UserCashEarn'] = $v['UserCashEarn'];
                $tmp['ProductId'] = $v['ProductId'];
                $tmp['UserType'] = $v['UserType'][0];
                $tmp['JackPotComm'] = $v['JackPotComm'];
                $tmp['OperType'] = $v['OperType'];

                $strKeys = join(',', array_keys($tmp));
                $strValues = join("','", array_values($tmp));
                $strValues_all .= "('".$strValues."'),";

            }
            $strValues_all = chop($strValues_all, ","); //删除字符串末端的空白字符（或者其他字符）
            $sql = "REPLACE INTO ".DBPREFIX."ag_buyu_projects (" . $strKeys . ") VALUES $strValues_all ";
            $res = mysqli_query(self::$_mysqli, $sql);
            if ($res){
                echo DBPREFIX."ag_buyu_bet纪录写入成功\n";
            }else{
                echo DBPREFIX."ag_buyu_bet纪录写入失败\n";
            }
            mysqli_commit(self::$_mysqli);

        } catch (Exception $e) {
            mysqli_rollback(self::$_mysqli);
        }
    }


}