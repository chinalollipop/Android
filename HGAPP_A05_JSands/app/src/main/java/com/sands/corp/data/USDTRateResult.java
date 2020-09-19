package com.sands.corp.data;

import android.os.Parcel;
import android.os.Parcelable;

public class USDTRateResult implements Parcelable {
    private String usdt_rate;
    private String usdt_amount;
    private String type;
    private String tutorial_url;

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
    }

    public USDTRateResult() {
    }

    protected USDTRateResult(Parcel in) {
        this.usdt_rate = in.readString();
        this.usdt_amount = in.readString();
        this.type = in.readString();
        this.tutorial_url = in.readString();
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
