package com.hgapp.betnhg.homepage;

public class HomePageIcon {
    private String iconName;
    private int iconId;
    private int id;
    private String iconNameTitle;
    private boolean heart = false;
    private String gameNum;

    public HomePageIcon() {
    }

    public HomePageIcon(String iconName, int iconId) {
        this.iconName = iconName;
        this.iconId = iconId;
    }
    public HomePageIcon(String iconName, int iconId,int id) {
        this.iconName = iconName;
        this.iconId = iconId;
        this.id = id;
    }

    public HomePageIcon(String iconName, int iconId, int id, String iconNameTitle) {
        this.iconName = iconName;
        this.iconId = iconId;
        this.id = id;
        this.iconNameTitle = iconNameTitle;
    }

    public HomePageIcon(String iconName, int iconId, int id, String iconNameTitle, boolean heart) {
        this.iconName = iconName;
        this.iconId = iconId;
        this.id = id;
        this.iconNameTitle = iconNameTitle;
        this.heart = heart;
    }

    public HomePageIcon(String iconName, int iconId, int id, String iconNameTitle, boolean heart, String gameNum) {
        this.iconName = iconName;
        this.iconId = iconId;
        this.id = id;
        this.iconNameTitle = iconNameTitle;
        this.heart = heart;
        this.gameNum = gameNum;
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

    public String getIconNameTitle() {
        return iconNameTitle;
    }

    public void setIconNameTitle(String iconNameTitle) {
        this.iconNameTitle = iconNameTitle;
    }

    public boolean isHeart() {
        return heart;
    }

    public void setHeart(boolean heart) {
        this.heart = heart;
    }

    public String getGameNum() {
        return gameNum;
    }

    public void setGameNum(String gameNum) {
        this.gameNum = gameNum;
    }
}
