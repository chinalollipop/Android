package com.nhg.xhg.homepage.sportslist.bet;

import android.os.Parcel;
import android.os.Parcelable;

public class OrderNumber implements Parcelable {
    private String appRefer;
    private  String cate;
    private String gid;
    private  String type;
    private String active;
    private String line_type;
    private String odd_f_type;
    private String gold;
    private String ioradio_r_h;
    private String rtype;
    private String wtype;

    public OrderNumber(){}

    public OrderNumber(String appRefer, String cate, String gid, String type, String active, String line_type, String odd_f_type, String gold, String ioradio_r_h, String rtype, String wtype) {
        this.appRefer = appRefer;
        this.cate = cate;
        this.gid = gid;
        this.type = type;
        this.active = active;
        this.line_type = line_type;
        this.odd_f_type = odd_f_type;
        this.gold = gold;
        this.ioradio_r_h = ioradio_r_h;
        this.rtype = rtype;
        this.wtype = wtype;
    }

    public String getAppRefer() {
        return appRefer;
    }

    public void setAppRefer(String appRefer) {
        this.appRefer = appRefer;
    }

    public String getCate() {
        return cate;
    }

    public void setCate(String cate) {
        this.cate = cate;
    }

    public String getGid() {
        return gid;
    }

    public void setGid(String gid) {
        this.gid = gid;
    }

    public String getType() {
        return type;
    }

    public void setType(String type) {
        this.type = type;
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

    public String getOdd_f_type() {
        return odd_f_type;
    }

    public void setOdd_f_type(String odd_f_type) {
        this.odd_f_type = odd_f_type;
    }

    public String getGold() {
        return gold;
    }

    public void setGold(String gold) {
        this.gold = gold;
    }

    public String getIoradio_r_h() {
        return ioradio_r_h;
    }

    public void setIoradio_r_h(String ioradio_r_h) {
        this.ioradio_r_h = ioradio_r_h;
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

    @Override
    public String toString() {
        return "OrderNumber{" +
                "appRefer='" + appRefer + '\'' +
                ", cate='" + cate + '\'' +
                ", gid='" + gid + '\'' +
                ", type='" + type + '\'' +
                ", active='" + active + '\'' +
                ", line_type='" + line_type + '\'' +
                ", odd_f_type='" + odd_f_type + '\'' +
                ", gold='" + gold + '\'' +
                ", ioradio_r_h='" + ioradio_r_h + '\'' +
                ", rtype='" + rtype + '\'' +
                ", wtype='" + wtype + '\'' +
                '}';
    }

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel dest, int flags) {
        dest.writeString(this.appRefer);
        dest.writeString(this.cate);
        dest.writeString(this.gid);
        dest.writeString(this.type);
        dest.writeString(this.active);
        dest.writeString(this.line_type);
        dest.writeString(this.odd_f_type);
        dest.writeString(this.gold);
        dest.writeString(this.ioradio_r_h);
        dest.writeString(this.rtype);
        dest.writeString(this.wtype);
    }

    protected OrderNumber(Parcel in) {
        this.appRefer = in.readString();
        this.cate = in.readString();
        this.gid = in.readString();
        this.type = in.readString();
        this.active = in.readString();
        this.line_type = in.readString();
        this.odd_f_type = in.readString();
        this.gold = in.readString();
        this.ioradio_r_h = in.readString();
        this.rtype = in.readString();
        this.wtype = in.readString();
    }

    public static final Parcelable.Creator<OrderNumber> CREATOR = new Parcelable.Creator<OrderNumber>() {
        @Override
        public OrderNumber createFromParcel(Parcel source) {
            return new OrderNumber(source);
        }

        @Override
        public OrderNumber[] newArray(int size) {
            return new OrderNumber[size];
        }
    };
}
