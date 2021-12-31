<?php
session_start();

require_once("ag/config.php");

class Dbnew{
    private static $_instance_m = null;	//该类中的唯一一个实例
    private static $_instance_s = null;	//该类中的唯一一个实例
    private function __construct($type){	//防止在外部实例化该类
        GLOBAL $database;
        if($type == "master"){
            self::$_instance_m = @mysqli_connect($database['gameDefault']['host'],$database['gameDefault']['user'],$database['gameDefault']['password'],$database['gameDefault']['dbname'],$database['gameDefault']['port']) or die("mysqli connect error".mysqli_connect_error()) ;
            mysqli_query(self::$_instance_m,"SET NAMES 'utf8'");
            mysqli_query(self::$_instance_m,"SET CHARACTER_SET_CLIENT=utf8");
            mysqli_query(self::$_instance_m,"SET CHARACTER_SET_RESULTS=utf8");
            mysqli_query(self::$_instance_m,"SET time_zone = '-04:00'");
        }elseif($type=="slave"){
            $slaveNo = rand(1,count($database['gameSlave']));
            self::$_instance_s = @mysqli_connect($database['gameSlave'][$slaveNo]['host'],$database['gameSlave'][$slaveNo]['user'],$database['gameSlave'][$slaveNo]['password'],$database['gameSlave'][$slaveNo]['dbname'],$database['gameSlave'][$slaveNo]['port']) or die("mysqli connect error".mysqli_connect_error()) ;
            mysqli_query(self::$_instance_s,"SET NAMES 'utf8'");
            mysqli_query(self::$_instance_s,"SET CHARACTER_SET_CLIENT=utf8");
            mysqli_query(self::$_instance_s,"SET CHARACTER_SET_RESULTS=utf8");
            mysqli_query(self::$_instance_s,"SET time_zone = '-04:00'");
        }
    }

    private function __clone(){}		//禁止通过复制的方式实例化该类

    public static function getInstance($type,$flag=''){
        if($type=='master'){
            if( self::$_instance_m == null )	new self('master');
            return self::$_instance_m;
        }elseif($type=='slave'){
            if( self::$_instance_s == null )	new self('slave');
            return self::$_instance_s;
        }
    }

    private function __destruct(){

    }
}

$dbLink  = Dbnew::getInstance('slave');
$dbMasterLink  = Dbnew::getInstance('master');
