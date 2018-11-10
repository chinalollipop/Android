package com.hgapp.a6668.homepage.cplist;

public class CPHallIcon {
    private String iconName;
    private int iconId;
    private int iconTime;

    public CPHallIcon(String iconName, int iconId,int iconTime) {
        this.iconName = iconName;
        this.iconId = iconId;
        this.iconTime = iconTime;
    }

    public String getIconName() {
        return iconName;
    }

    public void setIconName(String iconName) {
        this.iconName = iconName;
    }

    public int getIconId() {
        return iconId;
    }

    public void setIconId(int iconId) {
        this.iconId = iconId;
    }

    public int getIconTime() {
        return iconTime;
    }

    public void setIconTime(int iconTime) {
        this.iconTime = iconTime;
    }
}
