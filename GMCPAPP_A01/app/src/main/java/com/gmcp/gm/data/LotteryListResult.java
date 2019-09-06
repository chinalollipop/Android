package com.gmcp.gm.data;


public class LotteryListResult {


    /**
     * issue : 190225032
     * wn_number : 5,8,2,5,1
     * offical_time : 1551077400
     */

    private String issue;
    private String wn_number;
    private String offical_time;

    public String getIssue() {
        return issue.substring(0,4).equals("2019")?issue.replace("2019","19"):issue;
    }

    public void setIssue(String issue) {
        this.issue = issue;
    }

    public String getWn_number() {
        return wn_number.replace(" ",",");
    }

    public void setWn_number(String wn_number) {
        this.wn_number = wn_number;
    }

    public String getOffical_time() {
        return offical_time;
    }

    public void setOffical_time(String offical_time) {
        this.offical_time = offical_time;
    }
}
