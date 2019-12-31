package com.hgapp.a6668.common.util;

/**
 * Created by Daniel on 2017/5/18.
 */

public abstract class HGConstant {
    public static final String APP_CP_HEADER= "app_cp_header";
    public static final String APP_CP_COOKIE= "app_cp_cookie";
    public static final String APP_CP_X_SESSION_TOKEN= "app_cp_x_session_token";
    public static final String APP_TY_COOKIE= "app_ty_cookie";
    public static final String APP_CP_COOKIE_AVIABLE= "app_cp_cookie_aviable";
    public static final String APP_DEMAIN_URL= "app_demain_url";
    public static final String APP_DB_DIRNAME = "app_db_dirname";
    //防止重复点击按钮，避免冲突触发业务
    public static final int throttleWindowTime = 5;
    //有些按钮可以快速点击，例如刷新余额
    public static final int throttleWindowTime_short = 2;
    //发送验证码时长
    public static final int ACTION_SEND_AUTH_CODE = 60;

    //请求联盟盘口的时间 滚球
    public static final int ACTION_SEND_LEAGUE_TIME_R = 20;
    //请求联盟盘口的时间 今日
    public static final int ACTION_SEND_LEAGUE_TIME_T = 60;
    //请求联盟盘口的时间 早盘
    public static final int ACTION_SEND_LEAGUE_TIME_M = 90;

    //请求准备下注的时间
    public static final int ACTION_SEND_PREPARE_BET_TIME = 10;

    //发送验证码类型
    public static final String PRODUCT_N_SEND_AUTH_CODE_TYPE = "60025";

    //发送注册验证码类型
    public static final String PRODUCT_N_SEND_AUTH_CODE_REGISTER = "6";

    //发送登录验证码类型
    public static final String PRODUCT_N_SEND_AUTH_CODE_LOGIN = "10";

    //找回密码验证码类型
    public static final String PRODUCT_N_FIND_PWD_CODE_LOGIN = "5";

    //发送短信类型为修改手机号、安全验证手机号
    public static final String PRODUCT_N_SEND_AUTH_CODE_PHONE="11";

    //发送验证码有效时长
    public static final String PRODUCT_N_SEND_AUTH_CODE_DURATION = "1";

    //产品的首字母
    public static final String PRODUCT_INITIAL = "v";

    //产品的ID
    public static final String PRODUCT_ID = "a6668";

    //产品的平台 android
    public static final String PRODUCT_PLATFORM = "14";

    //默认传参H。 H 香港盘，M 马来盘，I 印尼盘，E 欧洲盘
    public static final String ODD_F_TYPE = "H";

    //产品的币种
    public static final String PRODUCT_CURRENCY = "CNY";

    //产品的语言
    public static final String PRODUCT_LANGUAGE = "CN";

    //产品的预定字符串
    public static final String PRODUCT_RESERVE = "e03tgbrdx";

    //用户账号的密码多次输入错误，账号已被锁定请五分钟后重试或联系客服解锁
    public static final String PRODUCT_PWD_MUL_ERROR = "W00005";

    //进入游戏加密秘钥
    public static final String PRODUCT_ENCRYPT_KEY = "jhs#%!fde";

    //进入游戏解密秘钥
    public static final String PRODUCT_DECODE_KEY = "cca619fb";

    //进入游戏URL地址配置第一步
    public static final String PRODUCT_ENTER_GAME_URL_1 = "doBusiness.do";

    //进入游戏URL地址配置第2步
    public static final String PRODUCT_ENTER_GAME_URL_2 = "transferAsyncNewApi.do";

    //进入游戏URL地址配置第3步
    public static final String PRODUCT_ENTER_GAME_URL_3 = "forwardGame.do";

    //flurry统计的Key
    public static final String FLURRY_KEY= "X3C8TSP8KWGPS4WG47GS";

    //官网渠道id 100056506900
    public static final String CHANNEL_ID = "";

    //用户的别名
    public static final String USERNAME_ALIAS = "alias";

    //用户是否自己退出
    public static final String USERNAME_LOGOUT = "username_logout";

    //用户的登录状态  1登录成功 0 未登录
    public static final String USERNAME_LOGIN_STATUS = "username_login_status";

    //用户的登录账号
    public static final String USERNAME_LOGIN_ACCOUNT = "username_login_account";


    //用户的试玩账号
    public static final String USERNAME_LOGIN_DEMO = "username_login_demo";

    //用户的登录密码
    public static final String USERNAME_LOGIN_PWD = "username_login_pwd";

    //用户的客服地址
    public static final String USERNAME_SERVICE_URL = "username_service_url";

