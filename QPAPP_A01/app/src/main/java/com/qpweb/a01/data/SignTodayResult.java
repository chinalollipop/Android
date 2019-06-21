package com.qpweb.a01.data;

import android.os.Parcel;
import android.os.Parcelable;

public class SignTodayResult implements Parcelable {


    /**
     * current_week_day : 4
     * sign_in_config : {"week_1":1,"week_2":1,"week_3":2,"week_4":2,"week_5":3,"week_6":3,"week_7":5}
     * sign_days_1 : 0
     * total_money : 0
     * sign_days_2 : 0
     * sign_days_3 : 0
     * sign_days_4 : 0
     * sign_days_5 : 0
     * sign_days_6 : 0
     * sign_days_0 : 0
     */

    private String current_week_day;
    private String sign_days_1;
    private String total_money;
    private String sign_days_2;
    private String sign_days_3;
    private String sign_days_4;
    private String sign_days_5;
    private String sign_days_6;
    private String sign_days_0;

    public String getCurrent_week_day() {
        return current_week_day;
    }

    public void setCurrent_week_day(String current_week_day) {
        this.current_week_day = current_week_day;
    }


    public String getSign_days_1() {
        return sign_days_1;
    }

    public void setSign_days_1(String sign_days_1) {
        this.sign_days_1 = sign_days_1;
    }

    public String getTotal_money() {
        return total_money;
    }

    public void setTotal_money(String total_money) {
        this.total_money = total_money;
    }

    public String getSign_days_2() {
        return sign_days_2;
    }

    public void setSign_days_2(String sign_days_2) {
        this.sign_days_2 = sign_days_2;
    }

    public String getSign_days_3() {
        return sign_days_3;
    }

    public void setSign_days_3(String sign_days_3) {
        this.sign_days_3 = sign_days_3;
    }

    public String getSign_days_4() {
        return sign_days_4;
    }

    public void setSign_days_4(String sign_days_4) {
        this.sign_days_4 = sign_days_4;
    }

    public String getSign_days_5() {
        return sign_days_5;
    }

    public void setSign_days_5(String sign_days_5) {
        this.sign_days_5 = sign_days_5;
    }

    public String getSign_days_6() {
        return sign_days_6;
    }

    public void setSign_days_6(String sign_days_6) {
        this.sign_days_6 = sign_days_6;
    }

    public String getSign_days_0() {
        return sign_days_0;
    }

    public void setSign_days_0(String sign_days_0) {
        this.sign_days_0 = sign_days_0;
    }

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel dest, int flags) {
        dest.writeString(this.current_week_day);
        dest.writeString(this.sign_days_1);
        dest.writeString(this.total_money);
        dest.writeString(this.sign_days_2);
        dest.writeString(this.sign_days_3);
        dest.writeString(this.sign_days_4);
        dest.writeString(this.sign_days_5);
        dest.writeString(this.sign_days_6);
        dest.writeString(this.sign_days_0);
    }

    public SignTodayResult() {
    }

    protected SignTodayResult(Parcel in) {
        this.current_week_day = in.readString();
        this.sign_days_1 = in.readString();
        this.total_money = in.readString();
        this.sign_days_2 = in.readString();
        this.sign_days_3 = in.readString();
        this.sign_days_4 = in.readString();
        this.sign_days_5 = in.readString();
        this.sign_days_6 = in.readString();
        this.sign_days_0 = in.readString();
    }

    public static final Parcelable.Creator<SignTodayResult> CREATOR = new Parcelable.Creator<SignTodayResult>() {
        @Override
        public SignTodayResult createFromParcel(Parcel source) {
            return new SignTodayResult(source);
        }

        @Override
        public SignTodayResult[] newArray(int size) {
            return new SignTodayResult[size];
        }
    };
}
