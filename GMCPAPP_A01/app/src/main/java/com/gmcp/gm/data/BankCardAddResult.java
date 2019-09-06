package com.gmcp.gm.data;

import android.os.Parcel;
import android.os.Parcelable;

public class BankCardAddResult implements Parcelable {

    /**
     * bank : 中国工商银行
     * bank_id : 1
     * branch : 深圳前海湾支行
     * account_name : 打你哦
     * account : 6666666666666666
     * account_confirmation : 6666666666666666
     */

    private String bank;
    private String bank_id;
    private String branch;
    private String account_name;
    private String account;
    private String account_confirmation;

    public String getBank() {
        return bank;
    }

    public void setBank(String bank) {
        this.bank = bank;
    }

    public String getBank_id() {
        return bank_id;
    }

    public void setBank_id(String bank_id) {
        this.bank_id = bank_id;
    }

    public String getBranch() {
        return branch;
    }

    public void setBranch(String branch) {
        this.branch = branch;
    }

    public String getAccount_name() {
        return account_name;
    }

    public void setAccount_name(String account_name) {
        this.account_name = account_name;
    }

    public String getAccount() {
        return account;
    }

    public void setAccount(String account) {
        this.account = account;
    }

    public String getAccount_confirmation() {
        return account_confirmation;
    }

    public void setAccount_confirmation(String account_confirmation) {
        this.account_confirmation = account_confirmation;
    }

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel dest, int flags) {
        dest.writeString(this.bank);
        dest.writeString(this.bank_id);
        dest.writeString(this.branch);
        dest.writeString(this.account_name);
        dest.writeString(this.account);
        dest.writeString(this.account_confirmation);
    }

    public BankCardAddResult() {
    }

    protected BankCardAddResult(Parcel in) {
        this.bank = in.readString();
        this.bank_id = in.readString();
        this.branch = in.readString();
        this.account_name = in.readString();
        this.account = in.readString();
        this.account_confirmation = in.readString();
    }

    public static final Parcelable.Creator<BankCardAddResult> CREATOR = new Parcelable.Creator<BankCardAddResult>() {
        @Override
        public BankCardAddResult createFromParcel(Parcel source) {
            return new BankCardAddResult(source);
        }

        @Override
        public BankCardAddResult[] newArray(int size) {
            return new BankCardAddResult[size];
        }
    };
}
