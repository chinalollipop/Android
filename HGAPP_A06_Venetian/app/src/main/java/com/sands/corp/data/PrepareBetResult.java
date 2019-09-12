package com.sands.corp.data;

import android.os.Parcel;
import android.os.Parcelable;

public class PrepareBetResult implements Parcelable {

    /**
     * leag : 美国职业美式足球季前赛
     * gametype : 全场 - 让分
     * MB_Team : 水牛城比尔
     * TG_Team : 辛辛那提孟加拉虎
     * sign : 1
     * ShowTypeRB :
     * ShowTypeR : H
     * inball :
     * M_Place : 辛辛那提孟加拉虎
     * minBet : 20
     * maxBet : 500000
     * active : 2
     * line_type : 2
     * type : C
     * rtype :
     * wtype : R
     * gnum :
     * ioradio_r_h : 0.92
     * odd_f_type : H
     * dataSou :
     */

    private String leag;
    private String gametype;
    private String MB_Team;
    private String TG_Team;
    private String sign;
    private String ShowTypeRB;
    private String ShowTypeR;
    private String inball;
    private String M_Place;
    private String minBet;
    private String maxBet;
    private String active;
    private String line_type;
    private String type;
    private String rtype;
    private String wtype;
    private String gnum;
    private String ioradio_r_h;
    private String odd_f_type;
    private String dataSou;

    public String getLeag() {
        return leag;
    }

    public void setLeag(String leag) {
        this.leag = leag;
    }

    public String getGametype() {
        return gametype;
    }

    public void setGametype(String gametype) {
        this.gametype = gametype;
    }

    public String getMB_Team() {
        return MB_Team;
    }

    public void setMB_Team(String MB_Team) {
        this.MB_Team = MB_Team;
    }

    public String getTG_Team() {
        return TG_Team;
    }

    public void setTG_Team(String TG_Team) {
        this.TG_Team = TG_Team;
    }

    public String getSign() {
        return sign;
    }

    public void setSign(String sign) {
        this.sign = sign;
    }

    public String getShowTypeRB() {
        return ShowTypeRB;
    }

    public void setShowTypeRB(String showTypeRB) {
        ShowTypeRB = showTypeRB;
    }

    public String getShowTypeR() {
        return ShowTypeR;
    }

    public void setShowTypeR(String showTypeR) {
        ShowTypeR = showTypeR;
    }

    public String getInball() {
        return inball;
    }

    public void setInball(String inball) {
        this.inball = inball;
    }

    public String getM_Place() {
        return M_Place;
    }

    public void setM_Place(String m_Place) {
        M_Place = m_Place;
    }

    public String getMinBet() {
        return minBet;
    }

    public void setMinBet(String minBet) {
        this.minBet = minBet;
    }

    public String getMaxBet() {
        return maxBet;
    }

    public void setMaxBet(String maxBet) {
        this.maxBet = maxBet;
    }

    public String getActive() {
        return active;
    }

    public void setActive(String active) {
        this.active = active;
    }

    public String getLine_type() {
        return line_type;
    }

    public void setLine_type(String line_type) {
        this.line_type = line_type;
    }

    public String getType() {
        return type;
    }

    public void setType(String type) {
        this.type = type;
    }

    public String getRtype() {
        return rtype;
    }

    public void setRtype(String rtype) {
        this.rtype = rtype;
    }

    public String getWtype() {
        return wtype;
    }

    public void setWtype(String wtype) {
        this.wtype = wtype;
    }

    public String getGnum() {
        return gnum;
    }

    public void setGnum(String gnum) {
        this.gnum = gnum;
    }

    public String getIoradio_r_h() {
        return ioradio_r_h;
    }

    public void setIoradio_r_h(String ioradio_r_h) {
        this.ioradio_r_h = ioradio_r_h;
    }

    public String getOdd_f_type() {
        return odd_f_type;
    }

    public void setOdd_f_type(String odd_f_type) {
        this.odd_f_type = odd_f_type;
    }

    public String getDataSou() {
        return dataSou;
    }

    public void setDataSou(String dataSou) {
        this.dataSou = dataSou;
    }

    @Override
    public String toString() {
        return "PrepareBetResult{" +
                "leag='" + leag + '\'' +
                ", gametype='" + gametype + '\'' +
                ", MB_Team='" + MB_Team + '\'' +
                ", TG_Team='" + TG_Team + '\'' +
                ", sign='" + sign + '\'' +
                ", ShowTypeRB='" + ShowTypeRB + '\'' +
                ", ShowTypeR='" + ShowTypeR + '\'' +
                ", inball='" + inball + '\'' +
                ", M_Place='" + M_Place + '\'' +
                ", minBet='" + minBet + '\'' +
                ", maxBet='" + maxBet + '\'' +
                ", active='" + active + '\'' +
                ", line_type='" + line_type + '\'' +
                ", type='" + type + '\'' +
                ", rtype='" + rtype + '\'' +
                ", wtype='" + wtype + '\'' +
                ", gnum='" + gnum + '\'' +
                ", ioradio_r_h='" + ioradio_r_h + '\'' +
                ", odd_f_type='" + odd_f_type + '\'' +
                ", dataSou='" + dataSou + '\'' +
                '}';
    }

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel dest, int flags) {
        dest.writeString(this.leag);
        dest.writeString(this.gametype);
        dest.writeString(this.MB_Team);
        dest.writeString(this.TG_Team);
        dest.writeString(this.sign);
        dest.writeString(this.ShowTypeRB);
        dest.writeString(this.ShowTypeR);
        dest.writeString(this.inball);
        dest.writeString(this.M_Place);
        dest.writeString(this.minBet);
        dest.writeString(this.maxBet);
        dest.writeString(this.active);
        dest.writeString(this.line_type);
        dest.writeString(this.type);
        dest.writeString(this.rtype);
        dest.writeString(this.wtype);
        dest.writeString(this.gnum);
        dest.writeString(this.ioradio_r_h);
        dest.writeString(this.odd_f_type);
        dest.writeString(this.dataSou);
    }

    public PrepareBetResult() {
    }

    protected PrepareBetResult(Parcel in) {
        this.leag = in.readString();
        this.gametype = in.readString();
        this.MB_Team = in.readString();
        this.TG_Team = in.readString();
        this.sign = in.readString();
        this.ShowTypeRB = in.readString();
        this.ShowTypeR = in.readString();
        this.inball = in.readString();
        this.M_Place = in.readString();
        this.minBet = in.readString();
        this.maxBet = in.readString();
        this.active = in.readString();
        this.line_type = in.readString();
        this.type = in.readString();
        this.rtype = in.readString();
        this.wtype = in.readString();
        this.gnum = in.readString();
        this.ioradio_r_h = in.readString();
        this.odd_f_type = in.readString();
        this.dataSou = in.readString();
    }

    public static final Parcelable.Creator<PrepareBetResult> CREATOR = new Parcelable.Creator<PrepareBetResult>() {
        @Override
        public PrepareBetResult createFromParcel(Parcel source) {
            return new PrepareBetResult(source);
        }

        @Override
        public PrepareBetResult[] newArray(int size) {
            return new PrepareBetResult[size];
        }
    };
}
