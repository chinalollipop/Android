<?php
session_start();

$uid = $_SESSION['Oid'];
$tplNmaeSession = $_SESSION['TPL_NAME_SESSION'];
$kytesturl = 'http://play.ky206.com/jump.do' ; // 开元试玩链接
$lytesturl = $_SESSION['LYTEST_PLAY_SESSION']; // 乐游试玩链接
$testuid = '3e3d444a6054eae7c22cra8' ;


?>

<style>
    /*live*/
    .mainBody{background:url(<?php echo $tplNmaeSession;?>images/chess/chess-bg.png) top center;}
    .mainBody .wrap {width:984px;margin: 10px auto 0;padding: 0 8px;overflow: hidden;background: #121212;}
    .qipai .wrap .leftDiv{width:186px}
    .qipai .wrap .leftDiv ul li{margin:8px 0 15px}
    .qipai .wrap .leftDiv ul li a{display:block;overflow:hidden;height:44px;line-height:44px;font-size:17px;color:#808080;text-align:center;border: solid 1px goldenrod;}
    .qipai .wrap .leftDiv ul li.active a{background:url(<?php echo $tplNmaeSession;?>images/chess/title_bg.png) no-repeat center center;color: #400d07;height: 44px;width: 188px;border: 0;}
    .qipai .wrap .leftDiv ul li a .imgDiv{width:52px;height:42px;text-align:center;float:left;margin-left:23px;display:flex;justify-content:center;align-items:center}
    .qipai .wrap .leftDiv ul li a span{float:left;margin-left:4px}
    .qipai .wrap .leftDiv .searchDiv{width:186px;height:32px;line-height:32px;background:url(<?php echo $tplNmaeSession;?>images/qp-search-bg.png)}
    .qipai .wrap .leftDiv .searchDiv input{font-size:12px;color:#400d07;width:140px;padding:0 6px;height:32px;float:left;background:transparent}
    .qipai .wrap .leftDiv .searchDiv input::-moz-placeholder{color:#400d07}
    .qipai .wrap .leftDiv .searchDiv input::-ms-input-placeholder{color:#400d07}
    .qipai .wrap .leftDiv .searchDiv input::-webkit-input-placeholder{color:#400d07}
    .qipai .wrap .leftDiv .searchDiv button{width:46px;height:31px;background:url(<?php echo $tplNmaeSession;?>images/search-icon.png) no-repeat center center;border:0}
    .qipai .wrap .rightDiv{width:790px}
    .qipai .wrap .rightDiv .hdDiv{padding-top:8px;height: 180px;background: url(<?php echo $tplNmaeSession;?>images/chess/ky_title.png) no-repeat center center;}
    .qipai .wrap .rightDiv .bdDiv{background:#1f1f1f;padding:13px 0;min-height:660px}
    .qipai .wrap .rightDiv .bdDiv ul{margin:0 -9px 0 -1px;overflow:hidden}
    .qipai .wrap .rightDiv .bdDiv ul li{float:left;margin:8px 15px}
    .qipai .wrap .rightDiv .bdDiv ul li .conDiv{width:170px;height:180px;position:relative;background:#E4E4E4;text-align:center}
    .qipai .wrap .rightDiv .bdDiv ul li .conDiv img{display:inline-block;vertical-align:middle;margin-top:14px}
    .qipai-fg .wrap .rightDiv .bdDiv ul li .conDiv img{margin-top:0}
    .qipai .wrap .rightDiv .bdDiv ul li .conDiv .bgDiv{display:none;position:absolute;top:0;left:0;width:100%;height:100%;}
    .qipai .wrap .rightDiv .bdDiv ul li:hover .conDiv .bgDiv{display:block}
    .qipai .wrap .rightDiv .bdDiv ul li .conDiv .bgDiv a.gz_a{position:absolute;right:10px;top:0px;line-height:1}
    .qipai-fg .wrap .rightDiv .bdDiv ul li .conDiv .bgDiv a.gz_a{top:10px}
    .qipai .wrap .rightDiv .bdDiv ul li .conDiv .bgDiv a.start_a{position:absolute;left:50%;margin-left:-37.5px;bottom:12px;line-height:1;width:75px;height:20px;line-height:20px;background:#ffff00;font-size:14px;color:#000;text-align:center;border-radius:4px;display:block}

</style>

<div class="page_banner">
    <div class="promlink">
        <div class="centre clearFix">
            <div class="title"><img src="<?php echo $tplNmaeSession;?>images/chess/qpmain.jpg"></div>
            <div class="marqueeWarp">
                <p style="text-align: center">
                    <marquee id="msgNews" scrollamount="4" scrolldelay="100" direction="left" onmouseover="this.stop();" onmouseout="this.start();" style="cursor: pointer;height: 30px;line-height: 30px;width: 950px;color: #fff;">
                        <?php echo $_SESSION['memberNotice']; ?>
                    </marquee>
                </p>
            </div>
        </div>
    </div>
</div>

<div class="mainBody qipai">
        <div class="wrap">
            <div class="leftDiv fl">
                <ul>
                    <li class="active">
                        <a href="javascript:;" data-to="ky">
                            <div class="imgDiv"><img src="<?php echo $tplNmaeSession;?>images/chess/ky.png"></div><span>KY棋牌</span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:;" data-to="ly">
                            <div class="imgDiv"><img src="<?php echo $tplNmaeSession;?>images/chess/ly.png"></div><span>乐游棋牌</span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:;" data-to="vg">
                            <div class="imgDiv"><img src="<?php echo $tplNmaeSession;?>images/chess/vg.png"></div><span>VG棋牌</span>
                        </a>
                    </li>
                   <!-- <li>
                        <a href="javascript:;" data-to="hg">
                            <div class="imgDiv"><img src="<?php /*echo $tplNmaeSession;*/?>images/chess/hg.png"></div><span>皇冠棋牌</span>
                        </a>
                    </li>-->
                    <li>
                        <a href="javascript:;" data-to="kl">
                            <div class="imgDiv"><img src="<?php echo $tplNmaeSession;?>images/chess/kl.png"></div><span>快乐棋牌</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="rightDiv fr">
                <div class="hdDiv"> </div>
                <div class="bdDiv bdDiv_game_list">

                </div>

            </div>
        </div>
    
</div>


<script type="text/javascript">
    $(function () {
       var kyGamedata = [
           {id:'220',name:'炸金花'},
           {id:'230',name:'极速炸金花'},
           {id:'600',name:'21点'},
           {id:'610',name:'斗地主'},
           {id:'620',name:'德州扑克'},
           {id:'630',name:'十三水'},
           {id:'720',name:'二八杠'},
           {id:'730',name:'抢庄牌九'},
           {id:'830',name:'抢庄牛牛'},
           {id:'860',name:'三公'},
           {id:'870',name:'通比牛牛'},
           {id:'900',name:'龙虎'},
           {id:'910',name:'百家乐'},
           {id:'920',name:'森林舞会'},
           {id:'930',name:'百人牛牛'},
       ];
        var lyGamedata = [
            {id:'220',name:'炸金花'},
            {id:'600',name:'21点'},
            {id:'610',name:'斗地主'},
            {id:'620',name:'德州扑克'},
            {id:'630',name:'十三水'},
            {id:'720',name:'二八杠'},
            {id:'730',name:'抢庄牌九'},
            {id:'740',name:'二人麻将'},
            {id:'830',name:'抢庄牛牛'},
            {id:'860',name:'三公'},
            {id:'870',name:'通比牛牛'},
            {id:'900',name:'龙虎'},
            {id:'910',name:'百家乐'},
            {id:'930',name:'百人牛牛'},
            {id:'950',name:'红黑大战'},
            {id:'8150',name:'看四张抢庄牛牛'},
        ];
        var vgGamedata = [
            {id:'1',name:'斗地主'},
            {id:'3',name:'抢庄牛牛'},
            {id:'4',name:'百人牛牛'},
            {id:'6',name:'多财多福'},
            {id:'7',name:'竞咪楚汉德州'},
            {id:'8',name:'推筒子'},
            {id:'9',name:'加倍斗地主'},
            {id:'11',name:'血战麻将'},
            {id:'12',name:'炸金花'},
            {id:'13',name:'必下德州'},
            {id:'14',name:'百人三公'},
            {id:'15',name:'十三水'},
        ];
        var hgGamedata = [
            {id:'3012',name:'斗地主'},
            {id:'3015',name:'抢庄牛牛'},
            {id:'3016',name:'龙虎斗'},
            {id:'3014',name:'百人诈金花'},
            {id:'3017',name:'二八杠'},
            {id:'3018',name:'德州扑克'},
            {id:'3019',name:'通比牛牛'},
            {id:'3020',name:'炸金花'},
            {id:'3021',name:'跑得快'},
            {id:'3022',name:'三公'},
            {id:'3023',name:'百家乐'},
        ];
        var klGamedata = [
            {id:'100',name:'快乐棋牌'},
        ];

        changeChessType();
        showChessGameList('ky'); // 默认开元

        // 切换棋牌种类
        function changeChessType() {
            $('.leftDiv li a').on('click',function () {
                var gametype = $(this).attr('data-to');
                $(this).parent('li').addClass('active').siblings().removeClass('active');
                showChessGameList(gametype)
            })
        }

        // 列表渲染
        function showChessGameList(type) {
            var gameList = kyGamedata ; // 游戏列表
            var openurl = 'ky'; // 打开游戏路径
            switch (type){
                case 'ky':
                    gameList = kyGamedata;
                    openurl = 'ky';
                    break;
                case 'ly':
                    gameList = lyGamedata;
                    openurl = 'lyqp';
                    break;
                case 'vg':
                    gameList = vgGamedata;
                    openurl = 'vgqp';
                    break;
                case 'hg':
                    gameList = hgGamedata;
                    openurl = 'hgqp';
                    break;
                case 'kl':
                    gameList = klGamedata;
                    openurl = 'klqp';
                    break;
            }
            var str = '<ul class="clearfix">';
            for(var i=0;i<gameList.length;i++){
                str += ' <li>' +
                    '                <div class="conDiv">' +
                    '                <img src="<?php echo $tplNmaeSession;?>images/chess/'+ type +'/'+ type +'_' +gameList[i].id +'.png" alt="">' +
                    '                <div class="bgDiv">' +
                    '                <a href="javascript:;" class="start_a" onclick="indexCommonObj.openGameCommon(this,\'<?php echo $uid;?>\',\'../../app/member/'+openurl+'/index.php?uid=<?php echo $uid;?>\')" >开始游戏</a>' +
                    '                </div>' +
                    '                </div>' +
                    '                </li>';
            }
            str += '</ul>';
            $('.bdDiv_game_list').html(str);
            
        }

    })
</script>