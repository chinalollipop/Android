package com.gmcp.gm.data;

import java.util.List;

public class BetDragonResult {

    private long serverTime;
    private List<DataBean> list;

    public long getServerTime() {
        return serverTime;
    }

    public void setServerTime(long serverTime) {
        this.serverTime = serverTime;
    }

    public List<DataBean> getData() {
        return list;
    }

    public void setData(List<DataBean> list) {
        this.list = list;
    }

    public static class DataBean {
        /**
         * currIssue : 20190406030
         * kjdTime : 60
         * endtime : 1554530940
         * lotteryTime : 1554531000
         * lotteryName : 欢乐生肖
         * lotteryType : cqssc
         * playId : 1212
         * playName : 单
         * gameId : 1
         * playGroupId : 2
         * playCateName : 第一球
         * count : 10
         * aDXDSPlayed : [{"id":1212,"name":"单","type":1,"odds":1.94,"rebate":0},{"id":1213,"name":"双","type":1,"odds":1.94,"rebate":0}]
         */

        private String currIssue;
        private int kjdTime;
        private long endtime;
        private long lotteryTime;
        private String lotteryName;
        private String lotteryType;
        private int playId;
        private String playName;
        private String gameId;
        private int playGroupId;
        private String playCateName;
        private int count;
        private int checkedId;

        public int getCheckedId() {
            return checkedId;
        }

        public void setCheckedId(int checkedId) {
            this.checkedId = checkedId;
        }

        private List<ADXDSPlayedBean> aDXDSPlayed;

        public String getCurrIssue() {
            return currIssue;
        }

        public void setCurrIssue(String currIssue) {
            this.currIssue = currIssue;
        }

        public int getKjdTime() {
            return kjdTime;
        }

        public void setKjdTime(int kjdTime) {
            this.kjdTime = kjdTime;
        }

        public long getEndtime() {
            return endtime;
        }

        public void setEndtime(long endtime) {
            this.endtime = endtime;
        }

        public long getLotteryTime() {
            return lotteryTime;
        }

        public void setLotteryTime(long lotteryTime) {
            this.lotteryTime = lotteryTime;
        }

        public String getLotteryName() {
            return lotteryName;
        }

        public void setLotteryName(String lotteryName) {
            this.lotteryName = lotteryName;
        }

        public String getLotteryType() {
            return lotteryType;
        }

        public void setLotteryType(String lotteryType) {
            this.lotteryType = lotteryType;
        }

        public int getPlayId() {
            return playId;
        }

        public void setPlayId(int playId) {
            this.playId = playId;
        }

        public String getPlayName() {
            return playName;
        }

        public void setPlayName(String playName) {
            this.playName = playName;
        }

        public String getGameId() {
            return gameId;
        }

        public void setGameId(String gameId) {
            this.gameId = gameId;
        }

        public int getPlayGroupId() {
            return playGroupId;
        }

        public void setPlayGroupId(int playGroupId) {
            this.playGroupId = playGroupId;
        }

        public String getPlayCateName() {
            return playCateName;
        }

        public void setPlayCateName(String playCateName) {
            this.playCateName = playCateName;
        }

        public int getCount() {
            return count;
        }

        public void setCount(int count) {
            this.count = count;
        }

        public List<ADXDSPlayedBean> getADXDSPlayed() {
            return aDXDSPlayed;
        }

        public void setADXDSPlayed(List<ADXDSPlayedBean> aDXDSPlayed) {
            this.aDXDSPlayed = aDXDSPlayed;
        }

        public static class ADXDSPlayedBean {
            /**
             * id : 1212
             * name : 单
             * type : 1
             * odds : 1.94
             * rebate : 0
             */

            private int id;
            private String name;
            private int type;
            private String odds;
            private int rebate;

            public int getId() {
                return id;
            }

            public void setId(int id) {
                this.id = id;
            }

            public String getName() {
                return name;
            }

            public void setName(String name) {
                this.name = name;
            }

            public int getType() {
                return type;
            }

            public void setType(int type) {
                this.type = type;
            }

            public String getOdds() {
                return odds;
            }

            public void setOdds(String odds) {
                this.odds = odds;
            }

            public int getRebate() {
                return rebate;
            }

            public void setRebate(int rebate) {
                this.rebate = rebate;
            }
        }
    }
}
