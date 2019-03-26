package com.cfcp.a01.ui.home.cplist.bet;

import java.util.List;

public class BetParam {

    /**
     * betdata : {"gameId":70,"turnNum":"2019028","totalNums":1,"totalMoney":2,"betSrc":5,"ftime":1552138020,"betBean":[{"playId":708902,"odds":"5.6","rebate":0,"money":"2","betInfo":"鼠,牛"}]}
     */

    private BetdataBean betdata;

    public BetdataBean getBetdata() {
        return betdata;
    }

    public void setBetdata(BetdataBean betdata) {
        this.betdata = betdata;
    }

    public static class BetdataBean {
        /**
         * gameId : 70
         * turnNum : 2019028
         * totalNums : 1
         * totalMoney : 2
         * betSrc : 5
         * ftime : 1552138020
         * betBean : [{"playId":708902,"odds":"5.6","rebate":0,"money":"2","betInfo":"鼠,牛"}]
         */

        private String gameId;
        private String turnNum;
        private String totalNums;
        private String totalMoney;
        private String betSrc;
        private String ftime;
        private List<BetBeanBean> betBean;

        public String getGameId() {
            return gameId;
        }

        public void setGameId(String gameId) {
            this.gameId = gameId;
        }

        public String getTurnNum() {
            return turnNum;
        }

        public void setTurnNum(String turnNum) {
            this.turnNum = turnNum;
        }

        public String getTotalNums() {
            return totalNums;
        }

        public void setTotalNums(String totalNums) {
            this.totalNums = totalNums;
        }

        public String getTotalMoney() {
            return totalMoney;
        }

        public void setTotalMoney(String totalMoney) {
            this.totalMoney = totalMoney;
        }

        public String getBetSrc() {
            return betSrc;
        }

        public void setBetSrc(String betSrc) {
            this.betSrc = betSrc;
        }

        public String getFtime() {
            return ftime;
        }

        public void setFtime(String ftime) {
            this.ftime = ftime;
        }

        public List<BetBeanBean> getBetBean() {
            return betBean;
        }

        public void setBetBean(List<BetBeanBean> betBean) {
            this.betBean = betBean;
        }

        public static class BetBeanBean {
            /**
             * playId : 708902
             * odds : 5.6
             * rebate : 0
             * money : 2
             * betInfo : 鼠,牛
             */

            private String playId;
            private String odds;
            private String rebate;
            private String money;
            private String betInfo;

            public String getPlayId() {
                return playId;
            }

            public void setPlayId(String playId) {
                this.playId = playId;
            }

            public String getOdds() {
                return odds;
            }

            public void setOdds(String odds) {
                this.odds = odds;
            }

            public String getRebate() {
                return rebate;
            }

            public void setRebate(String rebate) {
                this.rebate = rebate;
            }

            public String getMoney() {
                return money;
            }

            public void setMoney(String money) {
                this.money = money;
            }

            public String getBetInfo() {
                return betInfo;
            }

            public void setBetInfo(String betInfo) {
                this.betInfo = betInfo;
            }
        }
    }
}
