<?php
session_start();
$companyName = $_SESSION['COMPANY_NAME_SESSION'];
$uid = $_SESSION['Oid'];

?>

<style>
    .sg_mainBody{background:#eee;padding:20px 0;color:#333}
    p{color:#666;font-size:14px}
    .feedback_txt_box,.feedback_from_box{background:#fff;border-radius:7px}
    .feedback_from_box{margin-top:10px}
    .feedback_txt,.feedback_from{width:1120px;padding:20px 0;margin:auto}
    .feedback_txt p{line-height:25px;font-size:15px}
    .feedback_from_title{color:#333;font-size:18px;font-weight:700}
    .feedback_from_title span:last-child{color:#a5a5a5;font-weight:normal}
    .xt{display:inline-block;height:40px;width:5px;background:red;vertical-align:-13px;margin-right:15px}
    .feedback_from_choose{padding:0 20px}
    .feedback_from_choose_fl{padding:30px 0}
    .feedback_from_choose_fl > div{float:left}
    .feedback_num{font-size:16px;width:90px;text-align:right}
    .feedback_from_input input{width:250px;height:30px;border:1px solid #aaa}
    .feedback_from_btn{cursor:pointer;height:45px;background:#ff9a03;width:160px;color:#fff;line-height:45px;text-align:center;font-size:16px;border-radius:7px;margin-left:133px;margin-top:30px;margin-bottom:50px;transition: .3s}
    .feedback_from_btn:hover{opacity: .9;}
    .feedback_select select{cursor:pointer;padding:10px;width:250px;font-size:14px;color:#333}
</style>
<div class="sg_mainBody">
    <div class="w_1200">
        <div class="feedback_txt_box">
            <div class="feedback_txt">
                <p>
                    您好！感谢您对<?php echo $companyName;?>的关注与支持
                </p>
                <p>
                    如果您对我们的工作和服务有任何意见，建议，请填写一下内容进行反馈，您的反馈对我们非常重要。为了使您的反馈得到及时回复和处理，
                </p>
                <p>
                    请您务必完整填写以下信息。谢谢！
                </p>
            </div>
        </div>
        <div class="feedback_from_box">
            <div class="feedback_from">
                <div class="feedback_from_title">
                    <div style="margin-bottom: 20px">
                        <span class="xt"></span>投诉建议 <span>Comments/Complaints</span>
                    </div>
                    <div style="border-bottom: 3px solid #ddd;"></div>
                </div>
                <div class="feedback_from_choose">
                    <div class="feedback_from_choose_fl">
                        <div class="feedback_num" style="   margin-top: 3px;"><i>*</i>反馈类别：</div>
                        <div class="feedback_select">
                            <select name="cars" id="cars">
                                <option value="dianzi">体育赛事</option>
                                <option value="live">视讯直播</option>
                                <option value="dianzi">电子游艺</option>
                                <option value="caipiao">彩票游戏</option>
                                <option value="qipai">棋牌游戏</option>
                                <option value="buyu">捕鱼</option>
                                <option value="dianjing">电子竞技</option>
                                <option value="youhui">优惠活动</option>
                                <option value="appdown">APP下载</option>
                                <option value="jianyi">建议/投诉</option>
                            </select>
                        </div>
                    </div>
                    <div style="clear: both"></div>
                    <div class="feedback_from_choose_fl">
                        <div class="feedback_num"><i>*</i>投诉内容：</div>
                        <div class="feedback_from_textarea">
                            <textarea name="" id="content" cols="100" rows="15" placeholder="请填写投诉内容"></textarea>
                        </div>
                    </div>
                    <!--<div style="clear: both"></div>
                    <div class="feedback_from_choose_fl">
                        <div class="feedback_num"><i>*</i>会员号：</div>
                        <div class="feedback_from_input"><input type="text"></div>
                    </div>-->
                    <div style="clear: both"></div>
                    <div class="feedback_from_choose_fl">
                        <div class="feedback_num"><i>*</i>联系方式：</div>
                        <div class="feedback_from_input"><input id="phoneemail" type="text"></div>
                    </div>
                    <div style="clear: both"></div>

                    <div class="feedback_from_btn" onclick="jianyi_submit()">提交反馈</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function jianyi_submit() {
        var ajaxurl='app/member/api/opinionComplaint.php';
        var senddata={
            phone_email: $('#phoneemail').val(),
            content: $('#content').val(),
            category: $('#cars option:selected').val()
        };
        $.ajax({
            url:  ajaxurl ,
            type: 'POST',
            dataType: 'json',
            data: senddata ,
            success:function(ret){
                // console.log(ret);
                if(ret.status=='200'){ // 登录成功
                    layer.msg(ret.describe,{time:alertTime});
                    indexCommonObj.to_index;

                }else {
                    layer.msg(ret.describe,{time:alertTime});
                }
            },
            error: function (XMLHttpRequest, status) {
                layer.msg('网络错误，稍后请重试',{time:alertTime});
            }
        });

    }
</script>