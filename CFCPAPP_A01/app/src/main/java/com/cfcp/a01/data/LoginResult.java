package com.cfcp.a01.data;

import android.os.Parcel;
import android.os.Parcelable;

public class LoginResult implements Parcelable {


    /**
     * id : 33991
     * is_agent : 0
     * username : lincoin01
     * parent_id : 882
     * forefather_ids : 1,882
     * parent : admin8888
     * forefathers : admin888,admin8888
     * account_id : 32516
     * prize_group : 1950
     * blocked : 0
     * portrait_code : 1
     * name :
     * nickname : lincoin01
     * email :
     * mobile :
     * is_tester : 0
     * qq :
     * skype :
     * bet_coefficient :
     * login_ip : 192.168.1.32
     * signin_at : 2019-01-19 09:56:53
     * register_at : 2019-01-13 18:46:11
     * fund_password_exist : false
     * abalance : 0.0000
     * token : a420fab883bd62239138aa469bbfdd75760f02b1
     */

    private int id;
    private int is_agent;
    private String username;
    private int parent_id;
    private String forefather_ids;
    private String parent;
    private String forefathers;
    private int account_id;
    private String prize_group;
    private int blocked;
    private int portrait_code;
    private String name;
    private String nickname;
    private String email;
    private String mobile;
    private int is_tester;
    private String qq;
    private String skype;
    private String bet_coefficient;
    private String login_ip;
    private String signin_at;
    private String register_at;
    private boolean fund_password_exist;
    private String abalance;
    private String token;

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public int getIs_agent() {
        return is_agent;
    }

    public void setIs_agent(int is_agent) {
        this.is_agent = is_agent;
    }

    public String getUsername() {
        return username;
    }

    public void setUsername(String username) {
        this.username = username;
    }

    public int getParent_id() {
        return parent_id;
    }

    public void setParent_id(int parent_id) {
        this.parent_id = parent_id;
    }

    public String getForefather_ids() {
        return forefather_ids;
    }

    public void setForefather_ids(String forefather_ids) {
        this.forefather_ids = forefather_ids;
    }

    public String getParent() {
        return parent;
    }

    public void setParent(String parent) {
        this.parent = parent;
    }

    public String getForefathers() {
        return forefathers;
    }

    public void setForefathers(String forefathers) {
        this.forefathers = forefathers;
    }

    public int getAccount_id() {
        return account_id;
    }

    public void setAccount_id(int account_id) {
        this.account_id = account_id;
    }

    public String getPrize_group() {
        return prize_group;
    }

    public void setPrize_group(String prize_group) {
        this.prize_group = prize_group;
    }

    public int getBlocked() {
        return blocked;
    }

    public void setBlocked(int blocked) {
        this.blocked = blocked;
    }

    public int getPortrait_code() {
        return portrait_code;
    }

    public void setPortrait_code(int portrait_code) {
        this.portrait_code = portrait_code;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getNickname() {
        return nickname;
    }

    public void setNickname(String nickname) {
        this.nickname = nickname;
    }

    public String getEmail() {
        return email;
    }

    public void setEmail(String email) {
        this.email = email;
    }

    public String getMobile() {
        return mobile;
    }

    public void setMobile(String mobile) {
        this.mobile = mobile;
    }

    public int getIs_tester() {
        return is_tester;
    }

    public void setIs_tester(int is_tester) {
        this.is_tester = is_tester;
    }

    public String getQq() {
        return qq;
    }

    public void setQq(String qq) {
        this.qq = qq;
    }

    public String getSkype() {
        return skype;
    }

    public void setSkype(String skype) {
        this.skype = skype;
    }

    public String getBet_coefficient() {
        return bet_coefficient;
    }

    public void setBet_coefficient(String bet_coefficient) {
        this.bet_coefficient = bet_coefficient;
    }

    public String getLogin_ip() {
        return login_ip;
    }

    public void setLogin_ip(String login_ip) {
        this.login_ip = login_ip;
    }

    public String getSignin_at() {
        return signin_at;
    }

    public void setSignin_at(String signin_at) {
        this.signin_at = signin_at;
    }

    public String getRegister_at() {
        return register_at;
    }

    public void setRegister_at(String register_at) {
        this.register_at = register_at;
    }

    public boolean isFund_password_exist() {
        return fund_password_exist;
    }

    public void setFund_password_exist(boolean fund_password_exist) {
        this.fund_password_exist = fund_password_exist;
    }

    public String getAbalance() {
        return abalance;
    }

    public void setAbalance(String abalance) {
        this.abalance = abalance;
    }

    public String getToken() {
        return token;
    }

    public void setToken(String token) {
        this.token = token;
    }

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel dest, int flags) {
        dest.writeInt(this.id);
        dest.writeInt(this.is_agent);
        dest.writeString(this.username);
        dest.writeInt(this.parent_id);
        dest.writeString(this.forefather_ids);
        dest.writeString(this.parent);
        dest.writeString(this.forefathers);
        dest.writeInt(this.account_id);
        dest.writeString(this.prize_group);
        dest.writeInt(this.blocked);
        dest.writeInt(this.portrait_code);
        dest.writeString(this.name);
        dest.writeString(this.nickname);
        dest.writeString(this.email);
        dest.writeString(this.mobile);
        dest.writeInt(this.is_tester);
        dest.writeString(this.qq);
        dest.writeString(this.skype);
        dest.writeString(this.bet_coefficient);
        dest.writeString(this.login_ip);
        dest.writeString(this.signin_at);
        dest.writeString(this.register_at);
        dest.writeByte(this.fund_password_exist ? (byte) 1 : (byte) 0);
        dest.writeString(this.abalance);
        dest.writeString(this.token);
    }

    public LoginResult() {
    }

    protected LoginResult(Parcel in) {
        this.id = in.readInt();
        this.is_agent = in.readInt();
        this.username = in.readString();
        this.parent_id = in.readInt();
        this.forefather_ids = in.readString();
        this.parent = in.readString();
        this.forefathers = in.readString();
        this.account_id = in.readInt();
        this.prize_group = in.readString();
        this.blocked = in.readInt();
        this.portrait_code = in.readInt();
        this.name = in.readString();
        this.nickname = in.readString();
        this.email = in.readString();
        this.mobile = in.readString();
        this.is_tester = in.readInt();
        this.qq = in.readString();
        this.skype = in.readString();
        this.bet_coefficient = in.readString();
        this.login_ip = in.readString();
        this.signin_at = in.readString();
        this.register_at = in.readString();
        this.fund_password_exist = in.readByte() != 0;
        this.abalance = in.readString();
        this.token = in.readString();
    }

    public static final Parcelable.Creator<LoginResult> CREATOR = new Parcelable.Creator<LoginResult>() {
        @Override
        public LoginResult createFromParcel(Parcel source) {
            return new LoginResult(source);
        }

        @Override
        public LoginResult[] newArray(int size) {
            return new LoginResult[size];
        }
    };
}
