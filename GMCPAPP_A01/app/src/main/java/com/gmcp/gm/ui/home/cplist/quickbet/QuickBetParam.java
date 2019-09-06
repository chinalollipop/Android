package com.gmcp.gm.ui.home.cplist.quickbet;

import android.os.Parcel;
import android.os.Parcelable;

public class QuickBetParam implements Parcelable {
    public String game_code;
    public String type;
    public String sort;
    public String token;
    public String code;
    public String code_number;

    public String getGame_code() {
        return game_code;
    }

    public void setGame_code(String game_code) {
        this.game_code = game_code;
    }

    public String getType() {
        return type;
    }

    public void setType(String type) {
        this.type = type;
    }

    public String getSort() {
        return sort;
    }

    public void setSort(String sort) {
        this.sort = sort;
    }

    public String getToken() {
        return token;
    }

    public void setToken(String token) {
        this.token = token;
    }

    public String getCode() {
        return code;
    }

    public void setCode(String code) {
        this.code = code;
    }

    public String getCode_number() {
        return code_number;
    }

    public void setCode_number(String code_number) {
        this.code_number = code_number;
    }

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel dest, int flags) {
        dest.writeString(this.game_code);
        dest.writeString(this.type);
        dest.writeString(this.sort);
        dest.writeString(this.token);
        dest.writeString(this.code);
        dest.writeString(this.code_number);
    }

    public QuickBetParam() {
    }

    protected QuickBetParam(Parcel in) {
        this.game_code = in.readString();
        this.type = in.readString();
        this.sort = in.readString();
        this.token = in.readString();
        this.code = in.readString();
        this.code_number = in.readString();
    }

    public static final Creator<QuickBetParam> CREATOR = new Creator<QuickBetParam>() {
        @Override
        public QuickBetParam createFromParcel(Parcel source) {
            return new QuickBetParam(source);
        }

        @Override
        public QuickBetParam[] newArray(int size) {
            return new QuickBetParam[size];
        }
    };
}
