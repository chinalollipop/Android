<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");
include_once ("../../agents/include/address.mem.php");
include_once ("../../agents/include/config.inc.php");
include_once ("../../agents/include/define_function_list.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

if( (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG) && $_SESSION['admin_level']!='D') {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

?>
<html>
<head>
    <title>代理报表-代理</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="../../../style/agents/control_main.css?v=<?php echo AUTOVER; ?>" type="text/css">
    <style type="text/css">
        a{ color: #00f;}
        .main-ui{ width: 1000px; }
        .td1{width: 80px;}
    </style>
</head>
<body>
<dl class="main-nav">
    <dt>代理报表-代理</dt>
    <dd>
        <div class="header_info">
            <?php
            $date_end = isset($current_end_day)?$current_end_day:$_REQUEST['date_end'];
            echo "日期：".$_REQUEST['date_start'].' ~ '.$date_end."-- 报表分类：总账 -- 投注方式：全部 --下注管道：网络下注 --";

            ?>
            <a href="javascript:history.back(-1);" >回上一页</a>
        </div>
    </dd>
</dl>
<div class="main-ui">
    <table class="m_tab">
        <tr>
            <td class="td2">代理</td>
            <td>笔数</td>
            <td>下注金额</td>
            <td>实际投注</td>
            <td>盈利</td>
            <td>代理商结果</td>
            <td>代理商交收</td>
            <td>总代理交收</td>
            <td>股东交收</td>
        </tr>
        <tr class="data_total"></tr>
    </table>
    <br>


    <table class="agents_m_tab m_tab">
        <tr>
            <td class="td2">代理商</td>
            <td>笔数</td>
            <td>下注金额</td>
            <td>实际投注</td>
            <td>盈利</td>
            <td>代理商结果</td>
            <td>代理商交收</td>
            <td>总代理交收</td>
            <td>股东交收</td>
        </tr>

    </table>
</div>
</body>
<script type="text/javascript" src="../../../js/agents/jquery.js"></script>
<script type="text/javascript" src="../../../js/agents/layer/layer.js"></script>
<script>
    getReportTop() ;
    function getReportTop(){
        var action = 'agent_report_top_agents';
        var dateStart =  '<?php echo $_REQUEST['date_start'];?>' ;
        var dateEnd =  '<?php echo $_REQUEST['date_end'];?>' ;
        $.ajax({
            type : 'POST',
            url : '/app/agents/report_new/api/reportApi.php' ,
            data : {
                action: action,
                date_start: dateStart,
                date_end: dateEnd
            },
            dataType : 'json',
            success:function(res) {
                // console.log(res)
                var str = '';
                var str_agents ='';
                if(res){
                    str +=
                        '            <td class="td2">'+res.subtotal.UserName+'</td>' +
                        '            <td>'+res.subtotal.count_pay+'</td>' +
                        '            <td>'+res.subtotal.total+'</td>' +
                        '            <td>'+res.subtotal.valid_money+'</td>' +
                        '            <td>'+res.subtotal.user_win+'</td>' +
                        '            <td>'+res.subtotal.user_win+'</td>' +
                        '            <td>'+res.subtotal.user_win+'</td>' +
                        '            <td>'+res.subtotal.user_win+'</td>' +
                        '            <td>'+res.subtotal.user_win+'</td>';
                    $('.data_total').append(str);
                }
                if(res.agents.length>0){
                    for(var i=0;i<res.agents.length;i++){
                        str_agents +='  <tr>' +
                            '            <td class="td2"><a href="agent_report_top_users.php?action=agent_report_top_users&date_start='+dateStart+'&date_end='+dateEnd+'&agent='+ res.agents[i].UserName +'">'+ res.agents[i].UserName +'</a></td>' +
                            '            <td>'+ res.agents[i].count_pay +'</td>' +
                            '            <td>'+ res.agents[i].total  +'</td>' +
                            '            <td>'+ res.agents[i].valid_money  +'</td>' +
                            '            <td>'+ res.agents[i].user_win  +'</td>' +
                            '            <td>'+ res.agents[i].user_win  +'</td>' +
                            '            <td>'+ res.agents[i].user_win  +'</td>' +
                            '            <td>'+ res.agents[i].user_win  +'</td>' +
                            '            <td>'+ res.agents[i].user_win  +'</td>' +
                            '        </tr>';
                    }
                    $('.agents_m_tab tbody').append(str_agents);

                }
            },
            error:function(){
                layer.msg('请求汇总报表异常',{time:2000});
            }
        });
    }

</script>
</html>
