package com.hgapp.m8.data;

public class LoginResult {


    /**
     * UserName : lincoin06
     * Agents : ddm423
     * LoginTime : 2018-07-07 05:45:06
     * birthday : 2018-03-13 00:00:00
     * Money : 46941.6000
     * Phone :
     * test_flag : 0
     */

    private String UserName;
    private String Agents;
    private String LoginTime;
    private String birthday;
    private String Money;
    private String Phone;
    private String test_flag;
    private String BetMinMoney;
    private String BetMaxMoney;
    private String E_Mail;
    private String DOWNLOAD_APP_GIFT_GOLD;
    private String DOWNLOAD_APP_GIFT_DEPOSIT;

    /**
     * Money : 35308581
     * DOWNLOAD_APP_GIFT_GOLD : 108
     * DOWNLOAD_APP_GIFT_DEPOSIT : 1000
     * membermessage : {"mem_message":"HG0086诚邀共享【12.30】元旦暖冬彩金 HG0086钜惠盛典海量彩金不断，详情登陆网站查看！APP用户请点击优惠活动进行查看！","mcou":1}
     */

    private MembermessageBean membermessage;
    /**
     * chess_demo_url : {"KY_DEMO_URL":"http://play.ky206.com/jump.do","LY_DEMO_URL":"https://demo.leg666.com","HG_DEMO_URL":"/hgqp/index.php?uid=6df17071e63ed737cd17ra1&flag=test&tip=app","VG_DEMO_URL":"/vgqp/index.php?uid=6df17071e63ed737cd17ra1&flag=test&tip=app"}
     */

    private ChessDemoUrlBean chess_demo_url;

    public LoginResult() {
    }

    public LoginResult(String userName, String agents, String loginTime, String birthday, String money, String phone, String test_flag, String oid, String alias, String userid, String noteMessage) {
        UserName = userName;
        Agents = agents;
        LoginTime = loginTime;
        this.birthday = birthday;
        Money = money;
        Phone = phone;
        this.test_flag = test_flag;
        Oid = oid;
        Alias = alias;
        this.userid = userid;
        this.noteMessage = noteMessage;
    }


    /**
     * Oid : a5e5be5b58e516b03aacra9
     * Alias : 张三
     */

    private String Oid;
    private String userid;
    private String noteMessage;
    private String Alias;
    private String BindCard_Flag;

    public String getUserName() {
        return UserName;
    }

    public void setUserName(String UserName) {
        this.UserName = UserName;
    }

    public String getAgents() {
        return Agents;
    }

    public void setAgents(String Agents) {
        this.Agents = Agents;
    }

    public String getLoginTime() {
        return LoginTime;
    }

    public void setLoginTime(String LoginTime) {
        this.LoginTime = LoginTime;
    }

    public String getBirthday() {
        return birthday;
    }

    public void setBirthday(String birthday) {
        this.birthday = birthday;
    }

    public String getMoney() {
        return Money;
    }

    public void setMoney(String Money) {
        this.Money = Money;
    }

    public String getPhone() {
        return Phone;
    }

    public void setPhone(String Phone) {
        this.Phone = Phone;
    }

    public String getTest_flag() {
        return test_flag;
    }

    public void setTest_flag(String test_flag) {
        this.test_flag = test_flag;
    }

    public String getOid() {
        return Oid;
    }

    public void setOid(String Oid) {
        this.Oid = Oid;
    }

    public String getUserid() {
        return userid;
    }

    public void setUserid(String userid) {
        this.userid = userid;
    }

    public String getNoteMessage() {
        return noteMessage;
    }

    public void setNoteMessage(String noteMessage) {
        this.noteMessage = noteMessage;
    }

    public String getAlias() {
        return Alias;
    }

    public void setAlias(String Alias) {
        this.Alias = Alias;
    }

    public String getBindCard_Flag() {
        return BindCard_Flag;
    }

    public void setBindCard_Flag(String BindCard_Flag) {
        this.BindCard_Flag = BindCard_Flag;
    }


    public String getBetMinMoney() {
        return BetMinMoney;
    }

    public void setBetMinMoney(String betMinMoney) {
        BetMinMoney = betMinMoney;
    }

    public String getBetMaxMoney() {
        return BetMaxMoney;
    }

    public void setBetMaxMoney(String betMaxMoney) {
        BetMaxMoney = betMaxMoney;
    }

    public String getE_Mail() {
        return E_Mail;
    }

    public void setE_Mail(String e_Mail) {
        E_Mail = e_Mail;
    }

