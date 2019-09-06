package com.hfcp.hf.data;

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
    private String xy_total_turnover;
    private String xy_total_profit;
    private String xy_total_prize;
    /**
     * ky_total_turnover : 0
     * ky_total_profit : 0
     * ky_total_revenue : 0
     * ly_total_turnover : 0
     * ly_total_profit : 0
     * ly_total_revenue : 0
     * aglive_total_turnover : 0
     * aglive_total_profit : 0
     * aggame_total_turnover : 0
     * aggame_total_profit : 0
     * bet_total_turnover : 0
     * bet_total_prize : 0
     * bet_total_profit : 0
     */

    private String ky_total_turnover;
    private String ky_total_profit;
    private String ky_total_prize;
    private String ky_total_revenue;
    private String ly_total_turnover;
    private String ly_total_profit;
    private String ly_total_revenue;
    private String ly_total_prize;
    private String agby_total_turnover;
    private String agby_total_prize;
    private String agby_total_profit;
    private String aglive_total_turnover;
    private String aglive_total_profit;
    private String aggame_total_turnover;
    private String aggame_total_profit;
    private String bet_total_turnover;
    private String bet_total_prize;
    private String bet_total_profit;

    public String getAgby_total_turnover() {
        return agby_total_turnover;
    }

    public void setAgby_total_turnover(String agby_total_turnover) {
        this.agby_total_turnover = agby_total_turnover;
    }

    public String getAgby_total_prize() {
        return agby_total_prize;
    }

    public void setAgby_total_prize(String agby_total_prize) {
        this.agby_total_prize = agby_total_prize;
    }

    public String getAgby_total_profit() {
        return agby_total_profit;
    }

    public void setAgby_total_profit(String agby_total_profit) {
        this.agby_total_profit = agby_total_profit;
    }

    public String getXy_total_turnover() {
        return xy_total_turnover;
    }

    public void setXy_total_turnover(String xy_total_turnover) {
        this.xy_total_turnover = xy_total_turnover;
    }

    public String getXy_total_profit() {
        return xy_total_profit;
    }

    public void setXy_total_profit(String xy_total_profit) {
        this.xy_total_profit = xy_total_profit;
    }

    public String getXy_total_prize() {
        return xy_total_prize;
    }

    public void setXy_total_prize(String xy_total_prize) {
        this.xy_total_prize = xy_total_prize;
    }

    public String getKy_total_prize() {
        return ky_total_prize;
    }

    public void setKy_total_prize(String ky_total_prize) {
        this.ky_total_prize = ky_total_prize;
    }

    public String getLy_total_prize() {
        return ly_total_prize;
    }

    public void setLy_total_prize(String ly_total_prize) {
        this.ly_total_prize = ly_total_prize;
    }

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

    public String getKy_total_turnover() {
        return ky_total_turnover;
    }

    public void setKy_total_turnover(String ky_total_turnover) {
        this.ky_total_turnover = ky_total_turnover;
    }

    public String getKy_total_profit() {
        return ky_total_profit;
    }

    public void setKy_total_profit(String ky_total_profit) {
        this.ky_total_profit = ky_total_profit;
    }

    public String getKy_total_revenue() {
        return ky_total_revenue;
    }

    public void setKy_total_revenue(String ky_total_revenue) {
        this.ky_total_revenue = ky_total_revenue;
    }

    public String getLy_total_turnover() {
        return ly_total_turnover;
    }

    public void setLy_total_turnover(String ly_total_turnover) {
        this.ly_total_turnover = ly_total_turnover;
    }

    public String getLy_total_profit() {
        return ly_total_profit;
    }

    public void setLy_total_profit(String ly_total_profit) {
        this.ly_total_profit = ly_total_profit;
    }

    public String getLy_total_revenue() {
        return ly_total_revenue;
    }

    public void setLy_total_revenue(String ly_total_revenue) {
        this.ly_total_revenue = ly_total_revenue;
    }

    public String getAglive_total_turnover() {
        return aglive_total_turnover;
    }

    public void setAglive_total_turnover(String aglive_total_turnover) {
        this.aglive_total_turnover = aglive_total_turnover;
    }

    public String getAglive_total_profit() {
        return aglive_total_profit;
    }

    public void setAglive_total_profit(String aglive_total_profit) {
        this.aglive_total_profit = aglive_total_profit;
    }

    public String getAggame_total_turnover() {
        return aggame_total_turnover;
    }

    public void setAggame_total_turnover(String aggame_total_turnover) {
        this.aggame_total_turnover = aggame_total_turnover;
    }

    public String getAggame_total_profit() {
        return aggame_total_profit;
    }

    public void setAggame_total_profit(String aggame_total_profit) {
        this.aggame_total_profit = aggame_total_profit;
    }

    public String getBet_total_turnover() {
        return bet_total_turnover;
    }

    public void setBet_total_turnover(String bet_total_turnover) {
        this.bet_total_turnover = bet_total_turnover;
    }

    public String getBet_total_prize() {
        return bet_total_prize;
    }

    public void setBet_total_prize(String bet_total_prize) {
        this.bet_total_prize = bet_total_prize;
    }

    public String getBet_total_profit() {
        return bet_total_profit;
    }

    public void setBet_total_profit(String bet_total_profit) {
        this.bet_total_profit = bet_total_profit;
    }
}
