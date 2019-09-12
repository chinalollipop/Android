package com.venen.tian.homepage;

public class HomePageIcon {
    private String iconName;
    private int iconId;
    private int clickId;
    private int id;
    private boolean isClick;

    public HomePageIcon(String iconName, int iconId) {
        this.iconName = iconName;
        this.iconId = iconId;
    }
    public HomePageIcon(String iconName, int iconId,int id) {
        this.iconName = iconName;
        this.iconId = iconId;
        this.id = id;
    }


    public HomePageIcon(String iconName, int iconId, int clickId, int id, boolean isClick) {
        this.iconName = iconName;
        this.iconId = iconId;
        this.clickId = clickId;
        this.id = id;
        this.isClick = isClick;
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

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public boolean isClick() {
        return isClick;
    }

    public void setClick(boolean click) {
        isClick = click;
    }

    public int getClickId() {
        return clickId;
    }

    public void setClickId(int clickId) {
        this.clickId = clickId;
    }
}
