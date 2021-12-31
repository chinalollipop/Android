<?php
session_start();

include "../../../../app/member/include/config.inc.php";
$uid = $_SESSION['Oid'];
if( !isset($uid) || $uid == "" ) {
    echo "<script>window.location.href='/'</script>";
    exit;
}
$username=$_SESSION['UserName'];
$onlinetime=$_SESSION['OnlineTime'];
$Alias=$_SESSION['Alias'];
$birthday=$_SESSION['birthday'];

//  单页面维护功能


?>

<link rel="stylesheet" type="text/css" href="<?php echo TPL_NAME;?>/style/memberaccount.css?v=<?php echo AUTOVER; ?>" >
<style>
    .msg-box li{float:initial}
    .msg-box .first-row{display:flex;justify-content:space-between;align-content:center;position:relative;width:100%;height:42px;background:#fff;margin-bottom:10px;border-radius:10px;box-shadow:0px 2px 10px 0px rgba(0,0,0,.1)}
    .msg-box .first-row-left > p{color:#609eea;display:inline-block;position:absolute;left:10px;font-size:20px;line-height:42px}
    .msg-box .button-group{display:flex;border-bottom:1px solid #eaeff1}
    .msg-box .button-group button{width:80px;height:35px;padding-bottom:15px;font-size:16px;border:0;background:transparent;}
    .msg-box .button-group .active{color:#609eea;border-bottom:2px solid #609eea}
    .msg-box .message-red-dot{display:none;position:absolute;font-size:12px;right:10px;top:0;background-color:#f74e4e;color:white;width:12px;height:12px;border-radius:50%;line-height:1}
    .msg-box .msg-dot-padding-left{padding-left:0.5px}
    .msg-box .second-row button{background:#d8dcdd;border-radius:20px;border:none;margin:0;padding:0;color:#666666;font-size:14px;line-height:14px}
    .msg-box .default-checkbox{display:none}
    .msg-box .custom-checkbox{position:relative;border:1px solid #979797;width:18px;height:18px;border-radius:3px}
    .msg-box .message-custom-checkbox{position:relative;border:1px solid #979797;width:18px;height:18px;border-radius:3px;top:20px}
    .msg-box .custom-checkbox:hover{cursor:pointer}
    .msg-box .custom-checkbox.checked{background:#ff9421;border-color:#ff9421}
    .msg-box .custom-checkbox.checked::after{border-color:white;border-style:solid;border-width:0 2px 2px 0;width:5px;height:9px;transform:rotate(45deg);position:absolute;top:1px;left:5px;content:''}
    .msg-box .custom-checkbox .custom-checkbox-red-dot{display:block;height:10px;width:10px;background-color:red;border-radius:50%;position:absolute;right:-5px;top:-5px}
    .msg-box .custom-checkbox .custom-checkbox-red-dot--hidden{display:none}
    .msg-box .custom-checkbox-checkmark{border-color:white;border-style:solid;border-width:0 2px 2px 0;width:5px;height:9px;transform:rotate(45deg);position:absolute;top:2px;left:6px}
    .msg-box .custom-checkbox-label{position:relative;margin:0;left:14px;user-select:none}
    .msg-box .custom-checkbox-label:hover{cursor:pointer}
    .msg-box .message{display:flex;border-bottom:1px dashed #eaeff1;height:150px;padding:0 16px 0 20px}
   /* .msg-box .message:nth-child(2n){background: #F8F8F8;}*/
    .msg-box .message-image{flex:1;background:grey;width:548px;height:130px;margin:0 11px 0 7px;border-radius:2px;background-color:#d8d8d8;top:9px;position:relative;cursor:pointer}
    .msg-box .message-image--hide{display:none}
    .msg-box .message-content{flex:1;margin:10px 0 10px 13px;display:flex;flex-direction:column;justify-content:space-around;}
    .msg-box .message-content > div{display:flex;justify-content:space-between;align-items:center}
    .msg-box .message-content > p{overflow:hidden;text-overflow:ellipsis;/*display:-webkit-box;*/-webkit-box-orient:vertical;-webkit-line-clamp:2;margin:15px 0 ;width:512px;font-size:14px;font-weight:normal;line-height:15px;text-align:left;color:#333;word-break:break-all;min-height:31px;}
    .msg-box .message-content > p.message-content-without-thumbnail{width:660px}
    .msg-box .message-content .isRead-true{color:rgba(119,119,119,0.5)}
    .msg-box .message-content button{color:#ff9421;background:none;border:none;text-align:left;padding:0;width:max-content;outline:none;overflow:visible}
    .message-content__read-more{width:48px;line-height:12px;font-size:12px;margin-bottom:13px}
    .msg-box h3{line-height:22px;font-size:16px;color:#1a2226;margin:0;font-weight:normal;word-break:break-all}
    .msg-box .time{margin:0 0 0 10px;line-height:14px;font-size:14px;color:#999999;min-width:118px}
    .msg-box .time.isRead-true{margin:0 0 0 10px;line-height:14px;font-size:14px;color:#5e5e5e}
    .msg-box .pagination{display:flex;justify-content:flex-end;align-items:center;margin:14px 16px;color:#777}
    .msg-box .pagination > .page-box{margin:0 5px;line-height:30px;text-align:center;width:30px;height:30px;border:1px solid #dddddd;border-radius:3px;background-color:#fff;color:#d6d6d6}
    .msg-box .pagination > .page-box:hover{cursor:pointer}
    .msg-box .pagination .page-number--active{background:#ff9421;color:white}
    .msg-box .pagination .box-section{display:flex;justify-content:flex-end;align-items:center;color:#777}
    .msg-box .pagination .box-section > .page-box{width:30px;height:30px;border:1px solid #ddd;border-radius:3px;margin:0 5px;line-height:30px;text-align:center;font-size:14px;color:#333333}
    .msg-box .pagination .box-section > .page-box:hover{cursor:pointer}
    .msg-box .pagination .box-section .page-number--active{background:#5ea0ea;border-color:#5ea0ea;line-height:30px;font-size:14px;color:#ffffff}
    .msg-box .pagination .ellipsis{border:none;color:#dddddd;letter-spacing:3px;padding-bottom:8px;padding-left:3px}
    .msg-box .pagination .ellipsis:hover{cursor:default}
    .msg-box .pagination > input{width:54px;height:30px;border:1px solid #ddd;border-radius:3px;padding:0;margin:0 5px;text-align:center;font-size:14px;line-height:11px;}
    .msg-box .pagination .page-text{margin-left:6px;line-height:13px;font-size:14px;color:#666666}

</style>
<div class="memberWrap msg-box">
    <header>
        <div class="first-row">
            <div class="first-row-left">
                <p>消息中心</p>
            </div>

        </div>
    </header>

    <main class="payWay">
        <div class="button-group">
            <div class="announcement">
                <button class="button-left active" data-type="notice"> <!-- button-middle -->
                    网站公告
                </button>
            </div>
            <div class="inbox">
                <button class="button-right" data-type="message">
                    站内公告
                    <span class="dis_for_email_mount message-red-dot msg-dot-padding-left"> </span>
                </button>
            </div>
        </div>
        <div class="message-list">

        </div>

        <div class="pagination">
            <div class="page-box">&lt;</div>
            <div class="left-section box-section">
                <div class="page-box page-number page-number--active" data-page-number="1">1
                </div>
            </div>
            <span class="first-ellipsis ellipsis" style="display: none;">...</span>
            <div class="middle-section box-section"></div>
            <span class="second-ellipsis ellipsis" style="display: none;">...</span>
            <div class="right-section box-section"></div>
            <div class="page-box">&gt;</div>
            <input type="text" value="1">
            <span class="page-text">页</span>
        </div>

    </main>

</div>


<script type="text/javascript">


    $(function () {

        var msg_type = 'notice' ; // 默认公告

        // 切换信息
        function changeNavTags() {
            $('.button-group').on('click','button',function () {
                var type = $(this).attr('data-type');
                msg_type = type;
                $(this).addClass('active').parent('div').siblings().find('button').removeClass('active') ;
                getUserEmalis(type) ;
            })
        }

        function getUserEmalis(type) {
            var $messagelist = $('.message-list') ;

            var url = '/app/member/api/userEmailsApi.php?v='+Math.random() ;
            $.ajax({
                type: 'POST',
                url: url,
                data: {action:type},
                dataType: 'json',
                success: function (res) {
                    if(res){
                        if(res.status != 200){ // 登录已过期
                            window.location.href = '/' ;
                            return ;
                        }
                        var str = '' ;
                        var title = '赛事公告';
                        for(var i=0;i<res.data.length;i++){
                            switch (res.data[i].type){
                                case 0:
                                    title = '财务公告';
                                    break;
                            }
                            if(res.data[i].notice){
                                str +=' <div class="message" >' +
                                    '                <div class="message-content" >' +
                                    '                    <div><h3 class="isRead-false">'+ title +'</h3>' +
                                    '                        <p class="time isRead-true">'+ res.data[i].created_time +'</p></div>' +
                                    '                    <p class="isRead-false message-content-without-thumbnail">'+ res.data[i].notice +'</p>' +
                                    // '                    <button  class="message-content__read-more">查看详情</button>' +
                                    '                </div>' +
                                    '            </div>';
                            }

                        }

                        $messagelist.html(str) ;
                    }
                },
                error: function (res) {
                    layer.msg('获取数据失败，请稍后再试!',{time:alertTime});

                }
            });
        }
        indexCommonObj.getUserMessage();
        getUserEmalis(msg_type) ; // 默认公告
        changeNavTags() ;

        getMessageInt = setInterval(function () { // 每1分钟后请求数据
            getUserEmalis(msg_type) ;
        },10000)

    })
</script>