package com.hgapp.betnew.data;

import java.util.List;

public class COLastResultHK {

    /**
     * gameId : 2018138
     * nums : ["8","31","11","14","20","22","19"]
     * endtime : 2018-12-04
     * issue : 2018138
     */

    private String gameId;
    private String endtime;
    private String issue;
    private List<String> nums;

    public String getGameId() {
        return gameId;
    }

    public void setGameId(String gameId) {
        this.gameId = gameId;
    }

    public String getEndtime() {
        return endtime;
    }

    public void setEndtime(String endtime) {
        this.endtime = endtime;
    }

    public String getIssue() {
        return issue;
    }

    public void setIssue(String issue) {
        this.issue = issue;
    }

    public List<String> getNums() {
        return nums;
    }

    public void setNums(List<String> nums) {
        this.nums = nums;
    }
}
