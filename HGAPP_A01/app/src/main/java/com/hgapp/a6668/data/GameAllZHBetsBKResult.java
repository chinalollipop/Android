package com.hgapp.a6668.data;

import java.util.List;

public class GameAllZHBetsBKResult {

    /**
     * minBet : 20
     * maxBet : 100000
     * betItem : [{"m_rate":"2.01","m_gid":"3386058","type":"PRC","showtype":"C","leag":"奥地利乙组联赛","gametype":"全场 - 让球","mb_team":"瓦滕斯[主]","sign":"0.5","tg_team":"前进斯太尔","place":"瓦滕斯"},{"m_rate":"1.93","m_gid":"3386042","type":"PRC","showtype":"C","leag":"奥地利乙组联赛","gametype":"全场 - 让球","mb_team":"林茨蓝白[主]","sign":"0 / 0.5","tg_team":"卡芬堡","place":"林茨蓝白"},{"m_rate":"1.95","m_gid":"3386050","type":"PRC","showtype":"H","leag":"奥地利乙组联赛","gametype":"全场 - 让球","mb_team":"因斯布鲁克青年队[主]","sign":"0 / 0.5","tg_team":"弗洛里茨多夫","place":"弗洛里茨多夫"}]
     */

    private String minBet;
    private String maxBet;
    private List<BetItemBean> betItem;

    public String getMinBet() {
        return minBet;
    }

    public void setMinBet(String minBet) {
        this.minBet = minBet;
    }

    public String getMaxBet() {
        return maxBet;
    }

    public void setMaxBet(String maxBet) {
        this.maxBet = maxBet;
    }

    public List<BetItemBean> getBetItem() {
        return betItem;
    }

    public void setBetItem(List<BetItemBean> betItem) {
        this.betItem = betItem;
    }

    public static class BetItemBean {
        /**
         * m_rate : 2.01
         * m_gid : 3386058
         * type : PRC
         * showtype : C
         * leag : 奥地利乙组联赛
         * gametype : 全场 - 让球
         * mb_team : 瓦滕斯[主]
         * sign : 0.5
         * tg_team : 前进斯太尔
         * place : 瓦滕斯
         */

        private String m_rate;
        private String m_gid;
        private String type;
        private String showtype;
        private String leag;
        private String gametype;
        private String mb_team;
        private String sign;
        private String tg_team;
        private String place;

        public String getM_rate() {
            return m_rate;
        }

        public void setM_rate(String m_rate) {
            this.m_rate = m_rate;
        }

        public String getM_gid() {
            return m_gid;
        }

        public void setM_gid(String m_gid) {
            this.m_gid = m_gid;
        }

        public String getType() {
            return type;
        }

        public void setType(String type) {
            this.type = type;
        }

        public String getShowtype() {
            return showtype;
        }

        public void setShowtype(String showtype) {
            this.showtype = showtype;
        }

        public String getLeag() {
            return leag;
        }

        public void setLeag(String leag) {
            this.leag = leag;
        }

        public String getGametype() {
            return gametype;
        }

        public void setGametype(String gametype) {
            this.gametype = gametype;
        }

        public String getMb_team() {
            return mb_team;
        }

        public void setMb_team(String mb_team) {
            this.mb_team = mb_team;
        }

        public String getSign() {
            return sign;
        }

        public void setSign(String sign) {
            this.sign = sign;
        }

        public String getTg_team() {
            return tg_team;
        }

        public void setTg_team(String tg_team) {
            this.tg_team = tg_team;
        }

        public String getPlace() {
            return place;
        }

        public void setPlace(String place) {
            this.place = place;
        }
    }
}
