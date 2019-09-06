package com.hfcp.hf.data;

import java.util.List;

public class BetRecordsListItemResult {

    /**
     * data : [{"id":11079360,"playId":709953,"playCateId":99,"odds":3.18,"rebate":0,"addTime":"2019-03-27 17:19:41","turnNum":"2019036","gameId":70,"status":0,"orderNo":"201903271719414d1lJFYK7Z","openTime":"2019-03-28 21:35:00","betInfo":"4, 5","money":1,"resultMoney":2.18,"multiple":1,"detail":"连肖连尾 - 二连尾 4, 5@3.18"},{"id":11079359,"playId":709952,"playCateId":99,"odds":3.18,"rebate":0,"addTime":"2019-03-27 17:19:41","turnNum":"2019036","gameId":70,"status":0,"orderNo":"20190327171941DO9NbCtWz7","openTime":"2019-03-28 21:35:00","betInfo":"3, 5","money":1,"resultMoney":2.18,"multiple":1,"detail":"连肖连尾 - 二连尾 3, 5@3.18"}]
     * totalCount : 100
     * otherData : {"totalResultMoney":7169.68,"totalBetMoney":113}
     */

    private int totalCount;
    private OtherDataBean otherData;
    private List<DataBean> data;

    public int getTotalCount() {
        return totalCount;
    }

    public void setTotalCount(int totalCount) {
        this.totalCount = totalCount;
    }

    public OtherDataBean getOtherData() {
        return otherData;
    }

    public void setOtherData(OtherDataBean otherData) {
        this.otherData = otherData;
    }

    public List<DataBean> getData() {
        return data;
    }

    public void setData(List<DataBean> data) {
        this.data = data;
    }

    public static class OtherDataBean {
        /**
         * totalResultMoney : 7169.68
         * totalBetMoney : 113
         */

        private double totalResultMoney;
        private int totalBetMoney;

        public double getTotalResultMoney() {
            return totalResultMoney;
        }

        public void setTotalResultMoney(double totalResultMoney) {
            this.totalResultMoney = totalResultMoney;
        }

        public int getTotalBetMoney() {
            return totalBetMoney;
        }

        public void setTotalBetMoney(int totalBetMoney) {
            this.totalBetMoney = totalBetMoney;
        }
    }

    public static class DataBean {
        /**
         * id : 11079360
         * playId : 709953
         * playCateId : 99
         * odds : 3.18
         * rebate : 0
         * addTime : 2019-03-27 17:19:41
         * turnNum : 2019036
         * gameId : 70
         * status : 0
         * orderNo : 201903271719414d1lJFYK7Z
         * openTime : 2019-03-28 21:35:00
         * betInfo : 4, 5
         * money : 1
         * resultMoney : 2.18
         * multiple : 1
         * detail : 连肖连尾 - 二连尾 4, 5@3.18
         */

        private int id;
        private int playId;
        private int playCateId;
        private double odds;
        private int rebate;
        private String addTime;
        private String turnNum;
        private String gameId;
        private int status;
        private String orderNo;
        private String openTime;
        private String betInfo;
        private String money;
        private String resultMoney;
        private int multiple;
        private String detail;

        public int getId() {
            return id;
        }

        public void setId(int id) {
            this.id = id;
        }

        public int getPlayId() {
            return playId;
        }

        public void setPlayId(int playId) {
            this.playId = playId;
        }

        public int getPlayCateId() {
            return playCateId;
        }

        public void setPlayCateId(int playCateId) {
            this.playCateId = playCateId;
        }

        public double getOdds() {
            return odds;
        }

        public void setOdds(double odds) {
            this.odds = odds;
        }

        public int getRebate() {
            return rebate;
        }

        public void setRebate(int rebate) {
            this.rebate = rebate;
        }

        public String getAddTime() {
            return addTime;
        }

        public void setAddTime(String addTime) {
            this.addTime = addTime;
        }

        public String getTurnNum() {
            return turnNum;
        }

        public void setTurnNum(String turnNum) {
            this.turnNum = turnNum;
        }

        public String getGameId() {
            return gameId;
        }

        public void setGameId(String gameId) {
            this.gameId = gameId;
        }

        public int getStatus() {
            return status;
        }

        public void setStatus(int status) {
            this.status = status;
        }

        public String getOrderNo() {
            return orderNo;
        }

        public void setOrderNo(String orderNo) {
            this.orderNo = orderNo;
        }

        public String getOpenTime() {
            return openTime;
        }

        public void setOpenTime(String openTime) {
            this.openTime = openTime;
        }

        public String getBetInfo() {
            return betInfo;
        }

        public void setBetInfo(String betInfo) {
            this.betInfo = betInfo;
        }

        public String getMoney() {
            return money;
        }

        public void setMoney(String money) {
            this.money = money;
        }

        public String getResultMoney() {
            return resultMoney;
        }

        public void setResultMoney(String resultMoney) {
            this.resultMoney = resultMoney;
        }

        public int getMultiple() {
            return multiple;
        }

        public void setMultiple(int multiple) {
            this.multiple = multiple;
        }

        public String getDetail() {
            return detail;
        }

        public void setDetail(String detail) {
            this.detail = detail;
        }
    }
}
