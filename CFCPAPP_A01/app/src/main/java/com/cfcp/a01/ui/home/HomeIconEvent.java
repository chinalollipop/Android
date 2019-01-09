package com.cfcp.a01.ui.home;


import android.os.Parcel;
import android.os.Parcelable;

import com.cfcp.a01.ui.home.enumeration.LotteryType;

public class HomeIconEvent implements Parcelable {
    String iconName;
    String iconDescribe;
    int iconDrawable;
    LotteryType iconId;
    int id;

    public HomeIconEvent(String iconName, String iconDescribe, int iconDrawable, LotteryType iconId, int id) {
        this.iconName = iconName;
        this.iconDescribe = iconDescribe;
        this.iconDrawable = iconDrawable;
        this.iconId = iconId;
        this.id = id;
    }

    public String getIconName() {
        return iconName;
    }

    public void setIconName(String iconName) {
        this.iconName = iconName;
    }

    public String getIconDescribe() {
        return iconDescribe;
    }

    public void setIconDescribe(String iconDescribe) {
        this.iconDescribe = iconDescribe;
    }

    public int getIconDrawable() {
        return iconDrawable;
    }

    public void setIconDrawable(int iconDrawable) {
        this.iconDrawable = iconDrawable;
    }

    public LotteryType getIconId() {
        return iconId;
    }

    public void setIconId(LotteryType iconId) {
        this.iconId = iconId;
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }


    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel dest, int flags) {
        dest.writeString(this.iconName);
        dest.writeString(this.iconDescribe);
        dest.writeInt(this.iconDrawable);
        dest.writeInt(this.iconId == null ? -1 : this.iconId.ordinal());
        dest.writeInt(this.id);
    }

    protected HomeIconEvent(Parcel in) {
        this.iconName = in.readString();
        this.iconDescribe = in.readString();
        this.iconDrawable = in.readInt();
        int tmpIconId = in.readInt();
        this.iconId = tmpIconId == -1 ? null : LotteryType.values()[tmpIconId];
        this.id = in.readInt();
    }

    public static final Parcelable.Creator<HomeIconEvent> CREATOR = new Parcelable.Creator<HomeIconEvent>() {
        @Override
        public HomeIconEvent createFromParcel(Parcel source) {
            return new HomeIconEvent(source);
        }

        @Override
        public HomeIconEvent[] newArray(int size) {
            return new HomeIconEvent[size];
        }
    };
}
