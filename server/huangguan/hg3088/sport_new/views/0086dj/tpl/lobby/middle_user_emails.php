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

<link rel="stylesheet" type="text/css" href="<?php echo TPL_NAME;?>style/memberaccount.css?v=<?php echo AUTOVER; ?>" >

<div class="msg-box">
    <header>
        <div class="first-row">
            <div class="first-row-left">
                <span></span>
                <p>消息中心</p>
            </div>
            <div class="button-group">
               <!-- <div class="notifications">
                    <button class="button-left">
                        优惠通知
                        <span class="message-red-dot hidden"></span>
                    </button>
                </div>-->
                <div class="announcement">
                    <button class="button-left active" data-type="notice"> <!-- button-middle -->
                        网站公告
                        <!--<span class="message-red-dot msg-dot-padding-left">1</span>-->
                    </button>
                </div>
                <div class="inbox">
                    <button class="button-right" data-type="message">
                        站内信
                        <span class="for_email_mount message-red-dot msg-dot-padding-left"> </span>
                    </button>
                </div>
            </div>
        </div>
        <!--<div class="second-row">
            <div class="action-button-wrapper">
                <div class="select-all-wrapper">
                    <input id="msg-box__select-all" class="default-checkbox" type="checkbox" name="select-all">
                    <div id="allCheck" class="custom-checkbox">
                        <div class="custom-checkbox-checkmark hidden"></div>
                    </div>
                    <label class="custom-checkbox-label">全选</label>
                </div>
                <div class="second-row__button-group">
                    <button class="mark-as-read-button">标记已读</button>
                    <button class="delete-button">删除</button>
                </div>
            </div>
            <div class="message-content-breadcrumb"></div>
        </div>-->
    </header>

    <main>
        <div class="message-list">
          <!--  <div class="message" >
                 <input class="default-checkbox" type="checkbox">
              <div class="custom-checkbox message-custom-checkbox checked">
                    <div class="custom-checkbox-checkmark hidden"></div>
                    <span class="custom-checkbox-red-dot--hidden"></span>
                </div>
                <div class="message-content" onclick="window.location.href = 'emaildes.html'">
                    <div><h3 class="isRead-true">赛事公告</h3>
                        <p class="time isRead-true">2019/05/03 12:38</p></div>
                    <p class="isRead-true message-content-without-thumbnail">足球赛事:05月03日 俄罗斯乙组联赛（穆罗姆 VS
                        皮斯科维）因盘口错误，所有投注在11:34:16至11:36:35的注单被标记为(盘口错误）的一律取消！过关以 (1) 计算！</p>
                    <button onclick="window.location.href='emaildes.html'" class="message-content__read-more">查看详情</button>
                </div>
            </div>-->

          <!--  <div class="message" ><input class="default-checkbox" type="checkbox">
                <div class="custom-checkbox message-custom-checkbox">
                    <div class="custom-checkbox-checkmark hidden"></div>
                    <span class="custom-checkbox-red-dot"></span></div>
                <div class="message-content" onclick="window.location.href = 'emaildes.html'">
                    <div><h3 class="isRead-false">赛事公告</h3>
                        <p class="time isRead-false">2019/05/02 23:21</p></div>
                    <p class="isRead-false message-content-without-thumbnail">篮球赛事:05月02日 阿根廷全国篮球联赛（BBC奥林匹科 VS
                        基尔梅斯尼）因盘口错误，所有投注在21:17:15至22:13:49的注单被标记为(盘口错误）的一律取消！过关以 (1) 计算！</p>
                    <button onclick="window.location.href='emaildes.html'" class="message-content__read-more">查看详情</button>
                </div>
            </div>-->

        </div>

    </main>
    <footer>
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
    </footer>

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