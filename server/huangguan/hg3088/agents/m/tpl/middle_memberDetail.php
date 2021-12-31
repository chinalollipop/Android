<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Content-type: text/html; charset=utf-8");

require ("../../app/agents/include/config.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆
if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}
$uid = $_SESSION['Oid'];
$Alias = $_SESSION['Alias'];
$navtype = isset($_REQUEST['type'])?$_REQUEST['type']:'';
$navtitle = isset($_REQUEST['navtitle'])?$_REQUEST['navtitle']:'';

$enable =isset($_REQUEST['enable'])?trim($_REQUEST['enable']):'';// 状态
$sort =isset($_REQUEST['sort'])?trim($_REQUEST['sort']):'';// 类型
$orderby =isset($_REQUEST['orderby'])?trim($_REQUEST['orderby']):'';// 排序
$sea_text =isset($_REQUEST['sea_text'])?trim($_REQUEST['sea_text']):'' ; // 关键字查询
$winlosstext =isset($_REQUEST['winlosstext'])?trim($_REQUEST['winlosstext']):'' ; // 输赢额度大小查询


?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <META name="keywords" content="<?php echo COMPANY_NAME;?>,<?php echo COMPANY_NAME;?>登入,<?php echo COMPANY_NAME;?>平台">
    <meta name="HandheldFriendly" content="true"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="shortcut icon" href="/images/favicon_<?php echo TPL_FILE_NAME;?>.ico" type="image/x-icon"/>
    <link href="../css/main.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css" media="screen"/>

    <title> <?php echo COMPANY_NAME;?> </title>
    <style type="text/css">
        .nav_top{height:3rem;line-height:3rem;background:#f1f6fb;color:#4e525e}
        .nav_top span,.list span{width:18%}
        .nav_top span:nth-last-child(2), .list span:nth-last-child(2) {width: 13%;}
        .nav_top span:first-child,.nav_top span:last-child,.list span:first-child,.list span:last-child{width:8%}
        .list span{font-size:.9rem}
        .memberList{color:#4e525e}
        .memberList .list{height:3rem;justify-content: center;align-items: center;border-bottom:1px solid #f1f1f1;}
        .list_more {display:none;line-height: 3rem;color: #acacac;justify-content: center;}
    </style>
</head>
<body>
<div class="contentAll flex">
    <?php include 'middle_header.php'; ?>

    <div class="nav_top flex">
        <span > 序号 </span>
        <span > 姓名 </span>
        <span > 账号 </span>
        <span > 输赢额度 </span>
        <span > 新增日期 </span>
        <span > 停启用 </span>
        <span > 状态 </span>
    </div>
    <div class="contentCenterAll ">
        <div class="memberList">

        </div>
        <a class="list_more flex get_more_action" data-page="1">加载更多数据</a>
    </div>

    <?php include 'middle_footer.php'; ?>

</div>

<script type="text/javascript" src="/js/agents/jquery.js"></script>
<script type="text/javascript" src="/js/agents/layer/layer.js"></script>
<script type="text/javascript" src="/js/agents/common.js?v=<?php echo AUTOVER; ?>"></script>
<script type="text/javascript">
    $(function () {
        var $get_more_action = $('.get_more_action') ;
        var xl_num = 1; // 序列号
        settingHeight('.contentAll');
        getMemberDetail();
        getMoreData();

        // 获取会员信息
        var submitflag = false ; // 防止重复提交
        function getMemberDetail(Page,more) {
            if(submitflag){
                return false ;
            }
            if(more){ // 加载更多数据
                var curpage = Number($get_more_action.attr('data-page')) ; // 当前页面
            }
            if(!Page){Page=0;}
            var $memberList = $('.memberList');
            var page= Page;
            var enable= '<?php echo $enable;?>';
            var sort= '<?php echo $sort;?>';
            var orderby= '<?php echo $orderby;?>';
            var sea_text= '<?php echo $sea_text;?>';
            var winlosstext= '<?php echo $winlosstext;?>';
            submitflag = true ;
            var url = '/api/seachMemberApi.php';
            var dataParams = {
                page:page,
                enable:enable,
                sort:sort,
                orderby:orderby,
                sea_text:sea_text,
                winlosstext:winlosstext
            };
            $.ajax({
                type: 'POST',
                url:url,
                data:dataParams,
                dataType:'json',
                success:function(res){ // Status :0 启用,1 冻结, 2 停用,Online : 1 在线 其他离线
                    var str = '';
                    if(res){ // 有结果返回
                        $get_more_action.attr('data-count', res.data.page_count); // 总页数
                        submitflag = false ;
                        if(res.data.total==0){ // 没有数据
                            str += '<div class="list flex">暂无数据</div>';
                            $get_more_action.hide();
                        }else{
                            for(var i=0;i<res.data.rows.length;i++){
                                str +='<div class="list flex">' +
                                    '<span > '+ xl_num +' </span>' +
                                    '<span > '+ res.data.rows[i].Alias +' </span>' +
                                    '<span > '+ res.data.rows[i].UserName +' </span>' +
                                    '<span > '+ parseInt(res.data.rows[i].WinLossCredit) +' </span>' +
                                    '<span > '+ res.data.rows[i].AddDate.substr(0,10) +' </span>' +
                                    '<span class="'+ (res.data.rows[i].Status=='0'?'':'red') +'"> '+ (res.data.rows[i].Status=='0'?'启用':(res.data.rows[i].Status=='1'?'冻结':'停用')) +' </span>' +
                                    '<span class="'+ (res.data.rows[i].Online=='1'?'red':'') +'"> '+ (res.data.rows[i].Online=='1'?'在线':'离线') +' </span>'+
                                    '</div>';
                                xl_num++;
                            }
                        }
                        $memberList.append(str);

                        if(res.data.page_count>1){
                            $get_more_action.show().html('加载更多数据') ;
                        }else{
                            $get_more_action.hide() ;
                        }
                        if(more) { // 加载更多数据
                            curpage++ ;
                            $get_more_action.attr('data-page',curpage) ;
                            if(curpage == res.data.page_count){
                                $get_more_action.html('没有更多数据了') ;
                            }
                        }

                    }

                },
                error:function(){
                    submitflag = false ;
                    layer.msg('网络错误，请稍后重试',{time:alertComTime,shade: [0.2, '#000']});
                }
            });
        }

        // 加载更多数据
        function getMoreData() {
            $get_more_action.on('click',function () {
                $get_more_action.html('加载中...') ;
                var curpage = $(this).attr('data-page') ;
                var allcount = $(this).attr('data-count') ; // 总页数

                if(curpage<1 || curpage >= allcount){ // 没有数据
                    $get_more_action.html('没有更多数据了') ;
                    return false ;
                }
                getMemberDetail(curpage,'more');
            }) ;

        }

    })

</script>
</body>
</html>
