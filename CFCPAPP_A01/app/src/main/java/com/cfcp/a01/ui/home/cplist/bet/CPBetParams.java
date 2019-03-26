package com.cfcp.a01.ui.home.cplist.bet;

import android.os.Parcel;
import android.os.Parcelable;

public class CPBetParams implements Parcelable {
    // LM HK RX
    private String type;
    private String typeCode;
    private String rtype;
    private String typeName;
    private String typeNumber;
    private String gold;
    private String game_code;
    private String round;
    private String fTime;
    private String x_session_token;

    public String getType() {
        return type;
    }

    public void setType(String type) {
        this.type = type;
    }

    public String getTypeCode() {
        return typeCode;
    }

    public void setTypeCode(String typeCode) {
        this.typeCode = typeCode;
    }

    public String getRtype() {
        return rtype;
    }

    public void setRtype(String rtype) {
        this.rtype = rtype;
    }

    public String getTypeName() {
        return typeName;
    }

    public void setTypeName(String typeName) {
        this.typeName = typeName;
    }

    public String getTypeNumber() {
        return typeNumber;
    }

    public void setTypeNumber(String typeNumber) {
        this.typeNumber = typeNumber;
    }

    public String getGold() {
        return gold;
    }

    public void setGold(String gold) {
        this.gold = gold;
    }

    public String getGame_code() {
        return game_code;
    }

    public void setGame_code(String game_code) {
        this.game_code = game_code;
    }

    public String getRound() {
        return round;
    }

    public void setRound(String round) {
        this.round = round;
    }

    public String getfTime() {
        return fTime;
    }

    public void setfTime(String fTime) {
        this.fTime = fTime;
    }

    public String getX_session_token() {
        return x_session_token;
    }

    public void setX_session_token(String x_session_token) {
        this.x_session_token = x_session_token;
    }

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel dest, int flags) {
        dest.writeString(this.type);
        dest.writeString(this.typeCode);
        dest.writeString(this.rtype);
        dest.writeString(this.typeName);
        dest.writeString(this.typeNumber);
        dest.writeString(this.gold);
        dest.writeString(this.game_code);
        dest.writeString(this.round);
        dest.writeString(this.fTime);
        dest.writeString(this.x_session_token);
    }

    public CPBetParams() {
    }

    protected CPBetParams(Parcel in) {
        this.type = in.readString();
        this.typeCode = in.readString();
        this.rtype = in.readString();
        this.typeName = in.readString();
        this.typeNumber = in.readString();
        this.gold = in.readString();
        this.game_code = in.readString();
        this.round = in.readString();
        this.fTime = in.readString();
        this.x_session_token = in.readString();
    }

    public static final Creator<CPBetParams> CREATOR = new Creator<CPBetParams>() {
        @Override
        public CPBetParams createFromParcel(Parcel source) {
            return new CPBetParams(source);
        }

        @Override
        public CPBetParams[] newArray(int size) {
            return new CPBetParams[size];
        }
    };
}
