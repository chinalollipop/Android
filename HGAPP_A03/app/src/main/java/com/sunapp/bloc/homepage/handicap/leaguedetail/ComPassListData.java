package com.sunapp.bloc.homepage.handicap.leaguedetail;

import android.os.Parcel;
import android.os.Parcelable;

public class ComPassListData implements Parcelable {
    public String jointdata;
    public String gid;
    public String method_type;
    public int checked;

   public ComPassListData(){}

    public ComPassListData(String jointdata, String gid, String method_type,int checked) {
        this.jointdata = jointdata;
        this.gid = gid;
        this.method_type = method_type;
        this.checked = checked;
    }

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel dest, int flags) {
        dest.writeString(this.jointdata);
        dest.writeString(this.gid);
        dest.writeString(this.method_type);
        dest.writeInt(this.checked);
    }

    protected ComPassListData(Parcel in) {
        this.jointdata = in.readString();
        this.gid = in.readString();
        this.method_type = in.readString();
        this.checked = in.readInt();
    }

    public static final Creator<ComPassListData> CREATOR = new Creator<ComPassListData>() {
        @Override
        public ComPassListData createFromParcel(Parcel source) {
            return new ComPassListData(source);
        }

        @Override
        public ComPassListData[] newArray(int size) {
            return new ComPassListData[size];
        }
    };
}
