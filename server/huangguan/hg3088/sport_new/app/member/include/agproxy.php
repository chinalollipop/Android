<?php

/*
 * 用来换掉library/aggame.php (注意不是jxc/highadmin/weblib/aggame.php 这两个不一样)，做出相同界面，减少切换成本
 * 实做：把所有 new aggame() 换为 agproxy::getInterface();
 */
require("agdes.php");

class agproxy {
    
    private $domain_url = 'www.default.net';
    private $api_url = 'http://gi.default.com:81/doBusiness.do';
    private $game_url = 'http://gci.default.com:81/forwardGame.do';
    private $cagent = '';
    private $md5_key = 'XXXXXXXXXXXX';
    private $des_key = 'XXXXXXXX';
    private $logFolder = '/tmp/group/ag/';
    private $testers; //试玩帐号
    private $guid; //用来判定是否是同一次request
    private $cny;
//    private $prefix = ''; //前戳(配合旧线格式，只有july平台有用，只在play_creat时使用)

    public function __construct($domain_url, $api_url, $game_url, $cagent, $md5_key, $des_key, $testers, $cny) {
        //初始化配置 按设定更换(各平台不同设定）
        $this->domain_url = $domain_url ? $domain_url: $this->domain_url;
        $this->api_url = $api_url ? $api_url : $this->api_url;
        $this->game_url = $game_url?$game_url:$this->game_url;
        $this->cagent = $cagent?$cagent:$this->cagent;
        $this->md5_key = $md5_key?$md5_key:$this->md5_key;
        $this->des_key = $des_key?$des_key:$this->des_key;
        $this->testers = explode(',',$testers);
        $this->cny = $cny?$cny:'CNY';

        if (!file_exists($this->logFolder))
        {
            $this->create_folders($this->logFolder);
        }
        $this->guid = $this->_guidv4();
    }

    /**
     * 以下实做和library/aggame.php相同界面
     *
     * 回传 code = 1  表示执行成功
     *
     * ↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
     * ↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
     */

    /**
     * 创建玩家账号
     * @param type $loginname
     * @param type $password
     * @param type $bIsTest
     * @return array
     */
    public function play_creat($loginname=NULL, $password = NULL,$bIsTest = NULL) {
        $loginname = $this->prefix . $loginname;
        $result = $this->ag_checkOrCreateGameAccount($loginname, $password, $bIsTest);
        if ($result['info'] === '0')
        {
            $result['code'] = 1;
            $result['data'] = array('playername' => $loginname, 'password' => $password); //旧线有额外提供帐号密码，比照办理
            $this->_writeLog('INFO',__FUNCTION__,$loginname,$result);
        }
        else if (!empty($result['info'])) {
            $result['code'] = 'info:'.$result['info'];
            $this->_writeLog('ERROR',__FUNCTION__,$loginname,$result);
        }

        return $result;
    }

    /**
     * 获取AG游戏链接
     * @param type $loginname
     * @param type $password
     * @param type $lang
     * @param type $isTest
     * @return url(string)
     */
    public function player_login_url($loginname,$password,$lang='',$isTest = false,$gameType=0, $pam_mh5) {
        $dm = $this->domain_url;
        $lang = $lang ? $lang : 1;
        $gameType == 0 ? 0 : $gameType;
        $sid = $this->_getUniNumber();
        $url = $this->ag_forwardGameUrl($loginname, $password, $dm, $sid, $lang, $isTest, $gameType, $pam_mh5, '', '');
        return array('url' => $url);   //旧线格式，比照办理

    }

    /**
     * 获取玩家在AG平台的余额
     * @param type $loginname
     * @param type $password
     * @param type $isTest
     * @return type
     */
    public function player_balance($loginname = NULL,$password = NULL,$isTest = NULL) {
        $result = $this->ag_getBalance($loginname, $password, $isTest);

        $balance = $result['info'];

        if (is_numeric($balance)) //余额支持两位小数
        {
            $result['code'] = 1;
            $result['balance'] = $balance;
            $this->_writeLog('INFO',__FUNCTION__,$loginname,$result);
        }
        else if (!empty($result['info'])) {
            $result['code'] = 'info:'.$result['info'];
            $this->_writeLog('ERROR',__FUNCTION__,$loginname,$result);
        }
        return $result;
    }


