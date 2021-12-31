<?php
session_start();
require ("../include/config.inc.php");
//require ("../include/redis.php");
require ("../include/address.mem.php");

$datastr = getLyQpSetting() ;

?>

    <style type="text/css">

        .sy_content {
            min-height: 500px;
            background: #251106;
        }
        .container {
            width: 1000px;
            margin: 0 auto;
            padding: 60px 0 20px;
        }
        .chess_bottom {
            /*width: 900px;*/
            margin: 0 auto;
            padding: 20px 50px;
        }
        .chess_bottom>div {
            display: inline-block;
        }
        .chess_bottom_change {
            margin-bottom: 5px;
            overflow: hidden;
        }
        .chess_bottom_tran {
            display: inline-block;
            float: left;
            overflow: hidden;
            padding: 0 5px;
            height: 25px;
            line-height: 25px;
            max-width: 140px;
            min-width: 120px;
            border-radius: 50px;
            background: #554031;
            box-shadow: 1px 2px 1px rgba(0,0,0,.3);
            color: #fff;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-size: 16px;
            transition: background .3s ease;
        }
        .chess_bottom .money_logo {
            background-image: url(../images/qp/money_logo.png);
        }
        .chess_bottom .tran_logo {
            background-image: url(../images/qp/tran_logo.png);
        }
        .chess_bottom i {
            float: left;
            display: inline-block;
            margin: 2px 5px 0 0;
            width: 21px;
            height: 21px;
            background-size: contain;
        }
        .chess_bottom a {
            display: inline-block;
            margin-left: 25px;
            padding: 0 5px;
            width: 120px;
            height: 25px;
            line-height: 25px;
            border-radius: 50px;
            background: #d1601a;
            box-shadow: 1px 2px 1px rgba(0,0,0,.3);
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background .3s ease;
        }
        .chess_bottom_hg .chess_bottom_play {
            width: 400px;
            height: 172px;
            background: url(../images/qp/hg_chess.png) no-repeat;
        }
        .float_right {
            float: right;
        }
        .chess_bottom_play p {
            font-size: 26px;
            color: #fff;
            text-align: right;
            padding: 20px 30px;
        }
        .chess_bottom_play a.try_play {
            background: #644d3b;
        }
        .chess_bottom_play a {
            display: block;
            margin: 40px 43px 0 43px;
            width: 84px;
            height: 32px;
            line-height: 32px;
            border-radius: 6px;
            text-align: center;
        }
        .chess_bottom>div:nth-child(2n) {
            margin-left: 90px;
        }
        .chess_bottom_ky .chess_bottom_play {
            width: 400px;
            height: 172px;
            background: url(../images/qp/ky_chess.png) no-repeat;
        }
        .chess_bottom_vg .chess_bottom_play {
            width: 400px;
            height: 172px;
            background: url(../images/qp/vg_chess.png) no-repeat;
            background-size: cover;
        }
        .chess_bottom_ly .chess_bottom_play {
            width: 400px;
            height: 172px;
            background: url(../images/qp/ly_chess.png) no-repeat;
            background-size: cover;
        }
        .zrsx_mn {
            margin: 60px auto 0;
            overflow: hidden;
            width: 1000px;
            margin-bottom: 20px;
        }
        .chess_bottom>div:nth-child(1),.chess_bottom>div:nth-child(2){
            margin-bottom: 15px;
        }
    </style>

<div class="sy_m sy_content">
    <!-- 主体 -开始 -->
    <div class="container">
        <div class="big-img">
            <img src="../images/qp/bg.jpg">
        </div>
        <div class="chess_bottom">
            <!-- VG -->
            <div class="chess_bottom_vg">
                <div class="chess_bottom_play">
                    <div class="float_right">
                        <p>VG 棋 牌</p>
                        <a href="https://sw.vgvip88.com" target="_blank">免费试玩</a>
                    </div>
                </div>

            </div>
            <!-- 乐游 -->
            <div class="chess_bottom_ly">
                <div class="chess_bottom_play">
                    <div class="float_right">
                        <p>乐 游 棋 牌</p>
                        <a href="<?php echo $datastr['demourl'];?>" target="_blank">免费试玩</a>
                    </div>
                </div>

            </div>
            <!-- 皇冠 -->
            <div class="chess_bottom_hg">
                <div class="chess_bottom_play">
                    <div class="float_right">
                        <p>皇 冠 棋 牌</p>
                        <a href="javascript:alert('【注册会员账号后即可免费试玩】');">免费试玩</a>
                    </div>
                </div>

            </div>
            <!-- 开元 -->
            <div class="chess_bottom_ky">
                <div class="chess_bottom_play">
                    <div class="float_right">
                        <p>开 元 棋 牌</p>
                        <a href="http://new.ky206.com" target="_blank">免费试玩</a>
                    </div>
                </div>

            </div>

        </div>
    </div>
    <!-- 主体 -结束 -->
</div>
</body>
</html>