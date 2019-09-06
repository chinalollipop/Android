package com.gmcp.gm.data;

import java.util.List;

public class PersonReportResult {

    /**
     * user_profits : [{"user_id":5492,"date":"2019-03-13","deposit":"0.0000","withdrawal":"0.0000","turnover":"105448.0000","prize":"101866.190000","bonus":"0.0000","commission":"0.000000","lose_commission":"0","profit":"-3581.810000"}]
     * sub_total : {"deposit":0,"withdrawal":0,"turnover":105448,"prize":101866.19,"bonus":0,"commission":0,"lose_commission":0,"profit":-3581.81}
     * count : 2
     * page : 1
     * pagesize : 20
     */

    private SubTotalBean sub_total;
    private int count;
    private int page;
    private int pagesize;
    private List<UserProfitsBean> user_profits;

    public SubTotalBean getSub_total() {
        return sub_total;
    }

    public void setSub_total(SubTotalBean sub_total) {
        this.sub_total = sub_total;
    }

    public int getCount() {
        return count;
    }

    public void setCount(int count) {
        this.count = count;
    }

    public int getPage() {
        return page;
    }

    public void setPage(int page) {
        this.page = page;
    }

    public int getPagesize() {
        return pagesize;
    }

    public void setPagesize(int pagesize) {
        this.pagesize = pagesize;
    }

    public List<UserProfitsBean> getUser_profits() {
        return user_profits;
    }

    public void setUser_profits(List<UserProfitsBean> user_profits) {
        this.user_profits = user_profits;
    }

    public static class SubTotalBean {
        /**
         * deposit : 0
         * withdrawal : 0
         * turnover : 105448
         * prize : 101866.19
         * bonus : 0
         * commission : 0
         * lose_commission : 0
         * profit : -3581.81
         */

        private String deposit;
        private String withdrawal;
        private String turnover;
        private String prize;
        private String bonus;
        private String commission;
        private String lose_commission;
        private String profit;

        public String getDeposit() {
            return deposit;
        }

        public void setDeposit(String deposit) {
            this.deposit = deposit;
        }

        public String getWithdrawal() {
            return withdrawal;
        }

        public void setWithdrawal(String withdrawal) {
            this.withdrawal = withdrawal;
        }

        public String getTurnover() {
            return turnover;
        }

        public void setTurnover(String turnover) {
            this.turnover = turnover;
        }

        public String getPrize() {
            return prize;
        }

        public void setPrize(String prize) {
            this.prize = prize;
        }

        public String getBonus() {
            return bonus;
        }

        public void setBonus(String bonus) {
            this.bonus = bonus;
        }

        public String getCommission() {
            return commission;
        }

        public void setCommission(String commission) {
            this.commission = commission;
        }

        public String getLose_commission() {
            return lose_commission;
        }

        public void setLose_commission(String lose_commission) {
            this.lose_commission = lose_commission;
        }

        public String getProfit() {
            return profit;
        }

        public void setProfit(String profit) {
            this.profit = profit;
        }
    }

    public static class UserProfitsBean {
        /**
         * user_id : 5492
         * date : 2019-03-13
         * deposit : 0.0000
         * withdrawal : 0.0000
         * turnover : 105448.0000
         * prize : 101866.190000
         * bonus : 0.0000
         * commission : 0.000000
         * lose_commission : 0
         * profit : -3581.810000
         */

        private boolean isChecked;
        private int user_id;
        private String date;
        private String deposit;
        private String withdrawal;
        private String turnover;
        private String prize;
        private String bonus;
        private String commission;
        private String lose_commission;
        private String profit;

        public boolean isChecked() {
            return isChecked;
        }

        public void setChecked(boolean checked) {
            isChecked = checked;
        }

        public int getUser_id() {
            return user_id;
        }

        public void setUser_id(int user_id) {
            this.user_id = user_id;
        }

        public String getDate() {
            return date;
        }

        public void setDate(String date) {
            this.date = date;
        }

        public String getDeposit() {
            return deposit;
        }

        public void setDeposit(String deposit) {
            this.deposit = deposit;
        }

        public String getWithdrawal() {
            return withdrawal;
        }

        public void setWithdrawal(String withdrawal) {
            this.withdrawal = withdrawal;
        }

        public String getTurnover() {
            return turnover;
        }

        public void setTurnover(String turnover) {
            this.turnover = turnover;
        }

        public String getPrize() {
            return prize;
        }

        public void setPrize(String prize) {
            this.prize = prize;
        }

        public String getBonus() {
            return bonus;
        }

        public void setBonus(String bonus) {
            this.bonus = bonus;
        }

        public String getCommission() {
            return commission;
        }

        public void setCommission(String commission) {
            this.commission = commission;
        }

        public String getLose_commission() {
            return lose_commission;
        }

        public void setLose_commission(String lose_commission) {
            this.lose_commission = lose_commission;
        }

        public String getProfit() {
            return profit;
        }

        public void setProfit(String profit) {
            this.profit = profit;
        }
    }
}
