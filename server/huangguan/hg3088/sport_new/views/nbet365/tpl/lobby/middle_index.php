<?php
session_start();
include "../../../../app/member/include/config.inc.php";
$uid = $_SESSION['Oid']; // 判断是否已登录
$cpUrl = $_SESSION['LotteryUrl'];

?>
<style>
.footer{display: none;}
.focusBox { position: relative; width:850px; height:212px;overflow: hidden; }
.focusBox .pic{ position:relative; z-index:0;}
.focusBox .pic img { width:850px; height:212px; display: block; }
.focusBox .hd { width:100%; position: absolute; bottom: 10px; text-align: center; font-size:0; z-index:1; }
.focusBox .hd li{margin: 0 5px;  height: 14px; overflow: hidden; width: 14px; background: #fff; cursor: pointer;border-radius: 50%;
    display:inline-block; *display:inline; zoom:1;}
.focusBox .hd .on{ background-position:0 0; background: #eb5502; }
.focusBox .hd { display:none;}
.layui-layer-dialog{top: 35% !important;}
</style>

<div id="center">

    <div class="bet365wrapc" id="bet365wrapc">
        <div id="bet365mainleft">
            <div id="menuw">
                <ul>
                    <li><a href="#" onclick="alert('您的IP不支持该语言')">English</a></li>
                    <li><a href="#" onclick="alert('您的IP不支持该语言')">Español</a></li>
                    <li><a href="#" onclick="alert('您的IP不支持该语言')">Deutsch</a></li>
                    <li><a href="#" onclick="alert('您的IP不支持该语言')">Italiano</a></li>
                    <li><a href="#" onclick="alert('您的IP不支持该语言')">Português</a></li>
                    <li><a href="#" onclick="alert('您的IP不支持该语言')">Dansk</a></li>
                    <li><a href="#" onclick="alert('您的IP不支持该语言')">Svenska</a></li>
                    <li><a href="#" onclick="alert('您的IP不支持该语言')">Norsk</a></li>
                    <li><a href="#" class="cur">简体中文</a></li>
                    <li><a href="#" onclick="alert('您的IP不支持该语言')">繁體中文</a></li>
                    <li><a href="#" onclick="alert('您的IP不支持该语言')">Български</a></li>
                    <li><a href="#" onclick="alert('您的IP不支持该语言')">Ελληνικά</a></li>
                    <li><a href="#" onclick="alert('您的IP不支持该语言')">Polski</a></li>
                    <li><a href="#" onclick="alert('您的IP不支持该语言')">Română</a></li>
                    <li><a href="#" onclick="alert('您的IP不支持该语言')">Česky</a></li>
                    <li><a href="#" onclick="alert('您的IP不支持该语言')">Magyar</a></li>
                    <li><a href="#" onclick="alert('您的IP不支持该语言')">Slovenčina</a></li>

                </ul>
            </div>
        </div>

        <div id="bet365mainright">
            <!--right-->
            <div id="wrapmain">
                <div id="webmain"><div id="panela">

                        <div id="g01">
                            <div class="focusBox">
                                <ul class="pic">
                                    <li>
                                        <a href="javascript:;" class="to_sports" data-rtype="r" data-showtype="today">
                                            <img src="<?php echo TPL_NAME;?>images/index_sport.png">
                                        </a>
                                    </li>
                                </ul>
                                <ul class="hd">
                                    <li></li>

                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="clear"></div>
                    <div style=" margin-top:10px; background:#333333;">
                        <div class="panel_b" style=" margin-left:8px;">
                            <div class="g02">
                                <a href="javascript:;" class="to_lives"> <img src="<?php echo TPL_NAME;?>images/g02.png" alt=""></a>
                                <div class="line_01"></div>
                                <div class="p_cont">
                                    <h1>娱乐场</h1>
                                    <div class="line_02"></div>
                                    <span>1,000 RMB 奖金</span>
                                    <div class="line_02"></div>
                                    <p>超过200种精选游戏，包括最经典的现场荷官，精彩内容面向全部玩家。</p>
                                    <a href="javascript:;" class="to_lives btn_start"></a>
                                </div>
                            </div>
                        </div>

                        <div class="panel_b">
                            <div class="g04">
                                <a href="javascript:;" class="to_games"> <img src="<?php echo TPL_NAME;?>images/g04.png" alt=""> </a>
                                <div class="line_01"></div>
                                <div class="p_cont">
                                    <h1>电子</h1>
                                    <div class="line_02"></div>
                                    <span>1,000 RMB 奖金</span>
                                    <div class="line_02"></div>
                                    <p>上百款老虎机、电动扑克、大型电玩、桌上游戏、以丰富的视觉、声光效果提供您一级的娱乐。</p>
                                    <a href="javascript:;" class="to_games btn_start"></a>
                                </div>
                            </div>
                        </div>

                        <div class="panel_b">
                            <div class="g03">
                                <a href="javascript:;" class="to_lotterys"> <img src="<?php echo TPL_NAME;?>images/g03.png" alt=""> </a>
                                <div class="line_01"></div>
                                <div class="p_cont">
                                    <h1>彩票游戏</h1>
                                    <div class="line_02"></div>
                                    <span>1,000 RMB 奖金</span>
                                    <div class="line_02"></div>
                                    <p>最受全球华人喜欢的彩票游戏：时时彩，香港彩，双色球，福彩3D等在线投注方便快捷</p>
                                    <a href="javascript:;" class="btn_start playlotto to_lotterys"></a>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="clear"></div>
                </div>
            </div>
            <div id="page-footer">
                <div class="fo_01">
                    <div class="fo_01_l"> <img src="<?php echo TPL_NAME;?>images/worldcup.jpg" alt=""> </div>
                    <div class="fo_01_r">
                        <a href="javascript:;" class="to_aboutus" data-index="0">关于我们</a>&nbsp;&nbsp;|&nbsp;&nbsp;
                        <a href="javascript:;" class="to_aboutus" data-index="3">条款与规则</a>&nbsp;&nbsp;|&nbsp;&nbsp;
                        <a href="javascript:;" class="to_agentreg">申请合作</a>&nbsp;&nbsp;|&nbsp;&nbsp;
                        <a href="javascript:;" class="to_aboutus" data-index="5">帮助</a>&nbsp;&nbsp;|&nbsp;&nbsp;
                        <a href="javascript:;" class="to_aboutus" data-index="2">博彩责任</a>&nbsp;&nbsp;|&nbsp;&nbsp;
                        <a href="javascript:;" class="to_aboutus" data-index="4">银行</a>&nbsp;&nbsp;|&nbsp;&nbsp;
                        <a href="javascript:;" class="to_aboutus" data-index="1">联系我们</a>
                        <p> ©2001-<?php echo date('Y');?> bet365. All rights reserved | 18+ </p>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="fo_02">
                    <p>
                        通过进入、继续使用或浏览此网站，您即被认定接受：我们将使用特定的浏览器cookies优化您的客户享用体验。
                        bet365仅会使用优化您服务体验的cookies，而不是可侵犯您隐私的cookies。关于我们使用cookies，
                        以及您如何取消、管理cookies使用的更多详情，请参考我们的Cookies政策。
                        bet365是世界领先的网络博彩集团之一，提供体育投注、金融、娱乐场、扑克牌及游戏等丰富选择。
                    </p>
                    <p>
                        我们向客户提供全部体育范围内的丰富投注，内容涵盖足球、网球、篮球、棒球及乒乓球等。
                        每场欧冠联赛足球比赛都提供超过70个不同的滚球盘盘口，同时我们的100％欧洲精英足球奖金也适用于该赛事。
                        您可同时通过访问“移动中的bet365”，查看种类繁多的盘口和精彩赛事，其中包括我们的滚球盘服务。
                        如想体验该项服务，只需使用您的iPhone、iPad、Android或手机浏览器访问bet365。为增加滚球盘的兴奋感受，
                        我们还特别推出了现场链接，每年向您的电脑直播50,000多场精彩赛事。精选包括大师系列赛网球锦标赛和来自世界各地顶尖的国家级足球联赛。
                        如想查看最新的体育投注信息，请访问我们全新的投注新闻站点。
                    </p>
                    <p>
                        除了类别多样的体育投注之外，我们还提供丰富多种的精彩优惠。比如激动人心的欧洲精英足球奖金，
                        如果您在英超、意甲、西甲、德甲或欧洲冠军联赛上进行过关投注，即有机会获取最高可达您彩金100％比例的奖金。
                        另外，还有我们的零分平局退本大赠送优惠，如果您在赛前投注了"正确比分”、“半场/全场” 或者“首个得分球员及最终比分”这些盘口，
                        且假如比赛结果为0-0，我们将把输的投注取消，不惜退本大赠送!
                    </p>
                    <p>
                        为何不尝试我们惊喜不断的在线娱乐场？150多种精彩游戏任您选择，包括21点等各种游戏。如想进行轮盘或百家乐，
                        请立即访问现场荷官。而且，我们的扑克室是世界最大的在线扑克网络，您可在此挑战数千名现金比赛玩家或参加在线大型锦标赛事。
                    </p>
                    <p>
                        bet365体育投注与金融投注，由英国博彩委员会颁发执照及进行监管。娱乐场、游戏与扑克牌由直布罗陀政府颁发执照及进行监管。
                    </p>
                </div>
            </div>
        </div>
        <!--footer-->
        <div class="clear"></div>

        <!--right end -->
    </div>


</div>

<script type="text/javascript">
    $(function () {



    })



</script>
