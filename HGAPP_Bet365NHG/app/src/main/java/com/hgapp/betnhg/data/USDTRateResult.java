package com.hgapp.betnhg.data;

import android.os.Parcel;
import android.os.Parcelable;

public class USDTRateResult implements Parcelable {
    private String usdt_rate;
    private String usdt_amount;
    private String type;
    private String tutorial_url;
    private String withdrawals_usdt_rate;
    private String Usdt_Address;
    private String Usdt_Address_hide;
    private String jiaoyisuo;
    public String getUsdt_Address_hide() {
        return Usdt_Address_hide;
    }

    public void setUsdt_Address_hide(String usdt_Address_hide) {
        Usdt_Address_hide = usdt_Address_hide;
    }

    public String getUsdt_rate() {
        return usdt_rate;
    }

    public void setUsdt_rate(String usdt_rate) {
        this.usdt_rate = usdt_rate;
    }

    public String getUsdt_amount() {
        return usdt_amount;
    }

    public void setUsdt_amount(String usdt_amount) {
        this.usdt_amount = usdt_amount;
    }

    public String getType() {
        return type;
    }

    public void setType(String type) {
        this.type = type;
    }

    public String getTutorial_url() {
        return tutorial_url;
    }

    public void setTutorial_url(String tutorial_url) {
        this.tutorial_url = tutorial_url;
    }

    public String getWithdrawals_usdt_rate() {
        return withdrawals_usdt_rate;
    }

    public void setWithdrawals_usdt_rate(String withdrawals_usdt_rate) {
        this.withdrawals_usdt_rate = withdrawals_usdt_rate;
    }

    public String getUsdt_Address() {
        return Usdt_Address;
    }

    public void setUsdt_Address(String usdt_Address) {
        Usdt_Address = usdt_Address;
    }

    public String getJiaoyisuo() {
        return jiaoyisuo;
    }

    public void setJiaoyisuo(String jiaoyisuo) {
        this.jiaoyisuo = jiaoyisuo;
    }

    public USDTRateResult() {
    }

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel dest, int flags) {
        dest.writeString(this.usdt_rate);
        dest.writeString(this.usdt_amount);
        dest.writeString(this.type);
        dest.writeString(this.tutorial_url);
        dest.writeString(this.withdrawals_usdt_rate);
        dest.writeString(this.Usdt_Address);
        dest.writeString(this.Usdt_Address_hide);
        dest.writeString(this.jiaoyisuo);
    }

    protected USDTRateResult(Parcel in) {
        this.usdt_rate = in.readString();
        this.usdt_amount = in.readString();
        this.type = in.readString();
        this.tutorial_url = in.readString();
        this.withdrawals_usdt_rate = in.readString();
        this.Usdt_Address = in.readString();
        this.Usdt_Address_hide = in.readString();
        this.jiaoyisuo = in.readString();
    }

    public static final Creator<USDTRateResult> CREATOR = new Creator<USDTRateResult>() {
        @Override
        public USDTRateResult createFromParcel(Parcel source) {
            return new USDTRateResult(source);
        }

        @Override
        public USDTRateResult[] newArray(int size) {
            return new USDTRateResult[size];
        }
    };
}