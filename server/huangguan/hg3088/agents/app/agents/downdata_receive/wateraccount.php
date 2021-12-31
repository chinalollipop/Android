<?php
/**
 * APP请求接口：
 *
 *  1、增加一行刷水账号, 返回记录信息
 *  2、更新刷水账号
 *  3、删除刷水账号
 *
 */

require("../include/config.inc.php");
require_once("../../../../common/sportCenterData.php");
require("../include/curl_http.php");
require_once("../include/address.mem.php");

/*判断IP是否在白名单*/
//if(CHECK_IP_SWITCH) {
//    if(!checkip()) {
//        exit('登录失败!!\\n未被授权访问的IP!!');
//    }
//}

$action = $_REQUEST['action'];

switch ($action){

    case 'viewAcc':
        $sql = "select * from ".DATAHGPREFIX."web_getdata_account_expand";
        $result = mysqli_query($dbCenterMasterDbLink,$sql);
        while($resultFetch = mysqli_fetch_assoc($result)){
            $exitAccountArr[] = $resultFetch;
        }

        if (count($exitAccountArr)==0){
            $response['status'] = 400;
            $response['message'] = '请添加刷水账号';
            exit(json_encode($response));

        }else{
            $response['status'] = 200;
            $response['message'] = '刷水账号列表';
            $response['data'] = $exitAccountArr;
            exit(json_encode( $response));

        }

        break;
    case 'addAcc':

        $typeEx = $_REQUEST['typeEx']?$_REQUEST['typeEx']:'zh-cn';
        $urlEx = $_REQUEST['urlEx'];
        $nameEx = $_REQUEST['nameEx'];
        $passwdEx = $_REQUEST['passwdEx'];
        $uidEx = $_REQUEST['uidEx'];
        $curDate = date('y-m-d h:i:s',time());
        $source = 13;
        $cookie = $_REQUEST['cookie'];
        $ver = $_REQUEST['ver'];
        $status = $_REQUEST['status']; // 0 正常 1 异常

        $sql="REPLACE INTO ".DATAHGPREFIX."web_getdata_account_expand(`Type`,`Datasite`,`Uid`,`Name`,`Passwd`,`status`,`datetime`,`source`,`cookie`,`Ver`) VALUES('$typeEx','$urlEx','$uidEx','$nameEx','$passwdEx','$status','$curDate','$source','$cookie','$ver')";
        $res = mysqli_query($dbCenterMasterDbLink,$sql);
        $id= mysqli_insert_id($dbCenterMasterDbLink);
        if($res){

            $response['status'] = 200;
            $response['message'] = '刷水账号添加成功';
            $response['data'] = array(
                'id' => "$id",
                'typeEx' => $_REQUEST['typeEx'],
                'urlEx' => $_REQUEST['urlEx'],
                'nameEx' => $_REQUEST['nameEx'],
                'passwdEx' => $_REQUEST['passwdEx'],
                'uidEx' => $_REQUEST['uidEx'],
                'datetime' => $curDate,
                'source' => $source,
                'cookie' => $cookie,
                'ver' => $ver,
                'status' => $status,
            );
            exit(json_encode( $response));

        }else{

            $response['status'] = 500;
            $response['message'] = '添加失败';
            exit(json_encode( $response));

        }

        break;
    case 'edtAcc':

        $id = $_REQUEST['id'];
        $typeEx = $_REQUEST['typeEx']?$_REQUEST['typeEx']:'zh-cn';
        $urlEx = $_REQUEST['urlEx'];
        $nameEx = $_REQUEST['nameEx'];
        $passwdEx = $_REQUEST['passwdEx'];
        $uidEx = $_REQUEST['uidEx'];
        $datetime = date('y-m-d h:i:s',time());
        $source = 13;
        $cookie = $_REQUEST['cookie'];
        $ver = $_REQUEST['ver'];
        $status = $_REQUEST['status']; // 0 正常 1 异常

        $sql1 = "update ".DATAHGPREFIX."web_getdata_account_expand set 
        `datetime`='".$datetime."',Datasite='".$urlEx."',Name='".$nameEx."',Passwd='".$passwdEx."',uid='$uidEx',status='$status',source='$source',cookie='$cookie',Ver='$ver' where ID=".$id;
//        echo $sql1; die;
        $res1 = mysqli_query($dbCenterMasterDbLink,$sql1);
        if($res1){

            $response['status'] = 200;
            $response['message'] = '刷水账号更新成功';
            $response['data'] = array(
                'id' => "$id",
                'typeEx' => $_REQUEST['typeEx'],
                'urlEx' => $_REQUEST['urlEx'],
                'nameEx' => $_REQUEST['nameEx'],
                'passwdEx' => $_REQUEST['passwdEx'],
                'uidEx' => $_REQUEST['uidEx'],
                'datetime' => $datetime, //$curDate
                'source' => $source,
                'cookie' => $cookie,
                'ver' => $ver,
                'status' => $status,
            );
            exit(json_encode( $response));

        }else{

            $response['status'] = 500;
            $response['message'] = '更新失败';
            exit(json_encode( $response));

        }

        break;
    case 'delAcc':
        $sql="delete from ".DATAHGPREFIX."web_getdata_account_expand where ID=".$_REQUEST['id'];
        $res = mysqli_query($dbCenterMasterDbLink,$sql);
        if($res){
            $response['status'] = 200;
            $response['message'] = '刷水账号'.$_REQUEST['id'].'删除成功';
            exit(json_encode( $response));
        }else{
            $response['status'] = 500;
            $response['message'] = '删除失败';
            exit(json_encode( $response));
        }
        break;


}
