    /**
     * 玩家存款
     *
     * 如果确定转帐不成功，则回传code = -1.02 使事务回滚
     * 如果无法确定转帐结果，则回传code = -998 走人工处理流程
     * 转帐成功则回传 code = 1
     *
     * @param type $loginname 账号
     * @param type $password  密码
     * @param type $billno  商户自己的转账流水号（只能由数字、字母组成13-16位的长度）
     * @param type $credit  转账金额（最多保留两位小数）
     * @return type
     */
    public function player_deposit($loginname = NULL,$password = NULL,$billno = NULL,$credit = NULL,$isTest = NULL) {
        $type= 'IN';
        $result = $this->_player_transfer($type, $loginname, $password, $billno, $credit, $isTest);

        return $result;
    }

    /**
     * 玩家取款
     *
     * @param type $loginname 账号
     * @param type $password  密码
     * @param type $billno  商户自己的转账流水号（只能由数字、字母组成13-16位的长度）
     * @param type $credit  取款金额（最多保留两位小数）
     * @return type
     */
    public function player_withdraw($loginname = NULL,$password = NULL,$billno = NULL,$credit = NULL,$isTest = NULL) {
        $type= 'OUT';
        $result = $this->_player_transfer($type, $loginname, $password, $billno, $credit, $isTest);

        //旧线格式，比照办理
        $result['plat_trans_no'] = $billno;
        $result['trans_no'] = $billno;

        return $result;
    }

    /**
     * 转出转入-通用逻辑，只差在 type
     *
     * @param string $type
     * @param type $loginname
     * @param type $password
     * @param type $billno
     * @param type $credit
     * @param type $isTest
     * @return int
     */
    private function _player_transfer($type, $loginname = NULL,$password = NULL,$billno = NULL,$credit = NULL,$isTest = NULL) {
        $prepareResult = $this->ag_prepareTransferCredit($loginname, $password, $billno, $type, $credit, $isTest);

        $result = array('prepare' => $prepareResult);

        if ($prepareResult['info'] !== '0')
        {   //预备转帐未成功，使资料回滾( 参考weblib/aggame.php的player_deposit() )
            $result['code'] = '-1.02';
            $this->_writeLog('ERROR',__FUNCTION__,$loginname,$result);
        }
        else
        {   //预备转帐成功后，才进行确认转帐
            $confirmResult = $this->ag_transferCreditConfirm($loginname, $password, $billno, $type, $credit, 1, $isTest);

            $result['confirm'] = $confirmResult;

            if ($confirmResult['info'] !== '0')
            {   //确认转帐失败，先不回滚，待确认( 参考weblib/aggame.php的player_deposit() )
                $result['code'] = '-998';
                $this->_writeLog('ERROR',__FUNCTION__,$loginname,$result);
            }
            else //确认转帐成功
            {
                $result['code'] = 1;
                $this->_writeLog('INFO',__FUNCTION__,$loginname,$result);
            }
        }

        return $result;
    }


    /**
     * 检查转账记录状态
     * @param type $billno 商户自己的转账流水
     */
    public function player_checktransaction($billno = NULL,$isTest = NULL) {
        $result = $this->ag_queryOrderStatus($billno, $isTest);

        if ($result['info'] === '0')
        {
            $result['code'] = 1;
            $this->_writeLog('INFO',__FUNCTION__,$billno,$result);
        }
        else if (!empty($result['info'])) {
            $result['code'] = 'info:'.$result['info'];
            $this->_writeLog('ERROR',__FUNCTION__,$billno,$result);
        }
        return $result;
    }

    /**
     * 获取玩家注单数据 (查看应该是没用到，先不实做)
     * @param int  $maxid 从此游戏记录id(maxid)开始，取最新的500条数据。[不包括当前的游戏记录id(maxid)]
     */
    public function bet_historys($maxid = NULL,$bIsTest = NULL) {
        throw new Exception("not implement!!");
    }

    /**
     * 以上实做和library/aggame.php相同界面
     * ↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
     * ↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
     */


    /**
     * 建立或确认登入
     * @param type $loginname
     * @param type $password
     * @param type $isTest
     * @return type
     */
    public function ag_checkOrCreateGameAccount($loginname = NULL, $password = NULL, $isTest = FALSE) {
        $params = array();
        $params['method'] = 'lg';
        $params['actype'] = $isTest ? 0 : 1;

        return $this->_ag_doBusiness($params, $loginname, $password);
    }

    /**
     * 取得余额
     * @param type $loginname
     * @param type $password
     * @param type $isTest
     * @return type
     */
    public function ag_getBalance($loginname = NULL, $password = NULL, $isTest = FALSE) {
        $params = array();
        $params['method'] = 'gb';
        $params['actype'] = $isTest ? 0 : 1;

        return $this->_ag_doBusiness($params, $loginname, $password);
    }

