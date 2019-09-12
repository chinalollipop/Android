package com.vene.tian.data;

public class CPNextIssueResult {

    /**
     * gameId : 2
     * issue : 20181126-076
     * endtime : 2018-11-26 18:38:30
     * lotteryTime : 2018-11-26 18:40:00
     * serverTime : 2018-11-26 18:38:13
     * isopen : 1
     */

    private int gameId;
    private String issue;
    private String endtime;
    private String lotteryTime;
    private String serverTime;
    private int isopen;

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
