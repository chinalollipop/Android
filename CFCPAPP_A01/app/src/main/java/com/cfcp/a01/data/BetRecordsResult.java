package com.cfcp.a01.data;

import java.util.List;

public class BetRecordsResult {


    private List<ListBean> list;

    public List<ListBean> getList() {
        return list;
    }

    public void setList(List<ListBean> list) {
        this.list = list;
    }

    public static class ListBean {
        /**
         * wjorderId : 201903271718514dIT8Z7qJ6
         * username : daniel02
         * type : 1
         * Groupname : 总和-龙虎和
         * actionNo : 20190327040
         * actionData : 总和双
         * actionTime : 2019-03-27 17:18:51
         * lotteryNo : 5,8,4,0,7
         * money : 1.00
         * bonus : 2.00
         * status : 3
         */

        private String wjorderId;
        private String username;
        private int type;
        private String Groupname;
        private String actionNo;
        private String actionData;
        private String actionTime;
        private String lotteryNo;
        private String money;
        private String bonus;
        private int status;

        public String getWjorderId() {
            return wjorderId;
        }

        public void setWjorderId(String wjorderId) {
            this.wjorderId = wjorderId;
        }

        public String getUsername() {
            return username;
        }

        public void setUsername(String username) {
            this.username = username;
        }

        public int getType() {
            return type;
        }

        public void setType(int type) {
            this.type = type;
        }

        public String getGroupname() {
            return Groupname;
        }

        public void setGroupname(String Groupname) {
            this.Groupname = Groupname;
        }

        public String getActionNo() {
            return actionNo;
        }

        public void setActionNo(String actionNo) {
            this.actionNo = actionNo;
        }

        public String getActionData() {
            return actionData;
        }

        public void setActionData(String actionData) {
            this.actionData = actionData;
        }

        public String getActionTime() {
            return actionTime;
        }

        public void setActionTime(String actionTime) {
            this.actionTime = actionTime;
        }

        public String getLotteryNo() {
            return lotteryNo;
        }

        public void setLotteryNo(String lotteryNo) {
            this.lotteryNo = lotteryNo;
        }

        public String getMoney() {
            return money;
        }

        public void setMoney(String money) {
            this.money = money;
        }

        public String getBonus() {
            return bonus;
        }

        public void setBonus(String bonus) {
            this.bonus = bonus;
        }

        public int getStatus() {
            return status;
        }

        public void setStatus(int status) {
            this.status = status;
        }
    }
}
