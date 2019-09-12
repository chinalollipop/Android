package com.vene.tian.homepage.handicap.leaguedetail.zhbet;

import android.os.Parcel;
import android.os.Parcelable;

public class PrepareBetZHEvent implements Parcelable {

    private String mLeagueTitle;
    private String mLeagueName;
    private String mTeamH;
    private String mTeamC;
    private String ioradio_r_h;
    private String ratio;
    private String buyOrderText;

    public PrepareBetZHEvent(String mLeagueTitle, String mLeagueName, String mTeamH, String mTeamC, String ioradio_r_h, String ratio, String buyOrderText) {
        this.mLeagueTitle = mLeagueTitle;
        this.mLeagueName = mLeagueName;
        this.mTeamH = mTeamH;
        this.mTeamC = mTeamC;
        this.ioradio_r_h = ioradio_r_h;
        this.ratio = ratio;
        this.buyOrderText = buyOrderText;
    }

    public String getmLeagueTitle() {
        return mLeagueTitle;
    }

    public void setmLeagueTitle(String mLeagueTitle) {
        this.mLeagueTitle = mLeagueTitle;
    }

    public String getmLeagueName() {
        return mLeagueName;
    }

    public void setmLeagueName(String mLeagueName) {
        this.mLeagueName = mLeagueName;
    }

    public String getmTeamH() {
        return mTeamH;
    }

    public void setmTeamH(String mTeamH) {
        this.mTeamH = mTeamH;
    }

    public String getmTeamC() {
        return mTeamC;
    }

    public void setmTeamC(String mTeamC) {
        this.mTeamC = mTeamC;
    }

    public String getIoradio_r_h() {
        return ioradio_r_h;
    }

    public void setIoradio_r_h(String ioradio_r_h) {
        this.ioradio_r_h = ioradio_r_h;
    }

    public String getRatio() {
        return ratio;
    }

    public void setRatio(String ratio) {
        this.ratio = ratio;
    }

    public String getBuyOrderText() {
        return buyOrderText;
    }

    public void setBuyOrderText(String buyOrderText) {
        this.buyOrderText = buyOrderText;
    }

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel dest, int flags) {
        dest.writeString(this.mLeagueTitle);
        dest.writeString(this.mLeagueName);
        dest.writeString(this.mTeamH);
        dest.writeString(this.mTeamC);
        dest.writeString(this.ioradio_r_h);
        dest.writeString(this.ratio);
        dest.writeString(this.buyOrderText);
    }

    protected PrepareBetZHEvent(Parcel in) {
        this.mLeagueTitle = in.readString();
        this.mLeagueName = in.readString();
        this.mTeamH = in.readString();
        this.mTeamC = in.readString();
        this.ioradio_r_h = in.readString();
        this.ratio = in.readString();
        this.buyOrderText = in.readString();
    }

    public static final Creator<PrepareBetZHEvent> CREATOR = new Creator<PrepareBetZHEvent>() {
        @Override
        public PrepareBetZHEvent createFromParcel(Parcel source) {
            return new PrepareBetZHEvent(source);
        }

        @Override
        public PrepareBetZHEvent[] newArray(int size) {
            return new PrepareBetZHEvent[size];
        }
    };
}
