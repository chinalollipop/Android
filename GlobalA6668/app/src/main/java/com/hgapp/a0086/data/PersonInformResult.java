package com.hgapp.a0086.data;

public class PersonInformResult {
    /**
     * username : daniel01
     * realname : 丹尼尔
     * balance_hg : 0.00
     * balance_cp : 0.00
     */

    private String username;
    private String realname;
    private String balance_hg;
    private String balance_cp;
    private String joinDays;

    public String getUsername() {
        return username;
    }

    public void setUsername(String username) {
        this.username = username;
    }

    public String getRealname() {
        return realname;
    }

    public void setRealname(String realname) {
        this.realname = realname;
    }

    public String getBalance_hg() {
        return balance_hg;
    }

    public void setBalance_hg(String balance_hg) {
        this.balance_hg = balance_hg;
    }

    public String getBalance_cp() {
        return balance_cp;
    }

    public void setBalance_cp(String balance_cp) {
        this.balance_cp = balance_cp;
    }

    public String getJoinDays() {
        return joinDays;
    }

    public void setJoinDays(String joinDays) {
        this.joinDays = joinDays;
    }
}
