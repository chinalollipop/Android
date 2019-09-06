package com.hfcp.hf.ui.home.cplist.events;

public class CPIcon {
    private String iconName;
    private int iconId;
    private int gameId;

    public CPIcon(String iconName, int iconId,int gameId) {
        this.iconName = iconName;
        this.iconId = iconId;
        this.gameId = gameId;
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

    public int getGameId() {
        return gameId;
    }

    public void setGameId(int gameId) {
        this.gameId = gameId;
    }
}
