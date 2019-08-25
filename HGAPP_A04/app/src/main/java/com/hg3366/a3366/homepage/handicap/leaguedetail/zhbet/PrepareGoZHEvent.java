package com.hg3366.a3366.homepage.handicap.leaguedetail.zhbet;

import android.os.Parcel;
import android.os.Parcelable;

public class PrepareGoZHEvent implements Parcelable {
    private String gtype;
    public String sorttype;
    private String mdata;
    private String showtype;
    private String M_League;
    private String gid;
    private String userMoney;
    private String fromType;
    private String mLeague;
    private String mTeamH;
    private String mTeamC;
    private String fromString;

    public PrepareGoZHEvent(String gtype, String sorttype, String mdata, String showtype, String m_League, String gid, String userMoney, String fromType, String mLeague, String mTeamH, String mTeamC,String fromString) {
        this.gtype = gtype;
        this.sorttype = sorttype;
        this.mdata = mdata;
        this.showtype = showtype;
        this.M_League = m_League;
        this.gid = gid;
        this.userMoney = userMoney;
        this.fromType = fromType;
        this.mLeague = mLeague;
        this.mTeamH = mTeamH;
        this.mTeamC = mTeamC;
        this.fromString = fromString ;
    }

    public String getGtype() {
        return gtype;
    }

    public void setGtype(String gtype) {
        this.gtype = gtype;
    }

    public String getSorttype() {
        return sorttype;
    }

    public void setSorttype(String sorttype) {
        this.sorttype = sorttype;
    }

    public String getMdata() {
        return mdata;
    }

    public void setMdata(String mdata) {
        this.mdata = mdata;
    }

    public String getShowtype() {
        return showtype;
    }

    public void setShowtype(String showtype) {
        this.showtype = showtype;
    }

    public String getM_League() {
        return M_League;
    }

    public void setM_League(String m_League) {
        M_League = m_League;
    }

    public String getGid() {
        return gid;
    }

    public void setGid(String gid) {
        this.gid = gid;
    }

    public String getUserMoney() {
        return userMoney;
    }

    public void setUserMoney(String userMoney) {
        this.userMoney = userMoney;
    }

    public String getFromType() {
        return fromType;
    }

    public void setFromType(String fromType) {
        this.fromType = fromType;
    }

    public String getmLeague() {
        return mLeague;
    }

    public void setmLeague(String mLeague) {
        this.mLeague = mLeague;
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

    public String getFromString() {
        return fromString;
    }

    public void setFromString(String fromString) {
        this.fromString = fromString;
    }

    @Override
    public String toString() {
        return "PrepareGoZHEvent{" +
                "gtype='" + gtype + '\'' +
                ", sorttype='" + sorttype + '\'' +
                ", mdata='" + mdata + '\'' +
                ", showtype='" + showtype + '\'' +
                ", M_League='" + M_League + '\'' +
                ", gid='" + gid + '\'' +
                ", userMoney='" + userMoney + '\'' +
                ", fromType='" + fromType + '\'' +
                ", mLeague='" + mLeague + '\'' +
                ", mTeamH='" + mTeamH + '\'' +
                ", mTeamC='" + mTeamC + '\'' +
                ", fromString='" + fromString + '\'' +
                '}';
    }

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel dest, int flags) {
        dest.writeString(this.gtype);
        dest.writeString(this.sorttype);
        dest.writeString(this.mdata);
        dest.writeString(this.showtype);
        dest.writeString(this.M_League);
        dest.writeString(this.gid);
        dest.writeString(this.userMoney);
        dest.writeString(this.fromType);
        dest.writeString(this.mLeague);
        dest.writeString(this.mTeamH);
        dest.writeString(this.mTeamC);
        dest.writeString(this.fromString);
    }

    protected PrepareGoZHEvent(Parcel in) {
        this.gtype = in.readString();
        this.sorttype = in.readString();
        this.mdata = in.readString();
        this.showtype = in.readString();
        this.M_League = in.readString();
        this.gid = in.readString();
        this.userMoney = in.readString();
        this.fromType = in.readString();
        this.mLeague = in.readString();
        this.mTeamH = in.readString();
        this.mTeamC = in.readString();
        this.fromString = in.readString();
    }

    public static final Parcelable.Creator<PrepareGoZHEvent> CREATOR = new Parcelable.Creator<PrepareGoZHEvent>() {
        @Override
        public PrepareGoZHEvent createFromParcel(Parcel source) {
            return new PrepareGoZHEvent(source);
        }

        @Override
        public PrepareGoZHEvent[] newArray(int size) {
            return new PrepareGoZHEvent[size];
        }
    };
}
