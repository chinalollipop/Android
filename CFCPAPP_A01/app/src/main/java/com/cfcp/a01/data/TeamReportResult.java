package com.cfcp.a01.data;

public class TeamReportResult {


    /**
     * total_deposit ://充值
     * total_withdrawal ://提现
     * total_turnover :投注金额
     * total_commission : 中奖金额
     * total_profit :打和反款
     * total_prize :代理返点
     * total_lose_commission :投注返点
     * total_bonus :活动奖励
     */

    private String total_deposit;
    private String total_withdrawal;
    private String total_turnover;
    private String total_commission;
    private String total_profit;
    private String total_prize;
    private String total_lose_commission;
    private String total_bonus;
    private String available;

    public String getTotal_deposit() {
        return total_deposit.equals("")?"0.00":total_deposit;
    }

    public void setTotal_deposit(String total_deposit) {
        this.total_deposit = total_deposit;
    }

    public String getTotal_withdrawal() {
        return total_withdrawal.equals("")?"0.00":total_withdrawal;
    }

    public void setTotal_withdrawal(String total_withdrawal) {
        this.total_withdrawal = total_withdrawal;
    }

    public String getTotal_turnover() {
        return total_turnover.equals("")?"0.00":total_turnover;
    }

    public void setTotal_turnover(String total_turnover) {
        this.total_turnover = total_turnover;
    }

    public String getTotal_commission() {
        return total_commission.equals("")?"0.00":total_commission;
    }

    public void setTotal_commission(String total_commission) {
        this.total_commission = total_commission;
    }

    public String getTotal_profit() {
        return total_profit.equals("")?"0.00":total_profit;
    }

    public void setTotal_profit(String total_profit) {
        this.total_profit = total_profit;
    }

    public String getTotal_prize() {
        return total_prize.equals("")?"0.00":total_prize;
    }

    public void setTotal_prize(String total_prize) {
        this.total_prize = total_prize;
    }

    public String getTotal_lose_commission() {
        return total_lose_commission.equals("")?"0.00":total_lose_commission;
    }

    public void setTotal_lose_commission(String total_lose_commission) {
        this.total_lose_commission = total_lose_commission;
    }

    public String getTotal_bonus() {
        return total_bonus.equals("")?"0.00":total_bonus;
    }

    public void setTotal_bonus(String total_bonus) {
        this.total_bonus = total_bonus;
    }

    public String getAvailable() {
        return available;
    }

    public void setAvailable(String available) {
        this.available = available;
    }
}
