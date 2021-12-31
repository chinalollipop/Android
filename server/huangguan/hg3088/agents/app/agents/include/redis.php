<?php
/**
 * CodeIgniter Redis
 *
 * A CodeIgniter library to interact with Redis
 *
 * @package         CodeIgniter
 * @category        Libraries
 * @author          Joël Cox
 * @version         v0.4
 * @link            https://github.com/joelcox/codeigniter-redis
 * @link            http://joelcox.nl
 * @license         http://www.opensource.org/licenses/mit-license.html
 */
class Ciredis {

    private $conn;
    private $blsConnect = FALSE;

    /**
     * Constructor
     */
    public function __construct($params = array("master"))
    {
        GLOBAL $redis;

        //未使用哨兵机制
        if (!empty($params) && isset($redis[$params[0]]))
        {
            if($params[0] == "slave") {
                //随机得到一台redis缓存数据库
                $num = count($redis['slave'])-1;
                $randId = rand(0, $num);
                $config = $redis[$params[0]][$randId];
            }else {
                $config = $redis["master"];
            }
        }elseif($params == "datacenter") {
            $config = $redis["datacenter"];
        }else{
            $config = $redis["master"];
        }

        $this->conn = phpiredis_connect($config['host'], $config['port']);
        if($this->conn == FALSE) {
            show_error('Could not connect to Redis at ' . $config['host'] . ':' . $config['port']);
        }else {
            if($params == "datacenter"){
                if(!empty($config['password']) && $config['password'] != "") {
                    phpiredis_command_bs($this->conn, array("auth", (string)$config['password']));
                }
            }else{
                if(!empty($redis['password']) && $redis['password'] != "") {
                    phpiredis_command_bs($this->conn, array("auth", (string)$redis['password']));
                }
            }
        }
        $this->blsConnect = TRUE;
    }

    public function __destruct() {
        if($this->blsConnect == TRUE) {
            phpiredis_disconnect($this->conn);
        }
    }

    public function getRedis($iDb) {
        phpiredis_command_bs($this->conn, array("SELECT", (string)$iDb));
    }

    public function setHash($key, $value) {
        $hash = array();
        foreach($value as $vk => $vv) {
            $hash[] = array(
                "HSET",
                $key,
                $vk,
                $vv
            );
        }
        return phpiredis_multi_command_bs($this->conn, $hash);
    }

    public function getHashByKeyFail($key, $fields) {
        if(is_scalar($fields)) {
            $fields = array($fields);
        }
        $hashValue = array("HMGET", $key);
        foreach($fields as $field) {
            array_push($hashValue, $field);
        }
        return phpiredis_multi_command_bs($this->conn, array($hashValue));
    }

    public function getHashAllByKey($key) {
        return phpiredis_multi_command_bs($this->conn, array(
            array(
                "HGETALL",
                $key,
            )
        ));
    }


    function pushMessage($sName, $mValue, $iExpire = 0, $iFlag = 0) {
        return phpiredis_multi_command_bs($this->conn, array(
            array(
                "LPUSH",
                $sName,
                $mValue
            )
        ));
    }

    function popMessage($sName) {
        return phpiredis_multi_command_bs($this->conn, array(
            array(
                "RPOP",
                $sName
            )
        ));
    }

    function lenMessage($sName) {
        return phpiredis_multi_command_bs($this->conn, array(
            array(
                "Llen",
                $sName
            )
        ));
    }

    function insert($sName, $mValue, $iExpire = 0, $iFlag = 0) {
        $value = is_array($mValue)?serialize($mValue):$mValue;
        return phpiredis_multi_command_bs($this->conn, array(
            array(
                "SET",
                $sName,
                $value
            ),
            array(
                "EXPIRE",
                $sName,
                (string)$iExpire
            )
        ));
    }

