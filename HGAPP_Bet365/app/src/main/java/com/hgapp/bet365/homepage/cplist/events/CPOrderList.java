package com.hgapp.bet365.homepage.cplist.events;

import android.os.Parcel;
import android.os.Parcelable;

public class CPOrderList implements Parcelable {
    public String position;
    public String gid;
    public String gName;
    public String rate;
    public String otherName;


    public CPOrderList(String position, String gid, String gName, String rate,String otherName) {
        this.position = position;
        this.gid = gid;
        this.gName = gName;
        this.rate = rate;
        this.otherName = otherName;
    }

    public String getOtherName() {
        return otherName;
    }

    public void setOtherName(String otherName) {
        this.otherName = otherName;
    }

    public String getPosition() {
        return position;
    }

    public void setPosition(String position) {
        this.position = position;
    }

    public String getGid() {
        return gid;
    }

    public void setGid(String gid) {
        this.gid = gid;
    }

    public String getgName() {
        return gName;
    }

    public void setgName(String gName) {
        this.gName = gName;
    }

    public String getRate() {
        return rate;
    }

    public void setRate(String rate) {
        this.rate = rate;
    }

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel dest, int flags) {
        dest.writeString(this.otherName);
        dest.writeString(this.position);
        dest.writeString(this.gid);
        dest.writeString(this.gName);
        dest.writeString(this.rate);
    }

    protected CPOrderList(Parcel in) {
        this.otherName = in.readString();
        this.position = in.readString();
        this.gid = in.readString();
        this.gName = in.readString();
        this.rate = in.readString();
    }

    public static final Creator<CPOrderList> CREATOR = new Creator<CPOrderList>() {
        @Override
        public CPOrderList createFromParcel(Parcel source) {
            return new CPOrderList(source);
        }

        @Override
        public CPOrderList[] newArray(int size) {
            return new CPOrderList[size];
        }
    };
}
