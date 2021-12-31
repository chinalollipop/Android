<?php
session_start();
include "../../../../app/member/include/config.inc.php";
$tplNmaeSession = $_SESSION['TPL_NAME_SESSION'];

$key = isset($_REQUEST['key'])?$_REQUEST['key']:'';
$companyName = $_SESSION['COMPANY_NAME_SESSION'];

?>

<style>
.faqWrapper .faqQuestion{-webkit-tap-highlight-color: rgba(0, 0, 0, 0); padding: 0px 0px 8px 15px; margin: 0px; text-decoration: none; line-height: 20px; border-bottom-width: 1px !important; border-bottom-style: solid !important; border-bottom-color: rgb(220, 220, 220) !important; background-image: none !important; background-attachment: scroll !important; background-color: rgb(250, 250, 250) !important; display: block; color: rgb(42, 140, 102); font-size: 14px; box-sizing: border-box; background-position: 0px 0px !important; background-repeat: repeat repeat !important;}

</style>

<div id="new-banner">
    <div id="new-banner-box">
        <div id="banner"><img src="<?php echo TPL_NAME;?>images/live/6.jpg"></div>
        <div class="msg-connet">

            <div class="left" style="margin-lefT:8px;">
                <div><a href="javascript:;" class="to_lives ylc_top"></a></div>
                <div> <a href="javascript:;" class="to_lives ylc_left"></a>
                    <a href="javascript:;" class="to_lives ylc_right"></a> </div>
            </div>

        </div>
    </div>
</div>