    function insertOfTag($tags, $key, $value, $timeout) {
        $value = is_array($value)?serialize($value):$value;
        $paramsArr = array(array("SET", $key, $value), array("EXPIRE", $key, (string)$timeout), array("SADD", $tag, $key));
        if(!is_array($tags)) {
            $tags = explode(',', $tags);
        }
        $tags = array_unique(array_filter($tags));
        foreach($tags as $tag) {
            array_push($paramsArr, array('SADD', $tag, $key));
        }

        return phpiredis_multi_command_bs($this->conn, $paramsArr);
    }

    public function watch($key) {
        return phpiredis_command_bs($this->conn, array("WATCH", $key));
    }

    public function multi() {
        return phpiredis_command_bs($this->conn, array("MULTI"));
    }

    public function exec() {
        return phpiredis_command_bs($this->conn, array("EXEC"));
    }

    public function setOne($key, $value) {
        return phpiredis_command_bs($this->conn, array("SET", $key, $value));
    }

    public function getSET($key, $value) {
        return phpiredis_command_bs($this->conn, array("GETSET", $key, $value));
    }

    //独占锁
    public function setnxOne($key, $value) {
        return phpiredis_command_bs($this->conn, array("SETNX", $key, $value));
    }

    function getOne($sName, $iFlag = 0) {
        $res = phpiredis_command_bs($this->conn, array(
            "GET",
            $sName
        ));
        return unserialize($res);
    }

    function getSimpleOne($sName, $iFlag = 0) {
        $res = phpiredis_command_bs($this->conn, array(
            "GET",
            $sName
        ));

        return $res;
    }

    function getAll($aNames, $aFlags = array()) {
        array_unshift($aNames, "MGET");
        $res = phpiredis_command_bs($this->conn, array(
            $sNames
        ));
        return $res;
    }

    function getKeyByTag($tag) {
        return phpiredis_multi_command_bs($this->conn, array(array("SMEMBERS", $tag)));
    }

    function update($sName, $mValue, $iExpire = 0, $iFlag = 0) {
        $value = is_array($mValue)?serialize($mValue):$mValue;
        return phpiredis_multi_command_bs($this->conn, array(
            array(
                "SET",
                $sName,
                $value
            ),
            array(
                "EXPIRE",
                $sName,
                (string)$iExpire
            )
        ));
    }

    function delete($sName, $iTimeOut = 0) {
        return phpiredis_command_bs($this->conn, array(
            "DEL",
            $sName
        ));
    }

    public function deleteByPre($pre) {
        if(empty($pre)) {
            return false;
        }
        $delArr = array();
        $keys = phpiredis_command_bs($this->conn, array("KEYS", $pre.'*'));
        if(!empty($keys)) {
            foreach($keys as $key) {
                if(!empty($key)) {
                    $delArr[] = array("DEL", $key);
                }
            }
            return phpiredis_multi_command_bs($this->conn, $delArr);
        }
    }

    public function deleteOfTag($tag) {
        if($keysArr = $this->getKeyByTag($tag)) {
            $keysStr = implode(" ", $keysArr[0]);
            $this->delete($keysStr);
            return $this->delete($tag);
        }
    }

    public function deleteAll() {
        //
    }

	//$aCommand需要是数组的格式，例如: array("flushall");
    public function execute($aCommand) {
        return phpiredis_command_bs($this->conn, $aCommand);
    }

    public function execute_multi($aCommand) {
        return phpiredis_multi_command_bs($this->conn, $aCommand);
    }

    //redis对集合的操作方法： 添加集合里面的变量
    public function sadd($key, $value) {
        //像set中插入value，成功插入返回1，插入set中已有的value则失败且返回0
        return phpiredis_command_bs($this->conn, array("SADD", $key, $value));
    }

    //redis对集合的操作方法： 获取集合里面的所有变量
    public function smembers($key) {
        //查看set中的元素
        return phpiredis_command_bs($this->conn, array("SMEMBERS", $key));
    }

    //redis对集合的操作方法： 删除集合里面的变量
    public function srem($key, $value) {
        //删除set中对应的value,删除成功返回1，若不存在则返回
        return phpiredis_command_bs($this->conn, array("SREM", $key, $value));
    }
}
?>