    public String getDOWNLOAD_APP_GIFT_GOLD() {
        return DOWNLOAD_APP_GIFT_GOLD;
    }

    public void setDOWNLOAD_APP_GIFT_GOLD(String DOWNLOAD_APP_GIFT_GOLD) {
        this.DOWNLOAD_APP_GIFT_GOLD = DOWNLOAD_APP_GIFT_GOLD;
    }

    public String getDOWNLOAD_APP_GIFT_DEPOSIT() {
        return DOWNLOAD_APP_GIFT_DEPOSIT;
    }

    public void setDOWNLOAD_APP_GIFT_DEPOSIT(String DOWNLOAD_APP_GIFT_DEPOSIT) {
        this.DOWNLOAD_APP_GIFT_DEPOSIT = DOWNLOAD_APP_GIFT_DEPOSIT;
    }

    @Override
    public String toString() {
        return "LoginResult{" +
                "UserName='" + UserName + '\'' +
                ", Agents='" + Agents + '\'' +
                ", LoginTime='" + LoginTime + '\'' +
                ", birthday='" + birthday + '\'' +
                ", Money='" + Money + '\'' +
                ", Phone='" + Phone + '\'' +
                ", test_flag='" + test_flag + '\'' +
                ", BetMinMoney='" + BetMinMoney + '\'' +
                ", BetMaxMoney='" + BetMaxMoney + '\'' +
                ", E_Mail='" + E_Mail + '\'' +
                ", DOWNLOAD_APP_GIFT_GOLD='" + DOWNLOAD_APP_GIFT_GOLD + '\'' +
                ", DOWNLOAD_APP_GIFT_DEPOSIT='" + DOWNLOAD_APP_GIFT_DEPOSIT + '\'' +
                ", membermessage=" + membermessage +
                ", chess_demo_url=" + chess_demo_url +
                ", Oid='" + Oid + '\'' +
                ", userid='" + userid + '\'' +
                ", noteMessage='" + noteMessage + '\'' +
                ", Alias='" + Alias + '\'' +
                ", BindCard_Flag='" + BindCard_Flag + '\'' +
                '}';
    }

    public MembermessageBean getMembermessage() {
        return membermessage;
    }

    public void setMembermessage(MembermessageBean membermessage) {
        this.membermessage = membermessage;
    }

    public ChessDemoUrlBean getChess_demo_url() {
        return chess_demo_url;
    }

    public void setChess_demo_url(ChessDemoUrlBean chess_demo_url) {
        this.chess_demo_url = chess_demo_url;
    }

    public static class MembermessageBean {
        /**
         * mem_message : HG0086诚邀共享【12.30】元旦暖冬彩金 HG0086钜惠盛典海量彩金不断，详情登陆网站查看！APP用户请点击优惠活动进行查看！
         * mcou : 1
         */

        private String mem_message;
        private int mcou;

        public String getMem_message() {
            return mem_message;
        }

        public void setMem_message(String mem_message) {
            this.mem_message = mem_message;
        }

        public int getMcou() {
            return mcou;
        }

        public void setMcou(int mcou) {
            this.mcou = mcou;
        }
    }

    public static class ChessDemoUrlBean {
        /**
         * KY_DEMO_URL : http://play.ky206.com/jump.do
         * LY_DEMO_URL : https://demo.leg666.com
         * HG_DEMO_URL : /hgqp/index.php?uid=6df17071e63ed737cd17ra1&flag=test&tip=app
         * VG_DEMO_URL : /vgqp/index.php?uid=6df17071e63ed737cd17ra1&flag=test&tip=app
         */

        private String ky_demo_url;
        private String ly_demo_url;
        private String hg_demo_url;
        private String vg_demo_url;

        public String getKy_demo_url() {
            return ky_demo_url;
        }

        public void setKy_demo_url(String ky_demo_url) {
            this.ky_demo_url = ky_demo_url;
        }

        public String getLy_demo_url() {
            return ly_demo_url;
        }

        public void setLy_demo_url(String ly_demo_url) {
            this.ly_demo_url = ly_demo_url;
        }

        public String getHg_demo_url() {
            return hg_demo_url;
        }

        public void setHg_demo_url(String hg_demo_url) {
            this.hg_demo_url = hg_demo_url;
        }

        public String getVg_demo_url() {
            return vg_demo_url;
        }

        public void setVg_demo_url(String vg_demo_url) {
            this.vg_demo_url = vg_demo_url;
        }
    }
}
