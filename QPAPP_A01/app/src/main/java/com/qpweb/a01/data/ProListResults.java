package com.qpweb.a01.data;

import android.os.Parcel;
import android.os.Parcelable;

public class ProListResults implements Parcelable {
    String time;
    String money;

    public String getTime() {
        return time;
    }

    public void setTime(String time) {
        this.time = time;
    }

    public String getMoney() {
        return money;
    }

    public void setMoney(String money) {
        this.money = money;
    }

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel dest, int flags) {
        dest.writeString(this.time);
        dest.writeString(this.money);
    }

    public ProListResults() {
    }

    protected ProListResults(Parcel in) {
        this.time = in.readString();
        this.money = in.readString();
    }

    public static final Parcelable.Creator<ProListResults> CREATOR = new Parcelable.Creator<ProListResults>() {
        @Override
        public ProListResults createFromParcel(Parcel source) {
            return new ProListResults(source);
        }

        @Override
        public ProListResults[] newArray(int size) {
            return new ProListResults[size];
        }
    };
}
