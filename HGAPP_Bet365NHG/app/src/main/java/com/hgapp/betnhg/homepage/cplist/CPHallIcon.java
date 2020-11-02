package com.hgapp.betnhg.homepage.cplist;

public class CPHallIcon {
    private String iconName;
    private int iconId;
    private int iconTime;
    /**
     * gameId : 2
     * issue : 20181125-049
     * endtime : 2018-11-25 14:08:30
     * lotteryTime : 2018-11-25 14:10:00
     * serverTime : 2018-11-25 14:05:14
     * isopen : 1
     */

    private int gameId;
    private String issue;
    private String endtime;
    private String lotteryTime;
    private String serverTime;
    private int isopen =-1;

    public CPHallIcon() {
    }

    public CPHallIcon(String iconName, int iconId, int iconTime, int gameId) {
        this.iconName = iconName;
        this.iconId = iconId;
        this.iconTime = iconTime;
        this.gameId = gameId;
    }

    public CPHallIcon(String iconName, int iconId, int iconTime, int gameId, int isopen) {
        this.iconName = iconName;
        this.iconId = iconId;
        this.iconTime = iconTime;
        this.gameId = gameId;
        this.isopen = isopen;
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

    public int getGameId() {
        return gameId;
    }

    public void setGameId(int gameId) {
        this.gameId = gameId;
    }

    public String getIssue() {
        return issue;
    }

    public void setIssue(String issue) {
        this.issue = issue;
    }

    public String getEndtime() {
        return endtime;
    }

    public void setEndtime(String endtime) {
        this.endtime = endtime;
    }

    public String getLotteryTime() {
        return lotteryTime;
    }

    public void setLotteryTime(String lotteryTime) {
        this.lotteryTime = lotteryTime;
    }

    public String getServerTime() {
        return serverTime;
    }

    public void setServerTime(String serverTime) {
        this.serverTime = serverTime;
    }

    public int getIsopen() {
        return isopen;
    }

    public void setIsopen(int isopen) {
        this.isopen = isopen;
    }
}
