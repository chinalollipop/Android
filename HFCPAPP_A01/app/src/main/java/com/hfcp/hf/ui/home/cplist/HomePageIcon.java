package com.hfcp.hf.ui.home.cplist;

public class HomePageIcon {
    private String iconName;
    private int iconId;
    private int id;

    public HomePageIcon(String iconName, int iconId) {
        this.iconName = iconName;
        this.iconId = iconId;
    }
    public HomePageIcon(String iconName, int iconId, int id) {
        this.iconName = iconName;
        this.iconId = iconId;
        this.id = id;
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
}
