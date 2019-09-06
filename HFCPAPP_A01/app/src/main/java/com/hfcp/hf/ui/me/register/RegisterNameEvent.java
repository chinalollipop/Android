package com.hfcp.hf.ui.me.register;

import android.os.Parcel;
import android.os.Parcelable;

public class RegisterNameEvent implements Parcelable {
    String pwd;
    String type;
    String nickName;
    String accountName;
    String className;

    public RegisterNameEvent(String pwd, String type, String nickName, String accountName, String className) {
        this.pwd = pwd;
        this.type = type;
        this.nickName = nickName;
        this.accountName = accountName;
        this.className = className;
    }

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel dest, int flags) {
        dest.writeString(this.pwd);
        dest.writeString(this.type);
        dest.writeString(this.nickName);
        dest.writeString(this.accountName);
        dest.writeString(this.className);
    }

    protected RegisterNameEvent(Parcel in) {
        this.pwd = in.readString();
        this.type = in.readString();
        this.nickName = in.readString();
        this.accountName = in.readString();
        this.className = in.readString();
    }

    public static final Parcelable.Creator<RegisterNameEvent> CREATOR = new Parcelable.Creator<RegisterNameEvent>() {
        @Override
        public RegisterNameEvent createFromParcel(Parcel source) {
            return new RegisterNameEvent(source);
        }

        @Override
        public RegisterNameEvent[] newArray(int size) {
            return new RegisterNameEvent[size];
        }
    };
}