<div id="sidebarwrap">
    <div id="sidebarbox">
        <div id="leftsidebar">
            <ul>
                <li class="bbin"><a href="javascript:;" class="to_lives cur">BBIN娱乐</a></li>
                <li class="mg"><a href="javascript:;" class="to_lives">AG娱乐</a></li>
                <li class="sports"><a href="javascript:;" class="to_sports" data-rtype="r" data-showtype="today">体育投注</a></li>
                <li class="lot"><a href="javascript:;" class="to_lotterys">彩票游戏</a></li>
                <li class="ele"><a href="javascript:;" class="to_games">电子游艺</a></li>
            </ul>
            <div id="ads1"><a href="javascript:;" class="to_promos"></a></div>
            <div id="ads2"><a href="javascript:;" class="to_promos"></a></div>
        </div>

        <!-- 右侧 -->
        <div id="rightsidebar" class="textWrap">
            <div class="mainnav textBox">
                <h1><span>关于我们</span></h1>
                <div id="middle" class="description">
                    <div class="content"><p><span style="font-size:14px"><span style="color:#FFFF00"><strong>谁是bet365?</strong></span></span><br>
                        </p><p>bet365是世界领先的网络博彩集团之一，在200个不同的国家，拥有1,400多万客户。集团员工总数超过2,000名，是英国最大的私营公司之一。<br>
                            &nbsp;</p>

                        <p><strong><span style="color:#FFFF00"><span style="font-size:14px">bet365 - 值得您信赖的公司&nbsp;</span></span></strong><br>
                            我们的体育投注与金融投注产品由英国博彩委员会颁发执照并监管。我们的娱乐场、游戏、维加斯和扑克牌方面的运营由直布罗陀政府颁发执照并监管。<br>
                            &nbsp;</p>

                        <p><span style="font-size:14px"><span style="color:#FFFF00"><strong>bet365一户通系统</strong></span></span>&nbsp;<br>
                            除体育投注外，我们还在线提供一流的娱乐场、游戏、维加斯及扑克牌室。bet365采用方便快捷的一户通系统，即您可使用相同的用户名、密码及支付方式，畅快体验上述所有的精彩产品。<br>
                            &nbsp;</p>

                        <p><strong><span style="font-size:14px"><span style="color:#FFFF00">IBAS</span></span></strong><br>
                            为使您放心，bet365体育投注加入了IBAS（独立博彩仲裁服务机构），并同意遵守IBAS的裁決（如果您对任何争议的解决方案不满意）。<br>
                            &nbsp;</p>

                        <p><strong><span style="font-size:14px"><span style="color:#FFFF00">网络安全</span></span></strong><br>
                            bet365拥有Thawte SSL网络服务器证书，即来往于本网站上的所有数据均将被加密后传送。<br>
                            &nbsp;</p>

                        <p><strong><span style="font-size:14px"><span style="color:#FFFF00">客戶服务及更多信息 &nbsp;</span></span> </strong>&nbsp; &nbsp;<br>
                            我们本着客户至上的原则，致力于更好地完善各项客户服务。我们坚信本公司快捷、有效及友好的服务必能为您解答一切疑问。</p>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
            <div class="mainnav textBox hide">
                <h1><span>联系我们</span></h1>
                <div id="middle" class="description">
                    <div class="content">
                        <div class="innerPageWrapper contactUsPage" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0.0980392); padding: 15px 0px 0px; margin: 0px; float: left; width: 882px;">
                            <div class="telContactColumn" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0.0980392); padding: 0px 0px 30px; margin: 0px; width: 293.984375px; float: left; min-height: 500px;">
                                <div class="contactItem" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0.0980392); padding: 20px 10px 0px; margin: 0px 0px 10px; font-size: 15px; width: 293.984375px; float: left; box-sizing: border-box;">
                                    <div class="contactUsContainer" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0.0980392); padding: 0px 0px 0px 10px; margin: 0px; float: left; width: 191.78125px;"><strong>电话咨询</strong>
                                        <div class="telNumberBlock" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0.0980392); padding: 0px 0px 14px; margin: 0px; width: 191.78125px; float: left;"><span style="color:rgb(125, 125, 125); font-size:13px">我们的客户服务人员将通过电话为您提供24小时全天服务。请您拨打以下电话联系我们：</span></div>

                                        <div class="telNumberBlockWrapper" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0.0980392); padding: 10px 0px 0px; margin: 0px; width: 191.78125px; float: left;">
                                            <div class="telNumberBlock" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0.0980392); padding: 0px 0px 14px; margin: 0px; width: 191.78125px; float: left;">
                                                <p><strong><span style="font-size:15px">联系</span>电话</strong></p>

                                                <p><span style="color:rgb(125, 125, 125)" class="ess_service_phone"></span></p>

                                                <p><strong>投诉电话</strong></p>

                                                <p><span style="color:rgb(125, 125, 125)" class="phl_service_phone"></span></p>
                                            </div>
                                        </div>

                                        <div class="telNumberBlockWrapper" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0.0980392); padding: 10px 0px 0px; margin: 0px; width: 191.78125px; float: left;">&nbsp;</div>
                                    </div>
                                </div>
                            </div>

                            <div class="contactColumn" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0.0980392); padding: 0px; margin: 0px; float: left; width: 582.109375px; box-sizing: border-box;">
                                <div class="contactItem postItem" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0.0980392); padding: 20px 10px; margin: 0px; font-size: 15px; width: 291.046875px; float: left; box-sizing: border-box;">
                                    <div class="contactUsContainer" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0.0980392); padding: 0px 0px 0px 10px; margin: 0px; float: left; width: 189.71875px;"><strong>邮件</strong>

                                        <div class="contactUsBlurb postalBlurb" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0.0980392); padding: 0px; margin: 0px; width: 189.71875px; font-size: 13px; line-height: 19px; color: rgb(125, 125, 125); box-sizing: border-box;">
                                            <p><span style="color:rgb(125, 125, 125); font-size:13px">24小时全天服务</span></p>

                                            <p><a class="whiteButton" href="mailto:support-sch@customerservices365.com" style="color: rgb(6, 133, 95); -webkit-tap-highlight-color: rgba(0, 0, 0, 0.0980392); padding: 0px; margin: 0px; text-decoration: none; background-image: none; background-attachment: scroll; height: 24px; display: inline !important; background-position: 0px 0px; background-repeat: repeat repeat;"><span style="font-size:12px">发送电子邮件</span></a></p>

                                            <p><span style="font-size:12px" class="sz_service_email"></span></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="contactItem chatItem displayNoneOnPhone" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0.0980392); padding: 20px 10px; margin: 0px; font-size: 15px; width: 291.046875px; float: left; box-sizing: border-box; border-right-width: 0px; border-right-style: solid; border-right-color: rgb(235, 235, 235);">
                                    <div class="contactUsContainer" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0.0980392); padding: 0px 0px 0px 10px; margin: 0px; float: left; width: 189.71875px;">
                                        <p><strong>在线咨询</strong></p>

                                        <p><span style="color:rgb(125, 125, 125); font-size:13px">24小时全天候咨询客户服务人员</span>
                                            <a class="whiteButton to_livechat" style="font-size: 12px; line-height: 1.6em; -webkit-tap-highlight-color: rgba(0, 0, 0, 0.0980392); padding: 0px; margin: 0px; text-decoration: none; color: rgb(6, 133, 95); background-image: none; background-attachment: scroll; height: 24px; display: inline !important; background-position: 0px 0px; background-repeat: repeat repeat;">
                                                <span style="color:#FFA500">点击打开在线咨询</span></a></p>
                                    </div>
                                </div>
                            </div>

                            <div class="contactColumn" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0.0980392); padding: 0px; margin: 0px; float: left; width: 582.109375px; box-sizing: border-box; border-bottom-width: 0px; border-bottom-style: solid; border-bottom-color: rgb(235, 235, 235);">
                                <div class="contactItem emailItem" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0.0980392); padding: 20px 10px; margin: 0px; font-size: 15px; width: 291.046875px; float: left; box-sizing: border-box; color: rgb(84, 84, 84); font-family: Verdana, Geneva, sans-serif; line-height: normal;">
                                    <div class="contactUsContainer" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0.0980392); padding: 0px 0px 0px 10px; margin: 0px; float: left; width: 189.71875px;">
                                        <p>&nbsp;</p>

                                        <div class="telNumberBlockWrapper" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0.0980392); padding: 10px 0px 0px; margin: 0px; width: 189.71875px; float: left;">&nbsp;</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <p>&nbsp;</p>
                    </div>
                </div>
            </div>
            <div class="mainnav textBox hide">
                <h1><span>博彩责任</span></h1>
                <div id="middle" class="description">
                    <div class="content"><p style="text-align:justify"><span style="color:#FFFF00">我们为客户在博彩自律方面提供多方面的帮助，其中包括：</span><br>
                            - 在线存款限额【停止使用】如需使用，请联系在线客服进行限制？<a class="to_livechat"><span style="color:#FFFF00">在线客服</span></a></p>

                        <p style="text-align:justify">- 暂时关闭帐户【停止使用】如需使用，请联系在线客服进行关闭？<a class="to_livechat"><span style="color:#FFFF00">在线客服</span></a></p>

                        <p style="text-align:justify">- 关于控制投注的信息&nbsp;</p>

                        <p style="text-align:justify">- 问题性博彩问卷调查&nbsp;</p>

                        <p style="text-align:justify">- 家长控制<br>
                            &nbsp;</p>

                        <p style="text-align:justify"><span style="color:#FFFF00"><span style="font-size:14px"><strong>未满博彩年龄&nbsp;</strong></span></span><br>
                            未满18岁的人士进行博彩属非法行为，bet365会在此方面绝对严格执行。我们会对客户进行年龄验证，<br>
                            并可能要求其上交补充文件。对任何未满18岁人士使用此网站所获得的彩金，一旦发现，将被没收并上报有关部门处理。</p>

                        <p style="text-align:justify">&nbsp;</p>

                        <p style="text-align:justify"><span style="font-size:14px"><span style="color:#FFFF00"><strong>雇员培训</strong></span></span>&nbsp;<br>
                            我们所有的客户服务人员在问题性博彩方面都经过严格的培训。<br>
                            &nbsp;</p>

                        <p style="text-align:justify"><strong><span style="font-size:14px"><span style="color:#FFFF00">在线存款限额</span></span></strong><br>
                            此功能可帮助您控制每24小时或每168小时（7日）的在线存款限额。</p>

                        <p style="text-align:justify">您可以随时减少此限额，若要提高限额则需要24小时的生效时间。我们的客户服务小组很乐意为您提供帮助，
                            但我们不能更改您设定的限制。<br>登陆您的帐户之后，您可以访问“服务”区，在“会员” - “会员中心” - “信息管理” - “限额设定”中设定或修改您的存款限额。【停止使用】</p>

                        <p style="text-align:justify">&nbsp;</p>

                        <p style="text-align:justify"><strong><span style="font-size:14px"><span style="color:#FFFF00">查看账户历史</span></span></strong>&nbsp;<br>
                            您可随时查看账户的交易历史以及存取款记录。账户余额通常可以在“会员中心” - “资金流水” - “选择时间” - “翻页选择”部分中查看，同时您也可以在登录账户时在页面右上角查看。</p>

                        <p style="text-align:justify"><strong><span style="font-size:14px"><span style="color:#FFFF00">自我禁止或选择退出</span></span></strong><br>
                            bet365允许顾客自我禁止使用帐户，限期为6个月 、1 年、2 年或5 年。<br>
                            客户也可选择希望限制访问的站点，例如：体育投注或扑克牌。<br>
                            帐户一旦关闭，客户无论任何原因都不能重开帐户，直至所设定的期限到期。<br>
                            在关闭期间bet365将尽其所能避免客户另外开设一个新帐户。<br>
                            如果帐户状态只是单纯的“关闭”，该帐户可以在任何时间重开。<br>
                            如果您频繁光顾我们的移动版站点，我们建议从您的通知服务中移除bet365，以防止收到来自我们的内容。</p>

                        <p style="text-align:justify">如果您还使用网络社交媒体工具，我们建议您采取措施以确保不再收到我们的新闻或更新。</p>

                        <p style="text-align:justify">如果您希望使用这项功能，请联系我们，我们训练有素的客服人员将竭诚为您服务。</p>

                        <p style="text-align:justify">或者，您可在登录账户后选择“服务”菜单，然后点击“会员”进入“我的账户”部分选择自我禁止。&nbsp;<br>
                            &nbsp;</p>

                        <p style="text-align:justify"><span style="color:#FFFF00"><span style="font-size:14px"><strong>关于控制您投注的信息</strong></span></span><br>
                            在人们用他们的方式博彩的同时，博彩成了某些人的问题。以下提示也许能帮助您控制博彩问题：<br>
                            1. 博彩只是一种娱乐，不应被视为赚钱的方法&nbsp;<br>
                            2. 避免有把输的钱赢回来的想法&nbsp;<br>
                            3. 只投注您付得起的金额&nbsp;<br>
                            4. 时刻记着花在博彩上的时间和金钱&nbsp;<br>
                            5. 如果您暂时不想博彩了，可以选择使用自我控制期的关闭账户&nbsp;<br>
                            6. 如果您需要咨询问题博彩，请联系Gambling Therapy<br>
                            &nbsp;</p>

                        <p style="text-align:justify"><span style="color:#FFFF00"><span style="font-size:14px"><strong>Gambling Therapy&nbsp;</strong></span></span><br>
                            Gambling Therapy是一家为博彩问题人群提供电话咨询和辅导的注册慈善机构。</p>

                        <p style="text-align:justify">请访问www.gamblingtherapy.org进行联系。<br>
                            &nbsp;</p>

                        <p style="text-align:justify"><span style="color:#FFFF00"><span style="font-size:14px"><strong>问题性博彩问卷调查&nbsp;</strong></span></span><br>
                            如果您担心博彩已严重影响到自己或他人的生活，以下问题可帮您找到答案：<br>
                            1. 您进行博彩是否为了逃避乏味或不愉快的生活？&nbsp;<br>
                            2. 进行博彩并花光钱时，您是否感到迷茫或绝望，并且想尽快再开始博彩？&nbsp;<br>
                            3. 您博彩时是否会输掉最后一分钱才罢休（甚至是回家的路费或茶水钱）？&nbsp;<br>
                            4. 您是否曾经用谎言掩饰花在博彩上的金钱或时间？&nbsp;<br>
                            5. 您是否因博彩而对自己的家庭、朋友或爱好失去了兴趣？&nbsp;<br>
                            6. 输钱之后，您是否觉得一定要尽快把这些钱赢回来？&nbsp;<br>
                            7. 争执、挫折或失望是否会导致您想进行博彩？&nbsp;<br>
                            8. 您是否因为博彩而感到情绪低落甚至轻生？<br>
                            在这些问题中，您问答的“是”越多，您就越可能有严重博彩问题。如想了解更多相关信息，请访问GamblingTherapy的网站联系他们：www.gamblingtherapy.org。</p>

                        <p style="text-align:justify">&nbsp;</p>

                        <p style="text-align:justify"><span style="color:#FFFF00"><span style="font-size:14px"><strong>家长控制</strong></span></span><br>
                            父母或者监护人可以使用一系列的第三方软件来监控或者限制电脑的互联网使用:<br>
                            1. Net Nanny过滤软件防止儿童浏览不适宜的网站内容：www.netnanny.com。&nbsp;<br>
                            2. CYBERsitter过滤软件允许父母增加自定义过滤网站：www.cybersitter.com。</p></div>
                </div>
            </div>
            <div class="mainnav textBox hide">
                <h1><span>条款与规则</span></h1>
                <div id="middle" class="description">
                    <div class="content"><p><span style="font-size:14px"><span style="color:#FFFF00"><strong>A. 简介.</strong></span></span><br>
                            如果客户使用和/或访问<span class="new_web_url"></span>网站或我们拥有的任何其他网站（简称“网站”）或应用程序的任何部分和/或在网站上注册，则将被视为同意受制于：(i)这些条款与规则；(ii)我们的隐私政策；以及(iii)下方第二段中提及的有关我们投注或游戏产品的规则（共称“条款”），并被视为接受和理解全部条款。&nbsp;<br>
                            请仔细阅读条款，如果您不接受这些条款，请勿使用网站。条款亦适用于所有电话投注和通过手机设备进行的投注或游戏，包括可下载至手机设备上的软件（凡提述您对网站的使用，即等同于提述您对我们电话投注和/或手机设备投注服务的使用）。<br>
                            2.当您通过网站进行任何游戏或投注时，将被视为接受并同意受规则的制约，这些规则适用于网站上的相应产品。规则可在网站的“帮助”部分查看，具体位置如下：&nbsp;<br>
                            (a)体育投注产品的规则可通过点击此处查看；&nbsp;<br>
                            (b)娱乐场产品的规则可通过点击此处查看；<br>
                            (c)扑克牌产品的规则可通过点击此处查看；&nbsp;<br>
                            (d)游戏产品的规则可通过点击此处查看；&nbsp;<br>
                            (e)维加斯产品的规则可通过点击此处查看。<br>
                            3. 当在以下内容中提及“bet365”、“我们”或“我们的”时：&nbsp;<br>
                            (a) 体育投注产品 - 指Hillside (New Media)有限公司，在英格兰和威尔士（公司编号3958393）注册成立的公司，其注册办公地点设于Hillside, Festival Way, Stoke-on-Trent, Staffordshire, ST1 5SH。全部体育投注将被视为在英国境内所投注并接受。Hillside(New Media)有限公司是由英国博彩委员会颁发执照（执照号码000875）和监管；及&nbsp;<br>
                            (b) 游戏产品 - 指Hillside（直布罗陀）有限公司，在直布罗陀（公司编号97927）注册成立，其注册办公地点设于Unit 1.1, First Floor, Waterport Place, 2 Europort Avenue, Gibraltar。全部游戏产品投注将被视为在直布罗陀境内所投注并接受。Hillside（直布罗陀）有限公司是由直布罗陀博彩委员会颁发执照（执照号码035）和监管。<br>
                            4. 出于多种原因（包括遵循适用的法律、法规以及监管要求），我们可能需要不时更改这些条款和规则。所有更改均将会公布在网站上。最近更新的条款可以在我们的网站上查看。如果任何条款的变更是您无法接受的，您应该停止使用网站和/或关闭您的账户。但是如果在这些条款与规则的更改生效之后，您还继续使用网站，您将被视为已经接受这些更改。<br>
                            5. 当提及“您”、“您的”或“客户”时，是指任何使用网站或bet365服务的人，和/或在bet365注册的任何客户。&nbsp;<br>
                            6. 您也许知道，访问和/或使用网站（包括网站上提供的任何或全部产品）在某些国家可能是非法的（例如：美国）。您有责任确保您对网站的访问和/或使用符合您所在辖区的相关适用法律，且有责任向我们担保博彩在您的居住地并非非法。&nbsp;<br>
                            7. bet365致力于提供最佳的客户服务。作为该承诺的一部分，bet365支持博彩责任。欲知详情，请点击此处。虽然bet365将尽力执行其博彩责任政策，但是如果客户仍然继续博彩和/或出于故意逃避现有相关措施的目的而使用网站和/或因非可控原因bet365无法执行其相关措施/政策，bet365将不承担任何责任或义务。&nbsp;<br>
                            &nbsp;</p>

                        <p><span style="color:#FFFF00"><span style="font-size:14px"><strong>B. 您的BET365账户.</strong></span></span><br>
                            <span style="color:#FFFF00">1. 申请.</span><br>
                            1.1 所有申请者必须年满十八岁以上，方可在bet365投注或注册。同时bet365保留要求客户提供其年龄证明以及在相关资料未提供前冻结客户账户的权利。bet365将严肃处理未成年博彩和责任博彩事宜（如想获知更多详情，请点击此处）。&nbsp;<br>
                            1.2 在网站上注册时所提供的全部信息必须在各个方面都是准确和完整的。尤其在使用信用卡或借记卡时，持卡人姓名必须与在网站上注册时的一致，否则相应帐户将被冻结。当帐户被冻结时，相应客户应联系我们。所有在帐户被冻结前的投注，无论其结果如何，均为有效。<br>
                            1.3 bet365可能通过邮寄验证信件的方式确认客户的住址。信件的内容可包括条款手册、传单以及电话投注卡。所有函件的内容将会被谨慎处理，且信封上不会显示任何提及bet365的信息。当验证信件被寄送时，所有优惠和提款申请将处于待处理状态，直至地址被确认正确无误。<br>
                            1.4 通过接受条款和/或注册使用网站，您在此同意，根据相关法律法规和/或相关监管机构要求，并出于对网站及我们其他产品的使用，我们有权随时进行任何或所有相关的身份、信用及其他方面的核查。您将被视为同意提供与此类核查有关的所有信息。我们有权通过任何我们认为适当的方式，来冻结或限制您的账户，直至相关核查圆满通过。&nbsp;<br>
                            1.5 作为注册程序的一部分，我们可能将您的信息提供给授权的信用咨询机构，以便确认您的身份和支付卡信息。您将被视为同意我们处理有关您注册的此类信息。<br>
                            1.6 每位客户只能开设一个帐户。如果我们发现客户开设了一个以上的帐户，我们有权视这些帐户为一个联合帐户。<br>
                            1.7 客户有责任向本公司提供最新的个人资料，个人资料和您的账户信息可在网站上进行修改。如果您需要任何帮助，请联系我们。&nbsp;<br>
                            &nbsp;</p>

                        <p><span style="color:#FFFF00">2. 账户信息.</span><br>
                            2.1 客户在bet365注册时，可以自行选择其用户名及密码。客户必须对此类信息进行保密，因为您须对自己账户中的所有投注和任何其他活动负责。&nbsp;<br>
                            2.2 如果帐户中有足夠的资金，且您的用户名和密码被正确输入（无论是否经过您的授权），投注即为成立。&nbsp;<br>
                            2.3 如果您在任何时间认为其他第三方知晓您的用户名和/或密码，您应立即通过网站进行更改。如果您忘记部分或全部信息，请联系我们。<br>
                            2.4 对于通过电话进行的投注，只要您的名字及帐户号码或名字及用户名被正确提供（无论是否经过您的授权），则您须对所有交易负责。如果您指定其他人作为您账户的授权使用者，您将对此人使用相关账户信息而进行的全部交易负责。如果您丢失了帐户信息或认为其他人拥有了您的帐户信息，请立即联系我们。&nbsp;<br>
                            2.5 请注意：关于持卡人信息和任何其他敏感数据，请不要以非加密形式的电子邮件发送给我们。&nbsp;<br>
                            2.6 一旦通过网站登录您的账户后，您可以随时查看当前余额和交易历史记录。<br>
                            <span style="color:#FFFF00">3. 其他.</span><br>
                            3.1 bet365积极监控往来于其网站的通信量。bet365保留在发现自动化或机器人活动时封阻网站访问通路的绝对酌情权。&nbsp;<br>
                            3.2 根据某些管辖权，bet365保留限制网站全部或某些部分访问权的权利。&nbsp;<br>
                            3.3 bet365随时可能因任何原因修改或更正网站上提供的产品。&nbsp;<br>
                            3.4 网站的全部或部分内容随时可能因我们对网站的维护和/或对任何网站产品的修改或更正而无法为您所使用。<br>
                            <span style="color:rgb(255, 255, 0)">4. 投注限制.</span></p>

                        <p>4.1 <span style="color:#FF0000">【真人视讯】</span></p>

                        <p>4.1.1 百家乐（庄/闲）（大/小）（庄对/闲对）同局将不允许出现互投！</p>

                        <p>DS贵宾厅百家乐禁止同局投注【庄/闲、庄对/闲对/无对、大/小】</p>

                        <p>HG名人馆百家乐禁止同局投注【庄/闲、庄对/闲对/无对、大/小】</p>

                        <p>4.1.2 轮盘（红/黑）（单/双）同局不允许出现互投，轮盘游戏独号单局最高投注为18号！</p>

                        <p>4.1.3 骰宝（大/小）（单/双）同局不允许出现互投！</p>

                        <p>4.2.4 真人视讯禁止玩家进行【倍投】游戏，最低倍投金额10000起5倍起投除外；</p>

                        <p><span style="color:rgb(255, 0, 0)">【体育投注】</span></p>

                        <p>4.2.1 足球投注（让球）赛事未开赛内不允许出现互投（注:开赛后需比分发生变化且间隔15分钟）</p>

                        <p>4.2.2 足球投注（独赢）（单/双）（大/小）赛事不允许出现互投（注:无论单式或者滚球一律不能进行互投）</p>

                        <p>4.2.3&nbsp;篮球投注（独赢）（单/双）（大/小）（让球）赛事未开赛内不允许出现互投（注:开赛后需间隔10分钟）</p>

                        <p>4.2.4 足球投注 所有足球注单在赛事进行至80分钟后的注单一律不予计算盈利！</p>

                        <p>4.2.5 足球篮球 不允许投注赔率低于0.5盘口 如出现将不予计算盈利</p>

                        <p>4.2.6 篮球投注 投注篮球赛事已进行至第四节注单将不计算盈利。已投注成功注单一律取消</p>

                        <p>4.3 <span style="color:#FF0000">【彩票游戏】</span></p>

                        <p>4.3.1 六合彩投注（特码）同期最高投注号数为25号！</p>

                        <p>4.3.2 广大快乐十分（单号数）同期最高投注号数为10号！</p>

                        <p>4.3.3（大/小）（单/双）同局不允许出现互投！</p>

                        <p>4.4.<span style="color:#FF0000">【电子游戏】</span></p>

                        <p>4.4.1 投注HB电子【百家乐零佣金】【经典黄金21点】【欧洲轮盘】一律不计算有效投注。</p>

                        <p><span style="color:#FFFFFF">4.4.1 投注HB电子【<span style="font-family:verdana,arial,sans-serif; font-size:12px">百家乐零佣金】【</span>经典黄金21点】【欧洲轮盘】一律不计算盈利。</span></p>

                        <p>4.5.<span style="color:#FF0000">【注意】以上投注规则将不定时[推出/修改]，Bet365对此规则保有最终解释权！</span></p>

                        <p>4.6.<span style="color:#FF0000">【注意】如投注时产生上例投注限制的注单！所投注单将不计算盈利金额、有效投注！</span></p>

                        <p><span style="color:#FFFF00"><span style="font-size:14px"><strong>C. 我方责任.</strong></span></span><br>
                            1. bet365不对任何（被视为或被指控的）由网站或网站内容而导致产生的破坏及损失承担任何责任（包括运行或传输过程中的延迟或间断、数据丢失或损坏、通讯或线路故障、任何人对网站或其内容的滥用、内容的任何错误或遗漏）。&nbsp;<br>
                            2. 虽然bet365不断努力确保网站信息的正确性，但是不保证网站信息的准确性或完整性。网站可能包含误植或其他不准确或陈旧的信息。bet365没有义务更新此类信息。网站信息的提供将遵循“现状”原则，且不附带任何条件、保证或其他条款。因此，在法律允许的最大程度内，bet365为您提供网站所遵循的原则为：bet365排除所有可能对网站有效的（除了对这些条款与规则）陈述、明示或暗示的保证、条件及其他条款。&nbsp;<br>
                            3. 根据这些条款与规则，bet365对您承担的所有责任不超过：&nbsp;<br>
                            (a)您通过自己账户所进行投注的数额（对于引发相关责任的相关投注或产品）；和&nbsp;<br>
                            (b)相应资金的数额（如果此类资金被我们处置不当）。&nbsp;<br>
                            (c) £10,000（对于任何其他责任）。&nbsp;<br>
                            4. 无论是在合同、民事侵权行为（包括过失）、违反成文法或任何其他方面，bet365均不对以下情况承担责任（无论直接或间接产生）：&nbsp;<br>
                            (a)利润的损失；&nbsp;<br>
                            (b)生意的损失；&nbsp;<br>
                            (c)收入的损失；&nbsp;<br>
                            (d)机会的损失；&nbsp;<br>
                            (e)数据的损失；&nbsp;<br>
                            (f)商誉或名誉的损失；或&nbsp;<br>
                            (g)任何特殊的、间接的或后序的损失，&nbsp;<br>
                            无论此类损失是否于这些条款与规则之日时已被当事方纳入考虑范围内。&nbsp;<br>
                            5. F部分中的任何内容均不应限制bet365向客户赔付彩金或其他金额的责任，其必须遵循此处的条款与规则以及附录二中的产品最高彩金方面的规定。<br>
                            6. 如果以下情况发生，这些条款与规则中的任何内容均不能免除或限制bet365的责任：(i)由于bet365的疏忽导致死亡或人身伤害；(ii)欺诈或欺诈性错误陈述；或(iii)任何不能通过适用法免除或限制的责任。<br>
                            &nbsp;</p>

                        <p><span style="color:#FFFF00"><span style="font-size:14px"><strong>D. 我方知识产权.</strong></span></span><br>
                            1. 网站内容受国际版权法及其它知识产权保护。bet365、其合作伙伴或其他第三许可方享有以上权利。<br>
                            2. 网站上提及的全部产品、公司名称及标识均为相应所有者（包括bet365）的商标、服务商标或商品名。&nbsp;<br>
                            3. 除出于投注目的而使用产品所允许的范围外，网站的任何部分不得以任何方式或途径被再生或存储、修改、复制、再版、上传、张贴、传播或分发，亦不得未经我们事先明确书面同意被包含在任何其他网站或任何公共或私人的电子检索系统或服务（包括文字、图形、视频、消息、代码和/或软件）中。&nbsp;<br>
                            4. 如果您使用了一项允许您将材料、信息、评论、帖子或其他内容（“用户内容”）上传至网站的功能，则此“用户内容”将被视为非机密及非专有的，同时bet365有权出于任何目的使用、复制、分发及向第三方披露任何此类“用户内容”。如果任何第三方声称您在网站上所张贴或上传的任何“用户内容”构成了对其知识产权或隐私权的侵犯，则bet365亦有权将您的身份透露给这些第三方。bet365有权移除、更正或编辑您在网站上张贴的任何“用户内容”。&nbsp;<br>
                            5. 严禁任何对网站或其内容进行的商业用途或开发利用。&nbsp;<br>
                            &nbsp;</p>

                        <p><strong><span style="color:#FFFF00"><span style="font-size:14px">E. 其他条款.</span></span></strong><br>
                            1. 这些条款与规则、隐私政策、规则、任何其中明确提及的文件、网站上张贴的任何指南或规则，共同构成了整个协议及当事方之间的理解，并取代当事方之间有关本条款与规则主题的先前协议。您承认并同意，签署和接受这些条款与规则、隐私政策、规则、任何其中明确提及的文件、网站上张贴的任何指南或规则，即表示您不可以依靠或享有对任何人（无论是否为本协议的当事方）的任何声明、陈述、担保、理解、承诺或保证（无论出于疏忽还是无意造成）的法律救济，除非这些条款与规则中明确规定。本条款不得用作限制或免除任何因欺诈或欺诈性错误陈述产生的责任。&nbsp;<br>
                            2. 任何情况下，在执行、行使或诉求这些条款与规则或法律所授予或所产生的任何权利、权力、特权、索赔或法律救济过程中的任何延误、故障或遗漏（全部或部分）均不能被视为或解释为对其或上述情况中的任何其他权利、权力、特权、索赔或法律救济的放弃，或禁止对其或之后任何时候、任何其他情况中的任何其他权利、权力、特权、索赔或法律救济的执行。&nbsp;<br>
                            3. 这些条款与规则中提供的权利及法律救济是累积性的，并且（除非在这些条款与规则中另有规定）不排除法律中的任何其他权利或救济措施。<br>
                            4. 如果这些条款与规则中的任何规定被任何法庭或具备法定资格的管辖区内的行政机关认定为无效或不可执行，则该无效或不可执行性不得影响这些条款与规则中其他规定的完整法律效力。&nbsp;<br>
                            5. 您应履行义务填写并签署所有文件，并达到bet365通过这些条款与规则对您提出的要求，以便bet365能受益于这些条款与规则、保护和执行这些条款与规则，并让其充分发挥效力。&nbsp;<br>
                            6. 这些条款与规则不能在当事方之间建立或被视为建立合作、合资或委托代理关系。同时，除非这些条款与规则中另有明确规定，否则任何当事方均无权以任何方式约束任何其他当事方。&nbsp;<br>
                            7. bet365不得违反这些条款与规则，如果bet365延迟履行或无法履行其义务，且此延迟或无法履行是由赛事本身或bet365控制之外的原因或情况所导致的，则bet365对此不承担任何责任，这些不可控原因或情况包括（但不限于）任何电信网络故障、断电、第三方电脑硬件或软件故障、火灾、闪电、爆炸、洪水、恶劣天气、工业纠纷或停工、恐怖主义活动以及政府或其他权威机构的行为。在此情况下，履行时间应该被延长，所延时间等同于履行义务被延迟或无法履行义务的时间。&nbsp;<br>
                            8. bet365可能对这些条款与规则进行转让、移转、收费、再授权或以任何其他方式处理，或将这些条款与规则中的任何权利和义务分包给bet365集团中的任何公司。&nbsp;<br>
                            9. 在这些条款与规则下，任何通知必须以书面英语形式给出，并可以由专人递送或以一等邮件、记录派递、挂号邮寄、航空邮寄或传真方式寄往：(a)就bet365而言，列于这些条款与规则开始部分的相关bet365公司地址；及(b)就bet365向您发出的通知而言，遵循客户登记程序（包括任何您已通知bet365的资料更改）。任何通知被视为已收到的依据为：(a)如果由专人递送，则在递送时；(b)如果由一等邮件、记录派递或挂号邮寄，则在邮寄当天后的第二个晴朗日上午9:30(GMT)时；(c)如果由预付费挂号航空邮寄，则在邮寄当天后的第五个晴朗日上午9:30(GMT)时；及(d)如果通过传真发送，则在发件人传送时。<br>
                            10. 附录、隐私政策、规则、任何其中明确提及的文件、网站上张贴的任何指南或规则，是这些条款与规则不可或缺的组成部分，并且如同这些条款与规则的主体部分一样具有效力。如果主体和这些条款与规则、附录、隐私政策、规则、任何其中明确提及的文件、网站上张贴的任何指南或规则之间出现任何不一致，则以主体为准。&nbsp;<br>
                            &nbsp;</p>

                        <p><span style="color:#FFFF00"><span style="font-size:14px"><strong>F. 投诉、争议、适用法律及管辖权.</strong></span></span><br>
                            1. 如果因过去或当前交易产生任何索赔或争议，请联系我们。如果bet365无法解决争议，则会将有关争议移交给仲裁机构，例如独立博彩仲裁服务(IBAS)，在争议各方均有全面代理的情况下，IBAS将会做出最终裁决（除非出现明显错误）。除非bet365未遵循仲裁决定﹐否则任何关于投注的争议均不会导致诉讼，法律措施或博彩执照或许可（包括任何远程运行商的执照或个人执照）的吊销。&nbsp;<br>
                            2. 如想查看更多关于IBAS的信息，请点击此处。&nbsp;<br>
                            3. 美式运动投注的结算：在所有情况下，美式运动投注的结算应按照各项体育运动的管理机构所提供的数据和结果（包括明显错误）。相关管理机构如下：NFL、NCAAF、CFL、NBA、NCAAB、NHL、MLB、NASCAR、MLS和PGA巡回赛。&nbsp;<br>
                            4. 这些条款与规则以及任何由这些条款与规则或其主题导致产生的争议或索赔，无论是契约性或非契约性的，均应遵循：&nbsp;<br>
                            (a)英格兰及威尔士法律 - 如果相关争议或索赔是关于在Hillside (New Media)有限公司进行的体育或活动投注；&nbsp;<br>
                            (b)直布罗陀法律 - 如果相关争议或索赔是关于在Hillside（直布罗陀）有限公司进行的游戏投注；及&nbsp;<br>
                            (c)英格兰及威尔士法律 - 所有其他情况。&nbsp;<br>
                            5. 如果您接受这些条款与规则和/或进行投注和/或使用（无论是否经过授权）bet365提供的设施（无论是通过网站或其他方式），您即被视为无条件同意英格兰及威尔士法庭将对此有专属管辖权，并负责解决由这些条款与规则而产生的任何纠纷。尽管有上述规定，bet365有权在客户所居住的国家向客户提出索赔。<br>
                            &nbsp;</p>

                        <p><strong><span style="font-size:14px"><span style="color:#FFFF00">G.&nbsp;第三方权利.</span></span></strong><br>
                            作为第三方合同受益人，bet365及其集团公司可根据这些条款与规则，以bet365名义或其自身名义对您进行处罚。合同（第三方权利）法案2016适用于这些条款与规则。&nbsp;</p>
                    </div>
                </div>
            </div>
            <div class="mainnav textBox hide">
                <h1><span>银行</span></h1>
                <div id="middle" class="description">
                    <div class="content"><p><span style="font-size:20px"><strong>存款</strong></span></p>

                        <p>*可选择的存款方式会随时根据玩家所在的不同地区变更。</p>


                        <p>在线网银支付是一种安全的网上转账业务，您可以直接将存款从您的银行账户转入您的bet365账户。 经银行成功确认后，存款将即刻存入您的bet365账户。<br>
                            <strong>服务费</strong>: 免费<br>
                            <strong>办理时间</strong>: 即刻完成</p>


                        <p>快速银行转账以一种方便又安全的方式，通过网上银行业务将存款转入您的bet365账户。存款将从您的账户电子汇入到bet365持有的银行账户，经您的银行确认后，自动存入您的bet365账户。<br>
                            <strong>服务费</strong>: 免费<br>
                            <strong>办理时间</strong>: 即刻完成</p>


                        <p>使用网上银行业务，您可以从您的个人网银页面将存款转账到您的bet365账户。我们在成功收到您的转账后将即刻存入您的bet365账户。<br>
                            *请注意：时间会根据不同性质的转账变更。网银验证过程可能会导致时间延长。<br>
                            <strong>服务费</strong>: 免费(您的银行可能会收取手续费)<br>
                            <strong>处理时间</strong>: 即刻完成</p>


                        <p>银行柜台交易让您可以直接通过当地银行将存款转账到您的bet365账户。bet365在成功收到您的转账后将即刻存入您的bet365账户。<br>
                            *请注意：时间会根据不同性质的转账变更。网银验证过程可能会导致时间延长。<br>
                            <strong>服务费</strong>: 免费(您的银行可能会收取手续费)<br>
                            <strong>处理时间</strong>: 即刻完成</p>


                        <p>ATM选项让您可以通过自动取款机从您的银行账户转账到您的bet365账户。bet365在收到转账后将即刻存入您的bet365账户。<br>
                            *请注意：时间会根据不同性质的转账变更。网银验证过程可能会导致时间延长。<br>
                            <strong>服务费</strong>: 免费(您的银行可能会收取手续费)<br>
                            <strong>办理时间</strong>: 即刻完成</p>
                    </div>
                </div>
            </div>
            <div class="mainnav textBox hide">
                <h1><span>帮助</span></h1>
                <div id="middle" class="description">
                    <div class="content">
                        <h2><span style="font-size:14px">常见问题帮助&nbsp;- 我们的客户服务人员为您提供24小时全天为您解答服务！&nbsp; &nbsp; &nbsp;<span style="color:#FF8C00">&nbsp;</span><u>
                                    <a class="to_livechat">
                                        <span style="color:#FF8C00">点击联系在线客服</span>
                                    </a>
                                </u>
                            </span>
                        </h2>

                        <div class="faqWrapper ui-accordion ui-widget ui-helper-reset ui-accordion-icons" id="accordion" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0.0980392); padding: 0px; margin: 12px auto; border: 1px solid rgb(220, 220, 220); height: auto; border-top-left-radius: 8px; border-top-right-radius: 8px; border-bottom-right-radius: 8px; border-bottom-left-radius: 8px; color: rgb(84, 84, 84); font-family: Verdana, Geneva, sans-serif; font-size: 12px; line-height: normal;">
                            <a class="to_livechat faqQuestion ui-accordion-header ui-helper-reset ui-state-default ui-corner-all" id="accumulator">如果我忘记了自己的用户名/密码该怎么办？</a>
                            <a class="to_livechat faqQuestion ui-accordion-header ui-helper-reset ui-state-default ui-corner-all" id="accumulator">为什么我的用户名和密码无法登录账户?</a>
                            <a class="to_livechat faqQuestion ui-accordion-header ui-helper-reset ui-state-default ui-corner-all" id="accumulator">什么是“结束投注”，它是如何操作的？</a>
                            <a class="to_livechat faqQuestion ui-accordion-header ui-helper-reset ui-state-default ui-corner-all" id="accumulator">在哪里可以找到投注结算相关规则？</a>
                            <a class="to_livechat faqQuestion ui-accordion-header ui-helper-reset ui-state-default ui-corner-all" id="accumulator">我的投注需要多长时间才能得到结算？</a>
                            <a class="to_livechat faqQuestion ui-accordion-header ui-helper-reset ui-state-default ui-corner-all" id="accumulator">bet365有哪些博彩责任选项？</a>
                            <a class="to_livechat faqQuestion ui-accordion-header ui-helper-reset ui-state-default ui-corner-all" id="accumulator">我怎样验证账户？</a>
                            <a class="to_livechat faqQuestion ui-accordion-header ui-helper-reset ui-state-default ui-corner-all" id="accumulator">什么是“个人信息核实”？</a>
                            <a class="to_livechat faqQuestion ui-accordion-header ui-helper-reset ui-state-default ui-corner-all" id="accumulator">我如何进行存款？</a>
                            <a class="to_livechat faqQuestion ui-accordion-header ui-helper-reset ui-state-default ui-corner-all ui-state-hover" id="accumulator">你们接受什么支付方式？</a>
                            <a class="to_livechat faqQuestion ui-accordion-header ui-helper-reset ui-state-default ui-corner-all" id="accumulator">为什么我不能提款？</a>
                            <a class="to_livechat faqQuestion ui-accordion-header ui-helper-reset ui-state-default ui-corner-all" id="accumulator">我为什么不能存款？</a>
                            <a class="to_livechat faqQuestion ui-accordion-header ui-helper-reset ui-state-default ui-corner-all" id="accumulator">我如何更改我的支付方式？</a>
                            <a class="to_livechat faqQuestion ui-accordion-header ui-helper-reset ui-state-default ui-corner-all" id="accumulator">为什么我的投注没有得到全额返还？</a>
                            <a class="to_livechat faqQuestion ui-accordion-header ui-helper-reset ui-state-default ui-corner-all" id="accumulator">取款需要多长时间？</a>
                            <a class="to_livechat faqQuestion ui-accordion-header ui-helper-reset ui-state-default ui-corner-all" id="accumulator">为什么我被自动退出账户？</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>


<script type="text/javascript">
    $(function () {
        var index = '<?php echo $key;?>';
        // 进到页面默认处理
        $('.textWrap .textBox:eq('+index+')').removeClass('hide').siblings().addClass('hide');

       // 标签切换
        $('.about-nav li a').on('click',function () {
           var ii = $(this).parents('li').index();
           var tx = $(this).text();
            // console.log(ii);
            $('.bzzx_title').text(tx);
           $(this).addClass('active').parents('li').siblings().find('a').removeClass('active');
           $('.textWrap .textBox:eq('+ii+')').removeClass('hide').siblings().addClass('hide');

        });

        $('.new_web_url').text(web_config.new_web_url); // 最新网址
        indexCommonObj.addLiveUrl();
        
    })
</script>