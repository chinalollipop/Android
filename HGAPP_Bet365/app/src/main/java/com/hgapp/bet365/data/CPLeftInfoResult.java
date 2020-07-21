package com.hgapp.bet365.data;

public class CPLeftInfoResult {
    @Override
    public String toString() {
        return "CPLeftInfoResult{" +
                "money='" + money + '\'' +
                ", unsettledMoney='" + unsettledMoney + '\'' +
                ", todaywin='" + todaywin + '\'' +
                '}';
    }

    /**
     * money : 53929.14
     * unsettledMoney : 204
     * todaywin : 0
     */

    private String money;
    private String unsettledMoney;
    private String todaywin;

    public String getMoney() {
        return money;
    }

    public void setMoney(String money) {
        this.money = money;
    }

    public String getUnsettledMoney() {
        return unsettledMoney;
    }

    public void setUnsettledMoney(String unsettledMoney) {
        this.unsettledMoney = unsettledMoney;
    }

    public String getTodaywin() {
        return todaywin;
    }

    public void setTodaywin(String todaywin) {
        this.todaywin = todaywin;
    }
}
