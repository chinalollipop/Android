package com.cfcp.a01.data;

public class CPNextIssueResult {



    /**
     * issue : 731089
     * endtime : 2019-03-21 17:49:00
     * nums :
     * lotteryTime : 2019-03-21 17:50:00
     * preIssue : 731088
     * preLotteryTime : 2019-03-21 17:33:03
     * preNum : 04,07,08,02,01,03,06,09,05,10
     * preIsOpen : true
     * serverTime : 2019-03-21 17:49:24
     * gameId : 50
     */

    private String issue;
    private String endtime;
    private String nums;
    private String lotteryTime;
    private String preIssue;
    private String preLotteryTime;
    private String preNum;
    private String preIsOpen;
    private String isOpen;
    private String serverTime;
    private int gameId;

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

    public String getNums() {
        return nums;
    }

    public void setNums(String nums) {
        this.nums = nums;
    }

    public String getLotteryTime() {
        return lotteryTime;
    }

    public void setLotteryTime(String lotteryTime) {
        this.lotteryTime = lotteryTime;
    }

    public String getPreIssue() {
        return preIssue;
    }

    public void setPreIssue(String preIssue) {
        this.preIssue = preIssue;
    }

    public String getPreLotteryTime() {
        return preLotteryTime;
    }

    public void setPreLotteryTime(String preLotteryTime) {
        this.preLotteryTime = preLotteryTime;
    }

    public String getPreNum() {
        return preNum;
    }

    public void setPreNum(String preNum) {
        this.preNum = preNum;
    }

    public String isPreIsOpen() {
        return preIsOpen;
    }

    public void setPreIsOpen(String preIsOpen) {
        this.preIsOpen = preIsOpen;
    }

    public String getIsOpen() {
        return isOpen;
    }

    public void setIsOpen(String isOpen) {
        this.isOpen = isOpen;
    }

    public String getServerTime() {
        return serverTime;
    }

    public void setServerTime(String serverTime) {
        this.serverTime = serverTime;
    }

    public int getGameId() {
        return gameId;
    }

    public void setGameId(int gameId) {
        this.gameId = gameId;
    }
}
