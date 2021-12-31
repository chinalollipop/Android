<?php
/**
 * 优惠活动管理
 * Date: 2019/8/2
 * Time: 14:02
 */
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

require_once("../include/config.inc.php");
include_once("../include/address.mem.php");
include_once ("../include/redis.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$dayNow = date('Y-m-d');
$uid = $_REQUEST["uid"];
$langx = $_SESSION["langx"];
$loginname = $_SESSION['UserName'];
$title = isset($_REQUEST['title'])?$_REQUEST['title']:'' ; // 关键字


?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <title>优惠活动审核</title>
    <style>
        .list-tab td{line-height: 20px;}
        .list-tab input{ float: left;}
        input.za_text_auto {width: 100px;}
    </style>
</head>
<body>
<form name="myform" action="" method=post>
    <dl class="main-nav"><dt>优惠活动审核</dt>
        <dd>
            <table >
                <tr class="m_tline">
                    <td>
                        <input type="text" value="<?php echo $dayNow;?>" onclick="laydate({istime: true,istoday: false, format: 'YYYY-MM-DD'})" class="startTime za_text_auto" readonly>
                        至
                        <input type="text" value="<?php echo $dayNow;?>" onclick="laydate({istime: true,istoday: false, format: 'YYYY-MM-DD'})" class="endTime za_text_auto" readonly>
                    </td>
                    <td>&nbsp;&nbsp;
                        共<span class="totalNumber"> 0 </span>条
                        <select class="selectPage za_select za_select_auto" >
                            <option value="0">1</option>
                        </select>
                        共<span class="totalPage"> 1 </span>页
                    </td>
                    <td >&nbsp;&nbsp;&nbsp;
                        审核状态：
                        <select class="categoryStatus za_select za_select_auto" name="categoryStatus" >
                            <option value="">全部</option>
                            <option value="1">已派发</option>
                            <option value="2">未审核</option>
                            <option value="4">拒绝</option>
                        </select>
                        活动类别：
                        <select class="categoryType za_select za_select_auto" name="categoryType" >
                            <option value="">请选择类型</option>
                        </select>
                        &nbsp;&nbsp;&nbsp;搜索关键字：
                        <input type="text" name="title" value="<?php echo $title?>" class="seachTxt za_text" size="15" placeholder="请输入关键字">
                        <input type="button" value="查询" class="dlg_ok za_button">
                    </td>
                </tr>
            </table>
        </dd>
    </dl>
</FORM>
<div class="main-ui promo_table_all">
    <table class="m_tab">
        <thead>
            <tr class="m_title" >
                <td >序号</td>
                <td >用户资料</td>
                <td >活动名称 </td>
                <td >存款金额</td>
                <td >存款天数</td>
                <td >有效投注</td>
                <td >负盈利</td>
                <td >统计游戏</td>
                <td >游戏种类</td>
                <td >领取彩金</td>
                <td >申请时间</td>
                <td >审核时间</td>
                <td >审核人</td>
                <td >状态</td>
                <td >操作<br>
                    <a href="javascript:;" class="checkPromoBtn_all checkPromoBtn blue" data-status="1">一键派发当前页</a><br><br>
                    <!--<a href="javascript:;" class="checkPromoBtn_all checkPromoBtn blue" data-status="4">一键拒绝当前页</a>-->
                </td>
            </tr>
        </thead>
        <tbody class="promoList">

        </tbody>
        <tfoot>
            <tr>
                <td colspan="8"> 当前页面彩金总计：<span class="cur_total"> 0 </span> </td>
                <td colspan="8"> 所有页面彩金总计：<span class="all_total"> 0 </span> </td>
            </tr>
        </tfoot>
    </table>
</div>

<script type="text/javascript" src="../../../js/agents/jquery.js"></script>
<script type="text/javascript" src="../../../js/agents/layer/layer.js"></script>
<script type="text/javascript" src="../../../js/agents/register/laydate.min.js"></script>
<script type="text/javascript" src="/js/agents/common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript">

    var parFirst = {action :'get',seachType:'first'};
    var $categoryType = $('.categoryType');
    var $promoList = $('.promoList');
    var $selectPage = $('.selectPage');
    var $categoryStatus = $('.categoryStatus');
    var $totalNumber = $('.totalNumber');
    var $totalPage = $('.totalPage');
    var $cur_total = $('.cur_total');
    var $all_total = $('.all_total');
    var $dlg_ok = $('.dlg_ok');

    getPromosCheckApi(parFirst);
    checkPromos();
    seachMoreAction();
    /*
   * 接口
   * act:set 设置信息，get 获取信息
   * */
    function getPromosCheckApi(params) {
        var loading = layer.load(0, { // 加载层
            shade: [0.5,'#000'],
            time:1000
        });
        var url = '/api/promoCheckApi.php';
        $.ajax({
            type: 'POST',
            url:url,
            data:params,
            dataType:'json',
            success:function(res){
                var type_str = '<option value="">请选择类型</option>';
                var str = '';
                var page_str = '';
                if(res){ // 有结果返回

                    if(params.action=='get'){ // 查询
                        if(res.status !='200'){
                            layer.msg(res.describe,{time:alertComTime,shade: [0.2, '#000']});
                        }
                        if(res.data){
                            $totalNumber.html(res.data.total);
                            $totalPage.html(res.data.page_count);

                            $cur_total.html(res.data.curTotal);
                            $all_total.html(res.data.allTotal);

                            for(var ii=0;ii<res.data.page_count;ii++){
                                page_str += '<option value="'+ii+'" '+(params.page==ii?'selected':'')+'>'+(ii+1)+'</option>';
                            }
                            $selectPage.html(page_str);// 页码

                            var type_res = res.data.promoType;
                            var list_res = res.data.promoList;
                            var no_check_arr =[]; // 未审核的 ID
                            if(type_res !=''){
                                for(var i=0;i<type_res.length;i++){
                                    type_str += '<option value="'+ type_res[i].flag +'">'+ type_res[i].title +'</option>';
                                }
                                $categoryType.html(type_str);
                            }
                            if(list_res){
                                for(var j=0;j<list_res.length;j++){
                                    str += '<tr>' +
                                                '<td>'+ (j+1) +'</td>'+
                                                '<td align="left">账号：'+ list_res[j].UserName +
                                                '<br>姓名：'+ list_res[j].Alias +
                                                '<br>手机：'+ list_res[j].Phone +
                                                '<br>银行：'+ list_res[j].bankAccount +
                                                '<br>申请IP：'+ list_res[j].applyIp +'</td>'+
                                                '<td>'+ list_res[j].eventName +'</td>'+
                                                '<td>'+ list_res[j].deposit +'</td>'+
                                                '<td>'+ list_res[j].depositDay +'</td>'+
                                                '<td>'+ list_res[j].totalBet +'</td>'+
                                                '<td>'+ list_res[j].profitable +'</td>'+
                                                '<td>'+ returnGameType(list_res[j].gameType) +'</td>'+
                                                '<td>'+ list_res[j].gameTypeDetails +'</td>'+
                                                '<td>'+ list_res[j].promoGold +'</td>'+
                                                '<td>'+ list_res[j].add_time +'</td>'+
                                                '<td>'+ list_res[j].review_time +'</td>'+
                                                '<td>'+ list_res[j].review_name +'</td>'+
                                                '<td>'+ returnStaus(list_res[j].status) +'</td>'+
                                                '<td>'+ (list_res[j].status==2?'<a href="javascript:;" class="checkPromoBtn blue" data-id="'+list_res[j].ID+'" data-status="1">派发</a>&nbsp;&nbsp; <a href="javascript:;" class="checkPromoBtn blue" data-id="'+list_res[j].ID+'" data-status="4">拒绝</a>':'') +'</td>'+
                                            '</tr>';
                                    if(list_res[j].status==2){ // 未审核
                                        no_check_arr.push(list_res[j].ID);
                                    }

                                }
                                $('.checkPromoBtn_all').attr('data-id',no_check_arr.join(','));
                            }else{ // 没有数据
                                str = '<tr> <td colspan="20"> 暂无数据</td> </tr>';
                            }
                            $promoList.html(str);

                        }

                    }

                    if(params.action=='check'){ // 派发
                        layer.msg(res.describe,{time:alertComTime,shade: [0.2, '#000']});
                        if(res.status=='200'){ // 派发成功
                            $dlg_ok.click();// 触发查询
                        }

                    }

                }

            },
            error:function(){
                layer.msg('网络错误，请稍后重试',{time:alertComTime,shade: [0.2, '#000']});
            }
        });
    }
    // 查询
    function seachMoreAction() {
        $dlg_ok.off().on('click',function () {
            var starttime = $('.startTime').val();
            var endtime = $('.endTime').val();
            var page = $selectPage.val();
            var pro_status = $categoryStatus.val(); // 状态
            var type = $categoryType.val();
            var txt = $('.seachTxt').val();
            var data ={
                action:'get',
                startTime:starttime,
                endTime:endtime,
                page:page,
                status:pro_status,
                type:type,
                seachTxt:txt,
            };
            getPromosCheckApi(data);
        })
    }

    // 派发奖金
    function checkPromos() {
        $('.promo_table_all').off().on('click','.checkPromoBtn',function () {
            var curId = $(this).attr('data-id');
            var checkstatus = $(this).attr('data-status');
            var ttile = $(this).text();
            if(!curId){
                layer.msg('没有需要操作的ID',{time:alertComTime,shade: [0.2, '#000']});
                return;
            }
            var dataParams = {
                action:'check',
                curId:curId,
                checkstatus:checkstatus
            };
            //console.log(curId);
            layer.confirm('确认'+ttile+'彩金吗？', {
                title:'提示',
                btn: ['确定','取消'], //按钮
                yes: function(index, layero){
                    getPromosCheckApi(dataParams);
                    layer.close(index);
                    //按钮【按钮一】的回调
                },
                cancel: function(){
                    falg = false;
                    //右上角关闭回调
                },
            });

        })
    }
    
    function returnGameType(type) {
        var str;
        switch (type){
            case 'all':
                str = '全部';
            break;
            case 'sport':
                str = '体育';
                break;
            case 'lottery':
                str = '彩票';
                break;
            case 'live':
                str = '视讯';
                break;
            case 'chess':
                str = '棋牌';
                break;
            case 'game':
                str = '电子';
                break;
            case 'bygame':
                str = '捕鱼';
                break;
            case 'avia':
                str = '泛亚电竞';
                break;
        }
        return str;
    }

    function returnStaus(type) {
        var str;
        switch (type){
            case '1':
                str = '已派发';
                break;
            case '2':
                str = '未审核';
                break;
            case '3':
                str = '不符合';
                break;
            case '4':
                str = '<span class="red">已拒绝</span>';
                break;
        }
        return str;
    }

</script>
</body>
</html>
