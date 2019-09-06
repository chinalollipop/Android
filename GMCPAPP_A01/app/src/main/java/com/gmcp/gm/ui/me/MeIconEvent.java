package com.gmcp.gm.ui.me;


import android.os.Parcel;
import android.os.Parcelable;

public class MeIconEvent implements Parcelable {
    String iconName;
    String iconDescribe;
    int iconDrawable;
    MeFragment.MemberType iconId;
    int id;

    public MeIconEvent(String iconName, String iconDescribe, int iconDrawable, MeFragment.MemberType iconId, int id) {
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

    public MeFragment.MemberType getIconId() {
        return iconId;
    }

    public void setIconId(MeFragment.MemberType iconId) {
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

    protected MeIconEvent(Parcel in) {
        this.iconName = in.readString();
        this.iconDescribe = in.readString();
        this.iconDrawable = in.readInt();
        int tmpIconId = in.readInt();
        this.iconId = tmpIconId == -1 ? null : MeFragment.MemberType.values()[tmpIconId];
        this.id = in.readInt();
    }

    public static final Creator<MeIconEvent> CREATOR = new Creator<MeIconEvent>() {
        @Override
        public MeIconEvent createFromParcel(Parcel source) {
            return new MeIconEvent(source);
        }

        @Override
        public MeIconEvent[] newArray(int size) {
            return new MeIconEvent[size];
        }
    };
}
