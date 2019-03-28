package com.cfcp.a01.data;

import java.util.List;

public class CPBetNowResult {


    private List<ListBean> list;

    public List<ListBean> getList() {
        return list;
    }

    public void setList(List<ListBean> list) {
        this.list = list;
    }

    public static class ListBean {
        /**
         * gameId : 61
         * totalNums : 1
         * totalMoney : 1
         */

        private String gameId;
        private String totalNums;
        private String totalMoney;

        public String getGameId() {
            return gameId;
        }

        public void setGameId(String gameId) {
            this.gameId = gameId;
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
    }
}