    /**
     * 预备转帐
     * @param type $loginname
     * @param type $password
     * @param type $billno
     * @param type $type
     * @param type $credit
     * @param type $isTest
     * @return type
     */
    public function ag_prepareTransferCredit($loginname = NULL, $password = NULL, $billno, $type, $credit, $isTest = FALSE) {
        $params = array();
        $params['method'] = 'tc';
        $params['actype'] = $isTest ? 0 : 1;
        $params['billno'] = $billno;
        $params['type'] = $type;
        $params['credit'] = $credit;
        $params['cur'] = $this->cny;

        return $this->_ag_doBusiness($params, $loginname, $password);
    }

    /**
     * 确认转帐
     * @param type $loginname
     * @param type $password
     * @param type $billno
     * @param type $type
     * @param type $credit
     * @param type $flag
     * @param type $isTest
     * @return type
     */
    public function ag_transferCreditConfirm($loginname = NULL, $password = NULL, $billno, $type, $credit, $flag, $isTest = FALSE) {
        $params = array();
        $params['method'] = 'tcc';
        $params['actype'] = $isTest ? 0 : 1;
        $params['billno'] = $billno;
        $params['type'] = $type;
        $params['credit'] = $credit;
        $params['flag'] = $flag;

        return $this->_ag_doBusiness($params, $loginname, $password);
    }

    /**
     * 查询(转帐)订单状况
     * @param type $loginname
     * @param type $password
     * @param type $billno
     * @param type $isTest
     * @return type
     */
    public function ag_queryOrderStatus($billno, $isTest = FALSE) {
        $params = array();
        $params['method'] = 'qos';
        $params['actype'] = $isTest ? 0 : 1;
        $params['billno'] = $billno;

        return $this->_ag_doBusiness($params);
    }

    /**
     * 产生进入游戏网址
     * @param type $loginname
     * @param type $password
     * @param type $dm 返回的网站域名
     * @param type $sid 序列号，唯一(应该是session_id)
     * @param type $lang 语言代码
     * @param type $gametype 游戏代码
     * @param type $isTest
     * @param type $mh5 设置=y代表移动版，可选
     * @param type $flashid HB和PNG平台，可选
     * @param type $session_token 网站session_token，可选
     * @return string
     */
    public function ag_forwardGameUrl($loginname = NULL, $password = NULL, $dm, $sid, $lang, $isTest = FALSE, $gametype, $mh5, $flashid, $session_token) {
        $params = array();
        $params['cagent'] = $this->cagent;
        $params['cur'] = 'CNY';
        $params['loginname'] = $loginname;
        $params['password'] = $password;
        $params['dm'] = $dm;
        $params['sid'] = $sid;
        $params['actype'] = $isTest ? 0 : 1;
        $params['oddtype'] = 'A'; // 会员进入游戏默认限红
        if (!empty($lang))
            $params['lang'] = $lang;
        if (!empty($gametype))
            $params['gameType'] = $gametype;
        if (!empty($mh5))
            $params['mh5'] = $mh5;
        if (!empty($flashid))
            $params['flashid'] = $flashid;
        if (!empty($session_token))
            $params['session_token'] = $session_token;

        if (in_array($loginname,$this->testers))
            $params['actype'] = 0;

        $this->_writeLog('INFO',__FUNCTION__,$loginname,$params);

        $queryString = $this->_buildQueryString($params);

        $url = $this->game_url . '?' . $queryString;

        $this->_writeLog('INFO',__FUNCTION__,$loginname,$url,'url');

        return $url;
    }

