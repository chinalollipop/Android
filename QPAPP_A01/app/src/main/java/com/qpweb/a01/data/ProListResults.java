package com.qpweb.a01.data;

import android.os.Parcel;
import android.os.Parcelable;

public class ProListResults implements Parcelable {

    /**
     * adddate : 2019-07-12 03:28:58
     * gold : 100
     * status : 未审核
     */

    private String adddate;
    private String gold;
    private String status;

    public String getAdddate() {
        return adddate;
    }

    public void setAdddate(String adddate) {
        this.adddate = adddate;
    }

    public String getGold() {
        return gold;
    }

    public void setGold(String gold) {
        this.gold = gold;
    }

    public String getStatus() {
        return status;
    }

    public void setStatus(String status) {
        this.status = status;
    }

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel dest, int flags) {
        dest.writeString(this.adddate);
        dest.writeString(this.gold);
        dest.writeString(this.status);
    }

    public ProListResults() {
    }

    protected ProListResults(Parcel in) {
        this.adddate = in.readString();
        this.gold = in.readString();
        this.status = in.readString();
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