    //用户的客服QQ
    public static final String USERNAME_SERVICE_URL_QQ = "username_service_url_qq";

    //用户的客服QQ
    public static final String USERNAME_SERVICE_URL_WECHAT = "username_service_url_wechat";

    //用户的默认客服地址
    public static final String USERNAME_SERVICE_DEFAULT_URL = "https://ent-16.chatnow.mstatik.com/dist/standalone.html?eid=61033";


    //用户的是否绑卡
    public static final String USERNAME_BIND_CARD = "username_bind_card";


    //用户登录信息
    public static final String USERNAME_LOGIN_INFO = "username_login_info";

    public static final String KY_DEMO_URL = "KY_DEMO_URL";
    public static final String LY_DEMO_URL = "LY_DEMO_URL";
    public static final String AV_DEMO_URL = "AV_DEMO_URL";
    public static final String HG_DEMO_URL = "HG_DEMO_URL";
    public static final String VG_DEMO_URL = "VG_DEMO_URL";

    //首页banner
    public static final String USERNAME_HOME_BANNER = "username_home_banner";


    //首页notice
    public static final String USERNAME_HOME_NOTICE = "username_home_notice";



    //CP首页notice
    public static final String USERNAME_CP_HOME_NOTICE = "username_cp_home_notice";

    //用户当前的状态
    public static final String USERNAME_CURRENT_STATE = "username_current_state";

    //用户的最小投注额
    public static final String USERNAME_BUY_MIN = "username_buy_min";

    //用户的最大投注额
    public static final String USERNAME_BUY_MAX = "username_buy_max";

    //用户的剩余的金额
    public static final String USERNAME_REMAIN_MONEY = "username_remain_money";

    //用户的彩票地址
    public static final String USERNAME_CP_URL = "username_cp_url";


    //用户的棋牌地址
    public static final String USERNAME_QIPAI_URL = "username_qipai_url";


    //用户的皇冠棋牌地址
    public static final String USERNAME_HG_QIPAI_URL = "username_hg_qipai_url";


    //用户的vg棋牌地址
    public static final String USERNAME_VG_QIPAI_URL = "username_vg_qipai_url";


    //用户的Ly棋牌地址
    public static final String USERNAME_LY_QIPAI_URL = "username_ly_qipai_url";


    public static final String  USERNAME_AVIA_QIPAI_URL  = "username_avia_qipai_url";

    //用户的红包地址
    public static final String USERNAME_GIFT_URL = "username_gift_url";

    //用户的彩票用户信息
    public static final String USERNAME_CP_INFORM = "username_cp_inform";

    public static final String  USER_ACTION_SCREEN_OFF = "user_action_screen_off";

    public static final String  USER_CURRENT_POSITION  = "user_current_position";


    //用户的登录状态  后的用户名称
    public static final String USERNAME_LOGIN_USERNAME = "username_login_username";
    //用户的登录状态  后的金额
    public static final String USERNAME_LOGIN_MONEY = "username_login_money";

    //用户的登录状态  后的banner
    public static final String USERNAME_LOGIN_BANNER = "username_login_banner";


    //用户的登录状态  用户名
    public static final String USERNAME_LOGIN_NAME = "username_login_name";

    //用户的初始化下注的选中状态
    public static final String USERNAME_AUTO_ADD = "username_auto_add";

    //体育维护状态的日志
    public static final String USERNAME_SPORT_MAINTAIN = "username_sport_maintain";

    //棋牌维护状态的日志
    public static final String USERNAME_KY_MAINTAIN = "username_ky_maintain";

    //棋牌维护状态的日志
    public static final String USERNAME_HG_MAINTAIN = "username_hg_maintain";


    //棋牌维护状态的日志
    public static final String USERNAME_VG_MAINTAIN = "username_vg_maintain";


    //棋牌维护状态的日志
    public static final String USERNAME_LY_MAINTAIN = "username_ly_maintain";

    //棋牌维护状态的日志
    public static final String USERNAME_AVIA_MAINTAIN = "username_avia_maintain";

    //彩票维护状态的日志
    public static final String USERNAME_LOTTERY_MAINTAIN = "username_lottery_maintain";

    //视讯维护状态的日志
    public static final String USERNAME_VIDEO_MAINTAIN = "username_video_maintain";

    //电子维护状态的日志
    public static final String USERNAME_GAME_MAINTAIN = "username_game_maintain";

    //红包金额的日志
    public static final String DOWNLOAD_APP_GIFT_GOLD = "download_app_gift_gold";

    //充值金额
    public static final String DOWNLOAD_APP_GIFT_DEPOSIT = "download_app_gift_deposit";
}