    /**
     * 通用查询
     * @param type $params
     * @param type $loginname
     * @param type $password
     * @return type
     * @throws Exception
     */
    private function _ag_doBusiness($params = array(), $loginname, $password) {
        if (!is_array($params) || empty($params))
            throw new Exception("未带参数");

        if (!array_key_exists('method', $params))
            throw new Exception("参数不齐");

        //自动带入参数
        if (!array_key_exists('cagent', $params))
            $params['cagent'] = $this->cagent;

        if (!array_key_exists('cur', $params))
            $params['cur'] = 'CNY';

        if (in_array($loginname,$this->testers))
            $params['actype'] = 0;

        //帐号密码可选择不带入(for 订单查询)
        if (!empty($loginname))
            $params['loginname'] = $loginname;

        if (!empty($password))
            $params['password'] = $password;

        $logKey = !empty($loginname) ?  $loginname : $params['billno'];
        $this->_writeLog('INFO',__FUNCTION__,$logKey,$params);

        $queryString = $this->_buildQueryString($params);

        $url = $this->api_url . '?' . $queryString;

        $this->_writeLog('INFO',__FUNCTION__,$logKey,$url,'url');

        //curl post
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'WEB_LIB_GI_' . $this->cagent);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);

        $info = curl_getinfo($ch);

        curl_close($ch);

        $this->_writeLog('INFO',__FUNCTION__,$logKey,$info, 'response_info');
        $this->_writeLog('INFO',__FUNCTION__,$logKey,$result, 'response_content');

        $xmlobj = simplexml_load_string($result);

        $xmlarr = (array)$xmlobj;

        if (!array_key_exists('@attributes', $xmlarr)
            || !array_key_exists('info', $xmlarr['@attributes']))
        {
            $this->_writeLog('ERROR',__FUNCTION__,$logKey,'xml读取失败');
            return array('info'=> 'error', 'msg'=> 'xml读取失败');
        }

        $xmlContent = $xmlarr['@attributes'];
        $this->_writeLog('INFO',__FUNCTION__,$logKey,$xmlContent,'xmlContent');

        return $xmlContent;
    }

    /**
     * 组成query string
     * @param type $params
     * @return string
     */
    private function _buildQueryString($params = array()) {
        //组合
        $params_array = array();
        foreach ($params as $key => $value) {
            $params_array[] = $key . '=' . $value;
        }
        $params_string = join('/\\\/', $params_array);

        //加密
        $des = new agDES($this->des_key);
        $params_encrypted = $des->encrypt($params_string);
        $key_md5 = md5($params_encrypted . $this->md5_key);

        $queryString = 'params=' . $params_encrypted . '&key=' . $key_md5;

        return $queryString;
    }

    /**
     * 纪录日志
     * @param type $level  log 等级 INFO ERROR
     * @param type $function 函式名称
     * @param type $key      $loginname或$billno，用来标记log目标用户或订单
     * @param type $data     资料，会以json_encode形式储存
     * @param type $data_desc 资料格式/型态额外说明
     */
    private function _writeLog($level = "INFO", $function, $key, $data , $data_desc = NULL)
    {
        $now = time();
        $now_string = date('Y-m-d_H:i:s',time());
        $json_data = (!empty($data_desc) ?  "({$data_desc})=>" : '') . json_encode($data);
        $logArr = array($now_string, $this->guid, $level, $function, $key, $json_data );

        $logText = '[' . join('][',$logArr). ']' . "\r\n";
        $logFilePath = $this->logFolder .date('Y-m-d').'.log';

        error_log($logText,3, $logFilePath);
    }

    private function _guidv4()
    {
        if (function_exists('com_create_guid') === true)
            return trim(com_create_guid(),'{}');

        $data = openssl_random_pseudo_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s',str_split(bin2hex($data),4));
    }

    /**
     * 产生序列号
     * cagent + 序列，序列是唯一的13~16位数
     */
    private function _getUniNumber(){
        return $this->cagent . strval(microtime(true) * 10000) . strval(mt_rand(0,9)) . strval(mt_rand(0,9));
    }


    /**
     * 产出 AG 串接物件(新线或旧线）
     * @return \agproxy|\aggame
     */
    public static function getInterface()
    {
        if (($_SESSION['isAG2'] === TRUE) || getConfigValue('ag2_on') == 1) {
            return new agproxy(); //新线
        } else {
            return new aggame();  //旧线
        }
    }

    /**
     * 生产密码
     * @param $length
     * @return string
     */
    public function make_char($length){
        // 密码字符集，可任意添加你需要的字符

        $chars = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h',

            'i', 'j', 'k', 'l','m', 'n', 'o', 'p', 'q', 'r', 's',

            't', 'u', 'v', 'w', 'x', 'y','z', 'A', 'B', 'C', 'D',

            'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L','M', 'N', 'O',

            'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y','Z',

            '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
        // 在 $chars 中随机取 $length 个数组元素键名

        $char_txt = '';

        for($i = 0; $i < $length; $i++){

            // 将 $length 个数组元素连接成字符串

            $char_txt .= $chars[array_rand($chars)];

        }

        return $char_txt;
    }

    /**
     * 创建多级目录，方便保存日志
     * @param $dir
     * @return bool
     */
    public function create_folders($dir){
        return is_dir($dir) or ($this->create_folders(dirname($dir)) and mkdir($dir, 0777));
    }



}
