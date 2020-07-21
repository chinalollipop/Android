package com.hgapp.bet365.data;

import java.util.List;

public class XCC {

    /**
     * betBeans : [{"id":117,"handicapID":"90955","partID":1,"betAmount":"25","round":"0","handicapName":"比赛获胜方","handicapType":1,"partName":"[a]","partOdds":1010,"active":1},{"id":120,"handicapID":"91086","partID":1,"betAmount":"30","round":"0","handicapName":"比赛获胜方","handicapType":1,"partName":"[a]","partOdds":1590,"active":1}]
     * randomNum : Hmwq2AJswlnNMA4FhT8vyLJIfkidxUFe
     */

    private String randomNum;
    private List<BetBeansBean> betBeans;

    public String getRandomNum() {
        return randomNum;
    }

    public void setRandomNum(String randomNum) {
        this.randomNum = randomNum;
    }

    public List<BetBeansBean> getBetBeans() {
        return betBeans;
    }

    public void setBetBeans(List<BetBeansBean> betBeans) {
        this.betBeans = betBeans;
    }

    public static class BetBeansBean {
        /**
         * id : 117
         * handicapID : 90955
         * partID : 1
         * betAmount : 25
         * round : 0
         * handicapName : 比赛获胜方
         * handicapType : 1
         * partName : [a]
         * partOdds : 1010
         * active : 1
         */

        private int id;
        private String handicapID;
        private int partID;
        private String betAmount;
        private String round;
        private String handicapName;
        private int handicapType;
        private String partName;
        private int partOdds;
        private int active;

        public int getId() {
            return id;
        }

        public void setId(int id) {
            this.id = id;
        }

        public String getHandicapID() {
            return handicapID;
        }

        public void setHandicapID(String handicapID) {
            this.handicapID = handicapID;
        }

        public int getPartID() {
            return partID;
        }

        public void setPartID(int partID) {
            this.partID = partID;
        }

        public String getBetAmount() {
            return betAmount;
        }

        public void setBetAmount(String betAmount) {
            this.betAmount = betAmount;
        }

        public String getRound() {
            return round;
        }

        public void setRound(String round) {
            this.round = round;
        }

        public String getHandicapName() {
            return handicapName;
        }

        public void setHandicapName(String handicapName) {
            this.handicapName = handicapName;
        }

        public int getHandicapType() {
            return handicapType;
        }

        public void setHandicapType(int handicapType) {
            this.handicapType = handicapType;
        }

        public String getPartName() {
            return partName;
        }

        public void setPartName(String partName) {
            this.partName = partName;
        }

        public int getPartOdds() {
            return partOdds;
        }

        public void setPartOdds(int partOdds) {
            this.partOdds = partOdds;
        }

        public int getActive() {
            return active;
        }

        public void setActive(int active) {
            this.active = active;
        }
    }
}
