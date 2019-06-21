package com.qpweb.a01.data;

import android.os.Parcel;
import android.os.Parcelable;

public class LoginResult implements Parcelable {

    /**
     * ID : 81318
     * Oid : d1cb8a90e53b012b23d3ra4
     * Phone :
     * UserName : daniel111
     * AvatarId : null
     * NickName : 幽雪沫儿jxbx
     * PersonalizedSignature : 这家伙很懒，什么都没有留下vg
     * PassWord : qqqaaa
     * Agents : ddm423
     * Money : 2
     * Alias :
     * isTest : 0
     * isBindCard : 0
     * LoginTime : 2019-06-17 12:15:53
     * lastLoginTime : 2019-06-17 00:10:13
     * promotion_code : null
     * OnlineServer : https://e-60193.chatnow.meiqia.com/dist/standalone.html
     */

    private String ID;
    private String Oid;
    private String Phone;
    private String UserName;
    private String AvatarId;
    private String NickName;
    private String PersonalizedSignature;
    private String PassWord;
    private String Agents;
    private String Money;
    private String Alias;
    private String isTest;
    private String isBindCard;
    private String LoginTime;
    private String lastLoginTime;
    private String promotion_code;
    private String OnlineServer;
    private String haveLyAccount;//1 有乐游账号  0 没有乐游账号

    public String getID() {
        return ID;
    }

    public void setID(String ID) {
        this.ID = ID;
    }

    public String getOid() {
        return Oid;
    }

    public void setOid(String Oid) {
        this.Oid = Oid;
    }

    public String getPhone() {
        return Phone;
    }

    public void setPhone(String Phone) {
        this.Phone = Phone;
    }

    public String getUserName() {
        return UserName;
    }

    public void setUserName(String UserName) {
        this.UserName = UserName;
    }

    public String getAvatarId() {
        return AvatarId;
    }

    public void setAvatarId(String AvatarId) {
        this.AvatarId = AvatarId;
    }

    public String getNickName() {
        return NickName;
    }

    public void setNickName(String NickName) {
        this.NickName = NickName;
    }

    public String getPersonalizedSignature() {
        return PersonalizedSignature;
    }

    public void setPersonalizedSignature(String PersonalizedSignature) {
        this.PersonalizedSignature = PersonalizedSignature;
    }

    public String getPassWord() {
        return PassWord;
    }

    public void setPassWord(String PassWord) {
        this.PassWord = PassWord;
    }

    public String getAgents() {
        return Agents;
    }

    public void setAgents(String Agents) {
        this.Agents = Agents;
    }

    public String getMoney() {
        return Money;
    }

    public void setMoney(String Money) {
        this.Money = Money;
    }

    public String getAlias() {
        return Alias;
    }

    public void setAlias(String Alias) {
        this.Alias = Alias;
    }

    public String getIsTest() {
        return isTest;
    }

    public void setIsTest(String isTest) {
        this.isTest = isTest;
    }

    public String getIsBindCard() {
        return isBindCard;
    }

    public void setIsBindCard(String isBindCard) {
        this.isBindCard = isBindCard;
    }

    public String getLoginTime() {
        return LoginTime;
    }

    public void setLoginTime(String LoginTime) {
        this.LoginTime = LoginTime;
    }

    public String getLastLoginTime() {
        return lastLoginTime;
    }

    public void setLastLoginTime(String lastLoginTime) {
        this.lastLoginTime = lastLoginTime;
    }

    public Object getPromotion_code() {
        return promotion_code;
    }

    public void setPromotion_code(String promotion_code) {
        this.promotion_code = promotion_code;
    }

    public String getOnlineServer() {
        return OnlineServer;
    }

    public void setOnlineServer(String OnlineServer) {
        this.OnlineServer = OnlineServer;
    }

    public String getHaveLyAccount() {
        return haveLyAccount;
    }

    public void setHaveLyAccount(String haveLyAccount) {
        this.haveLyAccount = haveLyAccount;
    }

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel dest, int flags) {
        dest.writeString(this.ID);
        dest.writeString(this.Oid);
        dest.writeString(this.Phone);
        dest.writeString(this.UserName);
        dest.writeString(this.AvatarId);
        dest.writeString(this.NickName);
        dest.writeString(this.PersonalizedSignature);
        dest.writeString(this.PassWord);
        dest.writeString(this.Agents);
        dest.writeString(this.Money);
        dest.writeString(this.Alias);
        dest.writeString(this.isTest);
        dest.writeString(this.isBindCard);
        dest.writeString(this.LoginTime);
        dest.writeString(this.lastLoginTime);
        dest.writeString(this.promotion_code);
        dest.writeString(this.OnlineServer);
        dest.writeString(this.haveLyAccount);
    }

    public LoginResult() {
    }

    protected LoginResult(Parcel in) {
        this.ID = in.readString();
        this.Oid = in.readString();
        this.Phone = in.readString();
        this.UserName = in.readString();
        this.AvatarId = in.readString();
        this.NickName = in.readString();
        this.PersonalizedSignature = in.readString();
        this.PassWord = in.readString();
        this.Agents = in.readString();
        this.Money = in.readString();
        this.Alias = in.readString();
        this.isTest = in.readString();
        this.isBindCard = in.readString();
        this.LoginTime = in.readString();
        this.lastLoginTime = in.readString();
        this.promotion_code = in.readString();
        this.OnlineServer = in.readString();
        this.haveLyAccount = in.readString();
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
