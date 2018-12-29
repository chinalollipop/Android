package com.qpweb.a01.data;

import android.os.Parcel;
import android.os.Parcelable;

public class LoginResult implements Parcelable {

    /**
     * Oid : c9d8a4807901e02c31afra0
     * UserName : daniel00
     * Agents : jack001
     * Money : 0
     * Alias :
     * isTest : 0
     * isBindCard : 0
     * LoginTime : 2018-12-28 17:09:00
     * lastLoginTime : 首次登录
     * promotion_code : null
     */

    private String Oid;
    private String UserName;
    private String Agents;
    private String Money;
    private String Alias;
    private String isTest;
    private String isBindCard;
    private String LoginTime;
    private String lastLoginTime;
    private String promotion_code;

    public String getOid() {
        return Oid;
    }

    public void setOid(String Oid) {
        this.Oid = Oid;
    }

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

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel dest, int flags) {
        dest.writeString(this.Oid);
        dest.writeString(this.UserName);
        dest.writeString(this.Agents);
        dest.writeString(this.Money);
        dest.writeString(this.Alias);
        dest.writeString(this.isTest);
        dest.writeString(this.isBindCard);
        dest.writeString(this.LoginTime);
        dest.writeString(this.lastLoginTime);
        dest.writeString(this.promotion_code);
    }

    public LoginResult() {
    }

    protected LoginResult(Parcel in) {
        this.Oid = in.readString();
        this.UserName = in.readString();
        this.Agents = in.readString();
        this.Money = in.readString();
        this.Alias = in.readString();
        this.isTest = in.readString();
        this.isBindCard = in.readString();
        this.LoginTime = in.readString();
        this.lastLoginTime = in.readString();
        this.promotion_code = in.readString();
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
