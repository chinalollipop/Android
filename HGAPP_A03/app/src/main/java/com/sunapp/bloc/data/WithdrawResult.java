package com.sunapp.bloc.data;

import android.os.Parcel;
import android.os.Parcelable;

import java.util.ArrayList;
import java.util.List;

public class WithdrawResult implements Parcelable {

    /**
     * UserName : lincoin06
     * Bank_Name : 江苏省农村信用社联合社
     * Bank_Account : 1234123412341234
     * Bank_Address : 江苏
     */

    private String UserName;
    private String Bank_Name;
    private String Bank_Account;
    private String Bank_Address;
    /**
     * owe_bet : 0
     * total_bet : 0
     * bet_list : [{"key":"hg","value":0,"msg":"皇冠体育"},{"key":"cp","value":0,"msg":"体育彩票"},{"key":"ag","value":0,"msg":"AG视讯"},{"key":"ag_dianzi","value":0,"msg":"AG电子"},{"key":"ag_dayu","value":0,"msg":"AG打鱼捕鱼"},{"key":"ky","value":0,"msg":"开元棋盘"},{"key":"hgqp","value":0,"msg":"皇冠棋牌"},{"key":"vgqp","value":0,"msg":"VG棋牌"},{"key":"lyqp","value":0,"msg":"乐游棋牌"},{"key":"mg","value":0,"msg":"MG电子"},{"key":"avia","value":0,"msg":"泛亚电竞"},{"key":"og","value":0,"msg":"OG视讯"},{"key":"mw","value":0,"msg":"MW电子"},{"key":"cq","value":0,"msg":"CQ电子"},{"key":"fg","value":0,"msg":"FG电子"}]
     */

    private String owe_bet;
    private String total_bet;
    private List<BetListBean> bet_list;

    public String getUserName() {
        return UserName;
    }

    public void setUserName(String UserName) {
        this.UserName = UserName;
    }

    public String getBank_Name() {
        return Bank_Name;
    }

    public void setBank_Name(String Bank_Name) {
        this.Bank_Name = Bank_Name;
    }

    public String getBank_Account() {
        return Bank_Account;
    }

    public void setBank_Account(String Bank_Account) {
        this.Bank_Account = Bank_Account;
    }

    public String getBank_Address() {
        return Bank_Address;
    }

    public void setBank_Address(String Bank_Address) {
        this.Bank_Address = Bank_Address;
    }

    public String getOwe_bet() {
        return owe_bet;
    }

    public void setOwe_bet(String owe_bet) {
        this.owe_bet = owe_bet;
    }

    public String getTotal_bet() {
        return total_bet;
    }

    public void setTotal_bet(String total_bet) {
        this.total_bet = total_bet;
    }

    public List<BetListBean> getBet_list() {
        return bet_list;
    }

    public void setBet_list(List<BetListBean> bet_list) {
        this.bet_list = bet_list;
    }

    public static class BetListBean {
        /**
         * key : hg
         * value : 0
         * msg : 皇冠体育
         */

        private String key;
        private String value;
        private String msg;

        public String getKey() {
            return key;
        }

        public void setKey(String key) {
            this.key = key;
        }

        public String getValue() {
            return value;
        }

        public void setValue(String value) {
            this.value = value;
        }

        public String getMsg() {
            return msg;
        }

        public void setMsg(String msg) {
            this.msg = msg;
        }
    }

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel dest, int flags) {
        dest.writeString(this.UserName);
        dest.writeString(this.Bank_Name);
        dest.writeString(this.Bank_Account);
        dest.writeString(this.Bank_Address);
        dest.writeString(this.owe_bet);
        dest.writeString(this.total_bet);
        dest.writeList(this.bet_list);
    }

    public WithdrawResult() {
    }

    protected WithdrawResult(Parcel in) {
        this.UserName = in.readString();
        this.Bank_Name = in.readString();
        this.Bank_Account = in.readString();
        this.Bank_Address = in.readString();
        this.owe_bet = in.readString();
        this.total_bet = in.readString();
        this.bet_list = new ArrayList<BetListBean>();
        in.readList(this.bet_list, BetListBean.class.getClassLoader());
    }

    public static final Parcelable.Creator<WithdrawResult> CREATOR = new Parcelable.Creator<WithdrawResult>() {
        @Override
        public WithdrawResult createFromParcel(Parcel source) {
            return new WithdrawResult(source);
        }

        @Override
        public WithdrawResult[] newArray(int size) {
            return new WithdrawResult[size];
        }
    };
}
