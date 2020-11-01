package com.hgapp.betnew.data;

import java.util.List;

public class AGGameListResult {
    /**
     * status : 200
     * describe : success
     * timestamp : 20180710041157
     * data : [{"name":"水果拉霸","gameurl":"/images/aggame/FRU_ZH.png","gameid":"101"},{"name":"杰克高手","gameurl":"/images/aggame/PKBJ_ZH.png","gameid":"102"},{"name":"太空漫游","gameurl":"/images/aggame/SB01_ZH.png","gameid":"108"},{"name":"复古花园","gameurl":"/images/aggame/SB02_ZH.png","gameid":"109"},{"name":"日本武士","gameurl":"/images/aggame/SB06_ZH.png","gameid":"113"},{"name":"象棋老虎机","gameurl":"/images/aggame/SB07_ZH.png","gameid":"114"},{"name":"麻将老虎机","gameurl":"/images/aggame/SB08_ZH.png","gameid":"115"},{"name":"开心农场","gameurl":"/images/aggame/SB10_ZH.png","gameid":"117"},{"name":"夏日营地","gameurl":"/images/aggame/SB11_ZH.png","gameid":"118"},{"name":"武财神","gameurl":"/images/aggame/SB28_ZH.png","gameid":"135"},{"name":"灵猴献瑞","gameurl":"/images/aggame/SB30_ZH.png","gameid":"139"},{"name":"糖果碰碰乐","gameurl":"/images/aggame/SB33_ZH.png","gameid":"144"},{"name":"冰河世界","gameurl":"/images/aggame/SB34_ZH.png","gameid":"145"},{"name":"上海百乐门","gameurl":"/images/aggame/SB37_ZH.png","gameid":"149"},{"name":"猛龙传奇","gameurl":"/images/aggame/SB45_ZH.png","gameid":"156"},{"name":"金龙珠","gameurl":"/images/aggame/SB49_ZH.png","gameid":"160"},{"name":"XIN哥来了","gameurl":"/images/aggame/SB50_ZH.png","gameid":"161"},{"name":"龙凤呈祥","gameurl":"/images/aggame/DTAQ_ZH.png","gameid":"213"},{"name":"黄金对垒","gameurl":"/images/aggame/DTAQ_ZH.png","gameid":"215"},{"name":"街头烈战","gameurl":"/images/aggame/SX02_ZH.png","gameid":"221"},{"name":"金拉霸","gameurl":"/images/aggame/SC03_ZH.png","gameid":"802"},{"name":"五行世界","gameurl":"/images/aggame/DTA8_ZH.png","gameid":"DTGDTA8"},{"name":"梦幻森林","gameurl":"/images/aggame/DTAB_ZH.png","gameid":"DTGDTAB"},{"name":"财神到","gameurl":"/images/aggame/DTAF_ZH.png","gameid":"DTGDTAF"},{"name":"新年到","gameurl":"/images/aggame/DTAG_ZH.png","gameid":"DTGDTAG"},{"name":"龙凤呈祥","gameurl":"/images/aggame/DTAQ_ZH.png","gameid":"DTGDTAQ"},{"name":"福禄寿","gameurl":"/images/aggame/DTAT_ZH.png","gameid":"DTGDTAT"},{"name":"英雄荣耀","gameurl":"/images/aggame/DTAR_ZH.png","gameid":"DTGDTAR"},{"name":"快乐农庄","gameurl":"/images/aggame/DTB1_ZH.png","gameid":"DTGDTB1"},{"name":"封神榜","gameurl":"/images/aggame/DTAM_ZH.png","gameid":"DTGDTAM"},{"name":"摇滚之夜","gameurl":"/images/aggame/DTAZ_ZH.png","gameid":"DTGDTAZ"},{"name":"赛亚烈战","gameurl":"/images/aggame/DTA0_ZH.png","gameid":"DTGDTA0"},{"name":"欧洲轮盘","gameurl":"/images/aggame/TA1L_ZH.png","gameid":"TAITA1O"},{"name":"欧洲轮盘-低额投注","gameurl":"/images/aggame/TA1L_ZH.png","gameid":"TAITA1P"}]
     * sign : ff18ba90c410225a4865d09f89091ff8
     */

    private String status;
    private String describe;
    private String timestamp;
    private String sign;
    private List<DataBean> data;

    public String getStatus() {
        return status;
    }

    public void setStatus(String status) {
        this.status = status;
    }

    public String getDescribe() {
        return describe;
    }

    public void setDescribe(String describe) {
        this.describe = describe;
    }

    public String getTimestamp() {
        return timestamp;
    }

    public void setTimestamp(String timestamp) {
        this.timestamp = timestamp;
    }

    public String getSign() {
        return sign;
    }

    public void setSign(String sign) {
        this.sign = sign;
    }

    public List<DataBean> getData() {
        return data;
    }

    public void setData(List<DataBean> data) {
        this.data = data;
    }

    public static class DataBean {
        /**
         * name : 水果拉霸
         * gameurl : /images/aggame/FRU_ZH.png
         * gameid : 101
         */

        private String name;
        private String gameurl;
        private String gameid;

        public String getName() {
            return name;
        }

        public void setName(String name) {
            this.name = name;
        }

        public String getGameurl() {
            return gameurl;
        }

        public void setGameurl(String gameurl) {
            this.gameurl = gameurl;
        }

        public String getGameid() {
            return gameid;
        }

        public void setGameid(String gameid) {
            this.gameid = gameid;
        }
    }
}
